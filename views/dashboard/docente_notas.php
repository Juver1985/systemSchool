<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'docente') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Nota.php';
require_once __DIR__ . '/../../models/Evaluacion.php';

$id_evaluacion = $_GET['id_evaluacion'] ?? null;

if (!$id_evaluacion) {
    header("Location: docente_dashboard.php");
    exit;
}

$database = new Database();
$db = $database->conectar();

$evaluacionModel = new Evaluacion($db);
$eval = $evaluacionModel->obtenerPorId($id_evaluacion);

if (!$eval) {
    header("Location: docente_dashboard.php");
    exit;
}

// Obtener info de la materia y curso
$sqlInfo = "SELECT m.nombre as materia_nombre, c.nombre as curso_nombre, dmc.id as id_dmc
            FROM evaluaciones e
            JOIN docente_materia_curso dmc ON e.id_docente_materia_curso = dmc.id
            JOIN materias m ON dmc.id_materia = m.id_materia
            JOIN cursos c ON dmc.id_curso = c.id_curso
            WHERE e.id_evaluacion = :id";
$stmtInfo = $db->prepare($sqlInfo);
$stmtInfo->bindParam(":id", $id_evaluacion);
$stmtInfo->execute();
$info = $stmtInfo->fetch(PDO::FETCH_ASSOC);

$notaModel = new Nota($db);
$estudiantes = $notaModel->obtenerEstudiantesPorEvaluacion($id_evaluacion);

$titulo = "Calificar: " . $eval['titulo'];
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="docente_dashboard.php" class="text-sm font-medium text-slate-500 hover:text-blue-600 transition">Dashboard</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-xs text-slate-400 mx-2"></i>
                            <a href="docente.php?id_dmc=<?= $info['id_dmc'] ?>" class="text-sm font-medium text-slate-500 hover:text-blue-600 transition">Evaluaciones</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-xs text-slate-400 mx-2"></i>
                            <span class="text-sm font-bold text-slate-700">Calificar</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="text-3xl font-bold text-slate-800">
                Calificando: <span class="text-blue-600"><?= htmlspecialchars($eval['titulo']) ?></span>
            </h2>
            <p class="text-slate-500 font-medium"><?= htmlspecialchars($info['materia_nombre']) ?> - <?= htmlspecialchars($info['curso_nombre']) ?></p>
        </div>
        
        <div class="text-right">
            <span class="bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-sm font-bold">
                Peso: <?= $eval['porcentaje'] ?>%
            </span>
        </div>
    </div>

    <?php if (isset($_SESSION['alert'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '<?= htmlspecialchars($_SESSION['alert']['icon']) ?>',
                    title: '<?= htmlspecialchars($_SESSION['alert']['title']) ?>',
                    text: '<?= htmlspecialchars($_SESSION['alert']['text']) ?>',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <form action="../../controllers/DocenteNotaController.php?accion=guardar" method="POST" class="bg-white rounded-2xl shadow border border-slate-200 overflow-hidden">
        <input type="hidden" name="id_evaluacion" value="<?= $id_evaluacion ?>">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-700 border-b">
                    <tr>
                        <th class="p-4 w-1/3">Estudiante</th>
                        <th class="p-4 w-24 text-center">Nota (0-5)</th>
                        <th class="p-4">Observaciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($estudiantes)): ?>
                    <tr>
                        <td colspan="3" class="p-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i class="fas fa-user-slash text-4xl text-slate-300"></i>
                                <p class="text-slate-500 font-medium">No hay estudiantes matriculados en este curso.</p>
                                <p class="text-xs text-slate-400">El administrador debe realizar las matrículas antes de poder calificar.</p>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($estudiantes as $est): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4">
                                <div class="font-bold text-slate-800"><?= htmlspecialchars($est['apellidos'] . ' ' . $est['nombres']) ?></div>
                            </td>
                            <td class="p-4">
                                <input 
                                    type="number" 
                                    name="notas[<?= $est['id_matricula'] ?>]" 
                                    value="<?= $est['valor'] ?>" 
                                    step="0.1" 
                                    min="0" 
                                    max="5" 
                                    class="w-20 px-3 py-2 border border-slate-300 rounded-lg text-center font-bold focus:ring-2 focus:ring-blue-600 outline-none <?= $est['valor'] < 3 && $est['valor'] !== null ? 'text-red-600' : 'text-blue-600' ?>"
                                >
                            </td>
                            <td class="p-4">
                                <input 
                                    type="text" 
                                    name="observaciones[<?= $est['id_matricula'] ?>]" 
                                    value="<?= htmlspecialchars($est['observacion'] ?? '') ?>" 
                                    placeholder="Opcional..."
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-600 outline-none"
                                >
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-slate-50 border-t flex justify-between items-center">
            <p class="text-xs text-slate-500 italic">
                * Las notas se guardan individualmente al pulsar el botón. Use punto (.) para decimales.
            </p>
            <div class="flex gap-3">
                <a href="docente.php?id_dmc=<?= $info['id_dmc'] ?>" class="px-6 py-3 bg-white border border-slate-300 rounded-xl font-semibold hover:bg-slate-100 transition">Regresar</a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg hover:bg-blue-700 transition transform hover:-translate-y-1">
                    Guardar Todas las Notas
                </button>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
