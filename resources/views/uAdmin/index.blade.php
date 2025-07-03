@extends('layouts.adApp')

@section('title', 'Tableau de Bord')
@section('page-title', 'Tableau de Bord')
@section('breadcrumb')
    <li>Admin</li>
    <li>Tableau de Bord</li>
@endsection

@section('dashboard', 'active')

@section('content')
  <!-- Alert Notification -->
  <div class="alert">
    <i class="fas fa-exclamation-circle"></i>
    <div class="alert-content">
      <h4>Prochaine échéance</h4>
      <p>Votre projet pour le cours "Marketing Digital" est à rendre avant le 30 juin 2025</p>
    </div>
  </div>
  
  <!-- Stats Cards -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-card-header">
        <div class="stat-card-title">Formations suivies</div>
        <div class="stat-card-icon" style="background-color: rgba(63, 81, 181, 0.1); color: var(--primary);">
          <i class="fas fa-book"></i>
        </div>
      </div>
      <div class="stat-card-value">12</div>
      <div class="stat-card-change change-positive">
        <i class="fas fa-arrow-up"></i>
        <span>+2 ce mois-ci</span>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="stat-card-header">
        <div class="stat-card-title">Formations en cours</div>
        <div class="stat-card-icon" style="background-color: rgba(255, 202, 40, 0.1); color: var(--warning);">
          <i class="fas fa-spinner"></i>
        </div>
      </div>
      <div class="stat-card-value">4</div>
      <div class="stat-card-change change-positive">
        <i class="fas fa-arrow-up"></i>
        <span>+1 cette semaine</span>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="stat-card-header">
        <div class="stat-card-title">Formations terminées</div>
        <div class="stat-card-icon" style="background-color: rgba(76, 175, 80, 0.1); color: var(--success);">
          <i class="fas fa-check-circle"></i>
        </div>
      </div>
      <div class="stat-card-value">8</div>
      <div class="stat-card-change change-positive">
        <i class="fas fa-arrow-up"></i>
        <span>+3 ce trimestre</span>
      </div>
    </div>
  </div>
  
  <!-- Mes Formations -->
  <h2 class="section-title">
    <i class="fas fa-book-open"></i>
    Mes Formations Actives
  </h2>
  
  <div class="courses-container">
    <!-- Course 1 -->
    <div class="course-card">
      <div class="course-image">
        <div class="course-status status-in-progress">En cours</div>
      </div>
      <div class="course-content">
        <h3 class="course-title">Marketing Digital Avancé</h3>
        <div class="course-info">
          <div><i class="far fa-clock"></i> 8 semaines</div>
          <div><i class="far fa-calendar"></i> Termine le 15/07/2025</div>
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
          <button class="btn btn-primary">
            <i class="fas fa-play"></i> Continuer
          </button>
          <button class="btn btn-outline">
            <i class="fas fa-file-pdf"></i> Programme
          </button>
        </div>
      </div>
    </div>
    
    <!-- Course 2 -->
    <div class="course-card">
      <div class="course-image" style="background: linear-gradient(45deg, #FF9800, #FFB74D);">
        <div class="course-status status-in-progress">En cours</div>
      </div>
      <div class="course-content">
        <h3 class="course-title">Gestion de Projet Agile</h3>
        <div class="course-info">
          <div><i class="far fa-clock"></i> 6 semaines</div>
          <div><i class="far fa-calendar"></i> Termine le 30/06/2025</div>
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
          <button class="btn btn-secondary">
            <i class="fas fa-tasks"></i> Projet final
          </button>
          <button class="btn btn-outline">
            <i class="fas fa-file-pdf"></i> Programme
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Recent Activity -->
  <h2 class="section-title">
    <i class="fas fa-history"></i>
    Activité Récente
  </h2>
  
  <div class="courses-container">
    <!-- Activity 1 -->
    <div class="course-card">
      <div class="course-content">
        <div class="course-info" style="border: none; margin-bottom: 10px; padding-bottom: 0;">
          <div><i class="far fa-clock"></i> Il y a 2 heures</div>
          <div><i class="fas fa-book"></i> Marketing Digital</div>
        </div>
        <h3 class="course-title">Module 4 complété</h3>
        <p style="color: var(--text-medium); margin-bottom: 15px; font-size: 14px;">
          Vous avez terminé le module "Stratégies de contenu" avec succès et obtenu 92% au quiz.
        </p>
        <button class="btn btn-outline">
          <i class="fas fa-eye"></i> Voir les résultats
        </button>
      </div>
    </div>
    
    <!-- Activity 2 -->
    <div class="course-card">
      <div class="course-content">
        <div class="course-info" style="border: none; margin-bottom: 10px; padding-bottom: 0;">
          <div><i class="far fa-clock"></i> Hier</div>
          <div><i class="fas fa-comment"></i> Gestion de Projet</div>
        </div>
        <h3 class="course-title">Nouveau message du formateur</h3>
        <p style="color: var(--text-medium); margin-bottom: 15px; font-size: 14px;">
          Votre formateur a commenté votre projet: "Excellent travail sur le diagramme de Gantt, quelques ajustements nécessaires."
        </p>
        <button class="btn btn-outline">
          <i class="fas fa-reply"></i> Répondre
        </button>
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