<div>
    <table class='table' border="1" width="100%">
        <thead>
            <tr>
                <th align='center' width='3%'>#</th>
                <th align='center' width='10%'>ID</th>
                <th align='center' width='50%'>Student Name</th>
                <th align='center' width='50%'>Course</th>
                <th align='center' width='70%'>Address</th>
                <th align='center' width='13%'>Birthdate</th>
                <th align='center' width='8%'>Gender</th>
                <th align='center' width='10%'>Tel No.</th>
            </tr>
        </thead>
        
        <tbody>
            <?php $count = 0;?>
            @foreach($students as $student)
            <?php $user = \App\User::where('idno', $student->idno)->first(); ?>
            <?php $info = \App\StudentInfo::where('idno', $student->idno)->first(); ?>
            <?php $count = $count +1?>
            <tr>
                <td>{{$count}}.</td>
                <td>{{$user->idno}}</td>
                <td>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</td>
                <td>{{strtoupper($info->program_name)}}</td>
                <td>{{$info->street}}, {{$info->barangay}},{{$info->municipality}},{{$info->province}}</td>
                <td align='center'>{{date('m/d/Y',strtotime($info->birthdate))}}</td>
                <td align='center'>F</td>
                <td align='center'>{{$info->tel_no}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>  
</div>  
    