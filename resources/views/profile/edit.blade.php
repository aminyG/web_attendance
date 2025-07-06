@extends('layouts.master')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <h5 class="m-b-10">Profil Pengguna</h5>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Profil</a></li>
                    </ul>
                </div>
            </div>

            <!-- Menampilkan pesan sukses atau error -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Form untuk Update Profil -->
            <form action="{{ route('profile.update') }}" method="POST" class="mb-3 p-3 rounded"
                style="background-color: #f8f9fa;">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Nama -->
                    <div class="col">
                        <label for="name" class="small mb-1">Nama</label>
                        <input type="text" class="form-control form-control-sm" id="name" name="name"
                            value="{{ old('name', $user->name) }}" required>
                    </div>

                    <!-- Email -->
                    <div class="col">
                        <label for="email" class="small mb-1">Email</label>
                        <input type="email" class="form-control form-control-sm" id="email" name="email"
                            value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-primary">Update Profil</button>
                </div>
            </form>

            <hr>

            <!-- Form untuk Update Password -->
            <form action="{{ route('profile.password.update') }}" method="POST" class="mb-3 p-3 rounded"
                style="background-color: #f8f9fa;">
                @csrf
                @method('PUT')

                <!-- Password Lama -->
                <div class="form-group">
                    <label for="current_password" class="small mb-1">Password Lama</label>
                    <input type="password" class="form-control form-control-sm" id="current_password"
                        name="current_password" required>
                </div>

                <!-- Password Baru -->
                <div class="form-group">
                    <label for="password" class="small mb-1">Password Baru</label>
                    <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="form-group">
                    <label for="password_confirmation" class="small mb-1">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control form-control-sm" id="password_confirmation"
                        name="password_confirmation" required>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-sm btn-warning">Update Password</button>
                </div>
            </form>

        </div>
    </div>
@endsection