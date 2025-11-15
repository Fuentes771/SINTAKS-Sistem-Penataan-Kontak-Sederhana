<?php
require_once '../includes/functions.php';
requireLogin();

$id = $_GET['id'] ?? null;

if ($id === null || !isset($_SESSION['contacts'][$id])) {
    setFlash('error', 'Kontak tidak ditemukan');
    header('Location: ../index.php');
    exit();
}

// Hapus kontak
$contactName = $_SESSION['contacts'][$id]['nama'];
unset($_SESSION['contacts'][$id]);

// Re-index array
$_SESSION['contacts'] = array_values($_SESSION['contacts']);

setFlash('success', 'Kontak "' . $contactName . '" berhasil dihapus!');
header('Location: ../index.php');
exit();
