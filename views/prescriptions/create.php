<?php
require_once 'repositories/PatientRepository.php';
require_once 'repositories/MedicationRepository.php';
require_once 'repositories/PrescriptionRepository.php';

Auth::requireRole('doctor');

$patRepo = new PatientRepository($db);
$medRepo = new MedicationRepository($db);
$prescRepo = new PrescriptionRepository($db);

$patients = $patRepo->findAll();
$medications = $medRepo->findAll();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST['csrf_token'] ?? '');
    
    $patient_id = $_POST['patient_id'];
    $medication_id = $_POST['medication_id'];
    $dosage = $_POST['dosage'];
    $instructions = $_POST['instructions'];
    
    if (empty($patient_id) || empty($medication_id) || empty($dosage)) {
        $error = "Veuillez remplir les champs obligatoires.";
    } else {
        if ($prescRepo->create($patient_id, Auth::getUser()['id'], $medication_id, $dosage, $instructions)) {
            $message = "Ordonnance créée avec succès.";
        } else {
            $error = "Erreur lors de la création.";
        }
    }
}
?>

<div style="margin-bottom: 2rem;">
    <a href="dashboard.php?page=prescriptions" style="color: var(--text-light); text-decoration: none;"><i class="fa-solid fa-arrow-left"></i> Retour</a>
    <h3 style="color: var(--primary-color); margin-top: 1rem;">Nouvelle Prescription</h3>
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
            <label class="form-label">Patient</label>
            <select name="patient_id" class="form-input" required>
                <option value="">Sélectionner un patient...</option>
                <?php foreach ($patients as $p): ?>
                    <option value="<?php echo $p['id']; ?>">
                        <?php echo h($p['last_name'] . ' ' . $p['first_name']); ?> (<?php echo h($p['email']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Médicament</label>
            <select name="medication_id" class="form-input" required>
                <option value="">Sélectionner un médicament...</option>
                <?php foreach ($medications as $m): ?>
                    <option value="<?php echo $m['id']; ?>">
                        <?php echo h($m['name']); ?> (Stock: <?php echo h($m['stock_quantity']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Dosage</label>
            <input type="text" name="dosage" class="form-input" placeholder="ex: 500mg, 2 fois par jour" required>
        </div>

        <div class="form-group">
            <label class="form-label">Instructions / Notes</label>
            <textarea name="instructions" class="form-input" rows="3" placeholder="Instructions spéciales..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Créer l'ordonnance</button>
    </form>
</div>
