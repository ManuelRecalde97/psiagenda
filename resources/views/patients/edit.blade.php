<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Paciente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                
                <form action="{{ route('patients.update', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" value="{{ $patient->nombre }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Apellido</label>
                        <input type="text" name="apellido" value="{{ $patient->apellido }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" style="background-color: #2563eb !important; color: white !important; padding: 10px 20px !important; border-radius: 5px !important; cursor: pointer !important;">
                              Guardar Cambios
                        </button>
                        <a href="{{ route('patients.index') }}" class="text-gray-600 hover:text-gray-900">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>