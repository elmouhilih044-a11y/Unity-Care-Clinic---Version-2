<?php require_once 'views/layout/header.php'; ?>

<!-- Hero Section -->
<!-- Hero Section -->
<section class="relative bg-gray-50 py-20 lg:py-32 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="text-center lg:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                    Votre Santé, <br><span class="text-primary">Notre Priorité</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8 max-w-lg mx-auto lg:mx-0">
                    Bienvenue à Unity Care Clinic. Nous offrons des soins médicaux de classe mondiale avec une équipe dévouée de spécialistes. Prenez rendez-vous en ligne dès aujourd'hui.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="login.php" class="bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-lg font-medium transition-colors text-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Prendre Rendez-vous
                    </a>
                    <a href="#services" class="bg-white border-2 border-primary text-primary hover:bg-blue-50 px-8 py-3 rounded-lg font-medium transition-colors text-center">
                        En savoir plus
                    </a>
                </div>
            </div>
            <div class="relative lg:block">
                <div class="absolute -inset-4 bg-primary/10 rounded-2xl transform rotate-3"></div>
                <img src="https://images.unsplash.com/photo-1538108149393-fbbd81895907?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Medical Team" class="relative rounded-2xl shadow-2xl w-full object-cover h-[400px] lg:h-[500px]">
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Nos Services</h2>
            <div class="w-20 h-1 bg-primary mx-auto rounded-full"></div>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Service 1 -->
            <div class="bg-gray-50 rounded-xl p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border border-gray-100">
                <div class="w-14 h-14 bg-blue-100 text-primary rounded-lg flex items-center justify-center text-2xl mb-6">
                    <i class="fa-solid fa-user-doctor"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Consultations Générales</h3>
                <p class="text-gray-600 leading-relaxed">Diagnostics complets et suivi régulier pour toute la famille avec une approche personnalisée.</p>
            </div>
            <!-- Service 2 -->
            <div class="bg-gray-50 rounded-xl p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border border-gray-100">
                <div class="w-14 h-14 bg-blue-100 text-primary rounded-lg flex items-center justify-center text-2xl mb-6">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Cardiologie</h3>
                <p class="text-gray-600 leading-relaxed">Soins spécialisés pour votre coeur avec des équipements de pointe et des experts reconnus.</p>
            </div>
            <!-- Service 3 -->
            <div class="bg-gray-50 rounded-xl p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border border-gray-100">
                <div class="w-14 h-14 bg-blue-100 text-primary rounded-lg flex items-center justify-center text-2xl mb-6">
                    <i class="fa-solid fa-flask"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Laboratoire</h3>
                <p class="text-gray-600 leading-relaxed">Analyses médicales rapides et précises sur place avec des résultats en ligne sécurisés.</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats/Features -->
<section class="bg-primary py-16 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="p-4">
                <h2 class="text-4xl md:text-5xl font-bold mb-2">15+</h2>
                <p class="text-blue-100 font-medium">Années d'Expérience</p>
            </div>
            <div class="p-4">
                <h2 class="text-4xl md:text-5xl font-bold mb-2">50+</h2>
                <p class="text-blue-100 font-medium">Médecins Spécialistes</p>
            </div>
            <div class="p-4">
                <h2 class="text-4xl md:text-5xl font-bold mb-2">10k+</h2>
                <p class="text-blue-100 font-medium">Patients Satisfaits</p>
            </div>
            <div class="p-4">
                <h2 class="text-4xl md:text-5xl font-bold mb-2">24/7</h2>
                <p class="text-blue-100 font-medium">Services d'Urgence</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'views/layout/footer.php'; ?>
