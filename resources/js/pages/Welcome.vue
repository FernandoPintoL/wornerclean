<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const services = [
    {
        title: 'Limpieza Residencial',
        description: 'Mantenemos su hogar impecable con servicios personalizados de limpieza.',
        icon: 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'
    },
    {
        title: 'Limpieza Comercial',
        description: 'Soluciones profesionales para mantener su negocio limpio y presentable.',
        icon: 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21'
    },
    {
        title: 'Limpieza Profunda',
        description: 'Eliminamos la suciedad acumulada y desinfectamos todas las superficies.',
        icon: 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z'
    },
    {
        title: 'Limpieza Post-Construcción',
        description: 'Dejamos su espacio listo para usar después de renovaciones o construcciones.',
        icon: 'M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z'
    }
];

const testimonials = [
    {
        name: 'María González',
        role: 'Propietaria de Casa',
        content: 'El servicio de limpieza fue excepcional. Mi casa nunca había estado tan limpia y el personal fue muy profesional.',
        avatar: 'https://randomuser.me/api/portraits/women/1.jpg'
    },
    {
        name: 'Carlos Rodríguez',
        role: 'Gerente de Oficina',
        content: 'Contratamos sus servicios para nuestra oficina y quedamos muy satisfechos. El equipo es puntual y eficiente.',
        avatar: 'https://randomuser.me/api/portraits/men/1.jpg'
    },
    {
        name: 'Laura Martínez',
        role: 'Dueña de Restaurante',
        content: 'La limpieza profunda que realizaron en mi restaurante fue impresionante. Definitivamente los recomendaré.',
        avatar: 'https://randomuser.me/api/portraits/women/2.jpg'
    }
];

const mobileMenuOpen = ref(false);
</script>

