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

<form action="<?= base_url('/paket/update_data_paket_perencanaan/' . $paket['id']); ?>" method="post">
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
        <label for="kategori">Kategori</label>
        <input type="text" class="form-control" id="kategori" name="kategori" value="<?= old('kategori', $paket['kategori']); ?>" required>
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
        <label for="total_perencanaan">Total Perencanaan</label>
        <input type="number" class="form-control" id="total_perencanaan" name="total_perencanaan" value="<?= old('total_perencanaan', $paket['total_perencanaan']); ?>" required>
    </div>
    <div class="form-group">
        <label for="pdn">PDN</label>
        <input type="text" class="form-control" id="pdn" name="pdn" value="<?= old('pdn', $paket['pdn']); ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Perbarui</button>
</form>


</div>

<?php $this->endSection(); ?>