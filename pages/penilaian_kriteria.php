<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: ../login.php"); exit; }
include '../config/koneksi.php';

$id_pemain = (int)$_GET['id'];
$pemain = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pemain WHERE id_pemain='$id_pemain'"));

if(!$pemain){
    header("Location: penilaian.php");
    exit;
}

// Ambil kriteria berdasarkan posisi pemain
$kriteria = mysqli_query($conn, "
    SELECT * FROM kriteria 
    WHERE posisi = '{$pemain['posisi']}' AND aktif = 'Y' 
    ORDER BY id_kriteria
");

// Ambil nilai yang sudah ada
$nilaiExist = [];
$queryNilai = mysqli_query($conn, "SELECT id_kriteria, nilai FROM penilaian WHERE id_pemain='$id_pemain'");
while($row = mysqli_fetch_assoc($queryNilai)){
    $nilaiExist[$row['id_kriteria']] = $row['nilai'];
}

$msg = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Hapus nilai lama
    mysqli_query($conn, "DELETE FROM penilaian WHERE id_pemain='$id_pemain'");
    
    // Simpan nilai baru
    $success = true;
    foreach($_POST['nilai'] as $id_kriteria => $nilai){
        $nilai = floatval($nilai);
        $query = "INSERT INTO penilaian (id_pemain, id_kriteria, nilai) 
                  VALUES ('$id_pemain', '$id_kriteria', '$nilai')";
        if(!mysqli_query($conn, $query)){
            $success = false;
        }
    }
    
    if($success){
        header("Location: penilaian.php?msg=simpan");
        exit;
    } else {
        $msg = '<div class="alert alert-danger">❌ Gagal menyimpan penilaian</div>';
    }
}

// Hitung progress
$totalKriteria = mysqli_num_rows($kriteria);
$terisi = count($nilaiExist);
$progress = $totalKriteria > 0 ? ($terisi / $totalKriteria) * 100 : 0;

// Reset pointer kriteria
mysqli_data_seek($kriteria, 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Input Penilaian - <?= htmlspecialchars($pemain['nama_pemain']) ?></title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/penilaian.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php include '../partials/sidebar.php'; ?>
<div class="main-content">
<?php include '../partials/header.php'; ?>
<div class="content">

<?= $msg ?>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="penilaian.php">Penilaian</a>
    <span>/</span>
    <span class="active">Input Nilai</span>
</div>

<!-- Profile Header -->
<div class="profile-header">
    <div class="profile-avatar">
        <div class="avatar-placeholder">
            <span class="avatar-icon">⚽</span>
        </div>
    </div>
    <div class="profile-info">
        <h1><?= htmlspecialchars($pemain['nama_pemain']) ?></h1>
        <div class="profile-meta">
            <span class="meta-badge position">
                <?php
                $posisiIcon = ['Penyerang'=>'⚽', 'Gelandang'=>'🎯', 'Bek'=>'🛡️', 'Penjaga Gawang'=>'🧤'];
                echo $posisiIcon[$pemain['posisi']] ?? '🏃';
                ?> <?= $pemain['posisi'] ?>
            </span>
            <span class="meta-badge team">🏛️ <?= htmlspecialchars($pemain['tim'] ?: 'Tim belum diisi') ?></span>
            <?php if($pemain['nomor_punggung']): ?>
            <span class="meta-badge number">🔢 No. <?= $pemain['nomor_punggung'] ?></span>
            <?php endif; ?>
            <?php if($pemain['negara']): ?>
            <span class="meta-badge country">🌍 <?= htmlspecialchars($pemain['negara']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="profile-action">
        <a href="penilaian.php" class="btn-back">
            ← Kembali ke Daftar
        </a>
    </div>
</div>

<!-- Progress Bar -->
<div class="progress-section">
    <div class="progress-header">
        <span class="progress-title">📊 Progress Penilaian</span>
        <span class="progress-count"><?= $terisi ?> dari <?= $totalKriteria ?> kriteria terisi</span>
    </div>
    <div class="progress-bar-container">
        <div class="progress-bar-fill" style="width: <?= $progress ?>%">
            <span class="progress-text"><?= round($progress) ?>%</span>
        </div>
    </div>
</div>

<!-- Skala Penilaian Info -->
<div class="skala-info">
    <div class="skala-header">
        <span class="skala-icon">📏</span>
        <span class="skala-title">Skala Penilaian</span>
    </div>
    <div class="skala-items">
        <div class="skala-item">1 <span>Sangat Buruk</span></div>
        <div class="skala-item">2 <span>Buruk</span></div>
        <div class="skala-item">3 <span>Cukup</span></div>
        <div class="skala-item">4 <span>Baik</span></div>
        <div class="skala-item">5 <span>Sangat Baik</span></div>
    </div>
</div>

<form method="POST" class="form-penilaian">
    <div class="kriteria-container">
        <?php 
        $no = 1;
        while($k = mysqli_fetch_assoc($kriteria)): 
            $currentValue = isset($nilaiExist[$k['id_kriteria']]) ? $nilaiExist[$k['id_kriteria']] : 0;
        ?>
        <div class="kriteria-card" data-kriteria-id="<?= $k['id_kriteria'] ?>">
            <div class="kriteria-header">
                <div class="kriteria-number"><?= sprintf('%02d', $no++) ?></div>
                <div class="kriteria-title">
                    <h3><?= htmlspecialchars($k['nama_kriteria']) ?></h3>
                    <div class="kriteria-desc">
                        <span class="type-badge <?= $k['atribut'] == 'benefit' ? 'benefit' : 'cost' ?>">
                            <?= $k['atribut'] == 'benefit' ? '📈 Benefit (Semakin besar semakin baik)' : '📉 Cost (Semakin kecil semakin baik)' ?>
                        </span>
                    </div>
                </div>
                <div class="kriteria-status">
                    <?php if($currentValue > 0): ?>
                        <span class="status-checked">✓ Terisi</span>
                    <?php else: ?>
                        <span class="status-empty">○ Belum</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="kriteria-body">
                <div class="rating-wrapper">
                    <div class="rating-label">Pilih Nilai:</div>
                    <div class="rating-buttons">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                        <label class="rating-btn <?= $currentValue == $i ? 'active' : '' ?>">
                            <input type="radio" name="nilai[<?= $k['id_kriteria'] ?>]" value="<?= $i ?>" 
                                   <?= $currentValue == $i ? 'checked' : '' ?> required>
                            <span class="rating-number"><?= $i ?></span>
                            <span class="rating-label-small">
                                <?php
                                switch($i){
                                    case 1: echo 'Buruk'; break;
                                    case 2: echo 'Kurang'; break;
                                    case 3: echo 'Cukup'; break;
                                    case 4: echo 'Baik'; break;
                                    case 5: echo 'Sangat Baik'; break;
                                }
                                ?>
                            </span>
                        </label>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn-simpan">
            <span class="btn-icon">💾</span> Simpan Semua Penilaian
        </button>
        <a href="penilaian.php" class="btn-batal">
            <span class="btn-icon">❌</span> Batal
        </a>
    </div>
</form>

</div>
</div>

<script>
document.getElementById('sidebarToggle')?.addEventListener('click',()=>{
    document.querySelector('.sidebar').classList.toggle('open');
});

// Enhanced rating button interaction
document.querySelectorAll('.rating-btn').forEach(btn => {
    const radio = btn.querySelector('input');
    
    radio.addEventListener('change', function() {
        // Remove active class from siblings
        const parent = this.closest('.rating-buttons');
        parent.querySelectorAll('.rating-btn').forEach(b => {
            b.classList.remove('active');
        });
        // Add active class to current
        btn.classList.add('active');
        
        // Update kriteria status
        const kriteriaCard = btn.closest('.kriteria-card');
        const statusSpan = kriteriaCard.querySelector('.kriteria-status span');
        if(this.value && this.value > 0){
            statusSpan.innerHTML = '✓ Terisi';
            statusSpan.className = 'status-checked';
        }
    });
});

// Auto-save indicator (optional)
let formChanged = false;
document.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', () => {
        formChanged = true;
    });
});

window.addEventListener('beforeunload', (e) => {
    if(formChanged){
        e.preventDefault();
        e.returnValue = 'Anda belum menyimpan perubahan. Yakin ingin keluar?';
    }
});
</script>
</body>
</html>