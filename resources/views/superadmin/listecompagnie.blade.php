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
            <a href="/wp-admin/add-vendeur?idC={{$data->id}}"><button class="form-control">Ajouter vendeur</button></a>
            <div role="tabpanel" id="react-aria-292-tabpane-design" aria-labelledby="react-aria-292-tab-design"
                class="fade pb-4 p-4 tab-pane active show">
                <div class="table-responsive">
                    <table class="text table" id="rapport_table">
                        <thead style="background: #10439F;color:antiquewhite;">
                            <tr>
                                <th>Action</th>
                                
                                <th scope="col">Compagnie</th>
                                <th scope="col">Phone</th>
                                <th scope="col">plan</th>
                                <th scope="col">Date Expiration</th>
                                <th scope="col">Nombre de pos</th>
                                

                            </tr>
                        </thead>
                        <tbody style="">
                          
                            <tr>
                                <td><form action="{{route('edit_compagnie')}}" method="POST"> @csrf <input type="hidden" name="id" value="{{$data->id}}"/><button type="submit"><i class="mdi mdi-pencil mdi-24px"></i></button></form></td>
                              
                                <td>{{$data->name}}</td>
                            <td>{{$data->phone}}</td>
                            <td>{{$data->plan}}</td>
                            <td>{{$data->dateexpiration}}</td>
                            <td>{{$data->number_pos}}</td>
                            </tr>
             
                          

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="tab-content">
            <div role="tabpanel" id="react-aria-292-tabpane-design" aria-labelledby="react-aria-292-tab-design"
                class="fade pb-4 p-4 tab-pane active show">
                <div class="table-responsive">
                    <table class="text table" id="rapport_table">
                        <thead style="background: #10439F;color:antiquewhite;">
                            <tr>
                                <th>Action</th>
                                
                                <th scope="col">Vendeur</th>
                                <th scope="col">Username</th>
                                <th scope="col">Bank</th>
                                <th scope="col">IdPOS<i class="mdi mdi-cash-register mdi-24px float-right"></th>
                                <th scope="col">Status block</th>
                                <th scope="col">block/Unblock</th>
                                

                            </tr>
                        </thead>
                        <tbody style="">
                            @foreach ($datas as $dataD)
                            <tr>
                                <td><form action="{{route('edit_vendeur')}}" method="POST"> @csrf <input type="hidden" name="id" value="{{$data->id}}" /> <input type="hidden" name="iduser" value="{{$dataD->id}}" /> <input type="hidden" value="{{$data->name}}" name="compagnie"/><button type="submit"><i class="mdi mdi-pencil mdi-24px"></i></button></form></td>
                              
                            <td>{{$dataD->name}}</td>
                            <td>{{$dataD->username}}</td>
                            <td>{{$dataD->bank_name}}</td>
                            <td>{{$dataD->android_id}}</td>
                            <td>{{$dataD->is_block}}</td>
                            <td><form action="{{route('blockunlock')}}" method="POST"> @csrf <input type="hidden" name="id" value="{{$dataD->id}}"/><button type="submit"><i class="mdi mdi-block-helper mdi-24px"></i></button></form></td>
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