<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Courses with SRF</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Particular</th>
                    <th>Amount to Collect</th>
                    <th>Discount</th>
                    <th>Debit Memo</th>
                    <th>Payment</th>
                </tr>
                @foreach ($srfs as $srf)
                <?php 
                $totalpayment = 0;
                $totalpayment = $srf->payment; ?>
                <tr>
                    <td>{{$srf->subsidiary}}</td>
                    <td>{{$srf->amount}}</td>
                    <td>{{$srf->discount}}</td>
                    <td>{{$srf->debit_memo}}</td>
                    <td>{{$totalpayment}}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>