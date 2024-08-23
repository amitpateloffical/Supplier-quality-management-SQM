
@extends('frontend.rcms.layout.main_rcms')
@section('rcms_container')

    <style>
        #step-form>div {
            display: none
        }

        #step-form>div:nth-child(1) {
            display: block;
        }
        .hide-input{
            display: none !important;
        }
    </style>
    <style>
        header .header_rcms_bottom {
            display: none;
        }

        .calenderauditee {
            position: relative;
        }

        .new-date-data-field .input-date input.hide-input {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
        }

        .new-date-data-field input {
            border: 1px solid grey;
            border-radius: 5px;
            padding: 5px 15px;
            display: block;
            width: 100%;
            background: white;
        }

        .calenderauditee input::-webkit-calendar-picker-indicator {
            width: 100%;
        }
    </style>

    <script>
        function otherController(value, checkValue, blockID) {
            let block = document.getElementById(blockID)
            let blockTextarea = block.getElementsByTagName('textarea')[0];
            let blockLabel = block.querySelector('label span.text-danger');
            if (value === checkValue) {
                blockLabel.classList.remove('d-none');
                blockTextarea.setAttribute('required', 'required');
            } else {
                blockLabel.classList.add('d-none');
                blockTextarea.removeAttribute('required');
            }
        }
    </script>
    <div id="rcms_form-head">
        <div class="container-fluid">
            <div class="inner-block">


                <div class="slogan">
                    <strong>Site Division / Project </strong>:
                    {{ Helpers::getDivisionName(session()->get('division')) }} / Change Control
                </div>
            </div>
        </div>
    </div>

    <div id="change-control-view">
        <div class="container-fluid">

            <div class="inner-block state-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="main-head">Record Workflow </div>

                    <div class="d-flex" style="gap:20px;">
                        @php
                        $userRoles = DB::table('user_roles')->where(['user_id' => Auth::user()->id, 'q_m_s_divisions_id' => $data->division_id])->get();
                        $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
                    @endphp

                        <button class="button_theme1"> <a class="text-white"
                                href="{{ url('rcms/audit-trial', $data->id) }}"> Audit Trail </a> </button>
                        {{-- @if ($data->stage >= 9)
                            <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/eCheck', $data->id) }}">
                                    Close Done </a> </button>
                        @endif --}}
                        @if ($data->stage == 1  && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Submit
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancel
                            </button>
                        @elseif($data->stage == 2  && (in_array(4, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                HOD Review Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                More Info-required
                            </button>
                        @elseif($data->stage == 3  && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Send to CFT/SME/QA Reviewers
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cft-modal">
                                CFT/SME/QA Review Not Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                More Information required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal1">
                                Child
                            </button>
                        @elseif($data->stage == 4  && (in_array(5, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Review Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 5  && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Implemented
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal1">
                                Child
                            </button>
                        @endif
                        <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit
                            </a> </button>
                    </div>

                </div>
                <div class="status">
                    <div class="head">Current Status</div>
                    @if ($data->stage == 0)
                        <div class="progress-bars">
                            <div class="bg-danger">Closed-Cancelled</div>
                        </div>
                    @else
                        <div class="progress-bars">
                            @if ($data->stage >= 1)
                                <div class="active">Opened</div>
                            @else
                                <div class="">Opened</div>
                            @endif

                            @if ($data->stage >= 2)
                                <div class="active">HOD Review </div>
                            @else
                                <div class="">HOD Review </div>
                            @endif
                            
                            @if ($data->stage >= 3)
                                <div class="active">Pending CFT/SME/QA Review</div>
                            @else
                                <div class="">Pending CFT/SME/QA Review</div>
                            @endif

                            @if ($data->stage >= 4)
                                <div class="active"> CFT/SME/QA Review</div>
                            @else
                                <div class=""> CFT/SME/QA Review</div>
                            @endif

                            @if ($data->stage >= 5)
                                <div class="active">Pending Change Implementation</div>
                            @else
                                <div class="">Pending Change Implementation</div>
                            @endif

                            @if ($data->stage >= 6)
                                <div class="bg-danger">Closed - Done</div>
                            @else
                                <div class="">Closed - Done</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="control-list">
                @php
                    $users = DB::table('users')->get();
                @endphp
                <div id="change-control-fields">
                    <div class="container-fluid">
                        <!-- Tab links -->
                        <div class="cctab">
                            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General
                                Information</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Change Details</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">QA Review</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Evaluation</button>
                            {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Additional Information</button> --}}
                            <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Comments</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Risk Assessment</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm8')">QA Approval Comments</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm9')">Change Closure</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm10')">Activity Log</button>
                        </div>
                        <form id="CCFormInput" class="formSubmit" {{ route('CC.update', $data->id) }} method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Tab content -->
                            <div id="step-form">

                                <div id="CCForm1" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="rls">Record Number</label>
                                                    <div class="static">
                                                        <input disabled type="text"
                                                            value=" {{ Helpers::getDivisionName($data->division_id) }}/CC/{{ date('Y') }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Division Code"><b>Site/location Code</b></label>
                                                    <input disabled type="text" name="division_code" value=" {{ Helpers::getDivisionName($data->division_id) }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Initiator">Initiator</label>
                                                    <div class="static"><input disabled type="text" value="{{ Helpers::getInitiatorName($data->initiator_id) }}"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="date_initiation">Date of Initiation</label>
                                                    <div class="static"><input disabled type="text" value="{{ Helpers::getdateFormat($data->intiation_date) }}"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="group-input">
                                                    <label for="search">
                                                        Assigned To
                                                    </label>
                                                    <select placeholder="Select..." name="assign_to"  {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : ''}}>
                                                        <option value="">Select a value</option>
                                                            @foreach ($users as $datas)
                                                                @if(Helpers::checkUserRolesassign_to($datas))
                                                                    <option value="{{ $datas->id }}" @if ($data->assign_to == $datas->id) selected @endif> {{ $datas->name }}</option>
                                                                @endif    
                                                            @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Microbiology">CFT Reviewer</label>
                                                        <select name="cft_reviewer" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                            <option value="">-- Select --</option>
                                                            <option value="yes" {{ $data->cft_reviewer == 'yes' ? 'selected' : '' }}>Yes</option>
                                                            <option value="no" {{ $data->cft_reviewer == 'no' ? 'selected' : '' }}>No</option>
                                                        </select>
                                                    </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Microbiology-Person">CFT Reviewer Person</label>
                                                    <select multiple name="cft_reviewer_person[]"
                                                        placeholder="Select CFT Reviewers" data-search="false"
                                                        data-silent-initial-value-set="true" id="cft_reviewer" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                         <!-- <option value="">-- Select --</option> -->
                                                        @foreach ($cft as $data1)
                                                            <option value="{{ $data1->id }}" {{ in_array($data1->id, $cftReviewerIds) ? 'selected' : '' }}> {{$data1->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="group-input">
                                                    <label for="due-date">Due Date <span class="text-danger"></span></label>
                                                    <div><small class="text-primary">If revising Due Date, kindly mention revision reason in "Due Date Extension Justification" data field.</small></div>
                                                    <input readonly type="text"
                                                        value="{{ Helpers::getdateFormat($data->due_date) }}"
                                                        name="due_date" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}> 
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="initiator-group">Initiator Group</label>
                                                    <select name="Initiator_Group" id="initiator_group" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : ''}} >
                                                       <option >--select--</option>
                                                        <option value="CQA"
                                                            @if ($data->Initiator_Group == 'CQA') selected @endif>Corporate
                                                            Quality Assurance</option>
                                                        <option value="QAB"
                                                            @if ($data->Initiator_Group == 'QAB') selected @endif>Quality
                                                            Assurance Biopharma</option>
                                                        <option value="CQC"
                                                            @if ($data->Initiator_Group == 'CQC') selected @endif>Central
                                                            Quality Control</option>
                                                        <option value="MANU"
                                                            @if ($data->Initiator_Group == 'MANU') selected @endif>Manufacturing
                                                        </option>
                                                        <option value="PSG"
                                                            @if ($data->Initiator_Group == 'PSG') selected @endif>Plasma
                                                            Sourcing Group</option>
                                                        <option value="CS"
                                                            @if ($data->Initiator_Group == 'CS') selected @endif>Central
                                                            Stores</option>
                                                        <option value="ITG"
                                                            @if ($data->Initiator_Group == 'ITG') selected @endif>Information
                                                            Technology Group</option>
                                                        <option value="MM"
                                                            @if ($data->Initiator_Group == 'MM') selected @endif>Molecular
                                                            Medicine</option>
                                                        <option value="CL"
                                                            @if ($data->Initiator_Group == 'CL') selected @endif>Central
                                                            Laboratory</option>
                                                        <option value="TT"
                                                            @if ($data->Initiator_Group == 'TT') selected @endif>Tech
                                                            team</option>
                                                        <option value="QA"
                                                            @if ($data->Initiator_Group == 'QA') selected @endif>Quality
                                                            Assurance</option>
                                                        <option value="QM"
                                                            @if ($data->Initiator_Group == 'QM') selected @endif>Quality
                                                            Management</option>
                                                        <option value="IA"
                                                            @if ($data->Initiator_Group == 'IA') selected @endif>IT
                                                            Administration</option>
                                                        <option value="ACC"
                                                            @if ($data->Initiator_Group == 'ACC') selected @endif>Accounting
                                                        </option>
                                                        <option value="LOG"
                                                            @if ($data->Initiator_Group == 'LOG') selected @endif>Logistics
                                                        </option>
                                                        <option value="SM"
                                                            @if ($data->Initiator_Group == 'SM') selected @endif>Senior
                                                            Management</option>
                                                        <option value="BA"
                                                            @if ($data->Initiator_Group == 'BA') selected @endif>Business
                                                            Administration</option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Initiator Group Code">Initiator Group Code</label>
                                                    <input type="text" name="initiator_group_code"
                                                    value="{{ $data->initiator_group_code }}" id="initiator_group_code"
                                                    readonly {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="Short Description">Short Description<span
                                                            class="text-danger">*</span></label><span id="rchars"  class="text-primary">255 </span><span class="text-primary"> characters remaining</span>   
                                                    <div class="relative-container">
                                                        <input class="mic-input" name="short_description" id="docname" type="text" maxlength="255" required {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{ $data->short_description }}">
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                                <p id="docnameError" style="color:red">**Short Description is required</p>            
                                            </div> 

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="severity-level">Severity Level</label>
                                                    <span class="text-primary">Severity levels in a QMS record gauge issue seriousness, guiding priority for corrective actions. Ranging from low to high, they ensure quality standards and mitigate critical risks.</span>
                                                    <select name="severity_level1" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="">-- Select --</option>
                                                    <option @if ($data->severity_level1 == 'minor') selected @endif
                                                     value="minor">Minor</option>
                                                    <option  @if ($data->severity_level1 == 'major') selected @endif 
                                                    value="major">Major</option>
                                                    <option @if ($data->severity_level1 == 'critical') selected @endif
                                                    value="critical">Critical</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Initiator Group">Initiated Through</label>
                                                    <div><small class="text-primary">Please select related information</small></div>
                                                    <select name="initiated_through" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                        onchange="otherController(this.value, 'others', 'initiated_through_req')">
                                                        <option value="">Enter Your Selection Here</option>
                                                    <option @if ($data->initiated_through == 'recall') selected @endif
                                                        value="recall">Recall</option>
                                                    <option @if ($data->initiated_through == 'return') selected @endif
                                                        value="return">Return</option>
                                                    <option @if ($data->initiated_through == 'deviation') selected @endif
                                                        value="deviation">Deviation</option>
                                                    <option @if ($data->initiated_through == 'complaint') selected @endif
                                                        value="complaint">Complaint</option>
                                                    <option @if ($data->initiated_through == 'regulatory') selected @endif
                                                        value="regulatory">Regulatory</option>
                                                    <option @if ($data->initiated_through == 'lab-incident') selected @endif
                                                        value="lab-incident">Lab Incident</option>
                                                    <option @if ($data->initiated_through == 'improvement') selected @endif
                                                        value="improvement">Improvement</option>
                                                    <option @if ($data->initiated_through == 'others') selected @endif
                                                        value="others">Others</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input" id="initiated_through_req">
                                                    <label for="initiated_through">Others<span
                                                            class="text-danger d-none">*</span></label>
                                                            <div class="relative-container">
                                                                <textarea class="mic-input" name="initiated_through_req" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{$data->initiated_through_req }}</textarea>
                                                                @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                                 @endcomponent
                                                            </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="repeat">Repeat</label>
                                                    <div><small class="text-primary">Please select yes if it is has recurred in past six months</small></div>
                                                    <select name="repeat"
                                                        onchange="otherController(this.value, 'yes', 'repeat_nature')" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="" >Enter Your Selection Here</option>
                                                        <option @if ($data->repeat == 'yes') selected @endif
                                                            value="yes">Yes</option>
                                                        <option @if ($data->repeat == 'no') selected @endif
                                                            value="no">No</option>
                                                        <option @if ($data->repeat == 'na') selected @endif
                                                            value="na">NA</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input" id="repeat_nature">
                                                    <label for="repeat_nature">Repeat Nature<span
                                                            class="text-danger d-none">*</span></label>
                                                            <div class="relative-container">
                                                                <textarea class="mic-input" name="repeat_nature" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{$data->repeat_nature}}</textarea>
                                                                @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                                 @endcomponent
                                                            </div>
                                                    
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="nature-Change">Nature Of Change</label>
                                                    <select name="nature_Change" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="">-- Select --</option>
                                                        <option {{ $data->nature_Change == 'Temporary' ? 'selected' : '' }}
                                                            value="Temporary">Temporary
                                                        </option>
                                                        <option {{ $data->nature_Change == 'Permanent' ? 'selected' : '' }}
                                                            value="Permanent">Permanent
                                                        </option>
                                                    </select>                          
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="others">If Others</label>
                                                     <div class="relative-container">
                                                        <textarea class="mic-input" name="If_Others" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->If_Others}}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="group-input">
                                                    <label for="div_code">Division Code</label>
                                                    <select name="Division_Code" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="">-- Select --</option>
                                                        <option {{ $data->Division_Code == 'Instrumental Lab' ? 'selected' : '' }}
                                                            value="Instrumental Lab">Instrumental Lab</option>
                                                        <option {{ $data->Division_Code == 'Microbiology Lab' ? 'selected' : '' }}
                                                            value="Microbiology Lab"> Microbiology Lab</option>
                                                        <option {{ $data->Division_Code == 'Molecular lab' ? 'selected' : '' }}
                                                            value="Molecular lab"> Molecular lab</option>
                                                        <option {{ $data->Division_Code == 'Physical Lab' ? 'selected' : '' }}
                                                            value="Physical Lab"> Physical Lab</option>
                                                        <option {{ $data->Division_Code == 'Stability Lab' ? 'selected' : '' }}
                                                            value="Stability Lab"> Stability Lab</option>
                                                        <option {{ $data->Division_Code == 'Wet Chemistry' ? 'selected' : '' }}
                                                            value="Wet Chemistry"> Wet Chemistry</option>
                                                        {{-- <option {{ $data->Division_Code == 'IPQA Lab' ? 'selected' : '' }}
                                                            value="IPQA Lab"> IPQA Lab</option> --}}
                                                        <option {{ $data->Division_Code == 'Quality Department' ? 'selected' : '' }}
                                                            value="Quality Department">Quality Department</option>
                                                        <option {{ $data->Division_Code == 'Administration Department' ? 'selected' : '' }}
                                                            value="Administration Department">Administration Department</option>   
                                                    </select>
                                                </div>
                                            </div>
                                            @if ($data->in_attachment)
                                            @foreach (json_decode($data->in_attachment) as $file)
                                                <input id="initialFile-{{ $loop->index }}" type="hidden"
                                                    name="existing_in_attachment[{{ $loop->index }}]"
                                                    value="{{ $file }}">
                                            @endforeach
                                        @endif


                                            <div class="col-lg-12">
                                                <div class="group-input">
                                                    <label for="others">Initial attachment</label>
                                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                                    <div class="file-attachment-field">
                                                        <div disabled class="file-attachment-list" id="in_attachment">
                                                            @if ($data->in_attachment)
                                                                @foreach (json_decode($data->in_attachment) as $file)
                                                                    <h6 type="button" class="file-container text-dark"
                                                                        style="background-color: rgb(243, 242, 240);">
                                                                        <b>{{ $file }}</b>
                                                                        <a href="{{ asset('upload/' . $file) }}"
                                                                            target="_blank"><i
                                                                                class="fa fa-eye text-primary"
                                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                                        <a type="button" class="remove-file"
                                                                          data-remove-id="initialFile-{{ $loop->index }}"
                                                                            data-file-name="{{ $file }}"><i
                                                                                class="fa-solid fa-circle-xmark"
                                                                                style="color:red; font-size:20px;"></i></a>
                                                                    </h6>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="add-btn">
                                                            <div>Add</div>
                                                            <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file" id="myfile" name="in_attachment[]"
                                                                oninput="addMultipleFiles(this, 'in_attachment')" multiple>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-block">
                                            <button type="submit" class="saveButton on-submit-disable-button" @if($data->stage == 6) disabled @endif>Save</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit</a> </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="CCForm2" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            Change Details
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="doc-detail">
                                                        Document Details<button type="button" name="ann" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                            id="DocDetailbtn">+</button>
                                                    </label>
                                                    <table class="table-bordered table" id="doc-detail">
                                                        <thead>
                                                            <tr>
                                                                <th style='width:4%'>Sr. No.</th>
                                                                <th>Current Document No.</th>
                                                                <th>Current Version No.</th>
                                                                <th>New Document No.</th>
                                                                <th>New Version No.</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($docdetail->sno))
                                                                @foreach (unserialize($docdetail->current_doc_no) as $key => $datas)
                                                                    <tr>
                                                                        <td><input type="text" name="serial_number[]" disabled
                                                                                value="{{ $key ? $key + 1 : '1' }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}></td>
                                                                        <td><input type="text"
                                                                                name="current_doc_number[]"  {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                                value="{{ unserialize($docdetail->current_doc_no)[$key] ? unserialize($docdetail->current_doc_no)[$key] : '' }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                                        </td>
                                                                        <td><input type="text" name="current_version[]" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                                value="{{ unserialize($docdetail->current_version_no)[$key] ? unserialize($docdetail->current_version_no)[$key] : '' }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                                        </td>
                                                                        <td><input type="text" name="new_doc_number[]"  {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                                value="{{ unserialize($docdetail->new_doc_no)[$key] ? unserialize($docdetail->new_doc_no)[$key] : '' }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                                        </td>
                                                                        <td><input type="text" name="new_version[]"  {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                                value="{{ unserialize($docdetail->new_version_no)[$key] ? unserialize($docdetail->new_version_no)[$key] : '' }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                                        </td>
                                                                        <td>
                                                                            <button type="text"  {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} class="removeBtnDD">Remove</button>
                                                                        </td>

                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                            <div id="docdetaildiv"></div>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="current-practice">
                                                        Current Practice
                                                    </label>
                                                    <div class="relative-container">
                                                        <textarea name="current_practice" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->current_practice }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="proposed_change">
                                                        Proposed Change
                                                    </label>
                                                  <div class="relative-container">
                                                        <textarea name="proposed_change" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->proposed_change }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                 </div>
                                              </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="reason_change">
                                                        Reason for Change
                                                    </label>
                                                    <div class="relative-container">
                                                        <textarea name="reason_change" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->reason_change }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                            @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="other_comment">
                                                        Any Other Comments
                                                    </label>
                                                    <div class="relative-container">
                                                        <textarea name="other_comment" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->other_comment }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                                @endcomponent
                                                   </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="supervisor_comment">
                                                        Supervisor Comments
                                                    </label>
                                                    <div class="relative-container">
                                                        <textarea name="supervisor_comment" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->supervisor_comment }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                                @endcomponent
                                                   </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="button-block">
                                            <button type="submit"id="ChangesaveButton" class="saveButton on-submit-disable-button" @if($data->stage == 6) disabled @endif>Save</button>
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">Exit</a> </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="CCForm3" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="group-input">
                                                    <label for="type_change">Type of Change</label>
                                                    <select name="type_chnage" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="">-- Select --</option>
                                                        <option {{ $data->type_chnage == 'major' ? 'selected' : '' }}
                                                            value="major">Major</option>
                                                        <option {{ $data->type_chnage == 'minor' ? 'selected' : '' }}
                                                            value="minor">Minor</option>
                                                        <option {{ $data->type_chnage == 'critical' ? 'selected' : '' }}
                                                            value="critical">Critical</option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="qa_review_comments">QA Review Comments</label>
                                                    <div class="relative-container">
                                                        <textarea name="qa_review_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->qa_review_comments }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                  </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="related_records">Related Records</label>
                                                    <!-- <select {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} multiple id="related_records" name="related_records[]"
                                                        placeholder="Select Reference Records" data-search="false"
                                                        data-silent-initial-value-set="true" id="related_records">
                                                        @foreach ($pre as $prix)
                                                            <option value="{{ $prix->id }}" {{ in_array($prix->id, explode(',', $data->related_records)) ? 'selected' : '' }}>
                                                                {{ Helpers::getDivisionName($prix->division_id) }}/Change-Control/{{ Helpers::year($prix->created_at) }}/{{ Helpers::record($prix->record) }}
                                                            </option>
                                                        @endforeach
                                                    </select> -->

                                                    <select {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} multiple id="related_records" name="related_records[]">

                                                        @foreach ($pre as $new)
                                                            @php
                                                                $recordValue =
                                                                    Helpers::getDivisionName($new->division_id) .
                                                                    '/CC/' .
                                                                    date('Y') .
                                                                    '/' .
                                                                    Helpers::recordFormat($new->record);
                                                                $selected = in_array(
                                                                    $recordValue,
                                                                    explode(',', $data->related_records),
                                                                )
                                                                    ? 'selected'
                                                                    : '';
                                                            @endphp
                                                            <option value="{{ $recordValue }}" {{ $selected }}>
                                                                {{ $recordValue }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                         


                                            @if ($data->qa_head)
                                            @foreach (json_decode($data->qa_head) as $file)
                                                <input id="QAFile-{{ $loop->index }}" type="hidden"
                                                    name="existing_qa_head[{{ $loop->index }}]"
                                                    value="{{ $file }}">
                                            @endforeach
                                        @endif
                                          <div class="col-lg-12">
                                                <div class="group-input">
                                                    <label for="qa head">QA Attachments</label>
                                                    <div class="file-attachment-field">
                                                        <div class="file-attachment-list" id="qa_head">
                                                            @if ($data->qa_head)
                                                                @foreach (json_decode($data->qa_head) as $file)
                                                                    <h6 type="button" class="file-container text-dark"
                                                                        style="background-color: rgb(243, 242, 240);">
                                                                        <b>{{ $file }}</b>
                                                                        <a href="{{ asset('upload/' . $file) }}"
                                                                            target="_blank"><i
                                                                                class="fa fa-eye text-primary"
                                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                                        <a type="button" class="remove-file"
                                                                         data-remove-id="QAFile-{{ $loop->index }}"
                                                                            data-file-name="{{ $file }}"><i
                                                                                class="fa-solid fa-circle-xmark"
                                                                                style="color:red; font-size:20px;"></i></a>
                                                                    </h6>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="add-btn">
                                                            <div>Add</div>
                                                            <input type="file" id="myfile" name="qa_head[]" 
                                                                oninput="addMultipleFiles(this, 'qa_head')" multiple {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-block">
                                            <button type="submit" class="saveButton on-submit-disable-button" @if($data->stage == 6) disabled @endif>Save</button>
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">Exit</a> </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="CCForm4" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            Evaluation Detail
                                        </div>
                                        <div class="group-input">
                                            <label for="qa-eval-comments">QA Evaluation Comments</label>
                                            <div class="relative-container">
                                                <textarea name="qa_eval_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->qa_eval_comments }}</textarea>
                                                @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                @endcomponent
                                            </div>
                                        </div>

                                        @if ($data->qa_eval_attach)
                                        @foreach (json_decode($data->qa_eval_attach) as $file)
                                            <input id="EVALFile-{{ $loop->index }}" type="hidden"
                                                name="existing_qa_eval_attach[{{ $loop->index }}]"
                                                value="{{ $file }}">
                                        @endforeach
                                    @endif

                                        <div class="group-input">
                                            <label for="qa-eval-attach">QA Evaluation Attachments</label>
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="qa_eval_attach">
                                                    @if ($data->qa_eval_attach)
                                                        @foreach (json_decode($data->qa_eval_attach) as $file)
                                                            <h6 type="button" class="file-container text-dark"
                                                                style="background-color: rgb(243, 242, 240);">
                                                                <b>{{ $file }}</b>
                                                                <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
                                                                        style="font-size:20px; margin-right:-10px;"></i></a>
                                                                <a type="button" class="remove-file"
                                                                data-remove-id="EVALFile-{{ $loop->index }}"
                                                                    data-file-name="{{ $file }}"><i
                                                                        class="fa-solid fa-circle-xmark"
                                                                        style="color:red; font-size:20px;"></i></a>
                                                            </h6>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input  type="file" id="myfile" name="qa_eval_attach[]"
                                                        oninput="addMultipleFiles(this, 'qa_eval_attach')" multiple {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="sub-head">
                                            Training Information
                                        </div>
                                        <div class="group-input">
                                            <label for="nature-change">Training Required</label>
                                            <select name="training_required" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <option value="">-- Select --</option>
                                                <option {{ $data->training_required == 'no' ? 'selected' : '' }}
                                                    value="no">No</option>
                                                <option {{ $data->training_required == 'yes' ? 'selected' : '' }}
                                                    value="yes">Yes</option>
                                            </select>
                                        </div>

                                        <div class="group-input">
                                            <label for="train-comments">Training Comments</label>
                                            <div class="relative-container">
                                                    <textarea name="train_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->train_comments }}</textarea>
                                                    @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                            </div>            
                                        </div>

                                        <div class="button-block">
                                            <button type="submit" class="saveButton on-submit-disable-button" @if($data->stage == 6) disabled @endif>Save</button>
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">Exit</a> </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div id="CCForm5" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            CFT Information
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Microbiology">CFT Reviewer</label>
                                                    <select name="Microbiology">
                                                        <option value="">-- Select --</option>
                                                        <option value="yes" selected>Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="sub-head">
                                            Concerned Information
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="group_review">Is Concerned Group Review Required?</label>
                                                    <select name="goup_review" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="">-- Select --</option>
                                                        <option {{ $data->goup_review == 'yes' ? 'selected' : '' }}
                                                            value="yes">Yes</option>
                                                        <option {{ $data->goup_review == 'no' ? 'selected' : '' }}
                                                            value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Production">Production</label>
                                                    <select name="Production" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="">-- Select --</option>
                                                        <option {{ $data->Production == 'yes' ? 'selected' : '' }}
                                                            value="yes">Yes</option>
                                                        <option {{ $data->Production == 'no' ? 'selected' : '' }}
                                                            value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Production-Person">Production Person</label>
                                                    <select name="Production_Person" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="">-- Select --</option>
                                                        @foreach ($users as $datas)
                                                            <option
                                                                {{ $data->Production_Person == $datas->id ? 'selected' : '' }}
                                                                value="{{ $datas->id }}">{{ $datas->name }}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Quality-Approver">Quality Approver</label>
                                                    <select name="Quality_Approver" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="">-- Select --</option>
                                                        <option {{ $data->Quality_Approver == 'yes' ? 'selected' : '' }}
                                                            value="yes">Yes</option>
                                                        <option {{ $data->Quality_Approver == 'no' ? 'selected' : '' }}
                                                            value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Quality-Approver-Person">Quality Approver Person</label>
                                                    <select name="Quality_Approver_Person" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="">-- Select --</option>
                                                        @foreach ($users as $datas)
                                                            <option {{ $data->Quality_Approver_Person == $datas->id ? 'selected' : '' }}
                                                                value="{{ $datas->id }}">{{ $datas->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="bd_domestic">Others</label>
                                                    <select name="bd_domestic" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="">-- Select --</option>
                                                        <option {{ $data->bd_domestic == 'yes' ? 'selected' : '' }}
                                                            value="yes">Yes</option>
                                                        <option {{ $data->bd_domestic == 'no' ? 'selected' : '' }}
                                                            value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="bd_domestic-Person">Others Person</label>
                                                    <select name="Bd_Person" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="">-- Select --</option>

                                                        @foreach ($users as $datas)
                                                            <option {{ $data->Bd_Person == $datas->id ? 'selected' : '' }}
                                                                value="{{ $datas->id }}">{{ $datas->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="additional_attachments">Additional Attachments</label>
                                                    <div class="file-attachment-field">
                                                        <div class="file-attachment-list" id="additional_attachments">
                                                            @if ($data->additional_attachments)
                                                                @foreach (json_decode($data->additional_attachments) as $file)
                                                                    <h6 type="button" class="file-container text-dark"
                                                                        style="background-color: rgb(243, 242, 240);">
                                                                        <b>{{ $file }}</b>
                                                                        <a href="{{ asset('upload/' . $file) }}"
                                                                            target="_blank"><i
                                                                                class="fa fa-eye text-primary"
                                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                                        <a type="button" class="remove-file"
                                                                            data-file-name="{{ $file }}"><i
                                                                                class="fa-solid fa-circle-xmark"
                                                                                style="color:red; font-size:20px;"></i></a>
                                                                    </h6>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="add-btn">
                                                            <div>Add</div>
                                                            <input type="file" id="myfile"
                                                                name="additional_attachments[]"
                                                                oninput="addMultipleFiles(this, 'additional_attachments')"
                                                                multiple {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-block">
                                            <button type="submit" class="saveButton">Save</button>
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">Exit</a> </button>
                                    </div>
                                </div> -->

                                <div id="CCForm6" class="inner-block cctabcontent">
                                    <div class="inner-block-content">

                                        <div class="sub-head">
                                            Feedback
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-12">
                                                <div class="group-input">
                                                    <label for="comments">Comments</label>
                                                    <div class="relative-container">
                                                        <textarea class="mic-input" name="cft_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->cft_comments }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($data->cft_attchament)
                                            @foreach (json_decode($data->cft_attchament) as $file)
                                                <input id="FEEDFile-{{ $loop->index }}" type="hidden"
                                                    name="existing_cft_attchament[{{ $loop->index }}]"
                                                    value="{{ $file }}">
                                            @endforeach
                                        @endif



                                            <div class="col-lg-12">
                                                <div class="group-input">
                                                    <label for="comments">Feedback Attachment</label>
                                                    <div class="file-attachment-field">
                                                        <div class="file-attachment-list" id="cft_attchament">
                                                            
                                                            @if ($data->cft_attchament)
                                                                @foreach (json_decode($data->cft_attchament) as $file)
                                                                    <h6 type="button" class="file-container text-dark"
                                                                        style="background-color: rgb(243, 242, 240);">
                                                                        <b>{{ $file }}</b>
                                                                        <a href="{{ asset('upload/' . $file) }}"
                                                                            target="_blank"><i class="fa fa-eye text-primary"
                                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                                        <a type="button" class="remove-file"
                                                                         data-remove-id="FEEDFile-{{ $loop->index }}"
                                                                            data-file-name="{{ $file }}"><i
                                                                                class="fa-solid fa-circle-xmark"
                                                                                style="color:red; font-size:20px;"></i></a>
                                                                    </h6>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="add-btn">
                                                            <div>Add</div>
                                                            <input type="file" id="myfile" name="cft_attchament[]"
                                                                oninput="addMultipleFiles(this, 'cft_attchament')"
                                                                multiple {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="sub-head">
                                                Concerned Feedback
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="comments">QA Comments</label>
                                                    <div class="relative-container">
                                                        <textarea class="mic-input" name="qa_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->qa_comments }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="comments">QA Head Designee Comments</label>
                                                    <div class="relative-container">
                                                        <textarea class="mic-input" name="designee_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->designee_comments }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="comments">Warehouse Comments</label>
                                                    <div class="relative-container">
                                                        <textarea class="mic-input" name="Warehouse_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Warehouse_comments }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="comments">Engineering Comments</label>
                                                    <div class="relative-container">
                                                        <textarea class="mic-input" name="Engineering_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Engineering_comments }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="comments">Instrumentation Comments</label>
                                                    <div class="relative-container">
                                                        <textarea class="mic-input" name="Instrumentation_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Instrumentation_comments }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                      <label for="comments">Validation Comments</label>
                                                    <div class="relative-container">
                                                        <textarea class="mic-input" name="Validation_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Validation_comments }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                        <label for="comments">Others Comments</label>
                                                    <div class="relative-container">
                                                        <textarea class="mic-input" name="Others_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Others_comments }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                      <label for="comments">Comments</label>
                                                    <div class="relative-container">
                                                        <textarea class="mic-input" name="Group_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Group_comments }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($data->group_attachments)
                                            @foreach (json_decode($data->group_attachments) as $file)
                                                <input id="CFIFile-{{ $loop->index }}" type="hidden"
                                                    name="existing_group_attachments[{{ $loop->index }}]"
                                                    value="{{ $file }}">
                                            @endforeach
                                        @endif

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="group-attachments">Attachments</label>
                                                    <div class="file-attachment-field">
                                                        <div class="file-attachment-list" id="group_attachments">
                                                            @if ($data->group_attachments)
                                                                @foreach (json_decode($data->group_attachments) as $file)
                                                                    <h6 type="button" class="file-container text-dark"
                                                                        style="background-color: rgb(243, 242, 240);">
                                                                        <b>{{ $file }}</b>
                                                                        <a href="{{ asset('upload/' . $file) }}"
                                                                            target="_blank"><i
                                                                                class="fa fa-eye text-primary"
                                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                                        <a type="button" class="remove-file"
                                                                          data-remove-id="CFIFile-{{ $loop->index }}"
                                                                            data-file-name="{{ $file }}"><i
                                                                                class="fa-solid fa-circle-xmark"
                                                                                style="color:red; font-size:20px;"></i></a>
                                                                    </h6>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="add-btn">
                                                            <div>Add</div>
                                                            <input type="file" id="myfile"
                                                                name="group_attachments[]"
                                                                oninput="addMultipleFiles(this, 'group_attachments')"
                                                                multiple {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-block">
                                            <button type="submit" class="saveButton on-submit-disable-button" @if($data->stage == 6) disabled @endif>Save</button>
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">Exit</a> </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="CCForm7" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            Risk Assessment
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="risk-identification">Risk Identification</label>
                                                    <div class="relative-container">
                                                        <textarea class="mic-input" name="risk_identification" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->risk_identification }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="severity">Severity</label>
                                                    <select name="severity" id="analysisR"
                                                        onchange='calculateRiskAnalysis(this)' {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="">-- Select --</option>
                                                        <option {{ $data->severity == '1' ? 'selected' : '' }}
                                                            value="1">Negligible</option>
                                                        <option {{ $data->severity == '2' ? 'selected' : '' }}
                                                            value="2">Minor</option>
                                                        <option {{ $data->severity == '3' ? 'selected' : '' }}
                                                            value="3">Moderate</option>
                                                        <option {{ $data->severity == '4' ? 'selected' : '' }}
                                                            value="4">Major</option>
                                                        <option {{ $data->severity == '5' ? 'selected' : '' }}
                                                            value="5">Fatel</option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Occurance">Occurance</label>
                                                    <select name="Occurance" id="analysisP"
                                                        onchange='deleteFishBone' {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="">-- Select --</option>
                                                        <option
                                                            {{ $data->Occurance == '1' ? 'selected' : '' }}
                                                            value="1">Extremely Unlikely</option>
                                                        <option {{ $data->Occurance == '2' ? 'selected' : '' }}
                                                            value="2">Rare</option>
                                                        <option {{ $data->Occurance == '3' ? 'selected' : '' }}
                                                            value="3">Unlikely</option>
                                                        <option {{ $data->Occurance == '4' ? 'selected' : '' }}
                                                            value="4">Likely</option>
                                                        <option {{ $data->Occurance == '5' ? 'selected' : '' }}
                                                            value="5">Very Likely</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Detection">Detection</label>
                                                    <select name="Detection" id="analysisN"
                                                        onchange='calculateRiskAnalysis(this)' {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                        <option value="">-- Select --</option>
                                                        <option {{ $data->Detection == '1' ? 'selected' : '' }}
                                                            value="1">Impossible</option>
                                                        <option {{ $data->Detection == '2' ? 'selected' : '' }}
                                                            value="2">Rare</option>
                                                        <option {{ $data->Detection == '3' ? 'selected' : '' }}
                                                            value="3">Unlikely</option>
                                                        <option {{ $data->Detection == '4' ? 'selected' : '' }}
                                                            value="4">Likely</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="RPN">RPN</label>
                                                    <input type="text" name="RPN" id="analysisRPN"
                                                        value="{{ $data->RPN }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="risk-evaluation">Risk Evaluation</label>
                                                    <div class="relative-container">
                                                        <textarea class="mic-input" name="risk_evaluation" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->risk_evaluation }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="migration-action">Migration Action</label>
                                                    <div class="relative-container">
                                                        <textarea class="mic-input" name="migration_action" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->migration_action }}</textarea>
                                                        @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="button-block">
                                            <button type="submit" class="saveButton on-submit-disable-button" @if($data->stage == 6) disabled @endif>Save</button>
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">Exit</a> </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="CCForm8" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="group-input">
                                            <label for="qa-appro-comments">QA Approval Comments</label>
                                            <div class="relative-container">
                                                <textarea class="mic-input" name="qa_appro_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->qa_appro_comments}}</textarea>
                                                @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                @endcomponent
                                            </div>                                           
                                        </div>

                                        <div class="group-input">
                                            <label for="feedback">Training Feedback</label>
                                            <div class="relative-container">
                                                <textarea class="mic-input" name="feedback" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->feedback}}</textarea>
                                                @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                @endcomponent
                                            </div>
                                        </div>

                                        @if ($data->tran_attach)
                                        @foreach (json_decode($data->tran_attach) as $file)
                                            <input id="TRAINFile-{{ $loop->index }}" type="hidden"
                                                name="existing_tran_attach[{{ $loop->index }}]"
                                                value="{{ $file }}">
                                        @endforeach
                                    @endif

                                        <div class="group-input">
                                            <label for="tran-attach">Training Attachments</label>
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="tran_attach">
                                                    @if ($data->tran_attach)
                                                        @foreach (json_decode($data->tran_attach) as $file)
                                                            <h6 type="button" class="file-container text-dark"
                                                                style="background-color: rgb(243, 242, 240);">
                                                                <b>{{ $file }}</b>
                                                                <a href="{{ asset('upload/' . $file) }}"
                                                                    target="_blank"><i class="fa fa-eye text-primary"
                                                                        style="font-size:20px; margin-right:-10px;"></i></a>
                                                                <a type="button" class="remove-file"
                                                                 data-remove-id="TRAINFile-{{ $loop->index }}"
                                                                    data-file-name="{{ $file }}"><i
                                                                        class="fa-solid fa-circle-xmark"
                                                                        style="color:red; font-size:20px;"></i></a>
                                                            </h6>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input type="file" id="myfile" name="tran_attach[]"
                                                        oninput="addMultipleFiles(this, 'tran_attach')" multiple {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="button-block">
                                            <button type="submit" class="saveButton on-submit-disable-button" @if($data->stage == 6) disabled @endif>Save</button>
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">Exit</a> </button>
                                        
                                        </div>
                                    </div>
                                </div>

                                <div id="CCForm9" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="group-input">
                                            <label for="risk-assessment">
                                                Affected Documents<button type="button" name="ann"  {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                    id="addAffectedDocumentsbtn">+</button>
                                            </label>
                                            <table class="table table-bordered" id="affected-documents">
                                                <thead>
                                                    <tr>
                                                        <th style='width:3%'>Sr. No.</th>
                                                        <th>Affected Documents</th>
                                                        <th>Document Name</th>
                                                        <th>Document No.</th>
                                                         <th>Version No.</th> 
                                                        <th>Implementation Date</th>
                                                        <th>New Document No.</th>
                                                        <th>New Version No.</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($closure->sno))
                                                    @foreach (unserialize($closure->affected_document) as $key => $datas)
                                                        <tr>
                                                            <td><input type="text" name="serial_number[]"
                                                                    value="{{ $key ? $key + 1 : '1' }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}></td>
                                                            <td><input type="text"
                                                                    name="affected_documents[]"
                                                                    value="{{ unserialize($closure->affected_document)[$key] ? unserialize($closure->affected_document)[$key] : '' }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                            </td>
                                                            <td><input type="text" name="document_name[]"
                                                                    value="{{ unserialize($closure->doc_name)[$key] ? unserialize($closure->doc_name)[$key] : '' }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                            </td>
                                                            <td>                                                                
                                                                <input type="number" name="document_no[]"
                                                                    value="{{ unserialize($closure->doc_no)[$key] ? unserialize($closure->doc_no)[$key] : '' }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                            </td>
                                                             <td>
                                                                @if (!empty($closure->version_no))
                                                                <input type="text" name="version_no[]" value="{{ unserialize($closure->version_no)[$key] ? unserialize($closure->version_no)[$key] : '' }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                                @else
                                                                <input type="text" name="version_no[]" value="" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                                @endif
                                                            </td> 
                                                            
                                                            <td>
                                                                <div class="group-input new-date-data-field mb-0">
                                                                    <div class="input-date ">
                                                                        <div class="calenderauditee">
                                                                            <input type="text"  id="implementation_date" 
                                                                                placeholder="DD-MM-YYYY"  {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} readonly />
                                                                            <input type="date" name="implementation_date[]" class="hide-input" placeholder="DD-MM-YYYY"  value="{{ Helpers::getdateFormat($closure->implementation_date)[$key] }}"
                                                                                oninput="handleDateInput(this, `implementation_date' + serialNumber +'`)" 
                                                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}   value="{{ unserialize($closure->implementation_date)[$key] ? unserialize($closure->implementation_date)[$key] : '' }}"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                           
                                                           
                                                            <td><input type="text" name="new_document_no[]"  {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                value="{{ unserialize($closure->new_doc_no)[$key] ? unserialize($closure->new_doc_no)[$key] : '' }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                             </td>
                                                             <td><input type="text" name="new_version_no[]"  {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                value="{{ unserialize($closure->new_version_no)[$key] ? unserialize($closure->new_version_no)[$key] : '' }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                             </td>
                                                             <td><button  {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="text" class="removeaddAffectedDocumentsbtn">Remove</button></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                    <div id="docdetaildiv"></div>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="group-input">
                                            <label for="qa-closure-comments">QA Closure Comments</label>
                                            <div class="relative-container">
                                                <textarea class="mic-input" name="qa_closure_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->qa_closure_comments }}</textarea>
                                                @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                @endcomponent
                                            </div>
                                           
                                        </div>
                                        <!-- <div class="group-input">
                                            <label for="attach-list">List Of Attachments</label>
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="tran_attach">
                                                    @if ($closure->attach_list)
                                                        @foreach (json_decode($closure->attach_list) as $file)
                                                            <h6 type="button" class="file-container text-dark"
                                                                style="background-color: rgb(243, 242, 240);">
                                                                <b>{{ $file }}</b>
                                                                <a href="{{ asset('upload/' . $file) }}"
                                                                    target="_blank"><i class="fa fa-eye text-primary"
                                                                        style="font-size:20px; margin-right:-10px;"></i></a>
                                                                <a type="button" class="remove-file"
                                                                    data-file-name="{{ $file }}"><i
                                                                        class="fa-solid fa-circle-xmark"
                                                                        style="color:red; font-size:20px;"></i></a>
                                                            </h6>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input type="file" id="myfile" name="attach_list[]"
                                                        oninput="addMultipleFiles(this, 'attach_list')" multiple>
                                                </div>
                                            </div>
                                        </div> -->
                                        @if ($data->attach_list)
                                        @foreach (json_decode($data->attach_list) as $file)
                                            <input id="CCFile-{{ $loop->index }}" type="hidden"
                                                name="existing_attach_list[{{ $loop->index }}]"
                                                value="{{ $file }}">
                                        @endforeach
                                    @endif

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="others">Change Clouser attachment</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                                <div class="file-attachment-field">
                                                    <div disabled class="file-attachment-list" id="attach_list">
                                                        @if ($data->attach_list)
                                                            @foreach (json_decode($data->attach_list) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i
                                                                            class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                      data-remove-id="CCFile-{{ $loop->index }}"
                                                                        data-file-name="{{ $file }}"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file" id="myfile" name="attach_list[]"
                                                            oninput="addMultipleFiles(this, 'attach_list')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="sub-head">
                                            Effectiveness Check Information
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="effective-check">Effectivess Check Required?</label>
                                                    <select name="effective_check">
                                                        <option value="">-- Select --</option>
                                                        <option {{ $closure->effective_check == 'yes' ? 'selected' : '' }}
                                                            value="yes">Yes</option>
                                                        <option {{ $closure->effective_check == 'no' ? 'selected' : '' }}
                                                            value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                           
                                            <div class="col-lg-6 new-date-data-field">
                                                <div class="group-input input-date">
                                                    <label for="effective-check-date">Effectiveness Check Creation Date</label>
                                                   <div class="calenderauditee">                                     
                                                          <input type="text"  id="effective_check_date"  readonly value="{{ Helpers::getdateFormat($data->effective_check_date)}}"
                                                           name="effective_check_date"  placeholder="DD-MM-YYYY" />
                                                          <input type="date" name="effective_check_date" value="{{ $data->effective_check_date }}"  class="hide-input"
                                                           oninput="handleDateInput(this, 'effective_check_date')"/>
                                             </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="Effectiveness_checker">Effectiveness Checker</label>
                                                    <select name="Effectiveness_checker">
                                                        <option value="">Enter Your Selection Here</option>
                                                        @foreach ($users as $datas)
                                                            <option {{ $data->Effectiveness_checker == $datas->id ? 'selected' : '' }}
                                                                 value="{{ $datas->id }}">{{ $datas->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="effective_check_plan">Effectiveness Check Plan</label>
                                                    <textarea name="effective_check_plan">{{$data->effective_check_plan}}</textarea>
                                                </div>
                                            </div> -->
                                            <div class="col-12 sub-head">
                                                Extension Justification
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">                                                    
                                                    <label for="due_date_extension">Due Date Extension Justification</label>
                                                    <div class="relative-container">
                                                            <textarea class="mic-input" name="due_date_extension" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{$data->due_date_extension }}</textarea>
                                                            @component('frontend.forms.language-model' , [ 'disabled' => $data->stage == 0 || $data->stage == 6  ])
                                                            @endcomponent
                                                        </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-block">
                                            <button type="submit" class="saveButton on-submit-disable-button" @if($data->stage == 6) disabled @endif>Save</button>
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">Exit</a> </button>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $product = DB::table('products')->get();
                                    $material = DB::table('materials')->get();
                                @endphp

                                <div id="CCForm10" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            Electronic Signatures
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="Acknowledge_By">Submitted By</label>
                                                    <div class="static">{{  $data->submitted_by }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="Acknowledge_On">Submitted On</label>
                                                    <div class="static"> {{  $data->submitted_on }} </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Acknowledge_On">Submitted Comment</label>
                                                    <div class="static"> {{  $data->submitted_comment }}</div>
                                                </div>
                                            </div>


                                            <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="Submit_By">HOD Review Completed By</label>
                                                    <div class="static"> {{  $data->hod_review_completed_by }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="Submit_On">HOD Review Completed On</label>
                                                    <div class="static">{{  $data->hod_review_completed_on }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Submit_On">HOD Review Completed Comment</label>
                                                    <div class="static">{{  $data->hod_review_completed_comment }}</div>
                                                </div>
                                            </div>

                                             <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="cft_review_by"> CFT Review Completed By</label>
                                                    <div class="static"> {{  $data->cft_review_by }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="cft_review_on"> CFT Review Completed On</label>
                                                    <div class="static">{{  $data->cft_review_on }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="cft_review_comment">CFT Review Completed Comment</label>
                                                    <div class="static">{{  $data->cft_review_comment }}</div>
                                                </div>
                                            </div> 

                                            <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="cftNot_required_by"> CFT Review Not Required By</label>
                                                    <div class="static"> {{  $data->cftNot_required_by }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="cftNot_required_on"> CFT Review Not Required On</label>
                                                    <div class="static">{{  $data->cftNot_required_on }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="cftNot_required_comment">CFT Review Not Required Comment</label>
                                                    <div class="static">{{  $data->cftNot_required_comment }}</div>
                                                </div>
                                            </div> 

                                            {{-- <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="QA_Review_Complete_By">Pending CFT Review Completed By</label>
                                                    <div class="static"> {{  $data->cftNot_required_by }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="QA_Review_Complete_On">Pending CFT Review Completed On</label>
                                                    <div class="static">{{  $data->cftNot_required_on }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="QA_Review_Complete_On">Pending CFT Review Completed Comment</label>
                                                    <div class="static">{{  $data->cftNot_required_comment }}</div>
                                                </div>
                                            </div> --}}

                                            <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="QA_Review_Complete_By">Review Completed By</label>
                                                    <div class="static">{{  $data->QA_review_completed_by }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="QA_Review_Complete_On">Review Completed On</label>
                                                    <div class="static"> {{  $data->QA_review_completed_on }} </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="QA_Review_Complete_On">Review Completed Comment</label>
                                                    <div class="static"> {{  $data->QA_review_completed_comment }} </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="Cancelled By">Implemented By</label>
                                                    <div class="static"> {{  $data->implemented_by }} </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="group-input">
                                                    <label for="Cancelled On">Implemented On</label>
                                                    <div class="static">  {{  $data->implemented_on }} </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Cancelled On">Implemented Comment</label>
                                                    <div class="static"> {{  $data->implemented_comment }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-block">
                                            <!-- <button type="submit" class="saveButton">Save</button> -->
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <!-- <button type="submit">Submit</button> -->
                                            <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">Exit</a> </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="child-modal1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Child</h4>
                </div>
                <form action="{{ route('extension_child', $cc_lid) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="group-input">

                            <!-- <label for="major">
                                <input type="radio" name="child_type" value="extension">
                                Extension
                                <input type="hidden" name="parent_name" value="Change_control">
                                <input type="hidden" name="due_date" value="{{ $data->due_date }}">
                            </label> -->
                            <label for="major">
                                <input type="radio" name="child_type" value="documents">
                                New Document                               
                            </label>
  



                        </div>
 
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        <button type="submit">Continue</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <div class="modal fade" id="signature-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form class="formSubmit" action="{{ url('rcms/send-cc', $cc_lid) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
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
                            <input type="comment" name="comment">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="formsubmit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        {{-- <button>Close</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="division-modal" class="d-none">
        <div class="division-container">
            <div class="content-container">
                <form action="{{ route('division_submit') }}" method="post">
                    @csrf
                    <div class="division-tabs">
                        <div class="tab">
                            @php
                                $division = DB::table('divisions')->get();
                            @endphp
                            @foreach ($division as $temp)
                                <input type="hidden" value="{{ $temp->id }}" name="division_id" required>
                                <button class="divisionlinks"
                                    onclick="openDivision(event, {{ $temp->id }})">{{ $temp->name }}</button>
                            @endforeach

                        </div>
                        @php
                            $process = DB::table('processes')->get();
                        @endphp
                        @foreach ($process as $temp)
                            <div id="{{ $temp->division_id }}" class="divisioncontent">
                                @php
                                    $pro = DB::table('processes')
                                        ->where('division_id', $temp->division_id)
                                        ->get();
                                @endphp
                                @foreach ($pro as $test)
                                    <label for="process">
                                        <input type="radio" for="process" value="{{ $test->id }}"
                                            name="process_id" required> {{ $test->process_name }}
                                    </label>
                                @endforeach
                            </div>
                        @endforeach

                    </div>
                    <div class="button-container">
                        <button id="submit-division">Cancel</button>
                        <button id="submit-division" type="submit">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="child-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Child</h4>
                </div>
                <form action="{{ url('rcms/child', $cc_lid) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="group-input">
                            <label for="major">
                                <input type="radio" name="revision" id="major" value="Action-Item">
                                Action Item
                            </label>
                            @if ($data->stage == 10)
                                <label for="minor">
                                    <input type="radio" name="revision" id="minor" value="Extension">
                                    Extension
                                </label>
                            @elseif($data->stage == 7)
                                <label for="minor">
                                    <input type="radio" name="revision" id="minor" value="New Document">
                                    New Document
                                </label>
                            @endif
                        </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        <button type="submit">Continue</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="rejection-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form class="formSubmit" action="{{ url('rcms/send-rejection-field', $cc_lid) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
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
                            <input type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        {{-- <button>Close</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cft-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form class="formSubmit" action="{{ url('rcms/send-cft-field', $cc_lid) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
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
                            <input type="comment" name="comment">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        {{-- <button>Close</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="cancel-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form class="formSubmit" action="{{ url('rcms/send-cancel', $cc_lid) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
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
                            <input type="comment" name="comment">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        {{-- <button>Close</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>


    <style>
        #productTable,
        #materialTable {
            display: none;
        }
    </style>


    <script>
        VirtualSelect.init({
            ele: '#related_records, #cft_reviewer'
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

            // Find the index of the clicked tab button
            const index = Array.from(cctablinks).findIndex(button => button === evt.currentTarget);

            // Update the currentStep to the index of the clicked tab
            currentStep = index;
        }

        const saveButtons = document.querySelectorAll(".saveButton");
        const nextButtons = document.querySelectorAll(".nextButton");
        const form = document.getElementById("step-form");
        const stepButtons = document.querySelectorAll(".cctablinks");
        const steps = document.querySelectorAll(".cctabcontent");
        let currentStep = 0;

        function nextStep() {
            // Check if there is a next step
            if (currentStep < steps.length - 1) {
                // Hide current step
                steps[currentStep].style.display = "none";

                // Show next step
                steps[currentStep + 1].style.display = "block";

                // Add active class to next button
                stepButtons[currentStep + 1].classList.add("active");

                // Remove active class from current button
                stepButtons[currentStep].classList.remove("active");

                // Update current step
                currentStep++;
            }
        }

        function previousStep() {
            // Check if there is a previous step
            if (currentStep > 0) {
                // Hide current step
                steps[currentStep].style.display = "none";

                // Show previous step
                steps[currentStep - 1].style.display = "block";

                // Add active class to previous button
                stepButtons[currentStep - 1].classList.add("active");

                // Remove active class from current button
                stepButtons[currentStep].classList.remove("active");

                // Update current step
                currentStep--;
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#add-input').click(function() {
                var lastInput = $('.bar input:last');
                var newInput = $('<input type="text" name="review_comment">');
                lastInput.after(newInput);
            });
        });
    </script>

    <!-- Example Blade View -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>

    @if (session()->has('errorMessages'))
        <script>
            // Create an array to hold all the error messages
            var errorMessages = @json(session()->get('errorMessages'));

            if (!Array.isArray(errorMessages)) {
                errorMessages = [errorMessages];
            }

            errorMessages = errorMessages.map(function(message) {
                return '<div class="seperator">==================================================</div>' +
                    '<div class="slogan"><div>This form was not submitted because of the following errors.</div><div>Please correct the errors and re-submit.</div></div>' +
                    '<div class="data">This Activity cannot be performed, as there are some blank required fields.</div>' +
                    '<div class="message">' + message + '</div>';
            });

            Swal.fire({
                icon: '',
                title: 'Connexo DMS Says',
                html: errorMessages.join(''),

                showCloseButton: true, // Display a close button
                customClass: {
                    title: 'my-title-class', // Add a custom CSS class to the title
                    htmlContainer: 'my-html-class text-danger', // Add a custom CSS class to the popup content
                },
                confirmButtonColor: '#3085d6', // Customize the confirm button color
            });
        </script>
        @php session()->forget('errorMessages'); @endphp
    @endif

    <script>
        $(document).ready(function() {
            var disableInputs = {{ $data->stage }}; // Replace with your condition

            if (disableInputs == 0 || disableInputs > 8) {
                // Disable all input fields within the form
                $('#CCFormInput :input:not(select)').prop('disabled', true);
                $('#CCFormInput select').prop('disabled', true);
            } else {
               // $('#CCFormInput :input').prop('disabled', false);
            }
        });
    </script>
    <script>
        const productSelect = document.getElementById('productSelect');
        const productTable = document.getElementById('productTable');
        const materialSelect = document.getElementById('materialSelect');
        const materialTable = document.getElementById('materialTable');

        materialSelect.addEventListener('change', function() {
            if (materialSelect.value === 'yes') {
                materialTable.style.display = 'block';
            } else {
                materialTable.style.display = 'none';
            }
        });

        productSelect.addEventListener('change', function() {
            if (productSelect.value === 'yes') {
                productTable.style.display = 'block';
            } else {
                productTable.style.display = 'none';
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removeButtons = document.querySelectorAll('.remove-file');

            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const fileName = this.getAttribute('data-file-name');
                    const fileContainer = this.closest('.file-container');

                    // Hide the file container
                    if (fileContainer) {
                        fileContainer.style.display = 'none';
                    }
                });
            });
        });
    </script>
    <script>
        function calculateRiskAnalysis(selectElement) {
            // Get the row containing the changed select element
            let row = selectElement.closest('tr');

            // Get values from select elements within the row
            let R = parseFloat(document.getElementById('analysisR').value) || 0;
            let P = parseFloat(document.getElementById('analysisP').value) || 0;
            let N = parseFloat(document.getElementById('analysisN').value) || 0;

            // Perform the calculation
            let result = R * P * N;

            // Update the result field within the row
            document.getElementById('analysisRPN').value = result;
        }
    </script>
     <script>
        var maxLength = 255;
        $('#docname').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchars').text(textlen);});
    </script>

    <script>
        // JavaScript
        document.getElementById('initiator_group').addEventListener('change', function() {
            var selectedValue = this.value;
            document.getElementById('initiator_group_code').value = selectedValue;
        });
    </script>


<script>
    $(document).ready(function() {
        $('.remove-file').click(function() {
            const removeId = $(this).data('remove-id')
            console.log('removeId', removeId);
            $('#' + removeId).remove();
        })
    })
</script>
<script>
    $(document).ready(function() {

        $('.formSubmit').on('submit', function(e) {
            $('.on-submit-disable-button').prop('disabled', true);
        });
    })
</script>
@endsection
