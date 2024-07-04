@extends('admin-layout')
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Pwemye Lo</h4>
            <p class="card-description">Ajiste pri pwemye lo</p>
            <form class="forms-sample" action="{{ route('updateprilo') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> pri ki la se : X fwa</span>
                        </div>
                        <div class="input-group-prepend">

                            <span class="input-group-text">
                                @if (isset($data) && $data->prix > 0)
                                    {{ $data->prix }}
                                @else
                                    0
                                @endif
                            </span>
                        </div>
                        <select name="montant"
                            style="border-style: solid;     border-color: royalblue;  border-width: thin;  color: #d10a52; font-size: x-large;"
                            class="form-control" aria-label="Montant (ajoute montant a mise a jour)">
                           

                            <option >{{$data->prix}}</option>
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
                                    @if ($service->autoTirage=='1') checked @endif> Pemet sistem nan ajoute lo gayan pou
                                ou </label>
                        </div>
                    </div>
                    @if (isset($data) && $data->id)
                        <button type="submit" class="btn primary" style="background:rgb(0 94 254)">Mete a jou</button>
                    @endif
                </div>

            </form>
        </div>
    </div>






@stop
