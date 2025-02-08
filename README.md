# Innovatiespotter - Software Engineer Assignment

## Task 1: PHP Code Refactoring

### Errors Found in the Original Code
1. **Missing Return Value:**
    - In cases of invalid data, the function did not return a value despite the declared return type of `?array` (line 10 breaks).
   ![alt text](figures/image.png)

2. **Data Validation:**
   - The `isCompanyDataValid()` function innitially checked for an array index (`$data[0]`) instead of using the required keys (like `'name'`) exist. Also added checks for address and website, supposing that they are required fields.
    ![alt text](figures/image2.png)

3. **Undefined Variable:**
   - The original code uses `$cleanWebsite` in a regular expression check without initializing it. This causes a warning because PHP cannot find the variable's value.
   ![alt text](figures/image3.png)

4. **Code Readability:**
   - The provided code used inline conditional statements (ternary operators) in a way that reduced clarity.
   - If lacked braces.
   - The code lacked comments and PHPDoc blocks, making it harder to understand the purpose of each function and variable.
   - Name of variables are not meaningful.

### Procedure Followed to Fix the Issues

- **Consistent Return Type:**  
  The function now explicitly returns `error` if the company data provided is invalid, acording with its declared return type.

- **Improved Data Validation:**  
  The validation method now checks for `name` ,`address` and `website`  key, ensuring that the object provided is of correct format.

- **Defined `$cleanWebsite`:**  
  The variable is now properly initialized by trimming the input from `$data['website']` before using it in any operations.

- **Improved Readability:**  
  The refactored code is formatted with proper indentation, consistent use of braces, included detailed comments and PHPDoc blocks, changed name of the variables to be more meaningful and added extra validation.

- **Final output of the refactored code is:**
    ![alt text](figures/image4.png)

## Task 2: PostgreSQL
1. **DB innitialization and docker container**
    - The database was created using the `docker-compose.yml` file, which defines a PostgreSQL container with the required environment variables.
    - The database schema was created using the `init.sql` file, which contains the SQL commands to create the `companies` and `normalized_companies` tables and also inserted some test companies.
    - The `init.sql` file was mounted to the `/docker-entrypoint-initdb.d/` directory in the PostgreSQL container, such that the schema is created when the container starts.
    - The database connection variables were defined in the `.env` file to ensure secure docker container. (should be in the gitignore file but for this assignment it is included as demonstration)
    ![alt text](figures/image5.png)
    ![alt text](figures/image6.png)
2. **Implement DB controller, queries.php and runQueries.php files**
    - The `queries.php` file contains the SQL queries to interact with the database, such as inserting, updating, and selecting data from the `companies` and `normalized_companies` tables.
    - The `runQueries.php` file includes the necessary code to run the queries and display the results.
    - The controller file `dbController.php` is used to connect to the database and execute the queries.