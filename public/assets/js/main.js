document.addEventListener('DOMContentLoaded', function() {
  // Gestion du loader
  const loaderOverlay = document.querySelector('.loader-overlay');
  if (loaderOverlay) {
    // Cacher le loader après 1.5s
    setTimeout(() => {
      loaderOverlay.classList.add('hidden');
    }, 1500);
  }
  // Back to Top Button
  const backToTopButton = document.getElementById('backToTop');
  if (backToTopButton) {
    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 300) {
        backToTopButton.classList.add('active');
      } else {
        backToTopButton.classList.remove('active');
      }
    });
    
    backToTopButton.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  // Courses Scroll Controls
  const coursesScroll = document.getElementById('coursesScroll');
  if (coursesScroll) {
    const scrollLeft = document.getElementById('scrollLeft');
    const scrollRight = document.getElementById('scrollRight');
    
    // Calcul dynamique de la largeur à défiler
    const getScrollAmount = () => {
      const firstItem = coursesScroll.querySelector('.scroll-item');
      if (window.innerWidth >= 768) {
        // En desktop: défiler de 3 cartes à la fois
        return firstItem ? (firstItem.offsetWidth + 30) * 1 : 900;
      } else {
        // En mobile: défiler d'une carte à la fois
        return firstItem ? firstItem.offsetWidth + 30 : 300;
      }
    };

    const updateScrollButtons = () => {
      if (scrollLeft) {
        scrollLeft.disabled = coursesScroll.scrollLeft <= 10;
        scrollLeft.style.opacity = coursesScroll.scrollLeft <= 10 ? 0.5 : 1;
      }
      if (scrollRight) {
        const maxScroll = coursesScroll.scrollWidth - coursesScroll.clientWidth;
        scrollRight.disabled = coursesScroll.scrollLeft >= maxScroll - 10;
        scrollRight.style.opacity = coursesScroll.scrollLeft >= maxScroll - 10 ? 0.5 : 1;
      }
    };

    updateScrollButtons();
    
    coursesScroll.addEventListener('scroll', updateScrollButtons);
    window.addEventListener('resize', updateScrollButtons);
    
    if (scrollLeft) scrollLeft.addEventListener('click', () => {
      coursesScroll.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
    });
    
    if (scrollRight) scrollRight.addEventListener('click', () => {
      coursesScroll.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
    });
  }

  // Carousel initialization
  const carousel = document.getElementById('mainCarousel');
  if (carousel) {
    new bootstrap.Carousel(carousel, {
      interval: 5000,
      ride: 'carousel',
      wrap: true
    });
  }

  // Scroll animations
  const animateSections = document.querySelectorAll('.animate-section');
  if (animateSections.length) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
        }
      });
    }, { threshold: 0.1 });
    
    animateSections.forEach(section => {
      observer.observe(section);
    });
  }

  // Formations filtering and sorting
  const filters = {
    category: document.getElementById('categoryFilter'),
    level: document.getElementById('levelFilter'),
    sort: document.getElementById('sortBy')
  };

  if (filters.category) {
    Object.values(filters).forEach(filter => {
      filter.addEventListener('change', filterFormations);
    });
  }

  function filterFormations() {
    const categoryValue = filters.category.value;
    const levelValue = filters.level.value;
    const formations = document.querySelectorAll('.formations-list .col-lg-4');

    formations.forEach(formation => {
      const matchesCategory = !categoryValue || formation.dataset.category === categoryValue;
      const matchesLevel = !levelValue || formation.dataset.level === levelValue;
      
      formation.style.display = (matchesCategory && matchesLevel) ? 'block' : 'none';
    });

    sortFormations();
  }

  function sortFormations() {
    const container = document.getElementById('formationsContainer');
    const sortValue = filters.sort.value;
    const formations = Array.from(container.children);

    formations.sort((a, b) => {
      switch(sortValue) {
        case 'price-asc':
          return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
        case 'price-desc':
          return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
        case 'duration':
          return parseFloat(a.dataset.duration) - parseFloat(b.dataset.duration);
        default:
          return 0;
      }
    });

    formations.forEach(formation => container.appendChild(formation));

    // Calcul dynamique de la position sticky
    const navbar = document.getElementById('mainNavbar');
    const registrationForm = document.querySelector('.registration-form.sticky-top');
    
    if (navbar && registrationForm) {
      // Mettre à jour la position au chargement
      updateStickyPosition();
      
      // Mettre à jour lors du redimensionnement
      window.addEventListener('resize', updateStickyPosition);
      
      function updateStickyPosition() {
        const navbarHeight = navbar.offsetHeight;
        registrationForm.style.top = `${navbarHeight + 20}px`; // 20px de marge supplémentaire
      }
    }
  }
});
