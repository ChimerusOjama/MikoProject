@include('layouts.partials.headPage')
<!-- ********************** -->
<section class="course-listing-page">
			<div class="container">
				<!-- <div id="filters" class="button-group">
					<button class="button" data-filter="*">all</button>
  					<button class="button" data-filter=".business">business</button>
  					<button class="button" data-filter=".design">design</button>
  					<button class="button" data-filter=".development">development</button>
  					<button class="button" data-filter=".seo">seo</button>
  					<button class="button" data-filter=".marketing">marketing</button>
				</div> -->

				<div class="grid" id="cGrid">
					@foreach($forms as $form)
						<div class="grid-item business" data-category="business">
							<div class="img-wrap">
								<img src="{{ $form->image }}" alt="{{ $form->desc }}">
							</div>
							<div class="box-body">
								<a href="/Reserver_votre_place/form={{ $form->id }}" class="learn-desining-banner-course">{{ $form->libForm }} >>></a>
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
		</section>
        <!-- ********************** -->
        @include('layouts.partials.footPage')