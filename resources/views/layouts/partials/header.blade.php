<!-- [ Header ] start -->
<header class="navbar pcoded-header navbar-expand-lg navbar-light header-blue">


    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
        <a href="#!" class="b-brand">
            <!-- ========   change your logo hear   ============ -->
            <img src="{{ asset('assets/images/logo.png')}}" alt="" class="logo">
            {{-- <img src="assets/images/logo-icon.png" alt="" class="logo-thumb"> --}}
        </a>
        <a href="#!" class="mob-toggler">
            <i class="feather icon-more-vertical"></i>
        </a>
    </div>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="#!" class="pop-search"><i class="feather icon-search"></i></a>
                <div class="search-bar">
                    <input type="text" class="form-control border-0 shadow-none" placeholder="Search hear">
                    <button type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li>
                <div class="dropdown drp-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="feather icon-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-notification">
                        <div class="pro-head">
                            <img src="{{ asset('assets/images/user/avatar.jpg')}}" class="img-radius"
                                alt="User-Profile-Image">
                            <!-- Cek jika pengguna terautentikasi -->
                            @auth
                                <span>{{ auth()->user()->name }}</span>
                            @else
                                <span>Guest</span>
                            @endauth
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a href="#" class="dud-logout" title="Logout"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="feather icon-log-out"></i>
                            </a>

                        </div>
                        <ul class="pro-body">
                            <li><a href="{{route('profile.edit')  }}" class="dropdown-item"><i
                                        class="feather icon-user"></i>
                                    Profile</a></li>
                            {{-- <li><a href="auth-signin.html" class="dropdown-item"><i class="feather icon-lock"></i>
                                    Lock
                                    Screen</a></li> --}}
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>


</header>
<!-- [ Header ] end -->