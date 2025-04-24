<div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_navbar.html -->
        @include('layouts.partials.adNavBar')
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                @if(session('message'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Attention!</strong> {{ session('message') }}.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @elseif(session('message2'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Succès!</strong> {{ session('message2') }}.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @else

                @endif
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <div class="card-text">
                            <h4 class="card-title">Liste des formations</h4>
                            <a href="{{ route('newForm') }}" class="btn btn-primary addLink">Ajouter une formation</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Libellé</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($forms as $form)
                                    <tr>
                                        <td><img src="{{ $form->image }}" alt="" srcset=""></td>
                                        <td>{{ $form->libForm }}</td>
                                        <td>{{ $form->desc }}</td>
                                        <td><label class="text">{{ $form->status }}</label></td>
                                        <td>
                                            <a href="/Accepter_reservation/inscription={{ $form->id }}" class="badge badge-info">Mettre à jour</a>
                                            <a href="/Supprimer_formation/foramtion={{ $form->id }}" 
                                            class="badge badge-danger"
                                            onclick="return confirm('Souhaitez-vous réellement supprimer cette formation ?')">Supprimer</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
          <!-- content-wrapper ends -->
          <!-- partial:../../partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2021</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin template</a> from Bootstrapdash.com</span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>