<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <title>Kwiatkowski Mikolaj</title>
</head>
<body>
    <h1>Wybierz plik do przesłania</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="fileToUpload">Wybierz plik:</label>
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <input type="hidden" name="currentDir" value="<?php echo isset($_GET['dir']) ? $_GET['dir'] : ''; ?>"> <!-- Przesyłamy bieżący katalog jako ukryte pole -->
        <input type="submit" value="Prześlij plik" name="submit">
    </form>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>
