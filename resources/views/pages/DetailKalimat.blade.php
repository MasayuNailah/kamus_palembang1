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
        /* make selector more specific and use !important to override Bootstrap if needed */
        .btn.btn-submit {
            background: #ffee00 !important;
            border-color: #d1c300 !important;
            color: #000000 !important;
            font-weight: bold;
            border-radius: 10px;
            width: 200px;
            margin: 32px auto 0 auto;
            display: block;
        }
    </style>

    <div class="main-content">
        <div class="fixed-title">DETAIL KALIMAT</div>
        <div class="form-container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ url('/DetailKalimat/' . ($kalimat->id_kalimat ?? '')) }}">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Entri</label>
                        <input type="text" class="form-control" value="{{ optional($kalimat->created_at)->format('Y-m-d H:i') }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ID Kalimat</label>
                        <input type="text" class="form-control" value="{{ $kalimat->id_kalimat ?? '' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sub ID</label>
                        <input type="text" name="sub_id" class="form-control" value="{{ $kalimat->sub_id ?? '' }}" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kalimat</label>
                    <textarea name="kalimat" class="form-control" rows="3">{{ $kalimat->kalimat ?? '' }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Arti kalimat</label>
                    <textarea name="arti" class="form-control" rows="3">{{ $kalimat->arti ?? '' }}</textarea>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status_validasi" class="form-select">
                            <option value="draft" {{ (isset($kalimat->status_validasi) && $kalimat->status_validasi=='draft')? 'selected':'' }}>Draft</option>
                            <option value="valid" {{ (isset($kalimat->status_validasi) && $kalimat->status_validasi=='valid')? 'selected':'' }}>Valid</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Divalidasi</label>
                        <input type="text" class="form-control" value="{{ optional($kalimat->updated_at)->format('Y-m-d H:i') }}" readonly>
                    </div>
                </div>
                <button type="submit" class="btn btn-submit">SUBMIT</button>
            </form>
        </div>
    </div>
