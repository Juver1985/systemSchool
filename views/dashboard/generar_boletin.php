<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../config/database.php';

$id_matricula = $_GET['id_matricula'] ?? null;
$id_periodo = $_GET['id_periodo'] ?? null;

if (!$id_matricula) {
    die("ID de matrícula no proporcionado.");
}

$database = new Database();
$db = $database->conectar();

// 1. Obtener información del estudiante y curso
$sqlInfo = "SELECT u.nombres, u.apellidos, e.id_estudiante, e.codigo_estudiantil, e.grado_actual, 
                   c.nombre as curso_nombre, c.jornada, c.anio,
                   p.nombre as periodo_nombre
            FROM matriculas m
            JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cursos c ON m.id_curso = c.id_curso
            LEFT JOIN periodos p ON p.id_periodo = :id_p
            WHERE m.id_matricula = :id_m";
$stmtInfo = $db->prepare($sqlInfo);
$stmtInfo->execute([':id_m' => $id_matricula, ':id_p' => $id_periodo]);
$estudiante = $stmtInfo->fetch(PDO::FETCH_ASSOC);

if (!$estudiante) {
    die("Estudiante no encontrado.");
}

$id_estudiante = $estudiante['id_estudiante'];

// 2. Obtener todas las materias de todas las matrículas activas del estudiante
$sqlMaterias = "SELECT DISTINCT m.id_materia, m.nombre as materia_nombre, 
                       u_doc.nombres as doc_nombres, u_doc.apellidos as doc_apellidos,
                       dmc.id as id_dmc
                FROM docente_materia_curso dmc
                JOIN materias m ON dmc.id_materia = m.id_materia
                JOIN usuarios u_doc ON dmc.id_docente_usuario = u_doc.id_usuario
                JOIN matriculas mat ON dmc.id_curso = mat.id_curso
                WHERE mat.id_estudiante = :id_e AND mat.estado = 'ACTIVA'";
$stmtMaterias = $db->prepare($sqlMaterias);
$stmtMaterias->execute([':id_e' => $id_estudiante]);
$materias = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);

$boletinData = [];
$promedioGeneral = 0;
$totalMaterias = 0;

foreach ($materias as $mat) {
    // Obtener evaluaciones de esta materia para el periodo
    $sqlEval = "SELECT id_evaluacion, porcentaje FROM evaluaciones 
                WHERE id_docente_materia_curso = :id_dmc";
    if ($id_periodo) {
        $sqlEval .= " AND id_periodo = :id_p";
    }
    $stmtEval = $db->prepare($sqlEval);
    $paramsEval = [':id_dmc' => $mat['id_dmc']];
    if ($id_periodo) $paramsEval[':id_p'] = $id_periodo;
    $stmtEval->execute($paramsEval);
    $evals = $stmtEval->fetchAll(PDO::FETCH_ASSOC);

    $notaFinalMateria = 0;
    $sumaPesos = 0;
    
    foreach ($evals as $ev) {
        // Buscar la nota en cualquiera de las matrículas activas del estudiante
        $sqlN = "SELECT n.valor FROM notas n
                 JOIN matriculas m ON n.id_matricula = m.id_matricula
                 WHERE n.id_evaluacion = :id_e AND m.id_estudiante = :id_est AND m.estado = 'ACTIVA'";
        $stmtN = $db->prepare($sqlN);
        $stmtN->execute([':id_e' => $ev['id_evaluacion'], ':id_est' => $id_estudiante]);
        $n = $stmtN->fetch(PDO::FETCH_ASSOC);
        
        $valor = $n ? $n['valor'] : 0;
        $notaFinalMateria += ($valor * ($ev['porcentaje'] / 100));
        $sumaPesos += $ev['porcentaje'];
    }

    $boletinData[] = [
        'materia' => $mat['materia_nombre'],
        'docente' => $mat['doc_apellidos'] . ' ' . $mat['doc_nombres'],
        'nota' => round($notaFinalMateria, 2),
        'desempeno' => obtenerDesempeno($notaFinalMateria)
    ];

    $promedioGeneral += $notaFinalMateria;
    $totalMaterias++;
}

$promedioFinal = $totalMaterias > 0 ? round($promedioGeneral / $totalMaterias, 2) : 0;

