<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cotización</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .container {
            padding: 25px;
        }

        .brand {
            text-align: center;
            margin-bottom: 20px;
        }

        .brand .v {
            font-size: 90px;
            font-weight: bold;
            line-height: 80px;
            letter-spacing: -10px;
        }

        .brand .name {
            font-size: 22px;
            letter-spacing: 4px;
            font-weight: bold;
        }

        .brand .slogan {
            font-size: 12px;
            margin-top: 5px;
            font-style: italic;
        }

        hr {
            border: none;
            border-top: 2px solid #000;
            margin: 15px 0;
        }

        .info {
            margin-bottom: 20px;
        }

        .info strong {
            display: inline-block;
            width: 90px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .productos th,
        .productos td {
            border: 1px solid #ddd;
            padding: 7px;
        }

        .productos th {
            background: #f2f2f2;
        }

        .total {
            margin-top: 15px;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            border-top: 2px solid #000;
            padding-top: 10px;
            font-size: 11px;
        }

        .box {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">


        <div class="brand">
            <div class="v">V</div>
            <div class="name">VERZASCA</div>
            @if($empresa->slogan)
                <div class="slogan">{{ $empresa->slogan }}</div>
            @endif
        </div>

        <hr>
        <div class="info box">
            <p><strong>Empresa:</strong> {{ $empresa->nombre }}</p>
            <p><strong>Contacto:</strong> {{ $empresa->nroContacto }}</p>

            @if($empresa->facebook)
                <p><strong>Facebook:</strong> {{ $empresa->facebook }}</p>
            @endif
            @if($empresa->instagram)
                <p><strong>Instagram:</strong> {{ $empresa->instagram }}</p>
            @endif
            @if($empresa->tiktok)
                <p><strong>TikTok:</strong> {{ $empresa->tiktok }}</p>
            @endif

            @if($empresa->sucursales->count())
                <div style="margin-top:10px;">
                    <strong>Sucursales:</strong>
                    <ul style="padding-left:15px; margin:5px 0;">
                        @foreach($empresa->sucursales as $suc)
                            <li>
                                {{ $suc->nombre }} - {{ $suc->direccion }} - Tel: {{ $suc->telefono }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>


        <table class="productos">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Paquete</th>
                    <th>Precio Unit.</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carrito as $item)
                    @php
                        $m = $item['modelo'];
                        $precio = $m->precioReferencia ?? 0;
                        $paq = $m->paquete ?? 1;
                        $cant = $item['cantidad'];
                        $total = $precio * $paq * $cant;
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $m->descripcion }}</strong><br>
                            <small>
                                Tipo Contenido: {{ $m->tipoContenido ?? '-' }}<br>
                                Tipo Producto: {{ $m->tipoProducto ?? '-' }}<br>
                                Capacidad: {{ $m->capacidad ?? '-' }} {{ $m->unidad ?? '' }}<br>
                                Observaciones: {{ $m->observaciones ?? '-' }}
                            </small>
                        </td>
                        <td>{{ $cant }} x {{ $paq }}</td>
                        <td>Bs {{ number_format($precio, 2) }}</td>
                        <td>Bs {{ number_format($total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <p>Subtotal: Bs {{ number_format($cotizacion['subtotal'], 2) }}</p>
            <p>Total: Bs {{ number_format($cotizacion['total'], 2) }}</p>
        </div>

        @if($empresa->mision || $empresa->vision)
            <div class="footer">
                @if($empresa->mision)
                    <p><strong>Misión:</strong> {{ $empresa->mision }}</p>
                @endif
                @if($empresa->vision)
                    <p><strong>Visión:</strong> {{ $empresa->vision }}</p>
                @endif
            </div>
        @endif

    </div>
</body>

</html>