<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: ../login.php"); exit; }
include '../config/koneksi.php';

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
        mysqli_query($conn,"INSERT INTO kriteria (kode_kriteria,nama_kriteria,posisi,bobot,atribut,aktif) VALUES ('$kode','$nama','$posisi','$bobot','$atribut','$aktif')");
        header("Location: kriteria.php?msg=tambah"); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Tambah Kriteria</title>
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
<h2 style="margin-bottom:22px;font-size:15px">Tambah Kriteria Baru</h2>
<form method="POST">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
    <div class="form-group">
      <label>Kode Kriteria *</label>
      <input type="text" name="kode_kriteria" required placeholder="C1, C2, ..." value="<?= htmlspecialchars($_POST['kode_kriteria']??'') ?>">
    </div>
    <div class="form-group">
      <label>Nama Kriteria *</label>
      <input type="text" name="nama_kriteria" required placeholder="Contoh: Gol" value="<?= htmlspecialchars($_POST['nama_kriteria']??'') ?>">
    </div>
    <div class="form-group">
      <label>Posisi Pemain *</label>
      <select name="posisi" required>
        <option value="">– Pilih Posisi –</option>
        <?php foreach(['Penyerang','Gelandang','Bek','Penjaga Gawang'] as $p): ?>
        <option value="<?= $p ?>" <?= ($_POST['posisi']??'')===$p?'selected':'' ?>><?= $p ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Bobot (1–5, skala Likert) *</label>
      <input type="number" name="bobot" required min="1" max="5" placeholder="1–5" value="<?= $_POST['bobot']??'' ?>">
      <div class="form-hint">1=Sangat Kurang · 3=Cukup · 5=Sangat Baik</div>
    </div>
    <div class="form-group">
      <label>Atribut *</label>
      <select name="atribut" required>
        <option value="Benefit" <?= ($_POST['atribut']??'')==='Benefit'?'selected':'' ?>>Benefit (Semakin besar semakin baik)</option>
        <option value="Cost" <?= ($_POST['atribut']??'')==='Cost'?'selected':'' ?>>Cost (Semakin kecil semakin baik)</option>
      </select>
    </div>
    <div class="form-group">
      <label>Status</label>
      <select name="aktif">
        <option value="Y" <?= ($_POST['aktif']??'Y')==='Y'?'selected':'' ?>>Aktif</option>
        <option value="N" <?= ($_POST['aktif']??'')==='N'?'selected':'' ?>>Nonaktif</option>
      </select>
    </div>
  </div>
  <div class="form-action">
    <button type="submit" name="simpan" class="btn-simpan">💾 Simpan</button>
    <a href="kriteria.php" class="btn-batal">Batal</a>
  </div>
</form>
</div>
</div>
</div>
<script>document.getElementById('sidebarToggle')?.addEventListener('click',()=>document.querySelector('.sidebar').classList.toggle('open'));</script>
</body>
</html>
