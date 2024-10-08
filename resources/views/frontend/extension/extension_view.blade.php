@extends('frontend.layout.main')
@section('container')
    @php
        $users = DB::table('users')->select('id', 'name')->where('active', 1)->get();
        $userRoles = DB::table('user_roles')->select('user_id')->where('q_m_s_roles_id', 4)->distinct()->get();
        $departments = DB::table('departments')->select('id', 'name')->get();
        $divisions = DB::table('q_m_s_divisions')->select('id', 'name')->get();

        $userIds = DB::table('user_roles')
            ->where('q_m_s_roles_id', 4)

            ->distinct()
            ->pluck('user_id');

        // Step 3: Use the plucked user_id values to get the names from the users table
        $userNames = DB::table('users')->whereIn('id', $userIds)->pluck('name');

        // If you need both id and name, use the select method and get
        $userDetails = DB::table('users')->whereIn('id', $userIds)->select('id', 'name')->get();
        // dd ($userIds,$userNames, $userDetails);
    @endphp
    <style>
        textarea.note-codable {
            display: none !important;
        }

        header {
            display: none;
        }

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

        #change-control-fields>div>div.inner-block.state-block>div.status>div.progress-bars.d-flex>div:nth-child(4) {
            border-radius: 0px 20px 20px 0px;

        }

        .new-moreinfo {
            width: 100%;
            border-radius: 5px;
            margin-bottom: 13px;
        }
    </style>
    </style>

    <div class="form-field-head">
        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName($extensionNew->site_location_code) }} /
            Extension
        </div>
    </div>
    {{-- ======================================
                    DATA FIELDS
    ======================================= --}}
    <div id="change-control-fields">
        <div class="container-fluid">
            <div class="inner-block state-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="main-head">Record Workflow </div>
                    @php
                        $userRoles = DB::table('user_roles')
                            ->where([
                                'user_id' => Auth::user()->id,
                                'q_m_s_divisions_id' => $extensionNew->site_location_code,
                            ])
                            ->get();
                        $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
                    @endphp
                    <div class="d-flex" style="gap:20px;">
                        {{-- <button class="button_theme1" onclick="window.print();return false;"
                            class="new-doc-btn">Print</button> --}}
                        <button class="button_theme1"> <a class="text-white"
                                href="{{ url('rcms/audit_trailNew', $extensionNew->id) }}"> Audit Trail </a> </button>
                        @if ($extensionNew->stage == 1 && Helpers::check_roles($extensionNew->site_location_code, 'Extension', 3))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Submit
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#reject-required-modal">
                                Cancel
                            </button>
                        @elseif($extensionNew->stage == 2 && Helpers::check_roles($extensionNew->site_location_code, 'Extension', 4))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Review
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#more-info-required-modal">
                                More Information Required
                            </button>
                        @elseif($extensionNew->stage == 3 && Helpers::check_roles($extensionNew->site_location_code, 'Extension', 45))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Approved
                            </button>
                            {{-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-approved-modal">
                                Approved
                             </button> --}}
                            {{-- @if ($extensionNew->parent_type == 'Deviation' || $extensionNew->parent_type == 'CAPA')
                                    @if ($count == 3)
                                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-cqa-modal">
                                            Send for CQA
                                        </button>
                                    @endif
                                    @if ($capaCount == 3)
                                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-cqa-modal">
                                            Send for CQA
                                        </button>
                                    @endif
                                @endif --}}
                            {{-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Reject
                            </button> --}}
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#more-info-required-modal">
                                More Information Required
                            </button>
                            {{-- @elseif($extensionNew->stage == 5 && (in_array(10, $userRoleIds) || in_array(18, $userRoleIds))) --}}

                            {{-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-cqa-modal">
                             CQA Approval Complete
                            </button> --}}
                            {{-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#more-info-required-modal">
                                More Info Required
                            </button> --}}
                        @endif
                        <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"><button class="button_theme1"> Exit
                            </button> </a>
                    </div>
                </div>
                <div class="status">
                    <div class="head">Current Status</div>
                    @if ($extensionNew->stage == 0)
                        <div class="progress-bars">
                            <div class="bg-danger">Closed-Cancelled</div>
                        </div>
                    @else
                        <div class="progress-bars d-flex">
                            @if ($extensionNew->stage >= 1)
                                <div class="active">Opened</div>
                            @else
                                <div class="">Opened</div>
                            @endif

                            @if ($extensionNew->stage >= 2)
                                <div class="active">In Review</div>
                            @else
                                <div class="">In Review</div>
                            @endif

                            @if ($extensionNew->stage >= 3)
                                <div class="active">In Approved</div>
                            @else
                                <div class="">In Approved</div>
                            @endif
                            @if ($extensionNew->stage >= 4)
                                <div class="bg-danger">Close Done</div>
                            @else
                                <div class="">Close Done</div>
                            @endif
                            {{-- <div style="display: none" class=""> In CQA Approval</div> --}}
                            {{--
                            @if ($extensionNew->stage == 4)
                                <div class="bg-danger">Closed - Reject</div>
                                <div style="display: none" class="">Closed - Done</div>
                                @if ($count == 3)
                                    <div style="display: none" class=""> In CQA Approval</div>
                                @endif
                            @elseif($extensionNew->stage == 1 || $extensionNew->stage == 2 || $extensionNew->stage == 3)
                                <div class=""> Closed - Reject</div>
                            @else
                                <div class="" style="display: none"> Closed - Reject</div>
                            @endif
                            @if ($extensionNew->stage == 5)
                                <div class="bg-danger" style="display: none">Closed - Reject</div>
                                <div class="active"> In CQA Approval</div>
                            @endif
                            @if ($extensionNew->stage >= 6)
                            <div class="bg-danger" style="display: none">Closed - Reject</div>
                                @if ($count == 3)
                                    <div  class="active" > In CQA Approval</div>
                                @else
                                    <div  class="active" style="display: none"> In CQA Approval</div>
                                @endif
                                <div class="bg-danger">Closed - Done</div>
                            @endif --}}
                        </div>
                    @endif
                </div>
                {{-- @endif --}}
                {{-- ---------------------------------------------------------------------------------------- --}}
            </div>
            <!-- Tab links -->
            <div class="cctab">

                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">HOD review </button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">QA Approval</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Activity Log</button>
            </div>
            <form action="{{ route('extension_new.update', $extensionNew->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Tab content -->
                <div id="step-form">

                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                @if (!empty($parent_id))
                                    <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                                    <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                                    <input type="hidden" name="parent_record" value="{{ $parent_record }}">
                                @endif
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number"><b>Record Number</b></label>
                                        <input disabled type="text" name="record_number" {{-- value="{{ Helpers::getDivisionName($extensionNew->site_location_code) }}Ext/{{ Helpers::year($extensionNew->created_at) }}/{{ str_pad($extensionNew->record_number, 4, '0', STR_PAD_LEFT) }}"> --}}
                                            {{-- value="{{ Helpers::getDivisionName($extensionNew->division) }}/Ext/{{ Helpers::year($extensionNew->created_at) }}/{{ $extensionNew->record_number }}"> --}}
                                            value=" {{ Helpers::divisionNameForQMS($extensionNew->site_location_code) }}/Ext/{{ Helpers::year($extensionNew->created_at) }}/{{ str_pad($extensionNew->record_number, 4, '0', STR_PAD_LEFT) }}">



                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code"><b>Site/Location Code</b></label>
                                        <input disabled type="text" name="site_location" id="site_location"
                                            value="{{ Helpers::getDivisionName($extensionNew->site_location_code) }}">
                                        <input type="hidden" name="site_location_code" id="site_location_code"
                                            value="{{ session()->get('division') }}">
                                        {{-- <div class="static">{{ Helpers::getDivisionName(session()->get('division')) }}</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator"><b>Initiator</b></label>
                                        {{-- <input type="hidden" value="{{ Auth::user()->name }}" name="initiator" id="initiator"> --}}
                                        <input disabled type="text" name="initiator" id="initiator"
                                            value="{{ Auth::user()->name }}">
                                    </div>
                                </div>
                                @php
                                    // Calculate the due date (30 days from the initiation date)
                                    $initiationDate = date('Y-m-d'); // Current date as initiation date
                                    $dueDate = date('Y-m-d', strtotime($initiationDate . '+30 days')); // Due date
                                @endphp
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Date of Initiation"><b>Date of Initiation</b></label>
                                        <input readonly type="text"
                                            value="{{ Helpers::getdateFormat($extensionNew->initiation_date) }}"
                                            name="initiation_date" id="initiation_date"
                                            style="background-color: light-dark(rgba(239, 239, 239, 0.3), rgba(59, 59, 59, 0.3))">
                                        {{-- <input type="hidden" value="{{ date('Y-m-d') }}" name="initiation_date_hidden"> --}}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="docname">Short Description<span class="text-danger">*</span></label>
                                        <span id="rchars">255</span> Characters remaining
                                        <div class="relative-container">
                                            <input name="short_description" maxlength="255" class="mic-input" required
                                                {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }}
                                                id="docname" value="{{ $extensionNew->short_description }}">
                                            @component('frontend.forms.language-model', [
                                                'disabled' => $extensionNew->stage == 0 || $extensionNew->stage == 4,
                                            ])
                                            @endcomponent
                                        </div>
                                        {{-- <div style="position:relative;">
                                    <input id="docname" type="text" name="short_description" {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }} value="{{$extensionNew->short_description}}" maxlength="255" required class="mic-input">
                                    <button class="mic-btn" type="button">
                                        <i class="fas fa-microphone"></i>
                                    </button>
                                </div> --}}
                                    </div>
                                    {{-- @error('short_description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror --}}
                                </div>

                                <script>
                                    var maxLength = 255;
                                    $('#docname').keyup(function() {
                                        var textlen = maxLength - $(this).val().length;
                                        $('#rchars').text(textlen);
                                    });
                                </script>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Assigned To">HOD Reviewer </label>
                                        <select id="choices-multiple-remove" class="choices-multiple-reviewe"
                                            name="reviewers" placeholder="Select Reviewers"
                                            {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }}>
                                            <option value="">-- Select --</option>
                                            @if (!empty(Helpers::getHODDropdown()))
                                                @foreach (Helpers::getHODDropdown() as $listHod)
                                                    <option value="{{ $listHod['id'] }}"
                                                        @if ($listHod['id'] == $extensionNew->reviewers) selected @endif>
                                                        {{ $listHod['name'] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>



                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Assigned To">QA approval </label>
                                        <select id="choices-multiple-remove-but" class="choices-multiple-reviewer"
                                            name="approvers" placeholder="Select Approvers"
                                            {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }}>
                                            <option value="">-- Select --</option>

                                            @if (!empty($users))
                                                @foreach ($users as $lan)
                                                    <option value="{{ $lan->id }}"
                                                        @if ($lan->id == $extensionNew->approvers) selected @endif>
                                                        {{ $lan->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Actual Start Date">Current Due Date (Parent)</label>
                                        <div class="calenderauditee">

                                            <input type="text" id="current_due_date"
                                                value="{{ Helpers::getdateFormat($extensionNew->current_due_date) }}"
                                                readonly placeholder="DD-MM-YYYY" />
                                            <input type="date" name="current_due_date" id="current_due_date_checkdate"
                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                value="{{ $extensionNew->current_due_date ? \Carbon\Carbon::parse($extensionNew->current_due_date)->format('Y-m-d') : '' }}"
                                                class="hide-input" {{-- oninput="handleDateInput(this, 'current_due_date')"/> --}}
                                                oninput="handleDateInput(this, 'current_due_date');checkDate('proposed_due_date','current_due_date_checkdate')"
                                                multiple
                                                {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }}>


                                        </div>

                                    </div>
                                </div>

                                {{-- <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Actual Start Date">Proposed Due Date</label>
                                    <div class="calenderauditee">
                                        <input type="text"  id="proposed_due_date"  value="{{  Helpers::getdateFormat($extensionNew->proposed_due_date) }}" readonly placeholder="DD-MMM-YYYY" />
                                    <input type="date" name="proposed_due_date"  min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ $extensionNew->proposed_due_date }}"
                                    class="hide-input"
                                    {{-- oninput="handleDateInput(this, 'proposed_due_date')"/> --}}
                                {{-- oninput="handleDateInput(this, 'proposed_due_date');checkDate('current_due_date','proposed_due_date_checkdate')" /> --}}

                                {{-- </div> --}}
                                {{-- </div> --}}
                                {{-- </div> --}}
                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Scheduled End Date">Proposed Due Date</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="proposed_due_date" readonly
                                                value="{{ Helpers::getdateFormat($extensionNew->proposed_due_date) }}"
                                                placeholder="DD-MM-YYYY" />
                                            <input type="date" id="proposed_due_date_checkdate"
                                                name="proposed_due_date"
                                                value="{{ $extensionNew->proposed_due_date ? \Carbon\Carbon::parse($extensionNew->proposed_due_date)->format('Y-m-d') : '' }}"
                                                class="hide-input"
                                                oninput="handleDateInput(this, 'proposed_due_date');checkDate('current_due_date','proposed_due_date_checkdate')"
                                                multiple
                                                {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }}>
                                        </div>
                                        {{-- <input type="date" name="schedule_end_date1" value="{{$extensionNew->schedule_end_date}}"> --}}
                                    </div>
                                </div>
                            </div>
                            <script>
                                function handleDateInput(inputElement, displayElementId) {
                                    var displayElement = document.getElementById(displayElementId);
                                    var dateValue = new Date(inputElement.value);
                                    displayElement.value = dateValue.toLocaleDateString('en-GB', {
                                        day: '2-digit',
                                        month: '2-digit',
                                        year: 'numeric'
                                    });
                                }

                                function updateEndDateMin() {
                                    var startDate = document.getElementById('current_due_date_checkdate').value;
                                    var endDateInput = document.getElementById('proposed_due_date_checkdate');
                                    if (startDate) {
                                        endDateInput.setAttribute('min', startDate);
                                    }
                                }

                                document.addEventListener("DOMContentLoaded", function() {
                                    updateEndDateMin(); // Initialize the end date min on page load

                                    // Reapply the min attribute whenever the start date is changed
                                    document.getElementById('current_due_date_checkdate').addEventListener('input', function() {
                                        updateEndDateMin();
                                    });
                                });
                            </script>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="docname">Description</label>
                                    <div class="relative-container">
                                        <textarea name="description" class="mic-input"
                                            {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }} id="description">{{ $extensionNew->description }}</textarea>
                                        @component('frontend.forms.language-model', [
                                            'disabled' => $extensionNew->stage == 0 || $extensionNew->stage == 4,
                                        ])
                                        @endcomponent
                                    </div>
                                    {{-- <div style="position:relative;">
                                        <textarea id="docname" name="description" class="mic-input" {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }}>{{$extensionNew->description}}</textarea>
                                        <button class="mic-btn" type="button">
                                            <i class="fas fa-microphone"></i>
                                        </button>
                                    </div> --}}
                                </div>
                                {{-- @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror --}}
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="docname">Justification / Reason</label>
                                    <div class="relative-container">
                                        <textarea name="justification_reason" class="mic-input"
                                            {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }} id="justification_reason">{{ $extensionNew->justification_reason }}</textarea>
                                        @component('frontend.forms.language-model', [
                                            'disabled' => $extensionNew->stage == 0 || $extensionNew->stage == 4,
                                        ])
                                        @endcomponent
                                    </div>
                                </div>
                            </div>

                            @if ($extensionNew->file_attachment_extension)
                                @foreach (json_decode($extensionNew->file_attachment_extension) as $file)
                                    <input id="FIEXATFile-{{ $loop->index }}" type="hidden"
                                        name="existing_file_attachment_extension[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                @endforeach
                            @endif
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Inv Attachments"> Extension Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="file_attachment_extension">
                                            @if ($extensionNew->file_attachment_extension)
                                                @foreach (json_decode($extensionNew->file_attachment_extension) as $file)
                                                    <h6 class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-remove-id="FIEXATFile-{{ $loop->index }}"
                                                            data-file-name="{{ $file }}"
                                                            style="@if ($extensionNew->stage == 0 || $extensionNew->stage == 4) pointer-events: none; @endif">
                                                            <i class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i>
                                                        </a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="HOD_Attachments" name="file_attachment_extension[]"
                                                oninput="addMultipleFiles(this, 'file_attachment_extension')" multiple
                                                {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton"
                                {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }}>Save</button>
                            {{-- <button type="button" class="backButton" onclick="previousStep()">Back</button> --}}
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>

                        </div>

                    </div>
                </div>
        </div>

        <!-- reviewer content -->
        <div id="CCForm2" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="group-input">
                            <label for="reviewer_remarks">HOD Remarks</label>
                            <div class="relative-container">
                                <textarea name="reviewer_remarks" class="mic-input"
                                    {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }} id="reviewer_remarks">{{ $extensionNew->reviewer_remarks }}</textarea>
                                @component('frontend.forms.language-model', [
                                    'disabled' => $extensionNew->stage == 0 || $extensionNew->stage == 4,
                                ])
                                @endcomponent
                            </div>
                            {{-- <div style="position:relative;">
                                    <textarea name="reviewer_remarks" id="reviewer_remarks" class="mic-input"  {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }} cols="30">{{$extensionNew->reviewer_remarks}}</textarea>
                                    <button class="mic-btn" type="button">
                                        <i class="fas fa-microphone"></i>
                                    </button>
                                </div> --}}
                        </div>
                    </div>

                    {{-- <div class="col-12">
                            <div class="group-input">
                                <label for="Guideline Attachment">Reviewer Attachment  </label>
                                <div><small class="text-primary">Please Attach all relevant or supporting
                                        documents</small></div>
                                <div class="file-attachment-field">
                                    <div class="file-attachment-list" id="file_attachment_reviewer"></div>
                                    <div class="add-btn">
                                        <div>Add</div>
                                        <input type="file" id="myfile" name="file_attachment_reviewer[]"
                                            oninput="addMultipleFiles(this, 'file_attachment_reviewer')" multiple>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                    @if ($extensionNew->file_attachment_reviewer)
                        @foreach (json_decode($extensionNew->file_attachment_reviewer) as $file)
                            <input id="FIATREFile-{{ $loop->index }}" type="hidden"
                                name="existing_file_attachment_reviewer[{{ $loop->index }}]"
                                value="{{ $file }}">
                        @endforeach
                    @endif
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Inv Attachments">HOD Attachment </label>
                            <div><small class="text-primary">Please Attach all relevant or supporting
                                    documents</small></div>
                            <div class="file-attachment-field">
                                <div disabled class="file-attachment-list" id="file_attachment_reviewer">
                                    @if ($extensionNew->file_attachment_reviewer)
                                        @foreach (json_decode($extensionNew->file_attachment_reviewer) as $file)
                                            <h6 class="file-container text-dark"
                                                style="background-color: rgb(243, 242, 240);">
                                                <b>{{ $file }}</b>
                                                <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                        class="fa fa-eye text-primary"
                                                        style="font-size:20px; margin-right:-10px;"></i></a>
                                                <a type="button" class="remove-file"
                                                    data-remove-id="FIATREFile-{{ $loop->index }}"
                                                    data-file-name="{{ $file }}"
                                                    style="@if ($extensionNew->stage == 0 || $extensionNew->stage == 4) pointer-events: none; @endif">
                                                    <i class="fa-solid fa-circle-xmark"
                                                        style="color:red; font-size:20px;"></i>
                                                </a>
                                            </h6>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="HOD_Attachments" name="file_attachment_reviewer[]"
                                        oninput="addMultipleFiles(this, 'file_attachment_reviewer')" multiple
                                        {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-block">
                    <button type="submit" class="saveButton"
                        {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }}>Save</button>
                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                    <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                            Exit </a> </button>
                </div>
            </div>
        </div>
        <!-- Approver-->
        <div id="CCForm3" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="group-input">
                            <label for="approver_remarks">QA Remarks</label>
                            <div class="relative-container">
                                <textarea name="approver_remarks" class="mic-input"
                                    {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }} id="approver_remarks">{{ $extensionNew->approver_remarks }}</textarea>
                                @component('frontend.forms.language-model', [
                                    'disabled' => $extensionNew->stage == 0 || $extensionNew->stage == 4,
                                ])
                                @endcomponent
                            </div>
                            {{-- <div style="position:relative;">
                                    <textarea name="approver_remarks" id="approver_remarks" class="mic-input" {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }} cols="30">{{$extensionNew->approver_remarks}}</textarea>
                                    <button class="mic-btn" type="button">
                                        <i class="fas fa-microphone"></i>
                                    </button>
                                </div> --}}
                        </div>
                    </div>
                    @if ($extensionNew->file_attachment_approver)
                        @foreach (json_decode($extensionNew->file_attachment_approver) as $file)
                            <input id="FIATAPFile-{{ $loop->index }}" type="hidden"
                                name="existing_file_attachment_approver[{{ $loop->index }}]"
                                value="{{ $file }}">
                        @endforeach
                    @endif
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Inv Attachments"> QA Attachment</label>
                            <div><small class="text-primary">Please Attach all relevant or supporting
                                    documents</small></div>
                            <div class="file-attachment-field">
                                <div disabled class="file-attachment-list" id="file_attachment_approver">
                                    @if ($extensionNew->file_attachment_approver)
                                        @foreach (json_decode($extensionNew->file_attachment_approver) as $file)
                                            <h6 class="file-container text-dark"
                                                style="background-color: rgb(243, 242, 240);">
                                                <b>{{ $file }}</b>
                                                <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                        class="fa fa-eye text-primary"
                                                        style="font-size:20px; margin-right:-10px;"></i></a>
                                                <a type="button" class="remove-file"
                                                    data-remove-id="FIATAPFile-{{ $loop->index }}"
                                                    data-file-name="{{ $file }}"
                                                    style="@if ($extensionNew->stage == 0 || $extensionNew->stage == 4) pointer-events: none; @endif">
                                                    <i class="fa-solid fa-circle-xmark"
                                                        style="color:red; font-size:20px;"></i>
                                                </a>
                                            </h6>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="HOD_Attachments" name="file_attachment_approver[]"
                                        oninput="addMultipleFiles(this, 'file_attachment_approver')" multiple
                                        {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-block">
                    <button type="submit" class="saveButton"
                        {{ $extensionNew->stage == 0 || $extensionNew->stage == 4 ? 'disabled' : '' }}>Save</button>
                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                    <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a>
                    </button>
                </div>
            </div>
        </div>
        <!-- Activity Log content -->
        <div id="CCForm6" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="group-input">
                            <label for="Submitted By">Submitted By</label>
                            <div class="static">{{ $extensionNew->submit_by }}</div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="group-input">
                            <label for="Submitted On">Submitted On</label>
                            <div class="static">{{ $extensionNew->submit_on }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Submitted Comment">Submitted Comment</label>
                            <div class="static">{{ $extensionNew->submit_comment }}</div>
                        </div>
                    </div>
                </div>
                {{-- <div class="group-input">
                                <label for="Activated By">Submited By</label>
                                <div class="static">{{ $extensionNew->submit_by }}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Activated On">Submited On</label>
                                <div class="static">{{ $extensionNew->submit_on }}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Submitted Comment">Submitted Comment</label>
                                <div class="static">{{ $extensionNew->submitted_comment }}</div>
                            </div>
                        </div> --}}
                {{-- <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Activated On">Cancelled By</label>
                                {{-- <div class="static">{{ $extensionNew->submit_on }}</div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- <div class="col-lg-3"> --}}
                {{-- <div class="group-input"> --}}
                {{-- <label for="Activated On">Cancelled On</label> --}}
                {{-- <div class="static">{{ $extensionNew->submit_on }}</div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                <div class="row">
                    <div class="col-lg-3">
                        <div class="group-input">
                            <label for="Cancelled By">Cancelled By</label>
                            <div class="static">{{ $extensionNew->reject_by }}</div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="group-input">
                            <label for="Cancelled On">Cancelled On</label>
                            <div class="static">{{ $extensionNew->reject_on }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Cancelled Comment">Cancelled Comment</label>
                            <div class="static">{{ $extensionNew->reject_comment }}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="group-input">
                            <label for=" Rejected By">Reviewed By</label>
                            <div class="static">{{ $extensionNew->submit_by_review }}</div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="group-input">
                            <label for="Rejected On">Reviewed On</label>
                            <div class="static">{{ $extensionNew->submit_on_review }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Rejected On">Reviewed Comment</label>
                            <div class="static">{{ $extensionNew->submit_comment_review }}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="group-input">
                            <label for=" Rejected By">More Info Required By</label>
                            <div class="static">{{ $extensionNew->more_info_review_by }}</div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="group-input">
                            <label for="Rejected On">More Info Required On</label>
                            <div class="static">{{ $extensionNew->more_info_review_on }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Rejected On">More Info Required Comment</label>
                            <div class="static">{{ $extensionNew->more_info_review_comment }}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="group-input">
                            <label for=" Rejected By">Approved By</label>
                            <div class="static">{{ $extensionNew->submit_by_approved }}</div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="group-input">
                            <label for="Rejected On">Approved On</label>
                            <div class="static">{{ $extensionNew->submit_on_approved }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Rejected On">Approved Comment</label>
                            <div class="static">{{ $extensionNew->submit_commen_inapproved }}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="group-input">
                            <label for=" Rejected By">More Info Required By (In Approval)</label>
                            <div class="static">{{ $extensionNew->more_info_inapproved_by }}</div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="group-input">
                            <label for="Rejected On">More Info Required On (In Approval)</label>
                            <div class="static">{{ $extensionNew->more_info_inapproved_on }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Rejected On">More Info Required Comment (In Approval)</label>
                            <div class="static">{{ $extensionNew->more_info_inapproved_comment }}</div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-6">
                            <div class="group-input">
                                <label for=" Rejected By">More Info Required By</label>
                                <div class="static">{{ $extensionNew->more_info_inapproved_by }}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Rejected On">More Info Required On</label>
                                {{-- <div class="static">{{ $extensionNew->  }}</div> --}}
                {{-- </div>
                        </div> --}}

            </div>
            <div class="button-block">
                {{-- <button type="submit" class="saveButton">Save</button> --}}
                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                {{-- <button type="button" class="nextButton" onclick="nextStep()">Next</button> --}}
                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                        Exit </a> </button>
            </div>
        </div>
    </div>
    </form>
    </div>
    </div>
    <div class="modal fade" id="signature-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('extension_send_stage', $extensionNew->id) }}" method="POST"
                    id="signatureModalForm">
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
                            <input type="comment" name="comment">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="signatureModalButton">
                            <div class="spinner-border spinner-border-sm signatureModalSpinner" style="display: none"
                                role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Submit
                        </button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="signature-cqa-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('send-cqa', $extensionNew->id) }}" method="POST" id="signatureModalForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input class="new-moreinfo" type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input class="new-moreinfo" type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment</label>
                            <input class="new-moreinfo" type="comment" name="comment">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="signatureModalButton">
                            <div class="spinner-border spinner-border-sm signatureModalSpinner" style="display: none"
                                role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Submit
                        </button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="signature-approved-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('send-approved', $extensionNew->id) }}" method="POST" id="signatureModalForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input class="new-moreinfo" type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input class="new-moreinfo" type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment</label>
                            <input class="new-moreinfo" type="comment" name="comment">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="signatureModalButton">
                            <div class="spinner-border spinner-border-sm signatureModalSpinner" style="display: none"
                                role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Submit
                        </button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="more-info-required-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('moreinfoState_extension', $extensionNew->id) }}" method="POST">
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
                            <input class="new-moreinfo" type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input class="new-moreinfo" type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment <span class="text-danger">*</span></label>
                            <input class="new-moreinfo" type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <!-- <div class="modal-footer">
                                                                                                                                                                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                                                                                                                                                                        <button>Close</button>
                                                                                                                                                                    </div> -->
                    <div class="modal-footer">
                        <button type="submit">
                            Submit
                        </button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="reject-required-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('RejectState_extension', $extensionNew->id) }}" method="POST">
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
                            <input class="new-moreinfo" type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input class="new-moreinfo" type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment <span class="text-danger">*</span></label>
                            <input class="new-moreinfo" type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <!-- <div class="modal-footer">
                                                                                                                                                                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                                                                                                                                                                        <button>Close</button>
                                                                                                                                                                    </div> -->
                    <div class="modal-footer">
                        <button type="submit">
                            Submit
                        </button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        console.log('Script working')

        $(document).ready(function() {


            function submitForm() {

                let auditForm = document.getElementById('auditForm');


                console.log('sumitting form')

                document.querySelectorAll('.saveAuditFormBtn').forEach(function(button) {
                    button.disabled = true;
                })

                document.querySelectorAll('.auditFormSpinner').forEach(function(spinner) {
                    spinner.style.display = 'flex';
                })

                extensionForm.submit();
            }


        });

        document.addEventListener('DOMContentLoaded', function() {
            var signatureForm = document.getElementById('signatureModalForm');

            signatureForm.addEventListener('submit', function(e) {

                var submitButton = signatureForm.querySelector('.signatureModalButton');
                var spinner = signatureForm.querySelector('.signatureModalSpinner');

                submitButton.disabled = true;

                spinner.style.display = 'inline-block';
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var signatureForm = document.getElementById('pendingInitiatorForm');

            signatureForm.addEventListener('submit', function(e) {

                var submitButton = signatureForm.querySelector('.pendingInitiatorModalButton');
                var spinner = signatureForm.querySelector('.pendingInitiatorModalSpinner');

                submitButton.disabled = true;

                spinner.style.display = 'inline-block';
            });
        });


        // =========================
        wow = new WOW({
            boxClass: 'wow', // default
            animateClass: 'animated', // default
            offset: 0, // default
            mobile: true, // default
            live: true // default
        })
        wow.init();
    </script>
    <script>
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
        }



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
        VirtualSelect.init({
            ele: '#Facility, #Group, #Audit, #Auditee ,#reference_record, #designee, #hod'
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
        $(document).ready(function() {
            $('.remove-file').click(function() {
                const removeId = $(this).data('remove-id')
                console.log('removeId', removeId);
                $('#' + removeId).remove();
            })
        })
    </script>



@endsection
