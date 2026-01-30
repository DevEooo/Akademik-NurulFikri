<!DOCTYPE html>
<html lang="id">
<head>
    <title>{{ $page->title }} | Sistem Akademik Nurul Fikri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Agar sidebar tetap diam saat discroll (Sticky Sidebar) */
        .sticky-sidebar {
            position: sticky;
            top: 20px; /* Jarak dari atas */
            height: fit-content;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 fl
ex flex-col min-h-screen">

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

    <div class="container mx-auto px-4 py-8 grow">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <aside class="lg:col-span-3 hidden lg:block">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky-sidebar">
        <h3 class="font-bold text-gray-800 text-lg mb-4 uppercase tracking-wider text-sm border-b pb-2">
            Menu Navigasi
        </h3>
        
        <ul class="space-y-2 text-gray-700">
            @foreach(\App\Models\ManajemenKonten::whereNull('id_parent')->where('is_published', true)->orderBy('title')->get() as $menu)
                
                @php
                    $isActiveParent = Request::is($menu->slug);
                    $hasActiveChild = $menu->children->contains(fn($child) => Request::is($child->slug));
                    $isOpen = $isActiveParent || $hasActiveChild ? 'true' : 'false';
                @endphp

                <li x-data="{ expanded: {{ $isOpen }} }" class="relative">
                    
                    <div class="flex items-center justify-between w-full group">
                      
                        <a href="{{ url($menu->slug) }}" 
                           class="grow py-2 hover:text-blue-600 transition {{ $isActiveParent ? 'font-bold text-blue-700' : '' }}">
                           {{ $menu->title }}
                        </a>

                        @if($menu->children->count() > 0)
                            <button @click="expanded = !expanded" 
                                    class="p-1 rounded hover:bg-gray-100 text-gray-400 focus:outline-none transition-transform duration-200"
                                    :class="expanded ? 'rotate-180 text-blue-500' : ''">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        @endif
                    </div>

                    @if($menu->children->count() > 0)
                        <ul x-show="expanded" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="pl-4 mt-1 space-y-1 border-l-2 border-gray-100 ml-2">
                            
                            @foreach($menu->children as $child)
                                <li class="list-disc ml-4 marker:text-gray-300 hover:marker:text-blue-500">
                                    <a href="{{ url($child->slug) }}" 
                                       class="block py-1 text-sm hover:text-blue-600 hover:underline transition {{ Request::is($child->slug) ? 'font-bold text-blue-600 underline' : 'text-gray-500' }}">
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

            <main class="lg:col-span-9">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden min-h-[500px]">
                    
                    @if($page->slug !== 'home') 
                        <div class="px-8 py-6 border-b bg-gray-50">
                            <h1 class="text-3xl font-bold text-gray-800">{{ $page->title }}</h1>
                             <div class="text-sm text-gray-500 mt-2">
                                <a href="/" class="hover:underline">Beranda</a> 
                                @if($page->parent) / <a href="{{ url($page->parent->slug) }}" class="hover:underline">{{ $page->parent->title }}</a> @endif
                                / {{ $page->title }}
                             </div>
                        </div>
                    @endif

                    <div>
                        @if($page->konten)
                            @foreach($page->konten as $block)
                            
                                @if(in_array($block['type'], ['hero', 'hero_banner']))
                                    <div class="relative h-64 md:h-96 w-full overflow-hidden group">
                                        @php
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

                                @elseif($block['type'] === 'text_content')
                                    <div class="p-8 prose max-w-none text-gray-700">
                                        {!! $block['data']['text'] !!}
                                    </div>

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