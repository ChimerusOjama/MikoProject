@if(session('alert'))
    @php
        $type = session('alert')['type'] ?? 'info';
        $message = session('alert')['message'] ?? '';
        $icons = [
            'success' => 'check-circle-fill text-success',
            'error' => 'x-circle-fill text-danger',
            'info' => 'info-circle-fill text-info',
            'warning' => 'exclamation-triangle-fill text-warning',
        ];
        $iconClass = $icons[$type] ?? $icons['info'];
    @endphp

    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center p-3">
                <div class="modal-body">
                    <i class="bi bi-{{ $iconClass }}" style="font-size: 4rem;"></i>
                    <p class="mt-3">{{ $message }}</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
@endif
