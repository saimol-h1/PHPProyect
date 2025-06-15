# Paleta de Colores UTA - Sistema MVC PHP

## 📋 Resumen

Se ha implementado una paleta de colores coherente basada en **#901B21** (rojo universitario oficial de la UTA) en todo el sistema MVC PHP. Esta paleta garantiza una identidad visual consistente y profesional.

## 🎨 Paleta de Colores

### Colores Principales
- **Primario**: `#901B21` (Rojo UTA)
- **Primario Oscuro**: `#7A1619` (Rojo más profundo)
- **Primario Claro**: `#A52A31` (Rojo más brillante)
- **Secundario**: `#FFD700` (Dorado UTA)

### Colores de Apoyo
- **Acento**: `#2C3E50` (Azul gris oscuro)
- **Acento Claro**: `#34495E` (Azul gris)
- **Fondo**: `#F8F9FA` (Gris muy claro)
- **Texto**: `#2C3E50` (Texto principal)
- **Texto Claro**: `#6C757D` (Texto secundario)

### Colores de Estado
- **Éxito**: `#28A745` (Verde)
- **Advertencia**: `#FFC107` (Amarillo)
- **Peligro**: `#DC3545` (Rojo)
- **Información**: `#17A2B8` (Azul)

## 📁 Archivos Modificados

### CSS Principales
1. **`css/style.css`** - Variables CSS y estilos base
2. **`css/banner-png.css`** - Estilos del banner y overlay
3. **`css/uta-theme.css`** - Tema completo UTA para Bootstrap

### Vistas
1. **`views/template.php`** - Template principal con nueva hoja de estilos
2. **`views/login.php`** - Formulario de login con colores UTA

### Archivos de Prueba
1. **`test_banner.html`** - Demostración completa de componentes

## 🔧 Variables CSS

```css
:root {
    --uta-primary: #901B21;          /* Rojo principal UTA */
    --uta-primary-dark: #7A1619;     /* Rojo oscuro */
    --uta-primary-light: #A52A31;    /* Rojo claro */
    --uta-secondary: #FFD700;        /* Dorado UTA */
    --uta-accent: #2C3E50;           /* Azul gris oscuro */
    --uta-accent-light: #34495E;     /* Azul gris */
    --uta-background: #F8F9FA;       /* Fondo claro */
    --uta-text: #2C3E50;             /* Texto principal */
    --uta-text-light: #6C757D;       /* Texto secundario */
    --uta-white: #FFFFFF;            /* Blanco */
    --uta-success: #28A745;          /* Verde éxito */
    --uta-warning: #FFC107;          /* Amarillo advertencia */
    --uta-danger: #DC3545;           /* Rojo peligro */
    --uta-info: #17A2B8;             /* Azul información */
}
```

## 🎯 Componentes Actualizados

### Bootstrap Override
- **Botones**: Todos los botones usan la paleta UTA
- **Alertas**: Colores coherentes con la identidad UTA
- **Cards**: Headers con gradientes UTA
- **Formularios**: Focus states con colores UTA
- **Tablas**: Headers con estilo UTA
- **Modales**: Headers temáticos
- **Navegación**: Gradientes y efectos hover UTA

### Componentes Personalizados
- **Banner**: Fondo y efectos con paleta UTA
- **Overlay de Usuario**: Estilo coherente con identidad
- **Animaciones**: Efectos con colores UTA

## 🚀 Uso

### Clases CSS Personalizadas
```css
.text-uta-primary    /* Texto en rojo UTA */
.text-uta-secondary  /* Texto en dorado UTA */
.bg-uta-primary      /* Fondo rojo UTA */
.bg-uta-secondary    /* Fondo dorado UTA */
.border-uta-primary  /* Borde rojo UTA */
.border-uta-secondary /* Borde dorado UTA */
```

### Animaciones UTA
```css
.fade-in-uta         /* Animación de aparición */
.pulse-uta           /* Animación de pulso */
```

## ✅ Beneficios

1. **Identidad Corporativa**: Colores oficiales UTA en todo el sistema
2. **Consistencia Visual**: Paleta unificada en todos los componentes
3. **Profesionalismo**: Diseño coherente y elegante
4. **Mantenibilidad**: Variables CSS centralizadas
5. **Flexibilidad**: Fácil modificación de colores desde las variables
6. **Accesibilidad**: Contrastes apropiados para legibilidad

## 🔄 Próximos Pasos

1. Verificar la implementación en diferentes navegadores
2. Probar la accesibilidad de contraste de colores
3. Aplicar la paleta a componentes adicionales según se agreguen
4. Documentar guías de uso para desarrolladores

## 📸 Archivos de Prueba

- **`test_banner.html`**: Demostración completa de la paleta
- Incluye ejemplos de todos los componentes estilizados
- Muestra la responsividad del sistema
- Paleta de colores visible para referencia

---

**Desarrollado para la Universidad Técnica de Ambato**  
*Sistema MVC PHP con identidad corporativa UTA*
