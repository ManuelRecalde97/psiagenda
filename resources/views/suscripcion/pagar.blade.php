<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Tu suscripción ha vencido</h2>
        <p class="text-gray-600 text-sm mb-6">Para seguir gestionando turnos y pacientes, por favor renueva tu suscripción mensual.</p>
        
        <div class="mb-4">
            <a href="https://mpago.la/1bkw3jd" style="background-color: #2563eb; color: #ffffff;" class="block w-full font-bold p-3 rounded-lg hover:bg-blue-700 transition shadow text-center">
                Pagar Suscripción Mensual
            </a>
        </div>
        
        <div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 underline hover:text-gray-700">Cerrar sesión</button>
            </form>
        </div>
    </div>
</x-guest-layout>