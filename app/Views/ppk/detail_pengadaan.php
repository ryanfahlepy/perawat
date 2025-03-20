<?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<div class="card-body">
    <form action="<?= base_url('/pengadaan/update_pengadaan/' . $pengadaan['id']); ?>" method="post">
        <div class="row">
            <!-- Kolom 1 -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tahun_anggaran">Tahun Anggaran</label>
                    <input type="number" class="form-control" id="tahun_anggaran" name="tahun_anggaran"
                        value="<?= old('tahun_anggaran', $pengadaan['tahun_anggaran']); ?>" required readonly>
                </div>
                <div class="form-group">
                    <label for="dipa">DIPA</label>
                    <select class="form-control" id="dipa" name="dipa" required disabled>
                        <option value=""> --- Pilih DIPA ---</option>
                        <option value="DISINFOLAHTAL" <?= old('dipa', $pengadaan['dipa']) == 'DISINFOLAHTAL' ? 'selected' : ''; ?>>DISINFOLAHTAL</option>
                        <option value="MABES TNI AL" <?= old('dipa', $pengadaan['dipa']) == 'MABES TNI AL' ? 'selected' : ''; ?>>MABES TNI AL</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="kode_rup">Kode RUP</label>
                    <input type="text" class="form-control" id="kode_rup" name="kode_rup"
                        value="<?= old('kode_rup', $pengadaan['kode_rup']); ?>" required readonly>
                </div>
            </div>

            <!-- Kolom 2 -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="jenis">Jenis</label>
                    <input type="text" class="form-control" id="jenis" name="jenis"
                        value="<?= old('jenis', $pengadaan['jenis']); ?>" required readonly>
                </div>
                <div class="form-group">
                    <label for="metode">Metode</label>
                    <input type="text" class="form-control" id="metode" name="metode"
                        value="<?= old('metode', $pengadaan['metode']); ?>" required readonly>
                </div>
                <div class="form-group">
                    <label for="nama_pengadaan">Nama Pengadaan</label>
                    <input type="text" class="form-control" id="nama_pengadaan" name="nama_pengadaan"
                        value="<?= old('nama_pengadaan', $pengadaan['nama_pengadaan']); ?>" required readonly>
                </div>
            </div>

            <!-- Kolom 3 -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="perencanaan">Perencanaan</label>
                    <input type="number" class="form-control" id="perencanaan" name="perencanaan"
                        value="<?= old('perencanaan', $pengadaan['perencanaan']); ?>" required readonly>
                </div>
                <div class="form-group">
                    <label for="pelaksanaan">Pelaksanaan</label>
                    <input type="number" class="form-control" id="pelaksanaan" name="pelaksanaan"
                        value="<?= old('pelaksanaan', $pengadaan['pelaksanaan']); ?>" required readonly>
                </div>
                <div class="form-group">
                    <label for="pembayaran">Pembayaran</label>
                    <input type="number" class="form-control" id="pembayaran" name="pembayaran"
                        value="<?= old('pembayaran', $pengadaan['pembayaran']); ?>" required readonly>
                </div>
            </div>
        </div>


        <div class="d-flex justify-content-between mt-3">
            <div>
                <a href="javascript:void(0);" class="btn btn-secondary mr-2" onclick="closeTab()">Kembali</a>
                <button type="button" id="editBtn" class="btn btn-warning" style="color: white;"
                    onclick="editData()">Edit</button>
                <button type="submit" id="saveBtn" class="btn btn-success" style="display:none;">Simpan</button>
            </div>
            <button type="button" id="downloadAllBtn" class="btn btn-primary"
                onclick="location.href='<?= base_url('pengadaan/unduh_semua_dokumen/' . $pengadaan['id']) ?>'">
                Unduh Semua Dokumen
            </button>
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
                    <th style="width: 20%;">Nama</th>
                    <th style="width: 35%;">Dokumen</th>
                    <th style="width: 20%;">Waktu Unggah</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dokumenList)): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($dokumenList as $dokumen): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
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
                                        <a href="#" class="text-danger ml-2" onclick="confirmDelete(\'' . base_url('pengadaan/hapus_dokumen/' . $file['id']) . '\'); return false;">
                                        <i class="fas fa-trash-alt"></i>
                                        </a>
                                        </div>';
                                        $found = true;
                                    }
                                }
                                if (!$found) {
                                    echo '<span class="text-muted">Tidak ada Dokumen</span>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php
                                $uploadTimes = [];
                                foreach ($fileList as $file) {
                                    if ($file['ref_id_dokumen'] == $dokumen['id_dokumen']) {
                                        $uploadTimes[] = date('d-m-Y H:i', strtotime($file['created_at']));
                                    }
                                }
                                echo !empty($uploadTimes) ? implode('<br>', $uploadTimes) : '<span class="text-muted">-</span>';
                                ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm"
                                    onclick="showUploadModal(<?= $pengadaan['id']; ?>, <?= $dokumen['id_dokumen']; ?>)">Unggah</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada dokumen.</td>
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
            <form id="uploadForm" action="<?= base_url('pengadaan/unggah_dokumen'); ?>" method="POST"
                enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="uploadPengadaanId" name="id_pengadaan">
                    <input type="hidden" id="uploadDokumenId" name="id_dokumen">
                    <div class="form-group">
                        <label for="fileDokumen">Pilih Dokumen</label>
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

<!-- Tambahkan script SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function closeTab() {
        window.close();
    }
    // Fungsi SweetAlert2 untuk konfirmasi hapus
    const confirmDelete = (url) => {
    
        Swal.fire({
            title: "Konfirmasi Hapus",
            text: "Apakah Anda yakin ingin menghapus dokumen ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal"
        }).then((result) => {
        
            if (result.value) {  // <- Gunakan `value` bukan `isConfirmed`
                
                window.location.href = url;
            }
        });
    };

    // Fungsi untuk menampilkan modal upload
    const showUploadModal = (pengadaanId, dokumenId) => {
        document.getElementById("uploadPengadaanId").value = pengadaanId;
        document.getElementById("uploadDokumenId").value = dokumenId;
        $("#uploadModal").modal("show");
    };

    // Fungsi konfirmasi hapus (dengan modal Bootstrap)
    const confirmHapus = (id) => {
        const url = "<?= base_url('pengadaan/hapus_dokumen/'); ?>" + id;
        document.getElementById("confirmDeleteBtn").setAttribute("href", url);
        $("#hapusModal").modal("show");
    };

    // Fungsi untuk mengaktifkan mode edit
    const editData = () => {
        const fields = [
            "tahun_anggaran", "jenis", "metode", "kode_rup",
            "nama_pengadaan", "perencanaan", "pelaksanaan", "pembayaran"
        ];

        document.getElementById("saveBtn").style.display = "inline-block";
        document.getElementById("editBtn").style.display = "none";
        document.getElementById("dipa").removeAttribute("disabled");

        fields.forEach(id => document.getElementById(id).removeAttribute("readonly"));
    };

    // Tampilkan notifikasi jika ada flashdata sukses
    <?php if (session()->getFlashdata('success')): ?>
        Swal.fire({
            title: "Berhasil",
            text: "<?= session()->getFlashdata('success'); ?>",
            icon: "success",
            confirmButtonText: "OK"
        });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        Swal.fire({
            title: "Gagal",
            text: "<?= session()->getFlashdata('error'); ?>",
            icon: "error",
            confirmButtonText: "OK"
        });
    <?php endif; ?>
</script>

<?php $this->endSection(); ?>