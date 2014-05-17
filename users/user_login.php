<?php 
session_start();
if (isset($_SESSION["email"])) {
    header("location: ../index.php"); 
    exit();
}
?>
<?php 
// Parse the log in form if the user has filled it out and pressed "Log In"
if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = preg_replace('#[^A-Za-z0-9@.]#i', '', $_POST["email"]); // filter everything but numbers and letters
    $password = preg_replace('#[^A-Za-z0-9]#i', '', $_POST["password"]); // filter everything but numbers and letters
    // Connect to the MySQL database  
    include "../storescripts/connect_to_mysql.php"; 
    $sql_get_users = "SELECT id FROM users WHERE email=? AND password=? LIMIT 1"; // query the person
	$sql_query = $DBH->prepare($sql_get_users); //saves againt injections
	$sql_query->execute(array($email, $password));
    // ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
    $user = $sql_query->fetchColumn(); // returns the desired row
	var_export($email);
	var_export($password);
	var_export($user);
    if ($user) { // evaluate the count
		 $_SESSION["id"] = $user['id'];
		 $_SESSION["email"] = $email;
		 $_SESSION["password"] = $password;
		 header("location: ../index.php");
         exit();
    } else {
		echo 'That information is incorrect, try again <a href="./user_login.php">Click Here</a>';
		header( "Refresh:2;", true, 303);
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
          <h3>User login page</h3>
        <h5 class="subheader">
		  Please login, so you can purchase items.</h5>
        </div>

    </div><!-- End Side Bar -->
	 <div class="large-6 columns ">
	<div class="hide-for-small panel" >
      <form id="form1" name="form1" method="post" action="user_login.php">
        <label>Email:</label>
          <input name="email" type="text" id="email"  placeholder="lol@lol.lol" size="10" />
        <br />
        Password:<br />
       <input name="password" placeholder="lololololol"  type="password" id="password" size="10" />
       <br />

      <input type="submit" class="button" name="button" id="button" value="Log In" />
      </form>
      <p><a href="./user_register.php" class="button success" >Not a member? Join register here!</a> </p>

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