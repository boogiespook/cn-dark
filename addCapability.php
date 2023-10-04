<?php

$pg_host = getenv('PG_HOST');
$pg_db = getenv('PG_DATABASE');
$pg_user = getenv('PG_USER');
$pg_passwd = getenv('PG_PASSWORD');
$capability = htmlspecialchars($_REQUEST['capability'], ENT_QUOTES, 'UTF-8');
$domain = htmlspecialchars($_REQUEST['domainId'], ENT_QUOTES, 'UTF-8');

$db_connection = pg_connect("host=$pg_host port=5432  dbname=$pg_db user=$pg_user password=$pg_passwd");

$qq = "INSERT into capability (description,domain_id,flag_id) VALUES ('" . $capability . "','" . $domain . "','1')";

$result = pg_query($db_connection, $qq);

header("Location: index.php");



?>
