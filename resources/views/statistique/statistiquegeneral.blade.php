@extends('admin-layout')
@section('content')

    <div class="card">
        <div class="p-0 card-body">

            <div class="tab-content">
                <div role="tabpanel" id="react-aria-292-tabpane-design" aria-labelledby="react-aria-292-tab-design"
                    class="fade pb-4 p-4 tab-pane active show">
                    <label>Recherche ex: Florida Matin,maryaj</label>
                    <input class="form-control" type="text" name="text" placeholder="recherche" id="myInput" required
                        style="margin-bottom: 10px;">

                    <div class="table-responsive">
                        <table class="text table" id="rapport_table">
                            <thead style="background: #10439F;color:antiquewhite;">
                                <tr>
                                    <th>Tirage</th>
                                    <th>Jeux</th>
                                    <th scope="col">Boul</th>
                                    <th scope="col">Nombre Fois</th>
                                    <th scope="col">Montant</th>
                                    <th scope="col">Action</th>



                                </tr>
                            </thead>
                            <tbody style="" id="myTable">
                                @if(isset($data['result']))
    @foreach($data['result'] as $boul => $tirages)
        @foreach($tirages as $tirage => $details)
            <tr>
                <td>{{ $details['tirage'] }}</td>
                <td>Bolet</td>
                <td>{{ $boul }}</td>
                <td>{{ $details['nombre'] }} fois</td>
                <td>{{ $details['montant'] }}</td>
                <td>
                    @php
                        $tirageId = null;
                        foreach ($datarecord as $record) {
                            if ($record->name === $details['tirage']) {
                                $tirageId = $record->id;
                                break; // Sortir de la boucle une fois que l'enregistrement est trouvé
                            }
                        }

                        $type = null;
                        foreach ($datatype as $types) {
                            if ($types->name === 'Bolet') {
                                $type = $types->id;
                                break; // Sortir de la boucle une fois que l'enregistrement est trouvé
                            }
                        }

                        $block = null;
                        foreach ($datablock as $recor) {
                            $boulAsString = strval($boul);
                            if ($recor->boul === $boulAsString && $recor->opsyon == 'Bolet' && $recor->type === $details['tirage']) {
                                $block = $recor->is_general;
                                break;
                            }
                        }
                    @endphp

                    <form action="{{ route('saveprixlimit') }}" method="POST"> @csrf
                        <input type="hidden" name="isgeneral" value="45" />
                        <input type="hidden" name="type" value="{{ $type }}" />
                        <input type="hidden" name="tirage" value="{{ $tirageId }}" />
                        <input type="hidden" name="chiffre" value="{{ $boul }}" />
                        <input type="hidden" name="montant" value="{{ $details['montant'] }}" />
                        <button type="submit">
                            @if ($block == 1)
                                <i class="mdi mdi-block-helper mdi-24px" style="color: green;"></i>
                            @elseif ($block == null)
                                <i class="mdi mdi-block-helper mdi-24px" style="color: red;"></i>
                            @endif
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    @endforeach
@else
    <tr>
        <td colspan="4">Aucune donnée disponible pour Bolet</td>
    </tr>
@endif

@if(isset($data['resulmaryaj']))
@foreach($data['resulmaryaj'] as $boul => $tirages)
    @foreach($tirages as $tirage => $details)
        <tr>
            <td>{{ $details['tirage'] }}</td>
            <td>Maryaj</td>
            <td>{{ $boul }}</td>
            <td>{{ $details['nombre'] }} fois</td>
            <td>{{ $details['montant'] }}</td>
            <td>
                @php
                    $tirageId = null;
                    foreach ($datarecord as $record) {
                        if ($record->name === $details['tirage']) {
                            $tirageId = $record->id;
                            break; // Sortir de la boucle une fois que l'enregistrement est trouvé
                        }
                    }

                    $type = null;
                    foreach ($datatype as $types) {
                        if ($types->name === 'Maryaj') {
                            $type = $types->id;
                            break; // Sortir de la boucle une fois que l'enregistrement est trouvé
                        }
                    }

                    $block = null;
                    foreach ($datablock as $record2) {
                        $boulAsString = strval($boul);
                        if ($record2->boul === $boulAsString &&
                            $record2->opsyon === 'Maryaj' &&
                            $record2->type === $details['tirage']
                        ) {
                            $block = 1;
                            break;
                        }
                    }
                @endphp

                <form action="{{ route('saveprixlimit') }}" method="POST"> @csrf
                    <input type="hidden" name="isgeneral" value="45" />
                    <input type="hidden" name="type" value="{{ $type }}" />
                    <input type="hidden" name="tirage" value="{{ $tirageId }}" />
                    <input type="hidden" name="chiffre" value="{{ $boul }}" />
                    <input type="hidden" name="montant" value="{{ $details['montant'] }}" />
                    <button type="submit">
                        @if ($block === 1)
                            <i class="mdi mdi-block-helper mdi-24px" style="color: green;"></i>
                        @else
                            <i class="mdi mdi-block-helper mdi-24px" style="color: red;"></i>
                        @endif
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
@endforeach
@else
<tr>
    <td colspan="4">Aucune donnée disponible pour Maryaj</td>
