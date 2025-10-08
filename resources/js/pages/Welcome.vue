<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const isMenuOpen = ref(false);
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    
    <div class="min-h-screen bg-black text-white">
        <!-- Navigation -->
        <nav class="fixed top-0 w-full bg-black/90 backdrop-blur-sm border-b border-red-600/20 z-50">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-bold">
                        <span class="text-white">Health</span>
                        <span class="text-red-600">Care</span>
                    </div>
                    
                    <div class="hidden md:flex items-center gap-6">
                        <a href="#services" class="hover:text-red-600 transition-colors">Services</a>
                        <a href="#contact" class="hover:text-red-600 transition-colors">Contact</a>
                        <Link
                            v-if="$page.props.auth.user"
                            href="/dashboard"
                            class="px-5 py-2 bg-red-600 rounded hover:bg-red-700 transition-colors"
                        >
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link
                                href="/login"
                                class="px-5 py-2 border border-white/20 rounded hover:border-red-600 transition-colors"
                            >
                                Log in
                            </Link>
                            <Link
                                href="/register"
                                class="px-5 py-2 bg-red-600 rounded hover:bg-red-700 transition-colors"
                            >
                                Register
                            </Link>
                        </template>
                    </div>

                    <button 
                        class="md:hidden text-white"
                        @click="isMenuOpen = !isMenuOpen"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <div v-if="isMenuOpen" class="md:hidden mt-4 pb-4 space-y-3">
                    <a href="#services" class="block hover:text-red-600 transition-colors">Services</a>
                    <a href="#contact" class="block hover:text-red-600 transition-colors">Contact</a>
                    <Link
                        v-if="$page.props.auth.user"
                        href="/dashboard"
                        class="block px-5 py-2 bg-red-600 rounded text-center hover:bg-red-700 transition-colors"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            href="/login"
                            class="block px-5 py-2 border border-white/20 rounded text-center hover:border-red-600 transition-colors"
                        >
                            Log in
                        </Link>
                        <Link
                            href="/register"
                            class="block px-5 py-2 bg-red-600 rounded text-center hover:bg-red-700 transition-colors"
                        >
                            Register
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-32 pb-20 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="text-5xl lg:text-7xl font-bold mb-6 leading-tight">
                            Quality Healthcare
                            <span class="text-red-600"> For Everyone</span>
                        </h1>
                        <p class="text-xl text-gray-400 mb-8 leading-relaxed">
                            Expert medical care when you need it. Book appointments, access records, and manage your health online.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <Link 
                                href="/register" 
                                class="px-8 py-4 bg-red-600 rounded-lg hover:bg-red-700 transition-all transform hover:scale-105 text-center font-semibold"
                            >
                                Book Appointment
                            </Link>
                            <a 
                                href="#services" 
                                class="px-8 py-4 border border-white/20 rounded-lg hover:border-red-600 transition-colors text-center font-semibold"
                            >
                                Our Services
                            </a>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <div class="absolute inset-0 bg-red-600/20 blur-3xl rounded-full"></div>
                        <div class="relative bg-gradient-to-br from-gray-900 to-black border border-red-600/30 rounded-2xl p-8 shadow-2xl">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-red-600 rounded-full"></div>
                                    <div class="flex-1">
                                        <div class="h-3 bg-white/20 rounded w-3/4 mb-2"></div>
                                        <div class="h-2 bg-white/10 rounded w-1/2"></div>
                                    </div>
                                </div>
                                <div class="h-24 bg-gradient-to-br from-red-600/30 to-transparent rounded-lg"></div>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="h-16 bg-white/10 rounded"></div>
                                    <div class="h-16 bg-white/10 rounded"></div>
                                    <div class="h-16 bg-red-600/30 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="py-20 px-6 bg-gradient-to-b from-black to-gray-950">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl lg:text-5xl font-bold mb-4">
                        Our <span class="text-red-600">Services</span>
                    </h2>
                    <p class="text-gray-400 text-lg">Comprehensive healthcare solutions</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div 
                        v-for="(service, index) in [
                            { title: 'Emergency Care', desc: '24/7 emergency services with immediate response' },
                            { title: 'General Medicine', desc: 'Experienced doctors for all your healthcare needs' },
                            { title: 'Laboratory', desc: 'Advanced diagnostic testing and quick results' },
                            { title: 'Surgery', desc: 'Modern surgical facilities with expert surgeons' },
                            { title: 'Pediatrics', desc: 'Specialized care for infants, children, and teens' },
                            { title: 'Telemedicine', desc: 'Online consultations from home' }
                        ]"
                        :key="index"
                        class="group p-6 bg-gradient-to-br from-gray-900 to-black border border-white/10 rounded-xl hover:border-red-600/50 transition-all duration-300 hover:transform hover:scale-105"
                    >
                        <div class="w-12 h-12 bg-red-600/20 rounded-lg flex items-center justify-center mb-4 group-hover:bg-red-600/30 transition-colors">
                            <div class="w-6 h-6 bg-red-600 rounded"></div>
                        </div>
                        <h3 class="text-xl font-bold mb-2">{{ service.title }}</h3>
                        <p class="text-gray-400">{{ service.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-20 px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                    Get Started Today
                </h2>
                <p class="text-xl text-gray-400 mb-8">
                    Register for online access to appointments, medical records, and more
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link 
                        href="/register" 
                        class="px-8 py-4 bg-red-600 rounded-lg hover:bg-red-700 transition-all transform hover:scale-105 font-semibold"
                    >
                        Create Account
                    </Link>
                    <Link 
                        href="/login" 
                        class="px-8 py-4 border border-white/20 rounded-lg hover:border-red-600 transition-colors font-semibold"
                    >
                        Sign In
                    </Link>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-white/10 py-12 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <div class="text-xl font-bold mb-4">
                            <span class="text-white">Health</span>
                            <span class="text-red-600">Care</span>
                        </div>
                        <p class="text-gray-400 text-sm">
                            Committed to your health and wellbeing.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Services</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><a href="#" class="hover:text-red-600 transition-colors">Emergency</a></li>
                            <li><a href="#" class="hover:text-red-600 transition-colors">Appointments</a></li>
                            <li><a href="#" class="hover:text-red-600 transition-colors">Laboratory</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Contact</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><a href="#" class="hover:text-red-600 transition-colors">Emergency: 911</a></li>
                            <li><a href="#" class="hover:text-red-600 transition-colors">Appointments</a></li>
                            <li><a href="#" class="hover:text-red-600 transition-colors">Support</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Legal</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><a href="#" class="hover:text-red-600 transition-colors">Privacy</a></li>
                            <li><a href="#" class="hover:text-red-600 transition-colors">Terms</a></li>
                            <li><a href="#" class="hover:text-red-600 transition-colors">HIPAA</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-white/10 pt-8 text-center text-gray-400 text-sm">
                    © 2025 HealthCare. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
</template>