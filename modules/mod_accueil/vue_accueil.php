<?php
class VueAccueil {
    public function afficherFormulaireCode($erreur = null) {
        ?>
        <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">

            <div class="text-center mb-4">
                <h1 class="display-1">🍻</h1>
                <h2 class="display-5">Accès Buvette</h2>
                <p class="text-muted">Veuillez entrer le code de votre association.</p>
            </div>

            <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
                <?php if ($erreur): ?>
                    <div class="alert alert-danger text-center"><?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>

                <form action="index.php?module=accueil&action=chercher" method="post">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Code Association</label>
                        <input type="text" name="code_asso" class="form-control form-control-lg text-center text-uppercase"
                               placeholder="Ex: BDE-INFO" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-lg">Entrer </button>
                </form>
            </div>

            <div class="mt-4 text-muted small">
                Vous n'avez pas de code ? Demandez à votre responsable bar.
            </div>
        </div>
        <?php
    }
}
?>