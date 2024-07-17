<?php

namespace App\Http\Controllers\newForm;

use App\Http\Controllers\Controller;
use App\Models\MedicalDeviceRegistration;
use App\Models\Medical_Device_Grid;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RecordNumber;
use Carbon\Carbon;

class MedicalRegistrationController extends Controller
{
    public function index()
    {
        $old_record = MedicalDeviceRegistration::select('id', 'division_id', 'record_number')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');



        // $registrations = MedicalDeviceRegistration::all();
        return view('frontend.New_forms.medical_device_registration', compact('old_record','record_number','formattedDate','currentDate','due_date'));
    }

    public function medicalCreate(Request $request)
   {
     //dd($request);
    // $request->validate([
    //     'record_number' => 'required|string|max:255',
    //     'date_of_initiation' => 'required|date',
    //     'assign_to' => 'required|string|max:255',
    //     'due_date_gi' => 'required|date',
    //     'short_description' => 'nullable|string',
    //     'registration_type_gi' => 'required|string|max:255',
    //     'file_attachment_gi' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // example for file validation
    //     'parent_record_number' => 'nullable|string|max:255',
    //     'local_record_number' => 'nullable|string|max:255',
    //     'zone_departments' => 'nullable|string|max:255',
    //     'country_number' => 'nullable|string|max:255',
    //     'regulatory_departments' => 'nullable|string|max:255',
    //     'registration_number' => 'nullable|string|max:255',
    //     'risk_based_departments' => 'nullable|string|max:255',
    //     'device_approval_departments' => 'nullable|string|max:255',
    //     'marketing_auth_number' => 'nullable|string|max:255',
    //     'manufacturer_number' => 'nullable|string|max:255',
    //     'audit_agenda_grid' => 'nullable|string|max:255',
    //     'manufacturing_description' => 'nullable|string|max:255',
    //     'dossier_number' => 'nullable|string|max:255',
    //     'dossier_departments' => 'nullable|string|max:255',
    //     'description' => 'nullable|string',
    //     'planned_submission_date' => 'nullable|date',
    //     'actual_submission_date' => 'nullable|date',
    //     'actual_approval_date' => 'nullable|date',
    //     'actual_rejection_date' => 'nullable|date',
    //     'renewal_departments' => 'nullable|string|max:255',
    //     'next_renewal_date' => 'nullable|date',
    // ]);

    $data = new MedicalDeviceRegistration();

    // dd($request->all());
    $data->initiator_id= Auth::user()->id;
    $data->division_id = $request->division_id;
    $data->record_number = $request->record_number;
    $data->date_of_initiation = $request->intiation_date;
    $data->assign_to= $request->assign_to;
    $data->due_date_gi = $request->due_date_gi;
    $data->short_description= $request->short_description;
    $data->registration_type_gi = $request->registration_type_gi;
    // $data->file_attachment_gi = $request->file_attachment_gi;
    $data->parent_record_number = $request->parent_record_number;
    $data->local_record_number = $request->local_record_number;
    $data->zone_departments  = $request->zone_departments;
    $data->country_number = $request->country_number;
    $data->regulatory_departments = $request->regulatory_departments;
    $data->registration_number = $request->registration_number;
    $data->risk_based_departments = $request->risk_based_departments;
    $data->device_approval_departments = $request->device_approval_departments;
    $data->marketing_auth_number = $request->marketing_auth_number;
    $data->manufacturer_number = $request->manufacturer_number;
    $data->audit_agenda_grid = $request->audit_agenda_grid;
    $data->manufacturing_description = $request->manufacturing_description;
    $data->dossier_number = $request->dossier_number;
    $data->dossier_departments = $request->dossier_departments;
    $data->description = $request->description;
    $data->planned_submission_date = $request->planned_submission_date;
    $data->actual_submission_date = $request->actual_submission_date;
    $data->actual_approval_date = $request->actual_approval_date;
    $data->actual_rejection_date = $request->actual_rejection_date;
    $data->renewal_departments = $request->renewal_departments;
    $data->next_renewal_date = $request->next_renewal_date;

    if (!empty ($request->Audit_file)) {
        $files = [];
        if ($request->hasfile('Audit_file')) {
            foreach ($request->file('Audit_file') as $file) {
                $name = $request->name . 'Audit_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                $file->move('upload/', $name);
                $files[] = $name;
            }
        }


        $data->file_attachment_gi = json_encode($files);
    }

    // if (!empty ($request->file_attachment_gi)) {
    //     $files = [];

    //     if ($request->hasfile('file_attachment_gi')) {
    //         foreach ($request->file('file_attachment_gi') as $file) {
    //             $name = time() . '_' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
    //             $file->move(public_path('upload'), $name);
    //             $files[] = $name;
    //         }
    //     }

    //     $data->file_attachment_gi = json_encode($files);
    // }

    $data->save();
    //  dd($data);

    $packagedetailgrid = $data->id;
    $packagegrid = Medical_Device_Grid::where(['mdg_id' => $packagedetailgrid,'identifier'=>'Packaging Information '])->firstOrNew();
    $packagegrid->mdg_id = $packagedetailgrid;
    $packagegrid->identifier = 'Packaging Information ';
    $packagegrid->data = $request->packagedetail;
    $packagegrid->save();


    toastr()->success("Medical_Device _Grid is created succusfully");
    return redirect(url('rcms/qms-dashboard'));  
    
   }

