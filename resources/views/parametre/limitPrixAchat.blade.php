@extends('admin-layout')
@section('content')

    <div class="page-header">
        <h3 class="page-title">Limit Pri acha :</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>

            </ol>
        </nav>
    </div>
    <div class="col-lg-12 stretch-card"
        style="margin: 2px;padding: 2px;border-style:ridge; border-width:1px; border-color:rgb(209, 163, 252);">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>

                            <tr>

                                <th> Non </th>
                                <th> Pri </th>
                                <th> Aktive </th>
                                <th> Modifye </th>
                            </tr>
                        </thead>
                        <tbody>


                            <form method="post" action="{{ route('limitprixstore') }}">
                                @csrf
                                <tr class="table-info">

                                    <input type="hidden" name="id" value="bolet" />

                                    <td>Bolet</td>
                                    <td>
                                        @if ($limitprix)
                                            <input style="height:10px;padding-left: 1px;padding-right:1px;min-width:60px"
                                                type="number" class="form-control"
                                                value="{{ $limitprix->bolet != null ? $limitprix->bolet : '' }}"
                                                name="prix" />
                                        @else
                                            <input style="height:10px;" type="number" class="form-control" value=""
                                                name="prix" />
                                        @endif

                                    </td>

                                    <td>
                                        @if ($limitprix->boletetat == 1)
                                            <label> <input type="checkbox" class="form-check-inpu"
                                                    @if ($limitprix->boletetat == 1) @checked(true) @endif
                                                    name="active" id="membershipRadios2"> Aktif</label>
                                        @else
                                            <label> <input type="checkbox" class="form-check-inpu" name="active"
                                                    id="membershipRadios2"> Aktif </label>
                                        @endif
                                    </td>
                                    <td>
                                        <button style="" type="submit"
                                            class="btn btn-gradient-primary me-2">Modifye</button>
                                    </td>
                                </tr>
                            </form>

                            <form method="post" action="{{ route('limitprixstore') }}">
                                @csrf
                                <tr class="table-info">

                                    <input type="hidden" name="id" value="maryaj" />

                                    <td>Maryaj</td>
                                    <td>
                                        @if ($limitprix)
                                            <input style="height:10px;padding-left: 1px;padding-right:1px;min-width:60px"
                                                type="number" class="form-control"
                                                value="{{ $limitprix->maryaj != null ? $limitprix->maryaj : '' }}"
                                                name="prix" />
                                        @else
                                            <input style="height:10px;" type="number" class="form-control" value=""
                                                name="prix" />
                                        @endif

                                    </td>

                                    <td>
                                        @if ($limitprix->maryajetat == 1)
                                            <label> <input type="checkbox" class="form-check-inpu"
                                                    @if ($limitprix->maryajetat == 1) @checked(true) @endif
                                                    name="active" id="membershipRadios2"> Aktif </label>
                                        @else
                                            <label> <input type="checkbox" class="form-check-inpu" name="active"
                                                    id="membershipRadios2"> Aktif </label>
                                        @endif
                                    </td>
                                    <td>
                                        <button style="" type="submit"
                                            class="btn btn-gradient-primary me-2">Modifye</button>
                                    </td>
                                </tr>
                            </form>

                            <form method="post" action="{{ route('limitprixstore') }}">
                                @csrf
                                <tr class="table-info">

                                    <input type="hidden" name="id" value="loto3" />

                                    <td>Loto3</td>
                                    <td>
                                        @if ($limitprix)
                                            <input style="height:10px;padding-left: 1px;padding-right:1px;min-width:60px"
                                                type="number" class="form-control"
                                                value="{{ $limitprix->loto3 != null ? $limitprix->loto3 : '' }}"
                                                name="prix" />
                                        @else
                                            <input style="height:10px;" type="number" class="form-control" value=""
                                                name="prix" />
                                        @endif

                                    </td>

                                    <td>
                                        @if ($limitprix->loto3etat == 1)
                                            <label> <input type="checkbox" class="form-check-inpu"
                                                    @if ($limitprix->loto3etat == 1) @checked(true) @endif
                                                    name="active" id="membershipRadios2"> Aktif </label>
                                        @else
                                            <label> <input type="checkbox" class="form-check-inpu" name="active"
                                                    id="membershipRadios2"> Aktif </label>
                                        @endif
                                    </td>
                                    <td>
                                        <button style="" type="submit"
                                            class="btn btn-gradient-primary me-2">Modifye</button>
                                    </td>
                                </tr>
                            </form>


                            <form method="post" action="{{ route('limitprixstore') }}">
                                @csrf
                                <tr class="table-info">

                                    <input type="hidden" name="id" value="loto4" />

                                    <td>Loto4</td>
                                    <td>
                                        @if ($limitprix)
                                            <input style="height:10px;padding-left: 1px;padding-right:1px;min-width:60px"
                                                type="number" class="form-control"
                                                value="{{ $limitprix->loto4 != null ? $limitprix->loto4 : '' }}"
                                                name="prix" />
                                        @else
                                            <input style="height:10px;" type="number" class="form-control"
                                                value="" name="prix" />
                                        @endif

                                    </td>

                                    <td>
                                        @if ($limitprix->loto4etat == 1)
                                            <label> <input type="checkbox" class="form-check-inpu"
                                                    @if ($limitprix->loto4etat == 1) @checked(true) @endif
                                                    name="active" id="membershipRadios2"> Aktif </label>
                                        @else
                                            <label> <input type="checkbox" class="form-check-inpu" name="active"
                                                    id="membershipRadios2"> Aktif </label>
                                        @endif
                                    </td>
                                    <td>
                                        <button style="" type="submit"
                                            class="btn btn-gradient-primary me-2">Modifye</button>
                                    </td>
                                </tr>
                            </form>


                            <form method="post" action="{{ route('limitprixstore') }}">
                                @csrf

                                <tr class="table-info">

                                    <input type="hidden" name="id" value="loto5" />

                                    <td>Loto5</td>
                                    <td>
                                        @if ($limitprix)
                                            <input style="height: 20px;padding-left: 1px;padding-right:1px;min-width:60px"
                                                type="number" class="form-control"
                                                value="{{ $limitprix->loto5 != null ? $limitprix->loto5 : '' }}"
                                                name="prix" />
                                        @else
                                            <input style="height:10px;" type="number" class="form-control"
                                                value="" name="prix" />
                                        @endif

                                    </td>

                                    <td>
                                        @if ($limitprix)
                                            <label> <input type="checkbox" class="form-check-inpu"
                                                    @if ($limitprix->loto5etat == 1) @checked(true) @endif
                                                    name="active" id="membershipRadios2"> Aktif </label>
                                        @else
                                            <label> <input type="checkbox" class="form-check-inpu" name="active"
                                                    id="membershipRadios2"> Aktif </label>
                                        @endif

                                    </td>
                                    <td>
                                        <button style="" type="submit"
                                            class="btn btn-gradient-primary me-2">Modifye</button>
                                    </td>
                                </tr>
                            </form>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 grid-margin stretch-card"
        style="margin: 2px;padding: 2px;border-style:ridge; border-width:1px; border-color:rgb(209, 163, 252);">

        <div class="card">

            <div class="card-body">

                <h4 class="card-title">Limit pri pa boul</h4>

                </p>
                <div class="form-group">

                    <div style="display: flex;gap:8px;flex-wrap:wrap;">
                        <label for="dateFilter">Filtre par Tiraj:</label>

                        <select class="form-control" name="selecttirage" id="selecttiraj"
                            style="width: 192px;
              border-color: blue;
              outline-color: rgb(23, 9, 82);
              color: #41374b;">
                            <option></option>
                            @foreach ($listetirage as $lists)
                                <option value="{{ $lists->name }}">
                                    {{ $lists->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="dateFilter">Filtre par opsyon:</label>

                        <select class="form-control" name="selectoption" id="selectopsyon"
                            style="width: 192px;
              border-color: blue;
              outline-color: rgb(42, 13, 124);
              color: #41374b;">
                            <option></option>
                            @foreach ($listjwet as $jwet)
                                <option value="{{ $jwet->name }}">
                                    {{ $jwet->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-top: 20px;"></div>
                    <a href="/ajisteprix">
                        <button class="btn primary" style="background:rgb(0 94 254)">Ajoute</button>
                    </a>
                </div>
                <div class="table-responsive table-striped" >
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <tr>

                                <th>Tiraj</th>
                                <th>Boul</th>
                                <th>Deja jwe | Limit</th>
                                <th>Aksyon </th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $limit)
                                <tr>
                                    @if ($limit['created_at']->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d'))
                                        <td style="color: green;">{{ $limit['type'] != null ? $limit['type'] : '' }}</td>
                                    @else
                                        <td style="color: red;">{{ $limit['type'] != null ? $limit['type'] : '' }}</td>
                                    @endif
                                    <td> {{ $limit['opsyon'] }}:{{ $limit['boul'] }}
                                    </td>
                                    <td><span
                                            style="color: green;">{{ $limit['montant_play'] != null ? $limit['montant_play'] : 0 }}</span>
                                        |<span style="color: red;">
                                            {{ $limit['montant_limit'] != null ? $limit['montant_limit'] : 0 }}</span>
                                    </td>

                                    </td>

                                    <td class="text-end" style="justify-content: center;">
                                        
                                            <form action="" style="display:none;">
                                                @csrf
                                                <input type="hidden" name="id"
                                                    value="{{ $limit['id'] != null ? $limit['id'] : '' }}" />
                                                <button type="submit" style="color: rgb(0, 132, 255);"><i
                                                        class="mdi mdi-table-edit"></i>Siprime</button>
                                            </form>
                                            <form action="{{ route('modifierLimitePrix') }}" method="POST"
                                                style="display: flex;gap:3px;">
                                                @csrf
                                                <input type="hidden" name="id"
                                                    value="{{ $limit['id'] != null ? $limit['id'] : '' }}" />
                                                <button type="submit" style="color: red; padding: 3px; border: 1px groove red;"><i
                                                        class="mdi mdi-delete"></i>Siprime</button>
                                            </form>
                                        
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>



            </div>
        </div>
    </div>
    <script>
        // Fonction pour filtrer les données du tableau
        function filterTable() {
            var selecttiraj = document.getElementById('selecttiraj').value;
            console.log(selecttiraj);
            var selectopsyon = document.getElementById('selectopsyon').value;
            var rows = document.getElementById('dataTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var tirajCell = rows[i].getElementsByTagName('td')[0];
                var boulCell = rows[i].getElementsByTagName('td')[1];

                var tirajText = tirajCell.textContent || tirajCell.innerText;
                var boulText = boulCell.textContent || boulCell.innerText;

                // Si le filtre pour tiraj est sélectionné et ne correspond pas à la valeur de la cellule de tiraj, masquer la ligne
                if (selecttiraj && tirajText.trim() !== selecttiraj) {
                    rows[i].style.display = 'none';
                    continue; // Passer à la prochaine ligne
                }

                // Si le filtre pour opsyon est sélectionné et ne correspond pas à la valeur de la cellule de boul, masquer la ligne
                if (selectopsyon && boulText.indexOf(selectopsyon) === -1) {
                    rows[i].style.display = 'none';
                    continue; // Passer à la prochaine ligne
                }

                // Si la ligne correspond aux filtres, afficher la ligne
                rows[i].style.display = '';
            }
        }

        // Écouteurs d'événements pour les changements dans les selects
        document.getElementById('selecttiraj').addEventListener('change', filterTable);
        document.getElementById('selectopsyon').addEventListener('change', filterTable);
    </script>


@stop
