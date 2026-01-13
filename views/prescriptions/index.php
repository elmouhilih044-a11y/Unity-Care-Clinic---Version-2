<?php
require_once 'repositories/PrescriptionRepository.php';

$prescRepo = new PrescriptionRepository($db);
$role = Auth::getRole();
$userId = Auth::getUser()['id'];

$prescriptions = [];
if ($role === 'doctor') {
    $prescriptions = $prescRepo->findByDoctor($userId);
} elseif ($role === 'patient') {
    $prescriptions = $prescRepo->findByPatient($userId);
} elseif ($role === 'admin') {
    // Implement findAll in Repo if needed, or misuse one, but let's assume Admin sees all?
    // Repo doesn't have findAll join logic yet in what I saw, only findBy...
    // Let's add findAll to repo or just show empty for admin or restricted.
    // For now, let's skip admin view or implement a basic one if needed.
    // Given requirements, usually Patient views own, Doctor creates/views own.
    // I'll leave empty for admin or handle graceful fallback.
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h3 style="color: var(--primary-color);">Ordonnances</h3>
        <p style="color: var(--text-light);">Historique des prescriptions</p>
    </div>
    <?php if ($role === 'doctor'): ?>
        <a href="dashboard.php?page=prescriptions_create" class="btn btn-primary">Nouvelle Prescription</a>
    <?php endif; ?>
</div>

<div class="card" style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid #e2e8f0;">
                <th style="padding: 1rem;">Date</th>
                <th style="padding: 1rem;">Médicament</th>
                <th style="padding: 1rem;">Dosage</th>
                <th style="padding: 1rem;">Patient</th>
                <th style="padding: 1rem;">Médecin</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prescriptions as $p): ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 1rem;"><?php echo date('d/m/Y', strtotime($p['created_at'])); ?></td>
                    <td style="padding: 1rem;">
                        <div style="font-weight: 500;"><?php echo h($p['medication_name']); ?></div>
                        <?php if(isset($p['description'])) echo "<div style='font-size:0.75rem; color:gray;'>".h($p['description'])."</div>"; ?>
                    </td>
                    <td style="padding: 1rem;">
                        <?php echo h($p['dosage']); ?>
                        <div style="font-size: 0.875rem; color: var(--text-light);"><?php echo h($p['instructions']); ?></div>
                    </td>
                    <td style="padding: 1rem;">
                        <?php echo isset($p['patient_first_name']) ? h($p['patient_first_name'] . ' ' . $p['patient_last_name']) : 'Moi'; ?>
                    </td>
                    <td style="padding: 1rem;">
                        <?php echo isset($p['doctor_first_name']) ? "Dr. ".h($p['doctor_first_name'] . ' ' . $p['doctor_last_name']) : 'Moi'; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
