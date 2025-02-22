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

            .selectpicker {
                width: 100%;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
                font-size: 16px;
                color: #1469e9;
                background-color: #f9f9f9;
                transition: border-color 0.3s ease;
            }

            .selectpicker:focus {
                border-color: #1469e9;
                outline: none;
                box-shadow: 0 0 5px rgba(20, 105, 233, 0.5);
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
        </style>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Espas pou antre limit prix boul</h4>

                <form class="forms-sample"
                    @if (isset($record)) action="{{ route('updateprixboul') }}"  @else action="{{ route('saveprixlimit') }}" @endif
                    method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="select-tirage">Chwazi tiraj</label>
                        <select name="tirage[]" class="form-control selectpicker" data-live-search="true" multiple id="select-tirage" required>
                            @if (isset($record))
                                <option value="{{ $record->tirage_record->id }}">{{ $record->tirage_record->name }}</option>
                            @else
                                <option disabled>Lis tiraj</option>
                                @foreach ($list as $liste)
                                    <option value="{{ $liste->id }}">{{ $liste->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="select-opsyon">Chwazi Opsyon</label>
                        <select name="type" class="form-control selectpicker" id="select-opsyon" required>
                            @foreach ($listjwet as $lis)
                                <option value="{{ $lis->id }}">{{ $lis->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="input-boul">Antre Boul la</label>
                        <input type="number" @if (isset($record)) value="{{ $record->unchiffre }}" @endif
                            name="chiffre" class="form-control input" id="input-boul"
                            placeholder="loto Ex:34 , Maryaj Ex:3453">
                    </div>
                    <div class="form-group">
                        <label for="input-montant">Antre montant limit</label>
                        <input type="number" @if (isset($record)) value="{{ $record->unchiffre }}" @endif
                            name="montant" class="form-control input" id="input-montant"
                            placeholder="300">
                    </div>
                    <div class="form-group">
                        @if (isset($record))
                            <button type="submit" class="btn btn-gradient-primary me-2">Modifye</button>
                        @else
                            <button type="submit" class="btn btn-gradient-primary me-2">Ajoute</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop