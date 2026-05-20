@php
    $bgColor = $settings['bg_color'] ?? '#dc2626';
    $tKey = 'countdown_timer';
    $countdownUnits = [
        block_trans($tKey, 'unit_days'),
        block_trans($tKey, 'unit_hours'),
        block_trans($tKey, 'unit_minutes'),
        block_trans($tKey, 'unit_seconds'),
    ];
@endphp

<div class="py-8" data-aos="fade-up"
     x-data="countdownBlock('{{ $settings['end_date'] ?? '2026-12-31T23:59' }}', @js($countdownUnits))">
    <div class="rounded-3xl p-8 md:p-12 text-center text-white space-y-6 shadow-xl" style="background-color: {{ $bgColor }}">
        <h4 class="text-xl md:text-2xl font-black tracking-tight">{{ $settings['title'] ?? block_trans($tKey, 'default_title') }}</h4>

        <div class="flex justify-center gap-4 md:gap-8">
            <template x-for="(row, i) in rows" :key="i">
                <div class="flex flex-col items-center">
                    <span class="text-4xl md:text-6xl font-black tabular-nums" x-text="String(row[0]).padStart(2, '0')">00</span>
                    <span class="text-[10px] font-bold opacity-70 mt-1" x-text="row[1]"></span>
                </div>
            </template>
        </div>

        @if(!empty($settings['message']))
            <p class="opacity-80 text-sm">{{ $settings['message'] }}</p>
        @endif
    </div>
</div>

<script>
function countdownBlock(endStr, unitLabels) {
    return {
        days: 0, hours: 0, mins: 0, secs: 0,
        unitLabels: Array.isArray(unitLabels) ? unitLabels : ['', '', '', ''],
        get rows() {
            return [
                [this.days, this.unitLabels[0] || ''],
                [this.hours, this.unitLabels[1] || ''],
                [this.mins, this.unitLabels[2] || ''],
                [this.secs, this.unitLabels[3] || ''],
            ];
        },
        init() {
            this.tick();
            setInterval(() => this.tick(), 1000);
        },
        tick() {
            const diff = Math.max(0, new Date(endStr).getTime() - Date.now());
            this.days  = Math.floor(diff / 86400000);
            this.hours = Math.floor((diff % 86400000) / 3600000);
            this.mins  = Math.floor((diff % 3600000) / 60000);
            this.secs  = Math.floor((diff % 60000) / 1000);
        }
    }
}
</script>
