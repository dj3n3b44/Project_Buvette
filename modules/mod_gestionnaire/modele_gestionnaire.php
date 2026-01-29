<?php
require_once 'connexion.php';

class ModeleGestionnaire extends Connexion {

    public function getStatistiques($id_asso) {
        $stats = [];

        $sqlVentes = "SELECT SUM(montant) FROM Historique WHERE id_association = ? AND type_operation = 'achat'";
        $req = self::$bdd->prepare($sqlVentes);
        $req->execute([$id_asso]);
        $stats['ca_total'] = $req->fetchColumn() ?: 0;

        $sqlDepenses = "SELECT SUM(montant_total) FROM Depenses WHERE id_association = ?";
        $req = self::$bdd->prepare($sqlDepenses);
        $req->execute([$id_asso]);
        $stats['depenses_total'] = $req->fetchColumn() ?: 0;

        $sqlSolde = "SELECT SUM(U.solde) FROM Utilisateur U 
                     JOIN Membre M ON U.id_user = M.id_user 
                     WHERE M.id_association = ? AND U.solde > 0";
        $req = self::$bdd->prepare($sqlSolde);
        $req->execute([$id_asso]);
        $stats['dette_clients'] = $req->fetchColumn() ?: 0;

        return $stats;
    }

    public function getMembresAsso() {
        $id_asso = $_SESSION['id_asso_active'];

        $sql = "SELECT U.id_user, U.nom, U.prenom, U.email, U.solde, R.nom as role_nom, M.id_role
                FROM Utilisateur U
                JOIN Membre M ON U.id_user = M.id_user
                JOIN Role R ON M.id_role = R.id_role 
                WHERE M.id_association = ?
                ORDER BY U.nom ASC";

        $req = self::$bdd->prepare($sql);
        $req->execute([$id_asso]);
        return $req->fetchAll();
    }

    public function updateRole($id_user, $nouveau_role) {
        $id_asso = $_SESSION['id_asso_active'];


        $sqlCheck = "SELECT id_role FROM Membre WHERE id_user = ? AND id_association = ?";
        $reqCheck = self::$bdd->prepare($sqlCheck);
        $reqCheck->execute([$id_user, $id_asso]);
        $roleActuel = $reqCheck->fetchColumn();

        if ($roleActuel == 1) {
            return;
        }

        $sql = "UPDATE Membre SET id_role = ? WHERE id_user = ? AND id_association = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$nouveau_role, $id_user, $id_asso]);
    }

    public function creerMembre($nom, $prenom, $email, $mdp, $role) {
        $id_asso = $_SESSION['id_asso_active'];
        try {
            self::$bdd->beginTransaction();
            $hash = password_hash($mdp, PASSWORD_DEFAULT);

            $sqlU = "INSERT INTO Utilisateur (nom, prenom, email, mdp, solde) VALUES (?, ?, ?, ?, 0)";
            $reqU = self::$bdd->prepare($sqlU);
            $reqU->execute([$nom, $prenom, $email, $hash]);
            $id_user = self::$bdd->lastInsertId();

            $sqlM = "INSERT INTO Membre (id_user, id_association, id_role) VALUES (?, ?, ?)";
            $reqM = self::$bdd->prepare($sqlM);
            $reqM->execute([$id_user, $id_asso, $role]);

            self::$bdd->commit();
        } catch (Exception $e) {
            self::$bdd->rollBack();
            throw $e;
        }
    }

    public function creerBarman($nom, $prenom, $email, $mdp, $id_asso) {
        $sql = "INSERT INTO Utilisateur (nom, prenom, email, mdp, solde) VALUES (?, ?, ?, ?, 0)";
        $req = self::$bdd->prepare($sql);
        $req->execute([$nom, $prenom, $email, password_hash($mdp, PASSWORD_DEFAULT)]);

        $id_user = self::$bdd->lastInsertId();

        $sqlMembre = "INSERT INTO Membre (id_user, id_association, id_role) VALUES (?, ?, 2)";
        $reqM = self::$bdd->prepare($sqlMembre);
        $reqM->execute([$id_user, $id_asso]);
    }

    public function enregistrerStock($id_produit, $quantite, $cout_total, $id_asso, $date_depense = null) {
        $sqlStock = "UPDATE Produit SET stock_actuel = stock_actuel + ? WHERE id_produit = ?";
        $req = self::$bdd->prepare($sqlStock);
        $req->execute([$quantite, $id_produit]);

        if (!$date_depense) {
            $date_depense = date("Y-m-d H:i:s");
        }

        $sqlLog = "INSERT INTO Depenses (id_produit, quantite, montant_total, date_depense, id_association)
                    VALUES (?, ?, ?, ?, ?)";
        $reqL = self::$bdd->prepare($sqlLog);
        $reqL->execute([$id_produit, $quantite, $cout_total,$date_depense, $id_asso]);
    }

    public function getProduits($id_asso) {
        $req = self::$bdd->prepare("SELECT * FROM Produit WHERE id_association = ? ORDER BY nom");
        $req->execute([$id_asso]);
        return $req->fetchAll();
    }

    public function getHistoriqueStocks($id_asso) {
        $sql = "SELECT D.*, P.nom as nom_produit
                FROM Depenses D
                JOIN Produit P ON D.id_produit = P.id_produit
                WHERE D.id_association = ?
                ORDER BY D.date_depense DESC";
        $req = self::$bdd->prepare($sql);
        $req->execute([$id_asso]);
        return $req->fetchAll();
    }
}
?>