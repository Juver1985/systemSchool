<?php
$rol = $usuario['rol'];
$nombreCompleto = $usuario['nombres'] . ' ' . $usuario['apellidos'];
?>

<aside class="w-64 bg-gradient-to-b from-slate-900 to-blue-900 text-white shadow-2xl">
    <div class="h-24 flex items-center justify-center border-b border-white/10 px-4">
        <div class="text-center">
            <div class="text-lg font-bold leading-tight">Sistema de</div>
            <div class="text-2xl font-extrabold tracking-wide">CALIFICACIONES</div>
        </div>
    </div>

    <nav class="mt-4 px-3 space-y-2">
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-700/60 hover:bg-blue-700 transition">
            <i class="fas fa-gauge-high"></i>
            <span>Dashboard</span>
        </a>

        <?php if ($rol === 'administrador'): ?>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                <i class="fas fa-users"></i>
                <span>Usuarios</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                <i class="fas fa-school"></i>
                <span>Cursos</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                <i class="fas fa-book"></i>
                <span>Materias</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                <i class="fas fa-file-signature"></i>
                <span>Evaluaciones</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                <i class="fas fa-chart-pie"></i>
                <span>Reportes</span>
            </a>
        <?php endif; ?>

        <?php if ($rol === 'docente'): ?>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                <i class="fas fa-file-signature"></i>
                <span>Evaluaciones</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                <i class="fas fa-pen"></i>
                <span>Notas</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                <i class="fas fa-chart-line"></i>
                <span>Reportes</span>
            </a>
        <?php endif; ?>

        <?php if ($rol === 'estudiante' || $rol === 'acudiente'): ?>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                <i class="fas fa-file-lines"></i>
                <span>Boletín</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                <i class="fas fa-chart-column"></i>
                <span>Desempeño</span>
            </a>
        <?php endif; ?>

        <a href="../../controllers/AuthController.php?accion=logout" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-red-500/20 transition text-red-200">
            <i class="fas fa-right-from-bracket"></i>
            <span>Cerrar Sesión</span>
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
            <div class="text-right">
                <div class="font-semibold"><?= htmlspecialchars(ucfirst($rol)) ?></div>
                <div class="text-sm text-blue-100"><?= htmlspecialchars($nombreCompleto) ?></div>
            </div>
        </div>
    </header>

    <section class="p-8">