<?php
// Verificar que el usuario esté logueado para acceder a servicios
if (!isLoggedIn()) {
    echo "<div class='alert alert-warning'>Debes iniciar sesión para acceder a esta sección.</div>";
    return;
}

$usuario_info = getUsuarioInfo();
$es_admin = isAdmin();
?>

<div class="container-fluid">
    <h2 class="mb-4">🎓 Gestión de Estudiantes</h2> <!-- Información del usuario -->
    <div class="alert alert-info mb-4">
        <strong>👤 Bienvenido:</strong> <?php echo htmlspecialchars($usuario_info['nombre_completo'] ?? 'Usuario'); ?>
        <span class="badge bg-primary"><?php echo ucfirst($usuario_info['tipo_usuario'] ?? 'usuario'); ?></span>
    </div>

    <!-- Botones de acción -->
    <div class="row mb-4">
        <div class="col-md-12">
            <?php if ($es_admin): ?>
                <button class="btn btn-success me-2" onclick="mostrarFormulario()">
                    <i class="fas fa-plus"></i> Agregar Estudiante
                </button>
            <?php endif; ?>
            <button class="btn btn-primary" onclick="cargarEstudiantes()">
                <i class="fas fa-refresh"></i> Actualizar Lista
            </button>
        </div>
    </div>

    <!-- Formulario para agregar/editar estudiante -->
    <?php if ($es_admin): ?>
        <div id="formularioEstudiante" class="card mb-4" style="display: none;">
            <div class="card-header">
                <h5 id="tituloFormulario">📝 Agregar Nuevo Estudiante</h5>
            </div>
            <div class="card-body">
                <form id="formEstudiante">
                    <input type="hidden" id="estudianteId" name="id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombres" class="form-label">Nombres:</label>
                            <input type="text" class="form-control" id="nombres" name="nombres" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellidos" class="form-label">Apellidos:</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cedula" class="form-label">Cédula:</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="carrera" class="form-label">Carrera:</label>
                            <input type="text" class="form-control" id="carrera" name="carrera" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="semestre" class="form-label">Semestre:</label>
                            <input type="number" class="form-control" id="semestre" name="semestre" min="1" max="10">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="direccion" class="form-label">Dirección:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="ocultarFormulario()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Lista de estudiantes -->
    <div class="card">
        <div class="card-header">
            <h5>📋 Lista de Estudiantes</h5>
        </div>
        <div class="card-body">
            <div id="tablaEstudiantes">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando estudiantes...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts específicos para gestión de estudiantes -->
