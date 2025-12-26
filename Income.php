<?php 

class Incomes {
  private PDO $db;


  public function __construct(PDO $db) {
    $this->db = $db;
  }


  public function UserId(
    string $email
  ): int{
    $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    return $stmt->fetchColumn();
  }

  public function addIncome(int $amount, ?string $category, string $description, ?string $date, string $email): void{
                  
    $user_id = $this->UserId($email);


    if ($date) {
      $stmt = $this->db->prepare("INSERT INTO Incomes_tracker(Incomes, Category, description, Date, user_id) VALUES(?, ?, ?, ?, ?)");
      $stmt->execute([$amount, $category, $description, $date, $user_id]);
    }
    else{
      $stmt = $this->db->prepare("INSERT INTO Incomes_tracker(Incomes, Category, description, Date, user_id) VALUES(?, ?, ?, CURRENT_DATE(), ?)");
      $stmt->execute([$amount, $category, $description, $user_id]);
    }
  }

  public function updateIncome(
    int $id,
    int $amount,
    string $description,
    ?string $date
  ): void {
    if($date) {
      $stmt = $this->db->prepare("UPDATE Incomes_tracker SET Incomes = ?, Description = ?, Date = ? WHERE inc_id = ?");
      $stmt->execute([$amount, $description, $date, $id]);
    } else {
      $stmt = $this->db->prepare("UPDATE Incomes_tracker SET Incomes = ?, Description = ? WHERE inc_id = ?");
      $stmt->execute([$amount, $description, $id]);
    }
  }
  public function deleteIncome(int $id): void {
    $stmt = $this->db->prepare("DELETE FROM Incomes_tracker WHERE inc_id = ?");
    $stmt->execute([$id]);

  }

  public function IncomesList(
    string $email,
  ): array {
    $user_id = $this->UserId($email);

    $stmt = $this->db->prepare("SELECT * FROM incomes_tracker WHERE MONTH(Date) = MONTH(CURRENT_DATE) AND YEAR(Date) = YEAR(CURRENT_DATE) AND user_id = :result");
    $stmt->execute(['result' => $user_id]);
    return $stmt->fetchAll();
  }

  public function IncomesSum(
    string $email,
  ): float {

    $user_id = $this->UserId($email);
    $stmt = $this->db->prepare("SELECT SUM(Incomes) AS total_incomes FROM incomes_tracker WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch();
    return (float) $row['total_incomes'];
  }
  
  public function GetIncomesByCategory(string $category, string $email): array{
    $user_id = $this->UserId($email);
    $stmt = $this->db->prepare("SELECT * FROM incomes_tracker WHERE category = ? AND user_id = ?");
    $stmt->execute([$category, $user_id]);
    return $stmt->fetchAll();
  }
}



// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "tracker";


// $sql_con = new mysqli($servername, $username, $password, $dbname);

// if (isset($_POST['Incomes']) && isset($_POST['descreption'])){
//   $Incomes = trim($_POST['Incomes']);
//   $Desc = trim($_POST['descreption']);
//   $Date = $_POST['date'];
        
//     if ($Incomes !== '') {


              
//       if ($sql_con->connect_error) {
//         die("Connection failed: " . $sql_con->connect_error);
//       }


            
//       if($Date != '') {
//         $stmt = $sql_con->prepare("INSERT INTO incomes_tracker(Incomes, Description, Date) VALUES(?, ?, ?)");
//         $stmt->bind_param("iss", $Incomes, $Desc, $Date);
//       } else {
//             $stmt = $sql_con->prepare("INSERT INTO incomes_tracker (Incomes, Description, Date) VALUES (?, ?, CURRENT_DATE())");
//             $stmt->bind_param("is", $Incomes, $Desc);

//       }
//       $stmt->execute();
//       $stmt->close();
//     }
//   }

//       if (isset($_POST['edit-Incomes'])){
//         $EIncomes = trim($_POST['edit-Incomes']);
//         $EDesc = trim($_POST['edit-descreption']);
//         $EDate = $_POST['edit-date'];
//         $id = intval($_POST['id']);


//         if($EIncomes !== '') {
//         if (!empty($EDate)) {
//             // Update including date
//             $stmt = $sql_con->prepare("UPDATE incomes_tracker SET Incomes = ?, Description = ?, Date = ? WHERE id = ?");
//             $stmt->bind_param("issi", $EIncomes, $EDesc, $EDate, $id);

//         } else {
//             // Update without date change
//             $stmt = $sql_con->prepare("UPDATE incomes_tracker SET Incomes = ?, Description = ? WHERE id = ?");
//             $stmt->bind_param("isi", $EIncomes, $EDesc, $id);
//         }
//           $stmt->execute();
//           $stmt->close();
//         }
//       }

//       if (isset($_POST['Did'])){
//         $Did = $_POST['Did'];
//         $stmt = $sql_con->prepare("DELETE FROM incomes_tracker WHERE id = ?");
//         $stmt->bind_param("i", $Did);
//         $stmt->execute();
//         $stmt->close();
//       }


//     $sql_con->close();
    






// header("Location: Home.php");
// exit;

?>