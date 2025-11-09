@extends('superadmin.admin-layout')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Editer Compagnie</h4>
                <p class="card-description">Modifier les informations de la compagnie</p>
                <form class="forms-sample" action="{{route('update_compagnie')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{$data->id}}" name="id"/>

                    <!-- Nom Compagnie -->
                    <div class="form-group">
                        <label for="InputNomcompagnie"><i class="mdi mdi-office-building"></i> Nom Compagnie</label>
                        <input type="text" class="form-control" value="{{ $data->name }}" name="compagnie" id="InputNomcompagnie" placeholder="Nom compagnie" minlength="4" required>
                    </div>

                    <!-- Propriétaire -->
                    <div class="form-group">
                        <label for="InputProprietaire"><i class="mdi mdi-account-badge"></i> Propriétaire</label>
                        <input type="text" class="form-control" value="{{ $data->proprietaire ?? '' }}" name="proprietaire" id="InputProprietaire" placeholder="Nom du propriétaire">
                    </div>

                    <!-- Adresse -->
                    <div class="form-group">
                        <label for="Inputadresse"><i class="mdi mdi-map-marker"></i> Adresse</label>
                        <input type="text" class="form-control" value="{{ $data->address }}" name="adresse" id="Inputadresse" placeholder="Adresse" required>
                    </div>

                    <!-- City -->
                    <div class="form-group">
                        <label for="InputCity"><i class="mdi mdi-city"></i> Ville</label>
                        <input type="text" class="form-control" value="{{ $data->city }}" name="city" id="InputCity" placeholder="Ville" required>
                    </div>

                    <!-- Email Compagnie -->
                    <div class="form-group">
                        <label for="InputEmail1"><i class="mdi mdi-email"></i> Email Compagnie</label>
                        <input type="text" class="form-control" value="{{ $data->email }}" name="email" id="InputEmail1" placeholder="Email compagnie" required>
                    </div>

                    <!-- Phone Compagnie -->
                    <div class="form-group">
                        <label for="InputPhone"><i class="mdi mdi-phone"></i> Téléphone Compagnie</label>
                        <input type="text" class="form-control" value="{{ $data->phone }}" name="phone" id="InputPhone" placeholder="Téléphone">
                    </div>

                    <!-- WhatsApp -->
                    <div class="form-group">
                        <label for="InputWhatsapp"><i class="mdi mdi-whatsapp"></i> WhatsApp</label>
                        <input type="text" class="form-control" value="{{ $data->whatsapp ?? '' }}" name="whatsapp" id="InputWhatsapp" placeholder="+50940404040">
                    </div>

                    <!-- Plan -->
                    <div class="form-group">
                        <label for="plan"><i class="mdi mdi-format-list-checks"></i> Plan</label>
                        <select name="plan" class="form-control">
                            <option value="{{$data->plan}}">{{$data->plan}}</option>
                            <option value="10">10</option>
                            <option value="9">9</option>
                            <option value="8">8</option>
                            <option value="7">7</option>
                            <option value="6">6</option>
                        </select>
                    </div>

                    <!-- Pied fich -->
                    <div class="form-group">
                        <label for="info"><i class="mdi mdi-information"></i> Pied fich</label>
                        <input type="text" class="form-control" name="info" value="{{$data->info}}">
                    </div>

                    <!-- Logo -->
                    <div class="form-group">
                        <label for="logo"><i class="mdi mdi-image"></i>Logo</label>
                        <input type="file" class="form-control" name="logo">
                    </div>

                    <!-- Date abonnement -->
                    <div class="form-group">
                        <label for="InputNomcompagnie"><i class="mdi mdi-calendar"></i> Date abonnement</label>
                        <input type="date" class="form-control" value="{{ $data->dateplan }}" name="datePlan" id="InputNomcompagnie">
                    </div>
                    <!--password-->
                    <div class="form-group">
                        <label for="InputPassword1"><i class="mdi mdi-lock"></i>Reset Mot de passe</label>
                        <input type="password" class="form-control" name="password" id="InputPassword1" placeholder="Mot de passe" minlength="6">
                    </div>
                    <!-- blocked compagnie -->
                    <div class="form-group"></div>
                        <label for="InputBlocked"><i class="mdi mdi-block"></i> Bloquer Compagnie</label>
                        <select name="is_block" class="form-control">
                            <option value="0" {{ $data->is_block == 0 ? 'selected' : '' }}>Non</option>
                            <option value="1" {{ $data->is_block == 1 ? 'selected' : '' }}>Oui</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-primary me-2">
                            <i class="mdi mdi-content-save"></i> Mise à jour
                        </button>
                        <button type="reset" class="btn btn-light">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop