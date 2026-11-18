<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: ../login.php"); exit; }
include '../config/koneksi.php';

if(isset($_GET['hapus'])){
    $id=(int)$_GET['hapus'];
    mysqli_query($conn,"DELETE FROM pemain WHERE id_pemain='$id'");
    header("Location: pemain.php?msg=hapus"); 
    exit;
}

$msg=$_GET['msg']??'';
$cari='';
if(isset($_GET['cari'])){
    $cari=mysqli_real_escape_string($conn,$_GET['cari']);
    $query=mysqli_query($conn,"SELECT * FROM pemain WHERE nama_pemain LIKE '%$cari%' OR tim LIKE '%$cari%' OR posisi LIKE '%$cari%' ORDER BY posisi,nama_pemain");
}else{
    $query=mysqli_query($conn,"SELECT * FROM pemain ORDER BY posisi,nama_pemain");
}

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
<title>Data Pemain - SPK SAW</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/pemain.css">
</head>
<body>
<?php include '../partials/sidebar.php'; ?>
<div class="main-content">
<?php include '../partials/header.php'; ?>
<div class="content">

<?php if($msg==='hapus'): ?>
    <div class="alert alert-success">✅ Pemain berhasil dihapus</div>
<?php endif; ?>
<?php if($msg==='tambah'): ?>
    <div class="alert alert-success">✅ Pemain berhasil ditambahkan</div>
<?php endif; ?>
<?php if($msg==='edit'): ?>
    <div class="alert alert-success">✅ Pemain berhasil diperbarui</div>
<?php endif; ?>

<div class="page-header">
    <h1>📋 Data Pemain</h1>
    <a href="pemain_tambah.php" class="btn-tambah">+ Tambah Pemain</a>
</div>

<form method="GET" class="search-box">
    <input type="text" name="cari" placeholder="Cari nama, tim, atau posisi..." value="<?= htmlspecialchars($cari) ?>">
    <button type="submit">🔍 Cari</button>
    <?php if($cari): ?>
        <a href="pemain.php" class="btn-batal">Reset</a>
    <?php endif; ?>
</form>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pemain</th>
                <th>Posisi</th>
                <th>Tim</th>
                <th>No Punggung</th>
                <th>Umur</th>
                <th>Negara</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        while($row = mysqli_fetch_assoc($query)):
            $cls = $posisiClass[$row['posisi']] ?? 'pos-gelandang';
        ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="player-name"><?= htmlspecialchars($row['nama_pemain']) ?></td>
                <td><span class="posisi-badge <?= $cls ?>"><?= $row['posisi'] ?></span></td>
                <td><?= htmlspecialchars($row['tim']) ?></td>
                <td class="text-center"><?= $row['nomor_punggung'] ?: '–' ?></td>
                <td class="text-center"><?= $row['umur'] ? $row['umur'].' th' : '–' ?></td>
                <td><?= htmlspecialchars($row['negara'] ?: '–') ?></td>
                <td>
                    <div class="action-group">
                        <a href="pemain_edit.php?id=<?= $row['id_pemain'] ?>" class="btn-edit">✏️ Edit</a>
                        <a href="?hapus=<?= $row['id_pemain'] ?>" class="btn-hapus" onclick="return confirm('Yakin hapus pemain <?= htmlspecialchars($row['nama_pemain']) ?>?')">🗑 Hapus</a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php if($no === 1): ?>
            <tr>
                <td colspan="8" class="empty-state">
                    <div class="empty-data">
                        <span class="empty-icon">📭</span>
                        <p>Tidak ada data pemain<?= $cari ? " untuk \"$cari\"" : '' ?></p>
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