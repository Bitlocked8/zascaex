<div class="p-2 mt-20 flex justify-center bg-transparent">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto mb-4">
            Pedidos para entregar
        </h3>

        <div class="flex items-center gap-2 mb-4 flex-wrap">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por código, cliente o solicitud..."
                class="input-minimal w-full sm:w-auto flex-1" />
            <button
                wire:click="$toggle('soloHoy')"
                class="btn-cyan
               {{ $soloHoy ? 'bg-teal-600 text-white' : 'bg-cyan-600 text-white' }}">
                {{ $soloHoy ? ' todos' : ' hoy' }}
            </button>
        </div>

        <div class="overflow-auto max-h-[400px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Código</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Cliente</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Items para entrega</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Fecha del pedido</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Estado</th>
                        <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pedidos as $pedido)
                    <tr class="hover:bg-teal-50 align-top">
                        <td class="px-4 py-2 font-medium">{{ $pedido->codigo }}</td>
                        <td class="px-4 py-2">{{ $pedido->cliente->nombre ?? 'Sin cliente' }}</td>

                        <td class="px-4 py-2 align-top">
                            <h4 class="font-bold text-sm text-gray-700 mb-1">Pedido</h4>
                            @foreach($pedido->detalles as $detallePedido)
                            @php
                            $item = $detallePedido->existencia->existenciable ?? null;
                            $tipo = $item instanceof \App\Models\Producto ? 'producto' : 'otro';
                            $descripcion = $item?->descripcion ?? 'Sin descripción';
                            $cantidad = $detallePedido->cantidad;
                            $cantidadMostrar = ($cantidad == floor($cantidad))
                            ? intval($cantidad)
                            : $cantidad;
                            @endphp
                            <div class="flex justify-between text-xs border-b py-1">
                                <div>
                                    <span class="font-semibold">{{ $descripcion }} ({{ $tipo }})</span>
                                    @if(!empty($detallePedido->tipo_contenido))
                                    <span class="block text-indigo-600 text-[10px]">
                                        Contenido: {{ $detallePedido->tipo_contenido }}
                                    </span>
                                    @endif
                                </div>
                                <div class="font-semibold text-teal-600">
                                    {{ $cantidadMostrar }} unidades
                                </div>
                            </div>
                            @endforeach



                            @if($pedido->solicitudPedido)
                            <h4 class="font-bold text-sm text-gray-700 mt-3 mb-1">
                                Solicitud: {{ $pedido->solicitudPedido->codigo }}
                            </h4>

                            @foreach($pedido->solicitudPedido->detalles as $detalleSolicitud)
                            @php
                            $item = $detalleSolicitud->producto ?? $detalleSolicitud->otro;
                            $tipo = $detalleSolicitud->producto ? 'producto' : 'otro';
                            $cantidadPaquetes = $detalleSolicitud->cantidad ?? 0;
                            $unidadesPorPaquete = $item?->paquete ?? 1;
                            $cantidadUnidades = $cantidadPaquetes * $unidadesPorPaquete;
                            @endphp

                            <div class="flex flex-col text-xs border-b py-1 mb-1">
                                <div class="flex justify-between">
                                    <span class="font-semibold">{{ $item?->descripcion ?? 'Sin descripción' }} ({{ $tipo }})</span>
                                    <span class="font-semibold text-teal-600">
                                        {{ $cantidadPaquetes }} Paquete(s) / {{ $cantidadUnidades }} Unidad(es)
                                    </span>
                                </div>

                                @if($detalleSolicitud->producto)
                                <div class="text-gray-500 text-[10px] mt-0.5">
                                    <div>Producto: {{ $detalleSolicitud->producto?->descripcion ?? '-' }}</div>
                                    <div>Tapa: {{ $detalleSolicitud->tapa?->descripcion ?? '-' }}</div>
                                    <div>Etiqueta: {{ $detalleSolicitud->etiqueta?->descripcion ?? '-' }}</div>
                                    <div>{{ $unidadesPorPaquete }} unidad/paquete</div>
                                </div>
                                @endif

                                @if(!empty($detalleSolicitud->tipo_contenido))
                                <span class="block text-indigo-600 text-[10px] mt-0.5">
                                    Contenido: {{ $detalleSolicitud->tipo_contenido }}
                                </span>
                                @endif
                            </div>
                            @endforeach
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $pedido->fecha_pedido ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') : '—' }}</td>
                        <td class="px-4 py-2">
                            @php
                            $estados = [
                            0 => ['Pendiente', 'text-yellow-600'],
                            1 => ['En proceso de pago', 'text-blue-600'],
                            2 => ['Pedido Entregado', 'text-green-600'],
                            ];
                            [$texto, $color] = $estados[$pedido->estado_pedido] ?? ['Desconocido', 'text-gray-600'];
                            @endphp
                            <span class="{{ $color }} font-semibold">{{ $texto }}</span>
                        </td>

                        <td class="px-4 py-2 flex justify-center gap-1">
                            <button wire:click="cambiarEstadoPedido({{ $pedido->id }}, 1)" class="btn-cyan text-sm">Aun no pagado</button>
                            <button wire:click="cambiarEstadoPedido({{ $pedido->id }}, 2)" class="btn-cyan text-sm">Entregar</button>
                            <button wire:click="abrirModalPagoPedido({{ $pedido->id }})" class="btn-cyan text-sm">Pagos</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-600">No hay pedidos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
    @if($modalPagoPedido)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content">

                @foreach($pagos as $index => $pago)
                <div class="border p-4 rounded mb-4 flex flex-col gap-3">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold">Pago {{ $index + 1 }}</span>
                        <button type="button" wire:click="eliminarPago({{ $index }})" class="text-red-500 font-bold">Eliminar</button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                        <div class="sm:col-span-2">
                            <label class="font-semibold text-sm block mb-2 text-center">
                                Método de pago
                            </label>

                            <div class="flex flex-wrap justify-center gap-2">
                                <button
                                    type="button"
                                    wire:click="$set('pagos.{{ $index }}.metodo', 0)"
                                    class="px-4 py-2 rounded-full border text-sm
            {{ $pagos[$index]['metodo'] == 0 ? 'bg-teal-600 text-white' : 'bg-white text-gray-700' }}">
                                    QR
                                </button>

                                <button
                                    type="button"
                                    wire:click="$set('pagos.{{ $index }}.metodo', 1)"
                                    class="px-4 py-2 rounded-full border text-sm
            {{ $pagos[$index]['metodo'] == 1 ? 'bg-teal-600 text-white' : 'bg-white text-gray-700' }}">
                                    Efectivo
                                </button>

                                <button
                                    type="button"
                                    wire:click="$set('pagos.{{ $index }}.metodo', 2)"
                                    class="px-4 py-2 rounded-full border text-sm
            {{ $pagos[$index]['metodo'] == 2 ? 'bg-teal-600 text-white' : 'bg-white text-gray-700' }}">
                                    Contrato/Otro
                                </button>
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="font-semibold text-sm block mb-2 text-center">
                                Estado del pago
                            </label>

                            <div class="flex justify-center gap-2">
                                <button
                                    type="button"
                                    wire:click="$set('pagos.{{ $index }}.estado', 0)"
                                    class="px-5 py-2 rounded-full text-sm border
            {{ empty($pagos[$index]['estado']) ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700' }}">
                                    Sin pagar
                                </button>

                                <button
                                    type="button"
                                    wire:click="$set('pagos.{{ $index }}.estado', 1)"
                                    class="px-5 py-2 rounded-full text-sm border
            {{ !empty($pagos[$index]['estado']) ? 'bg-green-600 text-white' : 'bg-white text-gray-700' }}">
                                    Pagado
                                </button>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="font-semibold text-sm block mb-2 text-center">
                                Sucursal de pago
                            </label>

                            <div class="flex flex-wrap justify-center gap-2 mb-2">
                                <button
                                    type="button"
                                    wire:click="$set('pagos.{{ $index }}.sucursal_pago_id', null)"
                                    class="px-4 py-2 rounded-full border text-sm
                {{ empty($pagos[$index]['sucursal_pago_id']) ? 'bg-gray-500 text-white' : 'bg-white text-gray-700' }}">
                                    Ninguna
                                </button>

                                @foreach($sucursalesPago as $sucursal)
                                <button
                                    type="button"
                                    wire:click="$set('pagos.{{ $index }}.sucursal_pago_id', {{ $sucursal->id }})"
                                    class="px-4 py-2 rounded-full border text-sm
                    {{ ($pagos[$index]['sucursal_pago_id'] ?? null) == $sucursal->id
                        ? 'bg-indigo-600 text-white'
                        : 'bg-white text-gray-700' }}">
                                    {{ $sucursal->nombre }}
                                </button>
                                @endforeach
                            </div>
                            @php
                            $sucursalSeleccionada = $sucursalesPago->firstWhere('id', $pagos[$index]['sucursal_pago_id'] ?? null);
                            $qrUrl = $sucursalSeleccionada && $sucursalSeleccionada->imagen_qr
                            ? Storage::url($sucursalSeleccionada->imagen_qr)
                            : null;
                            @endphp

                            @if($qrUrl)
                            <div class="flex justify-center mt-2">
                                <img src="{{ $qrUrl }}" alt="QR Sucursal" class="h-50 w-50 rounded shadow">
                            </div>
                            @endif
                        </div>


                        <div>
                            <label>Monto</label>
                            <input type="number" wire:model="pagos.{{ $index }}.monto" class="input-minimal w-full">
                        </div>


                        <div>
                            <label>Cdigo Recibo/Comprobante</label>
                            <input type="text" wire:model="pagos.{{ $index }}.referencia" class="input-minimal w-full">
                        </div>

                        <div>
                            <label>Código factura</label>
                            <input type="text" wire:model="pagos.{{ $index }}.codigo_factura" class="input-minimal w-full">
                        </div>

                        <div>
                            <label>Observaciones</label>
                            <input type="text" wire:model="pagos.{{ $index }}.observaciones" class="input-minimal w-full">
                        </div>

                        <div>
                            <label>Fecha</label>
                            <input type="datetime-local" wire:model="pagos.{{ $index }}.fecha" class="input-minimal w-full" readonly>
                        </div>


                        <div class="sm:col-span-2">
                            <label class="font-semibold text-sm">Archivo Factura (Opcional)</label>
                            <input type="file" wire:model="pagos.{{ $index }}.archivoFactura" class="input-minimal">

                            @php
                            $archivo = $pagos[$index]['archivoFactura'] ?? null;
                            $archivoUrl = null;
                            $esImagen = false;

                            if ($archivo) {
                            if (is_object($archivo)) {
                            $mime = $archivo->getClientMimeType();
                            $esImagen = str_starts_with($mime, 'image');
                            if ($esImagen) $archivoUrl = $archivo->temporaryUrl();
                            } else {
                            $ext = pathinfo($archivo, PATHINFO_EXTENSION);
                            $esImagen = in_array(strtolower($ext), ['jpg','jpeg','png','gif','bmp','svg']);
                            $archivoUrl = Storage::url($archivo);
                            }
                            }
                            @endphp

                            @if($archivo)
                            @if($esImagen)
                            <img src="{{ $archivoUrl }}" class="mt-2 max-w-xs rounded shadow">
                            @else
                            <a href="{{ $archivoUrl }}" target="_blank" class="text-blue-600 underline mt-2 block">
                                Descargar archivo
                            </a>
                            @endif
                            @endif
                        </div>


                        <div class="sm:col-span-2">
                            <label>Comprobante (imagen)</label>
                            <input type="file" wire:model="pagos.{{ $index }}.archivoComprobante" class="input-minimal w-full">

                            @if(isset($pagos[$index]['archivoComprobante']))
                            @php
                            $archivo = $pagos[$index]['archivoComprobante'];
                            $url = is_string($archivo) ? Storage::url($archivo) : $archivo->temporaryUrl();
                            @endphp
                            <img src="{{ $url }}" alt="Vista previa" class="mt-2 max-w-xs rounded shadow">
                            @endif
                        </div>

                    </div>
                </div>
                @endforeach
                <div class="modal-footer">
                    <button type="button" wire:click="agregarPago" class="btn-cyan">Agregar pago</button>

                    <button type="button" wire:click="guardarPagos" class="btn-cyan">Guardar pagos</button>
                    <button type="button" wire:click="cerrarModalPagoPedido" class="btn-cyan">Cerrar</button>

                </div>
            </div>
        </div>
    </div>
    @endif


</div>