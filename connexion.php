<?php

    class Connexion extends PDO {
        const Host = 'localhost';
        const Dbname = 'db';
        const User = 'root';
        const Password = '';
        public function __construct() {
            try {
                parent::__construct('mysql:host=127.0.0.1:3307' . self::Host . ';dbname=' . self::Dbname, self::User, self::Password);
                echo 'Connexion reussie';
            } catch (PDOException $e) {
                echo 'Erreur : ' . $e->getMessage();
            }
        }
    }



?>