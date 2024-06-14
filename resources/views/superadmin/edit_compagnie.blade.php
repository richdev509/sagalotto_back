@extends('superadmin.admin-layout')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Editer Compagnie</h4>
                <p class="card-description"> </p>
                <form class="forms-sample" action="{{route('update_compagnie')}}" method="POST">
                    @csrf
                    <input type="hidden" value="{{$data->id}}"  name="id"/>
                    <div class="form-group">
                        <label for="exampleInputUsername1">Nom Compagnie</label>
                        <input type="text" class="form-control" value="{{ $data->name }}" name="compagnie"
                            id="InputNomcompagnie" placeholder="Nom capagnie" minlength="4" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">adresse</label>
                        <input type="text" class="form-control" value="{{ $data->address }}" name="adresse"
                            id="Inputadresse" placeholder="Adresse" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">City</label>
                        <input type="text" class="form-control" value="{{ $data->city }}" name="city" id="InputCity"
                            placeholder="city" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email Compagnie</label>
                        <input type="text" class="form-control" value="{{ $data->email }}" name="email"
                            id="InputEmail1" placeholder="email compagnie" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Phone Compagnie</label>
                        <input type="text" class="form-control" value="{{ $data->phone }}" name="phone"
                            id="InputPhone" placeholder="phone">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Plan</label>
                        <select name="plan" class="form-control">
                            @if ($data->plan == 10)
                                <option value="10">10</option>
                                <option value="8">8</option>
                            @else
                                <option value="8">8</option>
                                <option value="10">10</option>
                            @endif


                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Info fiche</label>
                        <textarea class="form-control" name="info">{{$data->info}}</textarea>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputUsername1">Date abonnement</label>
                            <input type="date" class="form-control" value="{{ $data->dateplan }}" name="compagnie"
                                id="InputNomcompagnie" placeholder="Nom capagnie" minlength="4" >
                        </div>
                        
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">mise a jour</button>
                    
                </form>
            </div>
        </div>
    </div>
@stop
