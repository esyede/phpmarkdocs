@extends('layout')

@section('body')
    <section class="layout">
        <!-- Mobile header -->
        <section class="header">
            <a class="menu-button" onclick="toggleSidenav();"><i class="fas fa-bars"></i></a>
            <a href="../{{ $version }}">
                {{ $app_name }}
            </a>
            @if (!empty($git))
                <a href="{{ $git }}" target="_blank" class="menu-edit"><i class="fas fa-pencil-alt"></i></a>
            @endif
        </section>

        <!-- Edit on GitHub button -->
        @if (!empty($git))
            <section class="edit">
                <a href="{{ $git }}" target="_blank">
                    <i class="fas fa-pencil-alt"></i>
                    <span>
                        {{ Util::lang('edit_page', 'Edit this page') }}<br>
                        <strong>{{ Util::lang('github', 'on GitHub') }}</strong>
                    </span>
                </a>
            </section>
        @endif

        <!-- Sidenav menu -->
        <section class="menu">
            <a href="../{{ $version }}">
                <img src="{{ Util::asset('images/logo.png') }}" class="logo">
                <img src="{{ Util::asset('images/logo-dark.png') }}" class="logo-dark">
            </a>
            @if (Util::config('search', true))
                <form action="search" class="search-bar">
                    <input type="search" name="q" required placeholder="Search...">
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
            {!! $content !!}

            <!-- Copyright -->
            <section class="copyright">
                <hr>
                Powered by <a href="https://github.com/esyede/phpmarkdocs" target="_blank">phpMarkDocs</a>
            </section>
        </section>
    </section>
@endsection