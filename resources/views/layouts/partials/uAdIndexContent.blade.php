<div id="page-wrapper" >
        <!-- Modal -->
        <x-alert-modal1 />
    <!-- Modal end -->
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Mes reservations</h2>   
                <h5>Bienvenu {{ Auth::user()->name }} , gérez vos reservations ici. </h5>
            </div>
        </div>
            <!-- /. ROW  -->
            <hr />
        <!-- /. ROW  -->
        <div class="row">
            <div class="col-md-12">
                    <!--    Hover Rows  -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Liste des reservations
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Formation</th>
                                        <th>Montant à payer</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inscShow as $oneInscShow)
                                        <tr>
                                            <td>{{ $oneInscShow->choixForm }}</td>
                                            <td>{{ $oneInscShow->montant }}</td>
                                            <td>{{ $oneInscShow->status }}</td>
                                            <td>
                                                <a href="#" class="btn btn-success square-btn-adjust">Finaliser l'inscription</a>
                                                <a href="#"
                                                class="btn btn-danger square-btn-adjust"
                                                onclick="event.preventDefault(); confirmAnnulation({{ $oneInscShow->id }});">
                                                    Annuler
                                                </a>

                                                <form id="confirmForm" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End  Hover Rows  -->
            </div>
        </div>
    </div>
            
</div>