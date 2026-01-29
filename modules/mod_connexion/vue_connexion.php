<?php
class VueConnexion {
    public function afficherFormulaire($messageErreur = null) {
        ?>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0">Connexion</h3>
                        </div>
                        <div class="card-body">

                            <?php if ($messageErreur): ?>
                                <div class="alert alert-danger">
                                    <?= htmlspecialchars($messageErreur) ?>
                                </div>
                            <?php endif; ?>

                            <form action="index.php?module=connexion&action=connecter" method="post">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required placeholder="ex: barman@asso.fr">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mot de passe</label>
                                    <input type="password" name="mdp" class="form-control" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Se connecter</button>
                                </div>
                            </form>

                            <div class="mt-3 text-center">
                                <p class="mb-0">Pas encore de compte ?</p>
                                <a href="index.php?module=connexion&action=inscription" class="btn btn-link">Créer un compte</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function afficherFormulaireInscription($message = null) {
        ?>
        <div class="container mt-5">
            <div class="card shadow mx-auto" style="max-width: 500px;">
                <div class="card-header bg-success text-white"><h3>Inscription</h3></div>
                <div class="card-body">
                    <?php if ($message): ?>
                        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>
                    <form action="index.php?module=connexion&action=traiter_inscription" method="post">
                        <div class="mb-3"><label>Nom</label><input type="text" name="nom" class="form-control" required></div>
                        <div class="mb-3"><label>Prénom</label><input type="text" name="prenom" class="form-control" required></div>
                        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="mb-3"><label>Mot de passe</label><input type="password" name="mdp" class="form-control" required></div>
                        <button type="submit" class="btn btn-success w-100">Créer mon compte</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="index.php?module=connexion">Déjà un compte ? Connectez-vous</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>