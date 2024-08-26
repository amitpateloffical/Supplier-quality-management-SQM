<?php

use App\Models\ActionItem;
use App\Models\Division;
use App\Models\QMSDivision;
use App\Models\QMSProcess;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class Helpers
{
    // public static function getdateFormat($date)
    // {
    //     $date = Carbon::parse($date);
    //     $formatted_date = $date->format("d-M-Y");
    //     return $formatted_date;
    // }
    public static function getdateFormat($date, $usingFormat = false)
        {
            try {

                if(empty($date)) {
                    return ''; // or any default value you prefer
                }
                else{
                    if ($usingFormat) {
                        $date = Carbon::createFromFormat('d/m/Y', $date);
                    } else {
                        $date = Carbon::parse($date);
                    }
                    $formatted_date = $date->format("d-M-Y");
                    return $formatted_date;
                }
            } catch (\Exception $e) {
                return "-";
            }

        }

        public static function getdateFormatNew($date)
        {
            if(empty($date)) {
                return ''; // or any default value you prefer
            }
            // else{
            else{
                $date = Carbon::parse($date);
                $formatted_date = $date->format("d-M-Y");
                return $formatted_date;
            }

        }

static function getFullDepartmentName($code)
{
    $full_department_name = '';

    switch ($code) {
        case 'CQA':
            $full_department_name = "Corporate Quality Assurance";
            break;
        case 'QA':
                $full_department_name = "Quality Assurance";
                break;
        case 'QAB':
            $full_department_name = "Quality Assurance Biopharma";
            break;
        case 'CQC':
            $full_department_name = "Central Quality Control";
            break;
        case 'MANU':
            $full_department_name = "Manufacturing";
            break;
        case 'PSG':
            $full_department_name = "Plasma Sourcing Group";
            break;
        case 'CS':
            $full_department_name = "Central Stores";
            break;
        case 'ITG':
            $full_department_name = "Information Technology Group";
            break;
        case 'MM':
            $full_department_name = "Molecular Medicine";
            break;
        case 'CL':
            $full_department_name = "Central Laboratory";
            break;
        case 'TT':
            $full_department_name = "Tech team";
            break;
        case 'ACC':
            $full_department_name = "Accounting";
            break;
        case 'LOG':
            $full_department_name = "Logistics";
            break;
        case 'SM':
            $full_department_name = "Senior Management";
            break;
        case 'BA':
            $full_department_name = "Business Administration";
            break;

        default:
            break;
    }

    return $full_department_name;

}
    public static function getdateFormat1($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-M-Y');
    }

    public static function isRevised($data)
    {
        if($data  >= 8 ){
            return 'disabled';
        }else{
            return  '';
        }

    }

     /************* Get Roles List Ends ***************/

    public static function getHodUserList($division = null){
        if (!$division) {
            return $hodUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '4'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '4', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }
    public static function getQAUserList($division = null){
        if (!$division) {
            return $QAUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '7'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '7', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getInitiatorUserList($division = null){        
        if (!$division) {
            return $InitiatorUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '3'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '3', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getCqaDepartmentList($division = null) {
        if (!$division) {
            return $InitiatorUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '35'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '35', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getPurchaseDepartmentList($division = null) {
        if (!$division) {
            return $PurchaseDepartmentList = DB::table('user_roles')->where(['q_m_s_roles_id' => '34'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '34', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getFormulationDepartmentList($division = null) {
        if (!$division) {
            return $FormulationDepartmentList = DB::table('user_roles')->where(['q_m_s_roles_id' => '36'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '36', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getSupplierAuditorDepartmentList($division = null) {
        if (!$division) {
            return $SupplierAuditorDepartmentList = DB::table('user_roles')->where(['q_m_s_roles_id' => '37'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '37', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getSupplierContactDepartmentList($division = null) {
        if (!$division) {
            return $SupplierContactDepartmentList = DB::table('user_roles')->where(['q_m_s_roles_id' => '38'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '38', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getAuditManagerDepartmentList($division = null) {
        if (!$division) {
            return $AuditManagerDepartmentList = DB::table('user_roles')->where(['q_m_s_roles_id' => '13'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '13', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getAuditeeDepartmentList($division = null) {
        if (!$division) {
            return $AuditeeDepartmentList = DB::table('user_roles')->where(['q_m_s_roles_id' => '39'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '39', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getBusinessRuleengineDeptList($division = null) {
        if (!$division) {
            return $BusinessRuleengineDeptList = DB::table('user_roles')->where(['q_m_s_roles_id' => '40'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '40', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getWorkGroupUserList($division = null){
        if (!$division) {
            return $WorkGroupUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '16'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '16', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getCFTUserList($division = null){
        if (!$division) {
            return $CFTUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '5'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '5', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getQAHeadUserList($division = null){
        if (!$division) {
            return $QAHeadUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '9'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '9', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getAuditorsList($division = null){
        if (!$division) {
            return $AuditorsList = DB::table('user_roles')->where(['q_m_s_roles_id' => '41'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '41', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getAuditeesList($division = null){
        if (!$division) {
            return $AuditeesList = DB::table('user_roles')->where(['q_m_s_roles_id' => '42'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '42', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getQualityList($division = null){
        if (!$division) {
            return $QualityList = DB::table('user_roles')->where(['q_m_s_roles_id' => '43'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '43', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getQaReviewerList($division = null){
        if (!$division) {
            return $QaReviewerList = DB::table('user_roles')->where(['q_m_s_roles_id' => '44'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '44', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getActionOwnerUserList($division = null){
        if (!$division) {
            return $ActionOwnerUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '8'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '8', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getQaApproverList($division = null){
        if (!$division) {
            return $QaApproverList = DB::table('user_roles')->where(['q_m_s_roles_id' => '45'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '45', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getSupervisorUserList($division = null){
        if (!$division) {
            return $SupervisorUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '14'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '14', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getScarInitiatorList($division = null){
        if (!$division) {
            return $ScarInitiatorList = DB::table('user_roles')->where(['q_m_s_roles_id' => '46'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '46', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getVendorList($division = null){
        if (!$division) {
            return $VendorList = DB::table('user_roles')->where(['q_m_s_roles_id' => '47'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '47', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getViewUserList($division = null){
        if (!$division) {
            return $ViewUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '17'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '17', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getFPUserList($division = null){
        if (!$division) {
            return $FPUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '18'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '18', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getApproverUserList(){

        return $ApproverUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'1'])->get();
    }
    public static function getReviewerUserList(){

        return $ReviewerUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'2'])->get();
    }
    
    public static function getTrainerUserList(){

        return $TrainerUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'6'])->get();
    }  
    
    public static function getQCHeadUserList(){

        return $QCHeadUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'10'])->get();
    }
    public static function getLeadAuditeeUserList(){

        return $LeadAuditeeUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'11'])->get();
    }
    public static function getLeadAuditorUserList(){

        return $LeadAuditorUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'12'])->get();
    }    
    public static function getResponsibleUserList(){

        return $ResponsibleUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'15'])->get();
    }
    

    public static function checkRoles($role)
    {

        $userRoles = DB::table('user_roles')->where(['user_id' => Auth::user()->id])->get();
        $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
        if(in_array($role, $userRoleIds)){
            return true;
        }else{
            return false;
        }
        // if (strpos(Auth::user()->role, $role) !== false) {
        //    return true;
        // }else{
        //     return false;
        // }
    }


    public static function checkRoles_check_reviewers($document)
    {

        if ($document->reviewers) {
            $datauser = explode(',', $document->reviewers);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == Auth::user()->id) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }
    public static function showStage($parentType, $model, $count)
    {
        $existingRecordsCount = $model::where('parent_type', $parentType)->count();
        return $existingRecordsCount > $count;
    }

    public static function checkRoles_check_approvers($document)
    {
        if ($document->approvers) {
            $datauser = explode(',', $document->approvers);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == Auth::user()->id) {
                    if($document->stage >= 4){
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        } else {
            return false;
        }
    }

    public static function checkUserRolesApprovers($data)
    {
        if ($data->role) {
            $datauser = explode(',', $data->role);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == 1) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public static function checkUserRolesreviewer($data)
    {
        if ($data->role) {
            $datauser = explode(',', $data->role);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == 2) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public static function checkUserRolestrainer($data)
    {
        if ($data->role) {
            $datauser = explode(',', $data->role);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == 6) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public static function checkUserRolesassign_to($data)
    {
        if ($data->role) {
            $datauser = explode(',', $data->role);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == 4) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public static function checkUserRolesMicrobiology_Person($data)
    {
        if ($data->role) {
            $datauser = explode(',', $data->role);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == 5) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public static function divisionNameForQMS($id)
    {
        return QMSDivision::where('id', $id)->value('name');
    }

    public static function year($createdAt)
    {
        return Carbon::parse($createdAt)->format('Y');
    }

    public static function getDivisionName($id)
    {
        $name = DB::table('q_m_s_divisions')->where('id', $id)->where('status', 1)->value('name');
        return $name;
    }
    public static function recordFormat($number)
    {
        return   str_pad($number, 4, '0', STR_PAD_LEFT);
    }
    public static function getInitiatorName($id)
    {
        return   User::where('id',$id)->value('name');
    }
    public static function record($id)
    {
        return   str_pad($id, 5, '0', STR_PAD_LEFT);
    }
    public static function getDepartmentWithString($id)
    {
        $response = [];
        if(!empty($id)){
            $response = explode(',',$id);
        }
        return $response;
    }
    public static function getInitiatorEmail($id)
    {

        return   DB::table('users')->where('id',$id)->value('email');
    }
    public static function getDepartmentNameWithString($id)
    {
        $response = [];
        $resp = [];
        if(!empty($id)){
            $result = explode(',',$id);
            if(in_array(1,$result)){
                array_push($response, 'QA');
            }
            if(in_array(2,$result)){
                array_push($response, 'QC');
            }
            if(in_array(3,$result)){
                array_push($response, 'R&D');
            }
            if(in_array(4,$result)){
                array_push($response, 'Manufacturing');
            }
            if(in_array(5,$result)){
                array_push($response, 'Warehouse');
            }
            $resp = implode(',',$response);
        }
        return $resp;
    }

public static function getInitiatorGroupFullName($shortName)
    {

        switch ($shortName) {
            case 'CQA':
                return 'Corporate Quality Assurance';
                break;
            case 'QAB':
                return 'Quality Assurance Biopharma';
                break;
            case 'CQC':
                return 'Central Quality Control';
                break;
            case 'MANU':
                return 'Manufacturing';
                break;
            case 'PSG':
                return 'Plasma Sourcing Group';
                break;
            case 'CS':
                return 'Central Stores';
                break;
            case 'ITG':
                return 'Information Technology Group';
                break;
            case 'MM':
                return 'Molecular Medicine';
                break;
            case 'CL':
                return 'Central Laboratory';
                break;
            case 'TT':
                return 'Tech Team';
                break;
            case 'QA':
                return 'Quality Assurance';
                break;
            case 'QM':
                return 'Quality Management';
                break;
            case 'IA':
                return 'IT Administration';
                break;
            case 'ACC':
                return 'Accounting';
                break;
            case 'LOG':
                return 'Logistics';
                break;
            case 'SM':
                return 'Senior Management';
                break;
            case 'BA':
                return 'Business Administration';
                break;
            default:
                return '';
                break;
        }
    }
// }


    public static function hodMail($data)
    {
        try {
            Mail::send('hod-mail',['data' => $data],
            function ($message){
            $message->to("shaleen.mishra@mydemosoftware.com")
                    ->subject('Record is for Review');
            });
        } catch (\Exception $e) {
            //
        }
    }

    public static function getHODDropdown() {
        $hodUserList = DB::table('user_roles')
            ->join('users', 'user_roles.user_id', '=', 'users.id')
            ->where('user_roles.q_m_s_roles_id', '4')
            ->select('users.id', 'users.name')
            ->distinct()
            ->get();

        $dropdown = [];
        foreach ($hodUserList as $hodUser) {
            $dropdown[] = ['id' => $hodUser->id, 'name' => $hodUser->name];
        }

        return $dropdown;
    }
    public static function getQADropdown() {
        $QAUserList = DB::table('user_roles')
            ->join('users', 'user_roles.user_id', '=', 'users.id')
            ->where('user_roles.q_m_s_roles_id', '7')
            ->select('users.id', 'users.name')
            ->distinct()
            ->get();

        $dropdown = [];
        foreach ($QAUserList as $QAUser) {
            $dropdown[] = ['id' => $QAUser->id, 'name' => $QAUser->name];
        }

        return $dropdown;
    }

    public static function check_roles($division_id, $process_name, $role_id, $user_id = null)
    {

        $process = QMSProcess::where([
            'division_id' => $division_id,
            'process_name' => $process_name
        ])->first();

        $roleExists = DB::table('user_roles')->where([
            'user_id' => $user_id ? $user_id : Auth::user()->id,
            'q_m_s_divisions_id' => $division_id,
            'q_m_s_processes_id' => $process ? $process->id : 0,
            'q_m_s_roles_id' => $role_id
        ])->first();

        return $roleExists ? true : false;
    }
}
