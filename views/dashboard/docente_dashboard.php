<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'docente') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/DocenteController.php';

$controller = new DocenteController();
$asignaciones = $controller->obtenerAsignaciones();

$titulo = "Mi Dashboard";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <h2 class="text-4xl font-light text-slate-800 mb-6">Mis <span class="font-bold">Cursos y Materias</span></h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($asignaciones as $asig): ?>
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6 hover:shadow-md transition group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-blue-600 text-white px-3 py-1 rounded-lg text-sm font-bold">
                            <?= htmlspecialchars($asig['curso_nombre']) ?>
                        </div>
                        <i class="fas fa-book text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-1"><?= htmlspecialchars($asig['materia_nombre']) ?></h3>
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-2"><?= htmlspecialchars($asig['jornada']) ?></p>
                    <p class="text-sm text-slate-500 mb-6">Año Académico: <?= htmlspecialchars($asig['anio']) ?></p>
                    
                    <a href="docente.php?id_dmc=<?= $asig['id'] ?>" class="block w-full text-center bg-white border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white py-2 rounded-xl font-semibold transition">
                        Gestionar Evaluaciones
                    </a>
                </div>
            <?php endforeach; ?>

            <?php if (empty($asignaciones)): ?>
                <div class="col-span-full py-12 text-center">
                    <div class="text-slate-300 text-6xl mb-4"><i class="fas fa-folder-open"></i></div>
                    <p class="text-slate-500 text-lg">No tienes materias asignadas para este año.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
