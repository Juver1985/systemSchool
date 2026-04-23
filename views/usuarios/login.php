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
    <title>EduGrade - Iniciar Sesión</title>
    <link rel="shortcut icon" type="image/png" href="../../img/ico.png">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        },
                        secondary: {
                            400: '#2dd4bf',
                            500: '#14b8a6',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">

    <!-- Volver al inicio -->
    <a href="../../public/index.php" class="absolute top-6 left-6 text-slate-500 hover:text-primary-600 transition-colors flex items-center gap-2 font-medium z-10">
        <i class="fas fa-arrow-left"></i> Volver al inicio
    </a>

    <div class="bg-white w-full max-w-5xl rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[600px] border border-slate-100">
        
        <!-- Panel Izquierdo (Imagen/Branding) -->
        <div class="hidden md:flex md:w-1/2 relative bg-slate-900 overflow-hidden">
            <!-- Imagen de fondo -->
            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80" alt="Estudiantes" class="absolute inset-0 w-full h-full object-cover object-center opacity-40">
            <!-- Capa de color degradado -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/90 via-primary-700/80 to-secondary-500/80 mix-blend-multiply"></div>
            
            <div class="relative z-10 p-12 text-white flex flex-col justify-between h-full w-full">
                <div>
                    <!-- Logo / Título -->
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white backdrop-blur-md border border-white/30 shadow-lg">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                        <span class="font-bold text-2xl tracking-tight">Edu<span class="text-secondary-400">Grade</span></span>
                    </div>

                    <h2 class="text-4xl font-bold mb-6 leading-tight">Gestión Académica <br>Simplificada.</h2>
                    <p class="text-primary-100 text-lg leading-relaxed max-w-md">
                        Accede a tu panel centralizado para gestionar calificaciones, revisar reportes en tiempo real y seguir impulsando el éxito académico de tu institución.
                    </p>
                </div>

                <div class="flex items-center space-x-4 bg-white/10 p-4 rounded-2xl backdrop-blur-md border border-white/10 mt-12">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-secondary-400 flex-shrink-0">
                        <i class="fas fa-shield-alt text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold">Acceso Seguro</p>
                        <p class="text-xs text-primary-200">Cifrado de grado bancario para todos tus datos.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Derecho (Formulario) -->
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center bg-white relative">
            
            <!-- Elemento decorativo -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary-50 rounded-bl-full -z-0 opacity-50"></div>

            <div class="relative z-10">
                <div class="text-center md:text-left mb-10">
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">Iniciar Sesión</h1>
                    <p class="text-slate-500">Ingresa tus credenciales para continuar</p>
                </div>

                <form action="../../controllers/AuthController.php" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Correo Electrónico</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input
                                type="email"
                                name="email"
                                placeholder="ejemplo@correo.com"
                                required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all text-slate-700"
                            >
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-slate-700">Contraseña</label>
                            <a href="#" class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition-colors">¿Olvidaste tu contraseña?</a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all text-slate-700"
                            >
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="remember" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-slate-300 rounded cursor-pointer transition-colors">
                        <label for="remember" class="ml-2 block text-sm text-slate-600 cursor-pointer">Recordar mi sesión en este equipo</label>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-primary-600 text-white font-bold py-4 rounded-xl hover:bg-primary-700 transform hover:-translate-y-1 transition-all duration-300 shadow-lg shadow-primary-600/30 flex justify-center items-center gap-2 group mt-8"
                    >
                        Entrar al Sistema
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    
                </form>

                <div class="mt-8 text-center bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <p class="text-sm text-slate-600">¿Aún no tienes una cuenta institucional? <br class="md:hidden"><a href="registre.php" class="text-primary-600 font-bold hover:underline ml-1">Regístrate como acudiente</a></p>
                </div>
            </div>
        </div>
    </div>

    <?php if ($alert): ?>
    <script>
        Swal.fire({
            icon: '<?= htmlspecialchars($alert['icon']) ?>',
            title: '<?= htmlspecialchars($alert['title']) ?>',
            text: '<?= htmlspecialchars($alert['text']) ?>',
            confirmButtonText: 'Aceptar',
            customClass: {
                confirmButton: 'bg-primary-600 text-white px-6 py-2 rounded-lg font-bold'
            }
        });
    </script>
    <?php endif; ?>

</body>
</html>