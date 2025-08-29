@extends('admin-layout')
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-primary">Pri Gagnan Lo</h4>
            <p class="card-description text-muted">Ajiste pri lo yo</p>
            
            <form class="forms-sample" action="{{ route('updateprilo') }}" method="POST">
                @csrf

                <div class="form-group mb-4">
                    <label for="branchh" class="form-label">Chwazi Branch</label>
                    <select class="form-select" id="branchh" name="branch">
                        <option value="" disabled selected>Chwazi Branch wap parametre a</option>
                        @foreach ($branch as $branchh)
                            <option value="{{ $branchh->id }}">{{ $branchh->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bolet Prices Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#boletPrices" style="cursor: pointer;">
                        <h5 class="mb-0">Pri Bolet</h5>
                        <i class="mdi mdi-chevron-down toggle-icon"></i>
                    </div>
                    <div class="collapse show" id="boletPrices">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Pri 1e lo</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">X fwa</span>
                                        <span class="input-group-text bg-light" id='prix_label1'>null</span>
                                        <select name="prix_first" class="form-select text-danger">
                                            <option id="prix1">null</option>
                                            <option value="60">60</option>
                                            <option value="50">50</option>
                                            <option value="55">55</option>
                                            <option value="65">65</option>
                                            <option value="70">70</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Pri 2e lo</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">X fwa</span>
                                        <span class="input-group-text bg-light" id='prix_label2'>null</span>
                                        <select name="prix_second" class="form-select text-danger">
                                            <option id="prix2">null</option>
                                            <option value="20">20</option>
                                            <option value="25">25</option>
                                            <option value="15">15</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Pri 3e lo</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">X fwa</span>
                                        <span class="input-group-text bg-light" id='prix_label3'>null</span>
                                        <select name="prix_third" class="form-select text-danger">
                                            <option id="prix3">null</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loto Prices Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#lotoPrices" style="cursor: pointer;">
                        <h5 class="mb-0">Pri maryaj ak loto</h5>
                        <i class="mdi mdi-chevron-down toggle-icon"></i>
                    </div>
                    <div class="collapse show" id="lotoPrices">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Pri maryaj</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">X fwa</span>
                                        <span class="input-group-text bg-light" id='prix_maryaj_label'>null</span>
                                        <select name="prix_maryaj" class="form-select text-danger">
                                            <option id="prix_maryaj">null</option>
                                            <option value="1000">1000</option>
                                            <option value="1250">1250</option>
                                            <option value="1500">1500</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Pri Loto3</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">X fwa</span>
                                        <span class="input-group-text bg-light" id='prix_loto3_label'>null</span>
                                        <select name="prix_loto3" class="form-select text-danger">
                                            <option id="prix_loto3">null</option>
                                            <option value="500">500</option>
                                            <option value="650">650</option>
                                            <option value="700">700</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Pri loto4</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">X fwa</span>
                                        <span class="input-group-text bg-light" id='prix_loto4_label'>null</span>
                                        <select name="prix_loto4" class="form-select text-danger">
                                            <option id="prix_loto4">null</option>
                                            <option value="5000">5000</option>
                                            <option value="6000">6000</option>
                                            <option value="7500">7500</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Pri loto5</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">X fwa</span>
                                        <span class="input-group-text bg-light" id='prix_loto5_label'>null</span>
                                        <select name="prix_loto5" class="form-select text-danger">
                                            <option id="prix_loto5">null</option>
                                            <option value="25000">25000</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Gabel Prices Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 me-3">Pri Gabel</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="toggleGabel" value="1" name="gabel_statut">
                                <label class="form-check-label" for="toggleGabel">Active</label>
                            </div>
                        </div>
                        <i class="mdi mdi-chevron-down toggle-icon" data-bs-toggle="collapse" href="#gabelPrices" style="cursor: pointer;"></i>
                    </div>
                    <div class="collapse show" id="gabelPrices">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Pri gabel 1e</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">X fwa</span>
                                        <span class="input-group-text bg-light" id='prix_gabel1_label'>null</span>
                                        <select name="prix_gabel1" class="form-select text-danger" id="prix_gabel1_select">
                                            <option id="prix_gabel1">null</option>
                                            <option value="1000">20</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Pri gabel 2e</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">X fwa</span>
                                        <span class="input-group-text bg-light" id='prix_gabel2_label'>null</span>
                                        <select name="prix_gabel2" class="form-select text-danger" id="prix_gabel2_select">
                                            <option id="prix_gabel2">null</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Auto Tirage Section -->
                <div class="form-group mb-4">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="tirage_auto" value='1' name='tirage_auto' 
                            @if ($service->autoTirage) checked @endif>
                        <label class="form-check-label" for="tirage_auto">
                            Pemet sistem nan ajoute lo gayan pou ou
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg w-100">Mete a jou</button>
            </form>
        </div>
    </div>

    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #edf2fe;
            --text-dark: #212529;
            --text-medium: #495057;
            --text-light: #6c757d;
            --border-color: #e1e1e1;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        .card {
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--primary-light) !important;
            border-bottom: 1px solid var(--border-color);
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.5rem;
        }

        .card-title {
            font-weight: 600;
            color: var(--text-dark);
        }

        .card-description {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .form-select, .form-control {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.6rem 1rem;
            transition: var(--transition);
        }

        .form-select:focus, .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fa;
            color: var(--text-medium);
            border-color: var(--border-color);
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 8px;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background-color: #3a56d4;
            border-color: #3a56d4;
            transform: translateY(-2px);
        }

        .toggle-icon {
            transition: transform 0.3s ease;
        }

        .collapsed .toggle-icon {
            transform: rotate(-90deg);
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            margin-right: 0.5em;
        }

        .disabled-section {
            opacity: 0.6;
            pointer-events: none;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .text-muted {
            color: var(--text-light) !important;
        }

        .text-primary {
            color: var(--primary) !important;
        }
    </style>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize all collapses as shown
            $('.collapse').addClass('show');

            // Toggle icon rotation
            $('[data-bs-toggle="collapse"]').on('click', function() {
                $(this).find('.toggle-icon').toggleClass('collapsed');
            });

            // Toggle Gabel section
            $('#toggleGabel').change(function() {
                const isEnabled = $(this).is(':checked');
                
                if(isEnabled) {
                    $('#gabelPrices').removeClass('disabled-section');
                    $('#prix_gabel1_select, #prix_gabel2_select').prop('disabled', false);
                } else {
                    $('#gabelPrices').addClass('disabled-section');
                    $('#prix_gabel1_select, #prix_gabel2_select').prop('disabled', true);
                }
            });
            
            // Initialize based on default state
            if(!$('#toggleGabel').is(':checked')) {
                $('#gabelPrices').addClass('disabled-section');
                $('#prix_gabel1_select, #prix_gabel2_select').prop('disabled', true);
            }

            // Branch change handler
            $('#branchh').change(function() {
                let current_id = this.value;
                $.ajax({
                    url: 'getByBranch',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: current_id
                    },
                    success: function(response) {
                        if(response.status == 'true') {
                            // Update bolet prices
                            $('#prix1').text(response.data.prix).val(response.data.prix);
                            $('#prix2').text(response.data.prix_second).val(response.data.prix_second);
                            $('#prix3').text(response.data.prix_third).val(response.data.prix_third);

                            // Update bolet labels
                            $('#prix_label1').text(response.data.prix);
                            $('#prix_label2').text(response.data.prix_second);
                            $('#prix_label3').text(response.data.prix_third);

                            // Update other prices
                            $('#prix_maryaj').text(response.data.prix_maryaj).val(response.data.prix_maryaj);
                            $('#prix_loto3').text(response.data.prix_loto3).val(response.data.prix_loto3);
                            $('#prix_loto4').text(response.data.prix_loto4).val(response.data.prix_loto4);
                            $('#prix_loto5').text(response.data.prix_loto5).val(response.data.prix_loto5);
                            $('#prix_gabel1').text(response.data.prix_gabel1).val(response.data.prix_gabel1);
                            $('#prix_gabel2').text(response.data.prix_gabel2).val(response.data.prix_gabel2);

                            // Update other labels
                            $('#prix_maryaj_label').text(response.data.prix_maryaj);
                            $('#prix_loto3_label').text(response.data.prix_loto3);
                            $('#prix_loto4_label').text(response.data.prix_loto4);
                            $('#prix_loto5_label').text(response.data.prix_loto5);
                            $('#prix_gabel1_label').text(response.data.prix_gabel1);
                            $('#prix_gabel2_label').text(response.data.prix_gabel2);

                            // Update toggle state if exists in response
                            if(response.data.gabel_statut == 1) {
                                $('#toggleGabel').prop('checked', true).trigger('change');
                            } else {
                                $('#toggleGabel').prop('checked', false).trigger('change');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        alert('Ere souvan, tanpri eseye ankò');
                    }
                });
            });
        });
    </script>
@stop