<?php
class VueBarman {

    public function afficherCaisse($clients, $produits, $nbcommandes = 0) {
    ?>
    <div class="container mt-3">
        <div class="card shadow-sm mb-4 border-primary">
            <div class="card-body bg-light">
                <h4 class="card-title text-primary"><i class="bi bi-qr-code"></i> Validation Click & Collect</h4>
                <form method="post" action="index.php?module=barman&action=commandes" class="d-flex gap-2">
                    <input type="text" name="code_recherche" class="form-control form-control-lg text-uppercase fw-bold"
                           placeholder="Scanner le code pour vérifier..." style="letter-spacing: 2px;" autofocus>
                    <button type="submit" class="btn btn-secondary btn-lg">Vérifier</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold"><i class="bi bi-box-seam"></i> État des Stocks</div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Produit</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produits as $p): ?>
                            <tr>
                                <td class="ps-3"><?= htmlspecialchars($p['nom']) ?></td>
                                <td class="text-center fw-bold"><?= $p['stock_actuel'] ?></td>
                                <td class="text-center">
                                    <?php if ($p['stock_actuel'] <= 0): ?>
                                        <span class="badge bg-danger">Rupture</span>
                                    <?php elseif ($p['stock_actuel'] < 10): ?>
                                        <span class="badge bg-warning text-dark">Faible</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">OK</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-end mb-5">
            <a href="index.php?module=barman&action=commandes" class="btn btn-primary position-relative me-2">
                 Commandes en cours
                <?php if ($nbcommandes > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= $nbcommandes ?>
                    </span>
                <?php endif; ?>
            </a>
            <a href="index.php?module=barman&action=recharge" class="btn btn-success">Recharger un compte</a>
            <a href="index.php?module=barman&action=inventaire" class="btn btn-outline-warning">Inventaire</a>
            <a href="index.php?module=barman&action=historique" class="btn btn-outline-info">Historique</a>
        </div>

        <div class="alert alert-secondary text-center shadow-sm">
            <i class="bi bi-info-circle"></i> Pour encaisser une vente, utilisez le <strong>code client</strong> ci-dessus ou gérez les <strong>commandes en cours</strong>.
        </div>
    </div>
    <?php
    }

    public function afficherPageInventaire($produits) {
        ?>
        <div class="container mt-4">
            <h2>📋 Inventaire (v0)</h2>
            <p class="text-muted">Indiquez la quantité réellement comptée pour chaque produit.</p>

            <form action="index.php?module=barman&action=valider_inventaire" method="post">
                <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                    <tr>
                        <th>Produit</th>
                        <th>Stock Théorique (Logiciel)</th>
                        <th>Stock Réel (Compté)</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($produits as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nom']) ?></td>

                            <td class="text-center">
                                <span class="badge bg-secondary"><?= $p['stock_actuel'] ?></span>
                            </td>

                            <td>
                                <input type="number"
                                       name="stock_reel[<?= $p['id_produit'] ?>]"
                                       value="<?= $p['stock_actuel'] ?>"
                                       min="0" class="form-control">
                            </td>

                            <td>
                                <a href="index.php?module=barman&action=supprimer_produit&id=<?= $p['id_produit'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce produit ?');">
                                    X
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
                <div class="d-flex justify-content-end mb-5">
                    <a href="index.php?module=barman" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-warning btn-lg">Valider et Sauvegarder l'inventaire</button>
                </div>
            </form>
            <hr>

            <div class="card shadow-sm bg-light mb-5">
                <div class="card-body">
                    <h4 class="card-title mb-3">Ajouter une nouvelle référence</h4>

                    <form action="index.php?module=barman&action=ajouter_produit" method="post" class="row g-3 align-items-end">

                        <div class="col-md-5">
                            <label class="form-label">Nom du produit</label>
                            <input type="text" name="nouveau_nom" class="form-control" placeholder="Ex: Orangina" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Prix de vente (€)</label>
                            <input type="number" name="nouveau_prix" step="0.01" class="form-control" placeholder="1.50" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Stock Initial</label>
                            <input type="number" name="nouveau_stock" class="form-control" value="0" required>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <?php
    }

    public function afficherHistorique($historique, $clients, $barmans) {
        $sel_client = $_POST['client'] ?? '';
        $sel_barman = $_POST['barman'] ?? '';
        $sel_date   = $_POST['date'] ?? '';
        ?>
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Historique des transactions</h2>
                <a href="index.php?module=barman" class="btn btn-outline-secondary">Retour Caisse</a>
            </div>

            <div class="card bg-light mb-4 shadow-sm">
                <div class="card-body py-3">
                    <form action="index.php?module=barman&action=historique" method="post" class="row g-3 align-items-end">

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Date</label>
                            <input type="date" name="date" class="form-control" value="<?= $sel_date ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Client</label>
                            <select name="client" class="form-select select-search"> <option value="">-- Tous les clients --</option>
                                <?php foreach ($clients as $c): ?>
                                    <option value="<?= $c['id_user'] ?>" <?= ($sel_client == $c['id_user']) ? 'selected' : '' ?>>
                                        <?= $c['nom'] ?> <?= $c['prenom'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Barman</label>
                            <select name="barman" class="form-select">
                                <option value="">-- Tous les barmans --</option>
                                <?php foreach ($barmans as $b): ?>
                                    <option value="<?= $b['id_user'] ?>" <?= ($sel_barman == $b['id_user']) ? 'selected' : '' ?>>
                                        <?= $b['nom'] ?> <?= $b['prenom'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">Filtrer</button>
                            <a href="index.php?module=barman&action=historique" class="btn btn-outline-danger">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (empty($historique)): ?>
                <div class="alert alert-warning text-center">Aucune transaction trouvée pour ces critères.</div>
            <?php else: ?>
        <div class="table-responsive">
                <table class="table table-hover shadow-sm bg-white">
                    <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Barman (Auteur)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($historique as $h): ?>
                        <tr>
                            <td><?= date("d/m/Y H:i", strtotime($h['date_hist'])) ?></td>
                            <td><?= htmlspecialchars($h['prenom_c']) ?> <?= htmlspecialchars($h['nom_c']) ?></td>

                            <td>
                                <?php if($h['type_operation'] == 'achat'): ?>
                                    <span class="badge bg-danger">Achat</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Rechargement</span>
                                <?php endif; ?>
                            </td>

                            <td class="fw-bold">
                                <?= $h['montant'] ?> €
                            </td>
                            <td class="text-muted small">
                                <?= htmlspecialchars($h['prenom_b']) ?> <?= htmlspecialchars($h['nom_b']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <?php
    }

    public function afficherPageRecharge($clients) {
        ?>
        <div class="container mt-4">
            <div class="mb-3">
                <a href="index.php?module=barman" class="btn btn-secondary">← Retour Caisse</a>
            </div>

            <div class="card shadow" style="max-width: 600px; margin: auto;">
                <div class="card-header bg-success text-white">
                    <h3>Recharger un compte Client</h3>
                </div>
                <div class="card-body">
                    <form action="index.php?module=barman&action=valider_recharge" method="post">
                        <div class="mb-3">
                            <label class="form-label">Client à créditer</label>
                            <select name="id_client" class="form-select select-search" required>
                                <?php foreach ($clients as $c): ?>
                                    <option value="<?= $c['id_user'] ?>">
                                        <?= $c['nom'] ?> <?= $c['prenom'] ?> (Solde actuel : <?= $c['solde'] ?> €)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Montant à ajouter (€)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="montant" class="form-control" placeholder="Ex: 20.00" required>
                                <span class="input-group-text">€</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 btn-lg">Valider le Rechargement</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    public function afficherEcranCommandes($commandes, $modele) {
        ?>
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-list-check"></i> Commandes en cours</h2>
                <a href="index.php?module=barman" class="btn btn-secondary">Retour Caisse</a>
            </div>

            <?php if (empty($commandes)): ?>
                <div class="alert alert-info text-center py-5">
                    <h4>Aucune commande en attente 🍹</h4>
                    <p>Tout est calme pour le moment.</p>
                </div>
            <?php else: ?>

                <div class="row">
                    <?php foreach ($commandes as $cmd): ?>
                        <?php
                            $isPaid = ($cmd['etat'] === 'a_servir');
                            $cardClass = $isPaid ? 'border-success shadow-lg' : 'border-primary';
                            $bgHeader  = $isPaid ? 'bg-success text-white' : 'bg-light';
                        ?>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow <?= $cardClass ?>">
                                <div class="card-header d-flex justify-content-between align-items-center <?= $bgHeader ?>">
                                    <span class="badge bg-dark text-uppercase fs-5">
                                        <?= htmlspecialchars($cmd['code_validation']) ?>
                                    </span>
                                    <small><?= date('H:i', strtotime($cmd['date_creation'])) ?></small>
                                </div>
                                <div class="card-body">

                                    <?php if ($isPaid): ?>
                                        <div class="alert alert-success py-1 text-center fw-bold">
                                            <i class="bi bi-cash-coin"></i> PAYÉ - À PRÉPARER
                                        </div>
                                    <?php endif; ?>

                                    <h5 class="card-title">
                                        <?= htmlspecialchars($cmd['prenom'] . ' ' . $cmd['nom']) ?>
                                    </h5>
                                    <hr>

                                    <ul class="list-group list-group-flush mb-3">
                                        <?php
                                        $panier = json_decode($cmd['contenu'], true);
                                        foreach ($panier as $id_prod => $qte):
                                            $info = $modele->getProduit($id_prod);
                                            $nomProduit = $info['nom'] ?? 'Produit Inconnu';
                                        ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                <?= htmlspecialchars($nomProduit) ?>
                                                <span class="badge bg-secondary rounded-pill">x<?= $qte ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>

                                    <div class="d-flex justify-content-between align-items-end mt-3">
                                        <div class="text-success fw-bold fs-4">
                                            <?= number_format($cmd['montant_total'], 2) ?> €
                                        </div>

                                        <div class="d-flex gap-2">
                                            <?php if ($isPaid): ?>
                                                <form method="post" action="index.php?module=barman&action=terminer_commande">
                                                    <input type="hidden" name="id_panier" value="<?= $cmd['id_panier'] ?>">
                                                    <button type="submit" class="btn btn-success btn-lg">
                                                        DONNER <i class="bi bi-check-circle-fill"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <form method="post" action="index.php?module=barman&action=annuler_commande"
                                                      onsubmit="return confirm('Annuler cette commande ?');">
                                                    <input type="hidden" name="code" value="<?= $cmd['code_validation'] ?>">
                                                    <button type="submit" class="btn btn-outline-danger">
                                                        <i class="bi bi-trash"></i> Annuler
                                                    </button>
                                                </form>

                                                <form method="post" action="index.php?module=barman&action=valider_code">
                                                    <input type="hidden" name="code" value="<?= $cmd['code_validation'] ?>">
                                                    <button type="submit" class="btn btn-primary">
                                                        Encaisser <i class="bi bi-arrow-right"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>
        <?php
    }
}
?>