@extends('admin-layout')


@section('content')
        <!-- partial -->
      <style>
            .card-description{
                font-weight: bold;
            }
      </style>
          
              <div class="page-header">
                <h3 class="page-title"> Modifye branch </h3>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                    <li class="breadcrumb-item active" aria-current="page">branch</li>
                  </ol>
                </nav>
              </div>
              <div class="row">          
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      
                      <form class="form-sample" method="post" action="editerBranch">
                        @csrf
                        <p class="card-description">Info sou branch lan </p>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Non branch lan</label>
                              <div class="col-sm-9">
                                <input type="hidden" name="id" value="{{$branch->id}}" />
                                <input type="text" name="name" value="{{$branch->name}}" class="form-control" placeholder="Ba li yon nom" />
                                <span class="error">@error('name') 
                                   {{$message}}
                                  @enderror</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Adrès branch lan</label>
                              <div class="col-sm-9">
                                <input type="text" name="address" value="{{$branch->address}}" class="form-control" placeholder="adrès branch la" />
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
                                  <label class="col-sm-3 col-form-label">Telefon branch la</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{$branch->phone}}"  placeholder="Telefon vandè a" name="phone" />
                                    <span class="error">@error('phone') 
                                      {{$message}}
                                     @enderror</span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label class="col-sm-3 col-form-label">Deskripsyon branch la</label>
                                  <div class="col-sm-9">
                                    <textarea  class="form-control" name="description"   rows="3">{{$branch->description}}</textarea>

                                    <span class="error">@error('description') 
                                      {{$message}}
                                     @enderror</span>
                                  </div>
                                </div>
                              </div>
                       
                        </div>
                      
                        <p class="card-description">Info Sipevize</p>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Non konple</label>
                              <div class="col-sm-9">
                                <input type="text" name="agent_fullname" value="{{$branch->agent_fullname}}" class="form-control" placeholder="Tout nom ajan" />
                                <span class="error">@error('agent_name')
                                    {{$message}}
                                @enderror</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Non Itilizatè</label>
                              <div class="col-sm-9">
                                <input type="text" style="color:black;" class="form-control"  value="{{$branch->agent_username}}" disabled/>
                                <input type="hidden" style="color:black;" class="form-control" name="agent_username" value="{{$branch->agent_username}}" />

                                <span class="error">@error('agent_username') 
                                  {{$message}}
                                 @enderror</span>
                              </div>
                              
                            </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Modpas</label>
                              <div class="col-sm-9">
                                <input type="text" name="agent_password" value=""  class="form-control"/>
                              </div>
                            </div>
                          </div>
                         
                          
                        </div>
                        <div class="row">
                         
                         
                          <button type="submit" class="btn primary" style="background:rgb(0 94 254)">Modifye</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            
          
        
          <!-- main-panel ends -->
    
@endsection