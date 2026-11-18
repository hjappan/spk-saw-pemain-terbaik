<?php
// Tentukan path relatif berdasarkan lokasi file yang include sidebar
$base = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? '../' : '';
$pagesBase = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? '' : 'pages/';
?>
<div class="sidebar">
    <div class="sidebar-logo">
        <span class="logo-icon">⚽</span>
        <span class="logo-text">SPK SAW</span>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="<?= $base ?>pages/dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>">
                    <span class="nav-icon">🏠</span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?= $base ?>pages/pemain.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='pemain.php'||basename($_SERVER['PHP_SELF'])=='pemain_tambah.php'||basename($_SERVER['PHP_SELF'])=='pemain_edit.php'?'active':'' ?>">
                    <span class="nav-icon">👤</span>
                    <span>Data Pemain</span>
                </a>
            </li>
            <li>
                <a href="<?= $base ?>pages/kriteria.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='kriteria.php'||basename($_SERVER['PHP_SELF'])=='kriteria_tambah.php'||basename($_SERVER['PHP_SELF'])=='kriteria_edit.php'?'active':'' ?>">
                    <span class="nav-icon">📋</span>
                    <span>Data Kriteria</span>
                </a>
            </li>
            <li>
                <a href="<?= $base ?>pages/penilaian.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='penilaian.php'||basename($_SERVER['PHP_SELF'])=='penilaian_kriteria.php'?'active':'' ?>">
                    <span class="nav-icon">📝</span>
                    <span>Penilaian</span>
                </a>
            </li>
            <li>
                <a href="<?= $base ?>pages/saw.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='saw.php'?'active':'' ?>">
                    <span class="nav-icon">⚙️</span>
                    <span>Hitung SAW</span>
                </a>
            </li>
            <li>
                <a href="<?= $base ?>pages/ranking.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='ranking.php'?'active':'' ?>">
                    <span class="nav-icon">🏆</span>
                    <span>Ranking</span>
                </a>
            </li>
            <li>
                <a href="<?= $base ?>pages/laporan.php" class="nav-link <?= basename($_SERVER['PHP_SELF'])=='laporan.php'?'active':'' ?>">
                    <span class="nav-icon">📊</span>
                    <span>Laporan</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= $base ?>logout.php" class="btn-logout">
            <span>🚪</span> Logout
        </a>
    </div>
</div>
