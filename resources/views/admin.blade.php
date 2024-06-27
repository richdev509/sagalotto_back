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
                    <h4 class="font-weight-normal mb-3">Kob ou vann jodia <i
                            class="mdi mdi-chart-line mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">HTG {{ $vente }}</h2>
                    <!--<h6 class="card-text">Vandè ki vann plis jodia:  <span style="font-weight: bold;">Bank #12</span></h6>-->
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Kob ou peye jodia <i
                            class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">HTG {{ $perte }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Balans <i class="mdi mdi-diamond mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">HTG {{ $vente - ($perte + $commission) }}</h2>
                    <h6 class="card-text"></h6>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">3 Denye tiraj ki tire @php echo "L'heure du serveur est : " . date('Y-m-d H:i:s') . "\n";@endphp</h4>
                    <a href="/"> <label class="badge badge-gradient-info">Gade plis</label></a>
                    <div class="table-responsive">
                        <table class="table  table-striped">
                            <thead>
                                <tr>
                                    <th>Tiraj Nom</th>
                                    <th>Date tirage</th>
                                    <th>Lo yo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $lists)
                                    <tr style="border: 1px solid black;font-weight: bold;">
                                    <tr>
                                        @if ($lists['name'] == 'NewYork Soir')
                                            <td style="color:blue;">
                                                {{ $lists['name'] }}
                                            </td>
                                        @elseif($lists['name'] == 'NewYork Matin')
                                            <td style="color: #06aafd">
                                                {{ $lists['name'] }}
                                            </td>
                                        @elseif($lists['name'] == 'Florida Matin')
                                            <td style="color: #53ca8c">
                                                {{ $lists['name'] }}
                                            </td>
                                        @elseif($lists['name'] == 'Florida Soir')
                                            <td style="color: #30be64">
                                                {{ $lists['name'] }}
                                            </td>
                                        @elseif($lists['name'] == 'Georgia Matin')
                                            <td style="color: #be3030">
                                                {{ $lists['name'] }}
                                            </td>
                                        @elseif($lists['name'] == 'Georgia ApresMidi')
                                            <td style="color: #fa8e8e">
                                                {{ $lists['name'] }}
                                            </td>
                                        @else
                                            <td>
                                                {{ $lists['name'] }}
                                            </td>
                                        @endif
                                        </td>
                                        <td style="font-weight: bold;">
                                            {{ \Carbon\Carbon::parse($lists['boulGagnant']->created_)->format('d-m-Y') }}
                                        </td>
                                        <td style="font-weight: bold;"><button type="button"
                                                class="btn btn-social-icon btn-youtube btn-rounded">{{ $lists['boulGagnant']->unchiffre }}</button>
                                            <button type="button"
                                                class="btn btn-social-icon btn-facebook btn-rounded">{{ $lists['boulGagnant']->premierchiffre }}</button>
                                            <button type="button"
                                                class="btn btn-social-icon btn-dribbble btn-rounded">{{ $lists['boulGagnant']->secondchiffre }}</button>
                                            <button type="button"
                                                class="btn btn-social-icon btn-linkedin btn-rounded">{{ $lists['boulGagnant']->troisiemechiffre }}</button>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td style="font-weight: bold;">
                                            Vann: <span>{{ $lists['vente'] ?? 0}} HTG</span>
                                        </td>

                                        <td style="font-weight: bold;">
                                            Pedi: <span>{{ $lists['perte'] ?? 0 }} HTG</span>

                                        </td>
                                        <td style="font-weight: bold;">
                                            Balans: <span>{{ $lists['vente'] - ($lists['perte'] + $lists['commission']) }} HTG</span>

                                        </td>
                                    </tr>
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
        const labels = ['Bolet: 30', 'Maryaj: 5092', 'Loto3: 436', 'Loto4: 5321', 'Loto5: 52390'];
        const values = ['7000', '600', '50', '1000', '7092'];

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
