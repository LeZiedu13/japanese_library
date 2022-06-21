<?php

namespace Models;

class Others {              // ici les méthodes potentiellements réutilisables
 
 
    
    public function verif_password($login) : array {
        define("NUMBERS_OF_CHARACTERS", 8); 
        $listErrors = [];
        $uppercase = preg_match('@[A-Z]@', $login);
        $lowercase = preg_match('@[a-z]@', $login);
        $number    = preg_match('@[0-9]@', $login);
        $specialChars = preg_match('@[^\w]@', $login);
    
        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($login) < NUMBERS_OF_CHARACTERS) {
            $listErrors[] = "Veuillez remplir le champ : « Mot de passe correctement » SVP !";
            $listErrors[] = "→ Minimum " . NUMBERS_OF_CHARACTERS ." caractères !";
            $listErrors[] = "→ Le mot de passe doit inclure au moins une lettre majuscule !";
            $listErrors[] = "→ Le mot de passe doit inclure au moins une lettre minuscule !";
            $listErrors[] = "→ Le mot de passe doit inclure au moins un chiffre !";
            $listErrors[] = "→ Le mot de passe doit inclure au moins un caractère spécial !";
        }
        return $listErrors; // Soit un tableau vide soit un tableau avec plusieurs entrées
    }
    
        /** Fonction permettant d'obtenir la date et l'heure au format et au fuseau horaire souhaité
     * @param string - $format - Le format de la date ( ex : 'd-m-Y H:i:s')
     * @param string - $timeZone - Le fuseau horaire choisi ( ex : 'Europe/Paris')
     * 
     * @ return string - La date au format voulu.
    */
    public function getCurrentDateTime($format, $timeZone) {
        date_default_timezone_set($timeZone);
        return date($format);
    }
    
        
        /* Fonction qui retourne une string d'une longueur déterminée en paramètre
    * @param integer - $length_chain - Le nombre de caractères souhaités dans la chaîne en sortie
    *
    * @return string - Une chaîne de caractères aléatoire (type mot de passe)
    */
    function random_generator($length_chain = 15){
        $chars = "0123456789";
        $var_size = strlen($chars);
        $out = "";
        for( $i = 0; $i < $length_chain; $i++ ) {  
            $random_str= $chars[ rand( 0, $var_size - 1 ) ];  
             $out = $out.$random_str;
        }
        return($out);
    
    }
    
}