<?php

namespace App\Http\Controllers;

use App\Models\ActionItem;
use App\Models\Capa;
use App\Models\RecordNumber;
use App\Models\RiskAuditTrail;
use App\Models\RiskManagement;
use App\Models\RiskAssesmentGrid;
use App\Models\RoleGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use PDF;
use Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;

class RiskManagementController extends Controller
{

    public function risk()
    {
        $old_record = RiskManagement::select('id', 'division_id', 'record')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');


        return view("frontend.forms.risk-management", compact('due_date', 'record_number', 'old_record'));
    }

    public function store(Request $request)
    {
        // return dd($request);
        // return $request;



        if (!$request->short_description) {
            toastr()->info("Short Description is required");
            return redirect()->back()->withInput();
        }
        // return $request;
        $data = new RiskManagement();
        $data->form_type = "risk-assessment";
        $data->division_id = $request->division_id;
        $data->division_code = $request->division_code;
        $data->parent_id = $request->parent_id;
        $data->parent_type = $request->parent_type;
        //$data->record_number = $request->record_number;
        $data->record = ((RecordNumber::first()->value('counter')) + 1);
        $data->initiator_id = Auth::user()->id;
        $data->intiation_date = $request->intiation_date;
        $data->assign_to = $request->assign_to;
        $data->due_date = $request->due_date;
        $data->Initiator_Group = $request->Initiator_Group;
        $data->initiator_group_code = $request->initiator_group_code;
        $data->short_description = $request->short_description;
        $data->severity2_level = $request->severity2_level;
        $data->departments = implode(',', $request->departments);
        $data->source_of_risk = $request->source_of_risk;
        $data->type = $request->type;
        $data->priority_level = $request->priority_level;
        $data->description = $request->description;
        $data->comments = $request->comments;
        // $data->open_date = $request->open_date;
        // $data->team_members = implode(',', $request->team_members);
        // $data->zone = $request->zone;
        // $data->country = $request->country;
        // $data->state = $request->state;
        // $data->city = $request->city;
        $data->departments2 = implode(',', $request->departments2);
        $data->source_of_risk2 = $request->source_of_risk2;
        $data->site_name = $request->site_name;
        $data->building = $request->building;
        $data->floor = $request->floor;
        $data->room = $request->room;
        // $data->related_record = json_encode($request->related_record);
        $data->duration = $request->duration;
        $data->hazard = $request->hazard;
        $data->room2 = $request->room2;
        $data->regulatory_climate = $request->regulatory_climate;
        $data->Number_of_employees = $request->Number_of_employees;
        $data->risk_management_strategy = $request->risk_management_strategy;

        $data->schedule_start_date1 = $request->schedule_start_date1;
        $data->schedule_end_date1 = $request->schedule_end_date1;
        $data->estimated_man_hours = $request->estimated_man_hours;
        $data->estimated_cost = $request->estimated_cost;
        $data->currency = $request->currency;
        $data->justification = $request->justification;

        $data->root_cause_methodology = implode(',', $request->root_cause_methodology);
        $data->root_cause_description = $request->root_cause_description;
        $data->investigation_summary = $request->investigation_summary;
        $data->severity_rate = $request->severity_rate;
        $data->occurrence = $request->occurrence;
        $data->detection = $request->detection;
        $data->rpn = $request->rpn;

        // $data->measurement = json_encode($request->measurement);
        // $data->materials = json_encode($request->materials);
        // $data->methods = json_encode($request->methods);
        // $data->environment = json_encode($request->environment);
        //$data->manpower = json_encode($request->manpower);
        //$data->machine = json_encode($request->machine);
        //$data->problem_statement1 = ($request->problem_statement1);
        // $data->why_problem_statement = $request->why_problem_statement;
        // $data->why_1 = json_encode($request->why_1);
        // $data->why_2 = json_encode($request->why_2);
        // $data->why_3 = json_encode($request->why_3);
        // $data->why_4 = json_encode($request->why_4);
        // $data->why_5 = json_encode($request->why_5);
        // $data->root_cause = $request->root_cause;
        // $data->what_will_be = $request->what_will_be;
        // $data->what_will_not_be = $request->what_will_not_be;
        // $data->what_rationable = $request->what_rationable;
        // $data->where_will_be = $request->where_will_be;
        // $data->where_will_not_be = $request->where_will_not_be;
        // $data->where_rationable = $request->where_rationable;
        // $data->when_will_be = $request->when_will_be;
        // $data->when_will_not_be = $request->when_will_not_be;
        // $data->when_rationable = $request->when_rationable;
        // $data->coverage_will_be = $request->coverage_will_be;
        // $data->coverage_will_not_be = $request->coverage_will_not_be;
        // $data->coverage_rationable = $request->coverage_rationable;
        // $data->who_will_be = $request->who_will_be;
        // $data->who_will_not_be = $request->who_will_not_be;
        // $data->who_rationable = $request->who_rationable;
        // $data->training_require = $request->training_require;
        // $data->cost_of_risk = $request->cost_of_risk;
        // $data->environmental_impact = $request->environmental_impact;
        // $data->public_perception_impact = $request->public_perception_impact;
        // $data->calculated_risk = $request->calculated_risk;
        // $data->impacted_objects = $request->impacted_objects;
        //  return $data;
        $data->residual_risk = $request->residual_risk;
        $data->residual_risk_impact = $request->residual_risk_impact;
        $data->residual_risk_probability = $request->residual_risk_probability;
        $data->detection2 = $request->detection2;
        $data->rpn2 = $request->rpn2;
        $data->comments2 = $request->comments2;

        $data->mitigation_required = $request->mitigation_required;
        $data->mitigation_plan = $request->mitigation_plan;
        $data->mitigation_due_date = $request->mitigation_due_date;
        $data->mitigation_status = $request->mitigation_status;
        $data->mitigation_status_comments = $request->mitigation_status_comments;
        $data->impact = $request->impact;
        $data->criticality = $request->criticality;
        $data->impact_analysis = $request->impact_analysis;
        $data->risk_analysis = $request->risk_analysis;
        $data->refrence_record =  implode(',', $request->refrence_record);
        $data->due_date_extension = $request->due_date_extension;

        // $data->analysisN2 = $request->analysisN2;
        // $data->analysisRPN2 = $request->analysisRPN2;
        // $data->initial_rpn = $request->initial_rpn;
        //$data->severity = $request->severity;
        //$data->occurance = $request->occurance;



        if (!empty($request->reference)) {
            $files = [];
            if ($request->hasfile('reference')) {
                foreach ($request->file('reference') as $file) {
                    $name = $request->name . ' reference' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $data->reference = json_encode($files);
        }
        $data->status = 'Opened';
        $data->stage = 1;
        // return $data;
        $data->save();

        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();



        // -----------grid=------
        $data1 = new RiskAssesmentGrid();
        $data1->risk_id = $data->id;
        $data1->type = "effect_analysis";
        if (!empty($request->risk_factor)) {
            $data1->risk_factor = serialize($request->risk_factor);
        }
        if (!empty($request->risk_element)) {
            $data1->risk_element = serialize($request->risk_element);
        }
        if (!empty($request->problem_cause)) {
            $data1->problem_cause = serialize($request->problem_cause);
        }
        if (!empty($request->existing_risk_control)) {
            $data1->existing_risk_control = serialize($request->existing_risk_control);
        }
        if (!empty($request->initial_severity)) {
            $data1->initial_severity = serialize($request->initial_severity);
        }
        if (!empty($request->initial_detectability)) {
            $data1->initial_detectability = serialize($request->initial_detectability);
        }
        if (!empty($request->initial_probability)) {
            $data1->initial_probability = serialize($request->initial_probability);
        }
        if (!empty($request->initial_rpn)) {
            $data1->initial_rpn = serialize($request->initial_rpn);
        }
        if (!empty($request->risk_acceptance)) {
            $data1->risk_acceptance = serialize($request->risk_acceptance);
        }
        if (!empty($request->risk_control_measure)) {
            $data1->risk_control_measure = serialize($request->risk_control_measure);
        }
        if (!empty($request->residual_severity)) {
            $data1->residual_severity = serialize($request->residual_severity);
        }
        if (!empty($request->residual_probability)) {
            $data1->residual_probability = serialize($request->residual_probability);
        }
        if (!empty($request->residual_detectability)) {
            $data1->residual_detectability = serialize($request->residual_detectability);
        }
        if (!empty($request->residual_rpn)) {
            $data1->residual_rpn = serialize($request->residual_rpn);
        }
        if (!empty($request->risk_acceptance2)) {
            $data1->risk_acceptance2 = serialize($request->risk_acceptance2);
        }
        if (!empty($request->mitigation_proposal)) {
            $data1->mitigation_proposal = serialize($request->mitigation_proposal);
        }

        $data1->save();

        // ---------------------------------------
        $data2 = new RiskAssesmentGrid();
        $data2->risk_id = $data->id;
        $data2->type = "fishbone";

        if (!empty($request->measurement)) {
            $data2->measurement = serialize($request->measurement);
        }
        if (!empty($request->materials)) {
            $data2->materials = serialize($request->materials);
        }
        if (!empty($request->methods)) {
            $data2->methods = serialize($request->methods);
        }
        if (!empty($request->environment)) {
            $data2->environment = serialize($request->environment);
        }
        if (!empty($request->manpower)) {
            $data2->manpower = serialize($request->manpower);
        }

        if (!empty($request->machine)) {
            $data2->machine = serialize($request->machine);
        }

        if (!empty($request->problem_statement)) {
            $data2->problem_statement = $request->problem_statement;
        }
        $data2->save();
        // =-------------------------------

        $data3 = new RiskAssesmentGrid();
        $data3->risk_id = $data->id;
        $data3->type = "why_chart";
        if (!empty($request->why_problem_statement)) {
            $data3->why_problem_statement = $request->why_problem_statement;
        }
        if (!empty($request->why_1)) {
            $data3->why_1 = serialize($request->why_1);
        }
        if (!empty($request->why_2)) {
            $data3->why_2 = serialize($request->why_2);
        }
        if (!empty($request->why_3)) {
            $data3->why_3 = serialize($request->why_3);
        }
        if (!empty($request->why_4)) {
            $data3->why_4 = serialize($request->why_4);
        }

        if (!empty($request->why_5)) {
            $data3->why_5 = serialize($request->why_5);
        }
    //    dd($request->why_root_cause);
        if (!empty($request->why_root_cause)) {
            $data3->why_root_cause = $request->why_root_cause;
        }
        $data3->save();

        // --------------------------------------------
        $data4 = new RiskAssesmentGrid();
        $data4->risk_id = $data->id;
        $data4->type = "what_who_where";
        if (!empty($request->what_will_be)) {
            $data4->what_will_be = $request->what_will_be;
        }
        if (!empty($request->what_will_not_be)) {
            $data4->what_will_not_be = $request->what_will_not_be;
        }
        if (!empty($request->what_rationable)) {
            $data4->what_rationable = $request->what_rationable;
        }
        if (!empty($request->where_will_be)) {
            $data4->where_will_be = $request->where_will_be;
        }
        if (!empty($request->where_will_not_be)) {
            $data4->where_will_not_be = $request->where_will_not_be;
        }
        if (!empty($request->where_rationable)) {
            $data4->where_rationable = $request->where_rationable;
        }
        if (!empty($request->coverage_will_be)) {
            $data4->coverage_will_be = $request->coverage_will_be;
        }
        if (!empty($request->coverage_will_not_be)) {
            $data4->coverage_will_not_be = $request->coverage_will_not_be;
        }
        if (!empty($request->coverage_rationable)) {
            $data4->coverage_rationable = $request->coverage_rationable;
        }
        if (!empty($request->who_will_be)) {
            $data4->who_will_be = $request->who_will_be;
        }
        if (!empty($request->who_will_not_be)) {
            $data4->who_will_not_be = $request->who_will_not_be;
        }
        if (!empty($request->who_rationable)) {
            $data4->who_rationable = $request->who_rationable;
        } if (!empty($request->when_will_be)) {
            $data4->when_will_be = $request->when_will_be;
        }
         if (!empty($request->when_will_not_be)) {
            $data4->when_will_not_be = $request->when_will_not_be;
        }
         if (!empty($request->when_rationable)) {
            $data4->when_rationable = $request->when_rationable;
        }
        $data4->save();


        $data5 = new RiskAssesmentGrid();
        $data5->risk_id = $data->id;
        $data5->type = "Action_Plan";

        if (!empty($request->action)) {
            $data5->action = serialize($request->action);
        }
        if (!empty($request->responsible)) {
            $data5->responsible = serialize($request->responsible);
        }
        if (!empty($request->deadline)) {
            $data5->deadline = serialize($request->deadline);
        }
        if (!empty($request->item_static)) {
            $data5->item_static = serialize($request->item_static);
        }

        $data5->save();

        $data6 = new RiskAssesmentGrid();
        $data6->risk_id = $data->id;
        $data6->type = "Mitigation_Plan_Details";

        if (!empty($request->mitigation_steps)) {
            $data6->mitigation_steps = serialize($request->mitigation_steps);
        }
        if (!empty($request->deadline2)) {
            $data6->deadline2 = serialize($request->deadline2);
        }
        if (!empty($request->responsible_person)) {
            $data6->responsible_person = serialize($request->responsible_person);
        }
        if (!empty($request->status)) {
            $data6->status = serialize($request->status);
        }
        if (!empty($request->remark)) {
            $data6->remark = serialize($request->remark);
        }

        $data6->save();
        // ------------------------------------------------
        if (!empty($data->division_code)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Site/Location Code';
            $history->previous = "Null";
            $history->current =$data->division_code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->initiator_id)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Initiator';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($data->initiator_id);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->intiation_date)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Date of Initiation';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($data->intiation_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($data->record)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Record Number';
            $history->previous = "Null";
            $history->current = Helpers::getDivisionName($data->division_id) . '/RA/' . Helpers::year($data->created_at) . '/' . str_pad($data->record, 4, '0', STR_PAD_LEFT);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->assign_to)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Assigned To';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($data->assign_to);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->due_date)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Due Date';
            $history->previous = "Null";
            $history->current = $data->due_date;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->Initiator_Group)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Initiator Group';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorGroupFullName($data->Initiator_Group);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->initiator_group_code)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = "Null";
            $history->current = $data->initiator_group_code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->short_description)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Short Description';
            $history->previous = "Null";
            $history->current = $data->short_description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->severity2_level)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Severity Level';
            $history->previous = "Null";
            $history->current = $data->severity2_level;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->departments)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Department(s)';
            $history->previous = "Null";
            $history->current = $data->departments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        // if (!empty($data->team_members)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Team Members';
        //     $history->previous = "Null";
        //     $history->current = $data->team_members;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->change_from = "Initiation";
        //     $history->change_to = "Opened";
        //     $history->action_name = "Create";
        //     $history->save();
        // }

        if (!empty($data->source_of_risk)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Source of Risk/Opportunity';
            $history->previous = "Null";
            $history->current = $data->source_of_risk;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->type)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Type';
            $history->previous = "Null";
            $history->current = $data->type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->priority_level)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Priority Level';
            $history->previous = "Null";
            $history->current = $data->priority_level;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->description)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Risk/Opportunity Description';
            $history->previous = "Null";
            $history->current = $data->description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        // if (!empty($data->country)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Country';
        //     $history->previous = "Null";
        //     $history->current = $data->country;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->change_from = "Initiation";
        //     $history->change_to = "Opened";
        //     $history->action_name = "Create";
        //     $history->save();
        // }

        // if (!empty($data->state)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'State/District';
        //     $history->previous = "Null";
        //     $history->current = $data->state;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->change_from = "Initiation";
        //     $history->change_to = "Opened";
        //     $history->action_name = "Create";
        //     $history->save();
        // }

        // if (!empty($data->city)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'City';
        //     $history->previous = "Null";
        //     $history->current = $data->city;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->change_from = "Initiation";
        //     $history->change_to = "Opened";
        //     $history->action_name = "Create";
        //     $history->save();
        // }

        // if (!empty($data->description)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Description';
        //     $history->previous = "Null";
        //     $history->current = $data->description;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->change_from = "Initiation";
        //     $history->change_to = "Opened";
        //     $history->action_name = "Create";
        //     $history->save();
        // }

        if (!empty($data->comments)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Risk/Opportunity Comments';
            $history->previous = "Null";
            $history->current = $data->comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->departments2)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Department(s)';
            $history->previous = "Null";
            $history->current = $data->departments2;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->source_of_risk2)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Source of Risk';
            $history->previous = "Null";
            $history->current = $data->source_of_risk2;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($data->site_name)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Site Name';
            $history->previous = "Null";
            $history->current = $data->site_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->building)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Building';
            $history->previous = "Null";
            $history->current = $data->building;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->floor)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Floor';
            $history->previous = "Null";
            $history->current = $data->floor;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->room)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Room.';
            $history->previous = "Null";
            $history->current = $data->room;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->duration)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Duration';
            $history->previous = "Null";
            $history->current = $data->duration;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->hazard)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Hazard';
            $history->previous = "Null";
            $history->current = $data->hazard;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->room2)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Room';
            $history->previous = "Null";
            $history->current = $data->room2;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->regulatory_climate)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Regulatory Climate';
            $history->previous = "Null";
            $history->current = $data->regulatory_climate;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->Number_of_employees)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Number Of Employees';
            $history->previous = "Null";
            $history->current = $data->Number_of_employees;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        // if (!empty($internalAudit->refrence_record)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Reference Recores';
        //     $history->previous = "Null";
        //     $history->current = $data->refrence_record;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }

        if (!empty($data->risk_management_strategy)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Risk Management Strategy';
            $history->previous = "Null";
            $history->current = $data->risk_management_strategy;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->schedule_start_date1)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Scheduled Start Date';
            $history->previous = "Null";
            $history->current =  Helpers::getdateFormat($data->schedule_start_date1);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($data->schedule_end_date1)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Scheduled End Date';
            $history->previous = "Null";
            $history->current =  Helpers::getdateFormat($data->schedule_end_date1);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->estimated_man_hours)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Estimated Man Hours';
            $history->previous = "Null";
            $history->current = $data->estimated_man_hours;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->estimated_cost)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Estimated Cost';
            $history->previous = "Null";
            $history->current = $data->estimated_cost;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->currency)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Currency';
            $history->previous = "Null";
            $history->current = $data->currency;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        // if (!empty($data->training_require)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Training Require';
        //     $history->previous = "Null";
        //     $history->current = $data->training_require;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->change_from = "Initiation";
        //     $history->change_to = "Opened";
        //     $history->action_name = "Create";
        //     $history->save();
        // }

        if (!empty($data->justification)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Justification';
            $history->previous = "Null";
            $history->current = $data->justification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->reference)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Work Group Attachments';
            $history->previous = "Null";
            $history->current = $data->reference;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        // if (!empty($data->cost_of_risk)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Cost Of Risk';
        //     $history->previous = "Null";
        //     $history->current = $data->cost_of_risk;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->change_from = "Initiation";
        //     $history->change_to = "Opened";
        //     $history->action_name = "Create";
        //     $history->save();
        // }

        // if (!empty($data->environmental_impact)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Environmental Impact';
        //     $history->previous = "Null";
        //     $history->current = $data->environmental_impact;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->change_from = "Initiation";
        //     $history->change_to = "Opened";
        //     $history->action_name = "Create";
        //     $history->save();
        // }

        // if (!empty($data->public_perception_impact)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Public Perception Impact';
        //     $history->previous = "Null";
        //     $history->current = $data->public_perception_impact;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->change_from = "Initiation";
        //     $history->change_to = "Opened";
        //     $history->action_name = "Create";
        //     $history->save();
        // }

        if (!empty($data->root_cause_methodology)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Root Cause Methodology';
            $history->previous = "Null";
            $history->current = implode(',', $request->root_cause_methodology);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->root_cause_description)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Root Cause Description';
            $history->previous = "Null";
            $history->current = $data->root_cause_description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->investigation_summary)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Investigation Summary';
            $history->previous = "Null";
            $history->current = $data->investigation_summary;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        // if (!empty($data->calculated_risk)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Calculated Risk';
        //     $history->previous = "Null";
        //     $history->current = $data->calculated_risk;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->change_from = "Initiation";
        //     $history->change_to = "Opened";
        //     $history->action_name = "Create";
        //     $history->save();
        // }

        // if (!empty($data->impacted_objects)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Impacted Objects';
        //     $history->previous = "Null";
        //     $history->current = $data->impacted_objects;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->change_from = "Initiation";
        //     $history->change_to = "Opened";
        //     $history->action_name = "Create";
        //     $history->save();
        // }

        if (!empty($data->severity_rate)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Severity Rate';
            $history->previous = "Null";
            // $history->current = $data->severity_rate;
            if($request->severity_rate == 1){
                $history->current = "Negligible";
            } elseif($request->severity_rate == 2){
                $history->current = "Moderate";
            } elseif($request->severity_rate == 3){
                $history->current = "Major";
            }else{
                $history->current = "Fatal";
            }
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->occurrence)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Occurrence';
            $history->previous = "Null";
            // $history->current = $data->occurrence;
            if($request->occurrence == 1){
                $history->current = "Very Likely";
            } elseif($request->occurrence == 2){
                $history->current = "Likely";
            } elseif($request->occurrence == 3){
                $history->current = "Unlikely";
            }elseif($request->occurrence == 4){
                $history->current = "Rare";
            }else{
                $history->current = "Extremely Unlikely";
            }
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->detection)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Detection';
            $history->previous = "Null";
            if($request->detection == 1){
                $history->current = "Very Likely";
            } elseif($request->detection == 2){
                $history->current = "Likely";
            } elseif($request->detection == 3){
                $history->current = "Unlikely";
            }elseif($request->detection == 4){
                $history->current = "Rare";
            }else{
                $history->current = "Impossible";
            }
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->rpn)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Rpn';
            $history->previous = "Null";
            $history->current = $data->rpn;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->residual_risk)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Residual Risk';
            $history->previous = "Null";
            $history->current = $data->residual_risk;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->residual_risk_impact)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Residual Risk Impact';
            $history->previous = "Null";
            // $history->current = $data->residual_risk_impact;
            if($request->residual_risk_impact == 1){
                $history->current = "High";
            } elseif($request->residual_risk_impact == 2){
                $history->current = "Low";
            } elseif($request->residual_risk_impact == 3){
                $history->current = "Medium";
            }else{
                $history->current = "None";
            }
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->residual_risk_probability)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Residual Risk Probability';
            $history->previous = "Null";
            // $history->current = $data->residual_risk_probability;
            if($request->residual_risk_probability == 1){
                $history->current = "High";
            } elseif($request->residual_risk_probability == 2){
                $history->current = "Medium";
            }else{
                $history->current = "Low";
            }
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->detection2)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Residual Detection';
            $history->previous = "Null";
            // $history->current = $data->detection2;
            if($request->detection2 == 1){
                $history->current = "Very Likely";
            } elseif($request->detection2 == 2){
                $history->current = "Likely";
            } elseif($request->detection2 == 3){
                $history->current = "Unlikely";
            }elseif($request->detection2 == 4){
                $history->current = "Rare";
            }else{
                $history->current = "Impossible";
            }
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->rpn2)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Residual RPN';
            $history->previous = "Null";
            $history->current = $data->rpn2;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->comments2)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Comments';
            $history->previous = "Null";
            $history->current = $data->comments2;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->mitigation_required)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Mitigation Required';
            $history->previous = "Null";
            $history->current = $data->mitigation_required;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($data->mitigation_plan)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Mitigation Plan';
            $history->previous = "Null";
            $history->current = $data->mitigation_plan;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($data->mitigation_due_date)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Scheduled End-Date';
            $history->previous = "Null";
            $history->current =  Helpers::getdateFormat($data->mitigation_due_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($data->mitigation_status)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Status of Mitigation';
            $history->previous = "Null";
            $history->current = $data->mitigation_status;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->mitigation_status_comments)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Mitigation Status Comments';
            $history->previous = "Null";
            $history->current = $data->mitigation_status_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->impact)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Impact';
            $history->previous = "Null";
            $history->current = $data->impact;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->criticality)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Criticality';
            $history->previous = "Null";
            $history->current = $data->criticality;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($data->impact_analysis)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Impact Analysis';
            $history->previous = "Null";
            $history->current = $data->impact_analysis;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($data->risk_analysis)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Risk Analysis';
            $history->previous = "Null";
            $history->current = $data->risk_analysis;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($data->refrence_record)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Reference Record';
            $history->previous = "Null";
            $history->current = implode(',', $request->refrence_record);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($data->due_date_extension)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = "Null";
            $history->current = $data->due_date_extension;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }
    public function riskUpdate(Request $request, $id)
    {

        if (!$request->short_description) {
            toastr()->info("Short Description is required");
            return redirect()->back()->withInput();
        }

        $lastDocument =  RiskManagement::find($id);
        $data =  RiskManagement::find($id);
        $data->division_code = $request->division_code;
        //$data->record_number = $request->record_number;
        // $data->open_date = $request->open_date;
        $data->assign_to = $request->assign_to;
        $data->Initiator_Group = $request->Initiator_Group;
        $data->initiator_group_code = $request->initiator_group_code;
        $data->short_description = $request->short_description;
        $data->severity2_level = $request->severity2_level;
        $data->departments = implode(',', $request->departments);
        $data->source_of_risk = $request->source_of_risk;
        // $data->due_date = $request->due_date;
        // $data->team_members = implode(',', $request->team_members);
        $data->type = $request->type;
        $data->priority_level = $request->priority_level;
        $data->description = $request->description;
        $data->comments = $request->comments;
        // $data->zone = $request->zone;
        // $data->country = $request->country;
        // $data->state = $request->state;
        // $data->city = $request->city;

        $data->departments2 = implode(',', $request->departments2);
        $data->source_of_risk2 = $request->source_of_risk2;
        $data->site_name = $request->site_name;
        $data->building = $request->building;
        $data->floor = $request->floor;
        $data->room = $request->room;
        // $data->related_record = json_encode($request->related_record);
        $data->duration = $request->duration;
        $data->hazard = $request->hazard;
        $data->room2 = $request->room2;
        $data->regulatory_climate = $request->regulatory_climate;
        $data->Number_of_employees = $request->Number_of_employees;
        $data->risk_management_strategy = $request->risk_management_strategy;

        $data->schedule_start_date1 = $request->schedule_start_date1;
        $data->schedule_end_date1 = $request->schedule_end_date1;
        $data->estimated_man_hours = $request->estimated_man_hours;
        $data->estimated_cost = $request->estimated_cost;
        $data->currency = $request->currency;
        $data->justification = $request->justification;

        $data->root_cause_methodology = implode(',', $request->root_cause_methodology);
        $data->root_cause_description = $request->root_cause_description;
        $data->investigation_summary = $request->investigation_summary;
        $data->severity_rate = $request->severity_rate;
        $data->occurrence = $request->occurrence;
        $data->detection = $request->detection;
        $data->rpn = $request->rpn;

        //$data->training_require = $request->training_require;
        // $data->cost_of_risk = $request->cost_of_risk;
        // $data->environmental_impact = $request->environmental_impact;
        // $data->public_perception_impact = $request->public_perception_impact;
        // $data->calculated_risk = $request->calculated_risk;
        // $data->impacted_objects = $request->impacted_objects;
        $data->residual_risk = $request->residual_risk;
        $data->residual_risk_impact = $request->residual_risk_impact;
        $data->residual_risk_probability = $request->residual_risk_probability;
        $data->detection2 = $request->detection2;
        $data->rpn2 = $request->rpn2;
        $data->comments2 = $request->comments2;

        $data->mitigation_required = $request->mitigation_required;
        $data->mitigation_plan = $request->mitigation_plan;
        $data->mitigation_due_date = $request->mitigation_due_date;
        $data->mitigation_status = $request->mitigation_status;
        $data->mitigation_status_comments = $request->mitigation_status_comments;
        $data->impact = $request->impact;
        $data->criticality = $request->criticality;
        $data->impact_analysis = $request->impact_analysis;
        $data->risk_analysis = $request->risk_analysis;
        $data->refrence_record =  implode(',', $request->refrence_record);
        $data->due_date_extension = $request->due_date_extension;

        $data->analysisN2 = $request->analysisN2;
        $data->analysisRPN2 = $request->analysisRPN2;
        //$data->severity = $request->severity;
        //$data->occurance = $request->occurance;

        $files = is_array($request->existing_attach_files) ? $request->existing_attach_files : null;

        if (!empty($request->reference)) {
            if ($data->reference) {
                $existingFiles = json_decode($data->reference, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = $existingFiles;
                }
            }

            if ($request->hasfile('reference')) {
                foreach ($request->file('reference') as $file) {
                    $name = $request->name . 'reference' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $data->reference = !empty($files) ? json_encode($files) : null;

        // if (!empty($request->reference)) {
        //     $files = [];
        //     if ($request->hasfile('reference')) {
        //         foreach ($request->file('reference') as $file) {
        //             $name = $request->name . ' reference' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }


        //     $data->reference = json_encode($files);
        // }
        // return $data;
        $data->update();
             // -----------grid=------
            //  $data1 = new RiskAssesmentGrid();
            //  $data1->risk_id = $data->id;
            //  $data1->type = "effect_analysis";

             $data1 = RiskAssesmentGrid::where('risk_id',$data->id)->where('type','effect_analysis')->first();

             if (!empty($request->risk_factor)) {
                 $data1->risk_factor = serialize($request->risk_factor);
             }
             if (!empty($request->risk_element)) {
                 $data1->risk_element = serialize($request->risk_element);
             }
             if (!empty($request->problem_cause)) {
                 $data1->problem_cause = serialize($request->problem_cause);
             }
             if (!empty($request->existing_risk_control)) {
                 $data1->existing_risk_control = serialize($request->existing_risk_control);
             }
             if (!empty($request->initial_severity)) {
                 $data1->initial_severity = serialize($request->initial_severity);
             }
             if (!empty($request->initial_detectability)) {
                 $data1->initial_detectability = serialize($request->initial_detectability);
             }
             if (!empty($request->initial_probability)) {
                 $data1->initial_probability = serialize($request->initial_probability);
             }
             if (!empty($request->initial_rpn)) {
                 $data1->initial_rpn = serialize($request->initial_rpn);
             }
             if (!empty($request->risk_acceptance)) {
                 $data1->risk_acceptance = serialize($request->risk_acceptance);
             }
             if (!empty($request->risk_control_measure)) {
                 $data1->risk_control_measure = serialize($request->risk_control_measure);
             }
             if (!empty($request->residual_severity)) {
                 $data1->residual_severity = serialize($request->residual_severity);
             }
             if (!empty($request->residual_probability)) {
                 $data1->residual_probability = serialize($request->residual_probability);
             }
             if (!empty($request->residual_detectability)) {
                 $data1->residual_detectability = serialize($request->residual_detectability);
             }
             if (!empty($request->residual_rpn)) {
                 $data1->residual_rpn = serialize($request->residual_rpn);
             }
             if (!empty($request->risk_acceptance2)) {
                 $data1->risk_acceptance2 = serialize($request->risk_acceptance2);
             }
             if (!empty($request->mitigation_proposal)) {
                 $data1->mitigation_proposal = serialize($request->mitigation_proposal);
             }

             $data1->save();

             // ---------------------------------------
            //  $data2 = new RiskAssesmentGrid();
            //  $data2->risk_id = $data->id;
            //  $data2->type = "fishbone";
                 $data2 = RiskAssesmentGrid::where('risk_id',$data->id)->where('type','fishbone')->first();

             if (!empty($request->measurement)) {
                 $data2->measurement = serialize($request->measurement);
             }
             if (!empty($request->materials)) {
                 $data2->materials = serialize($request->materials);
             }
             if (!empty($request->methods)) {
                 $data2->methods = serialize($request->methods);
             }
             if (!empty($request->environment)) {
                 $data2->environment = serialize($request->environment);
             }
             if (!empty($request->manpower)) {
                 $data2->manpower = serialize($request->manpower);
             }
             if (!empty($request->machine)) {
                 $data2->machine = serialize($request->machine);
             }
             if (!empty($request->problem_statement)) {
                 $data2->problem_statement = $request->problem_statement;
             }
             $data2->save();
             // =-------------------------------
               $data3 = RiskAssesmentGrid::where('risk_id',$data->id)->where('type','why_chart')->first();
            //  $data3 = new RiskAssesmentGrid();
            //  $data3->risk_id = $data->id;
            //  $data3->type = "why_chart";

             if (!empty($request->why_problem_statement)) {
                 $data3->why_problem_statement = $request->why_problem_statement;
             }
             if (!empty($request->why_1)) {
                 $data3->why_1 = serialize($request->why_1);
             }
             if (!empty($request->why_2)) {
                 $data3->why_2 = serialize($request->why_2);
             }
             if (!empty($request->why_3)) {
                 $data3->why_3 = serialize($request->why_3);
             }
             if (!empty($request->why_4)) {
                 $data3->why_4 = serialize($request->why_4);
             }
             if (!empty($request->why_5)) {
                 $data3->why_5 = serialize($request->why_5);
             }
             if (!empty($request->why_root_cause)) {
                 $data3->why_root_cause = $request->why_root_cause;
             }
             $data3->save();

             // --------------------------------------------
            //  $data4 = new RiskAssesmentGrid();
            //  $data4->risk_id = $data->id;
            //  $data4->type = "what_who_where";
              $data4 = RiskAssesmentGrid::where('risk_id',$data->id)->where('type','what_who_where')->first();

             if (!empty($request->what_will_be)) {
                 $data4->what_will_be = $request->what_will_be;
             }
             if (!empty($request->what_will_not_be)) {
                 $data4->what_will_not_be = $request->what_will_not_be;
             }
             if (!empty($request->what_rationable)) {
                 $data4->what_rationable = $request->what_rationable;
             }
             if (!empty($request->where_will_be)) {
                 $data4->where_will_be = $request->where_will_be;
             }
             if (!empty($request->where_will_not_be)) {
                 $data4->where_will_not_be = $request->where_will_not_be;
             }
             if (!empty($request->where_rationable)) {
                 $data4->where_rationable = $request->where_rationable;
             }
             if (!empty($request->coverage_will_be)) {
                 $data4->coverage_will_be = $request->coverage_will_be;
             }
             if (!empty($request->coverage_will_not_be)) {
                 $data4->coverage_will_not_be = $request->coverage_will_not_be;
             }
             if (!empty($request->coverage_rationable)) {
                 $data4->coverage_rationable = $request->coverage_rationable;
             }
             if (!empty($request->who_will_be)) {
                 $data4->who_will_be = $request->who_will_be;
             }
             if (!empty($request->who_will_not_be)) {
                 $data4->who_will_not_be = $request->who_will_not_be;
             }
             if (!empty($request->who_rationable)) {
                 $data4->who_rationable = $request->who_rationable;
             } if (!empty($request->when_will_be)) {
                 $data4->when_will_be = $request->when_will_be;
             }
              if (!empty($request->when_will_not_be)) {
                 $data4->when_will_not_be = $request->when_will_not_be;
             }
              if (!empty($request->when_rationable)) {
                 $data4->when_rationable = $request->when_rationable;
             }
             $data4->save();

      $data5 = RiskAssesmentGrid::where('risk_id',$data->id)->where('type','Action_Plan')->first();
            //  $data5 = new RiskAssesmentGrid();
            //  $data5->risk_id = $data->id;
            //  $data5->type = "Action_Plan";

             if (!empty($request->action)) {
                 $data5->action = serialize($request->action);
             }
             if (!empty($request->responsible)) {
                 $data5->responsible = serialize($request->responsible);
             }
             if (!empty($request->deadline)) {
                 $data5->deadline = serialize($request->deadline);
             }
             if (!empty($request->item_static)) {
                 $data5->item_static = serialize($request->item_static);
             }

             $data5->save();

            //  $data6 = new RiskAssesmentGrid();
            //  $data6->risk_id = $data->id;
            //  $data6->type = "Mitigation_Plan_Details";
              $data6 = RiskAssesmentGrid::where('risk_id',$data->id)->where('type','Mitigation_Plan_Details')->first();
             if (!empty($request->mitigation_steps)) {
                 $data6->mitigation_steps = serialize($request->mitigation_steps);
             }
             if (!empty($request->deadline2)) {
                 $data6->deadline2 = serialize($request->deadline2);
             }
             if (!empty($request->responsible_person)) {
                 $data6->responsible_person = serialize($request->responsible_person);
             }
             if (!empty($request->status)) {
                 $data6->status = serialize($request->status);
             }
             if (!empty($request->remark)) {
                 $data6->remark = serialize($request->remark);
             }

             $data6->save();

            //  if ($lastDocument->due_date != $data->due_date || !empty($request->due_date_comment)) {
            //     $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            //     ->where('activity_type', 'Due Date')
            //     ->exists();
            //     $history = new RiskAuditTrail();
            //     $history->risk_id = $id;
            //     $history->activity_type = 'Due Date';
            //     $history->previous = $lastDocument->due_date;
            //     $history->current = $data->due_date;
            //     $history->comment = $request->due_date_comment;
            //     $history->user_id = Auth::user()->id;
            //     $history->user_name = Auth::user()->name;
            //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            //     $history->origin_state = $lastDocument->status;
            //     $history->change_to = 'Not Applicable';
            //     $history->change_from = $lastDocument->status;
            //     $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            //     $history->save();
            // }

        if ($lastDocument->assign_to != $data->assign_to || !empty($request->assign_id_comment)) {
                $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
                ->where('activity_type', 'Assigned To')
                ->exists();
                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Assigned To';
                $history->previous = Helpers::getInitiatorName($lastDocument->assign_to);;
                $history->current = Helpers::getInitiatorName($data->assign_to);
                $history->comment = $request->assign_id_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to = 'Not Applicable';
                $history->change_from = $lastDocument->status;
                $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
                $history->save();
            }

        if ($lastDocument->Initiator_Group != $data->Initiator_Group || !empty($request->Initiator_Group_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Initiator Group')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Initiator Group';
            $history->previous = Helpers::getInitiatorGroupFullName($lastDocument->Initiator_Group);
            $history->current = Helpers::getInitiatorGroupFullName($data->Initiator_Group);
            $history->comment = $request->Initiator_Group_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->initiator_group_code != $data->initiator_group_code || !empty($request->initiator_group_code_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Initiator Group Code')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = $lastDocument->initiator_group_code;
            $history->current = $data->initiator_group_code;
            $history->comment = $request->initiator_group_code_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->short_description != $data->short_description || !empty($request->short_description_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Short Description')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Short Description';
            $history->previous = $lastDocument->short_description;
            $history->current = $data->short_description;
            $history->comment = $request->short_description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->severity2_level != $data->severity2_level || !empty($request->severity2_level_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Severity Level')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Severity Level';
            $history->previous = $lastDocument->severity2_level;
            $history->current = $data->severity2_level;
            $history->comment = $request->severity2_level_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }


        if ($lastDocument->departments != $data->departments || !empty($request->departments_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Department(s)')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Department(s)';
            $history->previous = $lastDocument->departments;
            $history->current = $data->departments;
            $history->comment = $request->departments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        // if ($lastDocument->team_members != $data->team_members || !empty($request->team_members_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Team Members';
        //     $history->previous = $lastDocument->team_members;
        //     $history->current = $data->team_members;
        //     $history->comment = $request->team_members_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
        //     $history->save();
        // }

        if ($lastDocument->source_of_risk != $data->source_of_risk || !empty($request->source_of_risk_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Source of Risk/Opportunity')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Source of Risk/Opportunity';
            $history->previous = $lastDocument->source_of_risk;
            $history->current = $data->source_of_risk;
            $history->comment = $request->source_of_risk_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->type != $data->type || !empty($request->type_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Type')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Type';
            $history->previous = $lastDocument->type;
            $history->current = $data->type;
            $history->comment = $request->type_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->priority_level != $data->priority_level || !empty($request->priority_level_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Priority Level')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Priority Level';
            $history->previous = $lastDocument->priority_level;
            $history->current = $data->priority_level;
            $history->comment = $request->priority_level_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->description != $data->description || !empty($request->description_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Risk/Opportunity Description')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Risk/Opportunity Description';
            $history->previous = $lastDocument->description;
            $history->current = $data->description;
            $history->comment = $request->description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        // if ($lastDocument->country != $data->country || !empty($request->country_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Country';
        //     $history->previous = $lastDocument->country;
        //     $history->current = $data->country;
        //     $history->comment = $request->country_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
        //     $history->save();
        // }

        // if ($lastDocument->state != $data->state || !empty($request->state_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'State/District';
        //     $history->previous = $lastDocument->state;
        //     $history->current = $data->state;
        //     $history->comment = $request->state_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
        //     $history->save();
        // }
        // if ($lastDocument->city != $data->city || !empty($request->city_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'City';
        //     $history->previous = $lastDocument->city;
        //     $history->current = $data->city;
        //     $history->comment = $request->city_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
        //     $history->save();
        // }


        if ($lastDocument->comments != $data->comments || !empty($request->comments_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Risk/Opportunity Comments')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Risk/Opportunity Comments';
            $history->previous = $lastDocument->comments;
            $history->current = $data->comments;
            $history->comment = $request->comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->departments2 != $data->departments2 || !empty($request->departments2_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Department(s)')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Department(s)';
            $history->previous = $lastDocument->departments2;
            $history->current = $data->departments2;
            $history->comment = $request->departments2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->source_of_risk2 != $data->source_of_risk2 || !empty($request->source_of_risk2_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Source of Risk')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Source of Risk';
            $history->previous = $lastDocument->source_of_risk2;
            $history->current = $data->source_of_risk2;
            $history->comment = $request->source_of_risk2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->site_name != $data->site_name || !empty($request->site_name_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Site Name')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Site Name';
            $history->previous = $lastDocument->site_name;
            $history->current = $data->site_name;
            $history->comment = $request->site_name_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->building != $data->building || !empty($request->building_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Building')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Building';
            $history->previous = $lastDocument->building;
            $history->current = $data->building;
            $history->comment = $request->building_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->floor != $data->floor || !empty($request->floor_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Floor')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Floor';
            $history->previous = $lastDocument->floor;
            $history->current = $data->floor;
            $history->comment = $request->floor_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->room != $data->room || !empty($request->room_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Room.')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Room.';
            $history->previous = $lastDocument->room;
            $history->current = $data->room;
            $history->comment = $request->room_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->duration != $data->duration || !empty($request->duration_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Duration')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Duration';
            $history->previous = $lastDocument->duration;
            $history->current = $data->duration;
            $history->comment = $request->duration_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->hazard != $data->hazard || !empty($request->hazard_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Hazard')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Hazard';
            $history->previous = $lastDocument->hazard;
            $history->current = $data->hazard;
            $history->comment = $request->hazard_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->room2 != $data->room2 || !empty($request->room2_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Room')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Room';
            $history->previous = $lastDocument->room2;
            $history->current = $data->room2;
            $history->comment = $request->room2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->regulatory_climate != $data->regulatory_climate || !empty($request->regulatory_climate_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Regulatory Climate')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Regulatory Climate';
            $history->previous = $lastDocument->regulatory_climate;
            $history->current = $data->regulatory_climate;
            $history->comment = $request->regulatory_climate_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->Number_of_employees != $data->Number_of_employees || !empty($request->Number_of_employees_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Number Of Employees')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Number Of Employees';
            $history->previous = $lastDocument->Number_of_employees;
            $history->current = $data->Number_of_employees;
            $history->comment = $request->Number_of_employees_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        // if ($lastDocument->refrence_record != $data->refrence_record || !empty($request->refrence_record_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Reference Recores';
        //     $history->previous = $lastDocument->refrence_record;
        //     $history->current = $data->refrence_record;
        //     $history->comment = $request->refrence_record_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        if ($lastDocument->risk_management_strategy != $data->risk_management_strategy || !empty($request->risk_management_strategy_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Risk Management Strategy')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Risk Management Strategy';
            $history->previous = $lastDocument->risk_management_strategy;
            $history->current = $data->risk_management_strategy;
            $history->comment = $request->risk_management_strategy_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->schedule_start_date1 != $data->schedule_start_date1 || !empty($request->schedule_start_date1_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Scheduled Start Date')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Scheduled Start Date';
            $history->previous =  Helpers::getdateFormat($lastDocument->schedule_start_date1);
            $history->current =  Helpers::getdateFormat($data->schedule_start_date1);
            $history->comment = $request->schedule_start_date1_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->schedule_end_date1 != $data->schedule_end_date1 || !empty($request->schedule_end_date1_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Scheduled End Date')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Scheduled End Date';
            $history->previous =  Helpers::getdateFormat($lastDocument->schedule_end_date1);
            $history->current =  Helpers::getdateFormat($data->schedule_end_date1);
            $history->comment = $request->schedule_end_date1_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->estimated_man_hours != $data->estimated_man_hours || !empty($request->estimated_man_hours_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Estimated Man Hours')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Estimated Man Hours';
            $history->previous = $lastDocument->estimated_man_hours;
            $history->current = $data->estimated_man_hours;
            $history->comment = $request->estimated_man_hours_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->estimated_cost != $data->estimated_cost || !empty($request->estimated_cost_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Estimated Cost')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Estimated Cost';
            $history->previous = $lastDocument->estimated_cost;
            $history->current = $data->estimated_cost;
            $history->comment = $request->estimated_cost_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->currency != $data->currency || !empty($request->currency_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Currency')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Currency';
            $history->previous = $lastDocument->currency;
            $history->current = $data->currency;
            $history->comment = $request->currency_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        // if ($lastDocument->training_require != $data->training_require || !empty($request->training_require_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Training Require';
        //     $history->previous = $lastDocument->training_require;
        //     $history->current = $data->training_require;
        //     $history->comment = $request->training_require_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
        //     $history->save();
        // }
        if ($lastDocument->justification != $data->justification || !empty($request->justification_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Justification')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Justification';
            $history->previous = $lastDocument->justification;
            $history->current = $data->justification;
            $history->comment = $request->justification_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->reference != $data->reference || !empty($request->reference_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Work Group Attachments')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Work Group Attachments';
            $history->previous = $lastDocument->reference;
            $history->current = $data->reference;
            $history->comment = $request->reference_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        // if ($lastDocument->cost_of_risk != $data->cost_of_risk || !empty($request->cost_of_risk_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Cost Of Risk';
        //     $history->previous = $lastDocument->cost_of_risk;
        //     $history->current = $data->cost_of_risk;
        //     $history->comment = $request->cost_of_risk_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
        //     $history->save();
        // }

        // if ($lastDocument->environmental_impact != $data->environmental_impact || !empty($request->environmental_impact_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Environmental Impact';
        //     $history->previous = $lastDocument->environmental_impact;
        //     $history->current = $data->environmental_impact;
        //     $history->comment = $request->environmental_impact_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
        //     $history->save();
        // }

        // if ($lastDocument->public_perception_impact != $data->public_perception_impact || !empty($request->public_perception_impact_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Public Perception Impact';
        //     $history->previous = $lastDocument->public_perception_impact;
        //     $history->current = $data->public_perception_impact;
        //     $history->comment = $request->public_perception_impact_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
        //     $history->save();
        // }


        if ($lastDocument->root_cause_methodology != $data->root_cause_methodology || !empty($request->root_cause_methodology_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Root Cause Methodology')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Root Cause Methodology';
            $history->previous = $lastDocument->root_cause_methodology;
            $history->current = $data->root_cause_methodology;
            $history->comment = $request->root_cause_methodology_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->root_cause_description != $data->root_cause_description || !empty($request->root_cause_description_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Root Cause Description')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Root Cause Description';
            $history->previous = $lastDocument->root_cause_description;
            $history->current = $data->root_cause_description;
            $history->comment = $request->root_cause_description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->investigation_summary != $data->investigation_summary || !empty($request->investigation_summary_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Investigation Summary')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Investigation Summary';
            $history->previous = $lastDocument->investigation_summary;
            $history->current = $data->investigation_summary;
            $history->comment = $request->investigation_summary_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }


        // if ($lastDocument->calculated_risk != $data->calculated_risk || !empty($request->calculated_risk_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Calculated Risk';
        //     $history->previous = $lastDocument->calculated_risk;
        //     $history->current = $data->calculated_risk;
        //     $history->comment = $request->calculated_risk_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
        //     $history->save();
        // }

        // if ($lastDocument->impacted_objects != $data->impacted_objects || !empty($request->impacted_objects_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Impacted Objects';
        //     $history->previous = $lastDocument->impacted_objects;
        //     $history->current = $data->impacted_objects;
        //     $history->comment = $request->impacted_objects_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
        //     $history->save();
        // }
        if ($lastDocument->severity_rate != $data->severity_rate || !empty($request->severity_rate_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Severity Rate')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Severity Rate';
            // $history->previous = $lastDocument->severity_rate;
            if($lastDocument->severity_rate == 1){
                $history->previous = "Negligible";
            } elseif($lastDocument->severity_rate == 2){
                $history->previous = "Moderate";
            } elseif($lastDocument->severity_rate == 3){
                $history->previous = "Major";
            } elseif($lastDocument->severity_rate == 4){
                $history->previous = "Fatal";
            } else{
                $history->previous = "Null";
            }
            // $history->current = $data->severity_rate;
            if($request->severity_rate == 1){
                $history->current = "Negligible";
            } elseif($request->severity_rate == 2){
                $history->current = "Moderate";
            } elseif($request->severity_rate == 3){
                $history->current = "Major";
            }else{
                $history->current = "Fatal";
            }
            $history->comment = $request->severity_rate_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->occurrence != $data->occurrence || !empty($request->occurrence_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Occurrence')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Occurrence';
            // $history->previous = $lastDocument->occurrence;
            if($lastDocument->occurrence == 1){
                $history->previous = "Very Likely";
            } elseif($lastDocument->occurrence == 2){
                $history->previous = "Likely";
            } elseif($lastDocument->occurrence == 3){
                $history->previous = "Unlikely";
            } elseif($lastDocument->occurrence == 4){
                $history->previous = "Rare";
            } elseif($lastDocument->occurrence == 5){
                $history->previous = "Extremely Unlikely";
            } else{
                $history->previous = "Null";
            }
            // $history->current = $data->occurrence;
            if($request->occurrence == 1){
                $history->current = "Very Likely";
            } elseif($request->occurrence == 2){
                $history->current = "Likely";
            } elseif($request->occurrence == 3){
                $history->current = "Unlikely";
            }elseif($request->occurrence == 4){
                $history->current = "Rare";
            }else{
                $history->current = "Extremely Unlikely";
            }
            $history->comment = $request->occurrence_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->detection != $data->detection || !empty($request->detection_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Detection')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Detection';
            // $history->previous = $lastDocument->detection;
            if($lastDocument->detection == 1){
                $history->previous = "Very Likely";
            } elseif($lastDocument->detection == 2){
                $history->previous = "Likely";
            } elseif($lastDocument->detection == 3){
                $history->previous = "Unlikely";
            } elseif($lastDocument->detection == 4){
                $history->previous = "Rare";
            } elseif($lastDocument->detection == 5){
                $history->previous = "Impossible";
            } else{
                $history->previous = "Null";
            }
            // $history->current = $data->detection;
            if($request->detection == 1){
                $history->current = "Very Likely";
            } elseif($request->detection == 2){
                $history->current = "Likely";
            } elseif($request->detection == 3){
                $history->current = "Unlikely";
            }elseif($request->detection == 4){
                $history->current = "Rare";
            }else{
                $history->current = "Impossible";
            }
            $history->comment = $request->detection_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->rpn != $data->rpn || !empty($request->rpn_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Rpn')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Rpn';
            $history->previous = $lastDocument->rpn;
            $history->current = $data->rpn;
            $history->comment = $request->rpn_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->residual_risk != $data->residual_risk || !empty($request->residual_risk_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Residual Risk')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Residual Risk';
            $history->previous = $lastDocument->residual_risk;
            $history->current = $data->residual_risk;
            $history->comment = $request->residual_risk_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->residual_risk_impact != $data->residual_risk_impact || !empty($request->residual_risk_impact_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Residual Risk Impact')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Residual Risk Impact';
            // $history->previous = $lastDocument->residual_risk_impact;
            if($lastDocument->residual_risk_impact == 1){
                $history->previous = "High";
            } elseif($lastDocument->residual_risk_impact == 2){
                $history->previous = "Low";
            } elseif($lastDocument->residual_risk_impact == 3){
                $history->previous = "Medium";
            } elseif($lastDocument->residual_risk_impact == 4){
                $history->previous = "None";
            } else{
                $history->previous = "Null";
            }
            // $history->current = $data->residual_risk_impact;
            if($request->residual_risk_impact == 1){
                $history->current = "High";
            } elseif($request->residual_risk_impact == 2){
                $history->current = "Low";
            } elseif($request->residual_risk_impact == 3){
                $history->current = "Medium";
            }else{
                $history->current = "None";
            }
            $history->comment = $request->residual_risk_impact_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->residual_risk_probability != $data->residual_risk_probability || !empty($request->residual_risk_probability_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Residual Risk Probability')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Residual Risk Probability';
            // $history->previous = $lastDocument->residual_risk_probability;
            if($lastDocument->residual_risk_probability == 1){
                $history->previous = "High";
            } elseif($lastDocument->residual_risk_probability == 2){
                $history->previous = "Medium";
            } elseif($lastDocument->residual_risk_probability == 3){
                $history->previous = "Low";
            } else{
                $history->previous = "Null";
            }

            // $history->current = $data->residual_risk_probability;
            if($request->residual_risk_probability == 1){
                $history->current = "High";
            } elseif($request->residual_risk_probability == 2){
                $history->current = "Medium";
            }else{
                $history->current = "Low";
            }
            $history->comment = $request->residual_risk_probability_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->detection2 != $data->detection2 || !empty($request->detection2_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Residual Detection')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Residual Detection';
            // $history->previous = $lastDocument->detection2;
            if($lastDocument->detection2 == 1){
                $history->previous = "Very Likely";
            } elseif($lastDocument->detection2 == 2){
                $history->previous = "Likely";
            } elseif($lastDocument->detection2 == 3){
                $history->previous = "Unlikely";
            } elseif($lastDocument->detection2 == 4){
                $history->previous = "Rare";
            } elseif($lastDocument->detection2 == 5){
                $history->previous = "Impossible";
            } else{
                $history->previous = "Null";
            }
            // $history->current = $data->detection2;
            if($request->detection2 == 1){
                $history->current = "Very Likely";
            } elseif($request->detection2 == 2){
                $history->current = "Likely";
            } elseif($request->detection2 == 3){
                $history->current = "Unlikely";
            } elseif($request->detection2 == 4){
                $history->current = "Rare";
            } else{
                $history->current = "Impossible";
            }
            $history->comment = $request->detection2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->rpn2 != $data->rpn2 || !empty($request->rpn2_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Residual RPN')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Residual RPN';
            $history->previous = $lastDocument->rpn2;
            $history->current = $data->rpn2;
            $history->comment = $request->rpn2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->comments2 != $data->comments2 || !empty($request->comments2_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Comments')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Comments';
            $history->previous = $lastDocument->comments2;
            $history->current = $data->comments2;
            $history->comment = $request->comments2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->mitigation_required != $data->mitigation_required || !empty($request->mitigation_required_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Mitigation Required')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Mitigation Required';
            $history->previous = $lastDocument->mitigation_required;
            $history->current = $data->mitigation_required;
            $history->comment = $request->mitigation_required_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->mitigation_plan != $data->mitigation_plan || !empty($request->mitigation_plan_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Mitigation Plan')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Mitigation Plan';
            $history->previous = $lastDocument->mitigation_plan;
            $history->current = $data->mitigation_plan;
            $history->comment = $request->mitigation_plan_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->mitigation_due_date != $data->mitigation_due_date || !empty($request->mitigation_due_date_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Scheduled End-Date')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Scheduled End-Date';
            $history->previous =  Helpers::getdateFormat($lastDocument->mitigation_due_date);
            $history->current =  Helpers::getdateFormat($data->mitigation_due_date);
            $history->comment = $request->mitigation_due_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->mitigation_status != $data->mitigation_status || !empty($request->mitigation_status_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Status of Mitigation')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Status of Mitigation';
            $history->previous = $lastDocument->mitigation_status;
            $history->current = $data->mitigation_status;
            $history->comment = $request->mitigation_status_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->mitigation_status_comments != $data->mitigation_status_comments || !empty($request->mitigation_status_comments_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Mitigation Status Comments')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Mitigation Status Comments';
            $history->previous = $lastDocument->mitigation_status_comments;
            $history->current = $data->mitigation_status_comments;
            $history->comment = $request->mitigation_status_comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->impact != $data->impact || !empty($request->impact_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Impact')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Impact';
            $history->previous = $lastDocument->impact;
            $history->current = $data->impact;
            $history->comment = $request->impact_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->criticality != $data->criticality || !empty($request->criticality_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Criticality')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Criticality';
            $history->previous = $lastDocument->criticality;
            $history->current = $data->criticality;
            $history->comment = $request->criticality_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->impact_analysis != $data->impact_analysis || !empty($request->impact_analysis_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Impact Analysis')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Impact Analysis';
            $history->previous = $lastDocument->impact_analysis;
            $history->current = $data->impact_analysis;
            $history->comment = $request->impact_analysis_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->risk_analysis != $data->risk_analysis || !empty($request->risk_analysis_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Risk Analysis')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Risk Analysis';
            $history->previous = $lastDocument->risk_analysis;
            $history->current = $data->risk_analysis;
            $history->comment = $request->risk_analysis_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->refrence_record != $data->refrence_record || !empty($request->refrence_record_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Reference Record')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Reference Record';
            $history->previous = $lastDocument->refrence_record;
            $history->current = $data->refrence_record;
            $history->comment = $request->refrence_record_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->due_date_extension != $data->due_date_extension || !empty($request->due_date_extension_comment)) {
            $lastDocumentAuditTrail = RiskAuditTrail::where('risk_id', $data->id)
            ->where('activity_type', 'Due Date Extension Justification')
            ->exists();
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = $lastDocument->due_date_extension;
            $history->current = $data->due_date_extension;
            $history->comment = $request->due_date_extension_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        toastr()->success("Record is update Successfully");
        return redirect()->back();
    }

    public function show($id)
    {
        $data = RiskManagement::find($id);
        $old_record = RiskManagement::select('id', 'division_id', 'record')->get();
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_to)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $riskEffectAnalysis = RiskAssesmentGrid::where('risk_id',$id)->where('type',"effect_analysis")->first();
        $fishbone = RiskAssesmentGrid::where('risk_id',$id)->where('type',"fishbone")->first();
        $whyChart = RiskAssesmentGrid::where('risk_id',$id)->where('type',"why_chart")->first();
        $what_who_where = RiskAssesmentGrid::where('risk_id',$id)->where('type',"what_who_where")->first();
        $action_plan = RiskAssesmentGrid::where('risk_id',$id)->where('type',"Action_Plan")->first();
        $mitigation_plan_details = RiskAssesmentGrid::where('risk_id',$id)->where('type',"Mitigation_Plan_Details")->first();

        return view('frontend.riskAssesment.view', compact('data','riskEffectAnalysis','fishbone','whyChart','what_who_where', 'old_record', 'action_plan', 'mitigation_plan_details'));
    }


    public function riskAssesmentStateChange(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = RiskManagement::find($id);
            $lastDocument =  RiskManagement::find($id);
            $data =  RiskManagement::find($id);

            if ($changeControl->stage == 1) {
                $changeControl->stage = "2";
                $changeControl->status = 'Risk Analysis & Work Group Assignment';
                $changeControl->submitted_by = Auth::user()->name;
                $changeControl->submitted_on = Carbon::now()->format('d-M-Y');
                $changeControl->submitted_comment = $request->comments;

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Submitted By, Submitted On';
                if (is_null($lastDocument->submitted_by) || $lastDocument->submitted_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->submitted_by . ' , ' . $lastDocument->submitted_on;
                }
                $history->action = 'Submit';
                $history->current = $changeControl->submitted_by . ' , ' . $changeControl->submitted_on;
                $history->comment = $request->comments;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Submit';
                $history->change_to =   "Risk Analysis & Work Group Assignment";
                $history->change_from = 'Opened';
                if (is_null($lastDocument->submitted_by) || $lastDocument->submitted_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }


                $history->save();
            //      $list = Helpers::getHodUserList();
            //      return $list;
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id == $changeControl->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {
            //                 try {
            //                     Mail::send(
            //                         'mail.view-mail',
            //                          ['data' => $changeControl],
            //                       function ($message) use ($email) {
            //                           $message->to($email)
            //                               ->subject("Document is Send By".Auth::user()->name);
            //                       }
            //                     );
            //                 } catch (\Exception $e) {
            //                     //
            //                 }
            //             }
            //      }
            //   }
                $changeControl->update();

                toastr()->success('document send');
                return back();
        }
            if ($changeControl->stage == 2) {
                $changeControl->stage = "3";
                $changeControl->status = 'Risk Processing & Action Plan';
                $changeControl->evaluated_by = Auth::user()->name;
                $changeControl->evaluated_on = Carbon::now()->format('d-M-Y');
                $changeControl->evaluated_comment = $request->comments;

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Evaluation Complete By, Evaluation Complete On';
                if (is_null($lastDocument->evaluated_by) || $lastDocument->evaluated_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->evaluated_by . ' , ' . $lastDocument->evaluated_on;
                }
                $history->action = 'Evaluation Complete';
                $history->current = $changeControl->evaluated_by . ' , ' . $changeControl->evaluated_on;

                $history->comment = $request->comments;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Evaluation Complete';
                $history->change_to = 'Risk Processing & Action Plan';
                $history->change_from = $lastDocument->status;
                if (is_null($lastDocument->evaluated_by) || $lastDocument->evaluated_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            //     $list = Helpers::getWorkGroupUserList();
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id == $changeControl->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {
            //                 try {
            //                     Mail::send(
            //                         'mail.view-mail',
            //                          ['data' => $changeControl],
            //                       function ($message) use ($email) {
            //                           $message->to($email)
            //                               ->subject("Document is Send By".Auth::user()->name);
            //                       }
            //                     );
            //                 } catch (\Exception $e) {
            //                     //
            //                 }
            //             }
            //      }
            //   }
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = "4";
                $changeControl->status = 'Pending HOD Approval';
                $changeControl->plan_approved_by = Auth::user()->name;
                $changeControl->plan_approved_on = Carbon::now()->format('d-M-Y');
                $changeControl->plan_approved_comment = $request->comments;

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Action Plan Completed By, Action Plan Completed On';
                if (is_null($lastDocument->plan_approved_by) || $lastDocument->plan_approved_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->plan_approved_by . ' , ' . $lastDocument->plan_approved_on;
                }
                $history->action = 'Action Plan Complete';
                $history->current = $changeControl->plan_approved_by . ' , ' . $changeControl->plan_approved_on;
                $history->comment = $request->comments;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Action Plan Complete';
                $history->change_to = "Pending HOD Approval";
                $history->change_from = $lastDocument->status;
                if (is_null($lastDocument->plan_approved_by) || $lastDocument->plan_approved_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
                //  $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $supplier->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {
                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $supplier],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Send By".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      }
                //   }
                $changeControl->update();

                toastr()->success('document send');
                return back();
        }

            if ($changeControl->stage == 4) {
                $changeControl->stage = "5";
                $changeControl->status = 'Actions Items in Progress';
                $changeControl->action_plan_approved_by = Auth::user()->name;
                $changeControl->action_plan_approved_on = Carbon::now()->format('d-M-Y');
                $changeControl->action_plan_approved_comment = $request->comments;

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = "Action Plan Approved By, Action Plan Approved On";
                if (is_null($lastDocument->action_plan_approved_by) || $lastDocument->action_plan_approved_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->action_plan_approved_by . ' , ' . $lastDocument->action_plan_approved_on;
                }

                $history->action = 'Action Plan Approved';
                $history->current = $changeControl->action_plan_approved_by . ' , ' . $changeControl->action_plan_approved_on;
                $history->comment = $request->comments;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Action Plan Approved';
                $history->change_to = "Actions Items in Progress";
                $history->change_from = $lastDocument->status;
                if (is_null($lastDocument->action_plan_approved_by) || $lastDocument->action_plan_approved_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            //     $list = Helpers::getQAHeadUserList();
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id == $changeControl->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {
            //                 try {
            //                     Mail::send(
            //                         'mail.view-mail',
            //                          ['data' => $changeControl],
            //                       function ($message) use ($email) {
            //                           $message->to($email)
            //                               ->subject("Document is Send By".Auth::user()->name);
            //                       }
            //                     );
            //                 } catch (\Exception $e) {
            //                     //
            //                 }
            //             }
            //      }
            //   }
                $changeControl->update();

                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 5) {
                $changeControl->stage = "6";
                $changeControl->status = 'Residual Risk Evaluation';
                $changeControl->all_action_completed_by = Auth::user()->name;
                $changeControl->all_action_completed_on = Carbon::now()->format('d-M-Y');
                $changeControl->all_action_completed_comment = $request->comments;

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'All Actions Completed By, All Actions Completed On';
                if (is_null($lastDocument->all_action_completed_by) || $lastDocument->all_action_completed_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->all_action_completed_by . ' , ' . $lastDocument->all_action_completed_on;
                }
                $history->action = 'All Actions Completed';
                $history->current = $changeControl->all_action_completed_by . ' , ' . $changeControl->all_action_completed_on;
                $history->comment = $request->comments;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'All Actions Completed';
                $history->change_to = "Residual Risk Evaluation";
                $history->change_from = $lastDocument->status;
                if (is_null($lastDocument->all_action_completed_by) || $lastDocument->all_action_completed_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            //     $list = Helpers::getHodUserList();
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id == $changeControl->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {

            //                 try {
            //                     Mail::send(
            //                         'mail.view-mail',
            //                          ['data' => $changeControl],
            //                       function ($message) use ($email) {
            //                           $message->to($email)
            //                               ->subject("Document is Send By".Auth::user()->name);
            //                       }
            //                     );
            //                 } catch (\Exception $e) {
            //                     //
            //                 }
            //             }
            //      }
            //   }
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }

            if ($changeControl->stage == 6) {
                $changeControl->stage = "7";
                $changeControl->status = 'Closed - Done';
                $changeControl->residual_risk_completed_by = Auth::user()->name;
                $changeControl->residual_risk_completed_on = Carbon::now()->format('d-M-Y');
                $changeControl->residual_risk_completed_comment = $request->comments;

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Residual Risk Evaluation Completed By, Residual Risk Evaluation Completed On';
                if (is_null($lastDocument->residual_risk_completed_by) || $lastDocument->residual_risk_completed_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->residual_risk_completed_by . ' , ' . $lastDocument->residual_risk_completed_on;
                }
                $history->action = 'Residual Risk Evaluation Completed';
                $history->current = $changeControl->residual_risk_completed_by . ' , ' . $changeControl->residual_risk_completed_on;
                $history->comment = $request->comments;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Residual Risk Evaluation Completed';
                $history->change_to = "Closed - Done";
                $history->change_from = $lastDocument->status;
                if (is_null($lastDocument->residual_risk_completed_by) || $lastDocument->residual_risk_completed_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


    public function RejectStateChange(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = RiskManagement::find($id);
            $lastDocument =  RiskManagement::find($id);
            $data =  RiskManagement::find($id);
            if ($changeControl->stage == 1) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed - Cancelled";
                $changeControl->cancelled_by = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
                $changeControl->cancelled_comment = $request->comment;

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Cancelled By, Cancelled On';
                if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->cancelled_by . ' , ' . $lastDocument->cancelled_on;
                }
                $history->action = 'Cancel';
                $history->current = $changeControl->cancelled_by . ' , ' . $changeControl->cancelled_on;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Cancel';
                $history->change_to = 'Closed - Cancelled';
                $history->change_from = 'Opened';
                if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 2) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed - Cancelled";
                $changeControl->cancelled_by = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
                $changeControl->cancelled_comment = $request->comment;

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Cancelled By, Cancelled On';
                if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->cancelled_by . ' , ' . $lastDocument->cancelled_on;
                }
                $history->action = 'Cancel';
                $history->current = $changeControl->cancelled_by . ' , ' . $changeControl->cancelled_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Cancel';
                $history->change_to = 'Closed - Cancelled';
                $history->change_from = 'Risk Analysis & Work Group Assignment';
                if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function MoreInfoCAPA(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = RiskManagement::find($id);
            $lastDocument =  RiskManagement::find($id);
            $data =  RiskManagement::find($id);
            if ($changeControl->stage == 2) {
                $changeControl->stage = "1";
                $changeControl->status = "Opened";
                $changeControl->analysis_more_info_by = Auth::user()->name;
                $changeControl->analysis_more_info_on = Carbon::now()->format('d-M-Y');
                $changeControl->analysis_more_info_comment = $request->comment;


                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'More Information Required By, More Information Required On';
                if (is_null($lastDocument->analysis_more_info_by) || $lastDocument->analysis_more_info_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->analysis_more_info_by . ' , ' . $lastDocument->analysis_more_info_on;
                }
                $history->action = 'More Information Required';
                $history->current = $changeControl->analysis_more_info_by . ' , ' . $changeControl->analysis_more_info_on;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='More Information Required';
                $history->change_to = 'Opened';
                $history->change_from = 'Risk Analysis & Work Group Assignment';
                if (is_null($lastDocument->analysis_more_info_by) || $lastDocument->analysis_more_info_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = "2";
                $changeControl->status = "Risk Analysis & Work Group Assignment";
                $changeControl->request_more_info_by = Auth::user()->name;
                $changeControl->request_more_info_on = Carbon::now()->format('d-M-Y');
                $changeControl->request_more_info_comment = $request->comment;

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Request More Info By, Request More Info On';
                if (is_null($lastDocument->request_more_info_by) || $lastDocument->request_more_info_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->request_more_info_by . ' , ' . $lastDocument->request_more_info_on;
                }
                $history->action = 'More Information Required';
                $history->current = $changeControl->request_more_info_by . ' , ' . $changeControl->request_more_info_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='More Information Required';
                $history->change_to = 'Risk Analysis & Work Group Assignment';
                $history->change_from = 'Risk Processing & Action Plan';
                if (is_null($lastDocument->request_more_info_by) || $lastDocument->request_more_info_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 4) {
                $changeControl->stage = "3";
                $changeControl->status = "Risk Processing & Action Plan";
                $changeControl->reject_action_by = Auth::user()->name;
                $changeControl->reject_action_on = Carbon::now()->format('d-M-Y');
                $changeControl->reject_action_comment = $request->comment;

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Reject Action Plan By, Reject Action Plan On';
                if (is_null($lastDocument->reject_action_by) || $lastDocument->reject_action_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->reject_action_by . ' , ' . $lastDocument->reject_action_on;
                }
                $history->action = 'Reject Action Plan';
                $history->current = $changeControl->reject_action_by . ' , ' . $changeControl->reject_action_on;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Reject Action Plan';
                $history->change_to = 'Risk Processing & Action Plan';
                $history->change_from = 'Pending HOD Approval';
                if (is_null($lastDocument->reject_action_by) || $lastDocument->reject_action_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 5) {
                $changeControl->stage = "4";
                $changeControl->status = "Pending HOD Approval";
                $changeControl->action_request_action_by = Auth::user()->name;
                $changeControl->action_request_action_on = Carbon::now()->format('d-M-Y');
                $changeControl->action_request_action_comment = $request->comment;

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Request More Info By, Request More Info On';
                if (is_null($lastDocument->action_request_action_by) || $lastDocument->action_request_action_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->action_request_action_by . ' , ' . $lastDocument->action_request_action_on;
                }
                $history->action = 'Request More Info';
                $history->current = $changeControl->action_request_action_by . ' , ' . $changeControl->action_request_action_on;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Request More Info';
                $history->change_to = 'Pending HOD Approval';
                $history->change_from = 'Actions Items in Progress';
                if (is_null($lastDocument->action_request_action_by) || $lastDocument->action_request_action_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 6) {
                $changeControl->stage = "5";
                $changeControl->status = "Actions Items in Progress";
                $changeControl->more_action_needed_by = Auth::user()->name;
                $changeControl->more_action_needed_on = Carbon::now()->format('d-M-Y');
                $changeControl->more_action_needed_comment = $request->comment;

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'More Actions Needed By, More Actions Needed On';
                if (is_null($lastDocument->more_action_needed_by) || $lastDocument->more_action_needed_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->more_action_needed_by . ' , ' . $lastDocument->more_action_needed_on;
                }
                $history->action = 'More Actions Needed';
                $history->current = $changeControl->more_action_needed_by . ' , ' . $changeControl->more_action_needed_on;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='More Actions Needed';
                $history->change_to = 'Actions Items in Progress';
                $history->change_from = 'Residual Risk Evaluation';
                if (is_null($lastDocument->more_action_needed_by) || $lastDocument->more_action_needed_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }
    public function riskAuditTrial($id)
    {
        $audit = RiskAuditTrail::where('risk_id', $id)->orderByDESC('id')->paginate(5);
        $today = Carbon::now()->format('d-m-y');
        $document = RiskManagement::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');

        return view("frontend.riskAssesment.audit-trail", compact('audit', 'document', 'today'));
    }

    public function auditDetailsrisk($id)
    {

        $detail = RiskAuditTrail::find($id);

        $detail_data = RiskAuditTrail::where('activity_type', $detail->activity_type)->where('risk_id', $detail->risk_id)->latest()->get();

        $doc = RiskManagement::where('id', $detail->risk_id)->first();

        $doc->origiator_name = User::find($doc->initiator_id);
        return view("frontend.riskAssesment.audit-trial-inner", compact('detail', 'doc', 'detail_data'));
    }

    public static function singleReport($id)
    {
        $data = RiskManagement::find($id);
        if (!empty($data)) {

            $riskgrdfishbone = RiskAssesmentGrid::where('risk_id', $data->id)->where('type','fishbone')->first();

            $riskgrdwhy_chart = RiskAssesmentGrid::where('risk_id', $data->id)->where('type','why_chart')->first();
            $riskgrdwhat_who_where = RiskAssesmentGrid::where('risk_id', $data->id)->where('type','what_who_where')->first();
            $riskEffectAnalysis = RiskAssesmentGrid::where('risk_id', $data->id)->where('type','effect_analysis')->first();
            $data5 = RiskAssesmentGrid::where('risk_id', $data->id)->where('type','Action_Plan')->first();
            $data6 = RiskAssesmentGrid::where('risk_id', $data->id)->where('type','Mitigation_Plan_Details')->first();
             //dd($riskgrd);
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.riskAssesment.singleReport', compact('data','riskgrdfishbone','riskgrdwhy_chart','riskgrdwhat_who_where','riskEffectAnalysis', 'data5', 'data6'))
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
            return $pdf->stream('Risk-assessment' . $id . '.pdf');
        }
    }

    public static function auditReport($id)
    {
        $doc = RiskManagement::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $audit = RiskAuditTrail::where('risk_id', $id)->get();
            $data = RiskAuditTrail::where('risk_id', $id)->orderbyDesc('id')->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.riskAssesment.auditReport', compact('data', 'doc','audit'))
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
            return $pdf->stream('Risk-Audit-Trial' . $id . '.pdf');
        }
    }

    public function child(Request $request, $id)
    {
        $RM = RiskManagement::find($id);
        $parent_id = $id;
        $parent_type = "Risk-Assessment";
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $parent_record = RiskManagement::where('id', $id)->value('record');
        $parent_record = str_pad($parent_record, 4, '0', STR_PAD_LEFT);
        $parent_division_id = RiskManagement::where('id', $id)->value('division_id');
        $parent_initiator_id = RiskManagement::where('id', $id)->value('initiator_id');
        $parent_intiation_date = RiskManagement::where('id', $id)->value('intiation_date');
        $parent_short_description = RiskManagement::where('id', $id)->value('short_description');
        $old_record = RiskManagement::select('id', 'division_id', 'record')->get();

        if ($request->revision == "Action-Item") {
            $old_record = ActionItem::all();
            $RM->originator = User::where('id', $RM->initiator_id)->value('name');
            return view('frontend.forms.action-item', compact('record_number','old_record', 'due_date','parent_division_id','parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id'));

        }

        if ($request->revision == "CAPA") {
            $old_record = Capa::all();
            $RM->originator = User::where('id', $RM->initiator_id)->value('name');
           return view('frontend.forms.capa', compact('record_number','old_record', 'due_date','parent_division_id','parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id'));

        }

    }
}
