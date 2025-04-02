@extends('superadmin.admin-layout')
@section('content')


    <div class="card">
        <div class="p-0 card-body">

            <div class="tab-content">

                <div role="tabpanel" id="react-aria-292-tabpane-design" aria-labelledby="react-aria-292-tab-design"
                    class="fade pb-4 p-4 tab-pane active show">

                    <div class="table-responsive">

                        <table class="text table" id="rapport_table">
                            <div class="row col-12">
                                <div class="form-group col-12">
                                    <label for="searchInput">Recherche par:</label>
                                    <input class="form-control" type="text" id="searchInput" name="text"
                                        placeholder="Recherche (nom compagnie,phone, code, date expiration, bloquer (oui non))" onkeyup="filterTable()" required />
                                </div>
                               
                                
                            </div>
                            <thead style="background: #10439F;color:antiquewhite;">
                                <tr>
                                    <th>Action</th>
                                    <th scope="col">Compagnie</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Plan</th>
                                    <th scope="col">Date Abonnement</th>
                                    <th scope="col">Date Expiration</th>
                                    <th scope="col">Bloquer</th>
                                    @if (session('role') == 'admin' || session('role') == 'addeur' || session('role') == 'comptable')
                                        <th scope="col">Abonnement</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @foreach ($data as $donnee)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" >
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    @if (session('role') == 'admin' || session('role') == 'admin2' || session('role') == 'addeur')
                                                        <li>
                                                            <form action="{{ route('edit_compagnie') }}" method="POST"
                                                                class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $donnee->id }}" />
                                                                <button type="submit" class="dropdown-item">Edit</button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <form action="{{ route('login_as_company') }}" method="GET"
                                                            target="_blank" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $donnee->id }}" />
                                                            <button type="submit" class="dropdown-item">Login</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="genererfacture" method="POST"
                                                            target="_blank" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="company"
                                                                value="{{ $donnee->id }}" />
                                                                <input type="hidden" name="date"
                                                                value="{{ $donnee->dateexpiration }}" />
                                                            <button type="submit" class="dropdown-item">Generer Facture</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('listecompagnieU') }}" method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="idcompagnie"
                                                                value="{{ $donnee->id }}" />
                                                            <button type="submit" class="dropdown-item">View</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>


                                        <td>{{ $donnee->name }}</td>
                                        <td>{{ $donnee->code }}</td>
                                        <td>{{ $donnee->phone }}</td>
                                        <td>{{ $donnee->plan }}</td>
                                        <td>{{ $donnee->dateplan }}</td>

                                        <td>
                                            @php
                                                $expirationDate = \Carbon\Carbon::parse($donnee->dateexpiration);
                                                $currentDate = \Carbon\Carbon::now();
                                            @endphp


                                            @if ($expirationDate->isToday())
                                                <label class="badge"
                                                    style="background: red;">{{ $donnee->dateexpiration }}</label>
                                            @elseif ($expirationDate->lessThan($currentDate))
                                                <label class="badge"
                                                    style="background: red;">{{ $donnee->dateexpiration }}</label>
                                            @else
                                                <label class="badge"
                                                    style="background: rgb(3, 148, 34);">{{ $donnee->dateexpiration }}</label>
                                            @endif
                                        </td>
                                        <td>



                                            @if ($donnee->is_block == 1)
                                                <label class="badge" style="background: red;">Oui</label>
                                            @else
                                                <label class="badge" style="background: rgb(3, 148, 34);">Non</label>
                                            @endif
                                        </td>
                                        @if (session('role') == 'admin' || session('role') == 'comptable')
                                            <td>
                                                <form action="{{ route('add_abonnement2') }}" method="POST"> @csrf <input
                                                        type="hidden" name="id"
                                                        value="{{ $donnee->id }}" /><button><i
                                                            class="mdi mdi-calendar-check mdi-24px"></i></button></form>
                                        @endif
                                    
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
        function filterTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("rapport_table");
            const rows = table.querySelectorAll("tbody tr");

            rows.forEach((row) => {
                const cells = row.querySelectorAll("td");
                const rowText = Array.from(cells)
                    .map((cell) => cell.textContent.toLowerCase())
                    .join(" ");

                if (rowText.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        
    </script>


@stop
