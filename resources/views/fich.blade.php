@extends('admin-layout')
@section('content')
    <style>
        .qt {
            height: 30px;

        }
    </style>
    <div class="page-header">
        <h3 class="page-title"> Paramet fich </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>

            </ol>
        </nav>
    </div>
    <div class="col-md-6 grid-margin stretch-card"
        style="margin: 2px;border-style:ridge; border-width:1px; border-color:rgb(209, 163, 252);">
        <div class="card">
            <form method="POST" action="fich_update">
                @csrf
                <div class="card-body">

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend ">
                                <span class="input-group-text qt">Kantite bolet pa fich:</span>
                            </div>
                            <input type="number" name="qt_bolet" value="{{ $fich->qt_bolet }}" class="form-control qt"
                                placeholder="Antre 1-100" maxlength="3" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend ">
                                <span class="input-group-text qt">Kantite maryaj pa fich:</span>
                            </div>
                            <input type="number" name="qt_maryaj" value="{{ $fich->qt_maryaj }}" class="form-control qt"
                                placeholder="Antre 1-1000" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend ">
                                <span class="input-group-text qt">Kantite loto3 pa fich:</span>
                            </div>
                            <input type="number" name="qt_loto3" value="{{ $fich->qt_loto3 }}" class="form-control qt"
                                placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend ">
                                <span class="input-group-text qt">Kantite loto4 pa fich:</span>
                            </div>
                            <input type="number" name="qt_loto4" value="{{ $fich->qt_loto4 }}" class="form-control qt"
                                placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend ">
                                <span class="input-group-text qt">Kantite loto5 pa fich:</span>
                            </div>
                            <input type="number" name="qt_loto5" value="{{ $fich->qt_loto5 }}" class="form-control qt"
                                placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="show_logo" class="form-check-input" checked> Afiche Logo </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" checked> Afiche Addres</label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" checked> Afiche Telefon</label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" checked>Afiche pied fich</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">


                        <button type="submit" class="btn btn-gradient-primary mb-2">Modifye</button>
                    </div>














                </div>
            </form>
        </div>
    </div>


@stop
