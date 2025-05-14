<?php

$session = \Config\Services::session();
$level = $session->level;
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<div class="card-header">
    <h3 class="card-title">Hallo <b><?= esc($session->nama); ?></b>, Selamat datang</h3>
</div>


<div class="card-body">
    <h4>Daftar User (Level 2, 3, 4, 5, 6)</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">Foto</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Tingkat PK</th>
                <th class="text-center">Mentor</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($dataUser)): ?>
                <?php foreach ($dataUser as $user): ?>
                    <tr>
                        <td class="text-center">
                            <?php if (!empty($user['photo'])): ?>
                                <img src="<?= base_url('assets/dist/img/user/' . esc($user['photo'])) ?>" width="50" height="50"
                                    alt="User Photo">
                            <?php else: ?>
                                <span class="text-muted">Tidak ada foto</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($user['nama'] ?? '-') ?></td>
                        <td><?= esc($user['nama_level'] ?? '-') ?></td>
                        <td class="text-center">
                            <form action="<?= site_url('mentoring/setMentor') ?>" method="post">
                                <input type="hidden" name="user_id" value="<?= esc($user['id']) ?>">
                                <select name="mentor_id" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="">Pilih Mentor</option>
                                    <?php foreach ($mentorOptions[$user['id']] as $mentor): ?>
                                        <option value="<?= esc($mentor['id']) ?>" <?= isset($userMentorMapping[$user['id']]) && $userMentorMapping[$user['id']] == $mentor['id'] ? 'selected' : '' ?>>
                                            <?= esc($mentor['nama']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>

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