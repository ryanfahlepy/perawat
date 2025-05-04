<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (session()->getFlashdata('pesan')) : ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?= session()->getFlashdata('pesan'); ?>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
</script>
<?php endif; ?>

<!-- Blok content dinamis disini -->
<div class="card-body">
    <div class="text-center">
        <img src="/assets/dist/img/user/<?= $session->photo ?>" class="img-circle" width="125" alt="User profil picture">
    </div>
    <h3 class="mt-5 profil-username text-center"><?= $session->nama; ?></h3>

    <div class="text-center mt-4">
        <a href="/admin/manuser/hal_resset_psswrd" class="btn btn-warning">
            Ganti Password
        </a>
    </div>
</div>

<!-- Blok content dinamis disini -->
<!-- /.card-body -->

<?php $this->endSection(); ?>