@extends('superadmin.admin-layout')
@section('content')

    <div class="card mb-4 box-shadow">
        <div class="card-header" style="    background: #0e75b3;
        color: white;">
            <h4 class="my-0 font-weight-normal">Mise a jour abonnement compagnie</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('add_abonnement') }}">
                @csrf
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputUsername1">Code compagnie</label>
                            @if (isset($data))
                                <input type="text" class="form-control" name="code" value="{{ $data->code }}"
                                    placeholder="code" required />
                            @else
                                <input type="text" class="form-control" name="code" value="" placeholder="code"
                                    required />
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputUsername1">Nom compagnie</label>
                            @if (isset($data))
                                <input type="text" class="form-control" name="name" value="{{ $data->name }}"
                                    placeholder="Nom Compagnie"/>
                            @else
                                <input type="text" class="form-control" name="name" value=""
                                    placeholder="Nom Compagnie" required />
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputUsername1">Date abonnement</label>
                            <input type="date" class="form-control" name="date"
                                @if (isset($data)) value="{{ $data->dateexpiration }}" @endif />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputUsername1">Montant</label>
                            <input type="number" class="form-control" name="montant" required />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputUsername1">Nombre de mois abonnement</label>
                    <input type="number" class="form-control" name="duree" placeholder="nombre de mois" required />
                </div>
                <button type="submit" class="btn btn-lg btn-block btn-primary">mise a jour</button>
            </form>
        </div>
    </div>

@stop
