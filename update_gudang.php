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

// Mendapatkan ID gudang dari URL
if(isset($_GET['id']) && !empty($_GET['id'])){
    $gudang->id = $_GET['id'];

    // Mengambil data gudang yang akan diupdate
    $stmt = $gudang->read();
    if($stmt->rowCount() > 0){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $gudang->name = $row['name'];
        $gudang->location = $row['location'];
        $gudang->capacity = $row['capacity'];
        $gudang->status = $row['status'];
        $gudang->opening_hour = $row['opening_hour'];
        $gudang->closing_hour = $row['closing_hour'];
    } else {
        echo "<div class='alert alert-danger'>Gudang tidak ditemukan.</div>";
        include_once 'includes/footer.php';
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>ID gudang tidak valid.</div>";
    include_once 'includes/footer.php';
    exit();
}

// Cek apakah formulir telah disubmit
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Mengambil data dari formulir
    $gudang->name = $_POST['name'];
    $gudang->location = $_POST['location'];
    $gudang->capacity = $_POST['capacity'];
    $gudang->status = $_POST['status'];
    $gudang->opening_hour = $_POST['opening_hour'];
    $gudang->closing_hour = $_POST['closing_hour'];

    // Mengupdate gudang
    if($gudang->update()){
        $message = "<div class='alert alert-success'>Gudang berhasil diupdate.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Gagal mengupdate gudang.</div>";
    }
}
?>

<h2 class="mb-4">Edit Gudang</h2>

<?php echo $message; ?>

<form method="POST" action="update_gudang.php?id=<?php echo $gudang->id; ?>">
    <div class="mb-3">
        <label for="name" class="form-label">Nama Gudang</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($gudang->name); ?>" required>
    </div>
    <div class="mb-3">
        <label for="location" class="form-label">Lokasi Gudang</label>
        <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($gudang->location); ?>" required>
    </div>
    <div class="mb-3">
        <label for="capacity" class="form-label">Kapasitas (unit)</label>
        <input type="number" class="form-control" id="capacity" name="capacity" min="1" value="<?php echo htmlspecialchars($gudang->capacity); ?>" required>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status Operasi</label>
        <select class="form-select" id="status" name="status" required>
            <option value="aktif" <?php if($gudang->status == 'aktif') echo 'selected'; ?>>Aktif</option>
            <option value="tidak_aktif" <?php if($gudang->status == 'tidak_aktif') echo 'selected'; ?>>Tidak Aktif</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="opening_hour" class="form-label">Waktu Buka</label>
        <input type="time" class="form-control" id="opening_hour" name="opening_hour" value="<?php echo htmlspecialchars($gudang->opening_hour); ?>" required>
    </div>
    <div class="mb-3">
        <label for="closing_hour" class="form-label">Waktu Tutup</label>
        <input type="time" class="form-control" id="closing_hour" name="closing_hour" value="<?php echo htmlspecialchars($gudang->closing_hour); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Gudang</button>
    <a href="read_gudang.php" class="btn btn-secondary">Kembali</a>
</form>

<?php
include_once 'includes/footer.php';
?>
