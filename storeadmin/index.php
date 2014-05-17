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
  	<div class="row">
      <!-- Side Bar -->

    <div class="large-4 small-12 columns">
        <div class="hide-for-small panel">
          <h3>Manager page</h3>
        <h5 class="subheader">
		 Welcome manager! Here you can manage items and import/export the products table from mysql as XML</h5>
        </div>
      </div><!-- End Side Bar -->
      <!-- Thumbnails -->
	<div class="large-8 columns">
	<div class="row">
	<form  action="inventory_list.php" >
	<fieldset> 
	<legend>Manage Inventory</legend>
		<input  class="button"  type="submit" value="Manage">
	</fieldset>
	</form>
	<form  action="exportXML.php" >
	<fieldset> 
	<legend>Export products table as xml file</legend>
		<input  class="button"  type="submit" value="Export">
	</fieldset>
	</form>
	<form name="import_form" action="importXML.php" method="POST"  enctype="multipart/form-data">
	<fieldset> 
	<legend>Import a products via xml file</legend>
		<input type="file" class="button postfix"  value="upload"  name="file">
		<input  class="button"  type="submit" value="Upload">
	</fieldset>
	</form>
    </div>
   </div>
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


