<?php

$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<div class="card-header">
    <h4>Daftar Perawat</h4>
</div>
<div class="card-body" style="max-height: 400px; overflow-y: auto;">
    <table class="table table-bordered table-striped mb-0">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Foto</th>
                <th class="text-center">Nama</th>
                <th class="text-center">PK</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($users as $user): ?>
                <?php if ($user->level_user != 1): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center">
                            <img src="<?= base_url('assets/dist/img/user/' . $user->photo); ?>" alt="Foto" width="50"
                                height="50">
                        </td>
                        <td><?= esc($user->nama); ?></td>
                        <td><?= esc($user->nama_level); ?></td>
                        <td class="text-center">
                            <a href="<?= base_url('/ekinerja/lihat_kinerja/' . $user->id) ?>" class="btn btn-sm btn-info"
                                title="Lihat Pelatihan Perawat">
                                <i class="text-white fas fa-info-circle"></i>
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<style>
    /* Style untuk datetime picker */
    input[type="datetime-local"] {
        padding: 0.375rem 0.75rem;
        line-height: 1.5;
    }

    /* Modal lebih lebar */
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 500px;
            margin: 1.75rem auto;
        }
    }
</style>
<?= $this->endSection(); ?>