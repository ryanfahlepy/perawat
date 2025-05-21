<?php
$session = \Config\Services::session();
?>
<?= $this->extend('shared_page/template'); ?>
<?= $this->section('content'); ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* ADMIN ONLY STYLING */
.admin-section {
    background-color: #ffffff;
    padding: 6px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    margin-bottom: 24px;
}
.table th, .table td {
    vertical-align: middle !important;
    font-size: 14px;
}

.table thead {
    background-color: #f1f3f5;
}

.btn {
    font-size: 14px;
    font-weight: 500;
}

.btn-sm {
    padding: 6px 10px;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-primary {
    background-color: #1e88e5;
    border-color: #1e88e5;
}

.btn:hover {
    opacity: 0.9;
}

.modal-content {
    border: none;
    padding: 32px;
    border-radius: 12px;
}

.modal-header h5 {
    font-size: 20px;
    font-weight: 600;
    color: #1e88e5;
}

.form-label {
    font-weight: 600;
    color: #343a40;
}

.form-control {
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
}

button[type="submit"] {
    padding: 10px 20px;
    font-size: 15px;
    font-weight: 600;
}


    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fc;
    }

    .card-body {
        padding: 24px;
    }

    .pelatihan-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-top: 16px;
    }

    .pelatihan-box {
        border-radius: 10px;
        padding: 20px;
        background-color: #ffffff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        transition: all 0.25s ease-in-out;
        text-decoration: none;
        color: #212529;
        border-left: 5px solid transparent;
    }

    .pelatihan-box:hover {
        transform: translateY(-3px);
        background-color: #f9fcff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .pelatihan-box.sudah { border-left-color: #198754; }
    .pelatihan-box.belum { border-left-color: #dc3545; }

    .pelatihan-box.tambah {
        border-left-color: #6c757d;
        text-align: center;
        color: #6c757d;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-style: italic;
        cursor: pointer;
        font-weight: 500;
        background-color: #f1f3f5;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.4);
        overflow: auto;
    }

    .modal-content {
        background: #ffffff;
        border-radius: 12px;
        padding: 30px;
        max-width: 600px;
        margin: 5% auto;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        animation: fadeIn 0.3s ease;
    }

    .modal-header h5 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 0;
    }

    .close {
        position: absolute;
        right: 20px;
        top: 14px;
        font-size: 28px;
        color: #888;
        cursor: pointer;
    }

    .form-label {
        font-weight: 600;
        font-size: 14px;
        color: #495057;
    }

    .form-control {
        border-radius: 6px;
    }

    .btn-primary {
        background-color: #1e88e5;
        border-color: #1e88e5;
        border-radius: 6px;
        padding: 10px;
    }

    .btn-outline-success {
        border-radius: 6px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 12px;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 6px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert-info {
        background-color: #e9f6ff;
        border-color: #b6e0fe;
        color: #31708f;
    }
</style>

<div class="card-header">
    <h3 class="card-title">Pelatihan untuk <b><?= esc($user->nama); ?></b></h3>
</div>

<div class="card-body">

<?php if ($session->level == 1): ?>
<div class="admin-section">
    <button class="btn btn-success mb-3" id="btnTambahKompetensi">+ Tambah Pelatihan</button>
    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Tingkat PK</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($pelatihan_wajib as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($row['kategori']) ?></td>
                    <td><?= esc($row['level']) ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary btnEditKompetensi" 
                            data-id="<?= $row['id'] ?>" 
                            data-kategori="<?= esc($row['kategori']) ?>" 
                            data-level="<?= esc($row['level']) ?>">
                            <i class="fas fa-pencil-alt text-white"></i>
                        </a>

                        <form action="<?= base_url('pelatihan/delete/' . $row['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus?');">
                            <a type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash text-white"></i></a>
                        </form>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal Form -->
    <div class="modal" id="modalKompetensi">
        <div class="modal-content position-relative">
            <span class="close" id="closeKompetensi">&times;</span>
            <div class="modal-header mb-4">
                <h5 class="modal-title text-dark">Form Pelatihan</h5>
            </div>
            <form action="<?= base_url('Pelatihan/simpanadmin') ?>" method="post">
                <input type="hidden" name="id" id="kompetensiId">
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <input type="text" class="form-control" name="kategori" id="kompetensiKategori" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tingkat PK</label>
                    <select name="level" class="form-control" required>
                        <option value="">-- Pilih Tingkat PK --</option>
                        <option value="PK I">PK I</option>
                        <option value="PK II">PK II</option>
                        <option value="PK III">PK III</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        const modalKompetensi = document.getElementById('modalKompetensi');
        const closeKompetensi = document.getElementById('closeKompetensi');
        const formKompetensi = modalKompetensi.querySelector('form');

        // Tombol tambah
        document.getElementById('btnTambahKompetensi').addEventListener('click', function () {
            formKompetensi.reset();
            document.getElementById('kompetensiId').value = '';
            modalKompetensi.style.display = 'block';
        });

        // Tombol edit
        document.querySelectorAll('.btnEditKompetensi').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('kompetensiId').value = this.dataset.id;
                document.getElementById('kompetensiKategori').value = this.dataset.kategori;
                document.querySelector('select[name="level"]').value = this.dataset.level;
                modalKompetensi.style.display = 'block';
            });
        });

        closeKompetensi.addEventListener('click', () => modalKompetensi.style.display = 'none');
        window.addEventListener('click', (e) => {
            if (e.target === modalKompetensi) modalKompetensi.style.display = 'none';
        });
    </script>
