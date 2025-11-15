<?php
require_once '../includes/functions.php';
requireGuest();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi';
    } else {
        $userFound = false;
        foreach ($_SESSION['users'] as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'nama' => $user['nama'],
                    'email' => $user['email']
                ];
                $_SESSION['user_logged_in'] = true;
                
                $userFound = true;
                header('Location: ../index.php');
                exit();
            }
        }
        
        if (!$userFound) {
            $error = 'Email atau password salah';
        }
    }
}

$pageTitle = 'Login';
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
            <h2>Login</h2>
            <p style="color: var(--gray-400); margin-top: 0.5rem;">Masuk ke akun Anda</p>
        </div>
        
        <div class="auth-body">
            <?php 
            $flash = getFlash();
            if ($flash): 
            ?>
                <div class="alert alert-<?php echo $flash['type']; ?>">
                    <?php if ($flash['type'] === 'success'): ?>
                        <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    <?php endif; ?>
                    <div><?php echo $flash['message']; ?></div>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div><?php echo $error; ?></div>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="nama@email.com" 
                           value="<?php echo isset($email) ? $email : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Masukkan password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Login
                </button>
            </form>
            
            <div class="divider">
                <span>Belum punya akun?</span>
            </div>
            
            <a href="register.php" class="btn btn-secondary btn-block">
                Daftar sekarang
            </a>
        </div>
    </div>
</div>

</body>
</html>
