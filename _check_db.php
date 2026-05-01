<?php
$conn = new mysqli('localhost', 'root', '', 'hongdu_cms');
$result = $conn->query('SHOW TABLES');
echo "Tables:\n";
while ($row = $result->fetch_row()) {
    echo '  - ' . $row[0] . "\n";
}
$conn->close();
