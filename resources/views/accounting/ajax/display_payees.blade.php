@if(!$suppliers->isEmpty())

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Supplier</th>
                <th style="width:15%">Select</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
            <tr>
                <td>{{$supplier->supplier_name}}</td>
                <td><a href="javascript:void(0)" onclick="selectpayee('{{$supplier}}')">Select</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endif