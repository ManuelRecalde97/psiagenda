<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Feriados y Días Sin Atención') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Mensaje de éxito --}}
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        {{-- Errores --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">
            <ul class="list-disc ml-5 text-sm">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Agregar día sin atención --}}
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84]">
            <h3 class="text-lg font-bold text-gray-800 mb-1">
                Agregar día sin atención
            </h3>
            <p class="text-xs text-gray-500 mb-5">
                Registrá un feriado, vacaciones o cualquier día en el que no vas a brindar atención.
            </p>

            <form action="{{ route('feriados.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">
                        Fecha
                    </label>
                    <input
                        type="date"
                        name="fecha"
                        required
                        class="w-full border-gray-300 rounded-lg p-2.5 border text-sm focus:ring-[#10ac84] focus:border-[#10ac84]"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">
                        Motivo
                    </label>
                    <input
                        type="text"
                        name="motivo"
                        placeholder="Ej: Feriado nacional"
                        required
                        class="w-full border-gray-300 rounded-lg p-2.5 border text-sm focus:ring-[#10ac84] focus:border-[#10ac84]"
                    >
                </div>

                <div class="pt-2 flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center px-5 py-2.5 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md hover:opacity-95"
                        style="background-color: #10ac84 !important;"
                    >
                        Registrar día sin atención
                    </button>
                </div>
            </form>
        </div>

        {{-- Mis días sin atención y Turnos afectados --}}
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84]">
            <h3 class="text-lg font-bold text-gray-800 mb-5">
                Mis días sin atención
            </h3>

            @if(isset($feriados) && $feriados->count() > 0)
                <div class="overflow-x-auto mb-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="py-3 px-3">Fecha</th>
                                <th class="py-3 px-3">Motivo</th>
                                <th class="py-3 px-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($feriados as $feriado)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3 px-3 font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($feriado->fecha)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-3 text-gray-700">
                                        {{ $feriado->motivo }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <form action="{{ route('feriados.destroy', $feriado->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="text-gray-400 hover:text-red-600 text-xs font-bold py-1 transition"
                                            >
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-sm italic mb-6">No hay días sin atención registrados.</p>
            @endif

            {{-- Turnos fijos afectados (si aplica) --}}
            @if(isset($turnosAfectados) && $turnosAfectados->count() > 0)
                <div class="bg-amber-50 border border-amber-200 p-5 rounded-xl space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="text-amber-600 font-bold">⚠️</span>
                        <h4 class="font-bold text-amber-900 text-base">Turnos fijos afectados</h4>
                    </div>
                    <p class="text-xs text-amber-700">
                        Estos pacientes tienen un turno fijo que coincide con este día sin atención.
                    </p>

                    <div class="space-y-3">
                        @foreach($turnosAfectados as $afectado)
                            <div class="bg-white p-4 rounded-lg border border-amber-200 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                <div class="space-y-1">
                                    <span class="font-bold text-gray-900 text-sm block">
                                        {{ $afectado->patient ? $afectado->patient->nombre . ' ' . $afectado->patient->apellido : 'Sin paciente' }}
                                    </span>
                                    <span class="text-xs text-gray-600 block">
                                        📅 Turno fijo: {{ \Carbon\Carbon::parse($afectado->hora)->format('H:i') }} hs
                                    </span>
                                    @if($afectado->patient && $afectado->patient->celular)
                                        <span class="text-xs text-gray-600 block">
                                            📞 {{ $afectado->patient->celular }}
                                        </span>
                                    @endif
                                    @if($afectado->obraSocial)
                                        <span class="text-xs text-teal-600 font-medium block">
                                            {{ $afectado->obraSocial->nombre }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500 block">Particular</span>
                                    @endif
                                </div>

                                @if($afectado->patient && $afectado->patient->celular)
                                    <a
                                        href="https://wa.me/{{ $afectado->patient->celular }}?text={{ urlencode('Hola '.$afectado->patient->nombre.', te escribo para avisarte que debido a un día sin atención (feriado/vacaciones) programado para el día de la fecha, no podremos realizar la sesión.') }}"
                                        target="_blank"
                                        class="inline-flex items-center px-4 py-2 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition shadow-sm hover:opacity-90"
                                        style="background-color: #10ac84 !important; text-decoration: none;"
                                    >
                                        💬 Avisar por WhatsApp
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>
</x-app-layout>