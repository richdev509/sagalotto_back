@extends('admin-layout')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <style>
         
        .input {
            font-size: 18px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: border-color 0.3s ease;
        }
        .input:focus {
            border-color: #1469e9;
            outline: none;
            box-shadow: 0 0 5px rgba(20, 105, 233, 0.5);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        .btn-gradient-primary {
            background: linear-gradient(45deg, #1469e9, #14e9e9);
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn-gradient-primary:hover {
            background: linear-gradient(45deg, #14e9e9, #1469e9);
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 30px;
        }
        .card-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .bootstrap-select .dropdown-toggle {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
    
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Espas pou antre limit prix boul</h4>

            <form class="forms-sample" 
                  @if(isset($record)) action="{{ route('updateprixboul') }}" 
                  @else action="{{ route('saveprixlimit') }}" @endif
                  method="POST">
                @csrf
                <div class="form-group">
                    <label for="select-tirage">Chwazi tiraj</label>
                    <div class="mb-3">
                        <button type="button" id="select-all-btn" class="btn btn-sm btn-primary me-2">
                            <i class="mdi mdi-check-all"></i> Tout chwazi
                        </button>
                        <button type="button" id="deselect-all-btn" class="btn btn-sm btn-danger">
                            <i class="mdi mdi-close-box-multiple"></i> Retire tout
                        </button>
                    </div>
                    <select name="tirage[]" class="selectpicker form-control" id="select-tirage" multiple data-live-search="true" required>
                        @if(isset($record))
                            <option value="{{ $record->tirage_record->id }}" selected>
                                {{ $record->tirage_record->name }}
                            </option>
                        @else
                            @foreach($list as $liste)
                                <option value="{{ $liste->id }}">{{ $liste->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="select-opsyon">Chwazi Opsyon</label>
                    <select name="type" class="selectpicker form-control" id="select-opsyon" required>
                        <option value="paire">Boulpè yo</option>
                        <option value="grap">Grap yo</option>
                        @foreach($listjwet as $lis)
                            <option value="{{ $lis->id }}">{{ $lis->name }}</option>
                        @endforeach
                      
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="input-boul">Antre Boul la</label>
                    <input type="number" 
                           @if(isset($record)) value="{{ $record->unchiffre }}" @endif
                           name="chiffre" class="form-control input" id="input-boul"
                           placeholder="loto Ex:34 , Maryaj Ex:3453">
                </div>
                
                <div class="form-group">
                    <label for="input-montant">Antre montant limit</label>
                    <input type="number" 
                           @if(isset($record)) value="{{ $record->unchiffre }}" @endif
                           name="montant" class="form-control input" id="input-montant"
                           placeholder="300" required>
                </div>
                
                <div class="form-group">
                    @if(isset($record))
                        <button type="submit" class="btn btn-gradient-primary me-2">Modifye</button>
                    @else
                        <button type="submit" class="btn btn-gradient-primary me-2">Ajoute</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Required dependencies -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize select pickers
        $('.selectpicker').selectpicker();
        
        // Select all functionality
        $('#select-all-btn').click(function() {
            $('#select-tirage option').prop('selected', true);
            $('#select-tirage').selectpicker('refresh');
        });
        
        // Deselect all functionality
        $('#deselect-all-btn').click(function() {
            $('#select-tirage option').prop('selected', false);
            $('#select-tirage').selectpicker('refresh');
        });
    
        // Hide or show "Antre Boul la" input based on selection
        $('#select-opsyon').change(function() {
            let selectedValue = $(this).val();
            if (selectedValue === 'paire' || selectedValue === 'grap') {
                $('#input-boul').closest('.form-group').hide();
            } else {
                $('#input-boul').closest('.form-group').show();
            }
        }).trigger('change'); // Ensure the function runs on page load if a value is already set
    
        // Debugging
        $('#select-tirage').on('changed.bs.select', function(e) {
            console.log('Selected values:', $(this).val());
        });
    });
    </script>
    
@endsection
