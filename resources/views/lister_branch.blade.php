@extends('admin-layout')


@section('content')
    <div class="page-header">
        <h3 class="page-title">Lis branch </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Branch</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Lis branch yo: {{$branch->count()}}</h4>

                </p>
                <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kod</th>
                            <th>Non</th>
                           
                            <th>Sipevize</th>
                           
                            <th class="text-end">Aksyon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($branch as $row)
                          
                              <tr>
                                <td>{{ $i}}</td>
                                <td>{{ $row->code }}</td>
                                <td>{{ $row->name }}</td>
                              
                                <td>{{ $row->full_name }}</td>
                             
                                <td class="text-end">
                                    <form action="editer_branch">
                                        <input type="hidden" name="id" value="{{$row->id}}" />
                                        <button type="submit"><i class="mdi mdi-table-edit"></i></button>

                                    </form>


                                </td>
                            </tr>
                            @php $i =$i +1 ; @endphp
                        @endforeach



                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
@endsection
