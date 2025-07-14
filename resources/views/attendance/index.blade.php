@extends('layouts.master')

@section('content')
    <div class="pcoded-main-container">
        {{-- <div class="container"> --}}
            <div class="pcoded-content">
                {{-- <h1>Riwayat Absensi</h1> --}}
                <div class="page-header">
                    <div class="page-block">
                        <h5 class="m-b-10">Riwayat Absensi</h5>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="feather icon-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#!">Absensi</a></li>
                        </ul>
                    </div>
                </div>

                <form method="GET" action="{{ route('attendance.index') }}" class="mb-3 p-3 rounded"
                    style="background-color: #f8f9fa;">
                    <div class="row align-items-end">
                        <div class="col">
                            <label for="category_id" class="small mb-1">Kategori</label>
                            <select name="category_id" id="category_id" class="form-control form-control-sm">
                                <option value="">-- Semua Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="employee_id" class="small mb-1">Karyawan</label>
                            <select name="employee_id" id="employee_id" class="form-control form-control-sm">
                                <option value="">-- Semua Karyawan --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="date_from" class="small mb-1">Dari Tanggal</label>
                            <input type="date" name="date_from" id="date_from" class="form-control form-control-sm"
                                value="{{ request('date_from') }}">
                        </div>
                        <div class="col">
                            <label for="date_to" class="small mb-1">Sampai Tanggal</label>
                            <input type="date" name="date_to" id="date_to" class="form-control form-control-sm"
                                value="{{ request('date_to') }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                            <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Karyawan</th>
                            <th>Kategori</th>
                            <th>Absen</th>
                            <th>Jam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->date }}</td>
                                <td>{{ $attendance->employee->name }}</td>
                                <td>{{ $attendance->employee->category->name ?? '-' }}</td>
                                <td>{{ $attendance->schedule->name ?? '-' }}</td>
                                <td>{{ $attendance->time }}</td>
                                <td>
                                    @php
                                        switch($attendance->status) {
                                            case 'Hadir': $badge = 'success'; break;
                                            case 'Sakit': $badge = 'info'; break;
                                            case 'Izin': $badge = 'warning'; break;
                                            case 'Terlambat': $badge = 'primary'; break;
                                            case 'Alpha': $badge = 'danger'; break;
                                            default: $badge = 'secondary'; break;
                                        }
                                    @endphp
                                    <span class="badge badge-{{ $badge }}">{{ $attendance->status }}</span>
                                    
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-sm btn-warning ml-2">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- {{ $attendances->links() }} --}}
                {{ $attendances->links('pagination::simple-bootstrap-4') }}

            </div>
        </div>


@endsection