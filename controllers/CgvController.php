<?php

namespace Controllers;

class CgvController{
    
    public function displayCGV() {  //simple méthode pour afficher les conditions
        
        $model = new \Models\Articles();  
        $books = $model->getAllArticles();
        $catBooks = $model->getAllCategories();
        
        require_once('config/config.php');
        $template = "cgv.phtml";        
        include_once 'views/layout.phtml';
    }
        
}
 