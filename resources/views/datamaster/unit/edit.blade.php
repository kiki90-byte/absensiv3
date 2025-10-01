<form action="{{ route('unit.update', Crypt::encrypt($unit->kode_unit)) }}" id="formeditUnit" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Kode Unit" name="kode_unit" value="{{ $unit->kode_unit }}" readonly />
    <x-input-with-icon icon="ti ti-file-text" label="Nama Unit" name="nama_unit" value="{{ $unit->nama_unit }}" />
    <x-input-with-icon icon="ti ti-map-pin" label="Alamat Unit" name="alamat_unit" value="{{ $unit->alamat_unit }}" />
    <x-input-with-icon icon="ti ti-phone" label="Telepon Unit" name="telepon_unit" value="{{ $unit->telepon_unit }}" />
    <x-input-with-icon icon="ti ti-map-pin" label="Lokasi Unit" name="lokasi_unit" value="{{ $unit->lokasi_unit }}" />
    <x-input-with-icon icon="ti ti-access-point" label="Radius Unit" name="radius_unit" value="{{ $unit->radius_unit }}" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Update
        </button>
    </div>
</form>

<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/unit/edit.js') }}"></script>
