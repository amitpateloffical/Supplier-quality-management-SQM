<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Models\AuditTrialObservation;
use App\Models\Observation;
use App\Models\RecordNumber;
use App\Models\User;
use App\Models\OpenStage;
use App\Models\Capa;
use Carbon\Carbon;
use Helpers;
use PDF;
use Illuminate\Support\Facades\App;

use App\Models\RoleGroup;
use App\Models\ObservationGrid;
use App\Models\InternalAuditGrid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ObservationController extends Controller
{

    public function observation()
    {
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');
        return view('frontend.forms.observation', compact('due_date', 'record_number'));
    }

    public function observationstore(Request $request)
    {

        if (!$request->short_description) {
            toastr()->error("Short description is required");
            //return redirect()->back();
        }
        $data = new Observation();

        $data->record = ((RecordNumber::first()->value('counter')) + 1);
        $data->initiator_id = Auth::user()->id;
        $data->parent_id = $request->parent_id;
        // dd($request->parent_id);
        $data->parent_type = $request->parent_type;
        $data->division_id = $request->division_id;
        $data->intiation_date = $request->intiation_date;
        $data->due_date = $request->due_date;
        $data->short_description = $request->short_description;
        $data->assign_to = $request->assign_to;
        // $data->grading = $request->grading;
        // $data->category_observation = $request->category_observation;
        // $data->reference_guideline = $request->reference_guideline;
        // $data->description = $request->description;

        // if ($request->hasfile('gned1')) {
        //     $image = $request->file('attach_files1');
        //     $ext = $image->getClientOriginalExtension();
        //     $image_name = date('y-m-d') . '-' . rand() . '.' . $ext;
        //     $image->move('upload/document/', $image_name);
        //     $data->attach_files1 = $image_name;
        // }

        if (!empty($request->attach_files1)) {
            $files = [];
            if ($request->hasfile('attach_files1')) {
                foreach ($request->file('attach_files1') as $file) {
                    $name = $request->name . 'attach_files1' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $data->attach_files1 = json_encode($files);
        }
        $data->recomendation_capa_date_due = $request->recomendation_capa_date_due;
        $data->non_compliance = $request->non_compliance;
        $data->recommend_action = $request->recommend_action;
        $data->date_Response_due2 = $request->date_Response_due2;
        $data->capa_date_due = $request->capa_date_due;
        $data->assign_to2 = $request->assign_to2;
        // $data->cro_vendor = $request->cro_vendor;
        $data->comments = $request->comments;
        $data->impact = $request->impact;
        $data->impact_analysis = $request->impact_analysis;
        $data->severity_rate = $request->severity_rate;
        $data->occurrence = $request->occurrence;
        $data->detection = $request->detection;
        $data->analysisRPN = $request->analysisRPN;
        $data->actual_start_date = $request->actual_start_date;
        $data->actual_end_date = $request->actual_end_date;
        $data->action_taken = $request->action_taken;
        // $data->date_response_due1= $request->date_response_due1;

        // $data->response_date = $request->response_date;
        $data->attach_files2 = $request->attach_files2;
        $data->related_url = $request->related_url;
        $data->response_summary = $request->response_summary;

        // if ($request->hasfile('related_observations')) {
        //     $image = $request->file('related_observations');
        //     $ext = $image->getClientOriginalExtension();
        //     $image_name = date('y-m-d') . '-' . rand() . '.' . $ext;
        //     $image->move('upload/document/', $image_name);
        //     $data->related_observations = $image_name;
        // }
        if (!empty($request->related_observations)) {
            $files = [];
            if ($request->hasfile('related_observations')) {
                foreach ($request->file('related_observations') as $file) {
                    $name = $request->name . 'related_observations' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $data->related_observations = json_encode($files);
        }

        if (!empty($request->attach_files2)) {
            $files = [];
            if ($request->hasfile('attach_files2')) {
                foreach ($request->file('attach_files2') as $file) {
                    $name = $request->name . 'attach_files2' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $data->attach_files2 = json_encode($files);
        }
        $data->status = 'Opened';
        $data->stage = 1;
        $data->save();

        $data1 = new ObservationGrid();
        $data1->observation_id = $data->id;
        if (!empty($request->action)) {
            $data1->action = serialize($request->action);
        }
        if (!empty($request->responsible)) {
            $data1->responsible = serialize($request->responsible);
        }
        if (!empty($request->deadline)) {
            $data1->deadline = serialize($request->deadline);
        }
        if (!empty($request->item_status)) {
            $data1->item_status = serialize($request->item_status);
        }
        $data1->save();

        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();

        if (! empty($data->parent_id)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
            $history->activity_type = 'Parent Id';
            $history->previous = "Null";
            $history->current = $data->parent_id;
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

        if (! empty($data->parent_type)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
            $history->activity_type = 'Parent Type';
            $history->previous = "Null";
            $history->current = $data->parent_type;
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

        if (! empty($data->division_id)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
            $history->activity_type = 'Site/Location Code';
            $history->previous = "Null";
            $history->current = Helpers::getDivisionName($data->division_id);
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

        
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
            $history->activity_type = 'Record Number';
            $history->previous = "Null";
            $history->current = Helpers::getDivisionName(session()->get('division')) . "/OBS/" . Helpers::year($data->created_at) . "/" . str_pad($data->record, 4, '0', STR_PAD_LEFT) ;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        


        if (! empty($data->initiator_id)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
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

        if (! empty($data->intiation_date)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
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

        if (! empty($data->assign_to)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
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

        if (! empty($data->due_date)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
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

        // if (! empty($data->grading)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $data->id;
        //     $history->activity_type = 'Grading';
        //     $history->previous = "Null";
        //     $history->current = $data->grading;
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

        // if (! empty($data->category_observation)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $data->id;
        //     $history->activity_type = 'Category Observation';
        //     $history->previous = "Null";
        //     $history->current = $data->category_observation;
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


        // if (! empty($data->reference_guideline)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $data->id;
        //     $history->activity_type = 'Reference Guideline';
        //     $history->previous = "Null";
        //     $history->current = $data->reference_guideline;
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

        // if (! empty($data->description)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $data->id;
        //     $history->activity_type = 'Parent Type';
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


        if (! empty($data->short_description)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
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


        if (! empty($data->attach_files1)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
            $history->activity_type = 'Attached Files';
            $history->previous = "Null";
            $history->current = json_encode($data->attach_files1);
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

        if (! empty($data->recomendation_capa_date_due)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
            $history->activity_type = 'Recomendation Capa Date Due';
            $history->previous = "Null";
            $history->current = $data->recomendation_capa_date_due;
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
        if (! empty($data->non_compliance)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
            $history->activity_type = 'Non Compliance';
            $history->previous = "Null";
            $history->current = $data->non_compliance;
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
        if (! empty($data->recommend_action)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
            $history->activity_type = 'Recommended Action';
            $history->previous = "Null";
            $history->current = $data->recommend_action;
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

        if (! empty($data->related_observations)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
            $history->activity_type = 'Related Observations';
            $history->previous = "Null";
            $history->current = json_encode($data->related_observations);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();

            if (! empty($data->date_Response_due2)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'Date Response Due';
                $history->previous = "Null";
                $history->current = $data->date_Response_due2;
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
            if (! empty($data->capa_date_due)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'Due Date.';
                $history->previous = "Null";
                $history->current = $data->capa_date_due;
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
            if (! empty($data->assign_to2)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'Assigned To.';
                $history->previous = "Null";
                $history->current = $data->assign_to2;
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

            // if (! empty($data->cro_vendor)) {
            //     $history = new AuditTrialObservation();
            //     $history->Observation_id = $data->id;
            //     $history->activity_type = 'Cro Vendor ';
            //     $history->previous = "Null";
            //     $history->current = $data->cro_vendor;
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

            if (! empty($data->comments)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'Comments ';
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
            if (! empty($data->impact)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'Impact ';
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
            if (! empty($data->impact_analysis)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'Impact Analysis ';
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
            if (! empty($data->severity_rate)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'Severity Rate ';
                $history->previous = "Null";

                // $history->current = $data->severity_rate;
                if ($request->severity_rate == 1) {
                    $history->current = "Negligible";
                } elseif ($request->severity_rate == 2) {
                    $history->current = "Moderate";
                } elseif ($request->severity_rate == 3) {
                    $history->current = "Major";
                } else {
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
            if (! empty($data->occurrence)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'Occurrence ';
                $history->previous = "Null";
                // $history->current = $data->occurrence;
                if ($request->occurrence == 1) {
                    $history->current = "Very Likely";
                } elseif ($request->occurrence == 2) {
                    $history->current = "Likely";
                } elseif ($request->occurrence == 3) {
                    $history->current = "Unlikely";
                } elseif ($request->occurrence == 4) {
                    $history->current = "Rare";
                } else {
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
            if (! empty($data->detection)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'Detection ';
                $history->previous = "Null";
                // $history->current = $data->detection;
                if ($request->detection == 1) {
                    $history->current = "Very Likely";
                } elseif ($request->detection == 2) {
                    $history->current = "Likely";
                } elseif ($request->detection == 3) {
                    $history->current = "Unlikely";
                } elseif ($request->detection == 4) {
                    $history->current = "Rare";
                } else {
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
            if (! empty($data->analysisRPN)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'RPN ';
                $history->previous = "Null";
                $history->current = $data->analysisRPN;
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

            if (! empty($data->actual_start_date)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'Actual Start Date ';
                $history->previous = "Null";
                $history->current = Helpers::getdateFormat($data->actual_start_date);
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

            if (! empty($data->actual_end_date)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'Actual End Date ';
                $history->previous = "Null";
                $history->current = Helpers::getdateFormat($data->actual_end_date);
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

            if (! empty($data->action_taken)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'Action Taken ';
                $history->previous = "Null";
                $history->current = $data->action_taken;
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

            // if (! empty($data->date_response_due1)) {
            //     $history = new AuditTrialObservation();
            //     $history->Observation_id = $data->id;
            //     $history->activity_type = 'Date Response Due1 ';
            //     $history->previous = "Null";
            //     $history->current = $data->date_response_due1;
            //     $history->comment = "Not Applicable";
            //     $history->user_id = Auth::user()->id;
            //     $history->user_name = Auth::user()->name;
            //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            //     $history->origin_state = $data->status;
            //     $history->save();
            // }

            // if (! empty($data->response_date)) {
            //     $history = new AuditTrialObservation();
            //     $history->Observation_id = $data->id;
            //     $history->activity_type = 'Response Date ';
            //     $history->previous = "Null";
            //     $history->current = $data->response_date;
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

            // if (! empty($data->assign_to2)) {
            //     $history = new AuditTrialObservation();
            //     $history->Observation_id = $data->id;
            //     $history->activity_type = 'Attach Files2 ';
            //     $history->previous = "Null";
            //     $history->current = json_encode($data->assign_to2);
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

            if (! empty($data->attach_files2)) {
                $history = new AuditTrialObservation();
                $history->Observation_id = $data->id;
                $history->activity_type = 'Attachment';
                $history->previous = "Null";
                $history->current = json_encode($data->attach_files2);
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $data->status;
                $history->change_from = "Initiation";
                $history->change_to = "Opened";
                $history->action_name = "Create";
                $history->save();

                if (! empty($data->related_url)) {
                    $history = new AuditTrialObservation();
                    $history->Observation_id = $data->id;
                    $history->activity_type = 'Related Url ';
                    $history->previous = "Null";
                    $history->current = $data->related_url;
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

                if (! empty($data->response_summary)) {
                    $history = new AuditTrialObservation();
                    $history->Observation_id = $data->id;
                    $history->activity_type = 'Response Summary ';
                    $history->previous = "Null";
                    $history->current = $data->response_summary;
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


                // $history = new AuditTrialObservation();
                // $history->Observation_id = $data->id;
                // $history->activity_type = 'Parent ';
                // $history->previous = "Null";
                // $history->current = $data->response_summary;
                // $history->comment = "Not Applicable";
                // $history->user_id = Auth::user()->id;
                // $history->user_name = Auth::user()->name;
                // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                // $history->origin_state = $data->status;
                // $history->change_from = "Initiation";
                // $history->change_to = "Opened";
                // $history->action_name = "Create";
                // $history->save();

                // $history = new AuditTrialObservation();
                // $history->Observation_id = $data->id;
                // $history->activity_type = 'Attach Files2 ';
                // $history->previous = "Null";
                // $history->current = json_encode($data->attach_files2);
                // $history->comment = "Not Applicable";
                // $history->user_id = Auth::user()->id;
                // $history->user_name = Auth::user()->name;
                // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                // $history->origin_state = $data->status;
                // $history->change_from = "Initiation";
                // $history->change_to = "Opened";
                // $history->action_name = "Create";
                // $history->save();
            }
        }
        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }
    public function observationupdate(Request $request, $id)
    {
        $lastDocument = Observation::find($id);
        $data = Observation::find($id);
         $data->initiator_id = Auth::user()->id;
        // $data->parent_id = $request->parent_id;
        // $data->parent_type = $request->parent_type;
        // $data->division_code = $request->division_code;
        // $data->intiation_date = $request->intiation_date;
        // $data->due_date = $request->due_date;
        $data->short_description = $request->short_description;
        $data->assign_to = $request->assign_to;
        // $data->grading = $request->grading;
        // $data->category_observation = $request->category_observation;
        // $data->reference_guideline = $request->reference_guideline;
        // $data->description = $request->description;

        // if ($request->hasfile('attach_files1')) {
        //     $image = $request->file('attach_files1');
        //     $ext = $image->getClientOriginalExtension();
        //     $image_name = date('y-m-d') . '-' . rand() . '.' . $ext;
        //     $image->move('upload/document/', $image_name);
        //     $data->attach_files1 = $image_name;
        // }

        // $files = is_array($request->existing_attach_files) ? $request->existing_attach_files : [];
        // if (!empty ($request->attach_files1)) {
        //     if ($data->attach_files1) {
        //         $existingFiles = json_decode($data->attach_files1, true); // Convert to associative array
        //         if (is_array($existingFiles)) {
        //             $files = $existingFiles;
        //         }
        //         // $files = is_array(json_decode($data->attach_files1)) ? $data->attach_files1 : [];
        //     }

        //     if ($request->hasfile('attach_files1')) {
        //         foreach ($request->file('attach_files1') as $file) {
        //             $name = $request->name . 'attach_files1' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        // }
        // $data->attach_files1 = json_encode($files);
        $files = is_array($request->existing_attach_files) ? $request->existing_attach_files : null;

        if (!empty($request->attach_files1)) {
            if ($data->attach_files1) {
                $existingFiles = json_decode($data->attach_files1, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('attach_files1')) {
                foreach ($request->file('attach_files1') as $file) {
                    $name = $request->name . 'attach_files1' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        // If no files are attached, set to null
        $data->attach_files1 = !empty($files) ? json_encode(array_values($files)) : null;


        // if (!empty($request->attach_files1)) {
        //     $files = [];
        //     if ($request->hasfile('attach_files1')) {
        //         foreach ($request->file('attach_files1') as $file) {
        //             $name = $request->name . 'attach_files1' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }

        //     $data->attach_files1 = json_encode($files);
        // }
        $data->recomendation_capa_date_due = $request->recomendation_capa_date_due;
        $data->non_compliance = $request->non_compliance;
        $data->recommend_action = $request->recommend_action;
        $data->date_Response_due2 = $request->date_Response_due2;
        $data->capa_date_due = $request->capa_date_due11;
        $data->assign_to2 = $request->assign_to2;
        // $data->cro_vendor = $request->cro_vendor;
        $data->comments = $request->comments;
        $data->impact = $request->impact;
        $data->impact_analysis = $request->impact_analysis;
        $data->severity_rate = $request->severity_rate;
        $data->occurrence = $request->occurrence;
        $data->detection = $request->detection;
        $data->analysisRPN = $request->analysisRPN;
        $data->actual_start_date = $request->actual_start_date;
        $data->actual_end_date = $request->actual_end_date;
        $data->action_taken = $request->action_taken;

        //  $data->date_response_due1 = $request->date_Response_due22;
        // $data->date_response_due1 = $request->date_response_due1;
        // $data->response_date = $request->response_date;
        // $data->attach_files2 = $request->attach_files2;
        $data->related_url = $request->related_url;
        $data->response_summary = $request->response_summary;

        // if ($request->hasfile('related_observations')) {
        //     $image = $request->file('related_observations');
        //     $ext = $image->getClientOriginalExtension();
        //     $image_name = date('y-m-d') . '-' . rand() . '.' . $ext;
        //     $image->move('upload/document/', $image_name);
        //     $data->related_observations = $image_name;
        // }

        $files = is_array($request->existing_related_observations) ? $request->existing_related_observations : null;

        if (!empty($request->related_observations)) {
            if ($data->related_observations) {
                $existingFiles = json_decode($data->related_observations, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('related_observations')) {
                foreach ($request->file('related_observations') as $file) {
                    $name = $request->name . 'related_observations' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        // If no files are attached, set to null
        $data->related_observations = !empty($files) ? json_encode(array_values($files)) : null;

        // if (!empty($request->related_observations)) {
        //     $files = [];
        //     if ($request->hasfile('related_observations')) {
        //         foreach ($request->file('related_observations') as $file) {
        //             $name = $request->name . 'related_observations' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }

        //     $data->related_observations = json_encode($files);
        // }
        // if ($request->hasfile('attach_files2')) {
        //     $image = $request->file('attach_files2');
        //     $ext = $image->getClientOriginalExtension();
        //     $image_name = date('y-m-d') . '-' . rand() . '.' . $ext;
        //     $image->move('upload/document/', $image_name);
        //     $data->attach_files2 = $image_name;
        // }

        $files = is_array($request->existing_attach_files2) ? $request->existing_attach_files2 : null;

        if (!empty($request->attach_files2)) {
            if ($data->attach_files2) {
                $existingFiles = json_decode($data->attach_files2, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('attach_files2')) {
                foreach ($request->file('attach_files2') as $file) {
                    $name = $request->name . 'attach_files2' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        // If no files are attached, set to null
        $data->attach_files2 = !empty($files) ? json_encode(array_values($files)) : null;

        // if (!empty($request->attach_files2)) {
        //     $files = [];
        //     if ($request->hasfile('attach_files2')) {
        //         foreach ($request->file('attach_files2') as $file) {
        //             $name = $request->name . 'attach_files2' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }

        //     $data->attach_files2 = json_encode($files);
        // }

        $data->update();

        $data1 = ObservationGrid::where('observation_id', $id)->first();

        if (!empty($data1)) { // Check if the record exists
            $data1->observation_id = $id;

            if (!empty($request->action)) {
                $data1->action = serialize($request->action);
            }
            if (!empty($request->responsible)) {
                $data1->responsible = serialize($request->responsible);
            }
            if (!empty($request->deadline)) {
                $data1->deadline = serialize($request->deadline);
            }
            if (!empty($request->item_status)) {
                $data1->item_status = serialize($request->item_status);
            }

            $data1->update(); // Update the record in the database
        }

        // if ($lastDocument->parent_id != $data->parent_id || !empty($request->parent_id_comment)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Parent Id';
        //     $history->previous = $lastDocument->parent_id;
        //     $history->current = $data->parent_id;
        //     $history->comment = $request->parent_id_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";
        //     $history->save();
        // }

        // if ($lastDocument->parent_type != $data->parent_type || !empty($request->parent_type_comment)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Parent Type';
        //     $history->previous = $lastDocument->parent_type;
        //     $history->current = $data->parent_type;
        //     $history->comment = $request->parent_type_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";
        //     $history->save();
        // }

        // if ($lastDocument->division_code != $data->division_code || !empty($request->division_code_comment)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Division Code';
        //     $history->previous = $lastDocument->division_code;
        //     $history->current = $data->division_code;
        //     $history->comment = $request->division_code_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";
        //     $history->save();
        // }

        // if ($lastDocument->short_description != $data->short_description || ! empty($request->short_description_comment)) {
        //         $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
        //             ->where('activity_type', 'Short Description')
        //             ->exists();
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $lastDocument->id;
        //     $history->activity_type = 'Short Description';
        //     $history->previous = $lastDocument->short_description;
        //     $history->current = $data->short_description;
        //     $history->comment = $request->short_description_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
        //     $history->save();
        // }

        // if ($lastDocument->intiation_date != $data->intiation_date || !empty($request->intiation_date_comment)) {
        //     $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
        //              ->where('activity_type', 'Intiation Date')
        //              ->exists();
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Intiation Date';
        //     $history->previous = $lastDocument->intiation_date;
        //     $history->current = $data->intiation_date;
        //     $history->comment = $request->intiation_date_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";
        //     $history->save();
        // }

        // if ($lastDocument->due_date != $data->due_date || ! empty($request->due_date_comment)) {
        //         $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
        //             ->where('activity_type', 'Due Date')
        //             ->exists();
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
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

        if ($lastDocument->short_description != $data->short_description || ! empty($request->short_description_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Short Description')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
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
        if ($lastDocument->attach_files1 != $data->attach_files1 || !empty($request->attach_files1_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Attached Files')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Attached Files';
            $history->previous = $lastDocument->attach_files1;
            $history->current = $data->attach_files1;
            $history->comment = $request->attach_files1_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'NULL';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->assign_to != $data->assign_to || !empty($request->assign_to_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Assigned To')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Assigned To';
            $history->previous = Helpers::getInitiatorName($lastDocument->assign_to);
            $history->current = Helpers::getInitiatorName($data->assign_to);
            $history->comment = $request->assign_to_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        // if ($lastDocument->grading != $data->grading || !empty($request->grading_comment)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Grading';
        //     $history->previous = $lastDocument->grading;
        //     $history->current = $data->grading;
        //     $history->comment = $request->grading_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";
        //     $history->save();
        // }

        // if ($lastDocument->category_observation != $data->category_observation || !empty($request->category_observation_comment)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Category Observation';
        //     $history->previous = $lastDocument->category_observation;
        //     $history->current = $data->category_observation;
        //     $history->comment = $request->category_observation_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";
        //     $history->save();
        // }

        // if ($lastDocument->reference_guideline != $data->reference_guideline || !empty($request->reference_guideline_comment)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Reference Guideline';
        //     $history->previous = $lastDocument->reference_guideline;
        //     $history->current = $data->reference_guideline;
        //     $history->comment = $request->reference_guideline_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";
        //     $history->save();
        // }

        // if ($lastDocument->description != $data->description || !empty($request->description_comment)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Parent Type';
        //     $history->previous = $lastDocument->description;
        //     $history->current = $data->description;
        //     $history->comment = $request->description_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";
        //     $history->save();
        // }

        // if ($lastDocument->attach_files1 != $data->attach_files1 || !empty($request->attach_files1_comment)) {
        //         $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
        //             ->where('activity_type', 'Attach Files1')
        //             ->exists();
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Attach Files1';
        //     $history->previous = $lastDocument->attach_files1;
        //     $history->current = $data->attach_files1;
        //     $history->comment = $request->attach_files1_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
        //     $history->save();
        // }

        if ($lastDocument->recomendation_capa_date_due != $data->recomendation_capa_date_due || !empty($request->recomendation_capa_date_due_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Recomendation Capa Date Due')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Recomendation Capa Date Due';
            $history->previous = $lastDocument->recomendation_capa_date_due;
            $history->current = $data->recomendation_capa_date_due;
            $history->comment = $request->recomendation_capa_date_due_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->non_compliance != $data->non_compliance || !empty($request->non_compliance_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Non Compliance')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Non Compliance';
            $history->previous = $lastDocument->non_compliance;
            $history->current = $data->non_compliance;
            $history->comment = $request->non_compliance_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->recommend_action != $data->recommend_action || !empty($request->recommend_action_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Recommended Action')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Recommended Action';
            $history->previous = $lastDocument->recommend_action;
            $history->current = $data->recommend_action;
            $history->comment = $request->recommend_action_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastDocument->related_observations != $data->related_observations || !empty($request->related_observations_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Related Observations')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Related Observations ';
            $history->previous = $lastDocument->related_observations;
            $history->current = $data->related_observations;
            $history->comment = $request->related_observations_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->date_Response_due2 != $data->date_Response_due2 || !empty($request->date_Response_due2_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Date Response Due')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Date Response Due';
            $history->previous = $lastDocument->date_Response_due2;
            $history->current = $data->date_Response_due2;
            $history->comment = $request->date_Response_due2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->capa_date_due != $data->capa_date_due || !empty($request->capa_date_due_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Due Date.')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Due Date.';
            $history->previous = $lastDocument->capa_date_due;
            $history->current = $data->capa_date_due;
            $history->comment = $request->capa_date_due_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            // $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->assign_to2 != $data->assign_to2 || !empty($request->assign_to2_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Assigned To.')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Assigned To.';
            $history->previous = Helpers::getInitiatorName($lastDocument->assign_to2);
            $history->current = Helpers::getInitiatorName($data->assign_to2);
            $history->comment = $request->assign_to2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        // if ($lastDocument->cro_vendor != $data->cro_vendor || !empty($request->cro_vendor_comment)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Cro Vendor ';
        //     $history->previous = $lastDocument->cro_vendor;
        //     $history->current = $data->cro_vendor;
        //     $history->comment = $request->cro_vendor_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";
        //     $history->save();
        // }

        if ($lastDocument->comments != $data->comments || !empty($request->comments_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Comments')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Comments ';
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

        if ($lastDocument->impact != $data->impact || !empty($request->impact_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Impact')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Impact ';
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

        if ($lastDocument->impact_analysis != $data->impact_analysis || !empty($request->impact_analysis_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Impact Analysis')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Impact Analysis ';
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

        if ($lastDocument->severity_rate != $data->severity_rate || !empty($request->severity_rate_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Severity Rate')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Severity Rate ';
            // $history->previous = $lastDocument->severity_rate;
            if ($lastDocument->severity_rate == 1) {
                $history->previous = "Negligible";
            } elseif ($lastDocument->severity_rate == 2) {
                $history->previous = "Moderate";
            } elseif ($lastDocument->severity_rate == 3) {
                $history->previous = "Major";
            } elseif ($lastDocument->severity_rate == 4) {
                $history->previous = "Fatal";
            } else {
                $history->previous = "Null";
            }
            // $history->current = $data->severity_rate;
            if ($request->severity_rate == 1) {
                $history->current = "Negligible";
            } elseif ($request->severity_rate == 2) {
                $history->current = "Moderate";
            } elseif ($request->severity_rate == 3) {
                $history->current = "Major";
            } else {
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
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Occurrence')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Occurrence ';
            // $history->previous = $lastDocument->occurrence;
            if ($lastDocument->occurrence == 1) {
                $history->previous = "Very Likely";
            } elseif ($lastDocument->occurrence == 2) {
                $history->previous = "Likely";
            } elseif ($lastDocument->occurrence == 3) {
                $history->previous = "Unlikely";
            } elseif ($lastDocument->occurrence == 4) {
                $history->previous = "Rare";
            } elseif ($lastDocument->occurrence == 5) {
                $history->previous = "Extremely Unlikely";
            } else {
                $history->previous = "Null";
            }
            // $history->current = $data->occurrence;
            if ($request->occurrence == 1) {
                $history->current = "Very Likely";
            } elseif ($request->occurrence == 2) {
                $history->current = "Likely";
            } elseif ($request->occurrence == 3) {
                $history->current = "Unlikely";
            } elseif ($request->occurrence == 4) {
                $history->current = "Rare";
            } else {
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
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Detection')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Detection ';
            // $history->previous = $lastDocument->detection;
            if ($lastDocument->detection == 1) {
                $history->previous = "Very Likely";
            } elseif ($lastDocument->detection == 2) {
                $history->previous = "Likely";
            } elseif ($lastDocument->detection == 3) {
                $history->previous = "Unlikely";
            } elseif ($lastDocument->detection == 4) {
                $history->previous = "Rare";
            } elseif ($lastDocument->detection == 5) {
                $history->previous = "Impossible";
            } else {
                $history->previous = "Null";
            }
            // $history->current = $data->detection;
            if ($request->detection == 1) {
                $history->current = "Very Likely";
            } elseif ($request->detection == 2) {
                $history->current = "Likely";
            } elseif ($request->detection == 3) {
                $history->current = "Unlikely";
            } elseif ($request->detection == 4) {
                $history->current = "Rare";
            } else {
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

        if ($lastDocument->analysisRPN != $data->analysisRPN || !empty($request->analysisRPN_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'RPN')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'RPN ';
            $history->previous = $lastDocument->analysisRPN;
            $history->current = $data->analysisRPN;
            $history->comment = $request->analysisRPN_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->actual_start_date != $data->actual_start_date || !empty($request->actual_start_date_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Actual Start Date')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Actual Start Date ';
            $history->previous = Helpers::getdateFormat($lastDocument->actual_start_date);
            $history->current = Helpers::getdateFormat($data->actual_start_date);
            $history->comment = $request->actual_start_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->actual_end_date != $data->actual_end_date || !empty($request->actual_end_date_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Actual End Date')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Actual End Date ';
            $history->previous = Helpers::getdateFormat($lastDocument->actual_end_date);
            $history->current = Helpers::getdateFormat($data->actual_end_date);
            $history->comment = $request->actual_end_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->action_taken != $data->action_taken || !empty($request->action_taken_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Action Taken')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Action Taken ';
            $history->previous = $lastDocument->action_taken;
            $history->current = $data->action_taken;
            $history->comment = $request->action_taken_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        // if ($lastDocument->date_response_due1 != $data->date_response_due1 || !empty($request->date_response_due1_comment)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Date Response Due1 ';
        //     $history->previous = $lastDocument->date_response_due1;
        //     $history->current = $data->date_response_due1;
        //     $history->comment = $request->date_response_due1_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";
        //     $history->save();
        // }

        // if ($lastDocument->response_date != $data->response_date || !empty($request->response_date_comment)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Response Date ';
        //     $history->previous = $lastDocument->response_date;
        //     $history->current = $data->response_date;
        //     $history->comment = $request->response_date_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";
        //     $history->save();
        // }

        if ($lastDocument->attach_files2 != $data->attach_files2 || !empty($request->attach_files2_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Attachment')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Attachment ';
            $history->previous = $lastDocument->attach_files2;
            $history->current = $data->attach_files2;
            $history->comment = $request->attach_files2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->related_url != $data->related_url || !empty($request->related_url_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Related Url')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Related Url ';
            $history->previous = $lastDocument->related_url;
            $history->current = $data->related_url;
            $history->comment = $request->related_url_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastDocument->response_summary != $data->response_summary || !empty($request->response_summary_comment)) {
            $lastDocumentAuditTrail = AuditTrialObservation::where('Observation_id', $data->id)
                ->where('activity_type', 'Response Summary')
                ->exists();
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Response Summary ';
            $history->previous = $lastDocument->response_summary;
            $history->current = $data->response_summary;
            $history->comment = $request->response_summary_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = 'Not Applicable';
            $history->change_from = $lastDocument->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }

        // if ($lastDocument->response_summary != $data->response_summary || !empty($request->response_summary_comment)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Parent ';
        //     $history->previous = $lastDocument->response_summary;
        //     $history->current = $data->response_summary;
        //     $history->comment = $request->response_summary_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";
        //     $history->save();
        // }

        // if ($lastDocument->assign_to2 != $data->assign_to2 || !empty($request->assign_to2_comment)) {
        //     $history = new AuditTrialObservation();
        //     $history->Observation_id = $id;
        //     $history->activity_type = 'Attachm Files2 ';
        //     $history->previous = $lastDocument->assign_to2;
        //     $history->current = $data->assign_to2;
        //     $history->comment = $request->assign_to2_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";
        //     $history->save();
        // }

        toastr()->success("Record is update successfully");
        return back();
    }

    public function observationshow($id)
    {
        $data = Observation::find($id);
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_id)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $grid_data = InternalAuditGrid::where('audit_id', $id)->where('type', "external_audit")->first();
        $griddata = ObservationGrid::where('observation_id', $data->id)->first();

        return view('frontend.observation.view', compact('data', 'griddata', 'grid_data'));
    }
    public function observation_send_stage(Request $request, $id)
    {


        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changestage = Observation::find($id);
            $lastDocument = Observation::find($id);
            if ($changestage->stage == 1) {
                $changestage->stage = "2";
                $changestage->status = "Pending CAPA Plan";
                $changestage->Report_Issued_By = Auth::user()->name;
                $changestage->Report_Issued_On = Carbon::now()->format('d-M-Y');
                $changestage->Report_Issued_Comment = $request->comment;


                $history = new AuditTrialObservation();
                $history->Observation_id = $id;

                $history->activity_type = 'Report Issued By, Report Issued On';
                if (is_null($lastDocument->Report_Issued_By) || $lastDocument->Report_Issued_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->Report_Issued_By . ' , ' . $lastDocument->Report_Issued_On;
                }
                $history->current = $changestage->Report_Issued_By . ' , ' . $changestage->Report_Issued_On;

                $history->comment = $request->comment;
                $history->action = 'Report Issued';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Report Issued";
                $history->change_to = 'Pending CAPA Plan';
                $history->change_from = 'Opened';
                if (is_null($lastDocument->Report_Issued_By) || $lastDocument->Report_Issued_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getAuditeeDepartmentList($changestage->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new AuditTrialObservation();
                        $history->Observation_id = $id;
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
                        $history->role_name = "Auditee";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                        $email = Helpers::getAuditeeEmail($u->user_id);
                        if (!empty($email)) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $changestage, 'site' => "OBS", 'history' => "Report Issued", 'process' => 'Observation', 'comment' => $request->comment, 'user'=> Auth::user()->name],
                                    function ($message) use ($email, $changestage) {
                                        $message->to($email)
                                        ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity: Report Issued Performed");
                                    }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    // }
                }

                $changestage->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changestage->stage == 2) {
                $changestage->stage = "3";
                $changestage->status = "Pending Approval";
                $changestage->Completed_By = Auth::user()->name;
                $changestage->completed_on = Carbon::now()->format('d-M-Y');
                $changestage->Completed_Comment = $request->comment;

                $history = new AuditTrialObservation();
                $history->Observation_id = $id;

                $history->activity_type = 'Completed By, Completed On';
                if (is_null($lastDocument->Completed_By) || $lastDocument->Completed_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->Completed_By . ' , ' . $lastDocument->Completed_on;
                }
                $history->current = $changestage->Completed_By . ' , ' . $changestage->Completed_on;

                $history->comment = $request->comment;
                $history->action = 'Complete';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Complete";
                $history->change_to = 'Pending Approval';
                $history->change_from = 'Pending CAPA Plan';
                if (is_null($lastDocument->Completed_By) || $lastDocument->Completed_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getQualityList($changestage->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new AuditTrialObservation();
                        $history->Observation_id = $id;
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
                        $history->change_from = "Pending Approval";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Quality";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                        $email = Helpers::getQualityEmail($u->user_id);
                        if (!empty($email)) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $changestage, 'site' => "OBS", 'history' => "Complete", 'process' => 'Observation', 'comment' => $request->comment, 'user'=> Auth::user()->name],
                                    function ($message) use ($email, $changestage) {
                                        $message->to($email)
                                        ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity: Complete Performed");
                                    }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    // }
                }

                $changestage->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changestage->stage == 3) {
                $changestage->stage = "4";
                $changestage->status = "CAPA Execution in Progress";
                $changestage->QA_Approved_By = Auth::user()->name;
                $changestage->QA_Approved_on = Carbon::now()->format('d-M-Y');
                $changestage->QA_Approved_Comment = $request->comment;

                $history = new AuditTrialObservation();
                $history->Observation_id = $id;
                $history->activity_type = 'QA Approved By, QA Approved On';
                if (is_null($lastDocument->QA_Approved_By) || $lastDocument->QA_Approved_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->QA_Approved_By . ' , ' . $lastDocument->QA_Approved_on;
                }
                $history->current = $changestage->QA_Approved_By . ' , ' . $changestage->QA_Approved_on;
                $history->comment = $request->comment;
                $history->action = 'QA Approved';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "QA Approved";
                $history->change_to = 'CAPA Execution in Progress';
                $history->change_from = 'Pending Approval';
                if (is_null($lastDocument->QA_Approved_By) || $lastDocument->QA_Approved_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
                //     $list = Helpers::getLeadAuditeeUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changestage->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {

                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changestage],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document sent ".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      }
                //   }
                $changestage->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changestage->stage == 4) {
                $changestage->stage = "5";
                $changestage->status = "Pending Final Approval";
                $changestage->All_CAPA_Closed_By = Auth::user()->name;
                $changestage->All_CAPA_Closed_On = Carbon::now()->format('d-M-Y');
                $changestage->All_CAPA_Closed_Comment = $request->comment;

                $history = new AuditTrialObservation();
                $history->Observation_id = $id;

                $history->activity_type = 'All CAPA Closed By, All CAPA Closed On';

                if (is_null($lastDocument->All_CAPA_Closed_By) || $lastDocument->All_CAPA_Closed_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->All_CAPA_Closed_By . ' , ' . $lastDocument->All_CAPA_Closed_On;
                }
                $history->current = $changestage->All_CAPA_Closed_By . ' , ' . $changestage->All_CAPA_Closed_On;

                $history->comment = $request->comment;
                $history->action = 'All CAPA Closed';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "All CAPA Closed";
                $history->change_to = 'Pending Final Approval';
                $history->change_from = 'CAPA Execution in Progress';

                if (is_null($lastDocument->All_CAPA_Closed_By) || $lastDocument->All_CAPA_Closed_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getQualityList($changestage->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new AuditTrialObservation();
                        $history->Observation_id = $id;
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
                        $history->change_from = "Pending Final Approval";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Quality";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                        $email = Helpers::getQualityEmail($u->user_id);
                        if (!empty($email)) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $changestage, 'site' => "OBS", 'history' => "All CAPA Closed", 'process' => 'Observation', 'comment' => $request->comment, 'user'=> Auth::user()->name],
                                    function ($message) use ($email, $changestage) {
                                        $message->to($email)
                                        ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity: All CAPA Closed Performed");
                                    }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    // }
                }

                $changestage->update();
                toastr()->success('Document Sent');
                return back();
            }

            if ($changestage->stage == 5) {
                $changestage->stage = "6";
                $changestage->status = "Closed - Done";
                $changestage->Final_Approval_By = Auth::user()->name;
                $changestage->Final_Approval_on = Carbon::now()->format('d-M-Y');
                $changestage->Final_Approval_Comment = $request->comment;

                $history = new AuditTrialObservation();
                $history->Observation_id = $id;

                $history->activity_type = 'Final Approval By, Final Approval On';
                if (is_null($lastDocument->Final_Approval_By) || $lastDocument->Final_Approval_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->Final_Approval_By . ' , ' . $lastDocument->Final_Approval_on;
                }
                $history->current = $changestage->Final_Approval_By . ' , ' . $changestage->Final_Approval_on;

                $history->comment = $request->comment;
                $history->action = 'Final Approval';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Final Approval";
                $history->change_to = 'Closed - Done';
                $history->change_from = 'Pending Final Approval';
                if (is_null($lastDocument->Final_Approval_By) || $lastDocument->Final_Approval_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getAuditeesList($changestage->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new AuditTrialObservation();
                        $history->Observation_id = $id;
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
                        $history->role_name = "Auditee";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                        $email = Helpers::getAuditeesEmail($u->user_id);
                        if (!empty($email)) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $changestage, 'site' => "OBS", 'history' => "Final Approval", 'process' => 'Observation', 'comment' => $request->comment, 'user'=> Auth::user()->name],
                                    function ($message) use ($email, $changestage) {
                                        $message->to($email)
                                        ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity: Final Approval Performed");
                                    }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    // }
                }

                $list = Helpers::getQualityList($changestage->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new AuditTrialObservation();
                        $history->Observation_id = $id;
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
                        $history->role_name = "Quality";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                        $email = Helpers::getQualityEmail($u->user_id);
                        if (!empty($email)) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $changestage, 'site' => "OBS", 'history' => "Final Approval", 'process' => 'Observation', 'comment' => $request->comment, 'user'=> Auth::user()->name],
                                    function ($message) use ($email, $changestage) {
                                        $message->to($email)
                                        ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity: Final Approval Performed");
                                    }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    // }
                }


                $list = Helpers::getAuditorsList($changestage->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new AuditTrialObservation();
                        $history->Observation_id = $id;
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
                        $history->role_name = "Auditors";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                        $email = Helpers::getAuditorsEmail($u->user_id);
                        if (!empty($email)) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $changestage, 'site' => "OBS", 'history' => "Final Approval", 'process' => 'Observation', 'comment' => $request->comment, 'user'=> Auth::user()->name],
                                    function ($message) use ($email, $changestage) {
                                        $message->to($email)
                                        ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity: Final Approval Performed");
                                    }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    // }
                }
                $changestage->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function ObservationCancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = Observation::find($id);
            $lastDocument = Observation::find($id);


            if ($changeControl->stage == 1) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed - Cancelled";
                $changeControl->Cancelled_By = Auth::user()->name;
                $changeControl->Cancelled_On = Carbon::now()->format('d-M-Y');
                $changeControl->Cancelled_Comment = $request->comment;

                $history = new AuditTrialObservation();
                $history->Observation_id = $id;

                $history->activity_type = 'Cancelled By, Cancelled On';
                if (is_null($lastDocument->Cancelled_By) || $lastDocument->Cancelled_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->Cancelled_By . ' , ' . $lastDocument->Cancelled_On;
                }
                $history->current = $changeControl->Cancelled_By . ' , ' . $changeControl->Cancelled_On;
                $history->comment = $request->comment;
                $history->action = 'Cancel';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Cancel";
                $history->change_to = 'Closed - Cancelled';
                $history->change_from = 'Opened';

                if (is_null($lastDocument->Cancelled_By) || $lastDocument->Cancelled_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getQualityList($changestage->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new AuditTrialObservation();
                        $history->Observation_id = $id;
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
                        $history->change_from = "Closed - Cancelled";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Quality";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                        $email = Helpers::getQualityEmail($u->user_id);
                        if (!empty($email)) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $changeControl, 'site' => "OBS", 'history' => "Cancel", 'process' => 'Observation', 'comment' => $request->comment, 'user'=> Auth::user()->name],
                                    function ($message) use ($email, $changeControl) {
                                        $message->to($email)
                                        ->subject("QMS Notification: Observation, Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed");
                                    }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    // }
                }

                $list = Helpers::getAuditeesList($changestage->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new AuditTrialObservation();
                        $history->Observation_id = $id;
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
                        $history->change_from = "Closed - Cancelled";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Auditee";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                        $email = Helpers::getAuditeesEmail($u->user_id);
                        if (!empty($email)) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $changeControl, 'site' => "OBS", 'history' => "Cancel", 'process' => 'Observation', 'comment' => $request->comment, 'user'=> Auth::user()->name],
                                    function ($message) use ($email, $changeControl) {
                                        $message->to($email)
                                        ->subject("QMS Notification: Observation, Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed");
                                    }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    // }
                }

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
            $changeControl = Observation::find($id);
            $lastDocument = Observation::find($id);

            if ($changeControl->stage == 1) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed - Cancelled";
                $changeControl->Cancelled_By = Auth::user()->name;
                $changeControl->Cancelled_On = Carbon::now()->format('d-M-Y');
                $changeControl->Cancelled_Comment = $request->comment;

                $history = new AuditTrialObservation();
                $history->Observation_id = $id;
                $history->activity_type = 'Final Approval By, Final Approval On';
                if (is_null($lastDocument->Cancelled_By) || $lastDocument->Cancelled_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->Cancelled_By . ' , ' . $lastDocument->Cancelled_On;
                }
                $history->current = $changeControl->Cancelled_By . ' , ' . $changeControl->Cancelled_On;
                $history->comment = $request->comment;
                $history->action = 'Cancel';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Cancel";
                $history->change_to = 'Closed - Cancelled';
                $history->change_from = 'Opened';
                if (is_null($lastDocument->Cancelled_By) || $lastDocument->Cancelled_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                //     $list = Helpers::getQAUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {

                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document sent ".Auth::user()->name);
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
                $changeControl->status = "Pending CAPA Plan";
                $changeControl->Reject_CAPA_Plan_By = Auth::user()->name;
                $changeControl->Reject_CAPA_Plan_On = Carbon::now()->format('d-M-Y');
                $changeControl->Reject_CAPA_Plan_Comment = $request->comment;

                $history = new AuditTrialObservation();
                $history->Observation_id = $id;

                $history->activity_type = 'Final Approval By, Final Approval On';
                if (is_null($lastDocument->Reject_CAPA_Plan_By) || $lastDocument->Reject_CAPA_Plan_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->Reject_CAPA_Plan_By . ' , ' . $lastDocument->Reject_CAPA_Plan_On;
                }
                $history->current = $changeControl->Reject_CAPA_Plan_By . ' , ' . $changeControl->Reject_CAPA_Plan_On;

                $history->comment = $request->comment;
                $history->action = 'Reject CAPA Plan';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Reject CAPA Plan";
                $history->change_to = 'Pending CAPA Plan';
                $history->change_from = 'Pending Approval';
                if (is_null($lastDocument->Reject_CAPA_Plan_By) || $lastDocument->Reject_CAPA_Plan_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $changeControl->update();

                $list = Helpers::getAuditeeDepartmentList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new AuditTrialObservation();
                        $history->Observation_id = $id;
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
                        $history->role_name = "Auditee";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                        $email = Helpers::getAuditeeEmail($u->user_id);
                        if (!empty($email)) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $changeControl, 'site' => "OBS", 'history' => "Reject CAPA Plan", 'process' => 'Observation', 'comment' => $request->comment, 'user'=> Auth::user()->name],
                                    function ($message) use ($email, $changeControl) {
                                        $message->to($email)
                                        ->subject("QMS Notification: Observation, Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Reject CAPA Plan");
                                    }
                                );
                            } catch (\Exception $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    // }
                }

                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 5) {
                $changeControl->stage = "2";
                $changeControl->status = "Pending CAPA Plan";
                $changeControl->Reject_CAPA_Plan_By1 = Auth::user()->name;
                $changeControl->Reject_CAPA_Plan_On1 = Carbon::now()->format('d-M-Y');
                $changeControl->Reject_CAPA_Plan_Comment1 = $request->comment;

                $history = new AuditTrialObservation();
                $history->Observation_id = $id;

                $history->activity_type = 'Final Approval By, Final Approval On';
                if (is_null($lastDocument->Reject_CAPA_Plan_By1) || $lastDocument->Reject_CAPA_Plan_By1 === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->Reject_CAPA_Plan_By1 . ' , ' . $lastDocument->Reject_CAPA_Plan_On1;
                }
                $history->current = $changeControl->Reject_CAPA_Plan_By1 . ' , ' . $changeControl->Reject_CAPA_Plan_On1;

                $history->comment = $request->comment;
                $history->action = 'Reject CAPA Plan';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Reject CAPA Plan";
                $history->change_to = 'Pending CAPA Plan';
                $history->change_from = 'Pending Final Approval';

                if (is_null($lastDocument->Reject_CAPA_Plan_By1) || $lastDocument->Reject_CAPA_Plan_By1 === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getAuditeeDepartmentList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new AuditTrialObservation();
                        $history->Observation_id = $id;
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
                        $history->role_name = "Auditee";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $changeControl->division_id) {
                        $email = Helpers::getAuditeeEmail($u->user_id);
                        if ($email !== null) {

                            Mail::send(
                                'mail.view-mail',
                                ['data' => $changeControl, 'site' => "OBS", 'history' => "QA Approval Without CAPA", 'process' => 'Observation', 'comment' => $request->comment, 'user'=> Auth::user()->name],
                                function ($message) use ($email, $changeControl) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Observation, Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Approval Without CAPA Performed");
                                }
                            );
                        }
                    //}
                }
                //     $list = Helpers::getLeadAuditeeUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {

                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document sent ".Auth::user()->name);
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

    public function boostStage(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = Observation::find($id);
            $lastDocument = Observation::find($id);



            if ($changeControl->stage == 3) {
                $changeControl->stage = "6";
                $changeControl->status = "Closed - Done";
                $changeControl->QA_Approval_Without_CAPA_By = Auth::user()->name;
                $changeControl->QA_Approval_Without_CAPA_On = Carbon::now()->format('d-M-Y');
                $changeControl->QA_Approval_Without_CAPA_Comment = $request->comment;

                $history = new AuditTrialObservation();
                $history->Observation_id = $id;
                $history->activity_type = 'Final Approval By, Final Approval On';
                if (is_null($lastDocument->QA_Approval_Without_CAPA_By) || $lastDocument->QA_Approval_Without_CAPA_By === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->QA_Approval_Without_CAPA_By . ' , ' . $lastDocument->QA_Approval_Without_CAPA_On;
                }
                $history->current = $changeControl->QA_Approval_Without_CAPA_By . ' , ' . $changeControl->QA_Approval_Without_CAPA_On;
                $history->comment = $request->comment;
                $history->action = 'QA Approval Without CAPA';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "QA Approval Without CAPA";
                $history->change_to = 'Closed - Done';
                $history->change_from = 'Pending Approval';
                if (is_null($lastDocument->QA_Approval_Without_CAPA_By) || $lastDocument->QA_Approval_Without_CAPA_By === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getAuditeesList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new AuditTrialObservation();
                        $history->Observation_id = $id;
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
                        $history->role_name = "Auditee";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $changeControl->division_id) {
                        $email = Helpers::getAuditeesEmail($u->user_id);
                        if ($email !== null) {

                            Mail::send(
                                'mail.view-mail',
                                ['data' => $changeControl, 'site' => "OBS", 'history' => "QA Approval Without CAPA", 'process' => 'Observation', 'comment' => $request->comment, 'user'=> Auth::user()->name],
                                function ($message) use ($email, $changeControl) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Observation, Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Approval Without CAPA Performed");
                                }
                            );
                        }
                    //}
                }

                $list = Helpers::getAuditorsList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new AuditTrialObservation();
                        $history->Observation_id = $id;
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
                        $history->role_name = "Aditors";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                    //if ($u->q_m_s_divisions_id == $changeControl->division_id) {
                        $email = Helpers::getAuditorsEmail($u->user_id);
                        if ($email !== null) {

                            Mail::send(
                                'mail.view-mail',
                                ['data' => $changeControl, 'site' => "OBS", 'history' => "QA Approval Without CAPA", 'process' => 'Observation', 'comment' => $request->comment, 'user'=> Auth::user()->name],
                                function ($message) use ($email, $changeControl) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Observation, Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Approval Without CAPA Performed");
                                }
                            );
                        }
                    //}
                }
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function observation_child(Request $request, $id)
    {
        $cft = [];
        $parent_id = $id;
        $parent_type = "Capa";
        $old_record = Capa::select('id', 'division_id', 'record')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $parent_division_id = Observation::where('id', $id)->value('division_id');
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $changeControl = OpenStage::find(1);
        if (!empty($changeControl->cft)) $cft = explode(',', $changeControl->cft);
        return view('frontend.forms.capa', compact('record_number', 'parent_division_id', 'due_date', 'parent_id', 'parent_type', 'old_record', 'cft'));
    }


    public function ObservationAuditTrialShow($id)
    {
        $audit = AuditTrialObservation::where('Observation_id', $id)->orderByDESC('id')->paginate(5);
        $today = Carbon::now()->format('d-m-y');
        $document = Observation::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');

        return view('frontend.observation.audit-trial-inner', compact('audit', 'document', 'today'));
    }

    public function ObservationAuditTrialDetails($id)
    {


        $doc = Observation::find($id);
        $audit = AuditTrialObservation::where('Observation_id', $id)->orderByDesc('id')->get();
        $doc->originator = User::where('id', $doc->initiator_id)->value('name');
        $data = AuditTrialObservation::where('Observation_id', $doc->id)->orderByDesc('id')->get();
        $pdf = App::make('dompdf.wrapper');
        $time = Carbon::now();
        $pdf = PDF::loadview('frontend.observation.audit-trial', compact('data', 'doc', 'audit'))
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
            25,
            [0, 0, 0],
            2,
            6,
            -20
        );

        return $pdf->stream('Observation-AuditTrial' . $id . '.pdf');
    }


    public function ObservationSingleReportshow($id)
    {
        $data = Observation::find($id);
        $griddata = ObservationGrid::where('observation_id', $data->id)->first();

        if (! empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();

            // $Observation_id = $data->id;
            // $griddata = ObservationGrid::where(['_id' => $Observation_id, 'identifier' => 'action-plan-grid'])->firstOrCreate();
            $pdf = PDF::loadview('frontend.observation.observation_single_report', compact('data', 'griddata'))
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

            return $pdf->stream('Observation-SingleReport' . $id . '.pdf');
        }
    }
}
