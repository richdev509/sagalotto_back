@extends('admin-layout')


@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <style>
            .input {
                font-size: 20px;
            }
        </style>
        <div class="card">

            <div class="card-body">
                <h4 class="card-title">Espas pou antre lo ki tire</h4>
                <p class="card-description">Mete lo ki tire yo: yon chif + pweye lo Ex:01 +dezyem lo ex:02 et twazyem lo
                    ex:03</p>
                <form class="forms-sample"
                    @if (isset($record)) action="{{ route('modifierlo') }}"  @else action="{{ route('savelot') }}" @endif
                    method="POST" style="font-weight: bold;">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputUsername1">Chazi dat la </label>
                        <input type="date" name="date"
                            @if (isset($record)) value="{{ $record->created_ }}"  style="pointer-events: none;" @endif
                            class="form-control input" placeholder="23/12/2023"
                            style="border-color: #1469e9;
                        
                    border-style: double;
                    border-width: 1px;">
                    </div>
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label for="exampleInputUsername1">chwazi tiraj</label>
                        <select name="tirage" class="form-control" id="select" placeholder="List tiraj"
                            style="height: 49px;
                    border-color: #e95d14;
                    
                    border-style: double;
                    border-width: 1px;font-size:18px;color:#1469e9; @if (isset($record)) pointer-events:none; @endif"
                            required>

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
                        <label for="exampleInputUsername1">Yon chif </label>
                        <input type="number" @if (isset($record)) value="{{ $record->unchiffre }}" @endif
                            name="unchiffre" maxlength="1" minlength="1" class="form-control input" id="1"
                            placeholder="1"
                            style="border-color: #1469e9;
                    
                border-style: double;
                border-width: 1px;">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">premye lo</label>
                        <input type="number" @if (isset($record)) value="{{ $record->premierchiffre }}" @endif
                            name="premierchiffre" maxlength="2" minlength="1" class="form-control input" id="premier"
                            placeholder="05"
                            style="border-color: #14e990;
                    
                border-style: double;
                border-width: 1px;">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">dezyem lo</label>
                        <input type="number" @if (isset($record)) value="{{ $record->secondchiffre }}" @endif
                            name="secondchiffre" maxlength="2" minlength="2" class="form-control input" id="deuxieme"
                            placeholder="06"
                            style="border-color: #14e942;
                    
                border-style: double;
                border-width: 1px;">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputConfirmPassword1">twazyem lo</label>
                        <input type="number"
                            @if (isset($record)) value="{{ $record->troisiemechiffre }}" @endif
                            name="troisiemechiffre" maxlength="2" minlength="2" class="form-control input" id="troisieme"
                            placeholder="07"
                            style="border-color: #bbe914;
                    
                border-style: double;
                border-width: 1px;">
                    </div>
                    <div class="form-check form-check-flat form-check-primary">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input">lot yo bon<i class="input-helper"></i></label>
                    </div>
                    @if (isset($record))
                        <button type="mise a" class="btn btn-gradient-primary me-2">Modifye</button>
                    @else
                        <button type="submit" class="btn btn-gradient-primary me-2">Ajoute</button>
                    @endif

                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction de validation pour le champ "unchiffre"
            function validerUnchiffre(champ) {
                var valeur = champ.value.trim();
                if (valeur.length > 1) {
                    champ.value = valeur.slice(0, 1); // Tronquer la valeur à un seul caractère
                    alert('La longueur maximale est de 1 chiffre pour ce champ.');
                }
            }

            // Fonction de validation pour le champ "premierchiffre"
            function validerPremierchiffre(champ) {
                var valeur = champ.value.trim();
                if (valeur.length > 2) {
                    champ.value = valeur.slice(0, 2); // Tronquer la valeur à deux caractères
                    alert('La longueur maximale est de 2 chiffres pour ce champ.');
                }
            }

            // Fonction de validation pour le champ "secondchiffre"
            function validerSecondchiffre(champ) {
                var valeur = champ.value.trim();
                if (valeur.length > 2) {
                    champ.value = valeur.slice(0, 2); // Tronquer la valeur à deux caractères
                    alert('La longueur maximale est de 2 chiffres pour ce champ.');
                }
            }

            // Fonction de validation pour le champ "troisiemechiffre"
            function validerTroisiemechiffre(champ) {
                var valeur = champ.value.trim();
                if (valeur.length > 2) {
                    champ.value = valeur.slice(0, 2); // Tronquer la valeur à deux caractères
                    alert('La longueur maximale est de 2 chiffres pour ce champ.');
                }
            }

            // Ajouter des gestionnaires d'événements pour chaque champ d'entrée
            document.getElementById('1').addEventListener('input', function() {
                validerUnchiffre(this);
            });

            document.getElementById('premier').addEventListener('input', function() {
                validerPremierchiffre(this);
            });

            document.getElementById('deuxieme').addEventListener('input', function() {
                validerSecondchiffre(this);
            });

            document.getElementById('troisieme').addEventListener('input', function() {
                validerTroisiemechiffre(this);
            });
        });
    </script>






@stop
