<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
    }

    .pelatihan-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 16px;
        margin-top: 16px;
    }

    .pelatihan-box {
        border-radius: 12px;
        padding: 20px;
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        transition: 0.3s ease-in-out;
        text-decoration: none;
        color: #212529;
        border-left: 5px solid transparent;
    }

    .pelatihan-box:hover {
        transform: translateY(-2px);
        background-color: #f8f9fa;
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
        cursor: pointer; /* 👈 Tambahkan baris ini */
    }


    .modal-content {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        max-width: 600px;
        margin: 5% auto;
        animation: fadeIn 0.3s ease;
    }

    .modal-header h5 {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .close {
        position: absolute;
        right: 24px;
        top: 16px;
        font-size: 28px;
        font-weight: bold;
        color: #aaa;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #0d6efd;
        border: none;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-label {
        font-weight: 600;
        font-size: 14px;
    }

    .alert-info {
        font-size: 14px;
    }

    .text-primary {
        color: #0d6efd !important;
    }
</style>

<div class="card-header">
    <h3 class="card-title">Hallo <b><?= esc($session->nama); ?></b>, Selamat datang</h3>
</div>

<div class="card-body">

    <!-- Deskripsi Pelatihan -->
    <div class="mb-4 p-3 rounded bg-light border">
        <h5 class="fw-semibold text-dark">Informasi Pelatihan</h5>
        <p class="mb-2 text-secondary" style="text-align: justify;">
            <strong>Pelatihan Wajib</strong> merupakan pelatihan yang harus diikuti oleh seluruh peserta untuk memenuhi standar kompetensi tertentu yang telah ditetapkan oleh instansi. Kehadiran dan penyelesaian pelatihan ini menjadi syarat utama dalam penilaian kinerja dan pengembangan profesional.
        </p>
        <p class="text-secondary" style="text-align: justify;">
            <strong>Pelatihan Tambahan</strong> bersifat opsional namun sangat dianjurkan untuk diikuti guna meningkatkan wawasan, keterampilan tambahan, dan pengembangan diri secara berkelanjutan. Peserta dapat menambahkan catatan pelatihan tambahan yang telah diikuti beserta sertifikatnya.
        </p>
    </div>

    <h4 class="fw-semibold mt-2">Pelatihan Wajib</h4>
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

    <h4 class="fw-semibold mt-5">Pelatihan Tambahan</h4>

    <?php if (empty($pelatihan_tambahan)): ?>
    <div class="alert alert-info mt-3">Belum ada pelatihan tambahan yang diikuti.</div>
<?php endif; ?>

<div class="pelatihan-container mt-3">
    <?php foreach ($pelatihan_tambahan as $tambahan): ?>
        <div class="pelatihan-box" style="border-left-color: #0d6efd;">
            <div class="fw-semibold text-primary mb-2"><?= esc($tambahan['judul']) ?></div>
            <div><strong>Lokasi:</strong> <?= esc($tambahan['lokasi']) ?></div>
            <div><strong>Tanggal:</strong> <?= esc($tambahan['tanggal']) ?></div>
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

    <!-- Tombol tambah selalu ditampilkan -->
    <div class="pelatihan-box tambah" id="btnTambahPelatihan">
        <div style="font-size: 2rem;">+</div>
        <div>Tambah Pelatihan Lain</div>
    </div>
</div>


<!-- Modal Tambah Pelatihan -->
<div id="modalTambahPelatihan" class="modal" style="display:none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); overflow: auto;">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <div class="modal-header mb-4">
            <h5 class="modal-title text-dark">Formulir Pelatihan Tambahan</h5>
        </div>
        <form action="<?= base_url('pelatihan/simpanPelatihanTambahan') ?>" method="post" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <label for="judul" class="form-label">Judul Pelatihan <span class="text-danger">*</span></label>
                <input type="text" name="judul" id="judul" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
                <input type="text" name="lokasi" id="lokasi" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" required>
            </div>
            <div class="form-group mb-4">
                <label for="sertifikat" class="form-label">Unggah Sertifikat <span class="text-danger">*</span></label>
                <input type="file" name="sertifikat" id="sertifikat" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                <small class="text-muted">File yang diterima: .jpg, .png, atau .pdf</small>
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan Pelatihan</button>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modalTambahPelatihan');
    const btnTambah = document.getElementById('btnTambahPelatihan');
    const closeModal = document.getElementById('closeModal');

    btnTambah.addEventListener('click', () => {
        modal.style.display = 'block';
    });

    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>

<?php $this->endSection(); ?>
