{{-- CSS untuk menambahkan radius pada dropdown menu --}}
<style>
    /* Menargetkan dropdown menu di user menu navbar */
    .navbar-nav > .user-menu > .dropdown-menu {
        border-radius: 12px; /* Atur tingkat lengkungan di sini */
        overflow: hidden;    /* Memastikan konten di dalam mengikuti lengkungan */
        border: 1px solid rgba(0,0,0,0.1); /* Opsional: border agar lebih rapi */
        padding: 0; /* Menghapus padding agar header dan footer pas */
    }

    /* Menyesuaikan padding pada user-header */
    .user-header {
        padding: 10px;
    }

    /* Memberi radius pada tombol di footer dropdown */
    .user-footer .btn {
        border-radius: 8px;
    }
</style>

<header class="main-header">
    <a href="index2.html" class="logo">
        @php
            $words = explode(' ', $setting->nama_perusahaan);
            $word  = '';
            foreach ($words as $w) {
                $word .= $w[0];
            }
        @endphp
        <span class="logo-mini">{{ $word }}</span>
        <span class="logo-lg"><b>{{ $setting->nama_perusahaan }}</b></span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ url(auth()->user()->foto ?? '') }}" class="user-image img-profil"
                            alt="User Image">
                        <span class="hidden-xs">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{ url(auth()->user()->foto ?? '') }}" class="img-circle img-profil"
                                alt="User Image">

                            <p>
                                {{ auth()->user()->name }} - {{ auth()->user()->email }}
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('user.profil') }}" class="btn btn-default btn-flat">Profil</a>
                            </div>
                            <div class="pull-right">
                                <a href="#" class="btn btn-default btn-flat"
                                    onclick="$('#logout-form').submit()">Keluar</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<form action="{{ route('logout') }}" method="post" id="logout-form" style="display: none;">
    @csrf
</form>
