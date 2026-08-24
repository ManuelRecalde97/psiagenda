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

        <!-- Sección para Registrar Evolución / Nota de Sesión -->
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84]">
            <h3 class="font-bold text-lg mb-4 text-gray-800">Registrar Evolución / Nota de Sesión</h3>

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm shadow-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('notes.store', $patient->id) }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Fecha de la sesión:</label>
                        <input type="date" name="fecha" value="{{ date('Y-m-d') }}" class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]" required>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Diagnóstico / Tema principal:</label>
                        <input type="text" name="diagnostico" class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]" placeholder="Ej: Ansiedad generalizada">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Evolución / Observaciones:</label>
                    <textarea name="evolucion" rows="4" class="border-gray-300 rounded-lg p-3 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]" placeholder="Escribe aquí el desarrollo de la sesión..." required></textarea>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Tareas / Indicaciones:</label>
                    <textarea name="tareas_indicaciones" rows="2" class="border-gray-300 rounded-lg p-3 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]" placeholder="Ejercicios o pautado para la casa..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2 border-t border-gray-100">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Monto ($):</label>
                        <input type="number" step="0.01" name="monto" class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]" placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Estado de Pago:</label>
                        <select name="estado_pago" class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="pagado">Pagado</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Tipo de Pago:</label>
                        <select name="tipo_pago" class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]" required>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="obra_social">Obra Social</option>
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-white rounded-md text-sm font-bold uppercase tracking-wider transition shadow-md hover:opacity-95" style="background-color: #10ac84;">Guardar Evolución</button>
                </div>
            </form>
        </div>

        <!-- Historial de Notas Clínicas -->
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84]">
            <h3 class="font-bold text-lg mb-4 text-gray-800">Historial Clínico (Notas Anteriores)</h3>

            @if($notes->isEmpty())
                <p class="text-gray-500 italic text-sm">Aún no hay evoluciones registradas para este paciente.</p>
            @else
                <div class="space-y-6">
                    @foreach($notes as $note)
                        <div class="border-b border-gray-100 pb-4 last:border-0 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-teal-600 font-bold bg-teal-50 px-2.5 py-1 rounded-full border border-teal-100">
                                    Fecha de sesión: {{ \Carbon\Carbon::parse($note->fecha)->format('d/m/Y') }}
                                </span>
                                @if($note->monto)
                                    <span class="text-xs font-semibold text-gray-600">
                                        Pago: ${{ number_format($note->monto, 2) }} ({{ ucfirst($note->estado_pago) }} - {{ ucfirst($note->tipo_pago) }})
                                    </span>
                                @endif
                            </div>

                            @if($note->diagnostico)
                                <p class="text-sm font-semibold text-gray-800">Diagnóstico: <span class="font-normal text-gray-600">{{ $note->diagnostico }}</span></p>
                            @endif

                            <p class="text-gray-700 text-sm whitespace-pre-line bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <strong class="block text-xs uppercase text-gray-400 mb-1">Evolución:</strong>
                                {{ $note->evolucion }}
                            </p>

                            @if($note->tareas_indicaciones)
                                <p class="text-gray-600 text-xs italic bg-amber-50 p-2.5 rounded-lg border border-amber-100">
                                    <strong>Tareas/Indicaciones:</strong> {{ $note->tareas_indicaciones }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>