@extends('frontend.layout.main')
@section('container')

    <script>
        function addFishBone(top, bottom) {
            let mainBlock = document.querySelector('.fishbone-ishikawa-diagram');
            let topBlock = mainBlock.querySelector(top)
            let bottomBlock = mainBlock.querySelector(bottom)

            let topField = document.createElement('div')
            topField.className = 'grid-field fields top-field'

            let measurement = document.createElement('div')
            let measurementInput = document.createElement('input')
            measurementInput.setAttribute('type', 'text')
            measurementInput.setAttribute('name', 'measurement[]')
            measurement.append(measurementInput)
            topField.append(measurement)

            let materials = document.createElement('div')
            let materialsInput = document.createElement('input')
            materialsInput.setAttribute('type', 'text')
            materialsInput.setAttribute('name', 'materials[]')
            materials.append(materialsInput)
            topField.append(materials)

            let methods = document.createElement('div')
            let methodsInput = document.createElement('input')
            methodsInput.setAttribute('type', 'text')
            methodsInput.setAttribute('name', 'methods[]')
            methods.append(methodsInput)
            topField.append(methods)

            topBlock.prepend(topField)

            let bottomField = document.createElement('div')
            bottomField.className = 'grid-field fields bottom-field'

            let environment = document.createElement('div')
            let environmentInput = document.createElement('input')
            environmentInput.setAttribute('type', 'text')
            environmentInput.setAttribute('name', 'environment[]')
            environment.append(environmentInput)
            bottomField.append(environment)

            let manpower = document.createElement('div')
            let manpowerInput = document.createElement('input')
            manpowerInput.setAttribute('type', 'text')
            manpowerInput.setAttribute('name', 'manpower[]')
            manpower.append(manpowerInput)
            bottomField.append(manpower)

            let machine = document.createElement('div')
            let machineInput = document.createElement('input')
            machineInput.setAttribute('type', 'text')
            machineInput.setAttribute('name', 'machine[]')
            machine.append(machineInput)
            bottomField.append(machine)

            bottomBlock.append(bottomField)
        }

        function deleteFishBone(top, bottom) {
            let mainBlock = document.querySelector('.fishbone-ishikawa-diagram');
            let topBlock = mainBlock.querySelector(top)
            let bottomBlock = mainBlock.querySelector(bottom)
            if (topBlock.firstChild) {
                topBlock.removeChild(topBlock.firstChild);
            }
            if (bottomBlock.lastChild) {
                bottomBlock.removeChild(bottomBlock.lastChild);
            }
        }
    </script>
    <script>
        function addWhyField(con_class, name) {
            let mainBlock = document.querySelector('.why-why-chart')
            let container = mainBlock.querySelector(`.${con_class}`)
            let textarea = document.createElement('textarea')
            textarea.setAttribute('name', name);
            container.append(textarea)
        }
    </script>
    {{-- <script>
        function calculateInitialResult(selectElement) {
            let row = selectElement.closest('tr');
            let R = parseFloat(row.querySelector('.fieldR').value) || 0;
            let P = parseFloat(row.querySelector('.fieldP').value) || 0;
            let N = parseFloat(row.querySelector('.fieldN').value) || 0;
            let result = R * P * N;

            // Update the result field within the row
            row.querySelector('.initial-rpn').value = result;
        }
    </script>
    <script>
        function calculateResidualResult(selectElement) {
            // Get the row containing the changed select element
            let row = selectElement.closest('tr');

            // Get values from select elements within the row
            let R = parseFloat(row.querySelector('.residual-fieldR').value) || 0;
            let P = parseFloat(row.querySelector('.residual-fieldP').value) || 0;
            let N = parseFloat(row.querySelector('.residual-fieldN').value) || 0;

            // Perform the calculation
            let result = R * P * N;

            // Update the result field within the row
            row.querySelector('.residual-rpn').value = result;
        }
    </script> --}}
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
        function calculateRiskAnalysis2(selectElement) {
            // Get the row containing the changed select element
            let row = selectElement.closest('tr');

            // Get values from select elements within the row
            let R = parseFloat(document.getElementById('analysisR2').value) || 0;
            let P = parseFloat(document.getElementById('analysisP2').value) || 0;
            let N = parseFloat(document.getElementById('analysisN2').value) || 0;

            // Perform the calculation
            let result = R * P * N;

            // Update the result field within the row
            document.getElementById('analysisRPN2').value = result;
        }
    </script>
    <style>
        textarea.note-codable {
            display: none !important;
        }

        header {
            display: none;
        }
    </style>

    <script>
        $(document).ready(function() {
            <?php if ($data->stage == 7): ?>
            $("#target :input").prop("disabled", true);
            <?php endif; ?>
        });
    </script>



    @php
        $users = DB::table('users')->get();
    @endphp

    <div class="form-field-head">

        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName($data->division_id) }} / Risk Assesment
        </div>
    </div>

    {{-- ---------------------- --}}
    <div id="change-control-view">
        <div class="container-fluid">

            <div class="inner-block state-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="main-head">Record Workflow </div>

                    <div class="d-flex" style="gap:20px;">
                        @php
                            $userRoles = DB::table('user_roles')
                                ->where(['user_id' => Auth::user()->id])
                                ->get();
                            $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
                        @endphp
                        {{-- <a href="{{route('riskSingleReport', $data->id)}}"><button class="button_theme1"
                            class="new-doc-btn">Print</button></a> --}}

                        <button class="button_theme1"> <a class="text-white" href="{{ url('riskAuditTrial', $data->id) }}">
                                Audit Trail </a> </button>

                        @if ($data->stage == 1 && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Submit
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Cancel
                            </button>
                        @elseif($data->stage == 2 && (in_array(4, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#more_info-modal">
                                More Information Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Evaluation Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Cancel
                            </button>
                        @elseif($data->stage == 3 && (in_array(16, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Action Plan Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#more_info-modal">
                                Request More Info
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button>
                        @elseif($data->stage == 4 && (in_array(4, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Action Plan Approved
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#more_info-modal">
                                Reject Action Plan
                            </button>
                        @elseif($data->stage == 5 && (in_array(7, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                All Actions Completed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#more_info-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 6 && (in_array(7, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Residual Risk Evaluation Completed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#more_info-modal">
                                More Actions Needed
                            </button>
                        @endif
                        <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit
                            </a> </button>


                    </div>

                </div>
                <div class="status">
                    <div class="head">Current Status</div>
                    {{-- ------------------------------By Pankaj-------------------------------- --}}
                    @if ($data->stage == 0)
                        <div class="progress-bars">
                            <div class="bg-danger">Closed-Cancelled</div>
                        </div>
                    @else
                        <div class="progress-bars" style="font-size: 14px;">
                            @if ($data->stage >= 1)
                                <div class="active">Opened</div>
                            @else
                                <div class="">Opened</div>
                            @endif

                            @if ($data->stage >= 2)
                                <div class="active">Risk Analysis & Work Group Assignment </div>
                            @else
                                <div class="">Risk Analysis & Work Group Assignment</div>
                            @endif

                            @if ($data->stage >= 3)
                                <div class="active">Risk Processing & Action Plan </div>
                            @else
                                <div class="">Risk Processing & Action Plan</div>
                            @endif

                            @if ($data->stage >= 4)
                                <div class="active">Pending HOD Approval </div>
                            @else
                                <div class="">Pending HOD Approval </div>
                            @endif


                            @if ($data->stage >= 5)
                                <div class="active">Actions Items in Progress</div>
                            @else
                                <div class="">Actions Items in Progress</div>
                            @endif
                            @if ($data->stage >= 6)
                                <div class="active">Residual Risk Evaluation</div>
                            @else
                                <div class="">Residual Risk Evaluation</div>
                            @endif
                            @if ($data->stage >= 7)
                                <div class="bg-danger">Closed - Done</div>
                            @else
                                <div class="">Closed - Done</div>
                            @endif
                    @endif


                </div>
                {{-- @endif --}}
                {{-- ---------------------------------------------------------------------------------------- --}}
            </div>
        </div>

        <div class="control-list">





            {{-- ======================================
                    DATA FIELDS
    ======================================= --}}
            <div id="change-control-fields">
                <div class="container-fluid">

                    <!-- Tab links -->
                    <div class="cctab">
                        <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Risk/Opportunity
                            Assesment</button>
                        <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Risk/Opportunity details </button>
                        <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Work Group Assignment</button>
                        <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Risk/Opportunity Analysis</button>
                        <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Residual Risk</button>
                        <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Risk Mitigation</button>
                        <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Signatures</button>
                    </div>

                    <form action="{{ route('riskUpdate', $data->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div id="step-form">

                            <!-- Risk Management Tab content -->
                            <div id="CCForm1" class="inner-block cctabcontent">

                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="RLS Record Number"><b>Record Number</b></label>
                                                <input disabled type="text" name="record_number"
                                                    value=" {{ Helpers::getDivisionName($data->division_id) }}/RA/{{ Helpers::year($data->created_at) }}/{{ $data->record }}">
                                                {{-- <div class="static">QMS-EMEA/CAPA/{{ date('Y') }}/{{ $record_number }}</div> --}}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Division Code"><b>Site/Location Code</b></label>
                                                <input readonly type="text" name="division_code"
                                                    value=" {{ Helpers::getDivisionName($data->division_id) }}">
                                                {{-- <div class="static">QMS-North America</div> --}}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Initiator"><b>Initiator</b></label>
                                                {{-- <div class="static">{{ Auth::user()->name }}</div> --}}
                                                <input disabled type="text" name="division_code"
                                                    value="{{ $data->initiator_name }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Date Due"><b>Date of Initiation</b></label>
                                                <input disabled type="text"
                                                    value="{{ Helpers::getdateFormat($data->intiation_date) }}"
                                                    name="intiation_date">
                                                {{-- <input type="hidden" value="{{ $data->intiation_date }}" name="intiation_date"> --}}

                                                {{-- <div class="static">{{ date('d-M-Y') }}</div> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="group-input">
                                                <label for="search">
                                                    Assigned To <span class="text-danger"></span>
                                                </label>
                                                <select id="select-state" placeholder="Select..." name="assign_to"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Select a value</option>
                                                    @foreach ($users as $value)
                                                        <option {{ $data->assign_to == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('assign_to')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="group-input">
                                                <label for="due-date">Due Date <span class="text-danger"></span></label>
                                                <div><small class="text-primary">If revising Due Date, kindly mention
                                                        revision reason in "Due Date Extension Justification" data
                                                        field.</small></div>
                                                <input readonly type="text"
                                                    value="{{ Helpers::getdateFormat($data->due_date) }}"
                                                    name="due_date">

                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Initiator Group"><b>Initiator Group</b></label>
                                                <select name="Initiator_Group"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                    id="initiator_group">
                                                    <option value="">-- Select --</option>
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
                                                    value="{{ $data->Initiator_Group }}" id="initiator_group_code"
                                                    readonly>
                                            </div>
                                        </div>
                                        {{-- <div class="col-12">
                                            <div class="group-input">
                                                <label for="Short Description">Short Description <span
                                                        class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please mention brief summary</small></div>
                                                <textarea name="short_description" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }} id="short_desc">{{ $data->short_description }}</textarea>
                                            </div>
                                        </div> --}}
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Short Description">Short Description<span
                                                        class="text-danger">*</span></label><span
                                                    id="rchars">255</span>
                                                characters remaining
                                                <div class="relative-container">
                                                    <input id="docname" type="text" name="short_description"
                                                        class="mic-input" maxlength="255" required
                                                        value="{{ $data->short_description }}"
                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                            {{-- <p id="docnameError" style="color:red">**Short Description is required</p> --}}
                                        </div>


                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="severity-level">Severity Level</label>
                                                <span class="text-primary">Severity levels in a QMS record gauge issue
                                                    seriousness, guiding priority for corrective actions. Ranging from low
                                                    to high, they ensure quality standards and mitigate critical
                                                    risks.</span>
                                                <select {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                    name="severity2_level">
                                                    <option value="0">-- Select --</option>
                                                    <option @if ($data->severity2_level == 'minor') selected @endif
                                                        value="minor">Minor</option>
                                                    <option @if ($data->severity2_level == 'major') selected @endif
                                                        value="major">Major</option>
                                                    <option @if ($data->severity2_level == 'critical') selected @endif
                                                        value="critical">Critical</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- --------------------------------------- --}}
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Department(s)">Department(s)</label>
                                                <select multiple name="departments[]" placeholder="Select Departments"
                                                    data-search="false" data-silent-initial-value-set="true"
                                                    id="departments"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>

                                                    <option value="QA"
                                                        {{ in_array('QA', explode(',', $data->departments)) ? 'selected' : '' }}>
                                                        QA</option>
                                                    <option value="QC"
                                                        {{ in_array('QC', explode(',', $data->departments)) ? 'selected' : '' }}>
                                                        QC</option>
                                                    <option value="R&D"
                                                        {{ in_array('R&D', explode(',', $data->departments)) ? 'selected' : '' }}>
                                                        R&D</option>
                                                    <option value="Manufacturing"
                                                        {{ in_array('Manufacturing', explode(',', $data->departments)) ? 'selected' : '' }}>
                                                        Manufacturing</option>
                                                    <option value="Warehouse"
                                                        {{ in_array('Warehouse', explode(',', $data->departments)) ? 'selected' : '' }}>
                                                        Warehouse</option>

                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col-12">
                                            <div class="group-input">
                                                <label for="Team Members">Team Members</label>
                                                <select multiple name="team_members[]" placeholder="Select Team Members"
                                                    data-search="false" data-silent-initial-value-set="true"
                                                    id="team_members"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">select team member</option>
                                                    <option value="1"
                                                        {{ in_array('1', explode(',', $data->team_members)) ? 'selected' : '' }}>
                                                        Amit Guru</option>
                                                    <option value="2"
                                                        {{ in_array('2', explode(',', $data->team_members)) ? 'selected' : '' }}>
                                                        Anshul Patel</option>
                                                    <option value="3"
                                                        {{ in_array('3', explode(',', $data->team_members)) ? 'selected' : '' }}>
                                                        Vikash Prajapati</option>
                                                    <option value="4"
                                                        {{ in_array('4', explode(',', $data->team_members)) ? 'selected' : '' }}>
                                                        Amit Patel</option>
                                                    <option value="5"
                                                        {{ in_array('5', explode(',', $data->team_members)) ? 'selected' : '' }}>
                                                        Shaleen Mishra</option>
                                                    <option value="6"
                                                        {{ in_array('6', explode(',', $data->team_members)) ? 'selected' : '' }}>
                                                        Madhulika Mishra</option>

                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Sourcd of Risk">Source of Risk/Opportunity</label>
                                                <select name="source_of_risk" id="source_of_risk"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->source_of_risk == 'Audit' ? 'selected' : '' }}
                                                        value="Audit">Audit</option>
                                                    <option {{ $data->source_of_risk == 'Complaint' ? 'selected' : '' }}
                                                        value="Complaint">Complaint</option>
                                                    <option {{ $data->source_of_risk == 'Employee' ? 'selected' : '' }}
                                                        value="Employee">Employee</option>
                                                    <option {{ $data->source_of_risk == 'Other' ? 'selected' : '' }}
                                                        value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Type..">Type</label>
                                                <select name="type" id="type"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->type == 'Other' ? 'selected' : '' }} value="Other">
                                                        Other</option>
                                                    <option {{ $data->type == 'Business_Risk' ? 'selected' : '' }}
                                                        value="Business_Risk">Business Risk</option>
                                                    <option {{ $data->type == 'custumer_Related' ? 'selected' : '' }}
                                                        value="custumer_Related">Customer-Related Risk(Complaint)
                                                    </option>
                                                    <option {{ $data->type == 'Opportunity' ? 'selected' : '' }}
                                                        value="Opportunity">Opportunity
                                                    </option>
                                                    <option {{ $data->type == 'Market' ? 'selected' : '' }}
                                                        value="Market">Market</option>
                                                    <option {{ $data->type == 'Operational_Risk' ? 'selected' : '' }}
                                                        value="Operational_Risk">Operational Risk</option>
                                                    <option {{ $data->type == 'Strategic_Rick' ? 'selected' : '' }}
                                                        value="Strategic_Risk">Strategic Risk</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Priority Level">Priority Level</label>
                                                <select name="priority_level" id="priority_level"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->priority_level == 'High' ? 'selected' : '' }}
                                                        value="High">High</option>
                                                    <option {{ $data->priority_level == 'medium' ? 'selected' : '' }}
                                                        value="medium">medium</option>
                                                    <option {{ $data->priority_level == 'Low' ? 'selected' : '' }}
                                                        value="Low">Low</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Zone">Zone</label>
                                                <select name="zone" id="zone"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="{{ $data->zone }}">Enter Your Selection Here</option>
                                                    <option {{ $data->zone == 'Asia' ? 'selected' : '' }} value="Asia">
                                                        Asia</option>
                                                    <option {{ $data->zone == 'Europe' ? 'selected' : '' }}
                                                        value="Europe">Europe</option>
                                                    <option {{ $data->zone == 'Africa' ? 'selected' : '' }}
                                                        value="Africa">Africa</option>
                                                    <option {{ $data->zone == 'Central_America' ? 'selected' : '' }}
                                                        value="Central_America">Central America</option>
                                                    <option {{ $data->zone == 'South_America' ? 'selected' : '' }}
                                                        value="South_America">South America</option>
                                                    <option {{ $data->zone == 'Oceania' ? 'selected' : '' }}
                                                        value="Oceania">Oceania</option>
                                                    <option {{ $data->zone == 'North_America' ? 'selected' : '' }}
                                                        value="North_America">North America</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Country">Country</label>
                                                <select name="country" class="countries" id="country" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="{{ $data->country }}">Select Country</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="State/District">State/District</label>
                                                <select name="state" class="states" id="stateId" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="{{ $data->state }}">Select State</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="City">City</label>
                                                <select name="city" class="cities" id="city" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="{{ $data->city }}">Select City</option>

                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="col-6">
                                            <div class="group-input">
                                                <label for="Description">Risk/Opportunity Description</label>
                                                <div class="relative-container">
                                                    <textarea name="description" class="mic-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        id="description">{{ $data->description }}</textarea>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Comments">Risk/Opportunity Comments</label>
                                                <div class="relative-container">
                                                    <textarea name="comments" class="mic-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        id="comments">{{ $data->comments }}</textarea>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="button-block">
                                        <button type="submit" id="ChangeSaveButton" class="saveButton"
                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>Save</button>
                                        <button type="button" id="ChangeNextButton" class="nextButton">Next</button>
                                        <button type="button"> <a class="text-white"
                                                href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                                    </div>
                                </div>

                            </div>

                            <!-- Risk Details content -->
                            <div id="CCForm2" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Department(s)">Department(s)</label>
                                                <select multiple name="departments2[]" placeholder="Select Departments"
                                                    data-search="false" data-silent-initial-value-set="true"
                                                    id="departments"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>

                                                    <option value="QA"
                                                        {{ in_array('QA', explode(',', $data->departments2)) ? 'selected' : '' }}>
                                                        QA</option>
                                                    <option value="QC"
                                                        {{ in_array('QC', explode(',', $data->departments2)) ? 'selected' : '' }}>
                                                        QC</option>
                                                    <option value="R&D"
                                                        {{ in_array('R&D', explode(',', $data->departments2)) ? 'selected' : '' }}>
                                                        R&D</option>
                                                    <option value="Manufacturing"
                                                        {{ in_array('Manufacturing', explode(',', $data->departments2)) ? 'selected' : '' }}>
                                                        Manufacturing</option>
                                                    <option value="Warehouse"
                                                        {{ in_array('Warehouse', explode(',', $data->departments2)) ? 'selected' : '' }}>
                                                        Warehouse</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Sourcd of Risk">Source of Risk</label>
                                                <select name="source_of_risk2" id="sourcd_of_risk"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->source_of_risk2 == 'Audit' ? 'selected' : '' }}
                                                        value="Audit">Audit</option>
                                                    <option {{ $data->source_of_risk2 == 'Complaint' ? 'selected' : '' }}
                                                        value="Complaint">Complaint</option>
                                                    <option {{ $data->source_of_risk2 == 'Employee' ? 'selected' : '' }}
                                                        value="Employee">Employee</option>
                                                    <option {{ $data->source_of_risk2 == 'Other' ? 'selected' : '' }}
                                                        value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Site Name">Site Name</label>
                                                <select name="site_name" id="site_name"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->site_name == 'City MFR A' ? 'selected' : '' }}
                                                        value="City MFR A">City MFR A</option>
                                                    <option {{ $data->site_name == 'City MFR B' ? 'selected' : '' }}
                                                        value="City MFR B">City MFR B</option>
                                                    <option {{ $data->site_name == 'City MFR C' ? 'selected' : '' }}
                                                        value="City MFR C">City MFR C</option>
                                                    <option {{ $data->site_name == 'Complex A' ? 'selected' : '' }}
                                                        value="Complex A">Complex A</option>
                                                    <option {{ $data->site_name == 'Complex B' ? 'selected' : '' }}
                                                        value="Complex B">Complex B</option>
                                                    <option {{ $data->site_name == 'Marketing A' ? 'selected' : '' }}
                                                        value="Marketing A">Marketing A</option>
                                                    <option {{ $data->site_name == 'Marketing B' ? 'selected' : '' }}
                                                        value="Marketing B">Marketing B</option>
                                                    <option {{ $data->site_name == 'Marketing C' ? 'selected' : '' }}
                                                        value="Marketing C">Marketing C</option>
                                                    <option {{ $data->site_name == 'Oceanside' ? 'selected' : '' }}
                                                        value="Oceanside">Oceanside</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Building.">Building.</label>
                                                <select name="building" id="building"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->building == 'A' ? 'selected' : '' }} value="A">
                                                        A</option>
                                                    <option {{ $data->building == 'B' ? 'selected' : '' }} value="B">
                                                        B</option>
                                                    <option {{ $data->building == 'C' ? 'selected' : '' }} value="C">
                                                        C</option>
                                                    <option {{ $data->building == 'D' ? 'selected' : '' }} value="D">
                                                        D</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Floor...">Floor</label>
                                                <select name="floor" id="floor"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->floor == 'First' ? 'selected' : '' }}
                                                        value="First">First</option>
                                                    <option {{ $data->floor == 'Second' ? 'selected' : '' }}
                                                        value="Second">Second</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Room">Room</label>
                                                <select name="room" id="room"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->room == 'C-101' ? 'selected' : '' }} value="C-101">
                                                        C-101</option>
                                                    <option {{ $data->room == 'C-202' ? 'selected' : '' }} value="C-202">
                                                        C-202</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="Reference Recores">Related Record</label>
                                                <select {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }} multiple id="related_record" name="related_record[]" id="">
                                                    <option value="">--Select---</option>
                                                    @foreach ($old_record as $new)
                                                        <option value="{{ $new->id }}" {{ in_array($new->id, explode(',', $data->refrence_record)) ? 'selected' : '' }}>
                                                            {{ Helpers::getDivisionName($new->division_id) }}/RA/{{date('Y')}}/{{ Helpers::recordFormat($new->record) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Duration">Duration</label>
                                                <select name="duration" id="duration"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->duration == '2 hours' ? 'selected' : '' }}
                                                        value="2 hours">
                                                        2 hours</option>
                                                    <option {{ $data->duration == '4 hours' ? 'selected' : '' }}
                                                        value="4 hours">
                                                        4 hours</option>
                                                    <option {{ $data->duration == '8 hours' ? 'selected' : '' }}
                                                        value="8 hours">
                                                        8 hours</option>
                                                    <option {{ $data->duration == '16 hours' ? 'selected' : '' }}
                                                        value="16 hours">
                                                        16 hours</option>
                                                    <option {{ $data->duration == '24 hours' ? 'selected' : '' }}
                                                        value="24 hours">
                                                        24 hours</option>
                                                    <option {{ $data->duration == '36 hours' ? 'selected' : '' }}
                                                        value="36 hours">
                                                        36 hours</option>
                                                    <option {{ $data->duration == '72 hours' ? 'selected' : '' }}
                                                        value="72 hours">
                                                        72 hours</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Hazard">Hazard</label>
                                                <select name="hazard" id="hazard"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->hazard == 'Confined Space' ? 'selected' : '' }}
                                                        value="Confined Space">
                                                        Confined Space</option>
                                                    <option {{ $data->hazard == 'Electrical' ? 'selected' : '' }}
                                                        value="Electrical">
                                                        Electrical</option>
                                                    <option {{ $data->hazard == 'Energy use' ? 'selected' : '' }}
                                                        value="Energy use">
                                                        Energy use</option>
                                                    <option {{ $data->hazard == 'Ergonomics' ? 'selected' : '' }}
                                                        value="Ergonomics">
                                                        Ergonomics</option>
                                                    <option {{ $data->hazard == 'Machine Guarding' ? 'selected' : '' }}
                                                        value="Machine Guarding">
                                                        Machine Guarding</option>
                                                    <option {{ $data->hazard == 'Material Storage' ? 'selected' : '' }}
                                                        value="Material Storage">
                                                        Material Storage</option>
                                                    <option {{ $data->hazard == 'Material use' ? 'selected' : '' }}
                                                        value="Material use">
                                                        Material use</option>
                                                    <option {{ $data->hazard == 'Pressure' ? 'selected' : '' }}
                                                        value="Pressure">
                                                        Pressure</option>
                                                    <option {{ $data->hazard == 'Thermal' ? 'selected' : '' }}
                                                        value="Thermal">
                                                        Thermal</option>
                                                    <option {{ $data->hazard == 'Water use' ? 'selected' : '' }}
                                                        value="Water use">
                                                        Water use</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Room">Room</label>
                                                <select name="room2" id="room2"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->room2 == 'Biochemistry' ? 'selected' : '' }}
                                                        value="Biochemistry">
                                                        Biochemistry
                                                    </option>
                                                    <option {{ $data->room2 == 'Automation' ? 'selected' : '' }}
                                                        value="Automation">
                                                        Automation
                                                    </option>
                                                    <option {{ $data->room2 == 'Blood Collection' ? 'selected' : '' }}
                                                        value="Blood Collection">
                                                        Blood Collection
                                                    </option>
                                                    <option {{ $data->room2 == 'Buffer Preparation' ? 'selected' : '' }}
                                                        value="Buffer Preparation">
                                                        Buffer Preparation
                                                    </option>
                                                    <option {{ $data->room2 == 'Bulk Fill' ? 'selected' : '' }}
                                                        value="Bulk Fill">
                                                        Bulk Fill
                                                    </option>
                                                    <option {{ $data->room2 == 'Calibration' ? 'selected' : '' }}
                                                        value="Calibration">
                                                        Calibration
                                                    </option>
                                                    <option
                                                        {{ $data->room2 == 'Component Manufacturing' ? 'selected' : '' }}
                                                        value="Component Manufacturing">
                                                        Component Manufacturing
                                                    </option>
                                                    <option {{ $data->room2 == 'Computer' ? 'selected' : '' }}
                                                        value="Computer">
                                                        Computer
                                                    </option>
                                                    <option
                                                        {{ $data->room2 == 'Computer / Automated Systems' ? 'selected' : '' }}
                                                        value="Computer / Automated Systems">
                                                        Computer / Automated Systems
                                                    </option>
                                                    <option
                                                        {{ $data->room2 == 'Despensing Donor Suitability' ? 'selected' : '' }}
                                                        value="Despensing Donor Suitability">
                                                        Despensing Donor Suitability
                                                    </option>
                                                    <option {{ $data->room2 == 'Filling' ? 'selected' : '' }}
                                                        value="Filling">
                                                        Filling
                                                    </option>
                                                    <option {{ $data->room2 == 'Filtration' ? 'selected' : '' }}
                                                        value="Filtration">
                                                        Filtration
                                                    </option>
                                                    <option {{ $data->room2 == 'Formulation' ? 'selected' : '' }}
                                                        value="Formulation">
                                                        Formulation
                                                    </option>
                                                    <option {{ $data->room2 == 'Incoming QA' ? 'selected' : '' }}
                                                        value="Incoming QA">
                                                        Incoming QA
                                                    </option>
                                                    <option {{ $data->room2 == 'Hazard' ? 'selected' : '' }}
                                                        value="Hazard">
                                                        Hazard
                                                    </option>
                                                    <option {{ $data->room2 == 'Laboratory' ? 'selected' : '' }}
                                                        value="Laboratory">
                                                        Laboratory
                                                    </option>
                                                    <option
                                                        {{ $data->room2 == 'Laboratory Support Facility' ? 'selected' : '' }}
                                                        value="Laboratory Support Facility">
                                                        Laboratory Support Facility
                                                    </option>
                                                    <option {{ $data->room2 == 'Enter Your' ? 'selected' : '' }}
                                                        value="Enter Your">
                                                        Enter Your
                                                    </option>
                                                    <option {{ $data->room2 == 'Lot Release' ? 'selected' : '' }}
                                                        value="Lot Release">
                                                        Lot Release
                                                    </option>
                                                    <option {{ $data->room2 == 'Manufacturing' ? 'selected' : '' }}
                                                        value="Manufacturing">
                                                        Manufacturing
                                                    </option>
                                                    <option {{ $data->room2 == 'Materials Management' ? 'selected' : '' }}
                                                        value="Materials Management">
                                                        Materials Management
                                                    </option>
                                                    <option {{ $data->room2 == 'Room' ? 'selected' : '' }}
                                                        value="Room">
                                                        Room
                                                    </option>
                                                    <option {{ $data->room2 == 'Operations' ? 'selected' : '' }}
                                                        value="Operations">
                                                        Operations
                                                    </option>
                                                    <option {{ $data->room2 == 'Packaging' ? 'selected' : '' }}
                                                        value="Packaging">
                                                        Packaging
                                                    </option>
                                                    <option {{ $data->room2 == 'Plant Engineering' ? 'selected' : '' }}
                                                        value="Plant Engineering">
                                                        Plant Engineering
                                                    </option>
                                                    <option {{ $data->room2 == 'Njown' ? 'selected' : '' }}
                                                        value="Njown">
                                                        Njown
                                                    </option>
                                                    <option {{ $data->room2 == 'Powder Filling' ? 'selected' : '' }}
                                                        value="Powder Filling">
                                                        Powder Filling
                                                    </option>
                                                    <option {{ $data->room2 == 'Process Development' ? 'selected' : '' }}
                                                        value="Process Development">
                                                        Process Development
                                                    </option>
                                                    <option {{ $data->room2 == 'Product Distribution' ? 'selected' : '' }}
                                                        value="Product Distribution">
                                                        Product Distribution
                                                    </option>
                                                    <option {{ $data->room2 == 'Product Testing' ? 'selected' : '' }}
                                                        value="Product Testing">
                                                        Product Testing
                                                    </option>
                                                    <option
                                                        {{ $data->room2 == 'Production Purification' ? 'selected' : '' }}
                                                        value="Production Purification">
                                                        Production Purification
                                                    </option>
                                                    <option {{ $data->room2 == 'QA' ? 'selected' : '' }} value="QA">
                                                        QA
                                                    </option>
                                                    <option
                                                        {{ $data->room2 == 'QA Laboratory Quality Control' ? 'selected' : '' }}
                                                        value="QA Laboratory Quality Control">
                                                        QA Laboratory Quality Control
                                                    </option>
                                                    <option
                                                        {{ $data->room2 == 'Quality Control / Assurance' ? 'selected' : '' }}
                                                        value="Quality Control / Assurance">
                                                        Quality Control / Assurance
                                                    </option>
                                                    <option {{ $data->room2 == 'Sanitization' ? 'selected' : '' }}
                                                        value="Sanitization">
                                                        Sanitization
                                                    </option>
                                                    <option
                                                        {{ $data->room2 == 'Shipping/Distribution Storage/Distribution' ? 'selected' : '' }}
                                                        value="Shipping/Distribution Storage/Distribution">
                                                        Shipping/Distribution Storage/Distribution
                                                    </option>
                                                    <option
                                                        {{ $data->room2 == 'Storage and Distribution' ? 'selected' : '' }}
                                                        value="Storage and Distribution">
                                                        Storage and Distribution
                                                    </option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Regulatory Climate">Regulatory Climate</label>
                                                <select name="regulatory_climate" id="regulatory_climate"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option
                                                        {{ $data->regulatory_climate == 'No significant regulatory issues affecting operation' ? 'selected' : '' }}
                                                        value="No significant regulatory issues affecting operation">
                                                        0. No significant regulatory issues affecting operation
                                                    </option>
                                                    <option
                                                        {{ $data->regulatory_climate == 'Some regulatory or enforcement changes potentially affecting operation are anticipated' ? 'selected' : '' }}
                                                        value="Some regulatory or enforcement changes potentially affecting operation are anticipated">
                                                        1. Some regulatory or enforcement changes potentially affecting
                                                        operation are anticipated
                                                    </option>
                                                    <option
                                                        {{ $data->regulatory_climate == 'A few regulatory or enforcement changes affect operations' ? 'selected' : '' }}
                                                        value="A few regulatory or enforcement changes affect operations">
                                                        2. A few regulatory or enforcement changes affect operations
                                                    </option>
                                                    <option
                                                        {{ $data->regulatory_climate == 'Regulatory and enforcement changes affect operation' ? 'selected' : '' }}
                                                        value="Regulatory and enforcement changes affect operation">
                                                        3. Regulatory and enforcement changes affect operation
                                                    </option>
                                                    <option
                                                        {{ $data->regulatory_climate == 'Significant programmatic regulatory and enforcement changes affect operation' ? 'selected' : '' }}
                                                        value="Significant programmatic regulatory and enforcement changes affect operation">
                                                        4. Significant programmatic regulatory and enforcement changes
                                                        affect operation
                                                    </option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Number of Employees">Number of Employees</label>
                                                <select name="Number_of_employees" id="Number_of_employees"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->Number_of_employees == '0-50' ? 'selected' : '' }}
                                                        value="0-50">0-50</option>
                                                    <option {{ $data->Number_of_employees == '50-100' ? 'selected' : '' }}
                                                        value="50-100">50-100</option>
                                                    <option
                                                        {{ $data->Number_of_employees == '100-200' ? 'selected' : '' }}
                                                        value="100-200">100-200</option>
                                                    <option
                                                        {{ $data->Number_of_employees == '200-300' ? 'selected' : '' }}
                                                        value="200-300">200-300</option>
                                                    <option
                                                        {{ $data->Number_of_employees == '300-500' ? 'selected' : '' }}
                                                        value="300-500">300-500</option>
                                                    <option
                                                        {{ $data->Number_of_employees == '500-1000' ? 'selected' : '' }}
                                                        value="500-1000">500-1000</option>
                                                    <option {{ $data->Number_of_employees == '1000+' ? 'selected' : '' }}
                                                        value="1000+">1000+</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Risk Management Strategy">Risk Management Strategy</label>
                                                <select name="risk_management_strategy" id="risk_management_strategy"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option
                                                        {{ $data->risk_management_strategy == 'Accept' ? 'selected' : '' }}
                                                        value="Accept">Accept</option>
                                                    <option
                                                        {{ $data->risk_management_strategy == 'Avoid the Risk' ? 'selected' : '' }}
                                                        value="Avoid the Risk">Avoid the Risk</option>
                                                    <option
                                                        {{ $data->risk_management_strategy == 'Mitigate' ? 'selected' : '' }}
                                                        value="Mitigate">Mitigate</option>
                                                    <option
                                                        {{ $data->risk_management_strategy == 'Transfer' ? 'selected' : '' }}
                                                        value="Transfer">Transfer</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-block">
                                        <button type="submit" class="saveButton"
                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>Save</button>
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                        <button type="button"> <a class="text-white"
                                                href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Work Group Assignment content -->
                            <div id="CCForm3" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="sub-head">
                                        Assignment Details
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 new-date-data-field">
                                            <div class="group-input input-date">
                                                <label for="Scheduled Start Date">Scheduled Start Date</label>
                                                <div class="calenderauditee">
                                                    <input type="text" id="schedule_start_date" readonly
                                                        value="{{ Helpers::getdateFormat($data->schedule_start_date1) }}"
                                                        placeholder="DD-MM-YYYY" />
                                                    <input type="date" id="schedule_start_date_checkdate"
                                                        name="schedule_start_date1"
                                                        value="{{ $data->schedule_start_date1 }}"
                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        class="hide-input"
                                                        oninput="handleDateInput(this, 'schedule_start_date');checkDate('schedule_start_date_checkdate','schedule_end_date_checkdate')" />
                                                </div>
                                                {{-- <input type="date" name="schedule_start_date1" value="{{$data->schedule_start_date1}}"> --}}
                                            </div>
                                        </div>
                                        <div class="col-lg-6 new-date-data-field">
                                            <div class="group-input input-date">
                                                <label for="Scheduled End Date">Scheduled End Date</label>
                                                <div class="calenderauditee">
                                                    <input type="text" id="schedule_end_date" readonly
                                                        value="{{ Helpers::getdateFormat($data->schedule_end_date1) }}"
                                                        placeholder="DD-MM-YYYY" />
                                                    <input type="date" id="schedule_end_date_checkdate"
                                                        name="schedule_end_date1"
                                                        value="{{ $data->schedule_end_date1 }}"
                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        class="hide-input"
                                                        oninput="handleDateInput(this, 'schedule_end_date');checkDate('schedule_start_date_checkdate','schedule_end_date_checkdate')" />
                                                </div>
                                                {{-- <input type="date" name="schedule_end_date1" value="{{$data->schedule_end_date}}"> --}}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Estimated Man-Hours">Estimated Man-Hours</label>
                                                <div class="relative-container">
                                                    <input type="text" class="mic-input" name="estimated_man_hours"
                                                        id="estimated_man_hours"
                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        value="{{ $data->estimated_man_hours }}">
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Estimated Cost">Estimated Cost</label>
                                                <div class="relative-container">
                                                    <input type="text" class="mic-input" name="estimated_cost"
                                                        id="estimated_cost"
                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        value="{{ $data->estimated_cost }}">
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Supervisor">Supervisor</label>
                                                <div class="static">shaleen</div>
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Currency">Currency</label>
                                                <select name="currency" id="currency"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option
                                                        {{ $data->currency == 'ARS-Argentine Peso' ? 'selected' : '' }}
                                                        value="ARS-Argentine Peso">
                                                        ARS-Argentine Peso
                                                    </option>
                                                    <option
                                                        {{ $data->currency == 'AUD-Australian Dollar' ? 'selected' : '' }}
                                                        value="AUD-Australian Dollar">
                                                        AUD-Australian Dollar
                                                    </option>
                                                    <option
                                                        {{ $data->currency == 'BRL-Brazilian Real' ? 'selected' : '' }}
                                                        value="BRL-Brazilian Real">
                                                        BRL-Brazilian Real
                                                    </option>
                                                    <option
                                                        {{ $data->currency == 'CAD-Canadian Dollar' ? 'selected' : '' }}
                                                        value="CAD-Canadian Dollar">
                                                        CAD-Canadian Dollar
                                                    </option>
                                                    <option {{ $data->currency == 'CHF-Swiss Franc' ? 'selected' : '' }}
                                                        value="CHF-Swiss Franc">
                                                        CHF-Swiss Franc
                                                    </option>
                                                    <option {{ $data->currency == 'CNY-Chinese Yuan' ? 'selected' : '' }}
                                                        value="CNY-Chinese Yuan">
                                                        CNY-Chinese Yuan
                                                    </option>
                                                    <option {{ $data->currency == 'EUR-Euro' ? 'selected' : '' }}
                                                        value="EUR-Euro">
                                                        EUR-Euro
                                                    </option>
                                                    <option
                                                        {{ $data->currency == 'HKD-Hong Kong Dollar' ? 'selected' : '' }}
                                                        value="HKD-Hong Kong Dollar">
                                                        HKD-Hong Kong Dollar
                                                    </option>
                                                    <option
                                                        {{ $data->currency == 'ILS-Israeli New Sheqel' ? 'selected' : '' }}
                                                        value="ILS-Israeli New Sheqel">
                                                        ILS-Israeli New Sheqel
                                                    </option>
                                                    <option {{ $data->currency == 'INR-Indian Rupee' ? 'selected' : '' }}
                                                        value="INR-Indian Rupee">
                                                        INR-Indian Rupee
                                                    </option>
                                                    <option {{ $data->currency == 'JPY-Japanese Yen' ? 'selected' : '' }}
                                                        value="JPY-Japanese Yen">
                                                        JPY-Japanese Yen
                                                    </option>
                                                    <option
                                                        {{ $data->currency == 'KRW-South Korean Won' ? 'selected' : '' }}
                                                        value="KRW-South Korean Won">
                                                        KRW-South Korean Won
                                                    </option>
                                                    <option {{ $data->currency == 'MXN-Mexican Peso' ? 'selected' : '' }}
                                                        value="MXN-Mexican Peso">
                                                        MXN-Mexican Peso
                                                    </option>
                                                    <option
                                                        {{ $data->currency == 'RUB-Russian Rouble' ? 'selected' : '' }}
                                                        value="RUB-Russian Rouble">
                                                        RUB-Russian Rouble
                                                    </option>
                                                    <option {{ $data->currency == 'SAR-Saudi Riyal' ? 'selected' : '' }}
                                                        value="SAR-Saudi Riyal">
                                                        SAR-Saudi Riyal
                                                    </option>
                                                    <option {{ $data->currency == 'TRY-Turkish Lira' ? 'selected' : '' }}
                                                        value="TRY-Turkish Lira">
                                                        TRY-Turkish Lira
                                                    </option>
                                                    <option {{ $data->currency == 'USD-US Dollar' ? 'selected' : '' }}
                                                        value="USD-US Dollar">
                                                        USD-US Dollar
                                                    </option>
                                                    <option {{ $data->currency == 'XAG-Silver' ? 'selected' : '' }}
                                                        value="XAG-Silver">
                                                        XAG-Silver
                                                    </option>
                                                    <option {{ $data->currency == 'XAU-Gold' ? 'selected' : '' }}
                                                        value="XAU-Gold">
                                                        XAU-Gold
                                                    </option>
                                                    <option {{ $data->currency == 'XPD-Palladium' ? 'selected' : '' }}
                                                        value="XPD-Palladium">
                                                        XPD-Palladium
                                                    </option>
                                                    <option {{ $data->currency == 'XPT-Platinum' ? 'selected' : '' }}
                                                        value="XPT-Platinum">
                                                        XPT-Platinum
                                                    </option>
                                                </select>

                                            </div>
                                        </div>
                                        {{-- <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Team Members">Team Members</label>
                                                <select multiple name="team_members2[]" placeholder="Select Team Members"
                                                    data-search="false" data-silent-initial-value-set="true"
                                                    id="team_members"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="1"
                                                        {{ in_array('1', explode(',', $data->team_members2)) ? 'selected' : '' }}>
                                                        Amit Guru</option>
                                                    <option value="2"
                                                        {{ in_array('2', explode(',', $data->team_members2)) ? 'selected' : '' }}>
                                                        Anshul Patel</option>
                                                    <option value="3"
                                                        {{ in_array('3', explode(',', $data->team_members2)) ? 'selected' : '' }}>
                                                        Vikash Prajapati</option>
                                                    <option value="4"
                                                        {{ in_array('4', explode(',', $data->team_members2)) ? 'selected' : '' }}>
                                                        Amit Patel</option>
                                                    <option value="5"
                                                        {{ in_array('5', explode(',', $data->team_members2)) ? 'selected' : '' }}>
                                                        Shaleen Mishra</option>
                                                    <option value="6"
                                                        {{ in_array('6', explode(',', $data->team_members2)) ? 'selected' : '' }}>
                                                        Madhulika Mishra</option>
                                                </select>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Training Requirement">Training Requirement</label>
                                                <select multiple name="training_require"
                                                    placeholder="Select Training Requirement" data-search="false"
                                                    data-silent-initial-value-set="true" id="team_members"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="1">ABC</option>
                                                    <option value="1">ABC</option>
                                                    <option value="1">ABC</option>
                                                    <option value="1">ABC</option>

                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="col-6">
                                            <div class="group-input">
                                                <label for="Justification / Rationale">Justification / Rationale</label>
                                                <div class="relative-container">
                                                    <textarea name="justification" class='mic-input' {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        id="justification">{{ $data->justification }} </textarea>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sub-head">
                                        Action Plan
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Product/Material">
                                                    Action Plan<button type="button" name="ann" id="action_plan"
                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>+</button>
                                                </label>
                                                <table class="table table-bordered" id="action_plan_details">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 4%">Row#</th>
                                                            <th>Remarks</th>
                                                            <th>Responsible</th>
                                                            <th>Deadline</th>
                                                            <th>Item static</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach (unserialize($action_plan->action) as $key => $temps)
                                                            <tr id="target">
                                                                <td><input disabled type="text" name="serial_number[]"
                                                                        value="{{ $key + 1 }}"></td>
                                                                <td><input type="text" name="action[]"
                                                                        value="{{ $temps ? $temps : ' ' }}"></td>
                                                                {{-- <td><input type="text" name="responsible[]"
                                                                        value="{{ unserialize($action_plan->responsible)[$key] ? unserialize($action_plan->responsible)[$key] : '' }}">
                                                                </td> --}}
                                                                <td> <select id="select-state" placeholder="Select..."
                                                                        name="responsible[]">
                                                                        <option value="">Select a value</option>
                                                                        @foreach ($users as $value)
                                                                            <option
                                                                                {{ unserialize($action_plan->responsible)[$key] ? (unserialize($action_plan->responsible)[$key] == $value->id ? 'selected' : ' ') : ' ' }}
                                                                                value="{{ $value->id }}">
                                                                                {{ $value->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select></td>
                                                                {{-- <td><input type="text" name="deadline[]"
                                                                        value="{{ unserialize($action_plan->deadline)[$key] ? unserialize($action_plan->deadline)[$key] : '' }}">
                                                                </td> --}}
                                                                <td>
                                                                    <div class="group-input new-date-data-field mb-0">
                                                                        <div class="input-date ">
                                                                            <div class="calenderauditee">
                                                                                <input type="text"
                                                                                    placeholder="DD-MM-YYYY"
                                                                                    id="deadline{{ $key }}' + serialNumber +'"
                                                                                    readonly
                                                                                    value="{{ Helpers::getdateFormat(unserialize($action_plan->deadline)[$key]) }}" />
                                                                                <input type="date" name="deadline[]"
                                                                                    class="hide-input"
                                                                                    placeholder="DD-MM-YYYY"
                                                                                    value="{{ unserialize($action_plan->deadline)[$key] ? unserialize($action_plan->deadline)[$key] : ' ' }}"
                                                                                    oninput="handleDateInput(this, `deadline{{ $key }}' + serialNumber +'`)" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td><input type="text" name="item_static[]"
                                                                        value="{{ unserialize($action_plan->item_static)[$key] ? unserialize($action_plan->item_static)[$key] : ' ' }}">
                                                                </td>
                                                                <td><button
                                                                        type="text"class="removeBtnMI">Remove</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        {{-- <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="Report Attachments">Work Group Attachments</label>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="reference"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="reference[]"
                                                            oninput="addMultipleFiles(this, 'reference')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="File Attachments">Work Group Attachments</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting
                                                        documents</small></div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="reference">
                                                        @if ($data->reference)
                                                            @foreach (json_decode($data->reference) as $file)
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
                                                        <input
                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                            type="file" id="myfile" name="reference[]"
                                                            oninput="addMultipleFiles(this, 'reference')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-block">
                                        <button type="submit" class="saveButton"
                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>Save</button>
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                        <button type="button"> <a class="text-white"
                                                href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                            <!-- General information content -->
                            <div id="CCForm4" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="sub-head">
                                        RCA Results
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="root-cause-methodology">Root Cause Methodology</label>
                                                <select name="root_cause_methodology[]" multiple
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                    placeholder="-- Select --" data-search="false"
                                                    data-silent-initial-value-set="true" id="root-cause-methodology">

                                                    <option value="Why-Why Chart"
                                                        {{ in_array('Why-Why Chart', explode(',', $data->root_cause_methodology)) ? 'selected' : '' }}>
                                                        Why-Why Chart</option>
                                                    <option value="Failure Mode and Efect Analysis"
                                                        {{ in_array('Failure Mode and Efect Analysis', explode(',', $data->root_cause_methodology)) ? 'selected' : '' }}>
                                                        Failure Mode and Efect Analysis</option>
                                                    <option value="Fishbone or Ishikawa Diagram"
                                                        {{ in_array('Fishbone or Ishikawa Diagram', explode(',', $data->root_cause_methodology)) ? 'selected' : '' }}>
                                                        Fishbone or Ishikawa Diagram</option>
                                                    <option value="Is/Is Not Analysis"
                                                        {{ in_array('Is/Is Not Analysis', explode(',', $data->root_cause_methodology)) ? 'selected' : '' }}>
                                                        Is/Is Not Analysis</option>


                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 sub-head"></div>
                                        <div class="group-input">
                                            <label for="agenda">
                                                Failure Mode and Effect Analysis
                                                <button type="button" name="agenda"
                                                    onclick="addRiskAssessment('risk-assessment-risk-management')"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>+</button>
                                            </label>
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="width: 200%"
                                                    id="risk-assessment-risk-management">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 4%">Row#</th>
                                                            <th>Risk Factor</th>
                                                            <th>Risk element</th>
                                                            <th>Probable cause of risk element</th>
                                                            <th>Existing Risk Controls</th>
                                                            <th>Initial Severity- H(3)/M(2)/L(1)</th>
                                                            <th>Initial Probability- H(3)/M(2)/L(1)</th>
                                                            <th>Initial Detectability- H(1)/M(2)/L(3)</th>
                                                            <th>Initial RPN</th>
                                                            <th>Risk Acceptance (Y/N)</th>
                                                            <th>Proposed Additional Risk control measure (Mandatory for Risk
                                                                elements having RPN>4)</th>
                                                            <th>Residual Severity- H(3)/M(2)/L(1)</th>
                                                            <th>Residual Probability- H(3)/M(2)/L(1)</th>
                                                            <th>Residual Detectability- H(1)/M(2)/L(3)</th>
                                                            <th>Residual RPN</th>
                                                            <th>Risk Acceptance (Y/N)</th>
                                                            <th>Mitigation proposal (Mention either CAPA reference number,
                                                                IQ, OQ, or PQ)</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($riskEffectAnalysis->risk_factor))
                                                            @foreach (unserialize($riskEffectAnalysis->risk_factor) as $key => $riskFactor)
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td><input name="risk_factor[]" type="text"
                                                                            value="{{ $riskFactor }}"></td>
                                                                    <td><input name="risk_element[]" type="text"
                                                                            value="{{ unserialize($riskEffectAnalysis->risk_element)[$key] ?? '' }}">
                                                                    </td>
                                                                    <td><input name="problem_cause[]" type="text"
                                                                            value="{{ unserialize($riskEffectAnalysis->problem_cause)[$key] ?? '' }}">
                                                                    </td>
                                                                    <td><input name="existing_risk_control[]"
                                                                            type="text"
                                                                            value="{{ unserialize($riskEffectAnalysis->existing_risk_control)[$key] ?? '' }}">
                                                                    </td>
                                                                    <td>
                                                                        <select onchange="calculateInitialResult(this)"
                                                                            class="fieldR" name="initial_severity[]">
                                                                            <option value="">-- Select --</option>
                                                                            <option value="1"
                                                                                {{ (unserialize($riskEffectAnalysis->initial_severity)[$key] ?? null) == 1 ? 'selected' : '' }}>
                                                                                1</option>
                                                                            <option value="2"
                                                                                {{ (unserialize($riskEffectAnalysis->initial_severity)[$key] ?? null) == 2 ? 'selected' : '' }}>
                                                                                2</option>
                                                                            <option value="3"
                                                                                {{ (unserialize($riskEffectAnalysis->initial_severity)[$key] ?? null) == 3 ? 'selected' : '' }}>
                                                                                3</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select onchange="calculateInitialResult(this)"
                                                                            class="fieldP" name="initial_probability[]">
                                                                            <option value="">-- Select --</option>
                                                                            <option value="1"
                                                                                {{ (unserialize($riskEffectAnalysis->initial_probability)[$key] ?? null) == 1 ? 'selected' : '' }}>
                                                                                1</option>
                                                                            <option value="2"
                                                                                {{ (unserialize($riskEffectAnalysis->initial_probability)[$key] ?? null) == 2 ? 'selected' : '' }}>
                                                                                2</option>
                                                                            <option value="3"
                                                                                {{ (unserialize($riskEffectAnalysis->initial_probability)[$key] ?? null) == 3 ? 'selected' : '' }}>
                                                                                3</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select onchange="calculateInitialResult(this)"
                                                                            class="fieldN" name="initial_detectability[]">
                                                                            <option value="">-- Select --</option>
                                                                            <option value="1"
                                                                                {{ (unserialize($riskEffectAnalysis->initial_detectability)[$key] ?? null) == 1 ? 'selected' : '' }}>
                                                                                1</option>
                                                                            <option value="2"
                                                                                {{ (unserialize($riskEffectAnalysis->initial_detectability)[$key] ?? null) == 2 ? 'selected' : '' }}>
                                                                                2</option>
                                                                            <option value="3"
                                                                                {{ (unserialize($riskEffectAnalysis->initial_detectability)[$key] ?? null) == 3 ? 'selected' : '' }}>
                                                                                3</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input name="initial_rpn[]" type="text"
                                                                            class="initial-rpn"
                                                                            value="{{ unserialize($riskEffectAnalysis->initial_rpn)[$key] ?? '' }}"
                                                                            readonly></td>
                                                                    <td>
                                                                        <select name="risk_acceptance[]">
                                                                            <option value="">-- Select --</option>
                                                                            <option value="Y"
                                                                                {{ (unserialize($riskEffectAnalysis->risk_acceptance)[$key] ?? null) == 'Y' ? 'selected' : '' }}>
                                                                                Y</option>
                                                                            <option value="N"
                                                                                {{ (unserialize($riskEffectAnalysis->risk_acceptance)[$key] ?? null) == 'N' ? 'selected' : '' }}>
                                                                                N</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input name="risk_control_measure[]"
                                                                            type="text"
                                                                            value="{{ unserialize($riskEffectAnalysis->risk_control_measure)[$key] ?? '' }}">
                                                                    </td>
                                                                    <td>
                                                                        <select onchange="calculateResidualResult(this)"
                                                                            class="residual-fieldR"
                                                                            name="residual_severity[]">
                                                                            <option value="">-- Select --</option>
                                                                            <option value="1"
                                                                                {{ (unserialize($riskEffectAnalysis->residual_severity)[$key] ?? null) == 1 ? 'selected' : '' }}>
                                                                                1</option>
                                                                            <option value="2"
                                                                                {{ (unserialize($riskEffectAnalysis->residual_severity)[$key] ?? null) == 2 ? 'selected' : '' }}>
                                                                                2</option>
                                                                            <option value="3"
                                                                                {{ (unserialize($riskEffectAnalysis->residual_severity)[$key] ?? null) == 3 ? 'selected' : '' }}>
                                                                                3</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select onchange="calculateResidualResult(this)"
                                                                            class="residual-fieldP"
                                                                            name="residual_probability[]">
                                                                            <option value="">-- Select --</option>
                                                                            <option value="1"
                                                                                {{ (unserialize($riskEffectAnalysis->residual_probability)[$key] ?? null) == 1 ? 'selected' : '' }}>
                                                                                1</option>
                                                                            <option value="2"
                                                                                {{ (unserialize($riskEffectAnalysis->residual_probability)[$key] ?? null) == 2 ? 'selected' : '' }}>
                                                                                2</option>
                                                                            <option value="3"
                                                                                {{ (unserialize($riskEffectAnalysis->residual_probability)[$key] ?? null) == 3 ? 'selected' : '' }}>
                                                                                3</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select onchange="calculateResidualResult(this)"
                                                                            class="residual-fieldN"
                                                                            name="residual_detectability[]">
                                                                            <option value="">-- Select --</option>
                                                                            <option value="1"
                                                                                {{ (unserialize($riskEffectAnalysis->residual_detectability)[$key] ?? null) == 1 ? 'selected' : '' }}>
                                                                                1</option>
                                                                            <option value="2"
                                                                                {{ (unserialize($riskEffectAnalysis->residual_detectability)[$key] ?? null) == 2 ? 'selected' : '' }}>
                                                                                2</option>
                                                                            <option value="3"
                                                                                {{ (unserialize($riskEffectAnalysis->residual_detectability)[$key] ?? null) == 3 ? 'selected' : '' }}>
                                                                                3</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input name="residual_rpn[]" type="text"
                                                                            class="residual-rpn"
                                                                            value="{{ unserialize($riskEffectAnalysis->residual_rpn)[$key] ?? '' }}"
                                                                            readonly></td>
                                                                    <td>
                                                                        <select name="risk_acceptance2[]">
                                                                            <option value="">-- Select --</option>
                                                                            <option value="Y"
                                                                                {{ (unserialize($riskEffectAnalysis->risk_acceptance2)[$key] ?? null) == 'Y' ? 'selected' : '' }}>
                                                                                Y</option>
                                                                            <option value="N"
                                                                                {{ (unserialize($riskEffectAnalysis->risk_acceptance2)[$key] ?? null) == 'N' ? 'selected' : '' }}>
                                                                                N</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input name="mitigation_proposal[]" type="text"
                                                                            value="{{ unserialize($riskEffectAnalysis->mitigation_proposal)[$key] ?? '' }}">
                                                                    </td>
                                                                    <td><button type="button"
                                                                            onclick="removeRow(this)">Remove</button></td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-12 sub-head"></div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="fishbone">
                                                    Fishbone or Ishikawa Diagram
                                                    <button type="button" name="agenda"
                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        onclick="addFishBone('.top-field-group', '.bottom-field-group')">+</button>
                                                    <button type="button" name="agenda" class="fishbone-del-btn"
                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        onclick="deleteFishBone('.top-field-group', '.bottom-field-group')">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                    <span class="text-primary" data-bs-toggle="modal"
                                                        data-bs-target="#fishbone-instruction-modal"
                                                        style="font-size: 0.8rem; font-weight: 400;">
                                                        (Launch Instruction)
                                                    </span>
                                                </label>
                                                <div class="fishbone-ishikawa-diagram">
                                                    <div class="left-group">
                                                        <div class="grid-field field-name">
                                                            <div>Measurement</div>
                                                            <div>Materials</div>
                                                            <div>Methods</div>
                                                        </div>
                                                        <div class="top-field-group">
                                                            <div class="grid-field fields top-field">
                                                                @if (!empty($fishbone->measurement))
                                                                    @foreach (unserialize($fishbone->measurement) as $key => $measure)
                                                                        <div><input type="text"
                                                                                value="{{ $measure }}"
                                                                                name="measurement[]" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}></div>
                                                                        <div><input type="text"
                                                                                value="{{ unserialize($fishbone->materials)[$key] ? unserialize($fishbone->materials)[$key] : '' }}"
                                                                                name="materials[]" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}></div>
                                                                        <div><input type="text"
                                                                                value="{{ unserialize($fishbone->methods)[$key] ? unserialize($fishbone->methods)[$key] : '' }}"
                                                                                name="methods[]" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}></div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="mid"></div>
                                                        <div class="bottom-field-group">
                                                            <div class="grid-field fields bottom-field">
                                                                @if (!empty($fishbone->environment))
                                                                    @foreach (unserialize($fishbone->environment) as $key => $measure)
                                                                        <div><input type="text"
                                                                                value="{{ $measure }}"
                                                                                name="environment[]" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}></div>
                                                                        <div><input type="text"
                                                                                value="{{ unserialize($fishbone->manpower)[$key] ? unserialize($fishbone->manpower)[$key] : '' }}"
                                                                                name="manpower[]" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}></div>
                                                                        <div><input type="text"
                                                                                value="{{ unserialize($fishbone->machine)[$key] ? unserialize($fishbone->machine)[$key] : '' }}"
                                                                                name="machine[]" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}></div>
                                                                    @endforeach
                                                                @endif

                                                            </div>
                                                        </div>
                                                        <div class="grid-field field-name">
                                                            <div>Environment</div>
                                                            <div>Manpower</div>
                                                            <div>Machine</div>
                                                        </div>
                                                    </div>
                                                    <div class="right-group">
                                                        <div class="field-name">
                                                            Problem Statement
                                                        </div>
                                                        <div class="field">

                                                            <textarea name="problem_statement" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $fishbone->problem_statement }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 sub-head"></div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="why-why-chart">
                                                    Why-Why Chart
                                                    <span class="text-primary" data-bs-toggle="modal"
                                                        data-bs-target="#why_chart-instruction-modal"
                                                        style="font-size: 0.8rem; font-weight: 400;">
                                                        (Launch Instruction)
                                                    </span>
                                                </label>
                                                <div class="why-why-chart">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr style="background: #f4bb22">
                                                                <th style="width:150px;">Problem Statement :</th>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="why_problem_statement"
                                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $whyChart->why_problem_statement }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="why-row">
                                                                <th style="width:150px; color: #393cd4;">
                                                                    Why 1 <span id="addWhyButton1" class="addWhyButton"
                                                                        onclick="addWhyField('why_1_block', 'why_1[]')"
                                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>+</span>
                                                                </th>
                                                                <td>
                                                                    <div class="why_1_block">
                                                                        @if (!empty($whyChart->why_1))
                                                                            @foreach (unserialize($whyChart->why_1) as $key => $measure)
                                                                                <textarea name="why_1[]" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $measure }}</textarea>
                                                                            @endforeach
                                                                        @endif

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="why-row">
                                                                <th style="width:150px; color: #393cd4;">
                                                                    Why 2 <span id="addWhyButton2" class="addWhyButton"
                                                                        onclick="addWhyField('why_2_block', 'why_2[]')"
                                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>+</span>
                                                                </th>
                                                                <td>
                                                                    <div class="why_2_block">
                                                                        @if (!empty($whyChart->why_2))
                                                                            @foreach (unserialize($whyChart->why_2) as $key => $measure)
                                                                                <textarea name="why_2[]" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $measure }}</textarea>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="why-row">
                                                                <th style="width:150px; color: #393cd4;">
                                                                    Why 3 <span id="addWhyButton3" class="addWhyButton"
                                                                        onclick="addWhyField('why_3_block', 'why_3[]')"
                                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>+</span>
                                                                </th>
                                                                <td>
                                                                    <div class="why_3_block">
                                                                        @if (!empty($whyChart->why_3))
                                                                            @foreach (unserialize($whyChart->why_3) as $key => $measure)
                                                                                <textarea name="why_3[]" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $measure }}</textarea>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="why-row">
                                                                <th style="width:150px; color: #393cd4;">
                                                                    Why 4 <span id="addWhyButton4" class="addWhyButton"
                                                                        onclick="addWhyField('why_4_block', 'why_4[]')"
                                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>+</span>
                                                                </th>
                                                                <td>
                                                                    <div class="why_4_block">
                                                                        @if (!empty($whyChart->why_4))
                                                                            @foreach (unserialize($whyChart->why_4) as $key => $measure)
                                                                                <textarea name="why_4[]" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $measure }}</textarea>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="why-row">
                                                                <th style="width:150px; color: #393cd4;">
                                                                    Why 5 <span id="addWhyButton5" class="addWhyButton"
                                                                        onclick="addWhyField('why_5_block', 'why_5[]')"
                                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>+</span>
                                                                </th>
                                                                <td>
                                                                    <div class="why_5_block">
                                                                        @if (!empty($whyChart->why_5))
                                                                            @foreach (unserialize($whyChart->why_5) as $key => $measure)
                                                                                <textarea name="why_5[]" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $measure }}</textarea>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr style="background: #0080006b;">
                                                                <th style="width:150px;">Root Cause :</th>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="why_root_cause" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $whyChart->why_root_cause }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 sub-head"></div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="why-why-chart">
                                                    Is/Is Not Analysis
                                                    <span class="text-primary" data-bs-toggle="modal"
                                                        data-bs-target="#is_is_not-instruction-modal"
                                                        style="font-size: 0.8rem; font-weight: 400;">
                                                        (Launch Instruction)
                                                    </span>
                                                </label>
                                                <div class="why-why-chart">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>&nbsp;</th>
                                                                <th>Will Be</th>
                                                                <th>Will Not Be</th>
                                                                <th>Rationale</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th style="background: #0039bd85">What</th>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="what_will_be" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->what_will_be }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="what_will_not_be" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->what_will_not_be }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="what_rationable" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->what_rationable }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="background: #0039bd85">Where</th>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="where_will_be" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->where_will_be }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="where_will_not_be" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->where_will_not_be }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="where_rationable" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->where_rationable }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="background: #0039bd85">When</th>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="when_will_be" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->when_will_be }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="when_will_not_be" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->when_will_not_be }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="when_rationable" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->when_rationable }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="background: #0039bd85">Coverage</th>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="coverage_will_be" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->coverage_will_be }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="coverage_will_not_be"
                                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->coverage_will_not_be }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="coverage_rationable"
                                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->coverage_rationable }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="background: #0039bd85">Who</th>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="who_will_be" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->who_will_be }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="who_will_not_be" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->who_will_not_be }}</textarea>
                                                                        @component('frontend.forms.language-model')
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="relative-container">
                                                                        <textarea class="mic-input" name="who_rationable" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $what_who_where->who_rationable }}</textarea>
                                                                        @component('frontend.forms.language-model', ['disabled' => $data->stage == 0 || $data->stage == 7])
                                                                        @endcomponent
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 sub-head"></div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="root_cause_description">Root Cause Description</label>
                                                <div class="relative-container">
                                                    <textarea class="mic-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        name="root_cause_description">{{ $data->root_cause_description }}</textarea>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="investigation_summary">Investigation Summary</label>
                                                <div class="relative-container">
                                                    <textarea class="mic-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        name="investigation_summary">{{ $data->investigation_summary }}</textarea>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sub-head">
                                        Risk Analysis
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Severity Rate">Severity Rate</label>
                                                <select name="severity_rate" id="analysisR"
                                                    onchange='calculateRiskAnalysis(this)'
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->severity_rate == '1' ? 'selected' : '' }}
                                                        value="1">Negligible</option>
                                                    <option {{ $data->severity_rate == '2' ? 'selected' : '' }}
                                                        value="2">Moderate</option>
                                                    <option {{ $data->severity_rate == '3' ? 'selected' : '' }}
                                                        value="3">Major</option>
                                                    <option {{ $data->severity_rate == '4' ? 'selected' : '' }}
                                                        value="4">Fatal</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Occurrence">Occurrence</label>
                                                <select name="occurrence" id="analysisP"
                                                    onchange='calculateRiskAnalysis(this)'
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->occurrence == '5' ? 'selected' : '' }}
                                                        value="5">Extremely Unlikely</option>
                                                    <option {{ $data->occurrence == '4' ? 'selected' : '' }}
                                                        value="4">Rare</option>
                                                    <option {{ $data->occurrence == '3' ? 'selected' : '' }}
                                                        value="3">Unlikely</option>
                                                    <option {{ $data->occurrence == '2' ? 'selected' : '' }}
                                                        value="2">Likely</option>
                                                    <option {{ $data->occurrence == '1' ? 'selected' : '' }}
                                                        value="1">Very Likely</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Detection">Detection</label>
                                                <select name="detection" id="analysisN"
                                                    onchange='calculateRiskAnalysis(this)'
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->detection == '5' ? 'selected' : '' }}
                                                        value="5">Impossible</option>
                                                    <option {{ $data->detection == '4' ? 'selected' : '' }}
                                                        value="4">Rare</option>
                                                    <option {{ $data->detection == '3' ? 'selected' : '' }}
                                                        value="3">Unlikely</option>
                                                    <option {{ $data->detection == '2' ? 'selected' : '' }}
                                                        value="2">Likely</option>
                                                    <option {{ $data->detection == '1' ? 'selected' : '' }}
                                                        value="1">Very Likely</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="RPN">RPN</label>
                                                <div><small class="text-primary">Auto - Calculated</small></div>

                                                <input readonly type="text" name="rpn" id="analysisRPN"
                                                    value="{{ $data->rpn }}">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-block">
                                        <button type="submit" class="saveButton"
                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>Save</button>
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                        <button type="button"> <a class="text-white"
                                                href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Residual Risk content -->
                            <div id="CCForm5" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Residual Risk">Residual Risk</label>
                                                <div class="relative-container">
                                                    <input type="text" class="mic-input" name="residual_risk"
                                                        id="residual_risk"
                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        value="{{ $data->residual_risk }}">
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Residual Risk Impact">Residual Risk Impact</label>
                                                <select name="residual_risk_impact" id="analysisR2"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                    onchange='calculateRiskAnalysis2(this)'>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option value="1"
                                                        {{ $data->residual_risk_impact == '1' ? 'selected' : '' }}>High
                                                    </option>
                                                    <option value="2"
                                                        {{ $data->residual_risk_impact == '2' ? 'selected' : '' }}>Low
                                                    </option>
                                                    <option value="3"
                                                        {{ $data->residual_risk_impact == '3' ? 'selected' : '' }}>Medium
                                                    </option>
                                                    <option value="4"
                                                        {{ $data->residual_risk_impact == '4' ? 'selected' : '' }}>None
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Residual Risk Probability">Residual Risk Probability</label>
                                                <select name="residual_risk_probability" id="analysisP2"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                    onchange='calculateRiskAnalysis2(this)'>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option value="1"
                                                        {{ $data->residual_risk_probability == '1' ? 'selected' : '' }}>
                                                        High</option>
                                                    <option value="2"
                                                        {{ $data->residual_risk_probability == '2' ? 'selected' : '' }}>
                                                        Medium</option>
                                                    <option value="3"
                                                        {{ $data->residual_risk_probability == '3' ? 'selected' : '' }}>
                                                        Low</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Detection">Residual Detection</label>
                                                <select name="detection2" id="analysisN2"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                    onchange='calculateRiskAnalysis2(this)'>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option value="5"
                                                        {{ $data->detection2 == '5' ? 'selected' : '' }}>Impossible
                                                    </option>
                                                    <option value="4"
                                                        {{ $data->detection2 == '4' ? 'selected' : '' }}>Rare</option>
                                                    <option value="3"
                                                        {{ $data->detection2 == '3' ? 'selected' : '' }}>Unlikely</option>
                                                    <option value="2"
                                                        {{ $data->detection2 == '2' ? 'selected' : '' }}>Likely</option>
                                                    <option value="1"
                                                        {{ $data->detection2 == '1' ? 'selected' : '' }}>Very Likely
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="RPN">Residual RPN</label>
                                                <div><small class="text-primary">Auto - Calculated</small></div>
                                                <input readonly type="text" name="rpn2" id="analysisRPN2"
                                                    value="{{ $data->rpn2 }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Comments">Comments</label>
                                                <div class="relative-container">
                                                    <textarea class="mic-input" name="comments2" id="comments2"
                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>{{ $data->comments2 }}</textarea>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- ----------------------------------------------- --}}
                                    <div class="button-block">
                                        <button type="submit" class="saveButton"
                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>Save</button>
                                        <button type="button" class="backButton"
                                            onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                        <button type="button"> <a class="text-white"
                                                href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                            <div id="CCForm6" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="group-input">
                                                {{-- <label for="mitigation_plan_details">
                                                    Mitigation Plan Details<button type="button" name="ann"  {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        onclick="add5Input('mitigation_plan_details')">+</button>
                                                </label> --}}
                                                <label for="mitigation_plan_details">
                                                    Mitigation Plan Details<button type="button" name="ann"
                                                        id="action_plan2">+</button>
                                                </label>
                                                <table class="table table-bordered" id="action_plan_details2">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 4%">Row#</th>
                                                            <th>Mitigation Steps</th>
                                                            <th>
                                                                Deadline
                                                                {{-- Input Type Date --}}
                                                            </th>
                                                            <th>
                                                                Responsible Person
                                                                {{-- Person type Data Field --}}
                                                            </th>
                                                            <th>Status</th>
                                                            <th>Remarks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach (unserialize($mitigation_plan_details->mitigation_steps) as $key => $temps)
                                                            <tr>
                                                                <td><input disabled type="text"
                                                                        name="serial_number[]"
                                                                        value="{{ $key + 1 }}"></td>
                                                                <td><input type="text" name="mitigation_steps[]"
                                                                        value="{{ $temps ? $temps : ' ' }}"></td>
                                                                {{-- <td><input type="date" name="deadline2[]"
                                                                    value="{{ unserialize($mitigation_plan_details->deadline2)[$key] ? unserialize($mitigation_plan_details->deadline2)[$key] : '' }}">
                                                            </td> --}}
                                                                <td>
                                                                    <div class="group-input new-date-data-field mb-0">
                                                                        <div class="input-date ">
                                                                            <div class="calenderauditee">
                                                                                <input type="text"
                                                                                    id="deadline2{{ $key }}' + serialNumber +'"
                                                                                    readonly placeholder="DD-MM-YYYY"
                                                                                    value="{{ Helpers::getdateFormat(unserialize($mitigation_plan_details->deadline2)[$key]) }}" />
                                                                                <input type="date" name="deadline2[]"
                                                                                    class="hide-input"
                                                                                    value="{{ unserialize($mitigation_plan_details->deadline2)[$key] }}"
                                                                                    oninput="handleDateInput(this, `deadline2{{ $key }}' + serialNumber +'`)" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                {{-- <td><input type="text" name="responsible_person[]"
                                                                    value="{{ unserialize($mitigation_plan_details->responsible_person)[$key] ? unserialize($mitigation_plan_details->responsible_person)[$key] : '' }}">
                                                            </td> --}}
                                                                <td> <select id="select-state" placeholder="Select..."
                                                                        name="responsible_person[]">
                                                                        <option value="">Select a value</option>
                                                                        @foreach ($users as $value)
                                                                            <option
                                                                                {{ unserialize($mitigation_plan_details->responsible_person)[$key] ? (unserialize($mitigation_plan_details->responsible_person)[$key] == $value->id ? 'selected' : ' ') : '' }}
                                                                                value="{{ $value->id }}">
                                                                                {{ $value->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select></td>
                                                                <td><input type="text" name="status[]"
                                                                        value="{{ unserialize($mitigation_plan_details->status)[$key] ? unserialize($mitigation_plan_details->status)[$key] : ' ' }}">
                                                                </td>
                                                                <td><input type="text" name="remark[]"
                                                                        value="{{ unserialize($mitigation_plan_details->remark)[$key] ? unserialize($mitigation_plan_details->remark)[$key] : ' ' }}">
                                                                </td>
                                                                <td><button type="text"
                                                                        class="removeBtnMI1">Remove</button></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="sub-head">Risk Mitigation</div>
                                        </div>
                                        {{-- <div class="col-12">
                                            <div class="group-input">
                                                <label for="mitigation-required">Mitigation Required</label>
                                                <div class="check-input">
                                                    <label for="yes">
                                                        <input selected type="radio" name="mitigation_required" id="yes">
                                                        Yes
                                                    </label>
                                                    <label for="no">
                                                        <input selected type="radio" name="mitigation_required" id="no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Department(s)">Mitigation Required</label>
                                                <select name="mitigation_required" placeholder="Select Departments"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                    data-search="false" data-silent-initial-value-set="true"
                                                    id="">
                                                    <option value="">Select Mitigation </option>
                                                    <option value="Yes"
                                                        {{ $data->mitigation_required == 'Yes' ? 'selected' : '' }}>Yes
                                                    </option>
                                                    <option value="No"
                                                        {{ $data->mitigation_required == 'No' ? 'selected' : '' }}>No
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="mitigation-plan">Mitigation Plan</label>
                                                <div class="relative-container">
                                                    <textarea class="mic-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }} name="mitigation_plan">{{ $data->mitigation_plan }}</textarea>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 new-date-data-field">
                                            <div class="group-input input-date">
                                                <label for="mitigation-due-date">Scheduled End Date</label>
                                                <div class="calenderauditee">
                                                    <input type="text" id="mitigation_due_date" readonly
                                                        value="{{ Helpers::getdateFormat($data->mitigation_due_date) }}"
                                                        name="mitigation_due_date" placeholder="DD-MM-YYYY" />
                                                    <input type="date" name="mitigation_due_date"
                                                        {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        value="{{ $data->mitigation_due_date }}" class="hide-input"
                                                        oninput="handleDateInput(this, 'mitigation_due_date')" />
                                                </div>
                                                {{-- <input type="date" name="mitigation_due_date"
                                                    value="{{ $data->mitigation_due_date }}"> --}}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="mitigation-status">Status of Mitigation</label>
                                                <select name="mitigation_status"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="0">-- Select --</option>
                                                    <option value="Green Status"
                                                        {{ $data->mitigation_status == 'Green Status' ? 'selected' : '' }}>
                                                        Green
                                                        Status</option>
                                                    <option value="Amber Status"
                                                        {{ $data->mitigation_status == 'Amber Status' ? 'selected' : '' }}>
                                                        Amber
                                                        Status</option>
                                                    <option value="Red Status"
                                                        {{ $data->mitigation_status == 'Red Status' ? 'selected' : '' }}>
                                                        Red
                                                        Status</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="mitigation-status-comments">Mitigation Status Comments</label>
                                                <div class="relative-container">
                                                    <textarea class="mic-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        name="mitigation_status_comments">{{ $data->mitigation_status_comments }}</textarea>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="sub-head">Overall Assessment</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="impact">Impact</label>
                                                <select name="impact"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">-- Select --</option>
                                                    <option value="high"
                                                        {{ $data->impact == 'high' ? 'selected' : '' }}>High</option>
                                                    <option value="medium"
                                                        {{ $data->impact == 'medium' ? 'selected' : '' }}>Medium</option>
                                                    <option value="low"
                                                        {{ $data->impact == 'low' ? 'selected' : '' }}>Low</option>
                                                    <option value="none"
                                                        {{ $data->impact == 'none' ? 'selected' : '' }}>None</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="criticality">Criticality</label>
                                                <select name="criticality"
                                                    {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>
                                                    <option value="">-- Select --</option>
                                                    <option value="high"
                                                        {{ $data->criticality == 'high' ? 'selected' : '' }}>High</option>
                                                    <option value="medium"
                                                        {{ $data->criticality == 'medium' ? 'selected' : '' }}>Medium
                                                    </option>
                                                    <option value="low"
                                                        {{ $data->criticality == 'low' ? 'selected' : '' }}>Low</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="impact-analysis">Impact Analysis</label>
                                                <div class="relative-container">
                                                    <textarea class="mic-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }} name="impact_analysis">{{ $data->impact_analysis }}</textarea>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="risk-analysis">Risk Analysis</label>
                                                <div class="relative-container">
                                                    <textarea class="mic-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }} name="risk_analysis">{{ $data->risk_analysis }}</textarea>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="severity">Severity</label>
                                                <select name="severity">
                                                    <option value="0">-- Select --</option>
                                                    <option value="Negligible"
                                                        {{ $data->severity == 'Negligible' ? 'selected' : '' }}>Negligible
                                                    </option>
                                                    <option value="Minor"
                                                        {{ $data->severity == 'Minor' ? 'selected' : '' }}>Minor</option>
                                                    <option value="Moderate"
                                                        {{ $data->severity == 'Moderate' ? 'selected' : '' }}>Moderate
                                                    </option>
                                                    <option value="Major"
                                                        {{ $data->severity == 'Major' ? 'selected' : '' }}>Major</option>
                                                    <option value="Fatal"
                                                        {{ $data->severity == 'Fatal' ? 'selected' : '' }}>Fatal</option>
                                                </select>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="occurance">Occurance</label>
                                                <select name="occurance">
                                                    <option value="0">-- Select --</option>
                                                    <option value="Extremely_Unlikely">Extremely Unlikely</option>
                                                    <option value="Rare"
                                                        {{ $data->occurance == 'Rare' ? 'selected' : '' }}>Rare</option>
                                                    <option value="Unlikely"
                                                        {{ $data->occurance == 'Unlikely' ? 'selected' : '' }}>Unlikely
                                                    </option>
                                                    <option value="Likely"
                                                        {{ $data->occurance == 'Likely' ? 'selected' : '' }}>Likely
                                                    </option>
                                                    <option value="Very_Likely"
                                                        {{ $data->occurance == 'Very_Likely' ? 'selected' : '' }}>Very
                                                        Likely</option>
                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="Reference Records">Reference Record</label>
                                                <select {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                    multiple id="reference_record" name="refrence_record[]">

                                                    @foreach ($old_record as $new)
                                                        @php
                                                            $recordValue =
                                                                Helpers::getDivisionName($new->division_id) .
                                                                '/RA/' .
                                                                date('Y') .
                                                                '/' .
                                                                Helpers::recordFormat($new->record);
                                                            $selected = in_array(
                                                                $recordValue,
                                                                explode(',', $data->refrence_record),
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

                                        <div class="col-12 sub-head">
                                            Extension Justification
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="due_date_extension">Due Date Extension Justification</label>
                                                <div><small class="text-primary">Please Mention justification if due date
                                                        is crossed</small></div>
                                                <div class="relative-container">
                                                    <textarea class="mic-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                        name="due_date_extension">{{ $data->due_date_extension }}</textarea>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-block">
                                        <button type="submit" class="saveButton">Save</button>
                                        <button type="button" class="backButton"
                                            onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                        <button type="button"> <a class="text-white"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Signatures content -->
                            <div id="CCForm7" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Submitted By..">Submitted By</label>
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
                                                <label for="More Information Required By">More Information Required
                                                    By</label>
                                                <div class="static">{{ $data->analysis_more_info_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="More Information Required On">More Information Required
                                                    On</label>
                                                <div class="static">{{ $data->analysis_more_info_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="More Information Required Comment">More Information Required
                                                    Comment</label>
                                                <div class="static">{{ $data->analysis_more_info_comment }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Evaluation Complete By">Evaluation Complete By</label>
                                                <div class="static">{{ $data->evaluated_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Evaluation Complete On">Evaluation Complete On</label>
                                                <div class="static">{{ $data->evaluated_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Evaluation Complete Comment">Evaluation Complete
                                                    Comment</label>
                                                <div class="static">{{ $data->evaluated_comment }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Request More Info By">Request More Info By</label>
                                                <div class="static">{{ $data->request_more_info_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Request More Info On">Request More Info On</label>
                                                <div class="static">{{ $data->request_more_info_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Request More Info Comment">Request More Info
                                                    Comment</label>
                                                <div class="static">{{ $data->request_more_info_comment }}</div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Action Plan Completed By">Action Plan Completed By</label>
                                                <div class="static">{{ $data->plan_approved_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Action Plan Completed On">Action Plan Completed On</label>
                                                <div class="static">{{ $data->plan_approved_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Action Plan Completed Comment">Action Plan Completed
                                                    Comment</label>
                                                <div class="static">{{ $data->plan_approved_comment }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Reject Action Plan By">Reject Action Plan By</label>
                                                <div class="static">{{ $data->reject_action_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Reject Action Plan On">Reject Action Plan On</label>
                                                <div class="static">{{ $data->reject_action_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reject Action Plan Comment">Reject Action Plan
                                                    Comment</label>
                                                <div class="static">{{ $data->reject_action_comment }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Action Plan Approved By">Action Plan Approved By</label>
                                                <div class="static">{{ $data->action_plan_approved_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Action Plan Approved On">Action Plan Approved On</label>
                                                <div class="static">{{ $data->action_plan_approved_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Action Plan Approved Comment">Action Plan Approved
                                                    Comment</label>
                                                <div class="static">{{ $data->action_plan_approved_comment }}</div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Reject Action Plan By">Reject Action Plan By</label>
                                                <div class="static">{{ $data->reject_action_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Reject Action Plan On">Reject Action Plan On</label>
                                                <div class="static">{{ $data->reject_action_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reject Action Plan Comment">Reject Action Plan
                                                    Comment</label>
                                                <div class="static">{{ $data->reject_action_comment }}</div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="All Actions Completed By">All Actions Completed By</label>
                                                <div class="static">{{ $data->all_action_completed_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="All Actions Completed On">All Actions Completed On</label>
                                                <div class="static">{{ $data->all_action_completed_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="All Actions Completed Comment">All Actions Completed
                                                    Comment</label>
                                                <div class="static">{{ $data->all_action_completed_comment }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Request More Info By">Request More Info By</label>
                                                <div class="static">{{ $data->action_request_action_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Request More Info On">Request More Info On</label>
                                                <div class="static">{{ $data->action_request_action_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Request More Info Comment">Request More Info
                                                    Comment</label>
                                                <div class="static">{{ $data->action_request_action_comment }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="More Actions Needed By">More Actions Needed By</label>
                                                <div class="static">{{ $data->more_action_needed_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="More Actions Needed On">More Actions Needed On</label>
                                                <div class="static">{{ $data->more_action_needed_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="More Actions Needed Comment">More Actions Needed
                                                    Comment</label>
                                                <div class="static">{{ $data->more_action_needed_comment }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Residual Risk Evaluation Completed By">Residual Risk
                                                    Evaluation Completed By</label>
                                                <div class="static">{{ $data->residual_risk_completed_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Residual Risk Evaluation Completed On">Residual Risk
                                                    Evaluation Completed On</label>
                                                <div class="static">{{ $data->residual_risk_completed_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Residual Risk Evaluation Completed Comment">Residual Risk
                                                    Evaluation Completed Comment</label>
                                                <div class="static">{{ $data->residual_risk_completed_comment }}</div>
                                            </div>
                                        </div>

                                        {{-- <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Risk Analysis Completed By">Risk Analysis Completed By</label>
                                                <div class="static">{{ $data->risk_analysis_completed_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Risk Analysis Completed On">Risk Analysis Completed On</label>
                                                <div class="static">{{ $data->risk_analysis_completed_on }}</div>
                                            </div>
                                        </div> --}}



                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Cancelled By">Cancelled By</label>
                                                <div class="static">{{ $data->cancelled_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="group-input">
                                                <label for="Cancelled On">Cancelled On</label>
                                                <div class="static">{{ $data->cancelled_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Cancelled Comment">Cancelled Comment</label>
                                                <div class="static">{{ $data->cancelled_comment }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-block">
                                        {{-- <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>Save</button> --}}
                                        <button type="button" class="backButton"
                                            onclick="previousStep()">Back</button>
                                        {{-- <button type="submit"
                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}>Submit</button> --}}
                                        <button type="button"> <a class="text-white"
                                                href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>

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
                        <form action="{{ route('riskAssesmentStateChangeshow', $data->id) }}" method="POST">
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
                                    <input type="comment" name="comments">
                                </div>
                            </div>

                            <!-- Modal footer -->
                            <!-- <div class="modal-footer">
                                                                                                                                                                                                                                                                                                                                                                                                                                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                <button type="button" data-bs-dismiss="modal">Close</button>
                                                                                                                                                                                                                                                                                                                                                                                                                                            </div> -->
                            <div class="modal-footer">
                                <button type="submit">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
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

                        <form action="{{ url('reject_Risk', $data->id) }}" method="POST">
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
                            <!-- <div class="modal-footer">
                                                                                                                                                                                                                                                                                                                                                                                                                                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                <button>Close</button>
                                                                                                                                                                                                                                                                                                                                                                                                                                            </div> -->
                            <div class="modal-footer">
                                <button type="submit">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="more_info-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="{{ url('more_info_model', $data->id) }}" method="POST">
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
                                    <input class="input-new" type="text" name="username" required>
                                </div>
                                <div class="group-input">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input class="input-new" type="password" name="password" required>
                                </div>
                                <div class="group-input">
                                    <label for="comment">Comment <span class="text-danger">*</span></label>
                                    <input class="input-new" type="comment" name="comment" required>
                                </div>
                            </div>

                            <!-- Modal footer -->
                            <!-- <div class="modal-footer">
                                                                                                                                                                                                                                                                                                                                                                                                                                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                <button>Close</button>
                                                                                                                                                                                                                                                                                                                                                                                                                                            </div> -->
                            <div class="modal-footer">
                                <button type="submit">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
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
                        <form action="{{ route('riskAssesmentChild', $data->id) }}" method="POST">
                            @csrf
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="group-input">
                                    <label for="major">
                                        <input type="radio" name="revision" id="major" value="Action-Item">
                                        Create Action Item
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


            <style>
                #step-form>div {
                    display: none
                }

                #step-form>div:nth-child(1) {
                    display: block;
                }

                .input-new {
                    width: 100%;
                    margin-bottom: 10px;
                    border-radius: 5px;
                }
            </style>
            <script>
                $(document).ready(function() {
                    $('#action_plan').click(function(e) {
                        function generateTableRow(serialNumber) {
                            var users = @json($users);
                            console.log(users);
                            var html =
                                '<tr>' +
                                '<td><input disabled type="text" name="serial_number[]" value="' + serialNumber +
                                '"></td>' +
                                '<td><input type="text" name="action[]"></td>' +
                                '<td><select name="responsible[]">' +
                                '<option value="">Select a value</option>';

                            for (var i = 0; i < users.length; i++) {
                                html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                            }

                            html += '</select></td>' +
                                // '<td><input type="date" name="deadline[]"></td>' +
                                '<td><div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"><input type="text" id="deadline' +
                                serialNumber +
                                '" readonly placeholder="DD-MM-YYYY" /><input type="date" name="deadline[]" class="hide-input" oninput="handleDateInput(this, `deadline' +
                    serialNumber + '`)" /></div></div></div></td>' +
                                '<td><input type="text" name="item_static[]"></td>' +
                                '<td><button type="text" class="removeBtnMI">Remove</button></td>' +
                                '</tr>';



                            return html;
                        }

                        var tableBody = $('#action_plan_details tbody');
                        var rowCount = tableBody.children('tr').length;
                        var newRow = generateTableRow(rowCount + 1);
                        tableBody.append(newRow);
                    });
                    $('#action_plan2').click(function(e) {
                        function generateTableRow(serialNumber) {
                            var users = @json($users);
                            console.log(users);
                            var html =
                                '<tr>' +
                                '<td><input disabled type="text" name="serial_number[]" value="' + serialNumber +
                                '"></td>' +
                                '<td><input type="text" name="mitigation_steps[]"></td>' +
                                // '<td><input type="date" name="deadline2[]"></td>' +
                                '<td><div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"><input type="text" id="deadline2' +
                                serialNumber +
                                '" readonly placeholder="DD-MM-YYYY" /><input type="date" name="deadline2[]" class="hide-input" oninput="handleDateInput(this, `deadline2' +
                    serialNumber + '`)" /></div></div></div></td>' +
                                '<td><select name="responsible_person[]">' +
                                '<option value="">Select a value</option>';

                            for (var i = 0; i < users.length; i++) {
                                html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                            }

                            html += '</select></td>' +
                                '<td><input type="text" name="status[]"></td>' +
                                '<td><input type="text" name="remark[]"></td>' +
                                '<td><button type="text" class="removeBtnMI1">Remove</button></td>' +
                                '</tr>';

                            return html;
                        }

                        var tableBody = $('#action_plan_details2 tbody');
                        var rowCount = tableBody.children('tr').length;
                        var newRow = generateTableRow(rowCount + 1);
                        tableBody.append(newRow);
                    });
                });
                $(document).on('click', '.removeBtnMI', function() {
                    $(this).closest('tr').remove();
                })
                $(document).on('click', '.removeBtnMI1', function() {
                    $(this).closest('tr').remove();
                })
            </script>

            <script>
                VirtualSelect.init({
                    ele: '#Facility, #Group, #Audit, #Auditee, #root-cause-methodology, #reference_record, #related_record'
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
                document.getElementById('initiator_group').addEventListener('change', function() {
                    var selectedValue = this.value;
                    document.getElementById('initiator_group_code').value = selectedValue;
                });
            </script>

            <script>
                VirtualSelect.init({
                    ele: '#departments, #team_members, #training-require, #impacted_objects'
                });
            </script>
            <script>
                $(document).ready(function() {
                    var loc = new locationInfo();
                    var countryDropdown = $("#country");
                    var desiredValue = '{{ $data->country }}';
                    setTimeout(function() {
                        countryDropdown.find('option[value="{{ $data->country }}"]').prop('selected', true);
                        var countryId = jQuery("option:selected", this).attr('countryid');
                        if (countryId != '') {
                            loc.getStates(countryId);
                        } else {
                            jQuery(".states option:gt(0)").remove();
                        }
                        var stateDropdown = $("#state");
                        stateDropdown.find('option[value="{{ $data->state }}"]').prop('selected', true);
                        var stateId = jQuery("option:selected", this).attr('stateid');
                        if (stateId != '') {
                            loc.getCities(stateId);
                        } else {
                            jQuery(".cities option:gt(0)").remove();
                        }
                        var cityDropdown = $("#city");
                        cityDropdown.find('option[value="{{ $data->city }}"]').prop('selected', true);
                    }, 1000);
                });

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
                    $('#rchars').text(textlen);
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
            {{-- <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Assuming $data->stage is available as a global variable or you can pass it from server-side code
                    const stage = {{ $data->stage }};

                    // Get all elements with the class 'mic-input'
                    const micInputs = document.querySelectorAll('.mic-input');

                    // Check the condition and set the disabled attribute accordingly
                    if (stage === 0 || stage === 7) {
                        micInputs.forEach(input => {
                            input.disabled = true;
                        });
                    }
                });
            </script> --}}
            <script>
                function disableButtonsIfNeeded(stage) {
                    if (stage == 0 || stage == 7) {
                        document.querySelectorAll('.addWhyButton').forEach(button => {
                            button.style.pointerEvents = 'none'; // Disable click
                            button.style.opacity = '0.5'; // Optional: make it look disabled
                            button.title = 'Button is disabled'; // Optional: show a tooltip
                        });
                    }
                }

                // Assuming $data->stage is passed into the script
                // Replace the following line with the actual way of retrieving $data->stage value in your environment
                const stage =
                    {{ $data->stage }}; // You may need to adjust this based on how you're passing the data to the script

                // Call the function to disable buttons if needed
                disableButtonsIfNeeded(stage);
            </script>

        @endsection
