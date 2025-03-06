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
            <a class="nav-link active" data-toggle="tab" href="#pl">Penunjukkan Langsung</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tender">Tender</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#ep">E-Purchasing</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3">
        <?php 
        $tabs = [
            'pl' => $plDocuments,
            'tender' => $tenderDocuments,
            'ep' => $epDocuments
        ];
        ?>

        <?php foreach ($tabs as $tabId => $documents): ?>
        <div class="tab-pane fade <?= ($tabId == 'pl') ? 'show active' : '' ?>" id="<?= $tabId ?>">
            <hr>
            <button class="btn btn-primary edit-btn mb-3" data-tab="<?= $tabId ?>">Edit</button>
            <button class="btn btn-success save-btn d-none mb-3" data-tab="<?= $tabId ?>">Simpan</button>

            <ul id="sortable-<?= $tabId ?>" class="list-group sortable">
                <?php foreach ($documents as $index => $doc): ?>
                <li class="list-group-item d-flex align-items-center" data-id="<?= $doc['id_dokumen'] ?>">
                    <span class="nomor"><?= $index + 1; ?>.</span>
                    <input type="text" class="form-control dokumen-input flex-grow-1 ml-2" value="<?= $doc['dokumen']; ?>" disabled>
                    <span class="handle ml-2 cursor-pointer">&#x2630;</span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .handle {
        cursor: grab; /* Mengubah ikon menjadi tangan saat hover */
    }
    .handle:active {
        cursor: grabbing; /* Saat di-drag, ikon berubah */
    }
</style>

<!-- jQuery dan jQuery UI -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        $(".edit-btn").click(function() {
            var tab = $(this).data("tab");
            $("#sortable-" + tab).sortable({ disabled: false });
            $("#sortable-" + tab + " .dokumen-input").prop("disabled", false);
            $(this).addClass("d-none");
            $(".save-btn[data-tab='" + tab + "']").removeClass("d-none");
        });

        $(".save-btn").click(function() {
            var tab = $(this).data("tab");
            var order = [];

            $("#sortable-" + tab + " li").each(function(index) {
                var id = $(this).data("id");
                var dokumen = $(this).find(".dokumen-input").val();
                order.push({ id: id, dokumen: dokumen });
            });

            $.ajax({
                url: "<?= base_url('admin/templatedokumen/update_order') ?>",
                type: "POST",
                data: { order: order, table: tab },
                success: function(response) {
                    alert("Data berhasil disimpan!");
                    $(".save-btn[data-tab='" + tab + "']").addClass("d-none");
                    $(".edit-btn[data-tab='" + tab + "']").removeClass("d-none");
                    $("#sortable-" + tab).sortable({ disabled: true });
                    $("#sortable-" + tab + " .dokumen-input").prop("disabled", true);
                    
                    // Update nomor urut setelah disimpan
                    updateNomor(tab);
                },
                error: function() {
                    alert("Terjadi kesalahan, coba lagi!");
                }
            });
        });

        $(".sortable").sortable({
            disabled: true, 
            handle: ".handle",
            update: function(event, ui) {
                var tab = $(this).attr("id").replace("sortable-", "");
                updateNomor(tab);
            }
        });

        function updateNomor(tab) {
            $("#sortable-" + tab + " li").each(function(index) {
                $(this).find(".nomor").text((index + 1) + ".");
            });
        }
    });
</script>

<?= $this->endSection(); ?>
