<?php 
session_start();
if (isset($_SESSION["manager"])) {
    header("location: index.php"); 
    exit();
}
?>
<?php 
// Parse the log in form if the user has filled it out and pressed "Log In"
if (isset($_POST["username"]) && isset($_POST["password"])) {

    $manager = preg_replace('#[^A-Za-z0-9]#i', '', $_POST["username"]); // filter everything but numbers and letters
    $password = preg_replace('#[^A-Za-z0-9]#i', '', $_POST["password"]); // filter everything but numbers and letters
    // Connect to the MySQL database  
    include "../storescripts/connect_to_mysql.php"; 
    $sql_get_admin = "SELECT id FROM admin WHERE username=? AND password=? LIMIT 1"; // query the person
	$sql_query = $DBH->prepare($sql_get_admin); //saves againt injections
	$sql_query->execute(array($manager, $password));
    // ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
    $user = $sql_query->fetchColumn(); // returns the desired row
    if ($user) { // evaluate the count
		 $_SESSION["id"] = $user['id'];
		 $_SESSION["manager"] = $manager;
		 $_SESSION["password"] = $password;
		 header("location: index.php");
         exit();
    } else {
		echo 'That information is incorrect, try again <a href="index.php">Click Here</a>';
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
          <h3>Manager login page</h3>
        <h5 class="subheader">
		  If you aren't a manager, please go to the customer login page.</h5>
        </div>

    </div><!-- End Side Bar -->
	 <div class="large-6 columns ">
	<div class="hide-for-small panel" >
       <form id="form1" name="form1" method="post" action="admin_login.php">
        <label>Username:</label>
          <input name="username" type="text" id="username"  placeholder="Username" size="10" />
        <br />
        Password:<br />
       <input name="password" placeholder="password"  type="password" id="password" size="10" />
       <br />

      <input type="submit" class="button" name="button" id="button" value="Log In" />
      </form>
   </div>
    </div>
   </div>
   
  <?php include_once("../template_footer.php");?>

    
  </div>

    <script src="../js/jquery.js"></script>
    <script src="../js/foundation.min.js"></script>
    <script>
      $(document).foundation();

      var doc = document.documentElement;
      doc.setAttribute('data-useragent', navigator.userAgent);
    </script>
</div>
</body>
</html>
  