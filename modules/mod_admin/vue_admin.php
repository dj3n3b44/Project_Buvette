<?php
class VueAdmin {

    public function afficherDashboard($assos, $nbTotalUsers) {
        ?>
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-danger"><i class="bi bi-shield-lock-fill"></i> Super-Admin Panel</h2>
                <span class="badge bg-secondary">Utilisateurs Plateforme : <?= $nbTotalUsers ?></span>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm border-danger mb-4">
                        <div class="card-header bg-danger text-white">Créer une Association</div>
                        <div class="card-body">
                            <form action="index.php?module=admin&action=creer_asso" method="post">
                                <div class="mb-3">
                                    <label class="form-label">Nom</label>
                                    <input type="text" name="nom" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Code d'accès</label>
                                    <input type="text" name="code" class="form-control text-uppercase" required>
                                </div>
                                <button type="submit" class="btn btn-danger w-100">Créer</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">Associations existantes</div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Code</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($assos as $a): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($a['nom']) ?></td>
                                        <td><code><?= htmlspecialchars($a['code_acces']) ?></code></td>
                                        <td class="text-end">
                                            <a href="index.php?module=admin&action=voir_membres&id=<?= $a['id_association'] ?>"
                                               class="btn btn-primary btn-sm me-2">
                                                <i class="bi bi-people-fill"></i> Gérer membres
                                            </a>

                                            <a href="index.php?module=admin&action=supprimer_asso&id=<?= $a['id_association'] ?>"
                                               class="btn btn-outline-danger btn-sm"
                                               onclick="return confirm('ATTENTION : Supprimer cette association effacera TOUS ses membres, produits et historiques. Continuer ?');">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function afficherListeMembres($id_asso, $nom_asso, $membres) {
        ?>
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Membres de : <span class="text-primary"><?= htmlspecialchars($nom_asso) ?></span></h3>
                <a href="index.php?module=admin" class="btn btn-secondary">← Retour Dashboard</a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if(empty($membres)): ?>
                        <div class="alert alert-info">Aucun membre inscrit dans cette association pour l'instant.</div>
                    <?php else: ?>
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Utilisateur</th>
                                <th>Rôle Actuel</th>
                                <th class="text-end">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($membres as $m): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($m['nom']) ?> <?= htmlspecialchars($m['prenom']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($m['email']) ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        $bg = 'secondary';
                                        if($m['id_role'] == 4) $bg = 'dark'; // Gestionnaire
                                        if($m['id_role'] == 2) $bg = 'warning text-dark'; // Barman
                                        if($m['id_role'] == 3) $bg = 'info'; // Membre
                                        ?>
                                        <span class="badge bg-<?= $bg ?>"><?= htmlspecialchars($m['role_nom']) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <?php if($m['id_role'] == 4): ?>
                                            <span class="text-success"><i class="bi bi-check-circle-fill"></i> Est Gestionnaire</span>
                                        <?php elseif($m['id_role'] == 1): ?>
                                            <span class="text-danger fw-bold">Super Admin</span>
                                        <?php else: ?>
                                            <form action="index.php?module=admin&action=promouvoir_gestionnaire" method="post" style="display:inline;">
                                                <input type="hidden" name="id_user" value="<?= $m['id_user'] ?>">
                                                <input type="hidden" name="id_asso" value="<?= $id_asso ?>">
                                                <button type="submit" class="btn btn-outline-dark btn-sm"
                                                        onclick="return confirm('Nommer <?= addslashes($m['nom']) ?> comme Gestionnaire ?');">
                                                    <i class="bi bi-person-badge"></i> Nommer Gestionnaire
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}
?>