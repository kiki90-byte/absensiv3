<form action="{{ route('jamkerjabydiv.store') }}" method="POST" id="formSetJamkerja">
    @csrf
    <div class="form-group">
        <select name="kode_unit" id="kode_unit" class="form-select select2Kodeunit">
            <option value="">Pilih Unit</option>
            @foreach ($unit as $c)
                <option value="{{ $c->kode_unit }}">{{ $c->nama_unit }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <select name="kode_div" id="kode_div" class="form-select select2Kodediv">
            <option value="">Pilih Divisi</option>
            @foreach ($divisi as $d)
                <option value="{{ $d->kode_div }}">{{ $d->nama_div }}</option>
            @endforeach
        </select>
    </div>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Hari</th>
                <th>Jam Kerja</th>
            </tr>
        </thead>
        <tbody>
            @php
                $nama_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            @endphp
            @foreach ($nama_hari as $hari)
                <tr>
                    <td class="text-capitalize" style="width: 10%">
                        <input type="hidden" name="hari[]" value="{{ $hari }}">
                        {{ $hari }}
                    </td>
                    <td>
                        <div class="form-group p-0" style="margin-bottom: 0px !important">
                            <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                <option value="">Pilih Jam Kerja</option>
                                @foreach ($jamkerja as $d)
                                    <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }} ({{ $d->jam_masuk }} - {{ $d->jam_pulang }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="form-group mt-3">
        <button type="submit" class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i> Submit</button></button>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#formSetJamkerja").submit(function(e) {
            let kode_unit = $(this).find("#kode_unit").val();
            let kode_div = $(this).find("#kode_div").val();
            let kode_jam_kerja = $(this).find("select[name^='kode_jam_kerja']").val();

            if (!kode_unit) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Kode Unit harus diisi!',
                    didClose: () => {
                        $(this).find("#kode_unit").focus();
                    }
                });
            } else if (!kode_div) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Kode Divisi harus diisi!',
                    didClose: () => {
                        $(this).find("#kode_div").focus();
                    }
                });
            } else {
                $(this).find("#btnSimpan").attr("disabled", true);
                $(this).find("#btnSimpan").html("<i class='fa fa-spin fa-spinner me-1'></i> Menyimpan...");
            }
        });
    });
</script>
