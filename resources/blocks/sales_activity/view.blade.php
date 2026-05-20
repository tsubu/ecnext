@php
    $contextProduct = $contextProduct ?? null;
    $contextCategory = $contextCategory ?? null;
    $tKey = 'sales_activity';

    $data = \App\Support\SalesActivityBlockData::resolve($settings ?? [], $contextProduct, $contextCategory);
    $scope = $data['scope'];
    $hours = $data['hours'];
    $style = $data['style'];
    $title = $data['title'];
    $totalQuantity = $data['totalQuantity'];
    $recentItems = $data['recentItems'];

    if ($title === null || $title === '') {
        $title = match ($scope) {
            'store' => block_trans($tKey, 'default_title_store'),
            'category' => block_trans($tKey, 'default_title_category'),
            'product' => block_trans($tKey, 'default_title_product'),
            default => block_trans($tKey, 'default_title'),
        };
    }

    $imageUrl = static function ($product) {
        if (! $product) {
            return null;
        }
        $img = $product->images->first();

        return $img && $img->file_path ? asset('storage/' . $img->file_path) : null;
    };
@endphp

@if($totalQuantity > 0)
    <div class="sales-activity-block my-4" data-aos="fade-up">
        @if($style !== 'badge')
            <h4 class="text-lg font-bold mb-3 text-slate-900">{{ $title }}</h4>
        @endif

        @if($style === 'badge')
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-600 rounded-full text-sm font-bold border border-red-100">
                <svg class="w-4 h-4 animate-pulse shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                <span>
                    @if($scope === 'product')
                        {!! sprintf(block_trans($tKey, 'badge_product_html'), e($hours), '<strong>'.e($totalQuantity).'</strong>') !!}
                    @else
                        {!! sprintf(block_trans($tKey, 'badge_orders_html'), e($hours), '<strong>'.e($totalQuantity).'</strong>') !!}
                    @endif
                </span>
            </div>
        @elseif($style === 'ticker' && $recentItems->count() > 0)
            <div class="bg-slate-50 rounded-lg p-3 text-sm text-slate-600 flex overflow-hidden whitespace-nowrap border border-slate-100">
                <span class="font-bold text-red-500 mr-4 shrink-0">HOT</span>
                <div class="animate-marquee inline-block">
                    @foreach($recentItems as $item)
                        <span class="mx-4">
                            @if($scope !== 'product')
                                @php $p = $item->variant?->product; @endphp
                                @php
                                    $link = $p
                                        ? '<a href="'.e(url('/products/'.$p->slug)).'" class="text-indigo-600 hover:underline">'.e($item->product_name).'</a>'
                                        : '<span class="font-medium">'.e($item->product_name).'</span>';
                                    $tickerLine = sprintf(block_trans($tKey, 'ticker_store_bought_html'), e($item->created_at->diffForHumans()), $link);
                                @endphp
                                {!! $tickerLine !!}
                            @else
                                {{ sprintf(block_trans($tKey, 'ticker_product_only'), e($item->created_at->diffForHumans())) }}
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>
            <style>
                @keyframes marquee {
                    0% { transform: translateX(100%); }
                    100% { transform: translateX(-100%); }
                }
                .animate-marquee {
                    animation: marquee 22s linear infinite;
                }
            </style>
        @elseif($style === 'card')
            <div class="bg-white border-2 border-red-100 rounded-xl p-4 shadow-sm relative overflow-hidden">
                <div class="absolute -right-6 -top-6 text-red-50 opacity-50 pointer-events-none">
                    <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                </div>
                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-500 font-medium">{{ sprintf(block_trans($tKey, 'card_subtitle'), e($hours)) }}</p>
                        <p class="text-3xl font-black text-slate-900 mt-1">{{ $totalQuantity }} <span class="text-base font-medium text-slate-500">{{ block_trans($tKey, 'unit_piece') }}</span></p>
                    </div>
                    @if($scope === 'product' && $recentItems->count() > 0)
                        <ul class="text-right text-xs text-slate-600 space-y-1 max-w-xs sm:ml-auto">
                            @foreach($recentItems->take(5) as $item)
                                <li>{{ sprintf(block_trans($tKey, 'card_line_quantity'), e($item->created_at->diffForHumans()), e($item->quantity)) }}</li>
                            @endforeach
                        </ul>
                    @elseif($scope !== 'product' && $recentItems->count() > 0)
                        <div class="text-right">
                            <p class="text-xs text-slate-400 mb-1">{{ block_trans($tKey, 'recent_products_heading') }}</p>
                            <div class="flex -space-x-2 justify-end flex-wrap gap-y-2">
                                @php $shown = 0; @endphp
                                @foreach($recentItems->unique(fn ($i) => $i->variant?->product_id)->take(4) as $item)
                                    @php $product = $item->variant?->product; $url = $imageUrl($product); @endphp
                                    @if($url)
                                        <img class="w-10 h-10 rounded-full border-2 border-white object-cover shadow-sm" src="{{ $url }}" alt="{{ $product->name ?? '' }}" loading="lazy">
                                        @php $shown++; @endphp
                                    @endif
                                @endforeach
                                @if($shown === 0)
                                    <span class="text-sm font-medium text-slate-500">{{ sprintf(block_trans($tKey, 'recent_products_more'), e($recentItems->first()->product_name)) }}</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endif
