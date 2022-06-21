<?php

namespace Controllers;

class BasketController{
    
    public function addToBasket($name, $art) {      // ici nom du cookie et Id

        $model = new \Models\Articles();
        $catBooks = $model->getAllCategories();
        $catIdBooks = $model->shownByCatId($art);
        
            if(empty($_COOKIE[$name])) {
                $prepareCookie = [['art' => $art, 'qte' => 1]];
                $prepareCookie = json_encode($prepareCookie);
                setcookie($name, $prepareCookie, time() + 300);
            }
            else {
                $prepareCookie = json_decode($_COOKIE[$name], true);

                // 1 - Vérifie si l'article existe déjà dans le panier, s'il n'existe pas, on l'ajoute
                // 2 - S'il n'existe pas dans le panier, on incrémente une unité à l'article
                if(array_search($art, array_column($prepareCookie, 'art')) === false){
                    array_push($prepareCookie, ['art' => $art, 'qte' => 1]);                // 1
                }
                else {
                    $index = array_search($art, array_column($prepareCookie, 'art'));
                    $prepareCookie[$index]['qte'] = $prepareCookie[$index]['qte'] + 1;      // 2
                }

                $prepareCookie = json_encode($prepareCookie);
                setcookie($name, $prepareCookie, time() + 300);
            }
            
            
            header('Location: index.php?route=basket');
            exit();
        }
        
        
    public function adviseBasket($name) {
        
        // Lire le cookie qui porte le $name
        
        $panier = '';

        if(isset($_COOKIE[$name])) {
            $panier = json_decode($_COOKIE[$name], true);
        }
        
        if($panier == '') { 
            $listArticlePanier = [];       
        
        } else { 
            $listArticlePanier = $panier;
        }
        $catIdBooks = [];
        $totalBasket = 0;
        // Boucler sur ce cookie qui porte le $listArticlePanier
        foreach($listArticlePanier as $key => $catIdBook){
            if($catIdBook['qte'] > 0){
               
                $model = new \Models\Articles();
                $result = $model->getArticleById($catIdBook['art']);
            
                $result['articles_qte'] = $catIdBook['qte'];
                $catIdBooks[] = $result; 
                $totalBasket = $totalBasket + (intval($catIdBook['qte']) * floatval($result['art_price']));
                
            }
         //   var_dump($catIdBooks);
        }   
        
      
            
        $model = new \Models\Articles();
        $catBooks = $model->getAllCategories();
        
            
        require_once ('config/config.php');
        $template = "basket.phtml";
        include_once 'views/layout.phtml';   

         
        }
        
         
          public function addOrLessOneInBasket($name, $id, $operator) {  
             // enlève, ajoute, supprime avec nom cookie, id article, operateur + et - .
             
              
            
            if(isset($_COOKIE[$name])){
                $basket = json_decode($_COOKIE[$name], true);
                $index = array_search($id, array_column($basket, 'art'));

            
                if(isset($basket[$index])) {
                    switch ($operator) {
                        case 'add':
                            $basket[$index]['qte'] = $basket[$index]['qte'] + 1;
                          
                            break;
       
                        case 'less':
                            $basket[$index]['qte'] = $basket[$index]['qte'] - 1;
                            if($basket[$index]['qte'] === 0){
                               
                               $basket[$index]['qte'] = 0;
                            }
                           
                            break;
                            
                        case 'del':
                          
                                $basket[$index]['qte'] = 0;
                                
                            break;

                        default:
                    }
                }
            }  
           $prepareCookie = json_encode($basket);
           setcookie($name, $prepareCookie, time() + 300);
    
       
        header('Location: index.php?route=basket');
        exit(); 
            
        }
        
        
        public function confirmBasket($name) {
            
            $errors = [];  // tableau vide servant à stocker les éventuels messages erreur
             
            $panier = '';                   //on récupère le cookie 'basket'
            $quantity = 0;
                
            if(isset($_COOKIE[$name])) {
                $panier = json_decode($_COOKIE[$name], true);
            }
            
            if($panier == '') { 
                $listArticlePanier = [];       
            
            } else { 
                $listArticlePanier = $panier;
            }
            
            for($i=0; $i<count($listArticlePanier); $i++) {
              
                $quantity = $quantity + $listArticlePanier[$i]['qte']; // qté article par id
                
                $model = new \Models\Articles();
                $results = $model->getArticleById($listArticlePanier[$i]['art']);
                
                if($listArticlePanier[$i]['qte'] > $results['art_quantity']) { //si panier > qté en stock, erreurs!
                    $errors[] = $results['art_title']." -> Quantité en stock : ".$results['art_quantity'];
                
                //var_dump($errors);
                }
            }      
            
             
            if(count($errors) == 0) {
                
               //On récupère l'id de l'utilisateur 
            $userId = $_SESSION['user']['id'];
            
            $model = new \Models\Others();
            // On récupère la date actuelle au moment de la validation de la commande
            $dateNow = $model->getCurrentDateTime('Y-m-d H:i:s', 'Europe/Paris');
            // On créé un numéro de référence aléatoire
            $reference = $model->random_generator(8);  

            $model = new \Models\Articles(); 
            $orderedSent = $model->newSent([$reference, $userId, $dateNow, $quantity, 'en attente de règlement']); 
            
                for($i = 0; $i < count($listArticlePanier); $i++) {
                 
                    $bookQty = $listArticlePanier[$i]['qte'];
                    $bookId  = $listArticlePanier[$i]['art'];
                    
                    if($bookQty != 0) {
                        $model = new \Models\Articles(); 
                        $orderedDetails = $model->newSentDetails([$reference, $bookId, $bookQty]);
                    }
                    
                    $qtyDatabase = $model->getArticleById($bookId);
                    
                    $datas = [ 'art_quantity'  => $qtyDatabase['art_quantity'] - $bookQty ];
                    
                    $model = new \Models\Articles(); 
                    $updateQuantity = $model->updateQuantityBook($datas, $bookId);
                    
                    
                    
                    
                }
                setcookie('basket',NULL,1);
              
                $_SESSION['message'] = 'Commande en cours de préparation, merci de vos achats !';
            
                   
                 header('Location: index.php?route=home');
                 exit(); 
            
            }
            
            $_SESSION['message'] = "Panier non validé car  quantités en stock insuffisantes. Merci de vérifier les quantités en magasin.";
            $this->adviseBasket('basket'); 
            
            
        }
       
        
    }
