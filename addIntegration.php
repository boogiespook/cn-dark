<?php

$pg_host = getenv('PG_HOST');
$pg_db = getenv('PG_DATABASE');
$pg_user = getenv('PG_USER');
$pg_passwd = getenv('PG_PASSWORD');

$db_connection = pg_connect("host=$pg_host port=5432  dbname=$pg_db user=$pg_user password=$pg_passwd");
#phpinfo();
# Get all the results (not currently doing any validation)

#$qq = "INSERT into integrations (integration_name, capability_id, url, \"user\", password, token, success_criteria) VALUES (
#'" . $_REQUEST['integration-name'] . "', '" . $_REQUEST['capability-id'] . "', '" . $_REQUEST['endpoint-url'] . "', '" . $_REQUEST['username'] . "', '" . $_REQUEST['password'] . "', '" . $_REQUEST['token'] . "', '" . $_REQUEST['success-criteria'] . "')";

$qq = "INSERT into integrations (integration_name, capability_id, url, \"user\", password, token, success_criteria, integration_method_id) VALUES ('" . $_REQUEST['integration-name'] . "', '" . $_REQUEST['capability-id'] . "', '" . $_REQUEST['endpoint-url'] . "', '" . $_REQUEST['username'] . "', '" . $_REQUEST['password'] . "', '" . $_REQUEST['token'] . "', '" . $_REQUEST['success-criteria'] . "', '" . $_REQUEST['integration_method_id'] . "')";

$result = pg_query($db_connection, $qq);


header("Location: index.php");



?>
