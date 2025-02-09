<?php
class CompanyClass {
      /**
     * Normalizes and validates company data, the name, website, and address.
     *
     * @param array $data Input company data.
     * @return array|error Normalized data or error if validation fails.
     */
    public function normalizeCompanyData(array $data): ?array {
        if (!$this->isCompanyDataValid($data)) {
            return ['error' => "Company data is not valid. A 'name', 'address' and 'website' must be provided for the company."];
        }
        $nomralizedCompanyData = [];
        
        // Normalize name 
        $name =  strtolower(trim($data['name']));
        $nomralizedCompanyData['name'] = ($name !== '') ? $name : null;
        

        // Normalize website by trimming and checking if it starts with http:// or https://
        $cleanWebsite = strtolower(trim($data['website']));    
        if (preg_match('/^https?:\/\//i', $cleanWebsite)) {
            $host = parse_url($cleanWebsite, PHP_URL_HOST); // Get the host from the URL
            $nomralizedCompanyData['website'] = $host ?: $cleanWebsite; // If host is not available, use the full URL, maybe parse failed
        } else {
            // TBD: Decide what to do if the website does not start with http:// or https://
            $nomralizedCompanyData['website'] = $cleanWebsite; //In the provided code this was done however not clear if this is the right approach (Should URL still be saved?)
        }
        
        // Normalize address
        $address = trim($data['address']);
        $nomralizedCompanyData['address'] = ($address !== '') ? $address : null;
    
        return $nomralizedCompanyData;
    }
    /**
     * Validates company data is complete.
     *
     * @param array $data Input company data.
     * @return bool True if all fields are provided, false otherwise.
     */	
    private function isCompanyDataValid(array $data): bool{
        return isset($data['name']) && isset($data['address']) && isset($data['website']);
    }
}
// Test Data
$input = [
    'name' => ' OpenAI ',
    'website' => 'https://openai.com ',
    'address' => ' '
];
$input2 = [
    'name' => 'Innovatiespotter',
    'address' => 'Groningen'
];
$input3 = [
    'name' => ' Apple ',
    'website' => 'xhttps://apple.com ',
];

$company = new CompanyClass();

$result = $company->normalizeCompanyData($input);
var_dump($result);

$result2 = $company->normalizeCompanyData($input2);
var_dump($result2);

$result3 = $company->normalizeCompanyData($input3);
var_dump($result3);
