@extends('admin-layout')


@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <style>
            .input {
                font-size: 20px;
            }
        </style>
        <div class="card">

            <div class="card-body">
                <h4 class="card-title">Espas pou antre limit prix boul</h4>

                <form class="forms-sample"
                    @if (isset($record)) action="{{ route('updateprixboul') }}"  @else action="{{ route('saveprixlimit') }}" @endif
                    method="POST" style="font-weight: bold;">
                    @csrf
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label for="exampleInputUsername1">chwazi tiraj</label>
                        <select name="tirage[]" class="form-control selectpicker" data-live-search="true" multiple id="select" placeholder="Chwazi youn ou plizye tiraj"
                            style="height: 49px;border-color: #b0e914;  border-style: double; border-width: 1px;font-size:18px;color:#1469e9; @if (isset($record)) pointer-events:none; @endif"
                            required>

                            @if (isset($record))
                                <option value="{{ $record->tirage_record->id }}">{{ $record->tirage_record->name }}</option>
                            @else
                                <option disabled>Lis tiraj</option>

                                @foreach ($list as $liste)
                                    <option value="{{ $liste->id }}" style="height: 49px; color:#1469e9;" >{{ $liste->name }}</option>
                                @endforeach

                            @endif
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label for="exampleInputUsername1">chwazi Opsyon</label>
                        <select name="type" class="form-control" id="select" placeholder="List tiraj"
                            style="height: 49px;
                    border-color: #c9e914;
                    
                    border-style: double;
                    border-width: 1px;font-size:18px;color:#1469e9; @if (isset($record)) pointer-events:none; @endif"
                            required>


                            @foreach ($listjwet as $lis)
                                <option value="{{ $lis->id }}">{{ $lis->name }}</option>
                            @endforeach


                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputUsername1">Antre Boul la</label>
                        <input type="number" @if (isset($record)) value="{{ $record->unchiffre }}" @endif
                            name="chiffre" class="form-control input" id="1"
                            placeholder="loto Ex:34 , Maryaj Ex:3453"
                            style="border-color: #1469e9;
                    
                border-style: double;
                border-width: 1px;">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputUsername1">Antre montant limit</label>
                        <input type="number" @if (isset($record)) value="{{ $record->unchiffre }}" @endif
                            name="montant" maxlength="5" minlength="2" class="form-control input" id="1"
                            placeholder="300"
                            style="border-color: #1469e9;
                    
                border-style: double;
                border-width: 1px;">
                    </div>

                    @if (isset($record))
                        <button type="mise a" class="btn btn-gradient-primary me-2">Modifye</button>
                    @else
                        <button type="submit" class="btn btn-gradient-primary me-2">Ajoute</button>
                    @endif

                </form>
            </div>
        </div>
    </div>







@stop
