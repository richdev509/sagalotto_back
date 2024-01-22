@extends('admin-layout')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    
    <div class="card">
        
      <div class="card-body">
        
        <h4 class="card-title">List lo ki ajoute</h4>
        <p class="card-description">On-boule bloke  <code>Off- Boul debloke</code>
        </p>

        <div class="table-responsive">
            <table class="table table-bordered" >
              <thead>
                <tr>
                  <th> Tiraj non </th>
                  <th> Date </th>
                  <th> Lo Yo </th>
                  <th> Etat </th>
                  <th> Action </th>
                </tr>
              </thead>
              <tbody>
                @foreach($list as $lists)
                <tr>
                    <td>{{$lists->tirage->name}}</td>
                    <td>{{}}</td>
                    <td><button type="button" class="btn btn-social-icon btn-youtube btn-rounded">{{$lists->unchiffre}}</button>  
                        <button type="button" class="btn btn-social-icon btn-facebook btn-rounded">{{$lists->premierchiffre}}</button>
                        <button type="button" class="btn btn-social-icon btn-dribbble btn-rounded">{{$lists->secondchiffre}}</button>
                        <button type="button" class="btn btn-social-icon btn-linkedin btn-rounded">{{$lists->troisiemechiffre}}</button>
                    </td>
                    <td>Tiraj 100%</td>
                    <td>Modifier</td>
                </tr>
                @endforeach
              </tbody>
            </table>
        </div>



      </div>
    </div>
</div>





@stop