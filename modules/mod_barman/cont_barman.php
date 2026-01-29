<?php
require_once 'modele_barman.php';
require_once 'vue_barman.php';

class ContBarman {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleBarman();
        $this->vue = new VueBarman();
    }

    public function accueil() {
        $clients = $this->modele->getListeClients();
        $produits = $this->modele->getListeProduits();

        $nbCommandes = $this->modele->getNbCommandesEnAttente();

        $this->vue->afficherCaisse($clients, $produits, $nbCommandes);
    }

    public function voirHistorique() {
        $filtre_client = isset($_POST['client']) ? $_POST['client'] : null;
        $filtre_barman = isset($_POST['barman']) ? $_POST['barman'] : null;
        $filtre_date   = isset($_POST['date']) ? $_POST['date'] : null;

        $tousLesClients = $this->modele->getListeClients();
        $tousLesBarmans = $this->modele->getListeBarmans();

        $liste_operations = $this->modele->getHistorique($filtre_client, $filtre_barman, $filtre_date);

        $this->vue->afficherHistorique($liste_operations, $tousLesClients, $tousLesBarmans);
    }


    public function formulaireInventaire() {
        $produits = $this->modele->getListeProduits();
        $this->vue->afficherPageInventaire($produits);
    }

    public function ajouterNouveauProduit()
    {
        if (isset($_POST['nouveau_nom']) && isset($_POST['nouveau_prix'])) {
            $nom = $_POST['nouveau_nom'];
            $prix = $_POST['nouveau_prix'];
            $stock = $_POST['nouveau_stock'];
            $id_asso = $_SESSION['id_asso_active'];

            $this->modele->creerProduit($nom,$prix,$stock, $id_asso);
            echo "<div class='alert alert-success'>Le produit " . htmlspecialchars($nom) . " a été ajouté !</div>";
        }
        $this->formulaireInventaire();
    }

    public function supprimerProduit() {
        if (isset($_GET['id'])) {
            $id_produit = $_GET['id'];
            $this->modele->supprimerProduit($id_produit);
            echo "<div class='alert alert-success'>Produit supprimé avec succès.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur : Aucun produit spécifié.</div>";
        }

        $this->formulaireInventaire();
    }

    public function validerInventaire() {
        if (!isset($_POST['stock_reel'])) {
             $this->accueil();
             return;
        }

        $stocks_reels = $_POST['stock_reel'];

        $nb_modifs = 0;
        $ecart_total = 0;

        foreach ($stocks_reels as $id_produit => $qte_reelle) {
            $stock_theorique = $this->modele->getStockProduit($id_produit);

            if ($qte_reelle != $stock_theorique) {
                $this->modele->updateStockReel($id_produit, $qte_reelle);
                $ecart_total += ($qte_reelle - $stock_theorique);
                $nb_modifs++;
            }
        }

        echo "<div class='alert alert-info'>Inventaire terminé. $nb_modifs produits mis à jour. Écart total : $ecart_total unités.</div>";
        $this->accueil();
    }

    public function validerCodePanier() {
        if (!isset($_POST['code']) || empty($_POST['code'])) {
            $this->accueil();
            return;
        }

        $code = $_POST['code'];
        $panierData = $this->modele->getPanierByCode($code);

        if (!$panierData || $panierData['etat'] !== 'en_attente') {
            echo "<div class='container mt-3 alert alert-danger'>Code invalide ou commande déjà traitée.</div>";
            $this->accueil();
            return;
        }

        $id_client = $panierData['id_client'];
        $total = $panierData['montant_total'];
        $soldeClient = $this->modele->getSoldeClient($id_client);

        if ($soldeClient < $total) {
            $this->modele->annulerPanier($code);
            echo "<div class='container mt-3 alert alert-danger'>Solde insuffisant. Commande annulée.</div>";
            $this->accueil();
            return;
        }

        $contenu = json_decode($panierData['contenu'], true);
        foreach ($contenu as $id_produit => $qte) {
            $this->modele->enleverStock($id_produit, $qte);
        }
        $this->modele->debiterClient($id_client, $total);
        $this->modele->enregistrerTransaction($id_client, $total, 'achat');

        $this->modele->marquerPanierAServir($panierData['id_panier']);

        header("Location: index.php?module=barman&action=commandes");
        exit();
    }

    public function terminerCommande() {
        if (isset($_POST['id_panier'])) {
            $this->modele->marquerPanierValide($_POST['id_panier']);
            echo "<div class='alert alert-success container mt-3'>Commande archivée !</div>";
        }
        $this->voirCommandesEnCours();
    }

    public function formulaireRecharge() {
        $clients = $this->modele->getListeClients();
        $this->vue->afficherPageRecharge($clients);
    }

    public function validerRecharge() {
        if (isset($_POST['id_client']) && isset($_POST['montant'])) {
            $id_client = $_POST['id_client'];
            $montant = floatval($_POST['montant']); // Sécurité

            if ($montant > 0) {
                $this->modele->crediterClient($id_client, $montant);
                $this->modele->enregistrerTransaction($id_client, $montant, 'rechargement');
                echo "<div class='container mt-3 alert alert-success'>Compte rechargé de $montant € !</div>";
            }
        }
        $this->formulaireRecharge();
    }

    public function voirCommandesEnCours() {
        $commandes = $this->modele->getCommandesEnAttente();

        if (isset($_POST['code_recherche']) && !empty($_POST['code_recherche'])) {
            $recherche = strtoupper(trim($_POST['code_recherche']));

            $commandes_filtrees = [];
            foreach ($commandes as $cmd) {
                if ($cmd['code_validation'] === $recherche) {
                    $commandes_filtrees[] = $cmd;
                }
            }
            $commandes = $commandes_filtrees;

            if (empty($commandes)) {
                echo "<div class='container mt-3 alert alert-warning'>Aucune commande trouvée pour le code : <strong>$recherche</strong></div>";
            }
        }

        $this->vue->afficherEcranCommandes($commandes, $this->modele);
    }

    public function annulerCommandeEnCours() {
        if (isset($_POST['code'])) {
            $code = $_POST['code'];
            $this->modele->annulerPanier($code);
            echo "<div class='container mt-3 alert alert-warning'>Commande $code annulée.</div>";
        }
        $this->voirCommandesEnCours();
    }

    public function ajouterPanierManuel() {
        if (!isset($_POST['client']) || !isset($_POST['qte'])) {
            $this->accueil();
            return;
        }

        $id_client = $_POST['client'];
        $qte_array = $_POST['qte'];

        $panier = [];
        $total = 0;
        foreach ($qte_array as $id_prod => $qte) {
            if ($qte > 0) {
                $panier[$id_prod] = $qte;
                $infos = $this->modele->getProduit($id_prod);
                $total += $infos['prix_vente'] * $qte;

                $stock = $this->modele->getStockProduit($id_prod);
                if ($stock < $qte) {
                    echo "<div class='container mt-3 alert alert-danger'>Stock insuffisant pour " . htmlspecialchars($infos['nom']) . ".</div>";
                    $this->accueil();
                    return;
                }
            }
        }

        if (empty($panier)) {
            echo "<div class='container mt-3 alert alert-warning'>Veuillez sélectionner au moins un produit.</div>";
            $this->accueil();
            return;
        }

        $solde = $this->modele->getSoldeClient($id_client);
        if ($solde < $total) {
            echo "<div class='container mt-3 alert alert-danger'>Le client n'a pas assez de solde ($solde € dispo).</div>";
            $this->accueil();
            return;
        }

        $this->modele->creerPanierManuel($id_client, json_encode($panier), $total);

        header("Location: index.php?module=barman&action=commandes");
        exit();
    }
}
?>