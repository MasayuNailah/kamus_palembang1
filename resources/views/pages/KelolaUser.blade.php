@extends('layouts.master')

@section('konten')
    <style>
        .main-content {
            margin-left: 114px;
            padding-top: 24px;
            min-height: 0;
        }
        .card {
            background-color: #d3d3c6;
            border-radius: 10px;
            padding-left: 50px;
        }
        .table-container {
            overflow-x: auto;
        }
    </style>

    <div class="main-content">    
        <h1 class="text-center mb-4">FORM KELOLA USER</h1>

        <!-- Content Card -->
        <div class="card p-4 shadow-sm">
            <div class="d-flex justify-content-between mb-3">
                <form method="GET" action="{{ url('/KelolaUser') }}" class="input-group" style="max-width: 300px;">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input name="q" value="{{ request('q') }}" type="text" class="form-control" placeholder="search">
                </form>
                <a href="{{ url('/TambahUser') }}" class="btn btn-danger">TAMBAH USER</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->nama_user ?? $user->name ?? '' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                @if(!empty($user->foto))
                                    <img src="{{ asset('storage/foto_users/' . $user->foto) }}" alt="Foto" style="max-width:60px; border-radius:6px;" />
                                @else
                                    <i class="fas fa-image"></i>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('/EditUser/'.$user->id_user) }}" class="btn btn-warning btn-sm">EDIT</a>
                                <form method="POST" action="{{ url('/EditUser/'.$user->id_user) }}" style="display:inline-block;" onsubmit="return confirm('Hapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">HAPUS</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada user</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
@endsection

