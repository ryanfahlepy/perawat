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
                                case 2:
                                    $warna = 'bg-warning';
                                    break;
                                case 3:
                                    $warna = 'bg-danger text-white';
                                    break;
                            }
                        }
                        ?>
                        <li class="list-group-item py-2 <?= $warna ?>">
                            <div class="d-flex align-items-center text-center">
                                <div style="width: 50px;"><?= esc($row['no']) ?></div>
                                <div class="flex-grow-1 text-start"><?= esc($row['kompetensi']) ?></div>
                                <div style="width: 150px;">
                                    <input type="checkbox" class="nilai-checkbox" name="nilai_<?= $row['id'] ?>" value="1"
                                        data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 1 ? 'checked' : '' ?> />
                                </div>
                                <div style="width: 150px;">
                                    <input type="checkbox" class="nilai-checkbox" name="nilai_<?= $row['id'] ?>" value="2"
                                        data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 2 ? 'checked' : '' ?> />
                                </div>
                                <div style="width: 150px;">
                                    <input type="checkbox" class="nilai-checkbox" name="nilai_<?= $row['id'] ?>" value="3"
                                        data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 3 ? 'checked' : '' ?> />
                                </div>

                                <div style="width: 200px;">
                                    <textarea class="form-control form-control-sm catatan-textarea"
                                        data-id="<?= $row['id'] ?>" rows="1"
                                        placeholder="Catatan..."><?= isset($dataHasil[$row['id']]['catatan']) ? esc($dataHasil[$row['id']]['catatan']) : '' ?></textarea>
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

<!-- Menambahkan jQuery jika belum ada -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Script untuk AJAX checkbox, drag-drop, dan tombol aksi -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userId = document.getElementById('nilai-form').dataset.userid;

        function kirimData(id) {
            const checkboxes = document.querySelectorAll(`.nilai-checkbox[data-id="${id}"]`);
            let nilai_id = 0;

            checkboxes.forEach(cb => {
                if (cb.checked) nilai_id = parseInt(cb.value);
            });

            const catatanElem = document.querySelector(`.catatan-textarea[data-id="${id}"]`);
            const catatan = catatanElem ? catatanElem.value.trim() : '';

            const bodyData = new URLSearchParams({
                kompetensi_id: id,
                nilai_id: nilai_id,
                user_id: userId,
                catatan: catatan
            });

            fetch("<?= site_url('mentoring/simpan') ?>", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: bodyData.toString()
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'ok') {
                        alert('Gagal menyimpan: ' + (data.message || ''));
                    }
                })
                .catch(err => {
                    console.error('Error saat kirim data:', err);
                });
        }

        // Checkbox perubahan
        document.querySelectorAll('.nilai-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                const id = this.dataset.id;

                // Uncheck yang lain
                document.querySelectorAll(`.nilai-checkbox[data-id="${id}"]`)
                    .forEach(x => { if (x !== this) x.checked = false });

                kirimData(id);
            });
        });

        // Catatan perubahan
        document.querySelectorAll('.catatan-textarea').forEach(area => {
            area.addEventListener('change', function () {
                const id = this.dataset.id;
                kirimData(id);
            });
        });
    });

    $(document).ready(function () {
        $('.nilai-checkbox').on('change', function () {
            const row = $(this).closest('li');
            const checkboxes = row.find('.nilai-checkbox');

            // Reset warna
            row.removeClass('bg-success bg-warning bg-danger text-white');

            // Deteksi nilai yang diceklis
            checkboxes.each(function () {
                if ($(this).is(':checked')) {
                    const val = $(this).val();
                    // Reset background
                    row.removeClass('bg-warning bg-danger bg-success');

                    if (val == '1') {
                        row.addClass('bg-success text-dark');
                    } else if (val == '2') {
                        row.addClass('bg-warning text-dark'); // Kuning
                    } else if (val == '3') {
                        row.addClass('bg-danger text-dark'); // Merah
                    }
                }

            });
        });

        // Trigger sekali biar warnanya langsung sesuai data awal
        $('.nilai-checkbox').trigger('change');
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
</style>


<?= $this->endSection() ?>