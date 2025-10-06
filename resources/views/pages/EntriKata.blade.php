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
            margin-top: 0;
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
        <div class="fixed-title">FORM ENTRI KATA</div>
        <div class="form-container">
            <form method="POST" action="{{ route('kata.store') }}">
                @csrf
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kata</label>
                        <select class="form-select" id="jenisKata" name="jenis_kata">
                            <option value="Kata Dasar">Kata Dasar</option>
                            <option value="Kata Turunan">Kata Turunan</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="subIdGroup" style="display: {{ old('jenis_kata') == 'Kata Turunan' ? '' : 'none' }};">
                        <label class="form-label">Sub ID</label>
                        <select class="form-select" id="subIdSelect" name="sub_id">
                            <option value="">Cari Sub ID...</option>
                            @if(isset($kataList) && $kataList->count())
                                @foreach($kataList as $k)
                                    <option value="{{ $k->id_kata }}" {{ old('sub_id') == $k->id_kata ? 'selected' : '' }}>{{ $k->id_kata }} - {{ $k->kata }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        $('#subIdSelect').select2({
                            placeholder: "Cari Sub ID...",
                            allowClear: true,
                            width: '100%'
                        });
                    });
                </script>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kata</label>
                    <input type="text" name="kata" class="form-control @error('kata') is-invalid @enderror" value="{{ old('kata') }}">
                    @error('kata') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Arti kata</label>
                    <input type="text" name="arti" class="form-control @error('arti') is-invalid @enderror" value="{{ old('arti') }}">
                    @error('arti') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Cara baca</label>
                        <input type="text" name="cara_baca" class="form-control" value="{{ old('cara_baca') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Keterangan</label>
                        <select class="form-select" name="keterangan">
                            <option value="">(kosong)</option>
                            <option value="Noun" {{ old('keterangan')=='Noun' ? 'selected' : '' }}>Noun</option>
                            <option value="Verb" {{ old('keterangan')=='Verb' ? 'selected' : '' }}>Verb</option>
                            <option value="Adjective" {{ old('keterangan')=='Adjective' ? 'selected' : '' }}>Adjective</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-submit">SUBMIT</button>
            </form>
            <script>
                const jenisKata = document.getElementById('jenisKata');
                const subIdGroup = document.getElementById('subIdGroup');
                jenisKata.addEventListener('change', function() {
                    if (jenisKata.value === 'Kata Turunan') {
                        subIdGroup.style.display = '';
                    } else {
                        subIdGroup.style.display = 'none';
                    }
                });
                // Inisialisasi saat load
                if (jenisKata.value === 'Kata Turunan') {
                    subIdGroup.style.display = '';
                } else {
                    subIdGroup.style.display = 'none';
                }
            </script>
        </div>
    </div>
@endsection
