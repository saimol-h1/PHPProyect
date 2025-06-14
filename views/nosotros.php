<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>UTA Cuarto</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery - REQUERIDO para AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-4">Lista de Estudiantes</h2>

        <!-- Tabla -->
        <div class="table-responsive">
            <table id="dg" class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="text-center">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            Cargando datos...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            loadData();
        });

        function loadData() {
            $.ajax({
                url: 'models/select.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('Datos recibidos:', data); // Debug
                    var tbody = $('#dg tbody');
                    tbody.empty();

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(function(row) {
                            tbody.append(
                                '<tr>' +
                                '<td>' + row.cedula + '</td>' +
                                '<td>' + row.nombre + '</td>' +
                                '<td>' + row.apellido + '</td>' +
                                '<td>' + row.direccion + '</td>' +
                                '<td>' + row.telefono + '</td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        tbody.append('<tr><td colspan="5" class="text-center">No hay datos disponibles</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', xhr.responseText); // Debug
                    $('#dg tbody').html('<tr><td colspan="5" class="text-center text-danger">Error al cargar los datos: ' + error + '</td></tr>');
                }
            });
        }
    </script>
</body>

</html>