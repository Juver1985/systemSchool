<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Matricula.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../models/Curso.php';

$database = new Database();
$db = $database->conectar();

$matriculaModel = new Matricula($db);
$usuarioModel = new Usuario($db);
$cursoModel = new Curso($db);

$matriculas = $matriculaModel->obtenerTodas();

// Para el modal, necesitamos estudiantes que no estén matriculados en cursos activos (o simplemente todos para simplificar)
// Vamos a obtener los que tienen rol 'estudiante'
$sqlEstudiantes = "SELECT e.id_estudiante, u.nombres, u.apellidos 
                  FROM estudiantes e 
                  JOIN usuarios u ON e.id_usuario = u.id_usuario 
                  WHERE u.activo = 1";
$stmtEst = $db->prepare($sqlEstudiantes);
$stmtEst->execute();
$estudiantes = $stmtEst->fetchAll(PDO::FETCH_ASSOC);

$cursos = $cursoModel->obtenerTodos();

$titulo = "Gestión de Matrículas";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-4xl font-light text-slate-800">Matrículas de <span class="font-bold">Estudiantes</span></h2>
            <button onclick="openModal('modalCrear')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow inline-block">
                Nueva Matrícula
            </button>
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

        <div class="overflow-x-auto">
            <table id="matriculasTable" class="w-full text-left border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="p-4">Estudiante</th>
                        <th class="p-4">Curso</th>
                        <th class="p-4">Año</th>
                        <th class="p-4">Fecha</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($matriculas as $mat): ?>
                    <tr>
                        <td class="p-4 font-semibold"><?= htmlspecialchars($mat['apellidos'] . ' ' . $mat['nombres']) ?></td>
                        <td class="p-4 text-blue-700 font-bold"><?= htmlspecialchars($mat['curso_nombre']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($mat['anio']) ?></td>
                        <td class="p-4 text-sm text-slate-500"><?= htmlspecialchars($mat['fecha_matricula']) ?></td>
                        <td class="p-4">
                            <?php if ($mat['estado'] === 'ACTIVA'): ?>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">ACTIVA</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">RETIRADA</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center space-x-2">
                            <a href="../../controllers/AdminMatriculaController.php?accion=toggleEstado&id=<?= $mat['id_matricula'] ?>&estado=<?= $mat['estado'] ?>" class="px-3 py-1 rounded border <?= $mat['estado'] === 'ACTIVA' ? 'text-orange-600 border-orange-300' : 'text-green-600 border-green-300' ?> hover:bg-slate-50" title="Cambiar Estado">
                                <i class="fas <?= $mat['estado'] === 'ACTIVA' ? 'fa-user-minus' : 'fa-user-check' ?>"></i>
                            </a>
                            <a href="../../controllers/AdminMatriculaController.php?accion=eliminar&id=<?= $mat['id_matricula'] ?>" class="px-3 py-1 rounded border text-red-600 border-red-300 hover:bg-red-50" title="Eliminar" onclick="confirmarAccion(event, this.href, '¿Eliminar matrícula?', 'Esta acción borrará el registro de matrícula permanentemente.', 'error')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear Matrícula -->
<div id="modalCrear" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-200">
            <h3 class="text-2xl font-bold text-slate-800">Nueva Matrícula</h3>
            <button onclick="closeModal('modalCrear')" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="../../controllers/AdminMatriculaController.php?accion=crear" method="POST" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Estudiante *</label>
                <select name="id_estudiante" required class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-white outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Seleccione Estudiante...</option>
                    <?php foreach ($estudiantes as $est): ?>
                        <option value="<?= $est['id_estudiante'] ?>"><?= htmlspecialchars($est['apellidos'] . ' ' . $est['nombres']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Curso *</label>
                <select name="id_curso" required class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-white outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Seleccione Curso...</option>
                    <?php foreach ($cursos as $cur): ?>
                        <option value="<?= $cur['id_curso'] ?>"><?= htmlspecialchars($cur['nombre']) ?> (<?= $cur['anio'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('modalCrear')" class="px-5 py-2 bg-slate-100 rounded-lg">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg">Confirmar Matrícula</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

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
