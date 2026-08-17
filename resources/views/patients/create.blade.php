<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nuevo Paciente') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow sm:rounded-lg">
            
            <form action="{{ route('patients.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="nombre" class="border-gray-300 rounded p-2 border w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                        <input type="text" name="apellido" class="border-gray-300 rounded p-2 border w-full" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                        <input type="text" name="celular" class="border-gray-300 rounded p-2 border w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Obra Social</label>
                        <input type="text" name="obra_social" class="border-gray-300 rounded p-2 border w-full">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de Consulta / Notas Iniciales</label>
                    <textarea name="motivo_consulta" rows="4" class="border-gray-300 rounded p-2 border w-full"></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('patients.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded font-bold hover:bg-gray-600">Cancelar</a>
                    <button type="submit" style="background-color: #059669 !important; color: white !important; padding: 10px 20px; border-radius: 6px; font-weight: bold; border: none; cursor: pointer;" >Guardar Paciente</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>