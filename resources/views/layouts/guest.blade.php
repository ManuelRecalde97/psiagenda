<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN para garantizar que cargue siempre los estilos perfectos sin depender de Vite en producción -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center py-10 px-4" style="background-color: #5b67ca;">
        
        <!-- Contenedor Principal del Logo y la Caja -->
        <div class="w-full sm:max-w-md flex flex-col items-center">
            
            <!-- Logo de Psiagenda -->
            <div class="mb-6 text-center">
                <a href="/">
                    <img src="{{ asset('images/logo.svg') }}" style="max-height: 80px; width: auto;" alt="psiagenda logo">
                </a>
            </div>

            <!-- Caja del Formulario con marco verde -->
            <div class="w-full px-6 py-6 shadow-2xl overflow-hidden sm:rounded-xl" style="background-color: #e2f5f0; border: 2.5px solid #10ac84;">
                {{ $slot }}
            </div>

        </div>
    </div>
</body>

</html>