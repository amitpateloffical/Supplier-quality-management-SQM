<?php

namespace App\Http\Controllers\rcms;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Models\CC;
use App\Models\CCStageHistory;
use App\Models\EffectivenessCheck;
use App\Models\EffectivenessCheckAuditTrail;
use App\Models\RecordNumber;
use App\Models\Capa;
use App\Models\User;
use App\Models\RoleGroup;
use Carbon\Carbon;
use PDF;
use Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class EffectivenessCheckController extends Controller
{

    public function effectiveness_check()
    {
        $old_record = EffectivenessCheck::select('id', 'division_id', 'record')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');
        return view('frontend.forms.effectiveness-check', compact('due_date', 'record_number', 'old_record'));
    }

    public function index()
    {
        $table = [];

        $datas = EffectivenessCheck::get();
        $datas1 = EffectivenessCheck::get();
        $datas2 = EffectivenessCheck::get();

        foreach ($datas as $data) {
            array_push($table, [
                "id" => $data->name ? $data->name  : "-",
                "type" => $data->name ? $data->name  : "-",
                "name" => $data->name ? $data->name  : "-",
                "address" => "",
                "role" => "",
                "phone" => "",
            ]);
        }
    }

    public function create() {}

    public function store(Request $request)
    {

        // dd($request->all());

        $openState = new EffectivenessCheck();
        // $openState->form_type = "effectiveness-check";
        $openState->is_parent = "No";
        $openState->parent_id = $request->parent_id;
        $openState->parent_type = $request->parent_type;
        // dd($openState->parent_id);

        $openState->division_id = $request->division_id;
        $openState->intiation_date = $request->intiation_date;
        $openState->initiator_id = Auth::user()->id;
        $openState->record = DB::table('record_numbers')->value('counter') + 1;
        $openState->assign_to = $request->assign_to;
        $openState->due_date = $request->due_date;
        $openState->short_description = $request->short_description;
        $openState->Effectiveness_check_Plan = $request->Effectiveness_check_Plan;
        $openState->Effectiveness_Summary = $request->Effectiveness_Summary;
        $openState->effect_summary = $request->effect_summary;
        $openState->Quality_Reviewer = $request->Quality_Reviewer;
        $openState->Effectiveness_Results = $request->Effectiveness_Results;
        $openState->Addendum_Comments = $request->Addendum_Comments;
        $openState->refer_record = implode(',', $request->refer_record);


        // $openState->Cancellation_Category = $request->Cancellation_Category;
        //$openState->Effectiveness_check_Attachment = $request->Effectiveness_check_Attachment;

        if (!empty($request->Effectiveness_check_Attachment)) {
            $files = [];
            if ($request->hasfile('Effectiveness_check_Attachment')) {
                foreach ($request->file('Effectiveness_check_Attachment') as $file) {
                    $name = $request->name . 'Effectiveness_check_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $openState->Effectiveness_check_Attachment = json_encode($files);
        }

        //  $openState->Addendum_Attachment = $request->Addendum_Attachment;
        if (!empty($request->Addendum_Attachment)) {
            $files = [];
            if ($request->hasfile('Addendum_Attachment')) {
                foreach ($request->file('Addendum_Attachment') as $file) {
                    $name = $request->name . 'Addendum_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $openState->Addendum_Attachment = json_encode($files);
        }
        // $openState->Attachment = $request->Attachment;
        if (!empty($request->Attachment)) {
            $files = [];
            if ($request->hasfile('Attachment')) {
                foreach ($request->file('Attachment') as $file) {
                    $name = $request->name . 'Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $openState->Attachment = json_encode($files);
        }
        if (!empty($request->Attachments)) {
            $files = [];
            if ($request->hasfile('Attachments')) {
                foreach ($request->file('Attachments') as $file) {
                    $name = $request->name . 'Attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $openState->Attachments = json_encode($files);
        }
        // $openState->refer_record = $request->refer_record;
        //    if (!empty($request->refer_record)) {
        //         $files = [];
        //         if ($request->hasfile('refer_record')) {
        //             foreach ($request->file('refer_record') as $file) {
        //                 $name = $request->name . 'refer_record' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //                 $file->move('upload/', $name);
        //                 $files[] = $name;
        //             }
        //         }

        //         $openState->refer_record = json_encode($files);
        //     }
        $openState->Comments = $request->Comments;
        $openState->status = "Opened";
        $openState->stage = 1;
        $openState->save();
        // dd($openState->id);


        $counter = DB::table('record_numbers')->value('counter');
        $recordNumber = str_pad($counter, 5, '0', STR_PAD_LEFT);
        $newCounter = $counter + 1;
        DB::table('record_numbers')->update(['counter' => $newCounter]);

        if (!empty($openState->record)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Record Number';
            $history->previous = "Null";
            $history->current = Helpers::getDivisionName($openState->division_id) . '/EC/' . Helpers::year($openState->created_at) . '/' . str_pad($openState->record, 4, '0', STR_PAD_LEFT);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save(); // Save the new instance
        }


        if (!empty($openState->division_id)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Division Code';
            $history->previous = "Null";
            $history->current = Helpers::getDivisionName($openState->division_id);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save(); // Save the new instance
        }

        if (!empty($openState->initiator_id)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Initiator';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($openState->initiator_id);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save(); // Save the new instance
        }



        if (!empty($openState->intiation_date)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Date of Initiation';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($openState->intiation_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save(); // Save the new instance
        }

        if (! empty($openState->due_date)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Due Date';
            $history->previous = "Null";
            $history->current = $openState->due_date;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (! empty($openState->assign_to)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Assigned To';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($openState->assign_to);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }


        if (!empty($openState->short_description)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Short Description';
            $history->previous = "Null";
            $history->current = $openState->short_description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }


        if (!empty($openState->Effectiveness_check_Plan)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Effectiveness Check Plan';
            $history->previous = "Null";
            $history->current = $openState->Effectiveness_check_Plan;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save(); // Save the new instance
        }

        if (!empty($openState->Attachments)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Attachments';
            $history->previous = "Null";
            $history->current = $openState->Attachments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save(); // Save the new instance
        }


        if (!empty($openState->effect_summary)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Effectiveness Summary';
            $history->previous = "Null";
            $history->current = $openState->effect_summary;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($openState->effect_summary)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Effect Summary';
            $history->previous = "Null";
            $history->current = $openState->effect_summary;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }


        if (!empty($openState->Quality_Reviewer)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Quality Reviewer';
            $history->previous = "Null";
            $history->current = $openState->Quality_Reviewer;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }


        if (!empty($openState->Effectiveness_Results)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Effectiveness Results';
            $history->previous = "Null";
            $history->current = $openState->Effectiveness_Results;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($openState->Addendum_Comments)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Addendum Comments';
            $history->previous = "Null";
            $history->current = $openState->Addendum_Comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }


        if (!empty($openState->Effectiveness_check_Attachment)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Effectiveness Check Attachments';
            $history->previous = "Null";
            $history->current = $openState->Effectiveness_check_Attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($openState->Comments)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Comments';
            $history->previous = "Null";
            $history->current = $openState->Comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($openState->Addendum_Attachment)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Addendum Attachments';
            $history->previous = "Null";
            $history->current = $openState->Addendum_Attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($openState->refer_record)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Reference Records';
            $history->previous = "Null";
            $history->current = $openState->refer_record;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($openState->Attachment)) {
            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Reference Attachments';
            $history->previous = "Null";
            $history->current = $openState->Attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_from = "Initiation";
            $history->change_to = "Opened";
            $history->action_name = "Create";
            $history->save();
        }

        toastr()->success('Record created succesfully');
        return redirect('rcms/qms-dashboard');
    }

    public function show($id)
    {
        $data = EffectivenessCheck::find($id);
        $old_record = EffectivenessCheck::select('id', 'division_id', 'record')->get();
        return view('frontend.effectivenessCheck.view', compact('data', 'old_record'));
    }

    public function edit($id) {}

    public function update(Request $request, $id)
    {
        $lastopenState = EffectivenessCheck::find($id);
        $openState =  EffectivenessCheck::find($id);
        $openState->assign_to = $request->assign_to;
        $openState->due_date = $request->due_date;
        $openState->short_description = $request->short_description;
        $openState->Effectiveness_check_Plan = $request->Effectiveness_check_Plan;
        $openState->Quality_Reviewer = $request->Quality_Reviewer;
        $openState->Effectiveness_Summary = $request->Effectiveness_Summary;
        $openState->effect_summary = $request->effect_summary;
        $openState->Effectiveness_Results = $request->Effectiveness_Results;
        $openState->Addendum_Comments = $request->Addendum_Comments;
        $openState->refer_record = implode(',', $request->refer_record);

        //   $openState->Cancellation_Category = $request->Cancellation_Category;
        //$openState->Effectiveness_check_Attachment = $request->Effectiveness_check_Attachment;

        // if (!empty($request->Effectiveness_check_Attachment)) {
        //     $files = [];
        //     if ($request->hasfile('Effectiveness_check_Attachment')) {
        //         foreach ($request->file('Effectiveness_check_Attachment') as $file) {
        //             $name = $request->name . 'Effectiveness_check_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }

        //     $openState->Effectiveness_check_Attachment = json_encode($files);
        // }

        $files = is_array($request->existing_Effectiveness_check_Attachment) ? $request->existing_Effectiveness_check_Attachment : null;

        if (!empty($request->Effectiveness_check_Attachment)) {
            if ($openState->Effectiveness_check_Attachment) {
                $existingFiles = json_decode($openState->Effectiveness_check_Attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('Effectiveness_check_Attachment')) {
                foreach ($request->file('Effectiveness_check_Attachment') as $file) {
                    $name = $request->name . 'Effectiveness_check_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        // If no files are attached, set to null
        $openState->Effectiveness_check_Attachment = !empty($files) ? json_encode(array_values($files)) : null;



        // $openState->Addendum_Attachment = $request->Addendum_Attachment;
        // if (!empty($request->Addendum_Attachment)) {
        //     $files = [];
        //     if ($request->hasfile('Addendum_Attachment')) {
        //         foreach ($request->file('Addendum_Attachment') as $file) {
        //             $name = $request->name . 'Addendum_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }

        //     $openState->Addendum_Attachment = json_encode($files);
        // }

        $files = is_array($request->existing_Addendum_Attachment) ? $request->existing_Addendum_Attachment : null;

        if (!empty($request->Addendum_Attachment)) {
            if ($openState->Addendum_Attachment) {
                $existingFiles = json_decode($openState->Addendum_Attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('Addendum_Attachment')) {
                foreach ($request->file('Addendum_Attachment') as $file) {
                    $name = $request->name . 'Addendum_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        // If no files are attached, set to null
        $openState->Addendum_Attachment = !empty($files) ? json_encode(array_values($files)) : null;

        //   $openState->Attachment = $request->Attachment;
        // if (!empty($request->Attachment)) {
        //     $files = [];
        //     if ($request->hasfile('Attachment')) {
        //         foreach ($request->file('Attachment') as $file) {
        //             $name = $request->name . 'Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }

        //     $openState->Attachment = json_encode($files);
        // }

        $files = is_array($request->existing_Reference_Attachment) ? $request->existing_Reference_Attachment : null;

        if (!empty($request->Attachment)) {
            if ($openState->Attachment) {
                $existingFiles = json_decode($openState->Attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('Attachment')) {
                foreach ($request->file('Attachment') as $file) {
                    $name = $request->name . 'Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        // If no files are attached, set to null
        $openState->Attachment = !empty($files) ? json_encode(array_values($files)) : null;
        // if (!empty($request->Attachments)) {
        //     $files = [];
        //     if ($request->hasfile('Attachments')) {
        //         foreach ($request->file('Attachments') as $file) {
        //             $name = $request->name . 'Attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }

        //     $openState->Attachments = json_encode($files);
        // }

        $files = is_array($request->existing_Attachments) ? $request->existing_Attachments : null;

        if (!empty($request->Attachments)) {
            if ($openState->Attachments) {
                $existingFiles = json_decode($openState->Attachments, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('Attachments')) {
                foreach ($request->file('Attachments') as $file) {
                    $name = $request->name . 'Attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        // If no files are attached, set to null
        $openState->Attachments = !empty($files) ? json_encode(array_values($files)) : null;

        // if (!empty($request->refer_record)) {
        //     $files = [];
        //     if ($request->hasfile('refer_record')) {
        //         foreach ($request->file('refer_record') as $file) {
        //             $name = $request->name . 'refer_record' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }

        // $openState->refer_record = json_encode($files);
        // }
        // $files = is_array($request->existing_refer_record) ? $request->existing_refer_record : null;

        // if (!empty($request->refer_record)) {
        //     if ($openState->refer_record) {
        //         $existingFiles = json_decode($openState->refer_record, true); // Convert to associative array
        //         if (is_array($existingFiles)) {
        //             $files = array_values($existingFiles);
        //         }
        //     }

        //     if ($request->hasfile('refer_record')) {
        //         foreach ($request->file('refer_record') as $file) {
        //             $name = $request->name . 'refer_record' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        // }
        // // If no files are attached, set to null
        // $openState->refer_record = !empty($files) ? json_encode(array_values($files)) : null;

        // $openState->refer_record = $request->refer_record;
        $openState->Comments = $request->Comments;
        $openState->update();




        if ($lastopenState->assign_to != $openState->assign_to || !empty($request->assign_to_comment)) {

            // $previousAssignedUser = User::find($lastopenState->assign_to)->name ?? 'Null';
            // $currentAssignedUser = User::find($openState->assign_to)->name ?? 'Null';

            $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
                ->where('activity_type', 'Assigned To')
                ->exists();

            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Assigned To';
            $history->previous = Helpers::getInitiatorName($lastopenState->assign_to);
            $history->current = Helpers::getInitiatorName($openState->assign_to);
            $history->comment = $request->assign_to_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDataAudittrail ? 'Update' : 'New';
            $history->update();
            $history->save();
        }




        // if ($lastopenState->short_description != $openState->short_description || !empty($request->short_description_comment)) {

        //     $previousAssignedUser = User::find($lastopenState->short_description)->name ?? 'Null';
        //     $currentAssignedUser = User::find($openState->short_description)->name ?? 'Null';

        //     $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
        //         ->where('activity_type', '  Short Description ')
        //         ->exists();

        //         $history = new EffectivenessCheckAuditTrail();
        //         $history->effectiveness_check_id = $openState->id;
        //         $history->activity_type = 'Short Description';
        //         $history->previous = $previousAssignedUser;
        //         $history->current = $openState->short_description;;
        //         $history->comment = $request->short_description_comment;
        //         $history->user_id = Auth::user()->id;
        //         $history->user_name = Auth::user()->name;
        //         $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //         $history->origin_state = $lastopenState->status;
        //         $history->change_to = "Not Applicable";
        //         $history->change_from = $lastopenState->status;
        //         $history->action_name = $lastDataAudittrail ? 'Update' : 'New';
        //         $history->update();
        //         $history->save();
        // }



        if ($lastopenState->short_description != $openState->short_description || !empty($request->short_description_comment)) {
            $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
                ->where('activity_type', 'Short Description')
                ->exists();

            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $openState->id;
            $history->activity_type = 'Short Description';
            $history->previous = $lastopenState->short_description;
            $history->current = $openState->short_description;
            $history->comment = $request->short_description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDataAudittrail ? 'Update' : 'New';


            $history->save();
        }

        if ($lastopenState->Effectiveness_check_Plan != $openState->Effectiveness_check_Plan || !empty($request->Effectiveness_check_Plan_comment)) {
            $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
                ->where('activity_type', 'Effectiveness Check Plan')
                ->exists();


            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $id;
            $history->activity_type = 'Effectiveness Check Plan';
            $history->previous = $lastopenState->Effectiveness_check_Plan;
            $history->current = $openState->Effectiveness_check_Plan;
            $history->comment = $request->Effectiveness_check_Plan_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDataAudittrail ? 'Update' : 'New';
            $history->save();
        }



        if ($lastopenState->Attachments != $openState->Attachments || !empty($request->Attachments_comment)) {
            $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
                ->where('activity_type', 'Attachments')
                ->exists();


            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $id;
            $history->activity_type = 'Attachments';
            $history->previous = $lastopenState->Attachments;
            $history->current = $openState->Attachments;
            $history->comment = $request->Attachments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDataAudittrail ? 'Update' : 'New';
            $history->save();
        }



        if ($lastopenState->effect_summary != $openState->effect_summary || !empty($request->effect_summary_comment)) {
            $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
                ->where('activity_type', 'Effectiveness Summary')
                ->exists();


            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $id;
            $history->activity_type = 'Effectiveness Summary';
            $history->previous = $lastopenState->effect_summary;
            $history->current = $openState->effect_summary;
            $history->comment = $request->effect_summary_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDataAudittrail ? 'Update' : 'New';
            $history->save();
        }


        if ($lastopenState->Effectiveness_Results != $openState->Effectiveness_Results || !empty($request->Effectiveness_Results_comment)) {
            $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
                ->where('activity_type', 'Effectiveness Results')
                ->exists();


            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $id;
            $history->activity_type = 'Effectiveness Results';
            $history->previous = $lastopenState->Effectiveness_Results;
            $history->current = $openState->Effectiveness_Results;
            $history->comment = $request->Effectiveness_Results_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDataAudittrail ? 'Update' : 'New';
            $history->save();
        }





        // $previousAttachments = $lastDocument->Attachments;
        // $areAttachmentsSame = $previousAttachments == $openState->Attachments;

        // if ($areAttachmentsSame != true) {
        //     $history = new EffectivenessCheckAuditTrail();
        //     $history->effectiveness_check_id = $id;
        //     $history->activity_type = '  Effectiveness Check Attachment';
        //     $history->previous = $previousAttachments;
        //     $history->current = $openState->Attachments;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastopenState->status;
        //     $history->change_to = "Not Applicable";
        //     $history->change_from = $lastopenState->status;
        //     if ($previousAttachments) {
        //         $history->action_name = "Update";
        //     } else {
        //         $history->action_name = "New";
        //     }
        //     $history->save();
        // }





        // if ($lastopenState->Attachment != $openState->Attachment || !empty($request->Attachment_comment)) {

        //     $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
        //         ->where('activity_type', '  Attachment')
        //         ->exists();


        //     $history = new EffectivenessCheckAuditTrail();
        //     $history->effectiveness_check_id = $id;
        //     $history->activity_type = '  Attachment';
        //     $history->previous = $lastopenState->Attachment;
        //     $history->current = $openState->Attachment;
        //     $history->comment = $request->Attachment_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastopenState->status;
        //     $history->change_to = "Not Applicable";
        //     $history->change_from = $lastopenState->status;
        //     $history->action_name = $lastDataAudittrail ? 'Update' : 'New';
        //     $history->save();
        // }
        if ($lastopenState->Effectiveness_check_Attachment != $openState->Effectiveness_check_Attachment || !empty($request->Effectiveness_check_Attachment)) {

            $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
                ->where('activity_type', 'Effectiveness Check Attachments')
                ->exists();


            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $id;
            $history->activity_type = 'Effectiveness Check Attachments';
            $history->previous = $lastopenState->Effectiveness_check_Attachment;
            $history->current = $openState->Effectiveness_check_Attachment;
            $history->comment = $request->Effectiveness_check_Attachment_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDataAudittrail ? 'Update' : 'New';
            $history->save();
        }


        if ($lastopenState->Addendum_Comments != $openState->Addendum_Comments || !empty($request->Addendum_Comments_comment)) {
            $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
                ->where('activity_type', 'Addendum Comments')
                ->exists();


            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $id;
            $history->activity_type = 'Addendum Comments';
            $history->previous = $lastopenState->Addendum_Comments;
            $history->current = $openState->Addendum_Comments;
            $history->comment = $request->Addendum_Comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDataAudittrail ? 'Update' : 'New';
            $history->save();
        }

        if ($lastopenState->Addendum_Attachment != $openState->Addendum_Attachment || !empty($request->Addendum_Attachment_comment)) {
            $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
                ->where('activity_type', 'Addendum Attachments')
                ->exists();


            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $id;
            $history->activity_type = 'Addendum Attachments';
            $history->previous = $lastopenState->Addendum_Attachment;
            $history->current = $openState->Addendum_Attachment;
            $history->comment = $request->Addendum_Attachment_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDataAudittrail ? 'Update' : 'New';


            $history->save();
        }

        if ($lastopenState->Comments != $openState->Comments || !empty($request->Comments_comment)) {

            $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
                ->where('activity_type', 'Comments')
                ->exists();


            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $id;
            $history->activity_type = 'Comments';
            $history->previous = $lastopenState->Comments;
            $history->current = $openState->Comments;
            $history->comment = $request->Comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDataAudittrail ? 'Update' : 'New';
            $history->save();
        }



        if ($lastopenState->Attachment != $openState->Attachment || !empty($request->Attachment_comment)) {

            $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
                ->where('activity_type', 'Reference Attachments')
                ->exists();


            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $id;
            $history->activity_type = 'Reference Attachments';
            $history->previous = $lastopenState->Attachment;
            $history->current = $openState->Attachment;
            $history->comment = $request->Attachment_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDataAudittrail ? 'Update' : 'New';
            $history->save();
        }


        if ($lastopenState->refer_record != $openState->refer_record || !empty($request->refer_record_comment)) {
            $lastDataAudittrail = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $openState->id)
                ->where('activity_type', 'Reference Records')
                ->exists();


            $history = new EffectivenessCheckAuditTrail();
            $history->effectiveness_check_id = $id;
            $history->activity_type = 'Reference Records';
            $history->previous = $lastopenState->refer_record;
            $history->current = $openState->refer_record;
            $history->comment = $request->refer_record_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDataAudittrail ? 'Update' : 'New';
            $history->save();
        }
        toastr()->success('Record Updated succesfully');
        return back();
    }

    public function destroy($id) {}


    public function stageChange(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $effectiveness = EffectivenessCheck::find($id);
            $lastopenState = EffectivenessCheck::find($id);
            if ($effectiveness->stage == 1) {
                // $rules = [
                //     'Addendum_Comments' => 'required|max:255',

                // ];
                // $customMessages = [
                //     'Addendum_Comments.required' => 'The Addendum Comments field is required.',

                // ];
                // $validator = Validator::make($effectiveness->toArray(), $rules, $customMessages);
                // if ($validator->fails()) {
                //     $errorMessages = implode('<br>', $validator->errors()->all());
                //     session()->put('errorMessages', $errorMessages);
                //     return back();
                // } else {
                $effectiveness->stage = '2';
                $effectiveness->status = 'Pending Effectiveness Check';
                $effectiveness->submit_by =  Auth::user()->name;
                $effectiveness->submit_on = Carbon::now()->format('d-M-Y');
                $effectiveness->submit_comment = $request->comment;

                $history = new EffectivenessCheckAuditTrail();
                $history->effectiveness_check_id = $id;
                $history->activity_type = 'Submit By, Submit On';
                if (is_null($lastopenState->submit_by) || $lastopenState->submit_by  === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastopenState->submit_by  . ' , ' . $lastopenState->submit_on;
                }
                $history->current = $effectiveness->submit_by  . ' , ' . $effectiveness->submit_on;
                // $history->activity_type = 'Activity Log';
                // $history->current = $effectiveness->submit_by;
                $history->comment = $request->comment;
                $history->action = 'Submit';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastopenState->status;
                $history->stage = 'Submit';
                $history->change_to = 'Pending Effectiveness Check';
                $history->change_from = 'Opened';
                $history->action_name = 'Not Applicable';
                if (is_null($lastopenState->submit_by) || $lastopenState->submit_by  === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();


                $list = Helpers::getSupervisorUserList($effectiveness->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if (!empty($users)) {
                    try {
                        $history = new EffectivenessCheckAuditTrail();
                        $history->effectiveness_check_id = $id;
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
                        $history->change_from = "Pending Effectiveness Check";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Supervisor";
                        $history->save();
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $effectiveness->division_id){
                    $email = Helpers::getSupervisorEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $effectiveness, 'site' => 'EC', 'history' => 'Submit', 'process' => 'Effectiveness Check', 'comment' => $effectiveness->submit_comment, 'user' => Auth::user()->name],
                                function ($message) use ($email, $effectiveness) {
                                    $message->to($email)
                                        ->subject("QMS Notification: Effectiveness Check, Record #" . str_pad($effectiveness->record, 4, '0', STR_PAD_LEFT) . " - Activity: Submit Performed");
                                }
                            );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                }


                $effectiveness->update();
                $history = new CCStageHistory();
                $history->type = "Effectiveness-Check";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $effectiveness->stage;
                $history->status = $effectiveness->status;
                $history->save();
                toastr()->success('Document Sent');

                return back();
            }
            if ($effectiveness->stage == 2) {
                // $rules = [
                //     'Comments' => 'req uired|max:255',

                // ];
                // $customMessages = [
                //     'Comments.required' => 'The  Comments field is required.',

                // ];
                // $validator = Validator::make($effectiveness->toArray(), $rules, $customMessages);
                // if ($validator->fails()) {
                //     $errorMessages = implode('<br>', $validator->errors()->all());
                //     session()->put('errorMessages', $errorMessages);
                //     return back();
                // } else {
                $effectiveness->stage = '3';
                $effectiveness->status = 'QA Approval-Effective';
                $effectiveness->effective_by =  Auth::user()->name;
                $effectiveness->effective_on = Carbon::now()->format('d-M-Y');
                $effectiveness->effective_comment = $request->comment;

                $history = new EffectivenessCheckAuditTrail();
                $history->effectiveness_check_id = $id;
                $history->activity_type = 'Effective By, Effective On';
                if (is_null($lastopenState->effective_by) || $lastopenState->effective_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastopenState->effective_by . ' , ' . $lastopenState->effective_on;
                }
                $history->current = $effectiveness->effective_by . ' , ' . $effectiveness->effective_on;
                // $history->activity_type = 'Activity Log';
                // $history->current = $effectiveness->effective_by;
                $history->comment = $request->comment;
                $history->action = 'Effective';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastopenState->status;
                $history->stage = 'Effective';
                $history->change_to = 'QA Approval-Effective';
                $history->change_from = 'Pending Effectiveness Check';
                $history->action_name = 'Not Applicable';
                if (is_null($lastopenState->effective_by) || $lastopenState->effective_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getQAUserList($effectiveness->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if (!empty($users)) {
                    try {
                        $history = new EffectivenessCheckAuditTrail();
                        $history->effectiveness_check_id = $id;
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
                        $history->change_from = "QA Approval-Effective";
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
                    // if($u->q_m_s_divisions_id == $effectiveness->division_id){
                    $email = Helpers::getQAEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $effectiveness, 'site' => 'EC', 'history' => 'Effective', 'process' => 'Effectiveness Check', 'comment' => $effectiveness->effective_comment, 'user' => Auth::user()->name],
                                function ($message) use ($email, $effectiveness) {
                                    $message->to($email)
                                        ->subject("QMS Notification: Effectiveness Check, Record #" . str_pad($effectiveness->record, 4, '0', STR_PAD_LEFT) . " - Activity: Effective Performed");
                                }
                            );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                }

                $effectiveness->update();
                $history = new CCStageHistory();
                $history->type = "Effectiveness-Check";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $effectiveness->stage;
                $history->status = $effectiveness->status;
                $history->save();
                toastr()->success('Document Sent');

                return back();
            }
            if ($effectiveness->stage == 3) {
                $effectiveness->stage = '4';
                $effectiveness->status = 'Closed  Effective';
                $effectiveness->effective_approval_complete_by =  Auth::user()->name;
                $effectiveness->effective_approval_complete_on = Carbon::now()->format('d-M-Y');
                $effectiveness->effective_approval_complete_comment = $request->comment;

                $history = new EffectivenessCheckAuditTrail();
                $history->effectiveness_check_id = $id;
                $history->activity_type = 'Effective Approval Completed By, Effective Approval Completed On';
                if (is_null($lastopenState->effective_approval_complete_by) || $lastopenState->effective_approval_complete_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastopenState->effective_approval_complete_by . ' , ' . $lastopenState->effective_approval_complete_on;
                }
                $history->current = $effectiveness->effective_approval_complete_by . ' , ' . $effectiveness->effective_approval_complete_on;
                // $history->activity_type = 'Activity Log';
                // $history->current = $effectiveness->effective_approval_complete_by;
                $history->comment = $request->comment;
                $history->action = 'Effective Approval Completed';
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastopenState->status;
                $history->stage = 'Effective Approval Completed';
                $history->change_to = 'Closed – Effective';
                $history->change_from = 'QA Approval-Effective';
                $history->action_name = 'Not Applicable';
                if (is_null($lastopenState->effective_approval_complete_by) || $lastopenState->effective_approval_complete_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getSupervisorUserList($effectiveness->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if (!empty($users)) {
                    try {
                        $history = new EffectivenessCheckAuditTrail();
                        $history->effectiveness_check_id = $id;
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
                        $history->change_from = "Closed  Effective";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Supervisor";
                        $history->save();
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $effectiveness->division_id){
                    $email = Helpers::getSupervisorEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $effectiveness, 'site' => 'EC', 'history' => 'Effective Approval Completed', 'process' => 'Effectiveness Check', 'comment' => $effectiveness->effective_approval_complete_comment, 'user' => Auth::user()->name],
                                function ($message) use ($email, $effectiveness) {
                                    $message->to($email)
                                        ->subject("QMS Notification: Effectiveness Check, Record #" . str_pad($effectiveness->record, 4, '0', STR_PAD_LEFT) . " - Activity: Effective Approval Completed Performed");
                                }
                            );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                }

                $list = Helpers::getInitiatorUserList($effectiveness->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if (!empty($users)) {
                    try {
                        $history = new EffectivenessCheckAuditTrail();
                        $history->effectiveness_check_id = $id;
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
                        $history->change_from = "Closed  Effective";
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
                    // if($u->q_m_s_divisions_id == $effectiveness->division_id){
                    $email = Helpers::getInitiatorEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $effectiveness, 'site' => 'EC', 'history' => 'Effective Approval Completed', 'process' => 'Effectiveness Check', 'comment' => $effectiveness->effective_approval_complete_comment, 'user' => Auth::user()->name],
                                function ($message) use ($email, $effectiveness) {
                                    $message->to($email)
                                        ->subject("QMS Notification: Effectiveness Check, Record #" . str_pad($effectiveness->record, 4, '0', STR_PAD_LEFT) . " - Activity: Effective Approval Completed Performed");
                                }
                            );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                }

                $effectiveness->update();
                $history = new CCStageHistory();
                $history->type = "Effectiveness-Check";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $effectiveness->stage;
                $history->status = $effectiveness->status;
                $history->save();

                toastr()->success('Document Sent');

                return back();
            }
        } else {
            toastr()->error('E-signature Not match');

            return back();
        }
    }

    public function reject(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $effectiveness = EffectivenessCheck::find($id);
            $lastopenState = EffectivenessCheck::find($id);

            if ($effectiveness->stage == 2) {
                $effectiveness->stage = '5';
                $effectiveness->status = 'QA Approval-Not Effective';
                $effectiveness->not_effective_by =  Auth::user()->name;
                $effectiveness->not_effective_on = Carbon::now()->format('d-M-Y');
                $effectiveness->not_effective_comment = $request->comment;

                $history = new EffectivenessCheckAuditTrail();
                $history->effectiveness_check_id = $id;
                $history->activity_type = 'Not Effective By, Not Effective On';
                if (is_null($lastopenState->not_effective_by) || $lastopenState->not_effective_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastopenState->not_effective_by . ' , ' . $lastopenState->not_effective_on;
                }
                $history->current = $effectiveness->not_effective_by . ' , ' . $effectiveness->not_effective_on;
                // $history->activity_type = 'Activity Log';
                $history->action = 'Not Effective';
                // $history->current = $effectiveness->not_effective_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastopenState->status;
                $history->stage = 'Not Effective';
                $history->change_to = 'QA Approval-Not Effective';
                $history->change_from = 'Pending Effectiveness Check';
                $history->action_name = 'Not Applicable';
                if (is_null($lastopenState->not_effective_by) || $lastopenState->not_effective_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getQAUserList($effectiveness->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if (!empty($users)) {
                    try {
                        $history = new EffectivenessCheckAuditTrail();
                        $history->effectiveness_check_id = $id;
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
                        $history->change_from = "QA Approval-Not Effective";
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
                    // if($u->q_m_s_divisions_id == $effectiveness->division_id){
                    $email = Helpers::getQAEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $effectiveness, 'site' => 'EC', 'history' => 'Not Effective', 'process' => 'Effectiveness Check', 'comment' => $effectiveness->not_effective_comment, 'user' => Auth::user()->name],
                                function ($message) use ($email, $effectiveness) {
                                    $message->to($email)
                                        ->subject("QMS Notification: Effectiveness Check, Record #" . str_pad($effectiveness->record, 4, '0', STR_PAD_LEFT) . " - Activity: Not Effective Performed");
                                }
                            );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                }

                $effectiveness->update();
                $history = new CCStageHistory();
                $history->type = "Effectiveness-Check";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $effectiveness->stage;
                $history->status = "Reject";
                $history->save();
                toastr()->success('Document Sent');

                return back();
            }

            if ($effectiveness->stage == 5) {
                $effectiveness->stage = '6';
                $effectiveness->status = 'Closed – Not Effective';
                $effectiveness->not_effective_approval_complete_by =  Auth::user()->name;
                $effectiveness->not_effective_approval_complete_on = Carbon::now()->format('d-M-Y');
                $effectiveness->not_effective_approval_complete_comment = $request->comment;

                $history = new EffectivenessCheckAuditTrail();
                $history->effectiveness_check_id = $id;
                $history->activity_type = 'Not Effective Approval Complete By ,Not Effective Approval Complete On';
                if (is_null($lastopenState->not_effective_approval_complete_by) || $lastopenState->not_effective_approval_complete_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastopenState->not_effective_approval_complete_by . ' , ' . $lastopenState->not_effective_approval_complete_on;
                }
                $history->current = $effectiveness->not_effective_approval_complete_by . ' , ' . $effectiveness->not_effective_approval_complete_on;
                // $history->activity_type = 'Activity Log';
                $history->action = 'Not Effective Approval Complete';
                //$history->current = $effectiveness->not_effective_approval_complete_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastopenState->status;
                $history->stage = 'Not Effective Approval Complete';
                $history->change_to = 'Closed  Not Effective';
                $history->change_from = 'QA Approval-Not Effective';
                $history->action_name = 'Not Applicable';
                if (is_null($lastopenState->not_effective_approval_complete_by) || $lastopenState->not_effective_approval_complete_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getSupervisorUserList($effectiveness->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if (!empty($users)) {
                    try {
                        $history = new EffectivenessCheckAuditTrail();
                        $history->effectiveness_check_id = $id;
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
                        $history->change_from = "Closed - Not Effective";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Supervisor";
                        $history->save();
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $effectiveness->division_id){
                    $email = Helpers::getSupervisorEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $effectiveness, 'site' => 'EC', 'history' => 'Not Effective Approval Complete', 'process' => 'Effectiveness Check', 'comment' => $effectiveness->not_effective_approval_complete_comment, 'user' => Auth::user()->name],
                                function ($message) use ($email, $effectiveness) {
                                    $message->to($email)
                                        ->subject("QMS Notification: Effectiveness Check, Record #" . str_pad($effectiveness->record, 4, '0', STR_PAD_LEFT) . " - Activity: Not Effective Approval Complete Performed");
                                }
                            );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                }

                $list = Helpers::getInitiatorUserList($effectiveness->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if (!empty($users)) {
                    try {
                        $history = new EffectivenessCheckAuditTrail();
                        $history->effectiveness_check_id = $id;
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
                        $history->change_from = "Closed - Not Effective";
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
                    // if($u->q_m_s_divisions_id == $effectiveness->division_id){
                    $email = Helpers::getInitiatorEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $effectiveness, 'site' => 'EC', 'history' => 'Not Effective Approval Complete', 'process' => 'Effectiveness Check', 'comment' => $effectiveness->not_effective_approval_complete_comment, 'user' => Auth::user()->name],
                                function ($message) use ($email, $effectiveness) {
                                    $message->to($email)
                                        ->subject("QMS Notification: Effectiveness Check, Record #" . str_pad($effectiveness->record, 4, '0', STR_PAD_LEFT) . " - Activity: Not Effective Approval Complete Performed");
                                }
                            );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                }

                $effectiveness->update();
                $history = new CCStageHistory();
                $history->type = "Effectiveness-Check";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $effectiveness->stage;
                $history->status = $effectiveness->status;
                $history->save();
                toastr()->success('Document Sent');

                return back();
            }
        } else {
            toastr()->error('E-signature Not match');

            return back();
        }
    }

    public function cancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $effectiveness = EffectivenessCheck::find($id);
            $lastopenState = EffectivenessCheck::find($id);

            if ($effectiveness->stage == 3) {
                $effectiveness->stage = '2';
                $effectiveness->status = 'Pending Effectiveness Check';
                $effectiveness->more_effective_by =  Auth::user()->name;
                $effectiveness->more_effective_on = Carbon::now()->format('d-M-Y');
                $effectiveness->more_effective_comment = $request->comment;

                $history = new EffectivenessCheckAuditTrail();
                $history->effectiveness_check_id = $id;
                $history->activity_type = 'More Information Required By ,More Information Required On';
                if (is_null($lastopenState->more_effective_by) || $lastopenState->more_effective_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastopenState->more_effective_by . ' , ' . $lastopenState->more_effective_on;
                }
                $history->current = $effectiveness->more_effective_by . ' , ' . $effectiveness->more_effective_on;
                // $history->activity_type = 'Activity Log';
                $history->action = 'More Information Required';
                // $history->current = $effectiveness->more_effective_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastopenState->status;
                $history->stage = 'More Information Required';
                $history->change_to = 'Pending Effectiveness Check';
                $history->change_from = 'QA Approval-Effective';
                $history->action_name = 'Not Applicable';
                if (is_null($lastopenState->more_effective_by) || $lastopenState->more_effective_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getSupervisorUserList($effectiveness->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if (!empty($users)) {
                    try {
                        $history = new EffectivenessCheckAuditTrail();
                        $history->effectiveness_check_id = $id;
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
                        $history->change_from = "Pending Effectiveness Check";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Supervisor";
                        $history->save();
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $effectiveness->division_id){
                    $email = Helpers::getSupervisorEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $effectiveness, 'site' => 'EC', 'history' => 'More Information Required', 'process' => 'Effectiveness Check', 'comment' => $effectiveness->more_effective_comment, 'user' => Auth::user()->name],
                                function ($message) use ($email, $effectiveness) {
                                    $message->to($email)
                                        ->subject("QMS Notification: Effectiveness Check, Record #" . str_pad($effectiveness->record, 4, '0', STR_PAD_LEFT) . " - Activity: More Information Required Performed");
                                }
                            );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                }

                $effectiveness->update();

                $history = new CCStageHistory();
                $history->type = "Effectiveness-Check";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $effectiveness->stage;
                $history->status = "Reject";
                $history->save();

                toastr()->success('Document Sent');

                return back();
            }

            if ($effectiveness->stage == 5) {
                $effectiveness->stage = '2';
                $effectiveness->status = 'Pending Effectiveness Check';
                $effectiveness->more_not_effective_by =  Auth::user()->name;
                $effectiveness->more_not_effective_on = Carbon::now()->format('d-M-Y');
                $effectiveness->more_not_effective_comment = $request->comment;

                $history = new EffectivenessCheckAuditTrail();
                $history->effectiveness_check_id = $id;
                $history->activity_type = 'More Information Required By ,More Information Required On';
                if (is_null($lastopenState->more_not_effective_by) || $lastopenState->more_not_effective_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastopenState->more_not_effective_by . ' , ' . $lastopenState->more_not_effective_on;
                }
                $history->current = $effectiveness->more_not_effective_by . ' , ' . $effectiveness->more_not_effective_on;

                $history->action = 'More Information Required';
                $history->current = $effectiveness->more_not_effective_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastopenState->status;
                $history->stage = 'More Information Required';
                $history->change_to = 'Pending Effectiveness Check';
                $history->change_from = 'QA Approval-Not Effective';
                $history->action_name = 'Not Applicable';
                if (is_null($lastopenState->more_not_effective_by) || $lastopenState->more_not_effective_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getSupervisorUserList($effectiveness->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if (!empty($users)) {
                    try {
                        $history = new EffectivenessCheckAuditTrail();
                        $history->effectiveness_check_id = $id;
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
                        $history->change_from = "Pending Effectiveness Check";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Supervisor";
                        $history->save();
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $effectiveness->division_id){
                    $email = Helpers::getSupervisorEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $effectiveness, 'site' => 'EC', 'history' => 'More Information Required', 'process' => 'Effectiveness Check', 'comment' => $effectiveness->more_not_effective_comment, 'user' => Auth::user()->name],
                                function ($message) use ($email, $effectiveness) {
                                    $message->to($email)
                                        ->subject("QMS Notification: Effectiveness Check, Record #" . str_pad($effectiveness->record, 4, '0', STR_PAD_LEFT) . " - Activity: More Information Required Performed");
                                }
                            );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                }


                $effectiveness->update();

                $history = new CCStageHistory();
                $history->type = "Effectiveness-Check";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $effectiveness->stage;
                $history->status = $effectiveness->status;
                $history->save();
                toastr()->success('Document Sent');

                return back();
            }
        } else {
            toastr()->error('E-signature Not match');

            return back();
        }
    }
    public function effectiveAuditTrialShow($id)
    {
        $audit = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $id)->orderByDESC('id')->paginate(5);
        $today = Carbon::now()->format('d-m-y');
        $document = EffectivenessCheck::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');

        return view('frontend.effectivenessCheck.audit-trial', compact('audit', 'document', 'today'));
    }
    public function effectiveAuditTrialDetails($id)
    {
        $detail = EffectivenessCheck::find($id);

        $detail_data = EffectivenessCheck::where('activity_type', $detail->activity_type)->where('parent_id', $detail->parent_id)->latest()->get();

        $doc = EffectivenessCheck::where('id', $detail->parent_id)->first();

        $doc->origiator_name = User::find($doc->initiator_id);
        return view('frontend.effectivenessCheck.audit-trial-inner', compact('detail', 'doc', 'detail_data'));
    }

    public static function singleReport($id)
    {
        $data = EffectivenessCheck::find($id);
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.effectivenessCheck.singleReport', compact('data'))
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
            return $pdf->stream('effectivenessCheck' . $id . '.pdf');
        }
    }
    public static function auditReport($id)
    {
        $doc = EffectivenessCheck::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = EffectivenessCheckAuditTrail::where('effectiveness_check_id', $id)->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.effectivenessCheck.auditReport', compact('data', 'doc'))
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
            return $pdf->stream('effectivenessCheck-Audit' . $id . '.pdf');
        }
    }
}
