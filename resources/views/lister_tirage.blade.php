@extends('admin-layout')


@section('content')
        <!-- partial -->
      
          
              <div class="page-header">
                <h3 class="page-title"> Lis tiraj yo: {{$tirage->count()}} </h3>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                 
                  </ol>
                </nav>
              </div>
              <div class="col-lg-12 stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                       
                        <tr>
                          <th> code </th>
                          <th> Non </th>
                          <th> Ouvri </th>
                          <th> Femen </th>
                          <th> Tire </th>
                          <th> Aktive </th>
                          <th> Modifye </th>
                        </tr>
                      </thead>
                      <tbody>
                        
                        @foreach ($tirage as $row)
                        <form method="post" action="editertirage">
                            @csrf
                          <tr class="table-info">
                         <input type="hidden" name="id" value="{{ $row->id}}"/>
                          <td> {{$row->id}} </td>
                          <td> {{$row->name}} </td>
                          <td>                                
                            <input style="height:10px;" type="time" class="form-control" value="{{$row->hour_open}}"   name="time_open" />
                   
                       </td>
                          <td>                                
                             <input style="height:10px;" type="time" class="form-control" value="{{$row->hour}}"   name="time" />
                    
                        </td>
                        <td>                                
                            <input style="height:10px;" type="time" class="form-control" value="{{$row->hour_tirer}}"   name="time_tirer" />
                   
                       </td>
                       <td>
                         <label>  <input type="checkbox" @if($row->is_active=='1')   @checked(true) @endif class="form-check-inpu" name="active" value="1" id="membershipRadios2"  > Aktif </label>

                       </td>
                       <td>                                
                        <button  style="" type="submit" class="btn btn-gradient-primary me-2">Modifye</button>               
                       </td>
                        </tr>
                        </form>
                        @endforeach

                      </tbody>
                    </table>
                    </div>
                  </div>
                </div>
              </div>
            
          
        
          <!-- main-panel ends -->
    
@stop