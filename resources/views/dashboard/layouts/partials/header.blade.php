<nav class="navbar navbar-expand-md navbar-dark bg-dark text-bg-dark shadow-sm">
	<div class="container">
		<a class="navbar-brand" href="{{ config('app.url') }}">
			<img src="{{ asset('img/horizontal_brand.svg') }}" class="logo" alt="{{ config('app.name') }}">
		</a>

		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link mr-4 text-center lh-1" aria-current="page" href="{{ route('site.index') }}">INÍCIO</a>
                </li>
            </ul>
			<ul class="navbar-nav ms-auto">
                @if(Route::has('login'))
                    @auth
                        <li class="nav-item dropdown">
                            @php
                                $userFullName = explode(' ', auth()->user()->name);
                                $userFirstName = reset($userFullName);
                                $userLastName = end($userFullName);
                            @endphp
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=808080&color=CCCCCC&length=2&bold=true&format=svg" class="avatar rounded-circle me-1">
                                {{ $userFirstName }} {{ $userLastName }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                <a class="dropdown-item" href="{{ url('/dashboard') }}">Dashboard</a>

                                @if(Route::has('register'))
                                    @can('all')
                                        <a class="dropdown-item" href="{{ route('register') }}">Cadastrar Novo Usuário</a>
                                    @endcan
                                @endif

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Entrar</a>
                        </li>

                        @if(Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @endauth
				@endif
			</ul>
		</div>
	</div>
</nav>
