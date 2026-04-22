<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'docente') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/DocenteController.php';

$id_dmc = $_GET['id_dmc'] ?? null;

if (!$id_dmc) {
    header("Location: docente_dashboard.php");
    exit;
}

$database = new Database();
$db = $database->conectar();

// Info de la asignación
$sqlAsig = "SELECT dmc.id, dmc.id_curso, m.nombre as materia_nombre, c.nombre as curso_nombre, c.jornada
            FROM docente_materia_curso dmc
            JOIN materias m ON dmc.id_materia = m.id_materia
            JOIN cursos c ON dmc.id_curso = c.id_curso
            WHERE dmc.id = :id AND dmc.id_docente_usuario = :id_doc";
$stmtAsig = $db->prepare($sqlAsig);
$stmtAsig->execute([':id' => $id_dmc, ':id_doc' => $_SESSION['usuario']['id_usuario']]);
$infoAsig = $stmtAsig->fetch(PDO::FETCH_ASSOC);

if (!$infoAsig) {
    header("Location: docente_dashboard.php");
    exit;
}

$id_curso = $infoAsig['id_curso'];

// Obtener estudiantes ya matriculados en este curso
$sqlMat = "SELECT m.id_matricula, u.nombres, u.apellidos, e.codigo_estudiantil
           FROM matriculas m
           JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
           JOIN usuarios u ON e.id_usuario = u.id_usuario
           WHERE m.id_curso = :id_curso AND m.estado = 'ACTIVA'";
$stmtMat = $db->prepare($sqlMat);
$stmtMat->execute([':id_curso' => $id_curso]);
$matriculados = $stmtMat->fetchAll(PDO::FETCH_ASSOC);

// Obtener estudiantes globales NO matriculados en este curso
$sqlGlobal = "SELECT e.id_estudiante, u.nombres, u.apellidos, e.codigo_estudiantil
              FROM estudiantes e
              JOIN usuarios u ON e.id_usuario = u.id_usuario
              WHERE e.id_estudiante NOT IN (SELECT id_estudiante FROM matriculas WHERE id_curso = :id_curso)
              ORDER BY u.apellidos, u.nombres";
$stmtGlobal = $db->prepare($sqlGlobal);
$stmtGlobal->execute([':id_curso' => $id_curso]);
$estudiantesDisponibles = $stmtGlobal->fetchAll(PDO::FETCH_ASSOC);

// Procesar agregar estudiante
if (isset($_POST['agregar_estudiante'])) {
    $id_est = $_POST['id_estudiante'];
    $sqlIns = "INSERT INTO matriculas (id_estudiante, id_curso, fecha_matricula, estado) VALUES (:id_e, :id_c, CURDATE(), 'ACTIVA')";
    $stmtIns = $db->prepare($sqlIns);
    if ($stmtIns->execute([':id_e' => $id_est, ':id_c' => $id_curso])) {
        $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Éxito', 'text' => 'Estudiante agregado al curso'];
    } else {
        $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo agregar al estudiante'];
    }
    header("Location: docente_estudiantes.php?id_dmc=$id_dmc");
    exit;
}

// Procesar retirar estudiante
if (isset($_GET['retirar'])) {
    $id_m = $_GET['retirar'];
    $sqlDel = "DELETE FROM matriculas WHERE id_matricula = :id"; // En este caso borramos la matrícula
    $stmtDel = $db->prepare($sqlDel);
    $stmtDel->execute([':id' => $id_m]);
    header("Location: docente_estudiantes.php?id_dmc=$id_dmc");
    exit;
}

$titulo = "Gestionar Estudiantes";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-800">Estudiantes de <span class="text-blue-600"><?= htmlspecialchars($infoAsig['curso_nombre']) ?></span></h2>
            <p class="text-slate-500"><?= htmlspecialchars($infoAsig['materia_nombre']) ?> - <?= htmlspecialchars($infoAsig['jornada']) ?></p>
        </div>
        <a href="docente.php?id_dmc=<?= $id_dmc ?>" class="px-6 py-3 bg-slate-800 text-white rounded-xl font-bold">Regresar a Evaluaciones</a>
    </div>

    <?php if (isset($_SESSION['alert'])): ?>
        <script>
            Swal.fire({
                icon: '<?= $_SESSION['alert']['icon'] ?>',
                title: '<?= $_SESSION['alert']['title'] ?>',
                text: '<?= $_SESSION['alert']['text'] ?>'
            });
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Lista de Matriculados -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Alumnos en el Curso</h3>
            </div>
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="p-4">Estudiante</th>
                        <th class="p-4">Código</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($matriculados as $m): ?>
                    <tr>
                        <td class="p-4 font-medium text-slate-800"><?= htmlspecialchars($m['apellidos'] . ' ' . $m['nombres']) ?></td>
                        <td class="p-4 text-sm text-slate-500"><?= htmlspecialchars($m['codigo_estudiantil']) ?></td>
                        <td class="p-4 text-center flex items-center justify-center gap-3">
                            <a href="generar_boletin.php?id_matricula=<?= $m['id_matricula'] ?>" target="_blank" class="text-blue-600 hover:text-blue-800" title="Generar Boletín">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <a href="?id_dmc=<?= $id_dmc ?>&retirar=<?= $m['id_matricula'] ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('¿Retirar estudiante del curso?')">
                                <i class="fas fa-user-minus"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($matriculados)): ?>
                    <tr><td colspan="3" class="p-8 text-center text-slate-400">No hay estudiantes en este curso.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Agregar Estudiante -->
        <div class="bg-white rounded-2xl shadow border border-slate-200 p-6 h-fit">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-user-plus text-blue-600"></i> Agregar Estudiante
            </h3>
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Buscar Estudiante Existente</label>
                    <select name="id_estudiante" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">Seleccione...</option>
                        <?php foreach ($estudiantesDisponibles as $e): ?>
                            <option value="<?= $e['id_estudiante'] ?>">
                                <?= htmlspecialchars($e['apellidos'] . ' ' . $e['nombres'] . ' (' . $e['codigo_estudiantil'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="agregar_estudiante" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg hover:bg-blue-700 transition">
                    Inscribir en el Curso
                </button>
            </form>
            <p class="mt-4 text-xs text-slate-400 leading-relaxed italic">
                * Solo aparecerán estudiantes que ya han sido registrados en el sistema por el administrador.
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
