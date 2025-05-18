@include('layouts.partials.adDoctype')
    <div class="container-scroller">
    <x-alert-modal1 />
      <!-- partial:partials/_sidebar.html -->
      @include('layouts.partials.sideBar')
      <!-- partial -->
      @include('layouts.partials.newFormElmts')
      <!-- page-body-wrapper ends -->
    </div>
@include('layouts.partials.adScripts')