@extends('superadmin.admin-layout')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-warning text-white me-2">
                <i class="mdi mdi-clock-alert-outline"></i>
            </span> Toutes les compagnies expirées les plus anciennes
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/wp-admin/admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Compagnies expirées anciennes</li>
            </ul>
        </nav>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 py-2" style="background:#e67e22;color:#fff;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="mdi mdi-clock-alert-outline mdi-18px"></i>
                        <span class="fw-semibold">Liste complète — Compagnies expirées ({{ count($companies) }})</span>
                    </div>
                    <a href="{{ url('/wp-admin/admin') }}" class="btn btn-sm btn-light" style="color:#e67e22;">
                        <i class="mdi mdi-arrow-left"></i> Retour
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Code</th>
                                    <th>Date expiration</th>
                                    <th>WhatsApp</th>
                                    <th>Plan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($companies as $index => $c)
                                    @php
                                        $daysAgo = \Carbon\Carbon::parse($c->dateexpiration)->diffInDays(\Carbon\Carbon::today());
                                        $badgeClass = $daysAgo >= 30 ? 'bg-danger' : 'bg-warning text-dark';
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $c->fullname ?? $c->name }}</td>
                                        <td><span class="badge bg-secondary">{{ $c->code }}</span></td>
                                        <td>
                                            <span class="badge {{ $badgeClass }}">{{ $c->dateexpiration }}</span>
                                            <small class="text-muted d-block">expirée il y a {{ $daysAgo }}j</small>
                                        </td>
                                        <td>{{ $c->whatsapp ?? 'N/A' }}</td>
                                        <td><span class="badge bg-primary">{{ $c->plan }}</span></td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary btn-generer-facture"
                                                data-company-id="{{ $c->id }}"
                                                data-company-name="{{ $c->fullname ?? $c->name }}"
                                                data-date="{{ $c->dateexpiration }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#genFactureModal">
                                                <i class="mdi mdi-file-document-outline"></i> Facture
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Aucune compagnie expirée trouvée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Générer Facture Modal --}}
    <div class="modal fade" id="genFactureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header" style="background:#0e75b3;color:#fff;">
                    <h5 class="modal-title"><i class="mdi mdi-file-document-outline"></i> Générer une facture</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('genererfacture') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Compagnie</label>
                            <input type="text" id="gf-company-name" class="form-control" readonly />
                            <input type="hidden" name="company" id="gf-company-id" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date (expiration)</label>
                            <input type="date" name="date" id="gf-date" class="form-control" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Générer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-generer-facture').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.getElementById('gf-company-id').value = this.dataset.companyId;
                    document.getElementById('gf-company-name').value = this.dataset.companyName;
                    document.getElementById('gf-date').value = this.dataset.date;
                });
            });
        });
    </script>
@endsection
