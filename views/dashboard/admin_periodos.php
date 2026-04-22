<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Periodo.php';

$database = new Database();
$db = $database->conectar();
$periodoModel = new Periodo($db);
$periodos = $periodoModel->obtenerTodos();

$titulo = "Gestión de Periodos";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-4xl font-light text-slate-800">Gestión de <span class="font-bold">Periodos Académicos</span></h2>
            <button onclick="openModal('modalCrear')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow inline-block">
                Agregar Periodo
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
            <table id="periodosTable" class="w-full text-left border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="p-4">Nombre</th>
                        <th class="p-4">Año</th>
                        <th class="p-4">Fecha Inicio</th>
                        <th class="p-4">Fecha Fin</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($periodos as $periodo): ?>
                    <tr>
                        <td class="p-4 font-bold text-blue-600"><?= htmlspecialchars($periodo['nombre']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($periodo['anio']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($periodo['fecha_inicio']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($periodo['fecha_fin']) ?></td>
                        <td class="p-4">
                            <?php if ($periodo['cerrado']): ?>
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">CERRADO</span>
                            <?php else: ?>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">ABIERTO</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center space-x-2">
                            <button type="button" onclick="openEditModal(<?= htmlspecialchars(json_encode($periodo)) ?>)" class="px-3 py-1 rounded border text-blue-600 border-blue-300 hover:bg-blue-50" title="Editar">
                                <i class="fas fa-pen"></i>
                            </button>
                            <a href="../../controllers/AdminPeriodoController.php?accion=toggleCierre&id=<?= $periodo['id_periodo'] ?>&estado=<?= $periodo['cerrado'] ?>" class="px-3 py-1 rounded border <?= $periodo['cerrado'] ? 'text-green-600 border-green-300' : 'text-orange-600 border-orange-300' ?> hover:bg-slate-50" title="<?= $periodo['cerrado'] ? 'Abrir Periodo' : 'Cerrar Periodo' ?>">
                                <i class="fas <?= $periodo['cerrado'] ? 'fa-lock-open' : 'fa-lock' ?>"></i>
                            </a>
                            <a href="../../controllers/AdminPeriodoController.php?accion=eliminar&id=<?= $periodo['id_periodo'] ?>" class="px-3 py-1 rounded border text-red-600 border-red-300 hover:bg-red-50" title="Eliminar" onclick="confirmarAccion(event, this.href, '¿Eliminar periodo?', '¿Seguro que desea eliminar este periodo?', 'error')">
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

<!-- Modal Crear Periodo -->
<div id="modalCrear" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-200">
            <h3 class="text-2xl font-bold text-slate-800">Agregar Periodo</h3>
            <button onclick="closeModal('modalCrear')" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="../../controllers/AdminPeriodoController.php?accion=crear" method="POST" class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre (Ej: P1, P2) *</label>
                    <input type="text" name="nombre" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Año *</label>
                    <input type="number" name="anio" value="<?= date('Y') ?>" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Inicio *</label>
                <input type="date" name="fecha_inicio" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Fin *</label>
                <input type="date" name="fecha_fin" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('modalCrear')" class="px-5 py-2 bg-slate-100 rounded-lg">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg">Guardar Periodo</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Periodo -->
<div id="modalEditar" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-200">
            <h3 class="text-2xl font-bold text-slate-800">Editar Periodo</h3>
            <button onclick="closeModal('modalEditar')" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="../../controllers/AdminPeriodoController.php?accion=editar" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id_periodo" id="edit_id_periodo">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre *</label>
                    <input type="text" name="nombre" id="edit_nombre" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Año *</label>
                    <input type="number" name="anio" id="edit_anio" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Inicio *</label>
                <input type="date" name="fecha_inicio" id="edit_inicio" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Fecha Fin *</label>
                <input type="date" name="fecha_fin" id="edit_fin" required class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('modalEditar')" class="px-5 py-2 bg-slate-100 rounded-lg">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg">Actualizar Periodo</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function openEditModal(periodo) {
        document.getElementById('edit_id_periodo').value = periodo.id_periodo;
        document.getElementById('edit_nombre').value = periodo.nombre;
        document.getElementById('edit_anio').value = periodo.anio;
        document.getElementById('edit_inicio').value = periodo.fecha_inicio;
        document.getElementById('edit_fin').value = periodo.fecha_fin;
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
