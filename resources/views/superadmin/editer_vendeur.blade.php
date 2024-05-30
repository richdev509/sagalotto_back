@extends('superadmin.admin-layout')
@section('content')
        <!-- partial -->
      
          
              <div class="page-header">
                <h3 class="page-title">Modification vendeur :Compagnie {{ $Compagnie}}</h3>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    
                     <li class="breadcrumb-item"><form action="{{route('listecompagnieU')}}" method="POST"> @csrf <input type="hidden" name="id" value="{{$data->compagnie_id}}"/><button type="submit"><i class="mdi mdi-eye mdi-24px"></i></button></form></td>
                    </li>
                  </ol>
                </nav>
              </div>
              <div class="row">          
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      
                      <form class="form-sample" method="post" action="{{route('update_vendeur2')}}">
                        @csrf
                        <input type="hidden" value="{{$data->compagnie_id}}" name="idcompagnie" />
                        <input type="hidden" value="{{$data->id}}" name="id"/>
                        <p class="card-description">Info sou vandè a </p>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Tout non vandè a</label>
                              <div class="col-sm-9">
                                <input type="text" name="name" value="{{$data->name}}" class="form-control" placeholder="non konplè" required />
                                <span class="error">@error('name') 
                                   {{$message}}
                                  @enderror</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Adrès vandè a</label>
                              <div class="col-sm-9">
                                <input type="text" name="address" value="{{$data->address}}" class="form-control" placeholder="adrès vandè a"  required/>
                                <span class="error">@error('address') 
                                  {{$message}}
                                 @enderror</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Seks</label>
                              <div class="col-sm-9">
                                <select class="form-control" name="gender" value="{{$data->gender}}" required>
                                  <option>Fi</option>
                                  <option>Gason</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Telefon vandè a</label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{$data->phone}}"  placeholder="Telefon vandè a" name="phone"  required/>
                                <span class="error">@error('phone') 
                                  {{$message}}
                                 @enderror</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Pousantaj sou vant</label>
                              <div class="col-sm-9">
                                <input type="number" class="form-control" value="{{$data->percent}}" placeholder="pousantaj" name="percent"  required/>
                                <span class="error">@error('percent') 
                                  {{$message}}
                                 @enderror</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">

                             
                              <div class="col-sm-5">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-inpu" name="block" value="{{$data->is_block}}" id="membershipRadios2"  > Bloke </label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <p class="card-description"> Bank info </p>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Non bank lan</label>
                              <div class="col-sm-9">
                                <input type="text" name="bank_name" value="{{$data->bank_name}}" class="form-control" placeholder="Egzanp: bank1"  required/>
                                <span class="error">@error('bank_name')
                                    {{$message}}
                                @enderror</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">ID machin nan</label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" name="bank_id" value="{{$data->android_id}}" placeholder="id machin kap konekte a" required/>
                                <span class="error">@error('bank_id') 
                                  {{$message}}
                                 @enderror</span>
                              </div>
                              
                            </div>
                          </div>
                        </div>
                        
                        @if (session('role')=='admin')
                        <div class="row" >
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Itilizatè</label>
                              <div class="col-sm-9">
                                <input type="text" name="username" value="{{$data->username}}"class="form-control" placeholder="470000"  required/>
                              </div>
                            </div>
                          </div>
                          
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Modepas</label>
                              <div class="col-sm-9">
                                <input type="password" class="form-control" value="{{old('password')}}" name="password" placeholder=""/>
                                <span class="error">@error('password') 
                                  {{$message}}
                                 @enderror</span>
                              </div>
                            </div>
                          </div>
                          
                        </div>
                        @endif
                        <div class="row">
                         
                         
                          <button type="submit" class="btn btn-gradient-primary mb-2">mise ajour</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            
          
        
          <!-- main-panel ends -->
    
@endsection