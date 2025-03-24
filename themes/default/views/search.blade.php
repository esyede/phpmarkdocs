@extends('layout')

@section('body')
    <section class="layout">
        <!-- Mobile header -->
        <section class="header">
            <a class="menu-button" onclick="toggleSidenav();"><i class="fas fa-bars"></i></a>
            <a href="../{{ $version }}">{{ $app_name }}</a>
            @if (!empty($git))
                <a href="{{ $git }}" target="_blank" class="menu-edit"><i class="fas fa-pencil-alt"></i></a>
            @endif
        </section>

        <!-- Sidenav menu -->
        <section class="menu">
            <a href="../{{ $version }}">
                <img src="{{ Util::asset('images/logo.png') }}" class="logo">
                <img src="{{ Util::asset('images/logo-dark.png') }}" class="logo-dark">
            </a>
            @if (Util::config('search', true))
                <form action="search" class="search-bar">
                    <input value="{{ $search }}" type="search" name="q" required
                        placeholder="{{ Util::lang('search', 'Search...') }}">
                </form>
            @endif
            <div class="dropdown">
                <a class="dropdown-toggle" data-bs-toggle="dropdown">
                    {{ $version }}
                    @if ($version === $latest)
                        <span>{{ Util::lang('latest', '(latest)') }}</span>
                    @endif
                </a>
                <ul class="dropdown-menu">
                    @foreach ($versions as $v)
                        <li>
                            <a href="../{{ $v }}" class="dropdown-item">
                                {{ $v }}
                                @if ($v === $latest)
                                    <span>{{ Util::lang('latest', '(latest)') }}</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            {!! $menu !!}
        </section>

        <!-- Content -->
        <section class="content">
            <h1>{{ Util::lang('search_results', 'Search Results') }}</h1>
            <h6>{{ Util::lang('searching_for', 'Searching for:') }} {{ $search }}</h6>

            <!-- Search results -->
            @foreach ($results as $item)
                <div class="item">
                    <h5><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></h5>
                    @foreach ($item['results'] as $result)
                        <p>{!! strip_tags($result) !!}</p>
                    @endforeach
                </div>
                <hr>
            @endforeach

            <!-- Empty message -->
            @if (empty($results))
                {{ Util::lang('no_results', 'No results found.') }}
            @endif

            <!-- Copyright -->
            <section class="copyright">
                @if (empty($results))
                    <hr>
                @endif
                Powered by <a href="https://github.com/esyede/phpmarkdocs" target="_blank">phpMarkDocs</a>
            </section>
        </section>
    </section>
@endsection