<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (!isset($_SESSION["email"]) && !isset($_SESSION["email"])) {
    header("location: users/user_login.php"); 
    exit();
}

// Connect to the MySQL database  
include "storescripts/connect_to_mysql.php";


//      rendering cart 
$cartOutput = "";
$cartTotal = "";
$pp_checkout_btn = '';
$product_id_array = '';
if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
    $cartOutput = "<tr><td>Your shopping cart is empty</td></tr>";
} else {
	// Start PayPal Checkout Button
	$i = 0; 
	 $price = 0;
	 $product_name = "";
	 $details ="";
    foreach ($_SESSION["cart_array"] as $each_item) { 
		$item_id = $each_item['item_id'];
		$sql_get_product = "SELECT * FROM products WHERE id='$item_id' LIMIT 1";
		$sql_query = $DBH->prepare($sql_get_product); //saves againt injections
		$sql_query->execute();
		$results = $sql_query->fetchAll(PDO::FETCH_ASSOC); 
		foreach($results as $row){ 
			$product_name = $row["product_name"];
			$price = $row["price"];
			$details = $row["details"];
		}
		$pricetotal = $price * $each_item['quantity'];
		$cartTotal = $pricetotal + $cartTotal;
	
        $pricetotal = money_format("%10.2n", $pricetotal);

		// Create the product array variable
		$product_id_array .= "$item_id-".$each_item['quantity'].","; 
		// Dynamic table row assembly
		$cartOutput .= "<tr>";
		$cartOutput .= '<td><a href="product.php?id=' . $item_id . '">' . $product_name . '</a><br /><img src="inventory_images/' . $item_id . '.jpg" alt="' . $product_name. '" width="40" height="52" border="1" /></td>';
		$cartOutput .= '<td>' . $details . '</td>';
		$cartOutput .= '<td>' . $price . '&euro;</td>';
		$cartOutput .= '<td><form action="cart.php" method="post">
		<input name="quantity" type="text" value="' . $each_item['quantity'] . '" size="1" maxlength="2" />
		<input name="adjustBtn' . $item_id . '" type="submit" class="button" value="change" />
		<input name="item_to_adjust" type="hidden" value="' . $item_id . '" />
		</form></td>';
		//$cartOutput .= '<td>' . $each_item['quantity'] . '</td>';
		$cartOutput .= '<td>' . $pricetotal . '&euro;</td>';
		$cartOutput .= '<td><form action="cart.php" method="post"><input name="deleteBtn' . $item_id . '" type="submit" class="button" value="X" /><input name="index_to_remove" type="hidden" value="' . $i . '" /></form></td>';
		$cartOutput .= '</tr>';
		$i++; 
    } 
	setlocale(LC_MONETARY, 'nl_NL');
    $cartTotal = money_format('%(#1n', $cartTotal);
	$cartTotal = "<div style='font-size:18px; margin-top:12px;'>Cart Total : ".$cartTotal." &euro;</div>";

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
          <h3>Your cart</h3>
        <h5 class="subheader">
		 Here is a list of the items you have added to your cart!</h5>
        </div>
      </div><!-- End Side Bar -->
      <!-- Thumbnails -->
	<div class="large-8 columns">


   
    <table>
	<thead>
      <tr>
        <th class= "extra_witdh"><strong>Product</strong></th>
        <th ><strong>Product Description</strong></th>
        <th ><strong>Unit Price</strong></th>
        <th ><strong>Quantity</strong></th>
        <th ><strong>Total</strong></th>
        <th ><strong>Remove</strong></th>
      </tr>
	  </thead>
	  <tbody>
     <?php echo $cartOutput; ?>
	 </tbody>
    </table>
    <?php echo $cartTotal; ?>
    <br />

         <form id="form1" name="form1" method="post" action="sent.php">
		 <div class="large-8 hide-for-small panel">
		Address: <br />
	    <input name="address" type="text" id="address" size="40" /><br />
		 Zip code: <br />
	    <input name="zipcode" type="text" id="zipcode" size="40" /><br />
       City:<br />
          <input name="city" type="text" id="city" size="40" /><br />
	Phone number: <br />
        <input name="phonenum" type="text" id="phonenum" size="40" />
       <br />
         <input type="submit" class="button success" name="button" id="button" value="Confirm order" />
    </div>
	</form>
  <?php include_once("template_footer.php");?>

    
  
</div>
  </div>
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

