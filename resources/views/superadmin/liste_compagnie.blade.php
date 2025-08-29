@extends('superadmin.admin-layout')
@section('content')

<style>
    :root {
        --primary-color: #113d8e;
        --secondary-color: #f8f9fa;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --text-color: #495057;
        --light-gray: #e9ecef;
        
        /* Dropdown specific colors */
        --dropdown-bg: #ffffff;
        --dropdown-border: #e0e0e0;
        --dropdown-hover: #f5f8ff;
        --dropdown-text: #333333;
        --dropdown-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    /* Table container */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        position: relative;
        margin-bottom: 1rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    /* Table styling */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background-color: white;
        margin-bottom: 0;
    }

    .custom-table thead th {
        background-color: var(--primary-color);
        color: white;
        font-weight: 500;
        padding: 12px 15px;
        border: none;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .custom-table tbody td {
        padding: 12px 15px;
        vertical-align: middle;
        border-bottom: 1px solid var(--light-gray);
        white-space: nowrap;
    }

    /* Row status colors */
    .expired-row {
        background-color: rgba(220, 53, 69, 0.05);
        border-left: 4px solid var(--danger-color);
    }
    
    .expiring-today {
        background-color: rgba(255, 193, 7, 0.05);
        border-left: 4px solid var(--warning-color);
    }
    
    .active-row {
        background-color: rgba(40, 167, 69, 0.05);
        border-left: 4px solid var(--success-color);
    }

    /* Dropdown container */
    .dropdown {
        position: relative !important;
    }

    /* Dropdown menu */
    .dropdown-menu {
        z-index: 9999 !important;
        position: absolute !important;
        background-color: var(--dropdown-bg);
        border: 1px solid var(--dropdown-border);
        border-radius: 8px;
        box-shadow: var(--dropdown-shadow);
        padding: 8px 0;
        min-width: 220px;
        max-width: 100vw;
        top: 100% !important;
        left: 0 !important;
        right: auto !important;
        transform: none !important;
        margin-top: 5px;
        opacity: 0;
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .dropdown-menu.show {
        opacity: 1;
        transform: translateY(0) !important;
    }

    /* Dropdown items styling */
    .dropdown-item {
        padding: 10px 20px;
        color: var(--dropdown-text);
        font-size: 0.9rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        margin: 0 5px;
        border-radius: 4px;
    }

    .dropdown-item:hover {
        background-color: var(--dropdown-hover);
        color: var(--primary-color);
        transform: translateX(3px);
    }

    .dropdown-item i {
        margin-right: 10px;
        width: 18px;
        text-align: center;
    }

    .dropdown-divider {
        border-color: var(--dropdown-border);
        margin: 5px 0;
    }

    .dropdown-action-btn {
        background-color: transparent;
        border: 1px solid var(--light-gray);
        color: var(--primary-color);
        border-radius: 6px;
        padding: 5px 12px;
        transition: all 0.2s;
    }

    .dropdown-action-btn:hover {
        background-color: var(--primary-color);
        color: white;
    }

    /* Search bar styling */
    .search-container {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    
    /* Badge styling */
    .badge {
        padding: 6px 10px;
        font-weight: 500;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    /* Responsive adjustments - Only font size changes */
    @media (max-width: 767.98px) {
        .custom-table {
            font-size: 0.85rem;
        }
        
        .custom-table td, .custom-table th {
            padding: 10px 12px;
        }
        
        .dropdown-menu {
            font-size: 0.85rem;
        }
        
        .dropdown-item {
            padding: 8px 15px;
        }
        
        .search-container {
            padding: 15px;
        }
    }
</style>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="tab-content">
            <div role="tabpanel" id="react-aria-292-tabpane-design" aria-labelledby="react-aria-292-tab-design"
                class="fade pb-3 tab-pane active show">

                <!-- Search Filter -->
                <div class="search-container mb-4">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-8 mb-3 mb-md-0">
                            <label for="searchInput" class="form-label fw-medium">Recherche:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input class="form-control form-control-lg" type="text" id="searchInput" name="text"
                                    placeholder="Nom, code, téléphone, date expiration..." onkeyup="filterTable()"
                                    required />
                            </div>
                        </div>
                        <div class="col-12 col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary ms-auto">
                                <i class="fas fa-filter me-2"></i>Filtrer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Single Table View - Same for all devices -->
                <div class="table-responsive">
                    <table class="table custom-table" id="rapport_table">
                        <thead>
                            <tr>
                                <th width="140px">Actions</th>
                                <th>Compagnie</th>
                                <th>Code</th>
                                <th>Téléphone</th>
                                <th>Plan</th>
                                <th>Date Abon.</th>
                                <th>Date Exp.</th>
                                <th>Statut</th>
                                <th>Référence</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @foreach ($data as $donnee)
                                @php
                                    $ref = DB::table('reference')->where('id', $donnee->id_reference)->first();
                                    $expirationDate = \Carbon\Carbon::parse($donnee->dateexpiration);
                                    $currentDate = \Carbon\Carbon::now();
                                    $isExpired = $expirationDate->lessThan($currentDate);
                                    $isExpiringToday = $expirationDate->isToday();
                                    $rowClass = '';
                                    
                                    if ($isExpired) {
                                        $rowClass = 'expired-row';
                                    } elseif ($isExpiringToday) {
                                        $rowClass = 'expiring-today';
                                    } else {
                                        $rowClass = 'active-row';
                                    }
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn dropdown-action-btn dropdown-toggle" type="button"
                                                id="dropdownMenuButton{{ $donnee->id }}" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $donnee->id }}">
                                                @if (session('role') == 'admin' || session('role') == 'admin2' || session('role') == 'addeur')
                                                    <li>
                                                        <form action="{{ route('edit_compagnie') }}" method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $donnee->id }}" />
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-edit text-primary"></i>Modifier
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                <li>
                                                    <form action="{{ route('login_as_company') }}" method="GET"
                                                        target="_blank" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $donnee->id }}" />
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-sign-in-alt text-info"></i>Connexion
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="genererfacture" method="POST" target="_blank"
                                                        class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="company" value="{{ $donnee->id }}" />
                                                        <input type="hidden" name="date"
                                                            value="{{ $donnee->dateexpiration }}" />
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-file-invoice text-warning"></i>Facture
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    @if (session('role') == 'admin' || session('role') == 'comptable')
                                                        <form action="{{ route('add_abonnement2') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $donnee->id }}" />
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-sync-alt text-success"></i>Mettre à jour
                                                            </button>
                                                        </form>
                                                    @endif
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('listecompagnieU') }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="idcompagnie" value="{{ $donnee->id }}" />
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-eye text-secondary"></i>Détails
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>

                                    <td class="fw-medium">{{ Str::limit($donnee->name, 15) }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $donnee->code }}</span></td>
                                    <td>{{ $donnee->phone }}</td>
                                    <td><span class="badge bg-primary">{{ $donnee->plan }}</span></td>
                                    <td class="text-nowrap">{{ $donnee->dateplan }}</td>

                                    <td class="text-nowrap">
                                        @if ($isExpiringToday)
                                            <span class="badge bg-warning text-dark">{{ $donnee->dateexpiration }}</span>
                                        @elseif ($isExpired)
                                            <span class="badge bg-danger">{{ $donnee->dateexpiration }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $donnee->dateexpiration }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($donnee->is_block == 1)
                                            <span class="badge bg-danger">Bloqué</span>
                                        @else
                                            <span class="badge bg-success">Actif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $ref ? $ref->name : 'N/A' }}</span>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Handle dropdown positioning
        document.querySelectorAll('.dropdown').forEach(function(dropdown) {
            dropdown.addEventListener('shown.bs.dropdown', function() {
                const menu = this.querySelector('.dropdown-menu');
                const button = this.querySelector('.dropdown-toggle');
                
                // Reset positioning
                menu.style.left = '0';
                menu.style.right = 'auto';
                menu.style.top = '100%';
                
                // Check viewport boundaries
                const menuRect = menu.getBoundingClientRect();
                const buttonRect = button.getBoundingClientRect();
                
                // Adjust if going off right of screen
                if (menuRect.right > window.innerWidth) {
                    menu.style.left = 'auto';
                    menu.style.right = '0';
                }
                
                // Adjust if going off bottom of screen
                if (menuRect.bottom > window.innerHeight) {
                    menu.style.top = 'auto';
                    menu.style.bottom = '100%';
                }
                
                // Animate in
                menu.style.opacity = '0';
                menu.style.transform = 'translateY(-5px)';
                setTimeout(() => {
                    menu.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                    menu.style.opacity = '1';
                    menu.style.transform = 'translateY(0)';
                }, 10);
            });
        });
    });

    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        
        // Show loading state
        document.getElementById("tableBody").style.opacity = "0.5";
        
        setTimeout(() => {
            // Filter table rows
            const rows = document.querySelectorAll("#rapport_table tbody tr");
            rows.forEach((row) => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(filter) ? "" : "none";
            });
            
            // Restore opacity
            document.getElementById("tableBody").style.opacity = "1";
        }, 100);
    }
</script>

@stop