</div>

<?php else: ?>
    <div class="mb-4 p-3 rounded bg-light border">
        <p class="mb-2 text-secondary" style="text-align: justify;">
            <strong>Pelatihan Wajib</strong> merupakan pelatihan yang harus diikuti 
            oleh seluruh peserta untuk memenuhi standar kompetensi tertentu yang telah ditetapkan oleh instansi. Kehadiran dan penyelesaian pelatihan ini menjadi syarat utama dalam penilaian kinerja dan pengembangan profesional.
        </p>
        <p class="text-secondary" style="text-align: justify;">
            <strong>Pelatihan dan Seminar Tambahan</strong>  bersifat opsional namun sangat dianjurkan untuk diikuti guna meningkatkan wawasan, keterampilan tambahan, dan pengembangan diri secara berkelanjutan. 
            Perawat dapat menambahkan catatan pelatihan dan seminar tambahan yang telah diikuti beserta sertifikatnya.
        </p>
    </div>

    <h5 class="section-title">Pelatihan Wajib</h5>
    <div class="pelatihan-container">
        <?php foreach ($pelatihan_wajib as $pelatihan):
            $judul = $pelatihan['kategori'];
            $pelatihan_id = $pelatihan['id'];
            $data_pelatihan = $pelatihan_map[$pelatihan_id] ?? null;
            $status = $data_pelatihan ? 'sudah' : 'belum';
        ?>
            <a href="<?= base_url('pelatihan/detail/' . $pelatihan_id) ?>" class="pelatihan-box <?= $status ?>">
                <div class="fw-semibold"><?= esc($judul) ?></div>
            </a>
        <?php endforeach; ?>
    </div>

    <h5 class="section-title mt-5">Pelatihan dan Seminar Tambahan</h5>

    <?php if (empty($pelatihan_tambahan)): ?>
        <div class="alert alert-info mt-3">Belum ada pelatihan tambahan yang diikuti.</div>
    <?php endif; ?>

    <div class="pelatihan-container mt-3">
        <?php foreach ($pelatihan_tambahan as $tambahan):
            $berlaku = new DateTime($tambahan['berlaku']);
            $hari_ini = new DateTime();
            $warna = '#1e88e5';
            $peringatan = '';

            if ($berlaku < $hari_ini) {
                $warna = '#dc3545';
                $peringatan = '<div class="text-danger mt-2 fw-semibold">⚠️ Masa berlaku sudah habis</div>';
            } elseif ($berlaku <= (clone $hari_ini)->modify('+1 month')) {
                $warna = '#fd7e14';
                $peringatan = '<div style="color: ' . $warna . ';" class="mt-2 fw-semibold">⚠️ Masa berlaku hampir habis</div>';
            }
        ?>
            <div class="pelatihan-box" style="border-left-color: <?= $warna ?>;">
                <div class="fw-semibold mb-2"><?= esc($tambahan['judul']) ?></div>
                <div><strong>Jenis:</strong> <?= esc($tambahan['jenis']) ?></div>
                <div><strong>Lokasi:</strong> <?= esc($tambahan['lokasi']) ?></div>
                <div><strong>Tanggal Mulai:</strong> <?= esc($tambahan['tanggal']) ?></div>
                <div><strong>Tanggal Berakhir:</strong> <?= esc($tambahan['berlaku']) ?></div>

                <?= $peringatan ?>

                <div class="mt-3">
                    <?php if ($tambahan['sertifikat']): ?>
                        <a href="<?= base_url('uploads/sertifikat/' . $tambahan['sertifikat']) ?>" target="_blank" class="btn btn-sm btn-outline-success w-100">
                            <i class="bi bi-file-earmark-pdf"></i> Lihat Sertifikat
                        </a>
                    <?php else: ?>
                        <span class="text-muted">Tidak ada sertifikat</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if ($session->level != 2): ?>
        <div class="pelatihan-box tambah" id="btnTambahPelatihan">
            <div style="font-size: 2rem;">+</div>
            <div>Tambah Pelatihan Lain</div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div id="modalTambahPelatihan" class="modal">
    <div class="modal-content position-relative">
        <span class="close" id="closeModal">&times;</span>
        <div class="modal-header mb-4">
            <h5 class="modal-title text-dark">Formulir Pelatihan/Seminar Tambahan</h5>
        </div>
        <form action="<?= base_url('pelatihan/simpanPelatihanTambahan') ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis <span class="text-danger">*</span></label>
                <select name="jenis" class="form-control" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Pelatihan">Pelatihan</option>
                    <option value="Seminar">Seminar</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
                <input type="text" name="lokasi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="berlaku" class="form-label">Tanggal Berakhir <span class="text-danger">*</span></label>
                <input type="date" name="berlaku" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="sertifikat" class="form-label">Unggah Sertifikat <span class="text-danger">*</span></label>
                <input type="file" name="sertifikat" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                <small class="text-muted">File yang diperbolehkan: .jpg, .jpeg, .png, .pdf</small>
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan Pelatihan</button>
        </form>
    </div>
<?php endif; ?>

</div>

<script>
    const modal = document.getElementById('modalTambahPelatihan');
    const btnTambah = document.getElementById('btnTambahPelatihan');
    const closeModal = document.getElementById('closeModal');

    btnTambah.addEventListener('click', () => modal.style.display = 'block');
    closeModal.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });
</script>

<?= $this->endSection(); ?>
