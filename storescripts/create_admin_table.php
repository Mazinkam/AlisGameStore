<?php

require"connect_to_mysql.php";

$sql = "CREATE TABLE IF NOT EXISTS admin(
            id int(11) NOT NULL auto_increment, 
            username varchar(255) NOT NULL,
            password varchar(255) NOT NULL,
            last_log_date date NOT NULL,
            PRIMARY KEY(id),
            UNIQUE KEY username(username)
            )";

$sq = $DBH->query($sql);

if($sq){
    echo"admin table created";
}else{
    echo"a wild error has appeared";
    }
?>