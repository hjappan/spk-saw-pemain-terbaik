<?php
session_start();
include 'config/koneksi.php';

if(isset($_SESSION['login'])){ header("Location: pages/dashboard.php"); exit; }

$error='';
if(isset($_POST['login'])){
    $username=mysqli_real_escape_string($conn,$_POST['username']);
    $password=$_POST['password'];
    $q=mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($q)==1){
        $user=mysqli_fetch_assoc($q);
        if(password_verify($password,$user['password'])){
            $_SESSION['login']=true;
            $_SESSION['id_user']=$user['id_user'];
            $_SESSION['nama']=$user['nama_lengkap'];
            header("Location: pages/dashboard.php"); exit;
        }
    }
    $error="Username atau password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Login – SPK Pemain Terbaik</title>
<link rel="stylesheet" href="assets/css/style.css">
<style>
.login-page {
  display:flex; justify-content:center; align-items:center;
  min-height:100vh; background:var(--bg-main);
}
.login-card {
  width:420px; background:var(--bg-card);
  padding:44px 40px; border-radius:20px;
  box-shadow:0 20px 60px rgba(0,0,0,0.5);
}
.login-logo { text-align:center; margin-bottom:28px; }
.login-logo .icon { font-size:48px; display:block; margin-bottom:10px; }
.login-logo h1 { font-size:22px; color:var(--accent); margin-bottom:4px; }
.login-logo p { color:var(--text-muted); font-size:13px; }
.login-card input {
  width:100%; padding:12px 15px;
  border:1px solid var(--border); border-radius:var(--radius-sm);
  background:var(--bg-main); color:var(--text);
  font-family:'Poppins',sans-serif; font-size:14px;
  outline:none; margin-bottom:14px;
  transition:border-color 0.2s;
}
.login-card input:focus { border-color:var(--accent); }
.login-card button {
  width:100%; padding:13px;
  background:var(--accent); border:none;
  border-radius:var(--radius-sm); color:#fff;
  font-family:'Poppins',sans-serif; font-size:14px;
  font-weight:600; cursor:pointer;
  transition:background 0.2s;
}
.login-card button:hover { background:var(--accent-dark); }
</style>
</head>
<body>
<div class="login-page">
<div class="login-card">
  <div class="login-logo">
    <span class="icon">⚽</span>
    <h1>SPK Pemain Terbaik</h1>
    <p>Sistem Pendukung Keputusan · Metode SAW</p>
  </div>
  <?php if($error): ?><div class="alert alert-error">⚠️ <?= $error ?></div><?php endif; ?>
  <form method="POST">
    <input type="text" name="username" placeholder="Username" required autocomplete="username">
    <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
    <button type="submit" name="login">Masuk →</button>
  </form>
</div>
</div>
</body>
</html>
