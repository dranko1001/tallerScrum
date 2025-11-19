document.addEventListener("DOMContentLoaded", () => {
    let cuerpoTabla = document.getElementById("datosReservas");

    fetch("../controllers/mostrarNotas.php")
        .then((respuesta) => respuesta.json())
        .then((datos) => {
            cuerpoTabla.innerHTML = ""; // limpiar tabla

            datos.forEach((trabajo) => {
                let fila = document.createElement("tr");

                fila.innerHTML = `
          <td>${trabajo.id_trabajo}</td>
          <td>${trabajo.nombre_trabajo}</td>
          <td>${trabajo.fecha_trabajo}</td>
          <td>
            <button class="btn btn-primary btn-sm btnSubirArchivo" data-id="${trabajo.id_trabajo}">ver nota</button>
          </td>
        `;

                cuerpoTabla.appendChild(fila);
            });
        })
        .catch((error) => {
            console.error("Error al cargar los datos:", error);
        });
});
