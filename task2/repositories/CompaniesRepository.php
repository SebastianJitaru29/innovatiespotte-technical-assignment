<?php
require_once 'controllers/dbController.php';
require_once 'queries/CompaniesQueries.php';

/*
    * CompaniesRepository class
    */
class CompaniesRepository {
    private $db;

    public function __construct() {
        $this->db = dbController::getInstance();
    }
    /**
     * Returns the duplicate companies.
     *
     * @return resource The result of the query.
     */

    public function getDuplicateCompanies() {
        $query = getDuplicateCompaniesQuery();
        echo 'Duplicate Companies Query: ';
        echo $query;
        return $this->db->executeQuery($query);
    }
    /**
     * Normalizes the companies and introduces the normalized outputs into companies_normalized table.
     *
     * @return resource The result of the query.
     */
    public function normalizeCompanies() {
        $query = getNormalizeCompaniesQuery();
        echo 'Normalize Companies Query: ';
        echo $query;
        return $this->db->executeQuery($query);
    }
    /**
     * Returns the source statistics how many entries per source.
     *
     * @return resource The result of the query.
     */

    public function getSourceStatistics() {
        $query = getSourceStatisticsQuery();
        echo 'Source Statistics Query: ';
        echo $query;
        return $this->db->executeQuery($query);
    }
}
