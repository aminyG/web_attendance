@extends('layouts.master')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <h5 class="m-b-10">Pengaturan Kategori</h5>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Kategori</a></li>
                    </ul>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="mb-3 text-right">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddCategory">Tambah Kategori</button>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>KATEGORI</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                    data-target="#modalEditCategory" data-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}">
                                    Edit
                                </button>

                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this category?');"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalAddCategory" tabindex="-1" role="dialog" aria-labelledby="modalAddCategoryLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px;">
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf
                <div class="modal-content rounded-3 shadow-sm">
                    <div class="modal-header bg-light border-bottom-0">
                        <h5 class="modal-title" id="modalAddCategoryLabel">Tambah Kategori Baru</h5>
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
                            <label for="name" class="font-weight-semibold">
                                <i class="fa fa-briefcase mr-2"></i>Nama Kategori
                            </label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
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

    <div class="modal fade" id="modalEditCategory" tabindex="-1" role="dialog" aria-labelledby="modalEditCategoryLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px;">
            <form method="POST" id="formEditCategory" action="">
                @csrf
                @method('PUT')
                <div class="modal-content rounded-3 shadow-sm">
                    <div class="modal-header bg-light border-bottom-0">
                        <h5 class="modal-title" id="modalEditCategoryLabel">Edit Kategori</h5>
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
                            <label for="edit_name" class="font-weight-semibold">
                                <i class="fa fa-briefcase mr-2"></i>Nama Kategori
                            </label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
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
        $('#modalEditCategory').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var categoryId = button.data('id');
            var categoryName = button.data('name');

            var modal = $(this);
            modal.find('#edit_name').val(categoryName);
            modal.find('#formEditCategory').attr('action', '/categories/' + categoryId);
        });
    </script>
@endsection