<?php
// Incluir sistema de autenticación
require_once 'config/auth.php';

// Verificar que el usuario esté logueado
requireLogin();

$usuario_info = getUsuarioInfo();
$es_admin = isAdmin();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios - Gestión de Estudiantes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container-fluid mt-3">
        <!-- Información del usuario logueado -->
        <?php mostrarUsuarioLogueado(); ?>

        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="fas fa-users"></i> Gestión de Estudiantes</h4>
                    </div>
                    <div class="card-body">

                        <?php if ($es_admin): ?>
                            <!-- Botones de administrador -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                                    <i class="fas fa-plus"></i> Agregar Estudiante
                                </button>
                                <button type="button" class="btn btn-info" onclick="cargarEstudiantes()">
                                    <i class="fas fa-refresh"></i> Actualizar Lista
                                </button>
                            </div>
                        <?php else: ?>
                            <!-- Botones de secretaria (solo lectura) -->
                            <div class="mb-3">
                                <button type="button" class="btn btn-info" onclick="cargarEstudiantes()">
                                    <i class="fas fa-refresh"></i> Actualizar Lista
                                </button>
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle"></i> Modo solo lectura - Contacte al administrador para realizar cambios
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Tabla de estudiantes -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaEstudiantes">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Cédula</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <?php if ($es_admin): ?>
                                            <th>Acciones</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="<?php echo $es_admin ? '6' : '5'; ?>" class="text-center">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                            Cargando estudiantes...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($es_admin): ?>
        <!-- Modal para agregar estudiante -->
        <div class="modal fade" id="modalAgregar" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Nuevo Estudiante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="formAgregar">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="cedula" class="form-label">Cédula</label>
                                <input type="text" class="form-control" id="cedula" name="cedula" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para editar estudiante -->
        <div class="modal fade" id="modalEditar" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Estudiante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="formEditar">
                        <div class="modal-body">
                            <input type="hidden" id="edit_cedula_original" name="cedula_original">
                            <div class="mb-3">
                                <label for="edit_cedula" class="form-label">Cédula</label>
                                <input type="text" class="form-control" id="edit_cedula" name="cedula" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="edit_apellido" name="apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_direccion" class="form-label">Dirección</label>
                                <textarea class="form-control" id="edit_direccion" name="direccion" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit_telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="edit_telefono" name="telefono">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const esAdmin = <?php echo $es_admin ? 'true' : 'false'; ?>;

        $(document).ready(function() {
            cargarEstudiantes();

            <?php if ($es_admin): ?>
                // Form para agregar
                $('#formAgregar').on('submit', function(e) {
                    e.preventDefault();
                    agregarEstudiante();
                });

                // Form para editar
                $('#formEditar').on('submit', function(e) {
                    e.preventDefault();
                    editarEstudiante();
                });
            <?php endif; ?>
        });

        function cargarEstudiantes() {
            $.ajax({
                url: 'models/select.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let tbody = $('#tablaEstudiantes tbody');
                    tbody.empty();

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(function(estudiante) {
                            let acciones = '';
                            if (esAdmin) {
                                acciones = `
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="editarModal('${estudiante.cedula}', '${estudiante.nombre}', '${estudiante.apellido}', '${estudiante.direccion}', '${estudiante.telefono}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="eliminarEstudiante('${estudiante.cedula}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                `;
                            }

                            tbody.append(`
                                <tr>
                                    <td>${estudiante.cedula}</td>
                                    <td>${estudiante.nombre}</td>
                                    <td>${estudiante.apellido}</td>
                                    <td>${estudiante.direccion || ''}</td>
                                    <td>${estudiante.telefono || ''}</td>
                                    ${acciones}
                                </tr>
                            `);
                        });
                    } else {
                        let colspan = esAdmin ? '6' : '5';
                        tbody.append(`<tr><td colspan="${colspan}" class="text-center">No hay estudiantes registrados</td></tr>`);
                    }
                },
                error: function() {
                    let colspan = esAdmin ? '6' : '5';
                    $('#tablaEstudiantes tbody').html(`<tr><td colspan="${colspan}" class="text-center text-danger">Error al cargar los datos</td></tr>`);
                }
            });
        }

        <?php if ($es_admin): ?>
            function agregarEstudiante() {
                $.ajax({
                    url: 'models/guardar.php',
                    type: 'POST',
                    data: $('#formAgregar').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            $('#modalAgregar').modal('hide');
                            $('#formAgregar')[0].reset();
                            cargarEstudiantes();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX:', xhr.responseText);
                        alert('Error al agregar estudiante: ' + error);
                    }
                });
            }

            function editarModal(cedula, nombre, apellido, direccion, telefono) {
                $('#edit_cedula_original').val(cedula);
                $('#edit_cedula').val(cedula);
                $('#edit_nombre').val(nombre);
                $('#edit_apellido').val(apellido);
                $('#edit_direccion').val(direccion);
                $('#edit_telefono').val(telefono);
                $('#modalEditar').modal('show');
            }

            function editarEstudiante() {
                $.ajax({
                    url: 'models/editar.php',
                    type: 'POST',
                    data: $('#formEditar').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            $('#modalEditar').modal('hide');
                            cargarEstudiantes();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX:', xhr.responseText);
                        alert('Error al actualizar estudiante: ' + error);
                    }
                });
            }

            function eliminarEstudiante(cedula) {
                if (confirm('¿Está seguro de eliminar este estudiante?')) {
                    $.ajax({
                        url: 'models/eliminar.php',
                        type: 'GET',
                        data: {
                            cedula: cedula
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                cargarEstudiantes();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error AJAX:', xhr.responseText);
                            alert('Error al eliminar estudiante: ' + error);
                        }
                    });
                }
            }
        <?php endif; ?>
    </script>
</body>

</html>