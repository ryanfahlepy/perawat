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
                e.preventDefault();

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
    <h3 class="card-title">Kelola Kompetensi Untuk <b><?= esc($namaLevel) ?></b></h3>
</div>
<div class="card-body" id="nilai-form" data-userid="<?= esc($levelId) ?>">

    <?php if ($level_akses == 1): ?>
        <!-- Form Tambah Kategori -->
        <form id="form-tambah-kategori" class="mb-3 d-flex gap-2">
            <input type="hidden" id="levelId" value="<?= esc($levelId) ?>">
            <input type="text" id="kategori-baru" class="form-control" placeholder="Nama Kategori Baru" required />
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i></button>
        </form>

    <?php endif; ?>

    <div id="kategori-list">
        <?php
        usort($dataKompetensi, fn($a, $b) => $a['no'] <=> $b['no']);
        $lastKategori = null;

        foreach ($dataKompetensi as $i => $row):
            $kategori = $row['kategori'] ?: 'Tanpa Kategori';
            $isKategoriBaru = $kategori !== $lastKategori;
            $nextKategori = isset($dataKompetensi[$i + 1]) ? ($dataKompetensi[$i + 1]['kategori'] ?: 'Tanpa Kategori') : null;
            $isKategoriBerakhir = is_null($nextKategori) || $kategori !== $nextKategori;


            if ($isKategoriBaru):
                ?>
                <div class="card mb-4 kategori-item" data-kategori="<?= esc($kategori) ?>">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong><?= esc($kategori) ?></strong>
                        <?php if ($level_akses == 1 && $kategori): ?>
                            <div class="btn-group ms-auto">
                                <button class="btn btn-sm text-white edit-kategori" data-kategori="<?= esc($kategori) ?>"
                                    style="background-color: #f1c40f;"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn btn-sm btn-danger hapus-kategori" data-kategori="<?= esc($kategori) ?>"
                                    data-level="<?= esc($levelId) ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-2">
                        <div class="d-flex fw-bold px-3 py-2 border-bottom bg-light text-center">
                            <div style="width: 50px;">No</div>
                            <div class="flex-grow-1 text-start">Kompetensi</div>
                            <?php if ($level_akses == 1): ?>
                                <div style="width: 90px;">Aksi</div>
                            <?php endif; ?>
                        </div>
                        <ul class="list-group kompetensi-list" data-kategori="<?= esc($kategori) ?>">
                        <?php endif; ?>

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
                                <?php if ($level_akses == 1): ?>
                                    <div class="btn-group" style="width: 90px;">
                                        <button style="background-color: #f1c40f;" class="btn btn-warning btn-sm text-white edit-kompetensi" data-id="<?= $row['id'] ?>"
                                            data-kompetensi="<?= esc($row['kompetensi']) ?>" data-level-id="<?= $levelId ?>">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button href="#" class="btn btn-sm btn-danger btn-hapus-kompetensi"
                                            data-url="<?= site_url('admin/manmentor/hapus_kompetensi/' . $row['id'] . '/' . $levelId) ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </li>

                        <?php if ($isKategoriBerakhir): ?>
                        </ul>
                        <?php if ($level_akses == 1): ?>
                            <form class="mt-3 form-tambah-kompetensi" data-kategori="<?= esc($kategori) ?>" method="post"
                                action="<?= site_url('admin/manmentor/tambah_kompetensi/' . $levelId) ?>">
                                <div class="d-flex gap-2">
                                    <input type="hidden" name="level_id" value="<?= esc($levelId) ?>">
                                    <input type="text" name="kompetensi" class="form-control" placeholder="Kompetensi baru"
                                        required />
                                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i></button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
                        endif;
                        $lastKategori = $kategori;
        endforeach;
        ?>
    </div> <!-- Tutup #kategori-list -->

</div> <!-- Tutup .card-body -->


<!-- Modal Edit Kompetensi -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= site_url('admin/manmentor/update_kompetensi') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Kompetensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id" />
                    <input type="hidden" name="level_id" id="edit-level-id" />
                    <input type="text" name="kompetensi" id="edit-kompetensi" class="form-control" required />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
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
        <form action="<?= site_url('admin/manmentor/edit_kategori/' . $levelId) ?>" method="post" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editKategoriModalLabel">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="old" id="edit-old-kategori" />
                <div class="mb-3">
                    <label for="edit-new-kategori" class="form-label">Nama Kategori Baru</label>
                    <input type="text" class="form-control" id="edit-new-kategori" name="new" required />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
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

                // Menyiapkan body data untuk dikirim
                const bodyData = `kompetensi_id=${id}&nilai_id=${toSend}&user_id=${userId}`;
                console.log('Data yang dikirim ke server:', bodyData);  // Log data yang dikirim ke server

                fetch("<?= site_url('mentoring/simpan') ?>", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: bodyData
                })
                    .then(r => r.json())
                    .then(d => {
                        // Log untuk melihat respons dari server
                        console.log('Response dari server:', d);

                        if (d.status !== 'ok') {
                            alert('Gagal menyimpan: ' + (d.message || ''));
                        }
                    })
                    .catch(error => {
                        // Menangani jika ada error saat request ke server
                        console.error('Error terjadi pada saat request:', error);
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
            const levelId = document.getElementById('levelId').value;
            if (k) {
                window.location.href = `<?= site_url('admin/manmentor/tambah_kategori') ?>?nama=${encodeURIComponent(k)}&levelId=${encodeURIComponent(levelId)}`;
            }
        });
    }


    document.querySelectorAll('.form-tambah-kompetensi').forEach(f => {
        f.addEventListener('submit', e => {
            e.preventDefault();
            const kpt = f.querySelector('[name="kompetensi"]').value;
            const kat = f.dataset.kategori;
            const levelId = f.querySelector('[name="level_id"]').value;
            if (kpt && levelId) {
                window.location.href =
                    `<?= site_url('admin/manmentor/tambah_kompetensi') ?>/${levelId}?kategori=${encodeURIComponent(kat)}&kompetensi=${encodeURIComponent(kpt)}`;
            }
        });
    });


    // Edit kompetensi (modal)
    document.querySelectorAll('.edit-kompetensi').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('edit-id').value = btn.dataset.id;
            document.getElementById('edit-kompetensi').value = btn.dataset.kompetensi;
            document.getElementById('edit-level-id').value = btn.dataset.levelId;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    });


    // Edit kategori (prompt)
    document.querySelectorAll('.edit-kategori').forEach(btn => {
        btn.addEventListener('click', () => {
            const oldKategori = btn.dataset.kategori;
            document.getElementById('edit-old-kategori').value = oldKategori;
            document.getElementById('edit-new-kategori').value = oldKategori;
            const modal = new bootstrap.Modal(document.getElementById('editKategoriModal'));
            modal.show();
        });
    });



    // Hapus kategori dengan SweetAlert2
    document.querySelectorAll('.hapus-kategori').forEach(btn => {
        btn.addEventListener('click', () => {
            const kategori = btn.dataset.kategori;
            const levelId = btn.dataset.level;

            Swal.fire({
                title: `Hapus semua kompetensi dalam kategori "${kategori}"?`,
                text: "Ini akan menghapus semua kompetensi yang terkait dengan kategori ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    // Kirim parameter kategori dan level_id ke controller
                    const url = `<?= site_url('admin/manmentor/hapus_kategori') ?>?kategori=${encodeURIComponent(kategori)}&level_id=${encodeURIComponent(levelId)}`;
                    window.location.href = url;
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