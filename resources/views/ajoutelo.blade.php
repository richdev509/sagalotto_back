@extends('admin-layout')

@section('content')
    <style>
        /* Modern color palette */
        :root {
            --primary-color: #6c5ce7;
            --secondary-color: #a29bfe;
            --success-color: #00b894;
            --danger-color: #d63031;
            --background-color: #f8f9fa;
            --text-color: #2d3436;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(108, 92, 231, 0.2);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-color);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            transition: opacity 0.3s;
        }

        .btn-gradient-primary:hover {
            opacity: 0.9;
        }

        .form-check-label {
            font-weight: 500;
            color: var(--text-color);
        }

        .form-check-input {
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            .form-control {
                font-size: 16px;
            }
        }
    </style>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Espas pou antre lo ki tire</h4>
                <p class="card-description">Mete lo ki tire yo: yon chif + pweye lo Ex:01 +dezyem lo ex:02 et twazyem lo ex:03</p>
                <form class="forms-sample" @if (isset($record)) action="{{ route('modifierlo') }}" @else action="{{ route('savelot') }}" @endif method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputUsername1">Chazi dat la</label>
                        <input type="date" name="date" @if (isset($record)) value="{{ $record->created_ }}" style="pointer-events: none;" @endif class="form-control" placeholder="23/12/2023" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputUsername1">Chwazi tiraj</label>
                        <select name="tirage" class="form-control" id="select" @if (isset($record)) style="pointer-events: none;" @endif required>
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
                        <label for="exampleInputUsername1">Yon chif</label>
                        <input type="number" @if (isset($record)) value="{{ $record->unchiffre }}" @endif name="unchiffre" maxlength="1" minlength="1" class="form-control" id="1" placeholder="1">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Premye lo</label>
                        <input type="number" @if (isset($record)) value="{{ $record->premierchiffre }}" @endif name="premierchiffre" maxlength="2" minlength="1" class="form-control" id="premier" placeholder="05">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Dezyem lo</label>
                        <input type="number" @if (isset($record)) value="{{ $record->secondchiffre }}" @endif name="secondchiffre" maxlength="2" minlength="2" class="form-control" id="deuxieme" placeholder="06">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputConfirmPassword1">Twazyem lo</label>
                        <input type="number" @if (isset($record)) value="{{ $record->troisiemechiffre }}" @endif name="troisiemechiffre" maxlength="2" minlength="2" class="form-control" id="troisieme" placeholder="07">
                    </div>
                    <div class="form-check form-check-flat form-check-primary">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input"> Lot yo bon <i class="input-helper"></i>
                        </label>
                    </div>
                    @if (isset($record))
                        <button type="submit" class="btn btn-gradient-primary">Modifye</button>
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
@endsection