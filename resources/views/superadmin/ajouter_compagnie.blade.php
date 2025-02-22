@extends('superadmin.admin-layout')

@section('content')
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Nouvelle Compagnie</h4>
                <p class="card-description">Ajouter une nouvelle compagnie</p>
                <form class="forms-sample" action="{{ route('add_compagnie') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="InputNomcompagnie" class="col-sm-3 col-form-label">Nom Compagnie</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-office-building"></i></span>
                                <input type="text" class="form-control" name="compagnie" id="InputNomcompagnie"
                                    placeholder="Nom compagnie" minlength="4" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Inputadresse" class="col-sm-3 col-form-label">Adresse</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-map-marker"></i></span>
                                <input type="text" class="form-control" name="adress" id="Inputadresse"
                                    placeholder="Adresse" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="InputCity" class="col-sm-3 col-form-label">Ville</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-city"></i></span>
                                <input type="text" class="form-control" name="city" id="InputCity" placeholder="Ville"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="InputEmail1" class="col-sm-3 col-form-label">Email Compagnie</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                                <input type="email" class="form-control" name="email" id="InputEmail1"
                                    placeholder="Email compagnie" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="InputPhone" class="col-sm-3 col-form-label">Téléphone Compagnie</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-phone"></i></span>
                                <input type="text" class="form-control" name="phone" id="InputPhone"
                                    placeholder="Téléphone">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="InputFich" class="col-sm-3 col-form-label">Pied ticket</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-file-document"></i></span>
                                <input type="text" class="form-control" name="info" id="InputInfo"
                                    placeholder="Fich sa valab pou 90 jou, verifye fich ou avan ou deplase." value="Fich sa valab pou 90 jou, verifye fich ou avan ou deplase.">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="InputLogo" class="col-sm-3 col-form-label">Logo</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-image"></i></span>
                                <input type="file" class="form-control" name="logo" id="InputLogo"
                                    placeholder="Logo">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="plan" class="col-sm-3 col-form-label">Reference</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-bookmark"></i></span>
                                <select name="reference" class="form-control" id="plan">
                                   @foreach ($reference as $row)
                                      <option value="{{$row->id }}">{{ $row->name }}</option>
                                   @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="plan" class="col-sm-3 col-form-label">Plan</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                <select name="plan" class="form-control" id="plan">
                                    <option value="10">10</option>
                                    <option value="9">9</option>
                                    <option value="8">8</option>
                                    <option value="7">7</option>
                                    <option value="6">6</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-sm-3 col-form-label">Nom d'utilisateur</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-account"></i></span>
                                <input type="text" class="form-control" name="user" id="username"
                                    placeholder="Nom d'utilisateur">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label">Mot de passe</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-lock"></i></span>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Mot de passe">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-gradient-primary me-2">
                                <i class="mdi mdi-check-circle"></i> Soumettre
                            </button>
                            <a href="/wp-admin/admin" class="btn btn-light">
                                <i class="mdi mdi-close-circle"></i> Annuler
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection