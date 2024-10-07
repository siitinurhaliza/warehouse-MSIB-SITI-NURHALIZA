<?php
include_once 'config/Database.php';
include_once 'classes/Gudang.php';

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi objek Gudang
$gudang = new Gudang($db);

// Memeriksa apakah parameter 'id' ada dan valid
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id = (int)$_GET['id']; // Pastikan ID adalah integer

    // Tambahkan log atau echo untuk debugging
    echo "Mencoba menonaktifkan gudang dengan ID: " . $id . "<br>";

    // Panggil metode nonaktifkan
    if($gudang->nonaktifkan($id)){
        echo "Gudang berhasil dinonaktifkan.<br>";
        // Redirect dengan parameter 'message=deactivated'
        header("Location: read_gudang.php?message=deactivated");
    }
    else{
        echo "Gagal menonaktifkan gudang.<br>";
        // Redirect dengan parameter 'message=failed'
        header("Location: read_gudang.php?message=failed");
    }
}
else{
    echo "Parameter 'id' tidak valid atau tidak ada.<br>";
    // Redirect dengan parameter 'message=invalid'
    header("Location: read_gudang.php?message=invalid");
}
exit();
?>
