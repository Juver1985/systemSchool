<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Asignacion.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../models/Materia.php';
require_once __DIR__ . '/../../models/Curso.php';

$database = new Database();
$db = $database->conectar();

$asignacionModel = new Asignacion($db);
$usuarioModel = new Usuario($db);
$materiaModel = new Materia($db);
$cursoModel = new Curso($db);

$asignaciones = $asignacionModel->obtenerTodas();
$usuarios = $usuarioModel->obtenerTodos();
$docentes = array_filter($usuarios, fn($u) => $u['rol'] === 'docente' && $u['activo'] == 1);
$materias = $materiaModel->obtenerTodos();
$cursos = $cursoModel->obtenerTodos();

$titulo = "Asignación de Docentes";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-4xl font-light text-slate-800">Asignación de <span class="font-bold">Docentes</span></h2>
            <button onclick="openModal('modalCrear')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow inline-block">
                Nueva Asignación
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
            <table id="asignacionesTable" class="w-full text-left border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="p-4">Docente</th>
                        <th class="p-4">Materia</th>
                        <th class="p-4">Curso</th>
                        <th class="p-4">Año</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($asignaciones as $asig): ?>
                    <tr>
                        <td class="p-4 font-semibold text-slate-800"><?= htmlspecialchars($asig['nombres'] . ' ' . $asig['apellidos']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($asig['materia_nombre']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($asig['curso_nombre']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($asig['anio']) ?></td>
                        <td class="p-4 text-center">
                            <a href="../../controllers/AdminAsignacionController.php?accion=eliminar&id=<?= $asig['id'] ?>" class="px-3 py-1 rounded border text-red-600 border-red-300 hover:bg-red-50" title="Eliminar" onclick="confirmarAccion(event, this.href, '¿Eliminar asignación?', 'Esta acción quitará al docente de esta materia y curso.', 'error')">
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

<!-- Modal Crear Asignación -->
<div id="modalCrear" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-200">
            <h3 class="text-2xl font-bold text-slate-800">Nueva Asignación</h3>
            <button onclick="closeModal('modalCrear')" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="../../controllers/AdminAsignacionController.php?accion=crear" method="POST" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Docente *</label>
                <select name="id_docente" required class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-white outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Seleccione Docente...</option>
                    <?php foreach ($docentes as $doc): ?>
                        <option value="<?= $doc['id_usuario'] ?>"><?= htmlspecialchars($doc['nombres'] . ' ' . $doc['apellidos']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Materia *</label>
                <select name="id_materia" required class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-white outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Seleccione Materia...</option>
                    <?php foreach ($materias as $mat): ?>
                        <option value="<?= $mat['id_materia'] ?>"><?= htmlspecialchars($mat['nombre']) ?></option>
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
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Año Académico *</label>
                <input type="number" name="anio" value="<?= date('Y') ?>" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('modalCrear')" class="px-5 py-2 bg-slate-100 rounded-lg">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg">Guardar Asignación</button>
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
