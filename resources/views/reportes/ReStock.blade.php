<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Reporte de Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }
        h1, h2, h3 {
            text-align: center;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 22px;
            margin-bottom: 5px;
        }
        h3 {
            margin-top: 30px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
            color: #555;
        }
        p.fecha {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #888;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }
    </style>
</head>
<body>
    <h1>Reporte General de Stock</h1>
    <p class="fecha"><strong>Fecha de impresión:</strong> {{ $fecha }}</p>

    <h3>Listado de Stocks</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad de Existencias</th>
                <th>Precio (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stocks as $stock)
                <tr>
                    <td>{{ $stock->producto?->nombre ?? 'Sin producto' }}</td>
                    <td>{{ $stock->existencias->count() }}</td>
                    <td>
                        @if(isset($stock->producto->precio))
                            {{ number_format($stock->producto->precio, 2) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
