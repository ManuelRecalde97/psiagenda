<x-guest-layout>
    <!-- Contenedor general que centra todo verticalmente -->
    <div class="min-h-screen flex flex-col justify-center items-center py-6 px-4" style="background-color: #5b67ca; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 9999;">
        
        <!-- Logo FUERA y arriba del cuadro (flotando en el fondo azul) -->
        <div class="mb-6 flex justify-center">
            <a href="/">
                <img src="{{ asset('images/logo.svg') }}" style="height: 75px; width: auto;" alt="Logo Psiagenda">
            </a>
        </div>

        <!-- Caja del Formulario con el marco verde (SOLO el formulario adentro) -->
        <div class="w-full sm:max-w-md px-6 py-6 shadow-2xl overflow-hidden sm:rounded-xl" style="background-color: #e2f5f0; border: 2.5px solid #10ac84;">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                    @if (Route::has('register'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 ml-4" href="{{ route('register') }}">
                            ¿No estás registrado?
                        </a>
                    @endif

                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 ms-3 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2" style="background-color: #10ac84;">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>