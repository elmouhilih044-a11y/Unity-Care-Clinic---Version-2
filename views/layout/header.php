<?php 
// Enable Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/../../classes/Auth.php';
Auth::init();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Care Clinic</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <!-- <link rel="stylesheet" href="/assets/css/style.css"> -->
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb', // Example blue, adjust to match original if needed
                        'primary-dark': '#1d4ed8',
                        'primary-light': '#60a5fa',
                    }
                }
            }
        }
    </script>
</head>
<body>

<nav class="bg-white shadow-md fixed w-full z-10 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="index.php" class="flex items-center text-primary text-xl font-bold">
                    <i class="fa-solid fa-heart-pulse mr-2"></i> Unity Care
                </a>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php" class="text-gray-600 hover:text-primary transition-colors">Accueil</a>
                <a href="index.php#services" class="text-gray-600 hover:text-primary transition-colors">Services</a>
                <a href="index.php#doctors" class="text-gray-600 hover:text-primary transition-colors">Médecins</a>
                <?php if (Auth::isLoggedIn()): ?>
                    <a href="dashboard.php" class="text-gray-600 hover:text-primary transition-colors font-medium">Dashboard</a>
                    <a href="logout.php" class="text-red-500 hover:text-red-700 transition-colors font-medium">Déconnexion</a>
                <?php else: ?>
                    <a href="login.php" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md transition-colors">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<div class="h-16"></div> <!-- Spacer for fixed nav -->
