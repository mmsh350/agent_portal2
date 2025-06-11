<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGENT PORTAL - Identity Verification Solutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/images/img/logo.png') }}" type="image">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        #loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        #loader-wrapper.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #082851;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .brand-color {
            color: #082851;
        }

        .brand-bg {
            background-color: #082851;
        }

        .brand-border {
            border-bottom: 2px solid #082851;
        }

        .brand-gradient {
            background: linear-gradient(135deg, #08285133 0%, #ffffff 50%, #08285133 100%);
        }

        a[href^="mailto:"]:hover {
            color: #082851;
        }

        .hover\:brand-bg:hover {
            background-color: #082851;
        }
    </style>
</head>

<body class="brand-gradient text-gray-800 antialiased">

    <!-- Loader -->
    <div id="loader-wrapper">
        <div class="loader"></div>
    </div>


    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo + Brand -->
                <div class="flex items-center">
                    <img src="{{ asset('assets/images/img/logo.png') }}" alt="AGENT PORTAL Logo" class="h-8 w-auto">
                    <span class="ml-2 text-xl font-semibold text-gray-700">AGENT PORTAL</span>
                </div>

                <!-- Desktop Nav -->
                <div class="hidden sm:flex space-x-8">
                    <a href="#" class="text-gray-900 brand-border px-1 pt-1 text-sm font-medium">Home</a>
                    <a href="#services"
                        class="text-gray-600 hover:text-gray-900 px-1 pt-1 text-sm font-medium">Services</a>
                    <a href="#contact"
                        class="text-gray-600 hover:text-gray-900 px-1 pt-1 text-sm font-medium">Contact</a>
                </div>

                <!-- Hamburger Button (Mobile) -->
                <div class="sm:hidden flex items-center">
                    <button @click="open = !open" class="text-gray-700 hover:text-gray-900 focus:outline-none">
                        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="sm:hidden" x-show="open" @click.outside="open = false" x-transition>
            <div class="px-4 pt-2 pb-3 space-y-1">
                <a href="#" class="block text-gray-900 px-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href="#services"
                    class="block text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-base font-medium">Services</a>
                <a href="#contact"
                    class="block text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-base font-medium">Contact</a>
            </div>
        </div>
    </nav>


    <!-- Main Content -->
    <main class="overflow-hidden">
        <!-- Hero Section -->
        <section class="relative py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-12 lg:gap-16 items-center">
                    <div class="lg:col-span-6 text-center lg:text-left">
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-gray-900">
                            Secure Verification Services by <span class="brand-color">AGENT PORTAL</span>
                        </h1>
                        <p class="mt-4 text-lg sm:text-xl text-gray-600">
                            Professional Verification and agency services. Fast, reliable, and secure.
                        </p>
                        <div class="mt-8 flex gap-4 justify-center lg:justify-start">
                            <a href="{{ route('auth.login') }}"
                                class="brand-bg text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:opacity-90 transition-all">
                                Login Portal
                            </a>
                            <a href="{{ route('auth.register') }}"
                                class="border-2 border-[#082851] px-6 py-3 rounded-lg font-medium hover:bg-[#08285111] hover:text-white transition-all">
                                Register Now
                            </a>
                        </div>
                    </div>
                    <div class="mt-6 lg:mt-0 lg:col-span-6 flex justify-center">
                        <img src="{{ asset('assets/images/img/verification-hero.png') }}" alt="Verification Services"
                            class="rounded-xl shadow-2xl w-full max-w-2xl">
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="py-16 sm:py-24 bg-white/50 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-sm brand-color font-semibold uppercase tracking-wide">Our Services</h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Identity Verification Solutions
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- NIN Service -->
                    <div
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow hover:brand-bg hover:text-white group">
                        <div class="brand-bg w-fit p-3 rounded-lg group-hover:bg-white group-hover:text-[#082851]">
                            <svg class="w-8 h-8 text-white group-hover:text-[#082851]" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-semibold group-hover:text-white">NIN Verification</h3>
                        <p class="mt-2 text-gray-600 group-hover:text-gray-200">Instant National Identity Number
                            verification with multiple lookup options</p>
                    </div>

                    <!-- BVN Service -->
                    <div
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow hover:brand-bg hover:text-white group">
                        <div class="brand-bg w-fit p-3 rounded-lg group-hover:bg-white group-hover:text-[#082851]">
                            <svg class="w-8 h-8 text-white group-hover:text-[#082851]" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-semibold group-hover:text-white">BVN Services</h3>
                        <p class="mt-2 text-gray-600 group-hover:text-gray-200">Bank Verification Number validation and
                            document generation</p>
                    </div>

                    <!-- Document Service -->
                    <div
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow hover:brand-bg hover:text-white group">
                        <div class="brand-bg w-fit p-3 rounded-lg group-hover:bg-white group-hover:text-[#082851]">
                            <svg class="w-8 h-8 text-white group-hover:text-[#082851]" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-semibold group-hover:text-white">BVN enrolment access
                        </h3>
                        <p class="mt-2 text-gray-600 group-hover:text-gray-200">OBecome a bvn enrolment agent and get
                            access for enrolling customers and get commision</p>
                    </div>

                    <!-- Government Service -->
                    <div
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow hover:brand-bg hover:text-white group">
                        <div class="brand-bg w-fit p-3 rounded-lg group-hover:bg-white group-hover:text-[#082851]">
                            <svg class="w-8 h-8 text-white group-hover:text-[#082851]" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-semibold group-hover:text-white">Register Your Business</h3>
                        <p class="mt-2 text-gray-600 group-hover:text-gray-200">Upgrade your business With CAC
                            registration.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-16 sm:py-5 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-sm brand-color font-semibold uppercase tracking-wide">Get Support</h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Contact Our Team
                    </p>
                </div>

                <div class="mt-12 grid lg:grid-cols-2 gap-12">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Support Information</h3>
                            <div class="mt-4 space-y-2 text-gray-600">
                                <p>Email: <a href="mailto:support@nintrust.gov.ng"
                                        class="brand-color hover:underline">agentportal@gmail.com</a></p>
                                <p>Phone: <a href="tel:+234700NINTRUST"
                                        class="brand-color hover:underline">XX-XXX-XXXX</a></p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="bg-gray-50 p-8 rounded-xl">
                        <form class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Message</label>
                                <textarea rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-3"></textarea>
                            </div>
                            <button type="submit"
                                class="w-full brand-bg text-white py-3 rounded-md font-medium hover:opacity-90 transition-all">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <img src="{{ asset('assets/images/img/logo2.png') }}" alt="NIN TRUST Logo" class="h-16 mx-auto">
                <p class="mt-4 text-sm">
                    National Identity Verification and Trust Services
                </p>
                <div class="mt-6 flex justify-center space-x-6">
                    <a href="#" class="text-gray-400 hover:text-[#082851] transition-colors">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-[#082851] transition-colors">
                        <span class="sr-only">GitHub</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8 text-center">
                <p class="text-xs text-gray-500">
                    &copy; {{ date('Y') }} AGENT PORTAL. All rights reserved.
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Identity verification partner
                </p>
            </div>
        </div>
    </footer>

    <script>
        window.onload = function() {
            document.getElementById('loader-wrapper').classList.add('hidden');
        };
    </script>

</body>

</html>
