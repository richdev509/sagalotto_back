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
        $plan = $data->plan;
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

            <!-- Latest Invoices Section -->
            <div class="card mb-4">
                <div class="p-4 bg-white card-header">
                    <h4 class="mb-0">Dernières factures</h4>
                </div>

                <div class="card-body">
                    @if($factures->isEmpty())
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle"></i> Aucune facture disponible
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Date d'échéance</th>
                                        <th>Montant</th>
                                        <th>Payé</th>
                                        <th>Statut</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($factures as $facture)
                                        <tr>
                                            <td><strong>#{{ $facture->id }}</strong></td>
                                            <td>{{ $facture->due_date ? \Carbon\Carbon::parse($facture->due_date)->format('j M Y') : 'N/A' }}</td>
                                            <td>${{ number_format($facture->amount, 2) }}</td>
                                            <td>${{ number_format($facture->paid_amount, 2) }}</td>
                                            <td>
                                                @if($facture->is_paid == 1)
                                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Payée</span>
                                                @else
                                                    <span class="badge bg-warning"><i class="bi bi-clock"></i> En attente</span>
                                                @endif
                                            </td>
                                            <td>{{ $facture->description ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('facture.receipt', $facture->id) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop