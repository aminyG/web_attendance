@extends('layouts.master')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <h5 class="m-b-10">Ubah Status Absensi</h5>
                </div>
            </div>

            <form action="{{ route('attendance.update', $attendance->id) }}" method="POST" class="p-4 rounded shadow-sm"
                style="background-color: #f8f9fa;">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nama Karyawan:</label>
                    <p>{{ $attendance->employee->name }}</p>
                </div>

                <div class="form-group">
                    <label>Tanggal:</label>
                    <p>{{ $attendance->date }}</p>
                </div>

                <div class="form-group">
                    <label for="status">Status Absensi</label>
                    <select name="status" id="status" class="form-control">
                        @foreach(['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpha'] as $status)
                            <option value="{{ $status }}" {{ $attendance->status === $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary mt-3">Batal</a>
            </form>
        </div>
    </div>
@endsection