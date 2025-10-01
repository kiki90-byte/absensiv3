@extends('layouts.app')
@section('titlepage', 'Jam Kerja Divisi')

@section('content')
@section('navigasi')
    <span>Jam Kerja Divisi</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('jamkerja.create')
                    <a href="#" class="btn btn-primary" id="btncreateJamKerja"><i class="fa fa-plus me-2"></i> Tambah
                        Jam Kerja</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ URL::current() }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <select name="kode_unit" id="kode_unit" class="form-select select2Kodeunit">
                                            <option value="">Semua Unit</option>
                                            @foreach ($unit as $c)
                                                <option value="{{ $c->kode_unit }}"
                                                    {{ Request('kode_unit') == $c->kode_unit ? 'selected' : '' }}>
                                                    {{ textUpperCase($c->nama_unit) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode</th>
                                        <th>Unit</th>
                                        <th>Divisi</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jamkerjabydiv as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $jamkerjabydiv->firstItem() - 1 }}</td>
                                            <td>{{ $d->kode_jk_div }}</td>
                                            <td class="text-uppercase">{{ $d->nama_unit }}</td>
                                            <td class="text-uppercase">{{ $d->nama_div }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('jamkerjabydiv.edit')
                                                        <a href="#" class="btnEdit me-1" kode_jk_div="{{ Crypt::encrypt($d->kode_jk_div) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endcan
                                                    @can('jamkerjabydiv.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('jamkerjabydiv.delete', Crypt::encrypt($d->kode_jk_div)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{-- {{ $jamkerja->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateJamKerja" size="" show="loadcreateJamKerja" title="" />

@endsection
@push('myscript')
<script>
    $(function() {
        $("#btncreateJamKerja").click(function(e) {
            $('#mdlcreateJamKerja').modal("show");
            $("#mdlcreateJamKerja").find(".modal-title").text("Tambah Jam Kerja");
            $("#loadcreateJamKerja").load('/jamkerjabydiv/create');

        });

        $(".btnEdit").click(function(e) {
            var kode_jk_dept = $(this).attr("kode_jk_div");
            e.preventDefault();
            $('#mdlcreateJamKerja').modal("show");
            $("#mdlcreateJamKerja").find(".modal-title").text("Edit Jam Kerja");
            $("#loadcreateJamKerja").load('/jamkerjabydiv/' + kode_jk_div + '/edit');
        });
    });
</script>
@endpush
