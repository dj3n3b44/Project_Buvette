<?php
require_once 'modele_client.php';
require_once 'vue_client.php';

class ContClient {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleClient();
        $this->vue = new VueClient();

        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
    }

    public function afficherCompte() {
        $id = $_SESSION['id_user'];
        $infos = $this->modele->getInfosClient($id);
        $historique = $this->modele->getHistoriqueAchats($id);
        $produits = $this->modele->getProduitsDisponibles();

        $panierAffichage = [];
        $total = 0;
        foreach ($_SESSION['panier'] as $idProduit => $qte) {
            $p = $this->modele->getProduit($idProduit);
            if ($p) {
                $ligneTotal = $p['prix_vente'] * $qte;
                $total += $ligneTotal;
                $panierAffichage[] = [
                    'id_produit' => $idProduit, 'nom' => $p['nom'],
                    'prix' => $p['prix_vente'], 'quantite' => $qte, 'total' => $ligneTotal
                ];
            }
        }

        $code_en_attente = null;
        $message_statut = null;

        if (isset($_SESSION['code_validation'])) {
            $etat = $this->modele->getStatutPanier($_SESSION['code_validation']);

            if ($etat === 'valide') {
                $message_statut = "✅ COMMANDE VALIDÉE ! Servez-vous.";
                unset($_SESSION['code_validation']);
            }
            elseif ($etat === 'a_servir') {
                $message_statut = "⏳ PAIEMENT VALIDÉ ! Préparation en cours...";
            }
            elseif ($etat === 'annule') {
                $message_statut = "❌ Commande annulée par le barman.";
                unset($_SESSION['code_validation']);
            }
            elseif ($etat === 'en_attente') {
                $code_en_attente = $_SESSION['code_validation'];
            }
            else {
                unset($_SESSION['code_validation']);
            }
        }

        $this->vue->afficherTableauBord($infos, $historique, $produits, $panierAffichage, $total, $code_en_attente, $message_statut);
    }

    public function ajouterAuPanier() {
        $id = $_POST['id_produit'];
        $_SESSION['panier'][$id] = ($_SESSION['panier'][$id] ?? 0) + 1;
        header("Location: index.php?module=client");
    }

    public function retirerDuPanier() {
        $id = $_POST['id_produit'];
        if (isset($_SESSION['panier'][$id])) {
            $_SESSION['panier'][$id]--;
            if ($_SESSION['panier'][$id] <= 0) unset($_SESSION['panier'][$id]);
        }
        header("Location: index.php?module=client");
    }

    public function genererCodePanier() {
        if (empty($_SESSION['panier'])) {
            header("Location: index.php?module=client");
            return;
        }

        $code = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6);


        $total = $this->modele->calculerTotalPanier($_SESSION['panier']);

        $this->modele->creerPanierValidation(
            $_SESSION['id_user'],
            $code,
            $_SESSION['panier'],
            $total
        );

        $_SESSION['panier'] = [];
        $_SESSION['code_validation'] = $code;

        header("Location: index.php?module=client#panier");
    }
}
?>