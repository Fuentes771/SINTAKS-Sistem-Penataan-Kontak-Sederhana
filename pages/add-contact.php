<?php
require_once '../includes/functions.php';
requireLogin();

$errors = [];
$pageTitle = 'Tambah Kontak';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = sanitize($_POST['nama'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $telepon = sanitize($_POST['telepon'] ?? '');
    $kategori = sanitize($_POST['kategori'] ?? '');
    
    // Validasi
    if (empty($nama)) {
        $errors[] = 'Nama harus diisi';
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $nama)) {
        $errors[] = 'Nama hanya boleh berisi huruf dan spasi';
    }
    
    if (empty($email)) {
        $errors[] = 'Email harus diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid';
    }
    
    if (empty($telepon)) {
        $errors[] = 'Telepon harus diisi';
    } elseif (!preg_match("/^[0-9+\-\(\)\s]+$/", $telepon)) {
        $errors[] = 'Format telepon tidak valid';
    }
    
    if (empty($kategori)) {
        $errors[] = 'Kategori harus dipilih';
    } elseif (!in_array($kategori, ['Pribadi', 'Kerja', 'Keluarga', 'Lainnya'])) {
        $errors[] = 'Kategori tidak valid';
    }
    
    if (empty($errors)) {
        $_SESSION['contacts'][] = [
            'nama' => $nama,
            'email' => $email,
            'telepon' => $telepon,
            'kategori' => $kategori,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        setFlash('success', 'Kontak berhasil ditambahkan!');
        header('Location: ../index.php');
        exit();
    }
}
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

<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="brand">
                <h1>SINTAKS</h1>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <span style="color: var(--gray-300); font-size: 0.9rem;"><?php echo $_SESSION['user']['nama']; ?></span>
                <a href="../logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </div>
</header>

<div class="dashboard-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 700; color: var(--primary-dark);">Tambah Kontak Baru</h1>
            <p style="color: var(--gray-500); margin-top: 0.5rem;">Isi form di bawah untuk menambahkan kontak</p>
        </div>
        <a href="../index.php" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul style="margin-top: 0.5rem; padding-left: 1.25rem;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="" style="max-width: 600px;">
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                <input type="text" name="nama" class="form-input" placeholder="Contoh: John Doe" 
                       value="<?php echo isset($nama) ? $nama : ''; ?>" required>
                <span class="form-hint">Hanya huruf dan spasi</span>
            </div>

            <div class="form-group">
                <label class="form-label">Email <span class="required">*</span></label>
                <input type="email" name="email" class="form-input" placeholder="contoh@email.com" 
                       value="<?php echo isset($email) ? $email : ''; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Telepon <span class="required">*</span></label>
                <input type="tel" name="telepon" class="form-input" placeholder="08123456789" 
                       value="<?php echo isset($telepon) ? $telepon : ''; ?>" required>
                <span class="form-hint">Boleh menggunakan +, -, (, ), dan spasi</span>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori <span class="required">*</span></label>
                <select name="kategori" class="form-input" required>
                    <option value="">Pilih kategori</option>
                    <option value="Pribadi" <?php echo (isset($kategori) && $kategori === 'Pribadi') ? 'selected' : ''; ?>>Pribadi</option>
                    <option value="Kerja" <?php echo (isset($kategori) && $kategori === 'Kerja') ? 'selected' : ''; ?>>Kerja</option>
                    <option value="Keluarga" <?php echo (isset($kategori) && $kategori === 'Keluarga') ? 'selected' : ''; ?>>Keluarga</option>
                    <option value="Lainnya" <?php echo (isset($kategori) && $kategori === 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                </select>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary btn-lg">Simpan Kontak</button>
                <a href="../index.php" class="btn btn-secondary btn-lg">Batal</a>
            </div>
        </form>
    </div>
</div>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <p style="font-weight: 600; font-size: 1.125rem; margin-bottom: 0.5rem;">Manajaman Kontak</p>
                <p>&copy; 2025 M Sulthon Alfarizky - Sistem Penataan Kontak Sederhana</p>
            </div>
        </div>
    </footer>
</body>
</html>
