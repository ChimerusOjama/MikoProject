<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('message'))
            const modal = new bootstrap.Modal(document.getElementById('alertModal'));
            modal.show();
        @endif
    });
</script>

<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success') || session('error') || session('warning'))
            const modal = new bootstrap.Modal(document.getElementById('exampleModal'));
            modal.show();
        @endif
    });
</script> -->
