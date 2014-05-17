<?php

require"connect_to_mysql.php";

$sql = "CREATE TABLE DROP IF EXISTS products(
            id int(11) NOT NULL auto_increment, 
            product_name varchar(255) NOT NULL,
            price varchar(16) NOT NULL,
			details text NOT NULL,
			category varchar(64) NOT NULL,
			subcategory varchar(64) NOT NULL,
            date_added date NOT NULL,
            PRIMARY KEY(id),
            UNIQUE KEY product_name(product_name)
            )";

$sq = $DBH->query($sql);

if($sq){
    echo"products table created";
}else{
    echo"a wild error has appeared";
    }
?>