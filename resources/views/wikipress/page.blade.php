@extends('layouts.app')

@section('content')

    {{-- Render Builder Blocks --}}
    @if($page->content)
        @foreach($page->content as $block)
        
            @if($block['type'] === 'hero_banner')
                <div class="hero" style="background-image: url({{ asset('storage/'.$block['data']['image']) }})">
                    <h1>{{ $block['data']['headline'] }}</h1>
                </div>

            @elseif($block['type'] === 'text_content')
                <div class="container py-5">
                    {!! $block['data']['text'] !!}
                </div>

            @endif
            
        @endforeach
    @endif

@endsection