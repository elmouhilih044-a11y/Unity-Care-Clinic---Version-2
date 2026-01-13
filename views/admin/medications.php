<?php
require_once 'repositories/MedicationRepository.php';

Auth::requireRole('admin');
$medRepo = new MedicationRepository($db);
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST['csrf_token'] ?? '');
    
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $name = $_POST['name'] ?? '';
        $desc = $_POST['description'] ?? '';
        $stock = $_POST['stock'] ?? 0;
        
        if ($medRepo->create($name, $desc, $stock)) {
            $message = "Médicament ajouté.";
        } else {
            $error = "Erreur lors de l'ajout.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id'] ?? 0;
        if ($medRepo->delete($id)) {
            $message = "Médicament supprimé.";
        } else {
            $error = "Erreur lors de la suppression.";
        }
    }
}

$medications = $medRepo->findAll();
?>

<div style="margin-bottom: 2rem;">
    <h3 style="color: var(--primary-color);">Catalogue des Médicaments</h3>
</div>

<?php if ($message): ?>
    <div class="alert alert-success" style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
        <?php echo h($message); ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: start;">
    <!-- Create Form -->
    <div class="card">
        <h4>Ajouter un médicament</h4>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label class="form-label">Nom</label>
                <input type="text" name="name" class="form-input" required> 
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Stock Initial</label>
                <input type="number" name="stock" class="form-input" value="0">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Ajouter</button>
        </form>
    </div>

    <!-- List -->
    <div class="card" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 1rem;">Nom</th>
                    <th style="padding: 1rem;">Stock</th>
                    <th style="padding: 1rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($medications as $m): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 500;"><?php echo h($m['name']); ?></div>
                            <div style="font-size: 0.75rem; color: var(--text-light);"><?php echo h($m['description']); ?></div>
                        </td>
                        <td style="padding: 1rem;"><?php echo h($m['stock_quantity']); ?></td>
                        <td style="padding: 1rem;">
                            <form method="POST" onsubmit="return confirm('Supprimer ce médicament ?');">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
                                <button type="submit" class="btn" style="background: #fee2e2; color: #b91c1c; padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
