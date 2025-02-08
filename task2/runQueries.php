<?php
include 'queries.php';

$envFile = __DIR__ . "/../.env"; 
if (!file_exists($envFile)) {
    die("Error: .env file not found.\n");
}

$env = file_get_contents($envFile);
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

$dbhost = getenv('DB_HOST') ?: 'localhost';
$dbport = getenv('DB_PORT') ?: '5432';
$dbname = getenv('POSTGRES_DB');
$dbuser = getenv('POSTGRES_USER');
$dbpass = getenv('POSTGRES_PASSWORD');

// Print values with \n 
echo "DB_HOST: $dbhost\n";
echo "DB_PORT: $dbport\n";
echo "POSTGRES_DB: $dbname\n";
echo "POSTGRES_USER: $dbuser\n";
echo "POSTGRES_PASSWORD: $dbpass\n";


if (!$dbname || !$dbuser || !$dbpass) {
    die("Database configuration is incomplete in .env file.\n");
}

$connectionString = "host=$dbhost port=$dbport dbname=$dbname user=$dbuser password=$dbpass";

$dbconn = pg_connect($connectionString);
if (!$dbconn) {
    die("Error: Unable to connect to the database.\n");
}
echo "Connected successfully to the database using pg_connect.\n\n";


$dupQuery = getDuplicateCompaniesQuery();
echo "Duplicate Companies Query:\n$dupQuery\n\n";
$result = pg_query($dbconn, $dupQuery);
if (!$result) {
    die("Error executing duplicate companies query: " . pg_last_error($dbconn) . "\n");
}
echo "Duplicate Companies:\n";
while ($row = pg_fetch_assoc($result)) {
    print_r($row);
}
pg_free_result($result);


$statsQuery = getSourceStatisticsQuery();
echo "\nSource Statistics Query:\n$statsQuery\n\n";
$result = pg_query($dbconn, $statsQuery);
if (!$result) {
    die("Error executing source statistics query: " . pg_last_error($dbconn) . "\n");
}
echo "\nSource Statistics:\n";
while ($row = pg_fetch_assoc($result)) {
    print_r($row);
}
pg_free_result($result);

// Close the database connection.
pg_close($dbconn);
echo "\nConnection closed.\n";