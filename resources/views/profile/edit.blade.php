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

            <!-- Configuración Personalizada del Turnero Público -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Configuración del Turnero Público') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Personaliza el mensaje de bienvenida y los campos que verán tus pacientes al solicitar un turno.') }}
                            </p>
                        </header>

                        <div class="mt-6 space-y-6">
                            <!-- Mensaje de Bienvenida -->
                            <div>
                                <x-input-label for="mensaje_bienvenida" :value="__('Mensaje de Bienvenida en Turnero Público')" />
                                <textarea id="mensaje_bienvenida" name="mensaje_bienvenida" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="4">{{ old('mensaje_bienvenida', $user->mensaje_bienvenida) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('mensaje_bienvenida')" />
                            </div>

                            <!-- Opciones / Checkboxes -->
                            <div class="flex flex-col space-y-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="activar_edad" value="1" {{ $user->activar_edad ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Solicitar edad del paciente (ideal si atiendes niños/adolescentes)') }}</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" name="activar_modalidad" value="1" {{ $user->activar_modalidad ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Habilitar selección de modalidad (Presencial / Virtual)') }}</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" name="activar_motivo" value="1" {{ $user->activar_motivo ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Habilitar motivo de consulta (Informes CUD, aptos, etc.)') }}</span>
                                </label>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>