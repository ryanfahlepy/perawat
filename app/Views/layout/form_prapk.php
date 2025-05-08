<?php
$session = \Config\Services::session();
$level = $session->level;
?>
<!-- view/mentoring/index.php -->
<?= $this->extend('shared_page/template') ?>
<?= $this->section('content') ?>

<!-- Font Awesome untuk ikon -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<div class="card-header">
    <h3 class="card-title">
        Halo <b><?= esc($session->nama) ?></b>, Selamat datang
    </h3>
</div>

<div class="card-body">
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
        // Group data per kategori
        $kategoriData = [];
        foreach ($dataPrapk as $row) {
            $kategoriData[$row['kategori']][] = $row;
        }
        foreach ($kategoriData as $kategori => $items):
            ?>
            <div class="card mb-4 kategori-item" data-kategori="<?= esc($kategori) ?>">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong><?= esc($kategori) ?></strong>
                    <?php if ($level == 1): ?>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-warning edit-kategori" data-kategori="<?= esc($kategori) ?>">
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
                            <div style="width: 90px;"></div> <!-- Kosong untuk tombol -->
                        <?php endif; ?>
                    </div>

                    <!-- Daftar Kompetensi -->
                    <ul class="list-group kompetensi-list" data-kategori="<?= esc($kategori) ?>">
                        <?php foreach ($items as $row): ?>
                            <li class="list-group-item py-2">
                                <div class="d-flex align-items-center text-center">
                                    <!-- Kolom No -->
                                    <div style="width: 50px;"><?= esc($row['no']) ?></div>

                                    <!-- Kolom Kompetensi -->
                                    <div class="flex-grow-1 text-start"><?= esc($row['kompetensi']) ?></div>

                                    <!-- Kolom Mampu -->
                                    <div style="width: 120px;">
                                        <input type="checkbox" class="nilai-checkbox" name="nilai_<?= $row['id'] ?>[]" value="1"
                                            data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 1 ? 'checked' : '' ?> />
                                    </div>

                                    <!-- Kolom Didampingi -->
                                    <div style="width: 120px;">
                                        <input type="checkbox" class="nilai-checkbox" name="nilai_<?= $row['id'] ?>[]" value="2"
                                            data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 2 ? 'checked' : '' ?> />
                                    </div>

                                    <!-- Kolom Tidak Mampu -->
                                    <div style="width: 130px;">
                                        <input type="checkbox" class="nilai-checkbox" name="nilai_<?= $row['id'] ?>[]" value="3"
                                            data-id="<?= $row['id'] ?>" <?= isset($dataHasil[$row['id']]) && $dataHasil[$row['id']]['nilai_id'] == 3 ? 'checked' : '' ?> />
                                    </div>

                                    <!-- Tombol Aksi -->
                                    <?php if ($level == 1): ?>
                                        <div class="btn-group" style="width: 90px;">
                                            <button class="btn btn-sm btn-primary edit-kompetensi" data-id="<?= $row['id'] ?>"
                                                data-kompetensi="<?= esc($row['kompetensi']) ?>">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <a href="<?= site_url('mentoring/hapus_kompetensi/' . $row['id']) ?>"
                                                class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Form Tambah -->
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
                </div>

            </div>
        <?php endforeach; ?>
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

<!-- Script untuk AJAX checkbox, drag-drop, dan tombol aksi -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // AJAX untuk checkbox nilai
        document.querySelectorAll('.nilai-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                const id = this.dataset.id;
                const val = this.value;
                // Uncheck lainnya
                document
                    .querySelectorAll(`.nilai-checkbox[data-id="${id}"]`)
                    .forEach(x => { if (x !== this) x.checked = false });
                const toSend = this.checked ? val : 0;
                fetch("<?= site_url('mentoring/simpan') ?>", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `prapk_id=${id}&nilai_id=${toSend}`
                })
                    .then(r => r.json())
                    .then(d => { if (d.status !== 'ok') alert('Gagal menyimpan'); });
            });
        });

        // Drag & Drop Kompetensi
        document.querySelectorAll('.kompetensi-list').forEach(el => {
            new Sortable(el, { group: 'kompetensi', animation: 150 });
        });
        // Drag & Drop Kategori
        new Sortable(document.getElementById('kategori-list'), { group: 'kategori', animation: 150 });

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
                const k = btn.dataset.kategori;
                const n = prompt('Edit nama kategori:', k);
                if (n && n !== k) {
                    window.location.href = `<?= site_url('mentoring/edit_kategori') ?>?old=${encodeURIComponent(k)}&new=${encodeURIComponent(n)}`;
                }
            });
        });

        // Hapus kategori
        document.querySelectorAll('.hapus-kategori').forEach(btn => {
            btn.addEventListener('click', () => {
                const k = btn.dataset.kategori;
                if (confirm(`Hapus semua kompetensi dalam kategori "${k}"?`)) {
                    window.location.href = `<?= site_url('mentoring/hapus_kategori') ?>?kategori=${encodeURIComponent(k)}`;
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>