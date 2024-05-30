@extends('superadmin.admin-layout')
@section('content')

<div class="card mb-4 box-shadow">
    <div class="card-header">
      <h4 class="my-0 font-weight-normal">Mise a jour abonnement compagnie</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{route('add_abonnement')}}">
      @csrf
      
        <div class="form-group">
            <label for="exampleInputUsername1">Telephone Compagnie</label>
      <input type="number" class="form-control" name="phone" placeholder="numeroTelephone Compagnie" required/>
        </div>
     
      
        <div class="form-group">
            <label for="exampleInputUsername1">Nom Compagnie</label>
            @if(isset($data))
      <input type="text"  class="form-control" name="name" value="{{$data->name}}" placeholder="Nom Compagnie" required/> 
            @endif
        </div>
      
     
        <div class="form-group">
            <label for="exampleInputUsername1">Date abonnement</label>
      <input type="date" class="form-control" name="date" @if(isset($data)) value="{{ $data->dateexpiration}}" @endif /> 
        </div>
      

      
        <div class="form-group">
            <label for="exampleInputUsername1">Nombre de mois abonnement</label>
      <input type="number" class="form-control" name="duree" placeholder="nombre de mois" required/> 
        
      </div>
      <button type="submit" class="btn btn-lg btn-block btn-primary">mise a jour</button>
        </form>
    </div>
  </div>

  @stop
