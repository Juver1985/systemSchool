<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Materia.php';

$database = new Database();
$db = $database->conectar();
$materiaModel = new Materia($db);
$materias = $materiaModel->obtenerTodos();

$titulo = "Gestión de Materias";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-4xl font-light text-slate-800">Gestión de <span class="font-bold">Materias</span></h2>
            <button onclick="openModal('modalCrear')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow inline-block">
                Agregar Materia
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
            <table id="materiasTable" class="w-full text-left border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="p-4">Nombre de la Materia</th>
                        <th class="p-4">Intensidad Horaria</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($materias as $materia): ?>
                    <tr>
                        <td class="p-4"><?= htmlspecialchars($materia['nombre']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($materia['intensidad_horaria']) ?> horas/semana</td>
                        <td class="p-4 text-center space-x-2">
                            <button type="button" onclick="openEditModal(<?= htmlspecialchars(json_encode($materia)) ?>)" class="px-3 py-1 rounded border text-blue-600 border-blue-300 hover:bg-blue-50" title="Editar">
                                <i class="fas fa-pen"></i>
                            </button>
                            <a href="../../controllers/AdminMateriaController.php?accion=eliminar&id=<?= $materia['id_materia'] ?>" class="px-3 py-1 rounded border text-red-600 border-red-300 hover:bg-red-50" title="Eliminar" onclick="confirmarAccion(event, this.href, '¿Eliminar materia?', '¿Seguro que desea eliminar esta materia?', 'error')">
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

<!-- Modal Crear Materia -->
<div id="modalCrear" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-200">
            <h3 class="text-2xl font-bold text-slate-800">Agregar Materia</h3>
            <button onclick="closeModal('modalCrear')" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="../../controllers/AdminMateriaController.php?accion=crear" method="POST" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nombre de la Materia *</label>
                <input type="text" name="nombre" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Intensidad Horaria (H/S)</label>
                <input type="number" name="intensidad_horaria" value="4" class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('modalCrear')" class="px-5 py-2 bg-slate-100 rounded-lg">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg">Guardar Materia</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Materia -->
<div id="modalEditar" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-200">
            <h3 class="text-2xl font-bold text-slate-800">Editar Materia</h3>
            <button onclick="closeModal('modalEditar')" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="../../controllers/AdminMateriaController.php?accion=editar" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id_materia" id="edit_id_materia">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nombre de la Materia *</label>
                <input type="text" name="nombre" id="edit_nombre" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Intensidad Horaria (H/S)</label>
                <input type="number" name="intensidad_horaria" id="edit_intensidad" class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('modalEditar')" class="px-5 py-2 bg-slate-100 rounded-lg">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg">Actualizar Materia</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function openEditModal(materia) {
        document.getElementById('edit_id_materia').value = materia.id_materia;
        document.getElementById('edit_nombre').value = materia.nombre;
        document.getElementById('edit_intensidad').value = materia.intensidad_horaria;
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
