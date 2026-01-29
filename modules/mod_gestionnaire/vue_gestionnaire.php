<?php
class VueGestionnaire {

    public function afficherDashboard($stats) {
        $benefice = $stats['ca_total'] - $stats['depenses_total'];
        ?>
        <div class="container mt-4">
            <h2 class="mb-4">📊 Tableau de bord Gestionnaire</h2>

            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card text-white bg-success shadow h-100">
                        <div class="card-header">Chiffre d'Affaires</div>
                        <div class="card-body text-center">
                            <h2 class="card-title">+ <?= number_format($stats['ca_total'], 2) ?> €</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white <?= $benefice >= 0 ? 'bg-info' : 'bg-secondary' ?> shadow h-100">
                        <div class="card-header">Bénéfice Net</div>
                        <div class="card-body text-center">
                            <h2 class="card-title"><?= number_format($benefice, 2) ?> €</h2>
                            <small>CA - Dépenses</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-danger shadow h-100">
                        <div class="card-header">Dépenses (Stocks)</div>
                        <div class="card-body text-center">
                            <h2 class="card-title">- <?= number_format($stats['depenses_total'], 2) ?> €</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-dark bg-warning shadow h-100">
                        <div class="card-header">Fonds Clients</div>
                        <div class="card-body text-center">
                            <h2 class="card-title"><?= number_format($stats['dette_clients'], 2) ?> €</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row text-center g-4">
                <div class="col-md-4">
                    <a href="index.php?module=gestionnaire&action=membres" class="btn btn-dark btn-lg w-100 shadow-sm p-4">
                        👥 Gérer les Membres
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="index.php?module=gestionnaire&action=form_stock" class="btn btn-primary btn-lg w-100 shadow-sm p-4">
                        📦 Commander du Stock
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="index.php?module=gestionnaire&action=historique_stock" class="btn btn-outline-secondary btn-lg w-100 shadow-sm p-4">
                        📜 Historique des Stocks
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    public function afficherGestionMembres($membres) {
        ?>
        <div class="container mt-4">
            <a href="index.php?module=gestionnaire" class="btn btn-secondary mb-3">← Retour Dashboard</a>
            <h3 class="mb-4">👥 Administration des Membres</h3>

            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-dark text-white">Inscrire un nouveau membre</div>
                <div class="card-body">
                    <form action="index.php?module=gestionnaire&action=ajouter_membre" method="post" class="row g-3">
                        <div class="col-md-2"><input type="text" name="nom" class="form-control" placeholder="Nom" required></div>
                        <div class="col-md-2"><input type="text" name="prenom" class="form-control" placeholder="Prénom" required></div>
                        <div class="col-md-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                        <div class="col-md-2"><input type="password" name="mdp" class="form-control" placeholder="Pass" required></div>
                        <div class="col-md-2">
                            <select name="role" class="form-select">
                                <option value="3">Client</option>
                                <option value="2">Barman</option>
                                <option value="4">Gestionnaire</option>
                            </select>
                        </div>
                        <div class="col-md-1"><button type="submit" class="btn btn-success">OK</button></div>
                    </form>
                </div>
            </div>

            <table class="table table-hover align-middle bg-white shadow-sm rounded">
                <thead class="table-light">
                <tr><th>Utilisateur</th><th>Email</th><th>Rôle</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php foreach ($membres as $m): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($m['nom']) ?></strong> <?= htmlspecialchars($m['prenom']) ?></td>
                        <td><?= htmlspecialchars($m['email']) ?></td>
                        <td>
                            <span class="badge bg-<?= $m['id_role'] == 1 ? 'danger' : ($m['id_role'] == 4 ? 'dark' : ($m['id_role'] == 2 ? 'warning' : 'info')) ?>">
                                <?= htmlspecialchars($m['role_nom']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($m['id_role'] == 1): ?>
                                <span class="text-danger fw-bold"><i class="bi bi-shield-lock"></i> Admin</span>
                            <?php else: ?>
                                <form action="index.php?module=gestionnaire&action=modifier_role" method="post" class="d-flex gap-1">
                                    <input type="hidden" name="id_user" value="<?= $m['id_user'] ?>">
                                    <select name="nouveau_role" class="form-select form-select-sm" style="width: auto;">
                                        <option value="3" <?= $m['id_role']==3?'selected':'' ?>>Client</option>
                                        <option value="2" <?= $m['id_role']==2?'selected':'' ?>>Barman</option>
                                        <option value="4" <?= $m['id_role']==4?'selected':'' ?>>Gestionnaire</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">OK</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    public function formulaireAchatStock($produits) {
        ?>
        <div class="container mt-4">
            <a href="index.php?module=gestionnaire" class="btn btn-secondary mb-3">← Retour Dashboard</a>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📦 Enregistrer une commande de stock</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?module=gestionnaire&action=valider_stock" method="post">

                        <div class="mb-4">
                            <label class="form-label fw-bold">1. Choisir l'article à commander</label>
                            <select name="id_produit" class="form-select form-select-lg" required>
                                <option value="" disabled selected>--- Sélectionnez un produit ---</option>
                                <?php foreach($produits as $p): ?>
                                    <option value="<?= $p['id_produit'] ?>">
                                        <?= htmlspecialchars($p['nom']) ?>
                                        (Stock actuel : <?= $p['stock_actuel'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">2. Quantité reçue</label>
                                <input type="number" name="quantite" class="form-control" placeholder="Ex: 24" required min="1">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">3. Coût total HT (€)</label>
                                <input type="number" name="cout" step="0.01" class="form-control" placeholder="Ex: 45.50" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">4. Date de commande</label>
                                <input type="datetime-local" name="date_commande" class="form-control"
                                       value="<?= date('Y-m-d\TH:i') ?>">
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                ✅ Valider l'entrée en stock
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    public function afficherHistoriqueStocks($historique) {
        ?>
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>📜 Historique des achats de stock</h3>
                <a href="index.php?module=gestionnaire" class="btn btn-secondary">
                    ← Retour Dashboard
                </a>
            </div>

            <?php if (empty($historique)): ?>
                <div class="alert alert-info">Aucun achat de stock enregistré pour le moment.</div>
            <?php else: ?>
                <table class="table table-striped shadow-sm">
                    <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Coût Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($historique as $h): ?>
                        <tr>
                            <td><?= date("d/m/Y H:i", strtotime($h['date_depense'])) ?></td>
                            <td><?= htmlspecialchars($h['nom_produit']) ?></td>
                            <td><?= $h['quantite'] ?></td>
                            <td class="text-danger">- <?= number_format($h['montant_total'], 2) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <?php
    }
}
?>