<?php
$rol = $usuario['rol'];
$nombreCompleto = $usuario['nombres'] . ' ' . $usuario['apellidos'];
?>

<aside class="w-64 bg-white shadow-xl border-r border-gray-200">
    <div class="h-32 flex flex-col items-center justify-center border-b border-gray-100 px-4 gap-1">
        <img src="../../img/logo.png" alt="Logo" class="h-16 w-auto object-contain">
        <div class="text-center text-blue-800">
            <div class="text-[10px] font-bold leading-tight uppercase tracking-[0.2em] opacity-70">Sistema de</div>
            <div class="text-xl font-black tracking-tight leading-none">CALIFICACIONES</div>
        </div>
    </div>

    <nav class="mt-4 px-3 space-y-2">
      

        <?php if ($rol === 'administrador'): ?>
            <a href="admin_dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            <a href="admin.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-users"></i>
                <span>Usuarios</span>
            </a>
            <a href="admin_cursos.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-school"></i>
                <span>Cursos</span>
            </a>
            <a href="admin_materias.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-book"></i>
                <span>Materias</span>
            </a>
            <a href="admin_periodos.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-calendar-alt"></i>
                <span>Periodos</span>
            </a>
            <a href="admin_asignaciones.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Asignaciones</span>
            </a>
            <a href="admin_matriculas.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-user-graduate"></i>
                <span>Matrículas</span>
            </a>
        <?php endif; ?>

        <?php if ($rol === 'docente'): ?>
            <a href="docente_dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            <a href="docente.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-file-signature"></i>
                <span>Evaluaciones</span>
            </a>
            <a href="docente_reporte.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-chart-line"></i>
                <span>Reportes</span>
            </a>
        <?php endif; ?>

        <?php if ($rol === 'estudiante'): ?>
            <a href="estudiante.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-file-lines"></i>
                <span>Mi Boletín</span>
            </a>
        <?php endif; ?>

        <?php if ($rol === 'acudiente'): ?>
            <a href="acudiente.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-users"></i>
                <span>Mis Acudidos</span>
            </a>
        <?php endif; ?>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-chart-column"></i>
                <span>Desempeño</span>
            </a>

    </nav>
</aside>

<main class="flex-1">
    <header class="h-24 bg-gradient-to-r from-blue-800 to-sky-700 text-white px-8 flex items-center justify-between shadow-md">
        <h1 class="text-4xl font-light"><?= htmlspecialchars($titulo) ?></h1>

        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-white text-blue-700 flex items-center justify-center text-xl">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="text-right flex flex-col">
                <div class="font-bold text-lg leading-tight"><?= htmlspecialchars(ucfirst($rol)) ?></div>
                <div class="text-sm text-blue-100 opacity-90"><?= htmlspecialchars($nombreCompleto) ?></div>
                <a href="../../controllers/AuthController.php?accion=logout" class="mt-1 text-xs bg-red-500/20 hover:bg-red-500/40 text-red-100 px-2 py-1 rounded-md transition-all flex items-center justify-end gap-1 self-end w-fit">
                    <i class="fas fa-power-off text-[10px]"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </div>
        </div>
    </header>

    <section class="p-8">