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
		<!-- <div class="banner">
			<div class="owl-five owl-carousel owl-theme">
	            <div class="item-video">
            		<iframe width="100%" height="450" src="https://www.youtube.com/embed/ENVW3uZ3a-4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
            		</iframe>
	            </div>
	            <div class="item-video">
	            	<iframe width="100%" height="450" src="https://www.youtube.com/embed/0bfk90rWV9U" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	            </div>
	            <div class="item-video">
	            	<iframe width="100%" height="450" src="https://www.youtube.com/embed/ktvTqknDobU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	            </div>
	            <div class="item-video">
            		<iframe width="100%" height="450" src="https://www.youtube.com/embed/ENVW3uZ3a-4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
            		</iframe>
	            </div>
	            <div class="item-video">
	            	<iframe width="100%" height="450" src="https://www.youtube.com/embed/0bfk90rWV9U" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	            </div>
	            <div class="item-video">
	            	<iframe width="100%" height="450" src="https://www.youtube.com/embed/ktvTqknDobU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	            </div>
          </div>
		</div> -->
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
							<div class="box-wrap" itemprop="event" itemscope itemtype=" http://schema.org/Course">
								<div class="img-wrap" itemprop="image">
									<img src="{{ $form->image }}" height="262 px" alt="{{ $form->desc }}">
								</div>
								<div class="box-body" itemprop="description">
									<a href="/Reserver_votre_place/form={{ $form->id }}" class="learn-desining-banner" itemprop="name">{{ $form->libForm }} >>></a>
									<p>{{ $form->desc }}</p>
									<section itemprop="time">
										<p><span>Durée:</span> 3 mois</p>
										<p><span>Horaires:</span> 8h00-10h00 / 13h00-15h00</p>
										<p><span>Prix:</span> 14 500 FCFA</p>
									</section>
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