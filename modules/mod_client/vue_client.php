<?php
class VueClient {

    public function afficherTableauBord($infos, $historique, $produits, $panierAffichage, $total, $code_en_attente, $message_statut) {

        $activeTab = ($code_en_attente || $message_statut) ? 'panier' : 'carte';

        ?>
        <style>

            .mobile-container {
                padding-bottom: 100px;
            }

            .mobile-header {
                position: sticky; top: 0; z-index: 1020;
                background: linear-gradient(135deg, #0d6efd, #0043a8);
                color: white; padding: 15px 20px;
                border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                margin-bottom: 20px;
            }
            .solde-badge {
                background-color: rgba(255,255,255,0.2); backdrop-filter: blur(5px);
                padding: 5px 15px; border-radius: 20px;
                font-weight: 700; font-size: 1.1rem; letter-spacing: 0.5px;
            }

            .product-card {
                border: none; border-radius: 15px; overflow: hidden;
                box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: transform 0.2s;
                background: white;
            }
            .product-card:active { transform: scale(0.96); }
            .emoji-icon { font-size: 2.5rem; display: block; margin-bottom: 5px; }

            .btn-circle {
                width: 35px; height: 35px; border-radius: 50%; padding: 0;
                display: flex; align-items: center; justify-content: center;
                font-weight: bold; font-size: 1.2rem;
            }

            .bottom-nav {
                position: fixed; bottom: 0; left: 0; width: 100%; height: 70px;
                background: white; border-top: 1px solid #f0f0f0;
                display: flex; justify-content: space-around; align-items: center;
                z-index: 1030; box-shadow: 0 -5px 20px rgba(0,0,0,0.05);
                padding-bottom: env(safe-area-inset-bottom); /* Pour iPhone X+ */
            }

            .nav-item-mobile {
                display: flex; flex-direction: column; align-items: center; justify-content: center;
                text-decoration: none !important; color: #9aa0a6;
                flex: 1; height: 100%; transition: color 0.2s;
            }

            .nav-item-mobile span.icon { font-size: 1.5rem; line-height: 1; margin-bottom: 4px; display: block; }
            .nav-item-mobile span.label { font-size: 0.75rem; font-weight: 500; display: block; }

            .nav-item-mobile.active { color: #0d6efd; }
            .nav-item-mobile.active span.icon { transform: scale(1.1); transition: transform 0.2s; }

            .notif-badge {
                position: absolute; top: -5px; right: -8px;
                width: 10px; height: 10px; background-color: #dc3545;
                border-radius: 50%; border: 2px solid white;
            }


            .sticky-action-bar {
                position: fixed; bottom: 70px; left: 0; right: 0;
                padding: 15px; background: linear-gradient(to top, rgba(255,255,255,1) 80%, rgba(255,255,255,0));
                z-index: 1010; text-align: center;
                pointer-events: none;
            }
            .sticky-action-bar .btn { pointer-events: auto; box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4); }

        </style>

        <div class="mobile-container">

            <div class="mobile-header d-flex justify-content-between align-items-center">
                <div>
                    <small style="opacity: 0.8;">Bonjour,</small>
                    <div class="h5 m-0 fw-bold"><?= htmlspecialchars($infos['prenom']) ?></div>
                </div>
                <div class="solde-badge">
                    <?= number_format($infos['solde'], 2) ?> €
                </div>
            </div>

            <div class="container">
                <div class="tab-content" id="mobileTabs">

                    <div class="tab-pane fade <?= $activeTab == 'carte' ? 'show active' : '' ?>" id="pane-carte">
                        <h6 class="text-uppercase text-muted fw-bold mb-3 ms-1">Au Menu</h6>
                        <div class="row g-3">
                            <?php foreach ($produits as $p): ?>
                                <div class="col-6 col-md-4">
                                    <div class="card product-card h-100">
                                        <div class="card-body text-center p-3 d-flex flex-column">
                                            <span class="emoji-icon">🥤</span>
                                            <h6 class="card-title fw-bold text-dark mb-1"><?= htmlspecialchars($p['nom']) ?></h6>
                                            <div class="mt-auto pt-2">
                                                <div class="text-primary fw-bold mb-2"><?= number_format($p['prix'], 2) ?> €</div>
                                                <form method="post" action="index.php?module=client&action=ajouterAuPanier">
                                                    <input type="hidden" name="id_produit" value="<?= $p['id_produit'] ?>">
                                                    <button type="submit" class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-bold">
                                                        Ajouter
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="tab-pane fade <?= $activeTab == 'panier' ? 'show active' : '' ?>" id="pane-panier">
                        <h6 class="text-uppercase text-muted fw-bold mb-3 ms-1">Votre Commande</h6>

                        <?php if ($message_statut): ?>
                            <div class="alert <?= strpos($message_statut, '✅') !== false ? 'alert-success' : 'alert-danger' ?> text-center shadow border-0 rounded-3 mb-4">
                                <h4 class="mb-0 fs-5"><?= htmlspecialchars($message_statut) ?></h4>
                            </div>
                        <?php
                        if (strpos($message_statut, '⏳') !== false): ?>
                        <script>setTimeout(function(){ window.location.reload(); }, 3000);</script>
                        <?php endif; ?>

                        <?php endif; ?>
                        <?php if ($code_en_attente): ?>
                            <div class="card bg-warning border-0 text-dark text-center shadow mb-4 rounded-3">
                                <div class="card-body py-4">
                                    <div class="spinner-border text-dark mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                                    <h5 class="fw-bold">EN ATTENTE...</h5>
                                    <p class="mb-1">Montrez ce code au bar :</p>
                                    <div class="display-3 fw-bold my-2" style="font-family: monospace; letter-spacing: 5px;">
                                        <?= htmlspecialchars($code_en_attente) ?>
                                    </div>
                                </div>
                            </div>
                            <script>setTimeout(function(){ window.location.reload(); }, 3000);</script>
                        <?php endif; ?>

                        <?php if (!empty($panierAffichage)): ?>
                            <div class="card shadow-sm border-0 rounded-3 overflow-hidden mb-5">
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($panierAffichage as $item): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 me-3 fs-4">🍺</div>
                                                <div>
                                                    <div class="fw-bold text-dark"><?= htmlspecialchars($item['nom']) ?></div>
                                                    <small class="text-muted">x<?= $item['quantite'] ?> à <?= $item['prix'] ?>€</small>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="fw-bold me-3"><?= number_format($item['total'], 2) ?>€</span>
                                                <?php if (!$code_en_attente): ?>
                                                    <form method="post" action="index.php?module=client&action=retirerDuPanier">
                                                        <input type="hidden" name="id_produit" value="<?= $item['id_produit'] ?>">
                                                        <button type="submit" class="btn btn-light text-danger btn-circle shadow-sm">
                                                            &times;
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="card-footer bg-light p-3 d-flex justify-content-between align-items-center">
                                    <span class="text-muted fw-bold">TOTAL</span>
                                    <span class="h4 text-primary fw-bold mb-0"><?= number_format($total, 2) ?> €</span>
                                </div>
                            </div>

                            <?php if (!$code_en_attente): ?>
                                <div class="sticky-action-bar">
                                    <form method="post" action="index.php?module=client&action=genererCodePanier">
                                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold fs-5">
                                            COMMANDER (<?= number_format($total, 2) ?> €)
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>

                        <?php else: ?>
                            <?php if (!$code_en_attente && !$message_statut): ?>
                                <div class="text-center py-5 mt-4">
                                    <div class="display-1 text-muted mb-3 opacity-25">🛒</div>
                                    <h5 class="text-muted">Votre panier est vide</h5>
                                    <button onclick="switchTab('carte')" class="btn btn-outline-primary mt-3 rounded-pill px-4">
                                        Voir la carte
                                    </button>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade" id="pane-historique">
                        <h6 class="text-uppercase text-muted fw-bold mb-3 ms-1">Historique</h6>
                        <?php if (!empty($historique)): ?>
                            <div class="list-group shadow-sm rounded-3 border-0">
                                <?php foreach ($historique as $h): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center p-3 border-light">
                                        <div>
                                            <div class="fw-bold text-dark">
                                                <?php
                                                if($h['type_operation'] == 'achat') echo "Conso Buvette";
                                                elseif($h['type_operation'] == 'rechargement') echo "Rechargement Compte";
                                                else echo "Autre";
                                                ?>
                                            </div>
                                            <small class="text-muted"><?= date("d/m/Y à H:i", strtotime($h['date_transaction'])) ?></small>
                                        </div>
                                        <span class="fw-bold <?= ($h['type_operation'] === 'rechargement') ? 'text-success' : 'text-danger' ?>">
                                            <?= ($h['type_operation'] === 'rechargement') ? '+' : '-' ?>
                                            <?= number_format($h['montant'], 2) ?> €
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-center text-muted mt-5">Aucune transaction récente.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <nav class="bottom-nav">
            <a href="#" class="nav-item-mobile <?= $activeTab == 'carte' ? 'active' : '' ?>" onclick="switchTab('carte'); return false;" id="nav-carte">
                <span class="icon">🍺</span>
                <span class="label">Carte</span>
            </a>

            <a href="#" class="nav-item-mobile <?= $activeTab == 'panier' ? 'active' : '' ?>" onclick="switchTab('panier'); return false;" id="nav-panier">
                <div class="position-relative">
                    <span class="icon">🛒</span>
                    <?php if(!empty($panierAffichage)): ?>
                        <span class="notif-badge"></span>
                    <?php endif; ?>
                </div>
                <span class="label">Panier</span>
            </a>

            <a href="#" class="nav-item-mobile" onclick="switchTab('historique'); return false;" id="nav-historique">
                <span class="icon">🕒</span>
                <span class="label">Historique</span>
            </a>
        </nav>

        <script>
            function switchTab(tabName) {
                document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('show', 'active'));
                document.querySelectorAll('.nav-item-mobile').forEach(el => el.classList.remove('active'));

                document.getElementById('pane-' + tabName).classList.add('show', 'active');
                document.getElementById('nav-' + tabName).classList.add('active');

                window.scrollTo({top: 0, behavior: 'smooth'});
            }
        </script>
        <?php
    }
}
?>