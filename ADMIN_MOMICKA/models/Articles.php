<?php

namespace Models;

class Articles extends Database{
    
        
        public function getAllArticles(){               //tous les articles
            
     $req = 'SELECT  articles.*, categories.cat_title
             FROM articles
             INNER JOIN categories
             ON categories.cat_id = articles.art_category
             ORDER BY art_id ASC
             LIMIT 10';
             
             return $this -> findAll($req);         
        }
        
        public function getAllCategories(){             //toutes les categories
            
     $req = "SELECT cat_id, cat_title 
             FROM categories";
         
              return $this->findAll($req);    
        }
        
        public function shownByCatId($id){              //select par ID
            
         $req = "SELECT * 
                 FROM articles
                 INNER JOIN categories
                 ON categories.cat_id = articles.art_category
                 WHERE art_category = ?
                 ORDER BY art_id ASC";
         
              return $this->findAll($req, [$id]);    
        }
        
        
        
        public function addBook($data) {
        $this->addOne( 'articles',
                       'art_title, art_writer, art_editor, art_price, art_category, art_ref , art_nbre_of_pages, art_description, art_image, art_quantity, art_author', 
                       '?,?,?,?,?,?,?,?,?,?,?',
                        $data);
         }
         
       public function getArticleById($id) {
        $req = "SELECT * FROM articles WHERE art_id = ?";
        return $this->findOne($req, [$id]);
    }  
    
    public function updateArticleById($newData, $id) {          //MAJ   
        $this->updateOne('articles', $newData, 'art_id', $id);
    }
    
    
    
    public function getAllArticlesBySearch($column, $search) {
     return $this->search('articles', $column, $search);
    }
    
    public function getImageArticlebyId($id) {
        $req = "SELECT art_image FROM articles WHERE art_id = ?";
        return $this->findOne($req, [$id]);
    }
    
    public function deleteArticleById($table, $idname, $id) {
        $this->deleteOneById($table, $idname, $id);
    }
    
    
    protected function updateOne($table, $newData, $condition, $val) {
    
        
        // On initialise les sets comme étant une chaine de caractères vide
        $sets = '';
        // On boucle sur les data à mettre à jour pour préparer le data binding
        foreach( $newData as $key => $value )
        {
            // On concatène le nom des colonnes et le paramètre du data binding:  clé = :clé,
            $sets .= $key . ' = :' . $key . ','; // ex :    art_title = :art_title, art_writer = :art_writer
        }
        // On supprime le dernier caractère, donc la derniere virgule
        $sets = substr($sets, 0, -1);
        // On indique la requete SQL
        $sql = "UPDATE " . $table . " SET " . $sets . " WHERE " . $condition . " = :" . $condition; // art_id = :art_id
        // On prépare la requete SQL
        $query = $this->bdd->prepare( $sql );
        // Pour chaque valeur de la recette, on lie la valeur de la clé à chaque :clé
        foreach( $newData as $key => $value ) {
            $query->bindValue(':' . $key, $value);
        }
        // On lie la valeur (par ex, l'id) de la condition à  :condition
        $query->bindValue( ':' . $condition, $val);
        // On execute la requete
        $query->execute();
        // On indique au serveur que notre requete est terminée
        $query->closeCursor();
    }
    
    public function newSent($data) {
        
         $this->addOne( 'orderedlist',
                       'ord_number, ord_user, ord_date_receipt, ord_number_of_article, ord_status',
                       '?,?,?,?,?',
                        $data);
    }
    
    public function newSentDetails($data) {
        
         $this->addOne('ordereddetails',
                       'order_number, order_article_id, order_article_quantity' ,
                       '?, ?, ?',
                       $data);
    }
    
    public function updateQuantityBook($datas, $bookId) {
        
        $this->updateOne('articles', $datas, 'art_id', $bookId);   //cf updateOne ligne 73
    
    
    }

}