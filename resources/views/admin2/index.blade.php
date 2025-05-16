<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Miko admin2</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="{{ asset('admin2/assets/css/bootstrap.css') }}" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="{{ asset('admin2/assets/css/font-awesome.css') }}" rel="stylesheet" />
     <!-- MORRIS CHART STYLES-->
    <link href="{{ asset('admin2/assets/js/morris/morris-0.4.3.min.css') }}" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="{{ asset('admin2/assets/css/custom.css') }}" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <!-- Modal -->
    <x-alert-modal2 />
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Miko admin2</a> 
            </div>
            <div style="color: white; padding: 15px 50px 5px 0px; float: right; font-size: 16px;"> 
                <form action="{{route('uLogout')}}" method="post">
                @csrf
                <button type="submit" class="btn btn-danger square-btn-adjust">Se déconnecter</button>
                </form> 
            </div>
            <div style="color: white; padding: 15px 50px 5px 0px; float: right; font-size: 16px;"> 
                <a href="{{route('home')}}" class="btn btn-primary square-btn-adjust">Page d'acceuil</a>
            </div>

        </nav>   
            <!-- /. NAV TOP  -->
            @include('layouts.partials.navSide')

            <!-- /. NAV SIDE  -->
            @include('layouts.partials.uAdIndexContent') 
         <!-- /. PAGE WRAPPER  -->
    </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="{{ asset('admin2/assets/js/jquery-1.10.2.js') }}"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="{{ asset('admin2/assets/js/bootstrap.min.js') }}"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="{{ asset('admin2/assets/js/jquery.metisMenu.js') }}"></script>
     <!-- MORRIS CHART SCRIPTS -->
     <script src="{{ asset('admin2/assets/js/morris/raphael-2.1.0.min.js') }}"></script>
    <script src="{{ asset('admin2/assets/js/morris/morris.js') }}"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="{{ asset('admin2/assets/js/custom.js') }}"></script>
     <!-- DATA TABLE SCRIPTS -->
    <script src="{{ asset('admin2/assets/js/dataTables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('admin2/assets/js/dataTables/dataTables.bootstrap.js') }}"></script>
    <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>

    <!-- <script>
        function showConfirmationModal(id) {
            // On modifie dynamiquement l'action du formulaire dans le modal
            const form = document.getElementById('confirmForm');
            form.action = `/Annuler_reservation/${id}`;

            // Puis on affiche le modal
            const modal = new bootstrap.Modal(document.getElementById('alertModal'));
            modal.show();
        }
    </script> -->
    @include('layouts.partials.modalScript2')



    <!-- <script>
        function openAlertModal(message, actionRoute) {
            // Injecte dynamiquement le message
            document.querySelector('#alertModal .modal-body p').textContent = message;

            // Si le formulaire existe dans le footer (type = 'info')
            const form = document.querySelector('#alertModal form');
            if (form) {
                form.action = actionRoute;
            }

            // Affiche le modal (Bootstrap 5)
            const modal = new bootstrap.Modal(document.getElementById('alertModal'));
            modal.show();
        }
    </script> -->

    <!-- <script>
        function confirmAnnulation(id) {
            const form = document.getElementById('confirmForm');
            form.action = `/afficher-confirmation/${id}`;
            form.submit();
        }
    </script> -->
    
   
</body>
</html>

