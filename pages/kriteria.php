<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: ../login.php"); exit; }
include '../config/koneksi.php';

if(isset($_GET['hapus'])){
    $id=(int)$_GET['hapus'];
    mysqli_query($conn,"DELETE FROM kriteria WHERE id_kriteria='$id'");
    header("Location: kriteria.php?msg=hapus"); exit;
}

$msg=$_GET['msg']??'';
$cari='';
if(isset($_GET['cari'])){
    $cari=mysqli_real_escape_string($conn,$_GET['cari']);
    $query=mysqli_query($conn,"SELECT * FROM kriteria WHERE kode_kriteria LIKE '%$cari%' OR nama_kriteria LIKE '%$cari%' OR posisi LIKE '%$cari%' ORDER BY posisi,kode_kriteria");
}else{
    $query=mysqli_query($conn,"SELECT * FROM kriteria ORDER BY posisi,kode_kriteria");
}

// Kelompokkan per posisi
$grouped=[];
while($row=mysqli_fetch_assoc($query)){
    $grouped[$row['posisi']][]=$row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Data Kriteria</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/kriteria.css">
</head>
<body>
<?php include '../partials/sidebar.php'; ?>
<div class="main-content">
<?php include '../partials/header.php'; ?>
<div class="content">

<?php if($msg==='hapus'): ?><div class="alert alert-success">✅ Kriteria berhasil dihapus</div><?php endif; ?>
<?php if($msg==='tambah'): ?><div class="alert alert-success">✅ Kriteria berhasil ditambahkan</div><?php endif; ?>
<?php if($msg==='edit'): ?><div class="alert alert-success">✅ Kriteria berhasil diperbarui</div><?php endif; ?>

<div class="page-header">
  <h1>Data Kriteria</h1>
  <a href="kriteria_tambah.php" class="btn-tambah">+ Tambah Kriteria</a>
</div>

<form method="GET" class="search-box">
  <input type="text" name="cari" placeholder="Cari kode, nama, atau posisi..." value="<?= htmlspecialchars($cari) ?>">
  <button type="submit">🔍 Cari</button>
  <?php if($cari): ?><a href="kriteria.php" class="btn-batal">Reset</a><?php endif; ?>
</form>

<?php foreach($grouped as $posisi=>$rows): ?>
<div class="table-container">
  <h2>⚽ <?= $posisi ?> <small style="font-size:11px;color:var(--text-muted)">(<?= count($rows) ?> kriteria)</small></h2>
  <table>
    <thead><tr>
      <th>Kode</th><th>Nama Kriteria</th><th>Bobot</th><th>Atribut</th><th>Status</th><th>Aksi</th>
    </tr></thead>
    <tbody>
    <?php foreach($rows as $row): ?>
    <tr>
      <td style="font-weight:700;color:var(--accent)"><?= $row['kode_kriteria'] ?></td>
      <td><?= htmlspecialchars($row['nama_kriteria']) ?></td>
      <td>
        <div class="bobot-bar">
          <div class="bobot-fill" style="width:<?= ($row['bobot']/5)*80 ?>px"></div>
          <span class="bobot-num"><?= $row['bobot'] ?></span>
        </div>
      </td>
      <td><span class="badge badge-<?= strtolower($row['atribut']) ?>"><?= $row['atribut'] ?></span></td>
      <td><span class="badge <?= $row['aktif']==='Y'?'badge-active':'badge-nonaktif' ?>"><?= $row['aktif']==='Y'?'Aktif':'Nonaktif' ?></span></td>
      <td>
        <div class="action-group">
          <a href="kriteria_edit.php?id=<?= $row['id_kriteria'] ?>" class="btn-edit">✏️ Edit</a>
          <a href="?hapus=<?= $row['id_kriteria'] ?>" class="btn-hapus" onclick="return confirm('Hapus kriteria <?= htmlspecialchars($row['nama_kriteria']) ?>?')">🗑 Hapus</a>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endforeach; ?>
<?php if(empty($grouped)): ?><div class="card"><div class="empty-state"><p>Belum ada kriteria</p></div></div><?php endif; ?>

</div>
</div>
<script>document.getElementById('sidebarToggle')?.addEventListener('click',()=>document.querySelector('.sidebar').classList.toggle('open'));</script>
</body>
</html>
