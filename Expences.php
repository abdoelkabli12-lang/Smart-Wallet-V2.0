<?php 



class Expences {
  private PDO $db;

  public function __construct(PDO $db){
    $this->db = $db;
  }
  
  public function UserId(
    string $email
  ): int{
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    return $stmt->fetchColumn();
  }

  public function addExpence(
    int $amount, 
    ?string $category,
    string $description, 
    ?string $date,
    string $email
    ){

          $user_id = $this->UserId($email);

    if ($date) {
      $stmt = $this->db->prepare("INSERT INTO expences_tracker(Expences, category, description, Date, user_id) VALUES(?, ?, ?, ?, ?)");
      $stmt->execute([$amount, $category, $description, $date, $user_id]);
    }
    else{
      $stmt = $this->db->prepare("INSERT INTO expences_tracker(Expences, category, description, Date, user_id) VALUES(?, ?, ?, CURRENT_DATE(), ?)");
      $stmt->execute([$amount, $category, $description, $user_id]);
    }
  }

  public function updateExpence(
    int $id,
    int $amount,
    string $description,
    ?string $date
  ): void {
    if($date) {
      $stmt = $this->db->prepare("UPDATE expences_tracker SET Expences = ?, Description = ?, Date = ? WHERE exp_id = ?");
      $stmt->execute([$amount, $description, $date, $id]);
    } else {
      $stmt = $this->db->prepare("UPDATE expences_tracker SET Expences = ?, Description = ? WHERE exp_id = ?");
      $stmt->execute([$amount, $description, $id]);
    }
  }
  public function deleteExpence(int $id, string $email): void {
              
    $user_id = $this->UserId($email);

    $stmt = $this->db->prepare("DELETE FROM expences_tracker WHERE exp_id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    // $this->db->close();
  }

  public function ExpensesList(string $email): array {
      $user_id = $this->UserId($email);

    $stmt = $this->db->prepare("SELECT * FROM expences_tracker WHERE MONTH(Date) = MONTH(CURRENT_DATE) AND YEAR(Date) = YEAR(CURRENT_DATE) AND user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
  }

  public function ExpensesSum(string $email): float {

          $user_id = $this->UserId($email);


    $stmt = $this->db->prepare("SELECT SUM(Expences) AS total_expenses FROM expences_tracker WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch();
    return (float) $row['total_expenses'];
  }

    public function GetExpensesByCategory(string $category, string $email): array{
    $user_id = $this->UserId($email);
    $stmt = $this->db->prepare("SELECT * FROM expences_tracker WHERE category = ? AND user_id = ?");
    $stmt->execute([$category, $user_id]);
    return $stmt->fetchAll();
  }

}
// if (isset($_POST['Expences']) && isset($_POST['descreption_exp']))  {
//     $Expences = trim($_POST['Expences']);
//     $Desc_exp = trim($_POST['descreption_exp']);
//     $Date_exp = $_POST['Date_exp1'];
        
//     if ($Expences !== '') {
//       if ($sql_con->connect_error) {
//         die("Connection failed: " . $sql_con->connect_error);
//       }
//       if($Date_exp != '') {
//       $stmt = $sql_con->prepare("INSERT INTO expences_tracker(Expences, description, Date) VALUES(?, ?, ?)");
//       $stmt->bind_param("iss", $Expences, $Desc_exp, $Date_exp);
//       } else { 
//         $stmt = $sql_con->prepare("INSERT INTO expences_tracker(expences, description, Date) VALUES(?, ?, CURRENT_DATE())");
//               $stmt->bind_param("is", $Expences, $Desc_exp);
//       }
//       $stmt->execute();
//       $stmt->close();
//     }
// }

//       if (isset($_POST['edit-Expenses'])){
//         $EExpences = trim($_POST['edit-Expenses']);
//         $EXDesc = trim($_POST['edit-Edescreption']);
//         $EXDate = $_POST['edit-Edate'];
//         $id = intval($_POST['id']);


//         if($EExpences !== '') {
//         if (!empty($EXDate)) {
//             // Update including date
//             $stmt = $sql_con->prepare("UPDATE expences_tracker SET Expences = ?, Description = ?, Date = ? WHERE id = ?");
//             $stmt->bind_param("issi", $EExpences, $EXDesc, $EXDate, $id);

//         } else {
//             // Update without date change
//             $stmt = $sql_con->prepare("UPDATE expences_tracker SET Expences = ?, Description = ? WHERE id = ?");
//             $stmt->bind_param("isi", $EExpences, $EXDesc, $id);
//         }
//           $stmt->execute();
//           $stmt->close();
//         }
//       }
            
//       if (isset($_POST['EDid'])){
//         $EDid = $_POST['EDid'];
//         $stmt = $sql_con->prepare("DELETE FROM expences_tracker WHERE exp_id = ?");
//         $stmt->bind_param("i", $EDid);
//         $stmt->execute();
//         $stmt->close();
//       }
            




?>