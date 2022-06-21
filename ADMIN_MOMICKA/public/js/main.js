'use strict'

// slider photos Japon

document.addEventListener('DOMContentLoaded', function(){

let i = 1;

let signe = 1;
// Augmenter l'opacité, on va multiplier par "signe"
// Diminier l'opacité, on change le signe à -1, et on multiplie par le signe

let div =  document.getElementById("LblClignotant");
div.style.opacity = 1; // On met l'opacité de la div par défaut à 1

let image = document.createElement("img"); // On créé un balise image
image.src = "../public/img/img_slider/" + i + ".jpg"; // On lui ajoute le src par défaut avec l'image 1

div.appendChild(image); // on met l'image dans la div


let monInterval = setInterval(cligno, 350);

function cligno() {

    div.style.opacity = (div.style.opacity * 1) + (signe * 0.20);


    // Diminuer l'opacité pour faire disparaitre l'image,
        if (div.style.opacity > 0.95) {
            signe = -1;
        }

    // Quand l'opacité est à 0
    if (div.style.opacity < 0.05) {
            signe = +1;
        }
   
   if (div.style.opacity == 0) {
            i++;
        image.src = "../public/img/img_slider/" + i + ".jpg";
    }
     if (i == 10) {
         i = 0;
     }
    
    // Augmente l'opacité pour faire apparaitre l'image

    // Quand l'opacité est à 1
  }
        
})