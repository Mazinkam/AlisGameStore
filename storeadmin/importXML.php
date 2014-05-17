<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors','On');
if (!isset($_SESSION["manager"])) {
    header("location: admin_login.php"); 
    exit();
	
}
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

echo "GOT TO PAGE </br>";
if(isset($_FILES['file']) )
	{
		$xml = simplexml_load_file($_FILES['file']['tmp_name']);
		$dom = new DOMDocument;
		$dom->loadXML($xml->asXML());
		$sql_insert = "INSERT INTO products(product_name, price,details,category,subcategory,date_added) VALUES (?,?,?,?,?,NOW())"; 
		$sql_query_insert = $DBH->prepare($sql_insert); //saves againt injections
		
		
		$sql_update = "UPDATE products SET product_name=?, price=?,details=?,category=?,subcategory=?,date_added=NOW() WHERE id=?"; 
		$sql_query_update = $DBH->prepare($sql_update); //saves againt injections
		
	/*	
		if(!$dom->schemaValidate("productschema.xsd"))
		{
			echo "XML document not valid!\n";
		}
		else
		{*/
			echo "XML load</br>";
			foreach($xml->children() as $child)
			{
				if(!isset($child->id) && $child->id == 0){
					echo "inserted child</br>";
					echo "$child->productname</br>";
					echo "$child->price</br>";
					echo "$child->details</br>";
					echo "$child->category</br>";
					echo "$child->subcategory</br>";
					$sql_query_insert->execute(array($child->productname,$child->price,$child->details,$child->category,$child->subcategory));
				}else{
					echo "updated child</br>";
					echo "$child->id</br>";
					echo "$child->productname</br>";
					echo "$child->price</br>";
					echo "$child->details</br>";
					echo "$child->category</br>";
					echo "$child->subcategory</br>";
					echo "$child->dateadded</br>";
					$sql_query_update->execute(array($child->productname,$child->price,$child->details,$child->category,$child->subcategory,$child->id));
	
				}
				echo "Done !!!!!!!!!";
			}
			echo "done!\n";
		//}
}

?>