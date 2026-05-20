<div class="flex justify-center items-center" style="height: {{ $settings['height'] ?? 80 }}px">
    @if(($settings['show_line'] ?? 'no') === 'yes')
        <div class="w-16 h-px bg-slate-200 rounded-full"></div>
    @endif
</div>
