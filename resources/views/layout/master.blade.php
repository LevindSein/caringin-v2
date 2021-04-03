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
        <link rel="stylesheet" href="{{asset('argon/vendor/nucleo/css/nucleo.css')}}" type="text/css">
        <link rel="stylesheet" href="{{asset('vendor/fontawesome/css/all.min.css')}}" type="text/css">
        <!-- Page plugins -->
        <link rel="stylesheet" href="{{asset('argon/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('datatables/fixed-columns/css/fixed-columns.min.css')}}">
        <link rel="stylesheet" href="{{asset('datatables/responsive/css/responsive.bootstrap.min.css')}}">
        <!-- Argon CSS -->
        <link rel="stylesheet" href="{{asset('argon/vendor/select2/dist/css/select2.min.css')}}">
        <link rel="stylesheet" href="{{asset('argon/css/argon.css')}}" type="text/css">
        
        <script src="{{asset('argon/vendor/jquery/dist/jquery.min.js')}}"></script>
    </head>
 
    <body>
        <div class="se-pre-con"></div>
        <!-- Sidenav -->
        <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-dark bg-gradient-vine" id="sidenav-main">
            <div class="scrollbar-inner">
                <div class="sidenav-header d-flex align-items-center">
                    <a class="navbar-brand" href="#">
                        <span class="sidebar-brand-text mx-3 text-white"><b>
                        @if(Session::get('role') == 'master')
                        MASTER
                        @elseif(Session::get('role') == 'manajer')
                        MANAJER
                        @elseif(Session::get('role') == 'admin')
                        ADMIN
                        @else
                        WHO ARE YOU ?
                        @endif
                        </b></span>  
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
                        @if(Session::get('role') == 'master' || Session::get('role') == 'manajer' || Session::get('role') == 'admin')
                        <!-- Nav items -->
                        <ul class="navbar-nav">
                            <!-- Nav Item - Dashboard -->
                            <li class="nav-item"  >
                                <a class="nav-link {{ (request()->is('dashboard*')) ? 'active' : '' }}" href="{{url('dashboard')}}">
                                    <i class="fas fa-tachometer-alt text-primary"></i>
                                    <span class="nav-link-text">Dashboard</span></a>
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
                                    <i class="fas fa-users text-orange"></i>
                                    <span class="nav-link-text">Pedagang</span></a>
                            </li>
                            @endif

                            @if(Session::get('role') == 'master' || Session::get('role') == 'manajer' ||  Session::get('role') == 'admin' && (Session::get('otoritas')->tempatusaha))
                            <!-- Nav Item - Tempat Usaha -->
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('tempatusaha*')) ? 'active' : '' }}" href="{{url('tempatusaha')}}">
                                    <i class="fas fa-store text-pink"></i>
                                    <span class="nav-link-text">Tempat&nbsp;Usaha</span></a>
                            </li>
                            @endif

                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->tagihan || Session::get('otoritas')->publish))
                            <!-- Nav Item - Tagihan -->
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('tagihan*')) ? 'active' : '' }}" href="{{url('tagihan')}}">
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
                                    <i class="fas fa-book text-success"></i>
                                    <span class="nav-link-text">Laporan</span>
                                </a>
                                <div class="collapse {{ (request()->is('rekap/*')) ? 'show' : '' }}" id="navbar-laporan">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'manajer' || Session::get('role') == 'admin' && (Session::get('otoritas')->pemakaian))
                                            <a class="nav-link {{ (request()->is('rekap/pemakaian')) ? 'active' : '' }}" href="{{url('rekap/pemakaian')}}">Pemakaian</a>
                                            @endif
                                        </li>
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'manajer' || Session::get('role') == 'admin' && (Session::get('otoritas')->pendapatan))
                                            <a class="nav-link {{ (request()->is('rekap/pendapatan')) ? 'active' : '' }}" href="{{url('rekap/pendapatan')}}">Pendapatan</a>
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
                                    <i class="fas fa-list text-yellow"></i>
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
                                    <i class="fas fa-tools text-pink"></i>
                                    <span class="nav-link-text">Utilities</span>
                                </a>
                                <div class="collapse {{ (request()->is('utilities/*')) ? 'show' : '' }}" id="navbar-utilities">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->tarif))
                                            <a class="nav-link {{ (request()->is('utilities/tarif*')) ? 'active' : '' }}" href="{{url('utilities/tarif')}}">Tarif</a>
                                            @endif
                                        </li>
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->alatmeter))
                                            <a class="nav-link {{ (request()->is('utilities/alatmeter*')) ? 'active' : '' }}" href="{{url('utilities/alatmeter')}}">Alat&nbsp;Meter</a>
                                            @endif
                                        </li>
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->harilibur))
                                            <a class="nav-link {{ (request()->is('utilities/harilibur*')) ? 'active' : '' }}" href="{{url('utilities/harilibur')}}">Hari&nbsp;Libur</a>
                                            @endif
                                        </li>
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->blok))
                                            <a class="nav-link {{ (request()->is('utilities/blok*')) ? 'active' : '' }}" href="{{url('utilities/blok')}}">Blok</a>
                                            @endif
                                        </li>
                                        <li class="nav-item">
                                            @if(Session::get('role') == 'master' || Session::get('role') == 'admin' && (Session::get('otoritas')->simulasi))
                                            <a class="nav-link {{ (request()->is('utilities/simulasi*')) ? 'active' : '' }}" href="{{url('utilities/simulasi')}}">Simulasi</a>
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
                                    <i class="fas fa-dollar-sign text-yellow"></i>
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
                                    <i class="fas fa-history text-primary"></i>
                                    <span class="nav-link-text">Riwayat&nbsp;Login</span></a>
                            </li>
                            @endif
                            
                            <!-- Nav Item - Log -->
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->is('information*')) ? 'active' : '' }}" href="{{url('information')}}">
                                    <i class="fas fa-info text-yellow"></i>
                                    <span class="nav-link-text">Patch Info</span></a>
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
        <script src="{{asset('argon/js/argon.js')}}"></script>
        
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