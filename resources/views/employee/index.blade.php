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
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="feather icon-home"></i></a></li>
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
                                    <input type="text" name="name" class="form-control form-control-sm"
                                        placeholder="Cari nama..." value="{{ request('name') }}">
                                </div>
                                <div class="col mb-2">
                                    <input type="text" name="category" class="form-control form-control-sm"
                                        placeholder="Cari kategori..." value="{{ request('category') }}">
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
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                style="background-color: #3B6790; color: #fff;">
                                Tambah Karyawan
                            </button>
                            <div class="dropdown-menu" aria-labelledby="tambahKaryawanDropdown">
                                <a href="#" class="dropdown-item" data-toggle="modal"
                                    data-target="#modalAddEmployeeIndividu">Tambah secara individu</a>
                                <a href="#" class="dropdown-item" data-toggle="modal"
                                    data-target="#modalAddEmployeeBulk">Tambah secara massal</a>
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
                            <td>{{ $emp->category?->name ?? '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info btn-detail" data-toggle="modal"
                                    data-target="#modalDetailEmployee" data-id="{{ $emp->id }}" data-name="{{ $emp->name }}"
                                    data-category="{{ $emp->category->name ?? '' }}" data-phone="{{ $emp->phone }}"
                                    data-address="{{ $emp->address }}" data-dob="{{ $emp->dob }}" data-email="{{ $emp->email }}"
                                    data-employee_number="{{ $emp->employee_number }}">
                                    <i class="fa fa-eye"></i> Detail
                                </button>

                                {{-- <form action="{{ route('employee.destroy', $emp->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin hapus data?')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between mt-3">
                {{ $employees->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>

    <div class="modal fade" id="modalAddEmployeeBulk" tabindex="-1" role="dialog" aria-labelledby="modalAddEmployeeLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px;">
            <form method="POST" action="{{ route('employee.storeMass') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content rounded-3 shadow-sm">
                    <div class="modal-header bg-light border-bottom-0">
                        <h5 class="modal-title" id="modalAddEmployeeLabel">Tambah Karyawan secara Massal</h5>
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
                            <label for="add_excel_file" class="font-weight-semibold">
                                <i class="fa fa-file mr-2"></i>Masukkan File Excel
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
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddEmployeeIndividu" tabindex="-1" role="dialog"
        aria-labelledby="modalAddEmployeeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px;">
            <form method="POST" action="{{ route('employee.store.individual') }}">
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
                            <input type="text" class="form-control" id="add_name" name="name" value="{{ old('name') }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="add_category" class="font-weight-semibold">
                                <i class="fa fa-briefcase mr-2"></i>Kategori
                            </label>
                            <select class="form-control" id="add_category" name="category" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="add_dob" class="font-weight-semibold">
                                <i class="fa fa-calendar mr-2"></i>Tanggal Lahir
                            </label>
                            <input type="date" class="form-control" id="add_dob" name="dob" value="{{ old('dob') }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="add_address" class="font-weight-semibold">
                                <i class="fa fa-map-marker-alt mr-2"></i>Alamat
                            </label>
                            <textarea class="form-control" id="add_address" name="address"
                                required>{{ old('address') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="add_phone" class="font-weight-semibold">
                                <i class="fa fa-mobile-alt mr-2"></i>Nomor Telepon
                            </label>
                            <input type="text" class="form-control" id="add_phone" name="phone" value="{{ old('phone') }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="add_email" class="font-weight-semibold">
                                <i class="fa fa-envelope mr-2"></i>Email
                            </label>
                            <input type="email" class="form-control" id="add_email" name="email" value="{{ old('email') }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="add_employee_number" class="font-weight-semibold">
                                <i class="fa fa-id-badge mr-2"></i>Nomor Pegawai
                            </label>
                            <input type="text" class="form-control" id="add_employee_number" name="employee_number"
                                value="{{ old('employee_number') }}" required>
                        </div>

                    </div>

                    <div class="modal-footer border-top-0 px-4 py-2 justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm px-4">SIMPAN</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.btn-detail').click(function () {
                $('#detail_name').text($(this).data('name'));
                $('#detail_category').text($(this).data('category') ?? '-');
                $('#detail_dob').text($(this).data('dob'));
                $('#detail_email').text($(this).data('email'));
                $('#detail_phone').text($(this).data('phone'));
                $('#detail_address').text($(this).data('address'));
                $('#detail_employee_number').text($(this).data('employee_number'));
            });
        });
    </script>
@endsection