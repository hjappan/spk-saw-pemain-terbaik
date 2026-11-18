<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: ../login.php"); exit; }
include '../config/koneksi.php';

$msg=$_GET['msg']??'';

$pemain = mysqli_query($conn, "
    SELECT p.*, 
           COUNT(pn.id_penilaian) AS jml_nilai, 
           COUNT(k.id_kriteria) AS jml_kriteria 
    FROM pemain p 
    LEFT JOIN penilaian pn ON p.id_pemain = pn.id_pemain 
    LEFT JOIN kriteria k ON k.posisi = p.posisi AND k.aktif = 'Y' 
    GROUP BY p.id_pemain 
    ORDER BY p.posisi, p.nama_pemain
");

$posisiClass = [
    'Penyerang' => 'pos-penyerang',
    'Gelandang' => 'pos-gelandang', 
    'Bek' => 'pos-bek',
    'Penjaga Gawang' => 'pos-kiper'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Penilaian Pemain - SPK SAW</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/penilaian.css">
</head>
<body>
<?php include '../partials/sidebar.php'; ?>
<div class="main-content">
<?php include '../partials/header.php'; ?>
<div class="content">

<?php if($msg==='simpan'): ?>
    <div class="alert alert-success">✅ Penilaian berhasil disimpan</div>
<?php endif; ?>

<div class="page-header">
    <h1>📝 Penilaian Pemain</h1>
    <a href="saw.php" class="btn-hitung">⚙️ Hitung SAW</a>
</div>

<div class="info-card">
    <div class="info-icon">ℹ️</div>
    <div class="info-text">
        <strong>Petunjuk:</strong> Klik tombol "Input Nilai" untuk memberikan penilaian kepada setiap pemain 
        berdasarkan kriteria yang sudah ditentukan. Pastikan semua kriteria terisi agar perhitungan SAW akurat.
    </div>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pemain</th>
                <th>Posisi</th>
                <th>Tim</th>
                <th>Status Nilai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $no = 1;
        while($row = mysqli_fetch_assoc($pemain)):
            $cls = $posisiClass[$row['posisi']] ?? 'pos-gelandang';
            $sudahLengkap = ($row['jml_nilai'] > 0 && $row['jml_nilai'] == $row['jml_kriteria']);
        ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="player-name"><?= htmlspecialchars($row['nama_pemain']) ?></td>
                <td><span class="posisi-badge <?= $cls ?>"><?= $row['posisi'] ?></span></td>
                <td><?= htmlspecialchars($row['tim']) ?></td>
                <td>
                    <?php if($sudahLengkap): ?>
                        <span class="status-badge success">✅ Lengkap (<?= $row['jml_nilai'] ?>/<?= $row['jml_kriteria'] ?>)</span>
                    <?php elseif($row['jml_nilai'] > 0): ?>
                        <span class="status-badge warning">⚠️ Sebagian (<?= $row['jml_nilai'] ?>/<?= $row['jml_kriteria'] ?>)</span>
                    <?php else: ?>
                        <span class="status-badge danger">❌ Belum dinilai</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="penilaian_kriteria.php?id=<?= $row['id_pemain'] ?>" class="btn-input">
                        📝 Input Nilai
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php if($no === 1): ?>
            <tr>
                <td colspan="6" class="empty-state">
                    <div class="empty-data">
                        <span class="empty-icon">📋</span>
                        <p>Belum ada data pemain</p>
                        <a href="pemain_tambah.php" class="btn-tambah-small">+ Tambah Pemain</a>
                    </div>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</div>
</div>

<script>
document.getElementById('sidebarToggle')?.addEventListener('click',()=>{
    document.querySelector('.sidebar').classList.toggle('open');
});
</script>
</body>
</html>