@extends('admin-layout')
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Pwemye Lo</h4>
            <p class="card-description">Ajiste pri pwemye lo</p>
            
            <form class="forms-sample" action="{{ route('updateprilo') }}" method="POST">
                @csrf
                <label>Branch</label>
                <select name="branch" id="branch"
                    style="border-style: solid;     border-color: royalblue;  border-width: thin; font-size: x-large;"
                    class="form-control" aria-label="Montant (ajoute montant a mise a jour)">
                    <option value="" disabled selected>Chwazi branch</option>
                    @foreach ($branch as $row)
                        <option value="{{ $row->id}}">{{ $row->name }}</option>
                    @endforeach
                </select>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> pri ki la se: X fwa</span>
                        </div>
                        <div class="input-group-prepend">

                            <span class="input-group-text" id='prix_label'>
                               null
                            </span>
                        </div>
                        <select name="montant"
                            style="border-style: solid;     border-color: royalblue;  border-width: thin;  color: #d10a52; font-size: x-large;"
                            class="form-control" aria-label="Montant (ajoute montant a mise a jour)">


                            <option id="prix">null</option>
                            <option value="60">60</option>
                            <option value="50">50</option>
                            <option value="55">55</option>
                            <option value="65">65</option>
                        </select>
                    </div>
                    <div class="input-group">

                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" value='1' name='tirage_auto'
                                    @if ($service->autoTirage) checked @endif> Pemet sistem nan ajoute lo gayan pou
                                ou </label>
                        </div>
                    </div>
                        <button type="submit" class="btn primary" style="background:rgb(0 94 254)">Mete a jou</button>
                </div>

            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {

            document.getElementById('branch').addEventListener('change', function() {
                let current_id = this.value;
                // document.getElementById('selectedDate').innerText = 'Selected date: ' + selectedDate;

                $.ajax({
                    url: 'getByBranch',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: current_id
                    },
                    success: function(response) {
                        if(response.status=='true'){
                            document.getElementById('prix_label').innerText = response.data.prix;
                            document.getElementById('prix').innerText = response.data.prix;



                        }

                    }
                });
            });

        });
    </script>





@stop
