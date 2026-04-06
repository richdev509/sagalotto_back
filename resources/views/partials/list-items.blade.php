@foreach($list as $lists)
<tr>
    <td>
        <strong style="color: #667eea;">{{ $lists->tirage_record->name ?? 'N/A' }}</strong>
    </td>
    <td>
        <i class="mdi mdi-calendar" style="color: #a0aec0; margin-right: 5px;"></i>
        {{ \Carbon\Carbon::parse($lists->created_)->format('d/m/Y') }}
    </td>
    <td>
        <div class="number-badges">
            <span class="badge-number badge-red" title="1er chiffre">
                {{ $lists->unchiffre }}
            </span>
            <span class="badge-number badge-blue" title="2ème chiffre">
                {{ $lists->premierchiffre }}
            </span>
            <span class="badge-number badge-purple" title="3ème chiffre">
                {{ $lists->secondchiffre }}
            </span>
            <span class="badge-number badge-green" title="4ème chiffre">
                {{ $lists->troisiemechiffre }}
            </span>
        </div>
    </td>
    <td>
        <span class="status-badge">
            <i class="mdi mdi-check-circle"></i> Tiraj 100%
        </span>
    </td>
    <td style="text-align: center;">
        <form action="{{ route('ajoutlo') }}" style="margin: 0;">
            <input type="hidden" name="id" value="{{ $lists->tirage_id }}" />
            <input type="hidden" name="dat_" value="{{ $lists->created_ }}" />
            <button type="submit" class="action-btn" title="Modifier">
                <i class="mdi mdi-pencil"></i>
            </button>
        </form>
    </td>
</tr>
@endforeach
