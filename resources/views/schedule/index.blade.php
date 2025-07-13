@extends('layouts.master')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="page-header">
                <div class="page-block">
                    <h5 class="m-b-10">Schedule Management</h5>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="feather icon-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Jadwal</a></li>
                    </ul>
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4" id="categoryTabs" role="tablist">
                @foreach($categories as $index => $cat)
                    <li class="nav-item">
                        <a class="nav-link {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $cat->id }}" data-toggle="tab"
                            href="#cat-{{ $cat->id }}" role="tab">
                            {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
                <!-- Tab untuk Semua Kategori -->
                {{-- <li class="nav-item">
                    <a class="nav-link" id="tab-all" data-toggle="tab" href="#cat-all" role="tab">
                        Semua Kategori
                    </a>
                </li> --}}
            </ul>

            <div class="tab-content" id="categoryTabsContent">
                @foreach($categories as $index => $cat)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="cat-{{ $cat->id }}" role="tabpanel">
                        <h4 class="mb-3">Kategori: {{ $cat->name }}</h4>

                        <!-- Form untuk Menambahkan Jadwal -->
                        <form action="{{ route('schedule.store') }}" method="POST" class="mb-3">
                            @csrf
                            <input type="hidden" name="category_id" value="{{ $cat->id }}">
                            <div class="form-row">
                                <div class="col">
                                    <input type="text" name="name" class="form-control" placeholder="Nama Jadwal (cth: Masuk)"
                                        required>
                                </div>
                                <div class="col">
                                    <input type="time" name="start_time" class="form-control" required>
                                </div>
                                <div class="col">
                                    <input type="time" name="end_time" class="form-control" required>
                                </div>
                                <div class="col">
                                    <input type="number" name="order" class="form-control" placeholder="Urutan (optional)">
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-success btn-block">Tambah</button>
                                </div>
                            </div>
                        </form>

                        @if($cat->schedules->count())
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Urutan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cat->schedules->sortBy('order') as $s)
                                        <tr>
                                            <td>{{ $s->name }}</td>
                                            <td>{{ $s->start_time }}</td>
                                            <td>{{ $s->end_time }}</td>
                                            <td>{{ $s->order }}</td>
                                            <td>
                                                <form action="{{ route('schedule.destroy', $s->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">Belum ada jadwal.</p>
                        @endif
                    </div>
                @endforeach

                <!-- Tab untuk Semua Kategori -->
                <div class="tab-pane fade" id="cat-all" role="tabpanel">
                    <h4 class="mb-3">Semua Kategori</h4>

                    <!-- Form untuk Menambahkan Jadwal ke Semua Kategori -->
                    <form action="{{ route('schedule.storeAll') }}" method="POST" class="mb-3">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <input type="text" name="name" class="form-control" placeholder="Nama Jadwal (cth: Masuk)"
                                    required>
                            </div>
                            <div class="col">
                                <input type="time" name="start_time" class="form-control" required>
                            </div>
                            <div class="col">
                                <input type="time" name="end_time" class="form-control" required>
                            </div>
                            <div class="col">
                                <input type="number" name="order" class="form-control" placeholder="Urutan (optional)">
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-block">Setel ke Semua Kategori</button>
                            </div>
                        </div>
                    </form>

                    <!-- Tampilkan Jadwal Global (Untuk Semua Kategori) -->
                    @if($allSchedules->count())
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Urutan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allSchedules as $s)
                                    <tr>
                                        <td>{{ $s->name }}</td>
                                        <td>{{ $s->start_time }}</td>
                                        <td>{{ $s->end_time }}</td>
                                        <td>{{ $s->order }}</td>
                                        <td>
                                            <form action="{{ route('schedule.destroy', $s->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Belum ada jadwal untuk semua kategori.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection