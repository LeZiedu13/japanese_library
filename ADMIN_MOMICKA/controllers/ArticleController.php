<?php

namespace Controllers;

class ArticleController{
    
    public function advertise() {  // j'affiche ma page d'accueil en récupérant mes articles dans ma bdd
        
        $model = new \Models\Articles();  
        $books = $model->getAllArticles();
        $catBooks = $model->getAllCategories();
            
        require_once('../config/config.php');
        $template = "home.phtml";         // j'affiche la vue
        include_once 'views/layout.phtml';
    }
    public function advertiseByCatId($id) {  // j'affiche mes articles par catégories
        
        $model = new \Models\Articles(); 
        $catIdBooks = $model->shownByCatId($id);
        $catBooks = $model->getAllCategories();
        
        require_once('../config/config.php');
        $template = "booksByCatId.phtml";         // j'affiche la vue
        include_once 'views/layout.phtml';
    }
    
    public function addBookForm(){        //ajout d'ouvrages
        
        $model = new \Models\Articles(); 
        
        $catBooks = $model->getAllCategories();
        
        $buttonName = "Ajouter";
        
        $addArticle = [  //donnée de la table articles
                         'addTitle'          => '',             
                         'addWriter'         => '',
                         'addEditor'         => '',
                         'addPrice'          => '',
                         'addCategory'       => '',
                         'addRef'            => '',
                         'addNbreOfPages'    => '',
                         'addDescription'    => '',
                         'addImage'          => '',
                         'addQuantity'       => '',
                         'addAuthor'         => ''
        ];
        
        require_once('../config/config.php');
        $template = "addBook.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function submitBookForm() {
        
        $errors = [];  // tableaux vides servant à stocker les éventuels messages erreur/validation
        $valids = [];
     
        $buttonName = "Ajouter";
     
        $addArticle = [  //donnée de la table articles
                         'addTitle'          => '',             
                         'addWriter'         => '',
                         'addEditor'         => '',
                         'addPrice'          => '',
                         'addCategory'       => '',
                         'addRef'            => '',
                         'addNbreOfPages'    => '',
                         'addDescription'    => '',
                         'addImage'          => '',
                         'addQuantity'       => '',
                         'addAuthor'         => '',
            
                        ];
        // var_dump($addArticle);
          
        if(array_key_exists('title', $_POST) && array_key_exists('writer', $_POST) && array_key_exists('editor', $_POST) && array_key_exists('price', $_POST) && array_key_exists('categorie', $_POST) && array_key_exists('reference', $_POST) && array_key_exists('nbre_of_page', $_POST) && array_key_exists('description', $_POST) && array_key_exists('image', $_FILES) && array_key_exists('quantity', $_POST) && array_key_exists('author', $_POST))   
        {
          
           
                $addArticle = [  
                         'addTitle'          => trim($_POST['title']),             
                         'addWriter'         => trim($_POST['writer']),
                         'addEditor'         => trim($_POST['editor']),
                         'addPrice'          => trim($_POST['price']),
                         'addCategory'       => trim($_POST['categorie']),
                         'addRef'            => trim($_POST['reference']),
                         'addNbreOfPages'    => trim($_POST['nbre_of_page']),
                         'addDescription'    => trim($_POST['description']),
                         'addImage'          => 'No_Picture.jpg',
                         'addQuantity'       => trim($_POST['quantity']),
                         'addAuthor'         => trim($_POST['author']),
          
                        ];
           
             require('../public/lib/messages.php'); 
                 
            if($addArticle['addTitle'] == ''){
                $errors[] = $listMessage[4];
            }
            if($addArticle['addWriter'] == ''){
                $errors[] = $listMessage[5];
            }
            if($addArticle['addEditor'] == ''){
                $errors[] = $listMessage[6];
            }
            if($addArticle['addPrice'] == ''){
                $errors[] = $listMessage[7]; 
            }
            if($addArticle['addCategory'] == ''){
                $errors[] = $listMessage[8]; 
            }
            if($addArticle['addRef'] == ''){
                $errors[] = $listMessage[18];
            }
            if($addArticle['addNbreOfPages'] == ''){
                $errors[] = $listMessage[9];
            }
            if($addArticle['addDescription'] == ''){
                $errors[] = $listMessage[19]; 
            }
            if($addArticle['addImage'] == ''){
                $errors[] = $listMessage[20];
            }
            if($addArticle['addQuantity'] == ''){
                $errors[] = $listMessage[21];
            }
            if($addArticle['addAuthor'] == ''){
                $errors[] = $listMessage[22];
            }
                
                
               if(count($errors) == 0) {
    
               if(isset($_FILES['image']) && $_FILES['image']['name'] !== '' ) {
                        $dossier = "images_Covers";
                        $model = new \Models\Uploads();
                       $addArticle['addImage'] = $model->upload($_FILES['image'], $dossier, $errors);
                    } 
                   if(count($errors) != 0) {
                 $addArticle['addImage'] = 'No_Picture.png';
                }
       
             
                 
                require('../public/lib/messages.php'); 
                   
                         
               $data = [   // on mets les infos dans la bdd, donc rangé dans le même ordre!
                            $addArticle['addTitle'],
                            $addArticle['addWriter'],
                            $addArticle['addEditor'],
                            $addArticle['addPrice'],
                            $addArticle['addCategory'],
                            $addArticle['addRef'],
                            $addArticle['addNbreOfPages'],
                            $addArticle['addDescription'],
                            $addArticle['addImage'],
                            $addArticle['addQuantity'],
                            1    // role ADMIN par defaut pour ajout d'article
                        ];
                   
                        
                $add = new \Models\Articles();
                $addMore = $add->addBook($data);
                $catBooks = $add->getAllCategories();//permet de rester sur la page après avoir ajouté un article
    
                $valids[] = "L'article a bien été enregistré !";
              }
        }
        
        $add = new \Models\Articles();
        $catBooks = $add->getAllCategories();
        require_once('../config/config.php');
        $template = "addBook.phtml";
        include_once 'views/layout.phtml'; 
    }
    
    
    public function adviseFormModifBook($id){
        
        $model = new \Models\Articles();
        $catBooks = $model->getAllCategories();
        $categories = $model->getAllCategories();
        
        $buttonName = "Modifier";
        
        $modifBook = $model->getArticleById($id);
        

        
        
        
        $addArticle = [  //donnée de la table articles
                         'addId'             => $modifBook ['art_id'] ,
                         'addTitle'          => $modifBook ['art_title'],             
                         'addWriter'         => $modifBook ['art_writer'],
                         'addEditor'         => $modifBook ['art_editor'],
                         'addPrice'          => $modifBook ['art_price'],
                         'addCategory'       => $modifBook ['art_category'],
                         'addRef'            => $modifBook ['art_ref'],
                         'addNbreOfPages'    => $modifBook ['art_nbre_of_pages'],
                         'addDescription'    => $modifBook ['art_description'],
                         'addImage'          => $modifBook ['art_image'],
                         'addQuantity'       => $modifBook ['art_quantity'],
                         'addAuthor'         => $modifBook ['art_author']
        ];
        
        require_once ('../config/config.php');
        $template = "addBook.phtml";
        include_once 'views/layout.phtml';          
        
    }
    
