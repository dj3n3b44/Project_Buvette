<?php
class Connexion {
    static protected $bdd;

    /*
    public static function initConnexion(){
        self::$bdd = new PDO('mysql:host=localhost;
                    dbname=saedevweb;charset=utf8',
                    'root',
                    '');
    }
*/

    public static function initConnexion(){
        self::$bdd = new PDO('mysql:host=database-etudiants.iut.univ-paris8.fr;
                    dbname=dutinfopw201639;charset=utf8',
            'dutinfopw201639',
            'qeruneqy');
    }

}


?>
