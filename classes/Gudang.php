<?php
class Gudang {
    private $conn;
    public $table_name = "gudang";

    // Properti sesuai dengan kolom tabel gudang
    public $id;
    public $name;
    public $location;
    public $capacity;
    public $status;
    public $opening_hour;
    public $closing_hour;

    // Constructor dengan mengirimkan koneksi database
    public function __construct($db){
        $this->conn = $db;
    }

    // **CREATE** - Menambahkan data gudang baru
    public function create(){
        $query = "INSERT INTO " . $this->table_name . "
                  SET name=:name, location=:location, capacity=:capacity, 
                      status=:status, opening_hour=:opening_hour, closing_hour=:closing_hour";

        $stmt = $this->conn->prepare($query);

        // Membersihkan data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->location = htmlspecialchars(strip_tags($this->location));
        $this->capacity = htmlspecialchars(strip_tags($this->capacity));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->opening_hour = htmlspecialchars(strip_tags($this->opening_hour));
        $this->closing_hour = htmlspecialchars(strip_tags($this->closing_hour));

        // Mengikat parameter
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":capacity", $this->capacity);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":opening_hour", $this->opening_hour);
        $stmt->bindParam(":closing_hour", $this->closing_hour);

        // Eksekusi query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    // **READ** - Mengambil semua data gudang atau berdasarkan ID, dengan pencarian dan pagination
    public function read($search = "", $limit = 10, $offset = 0){
        if(!empty($search)){
            $query = "SELECT * FROM " . $this->table_name . " 
                      WHERE name LIKE :search OR location LIKE :search 
                      ORDER BY id DESC 
                      LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($query);
            $search_term = "%{$search}%";
            $stmt->bindParam(":search", $search_term, PDO::PARAM_STR);
        }
        else{
            $query = "SELECT * FROM " . $this->table_name . " 
                      ORDER BY id DESC 
                      LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt;
    }

    // **READ BY ID** - Mengambil data gudang berdasarkan ID
    public function readById(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // **UPDATE** - Mengupdate data gudang berdasarkan ID
    public function update(){
        $query = "UPDATE " . $this->table_name . " 
                  SET name=:name, location=:location, capacity=:capacity, 
                      status=:status, opening_hour=:opening_hour, 
                      closing_hour=:closing_hour
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Membersihkan data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->location = htmlspecialchars(strip_tags($this->location));
        $this->capacity = htmlspecialchars(strip_tags($this->capacity));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->opening_hour = htmlspecialchars(strip_tags($this->opening_hour));
        $this->closing_hour = htmlspecialchars(strip_tags($this->closing_hour));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Mengikat parameter
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":capacity", $this->capacity);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":opening_hour", $this->opening_hour);
        $stmt->bindParam(":closing_hour", $this->closing_hour);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        // Eksekusi query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

  
    public function delete() {
        // Query untuk menghapus data berdasarkan ID
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
    
        // Eksekusi query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function nonaktifkan($id){
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Mengatur nilai status ke 'tidak_aktif'
        $status = 'tidak_aktif';

        // Binding parameter
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Eksekusi query
        if($stmt->execute()){
            // Periksa apakah ada baris yang terpengaruh
            if($stmt->rowCount() > 0){
                return true;
            }
            else{
                // Tidak ada baris yang diperbarui, mungkin ID tidak ditemukan
                return false;
            }
        }

        // Jika gagal, bisa menambahkan log error
        return false;
    }
    
    
}
?>
