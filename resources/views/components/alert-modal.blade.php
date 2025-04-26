@if (session('message'))
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    @switch(session('type'))
                        @case('success')
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                            @break
                        @case('error')
                            <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                            @break
                        @case('info')
                            <i class="bi bi-info-circle-fill text-info" style="font-size: 4rem;"></i>
                            @break
                        @default
                            <i class="bi bi-exclamation-circle-fill text-warning" style="font-size: 4rem;"></i>
                    @endswitch
                    <p class="mt-3">{{ session('message') }}</p>
                </div>

                <div class="modal-footer justify-content-center">
                    @if (session('type') === 'info' && session('confirm_route'))
                        <form method="POST" action="{{ session('confirm_route') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">Confirmer</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

