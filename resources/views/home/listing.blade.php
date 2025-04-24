@include('layouts.partials.headPage')
<!-- ********************** -->
<section class="course-listing-page">
	<div class="container">
		<div id="filters" class="button-group">
			<button class="button" data-filter="*">all</button>
			<button class="button" data-filter=".business">business</button>
			<button class="button" data-filter=".design">design</button>
			<button class="button" data-filter=".development">development</button>
			<button class="button" data-filter=".seo">seo</button>
			<button class="button" data-filter=".marketing">marketing</button>
		</div>
		<div class="grid" id="cGrid">
			@foreach($forms as $form)
				<div class="grid-item business" data-category="business">
				<div class="row g-4">
					<div class="col-md-6 col-lg-12">
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
				</div>
			@endforeach
		</div>
	</div>
</section>
<!-- ********************** -->
@include('layouts.partials.footPage')