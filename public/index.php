<!DOCTYPE html>
<html lang="es" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduGrade - Sistema de Calificación en Línea</title>
    <link rel="shortcut icon" type="image/png" href="../img/ico.png">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

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
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(135deg, #4f46e5 0%, #14b8a6 100%);
        }
        .shape-blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
        }
    </style>
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased overflow-x-hidden">

    <!-- Navigation -->
    <nav class="glass-nav fixed w-full top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-secondary-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-500/30">
                        <i class="fas fa-graduation-cap text-xl"></i>
                    </div>
                    <span class="font-bold text-2xl tracking-tight text-slate-900">Edu<span class="text-primary-600">Grade</span></span>
                </div>
                
                <div class="hidden md:flex space-x-8 items-center font-medium text-slate-600">
                    <a href="#inicio" class="hover:text-primary-600 transition-colors">Inicio</a>
                    <a href="#caracteristicas" class="hover:text-primary-600 transition-colors">Características</a>
                    <a href="#nosotros" class="hover:text-primary-600 transition-colors">Nosotros</a>
                    
                    <div class="h-6 w-px bg-slate-300 mx-2"></div>
                    
                    <a href="../views/usuarios/login.php"
                        class="group relative inline-flex items-center justify-center px-6 py-2.5 font-semibold text-white transition-all duration-200 bg-primary-600 border border-transparent rounded-full hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-600/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-600">
                        Ingresar
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button class="text-slate-600 hover:text-primary-600 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <!-- Background Blobs -->
        <div class="shape-blob bg-primary-300 w-96 h-96 rounded-full top-0 left-[-10%] mix-blend-multiply animate-blob"></div>
        <div class="shape-blob bg-secondary-300 w-96 h-96 rounded-full top-20 right-[-10%] mix-blend-multiply animate-blob" style="animation-delay: 2s;"></div>
        <div class="shape-blob bg-purple-300 w-96 h-96 rounded-full bottom-[-20%] left-[20%] mix-blend-multiply animate-blob" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div data-aos="fade-up" data-aos-duration="1000">
                <span class="inline-block py-1 px-3 rounded-full bg-primary-50 border border-primary-100 text-primary-600 text-sm font-semibold mb-6 tracking-wide uppercase">
                    La plataforma educativa del futuro
                </span>
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 text-slate-900 leading-tight">
                    Gestión Académica <br class="hidden md:block" />
                    <span class="text-gradient">Simplificada e Inteligente</span>
                </h1>
                <p class="mt-4 max-w-2xl text-lg md:text-xl text-slate-600 mx-auto mb-10 leading-relaxed">
                    Transforma la experiencia educativa con la plataforma más robusta para el seguimiento, evaluación y análisis del rendimiento escolar en tiempo real.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="../views/usuarios/login.php" class="w-full sm:w-auto px-8 py-4 bg-primary-600 text-white font-bold rounded-full shadow-xl shadow-primary-600/30 hover:bg-primary-700 hover:-translate-y-1 transition-all duration-300">
                        Comenzar Ahora
                    </a>
                    <a href="#caracteristicas" class="w-full sm:w-auto px-8 py-4 bg-white text-slate-700 font-bold rounded-full shadow-md border border-slate-200 hover:border-primary-300 hover:text-primary-600 transition-all duration-300 group">
                        Conoce Más <i class="fas fa-chevron-down ml-2 group-hover:translate-y-1 transition-transform text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Dashboard Preview Image/Mockup -->
            <div class="mt-20 relative mx-auto max-w-5xl" data-aos="fade-up" data-aos-delay="300" data-aos-duration="1200">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-50 via-transparent to-transparent z-10 h-full w-full bottom-0"></div>
                <div class="rounded-2xl border border-slate-200 bg-white/50 backdrop-blur-sm p-2 shadow-2xl overflow-hidden ring-1 ring-slate-900/5">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=2000&q=80" alt="Dashboard Preview" class="rounded-xl w-full object-cover object-top h-[300px] md:h-[500px] opacity-90">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-10 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-slate-100">
                <div data-aos="fade-up" data-aos-delay="100">
                    <div class="text-4xl font-extrabold text-primary-600 mb-2">+10k</div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wide">Estudiantes Activos</div>
                </div>
                <div data-aos="fade-up" data-aos-delay="200">
                    <div class="text-4xl font-extrabold text-primary-600 mb-2">99%</div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wide">Satisfacción</div>
                </div>
                <div data-aos="fade-up" data-aos-delay="300">
                    <div class="text-4xl font-extrabold text-primary-600 mb-2">24/7</div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wide">Soporte Técnico</div>
                </div>
                <div data-aos="fade-up" data-aos-delay="400">
                    <div class="text-4xl font-extrabold text-primary-600 mb-2">100%</div>
                    <div class="text-sm font-medium text-slate-500 uppercase tracking-wide">Datos Seguros</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="caracteristicas" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-primary-600 font-semibold tracking-wide uppercase mb-3">Por qué elegirnos</h2>
                <h3 class="text-3xl md:text-5xl font-bold text-slate-900 mb-6">Herramientas diseñadas para el éxito educativo</h3>
                <p class="text-lg text-slate-600">Nuestra plataforma ofrece un conjunto integral de funcionalidades para optimizar el tiempo de docentes, informar a padres y motivar a estudiantes.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 text-2xl mb-6 group-hover:bg-primary-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Automatización Total</h4>
                    <p class="text-slate-600 leading-relaxed">Reduce el tiempo de carga de notas y generación de reportes en un 60%. Enfócate en enseñar, no en la burocracia.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-secondary-50 rounded-2xl flex items-center justify-center text-secondary-500 text-2xl mb-6 group-hover:bg-secondary-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Seguridad de Nivel Bancario</h4>
                    <p class="text-slate-600 leading-relaxed">Garantizamos la integridad y confidencialidad absoluta de los registros académicos con encriptación avanzada.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 text-2xl mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Análisis y Seguimiento</h4>
                    <p class="text-slate-600 leading-relaxed">Gráficos y estadísticas detalladas para detectar estudiantes en riesgo y premiar la excelencia académica en tiempo real.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Split Section -->
    <section id="nosotros" class="py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <!-- Image Side -->
                <div class="w-full lg:w-1/2 relative" data-aos="fade-right">
                    <div class="absolute inset-0 bg-gradient-to-tr from-primary-600 to-secondary-400 rounded-[2rem] transform rotate-3 scale-105 opacity-20"></div>
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80" alt="Estudiantes colaborando" class="rounded-[2rem] relative z-10 shadow-2xl object-cover h-[500px] w-full">
                    
                    <!-- Floating Card -->
                    <div class="absolute -bottom-6 -right-6 bg-white p-6 rounded-2xl shadow-xl z-20 animate-bounce" style="animation-duration: 3s;">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                <i class="fas fa-check text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 font-medium">Calificaciones</p>
                                <p class="text-lg font-bold text-slate-900">Actualizadas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Text Side -->
                <div class="w-full lg:w-1/2 space-y-12" data-aos="fade-left">
                    <div>
                        <div class="inline-flex items-center justify-center p-3 bg-primary-50 rounded-xl text-primary-600 mb-6">
                            <i class="fas fa-bullseye text-2xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">Nuestra Misión</h2>
                        <p class="text-lg text-slate-600 leading-relaxed">
                            Proveer una herramienta tecnológica intuitiva que facilite el proceso de calificación, promoviendo la retroalimentación oportuna y el crecimiento académico continuo de nuestra comunidad educativa, conectando a padres, alumnos y profesores en un solo ecosistema.
                        </p>
                    </div>

                    <div>
                        <div class="inline-flex items-center justify-center p-3 bg-secondary-50 rounded-xl text-secondary-500 mb-6">
                            <i class="fas fa-eye text-2xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">Nuestra Visión</h2>
                        <p class="text-lg text-slate-600 leading-relaxed">
                            Ser el sistema de gestión académica referente a nivel nacional, reconocido por su innovación constante, seguridad inquebrantable de datos y capacidad para transformar la información cruda en historias de éxito escolar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-slate-900 z-0"></div>
        <!-- Decorative bg -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 z-0"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-600 rounded-full mix-blend-multiply filter blur-3xl opacity-50 z-0"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 z-0"></div>

        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center" data-aos="zoom-in">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">¿Listo para transformar tu institución?</h2>
            <p class="text-xl text-slate-300 mb-10">Únete a cientos de instituciones que ya están optimizando su gestión académica con EduGrade.</p>
            <a href="../views/usuarios/login.php" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-slate-900 bg-white rounded-full shadow-2xl hover:bg-slate-50 hover:scale-105 transition-all duration-300">
                Acceder a la plataforma
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 py-16 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary-600 to-secondary-500 rounded-lg flex items-center justify-center text-white">
                            <i class="fas fa-graduation-cap text-sm"></i>
                        </div>
                        <span class="font-bold text-xl text-white">EduGrade</span>
                    </div>
                    <p class="text-sm leading-relaxed max-w-sm mb-6">
                        Innovación tecnológica para la educación del futuro. Creando puentes entre el conocimiento y el éxito estudiantil a través de herramientas de gestión de primer nivel.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-900 flex items-center justify-center hover:bg-primary-600 hover:text-white transition-colors duration-300"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-900 flex items-center justify-center hover:bg-primary-600 hover:text-white transition-colors duration-300"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-900 flex items-center justify-center hover:bg-primary-600 hover:text-white transition-colors duration-300"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-900 flex items-center justify-center hover:bg-primary-600 hover:text-white transition-colors duration-300"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-6 tracking-wider uppercase text-sm">Enlaces Rápidos</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#inicio" class="hover:text-primary-400 transition-colors">Inicio</a></li>
                        <li><a href="#caracteristicas" class="hover:text-primary-400 transition-colors">Características</a></li>
                        <li><a href="#nosotros" class="hover:text-primary-400 transition-colors">Nosotros</a></li>
                        <li><a href="#" class="hover:text-primary-400 transition-colors">Soporte Técnico</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6 tracking-wider uppercase text-sm">Contacto</h4>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-envelope mt-1 text-primary-500"></i>
                            <span>contacto@edugrade.com</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-phone mt-1 text-primary-500"></i>
                            <span>+1 (234) 567-890</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt mt-1 text-primary-500"></i>
                            <span>123 Innovation Drive,<br>Tech District, 10001</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
                <p>&copy; 2026 EduGrade Sistema de Calificación. Todos los derechos reservados.</p>
                <div class="flex space-x-6">
                    <a href="#" class="hover:text-white transition-colors">Términos de Servicio</a>
                    <a href="#" class="hover:text-white transition-colors">Política de Privacidad</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
            easing: 'ease-in-out-cubic',
        });

        // Add blur effect to navbar on scroll
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 10) {
                nav.classList.add('shadow-sm');
            } else {
                nav.classList.remove('shadow-sm');
            }
        });
    </script>
    </script>

    <!-- Chatbot Widget -->
    <div id="chatbot-container" class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
        <!-- Ventana del Chat (Oculta por defecto) -->
        <div id="chatbot-window" class="bg-white w-80 sm:w-96 rounded-2xl shadow-2xl border border-slate-200 overflow-hidden mb-4 transition-all duration-300 transform scale-0 opacity-0 origin-bottom-right">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-600 to-secondary-500 p-4 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-robot text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">Asistente EduGrade</h4>
                        <p class="text-xs text-primary-100 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-green-400"></span> En línea
                        </p>
                    </div>
                </div>
                <button id="close-chatbot" class="text-white hover:text-slate-200 focus:outline-none transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <!-- Cuerpo (Mensajes) -->
            <div id="chatbot-messages" class="p-4 h-80 overflow-y-auto bg-slate-50 flex flex-col gap-3">
                <!-- Mensaje del Bot -->
                <div class="flex gap-2">
                    <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-sm"></i>
                    </div>
                    <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 max-w-[80%]">
                        <p class="text-sm text-slate-700">¡Hola! Soy el asistente virtual de EduGrade 👋. ¿En qué te puedo ayudar hoy con respecto a la plataforma de calificaciones?</p>
                    </div>
                </div>
                
                <!-- Chips de Sugerencias -->
                <div class="flex flex-wrap gap-2 mt-2">
                    <button class="chatbot-chip bg-white border border-primary-200 text-primary-600 text-xs px-3 py-1.5 rounded-full hover:bg-primary-50 transition-colors">¿Cómo me registro?</button>
                    <button class="chatbot-chip bg-white border border-primary-200 text-primary-600 text-xs px-3 py-1.5 rounded-full hover:bg-primary-50 transition-colors">Olvidé mi contraseña</button>
                    <button class="chatbot-chip bg-white border border-primary-200 text-primary-600 text-xs px-3 py-1.5 rounded-full hover:bg-primary-50 transition-colors">Ver calificaciones</button>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-slate-100 flex gap-2 items-center">
                <input type="text" id="chatbot-input" placeholder="Escribe tu mensaje..." class="w-full bg-slate-50 border border-slate-200 text-sm px-4 py-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all text-slate-700">
                <button id="send-chatbot" class="w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center flex-shrink-0 hover:bg-primary-700 hover:shadow-lg transition-all focus:outline-none">
                    <i class="fas fa-paper-plane text-sm -ml-1 mt-0.5"></i>
                </button>
            </div>
        </div>

        <!-- Botón Flotante -->
        <button id="toggle-chatbot" class="w-16 h-16 bg-primary-600 text-white rounded-full flex items-center justify-center shadow-2xl hover:bg-primary-700 hover:scale-105 transition-all duration-300 focus:outline-none group relative">
            <i class="fas fa-comment-dots text-2xl group-hover:hidden"></i>
            <i class="fas fa-times text-2xl hidden group-hover:block"></i>
            <!-- Indicador animado -->
            <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 border-2 border-white rounded-full"></span>
            <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full animate-ping opacity-75"></span>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-chatbot');
            const closeBtn = document.getElementById('close-chatbot');
            const chatWindow = document.getElementById('chatbot-window');
            const toggleIconOpen = toggleBtn.querySelector('.fa-comment-dots');
            const toggleIconClose = toggleBtn.querySelector('.fa-times');
            const inputField = document.getElementById('chatbot-input');
            const sendBtn = document.getElementById('send-chatbot');
            const messagesContainer = document.getElementById('chatbot-messages');
            
            let isOpen = false;

            function toggleChat() {
                isOpen = !isOpen;
                if (isOpen) {
                    chatWindow.classList.remove('scale-0', 'opacity-0');
                    chatWindow.classList.add('scale-100', 'opacity-100');
                    toggleIconOpen.classList.add('hidden');
                    toggleIconClose.classList.remove('hidden');
                    toggleBtn.classList.remove('group'); // Detener hover effect cuando está abierto
                    // Quitar notificaciones rojas
                    const badges = toggleBtn.querySelectorAll('.bg-red-500');
                    badges.forEach(b => b.style.display = 'none');
                } else {
                    chatWindow.classList.remove('scale-100', 'opacity-100');
                    chatWindow.classList.add('scale-0', 'opacity-0');
                    toggleIconOpen.classList.remove('hidden');
                    toggleIconClose.classList.add('hidden');
                    toggleBtn.classList.add('group');
                }
            }

            toggleBtn.addEventListener('click', toggleChat);
            closeBtn.addEventListener('click', toggleChat);

            // Funcionalidad de enviar mensaje
            function appendMessage(text, isUser) {
                const msgDiv = document.createElement('div');
                msgDiv.className = isUser ? 'flex gap-2 justify-end' : 'flex gap-2';
                
                const innerHtml = isUser ? `
                    <div class="bg-primary-600 text-white p-3 rounded-2xl rounded-tr-none shadow-sm max-w-[80%]">
                        <p class="text-sm">${text}</p>
                    </div>
                ` : `
                    <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-robot text-sm"></i>
                    </div>
                    <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 max-w-[80%]">
                        <p class="text-sm text-slate-700">${text}</p>
                    </div>
                `;
                
                msgDiv.innerHTML = innerHtml;
                messagesContainer.appendChild(msgDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function handleSend() {
                const text = inputField.value.trim();
                if (text) {
                    // Ocultar chips si existen
                    const chips = document.querySelector('.chatbot-chip').parentElement;
                    if(chips) chips.style.display = 'none';

                    appendMessage(text, true);
                    inputField.value = '';
                    
                    // Simular respuesta del bot
                    setTimeout(() => {
                        const loadingId = 'loading-' + Date.now();
                        appendMessage('<i class="fas fa-ellipsis-h animate-pulse"></i>', false);
                        messagesContainer.lastChild.id = loadingId;
                        
                        setTimeout(() => {
                            document.getElementById(loadingId).remove();
                            appendMessage('Gracias por tu mensaje. Por el momento soy un modelo de prueba, pero pronto podré ayudarte a resolver dudas específicas sobre notas, registro de acudientes y acceso a la plataforma.', false);
                        }, 1000);
                    }, 500);
                }
            }

            sendBtn.addEventListener('click', handleSend);
            inputField.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') handleSend();
            });

            // Hacer que los chips envíen el texto
            document.querySelectorAll('.chatbot-chip').forEach(chip => {
                chip.addEventListener('click', function() {
                    inputField.value = this.innerText;
                    handleSend();
                });
            });
        });
    </script>
</body>

</html>