document.addEventListener("DOMContentLoaded", () => {
    let cuerpoTabla = document.getElementById("datosReservas");

    fetch("../controllers/mostrarNotas.php")
        .then((respuesta) => respuesta.json())
        .then((datos) => {
            cuerpoTabla.innerHTML = ""; // limpiar tabla

            datos.forEach((notas) => {
                let fila = document.createElement("tr");

                fila.innerHTML = `
          <td>${notas.id_nota}</td>
          <td>${notas.calificacion_nota}</td>
          <td>${notas.comentario_nota}</td>
 
          <td>
            <button class="btn btn-primary btn-sm btnSubirArchivo" data-id="${notas.id_notas}">ver nota</button>
          </td>
        `;

                cuerpoTabla.appendChild(fila);
            });
        })
        .catch((error) => {
            console.error("Error al cargar los datos:", error);
        });
});
