<div class="container-fluid page-body-wrapper">

        @include('layouts.partials.adNavBar')

        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            @if(session('success'))
              <div class="alert alert-success">
                  <button type="button" class="close" data-dismiss="alert">X</button>
                  {{session('success')}}
              </div>
            @endif
          <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Formulaire de création</h4>
                    <form class="forms-sample" action="{{url('Insertion')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group">
                        <label for="exampleInputName1">Libellé</label>
                        <input type="text" class="form-control" id="exampleInputName1" name="libForm" required placeholder="Libellé de la formation">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName2">Description</label>
                        <input type="text" class="form-control" id="exampleInputName2" name="desc" required placeholder="Description de la formation">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName3">Image</label>
                        <input type="file" class="form-control" id="exampleInputName3" name="image" required>
                      </div>
                      <!-- <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" class="file-upload-default">
                        <div class="input-group col-xs-12">
                          <input type="text" class="form-control file-upload-info" disabled placeholder="Chargez une image">
                          <span class="input-group-append">
                            <button class="file-upload-browse btn btn-primary" type="button">Ajouter</button>
                          </span>
                        </div>
                      </div> -->
                      <!-- <input type="submit" value="Soumettre"> -->
                      <button type="submit" class="btn btn-primary me-2">Soummetre</button>
                      <button class="btn btn-dark">Annuler</button>
                    </form>
                  </div>
                </div>
              </div>
              
              <!-- <div class="row ">
                <div class="col-12 grid-margin">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Order Status</h4>
                      <div class="table-responsive">
                        <table class="table">
                          <thead>
                            <tr>
                              <th>
                                <div class="form-check form-check-muted m-0">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input">
                                  </label>
                                </div>
                              </th>
                              <th> Client Name </th>
                              <th> Order No </th>
                              <th> Product Cost </th>
                              <th> Project </th>
                              <th> Payment Mode </th>
                              <th> Start Date </th>
                              <th> Payment Status </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>
                                <div class="form-check form-check-muted m-0">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input">
                                  </label>
                                </div>
                              </td>
                              <td>
                                <img src="{{ asset('admin/assets/images/faces/face1.jpg') }}" alt="image" />
                                <span class="ps-2">Henry Klein</span>
                              </td>
                              <td> 02312 </td>
                              <td> $14,500 </td>
                              <td> Dashboard </td>
                              <td> Credit card </td>
                              <td> 04 Dec 2019 </td>
                              <td>
                                <div class="badge badge-outline-success">Approved</div>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <div class="form-check form-check-muted m-0">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input">
                                  </label>
                                </div>
                              </td>
                              <td>
                                <img src="assets/images/faces/face2.jpg" alt="image" />
                                <span class="ps-2">Estella Bryan</span>
                              </td>
                              <td> 02312 </td>
                              <td> $14,500 </td>
                              <td> Website </td>
                              <td> Cash on delivered </td>
                              <td> 04 Dec 2019 </td>
                              <td>
                                <div class="badge badge-outline-warning">Pending</div>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <div class="form-check form-check-muted m-0">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input">
                                  </label>
                                </div>
                              </td>
                              <td>
                                <img src="assets/images/faces/face5.jpg" alt="image" />
                                <span class="ps-2">Lucy Abbott</span>
                              </td>
                              <td> 02312 </td>
                              <td> $14,500 </td>
                              <td> App design </td>
                              <td> Credit card </td>
                              <td> 04 Dec 2019 </td>
                              <td>
                                <div class="badge badge-outline-danger">Rejected</div>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <div class="form-check form-check-muted m-0">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input">
                                  </label>
                                </div>
                              </td>
                              <td>
                                <img src="assets/images/faces/face3.jpg" alt="image" />
                                <span class="ps-2">Peter Gill</span>
                              </td>
                              <td> 02312 </td>
                              <td> $14,500 </td>
                              <td> Development </td>
                              <td> Online Payment </td>
                              <td> 04 Dec 2019 </td>
                              <td>
                                <div class="badge badge-outline-success">Approved</div>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <div class="form-check form-check-muted m-0">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input">
                                  </label>
                                </div>
                              </td>
                              <td>
                                <img src="assets/images/faces/face4.jpg" alt="image" />
                                <span class="ps-2">Sallie Reyes</span>
                              </td>
                              <td> 02312 </td>
                              <td> $14,500 </td>
                              <td> Website </td>
                              <td> Credit card </td>
                              <td> 04 Dec 2019 </td>
                              <td>
                                <div class="badge badge-outline-success">Approved</div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div> -->
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2021</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin template</a> from Bootstrapdash.com</span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>