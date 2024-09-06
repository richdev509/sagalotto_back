@extends('superviseur.admin-layout')


@section('content')
    <style type="text/css">
        .content {
            position: fixed;
            margin-top: 100px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            height: 400px;
            text-align: center;
            background-color: white;
            border: 1px solid blueviolet;
            box-sizing: border-box;
            padding: 10px;
            z-index: 100;
            display: none;
            overflow-y: scroll;
            overflow-x: scroll;
            /*to hide popup initially*/
        }

        #search div {
            display: inline-block;
        }

        .row_head {
            border: 1px solid blueviolet;
            padding: 10px;

        }

        .content-clear td {
            font-size: 11px;
        }


        .close-btn {
            position: absolute;
            right: 20px;
            top: 15px;
            background-color: black;
            color: white;
            border-radius: 50%;
            padding: 4px;
            width: 26px;
            cursor: pointer;
        }

        h1 {
            text-align: center;
            font-family: Tahoma, Arial, sans-serif;
            color: #06D85F;
            margin: 80px 0;
        }

        .box {
            width: 40%;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.2);
            padding: 35px;
            border: 2px solid #fff;
            border-radius: 20px/50px;
            background-clip: padding-box;
            text-align: center;
        }

        .button {
            font-size: 1em;
            padding: 10px;
            color: gray;
            border-radius: 20px/50px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease-out;
        }


        .overlay {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            transition: opacity 500ms;
            visibility: hidden;
            opacity: 0;
        }

        .overlay:target {
            visibility: visible;
            opacity: 1;
        }

        .popup {
            margin: 200px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            width: 50%;
            position: relative;
            transition: all 5s ease-in-out;
        }

        .popup h2 {
            margin-top: 0;
            color: #333;
            font-family: Tahoma, Arial, sans-serif;
        }

        .popup .close {
            position: absolute;
            top: 20px;
            right: 30px;
            transition: all 200ms;
            font-size: 30px;
            font-weight: bold;
            text-decoration: none;
            color: #333;
        }

        .popup .close:hover {
            color: black;
        }

        .popup .content {
            max-height: auto;
            overflow: auto;
        }

        @media screen and (max-width: 700px) {
            .box {
                width: 70%;
            }

            .popup {
                width: 70%;
            }
        }
        
    </style>
    <div class="page-header">
        <h3 class="page-title">Rapo</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Rapo</li>
            </ol>
        </nav>
    </div>

    <div class="row">



        <div class="card">
            <div class="card-body">
                <div class="row_head">
                    <form method="get" action="rapport" id="search">
                        @csrf

                        <div class="col-12 col-md-5">
                            <div class="form-group local-forms">
                                <label for="dateFilter">komanse</label>
                                <input style="height:10px;margin-top: 10px;" type="date" class="form-control"
                                    name="date_debut" value="{{ old('date_debut') }}" required />

                                <label for="dateFilter" style="margin-top: 5px;">Fini</label>

                                <input style="height:10px;margin-top: 10px;" type="date" class="form-control"
                                    value="" name="date_fin" value="{{ old('date_fin') }}" required />
                            </div>
                        </div>

                        <div class="col-12  col-md-5">
                            <div class="form-group local-forms">
                                <label for="dateFilter">Bank</label>
                                <select class="form-control" name="bank"
                                    value="{{ old('bank') }}">
                                    <option>Tout</option>
                                    @foreach ($vendeur as $row)
                                        <option value="{{ $row->id }}">{{ $row->bank_name }}</option>
                                    @endforeach
                                </select>
                                <label for="dateFilter" style="margin-top: 5px;">Tiraj</label>
                                <select class="form-control" name="tirage"
                                    value="{{ old('bank') }}">
                                    <option>Tout</option>
                                    @foreach ($tirage as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="student-submit">
                                <button style="margin-top: 18px;background:rgb(0 94 254)" type="submit"
                                    class="btn primary" >Rapo</button>
                            </div>
                        </div>



                    </form>



                </div>
                <div class="table-responsive">
                   <form class="form">
                    <table class="table table-striped">
                        <thead>
                            <tr>




                            </tr>
                        </thead>
                        <tbody style="border: 1px solid #ac32cb;">
                            @if ($is_calculated == 1)
                                <tr>
                                    <td colspan="2" class="text-center">Rapo soti {{ $date_debut }} Rive
                                        {{ $date_fin }}</td>


                                </tr>


                                <tr>
                                    <td>Bank:</td>
                                    <td>{{ $bank }}</td>

                                </tr>
                                <tr>
                                    <td>Tiraj:</td>
                                    <td>{{ $tirage_ }}</td>

                                </tr>
                                <tr>
                                    <td>Fich Total:</td>
                                    <td>{{ $ticket_win + $ticket_lose }}</td>

                                </tr>
                                <tr>
                                    <td>Fich Genyen:</td>
                                    <td>{{ $ticket_win }}</td>

                                </tr>
                                <tr>
                                    <td>Fich Pedi:</td>
                                    <td>{{ $ticket_lose }}</td>

                                </tr>
                                
                                <tr>
                                    <td>Vant:</td>
                                    <td>{{ $vente }} HTG</td>

                                </tr>
                                <tr>
                                    <td>Pet:</td>
                                    <td>{{ $perte }} HTG</td>

                                </tr>
                                <tr>
                                    <td>Komisyon:</td>
                                    <td>{{ $commission }} HTG</td>

                                </tr>
                                <tr>
                                    <td>Balans:</td>
                                    <td>{{ $vente - ($perte + $commission) }} HTG</td>

                                </tr>
                                <!--
                                <button type="button" id="create_pdf"><i
                                class="mdi mdi-briefcase-download"></i>Telechaje</i></button> -->

                            @endif


                        </tbody>
                    </table>
                </form>
                </div>
            </div>



        </div>
        <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"
            integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            $(document).ready(function() {
                var form = $('.form'),
                    cache_width = form.width(),
                    a4 = [595.28, 841.89]; // for a4 size paper width and height  

                $('#create_pdf').on('click', function() {
                    $('body').scrollTop(0);
                    createPDF();
                });

                function createPDF() {
                    getCanvas().then(function(canvas) {
                        var
                            img = canvas.toDataURL("image/png"),
                            doc = new jsPDF({
                                unit: 'px',
                                format: 'a4'
                            });
                        doc.addImage(img, 'JPEG', 20, 20);
                        doc.save('techsolutionstuff.pdf');
                        form.width(cache_width);
                    });
                }

                function getCanvas() {
                    form.width((a4[0] * 1.33333) - 80).css('max-width', 'none');
                    return html2canvas(form, {
                        imageTimeout: 2000,
                        removeContainer: true
                    });
                }
            });
        </script>
    @endsection
