<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Buvette Associative</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Ma Buvette </a>

        <div class="d-flex align-items-center">
            <?php if (isset($_SESSION['id_user'])): ?>
                <?php

                $roleId = $_SESSION['id_role'] ?? $_SESSION['role'] ?? 0;

                $libelles = [
                    1 => 'Admin',
                    2 => 'Barman',
                    3 => 'Membre',
                    4 => 'Gestionnaire'
                ];

                $statutTexte = $libelles[$roleId] ?? 'Utilisateur';
                ?>

                <span class="text-white me-3">
                         <strong><?= htmlspecialchars($_SESSION['prenom']) ?> <?= htmlspecialchars($_SESSION['nom']) ?></strong>
                        <span class="badge bg-warning text-dark ms-1">
                            <?= htmlspecialchars($statutTexte) ?>
                        </span>
                    </span>
                <a href="index.php?module=connexion&action=deconnecter" class="btn btn-sm btn-danger">
                    Déconnexion
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main>
    <?= $affichageModule ?? "<div class='alert alert-danger'>Erreur d'affichage</div>" ?>
</main>

<footer class="text-center mt-5 text-muted">
    <small>SAE Buvette - Contacter le gestionnaire à admin@exemple.fr pour créer une association.</small>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select-search').select2({
            theme: 'bootstrap-5',
            placeholder: " Rechercher un client...",
            allowClear: true
        });
    });
</script>
</body>
</html>