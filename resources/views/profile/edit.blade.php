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

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Form Update Profile -->
            <form action="{{ route('profile.update') }}" method="POST" class="mb-3 p-3 rounded"
                style="background-color: #f8f9fa;">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col">
                        <label for="name" class="small mb-1">Nama</label>
                        <input type="text" class="form-control form-control-sm" id="name" name="name"
                            value="{{ old('name', $user->name) }}" required>
                    </div>

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

            <!-- Form Update Password -->
            <form method="POST" action="{{ route('profile.password.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="current_password" class="small mb-1">Password Lama</label>
                    <input type="password" name="current_password" class="form-control" required>
                    @error('current_password') <div class="text-red-500">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="small mb-1">Password Baru</label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password') <div class="text-red-500">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="small mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                    @error('password_confirmation') <div class="text-red-500">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-warning">Update Password</button>
            </form>


        </div>
    </div>
@endsection