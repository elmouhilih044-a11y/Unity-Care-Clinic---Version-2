<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once 'classes/Database.php';
require_once 'classes/Auth.php';
require_once 'repositories/DashboardRepository.php';
require_once 'includes/security.php';

Auth::init();

if (!Auth::isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$db = Database::getInstance()->getConnection();
$dashboardRepo = new DashboardRepository($db);

$stats = $dashboardRepo->getStats();
$recentAppointments = $dashboardRepo->getRecentAppointments(5);
$appointmentsByStatus = $dashboardRepo->getAppointmentsByStatus();

$user = Auth::getUser();

require_once 'views/layout/header.php';
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Tableau de Bord</h1>
            <p class="text-gray-600">Bienvenue, <span class="font-semibold text-primary"><?php echo h($user['first_name'] . ' ' . $user['last_name']); ?></span>. Voici un aperçu de l'activité aujourd'hui.</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="views/appointments/create.php" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm inline-flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Nouveau Rendez-vous
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Patients Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-xl">
                    <i class="fa-solid fa-users"></i>
                </div>
                <!-- <span class="text-green-500 text-sm font-medium">+12% <i class="fa-solid fa-arrow-up"></i></span> -->
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Total Patients</h3>
            <p class="text-2xl font-bold text-gray-900"><?php echo h($stats['patients_count']); ?></p>
        </div>

        <!-- Doctors Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center text-xl">
                    <i class="fa-solid fa-user-doctor"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Médecins</h3>
            <p class="text-2xl font-bold text-gray-900"><?php echo h($stats['doctors_count']); ?></p>
        </div>

        <!-- Appointments Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center text-xl">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Rdv Programmés</h3>
            <p class="text-2xl font-bold text-gray-900"><?php echo h($stats['appointments_count']); ?></p>
        </div>

        <!-- Stock Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 <?php echo $stats['low_stock_medications'] > 0 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600'; ?> rounded-lg flex items-center justify-center text-xl">
                    <i class="fa-solid fa-pills"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Stock Faible</h3>
            <p class="text-2xl font-bold text-gray-900"><?php echo h($stats['low_stock_medications']); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Appointments Table -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 h-full">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-900">Rendez-vous Récents</h2>
                <a href="views/appointments/index.php" class="text-primary hover:text-primary-dark text-sm font-medium transition-colors">Voir tout</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">Patient</th>
                            <th class="px-6 py-4 font-semibold">Médecin</th>
                            <th class="px-6 py-4 font-semibold">Date & Heure</th>
                            <th class="px-6 py-4 font-semibold">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($recentAppointments as $appointment): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900"><?php echo h($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']); ?></div>
                                </td>
                                <td class="px-6 py-4 text-gray-600"><?php echo h($appointment['doctor_first_name'] . ' ' . $appointment['doctor_last_name']); ?></td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-900"><?php echo h($appointment['date']); ?></div>
                                    <div class="text-gray-500 text-xs"><?php echo h($appointment['time']); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php 
                                    $statusClasses = [
                                        'scheduled' => 'bg-blue-100 text-blue-700',
                                        'done' => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-red-100 text-red-700'
                                    ];
                                    $statusLabel = [
                                        'scheduled' => 'Programmé',
                                        'done' => 'Terminé',
                                        'cancelled' => 'Annulé'
                                    ];
                                    $class = $statusClasses[$appointment['status']] ?? 'bg-gray-100 text-gray-700';
                                    $label = $statusLabel[$appointment['status']] ?? $appointment['status'];
                                    ?>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium <?php echo $class; ?>">
                                        <?php echo h($label); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recentAppointments)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Aucun rendez-vous récent.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Appointments Status Chart/Summary -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Répartition des Rdv</h2>
                <div class="space-y-4">
                    <?php foreach ($appointmentsByStatus as $status): ?>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="capitalize text-gray-600"><?php echo h($status['status']); ?></span>
                                <span class="font-semibold text-gray-900"><?php echo h($status['count']); ?></span>
                            </div>
                            <?php 
                            $total = array_sum(array_column($appointmentsByStatus, 'count'));
                            $percentage = $total > 0 ? ($status['count'] / $total) * 100 : 0;
                            $barClass = 'bg-primary';
                            if ($status['status'] === 'done') $barClass = 'bg-green-500';
                            if ($status['status'] === 'cancelled') $barClass = 'bg-red-500';
                            ?>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="<?php echo $barClass; ?> h-2 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-gradient-to-br from-primary to-blue-700 p-6 rounded-xl shadow-lg text-white">
                <h3 class="text-lg font-bold mb-2">Besoin d'aide ?</h3>
                <p class="text-blue-100 text-sm mb-4">Consultez le guide d'utilisation ou contactez le support technique.</p>
                <a href="#" class="inline-block bg-white text-primary px-4 py-2 rounded-lg font-medium text-sm hover:bg-blue-50 transition-colors">Support</a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'views/layout/footer.php'; ?>
