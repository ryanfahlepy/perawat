<?php
$session = \Config\Services::session();

function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) {
        return $diff . ' detik lalu';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' menit lalu';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' jam lalu';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' hari lalu';
    } elseif ($diff < 2419200) {
        return floor($diff / 604800) . ' minggu lalu';
    } else {
        return date('d M Y', $time);
    }
}
?>

<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>
    .filter-tabs {
        display: flex;
        border-bottom: 1px solid #e0e0e0;
        margin-bottom: 10px;
        overflow-x: auto;
    }

    .filter-tabs a {
        padding: 10px 16px;
        font-size: 15px;
        font-weight: 500;
        color: #666;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .filter-tabs a:hover {
        color: #333;
    }

    .filter-tabs a.active {
        color: #000;
        border-color: #999;
        font-weight: 600;
        background-color: #f7f7f7;
        border-radius: 4px 4px 0 0;
    }

    .filter-tabs a i {
        font-size: 16px;
    }

    @media (max-width: 576px) {
        .filter-tabs a {
            padding: 8px 12px;
            font-size: 14px;
        }
    }


    .vote-container {
        display: flex;
        gap: 12px;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .vote-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        font-size: 14px;
        font-weight: 500;
        border-radius: 20px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    }

    .vote-btn:hover {
        transform: translateY(-2px);
        opacity: 0.95;
    }

    .vote-up {
        background-color: #e6f4ea;
        color: #2e7d32;
        border-color: #c8e6c9;
    }

    .vote-down {
        background-color: #fdecea;
        color: #c62828;
        border-color: #f8bbd0;
    }

    .attachment-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background-color: #f0f4f8;
        color: #0d6efd;
        border-radius: 20px;
        text-decoration: none;
        font-size: 14px;
        transition: 0.2s ease;
        border: 1px solid #cfd8dc;
    }

    .attachment-btn:hover {
        background-color: #dfeaf5;
        color: #0a58ca;
        transform: translateY(-1px);
    }

    .attachment-btn i {
        font-size: 15px;
    }

    .badge-status {
        font-size: 13px;
        padding: 6px 10px;
        border-radius: 12px;
    }

    
    .bg-light.rounded.p-2.mb-2 {
        margin-top: 20px; 
    }
</style>


<div class="card-header">
    <h3 class="card-title">Hallo <b><?= esc($session->nama); ?></b>, Selamat datang</h3>
</div>

