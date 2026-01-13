<?php
require_once 'repositories/DoctorRepository.php';
require_once 'repositories/AppointmentRepository.php';

Auth::requireRole('patient');

$docRepo = new DoctorRepository($db);
$apptRepo = new AppointmentRepository($db);
$doctors = $docRepo->findAll(); // Gets user details + specialization

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST['csrf_token'] ?? '');
    
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['date'];
    $time = $_POST['time']; // Should validate time slots technically
    $reason = $_POST['reason'];
    
    // Basic validation
    if (empty($doctor_id) || empty($date) || empty($time)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        if ($apptRepo->create($date, $time, $doctor_id, Auth::getUser()['id'], $reason)) {
            $message = "Rendez-vous confirmé.";
        } else {
            $error = "Erreur lors de la prise de rendez-vous.";
        }
    }
}
?>

<div style="margin-bottom: 2rem;">
    <a href="dashboard.php?page=appointments" style="color: var(--text-light); text-decoration: none;"><i class="fa-solid fa-arrow-left"></i> Retour</a>
    <h3 style="color: var(--primary-color); margin-top: 1rem;">Nouveau Rendez-vous</h3>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo h($message); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo h($error); ?></div>
<?php endif; ?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        
        <div class="form-group">
            <label class="form-label">Médecin Spécialiste</label>
            <select name="doctor_id" class="form-input" required>
                <option value="">Choisir un médecin...</option>
                <?php foreach ($doctors as $d): ?>
                    <option value="<?php echo $d['id']; ?>">
                        Dr. <?php echo h($d['last_name'] . ' ' . $d['first_name']); ?> (<?php echo h($d['specialization']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-input" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Heure</label>
                <select name="time" class="form-input" required>
                    <?php 
                    for($h=9; $h<=17; $h++) {
                        echo "<option value='".sprintf('%02d', $h).":00'>".sprintf('%02d', $h).":00</option>";
                        echo "<option value='".sprintf('%02d', $h).":30'>".sprintf('%02d', $h).":30</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Motif de consultation</label>
            <textarea name="reason" class="form-input" rows="3" placeholder="Ex: Maux de tête persistants..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Confirmer le Rendez-vous</button>
    </form>
</div>
