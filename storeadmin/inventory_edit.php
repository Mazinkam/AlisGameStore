<?php 
session_start();
if (!isset($_SESSION["manager"])) {
    header("location: admin_login.php"); 
    exit();
}
// Be sure to check that this manager SESSION value is in fact in the database
$managerID = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
$manager = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["manager"]); // filter everything but numbers and letters
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
// Run mySQL query to be sure that this person is an admin and that their password session var equals the database information
// Connect to the MySQL database  
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
?>
<?php 
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php 
// Parse the form data and add inventory item to the system
if (isset($_POST['product_name'])) {
	try {
	$pid = $_POST['thisID'];
    $product_name = $_POST['product_name'];
	$price = $_POST['price'];
	$category = $_POST['category'];
	$subcategory = $_POST['subcategory'];
	$details = $_POST['details'];

	$sql_check_product = "UPDATE products SET product_name='$product_name',
	price='$price', details='$details', category='$category', 
	subcategory='$subcategory' WHERE id='$pid'";
	
	$sql_query = $DBH->prepare($sql_check_product); //saves againt injections
	$sql_query->execute();
	
	if ($_FILES['fileField']['tmp_name'] != "") {
	    // Place image in the folder 
	    $newname = "$pid.jpg";
	    move_uploaded_file($_FILES['fileField']['tmp_name'], "../inventory_images/$newname");
	}
	header("location: inventory_list.php"); 
    exit();
} catch (PDOException $e) {
    print $e->getMessage ();
}

print $status; // it returns a null value, and no errors are reported
}
?>
<?php 
// Gather this product's full information for inserting automatically into the edit form below on page
if (isset($_GET['pid'])) {
	try {
	$targetID = $_GET['pid'];
	$sql_get_product = "SELECT * FROM products WHERE id='$targetID' LIMIT 1"; 
	$sql_query = $DBH->prepare($sql_get_product); //saves againt injections
	$sql_query->execute();
    // ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
	$results = $sql_query->fetchAll(PDO::FETCH_ASSOC); 
	
    if ($results >0) {
	foreach($results as $row){ 
	  	if($row['id'] == $targetID){
	  	 $product_name2 = $row["product_name"];
	  	 $price2 = $row["price"];
	  	 $category2 = $row["category"];
	  	 $subcategory2 = $row["subcategory"];
	  	 $details2 = $row["details"];
	  	 $date_added2 = strftime("%b %d, %Y", strtotime($row["date_added"]));
	}
	}
		 
    } else {
	    echo "Sorry dude that crap dont exist.";
		exit();
    }
	
	} catch (PDOException $e) {
    print $e->getMessage ();
}
}
?>
<?php 
// This php block grabs the whole list for viewing
$product_list = "";
$sql = "SELECT * FROM products ORDER BY date_added DESC";
$sql_query = $DBH->prepare($sql);
$sql_query->execute();
$results = $sql_query->fetchAll(PDO::FETCH_ASSOC);  // fetches everything in products 
if ($results > 0) {
	foreach($results as $row){ 
             $id = $row["id"];
			 $product_name = $row["product_name"];
			 $price = $row["price"];
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
			 			 $product_list .= "Product ID: $id - <strong>$product_name</strong> - $price &#8364; - <em>Added $date_added</em> &nbsp; &nbsp; &nbsp; <a class=\"button tiny \" href='inventory_edit.php?pid=$id'>edit</a> &bull; <a class=\"button alert tiny \" href='inventory_list.php?deleteid=$id'>delete</a><br />";
    }
} else {
	$product_list = "You have no products listed in your store yet";
}
?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ali's Game Store | Welcome</title>
    <link rel="stylesheet" href="../css/foundation.css" />
    <script src="../js/modernizr.js"></script>
	<link rel="stylesheet" href="../style/style.css" type="text/css" media="screen" />

</head>
<body>

<div class="row">
<div class="large-12 columns">
  <?php include_once("../template_header.php");?>
  
    <div align="right" style="margin-right:32px;">
	<a class="button success tiny"  href="inventory_list.php#inventoryForm">+ Add New Inventory Item</a></div>
<div align="left" style="margin-left:24px;">
      <h2>Inventory list</h2>
      <?php echo $product_list; ?>
    </div>
    <hr />
    <a name="inventoryForm" id="inventoryForm"></a>
    <h3>
    &darr; Add New Inventory Item Form &darr;
    </h3>
    <form action="inventory_edit.php" enctype="multipart/form-data" name="myForm" id="myform" method="post">
    <table width="90%" border="0" cellspacing="0" cellpadding="6">
      <tr>
        <td width="20%" align="right">Product Name</td>
        <td width="80%"><label>
          <input name="product_name" type="text" id="product_name" size="64" value="<?php echo $product_name2; ?>" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Product Price</td>
        <td><label>
          <input name="price" type="text" id="price" size="12" value="<?php echo $price2; ?>" />&euro;
        </label></td>
      </tr>
      <tr>
        <td align="right">Category</td>
        <td><label>
          <select name="category" id="category">
          <option value="Games">Games</option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td align="right">Subcategory</td>
        <td><select name="subcategory" id="subcategory">
          <option value="<?php echo $subcategory2; ?>"><?php echo $subcategory2; ?></option>
          <option value="PS3">PS3</option>
          <option value="PC">PC</option>
          <option value="Xbox360">XBOX360</option>
          </select></td>
      </tr>
      <tr>
        <td align="right">Product Details</td>
        <td><label>
          <textarea name="details" id="details" cols="64" rows="5"><?php echo $details2; ?></textarea>
        </label></td>
      </tr>
      <tr>
        <td align="right">Product Image</td>
        <td><label>
          <input type="file" class="button" name="fileField" id="fileField" />
        </label></td>
      </tr>      
      <tr>
        <td>&nbsp;</td>
        <td><label>
          <input name="thisID" type="hidden" value="<?php echo $targetID; ?>" />
          <input type="submit"  class="button" name="button" id="button" value="Make Changes" />
        </label></td>
      </tr>
    </table>
    </form>
    <br />
  <br />
  </div>
  <?php include_once("../template_footer.php");?>

    
  </div>
</div>
    <script src="../js/jquery.js"></script>
    <script src="../js/foundation.min.js"></script>
    <script>
      $(document).foundation();

      var doc = document.documentElement;
      doc.setAttribute('data-useragent', navigator.userAgent);
    </script>
</body>
</html>
