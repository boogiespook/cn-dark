<?php

$pg_host = getenv('PG_HOST');
$pg_db = getenv('PG_DATABASE');
$pg_user = getenv('PG_USER');
$pg_passwd = getenv('PG_PASSWORD');

$db_connection = pg_connect("host=$pg_host port=5432  dbname=$pg_db user=$pg_user password=$pg_passwd");
$qq = "insert into integration_methods (integration_method_name) values ('" . $_REQUEST['integration_method'] . "')";

$result = pg_query($db_connection, $qq);

header("Location: index.php");



?>
