 <?php 
$host = "";
$user= ""; 
$pass= ""; 
$dbname = "";

try {    
  # MySQL with PDO_MYSQL  
  $DBH = new PDO("mysql:host=".$host.";dbname=".$dbname, $user, $pass);  
}  
catch(PDOException $e) {  
    die("VIRHE: " . $e->getMessage());
}   
$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$DBH->exec("SET NAMES utf8");
?>

