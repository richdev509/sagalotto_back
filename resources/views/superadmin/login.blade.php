<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Konekte Sagalotto</title>
    @notifyCss
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                  <img src="assets/images/logo.png">
                  <h4>Sagalotto</h4>
                </div>
                
                <h6 class="font-weight-light">Konekte pouw kontinye.</h6>
                <form class="pt-3" method="POST" action="auth">
                  @csrf
                  <div class="form-group">
                    <input type="text" name="username" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Itilizatè" style="border-color: blueviolet;" required>

                  </div>
                  <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="modepas" style="border-color: blueviolet;" required>
                  </div>
                  <div class="mt-3">

                    <button class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" type="submit">KONEKTE</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> Kenbem konekte pou lontan</label>
                    </div>
                    <a href="/contact" class="auth-link text-black">Ou bliye modepas ou? Kontakte nou</a>
                  </div>
                  
                  <div class="text-center mt-4 font-weight-light"> Ou bezwen sistèm pou bolet ou? <a href="/contact" class="text-primary">Ranpli fom nan</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <!-- endinject -->
    <x-notify::notify />
    @notifyJs
  </body>
</html>