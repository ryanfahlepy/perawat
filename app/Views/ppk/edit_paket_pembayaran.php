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

<form action="<?= base_url('/paket/update_data_paket_pembayaran/' . $paket['id']); ?>" method="post">
    <div class="form-group">
        <label for="tahun_anggaran">Tahun Anggaran</label>
        <input type="number" class="form-control" id="tahun_anggaran" name="tahun_anggaran" value="<?= old('tahun_anggaran', $paket['tahun_anggaran']); ?>" required>
    </div>
    <div class="form-group">
    <label for="dipa">DIPA</label>
    <select class="form-control" id="dipa" name="dipa" required>
        <option value=""> --- Pilih DIPA ---</option>
        <option value="DISINFOLAHTAL" <?= old('dipa', $paket['dipa']) == 'DISINFOLAHTAL' ? 'selected' : ''; ?>>DISINFOLAHTAL</option>
        <option value="MABES TNI AL" <?= old('dipa', $paket['dipa']) == 'MABES TNI AL' ? 'selected' : ''; ?>>MABES TNI AL</option>
    </select>
</div>

    <div class="form-group">
        <label for="nama_penyedia">Nama Penyedia</label>
        <input type="text" class="form-control" id="nama_penyedia" name="nama_penyedia" value="<?= old('nama_penyedia', $paket['nama_penyedia']); ?>" required>
    </div>
    <div class="form-group">
        <label for="kode_dokumen">Kode Dokumen</label>
        <input type="text" class="form-control" id="kode_dokumen" name="kode_dokumen" value="<?= old('kode_dokumen', $paket['kode_dokumen']); ?>" required>
    </div>
    <div class="form-group">
        <label for="kode_sp2d">Kode SP2D</label>
        <input type="text" class="form-control" id="kode_sp2d" name="kode_sp2d" value="<?= old('kode_sp2d', $paket['kode_sp2d']); ?>" required>
    </div>
    <div class="form-group">
        <label for="total_pembayaran">Total Pembayaran</label>
        <input type="number" class="form-control" id="total_pembayaran" name="total_pembayaran" value="<?= old('total_pembayaran', $paket['total_pembayaran']); ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Perbarui</button>
</form>

</div>

<?php $this->endSection(); ?>
