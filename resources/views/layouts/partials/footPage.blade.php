<footer class="page-footer" itemprop="footer" itemscope itemtype="http://schema.org/WPFooter">
			<div class="footer-first-section">
				<div class="container">
					<div class="box-wrap" itemprop="about">
						<header>
							<h1>about</h1>
						</header>
						<p>Edulab is a great start for and education. Personnel or oganization to start the online business with 1 click</p>

						<h4><a href="tel:+9779813639131"><i class="fas fa-phone"></i> +977 9813639131</a></h4>
						<h4><a href="mailto:info@educationpro.com"><i class="fas fa-envelope"></i> info@educationpro.com</a></h4>
						<h4><a href=""><i class="fas fa-map-marker-alt"></i>Gongabu, Kathmandu, Nepal</a></h4>
					</div>

					<div class="box-wrap">
						<header>
							<h1>links</h1>
						</header>
						<ul>
							<li><a href="#">Teacher</a></li>
							<li><a href="#">Courses</a></li>
							<li><a href="#">Courses</a></li>
							<li><a href="#">Courses</a></li>
							<li><a href="#">Courses</a></li>
							<li><a href="#">Courses</a></li>
						</ul>
					</div>

					<div class="box-wrap">
						<header>
							<h1>recent courses</h1>
						</header>
						<div class="recent-course-wrap">
							<img src="{{ asset('template/images/ui-ux.jpg') }}" alt="Ui/Ux Designing">
							<a href=""><div class="course-name">
								<h3>UI/UX Designer courses</h3>
								<p><span>$50</span> $40</p>
							</div></a>
						</div>
						<div class="recent-course-wrap">
							<img src="{{ asset('template/images/ui-ux.jpg') }}" alt="Ui/Ux Designing">
							<a href=""><div class="course-name">
								<h3>UI/UX Designer courses</h3>
								<p><span>$50</span> $40</p>
							</div></a>
						</div>
					</div>

					<div class="box-wrap">
						<header>
							<h1>quick contact</h1>
						</header>
						<section class="quick-contact">
							<input type="email" name="email" placeholder="Your Email*">
							<textarea placeholder="Type your message*"></textarea>
							<button>send message</button>
						</section>
					</div>

				</div>
			</div>
			<!-- End of box-Wrap -->
			<div class="footer-second-section">
				<div class="container">
					<hr class="footer-line">
					<ul class="social-list">
						<li><a href=""><i class="fab fa-facebook-square"></i></a></li>
						<li><a href=""><i class="fab fa-twitter"></i></a></li>
						<li><a href=""><i class="fab fa-skype"></i></a></li>
						<li><a href=""><i class="fab fa-youtube"></i></a></li>
						<li><a href=""><i class="fab fa-instagram"></i></a></li>
					</ul>
					<hr class="footer-line">
				</div>
			</div>
			<div class="footer-last-section">
				<div class="container">
					<p>Copyright 2018 &copy; educationpro.com <span> | </span> Theme designed and developed by <a href="https://labtheme.com">Lab Theme</a></p>
				</div>
			</div>
		</footer>

		<!-- <nav id="menu">
			<ul>
				<li><a href="#">HOME</a></li>
				<li>
					<span>COURSES</span>
					<ul>
						<li><a href="#/courses/child">Child</a></li>
						<li><a href="#/courses/child">Child</a></li>
						<li>
							<span>Child</span>
							<ul>
								<li><a href="#/courses/child/grandChild">Grand Child</a></li>
								<li><a href="#/courses/child/grandChild">Grand Child</a></li>
							</ul>
						</li>
					</ul>
				</li>
				<li>
					<a href="#">gallery</a>
					<ul>
						<li><a href="#">Child</a></li>
						<li><a href="#">Child</a></li>
						<li><a href="#">Child</a></li>
					</ul>
				</li>
				<li>
					<a href="#">news</a>
					<ul>
						<li><a href="#">Child</a></li>
						<li><a href="#">Child</a></li>
						<li><a href="#">Child</a></li>
					</ul>
				</li>
				<li><a href="#">about</a></li>
				<li><a href="#">contact</a></li>
			</ul>
		</nav> -->

	</div>
	<!-- Edulab Template JS -->
<script src="{{ asset('template/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('template/js/lightbox.js') }}"></script>
<script src="{{ asset('template/js/all.js') }}"></script>
<script src="{{ asset('template/js/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('template/js/owl.carousel.js') }}"></script>
<script src="{{ asset('template/js/jquery.flexslider.js') }}"></script>
<script src="{{ asset('template/js/jquery.rateyo.js') }}"></script>
<script src="{{ asset('template/js/custom.js') }}"></script>

<!-- Bootstrap 5 JS (bundle includes Popper for modals) -->
<script src="{{ asset('bootstrap-5.3.3/js/bootstrap.bundle.min.js') }}"></script>

<!-- Modal trigger (optional if you're using a button with data-bs-toggle already) -->
@include('layouts.partials.modalScript')
@include('layouts.partials.modalScript1')




	
</body>
</html>