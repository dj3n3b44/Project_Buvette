<?php
class VueChoixAsso {
    public function afficherListe($actives, $message = null) {
        ?>
        <div class="container mt-5">
            <?php if ($message) echo $message; ?>

            <div class="row">
                <div class="col-md-7">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5>Mes Associations</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($actives)): ?>
                                <p class="text-muted">Vous n'avez rejoint aucune association.</p>
                            <?php else: ?>
                                <div class="list-group">
                                    <?php foreach ($actives as $a): ?>
                                        <a href="index.php?module=choix_asso&action=entrer&id_asso=<?= $a['id_association'] ?>&id_role=<?= $a['id_role'] ?>"
                                           class="list-group-item list-group-item-action mb-2 border rounded p-3 d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><?= htmlspecialchars($a['nom']) ?></strong><br>
                                                <small class="text-muted">Rôle : <?= htmlspecialchars($a['nom_role']) ?></small>
                                            </div>
                                            <span class="btn btn-primary btn-sm">Entrer</span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h5>Rejoindre une Asso</h5>
                        </div>
                        <div class="card-body">
                            <form action="index.php?module=choix_asso&action=rejoindre" method="post">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Code Secret</label>
                                    <input type="text" name="code_asso" class="form-control text-uppercase" placeholder="EX: BDE-INFO" required>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Envoyer la demande</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>