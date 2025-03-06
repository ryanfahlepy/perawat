<?php $this->extend('shared_page/template'); ?>

<?php $this->section('content'); ?>
<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="card-body">
    <form id="detailForm" method="POST" action="<?= base_url('pokja/pengadaan/update_pengadaan/' . $pengadaan['id']); ?>">
        <table class="table table-bordered">
            <tr>
                <th>Nama Pengadaan</th>
                <td>
                    <input type="text" name="nama_pengadaan" value="<?= $pengadaan['nama_pengadaan']; ?>" id="nama_pengadaan" class="form-control" readonly>
                </td>
            </tr>
            <tr>
                <th>Metode Pemilihan</th>
                <td>
                    <select name="ref_tabel" id="ref_tabel" class="form-control" disabled>
                        <option value="1" <?= ($pengadaan['ref_tabel'] == '1') ? 'selected' : '' ?>>Penunjukkan Langsung</option>
                        <option value="2" <?= ($pengadaan['ref_tabel'] == '2') ? 'selected' : '' ?>>Tender</option>
                        <option value="3" <?= ($pengadaan['ref_tabel'] == '3') ? 'selected' : '' ?>>E-Purchasing</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>PPK</th>
                <td>
                    <input type="text" name="ppk" value="<?= $pengadaan['ppk']; ?>" id="ppk" class="form-control" readonly>
                </td>
            </tr>
            <tr>
                <th>Pokja/PP</th>
                <td>
                    <input type="text" name="pokja_pp" value="<?= $pengadaan['pokja_pp']; ?>" id="pokja_pp" class="form-control" readonly>
                </td>
            </tr>
            <tr>
                <th>Nama Penyedia</th>
                <td>
                    <input type="text" name="nama_penyedia" value="<?= $pengadaan['nama_penyedia']; ?>" id="nama_penyedia" class="form-control" readonly>
                </td>
            </tr>
            <tr>
                <th>Tanggal Mulai</th>
                <td>
                    <input type="date" name="tanggal_mulai" value="<?= date('Y-m-d', strtotime($pengadaan['tanggal_mulai'])); ?>" id="tanggal_mulai" class="form-control" readonly>
                </td>
            </tr>
            <tr>
                <th>Tanggal Berakhir</th>
                <td>
                    <input type="date" name="tanggal_berakhir" value="<?= date('Y-m-d', strtotime($pengadaan['tanggal_berakhir'])); ?>" id="tanggal_berakhir" class="form-control" readonly>
                </td>
            </tr>
        </table>

        <div class="d-flex">
            <a href="<?= base_url('pokja/pengadaan'); ?>" class="btn btn-secondary mr-2">Kembali</a>
            <button type="button" id="editBtn" class="btn btn-warning" onclick="editData()">Edit</button>
            <button type="submit" id="saveBtn" class="btn btn-success" style="display:none;">Simpan</button>
        </div>
    </form>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5>Daftar Dokumen</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="text-center">
                <tr>
                <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama</th>
                    <th style="width: 60%;">File</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
    <?php if (!empty($dokumenList)) : ?>
        <?php $no = 1; ?>
        <?php foreach ($dokumenList as $dokumen) : ?>
            <tr>
                <td class="text-center" ><?= $no++; ?></td>
                <td><?= esc($dokumen['dokumen']); ?></td>
                <td class="text-left">
                    <?php 
                    $found = false;
                    foreach ($fileList as $file) {
                        if ($file['ref_id_dokumen'] == $dokumen['id_dokumen']) {
                            $filePath = base_url('uploads/' . $pengadaan['id'] . '/' . $file['nama_file']);
                            echo '<div class="d-flex align-items-center mb-2">
                                    <a href="' . esc($filePath) . '" target="_blank" class="mr-2">
                                        <i class="fas fa-file-pdf text-danger"></i> ' . esc($file['nama_file']) . '
                                    </a>
                                    <a href="' . base_url('pokja/pengadaan/hapus_file/' . $file['id']) . '" class="text-danger ml-2" onclick="return confirm(\'Apakah Anda yakin ingin menghapus file ini?\')">
    <i class="fas fa-trash-alt"></i> <!-- Ikon hapus -->
</a>

                                  </div>';
                            $found = true;
                        }
                    }
                    if (!$found) {
                        echo '<span class="text-muted">Tidak ada file</span>';
                    }
                    ?>
                </td>
                <td class="text-center">
                    <button class="btn btn-primary btn-sm" onclick="showUploadModal(<?= $pengadaan['id']; ?>, <?= $dokumen['id_dokumen']; ?>)">Unggah</button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="4" class="text-center">Tidak ada dokumen.</td>
        </tr>
    <?php endif; ?>
</tbody>

        </table>
    </div>
</div>

<!-- Modal Unggah Dokumen -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Unggah Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uploadForm" action="<?= base_url('pokja/pengadaan/unggah_dokumen'); ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="uploadPengadaanId" name="id_pengadaan">
                    <input type="hidden" id="uploadDokumenId" name="id_dokumen">
                    <div class="form-group">
                        <label for="fileDokumen">Pilih File</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Unggah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hapusModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus dokumen ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<script>
    function showUploadModal(pengadaanId, dokumenId) {
        document.getElementById('uploadPengadaanId').value = pengadaanId;
        document.getElementById('uploadDokumenId').value = dokumenId;
        $('#uploadModal').modal('show');
    }

    function confirmHapus(id) {
        let url = "<?= base_url('pokja/pengadaan/hapus_dokumen/'); ?>" + id;
        document.getElementById('confirmDeleteBtn').setAttribute('href', url);
        $('#hapusModal').modal('show');
    }
</script>
<script>
    function editData() {
        document.getElementById('saveBtn').style.display = 'inline-block';
        document.getElementById('editBtn').style.display = 'none';
        document.getElementById('nama_pengadaan').removeAttribute('readonly');
        document.getElementById('ref_tabel').removeAttribute('disabled');
        document.getElementById('ppk').removeAttribute('readonly');
        document.getElementById('pokja_pp').removeAttribute('readonly');
        document.getElementById('nama_penyedia').removeAttribute('readonly');
        document.getElementById('tanggal_mulai').removeAttribute('readonly');
        document.getElementById('tanggal_berakhir').removeAttribute('readonly');
    }
</script>

<?php $this->endSection(); ?>
