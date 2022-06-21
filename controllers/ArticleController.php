<?php

namespace Controllers;

class ArticleController{
    
    public function advertise() {  // j'affiche ma page d'accueil en récupérant mes articles dans ma bdd
        
        $model = new \Models\Articles();  
        $books = $model->getAllArticles();
        $catBooks = $model->getAllCategories();
            
        require_once('config/config.php');
        $template = "home.phtml";         // j'affiche la vue
        include_once 'views/layout.phtml';
    }
    public function advertiseByCatId($id) {
        
        $model = new \Models\Articles(); 
        $catIdBooks = $model->shownByCatId($id);
        $catBooks = $model->getAllCategories();
        
        require_once('config/config.php');
        $template = "booksByCatId.phtml";         // j'affiche la vue
        include_once 'views/layout.phtml';
    }
        
}
 