<?php 

class Database {
  private PDO $connection;


  public function __construct(
    string $host = "localhost",
    string $user = "root",
    string $pass = "",
    string $db = "Smart_Wallet"
  ){
    try{
      $this->connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
  }
  public function getConnection (): PDO {
    return $this->connection;
  }
}

?>