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

$table = htmlspecialchars($_REQUEST['table'], ENT_QUOTES, 'UTF-8');
$idColumn = htmlspecialchars($_REQUEST['idColumn'], ENT_QUOTES, 'UTF-8');
$id = htmlspecialchars($_REQUEST['id'], ENT_QUOTES, 'UTF-8');

## If it's a domain deletion, also delete all child capabilities
if($table == "domain") {

## Delete all child capabilities for that specific domain
$deleteCapabilities = "delete from capability where domain_id = '" . $id . "'";
$capabilityResult = pg_query($db_connection, $deleteCapabilities);
}


$qq = "delete from $table WHERE $idColumn = '" . $id . "'";
$result = pg_query($db_connection, $qq);

header("Location: index.php");



?>