   public function submitFormModifBook($id){
       
        $errors = [];  // tableaux vides servant à stocker les éventuels messages erreur/validation
        $valids = [];
        $buttonName = "Modifier";
       
        // au cas ou, récupérer l'image de la bdd
        $model = new \Models\Articles();
        $modifBook = $model->getArticleById($id);
        
        
       
   
       
       if (array_key_exists('title', $_POST) && array_key_exists('writer', $_POST) && array_key_exists('editor', $_POST) && array_key_exists('price', $_POST) && array_key_exists('categorie', $_POST) && array_key_exists('reference', $_POST) && array_key_exists('nbre_of_page', $_POST) && array_key_exists('description', $_POST) && array_key_exists('quantity', $_POST) && array_key_exists('author', $_POST)) {
       
    //   var_dump($_POST);
     
    
        $addArticle = [  
                         'addId'             => $id,
                         'addTitle'          => trim($_POST['title']),             
                         'addWriter'         => trim($_POST['writer']),
                         'addEditor'         => trim($_POST['editor']),
                         'addPrice'          => trim($_POST['price']),
                         'addCategory'       => trim($_POST['categorie']),
                         'addRef'            => trim($_POST['reference']),
                         'addNbreOfPages'    => trim($_POST['nbre_of_page']),
                         'addDescription'    => trim($_POST['description']),
                         'addImage'          => $modifBook['art_image'],
                         'addQuantity'       => trim($_POST['quantity']),
                         'addAuthor'         => trim($_POST['author']),
                         
                       
          
                        ];
                        
        
         // var_dump($modifyBook);
       require('../public/lib/messages.php'); 
                 
            if($addArticle['addTitle'] == ''){
                $errors[] = $listMessage[4];
            }
            
            if($addArticle['addWriter'] == ''){
                $errors[] = $listMessage[5];
            }
            if($addArticle['addEditor'] == ''){
                $errors[] = $listMessage[6];
            }
            if($addArticle['addPrice'] == ''){
                $errors[] = $listMessage[7]; 
            }
            if($addArticle['addCategory'] == ''){
                $errors[] = $listMessage[8]; 
            }
            if($addArticle['addRef'] == ''){
                $errors[] = $listMessage[18];
            }
            if($addArticle['addNbreOfPages'] == ''){
                $errors[] = $listMessage[9];
            }
            if($addArticle['addDescription'] == ''){
                $errors[] = $listMessage[19]; 
            }
      
            if($addArticle['addQuantity'] == ''){
                $errors[] = $listMessage[21];
            }
            if($addArticle['addAuthor'] == ''){
                $errors[] = $listMessage[22];
            }
            
      //      var_dump($errors); 
            
        if (count($errors) == 0) {
            
            // Si pas de nouvelle image chargée dans le formulaire --> garde l'ancienne
            // Si nouvelle image --> supprime l'ancienne et on upload la nouvelle et on met à jour notre tableau pour la bdd
            
            if( isset($_FILES['image']) && $_FILES['image']['name'] !== '' ) {
                
                if ($modifBook['art_image'] != 'No_Picture.jpg') {
                    unlink('../public/uploads/images_Covers/'.$modifBook['art_image']);
                }
                
                $dossier = "images_Covers";
                $model = new \Models\Uploads();
                $addArticle['addImage'] = $model->upload($_FILES['image'], $dossier, $errors);
            }     
            
            
            $newData = [
                                    
                                    'art_title'          => $addArticle['addTitle'],
                                    'art_writer'         => $addArticle['addWriter'],
                                    'art_editor'         => $addArticle['addEditor'],
                                    'art_price'          => $addArticle['addPrice'],
                                    'art_category'       => $addArticle['addCategory'],
                                    'art_ref'            => $addArticle['addRef'],
                                    'art_nbre_of_pages'  => $addArticle['addNbreOfPages'],
                                    'art_description'    => $addArticle['addDescription'],
                                    'art_image'          => $addArticle['addImage'], 
                                    'art_quantity'       => $addArticle['addQuantity'],
                                    'art_author'         => $addArticle['addAuthor']
            ];  
                    
            $model = new \Models\Articles();
          //   
            $model->updateArticleById($newData, $_GET['id']);
                    
                    
            $modifyBook = [  //donnée de la table articles
                             'art_title'          => '',             
                             'art_writer'         => '',
                             'art_editor'         => '',
                             'art_price'          => '',
                             'art_category'       => '',
                             'art_ref'            => '',
                             'art_nbre_of_pages'  => '',
                             'art_description'    => '',
                             'art_image'          => '',
                             'art_quantity'       => '',
                             'art_author'         => '',
            
            ];       
            
                $listMessage = [17];
                header('Location: index.php');
                exit;
            }
        }
        $model = new \Models\Articles();
        $catBooks = $model->getAllCategories();
        
        require_once ('../config/config.php');
        $template = "addBook.phtml";
        include_once 'views/layout.phtml';  
    }
    
    public function deleteArticle($id, $cat){
        
        require('../public/lib/messages.php'); 
        $valids=[];
        
        $model = new \Models\Articles();
        $pictureName = $model->getImageArticlebyId($id); 


        // Il faut supprimer l'image du dossier Uploads si differente de "No_Picture.jpg"
        if($pictureName['art_image'] != "No_Picture.jpg") {
            unlink('../public/uploads/images_Covers/'.$pictureName['art_image']); 
                    }
        
        $model->deleteArticleById('articles', 'art_id', $id);
        
        $valids[] = $listMessage[23];

        $model = new \Models\Articles();
        $catBooks = $model->getAllCategories();
        $catIdBooks = $model->shownByCatId($cat);
        
        require_once ('../config/config.php');
        $template = "booksByCatId.phtml";
        include_once 'views/layout.phtml';   
    }
        
}
 