</tr>
@endif

@if(isset($data['resultloto3']))
@forelse($data['resultloto3'] as $boul => $tirages)
    @foreach($tirages as $tirage => $details)

    
        <tr>
            <td>{{ $details['tirage'] }}</td>
            <td>Loto3</td>
            <td>{{ $boul }}</td>
            <td>{{ $details['nombre'] }} fois</td>
            <td>{{ $details['montant'] }}</td>
            <td>
                @php
                    $tirageId = null;
                    foreach ($datarecord as $record) {
                        if ($record->name === $details['tirage']) {
                            $tirageId = $record->id;
                            break; // Sortir de la boucle une fois que l'enregistrement est trouvé
                        }
                    }

                    $type = null;
                    foreach ($datatype as $types) {
                        if ($types->name === 'Loto3') {
                            $type = $types->id;
                            break; // Sortir de la boucle une fois que l'enregistrement est trouvé
                        }
                    }

                    $block = null;
                    foreach ($datablock as $record2) {
                        $vari=$boul;
                        $boulAsString = strval($vari);
                        if ($record2->boul === $boulAsString &&
                            $record2->opsyon === 'Loto3' &&
                            $record2->type === $details['tirage']
                        ) {
                            $block = 1;
                            break;
                        }
                    }
                @endphp

                <form action="{{ route('saveprixlimit') }}" method="POST"> @csrf
                    <input type="hidden" name="isgeneral" value="45" />
                    <input type="hidden" name="type" value="{{ $type }}" />
                    <input type="hidden" name="tirage" value="{{ $tirageId }}" />
                    <input type="hidden" name="chiffre" value="{{ $vari }}" />
                    <input type="hidden" name="montant" value="{{ $details['montant'] }}" />
                    <button type="submit">
                        @if ($block === 1)
                            <i class="mdi mdi-block-helper mdi-24px" style="color: green;"></i>
                        @else
                            <i class="mdi mdi-block-helper mdi-24px" style="color: red;"></i>
                        @endif
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
@empty
    <tr>
        <td colspan="4">Aucune donnée disponible pour Loto3</td>
    </tr>
@endforelse
@else
<tr>
    <td colspan="4">Aucune donnée disponible pour Loto3</td>
</tr>
@endif

@if(isset($data['resultloto4']))
@forelse($data['resultloto4'] as $boul => $tirages)
    @foreach($tirages as $tirage => $details)
        <tr>
            <td>{{ $details['tirage'] }}</td>
            <td>Loto4</td>
            <td>{{ $boul }}</td>
            <td>{{ $details['nombre'] }} fois</td>
            <td>{{ $details['montant'] }}</td>
            <td>
                @php
                    $tirageId = null;
                    foreach ($datarecord as $record) {
                        if ($record->name === $details['tirage']) {
                            $tirageId = $record->id;
                            break; // Sortir de la boucle une fois que l'enregistrement est trouvé
                        }
                    }

                    $type = null;
                    foreach ($datatype as $types) {
                        if ($types->name === 'Loto4') {
                            $type = $types->id;
                            break; // Sortir de la boucle une fois que l'enregistrement est trouvé
                        }
                    }

                    $block = null;
                    foreach ($datablock as $record2) {
                        $boulAsString = strval($boul);
                        if ($record2->boul === $boulAsString &&
                            $record2->opsyon === 'Loto4' &&
                            $record2->type === $details['tirage']
                        ) {
                            $block = 1;
                            break;
                        }
                    }
                @endphp

                <form action="{{ route('saveprixlimit') }}" method="POST"> @csrf
                    <input type="hidden" name="isgeneral" value="45" />
                    <input type="hidden" name="type" value="{{ $type }}" />
                    <input type="hidden" name="tirage" value="{{ $tirageId }}" />
                    <input type="hidden" name="chiffre" value="{{ $boul }}" />
                    <input type="hidden" name="montant" value="{{ $details['montant'] }}" />
                    <button type="submit">
                        @if ($block === 1)
                            <i class="mdi mdi-block-helper mdi-24px" style="color: green;"></i>
                        @else
                            <i class="mdi mdi-block-helper mdi-24px" style="color: red;"></i>
                        @endif
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
@empty
    <tr>
        <td colspan="4">Aucune donnée disponible pour Loto4</td>
    </tr>
