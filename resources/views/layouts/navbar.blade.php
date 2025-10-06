    <style>
        .sidebar {
            background: #dd0000;
            color: #fff;
            width: 130px;
            min-height: calc(100vh - 56px);
            position: fixed;
            left: 0;
            top: 56px; /* Mulai di bawah topbar */
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            z-index: 2000;
        }
        .sidebar .nav-link {
            color: #fff;
            font-weight: 500;
            margin-bottom: 10px;
            text-align: center;
            width: 100%;
            padding: 0;
            font-size: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1px;
        }
        .sidebar .nav-link i {
            font-size: 1.5rem;
            margin-bottom: 0;
        }
        .sidebar .nav-link span {
            font-size: 1rem;
            display: block;
        }
        .sidebar .nav-link.active {
            background: #910000;
        }

        .topbar .logo {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-right: auto;
            margin-left: 0;
            padding-left: 0;
        }
        .topbar {
            background: #d3d3c6;
            padding: 4px 0px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 56px;
            gap: 24px;
        }
        .topbar .user-info {
            margin-left: auto;
            margin-right: 16px;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #222;
        }
        .user-info .bi-person-circle {
            font-size: 2rem;
        }
        .user-info .user-name {
            font-weight: 500;
            font-size: 1.1rem;
        }
        .user-info .user-role {
            font-weight: bold;
            font-size: 1.05rem;
            color: #222;
        }
       
    </style>

    <div class="sidebar">


        @auth
            @php $role = strtolower(Auth::user()->role ?? ''); @endphp

            {{-- Kontributor: lihat data kata, entri kata, entri kalimat --}}
            @if($role === 'kontributor')
                <a href="{{ url('/beranda') }}" class="nav-link sidebar-item">
                    <i class="bi bi-house-door"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ url('/EntriKata') }}" class="nav-link sidebar-item">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Entri kata</span>
                </a>
                <a href="{{ url('/EntriKalimat') }}" class="nav-link sidebar-item">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Entri kalimat</span>
                </a>

            {{-- Validator: entri kata, entri kalimat, validasi kata, validasi kalimat --}}
            @elseif($role === 'validator')
                <a href="{{ url('/EntriKata') }}" class="nav-link sidebar-item">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Entri kata</span>
                </a>
                <a href="{{ url('/EntriKalimat') }}" class="nav-link sidebar-item">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Entri kalimat</span>
                </a>
                <a href="{{ url('/ValidasiKata') }}" class="nav-link sidebar-item">
                    <i class="bi bi-gear"></i>
                    <span>Validasi Kata</span>
                </a>
                <a href="{{ url('/ValidasiKalimat') }}" class="nav-link sidebar-item">
                    <i class="bi bi-gear"></i>
                    <span>Validasi Kalimat</span>
                </a>

            {{-- Admin: entri kata, entri kalimat, validasi kata, validasi kalimat, kelola user --}}
            @elseif($role === 'admin')
                <a href="{{ url('/EntriKata') }}" class="nav-link sidebar-item">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Entri kata</span>
                </a>
                <a href="{{ url('/EntriKalimat') }}" class="nav-link sidebar-item">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Entri kalimat</span>
                </a>
                <a href="{{ url('/ValidasiKata') }}" class="nav-link sidebar-item">
                    <i class="bi bi-gear"></i>
                    <span>Validasi Kata</span>
                </a>
                <a href="{{ url('/ValidasiKalimat') }}" class="nav-link sidebar-item">
                    <i class="bi bi-gear"></i>
                    <span>Validasi Kalimat</span>
                </a>
                <a href="{{ url('/KelolaUser') }}" class="nav-link sidebar-item">
                    <i class="bi bi-person-circle"></i>
                    <span>Kelola User</span>
                </a>
            @endif

            {{-- Logout form pinned to bottom --}}
            <form method="POST" action="{{ url('/logout') }}" class="mt-auto w-100">
                @csrf
                <button type="submit" class="nav-link sidebar-item btn btn-link text-start" style="color: #fff; width:100%;">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>

        @else
            {{-- Guest: show login link pinned to bottom --}}
            <a href="{{ url('/login') }}" class="nav-link mt-auto sidebar-item">
                <i class="bi bi-box-arrow-right"></i>
                <span>Login</span>
            </a>
        @endauth
    </div>
    
        <div class="topbar">
            <div></div>
            <div class="logo">
                <img src="{{ asset('img/logonobg.png') }}" alt="Logo" style="width: 85px; height: 55px;">
            </div>
            <div class="user-info">
                @auth
                    @php
                        $user = Auth::user();
                        $fotoUrl = $user->foto ? asset('storage/foto_users/' . $user->foto) : asset('img/louis.jpg');
                    @endphp
                    <img src="{{ $fotoUrl }}" alt="Avatar" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                    <div>
                        <div class="user-name">{{ $user->nama_user ?? $user->username }}</div>
                        <div class="user-role">{{ strtoupper($user->role ?? '') }}</div>
                    </div>
                @else
                    <a href="{{ url('/login') }}" class="btn btn-sm btn-warning">Login</a>
                @endauth
            </div>
        </div>
  