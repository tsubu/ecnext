@php
    $style = $settings['style'] ?? 'primary';
    $size = $settings['size'] ?? 'medium';
    $align = $settings['align'] ?? 'center';

    $alignClass = match($align) {
        'left' => 'text-left',
        'right' => 'text-right',
        default => 'text-center',
    };

    $sizeClass = match($size) {
        'small' => 'px-5 py-2 text-xs',
        'large' => 'px-10 py-5 text-sm',
        default => 'px-8 py-4 text-xs',
    };

    $styleClass = match($style) {
        'danger' => 'bg-red-600 text-white hover:bg-red-700 shadow-lg shadow-red-600/20',
        'secondary' => 'bg-slate-800 text-white hover:bg-slate-700',
        'outline' => 'bg-transparent text-indigo-600 border-2 border-indigo-600 hover:bg-indigo-600 hover:text-white',
        'text' => 'bg-transparent text-indigo-600 hover:text-indigo-800 underline underline-offset-4',
        default => 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-600/20',
    };
@endphp

<div class="py-4 {{ $alignClass }}" data-aos="fade-up">
    <a href="{{ $settings['url'] ?? '#' }}"
       class="inline-block {{ $sizeClass }} {{ $styleClass }} font-black uppercase tracking-widest rounded-2xl transition-all duration-300">
        {{ $settings['label'] ?? block_trans('button_cta', 'default_label') }}
    </a>
</div>
