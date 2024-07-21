@extends('admin-layout')


@section('content')
        <!-- partial -->
      <style>
            .card-description{
                font-weight: bold;
            }
      </style>
          
              <div class="page-header">
                <h3 class="page-title"> Fom ajoute branch </h3>
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
                      
                      <form class="form-sample" method="post" action="creerBranch">
                        @csrf
                        <p class="card-description">Info sou branch lan </p>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Non branch lan</label>
                              <div class="col-sm-9">
                                <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="Ba li yon nom" />
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
                                <input type="text" name="address" value="{{old('address')}}" class="form-control" placeholder="adrès branch la" />
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
                                    <input type="text" class="form-control" value="{{old('phone')}}"  placeholder="Telefon vandè a" name="phone" />
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
                                    <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>

                                    <span class="error">@error('description') 
                                      {{$message}}
                                     @enderror</span>
                                  </div>
                                </div>
                              </div>
                       
                        </div>
                      
                        <p class="card-description">Info sipevize</p>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Non konple</label>
                              <div class="col-sm-9">
                                <input type="text" name="agent_name" value="{{old('agent_name')}}" class="form-control" placeholder="Tout nom sipevize a" />
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
                                <input type="text" class="form-control" name="agent_username" vlaue="{{old('agent_username')}}" placeholder="itilizate pou ajan konekte"/>
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
                                <input type="password" name="agent_password" value="{{old('agent_password')}}"class="form-control"/>
                              </div>
                            </div>
                          </div>
                         
                          
                        </div>
                        <div class="row">
                         
                         
                          <button type="submit" class="btn primary" style="background:rgb(0 94 254)">Anrejistre</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            
          
        
          <!-- main-panel ends -->
    
@endsection