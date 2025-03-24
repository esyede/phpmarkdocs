@extends('layout')

@section('body')
    <section class="not-found">
        <section class="inner">
            <h1>404</h1>
            <h3>{{ Util::lang('not_found', 'Page not found') }}</h3>
            <a href="">
                <i class="fas fa-chevron-left"></i>
                {{ Util::lang('back', 'Back to docs') }}
            </a>
        </section>
    </section>
@endsection