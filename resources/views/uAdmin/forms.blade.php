@extends('layouts.adApp')

@section('title', 'Mes formations - Tableau de Bord')
@section('page-title', 'Mes formations')
@section('breadcrumb')
    <li>Admin</li>
    <li>Formation</li>
@endsection

@section('forms', 'active')

@section('content')
      <div class="section">
        <h3 class="section-title"><i class="fas fa-list"></i> Toutes mes inscriptions</h3>
        
        <div class="data-table-container">
          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Formation</th>
                  <th>Montant</th>
                  <th>Date d'inscription</th>
                  <th>Statut</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($inscShow as $oneInscShow)
                <tr>
                  <td>{{ $oneInscShow->choixForm }}</td>
                  <td>{{ $oneInscShow->montant }}</td>
                  <td>10/06/2025</td>
                  <td><span class="status-badge">{{ $oneInscShow->status }}</span></td>
                  <td class="actions-cell">
                    <button class="btn-icon success" title="Finaliser">
                      <i class="fas fa-check-circle"></i>
                    </button>
                    <button class="btn-icon danger" title="Annuler">
                      <i class="fas fa-times-circle"></i>
                    </button>
                  </td>
                </tr>
                @endforeach
                <tr>
                  <td>Marketing Digital Intermédiaire</td>
                  <td>320 €</td>
                  <td>05/06/2025</td>
                  <td><span class="status-badge status-pending">En attente</span></td>
                  <td class="actions-cell">
                    <button class="btn-icon success" title="Finaliser">
                      <i class="fas fa-check-circle"></i>
                    </button>
                    <button class="btn-icon danger" title="Annuler">
                      <i class="fas fa-times-circle"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>UI/UX Design Fundamentals</td>
                  <td>420 €</td>
                  <td>12/12/2024</td>
                  <td><span class="status-badge status-completed">Terminée</span></td>
                  <td class="actions-cell">
                    <button class="btn-icon" disabled>
                      <i class="fas fa-file-download"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>Data Science Fundamentals</td>
                  <td>550 €</td>
                  <td>15/06/2025</td>
                  <td><span class="status-badge status-pending">En attente</span></td>
                  <td class="actions-cell">
                    <button class="btn-icon success" title="Finaliser">
                      <i class="fas fa-check-circle"></i>
                    </button>
                    <button class="btn-icon danger" title="Annuler">
                      <i class="fas fa-times-circle"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="section-container">
        <!-- Formations en cours -->
        <div class="section">
          <h3 class="section-title"><i class="fas fa-spinner"></i> Formations en cours</h3>
          <div class="courses-container">
            <div class="course-card">
              <div class="course-image development">
                <span class="course-status status-in-progress">En cours</span>
              </div>
              <div class="course-content">
                <h4 class="course-title">Formation en Développement Web Avancé</h4>
                <div class="course-info">
                  <span>Développement</span>
                  <span><i class="far fa-calendar"></i> 12/06/2025 - 30/08/2025</span>
                </div>
                <div class="course-progress">
                  <div class="progress-text">
                    <span>Progression</span>
                    <span>75%</span>
                  </div>
                  <div class="progress-bar">
                    <div class="progress-value progress-75"></div>
                  </div>
                </div>
                <div class="course-actions">
                  <button class="btn btn-primary"><i class="fas fa-play-circle"></i> Continuer</button>
                  <button class="btn btn-outline"><i class="fas fa-info-circle"></i> Détails</button>
                </div>
              </div>
            </div>
            
            <div class="course-card">
              <div class="course-image marketing">
                <span class="course-status status-in-progress">En cours</span>
              </div>
              <div class="course-content">
                <h4 class="course-title">Marketing Digital - Niveau Intermédiaire</h4>
                <div class="course-info">
                  <span>Marketing</span>
                  <span><i class="far fa-clock"></i> 8 semaines</span>
                </div>
                <div class="course-progress">
                  <div class="progress-text">
                    <span>Progression</span>
                    <span>30%</span>
                  </div>
                  <div class="progress-bar">
                    <div class="progress-value progress-30"></div>
                  </div>
                </div>
                <div class="course-actions">
                  <button class="btn btn-primary"><i class="fas fa-play-circle"></i> Continuer</button>
                  <button class="btn btn-outline"><i class="fas fa-info-circle"></i> Détails</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Formations terminées -->
        <div class="section">
          <h3 class="section-title"><i class="fas fa-check-circle"></i> Formations terminées</h3>
          <div class="courses-container">
            <div class="course-card">
              <div class="course-image design">
                <span class="course-status status-completed">Terminée</span>
              </div>
              <div class="course-content">
                <h4 class="course-title">UI/UX Design Fundamentals</h4>
                <div class="course-info">
                  <span>Design</span>
                  <span><i class="far fa-calendar"></i> 15/01/2025 - 15/04/2025</span>
                </div>
                <div class="course-progress">
                  <div class="progress-text">
                    <span>Progression</span>
                    <span>100%</span>
                  </div>
                  <div class="progress-bar">
                    <div class="progress-value progress-100"></div>
                  </div>
                </div>
                <div class="course-actions">
                  <button class="btn btn-outline"><i class="fas fa-info-circle"></i> Détails</button>
                  <button class="btn btn-secondary"><i class="fas fa-download"></i> Attestation</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Formations à venir -->
        <div class="section">
          <h3 class="section-title"><i class="fas fa-clock"></i> Formations à venir</h3>
          <div class="courses-container">
            <div class="course-card">
              <div class="course-image data">
                <span class="course-status status-upcoming">À venir</span>
              </div>
              <div class="course-content">
                <h4 class="course-title">Data Science Fundamentals</h4>
                <div class="course-info">
                  <span>Data Science</span>
                  <span><i class="far fa-calendar"></i> 01/09/2025 - 15/12/2025</span>
                </div>
                <div class="course-progress">
                  <div class="progress-text">
                    <span>Début dans</span>
                    <span>35 jours</span>
                  </div>
                  <div class="progress-bar">
                    <div class="progress-value progress-0"></div>
                  </div>
                </div>
                <div class="course-actions">
                  <button class="btn btn-outline" disabled><i class="fas fa-info-circle"></i> Détails</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection

@push('scripts')
<script>
  // Scripts spécifiques au dashboard
  document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard admin chargé');
  });
</script>
@endpush