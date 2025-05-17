<?php

$session = \Config\Services::session();
$level = $session->level;
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



<?php if ($level_akses == 2 || $level_akses == 3): ?>
    <div class="card-body">
        <a href="#" class="btn btn-success btn-show-mentees">
            <i class="far fa-sticky-note mr-2"></i>Tampilkan Mentees
        </a>
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
                                    <a href="<?= site_url('mentoring/form/' . esc($user['id'] ?? 0)) ?>"
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
<?php endif; ?>

<div class="tampil2 mt-4" style="display: none;">
    <div class="card-header">
    <h5 class="card-title mb-0">Rincian Kewenangan Klinik Perawat</h5>
        <div class="mt-4">
            <div><strong>Level:</strong> <?= esc($level) ?></div>
            <div><strong>Nama Perawat:</strong> <?= esc($session->nama) ?></div>
            <div><strong>Nama Mentor:</strong> <?= isset($mentorNama->nama) && $mentorNama->nama !== null ? esc($mentorNama->nama) : '-' ?></div>
        </div>
    </div>

    <div class="card-body">
        <div class="" id="nilai-form" data-userid="<?= esc($userId) ?>">
            <!-- List Kategori dan Kompetensi -->
            <div id="kategori-list">
                <?php
                usort($dataPk, function ($a, $b) {
                    return $a['no'] <=> $b['no'];
                });

                for ($i = 0; $i < count($dataPk); $i++):
                    $row = $dataPk[$i];
                    $kategori = $row['kategori'];
                    $lastKategori = $i > 0 ? $dataPk[$i - 1]['kategori'] : null;
                    $nextKategori = isset($dataPk[$i + 1]) ? $dataPk[$i + 1]['kategori'] : null;
                    $isKategoriBaru = ($i == 0) || ($kategori !== $lastKategori);
                    $isKategoriBerakhir = ($i == count($dataPk) - 1) || ($kategori !== $nextKategori);

                    if ($isKategoriBaru):
                        ?>
                        <!-- Awal Kategori -->
                        <div class="card mb-4 kategori-item" data-kategori="<?= esc($kategori) ?>">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <strong><?= $kategori ? esc($kategori) : 'Tanpa Kategori' ?></strong>

                            </div>
                            <div class="card-body p-2">
                                <!-- Header Kolom -->
                                <div class="d-flex fw-bold px-3 py-2 border-bottom bg-light text-center">
                                    <div style="width: 50px;">No</div>
                                    <div class="flex-grow-1 text-start">Kompetensi</div>
                                    <div style="width: 150px;">Mampu</div>
                                    <div style="width: 150px;">Didampingi</div>
                                    <div style="width: 150px;">Tidak Mampu</div>
                                    <div style="width: 200px;">Catatan</div>
                                </div>

                                <ul class="list-group kompetensi-list" data-kategori="<?= esc($kategori) ?>">
                                <?php endif; ?>

                                <!-- Item Kompetensi -->
                                <?php
                                $warna = '';
                                if (isset($dataHasil[$row['id']])) {
                                    switch ($dataHasil[$row['id']]['nilai_id']) {
                                        case 1:
                                            $warna = 'bg-success text-black';
                                            break;
                                        case 2:
                                            $warna = 'bg-warning text-black';
                                            break;
                                        case 3:
                                            $warna = 'bg-danger text-black';
                                            break;
                                    }
                                }
                                ?>
                                <li class="list-group-item py-2 <?= $warna ?>">
                                    <div class="d-flex align-items-center text-center">
                                        <div style="width: 50px;"><?= esc($row['no']) ?></div>
                                        <div class="flex-grow-1 text-start"><?= esc($row['kompetensi']) ?></div>
                                        <div style="width: 150px;">
                                            <input style="pointer-events: none;" type="checkbox" class="nilai-checkbox checkbox-disabled" name="nilai_<?= $row['id'] ?>"
                                                value="1" data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 1 ? 'checked' : '' ?> />
                                        </div>
                                        <div style="width: 150px;">
                                            <input style="pointer-events: none;" type="checkbox" class="nilai-checkbox checkbox-disabled" name="nilai_<?= $row['id'] ?>"
                                                value="2" data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 2 ? 'checked' : '' ?> />
                                        </div>
                                        <div style="width: 150px;">
                                            <input style="pointer-events: none;" type="checkbox" class="nilai-checkbox" name="nilai_<?= $row['id'] ?>"
                                                value="3" data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 3 ? 'checked' : '' ?> />
                                        </div>

                                        <div style="width: 200px;">
                                            <textarea  readonly style="background-color: #fff;"  class="form-control form-control-sm catatan-textarea"
                                                data-id="<?= $row['id'] ?>" rows="1"
                                                placeholder=""><?= isset($dataHasil[$row['id']]['catatan']) ? esc($dataHasil[$row['id']]['catatan']) : '' ?></textarea>
                                        </div>


                                    </div>
                                </li>

                                <?php if ($isKategoriBerakhir): ?>
                                </ul>

                                <!-- Form Tambah Kompetensi -->
                                <?php if ($level == 1): ?>
                                    <form class="mt-3 form-tambah-kompetensi" data-kategori="<?= esc($kategori) ?>">
                                        <div class="d-flex gap-2">
                                            <input type="text" name="kompetensi" class="form-control" placeholder="Kompetensi baru"
                                                required />
                                            <button class="btn btn-success">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </div> <!-- .card-body -->
                        </div> <!-- .card -->
                        <?php
                                endif;
                endfor;
                ?>
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