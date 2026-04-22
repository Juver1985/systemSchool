<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../models/Curso.php';
require_once __DIR__ . '/../../models/Materia.php';

$database = new Database();
$db = $database->conectar();

$usuarioModel = new Usuario($db);
$cursoModel = new Curso($db);
$materiaModel = new Materia($db);

$usuarios = $usuarioModel->obtenerTodos();
$totalUsuarios = count($usuarios);
$totalDocentes = count(array_filter($usuarios, fn($u) => $u['rol'] === 'docente'));
$totalEstudiantes = count(array_filter($usuarios, fn($u) => $u['rol'] === 'estudiante'));

$cursos = $cursoModel->obtenerTodos();
$totalCursos = count($cursos);

$materias = $materiaModel->obtenerTodos();
$totalMaterias = count($materias);

$titulo = "Dashboard Administrador";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card Estudiantes -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-2xl">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Estudiantes</p>
                <p class="text-2xl font-bold text-slate-800"><?= $totalEstudiantes ?></p>
            </div>
        </div>

        <!-- Card Docentes -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-2xl">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Docentes</p>
                <p class="text-2xl font-bold text-slate-800"><?= $totalDocentes ?></p>
            </div>
        </div>

        <!-- Card Cursos -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-2xl">
                <i class="fas fa-school"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Cursos Activos</p>
                <p class="text-2xl font-bold text-slate-800"><?= $totalCursos ?></p>
            </div>
        </div>

        <!-- Card Materias -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-2xl">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Materias</p>
                <p class="text-2xl font-bold text-slate-800"><?= $totalMaterias ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Próximos Eventos o Accesos Rápidos -->
        <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 mb-6">Accesos Rápidos</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="admin.php" class="p-4 border rounded-xl hover:bg-slate-50 transition flex flex-col items-center gap-2">
                    <i class="fas fa-user-plus text-blue-600 text-xl"></i>
                    <span class="text-sm font-medium">Nuevo Usuario</span>
                </a>
                <a href="admin_cursos.php" class="p-4 border rounded-xl hover:bg-slate-50 transition flex flex-col items-center gap-2">
                    <i class="fas fa-plus-circle text-green-600 text-xl"></i>
                    <span class="text-sm font-medium">Crear Curso</span>
                </a>
                <a href="admin_matriculas.php" class="p-4 border rounded-xl hover:bg-slate-50 transition flex flex-col items-center gap-2">
                    <i class="fas fa-id-card text-purple-600 text-xl"></i>
                    <span class="text-sm font-medium">Matricular</span>
                </a>
                <a href="admin_periodos.php" class="p-4 border rounded-xl hover:bg-slate-50 transition flex flex-col items-center gap-2">
                    <i class="fas fa-calendar-check text-orange-600 text-xl"></i>
                    <span class="text-sm font-medium">Gestionar Periodo</span>
                </a>
            </div>
        </div>

        <!-- Resumen de Sistema -->
        <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 mb-6">Estado del Sistema</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        <span class="text-slate-700 font-medium">Base de Datos</span>
                    </div>
                    <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded">CONECTADO</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clock text-slate-400"></i>
                        <span class="text-slate-700 font-medium">Hora del Servidor</span>
                    </div>
                    <span class="text-sm text-slate-500"><?= date('H:i:s d/m/Y') ?></span>
                </div>
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-shield-alt text-slate-400"></i>
                        <span class="text-slate-700 font-medium">Nivel de Acceso</span>
                    </div>
                    <span class="text-sm font-bold text-blue-600">ADMINISTRADOR</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
