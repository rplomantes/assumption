<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class assessBedAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assessBedAccounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $notYetEnrolleds = \App\Status::where('status',"<",3)->where('date_dropped', NULL)->where('school_year',2020)->where('period', NULL)->where('academic_type','BED')->get();
        foreach($notYetEnrolleds as $notYetEnrolled){
            $request = new \Symfony\Component\HttpFoundation\Request();
            $request->_token = csrf_field();
            $request->idno = $notYetEnrolled->idno;
            $request->level = $notYetEnrolled->level;
            $request->strand = null;
            $request->section = $notYetEnrolled->section;
            $request->plan = $notYetEnrolled->type_of_plan;
            $request->is_siblong = "No";
            $request->submit="Process Assessment";
            
            if($notYetEnrolled->status == 0){
            \App\Http\Controllers\BedRegistrar\Assess2::post_assess($request);
            }else{
            $this->manualMark($request->idno);  
             $this->info($request->idno."-Manual Marked");  
            }
            
            
             $this->info($request->idno);
        }
    }
    function manualMark($idno) {
            $status = \App\Status::where('idno', $idno)->first();
            if ($status->status == env("ASSESSED")){
                $idno = \App\Http\Controllers\BedRegistrar\Assess2::changeStatus($idno);
                \App\Http\Controllers\BedRegistrar\Assess2::addLevels($idno);
//                \App\Http\Controllers\Admin\Logs::log("Manually marked as enrolled - $idno");
            }
            return redirect(url('registrar_college',array('assessment',$idno)));
    }
}
