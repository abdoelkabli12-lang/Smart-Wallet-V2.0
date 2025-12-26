<?php
require 'email_send.php';
require 'database.php';

session_start();

class Login
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function userLogin(string $email, int $password): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return;
        }

        // Validate input
        if (empty($email) || empty($password)) {
            header("Location: index.php?error=login");
            exit;
        }

        // Fetch user
        $stmt = $this->db->prepare(
            "SELECT id, Password FROM users WHERE Email = :email"
        );
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            header("Location: index.php?error=user_not_found");
            exit;
        }
        $user_id = $user['id'];
        $hashedPasswordFromDb = $user['Password'];



        // Verify password
        if (!password_verify($password, $hashedPasswordFromDb)) {
            header("Location: index.php?error=wrong_password");
            exit;
        }

        // Store TEMP session (OTP pending)
        $_SESSION['pending_user'] = $email;
        $_SESSION['pending_user_id'] = $user_id;



        // Send OTP
        if (!sendOTP($email)) {
            header("Location: index.php?error=otp_failed");
            exit;
        }

        // Redirect to OTP verification
        header("Location: otp_verify.php");
        exit;
    }
// }


// class SignUp
// {
//     private mysqli $db;

//     public function __construct($db)
//     {
//         $this->db = $db;
//     }

    public function UserSignUp(string $name, string $email, string $password)
    {

        $hashedpass = password_hash($password, PASSWORD_DEFAULT);
        if ($_SERVER["REQUEST_METHOD" !== "POST"]) {
            return;
        }

        $stmt = $this->db->prepare("SELECT id FROM users WHERE Email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);


        if ($stmt->fetch() === true) {
            // Email already exists
            header("Location: index.php?error=email_exists");
            exit;
        }

        // 2️⃣ Insert new user
        $stmt = $this->db->prepare(
            "INSERT INTO users (UserName, Email, Password) VALUES (?, ?, ?)"
        );
        $stmt->execute([$name, $email, $hashedpass]);

        header("Location: index.php?success=signup");
        exit;
    }

    public function UserLogout($logout): void{
        $SESSION = $_SESSION;
        $SESSION = [];
        session_destroy();

        if($SESSION === []){
            header("Location: index.php");
            exit();
        }else{
            echo "Apah";
        }

        }
    
}


try {
    $db = new PDO(
        "mysql:host=localhost;dbname=Smart_Wallet;charset=utf8",
        "root",
        ""
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
$login = new Login($db);


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST['emailL'])){
        $login->userLogin($_POST['emailL'], $_POST['passwordL']);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST['email'])){
    $login->UserSignUp($_POST['username'], $_POST['email'], $_POST['password']);
        }
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
$login->UserLogout($_POST['logout']);
}
?>


<!-- //     if (!isset($_POST['otp']) || !isset($_SESSION['otp'])) {
        //         header("Location: otp_verify.php?error=otp_missing");
        //         exit;
        //     }
        //     if ((string) $otp !== (string) $_SESSION['otp']) {
        //         header("Location: otp_verify.php?error=otp_invalid");
        //         exit;
        //     }


        //     unset($_SESSION['otp']);


        //     $_SESSION['login_success'] = true;
        // 

        // header("Location: Home.php?success=login");
        // exit;



// $sql_con = new mysqli("localhost", "root", "", "Smart_Wallet");
// if ($sql_con->connect_error) {
//     die("Database connection failed");
// }

// if ($_SERVER["REQUEST_METHOD"] === "POST") {


//     if(!isset($_SESSION['user'])){



//     if (empty($_POST['emailL']) || empty($_POST['passwordL'])) {
//         header("Location: index.php?error=login");
//         exit;
//     }

//     $emailL   = trim($_POST['emailL']);
//     $password = $_POST['passwordL'];


// $stmt = $sql_con->prepare("SELECT id, Password FROM users WHERE Email = ?");
// $stmt->bind_param("s", $emailL);
// $stmt->execute();
// $stmt->bind_result($user_id, $hashedPasswordFromDb);

// if (!$stmt->fetch()) {
//     die("User not found");
// }

// $stmt->close();

// if (!password_verify($password, $hashedPasswordFromDb)) {
//     die("Wrong password");
// }

// // ✅ NOW user_id exists
// $_SESSION['user'] = $emailL;
// $_SESSION['user_id'] = $user_id;




//     if (!sendOTP($emailL)) {
//         die("Failed to send OTP");
//     }
    

//     header("Location: otp_verify.php");
//     exit;


//     if (!isset($_POST['otp']) || !isset($_SESSION['otp'])) {
//         header("Location: otp_verify.php?error=otp_missing");
//         exit;
//     }

//     if ((string) $_POST['otp'] !== (string) $_SESSION['otp']) {
//         header("Location: otp_verify.php?error=otp_invalid");
//         exit;
//     }


//     unset($_SESSION['otp']);


//     $_SESSION['login_success'] = true;
//         }

// // SEND OTP
//     header("Location: Home.php?success=login");
//     exit;
// } -->