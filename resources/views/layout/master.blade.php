<!DOCTYPE html>
<html> 
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Aplikasi Pengelolaan Pasar Induk Caringin">
        <meta name="author" content="Levind Sein & Maizu">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        
        <!-- Title -->

        @yield('title')

        <!-- Favicon -->
        <link rel="icon" href="{{asset('img/logo.png')}}">
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
        <!-- Icons -->
        <link rel="stylesheet" href="{{asset('argon/vendor/nucleo/css/nucleo.min.css')}}" type="text/css">
        <link rel="stylesheet" href="{{asset('vendor/fontawesome/css/all.min.css')}}" type="text/css">
        <link rel="stylesheet" href="{{asset('vendor/fontawesomepro/css/all.min.css')}}" type="text/css">
        <!-- Page plugins -->
        <link rel="stylesheet" href="{{asset('argon/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('datatables/fixed-columns/css/fixed-columns.min.css')}}">
        <link rel="stylesheet" href="{{asset('datatables/responsive/css/responsive.bootstrap.min.css')}}">
        <!-- Argon CSS -->
        <link rel="stylesheet" href="{{asset('argon/vendor/select2/dist/css/select2.min.css')}}">
        <link rel="stylesheet" href="{{asset('argon/css/argon.min.css')}}" type="text/css">
        
        <script src="{{asset('argon/vendor/jquery/dist/jquery.min.js')}}"></script>
    </head>
 
    <body>
        <div class="se-pre-con"></div>
        <!-- Sidenav -->
        <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-dark bg-gradient-vine" id="sidenav-main">
            <div class="scrollbar-inner">
                <div class="sidenav-header d-flex align-items-center">
                    <a class="navbar-brand" href="#">
                        <span class="sidebar-brand-text mx-2 text-white"><b>
                        @if(Session::get('role') == 'master')
                        MASTER
                        @elseif(Session::get('role') == 'manajer')
                        MANAJER
                        @elseif(Session::get('role') == 'admin')
                        ADMIN
                        @elseif(Session::get('role') == 'kasir')
                        KASIR
                        @elseif(Session::get('role') == 'keuangan')
                        KEUANGAN
                        @else
                        NGINX
                        @endif
                        </b>
                        </span>  
                    </a>
                    <div class="ml-auto" id="event-xl">
                        <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                            <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line bg-light"></i>
                            <i class="sidenav-toggler-line bg-light"></i>
                            <i class="sidenav-toggler-line bg-light"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="navbar-inner">
                    <!-- Collapse -->
                    <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                        @if(Session::get('role') == 'kasir')
                        <!-- Nav items -->
                        <ul class="navbar-nav">
                            <!-- Nav Item - Dashboard -->
                            <li class="nav-item"  >
                                <a class="nav-link {{ (request()->is('kasir*')) ? 'active' : '' }}" href="{{url('kasir')}}">
                                    <i class="fad fa-home text-primary"></i>
                                    <span class="nav-link-text">Fitur&nbsp;Utama</span></a>
                            </li>
                            <!-- <li class="nav-item"  >
                                <a class="nav-link" href="{{url('#')}}">
                                    <i class="fad fa-home text-success"></i>
                                    <span class="nav-link-text">Fitur&nbsp;Harian</span></a>
                            </li> -->
                            @if(Session::get('opsional') && Session::get('otoritas')->lapangan_kasir)
                            <li class="nav-item">
                                <a class="home-tagihan nav-link {{ (request()->is('tagihan*')) ? 'active' : '' }}" href="{{url('tagihan')}}">
                                    <i class="fas fa-plus text-info"></i>
                                    <span class="nav-link-text">Tagihan</span></a>
                            </li>
                            @endif
                        </ul>
                        <!-- Divider -->
                        <hr class="my-3">
                        @endif

                        @if(Session::get('role') == 'master' || Session::get('role') == 'manajer' || Session::get('role') == 'admin' || Session::get('role') == 'keuangan')
                        <!-- Nav items -->
                        <ul class="navbar-nav">
                            <!-- Nav Item - Dashboard -->
                            <li class="nav-item"  >
                                <a class="nav-link {{ (request()->is('dashboard*')) ? 'active' : '' }}" href="{{url('dashboard')}}">
                                    <i class="fad fa-tachometer-alt text-primary"></i>
                                    <span class="nav-link-text">Dashboard</span></a>
                            </li>
                        </ul>
                        <!-- Divider -->
                        <hr class="my-3">
                        @endif

                        @if(Session::get('role') == 'keuangan')
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a href="#laporan-tagihan" class="nav-link {{ (request()->is('keuangan/tagihan*')) ? 'active' : '' }}" data-toggle="collapse" role="button" aria-expanded="{{ (request()->is('keuangan/tagihan*')) ? 'true' : '' }}" aria-controls="laporan-tagihan"><i class="fas fa-dollar-sign text-yellow"></i><span class="nav-link-text">Tagihan</span></a>
                                <div class="collapse {{ (request()->is('keuangan/tagihan*')) ? 'show' : '' }}" id="laporan-tagihan">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item"><a href="{{url('keuangan/tagihan/listrik')}}" class="nav-link {{ (request()->is('keuangan/tagihan/listrik')) ? 'text-teal' : '' }}"><i class="fas fa-bolt text-yellow"></i>Listrik</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/tagihan/airbersih')}}" class="nav-link {{ (request()->is('keuangan/tagihan/airbersih')) ? 'text-teal' : '' }}"><i class="fad fa-tint text-primary"></i>Air Bersih</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/tagihan/keamananipk')}}" class="nav-link {{ (request()->is('keuangan/tagihan/keamananipk')) ? 'text-teal' : '' }}"><i class="fad fa-lock text-danger"></i>Keamanan IPK</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/tagihan/kebersihan')}}" class="nav-link {{ (request()->is('keuangan/tagihan/kebersihan')) ? 'text-teal' : '' }}"><i class="fad fa-leaf text-success"></i>Kebersihan</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/tagihan/airkotor')}}" class="nav-link {{ (request()->is('keuangan/tagihan/airkotor')) ? 'text-teal' : '' }}"><i class="fad fa-fill-drip text-white"></i>Air Kotor</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/tagihan/lain')}}" class="nav-link {{ (request()->is('keuangan/tagihan/lain')) ? 'text-teal' : '' }}"><i class="fad fa-thumbtack text-pink"></i>Lainnya</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/tagihan/tagihan')}}" class="nav-link {{ (request()->is('keuangan/tagihan/tagihan')) ? 'text-teal' : '' }}"><i class="fas fa-database text-orange"></i>Semua&nbsp;Fasilitas</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="#laporan-tunggakan" class="nav-link {{ (request()->is('keuangan/tunggakan*')) ? 'active' : '' }}" data-toggle="collapse" role="button" aria-expanded="{{ (request()->is('keuangan/tunggakan*')) ? 'true' : '' }}" aria-controls="laporan-tunggakan"><i class="fad fa-book text-red"></i><span class="nav-link-text">Tunggakan</span></a>
                                <div class="collapse {{ (request()->is('keuangan/tunggakan*')) ? 'show' : '' }}" id="laporan-tunggakan">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item"><a href="{{url('keuangan/tunggakan/listrik')}}" class="nav-link {{ (request()->is('keuangan/tunggakan/listrik')) ? 'text-teal' : '' }}"><i class="fas fa-bolt text-yellow"></i>Listrik</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/tunggakan/airbersih')}}" class="nav-link {{ (request()->is('keuangan/tunggakan/airbersih')) ? 'text-teal' : '' }}"><i class="fad fa-tint text-primary"></i>Air Bersih</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/tunggakan/keamananipk')}}" class="nav-link {{ (request()->is('keuangan/tunggakan/keamananipk')) ? 'text-teal' : '' }}"><i class="fad fa-lock text-danger"></i>Keamanan IPK</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/tunggakan/kebersihan')}}" class="nav-link {{ (request()->is('keuangan/tunggakan/kebersihan')) ? 'text-teal' : '' }}"><i class="fad fa-leaf text-success"></i>Kebersihan</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/tunggakan/airkotor')}}" class="nav-link {{ (request()->is('keuangan/tunggakan/airkotor')) ? 'text-teal' : '' }}"><i class="fad fa-fill-drip text-white"></i>Air Kotor</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/tunggakan/lain')}}" class="nav-link {{ (request()->is('keuangan/tunggakan/lain')) ? 'text-teal' : '' }}"><i class="fad fa-thumbtack text-pink"></i>Lainnya</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/tunggakan/tagihan')}}" class="nav-link {{ (request()->is('keuangan/tunggakan/tagihan')) ? 'text-teal' : '' }}"><i class="fas fa-database text-orange"></i>Semua&nbsp;Fasilitas</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="#laporan-pendapatan" class="nav-link {{ (request()->is('keuangan/pendapatan*')) ? 'active' : '' }}" data-toggle="collapse" role="button" aria-expanded="{{ (request()->is('keuangan/pendapatan*')) ? 'true' : '' }}" aria-controls="laporan-pendapatan"><i class="fad fa-hand-receiving text-success"></i><span class="nav-link-text">Pendapatan</span></a>
                                <div class="collapse {{ (request()->is('keuangan/pendapatan*')) ? 'show' : '' }}" id="laporan-pendapatan">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item"><a href="{{url('keuangan/pendapatan/harian')}}" class="nav-link {{ (request()->is('keuangan/pendapatan/harian')) ? 'text-teal' : '' }}"><i class="fad fa-calendar-day text-yellow"></i>Harian</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/pendapatan/bulanan')}}" class="nav-link {{ (request()->is('keuangan/pendapatan/bulanan')) ? 'text-teal' : '' }}"><i class="fad fa-calendar-week text-success"></i>Bulanan</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/pendapatan/tahunan')}}" class="nav-link {{ (request()->is('keuangan/pendapatan/tahunan')) ? 'text-teal' : '' }}"><i class="fad fa-calendar-alt text-orange"></i>Tahunan</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="#laporan-rekap" class="nav-link {{ (request()->is('keuangan/rekap*')) ? 'active' : '' }}" data-toggle="collapse" role="button" aria-expanded="{{ (request()->is('keuangan/rekap*')) ? 'true' : '' }}" aria-controls="laporan-rekap"><i class="fad fa-books text-info"></i><span class="nav-link-text">Rekap</span></a>
                                <div class="collapse {{ (request()->is('keuangan/rekap*')) ? 'show' : '' }}" id="laporan-rekap">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item"><a href="{{url('keuangan/rekap/sisa')}}" class="nav-link {{ (request()->is('keuangan/rekap/sisa')) ? 'text-teal' : '' }}"><i class="fad fa-calendar-day text-yellow"></i>Sisa&nbsp;Tagihan</a></li>
                                        <li class="nav-item"><a href="{{url('keuangan/rekap/selesai')}}" class="nav-link {{ (request()->is('keuangan/rekap/selesai')) ? 'text-teal' : '' }}"><i class="fad fa-calendar-week text-success"></i>Akhir&nbsp;Bulan</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                        <!-- Divider -->
                        <hr class="my-3">
                        @endif

                        @if(Session::get('role') == 'master' || Session::get('role') == 'manajer'||  Session::get('role') == 'admin' && (Session::get('otoritas')->pedagang || Session::get('otoritas')->tempatusaha || Session::get('otoritas')->tagihan || Session::get('otoritas')->publish || Session::get('otoritas')->layanan))
                        <ul class="navbar-nav">
                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->layanan))
                            <!-- Nav Item - Pedagang -->
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('layanan*')) ? 'active' : '' }}" href="{{url('layanan')}}">
                                    <i class="fas fa-headset text-success"></i>
                                    <span class="nav-link-text">Layanan</span></a>
                            </li>
                            @endif

                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->pedagang))
                            <!-- Nav Item - Pedagang -->
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('pedagang*')) ? 'active' : '' }}" href="{{url('pedagang')}}">
                                    <i class="fad fa-users text-orange"></i>
                                    <span class="nav-link-text">Pedagang</span></a>
                            </li>
                            @endif

                            @if(Session::get('role') == 'master' || Session::get('role') == 'manajer' ||  Session::get('role') == 'admin' && (Session::get('otoritas')->tempatusaha))
                            <!-- Nav Item - Tempat Usaha -->
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('tempatusaha*')) ? 'active' : '' }}" href="{{url('tempatusaha')}}">
                                    <i class="fad fa-store text-pink"></i>
                                    <span class="nav-link-text">Tempat&nbsp;Usaha</span></a>
                            </li>
                            @endif

                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->tagihan || Session::get('otoritas')->publish))
                            <!-- Nav Item - Tagihan -->
                            <li class="nav-item">
                                <a class="home-tagihan nav-link {{ (request()->is('tagihan*')) ? 'active' : '' }}" href="{{url('tagihan')}}">
                                    <i class="fas fa-plus text-info"></i>
                                    <span class="nav-link-text">Tagihan</span></a>
                            </li>
                            @endif
                        </ul>
                        <!-- Divider -->
                        <hr class="my-3">
                        @endif

                        @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->pemakaian || Session::get('otoritas')->pendapatan || Session::get('otoritas')->datausaha))
                        <ul class="navbar-nav">
                            @if(Session::get('role') == 'master' || Session::get('role') == 'manajer' ||  Session::get('role') == 'admin' && (Session::get('otoritas')->pemakaian || Session::get('otoritas')->pendapatan))
                            <!-- Nav Item - Laporan -->
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('rekap/*')) ? 'active' : '' }}" href="#navbar-laporan" data-toggle="collapse" role="button" aria-expanded="{{ (request()->is('rekap/*')) ? 'true' : 'false' }}" aria-controls="navbar-laporan">
                                    <i class="fad fa-book text-success"></i>
                                    <span class="nav-link-text">Laporan</span>
                                </a>
                                <div class="collapse {{ (request()->is('rekap/*')) ? 'show' : '' }}" id="navbar-laporan">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'manajer' || Session::get('role') == 'admin' && (Session::get('otoritas')->pemakaian))
                                            <a class="nav-link {{ (request()->is('rekap/pemakaian')) ? 'text-teal' : '' }}" href="{{url('rekap/pemakaian')}}">Pemakaian</a>
                                            @endif
                                        </li>
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'manajer' || Session::get('role') == 'admin' && (Session::get('otoritas')->pendapatan))
                                            <a class="nav-link {{ (request()->is('rekap/pendapatan')) ? 'text-teal' : '' }}" href="{{url('rekap/pendapatan')}}">Pendapatan</a>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            @endif

                            @if(Session::get('role') == 'master' || Session::get('role') == 'manajer' || Session::get('role') == 'admin' && (Session::get('otoritas')->datausaha))
                            <!-- Nav Item - Data -->
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('datausaha*')) ? 'active' : '' }}" href="{{url('datausaha')}}">
                                    <i class="fad fa-list text-yellow"></i>
                                    <span class="nav-link-text">Data&nbsp;Usaha</span></a>
                            </li>
                            @endif
                        </ul>
                        <!-- Divider -->
                        <hr class="my-3">
                        @endif

                        @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->blok || Session::get('otoritas')->alatmeter || Session::get('otoritas')->tarif || Session::get('otoritas')->harilibur || Session::get('otoritas')->simulasi))
                        <ul class="navbar-nav">
                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->blok || Session::get('otoritas')->alatmeter || Session::get('otoritas')->tarif || Session::get('otoritas')->harilibur || Session::get('otoritas')->simulasi))
                            <!-- Nav Item - Utilities -->
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('utilities/*')) ? 'active' : '' }}" href="#navbar-utilities" data-toggle="collapse" role="button" aria-expanded="{{ (request()->is('utilities/*')) ? 'true' : 'false' }}" aria-controls="navbar-utilities">
                                    <i class="fad fa-tools text-pink"></i>
                                    <span class="nav-link-text">Utilities</span>
                                </a>
                                <div class="collapse {{ (request()->is('utilities/*')) ? 'show' : '' }}" id="navbar-utilities">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->tarif))
                                            <a class="nav-link {{ (request()->is('utilities/tarif*')) ? 'text-teal' : '' }}" href="{{url('utilities/tarif')}}">Tarif</a>
                                            @endif
                                        </li>
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->alatmeter))
                                            <a class="nav-link {{ (request()->is('utilities/alatmeter*')) ? 'text-teal' : '' }}" href="{{url('utilities/alatmeter')}}">Alat&nbsp;Meter</a>
                                            @endif
                                        </li>
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->harilibur))
                                            <a class="nav-link {{ (request()->is('utilities/harilibur*')) ? 'text-teal' : '' }}" href="{{url('utilities/harilibur')}}">Hari&nbsp;Libur</a>
                                            @endif
                                        </li>
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->blok))
                                            <a class="nav-link {{ (request()->is('utilities/blok*')) ? 'text-teal' : '' }}" href="{{url('utilities/blok')}}">Blok</a>
                                            @endif
                                        </li>
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->simulasi))
                                            <a class="nav-link {{ (request()->is('utilities/simulasi*')) ? 'text-teal' : '' }}" href="{{url('utilities/simulasi')}}">Simulasi</a>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            @endif
                        </ul>
                        <!-- Divider -->
                        <hr class="my-3">
                        @endif
                        <ul class="navbar-nav">
                            @if(Session::get('role') == 'master')
                            <!-- Nav Item - Kasir -->
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('master/kasir*')) ? 'active' : '' }}" href="{{url('master/kasir')}}">
                                    <i class="fad fa-dollar-sign text-yellow"></i>
                                    <span class="nav-link-text">Kasir</span></a>
                            </li>
                            
                            <!-- Nav Item - User -->
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('user*')) ? 'active' : '' }}" href="{{url('user')}}">
                                    <i class="fas fa-users text-orange"></i>
                                    <span class="nav-link-text">User</span></a>
                            </li>

                            <!-- Nav Item - Log -->
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('log*')) ? 'active' : '' }}" href="{{url('log')}}">
                                    <i class="fad fa-history text-primary"></i>
                                    <span class="nav-link-text">Riwayat&nbsp;Login</span></a>
                            </li>
                            @endif
                            
                            <!-- Nav Item - Log -->
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('information*')) ? 'active' : '' }}" href="{{url('information')}}">
                                    <i class="fad fa-info text-yellow"></i>
                                    <span class="nav-link-text">Patch&nbsp;Info</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="main-content" id="panel">
            <nav class="navbar navbar-top navbar-expand navbar-dark bg-vine">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Navbar links -->
                        <ul class="navbar-nav align-items-center ml-md-auto">
                            <li class="nav-item d-xl-none">
                                <!-- Sidenav toggler -->
                                <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                                    <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line bg-light"></i>
                                    <i class="sidenav-toggler-line bg-light"></i>
                                    <i class="sidenav-toggler-line bg-light"></i>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="navbar-nav align-items-center ml-auto ml-md-0">
                            <!-- <li class="nav-item dropdown">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ni ni-bell-55"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden">
                                    <div class="col-lg-12 px-3 py-3">
                                        <h6 class="text-sm text-muted m-0">You have <strong class="text-primary">13</strong> notifications.</h6>
                                    </div>
                                    <div class="list-group list-group-flush">
                                        <a href="#!" class="list-group-item list-group-item-action">
                                            <div class="row align-items-center">
                                                <div class="col-lg-12">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h4 class="mb-0 text-sm">John Snow</h4>
                                                        </div>
                                                        <div class="text-right text-muted">
                                                            <small>2 hrs ago</small>
                                                        </div>
                                                    </div>
                                                    <p class="text-sm mb-0">Let's meet at Starbucks at 11:30. Wdyt?</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">View all</a>
                                </div>
                            </li> -->
                            <li class="nav-item dropdown">
                                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="media align-items-center">
                                        <span class="avatar avatar-sm rounded-circle">
                                            <img alt="user caringin" src="{{asset('img/icon_user.png')}}">
                                        </span>
                                        <div class="media-body ml-2 d-none d-lg-block">
                                            <span class="mb-0 text-sm  font-weight-bold">{{Session::get('username')}}</span>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#!" class="dropdown-item">
                                        <i class="ni ni-settings-gear-65"></i>
                                        <span>Settings</span>
                                    </a>
                                    <a href="#" data-toggle="modal" data-target="#modal-logout" class="dropdown-item">
                                        <i class="ni ni-user-run"></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Header -->
            <div class="header bg-vine pb-6">
                @include('message.flash-message') 
                <div class="container-fluid">
                    <div class="header-body">
                        <div class="row align-items-center py-4">
                            <div class="col-lg-6 col-7">
                                <!-- Judul -->
                                @yield('judul')
                                <!-- <h6 class="h2 text-white d-inline-block mb-0">Tables</h6> -->
                            </div>
                            <div class="col-lg-6 col-5 text-right">
                                <!-- Button -->
                                @yield('button')
                                <!-- <a href="#" class="btn btn-sm btn-neutral">New</a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page content -->
            <div class="container-fluid mt--6">
                <!-- Content -->
                @yield('content')
            </div>
        </div>

        <div class="modal fade" id="modal-logout" tabindex="-1" role="dialog" aria-labelledby="modal-logout" aria-hidden="true">
            <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                <div class="modal-content bg-gradient-danger">
                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-notification">Logout ?</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="py-3 text-center">
                        <i class="ni ni-button-power ni-3x"></i>
                        <h4 class="heading mt-4">Tekan Tombol Logout !</h4>
                        <p>Jika anda yakin untuk mengakhiri sesi anda.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <a class="btn btn-white" href="{{url('logout')}}">Logout</a>
                        <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        @yield('modal')

        <script>
            $(window).on('load', function() {
                $(".se-pre-con").fadeIn("slow").fadeOut("slow");
            });

            $(document).ready(function(){
                $(document).on('click', '.home-tagihan', function(){
                    $.ajax({
                        url :"/tagihan/checking/home",
                        cache:false,
                        dataType:"json",
                        success:function(data)
                        {
                            if(data.success)
                                console.log(data.success);
                            else
                                console.log(data.errors);
                        }
                    });
                });
                
                $(document).on('click', '#checking-report', function(){
                    $.ajax({
                        url :"/tagihan/checking/report",
                        cache:false,
                        dataType:"json",
                        success:function(data)
                        {
                            if(data.success)
                                location.reload();
                            else
                                alert(data.errors);
                        }
                    });
                });
            });
        </script>
        
        <!-- Argon Scripts -->
        <!-- Core -->
        <script src="{{asset('argon/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('argon/vendor/js-cookie/js.cookie.js')}}"></script>
        <script src="{{asset('argon/vendor/jquery.scrollbar/jquery.scrollbar.min.js')}}"></script>
        <script src="{{asset('argon/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js')}}"></script>
        <!-- Optional JS -->
        <script src="{{asset('argon/vendor/select2/dist/js/select2.min.js')}}"></script>
        <script src="{{asset('argon/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('argon/vendor/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('argon/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('datatables/fixed-columns/js/fixed-columns.min.js')}}"></script>
        <script src="{{asset('datatables/responsive/js/responsive.min.js')}}"></script>
        <script src="{{asset('datatables/responsive/js/responsiveBootstrap.min.js')}}"></script>
        <!-- Argon JS -->
        <script src="{{asset('argon/js/argon.min.js')}}"></script>
        
        <script src="{{asset('vendor/chart-js/Chart.min.js')}}"></script>
        
        <!--for column table toggle-->
        <script>
            $(document).ready(function() {
                $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
                    $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                } );
            });
        </script>

        @yield('js')
    </body>
</html>