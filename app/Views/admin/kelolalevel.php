<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<div class="card-body">
    <a href="#" type="button" class="btn btn-primary ml-2 my-4 btn-addlevel"><i class="fas fa-plus mr-2"></i>Tambah Level</a>
    <div class="tabel-level"></div>
    <div class="form-modal"></div>
</div>
<?php $this->endSection(); ?>