   public function medicalEdit($id) {
    $data = MedicalDeviceRegistration::findOrFail($id);
    $gridData = Medical_Device_Grid::where('mdg_id', $id)->first();

    return view('frontend.New_forms.update_medical_registration', compact('data', 'gridData'));
}


public function medicalUpdate(Request $request, $id) {
    $data = MedicalDeviceRegistration::findOrFail($id);
    $data->initiator_id= Auth::user()->id;
    $data->division_id = $request->division_id;
    $data->record_number = $request->record_number;
    $data->date_of_initiation = $request->intiation_date;
    $data->assign_to= $request->assign_to;
    $data->due_date_gi = $request->due_date_gi;
    $data->short_description= $request->short_description;
    $data->registration_type_gi = $request->registration_type_gi;
    // $data->file_attachment_gi = $request->file_attachment_gi;
    $data->parent_record_number = $request->parent_record_number;
    $data->local_record_number = $request->local_record_number;
    $data->zone_departments  = $request->zone_departments;
    $data->country_number = $request->country_number;
    $data->regulatory_departments = $request->regulatory_departments;
    $data->registration_number = $request->registration_number;
    $data->risk_based_departments = $request->risk_based_departments;
    $data->device_approval_departments = $request->device_approval_departments;
    $data->marketing_auth_number = $request->marketing_auth_number;
    $data->manufacturer_number = $request->manufacturer_number;
    $data->audit_agenda_grid = $request->audit_agenda_grid;
    $data->manufacturing_description = $request->manufacturing_description;
    $data->dossier_number = $request->dossier_number;
    $data->dossier_departments = $request->dossier_departments;
    $data->description = $request->description;
    $data->planned_submission_date = $request->planned_submission_date;
    $data->actual_submission_date = $request->actual_submission_date;
    $data->actual_approval_date = $request->actual_approval_date;
    $data->actual_rejection_date = $request->actual_rejection_date;
    $data->renewal_departments = $request->renewal_departments;
    $data->next_renewal_date = $request->next_renewal_date;
    

    if (!empty ($request->Audit_file)) {
        $files = [];
        if ($request->hasfile('Audit_file')) {
            foreach ($request->file('Audit_file') as $file) {
                $name = $request->name . 'Audit_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                $file->move('upload/', $name);
                $files[] = $name;
            }
        }


        $data->file_attachment_gi = json_encode($files);
    }


    
    
    $data->save();

    $packagedetailgrid = $data->id;
    $packagegrid = Medical_Device_Grid::where([
        'mdg_id' => $packagedetailgrid,
        'identifier' => 'Packaging Information '
    ])->firstOrNew();

    $packagegrid->mdg_id = $packagedetailgrid;
    $packagegrid->identifier = 'Packaging Information ';
    $packagegrid->data = $request->input('packagedetail');
    $packagegrid->save();

    return redirect()->back()->with('success', 'Medical Device Registration is Successfully Updated');
}

    
}
