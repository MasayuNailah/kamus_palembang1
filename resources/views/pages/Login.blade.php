<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="img/logonobg.png">
    <title>Login-Kamus Digital Melayu Palembang</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #d3d3c6;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-card {
      background: #db0000;
      border-radius: 50px;
      color: #fff;
      padding: 48px 40px;
      min-width: 400px;
      box-shadow: 0 0 24px rgba(0,0,0,0.05);
    }
    .login-card .form-control {
      border-radius: 30px;
      padding-left: 44px;
      background: #fff;
      color: #333;
      border: none;
      font-size: 1.1rem;
    }
    .login-card .input-group-text {
      background: transparent;
      border: none;
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: #da0000;
      font-size: 1.3rem;
    }
    .login-card .form-check-label {
      color: #222;
    }
    .login-card .btn {
      border-radius: 20px;
      background: #ffd82d;
      color: #222;
      font-weight: bold;
      width: 100%;
      margin-top: 16px;
    }
    .image-placeholder {
      border-radius: 16px;
      width: 700px;
      height: 400px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: auto;
    }
    .image-placeholder svg {
      width: 200px;
      height: 200px;
      opacity: 0.6;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
      <div class="col-md-6 d-flex justify-content-center">
        <div class="image-placeholder">
            <img src="img/logonobg.png" class="img-fluid float-end" alt="img">
        </div>
      </div>
      <div class="col-md-6 d-flex justify-content-center">
        <div class="login-card w-100">
          <h2 class="fw-bold mb-2">Welcome back!</h2>
          <p class="mb-4" style="font-size: 0.98rem; color: #eaeaea;">sign in by entering the information below</p>
          <form method="POST" action="{{ url('/login') }}">
            @csrf
            <div class="mb-3 position-relative">
              <span class="input-group-text">
                <i class="bi bi-person"></i>
              </span>
              <input type="text" name="username" class="form-control ps-5 @error('username') is-invalid @enderror" placeholder="Username" value="{{ old('username') }}">
              @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 position-relative">
              <span class="input-group-text">
                <i class="bi bi-lock"></i>
              </span>
              <input type="password" name="password" class="form-control ps-5 @error('password') is-invalid @enderror" placeholder="Password">
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <span class="position-absolute" style="right: 24px; top: 50%; transform: translateY(-50%); color: #222; cursor: pointer;">
                <i class="bi bi-eye-slash"></i>
              </span>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" id="rememberMe" checked>
              <label class="form-check-label" for="rememberMe" style="color: #ffffff;">
                Remember me
              </label>
            </div>
            <button type="submit" class="btn">LOGIN</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap Icons CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>