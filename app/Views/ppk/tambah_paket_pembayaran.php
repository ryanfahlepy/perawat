<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<div class="card-body">
    <form action="<?= base_url('ppk/paket/tambah_data_paket_pembayaran') ?>" method="post" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="tahun_anggaran">Tahun Anggaran</label>
            <input type="number" class="form-control" id="tahun_anggaran" name="tahun_anggaran" required>
        </div>
        <div class="form-group">
            <label for="sumber_data">Sumber Data</label>
            <input type="text" class="form-control" id="sumber_data" name="sumber_data" required>
        </div>
        <div class="form-group">
            <label for="nama_penyedia">Nama Penyedia</label>
            <input type="text" class="form-control" id="nama_penyedia" name="nama_penyedia" required>
        </div>
        <div class="form-group">
            <label for="kode_dokumen">Kode Dokumen</label>
            <input type="text" class="form-control" id="kode_dokumen" name="kode_dokumen" required>
        </div>
        <div class="form-group">
            <label for="kode_sp2d">Kode SP2D</label>
            <input type="text" class="form-control" id="kode_sp2d" name="kode_sp2d" required>
        </div>
        <div class="form-group">
            <label for="total_pembayaran">Total Pembayaran</label>
            <input type="number" class="form-control" id="total_pembayaran" name="total_pembayaran" required>
        </div>
        <div class="form-group">
            <label for="pdn">PDN</label>
            <input type="text" class="form-control" id="pdn" name="pdn" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="reset" class="btn btn-secondary">Atur Ulang</button>
    </form>
</div>

<script>
    function validateForm() {
        let fields = ["tahun_anggaran", "sumber_data", "nama_penyedia", "kode_dokumen", "kode_sp2d", "total_pembayaran", "pdn"];
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
