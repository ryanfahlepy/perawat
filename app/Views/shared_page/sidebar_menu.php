<?php
$session = \Config\Services::session();
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="/assets/dist/img/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .9">
        <span class="no-border">NURSE-LEAD</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="/assets/dist/img/user/<?= $session->photo ?>" class="img-circle elevation-3" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $session->nama ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">Menu <?= $session->nama_level ?></li>
                <!-- <li class="nav-item has-treeview menu-open"> -->
                <?php foreach ($dtmenu as $dtm) : ?>
                    <?php foreach ($dtm as $menu) : ?>
                        <li class="nav-item <?php echo ($nama_menu == $menu->nama) ? 'menu-active' : '' ?>">
                            <a href="<?= $menu->url; ?>" class="nav-link <?php echo ($nama_menu == $menu->nama) ? 'active' : '' ?>">
                                <i class="nav-icon <?= $menu->icon; ?>"></i>
                                <p>
                                    <?= $menu->nama; ?>
                                </p>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <li class="nav-item">
                    <a href="/login/logout" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>