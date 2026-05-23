import { initializeApp }
from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";

import {
getFirestore,
collection,
addDoc
}
from "https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js";


const firebaseConfig = {

apiKey: "PEGA_AQUI",

authDomain: "PEGA_AQUI",

projectId: "PEGA_AQUI",

storageBucket: "PEGA_AQUI",

messagingSenderId: "PEGA_AQUI",

appId: "PEGA_AQUI"

};


const app = initializeApp(firebaseConfig);

const db = getFirestore(app);


const formulario =
document.getElementById("formulario");


formulario.addEventListener("submit",
async(e)=>{

e.preventDefault();

const nombre =
document.getElementById("nombre").value;

const correo =
document.getElementById("correo").value;

const mensaje =
document.getElementById("mensaje").value;


try{

await addDoc(
collection(db,"contactos"),
{

nombre:nombre,

correo:correo,

mensaje:mensaje,

fecha:new Date()

}

);

alert("Mensaje enviado 💙");

formulario.reset();

}catch(error){

alert("Error");

console.log(error);

}

});
