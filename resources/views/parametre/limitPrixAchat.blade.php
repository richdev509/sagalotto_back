@extends('admin-layout')
@section('content')
  
<div class="page-header">
    <h3 class="page-title">Limit Pri acha :</h3>
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
              
              <th> Non </th>
              <th> Pri </th>
              <th> Aktive </th>
              <th> Modifye </th>
            </tr>
          </thead>
          <tbody>
            
            
            <form method="post" action="{{route('limitprixstore')}}">
                @csrf
              <tr class="table-info">
              
             <input type="hidden" name="id" value="bolet"/>
                
              <td>Bolet</td>
              <td>  
                @if($limitprix)
                <input style="height:10px;" type="number" class="form-control" value="{{ $limitprix->bolet != null ? $limitprix->bolet : '' }}" name="prix"/>
                @else
                <input style="height:10px;" type="number" class="form-control" value=""   name="prix" />
        
                @endif                              
                 
              </td>
           
             <td>
             @if($limitprix->boletetat==1)
             <label>  <input type="checkbox"  class="form-check-inpu" @if($limitprix->boletetat==1)   @checked(true) @endif name="active"  id="membershipRadios2"  > Aktif</label>
             @else
             <label>  <input type="checkbox"  class="form-check-inpu"  name="active"  id="membershipRadios2"  > Aktif </label>
           
             @endif
             </td>
             <td>                                
             <button  style="" type="submit" class="btn btn-gradient-primary me-2">Modifye</button>               
              </td>
              </tr>
            </form>

            <form method="post" action="{{route('limitprixstore')}}">
                @csrf
              <tr class="table-info">
                
                <input type="hidden" name="id" value="maryaj"/>
                   
              <td>Maryaj</td>
              <td>  
                @if($limitprix)
                <input style="height:10px;" type="number" class="form-control" value="{{ $limitprix->maryaj != null ? $limitprix->maryaj : '' }}" name="prix"/>
                @else
                <input style="height:10px;" type="number" class="form-control" value=""   name="prix" />
        
                @endif                              
                 
              </td>
           
             <td>
             @if($limitprix->maryajetat==1)
             <label>  <input type="checkbox"  class="form-check-inpu" @if($limitprix->maryajetat==1)   @checked(true) @endif name="active"  id="membershipRadios2"  > Aktif </label>
             @else
             <label>  <input type="checkbox"  class="form-check-inpu"  name="active"  id="membershipRadios2"  > Aktif </label>
           
             @endif
             </td>
              <td>                                
             <button  style="" type="submit" class="btn btn-gradient-primary me-2">Modifye</button>               
              </td>
             </tr>
            </form>

            <form method="post" action="{{route('limitprixstore')}}">
                @csrf
              <tr class="table-info">
               
             <input type="hidden" name="id" value="loto3"/>
              
              <td>Loto3</td>
              <td>  
                @if($limitprix)
                <input style="height:10px;" type="number" class="form-control" value="{{ $limitprix->loto3 != null ? $limitprix->loto3 : '' }}" name="prix"/>
                @else
                <input style="height:10px;" type="number" class="form-control" value=""   name="prix" />
        
                @endif                              
                 
              </td>
           
             <td>
             @if($limitprix->loto3etat==1)
             <label>  <input type="checkbox"  class="form-check-inpu" @if($limitprix->loto3etat==1)   @checked(true) @endif name="active"  id="membershipRadios2"  > Aktif </label>
             @else
             <label>  <input type="checkbox"  class="form-check-inpu"  name="active"  id="membershipRadios2"  > Aktif </label>
           
             @endif
             </td>    
             <td>                           
             <button  style="" type="submit" class="btn btn-gradient-primary me-2">Modifye</button>               
              </td>
             </tr>
            </form>


            <form method="post" action="{{route('limitprixstore')}}">
                @csrf
              <tr class="table-info">
                
             <input type="hidden" name="id" value="loto4"/>
             
              <td>Loto4</td>
              <td>  
                @if($limitprix)
                <input style="height:10px;" type="number" class="form-control" value="{{ $limitprix->loto4 != null ? $limitprix->loto4 : '' }}" name="prix"/>
                @else
                <input style="height:10px;" type="number" class="form-control" value=""   name="prix" />
        
                @endif                              
                 
              </td>
           
             <td>
             @if($limitprix->loto4etat==1)
             <label>  <input type="checkbox"  class="form-check-inpu" @if($limitprix->loto4etat==1)   @checked(true) @endif name="active"  id="membershipRadios2"  > Aktif </label>
             @else
             <label>  <input type="checkbox"  class="form-check-inpu"  name="active"  id="membershipRadios2"  > Aktif </label>
           
             @endif
             </td> 
             <td>                              
             <button  style="" type="submit" class="btn btn-gradient-primary me-2">Modifye</button>               
              </td>
             </tr>
            </form>
           

            <form method="post" action="{{route('limitprixstore')}}">
                @csrf

              <tr class="table-info">
                
             <input type="hidden" name="id" value="loto5"/>
              
              <td>Loto5</td>
              <td>  
                @if($limitprix)
                <input style="height:10px;" type="number" class="form-control" value="{{ $limitprix->loto5 != null ? $limitprix->loto5 : '' }}" name="prix"/>
                @else
                <input style="height:10px;" type="number" class="form-control" value=""   name="prix" />
        
                @endif                              
                 
              </td>
           
             <td>
             @if($limitprix)
             <label>  <input type="checkbox"  class="form-check-inpu" @if($limitprix->loto5etat==1)   @checked(true) @endif name="active"  id="membershipRadios2"  > Aktif </label>
             @else
             <label>  <input type="checkbox"  class="form-check-inpu"  name="active"  id="membershipRadios2"  > Aktif </label>
           
             @endif
             <
             </td>  
             <td>                             
             <button  style="" type="submit" class="btn btn-gradient-primary me-2">Modifye</button>               
              </td>
             </tr>
            </form>

          </tbody>
        </table>
        </div>
      </div>
    </div>
  </div>



@stop