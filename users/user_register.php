<?php 
session_start();
if (isset($_SESSION["memeberId"])) {
    header("location: ../index.php"); 
    exit();
}
?>
<?php 
include "../storescripts/connect_to_mysql.php"; 
require_once "Validate.php";
if (isset($_POST['email'])&& isset($_POST["password"])&& isset($_POST["firstname"])&& isset($_POST["lastname"])) {
		try {

		$id = 0;
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		
		$validate = new Validate();
		// Add this user into the database now
		$validate->email($email, array("check_domain" => 1));
		if($validate->email($email)){
		$sql_add_to_db = "INSERT INTO users (id, firstname, lastname, email, password) 
			VALUES(?,?,?,?,?)";	
		$sql_query = $DBH->prepare($sql_add_to_db); //saves againt injections
		$sql_query->execute(array($id,$firstname,$lastname,$email,$password));
		echo 'Welcome '.$firstname.' to the greatest store ever created!';
		}
		else{
			echo $email . ' email cannot be verified!';
		}
			
		
		header( "Refresh:2;", true, 303);
		exit();
		
	} catch (PDOException $e) {
		print $e->getMessage ();
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
          <h3>User reigster page</h3>
        <h5 class="subheader">
		  Please fill in all the fields.</h5>
        </div>

    </div><!-- End Side Bar -->
	 <div class="large-6 columns ">
	<div class="hide-for-small panel" >
      <form id="form1" name="form1" method="post" action="user_register.php">
		First Name<br />
	    <input name="firstname"  placeholder="Firstname"  type="text" id="firstname" size="40" /><br />
		 Last Name: <br />
	    <input name="lastname"  placeholder="Lastname"  type="text" id="lastname" size="40" /><br />
       Email:<br />
          <input name="email"  placeholder="Email"  type="text" id="email" size="40" /><br />
	Password: <br />
        <input name="password"  placeholder="Password"  type="password" id="password" size="40" />
       <br />
         <input type="submit" class="button" name="button" id="button" value="Register" />

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
	