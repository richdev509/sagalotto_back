@extends('admin-layout')
@section('content')
<style>

    .hover-primary:hover {
        background-color: rgba(67, 97, 238, 0.05) !important;
    }
    
    .bg-light-primary {
        background-color: rgba(67, 97, 238, 0.1);
    }
    
    .border-primary {
        border-color: #4361ee !important;
    }
    
    .checkbox-lg {
        width: 18px;
        height: 18px;
    }
    
    .badge {
        padding: 5px 8px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .btn-rounded {
        border-radius: 20px;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .select2-single {
        border-radius: 4px !important;
    }
   .card-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .table th {
        border-top: none;
        font-weight: 600;
    }
    .form-switch .form-check-input {
        width: 2.5em;
        margin-left: -0.5em;
    }
    .input-group-text {
        min-width: 45px;
        justify-content: center;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05);
    }
    .mdi {
        font-size: 1.1em;
        vertical-align: middle;
    }
</style>
<div class="page-header">
    <h3 class="page-title">
        <i class="mdi mdi-cash-multiple mr-2 text-primary"></i>Limit Pri Acha
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="admin"><i class="mdi mdi-home mr-1"></i>Akèy</a></li>
        </ol>
    </nav>
</div>

<!-- First Table: Limit Pri acha -->
<div class="col-lg-12 stretch-card">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="card-title mb-0">
                <i class="mdi mdi-tune mr-2"></i>Konfigirasyon Limit Pri
            </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="bg-light-primary">
                        <tr>
                            <th><i class="mdi mdi-format-title mr-1"></i> Non </th>
                            <th><i class="mdi mdi-currency-usd mr-1"></i> Pri </th>
                            <th><i class="mdi mdi-power mr-1"></i> Estati </th>
                            <th><i class="mdi mdi-cog mr-1"></i> Aksyon </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Bolet -->
                        <form method="post" action="{{ route('limitprixstore') }}">
                            @csrf
                            <tr>
                                <input type="hidden" name="id" value="bolet" />
                                <td class="align-middle">
                                    <i class="mdi mdi-ticket-confirmation mr-2 text-primary"></i>Bolet
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        @if ($limitprix)
                                            <input type="number" class="form-control form-control-sm"
                                                value="{{ $limitprix->bolet ?? '' }}" name="prix" style="padding: 5px;min-width: 100px;" />
                                        @else
                                            <input style="padding: 5px;min-width: 100px;" type="number" class="form-control form-control-sm" value=""
                                                name="prix" />
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="active" id="boletActive"
                                            {{ $limitprix->boletetat == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="boletActive">
                                            {{ $limitprix->boletetat == 1 ? 'Aktif' : 'Inaktif' }}
                                        </label>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="mdi mdi-pencil mr-1"></i> Modifye
                                    </button>
                                </td>
                            </tr>
                        </form>

                        <!-- Maryaj -->
                        <form method="post" action="{{ route('limitprixstore') }}">
                            @csrf
                            <tr>
                                <input type="hidden" name="id" value="maryaj" />
                                <td class="align-middle">
                                    <i class="mdi mdi-dice-2 mr-2 text-primary"></i>Maryaj
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        @if ($limitprix)
                                            <input type="number" class="form-control form-control-sm"
                                                value="{{ $limitprix->maryaj ?? '' }}" name="prix" />
                                        @else
                                            <input type="number" class="form-control form-control-sm" value=""
                                                name="prix" />
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="active" id="maryajActive"
                                            {{ $limitprix->maryajetat == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="maryajActive">
                                            {{ $limitprix->maryajetat == 1 ? 'Aktif' : 'Inaktif' }}
                                        </label>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="mdi mdi-pencil mr-1"></i> Modifye
                                    </button>
                                </td>
                            </tr>
                        </form>

                        <!-- Loto3 -->
                        <form method="post" action="{{ route('limitprixstore') }}">
                            @csrf
                            <tr>
                                <input type="hidden" name="id" value="loto3" />
                                <td class="align-middle">
                                    <i class="mdi mdi-dice-3 mr-2 text-primary"></i>Loto3
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        @if ($limitprix)
                                            <input type="number" class="form-control form-control-sm"
                                                value="{{ $limitprix->loto3 ?? '' }}" name="prix" />
                                        @else
                                            <input type="number" class="form-control form-control-sm" value=""
                                                name="prix" />
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="active" id="loto3Active"
                                            {{ $limitprix->loto3etat == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="loto3Active">
                                            {{ $limitprix->loto3etat == 1 ? 'Aktif' : 'Inaktif' }}
                                        </label>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="mdi mdi-pencil mr-1"></i> Modifye
                                    </button>
                                </td>
                            </tr>
                        </form>

                        <!-- Loto4 -->
                        <form method="post" action="{{ route('limitprixstore') }}">
                            @csrf
                            <tr>
                                <input type="hidden" name="id" value="loto4" />
                                <td class="align-middle">
                                    <i class="mdi mdi-dice-4 mr-2 text-primary"></i>Loto4
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        @if ($limitprix)
                                            <input type="number" class="form-control form-control-sm"
                                                value="{{ $limitprix->loto4 ?? '' }}" name="prix" />
                                        @else
                                            <input type="number" class="form-control form-control-sm" value=""
                                                name="prix" />
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="active" id="loto4Active"
                                            {{ $limitprix->loto4etat == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="loto4Active">
                                            {{ $limitprix->loto4etat == 1 ? 'Aktif' : 'Inaktif' }}
                                        </label>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="mdi mdi-pencil mr-1"></i> Modifye
                                    </button>
                                </td>
                            </tr>
                        </form>

                        <!-- Loto5 -->
                        <form method="post" action="{{ route('limitprixstore') }}">
                            @csrf
                            <tr>
                                <input type="hidden" name="id" value="loto5" />
                                <td class="align-middle">
                                    <i class="mdi mdi-dice-5 mr-2 text-primary"></i>Loto5
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        @if ($limitprix)
                                            <input type="number" class="form-control form-control-sm"
                                                value="{{ $limitprix->loto5 ?? '' }}" name="prix" />
                                        @else
                                            <input type="number" class="form-control form-control-sm" value=""
                                                name="prix" />
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="active" id="loto5Active"
                                            {{ $limitprix->loto5etat == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="loto5Active">
                                            {{ $limitprix->loto5etat == 1 ? 'Aktif' : 'Inaktif' }}
                                        </label>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="mdi mdi-pencil mr-1"></i> Modifye
                                    </button>
                                </td>
                            </tr>
                        </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Second Table: Limit pri pa boul -->
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card border-primary shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">
                <i class="mdi mdi-filter-variant mr-2"></i>Limit pri pa boul
            </h4>
            <i class="mdi mdi-database-search fs-4"></i>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label"><i class="mdi mdi-calendar-filter mr-1"></i>Filtre par Tiraj:</label>
                    <select class="form-control select2-single" id="selecttiraj">
                        <option value=""></option>
                        @foreach ($listetirage as $lists)
                            <option value="{{ $lists->name }}">{{ $lists->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><i class="mdi mdi-filter-cog mr-1"></i>Filtre par opsyon:</label>
                    <select class="form-control select2-single" id="selectopsyon" style="width: 150px;">
                        <option value=""></option>
                        @foreach ($listjwet as $jwet)
                            <option value="{{ $jwet->name }}">{{ $jwet->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="d-flex">
                        <a href="/ajisteprix" class="btn btn-primary  mr-2" >
                            <i class="mdi mdi-plus-circle-outline mr-1"></i> Ajoute
                        </a>
                        <button type="button" class="btn btn-danger " id="deleteSelected">
                            <i class="mdi mdi-delete-sweep mr-1"></i> Siprime Seleksyon
                        </button>
                    </div>
                </div>
            </div>
            
            <form id="bulkDeleteForm" action="{{ route('deleteMultipleLimits') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead class="bg-light-primary">
                            <tr>
                                <th width="50px">
                                    <input type="checkbox" id="selectAll" class="checkbox-lg">
                                </th>
                                <th><i class="mdi mdi-calendar-clock mr-1"></i>Tiraj</th>
                                <th><i class="mdi mdi-dice-multiple mr-1"></i>Boul</th>
                                <th><i class="mdi mdi-cash-lock mr-1"></i>Deja jwe | Limit</th>
                                <th width="120px"><i class="mdi mdi-cog-outline mr-1"></i>Aksyon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $limit)
                                <tr class="hover-primary">
                                    <td class="align-middle">
                                        <input type="checkbox" name="selected_ids[]" 
                                            value="{{ $limit['id'] }}" 
                                            class="row-checkbox checkbox-lg">
                                    </td>
                                    
                                    <td class="align-middle">
                                        @if ($limit['created_at']->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d'))
                                            <span class="text-success">
                                                <i class="mdi mdi-check-decagram mr-1"></i>
                                                {{ $limit['type'] ?? '' }}
                                            </span>
                                        @else
                                            <span class="text-danger">
                                                <i class="mdi mdi-alert-decagram mr-1"></i>
                                                {{ $limit['type'] ?? '' }}
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="align-middle">
                                        <span class="badge bg-primary-lighten text-primary">
                                            {{ $limit['opsyon'] }}
                                        </span>
                                        <span class="mx-1">:</span>
                                        <span class="badge bg-primary text-white">
                                            {{ $limit['boul'] }}
                                        </span>
                                    </td>
                                    
                                    <td class="align-middle">
                                        <span class="text-success font-weight-bold">
                                            <i class="mdi mdi-arrow-up-thin mr-1"></i>
                                            {{ $limit['montant_play'] ?? 0 }}
                                        </span>
                                        <span class="mx-2">|</span>
                                        <span class="text-danger font-weight-bold">
                                            <i class="mdi mdi-arrow-down-thin mr-1"></i>
                                            {{ $limit['montant_limit'] ?? 0 }}
                                        </span>
                                    </td>
                                    
                                    <td class="align-middle text-center">
                                        <form action="{{ route('modifierLimitePrix') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $limit['id'] ?? '' }}" />
                                            <button type="submit" class="btn btn-sm btn-danger btn-rounded">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Select/Deselect all VISIBLE checkboxes
    document.getElementById('selectAll').addEventListener('change', function () {
        var visibleCheckboxes = document.querySelectorAll('tbody tr:not([style*="display: none"]) .row-checkbox');
        visibleCheckboxes.forEach(function (checkbox) {
            checkbox.checked = this.checked;
        }, this);
    });

    // Update "Select All" checkbox when individual checkboxes are clicked
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('row-checkbox')) {
            var allVisibleChecked = true;
            var visibleCheckboxes = document.querySelectorAll('tbody tr:not([style*="display: none"]) .row-checkbox');

            visibleCheckboxes.forEach(function (checkbox) {
                if (!checkbox.checked) {
                    allVisibleChecked = false;
                }
            });

            document.getElementById('selectAll').checked = allVisibleChecked;
        }
    });

    // Bulk delete button - will only submit visible checked boxes
    document.getElementById('deleteSelected').addEventListener('click', function () {
        var selectedCount = document.querySelectorAll('tbody tr:not([style*="display: none"]) .row-checkbox:checked').length;

        if (selectedCount === 0) {
            alert('Tanpri chwazi omwen yon limit pou siprime.');
            return;
        }

        if (confirm(`Èske ou vle siprime ${selectedCount} limit chwazi yo?`)) {
            // Temporarily disable hidden checkboxes so they won't be submitted
            document.querySelectorAll('tbody tr[style*="display: none"] .row-checkbox').forEach(function (checkbox) {
                checkbox.disabled = true;
            });

            document.getElementById('bulkDeleteForm').submit();
        }
    });

    // Table filtering function
    function filterTable() {
        var selecttiraj = document.getElementById('selecttiraj').value;
        var selectopsyon = document.getElementById('selectopsyon').value;
        var rows = document.getElementById('dataTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        var hasVisibleRows = false;

        // Uncheck "Select All" when filtering
        document.getElementById('selectAll').checked = false;

        for (var i = 0; i < rows.length; i++) {
            var tirajCell = rows[i].getElementsByTagName('td')[1];
            var boulCell = rows[i].getElementsByTagName('td')[2];

            var tirajText = tirajCell.textContent || tirajCell.innerText;
            var boulText = boulCell.textContent || boulCell.innerText;

            if (selecttiraj && tirajText.trim() !== selecttiraj) {
                rows[i].style.display = 'none';
                continue;
            }

            if (selectopsyon && boulText.indexOf(selectopsyon) === -1) {
                rows[i].style.display = 'none';
                continue;
            }

            rows[i].style.display = '';
            hasVisibleRows = true;
        }

        // Disable "Select All" checkbox if no visible rows
        document.getElementById('selectAll').disabled = !hasVisibleRows;
    }

    document.getElementById('selecttiraj').addEventListener('change', filterTable);
    document.getElementById('selectopsyon').addEventListener('change', filterTable);

    // Initialize the table on page load
    document.addEventListener('DOMContentLoaded', function () {
        filterTable();
    });
</script>

@stop