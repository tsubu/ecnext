@php $nlT = 'newsletter_signup'; @endphp
<div class="newsletter-signup-block py-12" data-aos="fade-up">
    <div class="bg-indigo-600 rounded-[3.5rem] p-12 md:p-20 text-center space-y-8 relative overflow-hidden shadow-2xl shadow-indigo-600/30">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-indigo-400/20 via-transparent to-transparent"></div>
        <div class="relative z-10 max-w-2xl mx-auto space-y-6">
            <h3 class="text-3xl md:text-5xl font-black text-white tracking-tighter">{{ $settings['title'] ?? block_trans($nlT, 'default_title') }}</h3>
            <p class="text-indigo-100 text-sm font-medium leading-relaxed opacity-80">
                {{ block_trans($nlT, 'default_lead') }}
            </p>
            <form class="flex flex-col sm:flex-row gap-4 pt-4">
                <input type="email" placeholder="{{ $settings['placeholder'] ?? block_trans($nlT, 'default_placeholder') }}" class="flex-grow bg-white/10 border border-white/20 rounded-2xl px-6 py-4 text-white placeholder:text-white/40 outline-none focus:bg-white/20 transition-all font-mono text-sm">
                <button type="submit" class="bg-white text-indigo-600 px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-50 transition-colors shadow-lg shadow-black/10">{{ block_trans($nlT, 'subscribe') }}</button>
            </form>
        </div>
    </div>
</div>
