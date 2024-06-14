@extends('superadmin.admin-layout')
@section('content')

<div class="card">
    <div class="p-0 card-body">

        <div class="tab-content">
            <div role="tabpanel" id="react-aria-292-tabpane-design" aria-labelledby="react-aria-292-tab-design"
                class="fade pb-4 p-4 tab-pane active show">
                <div class="table-responsive">
                    <table class="text table" id="rapport_table">
                        <thead style="background: #10439F;color:antiquewhite;">
                            <tr>
                               
                                <th>TRANSACTION ID</th>
                                <th scope="col">ID abonnement</th>
                                <th scope="col">Compagnie</th>
                                <th scope="col">Montant</th>
                                <th scope="col">Libelle</th>
                                <th scope="col">Employer</th>
                               

                            </tr>
                        </thead>
                        @forelse ($data as $donnee)
        <tr>
            <td>{{ $donnee->id }}</td>
            <td>{{ $donnee->idabonnement }}</td>
            <td>{{ $donnee->compagnie->name }}</td>
            <td>{{ $donnee->montant }}</td>
            <td>{{ $donnee->libelle }}</td>
            <td>{{ $donnee->admin->fullname }}</td>
            
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center">Aucune donnée disponible</td>
        </tr>
        @endforelse
                    </table>

                </div>
            </div>
        </div>



    </div>
</div>
@stop