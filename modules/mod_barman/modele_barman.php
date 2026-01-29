<?php
require_once 'connexion.php';

class ModeleBarman extends Connexion {

    public function getListeProduits() {
        $id_asso = $_SESSION['id_asso_active'] ?? 0;
        $sql = "SELECT * FROM Produit WHERE id_association = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$id_asso]);

        return $req->fetchAll();
    }

    public function getListeClients() {
        $id_asso = $_SESSION['id_asso_active'] ?? 0;

        $sql = "SELECT U.* FROM Utilisateur U
                JOIN Membre M ON U.id_user = M.id_user
                WHERE M.id_association = ? AND M.id_role = 3
                ORDER BY U.nom ASC";

        $req = self::$bdd->prepare($sql);
        $req->execute([$id_asso]);

        return $req->fetchAll();
    }

    public function enleverStock($id_produit, $quantite) {
        $sql = "UPDATE Produit SET stock_actuel = stock_actuel - ? WHERE id_produit = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$quantite, $id_produit]);
    }

    public function debiterClient($id_client, $montant) {
        $sql = "UPDATE Utilisateur SET solde = solde - ? WHERE id_user = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$montant, $id_client]);
    }

    public function getSoldeClient($id_client) {
        $req = self::$bdd->prepare("SELECT solde FROM Utilisateur WHERE id_user = ?");
        $req->execute([$id_client]);
        return $req->fetchColumn();
    }

    public function getStockProduit($id_produit) {
        $req = self::$bdd->prepare("SELECT stock_actuel FROM Produit WHERE id_produit = ?");
        $req->execute([$id_produit]);
        return $req->fetchColumn();
    }

    public function updateStockReel($id_produit, $quantite_reelle) {
        $sql = "UPDATE Produit SET stock_actuel = ? WHERE id_produit = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$quantite_reelle, $id_produit]);
    }

    public function creerProduit($nom, $prix, $stock, $id_asso) {
        $sql = "INSERT INTO Produit (nom, prix_vente, stock_actuel, id_association, id_categorie) values (?,?,?,?,1)";
        $req = self::$bdd->prepare($sql);
        $req->execute([$nom, $prix,$stock, $id_asso]);
    }

    public function supprimerProduit($id_produit) {
        $sql = "DELETE FROM Produit WHERE id_produit = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$id_produit]);
    }

    public function enregistrerTransaction($id_client, $montant, $type) {
        $id_barman = $_SESSION['id_user'];
        $id_asso = $_SESSION['id_asso_active'];

        $sql = "INSERT INTO Historique (montant, type_operation, id_client, id_barman, id_association) 
                VALUES (?, ?, ?, ?, ?)";
        $req = self::$bdd->prepare($sql);
        $req->execute([$montant, $type, $id_client, $id_barman, $id_asso]);
    }

    public function getListeBarmans() {
        $id_asso = $_SESSION['id_asso_active'] ?? 0;
        $sql = "SELECT U.* FROM Utilisateur U
                JOIN Membre M ON U.id_user = M.id_user
                WHERE M.id_association = ? AND M.id_role = 2
                ORDER BY U.nom ASC";
        $req = self::$bdd->prepare($sql);
        $req->execute([$id_asso]);
        return $req->fetchAll();
    }

    public function getHistorique($client_id = null, $barman_id = null, $date = null) {
        $id_asso = $_SESSION['id_asso_active'];

        $sql = "SELECT H.*, 
                       Client.nom as nom_c, Client.prenom as prenom_c,
                       Barman.nom as nom_b, Barman.prenom as prenom_b
                FROM Historique H
                LEFT JOIN Utilisateur Client ON H.id_client = Client.id_user
                LEFT JOIN Utilisateur Barman ON H.id_barman = Barman.id_user
                WHERE H.id_association = ?";

        $params = [$id_asso];

        if (!empty($client_id)) {
            $sql .= " AND H.id_client = ?";
            $params[] = $client_id;
        }

        if (!empty($barman_id)) {
            $sql .= " AND H.id_barman = ?";
            $params[] = $barman_id;
        }

        if (!empty($date)) {
            $sql .= " AND DATE(H.date_hist) = ?";
            $params[] = $date;
        }

        $sql .= " ORDER BY H.date_hist DESC LIMIT 50";

        $req = self::$bdd->prepare($sql);
        $req->execute($params);
        return $req->fetchAll();
    }

    public function getPanierByCode($code) {
        $req = self::$bdd->prepare("SELECT * FROM Panier_validation WHERE code_validation = ?");
        $req->execute([$code]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function marquerPanierValide($id_panier) {
        $sql = "UPDATE Panier_validation SET etat = 'valide' WHERE id_panier = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$id_panier]);
    }

    public function crediterClient($id_client, $montant) {
        $sql = "UPDATE Utilisateur SET solde = solde + ? WHERE id_user = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$montant, $id_client]);
    }

    public function getProduit($id) {
        $req = self::$bdd->prepare("SELECT nom, prix_vente FROM Produit WHERE id_produit = ?");
        $req->execute([$id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function getCommandesEnAttente() {
        $sql = "SELECT P.*, U.nom, U.prenom, U.solde 
            FROM Panier_validation P
            JOIN Utilisateur U ON P.id_client = U.id_user
            WHERE P.etat IN ('en_attente', 'a_servir')
            AND U.solde >= P.montant_total  
            ORDER BY FIELD(P.etat, 'a_servir', 'en_attente'), P.date_creation ASC";
        return self::$bdd->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function marquerPanierAServir($id_panier) {
        $sql = "UPDATE Panier_validation SET etat = 'a_servir' WHERE id_panier = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$id_panier]);
    }

    public function annulerPanier($code) {
        $sql = "UPDATE Panier_validation SET etat = 'annule' WHERE code_validation = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$code]);
    }

    public function creerPanierManuel($id_client, $contenu_json, $total) {
        $code = 'CPT-' . substr(str_shuffle('0123456789AZERTY'), 0, 4);

        $sql = "INSERT INTO Panier_validation (code_validation, id_client, contenu, montant_total, etat)
                VALUES (?, ?, ?, ?, 'en_attente')";
        $req = self::$bdd->prepare($sql);
        $req->execute([$code, $id_client, $contenu_json, $total]);
    }

    public function getNbCommandesEnAttente() {
        $sql = "SELECT COUNT(*) FROM Panier_validation WHERE etat = 'en_attente'";
        $req = self::$bdd->query($sql);
        return $req->fetchColumn();
    }
}
?>