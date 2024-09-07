@extends('superadmin.admin-layout')
@section('content')

    <div class="card mb-4 box-shadow">
        <div class="card-header" style="    background: #0e75b3;
        color: white;">
            <h4 class="my-0 font-weight-normal">Generer facture</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="genererfacture">
                @csrf
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputUsername1">Compagnie</label>
                             <select name="company" id="company" class="form-control" data-live-search="true" style="height: 50px;">
                                @foreach ($data as $row)
                                  <option value="{{$row->id}}">{{$row->code}}({{$row->name}})</option>

                                @endforeach
                             </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date">Date Expiration</label>
                                <input type="date" class="form-control" name="date" 
                                    placeholder="" required />
                           
                        </div>
                    </div>
                </div>

             

                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-block btn-primary">Generer</button>

                </div>
                @if($facture ==1)
                <table class="table table-striped">
                    <h4>Sagaloto.com</h4>
                    <thead>
                        <tr>Facture du {{$date}} 
                        </tr>
                    </thead>
                    <tbody style="border: 1px solid #ac32cb;">
                            <tr>
                                <td colspan="2" class="text-center">{{$compagnie->name}}, Tel {{$compagnie->phone}}</td>
                            </tr>


                            <tr>
                                <td>POS Actif pour ce mois:</td>
                                <td>{{ $vendeur}}</td>

                            </tr>
                            <tr>
                                <td>Montant par POS:</td>
                                <td>{{ $compagnie->plan}}$</td>

                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td>{{ $vendeur * $compagnie->plan}}$</td>

                            </tr>
                            
                           
                            

                        
                    </tbody>
                </table>
                <p>NB: Ou gen yon delai de 5 jou pouw peye. Apresa system nan ap bloke ou otomatik. pou tout difikilte kontakte administrasyon an.



                @endif
            </form>
        </div>
    </div>

@stop
