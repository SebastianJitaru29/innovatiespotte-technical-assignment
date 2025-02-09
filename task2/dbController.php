<?php
class dbController {
    /**
     * The singleton instance.
     * @var dbController|null
     */
    private static $instance = null;

    /**
     * The PostgreSQL connection resource.
     * @var resource
     */
    private $connection;

    // Database configuration variables.
    private $dbhost;
    private $dbport;
    private $dbname;
    private $dbuser;
    private $dbpass;

    /**
     * Returns the singleton instance of dbController.
     *
     * @param string|null $envFilePath Path to the .env file.
     *                               Must be provided on first initialization.
     * @return dbController
     * @throws Exception If no .env path is provided on first call.
     */
    public static function getInstance($envFilePath = null) {
        if (self::$instance === null) {
            if ($envFilePath === null) {
                throw new Exception("You must provide an .env file path on first initialization.");
            }
            self::$instance = new static($envFilePath);
        }
        return self::$instance;
    }

    /**
     * Protected constructor prevents external instantiation.
     *
     * @param string $envFilePath Path to the .env file.
     */
    protected function __construct($envFilePath) {
        $this->loadEnv($envFilePath);
        $this->connect();
    }

    /**
     * Private clone method to prevent cloning.
     */
    private function __clone() { }

    /**
     * Private unserialize method to prevent unserializing. (made public but throws an exception to avoid warnings)
     */
    public function __wakeup() { 
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * Loads environment variables from a .env file.
     *
     * @param string $envFilePath
     */
    private function loadEnv($envFilePath) {
        if (!file_exists($envFilePath)) {
            die("Error: .env file not found.\n");
        }

        $env = file_get_contents($envFilePath);
        $lines = explode("\n", $env);
        foreach ($lines as $line) {
            $line = trim($line);
            // Skip empty lines and comments
            if ($line === "" || strpos($line, "#") === 0) {
                continue;
            }
            // Match key=value pairs
            if (preg_match("/([^=]+)=(.*)/", $line, $matches)) {
                $key = trim($matches[1]);
                $value = trim($matches[2]);
                // Remove surrounding quotes if they exist
                $value = trim($value, "\"'");
                putenv("$key=$value");
            }
        }

        // Retrieve environment variables
        $this->dbhost = getenv('DB_HOST') ?: 'localhost';
        $this->dbport = getenv('DB_PORT') ?: '5432';
        $this->dbname = getenv('POSTGRES_DB');
        $this->dbuser = getenv('POSTGRES_USER');
        $this->dbpass = getenv('POSTGRES_PASSWORD');

        // echo "DB_HOST: {$this->dbhost}\n";
        // echo "DB_PORT: {$this->dbport}\n";
        // echo "POSTGRES_DB: {$this->dbname}\n";
        // echo "POSTGRES_USER: {$this->dbuser}\n";
        // echo "POSTGRES_PASSWORD: {$this->dbpass}\n";

        if (!$this->dbname || !$this->dbuser || !$this->dbpass) {
            die("Database configuration is incomplete in .env file.\n");
        }
    }

    /**
     * Establishes a connection to the PostgreSQL database.
     */
    private function connect() {
        $connectionString = "host={$this->dbhost} port={$this->dbport} dbname={$this->dbname} user={$this->dbuser} password={$this->dbpass}";
        $this->connection = pg_connect($connectionString);
        if (!$this->connection) {
            die("Error: Unable to connect to the database.\n");
        }
        echo "Connected successfully to the database using pg_connect.\n\n";
    }

    /**
     * Executes a query using the active database connection.
     *
     * @param string $query The SQL query to execute.
     * @return resource The result resource on success.
     */
    public function executeQuery($query) {
        $result = pg_query($this->connection, $query);
        if (!$result) {
            die("Error executing query: " . pg_last_error($this->connection) . "\n");
        }
        return $result;
    }

    /**
     * Closes the database connection.
     */
    public function disconnect() {
        if ($this->connection) {
            pg_close($this->connection);
            echo "\nConnection closed.\n";
        }
    }
}
