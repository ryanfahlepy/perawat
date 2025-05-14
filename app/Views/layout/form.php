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
        Halo <b><?= esc($session->nama) ?></b>, Selamat datang
    </h3>
</div>

<div class="card-body" id="nilai-form" data-userid="<?= esc($userId) ?>">


    <!-- Form Tambah Kategori -->
    <?php if ($level == 1): ?>
        <form id="form-tambah-kategori" class="mb-3 d-flex gap-2">
            <input type="text" id="kategori-baru" class="form-control" placeholder="Nama Kategori Baru" required />
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i>
            </button>
        </form>
    <?php endif; ?>

    <!-- List Kategori dan Kompetensi -->
    <div id="kategori-list">
        <?php
        usort($dataPrapk, function ($a, $b) {
            return $a['no'] <=> $b['no'];
        });

        for ($i = 0; $i < count($dataPrapk); $i++):
            $row = $dataPrapk[$i];
            $kategori = $row['kategori'];
            $lastKategori = $i > 0 ? $dataPrapk[$i - 1]['kategori'] : null;
            $nextKategori = isset($dataPrapk[$i + 1]) ? $dataPrapk[$i + 1]['kategori'] : null;
            $isKategoriBaru = ($i == 0) || ($kategori !== $lastKategori);
            $isKategoriBerakhir = ($i == count($dataPrapk) - 1) || ($kategori !== $nextKategori);

            if ($isKategoriBaru):
                ?>
                <!-- Awal Kategori -->
                <div class="card mb-4 kategori-item" data-kategori="<?= esc($kategori) ?>">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong><?= $kategori ? esc($kategori) : 'Tanpa Kategori' ?></strong>
                        <?php if ($level == 1 && $kategori): ?>
                            <div class="btn-group ms-auto">
                                <button class="btn btn-sm text-white edit-kategori" data-kategori="<?= esc($kategori) ?>"
                                    style="background-color: #f1c40f;">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button class="btn btn-sm btn-danger hapus-kategori" data-kategori="<?= esc($kategori) ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-2">
                        <!-- Header Kolom -->
                        <div class="d-flex fw-bold px-3 py-2 border-bottom bg-light text-center">
                            <div style="width: 50px;">No</div>
                            <div class="flex-grow-1 text-start">Kompetensi</div>
                            <div style="width: 120px;">Mampu</div>
                            <div style="width: 120px;">Didampingi</div>
                            <div style="width: 130px;">Tidak Mampu</div>
                            <?php if ($level == 1): ?>
                                <div style="width: 90px;"></div>
                            <?php endif; ?>
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
                                <div style="width: 120px;">
                                    <input type="checkbox" class="nilai-checkbox" name="nilai_<?= $row['id'] ?>[]" value="1"
                                        data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 1 ? 'checked' : '' ?> />
                                </div>
                                <div style="width: 120px;">
                                    <input type="checkbox" class="nilai-checkbox" name="nilai_<?= $row['id'] ?>[]" value="2"
                                        data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 2 ? 'checked' : '' ?> />
                                </div>
                                <div style="width: 130px;">
                                    <input type="checkbox" class="nilai-checkbox" name="nilai_<?= $row['id'] ?>[]" value="3"
                                        data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 3 ? 'checked' : '' ?> />
                                </div>

                                <?php if ($level == 1): ?>
                                    <div class="btn-group" style="width: 90px;">
                                        <button class="btn btn-sm btn-primary edit-kompetensi" data-id="<?= $row['id'] ?>"
                                            data-kompetensi="<?= esc($row['kompetensi']) ?>">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <a href="#" class="btn btn-sm btn-danger btn-hapus-kompetensi"
                                            data-url="<?= site_url('mentoring/hapus_kompetensi/' . $row['id']) ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
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

<!-- Modal Edit Kompetensi -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= site_url('mentoring/update_kompetensi') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Kompetensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id" />
                    <input type="text" name="kompetensi" id="edit-kompetensi" class="form-control" required />
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editKategoriModal" tabindex="-1" aria-labelledby="editKategoriModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= site_url('mentoring/edit_kategori') ?>" method="get" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editKategoriModalLabel">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="old" id="edit-old-kategori">
                <div class="mb-3">
                    <label for="edit-new-kategori" class="form-label">Nama Kategori Baru</label>
                    <input type="text" class="form-control" id="edit-new-kategori" name="new" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Menambahkan jQuery jika belum ada -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Script untuk AJAX checkbox, drag-drop, dan tombol aksi -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userId = document.getElementById('nilai-form').dataset.userid;

        document.querySelectorAll('.nilai-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                const id = this.dataset.id;
                const val = this.value;

                // Uncheck checkbox lain di baris yang sama
                document.querySelectorAll(`.nilai-checkbox[data-id="${id}"]`)
                    .forEach(x => { if (x !== this) x.checked = false });

                const toSend = this.checked ? val : 0;

                // Log untuk memastikan nilai yang dikirim
                console.log(`id: ${id}, toSend: ${toSend}, userId: ${userId}`);

                fetch("<?= site_url('mentoring/simpan') ?>", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `kompetensi_id=${id}&nilai_id=${toSend}&user_id=${userId}`
                })
                    .then(r => r.json())
                    .then(d => {
                        // Log untuk melihat respons dari server
                        console.log(d);

                        if (d.status !== 'ok') {
                            alert('Gagal menyimpan: ' + (d.message || ''));
                        }
                    });
            });
        });
    });


    // Tambah kategori
    const formKat = document.getElementById('form-tambah-kategori');
    if (formKat) {
        formKat.addEventListener('submit', e => {
            e.preventDefault();
            const k = document.getElementById('kategori-baru').value.trim();
            if (k) window.location.href = `<?= site_url('mentoring/tambah_kategori?nama=') ?>${encodeURIComponent(k)}`;
        });
    }

    // Tambah kompetensi
    document.querySelectorAll('.form-tambah-kompetensi').forEach(f => {
        f.addEventListener('submit', e => {
            e.preventDefault();
            const kpt = f.querySelector('[name="kompetensi"]').value;
            const kat = f.dataset.kategori;
            if (kpt) window.location.href =
                `<?= site_url('mentoring/tambah_kompetensi') ?>?kategori=${encodeURIComponent(kat)}&kompetensi=${encodeURIComponent(kpt)}`;
        });
    });

    // Edit kompetensi (modal)
    document.querySelectorAll('.edit-kompetensi').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('edit-id').value = btn.dataset.id;
            document.getElementById('edit-kompetensi').value = btn.dataset.kompetensi;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    });

    // Edit kategori (prompt)
    document.querySelectorAll('.edit-kategori').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('edit-old-kategori').value = btn.dataset.kategori;
            document.getElementById('edit-new-kategori').value = btn.dataset.kategori;
            new bootstrap.Modal(document.getElementById('editKategoriModal')).show();
        });
    });


    // Hapus kategori dengan SweetAlert2
    document.querySelectorAll('.hapus-kategori').forEach(btn => {
        btn.addEventListener('click', () => {
            const k = btn.dataset.kategori;
            Swal.fire({
                title: `Hapus semua kompetensi dalam kategori "${k}"?`,
                text: "Ini akan menghapus semua kompetensi yang terkait dengan kategori ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    window.location.href = `<?= site_url('mentoring/hapus_kategori') ?>?kategori=${encodeURIComponent(k)}`;
                }
            });
        });
    });

</script>
<script>
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