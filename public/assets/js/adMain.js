// Animation au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du loader
    const loaderOverlay = document.querySelector('.loader-overlay');
    if (loaderOverlay) {
        // Cacher le loader après 1.5s
        setTimeout(() => {
        loaderOverlay.classList.add('hidden');
        }, 1500);
    }

    // Back to Top Functionality
    const backToTopButton = document.getElementById('backToTop');

    // Show/hide button based on scroll position
    window.addEventListener('scroll', () => {
    if (window.pageYOffset > 300) {
        backToTopButton.classList.add('show');
    } else {
        backToTopButton.classList.remove('show');
    }
    });

    // Smooth scroll to top
    backToTopButton.addEventListener('click', () => {
    backToTopButton.classList.add('clicked');
    
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
    
    // Animation feedback
    setTimeout(() => {
        backToTopButton.classList.remove('clicked');
    }, 500);
    });

    // Touch devices support
    backToTopButton.addEventListener('touchstart', () => {
    backToTopButton.classList.add('touched');
    });

    backToTopButton.addEventListener('touchend', () => {
    backToTopButton.classList.remove('touched');
    });
    // Animation des cartes de statistiques
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s, transform 0.5s';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, 200 * index);
    });
    
    // Animation des cartes de formation
    const courseCards = document.querySelectorAll('.course-card');
    courseCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s, transform 0.5s';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, 300 + (100 * index));
    });
    
    // Effet de survol sur les cartes
    courseCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = 'var(--shadow)';
        });
    });

    // Gestion de l'image de profil (erreur ou absence)
    const profileImgs = document.querySelectorAll('.profile-img');
    profileImgs.forEach(img => {
        img.addEventListener('error', function() {
            img.src = '';
            img.style.backgroundImage = 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%233F51B5\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Ccircle cx=\'12\' cy=\'8\' r=\'5\'/%3E%3Cpath d=\'M20 21a8 8 0 0 0-16 0\'/%3E%3C/svg%3E")';
        });
        if (!img.src || img.src === window.location.href) {
            img.src = '';
            img.style.backgroundImage = 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%233F51B5\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Ccircle cx=\'12\' cy=\'8\' r=\'5\'/%3E%3Cpath d=\'M20 21a8 8 0 0 0-16 0\'/%3E%3C/svg%3E")';
        }
    });

    // Simple dropdown toggle for mobile/desktop
    document.querySelectorAll('.user-profile .dropdown-toggle').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const menu = this.nextElementSibling;
            menu.classList.toggle('show');
        });
    });
    // Optional: close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.user-profile .dropdown-menu').forEach(menu => {
            if (!menu.parentElement.contains(e.target)) {
                menu.classList.remove('show');
            }
        });
    });

    // Gestion des boutons de navigation
    document.querySelectorAll('.btn-primary').forEach(button => {
    button.addEventListener('click', function() {
        this.innerHTML = '<i class="fas fa-sync fa-spin"></i> Chargement...';
        setTimeout(() => {
        // Simulation de navigation
        window.location.href = 'formation.html';
        }, 1000);
    });
    });

    // Animation des nouvelles cartes
    setTimeout(() => {
    document.querySelectorAll('.course-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s, transform 0.5s';
        
        setTimeout(() => {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
        }, 100 + (150 * index));
    });
    }, 300);

    // Ajouter après les gestionnaires existants
    document.querySelectorAll('.btn-icon.danger').forEach(button => {
    button.addEventListener('click', function(e) {
        e.stopPropagation();
        const row = this.closest('tr');
        
        // Confirmation avant suppression
        if (confirm("Voulez-vous vraiment annuler cette inscription ?")) {
        row.style.backgroundColor = 'rgba(244, 67, 54, 0.1)';
        
        setTimeout(() => {
            row.style.opacity = '0.5';
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;
            
            // Simulation de suppression après délai
            setTimeout(() => {
            row.remove();
            // Ici vous pourriez ajouter un appel AJAX réel
            }, 800);
        }, 300);
        }
    });
    });

    document.querySelectorAll('.btn-icon.success').forEach(button => {
    button.addEventListener('click', function(e) {
        e.stopPropagation();
        const row = this.closest('tr');
        const statusCell = row.querySelector('.status-badge');
        
        if (statusCell.classList.contains('status-pending')) {
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        this.disabled = true;
        
        // Simulation de confirmation
        setTimeout(() => {
            statusCell.textContent = 'Confirmée';
            statusCell.className = 'status-badge status-confirmed';
            this.innerHTML = '<i class="fas fa-check"></i>';
            this.style.color = 'var(--success)';
            this.style.backgroundColor = 'rgba(76, 175, 80, 0.1)';
        }, 800);
        }
    });
    });

    // Animation d'apparition des lignes du tableau
    document.querySelectorAll('.data-table tbody tr').forEach((row, index) => {
    setTimeout(() => {
        row.style.opacity = '0';
        row.style.transform = 'translateX(-20px)';
        row.style.transition = 'opacity 0.4s, transform 0.4s';
        
        setTimeout(() => {
        row.style.opacity = '1';
        row.style.transform = 'translateX(0)';
        }, 50);
    }, 200 * index);
    });

    // Gestion des onglets du profil
    document.querySelectorAll('.tab-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Supprimer la classe active de tous les boutons
        document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
        });
        
        // Ajouter la classe active au bouton cliqué
        this.classList.add('active');
        
        // Masquer tous les panneaux
        document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('active');
        });
        
        // Afficher le panneau correspondant
        const tabId = this.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
    });
    });

    // Gestion du mot de passe
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (newPassword !== confirmPassword) {
        alert('Les mots de passe ne correspondent pas.');
        return;
    }
    
    if (newPassword.length < 8) {
        alert('Le mot de passe doit contenir au moins 8 caractères.');
        return;
    }
    
    // Simulation d'envoi au serveur
    alert('Mot de passe mis à jour avec succès !');
    this.reset();
    });

    // Afficher/masquer le mot de passe
    document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    });

    // Indicateur de force du mot de passe
    document.getElementById('newPassword').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.querySelector('.strength-bar');
    const strengthValue = document.getElementById('strengthValue');
    
    let strength = 0;
    let color = '';
    let text = '';
    
    if (password.length >= 8) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    if (/[^A-Za-z0-9]/.test(password)) strength += 25;
    
    if (strength < 50) {
        color = 'var(--danger)';
        text = 'Faible';
    } else if (strength < 75) {
        color = 'var(--warning)';
        text = 'Moyen';
    } else {
        color = 'var(--success)';
        text = 'Fort';
    }
    
    strengthBar.style.width = strength + '%';
    strengthBar.style.background = color;
    strengthValue.textContent = text;
    strengthValue.style.color = color;
    });

    // Soumission des formulaires
    document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Profil mis à jour avec succès !');
    });

    document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Paramètres enregistrés avec succès !');
    });

    // Gestion du formulaire de support
    document.getElementById('supportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Afficher l'indicateur de chargement
    const loadingIndicator = document.querySelector('.loading-indicator');
    const errorMessage = document.querySelector('.error-message');
    const successMessage = document.querySelector('.success-message');
    
    // Cacher les autres messages
    errorMessage.style.display = 'none';
    successMessage.style.display = 'none';
    loadingIndicator.style.display = 'flex';
    
    // Simuler un envoi au serveur
    setTimeout(() => {
        // Vérification simple des champs requis
        const name = document.getElementById('supportName').value;
        const email = document.getElementById('supportEmail').value;
        const message = document.getElementById('supportMessage').value;
        
        if (name && email && message) {
        // Succès
        loadingIndicator.style.display = 'none';
        successMessage.style.display = 'flex';
        
        // Réinitialiser le formulaire après succès
        setTimeout(() => {
            this.reset();
            document.querySelector('.file-name').textContent = 'Aucun fichier sélectionné';
            successMessage.style.display = 'none';
        }, 3000);
        } else {
        // Erreur
        loadingIndicator.style.display = 'none';
        errorMessage.style.display = 'flex';
        }
    }, 1500);
    });

    // Gestion du téléchargement de fichier
    document.getElementById('supportFile').addEventListener('change', function(e) {
    const fileName = this.files.length > 0 ? this.files[0].name : 'Aucun fichier sélectionné';
    document.querySelector('.file-name').textContent = fileName;
    });

    // Animation des cartes
    document.addEventListener('DOMContentLoaded', function() {
    const infoCards = document.querySelectorAll('.info-card');
    const faqCards = document.querySelectorAll('.faq-card');
    
    // Animation des cartes d'information
    infoCards.forEach((card, index) => {
        setTimeout(() => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s, transform 0.5s';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100);
        }, 200 * index);
    });
    
    // Animation des cartes FAQ
    setTimeout(() => {
        faqCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s, transform 0.5s';
            
            setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
            }, 100);
        }, 200 * index);
        });
    }, 500);
    });
});
