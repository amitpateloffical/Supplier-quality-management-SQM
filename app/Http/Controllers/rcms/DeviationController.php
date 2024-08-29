<?php

namespace App\Http\Controllers\rcms;


use App\Models\DeviationNewGridData;
use App\Models\DeviationCftsResponse;
use App\Models\RootCauseAnalysis;
use App\Http\Controllers\Controller;
use App\Models\{EffectivenessCheck,LaunchExtension,DeviationGridQrms, RootAuditTrial};
use App\Models\CC;
use App\Models\RootAuditTrail;
use App\Models\ActionItem;
use App\Models\Deviation;
use App\Models\Extension;
use App\Models\{DeviationAuditTrail,ScarAuditTrail,SCAR};
use App\Models\DeviationGrid;
use App\Models\DeviationHistory;
use App\Models\DeviationCft;
use App\Models\AuditReviewersDetails;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Models\Capa;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\RecordNumber;
use App\Models\RoleGroup;
use App\Models\User;
use Helpers;
use Illuminate\Pagination\Paginator;
use PDF;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DeviationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deviation()
    {
        $old_record = Deviation::select('id', 'division_id', 'record')->get();
        $record_number = (RecordNumber::first()->value('counter')) + 1;
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $pre = Deviation::all();
        return response()->view('frontend.forms.deviation_new', compact('record_number', 'formattedDate', 'due_date', 'old_record', 'pre'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//dd($request->all());
        $form_progress = null; // initialize form progress

        if ($request->form_name == 'general')
        {
            $validator = Validator::make($request->all(), [
                'Initiator_Group' => 'required',
                'short_description' => 'required'

            ], [
                'Initiator_Group.required' => 'Department field required!',
                'short_description_required.required' => 'Nature of repeat field required!'
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $form_progress = 'general';
            }
        }


        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return response()->redirect()->back()->withInput();
        }

        $deviation = new Deviation();
        $deviation->form_type = "Deviation";

        $deviation->record = ((RecordNumber::first()->value('counter')) + 1);
        $deviation->initiator_id = Auth::user()->id;

        $deviation->form_progress = isset($form_progress) ? $form_progress : null;

        $deviation->Delay_Justification = $request->Delay_Justification;

        # -------------new-----------
        //  $deviation->record_number = $request->record_number;
        $deviation->division_id = $request->division_id;
        $deviation->parent_id = $request->parent_id;
        $deviation->parent_type = $request->parent_type;
        $deviation->assign_to = $request->assign_to;
        $deviation->Facility = $request->Facility;
        $deviation->intiation_date = $request->intiation_date;
        // dd($request->intiation_date);
        $deviation->Initiator_Group = $request->Initiator_Group;
        $deviation->due_date = $request->due_date;
        // dd($request->due_date);
        $deviation->initiator_group_code = $request->initiator_group_code;
        $deviation->short_description = $request->short_description;
        $deviation->Deviation_date = $request->Deviation_date;
        $deviation->deviation_time = $request->deviation_time;
        $deviation->Deviation_reported_date = $request->Deviation_reported_date;
        // $deviation->Observed_by = $request->Observed_by;
        if (is_array($request->audit_type)) {
            $deviation->audit_type = implode(',', $request->audit_type);
        }


        $deviation->short_description_required = $request->short_description_required;
        $deviation->nature_of_repeat = $request->nature_of_repeat;
        $deviation->others = $request->others;





        $deviation->Description_Deviation = implode(',', $request->Description_Deviation);

        // $deviation->Related_Records1 =  implode(',', $request->related_records);
        $deviation->Immediate_Action = implode(',', $request->Immediate_Action);
        $deviation->Preliminary_Impact = implode(',', $request->Preliminary_Impact);
        $deviation->Product_Details_Required = $request->Product_Details_Required;

        if($deviation->stage == 2){
            $deviation->HOD_Remarks = $request->HOD_Remarks;
        }
        $deviation->Deviation_category = $request->Deviation_category;
        $deviation->Justification_for_categorization = $request->Justification_for_categorization;
        $deviation->Investigation_required = $request->Investigation_required;
        $deviation->capa_required = $request->capa_required;
        $deviation->qrm_required = $request->qrm_required;


        $deviation->Investigation_Details = $request->Investigation_Details;
        $deviation->Customer_notification = $request->Customer_notification;
        $deviation->customers = $request->customers;
        $deviation->QAInitialRemark = $request->QAInitialRemark;

        $deviation->Root_cause = $request->Root_cause;

        $deviation->Facility_Equipment = $request->Facility_Equipment;
        $deviation->Document_Details_Required = $request->Document_Details_Required;
            // // Get the current date
            // $due_date = new Deviation();

            // // Threshold for sending notification (e.g., 7 days)`
            // $threshold_days = 30;

            // // Iterate through the CEO user list
            // $list = Helpers::getCEOUserList();
            // foreach ($list as $u) {
            //     if ($u->q_m_s_divisions_id == $deviation->division_id) {
            //         $email = Helpers::getInitiatorEmail($u->user_id);
            //         if ($email !== null) {
            //             // Calculate remaining days until due date
            //             $due_date = new Deviation($deviation->due_date);
            //             $remaining_days = $due_date->diff($due_date)->days;

            //             // Check if remaining days are within the threshold
            //             if ($remaining_days <= $threshold_days) {
            //                 // Send email notification
            //                 Mail::send(
            //                     'mail.duedateapproaching',
            //                     ['data' => $deviation],
            //                     function ($message) use ($email) {
            //                         $message->to($email)
            //                             ->subject("Activity Performed By " . Auth::user()->name);
            //                     }
            //                 );
            //             }
            //         }
            //     }
            // }



        if ($request->Deviation_category == 'major' || $request->Deviation_category == 'minor' || $request->Deviation_category == 'critical') {
            $list = Helpers::getHeadoperationsUserList();
                    foreach ($list as $u) {
                        if ($u->q_m_s_divisions_id == $deviation->division_id) {
                            $email = Helpers::getInitiatorEmail($u->user_id);
                            if ($email !== null) {
                                 // Add this if statement
                                try {
                                    Mail::send(
                                        'mail.Categorymail',
                                        ['data' => $deviation],
                                        function ($message) use ($email) {
                                            $message->to($email)
                                                ->subject("Document Sent By " . Auth::user()->name);
                                        }
                                    );
                                } catch (\Exception $e) {
                                    //log error
                                }

                            }
                        }
                    }
                }


                if ($request->Deviation_category == 'major' || $request->Deviation_category == 'minor' || $request->Deviation_category == 'critical') {
                    $list = Helpers::getCEOUserList();
                            foreach ($list as $u) {
                                if ($u->q_m_s_divisions_id == $deviation->division_id) {
                                    $email = Helpers::getInitiatorEmail($u->user_id);
                                    if ($email !== null) {
                                         // Add this if statement
                                         try {
                                                Mail::send(
                                                    'mail.Categorymail',
                                                    ['data' => $deviation],
                                                    function ($message) use ($email) {
                                                        $message->to($email)
                                                            ->subject("Document Sent By " . Auth::user()->name);
                                                    }
                                                );
                                            } catch (\Exception $e) {
                                                //log error
                                            }

                                    }
                                }
                            }
                        }
                        if ($request->Deviation_category == 'major' || $request->Deviation_category == 'minor' || $request->Deviation_category == 'critical') {
                            $list = Helpers::getCorporateEHSHeadUserList();
                                    foreach ($list as $u) {
                                        if ($u->q_m_s_divisions_id == $deviation->division_id) {
                                            $email = Helpers::getInitiatorEmail($u->user_id);
                                            if ($email !== null) {
                                                 // Add this if statement
                                                 try {
                                                        Mail::send(
                                                            'mail.Categorymail',
                                                            ['data' => $deviation],
                                                            function ($message) use ($email) {
                                                                $message->to($email)
                                                                    ->subject("Document Sent By " . Auth::user()->name);
                                                            }
                                                        );
                                                    } catch (\Exception $e) {
                                                        //log error
                                                    }

                                            }
                                        }
                                    }
                                }

                                if ($request->Post_Categorization == 'major' || $request->Post_Categorization == 'minor' || $request->Post_Categorization == 'critical') {
                                    $list = Helpers::getHeadoperationsUserList();
                                            foreach ($list as $u) {
                                                if ($u->q_m_s_divisions_id == $deviation->division_id) {
                                                    $email = Helpers::getInitiatorEmail($u->user_id);
                                                    if ($email !== null) {
                                                         // Add this if statement
                                                         try {
                                                            Mail::send(
                                                                'mail.Categorymail',
                                                                ['data' => $deviation],
                                                                function ($message) use ($email) {
                                                                    $message->to($email)
                                                                        ->subject("Document Sent By " . Auth::user()->name);
                                                                }
                                                            );
                                                        } catch (\Exception $e) {
                                                            //log error
                                                        }

                                                    }
                                                }
                                            }
                                        }
                                        if ($request->Post_Categorization == 'major' || $request->Post_Categorization == 'minor' || $request->Post_Categorization == 'critical') {
                                            $list = Helpers::getCEOUserList();
                                                    foreach ($list as $u) {
                                                        if ($u->q_m_s_divisions_id == $deviation->division_id) {
                                                            $email = Helpers::getInitiatorEmail($u->user_id);
                                                            if ($email !== null) {
                                                                 // Add this if statement
                                                                 try {
                                                                        Mail::send(
                                                                            'mail.Categorymail',
                                                                            ['data' => $deviation],
                                                                            function ($message) use ($email) {
                                                                                $message->to($email)
                                                                                    ->subject("Document Sent By " . Auth::user()->name);
                                                                            }
                                                                        );
                                                                    } catch (\Exception $e) {
                                                                        //log error
                                                                    }

                                                            }
                                                        }
                                                    }
                                                }
                                                if ($request->Post_Categorization == 'major' || $request->Post_Categorization == 'minor' || $request->Post_Categorization == 'critical') {
                                                    $list = Helpers::getCorporateEHSHeadUserList();
                                                            foreach ($list as $u) {
                                                                if ($u->q_m_s_divisions_id == $deviation->division_id) {
                                                                    $email = Helpers::getInitiatorEmail($u->user_id);
                                                                    if ($email !== null) {
                                                                         // Add this if statement
                                                                         try {
                                                                                Mail::send(
                                                                                    'mail.Categorymail',
                                                                                    ['data' => $deviation],
                                                                                    function ($message) use ($email) {
                                                                                        $message->to($email)
                                                                                            ->subject("Document Sent By " . Auth::user()->name);
                                                                                    }
                                                                                );
                                                                            } catch (\Exception $e) {
                                                                                //log error
                                                                            }

                                                                    }
                                                                }
                                                            }
                                                        }

        if (!empty ($request->Audit_file)) {
            $files = [];
            if ($request->hasfile('Audit_file')) {
                foreach ($request->file('Audit_file') as $file) {
                    $name = $request->name . 'Audit_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            if (!empty($files)) {
                $deviation->Audit_file = json_encode($files);
            } else {
                $deviation->Audit_file = null;
            }

            $deviation->Audit_file = json_encode($files);
        }

        if (!empty ($request->initial_file)) {
            $files = [];
            if ($request->hasfile('initial_file')) {
                foreach ($request->file('initial_file') as $file) {
                    $name = $request->name . 'initial_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $deviation->initial_file = json_encode($files);
        }

        if (!empty ($request->Initial_attachment)) {
            $files = [];
            if ($request->hasfile('Initial_attachment')) {
                foreach ($request->file('Initial_attachment') as $file) {
                    $name = $request->name . 'Initial_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $deviation->Initial_attachment = json_encode($files);
        }



        if (!empty ($request->QA_attachment)) {
            $files = [];
            if ($request->hasfile('QA_attachment')) {
                foreach ($request->file('QA_attachment') as $file) {
                    $name = $request->name . 'QA_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $deviation->QA_attachment = json_encode($files);
        }
        if (!empty ($request->Capa_attachment)) {
            $files = [];
            if ($request->hasfile('Capa_attachment')) {
                foreach ($request->file('Capa_attachment') as $file) {
                    $name = $request->name . 'Capa_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $deviation->Capa_attachment = json_encode($files);
        }

        if (!empty ($request->QA_attachments)) {
            $files = [];
            if ($request->hasfile('QA_attachments')) {
                foreach ($request->file('QA_attachments') as $file) {
                    $name = $request->name . 'QA_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $deviation->QA_attachments = json_encode($files);
        }

        if (!empty ($request->closure_attachment)) {
            $files = [];
            if ($request->hasfile('closure_attachment')) {
                foreach ($request->file('closure_attachment') as $file) {
                    $name = $request->name . 'closure_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $deviation->closure_attachment = json_encode($files);
        }

        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();



        $deviation->status = 'Opened';
        $deviation->stage = 1;

        $deviation->save();

        $data3 = new DeviationGrid();
        $data3->deviation_grid_id = $deviation->id;
        $data3->type = "Deviation";
        if (!empty($request->facility_name)) {
            $data3->facility_name = serialize($request->facility_name);
        }
        if (!empty($request->IDnumber)) {
            $data3->IDnumber = serialize($request->IDnumber);
        }

        if (!empty($request->Remarks)) {
            $data3->Remarks = serialize($request->Remarks);
        }
        $data3->save();
        $data4 = new DeviationGrid();
        $data4->deviation_grid_id = $deviation->id;
        $data4->type = "Document ";
        if (!empty($request->Number)) {
            $data4->Number = serialize($request->Number);
        }
        if (!empty($request->ReferenceDocumentName)) {
            $data4->ReferenceDocumentName = serialize($request->ReferenceDocumentName);
        }

        if (!empty($request->Document_Remarks)) {
            $data4->Document_Remarks = serialize($request->Document_Remarks);
        }
        $data4->save();

        $data5 = new DeviationGrid();
        $data5->deviation_grid_id = $deviation->id;
        $data5->type = "Product ";
        if (!empty($request->product_name)) {
            $data5->product_name = serialize($request->product_name);
        }
        if (!empty($request->product_stage)) {
            $data5->product_stage = serialize($request->product_stage);
        }

        if (!empty($request->batch_no)) {
            $data5->batch_no = serialize($request->batch_no);
        }
        $data5->save();



        $Cft = new DeviationCft();
        $Cft->deviation_id = $deviation->id;
        $Cft->Production_Review = $request->Production_Review;
        $Cft->Production_person = $request->Production_person;
        $Cft->Production_assessment = $request->Production_assessment;
        $Cft->Production_feedback = $request->Production_feedback;
        $Cft->production_on = $request->production_on;
        $Cft->production_by = $request->production_by;

        $Cft->Warehouse_review = $request->Warehouse_review;
        $Cft->Warehouse_notification = $request->Warehouse_notification;
        $Cft->Warehouse_assessment = $request->Warehouse_assessment;
        $Cft->Warehouse_feedback = $request->Warehouse_feedback;
        $Cft->Warehouse_by = $request->Warehouse_Review_Completed_By;
        $Cft->Warehouse_on = $request->Warehouse_on;

        $Cft->Quality_review = $request->Quality_review;
        $Cft->Quality_Control_Person = $request->Quality_Control_Person;
        $Cft->Quality_Control_assessment = $request->Quality_Control_assessment;
        $Cft->Quality_Control_feedback = $request->Quality_Control_feedback;
        $Cft->Quality_Control_by = $request->Quality_Control_by;
        $Cft->Quality_Control_on = $request->Quality_Control_on;

        $Cft->Quality_Assurance_Review = $request->Quality_Assurance_Review;
        $Cft->QualityAssurance_person = $request->QualityAssurance_person;
        $Cft->QualityAssurance_assessment = $request->QualityAssurance_assessment;
        $Cft->QualityAssurance_feedback = $request->QualityAssurance_feedback;
        $Cft->QualityAssurance_by = $request->QualityAssurance_by;
        $Cft->QualityAssurance_on = $request->QualityAssurance_on;

        $Cft->Engineering_review = $request->Engineering_review;
        $Cft->Engineering_person = $request->Engineering_person;
        $Cft->Engineering_assessment = $request->Engineering_assessment;
        $Cft->Engineering_feedback = $request->Engineering_feedback;
        $Cft->Engineering_by = $request->Engineering_by;
        $Cft->Engineering_on = $request->Engineering_on;

        $Cft->Analytical_Development_review = $request->Analytical_Development_review;
        $Cft->Analytical_Development_person = $request->Analytical_Development_person;
        $Cft->Analytical_Development_assessment = $request->Analytical_Development_assessment;
        $Cft->Analytical_Development_feedback = $request->Analytical_Development_feedback;
        $Cft->Analytical_Development_by = $request->Analytical_Development_by;
        $Cft->Analytical_Development_on = $request->Analytical_Development_on;

        $Cft->Kilo_Lab_review = $request->Kilo_Lab_review;
        $Cft->Kilo_Lab_person = $request->Kilo_Lab_person;
        $Cft->Kilo_Lab_assessment = $request->Kilo_Lab_assessment;
        $Cft->Kilo_Lab_feedback = $request->Kilo_Lab_feedback;
        $Cft->Kilo_Lab_attachment_by = $request->Kilo_Lab_attachment_by;
        $Cft->Kilo_Lab_attachment_on = $request->Kilo_Lab_attachment_on;

        $Cft->Technology_transfer_review = $request->Technology_transfer_review;
        $Cft->Technology_transfer_person = $request->Technology_transfer_person;
        $Cft->Technology_transfer_assessment = $request->Technology_transfer_assessment;
        $Cft->Technology_transfer_feedback = $request->Technology_transfer_feedback;
        $Cft->Technology_transfer_by = $request->Technology_transfer_by;
        $Cft->Technology_transfer_on = $request->Technology_transfer_on;

        $Cft->Environment_Health_review = $request->Environment_Health_review;
        $Cft->Environment_Health_Safety_person = $request->Environment_Health_Safety_person;
        $Cft->Health_Safety_assessment = $request->Health_Safety_assessment;
        $Cft->Health_Safety_feedback = $request->Health_Safety_feedback;
        $Cft->Environment_Health_Safety_by = $request->Environment_Health_Safety_by;
        $Cft->Environment_Health_Safety_on = $request->Environment_Health_Safety_on;

        $Cft->Human_Resource_review = $request->Human_Resource_review;
        $Cft->Human_Resource_person = $request->Human_Resource_person;
        $Cft->Human_Resource_assessment = $request->Human_Resource_assessment;
        $Cft->Human_Resource_feedback = $request->Human_Resource_feedback;
        $Cft->Human_Resource_by = $request->Human_Resource_by;
        $Cft->Human_Resource_on = $request->Human_Resource_on;

        $Cft->Information_Technology_review = $request->Information_Technology_review;
        $Cft->Information_Technology_person = $request->Information_Technology_person;
        $Cft->Information_Technology_assessment = $request->Information_Technology_assessment;
        $Cft->Information_Technology_feedback = $request->Information_Technology_feedback;
        $Cft->Information_Technology_by = $request->Information_Technology_by;
        $Cft->Information_Technology_on = $request->Information_Technology_on;

        $Cft->Project_management_review = $request->Project_management_review;
        $Cft->Project_management_person = $request->Project_management_person;
        $Cft->Project_management_assessment = $request->Project_management_assessment;
        $Cft->Project_management_feedback = $request->Project_management_feedback;
        $Cft->Project_management_by = $request->Project_management_by;
        $Cft->Project_management_on = $request->Project_management_on;

        $Cft->Other1_review = $request->Other1_review;
        $Cft->Other1_person = $request->Other1_person;
        $Cft->Other1_Department_person = $request->Other1_Department_person;
        $Cft->Other1_assessment = $request->Other1_assessment;
        $Cft->Other1_feedback = $request->Other1_feedback;
        $Cft->Other1_by = $request->Other1_by;
        $Cft->Other1_on = $request->Other1_on;

        $Cft->Other2_review = $request->Other2_review;
        $Cft->Other2_person = $request->Other2_person;
        $Cft->Other2_Department_person = $request->Other2_Department_person;
        $Cft->Other2_Assessment = $request->Other2_Assessment;
        $Cft->Other2_feedback = $request->Other2_feedback;
        $Cft->Other2_by = $request->Other2_by;
        $Cft->Other2_on = $request->Other2_on;

        $Cft->Other3_review = $request->Other3_review;
        $Cft->Other3_person = $request->Other3_person;
        $Cft->Other3_Department_person = $request->Other3_Department_person;
        $Cft->Other3_Assessment = $request->Other3_Assessment;
        $Cft->Other3_feedback = $request->Other3_feedback;
        $Cft->Other3_by = $request->Other3_by;
        $Cft->Other3_on = $request->Other3_on;

        $Cft->Other4_review = $request->Other4_review;
        $Cft->Other4_person = $request->Other4_person;
        $Cft->Other4_Department_person = $request->Other4_Department_person;
        $Cft->Other4_Assessment = $request->Other4_Assessment;
        $Cft->Other4_feedback = $request->Other4_feedback;
        $Cft->Other4_by = $request->Other4_by;
        $Cft->Other4_on = $request->Other4_on;

        $Cft->Other5_review = $request->Other5_review;
        $Cft->Other5_person = $request->Other5_person;
        $Cft->Other5_Department_person = $request->Other5_Department_person;
        $Cft->Other5_Assessment = $request->Other5_Assessment;
        $Cft->Other5_feedback = $request->Other5_feedback;
        $Cft->Other5_by = $request->Other5_by;
        $Cft->Other5_on = $request->Other5_on;

        if (!empty ($request->production_attachment)) {
            $files = [];
            if ($request->hasfile('production_attachment')) {
                foreach ($request->file('production_attachment') as $file) {
                    $name = $request->name . 'production_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->production_attachment = json_encode($files);
        }
        if (!empty ($request->Warehouse_attachment)) {
            $files = [];
            if ($request->hasfile('Warehouse_attachment')) {
                foreach ($request->file('Warehouse_attachment') as $file) {
                    $name = $request->name . 'Warehouse_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Warehouse_attachment = json_encode($files);
        }
        if (!empty ($request->Quality_Control_attachment)) {
            $files = [];
            if ($request->hasfile('Quality_Control_attachment')) {
                foreach ($request->file('Quality_Control_attachment') as $file) {
                    $name = $request->name . 'Quality_Control_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Quality_Control_attachment = json_encode($files);
        }
        if (!empty ($request->Quality_Assurance_attachment)) {
            $files = [];
            if ($request->hasfile('Quality_Assurance_attachment')) {
                foreach ($request->file('Quality_Assurance_attachment') as $file) {
                    $name = $request->name . 'Quality_Assurance_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Quality_Assurance_attachment = json_encode($files);
        }
        if (!empty ($request->Engineering_attachment)) {
            $files = [];
            if ($request->hasfile('Engineering_attachment')) {
                foreach ($request->file('Engineering_attachment') as $file) {
                    $name = $request->name . 'Engineering_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Engineering_attachment = json_encode($files);
        }
        if (!empty ($request->Analytical_Development_attachment)) {
            $files = [];
            if ($request->hasfile('Analytical_Development_attachment')) {
                foreach ($request->file('Analytical_Development_attachment') as $file) {
                    $name = $request->name . 'Analytical_Development_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Analytical_Development_attachment = json_encode($files);
        }
        if (!empty ($request->Kilo_Lab_attachment)) {
            $files = [];
            if ($request->hasfile('Kilo_Lab_attachment')) {
                foreach ($request->file('Kilo_Lab_attachment') as $file) {
                    $name = $request->name . 'Kilo_Lab_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Kilo_Lab_attachment = json_encode($files);
        }
        if (!empty ($request->Technology_transfer_attachment)) {
            $files = [];
            if ($request->hasfile('Technology_transfer_attachment')) {
                foreach ($request->file('Technology_transfer_attachment') as $file) {
                    $name = $request->name . 'Technology_transfer_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Technology_transfer_attachment = json_encode($files);
        }
        if (!empty ($request->Environment_Health_Safety_attachment)) {
            $files = [];
            if ($request->hasfile('Environment_Health_Safety_attachment')) {
                foreach ($request->file('Environment_Health_Safety_attachment') as $file) {
                    $name = $request->name . 'Environment_Health_Safety_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Environment_Health_Safety_attachment = json_encode($files);
        }
        if (!empty ($request->Human_Resource_attachment)) {
            $files = [];
            if ($request->hasfile('Human_Resource_attachment')) {
                foreach ($request->file('Human_Resource_attachment') as $file) {
                    $name = $request->name . 'Human_Resource_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Human_Resource_attachment = json_encode($files);
        }
        if (!empty ($request->Information_Technology_attachment)) {
            $files = [];
            if ($request->hasfile('Information_Technology_attachment')) {
                foreach ($request->file('Information_Technology_attachment') as $file) {
                    $name = $request->name . 'Information_Technology_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Information_Technology_attachment = json_encode($files);
        }
        if (!empty ($request->Project_management_attachment)) {
            $files = [];
            if ($request->hasfile('Project_management_attachment')) {
                foreach ($request->file('Project_management_attachment') as $file) {
                    $name = $request->name . 'Project_management_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Project_management_attachment = json_encode($files);
        }
        if (!empty ($request->Other1_attachment)) {
            $files = [];
            if ($request->hasfile('Other1_attachment')) {
                foreach ($request->file('Other1_attachment') as $file) {
                    $name = $request->name . 'Other1_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Other1_attachment = json_encode($files);
        }
        if (!empty ($request->Other2_attachment)) {
            $files = [];
            if ($request->hasfile('Other2_attachment')) {
                foreach ($request->file('Other2_attachment') as $file) {
                    $name = $request->name . 'Other2_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Other2_attachment = json_encode($files);
        }
        if (!empty ($request->Other3_attachment)) {
            $files = [];
            if ($request->hasfile('Other3_attachment')) {
                foreach ($request->file('Other3_attachment') as $file) {
                    $name = $request->name . 'Other3_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Other3_attachment = json_encode($files);
        }
        if (!empty ($request->Other4_attachment)) {
            $files = [];
            if ($request->hasfile('Other4_attachment')) {
                foreach ($request->file('Other4_attachment') as $file) {
                    $name = $request->name . 'Other4_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Other4_attachment = json_encode($files);
        }
        if (!empty ($request->Other5_attachment)) {
            $files = [];
            if ($request->hasfile('Other5_attachment')) {
                foreach ($request->file('Other5_attachment') as $file) {
                    $name = $request->name . 'Other5_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $Cft->Other5_attachment = json_encode($files);
        }


        $Cft->save();

        // $data4 = new DeviationGrid();
        // $data4->Number = $deviation->id;
        // $data4->type = "Deviation";
        // if (!empty ($request->Number)) {
        //     $data4->Number = serialize($request->Number);
        // }
        // if (!empty ($request->ReferenceDocumentName)) {
        //     $data4->ReferenceDocumentName = serialize($request->ReferenceDocumentName);
        // }
        // $data4->save();

        // $data5 = new DeviationGrid();
        // $data5->nameofproduct = $deviation->id;
        // $data5->type = "Deviation";
        // if (!empty ($request->nameofproduct)) {
        //     $data5->nameofproduct = serialize($request->nameofproduct);
        // }
        // if (!empty ($request->ExpiryDate)) {
        //     $data5->ExpiryDate = serialize($request->ExpiryDate);
        // }
        // $data5->save();

            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Initiator';
            $history->previous = "Null";
            $history->current = Auth::user()->name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();

            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Due Date';
            $history->previous = "Null";
            $history->current = Helpers::getDateFormat($deviation->due_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();

            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Date of Initiation';
            $history->previous = "Null";
            $history->current = Carbon::now()->format('d-M-Y');
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();

        if (!empty ($request->short_description)){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Short Description';
            $history->previous = "Null";
            $history->current = $deviation->short_description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty ($request->Initiator_Group)){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Department';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorGroupFullName($deviation->Initiator_Group);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if (!empty ($request->Deviation_date)){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Deviation Observed On';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($deviation->Deviation_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty ($request->deviation_time)){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Deviation Observed On (Time)';
            $history->previous = "Null";
            $history->current = $deviation->deviation_time;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty ($request->Delay_Justification)){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Delay Justification';
            $history->previous = "Null";
            $history->current = $deviation->Delay_Justification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty ($request->Facility)){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Deviation Observed By';
            $history->previous = "Null";
            $history->current = $deviation->Facility;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }

        if (is_array($request->Facility) && $request->Facility[0] !== null){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Observed by';
            $history->previous = "Null";
            $history->current = $deviation->Facility;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if (!empty ($request->Deviation_reported_date)){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Deviation Reported on';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($deviation->Deviation_reported_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if ($request->audit_type[0] !== null){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Deviation Related To';
            $history->previous = "Null";
            $history->current = $deviation->audit_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if (!empty ($request->others)){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Others';
            $history->previous = "Null";
            $history->current = $deviation->others;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->action_name = 'Create';
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->save();
        }
        if (!empty ($request->Facility_Equipment)){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Facility/ Equipment/ Instrument/ System Details Required?';
            $history->previous = "Null";
            $history->current = $deviation->Facility_Equipment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->action_name = 'Create';
            $history->save();
        }
        if (!empty ($request->Document_Details_Required)){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Document Details Required';
            $history->previous = "Null";
            $history->current = $deviation->Document_Details_Required;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->action_name = 'Create';
            $history->save();
        }
        if ($request->Description_Deviation[0] !== null){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Description of Deviation';
            $history->previous = "Null";
            $history->current = $deviation->Description_Deviation;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->action_name = 'Create';
            $history->save();
        }
        if ($request->Immediate_Action[0] !== null){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Immediate Action (if any)';
            $history->previous = "Null";
            $history->current = $deviation->Immediate_Action;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->action_name = 'Create';
            $history->save();
        }
        if ($request->Preliminary_Impact[0] !== null){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Preliminary Impact of Deviation';
            $history->previous = "Null";
            $history->current = $deviation->Preliminary_Impact;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->action_name = 'Create';
            $history->save();
        }

        if (!empty($request->initial_file)){
            $history = new DeviationAuditTrail();
            $history->deviation_id = $deviation->id;
            $history->activity_type = 'Initial Attachments';
            $history->previous = "Null";
            $history->current = $deviation->initial_file;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->change_to =   "Opened";
            $history->change_from = "Initiation";
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $deviation->status;
            $history->action_name = 'Create';
            $history->save();
        }

        //if (!empty($request->Audit_file)){
        //    $history = new DeviationAuditTrail();
        //    $history->deviation_id = $deviation->id;
        //    $history->activity_type = 'HOD Attachments';
        //    $history->previous = "Null";
        //    $history->current = $deviation->Audit_file;
        //    $history->comment = "Not Applicable";
        //    $history->user_id = Auth::user()->id;
        //    $history->change_to =   "Opened";
        //    $history->change_from = "Initiation";
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $deviation->status;
        //    $history->action_name = 'Create';
        //    $history->save();
        //}

        //if (!empty($request->Initial_attachment)){
        //    $history = new DeviationAuditTrail();
        //    $history->deviation_id = $deviation->id;
        //    $history->activity_type = 'QA Initial Attachments';
        //    $history->previous = "Null";
        //    $history->current = $deviation->Initial_attachment;
        //    $history->comment = "Not Applicable";
        //    $history->user_id = Auth::user()->id;
        //    $history->change_to =   "Opened";
        //    $history->change_from = "Initiation";
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $deviation->status;
        //    $history->action_name = 'Create';
        //    $history->save();
        //}

        //if (!empty($request->Other5_attachment)){
        //    $history = new DeviationAuditTrail();
        //    $history->deviation_id = $deviation->id;
        //    $history->activity_type = 'CFT Attachments';
        //    $history->previous = "Null";
        //    $history->current = $deviation->Other5_attachment;
        //    $history->comment = "Not Applicable";
        //    $history->user_id = Auth::user()->id;
        //    $history->change_to =   "Opened";
        //    $history->change_from = "Initiation";
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $deviation->status;
        //    $history->action_name = 'Create';
        //    $history->save();
        //}

        //if (!empty($request->QA_attachments)){
        //    $history = new DeviationAuditTrail();
        //    $history->deviation_id = $deviation->id;
        //    $history->activity_type = 'QA Attachments';
        //    $history->previous = "Null";
        //    $history->current = $deviation->QA_attachments;
        //    $history->comment = "Not Applicable";
        //    $history->user_id = Auth::user()->id;
        //    $history->change_to =   "Opened";
        //    $history->change_from = "Initiation";
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $deviation->status;
        //    $history->action_name = 'Create';
        //    $history->save();
        //}

        //if (!empty($request->initiator_final_attachments)){
        //    $history = new DeviationAuditTrail();
        //    $history->deviation_id = $deviation->id;
        //    $history->activity_type = 'Initiator Final Attachments';
        //    $history->previous = "Null";
        //    $history->current = $deviation->initiator_final_attachments;
        //    $history->comment = "Not Applicable";
        //    $history->user_id = Auth::user()->id;
        //    $history->change_to =   "Opened";
        //    $history->change_from = "Initiation";
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $deviation->status;
        //    $history->action_name = 'Create';
        //    $history->save();
        //}

        //if (!empty($request->hod_final_attachments)){
        //    $history = new DeviationAuditTrail();
        //    $history->deviation_id = $deviation->id;
        //    $history->activity_type = 'HOD Final Attachments';
        //    $history->previous = "Null";
        //    $history->current = $deviation->hod_final_attachments;
        //    $history->comment = "Not Applicable";
        //    $history->user_id = Auth::user()->id;
        //    $history->change_to =   "Opened";
        //    $history->change_from = "Initiation";
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $deviation->status;
        //    $history->action_name = 'Create';
        //    $history->save();
        //}

        //if (!empty($request->qa_final_attachments)){
        //    $history = new DeviationAuditTrail();
        //    $history->deviation_id = $deviation->id;
        //    $history->activity_type = 'QA Final Attachments';
        //    $history->previous = "Null";
        //    $history->current = $deviation->qa_final_attachments;
        //    $history->comment = "Not Applicable";
        //    $history->user_id = Auth::user()->id;
        //    $history->change_to =   "Opened";
        //    $history->change_from = "Initiation";
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $deviation->status;
        //    $history->action_name = 'Create';
        //    $history->save();
        //}

        //if (!empty($request->closure_attachment)){
        //    $history = new DeviationAuditTrail();
        //    $history->deviation_id = $deviation->id;
        //    $history->activity_type = 'Closure Attachments';
        //    $history->previous = "Null";
        //    $history->current = $deviation->closure_attachment;
        //    $history->comment = "Not Applicable";
        //    $history->user_id = Auth::user()->id;
        //    $history->change_to =   "Opened";
        //    $history->change_from = "Initiation";
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $deviation->status;
        //    $history->action_name = 'Create';
        //    $history->save();
        //}

        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function devshow($id)
    {
        $old_record = Deviation::select('id', 'division_id', 'record')->get();
        $data = Deviation::find($id);
        $userData = User::all();
        $data1 = DeviationCft::where('deviation_id', $id)->latest()->first();
        // return $data1;
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_id)->value('name');
        $grid_data = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Deviation")->first();
        $grid_data1 = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Document")->first();
        $grid_data2 = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Product")->first();
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $pre = Deviation::all();
        $divisionName = DB::table('q_m_s_divisions')->where('id', $data->division_id)->value('name');
        $deviationNewGrid = DeviationNewGridData::where('deviation_id', $id)->latest()->first();

        $investigation_data = DeviationNewGridData::where(['deviation_id' => $id, 'identifier' => 'investication'])->first();
        $root_cause_data = DeviationNewGridData::where(['deviation_id' => $id, 'identifier' => 'rootCause'])->first();
        $why_data = DeviationNewGridData::where(['deviation_id' => $id, 'identifier' => 'why'])->first();
        $fishbone_data = DeviationNewGridData::where(['deviation_id' => $id, 'identifier' => 'fishbone'])->first();

        $grid_data_qrms = DeviationGridQrms::where(['deviation_id' => $id, 'identifier' => 'failure_mode_qrms'])->first();
        $grid_data_matrix_qrms = DeviationGridQrms::where(['deviation_id' => $id, 'identifier' => 'matrix_qrms'])->first();

        $capaExtension = LaunchExtension::where(['deviation_id' => $id, "identifier" => "Capa"])->first();
        $qrmExtension = LaunchExtension::where(['deviation_id' => $id, "identifier" => "QRM"])->first();
        $investigationExtension = LaunchExtension::where(['deviation_id' => $id, "identifier" => "Investigation"])->first();
        $deviationExtension = LaunchExtension::where(['deviation_id' => $id, "identifier" => "Deviation"])->first();

        $getCft = DeviationCft::find($id);
        return view('frontend.forms.deviation_view', compact('data','userData', 'grid_data_qrms','grid_data_matrix_qrms', 'capaExtension','qrmExtension','investigationExtension','deviationExtension', 'old_record', 'pre', 'data1', 'divisionName','grid_data','grid_data1', 'deviationNewGrid','grid_data2','investigation_data','root_cause_data', 'why_data', 'fishbone_data', 'getCft'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $form_progress = null;
        $lastDeviation = deviation::find($id);
        $deviation = deviation::find($id);
        $deviation->Delay_Justification = $request->Delay_Justification;

        if ($request->Deviation_category == 'major' || $request->Deviation_category == 'critical')
        {
            $deviation->Investigation_required = "yes";
            $deviation->capa_required = "yes";
            $deviation->qrm_required = "yes";
        }

        if ($request->Deviation_category == 'minor')
        {
            $deviation->Investigation_required = $request->Investigation_required;
            $deviation->capa_required = $request->capa_required;
            $deviation->qrm_required = $request->qrm_required;
        }

        if ($request->form_name == 'general-open')
        {
            $validator = Validator::make($request->all(), [
                'Initiator_Group' => 'required',
                'short_description' => 'required',

                'Deviation_date' => 'required',
                'deviation_time' => 'required',
                'Deviation_reported_date' => 'required',
                'Delay_Justification' => [
                    function ($attribute, $value, $fail) use ($request) {
                        $deviation_date = Carbon::parse($request->Deviation_date);
                        $reported_date = Carbon::parse($request->Deviation_reported_date);
                        $diff_in_days = $reported_date->diffInDays($deviation_date);
                        if ($diff_in_days !== 0) {
                            if(!$request->Delay_Justification){
                                $fail('The Delay Justification is required!');
                            }
                        }
                    },
                ],
                'audit_type' => [
                    'required',
                    'array',
                    function($attribute, $value, $fail) {
                        if (count($value) === 1 && reset($value) === null) {
                            return $fail($attribute.' must not contain only null values.');
                        }
                    },
                ],
                'Facility_Equipment' => 'required|in:yes,no',
                'facility_name' => [
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->input('Facility_Equipment') === 'yes' && (count($value) === 1 && reset($value) === null)) {
                            $fail('The Facility name is required when Facility Equipment is yes.');
                        }
                    },
                ],
                'IDnumber' => [
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->input('Facility_Equipment') === 'yes' && (count($value) === 1 && reset($value) === null)) {
                            $fail('The ID Number field is required when Facility Equipment is yes.');
                        }
                    },
                ],
                'Document_Details_Required' => 'required|in:yes,no',
                'Product_Details_Required' => 'required|in:yes,no',
                'Number' => [
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->input('Document_Details_Required') === 'yes' && (count($value) === 1 && reset($value) === null)) {
                            $fail('The Document Number field is required when Document Details Required is yes.');
                        }
                    },
                ],
                'ReferenceDocumentName' => [
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->input('Document_Details_Required') === 'yes' && (count($value) === 1 && reset($value) === null)) {
                            $fail('The Referrence Document Number field is required when Document Details Required is yes.');
                        }
                    },
                ],
                'Description_Deviation' => [
                    'required',
                    'array',
                    function($attribute, $value, $fail) {
                        if (count($value) === 1 && reset($value) === null) {
                            return $fail('Description of deviation must not be empty!.');
                        }
                    },
                ],
                'Immediate_Action' => [
                    'required',
                    'array',
                    function($attribute, $value, $fail) {
                        if (count($value) === 1 && reset($value) === null) {
                            return $fail('Immediate Action field must not be empty!.');
                        }
                    },
                ],
                'Preliminary_Impact' => [
                    'required',
                    'array',
                    function($attribute, $value, $fail) {
                        if (count($value) === 1 && reset($value) === null) {
                            return $fail('Preliminary Impact field must not be empty!.');
                        }
                    },
                ],
            ], [
                'audit_type' => 'Deviation related to field required!'
            ]);

            $validator->sometimes('others', 'required|string|min:1', function ($input) {
                return in_array('Anyother(specify)', explode(',', $input->audit_type[0]));
            });

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $form_progress = 'general-open';
            }
        }
        if ($request->form_name == 'qa')
        {
            $validator = Validator::make($request->all(), [
                // 'Justification_for_categorization' => 'required',
                'short_description_required' => 'required|in:Yes,No',
                'nature_of_repeat' => 'required_if:short_description_required,Yes',
                'Investigation_Details' => 'required_if:Investigation_required,yes',
                'QAInitialRemark' => 'required'
            ], [
                'short_description_required.required' => 'Nature of Repeat required!',
                'nature_of_repeat.required' =>  'The nature of repeat field is required when nature of repeat is Yes.',
                'audit_type' => 'Deviation related to field required!'
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $form_progress = 'qa';
            }
        }
        if ($request->form_name == 'pending-initiator')
        {
            $validator = Validator::make($request->all(), [
                'initiator_final_remarks' => 'required'
            ], [
                'initiator_final_remarks.required' => 'Initiator Final Remarks  required!'
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $form_progress = 'pending-initiator';
            }
        }

        if ($request->form_name == 'hod-final')
        {
            $validator = Validator::make($request->all(), [
                'hod_final_remarks' => 'required'
            ], [
                'hod_final_remarks.required' => 'HOD Final Remarks  required!'
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $form_progress = 'hod-final';
            }
        }
        if ($request->form_name == 'qa-final-remark')
        {
            $validator = Validator::make($request->all(), [
                'qa_final_remarks' => 'required'
            ], [
                'qa_final_remarks.required' => 'QA Final Remarks  required!'
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $form_progress = 'qa-final-remark';
            }
        }
        if ($request->form_name == 'qah-des')
        {
            $validator = Validator::make($request->all(), [
                'Disposition_Batch' => 'required',
                'Closure_Comments' => 'required'
            ], [
                'Disposition_Batch.required' => 'Disposition of Batch   required!',
                'Closure_Comments.required' => 'Closure Comments required!'
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $form_progress = 'qah-des';
            }
        }

        if ($request->form_name == 'capa')
        {

            // ============ capa ======================
        if ($request->form_name == 'capa')
        {
            if($request->source_doc!=""){
                $deviation->capa_number = $request->capa_number ? $request->capa_number : $deviation->capa_number;
                $deviation->department_capa = $request->department_capa ? $request->department_capa : $deviation->department_capa;
                $deviation->source_of_capa = $request->source_of_capa ? $request->source_of_capa : $deviation->source_of_capa;
                $deviation->capa_others = $request->capa_others ? $request->capa_others : $deviation->capa_others;
                $deviation->source_doc = $request->source_doc ? $request->source_doc : $deviation->source_doc;
                $deviation->Description_of_Discrepancy = $request->Description_of_Discrepancy ? $request->Description_of_Discrepancy : $deviation->Description_of_Discrepancy;
                $deviation->capa_root_cause = $request->capa_root_cause ? $request->capa_root_cause : $deviation->capa_root_cause;
                $deviation->Immediate_Action_Take = $request->Immediate_Action_Take ? $request->Immediate_Action_Take : $deviation->Immediate_Action_Take;
                $deviation->Corrective_Action_Details = $request->Corrective_Action_Details ? $request->Corrective_Action_Details : $deviation->Corrective_Action_Details;
                $deviation->Preventive_Action_Details = $request->Preventive_Action_Details ? $request->Preventive_Action_Details : $deviation->Preventive_Action_Details;
                $deviation->capa_completed_date = $request->capa_completed_date ? $request->capa_completed_date : $deviation->capa_completed_date;
                $deviation->Interim_Control = $request->Interim_Control ? $request->Interim_Control : $deviation->Interim_Control;
                $deviation->Corrective_Action_Taken = $request->Corrective_Action_Taken ? $request->Corrective_Action_Taken : $deviation->Corrective_Action_Taken;
                $deviation->Preventive_action_Taken = $request->Preventive_action_Taken ? $request->Preventive_action_Taken : $deviation->Preventive_action_Taken;
                $deviation->CAPA_Closure_Comments = $request->CAPA_Closure_Comments ? $request->CAPA_Closure_Comments : $deviation->CAPA_Closure_Comments;

                 if (!empty ($request->CAPA_Closure_attachment)) {
                    $files = [];
                    if ($request->hasfile('CAPA_Closure_attachment')) {

                        foreach ($request->file('CAPA_Closure_attachment') as $file) {
                            $name = 'capa_closure_attachment-' . time() . '.' . $file->getClientOriginalExtension();
                            $file->move('upload/', $name);
                            $files[] = $name;
                        }
                    }
                    $deviation->CAPA_Closure_attachment = json_encode($files);

                }
                $deviation->update();
                toastr()->success('Document Sent');
                return back();
                }


                $validator = Validator::make($request->all(), [
                    'capa_root_cause' => 'required',
                    'Post_Categorization' => 'required'
                ],  [
                    // 'CAPA_Rquired.required' => 'Capa required field cannot be empty!',
                ]);

                if ($validator->fails()) {
                    return back()
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    $form_progress = 'capa';
                }

            }



        }

        if ($request->form_name == 'qa-final')
        {
            $form_progress = 'capa';
        }

        if ($request->form_name == 'qah')
        {
            if($deviation->stage == 10){
                $validator = Validator::make($request->all(), [
                    'Closure_Comments' => 'required',
                    'Disposition_Batch' => 'required',
                ]);

                if ($validator->fails()) {
                    return back()
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    $form_progress = 'qah';
                }
            }

        }

        $deviation->assign_to = $request->assign_to;
        $deviation->Initiator_Group = $request->Initiator_Group;

        if ($deviation->stage < 3) {
            $deviation->short_description = $request->short_description;
        } else {
            $deviation->short_description = $deviation->short_description;
        }
        $deviation->initiator_group_code = $request->initiator_group_code;
        $deviation->Deviation_reported_date = $request->Deviation_reported_date;
        $deviation->Deviation_date = $request->Deviation_date;
        $deviation->deviation_time = $request->deviation_time;
        $deviation->Delay_Justification = $request->Delay_Justification;
        $deviation->audit_type = implode(',', $request->audit_type);

        $deviation->others = $request->others;

        $deviation->Description_Deviation = implode(',', $request->Description_Deviation);
        if ($request->related_records) {
            $deviation->Related_Records1 =  implode(',', $request->related_records);
        }
        $deviation->Facility = $request->Facility;


        $deviation->Immediate_Action = implode(',', $request->Immediate_Action);
        $deviation->Preliminary_Impact = implode(',', $request->Preliminary_Impact);
        $deviation->Product_Details_Required = $request->Product_Details_Required;


        if($deviation->stage == 2){
            $deviation->HOD_Remarks = $request->HOD_Remarks;
        }
        $deviation->Justification_for_categorization = !empty($request->Justification_for_categorization) ? $request->Justification_for_categorization : $deviation->Justification_for_categorization;

        $deviation->Investigation_Details = !empty($request->Investigation_Details) ? $request->Investigation_Details : $deviation->Investigation_Details;

        $deviation->QAInitialRemark = $request->QAInitialRemark;
        $deviation->Root_cause = $request->Root_cause;

        $deviation->Conclusion = $request->Conclusion;
        $deviation->Identified_Risk = $request->Identified_Risk;
        $deviation->severity_rate = $request->severity_rate ? $request->severity_rate : $deviation->severity_rate;
        $deviation->Occurrence = $request->Occurrence ? $request->Occurrence : $deviation->Occurrence;
        $deviation->detection = $request->detection ? $request->detection: $deviation->detection;

        $newDataGridqrms = DeviationGridQrms::where(['deviation_id' => $id, 'identifier' =>
        'failure_mode_qrms'])->firstOrCreate();
        $newDataGridqrms->deviation_id = $id;
        $newDataGridqrms->identifier = 'failure_mode_qrms';
        $newDataGridqrms->data = $request->failure_mode_qrms;
        $newDataGridqrms->save();

        $matrixDataGridqrms = DeviationGridQrms::where(['deviation_id' => $id, 'identifier' => 'matrix_qrms'])->firstOrCreate();
        $matrixDataGridqrms->deviation_id = $id;
        $matrixDataGridqrms->identifier = 'matrix_qrms';
        $matrixDataGridqrms->data = $request->matrix_qrms;
        $matrixDataGridqrms->save();

        $deviation->Deviation_category = !empty($request->Deviation_category) ? $request->Deviation_category : $deviation->Deviation_category;
        $deviation->Post_Categorization = !empty($request->Post_Categorization) ? $request->Post_Categorization : $deviation->Post_Categorization;
        $deviation->Investigation_Of_Review = !empty($request->Investigation_Of_Review) ? $request->Investigation_Of_Review : $deviation->Investigation_Of_Review;
        $deviation->QA_Feedbacks = $request->has('QA_Feedbacks') ? $request->QA_Feedbacks : $deviation->QA_Feedbacks;
        $deviation->Closure_Comments = $request->Closure_Comments;
        $deviation->Disposition_Batch = $request->Disposition_Batch;
        $deviation->Facility_Equipment = $request->Facility_Equipment;
        $deviation->Document_Details_Required = $request->Document_Details_Required;

        if ($deviation->stage == 3)
        {
            $deviation->short_description_required = $request->short_description_required;
            $deviation->nature_of_repeat = $request->nature_of_repeat;
            $deviation->Customer_notification = $request->Customer_notification;
            $deviation->QAInitialRemark = $request->QAInitialRemark;
        }


        if($deviation->stage == 10){
            $deviation->Post_Categorization = $request->Post_Categorization;
            $deviation->Investigation_Of_Review = $request->Investigation_Of_Review;
            $deviation->QA_Feedbacks = $request->QA_Feedbacks;
            $deviation->Closure_Comments = $request->Closure_Comments;
            $deviation->Disposition_Batch = $request->Disposition_Batch;

            //new code navneet

            $files = is_array($request->existing_clouser_files) ? $request->existing_clouser_files : null;

            if (!empty($request->closure_attachment)) {
                if ($deviation->closure_attachment) {
                    $existingFiles = json_decode($deviation->closure_attachment, true); // Convert to associative array
                    if (is_array($existingFiles)) {
                        $files = $existingFiles;
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
            $deviation->closure_attachment = !empty($files) ? json_encode($files) : null;

            //$files = is_array($request->existing_closure_attachment) ? $request->existing_closure_attachment : [];
            //if (!empty ($request->closure_attachment)) {
            //    if ($deviation->closure_attachment) {
            //        $existingFiles = json_decode($deviation->closure_attachment, true); // Convert to associative array
            //        if (is_array($existingFiles)) {
            //            $files = $existingFiles;
            //        }
            //        // $files = is_array(json_decode($deviation->closure_attachment)) ? $deviation->closure_attachment : [];
            //    }

            //    if ($request->hasfile('closure_attachment')) {
            //        foreach ($request->file('closure_attachment') as $file) {
            //            $name = $request->name . 'closure_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
            //            $file->move('upload/', $name);
            //            $files[] = $name;
            //        }
            //    }
            //}
            //$deviation->closure_attachment = json_encode($files);
        }


        $getCft = DeviationCft::find($id);
        $Cft = DeviationCft::withoutTrashed()->where('deviation_id', $id)->first();
        if($deviation->stage == 3 || $deviation->stage == 4 ){


            if (!$form_progress) {
                $form_progress = 'cft';
            }



            $Cft = DeviationCft::withoutTrashed()->where('deviation_id', $id)->first();

            if($Cft && $deviation->stage == 4 ){
                $Cft->Production_Review = $request->Production_Review == null ? $Cft->Production_Review : $request->Production_Review;
                $Cft->Production_person = $request->Production_person == null ? $Cft->Production_person : $request->Production_Review;
                $Cft->Warehouse_review = $request->Warehouse_review == null ? $Cft->Warehouse_review : $request->Warehouse_review;
                $Cft->Warehouse_notification = $request->Warehouse_notification == null ? $Cft->Warehouse_notification : $request->Warehouse_notification;
                $Cft->Quality_review = $request->Quality_review == null ? $Cft->Quality_review : $request->Quality_review;;
                $Cft->Quality_Control_Person = $request->Quality_Control_Person == null ? $Cft->Quality_Control_Person : $request->Quality_Control_Person;
                $Cft->Quality_Assurance_Review = $request->Quality_Assurance_Review == null ? $Cft->Quality_Assurance_Review : $request->Quality_Assurance_Review;
                $Cft->QualityAssurance_person = $request->QualityAssurance_person == null ? $Cft->QualityAssurance_person : $request->QualityAssurance_person;

                $Cft->Engineering_review = $request->Engineering_review == null ? $Cft->Engineering_review : $request->Engineering_review;
                $Cft->Engineering_person = $request->Engineering_person == null ? $Cft->Engineering_person : $request->Engineering_person;
                $Cft->Analytical_Development_review = $request->Analytical_Development_review == null ? $Cft->Analytical_Development_review : $request->Analytical_Development_review;
                $Cft->Analytical_Development_person = $request->Analytical_Development_person == null ? $Cft->Analytical_Development_person : $request->Analytical_Development_person;
                $Cft->Kilo_Lab_review = $request->Kilo_Lab_review == null ? $Cft->Kilo_Lab_review : $request->Kilo_Lab_review;
                $Cft->Kilo_Lab_person = $request->Kilo_Lab_person == null ? $Cft->Kilo_Lab_person : $request->Kilo_Lab_person;
                $Cft->Technology_transfer_review = $request->Technology_transfer_review == null ? $Cft->Technology_transfer_review : $request->Technology_transfer_review;
                $Cft->Technology_transfer_person = $request->Technology_transfer_person == null ? $Cft->Technology_transfer_person : $request->Technology_transfer_person;
                $Cft->Environment_Health_review = $request->Environment_Health_review == null ? $Cft->Environment_Health_review : $request->Environment_Health_review;
                $Cft->Environment_Health_Safety_person = $request->Environment_Health_Safety_person == null ? $Cft->Environment_Health_Safety_person : $request->Environment_Health_Safety_person;
                $Cft->Human_Resource_review = $request->Human_Resource_review == null ? $Cft->Human_Resource_review : $request->Human_Resource_review;
                $Cft->Human_Resource_person = $request->Human_Resource_person == null ? $Cft->Human_Resource_person : $request->Human_Resource_person;
                $Cft->Project_management_review = $request->Project_management_review == null ? $Cft->Project_management_review : $request->Project_management_review;
                $Cft->Project_management_person = $request->Project_management_person == null ? $Cft->Project_management_person : $request->Project_management_person;
                $Cft->Information_Technology_review = $request->Information_Technology_review == null ? $Cft->Information_Technology_review : $request->Information_Technology_review;
                $Cft->Information_Technology_person = $request->Information_Technology_person == null ? $Cft->Information_Technology_person : $request->Information_Technology_person;
                $Cft->Other1_review = $request->Other1_review  == null ? $Cft->Other1_review : $request->Other1_review;
                $Cft->Other1_person = $request->Other1_person  == null ? $Cft->Other1_person : $request->Other1_person;
                $Cft->Other1_Department_person = $request->Other1_Department_person  == null ? $Cft->Other1_Department_person : $request->Other1_Department_person;
                $Cft->Other2_review = $request->Other2_review  == null ? $Cft->Other2_review : $request->Other2_review;
                $Cft->Other2_person = $request->Other2_person  == null ? $Cft->Other2_person : $request->Other2_person;
                $Cft->Other2_Department_person = $request->Other2_Department_person  == null ? $Cft->Other2_Department_person : $request->Other2_Department_person;
                $Cft->Other3_review = $request->Other3_review  == null ? $Cft->Other3_review : $request->Other3_review;
                $Cft->Other3_person = $request->Other3_person  == null ? $Cft->Other3_person : $request->Other3_person;
                $Cft->Other3_Department_person = $request->Other3_Department_person  == null ? $Cft->Other3_Department_person : $request->Other3_Department_person;
                $Cft->Other4_review = $request->Other4_review  == null ? $Cft->Other4_review : $request->Other4_review;
                $Cft->Other4_person = $request->Other4_person  == null ? $Cft->Other4_person : $request->Other4_person;
                $Cft->Other4_Department_person = $request->Other4_Department_person  == null ? $Cft->Other4_Department_person : $request->Other4_Department_person;
                $Cft->Other5_review = $request->Other5_review  == null ? $Cft->Other5_review : $request->Other5_review;
                $Cft->Other5_person = $request->Other5_person  == null ? $Cft->Other5_person : $request->Other5_person;
                $Cft->Other5_Department_person = $request->Other5_Department_person  == null ? $Cft->Other5_Department_person : $request->Other5_Department_person;
            }
            else{
                $Cft->Production_Review = $request->Production_Review;

                if ($getCft->Production_Review != $Cft->Production_Review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Production Review Required';
                    $history->previous = $getCft->Production_Review;
                    $history->current = $Cft->Production_Review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Production_Review) || $getCft->Production_Review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Production_person = $request->Production_person;

                if ($getCft->Production_person != $Cft->Production_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Production Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Production_person);
                    $history->current = Helpers::getInitiatorName($Cft->Production_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Production_person) || $getCft->Production_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Warehouse_review = $request->Warehouse_review;
                 //dd($Cft->Warehouse_review = $request->Warehouse_review);
                if ($getCft->Warehouse_review != $Cft->Warehouse_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Warehouse Review Required';
                    $history->previous = $getCft->Warehouse_review;
                    $history->current = $Cft->Warehouse_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Warehouse_review) || $getCft->Warehouse_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Warehouse_notification = $request->Warehouse_notification;
                //dd($Cft->Warehouse_notification = $request->Warehouse_notification);
                if ($getCft->Warehouse_notification != $Cft->Warehouse_notification) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Warehouse Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Warehouse_notification);
                    $history->current = Helpers::getInitiatorName($Cft->Warehouse_notification);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Warehouse_notification) || $getCft->Warehouse_notification === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Quality_review = $request->Quality_review;

                if ($getCft->Quality_review != $Cft->Quality_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Quality Control Review Required';
                    $history->previous = $getCft->Quality_review;
                    $history->current = $Cft->Quality_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Quality_review) || $getCft->Quality_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Quality_Control_Person = $request->Quality_Control_Person;

                if ($getCft->Quality_Control_Person != $Cft->Quality_Control_Person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Quality Control Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Quality_Control_Person);
                    $history->current = Helpers::getInitiatorName($Cft->Quality_Control_Person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Quality_Control_Person) || $getCft->Quality_Control_Person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Quality_Assurance_Review = $request->Quality_Assurance_Review;

                if ($getCft->Quality_Assurance_Review != $Cft->Quality_Assurance_Review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Quality Assurance Review Required';
                    $history->previous = $getCft->Quality_Assurance_Review;
                    $history->current = $Cft->Quality_Assurance_Review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Quality_Assurance_Review) || $getCft->Quality_Assurance_Review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->QualityAssurance_person = $request->QualityAssurance_person;

                if ($getCft->QualityAssurance_person != $Cft->QualityAssurance_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Quality Assurance Person';
                    $history->previous = Helpers::getInitiatorName($getCft->QualityAssurance_person);
                    $history->current = Helpers::getInitiatorName($Cft->QualityAssurance_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->QualityAssurance_person) || $getCft->QualityAssurance_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Engineering_review = $request->Engineering_review;

                if ($getCft->Engineering_review != $Cft->Engineering_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Engineering Review Required';
                    $history->previous = $getCft->Engineering_review;
                    $history->current = $Cft->Engineering_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Engineering_review) || $getCft->Engineering_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Engineering_person = $request->Engineering_person;

                if ($getCft->Engineering_person != $Cft->Engineering_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Engineering Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Engineering_person);
                    $history->current = Helpers::getInitiatorName($Cft->Engineering_person);
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Engineering_person) || $getCft->Engineering_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Analytical_Development_review = $request->Analytical_Development_review;

                if ($getCft->Analytical_Development_review != $Cft->Analytical_Development_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Analytical Development Laboratory Review Required';
                    $history->previous = $getCft->Analytical_Development_review;
                    $history->current = $Cft->Analytical_Development_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Analytical_Development_review) || $getCft->Analytical_Development_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Analytical_Development_person = $request->Analytical_Development_person;

                if ($getCft->Analytical_Development_person != $Cft->Analytical_Development_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Analytical Development Laboratory Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Analytical_Development_person);
                    $history->current = Helpers::getInitiatorName($Cft->Analytical_Development_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = " QA Initial Review";
                    if (is_null($getCft->Analytical_Development_person) || $getCft->Analytical_Development_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Kilo_Lab_review = $request->Kilo_Lab_review;

                if ($getCft->Kilo_Lab_review != $Cft->Kilo_Lab_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Process Development Laboratory / Kilo Lab Review Required';
                    $history->previous = $getCft->Kilo_Lab_review;
                    $history->current = $Cft->Kilo_Lab_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Kilo_Lab_review) || $getCft->Kilo_Lab_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Kilo_Lab_person = $request->Kilo_Lab_person;

                if ($getCft->Kilo_Lab_person != $Cft->Kilo_Lab_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Process Development Laboratory / Kilo Lab Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Kilo_Lab_person);
                    $history->current = Helpers::getInitiatorName($Cft->Kilo_Lab_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Kilo_Lab_person) || $getCft->Kilo_Lab_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Technology_transfer_review = $request->Technology_transfer_review;

                if ($getCft->Technology_transfer_review != $Cft->Technology_transfer_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Technology Transfer / Design Review Required';
                    $history->previous = $getCft->Technology_transfer_review;
                    $history->current = $Cft->Technology_transfer_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Technology_transfer_review) || $getCft->Technology_transfer_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Technology_transfer_person = $request->Technology_transfer_person;

                if ($getCft->Technology_transfer_person != $Cft->Technology_transfer_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Technology Transfer / Design Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Technology_transfer_person);
                    $history->current = Helpers::getInitiatorName($Cft->Technology_transfer_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Technology_transfer_person) || $getCft->Technology_transfer_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Environment_Health_review = $request->Environment_Health_review;

                if ($getCft->Environment_Health_review != $Cft->Environment_Health_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Environment, Health & Safety Review Required';
                    $history->previous = $getCft->Environment_Health_review;
                    $history->current = $Cft->Environment_Health_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Environment_Health_review) || $getCft->Environment_Health_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Environment_Health_Safety_person = $request->Environment_Health_Safety_person;

                if ($getCft->Environment_Health_Safety_person != $Cft->Environment_Health_Safety_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Environment, Health & Safety Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Environment_Health_Safety_person);
                    $history->current = Helpers::getInitiatorName($Cft->Environment_Health_Safety_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Environment_Health_Safety_person) || $getCft->Environment_Health_Safety_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Human_Resource_review = $request->Human_Resource_review;

                if ($getCft->Human_Resource_review != $Cft->Human_Resource_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Human Resource & Administration Review Required';
                    $history->previous = $getCft->Human_Resource_review;
                    $history->current = $Cft->Human_Resource_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Human_Resource_review) || $getCft->Human_Resource_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Human_Resource_person = $request->Human_Resource_person;

                if ($getCft->Human_Resource_person != $Cft->Human_Resource_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Human Resource & Administration Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Human_Resource_person);
                    $history->current = Helpers::getInitiatorName($Cft->Human_Resource_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Human_Resource_person) || $getCft->Human_Resource_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Information_Technology_review = $request->Information_Technology_review;

                if ($getCft->Information_Technology_review != $Cft->Information_Technology_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Information Technology Review Required';
                    $history->previous = $getCft->Information_Technology_review;
                    $history->current = $Cft->Information_Technology_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Information_Technology_review) || $getCft->Information_Technology_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Information_Technology_person = $request->Information_Technology_person;

                if ($getCft->Information_Technology_person != $Cft->Information_Technology_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Information Technology Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Information_Technology_person);
                    $history->current = Helpers::getInitiatorName($Cft->Information_Technology_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Information_Technology_person) || $getCft->Information_Technology_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Project_management_review = $request->Project_management_review;

                if ($getCft->Project_management_review != $Cft->Project_management_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Project management Review Required ? ';
                    $history->previous = $getCft->Project_management_review;
                    $history->current = $Cft->Project_management_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Project_management_review) || $getCft->Project_management_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Project_management_person = $request->Project_management_person;

                if ($getCft->Project_management_person != $Cft->Project_management_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Project management Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Project_management_person);
                    $history->current = Helpers::getInitiatorName($Cft->Project_management_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Project_management_person) || $getCft->Project_management_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }



                $Cft->Other1_review = $request->Other1_review;

                if ($getCft->Other1_review != $Cft->Other1_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 1 Review Required?';
                    $history->previous = $getCft->Other1_review;
                    $history->current = $Cft->Other1_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Other1_review) || $getCft->Other1_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Other1_person = $request->Other1_person;

                if ($getCft->Other1_person != $Cft->Other1_person) {
                            // return 'history';
                            $history = new DeviationAuditTrail;
                            $history->deviation_id = $id;
                            $history->activity_type = 'Others 1 Person';
                            $history->previous = Helpers::getInitiatorName($getCft->Other1_person);
                            $history->current = Helpers::getInitiatorName($Cft->Other1_person);
                            $history->comment = "Not Applicable";
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $history->origin_state = $lastDeviation->status;
                            $history->change_to =   "Not Applicable";
                            $history->change_from = "QA Initial Review";
                            if (is_null($getCft->Other1_person) || $getCft->Other1_person === '') {
                                $history->action_name = 'New';
                            } else {
                                $history->action_name = 'Update';
                            }
                            $history->save();
                        }


                $Cft->Other1_Department_person = $request->Other1_Department_person;

                if ($getCft->Other1_Department_person != $Cft->Other1_Department_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 1 Department';
                    $history->previous = $getCft->Other1_Department_person;
                    $history->current = $Cft->Other1_Department_person;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Other1_Department_person) || $getCft->Other1_Department_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Other2_review = $request->Other2_review;

                if ($getCft->Other2_review != $Cft->Other2_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 2 Review Required ?';
                    $history->previous = $getCft->Other2_review;
                    $history->current = $Cft->Other2_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Other2_review) || $getCft->Other2_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Other2_person = $request->Other2_person;

                if ($getCft->Other2_person != $Cft->Other2_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 2 Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Other2_person);
                    $history->current = Helpers::getInitiatorName($Cft->Other2_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Other2_person) || $getCft->Other2_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Other2_Department_person = $request->Other2_Department_person;

                if ($getCft->Other2_Department_person != $Cft->Other2_Department_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 2 Department ';
                    $history->previous = $getCft->Other2_Department_person;
                    $history->current = $Cft->Other2_Department_person;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Other2_Department_person) || $getCft->Other2_Department_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Other3_review = $request->Other3_review;

                if ($getCft->Other3_review != $Cft->Other3_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 3 Review Required ?';
                    $history->previous = $getCft->Other3_review;
                    $history->current = $Cft->Other3_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Other3_review) || $getCft->Other3_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Other3_person = $request->Other3_person;

                if ($getCft->Other3_person != $Cft->Other3_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 3 Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Other3_person);
                    $history->current = Helpers::getInitiatorName($Cft->Other3_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Inital Review";
                    if (is_null($getCft->Other3_person) || $getCft->Other3_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Other3_Department_person = $request->Other3_Department_person;

                if ($getCft->Other3_Department_person != $Cft->Other3_Department_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 3 Department';
                    $history->previous = $getCft->Other3_Department_person;
                    $history->current = $Cft->Other3_Department_person;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Other3_Department_person) || $getCft->Other3_Department_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Other4_review = $request->Other4_review;

                if ($getCft->Other4_review != $Cft->Other4_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 4 Review Required ?';
                    $history->previous = $getCft->Other4_review;
                    $history->current = $Cft->Other4_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Other4_review) || $getCft->Other4_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Other4_person = $request->Other4_person;

                if ($getCft->Other4_person != $Cft->Other4_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 4 Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Other4_person);
                    $history->current = Helpers::getInitiatorName($Cft->Other4_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Other4_person) || $getCft->Other4_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Other4_Department_person = $request->Other4_Department_person;

                if ($getCft->Other4_Department_person != $Cft->Other4_Department_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 4 Department';
                    $history->previous = $getCft->Other4_Department_person;
                    $history->current = $Cft->Other4_Department_person;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Other4_Department_person) || $getCft->Other4_Department_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Other5_review = $request->Other5_review;

                if ($getCft->Other5_review != $Cft->Other5_review) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 5 Review Required ?';
                    $history->previous = $getCft->Other5_review;
                    $history->current = $Cft->Other5_review;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Other5_review) || $getCft->Other5_review === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                $Cft->Other5_person = $request->Other5_person;

                if ($getCft->Other5_person != $Cft->Other5_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 5 Person';
                    $history->previous = Helpers::getInitiatorName($getCft->Other5_person);
                    $history->current = Helpers::getInitiatorName($Cft->Other5_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Other5_person) || $getCft->Other5_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


                $Cft->Other5_Department_person = $request->Other5_Department_person;

                if ($getCft->Other5_Department_person != $Cft->Other5_Department_person) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Others 5 Department';
                    $history->previous = Helpers::getInitiatorName($getCft->Other5_Department_person);
                    $history->current = Helpers::getInitiatorName($Cft->Other5_Department_person);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to =   "Not Applicable";
                    $history->change_from = "QA Initial Review";
                    if (is_null($getCft->Other5_Department_person) || $getCft->Other5_Department_person === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }


            }



            $Cft->Production_assessment = $request->Production_assessment;

            if ($getCft->Production_assessment != $Cft->Production_assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Production)';
                $history->previous = $getCft->Production_assessment;
                $history->current = $Cft->Production_assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Production_assessment) || $getCft->Production_assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }

            //$Cft->Production_feedback = $request->Production_feedback;
            $Cft->Warehouse_assessment = $request->Warehouse_assessment;

            if ($getCft->Warehouse_assessment != $Cft->Warehouse_assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment(By Warehouse)';
                $history->previous = $getCft->Warehouse_assessment;
                $history->current = $Cft->Warehouse_assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Warehouse_assessment) || $getCft->Warehouse_assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }

            //$Cft->Warehouse_feedback = $request->Warehouse_feedback;
            $Cft->Quality_Control_assessment = $request->Quality_Control_assessment;

            if ($getCft->Quality_Control_assessment != $Cft->Quality_Control_assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Quality Control)';
                $history->previous = $getCft->Quality_Control_assessment;
                $history->current = $Cft->Quality_Control_assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Quality_Control_assessment) || $getCft->Quality_Control_assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }


            //$Cft->Quality_Control_feedback = $request->Quality_Control_feedback;
            $Cft->QualityAssurance_assessment = $request->QualityAssurance_assessment;

            if ($getCft->QualityAssurance_assessment != $Cft->QualityAssurance_assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Quality Assurance)';
                $history->previous = $getCft->QualityAssurance_assessment;
                $history->current = $Cft->QualityAssurance_assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->QualityAssurance_assessment) || $getCft->QualityAssurance_assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }


            //$Cft->QualityAssurance_feedback = $request->QualityAssurance_feedback;
            $Cft->Engineering_assessment = $request->Engineering_assessment;

            if ($getCft->Engineering_assessment != $Cft->Engineering_assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Engineering)';
                $history->previous = $getCft->Engineering_assessment;
                $history->current = $Cft->Engineering_assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Engineering_assessment) || $getCft->Engineering_assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }


            //$Cft->Engineering_feedback = $request->Engineering_feedback;
            $Cft->Analytical_Development_assessment = $request->Analytical_Development_assessment;

            if ($getCft->Analytical_Development_assessment != $Cft->Analytical_Development_assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Analytical Development Laboratory)';
                $history->previous = $getCft->Analytical_Development_assessment;
                $history->current = $Cft->Analytical_Development_assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Analytical_Development_assessment) || $getCft->Analytical_Development_assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }

            //$Cft->Analytical_Development_feedback = $request->Analytical_Development_feedback;
            $Cft->Kilo_Lab_assessment = $request->Kilo_Lab_assessment;

            if ($getCft->Kilo_Lab_assessment != $Cft->Kilo_Lab_assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Process Development Laboratory / Kilo Lab)';
                $history->previous = $getCft->Kilo_Lab_assessment;
                $history->current = $Cft->Kilo_Lab_assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Kilo_Lab_assessment) || $getCft->Kilo_Lab_assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }


            //$Cft->Kilo_Lab_feedback = $request->Kilo_Lab_feedback;
            $Cft->Technology_transfer_assessment = $request->Technology_transfer_assessment;

            if ($getCft->Technology_transfer_assessment != $Cft->Technology_transfer_assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Technology Transfer / Design)';
                $history->previous = $getCft->Technology_transfer_assessment;
                $history->current = $Cft->Technology_transfer_assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Technology_transfer_assessment) || $getCft->Technology_transfer_assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }

            //$Cft->Technology_transfer_feedback = $request->Technology_transfer_feedback;
            $Cft->Health_Safety_assessment = $request->Health_Safety_assessment;

            if ($getCft->Health_Safety_assessment != $Cft->Health_Safety_assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Environment, Health & Safety)';
                $history->previous = $getCft->Health_Safety_assessment;
                $history->current = $Cft->Health_Safety_assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Health_Safety_assessment) || $getCft->Health_Safety_assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }

            //$Cft->Health_Safety_feedback = $request->Health_Safety_feedback;
            $Cft->Human_Resource_assessment = $request->Human_Resource_assessment;

            if ($getCft->Human_Resource_assessment != $Cft->Human_Resource_assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Human Resource & Administration)';
                $history->previous = $getCft->Human_Resource_assessment;
                $history->current = $Cft->Human_Resource_assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Human_Resource_assessment) || $getCft->Human_Resource_assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }

            //$Cft->Human_Resource_feedback = $request->Human_Resource_feedback;
            $Cft->Information_Technology_assessment = $request->Information_Technology_assessment;

            if ($getCft->Information_Technology_assessment != $Cft->Information_Technology_assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Information Technology)';
                $history->previous = $getCft->Information_Technology_assessment;
                $history->current = $Cft->Information_Technology_assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Information_Technology_assessment) || $getCft->Information_Technology_assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }

            //$Cft->Information_Technology_feedback = $request->Information_Technology_feedback;
            $Cft->Project_management_assessment = $request->Project_management_assessment;

            if ($getCft->Project_management_assessment != $Cft->Project_management_assessment) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Impact Assessment (By Project management ) ';
                        $history->previous = $getCft->Project_management_assessment;
                        $history->current = $Cft->Project_management_assessment;
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to =   "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Project_management_assessment) || $getCft->Project_management_assessment === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

            //$Cft->Project_management_feedback = $request->Project_management_feedback;
            $Cft->Other1_assessment = $request->Other1_assessment;

            if ($getCft->Other1_assessment != $Cft->Other1_assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Others 1)';
                $history->previous = $getCft->Other1_assessment;
                $history->current = $Cft->Other1_assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Other1_assessment) || $getCft->Other1_assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }

            //$Cft->Other1_feedback = $request->Other1_feedback;
            $Cft->Other2_Assessment = $request->Other2_Assessment;

            if ($getCft->Other2_Assessment != $Cft->Other2_Assessment || !empty ($request->comment)) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Others 2)';
                $history->previous = $getCft->Other2_Assessment;
                $history->current = $Cft->Other2_Assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Other2_Assessment) || $getCft->Other2_Assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }

            //$Cft->Other2_feedback = $request->Other2_feedback;
            $Cft->Other3_Assessment = $request->Other3_Assessment;

            if ($getCft->Other3_Assessment != $Cft->Other3_Assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Others 3)';
                $history->previous = $getCft->Other3_Assessment;
                $history->current = $Cft->Other3_Assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Other3_Assessment) || $getCft->Other3_Assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }


            //$Cft->Other3_feedback = $request->Other3_feedback;
            $Cft->Other4_Assessment = $request->Other4_Assessment;

            if ($getCft->Other4_Assessment != $Cft->Other4_Assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Others 4) ';
                $history->previous = $getCft->Other4_Assessment;
                $history->current = $Cft->Other4_Assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Other4_Assessment) || $getCft->Other4_Assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }

            //$Cft->Other4_feedback = $request->Other4_feedback;
            $Cft->Other5_Assessment = $request->Other5_Assessment;

            if ($getCft->Other5_Assessment != $Cft->Other5_Assessment) {
                // return 'history';
                $history = new DeviationAuditTrail;
                $history->deviation_id = $id;
                $history->activity_type = 'Impact Assessment (By Others 5)';
                $history->previous = $getCft->Other5_Assessment;
                $history->current = $Cft->Other5_Assessment;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDeviation->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = "CFT";
                if (is_null($getCft->Other5_Assessment) || $getCft->Other5_Assessment === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
            }

            //$Cft->Other5_feedback = $request->Other5_feedback;


            if (!empty ($request->production_attachment)) {
                $files = [];
                if ($request->hasfile('production_attachment')) {
                    foreach ($request->file('production_attachment') as $file) {
                        $name = $request->name . 'production_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->production_attachment = json_encode($files);
            }
            if (!empty ($request->Warehouse_attachment)) {
                $files = [];
                if ($request->hasfile('Warehouse_attachment')) {
                    foreach ($request->file('Warehouse_attachment') as $file) {
                        $name = $request->name . 'Warehouse_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->Warehouse_attachment = json_encode($files);
            }
            if (!empty ($request->Quality_Control_attachment)) {
                $files = [];
                if ($request->hasfile('Quality_Control_attachment')) {
                    foreach ($request->file('Quality_Control_attachment') as $file) {
                        $name = $request->name . 'Quality_Control_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->Quality_Control_attachment = json_encode($files);
            }
            if (!empty ($request->Quality_Assurance_attachment)) {
                $files = [];
                if ($request->hasfile('Quality_Assurance_attachment')) {
                    foreach ($request->file('Quality_Assurance_attachment') as $file) {
                        $name = $request->name . 'Quality_Assurance_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->Quality_Assurance_attachment = json_encode($files);
            }
            if (!empty ($request->Engineering_attachment)) {
                $files = [];
                if ($request->hasfile('Engineering_attachment')) {
                    foreach ($request->file('Engineering_attachment') as $file) {
                        $name = $request->name . 'Engineering_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->Engineering_attachment = json_encode($files);
            }
            if (!empty ($request->Analytical_Development_attachment)) {
                $files = [];
                if ($request->hasfile('Analytical_Development_attachment')) {
                    foreach ($request->file('Analytical_Development_attachment') as $file) {
                        $name = $request->name . 'Analytical_Development_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->Analytical_Development_attachment = json_encode($files);
            }
            if (!empty ($request->Kilo_Lab_attachment)) {
                $files = [];
                if ($request->hasfile('Kilo_Lab_attachment')) {
                    foreach ($request->file('Kilo_Lab_attachment') as $file) {
                        $name = $request->name . 'Kilo_Lab_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->Kilo_Lab_attachment = json_encode($files);
            }
            if (!empty ($request->Technology_transfer_attachment)) {
                $files = [];
                if ($request->hasfile('Technology_transfer_attachment')) {
                    foreach ($request->file('Technology_transfer_attachment') as $file) {
                        $name = $request->name . 'Technology_transfer_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->Technology_transfer_attachment = json_encode($files);
            }
            if (!empty ($request->Environment_Health_Safety_attachment)) {
                $files = [];
                if ($request->hasfile('Environment_Health_Safety_attachment')) {
                    foreach ($request->file('Environment_Health_Safety_attachment') as $file) {
                        $name = $request->name . 'Environment_Health_Safety_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->Environment_Health_Safety_attachment = json_encode($files);
            }
            if (!empty ($request->Human_Resource_attachment)) {
                $files = [];
                if ($request->hasfile('Human_Resource_attachment')) {
                    foreach ($request->file('Human_Resource_attachment') as $file) {
                        $name = $request->name . 'Human_Resource_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->Human_Resource_attachment = json_encode($files);
            }
            if (!empty ($request->Information_Technology_attachment)) {
                $files = [];
                if ($request->hasfile('Information_Technology_attachment')) {
                    foreach ($request->file('Information_Technology_attachment') as $file) {
                        $name = $request->name . 'Information_Technology_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->Information_Technology_attachment = json_encode($files);
            }
            if (!empty ($request->Project_management_attachment)) {
                $files = [];
                if ($request->hasfile('Project_management_attachment')) {
                    foreach ($request->file('Project_management_attachment') as $file) {
                        $name = $request->name . 'Project_management_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->Project_management_attachment = json_encode($files);
            }
            if (!empty ($request->Other1_attachment)) {
                $files = [];
                if ($request->hasfile('Other1_attachment')) {
                    foreach ($request->file('Other1_attachment') as $file) {
                        $name = $request->name . 'Other1_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->Other1_attachment = json_encode($files);
            }
            if (!empty ($request->Other2_attachment)) {
                $files = [];
                if ($request->hasfile('Other2_attachment')) {
                    foreach ($request->file('Other2_attachment') as $file) {
                        $name = $request->name . 'Other2_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }


                $Cft->Other2_attachment = json_encode($files);
            }
            if (!empty ($request->Other3_attachment)) {
                $files = [];
                if ($request->hasfile('Other3_attachment')) {
                    foreach ($request->file('Other3_attachment') as $file) {
                        $name = $request->name . 'Other3_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }
                $Cft->Other3_attachment = json_encode($files);
            }
            if (!empty ($request->Other4_attachment)) {
                $files = [];
                if ($request->hasfile('Other4_attachment')) {
                    foreach ($request->file('Other4_attachment') as $file) {
                        $name = $request->name . 'Other4_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }

                $Cft->Other4_attachment = json_encode($files);
            }

             //new code navneet

             $files = is_array($request->existing_Other5_attachment) ? $request->existing_Other5_attachment : null;

             if (!empty($request->Other5_attachment)) {
                 if ($Cft->Other5_attachment) {
                     $existingFiles = json_decode($Cft->Other5_attachment, true); // Convert to associative array
                     if (is_array($existingFiles)) {
                         $files = $existingFiles;
                     }
                 }

                 if ($request->hasfile('Other5_attachment')) {
                     foreach ($request->file('Other5_attachment') as $file) {
                         $name = $request->name . 'Other5_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                         $file->move('upload/', $name);
                         $files[] = $name;
                     }
                 }
             }

             // If no files are attached, set to null
             $Cft->Other5_attachment = !empty($files) ? json_encode($files) : null;

            //if (!empty ($request->Other5_attachment)) {
            //    $files = [];
            //    if ($request->hasfile('Other5_attachment')) {
            //        foreach ($request->file('Other5_attachment') as $file) {
            //            $name = $request->name . 'Other5_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
            //            $file->move('upload/', $name);
            //            $files[] = $name;
            //        }
            //    }

            //    if (!empty($files)) {
            //        $Cft->Other5_attachment = json_encode($files);
            //    } else {
            //        $Cft->Other5_attachment = null;
            //    }

            //    $Cft->Other5_attachment = json_encode($files);
            //}


            //if (!empty ($request->Other5_attachment)) {
            //    $files = [];
            //    if ($request->hasfile('Other5_attachment')) {
            //        foreach ($request->file('Other5_attachment') as $file) {
            //            $name = $request->name . 'Other5_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
            //            $file->move('upload/', $name);
            //            $files[] = $name;
            //        }
            //    }


            //    $Cft->Other5_attachment = json_encode($files);
            //}

        $Cft->save();

                $IsCFTRequired = DeviationCftsResponse::withoutTrashed()->where(['is_required' => 1, 'deviation_id' => $id])->latest()->first();
                $cftUsers = DB::table('deviationcfts')->where(['deviation_id' => $id])->first();
                // Define the column names
                $columns = ['Production_person', 'Warehouse_notification', 'Quality_Control_Person', 'QualityAssurance_person', 'Engineering_person', 'Analytical_Development_person', 'Kilo_Lab_person', 'Technology_transfer_person', 'Environment_Health_Safety_person', 'Human_Resource_person', 'Information_Technology_person', 'Project_management_person','Other1_person','Other2_person','Other3_person','Other4_person','Other5_person'];

                // Initialize an array to store the values
                $valuesArray = [];

                foreach ($columns as $index => $column) {
                    $value = $cftUsers->$column;
                    // Check if the value is not null and not equal to 0
                    if ($value != null && $value != 0) {
                        $valuesArray[] = $value;
                    }
                }
                // Remove duplicates from the array
                $valuesArray = array_unique($valuesArray);

                // Convert the array to a re-indexed array
                $valuesArray = array_values($valuesArray);

                foreach ($valuesArray as $u) {
                        $email = Helpers::getInitiatorEmail($u);
                        if ($email !== null) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation],
                                    function ($message) use ($email) {
                                        $message->to($email)
                                            ->subject("CFT Assgineed by " . Auth::user()->name);
                                    }
                                );
                            } catch (\Exception $e) {
                                //log error
                            }
                    }
                }


        }

        //if (!empty ($request->Initial_attachment)) {
        //    $files = [];
        //    if ($request->hasfile('Initial_attachment')) {
        //        foreach ($request->file('Initial_attachment') as $file) {
        //            $name = $request->name . 'Initial_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //            $file->move('upload/', $name);
        //            $files[] = $name;
        //        }
        //    }

        //    if (!empty($files)) {
        //        $deviation->Initial_attachment = json_encode($files);
        //    } else {
        //        $deviation->Initial_attachment = null;
        //    }

        //    $deviation->Initial_attachment = json_encode($files);
        //}

        //New Code navneet

        $files = is_array($request->existing_qa_initial_files) ? $request->existing_qa_initial_files : null;

        if (!empty($request->Initial_attachment)) {
            if ($deviation->Initial_attachment) {
                $existingFiles = json_decode($deviation->Initial_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = $existingFiles;
                }
            }

            if ($request->hasfile('Initial_attachment')) {
                foreach ($request->file('Initial_attachment') as $file) {
                    $name = $request->name . 'Initial_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $deviation->Initial_attachment = !empty($files) ? json_encode($files) : null;


        //if (!empty ($request->Audit_file)) {
        //    $files = [];
        //    if ($request->hasfile('Audit_file')) {
        //        foreach ($request->file('Audit_file') as $file) {
        //            $name = $request->name . 'Audit_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //            $file->move('upload/', $name);
        //            $files[] = $name;
        //        }
        //    }

        //    if (!empty($files)) {
        //        $deviation->Audit_file = json_encode($files);
        //    } else {
        //        $deviation->Audit_file = null;
        //    }

        //    $deviation->Audit_file = json_encode($files);
        //}

        //New Code navneet

        $files = is_array($request->existing_hod_files) ? $request->existing_hod_files : null;

        if (!empty($request->Audit_file)) {
            if ($deviation->Audit_file) {
                $existingFiles = json_decode($deviation->Audit_file, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = $existingFiles;
                }
            }

            if ($request->hasfile('Audit_file')) {
                foreach ($request->file('Audit_file') as $file) {
                    $name = $request->name . 'Audit_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $deviation->Audit_file = !empty($files) ? json_encode($files) : null;

        //New Code navneet

        $files = is_array($request->existing_initial_file) ? $request->existing_initial_file : null;

        if (!empty($request->initial_file)) {
            if ($deviation->initial_file) {
                $existingFiles = json_decode($deviation->initial_file, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = $existingFiles;
                }
            }

            if ($request->hasfile('initial_file')) {
                foreach ($request->file('initial_file') as $file) {
                    $name = $request->name . 'initial_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $deviation->initial_file = !empty($files) ? json_encode($files) : null;

        //if (!empty ($request->initial_file)) {
        //    $files = [];
        //    if ($request->hasfile('initial_file')) {
        //        foreach ($request->file('initial_file') as $file) {
        //            $name = $request->name . 'initial_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //            $file->move('upload/', $name);
        //            $files[] = $name;
        //        }
        //    }

        //    if (!empty($files)) {
        //        $deviation->initial_file = json_encode($files);
        //    } else {
        //        $deviation->initial_file = null;
        //    }

        //    $deviation->initial_file = json_encode($files);
        //}


        //$files = is_array($request->existing_initial_file) ? $request->existing_initial_file : [];

        //if (!empty($request->initial_file)) {
        //    $files = [];

        //    // Decode existing files if they exist
        //    if ($deviation->initial_file) {
        //        $existingFiles = json_decode($deviation->initial_file, true); // Convert to associative array
        //        if (is_array($existingFiles)) {
        //            $files = $existingFiles;
        //        }
        //    }

        //    // Remove files that were removed in the frontend
        //    if ($request->has('removed_files')) {
        //        $removedFiles = json_decode($request->removed_files, true);
        //        $files = array_diff($files, $removedFiles);
        //        // Optionally, delete the files from the server
        //        foreach ($removedFiles as $removedFile) {
        //            @unlink('upload/' . $removedFile);
        //        }
        //    }

        //    // Process and add new files
        //    if ($request->hasfile('initial_file')) {
        //        foreach ($request->file('initial_file') as $file) {
        //            $name = $request->name . 'initial_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //            $file->move('upload/', $name);
        //            $files[] = $name;
        //        }
        //    }
        //}

        //$deviation->initial_file = json_encode($files);

        if (!empty ($request->QA_attachment)) {
            $files = [];

            if ($deviation->QA_attachment) {
                $existingFiles = json_decode($deviation->QA_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = $existingFiles;
                }
                // $files = is_array(json_decode($deviation->QA_attachment)) ? $deviation->QA_attachment : [];
            }

            if ($request->hasfile('QA_attachment')) {
                foreach ($request->file('QA_attachment') as $file) {
                    $name = $request->name . 'QA_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $deviation->QA_attachment = json_encode($files);
        }
        if (!empty ($request->Capa_attachment)) {

            $files = [];

            if ($deviation->Capa_attachment) {
                $existingFiles = json_decode($deviation->Capa_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = $existingFiles;
                }
                // $files = is_array(json_decode($deviation->Capa_attachment)) ? $deviation->Capa_attachment : [];
            }

            if ($request->hasfile('Capa_attachment')) {
                foreach ($request->file('Capa_attachment') as $file) {
                    $name = $request->name . 'Capa_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $deviation->Capa_attachment = json_encode($files);
        }

        //$files = is_array($request->existing_QA_attachments) ? $request->existing_QA_attachments : [];
        //if (!empty ($request->QA_attachments)) {
        //    if ($deviation->QA_attachments) {
        //        $existingFiles = json_decode($deviation->QA_attachments, true); // Convert to associative array
        //        if (is_array($existingFiles)) {
        //            $files = $existingFiles;
        //        }
        //        // $files = is_array(json_decode($deviation->QA_attachments)) ? $deviation->QA_attachments : [];
        //    }

        //    if ($request->hasfile('QA_attachments')) {
        //        foreach ($request->file('QA_attachments') as $file) {
        //            $name = $request->name . 'QA_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //            $file->move('upload/', $name);
        //            $files[] = $name;
        //        }
        //    }
        //}
        //$deviation->QA_attachments = json_encode($files);

        //if (!empty ($request->QA_attachments)) {
        //    $files = [];
        //    if ($request->hasfile('QA_attachments')) {
        //        foreach ($request->file('QA_attachments') as $file) {
        //            $name = $request->name . 'QA_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //            $file->move('upload/', $name);
        //            $files[] = $name;
        //        }
        //    }

        //    if (!empty($files)) {
        //        $deviation->QA_attachments = json_encode($files);
        //    } else {
        //        $deviation->QA_attachments = null;
        //    }

        //    $deviation->QA_attachments = json_encode($files);
        //}


        //new code navneet

        $files = is_array($request->qa_attachments_files) ? $request->qa_attachments_files : null;

        if (!empty($request->QA_attachments)) {
            if ($deviation->QA_attachments) {
                $existingFiles = json_decode($deviation->QA_attachments, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = $existingFiles;
                }
            }

            if ($request->hasfile('QA_attachments')) {
                foreach ($request->file('QA_attachments') as $file) {
                    $name = $request->name . 'QA_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $deviation->QA_attachments = !empty($files) ? json_encode($files) : null;

            if($deviation->stage >= 5){

                //investiocation dynamic
                $deviation->Discription_Event = $request->Discription_Event;
                $deviation->objective = $request->objective;
                $deviation->scope = $request->scope;
                $deviation->imidiate_action = $request->imidiate_action;
                $deviation->imidiate_action1 = $request->imidiate_action1;
                $newDataGridInvestication = DeviationNewGridData::where(['deviation_id' => $id, 'identifier' => 'investication'])->firstOrCreate();
                $newDataGridInvestication->deviation_id = $id;
                $newDataGridInvestication->identifier = 'investication';
                $newDataGridInvestication->data = $request->investication;
                $newDataGridInvestication->save();

                $newDataGridRCA = DeviationNewGridData::where(['deviation_id' => $id, 'identifier' => 'rootCause'])->firstOrCreate();
                $newDataGridRCA->deviation_id = $id;
                $newDataGridRCA->identifier = 'rootCause';
                $newDataGridRCA->data = $request->rootCause;
                $newDataGridRCA->save();
            }


        if($deviation->stage == 7){
            $deviation->initiator_final_remarks = $request->initiator_final_remarks;
            // dd($deviation->initiator_final_remarks);

                //new code navneet

                $files = is_array($request->initiator_files) ? $request->initiator_files : null;

                if (!empty($request->initiator_final_attachments)) {
                    if ($deviation->initiator_final_attachments) {
                        $existingFiles = json_decode($deviation->initiator_final_attachments, true); // Convert to associative array
                        if (is_array($existingFiles)) {
                            $files = $existingFiles;
                        }
                    }

                    if ($request->hasfile('initiator_final_attachments')) {
                        foreach ($request->file('initiator_final_attachments') as $file) {
                            $name = $request->name . 'initiator_final_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                            $file->move('upload/', $name);
                            $files[] = $name;
                        }
                    }
                }

                // If no files are attached, set to null
                $deviation->initiator_final_attachments = !empty($files) ? json_encode($files) : null;

            //$files = is_array($request->existing_initiator_final_attachments) ? $request->existing_initiator_final_attachments : [];
            //if (!empty ($request->initiator_final_attachments)) {
            //    if ($deviation->initiator_final_attachments) {
            //        $existingFiles = json_decode($deviation->initiator_final_attachments, true); // Convert to associative array
            //        if (is_array($existingFiles)) {
            //            $files = $existingFiles;
            //        }
            //        // $files = is_array(json_decode($deviation->initiator_final_attachments)) ? $deviation->initiator_final_attachments : [];
            //    }

            //    if ($request->hasfile('initiator_final_attachments')) {
            //        foreach ($request->file('initiator_final_attachments') as $file) {
            //            $name = $request->name . 'initiator_final_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
            //            $file->move('upload/', $name);
            //            $files[] = $name;
            //        }
            //    }
            //}
            //$deviation->initiator_final_attachments = json_encode($files);
        }
        if($deviation->stage == 8){
            $deviation->hod_final_remarks = $request->hod_final_remarks;

        //new code navneet

        $files = is_array($request->hod_final_attachment_files) ? $request->hod_final_attachment_files : null;

        if (!empty($request->hod_final_attachments)) {
            if ($deviation->hod_final_attachments) {
                $existingFiles = json_decode($deviation->hod_final_attachments, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = $existingFiles;
                }
            }

            if ($request->hasfile('hod_final_attachments')) {
                foreach ($request->file('hod_final_attachments') as $file) {
                    $name = $request->name . 'hod_final_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $deviation->hod_final_attachments = !empty($files) ? json_encode($files) : null;

            //$files = is_array($request->existing_hod_final_attachments) ? $request->existing_hod_final_attachments : [];
            //if (!empty ($request->hod_final_attachments)) {
            //    if ($deviation->hod_final_attachments) {
            //        $existingFiles = json_decode($deviation->hod_final_attachments, true); // Convert to associative array
            //        if (is_array($existingFiles)) {
            //            $files = $existingFiles;
            //        }
            //        // $files = is_array(json_decode($deviation->hod_final_attachments)) ? $deviation->hod_final_attachments : [];
            //    }

            //    if ($request->hasfile('hod_final_attachments')) {
            //        foreach ($request->file('hod_final_attachments') as $file) {
            //            $name = $request->name . 'hod_final_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
            //            $file->move('upload/', $name);
            //            $files[] = $name;
            //        }
            //    }
            //}
            //$deviation->hod_final_attachments = json_encode($files);
        }
        if($deviation->stage == 9){
            $deviation->qa_final_remarks = $request->qa_final_remarks;


            $files = is_array($request->existing_qa_final_files) ? $request->existing_qa_final_files : null;

            if (!empty($request->qa_final_attachments)) {
                if ($deviation->qa_final_attachments) {
                    $existingFiles = json_decode($deviation->qa_final_attachments, true); // Convert to associative array
                    if (is_array($existingFiles)) {
                        $files = $existingFiles;
                    }
                }

                if ($request->hasfile('qa_final_attachments')) {
                    foreach ($request->file('qa_final_attachments') as $file) {
                        $name = $request->name . 'qa_final_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move('upload/', $name);
                        $files[] = $name;
                    }
                }
            }

            // If no files are attached, set to null
            $deviation->qa_final_attachments = !empty($files) ? json_encode($files) : null;

            //$files = is_array($request->existing_qa_final_attachments) ? $request->existing_qa_final_attachments : [];
            //if (!empty ($request->qa_final_attachments)) {
            //    if ($deviation->qa_final_attachments) {
            //        $existingFiles = json_decode($deviation->qa_final_attachments, true); // Convert to associative array
            //        if (is_array($existingFiles)) {
            //            $files = $existingFiles;
            //        }
            //        // $files = is_array(json_decode($deviation->qa_final_attachments)) ? $deviation->qa_final_attachments : [];
            //    }

            //    if ($request->hasfile('qa_final_attachments')) {
            //        foreach ($request->file('qa_final_attachments') as $file) {
            //            $name = $request->name . 'qa_final_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
            //            $file->move('upload/', $name);
            //            $files[] = $name;
            //        }
            //    }
            //}
            //$deviation->qa_final_attachments = json_encode($files);
        }

        $deviation->form_progress = isset($form_progress) ? $form_progress : null;
        $deviation->update();
        // grid
         $data3=DeviationGrid::where('deviation_grid_id', $deviation->id)->where('type', "Deviation")->first();
                if (!empty($request->IDnumber)) {
                    $data3->IDnumber = serialize($request->IDnumber);
                }
                if (!empty($request->facility_name)) {
                    $data3->facility_name = serialize($request->facility_name);
                }

                if (!empty($request->Remarks)) {
                    $data3->Remarks = serialize($request->Remarks);
                }

                $data3->update();
                // dd($request->Remarks);


            $data4=DeviationGrid::where('deviation_grid_id', $deviation->id)->where('type', "Document")->first();
            if (!empty($request->Number)) {
                $data4->Number = serialize($request->Number);
            }
            if (!empty($request->ReferenceDocumentName)) {
                $data4->ReferenceDocumentName = serialize($request->ReferenceDocumentName);
            }

            if (!empty($request->Document_Remarks)) {
                $data4->Document_Remarks = serialize($request->Document_Remarks);
            }
            $data4->update();

            $data5=DeviationGrid::where('deviation_grid_id', $deviation->id)->where('type', "Product")->first();
            if (!empty($request->product_name)) {
                $data5->product_name = serialize($request->product_name);
            }
            if (!empty($request->product_stage)) {
                $data5->product_stage = serialize($request->product_stage);
            }

            if (!empty($request->batch_no)) {
                $data5->batch_no = serialize($request->batch_no);
            }
            $data5->update();


        if ($lastDeviation->hod_final_remarks != $deviation->hod_final_remarks || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'HOD Final Remarks';
             $history->previous = $lastDeviation->hod_final_remarks;
            $history->current = $deviation->hod_final_remarks;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->hod_final_remarks) || $lastDeviation->hod_final_remarks === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        //if($deviation->stage == 3){
        //    $history = new DeviationAuditTrail;
        //    $history->deviation_id = $id;
        //    $history->activity_type = 'Record Number';
        //    $history->previous = "Null";
        //    $history->current = Helpers::getDivisionName($deviation->division_id) . '/RV/RP/' . Helpers::year($deviation->created_at) . '/' . str_pad($deviation->record, 4, '0', STR_PAD_LEFT);
        //    $history->comment = "Not Applicable";
        //    $history->user_id = Auth::user()->id;
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    //$history->origin_state = $record->status;
        //    $history->change_to =   "QA Initial Review";
        //    $history->change_from = 'HOD Review';
        //    $history->action_name = 'Create';
        //    $history->save();
        //}



        if ($lastDeviation->hod_final_attachments != $deviation->hod_final_attachments || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'HOD Final Attachments';
             $history->previous = $lastDeviation->hod_final_attachments;
            $history->current = $deviation->hod_final_attachments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->hod_final_attachments) || $lastDeviation->hod_final_attachments === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }


        if ($lastDeviation->qa_final_remarks != $deviation->qa_final_remarks || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'QA Final Remarks';
             $history->previous = $lastDeviation->qa_final_remarks;
            $history->current = $deviation->qa_final_remarks;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->qa_final_remarks) || $lastDeviation->qa_final_remarks === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->qa_final_attachments != $deviation->qa_final_attachments || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'QA Final Attachments';
             $history->previous = $lastDeviation->qa_final_attachments;
            $history->current = $deviation->qa_final_attachments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->qa_final_attachments) || $lastDeviation->qa_final_attachments === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }


        if ($lastDeviation->initiator_final_remarks != $deviation->initiator_final_remarks || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Initiator Final Remarks';
             $history->previous = $lastDeviation->initiator_final_remarks;
            $history->current = $deviation->initiator_final_remarks;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->initiator_final_remarks) || $lastDeviation->initiator_final_remarks === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->initiator_final_attachments != $deviation->initiator_final_attachments || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Initiator Final Attachments';
             $history->previous = $lastDeviation->initiator_final_attachments;
            $history->current = $deviation->initiator_final_attachments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->initiator_final_attachments) || $lastDeviation->initiator_final_attachments === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->short_description != $deviation->short_description || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Short Description';
             $history->previous = $lastDeviation->short_description;
            $history->current = $deviation->short_description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->short_description) || $lastDeviation->short_description === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Initiator_Group != $deviation->Initiator_Group || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Department';
            $history->previous = Helpers::getInitiatorGroupFullName($lastDeviation->Initiator_Group);
            $history->current = Helpers::getInitiatorGroupFullName($deviation->Initiator_Group);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Initiator_Group) || $lastDeviation->Initiator_Group === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Deviation_date != $deviation->Deviation_date || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Deviation Observed On';
            $history->previous = $lastDeviation->Deviation_date;
            $history->current = Helpers::getdateFormat($deviation->Deviation_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Deviation_date) || $lastDeviation->Deviation_date === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->deviation_time != $deviation->deviation_time || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Deviation Observed On (Time)';
            $history->previous = $lastDeviation->deviation_time;
            $history->current = $deviation->deviation_time;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->deviation_time) || $lastDeviation->deviation_time === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Delay_Justification != $deviation->Delay_Justification || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Delay Justification';
            $history->previous = $lastDeviation->Delay_Justification;
            $history->current = $deviation->Delay_Justification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Delay_Justification) || $lastDeviation->Delay_Justification === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Facility != $deviation->Facility || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Deviation Observed By';
            $history->previous = $lastDeviation->Facility;
            $history->current = $deviation->Facility;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Facility) || $lastDeviation->Facility === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Observed_by != $deviation->Observed_by || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Observed by';
            $history->previous = $lastDeviation->Observed_by;
            $history->current = $deviation->Observed_by;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Observed_by) || $lastDeviation->Observed_by === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Deviation_reported_date != $deviation->Deviation_reported_date || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Deviation Reported on';
            $history->previous = $lastDeviation->Deviation_reported_date;
            $history->current = Helpers::getdateFormat($deviation->Deviation_reported_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Deviation_reported_date) || $lastDeviation->Deviation_reported_date === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->audit_type != $deviation->audit_type || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Deviation Related To';
            $history->previous = $lastDeviation->audit_type;
            $history->current = $deviation->audit_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->audit_type) || $lastDeviation->audit_type === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Others != $deviation->Others || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Others';
            $history->previous = $lastDeviation->Others;
            $history->current = $deviation->Others;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Others) || $lastDeviation->Others === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Facility_Equipment != $deviation->Facility_Equipment || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Facility/ Equipment/ Instrument/ System Details Required?';
            $history->previous = $lastDeviation->Facility_Equipment;
            $history->current = $deviation->Facility_Equipment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Facility_Equipment) || $lastDeviation->Facility_Equipment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Document_Details_Required != $deviation->Document_Details_Required || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Document Details Required';
            $history->previous = $lastDeviation->Document_Details_Required;
            $history->current = $deviation->Document_Details_Required;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Document_Details_Required) || $lastDeviation->Document_Details_Required === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Description_Deviation != $deviation->Description_Deviation || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Description of Deviation';
            $history->previous = $lastDeviation->Description_Deviation;
            $history->current = $deviation->Description_Deviation;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Description_Deviation) || $lastDeviation->Description_Deviation === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Immediate_Action != $deviation->Immediate_Action || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Immediate Action (if any)';
            $history->previous = $lastDeviation->Immediate_Action;
            $history->current = $deviation->Immediate_Action;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Immediate_Action) || $lastDeviation->Immediate_Action === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Preliminary_Impact != $deviation->Preliminary_Impact || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Preliminary Impact of Deviation';
            $history->previous = $lastDeviation->Preliminary_Impact;
            $history->current = $deviation->Preliminary_Impact;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Preliminary_Impact) || $lastDeviation->Preliminary_Impact === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        $previousIniAttachments = $lastDeviation->initial_file;
        $IniAttachments = $previousIniAttachments == $deviation->initial_file;

        if ($IniAttachments != true) {

            $existingHistory = DeviationAuditTrail::where('deviation_id', $id)
            ->where('activity_type', 'Initial Attachments')
            ->exists();

                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Initial Attachments';
                    $history->previous = $previousIniAttachments;
                    $history->current = $deviation->initial_file;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to = "Not Applicable";
                    $history->change_from = $lastDeviation->status;
                    if ($existingHistory) {
                        $history->action_name = "Update";
                    } else {
                        $history->action_name = "New";
                    }
                    $history->save();
                }



        //if ($lastDeviation->initial_file != $deviation->initial_file || !empty($request->comment)) {
        //    // return 'history';
        //    $history = new DeviationAuditTrail;
        //    $history->deviation_id = $id;
        //    $history->activity_type = 'Initial Attachments';
        //    $history->previous = $lastDeviation->initial_file;
        //    $history->current = $deviation->initial_file;
        //    $history->comment = $request->comment;
        //    $history->user_id = Auth::user()->id;
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $lastDeviation->status;
        //    $history->change_to =   "Not Applicable";
        //    $history->change_from = $lastDeviation->status;
        //    if (is_null($lastDeviation->initial_file) || $lastDeviation->initial_file === '') {
        //        $history->action_name = 'New';
        //    } else {
        //        $history->action_name = 'Update';
        //    }
        //    $history->save();
        //}

        if ($lastDeviation->HOD_Remarks != $deviation->HOD_Remarks || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'HOD Remarks';
            $history->previous = $lastDeviation->HOD_Remarks;
            $history->current = $deviation->HOD_Remarks;
            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->HOD_Remarks) || $lastDeviation->HOD_Remarks === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        //if ($lastDeviation->Audit_file != $deviation->Audit_file || !empty($request->comment)) {
        //    // return 'history';
        //        if(is_array($lastDeviation->Audit_file) && count($lastDeviation->Audit_file) > 0){
        //            $history = new DeviationAuditTrail;
        //            $history->deviation_id = $id;
        //            $history->activity_type = 'HOD Attachments';
        //            $history->previous = $lastDeviation->Audit_file;
        //            $history->current = $deviation->Audit_file;
        //            $history->comment = $request->comment;
        //            $history->user_id = Auth::user()->id;
        //            $history->user_name = Auth::user()->name;
        //            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //            $history->origin_state = $lastDeviation->status;
        //            $history->change_to =   "Not Applicable";
        //            $history->change_from = $lastDeviation->status;
        //            if (is_null($lastDeviation->Audit_file) || $lastDeviation->Audit_file === '') {
        //                $history->action_name = 'New';
        //            } else {
        //                $history->action_name = 'Update';
        //            }
        //            $history->save();
        //        }
        //    }

            $previousAttachments = $lastDeviation->Audit_file;
            $areIniAttachmentsSame = $previousAttachments == $deviation->Audit_file;

            if ($areIniAttachmentsSame != true) {

                $existingHistory = DeviationAuditTrail::where('deviation_id', $id)
                ->where('activity_type', 'HOD Attachments')
                ->exists();

                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'HOD Attachments';
                        $history->previous = $previousAttachments;
                        $history->current = $deviation->Audit_file;
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to =   "Not Applicable";
                        $history->change_from = $lastDeviation->status;
                        if ($existingHistory) {
                            $history->action_name = "Update";
                        } else {
                            $history->action_name = "New";
                        }
                        $history->save();
                    }

                    $CftQAattachments = $getCft->Other5_attachment;
                    $areCftAttachments = $CftQAattachments == $Cft->Other5_attachment;

                    if ($areCftAttachments != true) {

                        $existingHistory = DeviationAuditTrail::where('deviation_id', $id)
                        ->where('activity_type', 'CFT Attachments')
                        ->exists();

                                $history = new DeviationAuditTrail;
                                $history->deviation_id = $id;
                                $history->activity_type = 'CFT Attachments';
                                $history->previous = $CftQAattachments;
                                $history->current = $Cft->Other5_attachment;
                                $history->comment = "Not Applicable";
                                $history->user_id = Auth::user()->id;
                                $history->user_name = Auth::user()->name;
                                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $history->origin_state = $lastDeviation->status;
                                $history->change_to =   "Not Applicable";
                                $history->change_from = $lastDeviation->status;
                                if ($existingHistory) {
                                    $history->action_name = "Update";
                                } else {
                                    $history->action_name = "New";
                                }
                                $history->save();
                            }


                    //$getdocument = DeviationCft::where('deviation_id', $id)->first();
                    //if ($getdocument) {
                    //  $getCftDoc = $getdocument->Other5_attachment;

                    //$Cft = DeviationCft::withoutTrashed()->where('deviation_id', $id)->first();

                    //$areIniAttachments = $getCftDoc == $Cft->Other5_attachment;
                    //if ($areIniAttachments != true) {

                    //    $existingHistory = DeviationAuditTrail::where('deviation_id', $id)
                    //    ->where('activity_type', 'CFT Attachments')
                    //    ->exists();

                    //            $history = new DeviationAuditTrail;
                    //            $history->deviation_id = $id;
                    //            $history->activity_type = 'CFT Attachments';
                    //            $history->previous = $getCftDoc;
                    //            $history->current = $Cft->Other5_attachment;
                    //            $history->comment = "Not Applicable";
                    //            $history->user_id = Auth::user()->id;
                    //            $history->user_name = Auth::user()->name;
                    //            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    //            $history->origin_state = $CftDocument->status;
                    //            $history->change_to =   "Not Applicable";
                    //            $history->change_from = $CftDocument->status;
                    //            if ($existingHistory) {
                    //                $history->action_name = "Update";
                    //            } else {
                    //                $history->action_name = "New";
                    //            }
                    //            $history->save();
                    //        }
                    //    }

        //if ($lastDeviation->Other5_attachment != $deviation->Other5_attachment || !empty ($request->comment)) {
        //    // return 'history';
        //    $history = new DeviationAuditTrail;
        //    $history->deviation_id = $id;
        //    $history->activity_type = 'CFT Attachments';
        //    $history->previous = $lastDeviation->Other5_attachment;
        //    $history->current = $deviation->Other5_attachment;
        //    $history->comment = $request->comment;
        //    $history->user_id = Auth::user()->id;
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $lastDeviation->status;
        //    $history->change_to =   "Not Applicable";
        //    $history->change_from = $lastDeviation->status;
        //    if (is_null($lastDeviation->Other5_attachment) || $lastDeviation->Other5_attachment === '') {
        //        $history->action_name = 'New';
        //    } else {
        //        $history->action_name = 'Update';
        //    }
        //    $history->save();
        //}


        if ($lastDeviation->Deviation_category != $deviation->Deviation_category || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Initial Deviation category';
            $history->previous = $lastDeviation->Deviation_category;
            $history->current = $deviation->Deviation_category;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Deviation_category) || $lastDeviation->Deviation_category === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Justification_for_categorization != $deviation->Justification_for_categorization || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Justification for Categorization';
            $history->previous = $lastDeviation->Justification_for_categorization;
            $history->current = $deviation->Justification_for_categorization;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Justification_for_categorization) || $lastDeviation->Justification_for_categorization === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Investigation_required != $deviation->Investigation_required || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Investigation Is required ?';
            $history->previous = $lastDeviation->Investigation_required;
            $history->current = $deviation->Investigation_required;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Investigation_required) || $lastDeviation->Investigation_required === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Investigation_Details != $deviation->Investigation_Details || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Investigation Details';
            $history->previous = $lastDeviation->Investigation_Details;
            $history->current = $deviation->Investigation_Details;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to =   "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Investigation_Details) || $lastDeviation->Investigation_Details === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->short_description_required != $deviation->short_description_required || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Repeat Deviation?';
            $history->previous = $lastDeviation->short_description_required;
            $history->current = $deviation->short_description_required;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->short_description_required) || $lastDeviation->short_description_required === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->nature_of_repeat != $deviation->nature_of_repeat || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Repeat Nature';
            $history->previous = $lastDeviation->nature_of_repeat;
            $history->current = $deviation->nature_of_repeat;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->nature_of_repeat) || $lastDeviation->nature_of_repeat === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }


        if ($lastDeviation->Customer_notification != $deviation->Customer_notification || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Customer Notification Required ?';
            $history->previous = $lastDeviation->Customer_notification;
            $history->current = $deviation->Customer_notification;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Customer_notification) || $lastDeviation->Customer_notification === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->customers != $deviation->customers || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Customer';
            $history->previous = $lastDeviation->customers;
            $history->current = $deviation->customers;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->customers) || $lastDeviation->customers === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->QAInitialRemark != $deviation->QAInitialRemark || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'QA Initial Remarks';
            $history->previous = $lastDeviation->QAInitialRemark;
            $history->current = $deviation->QAInitialRemark;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->QAInitialRemark) || $lastDeviation->QAInitialRemark === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        //Cft tab



         //Cfttabend

         $QaIniattachments = $lastDeviation->Initial_attachment;
         $areIniAttachments = $QaIniattachments == $deviation->Initial_attachment;

         if ($areIniAttachments != true) {

             $existingHistory = DeviationAuditTrail::where('deviation_id', $id)
             ->where('activity_type', 'QA Initial Attachments')
             ->exists();

                     $history = new DeviationAuditTrail;
                     $history->deviation_id = $id;
                     $history->activity_type = 'QA Initial Attachments';
                     $history->previous = $QaIniattachments;
                     $history->current = $deviation->Initial_attachment;
                     $history->comment = "Not Applicable";
                     $history->user_id = Auth::user()->id;
                     $history->user_name = Auth::user()->name;
                     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                     $history->origin_state = $lastDeviation->status;
                     $history->change_to = "Not Applicable";
                     $history->change_from = $lastDeviation->status;
                     if ($existingHistory) {
                         $history->action_name = "Update";
                     } else {
                         $history->action_name = "New";
                     }
                     $history->save();
                 }

        //if ($lastDeviation->Initial_attachment != $deviation->Initial_attachment || !empty ($request->comment)) {
        //    // return 'history';
        //    $history = new DeviationAuditTrail;
        //    $history->deviation_id = $id;
        //    $history->activity_type = 'QA Initial Attachments';
        //    $history->previous = $lastDeviation->Initial_attachment;
        //    $history->current = $deviation->Initial_attachment;
        //    $history->comment = "Not Applicable";
        //    $history->user_id = Auth::user()->id;
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $lastDeviation->status;
        //    $history->change_to =   "Not Applicable";
        //    $history->change_from = $lastDeviation->status;
        //    if (is_null($lastDeviation->Initial_attachment) || $lastDeviation->Initial_attachment === '') {
        //        $history->action_name = 'New';
        //    } else {
        //        $history->action_name = 'Update';
        //    }
        //    $history->save();
        //}

        if ($lastDeviation->Root_cause != $deviation->Root_cause || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Root Cause';
            $history->previous = $lastDeviation->Root_cause;
            $history->current = $deviation->Root_cause;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Root_cause) || $lastDeviation->Root_cause === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }


        if ($lastDeviation->Post_Categorization != $deviation->Post_Categorization || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Post Categorization Of Deviation';
            $history->previous = $lastDeviation->Post_Categorization;
            $history->current = $deviation->Post_Categorization;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Post_Categorization) || $lastDeviation->Post_Categorization === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Investigation_Of_Review != $deviation->Investigation_Of_Review || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Investigation Of Revised Categorization';
            $history->previous = $lastDeviation->Investigation_Of_Review;
            $history->current = $deviation->Investigation_Of_Review;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Investigation_Of_Review) || $lastDeviation->Investigation_Of_Review === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->QA_Feedbacks != $deviation->QA_Feedbacks || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'QA Feedbacks';
            $history->previous = $lastDeviation->QA_Feedbacks;
            $history->current = $deviation->QA_Feedbacks;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->QA_Feedbacks) || $lastDeviation->QA_Feedbacks === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        $previousQAattachments = $lastDeviation->QA_attachments;
        $areQAAttachmentsSame = $previousQAattachments == $deviation->QA_attachments;

        if ($areQAAttachmentsSame != true) {

            $existingHistory = DeviationAuditTrail::where('deviation_id', $id)
            ->where('activity_type', 'QA Attachments')
            ->exists();

                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'QA Attachments';
                    $history->previous = $previousQAattachments;
                    $history->current = $deviation->QA_attachments;
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDeviation->status;
                    $history->change_to = "Not Applicable";
                    $history->change_from = $lastDeviation->status;
                    if ($existingHistory) {
                        $history->action_name = "Update";
                    } else {
                        $history->action_name = "New";
                    }
                    $history->save();
                }

        //if ($lastDeviation->QA_attachments != $deviation->QA_attachments || !empty ($request->comment)) {
        //    // return 'history';
        //    if(is_array($deviation->QA_attachments) && count($deviation->QA_attachments) > 0){
        //    $history = new DeviationAuditTrail;
        //    $history->deviation_id = $id;
        //    $history->activity_type = 'QA Attachments';
        //    $history->previous = $lastDeviation->QA_attachments;
        //    $history->current = $deviation->QA_attachments;
        //    $history->comment = $request->comment;
        //    $history->user_id = Auth::user()->id;
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $lastDeviation->status;
        //    $history->change_to =   "Not Applicable";
        //    $history->change_from = $lastDeviation->status;
        //    if (is_null($lastDeviation->QA_attachments) || $lastDeviation->QA_attachments === '') {
        //        $history->action_name = 'New';
        //    } else {
        //        $history->action_name = 'Update';
        //    }
        //    $history->save();
        //  }
        //}

        if ($lastDeviation->Closure_Comments != $deviation->Closure_Comments || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Closure Comments';
            $history->previous = $lastDeviation->Closure_Comments;
            $history->current = $deviation->Closure_Comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Closure_Comments) || $lastDeviation->Closure_Comments === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->Disposition_Batch != $deviation->Disposition_Batch || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Disposition of Batch';
            $history->previous = $lastDeviation->Disposition_Batch;
            $history->current = $deviation->Disposition_Batch;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->Disposition_Batch) || $lastDeviation->Disposition_Batch === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }

        if ($lastDeviation->closure_attachment != $deviation->closure_attachment || !empty ($request->comment)) {
            // return 'history';
            $history = new DeviationAuditTrail;
            $history->deviation_id = $id;
            $history->activity_type = 'Closure Attachments';
            $history->previous = $lastDeviation->closure_attachment;
            $history->current = $deviation->closure_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDeviation->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDeviation->status;
            if (is_null($lastDeviation->closure_attachment) || $lastDeviation->closure_attachment === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }
            $history->save();
        }


        toastr()->success('Record is Update Successfully');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function launchExtensionDeviation(Request $request, $id){
        $deviation = Deviation::find($id);
        $getCounter = LaunchExtension::where(['deviation_id' => $deviation->id, 'identifier' => "Deviation"])->first();
        if($getCounter && $getCounter->counter == null){
            $counter = 1;
        } else {
            $counter = $getCounter ? $getCounter->counter + 1 : 1;
        }
        if($deviation->id != null){
            $data = LaunchExtension::where([
                'deviation_id' => $deviation->id,
                'identifier' => "Deviation"
            ])->firstOrCreate();

            $data->deviation_id = $request->deviation_id;
            $data->identifier = $request->identifier;
            $data->counter = $counter;
            $data->dev_proposed_due_date = $request->dev_proposed_due_date;
            $data->dev_extension_justification = $request->dev_extension_justification;
            $data->dev_extension_completed_by = $request->dev_extension_completed_by;
            $data->dev_completed_on = $request->dev_completed_on;
            $data->save();

            toastr()->success('Record is Update Successfully');
            return back();
        }
    }

    public function launchExtensionCapa(Request $request, $id){
        $deviation = Deviation::find($id);
        $getCounter = LaunchExtension::where(['deviation_id' => $deviation->id, 'identifier' => "Capa"])->first();
        if($getCounter && $getCounter->counter == null){
            $counter = 1;
        } else {
            $counter = $getCounter ? $getCounter->counter + 1 : 1;
        }
        if($deviation->id != null){

            $data = LaunchExtension::where([
                'deviation_id' => $deviation->id,
                'identifier' => "Capa"
            ])->firstOrCreate();

            $data->deviation_id = $request->deviation_id;
            $data->identifier = $request->identifier;
            $data->counter = $counter;
            $data->capa_proposed_due_date = $request->capa_proposed_due_date;
            $data->capa_extension_justification = $request->capa_extension_justification;
            $data->capa_extension_completed_by = $request->capa_extension_completed_by;
            $data->capa_completed_on = $request->capa_completed_on;
            $data->save();

            toastr()->success('Record is Update Successfully');
            return back();
        }
    }


    public function launchExtensionQrm(Request $request, $id){
        $deviation = Deviation::find($id);
        $getCounter = LaunchExtension::where(['deviation_id' => $deviation->id, 'identifier' => "QRM"])->first();
        if($getCounter && $getCounter->counter == null){
            $counter = 1;
        } else {
            $counter = $getCounter ? $getCounter->counter + 1 : 1;
        }
        if($deviation->id != null){

            $data = LaunchExtension::where([
                'deviation_id' => $deviation->id,
                'identifier' => "QRM"
            ])->firstOrCreate();

            $data->deviation_id = $request->deviation_id;
            $data->identifier = $request->identifier;
            $data->counter = $counter;
            $data->qrm_proposed_due_date = $request->qrm_proposed_due_date;
            $data->qrm_extension_justification = $request->qrm_extension_justification;
            $data->qrm_extension_completed_by = $request->qrm_extension_completed_by;
            $data->qrm_completed_on = $request->qrm_completed_on;
            $data->save();

            toastr()->success('Record is Update Successfully');
            return back();
        }
    }

    public function launchExtensionInvestigation(Request $request, $id){
        $deviation = Deviation::find($id);
        $getCounter = LaunchExtension::where(['deviation_id' => $deviation->id, 'identifier' => "Investigation"])->first();
        if($getCounter && $getCounter->counter == null){
            $counter = 1;
        } else {
            $counter = $getCounter ? $getCounter->counter + 1 : 1;
        }
        if($deviation->id != null){

            $data = LaunchExtension::where([
                'deviation_id' => $deviation->id,
                'identifier' => "Investigation"
            ])->firstOrCreate();

            $data->deviation_id = $request->deviation_id;
            $data->identifier = $request->identifier;
            $data->counter = $counter;
            $data->investigation_proposed_due_date = $request->investigation_proposed_due_date;
            $data->investigation_extension_justification = $request->investigation_extension_justification;
            $data->investigation_extension_completed_by = $request->investigation_extension_completed_by;
            $data->investigation_completed_on = $request->investigation_completed_on;
            $data->save();

            toastr()->success('Record is Update Successfully');
            return back();
        }
    }

    public function deviation_send_stage(Request $request, $id)
    {

        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $deviation = Deviation::find($id);
            $updateCFT = DeviationCft::where('deviation_id', $id)->latest()->first();
            $lastDocument = Deviation::find($id);
            $cftDetails = DeviationCftsResponse::withoutTrashed()->where(['status' => 'In-progress', 'deviation_id' => $id])->distinct('cft_user_id')->count();

            if ($deviation->stage == 1) {

                if ($deviation->form_progress !== 'general-open')
                {
                    Session::flash('swal', [
                        'type' => 'warning',
                        'title' => 'Mandatory Fields!',
                        'message' => 'General Information Tab is yet to be filled'
                    ]);

                    return redirect()->back();
                } else {
                    Session::flash('swal', [
                        'type' => 'success',
                        'title' => 'Success',
                        'message' => 'Sent for HOD review state'
                    ]);
                }

                $deviation->stage = "2";
                $deviation->status = "HOD Review";
                $deviation->submit_by = Auth::user()->name;
                $deviation->submit_on = Carbon::now()->format('d-M-Y H:i A');
                $deviation->submit_comment = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'Submitted By, Submitted On';
                if (is_null($lastDocument->submit_by) || $lastDocument->submit_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->submit_by . ' , ' . $lastDocument->submit_on;

                }
                $history->current = $deviation->submit_by . ' , ' . $deviation->submit_on;
                $history->action='Submit';
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to = "HOD Review";
                $history->change_from = $lastDocument->status;
                $history->stage = 'Plan Proposed';
                if (is_null($lastDocument->submit_by) || $lastDocument->submit_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();

//===========================uncomment karna hai =============
                 $list = Helpers::getHodUserList($deviation->division_id);
                 foreach ($list as $u) {
                    // if ($u->q_m_s_divisions_id == $deviation->division_id) {
                         $email = Helpers::getInitiatorEmail($u->user_id);
                         if ($email !== null) {

                             try {
                                 Mail::send(
                                     'mail.view-mail',
                                     ['data' => $deviation, 'site' => 'DEV', 'history' => 'Submit', 'process' => 'Deviation', 'comment' => $deviation->submit_comment, 'user'=> Auth::user()->name],
                                     //     function ($message) use ($email) {
                                //         $message->to($email)
                                //             ->subject("Document Sent By " . Auth::user()->name);
                                //     }
                                // );
                                 function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Submit Performed"); }
                                );
                             } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                             }
                         }
                    // }
                 }
//=============================uncomment karna hai ==============

                // $list = Helpers::getHeadoperationsUserList();
                // foreach ($list as $u) {
                //     if ($u->q_m_s_divisions_id == $deviation->division_id) {
                //         $email = Helpers::getInitiatorEmail($u->user_id);
                //         if ($email !== null) {

                //             Mail::send(
                //                 'mail.Categorymail',
                //                 ['data' => $deviation],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Activity Performed By " . Auth::user()->name);
                //                 }
                //             );
                //         }
                //     }
                // }

                $deviation->update();
                return back();
            }
            if ($deviation->stage == 2) {

                // Check HOD remark value
                if (!$deviation->HOD_Remarks) {

                    Session::flash('swal', [
                        'title' => 'Mandatory Fields Required!',
                        'message' => 'HOD Remarks is yet to be filled!',
                        'type' => 'warning',
                    ]);

                    return redirect()->back();
                } else {
                    Session::flash('swal', [
                        'type' => 'success',
                        'title' => 'Success',
                        'message' => 'Sent for QA initial review state'
                    ]);
                }

                $deviation->stage = "3";
                $deviation->status = "QA Initial Review";
                $deviation->HOD_Review_Complete_By = Auth::user()->name;
                $deviation->HOD_Review_Complete_On = Carbon::now()->format('d-M-Y H:i A');
                $deviation->HOD_Review_Comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'HOD Review Completed By, HOD Review Completed On';
                if (is_null($lastDocument->HOD_Review_Complete_By) || $lastDocument->HOD_Review_Complete_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->HOD_Review_Complete_By . ' , ' . $lastDocument->HOD_Review_Complete_On;

                }
                $history->current = $deviation->HOD_Review_Complete_By . ' , ' . $deviation->HOD_Review_Complete_On;
                $history->comment = $request->comment;
                $history->action= 'HOD Review Complete';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to = "QA Initial Review";
                $history->change_from = $lastDocument->status;
                $history->stage = 'Plan Approved';
                if (is_null($lastDocument->HOD_Review_Complete_By) || $lastDocument->HOD_Review_Complete_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
                // dd($history->action);
                $list = Helpers::getQAUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'HOD Review Complete', 'process' => 'Deviation', 'comment' => $deviation->HOD_Review_Comments, 'user'=> Auth::user()->name],
                                    //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: HOD Review Complete Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }


                $deviation->update();


                if ($lastDocument->record_number != $deviation->record_number || !empty ($request->comment)) {
                    // return 'history';
                    $history = new DeviationAuditTrail;
                    $history->deviation_id = $id;
                    $history->activity_type = 'Record Number';
                     $history->previous = 'Null';
                    $history->current = Helpers::getDivisionName($deviation->division_id) . '/DEV/' . Helpers::year($deviation->created_at) . '/' . str_pad($deviation->record, 4, '0', STR_PAD_LEFT);
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "Not Applicable";
                    $history->change_from = $lastDocument->status;
                    if (is_null($lastDocument->record_number) || $lastDocument->record_number === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();
                }

                toastr()->success('Document Sent');
                return back();
            }
            if ($deviation->stage == 3) {
                if ($deviation->form_progress !== 'cft')
                {
                    Session::flash('swal', [
                        'type' => 'warning',
                        'title' => 'Mandatory Fields!',
                        'message' => 'QA initial review / CFT Mandatory Tab is yet to be filled!',
                    ]);

                    return redirect()->back();

                }
                //else if($deviation->Production_Review != 'yes'){
                //    Session::flash('swal', [
                //        'type' => 'warning',
                //        'title' => 'Mandatory Fields!',
                //        'message'=>'If you select QA Initial Review Complete, it is mandatory to choose Yes for at least one field. Otherwise, select CFT Review Not Required.'
                //    ]);
                //}

                else {
                    Session::flash('swal', [
                        'type' => 'success',
                        'title' => 'Success',
                        'message' => 'Sent for CFT review state'
                    ]);
                }

                $deviation->stage = "4";
                $deviation->status = "CFT Review";

                $stage = new DeviationCftsResponse();
                $stage->deviation_id = $id;
                $stage->cft_user_id = Auth::user()->id;
                $stage->status = "CFT Required";
                $stage->comment = $request->comment;
                $stage->is_required = 1;
                $stage->save();

                $deviation->QA_Initial_Review_Complete_By = Auth::user()->name;
                $deviation->QA_Initial_Review_Complete_On = Carbon::now()->format('d-M-Y H:i A');
                $deviation->QA_Initial_Review_Comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'QA Initial Review Completed By, QA Initial Review Completed On';

                if (is_null($lastDocument->QA_Initial_Review_Complete_By) || $lastDocument->QA_Initial_Review_Complete_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->QA_Initial_Review_Complete_By . ' , ' . $lastDocument->QA_Initial_Review_Complete_On;

                }
                $history->current = $deviation->QA_Initial_Review_Complete_By . ' , ' . $deviation->QA_Initial_Review_Complete_On;

                $history->action= 'QA Initial Review Complete';
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->change_to = "CFT Review";
                $history->change_from = $lastDocument->status;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Completed';

                if (is_null($lastDocument->QA_Initial_Review_Complete_By) || $lastDocument->QA_Initial_Review_Complete_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                $list = Helpers::getCFTUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'QA Initial Review Complete', 'process' => 'Deviation', 'comment' => $deviation->QA_Initial_Review_Comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Initial Review Complete Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

                if ($request->Deviation_category == 'major' || $request->Deviation_category == 'minor' || $request->Deviation_category == 'critical') {
                    $list = Helpers::getHeadoperationsUserList();
                            foreach ($list as $u) {
                                if ($u->q_m_s_divisions_id == $deviation->division_id) {
                                    $email = Helpers::getInitiatorEmail($u->user_id);
                                    if ($email !== null) {
                                         // Add this if statement
                                         try {
                                                Mail::send(
                                                    'mail.Categorymail',
                                                    ['data' => $deviation],
                                                    function ($message) use ($email) {
                                                        $message->to($email)
                                                            ->subject("Document Sent By " . Auth::user()->name);
                                                    }
                                                );
                                            } catch (\Exception $e) {
                                                //log error
                                            }

                                    }
                                }
                            }
                        }
                        if ($request->Deviation_category == 'major' || $request->Deviation_category == 'minor' || $request->Deviation_category == 'critical') {
                            $list = Helpers::getCEOUserList();
                                    foreach ($list as $u) {
                                        if ($u->q_m_s_divisions_id == $deviation->division_id) {
                                            $email = Helpers::getInitiatorEmail($u->user_id);
                                            if ($email !== null) {
                                                 // Add this if statement
                                                 try {
                                                        Mail::send(
                                                            'mail.Categorymail',
                                                            ['data' => $deviation],
                                                            function ($message) use ($email) {
                                                                $message->to($email)
                                                                    ->subject("Document Sent By " . Auth::user()->name);
                                                            }
                                                        );
                                                    } catch (\Exception $e) {
                                                        //log error
                                                    }

                                            }
                                        }
                                    }
                                }
                                if ($request->Deviation_category == 'major' || $request->Deviation_category == 'minor' || $request->Deviation_category == 'critical') {
                                    $list = Helpers::getCorporateEHSHeadUserList();
                                            foreach ($list as $u) {
                                                if ($u->q_m_s_divisions_id == $deviation->division_id) {
                                                    $email = Helpers::getInitiatorEmail($u->user_id);
                                                    if ($email !== null) {
                                                         // Add this if statement
                                                         try {
                                                                Mail::send(
                                                                    'mail.Categorymail',
                                                                    ['data' => $deviation],
                                                                    function ($message) use ($email) {
                                                                        $message->to($email)
                                                                            ->subject("Document Sent By " . Auth::user()->name);
                                                                    }
                                                                );
                                                            } catch (\Exception $e) {
                                                                //log error
                                                            }

                                                    }
                                                }
                                            }
                                        }

                $deviation->update();
                toastr()->success('Document Sent');
                return back();
            }
            //if ($deviation->stage == 4) {

            //    $deviation->stage = "5";
            //    $deviation->status = "QA Secondary Review";
            //    $deviation->CFT_Review_Complete_By = Auth::user()->name;
            //    $deviation->CFT_Review_Complete_On = Carbon::now()->format('d-M-Y H:i A');
            //    $deviation->CFT_Review_Comments = $request->comment;

            //    $history = new DeviationAuditTrail();
            //    $history->deviation_id = $id;
            //    $history->activity_type = 'CFT Review Completed By, CFT Review Completed On';

            //    if (is_null($lastDocument->CFT_Review_Complete_By) || $lastDocument->CFT_Review_Complete_By === '') {
            //        $history->previous = "";
            //    } else {
            //        $history->previous = $lastDocument->CFT_Review_Complete_By . ' , ' . $lastDocument->CFT_Review_Complete_On;

            //    }
            //    $history->current = $deviation->CFT_Review_Complete_By . ' , ' . $deviation->CFT_Review_Complete_On;

            //    $history->action = 'CFT Review Complete';
            //    $history->comment = $request->comment;
            //    $history->user_id = Auth::user()->id;
            //    $history->user_name = Auth::user()->name;
            //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            //    $history->origin_state = $lastDocument->status;
            //    $history->change_to =   "QA Secondary Review";
            //    $history->change_from = $lastDocument->status;
            //    $history->stage = 'Complete';

            //    if (is_null($lastDocument->CFT_Review_Complete_By) || $lastDocument->CFT_Review_Complete_By === '') {
            //        $history->action_name = 'New';
            //    } else {
            //        $history->action_name = 'Update';
            //    }
            //    $history->save();

            //    // $list = Helpers::getQAUserList();
            //    //     foreach ($list as $u) {
            //    //         if ($u->q_m_s_divisions_id == $deviation->division_id) {
            //    //             $email = Helpers::getInitiatorEmail($u->user_id);
            //    //             if ($email !== null) {
            //    //                 try {
            //    //                     Mail::send(
            //    //                         'mail.view-mail',
            //    //                         ['data' => $deviation],
            //    //                         function ($message) use ($email) {
            //    //                             $message->to($email)
            //    //                                 ->subject("Activity Performed By " . Auth::user()->name);
            //    //                         }
            //    //                     );
            //    //                 } catch (\Exception $e) {
            //    //                     //log error
            //    //                 }
            //    //             }
            //    //         }
            //    //     }
            //    $deviation->update();
            //    toastr()->success('Document Sent');
            //    return back();
            //}
            if ($deviation->stage == 4) {

                // CFT review state update form_progress
//                if ($deviation->form_progress !== 'cft')
//                {
//dd("test");
//                    Session::flash('swal', [
//                        'type' => 'warning',
//                        'title' => 'Mandatory Fields!',
//                        'message' => 'CFT Tab is yet to be filled'
//                    ]);

//                    return redirect()->back();
//                } else {
//dd("test");
//                    Session::flash('swal', [
//                        'type' => 'success',
//                        'title' => 'Success',
//                        'message' => 'Sent for QA Secondary Review state'
//                    ]);
//                }


                $getCft = DeviationCft::find($id);
                $Cft = DeviationCft::withoutTrashed()->where('deviation_id', $id)->first();

                $IsCFTRequired = DeviationCftsResponse::withoutTrashed()->where(['is_required' => 1, 'deviation_id' => $id])->latest()->first();
                $cftUsers = DB::table('deviationcfts')->where(['deviation_id' => $id])->first();
                // Define the column names
                $columns = ['Production_person','Warehouse_notification','Quality_Control_Person', 'QualityAssurance_person', 'Engineering_person', 'Analytical_Development_person', 'Kilo_Lab_person', 'Technology_transfer_person', 'Environment_Health_Safety_person', 'Human_Resource_person', 'Information_Technology_person', 'Project_management_person', 'Other1_person', 'Other2_person', 'Other3_person', 'Other4_person', 'Other5_person'];
                // $columns2 = ['Production_review', 'Warehouse_review', 'Quality_Control_review', 'QualityAssurance_review', 'Engineering_review', 'Analytical_Development_review', 'Kilo_Lab_review', 'Technology_transfer_review', 'Environment_Health_Safety_review', 'Human_Resource_review', 'Information_Technology_review', 'Project_management_review'];

                // Initialize an array to store the values
                $valuesArray = [];

                // Iterate over the columns and retrieve the values
                foreach ($columns as $index => $column) {
                    $value = $cftUsers->$column;
                    $counter = 0;
                    //if($column == 'Production_person' && $cftUsers->$column == Auth::user()->id){
                    if($index == 0 && $cftUsers->$column == Auth::user()->id){
                        $counter++;

                        $updateCFT->production_by = Auth::user()->name;

                        if ($getCft->production_by != $updateCFT->production_by) {
                            // return 'history';
                            $history = new DeviationAuditTrail;
                            $history->deviation_id = $id;
                            $history->activity_type = 'Production Review Completed By';
                            $history->previous = $getCft->production_by;
                            $history->current = $updateCFT->production_by;
                            $history->comment = "Not Applicable";
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            //$history->origin_state = $lastDeviation->status;
                            $history->change_to = "Not Applicable";
                            $history->change_from = "CFT";
                            if (is_null($getCft->production_by) || $getCft->production_by === '') {
                                $history->action_name = 'New';
                            } else {
                                $history->action_name = 'Update';
                            }
                            $history->save();
                        }

                        $updateCFT->production_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->production_on != $updateCFT->production_on) {
                            // return 'history';
                            $history = new DeviationAuditTrail;
                            $history->deviation_id = $id;
                            $history->activity_type = 'Production Review Completed On';
                            $history->previous = Helpers::getdateFormat($getCft->production_on);
                            $history->current = Helpers::getdateFormat($updateCFT->production_on);
                            $history->comment = "Not Applicable";
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            //$history->origin_state = $lastDeviation->status;
                            $history->change_to = "Not Applicable";
                            $history->change_from = "CFT";
                            if (is_null($getCft->production_on) || $getCft->production_on === '') {
                                $history->action_name = 'New';
                            } else {
                                $history->action_name = 'Update';
                            }
                            $history->save();
                        }


                    }
                    if($index == 1 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Warehouse_by = Auth::user()->name;

                        if ($getCft->Warehouse_by != $updateCFT->Warehouse_by) {
                            // return 'history';
                            $history = new DeviationAuditTrail;
                            $history->deviation_id = $id;
                            $history->activity_type = 'Warehouse Review Completed By';
                            $history->previous = $getCft->Warehouse_by;
                            $history->current = $updateCFT->Warehouse_by;
                            $history->comment = "Not Applicable";
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            //$history->origin_state = $lastDeviation->status;
                            $history->change_to = "Not Applicable";
                            $history->change_from = "CFT";
                            if (is_null($getCft->Warehouse_by) || $getCft->Warehouse_by === '') {
                                $history->action_name = 'New';
                            } else {
                                $history->action_name = 'Update';
                            }
                            $history->save();
                        }

                        $updateCFT->Warehouse_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Warehouse_on != $updateCFT->Warehouse_on) {
                            // return 'history';
                            $history = new DeviationAuditTrail;
                            $history->deviation_id = $id;
                            $history->activity_type = 'Warehouse Review Completed On';
                            $history->previous = Helpers::getdateFormat($getCft->Warehouse_on);
                            $history->current = Helpers::getdateFormat($updateCFT->Warehouse_on);
                            $history->comment = "Not Applicable";
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            //$history->origin_state = $lastDeviation->status;
                            $history->change_to = "Not Applicable";
                            $history->change_from = "CFT";
                            if (is_null($getCft->Warehouse_on) || $getCft->Warehouse_on === '') {
                                $history->action_name = 'New';
                            } else {
                                $history->action_name = 'Update';
                            }
                            $history->save();
                        }


                    }
                    if($index == 2 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Quality_Control_by = Auth::user()->name;

                        if ($getCft->Quality_Control_by != $updateCFT->Quality_Control_by) {
                            // return 'history';
                            $history = new DeviationAuditTrail;
                            $history->deviation_id = $id;
                            $history->activity_type = 'Quality Control Review Completed By';
                            $history->previous = $getCft->Quality_Control_by;
                            $history->current = $updateCFT->Quality_Control_by;
                            $history->comment = "Not Applicable";
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            //$history->origin_state = $lastDeviation->status;
                            $history->change_to = "Not Applicable";
                            $history->change_from = "CFT";
                            if (is_null($getCft->Quality_Control_by) || $getCft->Quality_Control_by === '') {
                                $history->action_name = 'New';
                            } else {
                                $history->action_name = 'Update';
                            }
                            $history->save();
                        }

                        $updateCFT->Quality_Control_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Quality_Control_on != $updateCFT->Quality_Control_on) {
                            // return 'history';
                            $history = new DeviationAuditTrail;
                            $history->deviation_id = $id;
                            $history->activity_type = 'Quality Control Review Completed On';
                            $history->previous = Helpers::getdateFormat($getCft->Quality_Control_on);
                            $history->current = Helpers::getdateFormat($updateCFT->Quality_Control_on);
                            $history->comment = "Not Applicable";
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            //$history->origin_state = $lastDeviation->status;
                            $history->change_to = "Not Applicable";
                            $history->change_from = "CFT";
                            if (is_null($getCft->Quality_Control_on) || $getCft->Quality_Control_on === '') {
                                $history->action_name = 'New';
                            } else {
                                $history->action_name = 'Update';
                            }
                            $history->save();
                        }


                    }
                    if($index == 3 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->QualityAssurance_by = Auth::user()->name;

                        if ($getCft->QualityAssurance_by != $updateCFT->QualityAssurance_by) {
                            // return 'history';
                            $history = new DeviationAuditTrail;
                            $history->deviation_id = $id;
                            $history->activity_type = 'Quality Assurance Review Completed By';
                            $history->previous = $getCft->QualityAssurance_by;
                            $history->current = $updateCFT->QualityAssurance_by;
                            $history->comment = "Not Applicable";
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            //$history->origin_state = $lastDeviation->status;
                            $history->change_to = "Not Applicable";
                            $history->change_from = "CFT";
                            if (is_null($getCft->QualityAssurance_by) || $getCft->QualityAssurance_by === '') {
                                $history->action_name = 'New';
                            } else {
                                $history->action_name = 'Update';
                            }
                            $history->save();
                        }

                        $updateCFT->QualityAssurance_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->QualityAssurance_on != $updateCFT->QualityAssurance_on) {
                            // return 'history';
                            $history = new DeviationAuditTrail;
                            $history->deviation_id = $id;
                            $history->activity_type = 'Quality Assurance Review Completed On';
                            $history->previous = Helpers::getdateFormat($getCft->QualityAssurance_on);
                            $history->current = Helpers::getdateFormat($updateCFT->QualityAssurance_on);
                            $history->comment = "Not Applicable";
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            //$history->origin_state = $lastDeviation->status;
                            $history->change_to = "Not Applicable";
                            $history->change_from = "CFT";
                            if (is_null($getCft->QualityAssurance_on) || $getCft->QualityAssurance_on === '') {
                                $history->action_name = 'New';
                            } else {
                                $history->action_name = 'Update';
                            }
                            $history->save();
                        }

                    }
                    if($index == 4 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Engineering_by = Auth::user()->name;

                        if ($getCft->Engineering_by != $updateCFT->Engineering_by) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Engineering Review Completed By';
                        $history->previous = $getCft->Engineering_by;
                        $history->current = $Cft->Engineering_by;
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        //$history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Engineering_by) || $updateCFT->Engineering_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                        $updateCFT->Engineering_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Engineering_on != $updateCFT->Engineering_on) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Engineering Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Engineering_on);
                        $history->current = Helpers::getdateFormat($updateCFT->Engineering_on);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        //$history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Engineering_on) || $getCft->Engineering_on === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                    }
                    if($index == 5 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Analytical_Development_by = Auth::user()->name;

                        if ($getCft->Analytical_Development_by != $updateCFT->Analytical_Development_by) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Analytical Development Laboratory Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Analytical_Development_by);
                        $history->current = Helpers::getdateFormat($updateCFT->Analytical_Development_by);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        //$history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Analytical_Development_by) || $getCft->Analytical_Development_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                        $updateCFT->Analytical_Development_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Analytical_Development_on != $updateCFT->Analytical_Development_on) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Analytical Development Laboratory Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Analytical_Development_on);
                        $history->current = Helpers::getdateFormat($updateCFT->Analytical_Development_on);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        //$history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Analytical_Development_on) || $getCft->Analytical_Development_on === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                    }
                    if($index == 6 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Kilo_Lab_attachment_by = Auth::user()->name;

                        if ($getCft->Kilo_Lab_attachment_by != $updateCFT->Kilo_Lab_attachment_by) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Process Development Laboratory / Kilo Lab Review Completed By';
                        $history->previous = $getCft->Kilo_Lab_attachment_by;
                        $history->current = $updateCFT->Kilo_Lab_attachment_by;
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Kilo_Lab_attachment_by) || $getCft->Kilo_Lab_attachment_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                        $updateCFT->Kilo_Lab_attachment_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Kilo_Lab_attachment_on != $updateCFT->Kilo_Lab_attachment_on) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Process Development Laboratory / Kilo Lab Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Kilo_Lab_attachment_on);
                        $history->current = Helpers::getdateFormat($updateCFT->Kilo_Lab_attachment_on);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Kilo_Lab_attachment_on) || $getCft->Kilo_Lab_attachment_on === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }


                    }
                    if($index == 7 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Technology_transfer_by = Auth::user()->name;

                        if ($getCft->Technology_transfer_by != $updateCFT->Technology_transfer_by) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Technology Transfer / Design Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Technology_transfer_by);
                        $history->current = Helpers::getdateFormat($updateCFT->Technology_transfer_by);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Technology_transfer_by) || $getCft->Technology_transfer_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                        $updateCFT->Technology_transfer_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Technology_transfer_on != $updateCFT->Technology_transfer_on) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Technology Transfer / Design Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Technology_transfer_on);
                        $history->current = Helpers::getdateFormat($updateCFT->Technology_transfer_on);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Technology_transfer_on) || $getCft->Technology_transfer_on === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                     }
                    if($index == 8 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Environment_Health_Safety_by = Auth::user()->name;

                        if ($getCft->Environment_Health_Safety_by != $updateCFT->Environment_Health_Safety_by) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Environment, Health & Safety Review Completed By';
                        $history->previous = Helpers::getInitiatorName($getCft->Environment_Health_Safety_by);
                        $history->current = Helpers::getInitiatorName($updateCFT->Environment_Health_Safety_by);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Environment_Health_Safety_by) || $getCft->Environment_Health_Safety_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                        $updateCFT->Environment_Health_Safety_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Environment_Health_Safety_on != $updateCFT->Environment_Health_Safety_on) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Environment, Health & Safety Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Environment_Health_Safety_on);
                        $history->current = Helpers::getdateFormat($updateCFT->Environment_Health_Safety_on);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Environment_Health_Safety_on) || $getCft->Environment_Health_Safety_on === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                    }
                    if($index == 9 && $cftUsers->$column == Auth::user()->id){

                       $updateCFT->Human_Resource_by = Auth::user()->name;

                       if ($getCft->Human_Resource_by != $updateCFT->Human_Resource_by) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Human Resource & Administration Review Completed By';
                        $history->previous = $getCft->Human_Resource_by;
                        $history->current = $updateCFT->Human_Resource_by;
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Human_Resource_by) || $getCft->Human_Resource_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                        $updateCFT->Human_Resource_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Human_Resource_on != $updateCFT->Human_Resource_on) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Human Resource & Administration Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Human_Resource_on);
                        $history->current = Helpers::getdateFormat($updateCFT->Human_Resource_on);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Human_Resource_on) || $getCft->Human_Resource_on === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }


                    }
                    if($index == 10 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Information_Technology_by = Auth::user()->name;

                        if ($getCft->Information_Technology_by != $updateCFT->Information_Technology_by) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Information Technology Review Completed By';
                        $history->previous = $getCft->Information_Technology_by;
                        $history->current = $updateCFT->Information_Technology_by;
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Information_Technology_by) || $getCft->Information_Technology_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                        $updateCFT->Information_Technology_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Information_Technology_on != $updateCFT->Information_Technology_on) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Information Technology Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Information_Technology_on);
                        $history->current = Helpers::getdateFormat($updateCFT->Information_Technology_on);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Information_Technology_on) || $getCft->Information_Technology_on === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                    }
                    if($index == 11 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Project_management_by = Auth::user()->name;

                        if ($getCft->Project_management_by != $updateCFT->Project_management_by) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Project management Review Completed By';
                        $history->previous = $getCft->Project_management_by;
                        $history->current = $updateCFT->Project_management_by;
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Project_management_by) || $getCft->Project_management_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                        $updateCFT->Project_management_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Project_management_on != $updateCFT->Project_management_on) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Project management Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Project_management_on);
                        $history->current = Helpers::getdateFormat($updateCFT->Project_management_on);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Project_management_on) || $getCft->Project_management_on === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                    }
                    if($index == 12 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Other1_by = Auth::user()->name;

                        if ($getCft->Other1_by != $updateCFT->Other1_by) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Others 1 Review Completed By';
                        $history->previous = $getCft->Other1_by;
                        $history->current = $updateCFT->Other1_by;
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Other1_by) || $getCft->Other1_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                        $updateCFT->Other1_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Other1_on != $updateCFT->Other1_on) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Others 1 Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Other1_on);
                        $history->current = Helpers::getdateFormat($updateCFT->Other1_on);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Other1_on) || $getCft->Other1_on === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }


                    }
                    if($index == 13 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Other2_by = Auth::user()->name;

                        if ($getCft->Other2_by != $updateCFT->Other2_by) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Others 2 Review Completed By';
                        $history->previous = $getCft->Other2_by;
                        $history->current = $updateCFT->Other2_by;
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Other2_by) || $getCft->Other2_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                        $updateCFT->Other2_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Other2_on != $updateCFT->Other2_on) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Others 2 Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Other2_on);
                        $history->current = Helpers::getdateFormat($updateCFT->Other2_on);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Other2_on) || $getCft->Other2_on === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }
                    }
                    if($index == 14 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Other3_by = Auth::user()->name;

                        if ($getCft->Other3_by != $updateCFT->Other3_by) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Others 3 Review Completed By';
                        $history->previous = $getCft->Other3_by;
                        $history->current = $updateCFT->Other3_by;
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Other3_by) || $getCft->Other3_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                        $updateCFT->Other3_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Other3_on != $updateCFT->Other3_on) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Others 3 Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Other3_on);
                        $history->current = Helpers::getdateFormat($updateCFT->Other3_on);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Other3_on) || $getCft->Other3_on === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                    }
                    if($index == 15 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Other4_by = Auth::user()->name;

                        if ($getCft->Other4_by != $updateCFT->Other4_by) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Others 4 Review Completed By';
                        $history->previous = $getCft->Other4_by;
                        $history->current = $updateCFT->Other4_by;
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Other4_by) || $getCft->Other4_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }


                        $updateCFT->Other4_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Other4_on != $updateCFT->Other4_on) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Others 4 Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Other4_on);
                        $history->current = Helpers::getdateFormat($updateCFT->Other4_on);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Other4_on) || $getCft->Other4_on === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                    }
                    if($index == 16 && $cftUsers->$column == Auth::user()->id){

                        $updateCFT->Other5_by = Auth::user()->name;

                        if ($getCft->Other5_by != $updateCFT->Other5_by) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Others 5 Review Completed By';
                        $history->previous = $getCft->Other5_by;
                        $history->current = $updateCFT->Other5_by;
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Other5_by) || $getCft->Other5_by === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }

                        $updateCFT->Other5_on = Carbon::now()->format('Y-m-d');

                        if ($getCft->Other5_on != $updateCFT->Other5_on) {
                        // return 'history';
                        $history = new DeviationAuditTrail;
                        $history->deviation_id = $id;
                        $history->activity_type = 'Others 5 Review Completed On';
                        $history->previous = Helpers::getdateFormat($getCft->Other5_on);
                        $history->current = Helpers::getdateFormat($updateCFT->Other5_on);
                        $history->comment = "Not Applicable";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDeviation->status;
                        $history->change_to = "Not Applicable";
                        $history->change_from = "CFT";
                        if (is_null($getCft->Other5_on) || $getCft->Other5_on === '') {
                            $history->action_name = 'New';
                        } else {
                            $history->action_name = 'Update';
                        }
                        $history->save();
                    }
                    }

                    $updateCFT->update();

                    // Check if the value is not null and not equal to 0
                    if ($value != null && $value != 0) {
                        $valuesArray[] = $value;
                    }
                }

                //dd($counter);
                // dd($valuesArray, count(array_unique($valuesArray)), ($cftDetails+1));
                if ($IsCFTRequired) {
                    if (count(array_unique($valuesArray)) == ($cftDetails + 1)) {
                        $stage = new DeviationCftsResponse();
                        $stage->deviation_id = $id;
                        $stage->cft_user_id = Auth::user()->id;
                        $stage->status = "Completed";
                        // $stage->cft_stage = ;
                        $stage->comment = $request->comment;
                        $stage->save();
                    } else {
                        $stage = new DeviationCftsResponse();
                        $stage->deviation_id = $id;
                        $stage->cft_user_id = Auth::user()->id;
                        $stage->status = "In-progress";
                        // $stage->cft_stage = ;
                        $stage->comment = $request->comment;
                        $stage->save();
                    }
                }

                $checkCFTCount = DeviationCftsResponse::withoutTrashed()->where(['status' => 'Completed', 'deviation_id' => $id])->count();
                // dd(count(array_unique($valuesArray)), $checkCFTCount);


                //if ($checkCFTCount) {
                    //dd("test");

                    $deviation->stage = "5";
                    $deviation->status = "QA Secondary Review";
                    $deviation->CFT_Review_Complete_By = Auth::user()->name;
                    $deviation->CFT_Review_Complete_On = Carbon::now()->format('d-M-Y H:i A');
                    $deviation->CFT_Review_Comments = $request->comment;

                    $history = new DeviationAuditTrail();
                    $history->deviation_id = $id;
                    $history->activity_type = 'CFT Review Completed By, CFT Review Completed On';

                    if (is_null($lastDocument->CFT_Review_Complete_By) || $lastDocument->CFT_Review_Complete_By === '') {
                        $history->previous = "";
                    } else {
                        $history->previous = $lastDocument->CFT_Review_Complete_By . ' , ' . $lastDocument->CFT_Review_Complete_On;

                    }
                    $history->current = $deviation->CFT_Review_Complete_By . ' , ' . $deviation->CFT_Review_Complete_On;

                    $history->action = 'CFT Review Complete';
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to = "QA Secondary Review";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'Complete';

                    if (is_null($lastDocument->CFT_Review_Complete_By) || $lastDocument->CFT_Review_Complete_By === '') {
                        $history->action_name = 'New';
                    } else {
                        $history->action_name = 'Update';
                    }
                    $history->save();


                    $list = Helpers::getQAHeadUserList($deviation->division_id);
                     foreach ($list as $u) {
                        // if ($u->q_m_s_divisions_id == $deviation->division_id) {
                             $email = Helpers::getInitiatorEmail($u->user_id);
                             if ($email !== null) {
                                 try {
                                     Mail::send(
                                         'mail.view-mail',
                                         ['data' => $deviation, 'site' => 'DEV', 'history' => 'CFT Review Complete', 'process' => 'Deviation', 'comment' => $deviation->CFT_Review_Comments, 'user'=> Auth::user()->name],
                                         //     function ($message) use ($email) {
                                    //         $message->to($email)
                                    //             ->subject("Document Sent By " . Auth::user()->name);
                                    //     }
                                    // );
                                           function ($message) use ($email, $deviation, $history) {
                                                $message->to($email)
                                                ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: CFT Review Complete Performed"); }
                                            );
                                 } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                 }
                             }
                        // }
                     }

                    $deviation->update();
                    toastr()->success('Document Sent');
                    return back();
            //}
        }

            if ($deviation->stage == 5) {

                if ($deviation->form_progress === 'capa' && !empty($deviation->QA_Feedbacks))
                {
                    Session::flash('swal', [
                        'type' => 'success',
                        'title' => 'Success',
                        'message' => 'Sent for QA Head/Manager Designee Primary Approval'
                    ]);

                } else {
                    Session::flash('swal', [
                        'type' => 'warning',
                        'title' => 'Mandatory Fields!',
                        'message' => 'QA Secondary Review Tab is yet to be filled!'
                    ]);

                    return redirect()->back();
                }


                $deviation->stage = "6";
                $deviation->status = "QA Head/Manager Designee Primary Approval";
                $deviation->QA_Secondary_Review_Complete_By = Auth::user()->name;
                $deviation->QA_Secondary_Review_Complete_On = Carbon::now()->format('d-M-Y H:i A');
                $deviation->QA_Secondary_Review_Completed_Comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'QA Secondary Review Completed By, QA Secondary Review Completed On';

                if (is_null($lastDocument->QA_Secondary_Review_Complete_By) || $lastDocument->QA_Secondary_Review_Complete_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->QA_Secondary_Review_Complete_By . ' , ' . $lastDocument->QA_Secondary_Review_Complete_On;

                }

                $history->current = $deviation->QA_Secondary_Review_Complete_By . ' , ' . $deviation->QA_Secondary_Review_Complete_On;

                $history->comment = $request->comment;
                $history->action ='QA Secondary Review Complete';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to = "QA Head/Manager Designee Primary Approval";
                $history->change_from = $lastDocument->status;
                $history->stage = 'QAH Primary Approved Completed';

                if (is_null($lastDocument->QA_Secondary_Review_Complete_By) || $lastDocument->QA_Secondary_Review_Complete_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                //$list = Helpers::getQAHeadUserList($deviation->division_id);
                //foreach ($list as $u) {
                //    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                //        $email = Helpers::getInitiatorEmail($u->user_id);
                //        if ($email !== null) {
                //            try {
                //                Mail::send(
                //                    'mail.view-mail',
                //                    ['data' => $deviation],
                //                    function ($message) use ($email) {
                //                        $message->to($email)
                //                            ->subject("Document Sent By " . Auth::user()->name);
                //                    }
                //                );
                //            } catch (\Exception $e) {
                //                \Log::error('Mail failed to send: ' . $e->getMessage());
                //            }
                //        }
                //    //}
                //}
                $deviation->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($deviation->stage == 6) {

                // if ($deviation->form_progress !== 'qah')
                // {

                //     Session::flash('swal', [
                //         'title' => 'Mandatory Fields!',
                //         'message' => 'QAH/Designee Approval Tab is yet to be filled!',
                //         'type' => 'warning',
                //     ]);

                //     return redirect()->back();
                // } else {
                //     Session::flash('swal', [
                //         'type' => 'success',
                //         'title' => 'Success',
                //         'message' => 'Deviation sent to Intiator Update'
                //     ]);
                // }

                // $extension = Extension::where('parent_id', $deviation->id)->first();

                // $rca = RootCauseAnalysis::where('record', $deviation->id)->first();

                // if ($extension && $extension->status !== 'Closed-Done') {
                //     Session::flash('swal', [
                //         'title' => 'Extension record pending!',
                //         'message' => 'There is an Extension record which is yet to be closed/done!',
                //         'type' => 'warning',
                //     ]);

                //     return redirect()->back();
                // }

                // if ($rca && $rca->status !== 'Closed-Done') {
                //     Session::flash('swal', [
                //         'title' => 'RCA record pending!',
                //         'message' => 'There is an Root Cause Analysis record which is yet to be closed/done!',
                //         'type' => 'warning',
                //     ]);

                //     return redirect()->back();
                // }

                // return "PAUSE";

                $deviation->stage = "7";
                $deviation->status = "Pending Initiator Update";
                $deviation->Approved_By = Auth::user()->name;
                $deviation->Approved_On = Carbon::now()->format('d-M-Y H:i A');
                $deviation->Approved_Comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'QAH Primary Approval Completed By, QAH Primary Approval Completed On';

                if (is_null($lastDocument->Approved_By) || $lastDocument->Approved_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->Approved_By . ' , ' . $lastDocument->Approved_On;

                }

                $history->current = $deviation->Approved_By . ' , ' . $deviation->Approved_On;

                $history->action ='QAH Primary Approved Completed';
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to = "Pending Initiator Update";
                $history->change_from = $lastDocument->status;
                $history->stage = 'Completed';

                if (is_null($lastDocument->Approved_By) || $lastDocument->Approved_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                //$list = Helpers::getQAUserList($deviation->division_id);
                //foreach ($list as $u) {
                //    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                //        $email = Helpers::getInitiatorEmail($u->user_id);
                //        if ($email !== null) {
                //            try {
                //                Mail::send(
                //                    'mail.view-mail',
                //                    ['data' => $deviation],
                //                    function ($message) use ($email) {
                //                        $message->to($email)
                //                            ->subject("Document Sent By " . Auth::user()->name);
                //                    }
                //                );
                //            } catch (\Exception $e) {
                //                \Log::error('Mail failed to send: ' . $e->getMessage());
                //            }
                //        }
                //    //}
                //}

                $list = Helpers::getInitiatorUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'QAH Primary Approval Completed Performed', 'process' => 'Deviation', 'comment' => $deviation->Approved_Comments, 'user'=> Auth::user()->name],
                                    //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: QAH Primary Approval Completed Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

                //$list = Helpers::getCFTUserList($deviation->division_id);
                //foreach ($list as $u) {
                //    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                //        $email = Helpers::getInitiatorEmail($u->user_id);
                //        if ($email !== null) {
                //            try {
                //                Mail::send(
                //                    'mail.view-mail',
                //                    ['data' => $deviation],
                //                    function ($message) use ($email) {
                //                        $message->to($email)
                //                            ->subject("Document Sent By " . Auth::user()->name);
                //                    }
                //                );
                //            } catch (\Exception $e) {
                //                \Log::error('Mail failed to send: ' . $e->getMessage());
                //            }
                //        }
                //    //}
                //}
                $deviation->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($deviation->stage == 7) {

                // Check HOD remark value
                if (!$deviation->initiator_final_remarks) {

                    Session::flash('swal', [
                        'title' => 'Mandatory Fields Required!',
                        'message' => 'Initiator Final Remarks is yet to be filled!',
                        'type' => 'warning',
                    ]);

                    return redirect()->back();
                } else {
                    Session::flash('swal', [
                        'type' => 'success',
                        'title' => 'Success',
                        'message' => 'Sent for HOD Final Review state'
                    ]);
                }

                $deviation->stage = "8";
                $deviation->status = "HOD Final Review";
                $deviation->Initiator_Update_Completed_By = Auth::user()->name;
                $deviation->Initiator_Update_Completed_On = Carbon::now()->format('d-M-Y H:i A');
                $deviation->Initiator_Update_Completed_Comments = $request->comment;


                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'Initiator Update Completed By, Initiator Update Completed On';

                if (is_null($lastDocument->Initiator_Update_Completed_By) || $lastDocument->Initiator_Update_Completed_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->Initiator_Update_Completed_By . ' , ' . $lastDocument->Initiator_Update_Completed_On;

                }
                $history->current = $deviation->Initiator_Update_Completed_By . ' , ' . $deviation->Initiator_Update_Completed_On;

                $history->action ='Initiator Update Complete';
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to = "HOD Final Review";
                $history->change_from = $lastDocument->status;
                $history->stage = 'Completed';

                if (is_null($lastDocument->Initiator_Update_Completed_By) || $lastDocument->Initiator_Update_Completed_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                $list = Helpers::getHodUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'Initiator Update Completed Performed', 'process' => 'Deviation', 'comment' => $deviation->Initiator_Update_Completed_Comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Initiator Update Completed Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }
                $deviation->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($deviation->stage == 8) {
                // Check HOD remark value
                if (!$deviation->hod_final_remarks) {

                    Session::flash('swal', [
                        'title' => 'Mandatory Fields Required!',
                        'message' => 'HOD Final Remarks is yet to be filled!',
                        'type' => 'warning',
                    ]);

                    return redirect()->back();
                } else {
                    Session::flash('swal', [
                        'type' => 'success',
                        'title' => 'Success',
                        'message' => 'Sent for QA Final Review state'
                    ]);
                }

                $deviation->stage = "9";
                $deviation->status = "QA Final Review";
                $deviation->HOD_Final_Review_By = Auth::user()->name;
                $deviation->HOD_Final_Review_On = Carbon::now()->format('d-M-Y H:i A');
                $deviation->HOD_Final_Review_Comments = $request->comment;


                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'HOD Final Review Completed By, HOD Final Review Completed On';
                //dd($lastDocument->HOD_Final_Review_By);
                if (is_null($lastDocument->HOD_Final_Review_By) || $lastDocument->HOD_Final_Review_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->HOD_Final_Review_By . ' , ' . $lastDocument->HOD_Final_Review_On;

                }
                $history->current = $deviation->HOD_Final_Review_By . ' , ' . $deviation->HOD_Final_Review_On;

                $history->action ='HOD Final Review Complete';
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to = "QA Final Review";
                $history->change_from = $lastDocument->status;
                $history->stage = 'Completed';

                if (is_null($lastDocument->HOD_Final_Review_By) || $lastDocument->HOD_Final_Review_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                 $list = Helpers::getQaReviewerList($deviation->division_id);
                 foreach ($list as $u) {
                    // if ($u->q_m_s_divisions_id == $deviation->division_id) {
                         $email = Helpers::getInitiatorEmail($u->user_id);
                         if ($email !== null) {
                             try {
                                 Mail::send(
                                     'mail.view-mail',
                                     ['data' => $deviation, 'site' => 'DEV', 'history' => 'HOD Final Review Complete', 'process' => 'Deviation', 'comment' => $deviation->HOD_Final_Review_Comments, 'user'=> Auth::user()->name],
                                     //     function ($message) use ($email) {
                                //         $message->to($email)
                                //             ->subject("Document Sent By " . Auth::user()->name);
                                //     }
                                // );

                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: HOD Final Review Complete Performed"); }
                                );
                             } catch (\Exception $e) {
                                 //log error
                             }
                         }
                    // }
                 }
                $deviation->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($deviation->stage == 9) {
                // Check HOD remark value
                if (!$deviation->qa_final_remarks) {

                    Session::flash('swal', [
                        'title' => 'Mandatory Fields Required!',
                        'message' => 'QA Final Remarks is yet to be filled!',
                        'type' => 'warning',
                    ]);

                    return redirect()->back();
                } else {
                    Session::flash('swal', [
                        'type' => 'success',
                        'title' => 'Success',
                        'message' => 'Sent for QA Final Approval state'
                    ]);
                }
                $deviation->stage = "10";
                $deviation->status = "QA Final Approval";
                $deviation->QA_Final_Review_By = Auth::user()->name;
                $deviation->QA_Final_Review_On = Carbon::now()->format('d-M-Y H:i A');
                $deviation->QA_Final_Review_Comments = $request->comment;



                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'QA Final Review Completed By, QA Final Review Completed On';

                if (is_null($lastDocument->QA_Final_Review_By) || $lastDocument->QA_Final_Review_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->QA_Final_Review_By . ' , ' . $lastDocument->QA_Final_Review_On;

                }
                $history->current = $deviation->QA_Final_Review_By . ' , ' . $deviation->QA_Final_Review_On;

                $history->action ='QA Secondary Review Complete';
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to = "QA Final Approval";
                $history->change_from = $lastDocument->status;
                $history->stage = 'Completed';

                if (is_null($lastDocument->QA_Final_Review_By) || $lastDocument->QA_Final_Review_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                $list = Helpers::getQAHeadUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'QA Final Review Complete', 'process' => 'Deviation', 'comment' => $deviation->QA_Final_Review_Comments, 'user'=> Auth::user()->name],
                                    //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Activity Performed By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Final Review Complete Performed"); }
                                );
                            } catch (\Exception $e) {
                                //log error
                            }
                        }
                    //}
                }
                $deviation->update();
                toastr()->success('Document Sent');
                return back();
            }


            if ($deviation->stage == 10) {

                // Check HOD remark value
                if (!$deviation->Closure_Comments) {

                    Session::flash('swal', [
                        'title' => 'Mandatory Fields Required!',
                        'message' => 'Closure Comments is yet to be filled!',
                        'type' => 'warning',
                    ]);

                    return redirect()->back();
                }
                if(!$deviation->Disposition_Batch){
                    Session::flash('swal', [
                        'title' => 'Mandatory Fields Required!',
                        'message' => 'Disposition of Batch  is yet to be filled!',
                        'type' => 'warning',
                    ]);

                    return redirect()->back();
                }else {
                    Session::flash('swal', [
                        'type' => 'success',
                        'title' => 'Success',
                        'message' => 'Sent for Closed - Done state'
                    ]);
                }

                // $extension = Extension::where('parent_id', $deviation->id)->first();

                // $rca = RootCauseAnalysis::where('record',$deviation->id)->first();

                // if ($extension && $extension->status !== 'Closed-Done') {
                //     Session::flash('swal', [
                //         'title' => 'Extension record pending!',
                //         'message' => 'There is an Extension record which is yet to be closed/done!',
                //         'type' => 'warning',
                //     ]);

                //     return redirect()->back();
                // }

                // if ($rca && $rca->status !== 'Closed-Done') {
                //     Session::flash('swal', [
                //         'title' => 'RCA record pending!',
                //         'message' => 'There is an Root Cause Analysis record which is yet to be closed/done!',
                //         'type' => 'warning',
                //     ]);

                //     return redirect()->back();
                // }

                // return "PAUSE";

                $deviation->stage = "11";
                $deviation->status = "Closed-Done";
                $deviation->QA_Final_Approval_By = Auth::user()->name;
                $deviation->QA_Final_Approval_On = Carbon::now()->format('d-M-Y H:i A');
                $deviation->QA_Final_Approval_Comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'QA Final Approval By, QA Final Approval On';

                if (is_null($lastDocument->QA_Final_Approval_By) || $lastDocument->QA_Final_Approval_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->QA_Final_Approval_By . ' , ' . $lastDocument->QA_Final_Approval_On;

                }
                $history->current = $deviation->QA_Final_Approval_By . ' , ' . $deviation->QA_Final_Approval_On;

                $history->action ='QA Final Approval Complete';
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to = "Closed-Done";
                $history->change_from = $lastDocument->status;
                $history->stage = 'Completed';

                if (is_null($lastDocument->QA_Final_Approval_By) || $lastDocument->QA_Final_Approval_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                $list = Helpers::getInitiatorUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'QA Final Approval Complete', 'process' => 'Deviation', 'comment' => $deviation->QA_Final_Approval_Comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Final Approval Complete Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

                $list = Helpers::getHodUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'QA Final Approval Complete', 'process' => 'Deviation', 'comment' => $deviation->QA_Final_Approval_Comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Final Approval Complete Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

                $list = Helpers::getQAUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'QA Final Approval Complete', 'process' => 'Deviation', 'comment' => $deviation->QA_Final_Approval_Comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Final Approval Complete Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

                $list = Helpers::getCFTUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'QA Final Approval Complete', 'process' => 'Deviation', 'comment' => $deviation->QA_Final_Approval_Comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Final Approval Complete Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

                $list = Helpers::getQaReviewerList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'QA Final Approval Complete', 'process' => 'Deviation', 'comment' => $deviation->QA_Final_Approval_Comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Final Approval Complete Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }
                $deviation->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }


    }
    public function cftnotreqired(Request $request, $id)
    {


        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $deviation = Deviation::find($id);
            $lastDocument = Deviation::find($id);
            $cftDetails = DeviationCftsResponse::withoutTrashed()->where(['status' => 'In-progress', 'deviation_id' => $id])->distinct('cft_user_id')->count();

                $deviation->stage = "5";
                $deviation->status = "QA Secondary Review";
                $deviation->CFT_Review_Not_Required_By = Auth::user()->name;
                $deviation->CFT_Review_Not_Required_On = Carbon::now()->format('d-M-Y H:i A');
                $deviation->CFT_Review_Not_Required_Comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'CFT Review Not Required By, CFT Review Not Required On';

                if (is_null($lastDocument->CFT_Review_Not_Required_By) || $lastDocument->CFT_Review_Not_Required_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->CFT_Review_Not_Required_By . ' , ' . $lastDocument->CFT_Review_Not_Required_On;

                }
                $history->current = $deviation->CFT_Review_Not_Required_By . ' , ' . $deviation->CFT_Review_Not_Required_On;

                $history->action ='CFT Review Not Required';
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to = "QA Secondary Review";
                $history->change_from = $lastDocument->status;
                $history->stage = 'QA Secondary Review';

                if (is_null($lastDocument->CFT_Review_Not_Required_By) || $lastDocument->CFT_Review_Not_Required_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();

                $list = Helpers::getQAHeadUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'CFT Review Not Required', 'process' => 'Deviation', 'comment' => $deviation->CFT_Review_Not_Required_Comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: CFT Review Not Required Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }
                $deviation->update();
                toastr()->success('Document Sent');
                return back();
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function deviationCancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $deviation = Deviation::find($id);
            $lastDocument = Deviation::find($id);

            if( $deviation->stage == 1){
                $deviation->stage = "0";
                $deviation->status = "Closed-Cancelled";
                $deviation->cancelled_by = Auth::user()->name;
                $deviation->cancelled_on = Carbon::now()->format('d-M-Y H:i A');
                $deviation->cancelled_comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'Cancelled By, Cancelled On';

                if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->cancelled_by . ' , ' . $lastDocument->cancelled_on;

                }
                $history->current = $deviation->cancelled_by . ' , ' . $deviation->cancelled_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $deviation->status;
                $history->change_to = "Closed-Cancelled";
                $history->change_from = $lastDocument->status;
                $history->action = 'Cancel';
                $history->stage = 'Closed-Cancelled';

                if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();

                $history = new DeviationHistory();
                $history->type = "Deviation";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $deviation->stage;
                $history->status = $deviation->status;
                $history->save();

                $deviation->update();

                $list = Helpers::getInitiatorUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'Cancel', 'process' => 'Deviation', 'comment' => $deviation->cancelled_comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

                $list = Helpers::getHodUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'Cancel', 'process' => 'Deviation', 'comment' => $deviation->cancelled_comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

                $list = Helpers::getQAUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'Cancel', 'process' => 'Deviation', 'comment' => $deviation->cancelled_comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

                $list = Helpers::getCFTUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'Cancel Performed', 'process' => 'Deviation', 'comment' => $deviation->cancelled_comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

                $list = Helpers::getQAHeadUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'Cancel Performed', 'process' => 'Deviation', 'comment' => $deviation->cancelled_comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }


                $list = Helpers::getQaReviewerList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'Cancel Performed', 'process' => 'Deviation', 'comment' => $deviation->cancelled_comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

                toastr()->success('Document Sent');
                return back();

                        }

                        if($deviation->stage == 2){
                            $deviation->stage = "0";
                            $deviation->status = "Closed-Cancelled";
                            $deviation->Hod_cancelled_by = Auth::user()->name;
                            $deviation->Hod_cancelled_on = Carbon::now()->format('d-M-Y H:i A');
                            $deviation->Hod_cancelled_comments = $request->comment;

                            $history = new DeviationAuditTrail();
                            $history->deviation_id = $id;
                            $history->activity_type = 'Cancelled By, Cancelled On';

                            if (is_null($lastDocument->Hod_cancelled_by) || $lastDocument->Hod_cancelled_by === '') {
                                $history->previous = "";
                            } else {
                                $history->previous = $lastDocument->Hod_cancelled_by . ' , ' . $lastDocument->Hod_cancelled_on;

                            }
                            $history->current = $deviation->Hod_cancelled_by . ' , ' . $deviation->Hod_cancelled_on;

                            $history->comment = $request->comment;
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $history->origin_state = $deviation->status;
                            $history->change_to = "Closed-Cancelled";
                            $history->change_from = $lastDocument->status;
                            $history->action = 'Cancel';
                            $history->stage = 'Closed-Cancelled';

                            if (is_null($lastDocument->Hod_cancelled_by) || $lastDocument->Hod_cancelled_by === '') {
                                $history->action_name = 'New';
                            } else {
                                $history->action_name = 'Update';
                            }

                            $history->save();
                            $deviation->update();

                            $list = Helpers::getInitiatorUserList($deviation->division_id);
                            foreach ($list as $u) {
                                //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                                    $email = Helpers::getInitiatorEmail($u->user_id);
                                    if ($email !== null) {

                                        try {
                                            Mail::send(
                                                'mail.view-mail',
                                                ['data' => $deviation, 'site' => 'DEV', 'history' => 'Cancel', 'process' => 'Deviation', 'comment' => $deviation->Hod_cancelled_comments, 'user'=> Auth::user()->name],
                                                //    function ($message) use ($email) {
                                            //        $message->to($email)
                                            //            ->subject("Document Sent By " . Auth::user()->name);
                                            //    }
                                            //);
                                            function ($message) use ($email, $deviation, $history) {
                                                $message->to($email)
                                                ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
                                            );
                                        } catch (\Exception $e) {
                                            \Log::error('Mail failed to send: ' . $e->getMessage());
                                        }
                                    }
                                //}
                            }

                            $list = Helpers::getHodUserList($deviation->division_id);
                            foreach ($list as $u) {
                                //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                                    $email = Helpers::getInitiatorEmail($u->user_id);
                                    if ($email !== null) {

                                        try {
                                            Mail::send(
                                                'mail.view-mail',
                                                ['data' => $deviation, 'site' => 'DEV', 'history' => 'Cancel', 'process' => 'Deviation', 'comment' => $deviation->Hod_cancelled_comments, 'user'=> Auth::user()->name],
                                            //    function ($message) use ($email) {
                                            //        $message->to($email)
                                            //            ->subject("Document Sent By " . Auth::user()->name);
                                            //    }
                                            //);
                                            function ($message) use ($email, $deviation, $history) {
                                                $message->to($email)
                                                ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
                                            );
                                        } catch (\Exception $e) {
                                            \Log::error('Mail failed to send: ' . $e->getMessage());
                                        }
                                    }
                                //}
                            }

                            $list = Helpers::getQAUserList($deviation->division_id);
                            foreach ($list as $u) {
                                //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                                    $email = Helpers::getInitiatorEmail($u->user_id);
                                    if ($email !== null) {

                                        try {
                                            Mail::send(
                                                'mail.view-mail',
                                                ['data' => $deviation, 'site' => 'DEV', 'history' => 'Cancel', 'process' => 'Deviation', 'comment' => $deviation->Hod_cancelled_comments, 'user'=> Auth::user()->name],
                                            //    function ($message) use ($email) {
                                            //        $message->to($email)
                                            //            ->subject("Document Sent By " . Auth::user()->name);
                                            //    }
                                            //);
                                            function ($message) use ($email, $deviation, $history) {
                                                $message->to($email)
                                                ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
                                            );
                                        } catch (\Exception $e) {
                                            \Log::error('Mail failed to send: ' . $e->getMessage());
                                        }
                                    }
                                //}
                            }

                            $list = Helpers::getCFTUserList($deviation->division_id);
                            foreach ($list as $u) {
                                //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                                    $email = Helpers::getInitiatorEmail($u->user_id);
                                    if ($email !== null) {

                                        try {
                                            Mail::send(
                                                'mail.view-mail',
                                                ['data' => $deviation, 'site' => 'DEV', 'history' => 'Cancel', 'process' => 'Deviation', 'comment' => $deviation->Hod_cancelled_comments, 'user'=> Auth::user()->name],
                                            //    function ($message) use ($email) {
                                            //        $message->to($email)
                                            //            ->subject("Document Sent By " . Auth::user()->name);
                                            //    }
                                            //);
                                            function ($message) use ($email, $deviation, $history) {
                                                $message->to($email)
                                                ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
                                            );
                                        } catch (\Exception $e) {
                                            \Log::error('Mail failed to send: ' . $e->getMessage());
                                        }
                                    }
                                //}
                            }

                            $list = Helpers::getQAHeadUserList($deviation->division_id);
                            foreach ($list as $u) {
                                //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                                    $email = Helpers::getInitiatorEmail($u->user_id);
                                    if ($email !== null) {

                                        try {
                                            Mail::send(
                                                'mail.view-mail',
                                                ['data' => $deviation, 'site' => 'DEV', 'history' => 'Cancel Performed', 'process' => 'Deviation', 'comment' => $deviation->Hod_cancelled_comments, 'user'=> Auth::user()->name],
                                            //    function ($message) use ($email) {
                                            //        $message->to($email)
                                            //            ->subject("Document Sent By " . Auth::user()->name);
                                            //    }
                                            //);
                                            function ($message) use ($email, $deviation, $history) {
                                                $message->to($email)
                                                ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
                                            );
                                        } catch (\Exception $e) {
                                            \Log::error('Mail failed to send: ' . $e->getMessage());
                                        }
                                    }
                                //}
                            }
                            toastr()->success('Document Sent');
                            return back();
                        }
                                } else {
                                    toastr()->error('E-signature Not match');
                                    return back();
                                }
                            }

    public function deviationIsCFTRequired(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $deviation = Deviation::find($id);
            $lastDocument = Deviation::find($id);
            $list = Helpers::getInitiatorUserList();

            $deviation->stage = "5";
            $deviation->status = "QA Secondary Review";
            $deviation->CFT_Review_Complete_By = Auth::user()->name;
            $deviation->CFT_Review_Complete_On = Carbon::now()->format('d-M-Y');

            $history = new DeviationAuditTrail();
            $history->deviation_id = $id;
            $history->activity_type = 'Activity Log';
            $history->previous = "";
            $history->current = $deviation->CFT_Review_Complete_By;
            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->stage = 'Send to HOD';
            //foreach ($list as $u) {
            //    if ($u->q_m_s_divisions_id == $deviation->division_id) {
            //        $email = Helpers::getInitiatorEmail($u->user_id);
            //        if ($email !== null) {

            //            try {
            //                Mail::send(
            //                    'mail.view-mail',
            //                    ['data' => $deviation],
            //                    function ($message) use ($email) {
            //                        $message->to($email)
            //                            ->subject("Activity Performed By " . Auth::user()->name);
            //                    }
            //                );
            //            } catch (\Exception $e) {
            //                //log error
            //            }
            //        }
            //    }
            //}
            $history->save();
            $deviation->update();
            $history = new DeviationHistory();
            $history->type = "Deviation";
            $history->doc_id = $id;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->stage_id = $deviation->stage;
            $history->status = $deviation->status;
            $history->save();

            toastr()->success('Document Sent');
            return back();
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function cftReview(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $deviation = Deviation::find($id);
            $lastDocument = Deviation::find($id);
            $list = Helpers::getInitiatorUserList();
            $deviation->stage = "2";
            $deviation->status = "HOD Review";
            $deviation->qa_more_info_required_by = Auth::user()->name;
            $deviation->qa_more_info_required_on = Carbon::now()->format('d-M-Y');
            $history = new DeviationAuditTrail();
            $history->deviation_id = $id;
            $history->activity_type = 'Activity Log';
            $history->previous = "";
            $history->current = $deviation->qa_more_info_required_by;
            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->stage = 'Send to HOD';
            //foreach ($list as $u) {
            //    if ($u->q_m_s_divisions_id == $deviation->division_id) {
            //        $email = Helpers::getInitiatorEmail($u->user_id);
            //        if ($email !== null) {

            //            try {
            //                Mail::send(
            //                    'mail.view-mail',
            //                    ['data' => $deviation],
            //                    function ($message) use ($email) {
            //                        $message->to($email)
            //                            ->subject("Activity Performed By " . Auth::user()->name);
            //                    }
            //                );
            //            } catch (\Exception $e) {
            //                //log error
            //            }
            //        }
            //    }
            //}
            $history->save();
            $deviation->update();
            $history = new DeviationHistory();
            $history->type = "Deviation";
            $history->doc_id = $id;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->stage_id = $deviation->stage;
            $history->status = "Send to HOD";
            $history->save();

            toastr()->success('Document Sent');
            return back();
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }
    public function sendToQA(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $deviation = Deviation::find($id);
            $lastDocument = Deviation::find($id);
            $list = Helpers::getInitiatorUserList();
            $cftResponse = DeviationCftsResponse::withoutTrashed()->where(['deviation_id' => $id])->get();

            // Soft delete all records
            $cftResponse->each(function ($response) {
                $response->delete();
            });

            $deviation->stage = "3";
            $deviation->status = "QA Initial Review";
            $deviation->qa_more_info_required_by = Auth::user()->name;
            $deviation->qa_more_info_required_on = Carbon::now()->format('d-M-Y');
            $history = new DeviationAuditTrail();
            $history->deviation_id = $id;
            $history->activity_type = 'Activity Log';
            $history->previous = "";
            $history->current = $deviation->qa_more_info_required_by;
            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            //foreach ($list as $u) {
            //    if ($u->q_m_s_divisions_id == $deviation->division_id) {
            //        $email = Helpers::getInitiatorEmail($u->user_id);
            //        if ($email !== null) {

            //            try {
            //                Mail::send(
            //                    'mail.view-mail',
            //                    ['data' => $deviation],
            //                    function ($message) use ($email) {
            //                        $message->to($email)
            //                            ->subject("Activity Performed By " . Auth::user()->name);
            //                    }
            //                );
            //            } catch (\Exception $e) {
            //                //log error
            //            }
            //        }
            //    }
            //}
            $history->save();
            $deviation->update();
            $history = new DeviationHistory();
            $history->type = "Deviation";
            $history->doc_id = $id;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->stage_id = $deviation->stage;
            $history->status = "Send to QA Initial Review";
            $history->save();

            toastr()->success('Document Sent');
            return back();
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


    public function deviation_qa_more_info(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $deviation = Deviation::find($id);
            $lastDocument = Deviation::find($id);

            if ($deviation->stage == 2) {
                $deviation->stage = "1";
                $deviation->status = "Opened";
                $deviation->more_info_required_by = Auth::user()->name;
                $deviation->more_info_required_on = Carbon::now()->format('d-M-Y');
                $deviation->more_info_required_comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $deviation->hod_more_info_required_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'HOD Review';
                $history->save();
                $deviation->update();

                $history = new DeviationHistory();
                $history->type = "Deviation";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $deviation->stage;
                $history->status = $deviation->status;
                $history->save();
                //$list = Helpers::getHodUserList();
                //foreach ($list as $u) {
                //    if ($u->q_m_s_divisions_id == $deviation->division_id) {
                //        $email = Helpers::getInitiatorEmail($u->user_id);
                //        if ($email !== null) {
                //            try {
                //                Mail::send(
                //                    'mail.view-mail',
                //                    ['data' => $deviation],
                //                    function ($message) use ($email) {
                //                        $message->to($email)
                //                            ->subject("Activity Performed By " . Auth::user()->name);
                //                    }
                //                );
                //            } catch (\Exception $e) {
                //                //log error
                //            }
                //        }
                //    }
                //}
                toastr()->success('Document Sent');
                return back();
            }
            if ($deviation->stage == 3) {
                $deviation->stage = "2";
                $deviation->status = "HOD Review";
                $deviation->qa_more_info_required_by = Auth::user()->name;
                $deviation->qa_more_info_required_on = Carbon::now()->format('d-M-Y');
                $deviation->qa_more_info_required_comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->action='More Information Required';
                $history->current = $deviation->qa_more_info_required_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'More Info Required';
                $history->save();
                $deviation->update();
                $history = new DeviationHistory();
                $history->type = "Deviation";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $deviation->stage;
                $history->status = $deviation->status;
                $history->save();
                $list = Helpers::getHodUserList();
                //foreach ($list as $u) {
                //    if ($u->q_m_s_divisions_id == $deviation->division_id) {
                //        $email = Helpers::getInitiatorEmail($u->user_id);
                //        if ($email !== null) {
                //            try {
                //                Mail::send(
                //                    'mail.view-mail',
                //                    ['data' => $deviation],
                //                    function ($message) use ($email) {
                //                        $message->to($email)
                //                            ->subject("Activity Performed By " . Auth::user()->name);
                //                    }
                //                );
                //            } catch (\Exception $e) {
                //                //log error
                //            }
                //        }
                //    }
                //}
                toastr()->success('Document Sent');
                return back();
            }

            if ($deviation->stage == 4) {
                $deviation->stage = "3";
                $deviation->status = "QA Initial Review";
                $deviation->cft_more_info_required_by = Auth::user()->name;
                $deviation->cft_more_info_required_on = Carbon::now()->format('d-M-Y');
                $deviation->cft_more_info_required_comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->action='More Information Required';
                $history->current = $deviation->cft_more_info_required_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'More Info Required';
                $history->save();

                $history = new DeviationHistory();
                $history->type = "Deviation";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $deviation->stage;
                $history->status = $deviation->status;
                $history->save();

                $deviation->update();

                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


    public function pending_initiator_update(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $deviation = Deviation::find($id);
            $lastDocument = Deviation::find($id);
            // $cftResponse = DeviationCftsResponse::withoutTrashed()->where(['deviation_id' => $id])->get();
            $list = Helpers::getInitiatorUserList();
           // Soft delete all records
        //    $cftResponse->each(function ($response) {
        //     $response->delete();
        // });


       if($deviation->stage == 8){
        $deviation->stage = "7";
        $deviation->status = "Pending Initiator Update";
        $deviation->HOD_Final_Send_to_Initiator_By = Auth::user()->name;
        $deviation->HOD_Final_Send_to_Initiator_On = Carbon::now()->format('d-M-Y H:i A');
        $deviation->HOD_Final_Send_to_Initiator_Comments = $request->comment;

        $history = new DeviationAuditTrail();
        $history->deviation_id = $id;
        $history->activity_type = 'Hod Review Send to Pending Initiator Updated By, Hod Review Send to Pending Initiator Updated By';

        if (is_null($lastDocument->HOD_Final_Send_to_Initiator_By) || $lastDocument->HOD_Final_Send_to_Initiator_By === '') {
            $history->previous = "";
        } else {
            $history->previous = $lastDocument->HOD_Final_Send_to_Initiator_By . ' , ' . $lastDocument->HOD_Final_Send_to_Initiator_On;

        }
        $history->current = $deviation->HOD_Final_Send_to_Initiator_By . ' , ' . $deviation->HOD_Final_Send_to_Initiator_On;

        $history->comment = $request->comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->stage = 'Pending Initiator Update';
        // $history->stage = 'Send to Pending Initiator Update';
        $history->change_to =   "Pending Initiator Update";
        $history->change_from = $lastDocument->status;
        $history->action = 'Send to Pending Initiator Update';

        if (is_null($lastDocument->HOD_Final_Send_to_Initiator_By) || $lastDocument->HOD_Final_Send_to_Initiator_By === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }

        $history->save();
        $deviation->update();
        $history = new DeviationHistory();
        $history->type = "Deviation";
        $history->doc_id = $id;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->stage_id = $deviation->stage;
        $history->status = "Send to Pending Initiator Update";
        $history->save();

        $list = Helpers::getInitiatorUserList($deviation->division_id);
        foreach ($list as $u) {
            //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                $email = Helpers::getInitiatorEmail($u->user_id);
                if ($email !== null) {

                    try {
                        Mail::send(
                            'mail.view-mail',
                            ['data' => $deviation, 'site' => 'DEV', 'history' => 'Send to Pending Initiator Update', 'process' => 'Deviation', 'comment' => $deviation->HOD_Final_Send_to_Initiator_Comments, 'user'=> Auth::user()->name],
                            //    function ($message) use ($email) {
                        //        $message->to($email)
                        //            ->subject("Document Sent By " . Auth::user()->name);
                        //    }
                        //);
                        function ($message) use ($email, $deviation, $history) {
                            $message->to($email)
                            ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity:  Send to Pending Initiator Update Performed"); }
                        );
                    } catch (\Exception $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
            //}
        }

        $deviation->update();
        toastr()->success('Document Sent');
        return back();

    }
        if($deviation->stage == 9){
            $deviation->stage = "7";
            $deviation->status = "Pending Initiator Update";
            $deviation->Send_to_QA_Initiator_By = Auth::user()->name;
            $deviation->Send_to_QA_Initiator_On = Carbon::now()->format('d-M-Y H:i A');
            $deviation->Send_to_QA_Initiator_Comments = $request->comment;

            $history = new DeviationAuditTrail();
            $history->deviation_id = $id;
            $history->activity_type = 'Send to Pending Initiator Updated By, Send to Pending Initiator Updated On';

            if (is_null($lastDocument->Send_to_QA_Initiator_By) || $lastDocument->Send_to_QA_Initiator_By === '') {
                $history->previous = "";
            } else {
                $history->previous = $lastDocument->Send_to_QA_Initiator_By . ' , ' . $lastDocument->Send_to_QA_Initiator_On;

            }
            $history->current = $deviation->Send_to_QA_Initiator_By . ' , ' . $deviation->Send_to_QA_Initiator_On;

            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->stage = 'Pending Initiator Update';
            $history->change_to =   "Pending Initiator Update";
            $history->change_from = $lastDocument->status;
            $history->action = 'Send to Pending Initiator Update';

            if (is_null($lastDocument->Send_to_QA_Initiator_By) || $lastDocument->Send_to_QA_Initiator_By === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }

            $history->save();

            $list = Helpers::getQAUserList($deviation->division_id);
            foreach ($list as $u) {
                //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                    $email = Helpers::getInitiatorEmail($u->user_id);
                    if ($email !== null) {

                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $deviation, 'site' => 'DEV', 'history' => 'Send to QA Initiator Update', 'process' => 'Deviation', 'comment' => $deviation->Send_to_QA_Initiator_Comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                            //        $message->to($email)
                            //            ->subject("Document Sent By " . Auth::user()->name);
                            //    }
                            //);
                            function ($message) use ($email, $deviation, $history) {
                                $message->to($email)
                                ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to QA Initiator Update Performed"); }
                            );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                //}
            }

            $deviation->update();
            toastr()->success('Document Sent');
            return back();
        }

        if($deviation->stage == 10){
            $deviation->stage = "7";
            $deviation->status = "Pending Initiator Update";
            $deviation->Send_to_Pending_Initiator_Updated_By = Auth::user()->name;
            $deviation->Send_to_Pending_Initiator_Updated_On = Carbon::now()->format('d-M-Y H:i A');
            $deviation->Send_to_Pending_Initiator_Updated_Comments = $request->comment;

            $history = new DeviationAuditTrail();
            $history->deviation_id = $id;
            $history->activity_type = 'QA Approval Send to Pending Initiator Updated By, QA Approval Send to Pending Initiator Updated On';

            if (is_null($lastDocument->Send_to_Pending_Initiator_Updated_By) || $lastDocument->Send_to_Pending_Initiator_Updated_By === '') {
                $history->previous = "";
            } else {
                $history->previous = $lastDocument->Send_to_Pending_Initiator_Updated_By . ' , ' . $lastDocument->Send_to_Pending_Initiator_Updated_On;

            }
            $history->current = $deviation->Send_to_Pending_Initiator_Updated_By . ' , ' . $deviation->Send_to_Pending_Initiator_Updated_On;

            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->stage = 'Pending Initiator Update';
            $history->change_to =   "Pending Initiator Update";
            $history->change_from = $lastDocument->status;
            $history->action = 'Send to Pending Initiator Update';

            if (is_null($lastDocument->Send_to_Pending_Initiator_Updated_By) || $lastDocument->Send_to_Pending_Initiator_Updated_By === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }

            $history->save();

            $list = Helpers::getInitiatorUserList($deviation->division_id);
            foreach ($list as $u) {
                //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                    $email = Helpers::getInitiatorEmail($u->user_id);
                    if ($email !== null) {

                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $deviation, 'site' => 'DEV', 'history' => 'Send to Pending Initiator Update', 'process' => 'Deviation', 'comment' => $deviation->Send_to_Pending_Initiator_Updated_Comments, 'user'=> Auth::user()->name],
                            //    function ($message) use ($email) {
                            //        $message->to($email)
                            //            ->subject("Document Sent By " . Auth::user()->name);
                            //    }
                            //);
                            function ($message) use ($email, $deviation, $history) {
                                $message->to($email)
                                ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to Pending Initiator Update Performed"); }
                            );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                //}
            }

            $deviation->update();
            toastr()->success('Document Sent');
            return back();
        }


        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


    public function check(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $deviation = Deviation::find($id);
            $lastDocument = Deviation::find($id);
            $cftResponse = DeviationCftsResponse::withoutTrashed()->where(['deviation_id' => $id])->get();
            $list = Helpers::getInitiatorUserList();
           // Soft delete all records
           $cftResponse->each(function ($response) {
            $response->delete();
        });

// dd($deviation->stage);

    if ($deviation->stage == 5) {
        $deviation->stage = "1";
        $deviation->status = "Opened";
        $deviation->send_to_opened_by = Auth::user()->name;
        $deviation->send_to_opened_on = Carbon::now()->format('d-M-Y H:i A');
        $deviation->send_to_opened_comments = $request->comment;

        $history = new DeviationAuditTrail();
        $history->deviation_id = $id;
        $history->activity_type = 'QA Secondary Send to Opened By, QA Secondary Send to Opened On';

        if (is_null($lastDocument->send_to_opened_by) || $lastDocument->send_to_opened_by === '') {
            $history->previous = "";
        } else {
            $history->previous = $lastDocument->send_to_opened_by . ' , ' . $lastDocument->send_to_opened_on;

        }
        $history->current = $deviation->send_to_opened_by . ' , ' . $deviation->send_to_opened_on;

        $history->comment = $request->comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        // $history->stage = 'Send to Initiator';
        $history->change_to =   "Opened";
        $history->change_from = $lastDocument->status;
        $history->action = 'Send to Opened';

        if (is_null($lastDocument->send_to_opened_by) || $lastDocument->send_to_opened_by === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }

        $history->save();
        //$deviation->update();
        $history = new DeviationHistory();
        $history->type = "Deviation";
        $history->doc_id = $id;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->stage_id = $deviation->stage;
        $history->status = "Send to Opened";
        $history->save();

        $list = Helpers::getInitiatorUserList($deviation->division_id);
        foreach ($list as $u) {
            //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                $email = Helpers::getInitiatorEmail($u->user_id);
                if ($email !== null) {
                    try {
                        Mail::send(
                            'mail.view-mail',
                            ['data' => $deviation, 'site' => 'DEV', 'history' => 'Send to Opened', 'process' => 'Deviation', 'comment' => $deviation->send_to_opened_comments, 'user'=> Auth::user()->name],
                            //    function ($message) use ($email) {
                        //        $message->to($email)
                        //            ->subject("Document Sent By " . Auth::user()->name);
                        //    }
                        //);
                        function ($message) use ($email, $deviation, $history) {
                            $message->to($email)
                            ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to Opened Performed"); }
                        );
                    } catch (\Exception $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
            //}
        }

        $deviation->update();
        toastr()->success('Document Sent');
        return back();

    }

    if($deviation->stage == 7){

        $deviation->stage = "1";
        $deviation->status = "Opened";
        $deviation->Send_to_initialStage_By = Auth::user()->name;
        $deviation->Send_to_initialStage_On = Carbon::now()->format('d-M-Y H:i A');
        $deviation->Send_to_initialStage_Comments = $request->comment;

        $history = new DeviationAuditTrail();
        $history->deviation_id = $id;
        $history->activity_type = 'Initiator Send to opened By, Initiator Send to opened On';

        if (is_null($lastDocument->Send_to_initialStage_By) || $lastDocument->Send_to_initialStage_By === '') {
            $history->previous = "";
        } else {
            $history->previous = $lastDocument->Send_to_initialStage_By . ' , ' . $lastDocument->Send_to_initialStage_On;

        }
        $history->current = $deviation->Send_to_initialStage_By . ' , ' . $deviation->Send_to_initialStage_On;

        $history->comment = $request->comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->stage = 'Opened';
        $history->change_to =   "Opened";
        $history->change_from = $lastDocument->status;
        $history->action = 'Send to Opened';

        if (is_null($lastDocument->Send_to_initialStage_By) || $lastDocument->Send_to_initialStage_By === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }

        $history->save();

        $list = Helpers::getInitiatorUserList($deviation->division_id);
        foreach ($list as $u) {
            //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                $email = Helpers::getInitiatorEmail($u->user_id);
                if ($email !== null) {
                    try {
                        Mail::send(
                            'mail.view-mail',
                            ['data' => $deviation, 'site' => 'DEV', 'history' => 'Send to Opened', 'process' => 'Deviation', 'comment' => $deviation->Send_to_initialStage_Comments, 'user'=> Auth::user()->name],
                        //    function ($message) use ($email) {
                        //        $message->to($email)
                        //        ->subject("Document Sent By " . Auth::user()->name);
                        //    }
                        //);
                        function ($message) use ($email, $deviation, $history) {
                            $message->to($email)
                            ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to opened Performed"); }
                        );
                    } catch (\Exception $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                //}
            }

        $deviation->update();
        toastr()->success('Document Sent');
        return back();
    }


    if($deviation->stage == 8){

        $deviation->stage = "1";
        $deviation->status = "Opened";
        $deviation->HOD_Final_Send_to_Opened_By = Auth::user()->name;
        $deviation->HOD_Final_Send_to_Opened_On = Carbon::now()->format('d-M-Y H:i A');
        $deviation->HOD_Final_Send_to_Opened_Comments = $request->comment;

        $history = new DeviationAuditTrail();
        $history->deviation_id = $id;
        $history->activity_type = 'Hod Send to Opened By, Hod Send to Opened On';

        if (is_null($lastDocument->HOD_Final_Send_to_Opened_By) || $lastDocument->HOD_Final_Send_to_Opened_By === '') {
            $history->previous = "";
        } else {
            $history->previous = $lastDocument->HOD_Final_Send_to_Opened_By . ' , ' . $lastDocument->HOD_Final_Send_to_Opened_On;

        }
        $history->current = $deviation->HOD_Final_Send_to_Opened_By . ' , ' . $deviation->HOD_Final_Send_to_Opened_On;

        $history->comment = $request->comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->stage = 'Opened';
        $history->change_to =   "Opened";
        $history->change_from = $lastDocument->status;
        $history->action = 'Send to Opened';

        if (is_null($lastDocument->HOD_Final_Send_to_Opened_By) || $lastDocument->HOD_Final_Send_to_Opened_By === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }

        $history->save();


        $list = Helpers::getInitiatorUserList($deviation->division_id);
        foreach ($list as $u) {
            //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                $email = Helpers::getInitiatorEmail($u->user_id);
                if ($email !== null) {
                    try {
                        Mail::send(
                            'mail.view-mail',
                            ['data' => $deviation, 'site' => 'DEV', 'history' => 'Send to Opened', 'process' => 'Deviation', 'comment' => $deviation->HOD_Final_Send_to_Opened_Comments, 'user'=> Auth::user()->name],
                        //    function ($message) use ($email) {
                        //        $message->to($email)
                        //            ->subject("Document Sent By " . Auth::user()->name);
                        //    }
                        //);
                        function ($message) use ($email, $deviation, $history) {
                            $message->to($email)
                            ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to opened Performed"); }
                        );
                    } catch (\Exception $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
            //}
        }

        $deviation->update();
        toastr()->success('Document Sent');
        return back();
    }


    if($deviation->stage == 8){

        $deviation->stage = "1";
        $deviation->status = "Opened";
        $deviation->qa_more_info_required_by = Auth::user()->name;
        $deviation->qa_more_info_required_on = Carbon::now()->format('d-M-Y');

        $history = new DeviationAuditTrail();
        $history->deviation_id = $id;
        $history->activity_type = 'Activity Log';
        $history->previous = "";
        $history->current = $deviation->qa_more_info_required_by;
        $history->comment = $request->comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->stage = 'Opened';
        $history->change_to =   "Opened";
        $history->change_from = $lastDocument->status;
        $history->action = 'Send to Opened';
        $history->save();
        $deviation->update();

        toastr()->success('Document Sent');
        return back();
    }


    if($deviation->stage == 9){

        $deviation->stage = "1";
        $deviation->status = "Opened";
        $deviation->QA_Final_Send_to_Opened_By = Auth::user()->name;
        $deviation->QA_Final_Send_to_Opened_On = Carbon::now()->format('d-M-Y H:i A');
        $deviation->QA_Final_Send_to_Opened_Comments = $request->comment;

        $history = new DeviationAuditTrail();
        $history->deviation_id = $id;
        $history->activity_type = 'QA Review Send to Opened By, QA Review Send to Opened On';

        if (is_null($lastDocument->QA_Final_Send_to_Opened_By) || $lastDocument->QA_Final_Send_to_Opened_By === '') {
            $history->previous = "";
        } else {
            $history->previous = $lastDocument->QA_Final_Send_to_Opened_By . ' , ' . $lastDocument->QA_Final_Send_to_Opened_On;

        }
        $history->current = $deviation->QA_Final_Send_to_Opened_By . ' , ' . $deviation->QA_Final_Send_to_Opened_On;

        $history->comment = $request->comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->stage = 'Opened';
        $history->change_to =   "Opened";
        $history->change_from = $lastDocument->status;
        $history->action = 'Send to Opened';

        if (is_null($lastDocument->QA_Final_Send_to_Opened_By) || $lastDocument->QA_Final_Send_to_Opened_By === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }

        $history->save();


        $list = Helpers::getInitiatorUserList($deviation->division_id);
        foreach ($list as $u) {
            //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                $email = Helpers::getInitiatorEmail($u->user_id);
                if ($email !== null) {
                    try {
                        Mail::send(
                            'mail.view-mail',
                            ['data' => $deviation, 'site' => 'DEV', 'history' => 'Send to Opened', 'process' => 'Deviation', 'comment' => $deviation->QA_Final_Send_to_Opened_Comments, 'user'=> Auth::user()->name],
                        //    function ($message) use ($email) {
                        //        $message->to($email)
                        //            ->subject("Document Sent By " . Auth::user()->name);
                        //    }
                        //);
                        function ($message) use ($email, $deviation, $history) {
                            $message->to($email)
                            ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to opened Performed"); }
                        );
                    } catch (\Exception $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
            //}
        }

        $deviation->update();
        toastr()->success('Document Sent');
        return back();
    }


    if($deviation->stage == 10){

        $deviation->stage = "1";
        $deviation->status = "Opened";
        $deviation->QA_Approval_Send_to_Opened_By = Auth::user()->name;
        $deviation->QA_Approval_Send_to_Opened_On = Carbon::now()->format('d-M-Y H:i A');
        $deviation->QA_Approval_Send_to_Opened_Comments = $request->comment;

        $history = new DeviationAuditTrail();
        $history->deviation_id = $id;
        $history->activity_type = 'QA Approval Send to Opened By, QA Approval Send to Opened By';

        if (is_null($lastDocument->QA_Approval_Send_to_Opened_By) || $lastDocument->QA_Approval_Send_to_Opened_By === '') {
            $history->previous = "";
        } else {
            $history->previous = $lastDocument->QA_Approval_Send_to_Opened_By . ' , ' . $lastDocument->QA_Approval_Send_to_Opened_On;

        }
        $history->current = $deviation->QA_Approval_Send_to_Opened_By . ' , ' . $deviation->QA_Approval_Send_to_Opened_On;

        $history->comment = $request->comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->stage = 'Opened';
        $history->change_to =   "Opened";
        $history->change_from = $lastDocument->status;
        $history->action = 'Send to Opened';

        if (is_null($lastDocument->QA_Approval_Send_to_Opened_By) || $lastDocument->QA_Approval_Send_to_Opened_By === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }

        $history->save();


        $list = Helpers::getInitiatorUserList($deviation->division_id);
        foreach ($list as $u) {
            //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                $email = Helpers::getInitiatorEmail($u->user_id);
                if ($email !== null) {
                    try {
                        Mail::send(
                            'mail.view-mail',
                            ['data' => $deviation, 'site' => 'DEV', 'history' => 'Send to Opened', 'process' => 'Deviation', 'comment' => $deviation->QA_Approval_Send_to_Opened_Comments, 'user'=> Auth::user()->name],
                        //    function ($message) use ($email) {
                        //        $message->to($email)
                        //            ->subject("Document Sent By " . Auth::user()->name);
                        //    }
                        //);
                        function ($message) use ($email, $deviation, $history) {
                            $message->to($email)
                            ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to opened Performed"); }
                        );
                    } catch (\Exception $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
            //}
        }

        $deviation->update();
        toastr()->success('Document Sent');
        return back();
    }

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function check2(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $deviation = Deviation::find($id);
            $lastDocument = Deviation::find($id);
            $cftResponse = DeviationCftsResponse::withoutTrashed()->where(['deviation_id' => $id])->get();
            $list = Helpers::getInitiatorUserList();

        // Soft delete all records
        $cftResponse->each(function ($response) {
            $response->delete();
        });
       if($deviation->stage == 5){
        $deviation->stage = "2";
        $deviation->status = "HOD Review";
        $deviation->QA_Secondary_Send_to_Hod_By = Auth::user()->name;
        $deviation->QA_Secondary_Send_to_Hod_On = Carbon::now()->format('d-M-Y H:i A');
        $deviation->QA_Secondary_Send_to_Hod_Comments = $request->comment;

        $history = new DeviationAuditTrail();
        $history->deviation_id = $id;
        $history->activity_type = 'QA Secondary Send to HOD By, QA Secondary Send to HOd On';

        if (is_null($lastDocument->QA_Secondary_Send_to_Hod_By) || $lastDocument->QA_Secondary_Send_to_Hod_By === '') {
            $history->previous = "";
        } else {
            $history->previous = $lastDocument->QA_Secondary_Send_to_Hod_By . ' , ' . $lastDocument->QA_Secondary_Send_to_Hod_On;

        }
        $history->current = $deviation->QA_Secondary_Send_to_Hod_By . ' , ' . $deviation->QA_Secondary_Send_to_Hod_On;

        $history->comment = $request->comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =   "HOD Review";
        $history->change_from = $lastDocument->status;
        $history->action = 'Send to HOD';
        // $history->stage = 'Send to HOD';

        if (is_null($lastDocument->QA_Secondary_Send_to_Hod_By) || $lastDocument->QA_Secondary_Send_to_Hod_By === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }

        $history->save();
        $deviation->update();
        $history = new DeviationHistory();
        $history->type = "Deviation";
        $history->doc_id = $id;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->stage_id = $deviation->stage;
        $history->status = "Send to HOD Review";
        $history->save();

        $list = Helpers::getHodUserList($deviation->division_id);
        foreach ($list as $u) {
            //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                $email = Helpers::getInitiatorEmail($u->user_id);
                if ($email !== null) {
                    try {
                        Mail::send(
                            'mail.view-mail',
                            ['data' => $deviation, "site" => "DEV", 'history' => 'Send to HOD', 'process' => 'Deviation', 'comment' => $deviation->QA_Secondary_Send_to_Hod_Comments, 'user'=> Auth::user()->name],
                        //    function ($message) use ($email) {
                        //        $message->to($email)
                        //            ->subject("Document Sent By " . Auth::user()->name);
                        //    }
                        //);
                        function ($message) use ($email, $deviation, $history) {
                            $message->to($email)
                            ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to HOD Performed"); }
                        );
                    } catch (\Exception $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
            //}
        }

        $deviation->update();
        toastr()->success('Document Sent');
        return back();

       }


       if($deviation->stage == 7){
        $deviation->stage = "2";
        $deviation->status = "HOD Review";
        $deviation->Send_to_Hod_By = Auth::user()->name;
        $deviation->Send_to_Hod_On = Carbon::now()->format('d-M-Y H:i A');
        $deviation->Send_to_Hod_Comments = $request->comment;

        $history = new DeviationAuditTrail();
        $history->deviation_id = $id;
        $history->activity_type = 'Initiator Send to HOD Review By, Initiator Send to HOD Review By';

        if (is_null($lastDocument->Send_to_Hod_By) || $lastDocument->Send_to_Hod_By === '') {
            $history->previous = "";
        } else {
            $history->previous = $lastDocument->Send_to_Hod_By . ' , ' . $lastDocument->Send_to_Hod_On;

        }
        $history->current = $deviation->Send_to_Hod_By . ' , ' . $deviation->Send_to_Hod_On;

        $history->comment = $request->comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =   "HOD Review";
        $history->change_from = $lastDocument->status;
        $history->action = 'Send to HOD Review';
        // $history->stage = 'Send to HOD';

        if (is_null($lastDocument->Send_to_Hod_By) || $lastDocument->Send_to_Hod_By === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }

        $history->save();

        $list = Helpers::getHodUserList($deviation->division_id);
        foreach ($list as $u) {
            //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                $email = Helpers::getInitiatorEmail($u->user_id);
                if ($email !== null) {
                    try {
                        Mail::send(
                            'mail.view-mail',
                            ['data' => $deviation, "site" => "DEV", 'history' => 'Send to HOD Review', 'process' => 'Deviation', 'comment' => $deviation->Send_to_Hod_Comments, 'user'=> Auth::user()->name],
                        //    function ($message) use ($email) {
                        //        $message->to($email)
                        //            ->subject("Document Sent By " . Auth::user()->name);
                        //    }
                        //);
                        function ($message) use ($email, $deviation, $history) {
                            $message->to($email)
                            ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to HOD Review Performed"); }
                        );
                    } catch (\Exception $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
            //}
        }

        $deviation->update();
        toastr()->success('Document Sent');
        return back();
       }

       if($deviation->stage == 9){
        $deviation->stage = "2";
        $deviation->status = "HOD Review";
        $deviation->QA_Final_Send_to_HOD_By = Auth::user()->name;
        $deviation->QA_Final_Send_to_HOD_On = Carbon::now()->format('d-M-Y H:i A');
        $deviation->QA_Final_Send_to_HOD_Comments = $request->comment;

        $history = new DeviationAuditTrail();
        $history->deviation_id = $id;
        $history->activity_type = 'QA Review Send to HOD By, QA Review Send to HOD On';

        if (is_null($lastDocument->QA_Final_Send_to_HOD_By) || $lastDocument->QA_Final_Send_to_HOD_By === '') {
            $history->previous = "";
        } else {
            $history->previous = $lastDocument->QA_Final_Send_to_HOD_By . ' , ' . $lastDocument->QA_Final_Send_to_HOD_On;

        }
        $history->current = $deviation->QA_Final_Send_to_HOD_By . ' , ' . $deviation->QA_Final_Send_to_HOD_On;


        $history->comment = $request->comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =   "HOD Review";
        $history->change_from = $lastDocument->status;
        $history->action = 'Send to HOD Review';
        // $history->stage = 'Send to HOD';

        if (is_null($lastDocument->QA_Final_Send_to_HOD_By) || $lastDocument->QA_Final_Send_to_HOD_By === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }

        $history->save();


        $list = Helpers::getHodUserList($deviation->division_id);
        foreach ($list as $u) {
            //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                $email = Helpers::getInitiatorEmail($u->user_id);
                if ($email !== null) {
                    try {
                        Mail::send(
                            'mail.view-mail',
                            ['data' => $deviation, "site" => "DEV", 'history' => 'Send to HOD', 'process' => 'Deviation', 'comment' => $deviation->QA_Final_Send_to_HOD_Comments, 'user'=> Auth::user()->name],
                        //    function ($message) use ($email) {
                        //        $message->to($email)
                        //            ->subject("Document Sent By " . Auth::user()->name);
                        //    }
                        //);
                        function ($message) use ($email, $deviation, $history) {
                            $message->to($email)
                            ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to HOD Performed"); }
                        );
                    } catch (\Exception $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
            //}
        }

        $deviation->update();
        toastr()->success('Document Sent');
        return back();
       }

       if($deviation->stage == 10){
        $deviation->stage = "2";
        $deviation->status = "HOD Review";
        $deviation->QA_Approval_Send_to_HOD_By = Auth::user()->name;
        $deviation->QA_Approval_Send_to_HOD_On = Carbon::now()->format('d-M-Y H:i A');
        $deviation->QA_Approval_Send_to_HOD_Comments = $request->comment;

        $history = new DeviationAuditTrail();
        $history->deviation_id = $id;
        $history->activity_type = 'QA Approval Send to HOD By, QA Approval Send to HOD On';

        if (is_null($lastDocument->QA_Approval_Send_to_HOD_By) || $lastDocument->QA_Approval_Send_to_HOD_By === '') {
            $history->previous = "";
        } else {
            $history->previous = $lastDocument->QA_Approval_Send_to_HOD_By . ' , ' . $lastDocument->QA_Approval_Send_to_HOD_On;

        }
        $history->current = $deviation->QA_Approval_Send_to_HOD_By . ' , ' . $deviation->QA_Approval_Send_to_HOD_On;

        $history->comment = $request->comment;
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $lastDocument->status;
        $history->change_to =   "HOD Review";
        $history->change_from = $lastDocument->status;
        $history->action = 'Send to HOD Review';
        // $history->stage = 'Send to HOD';

        if (is_null($lastDocument->QA_Approval_Send_to_HOD_By) || $lastDocument->QA_Approval_Send_to_HOD_By === '') {
            $history->action_name = 'New';
        } else {
            $history->action_name = 'Update';
        }

        $history->save();


        $list = Helpers::getHodUserList($deviation->division_id);
        foreach ($list as $u) {
            //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                $email = Helpers::getInitiatorEmail($u->user_id);
                if ($email !== null) {
                    try {
                        Mail::send(
                            'mail.view-mail',
                            ['data' => $deviation, 'history' => 'HOD', "site" => "DEV", 'process' => 'Deviation', 'comment' => $deviation->QA_Approval_Send_to_HOD_Comments, 'user'=> Auth::user()->name],
                        //    function ($message) use ($email) {
                        //        $message->to($email)
                        //            ->subject("Document Sent By " . Auth::user()->name);
                        //    }
                        //);
                        function ($message) use ($email, $deviation, $history) {
                            $message->to($email)
                            ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to HOD Performed"); }
                        );
                    } catch (\Exception $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
            //}
        }

        $deviation->update();
        toastr()->success('Document Sent');
        return back();
       }

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function check3(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $deviation = Deviation::find($id);
            $lastDocument = Deviation::find($id);
            $cftResponse = DeviationCftsResponse::withoutTrashed()->where(['deviation_id' => $id])->get();
            $list = Helpers::getInitiatorUserList();

        // Soft delete all records
        $cftResponse->each(function ($response) {
            $response->delete();
        });

        if($deviation->stage == 5){
            $deviation->stage = "3";
            $deviation->status = "QA Initial Review";
            $deviation->Send_to_QA_Initial_Review_By = Auth::user()->name;
            $deviation->Send_to_QA_Initial_Review_On = Carbon::now()->format('d-M-Y H:i A');
            $deviation->Send_to_QA_Initial_Review_Comments = $request->comment;

            $history = new DeviationAuditTrail();
            $history->deviation_id = $id;
            $history->activity_type = 'QA Secondary Send to QA Initial Review By, QA Secondary Send to QA Initial Review By';

            if (is_null($lastDocument->Send_to_QA_Initial_Review_By) || $lastDocument->Send_to_QA_Initial_Review_By === '') {
                $history->previous = "";
            } else {
                $history->previous = $lastDocument->Send_to_QA_Initial_Review_By . ' , ' . $lastDocument->Send_to_QA_Initial_Review_On;

            }
            $history->current = $deviation->Send_to_QA_Initial_Review_By . ' , ' . $deviation->Send_to_QA_Initial_Review_On;

            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            // $history->stage = 'Send to HOD';
            $history->stage = 'QA Initial Review';
            $history->change_to =   "QA Initial Review";
            $history->change_from = $lastDocument->status;
            $history->action = 'Send to QA Initial Review';

            if (is_null($lastDocument->Send_to_QA_Initial_Review_By) || $lastDocument->Send_to_QA_Initial_Review_By === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }

            $history->save();
            $deviation->update();
            $history = new DeviationHistory();
            $history->type = "Deviation";
            $history->doc_id = $id;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->stage_id = $deviation->stage;
            $history->status = "Send to QA Initial Review";
            $history->save();

            $list = Helpers::getQAUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'history' => 'Send to QA Initial Review',"site" => "DEV", 'process' => 'Deviation', 'comment' => $deviation->Send_to_QA_Initial_Review_Comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to QA Initial Review Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

            $deviation->update();
            toastr()->success('Document Sent');
            return back();

        }

        if($deviation->stage == 7){
            $deviation->stage = "3";
            $deviation->status = "QA Initial Review";
            $deviation->Send_to_QA_Initial_By = Auth::user()->name;
            $deviation->Send_to_QA_Initial_On = Carbon::now()->format('d-M-Y H:i A');
            $deviation->Send_to_QA_Initial_Comments = $request->comment;

            $history = new DeviationAuditTrail();
            $history->deviation_id = $id;
            $history->activity_type = 'Initiator Send to QA Initial Review By, Initiator Send to QA Initial Review On';

            if (is_null($lastDocument->Send_to_QA_Initial_By) || $lastDocument->Send_to_QA_Initial_By === '') {
                $history->previous = "";
            } else {
                $history->previous = $lastDocument->Send_to_QA_Initial_By . ' , ' . $lastDocument->Send_to_QA_Initial_On;

            }
            $history->current = $deviation->Send_to_QA_Initial_By . ' , ' . $deviation->Send_to_QA_Initial_On;

            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            // $history->stage = 'Send to HOD';
            $history->stage = 'QA Initial Review';
            $history->change_to =   "QA Initial Review";
            $history->change_from = $lastDocument->status;
            $history->action = 'Send to QA Initial Review';

            if (is_null($lastDocument->Send_to_QA_Initial_By) || $lastDocument->Send_to_QA_Initial_By === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }

            $history->save();

            $list = Helpers::getQAUserList($deviation->division_id);
            foreach ($list as $u) {
                //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                    $email = Helpers::getInitiatorEmail($u->user_id);
                    if ($email !== null) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $deviation, 'history' => 'Send to QA Initial Review', "site" => "DEV", 'process' => 'Deviation', 'comment' => $deviation->Send_to_QA_Initial_Comments, 'user'=> Auth::user()->name],
                            //    function ($message) use ($email) {
                            //        $message->to($email)
                            //            ->subject("Document Sent By " . Auth::user()->name);
                            //    }
                            //);
                            function ($message) use ($email, $deviation, $history) {
                                $message->to($email)
                                ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to QA Initial Review Performed"); }
                            );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                //}
            }
            $deviation->update();

            toastr()->success('Document Sent');
            return back();

        }


        if($deviation->stage == 10){
            $deviation->stage = "3";
            $deviation->status = "QA Initial Review";
            $deviation->Approval_Send_to_QA_Initial_By = Auth::user()->name;
            $deviation->Approval_Send_to_QA_Initial_On = Carbon::now()->format('d-M-Y H:i A');
            $deviation->Approval_Send_to_QA_Initial_Comments = $request->comment;

            $history = new DeviationAuditTrail();
            $history->deviation_id = $id;
            $history->activity_type = 'QA Approval Send to QA Initial Review By, QA Approval Send to QA Initial Review On';

            if (is_null($lastDocument->Approval_Send_to_QA_Initial_By) || $lastDocument->Approval_Send_to_QA_Initial_By === '') {
                $history->previous = "";
            } else {
                $history->previous = $lastDocument->Approval_Send_to_QA_Initial_By . ' , ' . $lastDocument->Approval_Send_to_QA_Initial_On;

            }
            $history->current = $deviation->Approval_Send_to_QA_Initial_By . ' , ' . $deviation->Approval_Send_to_QA_Initial_On;

            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            // $history->stage = 'Send to HOD';
            $history->stage = 'QA Initial Review';
            $history->change_to =   "QA Initial Review";
            $history->change_from = $lastDocument->status;
            $history->action = 'Send to QA Initial Review';

            if (is_null($lastDocument->Approval_Send_to_QA_Initial_By) || $lastDocument->Approval_Send_to_QA_Initial_By === '') {
                $history->action_name = 'New';
            } else {
                $history->action_name = 'Update';
            }

            $history->save();

            $list = Helpers::getQAUserList($deviation->division_id);
            foreach ($list as $u) {
                //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                    $email = Helpers::getInitiatorEmail($u->user_id);
                    if ($email !== null) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $deviation, 'history' => 'Send to QA Initial Review', "site" => "DEV", 'process' => 'Deviation', 'comment' => $deviation->Approval_Send_to_QA_Initial_Comments, 'user'=> Auth::user()->name],
                            //    function ($message) use ($email) {
                            //        $message->to($email)
                            //            ->subject("Document Sent By " . Auth::user()->name);
                            //    }
                            //);
                            function ($message) use ($email, $deviation, $history) {
                                $message->to($email)
                                ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: Send to QA Initial Review Performed"); }
                            );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                //}
            }

            $deviation->update();

            toastr()->success('Document Sent');
            return back();

        }

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function deviation_reject(Request $request, $id)
    {

        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            // return $request;
            $deviation = Deviation::find($id);
            $lastDocument = Deviation::find($id);
            $list = Helpers::getInitiatorUserList();


            if ($deviation->stage == 2) {
                // dd($deviation->stage);
                $deviation->stage = "1";
                $deviation->status = "Opened";
                $deviation->hod_more_info_required_by = Auth::user()->name;
                $deviation->hod_more_info_required_on = Carbon::now()->format('d-M-Y H:i A');
                $deviation->hod_more_info_required_comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'Hod More Information Required By, Hod More Information Required On';

                if (is_null($lastDocument->hod_more_info_required_by) || $lastDocument->hod_more_info_required_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->hod_more_info_required_by . ' , ' . $lastDocument->hod_more_info_required_on;

                }
                $history->current = $deviation->hod_more_info_required_by . ' , ' . $deviation->hod_more_info_required_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                // $history->stage = 'Send to QA Initial Review';
                $history->change_to =   "Opened";
                $history->change_from = $lastDocument->status;
                $history->action = 'More Info Required';

                if (is_null($lastDocument->hod_more_info_required_by) || $lastDocument->hod_more_info_required_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                $deviation->update();

                $list = Helpers::getInitiatorUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'More Information Required', 'process' => 'Deviation', 'comment' => $deviation->hod_more_info_required_comments, 'user'=> Auth::user()->name],
                                    //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: More Information Required Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }


                //$history = new DeviationHistory();
                //$history->type = "Deviation";
                //$history->doc_id = $id;
                //$history->user_id = Auth::user()->id;
                //$history->user_name = Auth::user()->name;
                //$history->stage_id = $deviation->stage;
                //$history->status = "Opened";
                //foreach ($list as $u) {
                //    if ($u->q_m_s_divisions_id == $deviation->division_id) {
                //        $email = Helpers::getInitiatorEmail($u->user_id);
                //        if ($email !== null) {

                //            try {
                //                Mail::send(
                //                    'mail.view-mail',
                //                    ['data' => $deviation],
                //                    function ($message) use ($email) {
                //                        $message->to($email)
                //                            ->subject("Activity Performed By " . Auth::user()->name);
                //                    }
                //                );
                //            } catch (\Exception $e) {
                //                //log error
                //            }
                //        }
                //    }
                //}
                //$history->save();

                toastr()->success('Document Sent');
                return back();
            }
            if ($deviation->stage == 3) {
                $deviation->stage = "2";
                $deviation->status = "HOD Review";
                $deviation->form_progress = 'hod';
                $deviation->qa_more_info_required_by = Auth::user()->name;
                $deviation->qa_more_info_required_on = Carbon::now()->format('d-M-Y H:i A');
                $deviation->qa_more_info_required_comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'QA More Information Required By, QA More Information Required On';

                if (is_null($lastDocument->qa_more_info_required_by) || $lastDocument->qa_more_info_required_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->qa_more_info_required_by . ' , ' . $lastDocument->qa_more_info_required_on;

                }
                $history->current = $deviation->qa_more_info_required_by . ' , ' . $deviation->qa_more_info_required_on;

                // $history->action='More Information Required';
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                // $history->stage = 'More Info Required';
                $history->change_to =   "HOD Review";
                $history->change_from = $lastDocument->status;
                $history->action = 'More Info Required';

                if (is_null($lastDocument->qa_more_info_required_by) || $lastDocument->qa_more_info_required_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();

                $history = new DeviationHistory();
                $history->type = "Deviation";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $deviation->stage;
                $history->status = "More Info Required";
                $history->save();

                $deviation->update();

                $list = Helpers::getHodUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'More Information Required', 'process' => 'Deviation', 'comment' => $deviation->qa_more_info_required_comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: More Information Required Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

                //foreach ($list as $u) {
                //    if ($u->q_m_s_divisions_id == $deviation->division_id) {
                //        $email = Helpers::getInitiatorEmail($u->user_id);
                //        if ($email !== null) {

                //            try {
                //                Mail::send(
                //                    'mail.view-mail',
                //                    ['data' => $deviation],
                //                    function ($message) use ($email) {
                //                        $message->to($email)
                //                            ->subject("Activity Performed By " . Auth::user()->name);
                //                    }
                //                );
                //            } catch (\Exception $e) {
                //                //log error
                //            }
                //        }
                //    }
                //}


                toastr()->success('Document Sent');
                return back();
            }
            if ($deviation->stage == 4) {

                $cftResponse = DeviationCftsResponse::withoutTrashed()->where(['deviation_id' => $id])->get();

                // Soft delete all records
                $cftResponse->each(function ($response) {
                    $response->delete();
                });

                $stage = new DeviationCftsResponse();
                $stage->deviation_id = $id;
                $stage->cft_user_id = Auth::user()->id;
                $stage->status = "More Info Required";
                // $stage->cft_stage = ;
                $stage->comment = $request->comment;
                $stage->save();

                $deviation->stage = "3";
                $deviation->status = "QA Initial Review";
                $deviation->form_progress = 'qa';

                $deviation->cft_more_info_required_by = Auth::user()->name;
                $deviation->cft_more_info_required_on = Carbon::now()->format('d-M-Y H:i A');
                $deviation->cft_more_info_required_comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'CFT More Information Required By, CFT More Information Required On';

                if (is_null($lastDocument->cft_more_info_required_by) || $lastDocument->cft_more_info_required_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->cft_more_info_required_by . ' , ' . $lastDocument->cft_more_info_required_on;

                }
                $history->current = $deviation->cft_more_info_required_by . ' , ' . $deviation->cft_more_info_required_on;

                // $history->action='More Info Required';
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'QA Initial Review';
                $history->change_to =   "QA Initial Review";
                $history->change_from = $lastDocument->status;
                $history->action = 'More Info Required';

                if (is_null($lastDocument->cft_more_info_required_by) || $lastDocument->cft_more_info_required_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                $deviation->update();

                $history = new DeviationHistory();
                $history->type = "Deviation";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $deviation->stage;
                $history->status = "More Info Required";

                $list = Helpers::getQAUserList($deviation->division_id);
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $deviation->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {

                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $deviation, 'site' => 'DEV', 'history' => 'More Information Required', 'process' => 'Deviation', 'comment' => $deviation->cft_more_info_required_comments, 'user'=> Auth::user()->name],
                                //    function ($message) use ($email) {
                                //        $message->to($email)
                                //            ->subject("Document Sent By " . Auth::user()->name);
                                //    }
                                //);
                                function ($message) use ($email, $deviation, $history) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Deviation, Record #" . str_pad($deviation->record, 4, '0', STR_PAD_LEFT) . " - Activity: More Information Required Performed"); }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    //}
                }

                //foreach ($list as $u) {
                //    if ($u->q_m_s_divisions_id == $deviation->division_id) {
                //        $email = Helpers::getInitiatorEmail($u->user_id);
                //        if ($email !== null) {

                //            try {
                //                Mail::send(
                //                    'mail.view-mail',
                //                    ['data' => $deviation],
                //                    function ($message) use ($email) {
                //                        $message->to($email)
                //                            ->subject("Activity Performed By " . Auth::user()->name);
                //                    }
                //                );
                //            } catch (\Exception $e) {
                //                //log error
                //            }
                //        }
                //    }
                //}
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }

            if ($deviation->stage == 6) {
                $deviation->stage = "5";
                $deviation->status = "QA Secondary Review";
                $deviation->form_progress = 'capa';

                $deviation->QAH_More_Information_Required_By = Auth::user()->name;
                $deviation->QAH_More_Information_Required_On = Carbon::now()->format('d-M-Y H:i A');
                $deviation->QAH_More_Information_Required_Comments = $request->comment;

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;

                $history->activity_type = 'QAH More Information Required By, QAH More Information Required On';

                if (is_null($lastDocument->QAH_More_Information_Required_By) || $lastDocument->QAH_More_Information_Required_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->QAH_More_Information_Required_By . ' , ' . $lastDocument->QAH_More_Information_Required_On;

                }
                $history->current = $deviation->QAH_More_Information_Required_By . ' , ' . $deviation->QAH_More_Information_Required_On;


                // $history->action='More Info Required';
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'QA Secondary Review';
                $history->change_to =   "QA Secondary Review";
                $history->change_from = $lastDocument->status;
                $history->action = 'More Info Required';

                if (is_null($lastDocument->QAH_More_Information_Required_By) || $lastDocument->QAH_More_Information_Required_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                // dd();
                //foreach ($list as $u) {
                //    if ($u->q_m_s_divisions_id == $deviation->division_id) {
                //        $email = Helpers::getInitiatorEmail($u->user_id);
                //        if ($email !== null) {

                //            try {
                //                Mail::send(
                //                    'mail.view-mail',
                //                    ['data' => $deviation],
                //                    function ($message) use ($email) {
                //                        $message->to($email)
                //                            ->subject("Activity Performed By " . Auth::user()->name);
                //                    }
                //                );
                //            } catch (\Exception $e) {
                //                //log error
                //            }
                //        }
                //    }
                //}
                $history->save();
                $deviation->update();
                $history = new DeviationHistory();
                $history->type = "Deviation";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $deviation->stage;
                $history->status = "More Info Required";
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }

            if($deviation->stage = 8){
                $deviation->stage = "7";
                $deviation->status = "Pending Initiator Update";
                $deviation->qa_more_info_required_by = Auth::user()->name;
                $deviation->qa_more_info_required_on = Carbon::now()->format('d-M-Y');

                $history = new DeviationAuditTrail();
                $history->deviation_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $deviation->qa_more_info_required_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to =   "Pending Intiator Update";
                $history->change_from = $lastDocument->status;
                $history->action = 'Send to Pending Initiator Update';
                // $history->stage = 'Send to HOD';
                $history->save();
                $deviation->update();
                toastr()->success('Document Sent');
                return back();
            }

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function deviation_child_1(Request $request, $id)
    {

        $cft = [];
        $parent_id = $id;
        $parent_type = "Deviation";
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $parent_record = Deviation::where('id', $id)->value('record');
        $parent_record = str_pad($parent_record, 4, '0', STR_PAD_LEFT);
        $parent_division_id = Deviation::where('id', $id)->value('division_id');
        $parent_initiator_id = Deviation::where('id', $id)->value('initiator_id');
        $parent_intiation_date = Deviation::where('id', $id)->value('intiation_date');
        $parent_created_at = Deviation::where('id', $id)->value('created_at');
        $parent_short_description = Deviation::where('id', $id)->value('short_description');
        $hod = User::where('role', 4)->get();
        if ($request->child_type == "extension") {
            $parent_due_date = "";
            $parent_id = $id;
            $parent_name = $request->parent_name;
            if ($request->due_date) {
                $parent_due_date = $request->due_date;
            }

            $record_number = ((RecordNumber::first()->value('counter')) + 1);
            $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
            $Extensionchild = Deviation::find($id);
            $Extensionchild->Extensionchild = $record_number;
            $parentDivisionId = Deviation::where('id', $id)->value('division_id');
            $Extensionchild->save();
            return view('frontend.extension.extension_new', compact('parent_id','parent_record','parentDivisionId', 'parent_name','parent_type', 'record_number', 'parent_due_date', 'due_date', 'parent_created_at'));
        }
        $old_record = Deviation::select('id', 'division_id', 'record')->get();
        // dd($request->child_type)
        if ($request->child_type == "capa") {
            $parent_name = "CAPA";
            $Capachild = Deviation::find($id);
            $Capachild->Capachild = $record_number;
            $Capachild->save();

            return view('frontend.forms.capa', compact('parent_id', 'parent_record','parent_type', 'record_number', 'due_date', 'parent_short_description', 'parent_initiator_id', 'parent_intiation_date', 'parent_name', 'parent_division_id', 'parent_record', 'old_record', 'cft'));
        } elseif ($request->child_type == "Action_Item")
         {
            $parent_name = "CAPA";
            $actionchild = Deviation::find($id);
            $actionchild->actionchild = $record_number;
            $parent_id = $id;
            $actionchild->save();

            return view('frontend.forms.action-item', compact('old_record', 'parent_short_description', 'parent_initiator_id', 'parent_intiation_date', 'parent_name', 'parent_division_id', 'parent_record', 'record_number', 'due_date', 'parent_id', 'parent_type'));
        }
        elseif ($request->child_type == "effectiveness_check")
         {
            $parent_name = "CAPA";
            $effectivenesschild = Deviation::find($id);
            $effectivenesschild->effectivenesschild = $record_number;

            $effectivenesschild->save();
        return view('frontend.forms.effectiveness-check', compact('old_record','parent_short_description','parent_record', 'parent_initiator_id', 'parent_intiation_date', 'parent_division_id',  'record_number', 'due_date', 'parent_id', 'parent_type'));
        }
        elseif ($request->child_type == "Change_control") {
            $parent_name = "CAPA";
            $Changecontrolchild = Deviation::find($id);
            $Changecontrolchild->Changecontrolchild = $record_number;

            $Changecontrolchild->save();

            return view('frontend.change-control.new-change-control', compact('cft','pre','hod','parent_short_description', 'parent_initiator_id', 'parent_intiation_date', 'parent_division_id',  'record_number', 'due_date', 'parent_id', 'parent_type'));
        }
        else {
            $parent_name = "Root";
            $Rootchild = Deviation::find($id);
            $Rootchild->Rootchild = $record_number;
            $Rootchild->save();
            return view('frontend.forms.root-cause-analysis', compact('parent_id', 'parent_record','parent_type', 'record_number', 'due_date', 'parent_short_description', 'parent_initiator_id', 'parent_intiation_date', 'parent_name', 'parent_division_id', 'parent_record', ));
        }
    }

    public function DeviationAuditTrial($id)
    {
        $audit = DeviationAuditTrail::where('deviation_id', $id)
        ->orderByDesc('id')
        ->paginate(5);
        $today = Carbon::now()->format('d-m-y');
        $document = Deviation::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');
        return view('frontend.forms.deviation_audit', compact('audit', 'document', 'today'));
    }
    public function rootAuditTrial($id)
    {
        $audit = RootAuditTrial::where('root_id', $id)->orderByDESC('id')->get()->unique('activity_type');
        $today = Carbon::now()->format('d-m-y');
        $document = RootCauseAnalysis::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');
        dd($document->initiator);

        return view("frontend.root-cause-analysis.root-audit-trail", compact('audit', 'document', 'today'));
    }

    public function DeviationAuditTrialDetails($id)
    {
        $detail = DeviationAuditTrail::find($id);
        $detail_data = DeviationAuditTrail::where('activity_type', $detail->activity_type)->where('deviation_id', $detail->deviation_id)->latest()->get();
        $doc = Deviation::where('id', $detail->deviation_id)->first();
        $doc->origiator_name = User::find($doc->initiator_id);
        return view('frontend.forms.audit-trial-deviation-inner', compact('detail', 'doc', 'detail_data'));
    }
    public static function singleReport($id)
    {
        $data = Deviation::find($id);
        // return $data;
        $data1 =  DeviationCft::where('deviation_id', $id)->first();
        if (!empty ($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $grid_data = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Deviation")->first();
            $grid_data1 = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Document")->first();

            $investigation_data = DeviationNewGridData::where(['deviation_id' => $id, 'identifier' => 'investication'])->first();
            $root_cause_data = DeviationNewGridData::where(['deviation_id' => $id, 'identifier' => 'rootCause'])->first();
            $why_data = DeviationNewGridData::where(['deviation_id' => $id, 'identifier' => 'why'])->first();

            $capaExtension = LaunchExtension::where(['deviation_id' => $id, "identifier" => "Capa"])->first();
            $qrmExtension = LaunchExtension::where(['deviation_id' => $id, "identifier" => "QRM"])->first();
            $investigationExtension = LaunchExtension::where(['deviation_id' => $id, "identifier" => "Investigation"])->first();

            $grid_data_qrms = DeviationGridQrms::where(['deviation_id' => $id, 'identifier' => 'failure_mode_qrms'])->first();
            $grid_data_matrix_qrms = DeviationGridQrms::where(['deviation_id' => $id, 'identifier' => 'matrix_qrms'])->first();

            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.forms.SingleReportdeviation', compact('data','grid_data_qrms','grid_data_matrix_qrms','capaExtension','qrmExtension','investigationExtension','root_cause_data','why_data','investigation_data','grid_data','grid_data1', 'data1'))
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
            return $pdf->stream('Deviation' . $id . '.pdf');
        }
    }

    public static function parentchildReport($id)
    {
        $data = Deviation::find($id);
        $data4 =  DeviationCft::where('deviation_id', $id)->first();
        $Capachild = $data->Capachild;
        $Rootchild = $data->Rootchild;

        $Extensionchild = $data->Extensionchild;
        $data1 = Capa::where('record', $Capachild)->first();
        $data2 = RootCauseAnalysis::where('record', $Rootchild)->first();

        $data3 = Extension::where('record', $Extensionchild)->first();
        if (!empty ($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $grid_data = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Deviation")->first();

            $grid_data1 = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Document")->first();
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.forms.deviationparentchildReport', compact('data', 'data1', 'data2', 'data3','data4','grid_data1','grid_data'))
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
            return $pdf->stream('Deviation' . $id . '.pdf');
        }
    }
    public static function deviationfamilyReport($id)
    {
        $data = Deviation::find($id);
        $data7 =  DeviationCft::where('deviation_id', $id)->first();
        $Capachild = $data->Capachild;

        $Rootchild = $data->Rootchild;
        $Extensionchild = $data->Extensionchild;
         $actionchild=$data->actionchild;
         $effectivenesschild=$data->effectivenesschild;
         $Changecontrolchild=$data->Changecontrolchild;
        //  dd($Changecontrolchild);
        $data1 = Capa::where('record', $Capachild)->first();
        $data2 = RootCauseAnalysis::where('record', $Rootchild)->first();
        $data3 = Extension::where('record', $Extensionchild)->first();
        $data4 = ActionItem::where('record', $actionchild)->first();
        $data5 = EffectivenessCheck::where('record', $effectivenesschild)->first();
        $data6 = CC::where('record', $Changecontrolchild)->first();

        // $data4 = CC::find($id);

        if (!empty ($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $grid_data = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Deviation")->first();

            $grid_data1 = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Document")->first();
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.forms.DeviationFamily', compact('data', 'data1', 'data2', 'data3','data4','data5','data6','grid_data1','grid_data','data7'))
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
            return $pdf->stream('Deviation' . $id . '.pdf');
        }
    }

   public static function auditReport($id)
    {
        $doc = Deviation::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = DeviationAuditTrail::where('deviation_id', $doc->id)->orderByDesc('id')->get();

            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.forms.auditReport', compact('data', 'doc'))
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

    public function store_audit_review(Request $request, $id)
    {
            $history = new AuditReviewersDetails;
            $history->deviation_id = $id;
            $history->user_id = Auth::user()->id;
            $history->reviewer_comment = $request->reviewer_comment;
            $history->reviewer_comment_by = Auth::user()->name;
            $history->reviewer_comment_on = Carbon::now()->toDateString();
            $history->save();

        return redirect()->back();
    }

}
