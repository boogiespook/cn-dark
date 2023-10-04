<?php

$pg_host = getenv('PG_HOST');
$pg_db = getenv('PG_DATABASE');
$pg_user = getenv('PG_USER');
$pg_passwd = getenv('PG_PASSWORD');

$db_connection = pg_connect("host=$pg_host port=5432  dbname=$pg_db user=$pg_user password=$pg_passwd");

foreach($_REQUEST as $key => $val) {
if ($val == "1") {
	$flag_id = "2";
} else {
	$flag_id = "1";
}

$explode = explode("-",$key);
$capablity = $explode[1]; 
$qq = "UPDATE capability set flag_id = $flag_id  where id = $capablity"; 
$result = pg_query($db_connection, $qq);
}

header("Location: index.php");



?>
