@extends('layouts.master')

@section('konten')
    <style>
        .main-content {
            margin-left: 120px;
            padding-top: 56px;
            min-height: 100vh;
        }
        .fixed-title {
            position: fixed;
            left: 120px;
            top: 50px;
            right: 0;
            z-index: 900;
            padding: 16px 0 8px 0;
            text-align: center;
            font-weight: bold;
            font-size: 2rem;
        }
        .table-container {
            background: #d3d3c6;
            border-radius: 16px;
            padding: 32px 24px;
            margin: 0 auto;
            max-width: 1100px;
            position: fixed;
            left: 120px;
            right: 0;
            top: 120px; /* 56px topbar + ~48px title */
            bottom: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .search-box {
            margin-bottom: 16px;
            max-width: 300px;
        }
        .scroll-table-wrapper {
            flex: 1 1 auto;
            overflow-y: auto;
            min-height: 0;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
            border: 1px solid #d3d3c6;
            background: #fff;
            color: #222;
        }
        .table th {
            background: #fdf030;
            font-weight: 600;
        }
        .table {
            margin-bottom: 0;
        }
    </style>
    <div class="main-content">
        <div class="fixed-title">DATA KATA</div>
        <div class="table-container">
            <form method="GET" action="{{ url('/beranda') }}" class="input-group mb-3 search-box">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="search">
                <button class="btn btn-danger" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            @if(session('success'))
                <div class="mb-2"> <div class="alert alert-success">{{ session('success') }}</div> </div>
            @endif
            <div class="scroll-table-wrapper">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal Entri</th>
                                <th>ID kata</th>
                                <th>Sub ID</th>
                                <th>Kata</th>
                                <th>Arti</th>
                                <th>Cara Baca</th>
                                <th>Ket</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($katas as $k)
                                <tr>
                                    <td>{{ $k->created_at ? $k->created_at->format('Y-m-d') : '' }}</td>
                                    <td>{{ $k->id_kata }}</td>
                                    <td>{{ $k->sub_id }}</td>
                                    <td>{{ $k->kata }}</td>
                                    <td>{{ $k->arti }}</td>
                                    <td>{{ $k->cara_baca }}</td>
                                    <td>{{ $k->keterangan }}</td>
                                    <td>{{ strtoupper($k->status_validasi) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">Belum ada kata yang dientri.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                {{ $katas->links() }}
            </div>
        </div>
    </div>
@endsection
