<?= $this->extend('shared_page/template'); ?>

<?= $this->section('content'); ?>
<div class="card-body">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs" id="paketTabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#perencanaan">Perencanaan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#pelaksanaan">Pelaksanaan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#pembayaran">Pembayaran</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="perencanaan">
    <!-- Button untuk membuka modal -->
    <div class="d-flex justify-content-between">
    <a href="<?= base_url('ppk/paket/tambah_paket_perencanaan') ?>" class="btn btn-primary">
    Tambah
</a>
<div class="">
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#uploadModalPerencanaan">
    Impor
</button>

<a href="<?= base_url('ppk/paket/ekspor_paket_perencanaan') ?>" class="btn btn-info">
    Ekspor
</a></div>
</div>


<!-- Modal Impor Paket Perencanaan -->
<div class="modal fade" id="uploadModalPerencanaan" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Impor Paket Perencanaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('ppk/paket/import_csv_perencanaan') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="file_csv">Pilih file CSV:</label>
                        <input type="file" name="file_csv" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Tahun Anggaran</th>
                    <th>Kategori</th>
                    <th>Kode RUP</th>
                    <th>Nama Paket</th>
                    <th>Total Perencanaan (Rp)</th>
                    <th>PDN (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($paket_perencanaan)): ?>
                    <?php $no = 1; foreach ($paket_perencanaan as $paket): ?>
                        <tr>
                        <td class="text-center" ><?= $no++; ?></td>
                            <td><?= esc($paket['tahun_anggaran']); ?></td>
                            <td><?= esc($paket['kategori']); ?></td>
                            <td><?= esc($paket['kode_rup']); ?></td>
                            <td><?= esc($paket['nama_paket']); ?></td>
                            <td><?= number_format($paket['total_perencanaan'], 0, ',', '.'); ?></td>
                            <td><?= number_format($paket['pdn'], 0, ',', '.'); ?></td>
                            <td>
    <div class="center d-flex gap-2 justify-content-center">
        <a href="<?= base_url('/ppk/paket/edit_data_paket_perencanaan/' . $paket['id']); ?>" class="btn btn-warning btn-sm m-1">
            <i class="fas fa-edit"></i>
        </a>
        <a href="<?= base_url('/ppk/paket/hapus_data_paket_perencanaan/' . $paket['id']); ?>" class="btn btn-danger btn-sm m-1" onclick="return confirm('Yakin ingin menghapus?');">
            <i class="fas fa-trash"></i>
        </a>
    </div>
</td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="tab-pane fade" id="pelaksanaan">
   <!-- Button untuk membuka modal -->

   <div class="d-flex justify-content-between">
   <a href="<?= base_url('ppk/paket/tambah_paket_pelaksanaan') ?>" class="btn btn-primary">
    Tambah
</a>
    <div class="">
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#uploadModalPelaksanaan">
    Impor
</button>
<a href="<?= base_url('ppk/paket/ekspor_paket_pelaksanaan') ?>" class="btn btn-info">
    Ekspor
</a>
    </div>
   </div>
   


<!-- Modal Impor Paket Pelaksanaan -->
<div class="modal fade" id="uploadModalPelaksanaan" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabelPelaksanaan" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabelPelaksanaan">Impor Paket Pelaksanaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('ppk/paket/import_csv_pelaksanaan') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="file_csv">Pilih file CSV:</label>
                        <input type="file" name="file_csv" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
        <thead>
    <tr class="text-center">
        <th>No</th>
        <th>Tahun Anggaran</th>
        <th>Sumber Data</th>
        <th>Nama Penyedia</th>
        <th>Kode</th>
        <th>Kode RUP</th>
        <th>Nama Paket</th>
        <th>Total Pelaksanaan (Rp)</th>
        <th>PDN (Rp)</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
    <?php if (!empty($paket_pelaksanaan)): ?>
        <?php $no = 1; foreach ($paket_pelaksanaan as $paket): ?>
            <tr>
            <td class="text-center" ><?= $no++; ?></td>
                <td><?= esc($paket['tahun_anggaran']); ?></td>
                <td><?= esc($paket['sumber_data']); ?></td>
                <td><?= esc($paket['nama_penyedia']); ?></td>
                <td><?= esc($paket['kode']); ?></td>
                <td><?= esc($paket['kode_rup']); ?></td>
                <td><?= esc($paket['nama_paket']); ?></td>
                <td><?= number_format($paket['total_pelaksanaan'], 0, ',', '.'); ?></td>
                <td><?= number_format($paket['pdn'], 0, ',', '.'); ?></td>
                <td>
    <div class="center d-flex gap-2 justify-content-center">
        <a href="<?= base_url('/ppk/paket/edit_data_paket_pelaksanaan/' . $paket['id']); ?>" class="btn btn-warning btn-sm m-1">
            <i class="fas fa-edit"></i>
        </a>
        <a href="<?= base_url('/ppk/paket/hapus_data_paket_pelaksanaan/' . $paket['id']); ?>" class="btn btn-danger btn-sm m-1" onclick="return confirm('Yakin ingin menghapus?');">
            <i class="fas fa-trash"></i>
        </a>
    </div>
