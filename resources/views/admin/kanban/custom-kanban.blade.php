@props([
'buttons',
'column' => 'id',
'sortRoute' => '',
'statuses' => [],
'data' => [],
])
<x-moonshine::layout.grid>
    @foreach($statuses as $key => $title)
    <x-moonshine::layout.column colSpan="4">
        <x-moonshine::layout.box :title="$title">
            <ul x-data="kbSortable" data-parent_key="{{ $key }}">
                @if(isset($data[$key]))
                @foreach($data[$key] as $item)
                <li data-id="{{ $item->getKey() }}">
                    <x-moonshine::card
                        class="handle"
                        :title="$item->title"> <!-- Заголовок выступления -->

                        <!-- Описание выступления -->
                        <div class="p-4">
                            <p class="text-sm text-gray-600 mb-2">
                                {{ Str::limit($item->description, 100) }}
                            </p>

                            <!-- Автор выступления -->
                            @if($item->user)
                            <div class="flex items-center mt-2">
                                <span class="text-xs bg-purple-100 px-2 py-1 rounded">
                                    {{ $item->user->last_name }} {{ $item->user->first_name }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <x-slot:actions>
                            <div class="flex items-center justify-end gap-2">
                                {!! $buttons($item) !!}
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