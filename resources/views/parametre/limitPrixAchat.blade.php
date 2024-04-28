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


                            <form method="post" action="{{ route('limitprixstore') }}">
                                @csrf
                                <tr class="table-info">

                                    <input type="hidden" name="id" value="bolet" />

                                    <td>Bolet</td>
                                    <td>
                                        @if ($limitprix)
                                            <input style="height:10px;" type="number" class="form-control"
                                                value="{{ $limitprix->bolet != null ? $limitprix->bolet : '' }}"
                                                name="prix" />
                                        @else
                                            <input style="height:10px;" type="number" class="form-control" value=""
                                                name="prix" />
                                        @endif

                                    </td>

                                    <td>
                                        @if ($limitprix->boletetat == 1)
                                            <label> <input type="checkbox" class="form-check-inpu"
                                                    @if ($limitprix->boletetat == 1) @checked(true) @endif
                                                    name="active" id="membershipRadios2"> Aktif</label>
                                        @else
                                            <label> <input type="checkbox" class="form-check-inpu" name="active"
                                                    id="membershipRadios2"> Aktif </label>
                                        @endif
                                    </td>
                                    <td>
                                        <button style="" type="submit"
                                            class="btn btn-gradient-primary me-2">Modifye</button>
                                    </td>
                                </tr>
                            </form>

                            <form method="post" action="{{ route('limitprixstore') }}">
                                @csrf
                                <tr class="table-info">

                                    <input type="hidden" name="id" value="maryaj" />

                                    <td>Maryaj</td>
                                    <td>
                                        @if ($limitprix)
                                            <input style="height:10px;" type="number" class="form-control"
                                                value="{{ $limitprix->maryaj != null ? $limitprix->maryaj : '' }}"
                                                name="prix" />
                                        @else
                                            <input style="height:10px;" type="number" class="form-control" value=""
                                                name="prix" />
                                        @endif

                                    </td>

                                    <td>
                                        @if ($limitprix->maryajetat == 1)
                                            <label> <input type="checkbox" class="form-check-inpu"
                                                    @if ($limitprix->maryajetat == 1) @checked(true) @endif
                                                    name="active" id="membershipRadios2"> Aktif </label>
                                        @else
                                            <label> <input type="checkbox" class="form-check-inpu" name="active"
                                                    id="membershipRadios2"> Aktif </label>
                                        @endif
                                    </td>
                                    <td>
                                        <button style="" type="submit"
                                            class="btn btn-gradient-primary me-2">Modifye</button>
                                    </td>
                                </tr>
                            </form>

                            <form method="post" action="{{ route('limitprixstore') }}">
                                @csrf
                                <tr class="table-info">

                                    <input type="hidden" name="id" value="loto3" />

                                    <td>Loto3</td>
                                    <td>
                                        @if ($limitprix)
                                            <input style="height:10px;" type="number" class="form-control"
                                                value="{{ $limitprix->loto3 != null ? $limitprix->loto3 : '' }}"
                                                name="prix" />
                                        @else
                                            <input style="height:10px;" type="number" class="form-control" value=""
                                                name="prix" />
                                        @endif

                                    </td>

                                    <td>
                                        @if ($limitprix->loto3etat == 1)
                                            <label> <input type="checkbox" class="form-check-inpu"
                                                    @if ($limitprix->loto3etat == 1) @checked(true) @endif
                                                    name="active" id="membershipRadios2"> Aktif </label>
                                        @else
                                            <label> <input type="checkbox" class="form-check-inpu" name="active"
                                                    id="membershipRadios2"> Aktif </label>
                                        @endif
                                    </td>
                                    <td>
                                        <button style="" type="submit"
                                            class="btn btn-gradient-primary me-2">Modifye</button>
                                    </td>
                                </tr>
                            </form>


                            <form method="post" action="{{ route('limitprixstore') }}">
                                @csrf
                                <tr class="table-info">

                                    <input type="hidden" name="id" value="loto4" />

                                    <td>Loto4</td>
                                    <td>
                                        @if ($limitprix)
                                            <input style="height:10px;" type="number" class="form-control"
                                                value="{{ $limitprix->loto4 != null ? $limitprix->loto4 : '' }}"
                                                name="prix" />
                                        @else
                                            <input style="height:10px;" type="number" class="form-control"
                                                value="" name="prix" />
                                        @endif

                                    </td>

                                    <td>
                                        @if ($limitprix->loto4etat == 1)
                                            <label> <input type="checkbox" class="form-check-inpu"
                                                    @if ($limitprix->loto4etat == 1) @checked(true) @endif
                                                    name="active" id="membershipRadios2"> Aktif </label>
                                        @else
                                            <label> <input type="checkbox" class="form-check-inpu" name="active"
                                                    id="membershipRadios2"> Aktif </label>
                                        @endif
                                    </td>
                                    <td>
                                        <button style="" type="submit"
                                            class="btn btn-gradient-primary me-2">Modifye</button>
                                    </td>
                                </tr>
                            </form>


                            <form method="post" action="{{ route('limitprixstore') }}">
                                @csrf

                                <tr class="table-info">

                                    <input type="hidden" name="id" value="loto5" />

                                    <td>Loto5</td>
                                    <td>
                                        @if ($limitprix)
                                            <input style="height: 10px;
              
                width: 100px;"
                                                type="number" class="form-control"
                                                value="{{ $limitprix->loto5 != null ? $limitprix->loto5 : '' }}"
                                                name="prix" />
                                        @else
                                            <input style="height:10px;" type="number" class="form-control"
                                                value="" name="prix" />
                                        @endif

                                    </td>

                                    <td>
                                        @if ($limitprix)
                                            <label> <input type="checkbox" class="form-check-inpu"
                                                    @if ($limitprix->loto5etat == 1) @checked(true) @endif
                                                    name="active" id="membershipRadios2"> Aktif </label>
                                        @else
                                            <label> <input type="checkbox" class="form-check-inpu" name="active"
                                                    id="membershipRadios2"> Aktif </label>
                                        @endif

                                    </td>
                                    <td>
                                        <button style="" type="submit"
                                            class="btn btn-gradient-primary me-2">Modifye</button>
                                    </td>
                                </tr>
                            </form>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
    
        <div class="card">
            
          <div class="card-body">
            
            <h4 class="card-title">Limit pri pa boul</h4>
            
            </p>
            <div class="form-group">
              <label for="dateFilter">Filtre par Type:</label>
             
              <select class="form-control" name="select" id="selectype" style="width: 192px;
              border-color: blue;
              outline-color: aqua;
              color: #41374b;">
              @foreach($listetirage as $lists)
                <option value="{{$lists->tirage_id}}">
                         {{$lists->name}}
                </option>
                @endforeach
              </select>
              <div style="margin-top: 20px;"></div>
              <a href="/ajisteprix">
                <button class="btn btn-primary me-1">Ajoute</button>
                </a>
          </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                  <thead>
                    <tr>
                      <th>Type</th>
                      <th>boul</th>
                      <th>Montan HTG</th>
                      <th>Aksyon</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($limitprixboul as $limit)
                    <tr>

                        <td>{{ $limit->type != null ? $limit->type : '' }}</td>
                        <td> {{$limit->opsyon != null ? $limit->opsyon : ''}}:{{ $limit->boul != null ? $limit->boul : '' }}
                        </td>
                        <td> {{ $limit->montant != null ? $limit->montant : '' }}</td>
                        
                        <td class="text-end">
                          <form action="">
                              <input type="hidden" name="id" value="{{ $limit->id != null ? $limit->id : '' }}" />
                              <button type="submit" style="color: rgb(0, 132, 255);"><i class="mdi mdi-table-edit"></i></button>
                          </form>
                          <form action="" style="display: flex;gap:3px;">
                            <input type="hidden" name="id" value="{{ $limit->id != null ? $limit->id : '' }}" />
                            <button type="submit" style="color: red;"><i class="mdi mdi-delete"></i></button>
                          </form>
                        </td>
                    </tr>
                   @endforeach
                    
                  </tbody>
                </table>
            </div>
    
    
    
          </div>
        </div>
    </div>



@stop