</td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="10" class="text-center">Tidak ada data tersedia.</td>
        </tr>
    <?php endif; ?>
</tbody>

        </table>
    </div>
</div>

<div class="tab-pane fade" id="pembayaran">
   <!-- Button untuk membuka modal -->
   <div class="d-flex justify-content-between">
   <a href="<?= base_url('ppk/paket/tambah_paket_pembayaran') ?>" class="btn btn-primary">
    Tambah
</a>
  
   <div class="">
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#uploadModalPembayaran">
    Impor
</button>
<a href="<?= base_url('ppk/paket/ekspor_paket_pembayaran') ?>" class="btn btn-info">
    Ekspor
</a>
   </div>
   </div>
   


<!-- Modal Impor Paket Pembayaran -->
<div class="modal fade" id="uploadModalPembayaran" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabelPembayaran" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabelPembayaran">Impor Paket Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('ppk/paket/import_csv_pembayaran') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="file_csv">Pilih file CSV:</label>
                        <input type="file" name="file_csv" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    <hr>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Tahun Anggaran</th>
                    <th>Sumber Data</th>
                    <th>Nama Penyedia</th>
                    <th>Kode Dokumen</th>
                    <th>Kode SP2D</th>
                    <th>Total Pembayaran (Rp)</th>
                    <th>PDN (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($paket_pembayaran)): ?>
                    <?php $no = 1; foreach ($paket_pembayaran as $paket): ?>
                        <tr>
                        <td class="text-center" ><?= $no++; ?></td>
                            <td><?= esc($paket['tahun_anggaran']); ?></td>
                            <td><?= esc($paket['sumber_data']); ?></td>
                            <td><?= esc($paket['nama_penyedia']); ?></td>
                            <td><?= esc($paket['kode_dokumen']); ?></td>
                            <td><?= esc($paket['kode_sp2d']); ?></td>
                            <td><?= number_format($paket['total_pembayaran'], 0, ',', '.'); ?></td>
                            <td><?= number_format($paket['pdn'], 0, ',', '.'); ?></td>
                            <td>
    <div class="center d-flex gap-2 justify-content-center">
        <a href="<?= base_url('/ppk/paket/edit_data_paket_pembayaran/' . $paket['id']); ?>" class="btn btn-warning btn-sm m-1">
            <i class="fas fa-edit"></i>
        </a>
        <a href="<?= base_url('/ppk/paket/hapus_data_paket_pembayaran/' . $paket['id']); ?>" class="btn btn-danger btn-sm m-1" onclick="return confirm('Yakin ingin menghapus?');">
            <i class="fas fa-trash"></i>
        </a>
    </div>
</td></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- Modal Konfirmasi Hapus -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus data ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="" id="deleteLink" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Konfirmasi Hapus -->
    </div>
</div>

    </div>
</div>

<script>
    function setDeleteLink(url) {
        document.getElementById('deleteLink').setAttribute('href', url);
    }
    // Hapus notifikasi saat tombol close diklik
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".alert .close").forEach(function(button) {
            button.addEventListener("click", function() {
                this.parentElement.style.display = "none";
            });
        });
    });
</script>

<?= $this->endSection(); ?>
