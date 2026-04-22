<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'estudiante') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Periodo.php';

$database = new Database();
$db = $database->conectar();

$id_usuario = $_SESSION['usuario']['id_usuario'];

// 1. Obtener información de estudiante y su matrícula actual
$sqlMat = "SELECT m.id_matricula, m.id_curso, e.id_estudiante, c.nombre as curso_nombre, c.jornada
           FROM matriculas m
           JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
           JOIN cursos c ON m.id_curso = c.id_curso
           WHERE e.id_usuario = :id_u AND m.estado = 'ACTIVA'
           ORDER BY m.fecha_matricula DESC LIMIT 1";
$stmtMat = $db->prepare($sqlMat);
$stmtMat->execute([':id_u' => $id_usuario]);
$matricula = $stmtMat->fetch(PDO::FETCH_ASSOC);

if (!$matricula) {
    $titulo = "Mi Dashboard";
    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../layouts/sidebar.php';
    echo '<div class="bg-yellow-50 border border-yellow-200 p-6 rounded-2xl text-yellow-800">
            <h3 class="font-bold text-lg">No tienes matrículas activas</h3>
            <p>Comunícate con la administración para ser asignado a un curso.</p>
          </div>';
    require_once __DIR__ . '/../layouts/footer.php';
    exit;
}

$id_matricula = $matricula['id_matricula'];
$id_periodo = $_GET['id_periodo'] ?? null;

$periodoModel = new Periodo($db);
$periodos = $periodoModel->obtenerTodos();

// 2. Obtener materias y notas
$sqlMaterias = "SELECT m.nombre as materia_nombre, u.nombres as doc_nombre, u.apellidos as doc_apellido, dmc.id as id_dmc
                FROM docente_materia_curso dmc
                JOIN materias m ON dmc.id_materia = m.id_materia
                JOIN usuarios u ON dmc.id_docente_usuario = u.id_usuario
                WHERE dmc.id_curso = :id_c";
$stmtMaterias = $db->prepare($sqlMaterias);
$stmtMaterias->execute([':id_c' => $matricula['id_curso']]);
$materias = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);

$misNotas = [];
foreach ($materias as $mat) {
    // Buscar notas de esta materia
    $sqlN = "SELECT n.valor, e.titulo, e.porcentaje, p.nombre as periodo_nombre
             FROM notas n
             JOIN evaluaciones e ON n.id_evaluacion = e.id_evaluacion
             JOIN periodos p ON e.id_periodo = p.id_periodo
             WHERE n.id_matricula = :id_m AND e.id_docente_materia_curso = :id_dmc";
    
    if ($id_periodo) {
        $sqlN .= " AND e.id_periodo = :id_p";
    }
    
    $stmtN = $db->prepare($sqlN);
    $paramsN = [':id_m' => $id_matricula, ':id_dmc' => $mat['id_dmc']];
    if ($id_periodo) $paramsN[':id_p'] = $id_periodo;
    
    $stmtN->execute($paramsN);
    $notas = $stmtN->fetchAll(PDO::FETCH_ASSOC);
    
    $promedioMateria = 0;
    $porcentajeCubierto = 0;
    foreach ($notas as $n) {
        $promedioMateria += ($n['valor'] * ($n['porcentaje'] / 100));
        $porcentajeCubierto += $n['porcentaje'];
    }
    
    $misNotas[] = [
        'materia' => $mat['materia_nombre'],
        'docente' => $mat['doc_apellido'] . ' ' . $mat['doc_nombre'],
        'detalle' => $notas,
        'promedio' => round($promedioMateria, 2),
        'avance' => $porcentajeCubierto
    ];
}

$titulo = "Mi Boletín Académico";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-6">
    <!-- Header de Bienvenida -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h2 class="text-3xl font-bold text-slate-800">¡Hola, <span class="text-blue-600"><?= htmlspecialchars($_SESSION['usuario']['nombres']) ?></span>!</h2>
            <p class="text-slate-500 mt-1">Estás cursando <span class="font-bold text-slate-700"><?= htmlspecialchars($matricula['curso_nombre']) ?></span> en la jornada <span class="font-bold text-slate-700"><?= htmlspecialchars($matricula['jornada']) ?></span>.</p>
        </div>
        <div class="flex gap-4">
            <form action="" method="GET" class="flex gap-2">
                <select name="id_periodo" onchange="this.form.submit()" class="px-4 py-2 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-600 bg-white text-sm">
                    <option value="">Todos los periodos</option>
                    <?php foreach ($periodos as $p): ?>
                        <option value="<?= $p['id_periodo'] ?>" <?= $id_periodo == $p['id_periodo'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <a href="generar_boletin.php?id_matricula=<?= $id_matricula ?>&id_periodo=<?= $id_periodo ?>" target="_blank" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition flex items-center gap-2">
                <i class="fas fa-file-pdf"></i>
                Descargar Boletín
            </a>
        </div>
    </div>

    <!-- Grid de Materias -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($misNotas as $mn): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition">
            <div class="p-5 border-b border-slate-100 flex justify-between items-start">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg"><?= htmlspecialchars($mn['materia']) ?></h3>
                    <p class="text-xs text-slate-400 italic">Prof. <?= htmlspecialchars($mn['docente']) ?></p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-black <?= $mn['promedio'] < 3 ? 'text-red-600' : 'text-blue-600' ?>">
                        <?= number_format($mn['promedio'], 1) ?>
                    </div>
                    <p class="text-[10px] uppercase font-bold text-slate-400">Promedio</p>
                </div>
            </div>
            
            <div class="p-5 space-y-3 max-h-48 overflow-y-auto">
                <?php if (empty($mn['detalle'])): ?>
                    <p class="text-center text-slate-400 text-xs py-4">Aún no hay notas registradas.</p>
                <?php else: ?>
                    <?php foreach ($mn['detalle'] as $n): ?>
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex flex-col">
                            <span class="text-slate-700 font-medium"><?= htmlspecialchars($n['titulo']) ?></span>
                            <span class="text-[10px] text-slate-400"><?= $n['periodo_nombre'] ?> (<?= $n['porcentaje'] ?>%)</span>
                        </div>
                        <span class="font-bold <?= $n['valor'] < 3 ? 'text-red-500' : 'text-slate-600' ?>"><?= number_format($n['valor'], 1) ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="px-5 py-3 bg-slate-50 border-t border-slate-100">
                <div class="flex justify-between text-[10px] font-bold text-slate-500 mb-1">
                    <span>AVANCE DEL CURSO</span>
                    <span><?= $mn['avance'] ?>%</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-1.5">
                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: <?= $mn['avance'] ?>%"></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>