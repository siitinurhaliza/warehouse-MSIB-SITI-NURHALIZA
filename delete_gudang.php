<?php
include_once 'config/Database.php';
include_once 'classes/Gudang.php';

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi objek Gudang
$gudang = new Gudang($db);

// Mendapatkan ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID tidak ditemukan.');

// Menghapus gudang
$gudang->id = $id;
if ($gudang->delete()) {
    header('Location: read_gudang.php?message=deleted');
} else {
    header('Location: read_gudang.php?message=failed');
}
?>
