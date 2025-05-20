<!-- CSS Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- JavaScript Bootstrap (termasuk Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Font Awesome (versi lengkap) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<?php $session = \Config\Services::session(); ?>

<ul class="navbar-nav ml-auto">
    <!-- Notifikasi -->
    <li class="nav-item dropdown">
        <a class="nav-link" href="#" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell fs-5 me-1" style="color: #161616;"></i>
            <span id="jumlahNotif"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= $jumlahNotif; ?>
            </span>
        </a>

        <style>
            .dropdown-item {
                word-wrap: break-word;
                white-space: normal;
            }

            .unread {
                background-color: #f8f9fa;
            }

            .fas.fa-bell {
                font-weight: 900;
            }

            #jumlahNotif {
                background-color: #dc3545 !important;
                color: white !important;
                opacity: 1 !important;
                filter: brightness(1.1);
            }

            .nav-link,
            .nav-item {
                opacity: 1 !important;
            }
        </style>

        <div class="dropdown-menu dropdown-menu-lg" style="right: 0; left: auto; width: 350px;">
            <?php if (empty($notifikasi)): ?>
                <p class="dropdown-item text-center">Tidak ada notifikasi</p>
            <?php else: ?>
                <?php foreach ($notifikasi as $notif): ?>
                    <a href="#" class="dropdown-item unread">
                        <div class="media">
                            <div class="media-body">
                                <h3 class="dropdown-item-title"><?= $notif['pesan']; ?></h3>
                                <p class="text-sm"><?= $notif['created_at']; ?></p>
                            </div>
                            <button class="btn-close float-end" data-id="<?= $notif['id']; ?>"
                                onclick="tandaiDibaca(this)"></button>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </li>

    <!-- Profil User -->
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <img src="/assets/dist/img/user/<?= $session->photo ?>" alt="User Avatar" width="30"
                class="mr-3 img-circle">
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item">
                <div class="media">
                    <img src="/assets/dist/img/user/<?= $session->photo ?>" alt="User Avatar"
                        class="img-size-50 mr-3 img-circle">
                    <div class="media-body">
                        <h3 class="dropdown-item-title"><?= $session->nama ?></h3>
                        <p class="text-sm"><?= $session->nama_level ?></p>
                    </div>
                </div>
            </a>
            <div class="dropdown-divider"></div>
            <a href="/login/logout" class="dropdown-item dropdown-footer">Logout</a>
        </div>
    </li>
</ul>

<script>
    function tandaiDibaca(button) {
        var notifId = button.getAttribute('data-id');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://localhost:8080/notifikasi/tandaiDibaca', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    let item = button.closest('.dropdown-item');
                    item.classList.remove('unread');
                    item.style.opacity = '1';
                    updateNotificationCount();
                }
            }
        };
        xhr.send('notif_id=' + notifId);
    }

    function updateNotificationCount() {
        var count = document.querySelectorAll('.dropdown-item.unread').length;
        document.getElementById('jumlahNotif').textContent = count;
    }
</script>


<style>
    #jumlahNotif {
        background-color: #dc3545 !important;
        /* Warna merah Bootstrap */
        color: white !important;
        opacity: 1 !important;
        filter: brightness(1.1);
        /* opsional: bikin lebih terang */
    }
</style>