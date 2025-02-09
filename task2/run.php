<?php
require_once 'controllers/dbController.php';
require_once 'repositories/CompaniesRepository.php';


$envPath = __DIR__ . "/../.env";

// Connect to database by, initializing the database connection using the singleton dbController.
try {
    $dbController = dbController::getInstance($envPath);
} catch (Exception $e) {
    die("Initialization error: " . $e->getMessage() . "\n");
}

$companiesRepo = new CompaniesRepository();

// --- Query 1: Identify Duplicate Companies ---
echo "Duplicate Companies:\n";
$result = $companiesRepo->getDuplicateCompanies();
while ($row = pg_fetch_assoc($result)) {
    print_r($row);
}
pg_free_result($result);

// --- Query 2: Get Source Statistics ---
echo "\nSource Statistics:\n";
$result = $companiesRepo->getSourceStatistics();
while ($row = pg_fetch_assoc($result)) {
    print_r($row);
}
pg_free_result($result);

// --- Query 3: Normalize Companies ---
echo "\nExecuting normalization query...\n";
$result = $companiesRepo->normalizeCompanies();
echo "Normalization query executed. Rows affected: " . pg_affected_rows($result) . "\n";
pg_free_result($result);

// Disconnect from the database.
$dbController->disconnect();
