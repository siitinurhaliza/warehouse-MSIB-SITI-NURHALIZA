<?php
include_once 'config/Database.php'; 
include_once 'classes/Gudang.php';  
include_once 'classes/Lokasi.php';   
include_once 'includes/header.php';   

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi objek Gudang
$gudang = new Gudang($db);

// Menangani pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Menangani pagination
$limit = 10; // jumlah record per halaman
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Mengambil data gudang dengan pencarian dan pagination
$stmt = $gudang->read($search, $limit, $offset);
$num = $stmt->rowCount();

// Menghitung total record untuk pagination
$total_query = !empty($search) ? 
    "SELECT COUNT(*) as total FROM " . $gudang->table_name . " WHERE name LIKE :search OR location LIKE :search" :
    "SELECT COUNT(*) as total FROM " . $gudang->table_name;

$total_stmt = $db->prepare($total_query);
if (!empty($search)) {
    $search_term = "%{$search}%";
    $total_stmt->bindParam(":search", $search_term, PDO::PARAM_STR);
}
$total_stmt->execute();
$total_row = $total_stmt->fetch(PDO::FETCH_ASSOC);
$total = $total_row['total'];
$total_pages = ceil($total / $limit);

// Inisialisasi pesan
$message = "";
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'deleted':
            $message = "<div id='message-alert' class='alert alert-success'>Data gudang berhasil dihapus.</div>";
            break;
        case 'deactivated':
            $message = "<div id='message-alert' class='alert alert-success'>Status gudang berhasil diubah menjadi 'tidak_aktif'.</div>";
            break;
        case 'updated':
            $message = "<div id='message-alert' class='alert alert-success'>Gudang berhasil diupdate.</div>";
            break;
        case 'failed':
            $message = "<div id='message-alert' class='alert alert-danger'>Operasi gagal dilakukan.</div>";
            break;
        case 'invalid':
            $message = "<div id='message-alert' class='alert alert-warning'>ID gudang tidak valid.</div>";
            break;
        default:
            $message = "<div id='message-alert' class='alert alert-info'>Pesan tidak dikenal.</div>";
            break;
    }
}
?>

<h2 class="mb-4">Daftar Gudang</h2>

<?php echo $message; ?>

<!-- JavaScript untuk menyembunyikan pesan setelah 3 detik dan menghapus parameter 'message' dari URL -->
<script>
window.setTimeout(function() {
    var messageAlert = document.getElementById('message-alert');
    if (messageAlert) {
        // Sembunyikan pesan
        messageAlert.style.display = 'none';

        // Menghapus parameter 'message' dari URL
        if (history.replaceState) {
            var url = new URL(window.location);
            url.searchParams.delete('message');
            history.replaceState(null, '', url.toString());
        }
    }
}, 3000); // 3000 milidetik = 3 detik
</script>

<!-- Formulir Pencarian -->
<form method="GET" action="read_gudang.php" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Cari nama atau lokasi gudang..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-primary" type="submit">Cari</button>
    </div>
</form>

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Nama Gudang</th>
            <th>Lokasi</th>
            <th>Kapasitas</th>
            <th>Status</th>
            <th>Waktu Buka</th>
            <th>Waktu Tutup</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = $offset + 1; // Inisialisasi nomor urut
        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = htmlspecialchars($row['id']);
                $name = htmlspecialchars($row['name']);
                $location = htmlspecialchars($row['location']);
                $capacity = htmlspecialchars($row['capacity']);
                $status = htmlspecialchars(ucfirst($row['status']));
                $opening_hour = htmlspecialchars($row['opening_hour']);
                $closing_hour = htmlspecialchars($row['closing_hour']);

                // Tampilkan baris data gudang
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$name}</td>
                        <td>{$location}</td>
                        <td>{$capacity}</td>
                        <td>{$status}</td>
                        <td>{$opening_hour}</td>
                        <td>{$closing_hour}</td>
                        <td>
                            <a href='update_gudang.php?id={$id}' class='btn btn-sm btn-warning'>Edit</a>
                            <a href='delete_gudang.php?id={$id}' class='btn btn-sm btn-danger' onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini secara permanen?');\">Hapus</a>
                            <a href='nonaktifkan_gudang.php?id={$id}' class='btn btn-sm btn-secondary' onclick=\"return confirm('Apakah Anda yakin ingin menonaktifkan status gudang ini?');\">Nonaktifkan</a>
                        </td>
                      </tr>";
                $no++; // Increment nomor urut
            }
        } else {
            echo "<tr><td colspan='8' class='text-center'>Tidak ada data gudang.</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
<nav aria-label="Page navigation">
  <ul class="pagination justify-content-center">
    <!-- Previous Button -->
    <li class="page-item <?php if ($page <= 1) { echo 'disabled'; } ?>">
      <a class="page-link" href="<?php if ($page <= 1) { echo '#'; } else { echo "?page=".($page-1)."&search=".urlencode($search); } ?>">Previous</a>
    </li>

    <!-- Page Numbers -->
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?php if ($page == $i) { echo 'active'; } ?>">
            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
        </li>
    <?php endfor; ?>

    <!-- Next Button -->
    <li class="page-item <?php if ($page >= $total_pages) { echo 'disabled'; } ?>">
      <a class="page-link" href="<?php if ($page >= $total_pages) { echo '#'; } else { echo "?page=".($page+1)."&search=".urlencode($search); } ?>">Next</a>
    </li>
  </ul>
</nav>
<?php endif; ?>

<?php
include_once 'includes/footer.php'; // Ensure the footer is included
?>
