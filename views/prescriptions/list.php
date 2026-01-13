<?php
require_once 'repositories/PrescriptionRepository.php';

$prescRepo = new PrescriptionRepository($db);
$prescriptions = [];

if ($role === 'patient') {
    $prescriptions = $prescRepo->findByPatient($user['id']);
} elseif ($role === 'doctor') {
    $prescriptions = $prescRepo->findByDoctor($user['id']);
}
?>

<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h3 style="color: var(--primary-color);">
            <?php echo ($role === 'doctor') ? 'Prescriptions Émises' : 'Mes Ordonnances'; ?>
        </h3>
        <p style="color: var(--text-light);">Historique des médicaments</p>
    </div>
    
    <?php if ($role === 'doctor'): ?>
        <a href="dashboard.php?page=prescriptions_create" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Nouvelle Prescription
        </a>
    <?php endif; ?>
</div>

<div class="card" style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid #e2e8f0;">
                <th style="padding: 1rem; color: var(--text-light); font-weight: 600; font-size: 0.875rem;">Date</th>
                <th style="padding: 1rem; color: var(--text-light); font-weight: 600; font-size: 0.875rem;">Médicament</th>
                <th style="padding: 1rem; color: var(--text-light); font-weight: 600; font-size: 0.875rem;">Dosage</th>
                <th style="padding: 1rem; color: var(--text-light); font-weight: 600; font-size: 0.875rem;">
                    <?php echo ($role === 'doctor') ? 'Patient' : 'Prescrit par'; ?>
                </th>
                <th style="padding: 1rem; color: var(--text-light); font-weight: 600; font-size: 0.875rem;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($prescriptions)): ?>
                <tr>
                    <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-light);">Aucune prescription trouvée.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($prescriptions as $p): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 1rem;">
                            <?php echo h(date('d/m/Y', strtotime($p['created_at']))); ?>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 500;"><?php echo h($p['medication_name']); ?></div>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 500;"><?php echo h($p['dosage']); ?></div>
                            <div style="font-size: 0.75rem; color: var(--text-light);"><?php echo h($p['instructions']); ?></div>
                        </td>
                        <td style="padding: 1rem;">
                            <?php 
                            if ($role === 'doctor') {
                                echo h($p['patient_first_name'] . ' ' . $p['patient_last_name']);
                            } else {
                                echo h("Dr. " . $p['doctor_first_name'] . ' ' . $p['doctor_last_name']);
                            }
                            ?>
                        </td>
                        <td style="padding: 1rem;">
                            <button class="btn" style="background: #f1f5f9; color: #64748b; padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="alert('Impression PDF bientôt disponible')">
                                <i class="fa-solid fa-print"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
