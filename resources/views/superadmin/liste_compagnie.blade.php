@extends('superadmin.admin-layout')
@section('content')


<div class="card">
    <div class="p-0 card-body">
        <div class="row">

            <form id="rapport_form">
                @csrf
                <div class="form-group" style="display:inline-flex;border: 1px solid #ab61e7;padding: 0px;">
                    
                    <div>
                        <input class="form-control" type="text" name="text" placeholder="recherche(nom ou phone)" required>
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
                                <th>View</th>
                                <th scope="col">Compagnie</th>
                                <th scope="col">Phone</th>
                                <th scope="col">plan</th>
                                <th scope="col">Date Expiration</th>
                                <th scope="col">Nombre de pos</th>
                                

                            </tr>
                        </thead>
                        <tbody style="">
                            @foreach($data as $donnee)
                            <tr>
                                <td><form action="{{route('edit_compagnie')}}" method="POST"> @csrf <input type="hidden" name="id" value="{{$donnee->id}}"/><button type="submit"><i class="mdi mdi-pencil mdi-24px"></i></button></form></td>
                                <td><form action="{{route('listecompagnieU')}}" method="POST"> @csrf <input type="hidden" name="idcompagnie" value="{{$donnee->id}}"/><button type="submit"><i class="mdi mdi-eye mdi-24px"></i></button></form></td>
                            
                                <td>{{$donnee->name}}</td>
                            <td>{{$donnee->phone}}</td>
                            <td>{{$donnee->plan}}</td>
                            <td>{{$donnee->dateexpiration}}</td>
                            <td>{{$donnee->number_pos}}</td>
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