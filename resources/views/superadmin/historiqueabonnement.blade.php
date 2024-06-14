@extends('superadmin.admin-layout')
@section('content')

<div class="card">
    <div class="p-0 card-body">

        <div class="tab-content">
            <div role="tabpanel" id="react-aria-292-tabpane-design" aria-labelledby="react-aria-292-tab-design"
                class="fade pb-4 p-4 tab-pane active show">
                <div class="table-responsive">
                    <table class="text table" id="rapport_table">
                        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
                        <thead style="background: #10439F;color:antiquewhite;">
                            <tr>
                               
                                <th>TRANSACTION ID</th>
                                <th scope="col">Compagnie</th>
                                <th scope="col">Nbr POS</th>
                                <th scope="col">Montant</th>
                                <th scope="col">Balance</th>
                                <th scope="col">Etat</th>
                                <th scope="col">Peye</th>
                                
                               

                            </tr>
                        </thead>
                        <tbody style="">
                            @forelse($data as $donnee)
                            <tr>
                                <td>{{$donnee->id}}</td>
                                <td>{{$donnee->compagnie->name}}</td>
                                <td>{{$donnee->compagnie->name}}</td>
                                <td>{{$donnee->montant}}</td>
                                 <td> @if($donnee->balance>0)  <label class="badge" style="background: rgb(148, 61, 3);">{{$donnee->balance}} $</label>@endif</td>
                                 <td>@if($donnee->etat=="dwe")<label class="badge" style="background: rgb(206, 192, 2);">{{$donnee->etat}} 
                                </label>@else <label class="badge" style="background: rgb(2, 194, 11);">ok</label>@endif</td>
                               <td> @if($donnee->etat=="dwe")<form class="form" method="POST" action="{{route('paiementsdwe')}}">@csrf <input type="hidden" value="{{$donnee->id}}" name="id"/><input class="form-control" placeholder="montant" name="montant" type="number" min="{{$donnee->balance}}" max="{{$donnee->balance}}" required/>
                                <button type="submit" class="btn btn-gradient-primary me-2" style="margin-top: 5px;">Send</button></form>@endif</td>
                        </tr>
                            @empty
        <tr>
            <td colspan="6" style="text-align: center">Aucune donnée disponible</td>
        </tr>
        @endforelse

                        </tbody>
                    </table>

                </div>
            </div>
        </div>



    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">...</div>
        <div class="modal-footer">
          <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
          <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  
@stop