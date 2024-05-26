@push('js')
<script src="https://cdn.tiny.cloud/1/lj3niasi9s3d111t7rdmcli3m6lwbc6k3gs85xciou7c0oyn/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector : "textarea:not(.mceNoEditor)",
        plugins: "advlist autolink lists link image charmap preview anchor pagebreak",
        toolbar_mode: "floating",
        toolbar: 'undo redo styleselect bold italic alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
    });
</script>
@endpush