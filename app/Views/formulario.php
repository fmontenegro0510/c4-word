<!-- app/Views/formulario.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Formulario</title>
</head>
<body>
    <h2>Formulario</h2>
    <form action="<?= base_url('form/generarDocumento'); ?>" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required><br>

        <label for="dni">DNI:</label>
        <input type="text" name="dni" required><br>

        <label for="categoria">Categoría:</label>
        <input type="text" name="categoria" required><br>

        <button type="submit">Generar Documento</button>
    </form>
</body>
</html>
