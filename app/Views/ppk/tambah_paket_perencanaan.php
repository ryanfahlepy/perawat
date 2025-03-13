<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<div class="card-body">
    <form action="<?= base_url('paket/tambah_data_paket_perencanaan') ?>" method="post" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="tahun_anggaran">Tahun Anggaran</label>
            <input type="number" class="form-control" id="tahun_anggaran" name="tahun_anggaran" required>
        </div>
        <div class="form-group">
    <label for="dipa">DIPA</label>
    <select class="form-control" id="dipa" name="dipa" required>
        <option value="">--- Pilih DIPA ---</option>
        <option value="DISINFOLAHTAL">DISINFOLAHTAL</option>
        <option value="MABES TNI AL">MABES TNI AL</option>
    </select>
</div>

        <div class="form-group">
            <label for="kategori">Kategori</label>
            <input type="text" class="form-control" id="kategori" name="kategori" required>
        </div>
        <div class="form-group">
            <label for="kode_rup">Kode RUP</label>
            <input type="text" class="form-control" id="kode_rup" name="kode_rup" required>
        </div>
        <div class="form-group">
            <label for="nama_paket">Nama Paket</label>
            <input type="text" class="form-control" id="nama_paket" name="nama_paket" required>
        </div>
        <div class="form-group">
            <label for="total_perencanaan">Total Perencanaan</label>
            <input type="number" class="form-control" id="total_perencanaan" name="total_perencanaan" required>
        </div>
       
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="reset" class="btn btn-secondary">Atur Ulang</button>
    </form>
</div>
<script>
    function validateForm() {
        let fields = ["tahun_anggaran","dipa", "kategori", "kode_rup", "nama_paket", "total_perencanaan"];
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