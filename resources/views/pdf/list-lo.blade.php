<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Liste des Numéros Gagnants</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #2d3748;
            padding: 15px;
            background: #ffffff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 20px;
            background: #667eea;
            border-radius: 5px;
            color: white;
        }
        
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .header h2 {
            font-size: 13px;
            font-weight: normal;
        }
        
        .info-section {
            margin-bottom: 20px;
            background: white;
            padding: 15px;15px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 3px;
            border-left: 3px solid #667eea;
        }
        
        .info-section p {
            margin: 4px 0;
            font-size: 10px;
            line-height: 1.4
        .info-section strong {
            color: #667eea;
            font-weight: bold;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 5px 0;
            font-weight: bold;
            color: #667eea;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white#667eea;
            color: white;
        }
        
        table thead th {
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase
        
        table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        table tbody tr:hover {
            background-color: #edf2f7;
        }
        
        table tbody td {
            padding: 10px;
            font-size: 10px;
            color: #46px;
            font-size: 9px;
            color: #4a5568;
        }
        
        .numero-container {
            display: inline-block;
        }
        
        .numero-badge {
            display: inline-block;
            padding: 3px 6px;
            margin: 1px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
            font-size: 9px;
            min-width: 24px;
            text-align: center;
        }
        
        .badge-1 {
            background: #f5576c;
        }
        
        .badge-2 {
            background: #4facfe;
        }
        
        .badge-3 {
            background: #43e97b;
        }
        
        .badge-4 {
            background: #fa709a
        .status-ok {
            color: #38a169;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #718096;
            border-top: 2px solid #e2e8f0;
            padding-top: 15px;
        }
        
        .footer p {
            margin: 4px 0;
        }
        
        .no-data {
            text-align: center;
            padding: 50px 20px;
            color: #a0aec0;
            font-style: italic;
            font-size: 14px;
        }
        
        .index-cell {
            font-weight: bold;
            color: #667eea;
        }
        
        .tirage-cell {
            font-weight: bold;
            color: #2d3748;
        }
        
        @page {
            margin: 10mm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LISTE DES NUMÉROS GAGNANTS</h1>
        <h2>{{ $compagnie->name ?? 'SagaLotto' }}</h2>
    </div>
    
    <div class="info-section">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Période:</div>
                <div class="info-value">
                    @if($dateDebut && $dateFin)
                        Du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
                    @else
                        Tous les tirages
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Date d'export:</div>
                <div class="info-value">{{ $dateExport }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nombre total:</div>
                <div class="info-value">{{ $list->count() }} tirage(s)@if($list->count() >= 500) <strong style="color: #e53e3e;">(Maximum atteint)</strong>@endif</div>
            </div>
        </div>
    </div>
    
    @if($list->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 6%">N°</th>
                    <th style="width: 24%">Tirage</th>
                    <th style="width: 15%">Date</th>
                    <th style="width: 45%">Numéros Gagnants</th>
                    <th style="width: 10%; text-align: center;">Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $index => $item)
                <tr>
                    <td class="index-cell">{{ $index + 1 }}</td>
                    <td class="tirage-cell">{{ $item->tirage_record->name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_)->format('d/m/Y') }}</td>
                    <td>
                        <div class="numero-container">
                            <span class="numero-badge badge-1">{{ $item->unchiffre }}</span>
                            <span class="numero-badge badge-2">{{ $item->premierchiffre }}</span>
                            <span class="numero-badge badge-3">{{ $item->secondchiffre }}</span>
                            <span class="numero-badge badge-4">{{ $item->troisiemechiffre }}</span>
                        </div>
                    </td>
                    <td style="text-align: center">
                        @if($item->etat == 'true')
                            <span class="status-ok">✓</span>
                        @else
                            <span style="color: #e53e3e;">✗</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>⚠ Aucun numéro gagnant trouvé pour la période sélectionnée.</p>
        </div>
    @endif
    
    <div class="footer">
        <p>Document généré automatiquement le {{ $dateExport }}</p>
        <p>© {{ date('Y') }} {{ $compagnie->name ?? 'SagaLotto' }} - Tous droits réservés</p>
    </div>
</body>
</html>
