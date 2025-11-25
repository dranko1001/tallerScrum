    console.log("JS editar_usuario.js cargado ");

    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("btn-editar")) {

            let id = e.target.dataset.id;
            let rol = e.target.dataset.rol;
            let correo = e.target.dataset.correo;

            console.log("Datos recibidos:", id, rol, correo);

            document.getElementById("edit_id").value = id;
            document.getElementById("edit_rol").value = rol;
            document.getElementById("edit_correo").value = correo;
        }
    });

    document.getElementById("btnGuardarCambios").addEventListener("click", function () {

        let formData = new FormData();
        formData.append("id", document.getElementById("edit_id").value);
        formData.append("rol", document.getElementById("edit_rol").value);
        formData.append("correo", document.getElementById("edit_correo").value);
        formData.append("password", document.getElementById("edit_password").value);

        // ← ESTA ES LA CORRECCIÓN
        console.log("Enviando a PHP:");
        for (let p of formData.entries()) {
            console.log(p[0] + " = " + p[1]);
        }

        fetch("controllers/editar_usuario.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(resp => {
            console.log("Respuesta PHP:", resp);

            if (resp.trim() === "OK") {
                Swal.fire("Usuario actualizado", "", "success");
                setTimeout(() => location.reload(), 1200);
            } else {
                Swal.fire("Error", resp, "error");
            }
        })
        .catch(err => {
            console.error("Error en fetch:", err);
            Swal.fire("Error crítico", "No se pudo enviar la petición", "error");
        });
    });
