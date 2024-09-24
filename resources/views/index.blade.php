<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel + Vue 3</title>
    <script src="{{ mix('js/app.js') }}"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    @vite('resources/js/app.js') <!-- Sertakan file JavaScript Vite -->
</head>
<body>
    <div id="app">
        <!-- <example-component></example-component> Panggil komponen Vue -->
        <search-component></search-component>
    </div>
</body>
</html>
