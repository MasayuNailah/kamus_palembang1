<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kalimat;
use App\Models\TransaksiValidasi;
use Illuminate\Support\Facades\DB;

class KalimatController extends Controller
{
    public function tambahKalimat()
    {
        // provide kata list for sub_id selection
        $kataList = \App\Models\Kata::orderBy('kata')->get(['id_kata','kata']);
        return view('pages.EntriKalimat', compact('kataList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // ensure sub_id (if provided) points to an existing kata.sub_id
            'sub_id' => 'nullable|string|exists:tb_kata,sub_id',
            'kalimat' => 'required|string',
            'arti' => 'required|string',
        ]);

        // Generate id_kalimat sequentially similar to kata (KL0001...)
        $existing = Kalimat::pluck('id_kalimat')->toArray();
        $max = 0;
        foreach ($existing as $e) {
            $num = intval(preg_replace('/[^0-9]/','',$e));
            if ($num > $max) $max = $num;
        }
        $next = $max + 1;
        $id_kalimat = 'KL' . str_pad($next,4,'0',STR_PAD_LEFT);

        try {
            DB::transaction(function() use ($id_kalimat, $data) {
                $kal = new Kalimat();
                $kal->id_kalimat = $id_kalimat;
                $kal->sub_id = $data['sub_id'] ?? null;
                $kal->kalimat = $data['kalimat'];
                $kal->arti = $data['arti'];
                $kal->status_validasi = 'draft';
                $kal->id_kontributor = Auth::check() ? Auth::id() : null;
                $kal->save();

                TransaksiValidasi::create([
                    'id_objek' => (string) $id_kalimat,
                    'tipe_objek' => 'kalimat',
                    'id_kontributor' => $kal->id_kontributor,
                    'tgl_kontribusi' => $kal->created_at ?? now(),
                ]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Log full exception and input for debugging
            \Log::error('Kalimat store failed', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
                'user_id' => Auth::id(),
            ]);

            // If it's a foreign key constraint error, give a helpful message
            if (strpos($e->getMessage(), '1452') !== false || stripos($e->getMessage(), 'foreign key') !== false) {
                $msg = 'Pilihan Sub ID tidak valid — pastikan kata dasar (sub_id) ada di daftar.';
                if (config('app.debug')) {
                    $msg .= ' Debug: ' . $e->getMessage();
                }
                return redirect()->back()->withInput()->withErrors(['sub_id' => $msg]);
            }

            // For other DB errors, expose detail only in debug mode
            if (config('app.debug')) {
                return redirect()->back()->withInput()->withErrors(['db' => 'Database error: ' . $e->getMessage()]);
            }

            return redirect()->back()->withInput()->withErrors(['db' => 'Terjadi kesalahan saat menyimpan data.']);
        }

        return redirect('/EntriKalimat')->with('success','Kalimat berhasil disimpan.');
    }

    public function validasiList(Request $request)
    {
        $query = Kalimat::query();
        if ($q = $request->get('q')) {
            $query->where('kalimat','like','%'.$q.'%')->orWhere('id_kalimat','like','%'.$q.'%')->orWhere('sub_id','like','%'.$q.'%');
        }
        $kals = $query->orderBy('created_at','desc')->paginate(25);
        return view('pages.ValidasiKalimat', compact('kals'));
    }

    public function validateKalimat(Request $request, $id_kalimat)
    {
        $kal = Kalimat::findOrFail($id_kalimat);
        $kal->status_validasi = 'valid';
        $kal->id_validator = Auth::check() ? Auth::id() : null;
        $kal->save();

        DB::transaction(function() use ($kal) {
            $trans = TransaksiValidasi::where('id_objek',$kal->id_kalimat)->where('tipe_objek','kalimat')->first();
            if ($trans) {
                $trans->id_validator = $kal->id_validator;
                $trans->tgl_validasi = now();
                $trans->save();
            } else {
                TransaksiValidasi::create([
                    'id_objek' => (string) $kal->id_kalimat,
                    'tipe_objek' => 'kalimat',
                    'id_validator' => $kal->id_validator,
                    'tgl_validasi' => now(),
                ]);
            }
        });

        return redirect()->back()->with('success','Kalimat telah divalidasi.');
    }

    /**
     * Show a kalimat detail for viewing/editing.
     */
    public function show($id_kalimat)
    {
        $kalimat = Kalimat::findOrFail($id_kalimat);
        return view('pages.DetailKalimat', compact('kalimat'));
    }

    /**
     * Update kalimat.
     */
    public function update(Request $request, $id_kalimat)
    {
        $kal = Kalimat::findOrFail($id_kalimat);

        $validated = $request->validate([
            'kalimat' => 'required|string',
            'arti' => 'nullable|string',
            'sub_id' => 'nullable|string|exists:tb_kata,sub_id',
            'status_validasi' => 'nullable|in:draft,valid',
        ]);

        $kal->kalimat = $validated['kalimat'];
        $kal->arti = $validated['arti'] ?? $kal->arti;
        $kal->sub_id = $validated['sub_id'] ?? $kal->sub_id;
        if (isset($validated['status_validasi'])) $kal->status_validasi = $validated['status_validasi'];
        try {
            $kal->save();
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Kalimat update failed', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'input' => $request->all(),
                'user_id' => Auth::id(),
            ]);

            if (strpos($e->getMessage(), '1452') !== false || stripos($e->getMessage(), 'foreign key') !== false) {
                $msg = 'Pilihan Sub ID tidak valid saat update — pastikan kata dasar (sub_id) ada di daftar.';
                if (config('app.debug')) $msg .= ' Debug: ' . $e->getMessage();
                return redirect()->back()->withInput()->withErrors(['sub_id' => $msg]);
            }

            if (config('app.debug')) {
                return redirect()->back()->withInput()->withErrors(['db' => 'Database error: ' . $e->getMessage()]);
            }

            return redirect()->back()->withInput()->withErrors(['db' => 'Terjadi kesalahan saat memperbarui data.']);
        }

        // best-effort log
        try {
            TransaksiValidasi::create([
                'id_objek' => (string) $kal->id_kalimat,
                'tipe_objek' => 'kalimat',
                'id_kontributor' => $kal->id_kontributor,
                'id_validator' => Auth::check() ? Auth::id() : null,
                'aksi' => 'update',
                'keterangan' => 'Diperbarui melalui halaman detail',
                'tgl_validasi' => $kal->status_validasi === 'valid' ? now() : null,
            ]);
        } catch (\Exception $e) {
            // ignore
        }

        return redirect(url('/ValidasiKalimat'))->with('success','Kalimat berhasil diperbarui.');
    }
}
