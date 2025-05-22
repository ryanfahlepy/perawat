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

.vote-container {
    display: flex;
    gap: 10px;
    margin-top: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.vote-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    font-size: 14px;
    border-radius: 50px;
    border: 1px solid transparent;
    background-color: #f1f1f1;
    transition: all 0.3s ease;
    cursor: pointer;
    font-weight: 500;
    color: #444;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    position: relative;
}

.vote-btn i {
    font-size: 16px;
    transition: transform 0.2s ease;
}

.vote-btn:hover i {
    transform: scale(1.1);
}

.vote-btn:hover {
    background-color: #e0e0e0;
}

.vote-up.active-vote {
    background-color: #d4edda;
    border-color: #81c784;
    color: #2e7d32;
}

.vote-up.active-vote i {
    color: #1b5e20;
}

.vote-down.active-vote {
    background-color: #f8d7da;
    border-color: #e57373;
    color: #c62828;
}

.vote-down.active-vote i {
    color: #b71c1c;
}

.vote-btn.inactive-vote {
    opacity: 0.6;
    filter: grayscale(40%);
}

.attachment-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    background-color: #e3f2fd;
    color: #1565c0;
    border-radius: 50px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 1px solid #90caf9;
    box-shadow: 0 1px 4px rgba(21, 101, 192, 0.1);
}

.attachment-btn i {
    font-size: 16px;
    color: #1565c0;
    transition: transform 0.2s ease;
}

.attachment-btn:hover {
    background-color: #bbdefb;
    color: #0d47a1;
    transform: translateY(-1px);
}

.attachment-btn:hover i {
    transform: scale(1.1);
}


.attachment-btn i {
    font-size: 15px;
}

.action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 12px;
    flex-wrap: wrap;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 20px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.btn-action i {
    font-size: 14px;
}

