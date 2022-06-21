<?php

namespace Controllers;

class AjaxController{
    
    public function ajaxSearch() {
         
        $model = new \Models\Articles(); 
        $catBooks = $model->getAllCategories();
    
    
        $content = file_get_contents("php://input");    //je récupère ce que le JS a envoyé
        $data = json_decode($content, true);

        $search = "%".$data['textToFind']."%";
        
        
        $books = $model->getAllArticlesBySearch('art_title', $search); //je crée la requête
        
     
        require_once('config/config.php');
        include "views/home.phtml";         // j'affiche la vue
            
    }
    
       
}