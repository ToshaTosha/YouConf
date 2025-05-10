@props([
'name',
'value' => '',
'label' => '',
'required' => false,
'disabled' => false,
'uploadUrl' => route('moonshine.quill.upload'),
'uploadDir' => 'quill-uploads'
])

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar,
    .ql-container {
        border-color: rgb(209 213 219) !important;
    }

    .dark .ql-toolbar,
    .dark .ql-container {
        border-color: rgb(75 85 99) !important;
        background: rgb(31 41 55);
    }

    .dark .ql-editor {
        color: white;
    }
</style>
@endpush

<div class="mb-4" x-data="quillEditor('{{ $name }}', '{{ $value }}')" x-init="init()">
    @if($label)
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        {{ $label }}
        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>
    @endif

    <div id="editor-{{ $name }}" style="min-height: 200px;">{!! $value !!}</div>
    <textarea name="{{ $name }}" style="display: none;">{{ $value }}</textarea>
    <input type="file" id="file-upload-{{ $name }}" style="display: none;" accept="image/*">
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quill = new Quill('#editor-{{ $name }}', {
            modules: {
                toolbar: {
                    container: [
                        [{
                            header: [1, 2, 3, false]
                        }],
                        ['bold', 'italic', 'underline'],
                        ['image', 'code-block'],
                        ['link'],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }]
                    ],
                    handlers: {
                        image: function() {
                            document.getElementById('file-upload-{{ $name }}').click();
                        }
                    }
                }
            },
            placeholder: 'Введите содержимое...',
            theme: 'snow'
        });

        // Устанавливаем начальное значение
        quill.root.innerHTML = `{!! $value !!}`;

        // Обновляем скрытое textarea при изменении содержимого
        quill.on('text-change', function() {
            const editorContent = quill.root.innerHTML;
            document.querySelector(`textarea[name="{{ $name }}"]`).value = editorContent;
        });

        // Обработка загрузки файлов
        document.getElementById('file-upload-{{ $name }}').addEventListener('change', function(e) {
            if (e.target.files.length === 0) return;

            const file = e.target.files[0];
            const formData = new FormData();
            formData.append('file', file);
            formData.append('dir', '{{ $uploadDir }}');

            fetch('{{ $uploadUrl }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.url) {
                        const range = quill.getSelection();
                        quill.insertEmbed(range.index, 'image', data.url);
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                });
        });
    });
</script>
@endpush