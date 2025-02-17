<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<div class="card-body">
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/ppk/paket/update_data_paket_pelaksanaan/' . $paket['id']); ?>" method="post">
        <div class="form-group">
            <label for="tahun_anggaran">Tahun Anggaran</label>
            <input type="text" class="form-control" id="tahun_anggaran" name="tahun_anggaran" value="<?= old('tahun_anggaran', $paket['tahun_anggaran']); ?>" required>
        </div>
        <div class="form-group">
            <label for="sumber_data">Sumber Data</label>
            <input type="text" class="form-control" id="sumber_data" name="sumber_data" value="<?= old('sumber_data', $paket['sumber_data']); ?>" required>
        </div>
        <div class="form-group">
            <label for="nama_penyedia">Nama Penyedia</label>
            <input type="text" class="form-control" id="nama_penyedia" name="nama_penyedia" value="<?= old('nama_penyedia', $paket['nama_penyedia']); ?>" required>
        </div>
        <div class="form-group">
            <label for="kode">Kode</label>
            <input type="text" class="form-control" id="kode" name="kode" value="<?= old('kode', $paket['kode']); ?>" required>
        </div>
        <div class="form-group">
            <label for="kode_rup">Kode RUP</label>
            <input type="text" class="form-control" id="kode_rup" name="kode_rup" value="<?= old('kode_rup', $paket['kode_rup']); ?>" required>
        </div>
        <div class="form-group">
            <label for="nama_paket">Nama Paket</label>
            <input type="text" class="form-control" id="nama_paket" name="nama_paket" value="<?= old('nama_paket', $paket['nama_paket']); ?>" required>
        </div>
        <div class="form-group">
            <label for="total_pelaksanaan">Total Pelaksanaan</label>
            <input type="number" class="form-control" id="total_pelaksanaan" name="total_pelaksanaan" value="<?= old('total_pelaksanaan', $paket['total_pelaksanaan']); ?>" required>
        </div>
        <div class="form-group">
            <label for="pdn">PDN</label>
            <input type="text" class="form-control" id="pdn" name="pdn" value="<?= old('pdn', $paket['pdn']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
    </form>
</div>

<?php $this->endSection(); ?>
