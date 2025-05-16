<script>
    function openCancelModal(id) {
        const form = document.getElementById('cancelForm');
        form.action = `/Annuler_reservation/${id}`;
        const cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));
        cancelModal.show();
    }
</script>
