<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Models\ActionItem;
use App\Models\EffectivenessCheck;
use App\Models\Capa;
use App\Models\CapaHistory;
use App\Models\RecordNumber;
use App\Models\User;
use App\Models\CapaAuditTrial;
use App\Models\RoleGroup;
use App\Models\CapaGrid;
use App\Models\Extension;
use App\Models\CC;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use PDF;
use Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\OpenStage;

class CapaController extends Controller
{

    public function capa()
    {
        $cft = [];
        $old_record = Capa::select('id', 'division_id', 'record')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date= $formattedDate->format('Y-m-d');
        $changeControl = OpenStage::find(1);
         if(!empty($changeControl->cft)) $cft = explode(',', $changeControl->cft);
        return view("frontend.forms.capa", compact('due_date', 'record_number', 'old_record', 'cft'));
    }

    public static function CustomHelpers($id)
    {
        $name = DB::table('q_m_s_divisions')->where('id', $id)->where('status', 1)->value('name');
        return $name;
    }


    public function capastore(Request $request)
    {

        // return $request;
        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return redirect()->back();
        }
        $capa = new Capa();
        $lastDocument = new Capa();
        $capa->form_type = "CAPA";
        $capa->record = ((RecordNumber::first()->value('counter')) + 1);
        $capa->initiator_id = Auth::user()->id;
        $capa->division_id = $request->division_id;
        $capa->parent_id = $request->parent_id;
        $capa->parent_type = $request->parent_type;
        $capa->division_code = $request->division_code;
        $capa->initiator_name = $request->initiator_name;
        $capa->record_number = $request->record_number;
        $capa->intiation_date = $request->intiation_date;
        $capa->general_initiator_group = $request->initiator_group;
        $capa->short_description = $request->short_description;
        $capa->problem_description = $request->problem_description;
        $capa->due_date= $request->due_date;
        // dd($capa->due_date);

        $capa->more_info_review_by= $request->more_info_review_by;
        $capa->more_info_review_on= $request->more_info_review_on;
        $capa->pending_more_info_review_comment= $request->pending_more_info_review_comment;

        $capa->assign_to = $request->assign_to;
       $capa->capa_team = implode(',', $request->capa_team);
        $capa->capa_type = $request->capa_type;
        $capa->severity_level_form= $request->severity_level_form;
        $capa->initiated_through = $request->initiated_through;
        $capa->initiated_through_req = $request->initiated_through_req;
        $capa->repeat = $request->repeat;
        $capa->initiator_Group= $request->initiator_Group;
        $capa->initiator_group_code= $request->initiator_group_code;
        $capa->repeat_nature = $request->repeat_nature;
        $capa->Effectiveness_checker = $request->Effectiveness_checker;
        $capa->effective_check_plan = $request->effective_check_plan;
        $capa->due_date_extension = $request->due_date_extension;
        $capa->cft_comments_form= $request->cft_comments_form;
        $capa->qa_comments_new = $request->qa_comments_new;
        $capa->designee_comments_new= $request->designee_comments_new;
       $capa->Warehouse_comments_new = $request->Warehouse_comments_new;
        $capa->Engineering_comments_new = $request->Engineering_comments_new;
       $capa->Instrumentation_comments_new = $request->Instrumentation_comments_new;
       $capa->Validation_comments_new = $request->Validation_comments_new;
       $capa->Others_comments_new = $request->Others_comments_new;
       $capa->Group_comments_new = $request->Group_comments_new;
    //    $capa->cft_attchament_new= json_encode($request->cft_attchament_new);
    //    $capa->additional_attachments= json_encode($request->additional_attachments);
    //    $capa->group_attachments_new = json_encode($request->group_attachments_new);
       $capa->Microbiology_new= $request->Microbiology_new;
    //    $capa->Microbiology_Person = $request->Microbiology_Person;
       $capa->goup_review = $request->goup_review;
       $capa->Production_new = $request->Production_new;
       $capa->Quality_Approver = $request->Quality_Approver;
       $capa->Quality_Approver_Person = $request->Quality_Approver_Person;
       $capa->bd_domestic = $request->bd_domestic;
       $capa->Bd_Person = $request->Bd_Person;
       $capa->Production_Person= $request->Production_Person;
    //    $capa->additional_attachments= json_encode($request->additional_attachments);
         $capa->capa_related_record= implode(',', $request->capa_related_record);
         $capa->initial_observation = $request->initial_observation;

        //$capa->comment = $request->comment;
        $capa->interim_containnment = $request->interim_containnment;
        $capa->containment_comments = $request->containment_comments;
        if (!empty($request->capa_attachment)) {
            $files = [];
            if ($request->hasfile('capa_attachment')) {
                foreach ($request->file('capa_attachment') as $file) {
                    $name = $request->name . '-capa_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $capa->capa_attachment = json_encode($files);
        }
        if (!empty($request->cft_attchament_new)) {
            $files = [];
            if ($request->hasfile('cft_attchament_new')) {
                foreach ($request->file('cft_attchament_new') as $file) {
                    $name = $request->name . '-cft_attchament_new' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $capa->cft_attchament_new= json_encode($files);
        }
        if (!empty($request->additional_attachments)) {
            $files = [];
            if ($request->hasfile('additional_attachments')) {
                foreach ($request->file('additional_attachments') as $file) {
                    $name = $request->name . '-additional_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $capa->additional_attachments=json_encode($files);
        }
        if (!empty($request->group_attachments_new)) {
            $files = [];
            if ($request->hasfile('group_attachments_new')) {
                foreach ($request->file('group_attachments_new') as $file) {
                    $name = $request->name . '-group_attachments_new' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $capa->group_attachments_new = json_encode($files);
        }

        $capa->capa_qa_comments= $request->capa_qa_comments;
        $capa->capa_qa_comments2 = $request->capa_qa_comments2;
        $capa->details_new = $request->details_new;
        $capa->project_details_application = $request->project_details_application;
        $capa->project_initiator_group = $request->project_initiator_group;
        $capa->site_number = $request->site_number;
        $capa->subject_number = $request->subject_number;
        $capa->subject_initials = $request->subject_initials;
        $capa->sponsor = $request->sponsor;
        $capa->general_deviation= $request->general_deviation;
        $capa->corrective_action = $request->corrective_action;
        $capa->preventive_action = $request->preventive_action;
        $capa->supervisor_review_comments = $request->supervisor_review_comments;
        $capa->qa_review = $request->qa_review;
        $capa->effectiveness = $request->effectiveness;
        $capa->effect_check = $request->effect_check;
        $capa->effect_check_date = $request->effect_check_date;

        if (!empty($request->closure_attachment)) {
            $files = [];
            if ($request->hasfile('closure_attachment')) {
                foreach ($request->file('closure_attachment') as $file) {
                    $name = $request->name . '-closure_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $capa->closure_attachment = json_encode($files);
        }

        $capa->status = 'Opened';
        $capa->stage = 1;
        $capa->save();

        $data1 = new CapaGrid();
        $data1->capa_id = $capa->id;
        $data1->type = "Product_Details";
        if (!empty($request->product_name)) {
            $data1->product_name = serialize($request->product_name);
        }
        if (!empty($request->batch_no)) {
            $data1->batch_no = serialize($request->batch_no);
        }
        if (!empty($request->mfg_date)) {
            $data1->mfg_date = serialize($request->mfg_date);
        }
        if (!empty($request->batch_desposition)) {
            $data1->batch_desposition = serialize($request->batch_desposition);
        }
        if (!empty($request->expiry_date)) {
            $data1->expiry_date = serialize($request->expiry_date);
        }
        if (!empty($request->remark)) {
            $data1->remark = serialize($request->remark);
        }
        if (!empty($request->batch_status)) {
            $data1->batch_status = serialize($request->batch_status);
        }

        $data1->save();


        $data2 = new CapaGrid();
        $data2->capa_id = $capa->id;
        $data2->type = "Material_Details";
        if (!empty($request->material_name)) {
            $data2->material_name = serialize($request->material_name);
        }
        if (!empty($request->material_batch_no)) {
            $data2->material_batch_no = serialize($request->material_batch_no);
        }
        if (!empty($request->material_mfg_date)) {
            $data2->material_mfg_date = serialize($request->material_mfg_date);
        }
        if (!empty($request->material_expiry_date)) {
            $data2->material_expiry_date = serialize($request->material_expiry_date);
        }
        if (!empty($request->material_batch_desposition)) {
            $data2->material_batch_desposition = serialize($request->material_batch_desposition);
        }
        if (!empty($request->material_remark)) {
            $data2->material_remark = serialize($request->material_remark);
        }
        if (!empty($request->material_batch_status)) {
            $data2->material_batch_status = serialize($request->material_batch_status);
        }


        $data2->save();

        $data3 = new CapaGrid();
        $data3->capa_id = $capa->id;
        $data3->type = "Instruments_Details";
        if (!empty($request->equipment)) {
            $data3->equipment = serialize($request->equipment);
        }
        if (!empty($request->equipment_instruments)) {
            $data3->equipment_instruments = serialize($request->equipment_instruments);
        }
        if (!empty($request->equipment_comments)) {
            $data3->equipment_comments = serialize($request->equipment_comments);
        }
        $data3->save();


        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();

        if (!empty($capa->division_code)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Site/Location Code';
            $history->previous = "Null";
            $history->current = $capa->division_code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->record_number)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Record Number';
            $history->previous = "Null";
            $history->current = $capa->record_number;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->initiator_name)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Initiator';
            $history->previous = "Null";
            $history->current = $capa->initiator_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->due_date_extension)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = "Null";
            $history->current = $capa->due_date_extension;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if (!empty($capa->initiator_Group)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Initiator Group';
            $history->previous = "Null";
            $history->current = Helpers::getFullDepartmentName($capa->initiator_Group);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->initiator_group_code)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = "Null";
            $history->current = $capa->initiator_group_code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->intiation_date)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Date of Initiation';
            $history->previous = "Null";
            $history->current = $capa->intiation_date->format('d-M-Y');
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        $previousAssignedUser = User::find($lastDocument->assign_to)->name ?? 'Null'; // Assuming there might be a case where the user doesn't exist
        $currentAssignedUser = User::find($capa->assign_to)->name ?? 'Null';

        if (!empty($capa->assign_to)) {

            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Assigned To';
            $history->previous = $previousAssignedUser; // Previous assigned user name
            $history->current = $currentAssignedUser;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->severity_level_form)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Severity Level';
            $history->previous = "Null";
            $history->current = $capa->severity_level_form;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }


        if (!empty($capa->initiated_through)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Initiated Through';
            $history->previous = "Null";
            $history->current = $capa->initiated_through;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if (!empty($capa->initiated_through_req)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Others';
            $history->previous = "Null";
            $history->current = $capa->initiated_through_req;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->repeat)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Repeat';
            $history->previous = "Null";
            $history->current = $capa->repeat;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->repeat_nature)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Repeat Nature';
            $history->previous = "Null";
            $history->current = $capa->repeat_nature;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->problem_description)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Problem Description';
            $history->previous = "Null";
            $history->current = $capa->problem_description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        $previousTeamMembers = User::whereIn('id', explode(',', $lastDocument->capa_team))->pluck('name')->toArray();
            $currentTeamMembers = User::whereIn('id', explode(',', $capa->capa_team))->pluck('name')->toArray();

        if (!empty($capa->capa_team)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'CAPA Team';
            $history->previous = "Null"; // Convert array to comma-separated string
            $history->current = implode(', ', $currentTeamMembers);   // Convert array to comma-separated string
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }


        if (!empty($capa->short_description)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Short Description';
            $history->previous = "Null";
            $history->current = $capa->short_description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }


        if (!empty($capa->due_date)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Due Date';
            $history->previous = "Null";
            $history->current = $capa->due_date;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }


        if (!empty($capa->capa_related_record)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Reference Record';
            $history->previous = "Null";
            $history->current = implode(',', $request->capa_related_record);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }




        if (!empty($capa->initial_observation)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Initial Observation';
            $history->previous = "Null";
            $history->current = $capa->initial_observation;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->interim_containnment)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Interim Containnment';
            $history->previous = "Null";
            $history->current = $capa->interim_containnment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->containment_comments)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Containment Comments';
            $history->previous = "Null";
            $history->current = $capa->containment_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if (!empty($capa->capa_attachment)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'CAPA Attachment';
            $history->previous = "Null";
            $history->current = $capa->capa_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->capa_qa_comments)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Comments';
            $history->previous = "Null";
            $history->current = $capa->capa_qa_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->capa_qa_comments2)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'CAPA QA Comments';
            $history->previous = "Null";
            $history->current = $capa->capa_qa_comments2;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->details_new)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Details';
            $history->previous = "Null";
            $history->current = $capa->details_new;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->capa_type)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = '';
            $history->previous = "Null";
            $history->current = $capa->capa_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }


