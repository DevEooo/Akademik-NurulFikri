<!DOCTYPE html>
<html lang="id">
<head>
    <title>{{ $page->title }} - App1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Agar sidebar tetap diam saat discroll (Sticky Sidebar) */
        .sticky-sidebar {
            position: sticky;
            top: 20px; /* Jarak dari atas */
            height: fit-content;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

   <nav class="bg-white shadow z-50 relative sticky top-0">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        
        <a href="/" class="flex items-center gap-3 group">
            <img src="{{ asset('img/logo.png') }}" 
                 alt="Logo STT NF" 
                 class="h-10 w-auto object-contain transition group-hover:scale-105">
            
            <div class="flex flex-col">
                <span class="font-bold text-xl text-blue-900 leading-tight">Nurul Fikri</span>
                <span class="text-xs text-gray-500 font-medium tracking-wide">Sistem Akademik</span>
            </div>
        </a>
        
        {{-- MENU KANAN (Desktop) --}}
        <div class="hidden md:flex items-center space-x-6">
             <a href="/" class="text-gray-600 hover:text-blue-600 font-medium text-sm">Beranda</a>
             
             <a href="/portal/login" class="flex items-center gap-2 text-blue-600 font-bold border border-blue-600 px-4 py-2 rounded-full hover:bg-blue-600 hover:text-white transition text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <span>Login</span>
             </a>
        </div>

    </div>
</nav>

    {{-- WRAPPER UTAMA: Grid 12 Kolom --}}
    <div class="container mx-auto px-4 py-8 grow">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <aside class="lg:col-span-3 hidden lg:block">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky-sidebar">
                    <h3 class="font-bold text-gray-800 text-lg mb-4 uppercase tracking-wider text-sm border-b pb-2">
                        Menu Navigasi
                    </h3>
                    
                    {{-- List Utama (Parent) dengan Bullet Disc --}}
                    <ul class="list-disc pl-5 space-y-3 text-gray-700">
                        @foreach(\App\Models\ManajemenKonten::whereNull('id_parent')->where('is_published', true)->orderBy('title')->get() as $menu)
                            
                            <li class="marker:text-blue-500"> {{-- Marker Parent warna Biru --}}
                                {{-- Link Parent --}}
                                <a href="{{ url($menu->slug) }}" 
                                   class="hover:text-blue-600 hover:underline transition {{ Request::is($menu->slug) ? 'font-bold text-blue-700' : '' }}">
                                    {{ $menu->title }}
                                </a>

                                {{-- Jika punya Child, buat nested list --}}
                                @if($menu->children->count() > 0)
                                    <ul class="list-[circle] pl-5 mt-2 space-y-1 text-sm text-gray-500">
                                        @foreach($menu->children as $child)
                                            <li class="marker:text-gray-300"> {{-- Marker Child warna Abu --}}
                                                <a href="{{ url($child->slug) }}" 
                                                   class="hover:text-blue-600 transition {{ Request::is($child->slug) ? 'font-bold text-blue-600 underline' : '' }}">
                                                    {{ $child->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>

                        @endforeach
                    </ul>
                </div>
            </aside>

            {{-- MAIN CONTENT (KANAN - Lebar 9/12) --}}
            <main class="lg:col-span-9">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden min-h-[500px]">
                    
                    {{-- Judul Halaman di Atas Konten --}}
                    @if($page->slug !== 'home') 
                        <div class="px-8 py-6 border-b bg-gray-50">
                            <h1 class="text-3xl font-bold text-gray-800">{{ $page->title }}</h1>
                             {{-- Breadcrumb Opsional --}}
                             <div class="text-sm text-gray-500 mt-2">
                                <a href="/" class="hover:underline">Beranda</a> 
                                @if($page->parent) / <a href="{{ url($page->parent->slug) }}" class="hover:underline">{{ $page->parent->title }}</a> @endif
                                / {{ $page->title }}
                             </div>
                        </div>
                    @endif

                    {{-- LOOPING BLOK BUILDER --}}
                    <div>
                        @if($page->konten)
                            @foreach($page->konten as $block)
                            
                                {{-- 1. HERO SECTION (Full Width di dalam container main) --}}
                                @if(in_array($block['type'], ['hero', 'hero_banner']))
                                    <div class="relative h-64 md:h-96 w-full overflow-hidden group">
                                        @php
                                            // Support multiple field names coming from different builders
                                            $heroImage = $block['data']['image'] ?? $block['data']['hero_image'] ?? null;
                                            $heroHeading = $block['data']['heading'] ?? $block['data']['headline'] ?? '';
                                            $heroSub = $block['data']['subheading'] ?? $block['data']['sub_headline'] ?? $block['data']['subheadline'] ?? '';
                                            $heroButtonUrl = $block['data']['button_url'] ?? $block['data']['cta_url'] ?? null;
                                            $heroButtonText = $block['data']['button_text'] ?? $block['data']['cta_text'] ?? null;
                                        @endphp

                                        @if($heroImage)
                                            <img src="{{ asset('storage/'.$heroImage) }}" class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105">
                                            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                                        @endif

                                        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4 text-white">
                                            <h2 class="text-3xl md:text-5xl font-bold mb-2">{{ $heroHeading }}</h2>
                                            @if($heroSub)
                                                <p class="text-lg text-gray-200">{{ $heroSub }}</p>
                                            @endif
                                            @if($heroButtonUrl)
                                                <a href="{{ $heroButtonUrl }}" class="mt-6 inline-block bg-yellow-500 text-black px-6 py-3 rounded font-bold hover:bg-yellow-400">
                                                    {{ $heroButtonText ?? 'Learn More' }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                {{-- 2. TEXT + IMAGE --}}
                                @elseif($block['type'] === 'text_image')
                                    <div class="p-8">
                                        <div class="flex flex-col md:flex-row gap-8 items-start">
                                            @if($block['data']['layout'] == 'left')
                                                <img src="{{ asset('storage/'.$block['data']['image']) }}" class="w-full md:w-1/3 rounded-lg shadow-md">
                                            @endif
                                            
                                            <div class="prose max-w-none w-full text-gray-700 leading-relaxed">
                                                {!! $block['data']['text'] !!}
                                            </div>

                                            @if($block['data']['layout'] == 'right')
                                                <img src="{{ asset('storage/'.$block['data']['image']) }}" class="w-full md:w-1/3 rounded-lg shadow-md">
                                            @endif
                                        </div>
                                    </div>

                                {{-- 3. TEXT ONLY (Tambahan) --}}
                                @elseif($block['type'] === 'text_content')
                                    <div class="p-8 prose max-w-none text-gray-700">
                                        {!! $block['data']['text'] !!}
                                    </div>

                                {{-- 4. FEATURES GRID --}}
                                @elseif($block['type'] === 'features')
                                    <div class="p-8 bg-gray-50">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                            @foreach($block['data']['items'] as $item)
                                                <div class="bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition text-center">
                                                    @if(isset($item['icon']))
                                                        <img src="{{ asset('storage/'.$item['icon']) }}" class="h-12 w-12 mx-auto mb-4 object-contain">
                                                    @endif
                                                    <h4 class="font-bold text-lg mb-2 text-gray-800">{{ $item['title'] }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $item['description'] }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                @endif
                            @endforeach
                        @else
                            <div class="p-12 text-center text-gray-500">
                                <p>Halaman ini belum memiliki konten.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- FOOTER --}}
    <!-- <footer class="bg-gray-800 text-gray-400 text-center py-6 mt-auto">
        <div class="container mx-auto">
            <p>&copy; {{ date('Y') }} Kampus App. Powered by Filament & Laravel.</p>
        </div>
    </footer> -->

</body>
</html>