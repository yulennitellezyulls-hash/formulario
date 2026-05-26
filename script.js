document.getElementById("formulario").addEventListener("submit", function(e) {

    let nombre = document.getElementById("Nombre").value.trim();
    let apellidoP = document.getElementById("ApellidoP").value.trim();
    let apellidoM = document.getElementById("ApellidoM").value.trim();
    let fecha = document.getElementById("fechanacimiento").value.trim();
    let curp = document.getElementById("Curp").value.trim();

    // VALIDAR CAMPOS VACÍOS
    if (
        nombre === "" ||
        apellidoP === "" ||
        apellidoM === "" ||
        fecha === "" ||
        curp === ""
    ) {

        e.preventDefault();

        alert("Todos los campos son obligatorios");

        return;
    }

    // VALIDAR CURP
    if (curp.length !== 18) {

        e.preventDefault();

        alert("La CURP debe tener 18 caracteres");

        return;
    }

    // VALIDAR SOLO LETRAS EN NOMBRE
    let letras = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;

    if (!letras.test(nombre)) {

        e.preventDefault();

        alert("El nombre solo debe contener letras");

        return;
    }

});