@endforelse
@else
<tr>
    <td colspan="4">Aucune donnée disponible pour Loto4</td>
</tr>
@endif

@if(isset($data['resultloto5']))
    @foreach($data['resultloto5'] as $boul => $tirages)
       @foreach($tirages as $tirage => $details)
     <tr>
        <td>{{ $details['tirage'] }}</td>
        <td>Loto5</td>
        <td>{{ $boul }}</td>
        <td>{{ $details['nombre'] }} fois</td>
        <td>{{ $details['montant'] }}</td>
        <td>
            @php
                $tirageId = null;
                foreach ($datarecord as $record) {
                    if ($record->name === $details['tirage']) {
                        $tirageId = $record->id;
                        break; // Sortir de la boucle une fois que l'enregistrement est trouvé
                    }
                }

                $type = null;
                foreach ($datatype as $types) {
                    if ($types->name === 'L5') {
                        $type = $types->id;
                        break; // Sortir de la boucle une fois que l'enregistrement est trouvé
                    }
                }

                $block = null;
                foreach ($datablock as $record2) {
                    $boulAsString = strval($boul);
                    if ($record2->boul === $boulAsString &&
                        $record2->opsyon === 'L5' &&
                        $record2->type === $details['tirage']
                    ) {
                        $block = 1;
                        break;
                    }
                }
            @endphp

            <form action="{{ route('saveprixlimit') }}" method="POST"> @csrf
                <input type="hidden" name="isgeneral" value="45" />
                <input type="hidden" name="type" value="{{ $type }}" />
                <input type="hidden" name="tirage" value="{{ $tirageId }}" />
                <input type="hidden" name="chiffre" value="{{ $boul }}" />
                <input type="hidden" name="montant" value="{{ $details['montant'] }}" />
                <button type="submit">
                    @if ($block === 1)
                        <i class="mdi mdi-block-helper mdi-24px" style="color: green;"></i>
                    @else
                        <i class="mdi mdi-block-helper mdi-24px" style="color: red;"></i>
                    @endif
                </button>
            </form>
        </td>
        </tr>
       
    @endforeach
@endforeach
@else
<tr>
    <td colspan="6">Aucune donnée disponible pour Loto5</td>
</tr>
@endif

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#myInput").on("keyup", function() {
                var searchValue = $(this).val().toLowerCase();
                var terms = searchValue.split(',').map(function(term) {
                    return term.trim(); // Enlever les espaces autour de chaque terme
                });

                // Fonction pour filtrer les lignes
                $("#myTable tr").each(function() {
                    var row = $(this);
                    var rowText = row.text().toLowerCase();

                    // Vérifier si chaque terme est présent dans la ligne
                    var termFound = terms.every(function(term) {
                        return rowText.indexOf(term) > -1;
                    });

                    // Afficher ou masquer la ligne en fonction de la présence des termes
                    row.toggle(termFound);
                });
            });
        });





        document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('myTable');

    // Fonction pour comparer les valeurs des colonnes "montant"
    function compare(a, b) {
        const montantA = parseFloat(a.cells[4].textContent); // Cinquième colonne
        const montantB = parseFloat(b.cells[4].textContent); // Cinquième colonne

        if (montantA < montantB) {
            return 1;
        } else if (montantA > montantB) {
            return -1;
        } else {
            return 0;
        }
    }

    // Fonction pour trier les lignes du tableau en fonction de la colonne "montant"
    function sortTable() {
        const rows = Array.from(tbody.querySelectorAll('tr'));
        rows.sort(compare);

        // Supprime toutes les lignes du tableau
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }

        // Ajoute les lignes triées au tableau
        rows.forEach(row => {
            tbody.appendChild(row);
        });
    }

    // Tri initial lors du chargement de la page
    sortTable();
});


    </script>







@stop
