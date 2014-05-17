<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php 
// Run a select query to get my letest 6 items
// Connect to the MySQL database  
include "storescripts/connect_to_mysql.php"; 
$dynamicList_pc = "";

$dynamicList_xbox = "";
$sql_pc = "SELECT * FROM products WHERE subcategory='PC' ORDER BY date_added DESC LIMIT 3";
$sql_query_pc = $DBH->prepare($sql_pc);
$sql_query_pc->execute();
$results_pc = $sql_query_pc->fetchAll(PDO::FETCH_ASSOC);  // fetches everything in products 

if ($results_pc > 0) {
	foreach($results_pc as $row){ 
      		        $id = $row["id"];
			 $product_name = $row["product_name"];
			 $price = $row["price"];
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
			 $dynamicList_pc .= '
		  <div class="large-4 small-6 columns">
            <img class="framed_picture" src="inventory_images/' . $id . '.jpg" alt="' . $product_name . '" >

            <div class="panel">
              <h5>'. $product_name .'</h5>

              <h6 class="subheader">' . $price . '&euro;</h6>
			  <a class="button success tiny" href="product.php?id=' . $id . '">View Product Details</a>
            </div>
          </div>';
    }
} else {
	$dynamicList_pc = "We have no products listed in our store yet";
}

$dynamicList_ps3 = "";
$sql_ps3 = "SELECT * FROM products WHERE subcategory='PS3' ORDER BY date_added DESC LIMIT 3";
$sql_query_ps3 = $DBH->prepare($sql_ps3);
$sql_query_ps3->execute();
$resulsts_ps3 = $sql_query_ps3->fetchAll(PDO::FETCH_ASSOC);  // fetches everything in products 

if ($resulsts_ps3 > 0) {
	foreach($resulsts_ps3 as $row){ 
      		        $id = $row["id"];
			 $product_name = $row["product_name"];
			 $price = $row["price"];
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
			 $dynamicList_ps3 .= '
		  <div class="large-4 small-6 columns">
            <img class="framed_picture" src="inventory_images/' . $id . '.jpg" alt="' . $product_name . '" >

            <div class="panel">
              <h5>'. $product_name .'</h5>

              <h6 class="subheader">' . $price . '&euro;</h6>
			  <a class="button success tiny" href="product.php?id=' . $id . '">View Product Details</a>
            </div>
          </div>';
    }
} else {
	$dynamicList_ps3 = "We have no products listed in our store yet";
}

$dynamicList_xbox360 = "";
$sql_xbox360  = "SELECT * FROM products WHERE subcategory='Xbox360' ORDER BY date_added DESC LIMIT 3";
$sql_query_xbox360  = $DBH->prepare($sql_xbox360);
$sql_query_xbox360 ->execute();
$resulsts_xbox360  = $sql_query_xbox360->fetchAll(PDO::FETCH_ASSOC);  // fetches everything in products 

if ($resulsts_xbox360 > 0) {
	foreach($resulsts_xbox360 as $row){ 
      		        $id = $row["id"];
			 $product_name = $row["product_name"];
			 $price = $row["price"];
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
			 $dynamicList_xbox360 .= '
		  <div class="large-4 small-6 columns">
            <img class="framed_picture" src="inventory_images/' . $id . '.jpg" alt="' . $product_name . '" >

            <div class="panel">
              <h5>'. $product_name .'</h5>

              <h6 class="subheader">' . $price . '&euro;</h6>
			  <a class="button success tiny" href="product.php?id=' . $id . '">View Product Details</a>
            </div>
          </div>';
    }
} else {
	$dynamicList_xbox360 = "We have no products listed in our store yet";
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
  	<div class="row">
      <!-- Side Bar -->

    <div class="large-4 small-12 columns">
        <div class="hide-for-small panel">
          <h3>Ali's Game Store</h3>
        <h5 class="subheader">
		  Here at ali's we dedicate our lives to please you with our services. Check out the latest games that got added to our site!</h5>
        </div>

		<?php 
		if ( isset($_SESSION['email']) || isset($_SESSION['manager'])){ 
			echo "<a href=\"http://users.metropolia.fi/~aliab/webstore/cart.php\">
				<div class=\"panel callout radius\">
				  <h6>Your Cart</h6>
				</div></a>";
			}
		?>
      </div><!-- End Side Bar -->
      <!-- Thumbnails -->
	<div class="large-8 columns">
	<div class="row">

    <h3>Latest PC:</h3>
      <p><?php echo $dynamicList_pc; ?>
	  <h3>Latest PS3:</h3>
	  <p><?php echo $dynamicList_ps3; ?>
	  <h3>Latest Xbox360:</h3>
	  <p><?php echo $dynamicList_xbox360; ?>
	  
    </div>
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
</body>
</html>