<div class="card-body">

    <!-- FORM AJUKAN INOVASI -->
    <div class="mb-5">
        <h4 class="mb-3">Ajukan Saran Baru</h4>
        <form action="<?= site_url('EInovasi/simpan') ?>" method="post" class="p-3 border rounded shadow-sm" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Inovasi</label>
                <input type="text" name="judul" id="judul" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label for="lampiran" class="form-label">Lampiran (Foto atau Dokumen)</label>
                <input type="file" name="lampiran" id="lampiran" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
            </div>

            <input type="hidden" name="pengusul" value="<?= esc($session->nama) ?>">
            <button type="submit" class="btn btn-success">Kirim Inovasi</button>
        </form>
    </div>


    <!-- LIST INOVASI -->
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Daftar Saran Perawat</h4>
            <div>
                <div class="filter-tabs">
                    <a href="<?= site_url('EInovasi/index') ?>"
                    class="<?= !isset($_GET['status']) ? 'active' : '' ?>">
                        <i class="bi bi-list"></i> Semua
                    </a>
                    <a href="<?= site_url('EInovasi/index?status=diajukan') ?>"
                    class="<?= ($_GET['status'] ?? '') === 'diajukan' ? 'active' : '' ?>">
                        <i class="bi bi-hourglass-split"></i> Diajukan
                    </a>
                    <a href="<?= site_url('EInovasi/index?status=disetujui') ?>"
                    class="<?= ($_GET['status'] ?? '') === 'disetujui' ? 'active' : '' ?>">
                        <i class="bi bi-check-circle"></i> Disetujui
                    </a>
                    <a href="<?= site_url('EInovasi/index?status=ditolak') ?>"
                    class="<?= ($_GET['status'] ?? '') === 'ditolak' ? 'active' : '' ?>">
                        <i class="bi bi-x-circle"></i> Tidak Disetujui
                    </a>
                </div>
            </div>
        </div>


        <?php if (!empty($inovasi)): ?>
            <div class="d-flex flex-column gap-4">
                <?php foreach ($inovasi as $item): ?>
                    <div class="border rounded shadow-sm p-3 bg-white">
                        <div class="d-flex">
                            <img src="<?= base_url('assets/dist/img/user/' . ($item['photo'] ?? 'default.png')) ?>" class="rounded-circle me-3" width="50" height="50" alt="user">
                            <div class="w-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0"><?= esc($item['pengusul']) ?></h5>
                                    <small class="text-muted"><?= esc($item['judul']) ?> · <?= timeAgo($item['created_at']) ?></small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-<?= $item['status'] === 'disetujui' ? 'success' : ($item['status'] === 'ditolak' ? 'danger' : 'secondary') ?>">
                                        <?= esc(ucfirst($item['status'] ?? 'menunggu')) ?>
                                    </span>
                                    <span class="badge bg-info"><?= esc($item['grade']) ?></span>
                                </div>
                            </div>


                                <p class="mt-2"><?= esc($item['deskripsi']) ?></p>

                                <?php if (!empty($item['lampiran'])): ?>
                                    <div class="mt-3">
                                        <?php $lampiranPath = base_url('uploads/inovasi/' . $item['lampiran']); ?>
                                        <a href="<?= esc($lampiranPath) ?>" target="_blank" class="attachment-btn">
                                            <i class="fas fa-paperclip"></i> Lampiran
                                        </a>
                                    </div>
                                <?php endif; ?>



                                <div class="vote-container">
                                    <form action="<?= site_url('EInovasi/vote') ?>" method="post">
                                        <input type="hidden" name="inovasi_id" value="<?= esc($item['id']) ?>">
                                        <input type="hidden" name="vote" value="setuju">
                                        <button type="submit" class="vote-btn vote-up">
                                            <i class="fas fa-thumbs-up"></i> Setuju (<?= $item['jumlah_setuju'] ?>)
                                        </button>
                                    </form>

                                    <form action="<?= site_url('EInovasi/vote') ?>" method="post">
                                        <input type="hidden" name="inovasi_id" value="<?= esc($item['id']) ?>">
                                        <input type="hidden" name="vote" value="tidak_setuju">
                                        <button type="submit" class="vote-btn vote-down">
                                            <i class="fas fa-thumbs-down"></i> Tidak Setuju (<?= $item['jumlah_tidak_setuju'] ?>)
                                        </button>
                                    </form>
                                </div>


                                
                               <!-- Komentar -->
                                <div class="bg-light rounded p-2 mb-2">
                                    <strong>Komentar:</strong>
                                    <div class="mt-2">
                                        <?php
                                            $totalKomentar = count($item['komentar']);
                                            $komentarAwal = array_slice($item['komentar'], 0, 2);
                                        ?>
                                        <?php if ($totalKomentar > 0): ?>
                                            <?php foreach ($komentarAwal as $komentar): ?>
                                                <div class="d-flex mb-3">
                                                    <img src="<?= base_url('assets/dist/img/user/' . ($komentar['photo'] ?? 'default.png')) ?>" class="rounded-circle me-2" width="35" height="35" alt="user">
                                                    <div>
                                                    <div><strong><?= esc($komentar['nama']) ?></strong> · <small class="text-muted"><?= timeAgo($komentar['created_at']) ?></small></div>
                                                    <small><?= esc($komentar['komentar']) ?></small>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                            <?php if ($totalKomentar > 2): ?>
                                                <button type="button" class="btn btn-sm btn-link" onclick="bukaModalKomentar(<?= $item['id'] ?>)">Lihat semua komentar</button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <p class="text-muted"><em>Belum ada komentar.</em></p>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Form Tambah Komentar -->
                                    <form action="<?= site_url('EInovasi/kirimkomentar') ?>" method="post" class="mt-2">
                                        <input type="hidden" name="inovasi_id" value="<?= esc($item['id']) ?>">
                                        <div class="input-group">
                                            <input type="text" name="komentar" class="form-control" placeholder="Tulis komentar..." required>
                                            <button class="btn btn-primary" type="submit">Kirim</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Catatan Karu -->
                                <?php if ($item['persetujuan']): ?>
                                    <div class="mb-2">
                                        <strong>Catatan Karu:</strong>
                                        <div class="border rounded bg-light p-2 mt-1"><?= esc($item['persetujuan']['catatan']) ?></div>
                                    </div>
                                <?php endif; ?>

                                <!-- Form Persetujuan -->
                                <?php if ($session->role === 'karu' && !$item['persetujuan']): ?>
                                    <form action="<?= site_url('inovasi/tanggapi-karu') ?>" method="post" class="border-top pt-3 mt-3">
                                        <input type="hidden" name="inovasi_id" value="<?= esc($item['id']) ?>">
                                        <div class="mb-2">
                                            <label class="form-label">Status Persetujuan:</label>
                                            <select name="status" class="form-select" required>
                                                <option value="disetujui">Disetujui</option>
                                                <option value="ditolak">Ditolak</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Catatan:</label>
                                            <textarea name="catatan" class="form-control" rows="3" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Kirim Persetujuan</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- MODAL KOMENTAR -->
            <?php foreach ($inovasi as $item): ?>
                <div class="modal fade" id="modalKomentar<?= $item['id'] ?>" tabindex="-1" aria-labelledby="modalKomentarLabel<?= $item['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalKomentarLabel<?= $item['id'] ?>">Semua Komentar</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <?php foreach ($item['komentar'] as $komentar): ?>
                                    <div class="d-flex mb-3">
                                        <img src="<?= base_url('assets/dist/img/user/' . ($komentar['photo'] ?? 'default.png')) ?>" class="rounded-circle me-2" width="40" height="40" alt="user">
                                        <div>
                                            <div>
                                                <strong><?= esc($komentar['nama']) ?></strong>
                                                · <small class="text-muted"><?= timeAgo($komentar['created_at']) ?></small> <!-- ⬅️ Tambahkan ini -->
                                            </div>
                                            <small class="text-muted"><?= esc($komentar['komentar']) ?></small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">Belum ada inovasi yang diajukan.</div>
        <?php endif; ?>
    </div>
</div>


<script>
    function bukaModalKomentar(inovasiId) {
        var modal = new bootstrap.Modal(document.getElementById('modalKomentar' + inovasiId));
        modal.show();
    }
</script>



<?php $this->endSection(); ?>
