<?php
// Try both databases
$dbs = ['hongdu_cms', 'hongdu'];
foreach ($dbs as $db) {
    try {
        $conn = @new mysqli('localhost', 'root', '', $db);
        if ($conn->connect_error) {
            echo "DB '$db': Connection failed - " . $conn->connect_error . "\n";
        } else {
            echo "DB '$db': Connected OK\n";
            $tables = $conn->query('SHOW TABLES');
            if ($tables) {
                while ($row = $tables->fetch_row()) {
                    echo "  - Table: " . $row[0] . "\n";
                }
            }
            $conn->close();
        }
    } catch (Exception $e) {
        echo "DB '$db': Error - " . $e->getMessage() . "\n";
    }
}

// Check WordPress tables in hongdu
try {
    $conn = @new mysqli('localhost', 'root', '', 'hongdu');
    if (!$conn->connect_error) {
        $result = $conn->query("SHOW TABLES LIKE 'wp_%'");
        if ($result && $result->num_rows > 0) {
            echo "\nWordPress tables exist in 'hongdu' database\n";
        } else {
            echo "\nNo WordPress tables in 'hongdu'\n";
            $result = $conn->query("SHOW TABLES");
            if ($result) {
                while ($row = $result->fetch_row()) {
                    echo "  - " . $row[0] . "\n";
                }
            }
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
