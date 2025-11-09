@extends('admin-layout')
@section('content')
        <?php 
                             if ($data->plan == 10) {
        if ($vendeur >= 0 && $vendeur <10) {
            $plan = 10;
        } elseif ($vendeur >= 10 && $vendeur < 20) {
            $plan = 9;
        } elseif ($vendeur >= 20 && $vendeur <30 ) {
            $plan = 8;
        } elseif ($vendeur >= 30 && $vendeur <50) {
            $plan = 7;
        } elseif ($vendeur >= 50 && $vendeur < 10000) {
            $plan = 6;
        }
    } else {
        $plan = $compagnie->plan;
    }
    
                            ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($data->name) }}&size=96"
                            class="rounded-circle border" alt="Profile" width="96" height="96">
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="fw-bold mb-1">{{$data->name}}</h4>
                        <p class="mb-1"><i class="bi bi-building"></i> <strong>Compagnie:</strong> {{$data->name}}</p>
                        <p class="mb-1"><i class="bi bi-geo-alt"></i> <strong>Adresse:</strong> <span
                                id="address-text">{{$data->address}}, {{$data->city}}</span></p>
                        <p class="mb-1"><i class="bi bi-telephone"></i> <strong>Phone:</strong> <span
                                id="phone-text">{{$data->phone}}</span></p>
                        <p class="mb-1"><i class="bi bi-envelope"></i> <strong>Email:</strong> {{$data->email}}</p>
                    </div>
                    <div class="ms-4">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#editProfileModal"><i class="bi bi-pencil"></i> Modifier</button>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="plan">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="editProfileModalLabel">Modifier les informations</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="{{$data->address}}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{$data->phone}}" required>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ...existing code... -->
            <div class="card mb-4">
                <div class="p-4 bg-white card-header">
                    <h4 class="mb-0">Plan ki aktive</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-8 col-lg-6 col-md-12 col-12">
                            <div class="mb-2">
                                <p class="text-muted mb-0">Pos aktif</p>
                                <p>Nombre pos: <span class="fw-bold">{{ $vendeur}}</span></p>
                                <h5 class="mt-2 mb-3 fw-bold">Demare le -
                                    {{ \Carbon\Carbon::parse($data->dateplan)->format('j M Y') }} </h5>
                                <p>Fini {{ \Carbon\Carbon::parse($data->dateexpiration)->format('j M Y') }},<span
                                        class="text-danger"> NB: apre plan fini sistem nan ap bow 5 jou delai apresa lap
                                        blokew otomatik</span></p>
                                <p>
                                    Wap gen pou peye nan lel expire:
                                    <span class="text-primary">${{$plan * $vendeur}} USD </span>
                                    <span class="text-dark fw-bold">
                                        {{ \Carbon\Carbon::parse($data->dateexpiration)->format('j M Y') }} </span>
                                </p>
                                <p>
                                    Ou rete <span class="fw-bold">{{ $nombre }}</span> Jour(s)
                                <div class="progress mt-2" style="height: 8px;">
                                    <div class="progress-bar bg-gradient-danger" role="progressbar"
                                        style="width: {{ max(0, min(100, round($nombre * 100 / 30))) }}%"
                                        aria-valuenow="{{ $nombre }}" aria-valuemin="0" aria-valuemax="30"></div>
                                </div>
                                </p>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-12 col-12">
                            <div><small class="text-muted">Peye pa Mwa</small>
                                <h1 class="fw-bold text-primary">$ {{$plan * $vendeur}} USD</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop