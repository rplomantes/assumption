<table class="table table-condensed">
    @if(count($get_discount_collections)>0)
    @foreach($get_discount_collections as $discount_collection)
    <tr><td>{{$discount_collection->discount_type}}</td><td>{{$discount_collection->subsidiary}}</td>
        <td align="center">
            @if($discount_collection->subsidiary == "Family Council")
            Php {{$discount_collection->discount_amount}}
            @else
                @if($discount_collection->discount_type == "Benefit Discount")
                100%
                @else
                50%
                @endif
            @endif
        </td>
        <td><a href="javascript:void(0)" onclick="remove_discount_collection('{{$discount_collection->id}}','{{$idno}}')">Remove</a></td></tr>
    @endforeach
    @else
    <tr><td><i>No Other Discounts Added.</i></td></tr>
    @endif
</table>