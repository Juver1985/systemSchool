<?php
require 'c:/laragon/www/sistema-calificaciones/config/database.php';
require 'c:/laragon/www/sistema-calificaciones/models/Usuario.php';

$db = new Database();
$conn = $db->conectar();
$usuarioModel = new Usuario($conn);

$datos = [
    'nombres' => 'Prueba',
    'apellidos' => 'QA',
    'email' => 'pruebaqa@test.com',
    'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
    'rol' => 'administrador'
];

$resultado = $usuarioModel->registrar($datos);
echo "Result of registrar: ";
var_dump($resultado);
