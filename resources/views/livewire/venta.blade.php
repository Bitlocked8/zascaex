<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Buscar + Crear Venta -->
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <!-- Input de búsqueda -->
            <input type="text" wire:model.live="searchCliente"
                placeholder="Buscar por nombre de cliente..."
                class="flex-1 border rounded px-3 py-2" />

            <!-- Botón Crear Venta -->
            <button wire:click="abrirModal('create')"
                class="bg-cyan-500 hover:bg-cyan-600 rounded-xl px-4 py-2 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9" />
                    <line x1="12" y1="9" x2="12" y2="15" />
                    <line x1="9" y1="12" x2="15" y2="12" />
                </svg>
            </button>
        </div>


        <div class="flex flex-col md:flex-row justify-center gap-2 mb-4 col-span-full">
            <div class="flex flex-col sm:flex-row flex-wrap gap-2 items-center justify-center">

                <!-- Filtro Estado Pedido -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-medium">Estado Pedido:</span>
                    <button wire:click="filtrarEstadoPedido(null)"
                        class="px-2 py-1 rounded {{ $filtroEstadoPedido === null ? 'bg-cyan-600 text-white' : 'bg-gray-200' }}">Todos</button>
                    <button wire:click="filtrarEstadoPedido(1)"
                        class="px-2 py-1 rounded {{ $filtroEstadoPedido === 1 ? 'bg-cyan-600 text-white' : 'bg-gray-200' }}">Pedido</button>
                    <button wire:click="filtrarEstadoPedido(0)"
                        class="px-2 py-1 rounded {{ $filtroEstadoPedido === 0 ? 'bg-cyan-600 text-white' : 'bg-gray-200' }}">Cancelado</button>
                    <button wire:click="filtrarEstadoPedido(2)"
                        class="px-2 py-1 rounded {{ $filtroEstadoPedido === 2 ? 'bg-cyan-600 text-white' : 'bg-gray-200' }}">Entregado</button>
                </div>

                <!-- Filtro Estado Pago -->
                <div class="flex flex-wrap items-center gap-2 mt-2 sm:mt-0 sm:ml-4">
                    <span class="font-medium">Estado Pago:</span>
                    <button wire:click="filtrarEstadoPago(null)"
                        class="px-2 py-1 rounded {{ $filtroEstadoPago === null ? 'bg-green-600 text-white' : 'bg-gray-200' }}">Todos</button>
                    <button wire:click="filtrarEstadoPago(1)"
                        class="px-2 py-1 rounded {{ $filtroEstadoPago === 1 ? 'bg-green-600 text-white' : 'bg-gray-200' }}">Completo</button>
                    <button wire:click="filtrarEstadoPago(0)"
                        class="px-2 py-1 rounded {{ $filtroEstadoPago === 0 ? 'bg-green-600 text-white' : 'bg-gray-200' }}">Pendiente</button>
                </div>
            </div>
        </div>
        @foreach ($ventas as $venta)
        <div class="bg-white shadow rounded-lg p-4 flex flex-col justify-between">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-cyan-950">Venta #{{ $venta->id }}</h3>

                <p class="text-cyan-950"><strong>Cliente:</strong> {{ $venta->cliente->nombre ?? 'N/A' }}</p>
                <p class="text-cyan-950"><strong>Personal:</strong> {{ $venta->personal->nombres ?? 'N/A' }}</p>
                <p class="text-cyan-950">
                    <strong>Personal Entrega:</strong> {{ $venta->personalEntrega->nombres ?? 'N/A' }}
                </p>

                <p class="text-cyan-950"><strong>Sucursal:</strong> {{ $venta->sucursal->nombre ?? 'N/A' }}</p>

                <p class="text-cyan-950">
                    <strong>Estado Pedido:</strong>
                    @if($venta->estadoPedido === 0) Cancelado
                    @elseif($venta->estadoPedido === 1) Pedido
                    @else Entregado
                    @endif
                </p>

                <p class="text-cyan-950">
                    <strong>Estado Pago:</strong> {{ $venta->estadoPago === 1 ? 'Completo' : 'Pendiente' }}
                </p>

                <p class="text-cyan-950"><strong>Fecha Pedido:</strong> {{ $venta->fechaPedido ?? 'N/A' }}</p>
                <p class="text-cyan-950"><strong>Fecha Entrega:</strong> {{ $venta->fechaEntrega ?? 'N/A' }}</p>
            </div>

            <div class="flex justify-center gap-4 mt-4">
                <!-- Botón Editar -->
                <button wire:click="editarVenta({{ $venta->id }})"
                    class="bg-cyan-500 hover:bg-cyan-600 rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                    </svg>
                </button>

                <!-- Botón Ver detalle -->
                <button wire:click="verDetalle({{ $venta->id }})"
                    class="bg-stone-500 hover:bg-stone-600 rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9h.01" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 12h1v4h1" />
                    </svg>
                </button>
            </div>
        </div>
        @endforeach


    </div>
    @if($modal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white text-cyan-950 rounded-lg shadow-lg w-full max-w-3xl p-6 overflow-auto max-h-[90vh]">
            <h2 class="text-xl font-semibold mb-4">{{ $accion === 'create' ? 'Registrar Venta' : 'Editar Venta' }}</h2>

            <!-- Cliente -->
            <div class="mb-4">
                <label class="block font-medium mb-1">Cliente: <span class="text-red-500">*</span></label>
                <select wire:model="cliente_id" class="w-full border rounded p-2 @error('cliente_id') border-red-500 @enderror"
                    @if($accion==='edit' ) disabled @endif>
                    <option value="">Seleccione Cliente</option>
                    @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                    @endforeach
                </select>
                @error('cliente_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Personal y Sucursal -->
            <div class="mb-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-medium mb-1">Personal: <span class="text-red-500">*</span></label>
                    <select wire:model="personal_id" class="w-full border rounded p-2 @error('personal_id') border-red-500 @enderror"
                        @if($accion==='edit' ) disabled @endif>
                        <option value="">Seleccione Personal</option>
                        @foreach($personales as $personal)
                        <option value="{{ $personal->id }}">{{ $personal->nombres }}</option>
                        @endforeach
                    </select>
                    @error('personal_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-medium mb-1">Personal Entrega:</label>
                    <select wire:model="personalEntrega_id" class="w-full border rounded p-2 @error('personalEntrega_id') border-red-500 @enderror">
                        <option value="">Seleccione Personal Entrega</option>
                        @foreach($personales as $personal)
                        <option value="{{ $personal->id }}">{{ $personal->nombres }}</option>
                        @endforeach
                    </select>
                    @error('personalEntrega_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-medium mb-1">Sucursal: <span class="text-red-500">*</span></label>
                    <select wire:model="sucursal_id" wire:change="cargarProductos"
                        class="w-full border rounded p-2 @error('sucursal_id') border-red-500 @enderror"
                        @if($accion==='edit' ) disabled @endif>
                        <option value="">Seleccione Sucursal</option>
                        @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                    @error('sucursal_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Fechas -->
            <div class="mb-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-medium mb-1">Fecha Pedido: <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="fechaPedido" class="w-full border rounded p-2"
                        @if($accion==='edit' ) disabled @endif>
                </div>
                <div>
                    <label class="block font-medium mb-1">Fecha Entrega:</label>
                    <input type="date" wire:model="fechaEntrega" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block font-medium mb-1">Fecha Máxima:</label>
                    <input type="date" wire:model="fechaMaxima" class="w-full border rounded p-2">
                </div>
            </div>

            <!-- Estado Pedido y Pago -->
            <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-1">Estado Pedido:</label>
                    <select wire:model="estadoPedido" class="w-full border rounded p-2">
                        <option value="1">Pedido</option>
                        <option value="0">Cancelado</option>
                        <option value="2">Entregado</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium mb-1">Estado Pago:</label>
                    <select wire:model="estadoPago" class="w-full border rounded p-2">
                        <option value="1">Completo</option>
                        <option value="0">Pendiente</option>
                    </select>
                </div>
            </div>

            @if($accion === 'create')
            <div class="mb-4">
                <h3 class="font-semibold mb-2">Productos disponibles en la sucursal</h3>
                <ul class="max-h-64 overflow-y-auto border rounded p-2 space-y-2">
                    @forelse($productos as $index => $producto)
                    <li class="flex justify-between items-center p-2 border-b last:border-b-0 gap-2">
                        <div class="flex flex-col">
                            <span class="font-medium">{{ $producto['nombre'] }}</span>
                            <span class="text-sm text-gray-600">Precio: ${{ number_format($producto['precioReferencia'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($producto['cantidad'] > 0)
                            <input type="number"
                                min="1"
                                max="{{ $producto['cantidad'] }}"
                                wire:model.defer="productosSeleccionados.{{ $index }}.cantidad"
                                class="w-16 border rounded p-1"
                                placeholder="Cantidad">

                            <span>Disponibles: {{ $producto['cantidad'] }}</span>
                            <button type="button"
                                wire:click="agregarProducto({{ $index }})"
                                class="bg-green-500 text-white px-2 py-1 rounded">
                                + Añadir
                            </button>
                            @else
                            <span class="text-red-500 font-semibold">Sin existencias</span>
                            @endif
                        </div>
                    </li>

                    @empty
                    <li class="text-gray-500 text-center">No hay productos disponibles</li>
                    @endforelse
                </ul>
            </div>
            @endif


            <!-- Productos añadidos -->
            @if(!empty($productosAgregados))
            <div class="mb-4 mt-4">
                <h3 class="font-semibold mb-2">Productos añadidos a la venta</h3>
                <table class="w-full border rounded text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1 text-left">Producto</th>
                            <th class="border px-2 py-1 text-center">Cantidad</th>
                            <th class="border px-2 py-1 text-right">Precio unitario</th>
                            <th class="border px-2 py-1 text-right">Subtotal</th>
                            @if($accion === 'create')
                            <th class="border px-2 py-1 text-center">Acción</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($productosAgregados as $index => $prod)
                        @php
                        $precio = $prod['precio'] ?? 0;
                        $subtotal = $precio * $prod['cantidad'];
                        $total += $subtotal;
                        @endphp
                        <tr>
                            <td class="border px-2 py-1">{{ $prod['nombre'] }}</td>
                            <td class="border px-2 py-1 text-center">{{ $prod['cantidad'] }}</td>
                            <td class="border px-2 py-1 text-right">${{ number_format($precio, 2) }}</td>
                            <td class="border px-2 py-1 text-right">${{ number_format($subtotal, 2) }}</td>
                            @if($accion === 'create')
                            <td class="border px-2 py-1 text-center">
                                <button type="button"
                                    wire:click="quitarProducto({{ $index }})"
                                    class="bg-red-500 text-white px-2 py-1 rounded">
                                    Eliminar
                                </button>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 font-semibold">
                            <td colspan="3" class="border px-2 py-1 text-right">Total</td>
                            <td class="border px-2 py-1 text-right">${{ number_format($total, 2) }}</td>
                            @if($accion === 'create') <td></td> @endif
                        </tr>
                    </tfoot>
                </table>

            </div>
            @endif

            <!-- Botones -->
            <div class="flex justify-end gap-4 mt-4 flex-wrap">
                <button type="button" wire:click="cerrarModal" class="bg-gray-300 hover:bg-gray-400 rounded-xl w-12 h-12 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <button type="button" wire:click="guardarVenta" class="bg-green-500 hover:bg-green-600 rounded-xl w-12 h-12 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif


    @if($detalleModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white text-cyan-950 rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
            <h2 class="text-xl font-semibold mb-4">Detalle Venta #{{ $ventaSeleccionada->id }}</h2>

            <p><strong>Cliente:</strong> {{ $ventaSeleccionada->cliente->nombre ?? 'N/A' }}</p>
            <p><strong>Personal:</strong> {{ $ventaSeleccionada->personal->nombres ?? 'N/A' }}</p>
            <p><strong>Personal Entrega:</strong> {{ $ventaSeleccionada->personalEntrega->nombres ?? 'N/A' }}</p>
            <p><strong>Sucursal:</strong> {{ $ventaSeleccionada->sucursal->nombre ?? 'N/A' }}</p>
            <p><strong>Estado Pedido:</strong>
                @if($ventaSeleccionada->estadoPedido === 0) Cancelado
                @elseif($ventaSeleccionada->estadoPedido === 1) Pedido
                @else Entregado
                @endif
            </p>
            <p><strong>Estado Pago:</strong> {{ $ventaSeleccionada->estadoPago === 1 ? 'Completo' : 'Pendiente' }}</p>
            <p><strong>Fecha Pedido:</strong> {{ $ventaSeleccionada->fechaPedido ?? 'N/A' }}</p>
            <p><strong>Fecha Entrega:</strong> {{ $ventaSeleccionada->fechaEntrega ?? 'N/A' }}</p>
            <p><strong>Fecha Máxima (crédito):</strong> {{ $ventaSeleccionada->fechaMaxima ?? 'N/A' }}</p>

            <!-- 🔹 Productos de la venta -->
            <div class="mt-4">
                <h3 class="font-semibold mb-2">Productos en esta venta</h3>
                <table class="w-full border rounded text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1 text-left">Producto</th>
                            <th class="border px-2 py-1 text-center">Cantidad</th>
                            <th class="border px-2 py-1 text-right">Precio unitario</th>
                            <th class="border px-2 py-1 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($ventaSeleccionada->itemventas as $item)
                        @php
                        $precio = $item->precio ?? 0;
                        $subtotal = $precio * $item->cantidad;
                        $total += $subtotal;
                        @endphp
                        <tr>
                            <td class="border px-2 py-1">{{ $item->existencia->existenciable->nombre ?? 'N/A' }}</td>
                            <td class="border px-2 py-1 text-center">{{ $item->cantidad }}</td>
                            <td class="border px-2 py-1 text-right">${{ number_format($precio, 2) }}</td>
                            <td class="border px-2 py-1 text-right">${{ number_format($subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 font-semibold">
                            <td colspan="3" class="border px-2 py-1 text-right">Total</td>
                            <td class="border px-2 py-1 text-right">${{ number_format($total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>


            <div class="flex justify-end mt-4">
                <button type="button" wire:click="cerrarModal"
                    class="bg-gray-300 hover:bg-gray-400 rounded-xl w-12 h-12 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif


</div>