<?php

$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>


<div class="card-header">

    <h3 class="card-title">Hallo <b><?= $session->nama; ?></b>, Selamat datang</h3>
</div>
<div class="col-6">
    <div class="card">
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
                        <th class="text-center">Kinerja</th>
                        <th class="text-center">Mentoring</th>
                        <th class="text-center">Pelatihan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($users as $user): ?>
                        <?php if ($user->level_user != 1): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <img src="<?= base_url('assets/dist/img/user/' . $user->photo); ?>" alt="Foto" width="50"
                                        height="50">
                                </td>
                                <td><?= esc($user->nama); ?></td>
                                <td><?= esc($user->nama_level); ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('/dashboardkaru/resume_kinerja/' . $user->id . '/' . $user->id) ?>"
                                        class="btn btn-sm btn-primary" title="Lihat Kinerja Perawat">
                                        <i class="text-white fas fa-chalkboard-teacher"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('/dashboardkaru/resume_mentoring/' . $user->id) ?>"
                                        class="btn btn-sm btn-info" title="Lihat Mentoring Perawat">
                                        <i class="text-white fas fa-calendar"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('/dashboardkaru/resume_pelatihan/' . $user->id . '/' . $user->id) ?>"
                                        class="btn btn-sm btn-success" title="Lihat Pelatihan Perawat">
                                        <i class="text-white fas fa-book"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




<?php $this->endSection(); ?>