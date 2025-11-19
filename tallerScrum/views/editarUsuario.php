require_once '../controllers/editar_usuario.php';
?>

<div class="container">
    <h2>Editar Usuario (<?php echo ucfirst($tipo); ?>)</h2>

    <?php if(isset($usuario)): ?>
    <form method="POST" action="../controllers/editar_usuario.php?tipo=<?php echo $tipo; ?>">
        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>" required><br>

        <label>Correo electrónico:</label>
        <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required><br>

        <label>Rol:</label>
        <select name="rol" required>
            <option value="instructor" <?php echo ($usuario['rol'] == 'instructor') ? 'selected' : ''; ?>>Instructor</option>
            <option value="aprendiz" <?php echo ($usuario['rol'] == 'aprendiz') ? 'selected' : ''; ?>>Aprendiz</option>
        </select><br><br>

        <button type="submit" name="actualizar">Actualizar Usuario</button>
    </form>
    <?php else: ?>
        <p>Usuario no encontrado.</p>
    <?php endif; ?>
</div>

<?php
// include 'footer.php';
?>
