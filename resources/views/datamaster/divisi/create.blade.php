<form action="{{ route('divisi.store') }}" method="POST" id="formDivisi">
   @csrf
   <x-input-with-icon label="Kode Divisi" name="kode_div" icon="ti ti-barcode" />
   <x-input-with-icon label="Nama Divisi" name="nama_div" icon="ti ti-building" />
   <di class="form-group mb-3">
      <button class="btn btn-primary w-100"><i class="ti ti-send me-1"></i> Submit</button>
   </di>
</form>
<script src="{{ asset('assets/js/pages/divisi.js') }}"></script>
