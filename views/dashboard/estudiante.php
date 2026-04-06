<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'estudiante') {
    header("Location: ../usuarios/login.php");
    exit;
}

$titulo = "Boletín Académico";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <h2 class="text-4xl font-light text-slate-800 mb-6">Boletín Académico</h2>

        <div class="grid md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">Periodo</label>
                <select class="w-full border rounded-xl px-4 py-3">
                    <option>Periodo 1</option>
                    <option>Periodo 2</option>
                    <option>Periodo 3</option>
                    <option>Periodo 4</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">Año</label>
                <select class="w-full border rounded-xl px-4 py-3">
                    <option>2026</option>
                    <option>2025</option>
                </select>
            </div>

            <div>
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow">
                    Generar Boletín
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="p-4">Periodo</th>
                        <th class="p-4">Materia</th>
                        <th class="p-4">Tipo Evaluación</th>
                        <th class="p-4">Nota</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr>
                        <td class="p-4">Periodo 1</td>
                        <td class="p-4">Matemáticas</td>
                        <td class="p-4">Examen</td>
                        <td class="p-4">
                            <span class="px-4 py-2 rounded-lg bg-green-500 text-white font-semibold">4.8</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-4">Periodo 1</td>
                        <td class="p-4">Historia</td>
                        <td class="p-4">Taller</td>
                        <td class="p-4">
                            <span class="px-4 py-2 rounded-lg bg-emerald-500 text-white font-semibold">4.2</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-4">Periodo 1</td>
                        <td class="p-4">Español</td>
                        <td class="p-4">Quiz</td>
                        <td class="p-4">
                            <span class="px-4 py-2 rounded-lg bg-sky-500 text-white font-semibold">3.9</span>
                        </td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-4 font-bold">Promedio</td>
                        <td class="p-4 font-bold" colspan="2">Promedio del Periodo</td>
                        <td class="p-4">
                            <span class="px-4 py-2 rounded-lg bg-blue-600 text-white font-bold">4.3</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>