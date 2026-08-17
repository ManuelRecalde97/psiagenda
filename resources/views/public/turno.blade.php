<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva tu Turno</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen py-10">

    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">

        <h2 class="text-2xl font-bold text-center mb-2 text-gray-800">
            Reserva tu Sesión
        </h2>

        <!-- Mensaje de bienvenida personalizado del psicólogo (SaaS Multi-usuario) -->
        @if(!empty($psicologo->mensaje_bienvenida))
            <div class="mb-4 p-4 bg-indigo-50 text-indigo-900 rounded-lg text-sm whitespace-pre-line border border-indigo-100">
                {{ $psicologo->mensaje_bienvenida }}
            </div>
        @else
            <p class="text-center text-gray-500 mb-6 text-sm">
                Selecciona un horario y tu cobertura
            </p>
        @endif

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm text-center">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('public.turno.store', $psicologo->id) }}"
            method="POST"
            class="space-y-4"
        >
            @csrf

            <!-- ========================================= -->
            <!-- HORARIOS DISPONIBLES -->
            <!-- ========================================= -->

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Horario disponible:
                </label>

                <select
                    name="turno_id"
                    class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-indigo-500 focus:border-indigo-500"
                    required
                >
                    <option value="">
                        -- Selecciona un horario --
                    </option>

                    @foreach($psicologo->turnos as $turno)
                        <option value="{{ $turno->id }}">
                            {{ \Carbon\Carbon::parse($turno->fecha_hora)->format('d/m/Y H:i') }} hs
                        </option>
                    @endforeach
                </select>
            </div>


            <!-- ========================================= -->
            <!-- OBRA SOCIAL / PARTICULAR -->
            <!-- ========================================= -->

            <div>

                <label
                    for="obra_social_id"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Obra Social / Particular:
                </label>

                <select
                    name="obra_social_id"
                    id="obra_social_id"
                    class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-indigo-500 focus:border-indigo-500"
                    required
                >

                    <option value="">
                        -- Selecciona cobertura --
                    </option>

                    <!-- Atención particular -->
                    <option value="particular">
                        Particular
                    </option>

                    <!-- Obras sociales configuradas por el psicólogo -->
                    @foreach($psicologo->obrasSociales as $obra)

                        <option
                            value="{{ $obra->id }}"
                            data-condiciones="{{ $obra->condiciones }}"
                            data-copago="{{ $obra->copago_adicional }}"
                        >
                            {{ $obra->nombre }}
                        </option>

                    @endforeach

                </select>

            </div>


            <!-- ========================================= -->
            <!-- INFORMACIÓN DE LA COBERTURA -->
            <!-- ========================================= -->

            <div
                id="info-obra-social"
                class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm"
            >

                <h3 class="font-bold text-blue-800 mb-3">
                    Información de la cobertura
                </h3>


                <!-- COPAGO -->

                <div
                    id="copago-container"
                    class="hidden mb-3"
                >

                    <span class="font-semibold text-gray-700">
                        Copago adicional:
                    </span>

                    <span
                        id="copago-text"
                        class="text-gray-700"
                    ></span>

                </div>


                <!-- CONDICIONES -->

                <div
                    id="condiciones-container"
                    class="hidden"
                >

                    <span class="font-semibold text-gray-700">
                        Condiciones:
                    </span>

                    <p
                        id="condiciones-text"
                        class="text-gray-600 mt-1 whitespace-pre-line"
                    ></p>

                </div>

            </div>


            <!-- ========================================= -->
            <!-- CAMPOS DINÁMICOS SEGÚN CONFIGURACIÓN DEL PSICÓLOGO -->
            <!-- ========================================= -->

            <!-- 1. Edad del paciente (Si está activo en el perfil) -->
            @if($psicologo->activar_edad)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Edad del paciente / niño/a:
                    </label>
                    <input
                        type="text"
                        name="edad_paciente"
                        value="{{ old('edad_paciente') }}"
                        placeholder="Ej: 8 años"
                        class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-indigo-500 focus:border-indigo-500"
                        required
                    >
                </div>
            @endif

            <!-- 2. Modalidad (Si está activo en el perfil) -->
            @if($psicologo->activar_modalidad)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Modalidad de atención:
                    </label>
                    <select
                        name="modalidad"
                        class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-indigo-500 focus:border-indigo-500"
                        required
                    >
                        <option value="">-- Selecciona modalidad --</option>
                        <option value="presencial">Presencial</option>
                        <option value="virtual">Virtual</option>
                    </select>
                </div>
            @endif

            <!-- 3. Motivo de consulta (Si está activo en el perfil) -->
            @if($psicologo->activar_motivo)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Motivo de consulta / Requisito:
                    </label>
                    <input
                        type="text"
                        name="motivo_consulta"
                        value="{{ old('motivo_consulta') }}"
                        placeholder="Ej: Atención, CUD, Aptos psicológicos"
                        class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>
            @endif


            <!-- ========================================= -->
            <!-- NOMBRE -->
            <!-- ========================================= -->

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre:
                </label>

                <input
                    type="text"
                    name="nombre"
                    value="{{ old('nombre') }}"
                    class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-indigo-500 focus:border-indigo-500"
                    required
                >

            </div>


            <!-- ========================================= -->
            <!-- APELLIDO -->
            <!-- ========================================= -->

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Apellido:
                </label>

                <input
                    type="text"
                    name="apellido"
                    value="{{ old('apellido') }}"
                    class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-indigo-500 focus:border-indigo-500"
                    required
                >

            </div>


            <!-- ========================================= -->
            <!-- CELULAR -->
            <!-- ========================================= -->

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Celular / WhatsApp:
                </label>

                <input
                    type="text"
                    name="celular"
                    value="{{ old('celular') }}"
                    placeholder="Ej: 3704123456"
                    class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-indigo-500 focus:border-indigo-500"
                    required
                >

            </div>


            <!-- ========================================= -->
            <!-- BOTÓN -->
            <!-- ========================================= -->

            <button
                type="submit"
                class="w-full bg-blue-600 text-white font-bold p-3 rounded-lg hover:bg-blue-700 transition"
            >
                Solicitar Turno
            </button>

        </form>

    </div>


    <!-- ========================================= -->
    <!-- JAVASCRIPT DE OBRA SOCIAL -->
    <!-- ========================================= -->

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const select = document.getElementById('obra_social_id');

            const info = document.getElementById('info-obra-social');

            const copagoContainer =
                document.getElementById('copago-container');

            const copagoText =
                document.getElementById('copago-text');

            const condicionesContainer =
                document.getElementById('condiciones-container');

            const condicionesText =
                document.getElementById('condiciones-text');


            select.addEventListener('change', function () {

                const selectedOption =
                    this.options[this.selectedIndex];


                // Limpiar información anterior

                info.classList.add('hidden');

                copagoContainer.classList.add('hidden');

                condicionesContainer.classList.add('hidden');

                copagoText.textContent = '';

                condicionesText.textContent = '';


                // Si no seleccionó nada

                if (!this.value) {
                    return;
                }


                // =========================================
                // PARTICULAR
                // =========================================

                if (this.value === 'particular') {

                    info.classList.remove('hidden');

                    condicionesContainer.classList.remove('hidden');

                    condicionesText.textContent =
                        'La atención es particular. El arancel y las condiciones serán informados por el profesional.';

                    return;
                }


                // =========================================
                // OBRA SOCIAL
                // =========================================

                const condiciones =
                    selectedOption.dataset.condiciones;

                const copago =
                    selectedOption.dataset.copago;


                // Si existe alguna información,
                // mostramos el cuadro.

                if (condiciones || copago) {

                    info.classList.remove('hidden');

                }


                // =========================================
                // COPAGO
                // =========================================

                if (copago) {

                    copagoContainer.classList.remove('hidden');

                    const monto =
                        Number(copago).toLocaleString('es-AR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                    copagoText.textContent =
                        '$' + monto;

                }


                // =========================================
                // CONDICIONES
                // =========================================

                if (condiciones) {

                    condicionesContainer.classList.remove('hidden');

                    condicionesText.textContent =
                        condiciones;

                }

            });

        });

    </script>

</body>
</html>