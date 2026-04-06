<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'docente') {
    header("Location: ../usuarios/login.php");
    exit;
}

$titulo = "Registrar Evaluaciones";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="space-y-8">
    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">Seleccionar Curso</label>
                <select class="w-full border rounded-xl px-4 py-3">
                    <option>6A</option>
                    <option>7A</option>
                    <option>8B</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">Seleccionar Materia</label>
                <select class="w-full border rounded-xl px-4 py-3">
                    <option>Matemáticas</option>
                    <option>Historia</option>
                    <option>Español</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">Nombre Evaluación</label>
                <input type="text" class="w-full border rounded-xl px-4 py-3" placeholder="Ej. Parcial 1">
            </div>

            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">Porcentaje</label>
                <input type="number" class="w-full border rounded-xl px-4 py-3" placeholder="20">
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow">
                Guardar Evaluación
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="p-4">Evaluación</th>
                        <th class="p-4">Curso</th>
                        <th class="p-4">Materia</th>
                        <th class="p-4">Porcentaje</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr>
                        <td class="p-4">Quiz 1</td>
                        <td class="p-4">6A</td>
                        <td class="p-4">Matemáticas</td>
                        <td class="p-4">20%</td>
                        <td class="p-4 text-center space-x-2">
                            <button class="px-3 py-1 rounded border text-blue-600 border-blue-300"><i class="fas fa-pen"></i></button>
                            <button class="px-3 py-1 rounded border text-slate-600 border-slate-300"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-4">Parcial</td>
                        <td class="p-4">7A</td>
                        <td class="p-4">Historia</td>
                        <td class="p-4">25%</td>
                        <td class="p-4 text-center space-x-2">
                            <button class="px-3 py-1 rounded border text-blue-600 border-blue-300"><i class="fas fa-pen"></i></button>
                            <button class="px-3 py-1 rounded border text-slate-600 border-slate-300"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>