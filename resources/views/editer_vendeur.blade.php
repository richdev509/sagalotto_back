@extends('admin-layout')


@section('content')
        <!-- partial -->
      
          
              <div class="page-header">
                <h3 class="page-title"> Fom modifye vandè </h3>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vandè</li>
                  </ol>
                </nav>
              </div>
              <div class="row">          
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      
                      <form class="form-sample" method="post" action="editervendeur">
                        @csrf
                        <p class="card-description">Info sou vandè a </p>
                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Kod vandè a</label>
                                <div class="col-sm-9">
                                  <input type="text"  value="{{$vendeur->code}}" class="form-control" disabled/>
                                  <input type="hidden" name="id" value="{{$vendeur->id}}" class="form-control" />

                                </div>
                              </div>
                            </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Tout non vandè a</label>
                              <div class="col-sm-9">
                                <input type="text" name="name" value="{{$vendeur->name}}" class="form-control" placeholder="non konplè" />
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
                                <input type="text" name="address" value="{{$vendeur->address}}" class="form-control" placeholder="adrès vandè a" />
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
                                <select class="form-control" name="gender" value="{{old('gender')}}">
                                  <option selected>{{$vendeur->gender}}</option>
                                  @if($vendeur->gender=='Fi')
                                  <option>Gason</option>
                                  @else
                                  <option>Fi</option>

                                  @endif
                                 
                                 
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Telefon vandè a</label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{$vendeur->phone}}"  placeholder="Telefon vandè a" name="phone" />
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
                                <input type="number" class="form-control" value="{{$vendeur->percent}}" placeholder="pousantaj" name="percent" />
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
                                    <input type="checkbox" @if($vendeur->is_block=='1')   @checked(true) @endif class="form-check-inpu" name="block" value="1" id="membershipRadios2"  > Bloke </label>
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
                                <input type="text" name="bank_name" value="{{$vendeur->bank_name}}" class="form-control" placeholder="Egzanp: bank1" />
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
                                <input type="text" name="bank_id" value="{{$vendeur->android_id}}" class="form-control" placeholder="Egzanp: bank1" />
                                <span class="error">@error('bank_id')
                                    {{$message}}
                                @enderror</span>
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Itilizatè</label>
                              <div class="col-sm-9">
                                <input type="text" name="username" value="{{$vendeur->username}}"class="form-control" placeholder="470000" />
                              </div>
                            </div>
                          </div>
                         
                          
                        </div>
                        <div class="row">
                         
                         
                          <button type="submit" class="btn btn-gradient-primary mb-2">Modifye</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            
          
        
          <!-- main-panel ends -->
    
@endsection