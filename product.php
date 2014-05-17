<?php 
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php 
// Check to see the URL variable is set and that it exists in the database
if (isset($_GET['id'])) {
	// Connect to the MySQL database  
    include "storescripts/connect_to_mysql.php"; 
	$id = preg_replace('#[^0-9]#i', '', $_GET['id']); 
	// Use this var to check to see if this ID exists, if yes then get the product 
	// details, if no then exit this script and give message why
	$sql_get_product = "SELECT * FROM products WHERE id='$id' LIMIT 1"; 
	$sql_query = $DBH->prepare($sql_get_product); //saves againt injections
	$sql_query->execute();
    $products = $sql_query->fetchAll(PDO::FETCH_ASSOC);  // feteches everything
	
    if ($products  >0) {
		foreach($products as $row){ 
		if($row['id'] == $id){
		 $product_name = $row["product_name"];
		 $price = $row["price"];
		 $details = $row["details"];
		 $category = $row["category"];
		 $subcategory = $row["subcategory"];
		 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));	
		 }
	}
	} else {
		echo "That item does not exist.";
	    exit();
	}
}

?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ali's Game Store | Welcome</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/modernizr.js"></script>
	<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />

</head>

<body>
<div class="row">
<div class="large-12 columns">
  <?php include_once("template_header.php");?>
  
	<div class="large-8 columns">
	    <div class="large-4 small-12 columns">
		<div class="hide-for-small panel">
         <a href="inventory_images/<?php echo $id; ?>.jpg"><img src="inventory_images/<?php echo $id; ?>.jpg" width="142" height="188" alt="<?php echo $product_name; ?>" /><br />
       </a>
      </div></div>
	  <div class="large-8 small-12 columns">
	  <div class="hide-for-small panel">
      <h4>Chosen game:</h4>
	  <?php echo $product_name; ?><br/><br/>
	  <h4>Game price:</h4>
      <?php echo $price."&euro;"; ?><br/><br/>
        <h4>Platform:</h4>
        <?php echo "$subcategory $category"; ?><br/><br/>
	<h4>Game Details:</h4>
        <?php echo $details; ?> <br /><br /><br />
		
      <form id="form1" name="form1" method="post" action="cart.php">
        <input type="hidden" name="pid" id="pid" value="<?php echo $id; ?>" />
        <input type="submit" class="button [secondary success alert]" name="button" id="button" value="Add to Shopping Cart" />
      </form>
    </div>
  </div>     
   
  <?php include_once("template_footer.php");?>

    
  </div>
</div>
    <script src="js/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();

      var doc = document.documentElement;
      doc.setAttribute('data-useragent', navigator.userAgent);
    </script>

  <?php include_once("template_footer.php");?>
</div>
</body>
</html>