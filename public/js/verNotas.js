document.addEventListener("DOMContentLoaded", () => {
    let cuerpoTabla = document.getElementById("datosReservas");

    fetch("../controllers/mostrarNotas.php")
        .then((respuesta) => respuesta.json())
        .then((datos) => {
            cuerpoTabla.innerHTML = ""; // limpiar tabla

            datos.forEach((trabajo) => {
                let fila = document.createElement("tr");

                // Input file oculto para subir
                let inputFile = document.createElement("input");
                inputFile.type = "file";
                inputFile.style.display = "none";
                inputFile.id = `file_${trabajo.id_trabajo}`;

                fila.innerHTML = `
                    <td>${trabajo.id_trabajo}</td>
                    <td>${trabajo.nombre_trabajo}</td>
                    <td>${trabajo.fecha_trabajo}</td>
                    <td>
                        <button class="btn btn-primary btn-sm btnSubirArchivo" data-id="${trabajo.id_trabajo}">Seleccionar Archivo</button>
                        <button class="btn btn-success btn-sm btnVerArchivo" data-id="${trabajo.id_trabajo}">Ver Archivo</button>
                    </td>
                `;

                fila.appendChild(inputFile);
                cuerpoTabla.appendChild(fila);

                // Evento para subir archivo
                fila.querySelector(".btnSubirArchivo").addEventListener("click", () => {
                    inputFile.click();
                });

                inputFile.addEventListener("change", (event) => {
                    const archivo = event.target.files[0];
                    if (archivo) {
                        console.log("Archivo seleccionado para el trabajo ID " + trabajo.id_trabajo, archivo);

                        const formData = new FormData();
                        formData.append("id_trabajo", trabajo.id_trabajo);
                        formData.append("archivo", archivo);

                        fetch("../controllers/subirArchivo.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(res => res.json())
                        .then(respuesta => {
                            Swal.fire({
                                icon: "success",
                                title: "Archivo subido correctamente",
                                text: respuesta.mensaje || ""
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: "error",
                                title: "Error al subir archivo",
                                text: error
                            });
                        });
                    }
                });

                // Evento para ver archivo
                fila.querySelector(".btnVerArchivo").addEventListener("click", () => {
                    // Supongamos que tu backend devuelve la URL del archivo
                    fetch(`../controllers/verArchivo.php?id=${trabajo.id_trabajo}`)
                        .then(res => res.json())
                        .then(respuesta => {
                            if (respuesta.url) {
                                // Abrir el archivo en nueva pestaña
                                window.open(respuesta.url, "_blank");
                            } else {
                                Swal.fire({
                                    icon: "warning",
                                    title: "No hay archivo disponible"
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: "error",
                                title: "Error al cargar archivo",
                                text: error
                            });
                        });
                });
            });
        })
        .catch((error) => {
            console.error("Error al cargar los datos:", error);
        });
});
