<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión y Agenda de Turnos') }}
        </h2>
    </x-slot>

    <!-- ============================================ -->
    <!-- FULLCALENDAR -->
    <!-- ============================================ -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- ============================================ -->
        <!-- MENSAJES -->
        <!-- ============================================ -->
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- ============================================ -->
        <!-- SOLICITUDES PENDIENTES (BANDEJA DE ENTRADA) -->
        <!-- ============================================ -->
        @if(isset($turnosPendientes) && $turnosPendientes->count() > 0)
        <div class="bg-amber-50 border border-amber-200 p-6 shadow-sm sm:rounded-xl border-l-4 border-amber-400">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-amber-900 flex items-center gap-2">
                    <span>⚠️</span> Solicitudes Pendientes de Aprobación ({{ $turnosPendientes->count() }})
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($turnosPendientes as $solicitud)
                <div class="bg-white p-5 rounded-xl border border-amber-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-bold text-gray-900 text-base">
                                {{ $solicitud->patient ? $solicitud->patient->nombre . ' ' . $solicitud->patient->apellido : 'Sin paciente' }}
                            </span>
                            <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                {{ \Carbon\Carbon::parse($solicitud->fecha_hora)->format('d/m/Y H:i') }} hs
                            </span>
                        </div>

                        <div class="text-sm text-gray-600 space-y-1 mb-4">
                            @if($solicitud->patient)
                            <p><strong>Celular:</strong> {{ $solicitud->patient->celular }}</p>

                            @if(!empty($solicitud->patient->edad))
                            <p class="text-red-700 font-medium bg-red-50 p-2 rounded-lg border border-red-100 mt-1">
                                <strong>Edad del paciente:</strong> {{ $solicitud->patient->edad }}
                                <span class="block text-xs font-normal mt-0.5">ℹ️ Recordar: Primera consulta con menores es exclusiva con padres.</span>
                            </p>
                            @endif
                            @endif

                            <p><strong>Cobertura:</strong> {{ $solicitud->obraSocial ? $solicitud->obraSocial->nombre : 'Particular' }}</p>

                            @if(!empty($solicitud->motivo_consulta))
                            <p><strong>Motivo:</strong> {{ $solicitud->motivo_consulta }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Botones de Aceptar / Rechazar -->
                    <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                        <!-- Aceptar -->
                        <form action="{{ route('turnos.estado', $solicitud->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="estado" value="confirmado">
                            <button
                                type="submit"
                                class="w-full py-2 px-3 rounded-lg text-xs font-bold text-white transition shadow-sm hover:opacity-90"
                                style="background-color: #10ac84 !important;">
                                ✓ Aceptar Turno
                            </button>
                        </form>

                        <!-- Rechazar -->
                        <form action="{{ route('turnos.estado', $solicitud->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="estado" value="disponible">
                            <button
                                type="submit"
                                class="w-full py-2 px-3 rounded-lg text-xs font-bold text-white bg-red-600 hover:bg-red-700 transition shadow-sm">
                                ✕ Rechazar
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- ============================================ -->
        <!-- CALENDARIO -->
        <!-- ============================================ -->
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84]">
            <h3 class="font-bold text-lg mb-4 text-gray-800">
                Vista de la Agenda
            </h3>
            <div id="calendar" class="min-h-[500px]"></div>
        </div>

        <!-- ============================================ -->
        <!-- GENERAR FRANJAS -->
        <!-- ============================================ -->
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84] space-y-4">
            <h3 class="font-bold text-lg text-gray-800">Generar Franja Horaria Recurrente</h3>
            <p class="text-xs text-gray-500">Selecciona los días de la semana y los bloques horarios habituales para generar turnos automáticamente.</p>

            <form action="{{ route('turnos.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Días de la semana -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-2">Días de la semana:</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['1' => 'Lunes', '2' => 'Martes', '3' => 'Miércoles', '4' => 'Jueves', '5' => 'Viernes', '6' => 'Sábados', '0' => 'Domingos'] as $key => $dia)
                        <label class="inline-flex items-center bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-lg text-xs font-medium cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="dias[]" value="{{ $key }}" class="rounded border-gray-300 text-[#10ac84] focus:ring-[#10ac84] mr-2">
                            {{ $dia }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Rango de fechas (para saber a qué mes o semanas aplica) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Desde qué fecha:</label>
                        <input type="date" name="fecha_inicio" value="{{ date('Y-m-d') }}" class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Hasta qué fecha:</label>
                        <input type="date" name="fecha_fin" class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]" required>
                    </div>
                </div>

                <!-- Bloque Mañana -->
                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                    <span class="text-xs font-bold text-gray-700 uppercase">Turno Mañana:</span>
                    <div>
                        <label class="block text-[10px] text-gray-400 uppercase">Desde</label>
                        <input type="time" name="manana_desde" value="08:00" class="border-gray-300 rounded-lg p-2 border w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-400 uppercase">Hasta</label>
                        <input type="time" name="manana_hasta" value="12:00" class="border-gray-300 rounded-lg p-2 border w-full text-sm">
                    </div>
                </div>

                <!-- Bloque Tarde -->
                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                    <span class="text-xs font-bold text-gray-700 uppercase">Turno Tarde:</span>
                    <div>
                        <label class="block text-[10px] text-gray-400 uppercase">Desde</label>
                        <input type="time" name="tarde_desde" value="17:00" class="border-gray-300 rounded-lg p-2 border w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-400 uppercase">Hasta</label>
                        <input type="time" name="tarde_hasta" value="20:00" class="border-gray-300 rounded-lg p-2 border w-full text-sm">
                    </div>
                </div>

                <!-- Duración de cada turno -->
                <div class="w-full md:w-1/3">
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Duración de cada turno:</label>
                    <select name="duracion" class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]">
                        <option value="30">30 minutos</option>
                        <option value="45">45 minutos</option>
                        <option value="60" selected>60 minutos (1 hora)</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full text-white rounded-lg px-5 py-3 text-xs font-bold uppercase tracking-wider transition shadow-md hover:opacity-95" style="background-color: #10ac84 !important;">
                        Generar Franjas Masivas
                    </button>
                </div>
            </form>
        </div>

        <!-- ============================================ -->
        <!-- LISTADO Y GESTIÓN -->
        <!-- ============================================ -->
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84] space-y-4">
            <h3 class="font-bold text-lg text-gray-800">Listado y Gestión de Turnos</h3>

            @if($turnos->isEmpty())
            <p class="text-gray-500 italic text-sm">No hay turnos registrados.</p>
            @else
            <!-- Contenedor con scroll para que no sea infinito -->
            <div class="max-h-[500px] overflow-y-auto border border-gray-100 rounded-lg">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 sticky top-0 border-b border-gray-200">
                        <tr>
                            <th class="p-3 text-xs font-bold text-gray-600 uppercase">Fecha y Hora</th>
                            <th class="p-3 text-xs font-bold text-gray-600 uppercase">Paciente</th>
                            <th class="p-3 text-xs font-bold text-gray-600 uppercase">Obra Social</th>
                            <th class="p-3 text-xs font-bold text-gray-600 uppercase">Estado</th>
                            <th class="p-3 text-xs font-bold text-gray-600 uppercase text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($turnos as $turno)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($turno->fecha_hora)->format('d/m/Y H:i') }} hs
                            </td>
                            <td class="p-3 text-gray-600">
                                {{ $turno->patient ? $turno->patient->nombre . ' ' . $turno->patient->apellido : 'Sin asignar' }}
                            </td>
                            <td class="p-3 text-gray-600">
                                {{ $turno->obraSocial ? $turno->obraSocial->nombre : 'Particular' }}
                            </td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full 
                                    @if($turno->estado == 'disponible') bg-green-100 text-green-700 
                                    @elseif($turno->estado == 'pendiente') bg-yellow-100 text-yellow-700 
                                    @else bg-blue-100 text-blue-700 @endif">
                                    {{ ucfirst($turno->estado) }}
                                </span>
                            </td>
                            <td class="p-3 text-right space-x-2">
                                <form action="{{ route('turnos.destroy', $turno->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-bold uppercase" onclick="return confirm('¿Eliminar este turno?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Enlaces de Paginación -->
            <div class="mt-4">
                {{ $turnos->links() }}
            </div>
            @endif
        </div>

        <!-- ============================================ -->
        <!-- SCRIPT FULLCALENDAR -->
        <!-- ============================================ -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    locale: 'es',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: '{{ route("turnos.json") }}'
                });
                calendar.render();
            });
        </script>
</x-app-layout>