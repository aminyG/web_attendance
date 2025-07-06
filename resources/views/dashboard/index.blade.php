@extends('layouts.master')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <h5 class="m-b-10">Dashboard Absensi</h5>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Dashboard</a></li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5>Total Karyawan</h5>
                            <h3 class="text-c-blue">{{ $employeeCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5>Total Kategori</h5>
                            <h3 class="text-c-green">{{ $categoryCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5>Total Lokasi</h5>
                            <h3 class="text-c-purple">{{ $locationCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5>Absensi Hari Ini</h5>
                            <h3 class="text-c-red">{{ $todayAttendanceCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($todayAttendanceStatus as $status => $count)
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <h6>{{ $status }}</h6>
                                <h4>{{ $count }}</h4>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card table-card">
                <div class="card-header">
                    <h5>Absensi Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Karyawan</th>
                                    <th>Kategori</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestAttendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->employee->name }}</td>
                                        <td>{{ $attendance->employee->category->name ?? '-' }}</td>
                                        <td>{{ $attendance->date }}</td>
                                        <td>{{ $attendance->time }}</td>
                                        <td>{{ $attendance->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection