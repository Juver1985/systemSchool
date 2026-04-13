<?php
require 'c:/laragon/www/sistema-calificaciones/config/database.php';
$db = new Database();
$conn = $db->conectar();
$stmt = $conn->query("SELECT TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_NAME = 'usuarios' AND REFERENCED_COLUMN_NAME = 'id_usuario' AND TABLE_SCHEMA = 'bdcalificaciones'");
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($tables as $t) { echo $t['TABLE_NAME']."\n"; }
