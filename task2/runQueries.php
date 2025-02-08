<?php
include 'dbController.php';
include 'queries.php';

$envPath = __DIR__ . "/../.env";

$dbManager = new DBManager($envPath);

// Connect to the database.
$dbManager->connect();

// --- Query 1: Duplicate companies ---
$dupQuery = getDuplicateCompaniesQuery();
echo "Duplicate Companies Query:\n$dupQuery\n\n";
$result = $dbManager->executeQuery($dupQuery);
echo "Duplicate Companies:\n";
while ($row = pg_fetch_assoc($result)) {
    print_r($row);
}
pg_free_result($result);

// --- Query 2: Get statistics on sources ---
$statsQuery = getSourceStatisticsQuery();
echo "\nSource Statistics Query:\n$statsQuery\n\n";
$result = $dbManager->executeQuery($statsQuery);
echo "Source Statistics:\n";
while ($row = pg_fetch_assoc($result)) {
    print_r($row);
}
pg_free_result($result);


// Disconnect from the database.
$dbManager->disconnect();
