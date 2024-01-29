@extends('admin-layout')


@section('content')
    <div class="page-header">
        <h3 class="page-title">Lis vandè </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Vandè</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Lis vandè yo: {{$vendeur->count()}}</h4>

                </p>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kod</th>
                            <th>Bank</th>
                           
                            <th>Itilizatè</th>
                            <th>Aktif</th>
                            <th>Bloke</th>
                            <th class="text-end">Aksyon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($vendeur as $row)
                          
                              <tr>
                                <td>{{ $i}}</td>
                                <td>{{ $row->code }}</td>
                                <td>{{ $row->bank_name }}</td>
                              
                                <td>{{ $row->username }}</td>
                                <td style="color:green;">Wi</td>

                                @if ($row->is_block == 1)
                                    <td style="color:red;">Wi</td>
                                @else
                                    <td style="color:green;">Non</td>
                                @endif

                                <td class="text-end">
                                    <form action="editer-vendeur">
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
@endsection
