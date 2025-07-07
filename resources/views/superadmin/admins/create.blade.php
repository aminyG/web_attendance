@extends('layouts.master')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <h5 class="m-b-10">Tambah Admin Baru</h5>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard Super Admin</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Tambah Admin</a></li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('superadmin.admins.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Admin</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                value="{{ old('name') }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email Admin</label>
                            <input type="email" name="email" id="email" class="form-control" required
                                value="{{ old('email') }}">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection