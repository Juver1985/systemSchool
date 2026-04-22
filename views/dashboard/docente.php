<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'docente') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Evaluacion.php';
require_once __DIR__ . '/../../models/Periodo.php';
require_once __DIR__ . '/../../controllers/DocenteController.php';

$id_dmc = $_GET['id_dmc'] ?? null;

$database = new Database();
$db = $database->conectar();

$evaluacionModel = new Evaluacion($db);
$periodoModel = new Periodo($db);
$periodos = $periodoModel->obtenerTodos();

$controller = new DocenteController();
$asignaciones = $controller->obtenerAsignaciones();

// Si no hay id_dmc, intentamos obtenerlo de los selectores si se enviaron (aunque lo ideal es por URL)
if (!$id_dmc && isset($_GET['sel_dmc'])) {
    $id_dmc = $_GET['sel_dmc'];
}

$infoAsig = null;
$evaluaciones = [];

if ($id_dmc) {
    // Obtener info de la asignación
    $sqlAsig = "SELECT dmc.id, m.nombre as materia_nombre, c.nombre as curso_nombre 
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
        $evaluaciones = $evaluacionModel->obtenerPorAsignacion($id_dmc);
        $titulo = "Evaluaciones: " . $infoAsig['materia_nombre'] . " (" . $infoAsig['curso_nombre'] . ")";
    } else {
        $id_dmc = null; // ID inválido o no pertenece al docente
    }
}

if (!$id_dmc) {
    $titulo = "Gestión de Evaluaciones";
}

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">Seleccionar <span class="text-blue-600">Curso y Materia</span></h2>
        <form action="docente.php" method="GET" class="grid md:grid-cols-3 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Asignación Académica</label>
                <select name="id_dmc" onchange="this.form.submit()" class="w-full px-4 py-3 border border-slate-300 rounded-xl bg-white outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Seleccione una materia...</option>
                    <?php foreach ($asignaciones as $asig): ?>
                        <option value="<?= $asig['id'] ?>" <?= $id_dmc == $asig['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($asig['curso_nombre'] . " (" . $asig['jornada'] . ") - " . $asig['materia_nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-slate-800 text-white px-6 py-3 rounded-xl hover:bg-slate-900 transition">
                Cargar Actividades
            </button>
        </form>
    </div>

    <?php if ($id_dmc && $infoAsig): ?>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-800"><?= htmlspecialchars($infoAsig['materia_nombre']) ?> <span class="text-blue-600"><?= htmlspecialchars($infoAsig['curso_nombre']) ?></span></h2>
            <p class="text-slate-500">Listado de evaluaciones y actividades programadas.</p>
        </div>
        <div class="flex gap-3">
            <a href="docente_estudiantes.php?id_dmc=<?= $id_dmc ?>" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-3 rounded-xl shadow-sm transition flex items-center gap-2 border border-slate-200">
                <i class="fas fa-users"></i>
                <span>Gestionar Estudiantes</span>
            </a>
            <button onclick="openModal('modalCrear')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow transition flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Nueva Evaluación</span>
            </button>
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

    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="p-4">Título</th>
                        <th class="p-4">Periodo</th>
                        <th class="p-4">Tipo</th>
                        <th class="p-4">Fecha</th>
                        <th class="p-4">Peso</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($evaluaciones as $eval): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 font-semibold text-slate-800"><?= htmlspecialchars($eval['titulo']) ?></td>
                        <td class="p-4 text-sm"><?= htmlspecialchars($eval['periodo_nombre']) ?></td>
                        <td class="p-4 capitalize text-sm"><?= htmlspecialchars(strtolower($eval['tipo'])) ?></td>
                        <td class="p-4 text-sm"><?= htmlspecialchars($eval['fecha']) ?></td>
                        <td class="p-4"><span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-md text-xs font-bold"><?= $eval['porcentaje'] ?>%</span></td>
                        <td class="p-4 text-center space-x-2">
                            <a href="docente_notas.php?id_evaluacion=<?= $eval['id_evaluacion'] ?>" class="px-3 py-1 rounded border text-green-600 border-green-300 hover:bg-green-50" title="Calificar">
                                <i class="fas fa-pen-to-square"></i>
                            </a>
                            <button onclick="openEditModal(<?= htmlspecialchars(json_encode($eval)) ?>)" class="px-3 py-1 rounded border text-blue-600 border-blue-300 hover:bg-blue-50" title="Editar">
                                <i class="fas fa-pen"></i>
                            </button>
                            <a href="../../controllers/DocenteEvaluacionController.php?accion=eliminar&id=<?= $eval['id_evaluacion'] ?>&id_dmc=<?= $id_dmc ?>" class="px-3 py-1 rounded border text-red-600 border-red-300 hover:bg-red-50" title="Eliminar" onclick="confirmarAccion(event, this.href, '¿Eliminar evaluación?', 'Se borrarán todas las notas asociadas.', 'error')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($evaluaciones)): ?>
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">
                            No has creado evaluaciones para esta materia.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear -->
<div id="modalCrear" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-200">
            <h3 class="text-2xl font-bold text-slate-800">Nueva Evaluación</h3>
            <button onclick="closeModal('modalCrear')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form action="../../controllers/DocenteEvaluacionController.php?accion=crear" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id_dmc" value="<?= $id_dmc ?>">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Título de la Evaluación *</label>
                <input type="text" name="titulo" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Periodo *</label>
                    <select name="id_periodo" required class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-white outline-none focus:ring-2 focus:ring-blue-600">
                        <?php foreach ($periodos as $p): ?>
                            <option value="<?= $p['id_periodo'] ?>"><?= htmlspecialchars($p['nombre']) ?> (<?= $p['anio'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tipo *</label>
                    <select name="tipo" required class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-white outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="TAREA">Tarea</option>
                        <option value="QUIZ">Quiz</option>
                        <option value="EXAMEN">Examen</option>
                        <option value="PROYECTO">Proyecto</option>
                        <option value="OTRO">Otro</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fecha *</label>
                    <input type="date" name="fecha" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Porcentaje % *</label>
                    <input type="number" name="porcentaje" required min="1" max="100" class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 mt-4">
                <button type="button" onclick="closeModal('modalCrear')" class="px-5 py-2 bg-slate-100 rounded-lg">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg">Guardar Evaluación</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar -->
<div id="modalEditar" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-200">
            <h3 class="text-2xl font-bold text-slate-800">Editar Evaluación</h3>
            <button onclick="closeModal('modalEditar')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form action="../../controllers/DocenteEvaluacionController.php?accion=editar" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id_dmc" value="<?= $id_dmc ?>">
            <input type="hidden" name="id_evaluacion" id="edit_id_evaluacion">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Título de la Evaluación *</label>
                <input type="text" name="titulo" id="edit_titulo" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Periodo *</label>
                    <select name="id_periodo" id="edit_id_periodo" required class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-white outline-none focus:ring-2 focus:ring-blue-600">
                        <?php foreach ($periodos as $p): ?>
                            <option value="<?= $p['id_periodo'] ?>"><?= htmlspecialchars($p['nombre']) ?> (<?= $p['anio'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tipo *</label>
                    <select name="tipo" id="edit_tipo" required class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-white outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="TAREA">Tarea</option>
                        <option value="QUIZ">Quiz</option>
                        <option value="EXAMEN">Examen</option>
                        <option value="PROYECTO">Proyecto</option>
                        <option value="OTRO">Otro</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fecha *</label>
                    <input type="date" name="fecha" id="edit_fecha" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Porcentaje % *</label>
                    <input type="number" name="porcentaje" id="edit_porcentaje" required min="1" max="100" class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 mt-4">
                <button type="button" onclick="closeModal('modalEditar')" class="px-5 py-2 bg-slate-100 rounded-lg">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg">Actualizar Evaluación</button>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function openEditModal(eval) {
        document.getElementById('edit_id_evaluacion').value = eval.id_evaluacion;
        document.getElementById('edit_titulo').value = eval.titulo;
        document.getElementById('edit_id_periodo').value = eval.id_periodo;
        document.getElementById('edit_tipo').value = eval.tipo;
        document.getElementById('edit_fecha').value = eval.fecha;
        document.getElementById('edit_porcentaje').value = eval.porcentaje;
        openModal('modalEditar');
    }

    function confirmarAccion(event, url, titulo, texto, icono) {
        event.preventDefault();
        Swal.fire({
            title: titulo, text: texto, icon: icono,
            showCancelButton: true, confirmButtonColor: '#2563eb', cancelButtonColor: '#dc2626',
            confirmButtonText: 'Sí, continuar', cancelButtonText: 'Cancelar'
        }).then((result) => { if (result.isConfirmed) window.location.href = url; });
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>