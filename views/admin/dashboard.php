<?php
// Stats for Admin
$userCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$apptCount = $db->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
$todayAppt = $db->query("SELECT COUNT(*) FROM appointments WHERE date = CURDATE()")->fetchColumn();
?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="border-left: 4px solid var(--primary-color);">
        <div style="font-size: 0.875rem; color: var(--text-light);">Utilisateurs Total</div>
        <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary-color);"><?php echo $userCount; ?></div>
    </div>
    <div class="card" style="border-left: 4px solid var(--secondary-color);">
        <div style="font-size: 0.875rem; color: var(--text-light);">Rendez-vous Total</div>
        <div style="font-size: 1.5rem; font-weight: 700; color: var(--secondary-color);"><?php echo $apptCount; ?></div>
    </div>
    <div class="card" style="border-left: 4px solid var(--success-color);">
        <div style="font-size: 0.875rem; color: var(--text-light);">Rendez-vous Aujourd'hui</div>
        <div style="font-size: 1.5rem; font-weight: 700; color: var(--success-color);"><?php echo $todayAppt; ?></div>
    </div>
</div>

<div class="card">
    <h4>Bienvenue Admin</h4>
    <p>Utilisez le menu latéral pour gérer les utilisateurs, les médicaments et voir tous les rendez-vous.</p>
</div>
