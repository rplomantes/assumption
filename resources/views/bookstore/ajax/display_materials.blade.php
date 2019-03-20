<div class="container-fluid">
    <div class="col-md-12">
        <h3>{{$title}}</h3>
        <div class="col-md-offset-10">
            <h4><a role="button" onclick="newFee()"><strong>New Fee</strong></a></h4>
        </div>
        <table class="table table-condensed">
            <tr>
                @if($type == 5)
                <th>Particular</th>
                <th>Size</th>
                <th>Amount</th>
                <th></th>
                <th></th>
                @elseif($type == 1)
                <th>Category</th>
                <th>Subsidiary</th>
                <th>Amount</th>
                <th></th>
                <th></th>
                @elseif($type == 2)
                <th>Category</th>
                <th>Particular</th>
                <th></th>
                <th></th>
                @endif
            </tr>
            @foreach($fees as $fee)
            <tr>
                @if($type == 5)
                <td>{{$fee->particular}}</td>
                <td>{{$fee->size}}</td>
                <td>{{number_format($fee->amount,2)}}</td>
                <td>
                    <a role="button" onclick="updateFee({{$fee->id}})">Update</a>
                </td>
                <td>
                    <a role="button" onclick="removeFee({{$fee->id}})">Remove</a>
                </td>
                @elseif($type == 1)
                <td>@if($fee->category == "Books") Books/Per Item @else {{$fee->category}} @endif</td>
                <td>{{$fee->subsidiary}}</td>
                <td>{{number_format($fee->amount,2)}}</td>
                <td>
                    <a role="button" onclick="updateFee({{$fee->id}})">Update</a>
                </td>
                <td>
                    <a role="button" onclick="removeFee({{$fee->id}})">Remove</a>
                </td>
                @elseif($type == 2)
                <td>{{$fee->category}}</td>
                <td>{{$fee->particular}}</td>
                <td>
                    <a role="button" onclick="updateFee({{$fee->id}})">Update</a>
                </td>
                <td>
                    <a role="button" onclick="removeFee({{$fee->id}})">Remove</a>
                </td>
                @endif
            </tr>
            @endforeach
        </table>
    </div>
</div>