<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Turnos Fijos (Recurrentes)') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Formulario para Registrar Turno Fijo -->
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84] space-y-4">
            <h3 class="font-bold text-lg text-gray-800">Asignar Turno Fijo a Paciente</h3>
            <p class="text-xs text-gray-500">Selecciona el paciente, su obra social y define su día y horario recurrente.</p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('turnos-fijos.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Selector de Paciente (Usando $pacientes) -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Paciente (Apellido y Nombre):</label>
                        <select name="patient_id" class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]" required>
                            <option value="">-- Seleccione un paciente --</option>
                            @foreach($pacientes as $paciente)
                                <option value="{{ $paciente->id }}">
                                    {{ $paciente->apellido }}, {{ $paciente->nombre }} (Cel: {{ $paciente->celular ?? 'Sin cel' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selector de Obra Social -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Obra Social / Cobertura:</label>
                        <select name="obra_social_id" class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]">
                            <option value="">Particular / Sin Obra Social</option>
                            @foreach($obrasSociales as $os)
                                <option value="{{ $os->id }}">{{ $os->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Día de la semana -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Día Fijo:</label>
                        <select name="dia_semana" class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]" required>
                            <option value="1">Lunes</option>
                            <option value="2">Martes</option>
                            <option value="3">Miércoles</option>
                            <option value="4">Jueves</option>
                            <option value="5">Viernes</option>
                            <option value="6">Sábado</option>
                        </select>
                    </div>

                    <!-- Hora -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Hora de la Sesión:</label>
                        <input type="time" name="hora" class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]" required>
                    </div>

                    <!-- Modalidad -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Modalidad:</label>
                        <select name="modalidad" class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]">
                            <option value="presencial">Presencial</option>
                            <option value="virtual">Virtual</option>
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="text-white rounded-lg px-5 py-2.5 text-xs font-bold uppercase tracking-wider transition shadow-md hover:opacity-95" style="background-color: #10ac84;">
                        Guardar Turno Fijo
                    </button>
                </div>
            </form>
        </div>

        <!-- Listado de Turnos Fijos Registrados (Usando $turnosFijos) -->
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84] space-y-4">
            <h3 class="font-bold text-lg text-gray-800">Listado de Turnos Fijos Activos</h3>

            @if($turnosFijos->isEmpty())
                <p class="text-gray-500 italic text-sm">No hay turnos fijos registrados todavía.</p>
            @else
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="p-3 text-xs font-bold text-gray-600 uppercase">Paciente</th>
                            <th class="p-3 text-xs font-bold text-gray-600 uppercase">Obra Social</th>
                            <th class="p-3 text-xs font-bold text-gray-600 uppercase">Día</th>
                            <th class="p-3 text-xs font-bold text-gray-600 uppercase">Hora</th>
                            <th class="p-3 text-xs font-bold text-gray-600 uppercase">Modalidad</th>
                            <th class="p-3 text-xs font-bold text-gray-600 uppercase text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($turnosFijos as $tf)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-medium text-gray-800">
                                    {{ $tf->paciente->apellido ?? '' }}, {{ $tf->paciente->nombre ?? '' }}
                                </td>
                                <td class="p-3 text-gray-600">
                                    {{ $tf->obraSocial->nombre ?? 'Particular' }}
                                </td>
                                <td class="p-3 text-gray-600">
                                    @switch($tf->dia_semana)
                                        @case(1) Lunes @break
                                        @case(2) Martes @break
                                        @case(3) Miércoles @break
                                        @case(4) Jueves @break
                                        @case(5) Viernes @break
                                        @case(6) Sábado @break
                                        @default Desconocido
                                    @endswitch
                                </td>
                                <td class="p-3 text-gray-600">{{ $tf->hora }} hs</td>
                                <td class="p-3 text-gray-600">{{ ucfirst($tf->modalidad ?? 'Presencial') }}</td>
                                <td class="p-3 text-right">
                                    <form action="{{ route('turnos-fijos.destroy', $tf->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-bold uppercase" onclick="return confirm('¿Eliminar este turno fijo?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</x-app-layout>