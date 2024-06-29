@foreach($list as $lists)
<tr>
    <td>{{$lists->tirage_record->name}}</td>
    <td>{{ \Carbon\Carbon::parse($lists->created_)->format('d-m-Y') }}</td>
    <td>
        <button type="button" class="btn btn-social-icon btn-youtube btn-rounded">{{$lists->unchiffre}}</button>  
        <button type="button" class="btn btn-social-icon btn-facebook btn-rounded">{{$lists->premierchiffre}}</button>
        <button type="button" class="btn btn-social-icon btn-dribbble btn-rounded">{{$lists->secondchiffre}}</button>
        <button type="button" class="btn btn-social-icon btn-linkedin btn-rounded">{{$lists->troisiemechiffre}}</button>
    </td>
    <td>Tiraj 100%</td>
    <td class="text-end">
        <form action="{{route('ajoutlo')}}">
            <input type="hidden" name="id" value="{{$lists->tirage_id}}" />
            <input type="hidden" name="dat_" value="{{$lists->created_}}" />
            <button type="submit"><i class="mdi mdi-table-edit"></i></button>
        </form>
    </td>
</tr>
@endforeach
