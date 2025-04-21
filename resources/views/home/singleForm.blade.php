@include('layouts.partials.headPage')
<section class="page-content" id="course-page">
    <div class="container mt-4">
        <div class="row">
            <main class="course-detail col-md-8">
                <h2>{{ $oneForm->libForm }}</h2>
                <header>
                    <div class="course-box">
                        <i class="far fa-clock"></i>
                        <p>3 mois</p>
                        <p>(2 heures/ jour)</p>
                    </div>

                    <div class="course-box">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>10 apprenants/ salle</p>
                    </div>

                    <div class="course-box">
                        <i class="fas fa-money-check-alt"></i>
                        <p>14 500 FCFA</p>
                        <p>(Livre de formation inclu)</p>
                    </div>

                    <div class="course-box">
                        <h3>Emploi du temps</h3>
                        <button>fichier pdf</button>
                    </div>
                </header>
                <article>
                    <section class="course-intro">
                        <h3>Description</h3>

                        <p>{{ $oneForm->desc }}</p>
                    </section>

                    <section class="course-objective">
                        <h3>Objectives</h3>
                        <p>After this course student will be able to:</p>
                        <ul>
                            <li>Develop interactive Web pages using XHTML, HTML/DHTML & CSS</li>
                            <li>Create interactive forms that capture and validate user input using JavaScript</li>
                            <li>Create interactive forms that capture and validate user input using JavaScript</li>
                            <li>Control Java Applets, ActiveX Controls and other plug-ins</li>
                            <li>Control Java Applets, ActiveX Controls and other plug-ins</li>
                            <li>Enhance PHP programming skills to successfully build interactive, data-driven web applications</li>
                            <li>Develop interactive Web pages using XHTML, HTML/DHTML & CSS</li>
                            <li>Develop interactive Web pages using XHTML, HTML/DHTML & CSS</li>
                            <li>Control Java Applets, ActiveX Controls and other plug-ins</li>
                            <li>Create interactive forms that capture and validate user input using JavaScript</li>
                            <li>Develop interactive Web pages using XHTML, HTML/DHTML & CSS</li>
                        </ul>

                        <h3>Certification</h3>
                        <p>After this course Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodc illum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

                        <h3>Who can apply for this course?</h3>
                        <ul>
                            <li>+2 with science faculty</li>
                            <li>+2 with management faculty</li>
                            <li>+2 with science faculty</li>
                            <li>+2 with management faculty</li>
                        </ul>

                        <h3>Training Methodology</h3>
                        <ul>
                            <li>Weekly test</li>
                            <li>Digital Class</li>
                            <li>Field Visit and real project demonstration</li>
                            <li>+2 with managem</li>
                        </ul>
                    </section>
                </article>					
            </main>
            <aside class="course-sidebar col-md-4">
                <div class="reserve-course">
                    <h2>Reservez votre place</h2>
                    @if(Route::has('login'))
							@auth
                            <form>
                                <input type="text" value="{{ Auth::user()->name }}" disabled>
                                <input type="email" value="{{ Auth::user()->email }}" name="userEmail" disabled>
                                <input type="tel" value="{{ Auth::user()->phone }}" disabled>
                                <input type="text" value="{{ $oneForm->libForm }}" disabled>
                                <textarea placeholder="Besoins particuliers ? Faite le nous savoir ici."></textarea>
                                <input type="submit" value="Soumettre">
                            </form>
							@else
                            <form>
                                <input type="text" placeholder="Nom complet*" required>
                                <input type="email" name="userEmail" placeholder="Adresse mail*" required>
                                <input type="tel" placeholder="Teléphone*" required>
                                <input type="text" value="{{ $oneForm->libForm }}" disabled>
                                <textarea placeholder="Besoins particuliers ? Faite le nous savoir ici."></textarea>
                                <input type="submit" value="Soumettre">
                            </form>
							@endauth
						@endif
                </div>
                <!-- New Letter Ends -->
                <!-- Recent Post Close -->
            </aside>
        </div>
    </div>
</section>
@include('layouts.partials.footPage')
