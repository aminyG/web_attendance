@extends('layouts.master')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <h5 class="m-b-10">Pengaturan Jumlah Absensi per Kategori</h5>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Kategori</a></li>
                    </ul>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Hapus card supaya background putih hilang --}}
            <form action="{{ route('categories.updateAttendance') }}" method="POST">
                @csrf

                {{-- <div class="table-responsive"> --}}
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KATEGORI</th>
                                <th>JUMLAH ABSEN / HARI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $category)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <input type="number" name="attendances[{{ $category->id }}]"
                                            class="form-control form-control-sm" min="1" required
                                            value="{{ $category->required_attendance_per_day ?? 2 }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{--
                </div> --}}

                <div class="mt-3 text-right">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection