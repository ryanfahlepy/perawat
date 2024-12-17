<!-- Menampilan alert hasil verifikasi -->
<?php session();
if (session()->getFlashdata('pesan')) : ?>
    <div class="mb-3 pl-3 pr-3">
        <div class="alert alert-<?= $_SESSION['color'] ?>" role="alert">
            <div class="alert-icon">
                <i class="far fa-fw fa-bell"></i>
                <strong class="ml-3"><?= session()->getFlashdata('pesan'); ?></strong>
            </div>
        </div>
    </div>
<?php endif; ?>