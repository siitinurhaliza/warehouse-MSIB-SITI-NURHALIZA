<?php
include_once 'config/Database.php';
include_once 'classes/Gudang.php';
include_once 'includes/header.php';

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi objek Gudang
$gudang = new Gudang($db);

// Inisialisasi pesan
$message = "";

// Cek apakah formulir telah disubmit
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Mengambil data dari formulir
    $gudang->name = $_POST['name'];
    $gudang->location = $_POST['location'];
    $gudang->capacity = $_POST['capacity'];
    $gudang->status = $_POST['status'];
    $gudang->opening_hour = $_POST['opening_hour'];
    $gudang->closing_hour = $_POST['closing_hour'];
  // Menambahkan gudang
    if($gudang->create()){
        $message = "<div class='alert alert-success'>Gudang berhasil ditambahkan.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Gagal menambahkan gudang.</div>";
    }
}
?>

<h2 class="mb-4">Tambah Gudang Baru</h2>

<?php echo $message; ?>

<form method="POST" action="create_gudang.php">
    <div class="mb-3">
        <label for="name" class="form-label">Nama Gudang</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="location" class="form-label">Lokasi Gudang</label>
        <input type="text" class="form-control" id="location" name="location" required>
    </div>
    <div class="mb-3">
        <label for="capacity" class="form-label">Kapasitas (unit)</label>
        <input type="number" class="form-control" id="capacity" name="capacity" min="1" required>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status Operasi</label>
        <select class="form-select" id="status" name="status" required>
            <option value="aktif" selected>Aktif</option>
            <option value="tidak_aktif">Tidak Aktif</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="opening_hour" class="form-label">Waktu Buka</label>
        <input type="time" class="form-control" id="opening_hour" name="opening_hour" required>
    </div>
    <div class="mb-3">
        <label for="closing_hour" class="form-label">Waktu Tutup</label>
        <input type="time" class="form-control" id="closing_hour" name="closing_hour" required>
    </div>
    <button type="submit" class="btn btn-primary">Tambah Gudang</button>
</form>

<?php
include_once 'includes/footer.php';
?>
