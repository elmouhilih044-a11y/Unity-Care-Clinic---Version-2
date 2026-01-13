<?php
require_once 'repositories/AppointmentRepository.php';

$apptRepo = new AppointmentRepository($db);
$appointments = [];
$message = '';
$error = '';

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST['csrf_token'] ?? '');
    $action = $_POST['action'] ?? '';
    $apptId = $_POST['appointment_id'] ?? 0;

    if ($action === 'cancel') {
        // Verify ownership/permission
        $appt = $apptRepo->findById($apptId);
        if ($appt) {
            $canCancel = false;
            if ($role === 'admin') $canCancel = true;
            if ($role === 'doctor' && $appt['doctor_id'] == $user['id']) $canCancel = true;
            if ($role === 'patient' && $appt['patient_id'] == $user['id']) $canCancel = true;

            if ($canCancel) {
                $apptRepo->updateStatus($apptId, 'cancelled');
                $message = "Rendez-vous annulé.";
            } else {
                $error = "Action non autorisée.";
            }
        }
    } elseif ($action === 'complete') {
        if ($role === 'doctor' || $role === 'admin') {
            $apptRepo->updateStatus($apptId, 'done');
            $message = "Rendez-vous marqué comme effectué.";
        }
    }
}

// Fetch Data
if ($role === 'patient') {
    $appointments = $apptRepo->findByPatient($user['id']);
} elseif ($role === 'doctor') {
    $appointments = $apptRepo->findByDoctor($user['id']);
} elseif ($role === 'admin') {
    $appointments = $apptRepo->findAll();
}
?>

<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h3 style="color: var(--primary-color);">Mes Rendez-vous</h3>
        <p style="color: var(--text-light);">Gérez vos consultations</p>
    </div>
    <a href="dashboard.php?page=appointments_create" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Nouveau Rendez-vous
    </a>
</div>

<?php if ($message): ?>
    <div class="alert alert-success" style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
        <?php echo h($message); ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger" style="background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
        <?php echo h($error); ?>
    </div>
<?php endif; ?>

<div class="card" style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid #e2e8f0;">
                <th style="padding: 1rem; color: var(--text-light); font-weight: 600; font-size: 0.875rem;">Date & Heure</th>
                <th style="padding: 1rem; color: var(--text-light); font-weight: 600; font-size: 0.875rem;">
                    <?php echo ($role === 'doctor') ? 'Patient' : 'Médecin'; ?>
                </th>
                <th style="padding: 1rem; color: var(--text-light); font-weight: 600; font-size: 0.875rem;">Motif</th>
                <th style="padding: 1rem; color: var(--text-light); font-weight: 600; font-size: 0.875rem;">Statut</th>
                <th style="padding: 1rem; color: var(--text-light); font-weight: 600; font-size: 0.875rem;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($appointments)): ?>
                <tr>
                    <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-light);">Aucun rendez-vous trouvé.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($appointments as $appt): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 500;"><?php echo h($appt['date']); ?></div>
                            <div style="font-size: 0.875rem; color: var(--text-light);"><?php echo h(substr($appt['time'], 0, 5)); ?></div>
                        </td>
                        <td style="padding: 1rem;">
                            <?php 
                            if ($role === 'doctor') {
                                echo h($appt['patient_first_name'] . ' ' . $appt['patient_last_name']);
                            } elseif ($role === 'patient') {
                                echo h("Dr. " . $appt['doctor_first_name'] . ' ' . $appt['doctor_last_name']);
                                if (isset($appt['specialization'])) echo "<div style='font-size: 0.75rem; color: var(--text-light);'>" . h($appt['specialization']) . "</div>";
                            } else {
                                echo h("Dr. " . $appt['doctor_first_name']) . " <br> " . h($appt['patient_first_name']);
                            }
                            ?>
                        </td>
                        <td style="padding: 1rem;"><?php echo h($appt['reason']); ?></td>
                        <td style="padding: 1rem;">
                            <?php 
                            $statusColors = [
                                'scheduled' => ['bg' => '#e0f2fe', 'text' => '#0369a1'],
                                'done' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                'cancelled' => ['bg' => '#fee2e2', 'text' => '#b91c1c']
                            ];
                            $s = $appt['status'];
                            $color = $statusColors[$s] ?? ['bg' => '#f1f5f9', 'text' => '#64748b'];
                            ?>
                            <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; background: <?php echo $color['bg']; ?>; color: <?php echo $color['text']; ?>;">
                                <?php echo ucfirst($s); ?>
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <?php if ($s !== 'cancelled' && $s !== 'done'): ?>
                                    <form method="POST" onsubmit="return confirm('Êtes-vous sûr ?');" style="margin:0;">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                        <input type="hidden" name="action" value="cancel">
                                        <input type="hidden" name="appointment_id" value="<?php echo $appt['id']; ?>">
                                        <button type="submit" class="btn" style="background: #fee2e2; color: #b91c1c; padding: 0.25rem 0.5rem; font-size: 0.75rem;">Annuler</button>
                                    </form>
                                <?php endif; ?>

                                <?php if (($role === 'doctor' || $role === 'admin') && $s === 'scheduled'): ?>
                                    <form method="POST" style="margin:0;">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                        <input type="hidden" name="action" value="complete">
                                        <input type="hidden" name="appointment_id" value="<?php echo $appt['id']; ?>">
                                        <button type="submit" class="btn" style="background: #dcfce7; color: #166534; padding: 0.25rem 0.5rem; font-size: 0.75rem;">Terminer</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
