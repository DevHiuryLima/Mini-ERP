<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini ERP - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('head')
</head>
<body>
    <header id="header" class="">
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary mb-4">
            <div class="container d-flex justify-content-space-between align-items-center">
                <a class="navbar-brand" href="{{ route('produtos.index') }}">Mini ERP</a>

                <ul class="navbar-nav">
                    <li class="nav-item"><a href="{{ route('produtos.index') }}" class="nav-link">Produtos</a></li>
                    <li class="nav-item"><a href="{{ route('carrinho.index') }}" class="nav-link">Carrinho</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Pedidos</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    @stack('scripts')
</body>
</html>
