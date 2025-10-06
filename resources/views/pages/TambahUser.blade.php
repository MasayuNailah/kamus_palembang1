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
        <div class="fixed-title">TAMBAH USER</div>
        <div class="form-container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/TambahUser') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input name="nama_user" type="text" class="form-control" value="{{ old('nama_user') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control" value="{{ old('email') }}">
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                <label class="form-label">Username</label>
                <input name="username" type="text" class="form-control" value="{{ old('username') }}">
                </div>
                <div class="col-md-6">
                <label class="form-label">Password</label>
                <input name="password" type="password" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="Kontributor" {{ old('role')=='Kontributor'?'selected':'' }}>Kontributor</option>
                    <option value="Validator" {{ old('role')=='Validator'?'selected':'' }}>Validator</option>
                    <option value="Admin" {{ old('role')=='Admin'?'selected':'' }}>Admin</option>
                </select>
                </div>
                <div class="col-md-6">
                <label class="form-label">Foto</label>
                <div class="input-group">
                    <div>
                    <input name="foto" type="file" class="form-control d-none" id="input-foto" accept="image/*">
                    <label class="btn btn-secondary" for="input-foto">Pilih Foto</label>
                    <div class="mt-3 text-center">
                        <img id="preview-foto" src="" alt="Preview Foto" style="max-width:120px; display:none;" />
                    </div>
                    <span id="nama-file" class="ms-2"></span>
                    </div>
                </div>
                </div>
            </div>
            <button type="submit" class="btn btn-submit">SUBMIT</button>
            </form>

        <div class="mt-3 text-center">
            <img id="preview-foto" src="" alt="Preview Foto" style="max-width:120px; display:none;" />
        </div>

        <script>
        const inputFoto = document.getElementById('input-foto');
        if (inputFoto) {
            inputFoto.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    // Tampilkan nama file
                    document.getElementById('nama-file').textContent = file.name;
                    // Tampilkan pratinjau foto
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.getElementById('preview-foto');
                        img.src = e.target.result;
                        img.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        </script>

        </div>
    </div>

@endsection
