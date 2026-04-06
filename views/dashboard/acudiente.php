<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'acudiente') {
    header("Location: ../usuarios/login.php");
    exit;
}

$titulo = "Seguimiento Académico";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <h2 class="text-4xl font-light text-slate-800 mb-6">Seguimiento del Estudiante</h2>

        <div class="grid md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">Estudiante</label>
                <select class="w-full border rounded-xl px-4 py-3">
                    <option>Juan Pérez</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">Periodo</label>
                <select class="w-full border rounded-xl px-4 py-3">
                    <option>Periodo 1</option>
                    <option>Periodo 2</option>
                </select>
            </div>

            <div>
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow">
                    Consultar Boletín
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="p-4">Materia</th>
                        <th class="p-4">Evaluación</th>
                        <th class="p-4">Nota</th>
                        <th class="p-4">Observación</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr>
                        <td class="p-4">Matemáticas</td>
                        <td class="p-4">Parcial 1</td>
                        <td class="p-4"><span class="px-4 py-2 rounded-lg bg-green-500 text-white">4.5</span></td>
                        <td class="p-4">Buen desempeño</td>
                    </tr>
                    <tr>
                        <td class="p-4">Historia</td>
                        <td class="p-4">Taller</td>
                        <td class="p-4"><span class="px-4 py-2 rounded-lg bg-blue-500 text-white">4.0</span></td>
                        <td class="p-4">Puede mejorar</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>