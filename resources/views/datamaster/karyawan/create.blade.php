<form action="{{ route('karyawan.store') }}" id="formcreateKaryawan" method="POST" enctype="multipart/form-data">
    @csrf
    <x-input-with-icon-label icon="ti ti-barcode" label="NIK" name="nik" />
    <x-input-with-icon-label icon="ti ti-user" label="Nama Karyawan" name="nama_karyawan" />
    <div class="row">
        <div class="col-6">
            <x-input-with-icon-label icon="ti ti-map-pin" label="Tempat Lahir" name="tempat_lahir" />
        </div>
        <div class="col-6">
            <x-input-with-icon-label icon="ti ti-calendar" label="Tanggal Lahir" datepicker="flatpickr-date" name="tanggal_lahir" />
        </div>
    </div>
    <x-textarea-label label="Alamat" name="alamat" />
    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Jenis Kelamin</label>
        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
            <option value="">Jenis Kelamin</option>
            <option value="L">Laki - Laki</option>
            <option value="P">Perempuan</option>
        </select>
    </div>
    <x-input-with-icon-label icon="ti ti-phone" label="No. HP" name="no_hp" />
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-select-label label="Status Perkawinan" name="kode_status_kawin" :data="$status_kawin" key="kode_status_kawin" textShow="status_kawin"
                kode="true" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Pendidikan
                    Terakhir</label>
                <select name="pendidikan_terakhir" id="pendidikan_terakhir" class="form-select">
                    <option value="">Pendidikan Terakhir</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA">SMA</option>
                    <option value="SMK">SMK</option>
                    <option value="D1">D1</option>
                    <option value="D2">D2</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                </select>
            </div>
        </div>
    </div>
    <x-select-label label="Kantor Unit" name="kode_unit" :data="$unit" key="kode_unit" textShow="nama_unit" />
    <x-select-label label="Divisi" name="kode_div" :data="$divisi" key="kode_div" textShow="nama_div" upperCase="true" /> </div>
    <x-select-label label="Jabatan" name="kode_jabatan" :data="$jabatan" key="kode_jabatan" textShow="nama_jabatan" upperCase="true" />
    <div class="row">
  <div class="col-lg-6 col-md-6 col-sm-12">
    <div class="form-group mb-3">
      <label for="pangkat" style="font-weight: 600" class="form-label">Pangkat</label>
      <select name="pangkat" id="pangkat" class="form-select">
        <option value="">Pilih Pangkat</option>
        <option value="I/a">I/a – Karyaawan Dasar Muda</option>
        <option value="I/b">I/b – Karyaawan Dasar Muda 1</option>
        <option value="I/c">I/c – Karyawan Dasar</option>
        <option value="I/d">I/d – Karyawan Dasar 1</option>
        <option value="II/b">II/a – Pengatur Muda</option>
        <option value="II/b">II/b – Pengatur Muda</option>
        <option value="II/b">II/c – Pengatur Muda</option>
        <option value="II/b">II/d – Pengatur Muda</option>
        <option value="III/a">III/a – Penata Muda</option>
        <option value="III/b">III/b – Penata</option>
        <option value="IV/a">IV/a – Pembina</option>
        <option value="Belum Ada Pangkat">Belum Ada Pangkat</option>
      </select>
    </div>
  </div>

  <div class="col-lg-6 col-md-6 col-sm-12">
    <div class="form-group mb-3">
      <label for="golongan" style="font-weight: 600" class="form-label">Golongan</label>
      <select name="golongan" id="golongan" class="form-select">
        <option value="">Pilih Golongan</option>
        <option value="A">Golongan A</option>
        <option value="B">Golongan B</option>
        <option value="C">Golongan C</option>
        <option value="D">Golongan D</option>
      </select>
    </div>
  </div>
</div>

    <x-input-with-icon-label icon="ti ti-calendar" datepicker="flatpickr-date" label="Tanggal Masuk" name="tanggal_masuk" />
    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Status Karyawan</label>
        <select name="status_karyawan" id="pendidikan_terakhir" class="form-select">
            <option value="">Status Karyawan</option>
            <option value="K">PKWT</option>
            <option value="T">PKWTT</option>
        </select>
    </div>
    <x-input-file name="foto" label="Foto" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
<script src="{{ asset('assets/js/pages/karyawan.js') }}"></script>
<script src="{{ asset('assets/js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>

<script>
    $(function() {

        $(".flatpickr-date").flatpickr();
        //$('#nik').mask('00.00.000');
    });
</script>
