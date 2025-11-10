<div>
    <!-- Skeleton Loading pendant le chargement -->
    <div class="page-header" style="background-color: white;
    border-radius: 9px;">
        <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 8px;">
            <div class="d-flex align-items-center" style="gap:8px;">
                <span class="page-title-icon bg-gradient-primary text-white me-2" style="border-radiu: 100%; margin-left: 5px;">
                    <i class="mdi mdi-home"></i>
                </span>
                <h3 class="page-title mb-0">Dashboard</h3>
            </div>
            <button wire:click="refreshData" wire:loading.attr="disabled" class="btn btn-sm btn-outline-primary ms-2 mt-2 mt-md-0" style="white-space:nowrap;">
                <span wire:loading.class="mdi mdi-loading mdi-spin" wire:loading.remove.class="mdi mdi-refresh"></span>
                Actualiser
            </button>
        </div>
    </div>

    <div class="row">
    @php
        use App\Models\blockCompagnie;
        use Carbon\Carbon;
        $currentCompagnieId = session('loginId');
        $blocked = blockCompagnie::where('compagnie_id', $currentCompagnieId)
            ->where('blocked_at', '>', Carbon::now())
            ->orderBy('blocked_at', 'asc')
            ->first();
    @endphp
    @if($blocked)
        <div id="block-countdown" style="width:100%; text-align:center; color:#dc3545; font-size:16px; cursor:pointer; margin-bottom:10px;" onclick="showBlockMessage()">
            <span style="display:inline-block; width:100%; cursor:pointer;background-color: white; boder-radius: 4px;" >
                <i class="fas fa-exclamation-circle icon" style="font-size: 18px; color: #dc3545;"></i>
                Wap bloke nan: <span id="countdown-timer"></span> <span style="font-size:14px; font-weight:normal;">peze pouw we rezon an</span>
            </span>
        </div>
        <script>
            function updateCountdown() {
                var blockedAt = new Date(@json($blocked->blocked_at));
                var now = new Date();
                var diff = blockedAt - now;
                if (diff > 0) {
                    var hours = Math.floor(diff / (1000 * 60 * 60));
                    var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        document.getElementById('countdown-timer').innerHTML = hours.toString().padStart(2, '0') + 'h ' + minutes.toString().padStart(2, '0') + 'm ' + seconds.toString().padStart(2, '0') + 's';
                } else {
                    document.getElementById('countdown-timer').innerHTML = '00h:00m:00s';
                }
            }
            updateCountdown();
            setInterval(updateCountdown, 1000);
            function showBlockMessage() {
                var msg = @json($blocked->message);
                alert(msg);
            }
        </script>
    @endif
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card" style="background-color:#f06292;
    color: white;">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Kob ou vann jodia <i class="mdi mdi-chart-line mdi-24px float-right" style="color:aliceblue;"></i></h4>
                    @if($loading)
                        <div class="d-flex justify-content-center align-items-center" style="height: 50px;">
                            <div class="spinner-border text-light" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    @else
                        <h2 class="mb-5">{{ Session('devise') }} {{ number_format($vente, 2,'.', ' ') }}</h2>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card" style="background-color: #42A5F5;
    color: white;">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Kob ou peye jodia <i class="mdi mdi-bookmark-outline mdi-24px float-right" style="color: #ffa704;"></i></h4>
                    @if($loading)
                        <div class="d-flex justify-content-center align-items-center" style="height: 50px;">
                            <div class="spinner-border text-light" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    @else
                        <h2 class="mb-5">{{ Session('devise') }} {{ number_format($perte, 2,'.', ' ') }}</h2>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card" style="background-color
: #26A69A;
    color: white;">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Balans <i class="mdi mdi-diamond mdi-24px float-right" style="
    color: #f9ff04;"></i></h4>
                    @if($loading)
                        <div class="d-flex justify-content-center align-items-center" style="height: 50px;">
                            <div class="spinner-border text-light" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    @else
                        <h2 class="mb-5">{{ Session('devise') }} {{ number_format($vente - ($perte + $commission), 2, '.', ' ') }}</h2>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="cont">
        <a href="/raport2" style="z-index:1;">
        <div class="column">
            
                <i class="icon mdi mdi-account"></i>
                <span class="text">Bank aktif: <span style="color: var(--success-color);">{{ $actif_user }}</span>/{{ $total_user }}</span>
           
        </div>
         </a>

           <a href="/lister-ticket" style="z-index:1;">
        <div class="column">
          
                <i class="icon mdi mdi-ticket"></i>
                <span class="text">Fich genyen: <span style="color: var(--success-color);">{{ $ticket_win }}</span>/{{ $ticket_total }}</span>
            
        </div>
        </a>

         <a href="/lister-ticket-delete" style="z-index:1;">
        <div class="column">
           
                <i class="icon mdi mdi-delete" style="color: var(--danger-color);"></i>
                <span class="text">Fich anile: <span style="color: var(--danger-color);">{{ $ticket_delete }}</span>/{{ $ticket_total + $ticket_delete }}</span>
          
        </div>
          </a>
    </div>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">3 Denye tiraj ki tire yo, lè sèvè se: {{ now()->format('Y-m-d H:i:s') }}</h4>
                    <a href="/lister-lo"> <label class="badge badge-gradient-info">Gade plis</label></a>
                    @if($list_loading)
                    <div class="d-flex justify-content-center align-items-center" style="height: 120px;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    @else
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
                                        <td style="color: {{ App\Services\TirageService::getColor($lists['name']) }}; font-weight: bold;">
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
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bar Chart Section: Last 7 Days Vente & Perte -->
    <div class="row mt-2" style="">
        <div class="col-12">
            <div class="card" style="margin-bottom: 8px;">
                <div class="card-body">
                    <h4 class="card-title">Vann & Pèdi- 7 Jou ki pase yo</h4>
                    @if($loading)
                        <div class="d-flex justify-content-center align-items-center" style="height: 220px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    @else
                        <div style="width: 100%; max-width: 100vw;">
                            <canvas id="dashboardBarChart" style="height:320px;max-height:60vh;width:100vw;max-width:100vw;" height="320"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function renderDashboardChart() {
            var canvas = document.getElementById('dashboardBarChart');
            if (!canvas || canvas.offsetParent === null) return false;
            var width = canvas.parentElement.offsetWidth;
            var height = 320;
            var dpr = window.devicePixelRatio || 1;
            canvas.width = width * dpr;
            canvas.height = height * dpr;
            canvas.style.width = width + 'px';
            canvas.style.height = height + 'px';
            var ctx = canvas.getContext('2d');
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            if (window.dashboardChart) {
                window.dashboardChart.destroy();
            }
            window.dashboardChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($dates ?? []) !!},
                    datasets: [
                        {
                            label: 'Vente',
                            data: {!! json_encode($ventes ?? []) !!},
                            backgroundColor: 'rgba(40,167,69,0.7)',
                            borderColor: 'rgba(40,167,69,1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Perte',
                            data: {!! json_encode($pertes ?? []) !!},
                            backgroundColor: 'rgba(220,53,69,0.7)',
                            borderColor: 'rgba(220,53,69,1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true },
                        title: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
            return true;
        }
        // MutationObserver pou détecter l’apparition du canvas
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (document.getElementById('dashboardBarChart')) {
                    if (renderDashboardChart()) {
                        observer.disconnect(); // Stoppe l’observation après le premier rendu
                    }
                }
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });
        window.addEventListener('resize', function () {
            renderDashboardChart();
        });
    </script>
</div>