.btn-approve {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.btn-reject {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.btn-delete {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
}

.btn-action:hover {
    transform: translateY(-1px);
    opacity: 0.95;
}

.action-row {
    display: flex;
    gap: 12px;
    margin-top: 15px;
    flex-wrap: wrap;
    align-items: center;
}



</style>


<div class="card-header">
    <h3 class="card-title">Hallo <b><?= esc($session->nama); ?></b>, Selamat datang</h3>
</div>

<div class="card-body">

    <?php if ($level_user != 2): ?>
        <!-- FORM AJUKAN INOVASI -->
        <div class="mb-5">
            <h4 class="mb-3">Ajukan Saran Baru</h4>
            <form action="<?= site_url('Inovasi/simpan') ?>" method="post" class="p-3 border rounded shadow-sm" enctype="multipart/form-data">
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
    <?php endif; ?>


    <!-- LIST INOVASI -->
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Daftar Saran Perawat</h4>
            <div>
                <div class="filter-tabs">
                    <a href="<?= site_url('Inovasi/index') ?>"
                    class="<?= !isset($_GET['status']) ? 'active' : '' ?>">
                        <i class="bi bi-list"></i> Semua
                    </a>
                    <a href="<?= site_url('Inovasi/index?status=diajukan') ?>"
                    class="<?= ($_GET['status'] ?? '') === 'diajukan' ? 'active' : '' ?>">
                        <i class="bi bi-hourglass-split"></i> Diajukan
                    </a>
                    <a href="<?= site_url('Inovasi/index?status=disetujui') ?>"
                    class="<?= ($_GET['status'] ?? '') === 'disetujui' ? 'active' : '' ?>">
                        <i class="bi bi-check-circle"></i> Disetujui
                    </a>
                    <a href="<?= site_url('Inovasi/index?status=ditolak') ?>"
                    class="<?= ($_GET['status'] ?? '') === 'ditolak' ? 'active' : '' ?>">
                        <i class="bi bi-x-circle"></i> Ditolak
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

                                <?php if (!empty($item['catatan'])): ?>
                                    <div class="alert alert-warning mt-2">
                                        <strong>Catatan Karu:</strong><br>
                                        <?= nl2br(esc($item['catatan'])) ?>
                                    </div>
                                <?php endif; ?>


                                <div class="action-row mb-3">
                                    <?php if (!empty($item['lampiran'])): ?>
                                        <?php $lampiranPath = base_url('uploads/inovasi/' . $item['lampiran']); ?>
                                        <a href="<?= esc($lampiranPath) ?>" target="_blank" class="attachment-btn">
                                            <i class="fas fa-paperclip"></i> Lihat Lampiran
                                        </a>
                                    <?php endif; ?>

                                    <?php $userVote = $item['user_vote'] ?? null; ?>
                                    
                                    <button type="button"
                                            class="vote-btn vote-up <?= $userVote === 'setuju' ? 'active-vote' : 'inactive-vote' ?>"
                                            data-vote="setuju"
                                            data-id="<?= $item['id'] ?>">
                                        <i class="fas fa-thumbs-up"></i> Suka <span>(<?= $item['jumlah_setuju'] ?>)</span>
                                    </button>

                                    <button type="button"
                                            class="vote-btn vote-down <?= $userVote === 'tidak_setuju' ? 'active-vote' : 'inactive-vote' ?>"
                                            data-vote="tidak_setuju"
                                            data-id="<?= $item['id'] ?>">
                                        <i class="fas fa-thumbs-down"></i> Tidak Suka <span>(<?= $item['jumlah_tidak_setuju'] ?>)</span>
                                    </button>
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
                                    <form action="<?= site_url('Inovasi/kirimkomentar') ?>" method="post" class="mt-2">
                                        <input type="hidden" name="inovasi_id" value="<?= esc($item['id']) ?>">
                                        <div class="input-group">
                                            <input type="text" name="komentar" class="form-control" placeholder="Tulis komentar..." required>
                                            <button class="btn btn-primary" type="submit">Kirim</button>
                                        </div>
                                    </form>
                                </div>


                                <!-- Form Persetujuan Karu -->
                                <?php if ($level_user == 2):?>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-action btn-approve" data-bs-toggle="modal" data-bs-target="#modalPersetujuan<?= $item['id'] ?>" data-aksi="setujui">
                                            <i class="fas fa-check-circle"></i> Setujui
                                        </button>

                                        <button type="button" class="btn btn-action btn-reject" data-bs-toggle="modal" data-bs-target="#modalPersetujuan<?= $item['id'] ?>" data-aksi="tolak">
                                            <i class="fas fa-times-circle"></i> Tolak
                                        </button>

                                        <button type="button" class="btn btn-action btn-delete" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $item['id'] ?>">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </div>

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

            <!-- Modal Persetujuan Karu -->
            <div class="modal fade" id="modalPersetujuan<?= $item['id'] ?>" tabindex="-1" aria-labelledby="modalPersetujuanLabel<?= $item['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="<?= site_url('Inovasi/persetujuan') ?>" method="post" class="modal-content">
                        <?= csrf_field() ?>
                        <input type="hidden" name="inovasi_id" value="<?= $item['id'] ?>">
                        <input type="hidden" name="aksi" id="aksiInput<?= $item['id'] ?>">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalPersetujuanLabel<?= $item['id'] ?>">Catatan Persetujuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea name="catatan" class="form-control" rows="4" placeholder="Tuliskan catatan Anda (opsional)"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Hapus Inovasi -->
            <div class="modal fade" id="modalHapus<?= $item['id'] ?>" tabindex="-1" aria-labelledby="modalHapusLabel<?= $item['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="<?= site_url('Inovasi/hapus') ?>" method="post" class="modal-content">
                        <?= csrf_field() ?>
                        <input type="hidden" name="inovasi_id" value="<?= $item['id'] ?>">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalHapusLabel<?= $item['id'] ?>">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus inovasi ini?</p>
                            <strong><?= esc($item['judul']) ?></strong><br>
                            <small class="text-muted"><?= esc($item['pengusul']) ?> · <?= timeAgo($item['created_at']) ?></small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>


        <?php else: ?>
            <div class="alert alert-info">Belum ada inovasi.</div>
        <?php endif; ?>
    </div>
</div>


<script>
    function bukaModalKomentar(inovasiId) {
        var modal = new bootstrap.Modal(document.getElementById('modalKomentar' + inovasiId));
        modal.show();
    }

    document.querySelectorAll('[data-bs-target^="#modalPersetujuan"]').forEach(button => {
        button.addEventListener('click', function () {
            const modalId = this.getAttribute('data-bs-target').replace('#', '');
            const aksi = this.getAttribute('data-aksi');
            const inputAksi = document.querySelector(`#${modalId} input[name="aksi"]`);
            if (inputAksi) inputAksi.value = aksi;
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.vote-btn').forEach(button => {
        button.addEventListener('click', function () {
            const inovasiId = this.getAttribute('data-id');
            const voteType = this.getAttribute('data-vote');

            fetch("<?= site_url('Inovasi/voteAjax') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `inovasi_id=${inovasiId}&vote=${voteType}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const container = this.closest('.action-row');
                    // Update tampilan tombol
                    container.querySelectorAll('.vote-btn').forEach(btn => {
                        btn.classList.remove('active-vote');
                        btn.classList.add('inactive-vote');
                    });

                    // Jika vote aktif, toggle
                    if (data.user_vote) {
                        const btnToActivate = container.querySelector(`.vote-btn[data-vote="${data.user_vote}"]`);
                        btnToActivate.classList.remove('inactive-vote');
                        btnToActivate.classList.add('active-vote');
                    }

                    // Update jumlah
                    container.querySelector('.vote-up span').textContent = `(${data.jumlah_setuju})`;
                    container.querySelector('.vote-down span').textContent = `(${data.jumlah_tidak_setuju})`;
                }
            });
        });
    });
});
</script>




<?php $this->endSection(); ?>
