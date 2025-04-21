@props([
'buttons',
'column' => 'id',
'sortRoute' => '',
'statuses' => [],
'data' => [],
])
<x-moonshine::layout.grid>
    @php
    // Преобразуем и группируем данные
    $groupedPerformances = [];

    foreach ($data->first() as $schedule) {
    // Пропускаем записи без выступления
    if (!isset($schedule['performance'])) {
    logger()->warning('Пропущено расписание без выступления', ['schedule' => $schedule]);
    continue;
    }

    $sectionId = $schedule['performance']['section_id'];

    if (!isset($groupedPerformances[$sectionId])) {
    $groupedPerformances[$sectionId] = [];
    }

    $groupedPerformances[$sectionId][] = [
    'id' => $schedule['performance']['id'],
    'title' => $schedule['performance']['title'],
    'description' => $schedule['performance']['description'],
    'section_id' => $sectionId,
    'user' => [
    'id' => $schedule['performance']['user_id'],
    'full_name' => 'Автор' // Замените на реальное поле, если есть
    ],
    'schedule' => [
    'id' => $schedule['id'],
    'date' => $schedule['date'],
    'start_time' => $schedule['start_time'],
    'end_time' => $schedule['end_time'],
    'location_id' => $schedule['location_id']
    ]
    ];
    }

    logger()->info('Сгруппированные данные:', $groupedPerformances);
    @endphp

    @foreach($statuses as $sectionId => $title)
    <x-moonshine::layout.column colSpan="4">
        <x-moonshine::layout.box :title="$title">
            <ul x-data="kbSortable" data-parent_key="{{ $sectionId }}">
                @if(isset($groupedPerformances[$sectionId]))
                @foreach($groupedPerformances[$sectionId] as $performance)
                <li data-id="{{ $performance['id'] }}">
                    <x-moonshine::card class="handle" :title="$performance['title']">
                        <div class="p-4">
                            <div class="text-sm text-gray-600 mb-2">
                                {{ $performance['schedule']['date'] }}
                                {{ $performance['schedule']['start_time'] }} -
                                {{ $performance['schedule']['end_time'] }}
                            </div>
                            <p class="text-sm text-gray-500">
                                {{ Str::limit($performance['description'], 100) }}
                            </p>
                        </div>

                        <div class="px-4 pb-4">
                            <span class="text-xs bg-purple-100 px-2 py-1 rounded">
                                {{ $performance['user']['full_name'] }}
                            </span>
                        </div>

                        <x-slot:actions>
                            <div class="flex items-center justify-end gap-2">
                                {!! $buttons($data->first()->firstWhere('performance.id', $performance['id'])) !!}
                            </div>
                        </x-slot:actions>
                    </x-moonshine::card>
                    <hr class="divider" />
                </li>
                @endforeach
                @endif
            </ul>
        </x-moonshine::layout.box>
    </x-moonshine::layout.column>
    @endforeach
</x-moonshine::layout.grid>

<script>
    function kbSortable() {
        return {
            init() {
                Sortable.create(this.$el, {
                    group: {
                        name: 'nested'
                    },
                    handle: '.handle',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    dataIdAttr: 'data-id',

                    onSort: async function(evt) {
                        let formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('id', evt.item.dataset.id);
                        formData.append('parent', evt.to.dataset.parent_key);
                        formData.append('index', evt.newIndex);
                        formData.append('data', this.toArray());

                        await fetch('{{ $sortRoute }}', {
                            body: formData,
                            method: "post",
                        })
                    }
                });
            }
        }
    }
</script>