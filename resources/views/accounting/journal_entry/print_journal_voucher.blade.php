<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { size: 8.5inch 6.5inch; margin: 0px;  }
    </style>
    <style>
        .title-head{
            background-color: black;
            color:white;
            text-align: center;
            font-size:15pt;
            font-weight: bold;
        }
        #lower{
            font-size:10pt;
            border-top:none;
        }
        td{
            border-collapse: collapse;
            border: 1px solid black;
        }
        .tables, .tds, .ths {
            border-collapse: collapse;
            border: 1px solid black;
        }
        .entry{
            border-collapse: collapse;
            border: none;
            border-left: 1px solid black;
            border-right: 1px solid black;
            font-size:11pt;
        }
        body{
            margin:0px auto;
            padding:20px;
            padding-top:30px;
            font-family: Helvetica;
        }
    </style>
</head>
<body>
<?php $debit = $credit = 0;?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12" style="background-color:white;padding:10px;">
                <form class="form form-horizontal">
                    <center>
                        <div class="col-md-12">
                                        <b style="font-size:14pt">ASSUMPTION COLLEGE</b><br>
                                        <small style="margin-top: 0px;">San Lorenzo Drive, San Lorenzo Village Makati City</small><br/>
                            </br>
                        </div>
                    </center>
                    <br>
                    @if($journal_entry->is_reverse == 1)<span class="label label-danger" style="font-size:14pt;">CANCELLED</span> @endif
                    <table width="100%" cellpadding="2" class="tables" id="upper">
                        <tr>
                            <td class="title-head" colspan="5">JOURNAL VOUCHER</td>
                        </tr>
                        <tr>
                            <td width="50%" colspan="5">Date: {{date_format(date_create($journal_entry->transaction_date),"F d, Y")}}</td>
                        </tr>
                        <tr>
                            <td width="50%" colspan="5"><b>J.V. No. : {{str_pad($journal_entry->voucher_no,5,"0",STR_PAD_LEFT)}}</b></td>
                        </tr>
                        <tr>
                            <td colspan="5" rowspan="2" style="font-size:12pt">Particulars:<br>{{$journal_entry->particular}}</td> 
                        </tr>
                        <tr>
                        <tr>
                            <th width="10%">Acct No.</th>
                            <th width="30%">Account Title</th>
                            <th width="20%">Subsidiary</th>
                            <th width="15%">Debit</th>
                            <th width="15%">Credit</th>
                        </tr>
                        </r>
                        <tbody>
                            @foreach($accountings as $accounting)
                            <tr>
                                <td>{{$accounting->accounting_code}}</td>
                                <td>{{$accounting->category}}</td>
                                <td>{{$accounting->description}}</td>
                                <td align="right">@if($accounting->debit != 0){{number_format($accounting->debit,2)}} @endif</td>
                                <td align="right">@if($accounting->credit != 0){{number_format($accounting->credit,2)}} @endif</td>
                            </tr>
                            <?php
                            $debit += $accounting->debit;
                            $credit += $accounting->credit;
                            ?>
                            @endforeach
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align="right"><b>TOTAL</b></td>
                                <td align="right">{{number_format($debit,2)}}</td>
                                <td align="right">{{number_format($credit,2)}}</td>
                            </tr>
                        </tbody>

                    </table>
                </form>
                <div class="form-group">
                    <table id="lower" width="100%" cellpadding="1" class="tables" style="padding-top:-1px">
                        <tr>
                            <td width="29%">Prepared By:<br><br>&nbsp;</td>
                            <td width="29%">Checked By:<br><br>&nbsp;</td>
                            <td width="42%" colspan="3" rowspan="2">&nbsp;Received By:<br><br>
                                &nbsp;&nbsp;<u><span style="color:white;">&nbsp;&nbsp;WHITE COLOR HERE&nbsp;&nbsp;&nbsp;&nbsp;</span></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <u><span style="color:white;">JULY 23, 2019</span></u>
                                <br>&nbsp;&nbsp;&nbsp;&nbsp;Signature over printed name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span style="text-align:right">Date Received</span>
                        </td>
                        </tr>
                        <tr>
                            <td>Posted By:<br><br>&nbsp;</td>
                            <td>Approved By:<br><br>&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
