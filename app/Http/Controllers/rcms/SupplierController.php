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
use Illuminate\Support\Facades\File;

use App\Models\{RecordNumber,
Supplier,
SupplierGrid,
SupplierAuditTrail,
RoleGroup,
AuditReviewersDetails,
CC,
Deviation,
User,
OpenStage,
SCAR,
Capa,
RiskManagement,
SupplierChecklist
};

class SupplierController extends Controller
{
    public function index(Request $request){        
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_numbers = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');

        return view('frontend.supplier.supplier_new', compact('formattedDate', 'due_date', 'record_numbers'));
    }

    public function store(Request $request){
        $supplier = new Supplier();
        $supplier->type = "Supplier";
        $supplier->division_id = $request->division_id;
        $supplier->record = DB::table('record_numbers')->value('counter') + 1;
        $supplier->parent_id = $request->parent_id;
        $supplier->parent_type = $request->parent_type;
        $supplier->initiator_id = Auth::user()->id;
        $supplier->date_opened = $request->date_opened;
        $supplier->intiation_date = $request->intiation_date;
        $supplier->short_description = $request->short_description;
        // $supplier->assign_to = $request->assign_to;
        $supplier->due_date = Carbon::now()->addDays(30)->format('d-M-Y');
        $supplier->intiation_date = $request->intiation_date;
        $supplier->initiator_group_code = $request->initiator_group_code;
        $supplier->initiation_group = Helpers::getFullDepartmentName($request->initiator_group_code);
        $supplier->manufacturerName = $request->manufacturerName;
        $supplier->starting_material = $request->starting_material;
        $supplier->material_code = $request->material_code;
        $supplier->pharmacopoeial_claim = $request->pharmacopoeial_claim;
        $supplier->cep_grade = $request->cep_grade;
        $supplier->request_for = implode(',',$request->request_for);
        $supplier->attach_batch = $request->attach_batch;
        $supplier->request_justification = $request->request_justification;
        $supplier->manufacturer_availability = $request->manufacturer_availability;
        $supplier->request_accepted = $request->request_accepted;
        $supplier->cqa_remark = $request->cqa_remark;
        $supplier->accepted_by = $request->accepted_by;
        $supplier->accepted_on = $request->accepted_on;
        $supplier->pre_purchase_sample = $request->pre_purchase_sample;
        $supplier->justification = $request->justification;
        $supplier->cqa_coordinator = $request->cqa_coordinator;
        $supplier->pre_purchase_sample_analysis = $request->pre_purchase_sample_analysis;
        $supplier->availability_od_coa = $request->availability_od_coa;
        $supplier->analyzed_location = $request->analyzed_location;
        $supplier->cqa_comment = $request->cqa_comment;
        $supplier->materialName = $request->materialName;
        $supplier->manufacturerNameNew = $request->manufacturerNameNew;
        $supplier->analyzedLocation = $request->analyzedLocation;
        $supplier->cqa_corporate_comment = $request->cqa_corporate_comment;
        $supplier->cqa_designee = $request->cqa_designee;
        $supplier->sample_ordered = $request->sample_ordered;
        $supplier->sample_order_justification = $request->sample_order_justification;
        $supplier->acknowledge_by = $request->acknowledge_by;
        $supplier->trail_status_feedback = $request->trail_status_feedback;
        $supplier->sample_stand_approved = $request->sample_stand_approved;
        $supplier->tse_bse = $request->tse_bse;
        $supplier->tse_bse_remark = $request->tse_bse_remark;
        $supplier->residual_solvent = $request->residual_solvent;
        $supplier->residual_solvent_remark = $request->residual_solvent_remark;
        $supplier->gmo = $request->gmo;
        $supplier->gmo_remark = $request->gmo_remark;
        $supplier->melamine = $request->melamine;
        $supplier->melamine_remark = $request->melamine_remark;
        $supplier->gluten = $request->gluten;
        $supplier->gluten_remark = $request->gluten_remark;
        $supplier->nitrosamine = $request->nitrosamine;
        $supplier->nitrosamine_remark = $request->nitrosamine_remark;
        $supplier->who = $request->who;
        $supplier->who_remark = $request->who_remark;
        $supplier->gmp = $request->gmp;
        $supplier->gmp_remark = $request->gmp_remark;
        $supplier->iso_certificate = $request->iso_certificate;
        $supplier->iso_certificate_remark = $request->iso_certificate_remark;
        $supplier->manufacturing_license = $request->manufacturing_license;
        $supplier->manufacturing_license_remark = $request->manufacturing_license_remark;
        $supplier->cep = $request->cep;
        $supplier->cep_remark = $request->cep_remark;
        $supplier->msds = $request->msds;
        $supplier->msds_remark = $request->msds_remark;
        $supplier->elemental_impurities = $request->elemental_impurities;
        $supplier->elemental_impurities_remark = $request->elemental_impurities_remark;
        $supplier->declaration = $request->declaration;
        $supplier->declaration_remark = $request->declaration_remark;
        $supplier->supply_chain_availability = $request->supply_chain_availability;
        $supplier->quality_agreement_availability = $request->quality_agreement_availability;
        $supplier->risk_assessment_done = $request->risk_assessment_done;
        $supplier->risk_rating = $request->risk_rating;
        $supplier->manufacturer_audit_planned = $request->manufacturer_audit_planned;
        $supplier->manufacturer_audit_conducted = $request->manufacturer_audit_conducted;
        $supplier->manufacturer_can_be = $request->manufacturer_can_be;  
        $supplier->supplierJustification = $request->supplierJustification;     

        if (!empty($request->cep_attachment)) {
            $files = [];
            if ($request->hasfile('cep_attachment')) {
                foreach ($request->file('cep_attachment') as $file) {
                    $name = "Supplier" . '-cep_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->cep_attachment = json_encode($files);
        }

        if (!empty($request->coa_attachment)) {
            $files = [];
            if ($request->hasfile('coa_attachment')) {
                foreach ($request->file('coa_attachment') as $file) {
                    $name = "Supplier" . '-coa_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->coa_attachment = json_encode($files);
        } 

        /****************** HOD Review ********************/
        $supplier->HOD_feedback = $request->HOD_feedback;
        $supplier->HOD_comment = $request->HOD_comment;

        if (!empty($request->HOD_attachment)) {
            $files = [];
            if ($request->hasfile('HOD_attachment')) {
                foreach ($request->file('HOD_attachment') as $file) {
                    $name = "Supplier" . '-HOD_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->HOD_attachment = json_encode($files);
        }

        /****************** Supplier Details ********************/
        $supplier->supplier_name = $request->supplier_name;
        $supplier->supplier_id = $request->supplier_id;        
        $supplier->manufacturer_name = $request->manufacturer_name;
        $supplier->manufacturer_id = $request->manufacturer_id;
        $supplier->vendor_name = $request->vendor_name;
        $supplier->vendor_id = $request->vendor_id;
        $supplier->contact_person = $request->contact_person;
        $supplier->other_contacts = $request->other_contacts;
        $supplier->supplier_serivce = $request->supplier_serivce;
        $supplier->zone = $request->zone;
        $supplier->country = $request->country;
        $supplier->state = $request->state;
        $supplier->city = $request->city;
        $supplier->address = $request->address;
        $supplier->iso_certified_date = $request->iso_certified_date;
        $supplier->suppplier_contacts = $request->suppplier_contacts;
        $supplier->related_non_conformance = $request->related_non_conformance;
        $supplier->suppplier_agreement = $request->suppplier_agreement;
        $supplier->regulatory_history = $request->regulatory_history;
        $supplier->distribution_sites = $request->distribution_sites;
        $supplier->manufacturing_sited = $request->manufacturing_sited;
        $supplier->quality_management = $request->quality_management;
        $supplier->bussiness_history = $request->bussiness_history;
        $supplier->performance_history = $request->performance_history;
        $supplier->compliance_risk = $request->compliance_risk;
        $supplier->suppplier_web_site = $request->suppplier_web_site;

        /****************** Score Card Content ********************/
        $supplier->cost_reduction = $request->cost_reduction;
        $supplier->cost_reduction_weight = $request->cost_reduction_weight;        
        $supplier->payment_term = $request->payment_term;
        $supplier->payment_term_weight = $request->payment_term_weight;
        $supplier->lead_time_days = $request->lead_time_days;
        $supplier->lead_time_days_weight = $request->lead_time_days_weight;
        $supplier->ontime_delivery = $request->ontime_delivery;
        $supplier->ontime_delivery_weight = $request->ontime_delivery_weight;
        $supplier->supplier_bussiness_planning = $request->supplier_bussiness_planning;
        $supplier->supplier_bussiness_planning_weight = $request->supplier_bussiness_planning_weight;
        $supplier->rejection_ppm = $request->rejection_ppm;
        $supplier->rejection_ppm_weight = $request->rejection_ppm_weight;
        $supplier->quality_system = $request->quality_system;
        $supplier->quality_system_ranking = $request->quality_system_ranking;
        $supplier->car_generated = $request->car_generated;
        $supplier->car_generated_weight = $request->car_generated_weight;
        $supplier->closure_time = $request->closure_time;
        $supplier->closure_time_weight = $request->closure_time_weight;
        $supplier->end_user_satisfaction = $request->end_user_satisfaction;
        $supplier->end_user_satisfaction_weight = $request->end_user_satisfaction_weight;
        $supplier->scorecard_record = $request->scorecard_record;
        $supplier->achieved_score = $request->achieved_score;
        $supplier->total_available_score = $request->total_available_score;
        $supplier->total_score = $request->total_score;

        /****************** QA Reviewer ********************/
        $supplier->QA_reviewer_feedback = $request->QA_reviewer_feedback;
        $supplier->QA_reviewer_comment = $request->QA_reviewer_comment;

        if (!empty($request->QA_reviewer_attachment)) {
            $files = [];
            if ($request->hasfile('QA_reviewer_attachment')) {
                foreach ($request->file('QA_reviewer_attachment') as $file) {
                    $name = "Supplier" . '-QA_reviewer_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->QA_reviewer_attachment = json_encode($files);
        }

        /****************** Risk Assessment Content ********************/
        $supplier->last_audit_date = $request->last_audit_date;
        $supplier->next_audit_date = $request->next_audit_date;        
        $supplier->audit_frequency = $request->audit_frequency;
        $supplier->last_audit_result = $request->last_audit_result;
        $supplier->facility_type = $request->facility_type;
        $supplier->nature_of_employee = $request->nature_of_employee;
        $supplier->technical_support = $request->technical_support;
        $supplier->survice_supported = $request->survice_supported;
        $supplier->reliability = $request->reliability;
        $supplier->revenue = $request->revenue;
        $supplier->client_base = $request->client_base;
        $supplier->previous_audit_result = $request->previous_audit_result;
        $supplier->risk_raw_total = $request->risk_raw_total;
        $supplier->risk_median = $request->risk_median;
        $supplier->risk_average = $request->risk_average;
        $supplier->risk_assessment_total = $request->risk_assessment_total;

        /****************** QA Reviewer ********************/
        $supplier->QA_head_comment = $request->QA_head_comment;

        if (!empty($request->QA_head_attachment)) {
            $files = [];
            if ($request->hasfile('QA_head_attachment')) {
                foreach ($request->file('QA_head_attachment') as $file) {
                    $name = "Supplier" . '-QA_head_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->QA_head_attachment = json_encode($files);
        }

        /************ Additional Attchment Code ************/
        if (!empty($request->iso_certificate_attachment)) {
            $files = [];
            if ($request->hasfile('iso_certificate_attachment')) {
                foreach ($request->file('iso_certificate_attachment') as $file) {
                    $name = "Supplier" . '-iso_certificate_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->iso_certificate_attachment = json_encode($files);
        }

        if (!empty($request->gi_additional_attachment)) {
            $files = [];
            if ($request->hasfile('gi_additional_attachment')) {
                foreach ($request->file('gi_additional_attachment') as $file) {
                    $name = "Supplier" . '-gi_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->gi_additional_attachment = json_encode($files);
        }

        if (!empty($request->hod_additional_attachment)) {
            $files = [];
            if ($request->hasfile('hod_additional_attachment')) {
                foreach ($request->file('hod_additional_attachment') as $file) {
                    $name = "Supplier" . '-hod_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->hod_additional_attachment = json_encode($files);
        }

        if (!empty($request->supplier_detail_additional_attachment)) {
            $files = [];
            if ($request->hasfile('supplier_detail_additional_attachment')) {
                foreach ($request->file('supplier_detail_additional_attachment') as $file) {
                    $name = "Supplier" . '-supplier_detail_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->supplier_detail_additional_attachment = json_encode($files);
        }

        if (!empty($request->score_card_additional_attachment)) {
            $files = [];
            if ($request->hasfile('score_card_additional_attachment')) {
                foreach ($request->file('score_card_additional_attachment') as $file) {
                    $name = "Supplier" . '-score_card_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->score_card_additional_attachment = json_encode($files);
        }

        if (!empty($request->qa_reviewer_additional_attachment)) {
            $files = [];
            if ($request->hasfile('qa_reviewer_additional_attachment')) {
                foreach ($request->file('qa_reviewer_additional_attachment') as $file) {
                    $name = "Supplier" . '-qa_reviewer_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->qa_reviewer_additional_attachment = json_encode($files);
        }

        if (!empty($request->risk_assessment_additional_attachment)) {
            $files = [];
            if ($request->hasfile('risk_assessment_additional_attachment')) {
                foreach ($request->file('risk_assessment_additional_attachment') as $file) {
                    $name = "Supplier" . '-risk_assessment_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->risk_assessment_additional_attachment = json_encode($files);
        }

        if (!empty($request->qa_head_additional_attachment)) {
            $files = [];
            if ($request->hasfile('qa_head_additional_attachment')) {
                foreach ($request->file('qa_head_additional_attachment') as $file) {
                    $name = "Supplier" . '-qa_head_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->qa_head_additional_attachment = json_encode($files);
        }

        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();

        $supplier->status = 'Opened';
        $supplier->stage = 1;
        $supplier->save();
        
        /******************** Supplier Checklist ******************/
        $supplierId = $supplier->id;
        $types = ['tse', 'residual_solvent','melamine','gmo','gluten','manufacturer_evaluation','who','gmp','ISO','manufacturing_license','CEP','risk_assessment','elemental_impurity','azido_impurities'];

        foreach ($types as $type) {
            $attachments = $request->file("{$type}_attachment") ?? [];
            $issueDates = $request->input("certificate_issue_{$type}") ?? [];
            $expiryDates = $request->input("certificate_expiry_{$type}") ?? [];
            $remarks = $request->input("{$type}_remarks") ?? [];

            $maxRows = max(count($attachments), count($issueDates), count($expiryDates), count($remarks));

            Log::info("Processing type: {$type}, rows: {$maxRows}");

            for ($index = 0; $index < $maxRows; $index++) {
                $attachmentPath = null;
                if (isset($attachments[$index]) && $attachments[$index] != null) {
                    $attachment = $attachments[$index];
                    $filename =  "Supplier-" . "Certificate" . rand(1, 100) . '.' . $attachment->getClientOriginalExtension();
                    $attachmentPath = $attachment->move('upload/', $filename);
                }

                // Safely handle possibly missing array elements
                $issueDate = $issueDates[$index] ?? 'NULL';
                $expiryDate = $expiryDates[$index] ?? 'NULL';
                $remark = $remarks[$index] ?? 'NULL';

                Log::info("Creating row: index={$index}, issue_date={$issueDate}, expiry_date={$expiryDate}, remarks={$remark}, attachment={$attachmentPath}");

                SupplierChecklist::create([
                    'supplier_id' => $supplierId,
                    'doc_type' => $type,
                    'attachment' => $attachmentPath,
                    'issue_date' => $issueDates[$index] ?? null,
                    'expiry_date' => $expiryDates[$index] ?? null,
                    'remarks' => $remarks[$index] ?? null,
                ]);
            }
        }

        $certificationData = SupplierGrid::where(['supplier_id' => $supplier->id, 'identifier' =>'CertificationData'])->firstOrCreate();
        $certificationData->supplier_id = $supplier->id;
        $certificationData->identifier = 'CertificationData';
        $certificationData->data = $request->certificationData;
        $certificationData->save();

        /******************* Audit Trail Code ***********************/
        $history = new SupplierAuditTrail;
        $history->supplier_id = $supplier->id;
        $history->activity_type = 'Inititator';
        $history->previous = "Null";
        $history->current = Auth::user()->name;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $supplier->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        $history = new SupplierAuditTrail;
        $history->supplier_id = $supplier->id;
        $history->activity_type = 'Short Description';
        $history->previous = "Null";
        $history->current = $request->short_description;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $supplier->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        $history = new SupplierAuditTrail;
        $history->supplier_id = $supplier->id;
        $history->activity_type = 'Due Date';
        $history->previous = "Null";
        $history->current = $supplier->due_date;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $supplier->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        $history = new SupplierAuditTrail;
        $history->supplier_id = $supplier->id;
        $history->activity_type = 'Initiation Date';
        $history->previous = "Null";
        $history->current = Helpers::getdateFormat($supplier->intiation_date);
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $supplier->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiation";
        $history->action_name = 'Create';
        $history->save();

        if(!empty($request->assign_to)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Assign To';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($supplier->assign_to);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->initiation_group)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Initiation Department';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($supplier->initiation_group);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->initiator_group_code)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Initiator Department Code';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($supplier->initiator_group_code);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->manufacturerName)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Name of Manufacturer';
            $history->previous = "Null";
            $history->current = $supplier->manufacturerName;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->starting_material)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Name of Starting Material';
            $history->previous = "Null";
            $history->current = $supplier->starting_material;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->material_code)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Material Code';
            $history->previous = "Null";
            $history->current = $supplier->material_code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->pharmacopoeial_claim)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Pharmacopoeial Claim';
            $history->previous = "Null";
            $history->current = $supplier->pharmacopoeial_claim;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->cep_grade)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'CEP Grade Material';
            $history->previous = "Null";
            $history->current = $supplier->cep_grade;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->request_for)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Request For';
            $history->previous = "Null";
            $history->current = $supplier->request_for;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->attach_batch)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Attach Three Batch COAs';
            $history->previous = "Null";
            $history->current = $supplier->attach_batch;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->request_justification)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Justification for Request';
            $history->previous = "Null";
            $history->current = $supplier->request_justification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->manufacturer_availability)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Availability of Manufacturer COAs';
            $history->previous = "Null";
            $history->current = $supplier->manufacturer_availability;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->request_accepted)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Request Accepted';
            $history->previous = "Null";
            $history->current = $supplier->request_accepted;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

    
        if(!empty($request->cqa_remark)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Remark';
            $history->previous = "Null";
            $history->current = $supplier->cqa_remark;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->accepted_by)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Accepted By';
            $history->previous = "Null";
            $history->current = $supplier->accepted_by;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->pre_purchase_sample)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Pre Purchase Sample Required?';
            $history->previous = "Null";
            $history->current = $supplier->pre_purchase_sample;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->justification)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Justification';
            $history->previous = "Null";
            $history->current = $supplier->justification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->cqa_coordinator)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'CQA Coordinator';
            $history->previous = "Null";
            $history->current = $supplier->cqa_coordinator;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->pre_purchase_sample_analysis)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Pre Purchase Sample Analysis Completed?';
            $history->previous = "Null";
            $history->current = $supplier->pre_purchase_sample_analysis;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->availability_od_coa)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Availability of COAS After Analysis';
            $history->previous = "Null";
            $history->current = $supplier->availability_od_coa;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->analyzed_location)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Analyzed on Location';
            $history->previous = "Null";
            $history->current = $supplier->analyzed_location;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->cqa_comment)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Review Comment of CQA';
            $history->previous = "Null";
            $history->current = $supplier->cqa_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if(!empty($request->materialName)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Material Name';
            $history->previous = "Null";
            $history->current = $supplier->materialName;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->manufacturerNameNew)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Name of the Manufacturer';
            $history->previous = "Null";
            $history->current = $supplier->manufacturerNameNew;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->analyzedLocation)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Analyzed on Location';
            $history->previous = "Null";
            $history->current = $supplier->analyzedLocation;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplierJustification)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Justification';
            $history->previous = "Null";
            $history->current = $supplier->supplierJustification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->cqa_corporate_comment)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Review Comment of Corporate CQA';
            $history->previous = "Null";
            $history->current = $supplier->cqa_corporate_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->cqa_designee)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'CQA Designee';
            $history->previous = "Null";
            $history->current = $supplier->cqa_designee;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->sample_ordered)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Samples Ordered for Suitability Trail at R&D/MS & T';
            $history->previous = "Null";
            $history->current = $supplier->sample_ordered;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->sample_order_justification)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Justification';
            $history->previous = "Null";
            $history->current = $supplier->sample_order_justification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->acknowledge_by)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Acknowledge By';
            $history->previous = "Null";
            $history->current = $supplier->acknowledge_by;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->trail_status_feedback)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Feedback on Trail Status Completed';
            $history->previous = "Null";
            $history->current = $supplier->trail_status_feedback;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->sample_stand_approved)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Sample Stand Approved?';
            $history->previous = "Null";
            $history->current = $supplier->sample_stand_approved;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supply_chain_availability)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Availability of Supply Chain?';
            $history->previous = "Null";
            $history->current = $supplier->supply_chain_availability;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->quality_agreement_availability)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Availability of Quality Agreement?';
            $history->previous = "Null";
            $history->current = $supplier->quality_agreement_availability;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        // =======
        if(!empty($request->risk_assessment_done)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Risk Assessment Done?';
            $history->previous = "Null";
            $history->current = $supplier->risk_assessment_done;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->risk_rating)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Risk Rating';
            $history->previous = "Null";
            $history->current = $supplier->risk_rating;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->manufacturer_audit_planned)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'manufacturer_audit_planned';
            $history->previous = "Null";
            $history->current = $supplier->manufacturer_audit_planned;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->manufacturer_audit_conducted)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Maufacturer Audit Conducted On';
            $history->previous = "Null";
            $history->current = $supplier->manufacturer_audit_conducted;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->manufacturer_can_be)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Manufacturer Can be?';
            $history->previous = "Null";
            $history->current = $supplier->manufacturer_can_be;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->HOD_feedback)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'HOD Feedback';
            $history->previous = "Null";
            $history->current = strip_tags($supplier->HOD_feedback);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->HOD_comment)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'HOD Comment';
            $history->previous = "Null";
            $history->current = strip_tags($supplier->HOD_comment);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_name)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Supplier Name';
            $history->previous = "Null";
            $history->current = $supplier->supplier_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_id)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Supplier ID';
            $history->previous = "Null";
            $history->current = $supplier->supplier_id;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->manufacturer_name)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Manufacturer Name';
            $history->previous = "Null";
            $history->current = $supplier->manufacturer_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->manufacturer_id)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Manufacturer ID';
            $history->previous = "Null";
            $history->current = $supplier->manufacturer_id;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->vendor_name)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Vendor Name';
            $history->previous = "Null";
            $history->current = $supplier->vendor_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->vendor_id)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Vendor Id';
            $history->previous = "Null";
            $history->current = $supplier->vendor_id;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->contact_person)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Supplier Contract Person';
            $history->previous = "Null";
            $history->current = $supplier->contact_person;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_serivce)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Supplier Services';
            $history->previous = "Null";
            $history->current = strip_tags($supplier->supplier_serivce);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->zone)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Zone';
            $history->previous = "Null";
            $history->current = $supplier->zone;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->country)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Country';
            $history->previous = "Null";
            $history->current = $supplier->country;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->state)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'State';
            $history->previous = "Null";
            $history->current = $supplier->state;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->city)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'City';
            $history->previous = "Null";
            $history->current = $supplier->city;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->address)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Address';
            $history->previous = "Null";
            $history->current = $supplier->address;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->iso_certified_date)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'ISO Certified Date';
            $history->previous = "Null";
            $history->current = $supplier->iso_certified_date;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->related_non_conformance)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Related non-conformance';
            $history->previous = "Null";
            $history->current = $supplier->related_non_conformance;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->suppplier_agreement)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Supplier Agreement';
            $history->previous = "Null";
            $history->current = $supplier->suppplier_agreement;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->regulatory_history)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Regulatory History';
            $history->previous = "Null";
            $history->current = $supplier->regulatory_history;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->distribution_sites)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Distribution Site';
            $history->previous = "Null";
            $history->current = $supplier->distribution_sites;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->manufacturing_sited)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Manufacturing Site';
            $history->previous = "Null";
            $history->current = strip_tags($supplier->manufacturing_sited);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->quality_management)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Quality  Management';
            $history->previous = "Null";
            $history->current = strip_tags($supplier->quality_management);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->bussiness_history)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Business History';
            $history->previous = "Null";
            $history->current = strip_tags($supplier->bussiness_history);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->performance_history)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Performance History';
            $history->previous = "Null";
            $history->current = strip_tags($supplier->performance_history);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->compliance_risk)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Compliance Risk';
            $history->previous = "Null";
            $history->current = strip_tags($supplier->compliance_risk);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->cost_reduction)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Cost Reduction';
            $history->previous = "Null";
            $history->current = $supplier->cost_reduction;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->cost_reduction_weight)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Cost Reduction Weight';
            $history->previous = "Null";
            $history->current = $supplier->cost_reduction_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->payment_term)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Payment Term';
            $history->previous = "Null";
            $history->current = $supplier->payment_term;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->payment_term_weight)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Payment Term Weight';
            $history->previous = "Null";
            $history->current = $supplier->payment_term_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        
        if(!empty($request->lead_time_days_weight)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Lead Time Day Weight';
            $history->previous = "Null";
            $history->current = $supplier->lead_time_days_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->ontime_delivery)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'On Time Delivery';
            $history->previous = "Null";
            $history->current = $supplier->ontime_delivery;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->ontime_delivery_weight)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'On Time Delivery Weight';
            $history->previous = "Null";
            $history->current = $supplier->ontime_delivery_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_bussiness_planning)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Supplier Business Planning';
            $history->previous = "Null";
            $history->current = $supplier->supplier_bussiness_planning;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->supplier_bussiness_planning_weight)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Supplier Business Weight';
            $history->previous = "Null";
            $history->current = $supplier->supplier_bussiness_planning_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->rejection_ppm)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Rejection in PPM';
            $history->previous = "Null";
            $history->current = $supplier->rejection_ppm;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->rejection_ppm_weight)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Rejection in PPM Weight';
            $history->previous = "Null";
            $history->current = $supplier->rejection_ppm_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->quality_system)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Quality Systems';
            $history->previous = "Null";
            $history->current = $supplier->quality_system;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->quality_system_ranking)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Quality Systems Ranking';
            $history->previous = "Null";
            $history->current = $supplier->quality_system_ranking;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->car_generated)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = '# of CAR generated';
            $history->previous = "Null";
            $history->current = $supplier->car_generated;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->car_generated_weight)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = '# of CAR generated Weight';
            $history->previous = "Null";
            $history->current = $supplier->car_generated_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->closure_time)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'CAR Closure Time';
            $history->previous = "Null";
            $history->current = $supplier->closure_time;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->closure_time_weight)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'CAR Closure Time Weight';
            $history->previous = "Null";
            $history->current = $supplier->closure_time_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->end_user_satisfaction)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'End-User Satisfaction';
            $history->previous = "Null";
            $history->current = $supplier->end_user_satisfaction;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->end_user_satisfaction_weight)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'End-User Satisfaction Weight';
            $history->previous = "Null";
            $history->current = $supplier->end_user_satisfaction_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->QA_reviewer_feedback)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'QA Reviewer Feedback';
            $history->previous = "Null";
            $history->current = strip_tags($supplier->QA_reviewer_feedback);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->QA_reviewer_comment)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'QA Reviewer Comment';
            $history->previous = "Null";
            $history->current = strip_tags($supplier->QA_reviewer_comment);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->last_audit_date)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Last Audit Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($supplier->last_audit_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->next_audit_date)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Next Audit Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($supplier->next_audit_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->audit_frequency)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Audit Frequency';
            $history->previous = "Null";
            $history->current = $supplier->audit_frequency;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->last_audit_result)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Last Audit Result';
            $history->previous = "Null";
            $history->current = $supplier->last_audit_result;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->facility_type)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Facility Type';
            $history->previous = "Null";
            $history->current = $supplier->facility_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->nature_of_employee)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Number of Employees';
            $history->previous = "Null";
            $history->current = $supplier->nature_of_employee;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->technical_support)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Access to Technical Support';
            $history->previous = "Null";
            $history->current = $supplier->technical_support;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->survice_supported)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Services Supported';
            $history->previous = "Null";
            $history->current = $supplier->survice_supported;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->reliability)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Reliability';
            $history->previous = "Null";
            $history->current = $supplier->reliability;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->revenue)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Revenue';
            $history->previous = "Null";
            $history->current = $supplier->revenue;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->client_base)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Client Base';
            $history->previous = "Null";
            $history->current = $supplier->client_base;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->previous_audit_result)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'Previous Audit Results';
            $history->previous = "Null";
            $history->current = $supplier->previous_audit_result;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if(!empty($request->QA_head_comment)){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $supplier->id;
            $history->activity_type = 'QA Head Comment';
            $history->previous = "Null";
            $history->current = strip_tags($supplier->QA_head_comment);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $supplier->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }

    public function show(Request $request, $id){
        $data = Supplier::find($id);
        $supplierChecklist = SupplierChecklist::where('supplier_id', $id)->get();
        $gridData = SupplierGrid::where(['supplier_id' => $id, 'identifier' => "CertificationData"])->first();
        $certificationData = json_decode($gridData->data, true);
        return view('frontend.supplier.supplier_view', compact('data', 'certificationData', 'supplierChecklist'));
    }

    public function update(Request $request, $id){       
        $lastDocument = Supplier::find($id);
        $supplier = Supplier::find($id);

        $supplier->date_opened = $request->date_opened;
        $supplier->short_description = $request->short_description;
        // $supplier->assign_to = $request->assign_to;
        // dd($request->due_date);
        // $supplier->due_date = $request->due_date;
       $supplier->initiator_group_code = $request->initiator_group_code;
    //    dd($request->initiation_group);
        $supplier->initiation_group = Helpers::getFullDepartmentName($request->initiator_group_code);
        $supplier->manufacturerName = $request->manufacturerName;
        $supplier->starting_material = $request->starting_material;
        $supplier->material_code = $request->material_code;
        $supplier->pharmacopoeial_claim = $request->pharmacopoeial_claim;
        $supplier->cep_grade = $request->cep_grade;
        $supplier->request_for = implode(',',$request->request_for);
        $supplier->attach_batch = $request->attach_batch;
        $supplier->request_justification = $request->request_justification;
        $supplier->manufacturer_availability = $request->manufacturer_availability;
        // dd($request->manufacturer_availability);
        $supplier->request_accepted = $request->request_accepted;
        $supplier->cqa_remark = $request->cqa_remark;
        $supplier->accepted_by = $request->accepted_by;
        $supplier->accepted_on = $request->accepted_on;
        $supplier->pre_purchase_sample = $request->pre_purchase_sample;
        $supplier->justification = $request->justification;
        $supplier->cqa_coordinator = $request->cqa_coordinator;
        $supplier->pre_purchase_sample_analysis = $request->pre_purchase_sample_analysis;
        $supplier->availability_od_coa = $request->availability_od_coa;
        $supplier->analyzed_location = $request->analyzed_location;
        $supplier->cqa_comment = $request->cqa_comment;
        $supplier->materialName = $request->materialName;
        $supplier->manufacturerNameNew = $request->manufacturerNameNew;
        $supplier->analyzedLocation = $request->analyzedLocation;
        $supplier->cqa_corporate_comment = $request->cqa_corporate_comment;
        $supplier->cqa_designee = $request->cqa_designee;
        $supplier->sample_ordered = $request->sample_ordered;
        $supplier->sample_order_justification = $request->sample_order_justification;
        $supplier->acknowledge_by = $request->acknowledge_by;
        $supplier->trail_status_feedback = $request->trail_status_feedback;
        $supplier->sample_stand_approved = $request->sample_stand_approved;
        $supplier->tse_bse = $request->tse_bse;
        $supplier->tse_bse_remark = $request->tse_bse_remark;
        $supplier->residual_solvent = $request->residual_solvent;
        $supplier->residual_solvent_remark = $request->residual_solvent_remark;
        $supplier->gmo = $request->gmo;
        $supplier->gmo_remark = $request->gmo_remark;
        $supplier->melamine = $request->melamine;
        $supplier->melamine_remark = $request->melamine_remark;
        $supplier->gluten = $request->gluten;
        $supplier->gluten_remark = $request->gluten_remark;
        $supplier->nitrosamine = $request->nitrosamine;
        $supplier->nitrosamine_remark = $request->nitrosamine_remark;
        $supplier->who = $request->who;
        $supplier->who_remark = $request->who_remark;
        $supplier->gmp = $request->gmp;
        $supplier->gmp_remark = $request->gmp_remark;
        $supplier->iso_certificate = $request->iso_certificate;
        $supplier->iso_certificate_remark = $request->iso_certificate_remark;
        $supplier->manufacturing_license = $request->manufacturing_license;
        $supplier->manufacturing_license_remark = $request->manufacturing_license_remark;
        $supplier->cep = $request->cep;
        $supplier->cep_remark = $request->cep_remark;
        $supplier->msds = $request->msds;
        $supplier->msds_remark = $request->msds_remark;
        $supplier->elemental_impurities = $request->elemental_impurities;
        $supplier->elemental_impurities_remark = $request->elemental_impurities_remark;
        $supplier->declaration = $request->declaration;
        $supplier->declaration_remark = $request->declaration_remark;
        $supplier->supply_chain_availability = $request->supply_chain_availability;
        $supplier->quality_agreement_availability = $request->quality_agreement_availability;
        $supplier->risk_assessment_done = $request->risk_assessment_done;
        $supplier->risk_rating = $request->risk_rating;
        $supplier->manufacturer_audit_planned = $request->manufacturer_audit_planned;
        $supplier->manufacturer_audit_conducted = $request->manufacturer_audit_conducted;
        $supplier->manufacturer_can_be = $request->manufacturer_can_be;
        $supplier->supplierJustification = $request->supplierJustification;
    

        if (!empty($request->cep_attachment)) {
            $files = [];
            if ($request->hasfile('cep_attachment')) {
                foreach ($request->file('cep_attachment') as $file) {
                    $name = "Supplier" . '-cep_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->cep_attachment = json_encode($files);
        }

        if (!empty($request->coa_attachment)) {
            $files = [];
            if ($request->hasfile('coa_attachment')) {
                foreach ($request->file('coa_attachment') as $file) {
                    $name = "Supplier" . '-coa_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->coa_attachment = json_encode($files);
        } 

        /****************** HOD Review ********************/
        $supplier->HOD_feedback = $request->HOD_feedback;
        $supplier->HOD_comment = $request->HOD_comment;

        if (!empty($request->HOD_attachment)) {
            $files = [];
            if ($request->hasfile('HOD_attachment')) {
                foreach ($request->file('HOD_attachment') as $file) {
                    $name = "Supplier" . '-HOD_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->HOD_attachment = json_encode($files);
        }

        /****************** Supplier Details ********************/
        $supplier->supplier_name = $request->supplier_name;
        $supplier->supplier_id = $request->supplier_id;        
        $supplier->manufacturer_name = $request->manufacturer_name;
        $supplier->manufacturer_id = $request->manufacturer_id;
        $supplier->vendor_name = $request->vendor_name;
        $supplier->vendor_id = $request->vendor_id;
        $supplier->contact_person = $request->contact_person;
        $supplier->other_contacts = $request->other_contacts;
        $supplier->supplier_serivce = $request->supplier_serivce;
        $supplier->zone = $request->zone;
        // dd($request->zone);
        $supplier->country = $request->country;
        $supplier->state = $request->state;
        $supplier->city = $request->city;
        $supplier->address = $request->address;
        $supplier->iso_certified_date = $request->iso_certified_date;
        $supplier->suppplier_contacts = $request->suppplier_contacts;
        $supplier->related_non_conformance = $request->related_non_conformance;
        $supplier->suppplier_agreement = $request->suppplier_agreement;
        $supplier->regulatory_history = $request->regulatory_history;
        $supplier->distribution_sites = $request->distribution_sites;
        $supplier->manufacturing_sited = $request->manufacturing_sited;
        $supplier->quality_management = $request->quality_management;
        $supplier->bussiness_history = $request->bussiness_history;
        $supplier->performance_history = $request->performance_history;
        $supplier->compliance_risk = $request->compliance_risk;
        $supplier->supplier_website = $request->supplier_website;        
        $supplier->suppplier_web_site = $request->suppplier_web_site;
        // dd($request->supplier_website);
        
        /****************** Score Card Content ********************/
        $supplier->cost_reduction = $request->cost_reduction;
        $supplier->cost_reduction_weight = $request->cost_reduction_weight;        
        $supplier->payment_term = $request->payment_term;
        $supplier->payment_term_weight = $request->payment_term_weight;
        $supplier->lead_time_days = $request->lead_time_days;
        $supplier->lead_time_days_weight = $request->lead_time_days_weight;
        $supplier->ontime_delivery = $request->ontime_delivery;
        $supplier->ontime_delivery_weight = $request->ontime_delivery_weight;
        $supplier->supplier_bussiness_planning = $request->supplier_bussiness_planning;
        $supplier->supplier_bussiness_planning_weight = $request->supplier_bussiness_planning_weight;
        $supplier->rejection_ppm = $request->rejection_ppm;
        $supplier->rejection_ppm_weight = $request->rejection_ppm_weight;
        $supplier->quality_system = $request->quality_system;
        $supplier->quality_system_ranking = $request->quality_system_ranking;
        $supplier->car_generated = $request->car_generated;
        $supplier->car_generated_weight = $request->car_generated_weight;
        $supplier->closure_time = $request->closure_time;
        $supplier->closure_time_weight = $request->closure_time_weight;
        $supplier->end_user_satisfaction = $request->end_user_satisfaction;
        $supplier->end_user_satisfaction_weight = $request->end_user_satisfaction_weight;
        $supplier->scorecard_record = $request->scorecard_record;
        $supplier->achieved_score = $request->achieved_score;
        $supplier->total_available_score = $request->total_available_score;
        $supplier->total_score = $request->total_score;

        /****************** QA Reviewer ********************/
        $supplier->QA_reviewer_feedback = $request->QA_reviewer_feedback;
        $supplier->QA_reviewer_comment = $request->QA_reviewer_comment;

        if (!empty($request->QA_reviewer_attachment)) {
            $files = [];
            if ($request->hasfile('QA_reviewer_attachment')) {
                foreach ($request->file('QA_reviewer_attachment') as $file) {
                    $name = "Supplier" . '-QA_reviewer_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->QA_reviewer_attachment = json_encode($files);
        }

        /****************** Risk Assessment Content ********************/
        $supplier->last_audit_date = $request->last_audit_date;
        $supplier->next_audit_date = $request->next_audit_date;        
        $supplier->audit_frequency = $request->audit_frequency;
        $supplier->last_audit_result = $request->last_audit_result;
        $supplier->facility_type = $request->facility_type;
        $supplier->nature_of_employee = $request->nature_of_employee;
        $supplier->technical_support = $request->technical_support;
        $supplier->survice_supported = $request->survice_supported;
        $supplier->reliability = $request->reliability;
        $supplier->revenue = $request->revenue;
        $supplier->client_base = $request->client_base;
        $supplier->previous_audit_result = $request->previous_audit_result;
        $supplier->risk_raw_total = $request->risk_raw_total;
        $supplier->risk_median = $request->risk_median;
        $supplier->risk_average = $request->risk_average;
        $supplier->risk_assessment_total = $request->risk_assessment_total;

        /****************** QA Reviewer ********************/
        $supplier->QA_head_comment = $request->QA_head_comment;

        if (!empty($request->QA_head_attachment)) {
            $files = [];
            if ($request->hasfile('QA_head_attachment')) {
                foreach ($request->file('QA_head_attachment') as $file) {
                    $name = "Supplier" . '-QA_head_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->QA_head_attachment = json_encode($files);
        }

        /************ Additional Attchment Code ************/
        if (!empty($request->iso_certificate_attachment)) {
            $files = [];
            if ($request->hasfile('iso_certificate_attachment')) {
                foreach ($request->file('iso_certificate_attachment') as $file) {
                    $name = "Supplier" . '-iso_certificate_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->iso_certificate_attachment = json_encode($files);
        }

        if (!empty($request->gi_additional_attachment)) {
            $files = [];
            if ($request->hasfile('gi_additional_attachment')) {
                foreach ($request->file('gi_additional_attachment') as $file) {
                    $name = "Supplier" . '-gi_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->gi_additional_attachment = json_encode($files);
        }

        if (!empty($request->hod_additional_attachment)) {
            $files = [];
            if ($request->hasfile('hod_additional_attachment')) {
                foreach ($request->file('hod_additional_attachment') as $file) {
                    $name = "Supplier" . '-hod_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->hod_additional_attachment = json_encode($files);
        }

        if (!empty($request->supplier_detail_additional_attachment)) {
            $files = [];
            if ($request->hasfile('supplier_detail_additional_attachment')) {
                foreach ($request->file('supplier_detail_additional_attachment') as $file) {
                    $name = "Supplier" . '-supplier_detail_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->supplier_detail_additional_attachment = json_encode($files);
        }

        if (!empty($request->score_card_additional_attachment)) {
            $files = [];
            if ($request->hasfile('score_card_additional_attachment')) {
                foreach ($request->file('score_card_additional_attachment') as $file) {
                    $name = "Supplier" . '-score_card_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->score_card_additional_attachment = json_encode($files);
        }

        if (!empty($request->qa_reviewer_additional_attachment)) {
            $files = [];
            if ($request->hasfile('qa_reviewer_additional_attachment')) {
                foreach ($request->file('qa_reviewer_additional_attachment') as $file) {
                    $name = "Supplier" . '-qa_reviewer_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->qa_reviewer_additional_attachment = json_encode($files);
        }

        if (!empty($request->risk_assessment_additional_attachment)) {
            $files = [];
            if ($request->hasfile('risk_assessment_additional_attachment')) {
                foreach ($request->file('risk_assessment_additional_attachment') as $file) {
                    $name = "Supplier" . '-risk_assessment_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->risk_assessment_additional_attachment = json_encode($files);
        }

        if (!empty($request->qa_head_additional_attachment)) {
            $files = [];
            if ($request->hasfile('qa_head_additional_attachment')) {
                foreach ($request->file('qa_head_additional_attachment') as $file) {
                    $name = "Supplier" . '-qa_head_additional_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $supplier->qa_head_additional_attachment = json_encode($files);
        }
        
        $supplier->update();

        /***************** Certificate Checklist *************************/

        
        $types = ['tse', 'residual_solvent','melamine','gmo','gluten','manufacturer_evaluation','who','gmp','ISO','manufacturing_license','CEP','risk_assessment','elemental_impurity','azido_impurities'];

        $supplierID = $supplier->id;
        if (!empty($supplierID)) {
            Log::info("Supplier ID: " . $supplierID);

            if ($request->has('remove_files')) {
                foreach ($request->input('remove_files') as $idToRemove) {
                    $grid = SupplierChecklist::find($idToRemove);
                    if ($grid && $grid->attachment) {
                        Storage::delete('public/upload/' . $grid->attachment);
                        $grid->attachment = null;
                        $grid->save();
                    }
                }
            }

            foreach ($types as $type) {
                $attachments = $request->file("{$type}_attachment") ?? [];
                $issueDates = $request->input("certificate_issue_{$type}") ?? [];
                $expiryDates = $request->input("certificate_expiry_{$type}") ?? [];
                $remarks = $request->input("{$type}_remarks") ?? [];

                $maxRows = max(count($attachments), count($issueDates), count($expiryDates), count($remarks));

                for ($index = 0; $index < $maxRows; $index++) {
                    $grid = SupplierChecklist::where('supplier_id', $supplierID)
                                            ->where('doc_type', $type)
                                            ->skip($index)
                                            ->first();

                    $attachmentPath = null;
                    if (isset($attachments[$index]) && $attachments[$index] != null) {
                        $attachment = $attachments[$index];

                        $filename = "Supplier-Certificate" . rand(1, 100) . '.' . $attachment->getClientOriginalExtension();
                        $attachmentPath = $attachment->move('upload/', $filename); // Store the file in public/attachments
                    }

                    if ($grid) {
                        $grid->update([
                            'attachment' => $attachmentPath ?? $grid->attachment,
                            'issue_date' => $issueDates[$index] ?? $grid->issue_date,
                            'expiry_date' => $expiryDates[$index] ?? $grid->expiry_date,
                            'remarks' => $remarks[$index] ?? $grid->remarks,
                        ]);
                    } else {
                        // Debugging: Check the data before creating a new record
                        Log::info("Creating new SupplierChecklist record", [
                            'supplier_id' => $supplierID,
                            'doc_type' => $type,
                            'attachment' => $attachmentPath,
                            'issue_date' => $issueDates[$index] ?? null,
                            'expiry_date' => $expiryDates[$index] ?? null,
                            'remarks' => $remarks[$index] ?? null,
                        ]);

                        SupplierChecklist::create([
                            'supplier_id' => $supplierID,
                            'doc_type' => $type,
                            'attachment' => $attachmentPath,
                            'issue_date' => $issueDates[$index] ?? null,
                            'expiry_date' => $expiryDates[$index] ?? null,
                            'remarks' => $remarks[$index] ?? null,
                        ]);
                    }
                }
            }
        } else {
            return redirect()->back()->with('error', 'Supplier ID is required.');
        }


        $certificationData = SupplierGrid::where(['supplier_id' => $supplier->id, 'identifier' =>'CertificationData'])->firstOrCreate();
        $certificationData->supplier_id = $supplier->id;
        $certificationData->identifier = 'CertificationData';
        $certificationData->data = $request->certificationData;
        $certificationData->update();

        if($lastDocument->short_description != $request->short_description){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->initiation_group != $request->initiation_group){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Initiator Group';
            $history->previous = Helpers::getFullDepartmentName($lastDocument->initiation_group);
            $history->current = Helpers::getFullDepartmentName($request->initiation_group);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->initiator_group_code != $request->initiator_group_code){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = $lastDocument->initiator_group_code;
            $history->current = $request->initiator_group_code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->manufacturerName != $request->manufacturerName){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Name of Manufacturer';
            $history->previous = $lastDocument->manufacturerName;
            $history->current = $request->manufacturerName;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->starting_material != $request->starting_material){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Name of Starting Material';
            $history->previous = $lastDocument->starting_material;
            $history->current = $request->starting_material;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->material_code != $request->material_code){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Material Code';
            $history->previous = $lastDocument->material_code;
            $history->current = $request->material_code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->pharmacopoeial_claim != $request->pharmacopoeial_claim){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Pharmacopoeial Claim';
            $history->previous = $lastDocument->pharmacopoeial_claim;
            $history->current = $request->pharmacopoeial_claim;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->cep_grade != $request->cep_grade){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'CEP Grade Material';
            $history->previous = $lastDocument->cep_grade;
            $history->current = $request->cep_grade;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->attach_batch != $request->attach_batch){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Attach Three Batch COAs';
            $history->previous = $lastDocument->attach_batch;
            $history->current = $request->attach_batch;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->request_justification != $request->request_justification){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Justification for Request';
            $history->previous = $lastDocument->request_justification;
            $history->current = $request->request_justification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->manufacturer_availability != $request->manufacturer_availability){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Availability of Manufacturer COAs';
            $history->previous = $lastDocument->manufacturer_availability;
            $history->current = $request->manufacturer_availability;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->request_accepted != $request->request_accepted){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Request Accepted';
            $history->previous = $lastDocument->request_accepted;
            $history->current = $request->request_accepted;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->cqa_remark != $request->cqa_remark){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'CQA Remark';
            $history->previous = strip_tags($lastDocument->cqa_remark);
            $history->current = strip_tags($request->cqa_remark);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->accepted_by != $request->accepted_by){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Accepted By';
            $history->previous = Helpers::getInitiatorName($lastDocument->accepted_by);
            $history->current = Helpers::getInitiatorName($request->accepted_by);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->pre_purchase_sample != $request->pre_purchase_sample){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Pre Purchase Sample Required?';
            $history->previous = $lastDocument->pre_purchase_sample;
            $history->current = $request->pre_purchase_sample;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->justification != $request->justification){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Justification';
            $history->previous = $lastDocument->justification;
            $history->current = $request->justification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->cqa_coordinator != $request->cqa_coordinator){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'CQA Coordinator';
            $history->previous = Helpers::getInitiatorName($lastDocument->cqa_coordinator);
            $history->current = Helpers::getInitiatorName($request->cqa_coordinator);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->pre_purchase_sample_analysis != $request->pre_purchase_sample_analysis){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Pre Purchase Sample Analysis Completed?';
            $history->previous = $lastDocument->pre_purchase_sample_analysis;
            $history->current = $request->pre_purchase_sample_analysis;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->availability_od_coa != $request->availability_od_coa){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Availability of COAS After Analysis';
            $history->previous = $lastDocument->availability_od_coa;
            $history->current = $request->availability_od_coa;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->analyzed_location != $request->analyzed_location){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Analyzed on Location';
            $history->previous = $lastDocument->analyzed_location;
            $history->current = $request->analyzed_location;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->cqa_comment != $request->cqa_comment){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Review Comment of CQA';
            $history->previous = $lastDocument->cqa_comment;
            $history->current = $request->cqa_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->materialName != $request->materialName){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Material Name';
            $history->previous = $lastDocument->materialName;
            $history->current = $request->materialName;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->manufacturerNameNew != $request->manufacturerNameNew){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Name of the Manufacturer';
            $history->previous = $lastDocument->manufacturerNameNew;
            $history->current = $request->manufacturerNameNew;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->analyzedLocation != $request->analyzedLocation){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Analyzed on Location';
            $history->previous = $lastDocument->analyzedLocation;
            $history->current = $request->analyzedLocation;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->supplierJustification != $request->supplierJustification){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Supplier Justification';
            $history->previous = $lastDocument->supplierJustification;
            $history->current = $request->supplierJustification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->cqa_corporate_comment != $request->cqa_corporate_comment){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Review Comment of Corporate CQA';
            $history->previous = $lastDocument->cqa_corporate_comment;
            $history->current = $request->cqa_corporate_comment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->cqa_designee != $request->cqa_designee){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'CQA Designee';
            $history->previous = Helpers::getInitiatorName($lastDocument->cqa_designee);
            $history->current = Helpers::getInitiatorName($request->cqa_designee);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->sample_ordered != $request->sample_ordered){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Samples Ordered for Suitability Trail at R&D/MS & T';
            $history->previous = $lastDocument->sample_ordered;
            $history->current = $request->sample_ordered;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->sample_order_justification != $request->sample_order_justification){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Sample Order Justification';
            $history->previous = $lastDocument->sample_order_justification;
            $history->current = $request->sample_order_justification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->acknowledge_by != $request->acknowledge_by){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Acknowledge By';
            $history->previous = Helpers::getInitiatorName($lastDocument->acknowledge_by);
            $history->current = Helpers::getInitiatorName($request->acknowledge_by);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->trail_status_feedback != $request->trail_status_feedback){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Feedback on Trail Status Completed';
            $history->previous = $lastDocument->trail_status_feedback;
            $history->current = $request->trail_status_feedback;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->sample_stand_approved != $request->sample_stand_approved){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Sample Stand Approved?';
            $history->previous = $lastDocument->sample_stand_approved;
            $history->current = $request->sample_stand_approved;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->supply_chain_availability != $request->supply_chain_availability){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Availability of Supply Chain?';
            $history->previous = $lastDocument->supply_chain_availability;
            $history->current = $request->supply_chain_availability;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->quality_agreement_availability != $request->quality_agreement_availability){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Availability of Quality Agreement?';
            $history->previous = $lastDocument->quality_agreement_availability;
            $history->current = $request->quality_agreement_availability;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->risk_assessment_done != $request->risk_assessment_done){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Risk Assessment Done?';
            $history->previous = $lastDocument->risk_assessment_done;
            $history->current = $request->risk_assessment_done;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->risk_rating != $request->risk_rating){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Risk Rating';
            $history->previous = $lastDocument->risk_rating;
            $history->current = $request->risk_rating;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->manufacturer_audit_planned != $request->manufacturer_audit_planned){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Quality Management';
            $history->previous = $lastDocument->manufacturer_audit_planned;
            $history->current = $request->manufacturer_audit_planned;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->manufacturer_audit_conducted != $request->manufacturer_audit_conducted){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Business History';
            $history->previous = $lastDocument->manufacturer_audit_conducted;
            $history->current = $request->manufacturer_audit_conducted;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->manufacturer_can_be != $request->manufacturer_can_be){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Manufacturer Can be?';
            $history->previous = $lastDocument->manufacturer_can_be;
            $history->current = $request->manufacturer_can_be;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }

        if($lastDocument->pre_purchase_sample != $request->supplier_name){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Pre Purchase Sample Required?';
            $history->previous = $lastDocument->supplier_name;
            $history->current = $request->supplier_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->supplier_id != $request->supplier_id){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Supplier ID';
            $history->previous = $lastDocument->supplier_id;
            $history->current = $request->supplier_id;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->manufacturer_name != $request->manufacturer_name){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Manufacturer Name';
            $history->previous = $lastDocument->manufacturer_name;
            $history->current = $request->manufacturer_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->manufacturer_id != $request->manufacturer_id){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Manufacturer Id';
            $history->previous = $lastDocument->manufacturer_id;
            $history->current = $request->manufacturer_id;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->vendor_name != $request->vendor_name){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Vendor Name';
            $history->previous = $lastDocument->vendor_name;
            $history->current = $request->vendor_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->vendor_id != $request->vendor_id){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Vendor ID';
            $history->previous = $lastDocument->vendor_id;
            $history->current = $request->vendor_id;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->contact_person != $request->contact_person){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->other_contacts != $request->other_contacts){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->supplier_serivce != $request->supplier_serivce){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Supplier Services';
            $history->previous = strip_tags($lastDocument->supplier_serivce);
            $history->current = strip_tags($request->supplier_serivce);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->zone != $request->zone){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->country != $request->country){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->state != $request->state){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->city != $request->city){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->address != $request->address){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->suppplier_web_site != $request->suppplier_web_site){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Supplier Web Site';
            $history->previous = $lastDocument->suppplier_web_site;
            $history->current = $request->suppplier_web_site;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->iso_certified_date != $request->iso_certified_date){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'ISO Certification date';
            $history->previous = $lastDocument->iso_certified_date;
            $history->current = $request->iso_certified_date;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->suppplier_contacts != $request->suppplier_contacts){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Contract';
            $history->previous = $lastDocument->suppplier_contacts;
            $history->current = $request->suppplier_contacts;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->related_non_conformance != $request->related_non_conformance){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->suppplier_agreement != $request->suppplier_agreement){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->regulatory_history != $request->regulatory_history){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->distribution_sites != $request->distribution_sites){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->manufacturing_sited != $request->manufacturing_sited){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Manufacturing Sites';
            $history->previous = strip_tags($lastDocument->manufacturing_sited);
            $history->current = strip_tags($request->manufacturing_sited);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->quality_management != $request->quality_management){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Quality Management';
            $history->previous = strip_tags($lastDocument->quality_management);
            $history->current = strip_tags($request->quality_management);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->bussiness_history != $request->bussiness_history){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Business History';
            $history->previous = strip_tags($lastDocument->bussiness_history);
            $history->current = strip_tags($request->bussiness_history);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->performance_history != $request->performance_history){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Performance History';
            $history->previous = strip_tags($lastDocument->performance_history);
            $history->current = strip_tags($request->performance_history);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->compliance_risk != $request->compliance_risk){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Compliance Risk';
            $history->previous = strip_tags($lastDocument->compliance_risk);
            $history->current = strip_tags($request->compliance_risk);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }


        if($lastDocument->cost_reduction != $request->cost_reduction){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->cost_reduction_weight != $request->cost_reduction_weight){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->payment_term != $request->payment_term){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->payment_term_weight != $request->payment_term_weight){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->lead_time_days_weight != $request->lead_time_days_weight){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->ontime_delivery != $request->ontime_delivery){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->ontime_delivery_weight != $request->ontime_delivery_weight){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->supplier_bussiness_planning != $request->supplier_bussiness_planning){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->supplier_bussiness_planning_weight != $request->supplier_bussiness_planning_weight){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->rejection_ppm != $request->rejection_ppm){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->rejection_ppm_weight != $request->rejection_ppm_weight){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->quality_system != $request->quality_system){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->quality_system_ranking != $request->quality_system_ranking){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Quality Systems Ranking';
            $history->previous = $lastDocument->quality_system_ranking;
            $history->current = $request->quality_system_ranking;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->car_generated != $request->car_generated){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = '# of CAR generated';
            $history->previous = $lastDocument->car_generated;
            $history->current = $request->car_generated;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->car_generated_weight != $request->car_generated_weight){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = '# of CAR generated Weight';
            $history->previous = $lastDocument->car_generated_weight;
            $history->current = $request->car_generated_weight;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->closure_time != $request->closure_time){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->closure_time_weight != $request->closure_time_weight){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->end_user_satisfaction != $request->end_user_satisfaction){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->end_user_satisfaction_weight != $request->end_user_satisfaction_weight){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->QA_reviewer_feedback != $request->QA_reviewer_feedback){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'QA Reviewer Feedback';
            $history->previous = strip_tags($lastDocument->QA_reviewer_feedback);
            $history->current = strip_tags($request->QA_reviewer_feedback);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->QA_reviewer_comment != $request->QA_reviewer_comment){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'QA Reviewer Comment';
            $history->previous = strip_tags($lastDocument->QA_reviewer_comment);
            $history->current = strip_tags($request->QA_reviewer_comment);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->last_audit_date != $request->last_audit_date){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Last Audit Date';
            $history->previous = $lastDocument->last_audit_date;
            $history->current = Helpers::getdateFormat($request->last_audit_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->next_audit_date != $request->next_audit_date){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'Next Audit Date';
            $history->previous = $lastDocument->next_audit_date;
            $history->current = Helpers::getdateFormat($request->next_audit_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->audit_frequency != $request->audit_frequency){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->last_audit_result != $request->last_audit_result){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->facility_type != $request->facility_type){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->nature_of_employee != $request->nature_of_employee){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->technical_support != $request->technical_support){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->survice_supported != $request->survice_supported){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->reliability != $request->reliability){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->revenue != $request->revenue){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->client_base != $request->client_base){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->previous_audit_result != $request->previous_audit_result){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
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
            $history->action_name = 'Update';
            $history->save();
        }
        if($lastDocument->QA_head_comment != $request->QA_head_comment){
            $history = new SupplierAuditTrail;
            $history->supplier_id = $lastDocument->id;
            $history->activity_type = 'QA Head Comment';
            $history->previous = strip_tags($lastDocument->QA_head_comment);
            $history->current = strip_tags($request->QA_head_comment);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = 'Update';
            $history->save();
        }
        return back();
    }

    public function singleReportShow($id)
    {
        return view('frontend.supplier.supplier-single-report-show', compact('id'));
    }

    public function singleReport(Request $request, $id){
        $data = Supplier::find($id);
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $gridData = SupplierGrid::where('supplier_id', $data->id)->first();            
            $supplierChecklist = SupplierChecklist::where('supplier_id', $id)->get();
            
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.supplier.supplier-single-report', compact(
                'data',
                'gridData',
                'supplierChecklist'
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

            $directoryPath = public_path("user/pdf");
            $filePath = $directoryPath . '/sop' . $id . '.pdf';

            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true); // Recursive creation with read/write permissions
            }  

            $pdf->save($filePath);

            return $pdf->stream('SOP' . $id . '.pdf');
        }
    }

    public function auditTrail(Request $request, $id){
        $audit = SupplierAuditTrail::where('supplier_id', $id)->orderByDESC('id')->paginate(5);
        $today = Carbon::now()->format('d-m-y');
        $document = Supplier::where('id', $id)->first();
        $document->originator = User::where('id', $document->initiator_id)->value('name');

        return view('frontend.supplier.supplier-audit-trail', compact('audit', 'document', 'today'));
    }

    public function auditTrailPdf(Request $request, $id){
        $doc = Supplier::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = SupplierAuditTrail::where('supplier_id', $doc->id)->orderByDesc('id')->get();
    
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.supplier.supplier-audit-trail-pdf', compact('data', 'doc'))
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
    }

    public function approvedByContractGiver(Request $request, $id){
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $supplier = Supplier::find($id);
            $lastDocument = Supplier::find($id);

            $supplier->approvedBy_contract_giver_by = Auth::user()->name;
            $supplier->approvedBy_contract_giver_on = Carbon::now()->format('d-M-Y');
            $supplier->approvedBy_contract_giver_comment = $request->comments;
        
            $history = new SupplierAuditTrail();
            $history->supplier_id = $id;
            $history->activity_type = 'Activity Log';
            $history->previous = "";
            $history->action = 'Approved By Contract Giver';
            $history->current = "";
            $history->comment = $request->comments;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;        
            $history->save();
            $supplier->update();

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
            $supplier->update();
            toastr()->success('Sent to Pending CQA Review After Purchase Sample Request');
            return back();

        } else {
            toastr()->error('E-signature Not match');
            return back();
        } 
    }

    public function linkManufacturerToApprovedManufacturer(Request $request, $id){
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $supplier = Supplier::find($id);
            $lastDocument = Supplier::find($id);

            $supplier->stage = "12";
            $supplier->status = "Approved Manufacturer/Supplier";
            $supplier->manufacture_code_linked_by = Auth::user()->name;
            $supplier->manufacture_code_linked_on = Carbon::now()->format('d-M-Y');
            $supplier->manufacture_code_linked_comment = $request->comments;
        
            $history = new SupplierAuditTrail();
            $history->supplier_id = $id;
            $history->activity_type = 'Activity Log';
            $history->previous = "";
            $history->action = 'Link Manufacturer Code to Material Code through MPN in SAP';
            $history->current = "";
            $history->comment = $request->comments;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Approved Manufacturer/Supplier";
            $history->change_from = $lastDocument->status;        
            $history->save();
            $supplier->update();

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
            $supplier->update();
            toastr()->success('Sent to Approved Manufacturer/Supplier');
            return back();

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


    public function supplierSendStage(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $supplier = Supplier::find($id);
            $lastDocument = Supplier::find($id);
            if ($supplier->stage == 1) {
                    $supplier->stage = "2";
                    $supplier->status = "Pending Initiating Department Update";
                    $supplier->submitted_by = Auth::user()->name;
                    $supplier->submitted_on = Carbon::now()->format('d-M-Y');
                    $supplier->submitted_comment = $request->comments;

                    $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Need for Sourcing of Starting Material';
                    $history->current = "Not Applicable";
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Pending Initiating Department Update";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                    $supplier->update();

                    toastr()->success('Sent to Pending Initiating Department Update');
                    return back();
            }
            if ($supplier->stage == 2) {
                    $supplier->stage = "3";
                    $supplier->status = "Pending Update FROM CQA";
                    $supplier->request_justified_by = Auth::user()->name;
                    $supplier->request_justified_on = Carbon::now()->format('d-M-Y');
                    $supplier->request_justified_comment = $request->comments;

                    $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Request Justified';
                    $history->current = "Not Applicable";
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Pending Update FROM CQA";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                    $supplier->update();
                    
                    toastr()->success('Sent to Pending Supplier Audit');
                    return back();
            }
            if ($supplier->stage == 3) {
                $supplier->stage = "4";
                $supplier->status = "Pending Purchase Sample Request";
                $supplier->prepurchase_sample_by = Auth::user()->name;
                $supplier->prepurchase_sample_on = Carbon::now()->format('d-M-Y');
                $supplier->prepurchase_sample_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Pre-Purchase Sample Required';
                    $history->current = "Not Applicable";
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =  "Pending Purchase Sample Request";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();

                toastr()->success('Sent to Pending Rejction');
                return back();
            }
            if ($supplier->stage == 4) {
                $supplier->stage = "5";
                $supplier->status = "Pending CQA Review After Purchase Sample Request";
                $supplier->pendigPurchaseSampleRequested_by = Auth::user()->name;
                $supplier->pendigPurchaseSampleRequested_on = Carbon::now()->format('d-M-Y');
                $supplier->pendigPurchaseSampleRequested_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Purchase Sample Request Initiated & Acknowledgement By PD';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending CQA Review After Purchase Sample Request";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Pending CFT Review');
                return back();
            }
            if ($supplier->stage == 5) {
                $supplier->stage = "6";
                $supplier->status = "Pending F&D Review";
                $supplier->purchaseSampleanalysis_by = Auth::user()->name;
                $supplier->purchaseSampleanalysis_on = Carbon::now()->format('d-M-Y');
                $supplier->purchaseSampleanalysis_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Purchase Sample Analysis Satisfactory';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending F&D Review";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Pending CFT Review');
                return back();
            }
            if ($supplier->stage == 6) {
                $supplier->stage = "7";
                $supplier->status = "Pending Acknowledgement By Purchase Department";
                $supplier->FdReviewCompleted_by = Auth::user()->name;
                $supplier->FdReviewCompleted_on = Carbon::now()->format('d-M-Y');
                $supplier->FdReviewCompleted_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'F&D Review Completed';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending Acknowledgement By Purchase Department";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Pending CFT Review');
                return back();
            }
            if ($supplier->stage == 7) {
                $supplier->stage = "8";
                $supplier->status = "Pending CQA Final Review";
                $supplier->acknowledgByPD_by = Auth::user()->name;
                $supplier->acknowledgByPD_on = Carbon::now()->format('d-M-Y');
                $supplier->acknowledgByPD_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Acknowledgement By Purchase Department';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending CQA Final Review";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Pending CFT Review');
                return back();
            }
            if ($supplier->stage == 8) {
                $supplier->stage = "10";
                $supplier->status = "Pending Manufacturer Risk Assessment";
                $supplier->requirementFullfilled_by = Auth::user()->name;
                $supplier->requirementFullfilled_on = Carbon::now()->format('d-M-Y');
                $supplier->requirementFullfilled_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'All Requirements Fulfilled';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending Manufacturer Risk Assessment";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Pending CFT Review');
                return back();
            }
            if ($supplier->stage == 10) {
                $supplier->stage = "11";
                $supplier->status = "Pending Manufacturer Audit";
                $supplier->riskRatingObservedAsHigh_by = Auth::user()->name;
                $supplier->riskRatingObservedAsHigh_on = Carbon::now()->format('d-M-Y');
                $supplier->riskRatingObservedAsHigh_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Risk Rating Observed as High/Medium';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending Manufacturer Audit";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Pending CFT Review');
                return back();
            }
            if ($supplier->stage == 11) {
                $supplier->stage = "12";
                $supplier->status = "Approved Manufacturer/Supplier";
                $supplier->manufacturerAuditPassed_by = Auth::user()->name;
                $supplier->manufacturerAuditPassed_on = Carbon::now()->format('d-M-Y');
                $supplier->manufacturerAuditPassed_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Manufacturer Audit Passed';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Approved Manufacturer/Supplier";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Pending CFT Review');
                return back();
            }
            if ($supplier->stage == 12) {
                $supplier->stage = "13";
                $supplier->status = "Pending Manufacturer Risk Assessment";
                $supplier->periodicRevolutionInitiated_by = Auth::user()->name;
                $supplier->periodicRevolutionInitiated_on = Carbon::now()->format('d-M-Y');
                $supplier->periodicRevolutionInitiated_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Initiate Periodic Revaluation';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending Manufacturer Risk Assessment";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Pending CFT Review');
                return back();
            }
            if ($supplier->stage == 13) {
                $supplier->stage = "14";
                $supplier->status = "Pending Manufacturer Audit";
                $supplier->riskRatingObservedAsHighMedium_by = Auth::user()->name;
                $supplier->riskRatingObservedAsHighMedium_on = Carbon::now()->format('d-M-Y');
                $supplier->riskRatingObservedAsHighMedium_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Risk Rating Observed as High/Medium';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending Manufacturer Audit";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Pending CFT Review');
                return back();
            }
            if ($supplier->stage == 14) {
                $supplier->stage = "12";
                $supplier->status = "Approved Manufacturer/Supplier";
                $supplier->pendingManufacturerAuditFailed_by = Auth::user()->name;
                $supplier->pendingManufacturerAuditFailed_on = Carbon::now()->format('d-M-Y');
                $supplier->pendingManufacturerAuditFailed_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Manufacturer Audit Failed';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Approved Manufacturer/Supplier";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Pending CFT Review');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


    public function supplierStageReject(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $supplier = Supplier::find($id);
            $lastDocument = Supplier::find($id);
            if ($supplier->stage == 13) {
                    $supplier->stage = "12";
                    $supplier->status = "Approved Manufacturer/Supplier";
                    $supplier->riskRatingObservedLow_by = Auth::user()->name;
                    $supplier->riskRatingObservedLow_on = Carbon::now()->format('d-M-Y');
                    $supplier->riskRatingObservedLow_comment = $request->comments;

                    $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Risk Rating Observed as Low';
                    $history->current = "Not Applicable";
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "Approved Manufacturer/Supplier";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                    $supplier->update();

                    toastr()->success('Sent to Approved Manufacturer/Supplier');
                    return back();
            }
            if ($supplier->stage == 12) {
                    $supplier->stage = "11";
                    $supplier->status = "Pending Manufacturer Audit";
                    $supplier->requestedToPendingManufacturerAudit_by = Auth::user()->name;
                    $supplier->requestedToPendingManufacturerAudit_on = Carbon::now()->format('d-M-Y');
                    $supplier->requestedToPendingManufacturerAudit_comment = $request->comments;

                    $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Request More Info';
                    $history->current = "Not Applicable";
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending Manufacturer Audit";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                    $supplier->update();
                    
                    toastr()->success('Sent to Pending Manufacturer Audit');
                    return back();
            }
            if ($supplier->stage == 11) {
                $supplier->stage = "10";
                $supplier->status = "Pending Manufacturer Risk Assessment";
                $supplier->requestedToPendigManufacturerRA_by = Auth::user()->name;
                $supplier->requestedToPendigManufacturerRA_on = Carbon::now()->format('d-M-Y');
                $supplier->requestedToPendigManufacturerRA_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Request More Info';
                    $history->current = "Not Applicable";
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending Manufacturer Risk Assessment";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();

                toastr()->success('Sent to Pending Manufacturer Risk Assessment');
                return back();
            }
            if ($supplier->stage == 10) {
                $supplier->stage = "8";
                $supplier->status = "Pending CQA Final Review";
                $supplier->requestedToPendingCQAFinal_by = Auth::user()->name;
                $supplier->submitterequestedToPendingCQAFinal_on = Carbon::now()->format('d-M-Y');
                $supplier->requestedToPendingCQAFinal_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Request More Info';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending CQA Final Review";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Sent to Pending CQA Final Review');
                return back();
            }
            if ($supplier->stage == 8) {
                $supplier->stage = "7";
                $supplier->status = "Pending Acknowledgement By Purchase Department";
                $supplier->requestedToPD_by = Auth::user()->name;
                $supplier->requestedToPD_on = Carbon::now()->format('d-M-Y');
                $supplier->requestedToPD_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Request More Info';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending Acknowledgement By Purchase Department";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Sent to Pending Acknowledgement By Purchase Department');
                return back();
            }
            if ($supplier->stage == 7) {
                $supplier->stage = "6";
                $supplier->status = "Pending F&D Review";
                $supplier->reqquestedToFD_by = Auth::user()->name;
                $supplier->reqquestedToFD_on = Carbon::now()->format('d-M-Y');
                $supplier->reqquestedToFD_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Request More Info';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending F&D Review";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Sent to Pending F&D Review');
                return back();
            }
            if ($supplier->stage == 6) {
                $supplier->stage = "5";
                $supplier->status = "Pending CQA Review After Purchase Sample Request";
                $supplier->requestedToCQA_by = Auth::user()->name;
                $supplier->requestedToCQA_on = Carbon::now()->format('d-M-Y');
                $supplier->requestedToCQA_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Request More Info';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending CQA Review After Purchase Sample Request";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Sent to Pending CQA Review After Purchase Sample Request');
                return back();
            }
            if ($supplier->stage == 5) {
                $supplier->stage = "4";
                $supplier->status = "Pending Purchase Sample Request";
                $supplier->purchaseSampleanalysisNotSatisfactory_by = Auth::user()->name;
                $supplier->purchaseSampleanalysisNotSatisfactory_on = Carbon::now()->format('d-M-Y');
                $supplier->purchaseSampleanalysisNotSatisfactory_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Purchase Sample Analysis Not Satisfactory';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending Purchase Sample Request";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Sent to Pending Purchase Sample Request');
                return back();
            }
            if ($supplier->stage == 4) {
                $supplier->stage = "3";
                $supplier->status = "Pending Update FROM CQA";
                $supplier->requestedToPendingCQA_by = Auth::user()->name;
                $supplier->requestedToPendingCQA_on = Carbon::now()->format('d-M-Y');
                $supplier->requestedToPendingCQA_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Request More Info';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending Update FROM CQA";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Sent to Pending Update FROM CQA');
                return back();
            }
            if ($supplier->stage == 3) {
                $supplier->stage = "2";
                $supplier->status = "Pending Initiating Department Update";
                $supplier->requestedTo_initiating_department_by = Auth::user()->name;
                $supplier->requestedTo_initiating_department_on = Carbon::now()->format('d-M-Y');
                $supplier->requestedTo_initiating_department_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Request More Info';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Pending Initiating Department Update";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Sent to Pending Initiating Department Update');
                return back();
            }
            if ($supplier->stage == 2) {
                $supplier->stage = "1";
                $supplier->status = "Opened";
                $supplier->request_justified_by = Auth::user()->name;
                $supplier->request_justified_on = Carbon::now()->format('d-M-Y');
                $supplier->request_justified_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Request Not Justified';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Opened";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Sent to Opened');
                return back();
            }
            if ($supplier->stage == 1) {
                $supplier->stage = "0";
                $supplier->status = "Closed - Cancelled";
                $supplier->cancelled_by = Auth::user()->name;
                $supplier->cancelled_on = Carbon::now()->format('d-M-Y');
                $supplier->cancelled_comment = $request->comments;

                $history = new SupplierAuditTrail();
                    $history->supplier_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->action = 'Cancel';
                    $history->current = $supplier->submit_by;
                    $history->comment = $request->comments;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Closed - Cancelled";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Plan Proposed';
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
                $supplier->update();
                
                toastr()->success('Sent to Closed - Cancelled');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function sendToPendingCQAReview(Request $request, $id){
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $supplier = Supplier::find($id);
            $lastDocument = Supplier::find($id);

            $supplier->stage = "5";
            $supplier->status = "Pending CQA Review After Purchase Sample Request";
            $supplier->prepurchase_sample_notRequired_by = Auth::user()->name;
            $supplier->prepurchase_sample_notRequired_on = Carbon::now()->format('d-M-Y');
            $supplier->prepurchase_sample_notRequired_comment = $request->comments;
        
            $history = new SupplierAuditTrail();
            $history->supplier_id = $id;
            $history->activity_type = 'Activity Log';
            $history->previous = "";
            $history->action = 'Pre-Purchase Sample Not Required';
            $history->current = "";
            $history->comment = $request->comments;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Pending CQA Review After Purchase Sample Request";
            $history->change_from = $lastDocument->status;        
            $history->save();
            $supplier->update();

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
            $supplier->update();
            toastr()->success('Sent to Pending CQA Review After Purchase Sample Request');
            return back();

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function sendToApprovedManufacturerFromPendingManufacturer(Request $request, $id){
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $supplier = Supplier::find($id);
            $lastDocument = Supplier::find($id);

            $supplier->stage = "12";
            $supplier->status = "Approved Manufacturer/Supplier";
            $supplier->prepurchase_sample_notRequired_by = Auth::user()->name;
            $supplier->prepurchase_sample_notRequired_on = Carbon::now()->format('d-M-Y');
            $supplier->prepurchase_sample_notRequired_comment = $request->comments;
        
            $history = new SupplierAuditTrail();
            $history->supplier_id = $id;
            $history->activity_type = 'Activity Log';
            $history->previous = "";
            $history->action = 'Risk Rating Observed as Low';
            $history->current = "";
            $history->comment = $request->comments;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Approved Manufacturer/Supplier";
            $history->change_from = $lastDocument->status;        
            $history->save();
            $supplier->update();

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
            $supplier->update();
            toastr()->success('Sent to Approved Manufacturer/Supplier');
            return back();

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function manufacturerRejected(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $supplier = Supplier::find($id);
            $lastDocument = Supplier::find($id);

            $supplier->stage = "9";
            $supplier->status = "Manufacturer Rejected";
            $supplier->requiredNotFulfilled_by = Auth::user()->name;
            $supplier->requiredNotFulfilled_on = Carbon::now()->format('d-M-Y');
            $supplier->requiredNotFulfilled_comment = $request->comments;
        
            $history = new SupplierAuditTrail();
            $history->supplier_id = $id;
            $history->activity_type = 'Activity Log';
            $history->previous = "";
            $history->action = 'All Requirements Not Fulfilled';
            $history->current = "";
            $history->comment = $request->comments;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to =   "Manufacturer Rejected";
            $history->change_from = $lastDocument->status;        
            $history->save();
            $supplier->update();

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
            $supplier->update();
            toastr()->success('Sent to Manufacturer Rejected');
            return back();

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function cancelDocument(Request $request, $id){
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $supplier = Supplier::find($id);
            $lastDocument = Supplier::find($id);

            $supplier->stage = "0";
            $supplier->status = "Close - Cancelled";
            $supplier->cancelled_by = Auth::user()->name;
            $supplier->cancelled_on = Carbon::now()->format('d-M-Y');
            $supplier->cancelled_comment = $request->comments;
        
            $history = new SupplierAuditTrail();
            $history->supplier_id = $id;
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
            $supplier->update();

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
            $supplier->update();
            toastr()->success('Document Sent');
            return back();

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function store_audit_review(Request $request, $id)
    {
            $history = new AuditReviewersDetails;
            $history->doc_id = $id;
            $history->user_id = Auth::user()->id;
            $history->type = $request->type;
            $history->reviewer_comment = $request->reviewer_comment;
            $history->reviewer_comment_by = Auth::user()->name;
            $history->reviewer_comment_on = Carbon::now()->toDateString();
            $history->save();

        return redirect()->back();
    }

    public function supplier_child(Request $request, $id)
    {
        $supplierA = Supplier::find($id);
        $cft = [];
        $parent_id = $id;
        $parent_type = "Supplier";
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

        // Debugging to check the revision value


        if ($request->revision == "Action-Item") {
            $supplierA->originator = User::where('id', $supplierA->initiator_id)->value('name');
            return view('frontend.forms.action-item', compact('record_number', 'due_date', 'parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id'));

        }
        // dd($request->revision,$request->revision == "changecontrol");
        if ($request->revision == "changecontrol") {
            $supplierA->originator = User::where('id', $supplierA->initiator_id)->value('name');
            return view('frontend.change-control.new-change-control', compact('record_number', 'due_date', 'parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','hod','cft','pre'));
        }

        if ($request->revision == "capa-child") {
            $supplierA->originator = User::where('id', $supplierA->initiator_id)->value('name');
           return view('frontend.forms.capa', compact('record_number', 'due_date', 'parent_id', 'parent_type', 'old_record', 'cft'));
        }
        if ($request->revision == "deviation") {
         $supplierA->originator = User::where('id', $supplierA->initiator_id)->value('name');
        $pre = Deviation::all();
         return view('frontend.forms.deviation_new', compact('record_number', 'due_date', 'parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','pre'));
     }
     if ($request->revision == "RCA") {
        $supplierA->originator = User::where('id', $supplierA->initiator_id)->value('name');
    //    $pre = Deviation::all();
        return view('frontend.forms.root-cause-analysis', compact('record_number', 'due_date', 'parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','pre'));
    }
    if ($request->revision == "RA") { 
        $supplierA->originator = User::where('id', $supplierA->initiator_id)->value('name');
    //    $pre = Deviation::all();
    $old_record = RiskManagement::select('id', 'division_id', 'record')->get();
        return view('frontend.forms.risk-management', compact('record_number', 'due_date', 'parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','pre','old_record','old_record'));
    }
    if ($request->revision == "SA") {
        $supplierA->originator = User::where('id', $supplierA->initiator_id)->value('name');
    //    $pre = Deviation::all();
    $old_record = RiskManagement::select('id', 'division_id', 'record')->get();
        return view('frontend.New_forms.supplier_audit', compact('record_number', 'due_date', 'parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','pre','old_record','old_record'));
    }
    if ($request->revision == "SCAR") {
        $supplierA->originator = User::where('id', $supplierA->initiator_id)->value('name');
        $supplierData = Supplier::select('id','supplier_name','supplier_products','distribution_sites')->get();
        $supplierName = Supplier::select('id','supplier_name')->get();
        $supplierProduct = Supplier::where('supplier_products' , '!=' , "null")->get();
        $distributionSites = Supplier::where('distribution_sites', '!=', "null")->get();
        $old_record = SCAR::select('id', 'division_id', 'record')->get();
        return view('frontend.scar.scar_new', compact('record_number','supplierName','supplierProduct','distributionSites', 'due_date', 'parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id','pre','old_record','old_record'));
    }

    } 
}
