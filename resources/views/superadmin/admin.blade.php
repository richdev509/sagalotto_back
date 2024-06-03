@extends('superadmin.admin-layout')

@section('content')

      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <div class="page-header">
        <h3 class="page-title">
          <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
          </span> Dashboard
        </h3>
        <nav aria-label="breadcrumb">
          <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
              <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
          </ul>
        </nav>
      </div>
      <div class="row">
        <div class="col-md-4 stretch-card grid-margin">
          <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
              <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
              <h4 class="font-weight-normal mb-3">Nombre POS<i class="mdi mdi-cash-register mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-5">{{$nombrePos}}<i class="mdi mdi-cash-register mdi-24px float-right"></i> </h2>
              <!--<h6 class="card-text">Vandè ki vann plis jodia:  <span style="font-weight: bold;">Bank #12</span></h6>-->
            </div>
          </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
          <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
              <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
              <h4 class="font-weight-normal mb-3">Nombre de compagnie<i class="mdi mdi-domain mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-5">{{$nombreCompagnie}}<i class="mdi mdi-domain mdi-24px float-right"></i></h2> 
            </div>
          </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
          <div class="card bg-gradient-warning card-img-holder text-white">
            <div class="card-body">
              <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
              <h4 class="font-weight-normal mb-3">Compagnie inactif<i class="mdi mdi-domain mdi-24px float-right" ></i>
              </h4>
              <h2 class="mb-5">{{$Compagnieinactive}}<i class="mdi mdi-domain mdi-24px float-right" ></i></h2>
              <h6 class="card-text"></h6>
            </div>
          </div>
        </div>
      </div>
      <div class="row">

        

        <div class="col-md-7 grid-margin stretch-card" style="display:;">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Statistik</h4>
              <div style="display: felx;gap:5px;flex-wrap:wrap;">
              <form action="" method="">
              <select class="form-control" style="    border-color: #53dbd3;
              border-style: solid;
              border-width: thin;">
                <option disabled>Select Compagnie</option>
                <option value="">Sagaloto</option>
                <option value="">sagacenter</option>
              </select>
              <input type="date" style="    border-color: #96f3dc;
              border-style: solid;
              border-width: thin;" class="form-control" placeholder="select date" />
              </form>
              </div>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      
                      <th> Tirage</th>
                      <th> Nombre Fich </th>
                      <th> Gain</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                     
                      <td>New York</td>
                      <td> 400</td>
                      <td>
                        400HTG
                      </td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>
      
      


   





@endsection