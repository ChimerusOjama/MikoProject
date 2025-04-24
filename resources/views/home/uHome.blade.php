@include('layouts.partials.headPage')
<!-- ********************** -->
	<div class="banner">
		<div class="owl-four owl-carousel" itemprop="image">
			<img src="{{ asset('template/images/page-banner.jpg') }}" alt="Image of Bannner">
			<img src="{{ asset('template/images/page-banner2.jpg') }}" alt="Image of Bannner">
			<img src="{{ asset('template/images/page-banner3.jpg') }}" alt="Image of Bannner">
		</div>
		<div class="container" itemprop="description">
			<h1>welcome to education pro</h1>
			<h3>With our advance search feature you can now find the trips for you...</h3>
		</div>
			<div id="owl-four-nav" class="owl-nav"></div>
	</div>
	<!-- Banner Close -->
	<div class="page-heading">
		<div class="container">
			<h2>popular courses</h2>
		</div>
	</div>
	<!-- Popular courses End -->
	<div class="learn-courses">
		<div class="container">
			<div class="courses">
				<div class="owl-one owl-carousel">
					@foreach($forms as $form)
						<div class="row g-4">
							<div class="col-md-8 col-lg-12">
								<div class="card shadow-sm">
									<img src="{{ $form->image }}" class="card-img-top" alt="Cours">
									<div class="card-body">
										<h5 class="card-title">{{ $form->libForm }}</h5>
										<p class="tCard card-text">Durée : 3 mois</p>
										<p class="tCard card-text">Horaires : 8h00-10h00 / 13h00-15h00</p>
										<p class="tCard card-text"><strong>Prix : 14 500 FCFA Paiement unique</strong></p>
										<a href="/Reserver_votre_place/form={{ $form->id }}" class="btn btn-primary w-100">Voir le cours</a>
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<!-- Learn courses End -->
	<section class="whyUs-section">
		<div class="container">
			<div class="featured-points">
				<ul>
					<li><i class="fas fa-book"></i> free books for students</li>
					<li><i class="fas fa-money-check-alt"></i> affordable fees</li>
					<li><i class="fas fa-chalkboard-teacher"></i> experienced teachers</li>
					<li> <i class="fas fa-book"></i> free books for students</li>
				</ul>
			</div>
			<div class="whyus-wrap">
				<h1>why us?</h1>
				<p itemprop="description">Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsumLorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsumLorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsumLorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsumLorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsumLorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum Lorem Ipsum lorem ipsum</p>

				<a href="#" class="read-more-btn">read more</a>
			</div>
		</div>
	</section>
	<!-- Closed WhyUs section -->
	
	<!-- Latest News CLosed -->
	<section class="query-section">
		<div class="container">
			<p>Any Queries? Ask us a question at<a href="tel:+9779813639131"><i class="fas fa-phone"></i> +977 9813639131</a></p>
		</div>
	</section>
	<!-- End of Query Section -->
	<!-- ********************** -->
@include('layouts.partials.footPage')