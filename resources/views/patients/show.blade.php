<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ficha del Paciente: ') }} {{ $patient->nombre }} {{ $patient->apellido }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Información General del Paciente -->
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84]">
            <h3 class="font-bold text-lg mb-4 text-gray-800">Datos Personales</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-500">Celular:</span>
                    <p class="text-gray-800 font-semibold mt-1">{{ $patient->celular ?? 'No registrado' }}</p>
                </div>
                <div>
                    <span class="font-medium text-gray-500">Obra Social:</span>
                    <p class="text-gray-800 font-semibold mt-1">{{ $patient->obra_social ?? 'Particular' }}</p>
                </div>
                <div>
                    <span class="font-medium text-gray-500">Motivo de Consulta Inicial:</span>
                    <p class="text-gray-800 font-semibold mt-1">{{ $patient->motivo_consulta ?? 'Sin especificar' }}</p>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('patients.edit', $patient->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-md text-xs font-bold uppercase tracking-widest hover:bg-yellow-700 transition shadow-sm">Editar Datos</a>
                <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 text-white rounded-md text-xs font-bold uppercase tracking-widest transition shadow-sm hover:opacity-90" style="background-color: #10ac84;">Volver al Listado</a>
            </div>
        </div>

        <!-- Sección para Agregar Nota de Sesión / Evolución -->
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84]">
            <h3 class="font-bold text-lg mb-4 text-gray-800">Registrar Evolución / Nota de Sesión</h3>

            <form action="{{ route('notes.store', $patient->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <textarea name="contenido" rows="4" class="border-gray-300 rounded-lg p-3 border w-full focus:ring-[#10ac84] focus:border-[#10ac84]" placeholder="Escribe aquí las observaciones de la sesión actual..." required></textarea>
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-white rounded-md text-sm font-bold uppercase tracking-wider transition shadow-md hover:opacity-95" style="background-color: #10ac84;">Guardar Nota</button>
                </div>
            </form>
        </div>

        <!-- Historial de Notas Clínicas -->
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84]">
            <h3 class="font-bold text-lg mb-4 text-gray-800">Historial Clínico (Notas Anteriores)</h3>

            @if($notes->isEmpty())
                <p class="text-gray-500 italic text-sm">Aún no hay notas registradas para este paciente.</p>
            @else
                <div class="space-y-4">
                    @foreach($notes as $note)
                        <div class="border-b border-gray-100 pb-4 last:border-0">
                            <span class="text-xs text-teal-600 font-semibold block mb-1">{{ $note->created_at->format('d/m/Y H:i') }}</span>
                            <p class="text-gray-700 text-sm whitespace-pre-line">{{ $note->contenido }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>