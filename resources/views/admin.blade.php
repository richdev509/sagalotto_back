@extends('admin-layout')

@section('content')
    <style>
        /* Modern color palette */
        :root {
            --primary-color: #6c5ce7;
            --secondary-color: #a29bfe;
            --success-color: #00b894;
            --danger-color: #d63031;
            --info-color: #0984e3;
            --warning-color: #fdcb6e;
            --background-color: #f8f9fa;
            --text-color: #2d3436;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-img-holder {
            position: relative;
        }

        .card-img-absolute {
            position: absolute;
            top: -20px;
            right: -20px;
            opacity: 0.2;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-color);
        }

        .card-text {
            font-size: 0.9rem;
            color: #666;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-gradient-info {
            background: linear-gradient(135deg, var(--info-color), var(--secondary-color));
            color: white;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        .table tbody tr {
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .table tbody td {
            padding: 15px;
            border: none;
        }

        .btn-rounded {
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-social-icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 2px;
        }

        .cont {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .column {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1;
            min-width: 250px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .column:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .icon {
            font-size: 24px;
            color: var(--primary-color);
        }

        .text {
            font-size: 16px;
            color: var(--text-color);
        }

        @media (max-width: 768px) {
            .cont {
                flex-direction: column;
            }

            .column {
                width: 100%;
            }
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
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-danger">
                <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Kob ou vann jodia <i
                            class="mdi mdi-chart-line mdi-24px float-right"></i></h4>
                    <h2 class="mb-5">{{ Session('devise') }} {{ number_format($vente, 2,'.', ' ') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info">
                <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Kob ou peye jodia <i
                            class="mdi mdi-bookmark-outline mdi-24px float-right"></i></h4>
                    <h2 class="mb-5">{{ Session('devise') }} {{ number_format($perte, 2,'.', ' ') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-success">
                <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Balans <i class="mdi mdi-diamond mdi-24px float-right"></i></h4>
                    <h2 class="mb-5">{{ Session('devise') }} {{ number_format($vente - ($perte + $commission), 2, '.', ' ') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="cont">
        <div class="column">
            <a href="/raport2">
                <i class="icon mdi mdi-account"></i>
                <span class="text">Bank aktif: <span
                        style="color: var(--success-color);">{{ $actif_user }}</span>/{{ $total_user }}</span>
            </a>
        </div>
        <div class="column">
            <a href="/lister-ticket">
                <i class="icon mdi mdi-ticket"></i>
                <span class="text">Fich genyen: <span
                        style="color: var(--success-color);">{{ $ticket_win }}</span>/{{ $ticket_total }}</span>
            </a>
        </div>
        <div class="column">
            <a href="/lister-ticket-delete">

                <i class="icon mdi mdi-delete" style="color: var(--danger-color);"></i>
                <span class="text">Fich anile: <span
                        style="color: var(--danger-color);">{{ $ticket_delete }}</span>/{{ $ticket_total + $ticket_delete }}</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">3 Denye tiraj ki tire yo, lè sèvè se: @php echo date('Y-m-d H:i:s'); @endphp</h4>
                    <a href="/lister-lo"> <label class="badge badge-gradient-info">Gade plis</label></a>
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
                                    <tr>
                                        <td style="color: {{ getTirageColo($lists['name']) }}; font-weight: bold;">
                                            {{ $lists['name'] }}</td>
                                        <td style="font-weight: bold;">
                                            {{ \Carbon\Carbon::parse($lists['boulGagnant']->created_)->format('d-m-Y') }}
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-social-icon btn-youtube btn-rounded">{{ $lists['boulGagnant']->unchiffre }}</button>
                                            <button type="button"
                                                class="btn btn-social-icon btn-facebook btn-rounded">{{ $lists['boulGagnant']->premierchiffre }}</button>
                                            <button type="button"
                                                class="btn btn-social-icon btn-dribbble btn-rounded">{{ $lists['boulGagnant']->secondchiffre }}</button>
                                            <button type="button"
                                                class="btn btn-social-icon btn-linkedin btn-rounded">{{ $lists['boulGagnant']->troisiemechiffre }}</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;">Vann: <span>{{ $lists['vent'] ?? 0 }}
                                                {{ Session('devise') }}</span></td>
                                        <td style="font-weight: bold;">Pedi: <span>{{ $lists['pert'] ?? 0 }}
                                                {{ Session('devise') }}</span></td>
                                        <td style="font-weight: bold;">Balans:
                                            <span>{{ $lists['vent'] - ($lists['pert'] + $lists['commissio']) }}
                                                {{ Session('devise') }}</span>
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
    @php
        function getTirageColo($tirage)
        {
            $colors = [
                'NewYork Soir' => 'blue',
                'NewYork Matin' => '#06aafd',
                'Florida Matin' => '#53ca8c',
                'Florida Soir' => '#30be64',
                'Georgia Matin' => '#be3030',
                'Georgia ApresMidi' => '#fa8e8e',
            ];
            return $colors[$tirage] ?? 'black';
        }
    @endphp
@endsection
