<?php

namespace Models;

class Articles extends Database{
    
        
        public function getAllArticles(){
            
     $req = 'SELECT  articles.*, categories.cat_title
             FROM articles
             INNER JOIN categories
             ON categories.cat_id = articles.art_category
             ORDER BY art_id ASC
             LIMIT 10';
             
             return $this -> findAll($req);
        }
        
        public function getAllCategories(){
            
     $req = "SELECT cat_id, cat_title 
             FROM categories";
         
              return $this->findAll($req);    
        }
        
        public function shownByCatId($id){
            
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
    
    public function updateArticleById($newData, $id) {
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
    
        
        // On initialise les sets comme ??tant une chaine de caract??res vide
        $sets = '';
        // On boucle sur les data ?? mettre ?? jour pour pr??parer le data binding
        foreach( $newData as $key => $value )
        {
            // On concat??ne le nom des colonnes et le param??tre du data binding:  cl?? = :cl??,
            $sets .= $key . ' = :' . $key . ','; // ex :    art_title = :art_title, art_writer = :art_writer
        }
        // On supprime le dernier caract??re, donc la derniere virgule
        $sets = substr($sets, 0, -1);
        // On indique la requete SQL
        $sql = "UPDATE " . $table . " SET " . $sets . " WHERE " . $condition . " = :" . $condition; // art_id = :art_id
        // On pr??pare la requete SQL
        $query = $this->bdd->prepare( $sql );
        // Pour chaque valeur de la recette, on lie la valeur de la cl?? ?? chaque :cl??
        foreach( $newData as $key => $value ) {
            $query->bindValue(':' . $key, $value);
        }
        // On lie la valeur (par ex, l'id) de la condition ??  :condition
        $query->bindValue( ':' . $condition, $val);
        // On execute la requete
        $query->execute();
        // On indique au serveur que notre requete est termin??e
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