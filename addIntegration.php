<?php
class DotEnv
{
    /**
     * The directory where the .env file can be located.
     *
     * @var string
     */
    protected $path;


    public function __construct(string $path)
    {
        if(!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
        }
        $this->path = $path;
    }

    public function load() :void
    {
        if (!is_readable($this->path)) {
            throw new \RuntimeException(sprintf('%s file is not readable', $this->path));
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {

            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

(new DotEnv(__DIR__ . '/.env'))->load();

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
