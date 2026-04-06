@extends('superviseur.admin-layout')

@section('content')
    <style>
        /* Layout and cards */
        .card-img-holder { position: relative; }
        .card-img-absolute { position: absolute; top: 0; right: 0; height: 100%; opacity: .15; }
        .card { border-radius: 12px; box-shadow: 0 6px 16px rgba(0,0,0,.08); transition: transform .25s ease, box-shadow .25s ease; border: 0; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 10px 22px rgba(0,0,0,.14); }
        .card-body { padding: 1.4rem 1.6rem; }
        .card-title { font-size: 1.25rem; font-weight: 700; color: #2d2d2d; }

        /* Gradients */
        .bg-gradient-primary { background: linear-gradient(135deg, #4B49AC 0%, #6f6bb8 100%); }
        .bg-gradient-danger { background: linear-gradient(135deg, #ff4d4f, #ff7a45); }
        .bg-gradient-info { background: linear-gradient(135deg, #00b4d8, #0077b6); }
        .bg-gradient-success { background: linear-gradient(135deg, #2ecc71, #27ae60); }
        .bg-gradient-warning { background: linear-gradient(135deg, #f39c12, #f1c40f); }

        /* Table modern */
        .table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
        .table thead th { background: #f6f7fb; color: #515b6d; font-weight: 700; font-size: 13px; text-transform: uppercase; border: 0; padding: 14px; }
        .table tbody tr { background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,.05); }
        .table td { border-top: 0; padding: 14px; vertical-align: middle; }

        /* Chips for winning numbers */
        .chip { display: inline-flex; align-items: center; justify-content: center; min-width: 44px; height: 36px; padding: 0 12px; border-radius: 18px; color: #fff; font-weight: 700; letter-spacing: .5px; margin-right: 6px; }
        .chip.lo1 { background: linear-gradient(135deg, #ff7675, #d63031); }
        .chip.lo2 { background: linear-gradient(135deg, #6c5ce7, #4b4ae6); }
        .chip.lo3 { background: linear-gradient(135deg, #00cec9, #00b894); }
        .chip.lo4 { background: linear-gradient(135deg, #f39c12, #e67e22); }

    /* Small meta row - expanded */
    .meta { display:grid; grid-template-columns: repeat(3, 1fr); gap:16px; font-weight:700; margin-top: 6px; }
    .pill { display:flex; align-items:center; justify-content:space-between; padding:12px 14px; border-radius:10px; color:#fff; box-shadow:0 4px 10px rgba(0,0,0,.08); }
    .pill .label { opacity:.9; font-size:.9rem; letter-spacing:.3px; }
    .pill .value { font-size:1.05rem; }
    .pill.vann { background: linear-gradient(135deg, #ff4d4f, #ff7a45); }
    .pill.pedi { background: linear-gradient(135deg, #00b4d8, #0077b6); }
    .pill.balans { background: linear-gradient(135deg, #2ecc71, #27ae60); }

        /* Page */
        .breadcrumb { background-color: transparent; padding: 0; margin: 0; }
        .breadcrumb-item.active { color: #4B49AC; font-weight: 700; }
        .page-header { margin-bottom: 16px; }
        .page-title-icon { padding: 10px; border-radius: 50%; }
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
    <div class="col-lg-4 col-md-6 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Vant Jodi A <i class="mdi mdi-chart-line mdi-24px float-right"></i></h4>
                    <h2 class="mb-2">HTG {{ number_format($vente ?? 0, 0, '.', ' ') }}</h2>
                </div>
            </div>
        </div>

        <!-- Losses Card -->
    <div class="col-lg-4 col-md-6 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Peman Jodi A <i class="mdi mdi-cash-multiple mdi-24px float-right"></i></h4>
                    <h2 class="mb-2">HTG {{ number_format($perte ?? 0, 0, '.', ' ') }}</h2>
                </div>
            </div>
        </div>


        <!-- Balance Card -->
    <div class="col-lg-4 col-md-6 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Balans <i class="mdi mdi-diamond mdi-24px float-right"></i></h4>
                    <h2 class="mb-2">HTG {{ number_format(($vente ?? 0) - (($perte ?? 0) + ($commission ?? 0)), 0, '.', ' ') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Draws Table -->
    <div class="row" style="margin-top: 10px;">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">3 Denye Tiraj • <span class="badge-gradient-info" style="border-radius:16px;padding:6px 10px;">Sèvè: {{ now()->format('Y-m-d H:i:s') }}</span></h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tiraj</th>
                                    <th>Dat</th>
                                    <th>Lo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $lists)
                                    @php
                                        $tirageColors = [
                                            'NewYork Soir' => '#1e90ff',
                                            'NewYork Matin' => '#06aafd',
                                            'Florida Matin' => '#53ca8c',
                                            'Florida Soir' => '#30be64',
                                            'Georgia Matin' => '#be3030',
                                            'Georgia ApresMidi' => '#fa8e8e',
                                        ];
                                        $name = $lists['name'] ?? 'Tiraj';
                                        $tColor = $tirageColors[$name] ?? '#4B49AC';
                                        $vent = (float)($lists['vent'] ?? 0);
                                        $pert = (float)($lists['pert'] ?? 0);
                                        $comm = (float)($lists['commissio'] ?? 0);
                                        $bal = $vent - ($pert + $comm);
                                    @endphp
                                    <tr style="font-weight: 600;">
                                        <td style="color: {{ $tColor }};">{{ $name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lists['boulGagnant']->created_)->format('d-m-Y') }}</td>
                                        <td>
                                            <span class="chip lo1">{{ $lists['boulGagnant']->unchiffre }}</span>
                                            <span class="chip lo2">{{ $lists['boulGagnant']->premierchiffre }}</span>
                                            <span class="chip lo3">{{ $lists['boulGagnant']->secondchiffre }}</span>
                                            <span class="chip lo4">{{ $lists['boulGagnant']->troisiemechiffre }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <div class="meta">
                                                <div class="pill vann">
                                                    <span class="label"><i class="mdi mdi-chart-line"></i> Vann</span>
                                                    <span class="value">HTG {{ number_format($vent, 0, '.', ' ') }}</span>
                                                </div>
                                                <div class="pill pedi">
                                                    <span class="label"><i class="mdi mdi-cash"></i> Pedi</span>
                                                    <span class="value">HTG {{ number_format($pert, 0, '.', ' ') }}</span>
                                                </div>
                                                <div class="pill balans">
                                                    <span class="label"><i class="mdi mdi-finance"></i> Balans</span>
                                                    <span class="value">HTG {{ number_format($bal, 0, '.', ' ') }}</span>
                                                </div>
                                            </div>
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
@endsection