<?php
    require_once 'repositories/UserRepository.php';
    require_once 'repositories/DoctorRepository.php';
    require_once 'repositories/PatientRepository.php';

    Auth::requireRole('admin');
    $userRepo = new UserRepository($db);
    $docRepo = new DoctorRepository($db);
    $patRepo = new PatientRepository($db);

    $message = '';
    $error = '';

    // Handle Actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrfToken($_POST['csrf_token'] ?? '');
        $action = $_POST['action'] ?? '';

        if ($action === 'delete') {
            $id = $_POST['id'];
            $role = $_POST['role_to_delete'];
            
            $res = false;
            if ($role === 'doctor') $res = $docRepo->delete($id);
            elseif ($role === 'patient') $res = $patRepo->delete($id);
            else $res = $userRepo->delete($id); // Admin or just user

            if ($res) $message = "Utilisateur supprimé.";
            else $error = "Erreur lors de la suppression.";
        }
        elseif ($action === 'create') {
            $role = $_POST['role'];
            $pwHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            $baseData = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'password' => $pwHash,
                'phone' => $_POST['phone']
            ];

            $res = false;
            if ($role === 'admin') {
                $baseData['role'] = 'admin';
                $res = $userRepo->create($baseData);
            } elseif ($role === 'doctor') {
                $baseData['specialization'] = $_POST['specialization'];
                $baseData['department_id'] = $_POST['department_id'] ?? 1; // Default or select
                $res = $docRepo->create($baseData);
            } elseif ($role === 'patient') {
                $baseData['gender'] = $_POST['gender'];
                $baseData['date_of_birth'] = $_POST['date_of_birth'];
                $baseData['address'] = $_POST['address'];
                $res = $patRepo->create($baseData);
            }

            if ($res) $message = "Utilisateur créé avec succès.";
            else $error = "Erreur lors de la création.";
        }
    }

    $users = $userRepo->findAll();
?>

<div style="margin-bottom: 2rem;">
    <h3 style="color: var(--primary-color);">Gestion des Utilisateurs</h3>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo h($message); ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo h($error); ?></div>
<?php endif; ?>

<!-- Create Form -->
<div class="card" style="margin-bottom: 2rem;">
    <h4>Ajouter un utilisateur</h4>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        <input type="hidden" name="action" value="create">
        
        <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <div class="form-group">
                <label>Rôle</label>
                <select name="role" class="form-input" id="roleSelect" onchange="toggleFields()">
                    <option value="patient">Patient</option>
                    <option value="doctor">Médecin</option>
                    <option value="admin">Administrateur</option>
                </select>
            </div>
            <div class="form-group"><label>Prénom</label><input type="text" name="first_name" class="form-input" required></div>
            <div class="form-group"><label>Nom</label><input type="text" name="last_name" class="form-input" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" class="form-input" required></div>
            <div class="form-group"><label>Mot de passe</label><input type="password" name="password" class="form-input" required></div>
            <div class="form-group"><label>Téléphone</label><input type="text" name="phone" class="form-input"></div>
        </div>

        <!-- Doctor Fields -->
        <div id="doctorFields" style="display:none; border-top: 1px solid #eee; padding-top: 1rem; margin-bottom: 1rem;">
            <h5>Informations Médecin</h5>
            <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group"><label>Spécialité</label><input type="text" name="specialization" class="form-input"></div>
                <div class="form-group"><label>Département ID</label><input type="number" name="department_id" class="form-input" value="1"></div>
            </div>
        </div>

        <!-- Patient Fields -->
        <div id="patientFields" style="display:block; border-top: 1px solid #eee; padding-top: 1rem; margin-bottom: 1rem;">
            <h5>Informations Patient</h5>
            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label>Genre</label>
                    <select name="gender" class="form-input">
                        <option value="Male">Homme</option>
                        <option value="Female">Femme</option>
                    </select>
                </div>
                <div class="form-group"><label>Date de Naissance</label><input type="date" name="date_of_birth" class="form-input"></div>
                <div class="form-group" style="grid-column: span 2;"><label>Adresse</label><input type="text" name="address" class="form-input"></div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
    </form>
</div>

<script>
function toggleFields() {
    const role = document.getElementById('roleSelect').value;
    document.getElementById('doctorFields').style.display = role === 'doctor' ? 'block' : 'none';
    document.getElementById('patientFields').style.display = role === 'patient' ? 'block' : 'none';
}
</script>

<div class="card" style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid #e2e8f0;">
                <th style="padding: 1rem;">Nom</th>
                <th style="padding: 1rem;">Email</th>
                <th style="padding: 1rem;">Rôle</th>
                <th style="padding: 1rem;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 1rem;">
                        <?php echo h($u['first_name'] . ' ' . $u['last_name']); ?>
                    </td>
                    <td style="padding: 1rem;"><?php echo h($u['email']); ?></td>
                    <td style="padding: 1rem;"><?php echo ucfirst($u['role']); ?></td>
                    <td style="padding: 1rem;">
                        <form method="POST" onsubmit="return confirm('Supprimer ?');" style="display:inline;">
                             <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                             <input type="hidden" name="action" value="delete">
                             <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                             <input type="hidden" name="role_to_delete" value="<?php echo $u['role']; ?>">
                             <button class="btn" style="background: #fee2e2; color: #b91c1c; padding: 0.25rem 0.5rem;"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
