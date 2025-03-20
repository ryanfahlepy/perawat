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
    <ul class="nav nav-tabs" id="pengadaanTabs">
        <li class="nav-item">
            <a style="font-weight: bold;" class="nav-link active" data-toggle="tab" href="#pl">Pengadaan Langsung</a>
        </li>
        <li class="nav-item">
            <a style="font-weight: bold;" class="nav-link" data-toggle="tab" href="#juksung">Penunjukkan Langsung</a>
        </li>
        <li class="nav-item">
            <a style="font-weight: bold;" class="nav-link" data-toggle="tab" href="#tender">Tender</a>
        </li>
        <li class="nav-item">
            <a style="font-weight: bold;" class="nav-link" data-toggle="tab" href="#ep">E-Purchasing</a>
        </li>
        <li class="nav-item">
            <a style="font-weight: bold;" class="nav-link" data-toggle="tab" href="#swakelola">Swakelola</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3">
        <?php
        $tabs = [
            'pl' => $plDocuments,
            'juksung' => $juksungDocuments,
            'tender' => $tenderDocuments,
            'ep' => $epDocuments,
            'swakelola' => $swakelolaDocuments
        ];
        ?>

        <?php foreach ($tabs as $tabId => $documents): ?>
            <div class="tab-pane fade <?= ($tabId == 'pl') ? 'show active' : '' ?>" id="<?= $tabId ?>">
                <hr>
                <button class="btn btn-primary tambah-btn mb-3" data-tab="<?= $tabId ?>">Tambah</button>
                <button class="btn btn-warning edit-btn mb-3" style="color: white;" data-tab="<?= $tabId ?>">Edit</button>
                <button class="btn btn-success save-btn d-none mb-3" data-tab="<?= $tabId ?>">Simpan</button>

                <ul id="sortable-<?= $tabId ?>" class="list-group sortable">
                    <?php foreach ($documents as $index => $doc): ?>
                        <li class="list-group-item d-flex align-items-center" data-id="<?= $doc['id_dokumen'] ?>">
                            <span class="nomor"><?= $index + 1; ?>.</span>
                            <input type="text" class="form-control dokumen-input flex-grow-1 ml-2"
                                value="<?= $doc['dokumen']; ?>" disabled>
                            <span class="handle ml-2 cursor-pointer">&#x2630;</span>
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm ml-2 hapus-btn" style="color: white;"
                                data-tab="<?= $tabId ?>"><i class="fas fa-trash"></i></a>

                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .handle {
        cursor: grab;
        /* Mengubah ikon menjadi tangan saat hover */
    }

    .handle:active {
        cursor: grabbing;
        /* Saat di-drag, ikon berubah */
    }
</style>

<!-- jQuery dan jQuery UI -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    $(document).ready(function () {
        $(".edit-btn").click(function () {
            var tab = $(this).data("tab");
            $("#sortable-" + tab).sortable({ disabled: false });
            $("#sortable-" + tab + " .dokumen-input").prop("disabled", false);
            $(this).addClass("d-none");
            $(".save-btn[data-tab='" + tab + "']").removeClass("d-none");
        });

        $(".save-btn").click(function () {
            var tab = $(this).data("tab");
            var order = [];

            $("#sortable-" + tab + " li").each(function (index) {
                var id = $(this).data("id");
                var dokumen = $(this).find(".dokumen-input").val();
                order.push({ id: id, dokumen: dokumen });
            });

            $.ajax({
                url: "<?= base_url('admin/templatedokumen/update_order') ?>",
                type: "POST",
                data: { order: order, table: tab },
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        window.location.href = "<?= base_url('admin/templatedokumen') ?>?success=Data berhasil disimpan!";
                    } else {
                        window.location.href = "<?= base_url('admin/templatedokumen') ?>?error=" + encodeURIComponent("Gagal menyimpan data: " + response.message);
                    }
                },
                error: function () {
                    window.location.href = "<?= base_url('admin/templatedokumen') ?>?error=Terjadi kesalahan, coba lagi!";
                }

            });
        });

        $(".tambah-btn").click(function () {
            var tab = $(this).data("tab");
            var newItem = `
                <li class="list-group-item d-flex align-items-center new-item">
                    <span class="nomor">-</span>
                    <input type="text" class="form-control dokumen-input flex-grow-1 ml-2" placeholder="Masukkan dokumen baru">
                    <button class="btn btn-success simpan-btn ml-2" data-tab="` + tab + `">Simpan</button>
                </li>
            `;
            $("#sortable-" + tab).append(newItem);
            $(this).addClass("d-none");
        });

        $(document).on("click", ".simpan-btn", function () {
            var tab = $(this).data("tab");
            var dokumenBaru = $(this).siblings(".dokumen-input").val(); // Ambil nilai input



            // Menyesuaikan nama tabel sesuai dengan tab yang dipilih
            if (tab === "pl") {
                tab = "tabel_pl";
            } else if (tab === "juksung") {
                tab = "tabel_juksung";
            } else if (tab === "tender") {
                tab = "tabel_tender";
            } else if (tab === "ep") {
                tab = "tabel_ep";
            } else if (tab === "swakelola") {
                tab = "tabel_swakelola";
            }


            if (dokumenBaru.trim() === "") {
                alert("Dokumen tidak boleh kosong!");
                return;
            }

            console.log("Mengirim Data:", { dokumen: dokumenBaru, table: tab });

            $.ajax({
                url: "<?= base_url('admin/templatedokumen/add_document') ?>",
                type: "POST",
                data: {
                    dokumen: dokumenBaru, table: tab,

                },  // Ubah dari `dokumen` menjadi `dokumen: dokumenBaru`
                success: function (response) {
                    Swal.fire('Berhasil', 'Dokumen berhasil ditambahkan', 'success').then(() => {
                        window.location.reload(); // Paksa refresh halaman setelah hapus
                    });
                },
                error: function (xhr, status, error) {
                    console.log("AJAX Error:", xhr.responseText);
                }

            });
        });

    });

    $(document).on("click", ".hapus-btn", function () {
        var tab = $(this).data("tab");
        var listItem = $(this).closest("li");
        var documentId = listItem.data("id");
        var table;
        if (tab === "pl") {
            table = "tabel_pl";
        } else if (tab === "juksung") {
            table = "tabel_juksung";
        } else if (tab === "tender") {
            table = "tabel_tender";
        } else if (tab === "ep") {
            table = "tabel_ep";
        } else if (tab === "swakelola") {
            table = "tabel_swakelola";
        }

        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Dokumen ini akan dihapus secara permanen",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {

            if (result.value) {
                

                $.ajax({
                    url: "<?= base_url('admin/templatedokumen/delete_document') ?>",
                    type: "POST",
                    data: { id: documentId, table: table },
                    beforeSend: function () {
                    },
                    success: function (response) {
                        Swal.fire('Berhasil', 'Dokumen berhasil dihapus', 'success').then(() => {
                            window.location.reload(); // Paksa refresh halaman setelah hapus
                        });
                    },
                    error: function (xhr, status, error) {
                    }
                });
            } else {
                
            }

        });

    });

</script>

<?= $this->endSection(); ?>