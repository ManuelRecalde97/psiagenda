<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <!-- ÚNICO Formulario unificado para guardar Turnero y Recordatorios -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Configuración del Turnero y Recordatorios') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Personaliza el mensaje, los campos del turnero público y la frecuencia de los recordatorios automáticos.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <!-- Inputs ocultos obligatorios para Breeze -->
                            <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">

                            <!-- Mensaje de Bienvenida -->
                            <div>
                                <x-input-label for="mensaje_bienvenida" :value="__('Mensaje de Bienvenida en Turnero Público')" />
                                <textarea id="mensaje_bienvenida" name="mensaje_bienvenida" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="4">{{ old('mensaje_bienvenida', $user->mensaje_bienvenida) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('mensaje_bienvenida')" />
                            </div>

                            <!-- Opciones / Checkboxes del Turnero -->
                            <div class="flex flex-col space-y-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="activar_edad" value="1" {{ $user->activar_edad ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Solicitar edad del paciente (ideal si atiendes niños/adolescentes)') }}</span>
                                </label>

                                <!-- Checkbox para habilitar la regla de menores -->
                                <div class="mt-2 space-y-2 p-3 bg-gray-50 rounded-md border border-gray-200">
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="activar_aviso_menores" value="1" {{ $user->activar_aviso_menores ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                                        <span class="text-sm font-medium text-gray-700">Avisar que la primera consulta de menores de edad es solo con padres/tutores</span>
                                    </label>

                                    <!-- Cuadro de texto para personalizar el mensaje -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase mt-2 mb-1">Mensaje aclaratorio para la primera sesión:</label>
                                        <textarea name="mensaje_aviso_menores" rows="2" class="block w-full border-gray-300 rounded-md shadow-sm text-sm border-gray-300" placeholder="Ej: Importante: La primera entrevista de orientación es exclusiva con los padres o tutores.">{{ old('mensaje_aviso_menores', $user->mensaje_aviso_menores) }}</textarea>
                                    </div>
                                </div>

                                <label class="flex items-center">
                                    <input type="checkbox" name="activar_modalidad" value="1" {{ $user->activar_modalidad ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Habilitar selección de modalidad (Presencial / Virtual)') }}</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" name="activar_motivo" value="1" {{ $user->activar_motivo ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Habilitar motivo de consulta (Informes CUD, aptos, etc.)') }}</span>
                                </label>
                            </div>

                            <hr class="border-gray-200 my-4">

                            <!-- Configuración de Recordatorios Automáticos (dentro del mismo formulario) -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Configuración de Recordatorios Automáticos</h3>
                                <p class="mt-1 text-sm text-gray-600">Personaliza cada cuánto tiempo deseas que el sistema envíe alertas o recordatorios a tus pacientes.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">¿Activar recordatorios automáticos?</label>
                                <select name="enviar_recordatorios" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="1" {{ $user->enviar_recordatorios ? 'selected' : '' }}>Sí, activar</option>
                                    <option value="0" {{ !$user->enviar_recordatorios ? 'selected' : '' }}>No, desactivar</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Frecuencia de envío:</label>
                                <select name="frecuencia_recordatorio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="8hs" {{ $user->frecuencia_recordatorio == '8hs' ? 'selected' : '' }}>Cada 8 horas</option>
                                    <option value="12hs" {{ $user->frecuencia_recordatorio == '12hs' ? 'selected' : '' }}>Cada 12 horas</option>
                                    <option value="24hs" {{ $user->frecuencia_recordatorio == '24hs' ? 'selected' : '' }}>Cada 24 horas (Un día antes)</option>
                                    <option value="todos" {{ $user->frecuencia_recordatorio == 'todos' ? 'selected' : '' }}>Combinado (8hs, 12hs y 24hs)</option>
                                </select>
                            </div>

                            <!-- Botón único para guardar todo -->
                            <div class="flex items-center gap-4 pt-2">
                                <button type="submit" class="w-full text-white rounded-lg px-5 py-3 text-xs font-bold uppercase tracking-widest transition shadow-md hover:opacity-95" style="background-color: #10ac84 !important;">
                                    Guardar Configuración
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>