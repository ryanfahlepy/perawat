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



    <!-- Tab Content -->
    <div class="tab-content mt-3">
        <div class="tab-pane fade show active">
            <!-- Button untuk membuka modal -->
            <div class="d-flex justify-content-between">
                <div class="">
                    <a href="<?= base_url('pengadaan/tambah_pengadaan') ?>" class="btn btn-primary">
                        Tambah
                    </a>


                </div>
                <div class="">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#uploadModal">
                        Impor
                    </button>

                    <a href="<?= base_url('pengadaan/ekspor_pengadaan') ?>" class="btn btn-info">
                        Ekspor
                    </a>
                </div>
            </div>


            <!-- Modal Impor Pengadaan -->
            <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel">Impor Pengadaan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="<?= base_url('pengadaan/import_csv') ?>" method="post"
                                enctype="multipart/form-data">
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
            <!-- Input Pencarian -->
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <select id="filterTahun" class="form-control text-center" onchange="filterData()"
                        style="width: 140px;">
                        <option value="">Semua Tahun</option>
                        <?php
                        $tahun_anggaran_list = array_unique(array_column($pengadaan, 'tahun_anggaran'));
                        sort($tahun_anggaran_list);
                        foreach ($tahun_anggaran_list as $tahun) {
                            echo "<option value='$tahun'>$tahun</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="text" id="search" class="form-control ml-2" placeholder="Cari data pengadaan ..."
                    onkeyup="cariData('search', 'tablePengadaan')">
            </div>

            <div class="table-responsive">
                <table id="tablePengadaan" class="table table-bordered table-striped">

                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Tahun Anggaran</th>
                            <th>DIPA</th>
                            <th>Jenis</th>
                            <th>Metode</th>
                            <th>Kode RUP</th>
                            <th>Nama Pengadaan</th>
                            <th>Perencanaan (Rp)</th>
                            <th>Pelaksanaan (Rp)</th>
                            <th>Pembayaran (Rp)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pengadaan)): ?>
                            <?php $no = 1;
                            foreach ($pengadaan as $p): ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-center tahun-anggaran"><?= esc($p['tahun_anggaran']); ?></td>
                                    <td class="text-center"><?= esc($p['dipa']); ?></td>
                                    <td><?= esc($p['jenis']); ?></td>
                                    <td><?= esc($p['metode']); ?></td>
                                    <td>
                                        <span
                                            onclick="window.open('<?= base_url('/pengadaan/detail_pengadaan/' . $p['id']); ?>', '_blank')"
                                            style="cursor: pointer; text-decoration: underline; color: blue;">
                                            <?= esc($p['kode_rup']); ?>
                                        </span>
                                    </td>
                                    <td>

                                        <?= esc($p['nama_pengadaan']); ?>

                                    </td>


                                    <td><?= number_format($p['perencanaan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($p['pelaksanaan'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($p['pembayaran'], 0, ',', '.'); ?></td>
                                    <td>
                                        <div class="center d-flex gap-2 justify-content-center">
                                            <a href="<?= base_url('/pengadaan/detail_pengadaan/' . $p['id']); ?>" target="_blank"
                                                style="color: white;" class="btn btn-info btn-sm m-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('/pengadaan/hapus_data_pengadaan/' . $p['id']); ?>"
                                                class="btn btn-danger btn-sm m-1"
                                                onclick="return confirm('Yakin ingin menghapus?');">
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
    </div>
</div>

<script>
    function filterData() {
        var tahunFilter = document.getElementById("filterTahun").value.toLowerCase();
        var searchFilter = document.getElementById("search").value.toLowerCase();
        var table = document.getElementById("tablePengadaan");
        var tr = table.getElementsByTagName("tr");

        for (var i = 1; i < tr.length; i++) {
            var tahunCell = tr[i].getElementsByClassName("tahun-anggaran")[0];
            var allCells = tr[i].getElementsByTagName("td");
            var rowVisible = true;

            if (tahunCell && tahunFilter !== "") {
                var tahunText = tahunCell.textContent || tahunCell.innerText;
                if (tahunText !== tahunFilter) {
                    rowVisible = false;
                }
            }

            if (rowVisible && searchFilter !== "") {
                rowVisible = false;
                for (var j = 0; j < allCells.length; j++) {
                    var txtValue = allCells[j].textContent || allCells[j].innerText;
                    if (txtValue.toLowerCase().indexOf(searchFilter) > -1) {
                        rowVisible = true;
                        break;
                    }
                }
            }

            tr[i].style.display = rowVisible ? "" : "none";
        }
    }
    function cariData(inputId, tableId) {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById(inputId);
        filter = input.value.toLowerCase();
        table = document.getElementById(tableId);
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) { // Mulai dari 1 biar skip thead
            td = tr[i].getElementsByTagName("td");
            let rowVisible = false;
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        rowVisible = true;
                        break;
                    }
                }
            }
            tr[i].style.display = rowVisible ? "" : "none";
        }
    }

    function setDeleteLink(url) {
        document.getElementById('deleteLink').setAttribute('href', url);
    }
    // Hapus notifikasi saat tombol close diklik
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".alert .close").forEach(function (button) {
            button.addEventListener("click", function () {
                this.parentElement.style.display = "none";
            });
        });
    });
</script>

<?= $this->endSection(); ?>