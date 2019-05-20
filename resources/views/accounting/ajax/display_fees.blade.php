<div class="container-fluid">
    <div class="col-md-12">
        <h3>{{$title}}</h3>
        <div class="col-md-offset-10">
            <h4><a role="button" onclick="newFee()"><strong>New Fee</strong></a></h4>
        </div>
        <table class="table table-condensed">
            <tr>
                @if($type == 9)
                <th>Tuition Fee</th>
                <th>Amount</th>
                <th></th>
                @else
                <th>Category</th>
                <th>Subsidiary</th>
                <th>Amount</th>
                <th></th>
                <th></th>
                @endif
            </tr>
            @foreach($fees as $fee)
            <tr>
                @if($type == 9)
                <td>Tuition Fee</td>
                <td>{{number_format($fee->per_unit,2)}} per unit</td>
                <td>
                    <a role="button" onclick="updateFee({{$fee->id}})">Update</a>
                </td>
                @else
                <td>{{$fee->category}}</td>
                <td>{{$fee->subsidiary}}</td>
                <td>{{number_format($fee->amount,2)}}</td>
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