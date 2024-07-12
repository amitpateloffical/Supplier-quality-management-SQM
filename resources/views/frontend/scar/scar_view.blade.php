@extends('frontend.layout.main')
@section('container')
@php 
$users = DB::table('users')->select('id', 'name')->get();
@endphp
<style>
    textarea.note-codable {
        display: none !important;
    }

    header {
        display: none;
    }
</style>
    <style>
        textarea.note-codable {
            display: none !important;
        }

        header {
            display: none;
        }
    </style>
    <style>
        .progress-bars div {
            flex: 1 1 auto;
            border: 1px solid grey;
            padding: 5px;
            text-align: center;
            position: relative;
            /* border-right: none; */
            background: white;
        }

        .state-block {
            padding: 20px;
            margin-bottom: 20px;
        }

        .progress-bars div.active {
            background: green;
            font-weight: bold;
        }

        #change-control-fields>div>div.inner-block.state-block>div.status>div.progress-bars.d-flex>div:nth-child(1) {
            border-radius: 20px 0px 0px 20px;
        }

        #change-control-fields>div>div.inner-block.state-block>div.status>div.progress-bars.d-flex>div:nth-child(6) {
            border-radius: 0px 20px 20px 0px;

        }
    </style>
    <style>
        textarea.note-codable {
            display: none !important;
        }

        header {
            display: none;
        }

        .sub-main-head {
            display: flex;
            justify-content: space-evenly;
        }

        .Activity-type {
            margin-bottom: 7px;
        }

        /* .sub-head {
            margin-left: 280px;
            margin-right: 280px;
            color: #4274da;
            border-bottom: 2px solid #4274da;
            padding-bottom: 5px;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 1.2rem;

        } */
        .launch_extension {
            background: #4274da;
            color: white;
            border: 0;
            padding: 4px 15px;
            border: 1px solid #4274da;
            transition: all 0.3s linear;
        }
        .main_head_modal li {
            margin-bottom: 10px;
        }

        .extension_modal_signature {
            display: block;
            width: 100%;
            border: 1px solid #837f7f;
            border-radius: 5px;
        }

        .main_head_modal {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .create-entity {
            background: #323c50;
            padding: 10px 15px;
            color: white;
            margin-bottom: 20px;

        }

        .bottom-buttons {
            display: flex;
            justify-content: flex-end;
            margin-right: 300px;
            margin-top: 50px;
            gap: 20px;
        }

        .text-danger {
            margin-top: -22px;
            padding: 4px;
            margin-bottom: 3px;
        }

        /* .saveButton:disabled{
                background: black!important;
                border:  black!important;

            } */

        .main-danger-block {
            display: flex;
        }

        .swal-modal {
            scale: 0.7 !important;
        }

        .swal-icon {
            scale: 0.8 !important;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="form-field-head">
    <div class="division-bar">
        <strong>Site Division/Project</strong> :
        {{ Helpers::getDivisionName($data->division_id) }} / SCAR 
    </div>
</div>

<div id="change-control-fields">
    <div class="container-fluid">

    <div class="inner-block state-block">
            <div class="d-flex justify-content-between align-items-center">
                <div class="main-head">Record Workflow </div>

                <div class="d-flex" style="gap:20px;">
                    @php
                        $userRoles = DB::table('user_roles')
                            ->where(['user_id' => Auth::user()->id, 'q_m_s_divisions_id' => 1])
                            ->get();
                        $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
                    @endphp
                    <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/scar-audit-trail', $data->id) }}"> Audit Trail </a> </button>

                    @if ($data->stage == 1 && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)))
                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                            Submit
                        </button>
                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                            Cancel
                        </button>
                    @elseif($data->stage == 2 && (in_array(4, $userRoleIds) || in_array(18, $userRoleIds)))
                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                            Acknowledged
                        </button>
                    @elseif($data->stage == 3 && (in_array(7, $userRoleIds) || in_array(18, $userRoleIds)))
                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                            Work in Progress
                        </button>
                    @elseif($data->stage == 4 && (in_array(7, $userRoleIds) || in_array(18, $userRoleIds)))
                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                            Submit Response
                        </button>
                    @elseif($data->stage == 5 && (in_array(39, $userRoleIds) || in_array(18, $userRoleIds)))
                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#reject-modal">
                            Reject
                        </button>
                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                            Approve
                        </button>
                    @endif
                    <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                </div>

            </div>
            <div class="status">
                <div class="head">Current Status</div>
                @if ($data->stage == 0)
                    <div class="progress-bars">
                        <div class="bg-danger">Closed-Cancelled</div>
                    </div>
                @else
                    <div class="progress-bars d-flex" style="font-size: 15px;">
                        @if ($data->stage >= 1)
                            <div class="active">Opened</div>
                        @else
                            <div class="">Opened</div>
                        @endif

                        @if ($data->stage >= 2)
                            <div class="active">Submitted to Supplier</div>
                        @else
                            <div class="">Submitted to Supplier</div>
                        @endif

                        @if ($data->stage >= 3)
                            <div class="active">Acknowleged by Supplier</div>
                        @else
                            <div class="">Acknowleged by Supplier</div>
                        @endif

                        @if ($data->stage >= 4)
                            <div class="active">Work in Progress</div>
                        @else
                            <div class="">Work in Progress</div>
                        @endif

                        @if ($data->stage >= 5)
                            <div class="active">Response Received</div>
                        @else
                            <div class="">Response Received</div>
                        @endif

                        @if ($data->stage >= 6)
                            <div class="active bg-danger">Closed - Approved</div>
                        @else
                            <div class="">Closed - Approved</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Activity Log</button>
        </div>

        <form action="{{ route('scar-update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="step-form">
                <div id="CCForm1" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="sub-head">
                                General Information
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="SCAR Record Number"><b>Record Number</b></label>
                                    <input type="text" disabled value="{{ Helpers::getDivisionName($data->division_id) }}/SCAR/{{ date('Y') }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}">

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Division"><b>Division</b></label>
                                    <input disabled type="text" value="{{ Helpers::getDivisionName($data->division_id) }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Initiator</b></label>
                                    <input disabled type="text" value="{{ Helpers::getInitiatorName($data->initiator_id) }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiation Date"><b>Initiation Date</b></label>
                                    <input disabled type="text" value="{{ Helpers::getdateFormat($data->initiation_date) }}">
                                </div>
                            </div>
                        
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Due Date">Date Due</label>
                                    <div class="calenderauditee">
                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" readonly placeholder="DD-MM-YYYY" value="{{ Helpers::getdateFormat($data->due_date) }}"/>
                                        <!-- <input type="date" min="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" class="hide-input" oninput="handleDateInput(this, 'due_date')" /> -->
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Assign To">Assigned To <span class="text-danger"></span>
                                    </label>
                                    <select id="assign_to" name="assign_to" @if($data->stage >= 6) disabled @endif>
                                        <option value="">Select a value</option>
                                        @if(!empty($users))
                                            @foreach($users as $user)
                                                <option value="{{$user->id }}" @if($data->assign_to == $user->id) selected @endif>{{ $user->name }}</option>
                                            @endforeach
                                        @endif                                    
                                    </select>
                                </div>
                            </div>
                       
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Short Description">Short Description<span class="text-danger">*</span></label><span id="rchars">255</span>
                                    characters remaining
                                    <input id="docname" type="text" name="short_description" value="{{ $data->short_description }}" maxlength="255" required @if($data->stage >= 6) disabled @endif>
                                </div>
                            </div>
                            <script>
                                var maxLength = 255;
                                $('#docname').keyup(function() {
                                    var textlen = maxLength - $(this).val().length;
                                    $('#rchars').text(textlen);});
                            </script>
                             
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="SCAR Name">SCAR Name</label>
                                    <input type="text" value="{{ $data->scar_name }}" name="scar_name" placeholder="Enter SCAR Name" @if($data->stage >= 6) disabled @endif>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Owner">Owner</label>
                                    <input type="text" value="{{ $data->owner_name }}" name="owner_name" placeholder="Enter Owner Name" @if($data->stage >= 6) disabled @endif>
                                </div>
                            </div>


                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Follow Up Date">Follow Up Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="followup_date" placeholder="DD-MM-YYYY"  value="{{ Helpers::getdateFormat($data->followup_date) }}" @if($data->stage >= 6) disabled @endif/>
                                        <input type="date" name="followup_date" value="{{ $data->followup_date }}" min="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" class="hide-input" oninput="handleDateInput(this, 'followup_date')" @if($data->stage >= 6) disabled @endif/>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Site">Supplier Site</label>
                                    <select name="supplier_site" @if($data->stage >= 6) disabled @endif>
                                        <option value="">Select Supplier Site</option>
                                        @if(!empty($scarData))
                                            @foreach($scarData as $supplier)
                                                <option value="{{ $supplier->distribution_sites }}" @if($data->supplier_site == $supplier->distribution_sites) selected @endif>{{ $supplier->distribution_sites }}</option>
                                            @endforeach
                                        @else
                                            <option value="">N/A</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Product">Supplier Product</label>
                                    <select name="supplier_product" @if($data->stage >= 6) disabled @endif>
                                        <option value="">Select Supplier Product</option>
                                        @if(!empty($scarData))
                                            @foreach($scarData as $supplier)
                                                <option value="{{ $supplier->supplier_products }}" @if($data->supplier_product == $supplier->supplier_products) selected @endif>{{ $supplier->supplier_products }}</option>
                                            @endforeach
                                        @else
                                            <option value="">N/A</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Site Contact Email">Supplier Site Contact Email</label>
                                    <input type="text" name="supplier_site_contact_email" value="{{ $data->supplier_site_contact_email }}" @if($data->stage >= 6) disabled @endif>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Description">Description</label>
                                   <textarea name="description" id="description" cols="30" value="{{ $data->description }}" @if($data->stage >= 6) disabled @endif>{{$data->description}}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Recommended Action">Recommended Action</label>
                                   <textarea id="recommended_action" cols="30" name="recommended_action" value="{{ $data->recommended_action }}" @if($data->stage >= 6) disabled @endif>{{ $data->recommended_action }}</textarea>
                                </div>
                            </div>

                            <div class="sub-head">
                                Supplier Response
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Non Conformance">Non Conformance</label>
                                    <textarea id="non_conformance" cols="30" name="non_conformance" value="{{ $data->non_conformance }}" @if($data->stage >= 6) disabled @endif>{{ $data->non_conformance }}</textarea>
                                    <!-- <select name="non_conformance">
                                        <option value="">Enter Your Selection Here</option>
                                    </select> -->
                                </div>
                            </div>


                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Expected Closure Date">Expected Closure Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="expected_closure_date" placeholder="DD-MM-YYYY" value="{{ Helpers::getdateFormat($data->expected_closure_date) }}" @if($data->stage >= 6) disabled @endif/>
                                        <input type="date" name="expected_closure_date" value="{{ $data->expected_closure_date }}" min="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" class="hide-input" oninput="handleDateInput(this, 'expected_closure_date')" @if($data->stage >= 6) disabled @endif/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Expected Closure Time">Expected Closure Time</label>
                                   <input type="time" name="expected_closure_time" value="{{ $data->expected_closure_time }}" @if($data->stage >= 6) disabled @endif>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Root Cause">Root Cause</label>
                                   <textarea id="root_cause" cols="30" name="root_cause" value="{{ $data->root_cause }}" @if($data->stage >= 6) disabled @endif>{{ $data->root_cause }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Risk Analysis">Risk Analysis</label>
                                   <textarea cols="30" id="risk_analysis" name="risk_analysis" value="{{ $data->risk_analysis }}" @if($data->stage >= 6) disabled @endif>{{ $data->risk_analysis }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Effectiveness Check Summary">Effectiveness Check Summary</label>
                                   <textarea cols="30" name="effectiveness_check_summary" value="{{ $data->effectiveness_check_summary }}" id="effectiveness_check_summary" @if($data->stage >= 6) disabled @endif>{{ $data->effectiveness_check_summary }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="CAPA Plan">CAPA Plan</label>
                                   <textarea id="capa_plan" cols="30" name="capa_plan" value="{{ $data->capa_plan }}" @if($data->stage >= 6) disabled @endif>{{ $data->capa_plan }}</textarea>
                                </div>
                            </div>
                           
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit</a></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Signature content -->
                <div id="CCForm2" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Submitted By">Submitted By</label>
                                    <div class="static">{{ $data->submitted_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Submitted On">Submitted On</label>
                                    <div class="static">{{ $data->submitted_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Submitted Comment">Submitted Comment</label>
                                    <div class="static">{{ $data->submitted_comment }}</div>
                                </div>
                            </div>


                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Cancelled By</label>
                                    <div class="static">{{ $data->cancelled_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Cancelled On</label>
                                    <div class="static">{{ $data->cancelled_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Cancelled Comment</label>
                                    <div class="static">{{ $data->cancelled_comment }}</div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Acknowledge By</label>
                                    <div class="static">{{ $data->acknowledge_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Acknowledge On</label>
                                    <div class="static">{{ $data->acknowledge_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Acknowledge Comment</label>
                                    <div class="static">{{ $data->acknowledge_comment }}</div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Work in Progress By</label>
                                    <div class="static">{{ $data->workin_progress_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Work in Progress On</label>
                                    <div class="static">{{ $data->workin_progress_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Work in Progress Comment</label>
                                    <div class="static">{{ $data->workin_progress_comment }}</div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Response Submitted By</label>
                                    <div class="static">{{ $data->response_submitted_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Response Submitted On</label>
                                    <div class="static">{{ $data->response_submitted_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Response Submitted Comment</label>
                                    <div class="static">{{ $data->response_submitted_comment }}</div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Rejected By</label>
                                    <div class="static">{{ $data->rejected_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Rejected On</label>
                                    <div class="static">{{ $data->rejected_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Rejected Comment</label>
                                    <div class="static">{{ $data->rejected_comment }}</div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Approved By</label>
                                    <div class="static">{{ $data->approved_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Approved On</label>
                                    <div class="static">{{ $data->approved_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Approved Comment</label>
                                    <div class="static">{{ $data->approved_comment }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>  
        
            <!-- Forword Stage Modal -->
            <div class="modal fade" id="signature-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/scar-send-stage', $data->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3 text-justify">
                                    Please select a meaning and a outcome for this task and enter your username
                                    and password for this task. You are performing an electronic signature,
                                    which is legally binding equivalent of a hand written signature.
                                </div>
                                <div class="group-input">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" required>
                                </div>
                                <div class="group-input">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required>
                                </div>
                                <div class="group-input">
                                    <label for="comment">Comment</label>
                                    <input type="comment" name="comments">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Cancel Modal -->
            <div class="modal fade" id="cancel-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/scar-close-cancelled', $data->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3 text-justify">
                                    Please select a meaning and a outcome for this task and enter your username
                                    and password for this task. You are performing an electronic signature,
                                    which is legally binding equivalent of a hand written signature.
                                </div>
                                <div class="group-input">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" required>
                                </div>
                                <div class="group-input">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required>
                                </div>
                                <div class="group-input">
                                    <label for="comment">Comment <span class="text-danger">*</span></label>
                                    <input type="comment" name="comments" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="reject-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/scar-reject-stage', $data->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3 text-justify">
                                    Please select a meaning and a outcome for this task and enter your username
                                    and password for this task. You are performing an electronic signature,
                                    which is legally binding equivalent of a hand written signature.
                                </div>
                                <div class="group-input">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" required class="form-control">
                                </div>
                                <div class="group-input mt-3">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required class="form-control">
                                </div>
                                <div class="group-input mt-3">
                                    <label for="comment">Comment <span class="text-danger">*</span></label>
                                    <input type="comment" name="comments" required class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <style>
                #step-form>div {
                    display: none
                }

                #step-form>div:nth-child(1) {
                    display: block;
                }
            </style>

            <script>
                VirtualSelect.init({
                    ele: '#related_records, #hod'
                });

                function openCity(evt, cityName) {
                    var i, cctabcontent, cctablinks;
                    cctabcontent = document.getElementsByClassName("cctabcontent");
                    for (i = 0; i < cctabcontent.length; i++) {
                        cctabcontent[i].style.display = "none";
                    }
                    cctablinks = document.getElementsByClassName("cctablinks");
                    for (i = 0; i < cctablinks.length; i++) {
                        cctablinks[i].className = cctablinks[i].className.replace(" active", "");
                    }
                    document.getElementById(cityName).style.display = "block";
                    evt.currentTarget.className += " active";
                    const index = Array.from(cctablinks).findIndex(button => button === evt.currentTarget);
                    currentStep = index;
                }

                const saveButtons = document.querySelectorAll(".saveButton");
                const nextButtons = document.querySelectorAll(".nextButton");
                const form = document.getElementById("step-form");
                const stepButtons = document.querySelectorAll(".cctablinks");
                const steps = document.querySelectorAll(".cctabcontent");
                let currentStep = 0;

                function nextStep() {
                    if (currentStep < steps.length - 1) {
                        steps[currentStep].style.display = "none";
                        steps[currentStep + 1].style.display = "block";
                        stepButtons[currentStep + 1].classList.add("active");
                        stepButtons[currentStep].classList.remove("active");
                        currentStep++;
                    }
                }

                function previousStep() {
                    if (currentStep > 0) {
                        steps[currentStep].style.display = "none";
                        steps[currentStep - 1].style.display = "block";
                        stepButtons[currentStep - 1].classList.add("active");
                        stepButtons[currentStep].classList.remove("active");
                        currentStep--;
                    }
                }
            </script>

            <script>
                var maxLength = 255;
                $('#docname').keyup(function() {
                    var textlen = maxLength - $(this).val().length;
                    $('#rchars').text(textlen);
                });
            </script>
            @endsection