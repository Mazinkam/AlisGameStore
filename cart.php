<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');



// Connect to the MySQL database  
include "storescripts/connect_to_mysql.php";

// adding to cart
if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
	$wasFound = false;
	$i = 0;
	// If the cart session variable is not set or cart array is empty
	if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) { 
	    // RUN IF THE CART IS EMPTY OR NOT SET
		$_SESSION["cart_array"] = array(0 => array("item_id" => $pid, "quantity" => 1));
	} else {
		foreach ($_SESSION["cart_array"] as $each_item) { 
		      $i++;
		      while (list($key, $value) = each($each_item)) {
				  if ($key == "item_id" && $value == $pid) {
				
					  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $pid, "quantity" => $each_item['quantity'] + 1)));
					  $wasFound = true;
				  } 
		      } 
	       } 
		   if ($wasFound == false) {
			   array_push($_SESSION["cart_array"], array("item_id" => $pid, "quantity" => 1));
		   }
	}
	header("location: cart.php"); 
    exit();
}

//       empty shopping cart
if (isset($_GET['cmd']) && $_GET['cmd'] == "emptycart") {
    unset($_SESSION["cart_array"]);
}

//       adjust item quantity
if (isset($_POST['item_to_adjust']) && $_POST['item_to_adjust'] != "") {
	$item_to_adjust = $_POST['item_to_adjust'];
	$quantity = $_POST['quantity'];
	$quantity = preg_replace('#[^0-9]#i', '', $quantity); // filter everything but numbers
	if ($quantity >= 100) { $quantity = 99; } // sereting max to 99
	if ($quantity < 1) { $quantity = 1; }
	if ($quantity == "") { $quantity = 1; }
	$i = 0;
	foreach ($_SESSION["cart_array"] as $each_item) { 
		      $i++;
		      while (list($key, $value) = each($each_item)) {
				  if ($key == "item_id" && $value == $item_to_adjust) {
					  // That item is in cart already so let's adjust its quantity using array_splice()
					  //small bug, with this quantity can potentially go over 99..
					  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $item_to_adjust, "quantity" => $quantity)));
				  } 
		      } 
	} 
}

//      removing item from cart
if (isset($_POST['index_to_remove']) && $_POST['index_to_remove'] != "") {
    // Access the array and run code to remove that array index
 	$key_to_remove = $_POST['index_to_remove'];
	if (count($_SESSION["cart_array"]) <= 1) {
		unset($_SESSION["cart_array"]);
	} else {
		unset($_SESSION["cart_array"]["$key_to_remove"]);
		sort($_SESSION["cart_array"]);
	}
}


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
<br />
<a class="button [secondary success alert]" href="checkout.php">Checkout</a>
    <br />
    <br />
    <a  class="button alert" href="cart.php?cmd=emptycart">Click Here to Empty Your Shopping Cart</a>
   <br />

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