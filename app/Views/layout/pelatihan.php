<?php

$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>


<div class="card-header">

    <h3 class="card-title">Hallo <b><?= $session->nama; ?></b>, Selamat datang</h3>
</div>

<?php $this->endSection(); ?>