<?php

$session = \Config\Services::session();
$level = $session->level;

?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" />
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>





<div class="card-body"><?php if ($level_akses == 2 || $level_akses == 3): ?>
        <a href="#" class="btn btn-success btn-show-mentees">
            <i class="far fa-sticky-note mr-2"></i>Tampilkan Mentees
        </a>
    <?php endif; ?>
    <a href="#" class="btn btn-primary btn-show-mentor">
        <i class="far fa-sticky-note mr-2"></i>Tampilkan Hasil Mentoring Anda
    </a>
    <div class="tampil" style="display: none;">
        <!-- <h4>Daftar Mentees anda <b><?= esc($session->nama); ?></b></h4> -->
        <table class=" mt-4 table table-bordered table-striped" id="example1">
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
                                    <img src="<?= base_url('assets/dist/img/user/' . esc($user['photo'])) ?>" width="50" height="50"
                                        alt="User Photo">
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada foto</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($user['nama'] ?? '-') ?></td>
                            <td><?= esc($user['nama_level'] ?? '-') ?></td>
                            <td class="text-center">
                                <a href="<?= site_url('mentoring/daftar_form/' . esc($user['id'] ?? 0)) ?>"
                                    class="btn btn-info btn-sm w" title="Info">
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
</div>


<div class="tampil2 mt-4" style="display: none;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        
                        
                    </div>
                    <div class="card-body">
                        
                            
                  
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%">No</th>
                                        <th class="text-center" style="width: 25%">Nama</th>
                                        <th class="text-center" style="width: 15%">Tanggal Mulai</th>
                                        <th class="text-center" style="width: 15%">Tanggal Berakhir</th>
                                        <th class="text-center" style="width: 15%">Countdown</th>
                                        <th class="text-center" style="width: 15%">Progress</th>
                                        <th class="text-center" style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($daftarForms as $index => $form): ?>
                                        <tr>
                                            <td class="text-center"><?= $index + 1 ?></td>
                                            <td class="text-center"><?= $form['nama'] ?></td>
                                            <td class="text-center"><?= $form['tanggal_mulai_formatted'] ?></td>
                                            <td class="text-center"><?= $form['tanggal_berakhir_formatted'] ?></td>
                                            <td class="text-center">
                                                <span class="countdown" data-endtime="<?= $form['tanggal_berakhir'] ?>"
                                                    id="countdown-<?= $form['id'] ?>">
                                                    Loading...
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="progress">
                                                    <div class="progress-bar <?= $form['progress'] == 100 ? 'bg-success' : 'bg-primary' ?>"
                                                        role="progressbar" style="width: <?= $form['progress'] ?>%"
                                                        aria-valuenow="<?= $form['progress'] ?>" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <?= $form['progress'] ?>%
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= base_url('/mentoring/form_hasil/' . $userData->id . '/' . $form['id']) ?>"
                                                    class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="text-white fas fa-eye"></i>
                                                </a>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                    
                    </div>
                    <div class="card-footer">
                        <a href="<?= base_url('/mentoring') ?>" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Mentee
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<style>
    .bg-warning {
        background-color: rgba(255, 193, 7, 0.50) !important;
        /* Kuning dengan transparansi 75% */
    }

    .bg-danger {
        background-color: rgba(220, 53, 69, 0.50) !important;
        /* Merah dengan transparansi 75% */
    }

    .bg-success {
        background-color: rgba(40, 167, 69, 0.50) !important;
        /* Hijau dengan transparansi 75% */
    }
</style>
<script>
    $(function () {
        $("#example1").DataTable({
            responsive: true,
            autoWidth: false,        // Biasanya disarankan false agar layout lebih stabil
            paging: true,            // Aktifkan pagination
            lengthChange: true,      // Dropdown untuk jumlah baris per halaman
            searching: true,         // Aktifkan kotak pencarian
            ordering: true,          // Aktifkan pengurutan kolom
            info: true,              // Tampilkan info "Showing x of y entries"
            pageLength: 10,
        });
    });
    $(document).ready(function () {
        $('.btn-show-mentees').on('click', function (e) {
            e.preventDefault();
            $('.tampil2').slideUp(); // tutup yang lain
            $('.tampil').slideToggle(); // toggle yang ini
        });

        $('.btn-show-mentor').on('click', function (e) {
            e.preventDefault();
            $('.tampil').slideUp(); // tutup yang lain
            $('.tampil2').slideToggle(); // toggle yang ini
        });
    });

</script>




<?php $this->endSection(); ?>