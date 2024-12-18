<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<div class="card-body">
    <!-- Blok content dinamis disini -->
    <p>Template dokumen<b> </b> disini ...... </p>
    <!-- End blok content dinamis -->
</div>
<!-- /.card-body -->
<?php $this->endSection(); ?>