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
            transition: background 0.2s, color 0.2s;
        }
        .btn-submit:active {
            background: #ffd700;
            color: #333;
        }
    </style>
    <div class="main-content">
        <div class="fixed-title">FORM ENTRI KALIMAT</div>
        <div class="form-container">
            <form method="POST" action="{{ url('/EntriKalimat') }}">
                @csrf
                <div class="mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Sub ID</label>
                        <select class="form-select" id="subIdSelect" name="sub_id">
                            <option value="">Cari Sub ID...</option>
                            @if(isset($kataList))
                                @foreach($kataList as $k)
                                    <option value="{{ $k->id_kata }}">{{ $k->id_kata }} - {{ $k->kata }}</option>
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
                    <label class="form-label">Kalimat</label>
                    <textarea name="kalimat" class="form-control" rows="3">{{ old('kalimat') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Arti kalimat</label>
                    <textarea name="arti" class="form-control" rows="3">{{ old('arti') }}</textarea>
                </div>
                <button type="submit" class="btn btn-submit">SUBMIT</button>
            </form>
        </div>
        </div>
    </div>
@endsection