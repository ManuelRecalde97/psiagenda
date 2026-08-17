<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Pacientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-6">Lista de Pacientes</h3>
                    
                    @if($patients->isEmpty())
                        <p class="text-gray-500">Aún no tienes pacientes registrados.</p>
                    @else
                        <div class="flex flex-col space-y-4">
                            @foreach($patients as $patient)
                                <div class="flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 transition rounded-lg border border-gray-100">
                                    <a href="{{ route('patients.show', $patient->id) }}" class="font-medium text-gray-800 hover:text-[#10ac84] transition text-base">
                                        {{ $patient->nombre }} {{ $patient->apellido }}
                                    </a>
                        
                                    <!-- Botón de editar espaciado y limpio -->
                                    <a href="{{ route('patients.edit', $patient->id) }}" class="font-semibold px-4 py-2 text-xs uppercase tracking-wider rounded-md text-white shadow-sm transition hover:opacity-90" style="background-color: #10ac84;">
                                        Editar
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>