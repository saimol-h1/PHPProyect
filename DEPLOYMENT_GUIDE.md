# 🚀 Despliegue del Sistema MVC UTA en Railway

## Pasos Completados ✅

### 1. Preparación del Código
- [x] Archivos de Railway (`Procfile`, `package.json`)
- [x] Configuración híbrida de base de datos
- [x] Setup automático para Railway
- [x] Gitignore configurado

### 2. Pasos en Railway.app

1. **Crear cuenta:** [railway.app](https://railway.app)
2. **Nuevo proyecto:** "Deploy from GitHub repo"
3. **Agregar MySQL:** "+ New" → "Database" → "MySQL"
4. **Variables:** Se configuran automáticamente

### 3. Después del Despliegue

Tu aplicación estará disponible en: `https://tu-proyecto.railway.app`

**Credenciales de prueba:**
- Admin: `admin` / `admin123`
- Secretaria: `secretaria1` / `secret123`

### 4. Funcionalidades

- ✅ Login con roles (admin/secretaria)
- ✅ CRUD de estudiantes 
- ✅ Navegación por secciones
- ✅ Diseño responsivo
- ✅ Persistencia de sesiones

### 5. Monitoreo

- **Logs:** En Railway dashboard → tu proyecto → Deployments
- **Base de datos:** Railway MySQL dashboard
- **Métricas:** CPU, memoria, requests

## 🆘 Solución de Problemas

Si hay errores después del despliegue:
1. Revisar logs en Railway dashboard
2. Verificar variables de entorno MySQL
3. Ejecutar manualmente: `https://tu-url.railway.app/railway_setup.php`

## 📞 Soporte

Sistema desarrollado para la Universidad Técnica de Ambato
Arquitectura MVC con PHP, MySQL y Bootstrap
