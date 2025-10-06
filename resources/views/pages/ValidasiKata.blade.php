@extends('layouts.master')

@section('konten')
    <style>
        .main-content {
            margin-left: 120px;
            padding-top: 20px;
            min-height: 0;
        }
        .fixed-title {
            text-align: center;
            font-weight: bold;
            font-size: 1.8rem;
            margin-top: 18px;
            margin-bottom: 10px;
        }
        .table-container {
            background: #d3d3c6;
            border-radius: 16px;
            padding: 32px 24px;
            margin: 0 auto;
            max-width: 1100px;
        }
        .search-box {
            margin-bottom: 16px;
            max-width: 300px;
        }
        .scroll-table-wrapper {
            max-height: 350px;
            overflow-y: auto;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
            border: 1px solid #888;
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
        .btn-detail{
            border-radius: 10px;
            font-size: 0.95rem;
            padding: 2px 16px;
            background: #fffc52;
            color: #222;
            border: 1px solid #888;
        }
        .btn-validasi {
            border-radius: 10px;
            font-size: 0.95rem;
            padding: 2px 16px;
            background: #db0303;
            color: #ffffff;
            border: 1px solid #888;
        }
        .btn-detail {
            margin-right: 4px;
        }
    </style>

    <div class="main-content">
        <div class="fixed-title">VALIDASI KATA</div>
        <div class="table-container">
            <form class="input-group mb-3 search-box" method="GET" action="{{ url('/ValidasiKata') }}">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="search">
                <button class="btn btn-danger" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <div class="scroll-table-wrapper">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal Entri</th>
                                <th>ID kata</th>
                                <th>Sub ID</th>
                                <th>Kata</th>
                                <th>Status</th>
                                <th>Lihat Detail</th>
                                <th>Validasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($katas as $kata)
                                <tr>
                                    <td>{{ $kata->created_at ? $kata->created_at->format('d-m-Y') : '' }}</td>
                                    <td>{{ $kata->id_kata }}</td>
                                    <td>{{ $kata->sub_id }}</td>
                                    <td>{{ $kata->kata }}</td>
                                    <td><span class="fw-bold">{{ ucfirst($kata->status_validasi) }}</span></td>
                                    <td>
                                        <a href="{{ url('/DetailKata/'.$kata->id_kata) }}" class="btn btn-detail">Detail</a>
                                    </td>
                                    <td>
                                        @if($kata->status_validasi !== 'valid')
                                            <form method="POST" action="{{ route('kata.validate', $kata->id_kata) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-validasi">Validasi</button>
                                            </form>
                                        @else
                                            <button class="btn btn-validasi" disabled>Valid</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">Tidak ada kata untuk divalidasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection