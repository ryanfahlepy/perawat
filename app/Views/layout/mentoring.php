<?php

$session = \Config\Services::session();
$level = $session->level;
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<div class="card-body">
    <h4>Daftar Mentees anda <b><?= esc($session->nama); ?></b></h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">Foto</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Tingkat PK</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($dataUser)): ?>
                <?php foreach ($dataUser as $user): ?>
                    <tr>
                        <td class="text-center">
                            <?php if (!empty($user['photo'])): ?>
                                <img src="<?= base_url('uploads/' . esc($user['photo'])) ?>" width="50" height="50"
                                    alt="User Photo">
                            <?php else: ?>
                                <span class="text-muted">Tidak ada foto</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($user['nama'] ?? '-') ?></td>
                        <td><?= esc($user['nama_level'] ?? '-') ?></td>
                        <td class="text-center">
                            <a href="<?= site_url('mentoring/form/' . esc($user['id'] ?? 0)) ?>" class="btn btn-info btn-sm w"
                                title="Info">
                                <i class="fas fa-info-circle text-white"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data user untuk level 4, 5, atau 6.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php $this->endSection(); ?>