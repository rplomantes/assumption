<?php

function getCount($getlevel,$getsection,$getstrand,$schoolyear, $period){
 if($getlevel == "Grade 11" || $getlevel == "Grade 12"){
     $count = \App\BedLevel::selectRaw("level,section,strand,count(*) as count")
             ->whereRaw("level='$getlevel' AND section = '$getsection' AND strand = '$getstrand' AND status = '3' AND school_year = '$schoolyear' AND period = '$period'")
             ->groupBy('level','section','strand')->first();
 } else{
     $count = \App\BedLevel::selectRaw("level,section,count(*) as count")
             ->whereRaw("level='$getlevel' AND section = '$getsection' AND status = '3' AND school_year = '$schoolyear'")
             ->groupBy('level','section')->first();
     }
  if(count($count)>0){
         return $count->count;
     } else {
         return "0";
     }   
 
}

function getWithdrawn($schoolyear){
     $count = \App\BedLevel::selectRaw("count(*) as count")
             ->whereRaw("status = '4' AND school_year = '$schoolyear'")->first();

  if(count($count)>0){
         return $count->count;
     } else {
         return "0";
     }   
 
}

function getTotal($getlevel,$getstrand,$schoolyear,$period){
    
    if($getlevel == "Grade 11" || $getlevel == "Grade 12"){
     $count = \App\BedLevel::selectRaw("level,strand,count(*) as count")
             ->whereRaw("level='$getlevel'  AND strand = '$getstrand' AND status = '3' AND school_year = '$schoolyear' AND period = '$period'")
             ->groupBy('level','strand')->first();
 } else{
     $count = \App\BedLevel::selectRaw("level,count(*) as count")
             ->whereRaw("level='$getlevel' AND status = '3' AND school_year = '$schoolyear'")
             ->groupBy('level')->first();
     }
  if(count($count)>0){
         return $count->count;
     } else {
         return "0";
     } 
    
}
    //total of preschool
    $prekindergrandtotal = 0;
    $kindergrandtotal = 0;
    
    //total of elementary
    $elemtotal1 = 0; 
    $elemtotal2 = 0; 
    $elemtotal3 = 0;
    $elemtotal4 = 0;
    $elemtotal5 = 0;
    $elemtotal6 = 0;

    
    //junior total
    $elemtotal7 = 0;
    $elemtotal8 = 0;  
    $elemtotal9 = 0;
    $elemtotal10 = 0;
    //shs total
    $gr11total1_1st = 0;
    $gr11total2_1st = 0;
    $gr11total3_1st = 0;
    $gr11total4_1st = 0;
    $gr11total5_1st = 0;
    $gr12total1_1st = 0;
    $gr12total2_1st = 0;
    $gr12total3_1st = 0;
    $gr12total4_1st = 0;
    $gr12total5_1st = 0;
    
    $gr11total1_2nd = 0;
    $gr11total2_2nd = 0;
    $gr11total3_2nd = 0;
    $gr11total4_2nd = 0;
    $gr11total5_2nd = 0;
    $gr12total1_2nd = 0;
    $gr12total2_2nd = 0;
    $gr12total3_2nd = 0;
    $gr12total4_2nd = 0;
    $gr12total5_2nd = 0;
    
    $gr11total6_1st = 0;
    $gr11total7_1st = 0;
    $gr12total6_1st = 0;
    $gr12total7_1st = 0;
    $gr11total6_2nd = 0;
    $gr11total7_2nd = 0;
    $gr12total6_2nd = 0;
    $gr12total7_2nd = 0;

    
    ?>
     <h3>Pre School</h3>
     <table id="example1" class="table table-responsive table-striped">
         <thead>
              <tr><th>GRADE LEVEL</th><th>A</th><th>B</th><th>C</th><th>D</th><th>E</th><th>F</th><th>Total</th></tr>
         </thead>
          <tr><td>Pre Kinder </td><td>{{getCount('Pre-Kinder','A','',$school_year, "")}}</td>   
             <td>{{getCount('Pre-Kinder','B','',$school_year, "")}}</td>
             <td>{{getCount('Pre-Kinder','C','',$school_year, "")}}</td>
             <td>{{getCount('Pre-Kinder','D','',$school_year, "")}}</td>
             <td>{{getCount('Pre-Kinder','E','',$school_year, "")}}</td>
             <td>{{getCount('Pre-Kinder','F','',$school_year, "")}}</td>
             <td>{{$prekindergrandtotal = $prekindergrandtotal + gettotal('Pre-Kinder','',$school_year, "")}}</td>
             <td></td>
            </tr>
            <tr><td>Kinder </td><td>{{getCount('Kinder','A','',$school_year, "")}}</td>
             <td>{{getCount('Kinder','B','',$school_year, "")}}</td>
             <td>{{getCount('Kinder','C','',$school_year, "")}}</td>
             <td>{{getCount('Kinder','D','',$school_year, "")}}</td>
             <td>{{getCount('Kinder','E','',$school_year, "")}}</td>
             <td>{{getCount('Kinder','F','',$school_year, "")}}</td>
             <td>{{$kindergrandtotal = $kindergrandtotal + gettotal('Kinder','',$school_year, "")}}</td>
            </tr>
            <tr>
             <td><strong>Total</strong></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td><strong>{{$preschooltotal = $prekindergrandtotal + $kindergrandtotal}}</strong></td>
             <td></td>
         <tbody>
            @if(count($kinder)>0)
            <tr>
            @foreach($kinder as $count)
            <td>{{$count->count}}</td>
            @endforeach
            <tr>
            @endif
         </tbody>
     </table>
     
     <h3>Grade School</h3>    
     <table id="example2" class="table table-responsive table-striped">
         <thead>
             <tr><th>GRADE LEVEL</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>Total</th></tr>
         </thead>
         <tbody>
            <tr>
             <td>Grade 1 </td>
             <td>{{getCount('Grade 1','1','',$school_year, "")}}</td>
             <td>{{getCount('Grade 1','2','',$school_year, "")}}</td>
             <td>{{getCount('Grade 1','3','',$school_year, "")}}</td>
             <td>{{getCount('Grade 1','4','',$school_year, "")}}</td>
             <td>{{getCount('Grade 1','5','',$school_year, "")}}</td>
             <td>{{getCount('Grade 1','6','',$school_year, "")}}</td>
             <td>{{$elemtotal1 = $elemtotal1 + gettotal('Grade 1','',$school_year, "")}}</td>
            </tr>
             <tr>
             <td>Grade 2 </td>
             <td>{{getCount('Grade 2','1','',$school_year, "")}}</td>
             <td>{{getCount('Grade 2','2','',$school_year, "")}}</td>
             <td>{{getCount('Grade 2','3','',$school_year, "")}}</td>
             <td>{{getCount('Grade 2','4','',$school_year, "")}}</td>
             <td>{{getCount('Grade 2','5','',$school_year, "")}}</td>
             <td>{{getCount('Grade 2','6','',$school_year, "")}}</td>
             <td>{{$elemtotal2 = $elemtotal2 + gettotal('Grade 2','',$school_year, "")}}</td>
            </tr>
             <tr>
             <td>Grade 3 </td>
             <td>{{getCount('Grade 3','1','',$school_year, "")}}</td>
             <td>{{getCount('Grade 3','2','',$school_year, "")}}</td>
             <td>{{getCount('Grade 3','3','',$school_year, "")}}</td>
             <td>{{getCount('Grade 3','4','',$school_year, "")}}</td>
             <td>{{getCount('Grade 3','5','',$school_year, "")}}</td>
             <td>{{getCount('Grade 3','6','',$school_year, "")}}</td>
             <td>{{$elemtotal3 = $elemtotal3 + gettotal('Grade 3','',$school_year, "")}}</td>
            </tr>
             <tr>
             <td>Grade 4 </td>
             <td>{{getCount('Grade 4','1','',$school_year, "")}}</td>
             <td>{{getCount('Grade 4','2','',$school_year, "")}}</td>
             <td>{{getCount('Grade 4','3','',$school_year, "")}}</td>
             <td>{{getCount('Grade 4','4','',$school_year, "")}}</td>
             <td>{{getCount('Grade 4','5','',$school_year, "")}}</td>
             <td>{{getCount('Grade 4','6','',$school_year, "")}}</td>
             <td>{{$elemtotal4 = $elemtotal4 + gettotal('Grade 4','',$school_year, "")}}</td>
            </tr>
             <tr>
             <td>Grade 5 </td>
             <td>{{getCount('Grade 5','1','',$school_year, "")}}</td>
             <td>{{getCount('Grade 5','2','',$school_year, "")}}</td>
             <td>{{getCount('Grade 5','3','',$school_year, "")}}</td>
             <td>{{getCount('Grade 5','4','',$school_year, "")}}</td>
             <td>{{getCount('Grade 5','5','',$school_year, "")}}</td>
             <td>{{getCount('Grade 5','6','',$school_year, "")}}</td>
             <td>{{$elemtotal5 = $elemtotal5 + gettotal('Grade 5','',$school_year, "")}}</td>
            </tr>
             <tr><td>Grade 6 </td><td>{{getCount('Grade 6','1','',$school_year, "")}}</td>
             <td>{{getCount('Grade 6','2','',$school_year, "")}}</td>
             <td>{{getCount('Grade 6','3','',$school_year, "")}}</td>
             <td>{{getCount('Grade 6','4','',$school_year, "")}}</td>
             <td>{{getCount('Grade 6','5','',$school_year, "")}}</td>
             <td>{{getCount('Grade 6','6','',$school_year, "")}}</td>
             <td>{{$elemtotal6 = $elemtotal6 + gettotal('Grade 6','',$school_year, "")}}</td>
            </tr>
            <tr>
             <td><strong>Total</strong></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td><strong>{{$elemtotal = $elemtotal1 + $elemtotal2 + $elemtotal3 + $elemtotal4 + $elemtotal5 + $elemtotal6}}</strong></td>            
            </tr>     
         </tbody>
       <tfoot>
       </tfoot>    
       </table>
     
     <h3>Junior High School</h3>    
     <table id="example2" class="table table-responsive table-striped">
         <thead>
             <tr><th>GRADE LEVEL</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>Total</th></tr>
         </thead>
         <tbody>
            <tr>
             <tr><td>Grade 7 </td><td>{{getCount('Grade 7','1','',$school_year, "")}}</td>
             <td>{{getCount('Grade 7','2','',$school_year, "")}}</td>
             <td>{{getCount('Grade 7','3','',$school_year, "")}}</td>
             <td>{{getCount('Grade 7','4','',$school_year, "")}}</td>
             <td>{{getCount('Grade 7','5','',$school_year, "")}}</td>
             <td>{{getCount('Grade 7','6','',$school_year, "")}}</td>
             <td>{{$elemtotal7 = $elemtotal7 + gettotal('Grade 7','',$school_year, "")}}</td>
            </tr>
             <tr><td>Grade 8 </td><td>{{getCount('Grade 8','1','',$school_year, "")}}</td>
             <td>{{getCount('Grade 8','2','',$school_year, "")}}</td>
             <td>{{getCount('Grade 8','3','',$school_year, "")}}</td>
             <td>{{getCount('Grade 8','4','',$school_year, "")}}</td>
             <td>{{getCount('Grade 8','5','',$school_year, "")}}</td>
             <td>{{getCount('Grade 8','6','',$school_year, "")}}</td>
             <td>{{$elemtotal8 = $elemtotal8 + gettotal('Grade 8','',$school_year, "")}}</td>
            </tr>
             <tr><td>Grade 9 </td><td>{{getCount('Grade 9','1','',$school_year, "")}}</td>
             <td>{{getCount('Grade 9','2','',$school_year, "")}}</td>
             <td>{{getCount('Grade 9','3','',$school_year, "")}}</td>
             <td>{{getCount('Grade 9','4','',$school_year, "")}}</td>
             <td>{{getCount('Grade 9','5','',$school_year, "")}}</td>
             <td>{{getCount('Grade 9','6','',$school_year, "")}}</td>
             <td>{{$elemtotal9 = $elemtotal9 + gettotal('Grade 9','',$school_year, "")}}</td>
            </tr>
             <tr><td>Grade 10 </td><td>{{getCount('Grade 10','1','',$school_year, "")}}</td>
             <td>{{getCount('Grade 10','2','',$school_year, "")}}</td>
             <td>{{getCount('Grade 10','3','',$school_year, "")}}</td>
             <td>{{getCount('Grade 10','4','',$school_year, "")}}</td>
             <td>{{getCount('Grade 10','5','',$school_year, "")}}</td>
             <td>{{getCount('Grade 10','6','',$school_year, "")}}</td>
             <td>{{$elemtotal10 = $elemtotal10 + gettotal('Grade 10','',$school_year, "")}}</td>
            </tr>
            <tr>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td><strong>{{$juniortotal = $elemtotal7 + $elemtotal8 + $elemtotal9 + $elemtotal10}}</strong></td>            
            </tr>     
         </tbody>
       <tfoot>
       </tfoot>    
       </table>
     
        <h3>Senior High School-1st Semester</h3> 
     <table id="example2" class="table table-responsive table-striped">
         <thead>
             <tr><th>GRADE LEVEL</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th></th><th>Total</th></tr>
         </thead>
            <tr>
             <td>Grade 11 ABM</td>
             <td>{{getCount('Grade 11','1','ABM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','2','ABM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','3','ABM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','4','ABM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','5','ABM',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr11total1_1st = $gr11total1_1st + gettotal('Grade 11','ABM',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 11 HUMSS</td>
             <td>{{getCount('Grade 11','1','HUMSS',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','2','HUMSS',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','3','HUMSS',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','4','HUMSS',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','5','HUMSS',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr11total2_1st = $gr11total2_1st + gettotal('Grade 11','HUMSS',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 11 STEM</td>
             <td>{{getCount('Grade 11','1','STEM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','2','STEM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','3','STEM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','4','STEM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','5','STEM',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr11total3_1st = $gr11total3_1st + gettotal('Grade 11','STEM',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 11 GA</td>
             <td>{{getCount('Grade 11','1','GA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','2','GA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','3','GA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','4','GA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','5','GA',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr11total4_1st = $gr11total4_1st + gettotal('Grade 11','GA',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 11 PA</td>
             <td>{{getCount('Grade 11','1','PA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','2','PA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','3','PA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','4','PA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','5','PA',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr11total6_1st = $gr11total6_1st + gettotal('Grade 11','PA',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 11 VA</td>
             <td>{{getCount('Grade 11','1','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','2','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','3','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','4','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','5','VA',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr11total7_1st = $gr11total7_1st + gettotal('Grade 11','VA',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 11 NO STRAND</td>
             <td>{{getCount('Grade 11','1','NO STRAND YET',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','2','NO STRAND YET',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','3','NO STRAND YET',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','4','NO STRAND YET',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','5','NO STRAND YET',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr11total5_1st = $gr11total5_1st + gettotal('Grade 11','NO STRAND YET',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td><strong>Sub-Total</strong></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td><strong>{{$gr11total1_1st+$gr11total2_1st+$gr11total3_1st+$gr11total4_1st+$gr11total5_1st+$gr11total6_1st+$gr11total7_1st}}</strong></td> 
            </tr> 
          
            <tr><td>Grade 12 ABM</td><td>{{getCount('Grade 12','1','ABM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','2','ABM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','3','ABM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','4','ABM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','5','ABM',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr12total1_1st = $gr12total1_1st + gettotal('Grade 12','ABM',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 12 HUMSS</td>
             <td>{{getCount('Grade 12','1','HUMSS',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','2','HUMSS',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','3','HUMSS',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','4','HUMSS',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','5','HUMSS',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr12total2_1st = $gr12total2_1st + gettotal('Grade 12','HUMSS',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 12 STEM</td>
             <td>{{getCount('Grade 12','1','STEM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','2','STEM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','3','STEM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','4','STEM',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','5','STEM',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr12total3_1st = $gr12total3_1st + gettotal('Grade 12','STEM',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 12 GA</td>
             <td>{{getCount('Grade 12','1','GA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','2','GA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','3','GA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','4','GA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','5','GA',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr12total4_1st = $gr12total4_1st + gettotal('Grade 12','GA',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 12 PA</td>
             <td>{{getCount('Grade 12','1','PA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','2','PA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','3','PA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','4','PA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','5','PA',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr12total6_1st = $gr12total6_1st + gettotal('Grade 12','PA',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 12 VA</td>
             <td>{{getCount('Grade 12','1','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','2','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','3','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','4','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','5','VA',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr12total7_1st = $gr12total7_1st + gettotal('Grade 12','VA',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 12 NO STRAND</td>
             <td>{{getCount('Grade 12','1','NO STRAND YET',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','2','NO STRAND YET',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','3','NO STRAND YET',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','4','NO STRAND YET',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','5','NO STRAND YET',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr12total5_1st = $gr12total5_1st + gettotal('Grade 12','NO STRAND YET',$school_year, "1st Semester")}}</td>
            </tr>
            <tr>
             <td><strong>Sub-Total</strong></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td><strong>{{$gr12total1_1st+$gr12total2_1st+$gr12total3_1st+$gr12total4_1st+$gr12total5_1st+$gr12total6_1st+$gr12total7_1st}}</strong></td> 
            </tr> 
            <tr>
             <td><strong>Total</strong></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td><strong>{{$shstotal_1st = $gr11total1_1st + $gr11total2_1st + $gr11total3_1st + $gr11total4_1st + $gr11total5_1st + $gr12total1_1st + $gr12total2_1st + $gr12total3_1st + $gr12total4_1st + $gr12total5_1st + $gr11total6_1st + $gr11total7_1st + $gr12total6_1st + $gr12total7_1st}}</strong></td> 
            </tr> 
     </table>    
     
        <h3>Senior High School-2nd Semester</h3> 
     <table id="example2" class="table table-responsive table-striped">
         <thead>
             <tr><th>GRADE LEVEL</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th></th><th>Total</th></tr>
         </thead>
            <tr>
             <td>Grade 11 ABM</td>
             <td>{{getCount('Grade 11','1','ABM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','2','ABM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','3','ABM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','4','ABM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','5','ABM',$school_year, "2nd Semester")}}</td>
             <td></td>
             <td>{{$gr11total1_2nd = $gr11total1_2nd + gettotal('Grade 11','ABM',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 11 HUMSS</td>
             <td>{{getCount('Grade 11','1','HUMSS',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','2','HUMSS',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','3','HUMSS',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','4','HUMSS',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','5','HUMSS',$school_year, "2nd Semester")}}</td>
             <td></td>
             <td>{{$gr11total2_2nd = $gr11total2_2nd + gettotal('Grade 11','HUMSS',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 11 STEM</td>
             <td>{{getCount('Grade 11','1','STEM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','2','STEM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','3','STEM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','4','STEM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','5','STEM',$school_year, "2nd Semester")}}</td>
             <td></td>
             <td>{{$gr11total3_2nd = $gr11total3_2nd + gettotal('Grade 11','STEM',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 11 GA</td>
             <td>{{getCount('Grade 11','1','GA',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','2','GA',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','3','GA',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','4','GA',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','5','GA',$school_year, "2nd Semester")}}</td>
             <td></td>
             <td>{{$gr11total4_2nd = $gr11total4_2nd + gettotal('Grade 11','GA',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 11 PA</td>
             <td>{{getCount('Grade 11','1','PA',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','2','PA',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','3','PA',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','4','PA',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','5','PA',$school_year, "2nd Semester")}}</td>
             <td></td>
             <td>{{$gr11total6_2nd = $gr11total6_2nd + gettotal('Grade 11','PA',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 11 VA</td>
             <td>{{getCount('Grade 11','1','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','2','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','3','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','4','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 11','5','VA',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr11total7_2nd = $gr11total7_2nd + gettotal('Grade 11','VA',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 11 NO STRAND</td>
             <td>{{getCount('Grade 11','1','NO STRAND YET',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','2','NO STRAND YET',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','3','NO STRAND YET',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','4','NO STRAND YET',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 11','5','NO STRAND YET',$school_year, "2nd Semester")}}</td>
             <td></td>
             <td>{{$gr11total5_2nd = $gr11total5_2nd + gettotal('Grade 11','NO STRAND YET',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td><strong>Sub-Total</strong></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td><strong>{{$gr11total1_2nd+$gr11total2_2nd+$gr11total3_2nd+$gr11total4_2nd+$gr11total5_2nd+$gr11total6_2nd+$gr11total7_2nd}}</strong></td> 
            </tr> 
          
            <tr><td>Grade 12 ABM</td><td>{{getCount('Grade 12','1','ABM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','2','ABM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','3','ABM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','4','ABM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','5','ABM',$school_year, "2nd Semester")}}</td>
             <td></td>
             <td>{{$gr12total1_2nd = $gr12total1_2nd + gettotal('Grade 12','ABM',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 12 HUMSS</td>
             <td>{{getCount('Grade 12','1','HUMSS',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','2','HUMSS',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','3','HUMSS',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','4','HUMSS',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','5','HUMSS',$school_year, "2nd Semester")}}</td>
             <td></td>
             <td>{{$gr12total2_2nd = $gr12total2_2nd + gettotal('Grade 12','HUMSS',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 12 STEM</td>
             <td>{{getCount('Grade 12','1','STEM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','2','STEM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','3','STEM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','4','STEM',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','5','STEM',$school_year, "2nd Semester")}}</td>
             <td></td>
             <td>{{$gr12total3_2nd = $gr12total3_2nd + gettotal('Grade 12','STEM',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 12 GA</td>
             <td>{{getCount('Grade 12','1','GA',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','2','GA',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','3','GA',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','4','GA',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','5','GA',$school_year, "2nd Semester")}}</td>
             <td></td>
             <td>{{$gr12total4_2nd = $gr12total4_2nd + gettotal('Grade 12','GA',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 12 PA</td>
             <td>{{getCount('Grade 12','1','PA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','2','PA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','3','PA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','4','PA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','5','PA',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr12total6_2nd = $gr12total6_2nd + gettotal('Grade 12','PA',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 12 VA</td>
             <td>{{getCount('Grade 12','1','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','2','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','3','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','4','VA',$school_year, "1st Semester")}}</td>
             <td>{{getCount('Grade 12','5','VA',$school_year, "1st Semester")}}</td>
             <td></td>
             <td>{{$gr12total7_2nd = $gr12total7_2nd + gettotal('Grade 12','VA',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td>Grade 12 NO STRAND</td>
             <td>{{getCount('Grade 12','1','NO STRAND YET',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','2','NO STRAND YET',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','3','NO STRAND YET',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','4','NO STRAND YET',$school_year, "2nd Semester")}}</td>
             <td>{{getCount('Grade 12','5','NO STRAND YET',$school_year, "2nd Semester")}}</td>
             <td></td>
             <td>{{$gr12total5_2nd = $gr12total5_2nd + gettotal('Grade 12','NO STRAND YET',$school_year, "2nd Semester")}}</td>
            </tr>
            <tr>
             <td><strong>Sub-Total</strong></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td><strong>{{$gr12total1_2nd+$gr12total2_2nd+$gr12total3_2nd+$gr12total4_2nd+$gr12total5_2nd+$gr12total6_2nd+$gr12total7_2nd}}</strong></td> 
            </tr>
            <tr>
             <td><strong>Total</strong></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td><strong>{{$shstotal_2nd = $gr11total1_2nd + $gr11total2_2nd + $gr11total3_2nd + $gr11total4_2nd + $gr11total5_2nd + $gr12total1_2nd + $gr12total2_2nd + $gr12total3_2nd + $gr12total4_2nd + $gr12total5_2nd}}</strong></td> 
            </tr> 
     </table>     
     <table id="example2" class="table table-responsive table-striped">
            <tr>
             <td><div align="left"><strong>WITHDRAWN:</strong></div</td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td><strong>{{getWithdrawn($school_year, "")}}</strong></td>          
            </tr>         
     </table>
     <table id="example2" class="table table-responsive table-striped">
            <tr>
             <td><div align="left"><strong>GRAND TOTAL (1ST SEMESTER):</strong></div</td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td><strong>{{$preschooltotal + $elemtotal + $juniortotal + $shstotal_1st}}</strong></td>          
            </tr>         
            <tr>
             <td><div align="left"><strong>GRAND TOTAL (2ND SEMESTER):</strong></div</td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td><strong>{{$preschooltotal + $elemtotal + $juniortotal + $shstotal_2nd}}</strong></td>          
            </tr>         
     </table>
