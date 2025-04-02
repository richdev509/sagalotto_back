@extends('admin-layout')
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-primary">Pri gagnan Lo</h4>
            <p class="card-description text-muted">Ajiste pri lo yo</p>
            
            <form class="forms-sample" action="{{ route('updateprilo') }}" method="POST">
                @csrf
                <input type="hidden" name="gabel_enabled" id="gabel_enabled" value="1">
                
                <div class="form-group mb-4">
                    <label class="form-label">Branch</label>
                    <select name="branch" id="branchh" class="form-control form-control-lg border-primary">
                        <option value="" disabled selected>Chwazi branch</option>
                        @foreach ($branch as $row)
                            <option value="{{ $row->id}}">{{ $row->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Bolet Prices Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#boletPrices" style="cursor: pointer;">
                        <h5 class="mb-0">Pri Bolet</h5>
                        <i class="mdi mdi-chevron-down"></i>
                    </div>
                    <div class="collapse show" id="boletPrices">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Pri 1e lo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">X fwa</span>
                                        </div>
                                        <span class="input-group-text bg-light" id='prix_label1'>null</span>
                                        <select name="prix_first" class="form-control text-danger">
                                            <option id="prix1">null</option>
                                            <option value="60">60</option>
                                            <option value="50">50</option>
                                            <option value="55">55</option>
                                            <option value="65">65</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Pri 2e lo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">X fwa</span>
                                        </div>
                                        <span class="input-group-text bg-light" id='prix_label2'>null</span>
                                        <select name="prix_second" class="form-control text-danger">
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
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">X fwa</span>
                                        </div>
                                        <span class="input-group-text bg-light" id='prix_label3'>null</span>
                                        <select name="prix_third" class="form-control text-danger">
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
                        <i class="mdi mdi-chevron-down"></i>
                    </div>
                    <div class="collapse show" id="lotoPrices">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Pri maryaj</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">X fwa</span>
                                        </div>
                                        <span class="input-group-text bg-light" id='prix_maryaj_label'>null</span>
                                        <select name="prix_maryaj" class="form-control text-danger">
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
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">X fwa</span>
                                        </div>
                                        <span class="input-group-text bg-light" id='prix_loto3_label'>null</span>
                                        <select name="prix_loto3" class="form-control text-danger">
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
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">X fwa</span>
                                        </div>
                                        <span class="input-group-text bg-light" id='prix_loto4_label'>null</span>
                                        <select name="prix_loto4" class="form-control text-danger">
                                            <option id="prix_loto4">null</option>
                                            <option value="5000">5000</option>
                                            <option value="6000">6000</option>
                                            <option value="7500">7500</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="form-label">Pri loto5</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">X fwa</span>
                                        </div>
                                        <span class="input-group-text bg-light" id='prix_loto5_label'>null</span>
                                        <select name="prix_loto5" class="form-control text-danger">
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

                        <div class="flex-column flex-md-row align-items-md-center">
                            
                            <h5 class="mb-2 mb-md-0">Pri Gabel</h5>
                            <div class="form-check form-switch" style="left: 40px;">
                                <input class="form-check-input" type="checkbox" id="toggleGabel" value="1" name="gabel_statut">
                                <label class="form-check-label" for="toggleGabel">Active</label>
                            </div>
                        </div>
                        <i class="mdi mdi-chevron-down" data-bs-toggle="collapse" href="#gabelPrices" style="cursor: pointer;"></i>
                    </div>
                    <div class="collapse show" id="gabelPrices">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Pri gabel 1e</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">X fwa</span>
                                        </div>
                                        <span class="input-group-text bg-light" id='prix_gabel1_label'>null</span>
                                        <select name="prix_gabel1" class="form-control text-danger" id="prix_gabel1_select">
                                            <option id="prix_gabel1">null</option>
                                            <option value="1000">20</option>
                                          
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Pri gabel 2e</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">X fwa</span>
                                        </div>
                                        <span class="input-group-text bg-light" id='prix_gabel2_label'>null</span>
                                        <select name="prix_gabel2" class="form-control text-danger" id="prix_gabel2_select">
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
                    <div class="form-check" style="left: 40px;">
                        <input type="checkbox" class="form-check-input" id="tirage_auto" value='1' name='tirage_auto' 
                            @if ($service->autoTirage) checked @endif>
                        <label class="form-check-label" for="tirage_auto">
                            Pemet sistem nan ajoute lo gayan pou ou
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg">Mete a jou</button>
            </form>
        </div>
    </div>

    <style>
        .form-control {
            border: 1px solid #4a90e2;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #005efe;
            box-shadow: 0 0 0 0.2rem rgba(0, 94, 254, 0.25);
        }
        .input-group-text {
            background-color: #f8f9fa;
            color: #495057;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid #e1e1e1;
        }
        .card-header {
            border-bottom: 1px solid #e1e1e1;
            border-radius: 10px 10px 0 0 !important;
        }
        .card-title {
            font-weight: 600;
        }
        .btn-primary {
            background-color: #005efe;
            border-color: #005efe;
            padding: 10px 25px;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }
        .input-group {
            margin-bottom: 10px;
        }
        [data-bs-toggle="collapse"] .mdi-chevron-down {
            transition: transform 0.3s ease;
        }
        [data-bs-toggle="collapse"].collapsed .mdi-chevron-down {
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
    </style>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle Gabel section
            $('#toggleGabel').change(function() {
                const isEnabled = $(this).is(':checked');
                $('#gabel_enabled').val(isEnabled ? '1' : '0');
                
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
                $('#gabel_enabled').val('0');
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
                            //bolet
                            $('#prix1').text(response.data.prix);
                            $('#prix2').text(response.data.prix_second);
                            $('#prix3').text(response.data.prix_third);

                            //bolet label
                            $('#prix_label1').text(response.data.prix);
                            $('#prix_label2').text(response.data.prix_second);
                            $('#prix_label3').text(response.data.prix_third);

                            //other
                            $('#prix_maryaj').text(response.data.prix_maryaj);
                            $('#prix_loto3').text(response.data.prix_loto3);
                            $('#prix_loto4').text(response.data.prix_loto4);
                            $('#prix_loto5').text(response.data.prix_loto5);
                            $('#prix_gabel1').text(response.data.prix_gabel1);
                            $('#prix_gabel2').text(response.data.prix_gabel2);

                            $('#prix_maryaj_label').text(response.data.prix_maryaj);
                            $('#prix_loto3_label').text(response.data.prix_loto3);
                            $('#prix_loto4_label').text(response.data.prix_loto4);
                            $('#prix_loto5_label').text(response.data.prix_loto5);
                            $('#prix_gabel1_label').text(response.data.prix_gabel1);
                            $('#prix_gabel2_label').text(response.data.prix_gabel2);

                            // Update toggle state if exists in response
                            if(response.data.gabel_statut ==1) {
                                $('#toggleGabel').prop('checked');
                                $('#toggleGabel').trigger('change');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            });

            // Initialize all collapses as shown
            $('.collapse').addClass('show');
        });
    </script>
@stop