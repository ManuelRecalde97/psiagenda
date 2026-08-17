<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bienvenido') }}
        </h2>
    </x-slot>

    <div class="py-12" style="background-color: #f4f6f9;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Tarjetas de Estadísticas Principales con un toque del borde verde -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-l-4 border-[#10ac84]">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Pacientes Activos</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">4</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-l-4 border-[#10ac84]">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Turnos Esta Semana</div>
                    <div class="text-3xl font-bold text-teal-600 mt-2">0</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-l-4 border-[#10ac84]">
                    <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Facturación Mes</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">$ 0</div>
                </div>

            </div>

            <!-- Botones de Acción Rápida unificados con el verde corporativo -->
            <div class="flex flex-wrap gap-4">
                <a href="#" class="inline-flex items-center px-5 py-2.5 rounded-lg font-semibold text-xs text-white uppercase tracking-widest shadow-md transition hover:opacity-90" style="background-color: #10ac84;">
                    + Nuevo Paciente
                </a>
                <a href="#" class="inline-flex items-center px-5 py-2.5 rounded-lg font-semibold text-xs text-white uppercase tracking-widest shadow-md transition hover:opacity-90" style="background-color: #10ac84;">
                    📅 Agendar Turno
                </a>
            </div>

            <!-- Sección Agenda de Hoy -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-100">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Agenda de Hoy</h3>
                <p class="text-gray-500 text-sm">No hay turnos programados para el día de hoy.</p>
            </div>

        </div>
    </div>
</x-app-layout>