<?php (isset($_GET['logout']) ? session_destroy()  : "");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
    <div class="row">
      <div class="large-12 columns">
        <nav class="top-bar" data-topbar>
          <ul class="title-area">
            <!-- Title Area -->

            <li class="name">
              <h1><a href="http://users.metropolia.fi/~aliab/webstore/index.php">Ali's Game Store</a></h1>
            </li>

            <li class="toggle-topbar menu-icon">
              <a href="#"><span>menu</span></a>
            </li>
          </ul>

          <section class="top-bar-section">
            <!-- Right Nav Section -->

            <ul class="right">
              <li class="divider"></li>
			  
			  <li>
                <a href="http://users.metropolia.fi/~aliab/webstore/index.php">Home</a>
              </li>
			  
              <li class="divider"></li>
			  
			  <li class="has-dropdown">
                    <a class="" href="#">Products</a>

                    <ul class="dropdown">
                      <li>
                        <a href="http://users.metropolia.fi/~aliab/webstore/product_list.php?ps3">PS3 Games</a>
                      </li>

                      <li>
                        <a href="http://users.metropolia.fi/~aliab/webstore/product_list.php?pc">PC Games</a>
                      </li>
					  

                      <li>
                        <a href="http://users.metropolia.fi/~aliab/webstore/product_list.php?xbox360">XBox360 Games</a>
                      </li>
                    </ul>
             </li>

             <li class="divider"></li>

			  <?php (isset($_GET['logout']) ? session_destroy()  : "");
			if ( !isset($_SESSION["email"]) && !isset($_SESSION["manager"]) ){ 
				echo "
				<li class=\"has-dropdown\">
                <a href=\"#\">Users</a>

                <ul class=\"dropdown\">
                  <li>
                    <a href=\"http://users.metropolia.fi/~aliab/webstore/users/user_login.php\">User login</a>
                  </li>
					<li class=\"divider\"></li>
                  <li>
                    <a href=\"http://users.metropolia.fi/~aliab/webstore/storeadmin/\">Manager Login</a>
                  </li>
                </ul>
              </li>";
			}
			if(isset($_SESSION['manager'])){
				echo "
				<li class=\"has-dropdown\">
                <a href=\"#\">Users</a>

                <ul class=\"dropdown\">
                  <li>
                    <a href=\"http://users.metropolia.fi/~aliab/webstore/storeadmin/\">Manager Panel</a>
                  </li>
					<li class=\"divider\"></li>
                  <li>
                    <a href=\"?logout\"> Logout</a>
                  </li>
                </ul>
              </li>";
			}
			
			if(isset($_SESSION['email'])){
				echo "
				<li class=\"has-dropdown\">
                <a href=\"#\">Users</a>

                <ul class=\"dropdown\">
                  <li>
                    <a href=\"?logout\"> Logout</a>
                  </li>
                </ul>
              </li>";
			}
			?>
            </ul>
			
          </section>
        </nav><!-- End Top Bar -->
      </div>
    </div><!-- End Navigation -->
