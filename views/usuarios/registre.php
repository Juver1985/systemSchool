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
    <title>EduGrade | Registro de Acudientes</title>
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

    <div class="bg-white w-full max-w-6xl rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[650px] border border-slate-100 mt-12 mb-8">
        
        <!-- Panel Izquierdo (Formulario) -->
        <div class="w-full md:w-3/5 p-8 md:p-12 lg:p-16 flex flex-col justify-center bg-white relative">
            
            <div class="relative z-10">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">Crear Cuenta de Acudiente</h1>
                    <p class="text-slate-500">Únete a EduGrade para realizar un seguimiento continuo del rendimiento académico.</p>
                </div>

                <form action="../../controllers/UsuarioController.php" method="POST" class="space-y-5">
                    <input type="hidden" name="rol" value="acudiente">

                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- Nombres -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nombres</label>
                            <input type="text" name="nombres" required maxlength="100"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all text-slate-700"
                                placeholder="Ej. Juan Carlos">
                        </div>

                        <!-- Apellidos -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Apellidos</label>
                            <input type="text" name="apellidos" required maxlength="100"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all text-slate-700"
                                placeholder="Ej. Pérez Rodríguez">
                        </div>
                    </div>

                    <!-- Correo -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" required maxlength="150"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all text-slate-700"
                                placeholder="correo@ejemplo.com">
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono / Celular</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="text" name="telefono" required maxlength="30"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all text-slate-700"
                                placeholder="Ej. 300 123 4567">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- Contraseña -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" required
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all text-slate-700"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Confirmar Contraseña</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-check-double"></i>
                                </span>
                                <input type="password" name="confirmar_password" required
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all text-slate-700"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <!-- Términos -->
                    <div class="flex items-start mt-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <div class="flex items-center h-5">
                            <input id="terms" type="checkbox" required class="w-4 h-4 text-primary-600 focus:ring-primary-500 border-slate-300 rounded cursor-pointer transition-colors mt-0.5">
                        </div>
                        <label for="terms" class="ml-3 text-sm text-slate-600 cursor-pointer">
                            Acepto los <a href="#" class="text-primary-600 hover:underline">términos de servicio</a> y la <a href="#" class="text-primary-600 hover:underline">política de tratamiento de datos académicos</a>.
                        </label>
                    </div>

                    <!-- Botón -->
                    <button
                        type="submit"
                        class="w-full bg-primary-600 text-white font-bold py-4 rounded-xl hover:bg-primary-700 transform hover:-translate-y-1 transition-all duration-300 shadow-lg shadow-primary-600/30 flex justify-center items-center gap-2 group mt-6"
                    >
                        Completar Registro
                        <i class="fas fa-user-plus group-hover:scale-110 transition-transform"></i>
                    </button>
                    
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-slate-600">¿Ya tienes una cuenta registrada? <a href="login.php" class="text-primary-600 font-bold hover:underline ml-1">Inicia sesión aquí</a></p>
                </div>
            </div>
        </div>

        <!-- Panel Derecho (Imagen/Branding) -->
        <div class="hidden md:flex md:w-2/5 relative bg-slate-900 overflow-hidden">
            <!-- Imagen de fondo (Padres/Profesores) -->
            <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1000&q=80" alt="Educación" class="absolute inset-0 w-full h-full object-cover object-center opacity-50">
            <!-- Capa de color degradado -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/90 via-primary-800/80 to-secondary-600/80 mix-blend-multiply"></div>
            
            <!-- Patrón decorativo -->
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>

            <div class="relative z-10 p-12 text-white flex flex-col justify-center items-center text-center h-full w-full">
                <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center text-white backdrop-blur-md border border-white/20 shadow-2xl mb-8">
                    <i class="fas fa-users text-3xl"></i>
                </div>
                
                <h3 class="text-3xl font-bold mb-4">Conectando Familias</h3>
                <p class="text-primary-100 text-lg leading-relaxed mb-8">
                    Sé parte activa de la educación. Monitorea calificaciones, recibe notificaciones al instante y comunícate de manera efectiva con la institución.
                </p>

                <div class="space-y-4 text-left w-full max-w-xs">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-secondary-400 text-xl"></i>
                        <span class="text-slate-100 font-medium">Notas en tiempo real</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-secondary-400 text-xl"></i>
                        <span class="text-slate-100 font-medium">Reportes de rendimiento</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-secondary-400 text-xl"></i>
                        <span class="text-slate-100 font-medium">Comunicación directa</span>
                    </div>
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
            confirmButtonText: 'Continuar',
            customClass: {
                confirmButton: 'bg-primary-600 text-white px-6 py-2 rounded-lg font-bold'
            }
        }).then(() => {
            <?php if (!empty($alert['redirect'])): ?>
                window.location.href = '<?= htmlspecialchars($alert['redirect']) ?>';
            <?php endif; ?>
        });
    </script>
    <?php endif; ?>

</body>
</html>