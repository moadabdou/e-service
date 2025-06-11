<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'eservice';
$keepTable = 'user';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // Get all table names except the one to keep
    $stmt = $pdo->prepare("
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = :db 
          AND table_name <> :keepTable
    ");
    $stmt->execute(['db' => $db, 'keepTable' => $keepTable]);
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Truncate each table
    foreach ($tables as $table) {
        $pdo->exec("TRUNCATE TABLE `$table`");
        echo "Truncated: $table\n";
    }

    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "✅ Done!";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
