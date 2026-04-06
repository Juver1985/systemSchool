<?php
session_start();
$alert = $_SESSION['alert'] ?? null;
unset($_SESSION['alert']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" type="image/png" href="../../img/ico.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[550px]">

        <div class="hidden md:flex md:w-1/2 gradient-bg p-12 text-white flex-col justify-between">
            <div>
                <h2 class="text-3xl font-bold mb-4">¡Bienvenido de nuevo!</h2>
                <p class="text-blue-100 leading-relaxed">
                    Accede a tu panel para gestionar calificaciones, revisar reportes y seguir impulsando el éxito académico.
                </p>
            </div>
            <div>
                <img src="../../img/logo.png" alt="Logo" class="w-50 h-50">
            </div>

            <div class="flex items-center space-x-4">
                <div class="bg-white/20 p-3 rounded-lg backdrop-blur-sm">
                    <i class="fas fa-user-shield text-2xl"></i>
                </div>
                <p class="text-sm font-light italic">Acceso seguro con cifrado de datos institucionales.</p>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            <div class="text-center md:text-left mb-8">
                <div class="flex items-center justify-center md:justify-start text-blue-700 text-3xl font-bold mb-2">
                   <img src="../../img/logo.png" alt="Logo" class="w-20 h-20">
                </div>
                <h1 class="text-xl font-semibold text-gray-700">Iniciar Sesión</h1>
                <p class="text-gray-400 text-sm">Ingresa tus credenciales para continuar</p>
            </div>

            <form action="../../controllers/AuthController.php" method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Correo Electrónico</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input
                            type="email"
                            name="email"
                            placeholder="ejemplo@correo.com"
                            required
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Contraseña</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        >
                    </div>
                    <div class="text-right mt-2">
                        <a href="#" class="text-xs text-blue-600 hover:underline">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-600">Recordar mi sesión</label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transform hover:-translate-y-1 transition-all duration-200 shadow-lg"
                >
                    Entrar al Sistema
                </button>
                
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">Regístrate aquí <a href="registre.php" class="text-blue-600 font-medium hover:underline">Registrarse</a></p>
            </div>
        </div>
    </div>

    <?php if ($alert): ?>
    <script>
        Swal.fire({
            icon: '<?= htmlspecialchars($alert['icon']) ?>',
            title: '<?= htmlspecialchars($alert['title']) ?>',
            text: '<?= htmlspecialchars($alert['text']) ?>',
            confirmButtonText: 'Aceptar'
        });
    </script>
    <?php endif; ?>

</body>
</html>