<?php
include 'dbController.php';
include 'queries.php';

$envPath = __DIR__ . "/../.env";

try {
    $dbController = dbController::getInstance($envPath);
} catch (Exception $e) {
    die("Initialization error: " . $e->getMessage() . "\n");
}

// --- Query 1: Duplicate companies ---
$dupQuery = getDuplicateCompaniesQuery();
echo "Duplicate Companies Query:\n$dupQuery\n\n";
$result = $dbController->executeQuery($dupQuery);
echo "Duplicate Companies:\n";
while ($row = pg_fetch_assoc($result)) {
    print_r($row);
}
pg_free_result($result);

// --- Query 2: Get statistics on sources ---
$statsQuery = getSourceStatisticsQuery();
echo "\nSource Statistics Query:\n$statsQuery\n\n";
$result = $dbController->executeQuery($statsQuery);
echo "Source Statistics:\n";
while ($row = pg_fetch_assoc($result)) {
    print_r($row);
}
pg_free_result($result);


// Disconnect from the database.
$dbController->disconnect();
