@extends('admin-layout')

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
              <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
              <h4 class="font-weight-normal mb-3">Kob ou vann jodia <i class="mdi mdi-chart-line mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-5">HTG {{$vente}}</h2>
              <!--<h6 class="card-text">Vandè ki vann plis jodia:  <span style="font-weight: bold;">Bank #12</span></h6>-->
            </div>
          </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
          <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
              <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
              <h4 class="font-weight-normal mb-3">Kob ou peye jodia <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-5">HTG {{$perte}}</h2> 
            </div>
          </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
          <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
              <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
              <h4 class="font-weight-normal mb-3">Balans <i class="mdi mdi-diamond mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-5">HTG {{ $vente - ($perte + $commission)}}</h2>
              <h6 class="card-text"></h6>
            </div>
          </div>
        </div>
      </div>
      <div class="row">

        <div class="col-12 grid-margin">
          <div class="card">
            <div class="card-body">
              <canvas id="myChart" width="400" height="200"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-7 grid-margin stretch-card" style="display: none;">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Statistik</h4>
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
      <div class="row">
        <div class="col-12 grid-margin">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">3 Denye tiraj ki tire  @php echo "L'heure du serveur est : " . date('Y-m-d H:i:s') . "\n";@endphp</h4>
              <a href="/">  <label class="badge badge-gradient-info">Gade plis</label></a>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Tiraj Nom</th>
                      <th>Date tirage</th>
                      <th>Lo yo</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($list as $lists)
                    <tr>
                    
                      <td>{{$lists->tirage_record->name}}</td>
                     
                      <td>{{ \Carbon\Carbon::parse($lists->created_)->format('d-m-Y') }}
                      </td>
                      <td><button type="button" class="btn btn-social-icon btn-youtube btn-rounded">{{$lists->unchiffre}}</button>  
                          <button type="button" class="btn btn-social-icon btn-facebook btn-rounded">{{$lists->premierchiffre}}</button>
                          <button type="button" class="btn btn-social-icon btn-dribbble btn-rounded">{{$lists->secondchiffre}}</button>
                          <button type="button" class="btn btn-social-icon btn-linkedin btn-rounded">{{$lists->troisiemechiffre}}</button>
                      </td>
                      
                    </tr>
                    @endforeach
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script>
        // Fetch chart data from Laravel route
       
                // Extract data for labels and values
                const labels = ['Bolet: 30','Maryaj: 5092','Loto3: 436', 'Loto4: 5321','Loto5: 52390'];
                const values = ['7000','600', '50','1000','7092'];

                // Create Chart.js chart
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Pri li jwe',
                            data: values,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            
    </script>


   





@endsection