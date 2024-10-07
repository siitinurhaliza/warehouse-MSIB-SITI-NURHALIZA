<?php
class Lokasi {
    private $conn;
    public $table_name = "lokasi";

    // Properti sesuai dengan kolom tabel gudang
    public $id;
    public $nama_lokasi;
    public $kota;
    public $provinsi;
    public $alamat;
    public $kode_pos;

    // Constructor dengan mengirimkan koneksi database
    public function __construct($db){
        $this->conn = $db;
    }

    // **CREATE** - Menambahkan data gudang baru
    public function create(){
        $query = "INSERT INTO " . $this->table_name . "
                  SET nama_lokasi=:nama_lokasi, kota=:kota, provinsi=:provinsi, 
                      alamat=:alamat, kode_pos=:kode_pos";

        $stmt = $this->conn->prepare($query);

        // Membersihkan data
        $this->nama_lokasi = htmlspecialchars(strip_tags($this->nama_lokasi));
        $this->kota = htmlspecialchars(strip_tags($this->kota));
        $this->provinsi = htmlspecialchars(strip_tags($this->provinsi));
        $this->alamat = htmlspecialchars(strip_tags($this->alamat));
        $this->kode_pos = htmlspecialchars(strip_tags($this->kode_pos));
       

        // Mengikat parameter
        $stmt->bindParam(":nama_lokasi", $this->nama_lokasi);
        $stmt->bindParam(":kota", $this->kota);
        $stmt->bindParam(":provinsi", $this->provinsi);
        $stmt->bindParam(":alamat", $this->alamat);
        $stmt->bindParam(":kode_pos", $this->kode_pos);
      

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
                      WHERE nama_lokasi LIKE :search OR kota LIKE :search 
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
                  SET nama_lokasi=:nama_lokasi, kota=:kota, provinsi=:provinsi, 
                      alamat=:alamat, kode_pos=:kode_pos
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Membersihkan data
        $this->nama_lokasi = htmlspecialchars(strip_tags($this->nama_lokasi));
        $this->kota = htmlspecialchars(strip_tags($this->kota));
        $this->provinsi = htmlspecialchars(strip_tags($this->provinsi));
        $this->alamat = htmlspecialchars(strip_tags($this->alamat));
        $this->kode_pos = htmlspecialchars(strip_tags($this->kode_pos));
       
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Mengikat parameter
        $stmt->bindParam(":nama_lokasi", $this->nama_lokasi);
        $stmt->bindParam(":kota", $this->kota);
        $stmt->bindParam(":provinsi", $this->provinsi);
        $stmt->bindParam(":alamat", $this->alamat);
        $stmt->bindParam(":kode_pos", $this->kode_pos);
      
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
    
  
    
}
?>
