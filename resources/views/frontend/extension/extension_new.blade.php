@extends('frontend.layout.main')
@section('container')
    @php
        $users = DB::table('users')->select('id', 'name', 'role')->where('active', 1)->get();
        $userRoles = DB::table('user_roles')->select('user_id')->where('q_m_s_roles_id', 4)->distinct()->get();
        $departments = DB::table('departments')->select('id', 'name')->get();
        $divisions = DB::table('q_m_s_divisions')->select('id', 'name')->get();

        $userIds = DB::table('user_roles')->where('q_m_s_roles_id', 4)->distinct()->pluck('user_id');

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
    </style>
    </style>

    <script>
        $(document).ready(function() {
            $('#ObservationAdd').click(function(e) {
                function generateTableRow(serialNumber) {

                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="jobResponsibilities[' + serialNumber +
                        '][serial]" value="' + serialNumber +
                        '"></td>' +
                        '<td><input type="text" name="jobResponsibilities[' + serialNumber +
                        '][job]"></td>' +
                        '<td><input type="text" class="Document_Remarks" name="jobResponsibilities[' +
                        serialNumber + '][remarks]"></td>' +


                        '</tr>';

                    return html;
                }

                var tableBody = $('#job-responsibilty-table tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>
    <div class="form-field-head">

        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName(session()->get('division')) }} /
            {{-- {{ Helpers::getDivisionName($data->division_id) }} / --}}
            Extension
        </div>
    </div>


    {{-- ======================================
                    DATA FIELDS
    ======================================= --}}
    <div id="change-control-fields">
        <div class="container-fluid">

            <!-- Tab links -->
            <div class="cctab">

                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
                <button class="cctablinks " onclick="openCity(event, 'CCForm2')">HOD Review</button>
                <button class="cctablinks " onclick="openCity(event, 'CCForm3')">QA Approval</button>

                <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Activity Log</button>

            </div>
            <form action="{{ route('extension_new.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Tab content -->
                <div id="step-form">
                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                @if (!empty($parent_id))
                                    <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                                    <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                                    <input type="hidden" name="parent_record" id="parent_record"
                                        value="{{ $parent_record }}">
                                @endif
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number"><b>Record Number</b></label>
                                        <input disabled type="text" name="record_number"
                                            value="{{ Helpers::getDivisionName($parentDivisionId) }}/Ext/{{ date('y') }}/{{ $record_number }}">
                                        {{-- value="{{ Helpers::getDivisionName($data->division_id) }}/DEV/{{ Helpers::year($data->created_at) }}/{{ $data->record }}"> --}}

                                        {{-- <div class="static">QMS-EMEA/CAPA/{{ date('Y') }}/{{ $record_number }}</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code"><b>Site/Location Code</b></label>
                                        <input readonly type="text"
                                            value="{{ Helpers::getDivisionName($parentDivisionId) }}">
                                        <input type="hidden" name="site_location_code" value="{{ $parentDivisionId }}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator"><b>Initiator</b></label>
                                        <input disabled type="text" name="initiator" value="{{ Auth::user()->name }}">

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
                                        <input readonly type="text" value="{{ date('d-M-Y') }}"
                                            name="initiation_date_new" id="initiation_date"
                                            style="background-color: light-dark(rgba(239, 239, 239, 0.3), rgba(59, 59, 59, 0.3))">
                                        <input type="hidden" value="{{ date('Y-m-d') }}" name="initiation_date">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="short_description">Short Description <span
                                                class="text-danger">*</span></label>
                                        <span id="rchars">255</span> Characters remaining
                                        <div class="relative-container">
                                            <input id="docname" maxlength="255" name="short_description"
                                                class="mic-input">
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                        {{-- <div style="position:relative;">
                                    <input id="short_description" type="text" name="short_description" maxlength="255" required class="mic-input">
                                    <button class="mic-btn" type="button">
                                        <i class="fas fa-microphone"></i>
                                    </button>
                                </div> --}}
                                    </div>
                                    <script>
                                        var maxLength = 255;
                                        $('#docname').keyup(function() {
                                            var textlen = maxLength - $(this).val().length;
                                            $('#rchars').text(textlen);
                                        });
                                    </script>
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
                                        <label for="Assigned To">HOD review </label>
                                        <select id="choices-multiple-remove" class="choices-multiple-reviewe"
                                            name="reviewers" placeholder="Select Reviewers">
                                            <option value="">-- Select --</option>
                                            @if (!empty($users))
                                                s

                                                @foreach ($users as $lan)
                                                    @if (Helpers::checkUserRolesreviewer($lan))
                                                        <option value="{{ $lan->id }}">
                                                            {{ $lan->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Assigned To">QA approval </label>
                                        <select id="choices-multiple-remove-but" class="choices-multiple-reviewer"
                                            name="approvers" id="approvers" placeholder="Select approvers">
                                            <option value="">-- Select --</option>


                                            @if (!empty($users))
                                                @foreach ($users as $lan)
                                                    <option value="{{ $lan->id }}">
                                                        {{ $lan->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Actual Start Date"></label>
                                    <div class="calenderauditee">
                                        <input type="text" id="start_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="current_due_date"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                            class="hide-input" oninput="handleDateInput(this, 'start_date')" />
                                    </div>
                                </div>
                            </div>

                             <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Actual Start Date"></label>
                                    <div class="calenderauditee">
                                        <input type="text" id="test_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="proposed_due_date"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                            class="hide-input" oninput="handleDateInput(this, 'test_date')" />
                                    </div>
                                </div>
                            </div> --}}

                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Audit Schedule Start Date">Current Due Date (Parent)</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="start_date" name="current_due_date" readonly
                                                placeholder="DD-MM-YYYY" />
                                            <input type="date" id="current_due_date" name="start_date"
                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'start_date');checkDate('current_due_date','proposed_due_date')" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Audit Schedule End Date">Proposed Due Date</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="end_date" name="proposed_due_date" readonly
                                                placeholder="DD-MM-YYYY" />
                                            <input type="date" id="proposed_due_date" name="end_date"
                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'end_date');checkDate('current_due_date','proposed_due_date')" />
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
                                        var startDate = document.getElementById('current_due_date').value;
                                        var endDateInput = document.getElementById('proposed_due_date');
                                        if (startDate) {
                                            endDateInput.setAttribute('min', startDate);
                                        }
                                    }

                                    document.addEventListener("DOMContentLoaded", function() {
                                        updateEndDateMin(); // Initialize the end date min on page load

                                        // Reapply the min attribute whenever the start date is changed
                                        document.getElementById('current_due_date').addEventListener('input', function() {
                                            updateEndDateMin();
                                        });
                                    });
                                </script>
                                {{-- <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date ">
                                    <label for="date_due"> Due Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" name="capa_date_due" id="date_due" readonly placeholder="DD-MM-YYYY" />
                                        <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            id="date_due_checkdate" class="hide-input"
                                            oninput="handleDateInput(this, 'date_due');checkDate('date_Response_due_checkdate','date_due_checkdate')" />
                                    </div>
                                </div>
                            </div> --}}

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="description">Description</label>
                                        <div class="relative-container">
                                            <textarea name="description" id="description" cols="30" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                    {{-- @error('short_description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror --}}
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="justification_reason">Justification / Reason</label>

                                        <div class="relative-container">
                                            <textarea name="justification_reason" id="justification_reason" cols="30" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                        {{-- <div style="position:relative;">
                                        <textarea name="justification_reason" id="justification_reason" cols="30" class="mic-input"></textarea>
                                        <button class="mic-btn" type="button">
                                            <i class="fas fa-microphone"></i>
                                        </button>
                                    </div> --}}
                                    </div>

                                    {{-- @error('short_description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror --}}
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Guideline Attachment">Extension Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="file_attachment_extension"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="file_attachment_extension[]"
                                                    oninput="addMultipleFiles(this, 'file_attachment_extension')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
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
                                        <textarea name="reviewer_remarks" id="reviewer_remarks" cols="30" class="mic-input"></textarea>
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                    {{-- <div style="position:relative;">
                                    <textarea name="reviewer_remarks" id="reviewer_remarks" cols="30" class="mic-input"></textarea>
                                    <button class="mic-btn" type="button">
                                        <i class="fas fa-microphone"></i>
                                    </button>
                                </div> --}}
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Guideline Attachment">HOD Attachment</label>
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
                            </div>
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
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
                                        <textarea name="approver_remarks" id="approver_remarks" cols="30" class="mic-input"></textarea>
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                    {{-- <div style="position:relative;">
                                    <textarea name="approver_remarks" id="approver_remarks" cols="30" class="mic-input"></textarea>
                                    <button class="mic-btn" type="button">
                                        <i class="fas fa-microphone"></i>
                                    </button>
                                </div> --}}
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Guideline Attachment">QA Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="file_attachment_approver"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="file_attachment_approver[]"
                                                oninput="addMultipleFiles(this, 'file_attachment_approver')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                        Exit </a> </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Activity Log content -->
                <div id="CCForm6" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Activated By">Submitted By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Activated On">Submitted On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Activated By">Submitted Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Activated By">Cancelled By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Activated On">Cancelled On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Activated By">Cancelled Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for=" Rejected By">Reviewed By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Rejected On">Reviewed On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Activated By">Reviewed Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for=" Rejected By">More Info Required By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Rejected On">More Info Required On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Activated By"> More Info Required Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for=" Rejected By">Approved By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Rejected On">Approved On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Activated By">Approved Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for=" Rejected By">More Info Required By (In Approval)</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Rejected On">More Info Required On (In Approval)</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Rejected On">More Info Required Comment (In Approval)</label>
                                    <div class="static"></div>
                                </div>
                            </div>

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
    </script>
    <script>
        VirtualSelect.init({
            ele: '#Facility, #Group, #Audit, #Auditee ,#reference_record, #designee, #hod'
        });
    </script>

    {{-- <style>
    .mic-btn {
        background: none;
        border: none;
        outline: none;
        cursor: pointer;
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        box-shadow: none;
        color: black;
        display: none;
        /* Hide the button initially */
    }

    .relative-container textarea {
        width: 100%;
        padding-right: 40px;
    }

    .relative-container input:focus+.mic-btn {
        display: inline-block;
        /* Show the button when input is focused */
    }

    .mic-btn:focus,
    .mic-btn:hover,
    .mic-btn:active {
        box-shadow: none;
    }
</style> --}}

    <script>
        < link rel = "stylesheet"
        href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" >
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recognition = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';

            function startRecognition(targetElement) {
                recognition.start();
                recognition.onresult = function(event) {
                    const transcript = event.results[0][0].transcript;
                    targetElement.value += transcript;
                };
                recognition.onerror = function(event) {
                    console.error(event.error);
                };
            }

            document.addEventListener('click', function(event) {
                if (event.target.closest('.mic-btn')) {
                    const button = event.target.closest('.mic-btn');
                    const inputField = button.previousElementSibling;
                    if (inputField && inputField.classList.contains('mic-input')) {
                        startRecognition(inputField);
                    }
                }
            });

            document.querySelectorAll('.mic-input').forEach(input => {
                input.addEventListener('focus', function() {
                    const micBtn = this.nextElementSibling;
                    if (micBtn && micBtn.classList.contains('mic-btn')) {
                        micBtn.style.display = 'inline-block';
                    }
                });

                input.addEventListener('blur', function() {
                    const micBtn = this.nextElementSibling;
                    if (micBtn && micBtn.classList.contains('mic-btn')) {
                        setTimeout(() => {
                            micBtn.style.display = 'none';
                        }, 200); // Delay to prevent button from hiding immediately when clicked
                    }
                });
            });
        });
    </script>
@endsection
