<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>UTA Cuarto</title>
</head>

<body>
    <h2>Manejo de Estudiantes</h2>

    <table id="dg" title="My Users" class="easyui-datagrid" style="width:700px;height:250px"
        url="models/select.php"
        toolbar="#toolbar" pagination="true"
        rownumbers="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <th field="cedula" width="50">Cédula</th>
                <th field="nombre" width="50">Nombre</th>
                <th field="apellido" width="50">Apellido</th>
                <th field="direccion" width="50">Dirección</th>
                <th field="telefono" width="50">Teléfono</th>
            </tr>
        </thead>
    </table>
    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">Nuevo usuario</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Editar usuario</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">Eliminar usuario</a>
    </div>

    <div id="dlg" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
            <h3>User Information</h3>

            <div style="margin-bottom:10px">
                <input name="cedula" class="easyui-textbox" required="true" label="Cédula:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="nombre" class="easyui-textbox" required="true" label="Nombre:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="apellido" class="easyui-textbox" required="true" label="Apellido:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="direccion" class="easyui-textbox" required="true" label="Dirección:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="telefono" class="easyui-textbox" required="true" label="Teléfono:" style="width:100%">
            </div>

        </form>
    </div>
    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
    </div>
    <script type="text/javascript">
        var url;

        function newUser() {
            $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'New User');
            $('#fm').form('clear');
            url = 'models/guardar.php';
        }

        function editUser() {
            var row = $('#dg').datagrid('getSelected');
            if (row) {
                $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Edit User');
                $('#fm').form('load', row);
                url = 'models/editar.php?cedula=' + row.cedula;
            }
        }

        function saveUser() {
            $('#fm').form('submit', {
                url: url,
                iframe: false,
                onSubmit: function() {
                    return $(this).form('validate');
                },
                success: function(result) {
                    try {
                        var result = JSON.parse(result);
                        if (result.errorMsg) {
                            $.messager.show({
                                title: 'Error',
                                msg: result.errorMsg
                            });
                        } else {
                            $('#dlg').dialog('close'); // close the dialog
                            $('#dg').datagrid('reload'); // reload the user data
                            $.messager.show({
                                title: 'Éxito',
                                msg: 'Usuario guardado exitosamente'
                            });
                        }
                    } catch (e) {
                        // Si no es JSON válido, asumir éxito si no hay error obvio
                        $('#dlg').dialog('close');
                        $('#dg').datagrid('reload');
                        $.messager.show({
                            title: 'Éxito',
                            msg: 'Usuario guardado exitosamente'
                        });
                    }
                },
                error: function() {
                    $.messager.show({
                        title: 'Error',
                        msg: 'Error al conectar con el servidor'
                    });
                }
            });
        }

        function destroyUser() {
            var row = $('#dg').datagrid('getSelected');
            if (row) {
                $.messager.confirm('Confirmar', '¿Está seguro de que desea eliminar este usuario?', function(r) {
                    if (r) {
                        $.get('models/eliminar.php', {
                            cedula: row.cedula
                        }, function(result) {
                            try {
                                // Intentar parsear como JSON
                                if (typeof result === 'string') {
                                    result = JSON.parse(result);
                                }
                                if (result.success) {
                                    $('#dg').datagrid('reload');
                                    $.messager.show({
                                        title: 'Éxito',
                                        msg: 'Usuario eliminado exitosamente'
                                    });
                                } else {
                                    $.messager.show({
                                        title: 'Error',
                                        msg: result.errorMsg || 'Error al eliminar usuario'
                                    });
                                }
                            } catch (e) {
                                $('#dg').datagrid('reload');
                                $.messager.show({
                                    title: 'Éxito',
                                    msg: 'Usuario eliminado exitosamente'
                                });
                            }
                        }).fail(function() {
                            $.messager.show({
                                title: 'Error',
                                msg: 'Error al conectar con el servidor'
                            });
                        });
                    }
                });
            }
        }
    </script>
</body>

</html>