<!-- CSS Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- JavaScript Bootstrap (termasuk Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<?php
$session = \Config\Services::session();
?>
<ul class="navbar-nav ml-auto">

    <!-- Messages Dropdown Menu -->
    <?php if ($level_akses === 'Admin'): ?>
        <li class="nav-item dropdown">
            <a class="nav-link" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span id="jumlahNotif"
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo $jumlahNotif; ?>
                </span>
            </a>
            <style>
                .dropdown-item {
                    word-wrap: break-word;
                    white-space: normal;
                }
            </style>

            <div class="dropdown-menu dropdown-menu-lg" style="right: 0; left: auto; width: 350px;">
                <!-- Loop untuk menampilkan notifikasi -->

                <?php if (empty($notifikasi)): ?>
                    <p class="dropdown-item text-center">Tidak ada notifikasi</p>
                <?php else: ?>
                    <?php foreach ($notifikasi as $notif): ?>
                        <a href="#" class="dropdown-item">
                            <!-- Notifikasi Start -->
                            <div class="media">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        <?php echo $notif['pesan']; ?>
                                    </h3>
                                    <p class="text-sm">
                                        <!-- Menampilkan waktu notifikasi atau keterangan lainnya -->
                                        <?php echo $notif['created_at']; ?>
                                    </p>
                                </div>
                                <!-- Tombol Close untuk menandai notifikasi sebagai dibaca -->
                                <button class="btn-close float-end" data-id="<?php echo $notif['id']; ?>"
                                    onclick="tandaiDibaca(this)"></button>
                            </div>
                            <!-- Notifikasi End -->
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>



            </div>

        </li>



    <?php endif; ?>

    <script>
        function tandaiDibaca(button) {
            var notifId = button.getAttribute('data-id'); // Ambil ID notifikasi

            // Menggunakan AJAX untuk menandai notifikasi sebagai dibaca
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'http://localhost:8080/notifikasi/tandaiDibaca', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        // Jika berhasil, ubah tampilan notifikasi
                        button.closest('.dropdown-item').classList.add('read');
                        button.closest('.dropdown-item').style.opacity = '0.5';
                        updateNotificationCount();
                    }
                }
            };
            xhr.send('notif_id=' + notifId);

        }

        function updateNotificationCount() {
            var count = document.querySelectorAll('.dropdown-item').length;
            document.getElementById('jumlahNotif').textContent = count;
        }

    </script>



    <!-- Profil User -->
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <img src="/assets/dist/img/user/<?= $session->photo ?>" alt="User Avatar" width="30"
                class="mr-3 img-circle">
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item">
                <!-- Message Start -->
                <div class="media">
                    <img src="/assets/dist/img/user/<?= $session->photo ?>" alt="User Avatar"
                        class="img-size-50 mr-3 img-circle">
                    <div class="media-body">
                        <h3 class="dropdown-item-title">
                            <?= $session->nama ?>
                        </h3>
                        <p class="text-sm"><?= $session->nama_level ?></p>
                    </div>
                </div>
                <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
            <a href="/login/logout" class="dropdown-item dropdown-footer">Logout</a>
        </div>
    </li>
</ul>