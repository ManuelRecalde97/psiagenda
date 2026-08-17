<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Obras Sociales y Coberturas') }}
        </h2>
    </x-slot>

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
        <!-- LISTADO DE OBRAS SOCIALES EXISTENTES -->
        <!-- ============================================ -->
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84]">
            <h3 class="font-bold text-lg mb-4 text-gray-800">
                Mis Obras Sociales / Coberturas
            </h3>

            @if(isset($obrasSociales) && $obrasSociales->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($obrasSociales as $obra)
                        <div class="py-3 flex justify-between items-center">
                            <div>
                                <span class="font-medium text-gray-800 text-base">
                                    {{ $obra->nombre }}
                                </span>
                                @if(!empty($obra->condiciones))
                                    <span class="block text-xs text-gray-500 mt-0.5">
                                        {{ $obra->condiciones }}
                                    </span>
                                @endif
                                @if(!empty($obra->copago_adicional))
                                    <span class="block text-xs text-teal-600 font-semibold mt-0.5">
                                        Copago: ${{ number_format($obra->copago_adicional, 2, ',', '.') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Botón Eliminar -->
                            <form action="{{ route('obras.destroy', $obra->id) }}" method="POST">
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
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm italic">Aún no tienes obras sociales registradas. Se tomará como predeterminado "Particular".</p>
            @endif
        </div>

        <!-- ============================================ -->
        <!-- FORMULARIO NUEVA OBRA SOCIAL -->
        <!-- ============================================ -->
        <div class="bg-white p-6 shadow-sm sm:rounded-xl border-l-4 border-[#10ac84]">
            <h3 class="font-bold text-lg mb-4 text-gray-800">
                Registrar Nueva Obra Social / Cobertura
            </h3>

            <form action="{{ route('obras.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nombre de la Obra Social -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">
                        Nombre de la Obra Social
                    </label>
                    <input
                        type="text"
                        name="nombre"
                        class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]"
                        placeholder="Ej: IASEP / AMP / Sancor Salud"
                        required
                    >
                </div>

                <!-- Condiciones, Copagos o Requisitos -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">
                        Condiciones, Copagos o Requisitos
                    </label>
                    <textarea
                        name="condiciones"
                        rows="3"
                        class="border-gray-300 rounded-lg p-3 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]"
                        placeholder="Ej: Las sesiones se autorizan mediante token. El consultorio establece un copago adicional de $13.000..."
                    ></textarea>
                </div>

                <!-- Monto de Copago Adicional -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">
                        Monto de Copago Adicional (Opcional)
                    </label>
                    <input
                        type="number"
                        step="0.01"
                        name="copago_adicional"
                        class="border-gray-300 rounded-lg p-2.5 border w-full text-sm focus:ring-[#10ac84] focus:border-[#10ac84]"
                        placeholder="Ej: 13000"
                    >
                </div>

                <!-- Botón de Guardar -->
                <div class="pt-2 flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center px-5 py-2.5 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md hover:opacity-95"
                        style="background-color: #10ac84 !important;"
                    >
                        Guardar Obra Social
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>