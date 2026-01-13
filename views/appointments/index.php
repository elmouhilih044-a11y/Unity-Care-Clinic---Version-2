<?php
require_once 'repositories/AppointmentRepository.php';

$apptRepo = new AppointmentRepository($db);
$role = Auth::getRole();
$userId = Auth::getUser()['id'];

$appointments = [];
if ($role === 'admin') {
    $appointments = $apptRepo->findAll();
} elseif ($role === 'doctor') {
    $appointments = $apptRepo->findByDoctor($userId);
} elseif ($role === 'patient') {
    $appointments = $apptRepo->findByPatient($userId);
}

// Handle Cancel/Status Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST['csrf_token'] ?? '');
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_status') {
        $id = $_POST['id'];
        $status = $_POST['status'];
        if ($apptRepo->updateStatus($id, $status)) {
            echo "<script>window.location.reload();</script>"; // Simple reload
        }
    }
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h3 style="color: var(--primary-color);">Rendez-vous</h3>
        <p style="color: var(--text-light);">Gérez vos consultations</p>
    </div>
    <?php if ($role === 'patient'): ?>
        <a href="dashboard.php?page=appointments_create" class="btn btn-primary">Prendre Rendez-vous</a>
    <?php endif; ?>
</div>

<div class="card" style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid #e2e8f0;">
                <th style="padding: 1rem;">Date & Heure</th>
                <th style="padding: 1rem;">Patient</th>
                <th style="padding: 1rem;">Médecin</th>
                <th style="padding: 1rem;">Raison</th>
                <th style="padding: 1rem;">Statut</th>
                <th style="padding: 1rem;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $a): ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 1rem;">
                        <div style="font-weight: 500;"><?php echo date('d/m/Y', strtotime($a['date'])); ?></div>
                        <div style="font-size: 0.875rem; color: var(--text-light);"><?php echo substr($a['time'], 0, 5); ?></div>
                    </td>
                    <td style="padding: 1rem;">
                        <?php 
                        // If patient view, we might not have patient name if logic is weird, but repo joins generally cover it.
                        // Wait, findByPatient joins Doctor info. findByDoctor joins Patient info.
                        // Admin findAll joins BOTH.
                        // I should handle potentially missing keys if the repo results differ structure.
                        // Let's assume repo methods return consistently or check.
                        
                        if (isset($a['patient_first_name'])) {
                            echo h($a['patient_first_name'] . ' ' . $a['patient_last_name']);
                        } else {
                            // If I am the patient, I know who I am
                            echo "Moi";
                        }
                        ?>
                    </td>
                    <td style="padding: 1rem;">
                        <?php 
                        if (isset($a['doctor_first_name'])) {
                            echo "Dr. " . h($a['doctor_first_name'] . ' ' . $a['doctor_last_name']);
                            if (isset($a['specialization'])) echo "<br><span style='font-size:0.75rem; color:gray;'>" . h($a['specialization']) . "</span>";
                        } else {
                            echo "Moi";
                        }
                        ?>
                    </td>
                    <td style="padding: 1rem;"><?php echo h($a['reason']); ?></td>
                    <td style="padding: 1rem;">
                        <?php 
                        $statusColors = [
                            'scheduled' => '#3b82f6',
                            'done' => '#10b981',
                            'cancelled' => '#ef4444'
                        ];
                        $color = $statusColors[$a['status']] ?? '#64748b';
                        ?>
                        <span class="status-badge" style="background-color: <?php echo $color; ?>20; color: <?php echo $color; ?>;">
                            <?php echo ucfirst($a['status']); ?>
                        </span>
                    </td>
                    <td style="padding: 1rem;">
                        <?php if ($role === 'doctor' || $role === 'admin'): ?>
                             <form method="POST" style="display:inline;">
                                 <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                 <input type="hidden" name="action" value="update_status">
                                 <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                 
                                 <?php if ($a['status'] === 'scheduled'): ?>
                                    <button name="status" value="done" class="btn" style="background:var(--success-color); padding:0.25rem 0.5rem; font-size:0.75rem;" title="Marquer comme fait"><i class="fa-solid fa-check"></i></button>
                                    <button name="status" value="cancelled" class="btn" style="background:var(--error-color); padding:0.25rem 0.5rem; font-size:0.75rem;" title="Annuler"><i class="fa-solid fa-xmark"></i></button>
                                 <?php endif; ?>
                             </form>
                        <?php elseif ($role === 'patient' && $a['status'] === 'scheduled'): ?>
                             <form method="POST" onsubmit="return confirm('Annuler le rendez-vous ?');">
                                 <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                 <input type="hidden" name="action" value="update_status">
                                 <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                 <button name="status" value="cancelled" class="btn" style="background:var(--error-color); padding:0.25rem 0.5rem; font-size:0.75rem;">Annuler</button>
                             </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
