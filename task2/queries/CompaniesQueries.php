<?php
/**
 * Returns the SQL query to identify duplicate companies.
 *
 * @return string The SQL query.
 */
function getDuplicateCompaniesQuery() {
    return "
        SELECT 
            LOWER(name) as normalized_name,
            COUNT(*) as occurrence,
            array_agg(DISTINCT source) as sources,
            string_agg(name, ', ') as original_names
        FROM 
            companies
        GROUP BY 
            LOWER(name)
        HAVING 
            COUNT(*) > 1;
    ";
}

/**
 * Returns the SQL query to normalize company data and insert it into the normalized_companies table.
 *
 * @return string The SQL query.
 */
function getNormalizeCompaniesQuery() {
    return "
        WITH ranked_companies as (
            SELECT 
                name,
                website,
                address,
                source,
                ROW_NUMBER() OVER (
                    PARTITION BY LOWER(name)
                    ORDER BY 
                        CASE source
                            WHEN 'MANUAL' THEN 1
                            WHEN 'API' THEN 2
                            WHEN 'SCRAPER' THEN 3
                            ELSE 4
                        END
                ) as rn
            FROM 
                companies
        )

        INSERT INTO 
            normalized_companies (name, canonical_website, address)
        SELECT 
            name, 
            website, 
            address
        FROM 
            ranked_companies
        WHERE 
            rn = 1
        ON CONFLICT (name) DO NOTHING;

    ";
}

/**
 * Returns the SQL query to get statistics on the sources of companies.
 *
 * @return string The SQL query.
 */
function getSourceStatisticsQuery() {
    return "
        SELECT 
            source,
            COUNT(*) as company_count
        FROM 
            companies
        GROUP BY 
            source
        ORDER BY 
            company_count DESC;

    ";
}
   