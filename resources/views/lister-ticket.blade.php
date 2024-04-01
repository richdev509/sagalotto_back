@extends('admin-layout')


@section('content')
    <style type="text/css">
        .content {
            position: absolute;
            top: auto;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            height: auto;
            text-align: center;
            background-color: white;
            border: 1px solid blueviolet;
            box-sizing: border-box;
            padding: 10px;
            z-index: 100;
            display: none;
            overflow: scroll;
            /*to hide popup initially*/
        }

        .close-btn {
            position: absolute;
            right: 20px;
            top: 15px;
            background-color: black;
            color: white;
            border-radius: 50%;
            padding: 4px;
        }
    </style>
    <div class="page-header">
        <h3 class="page-title">Lis Fich</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Fich</li>
            </ol>
        </nav>
    </div>

    <div class="row">



        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <tbody>
                        <form method="post" action="editertirage">
                            @csrf
                            <tr class="table-info filter-head" style="border: 2px solid blue;">

                                <td>
                                    <label for="dateFilter">komanse</label>
                                    <input style="height:10px;" type="date" class="form-control" value=""
                                        name="time_tirer" />
                                    <label for="dateFilter">Fini</label>

                                    <input style="height:10px;" type="date" class="form-control" value=""
                                        name="time_tirer" />
                                    <button style="height:15px;" type="submit"
                                        class="btn btn-gradient-primary me-2">Chache</button>
                                </td>


                            </tr>
                        </form>


                    </tbody>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> #fich </th>
                                <th> Bank </th>
                                <th> Tirage </th>
                                <th> Boul </th>
                                <th> Jwe</th>
                                <th> Genyen</th>
                                <th> Kakile</th>
                                <th> Dat</th>
                                <th> Aksyon</th>



                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ticket as $row)
                                <tr>
                                    <td> {{ $row->ticket_id }} </td>
                                    <td class="py-1">
                                        {{ $row->bank }}
                                    </td>
                                    @if ($row->tirage == 'New York Soir')
                                        <td style="color:blue;">
                                            {{ $row->tirage }}
                                        </td>
                                    @elseif($row->tirage == 'New York Matin')
                                        <td style="color: #06aafd">
                                            {{ $row->tirage }}
                                        </td>
                                    @elseif($row->tirage == 'Florida Matin')
                                        <td style="color: #58dc0b">
                                            {{ $row->tirage }}
                                        </td>
                                    @elseif($row->tirage == 'Florida Soir')
                                        <td style="color: #30be64">
                                            {{ $row->tirage }}
                                        </td>
                                    @else
                                        <td>
                                            {{ $row->tirage }}
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        <form action="boule-show" method="GET" class="form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $row->id }}">
                                            <button type="submit" onclick="togglePopup()" style="color: blue;"><i
                                                    class="mdi mdi-eye"></i></button>

                                        </form>



                                    </td>

                                    <td> {{ $row->amount }} HTG</td>


                                    @if ($row->winning == null)
                                        <td style="color: red">
                                            {{ 0 }} HTG

                                        </td>
                                    @else
                                        <td style="color: green;">
                                            {{ $row->winning }} HTG
                                        </td>
                                    @endif
                                    </td>
                                    @if ($row->is_calculated == 0)
                                        <td style="color: red;"> Non </td>
                                    @else
                                        <td style="color:#58dc0b;"> Wi </td>
                                    @endif


                                    <td> {{ $row->created_at }} </td>

                                    <td class="text-center"><a href="#"><i class="mdi mdi-delete"></i>
                                        </a></td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>



        </div>
        <div class="content">
            <div onclick="closePopup()" class="close-btn">
                ×
            </div>
            <h5>Boul ak miz</h5>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th> Boul </th>

                        <th>Prix</th>



                    </tr>
                </thead>
                <tbody>
                    <tr>


                        <td>03</td>
                        <td>25 HTG</td>


                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        // Function to show and hide the popup 
        function togglePopup() {
            $(".content").show();

        }

        function closePopup() {
            $(".content").hide();
        }

        $('.form').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                url: "{{ url('boule-show') }}",
                data: $(this).serialize(),
                type: 'get',
                success: function(response) {

                    if (response.status == 'true') {
                        const jsonData = response.boule['boule'];

                        // Parse JSON string into a JavaScript object
                        const jsonObject = JSON.parse(jsonData);

                        // Iterate over the properties of the object
                        Object.keys(jsonObject).forEach(function(key) {
                             console.log(`${key}:`);

                            // Check if the value of the property is an array
                            if (Array.isArray(jsonObject[key])) {
                                // Iterate over elements of the array
                                jsonObject[key].forEach(function(element, index) {
                                    console.log(`  ${index + 1}. ${element}`);
                                });
                            } else {
                                // Iterate over properties of the nested object
                                Object.keys(jsonObject[key]).forEach(function(subKey) {
                                   // console.log(
                                    //    `  ${subKey}: ${jsonObject[key][subKey]}`);
                                });
                            }
                        });

                    } else {

                        alert('li pa mache');
                    }


                },
                error: function(jqXHR, textStatus, errorThrown) {


                }
            });





        });
    </script>
@endsection
