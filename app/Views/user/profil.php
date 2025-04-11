<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<!-- Blok content dinamis disini -->
<div class="card-body">
    <?= $this->include('shared_page/alert'); ?>
    <div class="text-center">
        <img src="/assets/dist/img/user/<?= $session->photo ?>" class="img-circle" width="125" alt="User profil picture">
    </div>

    <h3 class="mt-5 profil-username text-center"><?= $session->nama; ?></h3>

</div>
<!-- Blok content dinamis disini -->
<!-- /.card-body -->

<?php $this->endSection(); ?>