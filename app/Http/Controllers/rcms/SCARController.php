<?php

namespace App\Http\Controllers\rcms;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PDF;
use App\Models\{
RecordNumber,
SCAR,
ScarAuditTrail,
User,
Supplier,
RoleGroup
};

class SCARController extends Controller
{
    public function index(){
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $supplierName = Supplier::select('id','supplier_name')->get();
        $supplierProduct = Supplier::where('supplier_products' , '!=' , "null")->get();
        $distributionSites = Supplier::where('distribution_sites', '!=', "null")->get();

        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        return view('frontend.scar.scar_new',compact('record_number', 'due_date','supplierName','supplierProduct','distributionSites'));
    }

    public function store(Request $request){
        $scar = new SCAR();
        $scar->type = "SCAR";
        $scar->division_id = $request->division_id;
        $scar->record = DB::table('record_numbers')->value('counter') + 1;
        $scar->parent_id = $request->parent_id;
        $scar->parent_type = $request->parent_type;
        $scar->initiator_id = Auth::user()->id;
        $scar->initiation_date = $request->initiation_date;
        $scar->short_description = $request->short_description;
        $scar->assign_to = $request->assign_to;
        $scar->due_date = Carbon::now()->addDays(30)->format('d-M-Y');

        $scar->scar_name = $request->scar_name;
        $scar->owner_name = $request->owner_name;
        $scar->followup_date = $request->followup_date;
        $scar->supplier_site = $request->supplier_site;
        $scar->supplier_product = $request->supplier_product;
        $scar->supplier_site_contact_email = $request->supplier_site_contact_email;
        $scar->description = $request->description;
        $scar->recommended_action = $request->recommended_action;
        $scar->non_conformance = $request->non_conformance;
        $scar->expected_closure_date = $request->expected_closure_date;
        $scar->expected_closure_time = $request->expected_closure_time;
        $scar->root_cause = $request->root_cause;
        $scar->risk_analysis = $request->risk_analysis;
        $scar->effectiveness_check_summary = $request->effectiveness_check_summary;
        $scar->capa_plan = $request->capa_plan;

        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();

        $scar->stage = 1;
        $scar->status = "Opened";
        $scar->save();


        /******************* Audit Trail Code ***********************/
        $history = new ScarAuditTrail;
        $history->scar_id = $scar->id;
        $history->activity_type = 'Record Number';
        $history->previous = "Null";
        $history->current =  Helpers::getDivisionName($request->division_id).'/SCAR/'. date('Y') .'/'. str_pad( $scar->record, 4, '0', STR_PAD_LEFT);
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $scar->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();


        $history = new ScarAuditTrail();
        $history->scar_id = $scar->id;
        $history->activity_type = 'Division';
        $history->previous = "Null";
        $history->current = Helpers::getDivisionName($scar->division_id);
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $scar->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();


        $history = new ScarAuditTrail;
        $history->scar_id = $scar->id;
        $history->activity_type = 'Inititator';
        $history->previous = "Null";
        $history->current = Auth::user()->name;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $scar->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        $history = new ScarAuditTrail;
        $history->scar_id = $scar->id;
        $history->activity_type = 'Short Description';
        $history->previous = "Null";
        $history->current = $request->short_description;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $scar->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        $history = new ScarAuditTrail;
        $history->scar_id = $scar->id;
        $history->activity_type = 'Due Date';
        $history->previous = "Null";
        $history->current = $scar->due_date;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $scar->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        $history = new ScarAuditTrail;
        $history->scar_id = $scar->id;
        $history->activity_type = 'Initiation Date';
        $history->previous = "Null";
        $history->current = $scar->initiation_date;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $scar->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        if(!empty($request->assign_to)){

            $assign_to_name = User::where('id', $request->assign_to)->value('name');

            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Assigned To';
            $history->previous = "Null";
            $history->current = $assign_to_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->scar_name)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'SCAR Name';
            $history->previous = "Null";
            $history->current = $request->scar_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->owner_name)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Owner Name ';
            $history->previous = "Null";
            $history->current = $request->owner_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->followup_date)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Follow Up Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($request->followup_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_site)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Supplier Site';
            $history->previous = "Null";
            $history->current = $request->supplier_site;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_product)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Supplier Product';
            $history->previous = "Null";
            $history->current = $request->supplier_product;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_site_contact_email)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Supplier Site Contact Email';
            $history->previous = "Null";
            $history->current = $request->supplier_site_contact_email;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->description)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Description';
            $history->previous = "Null";
            $history->current = $request->description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->recommended_action)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Recommended Action';
            $history->previous = "Null";
            $history->current = $request->recommended_action;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->non_conformance)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Non Conformance';
            $history->previous = "Null";
            $history->current = $request->non_conformance;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->expected_closure_date)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Expected Closure Date';
            $history->previous = "Null";
            $history->current =  Helpers::getDateFormat($request->expected_closure_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';

            $history->save();
        }

        if(!empty($request->expected_closure_time)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Expected Closure Time';
            $history->previous = "Null";
            $history->current = $request->expected_closure_time;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->root_cause)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Root Cause';
            $history->previous = "Null";
            $history->current = $request->root_cause;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->risk_analysis)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Risk Analysis';
            $history->previous = "Null";
            $history->current = $request->risk_analysis;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->effectiveness_check_summary)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'Effectiveness Check Summary';
            $history->previous = "Null";
            $history->current = $request->effectiveness_check_summary;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->capa_plan)){
            $history = new ScarAuditTrail;
            $history->scar_id = $scar->id;
            $history->activity_type = 'CAPA Plan';
            $history->previous = "Null";
            $history->current = $request->capa_plan;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $scar->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        // if(!empty($request->record_number)){
        //     $history = new ScarAuditTrail;
        //     $history->scar_id = $scar->id;
        //     $history->activity_type = 'Record Number';
        //     $history->previous = "Null";
        //     $history->current = $request->record_number;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $scar->status;
        //     $history->change_to =   "Opened";
        //     $history->change_from = "Initiation";
        //     $history->action_name = 'Create';
        //     $history->save();
        // }

        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }

    public function show($id){
        $data = SCAR::find($id);
        $supplierName = Supplier::select('id','supplier_name')->get();
        $supplierProduct = Supplier::where('supplier_products' , '!=' , "null")->get();
        $distributionSites = Supplier::where('distribution_sites', '!=', "null")->get();
        return view('frontend.scar.scar_view', compact('data','supplierName','supplierProduct','distributionSites'));
    }

    public function update(Request $request, $id){
        $lastDocument = SCAR::find($id);
        $scar = SCAR::find($id);

        $scar->short_description = $request->short_description;
        $scar->assign_to = $request->assign_to;
        $scar->scar_name = $request->scar_name;
        $scar->owner_name = $request->owner_name;
        $scar->followup_date = $request->followup_date;
        $scar->supplier_site = $request->supplier_site;
        $scar->supplier_product = $request->supplier_product;
        $scar->supplier_site_contact_email = $request->supplier_site_contact_email;
        $scar->description = $request->description;
        $scar->recommended_action = $request->recommended_action;
        $scar->non_conformance = $request->non_conformance;
        $scar->expected_closure_date = $request->expected_closure_date;
        $scar->expected_closure_time = $request->expected_closure_time;
        $scar->root_cause = $request->root_cause;
        $scar->risk_analysis = $request->risk_analysis;
        $scar->effectiveness_check_summary = $request->effectiveness_check_summary;
        $scar->capa_plan = $request->capa_plan;
        $scar->update();


        if($lastDocument->short_description != $request->short_description){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Short Description')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
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
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->assign_to != $request->assign_to){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Assigned To')
            ->exists();

            $previous_assign_to_name = User::where('id', $lastDocument->assign_to)->value('name');
            $current_assign_to_name = User::where('id', $scar->assign_to)->value('name');

            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Assigned To';
            $history->previous = $previous_assign_to_name;
            $history->current = $current_assign_to_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->scar_name != $request->scar_name){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'SCAR Name')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'SCAR Name';
            $history->previous = $lastDocument->scar_name;
            $history->current = $request->scar_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->owner_name != $request->owner_name){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Owner Name')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Owner Name';
            $history->previous = $lastDocument->owner_name;
            $history->current = $request->owner_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->followup_date != $request->followup_date){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Follow Up Date')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Follow Up Date';
            $history->previous = Helpers::getdateFormat($lastDocument->followup_date);
            $history->current = Helpers::getdateFormat($request->followup_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->supplier_site != $request->supplier_site){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Supplier Site')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Supplier Site';
            $history->previous = $lastDocument->supplier_site;
            $history->current = $request->supplier_site;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->supplier_product != $request->supplier_product){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Supplier Product')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Supplier Product';
            $history->previous = $lastDocument->supplier_product;
            $history->current = $request->supplier_product;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->supplier_site_contact_email != $request->supplier_site_contact_email){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Supplier Site Contact Email')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Supplier Site Contact Email';
            $history->previous = $lastDocument->supplier_site_contact_email;
            $history->current = $request->supplier_site_contact_email;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->description != $request->description){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Description')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Description';
            $history->previous = $lastDocument->description;
            $history->current = $request->description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->recommended_action != $request->recommended_action){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Recommended Action')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Recommended Action';
            $history->previous = $lastDocument->recommended_action;
            $history->current = $request->recommended_action;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->non_conformance != $request->non_conformance){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Non Conformance')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Non Conformance';
            $history->previous = $lastDocument->non_conformance;
            $history->current = $request->non_conformance;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->expected_closure_date != $request->expected_closure_date){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Expected Closure Date')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Expected Closure Date';
            $history->previous = Helpers::getDateFormat($lastDocument->expected_closure_date);
            $history->current =  Helpers::getDateFormat($request->expected_closure_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if($lastDocument->expected_closure_time != $request->expected_closure_time){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Expected Closure Time')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Expected Closure Time';
            $history->previous = $lastDocument->expected_closure_time;
            $history->current = $request->expected_closure_time;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->root_cause != $request->root_cause){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Root Cause')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Root Cause';
            $history->previous = $lastDocument->root_cause;
            $history->current = $request->root_cause;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->risk_analysis != $request->risk_analysis){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Risk Analysis')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Risk Analysis';
            $history->previous = $lastDocument->risk_analysis;
            $history->current = $request->risk_analysis;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->effectiveness_check_summary != $request->effectiveness_check_summary){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'Effectiveness Check Summary')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'Effectiveness Check Summary';
            $history->previous = $lastDocument->effectiveness_check_summary;
            $history->current = $request->effectiveness_check_summary;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if($lastDocument->capa_plan != $request->capa_plan){
            $lastDocumentAuditTrail = ScarAuditTrail::where('scar_id', $scar->id)
            ->where('activity_type', 'CAPA Plan')
            ->exists();
            $history = new ScarAuditTrail;
            $history->scar_id = $lastDocument->id;
            $history->activity_type = 'CAPA Plan';
            $history->previous = $lastDocument->capa_plan;
            $history->current = $request->capa_plan;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        toastr()->success("Record Updated Successfully");
        return back();
    }

    public function singleReport($id){
        $data = SCAR::find($id);
        // dd($data);
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');

            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.scar.single-report', compact(
                'data',
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

    public function auditTrail($id){
        $audit = ScarAuditTrail::where('scar_id', $id)->orderByDESC('id')->paginate(5);
        $today = Carbon::now()->format('d-m-y');
        $document = SCAR::where('id', $id)->first();
        $document->originator = User::where('id', $document->initiator_id)->value('name');

        return view('frontend.scar.audit-trail', compact('audit', 'document', 'today'));
    }

    public function auditTrailPdf($id){
        $doc = SCAR::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = ScarAuditTrail::where('scar_id', $id)->orderByDesc('id')->get();
            $audit = ScarAuditTrail::where('scar_id', $id)->get();

            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.scar.audit-trail-pdf', compact('data', 'doc' ,'audit'))
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
                $width / 2.5,
                $height / 2,
                $doc->status,
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

    public function sendStage(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $scar = SCAR::find($id);
            $lastDocument = SCAR::find($id);
            if ($scar->stage == 1) {
                    $scar->stage = "2";
                    $scar->status = "Submitted to Supplier";
                    $scar->submitted_by = Auth::user()->name;
                    $scar->submitted_on = Carbon::now()->format('d-M-Y');
                    $scar->submitted_comment = $request->comments;
                    $history = new ScarAuditTrail();
                    $history->scar_id = $id;
                    $history->activity_type = 'Submit By, Submit On';
                    if (is_null($lastDocument->submitted_by) || $lastDocument->submitted_by === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->submitted_by . ' , ' . $lastDocument->submitted_on;
                    }
                    $history->current = $scar->submitted_by . ' , ' . $scar->submitted_on;
                    $history->action = 'Submit';

                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Submitted to Supplier";
                    $history->change_from = $lastDocument->status;
                    $history->stage = '';
                    if (is_null($lastDocument->submitted_by) || $lastDocument->submitted_by === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();

                    $list = Helpers::getVendorList($scar->division_id);
                    //  return $list;
                    foreach ($list as $u) {
                        // if($u->q_m_s_divisions_id == $scar->division_id){
                            $email = Helpers::getVendorEmail($u->user_id);
                            if (!empty($email)) {
                                try {
                                    Mail::send(
                                        'mail.view-mail',
                                         ['data' => $scar ,'site'=>'SCAR','history'=> 'Submit ','process' => 'SCAR','comment' => $scar->submitted_comment,'user'=> Auth::user()->name],
                                
                                         function ($message) use ($email, $scar ) {
                                             $message->to($email)
                                             ->subject("QMS Notification: SCAR , Record #" . str_pad( $scar->record, 4, '0', STR_PAD_LEFT) . " - Activity: Submit performed"); }
                                         );
                                    
                                } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            // }
                     }
                  }


                    $scar->update();

                    toastr()->success('Sent to Submitted to Supplier');
                    return back();
            }
            if ($scar->stage == 2) {
                    $scar->stage = "3";
                    $scar->status = "Acknowledged by Supplier";
                    $scar->acknowledge_by = Auth::user()->name;
                    $scar->acknowledge_on = Carbon::now()->format('d-M-Y');
                    $scar->acknowledge_comment = $request->comments;

                    $history = new ScarAuditTrail();
                    $history->scar_id = $id;
                    $history->activity_type = 'Acknowledge By, Acknowledge On';
                    if (is_null($lastDocument->acknowledge_by) || $lastDocument->acknowledge_by === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->acknowledge_by . ' , ' . $lastDocument->acknowledge_on;
                    }
                    $history->current =  $scar->acknowledge_by . ' , ' .  $scar->acknowledge_on;
                    $history->action = 'Acknowledge';
                   // $history->current = "Not Applicable";
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Acknowleged by Supplier";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
                    if (is_null($lastDocument->acknowledge_by) || $lastDocument->acknowledge_by === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();

                //     $list = Helpers::getScarInitiatorList($scar->division_id);
                //     //  return $list;
                //     foreach ($list as $u) {
                //         // if($u->q_m_s_divisions_id == $scar->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //             if (!empty($email)) {
                //                 try {
                //                     Mail::send(
                //                         'mail.view-mail',
                //                          ['data' => $scar],
                //                       function ($message) use ($email) {
                //                           $message->to($email)
                //                               ->subject("Document is Sent By".Auth::user()->name);
                //                       }
                //                     );
                //                 } catch (\Exception $e) {
                //                     \Log::error('Mail failed to send: ' . $e->getMessage());
                //                 }
                //             // }
                //      }
                //   }

                    $scar->update();

                    toastr()->success('Sent to Acknowleged by Supplier');
                    return back();
            }
            if ($scar->stage == 3) {
                $scar->stage = "4";
                $scar->status = "Work in Progress";
                $scar->workin_progress_by = Auth::user()->name;
                $scar->workin_progress_on = Carbon::now()->format('d-M-Y');
                $scar->workin_progress_comment = $request->comments;

                $history = new ScarAuditTrail();
                    $history->scar_id = $id;
                    // $history->activity_type = 'Activity Log';
                    $history->activity_type = 'Work in Progress By, Work in Progress On';
                    if (is_null($lastDocument->audit_schedule_by) || $lastDocument->audit_schedule_by === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->audit_schedule_by . ' , ' . $lastDocument->workin_progress_on;
                    }
                    $history->current = $scar->workin_progress_by . ' , ' .  $scar->workin_progress_on;
                   // $history->current = "Not Applicable";
                   $history->action = 'Work in Progress';
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Work in Progress";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
                    if (is_null($lastDocument->workin_progress_by) || $lastDocument->workin_progress_by === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                //  $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $scar->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {
                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $scar],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Send By".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      }
                //   }
                $scar->update();

                toastr()->success('Sent to Work in Progress');
                return back();
            }
            if ($scar->stage == 4) {
                $scar->stage = "5";
                $scar->status = "Response Received";
                $scar->response_submitted_by = Auth::user()->name;
                $scar->response_submitted_on = Carbon::now()->format('d-M-Y');
                $scar->response_submitted_comment = $request->comments;

                $history = new ScarAuditTrail();
                    $history->scar_id = $id;
                    $history->activity_type = 'Response By,Response On';
                    if (is_null($lastDocument->response_submitted_by) || $lastDocument->response_submitted_by === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->response_submitted_by . ' , ' . $lastDocument->response_submitted_on;
                    }
                    $history->current =  $scar->response_submitted_by . ' , ' .  $scar->response_submitted_on;
                    $history->action = 'Response';
                   // $history->current = $scar->response_submitted_by;
                    $history->comment = $request->comments;

                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Response Received";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
                    if (is_null($lastDocument->response_submitted_by) || $lastDocument->response_submitted_by === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();

                    $list = Helpers::getScarInitiatorList($scar->division_id);
                    //  return $list;
                    foreach ($list as $u) {
                        // if($u->q_m_s_divisions_id == $scar->division_id){
                            $email = Helpers::getInitiatorEmail($u->user_id);
                            if (!empty($email)) {
                                try {
                                    Mail::send(
                                        'mail.view-mail',
                                    //      ['data' => $scar],
                                    //   function ($message) use ($email) {
                                    //       $message->to($email)
                                    //           ->subject("Document is Sent By".Auth::user()->name);
                                    //   }
                                    // );
                                    ['data' => $scar ,'site'=>'SCAR','history'=> 'Response ' ,'process' => 'SCAR','comment' => $scar->response_submitted_comment,'user'=> Auth::user()->name],
                                    function ($message) use ($email, $scar ) {
                                        $message->to($email)
                                        ->subject("QMS Notification: SCAR , Record #" . str_pad( $scar->record, 4, '0', STR_PAD_LEFT) . " - Activity: Response performed"); }
                                    );
                                } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            // }
                     }
                  }

                $scar->update();
                toastr()->success('Sent to Response Received');
                return back();
            }
            if ($scar->stage == 5) {
                $scar->stage = "6";
                $scar->status = "Closed - Approved";
                $scar->approved_by = Auth::user()->name;
                $scar->approved_on = Carbon::now()->format('d-M-Y');
                $scar->approved_comment = $request->comments;
                $history = new ScarAuditTrail();
                $history->scar_id = $id;
                $history->activity_type = 'Approve By, Approve On';
                    if (is_null($lastDocument->approved_by) || $lastDocument->approved_by === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->approved_by . ' , ' . $lastDocument->approved_on;
                    }
                    $history->current =  $scar->approved_by . ' , ' .  $scar->approved_on;
                    $history->action = 'Approve';
                   // $history->current = $scar->approved_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Closed - Approved";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
                    if (is_null($lastDocument->approved_by) || $lastDocument->approved_by === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }

                    $history->save();

                    $list = Helpers::getScarInitiatorList($scar->division_id);
                    //  return $list;
                    foreach ($list as $u) {
                        // if($u->q_m_s_divisions_id == $scar->division_id){
                            $email = Helpers::getInitiatorEmail($u->user_id);
                            if (!empty($email)) {
                                try {
                                    Mail::send(
                                        'mail.view-mail',
                                        ['data' => $scar ,'site'=>'SCAR','history'=> 'Approve ','process' => 'SCAR', 'comment' => $scar->approved_comment,'user'=> Auth::user()->name],
                                         function ($message) use ($email, $scar ) {
                                             $message->to($email)
                                             ->subject("QMS Notification: SCAR , Record # " . str_pad( $scar->record, 4, '0', STR_PAD_LEFT) . " - Activity: Approve performed"); }
                                         );
                                } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            // }
                     }
                  }

                  $list = Helpers::getVendorList($scar->division_id);
                    //  return $list;
                    foreach ($list as $u) {
                        // if($u->q_m_s_divisions_id == $scar->division_id){
                            $email = Helpers::getVendorEmail($u->user_id);
                            if (!empty($email)) {
                                try {
                                    Mail::send(
                                        'mail.view-mail',
                                        ['data' => $scar,'site'=>'SCAR','history'=> 'Approve ','process' => 'SCAR', 'comment' => $scar->approved_comment,'user'=> Auth::user()->name],
                                        function ($message) use ($email, $scar ) {
                                            $message->to($email)
                                            ->subject("QMS Notification: SCAR , Record #" . str_pad( $scar->record, 4, '0', STR_PAD_LEFT) . " - Activity: Approve performed"); }
                                        );
                                } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            // }
                     }
                  }

                $scar->update();
                toastr()->success('Closed - Approved');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function sendToCancel(Request $request, $id){
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $scar = SCAR::find($id);
            $lastDocument = SCAR::find($id);

            $scar->stage = "0";
            $scar->status = "Close - Cancelled";
            $scar->cancelled_by = Auth::user()->name;
            $scar->cancelled_on = Carbon::now()->format('d-M-Y');
            $scar->cancelled_comment = $request->comments;

            $history = new ScarAuditTrail();
            $history->scar_id = $id;
            $history->activity_type = 'Activity Log';
            $history->previous = "";
            $history->action = 'Cancel';
            $history->current = "";
            $history->comment = $request->comments;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =  "Close - Cancelled";
            $history->change_from = $lastDocument->status;
            $history->save();
            $scar->update();

            // foreach ($list as $u) {
            //     if ($u->q_m_s_divisions_id == $deviation->division_id) {
            //         $email = Helpers::getInitiatorEmail($u->user_id);
            //         if ($email !== null) {

            //             try {
            //                 Mail::send(
            //                     'mail.view-mail',
            //                     ['data' => $deviation],
            //                     function ($message) use ($email) {
            //                         $message->to($email)
            //                             ->subject("Activity Performed By " . Auth::user()->name);
            //                     }
            //                 );
            //             } catch (\Exception $e) {
            //                 //log error
            //             }
            //         }
            //     }
            // }
            $scar->update();
            toastr()->success('Document Sent to Close Cancelled');
            return back();

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function rejectStage(Request $request, $id){
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $scar = SCAR::find($id);
            $lastDocument = SCAR::find($id);

            $scar->stage = "4";
            $scar->status = "Work in Progress";
            $scar->rejected_by = Auth::user()->name;
            $scar->rejected_on = Carbon::now()->format('d-M-Y');
            $scar->rejected_comment = $request->comments;

            $history = new ScarAuditTrail();
            $history->scar_id = $id;
            $history->activity_type = 'Reject By, Reject On';
                    if (is_null($lastDocument->rejected_by) || $lastDocument->rejected_by === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->rejected_by . ' , ' . $lastDocument->rejected_on;
                    }
                    $history->current =  $scar->rejected_by . ' , ' .  $scar->rejected_on;
           // $history->activity_type = 'Activity Log';
            $history->previous = "";
            $history->action = 'Reject';
            //s$history->current = "";
            $history->comment = $request->comments;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =  "Work in Progress";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->rejected_by) || $lastDocument->rejected_by === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
            $scar->update();

            $list = Helpers::getVendorList($scar->division_id);
            //  return $list;
            foreach ($list as $u) {
                // if($u->q_m_s_divisions_id == $scar->division_id){
                    $email = Helpers::getVendorEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $scar ,'site'=>'SCAR','history'=> 'Reject','process' => 'SCAR', 'comment' => $scar->rejected_comment,'user'=> Auth::user()->name],
                                         function ($message) use ($email, $scar ) {
                                             $message->to($email)
                                             ->subject("QMS Notification: SCAR , Record #" . str_pad( $scar->record, 4, '0', STR_PAD_LEFT) . " - Activity:  Reject performed"); }
                                         );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    // }
             }
          }

            $scar->update();
            toastr()->success('Document Sent to Work in Progress');
            return back();

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


}
