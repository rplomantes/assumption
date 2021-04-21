<head>
    <style>
        td{
            border-collapse: collapse;
            border: 1px solid black;
        }
        th{
            border-collapse: collapse;
            border: 1px solid black;
            text-align:center
        }
        .tables, .tds, .ths {
            border-collapse: collapse;
            border: 1px solid black;
            font-size:10pt;
        }
        body{
            margin:0px auto;
            padding:11px;
        }
        small{
            font-size:9pt;
        }
    </style>
</head>
<?php $grandtotal = 0;?>
<body>
    <div class="container-fluid">
        <center>
            <div class="col-md-12">
                <b style="font-size:14pt">ASSUMPTION COLLEGE</b><br>
                <small style="margin-top: 0px;">San Lorenzo Drive, San Lorenzo Village Makati City</small><br/>
                </br>
            </div>
        </center>
        <br>
        
        <table width="100%" class="tables" cellpadding="2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Accounting Code</th>
                    <th>Accounting Name</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $account)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$account->accounting_code}}</td>
                    <td>{{$account->accounting_name}}</td>
                    <td>{{$account->category}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <br><br>
    <small style="font-size:7pt;float:right" >{{date("Y-m-d H:i:s")}}</small>
</body>
