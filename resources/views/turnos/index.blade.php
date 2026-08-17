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
                                        style="background-color: #10ac84 !important;"
                                    >
                                        ✓ Aceptar Turno
                                    </button>
                                </form>

                                <!-- Rechazar -->
                                <form action="{{ route('turnos.estado', $solicitud->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="estado" value="disponible">
                                    <button
                                        type="submit"
                                        class="w-full py-2 px-3 rounded-lg text-xs font-bold text-white bg-red-600 hover:bg-red-700 transition shadow-sm"
                                    >
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
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84]">
            <h2 class="text-lg font-bold mb-4 text-gray-800">
                Generar Franja Horaria de Turnos
            </h2>

            <form
                action="{{ route('turnos.store') }}"
                method="POST"
                class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end"
            >
                @csrf

                <!-- Fecha -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">
                        Fecha
                    </label>
                    <input
                        type="date"
                        name="fecha"
                        class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]"
                        required
                    >
                </div>

                <!-- Desde -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">
                        Desde las
                    </label>
                    <input
                        type="time"
                        name="hora_inicio"
                        class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]"
                        required
                    >
                </div>

                <!-- Hasta -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">
                        Hasta las
                    </label>
                    <input
                        type="time"
                        name="hora_fin"
                        class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]"
                        required
                    >
                </div>

                <!-- Duración -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">
                        Duración (min)
                    </label>
                    <select
                        name="duracion"
                        class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]"
                    >
                        <option value="30">30 minutos</option>
                        <option value="45">45 minutos</option>
                        <option value="60" selected>60 minutos</option>
                    </select>
                </div>

                <!-- Botón -->
                <div class="flex justify-end pt-2">
                    <button
                        type="submit"
                        class="w-full md:w-auto inline-flex items-center justify-center px-5 py-2.5 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md hover:opacity-95"
                        style="background-color: #10ac84 !important;"
                    >
                        Generar Franjas
                    </button>
                </div>
            </form>
        </div>

        <!-- ============================================ -->
        <!-- LISTADO Y GESTIÓN -->
        <!-- ============================================ -->
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84]">
            <h3 class="font-bold text-lg mb-4 text-gray-800">
                Listado y Gestión de Turnos
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="py-3 px-3">Fecha y Hora</th>
                            <th class="py-3 px-3">Paciente</th>
                            <th class="py-3 px-3">Obra Social</th>
                            <th class="py-3 px-3">Estado</th>
                            <th class="py-3 px-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($turnos as $turno)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- FECHA Y HORA -->
                                <td class="py-3 px-3">
                                    <span class="font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($turno->fecha_hora)->format('d/m/Y') }}
                                    </span>
                                    <span class="block text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($turno->fecha_hora)->format('H:i') }} hs
                                    </span>
                                </td>

                                <!-- PACIENTE -->
                                <td class="py-3 px-3">
                                    @if($turno->paciente)
                                        <span class="font-medium text-gray-800">
                                            {{ $turno->paciente->nombre }} {{ $turno->paciente->apellido }}
                                        </span>
                                        <span class="block text-xs text-gray-500">
                                            {{ $turno->paciente->celular }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">Sin asignar</span>
                                    @endif
                                </td>

                                <!-- OBRA SOCIAL -->
                                <td class="py-3 px-3">
                                    @if($turno->obraSocial)
                                        <span class="font-medium text-gray-800">
                                            {{ $turno->obraSocial->nombre }}
                                        </span>
                                        @if($turno->obraSocial->copago_adicional)
                                            <span class="block text-xs text-gray-500">
                                                Copago: ${{ number_format($turno->obraSocial->copago_adicional, 2, ',', '.') }}
                                            </span>
                                        @endif
                                    @elseif($turno->patient_id)
                                        <span class="text-gray-700">Particular</span>
                                    @else
                                        <span class="text-gray-400 italic">Sin asignar</span>
                                    @endif
                                </td>

                                <!-- ESTADO -->
                                <td class="py-3 px-3">
                                    <span
                                        class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $turno->estado == 'disponible' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $turno->estado == 'pendiente' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ $turno->estado == 'confirmado' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $turno->estado == 'rechazado' ? 'bg-red-100 text-red-700' : '' }}"
                                    >
                                        {{ ucfirst($turno->estado) }}
                                    </span>
                                </td>

                                <!-- ACCIONES -->
                                <td class="py-3 px-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if($turno->estado == 'pendiente')
                                            <!-- ACEPTAR -->
                                            <form action="{{ route('turnos.estado', $turno->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="estado" value="confirmado">
                                                <button
                                                    type="submit"
                                                    class="px-3 py-1.5 rounded-lg text-xs font-bold text-white shadow-sm transition hover:opacity-90"
                                                    style="background-color: #10ac84 !important;"
                                                >
                                                    Aceptar
                                                </button>
                                            </form>

                                            <!-- RECHAZAR -->
                                            <form action="{{ route('turnos.estado', $turno->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="estado" value="disponible">
                                                <button
                                                    type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm"
                                                >
                                                    Rechazar
                                                </button>
                                            </form>
                                        @endif

                                        @if($turno->estado == 'confirmado' && $turno->paciente)
                                            <a
                                                href="https://wa.me/{{ $turno->paciente->celular }}?text={{ urlencode('Hola '.$turno->paciente->nombre.', tu turno para el '.\Carbon\Carbon::parse($turno->fecha_hora)->format('d/m/Y H:i').' hs ha sido confirmado.') }}"
                                                target="_blank"
                                                class="px-3 py-1.5 rounded-lg text-xs font-bold text-white shadow-sm transition hover:opacity-90 inline-block"
                                                style="background-color: #10ac84 !important; text-decoration: none;"
                                            >
                                                Enviar WhatsApp
                                            </a>
                                        @endif

                                        <form action="{{ route('turnos.destroy', $turno->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="text-gray-400 hover:text-red-600 text-xs font-bold py-1 transition"
                                            >
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500 italic">
                                    No hay turnos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

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