<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Kata;
use App\Models\TransaksiValidasi;
use Illuminate\Support\Facades\DB;

class KataController extends Controller
{
    public function getKata()
    {
        // List kata entries for beranda with optional search
        $query = Kata::query();
        // Optional search parameter `q`
        request()->whenHas('q', function($q) use ($query) {
            $query->where('kata', 'like', "%{$q}%")
                ->orWhere('id_kata', 'like', "%{$q}%")
                ->orWhere('sub_id', 'like', "%{$q}%");
        });

        $katas = $query->orderBy('created_at', 'desc')->paginate(25)->withQueryString();
        return view('pages.beranda', compact('katas'));
    }

    public function tambahKata()
    {
        // Provide existing kata options for sub_id dropdown (searchable)
        $kataList = Kata::orderBy('kata')->get(['id_kata','kata']);
        return view('pages.EntriKata', compact('kataList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_kata' => 'required|string',
            'sub_id' => 'nullable|string',
            'kata' => 'required|string|max:255',
            'arti' => 'required|string',
            'cara_baca' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:100',
        ]);
        // Generate sequential id_kata like K0001, K0002, ...
        $existingIds = Kata::pluck('id_kata')->toArray();
        $maxNum = 0;
        foreach ($existingIds as $eid) {
            // extract numeric part after leading non-digits
            $num = intval(preg_replace('/[^0-9]/', '', $eid));
            if ($num > $maxNum) $maxNum = $num;
        }
        $next = $maxNum + 1;
        $id_kata = 'K' . str_pad($next, 4, '0', STR_PAD_LEFT);

        // Use a DB transaction so kata creation and its log are consistent
        DB::transaction(function() use ($data, $id_kata) {
            $kata = new Kata();
            $kata->id_kata = $id_kata;

            // If kata is 'Kata Dasar', sub_id should follow same id_kata
            if (isset($data['jenis_kata']) && strtolower($data['jenis_kata']) === strtolower('Kata Dasar')) {
                $kata->sub_id = $id_kata;
            } else {
                // For Kata Turunan, sub_id must be provided and must exist
                $kata->sub_id = $data['sub_id'] ?? null;
            }
            $kata->kata = $data['kata'];
            $kata->arti = $data['arti'];
            $kata->cara_baca = $data['cara_baca'] ?? null;
            $kata->keterangan = $data['keterangan'] ?? null;
            $kata->id_kontributor = Auth::check() ? Auth::id() : null;
            $kata->status_validasi = 'draft';
            $kata->save();

            // Log the contribution in transaksi_validasi using the explicit generated id
            TransaksiValidasi::create([
                'id_objek' => (string) $id_kata,
                'tipe_objek' => 'kata',
                'id_kontributor' => $kata->id_kontributor,
                'tgl_kontribusi' => $kata->created_at ?? now(),
            ]);
        });

        return redirect('/EntriKata')->with('success', 'Kata berhasil disimpan.');
    }

    // Show kata entries that need validation (status draft)
    public function validasiList(Request $request)
    {
        $query = Kata::query();
        // Optional search
        if ($q = $request->get('q')) {
            $query->where('kata', 'like', "%{$q}%")
                  ->orWhere('id_kata', 'like', "%{$q}%")
                  ->orWhere('sub_id', 'like', "%{$q}%");
        }
        $katas = $query->orderBy('created_at','desc')->paginate(25);
        return view('pages.ValidasiKata', compact('katas'));
    }

    // Mark kata as valid and record validator
    public function validateKata(Request $request, $id_kata)
    {
        DB::transaction(function() use ($id_kata) {
            $kata = Kata::findOrFail($id_kata);
            $kata->status_validasi = 'valid';
            $kata->id_validator = Auth::check() ? Auth::id() : null;
            $kata->save();

            // Log the validation event in transaksi_validasi (find the kontribusi record if exists)
            $trans = TransaksiValidasi::where('id_objek', $kata->id_kata)->where('tipe_objek','kata')->first();
            if ($trans) {
                $trans->id_validator = $kata->id_validator;
                $trans->tgl_validasi = now();
                $trans->save();
            } else {
                TransaksiValidasi::create([
                    'id_objek' => (string) $kata->id_kata,
                    'tipe_objek' => 'kata',
                    'id_validator' => $kata->id_validator,
                    'tgl_validasi' => now(),
                ]);
            }
        });
        return redirect()->back()->with('success', 'Kata telah divalidasi.');
    }

    /**
     * Display the specified kata for detail/edit.
     */
    public function show($id_kata)
    {
        $kata = Kata::findOrFail($id_kata);
        return view('pages.DetailKata', compact('kata'));
    }

    /**
     * Update the specified kata.
     */
    public function update(Request $request, $id_kata)
    {
        $kata = Kata::findOrFail($id_kata);

        $validated = $request->validate([
            'kata' => 'required|string|max:255',
            'arti' => 'nullable|string',
            'cara_baca' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'status_validasi' => 'nullable|in:draft,valid',
        ]);

        $kata->kata = $validated['kata'];
        $kata->arti = $validated['arti'] ?? $kata->arti;
        $kata->cara_baca = $validated['cara_baca'] ?? $kata->cara_baca;
        $kata->keterangan = $validated['keterangan'] ?? $kata->keterangan;
        if (isset($validated['status_validasi'])) {
            $kata->status_validasi = $validated['status_validasi'];
        }

        $kata->save();

        // record update in transaksi_validasi (best-effort)
        try {
            TransaksiValidasi::create([
                'id_objek' => (string) $kata->id_kata,
                'tipe_objek' => 'kata',
                'id_kontributor' => $kata->id_kontributor,
                'id_validator' => Auth::check() ? Auth::id() : null,
                'aksi' => 'update',
                'keterangan' => 'Diperbarui melalui halaman detail',
                'tgl_validasi' => $kata->status_validasi === 'valid' ? now() : null,
            ]);
        } catch (\Exception $e) {
            // ignore logging errors
        }

        // After updating, send the user back to the validation list
        return redirect('/ValidasiKata')->with('success', 'Kata berhasil diperbarui.');
    }
}
