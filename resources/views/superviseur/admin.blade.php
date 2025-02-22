@extends('superviseur.admin-layout')

@section('content')
    <style>
        /* Custom Styles */
        .card-img-holder {
            position: relative;
        }

        .card-img-absolute {
            position: absolute;
            top: 0;
            right: 0;
            height: 100%;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .badge-gradient-info {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #0d2a95;
            color: white;
            font-weight: bold;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-rounded {
            border-radius: 20px;
            margin: 2px;
            font-size: 0.9rem;
            padding: 5px 10px;
        }

        .btn-youtube {
            background-color: #ff0000;
            color: white;
        }

        .btn-facebook {
            background-color: #1877f2;
            color: white;
        }

        .btn-dribbble {
            background-color: #ea4c89;
            color: white;
        }

        .btn-linkedin {
            background-color: #0077b5;
            color: white;
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item.active {
            color: #0d2a95;
            font-weight: bold;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .page-title-icon {
            padding: 10px;
            border-radius: 50%;
        }

        .bg-gradient-primary {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
        }

        .bg-gradient-danger {
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
        }

        .bg-gradient-info {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
        }

        .bg-gradient-success {
            background: linear-gradient(45deg, #00b09b, #96c93d);
        }
    </style>

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
        <!-- Sales Card -->
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Kob ou vann jodia <i class="mdi mdi-chart-line mdi-24px float-right"></i></h4>
                    <h2 class="mb-5">HTG {{ $vente }}</h2>
                </div>
            </div>
        </div>

        <!-- Losses Card -->
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Kob ou peye jodia <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                    <h2 class="mb-5">HTG {{ $perte }}</h2>
                </div>
            </div>
        </div>

        <!-- Balance Card -->
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Balans <i class="mdi mdi-diamond mdi-24px float-right"></i></h4>
                    <h2 class="mb-5">HTG {{ $vente - ($perte + $commission) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Draws Table -->
    <div class="row" style="margin-top: 10px;">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">3 Denye tiraj ki tire @php echo "L'heure du serveur est : " . date('Y-m-d H:i:s') . "\n"; @endphp</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tiraj Nom</th>
                                    <th>Date tirage</th>
                                    <th>Lo yo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $lists)
                                    <tr style="border: 1px solid black; font-weight: bold;">
                                        <td style="color: {{ $lists['name'] == 'NewYork Soir' ? 'blue' : ($lists['name'] == 'NewYork Matin' ? '#06aafd' : ($lists['name'] == 'Florida Matin' ? '#53ca8c' : ($lists['name'] == 'Florida Soir' ? '#30be64' : ($lists['name'] == 'Georgia Matin' ? '#be3030' : ($lists['name'] == 'Georgia ApresMidi' ? '#fa8e8e' : 'inherit'))))) }}">
                                            {{ $lists['name'] }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($lists['boulGagnant']->created_)->format('d-m-Y') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-social-icon btn-youtube btn-rounded">{{ $lists['boulGagnant']->unchiffre }}</button>
                                            <button type="button" class="btn btn-social-icon btn-facebook btn-rounded">{{ $lists['boulGagnant']->premierchiffre }}</button>
                                            <button type="button" class="btn btn-social-icon btn-dribbble btn-rounded">{{ $lists['boulGagnant']->secondchiffre }}</button>
                                            <button type="button" class="btn btn-social-icon btn-linkedin btn-rounded">{{ $lists['boulGagnant']->troisiemechiffre }}</button>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid black;">
                                        <td>Vann: <span>{{ $lists['vent'] ?? 0 }} HTG</span></td>
                                        <td>Pedi: <span>{{ $lists['pert'] ?? 0 }} HTG</span></td>
                                        <td>Balans: <span>{{ $lists['vent'] - ($lists['pert'] + $lists['commissio']) }} HTG</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection