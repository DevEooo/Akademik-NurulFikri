@extends('layout.app')

@section('title', $page->title ?? 'Beranda')

@section('content')

    {{-- JIKA KONTEN KOSONG --}}
    @if(empty($page->content))
        <div class="container my-5 text-center">
            <p>Belum ada konten yang disusun untuk halaman ini.</p>
        </div>
    @else

        {{-- LOOPING BLOK BUILDER (ManajemenKonten) --}}
        @foreach($page->content as $block)
            
            {{-- 1. Blok Hero / Slider (Gantikan Slider Lama) --}}
            @if($block['type'] === 'hero_banner')
                <div class="hero-section" style="background-image: url('{{ asset('storage/'.$block['data']['image']) }}'); height: 500px; background-size: cover; display: flex; align-items: center;">
                    <div class="container text-white">
                        <h1>{{ $block['data']['headline'] }}</h1>
                        <p class="lead">{{ $block['data']['sub_headline'] ?? '' }}</p>
                    </div>
                </div>

            {{-- 2. Blok Teks Biasa (Artikel/Sambutan) --}}
            @elseif($block['type'] === 'text_content')
                <section class="content-section py-5">
                    <div class="container">
                        {{-- Render Rich Text --}}
                        {!! $block['data']['text'] !!}
                    </div>
                </section>
            
            {{-- 3. Blok Grid Fitur (Contoh Fasilitas/Layanan) --}}
            @elseif($block['type'] === 'feature_grid')
                <section class="features py-5 bg-light">
                    <div class="container">
                        <div class="row">
                            @foreach($block['data']['items'] as $item)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 text-center p-3">
                                        @if(isset($item['icon']))
                                            <img src="{{ asset('storage/'.$item['icon']) }}" class="mx-auto d-block mb-3" style="height: 60px;">
                                        @endif
                                        <h4>{{ $item['title'] }}</h4>
                                        <p>{{ $item['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

            @endif
            
        @endforeach

    @endif

@endsection