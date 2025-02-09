<?php
require_once 'controllers/dbController.php';
require_once 'queries/CompaniesQueries.php';


class CompaniesRepository {
    private $db;

    public function __construct() {
        $this->db = dbController::getInstance();
    }

    public function getDuplicateCompanies() {
        $query = getDuplicateCompaniesQuery();
        echo 'Duplicate Companies Query: ';
        echo $query;
        return $this->db->executeQuery($query);
    }

    public function normalizeCompanies() {
        $query = getNormalizeCompaniesQuery();
        echo 'Normalize Companies Query: ';
        echo $query;
        return $this->db->executeQuery($query);
    }

    public function getSourceStatistics() {
        $query = getSourceStatisticsQuery();
        echo 'Source Statistics Query: ';
        echo $query;
        return $this->db->executeQuery($query);
    }
}
