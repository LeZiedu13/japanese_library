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
        
        case 'addBook':
            if(!array_key_exists('ref', $_GET) || $_GET['ref'] != "add") {
                $controller = new Controllers\ArticleController();  //formulaire à afficher
                $controller->addBookForm();
            }else {
                $controller = new Controllers\ArticleController(); //formulaire à soumettre
                $controller->submitBookForm();
            }
            break;    
            
        case 'modifBook':
            if( isset ($_SESSION["user"]) && $_SESSION["user"]["role"] == 1) {
                if(array_key_exists('id', $_GET) && $_GET['id'] > 0) {
                    $controller = new Controllers\ArticleController();
                    $controller->adviseFormModifBook($_GET['id']);
                }else{
                    header('Location: index.php?route=home');
                    exit;  
                }
            }
            else{
              header('Location: index.php?route=home');
              exit;  
            }
            
            break;
            
        case 'submitModifBook':
            if( isset ($_SESSION["user"]) && $_SESSION["user"]["role"] == 1) {
                if(array_key_exists('id', $_GET) && $_GET['id'] > 0) {
                    $controller = new Controllers\ArticleController();
                    $controller->submitFormModifBook($_GET['id']);
                }else{
                    header('Location: index.php?route=home');
                    exit;  
                }
            }
            else{
              header('Location: index.php?route=home');
              exit;  
            }
        break;
            
        case 'deleteArticle':
        
            if(isset($_SESSION["user"]) && $_SESSION["user"]["role"] == 1) {
                
                if(isset($_GET['id']) && $_GET['id'] > 0) {
    
                    $controller = new Controllers\ArticleController();
                    $controller->deleteArticle($_GET['id'], $_GET['cat']);
                }
        
        }  
          break;
          
        case 'allUsers':
             $controller = new Controllers\UserController();
             $controller->advertise();
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
            header('Location: index.php?route=connect');
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
         
         case 'cgv':
                $controller = new Controllers\CgvController();
                $controller->displayCGV(); 
            break;
            
            default:
                header('Location: index.php?route=connect');
                exit;
                break;
    }
else:
    header('Location: index.php?route=connect');
    exit;
endif;
        
        