<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    @if ($code === 503)
        <title>Under Maintenance | {{ config('app.name') }}</title>
    @else
        <title>Error {{ $code ?? '' }} | {{ config('app.name') }}</title>
    @endif

    <link rel="shortcut icon" href="/storage/img/favicon.png">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>

<body>
    <div class="min-h-screen bg-gray-100 pt-28 pb-20 px-6">
        <div class="max-w-lg mx-auto flex flex-col space-y-4">
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

                <div>
                    <div class="text-lg">
                        Error {{ $code }}
                    </div>
    
                    <div class="text-lg text-gray-500">
                        {{ $message }}
                    </div>
                </div>
    
                <div>
                    <x-button href="/">
                        Back to home
                    </x-button>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
