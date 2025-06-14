# Guía de Despliegue a Producción

## 📋 Pasos para Desplegar en InfinityFree (Recomendado)

### 1. Registro y Configuración Inicial
1. Ve a https://infinityfree.net
2. Regístrate con tu email
3. Crea una nueva cuenta de hosting
4. Elige un subdominio gratuito (ej: `tuproyecto.great-site.net`)

### 2. Configuración de Base de Datos
1. En el panel de control, ve a "MySQL Databases"
2. Crea una nueva base de datos
3. Anota los datos de conexión:
   - Host: `sql123.infinityfree.com`
   - Usuario: `if0_12345678`
   - Contraseña: (la que elijas)
   - Base de datos: `if0_12345678_cuarto`

### 3. Subir Archivos
1. Ve a "File Manager" o usa FTP
2. Sube todos los archivos del proyecto a la carpeta `htdocs`
3. Estructura final en el servidor:
   ```
   htdocs/
   ├── config/
   ├── controllers/
   ├── models/
   ├── views/
   ├── css/
   ├── img/
   ├── index.php
   └── .htaccess
   ```

### 4. Configurar Base de Datos
1. Ve a "phpMyAdmin" en el panel
2. Selecciona tu base de datos
3. Ejecuta el script `database/schema.sql`

### 5. Actualizar Configuración
Edita `config/database.php` con tus datos reales:
```php
define('DB_HOST', 'sql123.infinityfree.com');
define('DB_USER', 'if0_12345678'); // Tu usuario real
define('DB_PASS', 'tu_password');  // Tu password real
define('DB_NAME', 'if0_12345678_cuarto'); // Tu base real
```

## 🚀 Alternativas Rápidas

### Opción 1: Railway (Con GitHub)
1. Sube tu proyecto a GitHub
2. Ve a https://railway.app
3. Conecta tu repositorio
4. Railway detectará automáticamente PHP
5. Agrega base de datos MySQL desde el dashboard

### Opción 2: Heroku (Limitado pero funcional)
1. Instala Heroku CLI
2. Crea un `composer.json`:
   ```json
   {
     "require": {
       "php": "^8.0"
     }
   }
   ```
3. Usa ClearDB MySQL (add-on gratuito)

### Opción 3: Vercel (Para frontend + API)
- Mejor para proyectos con separación frontend/backend
- MySQL a través de PlanetScale (gratis)

## ⚙️ Configuraciones Adicionales

### Para mejor SEO y rendimiento:
```apache
# En .htaccess
Header set X-Content-Type-Options nosniff
Header set X-Frame-Options DENY
Header set X-XSS-Protection "1; mode=block"
```

### Para SSL automático:
La mayoría de estos servicios ofrecen SSL gratuito automático.

## 🔒 Consideraciones de Seguridad

1. **Variables de Entorno**: Nunca subas credenciales en el código
2. **Validación de Datos**: Implementa validación en todos los formularios
3. **SQL Injection**: Usa prepared statements
4. **HTTPS**: Siempre usa HTTPS en producción

## 📞 Soporte

- **InfinityFree**: Foro de comunidad muy activo
- **000WebHost**: Chat en vivo
- **Railway**: Discord community
- **Heroku**: Documentación extensa

¿Te gustaría que te ayude con algún paso específico del despliegue?
