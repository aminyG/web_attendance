<nav class="pcoded-navbar menu-light ">
    <div class="navbar-wrapper  ">
        <div class="navbar-content scroll-div ">

            <div class="">
                <div class="main-menu-header">
                    <img class="img-radius" src="{{ asset('assets/images/user/avatar-2.jpg')}}"
                        alt="User-Profile-Image">

                    <div class="user-details">
                        <div id="more-details">Admin<i class="fa fa-caret-down"></i></div>
                    </div>
                </div>
                <div class="collapse" id="nav-user-link">
                    <ul class="list-unstyled">
                        <li class="list-group-item"><a href="{{route('profile.edit')  }}"><i
                                    class="feather icon-user m-r-5"></i>View Profile</a></li>
                        <li class="list-group-item"><a href="#" title="Logout"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                    class="feather icon-log-out m-r-5"></i>Logout</a>
                        </li>
                        </a>
                    </ul>
                </div>
            </div>

            <ul class="nav pcoded-inner-navbar ">
                <li class="nav-item pcoded-menu-caption">
                    <label>Navigation</label>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link "><span class="pcoded-micon"><i
                                class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                </li>
                <li class="nav-item">
                    <a href="{{route('employee.index')  }}" class="nav-link "><span class="pcoded-micon"><i
                                class="feather icon-user"></i></span><span class="pcoded-mtext">Karyawan</span></a>
                </li>
                <li class="nav-item">
                    <a href="{{route('attendance.index') }}" class="nav-link "><span class="pcoded-micon"><i
                                class="feather icon-calendar"></i></span><span class="pcoded-mtext">Absensi</span></a>
                </li>
                <li class="nav-item pcoded-menu-caption">
                    <label>SETTINGS</label>
                </li>
                <li class="nav-item">
                    <a href="{{ route('categories.attendanceSettings') }}" class="nav-link "><span
                            class="pcoded-micon"><i class="feather icon-layers"></i></span><span
                            class="pcoded-mtext">Kategori
                            Karyawan</span></a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('schedule.index') }}" class="nav-link "><span class="pcoded-micon"><i
                                class="feather icon-grid"></i></span><span class="pcoded-mtext">Jadwal</span></a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('locations.index') }}" class="nav-link "><span class="pcoded-micon"><i
                                class="feather icon-map-pin"></i></span><span class="pcoded-mtext">Lokasi</span></a>
                </li>
            </ul>
        </div>
    </div>
</nav>