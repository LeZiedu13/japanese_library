<?php

namespace Models;

require('../config/config.php');

class Database {
    
    protected $bdd;
    
    
    public function __construct() {
        $this->bdd = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);    // connexion à la BDD
    }
    
    
      protected function findAll($req, $params = []) {
        $query = $this->bdd->prepare($req);
        $query->execute($params);
        return $query->fetchAll(); // stocke les requêtes sql et les execute, ici on récupère tout.
    }
    
     protected function addOne(string $table, string $columns, string $values, $data) {
        $query = $this->bdd->prepare('INSERT INTO ' . $table . '(' . $columns . ') values (' . $values . ')');
        $query->execute($data);
        $query->closeCursor();
    }
    
    protected function getOneByEmail($table, $email) {
        $query = $this->bdd->prepare("SELECT * FROM " . $table . " WHERE user_email = ?");
        $query->execute([$email]);
        $data = $query->fetch();
        $query->closeCursor();
        return $data;
    }
    
    protected function search($table, $column, $search) {
        $query = $this->bdd->prepare( "SELECT * 
                                        FROM $table 
                                        INNER JOIN categories
                                            ON categories.cat_id = articles.art_category 
                                        WHERE $column LIKE ?" );
        $query->execute([$search]);
        $result = $query -> fetchAll();
        $query->closeCursor(); // On indique au serveur que notre requete est terminée
        return $result;

    }
    protected function findOne($req, $params = []) {
        $query = $this->bdd->prepare($req);
        $query->execute($params);
        return $query->fetch(); // Récupérer les enregistrements
    }
    
    
    protected function deleteOneById($table, $idname, $id) {
        $query = $this->bdd->prepare(
            "DELETE FROM " . $table . " WHERE " . $idname . " = ?"
            );
        $query->execute([$id]);
        $query->closeCursor();
        
    }
    
    public function getOneById($table, $id) {
      $query = $this->bdd->prepare("SELECT * FROM " . $table . " WHERE art_id = ?");
      $query->execute([$id]);
      $data = $query->fetch();
      $query->closeCursor(); // requete terminée !
      return $data;
    }
    
    
}  
    // la class Database permet de récuperer toutes les infos utiles à la connexion, grâce au require config'.
    