<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: ../login.php"); exit; }
include '../config/koneksi.php';

$msg = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nama_pemain = mysqli_real_escape_string($conn, $_POST['nama_pemain']);
    $posisi = mysqli_real_escape_string($conn, $_POST['posisi']);
    $tim = mysqli_real_escape_string($conn, $_POST['tim']);
    $nomor_punggung = (int)$_POST['nomor_punggung'];
    $umur = (int)$_POST['umur'];
    $negara = mysqli_real_escape_string($conn, $_POST['negara']);
    
    $query = "INSERT INTO pemain (nama_pemain, posisi, tim, nomor_punggung, umur, negara) 
              VALUES ('$nama_pemain', '$posisi', '$tim', '$nomor_punggung', '$umur', '$negara')";
    
    if(mysqli_query($conn, $query)){
        header("Location: pemain.php?msg=tambah");
        exit;
    } else {
        $msg = '<div class="alert alert-danger">❌ Gagal menambahkan pemain: ' . mysqli_error($conn) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Tambah Pemain - SPK SAW</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/pemain.css">
</head>
<body>
<?php include '../partials/sidebar.php'; ?>
<div class="main-content">
<?php include '../partials/header.php'; ?>
<div class="content">

<div class="page-header">
    <h1>➕ Tambah Pemain Baru</h1>
    <a href="pemain.php" class="btn-batal">← Kembali</a>
</div>

<?= $msg ?>

<div class="form-container">
    <form method="POST" class="form-pemain">
        <div class="form-row">
            <div class="form-group">
                <label for="nama_pemain">Nama Pemain <span class="required">*</span></label>
                <input type="text" id="nama_pemain" name="nama_pemain" required placeholder="Masukkan nama pemain">
            </div>
            
            <div class="form-group">
                <label for="posisi">Posisi <span class="required">*</span></label>
                <select id="posisi" name="posisi" required>
                    <option value="">Pilih Posisi</option>
                    <option value="Penyerang">⚽ Penyerang</option>
                    <option value="Gelandang">🎯 Gelandang</option>
                    <option value="Bek">🛡️ Bek</option>
                    <option value="Penjaga Gawang">🧤 Penjaga Gawang</option>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="tim">Tim / Klub</label>
                <input type="text" id="tim" name="tim" placeholder="Contoh: Persib, Barcelona">
            </div>
            
            <div class="form-group">
                <label for="nomor_punggung">Nomor Punggung</label>
                <input type="number" id="nomor_punggung" name="nomor_punggung" min="1" max="99" placeholder="1-99">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="umur">Umur (Tahun)</label>
                <input type="number" id="umur" name="umur" min="15" max="50" placeholder="25">
            </div>
            
            <div class="form-group">
                <label for="negara">Negara Asal</label>
                <input type="text" id="negara" name="negara" placeholder="Indonesia, Argentina">
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-simpan">💾 Simpan Pemain</button>
            <a href="pemain.php" class="btn-batal">❌ Batal</a>
        </div>
    </form>
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