<script>
    // Variables globales
    let esAdmin = <?php echo $es_admin ? 'true' : 'false'; ?>;

    // Cargar estudiantes al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        cargarEstudiantes();
    }); // Función para cargar estudiantes
    function cargarEstudiantes() {
        fetch('models/select.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarTablaEstudiantes(data.data);
                } else {
                    document.getElementById('tablaEstudiantes').innerHTML =
                        '<div class="alert alert-danger">Error al cargar estudiantes: ' + (data.error || 'Error desconocido') + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('tablaEstudiantes').innerHTML =
                    '<div class="alert alert-danger">Error de conexión</div>';
            });
    }

    // Función para mostrar tabla de estudiantes
    function mostrarTablaEstudiantes(estudiantes) {
        let html = '';

        if (estudiantes.length === 0) {
            html = '<div class="alert alert-info">No hay estudiantes registrados</div>';
        } else {
            html = `
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Cédula</th>
                            <th>Carrera</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            ${esAdmin ? '<th>Acciones</th>' : ''}
                        </tr>
                    </thead>
                    <tbody>
        `;

            estudiantes.forEach(estudiante => {
                html += `
                <tr>
                    <td>${estudiante.id}</td>
                    <td>${estudiante.nombres}</td>
                    <td>${estudiante.apellidos}</td>
                    <td>${estudiante.cedula}</td>
                    <td>${estudiante.carrera}</td>
                    <td>${estudiante.email}</td>
                    <td>${estudiante.telefono || 'N/A'}</td>
                    ${esAdmin ? `
                        <td>
                            <button class="btn btn-sm btn-warning me-1" onclick="editarEstudiante(${estudiante.id})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarEstudiante(${estudiante.id})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    ` : ''}
                </tr>
            `;
            });

            html += `
                    </tbody>
                </table>
            </div>
        `;
        }

        document.getElementById('tablaEstudiantes').innerHTML = html;
    }

    // Funciones para el formulario (solo si es admin)
    <?php if ($es_admin): ?>

        function mostrarFormulario() {
            document.getElementById('formularioEstudiante').style.display = 'block';
            document.getElementById('tituloFormulario').textContent = '📝 Agregar Nuevo Estudiante';
            document.getElementById('formEstudiante').reset();
            document.getElementById('estudianteId').value = '';
        }

        function ocultarFormulario() {
            document.getElementById('formularioEstudiante').style.display = 'none';
        }

        function editarEstudiante(id) {
            // Cargar datos del estudiante para edición
            fetch(`models/editar.php?id=${id}`)
                .then(response => response.text()) // Primero obtener como texto
                .then(text => {
                    // Limpiar posibles warnings de PHP antes del JSON
                    const jsonStart = text.indexOf('{');
                    const cleanText = jsonStart !== -1 ? text.substring(jsonStart) : text;

                    try {
                        return JSON.parse(cleanText);
                    } catch (e) {
                        console.error('Error parsing JSON:', cleanText);
                        throw new Error('Respuesta inválida del servidor');
                    }
                })
                .then(data => {
                    if (data.success) {
                        const estudiante = data.data;

                        // Llenar el formulario con los datos del estudiante
                        document.getElementById('estudianteId').value = estudiante.id;
                        document.getElementById('nombres').value = estudiante.nombres;
                        document.getElementById('apellidos').value = estudiante.apellidos;
                        document.getElementById('cedula').value = estudiante.cedula;
                        document.getElementById('carrera').value = estudiante.carrera;
                        document.getElementById('email').value = estudiante.email;
                        document.getElementById('telefono').value = estudiante.telefono || '';
                        document.getElementById('semestre').value = estudiante.semestre || '';
                        document.getElementById('fecha_nacimiento').value = estudiante.fecha_nacimiento || '';
                        document.getElementById('direccion').value = estudiante.direccion || '';

                        // Cambiar título y mostrar formulario
                        document.getElementById('tituloFormulario').textContent = '✏️ Editar Estudiante';
                        document.getElementById('formularioEstudiante').style.display = 'block';

                        // Scroll hacia el formulario
                        document.getElementById('formularioEstudiante').scrollIntoView({
                            behavior: 'smooth'
                        });
                    } else {
                        alert('Error al cargar los datos del estudiante: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error de conexión al cargar los datos del estudiante: ' + error.message);
                });
        }


        function eliminarEstudiante(id) {
            if (confirm('¿Estás seguro de eliminar este estudiante? Esta acción no se puede deshacer.')) {
                fetch('models/eliminar.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${id}`
                    })
                    .then(response => response.text()) // Primero obtener como texto
                    .then(text => {
                        // Limpiar posibles warnings de PHP antes del JSON
                        const jsonStart = text.indexOf('{');
                        const cleanText = jsonStart !== -1 ? text.substring(jsonStart) : text;

                        try {
                            return JSON.parse(cleanText);
                        } catch (e) {
                            console.error('Error parsing JSON:', cleanText);
                            throw new Error('Respuesta inválida del servidor');
                        }
                    })
                    .then(data => {
                        if (data.success) {
                            alert('Estudiante eliminado: ' + (data.eliminado || 'Exitosamente'));
                            cargarEstudiantes(); // Recargar la lista
                        } else {
                            alert('Error al eliminar estudiante: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error de conexión al eliminar estudiante: ' + error.message);
                    });
            }
        } // Manejar envío del formulario
        document.getElementById('formEstudiante').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const estudianteId = document.getElementById('estudianteId').value;

            // Determinar si es edición o creación
            const url = estudianteId ? 'models/editar.php' : 'models/guardar.php';
            const accion = estudianteId ? 'actualizado' : 'creado';

            fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text()) // Primero obtener como texto
                .then(text => {
                    // Limpiar posibles warnings de PHP antes del JSON
                    const jsonStart = text.indexOf('{');
                    const cleanText = jsonStart !== -1 ? text.substring(jsonStart) : text;

                    try {
                        return JSON.parse(cleanText);
                    } catch (e) {
                        console.error('Error parsing JSON:', cleanText);
                        throw new Error('Respuesta inválida del servidor');
                    }
                })
                .then(data => {
                    if (data.success) {
                        alert(`Estudiante ${accion} exitosamente: ` + (data.estudiante || data.message));
                        ocultarFormulario();
                        cargarEstudiantes(); // Recargar la lista
                    } else {
                        alert('Error al guardar estudiante: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error de conexión al guardar estudiante: ' + error.message);
                });
        });
    <?php endif; ?>
</script>