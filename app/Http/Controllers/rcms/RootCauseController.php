<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Models\RecordNumber;
use App\Models\RootAuditTrial;
use App\Models\RoleGroup;
use App\Models\RiskAssesmentGrid;
use App\Models\RootCauseAnalysis;
use App\Models\RootCauseAnalysesGrid;
use App\Models\RootCauseAnalysisHistory;
use App\Models\User;
use Helpers;
use Illuminate\Support\Facades\Mail;
use App\Models\RootcauseAnalysisDocDetails;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

 class RootCauseController extends Controller
{
    public function rootcause()
    {
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');
        return view("frontend.forms.root-cause-analysis", compact('due_date', 'record_number'));
    }
    public function root_store(Request $request)
    { 
        if (!$request->short_description) {
           toastr()->error("Short description is required");
             return redirect()->back();
        }
        $root = new RootCauseAnalysis();
        $root->form_type = "Root-cause-analysis"; 
        $root->originator_id = $request->originator_id;
        $root->parent_id = $request->parent_id;
        $root->parent_type = $request->parent_type;
        $root->date_opened = $request->date_opened;
        $root->division_id = $request->division_id;
        $root->priority_level = $request->priority_level;
        $root->severity_level = $request->severity_level;
        $root->short_description =($request->short_description);
        $root->assigned_to = $request->assigned_to;
        $root->assign_to = $request->assign_to;
        $root->root_cause_description = $request->root_cause_description;
        $root->due_date = $request->due_date;
        $root->cft_comments_new = $request->cft_comments_new;
         $root->Type= $request->Type;
        
         $root->investigators = $request->investigators;
        // $root->investigators = implode(',', $request->investigators);
        $root->initiated_through = $request->initiated_through;
        $root->initiated_if_other = $request->initiated_if_other;
        // $root->department = $request->department;
        if(is_array($request->department)){
            $root->department = implode(',',$request->department);
        }
        $root->description = ($request->description);
        $root->comments = ($request->comments);
        $root->related_url = ($request->related_url);
        $root->root_cause_methodology = implode(',', $request->root_cause_methodology);

        // $root->root_cause_methodology = $request->root_cause_methodology;
        // if(is_array($request->root_cause_methodology)){
        //     $root->root_cause_methodology = implode(',',$request->root_cause_methodology);
        // }
        //Fishbone or Ishikawa Diagram 
        if (!empty($request->measurement  )) {
            $root->measurement = serialize($request->measurement);
        }
        else {
            $data2->measurement = serialize([]);
        }
        if (!empty($request->materials  )) {
            $root->materials = serialize($request->materials);
        } else {
            $data2->materials = serialize([]);
        }
        if (!empty($request->environment  )) {
            $root->environment = serialize($request->environment);
        } else {
            $data2->environment = serialize([]);
        }
        if (!empty($request->manpower  )) {
            $root->manpower = serialize($request->manpower);
        } else {
            $data2->manpower = serialize([]);
        }
        if (!empty($request->machine  )) {
            $root->machine = serialize($request->machine);
        } else {
            $data2->machine = serialize([]);
        }
        if (!empty($request->methods)) {
            $root->methods = serialize($request->methods);
        } else {
            $data2->methods = serialize([]);
        }
        $root->problem_statement = ($request->problem_statement);
        // Why-Why Chart (Launch Instruction) Problem Statement 
        if (!empty($request->why_problem_statement)) {
            $root->why_problem_statement = $request->why_problem_statement;
        }
        if (!empty($request->why_1  )) {
            $root->why_1 = serialize($request->why_1);
        }
        if (!empty($request->why_2  )) {
            $root->why_2 = serialize($request->why_2);
        }
        if (!empty($request->why_3  )) {
            $root->why_3 = serialize($request->why_3);
        }
        if (!empty($request->why_4 )) {
            $root->why_4 = serialize($request->why_4);
        }
        if (!empty($request->why_5  )) {
            $root->why_5 = serialize($request->why_5);
        }
        if (!empty($request->why_root_cause)) {
            $root->why_root_cause = $request->why_root_cause;
        }

        // Is/Is Not Analysis (Launch Instruction)
        $root->what_will_be = ($request->what_will_be);
        $root->what_will_not_be = ($request->what_will_not_be);
        $root->what_rationable = ($request->what_rationable);

        $root->where_will_be = ($request->where_will_be);
        $root->where_will_not_be = ($request->where_will_not_be);
        $root->where_rationable = ($request->where_rationable);

        $root->when_will_be = ($request->when_will_be);
        $root->when_will_not_be = ($request->when_will_not_be);
        $root->when_rationable = ($request->when_rationable);

        $root->coverage_will_be = ($request->coverage_will_be);
        $root->coverage_will_not_be = ($request->coverage_will_not_be);
        $root->coverage_rationable = ($request->coverage_rationable);

        $root->who_will_be = ($request->who_will_be);
        $root->who_will_not_be = ($request->who_will_not_be);
        $root->who_rationable = ($request->who_rationable);
        
        $root->investigation_summary = ($request->investigation_summary);
        // $root->zone = ($request->zone);
        // $root->country = ($request->country);
        // $root->state = ($request->state);
        // $root->city = ($request->city);
        $root->submitted_by = ($request->submitted_by);

        if (!empty($request->Root_Cause_Category  )) {
            $root->Root_Cause_Category = serialize($request->Root_Cause_Category);
        }
        if (!empty($request->Root_Cause_Sub_Category)) {
            $root->Root_Cause_Sub_Category= serialize($request->Root_Cause_Sub_Category);
        }
        if (!empty($request->Probability)) {
            $root->Probability = serialize($request->Probability);
        }
        if (!empty($request->Remarks)) {
            $root->Remarks = serialize($request->Remarks);
        }

        if (!empty($request->initial_rpn)) {
            $root->initial_rpn = serialize($root->initial_rpn);
        }

        $root->record = ((RecordNumber::first()->value('counter')) + 1);
        $root->initiator_id = Auth::user()->id;
        $root->division_code = $request->division_code;
        $root->intiation_date = $request->intiation_date;
        $root->initiator_Group= $request->initiator_Group;
        $root->initiator_group_code = $request->initiator_group_code;
        $root->short_description = $request->short_description;
        $root->due_date = $request->due_date;
        $root->assign_to = $request->assign_to;
        $root->Sample_Types = $request->Sample_Types;
        if (!empty($request->root_cause_initial_attachment)) {
            $files = [];
            if ($request->hasfile('root_cause_initial_attachment')) {
                foreach ($request->file('root_cause_initial_attachment') as $file) {
                    $name = $request->name . 'root_cause_initial_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $root->root_cause_initial_attachment = json_encode($files);
        }
        if (!empty($request->cft_attchament_new)) {
            $files = [];
            if ($request->hasfile('cft_attchament_new')) {
                foreach ($request->file('cft_attchament_new') as $file) {
                    $name = $request->name . 'cft_attchament_new' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $root->cft_attchament_new = json_encode($files);
        }
        
        //Failure Mode and Effect Analysis+

        if (!empty($request->risk_factor)) {
            $root->risk_factor = serialize($request->risk_factor);
        }
        if (!empty($request->risk_element)) {
            $root->risk_element = serialize($request->risk_element);
        }
        if (!empty($request->problem_cause)) {
            $root->problem_cause = serialize($request->problem_cause);
        }
        if (!empty($request->existing_risk_control)) {
            $root->existing_risk_control = serialize($request->existing_risk_control);
        }
        if (!empty($request->initial_severity)) {
            $root->initial_severity = serialize($request->initial_severity);
        }
        if (!empty($request->initial_detectability)) {
            $root->initial_detectability = serialize($request->initial_detectability);
        }
        if (!empty($request->initial_probability)) {
            $root->initial_probability = serialize($request->initial_probability);
        }
        if (!empty($request->initial_rpn)) {
            $root->initial_rpn = serialize($request->initial_rpn);
        }
        if (!empty($request->risk_acceptance)) {
            $root->risk_acceptance = serialize($request->risk_acceptance);
        }
        if (!empty($request->risk_control_measure)) {
            $root->risk_control_measure = serialize($request->risk_control_measure);
        }
        if (!empty($request->residual_severity)) {
            $root->residual_severity = serialize($request->residual_severity);
        }
        if (!empty($request->residual_probability)) {
            $root->residual_probability = serialize($request->residual_probability);
        }
        if (!empty($request->residual_detectability)) {
            $root->residual_detectability = serialize($request->residual_detectability);
        }
        if (!empty($request->residual_rpn)) {
            $root->residual_rpn = serialize($request->residual_rpn);
        }
        if (!empty($request->risk_acceptance2)) {
            $root->risk_acceptance2 = serialize($request->risk_acceptance2);
        }
        if (!empty($request->mitigation_proposal)) {
            $root->mitigation_proposal = serialize($request->mitigation_proposal);
        }

        $root->status = 'Opened';
        $root->stage = 1;
        $root->save();
        // -------------------------------------------------------
        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();
        
        if (!empty($root->record)){
            $history = new RootAuditTrial();
            $history->root_id = $root->id;
            $history->activity_type = 'Record Number';
           
            $history->previous = "Null";
            $history->current =  Helpers::getDivisionName($request->division_id).'/RCA/'. date('Y') .'/'. str_pad( $root->record, 4, '0', STR_PAD_LEFT);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $root->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if (!empty($root->Initiator)){
            $history = new RootAuditTrial();
            $history->root_id = $root->id;
            $history->activity_type = 'Initiator';
            $history->activity_type = '';
            $history->previous = "Null";
            $history->current = $root->Initiator;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $root->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
           
         
        if (!empty($root->division_code)){
            $history = new RootAuditTrial();
            $history->root_id = $root->id;
            $history->activity_type = 'Site/Location code';
            $history->previous = "Null";
            $history->current = $root->division_code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $root->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
       
    
            if (!empty($root->intiation_date)){
                $history = new RootAuditTrial();
                $history->root_id = $root->id;
                $history->activity_type = 'Date of Initiation';
                $history->previous = "Null";
                $history->current =  Carbon::now()->format('d-M-Y');
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $root->status;
                $history->change_to =   "Opened";
                $history->change_from = "Initiation";
                $history->action_name = 'Create';
                $history->save();
            }
           
    
       
       

        if (!empty($root->initiator_Group)){

            $history = new RootAuditTrial();
            $history->root_id = $root->id;
            $history->activity_type = 'Initiator Group';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorGroupFullName($root->initiator_Group);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $root->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if (!empty($root->initiator_group_code)){

            $history = new RootAuditTrial();
            $history->root_id = $root->id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = "Null";
            $history->current = $root->initiator_group_code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $root->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if (!empty($root->assign_to)){
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Assigned To';
        $history->previous = "Null";
        $history->current = Helpers::getInitiatorName($root->assign_to);
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();
        }
       
      
        if (!empty($root->due_date)) {
            $history = new RootAuditTrial();
            $history->root_id = $root->id;
            $history->activity_type = 'Due Date';
            $history->previous = "Null";
            $history->current = $root->due_date;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $root->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
    
            }

        if (!empty($root->short_description)){
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Short Description';
        $history->previous = "Null";
        $history->current = $root->short_description;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();


        }
        if (!empty($root->severity_level)){
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Severity Level';
        $history->previous = "Null";
        $history->current = $root->severity_level;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();
        }

        if (!empty($root->initiated_through)){
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Initiated Through';
        $history->previous = "Null";
        $history->current =$root->initiated_through;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();
        }
       
        if (!empty($root->initiated_if_other)){
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Other';
        $history->previous = "Null";
        $history->current = $root->initiated_if_other;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();
        }
        if (!empty($root->Type)){
            $history = new RootAuditTrial();
            $history->root_id = $root->id;
            $history->activity_type = 'Type';
            $history->previous = "Null";
            $history->current = $root->Type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $root->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();

        }
        if (!empty($root->priority_level)){
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Priority Level';
        $history->previous = "Null";
        $history->current = $root->priority_level;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        }


        if (!empty($root->department)){
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Department';
        $history->previous = "Null";
        // $history->current =$root->department;
        $history->current = $root->department;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();
        }
       
        if (!empty($root->description)){
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Description';
        $history->previous = "Null";
        $history->current = $root->description;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();


        }
        
        if (!empty($root->comments)){
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Comments';
        $history->previous = "Null";
        $history->current = $root->comments;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();
        }
        

if (!empty($root->root_cause_initial_attachment)){
    $history = new RootAuditTrial();
$history->root_id = $root->id;
$history->activity_type = 'Initial Attachment';
$history->previous = "Null";
$history->current = $root->root_cause_initial_attachment;
$history->comment = "Not Applicable";
$history->user_id = Auth::user()->id;
$history->user_name = Auth::user()->name;
$history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
$history->origin_state = $root->status;
$history->change_to =   "Opened";
$history->change_from = "Initiation";
$history->action_name = 'Create';
$history->save();
}




        // if (!empty($root->department)){
        //     $history = new RootAuditTrial();
        // $history->root_id = $root->id;
        // $history->activity_type = 'department';
        // $history->previous = "Null";
        // $history->current =$root->department;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $root->status;
        // $history->change_to =   "Opened";
        // $history->change_from = "Initiation";
        // $history->action_name = 'Create';
        // $history->save();
        // }
          
        
        

      
       
        // if (!empty($root->Sample_Types)){
        //     $history = new RootAuditTrial();
        //     $history->root_id = $root->id;
        //     $history->activity_type = 'Sample_Types';
        //     $history->previous = "Null";
        //     $history->current = $root->Sample_Types;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $root->status;
        //     $history->change_to =   "Opened";
        //     $history->change_from = "Initiation";
        //     $history->action_name = 'Create';
        //     $history->save();

        // }
       
 
        if (!empty($root->related_url)){
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Related Url';
        $history->previous = "Null";
        $history->current = $root->related_url;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();
        }


        if (!empty($root->root_cause_methodology)){

            $history = new RootAuditTrial();
            $history->root_id = $root->id;
            $history->activity_type = 'Root Cause Methodology';
            $history->previous = "Null";
            $history->current = $root->root_cause_methodology;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $root->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }


        if (!empty($root->root_cause_description)){

            $history = new RootAuditTrial();
            $history->root_id = $root->id;
            $history->activity_type = 'Root Cause Description';
            $history->previous = "Null";
            $history->current = $root->root_cause_description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $root->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        // if (!empty($root->investigators)){
        //     $history = new RootAuditTrial();
        // $history->root_id = $root->id;
        // $history->activity_type = 'Investigators';
        // $history->previous = "Null";
        // $history->current = $root->investigators;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $root->status;
        // $history->change_to =   "Opened";
        // $history->change_from = "Initiation";
        // $history->action_name = 'Create';
        // $history->save();

        // }
        if (!empty($root->investigation_summary)){

            $history = new RootAuditTrial();
            $history->root_id = $root->id;
            $history->activity_type = 'Investigation Summary';
            $history->previous = "Null";
            $history->current = $root->investigation_summary;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $root->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if (!empty($root->cft_comments_new)){

            $history = new RootAuditTrial();
            $history->root_id = $root->id;
            $history->activity_type = 'Final Comments';
            $history->previous = "Null";
            $history->current = $root->cft_comments_new;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $root->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
     
        if (!empty($root->cft_attchament_new)){
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Final Attchament';
        $history->previous = "Null";
        $history->current = $root->cft_attchament_new;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();
        }
        
        
        
        // if (!empty($root->lab_inv_concl)){
        //     $history = new RootAuditTrial();
        //     $history->root_id = $root->id;
        //     $history->activity_type = 'Lab Inv Concl';
        //     $history->previous = "Null";
        //     $history->current = $root->lab_inv_concl;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $root->status;
        //     $history->change_to =   "Opened";
        //     $history->change_from = "Initiation";
        //     $history->action_name = 'Create';
        //     $history->save();
    

        // }
       
        
       
        // if (!empty($root->qc_head_comments)){

        //     $history = new RootAuditTrial();
        // $history->root_id = $root->id;
        // $history->activity_type = 'Qc Head Comments';
        // $history->previous = "Null";
        // $history->current = $root->qc_head_comments;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $root->status;
        // $history->change_to =   "Opened";
        // $history->change_from = "Initiation";
        // $history->action_name = 'Create';
        // $history->save();

        // }
        
        

      
        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }
    public function root_update(Request $request, $id)
    {
        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return redirect()->back();
        }
        $lastDocument =  RootCauseAnalysis::find($id);
        $root =  RootCauseAnalysis::find($id);
        $root->initiated_through = $request->initiated_through;
        $root->initiated_if_other = ($request->initiated_if_other);
        $root->short_description = $request->short_description;
        // $root->due_date = $request->due_date;
        $root->severity_level= $request->severity_level;
        $root->Type= ($request->Type);
        $root->priority_level = ($request->priority_level);
        $root->department = ($request->department);
        // $root->department = implode(',',$request->department);
        $root->description = ($request->description);
        $root->investigation_summary = ($request->investigation_summary);
        $root->root_cause_description = $request->root_cause_description;
        $root->cft_comments_new = ($request->cft_comments_new);

        $root->initiator_group_code= $request->initiator_group_code;
        // $root->initiator_Group= Helpers::getInitiatorGroupFullName($request->initiator_Group);
        $root->initiator_Group= $request->initiator_Group;
        // dd($root->initiator_group_code);
       
         $root->investigators = ($request->investigators);
        $root->related_url = ($request->related_url);
        // $root->investigators = implode(',', $request->investigators);
        $root->root_cause_methodology = implode(',', $request->root_cause_methodology);
        // $root->country = ($request->country);
        $root->assign_to = $request->assign_to;
        $root->Sample_Types = $request->Sample_Types;
         
        // Root Cause +
        if (!empty($request->Root_Cause_Category  )) {
            $root->Root_Cause_Category = serialize($request->Root_Cause_Category);
        }
        if (!empty($request->Root_Cause_Sub_Category)) {
            $root->Root_Cause_Sub_Category= serialize($request->Root_Cause_Sub_Category);
        }
        if (!empty($request->Probability)) {
            $root->Probability = serialize($request->Probability);
        }
        if (!empty($request->Remarks)) {
            $root->Remarks = serialize($request->Remarks);
        }
        if (!empty($request->why_problem_statement)) {
            $root->why_problem_statement = $request->why_problem_statement;
        } 
        if (!empty($request->why_1  )) {
            $root->why_1 = serialize($request->why_1);
        }
        if (!empty($request->why_2  )) {
            $root->why_2 = serialize($request->why_2);
        }
        if (!empty($request->why_3  )) {
            $root->why_3 = serialize($request->why_3);
        }
        if (!empty($request->why_4 )) {
            $root->why_4 = serialize($request->why_4);
        }
        if (!empty($request->why_5  )) {
            $root->why_5 = serialize($request->why_5);
        }
        if (!empty($request->why_root_cause)) {
            $root->why_root_cause = $request->why_root_cause;
        }

         // Is/Is Not Analysis (Launch Instruction)
         $root->what_will_be = ($request->what_will_be);
         $root->what_will_not_be = ($request->what_will_not_be);
         $root->what_rationable = ($request->what_rationable);
 
         $root->where_will_be = ($request->where_will_be);
         $root->where_will_not_be = ($request->where_will_not_be);
         $root->where_rationable = ($request->where_rationable);
 
         $root->when_will_be = ($request->when_will_be);
         $root->when_will_not_be = ($request->when_will_not_be);
         $root->when_rationable = ($request->when_rationable);
 
         $root->coverage_will_be = ($request->coverage_will_be);
         $root->coverage_will_not_be = ($request->coverage_will_not_be);
         $root->coverage_rationable = ($request->coverage_rationable);
 
         $root->who_will_be = ($request->who_will_be);
         $root->who_will_not_be = ($request->who_will_not_be);
         $root->who_rationable = ($request->who_rationable);
         
        // if (!empty($request->root_cause_initial_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('root_cause_initial_attachment')) {
        //         foreach ($request->file('root_cause_initial_attachment') as $file) {
        //             $name = $request->name . 'root_cause_initial_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $root->root_cause_initial_attachment = json_encode($files);
        // }


        $files = is_array($request->existing_root_cause_initial_attachment) ? $request->existing_root_cause_initial_attachment : null;

        if (!empty($request->root_cause_initial_attachment)) {
            if ($root->root_cause_initial_attachment) {
                $existingFiles = json_decode($root->root_cause_initial_attachment, true); // Convert to associative array
                if (is_array($existingFiles )) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('root_cause_initial_attachment')) {
                foreach ($request->file('root_cause_initial_attachment') as $file) {
                    $name = $request->name . 'root_cause_initial_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $root->root_cause_initial_attachment = !empty($files) ? json_encode(array_values($files)) : null;
        // if (!empty($request->cft_attchament_new)) {
        //     $files = [];
        //     if ($request->hasfile('cft_attchament_new')) {
        //         foreach ($request->file('cft_attchament_new') as $file) {
        //             $name = $request->name . 'cft_attchament_new' . rand(1, 100) . '.' . $file->getClientOriginalExtension() ;
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $root->cft_attchament_new = json_encode($files);
        // }

        $files = is_array($request->existing_cft_attchament_new) ? $request->existing_cft_attchament_new : null;

        if (!empty($request->cft_attchament_new)) {
            if ($root->cft_attchament_new) {
                $existingFiles = json_decode($root->cft_attchament_new, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles); // Re-index the array to ensure it's a proper array
                }
            }

            if ($request->hasfile('cft_attchament_new')) {
                foreach ($request->file('cft_attchament_new') as $file) {
                    $name = $request->name . 'cft_attchament_new' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $root->cft_attchament_new = !empty($files) ?  json_encode(array_values($files)) : null; // Re-index again before encoding

        
        // $root->investigators = json_encode($request->investigators);
        $root->submitted_by = $request->submitted_by;
        
        $root->comments = $request->comments;
        $root->lab_inv_concl = $request->lab_inv_concl;
        //Failure Mode and Effect Analysis+

        if (!empty($request->risk_factor)) {
            $root->risk_factor = serialize($request->risk_factor);
        }
        if (!empty($request->risk_element)) {
            $root->risk_element = serialize($request->risk_element);
        }
        if (!empty($request->problem_cause)) {
            $root->problem_cause = serialize($request->problem_cause);
        }
        if (!empty($request->existing_risk_control)) {
            $root->existing_risk_control = serialize($request->existing_risk_control);
        }
        if (!empty($request->initial_severity)) {
            $root->initial_severity = serialize($request->initial_severity);
        }
        if (!empty($request->initial_detectability)) {
            $root->initial_detectability = serialize($request->initial_detectability);
        }
        if (!empty($request->initial_probability)) {
            $root->initial_probability = serialize($request->initial_probability);
        }
        if (!empty($request->initial_rpn)) {
            $root->initial_rpn = serialize($request->initial_rpn);
        }
        if (!empty($request->risk_acceptance)) {
            $root->risk_acceptance = serialize($request->risk_acceptance);
        }
        if (!empty($request->risk_control_measure)) {
            $root->risk_control_measure = serialize($request->risk_control_measure);
        }
        if (!empty($request->residual_severity)) {
            $root->residual_severity = serialize($request->residual_severity);
        }
        if (!empty($request->residual_probability)) {
            $root->residual_probability = serialize($request->residual_probability);
        }
        if (!empty($request->residual_detectability)) {
            $root->residual_detectability = serialize($request->residual_detectability);
        }
        if (!empty($request->residual_rpn)) {
            $root->residual_rpn = serialize($request->residual_rpn);
        }
        if (!empty($request->risk_acceptance2)) {
            $root->risk_acceptance2 = serialize($request->risk_acceptance2);
        }
        if (!empty($request->mitigation_proposal)) {
            $root->mitigation_proposal = serialize($request->mitigation_proposal);
        }

        // Fishbone or Ishikawa Diagram +  (Launch Instruction)

        if (!empty($request->measurement)) {
            $root->measurement = serialize($request->measurement);
        }else {
            $data2->measurement = serialize([]);
        }
        if (!empty($request->materials)) {
            $root->materials = serialize($request->materials);
        }else {
            $data2->materials = serialize([]);
        }
        if (!empty($request->methods)) {
            $root->methods = serialize($request->methods);
        }else {
            $data2->methods = serialize([]);
        }
        if (!empty($request->environment)) {
            $root->environment = serialize($request->environment);
        }else {
            $data2->environment = serialize([]);
        }
        if (!empty($request->manpower)) {
            $root->manpower = serialize($request->manpower);
        }else {
            $data2->manpower = serialize([]);
        }
        if (!empty($request->machine)) {
            $root->machine = serialize($request->machine);
        }else {
            $data2->machine = serialize([]);
        }
        if (!empty($request->problem_statement)) {
            $root->problem_statement = $request->problem_statement;
        }
        $root->update(); 

        // if ($lastDocument->division_code != $root->division_code || !empty($request->division_code_comment)) {

        //     $history = new RootAuditTrial();
        //     $history->root_id = $id;
        //     $history->activity_type = 'Division Code';
        //     $history->previous = $lastDocument->division_code;
        //     $history->current = $root->division_code;
        //     $history->comment = $request->division_code_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to =   "Not Applicable";
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = 'Update';
        //     $history->save();
        // }

    

      
       
       
        // if ($lastDocument->Sample_Types != $root->Sample_Types || !empty($request->Sample_Types_comment)) {

        //     $history = new RootAuditTrial();
        //     $history->root_id = $id;
        //     $history->activity_type = 'Sample Types';
        //     $history->previous = $lastDocument->Sample_Types;
        //     $history->current = $root->Sample_Types;
        //     $history->comment = $request->Sample_Types_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to =   "Not Applicable";
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = 'Update';
        //     $history->save();
        // }
        // if ($lastDocument->investigators != $root->investigators || !empty($request->investigators_comment)) {

        //     $history = new RootAuditTrial();
        //     $history->root_id = $id;
        //     $history->activity_type = 'Investigators';
        //     $history->previous = $lastDocument->investigators;
        //     $history->current = $root->investigators;
        //     $history->comment = $request->investigators_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to =   "Not Applicable";
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = 'Update';
        //     $history->save();
        // }
       
        // if ($lastDocument->lab_inv_concl != $root->lab_inv_concl || !empty($request->lab_inv_concl_comment)) {

        //     $history = new RootAuditTrial();
        //     $history->root_id = $id;
        //     $history->activity_type = 'Lab Inv Concl';
        //     $history->previous = $lastDocument->lab_inv_concl;
        //     $history->current = $root->lab_inv_concl;
        //     $history->comment = $request->lab_inv_concl_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to =   "Not Applicable";
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = 'Update';
        //     $history->save();
        // }
       
        // if ($lastDocument->qc_head_comments != $root->qc_head_comments || !empty($request->qc_head_comments_comment)) {

        //     $history = new RootAuditTrial();
        //     $history->root_id = $id;
        //     $history->activity_type = 'Qc Head Comments';
        //     $history->previous = $lastDocument->qc_head_comments;
        //     $history->current = $root->qc_head_comments;
        //     $history->comment = $request->qc_head_comments_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to =   "Not Applicable";
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = 'Update';
        //     $history->save();
        // }
        // if ($lastDocument->inv_attach != $root->inv_attach || !empty($request->inv_attachcomment)) {

        //     $history = new RootAuditTrial();
        //     $history->root_id = $id;
        //     $history->activity_type = 'Inv Attach';
        //     $history->previous = $lastDocument->inv_attach;
        //     $history->current = $root->inv_attach;
        //     $history->comment = $request->inv_attach_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to =   "Not Applicable";
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = 'Update';
        //     $history->save();
        // }
        if ($lastDocument->initiator_Group != $root->initiator_Group) {
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Initiator Group')
            ->exists();
            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Initiator Group';
            $history->previous =  Helpers::getInitiatorGroupFullName($lastDocument->initiator_Group);
            $history->current =  Helpers::getInitiatorGroupFullName($root->initiator_Group);
            $history->comment =  "";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->initiator_group_code != $root->initiator_group_code) {
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Initiator Group Code')
            ->exists();
            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = $lastDocument->initiator_group_code;
            $history->current = $root->initiator_group_code;
            $history->comment = "";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->assign_to != $root->assign_to || !empty($request->assign_to_comment)) {
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Assigned To')
            ->exists();
            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Assigned To';
            $history->previous = Helpers::getInitiatorName($lastDocument->assign_to);
            $history->current = Helpers::getInitiatorName($root->assign_to);
            $history->comment = Helpers::getInitiatorName($request->assign_to_comment);
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
             $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->due_date != $root->due_date || !empty($request->due_date_comment)) {
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Due Date')
            ->exists();
            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Due Date';
            $history->previous = $lastDocument->due_date;
            $history->current = $root->due_date;
            $history->comment = $request->due_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';       
            $history->save();
        }

        if ($lastDocument->short_description != $root->short_description || !empty($request->short_description_comment)) {
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Short Description')
            ->exists();
            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Short Description';
            $history->previous = $lastDocument->short_description;
            $history->current = $root->short_description;
            $history->comment = $request->short_description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';           
            $history->save();
        }
     
        if ($lastDocument->severity_level != $root->severity_level || !empty($request->division_code_comment)) {
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Severity Level')
            ->exists();
            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Severity Level';
            $history->previous = $lastDocument->severity_level;
            $history->current = $root->severity_level;
            $history->comment = $request->division_code_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';          
            $history->save();
        }
        
        if ($lastDocument->initiated_through != $root->initiated_through|| !empty($request->initiated_through_comment)){
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Initiated Through')
            ->exists();
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Initiated Through';
        $history->previous =$lastDocument->initiated_through;
        $history->current =$root->initiated_through;
        $history->comment = $request->initiated_through_comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =    "Not Applicable";
        $history->change_from = $lastDocument->status;
        $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
         $history->save();
        }
        if ($lastDocument->initiated_if_other != $root->initiated_if_other || !empty($request->initiated_if_other_comment)){
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Other')
            ->exists();
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Other';
        $history->previous = $lastDocument->initiated_if_other;
        $history->current =$root->initiated_if_other;
        $history->comment = $request->initiated_if_other_comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =    "Not Applicable";
        $history->change_from = $lastDocument->status;
        $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';        
        $history->save();
        }
        //    if ($lastDocument->initiated_if_other != $root->initiated_if_other|| !empty($request->initiated_if_other_comment)){
        //     $history = new RootAuditTrial();
        // $history->root_id = $root->id;
        // $history->activity_type = 'Initiatedn If Other';
        // $history->previous = $lastDocument->initiated_if_other;
        // $history->current =$root->initiated_if_other;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $lastDocument->status;
        // $history->change_to =    "Not Applicable";
        // $history->change_from = $lastDocument->status;
        // $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';      
        // $history->save();
        // }
       
        if ($lastDocument->Type != $root->Type || !empty($request->Type_comment)) {
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Type')
            ->exists();
            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Type';
            $history->previous = $lastDocument->Type;
            $history->current = $root->Type;
            $history->comment = $request->Type_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';            
            $history->save();
        }
       

        if ($lastDocument->priority_level != $root->priority_level || !empty($request->priority_level_comment)){
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Priority Level')
            ->exists();
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Priority Level';
        $history->previous = $lastDocument->priority_level;
        $history->current =$root->priority_level;
        $history->comment = $request->priority_level_comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =    "Not Applicable";
        $history->change_from = $lastDocument->status;
        $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';        
        $history->save();
        }
        if ($lastDocument->department != $root->department || !empty($request->department_comment)){
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Department')
            ->exists();
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Department';
        $history->previous = $lastDocument->department;
        $history->current = $root->department;
        // $history->action =  "Not Applicable";
        $history->comment = $request->department_comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =    "Not Applicable";
        $history->change_from = $lastDocument->status;
        $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';        
        $history->save();
        }
        if ($lastDocument->description != $root->description || !empty($request->description_comment)){
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Description')
            ->exists();
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Description';
        $history->previous = $lastDocument->description;
        $history->current =$root->description;
        $history->comment = $request->description_comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =    "Not Applicable";
        $history->change_from = $lastDocument->status;
        $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';        
        $history->save();
        }
        if ($lastDocument->comments != $root->comments || !empty($request->comments_comment)) {
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Comments')
            ->exists();

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Comments';
            $history->previous = $lastDocument->comments;
            $history->current = $root->comments;
            $history->comment = $request->comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';            
            $history->save();
        }

        $previousAttachments = $lastDocument->root_cause_initial_attachment;
        $areIniAttachmentsSame = $previousAttachments == $root->root_cause_initial_attachment;
        
                if ($areIniAttachmentsSame != true) {
                    $history = new RootAuditTrial();
                    $history->root_id = $id;
                    $history->activity_type = 'Initial Attachment';
                    $history->previous = $previousAttachments;
                    $history->current = $root->root_cause_initial_attachment;
                    $history->comment =$request->root_cause_initial_attachment_comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = $lastDocument->status;
                    if ($previousAttachments) {
                        $history->action_name = "Update";
                    } else {
                        $history->action_name = "New";
                    }    
                    $history->save();
                }
        






        if ($lastDocument->related_url != $root->related_url || !empty($request->related_url_comment)){
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Related Url')
            ->exists();
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Related Url';
        $history->previous = $lastDocument->related_url;
        $history->current =$root->related_url;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =    "Not Applicable";
        $history->change_from = $lastDocument->status;
        $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';      
        $history->save();
        }
     
        if ($lastDocument->root_cause_methodology != $root->root_cause_methodology || !empty($request->root_cause_methodology_comment)){
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Root Cause Methodology')
            ->exists();
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Root Cause Methodology';
        $history->previous = $lastDocument->root_cause_methodology;
        $history->current =$root->root_cause_methodology;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =    "Not Applicable";
        $history->change_from = $lastDocument->status;
        $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';      
        $history->save();
        }

        if ($lastDocument->root_cause_description != $root->root_cause_description || !empty($request->root_cause_description_comment)){
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Root Cause Description')
            ->exists();
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Root Cause Description';
        $history->previous = $lastDocument->root_cause_description;
        $history->current =$root->root_cause_description;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =    "Not Applicable";
        $history->change_from = $lastDocument->status;
        $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';      
        $history->save();
        }
        if ($lastDocument->investigation_summary != $root->investigation_summary || !empty($request->investigation_summary_comment)){
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Investigation Summary')
            ->exists();
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Investigation Summary';
        $history->previous = $lastDocument->investigation_summary;
        $history->current =$root->investigation_summary;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =    "Not Applicable";
        $history->change_from = $lastDocument->status;
        $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';      
        $history->save();
        }

        if ($lastDocument->cft_comments_new != $root->cft_comments_new|| !empty($request->cft_comments_new_comment)){
            $lastDocumentAuditTrail = RootAuditTrial::where('root_id', $root->id)
            ->where('activity_type', 'Final Comments')
            ->exists();
            $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Final Comments';
        $history->previous = $lastDocument->cft_comments_new ;
        $history->current =$root->cft_comments_new;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =    "Not Applicable";
        $history->change_from = $lastDocument->status;
        $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';   
           
        $history->save();
        }



        $previousFinalAttachments = $lastDocument->cft_attchament_new;
        $areFinalAttachmentsSame = $previousFinalAttachments == $root->cft_attchament_new;
        
                if ($areFinalAttachmentsSame != true) {
                    $history = new RootAuditTrial();
                    $history->root_id = $id;
                    $history->activity_type = 'Final Attachment';
                    $history->previous = $previousFinalAttachments;
                    $history->current = $root->cft_attchament_new;
                    $history->comment = "NA";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = $lastDocument->status;
                    if ($previousFinalAttachments) {
                        $history->action_name = "Update";
                    } else {
                        $history->action_name = "New";
                    }    
                    $history->save();
                }
        
      
        // if ($lastDocument->due_date != $root->due_date || !empty($request->due_date_comment)) {

        //     $history = new RootAuditTrial();
        //     $history->root_id = $id;
        //     $history->activity_type = 'Due Date';
        //     $history->previous = $lastDocument->due_date;
        //     $history->current = $root->due_date;
        //     $history->comment = $request->due_date_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to =   "Not Applicable";
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = 'Update';
        //     $history->save();
        // }
       
        toastr()->success("Record is update Successfully");
        return back();
    }
    public function root_show($id)
    {
        $data = RootCauseAnalysis::find($id);
        if(empty($data)) {
            toastr()->error('Invalid ID.');
            return back();
        }
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_to)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
          return view('frontend.root-cause-analysis.root_cause_analysisView', compact(
            'data'
        ));
    }

    public function root_send_stage(Request $request, $id)
    {


        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $root = RootCauseAnalysis::find($id);
            $lastDocument =  RootCauseAnalysis::find($id);
           

            if ($root->stage == 1) {
                $root->stage = "2";
                $root->status = "Investigation in Progress";
                $root->acknowledge_by = Auth::user()->name;
                $root->acknowledge_on = Carbon::now()->format('d-M-Y');
                $root->acknowledge_comment = $request->comment;
                
                $history = new RootAuditTrial();
                $history->root_id = $id;
                $history->activity_type = 'Acknowledge By, Acknowledge On';
                if (is_null($lastDocument->acknowledge_by ) || $lastDocument->acknowledge_by  === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->acknowledge_by . ' , ' . $lastDocument->acknowledge_on;
                }
                $history->current = $root->acknowledge_by . ' , ' . $root->acknowledge_on;
                // $history->activity_type = 'Activity Log';
                // $history->previous = $lastDocument->acknowledge_by;
                // $history->current = $root->acknowledge_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_from = $lastDocument->status;
                $history->change_to = "Investigation in Progress";
                $history->action = 'Acknowledge';
                $history->stage='Acknowledge';
                if (is_null($lastDocument->acknowledge_by) || $lastDocument->acknowledge_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();


                $list = Helpers::getQAUserList($root->division_id);

                
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new RootAuditTrial();
                        $history->root_id = $id;
                        $history->activity_type = "Not Applicable";
                        $history->previous = "Not Applicable";
                        $history->current = "Not Applicable";
                        $history->action = 'Notification';
                        $history->comment = "";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = "Not Applicable";
                        $history->change_to = "Not Applicable";
                        $history->change_from = "Submitted to Supplier";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "QA/Deginee";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $root->division_id){
                        $email = Helpers::getQAEmail($u->user_id);
                        if ($email !== null) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $root , 'site'=>'RCA','history'=>"  Acknowledge "  ,'process' => ' Root Cause Analyses', 'comment' => $root->acknowledge_comment,'user'=> Auth::user()->name ],
                                function ($message) use ($email, $root ) {
                                    $message->to($email)
                                        ->subject("QMS Notification: Root Cause Analyses, Record #" . str_pad( $root->record, 4, '0', STR_PAD_LEFT) . " - Activity: Acknowledge Permormed");
                                }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                // } 
            }
         
                  
                
                $root->update();
                toastr()->success('Document Sent 2');
                return back();
            }
            if($root->stage == 2) {
                $root->stage = "3";
                $root->status = 'Pending QA Review';
                $root->submitted_by = Auth::user()->name;
                $root->submitted_on = Carbon::now()->format('d-M-Y');
                $root->submitted_comment = $request->comment;
                $root->update();

                $history = new RootAuditTrial();
                $history->root_id = $id;
                $history->activity_type = 'Submitted By, Submitted On';
                if (is_null($lastDocument->submitted_by) || $lastDocument->submitted_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->submitted_by . ' , ' . $lastDocument->submitted_on;
                }
                $history->current = $root->submitted_by . ' , ' . $root->submitted_on;
                // $history->activity_type = 'Activity Log';
                // $history->previous = $lastDocument->submitted_by;
                // $history->current = $root->submitted_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_from = $lastDocument->status;
                $history->change_to = "Pending QA Review";
                $history->action = 'Submit';
                $history->stage = 'Submited';
                if (is_null($lastDocument->submitted_by) || $lastDocument->submitted_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
                toastr()->success('Document Sent 3');
                return back();
            }
            
            if ($root->stage == 3) {
                $root->stage = "4";
                $root->status = "Closed - Done";
                $root->qA_review_complete_by = Auth::user()->name;
                $root->qA_review_complete_on = Carbon::now()->format('d-M-Y');
                $root->qA_review_complete_comment = $request->comment;
                
                $history = new RootAuditTrial();
                $history->root_id = $id;
                $history->activity_type = 'QA Review Complete By,QA Review Complete On';

                if (is_null($lastDocument->qA_review_complete_by) || $lastDocument->qA_review_complete_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->qA_review_complete_by . ' , ' . $lastDocument->qA_review_complete_on;
                }
                $history->current = $root->qA_review_complete_by. ' , ' . $root->qA_review_complete_on;
                // $history->activity_type = 'Activity Log';
                // $history->previous = $lastDocument->qA_review_complete_by;
                // $history->current = $root->qA_review_complete_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='QA Review Complete';
                $history->change_from = $lastDocument->status;
                $history->change_to = "Closed - Done";
                $history->action = 'QA Review Complete';
                if (is_null($lastDocument->qA_review_complete_by) || $lastDocument->qA_review_complete_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
                $list = Helpers::getInitiatorUserList($root->division_id);
                 $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new RootAuditTrial();
                        $history->root_id = $id;
                        $history->activity_type = "Not Applicable";
                        $history->previous = "Not Applicable";
                        $history->current = "Not Applicable";
                        $history->action = 'Notification';
                        $history->comment = "";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = "Not Applicable";
                        $history->change_to = "Not Applicable";
                        $history->change_from = "Closed - Done";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Initiator";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if (!empty($email)) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    // ['data' => $root],
                                    // function ($message) use ($email) {
                                    //     $message->to($email)
                                    //             ->subject("Document is Sent By " . Auth::user()->name);
                                    // }

                                    ['data' => $root , 'site'=>'RCA', 'history'=>"  QA Review Complete ",'process' => ' Root Cause Analyses', 'comment' => $root->qA_review_complete_comment,'user'=> Auth::user()->name ],
                                    function ($message) use ($email, $root ) {
                                        $message->to($email)
                                            ->subject("QMS Notification: Root Cause Analyses, Record #" . str_pad( $root->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Review Complete Permormed");
                                    }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    // }
                }
                $root->update();
                toastr()->success('Document Sent 5');
                return back();
            }
        }
        else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function root_Cancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $root = RootCauseAnalysis::find($id);
            $lastDocument =  RootCauseAnalysis::find($id);
            $data =  RootCauseAnalysis::find($id);
            if ($root->stage == 1){

                $root->stage = "0";
                $root->status = "Closed-Cancelled";
                $root->cancelled_by = Auth::user()->name;
                $root->cancelled_on = Carbon::now()->format('d-M-Y');
                $root->cancelled_comment = $request->comment;
                $history = new RootAuditTrial();
                $history->root_id = $id;
                if (is_null($lastDocument->cancelled_by ) || $lastDocument->cancelled_by  === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->cancelled_by  . ' , ' . $lastDocument->cancelled_on;
                }
                $history->current = $root->cancelled_by  . ' , ' . $root->cancelled_on;
                $history->activity_type = 'Cancelled By,Cancelled On';
                // $history->previous = $lastDocument->cancelled_by;
                // $history->current = $root->cancelled_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Cancelled ';
                $history->change_from = $lastDocument->status;
                $history->change_to = "Closed - Cancelled";
                $history->action = 'Cancel';
                if (is_null($lastDocument->cancelled_on ) || $lastDocument->cancelled_on  === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
    
                $list = Helpers::getQAUserList($root->division_id);

                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new RootAuditTrial();
                        $history->root_id = $id;
                        $history->activity_type = "Not Applicable";
                        $history->previous = "Not Applicable";
                        $history->current = "Not Applicable";
                        $history->action = 'Notification';
                        $history->comment = "";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = "Not Applicable";
                        $history->change_to = "Not Applicable";
                        $history->change_from = "Closed-Cancelled";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "QA";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                 foreach ($list as $u) {
                        // if($u->q_m_s_divisions_id == $root->division_id){
                            $email = Helpers::getQAEmail($u->user_id);
                            if ($email !== null) {
                                try {
                                    Mail::send(
                                        'mail.view-mail',
                                    //     ['data' => $root],
                                    // function ($message) use ($email) {
                                    //     $message->to($email)
                                    //         ->subject("Document sent 6 ".Auth::user()->name);
                                    // }
                                    ['data' => $root ,'site'=>'RCA', 'history'=>"  Cancel " ,'process' => ' Root Cause Analyses', 'comment' => $root->cancelled_comment,'user'=> Auth::user()->name ],
                                    function ($message) use ($email, $root ) {
                                        $message->to($email)
                                            ->subject("QMS Notification: Root Cause Analyses, Record #" . str_pad( $root->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Permormed");
                                    }
                                    );
                                } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            }
                    // } 
                }
                $root->update();
                $history = new RootCauseAnalysisHistory();
                $history->type = "Root Cause Analysis";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $root->stage;
                $history->status = $root->status;
                $history->save();
                toastr()->success('Document Sent 7');
                return back();
            }
           
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function root_reject(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $root = RootCauseAnalysis::find($id);
            $lastDocument =  RootCauseAnalysis::find($id);

            if ($root->stage == 3) {
                $root->stage = "2";
                $root->status = "Investigation in Progress";
                $root->moreinfo_by = Auth::user()->name;
                $root->moreinfo_on = Carbon::now()->format('d-M-Y');
                $root->moreinfo_comment = $request->comment;
                
                $history = new RootAuditTrial();
                $history->root_id = $id;
                if (is_null($lastDocument->moreinfo_by ) || $lastDocument->moreinfo_on  === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->moreinfo_by  . ' , ' . $lastDocument->moreinfo_on;
                }
                $history->activity_type = 'More information Required By,More information Required On';
                // $history->previous = $lastDocument->moreinfo_by;
                // $history->current = $root->moreinfo_by;
                $history->current = $root->moreinfo_by  . ' , ' . $root->moreinfo_on;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Pending QA Review';
                $history->change_from = $lastDocument->status;
                $history->change_to = "Investigation in Progress";
                $history->action = 'More Info Required';
                if (is_null($lastDocument->moreinfo_on ) || $lastDocument->moreinfo_on  === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $root->update();
                toastr()->success('Document Sent 8');
                return back();
              }
              } else {
               toastr()->error('E-signature Not match');
              return back();
            }
        }
        public function rootAuditTrial($id)
       {
        $audit = RootAuditTrial::where('root_id', $id)->orderByDESC('id')->paginate(5);
        $today = Carbon::now()->format('d-m-y');
        $document = RootCauseAnalysis::where('id', $id)->first();
        // dd($document);
        $document->originator = User::where('id', $document->initiator_id)->value('name');
        return view("frontend.root-cause-analysis.root-audit-trail", compact('audit', 'document', 'today'));
       }

    public function auditDetailsroot($id)
    {

        $detail = RootAuditTrial::find($id);

        $detail_data = RootAuditTrial::where('activity_type', $detail->activity_type)->where('root_id', $detail->root_id)->latest()->get();

        $doc = RootCauseAnalysis::where('id', $detail->root_id)->first();

        $doc->origiator_name = User::find($doc->initiator_id);
        return view("frontend.root-cause-analysis.root-audit-trial-inner", compact('detail', 'doc', 'detail_data'));
    }

    public static function singleReport($id)
    {    
        $data = RootCauseAnalysis::find($id);
        if (!empty($data)) {
            $data->originator_id = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.root-cause-analysis.singleReport', compact('data'))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
            $pdf->setPaper('A4');
            $pdf->render();
            $canvas = $pdf->getDomPDF()->getCanvas();
            $height = $canvas->get_height();
            $width = $canvas->get_width();
            $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');
            $canvas->page_text($width / 4, $height / 2, $data->status, null, 25, [0, 0, 0], 2, 6, -20);
            return $pdf->stream('Root-cause' . $id . '.pdf');
        }
        
    }

    public static function auditReport($id)
    {
        $doc = RootCauseAnalysis::find($id);
        if (!empty($doc)) {
            $doc->originator_id = User::where('id', $doc->initiator_id)->value('name');
            $data = RootAuditTrial::where('root_id', $id)->orderByDESC('id')->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.root-cause-analysis.auditReport', compact('data', 'doc'))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
            $pdf->setPaper('A4');
            $pdf->render();
            $canvas = $pdf->getDomPDF()->getCanvas();
            $height = $canvas->get_height();
            $width = $canvas->get_width();
            $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');
            $canvas->page_text($width / 4, $height / 2, $doc->status, null, 25, [0, 0, 0], 2, 6, -20);
            return $pdf->stream('Root-Audit' . $id . '.pdf');
        }
    }


    // public function notificationDetail($slug, $id){
    //     switch ($slug) {
               
    //         case 'RCA':
    //             $notification = RootAuditTrial::find($id);
    //             if($notification){
    //                 $rootCauseId = $notification->root_id;
    //                 $parentData = RootCauseAnalysis::where('id', $rootCauseId)->first();
        
    //                 $userId = explode(',', $notification->mailUserId);
    //                 $getName = User::whereIn('id', $userId)->get(['name', 'email']);
    //                 return view('frontend.supplier.notification_detail', compact('notification', 'getName', 'parentData'));
    //             }
    //             break;
            

    //         default:
    //             return $slug;
    //             break;
    //     }
    // }
}
