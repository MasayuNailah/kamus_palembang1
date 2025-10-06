<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $query = DB::table('users');
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_user', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('username', 'like', "%{$q}%");
            });
        }
        $users = $query->get();
        return view('pages.KelolaUser', compact('users'));
    }

    public function create()
    {
        return view('pages.TambahUser');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'nullable|string|unique:users,username',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto_users', 'public');
            $data['foto'] = basename($path);
        }

        $dataToInsert = [
            'nama_user' => $data['nama_user'],
            'email' => $data['email'],
            'username' => $data['username'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'foto' => $data['foto'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('users')->insert($dataToInsert);

        return redirect('/KelolaUser')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id_user)
    {
        $user = DB::table('users')->where('id_user', $id_user)->first();
        if (! $user) abort(404);
        return view('pages.EditUser', compact('user'));
    }

    public function update(Request $request, $id_user)
    {
        $user = DB::table('users')->where('id_user', $id_user)->first();
        if (! $user) abort(404);

        $data = $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id_user.',id_user',
            'username' => 'nullable|string|unique:users,username,'.$id_user.',id_user',
            'password' => 'nullable|string|min:6',
            'role' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto_users', 'public');
            $data['foto'] = basename($path);
            // delete old foto if exists
            if (!empty($user->foto) && Storage::disk('public')->exists('foto_users/'.$user->foto)) {
                Storage::disk('public')->delete('foto_users/'.$user->foto);
            }
        }

        $update = [
            'nama_user' => $data['nama_user'],
            'email' => $data['email'],
            'username' => $data['username'] ?? null,
            'role' => $data['role'],
            'updated_at' => now(),
        ];

        if (!empty($data['foto'])) $update['foto'] = $data['foto'];
        if (!empty($data['password'])) $update['password'] = Hash::make($data['password']);

        DB::table('users')->where('id_user', $id_user)->update($update);

        return redirect('/KelolaUser')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id_user)
    {
        $user = DB::table('users')->where('id_user', $id_user)->first();
        if (! $user) abort(404);
        if (!empty($user->foto) && Storage::disk('public')->exists('foto_users/'.$user->foto)) {
            Storage::disk('public')->delete('foto_users/'.$user->foto);
        }
        DB::table('users')->where('id_user', $id_user)->delete();
        return redirect('/KelolaUser')->with('success', 'User berhasil dihapus');
    }
}
