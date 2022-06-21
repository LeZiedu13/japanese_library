<?php


namespace Controllers;

class UserController    {
    
    public function advertise(){
        
        $add = new \Models\Articles();
        $catBooks = $add->getAllCategories();
        $model = new \Models\Users();
        
     
        $listUsers = $model -> getAllUsers();
         
        require_once('config/config.php');
        $template = "users.phtml";         // j'affiche la vue
        include_once 'views/layout.phtml';
    
    }
    
    public function addUserForm(){
        
        $model = new \Models\Users();
        $add = new \Models\Articles();
        $catBooks = $add->getAllCategories(); 
        
        require_once('config/config.php');
        $template = "addUser.phtml";         // j'affiche la vue
        include_once 'views/layout.phtml';
      
    }
    
    public function submitUserForm(){ 
        
        $add = new \Models\Articles();
        $catBooks = $add->getAllCategories();
        $model = new \Models\Users();
        
        $errors = [];  // tableaux vides servant à stocker les éventuels messages erreur/validation
        $valids = [];
        
        $addUser = [  //donnée de la table users
                         'addLastname'          => '',             
                         'addFirstname'         => '',
                         'addEmail'             => '',
                         'addPassword'          => '',
                         'addPassword_confirm'  => '',
                         'addBirthday'          => '',
                         'addAddress'           => '',
                         'addCp'                => '',
                         'addCity'              => ''
                                
                   ];
                  
             
         
       if(array_key_exists('lastname', $_POST) && array_key_exists('firstname', $_POST) && array_key_exists('email', $_POST) && array_key_exists('password', $_POST) && array_key_exists('password_confirm', $_POST) && array_key_exists('birthday', $_POST) && array_key_exists('address', $_POST) && array_key_exists('cp', $_POST) && array_key_exists('city', $_POST))   //si toutes les clés existent...
        {    
       
         $addUser = [      //on protège 
                        'addLastname'         => trim(strtoupper($_POST['lastname'])),
                        'addFirstname'        => trim(ucfirst($_POST['firstname'])),
                        'addEmail'            => trim($_POST['email']),
                        'addPassword'         => trim($_POST['password']),
                        'addPassword_confirm' => trim($_POST['password_confirm']),
                        'addBirthday'         => trim($_POST['birthday']),
                        'addAddress'           => trim($_POST['address']),
                        'addCp'               => trim($_POST['cp']),
                        'addCity'             => trim($_POST['city']),
 
                 ];
        //   var_dump($addUser);
      require('public/lib/messages.php');     
      
      
             if($addUser['addLastname'] == ''){
                $errors[] = $listMessage[0];
             }
            if($addUser['addFirstname'] == ''){
                $errors[] = $listMessage[1];
            }
            if(!filter_var($addUser['addEmail'], FILTER_VALIDATE_EMAIL)){
                $errors[] = $listMessage[2];
            }
            if(empty($addUser['addPassword'])){
                $errors[] = $listMessage[16];
            }
            if($addUser['addPassword'] != $addUser['addPassword_confirm']){
                $errors[] = $listMessage[3];
            }else{
                
                $model = new \Models\Others();
                $listErrors = $model->verif_password($addUser['addPassword']);
                
                foreach($listErrors as $error){
                   $errors[] = $error; 
                }
            } 
           
           
           
           if(count($errors) == 0) {
               
               
                $passwordHash = password_hash($addUser['addPassword'], PASSWORD_DEFAULT);
                
                $user_role = 3; // Role USER par défaut
                $data = [
                            $addUser['addLastname'],
                            $addUser['addFirstname'],
                            $addUser['addEmail'],
                            $passwordHash,
                            $addUser['addBirthday'],
                            $addUser['addAddress'],
                            $addUser['addCp'],
                            $addUser['addCity'],
                            3  // Role USER par défaut
                ];
                             
                require('public/lib/messages.php'); 
                $valids[]= $listMessage[13];
                    
                $model = new \Models\Users();
                $listUsers = $model->addNewUsers($data); 
            }   
               
            require_once('config/config.php');
            $template = "addUser.phtml";
            include_once 'views/layout.phtml';   
         
           }
         }
         
             
    public function connectForm(){ 
            
        $add = new \Models\Articles();
        $catBooks = $add->getAllCategories();
             
        require_once('config/config.php');
        $template = "connect.phtml";         // j'affiche la vue
        include_once 'views/layout.phtml';
            
    }
    
    
    public function submitConnectForm(){
        
        $add = new \Models\Articles();
        $catBooks = $add->getAllCategories(); 
        
        $errors = [];
        $valids =[];
        $login = [
                        'email_log'     => '',
                        'password_log'  => '',
            
            ];
            
       
       if(array_key_exists('email',$_POST) && (array_key_exists('password',$_POST))){ 
         
         
         $login =       [
                        'email_log'     => trim(strtolower($_POST['email'])),
                        'password_log'  => trim($_POST['password']),
                        ];  
           
    require('public/lib/messages.php');
           
           if($login['email_log'] == ''){
            $errors[] = $listMessage[15];
         }
         
           if(!filter_var($login['email_log'], FILTER_VALIDATE_EMAIL)){
            $errors[] = $listMessage[2];
            }
            
            if(empty($login['password_log'])){
             $errors[] = $listMessage[16];   
            }
          
            if(!empty($login['password_log'])){   
                
                  $model = new \Models\Others();
                  
                $listErrors = $model->verif_password($login['password_log']);
                foreach($listErrors as $error){
                   $errors[] = $error; 
                }
                
                if(count($errors) == 0){
                    
                    $model = new \Models\Users();
                    
                    $findEmail = $model->getUserByEmail($login['email_log']);   
              
                  if(empty($findEmail)){
                    $errors[] = $listMessage[12];
                    }  
                   
                 if(count($errors) == 0)   {
                     
                     
                     if(password_verify($login['password_log'],$findEmail['user_password'])){  // Vérifie qu'un mot de passe correspond à une table de hachage
                         
                    $_SESSION['connected'] = true;
                    $_SESSION['user'] = [   
                                            'lastName'  => $findEmail['user_lastname'],
                                            'firstName' => $findEmail['user_firstname'],
                                            'email'     => $findEmail['user_email'],
                                            'role'      => $findEmail['user_role'], 
                                            'id'        => $findEmail['user_id']
                    ];
            
                header('Location: index.php');
                        exit;
               
            }else {
                $errors[] = $listMessage[14]; 
             
            }
                     }
                 }
         
             }
             require_once('config/config.php');
             $template = "connect.phtml";
             include_once 'views/layout.phtml';         
        }
     }
}
         
         
    


    
    
    


