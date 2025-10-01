@extends('layouts.app')
@section('titlepage', 'Divisi')

@section('content')
@section('navigasi')
   <span>Divisi</span>
@endsection

<div class="row">
   <div class="col-lg-6 col-sm-12 col-xs-12">
      <div class="card">
         <div class="card-header">
            @can('divisi.create')
               <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah
                  Divisi</a>
            @endcan
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-12">
                  <form action="{{ route('divisi.index') }}">
                     <div class="row">
                        {{-- <div class="col-lg-4 col-sm-12 col-md-12">
                           <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}"
                              name="nama_karyawan" icon="ti ti-search" />
                        </div>
                        <div class="col-lg-2 col-sm-12 col-md-12">
                           <button class="btn btn-primary"><i
                                 class="ti ti-icons ti-search me-1"></i>Cari</button>
                        </div> --}}
                     </div>

                  </form>
               </div>
            </div>
            <div class="row">
               <div class="col-12">
                  <div class="table-responsive mb-2">
                     <table class="table  table-hover table-bordered table-striped">
                        <thead class="table-dark">
                           <tr>
                              <th>Kode Div</th>
                              <th>Nama Divisi</th>
                              <th>#</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($divisi as $d)
                              <tr>
                                 <td>{{ $d->kode_div }}</td>
                                 <td>{{ $d->nama_div }}</td>
                                 <td>
                                    <div class="d-flex">
                                       @can('divisi.edit')
                                          <div>
                                             <a href="#" class="me-2 btnEdit"
                                                kode_dept="{{ Crypt::encrypt($d->kode_dept) }}">
                                                <i class="ti ti-edit text-success"></i>
                                             </a>
                                          </div>
                                       @endcan

                                       @can('divisi.delete')
                                          <div>
                                             <form method="POST" name="deleteform" class="deleteform"
                                                action="{{ route('divisi.delete', Crypt::encrypt($d->kode_div)) }}">
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
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<x-modal-form id="modal" show="loadmodal" />
@endsection
@push('myscript')
<script>
   $(function() {

      function loading() {
         $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
      };
      loading();

      $("#btnCreate").click(function() {
         $("#modal").modal("show");
         $(".modal-title").text("Tambah Data Divisi");
         $("#loadmodal").load("{{ route('divisi.create') }}");
      });


      $(".btnEdit").click(function() {
         loading();
         const kode_dept = $(this).attr("kode_div");
         $("#modal").modal("show");
         $(".modal-title").text("Edit Divisi");
         $("#loadmodal").load(`/divisi/${kode_div}`);
      });
   });
</script>
@endpush
