<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: ../login.php"); exit; }
include '../config/koneksi.php';

$id=(int)($_GET['id']??0);
$kriteria=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM kriteria WHERE id_kriteria='$id'"));
if(!$kriteria){ header("Location: kriteria.php"); exit; }

$error='';
if(isset($_POST['simpan'])){
    $kode   = mysqli_real_escape_string($conn,$_POST['kode_kriteria']);
    $nama   = mysqli_real_escape_string($conn,$_POST['nama_kriteria']);
    $posisi = mysqli_real_escape_string($conn,$_POST['posisi']);
    $bobot  = (int)$_POST['bobot'];
    $atribut= mysqli_real_escape_string($conn,$_POST['atribut']);
    $aktif  = mysqli_real_escape_string($conn,$_POST['aktif']);

    if($bobot<1||$bobot>5){ $error="Bobot harus antara 1–5"; }
    else {
        mysqli_query($conn,"UPDATE kriteria SET kode_kriteria='$kode',nama_kriteria='$nama',posisi='$posisi',bobot='$bobot',atribut='$atribut',aktif='$aktif' WHERE id_kriteria='$id'");
        header("Location: kriteria.php?msg=edit"); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Edit Kriteria</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/kriteria.css">
</head>
<body>
<?php include '../partials/sidebar.php'; ?>
<div class="main-content">
<?php include '../partials/header.php'; ?>
<div class="content">
<?php if($error): ?><div class="alert alert-error">⚠️ <?= $error ?></div><?php endif; ?>

<div class="form-card">
<h2 style="margin-bottom:22px;font-size:15px">Edit Kriteria: <?= htmlspecialchars($kriteria['kode_kriteria']) ?> – <?= htmlspecialchars($kriteria['nama_kriteria']) ?></h2>
<form method="POST">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
    <div class="form-group">
      <label>Kode Kriteria *</label>
      <input type="text" name="kode_kriteria" required value="<?= htmlspecialchars($kriteria['kode_kriteria']) ?>">
    </div>
    <div class="form-group">
      <label>Nama Kriteria *</label>
      <input type="text" name="nama_kriteria" required value="<?= htmlspecialchars($kriteria['nama_kriteria']) ?>">
    </div>
    <div class="form-group">
      <label>Posisi Pemain *</label>
      <select name="posisi" required>
        <?php foreach(['Penyerang','Gelandang','Bek','Penjaga Gawang'] as $p): ?>
        <option value="<?= $p ?>" <?= $kriteria['posisi']===$p?'selected':'' ?>><?= $p ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Bobot (1–5) *</label>
      <input type="number" name="bobot" required min="1" max="5" value="<?= $kriteria['bobot'] ?>">
    </div>
    <div class="form-group">
      <label>Atribut *</label>
      <select name="atribut" required>
        <option value="Benefit" <?= $kriteria['atribut']==='Benefit'?'selected':'' ?>>Benefit</option>
        <option value="Cost" <?= $kriteria['atribut']==='Cost'?'selected':'' ?>>Cost</option>
      </select>
    </div>
    <div class="form-group">
      <label>Status</label>
      <select name="aktif">
        <option value="Y" <?= $kriteria['aktif']==='Y'?'selected':'' ?>>Aktif</option>
        <option value="N" <?= $kriteria['aktif']==='N'?'selected':'' ?>>Nonaktif</option>
      </select>
    </div>
  </div>
  <div class="form-action">
    <button type="submit" name="simpan" class="btn-simpan">💾 Update</button>
    <a href="kriteria.php" class="btn-batal">Batal</a>
  </div>
</form>
</div>
</div>
</div>
<script>document.getElementById('sidebarToggle')?.addEventListener('click',()=>document.querySelector('.sidebar').classList.toggle('open'));</script>
</body>
</html>
