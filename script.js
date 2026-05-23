document.getElementById("formulario").addEventListener("submit", function(e){

    let nombre = document.getElementById("Nombre").value.trim();
    let apellidoP = document.getElementById("ApellidoP").value.trim();
    let apellidoM = document.getElementById("ApellidoM").value.trim();
    let fecha = document.getElementById("fechanacimiento").value.trim();
    let curp = document.getElementById("Curp").value.trim();

    if(nombre === "" || apellidoP === "" || apellidoM === "" || fecha === "" || curp === ""){
        e.preventDefault();
        alert("Todos los campos son obligatorios");
    }

});
