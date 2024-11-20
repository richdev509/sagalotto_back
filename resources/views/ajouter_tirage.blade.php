@extends('admin-layout')


@section('content')
        <!-- partial -->
      
          
              <div class="page-header">
                <h3 class="page-title"> Fom ajoute tiraj </h3>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                 
                  </ol>
                </nav>
              </div>
              <div class="row" style="margin: 10px;border-style:ridge; border-width:1px; border-color:rgb(209, 163, 252);">          
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      
                      <form class="form-sample" method="post" action="ajouterTirage">
                        @csrf
                        <p class="card-description">Info sou tiraj la </p>

                        <div class="row" >
                          <div class="col-md-6">
                            <div class="form-group row">
                              
                              <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">Tiraj</label>
                                <select class="form-control" name="tirage" value="{{old('tirage')}}" style="outline-color: black">
                                    @foreach ($tirage as $row)
                                        <option>{{$row->name}}</option>
                                        
                                    @endforeach
                                </select>
                                <span class="error">@error('tirage') 
                                  {{$message}}
                                 @enderror</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              
                              <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">Lè wap ouvril lan</label>
                                <input style="height:10px;" type="time" class="form-control" value="00:00:00"   name="time_open" />
                                <span class="error">@error('time_open') 
                                  {{$message}}
                                 @enderror</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              
                              <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">Lè wap fèmenl lan</label>
                                <input style="height:10px;" type="time" class="form-control" value="{{old('time')}}"   name="time" />
                                <span class="error">@error('time') 
                                  {{$message}}
                                 @enderror</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              
                              <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">Lè li tire a</label>
                                <input style="height:10px;" type="time" class="form-control" value="{{old('time_tirer')}}"   name="time_tirer"/>
                                <span class="error">@error('time_tirer') 
                                  {{$message}}
                                 @enderror</span>
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