<style>
    .bold {
        font-weight: bold
    }
</style>
<h4>{{$department}}</h4>
@if($department == "College Department" or $department == "Senior High School") 
<h4>S.Y. {{$school_year}}-{{$school_year +1}} - {{$period}}</h4>
@endif
@if($department == "Junior High School" or $department == "Elementary" or $department == "Pre School") 
<h4>S.Y. {{$school_year}}-{{$school_year +1}}</h4>
@endif

@if(count($lists)>0)
<?php $total = 0;
$x = 0;
?>
<table width='100%' cellpadding='0' cellspacing='0' class="table table-striped table-condensed">
    @foreach($heads as $head)
    <?php $x = 0;?>
    <thead>
        <tr><td colspan="6"><h4>{{$head->level}}</h4></td></tr>
        <tr>
            <th style='border-bottom: 1px solid black'>  </th>
            <th style='border-bottom: 1px solid black'>ID No.</th>
            <th style='border-bottom: 1px solid black'>Name</th>
            <th style='border-bottom: 1px solid black' align='right'>Plan</th>
            <th style='border-bottom: 1px solid black; text-align: right'>Assessment</th>
            <th style='border-bottom: 1px solid black'>Transactions</th>
        </tr>
    </thead>
    <tbody>
            @foreach($lists as $list)
                <?php $total += $list->assessment; $x++; ?>
                <tr>
                    <td>{{$x}}.  </td>
                    <td align='left'>{{$list->idno}}</td>
                    <td>{{ucwords(strtolower($list->lastname))}}, {{ucwords(strtolower($list->firstname))}} {{ucwords(strtolower($list->middlename))}} {{ucwords(strtolower($list->extensionname))}}</td>
                    <td>{{$list->type_of_plan}}</td>
                    <td align='right'>{{number_format($list->assessment,2)}}</td>
<!--                    <td><strong>STILL IN DEVELOPMENT...</strong></td>-->
                    <?php $total_assessment = $list->assessment; ?>
<!--deposit-->      <?php 
                    if($department != "Senior High School" && $department != "College" ){
                        $period = "";
                    }; ?>
                    <?php $debit_memos = App\DebitMemo::where('idno', $list->idno)->where('school_year', $school_year)->where('period', $period)->get(); ?>
                        @if(count($debit_memos)>0)
                        @foreach($debit_memos as $debit_memo)                        
                    <td>
                        <?php $total_assessment = $total_assessment-$debit_memo->amount; ?>
                        <table border="1">
                            <tr width="50px"><td colspan="2">DEPOSIT</td>
                            <tr>
                                <td>Date</td><td>{{$debit_memo->transaction_date}}</td>
                            </tr>
                            <tr>
                                <td>DM#</td><td>{{$debit_memo->dm_no}}</td>
                            </tr>
                            <tr>
                                <td>Amount</td><td>{{number_format($debit_memo->amount,2)}}</td>
                            </tr>
                            <tr>
                                <td>Explanation</td><td>{{$debit_memo->explanation}}</td>
                            </tr>
                            <tr class="bold">
                                <td>Balance</td><td>{{number_format($total_assessment,2)}}</td>
                            </tr>
                        </table>
                    </td>
                        @endforeach
                        @endif
                    
<!--Payment-->
                    <?php
                    if($department != "Senior High School" && $department != "College" ){
                        $period = null;
                    };
                    $payments = \App\Payment::where('idno', $list->idno)->where('school_year', $school_year)->where('period',$period)->get(); ?>
                    @if(count($payments)>0)
                    @foreach($payments as $payment)
                    <?php $check_accounting = \App\Accounting::where('reference_id', $payment->reference_id)->where('category', '!=','Cash')->first(); ?>
                    <?php $check_ledger = \App\Ledger::where('id', $check_accounting->reference_number)->whereRaw('category_switch =7')->get(); ?>
                    @if(!$check_accounting->reference_number == null)
                    @if(count($check_ledger)==0)
                    <?php $check_sy_ledger = \App\Ledger::where('id',$check_accounting->reference_number)->where('school_year', $school_year)->where('period', $period)->get(); ?>
                    @if(count($check_sy_ledger)>0)
                    <?php $total_assessment = $total_assessment-($payment->check_amount+$payment->cash_amount+$payment->credit_card_amount+$payment->check_deposit_amount); ?>
                    <td>
                        <table border="1">
                            <tr width="50px"><td colspan="2">PAYMENT</td>
                            <tr>
                                <td>Date</td><td>{{$payment->transaction_date}}</td>
                            </tr>
                            <tr>
                                <td>OR#</td><td>{{$payment->receipt_no}}</td>
                            </tr>
                            <tr>
                                <td>Amount</td><td>{{number_format($payment->check_amount+$payment->cash_amount+$payment->credit_card_amount+$payment->check_deposit_amount,2)}}</td>
                            </tr>
                            <tr>
                                <td>Remarks<br>&nbsp;</td><td>@if($payment->is_reverse == 1)Cancel @endif</td>
                            </tr>
                            <tr class="bold">
                                <td>Balance</td><td>{{number_format($total_assessment,2)}}</td>
                            </tr>
                        </table>
                    </td>
                    @endif
                    @endif
                    @endif
                    @endforeach
                    @endif
                </tr>
            @endforeach
            <tr><td align="right" colspan="4">SUB TOTAL</td><td align="right"><strong>{{number_format($head->total,2)}}</strong></td><td></td></tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" style='border-top: 1px solid black' align="center">GRAND TOTAL</th>
            <td style='border-top: 1px solid black'></td>
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($total,2)}}</strong></td>
            <td style='border-top: 1px solid black'></td>
            <td style='border-top: 1px solid black'></td>
        </tr>
    </tfoot>
</table>
<br>
@endif