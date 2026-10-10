@props(['chartData' => []])

@php
    $defaultPeriod = 'harian';
    $jsonData = json_encode($chartData);
@endphp

<div
    class="bg-[#F8FAFC] dark:bg-[#1E293B] rounded-xl border border-[#E2E8F0] dark:border-slate-800 transition-colors duration-200"
    x-data="chartBar({{ $jsonData }})"
>
    {{-- Header --}}
    <div
        class="px-4 py-2.5 flex items-center justify-between border-b border-[#E2E8F0]/60 dark:border-slate-800/80"
        style="background: repeating-linear-gradient(135deg, transparent, transparent 4px, rgba(0,0,0,0.02) 4px, rgba(0,0,0,0.02) 5px)"
    >
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#94A3B8] dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
            <span class="text-sm font-semibold text-[#1E293B] dark:text-white">Statistik Donasi Masuk</span>
        </div>
    </div>

    {{-- Body --}}
    <div class="bg-white dark:bg-[#0F172A] rounded-lg mx-2 my-2 sm:mx-3 sm:my-3 p-3.5 sm:p-5 border border-transparent dark:border-slate-800/80 transition-colors duration-200">
        {{-- Top row: big number + period selector --}}
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-5 sm:mb-6">
            <div>
                <p class="text-2xl sm:text-3xl font-bold text-[#1E293B] dark:text-white" x-text="formattedTotal"></p>
                <div class="flex flex-wrap items-center gap-2 mt-1">
                    <span class="inline-flex items-center gap-1.5 text-xs text-[#065F46] dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 px-2.5 py-0.5 rounded-full font-medium">
                        ✓ Realtime Kas Manajemen
                    </span>
                    <span class="text-xs text-[#64748B] dark:text-slate-400 font-medium" x-text="currentPeriodTitle"></span>
                </div>
            </div>

            {{-- Period segmented control --}}
            <div class="flex bg-[#F1F5F9] dark:bg-slate-800/80 rounded-lg p-0.5 self-start">
                <template x-for="p in periods" :key="p.key">
                    <button
                        @click="switchPeriod(p.key)"
                        :class="period === p.key ? 'bg-white dark:bg-slate-700 shadow-sm text-[#1E293B] dark:text-white font-medium' : 'text-[#64748B] dark:text-slate-400 hover:text-[#1E293B] dark:hover:text-white'"
                        class="px-2.5 sm:px-3 py-1 text-xs rounded-md transition-all focus-visible:ring-2 focus-visible:ring-[#065F46] cursor-pointer"
                        x-text="p.label"
                    ></button>
                </template>
            </div>
        </div>

        {{-- Chart area --}}
        <div class="relative" role="img" :aria-label="'Grafik batang statistik donasi ' + periodLabel">
            {{-- Gridlines --}}
            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pr-10">
                <div class="border-t border-dashed border-[#E2E8F0] dark:border-slate-800"></div>
                <div class="border-t border-dashed border-[#E2E8F0] dark:border-slate-800"></div>
                <div class="border-t border-dashed border-[#E2E8F0] dark:border-slate-800"></div>
                <div class="border-t border-dashed border-[#E2E8F0] dark:border-slate-800"></div>
                <div class="border-t border-dashed border-[#E2E8F0] dark:border-slate-800"></div>
            </div>

            {{-- Empty state when all values are zero --}}
            <div
                x-show="maxValue <= 0"
                class="absolute inset-0 flex flex-col items-center justify-center text-center p-4 z-10 pointer-events-none pr-10"
            >
                <p class="text-xs text-[#94A3B8] dark:text-slate-500">Belum ada donasi masuk pada periode ini</p>
            </div>

            <div class="flex items-end gap-1 sm:gap-2 h-[200px] pr-10 relative">
                <template x-for="(val, idx) in currentData.values" :key="period + '-' + idx">
                    <div
                        class="relative flex-1 h-full flex flex-col items-center justify-end cursor-pointer group"
                        @click="highlightedBar = idx"
                        @focus="highlightedBar = idx"
                        @mouseenter="highlightedBar = idx"
                        tabindex="0"
                        role="img"
                        :aria-label="getAriaLabel(idx)"
                    >
                        {{-- Bar --}}
                        <div
                            class="w-full relative rounded-t-md transition-all duration-300 ease-out shadow-sm"
                            :class="highlightedBar === idx ? 'bg-[#1E293B] dark:bg-emerald-500 ring-2 ring-[#065F46] dark:ring-emerald-400 shadow-md' : 'bg-gradient-to-t from-[#065F46] via-[#047857] to-[#10B981] hover:brightness-110'"
                            :style="{ height: getBarHeight(val) + '%' }"
                        >
                            {{-- Tooltip right above the bar --}}
                            <div
                                x-show="highlightedBar === idx && val > 0"
                                x-transition.opacity
                                class="absolute -top-9 left-1/2 -translate-x-1/2 bg-[#1E293B] dark:bg-slate-800 dark:border dark:border-slate-700 text-white text-[11px] font-medium px-2.5 py-1 rounded-md shadow-lg whitespace-nowrap z-20 pointer-events-none"
                            >
                                <span x-text="getTooltipText(idx)"></span>
                                <div class="absolute left-1/2 -translate-x-1/2 -bottom-1 w-2 h-2 bg-[#1E293B] dark:bg-slate-800 rotate-45"></div>
                            </div>
                        </div>

                        {{-- Tooltip when bar is 0 and hovered --}}
                        <div
                            x-show="highlightedBar === idx && (!val || val <= 0) && maxValue > 0"
                            x-transition.opacity
                            class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-[#1E293B] dark:bg-slate-800 dark:border dark:border-slate-700 text-white text-[11px] font-medium px-2.5 py-1 rounded-md shadow-lg whitespace-nowrap z-20 pointer-events-none"
                        >
                            <span x-text="getTooltipText(idx)"></span>
                            <div class="absolute left-1/2 -translate-x-1/2 -bottom-1 w-2 h-2 bg-[#1E293B] dark:bg-slate-800 rotate-45"></div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Y-axis labels (right side) --}}
            <div class="absolute right-0 top-0 bottom-0 w-10 flex flex-col justify-between text-right py-0">
                <template x-for="label in yAxisLabels" :key="label">
                    <span class="text-[10px] text-[#94A3B8] dark:text-slate-500" x-text="label"></span>
                </template>
            </div>

            {{-- X-axis labels --}}
            <div class="flex gap-1 sm:gap-2 mt-2 pr-10">
                <template x-for="(label, idx) in currentData.labels" :key="period + '-label-' + idx">
                    <div class="flex-1 text-center">
                        <span
                            class="text-[10px] sm:text-xs block truncate"
                            :class="highlightedBar === idx ? 'text-[#1E293B] dark:text-white font-medium' : 'text-[#94A3B8] dark:text-slate-400'"
                            x-text="label"
                        ></span>
                    </div>
                </template>
            </div>
        </div>

        {{-- Screen reader table --}}
        <table class="sr-only">
            <caption x-text="'Data statistik donasi ' + periodLabel"></caption>
            <thead>
                <tr>
                    <th scope="col">Periode</th>
                    <th scope="col">Nominal</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(val, idx) in currentData.values" :key="'sr-' + idx">
                    <tr>
                        <td x-text="currentData.labels[idx]"></td>
                        <td x-text="formatRupiah(val)"></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>

