<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<div class="card-body">
    <form action="<?= base_url('paket/tambah_data_paket') ?>" method="post" onsubmit="return validateForm()">
        <div class="row">
            <div class="col-md-4">
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
                    <label for="kode_rup">Kode RUP</label>
                    <input type="text" class="form-control" id="kode_rup" name="kode_rup" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="jenis">Jenis</label>
                    <input type="text" class="form-control" id="jenis" name="jenis" required>
                </div>
                <div class="form-group">
                    <label for="metode">Metode</label>
                    <input type="text" class="form-control" id="metode" name="metode" required>
                </div>
                <div class="form-group">
                    <label for="nama_paket">Nama Paket</label>
                    <input type="text" class="form-control" id="nama_paket" name="nama_paket" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="perencanaan">Perencanaan</label>
                    <input type="number" class="form-control" id="perencanaan" name="perencanaan" required>
                </div>
                <div class="form-group">
                    <label for="pelaksanaan">Pelaksanaan</label>
                    <input type="number" class="form-control" id="pelaksanaan" name="pelaksanaan" required>
                </div>
                <div class="form-group">
                    <label for="pembayaran">Pembayaran</label>
                    <input type="number" class="form-control" id="pembayaran" name="pembayaran" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="reset" class="btn btn-secondary">Atur Ulang</button>
    </form>
</div>
<script>
    function validateForm() {
        let fields = ["tahun_anggaran", "dipa", "jenis", "metode", "kode_rup", "nama_paket", "perencanaan", "pelaksanaan", "pembayaran"];
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