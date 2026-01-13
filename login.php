<?php
require_once 'classes/Database.php';
require_once 'classes/Auth.php';
require_once 'repositories/UserRepository.php';
require_once 'includes/security.php';

Auth::init();


if (Auth::isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$db = Database::getInstance()->getConnection();
$userRepo = new UserRepository($db);
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $user = $userRepo->findByEmail($email);

        if ($user &&  $user['password'] === $password) {
            Auth::login($user);
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}

require_once 'views/layout/header.php';
?>

<div class="min-h-[calc(100vh-140px)] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-primary mb-2">Bienvenue</h2>
            <p class="text-gray-500 text-sm">Connectez-vous à votre compte</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r" role="alert">
                <p class="text-red-700 text-sm font-medium"><?php echo h($error); ?></p>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="mt-8 space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" placeholder="exemple@email.com" required value="<?php echo h($_POST['email'] ?? ''); ?>">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input type="password" name="password" class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" placeholder="••••••••" required>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <a href="#" class="font-medium text-primary hover:text-primary-dark text-sm transition-colors">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 shadow-md hover:shadow-lg">
                Se connecter
            </button>
        </form>

        <div class="border-t border-gray-200 pt-6 mt-6 text-center">
             <p class="text-xs text-gray-500">
                Comptes de test disponibles dans le README.
            </p>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
