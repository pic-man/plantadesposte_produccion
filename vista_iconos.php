<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador de Iconos SVG</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    
    <style>
        .svg-container {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .svg-container svg {
            max-width: 100%;
            max-height: 100%;
        }
        table.dataTable {
            width: 100% !important;
        }
        .btn-simple {
            padding: 0;
            background: transparent;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container" style="padding: 20px;">
        <h2>Iconos SVG</h2>
        <table id="iconosTable" class="display">
            <thead>
                <tr>
                    <th>Nombre del archivo</th>
                    <th>Vista previa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#iconosTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "iconos.php",
                    "type": "GET"
                },
                "columns": [
                    { "data": 0 },
                    { "data": 1 },
                    { "data": 2 }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
                }
            });
        });

        function editarIcono(filename) {
            // Implementar lógica de edición
            console.log('Editar icono:', filename);
        }

        function eliminarIcono(filename) {
            if(confirm('¿Está seguro de eliminar este icono?')) {
                // Implementar lógica de eliminación
                console.log('Eliminar icono:', filename);
            }
        }
    </script>
</body>
</html> 