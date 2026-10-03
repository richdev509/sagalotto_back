@extends('superadmin.admin-layout')

@section('content')
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
    <div class="row" >
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Actif POS/Nombre POS<i
                            class="mdi mdi-cash-register mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">{{ $actifPos }}/{{ $nombrePos }}<i
                            class="mdi mdi-cash-register mdi-24px float-right"></i> </h2>
                    <!--<h6 class="card-text">Vandè ki vann plis jodia:  <span style="font-weight: bold;">Bank #12</span></h6>-->
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Nombre de compagnie<i
                            class="mdi mdi-domain mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">{{ $nombreCompagnie }}<i class="mdi mdi-domain mdi-24px float-right"></i></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-warning card-img-holder text-white">
                <div class="card-body">
                    <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Compagnie inactif<i class="mdi mdi-domain mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">{{ $Compagnieinactive }}<i class="mdi mdi-domain mdi-24px float-right"></i></h2>
                    <h6 class="card-text"></h6>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Expiration Tables ===== --}}
    <div class="row mt-2">

        {{-- Table 1: Expired today --}}
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center gap-2 py-2" style="background:#dc3545;color:#fff;">
                    <i class="mdi mdi-alert-circle mdi-18px"></i>
                    <span class="fw-semibold">Compagnies expirées le jour {{ \Carbon\Carbon::today()->day }} ({{ count($expiredToday) }})</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Code</th>
                                    <th>Date expiration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expiredToday as $c)
                                    <tr>
                                        <td>{{ $c->fullname ?? $c->name }}</td>
                                        <td><span class="badge bg-secondary">{{ $c->code }}</span></td>
                                        <td><span class="badge bg-danger">{{ $c->dateexpiration }}</span></td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary btn-generer-facture"
                                                data-company-id="{{ $c->id }}"
                                                data-company-name="{{ $c->fullname ?? $c->name }}"
                                                data-date="{{ $c->dateexpiration }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#genFactureModal">
                                                <i class="mdi mdi-file-document-outline"></i> Facture
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Aucune compagnie expirée aujourd'hui.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table 2: Top 5 oldest expired --}}
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 py-2" style="background:#e67e22;color:#fff;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="mdi mdi-clock-alert-outline mdi-18px"></i>
                        <span class="fw-semibold">Top 5 — Expirées pi ansyen (pi lontan pase)</span>
                    </div>
                    <a href="{{ route('oldest_companies') }}" class="btn btn-sm btn-light" style="color:#e67e22;">
                        <i class="mdi mdi-eye"></i> Voir plus
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Code</th>
                                    <th>Date expiration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingExpiring as $c)
                                    @php
                                        $daysAgo = \Carbon\Carbon::parse($c->dateexpiration)->diffInDays(\Carbon\Carbon::today());
                                        $badgeClass = $daysAgo >= 30 ? 'bg-danger' : 'bg-warning text-dark';
                                    @endphp
                                    <tr>
                                        <td>{{ $c->fullname ?? $c->name }}</td>
                                        <td><span class="badge bg-secondary">{{ $c->code }}</span></td>
                                        <td>
                                            <span class="badge {{ $badgeClass }}">{{ $c->dateexpiration }}</span>
                                            <small class="text-muted d-block">expirée il y a {{ $daysAgo }}j</small>
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary btn-generer-facture"
                                                data-company-id="{{ $c->id }}"
                                                data-company-name="{{ $c->fullname ?? $c->name }}"
                                                data-date="{{ $c->dateexpiration }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#genFactureModal">
                                                <i class="mdi mdi-file-document-outline"></i> Facture
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Aucune expiration prochaine.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Générer Facture Modal --}}
    <div class="modal fade" id="genFactureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header" style="background:#0e75b3;color:#fff;">
                    <h5 class="modal-title"><i class="mdi mdi-file-document-outline"></i> Générer une facture</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('genererfacture') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Compagnie</label>
                            <input type="text" id="gf-company-name" class="form-control" readonly />
                            <input type="hidden" name="company" id="gf-company-id" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date (expiration)</label>
                            <input type="date" name="date" id="gf-date" class="form-control" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Générer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-generer-facture').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.getElementById('gf-company-id').value = this.dataset.companyId;
                    document.getElementById('gf-company-name').value = this.dataset.companyName;
                    document.getElementById('gf-date').value = this.dataset.date;
                });
            });
        });
    </script>

    <script>
      $(document).ready(function(){
        if (false) {
            Chart.defaults.global.legend.labels.usePointStyle = true;
            var ctx = document.getElementById('visit-sale-chart').getContext("2d");

            var gradientStrokeViolet = ctx.createLinearGradient(0, 0, 0, 181);
            gradientStrokeViolet.addColorStop(0, 'rgba(218, 140, 255, 1)');
            gradientStrokeViolet.addColorStop(1, 'rgba(154, 85, 255, 1)');
            var gradientLegendViolet = 'linear-gradient(to right, rgba(218, 140, 255, 1), rgba(154, 85, 255, 1))';

            var gradientStrokeBlue = ctx.createLinearGradient(0, 0, 0, 360);
            gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
            gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');
            var gradientLegendBlue = 'linear-gradient(to right, rgba(54, 215, 232, 1), rgba(177, 148, 250, 1))';

            var gradientStrokeRed = ctx.createLinearGradient(0, 0, 0, 300);
            gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
            gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');
            var gradientLegendRed = 'linear-gradient(to right, rgba(255, 191, 150, 1), rgba(254, 112, 150, 1))';

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG','SEP','OCT','NOV','DEC'],
                    datasets: [{
                            label: "Total",
                            borderColor: gradientStrokeViolet,
                            backgroundColor: gradientStrokeViolet,
                            hoverBackgroundColor: gradientStrokeViolet,
                            legendColor: gradientLegendViolet,
                            pointRadius: 0,
                            fill: false,
                            borderWidth: 1,
                            fill: 'origin',
                            data: [20, 40, 15, 35, 25, 50, 30, 20,0,0,0,0]
                        },
                        {
                            label: "Actif",
                            borderColor: gradientStrokeRed,
                            backgroundColor: gradientStrokeRed,
                            hoverBackgroundColor: gradientStrokeRed,
                            legendColor: gradientLegendRed,
                            pointRadius: 0,
                            fill: false,
                            borderWidth: 1,
                            fill: 'origin',
                            data: [40, 30, 20, 10, 50, 15, 35, 40,0,0,0,0]
                        }

                    ]
                },
                options: {
                    responsive: true,
                    legend: false,
                    legendCallback: function(chart) {
                        var text = [];
                        text.push('<ul>');
                        for (var i = 0; i < chart.data.datasets.length; i++) {
                            text.push('<li><span class="legend-dots" style="background:' +
                                chart.data.datasets[i].legendColor +
                                '"></span>');
                            if (chart.data.datasets[i].label) {
                                text.push(chart.data.datasets[i].label);
                            }
                            text.push('</li>');
                        }
                        text.push('</ul>');
                        return text.join('');
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                display: false,
                                min: 0,
                                stepSize: 20,
                                max: 80
                            },
                            gridLines: {
                                drawBorder: false,
                                color: 'rgba(235,237,242,1)',
                                zeroLineColor: 'rgba(235,237,242,1)'
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                display: false,
                                drawBorder: false,
                                color: 'rgba(0,0,0,1)',
                                zeroLineColor: 'rgba(235,237,242,1)'
                            },
                            ticks: {
                                padding: 20,
                                fontColor: "#9c9fa6",
                                autoSkip: true,
                            },
                            categoryPercentage: 0.5,
                            barPercentage: 0.5
                        }]
                    }
                },
                elements: {
                    point: {
                        radius: 0
                    }
                }
            })
            $("#visit-sale-chart-legend").html(myChart.generateLegend());
        }

      });
    
    </script>
@endsection
