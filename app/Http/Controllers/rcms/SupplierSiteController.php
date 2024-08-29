<?php

namespace App\Http\Controllers\rcms;

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

use App\Models\{RecordNumber,
SupplierSite,
SupplierSiteGrid,
SupplierSiteAuditTrail,
RoleGroup,
AuditReviewersDetails,
CC,
Deviation,
User,
SCAR,
Supplier,
RiskManagement,
OpenStage,
Capa,
ActionItem
};

class SupplierSiteController extends Controller
{
    public function index(Request $request){        
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');

        return view('frontend.supplier-site.suppliersite_new', compact('formattedDate', 'due_date', 'record_number'));
    }

    public function store(Request $request){
        // dd($request->all());
        $supplierSite = new SupplierSite();
        $supplierSite->type = "Supplier Site";
        $supplierSite->division_id = $request->division_id;
        $supplierSite->record = DB::table('record_numbers')->value('counter') + 1;
        $supplierSite->parent_id = $request->parent_id;
        $supplierSite->parent_type = $request->parent_type;
        $supplierSite->initiator_id = Auth::user()->id;
        $supplierSite->date_opened = $request->date_opened;
        $supplierSite->intiation_date = $request->intiation_date;
        $supplierSite->short_description = $request->short_description;
        $supplierSite->assign_to = $request->assign_to;
        $supplierSite->due_date = Carbon::now()->addDays(30)->format('d-M-Y');
        $supplierSite->supplier_person = $request->supplier_person;        
        $supplierSite->supplier_contact_person = $request->supplier_contact_person;
        $supplierSite->supplier_products = $request->supplier_products;
        $supplierSite->description = $request->description;
        $supplierSite->supplier_type = $request->supplier_type;
        $supplierSite->supplier_sub_type = $request->supplier_sub_type;
        $supplierSite->supplier_other_type = $request->supplier_other_type;
        $supplierSite->supply_from = $request->supply_from;
        $supplierSite->supply_to = $request->supply_to;
        $supplierSite->supplier_website = $request->supplier_website;
        $supplierSite->supplier_web_search = $request->supplier_web_search;
        $supplierSite->related_url = $request->related_url;
        $supplierSite->related_quality_events = $request->related_quality_events;

        // if (!empty($request->logo_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('logo_attachment')) {
        //         foreach ($request->file('logo_attachment') as $file) {
        //             $name = "Supplier-Site" . '-logo_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->logo_attachment = json_encode($files);
        // }

        if (!empty($request->logo_attachment)) {
            $files = [];
            if ($request->hasfile('logo_attachment')) {
                foreach ($request->file('logo_attachment') as $file) {
                    $name = "Supplier-Site" . '-logo_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplierSite->logo_attachment = json_encode($files);
            // dd($openState->in_attachment);
        }


        // if (!empty($request->supplier_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('supplier_attachment')) {
        //         foreach ($request->file('supplier_attachment') as $file) {
        //             $name = "Supplier-Site" . '-supplier_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->supplier_attachment = json_encode($files);
        // }
        
        if (!empty($request->supplier_attachment)) {
            $files = [];
            if ($request->hasfile('supplier_attachment')) {
                foreach ($request->file('supplier_attachment') as $file) {
                    $name = "Supplier-Site" . '-supplier_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplierSite->supplier_attachment = json_encode($files);
            // dd($openState->in_attachment);
        }

        /****************** HOD Review ********************/
        $supplierSite->HOD_feedback = $request->HOD_feedback;
        $supplierSite->HOD_comment = $request->HOD_comment;

        if (!empty($request->HOD_attachment)) {
            $files = [];
            if ($request->hasfile('HOD_attachment')) {
                foreach ($request->file('HOD_attachment') as $file) {
                    $name = "Supplier-Site" . '-HOD_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplierSite->HOD_attachment = json_encode($files);
        }

        /****************** Supplier Details ********************/
        $supplierSite->supplier_name = $request->supplier_name;
        $supplierSite->supplier_id = $request->supplier_id;        
        $supplierSite->manufacturer_name = $request->manufacturer_name;
        $supplierSite->manufacturer_id = $request->manufacturer_id;
        $supplierSite->vendor_name = $request->vendor_name;
        $supplierSite->vendor_id = $request->vendor_id;
        $supplierSite->contact_person = $request->contact_person;
        $supplierSite->other_contacts = $request->other_contacts;
        $supplierSite->supplier_serivce = $request->supplier_serivce;
        $supplierSite->zone = $request->zone;
        $supplierSite->country = $request->country == 'Select Country' ? null: $request->country;
        $supplierSite->state = $request->state == 'Select State/District' ? null: $request->state;
        $supplierSite->city = $request->city == 'Select City' ? null: $request->city ;
        $supplierSite->address = $request->address;
        $supplierSite->suppplier_web_site = $request->suppplier_web_site;
        $supplierSite->iso_certified_date = $request->iso_certified_date;
        $supplierSite->suppplier_contacts = $request->suppplier_contacts;
        $supplierSite->related_non_conformance = $request->related_non_conformance;
        $supplierSite->suppplier_agreement = $request->suppplier_agreement;
        $supplierSite->regulatory_history = $request->regulatory_history;
        $supplierSite->distribution_sites = $request->distribution_sites;
        $supplierSite->manufacturing_sited = $request->manufacturing_sited;
        $supplierSite->quality_management = $request->quality_management;
        $supplierSite->bussiness_history = $request->bussiness_history;
        $supplierSite->performance_history = $request->performance_history;
        $supplierSite->compliance_risk = $request->compliance_risk;

        /****************** Score Card Content ********************/
        $supplierSite->cost_reduction = $request->cost_reduction;
        $supplierSite->cost_reduction_weight = $request->cost_reduction_weight;        
        $supplierSite->payment_term = $request->payment_term;
        $supplierSite->payment_term_weight = $request->payment_term_weight;
        $supplierSite->lead_time_days = $request->lead_time_days;
        $supplierSite->lead_time_days_weight = $request->lead_time_days_weight;
        $supplierSite->ontime_delivery = $request->ontime_delivery;
        $supplierSite->ontime_delivery_weight = $request->ontime_delivery_weight;
        $supplierSite->supplier_bussiness_planning = $request->supplier_bussiness_planning;
        $supplierSite->supplier_bussiness_planning_weight = $request->supplier_bussiness_planning_weight;
        $supplierSite->rejection_ppm = $request->rejection_ppm;
        $supplierSite->rejection_ppm_weight = $request->rejection_ppm_weight;
        $supplierSite->quality_system = $request->quality_system;
        $supplierSite->quality_system_ranking = $request->quality_system_ranking;
        $supplierSite->car_generated = $request->car_generated;
        $supplierSite->car_generated_weight = $request->car_generated_weight;
        $supplierSite->closure_time = $request->closure_time;
        $supplierSite->closure_time_weight = $request->closure_time_weight;
        $supplierSite->end_user_satisfaction = $request->end_user_satisfaction;
        $supplierSite->end_user_satisfaction_weight = $request->end_user_satisfaction_weight;
        $supplierSite->scorecard_record = $request->scorecard_record;
        $supplierSite->achieved_score = $request->achieved_score;
        $supplierSite->total_available_score = $request->total_available_score;
        $supplierSite->total_score = $request->total_score;

        /****************** QA Reviewer ********************/
        $supplierSite->QA_reviewer_feedback = $request->QA_reviewer_feedback;
        $supplierSite->QA_reviewer_comment = $request->QA_reviewer_comment;

        if (!empty($request->QA_reviewer_attachment)) {
            $files = [];
            if ($request->hasfile('QA_reviewer_attachment')) {
                foreach ($request->file('QA_reviewer_attachment') as $file) {
                    $name = "Supplier-Site" . '-QA_reviewer_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplierSite->QA_reviewer_attachment = json_encode($files);
        }

        /****************** Risk Assessment Content ********************/
        
        $supplierSite->last_audit_date = $request->last_audit_date;
        $supplierSite->next_audit_date = $request->next_audit_date;   
              
        $supplierSite->audit_frequency = $request->audit_frequency;
        $supplierSite->last_audit_result = $request->last_audit_result;
        $supplierSite->facility_type = $request->facility_type;
        $supplierSite->nature_of_employee = $request->nature_of_employee;
        $supplierSite->technical_support = $request->technical_support;
        $supplierSite->survice_supported = $request->survice_supported;
        $supplierSite->reliability = $request->reliability;
        $supplierSite->revenue = $request->revenue;
        $supplierSite->client_base = $request->client_base;
        $supplierSite->previous_audit_result = $request->previous_audit_result;
        $supplierSite->risk_raw_total = $request->risk_raw_total;
        $supplierSite->risk_median = $request->risk_median;
        $supplierSite->risk_average = $request->risk_average;
        $supplierSite->risk_assessment_total = $request->risk_assessment_total;

        /****************** QA Reviewer ********************/
        $supplierSite->QA_head_comment = $request->QA_head_comment;

        if (!empty($request->QA_head_attachment)) {
            $files = [];
            if ($request->hasfile('QA_head_attachment')) {
                foreach ($request->file('QA_head_attachment') as $file) {
                    $name = "Supplier-Site" . '-QA_head_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplierSite->QA_head_attachment = json_encode($files);
        }

        /************ Additional Attchment Code ************/
        if (!empty($request->iso_certificate_attachment)) {
            $files = [];
            if ($request->hasfile('iso_certificate_attachment')) {
                foreach ($request->file('iso_certificate_attachment') as $file) {
                    $name = "Supplier-Site" . '-iso_certificate_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplierSite->iso_certificate_attachment = json_encode($files);
        }

        if (!empty($request->gi_additional_attachment)) {
            $files = [];
            if ($request->hasfile('gi_additional_attachment')) {
                foreach ($request->file('gi_additional_attachment') as $file) {
                    $name = "Supplier-Site" . '-gi_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplierSite->gi_additional_attachment = json_encode($files);
        }

        if (!empty($request->hod_additional_attachment)) {
            $files = [];
            if ($request->hasfile('hod_additional_attachment')) {
                foreach ($request->file('hod_additional_attachment') as $file) {
                    $name = "Supplier-Site" . '-hod_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplierSite->hod_additional_attachment = json_encode($files);
        }

        if (!empty($request->supplier_detail_additional_attachment)) {
            $files = [];
            if ($request->hasfile('supplier_detail_additional_attachment')) {
                foreach ($request->file('supplier_detail_additional_attachment') as $file) {
                    $name = "Supplier-Site" . '-supplier_detail_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplierSite->supplier_detail_additional_attachment = json_encode($files);
        }

        if (!empty($request->score_card_additional_attachment)) {
            $files = [];
            if ($request->hasfile('score_card_additional_attachment')) {
                foreach ($request->file('score_card_additional_attachment') as $file) {
                    $name = "Supplier-Site" . '-score_card_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplierSite->score_card_additional_attachment = json_encode($files);
        }

        if (!empty($request->qa_reviewer_additional_attachment)) {
            $files = [];
            if ($request->hasfile('qa_reviewer_additional_attachment')) {
                foreach ($request->file('qa_reviewer_additional_attachment') as $file) {
                    $name = "Supplier-Site" . '-qa_reviewer_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplierSite->qa_reviewer_additional_attachment = json_encode($files);
        }

        if (!empty($request->risk_assessment_additional_attachment)) {
            $files = [];
            if ($request->hasfile('risk_assessment_additional_attachment')) {
                foreach ($request->file('risk_assessment_additional_attachment') as $file) {
                    $name = "Supplier-Site" . '-risk_assessment_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplierSite->risk_assessment_additional_attachment = json_encode($files);
        }

        if (!empty($request->qa_head_additional_attachment)) {
            $files = [];
            if ($request->hasfile('qa_head_additional_attachment')) {
                foreach ($request->file('qa_head_additional_attachment') as $file) {
                    $name = "Supplier-Site" . '-qa_head_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplierSite->qa_head_additional_attachment = json_encode($files);
        }

        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();

        $supplierSite->status = 'Opened';
        $supplierSite->stage = 1;
        $supplierSite->save();

        $certificationData = SupplierSiteGrid::where(['supplier_site_id' => $supplierSite->id, 'identifier' =>'CertificationData'])->firstOrCreate();
        $certificationData->supplier_site_id = $supplierSite->id;
        $certificationData->identifier = 'CertificationData';
        $certificationData->data = $request->certificationData;
        $certificationData->save();

        

        /******************* Audit Trail Code ***********************/
        
        $history = new SupplierSiteAuditTrail;
        $history->supplier_site_id = $supplierSite->id;
        $history->activity_type = 'Record Number';
        $history->previous = "Null";
       // $history->current = $supplierSite->record;
    //    $history->current = Helpers::getDivisionName(session()->get('division')) }}/SS/{{ Helpers::year($supplierSite->created_at) }}/{{ str_pad($supplierSite->record, 4, '0', STR_PAD_LEFT); 

        $history->current = Helpers::getDivisionName(session()->get('division')) . "/SS/" . Helpers::year($supplierSite->created_at) . "/" . str_pad($supplierSite->record, 4, '0', STR_PAD_LEFT);

        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $supplierSite->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        $history = new SupplierSiteAuditTrail;
        $history->supplier_site_id = $supplierSite->id;
        $history->activity_type = 'Division';
        $history->previous = "Null";
        $history->current =  Helpers::getDivisionName(session()->get('division'));
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $supplierSite->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();
        
        $history = new SupplierSiteAuditTrail;
        $history->supplier_site_id = $supplierSite->id;
        $history->activity_type = 'Initiator';
        $history->previous = "Null";
        $history->current = Auth::user()->name;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $supplierSite->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        $history = new SupplierSiteAuditTrail;
        $history->supplier_site_id = $supplierSite->id;
        $history->activity_type = 'Short Description';
        $history->previous = "Null";
        $history->current = $supplierSite->short_description;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $supplierSite->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        $history = new SupplierSiteAuditTrail;
        $history->supplier_site_id = $supplierSite->id;
        $history->activity_type = 'Due Date';
        $history->previous = "Null";
        $history->current = $supplierSite->due_date;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $supplierSite->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        $history = new SupplierSiteAuditTrail;
        $history->supplier_site_id = $supplierSite->id;
        $history->activity_type = 'Initiation Date';
        $history->previous = "Null";
        $history->current = Helpers::getdateFormat($supplierSite->intiation_date);
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $supplierSite->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        if(!empty($request->assign_to)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Assigned To';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($supplierSite->assign_to);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->supplier_person)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Supplier';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($supplierSite->supplier_person);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        if(!empty($supplierSite->logo_attachment)){
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Logo';
            $history->previous = "Null";
            $history->current = $supplierSite->logo_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        if(!empty($request->supplier_contact_person)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Contact Person';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($supplierSite->supplier_contact_person);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->supplier_products)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Suppliers Products';
            $history->previous = "Null";
            $history->current = $supplierSite->supplier_products;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

         if(!empty($request->description)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Description';
            $history->previous = "Null";
            $history->current = $supplierSite->description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_type)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Type';
            $history->previous = "Null";
            $history->current = $supplierSite->supplier_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_sub_type)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Sub Type';
            $history->previous = "Null";
            $history->current = $supplierSite->supplier_sub_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_other_type)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Other Type';
            $history->previous = "Null";
            $history->current = $supplierSite->supplier_other_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supply_from)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Supply From';
            $history->previous = "Null";
            $history->current = $supplierSite->supply_from;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supply_to)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Supply To';
            $history->previous = "Null";
            $history->current = $supplierSite->supply_to;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
         
        if(!empty($request->supplier_website)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Supplier Website';
            $history->previous = "Null";
            $history->current = $supplierSite->supplier_website;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->supplier_web_search)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Web Search';
            $history->previous = "Null";
            $history->current = $supplierSite->supplier_web_search;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($supplierSite->supplier_attachment)){
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'File Attachment';
            $history->previous = "Null";
            $history->current = $supplierSite->supplier_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($supplierSite->gi_additional_attachment)) {
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Additional Attachment';
            $history->previous = "Null";
            $history->current = $supplierSite->gi_additional_attachment;
            $history->comment ="Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to= "Opened";
            $history->change_from= "Initiation";
            $history->action_name="Create";
            $history->save();
        }
        
        if(!empty($request->related_url)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Related URLs';
            $history->previous = "Null";
            $history->current = $supplierSite->related_url;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->related_quality_events)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Related Quality Events';
            $history->previous = "Null";
            $history->current = $supplierSite->related_quality_events;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->HOD_feedback)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'HOD Feedback';
            $history->previous = "Null";
            $history->current = $supplierSite->HOD_feedback;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->HOD_comment)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'HOD Comment';
            $history->previous = "Null";
            $history->current = $supplierSite->HOD_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        if (!empty($supplierSite->HOD_attachment)) {
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'HOD Attachment';
            $history->previous = "Null";
            $history->current = $supplierSite->HOD_attachment;
            $history->comment ="Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to= "Opened";
            $history->change_from= "Initiation";
            $history->action_name="Create";
            $history->save();
        }

        if (!empty($supplierSite->hod_additional_attachment)) {
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'HOD Additional Attachment';
            $history->previous = "Null";
            $history->current = $supplierSite->hod_additional_attachment;
            $history->comment ="Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to= "Opened";
            $history->change_from= "Initiation";
            $history->action_name="Create";
            $history->save();
        }
        
        if(!empty($request->supplier_name)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Supplier';
            $history->previous = "Null";
            $history->current = $supplierSite->supplier_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_id)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Supplier ID';
            $history->previous = "Null";
            $history->current = $supplierSite->supplier_id;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->manufacturer_name)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Manufacturer';
            $history->previous = "Null";
            $history->current = $supplierSite->manufacturer_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->manufacturer_id)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Manufacturer ID';
            $history->previous = "Null";
            $history->current = $supplierSite->manufacturer_id;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->vendor_name)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Vendor';
            $history->previous = "Null";
            $history->current = $supplierSite->vendor_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->vendor_id)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Vendor ID';
            $history->previous = "Null";
            $history->current = $supplierSite->vendor_id;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->contact_person)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Contact Person';
            $history->previous = "Null";
            $history->current = $supplierSite->contact_person;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->other_contacts)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Other Contacts';
            $history->previous = "Null";
            $history->current = $supplierSite->other_contacts;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_serivce)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Supplier Services';
            $history->previous = "Null";
            $history->current = $supplierSite->supplier_serivce;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->zone)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Zone';
            $history->previous = "Null";
            $history->current = $supplierSite->zone;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->country)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Country';
            $history->previous = "Null";
            $history->current = $supplierSite->country;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->state)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'State';
            $history->previous = "Null";
            $history->current = $supplierSite->state;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->city)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'City';
            $history->previous = "Null";
            $history->current = $supplierSite->city;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->address)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Address';
            $history->previous = "Null";
            $history->current = $supplierSite->address;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->suppplier_web_site)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Supplier Website';
            $history->previous = "Null";
            $history->current = $supplierSite->suppplier_web_site;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->iso_certified_date)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'ISO Certification Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($supplierSite->iso_certified_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        if (!empty($supplierSite->iso_certificate_attachment)) {
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'ISO Certificate Attachment';
            $history->previous = "Null";
            $history->current = $supplierSite->iso_certificate_attachment;
            $history->comment ="Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to= "Opened";
            $history->change_from= "Initiation";
            $history->action_name="Create";
            $history->save();
        }
        
        if(!empty($request->suppplier_contacts)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Contracts';
            $history->previous = "Null";
            $history->current = $supplierSite->suppplier_contacts;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->related_non_conformance)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Related Non Conformances';
            $history->previous = "Null";
            $history->current = $supplierSite->related_non_conformance;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->suppplier_agreement)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Supplier Contracts/Agreements';
            $history->previous = "Null";
            $history->current = $supplierSite->suppplier_agreement;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->regulatory_history)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Regulatory History';
            $history->previous = "Null";
            $history->current = $supplierSite->regulatory_history;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->distribution_sites)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Distribution Sites';
            $history->previous = "Null";
            $history->current = $supplierSite->distribution_sites;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->manufacturing_sited)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Manufacturing Sites';
            $history->previous = "Null";
            $history->current = $supplierSite->manufacturing_sited;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->quality_management)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Quality Management';
            $history->previous = "Null";
            $history->current = $supplierSite->quality_management;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->bussiness_history)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Business History';
            $history->previous = "Null";
            $history->current = $supplierSite->bussiness_history;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->performance_history)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Performance History';
            $history->previous = "Null";
            $history->current = $supplierSite->performance_history;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->compliance_risk)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Compliance Risk';
            $history->previous = "Null";
            $history->current = $supplierSite->compliance_risk;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        if (!empty($supplierSite->supplier_detail_additional_attachment)) {
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Supplier Details Additional Attachment';
            $history->previous = "Null";
            $history->current = $supplierSite->supplier_detail_additional_attachment;
            $history->comment ="Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to= "Opened";
            $history->change_from= "Initiation";
            $history->action_name="Create";
            $history->save();
        }
        if(!empty($request->cost_reduction)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Cost Reduction';
            $history->previous = "Null";
            $history->current = $supplierSite->cost_reduction;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->cost_reduction_weight)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Cost Reduction Weight';
            $history->previous = "Null";
            $history->current = $supplierSite->cost_reduction_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->payment_term)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Payment Terms';
            $history->previous = "Null";
            $history->current = $supplierSite->payment_term;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->payment_term_weight)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Payment Terms Weight';
            $history->previous = "Null";
            $history->current = $supplierSite->payment_term_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->lead_time_days)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Lead Time Days';
            $history->previous = "Null";
            $history->current = $supplierSite->lead_time_days;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->lead_time_days_weight)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Lead Time Days Weight';
            $history->previous = "Null";
            $history->current = $supplierSite->lead_time_days_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->ontime_delivery)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'On-Time Delivery';
            $history->previous = "Null";
            $history->current = $supplierSite->ontime_delivery;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->ontime_delivery_weight)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'On-Time Delivery Weight';
            $history->previous = "Null";
            $history->current = $supplierSite->ontime_delivery_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_bussiness_planning)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Supplier Business Planning';
            $history->previous = "Null";
            $history->current = $supplierSite->supplier_bussiness_planning;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_bussiness_planning_weight)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Supplier Business Weight';
            $history->previous = "Null";
            $history->current = $supplierSite->supplier_bussiness_planning_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->rejection_ppm)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Rejection in PPM';
            $history->previous = "Null";
            $history->current = $supplierSite->rejection_ppm;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->rejection_ppm_weight)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Rejection in PPM Weight';
            $history->previous = "Null";
            $history->current = $supplierSite->rejection_ppm_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->quality_system)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Quality Systems';
            $history->previous = "Null";
            $history->current = $supplierSite->quality_system;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->quality_system_ranking)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Quality Systems Weight';
            $history->previous = "Null";
            $history->current = $supplierSite->quality_system_ranking;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->car_generated)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = '# of CARs generated';
            $history->previous = "Null";
            $history->current = $supplierSite->car_generated;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->car_generated_weight)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = '# of CARs generated Weight';
            $history->previous = "Null";
            $history->current = $supplierSite->car_generated_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->closure_time)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'CAR Closure Time';
            $history->previous = "Null";
            $history->current = $supplierSite->closure_time;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->closure_time_weight)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'CAR Closure Time Weight';
            $history->previous = "Null";
            $history->current = $supplierSite->closure_time_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->end_user_satisfaction)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'End-User Satisfaction';
            $history->previous = "Null";
            $history->current = $supplierSite->end_user_satisfaction;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->end_user_satisfaction_weight)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'End-User Satisfaction Weight';
            $history->previous = "Null";
            $history->current = $supplierSite->end_user_satisfaction_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        if (!empty($supplierSite->score_card_additional_attachment)) {
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Score Card Additional Attachment';
            $history->previous = "Null";
            $history->current = $supplierSite->score_card_additional_attachment;
            $history->comment ="Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to= "Opened";
            $history->change_from= "Initiation";
            $history->action_name="Create";
            $history->save();
        }
        if(!empty($request->QA_reviewer_feedback)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'QA Reviewer Feedback';
            $history->previous = "Null";
            $history->current = $supplierSite->QA_reviewer_feedback;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->QA_reviewer_comment)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'QA Reviewer Comment';
            $history->previous = "Null";
            $history->current = $supplierSite->QA_reviewer_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        if (!empty($supplierSite->QA_reviewer_attachment)) {
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'QA Reviewer Attachment';
            $history->previous = "Null";
            $history->current = $supplierSite->QA_reviewer_attachment;
            $history->comment ="Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to= "Opened";
            $history->change_from= "Initiation";
            $history->action_name="Create";
            $history->save();
        }
        
        if (!empty($supplierSite->qa_reviewer_additional_attachment)) {
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'QA Reviewer Additional Attachment';
            $history->previous = "Null";
            $history->current = $supplierSite->qa_reviewer_additional_attachment;
            $history->comment ="Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to= "Opened";
            $history->change_from= "Initiation";
            $history->action_name="Create";
            $history->save();
        }
        if(!empty($request->last_audit_date)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Last Audit Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($supplierSite->last_audit_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->next_audit_date)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Next Audit Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($supplierSite->next_audit_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->audit_frequency)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Audit Frequency';
            $history->previous = "Null";
            $history->current = $supplierSite->audit_frequency;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->last_audit_result)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Last Audit Result';
            $history->previous = "Null";
            $history->current = $supplierSite->last_audit_result;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->facility_type)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Facility Type';
            $history->previous = "Null";
            $history->current = $supplierSite->facility_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->nature_of_employee)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Number of Employees';
            $history->previous = "Null";
            $history->current = $supplierSite->nature_of_employee;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->technical_support)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Access to Technical Support';
            $history->previous = "Null";
            $history->current = $supplierSite->technical_support;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->survice_supported)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Services Supported';
            $history->previous = "Null";
            $history->current = $supplierSite->survice_supported;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->reliability)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Reliability';
            $history->previous = "Null";
            $history->current = $supplierSite->reliability;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->revenue)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Revenue';
            $history->previous = "Null";
            $history->current = $supplierSite->revenue;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->client_base)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Client Base';
            $history->previous = "Null";
            $history->current = $supplierSite->client_base;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->previous_audit_result)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Previous Audit Results';
            $history->previous = "Null";
            $history->current = $supplierSite->previous_audit_result;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        if (!empty($supplierSite->risk_assessment_additional_attachment)) {
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'Risk Assessment Additional Attachment';
            $history->previous = "Null";
            $history->current = $supplierSite->risk_assessment_additional_attachment;
            $history->comment ="Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to= "Opened";
            $history->change_from= "Initiation";
            $history->action_name="Create";
            $history->save();
        }
        if(!empty($request->QA_head_comment)){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'QA Head Comment';
            $history->previous = "Null";
            $history->current = $supplierSite->QA_head_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        if (!empty($supplierSite->QA_head_attachment)) {
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'QA Head Attachment';
            $history->previous = "Null";
            $history->current = $supplierSite->QA_head_attachment;
            $history->comment ="Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to= "Opened";
            $history->change_from= "Initiation";
            $history->action_name="Create";
            $history->save();
        }

        if (!empty($supplierSite->qa_head_additional_attachment)) {
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $supplierSite->id;
            $history->activity_type = 'QA Head Additional Attachment';
            $history->previous = "Null";
            $history->current = $supplierSite->qa_head_additional_attachment;
            $history->comment ="Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplierSite->status;
            $history->change_to= "Opened";
            $history->change_from= "Initiation";
            $history->action_name="Create";
            $history->save();
        }
        
        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }

    public function show(Request $request, $id)
    {
        
        $data = SupplierSite::find($id);
        $gridData = SupplierSiteGrid::where(['supplier_site_id' => $id, 'identifier' => "CertificationData"])->first();
        $certificationData = json_decode($gridData->data, true);
        
        return view('frontend.supplier-site.suppliersite_view', compact('data', 'certificationData',));
    }

    public function update(Request $request, $id)
    {      
        $lastDocument = SupplierSite::find($id);
        $supplierSite = SupplierSite::find($id);
        
        $supplierSite->date_opened = $request->date_opened;
        $supplierSite->short_description = $request->short_description;
        $supplierSite->assign_to = $request->assign_to;
        $supplierSite->supplier_person = $request->supplier_person;        
        $supplierSite->supplier_contact_person = $request->supplier_contact_person;
        $supplierSite->supplier_products = $request->supplier_products;
        $supplierSite->description = $request->description;
        $supplierSite->supplier_type = $request->supplier_type;
        $supplierSite->supplier_sub_type = $request->supplier_sub_type;
        $supplierSite->supplier_other_type = $request->supplier_other_type;
        $supplierSite->supply_from = $request->supply_from;
        $supplierSite->supply_to = $request->supply_to;
        $supplierSite->supplier_website = $request->supplier_website;
        $supplierSite->supplier_web_search = $request->supplier_web_search;
        $supplierSite->related_url = $request->related_url;
        $supplierSite->related_quality_events = $request->related_quality_events;

        // if (!empty($request->logo_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('logo_attachment')) {
        //         foreach ($request->file('logo_attachment') as $file) {
        //             $name = "Supplier-Site" . '-logo_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->logo_attachment = json_encode($files);
        // }

        $files = is_array($request->existing_attach_files) ? $request->existing_attach_files : null;

        if (!empty($request->logo_attachment)) {
            if ($supplierSite->logo_attachment) {
                $existingFiles = json_decode($supplierSite->logo_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('logo_attachment')) {
                foreach ($request->file('logo_attachment') as $file) {
                    $name = $request->name . 'logo_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $supplierSite->logo_attachment = !empty($files) ? json_encode(array_values($files)) : null; 


       

        // if (!empty($request->supplier_attachment))
        // {
        //     $files = [];
        //     if ($request->hasfile('supplier_attachment')) {
        //         foreach ($request->file('supplier_attachment') as $file) {
        //             $name = "Supplier-Site" . '-supplier_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->supplier_attachment = json_encode($files);
        // }

        $files = is_array($request->existing_attach_files1) ? $request->existing_attach_files1 : null;

        if (!empty($request->supplier_attachment)) {
            if ($supplierSite->supplier_attachment) {
                $existingFiles = json_decode($supplierSite->supplier_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('supplier_attachment')) {
                foreach ($request->file('supplier_attachment') as $file) {
                    $name = $request->name . 'supplier_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $supplierSite->supplier_attachment = !empty($files) ? json_encode(array_values($files)) : null; 
        
        /****************** HOD Review ********************/
        $supplierSite->HOD_feedback = $request->HOD_feedback;
        $supplierSite->HOD_comment = $request->HOD_comment;

        // if (!empty($request->HOD_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('HOD_attachment')) {
        //         foreach ($request->file('HOD_attachment') as $file) {
        //             $name = "Supplier-Site" . '-HOD_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->HOD_attachment = json_encode($files);
        // }

        $files = is_array($request->existing_attach_files3) ? $request->existing_attach_files3 : null;

        if (!empty($request->HOD_attachment)) {
            if ($supplierSite->HOD_attachment) {
                $existingFiles = json_decode($supplierSite->HOD_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('HOD_attachment')) {
                foreach ($request->file('HOD_attachment') as $file) {
                    $name = $request->name . 'HOD_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $supplierSite->HOD_attachment = !empty($files) ? json_encode(array_values($files)) : null; 

        /****************** Supplier Details ********************/
        $supplierSite->supplier_name = $request->supplier_name;
        $supplierSite->supplier_id = $request->supplier_id;        
        $supplierSite->manufacturer_name = $request->manufacturer_name;
        $supplierSite->manufacturer_id = $request->manufacturer_id;
        $supplierSite->vendor_name = $request->vendor_name;
        $supplierSite->vendor_id = $request->vendor_id;
        $supplierSite->contact_person = $request->contact_person;
        $supplierSite->other_contacts = $request->other_contacts;
        $supplierSite->supplier_serivce = $request->supplier_serivce;
        $supplierSite->zone = $request->zone;
        $supplierSite->country = $request->country == 'Select Country' ? null: $request->country;
        $supplierSite->state = $request->state == 'Select State/District' ? null: $request->state;
        $supplierSite->city = $request->city == 'Select City' ? null: $request->city ;
        $supplierSite->address = $request->address;
        $supplierSite->suppplier_web_site = $request->suppplier_web_site;
        $supplierSite->iso_certified_date = $request->iso_certified_date;
        $supplierSite->suppplier_contacts = $request->suppplier_contacts;
        $supplierSite->related_non_conformance = $request->related_non_conformance;
        $supplierSite->suppplier_agreement = $request->suppplier_agreement;
        $supplierSite->regulatory_history = $request->regulatory_history;
        $supplierSite->distribution_sites = $request->distribution_sites;
        $supplierSite->manufacturing_sited = $request->manufacturing_sited;
        $supplierSite->quality_management = $request->quality_management;
        $supplierSite->bussiness_history = $request->bussiness_history;
        $supplierSite->performance_history = $request->performance_history;
        $supplierSite->compliance_risk = $request->compliance_risk;

        /****************** Score Card Content ********************/
        $supplierSite->cost_reduction = $request->cost_reduction;
        $supplierSite->cost_reduction_weight = $request->cost_reduction_weight;        
        $supplierSite->payment_term = $request->payment_term;
        $supplierSite->payment_term_weight = $request->payment_term_weight;
        $supplierSite->lead_time_days = $request->lead_time_days;
        $supplierSite->lead_time_days_weight = $request->lead_time_days_weight;
        $supplierSite->ontime_delivery = $request->ontime_delivery;
        $supplierSite->ontime_delivery_weight = $request->ontime_delivery_weight;
        $supplierSite->supplier_bussiness_planning = $request->supplier_bussiness_planning;
        $supplierSite->supplier_bussiness_planning_weight = $request->supplier_bussiness_planning_weight;
        $supplierSite->rejection_ppm = $request->rejection_ppm;
        $supplierSite->rejection_ppm_weight = $request->rejection_ppm_weight;
        $supplierSite->quality_system = $request->quality_system;
        $supplierSite->quality_system_ranking = $request->quality_system_ranking;
        $supplierSite->car_generated = $request->car_generated;
        $supplierSite->car_generated_weight = $request->car_generated_weight;
        $supplierSite->closure_time = $request->closure_time;
        $supplierSite->closure_time_weight = $request->closure_time_weight;
        $supplierSite->end_user_satisfaction = $request->end_user_satisfaction;
        $supplierSite->end_user_satisfaction_weight = $request->end_user_satisfaction_weight;
        $supplierSite->scorecard_record = $request->scorecard_record;
        $supplierSite->achieved_score = $request->achieved_score;
        $supplierSite->total_available_score = $request->total_available_score;
        $supplierSite->total_score = $request->total_score;

        /****************** QA Reviewer ********************/
        $supplierSite->QA_reviewer_feedback = $request->QA_reviewer_feedback;
        $supplierSite->QA_reviewer_comment = $request->QA_reviewer_comment;

        // if (!empty($request->QA_reviewer_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('QA_reviewer_attachment')) {
        //         foreach ($request->file('QA_reviewer_attachment') as $file) {
        //             $name = "Supplier-Site" . '-QA_reviewer_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->QA_reviewer_attachment = json_encode($files);
        // }

        $files = is_array($request->existing_attach_files8) ? $request->existing_attach_files8 : null;

        if (!empty($request->QA_reviewer_attachment)) {
            if ($supplierSite->QA_reviewer_attachment) {
                $existingFiles = json_decode($supplierSite->QA_reviewer_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('QA_reviewer_attachment')) {
                foreach ($request->file('QA_reviewer_attachment') as $file) {
                    $name = $request->name . 'QA_reviewer_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $supplierSite->QA_reviewer_attachment = !empty($files) ? json_encode(array_values($files)) : null; 


        /****************** Risk Assessment Content ********************/
        $supplierSite->last_audit_date = $request->last_audit_date;
        $supplierSite->next_audit_date = $request->next_audit_date;        
        $supplierSite->audit_frequency = $request->audit_frequency;
        $supplierSite->last_audit_result = $request->last_audit_result;
        $supplierSite->facility_type = $request->facility_type;
        $supplierSite->nature_of_employee = $request->nature_of_employee;
        $supplierSite->technical_support = $request->technical_support;
        $supplierSite->survice_supported = $request->survice_supported;
        $supplierSite->reliability = $request->reliability;
        $supplierSite->revenue = $request->revenue;
        $supplierSite->client_base = $request->client_base;
        $supplierSite->previous_audit_result = $request->previous_audit_result;
        $supplierSite->risk_raw_total = $request->risk_raw_total;
        $supplierSite->risk_median = $request->risk_median;
        $supplierSite->risk_average = $request->risk_average;
        $supplierSite->risk_assessment_total = $request->risk_assessment_total;

        /****************** QA Reviewer ********************/
        $supplierSite->QA_head_comment = $request->QA_head_comment;

        // if (!empty($request->QA_head_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('QA_head_attachment')) {
        //         foreach ($request->file('QA_head_attachment') as $file) {
        //             $name = "Supplier-Site" . '-QA_head_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->QA_head_attachment = json_encode($files);
        // }

        $files = is_array($request->existing_attach_files11) ? $request->existing_attach_files11 : null;

        if (!empty($request->QA_head_attachment)) {
            if ($supplierSite->QA_head_attachment) {
                $existingFiles = json_decode($supplierSite->QA_head_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('QA_head_attachment')) {
                foreach ($request->file('QA_head_attachment') as $file) {
                    $name = $request->name . 'QA_head_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $supplierSite->QA_head_attachment = !empty($files) ? json_encode(array_values($files)) : null; 


        /************ Additional Attchment Code ************/
        // if (!empty($request->iso_certificate_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('iso_certificate_attachment')) {
        //         foreach ($request->file('iso_certificate_attachment') as $file) {
        //             $name = "Supplier-Site" . '-iso_certificate_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->iso_certificate_attachment = json_encode($files);
        // }

        $files = is_array($request->existing_attach_files5) ? $request->existing_attach_files5 : null;

        if (!empty($request->iso_certificate_attachment)) {
            if ($supplierSite->iso_certificate_attachment) {
                $existingFiles = json_decode($supplierSite->iso_certificate_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('iso_certificate_attachment')) {
                foreach ($request->file('iso_certificate_attachment') as $file) {
                    $name = $request->name . 'iso_certificate_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $supplierSite->iso_certificate_attachment = !empty($files) ? json_encode(array_values($files)) : null; 


        // if (!empty($request->gi_additional_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('gi_additional_attachment')) {
        //         foreach ($request->file('gi_additional_attachment') as $file) {
        //             $name = "Supplier-Site" . '-gi_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->gi_additional_attachment = json_encode($files);
        // }

        $files = is_array($request->existing_attach_files2) ? $request->existing_attach_files2 : null;

        if (!empty($request->gi_additional_attachment)) {
            if ($supplierSite->gi_additional_attachment) {
                $existingFiles = json_decode($supplierSite->gi_additional_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('gi_additional_attachment')) {
                foreach ($request->file('gi_additional_attachment') as $file) {
                    $name = $request->name . 'gi_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $supplierSite->gi_additional_attachment = !empty($files) ? json_encode(array_values($files)) : null; 

        // if (!empty($request->hod_additional_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('hod_additional_attachment')) {
        //         foreach ($request->file('hod_additional_attachment') as $file) {
        //             $name = "Supplier-Site" . '-hod_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->hod_additional_attachment = json_encode($files);
        // }

        $files = is_array($request->existing_attach_files4) ? $request->existing_attach_files4 : null;

        if (!empty($request->hod_additional_attachment)) {
            if ($supplierSite->hod_additional_attachment) {
                $existingFiles = json_decode($supplierSite->hod_additional_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('hod_additional_attachment')) {
                foreach ($request->file('hod_additional_attachment') as $file) {
                    $name = $request->name . 'hod_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $supplierSite->hod_additional_attachment =!empty($files) ? json_encode(array_values($files)) : null; 


        

        // if (!empty($request->supplier_detail_additional_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('supplier_detail_additional_attachment')) {
        //         foreach ($request->file('supplier_detail_additional_attachment') as $file) {
        //             $name = "Supplier-Site" . '-supplier_detail_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->supplier_detail_additional_attachment = json_encode($files);
        // }

        $files = is_array($request->existing_attach_files6) ? $request->existing_attach_files6 : null;

        if (!empty($request->supplier_detail_additional_attachment)) {
            if ($supplierSite->supplier_detail_additional_attachment) {
                $existingFiles = json_decode($supplierSite->supplier_detail_additional_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('supplier_detail_additional_attachment')) {
                foreach ($request->file('supplier_detail_additional_attachment') as $file) {
                    $name = $request->name . 'supplier_detail_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $supplierSite->supplier_detail_additional_attachment = !empty($files) ? json_encode(array_values($files)) : null; 


        // if (!empty($request->score_card_additional_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('score_card_additional_attachment')) {
        //         foreach ($request->file('score_card_additional_attachment') as $file) {
        //             $name = "Supplier-Site" . '-score_card_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->score_card_additional_attachment = json_encode($files);
        // }

         $files = is_array($request->existing_attach_files7) ? $request->existing_attach_files7 : null;

        if (!empty($request->score_card_additional_attachment)) {
            if ($supplierSite->score_card_additional_attachment) {
                $existingFiles = json_decode($supplierSite->score_card_additional_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('score_card_additional_attachment')) {
                foreach ($request->file('score_card_additional_attachment') as $file) {
                    $name = $request->name . 'score_card_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $supplierSite->score_card_additional_attachment = !empty($files) ? json_encode(array_values($files)) : null; 


        // if (!empty($request->qa_reviewer_additional_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('qa_reviewer_additional_attachment')) {
        //         foreach ($request->file('qa_reviewer_additional_attachment') as $file) {
        //             $name = "Supplier-Site" . '-qa_reviewer_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->qa_reviewer_additional_attachment = json_encode($files);
        // }

        $files = is_array($request->existing_attach_files9) ? $request->existing_attach_files9 : null;

        if (!empty($request->qa_reviewer_additional_attachment)) {
            if ($supplierSite->qa_reviewer_additional_attachment) {
                $existingFiles = json_decode($supplierSite->qa_reviewer_additional_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('qa_reviewer_additional_attachment')) {
                foreach ($request->file('qa_reviewer_additional_attachment') as $file) {
                    $name = $request->name . 'qa_reviewer_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $supplierSite->qa_reviewer_additional_attachment = !empty($files) ? json_encode(array_values($files)) : null; 


        // if (!empty($request->risk_assessment_additional_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('risk_assessment_additional_attachment')) {
        //         foreach ($request->file('risk_assessment_additional_attachment') as $file) {
        //             $name = "Supplier-Site" . '-risk_assessment_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->risk_assessment_additional_attachment = json_encode($files);
        // }

         $files = is_array($request->existing_attach_files10) ? $request->existing_attach_files10 : null;

        if (!empty($request->risk_assessment_additional_attachment)) {
            if ($supplierSite->risk_assessment_additional_attachment) {
                $existingFiles = json_decode($supplierSite->risk_assessment_additional_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('risk_assessment_additional_attachment')) {
                foreach ($request->file('risk_assessment_additional_attachment') as $file) {
                    $name = $request->name . 'risk_assessment_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $supplierSite->risk_assessment_additional_attachment = !empty($files) ? json_encode(array_values($files)) : null; 


        // if (!empty($request->qa_head_additional_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('qa_head_additional_attachment')) {
        //         foreach ($request->file('qa_head_additional_attachment') as $file) {
        //             $name = "Supplier-Site" . '-qa_head_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $supplierSite->qa_head_additional_attachment = json_encode($files);
        // }

        $files = is_array($request->existing_attach_files12) ? $request->existing_attach_files12 : null;

        if (!empty($request->qa_head_additional_attachment)) {
            if ($supplierSite->qa_head_additional_attachment) {
                $existingFiles = json_decode($supplierSite->qa_head_additional_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('qa_head_additional_attachment')) {
                foreach ($request->file('qa_head_additional_attachment') as $file) {
                    $name = $request->name . 'qa_head_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $supplierSite->qa_head_additional_attachment = !empty($files) ? json_encode(array_values($files)) : null; 

        
        $supplierSite->update();

        $certificationData = SupplierSiteGrid::where(['supplier_site_id' => $supplierSite->id, 'identifier' =>'CertificationData'])->firstOrCreate();
        $certificationData->supplier_site_id = $supplierSite->id;
        $certificationData->identifier = 'CertificationData';
        $certificationData->data = $request->certificationData;
        $certificationData->update();

        if($lastDocument->short_description != $request->short_description){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
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
            if (is_null($lastDocument->short_description) || $lastDocument->short_description === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if($lastDocument->assign_to != $request->assign_to){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Assigned To';
            $history->previous = Helpers::getInitiatorName($lastDocument->assign_to);
            $history->current =  Helpers::getInitiatorName($request->assign_to);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->assign_to) || $lastDocument->assign_to === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if($lastDocument->supplier_person != $request->supplier_person){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Supplier';
            $history->previous = Helpers::getInitiatorName($lastDocument->supplier_person);
            $history->current =  Helpers::getInitiatorName($request->supplier_person);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_person) || $lastDocument->supplier_person === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if($lastDocument->logo_attachment != $supplierSite->logo_attachment){
               $history = new SupplierSiteAuditTrail();
               $history->supplier_site_id = $lastDocument->id;
               $history->activity_type = 'Logo';
               $history->previous = $lastDocument->logo_attachment;
               $history->current = $supplierSite->logo_attachment;
               $history->comment = "Not Applicable";
               $history->user_id = Auth::user()->id;
               $history->user_name = Auth::user()->name;
               $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $lastDocument->status;
               $history->change_to =   "Not Applicable";
               $history->change_from = $lastDocument->status;
               if (is_null($lastDocument->logo_attachment) || $lastDocument->logo_attachment === '') {
                 $history->action_name = 'New';
               }else {
                 $history->action_name = 'Update';
               }
              $history->save();
        }
        
        if($lastDocument->supplier_contact_person != $request->supplier_contact_person){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Contact Person';
            $history->previous = Helpers::getInitiatorName($lastDocument->supplier_contact_person);
            $history->current = Helpers::getInitiatorName($request->supplier_contact_person);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_contact_person) || $lastDocument->supplier_contact_person === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->supplier_products != $request->supplier_products){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Suppliers Products';
            $history->previous = $lastDocument->supplier_products;
            $history->current = $request->supplier_products;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_products) || $lastDocument->supplier_products === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->description != $request->description){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
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
            if (is_null($lastDocument->description) || $lastDocument->description === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->supplier_type != $request->supplier_type){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Type';
            $history->previous = $lastDocument->supplier_type;
            $history->current = $request->supplier_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_type) || $lastDocument->supplier_type === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->supplier_sub_type != $request->supplier_sub_type){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Sub Type';
            $history->previous = $lastDocument->supplier_sub_type;
            $history->current = $request->supplier_sub_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_sub_type) || $lastDocument->supplier_sub_type === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->supplier_other_type != $request->supplier_other_type){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Other Type';
            $history->previous = $lastDocument->supplier_other_type;
            $history->current = $request->supplier_other_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_other_type) || $lastDocument->supplier_other_type === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->supply_from != $request->supply_from){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Supply From';
            $history->previous = $lastDocument->supply_from;
            $history->current = $request->supply_from;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supply_from) || $lastDocument->supply_from === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->supply_to != $request->supply_to){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Supply To';
            $history->previous = $lastDocument->supply_to;
            $history->current = $request->supply_to;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supply_to) || $lastDocument->supply_to === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->supplier_website != $request->supplier_website){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Supplier Website';
            $history->previous = $lastDocument->supplier_website;
            $history->current = $request->supplier_website;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_website) || $lastDocument->supplier_website === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if($lastDocument->supplier_web_search != $request->supplier_web_search){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Web Search';
            $history->previous = $lastDocument->supplier_web_search;
            $history->current = $request->supplier_web_search;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_web_search) || $lastDocument->supplier_web_search === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        
        if($lastDocument->supplier_attachment != $supplierSite->supplier_attachment){
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'File Attachment';
            $history->previous = $lastDocument->supplier_attachment;
            $history->current = $supplierSite->supplier_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_attachment) || $lastDocument->supplier_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        
        if($lastDocument->gi_additional_attachment != $supplierSite->gi_additional_attachment){    
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Additional Attachment';
            $history->previous = $lastDocument->gi_additional_attachment;
            $history->current = $supplierSite->gi_additional_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->gi_additional_attachment) || $lastDocument->gi_additional_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        
        if($lastDocument->related_url != $request->related_url){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Related URLs';
            $history->previous = $lastDocument->related_url;
            $history->current = $request->related_url;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->related_url) || $lastDocument->related_url === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->related_quality_events != $request->related_quality_events){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Related Quality Events';
            $history->previous = $lastDocument->related_quality_events;
            $history->current = $request->related_quality_events;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->related_quality_events) || $lastDocument->related_quality_events === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->HOD_feedback != $request->HOD_feedback){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'HOD Feedback';
            $history->previous = $lastDocument->HOD_feedback;
            $history->current = $request->HOD_feedback;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->HOD_feedback) || $lastDocument->HOD_feedback === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            
            $history->save();
        }
        if($lastDocument->HOD_comment != $request->HOD_comment){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'HOD Comment';
            $history->previous = $lastDocument->HOD_comment;
            $history->current = $request->HOD_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->HOD_comment) || $lastDocument->HOD_comment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if($lastDocument->HOD_attachment != $supplierSite->HOD_attachment){
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'HOD Attachment';
            $history->previous = $lastDocument->HOD_attachment;
            $history->current = $supplierSite->HOD_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->HOD_attachment) || $lastDocument->HOD_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        
        if($lastDocument->hod_additional_attachment != $supplierSite->hod_additional_attachment){
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'HOD Additional Attachment';
            $history->previous = $lastDocument->hod_additional_attachment;
            $history->current = $supplierSite->hod_additional_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->hod_additional_attachment) || $lastDocument->hod_additional_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        
        if($lastDocument->supplier_name != $request->supplier_name){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Supplier';
            $history->previous = $lastDocument->supplier_name;
            $history->current = $request->supplier_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_name) || $lastDocument->supplier_name === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->supplier_id != $supplierSite->supplier_id){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Supplier ID';
            $history->previous = $lastDocument->supplier_id;
            $history->current = $supplierSite->supplier_id;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_id) || $lastDocument->supplier_id === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->manufacturer_name != $request->manufacturer_name){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Manufacturer';
            $history->previous = $lastDocument->manufacturer_name;
            $history->current = $request->manufacturer_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->manufacturer_name) || $lastDocument->manufacturer_name === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->manufacturer_id != $request->manufacturer_id){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Manufacturer ID';
            $history->previous = $lastDocument->manufacturer_id;
            $history->current = $request->manufacturer_id;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->manufacturer_id) || $lastDocument->manufacturer_id === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->vendor_name != $request->vendor_name){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Vendor';
            $history->previous = $lastDocument->vendor_name;
            $history->current = $request->vendor_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->vendor_name) || $lastDocument->vendor_name === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->vendor_id != $supplierSite->vendor_id){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Vendor ID';
            $history->previous = $lastDocument->vendor_id;
            $history->current = $supplierSite->vendor_id;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->vendor_id) || $lastDocument->vendor_id === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->contact_person != $request->contact_person){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Contact Person';
            $history->previous = $lastDocument->contact_person;
            $history->current = $request->contact_person;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->contact_person) || $lastDocument->contact_person === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->other_contacts != $request->other_contacts){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Other Contacts';
            $history->previous = $lastDocument->other_contacts;
            $history->current = $request->other_contacts;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->other_contacts) || $lastDocument->other_contacts === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->supplier_serivce != $request->supplier_serivce){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Supplier Services';
            $history->previous = $lastDocument->supplier_serivce;
            $history->current = $request->supplier_serivce;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_serivce) || $lastDocument->supplier_serivce === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->zone != $request->zone){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Zone';
            $history->previous = $lastDocument->zone;
            $history->current = $request->zone;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->zone) || $lastDocument->zone === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->country != $request->country){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Country';
            $history->previous = $lastDocument->country;
            $history->current = $request->country;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->country) || $lastDocument->country === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->state != $request->state){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'State';
            $history->previous = $lastDocument->state;
            $history->current = $request->state;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->state) || $lastDocument->state === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->city != $request->city){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'City';
            $history->previous = $lastDocument->city;
            $history->current = $request->city;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->city) || $lastDocument->city === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->address != $request->address){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Address';
            $history->previous = $lastDocument->address;
            $history->current = $request->address;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->address) || $lastDocument->address === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->suppplier_web_site != $request->suppplier_web_site){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Supplier Website';
            $history->previous = $lastDocument->suppplier_web_site;
            $history->current = $request->suppplier_web_site;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->suppplier_web_site) || $lastDocument->suppplier_web_site === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->iso_certified_date != $request->iso_certified_date){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'ISO Certification Date';
            $history->previous = Helpers::getdateFormat($lastDocument->iso_certified_date);
            $history->current =  Helpers::getdateFormat($request->iso_certified_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->iso_certified_date) || $lastDocument->iso_certified_date === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

         if($lastDocument->iso_certificate_attachment != $supplierSite->iso_certificate_attachment){
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'ISO Certificate Attachment';
            $history->previous = $lastDocument->iso_certificate_attachment;
            $history->current = $supplierSite->iso_certificate_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->iso_certificate_attachment) || $lastDocument->iso_certificate_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if($lastDocument->suppplier_contacts != $request->suppplier_contacts){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Contracts';
            $history->previous = $lastDocument->suppplier_contacts;
            $history->current = $request->suppplier_contacts;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->suppplier_contacts) || $lastDocument->suppplier_contacts === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->related_non_conformance != $request->related_non_conformance){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Related Non Conformances';
            $history->previous = $lastDocument->related_non_conformance;
            $history->current = $request->related_non_conformance;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->related_non_conformance) || $lastDocument->related_non_conformance === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->suppplier_agreement != $request->suppplier_agreement){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Supplier Contracts/Agreements';
            $history->previous = $lastDocument->suppplier_agreement;
            $history->current = $request->suppplier_agreement;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->suppplier_agreement) || $lastDocument->suppplier_agreement === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->regulatory_history != $request->regulatory_history){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Regulatory History';
            $history->previous = $lastDocument->regulatory_history;
            $history->current = $request->regulatory_history;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->regulatory_history) || $lastDocument->regulatory_history === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->distribution_sites != $request->distribution_sites){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Distribution Sites';
            $history->previous = $lastDocument->distribution_sites;
            $history->current = $request->distribution_sites;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->distribution_sites) || $lastDocument->distribution_sites === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->manufacturing_sited != $request->manufacturing_sited){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Manufacturing Sites';
            $history->previous = $lastDocument->manufacturing_sited;
            $history->current = $request->manufacturing_sited;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            
            if (is_null($lastDocument->manufacturing_sited) || $lastDocument->manufacturing_sited === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->quality_management != $request->quality_management){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Quality Management';
            $history->previous = $lastDocument->quality_management;
            $history->current = $request->quality_management;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            
            if (is_null($lastDocument->quality_management) || $lastDocument->quality_management === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            
            $history->save();
        }
        if($lastDocument->bussiness_history != $request->bussiness_history){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Business History';
            $history->previous = $lastDocument->bussiness_history;
            $history->current = $request->bussiness_history;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->bussiness_history) || $lastDocument->bussiness_history === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->performance_history != $request->performance_history){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Performance History';
            $history->previous = $lastDocument->performance_history;
            $history->current = $request->performance_history;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->performance_history) || $lastDocument->performance_history === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->compliance_risk != $request->compliance_risk){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Compliance Risk';
            $history->previous = $lastDocument->compliance_risk;
            $history->current = $request->compliance_risk;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            
            if (is_null($lastDocument->compliance_risk) || $lastDocument->compliance_risk === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        
        if($lastDocument->supplier_detail_additional_attachment != $supplierSite->supplier_detail_additional_attachment){
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Supplier Details Additional Attachment';
            $history->previous = $lastDocument->supplier_detail_additional_attachment;
            $history->current = $supplierSite->supplier_detail_additional_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_detail_additional_attachment) || $lastDocument->supplier_detail_additional_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->cost_reduction != $request->cost_reduction){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Cost Reduction';
            $history->previous = $lastDocument->cost_reduction;
            $history->current = $request->cost_reduction;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            
            if (is_null($lastDocument->cost_reduction) || $lastDocument->cost_reduction === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->cost_reduction_weight != $request->cost_reduction_weight){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Cost Reduction Weight';
            $history->previous = $lastDocument->cost_reduction_weight;
            $history->current = $request->cost_reduction_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->cost_reduction_weight) || $lastDocument->cost_reduction_weight === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->payment_term != $request->payment_term){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Payment Terms';
            $history->previous = $lastDocument->payment_term;
            $history->current = $request->payment_term;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->payment_term) || $lastDocument->payment_term === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->payment_term_weight != $request->payment_term_weight){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Payment Terms Weight';
            $history->previous = $lastDocument->payment_term_weight;
            $history->current = $request->payment_term_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->payment_term_weight) || $lastDocument->payment_term_weight === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->lead_time_days != $request->lead_time_days){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Lead Time Days';
            $history->previous = $lastDocument->lead_time_days;
            $history->current = $request->lead_time_days;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->lead_time_days) || $lastDocument->lead_time_day === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->lead_time_days_weight != $request->lead_time_days_weight){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Lead Time Days Weight';
            $history->previous = $lastDocument->lead_time_days_weight;
            $history->current = $request->lead_time_days_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->lead_time_days_weight) || $lastDocument->lead_time_days_weight === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->ontime_delivery != $request->ontime_delivery){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'On-Time Delivery';
            $history->previous = $lastDocument->ontime_delivery;
            $history->current = $request->ontime_delivery;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            
            if (is_null($lastDocument->ontime_delivery) || $lastDocument->ontime_delivery === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->ontime_delivery_weight != $request->ontime_delivery_weight){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'On-Time Delivery Weight';
            $history->previous = $lastDocument->ontime_delivery_weight;
            $history->current = $request->ontime_delivery_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->ontime_delivery_weight) || $lastDocument->ontime_delivery_weight === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->supplier_bussiness_planning != $request->supplier_bussiness_planning){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Supplier Business Planning';
            $history->previous = $lastDocument->supplier_bussiness_planning;
            $history->current = $request->supplier_bussiness_planning;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->supplier_bussiness_planning) || $lastDocument->supplier_bussiness_planning === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->supplier_bussiness_planning_weight != $request->supplier_bussiness_planning_weight){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Supplier Business Weight';
            $history->previous = $lastDocument->supplier_bussiness_planning_weight;
            $history->current = $request->supplier_bussiness_planning_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if(is_null($lastDocument->supplier_bussiness_planning_weight) || $lastDocument->supplier_bussiness_planning_weight === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->rejection_ppm != $request->rejection_ppm){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Rejection in PPM';
            $history->previous = $lastDocument->rejection_ppm;
            $history->current = $request->rejection_ppm;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->rejection_ppm) || $lastDocument->rejection_ppm === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->rejection_ppm_weight != $request->rejection_ppm_weight){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Rejection in PPM Weight';
            $history->previous = $lastDocument->rejection_ppm_weight;
            $history->current = $request->rejection_ppm_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            
            if (is_null($lastDocument->rejection_ppm_weight) || $lastDocument->rejection_ppm_weight === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->quality_system != $request->quality_system){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Quality Systems';
            $history->previous = $lastDocument->quality_system;
            $history->current = $request->quality_system;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->quality_system) || $lastDocument->quality_system === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->quality_system_ranking != $request->quality_system_ranking){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Quality Systems Weight';
            $history->previous = $lastDocument->quality_system_ranking;
            $history->current = $request->quality_system_ranking;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->quality_system_ranking) || $lastDocument->quality_system_ranking === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->car_generated != $request->car_generated){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = '# of CARs generated';
            $history->previous = $lastDocument->car_generated;
            $history->current = $request->car_generated;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->car_generated) || $lastDocument->car_generated === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->car_generated_weight != $request->car_generated_weight){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = '# of CARs generated Weight';
            $history->previous = $lastDocument->car_generated_weight;
            $history->current = $request->car_generated_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->car_generated_weight) || $lastDocument->car_generated_weight === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->closure_time != $request->closure_time){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'CAR Closure Time';
            $history->previous = $lastDocument->closure_time;
            $history->current = $request->closure_time;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->closure_time) || $lastDocument->closure_time === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->closure_time_weight != $request->closure_time_weight){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'CAR Closure Time Weight';
            $history->previous = $lastDocument->closure_time_weight;
            $history->current = $request->closure_time_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->closure_time_weight) || $lastDocument->closure_time_weight === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->end_user_satisfaction != $request->end_user_satisfaction){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'End-User Satisfaction';
            $history->previous = $lastDocument->end_user_satisfaction;
            $history->current = $request->end_user_satisfaction;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->end_user_satisfaction) || $lastDocument->end_user_satisfaction === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->end_user_satisfaction_weight != $request->end_user_satisfaction_weight){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'End-User Satisfaction Weight';
            $history->previous = $lastDocument->end_user_satisfaction_weight;
            $history->current = $request->end_user_satisfaction_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->end_user_satisfaction_weight) || $lastDocument->end_user_satisfaction_weight === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        
        if($lastDocument->score_card_additional_attachment != $supplierSite->score_card_additional_attachment){
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Score Card Additional Attachment';
            $history->previous = $lastDocument->score_card_additional_attachment;
            $history->current = $supplierSite->score_card_additional_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->score_card_additional_attachment) || $lastDocument->score_card_additional_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->QA_reviewer_feedback != $request->QA_reviewer_feedback){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'QA Reviewer Feedback';
            $history->previous = $lastDocument->QA_reviewer_feedback;
            $history->current = $request->QA_reviewer_feedback;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->QA_reviewer_feedback) || $lastDocument->QA_reviewer_feedback === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->QA_reviewer_comment != $request->QA_reviewer_comment){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'QA Reviewer Comment';
            $history->previous = $lastDocument->QA_reviewer_comment;
            $history->current = $request->QA_reviewer_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->QA_reviewer_comment) || $lastDocument->QA_reviewer_comment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        
        if($lastDocument->QA_reviewer_attachment != $supplierSite->QA_reviewer_attachment){
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'QA Reviewer Attachment';
            $history->previous = $lastDocument->QA_reviewer_attachment;
            $history->current = $supplierSite->QA_reviewer_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->QA_reviewer_attachment) || $lastDocument->QA_reviewer_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        
        if($lastDocument->qa_reviewer_additional_attachment != $supplierSite->qa_reviewer_additional_attachment){
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'QA Reviewer Additional Attachment';
            $history->previous = $lastDocument->qa_reviewer_additional_attachment;
            $history->current = $supplierSite->qa_reviewer_additional_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->qa_reviewer_additional_attachment) || $lastDocument->qa_reviewer_additional_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->last_audit_date != $request->last_audit_date){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Last Audit Date';
            $history->previous = Helpers::getdateFormat($lastDocument->last_audit_date);
            $history->current = Helpers::getdateFormat($request->last_audit_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->last_audit_date) || $lastDocument->last_audit_date === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->next_audit_date != $request->next_audit_date){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Next Audit Date';
            $history->previous = Helpers::getdateFormat($lastDocument->next_audit_date);
            $history->current = Helpers::getdateFormat($request->next_audit_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->next_audit_date) || $lastDocument->next_audit_date === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->audit_frequency != $request->audit_frequency){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Audit Frequency';
            $history->previous = $lastDocument->audit_frequency;
            $history->current = $request->audit_frequency;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->audit_frequency) || $lastDocument->audit_frequency === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->last_audit_result != $request->last_audit_result){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Last Audit Result';
            $history->previous = $lastDocument->last_audit_result;
            $history->current = $request->last_audit_result;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->last_audit_result) || $lastDocument->last_audit_result === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->facility_type != $request->facility_type){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Facility Type';
            $history->previous = $lastDocument->facility_type;
            $history->current = $request->facility_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->facility_type) || $lastDocument->facility_type === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->nature_of_employee != $request->nature_of_employee){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Number of Employees';
            $history->previous = $lastDocument->nature_of_employee;
            $history->current = $request->nature_of_employee;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->nature_of_employee) || $lastDocument->nature_of_employee === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->technical_support != $request->technical_support){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Access to Technical Support';
            $history->previous = $lastDocument->technical_support;
            $history->current = $request->technical_support;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->technical_support) || $lastDocument->technical_support === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->survice_supported != $request->survice_supported){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Services Supported';
            $history->previous = $lastDocument->survice_supported;
            $history->current = $request->survice_supported;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->survice_supported) || $lastDocument->survice_supported === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->reliability != $request->reliability){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Reliability';
            $history->previous = $lastDocument->reliability;
            $history->current = $request->reliability;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->reliability) || $lastDocument->reliability === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->revenue != $request->revenue){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Revenue';
            $history->previous = $lastDocument->revenue;
            $history->current = $request->revenue;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->revenue) || $lastDocument->revenue === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->client_base != $request->client_base){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Client Base';
            $history->previous = $lastDocument->client_base;
            $history->current = $request->client_base;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->client_base) || $lastDocument->client_base === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->previous_audit_result != $request->previous_audit_result){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Previous Audit Results';
            $history->previous = $lastDocument->previous_audit_result;
            $history->current = $request->previous_audit_result;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->previous_audit_result) || $lastDocument->previous_audit_result === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        
        if($lastDocument->risk_assessment_additional_attachment != $supplierSite->risk_assessment_additional_attachment){
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'Risk Assessment Additional Attachment';
            $history->previous = $lastDocument->risk_assessment_additional_attachment;
            $history->current = $supplierSite->risk_assessment_additional_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->risk_assessment_additional_attachment) || $lastDocument->risk_assessment_additional_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        if($lastDocument->QA_head_comment != $request->QA_head_comment){
            $history = new SupplierSiteAuditTrail;
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'QA Head Comment';
            $history->previous = $lastDocument->QA_head_comment;
            $history->current = $request->QA_head_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->QA_head_comment) || $lastDocument->QA_head_comment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        
        if($lastDocument->QA_head_attachment != $supplierSite->QA_head_attachment){
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'QA Head Attachment';
            $history->previous = $lastDocument->QA_head_attachment;
            $history->current = $supplierSite->QA_head_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->QA_head_attachment) || $lastDocument->QA_head_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        
        if($lastDocument->qa_head_additional_attachment != $supplierSite->qa_head_additional_attachment){
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $lastDocument->id;
            $history->activity_type = 'QA Head Additional Attachment';
            $history->previous = $lastDocument->qa_head_additional_attachment;
            $history->current = $supplierSite->qa_head_additional_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            if (is_null($lastDocument->qa_head_additional_attachment) || $lastDocument->qa_head_additional_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }
        
        toastr()->success("Record is updated Successfully");
        return redirect()->back();
    }

    public function singleReport(Request $request, $id){
        $data = SupplierSite::find($id);
        $supplierData = SupplierSite::find($id);
       
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $gridData = SupplierSiteGrid::where(['supplier_site_id' => $id, 'identifier' => "CertificationData"])->first();
            $certificationData = json_decode($gridData->data, true);
            // $gridData = SupplierSiteGrid::where('supplier_site_id', $data->id)->first();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.supplier-site.suppliersite-single-report', compact(
                'data',
                'gridData',
                'certificationData',
                'supplierData',
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
                $width / 2.5,
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

    public function auditTrail(Request $request, $id){
        $audit = SupplierSiteAuditTrail::where('supplier_site_id', $id)->orderByDESC('id')->paginate(5);
        $today = Carbon::now()->format('d-m-y');
        $document = SupplierSite::where('id', $id)->first();
        $document->originator = User::where('id', $document->initiator_id)->value('name');

        return view('frontend.supplier-site.suppliersite-audit-trail', compact('audit', 'document', 'today'));
    }

    public function auditTrailPdf(Request $request, $id){
        $doc = SupplierSite::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = SupplierSiteAuditTrail::where('supplier_site_id', $doc->id)->orderByDesc('id')->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.supplier-site.suppliersite-audit-trail-pdf', compact('data', 'doc'))
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

    public function supplierSendStage(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $supplierSite = SupplierSite::find($id);
            $lastDocument = SupplierSite::find($id);
            if ($supplierSite->stage == 1) {
                    $supplierSite->stage = "2";
                    $supplierSite->status = "Pending Qualification";
                    $supplierSite->submitted_by = Auth::user()->name;
                    $supplierSite->submitted_on = Carbon::now()->format('d-M-Y H:i A');
                    $supplierSite->submitted_comment = $request->comments;

                    $history = new SupplierSiteAuditTrail();
                    $history->supplier_site_id = $id;
                    // $history->activity_type = 'Activity Log';

                    $history->activity_type = 'Submit Supplier Details By, Submit Supplier Details On';
                    if (is_null($lastDocument->submitted_by) || $lastDocument->submitted_by === '') {
                           $history->previous = "";
                        } else {
                           $history->previous = $lastDocument->submitted_by . ' , ' . $lastDocument->submitted_on;
                      }
                    
                    $history->current = $supplierSite->submitted_by . ' , ' . $supplierSite->submitted_on;

                    
                    // $history->previous = "";
                    $history->action = 'Submit Supplier Details';
                    // $history->current = "Not Applicable";
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Pending Qualification";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';

                    if (is_null($lastDocument->submitted_by) || $lastDocument->submitted_by === '')
                    {
                         $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    
                    $history->save();
                    //  $list = Helpers::getHodUserList();
                    //     foreach ($list as $u) {
                    //         if($u->q_m_s_divisions_id == $supplierSite->division_id){
                    //             $email = Helpers::getInitiatorEmail($u->user_id);
                    //              if ($email !== null) {
                    //               Mail::send(
                    //                   'mail.view-mail',
                    //                    ['data' => $supplierSite],
                    //                 function ($message) use ($email) {
                    //                     $message->to($email)
                    //                         ->subject("Document is Send By".Auth::user()->name);
                    //                 }
                    //               );
                    //             }
                    //      }
                    //   }
                    $supplierSite->update();

                    toastr()->success('Document Sent');
                    return back();
            }
            if ($supplierSite->stage == 2) {
                    $supplierSite->stage = "3";
                    $supplierSite->status = "Pending Supplier Audit";
                    $supplierSite->pending_qualification_by = Auth::user()->name;
                    $supplierSite->pending_qualification_on = Carbon::now()->format('d-M-Y H:i A');
                    $supplierSite->pending_qualification_comment = $request->comments;

                    $history = new SupplierSiteAuditTrail();
                    $history->supplier_site_id = $id;
                    // $history->activity_type = 'Activity Log';
                    // $history->previous = "";
                    $history->activity_type = 'Qualification Complete By, Qualification Complete On';
                    if (is_null($lastDocument->pending_qualification_by) || $lastDocument->pending_qualification_by === '') {
                           $history->previous = "";
                        } else {
                           $history->previous = $lastDocument->pending_qualification_by . ' , ' . $lastDocument->pending_qualification_on;
                      }
                    
                    $history->current = $supplierSite->pending_qualification_by . ' , ' . $supplierSite->pending_qualification_on;

                    $history->action = 'Qualification Complete';
                    // $history->current = "Not Applicable";
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Pending Supplier Audit";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
                    
                    if (is_null($lastDocument->pending_qualification_by) || $lastDocument->pending_qualification_by === '')
                    {
                         $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }

                    
                    $history->save();
                     $list = Helpers::getSupplierAuditorDepartmentList($supplierSite->division_id);
                        foreach ($list as $u) {
                            // if($u->q_m_s_divisions_id == $supplierSite->division_id){
                                $email = Helpers::getInitiatorEmail($u->user_id);
                                 if ($email !== null) {
                                 try {
                                  Mail::send(
                                      'mail.view-mail',
                                       ['data' => $supplierSite],
                                    function ($message) use ($email) {
                                        $message->to($email)
                                            ->subject("Document is Sent By".Auth::user()->name);
                                    }
                                  );
                                  } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                  }
                                }
                        //  }
                      }
                    $supplierSite->update();
                    
                    toastr()->success('Document Sent');
                    return back();
            }
            if ($supplierSite->stage == 3) {
                $supplierSite->stage = "4";
                $supplierSite->status = "Pending Rejction";
                $supplierSite->pending_supplier_audit_by = Auth::user()->name;
                $supplierSite->pending_supplier_audit_on = Carbon::now()->format('d-M-Y H:i A');
                $supplierSite->pending_supplier_audit_comment = $request->comments;

                $history = new SupplierSiteAuditTrail();
                    $history->supplier_site_id = $id;
                    // $history->activity_type = 'Activity Log';
                    // $history->previous = "";
                    
                    $history->activity_type = 'Audit Failed By, Audit Failed On';
                    if (is_null($lastDocument->pending_supplier_audit_by) || $lastDocument->pending_supplier_audit_by === '') {
                           $history->previous = "";
                        } else {
                           $history->previous = $lastDocument->pending_supplier_audit_by . ' , ' . $lastDocument->pending_supplier_audit_on;
                      }
                    
                    $history->current = $supplierSite->pending_supplier_audit_by . ' , ' . $supplierSite->pending_supplier_audit_on;

                    
                    $history->action = 'Audit Failed';
                    // $history->current = "Not Applicable";
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Pending Rejction";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';

                    if (is_null($lastDocument->pending_supplier_audit_by) || $lastDocument->pending_supplier_audit_by === '')
                    {
                         $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }

                    $history->save();
                 $list = Helpers::getSupplierContactDepartmentList($supplierSite->division_id);
                    foreach ($list as $u) {
                        // if($u->q_m_s_divisions_id == $supplierSite->division_id){
                            $email = Helpers::getInitiatorEmail($u->user_id);
                            if ($email !== null) {
                            try {
                              Mail::send(
                                  'mail.view-mail',
                                   ['data' => $supplierSite],
                                function ($message) use ($email) {
                                    $message->to($email)
                                        ->subject("Document is Sent By".Auth::user()->name);
                                }
                               );
                            } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            }
                    //  }
                  }
                $supplierSite->update();

                toastr()->success('Document Sent');
                return back();
            }
            if ($supplierSite->stage == 4) {
                $supplierSite->stage = "6";
                $supplierSite->status = "Obsolete";
                $supplierSite->pending_rejection_by = Auth::user()->name;
                $supplierSite->pending_rejection_on = Carbon::now()->format('d-M-Y H:i A');
                $supplierSite->pending_rejection_comment = $request->comments;

                $history = new SupplierSiteAuditTrail();
                    $history->supplier_site_id = $id;
                    // $history->activity_type = 'Activity Log';
                    // $history->previous = "";

                    $history->activity_type = 'Supplier Obsolete By, Supplier Obsolete On';
                    if (is_null($lastDocument->pending_rejection_by) || $lastDocument->pending_rejection_by === '') {
                           $history->previous = "";
                        } else {
                           $history->previous = $lastDocument->pending_rejection_by . ' , ' . $lastDocument->pending_rejection_on;
                      }
                    
                    $history->current = $supplierSite->pending_rejection_by . ' , ' . $supplierSite->pending_rejection_on;
                    $history->action = 'Supplier Obsolete';
                    // $history->current = $supplierSite->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Obsolete";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';

                    if (is_null($lastDocument->pending_rejection_by) || $lastDocument->pending_rejection_by === '')
                    {
                         $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    
                    $history->save();
                    
                    $list = Helpers::getSupplierAuditorDepartmentList($supplierSite->division_id);
                    foreach ($list as $u) {
                        // if($u->q_m_s_divisions_id == $supplierSite->division_id){
                            $email = Helpers::getInitiatorEmail($u->user_id);
                            if ($email !== null) {
                            try {
                              Mail::send(
                                  'mail.view-mail',
                                   ['data' => $supplierSite],
                                function ($message) use ($email) {
                                    $message->to($email)
                                        ->subject("Document is Sent By".Auth::user()->name);
                                }
                               );
                            } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            }
                    //  }
                  }
                  
                    $list = Helpers::getInitiatorUserList($supplierSite->division_id);
                    foreach ($list as $u) {
                        // if($u->q_m_s_divisions_id == $supplierSite->division_id){
                            $email = Helpers::getInitiatorEmail($u->user_id);
                            if ($email !== null) {
                            try {
                              Mail::send(
                                  'mail.view-mail',
                                   ['data' => $supplierSite],
                                function ($message) use ($email) {
                                    $message->to($email)
                                        ->subject("Document is Sent By".Auth::user()->name);
                                }
                               );
                            } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            }
                    //  }
                  }
                $supplierSite->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function sendToSupplierApproved(Request $request, $id){
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $supplierSite = SupplierSite::find($id);
            $lastDocument = SupplierSite::find($id);

            $supplierSite->stage = "5";
            $supplierSite->status = "Supplier Approved";
            $supplierSite->supplier_approved_by = Auth::user()->name;
            $supplierSite->supplier_approved_on = Carbon::now()->format('d-M-Y H:i A');
            $supplierSite->supplier_approved_comment = $request->comments;
        
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $id;
            // $history->activity_type = 'Activity Log';
            // $history->previous = "";

            $history->activity_type = 'Audit Passed By, Audit Passed On';
                    if (is_null($lastDocument->supplier_approved_by) || $lastDocument->supplier_approved_by === '') {
                           $history->previous = "";
                        } else {
                           $history->previous = $lastDocument->supplier_approved_by . ' , ' . $lastDocument->supplier_approved_on;
                      }
                    
            $history->current = $supplierSite->supplier_approved_by . ' , ' . $supplierSite->supplier_approved_on;
            
            $history->action = 'Audit Passed';
            // $history->current = "";
            $history->comment = $request->comments;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Supplier Approved";
            $history->change_from = $lastDocument->status;
            $history->stage = 'Plan Proposed';
            if (is_null($lastDocument->supplier_approved_by) || $lastDocument->supplier_approved_by === '')
            {
                 $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
                            
            $history->save();
            
            $list = Helpers::getSupplierContactDepartmentList($supplierSite->division_id);
            foreach ($list as $u) {
                // if($u->q_m_s_divisions_id == $supplierSite->division_id){
                    $email = Helpers::getInitiatorEmail($u->user_id);
                    if ($email !== null) {
                    try {
                      Mail::send(
                          'mail.view-mail',
                           ['data' => $supplierSite],
                        function ($message) use ($email) {
                            $message->to($email)
                                ->subject("Document is Sent By".Auth::user()->name);
                        }
                       );
                    } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
            //  }
          }
          
            $supplierSite->update();
            toastr()->success('Document Sent');
            return back();

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function supplierApprovedToObselete(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $supplierSite = SupplierSite::find($id);
            $lastDocument = SupplierSite::find($id);

            $supplierSite->stage = "6";
            $supplierSite->status = "Obsolete";
            $supplierSite->supplier_approved_to_obselete_by = Auth::user()->name;
            $supplierSite->supplier_approved_to_obselete_on = Carbon::now()->format('d-M-Y H:i A');
            $supplierSite->supplier_approved_to_obselete_comment = $request->comments;
        
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $id;
            // $history->activity_type = 'Activity Log';
            // $history->previous = "";

            $history->activity_type = 'Supplier Obsolete By, Supplier Obsolete On';
                    if (is_null($lastDocument->supplier_approved_to_obselete_by) || $lastDocument->supplier_approved_to_obselete_by === '') {
                           $history->previous = "";
                        } else {
                           $history->previous = $lastDocument->supplier_approved_to_obselete_by . ' , ' . $lastDocument->supplier_approved_to_obselete_on;
                      }
                    
            $history->current = $supplierSite->supplier_approved_to_obselete_by . ' , ' . $supplierSite->supplier_approved_to_obselete_on;
            
            $history->action = 'Supplier Obsolete';
            // $history->current = "";
            $history->comment = $request->comments;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Obsolete";
            $history->change_from = $lastDocument->status;
            $history->stage = 'Plan Proposed'; 
            
            if (is_null($lastDocument->supplier_approved_to_obselete_by) || $lastDocument->supplier_approved_to_obselete_by === '')
            {
                 $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
            
            $list = Helpers::getInitiatorUserList($supplierSite->division_id);
            foreach ($list as $u) {
                // if($u->q_m_s_divisions_id == $supplierSite->division_id){
                    $email = Helpers::getInitiatorEmail($u->user_id);
                    if ($email !== null) {
                    try {
                      Mail::send(
                          'mail.view-mail',
                           ['data' => $supplierSite],
                        function ($message) use ($email) {
                            $message->to($email)
                                ->subject("Document is Sent By".Auth::user()->name);
                        }
                       );
                    } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
            //  }
          }

          $list = Helpers::getSupplierAuditorDepartmentList($supplierSite->division_id);
            foreach ($list as $u) {
                // if($u->q_m_s_divisions_id == $supplierSite->division_id){
                    $email = Helpers::getInitiatorEmail($u->user_id);
                    if ($email !== null) {
                    try {
                      Mail::send(
                          'mail.view-mail',
                           ['data' => $supplierSite],
                        function ($message) use ($email) {
                            $message->to($email)
                                ->subject("Document is Sent By".Auth::user()->name);
                        }
                       );
                    } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
            //  }
          }
          
            $supplierSite->update();
            toastr()->success('Document Sent');
            return back();

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function cancelDocument(Request $request, $id){
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $supplierSite = SupplierSite::find($id);
            $lastDocument = SupplierSite::find($id);

            $supplierSite->stage = "0";
            $supplierSite->status = "Close - Cancelled";
            $supplierSite->cancelled_by = Auth::user()->name;
            $supplierSite->cancelled_on = Carbon::now()->format('d-M-Y H:i A');
            $supplierSite->cancelled_comment = $request->comments;
        
            $history = new SupplierSiteAuditTrail();
            $history->supplier_site_id = $id;
            // $history->activity_type = 'Activity Log';
            // $history->previous = "";

            $history->activity_type = 'Cancel By, Cancel On';
                    if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by === '') {
                           $history->previous = "";
                        } else {
                           $history->previous = $lastDocument->cancelled_by . ' , ' . $lastDocument->cancelled_on;
                      }
                    
            $history->current = $supplierSite->cancelled_by . ' , ' . $supplierSite->cancelled_on;
            
            $history->action = 'Cancel';
            // $history->current = "";
            $history->comment = $request->comments;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =  "Close - Cancelled";
            $history->change_from = $lastDocument->status; 
            
            if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by === '')
            {
                 $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            
            $history->save();
            // $supplierSite->update();

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

                $list = Helpers::getSupplierAuditorDepartmentList($supplierSite->division_id);
                        foreach ($list as $u) {
                            // if($u->q_m_s_divisions_id == $supplierSite->division_id){
                                $email = Helpers::getInitiatorEmail($u->user_id);
                                 if ($email !== null) {
                                 try {
                                  Mail::send(
                                      'mail.view-mail',
                                       ['data' => $supplierSite],
                                    function ($message) use ($email) {
                                        $message->to($email)
                                            ->subject("Document is Sent By".Auth::user()->name);
                                    }
                                  );
                                  } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                  }
                                }
                        //  }
                      }

                   $list = Helpers::getSupplierContactDepartmentList($supplierSite->division_id);
                        foreach ($list as $u) {
                            // if($u->q_m_s_divisions_id == $supplierSite->division_id){
                                $email = Helpers::getInitiatorEmail($u->user_id);
                                 if ($email !== null) {
                                 try {
                                  Mail::send(
                                      'mail.view-mail',
                                       ['data' => $supplierSite],
                                    function ($message) use ($email) {
                                        $message->to($email)
                                            ->subject("Document is Sent By".Auth::user()->name);
                                    }
                                  );
                                  } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                  }
                                }
                        //  }
                      }


            
            $supplierSite->update();
            toastr()->success('Document Sent');
            return back();

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function sendToPendingSupplierAudit(Request $request, $id){
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $supplierSite = SupplierSite::find($id);
            $lastDocument = SupplierSite::find($id);
            if ($supplierSite->stage == 4) {
                    $supplierSite->stage = "3";
                    $supplierSite->status = "Pending Supplier Audit";
                    $supplierSite->reAudit_by = Auth::user()->name;
                    $supplierSite->reAudit_on = Carbon::now()->format('d-M-Y H:i A');
                    $supplierSite->reAudit_comment = $request->comments;

                    $history = new SupplierSiteAuditTrail();
                    $history->supplier_site_id = $id;
                    // $history->activity_type = 'Activity Log';
                    // $history->previous = "";

                    $history->activity_type = 'Re-Audit By, Re-Audit On';
                    if (is_null($lastDocument->reAudit_by) || $lastDocument->reAudit_by === '') {
                           $history->previous = "";
                        } else {
                           $history->previous = $lastDocument->reAudit_by . ' , ' . $lastDocument->reAudit_on;
                      }
                    
                    $history->current = $supplierSite->reAudit_by . ' , ' . $supplierSite->reAudit_on;
                    
                    $history->action = 'Re-Audit';
                    // $history->current = "Not Applicable";
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Pending Supplier Audit";
                    $history->change_from = $lastDocument->status;
                    // $history->stage = 'Plan Proposed';

                    if (is_null($lastDocument->reAudit_by) || $lastDocument->reAudit_by === '')
                    {
                      $history->action_name = 'New';
                    } else {
                      $history->action_name = 'Update';
                    }
                    $history->save();
                     $list = Helpers::getSupplierAuditorDepartmentList($supplierSite->division_id);
                        foreach ($list as $u) {
                            // if($u->q_m_s_divisions_id == $supplierSite->division_id){
                                $email = Helpers::getInitiatorEmail($u->user_id);
                                 if ($email !== null) {
                                try {
                                  Mail::send(
                                      'mail.view-mail',
                                       ['data' => $supplierSite],
                                    function ($message) use ($email) {
                                        $message->to($email)
                                            ->subject("Document is Sent By".Auth::user()->name);
                                    }
                                  );
                                } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            }
                        //  }
                      }
                    $supplierSite->update();

                    toastr()->success('Document Sent');
                    return back();
            }
            if ($supplierSite->stage == 5) {
                    $supplierSite->stage = "3";
                    $supplierSite->status = "Pending Supplier Audit";
                    $supplierSite->rejectedDueToQuality_by = Auth::user()->name;
                    $supplierSite->rejectedDueToQuality_on = Carbon::now()->format('d-M-Y H:i A');
                    $supplierSite->rejectedDueToQuality_comment = $request->comments;

                    $history = new SupplierSiteAuditTrail();
                    $history->supplier_site_id = $id;
                    // $history->activity_type = 'Activity Log';
                    // $history->previous = "";
                    $history->activity_type = 'Reject Due To Quality Issues By, Reject Due To Quality Issues On';
                    if (is_null($lastDocument->rejectedDueToQuality_by) || $lastDocument->rejectedDueToQuality_by === '') {
                           $history->previous = "";
                        } else {
                           $history->previous = $lastDocument->rejectedDueToQuality_by . ' , ' . $lastDocument->rejectedDueToQuality_on;
                      }
                    
                    $history->current = $supplierSite->rejectedDueToQuality_by . ' , ' . $supplierSite->rejectedDueToQuality_on;
                    
                    $history->action = 'Reject Due To Quality Issues';
                    // $history->current = "Not Applicable";
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Pending Supplier Audit";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
                    
                    if (is_null($lastDocument->rejectedDueToQuality_by) || $lastDocument->rejectedDueToQuality_by === '')
                    {
                      $history->action_name = 'New';
                    } else {
                      $history->action_name = 'Update';
                    }
                    
                    $history->save();
                    $list = Helpers::getSupplierAuditorDepartmentList($supplierSite->division_id);
                    foreach ($list as $u) {
                        // if($u->q_m_s_divisions_id == $supplierSite->division_id){
                            $email = Helpers::getInitiatorEmail($u->user_id);
                             if ($email !== null) {
                            try {
                              Mail::send(
                                  'mail.view-mail',
                                   ['data' => $supplierSite],
                                function ($message) use ($email) {
                                    $message->to($email)
                                        ->subject("Document is Sent By".Auth::user()->name);
                                }
                              );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //  }
                  }
                    $supplierSite->update();
                    
                    toastr()->success('Document Sent');
                    return back();
            }

             if ($supplierSite->stage == 3) {
                    $supplierSite->stage = "5";
                    $supplierSite->status = "Supplier Approved";

                    $supplierSite->conditionally_approved_by = Auth::user()->name;
                    $supplierSite->conditionally_approved_on = Carbon::now()->format('d-M-Y H:i A');
                    $supplierSite->conditionally_approved_comments = $request->comments;

                    $history = new SupplierSiteAuditTrail();
                    $history->supplier_site_id = $id;
                    // $history->activity_type = 'Activity Log';
                    // $history->previous = "";

                    $history->activity_type = 'Conditionally Approved By, Conditionally Approved On';
                    if (is_null($lastDocument->conditionally_approved_by) || $lastDocument->conditionally_approved_by === '') {
                           $history->previous = "";
                        } else {
                           $history->previous = $lastDocument->conditionally_approved_by . ' , ' . $lastDocument->conditionally_approved_on;
                      }
                    
                    $history->current = $supplierSite->conditionally_approved_by . ' , ' . $supplierSite->conditionally_approved_on;

                    
                    $history->action = 'Conditionally Approved';
                    // $history->current = "Not Applicable";
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Supplier Approved";
                    $history->change_from = $lastDocument->status;
                    // $history->stage = 'Plan Proposed';

                    if (is_null($lastDocument->conditionally_approved_by) || $lastDocument->conditionally_approved_by === '')
                    {
                      $history->action_name = 'New';
                    } else {
                      $history->action_name = 'Update';
                    }
                    
                    $history->save();
                    $list = Helpers::getSupplierContactDepartmentList($supplierSite->division_id);
                    foreach ($list as $u) {
                        // if($u->q_m_s_divisions_id == $supplierSite->division_id){
                            $email = Helpers::getInitiatorEmail($u->user_id);
                            if ($email !== null) {
                            try {
                              Mail::send(
                                  'mail.view-mail',
                                   ['data' => $supplierSite],
                                function ($message) use ($email) {
                                    $message->to($email)
                                        ->subject("Document is Sent By".Auth::user()->name);
                                }
                               );
                            } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            }
                    //  }
                  }
                    $supplierSite->update();
                    
                    toastr()->success('Document Sent');
                    return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function supplier_child(Request $request, $id)
    {
        $cc = SupplierSite::find($id);
        $cft = [];
        $parent_id = $id;
        $parent_division_id = $cc->division_id;
        $parent_type = "Supplier Site";
        $old_record = Capa::select('id', 'division_id', 'record')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $parent_intiation_date = Capa::where('id', $id)->value('intiation_date');
        $parent_record =  ((RecordNumber::first()->value('counter')) + 1);
        $parent_record = str_pad($parent_record, 4, '0', STR_PAD_LEFT);
        $parent_initiator_id = $id;
        $changeControl = OpenStage::find(1);
        $hod = User::get();
        $pre = CC::all();
        if (!empty($changeControl->cft)) $cft = explode(',', $changeControl->cft);
        
        if ($request->revision == "Action-Item") {
            $old_record = ActionItem::all();
            $cc->originator = User::where('id', $cc->initiator_id)->value('name');
            return view('frontend.forms.action-item', compact('record_number','old_record', 'due_date','parent_division_id','parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id'));

        }
        // dd($request->revision,$request->revision == "changecontrol");
        if ($request->revision == "changecontrol") {
            $cc->originator = User::where('id', $cc->initiator_id)->value('name');
            return view('frontend.change-control.new-change-control', compact('record_number', 'due_date', 'parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','hod','cft','pre'));
        }

        if ($request->revision == "capa-child") {
            $cc->originator = User::where('id', $cc->initiator_id)->value('name');
           return view('frontend.forms.capa', compact('record_number', 'due_date', 'parent_id', 'parent_type', 'old_record', 'cft'));
        }
        if ($request->revision == "deviation") {
         $cc->originator = User::where('id', $cc->initiator_id)->value('name');
        $pre = Deviation::all();
         return view('frontend.forms.deviation_new', compact('record_number', 'due_date', 'parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','pre'));
     }
     if ($request->revision == "RCA") {
        $cc->originator = User::where('id', $cc->initiator_id)->value('name');
    //    $pre = Deviation::all();
        return view('frontend.forms.root-cause-analysis', compact('record_number', 'due_date', 'parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','pre'));
    }
    if ($request->revision == "RA") {
        $cc->originator = User::where('id', $cc->initiator_id)->value('name');
    //    $pre = Deviation::all();
    $old_record = RiskManagement::select('id', 'division_id', 'record')->get();
        return view('frontend.forms.risk-management', compact('record_number', 'due_date', 'parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','pre','old_record','old_record'));
    }
    if ($request->revision == "SA") {
        $cc->originator = User::where('id', $cc->initiator_id)->value('name');
    //    $pre = Deviation::all();
    $old_record = RiskManagement::select('id', 'division_id', 'record')->get();
        return view('frontend.New_forms.supplier_audit', compact('record_number', 'due_date', 'parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','pre','old_record','old_record'));
    }
    if ($request->revision == "SCAR") {
        $cc->originator = User::where('id', $cc->initiator_id)->value('name');
        $supplierData = Supplier::select('id','supplier_name','supplier_products','distribution_sites')->get();
        $supplierName = Supplier::select('id','supplier_name')->get();
        $supplierProduct = Supplier::where('supplier_products' , '!=' , "null")->get();
        $distributionSites = Supplier::where('distribution_sites', '!=', "null")->get();
        $old_record = SCAR::select('id', 'division_id', 'record')->get();
        return view('frontend.scar.scar_new', compact('record_number','supplierName','supplierProduct','distributionSites', 'due_date', 'parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','pre','old_record','old_record'));
    }
    }
}