<?php
require_once '../includes/functions.php';
requireGuest();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = sanitize($_POST['nama'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    // Validasi
    if (empty($nama)) {
        $errors[] = 'Nama lengkap harus diisi';
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $nama)) {
        $errors[] = 'Nama hanya boleh berisi huruf dan spasi';
    }
    
    if (empty($email)) {
        $errors[] = 'Email harus diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid';
    } else {
        // Cek email sudah terdaftar
        foreach ($_SESSION['users'] as $user) {
            if ($user['email'] === $email) {
                $errors[] = 'Email sudah terdaftar';
                break;
            }
        }
    }
    
    if (empty($password)) {
        $errors[] = 'Password harus diisi';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password minimal 6 karakter';
    }
    
    if ($password !== $password_confirm) {
        $errors[] = 'Konfirmasi password tidak cocok';
    }
    
    if (empty($errors)) {
        $_SESSION['users'][] = [
            'nama' => $nama,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        setFlash('success', 'Registrasi berhasil! Silakan login.');
        header('Location: login.php');
        exit();
    }
}

$pageTitle = 'Daftar';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - SINTAKS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php
?>

<div class="auth-container">
    <div class="auth-card fade-in">
        <div class="auth-header">
            <h2>Daftar Akun</h2>
            <p style="color: var(--gray-400); margin-top: 0.5rem;">Buat akun baru untuk mengelola kontak Anda</p>
        </div>
        
        <div class="auth-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <strong>Error:</strong>
                        <ul style="margin-top: 0.5rem; padding-left: 1.25rem;">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="nama" class="form-input" placeholder="Masukkan nama lengkap" 
                           value="<?php echo isset($nama) ? $nama : ''; ?>" required>
                    <span class="form-hint">Hanya huruf dan spasi</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-input" placeholder="nama@email.com" 
                           value="<?php echo isset($email) ? $email : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password <span class="required">*</span></label>
                    <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                    <input type="password" name="password_confirm" class="form-input" placeholder="Ketik ulang password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Daftar Sekarang
                </button>
            </form>
            
            <div class="divider">
                <span>Sudah punya akun?</span>
            </div>
            
            <a href="login.php" class="btn btn-secondary btn-block">
                Login di sini
            </a>
        </div>
    </div>
</div>

</body>
</html>
