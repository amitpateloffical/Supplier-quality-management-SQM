<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Models\CC;
use App\Models\CCStageHistory;
use App\Models\EffectivenessCheck;
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
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');
        return view('frontend.forms.effectiveness-check', compact('due_date', 'record_number'));
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

    public function create()
    {
    }

    public function store(Request $request)
    {

        $openState = new EffectivenessCheck();
        // $openState->form_type = "effectiveness-check";
        $openState->is_parent = "No";
        $openState->parent_id = $request->cc_id;
        $openState->parent_type = $request->cc_id;

        $openState->division_id = $request->division_id;
        $openState->intiation_date = $request->intiation_date;
        $openState->initiator_id = Auth::user()->id;
        $openState->parent_record = CC::where('id', $request->cc_id)->value('id');
        $openState->record = DB::table('record_numbers')->value('counter') + 1;
        $openState->originator = CC::where('id', $request->cc_id)->value('initiator_id');
        $openState->assign_to = $request->assign_to;
        $openState->due_date = $request->due_date;
        $openState->short_description = $request->short_description;
        $openState->Effectiveness_check_Plan = $request->Effectiveness_check_Plan;
        $openState->Effectiveness_Summary = $request->Effectiveness_Summary;
        $openState->effect_summary = $request->effect_summary;
        $openState->Quality_Reviewer = $request->Quality_Reviewer;
        $openState->Effectiveness_Results = $request->Effectiveness_Results;
        $openState->Addendum_Comments = $request->Addendum_Comments;
        $openState->change_from;
        $openState->change_to;
        $openState->action_name;
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
       if (!empty($request->refer_record)) {
        $files = [];
        if ($request->hasfile('refer_record')) {
            foreach ($request->file('refer_record') as $file) {
                $name = $request->name . 'refer_record' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                $file->move('upload/', $name);
                $files[] = $name;
            }
        }

        $openState->refer_record = json_encode($files);
    }
        $openState->Comments = $request->Comments;
        $openState->status = "Opened";
        $openState->stage = 1;
        $openState->save();


        $counter = DB::table('record_numbers')->value('counter');
        $recordNumber = str_pad($counter, 5, '0', STR_PAD_LEFT);
        $newCounter = $counter + 1;
        DB::table('record_numbers')->update(['counter' => $newCounter]);






        if (!empty($openState->record)) {
            $historyEffectiveness = new EffectivenessCheck(); // Create a new instance
            $historyEffectiveness->parent_id = $openState->id;
            $historyEffectiveness->activity_type = 'Record Number';
            $historyEffectiveness->previous = "Null";
            $historyEffectiveness->current = $openState->record;
            $historyEffectiveness->comment = "NA";
            $historyEffectiveness->user_id = Auth::user()->id;
            $historyEffectiveness->user_name = Auth::user()->name;
            $historyEffectiveness->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyEffectiveness->origin_state = $openState->status;
            $historyEffectiveness->change_from = "Initiation";
            $historyEffectiveness->change_to = "Opened";
            $historyEffectiveness->action_name = "Create";
            $historyEffectiveness->save(); // Save the new instance
        }



        if (!empty($openState->division_code)) {
            $historyEffectiveness = new EffectivenessCheck(); // Create a new instance
            $historyEffectiveness->parent_id = $openState->id;
            $historyEffectiveness->activity_type = 'Division Code';
            $historyEffectiveness->previous = "Null";
            $historyEffectiveness->current = $openState->division_code;
            $historyEffectiveness->comment = "NA";
            $historyEffectiveness->user_id = Auth::user()->id;
            $historyEffectiveness->user_name = Auth::user()->name;
            $historyEffectiveness->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyEffectiveness->origin_state = $openState->status;
            $historyEffectiveness->change_from = "Initiation";
            $historyEffectiveness->change_to = "Opened";
            $historyEffectiveness->action_name = "Create";
            $historyEffectiveness->save(); // Save the new instance
        }

        if (!empty($openState->initiator_id)) {
            $historyEffectiveness = new EffectivenessCheck(); // Create a new instance
            $historyEffectiveness->parent_id = $openState->id;
            $historyEffectiveness->activity_type = 'Initiator';
            $historyEffectiveness->previous = "Null";
            $historyEffectiveness->current = $openState->initiator_id;
            $historyEffectiveness->comment = "NA";
            $historyEffectiveness->user_id = Auth::user()->id;
            $historyEffectiveness->user_name = Auth::user()->name;
            $historyEffectiveness->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyEffectiveness->origin_state = $openState->status;
            $historyEffectiveness->change_from = "Initiation";
            $historyEffectiveness->change_to = "Opened";
            $historyEffectiveness->action_name = "Create";
            $historyEffectiveness->save(); // Save the new instance
        }



        if (!empty($openState->intiation_date)) {
            $historyEffectiveness = new EffectivenessCheck(); // Create a new instance
            $historyEffectiveness->parent_id = $openState->id;
            $historyEffectiveness->activity_type = 'Date of Initiation';
            $historyEffectiveness->previous = "Null";
            $historyEffectiveness->current = $openState->intiation_date;
            $historyEffectiveness->comment = "NA";
            $historyEffectiveness->user_id = Auth::user()->id;
            $historyEffectiveness->user_name = Auth::user()->name;
            $historyEffectiveness->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyEffectiveness->origin_state = $openState->status;
            $historyEffectiveness->change_from = "Initiation";
            $historyEffectiveness->change_to = "Opened";
            $historyEffectiveness->action_name = "Create";
            $historyEffectiveness->save(); // Save the new instance
        }


































        if ($openState->assign_to != $request->input('previous_assign_to') || !empty($request->assign_to_comment)) {


            $previousAssignedUser = User::find($request->input('previous_assign_to'))->name ?? 'Null';
            $currentAssignedUser = User::find($openState->assign_to)->name ?? 'Null';

            $lastDataAudittrail = EffectivenessCheck::where('is_parent', $openState->id)
                ->where('activity_type', 'Assigned To')
                ->exists();

            $historyAssigned = new EffectivenessCheck();
            $historyAssigned->parent_id = $openState->id;
            $historyAssigned->activity_type = 'Assigned To';
            $historyAssigned->previous = $previousAssignedUser;
            $historyAssigned->current = $currentAssignedUser;
            $historyAssigned->comment = $request->assign_to_comment ?? 'NA';
            $historyAssigned->user_id = Auth::user()->id;
            $historyAssigned->user_name = Auth::user()->name;
            $historyAssigned->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyAssigned->origin_state = $openState->status;
            $historyAssigned->change_from = "Initiation";
            $historyAssigned->change_to = "Opened";
            $historyAssigned->action_name =  'Create';
            $historyAssigned->save();
        }

        if (!empty($openState->short_description)) {
            $historyShort = new EffectivenessCheck();
            $historyShort->parent_id = $openState->id;
            $historyShort->activity_type = 'Short Description';
            $historyShort->previous = "Null";
            $historyShort->current = $openState->short_description;
            $historyShort->comment = "NA";
            $historyShort->user_id = Auth::user()->id;
            $historyShort->user_name = Auth::user()->name;
            $historyShort->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyShort->origin_state = $openState->status;
            $historyShort->change_from = "Initiation";
            $historyShort->change_to = "Opened";
            $historyShort->action_name = "Create";
            $historyShort->save();
        }


        if (!empty($openState->Effectiveness_check_Plan)) {
            $historyEffectiveness = new EffectivenessCheck(); // Create a new instance
            $historyEffectiveness->parent_id = $openState->id;
            $historyEffectiveness->activity_type = 'Effectiveness Check Plan';
            $historyEffectiveness->previous = "Null";
            $historyEffectiveness->current = $openState->Effectiveness_check_Plan;
            $historyEffectiveness->comment = "NA";
            $historyEffectiveness->user_id = Auth::user()->id;
            $historyEffectiveness->user_name = Auth::user()->name;
            $historyEffectiveness->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyEffectiveness->origin_state = $openState->status;
            $historyEffectiveness->change_from = "Initiation";
            $historyEffectiveness->change_to = "Opened";
            $historyEffectiveness->action_name = "Create";
            $historyEffectiveness->save(); // Save the new instance
        }


        if (!empty($openState->Effectiveness_Summary)) {
            $historyEffectiveness = new EffectivenessCheck();
            $historyEffectiveness->parent_id = $openState->id;
            $historyEffectiveness->activity_type = 'Effectiveness Summary';
            $historyEffectiveness->previous = "Null";
            $historyEffectiveness->current = $openState->Effectiveness_Summary;
            $historyEffectiveness->comment = "NA";
            $historyEffectiveness->user_id = Auth::user()->id;
            $historyEffectiveness->user_name = Auth::user()->name;
            $historyEffectiveness->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyEffectiveness->origin_state = $openState->status;
            $historyEffectiveness->change_from = "Initiation";
            $historyEffectiveness->change_to = "Opened";
            $historyEffectiveness->action_name = "Create";
            $historyEffectiveness->save();
        }

        if (!empty($openState->effect_summary)) {
            $historyEffect = new EffectivenessCheck();
            $historyEffect->parent_id = $openState->id;
            $historyEffect->activity_type = 'Effect Summary';
            $historyEffect->previous = "Null";
            $historyEffect->current = $openState->effect_summary;
            $historyEffect->comment = "NA";
            $historyEffect->user_id = Auth::user()->id;
            $historyEffect->user_name = Auth::user()->name;
            $historyEffect->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyEffect->origin_state = $openState->status;
            $historyEffect->change_from = "Initiation";
            $historyEffect->change_to = "Opened";
            $historyEffect->action_name = "Create";
            $historyEffect->save();
        }


        if (!empty($openState->Quality_Reviewer)) {
            $historyQuality = new EffectivenessCheck();
            $historyQuality->parent_id = $openState->id;
            $historyQuality->activity_type = 'Quality Reviewer';
            $historyQuality->previous = "Null";
            $historyQuality->current = $openState->Quality_Reviewer;
            $historyQuality->comment = "NA";
            $historyQuality->user_id = Auth::user()->id;
            $historyQuality->user_name = Auth::user()->name;
            $historyQuality->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyQuality->origin_state = $openState->status;
            $historyQuality->change_from = "Initiation";
            $historyQuality->change_to = "Opened";
            $historyQuality->action_name = "Create";
            $historyQuality->save();
        }


        if (!empty($openState->Effectiveness_Results)) {
            $historyEffectiveness = new EffectivenessCheck();
            $historyEffectiveness->parent_id = $openState->id;
            $historyEffectiveness->activity_type = 'Effectiveness Results';
            $historyEffectiveness->previous = "Null";
            $historyEffectiveness->current = $openState->Effectiveness_Results;
            $historyEffectiveness->comment = "NA";
            $historyEffectiveness->user_id = Auth::user()->id;
            $historyEffectiveness->user_name = Auth::user()->name;
            $historyEffectiveness->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyEffectiveness->origin_state = $openState->status;
            $historyEffectiveness->change_from = "Initiation";
            $historyEffectiveness->change_to = "Opened";
            $historyEffectiveness->action_name = "Create";
            $historyEffectiveness->save();
        }

        if (!empty($openState->Addendum_Comments)) {
            $historyAddendum = new EffectivenessCheck();
            $historyAddendum->parent_id = $openState->id;
            $historyAddendum->activity_type = 'Addendum Comments';
            $historyAddendum->previous = "Null";
            $historyAddendum->current = $openState->Addendum_Comments;
            $historyAddendum->comment = "NA";
            $historyAddendum->user_id = Auth::user()->id;
            $historyAddendum->user_name = Auth::user()->name;
            $historyAddendum->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyAddendum->origin_state = $openState->status;
            $historyAddendum->change_from = "Initiation";
            $historyAddendum->change_to = "Opened";
            $historyAddendum->action_name = "Create";
            $historyAddendum->save();
        }


        if (!empty($openState->Effectiveness_check_Attachment)) {
            $historyEffectiveness = new EffectivenessCheck();
            $historyEffectiveness->parent_id = $openState->id;
            $historyEffectiveness->activity_type = 'Effectiveness Check Attachment';
            $historyEffectiveness->previous = "Null";
            $historyEffectiveness->current = $openState->Effectiveness_check_Attachment;
            $historyEffectiveness->comment = "NA";
            $historyEffectiveness->user_id = Auth::user()->id;
            $historyEffectiveness->user_name = Auth::user()->name;
            $historyEffectiveness->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyEffectiveness->origin_state = $openState->status;
            $historyEffectiveness->change_from = "Initiation";
            $historyEffectiveness->change_to = "Opened";
            $historyEffectiveness->action_name = "Create";
            $historyEffectiveness->save();
        }

        if (!empty($openState->Addendum_Attachment)) {
            $historyAddendum = new EffectivenessCheck();
            $historyAddendum->parent_id = $openState->id;
            $historyAddendum->activity_type = 'Addendum Attachment';
            $historyAddendum->previous = "Null";
            $historyAddendum->current = $openState->Addendum_Attachment;
            $historyAddendum->comment = "NA";
            $historyAddendum->user_id = Auth::user()->id;
            $historyAddendum->user_name = Auth::user()->name;
            $historyAddendum->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyAddendum->origin_state = $openState->status;
            $historyAddendum->change_from = "Initiation";
            $historyAddendum->change_to = "Opened";
            $historyAddendum->action_name = "Create";
            $historyAddendum->save();
        }

        if (!empty($openState->Attachment)) {
            $historyAttachment = new EffectivenessCheck();
            $historyAttachment->parent_id = $openState->id;
            $historyAttachment->activity_type = 'Attachment';
            $historyAttachment->previous = "Null";
            $historyAttachment->current = $openState->Attachment;
            $historyAttachment->comment = "NA";
            $historyAttachment->user_id = Auth::user()->id;
            $historyAttachment->user_name = Auth::user()->name;
            $historyAttachment->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $historyAttachment->origin_state = $openState->status;
            $historyAttachment->change_from = "Initiation";
            $historyAttachment->change_to = "Opened";
            $historyAttachment->action_name = "Create";
            $historyAttachment->save();
        }
        if (!empty($openState->Attachments)) {

            $attachments = is_string($openState->Attachments) ? json_decode($openState->Attachments, true) : $openState->Attachments;


            if (is_array($attachments)) {
                foreach ($attachments as $attachment) {
                    $historyAttachments = new EffectivenessCheck();
                    $historyAttachments->parent_id = $openState->id;
                    $historyAttachments->activity_type = 'Attachments';
                    $historyAttachments->previous = "Null";
                    $historyAttachments->current = $attachment;
                    $historyAttachments->comment = "NA";
                    $historyAttachments->user_id = Auth::user()->id;
                    $historyAttachments->user_name = Auth::user()->name;
                    $historyAttachments->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $historyAttachments->origin_state = $openState->status;
                    $historyAttachments->change_from = "Initiation";
                    $historyAttachments->change_to = "Opened";
                    $historyAttachments->action_name = "Create";
                    $historyAttachments->save();
                }
            } else {

            }
        }


        if (!empty($openState->refer_record)) {
            $history = new EffectivenessCheck();
            $history->parent_id = $openState->id;
            $history->activity_type = 'Refer Record';
            $history->previous = "Null";
            $history->current = $openState->refer_record;
            $history->comment = "NA";
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
        return view('frontend.effectivenessCheck.view', compact('data'));
    }

    public function edit($id)
    {
    }

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
     //   $openState->Cancellation_Category = $request->Cancellation_Category;
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

       // $openState->Addendum_Attachment = $request->Addendum_Attachment;
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
     //   $openState->Attachment = $request->Attachment;
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
        if (!empty($request->refer_record)) {
            $files = [];
            if ($request->hasfile('refer_record')) {
                foreach ($request->file('refer_record') as $file) {
                    $name = $request->name . 'refer_record' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $openState->refer_record = json_encode($files);
        }
        $openState->Comments = $request->Comments;
        $openState->update();




if ($lastopenState->assign_to != $openState->assign_to || !empty($request->assign_to_comment)) {

    $previousAssignedUser = User::find($lastopenState->assign_to)->name ?? 'Null';
    $currentAssignedUser = User::find($openState->assign_to)->name ?? 'Null';

    $lastDataAudittrail = EffectivenessCheck::where('parent_id', $openState->id)
        ->where('activity_type', '  Assigned To')
        ->exists();

    $history = new EffectivenessCheck();
    $history->parent_id = $openState->id;
    $history->activity_type = '  Assigned To';
    $history->previous = $previousAssignedUser;
    $history->current = $currentAssignedUser;
    $history->comment = $request->assign_to_comment ?? 'NA';
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

        if ($lastopenState->short_description != $openState->short_description || !empty($request->short_description_comment)) {
           $lastDataAudittrail = EffectivenessCheck::where('parent_id', $openState->id)
                ->where('activity_type', '  Short Description')
                ->exists();

                $history = new EffectivenessCheck();
                $history->parent_id = $openState->id;
                $history->activity_type = '  Short Description';
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
        $lastDataAudittrail = EffectivenessCheck::where('parent_id', $openState->id)
            ->where('activity_type', '  Effectiveness Check Plan')
            ->exists();

        $history = new EffectivenessCheck();
        $history->parent_id = $id;
        $history->activity_type = '  Effectiveness Check Plan';
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



    if ($lastopenState->effect_summary != $openState->effect_summary || !empty($request->effect_summary_comment)) {
        $lastDataAudittrail = EffectivenessCheck::where('parent_id', $openState->id)
            ->where('activity_type', '  Effectiveness Summary')
            ->exists();

        // Create a new instance of EffectivenessCheck
        $history = new EffectivenessCheck();
        $history->parent_id = $id;
        $history->activity_type = '  Effectiveness Summary';
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


        // if ($lastopenState->effect_summary != $openState->effect_summary || !empty($request->effect_summary_comment)) {
        //     $history = EffectivenessCheck::find($id);
        //     $history->parent_id = $id;
        //     $history->activity_type = 'Effect Summary';
        //     $history->previous = $lastopenState->effect_summary;
        //     $history->current = $openState->effect_summary;
        //     $history->comment = $request->effect_summary_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastopenState->status;
        //     $history->update();
        // }


        //     if ($lastopenState->Quality_Reviewer != $openState->Quality_Reviewer || !empty($request->Quality_Reviewer_comment)) {
        //         $lastDataAudittrail = EffectivenessCheck::where('parent_id', $openState->id)
        //             ->where('activity_type', '  Quality Reviewer')
        //             ->exists();

        //     $history = EffectivenessCheck::find($id);
        //     $history->parent_id = $id;
        //     $history->activity_type = '  Quality Reviewer';
        //     $history->previous = $lastopenState->Quality_Reviewer;
        //     $history->current = $openState->Quality_Reviewer;
        //     $history->comment = $request->Quality_Reviewer_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastopenState->status;
        //     $history->update();
        // }





        if ($lastopenState->Effectiveness_Results != $openState->Effectiveness_Results || !empty($request->Effectiveness_Results_comment)) {
            $lastDataAudittrail = EffectivenessCheck::where('parent_id', $openState->id)
                ->where('activity_type', '  Effectiveness Results')
                ->exists();


            $history = new EffectivenessCheck();
            $history->parent_id = $id;
            $history->activity_type = '  Effectiveness Results';
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


        if ($lastopenState->Addendum_Comments != $openState->Addendum_Comments || !empty($request->Addendum_Comments_comment)) {
            $lastDataAudittrail = EffectivenessCheck::where('parent_id', $openState->id)
                ->where('activity_type', '  Addendum Comments')
                ->exists();


            $history = new EffectivenessCheck();
            $history->parent_id = $id;
            $history->activity_type = '  Addendum Comments';
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

        if ($lastopenState->Effectiveness_check_Attachment != $openState->Effectiveness_check_Attachment || !empty($request->Effectiveness_check_Attachment_comment)) {
            $lastDataAudittrail = EffectivenessCheck::where('parent_id', $openState->id)
                ->where('activity_type', '  Effectiveness Check Attachment')
                ->exists();

            // Create a new instance of EffectivenessCheck
            $history = new EffectivenessCheck();
            $history->parent_id = $id;
            $history->activity_type = '  Effectiveness Check Attachment';
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


        if ($lastopenState->Addendum_Attachment != $openState->Addendum_Attachment || !empty($request->Addendum_Attachment_comment)) {
            $lastDataAudittrail = EffectivenessCheck::where('parent_id', $openState->id)
                ->where('activity_type', '  Addendum Attachment')
                ->exists();


            $history = new EffectivenessCheck();
            $history->parent_id = $id;
            $history->activity_type = '  Addendum Attachment';
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


        if ($lastopenState->Attachment != $openState->Attachment || !empty($request->Attachment_comment)) {

            $lastDataAudittrail = EffectivenessCheck::where('parent_id', $openState->id)
                ->where('activity_type', '  Attachment')
                ->exists();


            $history = new EffectivenessCheck();
            $history->parent_id = $id;
            $history->activity_type = '  Attachment';
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


        if ($lastopenState->Attachments != $openState->Attachments || !empty($request->Attachments_comment)) {

            $lastDataAudittrail = EffectivenessCheck::where('parent_id', $openState->id)
                ->where('activity_type', '  Attachments')
                ->exists();


            $history = new EffectivenessCheck();
            $history->parent_id = $id;
            $history->activity_type = '  Attachments';
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


            if ($lastopenState->refer_record != $openState->refer_record || !empty($request->refer_record_comment)) {
                $lastDataAudittrail = EffectivenessCheck::where('parent_id', $openState->id)
                    ->where('activity_type', '  Refer Record')
                    ->exists();

                    $history = new EffectivenessCheck();
                    $history->parent_id = $id;
            $history->activity_type = '  Refer Record';
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

    public function destroy($id)
    {
    }


    public function stageChange(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = EffectivenessCheck::find($id);
            $lastopenState = EffectivenessCheck::find($id);
            if ($changeControl->stage == 1) {
                // $rules = [
                //     'Addendum_Comments' => 'required|max:255',

                // ];
                // $customMessages = [
                //     'Addendum_Comments.required' => 'The Addendum Comments field is required.',

                // ];
                // $validator = Validator::make($changeControl->toArray(), $rules, $customMessages);
                // if ($validator->fails()) {
                //     $errorMessages = implode('<br>', $validator->errors()->all());
                //     session()->put('errorMessages', $errorMessages);
                //     return back();
                // } else {
                    $changeControl->stage = '2';
                    $changeControl->status = 'Pending Effectiveness Check';
                    $changeControl->submit_by =  Auth::user()->name;
                    $changeControl->submit_on = Carbon::now()->format('d-M-Y');
                            $history = new EffectivenessCheck();
                            $history->parent_id = $id;
                            $history->activity_type = 'Activity Log';
                            $history->previous = "";
                            $history->current = $changeControl->submit_by;
                            $history->comment = $request->comment;
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $history->origin_state = $lastopenState->status;
                            $history->step = 'Submit';
                            $history->change_to = 'Pending  Plan';
                            $history->change_from = 'Opened';
                            $history->action_name = 'Not Applicable';
                            $history->save();


                    $list = Helpers:: getSupervisorUserList();
                    foreach ($list as $u) {
                        if($u->q_m_s_divisions_id == $changeControl->division_id){
                            $email = Helpers::getInitiatorEmail($u->user_id);
                             if ($email !== null) {

                              Mail::send(
                                  'mail.view-mail',
                                   ['data' => $changeControl],
                                function ($message) use ($email) {
                                    $message->to($email)
                                        ->subject("Document is Submitted By ".Auth::user()->name);
                                                                                        }
                              );
                            }
                     }
                  }


                    $changeControl->update();
                    $history = new CCStageHistory();
                    $history->type = "Effectiveness-Check";
                    $history->doc_id = $id;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->stage_id = $changeControl->stage;
                    $history->status = $changeControl->status;
                    $history->save();
                    toastr()->success('Document Sent');

                    return back();

            }
            if ($changeControl->stage == 2) {
                // $rules = [
                //     'Comments' => 'req uired|max:255',

                           // ];
                // $customMessages = [
                //     'Comments.required' => 'The  Comments field is required.',

                // ];
                // $validator = Validator::make($changeControl->toArray(), $rules, $customMessages);
                // if ($validator->fails()) {
                //     $errorMessages = implode('<br>', $validator->errors()->all());
                //     session()->put('errorMessages', $errorMessages);
                //     return back();
                // } else {
                    $changeControl->stage = '3';
                                    $changeControl->status = 'QA Approval-Effective';
                    $changeControl->effective_by =  Auth::user()->name;
                    $changeControl->effective_on = Carbon::now()->format('d-M-Y');
                            $history = new EffectivenessCheck();
                            $history->parent_id = $id;
                            $history->activity_type = 'Activity Log';
                            $history->previous = "";
                            $history->current = $changeControl->effective_by;
                            $history->comment = $request->comment;
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $history->origin_state = $lastopenState->status;
                            $history->step = 'Effective';
                            $history->save();

                    $list = Helpers:: getQAUserList();
                    foreach ($list as $u) {
                        if($u->q_m_s_divisions_id == $changeControl->division_id){
                            $email = Helpers::getInitiatorEmail($u->user_id);
                             if ($email !== null) {

                              Mail::send(
                                  'mail.view-mail',
                                   ['data' => $changeControl],
                                function ($message) use ($email) {
                                    $message->to($email)
                                        ->subject("Document is Send By ".Auth::user()->name);
                                }
                              );
                                                        }
                     }
                  }

                    $changeControl->update();
                    $history = new CCStageHistory();
                    $history->type = "Effectiveness-Check";
                    $history->doc_id = $id;
                    $history->user_id = Auth::user()->id;
                                              $history->user_name = Auth::user()->name;
                    $history->stage_id = $changeControl->stage;
                    $history->status = $changeControl->status;
                    $history->save();
                    toastr()->success('Document Sent');

                    return back();

            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = '4';
                $changeControl->status = 'Closed – Effective';
                $changeControl->effective_approval_complete_by =  Auth::user()->name;
                $changeControl->effective_approval_complete_on = Carbon::now()->format('d-M-Y');
                            $history = new EffectivenessCheck();
                            $history->parent_id = $id;
                            $history->activity_type = 'Activity Log';
                            $history->previous = "";
                            $history->current = $changeControl->effective_approval_complete_by;
                            $history->comment = $request->comment;
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $history->origin_state = $lastopenState->status;
                            $history->step = 'Effective Approval Complete';
                            $history->save();
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Effectiveness-Check";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
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
            $changeControl = EffectivenessCheck::find($id);
            $lastopenState = EffectivenessCheck::find($id);
            if ($changeControl->stage == 2) {
                $changeControl->stage = '5';
                $changeControl->status = 'QA Approval-Not Effective';
                $changeControl->not_effective_by =  Auth::user()->name;
                $changeControl->not_effective_on = Carbon::now()->format('d-M-Y');
                            $history = new EffectivenessCheck();
                            $history->parent_id = $id;
                            $history->activity_type = 'Activity Log';
                            $history->previous = "";
                            $history->current = $changeControl->not_effective_by;
                            $history->comment = $request->comment;
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $history->origin_state = $lastopenState->status;
                            $history->step = 'Not Effective';
                            $history->save();

                    $list = Helpers:: getQAUserList();
                    foreach ($list as $u) {
                        if($u->q_m_s_divisions_id == $changeControl->division_id){
                            $email = Helpers::getInitiatorEmail($u->user_id);
                             if ($email !== null) {

                              Mail::send(
                                  'mail.view-mail',
                                   ['data' =>  $changeControl],
                                function ($message) use ($email) {
                                    $message->to($email)
                                        ->subject("Document is Send By ".Auth::user()->name);
                                }
                              );
                            }
                     }
                  }

                $changeControl->update();
                                            $history = new CCStageHistory();
                $history->type = "Effectiveness-Check";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                                          $history->status = "Reject";
                $history->save();
                toastr()->success('Document Sent');

                return back();
            }

            if ($changeControl->stage == 5) {
                $changeControl->stage = '6';
                $changeControl->status = 'Closed – Not Effective';
                $changeControl->not_effective_approval_complete_by =  Auth::user()->name;
                $changeControl->not_effective_approval_complete_on = Carbon::now()->format('d-M-Y');
                                   $history = new EffectivenessCheck();
                        $history->parent_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = "";
                        $history->current = $changeControl->not_effective_approval_complete_by;
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastopenState->status;
                        $history->step = 'Not Effective Approval Complete';
                        $history->save();
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Effectiveness-Check";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
                $history->save();
                toastr()->success('Document Sent');

                return back();
            }
        } else {
            toastr()->error('E-signature Not match');

            return back();
        }
    }

    public function cancel(Request $request, $id) {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = EffectivenessCheck::find($id);
            if ($changeControl->stage == 3) {
                $changeControl->stage = '2';
                $changeControl->status = 'Check Effectiveness';
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Effectiveness-Check";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = "Reject";
                $history->save();
                toastr()->success('Document Sent');

                return back();
            }

            if ($changeControl->stage == 5) {
                $changeControl->stage = '2';
                $changeControl->status = 'Check Effectiveness';
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Effectiveness-Check";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
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
        $audit = EffectivenessCheck::where('parent_id', $id)->orderByDESC('id')->paginate(5);
        $today = Carbon::now()->format('d-m-y');
        $document = EffectivenessCheck::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');
//dd( $document->initiator);
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
        $canvas->page_text($width / 4, $height / 2, $data->status, null, 25, [0, 0, 0], 2, 6, -20);
        return $pdf->stream('effectivenessCheck' . $id . '.pdf');
    }
}
public static function auditReport($id)
{
    $doc = EffectivenessCheck::find($id);
    if (!empty($doc)) {
        $doc->originator = User::where('id', $doc->initiator_id)->value('name');
        $data = EffectivenessCheck::where('parent_id', $id)->get();
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
        $canvas->page_text($width / 4, $height / 2, $doc->status, null, 25, [0, 0, 0], 2, 6, -20);
        return $pdf->stream('effectivenessCheck-Audit' . $id . '.pdf');
    }
}

}
