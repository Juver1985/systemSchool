<?php
require 'c:/laragon/www/sistema-calificaciones/config/database.php';
$db = new Database();
$conn = $db->conectar();

$stmt = $conn->query("SELECT TABLE_NAME, CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'bdcalificaciones' AND REFERENCED_TABLE_NAME IS NOT NULL");
$fks = $stmt->fetchAll(PDO::FETCH_ASSOC);

try {
    foreach($fks as $fk) {
        $table = $fk['TABLE_NAME'];
        $constraint = $fk['CONSTRAINT_NAME'];
        $conn->exec("ALTER TABLE `$table` DROP FOREIGN KEY `$constraint`");
    }

    foreach($fks as $fk) {
        $table = $fk['TABLE_NAME'];
        $constraint = $fk['CONSTRAINT_NAME'];
        $col = $fk['COLUMN_NAME'];
        $refTable = $fk['REFERENCED_TABLE_NAME'];
        $refCol = $fk['REFERENCED_COLUMN_NAME'];
        $conn->exec("ALTER TABLE `$table` ADD CONSTRAINT `$constraint` FOREIGN KEY (`$col`) REFERENCES `$refTable` (`$refCol`) ON DELETE CASCADE ON UPDATE CASCADE");
    }
    echo "Success!";
} catch (Exception $e) {
    echo "Error: ". $e->getMessage();
}
