<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Usuario.php';

$database = new Database();
$db = $database->conectar();
$usuarioModel = new Usuario($db);
$usuarios = $usuarioModel->obtenerTodos();

$titulo = "Dashboard Administrador";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-4xl font-light text-slate-800">Gestión de <span class="font-bold">Usuarios</span></h2>
            <button onclick="openModal('modalCrear')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow inline-block">
                Agregar Usuario
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
            <table id="usuariosTable" class="w-full text-left border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="p-4">Nombre Completo</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Rol</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($usuarios as $ruben): ?>
                    <tr class="<?= $ruben['activo'] == 0 ? 'bg-red-50 opacity-75' : '' ?>">
                        <td class="p-4"><?= htmlspecialchars($ruben['nombres'] . ' ' . $ruben['apellidos']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($ruben['email']) ?></td>
                        <td class="p-4 capitalize"><?= htmlspecialchars($ruben['rol']) ?></td>
                        <td class="p-4">
                            <?php if ($ruben['activo'] == 1): ?>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Activo</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center space-x-2">
                            <?php if ($ruben['rol'] === 'acudiente'): ?>
                                <button type="button" onclick="openVincularModal(<?= $ruben['id_usuario'] ?>, '<?= htmlspecialchars($ruben['nombres'] . ' ' . $ruben['apellidos']) ?>')" class="px-3 py-1 rounded border text-indigo-600 border-indigo-300 hover:bg-indigo-50" title="Vincular Estudiantes">
                                    <i class="fas fa-link"></i>
                                </button>
                            <?php endif; ?>
                            <button type="button" onclick="openEditModal(<?= htmlspecialchars(json_encode($ruben)) ?>)" class="px-3 py-1 rounded border text-blue-600 border-blue-300 hover:bg-blue-50" title="Editar">
                                <i class="fas fa-pen"></i>
                            </button>
                            <?php if ($ruben['activo'] == 1): ?>
                                <a href="../../controllers/AdminUsuarioController.php?accion=toggleEstado&id=<?= $ruben['id_usuario'] ?>&estado=1" class="px-3 py-1 rounded border text-red-600 border-red-300 hover:bg-red-50" title="Desactivar" onclick="confirmarAccion(event, this.href, '¿Desactivar usuario?', '¿Seguro que desea desactivar este usuario?', 'warning')">
                                    <i class="fas fa-ban"></i>
                                </a>
                            <?php else: ?>
                                <a href="../../controllers/AdminUsuarioController.php?accion=toggleEstado&id=<?= $ruben['id_usuario'] ?>&estado=0" class="px-3 py-1 rounded border text-green-600 border-green-300 hover:bg-green-50" title="Activar" onclick="confirmarAccion(event, this.href, '¿Activar usuario?', '¿Seguro que desea activar este usuario?', 'info')">
                                    <i class="fas fa-check"></i>
                                </a>
                            <?php endif; ?>
                            <a href="../../controllers/AdminUsuarioController.php?accion=eliminar&id=<?= $ruben['id_usuario'] ?>" class="px-3 py-1 rounded border text-red-600 border-red-300 hover:bg-red-50" title="Eliminar" onclick="confirmarAccion(event, this.href, '¿Eliminar permanentemente?', 'Esta acción no se puede deshacer. ¿Seguro que desea eliminar a este usuario?', 'error')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="5" class="p-4 text-center text-slate-500">No hay usuarios registrados.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-3xl font-light text-slate-800">Últimos Reportes</h3>
            <button class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-3 rounded-xl border">
                Consultar Reporte
            </button>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between border rounded-xl p-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-blue-700">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">Análisis General de Usuarios</p>
                        <p class="text-sm text-slate-500">Reporte administrativo del sistema</p>
                    </div>
                </div>
                <button class="bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-lg border">Ver</button>
            </div>

            <div class="flex items-center justify-between border rounded-xl p-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-blue-700">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">Reporte de Rendimiento</p>
                        <p class="text-sm text-slate-500">Resumen académico institucional</p>
                    </div>
                </div>
                <button class="bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-lg border">Abrir</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Usuario -->
<div id="modalCrear" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-200">
            <h3 class="text-2xl font-bold text-slate-800">Agregar Usuario</h3>
            <button onclick="closeModal('modalCrear')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="../../controllers/AdminUsuarioController.php?accion=crear" method="POST" class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombres *</label>
                    <input type="text" name="nombres" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Apellidos *</label>
                    <input type="text" name="apellidos" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico *</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña *</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Rol *</label>
                <select name="rol" id="rolSelect" required onchange="toggleExtraFields(this.value)" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none bg-white">
                    <option value="">Seleccione un rol...</option>
                    <option value="administrador">Administrador</option>
                    <option value="docente">Docente</option>
                    <option value="estudiante">Estudiante</option>
                    <option value="acudiente">Acudiente</option>
                </select>
            </div>

            <!-- Campos extra para Estudiantes -->
            <div id="estudianteFields" class="hidden space-y-4 pt-4 border-t border-slate-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Código Estudiantil *</label>
                        <input type="text" name="codigo_estudiantil" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Grado Actual</label>
                        <input type="number" name="grado_actual" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">
                </div>
            </div>

            <!-- Campos extra para Acudientes -->
            <div id="acudienteFields" class="hidden pt-4 border-t border-slate-100">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono / Celular *</label>
                    <input type="text" name="telefono" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 mt-6">
                <button type="button" onclick="closeModal('modalCrear')" class="px-5 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Cancelar</button>
                <button type="submit" class="px-5 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition-colors">Guardar Usuario</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Vincular Acudiente-Estudiante -->
<div id="modalVincular" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-200">
            <h3 class="text-xl font-bold text-slate-800">Vincular Estudiante</h3>
            <button onclick="closeModal('modalVincular')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="../../controllers/AdminUsuarioController.php?accion=vincular_acudiente" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id_usuario_acudiente" id="vincular_id_usuario">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Acudiente</label>
                <input type="text" id="vincular_nombre_acudiente" readonly class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-600 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Seleccionar Estudiante</label>
                <select name="id_estudiante" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none bg-white">
                    <option value="">Seleccione un estudiante...</option>
                    <?php 
                    $db_v = (new Database())->conectar();
                    $stmt_v = $db_v->prepare("SELECT e.id_estudiante, u.nombres, u.apellidos FROM estudiantes e JOIN usuarios u ON e.id_usuario = u.id_usuario ORDER BY u.apellidos");
                    $stmt_v->execute();
                    $estudiantes_v = $stmt_v->fetchAll(PDO::FETCH_ASSOC);
                    foreach($estudiantes_v as $ev): ?>
                        <option value="<?= $ev['id_estudiante'] ?>"><?= htmlspecialchars($ev['apellidos'] . ' ' . $ev['nombres']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Parentesco</label>
                <select name="parentesco" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none bg-white">
                    <option value="Padre">Padre</option>
                    <option value="Madre">Madre</option>
                    <option value="Tutor">Tutor</option>
                    <option value="Abuelo/a">Abuelo/a</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 mt-6">
                <button type="button" onclick="closeModal('modalVincular')" class="px-5 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Cancelar</button>
                <button type="submit" class="px-5 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition-colors font-bold">Vincular</button>
            </div>
        </form>
    </div>
</div>

</script>

<!-- Modal Editar Usuario -->
<div id="modalEditar" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-200">
            <h3 class="text-2xl font-bold text-slate-800">Editar Usuario</h3>
            <button onclick="closeModal('modalEditar')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="../../controllers/AdminUsuarioController.php?accion=editar" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id_usuario" id="edit_id_usuario">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombres *</label>
                    <input type="text" name="nombres" id="edit_nombres" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Apellidos *</label>
                    <input type="text" name="apellidos" id="edit_apellidos" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                <input type="email" id="edit_email" readonly class="w-full px-4 py-2 border border-slate-200 bg-slate-50 text-slate-500 rounded-lg outline-none cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nueva Contraseña <span class="font-normal text-slate-400">(opcional)</span></label>
                <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Rol *</label>
                <select name="rol" id="edit_rol" required onchange="toggleExtraFieldsEdit(this.value)" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none bg-white">
                    <option value="administrador">Administrador</option>
                    <option value="docente">Docente</option>
                    <option value="estudiante">Estudiante</option>
                    <option value="acudiente">Acudiente</option>
                </select>
            </div>

            <!-- Campos extra para Edición -->
            <div id="estudianteFieldsEdit" class="hidden space-y-4 pt-4 border-t border-slate-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Código Estudiantil *</label>
                        <input type="text" name="codigo_estudiantil" id="edit_codigo_estudiantil" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Grado Actual</label>
                        <input type="number" name="grado_actual" id="edit_grado_actual" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="edit_fecha_nacimiento" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">
                </div>
            </div>

            <div id="acudienteFieldsEdit" class="hidden pt-4 border-t border-slate-100">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono / Celular *</label>
                    <input type="text" name="telefono" id="edit_telefono" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 mt-6">
                <button type="button" onclick="closeModal('modalEditar')" class="px-5 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Cancelar</button>
                <button type="submit" class="px-5 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition-colors">Actualizar Usuario</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function toggleExtraFields(rol, mode = '') {
        const estFields = document.getElementById('estudianteFields' + mode);
        const acuFields = document.getElementById('acudienteFields' + mode);
        
        if (estFields) estFields.classList.add('hidden');
        if (acuFields) acuFields.classList.add('hidden');
        
        if (rol === 'estudiante' && estFields) {
            estFields.classList.remove('hidden');
        } else if (rol === 'acudiente' && acuFields) {
            acuFields.classList.remove('hidden');
        }
    }

    function toggleExtraFieldsEdit(rol) {
        toggleExtraFields(rol, 'Edit');
    }

    function openEditModal(usuario) {
        document.getElementById('edit_id_usuario').value = usuario.id_usuario;
        document.getElementById('edit_nombres').value = usuario.nombres;
        document.getElementById('edit_apellidos').value = usuario.apellidos;
        document.getElementById('edit_email').value = usuario.email;
        document.getElementById('edit_rol').value = usuario.rol;
        
        // Cargar campos extra
        if (usuario.rol === 'estudiante') {
            document.getElementById('edit_codigo_estudiantil').value = usuario.codigo_estudiantil || '';
            document.getElementById('edit_grado_actual').value = usuario.grado_actual || '';
            document.getElementById('edit_fecha_nacimiento').value = usuario.fecha_nacimiento || '';
        } else if (usuario.rol === 'acudiente') {
            document.getElementById('edit_telefono').value = usuario.telefono || '';
        }
        
        toggleExtraFieldsEdit(usuario.rol);
        openModal('modalEditar');
    }
</script>


<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#usuariosTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            responsive: true,
            dom: '<"flex flex-col md:flex-row justify-between gap-4 mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4"ip>',
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: 4 }
            ]
        });
    });

    function openVincularModal(id, nombre) {
        document.getElementById('vincular_id_usuario').value = id;
        document.getElementById('vincular_nombre_acudiente').value = nombre;
        openModal('modalVincular');
    }
</script>

<style>
    /* Ajustes básicos para que DataTables se vea bien con Tailwind */
    .dataTables_wrapper .dataTables_length select {
        padding: 0.25rem 2rem 0.25rem 0.5rem;
        border-radius: 0.375rem;
        border: 1px solid #cbd5e1;
    }
    .dataTables_wrapper .dataTables_filter input {
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        border: 1px solid #cbd5e1;
        margin-left: 0.5rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #2563eb !important;
        color: white !important;
        border: 1px solid #2563eb !important;
        border-radius: 0.375rem;
    }
</style>


<?php require_once __DIR__ . '/../layouts/footer.php'; ?>