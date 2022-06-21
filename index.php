<?php

session_start();

// index = routeur

spl_autoload_register(function($class) {                            // $class = Controllers/HomeController
    require_once lcfirst(str_replace('\\','/', $class)) . '.php';   // require_once 'controllers/HomeController.php'
});


if(array_key_exists('route',$_GET)):
    
    switch ($_GET['route']) {
        
        case 'home':
            $controller = new Controllers\ArticleController();
            $controller->advertise();
            break;
            
        case 'categories':
            $controller = new Controllers\ArticleController();
            $controller->advertiseByCatId($_GET['id']);
            break;
        

            
         case 'connect'  :
             
            if(!array_key_exists('ref', $_GET) || $_GET['ref'] != "connect") {
                $controller = new Controllers\UserController();
                $controller ->connectForm();
             }else{
                $controller = new Controllers\UserController();
                $controller -> submitConnectForm();
             }    
            break;
         
         case 'logout':
            session_destroy();
            header('Location: index.php?route=home');
            exit;
            break;
             
         case 'addUser':
             
            if(!array_key_exists('ref', $_GET) || $_GET['ref'] != "add") {
                $controller = new Controllers\UserController();  //formulaire à afficher
                $controller->addUserForm();
            }else {
                $controller = new Controllers\UserController(); //formulaire à soumettre
                $controller->submitUserForm();
            }  
            break; 
            
        case 'search':
            $controller = new Controllers\AjaxController();
            $controller->ajaxSearch();
            break;
            
        case 'basket':
            
            if(isset($_GET['id']) && $_GET['id'] > 0) {
                $controller = new Controllers\BasketController();
                $controller->addToBasket('basket', ($_GET['id']));
            }else{
               $controller = new Controllers\BasketController();
               $controller->adviseBasket('basket'); 
            }
            break;
            
            
        case 'add_book_basket':
           
           if(isset($_GET['id']) && $_GET['id'] > 0) {
                $controller = new Controllers\BasketController();
                $controller->addOrLessOneInBasket('basket', ($_GET['id']), 'add');
            }else{
               $controller = new Controllers\BasketController();
               $controller->adviseBasket('basket'); 
            }
            break;
         
        case 'less_book_basket':
           
           if(isset($_GET['id']) && $_GET['id'] > 0) {
                $controller = new Controllers\BasketController();
                $controller->addOrLessOneInBasket('basket', ($_GET['id']), 'less');
            }else{
               $controller = new Controllers\BasketController();
               $controller->adviseBasket('basket'); 
            }
            break;  
            
        case 'delete_book_basket':
            
            if(isset($_GET['id']) && $_GET['id'] > 0) {
                $controller = new Controllers\BasketController();
                $controller->addOrLessOneInBasket('basket', ($_GET['id']), 'del');
            }else{
               $controller = new Controllers\BasketController();
               $controller->adviseBasket('basket'); 
            }
            break;  
         
         case 'validationBasket':
             
                $controller = new Controllers\BasketController();
                $controller->confirmBasket('basket');   
           
            break;
         
         case 'cgv':
                $controller = new Controllers\CgvController();
                $controller->displayCGV(); 
            break;
            
            default:
                header('Location: index.php?route=home');
                exit;
                break;
    }
else:
    header('Location: index.php?route=home');
    exit;
endif;
        
        