function obtenerDesempeno($nota) {
    if ($nota >= 4.6) return "Superior";
    if ($nota >= 4.0) return "Alto";
    if ($nota >= 3.0) return "Básico";
    return "Bajo";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boletín - <?= $estudiante['apellidos'] ?> <?= $estudiante['nombres'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- html2pdf Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .print-shadow { shadow: none; border: 1px solid #e2e8f0; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 md:p-10">

    <div class="max-w-4xl mx-auto no-print mb-6 flex justify-between items-center">
        <a href="javascript:history.back()" class="text-slate-600 hover:text-slate-900 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
        <button id="btnDownload" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold shadow-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Descargando...
        </button>
    </div>

    <!-- Boletín Contenedor -->
    <div id="boletin-content" class="max-w-4xl mx-auto bg-white shadow-2xl rounded-sm overflow-hidden print-shadow border-t-8 border-blue-800">
        <!-- Encabezado -->
        <div class="p-8 border-b border-slate-200 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4">
                <img src="../../img/logo.png" alt="Logo" class="h-20 w-auto">
                <div>
                    <h1 class="text-2xl font-black text-blue-900 uppercase">Institución Educativa Rubennet</h1>
                    <p class="text-sm text-slate-500 font-medium">Resolución No. 1234 de 2026 - Secretaría de Educación</p>
                    <p class="text-xs text-slate-400">Calle 123 # 45-67 | Tel: +57 123 456 7890</p>
                </div>
            </div>
            <div class="text-center md:text-right">
                <div class="bg-blue-50 px-4 py-2 rounded-lg border border-blue-100 inline-block">
                    <span class="text-blue-800 font-bold text-lg uppercase tracking-wider">Boletín Académico</span>
                </div>
                <p class="text-slate-500 mt-2 font-bold"><?= $estudiante['periodo_nombre'] ? $estudiante['periodo_nombre'] : 'Consolidado Anual' ?></p>
            </div>
        </div>

        <!-- Información del Estudiante -->
        <div class="p-8 bg-slate-50 grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 border-b border-slate-200 text-sm">
            <div class="flex justify-between border-b border-slate-200 pb-1">
                <span class="text-slate-500 font-semibold uppercase text-[10px]">Estudiante:</span>
                <span class="text-slate-800 font-bold uppercase"><?= $estudiante['apellidos'] ?> <?= $estudiante['nombres'] ?></span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-1">
                <span class="text-slate-500 font-semibold uppercase text-[10px]">Código:</span>
                <span class="text-slate-800 font-bold"><?= $estudiante['codigo_estudiantil'] ?></span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-1">
                <span class="text-slate-500 font-semibold uppercase text-[10px]">Curso:</span>
                <span class="text-slate-800 font-bold"><?= $estudiante['curso_nombre'] ?></span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-1">
                <span class="text-slate-500 font-semibold uppercase text-[10px]">Jornada:</span>
                <span class="text-slate-800 font-bold"><?= $estudiante['jornada'] ?></span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-1">
                <span class="text-slate-500 font-semibold uppercase text-[10px]">Grado:</span>
                <span class="text-slate-800 font-bold"><?= $estudiante['grado_actual'] ?></span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-1">
                <span class="text-slate-500 font-semibold uppercase text-[10px]">Año Lectivo:</span>
                <span class="text-slate-800 font-bold uppercase"><?= $estudiante['anio'] ?></span>
            </div>
        </div>

        <!-- Tabla de Calificaciones -->
        <div class="p-8">
            <table class="w-full border-collapse border border-slate-300 text-sm">
                <thead>
                    <tr class="bg-blue-900 text-white uppercase text-xs">
                        <th class="p-3 border border-blue-900 text-left">Área / Asignatura</th>
                        <th class="p-3 border border-blue-900 text-left">Docente</th>
                        <th class="p-3 border border-blue-900 text-center w-24">Valoración</th>
                        <th class="p-3 border border-blue-900 text-center w-32">Desempeño</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($boletinData as $data): ?>
                    <tr class="odd:bg-white even:bg-slate-50">
                        <td class="p-3 border border-slate-300 font-bold text-slate-800"><?= $data['materia'] ?></td>
                        <td class="p-3 border border-slate-300 text-slate-600 italic"><?= $data['docente'] ?></td>
                        <td class="p-3 border border-slate-300 text-center font-black text-lg <?= $data['nota'] < 3 ? 'text-red-600' : 'text-blue-700' ?>">
                            <?= number_format($data['nota'], 2) ?>
                        </td>
                        <td class="p-3 border border-slate-300 text-center">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase border 
                                <?= $data['desempeno'] === 'Superior' ? 'bg-green-100 text-green-800 border-green-200' : 
                                   ($data['desempeno'] === 'Alto' ? 'bg-blue-100 text-blue-800 border-blue-200' : 
                                   ($data['desempeno'] === 'Básico' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 'bg-red-100 text-red-800 border-red-200')) ?>">
                                <?= $data['desempeno'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="bg-slate-800 text-white font-bold">
                        <td colspan="2" class="p-4 text-right uppercase text-xs">Promedio General Académico:</td>
                        <td class="p-4 text-center text-xl"><?= number_format($promedioFinal, 2) ?></td>
                        <td class="p-4 text-center uppercase text-xs"><?= obtenerDesempeno($promedioFinal) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Observaciones y Firmas -->
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-12 mt-8">
            <div class="border-t-2 border-slate-300 pt-4 text-center">
                <p class="text-sm font-bold text-slate-800 uppercase">Rectoría / Dirección</p>
                <p class="text-xs text-slate-400 mt-1">Sello Institucional</p>
            </div>
            <div class="border-t-2 border-slate-300 pt-4 text-center">
                <p class="text-sm font-bold text-slate-800 uppercase">Secretaría Académica</p>
                <p class="text-xs text-slate-400 mt-1">Firma Autorizada</p>
            </div>
        </div>

        <!-- Pie de Página -->
        <div class="bg-slate-100 p-4 text-[9px] text-slate-400 text-center uppercase tracking-widest border-t border-slate-200">
            Este documento carece de validez legal sin los sellos y firmas originales de la institución. Generado el: <?= date('d/m/Y H:i') ?>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            const element = document.getElementById('boletin-content');
            const opt = {
                margin:       0.5,
                filename:     'Boletin_<?= str_replace(' ', '_', $estudiante['apellidos']) ?>_<?= $estudiante['codigo_estudiantil'] ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            // Ejecutar la descarga automática
            html2pdf().set(opt).from(element).save().then(() => {
                document.getElementById('btnDownload').innerHTML = '<i class="fas fa-check mr-2"></i> Descargado';
                document.getElementById('btnDownload').classList.replace('bg-blue-600', 'bg-green-600');
            });
        });
    </script>
</body>
</html>
