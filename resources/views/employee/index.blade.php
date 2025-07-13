@php use Illuminate\Support\Str; @endphp

@extends('layouts.master')

@section('content')
        <div class="pcoded-main-container">
            <div class="pcoded-content">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="page-header">
                    <div class="page-block">
                        <h5 class="m-b-10">Karyawan</h5>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="feather icon-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#!">Karyawan</a></li>
                        </ul>
                    </div>
                </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
<div class="mb-3 p-3 rounded" style="background-color: #f8f9fa; margin-top: 15px;">
    <div class="row align-items-end">
        <div class="col">
            <form method="GET" action="{{ route('employee.index') }}">
                <div class="form-row">
                    <div class="col mb-2">
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Cari nama..." value="{{ request('name') }}">
                    </div>
                    <div class="col mb-2">
                        <input type="text" name="category" class="form-control form-control-sm" placeholder="Cari kategori..." value="{{ request('category') }}">
                    </div>
                    <div class="col-auto mb-2">
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('employee.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-auto mb-2">
            <div class="dropdown">
                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="tambahKaryawanDropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #3B6790; color: #fff;">
                    Tambah Karyawan
                </button>
                <div class="dropdown-menu" aria-labelledby="tambahKaryawanDropdown">
                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modalAddEmployeeIndividu">Tambah secara individu</a>
                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modalAddEmployeeBulk">Tambah secara massal</a>
                </div>
            </div>
        </div>
    </div>
</div>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $emp)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $emp['name'] }}</td>
                                                        {{-- <td>{{ $emp['category'] }}</td> --}}
                                                        <td>{{ $emp->category?->name ?? '-' }}</td>
                                                        <td>
                                <!-- Tombol Detail -->
                                <button type="button" class="btn btn-sm btn-info btn-detail" data-toggle="modal"
                                    data-target="#modalDetailEmployee"
                                    data-id="{{ $emp->id }}"
                                    data-name="{{ $emp->name }}"
                                  data-category="{{ $emp->category->name ?? '' }}"
                                    data-phone="{{ $emp->phone }}"
                                    data-address="{{ $emp->address }}"
                                    data-dob="{{ $emp->dob }}"
                                    data-email="{{ $emp->email }}"
                                    data-employee_number="{{ $emp->employee_number }}"
                                    {{-- data-photo="{{ $emp->photo ? asset('storage/' . $emp->photo) : '' }}" --}}
                                    {{-- data-photo="{{ $emp->photo ?? '' }}"> --}}
                                    data-photo="{{ Str::startsWith($emp->photo, 'http') ? $emp->photo : asset('storage/' . $emp->photo) }}">
                                    <i class="fa fa-eye"></i> Detail
                                </button>

                                <!-- Tombol Edit -->
                                <button type="button" class="btn btn-sm btn-warning btn-edit" data-toggle="modal"
                                    data-target="#modalEditEmployee"
                                    data-id="{{ $emp->id }}"
                                    data-name="{{ $emp->name }}"
                                    data-category="{{ $emp->category->name ?? '' }}"
                                    data-phone="{{ $emp->phone }}"
                                    data-address="{{ $emp->address }}"
                                    data-dob="{{ $emp->dob }}"
                                    data-email="{{ $emp->email }}"
                                    data-employee_number="{{ $emp->employee_number }}"
                                    {{-- data-photo="{{ $emp->photo ? asset('storage/' . $emp->photo) : '' }}" --}}
                                    data-photo="{{ Str::startsWith($emp->photo, 'http') ? $emp->photo : asset('storage/' . $emp->photo) }}">
                                    <i class="fa fa-edit"></i> Edit
                                </button>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('employee.destroy', $emp->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin hapus data?')"><i class="fa fa-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


       <!-- Modal Tambah Karyawan Individu -->
    <div class="modal fade" id="modalAddEmployeeIndividu" tabindex="-1" role="dialog"
        aria-labelledby="modalAddEmployeeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px;">
            <form method="POST" action="{{ route('employee.store.individual') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content rounded-3 shadow-sm">
                    <div class="modal-header bg-light border-bottom-0">
                        <h5 class="modal-title" id="modalAddEmployeeLabel">Tambah Karyawan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="font-size: 1.5rem;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body px-4 py-3">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="add_name" class="font-weight-semibold">
                                <i class="fa fa-user mr-2"></i>Nama Lengkap
                            </label>
                            <input type="text" class="form-control" id="add_name" name="name"value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="add_category" class="font-weight-semibold">
                                <i class="fa fa-briefcase mr-2"></i>Kategori
                            </label>
                            <input type="text" class="form-control" id="add_category" name="category"
                                value="{{ old('category') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="add_dob" class="font-weight-semibold">
                                <i class="fa fa-calendar mr-2"></i>Tanggal Lahir
                            </label>
                            <input type="date" class="form-control" id="add_dob" name="dob"
                                value="{{ old('dob') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="add_address" class="font-weight-semibold">
                                <i class="fa fa-map-marker-alt mr-2"></i>Alamat
                            </label>
                            <textarea class="form-control" id="add_address" name="address" required>{{ old('address') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="add_phone" class="font-weight-semibold">
                                <i class="fa fa-mobile-alt mr-2"></i>Nomor Telepon
                            </label>
                            <input type="text" class="form-control" id="add_phone" name="phone"
                                value="{{ old('phone') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="add_email" class="font-weight-semibold">
                                <i class="fa fa-envelope mr-2"></i>Email
                            </label>
                            <input type="email" class="form-control" id="add_email" name="email"
                                value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="add_employee_number" class="font-weight-semibold">
                                <i class="fa fa-id-badge mr-2"></i>Nomor Pegawai
                            </label>
                            <input type="text" class="form-control" id="add_employee_number" name="employee_number"
                                value="{{ old('employee_number') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="add_photo_individual" class="font-weight-semibold">
                                <i class="fa fa-camera mr-2"></i>Foto
                            </label>
                            <input type="file" class="form-control-file" id="add_photo_individual" name="photo" accept="image/*">
                        </div>
                    </div>

                    <div class="modal-footer border-top-0 px-4 py-2 justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm px-4">SIMPAN</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

        <!-- Modal Tambah Karyawan Massal -->
        <div class="modal fade" id="modalAddEmployeeBulk" tabindex="-1" role="dialog" aria-labelledby="modalAddEmployeeLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px;">
                <form method="POST" action="{{ route('employee.storeMass') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content rounded-3 shadow-sm">
                        <div class="modal-header bg-light border-bottom-0">
                            <h5 class="modal-title" id="modalAddEmployeeLabel">Tambah Karyawan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                style="font-size: 1.5rem;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body px-4 py-3">
                            @if ($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="add_photo" class="font-weight-semibold">
                                    <i class="fa fa-file mr-2"></i>Masukkan File
                                </label>
                                <input type="file" class="form-control-file" id="add_excel_file" name="file" accept=".xlsx,.xls"
                                    required>
                            </div>
                        </div>

                        <div class="modal-footer border-top-0 px-4 py-2 justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm px-4">SIMPAN</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Detail Karyawan -->
    <div class="modal fade" id="modalDetailEmployee" tabindex="-1" role="dialog" aria-labelledby="modalDetailEmployeeLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailEmployeeLabel">Detail Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Nama:</strong> <span id="detail_name"></span></p>
                    <p><strong>Kategori:</strong> <span id="detail_category"></span></p>
                    <p><strong>Tanggal Lahir:</strong> <span id="detail_dob"></span></p>
                    <p><strong>Alamat:</strong> <span id="detail_address"></span></p>
                    <p><strong>Nomor Telepon:</strong> <span id="detail_phone"></span></p>
                    <p><strong>Email:</strong> <span id="detail_email"></span></p>
                    <p><strong>Nomor Pegawai:</strong> <span id="detail_employee_number"></span></p>
                    <p><strong>Foto:</strong> <span id="detail_photo"></span></p>
                </div>
            </div>
        </div>
    </div>

        <!-- Modal Edit Karyawan -->
        <div class="modal fade" id="modalEditEmployee" tabindex="-1" role="dialog" aria-labelledby="modalEditEmployeeLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="POST" id="formEditEmployee" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditEmployeeLabel">Edit Karyawan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>x
                        <div class="modal-body">
                            <input type="hidden" id="edit_id" name="id">
                            <div class="form-group">
                                <label for="edit_name">Nama</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_category">Kategori</label>
                                <input type="text" class="form-control" id="edit_category" name="category" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_dob">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="edit_dob" name="dob">
                            </div>
                            <div class="form-group">
                                <label for="edit_address">Alamat</label>
                                <textarea class="form-control" id="edit_address" name="address" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_email">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email">
                            </div>
                            <div class="form-group">
                                <label for="edit_phone">Nomor Telepon</label>
                                <input type="text" class="form-control" id="edit_phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_employee_number">Nomor Pegawai</label>
                                <input type="text" class="form-control" id="edit_employee_number" name="employee_number">
                            </div>
                            {{-- <div class="form-group">
        <label for="edit_photo">Foto</label>
        <input type="file" class="form-control-file" id="edit_photo" name="photo" accept="image/*">
        <div id="current_photo_preview" class="mt-2"></div>
                        </div> --}}
                        <div class="form-group">
        <label for="edit_photo">Foto</label>

        {{-- Preview foto lama --}}
        <div id="current_photo_preview" class="mb-2">
            {{-- Diisi lewat JS --}}
        </div>

        {{-- Input ubah foto --}}
        <input type="file" class="form-control-file" id="edit_photo" name="photo" accept="image/*">
    </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Modal DETAIL
            $('.btn-detail').click(function () {
                // console.log('DETAIL BUTTON CLICKED');
                // console.log($(this).data());
                $('#detail_name').text($(this).data('name'));
                $('#detail_category').text($(this).data('category') ?? '-');
                $('#detail_dob').text($(this).data('dob'));
                $('#detail_email').text($(this).data('email'));
                $('#detail_phone').text($(this).data('phone'));
                $('#detail_address').text($(this).data('address'));
                $('#detail_employee_number').text($(this).data('employee_number'));
                // $('#detail_photo').html($(this).data('photo') ? `<img src="${$(this).data('photo')}" class="img-fluid" width="150">` : 'Tidak ada foto');
                $('#detail_photo').html($(this).data('photo') 
        ? `<img src="${$(this).data('photo').includes('http') ? $(this).data('photo') : '/storage/' + $(this).data('photo')}" class="img-fluid" width="150">` 
        : 'Tidak ada foto');
            });

            // Modal EDIT
            $('.btn-edit').click(function () {
                $('#edit_id').val($(this).data('id'));
                $('#edit_name').val($(this).data('name'));
                $('#edit_category').val($(this).data('category') ?? '');
                $('#edit_dob').val($(this).data('dob'));
                $('#edit_email').val($(this).data('email'));
                $('#edit_phone').val($(this).data('phone'));
                $('#edit_address').val($(this).data('address'));
                $('#edit_employee_number').val($(this).data('employee_number'));
    const photoUrl = $(this).data('photo');
    if (photoUrl) {
        const src = photoUrl.includes('http') ? photoUrl : '/storage/' + photoUrl;
        $('#current_photo_preview').html(`<img src="${src}" class="img-fluid mb-2" width="100">`);
    } else {
        $('#current_photo_preview').html('<em>Tidak ada foto</em>');
    }
                // Set action form edit
                var id = $(this).data('id');
                $('#formEditEmployee').attr('action', '/employee/' + id);
            });
        });
    </script>
@endsection
