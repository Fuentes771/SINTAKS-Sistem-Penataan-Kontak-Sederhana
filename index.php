<?php
require_once 'includes/functions.php';
requireLogin();

// Ambil data kontak
$contacts = $_SESSION['contacts'] ?? [];

// Filter dan search
$search = sanitize($_GET['search'] ?? '');
$category = sanitize($_GET['category'] ?? '');

if (!empty($search) || !empty($category)) {
    $contacts = array_filter($contacts, function($contact) use ($search, $category) {
        $matchSearch = empty($search) || 
                      stripos($contact['nama'], $search) !== false || 
                      stripos($contact['email'], $search) !== false ||
                      stripos($contact['telepon'], $search) !== false;
        
        $matchCategory = empty($category) || $contact['kategori'] === $category;
        
        return $matchSearch && $matchCategory;
    });
}

$pageTitle = 'Dashboard - Manajemen Kontak';
include 'includes/header.php';
?>

<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="brand">
                <h1>SINTAKS</h1>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <span style="color: var(--gray-300); font-size: 0.9rem;"><?php echo $_SESSION['user']['nama']; ?></span>
                <a href="auth/logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </div>
</header>

<div class="dashboard-container">

    <?php 
    $flash = getFlash();
    if ($flash): 
    ?>
        <div class="alert alert-<?php echo $flash['type']; ?> slide-down">
            <?php if ($flash['type'] === 'success'): ?>
                <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            <?php endif; ?>
            <div><?php echo $flash['message']; ?></div>
        </div>
    <?php endif; ?>

    <!-- Summary -->
    <div style="background: var(--gray-100); padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--primary-dark);">Manajemen Kontak</h2>
            <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: var(--gray-600);">Total: <strong><?php echo count($_SESSION['contacts']); ?></strong> kontak</p>
        </div>
        <a href="pages/add-contact.php" class="btn btn-primary">+ Tambah Kontak</a>
    </div>

    <!-- Actions -->
    <div class="card">

        <!-- Filter & Search -->
        <form method="GET" style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
            <input type="text" name="search" class="form-input" placeholder="Cari nama, email, atau telepon..." 
                   value="<?php echo $search; ?>" style="flex: 1; min-width: 200px;">
            
            <select name="category" class="form-input" style="width: 200px;">
                <option value="">Semua Kategori</option>
                <option value="Pribadi" <?php echo $category === 'Pribadi' ? 'selected' : ''; ?>>Pribadi</option>
                <option value="Kerja" <?php echo $category === 'Kerja' ? 'selected' : ''; ?>>Kerja</option>
                <option value="Keluarga" <?php echo $category === 'Keluarga' ? 'selected' : ''; ?>>Keluarga</option>
                <option value="Lainnya" <?php echo $category === 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
            </select>
            
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if (!empty($search) || !empty($category)): ?>
                <a href="index.php" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
        </form>

        <!-- Contact List -->
        <?php if (empty($contacts)): ?>
            <div class="empty-state">
                <svg style="width: 64px; height: 64px; color: var(--gray-300); margin-bottom: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p style="color: var(--gray-400); font-size: 1.125rem; margin-bottom: 1rem;">
                    <?php echo (!empty($search) || !empty($category)) ? 'Tidak ada kontak yang ditemukan' : 'Belum ada kontak'; ?>
                </p>
                <?php if (empty($search) && empty($category)): ?>
                    <a href="pages/add-contact.php" class="btn btn-primary">Tambah Kontak Pertama</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($contacts as $id => $contact): 
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <strong><?php echo $contact['nama']; ?></strong>
                                </td>
                                <td><?php echo $contact['email']; ?></td>
                                <td><?php echo $contact['telepon']; ?></td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo match($contact['kategori']) {
                                            'Pribadi' => 'primary',
                                            'Kerja' => 'success',
                                            'Keluarga' => 'warning',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo $contact['kategori']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="pages/edit-contact.php?id=<?php echo $id; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                        <a href="pages/delete-contact.php?id=<?php echo $id; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin ingin menghapus kontak <?php echo $contact['nama']; ?>?')">
                                            Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
