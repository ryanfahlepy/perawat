<?php

$session = \Config\Services::session();
$level = $session->level;
?>
<!-- view/mentoring/index.php -->
<?= $this->extend('shared_page/template') ?>
<?= $this->section('content') ?>

<!-- Font Awesome untuk ikon -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />

<!-- <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script> -->
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    <?php if (session()->getFlashdata('message')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '<?= session('message') ?>',
            timer: 2000,
            showConfirmButton: false
        });
    <?php elseif (session()->getFlashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '<?= session('error') ?>',
            timer: 2000,
            showConfirmButton: false
        });
    <?php endif; ?>
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-hapus-kompetensi').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault(); // Hindari redirect langsung

                const url = this.getAttribute('data-url');

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data kompetensi akan dihapus permanen",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        window.location.href = url;
                    }
                });
            });
        });
    });
</script>


<div class="card-header">
    <h3 class="card-title">
        Rincian Kewenangan Klinik Perawat untuk <b><?= esc($userData->nama) ?></b>
    </h3>
</div>

<div class="" id="nilai-form" data-userid="<?= esc($pkId) ?>">

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
                                    $warna = 'bg-success text-dark';
                                    break;
                                case 2:
                                    $warna = 'bg-warning text-dark';
                                    break;
                                case 3:
                                    $warna = 'bg-danger text-dark';
                                    break;
                            }
                        }
                        ?>
                        <li class="list-group-item py-2 <?= $warna ?>">
                            <div class="d-flex align-items-center text-center">
                                <div style="width: 50px;"><?= esc($row['no']) ?></div>
                                <div class="flex-grow-1 text-start"><?= esc($row['kompetensi']) ?></div>
                                <div style="width: 150px;">
                                    <input  type="checkbox" class="nilai-checkbox readonly-checkbox" name="nilai_<?= $row['id'] ?>" value="1"
                                        data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 1 ? 'checked' : '' ?> />
                                </div>
                                <div style="width: 150px;">
                                    <input type="checkbox" class="nilai-checkbox readonly-checkbox" name="nilai_<?= $row['id'] ?>" value="2"
                                        data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 2 ? 'checked' : '' ?> />
                                </div>
                                <div style="width: 150px;">
                                    <input type="checkbox" class="nilai-checkbox readonly-checkbox" name="nilai_<?= $row['id'] ?>" value="3"
                                        data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 3 ? 'checked' : '' ?> />
                                </div>

                                <div style="width: 200px;">
                                    <textarea readonly class="form-control form-control-sm catatan-textarea"
                                        data-id="<?= $row['id'] ?>" rows="1"
                                        ><?= isset($dataHasil[$row['id']]['catatan']) ? esc($dataHasil[$row['id']]['catatan']) : '' ?></textarea>
                                </div>


                            </div>
                        </li>

                        <?php if ($isKategoriBerakhir): ?>
                        </ul>

                        <!-- Form Tambah Kompetensi -->

                    </div> <!-- .card-body -->
                </div> <!-- .card -->
                <?php
                        endif;
        endfor;
        ?>
    </div>
</div>

<!-- Menambahkan jQuery jika belum ada -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Script untuk AJAX checkbox, drag-drop, dan tombol aksi -->

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.readonly-checkbox').forEach(cb => {
            cb.addEventListener('click', function (e) {
                // Cegah perubahan status checkbox
                e.preventDefault();
            });
        });
    });


</script>
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
    textarea[readonly] {
    background-color: #fff !important;  /* Atau sesuaikan warna normal kamu */
    color: #212529;                     /* Warna teks default */
    cursor: default;                   /* Cursor tetap seperti biasa */
    opacity: 1;                        /* Pastikan tidak transparan */
}

</style>


<?= $this->endSection() ?>