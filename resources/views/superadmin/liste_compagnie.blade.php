@extends('superadmin.admin-layout')
@section('content')


    <div class="card">
        <div class="p-0 card-body">
            <div class="row">

                <form id="rapport_form">
                    @csrf
                    <div class="form-group" style="display:inline-flex;border: 1px solid #ab61e7;padding: 0px;">

                        <div>
                            <input class="form-control" type="text" name="text" placeholder="recherche(nom ou phone)"
                                required>
                        </div>
                        <div>
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary">
                                Chache
                            </button>
                        </div>
                    </div>




                </form>
            </div>
            <div class="tab-content">
                <div role="tabpanel" id="react-aria-292-tabpane-design" aria-labelledby="react-aria-292-tab-design"
                    class="fade pb-4 p-4 tab-pane active show">
                    <div class="table-responsive">
                        <table class="text table" id="rapport_table">
                            <thead style="background: #10439F;color:antiquewhite;">
                                <tr>
                                    <th>Action</th>
                                    <th>Login</th>
                                    <th>View</th>
                                    <th scope="col">Compagnie</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">plan</th>
                                    <th scope="col">Montant initial</th>
                                    <th scope="col">Montant due</th>
                                    <th scope="col">Date Abornement</th>
                                    <th scope="col">Date Expiration</th>
                                    <th scope="col">Bloquer</th>
                                    @if (session('role') == 'admin' || session('role') == 'addeur' || session('role') == 'comptable')
                                        <th scope="col">abonnement</th>
                                    @endif

                                </tr>
                            </thead>
                            <tbody style="">
                                @foreach ($data as $donnee)
                                    <tr>
                                        @if (session('role') == 'admin' || session('role') == 'admin2' || session('role') == 'addeur')
                                            <td>
                                                <form action="{{ route('edit_compagnie') }}" method="POST"> @csrf <input
                                                        type="hidden" name="id" value="{{ $donnee->id }}" /><button
                                                        type="submit"><i class="mdi mdi-pencil mdi-24px"></i></button>
                                                </form>
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>
                                            <form action="{{ route('login_as_company') }}" method="get" target="_blank"> @csrf <input
                                                type="hidden" name="id" value="{{ $donnee->id }}" /><button
                                                type="submit"><i class="mdi mdi-pencil mdi-24px"></i>Login</button>
                                        </form>
                                        </td>
                                        <td>
                                            <form action="{{ route('listecompagnieU') }}" method="POST"> @csrf <input
                                                    type="hidden" name="idcompagnie" value="{{ $donnee->id }}" /><button
                                                    type="submit"><i class="mdi mdi-eye mdi-24px"></i></button></form>
                                        </td>

                                        <td>{{ $donnee->name }}</td>
                                        <td>{{ $donnee->phone }}</td>
                                        <td>{{ $donnee->plan }}</td>
                                        <td> {{ $resultsmontant[$donnee->id] }}</td>
                                        <td>
                                            {{ $results[$donnee->id] * $donnee->plan }} USD</li>
                                        </td>
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
                                                <label class="badge"
                                                    style="background: rgb(3, 148, 34);">Non</label>
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

@stop
