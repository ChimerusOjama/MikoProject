@section('title', 'Tableau de Bord')
@section('page-title', 'Tableau de Bord')
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Accueil</li>
@endsection

@section('dashboard', 'active')

@section('content')
<div class="row">
    <!-- Carte Total Formations -->
    <div class="col-sm-3 grid-margin">
        <div class="card">
            <div class="card-body">
                <h5>Total Formations</h5>
                <h2 class="mb-0">{{ $totalFormations ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <!-- Carte Total Inscriptions -->
    <div class="col-sm-3 grid-margin">
        <div class="card">
            <div class="card-body">
                <h5>Total Inscriptions</h5>
                <h2 class="mb-0">{{ $totalInscriptions ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <!-- Carte Total Paiements -->
    <div class="col-sm-3 grid-margin">
        <div class="card">
            <div class="card-body">
                <h5>Total Paiements</h5>
                <h2 class="mb-0">{{ isset($totalPaiements) ? number_format($totalPaiements, 0, ',', ' ') : 0 }} FCFA</h2>
            </div>
        </div>
    </div>

    <!-- Carte Formation la plus populaire -->
    <div class="col-sm-3 grid-margin">
        <div class="card">
            <div class="card-body">
                <h5>Formation Populaire</h5>
                @if($topFormations->count() > 0)
                    <h6>{{ $topFormations->first()->choixForm }} ({{ $topFormations->first()->count }} inscrits)</h6>
                @else
                    <h6>Aucune donnée</h6>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Graphique Inscriptions par mois -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Inscriptions par Mois</h4>
                <canvas id="inscriptionsChart" style="height:300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Graphique Statuts -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Répartition des Statuts</h4>
                <canvas id="statutChart" style="height:300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Graphique Revenus -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Revenus Mensuels</h4>
                <canvas id="revenusChart" style="height:300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Graphique Modes de paiement -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Modes de Paiement</h4>
                <canvas id="paiementChart" style="height:300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Tableau dernières inscriptions -->
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Dernières Inscriptions</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Formation</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dernieresInscriptions as $insc)
                                <tr>
                                    <td>{{ $insc->name }}</td>
                                    <td>{{ $insc->choixForm }}</td>
                                    <td>{{ number_format($insc->montant, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ $insc->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($insc->status == 'Accepté')
                                            <span class="badge badge-outline-success">Accepté</span>
                                        @elseif($insc->status == 'Rejeté')
                                            <span class="badge badge-outline-danger">Rejeté</span>
                                        @else
                                            <span class="badge badge-outline-warning">En attente</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucune inscription récente</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin/assets/vendors/chart.js/Chart.min.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
      // Inscriptions par mois
      new Chart(document.getElementById('inscriptionsChart').getContext('2d'), {
          type: 'line',
          data: {
              labels: {!! json_encode($inscriptions->pluck('month')) !!},
              datasets: [{
                  label: 'Inscriptions',
                  data: {!! json_encode($inscriptions->pluck('count')) !!},
                  borderColor: 'rgba(54, 162, 235, 1)',
                  backgroundColor: 'rgba(54, 162, 235, 0.2)',
                  fill: true,
                  tension: 0.4
              }]
          }
      });

      // Statuts
      new Chart(document.getElementById('statutChart').getContext('2d'), {
          type: 'doughnut',
          data: {
              labels: {!! json_encode($statutCounts->pluck('status')) !!},
              datasets: [{
                  data: {!! json_encode($statutCounts->pluck('count')) !!},
                  backgroundColor: ['#28a745', '#ffc107', '#dc3545']
              }]
          }
      });

      // Revenus mensuels
      new Chart(document.getElementById('revenusChart').getContext('2d'), {
          type: 'bar',
          data: {
              labels: {!! json_encode($revenusMensuels->pluck('month')) !!},
              datasets: [{
                  label: 'Revenus (FCFA)',
                  data: {!! json_encode($revenusMensuels->pluck('total')) !!},
                  backgroundColor: 'rgba(75, 192, 192, 0.7)'
              }]
          }
      });

      // Modes de paiement
      new Chart(document.getElementById('paiementChart').getContext('2d'), {
          type: 'pie',
          data: {
              labels: {!! json_encode($paiementModes->pluck('mode_paiement')) !!},
              datasets: [{
                  data: {!! json_encode($paiementModes->pluck('count')) !!},
                  backgroundColor: ['#007bff', '#17a2b8', '#6f42c1', '#ffc107']
              }]
          }
      });
  });
</script>
@endpush