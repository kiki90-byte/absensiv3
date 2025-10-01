<form action="{{ route('harilibur.update', ['kode_libur' => Crypt::encrypt($harilibur->kode_libur)]) }}" method="POST" id="formHariLibur">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" :value="$harilibur->tanggal" />
    @if ($user->hasRole(['super admin', 'admin pusat']))
        <x-select label="Unit" name="kode_unit" :data="$unit" key="kode_unit" textShow="nama_unit" select2="select2Kodeunit"
            upperCase="true" :selected="$harilibur->kode_unit" />
    @endif
    <x-textarea label="Keterangan" name="keterangan" :value="$harilibur->keterangan" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Simpan</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        const form = $('#formHariLibur');
        $(".flatpickr-date").flatpickr();
        const select2Kodeunit = $(".select2Kodeunit");

        if (select2Kodeunit.length) {
            select2Kodeunit.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Unit',
                    dropdownParent: $this.parent()
                });
            });
        }





        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        form.submit(function(e) {
            //e.preventDefault();
            const tanggal = form.find("#tanggal").val();
            const kode_unit = form.find("#kode_unit").val();
            const keterangan = form.find("#keterangan").val();

            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_unit == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Unit Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_unit").focus();
                    },
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#keterangan").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });

    });
</script>
