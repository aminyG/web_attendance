@extends('layouts.master')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <h5 class="m-b-10">Dashboard Super Admin</h5>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Super Admin Dashboard</a></li>
                    </ul>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="white-space: pre-wrap;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5>Kelola Admin</h5>
                    <div class="card-header-right">
                        <a href="{{ route('superadmin.admins.create') }}" class="btn btn-primary btn-sm">Tambah Admin</a>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Dibuat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($admins as $admin)
                                <tr>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->created_at->format('d M Y') }}</td>
                                    <td>
                                        {{-- <a href="{{ route('superadmin.impersonate.start', $admin->id) }}"
                                            class="btn btn-info btn-sm">
                                            Masuk sebagai Admin
                                        </a> --}}
                                        <form action="{{ route('superadmin.admins.destroy', $admin->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin mau menghapus admin ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada admin terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if (session()->has('impersonate'))
                <div class="alert alert-warning mt-3">
                    Anda sedang masuk sebagai Admin: {{ auth()->user()->name }}
                    <a href="{{ route('superadmin.impersonate.stop') }}" class="btn btn-outline-dark btn-sm">Kembali ke
                        Superadmin</a>
                </div>
            @endif
        </div>
    </div>
@endsection