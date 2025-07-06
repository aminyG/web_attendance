@extends('layouts.master')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <h5 class="m-b-10">Lokasi Absensi</h5>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Lokasi</a></li>
                    </ul>
                </div>
            </div>
            <h6 class="text-2xl font-semibold mb-4">Daftar Lokasi Absensi</h6>

            @if(session('success'))
                <div class="bg-green-200 text-green-700 p-3 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('locations.create') }}" class="btn btn-primary mb-3">Tambah Lokasi</a>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Radius (m)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $location)
                        <tr>
                            <td>{{ $location->name }}</td>
                            <td>{{ $location->latitude }}</td>
                            <td>{{ $location->longitude }}</td>
                            <td>{{ $location->radius }}</td>
                            <td>
                                <form action="{{ route('locations.setActive', $location) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button class="btn btn-sm btn-success" {{ $location->is_active ? 'disabled' : '' }}>
                                        {{ $location->is_active ? 'Aktif' : 'Set Aktif' }}
                                    </button>
                                </form>
                                <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('locations.destroy', $location) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus lokasi ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $locations->links() }}
        </div>
    </div>
@endsection