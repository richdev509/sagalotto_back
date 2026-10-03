@extends('admin-layout')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center bg-white">
                    <div>
                        <h4 class="mb-1">Reçu de facture #{{ $facture->id }}</h4>
                        <p class="mb-0 text-muted">Compagnie: {{ $compagnie->name ?? 'N/A' }}</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        @if(!empty($facture->facture_image))
                            <a href="{{ asset($facture->facture_image) }}" target="_blank" class="btn btn-sm btn-outline-secondary me-2">Ouvrir</a>
                            <a href="{{ asset($facture->facture_image) }}" download="facture-{{ $facture->id }}.png" class="btn btn-sm btn-primary">Télécharger</a>
                        @else
                            <span class="badge bg-warning text-dark">Pas d'image disponible</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4"><strong>Montant total</strong><div>${{ number_format($facture->amount ?? 0, 2) }}</div></div>
                        <div class="col-md-4"><strong>Montant payé</strong><div>${{ number_format($facture->paid_amount ?? 0, 2) }}</div></div>
                        <div class="col-md-4"><strong>Statut</strong>
                            <div>
                                @if($facture->is_paid)
                                    <span class="badge bg-success">Payée</span>
                                @else
                                    <span class="badge bg-warning text-dark">Non payée</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4"><strong>Date d'échéance</strong><div>{{ $facture->due_date ? \Carbon\Carbon::parse($facture->due_date)->format('j M Y') : 'N/A' }}</div></div>
                        <div class="col-md-4"><strong>Méthode de paiement</strong><div>{{ $facture->payment_method ?? '-' }}</div></div>
                        <div class="col-md-4"><strong>Payment ID</strong><div>{{ $facture->payment_id ?? '-' }}</div></div>
                    </div>
                    <div class="mb-4">
                        <strong>Description</strong>
                        <div class="border rounded p-3 bg-light" style="white-space: pre-line;">{{ $facture->description ?? '-' }}</div>
                    </div>
                    @if(!empty($facture->facture_image))
                        <div class="text-center">
                            <img src="{{ asset($facture->facture_image) }}" alt="Facture #{{ $facture->id }}" class="img-fluid rounded shadow-sm" style="max-width: 100%;" />
                        </div>
                    @else
                        <div class="alert alert-warning">Aucune image de facture n'est disponible pour cette facture.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop
