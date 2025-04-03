<?php
require_once __DIR__.'/../vendor/autoload.php';
use Dotenv\Dotenv;

class Database {
    /** @var string Database host:port */
    private string $host;
    private string $port;
    /** @var string Database name */
    private string $dbName;
    /** @var string Database username */
    private string $username;
    /** @var string Database password */
    private string $password;
    /** @var string Character set */
    private string $charset;

    /** @var PDO|null The PDO instance */
    private ?PDO $pdo = null;
    /** @var PDOStatement|null The last prepared statement */
    private ?PDOStatement $stmt = null;
    /** @var string|null Stores the last error message */
    private ?string $error = null;
    /** @var array Default PDO connection options */
    private array $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays by default
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
        PDO::ATTR_PERSISTENT         => false                   // Use persistent connections (optional, consider carefully)
    ];

    /**
     * Database constructor.
     *
     * @param string $host     Database host (e.g., 'localhost')
     * @param string $dbName   Database name
     * @param string $username Database username
     * @param string $password Database password
     * @param string $charset  Character set (default: 'utf8mb4')
     * @param array  $options  Optional array of PDO options to override defaults
     */
    public function __construct(string $charset = 'utf8mb4', array $options = []) {

        $dotenv = Dotenv::createImmutable(__DIR__."/..");
        $dotenv->load();
        
        $host = $_ENV["host"];
        $dbname = $_ENV["dbname"]; 
        $username = $_ENV["username"];
        $password = $_ENV["password"];  
        $port = $_ENV["port"];

        $this->host     = $host;
        $this->port     = $port;
        $this->dbName   = $dbname;
        $this->username = $username;
        $this->password = $password;
        $this->charset  = $charset;

        // Override default options if provided
        $this->options = $options + $this->options;

        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbName};charset={$this->charset}";

        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $this->options);
        } catch (PDOException $e) {
            // Store error message but don't expose sensitive details publicly in production
            $this->error = "Connection failed: " . $e->getMessage();
            // In a real application, you would log this error more robustly
            // error_log("Database Connection Error: " . $e->getMessage());
            // You might choose to throw an exception here or handle it differently
            // depending on your application's error handling strategy.
            // For this example, we store the error and the pdo property remains null.
        }
    }

    /**
     * Checks if the PDO connection was successfully established.
     *
     * @return bool True if connected, false otherwise.
     */
    public function isConnected(): bool {
        return $this->pdo !== null;
    }

    /**
     * Prepares and executes a SQL query with optional parameters.
     * Always uses prepared statements when parameters are provided.
     *
     * @param string $sql    The SQL query string. Use placeholders like :name or ?
     * @param array  $params An associative array of parameters (e.g., [':name' => 'value']) or a numerically indexed array for ?.
     * @return bool True on success, false on failure.
     */
    public function query(string $sql, array $params = []): bool {
        if (!$this->isConnected()) {
            $this->error = "Not connected to the database.";
            return false;
        }

        $this->error = null; // Reset error before new query
        try {
            $this->stmt = $this->pdo->prepare($sql);
            // execute() returns true on success or false on failure.
            return $this->stmt->execute($params);
        } catch (PDOException $e) {

            $this->error = "Query failed: " . $e->getMessage() . " (SQL: " . $sql . ")";
            // error_log("Database Query Error: " . $e->getMessage() . " | SQL: " . $sql);
            $this->stmt = null; // Reset statement on error
            return false;
        }
    }

    /**
     * Fetches the next row from the result set of the last query.
     *
     * @param int $fetchStyle Optional PDO fetch style (e.g., PDO::FETCH_OBJ). Defaults to the connection default (PDO::FETCH_ASSOC).
     * @return mixed The next row as an array or object, or false if no more rows or error.
     */
    public function fetch(int $fetchStyle = PDO::FETCH_DEFAULT): mixed {
        if (!$this->stmt) {
            $this->error = "No statement executed or previous query failed.";
            return false;
        }
        try {
            return $this->stmt->fetch($fetchStyle === PDO::FETCH_DEFAULT ? $this->options[PDO::ATTR_DEFAULT_FETCH_MODE] : $fetchStyle);
        } catch (PDOException $e) {
            $this->error = "Fetch failed: " . $e->getMessage();
            // error_log("Database Fetch Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetches all rows from the result set of the last query.
     *
     * @param int $fetchStyle Optional PDO fetch style (e.g., PDO::FETCH_OBJ). Defaults to the connection default (PDO::FETCH_ASSOC).
     * @return array|false An array containing all result rows, or false on error. Returns an empty array if no rows matched.
     */
    public function fetchAll(int $fetchStyle = PDO::FETCH_DEFAULT): array|false {
        if (!$this->stmt) {
            $this->error = "No statement executed or previous query failed.";
            return false;
        }
        try {
             return $this->stmt->fetchAll($fetchStyle === PDO::FETCH_DEFAULT ? $this->options[PDO::ATTR_DEFAULT_FETCH_MODE] : $fetchStyle);
        } catch (PDOException $e) {
            $this->error = "FetchAll failed: " . $e->getMessage();
            // error_log("Database FetchAll Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetches a single column from the next row of the result set.
     *
     * @param int $columnNumber The 0-indexed column number to fetch.
     * @return mixed The value of the specified column, or false if no more rows or error.
     */
    public function fetchColumn(int $columnNumber = 0): mixed {
        if (!$this->stmt) {
            $this->error = "No statement executed or previous query failed.";
            return false;
        }
        try {
            return $this->stmt->fetchColumn($columnNumber);
        } catch (PDOException $e) {
            $this->error = "FetchColumn failed: " . $e->getMessage();
            // error_log("Database FetchColumn Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Returns the number of rows affected by the last executed DELETE, INSERT, or UPDATE statement.
     * For SELECT statements, behavior varies across databases; use count($db->fetchAll()) for reliable SELECT counts.
     *
     * @return int|false The number of affected rows, or false if the last query failed or was not applicable.
     */
    public function rowCount(): int|false {
        if (!$this->stmt) {
             $this->error = "No statement available to count rows.";
             return false;
        }
        // rowCount() can be unreliable for SELECT in some drivers.
        // It's primarily for INSERT, UPDATE, DELETE.
        try {
           return $this->stmt->rowCount();
        } catch (PDOException $e) {
             $this->error = "RowCount failed: " . $e->getMessage();
             return false;
        }
    }

    /**
     * Returns the ID of the last inserted row or sequence value.
     *
     * @param string|null $name Name of the sequence object (if needed by the driver).
     * @return string|false The ID of the last inserted row, or false on failure or if not supported.
     */
    public function lastInsertId(?string $name = null): string|false {
         if (!$this->isConnected()) {
            $this->error = "Not connected to the database.";
            return false;
        }
        try {
            return $this->pdo->lastInsertId($name);
        } catch (PDOException $e) {
            $this->error = "LastInsertId failed: " . $e->getMessage();
            return false;
        }
    }

    // --- Transaction Methods ---

    /**
     * Initiates a transaction.
     * Turns off autocommit mode. Changes will not be saved until commit() is called.
     *
     * @return bool True on success, false on failure.
     */
    public function beginTransaction(): bool {
         if (!$this->isConnected()) {
            $this->error = "Not connected to the database.";
            return false;
        }
        $this->error = null;
        try {
            return $this->pdo->beginTransaction();
        } catch (PDOException $e) {
            $this->error = "Failed to begin transaction: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Commits a transaction.
     * Makes changes permanent and turns autocommit back on (if it was on before).
     *
     * @return bool True on success, false on failure.
     */
    public function commit(): bool {
        if (!$this->isConnected()) {
            $this->error = "Not connected to the database.";
            return false;
        }
         $this->error = null;
        try {
            return $this->pdo->commit();
        } catch (PDOException $e) {
            $this->error = "Failed to commit transaction: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Rolls back the current transaction.
     * Discards changes made since beginTransaction() and turns autocommit back on.
     *
     * @return bool True on success, false on failure.
     */
    public function rollBack(): bool {
        if (!$this->isConnected()) {
            $this->error = "Not connected to the database.";
            return false;
        }
        $this->error = null;
        try {
            return $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->error = "Failed to roll back transaction: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Checks if inside a transaction.
     *
     * @return bool True if a transaction is currently active, false otherwise.
     */
    public function inTransaction(): bool {
         if (!$this->isConnected()) {
            return false; // Cannot be in a transaction if not connected
        }
        return $this->pdo->inTransaction();
    }

    // --- Error Handling ---

    /**
     * Returns the last error message that occurred.
     *
     * @return string|null The last error message, or null if no error occurred.
     */
    public function getError(): ?string {
        return $this->error;
    }

    /**
     * Returns the underlying PDO object.
     * Use with caution, as direct manipulation bypasses the wrapper's logic.
     *
     * @return PDO|null The PDO instance or null if connection failed.
     */
    public function getPdoInstance(): ?PDO {
        return $this->pdo;
    }

    /**
     * Returns the last PDOStatement object.
     * Useful for accessing specific PDOStatement methods if needed.
     *
     * @return PDOStatement|null The last PDOStatement instance or null if no query run/failed.
     */
    public function getStatement(): ?PDOStatement {
        return $this->stmt;
    }

    /**
     * Closes the database connection by nullifying the PDO object.
     * Optional: PHP usually handles this automatically when the script ends or the object is destroyed.
     */
    public function closeConnection(): void {
        $this->pdo = null;
        $this->stmt = null; // Also clear the statement
    }

    /**
     * Destructor. Optionally close the connection.
     */
    public function __destruct() {
       // $this->closeConnection(); // Uncomment if explicit closing is desired
    }
}

?>