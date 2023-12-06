@php use Illuminate\Support\Facades\Route; @endphp
    <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @viteReactRefresh
    @vite(['resources/sass/app.scss', 'resources/js/app.user.jsx'])
</head>
<body>
<div id="app">
    <main class="py-4">
        <div class="container">
            <div class="mb-5">
                <x-navbar></x-navbar>
            </div>
            @if(session('success') || session('error'))
                <div class="row justify-content-center">
                    <div class="col-9">
                        <div
                            class="alert alert-dismissible fade show {{session('success') ? 'alert-success' : 'alert-danger'}}">
                            <div>
                                @if(session('success'))
                                    {{session('success')}}
                                @else
                                    {{session('error')}}
                                @endif
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</div>
<script type="text/javascript">
    const lvToken = '{{ csrf_token() }}'
</script>
@yield('scripts')
</body>
</html>
