@php
    $alignment = $settings['alignment'] ?? 'left';
    $isLeft = $alignment === 'left';
@endphp

<div class="relative overflow-hidden group" data-aos="fade-up">
    <div class="grid grid-cols-1 lg:grid-cols-2 lg:min-h-[600px] gap-0">
        <!-- Visual Column -->
        <div class="{{ $isLeft ? 'lg:order-1' : 'lg:order-2' }} relative h-[400px] lg:h-auto overflow-hidden">
            <img src="{{ $settings['image_url'] ?? 'https://images.unsplash.com/photo-1592078615290-033ee584e267?auto=format&fit=crop&q=80&w=1200' }}" class="w-full h-full object-cover transition-transform duration-[8s] group-hover:scale-110">
            <!-- Glass Overlay -->
            <div class="absolute inset-0 bg-indigo-900/10 group-hover:bg-indigo-900/0 transition-colors duration-700"></div>
        </div>

        <!-- Content Column -->
        <div class="{{ $isLeft ? 'lg:order-2' : 'lg:order-1' }} bg-white flex flex-col justify-center p-12 md:p-24 space-y-8 relative">
            <!-- Decorative Accent -->
            <div class="absolute top-20 {{ $isLeft ? 'right-10' : 'left-10' }} text-[80px] font-black text-slate-50 select-none pointer-events-none transform -rotate-12">STITCH</div>
            
            <div class="relative z-10 space-y-6">
                <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 text-[9px] font-black uppercase tracking-widest rounded-md border border-indigo-100">
                    {{ block_trans('split_banner', 'eyebrow') }}
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter leading-tight italic">
                    {{ $settings['headline'] ?? 'The Intersection of Art and Logic.' }}
                </h2>
                <p class="text-lg text-slate-500 leading-relaxed max-w-lg">
                    {{ $settings['description'] ?? '' }}
                </p>
                
                @if(!empty($settings['button_text']) && !empty($settings['button_url']))
                    <div class="pt-6">
                        <a href="{{ $settings['button_url'] }}" class="inline-flex items-center gap-4 text-xs font-black text-slate-900 uppercase tracking-[0.2em] group/link">
                            <span class="border-b-2 border-slate-900 pb-1 group-hover/link:border-indigo-600 group-hover/link:text-indigo-600 transition-all">{{ $settings['button_text'] }}</span>
                            <div class="w-10 h-[1px] bg-slate-200 group-hover/link:w-16 group-hover/link:bg-indigo-600 transition-all"></div>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
