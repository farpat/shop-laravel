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
                @auth
                    <li class="navbar-text mr-2 p-0">
                        <form class="form-inline" action="{{ route('logout') }}" method="post">
                            @csrf
                            <span>{{ Auth::user()->name }}</span>
                            <button class="btn btn-link text-danger">{{ __('Logout') }}</button>
                        </form>
                    </li>
                @endauth
                @guest
                    <li class="nav-item mr-2">
                        <a class="btn btn-outline-info" href="{{ route('register') }}">{{ __('Register') }}</a>
                        <a class="btn btn-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                @endguest
                <li id="cart" class="nav-item js-nav-item-cart">
                    <cart-component></cart-component>
                </li>
            </ul>
        </section>
    </div>
</nav>
