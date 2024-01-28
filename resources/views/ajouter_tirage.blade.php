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
              <div class="row">          
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      
                      <form class="form-sample" method="post" action="ajouterTirage">
                        @csrf
                        <p class="card-description">Info sou tiraj la </p>

                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              
                              <div class="col-sm-9">
                                <label class="col-sm-3 col-form-label">Tiraj</label>
                                <select class="form-control" name="tirage" value="{{old('tirage')}}">
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
                                <label class="col-sm-3 col-form-label">Lè lap fème</label>
                                <input style="height:10px;" type="time" class="form-control" value="{{old('phone')}}"   name="time" />
                                <span class="error">@error('time') 
                                  {{$message}}
                                 @enderror</span>
                              </div>
                            </div>
                          </div>
                        </div>                     
                       
                        <div class="row">
                         
                         
                          <button type="submit" class="btn btn-gradient-primary mb-2">Anrejistre</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            
          
        
          <!-- main-panel ends -->
    
@endsection