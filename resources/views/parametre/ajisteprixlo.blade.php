@extends('admin-layout')
@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Pwemye Lo</h4>
      <p class="card-description">Ajiste pri pwemye lo</p>
      <form class="forms-sample" action="{{route('updateprilo')}}" method="POST">
        @csrf
    <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"> pri ki la se : X fwa</span>
          </div>
          <div class="input-group-prepend">
            
            <span class="input-group-text">   @if(isset($data) && $data->prix >0) {{$data->prix}} @else 0 @endif</span>
          </div>
          <select  name="montant" style="border-style: solid;
          border-color: royalblue;
          border-width: thin;
          color: #d10a52;
          font-size: x-large;" class="form-control" aria-label="Montant (ajoute montant a mise a jour)">
            <option disabled>Selectionner</option>
            <option>--</option>
            <option value="1">50</option>  
          <option value="2">60</option> 
        </select>
          @if(isset($data) && $data->id)
          <button type="submit" class="btn btn-gradient-primary me-2">Mete a jou</button>
          @endif
        </div>
      </div>
    </form>
    </div>
</div>






@stop