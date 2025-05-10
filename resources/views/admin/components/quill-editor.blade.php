@props([
'name',
'value' => '',
'label' => '',
'required' => false,
'disabled' => false,
])

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
@endpush

<div id="editor">
    <h2>Demo Content</h2>
    <p>Preset build with <code>snow</code> theme, and some common formats.</p>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<script>
    const quill = new Quill('#editor', {
        modules: {
            toolbar: [
                [{
                    header: [1, 2, false]
                }],
                ['bold', 'italic', 'underline'],
                ['image', 'code-block'],
            ],
        },
        placeholder: 'Compose an epic...',
        theme: 'snow', // or 'bubble'
    });
</script>
@endpush