        if (!empty($capa->project_details_application)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Project Datails Application';
            $history->previous = "Null";
            $history->current = $capa->project_details_application;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }


        if (!empty($capa->site_number)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Site Number';
            $history->previous = "Null";
            $history->current = $capa->site_number;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->subject_number)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Subject Number';
            $history->previous = "Null";
            $history->current = $capa->subject_number;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->subject_initials)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Subject Initials';
            $history->previous = "Null";
            $history->current = $capa->subject_initials;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->sponsor)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Sponsor';
            $history->previous = "Null";
            $history->current = $capa->sponsor;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->general_deviation)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'General Deviation';
            $history->previous = "Null";
            $history->current = $capa->general_deviation;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->capa_type)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'CAPA Type';
            $history->previous = "Null";
            $history->current = $capa->capa_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->corrective_action)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Corrective Action';
            $history->previous = "Null";
            $history->current = $capa->corrective_action;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->preventive_action)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Preventive Action';
            $history->previous = "Null";
            $history->current = $capa->preventive_action;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->supervisor_review_comments)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Supervisor Review Comments';
            $history->previous = "Null";
            $history->current = $capa->supervisor_review_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->qa_review)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'QA Review & Closure';
            $history->previous = "Null";
            $history->current = $capa->qa_review;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->effectiveness)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Effectiveness Check required';
            $history->previous = "Null";
            $history->current = $capa->effectiveness;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->effect_check_date)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Effect.Check Creation Date';
            $history->previous = "Null";
            $history->current = $capa->effect_check_date;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($capa->closure_attachment)) {
            $history = new CapaAuditTrial();
            $history->capa_id = $capa->id;
            $history->activity_type = 'Closure Attachment';
            $history->previous = "Null";
            $history->current = $capa->closure_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $capa->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }



        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }
    public function capaUpdate(Request $request, $id)
    {
        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return redirect()->back();
        }
        $lastDocument = Capa::find($id);
        $capa = Capa::find($id);
        $capa->parent_id = $request->parent_id;
        $capa->parent_type = $request->parent_type;
        $capa->division_code = $request->division_code;
        $capa->record_number = $request->record_number;
        $capa->intiation_date= $request->intiation_date;
        $capa->general_initiator_group = $request->initiator_group;
        $capa->short_description = $request->short_description;
        $capa->problem_description = $request->problem_description;
        // $capa->due_date= $request->due_date;
        $capa->assign_to = $request->assign_to;
      //  $capa->capa_team = $request->capa_team;
        $capa->capa_team = implode(',', $request->capa_team);
        $capa->capa_type = $request->capa_type;
        $capa->details_new = $request->details_new;
        $capa->initiated_through = $request->initiated_through;
        $capa->initiated_through_req = $request->initiated_through_req;
        $capa->repeat = $request->repeat;
        $capa->initiator_Group= $request->initiator_Group;
        $capa->initiator_group_code= $request->initiator_group_code;
        $capa->severity_level_form= $request->severity_level_form;
        $capa->cft_comments_form= $request->cft_comments_form;
        $capa->qa_comments_new = $request->qa_comments_new;
        $capa->designee_comments_new= $request->designee_comments_new;
       $capa->Warehouse_comments_new = $request->Warehouse_comments_new;
        $capa->Engineering_comments_new = $request->Engineering_comments_new;
       $capa->Instrumentation_comments_new = $request->Instrumentation_comments_new;
       $capa->Validation_comments_new = $request->Validation_comments_new;
       $capa->Others_comments_new = $request->Others_comments_new;
       $capa->Quality_Approver= $request->Quality_Approver;
       $capa->Quality_Approver_Person= $request->Quality_Approver_Person;
       $capa->Production_new = $request->Production_new;
       $capa->Group_comments_new = $request->Group_comments_new;

            //    $capa->comment = $request->comment;

    //    $capa->cft_attchament_new = json_encode($request->cft_attchament_new);
    //    $capa->group_attachments_new = json_encode($request->group_attachments_new);
        $capa->repeat_nature = $request->repeat_nature;
        $capa->Effectiveness_checker = $request->Effectiveness_checker;
        $capa->effective_check_plan = $request->effective_check_plan;
        $capa->due_date_extension = $request->due_date_extension;
        //  $capa->capa_related_record=  implode(',', $request->capa_related_record);

        $capa->capa_related_record = implode(',', $request->capa_related_record);
        // $capa->reference_record = $request->reference_record;
        $capa->Microbiology_new= $request->Microbiology_new;
        $capa->goup_review = $request->goup_review;
        $capa->initial_observation = $request->initial_observation;

        $capa->interim_containnment = $request->interim_containnment;
        $capa->containment_comments = $request->containment_comments;
        $capa->capa_qa_comments= $request->capa_qa_comments;
        $capa->capa_qa_comments2 = $request->capa_qa_comments2;
        // $capa->details = $request->details;
        $capa->project_details_application = $request->project_details_application;
        $capa->project_initiator_group = $request->project_initiator_group;
        $capa->site_number = $request->site_number;
        $capa->subject_number = $request->subject_number;
        $capa->subject_initials = $request->subject_initials;
        $capa->sponsor = $request->sponsor;
        $capa->general_deviation = $request->general_deviation;
        $capa->corrective_action = $request->corrective_action;
        $capa->preventive_action = $request->preventive_action;
        $capa->supervisor_review_comments = $request->supervisor_review_comments;
        $capa->qa_review = $request->qa_review;
        $capa->effectiveness = $request->effectiveness;
        $capa->effect_check = $request->effect_check;
        $capa->effect_check_date = $request->effect_check_date;
        $capa->bd_domestic= $request->bd_domestic;
        $capa->Bd_Person= $request->Bd_Person;
        $capa->Production_Person= $request->Production_Person;


        $files = is_array($request->existing_capa_attachment) ? $request->existing_capa_attachment : null;

        if (!empty($request->capa_attachment)) {
            if ($capa->capa_attachment) {
                $existingFiles = json_decode($capa->capa_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('capa_attachment')) {
                foreach ($request->file('capa_attachment') as $file) {
                    $name = $request->name . 'capa_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

         //If no files are attached, set to null
        $capa->capa_attachment = !empty($files) ? json_encode(array_values($files)) : null;

        // if (!empty($request->closure_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('closure_attachment')) {
        //         foreach ($request->file('closure_attachment') as $file) {
        //             $name = $request->name . 'closure_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $capa->closure_attachment = json_encode($files);
        // }

        $files = is_array($request->existing_closure_attachment) ? $request->existing_closure_attachment : null;

        if (!empty($request->closure_attachment)) {
            if ($capa->closure_attachment) {
                $existingFiles = json_decode($capa->closure_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles); // Re-index the array to ensure it's a proper array
                }
            }

            if ($request->hasfile('closure_attachment')) {
                foreach ($request->file('closure_attachment') as $file) {
                    $name = $request->name . 'closure_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        // If no files are attached, set to null
        $capa->closure_attachment = !empty($files) ? json_encode(array_values($files)) : null;

        $capa->update();


        // -----------------------grid--------------------
        if ($request->product_name) {
            $data1 = CapaGrid::where('capa_id', $id)->where('type', "Product_Details")->first();
            $data1->capa_id = $capa->id;
            $data1->type = "Product_Details";
            if (!empty($request->product_name)) {
                $data1->product_name = serialize($request->product_name);
            }
            if (!empty($request->batch_no)) {
                $data1->batch_no = serialize($request->batch_no);
            }
            if (!empty($request->mfg_date)) {
                $data1->mfg_date = serialize($request->mfg_date);
            }
            if (!empty($request->expiry_date)) {
                $data1->expiry_date = serialize($request->expiry_date);
            }
            if (!empty($request->batch_desposition)) {
                $data1->batch_desposition = serialize($request->batch_desposition);
            }

            if (!empty($request->remark)) {
                $data1->remark = serialize($request->remark);
            }
            if (!empty($request->batch_status)) {
                $data1->batch_status = serialize($request->batch_status);
            }

          $data1->update();
        }

        // // --------------------------

        if ($request->material_name) {
            $data2 = CapaGrid::where('type','Material_Details')->where('capa_id',$id)->first();
            if(empty( $data2)){
                $data2 = new CapaGrid();
            }

            $data2->capa_id = $capa->id;
            $data2->type = "Material_Details";
            if (!empty($request->material_name)) {
                $data2->material_name = serialize($request->material_name);
            }
            if (!empty($request->material_batch_no)) {
                $data2->material_batch_no = serialize($request->material_batch_no);
            }

            if (!empty($request->material_mfg_date)) {
                $data2->material_mfg_date = serialize($request->material_mfg_date);
            }
            if (!empty($request->material_expiry_date)) {
                $data2->material_expiry_date = serialize($request->material_expiry_date);
            }
            if (!empty($request->material_batch_desposition)) {
                $data2->material_batch_desposition = serialize($request->material_batch_desposition);
            }
            if (!empty($request->material_remark)) {
                $data2->material_remark = serialize($request->material_remark);
            }
            if (!empty($request->material_batch_status)) {
                $data2->material_batch_status = serialize($request->material_batch_status);
            }


            $data2->update();
        }

        // // ----------------------------------------
        if ($request->equipment) {
            $data3 = CapaGrid::where('capa_id', $id)->where('type', "Instruments_Details")->first();
            if(empty( $data3)){
                $data3 = new CapaGrid();
            }
            $data3->capa_id = $capa->id;
            $data3->type = "Instruments_Details";
            if (!empty($request->equipment)) {
                $data3->equipment = serialize($request->equipment);
            }
            if (!empty($request->equipment_instruments)) {
                $data3->equipment_instruments = serialize($request->equipment_instruments);
            }
            if (!empty($request->equipment_comments)) {
                $data3->equipment_comments = serialize($request->equipment_comments);
            }

            $data3->update();

        }



        //     $record = RecordNumber::first();
        //     $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        //     $record->update();
        // }






        //     if ($lastDocument->division_code !=  $capa->division_code || ! empty($request->division_code_comment)) {
        //         $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
        //               ->where('activity_type', 'Division Code')
        //               ->exists();

        //     $history = new CapaAuditTrial();
        //     $history->capa_id = $id;
        //     $history->activity_type = 'Division Code';
        //     $history->previous = $lastDocument->division_code;
        //     $history->current = $capa->division_code;
        //     $history->comment = $request->division_code_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to =   "Null";
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = $lastDataAudittrail  ? 'Update' : 'New';
        //     $history->save();
        // }



        if ($lastDocument->initiated_through_req !=  $capa->initiated_through_req || ! empty($request->initiated_through_req_comment)) {
            $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                  ->where('activity_type', 'Others')
                  ->exists();

        $history = new CapaAuditTrial();
        $history->capa_id = $id;
        $history->activity_type = 'Others';
        $history->previous = $lastDocument->initiated_through_req;
        $history->current = $capa->initiated_through_req;
        $history->comment = $request->initiated_through_req_comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =   "Not Applicable";
        $history->change_from = $lastDocument->status;
        if (is_null($lastDocument->initiated_through_req) || $lastDocument->initiated_through_req === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }        $history->save();
    }


            if ($lastDocument->initiator_Group !=  $capa->initiator_Group || ! empty($request->initiator_Group_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                    ->where('activity_type', 'Initiator Group')
                    ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Initiator Group';
            $history->previous = Helpers::getInitiatorGroupFullName($lastDocument->initiator_Group);
            $history->current =  Helpers::getFullDepartmentName($capa->initiator_Group);
            $history->comment = $request->initiator_Group_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->initiator_Group) || $lastDocument->initiator_Group === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
            }

            if ($lastDocument->initiator_group_code !=  $capa->initiator_group_code || ! empty($request->initiator_group_code_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                    ->where('activity_type', 'Initiator Group Code')
                    ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = $lastDocument->initiator_group_code;
            $history->current = $capa->initiator_group_code;
            $history->comment = $request->initiator_group_code_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->initiator_group_code) || $lastDocument->initiator_group_code === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
            }


              if ($lastDocument->short_description !=  $capa->short_description || ! empty($request->short_description_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', '  Short Description')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = '  Short Description';
            $history->previous = $lastDocument->short_description;
            $history->current = $capa->short_description;
            $history->comment = $request->short_description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            //$history->action_name = $lastDataAudittrail  ? 'New' : 'Update';
            if (is_null($lastDocument->short_description) || $lastDocument->short_description === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }


        if ($lastDocument->repeat_nature !=  $capa->repeat_nature || ! empty($request->repeat_nature_comment)) {
            $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                  ->where('activity_type', '  Repeat Nature')
                  ->exists();

        $history = new CapaAuditTrial();
        $history->capa_id = $id;
        $history->activity_type = '  Repeat Nature';
        $history->previous = $lastDocument->repeat_nature;
        $history->current = $capa->repeat_nature;
        $history->comment = $request->repeat_nature_comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =   "Not Applicable";
        $history->change_from = $lastDocument->status;
        //$history->action_name = $lastDataAudittrail  ? 'New' : 'Update';
        if (is_null($lastDocument->repeat_nature) || $lastDocument->repeat_nature === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
        $history->save();
    }

        if ($lastDocument->repeat !=  $capa->repeat || ! empty($request->repeat_comment)) {
            $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                  ->where('activity_type', '  Repeat')
                  ->exists();

        $history = new CapaAuditTrial();
        $history->capa_id = $id;
        $history->activity_type = '  Repeat';
        $history->previous = $lastDocument->repeat;
        $history->current = $capa->repeat;
        $history->comment = $request->repeat_comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =   "Not Applicable";
        $history->change_from = $lastDocument->status;
        if (is_null($lastDocument->repeat) || $lastDocument->repeat === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }        $history->save();
        }

        if ($lastDocument->initiated_through !=  $capa->initiated_through || ! empty($request->initiated_through_comment)) {
            $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                  ->where('activity_type', '  Initiated Through')
                  ->exists();

        $history = new CapaAuditTrial();
        $history->capa_id = $id;
        $history->activity_type = '  Initiated Through';
        $history->previous = $lastDocument->initiated_through;
        $history->current = $capa->initiated_through;
        $history->comment = $request->initiated_through_comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =   "Not Applicable";
        $history->change_from = $lastDocument->status;
        if (is_null($lastDocument->initiated_through) || $lastDocument->initiated_through === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }        $history->save();
    }



                if ($lastDocument->severity_level_form  !=  $capa->severity_level_form  || ! empty($request->severity_level_form_comment)) {
                    $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                        ->where('activity_type', '  Severity Level')
                        ->exists();

                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = '  Severity Level';
                $history->previous = $lastDocument->severity_level_form;
                $history->current = $capa->severity_level_form;
                $history->comment = $request->severity_level_form_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = $lastDocument->status;
                if (is_null($lastDocument->severity_level_form) || $lastDocument->severity_level_form === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }


            if ($lastDocument->problem_description  !=  $capa->problem_description  || ! empty($request->problem_description_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', '  Problem Description')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = '  Problem Description';
            $history->previous = $lastDocument->problem_description;
            $history->current = $capa->problem_description;
            $history->comment = $request->problem_description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            //$history->action_name = $lastDataAudittrail  ? 'New' : 'Update';
            if (is_null($lastDocument->problem_description) || $lastDocument->problem_description === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDocument->assign_to != $capa->assign_to || !empty($request->assign_to_comment)) {
            // Fetch previous and current assigned user's name
            $previousAssignedUser = User::find($lastDocument->assign_to)->name ?? 'Null'; // Assuming there might be a case where the user doesn't exist
            $currentAssignedUser = User::find($capa->assign_to)->name ?? 'Null'; // Assuming there might be a case where the user doesn't exist

            $lastDataAudittrail = CapaAuditTrial::where('capa_id', $capa->id)
                ->where('activity_type', '  Assigned To')
                ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = '  Assigned To';
            $history->previous = $previousAssignedUser; // Previous assigned user name
            $history->current = $currentAssignedUser;   // Current assigned user name
            $history->comment = $request->assign_to_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->assign_to) || $lastDocument->assign_to === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }

        if ($lastDocument->capa_team != $capa->capa_team || !empty($request->capa_team_comment)) {
            // Fetch previous and current team members' names
            $previousTeamMembers = User::whereIn('id', explode(',', $lastDocument->capa_team))->pluck('name')->toArray();
            $currentTeamMembers = User::whereIn('id', explode(',', $capa->capa_team))->pluck('name')->toArray();

            $lastDataAudittrail = CapaAuditTrial::where('capa_id', $capa->id)
                ->where('activity_type', '  CAPA Team')
                ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = '  CAPA Team';
            $history->previous = implode(', ', $previousTeamMembers); // Convert array to comma-separated string
            $history->current = implode(', ', $currentTeamMembers);   // Convert array to comma-separated string
            $history->comment = $request->capa_team_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->capa_team) || $lastDocument->capa_team === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }


        // if ($lastDocument->reference_record != $capa->reference_record || !empty($request->reference_record_comment)) {

        //     $history = new CapaAuditTrial();
        //     $history->capa_id = $id;
        //     $history->activity_type = 'Reference Records';
        //     $history->previous = $lastDocument->reference_record;
        //     $history->current = $capa->reference_record;
        //     $history->comment = $request->reference_record_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

            if ($lastDocument->initial_observation !=  $capa->initial_observation || ! empty($request->initial_observation_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', '  Initial Observation')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = '  Initial Observation';
            $history->previous = $lastDocument->initial_observation;
            $history->current = $capa->initial_observation;
            $history->comment = $request->initial_observation_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->initial_observation) || $lastDocument->initial_observation === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }

            if ($lastDocument->interim_containnment !=  $capa->interim_containnment || ! empty($request->interim_containnment_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', ' Interim Containnment')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = ' Interim Containnment';
            $history->previous = $lastDocument->interim_containnment;
            $history->current = $capa->interim_containnment;
            $history->comment = $request->interim_containnment_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->interim_containnment) || $lastDocument->interim_containnment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }

            if ($lastDocument->containment_comments !=  $capa->containment_comments || ! empty($request->containment_comments_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', '  Containment Comments')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = '  Containment Comments';
            $history->previous = $lastDocument->containment_comments;
            $history->current = $capa->containment_comments;
            $history->comment = $request->containment_comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->containment_comments) || $lastDocument->containment_comments === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDocument->capa_attachment != $capa->capa_attachment || !empty($request->capa_attachment_comment)) {
            $lastDataAudittrail = CapaAuditTrial::where('capa_id', $capa->id)
                ->where('activity_type', '  CAPA Attachment')
                ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = '  CAPA Attachment';

            // Ensure previous and current are strings
            $history->previous = is_array($lastDocument->capa_attachment) ? json_encode($lastDocument->capa_attachment) : $lastDocument->capa_attachment;
            $history->current = is_array($capa->capa_attachment) ? json_encode($capa->capa_attachment) : $capa->capa_attachment;

            // Ensure comment is a string
            $history->comment = is_array($request->capa_attachment_comment) ? json_encode($request->capa_attachment_comment) : $request->capa_attachment_comment;

            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->capa_attachment) || $lastDocument->capa_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }


            if ($lastDocument->capa_qa_comments !=  $capa->capa_qa_comments || ! empty($request->capa_qa_comments_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', 'Comments')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Comments';
            $history->previous = $lastDocument->capa_qa_comments;
            $history->current = $capa->capa_qa_comments;
            $history->comment = $request->capa_qa_comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->capa_qa_comments) || $lastDocument->capa_qa_comments === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }



        if ($lastDocument->capa_related_record != $capa->capa_related_record || !empty($request->capa_related_record_comment)) {
            $lastDataAudittrail = CapaAuditTrial::where('capa_id', $capa->id)
            ->where('activity_type', 'Reference Records')
            ->exists();
            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Reference Records';
            $history->previous = $lastDocument->capa_related_record;
            $history->current = $capa->capa_related_record;
            $history->comment = $request->capa_related_record_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->capa_related_record) || $lastDocument->capa_related_record === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }


            if ($lastDocument->project_details_application !=  $capa->project_details_application || ! empty($request->project_details_application_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', 'Project Datails Application')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Project Datails Application';
            $history->previous = $lastDocument->project_details_application;
            $history->current = $capa->project_details_application;
            $history->comment = $request->project_details_application_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->project_details_application) || $lastDocument->project_details_application === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }

            if ($lastDocument->project_initiator_group !=  $capa->project_initiator_group || ! empty($request->project_initiator_group_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', 'Project Datails Application')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Project Datails Application';
            $history->previous = $lastDocument->project_initiator_group;
            $history->current = $capa->project_initiator_group;
            $history->comment = $request->project_initiator_group_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->project_initiator_group) || $lastDocument->project_initiator_group === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }

            if ($lastDocument->site_number !=  $capa->site_number || ! empty($request->site_number_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', 'Site Number')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Site Number';
            $history->previous = $lastDocument->site_number;
            $history->current = $capa->site_number;
            $history->comment = $request->site_number_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->site_number) || $lastDocument->site_number === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }


            if ($lastDocument->subject_number !=  $capa->subject_number || ! empty($request->subject_number_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', 'Site Number')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Subject Number';
            $history->previous = $lastDocument->subject_number;
            $history->current = $capa->subject_number;
            $history->comment = $request->subject_number_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->subject_number) || $lastDocument->subject_number === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }

            if ($lastDocument->subject_initials !=  $capa->subject_initials || ! empty($request->subject_initials_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', 'Site Number')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Subject Initials';
            $history->previous = $lastDocument->subject_initials;
            $history->current = $capa->subject_initials;
            $history->comment = $request->subject_initials_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->subject_initials) || $lastDocument->subject_initials === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }



            if ($lastDocument->sponsor !=  $capa->sponsor || ! empty($request->sponsor_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', 'Site Number')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Sponsor';
            $history->previous = $lastDocument->sponsor;
            $history->current = $capa->sponsor;
            $history->comment = $request->sponsor_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->sponsor) || $lastDocument->sponsor === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }

            if ($lastDocument->general_deviation !=  $capa->general_deviation || ! empty($request->general_deviation_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', 'General Deviation')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'General Deviation';
            $history->previous = $lastDocument->general_deviation;
            $history->current = $capa->general_deviation;
            $history->comment = $request->general_deviation_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->general_deviation) || $lastDocument->general_deviation === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }

        if ($lastDocument->capa_qa_comments2 !=  $capa->capa_qa_comments2 || ! empty($request->capa_qa_comments2_comment)) {
            $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                  ->where('activity_type', '  CAPA QA Comments')
                  ->exists();

        $history = new CapaAuditTrial();
        $history->capa_id = $id;
        $history->activity_type = '  CAPA QA Comments';
        $history->previous = $lastDocument->capa_qa_comments2;
        $history->current = $capa->capa_qa_comments2;
        $history->comment = $request->capa_qa_comments2_comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =   "Not Applicable";
        $history->change_from = $lastDocument->status;
        if (is_null($lastDocument->capa_qa_comments2) || $lastDocument->capa_qa_comments2 === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }            $history->save();
    }

                if ($lastDocument->details_new !=  $capa->details_new || ! empty($request->capa_qa_comments2_comment)) {
                    $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                        ->where('activity_type', '  Details')
                        ->exists();

                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = '  Details';
                $history->previous = $lastDocument->details_new;
                $history->current = $capa->details_new;
                $history->comment = $request->capa_qa_comments2_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = $lastDocument->status;
                if (is_null($lastDocument->details_new) || $lastDocument->details_new === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }            $history->save();
            }
        if ($lastDocument->capa_type !=  $capa->capa_type || ! empty($request->capa_type_comment)) {
            $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                  ->where('activity_type', ' CAPA Type')
                  ->exists();


        $history = new CapaAuditTrial();
        $history->capa_id = $id;
        $history->activity_type = 'CAPA Type';
        $history->previous = $lastDocument->capa_type;
        $history->current = $capa->capa_type;
        $history->comment = $request->capa_type_comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =   "Not Applicable";
        $history->change_from = $lastDocument->status;
        if (is_null($lastDocument->capa_type) || $lastDocument->capa_type === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }        $history->save();
    }


            if ($lastDocument->corrective_action !=  $capa->corrective_action || ! empty($request->corrective_action_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', '  Corrective Action')
                      ->exists();


            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = '  Corrective Action';
            $history->previous = $lastDocument->corrective_action;
            $history->current = $capa->corrective_action;
            $history->comment = $request->corrective_action_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->corrective_action) || $lastDocument->corrective_action === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }


            if ($lastDocument->preventive_action !=  $capa->preventive_action || ! empty($request->preventive_action_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', '  Preventive Action')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = '  Preventive Action';
            $history->previous = $lastDocument->preventive_action;
            $history->current = $capa->preventive_action;
            $history->comment = $request->preventive_action_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->preventive_action) || $lastDocument->preventive_action === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }


            if ($lastDocument->supervisor_review_comments !=  $capa->supervisor_review_comments || ! empty($request->supervisor_review_comments_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', '  Supervisor Review Comments')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = '  Supervisor Review Comments';
            $history->previous = $lastDocument->supervisor_review_comments;
            $history->current = $capa->supervisor_review_comments;
            $history->comment = $request->supervisor_review_comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supervisor_review_comments) || $lastDocument->supervisor_review_comments === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }


            if ($lastDocument->qa_review !=  $capa->qa_review || ! empty($request->qa_review_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', '  QA Review & Closure')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = '  QA Review & Closure';
            $history->previous = $lastDocument->qa_review;
            $history->current = $capa->qa_review;
            $history->comment = $request->qa_review_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->qa_review) || $lastDocument->qa_review === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }

        if ($lastDocument->due_date_extension !=  $capa->due_date_extension || ! empty($request->due_date_extension_comment)) {
            $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                  ->where('activity_type', ' Due Date Extension Justification')
                  ->exists();

        $history = new CapaAuditTrial();
        $history->capa_id = $id;
        $history->activity_type = ' Due Date Extension Justification';
        $history->previous = $lastDocument->due_date_extension;
        $history->current = $capa->due_date_extension;
        $history->comment = $request->due_date_extension_comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =   "Not Applicable";
        $history->change_from = $lastDocument->status;
        if (is_null($lastDocument->due_date_extension) || $lastDocument->due_date_extension === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }            $history->save();
    }

            if ($lastDocument->effectiveness !=  $capa->effectiveness || ! empty($request->effectiveness_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', 'Effectiveness Check required')
                      ->exists();


            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Effectiveness Check required';
            $history->previous = $lastDocument->effectiveness;
            $history->current = $capa->effectiveness;
            $history->comment = $request->effectiveness_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->effectiveness) || $lastDocument->effectiveness === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }

            if ($lastDocument->effect_check_date !=  $capa->effect_check_date || ! empty($request->effect_check_date_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', 'Effect.Check Creation Date')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Effect.Check Creation Date';
            $history->previous = $lastDocument->effect_check_date;
            $history->current = $capa->effect_check_date;
            $history->comment = $request->effect_check_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->effect_check_date) || $lastDocument->effect_check_date === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }


            if ($lastDocument->closure_attachment !=  $capa->closure_attachment || ! empty($request->closure_attachment_comment)) {
                $lastDataAudittrail  = CapaAuditTrial::where('capa_id', $capa->id)
                      ->where('activity_type', '  Closure Attachment')
                      ->exists();

            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Closure Attachment';
            $history->previous = $lastDocument->closure_attachment;
            $history->current = $capa->closure_attachment;
            $history->comment = $request->closure_attachment_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->closure_attachment) || $lastDocument->closure_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }            $history->save();
        }
        toastr()->success("Record is updated Successfully");
        return back();
    }


    public function capashow($id)
    {
        $cft = [];
        $revised_date = "";
        $data = Capa::find($id);
        //dd($data);
        $old_record = Capa::select('id', 'division_id', 'record')->get();
        $revised_date = Extension::where('parent_id', $id)->where('parent_type', "Capa")->value('revised_date');
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_id)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $data1 = CapaGrid::where('capa_id', $id)->where('type', "Product_Details")->first();


        $data2 = CapaGrid::where('capa_id', $id)->where('type', "Material_Details")->first();
        $data3 = CapaGrid::where('capa_id', $id)->where('type', "Instruments_Details")->first();
        $changeControl = OpenStage::find(1);
        if(!empty($changeControl->cft)) $cft = explode(',', $changeControl->cft);
        // $MaterialsQueryData = Http::get('http://103.167.99.37/LIMS_EL/WebServices.Query.MaterialsQuery.lims');
        // dd( $MaterialsQueryData->json());
        // $EquipmentsQueryData = Http::get('http://103.167.99.37/LIMS_EL/WebServices.Query.EquipmentsQuery.lims');
        // dd( $EquipmentsQueryData->json());

        return view('frontend.capa.capaView', compact('data', 'data1', 'data2', 'data3', 'old_record','revised_date','cft' ));
    }


    public function capa_send_stage(Request $request, $id)
    {


        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $capa = Capa::find($id);
            $lastDocument = Capa::find($id);
            if ($capa->stage == 1) {
                $capa->stage = "2";
                $capa->status = "Pending CAPA Plan";
                $capa->plan_proposed_by = Auth::user()->name;
                $capa->plan_proposed_on = Carbon::now()->format('d-M-Y');
                $capa->plan_proposed_comment = $request->comment;



                    $history = new CapaAuditTrial();
                    $history->capa_id = $id;
                    $history->activity_type = 'Proposed Plan By, Proposed Plan On';
                    if (is_null($lastDocument->plan_proposed_by) || $lastDocument->plan_proposed_by === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->plan_proposed_by . ' , ' . $lastDocument->plan_proposed_on;
                    }

                    //$history->previous = "";
                    $history->action = 'Prapose Plan';
                    $history->current = $capa->plan_proposed_by . ' , ' . $capa->plan_proposed_on;
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Pending CAPA Plan";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
                    if (is_null($lastDocument->plan_proposed_by) || $lastDocument->plan_proposed_by === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();

                    $list = Helpers::getHodUserList($capa->division_id);

                    $userIds = collect($list)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                    $userId = $users->pluck('id')->implode(',');
                    if(!empty($users)){
                        try {
                            $history = new CapaAuditTrial();
                            $history->capa_id = $id;
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
                            $history->change_from = "Pending CAPA Plan";
                            $history->stage = "";
                            $history->action_name = "";
                            $history->mailUserId = $userId;
                            $history->role_name = "HOD/Designee  ";
                            $history->save(); 
                        } catch (\Throwable $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                       
                    foreach ($list as $u) {
                        // if($u->q_m_s_divisions_id == $capa->division_id){
                            $email = Helpers::getHODEmail($u->user_id);
                             if ($email !== null) {
                                try {
                              Mail::send(
                                  'mail.view-mail',
                                  ['data' => $capa, 'site' => "CAPA", 'history' => "Propose Plan", 'process' => 'CAPA', 'comment' => $capa->plan_proposed_comment, 'user'=> Auth::user()->name],
                                // function ($message) use ($email) {
                                //     $message->to($email)
                                //         ->subject("Document is sent By ".Auth::user()->name);
                                // }
                                function ($message) use ($email, $capa) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Capa, Record #" . str_pad($capa->record, 4, '0', STR_PAD_LEFT) . " - Activity: Propose Plan Performed");
                                }
                              );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            }
                    //  }
                  }


                $capa->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($capa->stage == 2) {
                $capa->stage = "3";
                $capa->status = "CAPA In Progress";
                $capa->plan_approved_by = Auth::user()->name;
                $capa->plan_approved_on = Carbon::now()->format('d-M-Y');
                $capa->plan_approved_comment = $request->comment;



                    $history = new CapaAuditTrial();
                    $history->capa_id = $id;
                    $history->activity_type = 'Approved Plan By, Approved Plan On';
                    if (is_null($lastDocument->plan_approved_by) || $lastDocument->plan_approved_by === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->plan_approved_by . ' , ' . $lastDocument->plan_approved_on;
                    }

                    //$history->previous = "";
                    $history->action = 'Approved Plan';
                    $history->current = $capa->plan_approved_by . ' , ' . $capa->plan_approved_on;
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "CAPA In Progress";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Approved';
                    if (is_null($lastDocument->plan_approved_by) || $lastDocument->plan_approved_by === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();



                    $list = Helpers::getQAUserList($capa->division_id);

                    $userIds = collect($list)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                    $userId = $users->pluck('id')->implode(',');
                    if(!empty($users)){
                        try {
                            $history = new CapaAuditTrial();
                            $history->capa_id = $id;
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
                            $history->change_from = "CAPA In Progress";
                            $history->stage = "";
                            $history->action_name = "";
                            $history->mailUserId = $userId;
                            $history->role_name = "QA ";
                            $history->save(); 
                        } catch (\Throwable $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                        foreach ($list as $u) {
                            // if($u->q_m_s_divisions_id == $capa->division_id){
                                $email = Helpers::getQAEmail($u->user_id);
                                if ($email !== null) {
                                    try {

                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $capa, 'site' => "CAPA", 'history' => "Approve Plan", 'process' => 'CAPA', 'comment' => $capa->plan_approved_comment, 'user'=> Auth::user()->name],
                                    // function ($message) use ($email) {
                                    //     $message->to($email)
                                    //         ->subject("Document is sent By ".Auth::user()->name);
                                    // }
                                    function ($message) use ($email, $capa) {
                                        $message->to($email)
                                        ->subject("QMS Notification: Capa, Record #" . str_pad($capa->record, 4, '0', STR_PAD_LEFT) . " - Activity: Approve Plan Performed");
                                    }
                                );
                                } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                    }
                                }
                            // }
                        }

                $capa->update();
                toastr()->success('Document Sent');
                return back();
            }

            if ($capa->stage == 3) {
                $capa->stage = "4";
                $capa->status = "QA Review";
                $capa->completed_by = Auth::user()->name;
                $capa->completed_on = Carbon::now()->format('d-M-Y');
                $capa->completedd_comment = $request->comment;


                    $history = new CapaAuditTrial();
                    $history->capa_id = $id;
                    $history->activity_type = 'Completed By, Completed On';
                    if (is_null($lastDocument->completed_by) || $lastDocument->completed_by === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->completed_by . ' , ' . $lastDocument->completed_on;
                    }
                    //$history->previous = "";
                    $history->action = 'Complete';
                    $history->current = $capa->completed_by . ' , ' . $capa->completed_on;
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "QA Review";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Complete';
                    if (is_null($lastDocument->completed_by) || $lastDocument->completed_by === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                $capa->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($capa->stage == 4) {
                $capa->stage = "5";
                $capa->status = "Pending Actions Completion";
                $capa->approved_by = Auth::user()->name;
                $capa->approved_on = Carbon::now()->format('d-M-Y');
                $capa->approved_comment = $request->comment;


                        $history = new CapaAuditTrial();
                        $history->capa_id = $id;
                        $history->activity_type = 'Approved By, Approved On';
                        if (is_null($lastDocument->approved_by) || $lastDocument->approved_by === '') {
                            $history->previous = "";
                        } else {
                            $history->previous = $lastDocument->approved_by . ' , ' . $lastDocument->approved_on;
                        }
                        $history->previous = "";
                        $history->action = 'Approve';
                        $history->current = $capa->approved_by . ' , ' . $capa->approved_on;
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->change_to =   "Pending Actions Completion";
                        $history->change_from = $lastDocument->status;
                        $history->stage = 'Approve';
                        if (is_null($lastDocument->approved_by) || $lastDocument->approved_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();

                        $list = Helpers::getQAHeadUserList($capa->division_id);

                              $userIds = collect($list)->pluck('user_id')->toArray();
                                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                                $userId = $users->pluck('id')->implode(',');
                                if(!empty($users)){
                                    try {
                                        $history = new CapaAuditTrial();
                                        $history->capa_id = $id;
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
                                        $history->change_from = "Pending Actions Completion";
                                        $history->stage = "";
                                        $history->action_name = "";
                                        $history->mailUserId = $userId;
                                        $history->role_name = "QA Head/Designee";
                                        $history->save(); 
                                    } catch (\Throwable $e) {
                                        \Log::error('Mail failed to send: ' . $e->getMessage());
                                    }
                                }
                        foreach ($list as $u) {
                            // if($u->q_m_s_divisions_id == $capa->division_id){
                                $email = Helpers::getQAHeadEmail($u->user_id);
                                 if ($email !== null) {
                                    try {

                                  Mail::send(
                                      'mail.view-mail',
                                      ['data' => $capa, 'site' => "CAPA", 'history' => "Approve ", 'process' => 'CAPA', 'comment' => $capa->approved_comment, 'user'=> Auth::user()->name],
                                    // function ($message) use ($email) {
                                    //     $message->to($email)
                                    //         ->subject("Document is sent By ".Auth::user()->name);
                                    // }
                                    function ($message) use ($email, $capa) {
                                        $message->to($email)
                                        ->subject("QMS Notification: Capa, Record #" . str_pad($capa->record, 4, '0', STR_PAD_LEFT) . " - Activity: Approve  Performed");
                                    }
                                  );
                                } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                    }
                                }
                            // }
                        }

                    $capa->update();
                    toastr()->success('Document Sent');
                    return back();
                }

            if ($capa->stage == 5) {
                $capa->stage = "6";
                $capa->status = "Closed - Done";
                $capa->all_actions_completed_by = Auth::user()->name;
                $capa->all_actions_completed_on = Carbon::now()->format('d-M-Y');
                $capa->all_actions_completed_comment = $request->comment;

                        $history = new CapaAuditTrial();
                        $history->capa_id = $id;
                        $history->activity_type = 'All Action Completed By, All Action Completed On';
                        if (is_null($lastDocument->all_actions_completed_by) || $lastDocument->all_actions_completed_by === '') {
                            $history->previous = "";
                        } else {
                            $history->previous = $lastDocument->all_actions_completed_by . ' , ' . $lastDocument->all_actions_completed_on;
                        }
                        $history->previous = "";
                        $history->action = 'All Action Completed';
                        $history->current = $capa->all_actions_completed_by . ' , ' . $capa->all_actions_completed_on;
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->change_to =   "Closed - Done";
                        $history->change_from = $lastDocument->status;
                        $history->stage = 'All Action Completed';
                        if (is_null($lastDocument->all_actions_completed_by) || $lastDocument->all_actions_completed_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                                $list = Helpers::getQAUserList($capa->division_id);

                                $userIds = collect($list)->pluck('user_id')->toArray();
                                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                                $userId = $users->pluck('id')->implode(',');
                                if(!empty($users)){
                                    try {
                                        $history = new CapaAuditTrial();
                                        $history->capa_id = $id;
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
                                        $history->role_name = "QA";
                                        $history->save(); 
                                    } catch (\Throwable $e) {
                                        \Log::error('Mail failed to send: ' . $e->getMessage());
                                    }
                                }


                                foreach ($list as $u) {
                                    // if($u->q_m_s_divisions_id == $capa->division_id){
                                        $email = Helpers::getQAEmail($u->user_id);
                                        if ($email !== null) {
                                            try {

                                        Mail::send(
                                            'mail.view-mail',
                                            ['data' => $capa, 'site' => "CAPA", 'history' => "All Action Completed", 'process' => 'CAPA', 'comment' => $capa->all_actions_completed_comment, 'user'=> Auth::user()->name],
                                            // function ($message) use ($email) {
                                            //     $message->to($email)
                                            //         ->subject("Document is sent By ".Auth::user()->name);
                                            // }
                                            function ($message) use ($email, $capa) {
                                                $message->to($email)
                                                ->subject("QMS Notification: Capa, Record #" . str_pad($capa->record, 4, '0', STR_PAD_LEFT) . " - Activity: All Action Completed Performed");
                                            }
                                        );
                                        } catch (\Exception $e) {
                                            \Log::error('Mail failed to send: ' . $e->getMessage());
                                            }
                                        }
                                    // }
                                }

                                $list = Helpers::getHodUserList($capa->division_id);

                                $userIds = collect($list)->pluck('user_id')->toArray();
                                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                                $userId = $users->pluck('id')->implode(',');
                                if(!empty($users)){
                                    try {
                                        $history = new CapaAuditTrial();
                                        $history->capa_id = $id;
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
                                        $history->role_name = "HOD/Designee";
                                        $history->save(); 
                                    } catch (\Throwable $e) {
                                        \Log::error('Mail failed to send: ' . $e->getMessage());
                                    }
                                }
                                foreach ($list as $u) {
                                    // if($u->q_m_s_divisions_id == $capa->division_id){
                                        $email = Helpers::getHODEmail($u->user_id);
                                        if ($email !== null) {
                                            try {

                                        Mail::send(
                                            'mail.view-mail',
                                            ['data' => $capa, 'site' => "CAPA", 'history' => "All Action Completed", 'process' => 'CAPA', 'comment' => $capa->all_actions_completed_comment, 'user'=> Auth::user()->name],
                                            // function ($message) use ($email) {
                                            //     $message->to($email)
                                            //         ->subject("Document is sent By ".Auth::user()->name);
                                            // }
                                            function ($message) use ($email, $capa) {
                                                $message->to($email)
                                                ->subject("QMS Notification: Capa, Record #" . str_pad($capa->record, 4, '0', STR_PAD_LEFT) . " - Activity: All Action Completed Performed");
                                            }

                                        );
                                        } catch (\Exception $e) {
                                            \Log::error('Mail failed to send: ' . $e->getMessage());
                                            }
                                        }
                                    // }
                                }

                                $list = Helpers::getInitiatorUserList($capa->division_id);

                                $userIds = collect($list)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                    $userId = $users->pluck('id')->implode(',');
                    if(!empty($users)){
                        try {
                            $history = new CapaAuditTrial();
                            $history->capa_id = $id;
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
                                    // if($u->q_m_s_divisions_id == $capa->division_id){
                                        $email = Helpers::getInitiatorEmail($u->user_id);
                                        if ($email !== null) {
                                            try {

                                        Mail::send(
                                            'mail.view-mail',
                                             ['data' => $capa, 'site' => "CAPA", 'history' => "All Action Completed", 'process' => 'CAPA', 'comment' => $capa->all_actions_completed_comment, 'user'=> Auth::user()->name],
                                            // function ($message) use ($email) {
                                            //     $message->to($email)
                                            //         ->subject("Document is sent By ".Auth::user()->name);
                                            // }
                                            function ($message) use ($email, $capa) {
                                                $message->to($email)
                                                ->subject("QMS Notification: Capa, Record #" . str_pad($capa->record, 4, '0', STR_PAD_LEFT) . " - Activity: All Action Completed Performed");
                                            }
                                        );
                                        } catch (\Exception $e) {
                                            \Log::error('Mail failed to send: ' . $e->getMessage());
                                            }
                                        }
                                    // }
                                }

                    $capa->update();
                    toastr()->success('Document Sent');
                    return back();
                }else {
                toastr()->error('E-signature Not match');
                return back();
            }

         }
     }


    public function capaCancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $capa = Capa::find($id);
            $lastDocument = Capa::find($id);

            $capa->stage = "0";
            $capa->status = "Closed-Cancelled";
            $capa->cancelled_by = Auth::user()->name;
            $capa->cancelled_on = Carbon::now()->format('d-M-Y');
            $capa->cancelled_comment = $request->comment;

                    $history = new CapaAuditTrial();
                    $history->capa_id = $id;
                    $history->activity_type = 'Cancelled By, Cancelled On';
                    if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->cancelled_by . ' , ' . $lastDocument->cancelled_on;
                    }
                    $history->previous ="";
                    $history->action = 'Cancelled';
                    $history->current = $capa->all_actions_completed_by . ' , ' . $capa->all_actions_completed_on;
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state =  $capa->status;
                    $history->stage = 'Cancelled';
                    if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();

                    $list = Helpers::getInitiatorUserList($capa->division_id);

                    $userIds = collect($list)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                    $userId = $users->pluck('id')->implode(',');
                    if(!empty($users)){
                        try {
                            $history = new CapaAuditTrial();
                            $history->capa_id = $id;
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
                            $history->role_name = "Initiator";
                            $history->save(); 
                        } catch (\Throwable $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                                foreach ($list as $u) {
                                    // if($u->q_m_s_divisions_id == $capa->division_id){
                                        $email = Helpers::getInitiatorEmail($u->user_id);
                                        if ($email !== null) {
                                            try {

                                        Mail::send(
                                            'mail.view-mail',
                                            ['data' => $capa, 'site' => "CAPA", 'history' => "Cancelled", 'process' => 'CAPA', 'comment' => $capa->cancelled_comment, 'user'=> Auth::user()->name],
                                            // function ($message) use ($email) {
                                            //     $message->to($email)
                                            //         ->subject("Document is Sent By ".Auth::user()->name);
                                            // }
                                            function ($message) use ($email, $capa) {
                                                $message->to($email)
                                                ->subject("QMS Notification: Capa, Record #" . str_pad($capa->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancelled Performed");
                                            }
                                        );
                                        } catch (\Exception $e) {
                                            \Log::error('Mail failed to send: ' . $e->getMessage());
                                            }
                                        }
                                    // }
                                }
                    $list = Helpers::getQAHeadUserList($capa->division_id);
                    
                    $userIds = collect($list)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                    $userId = $users->pluck('id')->implode(',');
                    if(!empty($users)){
                        try {
                            $history = new CapaAuditTrial();
                            $history->capa_id = $id;
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
                            $history->role_name = "QA Head/Designee";
                            $history->save(); 
                        } catch (\Throwable $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    } 
                                foreach ($list as $u) {
                                    // if($u->q_m_s_divisions_id == $capa->division_id){
                                        $email = Helpers::getQAHeadEmail($u->user_id);
                                        if ($email !== null) {
                                            try {

                                        Mail::send(
                                            'mail.view-mail',
                                            ['data' => $capa, 'site' => "CAPA", 'history' => "Cancelled", 'process' => 'CAPA', 'comment' => $capa->cancelled_comment, 'user'=> Auth::user()->name],
                                            // function ($message) use ($email) {
                                            //     $message->to($email)
                                            //         ->subject("Document is Sent By ".Auth::user()->name);
                                            // }
                                            function ($message) use ($email, $capa) {
                                                $message->to($email)
                                                ->subject("QMS Notification: Capa, Record #" . str_pad($capa->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancelled Performed");
                                            }
                                        );
                                        } catch (\Exception $e) {
                                            \Log::error('Mail failed to send: ' . $e->getMessage());
                                            }
                                        }
                                    // }
                                }


            $capa->update();
            $history = new CapaHistory();
            $history->type = "Capa";
            $history->doc_id = $id;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->stage_id = $capa->stage;
            $history->status = $capa->status;
            $history->save();

            // $list = Helpers::getInitiatorUserList();
            // foreach ($list as $u) {
            //     if($u->q_m_s_divisions_id == $capa->division_id){
            //       $email = Helpers::getInitiatorEmail($u->user_id);
            //       if ($email !== null) {

            //         Mail::send(
            //             'mail.view-mail',
            //             ['data' => $capa],
            //             function ($message) use ($email) {
            //                 $message->to($email)
            //                     ->subject("Cancelled By ".Auth::user()->name);
            //             }
            //          );
            //       }
            //     }
            // }

            toastr()->success('Document Sent');
            return back();
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function capa_qa_more_info(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $capa = Capa::find($id);
            $lastDocument = Capa::find($id);
            if ($capa->stage == 2) {
                $capa->stage = "1";
                $capa->status = "Opened";
                $capa->more_info_review_by = Auth::user()->name;
                $capa->more_info_review_on = Carbon::now()->format('d-M-Y');
                $capa->more_info_review_comment = $request->comment;



                    $history = new CapaAuditTrial();
                    $history->capa_id = $id;
                    $history->activity_type = 'More Info Required By, More Info Required On';
                    if (is_null($lastDocument->more_info_review_by) || $lastDocument->more_info_review_by === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->more_info_review_by . ' , ' . $lastDocument->more_info_review_on;
                    }

                    //$history->previous = "";
                    $history->action = 'More Info Required';
                    $history->current = $capa->more_info_review_by . ' , ' . $capa->more_info_review_on;
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Opened";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'More Info Required';
                    if (is_null($lastDocument->more_info_review_by) || $lastDocument->more_info_review_by === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();

                    $list = Helpers::getInitiatorUserList($capa->division_id);

                    $userIds = collect($list)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                    $userId = $users->pluck('id')->implode(',');
                    if(!empty($users)){
                        try {
                            $history = new CapaAuditTrial();
                            $history->capa_id = $id;
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
                            $history->change_from = "Opened";
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
                                // if($u->q_m_s_divisions_id == $capa->division_id){
                                    $email = Helpers::getInitiatorEmail($u->user_id);
                                        if ($email !== null) {
                                        try {

                                        Mail::send(
                                            'mail.view-mail',
                                            ['data' => $capa, 'site' => "CAPA", 'history' => "More Info Required", 'process' => 'CAPA', 'comment' => $capa->more_info_review_comment, 'user'=> Auth::user()->name],
                                        // function ($message) use ($email) {
                                        //     $message->to($email)
                                        //         ->subject("Document is sent By ".Auth::user()->name);
                                        // }
                                        function ($message) use ($email, $capa) {
                                            $message->to($email)
                                            ->subject("QMS Notification: Capa, Record #" . str_pad($capa->record, 4, '0', STR_PAD_LEFT) . " - Activity: More Info Required Performed");
                                        }
                                        );
                                    } catch (\Exception $e) {
                                        \Log::error('Mail failed to send: ' . $e->getMessage());
                                        }
                                    }
                                // }
                            }

                $capa->update();
                toastr()->success('Document Sent');
                return back();
            }

        if($capa->stage == 3){
        $capa->stage = "2";
        $capa->status = "Pending CAPA plan";
        $capa->qa_more_info_required_by = Auth::user()->name;
        $capa->qa_more_info_required_on = Carbon::now()->format('d-M-Y');
        $capa->qa_more_info_required_comment = $request->comment;

                    $history = new CapaAuditTrial();
                    $history->capa_id = $id;
                    $history->activity_type = 'QA More Info Required By, QA More Info Required On';
                    if (is_null($lastDocument->qa_more_info_required_by) || $lastDocument->qa_more_info_required_by === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->qa_more_info_required_by . ' , ' . $lastDocument->qa_more_info_required_on;
                    }
                    //$history->previous = "";
                    $history->action = 'QA More Info Required';
                    $history->current = $capa->qa_more_info_required_by . ' , ' . $capa->qa_more_info_required_on;
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Pending CAPA Plan";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'QA More Info Required';
                    if (is_null($lastDocument->qa_more_info_required_by) || $lastDocument->qa_more_info_required_by === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();

                    $list = Helpers::getHodUserList($capa->division_id);

                    $userIds = collect($list)->pluck('user_id')->toArray();
            $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
            $userId = $users->pluck('id')->implode(',');
            if(!empty($users)){
                try {
                    $history = new CapaAuditTrial();
                    $history->capa_id = $id;
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
                    $history->change_from = "Pending CAPA plan";
                    $history->stage = "";
                    $history->action_name = "";
                    $history->mailUserId = $userId;
                    $history->role_name = "HOD/Designee";
                    $history->save(); 
                } catch (\Throwable $e) {
                    \Log::error('Mail failed to send: ' . $e->getMessage());
                }
            } 
                    foreach ($list as $u) {
                        // if($u->q_m_s_divisions_id == $capa->division_id){
                            $email = Helpers::getHODEmail($u->user_id);
                             if ($email !== null) {
                                try {

                              Mail::send(
                                  'mail.view-mail',
                                  ['data' => $capa, 'site' => "CAPA", 'history' => "More Info Required", 'process' => 'CAPA', 'comment' => $capa->more_info_review_comment, 'user'=> Auth::user()->name],
                                // function ($message) use ($email) {
                                //     $message->to($email)
                                //         ->subject("Document is sent By ".Auth::user()->name);
                                // }
                                function ($message) use ($email, $capa) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Capa, Record #" . str_pad($capa->record, 4, '0', STR_PAD_LEFT) . " - Activity: More Info Required Performed");
                                }
                              );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            }
                        // }
                    }


        $capa->update();

        $history = new CapaHistory();
        $history->type = "Capa";
        $history->doc_id = $id;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->stage_id = $capa->stage;
        $history->status = $capa->status;
        $history->save();
        toastr()->success('Document Sent');
        return back();
        }

        if ($capa->stage == 4) {
            $capa->stage = "3";
            $capa->status = "CAPA In Progress";
            $capa->reject_more_info_requierd_by = Auth::user()->name;
            $capa->reject_more_info_requierd_on = Carbon::now()->format('d-M-Y');
            $capa->reject_more_info_requierd_comment = $request->comments;
            $capa->update();
            $history = new CapaAuditTrial();
                    $history->capa_id = $id;
                    $history->activity_type = 'Rejected By, Rejected On';
                    if (is_null($lastDocument->reject_more_info_requierd_by) || $lastDocument->reject_more_info_requierd_by === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->reject_more_info_requierd_by . ' , ' . $lastDocument->reject_more_info_requierd_on;
                    }
                    //  $history->previous = "";
                    $history->action = 'Reject';
                    $history->current = $capa->reject_more_info_requierd_by . ' , ' . $capa->reject_more_info_requierd_on;
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "CAPA In Progress";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Reject';
                    if (is_null($lastDocument->reject_more_info_requierd_by) || $lastDocument->reject_more_info_requierd_by === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
        $capa->update();
        $history = new CapaHistory();
        $history->type = "Capa";
        $history->doc_id = $id;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->stage_id = $capa->stage;
        $history->status = $capa->status;
        $history->save();

            

            toastr()->success('Document Sent');
            return back();
        }
        }
        else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function capa_reject(Request $request, $id)
    {

        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $capa = Capa::find($id);
            $lastDocument = Capa::find($id);
            if ($capa->stage == 2) {
                $capa->stage = "1";
                $capa->status = "Opened";
                $capa->rejected_by = Auth::user()->name;
                $capa->rejected_on = Carbon::now()->format('d-M-Y');
                $capa->rejected_comment = $request->comments;

                $capa->update();
                $history = new CapaHistory();
                $history->type = "Capa";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $capa->stage;
                $history->status = "Opened";

                $list = Helpers::getInitiatorUserList();

                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new CapaAuditTrial();
                        $history->capa_id = $id;
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
                        $history->change_from = "Opened";
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
                    if($u->q_m_s_divisions_id == $capa->division_id){
                    $email = Helpers::getInitiatorEmail($u->user_id);
                    if ($email !== null) {

                        Mail::send(
                            'mail.view-mail',
                            ['data' => $capa, 'site' => "CAPA", 'history' => "Cancel", 'process' => 'CAPA', 'comment' => $request->rejected_comment, 'user'=> Auth::user()->name],
                            // function ($message) use ($email) {
                            //     $message->to($email)
                            //         ->subject("More Info Required ".Auth::user()->name);
                            // }
                            function ($message) use ($email, $capa) {
                                $message->to($email)
                                ->subject("QMS Notification: Capa, Record #" . str_pad($capa->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed");
                            }
                        );
                      }
                    }
                }
                $history->save();

                toastr()->success('Document Sent');
                return back();
            }
            if ($capa->stage == 3) {
                $capa->stage = "2";
                $capa->status = "Pending CAPA Plan";
                $capa->qa_more_info_required_by = Auth::user()->name;
                $capa->qa_more_info_required_on = Carbon::now()->format('d-M-Y');
                // $capa->qa_more_info_required_comment = $request->comments;


                        $history = new CapaAuditTrial();
                        $history->capa_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = "";
                        $history->current = $capa->qa_more_info_required_by;
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = 'Qa More Info Required';
                        $history->save();

                        $list = Helpers::getHodUserList($capa->division_id);

                        $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new CapaAuditTrial();
                        $history->capa_id = $id;
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
                        $history->change_from = "CAPA In Progress";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "HOD/Designee";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                } 
                        foreach ($list as $u) {
                            // if($u->q_m_s_divisions_id == $capa->division_id){
                                $email = Helpers::getHODEmail($u->user_id);
                                 if ($email !== null) {
                                    try {
    
                                  Mail::send(
                                      'mail.view-mail',
                                      ['data' => $capa, 'site' => "CAPA", 'history' => "More Info Required", 'process' => 'CAPA', 'comment' => $capa->more_info_review_comment, 'user'=> Auth::user()->name],
                                    // function ($message) use ($email) {
                                    //     $message->to($email)
                                    //         ->subject("Document is sent By ".Auth::user()->name);
                                    // }
                                    function ($message) use ($email, $capa) {
                                        $message->to($email)
                                        ->subject("QMS Notification: Capa, Record #" . str_pad($capa->record, 4, '0', STR_PAD_LEFT) . " - Activity: More Info Required Performed");
                                    }
                                  );
                                } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                    }
                                }
                            // }
                        }
    
    

                $capa->update();
                $history = new CapaHistory();
                $history->type = "Capa";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $capa->stage;
                $history->status = "Pending CAPA Plan<";
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }




    public function CapaAuditTrial(Request $request, $id){
        $audit = CapaAuditTrial::where('capa_id', $id)->orderByDESC('id')->paginate(5);
        $today = Carbon::now()->format('d-m-y');
        $document = Capa::where('id', $id)->first();
        $document->originator = User::where('id', $document->initiator_id)->value('name');

        return view('frontend.capa.audit-trial', compact('audit', 'document', 'today'));
    }

    public function auditDetailsCapa($id)
    {

        $detail = CapaAuditTrial::find($id);

        $detail_data = CapaAuditTrial::where('activity_type', $detail->activity_type)->where('capa_id', $detail->capa_id)->latest()->get();

        $doc = Capa::where('id', $detail->capa_id)->first();

        $doc->origiator_name = User::find($doc->initiator_id);
        return view('frontend.capa.audit-trial-inner', compact('detail', 'doc', 'detail_data'));
    }

    public function child_change_control(Request $request, $id)
    {
        $cft =[];
        $parent_id = $id;
        $parent_type = "CAPA";
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date= $formattedDate->format('d-M-Y');
        $parent_record = Capa::where('id', $id)->value('record');
        $parent_record = str_pad($parent_record, 4, '0', STR_PAD_LEFT);
        $parent_division_id = Capa::where('id', $id)->value('division_id');
        $parent_initiator_id = Capa::where('id', $id)->value('initiator_id');
        $parent_intiation_date = Capa::where('id', $id)->value('intiation_date');
        $parent_short_description = Capa::where('id', $id)->value('short_description');
        $hod = User::where('role', 4)->get();
        $pre = CC::all();
        $changeControl = OpenStage::find(1);
        if(!empty($changeControl->cft)) $cft = explode(',', $changeControl->cft);
        // return $capa_data;
        if ($request->child_type == "Change_control") {
            return view('frontend.change-control.new-change-control', compact('cft','pre','hod','parent_short_description', 'parent_initiator_id', 'parent_intiation_date', 'parent_division_id', 'parent_record', 'record_number', 'due_date', 'parent_id', 'parent_type'));
        }
        if ($request->child_type == "extension")
        {
            $parent_due_date = "";
            $parent_id = $id;
            $parent_name = $request->parent_name;
            $parent_division_id = Capa::where('id', $id)->value('division_id');
            if ($request->due_date) {
                $parent_due_date = $request->due_date;
            }
            $parentDivisionId = Capa::where('id', $id)->value('division_id');
            $record_number = ((RecordNumber::first()->value('counter')) + 1);
            $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
            return view('frontend.extension.extension_new', compact('parent_id','parent_record','parentDivisionId','parent_division_id', 'parent_name','parent_type', 'record_number', 'parent_due_date'));
        }
        $old_record = ActionItem::select('id', 'division_id', 'record')->get();
        if ($request->child_type == "Action_Item") {
            $parent_name = "CAPA";

            return view('frontend.forms.action-item', compact('old_record','parent_short_description', 'parent_initiator_id', 'parent_intiation_date', 'parent_name', 'parent_division_id', 'parent_record', 'record_number', 'due_date', 'parent_id', 'parent_type'));
        }

        if ($request->child_type == "RCA") {
            $parent_due_date = "";
            $parent_id = $id;
            $parent_name = $request->parent_name;
            if ($request->due_date) {
                $due_date = $request->due_date;
            }
            $parentDivisionId = Capa::where('id', $id)->value('division_id');
            $parent_division_id = Capa::where('id', $id)->value('division_id');
            $record_number = ((RecordNumber::first()->value('counter')) + 1);
            $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
            return view('frontend.forms.root-cause-analysis', compact('parent_id','parent_record','parentDivisionId','parent_division_id', 'parent_name','parent_type', 'record_number', 'parent_due_date','due_date'));
        }

        else {
            return view('frontend.forms.effectiveness-check', compact('old_record','parent_short_description', 'parent_initiator_id', 'parent_intiation_date', 'parent_division_id', 'parent_record', 'record_number', 'due_date', 'parent_id', 'parent_type'));
        }
    }

    public function effectiveness_check(Request $request, $id)
    {
        $parentId = $id;
        $parentType = "CAPA";
        $old_record = EffectivenessCheck::select('id', 'division_id', 'record')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date= $formattedDate->format('Y-m-d');
        $division = Capa::find($id);
        $divisionId = $division->division_id;
        return view("frontend.forms.effectiveness-check", compact('due_date','parentId','parentType', 'record_number', 'divisionId', 'old_record'));
    }


    public static function singleReport($id)
    {
        $data = Capa::find($id);
        if (!empty($data)) {
            $data->Product_Details = CapaGrid::where('capa_id', $id)->where('type', "Product_Details")->first();
            $data->Instruments_Details = CapaGrid::where('capa_id', $id)->where('type', "Instruments_Details")->first();
            $data->Material_Details = CapaGrid::where('capa_id', $id)->where('type', "Material_Details")->first();
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.capa.singleReport', compact('data'))
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
            $canvas->page_text($width / 2.5, $height / 2, $data->status, null, 25, [0, 0, 0], 2, 6, -20);
            return $pdf->stream('CAPA' . $id . '.pdf');
        }
    }

    public static function auditReport($id)
    {
        $doc = Capa::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = CapaAuditTrial::where('capa_id', $id)->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.capa.auditReport', compact('data', 'doc'))
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
            $canvas->page_text($width / 2.5, $height / 2, $doc->status, null, 25, [0, 0, 0], 2, 6, -20);
            return $pdf->stream('CAPA-Audit' . $id . '.pdf');
        }
    }

   
}
