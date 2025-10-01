<form action="{{ route('divisi.update', Crypt::encrypt($divisi->kode_div)) }}" method="POST" id="formDivisi">
   @csrf
   @method('PUT')
   <x-input-with-icon label="Kode Divisi" name="kode_div" icon="ti ti-barcode" value="{{ $div->kode_div }}" />
   <x-input-with-icon label="Nama Divisi" name="nama_div" icon="ti ti-building" value="{{ $divisi->nama_div}}" />
   <di class="form-group mb-3">
      <button class="btn btn-primary w-100"><i class="ti ti-send me-1"></i> Submit</button>
   </di>
</form>
<script src="{{ asset('assets/js/pages/divisi.js') }}"></script>