<template>
    <Head title="Bienvenido a Worner Clean">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <!-- Main Container -->
    <div class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
        <!-- Navigation -->
        <header class="relative bg-white dark:bg-gray-900 shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <img class="h-10 w-auto" src="/assets/logo.png" alt="Worner Clean">
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-center space-x-4">
                            <a href="#servicios" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-green-600 dark:text-gray-300 dark:hover:text-white">Servicios</a>
                            <a href="#nosotros" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-green-600 dark:text-gray-300 dark:hover:text-white">Nosotros</a>
                            <a href="#testimonios" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-green-600 dark:text-gray-300 dark:hover:text-white">Testimonios</a>
                            <a href="#contacto" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-green-600 dark:text-gray-300 dark:hover:text-white">Contacto</a>

                            <Link
                                v-if="$page.props.auth.user"
                                :href="route('dashboard')"
                                class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500"
                            >
                                Dashboard
                            </Link>
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-700"
                                >
                                    Iniciar Sesión
                                </Link>
                            </template>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex md:hidden">
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            type="button"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                            aria-controls="mobile-menu"
                            :aria-expanded="mobileMenuOpen"
                        >
                            <span class="sr-only">Abrir menú principal</span>
                            <svg
                                class="h-6 w-6"
                                :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen}"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg
                                class="h-6 w-6"
                                :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state -->
            <div
                id="mobile-menu"
                class="md:hidden"
                :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}"
            >
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="#servicios" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-green-600 dark:text-gray-300 dark:hover:text-white">Servicios</a>
                    <a href="#nosotros" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-green-600 dark:text-gray-300 dark:hover:text-white">Nosotros</a>
                    <a href="#testimonios" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-green-600 dark:text-gray-300 dark:hover:text-white">Testimonios</a>
                    <a href="#contacto" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-green-600 dark:text-gray-300 dark:hover:text-white">Contacto</a>

                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-white bg-green-600 hover:bg-green-700"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                        >
                            Iniciar Sesión
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <div class="relative bg-white dark:bg-gray-900 overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 bg-white dark:bg-gray-900 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white dark:text-gray-900 transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                        <polygon points="50,0 100,0 50,100 0,100" />
                    </svg>

                    <div class="pt-10 sm:pt-16 lg:pt-8 xl:pt-16">
                        <div class="sm:text-center lg:text-left px-4 sm:px-8 xl:pl-0">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                                <span class="block">Servicios de limpieza</span>
                                <span class="block text-green-600">profesionales y confiables</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-500 dark:text-gray-400 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                Ofrecemos soluciones de limpieza completas para hogares y negocios. Nuestro equipo profesional garantiza resultados impecables en cada servicio.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-md shadow">
                                    <a href="#contacto" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10">
                                        Solicitar Servicio
                                    </a>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <a href="#servicios" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-teal-700 bg-teal-100 hover:bg-teal-200 dark:bg-gray-800 dark:text-teal-400 dark:hover:bg-gray-700 md:py-4 md:text-lg md:px-10">
                                        Ver Servicios
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="/assets/poster.png" alt="Worner Clean">
            </div>
        </div>

        <!-- Services Section -->
        <div id="servicios" class="py-12 bg-gray-50 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Servicios</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        Soluciones de limpieza a su medida
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-400 lg:mx-auto">
                        Ofrecemos una amplia gama de servicios de limpieza profesional para satisfacer todas sus necesidades.
                    </p>
                </div>

                <div class="mt-10">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                        <div v-for="(service, index) in services" :key="index" class="pt-6">
                            <div class="flow-root bg-white dark:bg-gray-900 rounded-lg px-6 pb-8 shadow-lg">
                                <div class="-mt-6">
                                    <div>
                                        <span class="inline-flex items-center justify-center p-3 bg-teal-500 rounded-md shadow-lg">
                                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="service.icon" />
                                            </svg>
                                        </span>
                                    </div>
                                    <h3 class="mt-8 text-lg font-medium text-gray-900 dark:text-white tracking-tight">{{ service.title }}</h3>
                                    <p class="mt-5 text-base text-gray-500 dark:text-gray-400">
                                        {{ service.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- About Section -->
        <div id="nosotros" class="py-12 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Sobre Nosotros</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        Expertos en limpieza profesional
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-400 lg:mx-auto">
                        Con años de experiencia, nos hemos convertido en líderes en el sector de servicios de limpieza.
                    </p>
                </div>

                <div class="mt-10">
                    <div class="md:grid md:grid-cols-2 md:gap-8">
                        <div class="mt-5 md:mt-0">
                            <div class="flex items-center justify-center h-full">
                                <img src="/assets/logo.png" alt="Worner Clean" class="max-w-full h-auto rounded-lg shadow-lg">
                            </div>
                        </div>
                        <div class="mt-10 md:mt-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Nuestra Misión</h3>
                            <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
                                Proporcionar servicios de limpieza excepcionales que superen las expectativas de nuestros clientes, utilizando productos ecológicos y técnicas avanzadas para garantizar resultados impecables.
                            </p>

                            <h3 class="mt-8 text-lg font-medium text-gray-900 dark:text-white">Nuestros Valores</h3>
                            <ul class="mt-3 list-disc list-inside text-base text-gray-500 dark:text-gray-400">
                                <li>Compromiso con la calidad</li>
                                <li>Respeto por el medio ambiente</li>
                                <li>Profesionalismo en cada servicio</li>
                                <li>Atención personalizada</li>
                                <li>Puntualidad y eficiencia</li>
                            </ul>

                            <div class="mt-8">
                                <a href="#contacto" class="text-green-600 hover:text-green-500 font-medium">
                                    Contáctanos para más información <span aria-hidden="true">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials Section -->
        <div id="testimonios" class="py-12 bg-gray-50 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Testimonios</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        Lo que dicen nuestros clientes
                    </p>
                </div>

                <div class="mt-10">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                        <div v-for="(testimonial, index) in testimonials" :key="index" class="bg-white dark:bg-gray-900 overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <img class="h-12 w-12 rounded-full" :src="testimonial.avatar" :alt="testimonial.name">
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ testimonial.name }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ testimonial.role }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 text-gray-500 dark:text-gray-400">
                                    <p class="text-base italic">
                                        "{{ testimonial.content }}"
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div id="contacto" class="py-12 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Contacto</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        ¿Listo para un servicio impecable?
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-400 mx-auto">
                        Contáctenos hoy mismo para obtener una cotización gratuita.
                    </p>
                </div>

                <div class="mt-10 md:grid md:grid-cols-2 md:gap-8">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Información de Contacto</h3>
                        <div class="mt-5 space-y-4">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="ml-3 text-base text-gray-500 dark:text-gray-400">+(591) 73682145</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="ml-3 text-base text-gray-500 dark:text-gray-400">grupo18sc@tecnoweb.org.bo</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="ml-3 text-base text-gray-500 dark:text-gray-400">Santa Cruz de la Sierra, Bolivia</span>
                            </div>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Horario de Atención</h3>
                            <div class="mt-5 text-base text-gray-500 dark:text-gray-400">
                                <p>Lunes a Viernes: 8:00 AM - 6:00 PM</p>
                                <p>Sábados: 9:00 AM - 2:00 PM</p>
                                <p>Domingos: Cerrado</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10 md:mt-0">
                        <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Solicite una cotización</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Complete el formulario y nos pondremos en contacto con usted a la brevedad.
                            </p>
                            <div class="mt-6 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <label for="first-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                                    <div class="mt-1">
                                        <input type="text" name="first-name" id="first-name" class="py-3 px-4 block w-full shadow-sm focus:ring-teal-500 focus:border-teal-500 border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>
                                </div>
                                <div>
                                    <label for="last-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Apellido</label>
                                    <div class="mt-1">
                                        <input type="text" name="last-name" id="last-name" class="py-3 px-4 block w-full shadow-sm focus:ring-teal-500 focus:border-teal-500 border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                    <div class="mt-1">
                                        <input id="email" name="email" type="email" class="py-3 px-4 block w-full shadow-sm focus:ring-teal-500 focus:border-teal-500 border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
                                    <div class="mt-1">
                                        <input type="text" name="phone" id="phone" class="py-3 px-4 block w-full shadow-sm focus:ring-teal-500 focus:border-teal-500 border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mensaje</label>
                                    <div class="mt-1">
                                        <textarea id="message" name="message" rows="4" class="py-3 px-4 block w-full shadow-sm focus:ring-teal-500 focus:border-teal-500 border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                                    </div>
                                </div>
                                <div class="sm:col-span-2">
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                        Enviar Mensaje
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 dark:bg-gray-900">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex justify-center md:justify-start">
                        <img class="h-8 w-auto" src="/assets/logo.png" alt="Worner Clean">
                    </div>
                    <div class="mt-8 md:mt-0">
                        <p class="text-center text-base text-gray-400 md:text-right">
                            &copy; 2025 Worner Clean. Todos los derechos reservados. Tecnologia Web Grupo 18SC
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
