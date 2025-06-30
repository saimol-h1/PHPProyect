# Manual de ejecución

## Requisitos previos
- Php 8.0 o superior

## Modificar el archivo database.php
- Cambiar los datos de conexión a la base de datos en el archivo `config/database.php`:
```php
//config/database.php
// Colocar tus datos de conexión a la base de datos aquí
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "cuarto";
        $port = 9040;
        $environment = 'development';
```

## Ejecutar el proyecto
- Habrir el navegador y acceder a `http://localhost/PracticaMVC/setup.php` 
- Este te creará la base de datos y las tablas necesarias.