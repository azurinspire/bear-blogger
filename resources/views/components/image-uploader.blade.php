<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">

<input type="file" name="filepond" multiple>

<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script>
FilePond.registerPlugin(FilePondPluginImagePreview);
const inputElement = document.querySelector('input[type="file"]');
const pond = FilePond.create(inputElement, {
    server: {
        url: '{{ route('bear-blogger.upload', $post->slug) }}',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }
});
</script>
