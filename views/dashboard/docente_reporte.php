<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'docente') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/DocenteController.php';
require_once __DIR__ . '/../../models/Periodo.php';

$id_dmc = $_GET['id_dmc'] ?? null;

$database = new Database();
$db = $database->conectar();

$periodoModel = new Periodo($db);
$periodos = $periodoModel->obtenerTodos();
$id_periodo = $_GET['id_periodo'] ?? null;

$controller = new DocenteController();
$asignaciones = $controller->obtenerAsignaciones();

$infoAsig = null;
$reporte = [];
$evaluaciones = [];

if ($id_dmc) {
    // Info de la asignación
    $sqlAsig = "SELECT dmc.id, m.nombre as materia_nombre, c.nombre as curso_nombre, c.jornada
                FROM docente_materia_curso dmc
                JOIN materias m ON dmc.id_materia = m.id_materia
                JOIN cursos c ON dmc.id_curso = c.id_curso
                WHERE dmc.id = :id AND dmc.id_docente_usuario = :id_doc";
    $stmtAsig = $db->prepare($sqlAsig);
    $stmtAsig->bindParam(":id", $id_dmc);
    $stmtAsig->bindParam(":id_doc", $_SESSION['usuario']['id_usuario']);
    $stmtAsig->execute();
    $infoAsig = $stmtAsig->fetch(PDO::FETCH_ASSOC);

    if ($infoAsig) {
        // Obtener todas las evaluaciones de esta asignación filtradas por periodo si existe
        $sqlEval = "SELECT * FROM evaluaciones WHERE id_docente_materia_curso = :id";
        $paramsEval = [':id' => $id_dmc];
        if ($id_periodo) {
            $sqlEval .= " AND id_periodo = :id_p";
            $paramsEval[':id_p'] = $id_periodo;
        }
        $sqlEval .= " ORDER BY fecha ASC";
        $stmtEval = $db->prepare($sqlEval);
        $stmtEval->execute($paramsEval);
        $evaluaciones = $stmtEval->fetchAll(PDO::FETCH_ASSOC);

        // Obtener estudiantes y sus notas
        $sqlEst = "SELECT m.id_matricula, u.nombres, u.apellidos, e.codigo_estudiantil
                   FROM matriculas m
                   JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
                   JOIN usuarios u ON e.id_usuario = u.id_usuario
                   WHERE m.id_curso = (SELECT id_curso FROM docente_materia_curso WHERE id = :id)
                   AND m.estado = 'ACTIVA'
                   ORDER BY u.apellidos, u.nombres";
        $stmtEst = $db->prepare($sqlEst);
        $stmtEst->execute([':id' => $id_dmc]);
        $estudiantes = $stmtEst->fetchAll(PDO::FETCH_ASSOC);

        foreach ($estudiantes as $est) {
            $notasEst = [];
            $promedio = 0;
            $sumaPesos = 0;

            foreach ($evaluaciones as $ev) {
                $sqlN = "SELECT valor FROM notas WHERE id_evaluacion = :id_e AND id_matricula = :id_m";
                $stmtN = $db->prepare($sqlN);
                $stmtN->execute([':id_e' => $ev['id_evaluacion'], ':id_m' => $est['id_matricula']]);
                $n = $stmtN->fetch(PDO::FETCH_ASSOC);
                
                $valor = $n ? $n['valor'] : 0;
                $notasEst[$ev['id_evaluacion']] = $valor;
                
                $promedio += ($valor * ($ev['porcentaje'] / 100));
                $sumaPesos += $ev['porcentaje'];
            }

            $reporte[] = [
                'codigo' => $est['codigo_estudiantil'],
                'nombres' => $est['apellidos'] . ' ' . $est['nombres'],
                'notas' => $notasEst,
                'promedio' => round($promedio, 2),
                'porcentaje_evaluado' => $sumaPesos
            ];
        }
    }
}

$titulo = "Reporte de Calificaciones";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">Consolidado de <span class="text-blue-600">Notas</span></h2>
        <form action="docente_reporte.php" method="GET" class="grid md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Materia</label>
                <select name="id_dmc" required class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-white outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Seleccione una materia...</option>
                    <?php foreach ($asignaciones as $asig): ?>
                        <option value="<?= $asig['id'] ?>" <?= $id_dmc == $asig['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($asig['curso_nombre'] . " (" . $asig['jornada'] . ") - " . $asig['materia_nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Trimestre / Periodo</label>
                <select name="id_periodo" class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-white outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Todos los periodos</option>
                    <?php foreach ($periodos as $p): ?>
                        <option value="<?= $p['id_periodo'] ?>" <?= $id_periodo == $p['id_periodo'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition font-bold shadow-lg">
                Generar Reporte
            </button>
        </form>
    </div>

    <?php if ($id_dmc && $infoAsig): ?>
    <div class="bg-white rounded-2xl shadow border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <div>
                <h3 class="text-xl font-bold text-slate-800"><?= htmlspecialchars($infoAsig['materia_nombre']) ?> - <?= htmlspecialchars($infoAsig['curso_nombre']) ?></h3>
                <p class="text-sm text-slate-500">Promedios calculados según el peso de cada evaluación.</p>
            </div>
            <button onclick="window.print()" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-bold hover:bg-slate-50 transition">
                <i class="fas fa-print mr-2"></i> Imprimir
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 text-xs uppercase tracking-wider">
                        <th class="p-4 border">Código</th>
                        <th class="p-4 border">Estudiante</th>
                        <?php foreach ($evaluaciones as $ev): ?>
                        <th class="p-4 border text-center" title="<?= htmlspecialchars($ev['titulo']) ?>">
                            <?= htmlspecialchars($ev['titulo']) ?><br>
                            <span class="text-[10px] text-blue-600"><?= $ev['porcentaje'] ?>%</span>
                        </th>
                        <?php endforeach; ?>
                        <th class="p-4 border text-center bg-blue-50 text-blue-700 font-bold">Promedio</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($reporte as $fila): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 border text-sm text-slate-500"><?= htmlspecialchars($fila['codigo']) ?></td>
                        <td class="p-4 border font-medium text-slate-800"><?= htmlspecialchars($fila['nombres']) ?></td>
                        <?php foreach ($evaluaciones as $ev): ?>
                        <td class="p-4 border text-center font-bold <?= $fila['notas'][$ev['id_evaluacion']] < 3 ? 'text-red-600' : 'text-slate-600' ?>">
                            <?= number_format($fila['notas'][$ev['id_evaluacion']], 1) ?>
                        </td>
                        <?php endforeach; ?>
                        <td class="p-4 border text-center bg-blue-50">
                            <span class="px-3 py-1 rounded-full text-sm font-bold <?= $fila['promedio'] < 3 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
                                <?= number_format($fila['promedio'], 2) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if (empty($reporte)): ?>
                    <tr>
                        <td colspan="<?= count($evaluaciones) + 2 ?>" class="p-12 text-center text-slate-400 italic">
                            No hay estudiantes matriculados en este curso o no hay evaluaciones creadas.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-slate-50 border-t text-xs text-slate-500">
            * El promedio se calcula como la suma ponderada de todas las evaluaciones creadas hasta el momento.
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
