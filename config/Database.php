<?php
class Database {
    private $host = "localhost";         
    private $db_name = "warehouse_msib";  
    private $username = "root";          
    private $password = "";              
    public $conn;

    // Method untuk mendapatkan koneksi
    public function getConnection(){
        $this->conn = null;

        try{
            // Membuat koneksi menggunakan PDO
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8", 
                                  $this->username, 
                                  $this->password);
            // Mengatur mode error PDO ke Exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $exception){
            // Melempar pengecualian ke skrip pemanggil
            throw new Exception("Koneksi gagal: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
?>
