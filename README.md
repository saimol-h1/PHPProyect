# Proyecto MVC - Universidad Técnica de Ambato

## Sistema de Rutas Relativas

Este proyecto ha sido configurado con un sistema de rutas relativas centralizado para facilitar el mantenimiento y la portabilidad del código.

### Estructura del Proyecto

```
PracticaMVC/
├── config/
│   └── config.php          # Configuración centralizada de rutas
├── controllers/
│   └── controller.php      # Controladores MVC
├── models/
│   ├── model.php          # Modelo principal
│   ├── conexion.php       # Conexión a base de datos
│   ├── guardar.php        # CRUD - Crear
│   ├── select.php         # CRUD - Leer
│   ├── editar.php         # CRUD - Actualizar
│   └── eliminar.php       # CRUD - Eliminar
├── views/
│   ├── template.php       # Plantilla principal
│   ├── inicio.php         # Vista de inicio
│   ├── nosotros.php       # Vista nosotros
│   ├── servicios.php      # Vista servicios
│   └── contactanos.php    # Vista contactanos
├── css/
│   └── style.css          # Estilos del proyecto
├── img/                   # Imágenes
├── jquery/                # Archivos JavaScript
├── index.php              # Punto de entrada
└── .htaccess              # Configuración del servidor
```

### Configuración de Rutas

El archivo `config/config.php` centraliza toda la configuración de rutas:

#### Constantes Definidas:
- `BASE_PATH`: Ruta base del proyecto
- `BASE_URL`: URL base para el frontend
- `CONTROLLERS_PATH`: Ruta a los controladores
- `MODELS_PATH`: Ruta a los modelos
- `VIEWS_PATH`: Ruta a las vistas
- `CSS_PATH`: Ruta a los archivos CSS
- `JS_PATH`: Ruta a los archivos JavaScript
- `IMG_PATH`: Ruta a las imágenes

#### Funciones Principales:

1. **getPath($type, $file)**: Genera rutas absolutas del sistema de archivos
   ```php
   $rutaControlador = getPath('controller', 'controller.php');
   ```

2. **getUrl($type, $file)**: Genera URLs para el frontend
   ```php
   echo getUrl('css', 'style.css'); // Para enlaces CSS
   ```

3. **includeFile($type, $file)**: Incluye archivos de forma segura
   ```php
   includeFile('view', 'inicio.php');
   ```

4. **requireFile($type, $file)**: Requiere archivos de forma segura
   ```php
   requireFile('model', 'conexion.php');
   ```

### Navegación

Las URLs del proyecto siguen el patrón:
- `index.php?action=inicio` - Página de inicio
- `index.php?action=nosotros` - Página nosotros
- `index.php?action=servicios` - Página servicios
- `index.php?action=contactanos` - Página contactanos

### Beneficios del Sistema

1. **Portabilidad**: El proyecto funciona en cualquier ubicación sin modificar código
2. **Mantenimiento**: Cambios de estructura se hacen solo en `config.php`
3. **Seguridad**: Validación centralizada de rutas y archivos
4. **Escalabilidad**: Fácil agregar nuevas rutas y recursos
5. **Consistencia**: Todas las rutas siguen el mismo patrón

### Instrucciones de Uso

1. **Para agregar una nueva vista**:
   - Crear el archivo en `views/`
   - Agregar la página al array `$validPages` en `model.php`
   - El enlace será automáticamente `index.php?action=nombre_vista`

2. **Para agregar recursos CSS/JS**:
   - Colocar archivos en las carpetas correspondientes
   - Usar `getUrl('css', 'archivo.css')` en las vistas

3. **Para mover el proyecto**:
   - Simplemente copiar toda la carpeta
   - Las rutas se ajustarán automáticamente

### Configuración del Servidor

El archivo `.htaccess` incluye:
- Reescritura de URLs
- Configuración de cache
- Compresión de archivos
- Seguridad básica

### Base de Datos

La conexión está configurada en `models/conexion.php` para la base de datos "cuarto" con las siguientes credenciales por defecto:
- Servidor: localhost
- Usuario: root
- Contraseña: (vacía)
- Base de datos: cuarto
