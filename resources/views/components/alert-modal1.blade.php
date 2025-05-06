@if(session('success') || session('error') || session('warning') || session('info'))
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-square">
            <div class="modal-content text-center p-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title w-100" id="exampleModalLabel">Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body" id="modalMessage">
                    @php
                        $type = session('success') ? 'success' : (session('error') ? 'error' : 'warning');
                        $message = session($type);
                    @endphp

                    @switch($type)
                        @case('success')
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                            @break
                        @case('error')
                            <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                            @break
                        @case('warning')
                            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 4rem;"></i>
                            @break
                        @case('info')
                            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 4rem;"></i>
                            <p class="mt-3 fs-5">&nbsp;{{ $message }}</p>
                            </div>
                            <div class="modal-footer justify-content-center border-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                            @break
                    @endswitch

                <p class="mt-3 fs-5">&nbsp;{{ $message }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
@endif
