<?php
require_once 'repositories/AppointmentRepository.php';
require_once 'repositories/PrescriptionRepository.php';
require_once 'classes/Database.php';

$db = Database::getInstance()->getConnection();
$user = Auth::getUser();

$apptRepo = new AppointmentRepository($db);
$prescRepo = new PrescriptionRepository($db);

$myAppts = $apptRepo->findByPatient($user['id']);
$myPrescs = $prescRepo->findByPatient($user['id']);

// Find next appointment
$nextAppt = null;
foreach ($myAppts as $a) {
    if ($a['status'] === 'scheduled' && $a['date'] >= date('Y-m-d')) {
        $nextAppt = $a;
        break; // Assuming sorted correctly or close enough
    }
}
?>

<div style="margin-bottom: 2rem;">
    <h3 style="color: var(--primary-color);">Bonjour <?php echo h($user['first_name']); ?></h3>
    <p style="color: var(--text-light);">Bienvenue sur votre espace santé.</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Next Appointment -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h4 style="margin:0;">Prochain Rendez-vous</h4>
            <i class="fa-solid fa-calendar-check" style="color: var(--primary-color); font-size: 1.5rem;"></i>
        </div>
        
        <?php if ($nextAppt): ?>
            <div style="margin-bottom: 0.5rem; font-weight: 600; font-size: 1.125rem;">
                <?php echo date('d/m/Y', strtotime($nextAppt['date'])); ?> à <?php echo substr($nextAppt['time'], 0, 5); ?>
            </div>
            <div style="color: var(--text-light); margin-bottom: 1rem;">
                Avec Dr. <?php echo h($nextAppt['doctor_first_name'] . ' ' . $nextAppt['doctor_last_name']); ?><br>
                <span style="font-size: 0.875rem;"><?php echo h($nextAppt['specialization']); ?></span>
            </div>
            <a href="dashboard.php?page=appointments" class="btn btn-primary" style="width: 100%; text-align: center;">Gérer</a>
        <?php else: ?>
            <p style="color: var(--text-light);">Aucun rendez-vous à venir.</p>
            <a href="dashboard.php?page=appointments_create" class="btn btn-primary" style="width: 100%; text-align: center;">Prendre RDV</a>
        <?php endif; ?>
    </div>

    <!-- Recent Prescriptions -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h4 style="margin:0;">Dernières Ordonnances</h4>
            <i class="fa-solid fa-file-prescription" style="color: var(--secondary-color); font-size: 1.5rem;"></i>
        </div>

        <?php if (!empty($myPrescs)): ?>
            <ul style="list-style: none; padding: 0;">
                <?php 
                $count = 0;
                foreach ($myPrescs as $p): 
                    if ($count++ >= 3) break;
                ?>
                <li style="padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;">
                    <div style="font-weight: 500;"><?php echo h($p['medication_name']); ?></div>
                    <div style="font-size: 0.75rem; color: var(--text-light);"><?php echo h(date('d/m/Y', strtotime($p['created_at']))); ?></div>
                </li>
                <?php endforeach; ?>
            </ul>
            <a href="dashboard.php?page=prescriptions" style="color: var(--primary-color); font-size: 0.875rem; font-weight: 500; display: block; margin-top: 1rem;">Voir tout &rarr;</a>
        <?php else: ?>
            <p style="color: var(--text-light);">Aucune prescription.</p>
        <?php endif; ?>
    </div>
</div>
