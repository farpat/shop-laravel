<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="{{ route('home.index') }}">{{ config('app.name') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar"
            aria-controls="navbar" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div id="navbar" class="collapse navbar-collapse">
        <ul class="navbar-nav">
            {{ navigation() }}
        </ul>
        <section class="ml-auto">
            <ul class="navbar-nav">
                <li class="nav-item mr-2">
                    <a class="btn btn-outline-info" href="{{ route('home.index') }}">{{ __('Login') }}
                        / {{ __('Register') }}</a>
                </li>
                <li class="nav-item js-nav-item-cart">
                    <div id="cart">
                        <cart-component></cart-component>
                    </div>
                </li>
            </ul>
        </section>
    </div>
</nav>
