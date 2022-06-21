<?php

namespace Models;

class Users extends Database{
    
        
        public function getAllUsers(){
            
     $req = "SELECT  *
             FROM users
             ORDER BY user_id 
             DESC LIMIT 50";
             
             return $this -> findAll($req);
        }
        
        public function addNewUsers($data){
            
        $this->addOne( 'users',
                   'user_lastname, user_firstname, user_email, user_password, user_birthday, user_address , user_cp, user_city, user_role', 
                   '?,?,?,?,?,?,?,?,?',
                    $data);
        }
        
        public function getUserByEmail($email){
            
             return $this->getOneByEmail('users', $email);
    
        }
        
}










