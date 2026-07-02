@props([
    'name',
    'options' => [],
    'valueField' => 'id',
    'labelField' => 'nama',
    'placeholder' => 'Pilih Data',
    'selected' => null,
    'model' => null,
    'onSelect' => null,
    'dropup' => false,
    'searchable' => null,
    'searchThreshold' => 6,
])
<div
    x-data="{
        open:false,
        search:'',
        value:'{{ old($name, $selected) }}',
        externalValue:null,
        highlighted:0,
        selectedText:'{{ $placeholder }}',
        showSearch: false,
        items: @js(
            collect($options)->map(function($item) use ($valueField,$labelField){
                return [
                    'value' => data_get($item,$valueField),
                    'label' => data_get($item,$labelField),
                ];
            })->values()
        ),
        get filtered() {
            if (!this.search) return this.items;
            return this.items.filter(item =>
                item.label.toLowerCase()
                    .includes(this.search.toLowerCase())
            );
        },
        init() {

    @if($model)
        this.value = Alpine.evaluate( this.$el, '{{ $model }}') ?? this.value;
        const currentItem = this.items.find(
            item => item.value == this.value
        );

        if (currentItem) {
            this.selectedText = currentItem.label;
        }

        this.$watch('{{ $model }}', value => {
            this.value = value;
            const selectedItem = this.items.find(
                item => item.value == value
            );
            this.selectedText = selectedItem ? selectedItem.label : '{{ $placeholder }}';
        }
    );

        @endif

            const selectedItem = this.items.find(
                item => item.value == this.value
            );

            this.selectedText = selectedItem
                ? selectedItem.label
                : '{{ $placeholder }}';

            if ({{ $searchable === null ? 'true' : 'false' }}) {
                this.showSearch =
                    this.items.length >= {{ $searchThreshold }};
            } else {
                this.showSearch =
                    {{ $searchable ? 'true' : 'false' }};
            }

            this.$watch('open', value => {
                if (value) {
                    this.$nextTick(() => {
                        this.$refs.search?.focus();
                    });
                }
            });

        }
    }"
    @keydown.escape.window="open=false"
    class="relative">
    <input
        type="hidden"
        name="{{ $name }}"
        x-model="value">
    <button
        type="button"
        @click="open=!open"
        class="w-full flex items-center justify-between rounded-lg border border-slate-200 bg-white shadow-sm hover:border-slate-300">
        <span
            class="px-3 py-2.5 truncate"
            :class="value ? 'text-md text-slate-900 font-medium' : 'text-md text-gray-500'"
            x-text="selectedText">
        </span>
        <div class="px-3">
            <i
                data-lucide="chevron-down"
                class="w-4 h-4 text-slate-500 transition"
                :class="{ 'rotate-180': open }">
            </i>
        </div>
    </button>
    <div
        x-show="open"
        x-transition
        @click.away="open=false"
        class="absolute z-20 w-full rounded-lg border border-slate-200 bg-white shadow-xl
        {{ $dropup ? 'bottom-full mb-1' : 'top-full mt-1' }}">
        <template x-if="showSearch">
            <div class="border-b border-slate-100 p-2">
                <input
                    x-ref="search"
                    x-model="search"
                    type="text"
                    placeholder="Cari..."
                    class="w-full rounded-md border-slate-200 text-sm">
            </div>
        </template>
        <div class="max-h-60 overflow-y-auto">
            <template
                x-for="(item,index) in filtered"
                :key="item.value">
                <button
                    type="button"
                    @click="
                        value=item.value;
                        selectedText=item.label;
                        open=false;

                        @if($model)
                        $root.{{ $model }} = item.value;
                        @endif

                        {{ $onSelect }}"
                    class="flex w-full items-center justify-between px-3 py-3 hover:bg-[#E0E8FF]">
                    <span
                        class="text-sm font-medium text-slate-700"
                        x-text="item.label">
                    </span>
                    <template x-if="value == item.value">
                        <svg
                            class="w-5 h-5 text-[#1E3A8A]"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="3"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"/>
                        </svg>
                    </template>
                </button>
            </template>
        </div>
    </div>
</div>