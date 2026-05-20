{{-- 統合: プロダクトグリッド + プロダクトスクローラー --}}
@php
    $style = $settings['style'] ?? 'grid';
    $limit = $settings['limit'] ?? 8;
    $query = \App\Models\Product::where('is_active', true)->with(['defaultVariant', 'images']);
    if (!empty($settings['category_id'])) {
        $query->whereHas('categories', fn($q) => $q->where('categories.id', $settings['category_id']));
    }
    $products = $query->limit($limit)->get();
@endphp

<section class="py-12" data-aos="fade-up">
    @if(!empty($settings['title']))
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-black text-slate-900 tracking-tighter">{{ $settings['title'] }}</h3>
            @if($style === 'scroll')
                <a href="/products" class="text-xs font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-800 transition-colors">{{ block_trans('product_display', 'view_all') }}</a>
            @endif
        </div>
    @endif

    @if($style === 'scroll')
        {{-- 横スクロール --}}
        <div class="flex gap-6 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide -mx-4 px-4">
            @foreach($products as $product)
                <a href="/products/{{ $product->slug }}" class="group flex-none w-[260px] snap-start">
                    <div class="aspect-square rounded-2xl overflow-hidden bg-slate-100 mb-3">
                        @if($product->images->first())
                            <img src="{{ $product->images->first()->url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="{{ $product->name }}">
                        @endif
                    </div>
                    <h4 class="text-sm font-medium text-slate-900 group-hover:text-indigo-600 transition-colors truncate">{{ $product->name }}</h4>
                    @if($product->defaultVariant)
                        <p class="text-sm font-bold text-slate-900 mt-1">¥{{ number_format($product->defaultVariant->price) }}</p>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        {{-- グリッド --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($products as $product)
                <a href="/products/{{ $product->slug }}" class="group">
                    <div class="aspect-square rounded-2xl overflow-hidden bg-slate-100 mb-3">
                        @if($product->images->first())
                            <img src="{{ $product->images->first()->url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="{{ $product->name }}">
                        @endif
                    </div>
                    <h4 class="text-sm font-medium text-slate-900 group-hover:text-indigo-600 transition-colors truncate">{{ $product->name }}</h4>
                    @if($product->defaultVariant)
                        <p class="text-sm font-bold text-slate-900 mt-1">¥{{ number_format($product->defaultVariant->price) }}</p>
                    @endif
                </a>
            @endforeach
        </div>
    @endif

    @if($products->isEmpty())
        <div class="text-center py-12 text-sm text-slate-400">{{ block_trans('product_display', 'empty') }}</div>
    @endif
</section>
