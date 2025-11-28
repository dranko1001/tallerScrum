document.addEventListener("DOMContentLoaded", () => {
    let cuerpoTabla = document.getElementById("tablaNotas");

    fetch("../controllers/mostrarNotas.php")
        .then(res => res.json())
        .then(datos => {

            cuerpoTabla.innerHTML = "";

            datos.forEach(registro => {

                let fila = document.createElement("tr");

                fila.innerHTML = `
                    <td>${registro.id_trabajo}</td>
                    <td>${registro.nombre_trabajo}</td>
                    <td>${registro.fecha_trabajo}</td>
                    <td>${registro.correo_aprendiz}</td>

                    <td>
                        <select class="form-select form-select-sm calificacion">
                            <option value="">Seleccione</option>
                            <option value="A" ${registro.calificacion_nota === "A" ? "selected" : ""}>A</option>
                            <option value="D" ${registro.calificacion_nota === "D" ? "selected" : ""}>D</option>
                        </select>
                    </td>

                    <td>
                        <input 
                            type="text"
                            class="form-control form-control-sm comentario"
                            value="${registro.comentario_nota ?? ''}"
                            placeholder="Escriba un comentario"
                        >
                    </td>

                    <td>
                        <button 
                            class="btn btn-success btn-sm guardar" 
                            data-id-aprendiz="${registro.id_aprendiz}" 
                            data-id-trabajo="${registro.id_trabajo}">
                            Guardar
                        </button>
                    </td>
                `;

                cuerpoTabla.appendChild(fila);
            });

        });
});

// Delegación de eventos para el botón Guardar
document.addEventListener("click", (e) => {
    if (e.target.classList.contains("guardar")) {

        let id_aprendiz = e.target.getAttribute("data-id-aprendiz");
        let id_trabajo = e.target.getAttribute("data-id-trabajo");

        let fila = e.target.closest("tr");
        let calificacion = fila.querySelector(".calificacion").value;
        let comentario = fila.querySelector(".comentario").value;

        if (calificacion === "") {
            alert("Seleccione una calificación A o D");
            return;
        }

        let formData = new FormData();
        formData.append("id_aprendiz", id_aprendiz);
        formData.append("id_trabajo", id_trabajo);
        formData.append("calificacion", calificacion);
        formData.append("comentario", comentario);

        fetch("../controllers/guardarNota.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                alert("✔ Nota guardada correctamente");
            } else {
                alert("❌ Error al guardar la nota: " + (resp.error || ""));
            }
        })
    }
});
