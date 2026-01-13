<?php
require_once 'repositories/AppointmentRepository.php';
require_once 'classes/Database.php';

$db = Database::getInstance()->getConnection();
$user = Auth::getUser();

$apptRepo = new AppointmentRepository($db);
$todayCount = 0;
$totalCount = 0;
$nextAppts = [];
$error = '';

try {
    // Count Today's Appointments
    $stmt = $db->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND date = CURDATE()");
    $stmt->execute([$user['id']]);
    $todayCount = $stmt->fetchColumn();

    // Upcoming Appointments
    $nextAppts = $apptRepo->findByDoctor($user['id']);
    $totalCount = count($nextAppts);
} catch (Exception $e) {
    $error = "Erreur lors du chargement des données.";
}
?>

<div style="margin-bottom: 2.5rem;">
    <h3 style="color: var(--primary-color);">Bonjour, Dr. <?php echo h($user['last_name']); ?></h3>
    <p style="color: var(--text-muted);">Voici un aperçu de votre activité aujourd'hui.</p>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <?php echo h($error); ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
    <!-- Stat Card 1 -->
    <div class="card" style="border-top: 4px solid var(--primary-color);">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
            <div style="width: 48px; height: 48px; background: var(--primary-light); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary-color);">
                <i class="fa-solid fa-calendar-day" style="font-size: 1.5rem;"></i>
            </div>
            <div>
                <h4 style="margin: 0; color: var(--text-muted); font-weight: 500;">RDV Aujourd'hui</h4>
            </div>
        </div>
        <div style="font-size: 2.5rem; font-weight: 700; color: var(--text-main);"><?php echo $todayCount; ?></div>
    </div>

    <!-- Stat Card 2 -->
    <div class="card" style="border-top: 4px solid var(--secondary-color);">
         <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
            <div style="width: 48px; height: 48px; background: #e0e7ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--secondary-color);">
                <i class="fa-solid fa-user-injured" style="font-size: 1.5rem;"></i>
            </div>
            <div>
                <h4 style="margin: 0; color: var(--text-muted); font-weight: 500;">Total Patients</h4>
            </div>
        </div>
        <div style="font-size: 2.5rem; font-weight: 700; color: var(--text-main);"><?php echo $totalCount; ?></div>
    </div>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h4>Accès Rapide</h4>
    </div>
    <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
        <a href="dashboard.php?page=appointments" class="btn btn-primary">
            <i class="fa-regular fa-calendar-check"></i> Consulter l'agenda
        </a>
        <a href="dashboard.php?page=prescriptions_create" class="btn" style="background-color: var(--primary-light); color: var(--primary-color);">
            <i class="fa-solid fa-file-prescription"></i> Nouvelle Prescription
        </a>
    </div>
</div>