<script>
    window.chartBar = function chartBar(data) {
        return {
            allData: data || {},
            period: 'harian',
            highlightedBar: -1,
            periods: [
                { key: 'harian', label: 'Harian' },
                { key: 'mingguan', label: 'Mingguan' },
                { key: 'bulanan', label: 'Bulanan' },
                { key: 'tahunan', label: 'Tahunan' },
            ],

            init() {
                // Ensure data exists
            },

            get currentData() {
                return this.allData[this.period] || {
                    period_title: '',
                    labels: [],
                    subtitles: [],
                    values: []
                };
            },

            get currentPeriodTitle() {
                return this.currentData.period_title || '';
            },

            get periodLabel() {
                const found = this.periods.find(p => p.key === this.period);
                return found ? found.label : '';
            },

            get totalAmount() {
                const vals = this.currentData.values || [];
                return vals.reduce((sum, v) => sum + (parseFloat(v) || 0), 0);
            },

            get formattedTotal() {
                return this.formatRupiah(this.totalAmount);
            },

            get maxValue() {
                const vals = this.currentData.values || [];
                if (!vals.length) return 0;
                return Math.max(...vals.map(v => parseFloat(v) || 0));
            },

            get yAxisLabels() {
                const max = this.maxValue;
                if (max <= 0) {
                    return ['Rp 0', 'Rp 0', 'Rp 0', 'Rp 0', 'Rp 0'];
                }
                const steps = 4;
                const labels = [];
                for (let i = steps; i >= 0; i--) {
                    const val = (max / steps) * i;
                    labels.push(this.formatCompact(val));
                }
                return labels;
            },

            switchPeriod(key) {
                this.period = key;
                this.highlightedBar = -1;
            },

            getBarHeight(val) {
                const v = parseFloat(val) || 0;
                const max = this.maxValue;
                if (max <= 0 || v <= 0) return 0;
                return Math.max(3, Math.round((v / max) * 100));
            },

            getTooltipText(idx) {
                const val = (this.currentData.values || [])[idx] || 0;
                const sub = (this.currentData.subtitles || [])[idx] || '';
                return (sub ? sub + ': ' : '') + this.formatRupiah(val);
            },

            getAriaLabel(idx) {
                const label = (this.currentData.labels || [])[idx] || '';
                const val = (this.currentData.values || [])[idx] || 0;
                return label + ': ' + this.formatRupiah(val);
            },

            formatRupiah(num) {
                const val = Math.round(parseFloat(num) || 0);
                return 'Rp ' + val.toLocaleString('id-ID');
            },

            formatCompact(num) {
                const val = parseFloat(num) || 0;
                if (val >= 1000000000) {
                    return (val / 1000000000).toFixed(1).replace('.0', '') + 'M';
                }
                if (val >= 1000000) {
                    return (val / 1000000).toFixed(1).replace('.0', '') + 'Jt';
                }
                if (val >= 1000) {
                    return (val / 1000).toFixed(0) + 'Rb';
                }
                return 'Rp ' + val;
            }
        };
    };
</script>
