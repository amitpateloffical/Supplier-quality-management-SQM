<?php

namespace App\Http\Controllers\rcms;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\ActionItem;
use App\Models\AdditionalInformation;
use App\Models\CC;
use App\Models\RecordNumber;
use App\Models\CCStageHistory;
use App\Models\ChangeClosure;
use App\Models\Docdetail;
use App\Models\Evaluation;
use App\Models\Extension;
use App\Models\GroupComments;
use App\Models\QaApprovalComments;
use App\Models\Qareview;
use App\Models\RiskAssessment;
use App\Models\RcmDocHistory;
use App\Models\RiskLevelKeywords;
use App\Models\RoleGroup;
use App\Models\User;
// use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PDF;

class CCController extends Controller
{
    public function changecontrol()
    {

        $riskData = RiskLevelKeywords::all();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        return view('frontend.change-control.new-change-control', compact("riskData", "record_number", "due_date"));
    }

    public function index()
    {

        $document = CC::where('initiator_id', Auth::user()->id)->get();
        foreach ($document as $data) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
        }

        return view('frontend.change-control.CC', compact('document'));
    }

    public function create()
    {

        $riskData = RiskLevelKeywords::all();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $hod = User::get();
        $cft = User::get();
        $pre = CC::all();

        return view('frontend.change-control.new-change-control', compact("riskData", "record_number", "due_date","hod","cft","pre"));
    }

    public function store(Request $request)
    {
        $openState = new CC();
        $openState->form_type = "CC";
        $openState->division_id = $request->division_id;
        $openState->initiator_id = Auth::user()->id;
        $openState->record = DB::table('record_numbers')->value('counter') + 1;
        $openState->parent_id = $request->parent_id;
        $openState->parent_type = $request->parent_type;
        $openState->intiation_date = $request->intiation_date;
        $openState->due_date = $request->due_date;

        $openState->Initiator_Group = $request->Initiator_Group;
        $openState->initiator_group_code = $request->initiator_group_code;
        $openState->short_description = $request->short_description;
        $openState->assign_to = $request->assign_to;
        $openState->cft_reviewer_person = implode(',',$request->cft_reviewer_person);
        $openState->cft_reviewer = $request->cft_reviewer;
        $openState->nature_Change = $request->nature_Change;
        $openState->If_Others = $request->If_Others;
        $openState->Division_Code = $request->Division_Code;
        if (!empty($request->in_attachment)) {
            $files = [];
            if ($request->hasfile('in_attachment')) {
                foreach ($request->file('in_attachment') as $file) {
                    $name = "CC" . '-in_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->in_attachment = json_encode($files);
            // dd($openState->in_attachment);
        }
        $openState->current_practice = $request->current_practice;
        $openState->proposed_change = $request->proposed_change;
        $openState->reason_change = $request->reason_change;
        $openState->other_comment = $request->other_comment; 
        $openState->supervisor_comment = $request->supervisor_comment;
        $openState->type_chnage = $request->type_chnage;
        $openState->qa_review_comments = $request->qa_review_comments;
        
        $json_decode = json_encode($request->related_records);
        $openState->related_records = implode(',', $request->related_records);
        $qaHeadJson = json_encode($request->qa_head);
        if (!empty($request->qa_head)) {
            $files = [];
            if ($request->hasfile('qa_head')) {
                foreach ($request->file('qa_head') as $file) {
                    $name = "CC" . '-qa_head' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->qa_head = json_encode($files);
        }

        /**************** Evaluation **************/


        $openState->doc_change = $request->doc_change;
        $openState->severity_level1 = $request->severity_level1;
        $openState->initiated_through = $request->initiated_through;
        $openState->initiated_through_req = $request->initiated_through_req;
        $openState->repeat = $request->repeat;
        $openState->repeat_nature = $request->repeat_nature;
                   
        $openState->qa_eval_comments = $request->qa_eval_comments;
        $json_qa_eval_attach = json_encode($request->qa_eval_attach);
        if (!empty($request->qa_eval_attach)) {
            $files = [];
            if ($request->hasfile('qa_eval_attach')) {
                foreach ($request->file('qa_eval_attach') as $file) {
                    $name = "CC" . '-qa_eval_attach' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->qa_eval_attach = json_encode($files);
        }
        $openState->training_required = $request->training_required;
        $openState->train_comments = $request->train_comments;

        /********** Comments Section **********/
        $openState->cft_comments = $request->cft_comments; 
        $openState->qa_comments = $request->qa_comments;
        $openState->designee_comments = $request->designee_comments;
        $openState->Warehouse_comments = $request->Warehouse_comments;
        $openState->Engineering_comments = $request->Engineering_comments;
        $openState->Instrumentation_comments = $request->Instrumentation_comments;
        $openState->Validation_comments = $request->Validation_comments;
        $openState->Others_comments = $request->Others_comments;
        $openState->Group_comments = $request->Group_comments;
        $json_group_attachments = json_encode($request->group_attachments);
        if (!empty($request->group_attachments)) {
            $files = [];
            if ($request->hasfile('group_attachments')) {
                foreach ($request->file('group_attachments') as $file) {
                    $name = "CC" . '-group_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->group_attachments = json_encode($files);
        }
        if (!empty($request->cft_attchament)) {
            $files = [];
            if ($request->hasfile('cft_attchament')) {
                foreach ($request->file('cft_attchament') as $file) {
                    $name = "CC" . '-cft_attchament' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->cft_attchament = json_encode($files);
        }

        /************* Risk Assessment ************/
        $openState->risk_identification = $request->risk_identification;
        $openState->severity = $request->severity;
        $openState->Occurance = $request->Occurance;
        $openState->Detection = $request->Detection;
        $openState->RPN = $request->RPN;
        $openState->risk_evaluation = $request->risk_evaluation;
        $openState->migration_action = $request->migration_action;

        /**************** QA Approval *************/
        $openState->qa_appro_comments = $request->qa_appro_comments;
        $openState->feedback = $request->feedback;
        $json_tran_attach = json_encode($request->tran_attach);
        if (!empty($request->tran_attach)) {
            $files = [];
            if ($request->hasfile('tran_attach')) {
                foreach ($request->file('tran_attach') as $file) {
                    $name = "CC" . '-tran_attach' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->tran_attach = json_encode($files);
            
        }

        /**************** Closure Comments *************/
        $openState->qa_closure_comments = $request->qa_closure_comments;
        // $json_attach_list = json_encode($request->attach_list);
        // $openState->attach_list = json_encode($request->attach_list);
        if (!empty($request->attach_list)) {
            $files = [];
            if ($request->hasfile('attach_list')) {
                foreach ($request->file('attach_list') as $file) {
                    $name = "CC" . '-attach_list' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->attach_list = json_encode($files);
        }
        $openState->due_date_extension = $request->due_date_extension;
  
        $openState->status = 'Opened';
        $openState->stage = 1;
        $openState->save();
        // dd($openState);
 
        $counter = DB::table('record_numbers')->value('counter');
        $recordNumber = str_pad($counter, 5, '0', STR_PAD_LEFT);
        $newCounter = $counter + 1;
        DB::table('record_numbers')->update(['counter' => $newCounter]);


        $docdetail = new Docdetail();
        $docdetail->cc_id = $openState->id;
        if (!empty($request->serial_number)) {
            $docdetail->sno = serialize($request->serial_number);
        }
        if (!empty($request->current_doc_number)) {
            $docdetail->current_doc_no = serialize($request->current_doc_number);
        }
        if (!empty($request->current_version)) {
            $docdetail->current_version_no = serialize($request->current_version);
        }
        if (!empty($request->new_doc_number)) {
            $docdetail->new_doc_no = serialize($request->new_doc_number);
        }
        if (!empty($request->new_version)) {
            $docdetail->new_version_no = serialize($request->new_version);
        }
        $docdetail->save();

        $closure = new ChangeClosure();
        $closure->cc_id = $openState->id;

        if (!empty($request->serial_number)) {
            $closure->sno = serialize($request->serial_number);
        }
        if (!empty($request->affected_documents)) {
            $closure->affected_document = serialize($request->affected_documents);
        }
        if (!empty($request->document_name)) {
            $closure->doc_name = serialize($request->document_name);
        }
        if (!empty($request->document_no)) {
            $closure->doc_no = serialize($request->document_no);
        }
        if (!empty($request->version_no)) {
            $closure->version_no = serialize($request->version_no);
        }
        if (!empty($request->implementation_date)) {
            $closure->implementation_date = serialize($request->implementation_date);
        }
        if (!empty($request->new_document_no)) {
            $closure->new_doc_no = serialize($request->new_document_no);
        }
        if (!empty($request->new_version_no)) {
            $closure->new_version_no = serialize($request->new_version_no);
        }
        $closure->save();

        /******************* Audit Trail Code ********************/
        $history = new RcmDocHistory;
        $history->cc_id = $openState->id;
        $history->activity_type = 'Division';
        $history->previous = "Null";
        $history->current = Helpers::getDivisionName($request->division_id);
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        $history = new RcmDocHistory;
        $history->cc_id = $openState->id;
        $history->activity_type = 'Initiator';
        $history->previous = "Null";
        $history->current = Helpers::getInitiatorName($request->initiator_id);
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        $history = new RcmDocHistory;
        $history->cc_id = $openState->id;
        $history->activity_type = 'Initiation Date';
        $history->previous = "Null";
        $history->current = Helpers::getdateFormat($request->intiation_date);
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        if(!empty($request->assign_to)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Assigned To';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($request->assign_to);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        $cftPerson = json_encode($request->cft_reviewer_person);
        if(!empty($cftPerson) && is_array($cftPerson)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'CFT Reviewer';
            $history->previous = "Null";
            $history->current = $openState->cft_reviewer;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        if(!empty($request->cft_reviewer)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'CFT Reviewer Person';
            $history->previous = "Null";
            $history->current = $openState->cft_reviewer;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->due_date)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Due Date';
            $history->previous = "Null";
            $history->current = $openState->due_date;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        if(!empty($request->Initiator_Group)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Inititator Group';
            $history->previous = "Null";
            $history->current = Helpers::getFullDepartmentName($openState->Initiator_Group);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->initiator_group_code)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Inititator Group Code';
            $history->previous = "Null";
            $history->current = $request->initiator_group_code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->short_description)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Short Description';
            $history->previous = "Null";
            $history->current = $openState->short_description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->other_comment)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Current Practice';
            $history->previous = "Null";
            $history->current = $openState->other_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->severity_level1)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Severity Level';
            $history->previous = "Null";
            $history->current = $request->severity_level1;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->initiated_through)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Initiated Through';
            $history->previous = "Null";
            $history->current = $request->initiated_through;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }        

        if(!empty($request->initiated_through_req)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Initiated Through Request';
            $history->previous = "Null";
            $history->current = $request->initiated_through_req;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->repeat)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Repeat';
            $history->previous = "Null";
            $history->current = $request->repeat;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->repeat_nature)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Repeat Nature';
            $history->previous = "Null";
            $history->current = $request->repeat_nature;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->nature_Change)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Nature Of Change';
            $history->previous = "Null";
            $history->current = $request->nature_Change;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }      

        if(!empty($request->If_Others)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'If Others';
            $history->previous = "Null";
            $history->current = $request->If_Others;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->Division_Code)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Division Code';
            $history->previous = "Null";
            $history->current = $request->Division_Code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($openState->in_attachment)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Initial Attachment';
            $history->previous = "Null";
            $history->current = $openState->in_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        /************* Change Details ***********/

        if(!empty($request->current_practice)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Current Practice';
            $history->previous = "Null";
            $history->current = $request->current_practice;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->proposed_change)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Proposed Change';
            $history->previous = "Null";
            $history->current = $request->proposed_change;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->reason_change)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Reason for Chage';
            $history->previous = "Null";
            $history->current = $request->reason_change;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->other_comment)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Any Other Comments';
            $history->previous = "Null";
            $history->current = $request->other_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->supervisor_comment)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Supervisor Comments';
            $history->previous = "Null";
            $history->current = $request->supervisor_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        /************** QA Review *************/

        if(!empty($request->type_chnage)){
            $history = new RcmDocHistory;
            $history->cc_id = $request->id;
            $history->activity_type = 'Type of Change';
            $history->previous = "Null";
            $history->current = $request->type_chnage;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->qa_review_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $request->id;
            $history->activity_type = 'QA Review Comments';
            $history->previous = "Null";
            $history->current = $request->qa_review_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        $qaAttachment = json_encode($request->qa_head);
        if(!empty($qaAttachment) && is_array($qaAttachment)){
            $history = new RcmDocHistory;
            $history->cc_id = $request->id;
            $history->activity_type = 'QA Attachments';
            $history->previous = "Null";
            $history->current = $qaAttachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }  

        $relatedRecords = json_encode($request->related_records);
        if(!empty($relatedRecords) && is_array($relatedRecords)){
            $history = new RcmDocHistory;
            $history->cc_id = $request->id;
            $history->activity_type = 'Related Records';
            $history->previous = "Null";
            $history->current = $relatedRecords;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        /************ Evaluation Details ************/
        if(!empty($request->qa_eval_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $request->id;
            $history->activity_type = 'QA Evaluation Comments';
            $history->previous = "Null";
            $history->current = $request->qa_eval_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        $QaAttach = json_encode($request->qa_eval_attach);
        if(!empty($QaAttach) && is_array($QaAttach)){
            $history = new RcmDocHistory;
            $history->cc_id = $request->id;
            $history->activity_type = 'QA Evaluation Attachments';
            $history->previous = "Null";
            $history->current = $QaAttach;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->training_required)){
            $history = new RcmDocHistory;
            $history->cc_id = $request->id;
            $history->activity_type = 'Training Required';
            $history->previous = "Null";
            $history->current = $request->training_required;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        if(!empty($request->train_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $request->id;
            $history->activity_type = 'Training Comments';
            $history->previous = "Null";
            $history->current = $request->train_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        /********** Comments Tab ************/

        if(!empty($request->cft_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'CFT Comments';
            $history->previous = "Null";
            $history->current = $openState->cft_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        $cftAttachment = json_encode($request->cft_attchament);
        if(!empty($cftAttachment) && is_array($cftAttachment)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Feedback Attachment';
            $history->previous = "Null";
            $history->current = $cftAttachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->qa_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'QA Comments';
            $history->previous = "Null";
            $history->current = $openState->qa_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->designee_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'QA Head Designee Comments';
            $history->previous = "Null";
            $history->current = $openState->designee_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->Warehouse_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Warehouse Comments';
            $history->previous = "Null";
            $history->current = $openState->Warehouse_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->Engineering_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Engineering Comments';
            $history->previous = "Null";
            $history->current = $openState->Engineering_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->Instrumentation_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Instrumentation Comments';
            $history->previous = "Null";
            $history->current = $openState->Instrumentation_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->Validation_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Validation Comments';
            $history->previous = "Null";
            $history->current = $openState->Validation_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->Others_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Others Comments';
            $history->previous = "Null";
            $history->current = $openState->Others_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->Group_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Comments';
            $history->previous = "Null";
            $history->current = $openState->Group_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        $groupAttachment = json_encode($request->group_attachments);
        if(!empty($groupAttachment) && is_array($groupAttachment)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Group Attachments';
            $history->previous = "Null";
            $history->current = $groupAttachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        /************ Risk Assessment *************/
        if(!empty($request->risk_identification)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Risk Identification';
            $history->previous = "Null";
            $history->current = $openState->risk_identification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->severity)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Severity';
            $history->previous = "Null";
            $history->current = $openState->severity;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->Occurance)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Occurance';
            $history->previous = "Null";
            $history->current = $openState->Occurance;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->Detection)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Detection';
            $history->previous = "Null";
            $history->current = $openState->Detection;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->RPN)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'RPN';
            $history->previous = "Null";
            $history->current = $openState->RPN;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->risk_evaluation)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Risk Evaluation';
            $history->previous = "Null";
            $history->current = $openState->risk_evaluation;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->migration_action)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Migration Action';
            $history->previous = "Null";
            $history->current = $openState->migration_action;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        /************ QA Approval Comments **************/
        if(!empty($request->qa_appro_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'QA Approval Comments';
            $history->previous = "Null";
            $history->current = $openState->qa_appro_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->feedback)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Training Feedback';
            $history->previous = "Null";
            $history->current = $openState->feedback;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        $trainAttach = json_encode($request->tran_attch);
        if(!empty($trainAttach) && is_array($trainAttach)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Training Attachments';
            $history->previous = "Null";
            $history->current = $trainAttach;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        /************** Change Closure ****************/
        if(!empty($request->qa_closure_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'QA Closure Comments';
            $history->previous = "Null";
            $history->current = $openState->qa_closure_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        $attachmentList = json_encode($request->attach_list);
        if(!empty($attachmentList) && is_array($attachmentList)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'QA Closure Attachment';
            $history->previous = "Null";
            $history->current = $attachmentList;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->due_date_extension)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Due Date Extension';
            $history->previous = "Null";
            $history->current = $openState->due_date_extension;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        return redirect('rcms/qms-dashboard');
    }

    public function show($id)
    {

        $data = CC::find($id);
        $cftReviewerIds = explode(',', $data->cft_reviewer_person);
        $cc_lid = $data->id;
        $data->assign_to_name = User::where('id', $data->assign_to)->value('name');
        $docdetail = Docdetail::where('cc_id', $id)->first();
        $review = Qareview::where('cc_id', $id)->first();
        $evaluation = Evaluation::where('cc_id', $id)->first();
        $info = AdditionalInformation::where('cc_id', $id)->first();
        $comments = GroupComments::where('cc_id', $id)->first();
        $assessment = RiskAssessment::where('cc_id', $id)->first();
        $approcomments = QaApprovalComments::where('cc_id', $id)->first();
        $closure = ChangeClosure::where('cc_id', $id)->first();
        $hod = User::get();
        $cft = User::get();
        $cft_aff = [];
        if(!is_null($data->Microbiology_Person)){
            $cft_aff = explode(',', $data->Microbiology_Person);
        }
        $pre = CC::all();
        $due_date_extension = $data->due_date_extension;
    
        return view('frontend.change-control.CCview', compact(
            'data',
            'docdetail',
            'review',
            'evaluation',
            'info',
            'comments',
            'assessment',
            'approcomments',
            'closure',
            "hod",
            "cft",
            "cft_aff",
            "due_date_extension",
            "cc_lid",
            "pre",
            'cftReviewerIds'
        ));
    }

    public function update(Request $request, $id)
    {
   
        $lastDocument = CC::find($id);
        $openState = CC::find($id);
        $openState->initiator_id = Auth::user()->id;
        $openState->Initiator_Group = $request->Initiator_Group;
        $openState->initiator_group_code = $request->initiator_group_code;
        $openState->short_description = $request->short_description;
        $openState->assign_to = $request->assign_to;
        $openState->due_date = $request->due_date;
        $openState->cft_reviewer_person = implode(',',$request->cft_reviewer_person);
        $openState->cft_reviewer = $request->cft_reviewer;
        $openState->nature_Change = $request->nature_Change;
        $openState->If_Others = $request->If_Others;
        $openState->Division_Code = $request->Division_Code;
        if (!empty($request->in_attachment)) {
            $files = [];
            if ($request->hasfile('in_attachment')) {
                foreach ($request->file('in_attachment') as $file) {
                    $name = "CC" . '-in_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->in_attachment = json_encode($files);
        }
        $previousAttachments = $lastDocument->in_attachment;
        $areIniAttachmentsSame = $previousAttachments == $openState->in_attachment;

        $openState->current_practice = $request->current_practice;
        $openState->proposed_change = $request->proposed_change;
        $openState->reason_change = $request->reason_change;
        $openState->other_comment = $request->other_comment; 
        $openState->supervisor_comment = $request->supervisor_comment;
        $openState->type_chnage = $request->type_chnage;
        $openState->qa_review_comments = $request->qa_review_comments;
        
        $json_decode = json_encode($request->related_records);
        $openState->related_records = implode(',', $request->related_records);
        $qaHeadJson = json_encode($request->qa_head);
        if (!empty($request->qa_head)) {
            $files = [];
            if ($request->hasfile('qa_head')) {
                foreach ($request->file('qa_head') as $file) {
                    $name = "CC" . '-qa_head' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->qa_head = json_encode($files);
        }
        $previousQaHeadAttachments = $lastDocument->qa_head;
        $areQAHeadAttachmentsSame = $previousQaHeadAttachments == $openState->qa_head;

        /**************** Evaluation **************/


        $openState->doc_change = $request->doc_change;
        $openState->severity_level1 = $request->severity_level1;
        $openState->initiated_through = $request->initiated_through;
        $openState->initiated_through_req = $request->initiated_through_req;
        $openState->repeat = $request->repeat;
        $openState->repeat_nature = $request->repeat_nature;
                   
        $openState->qa_eval_comments = $request->qa_eval_comments;
        $json_qa_eval_attach = json_encode($request->qa_eval_attach);
        if (!empty($request->qa_eval_attach)) {
            $files = [];
            if ($request->hasfile('qa_eval_attach')) {
                foreach ($request->file('qa_eval_attach') as $file) {
                    $name = "CC" . '-qa_eval_attach' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->qa_eval_attach = json_encode($files);
        }
        $previousQaEvalAttachments = $lastDocument->qa_eval_attach;
        $areQaEvalAttachmentsSame = $previousQaEvalAttachments == $openState->qa_eval_attach;

        $openState->training_required = $request->training_required;
        $openState->train_comments = $request->train_comments;

        /********** Comments Section **********/
        $openState->cft_comments = $request->cft_comments; 
        $openState->qa_comments = $request->qa_comments;
        $openState->designee_comments = $request->designee_comments;
        $openState->Warehouse_comments = $request->Warehouse_comments;
        $openState->Engineering_comments = $request->Engineering_comments;
        $openState->Instrumentation_comments = $request->Instrumentation_comments;
        $openState->Validation_comments = $request->Validation_comments;
        $openState->Others_comments = $request->Others_comments;
        $openState->Group_comments = $request->Group_comments;
        if (!empty($request->group_attachments)) {
            $files = [];
            if ($request->hasfile('group_attachments')) {
                foreach ($request->file('group_attachments') as $file) {
                    $name = "CC" . '-group_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->group_attachments = json_encode($files);
        }
        $previousGroupAttachments = $lastDocument->group_attachments;
        $areGroupAttachmentsSame = $previousGroupAttachments == $openState->group_attachments;

        if (!empty($request->cft_attchament)) {
            $files = [];
            if ($request->hasfile('cft_attchament')) {
                foreach ($request->file('cft_attchament') as $file) {
                    $name = "CC" . '-cft_attchament' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->cft_attchament = json_encode($files);
        }
        $previousCftAttachments = $lastDocument->cft_attchament;
        $areCftAttachmentsSame = $previousCftAttachments == $openState->cft_attchament;

        /************* Risk Assessment ************/
        $openState->risk_identification = $request->risk_identification;
        $openState->severity = $request->severity;
        $openState->Occurance = $request->Occurance;
        $openState->Detection = $request->Detection;
        $openState->RPN = $request->RPN;
        $openState->risk_evaluation = $request->risk_evaluation;
        $openState->migration_action = $request->migration_action;

        /**************** QA Approval *************/
        $openState->qa_appro_comments = $request->qa_appro_comments;
        $openState->feedback = $request->feedback;
        $json_tran_attach = json_encode($request->tran_attach);
        if (!empty($request->tran_attach)) {
            $files = [];
            if ($request->hasfile('tran_attach')) {
                foreach ($request->file('tran_attach') as $file) {
                    $name = "CC" . '-tran_attach' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->tran_attach = json_encode($files);
        }
        $previousQaApprovalAttachments = $lastDocument->tran_attach;
        $areQaApprovalAttachmentsSame = $previousQaApprovalAttachments == $openState->tran_attach;

        /**************** Closure Comments *************/
        $openState->qa_closure_comments = $request->qa_closure_comments;
        if (!empty($request->attach_list)) {
            $files = [];
            if ($request->hasfile('attach_list')) {
                foreach ($request->file('attach_list') as $file) {
                    $name = "CC" . '-attach_list' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->attach_list = json_encode($files);
        }
        $previousClosureAttachments = $lastDocument->attach_list;
        $areClosureAttachmentsSame = $previousClosureAttachments == $openState->attach_list;

        $openState->due_date_extension = $request->due_date_extension;

        // $openState->Microbiology = $request->Microbiology;    
        // $openState->goup_review = $request->goup_review;
        // $openState->Production = $request->Production;
        // $openState->Production_Person = $request->Production_Person;
        // $openState->Quality_Approver = $request->Quality_Approver;
        // $openState->Quality_Approver_Person = $request->Quality_Approver_Person;
        // $openState->bd_domestic = $request->bd_domestic;
        // $openState->Bd_Person = $request->Bd_Person;
        // $openState->additional_attachments = json_encode($request->additional_attachments);
        // $openState->effective_check = $request->effective_check;
        // $openState->effective_check_date = $request->effective_check_date;
        // $openState->Effectiveness_checker = $request->Effectiveness_checker;
        // $openState->effective_check_plan = $request->effective_check_plan;
        
        $openState->update();

        /************** Change Closure Grid *************/
        $lastDocument = ChangeClosure::where('cc_id', $id)->first();
        $closure = ChangeClosure::where('cc_id', $id)->first();

        $closure->cc_id = $openState->id;

        if (!empty($request->serial_number)) {
            $closure->sno = serialize($request->serial_number);
        }
        if (!empty($request->affected_documents)) {
            $closure->affected_document = serialize($request->affected_documents);
        }
        if (!empty($request->document_name)) {
            $closure->doc_name = serialize($request->document_name);
        }
        if (!empty($request->document_no)) {
            $closure->doc_no = serialize($request->document_no);
        }
        if (!empty($request->version_no)) {
            $closure->version_no = serialize($request->version_no);
        }
        if (!empty($request->implementation_date)) {
            $closure->implementation_date = serialize($request->implementation_date);
        }
        if (!empty($request->new_document_no)) {
            $closure->new_doc_no = serialize($request->new_document_no);
        }
        if (!empty($request->new_version_no)) {
            $closure->new_version_no = serialize($request->new_version_no);
        }
        $closure->update();        

        $lastdocdetail = Docdetail::where('cc_id', $id)->first();
        $docdetail = Docdetail::where('cc_id', $id)->first();
        if (!empty($request->serial_number)) {
            $docdetail->sno = serialize($request->serial_number);
        }
        if (!empty($request->current_doc_number)) {
            $docdetail->current_doc_no = serialize($request->current_doc_number);
        }
        if (!empty($request->current_version)) {
            $docdetail->current_version_no = serialize($request->current_version);
        }
        if (!empty($request->new_doc_number)) {
            $docdetail->new_doc_no = serialize($request->new_doc_number);
        }
        if (!empty($request->new_version)) {
            $docdetail->new_version_no = serialize($request->new_version);
        }
        $docdetail->update();


        /******** Audit Trail Code ********/
        if ($lastDocument->assign_to != $request->assign_to) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Assign To')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Assign To';
            $history->previous = Helpers::getInitiatorName($lastDocument->assign_to);
            $history->current = Helpers::getInitiatorName($request->assign_to);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->cft_reviewer != $request->cft_reviewer) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'CFT Reviewer')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'CFT Reviewer';
            $history->previous = $lastDocument->cft_reviewer;
            $history->current = $request->cft_reviewer;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        // if ($lastDocument->cft_reviewer_person != $request->cft_reviewer_person) {
        //     $existingHistory = RcmDocHistory::where('cc_id', $id)
        //     ->where('activity_type', 'CFT Reviewer Person')
        //     ->exists();
        //     $history = new RcmDocHistory;
        //     $history->cc_id = $id;
        //     $history->activity_type = 'CFT Reviewer Person';
        //     $history->previous = $lastDocument->cft_reviewer_person;
        //     $history->current = $request->cft_reviewer_person;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to =   "Not Applicable";
        //     $history->change_from = $lastDocument->status;
        //     if ($existingHistory) {
        //         $history->action_name = "Update";
        //     } else {
        //         $history->action_name = "New";
        //     }
        //     $history->save();
        // }

        if ($lastDocument->short_description != $request->short_description) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Short Description')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Short Description';
            $history->previous = $lastDocument->short_description;
            $history->current = $request->short_description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->Initiator_Group != $request->Initiator_Group) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Inititator Group')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Inititator Group';
            $history->previous = Helpers::getFullDepartmentName($lastDocument->Initiator_Group);
            $history->current = Helpers::getFullDepartmentName($request->Initiator_Group);
            $history->comment = "Not Appliccable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        
        if ($lastDocument->initiator_group_code != $request->initiator_group_code) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Assign To')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Assigned To';
            $history->previous = $lastDocument->initiator_group_code;
            $history->current = $request->initiator_group_code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->severity_level1 != $request->severity_level1) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Severity Level')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Severity Level';
            $history->previous = $lastDocument->severity_level1;
            $history->current = $request->severity_level1;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->initiated_through != $request->initiated_through) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Initiated Through')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Initiated Through';
            $history->previous = $lastDocument->initiated_through;
            $history->current = $request->initiated_through;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->initiated_through_req != $request->initiated_through_req) {

            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Initiated Through Request')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Initiated Through Request';
            $history->previous = $lastDocument->initiated_through_req;
            $history->current = $request->initiated_through_req;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->repeat != $request->repeat) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Repeat')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Repeat';
            $history->previous = $lastDocument->repeat;
            $history->current = $request->repeat;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->repeat_nature != $request->repeat_nature) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Repeat Nature')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Repeat Nature';
            $history->previous = $lastDocument->repeat_nature;
            $history->current = $request->repeat_nature;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->nature_Change != $request->nature_Change) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Nature Of Change')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Nature Of Change';
            $history->previous = $lastDocument->nature_Change;
            $history->current = $request->nature_Change;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->If_Others != $request->If_Others) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'If Others')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'If Others';
            $history->previous = $lastDocument->If_Others;
            $history->current = $request->If_Others;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->Division_Code != $request->Division_Code) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Division Code')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Division Code';
            $history->previous = $lastDocument->Division_Code;
            $history->current = $request->Division_Code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($areIniAttachmentsSame != true) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Initial Attachment';
            $history->previous = $previousAttachments;
            $history->current = $openState->in_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->due_date != $request->due_date) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Due Date')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Due Date';
            $history->previous = $lastDocument->due_date;
            $history->current = $request->due_date;
            $history->comment = $request->due_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
       
        if ($lastDocument->Division_Code != $request->Division_Code) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Division Code')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Division Code';
            $history->previous = $lastDocument->Division_Code;
            $history->current = $request->Division_Code;
            $history->comment = $request->Division_Code_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        /********** Change Details **********/

        if ($lastDocument->current_practice != $request->current_practice) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Current Practice')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Current Practice';
            $history->previous = $lastDocument->current_practice;
            $history->current = $request->current_practice;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->proposed_change != $request->proposed_change) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Proposed Change')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Proposed Change';
            $history->previous = $lastDocument->proposed_change;
            $history->current = $request->proposed_change;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        if ($lastDocument->reason_change != $request->reason_change) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Reason for Change')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Reason for Change';
            $history->previous = $lastDocument->proposed_change;
            $history->current = $request->proposed_change;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        if ($lastDocument->other_comment != $request->other_comment) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Any Other Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Any Other Comments';
            $history->previous = $lastDocument->other_comment;
            $history->current = $request->other_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->supervisor_comment != $request->other_comment) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Supervisor Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Supervisor Comments';
            $history->previous = $lastDocument->supervisor_comment;
            $history->current = $request->supervisor_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        /********** QA Review *********/
        if ($lastDocument->type_chnage != $request->type_chnage) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Type of Change')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Type of Change';
            $history->previous = $lastDocument->type_chnage;
            $history->current = $request->type_chnage;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->qa_review_comments != $request->qa_review_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'QA Review Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Review Comments';
            $history->previous = $lastDocument->qa_review_comments;
            $history->current = $request->qa_review_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        // if ($lastDocument->related_records != $related_records) {
        //     $history = new RcmDocHistory;
        //     $history->cc_id = $id;
        //     $history->activity_type = 'Related Records';
        //     $history->previous = $lastDocument->related_records;
        //     $history->current = $request->related_records;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to =   "Not Applicable";
        //     $history->change_from = $lastDocument->status;
        //     if ($existingHistory) {
            //     $history->action_name = "Update";
            // } else {
            //     $history->action_name = "New";
            // }
        //     $history->save();
        // }

        if ($areQAHeadAttachmentsSame != true) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Attachments';
            $history->previous = $previousQaHeadAttachments;
            $history->current = $openState->qa_head;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        /********** Evaluation ********/

        if ($lastDocument->qa_eval_comments != $request->qa_eval_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'QA Evaluation Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Evaluation Comments';
            $history->previous = $lastDocument->qa_eval_comments;
            $history->current = $request->qa_eval_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($areQaEvalAttachmentsSame != true) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'QA Evaluation Attachments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Evaluation Attachments';
            $history->previous = $previousQaEvalAttachments;
            $history->current = $openState->qa_eval_attach;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->training_required != $request->training_required) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Training Required')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Training Required';
            $history->previous = $lastDocument->training_required;
            $history->current = $request->training_required;
            $history->comment = "Not Appllicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->train_comments != $request->train_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Training Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Training Comments';
            $history->previous = $lastDocument->train_comments;
            $history->current = $request->train_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
    
        /*********** Comments Tab ***********/
        if ($lastDocument->cft_comments != $request->cft_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Feedback')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Feedback';
            $history->previous = $lastDocument->cft_comments;
            $history->current = $request->cft_comments;
            $history->comment = "Not Appplicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($areCftAttachmentsSame != true) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'CFT Atttachments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'CFT Atttachments';
            $history->previous = $previousCftAttachments;
            $history->current = $openState->cft_attchament;
            $history->comment = "Not Appplicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->qa_comments != $request->qa_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'QA Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Comments';
            $history->previous = $lastDocument->qa_comments;
            $history->current = $request->qa_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->designee_comments != $request->designee_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'QA Head Designee Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Head Designee Comments';
            $history->previous = $lastDocument->designee_comments;
            $history->current = $request->designee_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->Warehouse_comments != $request->Warehouse_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Warehouse Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Warehouse Comments';
            $history->previous = $lastDocument->Warehouse_comments;
            $history->current = $request->Warehouse_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->Engineering_comments != $request->Engineering_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Engineering Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Engineering Comments';
            $history->previous = $lastDocument->Engineering_comments;
            $history->current = $request->Engineering_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->Instrumentation_comments != $request->Instrumentation_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Instrumentation Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Instrumentation Comments';
            $history->previous = $lastDocument->Instrumentation_comments;
            $history->current = $request->Instrumentation_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->Validation_comments != $request->Validation_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Validation Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Validation Comments';
            $history->previous = $lastDocument->Validation_comments;
            $history->current = $request->Validation_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->Others_comments != $request->Others_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Others Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Others Comments';
            $history->previous = $lastDocument->Others_comments;
            $history->current = $request->Others_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->Group_comments != $request->Group_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Comments';
            $history->previous = $lastDocument->Group_comments;
            $history->current = $request->Group_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        
        if ($areGroupAttachmentsSame != null) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Attachments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Attachments';
            $history->previous = $previousGroupAttachments;
            $history->current = $openState->group_attachments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        /*********** Risk Assessment **********/

        if ($lastDocument->risk_identification != $request->risk_identification) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Risk Identification')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Risk Identification';
            $history->previous = $lastDocument->risk_identification;
            $history->current = $request->risk_identification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->severity != $request->severity) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Severity')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Severity';
            $history->previous = $lastDocument->severity;
            $history->current = $request->severity;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->Occurance != $request->Occurance) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Occurance')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Occurance';
            $history->previous = $lastDocument->Occurance;
            $history->current = $request->Occurance;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();            
        }

        if ($lastDocument->Detection != $request->Detection) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Detection')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Detection';
            $history->previous = $lastDocument->Detection;
            $history->current = $request->Detection;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->RPN != $request->RPN || !empty($request->RPN_comment)) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'RPN')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'RPN';
            $history->previous = $lastDocument->RPN;
            $history->current = $request->RPN;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->risk_evaluation != $request->risk_evaluation) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Risk Evaluation')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Risk Evaluation';
            $history->previous = $lastDocument->risk_evaluation;
            $history->current = $request->risk_evaluation;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->migration_action != $request->migration_action) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Migration Action')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Migration Action';
            $history->previous = $lastDocument->migration_action;
            $history->current = $request->migration_action;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        /********* QA Approval *********/
        if ($lastDocument->qa_appro_comments != $request->qa_appro_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'QA Approval Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Approval Comments';
            $history->previous = $lastDocument->qa_appro_comments;
            $history->current = $request->qa_appro_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->feedback != $request->feedback) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Training Feedback')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Training Feedback';
            $history->previous = $lastDocument->feedback;
            $history->current = $request->feedback;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($areQaApprovalAttachmentsSame != true) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Training Attachments';
            $history->previous = $previousQaApprovalAttachments;
            $history->current = $openState->tran_attach;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        /******** Change Closure ***********/
        if ($lastDocument->qa_closure_comments != $request->qa_closure_comments) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'QA Closure Comments')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Closure Comments';
            $history->previous = $lastDocument->qa_closure_comments;
            $history->current = $request->qa_closure_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->due_date_extension != $request->due_date_extension) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'Due Date Extension Justification')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = $lastDocument->due_date_extension;
            $history->current = $request->due_date_extension;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($areClosureAttachmentsSame != true) {
            $existingHistory = RcmDocHistory::where('cc_id', $id)
            ->where('activity_type', 'QA Closure Attachment')
            ->exists();
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Closure Attachment';
            $history->previous = $previousClosureAttachments;
            $history->current = $openState->attach_list;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
       toastr()->success('Record is updated Successfully');
        return back();
    }


    public function destroy($id)
    {
    }

    public function stageChange(Request $request, $id){
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = CC::find($id);
            $lastDocument = CC::find($id);
            
            if ($changeControl->stage == 1) {
                $changeControl->stage = "2";
                $changeControl->status = "HOD Review";
                $changeControl->submitted_by = Auth::user()->name;
                $changeControl->submitted_on = Carbon::now()->format('d-M-Y');
                $changeControl->submitted_comment = $request->comment;

                $history = new RcmDocHistory;
                $history->cc_id = $id;
                $history->activity_type = 'Submited By, Submited On';
                if (is_null($lastDocument->submitted_by) || $lastDocument->submitted_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->submitted_by . ' , ' . $lastDocument->submitted_on;
                }
                $history->current = $changeControl->submitted_by . ' , ' . $changeControl->submitted_on;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_from = $lastDocument->status;
                $history->change_to = "HOD Review";
                $history->action = 'Submit';
                if (is_null($lastDocument->submitted_by) || $lastDocument->submitted_on === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
                
                //  $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {
                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Send By".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      } 
                //   }
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();

            }
            if ($changeControl->stage == 2) {
                $changeControl->stage = "3";
                $changeControl->status = "Pending CFT/SME/QA Review";
                $changeControl->hod_review_completed_by = Auth::user()->name;
                $changeControl->hod_review_completed_on = Carbon::now()->format('d-M-Y');
                $changeControl->hod_review_completed_comment = $request->comment;

                $history = new RcmDocHistory;
                $history->cc_id = $id;

                $history->activity_type = 'HOD Review Complete By, HOD Review Complete On';
                if (is_null($lastDocument->hod_review_completed_by) || $lastDocument->hod_review_completed_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->hod_review_completed_by . ' , ' . $lastDocument->hod_review_completed_on;
                }
                $history->current = $changeControl->hod_review_completed_by . ' , ' . $changeControl->hod_review_completed_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_from = $lastDocument->status;
                $history->change_to = "Pending CFT/SME/QA Review";
                $history->action = 'HOD review Complete';
                if (is_null($lastDocument->hod_review_completed_by) || $lastDocument->hod_review_completed_on === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                //  $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {
                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Send By".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      } 
                //   }
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();

            }

            if ($changeControl->stage == 3) {
                $changeControl->stage = "4";
                $changeControl->status = "CFT/SME/QA Review";
                $changeControl->cft_review_by = Auth::user()->name;
                $changeControl->cft_review_on = Carbon::now()->format('d-M-Y');
                $changeControl->cft_review_comment = $request->comment;

                $history = new RcmDocHistory;
                $history->cc_id = $id;
                
                $history->activity_type = 'CFT/SME/QA Review By, CFT/SME/QA Review On';
                if (is_null($lastDocument->cft_review_by) || $lastDocument->cft_review_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->cft_review_by . ' , ' . $lastDocument->cft_review_on;
                }
                $history->current = $changeControl->cft_review_by . ' , ' . $changeControl->cft_review_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_from = $lastDocument->status;
                $history->change_to = "CFT/SME/QA Review";
                $history->action = 'Send to CFT/SME/QA Review';

                if (is_null($lastDocument->cft_review_by) || $lastDocument->cft_review_on === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                //  $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {
                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Send By".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      } 
                //   }
                $changeControl->update();
                // Helpers::hodMail($changeControl);
                toastr()->success('Document Sent');
                return back();

            }
            if ($changeControl->stage == 4) {
                $changeControl->stage = "5";
                $changeControl->status = "Pending Change Implementation";
                $changeControl->QA_review_completed_by = Auth::user()->name;
                $changeControl->QA_review_completed_on = Carbon::now()->format('d-M-Y');
                $changeControl->QA_review_completed_comment = $request->comment;

                $history = new RcmDocHistory;
                $history->cc_id = $id;
                
                $history->activity_type = 'Review Completed By, Review Completed On';
                if (is_null($lastDocument->QA_review_completed_by) || $lastDocument->QA_review_completed_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->QA_review_completed_by . ' , ' . $lastDocument->QA_review_completed_on;
                }
                $history->current = $changeControl->QA_review_completed_by . ' , ' . $changeControl->QA_review_completed_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_from = $lastDocument->status;
                $history->change_to = "Pending Change Implementation";
                $history->action = 'Review Completed';

                if (is_null($lastDocument->QA_review_completed_by) || $lastDocument->QA_review_completed_on === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                //  $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {
                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Send By".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      } 
                //   }
                $changeControl->update();
                // Helpers::hodMail($changeControl);
                toastr()->success('Document Sent');
                return back();

            }
            if ($changeControl->stage == 5) {
                $changeControl->stage = "6";
                $changeControl->status = "Closed - Done";
                $changeControl->implemented_by = Auth::user()->name;
                $changeControl->implemented_on = Carbon::now()->format('d-M-Y');
                $changeControl->implemented_comment = $request->comment;

                $history = new RcmDocHistory;
                $history->cc_id = $id;

                $history->activity_type = 'Implemented By, Implemented On';
                if (is_null($lastDocument->implemented_by) || $lastDocument->implemented_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->implemented_by . ' , ' . $lastDocument->implemented_on;
                }
                $history->current = $changeControl->implemented_by . ' , ' . $changeControl->implemented_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_from = $lastDocument->status;
                $history->change_to = "Closed - Done";
                $history->action = 'Implemented';

                if (is_null($lastDocument->implemented_by) || $lastDocument->implemented_on === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                //  $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {
                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Send By".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      } 
                //   }
                $changeControl->update();
                // Helpers::hodMail($changeControl);
                toastr()->success('Document Sent');
                return back();

            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function stagereject(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = CC::find($id);
            $openState = CC::find($id);

            if ($changeControl->stage == 2) {
                $changeControl->stage = "1";
                $changeControl->status = "Opened";
                $changeControl->sent_to_opened_by = Auth::user()->name;
                $changeControl->sent_to_opened_on = Carbon::now()->format('d-M-Y');
                $changeControl->sent_to_opened_comment = $request->comment;

                $history = new RcmDocHistory;
                $history->cc_id = $id;
                
                $history->activity_type = 'Request More Information By, Request More Information On';
                if (is_null($openState->sent_to_opened_by) || $openState->sent_to_opened_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $openState->sent_to_opened_by . ' , ' . $openState->sent_to_opened_on;
                }
                $history->current = $changeControl->sent_to_opened_by . ' , ' . $changeControl->sent_to_opened_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $openState->status;
                $history->change_from = $openState->status;
                $history->change_to = "Opened";
                $history->action = 'Request More Information';

                if (is_null($openState->sent_to_opened_by) || $openState->sent_to_opened_on === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                //  $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {
                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Send By".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      } 
                //   }
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = "2";
                $changeControl->status = "HOD Review";
                $changeControl->requested_to_hod_by = Auth::user()->name;
                $changeControl->requested_to_hod_on = Carbon::now()->format('d-M-Y');
                $changeControl->requested_to_hod_comment = $request->comment;

                $history = new RcmDocHistory;
                $history->cc_id = $id;
                
                $history->activity_type = 'Request More Information By, Request More Information On';
                if (is_null($openState->requested_to_hod_by) || $openState->requested_to_hod_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $openState->requested_to_hod_by . ' , ' . $openState->requested_to_hod_on;
                }
                $history->current = $changeControl->requested_to_hod_by . ' , ' . $changeControl->requested_to_hod_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $openState->status;
                $history->change_from = $openState->status;
                $history->change_to = "HOD Review";
                $history->action = 'Request More Information';

                if (is_null($openState->requested_to_hod_by) || $openState->requested_to_hod_on === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                
                $history->save();
                //  $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {
                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Send By".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      } 
                //   }
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 4) {
                $changeControl->stage = "2";
                $changeControl->status = "HOD Review";
                $changeControl->requested_to_hod_by = Auth::user()->name;
                $changeControl->requested_to_hod_on = Carbon::now()->format('d-M-Y');
                $changeControl->requested_to_hod_comment = $request->comment;

                $history = new RcmDocHistory;
                $history->cc_id = $id;
                
                $history->activity_type = 'Request More Information By, Request More Information On';
                if (is_null($openState->requested_to_hod_by) || $openState->requested_to_hod_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $openState->requested_to_hod_by . ' , ' . $openState->requested_to_hod_on;
                }
                $history->current = $changeControl->requested_to_hod_by . ' , ' . $changeControl->requested_to_hod_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $openState->status;
                $history->change_from = $openState->status;
                $history->change_to = "HOD Review";
                $history->action = 'Request More Information';

                if (is_null($openState->requested_to_hod_by) || $openState->requested_to_hod_on === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                //  $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {
                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Send By".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      } 
                //   }
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


    public function stageCFTnotReq(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = CC::find($id);
            $lastDocument = CC::find($id);
            $openState = CC::find($id);

            $changeControl->stage = "5";
            $changeControl->status = "Pending Change Implementation";
            $changeControl->cftNot_required_by = Auth::user()->name;
            $changeControl->cftNot_required_on = Carbon::now()->format('d-M-Y');
            $changeControl->cftNot_required_comment = $request->comment;

            $history = new RcmDocHistory;
            $history->cc_id = $id;
            
            $history->activity_type = 'CFT Review Not Required By, CFT Review Not Required On';
            if (is_null($openState->cftNot_required_by) || $openState->cftNot_required_by === '') {
                $history->previous = "";
            } else {
                $history->previous = $openState->cftNot_required_by . ' , ' . $openState->cftNot_required_on;
            }
            $history->current = $changeControl->cftNot_required_by . ' , ' . $changeControl->cftNot_required_on;

            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->stage = 'Pending Change Implementation';
            $history->change_from = $openState->status;
            $history->change_to = "Pending Change Implementation";
            $history->action = 'CFT/SME/QA Review Not Required';

            if (is_null($openState->cftNot_required_by) || $openState->cftNot_required_on === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }

            $history->save();
            $changeControl->update();
            toastr()->success('Document Sent');
            return back();
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function stagecancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = CC::find($id);
            $openState = CC::find($id);
            $lastDocument = CC::find($id);
 
            $changeControl->stage = "0";
            $changeControl->status = "Closed- Cancelled";
            $changeControl->cancelled_by = Auth::user()->name;
            $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
            $changeControl->cancelled_comment = $request->comment;
            $changeControl->update();

            $history = new RcmDocHistory;
            $history->cc_id = $id;
            
            $history->activity_type = 'Closed - Cancelled By, Closed - Cancelled On';
            if (is_null($openState->cancelled_by) || $openState->cancelled_by === '') {
                $history->previous = "";
            } else {
                $history->previous = $openState->cancelled_by . ' , ' . $openState->cancelled_on;
            }
            $history->current = $changeControl->cancelled_by . ' , ' . $changeControl->cancelled_on;

            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->stage = 'Opened';
            $history->change_from = $openState->status;
            $history->change_to = "Closed - Cancelled";
            $history->action = 'Cancel';

            if (is_null($openState->cancelled_by) || $openState->cancelled_on === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }

            $history->save();
            toastr()->success('Document Sent');
            return back();
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function child(Request $request,$id){
        // return "hiii";
        $cc = CC::find($id);
        $parent_name = "CC";
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');

        $parent_data = CC::where('id', $id)->select('record','division_id','initiator_id','short_description')->first();
        $parent_data1 = CC::select('record','division_id','initiator_id','id')->get();
        $parent_record = CC::where('id', $id)->value('record');
        $parent_record = str_pad($parent_record, 4, '0', STR_PAD_LEFT);
        $parent_division_id = CC::where('id', $id)->value('division_id');
        $parent_initiator_id = CC::where('id', $id)->value('initiator_id');
        $parent_intiation_date = CC::where('id', $id)->value('intiation_date');
        $parent_short_description = CC::where('id', $id)->value('short_description');
        $old_record = CC::select('id', 'division_id', 'record')->get();

        if($request->revision == "Action-Item"){
            $cc->originator = User::where('id',$cc->initiator_id)->value('name');
            return view('frontend.forms.action-item',compact('parent_record','parent_name','record_number','cc','parent_data','parent_data1','parent_short_description','parent_initiator_id','parent_intiation_date','parent_division_id','due_date','old_record'));
        }
        if($request->revision == "Extension"){
            $cc->originator = User::where('id',$cc->initiator_id)->value('name');
            return view('frontend.forms.extension',compact('parent_name','record_number','parent_short_description','parent_initiator_id','parent_intiation_date','parent_division_id', 'parent_record','cc'));
        }
        if($request->revision == "New Document"){
            $cc->originator = User::where('id',$cc->initiator_id)->value('name');
            return redirect()->route('documents.create');
        }
        else{
            toastr()->warning('Not Working');
            return back();
        }
    }

    public function auditTrial($id)
    {
        $audit = RcmDocHistory::where('cc_id', $id)->orderByDESC('id')->paginate(5);
        $today = Carbon::now()->format('d-m-y');
        $document = CC::where('id', $id)->first();
        $document->originator = User::where('id', $document->initiator_id)->value('name');
        return view('frontend.change-control.audit-trial', compact('audit', 'document', 'today'));
    }

    public function auditDetails($id)
    {
        $detail = RcmDocHistory::find($id);
        $detail_data = RcmDocHistory::where('activity_type', $detail->activity_type)->where('cc_id', $detail->cc_id)->latest()->get();
        $doc = CC::where('id', $detail->cc_id)->first();
        $doc->origiator_name = User::find($doc->initiator_id);
        return view('frontend.rcms.CC.audit-trial-inner', compact('detail', 'doc', 'detail_data'));
    }



    public function summery_pdf($id)
    {
        $data = CC::find($id);
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
        } else {
            $datas = ActionItem::find($id);

            if (empty($datas)) {
                $datas = Extension::find($id);
                $data = CC::find($datas->cc_id);
                $data->originator = User::where('id', $data->initiator_id)->value('name');
                $data->created_at = $datas->created_at;
            } else {
                $data = CC::find($datas->cc_id);
                $data->originator = User::where('id', $data->initiator_id)->value('name');
                $data->created_at = $datas->created_at;
            }
        }

        // pdf related work
        $pdf = App::make('dompdf.wrapper');
        $time = Carbon::now();
        $pdf = PDF::loadview('frontend.change-control.summary_pdf', compact('data', 'time'))
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

        $canvas->page_text(
            $width / 3,
            $height / 2,
            $data->status,
            null,
            60,
            [0, 0, 0],
            2,
            6,
            -20
        );

        if ($data->documents) {

            $pdfArray = explode(',', $data->documents);
            foreach ($pdfArray as $pdfFile) {
                $existingPdfPath = public_path('upload/PDF/' . $pdfFile);
                $permissions = 0644; // Example permission value, change it according to your needs
                if (file_exists($existingPdfPath)) {
                    // Create a new Dompdf instance
                    $options = new Options();
                    $options->set('chroot', public_path());
                    $options->set('isPhpEnabled', true);
                    $options->set('isRemoteEnabled', true);
                    $options->set('isHtml5ParserEnabled', true);
                    $options->set('allowedFileExtensions', ['pdf']); // Allow PDF file extension

                    $dompdf = new Dompdf($options);

                    chmod($existingPdfPath, $permissions);

                    // Load the existing PDF file
                    $dompdf->loadHtmlFile($existingPdfPath);

                    // Render the PDF
                    $dompdf->render();

                    // Output the PDF to the browser
                    $dompdf->stream();
                }
            }
        }

        return $pdf->stream('SOP' . $id . '.pdf');
    }

    public function audit_pdf($id)
    {
        $doc = CC::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
        } else {
            $datas = ActionItem::find($id);

            if (empty($datas)) {
                $datas = Extension::find($id);
                $doc = CC::find($datas->cc_id);
                $doc->originator = User::where('id', $doc->initiator_id)->value('name');
                $doc->created_at = $datas->created_at;
            } else {
                $doc = CC::find($datas->cc_id);
                $doc->originator = User::where('id', $doc->initiator_id)->value('name');
                $doc->created_at = $datas->created_at;
            }
        }
        $data = RcmDocHistory::where('cc_id', $doc->id)->orderByDesc('id')->get();
        // dd($data);
        // pdf related work
        $pdf = App::make('dompdf.wrapper');
        $time = Carbon::now();
        $pdf = PDF::loadview('frontend.change-control.audit_trial_pdf', compact('data', 'doc'))
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

        $canvas->page_text(
            $width / 3,
            $height / 2,
            $doc->status,
            null,
            60,
            [0, 0, 0],
            2,
            6,
            -20
        );



        return $pdf->stream('SOP' . $id . '.pdf');
    }

    public function ccView($id)
    {

        $data = CC::find($id);
        if (empty($data)) {
            $data = ActionItem::find($id);
            if (empty($data)) {
                $data = Extension::find($id);
            }
        }
        $html = '';
        $html = '<div class="block">
        <div class="record_no">
            Record No. ' . str_pad($data->record, 4, '0', STR_PAD_LEFT) .
            '</div>
        <div class="short_desc">' .
            $data->short_description . '
        </div>
        <div class="division">
            QMS - EMEA / Change Control
        </div>
        <div class="status">' .
            $data->status . '
        </div>
            </div>
            <div class="block">
                <div class="block-head">
                    Actions
                </div>
                <div class="block-list">
                    <a href="/rcms/audit/' . $data->id . '" class="list-item">View History</a>
                    <a href="send-notification" class="list-item">Send Notification</a>
                    <div class="list-drop">
                        <div class="list-item" onclick="showAction()">
                            <div>Run Report</div>
                            <div><i class="fa-solid fa-angle-down"></i></div>
                        </div>
                        <div class="drop-list">
                            <a target="_blank" href="summary/' . $data->id . '" class="inner-item">Change Control Summary</a>
                            <a target="_blank" href="/rcms/audit/' . $data->id . '" class="inner-item">Audit Trail</a>
                            <a target="_blank" href="/rcms/change_control_single_pdf/' . $data->id . '" class="inner-item">Change Control Single Report</a>
                            <a target="_blank" href="/rcms/change_control_family_pdf" class="inner-item">Change Control Parent with Immediate Child</a>
                        </div>
                    </div>
                </div>
            </div>';
        $response['html'] = $html;

        return response()->json($response);
    }
    public function single_pdf($id)
    {
        $data = CC::find($id);
        $cftReviewerIds = explode(',', $data->cft_reviewer_person);
        $userNames = User::whereIn('id', $cftReviewerIds)->pluck('name')->toArray();
        $users = implode(', ', $userNames);
        
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');

            $docdetail = Docdetail::where('cc_id', $data->id)->first();
            $review = Qareview::where('cc_id', $data->id)->first();
            $evaluation = Evaluation::where('cc_id', $data->id)->first();
            $info = AdditionalInformation::where('cc_id', $data->id)->first();
            $comments = GroupComments::where('cc_id', $data->id)->first();
            $assessment = RiskAssessment::where('cc_id', $data->id)->first();
            $approcomments = QaApprovalComments::where('cc_id', $data->id)->first();
            $closure = ChangeClosure::where('cc_id', $data->id)->first();


            // pdf related work
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.change-control.change_control_single_pdf', compact(
                'data',
                'docdetail',
                'review',
                'evaluation',
                'info',
                'comments',
                'assessment',
                'approcomments',
                'closure',
                'users'
            ))
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

            $canvas->page_text(
                $width / 4,
                $height / 2,
                $data->status,
                null,
                25,
                [0, 0, 0],
                2,
                6,
                -20
            );



            return $pdf->stream('SOP' . $id . '.pdf');
        }
    }


    public function parent_child()
    {



        // pdf related work
        $pdf = App::make('dompdf.wrapper');
        $time = Carbon::now();
        $pdf = PDF::loadview('frontend.change-control.change_control_family_pdf')
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

        $canvas->page_text(
            $width / 4,
            $height / 2,
            "Opened",
            null,
            25,
            [0, 0, 0],
            2,
            6,
            -20
        );



        return $pdf->stream('SOP.pdf');
    }

    public function eCheck($id)
    {
        $data = CC::find($id);
        return view('frontend.effectivenessCheck.create', compact('data'));
    }
}
