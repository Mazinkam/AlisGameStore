<?php 
session_start();
if (!isset($_SESSION["manager"])) {
    header("location: admin_login.php"); 
    exit();
}
header("Content-type: text/xml");
header("Content-Disposition:attachment;filename=prodcuts.xml");
echo "<?xml version='1.0' encoding='utf-8'?>\n";
// Be sure to check that this manager SESSION value is in fact in the database
$managerID = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
$manager = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["manager"]); // filter everything but numbers and letters
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
// Run mySQL query to be sure that this person is an admin and that their password session var equals the database information

include "../storescripts/connect_to_mysql.php"; 
    $sql_get_admin = "SELECT * FROM admin WHERE id=? AND username=? AND password=? LIMIT 1"; 
	$sql_query = $DBH->prepare($sql_get_admin); //saves againt injections
	$sql_query->execute(array($managerID,$manager,$password));
    // ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
    $user = $sql_query->fetchColumn(); // returns the desired row
if (!$user  ) { // evaluate the count
	 echo "Your login session data is not on record in the database.";
     exit();
}



    $sql_get_products = "SELECT * FROM products"; 
	$sql_query = $DBH->prepare($sql_get_products); //saves againt injections
	$sql_query->execute();
    // ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
    //$fetched_array = $sql_query->fetch(); // returns the desired row

echo "<products>\n";
while($r = $sql_query->fetch()){
  echo "\t<product>\n";
  echo "\t\t<id>".$r['id']."</id>\n";  
  echo "\t\t<product_name>".$r['product_name']."</product_name>\n";  
  echo "\t\t<price>".$r['price']."</price>\n";
  echo "\t\t<details>".$r['details']."</details>\n";  
  echo "\t\t<category>".$r['category']."</category>\n";    
  echo "\t\t<subcategory>".$r['subcategory']."</subcategory>\n";
  echo "\t\t<date_added>".$r['date_added']."</date_added>\n";      
  echo "\t</product>\n";  
}
echo "</products>";
//$sxe = new SimpleXMLElement($xml);
echo $xml;

?>