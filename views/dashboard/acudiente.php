<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'acudiente') {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$db = $database->conectar();

$id_usuario = $_SESSION['usuario']['id_usuario'];

// Procesar búsqueda por código
if (isset($_POST['buscar_codigo'])) {
    $codigo = trim($_POST['codigo_busqueda']);
    if (!empty($codigo)) {
        // 1. Buscar estudiante por código
        $sqlBusca = "SELECT e.id_estudiante FROM estudiantes e WHERE e.codigo_estudiantil = :cod";
        $stmtBusca = $db->prepare($sqlBusca);
        $stmtBusca->execute([':cod' => $codigo]);
        $estEncontrado = $stmtBusca->fetch(PDO::FETCH_ASSOC);

        if ($estEncontrado) {
            $id_est_found = $estEncontrado['id_estudiante'];
            // 2. Obtener id_acudiente
            $stmtAcuId = $db->prepare("SELECT id_acudiente FROM acudientes WHERE id_usuario = :id_u");
            $stmtAcuId->execute([':id_u' => $id_usuario]);
            $acuRow = $stmtAcuId->fetch(PDO::FETCH_ASSOC);
            
            if ($acuRow) {
                $id_acu_real = $acuRow['id_acudiente'];
                // 3. Verificar si ya están vinculados
                $stmtCheck = $db->prepare("SELECT * FROM acudiente_estudiante WHERE id_acudiente = :id_a AND id_estudiante = :id_e");
                $stmtCheck->execute([':id_a' => $id_acu_real, ':id_e' => $id_est_found]);
                
                if (!$stmtCheck->fetch()) {
                    // 4. Vincular automáticamente
                    $stmtIns = $db->prepare("INSERT INTO acudiente_estudiante (id_acudiente, id_estudiante, parentesco) VALUES (:id_a, :id_e, 'Acudiente')");
                    $stmtIns->execute([':id_a' => $id_acu_real, ':id_e' => $id_est_found]);
                }
            }
        }
    }
}

// 1. Obtener ID de acudiente
$sqlAcu = "SELECT id_acudiente FROM acudientes WHERE id_usuario = :id_u";
$stmtAcu = $db->prepare($sqlAcu);
$stmtAcu->execute([':id_u' => $id_usuario]);
$acudiente = $stmtAcu->fetch(PDO::FETCH_ASSOC);

$misEstudiantes = [];

if ($acudiente) {
    $id_acudiente = $acudiente['id_acudiente'];
    
    // 2. Obtener estudiantes vinculados (Agrupados por estudiante para evitar duplicados si están en varios cursos)
    $sqlEst = "SELECT e.id_estudiante, u.nombres, u.apellidos, e.codigo_estudiantil, 
                      MAX(m.id_matricula) as id_matricula, 
                      GROUP_CONCAT(c.nombre SEPARATOR ' / ') as curso_nombre, 
                      ae.parentesco
               FROM acudiente_estudiante ae
               JOIN estudiantes e ON ae.id_estudiante = e.id_estudiante
               JOIN usuarios u ON e.id_usuario = u.id_usuario
               LEFT JOIN matriculas m ON e.id_estudiante = m.id_estudiante AND m.estado = 'ACTIVA'
               LEFT JOIN cursos c ON m.id_curso = c.id_curso
               WHERE ae.id_acudiente = :id_a
               GROUP BY e.id_estudiante";
    $stmtEst = $db->prepare($sqlEst);
    $stmtEst->execute([':id_a' => $id_acudiente]);
    $misEstudiantes = $stmtEst->fetchAll(PDO::FETCH_ASSOC);
}

$titulo = "Panel del Acudiente";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h2 class="text-3xl font-bold text-slate-800">Bienvenido, <span class="text-blue-600"><?= htmlspecialchars($_SESSION['usuario']['nombres']) ?></span></h2>
            <p class="text-slate-500 mt-2">Desde aquí puede realizar el seguimiento académico de sus acudidos.</p>
        </div>
        <form action="" method="POST" class="flex w-full md:w-auto gap-2">
            <div class="relative flex-1 md:w-64">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="codigo_busqueda" placeholder="Código del estudiante..." required class="w-full pl-11 pr-4 py-3 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-600 bg-white">
            </div>
            <button type="submit" name="buscar_codigo" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                Vincular
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <?php foreach ($misEstudiantes as $est): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="p-6 bg-gradient-to-br from-blue-600 to-indigo-700 text-white">
                <div class="flex justify-between items-start">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-2xl border-2 border-white/30">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <span class="px-3 py-1 bg-white/20 rounded-full text-[10px] font-bold uppercase tracking-wider border border-white/30">
                        <?= htmlspecialchars($est['parentesco']) ?>
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-xl font-bold"><?= htmlspecialchars($est['nombres'] . ' ' . $est['apellidos']) ?></h3>
                    <p class="text-blue-100 text-sm font-medium">Código: <?= htmlspecialchars($est['codigo_estudiantil']) ?></p>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Curso Actual:</span>
                    <span class="font-bold text-slate-800"><?= $est['curso_nombre'] ? htmlspecialchars($est['curso_nombre']) : 'No matriculado' ?></span>
                </div>
                
                <div class="pt-4 border-t border-slate-100 flex flex-col gap-3">
                    <?php if ($est['id_matricula']): ?>
                        <a href="generar_boletin.php?id_matricula=<?= $est['id_matricula'] ?>" target="_blank" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition flex items-center justify-center gap-2">
                            <i class="fas fa-file-pdf"></i>
                            Descargar Boletín Académico
                        </a>
                        <p class="text-[10px] text-center text-slate-400">
                            * El boletín incluye el consolidado de todas las materias y promedios.
                        </p>
                    <?php else: ?>
                        <div class="py-3 px-4 bg-yellow-50 text-yellow-700 rounded-xl text-xs text-center font-medium border border-yellow-100">
                            El estudiante aún no tiene una matrícula activa para este año.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($misEstudiantes)): ?>
        <div class="md:col-span-2 py-20 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-users-slash text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800">No tiene estudiantes vinculados</h3>
            <p class="text-slate-500 mt-2 max-w-sm mx-auto">
                Para ver el rendimiento académico de sus hijos, el administrador del sistema debe vincular su cuenta con los estudiantes respectivos.
            </p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>