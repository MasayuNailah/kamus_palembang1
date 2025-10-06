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
            font-size: 2rem;
            margin-top: 12px;
            margin-bottom: 10px;
        }
        .form-container {
            background: #d3d3c6;
            border-radius: 8px;
            max-width: 700px;
            margin: 0 auto;
            padding: 40px 32px 32px 32px;
            border: 1px solid #85857f;
        }
        .form-label {
            font-weight: 500;
            color: #222;
        }
        .form-control, .form-select {
            border-radius: 4px;
            background: #fff;
            color: #222;
        }
        .input-group-text {
            background: #fff;
            border: none;
        }
        .btn-submit {
            background: #ffee00;
            border-color: #d1c300;
            color: #000000;
            font-weight: bold;
            border-radius: 10px;
            width: 200px;
            margin: 32px auto 0 auto;
            display: block;
        }
    </style>


    <div class="main-content">
        <div class="fixed-title">DETAIL KATA</div>
        <div class="form-container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ url('/DetailKata/' . ($kata->id_kata ?? '')) }}">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Entri</label>
                        <input type="text" class="form-control" value="{{ optional($kata->created_at)->format('Y-m-d H:i') }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ID Kata</label>
                        <input type="text" class="form-control" value="{{ $kata->id_kata ?? '' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sub ID</label>
                        <input type="text" class="form-control" value="{{ $kata->sub_id ?? '' }}" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kata</label>
                    <input type="text" name="kata" class="form-control" value="{{ $kata->kata ?? '' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Arti kata</label>
                    <input type="text" name="arti" class="form-control" value="{{ $kata->arti ?? '' }}">
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Cara baca</label>
                        <input type="text" name="cara_baca" class="form-control" value="{{ $kata->cara_baca ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Keterangan</label>
                        <select name="keterangan" class="form-select">
                            <option value="" {{ empty($kata->keterangan) ? 'selected' : '' }}>-- Pilih Keterangan --</option>
                            <option value="Noun" {{ (isset($kata->keterangan) && $kata->keterangan=='Noun')? 'selected' : '' }}>Noun</option>
                            <option value="Verb" {{ (isset($kata->keterangan) && $kata->keterangan=='Verb')? 'selected' : '' }}>Verb</option>
                            <option value="Adjective" {{ (isset($kata->keterangan) && $kata->keterangan=='Adjective')? 'selected' : '' }}>Adjective</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status_validasi" class="form-select">
                            <option value="draft" {{ (isset($kata->status_validasi) && $kata->status_validasi=='draft')? 'selected' : '' }}>Draft</option>
                            <option value="valid" {{ (isset($kata->status_validasi) && $kata->status_validasi=='valid')? 'selected' : '' }}>Valid</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Divalidasi</label>
                        <input type="text" class="form-control" value="{{ optional($kata->updated_at)->format('Y-m-d H:i') }}" readonly>
                    </div>
                </div>
                <button type="submit" class="btn btn-submit">SUBMIT</button>
            </form>
        </div>
    </div>
@endsection
