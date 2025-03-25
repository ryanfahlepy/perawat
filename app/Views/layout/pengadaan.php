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

    <?php if ($level_akses === 'Pokja'): ?>
            <div class="pokja">
                <form method="get">
                    <label for="tahun">Tahun Anggaran : </label>
                    <select name="tahun" id="tahun" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        <?php foreach ($tahun_tersedia as $t): ?>
                            <option value="<?= $t['tahun_anggaran'] ?>" <?= $t['tahun_anggaran'] == $tahun_dipilih ? 'selected' : '' ?>>
                                <?= $t['tahun_anggaran'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <div class="row">
                    <!-- JUMLAH PENGADAAN -->
                    <div class="col-3 position-relative">
                        <div class="small-box bg-info"
                            onmouseout="hideDetail('detail-pengadaan')">
                            <div class="inner">
                            <h3 id="jumlahPengadaan"><?= $jumlah_pengadaan; ?></h3>
                                <p>Jumlah Pengadaan</p>
                            </div>
                            <div class="icon"><i class="ion ion-bag"></i></div>
                        </div>
                    </div>

                    <!-- TOTAL PERENCANAAN -->
                    <div class="col-3 position-relative">
                        <div class="small-box" style="background-color:#DBB300FF; color:white"
                            onmouseover="showDetail('detail-perencanaan')">
                            <div class="inner">
                            <h3 id="totalPerencanaan">Rp <?= number_format($total_perencanaan, 0, ',', '.'); ?></h3>
                                <p>Total Perencanaan</p>
                            </div>
                            <div class="icon"><i class="ion ion-bag"></i></div>
                        </div>
                    </div>

                    <!-- TOTAL PELAKSANAAN -->
                    <div class="col-3 position-relative">
                        <div class="small-box bg-success">
                            <div class="inner">
                            <h3 id="totalPelaksanaan">Rp <?= number_format($total_pelaksanaan, 0, ',', '.'); ?></h3>
                                <p>Total Pelaksanaan</p>
                            </div>
                            <div class="icon"><i class="ion ion-bag"></i></div>
                        </div>
                    </div>

                    <!-- TOTAL PEMBAYARAN -->
                    <div class="col-3 position-relative">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3 id="totalPembayaran">Rp <?= number_format($total_pembayaran, 0, ',', '.'); ?></h3>
                                <p>Total Pembayaran</p>
                            </div>
                            <div class="icon"><i class="ion ion-bag"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ($level_akses === 'PP'): ?>
            <div class="pp">
                <form method="get">
                    <label for="tahun">Tahun Anggaran : </label>
                    <select name="tahun" id="tahun" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        <?php foreach ($tahun_tersedia as $t): ?>
                            <option value="<?= $t['tahun_anggaran'] ?>" <?= $t['tahun_anggaran'] == $tahun_dipilih ? 'selected' : '' ?>>
                                <?= $t['tahun_anggaran'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <div class="row">
                    <!-- JUMLAH PENGADAAN -->
                    <div class="col-3 position-relative">
                        <div class="small-box bg-info">
                            <div class="inner">
                            <h3 id="jumlahPengadaan"><?= $jumlah_pengadaan; ?></h3>
                                <p>Jumlah Pengadaan</p>
                            </div>
                            <div class="icon"><i class="ion ion-bag"></i></div>
                        </div>
                    </div>

                    <!-- TOTAL PERENCANAAN -->
                    <div class="col-3 position-relative">
                        <div class="small-box" style="background-color:#DBB300FF; color:white">
                            <div class="inner">
                            <h3 id="totalPerencanaan">Rp <?= number_format($total_perencanaan, 0, ',', '.'); ?></h3>
                                <p>Total Perencanaan</p>
                            </div>
                            <div class="icon"><i class="ion ion-bag"></i></div>
                        </div>
                    </div>

                    <!-- TOTAL PELAKSANAAN -->
                    <div class="col-3 position-relative">
                        <div class="small-box bg-success">
                            <div class="inner">
                            <h3 id="totalPelaksanaan">Rp <?= number_format($total_pelaksanaan, 0, ',', '.'); ?></h3>
                                <p>Total Pelaksanaan</p>
                            </div>
                            <div class="icon"><i class="ion ion-bag"></i></div>
                        </div>
                    </div>

                    <!-- TOTAL PEMBAYARAN -->
                    <div class="col-3 position-relative">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3 id="totalPembayaran">Rp <?= number_format($total_pembayaran, 0, ',', '.'); ?></h3>
                                <p>Total Pembayaran</p>
                            </div>
                            <div class="icon"><i class="ion ion-bag"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>




    <!-- Tab Content -->
    <div class="tab-content">
        <div class="tab-pane fade show active">
            <!-- Button untuk membuka modal -->
            <div class="d-flex justify-content-between">
                <div class="">
                <?php if ($level_akses ==! 'PPK'): ?>    
                <a href="<?= base_url('pengadaan/tambah_pengadaan') ?>" class="btn btn-primary">
                        Tambah
                    </a>
                    <?php endif ?>
                </div>
                <div class="">
                    <a href="<?= base_url('pengadaan/ekspor_pengadaan') ?>" class="btn btn-success">
                        Ekspor
                    </a>
                </div>
            </div>
            <hr>
            <!-- Input Pencarian -->
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div>
                <select id="filterTahun" class="form-control text-center" onchange="filterTahun(); filterData();" style="width: 140px;">
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
                            <th>Tahun</th>
                            <th>DIPA</th>
                            <th>Metode</th>
                            <th>Kode RUP</th>
                            <th>Nama Pengadaan</th>
                            <th>Progress</th>
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
                                    <!-- <td><?= esc($p['jenis']); ?></td> -->
                                    <td><?= esc($p['metode']); ?></td>
                                    <td class="text-center">
                                        <span
                                            onclick="window.open('<?= base_url('/pengadaan/detail_pengadaan/' . $p['id']); ?>', '_blank')"
                                            style="cursor: pointer; color: blue;">
                                            <?= esc($p['kode_rup']); ?>
                                        </span>
                                    </td>
                                    <td>

                                        <?= esc($p['nama_pengadaan']); ?>
                                    </td>
                                    <td>
                                        <!-- Tampilkan Progress dalam bentuk Persentase -->
                                        <div
                                            style="width: 100%; background-color: #e0e0e0; border-radius: 5px; overflow: hidden;">
                                            <div
                                                style="width: <?= $p['progress']; ?>%; background-color: <?= $p['progress'] >= 75 ? 'green' : ($p['progress'] >= 50 ? 'yellow' : 'red'); ?>; height: 20px;">
                                            </div>
                                        </div>
                                        <div style="text-align: center;"><?= $p['progress']; ?>%</div>
                                    </td>


                                    <td class="text-right"><?= number_format($p['perencanaan'], 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($p['pelaksanaan'], 0, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($p['pembayaran'], 0, ',', '.'); ?></td>
                                    <td>
                                        <div class="center d-flex gap-2 justify-content-center">
                                            <a href="<?= base_url('/pengadaan/detail_pengadaan/' . $p['id']); ?>"
                                                target="_blank" style="color: white;" class="btn btn-info btn-sm m-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <?php if ($level_akses ==! 'PPK'): ?>    
                                                <a href="<?= base_url('/pengadaan/hapus_data_pengadaan/' . $p['id']); ?>"
                                                class="btn btn-danger btn-sm m-1"
                                                onclick="return confirm('Yakin ingin menghapus?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                                <?php endif ?>
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
    function filterTahun() {
    var tahunFilter = document.getElementById("filterTahun").value;

    fetch("<?= base_url('pengadaan/filterByTahun'); ?>", {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: "tahun=" + encodeURIComponent(tahunFilter)
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("jumlahPengadaan").innerText = data.jumlah_pengadaan;
        document.getElementById("totalPerencanaan").innerText = 'Rp ' + data.total_perencanaan;
        document.getElementById("totalPelaksanaan").innerText = 'Rp ' + data.total_pelaksanaan;
        document.getElementById("totalPembayaran").innerText = 'Rp ' + data.total_pembayaran;
    });
}

function filterData() {
    var tahunFilter = document.getElementById("filterTahun").value;
    console.log("Tahun yang dipilih:", tahunFilter); // DEBUG
    var searchFilter = document.getElementById("search") ? document.getElementById("search").value.toLowerCase() : "";
    var table = document.getElementById("tablePengadaan");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) {
        var tahunCell = tr[i].getElementsByClassName("tahun-anggaran")[0];
        var allCells = tr[i].getElementsByTagName("td");
        var rowVisible = true;

        // Filter Tahun
        if (tahunFilter !== "" && tahunCell) {
            var tahunText = tahunCell.textContent || tahunCell.innerText;
            if (tahunText !== tahunFilter) {
                rowVisible = false;
            }
        }

        // Filter Keyword (optional)
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