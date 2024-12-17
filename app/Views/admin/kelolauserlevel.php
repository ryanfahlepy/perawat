<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<div class="card-body">
    <!-- Blok content dinamis disini -->
    <?= $this->include('shared_page/alert'); ?>
    <?= $this->include('tabel/user_level'); ?>
    <!-- End blok content dinamis -->
</div>
<!-- /.card-body -->
<?php $this->endSection(); ?>