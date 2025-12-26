
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Smart_wallet";

$sql_con = new mysqli($servername, $username, $password, $dbname);



session_start();

if ($sql_con->connect_error) {
    die("Connection failed: " . $sql_con->connect_error);
}

$_SESSION = [];
session_destroy();

header("Location: index.php");
exit();


?>
