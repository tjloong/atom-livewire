<!doctype html>
<html lang="{{ app()->currentLocale() }}">

<head>
    @if ($code === 503)
        <title>Under Maintenance | {{ config('app.name') }}</title>
    @else
        <title>Error {{ $code ?? '' }} | {{ config('app.name') }}</title>
    @endif

    <link rel="shortcut icon" href="/storage/img/favicon.png">
    <link href="/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="/fontawesome/css/solid.css" rel="stylesheet">

    @vite('resources/css/app.css')
</head>

<body>
    <div class="min-h-screen bg-gray-100 pt-28 pb-20 px-6">
        <div class="max-w-screen-lg mx-auto flex flex-col items-center justify-center gap-4">
            <a href="/">
                <x-logo class="w-20"/>
            </a>

            @if ($code === 503)
                <div class="text-7xl font-bold">STAY TUNE!</div>
                <div class="text-lg text-gray-500">{{ $message }}</div>
            @else
                <div class="text-7xl font-bold">
                    OOPS!
                </div>

                <div class="text-center">
                    <div class="text-lg">
                        Error {{ $code }}
                    </div>
    
                    <div class="text-lg text-gray-500">
                        {{ $message }}
                    </div>
                </div>

                <x-link label="Back to Home" icon="back" href="/"/>
            @endif
        </div>
    </div>
</body>
</html>
