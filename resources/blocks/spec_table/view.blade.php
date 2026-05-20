<section class="py-12" data-aos="fade-up">
    @if(!empty($settings['title']))
        <h3 class="text-2xl font-black text-slate-900 tracking-tighter mb-8 text-center">{{ $settings['title'] }}</h3>
    @endif

    <div class="max-w-3xl mx-auto bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm">
        <table class="w-full">
            @for($i = 1; $i <= 6; $i++)
                @if(!empty($settings["r{$i}_label"]))
                    <tr class="{{ $i % 2 === 0 ? 'bg-slate-50/50' : 'bg-white' }} border-b border-slate-100 last:border-b-0">
                        <th class="px-8 py-5 text-left w-1/3">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ $settings["r{$i}_label"] }}</span>
                        </th>
                        <td class="px-8 py-5 text-sm text-slate-700 leading-relaxed">
                            {{ $settings["r{$i}_value"] ?? '—' }}
                        </td>
                    </tr>
                @endif
            @endfor
        </table>
    </div>
</section>
