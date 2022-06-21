'use strict';
    
// console.log("ok");


            /*  =========   oeil MDP        =========  */

document.addEventListener('DOMContentLoaded', function(){
    
    if(document.querySelector("#eyes1") !== null) {
        document.querySelector("#eyes1").addEventListener("click", eyesActivate_1)
    }
    
    const fas1 = document.querySelector('#eyesImag1');
    
    const user_password = document.querySelector('#user_password');
    
    
    
    function eyesActivate_1(e){     
        e.preventDefault();
        if(user_password.type == 'password'){              // si le mdp est tapé
            user_password.setAttribute('type', 'text');
    
            fas1.classList.remove('fa-eye-slash');              //on lève le slash
            fas1.classList.add('fa-eye');
    
            fas1.setAttribute('style', 'color: rgb(132, 9, 103)');
        }else {
            user_password.setAttribute('type', 'password');     // sinon on le met
            fas1.classList.add('fa-eye-slash');
            fas1.classList.remove('fa-eye');
            fas1.removeAttribute('style');
        }
        window.setTimeout(closeEyes_1, 5000);               //se remet auto au bout de 5s
    }
    
    function closeEyes_1() {
        user_password.setAttribute('type', 'password');      //on crée l'oeil barré
        fas1.classList.remove('fa-eye');
        fas1.classList.add('fa-eye-slash');
        fas1.removeAttribute('style');
    }
    
    
    
    if(document.querySelector("#eyes2") !== null) {document.querySelector("#eyes2").addEventListener("click", eyesActivate_2) }
    
    const fas2 = document.querySelector('#eyesImag2');
    
    const user_password_confirm = document.querySelector('#user_password_confirm');
    
    
    // même chose pour le second
    function eyesActivate_2(e){
        e.preventDefault();
        if(user_password_confirm.type == 'password'){
            user_password_confirm.setAttribute('type', 'text');
            fas2.classList.remove('fa-eye-slash');
            fas2.classList.add('fa-eye');
            fas2.setAttribute('style', 'color: rgb(132, 9, 103)');
        }else {
            user_password_confirm.setAttribute('type', 'password');
            fas2.classList.add('fa-eye-slash');
            fas2.classList.remove('fa-eye');
            fas2.removeAttribute('style');
        }
        window.setTimeout(closeEyes_2, 5000);
    }
    
    function closeEyes_2() {
        user_password_confirm.setAttribute('type', 'password');
        fas2.classList.add('fa-eye-slash');
        fas2.classList.remove('fa-eye');
        fas2.removeAttribute('style');
    }
    
    /* ======= heure au Japon ======*/
    
    
    let heuresDiv = document.querySelector('.heures');
 
    
    let affichageHeure = function(){
        // Déclaration des variables qui seront utilisées : 
        let today;
    
        // Récupérer la date actuelle : 
        today = new Date();
        let todayInJapan = today.toLocaleString('JP-u-ca-japanese', {timeZone: "Asia/Tokyo"});

        heuresDiv.textContent = todayInJapan;
      
        // Lancer la fonction affichage heure toutes les 1000 ms, soit toute les secondes : 
        setTimeout(affichageHeure, 1000);
    }
    
    //Lancer la fonction une fois au début : 
    affichageHeure();
    
 
}); 
