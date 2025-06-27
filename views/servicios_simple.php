<?php
// Verificar que el usuario esté logueado para acceder a servicios
if (!isLoggedIn()) {
    echo "<div class='alert alert-warning'>Debes iniciar sesión para acceder a esta sección.</div>";
    return;
}

$usuario_info = getUsuarioInfo();
$es_admin = isAdmin();
?>

<style>
    /* Mejorar visibilidad de fila seleccionada */
    .fila-estudiante {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .fila-estudiante:hover {
        background-color: #f8d7da !important;
        /* rojo claro Bootstrap */
    }

    .fila-estudiante.table-active {
        background-color: #c82333 !important;
        /* rojo oscuro Bootstrap */
        color: #fff !important;
        font-weight: 500;
    }

    .fila-estudiante.table-active td {
        background-color: #c82333 !important;
        color: #fff !important;
    }

    .fila-estudiante.table-active:hover,
    .fila-estudiante.table-active:hover td {
        background-color: #a71d2a !important;
        /* rojo más oscuro */
        color: #fff !important;
    }

    /* Botones en fila seleccionada */
    .fila-estudiante.table-active .btn {
        border-color: #fff;
        color: #fff;
    }

    .fila-estudiante.table-active .btn:hover {
        background-color: #fff;
        color: #c82333;
    }
</style>

<div class="container-fluid">
    <h2 class="mb-4">🎓 Gestión de Estudiantes</h2>
    <div class="alert alert-info mb-4">
        <strong>👤 Bienvenido:</strong> <?php echo htmlspecialchars($usuario_info['nombre_completo'] ?? 'Usuario'); ?>
        <span class="badge bg-primary"><?php echo ucfirst($usuario_info['tipo_usuario'] ?? 'usuario'); ?></span>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <?php if ($es_admin): ?>
                <button class="btn btn-success me-2" onclick="mostrarFormularioEstudiante()">
                    <i class="fas fa-plus"></i> Agregar Estudiante
                </button>
                <button class="btn btn-warning me-2" onclick="mostrarFormularioSecretaria()">
                    <i class="fas fa-plus"></i> Agregar Secretaria
                </button>
            <?php endif; ?>
            <button class="btn btn-primary" onclick="cargarEstudiantes()">
                <i class="fas fa-refresh"></i> Actualizar Lista
            </button>
            <a type="button" class="btn btn-sm" href="reports/reporteGeneral.php" target="_blank">
                <i class="fas fa-users"></i> Reporte de Estudiantes
            </a>
        </div>
    </div>

    <!-- MODAL ESTUDIANTE -->
    <div class="modal fade" id="modalEstudiante" tabindex="-1" aria-labelledby="modalEstudianteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEstudianteLabel">📝 Agregar Nuevo Estudiante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="msgEstudiante"></div>
                    <form id="formEstudiante" novalidate>
                        <input type="hidden" id="estudianteId" name="id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombres" class="form-label">Nombres:</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo letras">
                                <div class="invalid-feedback">Solo se permiten letras.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label">Apellidos:</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo letras">
                                <div class="invalid-feedback">Solo se permiten letras.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cedula" class="form-label">Cédula:</label>
                                <input type="text" class="form-control" id="cedula" name="cedula" required maxlength="10" pattern="[0-9]{10}" title="Debe ser 10 dígitos numéricos">
                                <div class="invalid-feedback">La cédula debe ser 10 dígitos numéricos.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="carrera" class="form-label">Carrera:</label>
                                <input type="text" class="form-control" id="carrera" name="carrera" required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo letras">
                                <div class="invalid-feedback">Solo se permiten letras.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">Por favor, introduce un email válido.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono:</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" maxlength="10" pattern="[0-9]{10}" title="Debe ser 10 dígitos numéricos">
                                <div class="invalid-feedback">El teléfono debe ser 10 dígitos numéricos.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="semestre" class="form-label">Semestre:</label>
                                <input type="number" class="form-control" id="semestre" name="semestre" min="0" max="10" required>
                                <div class="invalid-feedback">El semestre debe ser un número entre 0 y 10.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                                <div class="invalid-feedback" id="feedbackFechaNacimiento">Debe tener al menos 19 años.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="direccion" class="form-label">Dirección:</label>
                                <input type="text" class="form-control" id="direccion" name="direccion">
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SECRETARIA -->
    <div class="modal fade" id="modalSecretaria" tabindex="-1" aria-labelledby="modalSecretariaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSecretariaLabel">📝 Agregar Nueva Secretaria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="msgSecretaria"></div>
                    <form id="formSecretaria" novalidate>
                        <input type="hidden" id="secretariaId" name="id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario_secretaria" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="usuario_secretaria" name="usuario" required>
                                <div class="invalid-feedback">El usuario es requerido.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="clave_secretaria" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="clave_secretaria" name="clave" required minlength="6">
                                <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="nombre_secretaria" class="form-label">Nombre Completo:</label>
                                <input type="text" class="form-control" id="nombre_secretaria" name="nombre" required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo letras y espacios">
                                <div class="invalid-feedback">Solo se permiten letras y espacios.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="email_secretaria" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email_secretaria" name="email" required>
                                <div class="invalid-feedback">Por favor, introduce un email válido.</div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <input type="text" class="form-control" id="busquedaCedula" placeholder="Buscar por cédula...">
        <br>
        <button id="btnReporteCedula" class="btn btn-primary mb-3" disabled>Ver Reporte de Cédula</button>
    </div>

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

    <!-- MODAL CONFIRMACIÓN ELIMINAR -->
    <div class="modal fade" id="modalConfirmarEliminar" tabindex="-1" aria-labelledby="modalConfirmarEliminarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalConfirmarEliminarLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                        <h5>¿Estás seguro de eliminar este estudiante?</h5>
                        <p class="text-muted">Esta acción no se puede deshacer.</p>
                        <div id="estudianteAEliminar" class="alert alert-info">
                            <!-- Aquí se mostrará la información del estudiante -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let esAdmin = <?php echo $es_admin ? 'true' : 'false'; ?>;

    // Función para permitir solo números en el input
    function allowOnlyNumbers(event) {
        const charCode = (event.which) ? event.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            event.preventDefault(); // Evita que se escriba el carácter
        }
    }

    // Función para permitir solo letras (y espacios)
    function allowOnlyLetters(event) {
        const charCode = (event.which) ? event.which : event.keyCode;
        // Permitir letras (mayúsculas y minúsculas), espacios, y teclas de control (como backspace, delete, flechas)
        // Incluye códigos para letras acentuadas y Ñ/ñ
        if (!((charCode >= 65 && charCode <= 90) || // A-Z
                (charCode >= 97 && charCode <= 122) || // a-z
                charCode === 32 || // Espacio
                charCode === 209 || charCode === 241 || // Ñ, ñ (códigos comunes)
                (charCode >= 192 && charCode <= 255 && charCode !== 215 && charCode !== 247)) // Caracteres extendidos para acentos, pero excluyendo × (215) y ÷ (247)
            &&
            charCode > 31 // Teclas de control (BackSpace, Tab, Enter, Shift, Ctrl, Alt, CapsLock, Esc, PageUp, PageDown, End, Home, Left Arrow, Up Arrow, Right Arrow, Down Arrow, Insert, Delete)
        ) {
            event.preventDefault();
        }
    }


    function validarEdad() {
        const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
        const feedbackFechaNacimiento = document.getElementById('feedbackFechaNacimiento');
        const fechaNacimiento = new Date(fechaNacimientoInput.value);
        const hoy = new Date();
        const edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
        const mes = hoy.getMonth() - fechaNacimiento.getMonth();

        let esValida = true;

        if (fechaNacimientoInput.value === '') {
            esValida = false; // Campo requerido
            feedbackFechaNacimiento.textContent = 'Este campo es requerido.';
        } else if (edad < 16 || (edad === 16 && mes < 0) || (edad === 16 && mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
            esValida = false;
            feedbackFechaNacimiento.textContent = 'Debe ser mayor de 16 años.';
        } else {
            feedbackFechaNacimiento.textContent = ''; // Limpiar mensaje de error
        }

        if (!esValida) {
            fechaNacimientoInput.classList.remove('is-valid');
            fechaNacimientoInput.classList.add('is-invalid');
        } else {
            fechaNacimientoInput.classList.remove('is-invalid');
            fechaNacimientoInput.classList.add('is-valid');
        }
        return esValida;
    }

    // Función para validar el semestre (entre 0 y 10)
    function validarSemestre() {
        const semestreInput = document.getElementById('semestre');
        const semestreValue = semestreInput.value.trim();
        const feedbackSemestre = semestreInput.nextElementSibling; // El div.invalid-feedback

        let isValid = true;
        let message = '';

        if (semestreValue === '') {
            isValid = false;
            message = 'El semestre es obligatorio.';
        } else {
            const numValue = parseInt(semestreValue);
            if (isNaN(numValue) || numValue < 0 || numValue > 10) {
                isValid = false;
                message = 'El semestre debe ser un número entre 0 y 10.';
            }
        }

        if (isValid) {
            semestreInput.classList.remove('is-invalid');
            semestreInput.classList.add('is-valid');
            feedbackSemestre.textContent = '';
        } else {
            semestreInput.classList.remove('is-valid');
            semestreInput.classList.add('is-invalid');
            feedbackSemestre.textContent = message;
        }
        return isValid;
    }


    // Cargar estudiantes al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        cargarEstudiantes();

        // Inicializar validación de Bootstrap en formularios (con listeners genéricos)
        const forms = document.querySelectorAll('form'); // Seleccionar todos los formularios
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', function(event) {
                let formValido = true;

                // Validaciones específicas para el formulario de estudiantes
                if (form.id === 'formEstudiante') {
                    if (!validarSemestre()) formValido = false;
                    if (!validarEdad()) formValido = false;
                }
                // Las validaciones de pattern y required de HTML5 se manejan con checkValidity
                if (!this.checkValidity()) {
                    formValido = false;
                }

                if (!formValido) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                this.classList.add('was-validated');
            }, false);
        });

        // --- APLICAR EVENT LISTENERS PARA CONTROL DE TECLADO Y FEEDBACK VISUAL ---
        if (esAdmin) {
            // Formulario Estudiante
            document.getElementById('nombres').addEventListener('keypress', allowOnlyLetters);
            document.getElementById('nombres').addEventListener('input', function() {
                this.classList.toggle('is-invalid', !this.checkValidity());
                this.classList.toggle('is-valid', this.checkValidity());
            });

            document.getElementById('apellidos').addEventListener('keypress', allowOnlyLetters);
            document.getElementById('apellidos').addEventListener('input', function() {
                this.classList.toggle('is-invalid', !this.checkValidity());
                this.classList.toggle('is-valid', this.checkValidity());
            });

            document.getElementById('cedula').addEventListener('keypress', allowOnlyNumbers);
            document.getElementById('cedula').addEventListener('input', function() {
                this.classList.toggle('is-invalid', !this.checkValidity());
                this.classList.toggle('is-valid', this.checkValidity());
            });

            document.getElementById('carrera').addEventListener('keypress', allowOnlyLetters);
            document.getElementById('carrera').addEventListener('input', function() {
                this.classList.toggle('is-invalid', !this.checkValidity());
                this.classList.toggle('is-valid', this.checkValidity());
            });

            document.getElementById('email').addEventListener('input', function() {
                this.classList.toggle('is-invalid', !this.checkValidity());
                this.classList.toggle('is-valid', this.checkValidity());
            });

            document.getElementById('telefono').addEventListener('keypress', allowOnlyNumbers);
            document.getElementById('telefono').addEventListener('input', function() {
                this.classList.toggle('is-invalid', !this.checkValidity());
                this.classList.toggle('is-valid', this.checkValidity());
            });

            // Semestre usa su propia función de validación
            document.getElementById('semestre').addEventListener('input', validarSemestre);
            document.getElementById('semestre').addEventListener('change', validarSemestre); // También al cambiar el valor completo

            // Fecha de nacimiento usa su propia función de validación
            document.getElementById('fecha_nacimiento').addEventListener('change', validarEdad);


            // Formulario Secretaria
            document.getElementById('usuario_secretaria').addEventListener('input', function() {
                this.classList.toggle('is-invalid', !this.checkValidity());
                this.classList.toggle('is-valid', this.checkValidity());
            });
            document.getElementById('clave_secretaria').addEventListener('input', function() {
                this.classList.toggle('is-invalid', !this.checkValidity());
                this.classList.toggle('is-valid', this.checkValidity());
            });

            // Nombre Completo de Secretaria - Control de teclado y feedback visual
            document.getElementById('nombre_secretaria').addEventListener('keypress', allowOnlyLetters);
            document.getElementById('nombre_secretaria').addEventListener('input', function() {
                this.classList.toggle('is-invalid', !this.checkValidity());
                this.classList.toggle('is-valid', this.checkValidity());
            });

            document.getElementById('email_secretaria').addEventListener('input', function() {
                this.classList.toggle('is-invalid', !this.checkValidity());
                this.classList.toggle('is-valid', this.checkValidity());
            });
        }
        // Buscador de cédula
        document.getElementById('busquedaCedula').addEventListener('keypress', allowOnlyNumbers);
    });

    // Función para cargar estudiantes (sin cambios)
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

    // Función para mostrar tabla de estudiantes (sin cambios significativos, usa el campo de la BD tal cual)
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
                            <th>Semestre</th> <th>Fecha Nacimiento</th> <th>Dirección</th> ${esAdmin ? '<th>Acciones</th>' : ''}
                        </tr>
                    </thead>
                    <tbody>
            `;

            estudiantes.forEach(estudiante => {
                html += `
                <tr class="fila-estudiante" data-cedula="${estudiante.cedula}">
                    <td>${estudiante.id}</td>
                    <td>${estudiante.nombres || 'N/A'}</td> <td>${estudiante.apellidos || 'N/A'}</td>
                    <td>${estudiante.cedula || 'N/A'}</td>
                    <td>${estudiante.carrera || 'N/A'}</td>
                    <td>${estudiante.email || 'N/A'}</td>
                    <td>${estudiante.telefono || 'N/A'}</td>
                    <td>${estudiante.semestre || 'N/A'}</td> <td>${estudiante.fecha_nacimiento || 'N/A'}</td> <td>${estudiante.direccion || 'N/A'}</td> ${esAdmin ? `
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

        document.querySelectorAll('.fila-estudiante').forEach(fila => {
            fila.addEventListener('click', function() {
                // Quitar selección previa
                document.querySelectorAll('.fila-estudiante').forEach(f => f.classList.remove('table-active'));
                this.classList.add('table-active');
                // Habilitar botón y guardar cédula seleccionada
                document.getElementById('btnReporteCedula').disabled = false;
                document.getElementById('btnReporteCedula').dataset.cedula = this.dataset.cedula;
            });
        })
    }

    // Funciones para el formulario (solo si es admin)
    <?php if ($es_admin): ?>

        function mostrarFormularioEstudiante() {
            document.getElementById('formEstudiante').reset();
            document.getElementById('estudianteId').value = '';
            document.getElementById('cedula').removeAttribute('readonly');
            document.getElementById('formEstudiante').classList.remove('was-validated');
            document.getElementById('msgEstudiante').innerHTML = '';
            document.getElementById('modalEstudianteLabel').textContent = '📝 Agregar Nuevo Estudiante';
            establecerFechaNacimientoMaxima();
            // Limpiar validaciones visuales
            ['nombres', 'apellidos', 'cedula', 'carrera', 'email', 'telefono', 'semestre', 'fecha_nacimiento', 'direccion'].forEach(id => {
                let el = document.getElementById(id);
                if (el) el.classList.remove('is-valid', 'is-invalid');
            });
            document.getElementById('feedbackFechaNacimiento').textContent = 'Debe ser mayor de 16 años.';
            var modal = new bootstrap.Modal(document.getElementById('modalEstudiante'));
            modal.show();
        }

        function mostrarFormularioSecretaria() {
            document.getElementById('formSecretaria').reset();
            document.getElementById('secretariaId').value = '';
            document.getElementById('formSecretaria').classList.remove('was-validated');
            document.getElementById('msgSecretaria').innerHTML = '';
            document.getElementById('modalSecretariaLabel').textContent = '📝 Agregar Nueva Secretaria';
            ['usuario_secretaria', 'clave_secretaria', 'nombre_secretaria', 'email_secretaria'].forEach(id => {
                let el = document.getElementById(id);
                if (el) el.classList.remove('is-valid', 'is-invalid');
            });
            var modal = new bootstrap.Modal(document.getElementById('modalSecretaria'));
            modal.show();
        }

        function ocultarFormularioEstudiante() {
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalEstudiante'));
            if (modal) modal.hide();
        }

        function ocultarFormularioSecretaria() {
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalSecretaria'));
            if (modal) modal.hide();
        }

        // Función para establecer la fecha de nacimiento máxima (al menos 16 años)
        function establecerFechaNacimientoMaxima() {
            const hoy = new Date();
            const fechaMinimaNacimiento = new Date(hoy.getFullYear() - 16, hoy.getMonth(), hoy.getDate());
            const yyyy = fechaMinimaNacimiento.getFullYear();
            const mm = String(fechaMinimaNacimiento.getMonth() + 1).padStart(2, '0');
            const dd = String(fechaMinimaNacimiento.getDate()).padStart(2, '0');
            document.getElementById('fecha_nacimiento').setAttribute('max', `${yyyy}-${mm}-${dd}`);
        }

        function editarEstudiante(id) {
            // Limpiar validaciones visuales y mensajes
            document.getElementById('formEstudiante').classList.remove('was-validated');
            document.getElementById('msgEstudiante').innerHTML = '';
            ['nombres', 'apellidos', 'cedula', 'carrera', 'email', 'telefono', 'semestre', 'fecha_nacimiento', 'direccion'].forEach(idf => {
                let el = document.getElementById(idf);
                if (el) el.classList.remove('is-valid', 'is-invalid');
            });
            document.getElementById('feedbackFechaNacimiento').textContent = 'Debe tener al menos 16 años.';
            fetch(`models/editar.php?id=${id}`)
                .then(response => response.text())
                .then(text => {
                    const jsonStart = text.indexOf('{');
                    const cleanText = jsonStart !== -1 ? text.substring(jsonStart) : text;
                    try {
                        return JSON.parse(cleanText);
                    } catch (e) {
                        throw new Error('Respuesta inválida del servidor');
                    }
                })
                .then(data => {
                    if (data.success) {
                        const estudiante = data.data;
                        document.getElementById('estudianteId').value = estudiante.id;
                        document.getElementById('nombres').value = estudiante.nombres;
                        document.getElementById('apellidos').value = estudiante.apellidos;
                        document.getElementById('cedula').value = estudiante.cedula;
                        document.getElementById('cedula').setAttribute('readonly', 'readonly');
                        document.getElementById('carrera').value = estudiante.carrera;
                        document.getElementById('email').value = estudiante.email;
                        document.getElementById('telefono').value = estudiante.telefono;
                        document.getElementById('semestre').value = estudiante.semestre;
                        document.getElementById('fecha_nacimiento').value = estudiante.fecha_nacimiento;
                        document.getElementById('direccion').value = estudiante.direccion;
                        document.getElementById('modalEstudianteLabel').textContent = '📝 Editar Estudiante';
                        establecerFechaNacimientoMaxima();
                        // Mostrar el modal correctamente
                        var modal = new bootstrap.Modal(document.getElementById('modalEstudiante'));
                        modal.show();
                    } else {
                        document.getElementById('msgEstudiante').innerHTML = '<div class="alert alert-danger">' + (data.error || 'No se pudo cargar el estudiante') + '</div>';
                    }
                })
                .catch(error => {
                    document.getElementById('msgEstudiante').innerHTML = '<div class="alert alert-danger">Error de conexión al cargar los datos: ' + error.message + '</div>';
                });
        }

        function eliminarEstudiante(id) {
            // Obtener información del estudiante desde la tabla
            const fila = document.querySelector(`button[onclick="eliminarEstudiante(${id})"]`).closest('tr');
            const celdas = fila.querySelectorAll('td');
            const nombres = celdas[1].textContent;
            const apellidos = celdas[2].textContent;
            const cedula = celdas[3].textContent;

            // Mostrar información en el modal
            document.getElementById('estudianteAEliminar').innerHTML = `
                <strong>${nombres} ${apellidos}</strong><br>
                <small class="text-muted">Cédula: ${cedula}</small>
            `;

            // Configurar el botón de confirmación
            document.getElementById('btnConfirmarEliminar').onclick = function() {
                ejecutarEliminacion(id);
            };

            // Mostrar modal de confirmación
            var modal = new bootstrap.Modal(document.getElementById('modalConfirmarEliminar'));
            modal.show();
        }

        function ejecutarEliminacion(id) {
            fetch('models/eliminar.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${id}`
                })
                .then(response => response.text())
                .then(text => {
                    const jsonStart = text.indexOf('{');
                    const cleanText = jsonStart !== -1 ? text.substring(jsonStart) : text;
                    try {
                        return JSON.parse(cleanText);
                    } catch (e) {
                        throw new Error('Respuesta inválida del servidor');
                    }
                })
                .then(data => {
                    // Cerrar modal de confirmación
                    var modal = bootstrap.Modal.getInstance(document.getElementById('modalConfirmarEliminar'));
                    if (modal) modal.hide();

                    if (data.success) {
                        cargarEstudiantes();
                        // Mostrar mensaje de éxito en un toast o alert temporal
                        mostrarNotificacion('Estudiante eliminado correctamente.', 'success');
                    } else {
                        mostrarNotificacion('Error: ' + (data.error || 'No se pudo eliminar el estudiante'), 'danger');
                    }
                })
                .catch(error => {
                    // Cerrar modal de confirmación
                    var modal = bootstrap.Modal.getInstance(document.getElementById('modalConfirmarEliminar'));
                    if (modal) modal.hide();
                    mostrarNotificacion('Error de conexión al eliminar: ' + error.message, 'danger');
                });
        }

        function mostrarNotificacion(mensaje, tipo) {
            // Crear notificación temporal tipo toast
            const notificacion = document.createElement('div');
            notificacion.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
            notificacion.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notificacion.innerHTML = `
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(notificacion);

            // Auto-eliminar después de 3 segundos
            setTimeout(() => {
                if (notificacion.parentNode) {
                    notificacion.remove();
                }
            }, 3000);
        }

        document.getElementById('formEstudiante').addEventListener('submit', function(e) {
            e.preventDefault();
            let formValido = true;
            if (!validarSemestre()) formValido = false;
            if (!validarEdad()) formValido = false;
            if (!this.checkValidity()) formValido = false;
            this.classList.add('was-validated');
            if (!formValido) return;
            const formData = new FormData(this);
            const estudianteId = document.getElementById('estudianteId').value;
            const url = estudianteId ? 'models/editar.php' : 'models/guardar.php';
            fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    const jsonStart = text.indexOf('{');
                    const cleanText = jsonStart !== -1 ? text.substring(jsonStart) : text;
                    try {
                        return JSON.parse(cleanText);
                    } catch (e) {
                        throw new Error('Respuesta inválida del servidor');
                    }
                })
                .then(data => {
                    if (data.success) {
                        document.getElementById('msgEstudiante').innerHTML = '<div class="alert alert-success">' + (data.message || 'Estudiante guardado correctamente.') + '</div>';
                        cargarEstudiantes();
                        // Resetear el título del modal para próxima vez
                        document.getElementById('modalEstudianteLabel').textContent = '📝 Agregar Nuevo Estudiante';
                        setTimeout(() => {
                            ocultarFormularioEstudiante();
                        }, 1200);
                    } else {
                        document.getElementById('msgEstudiante').innerHTML = '<div class="alert alert-danger">' + (data.error || 'Error al guardar estudiante.') + '</div>';
                    }
                })
                .catch(error => {
                    document.getElementById('msgEstudiante').innerHTML = '<div class="alert alert-danger">Error de conexión: ' + error.message + '</div>';
                });
        });
        document.getElementById('formSecretaria').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }
            const formData = new FormData(this);
            fetch('models/guardarSecretaria.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    const jsonStart = text.indexOf('{');
                    const cleanText = jsonStart !== -1 ? text.substring(jsonStart) : text;
                    try {
                        return JSON.parse(cleanText);
                    } catch (e) {
                        throw new Error('Respuesta inválida del servidor');
                    }
                })
                .then(data => {
                    if (data.success) {
                        document.getElementById('msgSecretaria').innerHTML = '<div class="alert alert-success">' + (data.message || 'Secretaria guardada correctamente.') + '</div>';
                        // Resetear el título del modal para próxima vez
                        document.getElementById('modalSecretariaLabel').textContent = '📝 Agregar Nueva Secretaria';
                        setTimeout(() => {
                            ocultarFormularioSecretaria();
                        }, 1200);
                    } else {
                        document.getElementById('msgSecretaria').innerHTML = '<div class="alert alert-danger">' + (data.error || 'Error al guardar secretaria.') + '</div>';
                    }
                })
                .catch(error => {
                    document.getElementById('msgSecretaria').innerHTML = '<div class="alert alert-danger">Error de conexión: ' + error.message + '</div>';
                });
        });
    <?php endif; ?>

    // Función para cargar reporte de estudiantes
    function cargarRerporteEstudiantes() {
        window.open('reports/reporteGeneral.php', '_blank');
    }

    // Buscar estudiantes por cédula en tiempo real
    document.getElementById('busquedaCedula').addEventListener('input', function() {
        const cedula = this.value.trim();
        if (cedula === '') {
            cargarEstudiantes(); // Si está vacío, carga todos
            return;
        }
        fetch('models/selectCed.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'cedula=' + encodeURIComponent(cedula)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarTablaEstudiantes(data.data);
                } else {
                    document.getElementById('tablaEstudiantes').innerHTML =
                        '<div class="alert alert-danger">Error al buscar: ' + (data.error || 'Error desconocido') + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('tablaEstudiantes').innerHTML =
                    '<div class="alert alert-danger">Error de conexión</div>';
            });
    });

    document.getElementById('btnReporteCedula').addEventListener('click', function() {
        const cedula = this.dataset.cedula;
        if (cedula) {
            window.open('reports/reporteCedula.php?cedula=' + encodeURIComponent(cedula), '_blank');
        }
    });
</script>