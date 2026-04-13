<?php
require 'c:/laragon/www/sistema-calificaciones/config/database.php';
$db = new Database();
$conn = $db->conectar();
$stmt = $conn->query("SELECT TABLE_NAME, REFERENCED_TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'bdcalificaciones' AND REFERENCED_TABLE_NAME IS NOT NULL");
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($tables as $t) { echo $t['TABLE_NAME']." -> ".$t['REFERENCED_TABLE_NAME']."\n"; }
