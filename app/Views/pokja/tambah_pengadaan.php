<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<div class="card-body">
    <form action="<?= base_url('pokja/pengadaan/simpan_pengadaan') ?>" method="post" onsubmit="return validateForm()">
        
        <div class="form-group">
            <label for="nama_pengadaan">Nama Pengadaan</label>
            <input type="text" class="form-control" id="nama_pengadaan" name="nama_pengadaan" required>
        </div>

        <div class="form-group">
        <label for="ref_tabel">Metode Pemilihan</label>
<select class="form-control" id="ref_tabel" name="ref_tabel" required>
    <option value="" disabled selected>--PILIH--</option>
    <option value="1">Pengadaan Langsung/Penunjukkan Langsung</option>
    <option value="2">Tender</option>
    <option value="3">E-Purchasing</option>
</select>

        </div>

        <div class="form-group">
            <label for="ppk">PPK</label>
            <input type="text" class="form-control" id="ppk" name="ppk" required>
        </div>

        <div class="form-group">
            <label for="pokja_pp">Pokja PP</label>
            <input type="text" class="form-control" id="pokja_pp" name="pokja_pp" required>
        </div>

        <div class="form-group">
            <label for="nama_penyedia">Nama Penyedia</label>
            <input type="text" class="form-control" id="nama_penyedia" name="nama_penyedia" required>
        </div>

        <div class="form-group">
            <label for="tanggal_mulai">Tanggal Mulai</label>
            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
        </div>

        <div class="form-group">
            <label for="tanggal_berakhir">Tanggal Berakhir</label>
            <input type="date" class="form-control" id="tanggal_berakhir" name="tanggal_berakhir" required>
        </div>

        

        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="reset" class="btn btn-secondary">Atur Ulang</button>
    </form>
</div>

<script>
    function validateForm() {
        let fields = ["nama_pengadaan", "ref_tabel", "ppk", "pokja_pp", "nama_penyedia", "tanggal_mulai", "tanggal_berakhir"];
        for (let field of fields) {
            if (document.getElementById(field).value.trim() === "") {
                alert("Semua kolom wajib diisi!");
                return false;
            }
        }
        return true;
    }
</script>

<?php $this->endSection(); ?>
