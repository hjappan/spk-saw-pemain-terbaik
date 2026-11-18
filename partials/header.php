<?php
$pageTitles = [
    'dashboard.php'         => 'Dashboard',
    'pemain.php'            => 'Data Pemain',
    'pemain_tambah.php'     => 'Tambah Pemain',
    'pemain_edit.php'       => 'Edit Pemain',
    'kriteria.php'          => 'Data Kriteria',
    'kriteria_tambah.php'   => 'Tambah Kriteria',
    'kriteria_edit.php'     => 'Edit Kriteria',
    'penilaian.php'         => 'Penilaian Pemain',
    'penilaian_kriteria.php'=> 'Input Nilai',
    'saw.php'               => 'Perhitungan SAW',
    'ranking.php'           => 'Ranking Pemain',
    'laporan.php'           => 'Laporan',
];
$currentPage = basename($_SERVER['PHP_SELF']);
$pageTitle = $pageTitles[$currentPage] ?? 'SPK SAW';
?>
<header class="topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" id="sidebarToggle">&#9776;</button>
        <h2 class="topbar-title"><?= $pageTitle ?></h2>
    </div>
    <div class="topbar-right">
        <div class="user-info">
            <span class="user-avatar">👤</span>
            <span class="user-name"><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></span>
        </div>
    </div>
</header>
