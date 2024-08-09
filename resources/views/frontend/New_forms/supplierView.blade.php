@extends('frontend.layout.main')
@section('container')
@php
 
$users = DB::table('users')
    ->select('id', 'name')
    ->get();

@endphp
    <style>
        textarea.note-codable {
            display: none !important;
        }
        .hide-input {
            display: none;
        }

        header {
            display: none;
        }
        .remove-file  {
            color: white;
            cursor: pointer;
            margin-left: 10px;
        }

        .remove-file :hover {
            color: white;
        }
    </style>
      <style>
          .mini-modal {
            display: none;
            position: absolute;
            z-index: 1;
            padding: 10px;
            background-color: #fefefe;
            border: 1px solid #888;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 200px; /* Adjust width as needed */
        }
        .mini-modal-content {
            background-color: #fefefe;
            padding: 10px;
            border-radius: 4px;
        }
        .mini-modal-content h2 {
            font-size: 16px;
            margin-top: 0;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
        button {
    border: 0;
    background: white;
    color: #060606;
    /* border: 2px solid black; */
    transition: all 0.3s linear;
}

button:hover {
    color: #0c0c0c;
}
    </style>
    <script>
        $(document).ready(function () {
    let multipleCancelButton = new Choices("#choices-multiple-remove-button", {
        removeItemButton: true,
    });
});

function addMultipleFiles(input, block_id) {
    let block = document.getElementById(block_id);
    block.innerHTML = "";
    let files = input.files;
    for (let i = 0; i < files.length; i++) {
        let div = document.createElement('div');
        div.innerHTML += files[i].name;
        let viewLink = document.createElement("a");
        viewLink.href = URL.createObjectURL(files[i]);
        viewLink.textContent = "View";
        div.appendChild(viewLink);
        block.appendChild(div);
    }
}
    </script>
  <script>
    $(document).ready(function() {
        $('#ObservationAdd').click(function(e) {
            function generateTableRow(serialNumber) {
                var users = @json($users);

                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="observation_id[]"></td>' +
                    '<td><input type="text" name="observation_description[]"></td>' +
                    '<td><input type="text" name="area[]"></td>' +
                    '<td><input type="text" name="auditee_response[]"></td>' +
                    '<td><button type="button" class="removeRowBtn" style="background-color: black;color: white;">Remove</button></td>' +
                    '</tr>';

                return html;
            }

            var tableBody = $('#onservation-field-table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });

    $(document).on('click', '.removeRowBtn', function() {
        $(this).closest('tr').remove();
    });
</script>
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

<script>
    function addAuditAgenda(tableId) {
        var users = @json($users);
        var table = document.getElementById(tableId);
        var currentRowCount = table.rows.length;
        var newRow = table.insertRow(currentRowCount);
        newRow.setAttribute("id", "row" + currentRowCount);
        var cell1 = newRow.insertCell(0);
        cell1.innerHTML = currentRowCount;

        var cell2 = newRow.insertCell(1);
        cell2.innerHTML = "<input type='text' name='audit[]'>";

        var cell3 = newRow.insertCell(2);
        cell3.innerHTML = '<td><div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"> <input type="text" id="scheduled_start_date' + currentRowCount +'" readonly placeholder="DD-MM-YYYY" /><input type="date" name="scheduled_start_date[]" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="scheduled_start_date' + currentRowCount +'_checkdate"  class="hide-input" oninput="handleDateInput(this, `scheduled_start_date' + currentRowCount +'`);checkDate(`scheduled_start_date' + currentRowCount +'_checkdate`,`scheduled_end_date' + currentRowCount +'_checkdate`)" /></div></div></div></td>';

        var cell4 = newRow.insertCell(3);
        cell4.innerHTML = "<input type='time' name='scheduled_start_time[]' >";

        var cell5 = newRow.insertCell(4);
        cell5.innerHTML = '<td><div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"> <input type="text" id="scheduled_end_date' + currentRowCount +'" readonly placeholder="DD-MM-YYYY" /><input type="date" name="scheduled_end_date[]" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="scheduled_end_date'+ currentRowCount +'_checkdate" class="hide-input" oninput="handleDateInput(this, `scheduled_end_date' + currentRowCount +'`);checkDate(`scheduled_start_date' + currentRowCount +'_checkdate`,`scheduled_end_date' + currentRowCount +'_checkdate`)" /></div></div></div></td>';

        var cell6 = newRow.insertCell(5);
        cell6.innerHTML = "<input type='time' name='scheduled_end_time[]' >";

        var cell7 = newRow.insertCell(6);
        var userHtml = '<select name="auditor[]"><option value="">-Select-</option>';
                for (var i = 0; i < users.length; i++) {
                    userHtml += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                }
                userHtml +='</select>';
        
                cell7.innerHTML = userHtml;

        var cell8 = newRow.insertCell(7);
        
        var userHtml = '<select name="auditee[]"><option value="">-Select-</option>';
            for (var i = 0; i < users.length; i++) {
                userHtml += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
            }
            userHtml +='</select>';
    
            cell8.innerHTML = userHtml;

        var cell9 = newRow.insertCell(8);
        cell9.innerHTML = "<input type='text'name='remark[]'>";
        var cell10 = newRow.insertCell(9);
        cell10.innerHTML = '<button type="button" class="removeRowBtn" style="background-color: black;color: white;" onclick="removeRow(this)">Remove</button>';

        // Update row numbering
        for (var i = 1; i < currentRowCount; i++) {
            var row = table.rows[i];
            row.cells[0].innerHTML = i;
        }
    }

    function removeRow(button) {
        var row = button.closest('tr');
        row.parentNode.removeChild(row);
        
        // Update row numbering
        var table = document.getElementById('audit-agenda-grid');
        for (var i = 1; i < table.rows.length; i++) {
            var row = table.rows[i];
            row.cells[0].innerHTML = i;
        }
    }
</script>
<script>
    $(document).on('click', '.removeRowBtn', function() {
        $(this).closest('tr').remove();
    })
</script>

    <div class="form-field-head">

        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName($data->division_id) }} / Supplier Audit
        </div>
    </div>

    {{-- ---------------------- --}}
    <div id="change-control-view">
        <div class="container-fluid">

            <div class="inner-block state-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="main-head">Record Workflow  </div>

                    <div class="d-flex" style="gap:20px;">
                      
                    <?php
                        $userRoles = DB::table('user_roles')->where(['user_id' => Auth::user()->id, 'q_m_s_divisions_id' => $data->division_id])->get();
                        $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
                        // dd($userRoles);
                    ?>
                        <!-- {{-- <button class="button_theme1" onclick="window.print();return false;"
                            class="new-doc-btn">Print</button> --}} -->
                        <button class="button_theme1"> <a class="text-white"
                                href="{{ route('ShowexternalAuditTrials', $data->id) }}"> Audit Trail </a> </button>

                        @if ($data->stage == 1 && (in_array(13, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Schedule Audit
                            </button>
                            <!-- {{-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal1">
                                Child
                            </button> --}} -->
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancel
                            </button>
                        @elseif($data->stage == 2 && (in_array(12, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Complete Audit Preparation
                            </button>
                           
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Reject
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancel
                            </button>
                        @elseif($data->stage == 3 && (in_array(12, $userRoleIds) || in_array(18, $userRoleIds)))
                            </button> <button class="button_theme1" data-bs-toggle="modal"
                                data-bs-target="#rejection-modal">
                                Reject
                            </button>
                            <!-- {{-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal1">
                                Child
                            </button> --}} -->
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Issue Report</button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancel
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button>
                        @elseif($data->stage == 4 && (in_array(11, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                CAPA Plan Proposed
                            </button>

                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                No CAPAs Required
                            </button>
                           
                        @elseif($data->stage == 5 && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds) || in_array(11, $userRoleIds) ))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                All CAPA Closed
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
                        <div class="progress-bars">
                            @if ($data->stage >= 1)
                                <div class="active">Opened</div>
                            @else
                                <div class="">Opened</div>
                            @endif

                            @if ($data->stage >= 2)
                                <div class="active">Audit Preparation </div>
                            @else
                                <div class="">Audit Preparation</div>
                            @endif

                            @if ($data->stage >= 3)
                                <div class="active">Pending Audit</div>
                            @else
                                <div class="">Pending Audit</div>
                            @endif

                            @if ($data->stage >= 4)
                                <div class="active">Pending Response</div>
                            @else
                                <div class="">Pending Response</div>
                            @endif
                            @if ($data->stage >= 5)
                                <div class="active">CAPA Execution in Progress</div>
                            @else
                                <div class="">CAPA Execution in Progress</div>
                            @endif
                            @if ($data->stage >= 6)
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
            {{-- ------------------------------- --}}

            {{-- ======================================
                    DATA FIELDS
    ======================================= --}}

            @php
                $users = DB::table('users')->get();
            @endphp

            <div id="change-control-fields">
                <div class="container-fluid">

                    <!-- Tab links -->
                    <div class="cctab">
                        <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
                        <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Audit Planning</button>
                        <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Audit Preparation</button>
                        <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Audit Execution</button>
                        <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Audit Response & Closure</button>
                        <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Activity Log</button>
                    </div>

                    <form action="{{ route('updateSupplierAudit', $data->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="step-form">

                            <!-- General information content -->
                            <div id="CCForm1" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="RLS Record Number"><b>Record Number</b></label>
                                                <input type="hidden" name="record_number">
                                                {{-- <div class="static">QMS-EMEA/IA/{{ Helpers::year($data->created_at) }}/{{ $data->record }}</div> --}}
                                                <input disabled type="text"
                                                    value="{{ Helpers::getDivisionName($data->division_id) }}/SA/{{ date('Y') }}/{{ $data->record }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Division Code"><b>Site/Location Code</b></label>
                                                <input disabled type="text" name="division_code"
                                                    value="{{ Helpers::getDivisionName($data->division_id) }}">
                                                {{-- <div class="static">{{ Helpers::getDivisionName(session()->get('division')) }}</div> --}}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Initiator"><b>Initiator</b></label>
                                                <input type="hidden" name="initiator_id">
                                                {{-- <div class="static">{{ $data->initiator_name }} </div> --}}
                                                <input disabled type="text" value="{{ $data->initiator_name }} ">
                                            </div>
                                        </div>
                                       <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Date Due">Date of Initiation</label>
                                                <input readonly type="text"
                                                    value="{{Helpers::getdateFormat($data->intiation_date) }}"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>

                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Assigned to">Assigned to</label>
                                                <select name="assign_to"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="">-- Select --</option>
                                                    @foreach ($users as $key => $value)
                                                        <option value="{{ $value->id }}"
                                                            @if ($data->assign_to == $value->id) selected @endif>
                                                            {{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @php
                                        $initiationDate = date('Y-m-d');
                                        $dueDate = date('Y-m-d', strtotime($initiationDate . '+30 days'));
                                    @endphp
        
                                    <div class="col-md-6 new-date-data-field">
                                        <div class="group-input input-date">
                                            <label for="due-date">Date Due</label>
                                            <div><small class="text-primary">Please mention expected date of completion</small></div>
                                            <div class="calenderauditee">
                                                <input readonly type="text" value="{{ Helpers::getdateFormat($data->due_date) }}" name="due_date" />
                                                <input readonly type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                                            </div>
                                        </div>
                                    </div>
        
                                    <script>
                                            // Format the due date to DD-MM-YYYY
                                            // Your input date
                                            var dueDate = "{{ $dueDate }}"; // Replace {{ $dueDate }} with your actual date variable
        
                                            // Create a Date object
                                            var date = new Date(dueDate);
        
                                            // Array of month names
                                            var monthNames = [
                                                "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                                            ];
        
                                            // Extracting day, month, and year from the date
                                            var day = date.getDate().toString().padStart(2, '0'); // Ensuring two digits
                                            var monthIndex = date.getMonth();
                                            var year = date.getFullYear();
        
                                            // Formatting the date in "DD-MM-YYYY" format
                                            var dueDateFormatted = `${day}-${monthNames[monthIndex]}-${year}`;
        
                                            // Set the formatted due date value to the input field
                                            document.getElementById('due_date').value = dueDateFormatted;
                                        </script>

<div class="col-lg-6">
    <div class="group-input">
        <label for="Initiator Group"><b>Initiator Group</b></label>
        <select name="Initiator_Group" {{ $data->stage == 0 || $data->stage == 6 ? "disabled" : "" }} id="initiator_group">
            <option value="">Select Department</option>
            <option value="CQA" @if ($data->Initiator_Group == 'CQA') selected @endif>Corporate Quality Assurance</option>
            <option value="QAB" @if ($data->Initiator_Group == 'QAB') selected @endif>Quality Assurance Biopharma</option>
            <option value="CQC" @if ($data->Initiator_Group == 'CQC') selected @endif>Central Quality Control</option>
            <option value="MANU" @if ($data->Initiator_Group == 'MANU') selected @endif>Manufacturing</option>
            <option value="PSG" @if ($data->Initiator_Group == 'PSG') selected @endif>Plasma Sourcing Group</option>
            <option value="CS" @if ($data->Initiator_Group == 'CS') selected @endif>Central Stores</option>
            <option value="ITG" @if ($data->Initiator_Group == 'ITG') selected @endif>Information Technology Group</option>
            <option value="MM" @if ($data->Initiator_Group == 'MM') selected @endif>Molecular Medicine</option>
            <option value="CL" @if ($data->Initiator_Group == 'CL') selected @endif>Central Laboratory</option>
            <option value="TT" @if ($data->Initiator_Group == 'TT') selected @endif>Tech team</option>
            <option value="QA" @if ($data->Initiator_Group == 'QA') selected @endif>Quality Assurance</option>
            <option value="QM" @if ($data->Initiator_Group == 'QM') selected @endif>Quality Management</option>
            <option value="IA" @if ($data->Initiator_Group == 'IA') selected @endif>IT Administration</option>
            <option value="ACC" @if ($data->Initiator_Group == 'ACC') selected @endif>Accounting</option>
            <option value="LOG" @if ($data->Initiator_Group == 'LOG') selected @endif>Logistics</option>
            <option value="SM" @if ($data->Initiator_Group == 'SM') selected @endif>Senior Management</option>
            <option value="BA" @if ($data->Initiator_Group == 'BA') selected @endif>Business Administration</option>
        </select>
    </div>
</div>


                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Initiator Group Code</label>
                                        <input type="text" id="initiator_group_code"  name="initiator_group_code" {{ $data->stage == 0 || $data->stage == 6 ? "disabled" : "" }} value="{{$data->initiator_group_code}}" readonly>
                                    </div>

                                </div>
                                     <!-- <div class="col-12">
                                            <div class="group-input">
                                                <label for="Short Description">Short Description <span
                                                        class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please mention brief summary</small></div>
                                                <textarea name="short_description" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->short_description }}</textarea>
                                            </div>
                                        </div>  -->
                                        <!-- <div class="col-12">
                                            <div class="group-input">
                                                <label for="Short Description">Short Description<span
                                                        class="text-danger">*</span></label><span id="rchars">255</span>
                                                characters remaining
                                                
                                                <textarea name="short_description"   id="docname" type="text"    maxlength="255" required  {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->short_description }}</textarea>
                                            </div>
                                            <p id="docnameError" style="color:red">**Short Description is required</p>
        
                                        </div> -->
                                        <div class="col-12">
    <div class="group-input">
        <label for="Short Description">Short Description<span class="text-danger">*</span></label>
        <span id="rchars">255</span> characters remaining
        <div style="position: relative;">
            <input id="docname" type="text" value="{{ $data->short_description }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} name="short_description" maxlength="255" required>
            <button class="mic-btn" type="button" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button" style="position: absolute; right: 40px; top: 50%; transform: translateY(-50%);">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="severity-level">Severity Level</label>
                                                <span class="text-primary">Severity levels in a QMS record gauge issue seriousness, guiding priority for corrective actions. Ranging from low to high, they ensure quality standards and mitigate critical risks.</span>
                                                <select {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} name="severity_level">
                                                    <option value="0">-- Select --</option>
                                                    <option @if ($data->severity_level == 'minor') selected @endif
                                                     value="minor">Minor</option>
                                                    <option  @if ($data->severity_level == 'major') selected @endif 
                                                    value="major">Major</option>
                                                    <option @if ($data->severity_level == 'critical') selected @endif
                                                    value="critical">Critical</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Initiator Group">Initiated Through</label>
                                                <div><small class="text-primary">Please select related information</small></div>
                                                <select {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} name="initiated_through"
                                                    onchange="otherController(this.value, 'others', 'initiated_through_req')">
                                                    <option value="">-- select --</option>
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
        <label for="initiated_if_other">Others<span class="text-danger d-none">*</span></label>
        <div class="relative-container">
            <textarea name="initiated_if_other" id="initiated_if_other" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{$data->initiated_if_other}}</textarea>
            <button class="mic-btn" type="button" style="display: none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

                                        {{-- <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="repeat">Repeat</label>
                                                <select {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} name="repeat"
                                                    onchange="otherController(this.value, 'yes', 'repeat_nature')">
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option  @if ($data->repeat == 'Yes') selected @endif value="Yes">Yes</option>
                                                    <option  @if ($data->repeat == 'No') selected @endif value="No">No</option>
                                                    <option  @if ($data->repeat == 'NA') selected @endif value="NA">NA</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input" id="repeat_nature">
                                                <label for="repeat_nature">Repeat Nature<span
                                                        class="text-danger d-none">*</span></label>
                                                <textarea {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} name="repeat_nature">{{$data->repeat_nature}}</textarea>
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-6">
    <div class="group-input">
        <label for="audit_type">Type of Audit</label>
        <select name="audit_type" id="audit_type"
                onchange="otherController(this.value, 'others', 'if_other')"
                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
            <option value="">Enter Your Selection Here</option>
            <option value="R&D" {{ old('audit_type', $data->audit_type) == 'R&D' ? 'selected' : '' }}>R&D</option>
            <option value="GLP" {{ old('audit_type', $data->audit_type) == 'GLP' ? 'selected' : '' }}>GLP</option>
            <option value="GCP" {{ old('audit_type', $data->audit_type) == 'GCP' ? 'selected' : '' }}>GCP</option>
            <option value="GDP" {{ old('audit_type', $data->audit_type) == 'GDP' ? 'selected' : '' }}>GDP</option>
            <option value="GEP" {{ old('audit_type', $data->audit_type) == 'GEP' ? 'selected' : '' }}>GEP</option>
            <option value="ISO 17025" {{ old('audit_type', $data->audit_type) == 'ISO 17025' ? 'selected' : '' }}>ISO 17025</option>
            <option value="others" {{ old('audit_type', $data->audit_type) == 'others' ? 'selected' : '' }}>Others</option>
        </select>
    </div>
</div>

<div class="col-lg-6">
    <div class="group-input" id="if_other">
        <label for="if_other_textarea">If Other<span class="text-danger d-none">*</span></label>
        <div class="relative-container">
            <textarea name="if_other" id="if_other_textarea" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->if_other }}</textarea>
            <button class="mic-btn" type="button" style="display: none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

                                        
                                        <div class="col-lg-6">
    <div class="group-input">
        <label for="external_agencies">Supplier Agencies</label>
        <select {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} name="external_agencies" id="external_agencies">
            <option value="">-- Select --</option>
            <option @if ($data->external_agencies == 'jordan_fda') selected @endif value="jordan_fda">Jordan FDA</option>
            <option @if ($data->external_agencies == 'us_fda') selected @endif value="us_fda">USFDA</option>
            <option @if ($data->external_agencies == 'mhra') selected @endif value="mhra">MHRA</option>
            <option @if ($data->external_agencies == 'anvisa') selected @endif value="anvisa">ANVISA</option>
            <option @if ($data->external_agencies == 'iso') selected @endif value="iso">ISO</option>
            <option @if ($data->external_agencies == 'who') selected @endif value="who">WHO</option>
            <option @if ($data->external_agencies == 'local_fda') selected @endif value="local_fda">Local FDA</option>
            <option @if ($data->external_agencies == 'tga') selected @endif value="tga">TGA</option>
            <option value="others" @if ($data->external_agencies == 'others') selected @endif>Others</option>
        </select>
    </div>
</div>
      
<div class="col-lg-6" id="others_group">
    <div class="group-input">
        <label for="others">Supplier Agencies Others<span class="text-danger d-none">*</span></label>
        <textarea name="others" id="others" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->others }}</textarea>
    </div>
</div>

<div class="col-12">
    <div class="group-input">
        <label for="initial_comments">Description</label>
        <div class="relative-container">
            <textarea name="initial_comments" id="initial_comments" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->initial_comments }}</textarea>
            <button class="mic-btn" type="button" style="display: none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

                                        
                                    
<div class="col-lg-12">
    <div class="group-input">
        <label for="File Attachments">Initial Attachment</label>
        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
        <div class="file-attachment-field">
            <div disabled class="file-attachment-list" id="inv_attachment">
                @if ($data->inv_attachment)
                    @foreach(json_decode($data->inv_attachment) as $file)
                        <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                            <b>{{ $file }}</b>
                            <a href="{{ asset('upload/' . $file) }}" target="_blank">
                                <i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i>
                            </a>
                            <a type="button" class="remove-file" data-file-name="{{ $file }}">
                                <i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i>
                            </a>
                        </h6>
                    @endforeach
                @endif
            </div>
            <div class="file-input-wrapper">
                <div class="add-btn">
                    <div>Add</div>
                    <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file" id="myfile" name="inv_attachment[]" oninput="addMultipleFiles(this, 'inv_attachment')" multiple>
                </div>
            </div>
        </div>
    </div>
</div>
                                    </div>
                                    <div class="button-block">
                                        @if ($data->stage != 0)
                                            <button type="submit" id="ChangesaveButton" class="saveButton"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        @endif
                                        <button type="button" id="ChangeNextButton" class="nextButton">Next</button>
                                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}"
                                                class="text-white"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Audit Planning content -->
                            <div id="CCForm2" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="col-lg-6 new-date-data-field">
                                            <div class="group-input input-date">
                                                <label for="Audit Schedule Start Date">Audit Schedule Start Date</label>
                                                <div class="calenderauditee">                                     
                                                    <input type="text" id="start_date" readonly placeholder="DD-MM-YYYY" value="{{ Helpers::getdateFormat($data->start_date) }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}/>
                                                    <input type="date" id="start_date_checkdate" name="start_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ $data->start_date ? $data->start_date->format('Y-m-d') : '' }}" class="hide-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} oninput="handleDateInput(this, 'start_date');updateEndDateMin();"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 new-date-data-field">
                                            <div class="group-input input-date">
                                                <label for="Audit Schedule End Date">Audit Schedule End Date</label>
                                                <div class="calenderauditee">                                     
                                                    <input type="text" id="end_date" readonly placeholder="DD-MM-YYYY" value="{{ Helpers::getdateFormat($data->end_date) }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}/>
                                                    <input type="date" id="end_date_checkdate" name="end_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ $data->end_date ? $data->end_date ->format('Y-m-d') : '' }}" class="hide-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} oninput="handleDateInput(this, 'end_date');"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="audit-agenda-grid">
                                                    Audit Agenda
                                                    <button type="button" name="audit-agenda-grid" onclick="addAuditAgenda('audit-agenda-grid')" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                                </label>
                                                <table class="table table-bordered" id="audit-agenda-grid">
                                                    <thead>
                                                        <tr>
                                                            <th>Row #</th>
                                                            <th>Area of Audit</th>
                                                            <th>Scheduled Start Date</th>
                                                            <th>Scheduled Start Time</th>
                                                            <th>Scheduled End Date</th>
                                                            <th>Scheduled End Time</th>
                                                            <th>Auditor</th>
                                                            <th>Auditee</th>
                                                            <th>Remarks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($sgrid->start_date)
                                                            @foreach (unserialize($sgrid->start_date) as $key => $temps)
                                                                <tr>
                                                                    <td><input disabled type="text" name="serial_number[]" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{ $key + 1 }}"></td>
                                                                    <td><input type="text" name="audit[]" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{ unserialize($sgrid->area_of_audit)[$key] ?? '' }}"></td>
                                                                    <td>
                                                                        <div class="group-input new-date-data-field mb-0">
                                                                            <div class="input-date ">
                                                                                <div class="calenderauditee">
                                                                                    <input type="text" class="test" id="scheduled_start_date{{$key}}" readonly placeholder="DD-MM-YYYY" value="{{ Helpers::getdateFormat(unserialize($sgrid->start_date)[$key]) }}" />
                                                                                    <input type="date" id="schedule_start_date{{$key}}_checkdate" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} name="scheduled_start_date[]" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ unserialize($sgrid->start_date)[$key] ?? '' }}" class="hide-input" oninput="handleDateInput(this, `scheduled_start_date{{$key}}`);checkDate('schedule_start_date{{$key}}_checkdate','schedule_end_date{{$key}}_checkdate')" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><input type="time" name="scheduled_start_time[]" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{ unserialize($sgrid->start_time)[$key] ?? '' }}"></td>
                                                                    <td>
                                                                        <div class="group-input new-date-data-field mb-0">
                                                                            <div class="input-date ">
                                                                                <div class="calenderauditee">
                                                                                    <input type="text" class="test" id="scheduled_end_date{{$key}}" readonly placeholder="DD-MM-YYYY" value="{{ Helpers::getdateFormat(unserialize($sgrid->end_date)[$key]) }}" />
                                                                                    <input type="date" id="schedule_end_date{{$key}}_checkdate" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} name="scheduled_end_date[]" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ unserialize($sgrid->end_date)[$key] ?? '' }}" class="hide-input" oninput="handleDateInput(this, `scheduled_end_date{{$key}}`);checkDate('schedule_start_date{{$key}}_checkdate','schedule_end_date{{$key}}_checkdate')" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><input type="time" name="scheduled_end_time[]" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{ unserialize($sgrid->end_time)[$key] ?? '' }}"></td>
                                                                    <td>
                                                                        <select id="select-state" placeholder="Select..." name="auditor[]" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                                            <option value="">-Select-</option>
                                                                            @foreach ($users as $value)
                                                                                <option {{ unserialize($sgrid->auditor)[$key] == $value->id ? 'selected' : '' }} value="{{ $value->id }}">{{ $value->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select id="select-state" placeholder="Select..." name="auditee[]" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                                            <option value="">-Select-</option>
                                                                            @foreach ($users as $value)
                                                                                <option {{ unserialize($sgrid->auditee)[$key] == $value->id ? 'selected' : '' }} value="{{ $value->id }}">{{ $value->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="text" name="remark[]" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{ unserialize($sgrid->remark)[$key] ?? '' }}"></td>
                                                                    <td><button type="button" class="removeRowBtn" style="background-color: black;color: white;" onclick="removeRow(this)">Remove</button></td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                       
<div class="col-lg-6">
    <div class="group-input">
        <label for="material_name">Product/Material Name</label>
        <div class="relative-container">
            <input type="text" name="material_name" id="material_name" class="mic-input" value="{{ $data->material_name }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
            <button class="mic-btn" type="button" style="display:none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="group-input">
        <label for="if_comments">Comments(If Any)</label>
        <div class="relative-container">
            <textarea name="if_comments" id="if_comments" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->if_comments }}</textarea>
            <button class="mic-btn" type="button" style="display:none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

                                    </div>
                                    <div class="button-block">
                                        @if ($data->stage != 0)
                                            <button type="submit" id="ChangesaveButton" class="saveButton"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        @endif
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}"
                                                class="text-white"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Audit Preparation content -->
                            <div id="CCForm3" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="Lead Auditor">Lead Auditor</label>
                                                <select name="lead_auditor"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="">-- Select --</option>
                                                    @foreach ($users as $key => $value)
                                                        <option value="{{ $value->id }}"
                                                            @if ($data->lead_auditor == $value->id) selected @endif>
                                                            {{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="File Attachments">File Attachment</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                                <div class="file-attachment-field">
                                                    <div disabled  class="file-attachment-list" id="file_attachment">
                                                        @if ($data->file_attachment)
                                                            @foreach(json_decode($data->file_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a  type="button" class="remove-file" data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif

                                                    </div>
                                                    <div  class="add-btn">
                                                        <div>Add</div>
                                                        <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file" id="myfile" name="file_attachment[]"
                                                         oninput="addMultipleFiles(this, 'file_attachment')"
                                                            multiple>
                                                    </div>
                                                </div>
                                                {{-- <input type="file" id="myfile" name="file_attachment"
                                                    value="{{ $data->file_attachment }}" --}}
                                                    {{-- {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}> --}}
                                            </div>
                                        </div>
                                        {{-- <div class="col-12">
                                            <div class="group-input">
                                                <label for="audit-agenda-grid">
                                                    Observation Details
                                                    <button type="button" name="audit-agenda-grid"
                                                      id="ObservationAdd">+</button>
                                                    <span class="text-primary" data-bs-toggle="modal"
                                                        data-bs-target="#observation-field-instruction-modal"
                                                        style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                                        (Launch Instruction)
                                                    </span>
                                                </label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="onservation-field-table"
                                                        style="width: 150%;">
                                                        <thead>
                                                            <tr>
                                                                <th>Row#</th>
                                                                <th>Observation ID</th>
                                                                <th>Date</th>
                                                                <th>Auditor</th>
                                                                <th>Auditee</th>
                                                                <th>Observation Description</th>
                                                                <th>Severity Level</th>
                                                                <th>Area/process</th>
                                                                <th>Observation Category</th>
                                                                <th>CAPA Required</th>
                                                                <th>Auditee Response</th>
                                                                <th>Auditor Review on Response</th>
                                                                <th>QA Comments</th>
                                                                <th>CAPA Details</th>
                                                                <th>CAPA Due Date</th>
                                                                <th>CAPA Owner</th>
                                                                <th>Action Taken</th>
                                                                <th>CAPA Completion Date</th>
                                                                <th>Status</th>
                                                                <th>Remarks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="observationDetail">
                                                            @if ($grid_data1->observation_id)
                                                            @foreach (unserialize($grid_data1->observation_id) as $key => $tempData)
                                                            <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td><input type="text" name="observation_id[]" value="{{ $tempData ? $tempData : "" }}"></td>
                                                                    <td><input type="date" name="date[]" value="{{unserialize($grid_data1->date)[$key] ? unserialize($grid_data1->date)[$key]: "" }}"></td>
                                                                <td>
                                                                    <select placeholder="Select..." name="auditorG[]">
                                                                        <option value="">Select a value</option>
                                                                        @foreach ($users as $datas)
                                                                            <option value="{{ $datas->id }}">

                                                                                {{ $datas->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select placeholder="Select..." name="auditeeG[]">
                                                                        <option value="">Select a value</option>
                                                                        @foreach ($users as $datas)
                                                                            <option value="{{ $datas->id }}">

                                                                                {{ $datas->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>                                                            <td><input type="text" name="observation_description[]" value="{{unserialize($grid_data1->observation_description)[$key] ? unserialize($grid_data1->observation_description)[$key]: "" }}"></td>
                                                                    <td><input type="text" name="severity_level[]" value="{{unserialize($grid_data1->severity_level)[$key] ? unserialize($grid_data1->severity_level)[$key]: "" }}"></td>
                                                                    <td><input type="text" name="area[]" value="{{unserialize($grid_data1->area)[$key] ? unserialize($grid_data1->area)[$key]: "" }}"></td>
                                                                    <td><input type="text" name="observation_category[]" value="{{unserialize($grid_data1->observation_category)[$key] ? unserialize($grid_data1->observation_category)[$key]: "" }}"></td>
                                                                    <td>
                                                                        <select name="capa_required[]">
                                                                            <option value="0">-- Select --</option>
                                                                            <option value="yes">Yes</option>
                                                                            <option value="no">No</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="text" name="auditee_response[]" value="{{unserialize($grid_data1->auditee_response)[$key] ? unserialize($grid_data1->auditee_response)[$key]: "" }}"></td>
                                                                    <td><input type="text" name="auditor_review_on_response[]" value="{{unserialize($grid_data1->auditor_review_on_response)[$key] ? unserialize($grid_data1->auditor_review_on_response)[$key]: "" }}"></td>
                                                                    <td><input type="text" name="qa_comment[]" value="{{unserialize($grid_data1->qa_comment)[$key] ? unserialize($grid_data1->qa_comment)[$key]: "" }}"></td>
                                                                    <td><input type="text" name="capa_details[]" value="{{unserialize($grid_data1->capa_details)[$key] ? unserialize($grid_data1->capa_details)[$key]: "" }}"></td>
                                                                    <td><input type="date" name="capa_due_date[]" value="{{unserialize($grid_data1->capa_due_date)[$key] ? unserialize($grid_data1->capa_due_date)[$key]: "" }}"></td>
                                                                    <td>
                                                                        <select placeholder="Select..." name="capa_owner[]">
                                                                            <option value="">Select a value</option>
                                                                            @foreach ($users as $datas)
                                                                                <option value="{{ $datas->id }}">
                                                                                    {{ $datas->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="text" name="action_taken[]" value="{{unserialize($grid_data1->action_taken)[$key] ? unserialize($grid_data1->action_taken)[$key]: "" }}"></td>
                                                                    <td><input type="date" name="capa_completion_date[]" value="{{unserialize($grid_data1->capa_completion_date)[$key] ? unserialize($grid_data1->capa_completion_date)[$key]: "" }}"></td>
                                                                    <td><input type="text" name="status_Observation[]" value="{{unserialize($grid_data1->status)[$key] ? unserialize($grid_data1->status)[$key]: "" }}"></td>
                                                                    <td><input type="text" name="remark_observation[]" value="{{unserialize($grid_data1->remark)[$key] ? unserialize($grid_data1->remark)[$key]: "" }}"></td>
                                                                </tr>
                                                            @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-6">
    <div class="group-input">
        <label for="Audit Team">Audit Team</label>
        <select multiple name="Audit_team[]" placeholder="Select Audit Team"
            data-search="false" data-silent-initial-value-set="true"
            id="Audit"
            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
            @foreach ($users as $user)
                <option value="{{ $user->id }}"
                    {{ in_array($user->id, explode(',', $data->Audit_team)) ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-6">
    <div class="group-input">
        <label for="Auditee">Auditee</label>
        <select multiple name="Auditee[]" placeholder="Select Auditee"
            data-search="false" data-silent-initial-value-set="true"
            id="Auditee"
            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
            @foreach ($users as $user)
                <option value="{{ $user->id }}"
                    {{ in_array($user->id, explode(',', $data->Auditee)) ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-12">
    <div class="group-input">
        <label for="Auditor_Details">Suppliers Auditor Details</label>
        <div class="relative-container">
            <textarea name="Auditor_Details" id="Auditor_Details" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Auditor_Details }}</textarea>
            <button class="mic-btn" type="button" style="display:none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="group-input">
        <label for="External_Auditing_Agency">Supplier Auditing Agency</label>
        <div class="relative-container">
            <textarea name="External_Auditing_Agency" id="External_Auditing_Agency" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->External_Auditing_Agency }}</textarea>
            <button class="mic-btn" type="button" style="display:none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="group-input">
        <label for="Relevant_Guidelines">Relevant Guidelines / Industry Standards</label>
        <div class="relative-container">
            <textarea name="Relevant_Guidelines" id="Relevant_Guidelines" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Relevant_Guidelines }}</textarea>
            <button class="mic-btn" type="button" style="display:none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="group-input">
        <label for="QA_Comments">QA Comments</label>
        <div class="relative-container">
            <textarea name="QA_Comments" id="QA_Comments" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->QA_Comments }}</textarea>
            <button class="mic-btn" type="button" style="display:none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Guideline Attachment">Guideline Attachment</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting
                                                        documents</small></div>
                                                <div class="file-attachment-field">
                                                    <div disabled  class="file-attachment-list" id="file_attachment_guideline">
                                                        @if ($data->file_attachment_guideline)
                                                            @foreach(json_decode($data->file_attachment_guideline) as $file)
                                                                <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a  type="button" class="remove-file" data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif

                                                    </div>
                                                    <div  class="add-btn">
                                                        <div>Add</div>
                                                        <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file" id="myfile" name="file_attachment_guideline[]"
                                                            oninput="addMultipleFiles(this, 'file_attachment_guideline')"
                                                            multiple>
                                                    </div>
                                                </div>

                                                
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Audit Category">Audit Category</label>
                                                <select name="Audit_Category" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="0">-- Select --</option>
                                                    <option @if ($data->Audit_Category == '1') selected @endif
                                                         value="1">Internal Audit/Self Inspection</option>
                                                    <option  @if ($data->Audit_Category == '2') selected @endif
                                                         value="2">Supplier Audit</option>
                                                    <option @if ($data->Audit_Category == '3') selected @endif
                                                         value="3">Regulatory Audit</option>
                                                    <option @if ($data->Audit_Category == '4') selected @endif
                                                         value="4">Consultant Audit</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
    <div class="group-input">
        <label for="Supplier_Details">Supplier/Vendor/Manufacturer Details</label>
        <div class="relative-container">
            <textarea name="Supplier_Details" id="Supplier_Details" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Supplier_Details }}</textarea>
            <button class="mic-btn" type="button" style="display:none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="group-input">
        <label for="Supplier_Site">Supplier/Vendor/Manufacturer Site</label>
        <div class="relative-container">
            <textarea name="Supplier_Site" id="Supplier_Site" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Supplier_Site }}</textarea>
            <button class="mic-btn" type="button" style="display:none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-12">
    <div class="group-input">
        <label for="Comments">Comments</label>
        <div class="relative-container">
            <textarea name="Comments" id="Comments" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Comments }}</textarea>
            <button class="mic-btn" type="button" style="display:none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

                                    </div>
                                    <div class="button-block">
                                        @if ($data->stage != 0)
                                            <button type="submit" id="ChangesaveButton" class="saveButton"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        @endif
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}"
                                                class="text-white"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Audit Execution content -->
                            <div id="CCForm4" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        {{-- <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Due Date">Due Date</label>
                                                <input type="hidden" name="due_date" value="{{ $data->due_date }}">
                                                <div class="static">{{ $data->due_date }}</div>
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-6 new-date-data-field">
                                            <div class="group-input input-date">
                                                <label for="Audit Start Date">Audit Start Date</label>
                                                <div class="calenderauditee">                                     
                                                    <input type="text" id="audit_start_date" readonly placeholder="DD-MM-YYYY" value="{{ Helpers::getdateFormat($data->audit_start_date) }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} />
                                                    <input type="date" id="audit_start_date_checkdate" name="audit_start_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ $data->audit_start_date }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} class="hide-input" oninput="handleDateInput(this, 'audit_start_date');updateEndDateMinAudit();" />
                                                </div>    
                                            </div>
                                        </div>
                                        <div class="col-lg-6 new-date-data-field">
                                            <div class="group-input input-date">
                                                <label for="Audit End Date">Audit End Date</label>
                                                <div class="calenderauditee">                                     
                                                    <input type="text" id="audit_end_date" readonly placeholder="DD-MM-YYYY" value="{{ Helpers::getdateFormat($data->audit_end_date) }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} />
                                                    <input type="date" id="audit_end_date_checkdate" name="audit_end_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ $data->audit_end_date }}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} class="hide-input" oninput="handleDateInput(this, 'audit_end_date');" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
    <div class="group-input">
        <label for="audit-agenda-grid">
            Observation Details
            <button type="button" name="audit-agenda-grid" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} id="ObservationAdd">+</button>
            <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                (Launch Instruction)
            </span>
        </label>
        <div class="table-responsive">
            <table class="table table-bordered" id="onservation-field-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 4%;">Row#</th>
                        <th>Observation Details</th>
                        <th>Pre Comments</th>
                        <th>CAPA Details if any</th>
                        <th>Post Comments</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="observationDetail">
                    @if ($grid_data1->observation_id)
                    @foreach (unserialize($grid_data1->observation_id) as $key => $tempData)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><input type="text" name="observation_id[]" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{ $tempData ? $tempData : "" }}"></td>
                        <td><input type="text" name="observation_description[]" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{unserialize($grid_data1->observation_description)[$key] ? unserialize($grid_data1->observation_description)[$key]: "" }}"></td>
                        <td><input type="text" name="area[]" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{unserialize($grid_data1->area)[$key] ? unserialize($grid_data1->area)[$key]: "" }}"></td>
                        <td><input type="text" name="auditee_response[]" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{unserialize($grid_data1->auditee_response)[$key] ? unserialize($grid_data1->auditee_response)[$key]: "" }}"></td>
                        <td><button type="button" class="removeRowBtn" style="background-color: black;color: white;">Remove</button></td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="Audit Attachments">Audit Attachments</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                                {{-- <input type="file" id="myfile" name="Audit_file"
                                                    value="{{ $data->Audit_file }}"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}> --}}
                                                    <div class="file-attachment-field">
                                                        <div class="file-attachment-list" id="Audit_file">
                                                            @if ($data->Audit_file)
                                                            @foreach(json_decode($data->Audit_file) as $file)
                                                            <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                                <b>{{ $file }}</b>
                                                                <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                                <a  type="button" class="remove-file" data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                            </h6>
                                                       @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="add-btn">
                                                            <div>Add</div>
                                                            <input type="file" id="myfile" name="Audit_file[]"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                oninput="addMultipleFiles(this, 'Audit_file')" multiple>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
    <div class="group-input">
        <label for="Audit_Comments1">Audit Comments</label>
        <div class="relative-container">
            <textarea name="Audit_Comments1" id="Audit_Comments1" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Audit_Comments1 }}</textarea>
            <button class="mic-btn" type="button" style="display:none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>

                                    </div>
                                    <div class="button-block">
                                        @if ($data->stage != 0)
                                            <button type="submit" id="ChangesaveButton" class="saveButton"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        @endif
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}"
                                                class="text-white"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Audit Response & Closure content -->
                            <div id="CCForm5" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="sub-head">
                                            Audit Response
                                        </div>
                                        <div class="col-12">
                                        <div class="col-12">
    <div class="group-input">
        <label for="Remarks">Remarks</label>
        <div class="relative-container">
            <textarea name="Remarks" id="Remarks" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Remarks }}</textarea>
            <button class="mic-btn" type="button" style="display:none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>


                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="Reference Recores">Reference Record</label>
                                                <select {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} multiple id="reference_record" name="refrence_record[]" id="">
                                                    {{-- <option value="">--Select---</option> --}}
                                                    @foreach ($old_record as $new)
                                                        <option value="{{ $new->id }}"  {{ in_array($new->id, explode(',', $data->Reference_Recores1)) ? 'selected' : '' }}>
                                                            {{ Helpers::getDivisionName($new->division_id) }}/IA/{{date('Y')}}/{{ Helpers::recordFormat($new->record) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="Report Attachments">Report Attachments</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                                {{-- <input type="file" id="myfile" name="report_file"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}> --}}
                                                    <div class="file-attachment-field">
                                                        <div class="file-attachment-list" id="report_attachment">
                                                            @if ($data->report_file)
                                                            @foreach(json_decode($data->report_file) as $file)
                                                            <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                                <b>{{ $file }}</b>
                                                                <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                                <a  type="button" class="remove-file" data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                            </h6>
                                                       @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="add-btn">
                                                            <div>Add</div>
                                                            <input  {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file" id="myfile" name="report_file[]"
                                                                oninput="addMultipleFiles(this, 'report_attachment')" multiple>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Audit Attachments">Audit Attachments</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                                {{-- <input type="file" id="myfile" name="myfile"
                                                    value="{{ $data->myfile }}"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}> --}}
                                                    <div class="file-attachment-field">
                                                        <div class="file-attachment-list" id="audit_attachment">
                                                            @if ($data->myfile)
                                                            @foreach(json_decode($data->myfile) as $file)
                                                            <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                                <b>{{ $file }}</b>
                                                                <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                                <a  type="button" class="remove-file" data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                            </h6>
                                                       @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="add-btn">
                                                            <div>Add</div>
                                                            <input  {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file" id="myfile" name="myfile[]"
                                                                oninput="addMultipleFiles(this, 'audit_attachment')" multiple>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
    <div class="group-input">
        <label for="Audit_Comments2">Audit Comments</label>
        <div class="relative-container">
            <textarea name="Audit_Comments2" id="Audit_Comments2" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Audit_Comments2 }}</textarea>
            <button class="mic-btn" type="button" style="display:none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div>
                                                </div>

                                                <div class="col-12">
    <div class="group-input">
        <label for="due_date_extension">Due Date Extension Justification</label>
        <div><small class="text-primary">Please Mention justification if due date is crossed</small></div>
        <div class="relative-container">
            <textarea name="due_date_extension" id="due_date_extension" class="mic-input" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{$data->due_date_extension}}</textarea>
            <button class="mic-btn" type="button" style="display:none;">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="speak-btn" type="button">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="mini-modal">
                <div class="mini-modal-content">
                    <span class="close">&times;</span>
                    <h2>Select Language</h2>
                    <select id="language-select">
                        <option value="en-us">English</option>
                        <option value="hi-in">Hindi</option>
                        <option value="te-in">Telugu</option>
                        <option value="fr-fr">French</option>
                        <option value="es-es">Spanish</option>
                        <option value="zh-cn">Chinese (Mandarin)</option>
                        <option value="ja-jp">Japanese</option>
                        <option value="de-de">German</option>
                        <option value="ru-ru">Russian</option>
                        <option value="ko-kr">Korean</option>
                        <option value="it-it">Italian</option>
                        <option value="pt-br">Portuguese (Brazil)</option>
                        <option value="ar-sa">Arabic</option>
                        <option value="bn-in">Bengali</option>
                        <option value="pa-in">Punjabi</option>
                        <option value="mr-in">Marathi</option>
                        <option value="gu-in">Gujarati</option>
                        <option value="ur-pk">Urdu</option>
                        <option value="ta-in">Tamil</option>
                        <option value="kn-in">Kannada</option>
                        <option value="ml-in">Malayalam</option>
                        <option value="or-in">Odia</option>
                        <option value="as-in">Assamese</option>
                        <!-- Add more languages as needed -->
                    </select>
                    <button id="select-language-btn">Select</button>
                </div>
            </div>
        </div>
    </div>
</div></div>
                                    <div class="button-block">
                                        @if ($data->stage != 0)
                                            <button type="submit" id="ChangesaveButton" class="saveButton"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        @endif
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}"
                                                class="text-white"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Activity Log content -->
                            <div id="CCForm6" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Schedule On">Schedule Audit By</label>
                                                <div class="static">{{ $data->audit_schedule_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Schedule On">Schedule Audit On</label>
                                                <div class="static">{{ $data->audit_schedule_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Schedule On">Comment</label>
                                                <div class="static">{{ $data->comment }}</div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Preparation Completed On">Completed Audit Preparation
                                                    By</label>
                                                <div class="static">{{ $data->audit_preparation_completed_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Preparation Completed On">Completed Audit Preparation
                                                    On</label>
                                                <div class="static">{{ $data->audit_preparation_completed_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Schedule On">Comment</label>
                                                <div class="static">{{ $data->audit_preparation_comment }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Response Feedback Verified By"> Rejected By
                                                    </label> 
                                                <div class="static">{{ $data->rejected_by}}</div>Audit Agend
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Response Feedback Verified On"> Rejected On
                                                    </label>
                                                <div class="static">{{ $data->rejected_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Schedule On">Comment</label>
                                                <div class="static">{{$data->comment_rejected_comment}}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Cancelled By">Cancelled By</label>
                                                <div class="static">{{ $data->cancelled_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Cancelled On">Cancelled On</label>
                                                <div class="static">{{ $data->cancelled_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Schedule On"> Comment</label>
                                                <div class="static">{{$data->comment_cancelled_comment}}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="No CAPA Required By">No CAPA Required By</label>
                                                <div class="static">{{ $data->audit_response_completed_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="No Capa Required On">No CAPA Required On</label>
                                                <div class="static">{{ $data->audit_response_completed_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Schedule On"> Comment</label>
                                                <div class="static">{{$data->comment_closed_done_by_comment}}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Mgr.more Info Reqd By">Issue  Report
                                                    By</label>
                                                <div class="static">{{ $data->audit_mgr_more_info_reqd_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Mgr.more Info Reqd On">Issue  Report
                                                    On</label>
                                                <div class="static">{{ $data->audit_mgr_more_info_reqd_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Schedule On">Comment</label>
                                                <div class="static">{{ $data->pending_response_comment }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Observation Submitted By">CAPA Plan Proposed
                                                    By</label>
                                                <div class="static">{{ $data->audit_observation_submitted_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Observation Submitted On">CAPA Plan Proposed
                                                    On</label>
                                                <div class="static">{{ $data->audit_observation_submitted_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Schedule On">Comment</label>
                                                <div class="static">{{ $data->capa_execution_in_progress_comment }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Lead More Info Reqd By">All CAPA Closed By</label>
                                                <div class="static">{{ $data->audit_lead_more_info_reqd_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Lead More Info Reqd On">All CAPA Closed On</label>
                                                <div class="static">{{ $data->audit_lead_more_info_reqd_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="group-input">
                                                <label for="Audit Schedule On">Comment</label>
                                                <div class="static">{{ $data->comment_closed_done_by_comment }}</div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Audit Response Completed By">Audit Response Completed
                                                    By</label>
                                                <div class="static">{{ $data->audit_response_completed_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Audit Response Completed On">Audit Response Completed
                                                    On</label>
                                                <div class="static">{{ $data->audit_response_completed_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Response Feedback Verified By">Response Feedback Verified
                                                    By</label>
                                                <div class="static">{{ $data->response_feedback_verified_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Response Feedback Verified On">Response Feedback Verified
                                                    On</label>
                                                <div class="static">{{ $data->response_feedback_verified_on }}</div>
                                            </div>
                                        </div> -->


                                    </div>
                                    <div class="button-block">
                                        <!-- @if ($data->stage != 0)
                                            <button type="submit" id="ChangesaveButton" class="saveButton"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        @endif -->
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <!-- <button type="submit"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Submit</button> -->
                                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}"
                                                class="text-white"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
            <div class="modal fade" id="child-modal1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Child</h4>
                        </div>
                        <form action="{{ route('extension_child', $data->id) }}" method="POST">
                            @csrf
                            <!-- Modal body -->
                            {{-- <div class="modal-body">
                                <div class="group-input">
                                    <label for="major">
                                        <input type="hidden" name="parent_name" value="External_audit">
                                        <input type="hidden" name="due_date" value="{{ $data->due_date }}">
                                        <input type="radio" name="child_type" value="extension">
                                        extension
                                    </label>

                                </div>

                            </div> --}}

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
                        <form action="{{ route('SupplierAuditStateChange_view', $data->id) }}" method="POST">
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


            <div class="modal fade" id="rejection-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="{{ url('RejectStateAuditee', $data->id) }}" method="POST">
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
                                    <input type="comment" name="comment" required >
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

            <div class="modal fade" id="cancel-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="{{ route('CancelStateSupplierAudit', $data->id) }}" method="POST">
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
                                    <input type="comment" name="comment"required >
                                </div>
                            </div>

                            <!-- Modal footer -->
                            <!-- <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button>Close</button>
                            </div> -->
                            <div class="modal-footer">
                              <button type="submit">Submit</button>
                                <button data-bs-dismiss="modal">Close</button>
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
                        <form action="{{ route('child_external_Supplier', $data->id) }}" method="POST">
                            @csrf
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="group-input">
                                    <label></lable>
                                    <label for="major">
                                        <input type="radio" name="child_type" value="Observations">
                                        Observations
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
            </style>

            <script>
                VirtualSelect.init({
                    ele: '#Facility, #Group, #Audit, #Auditee , #reference_record'
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
                        document.addEventListener('DOMContentLoaded', function () {
                            const removeButtons = document.querySelectorAll('.remove-file');
            
                            removeButtons.forEach(button => {
                                button.addEventListener('click', function () {
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
                        var maxLength = 255;
                        $('#docname').keyup(function() {
                            var textlen = maxLength - $(this).val().length;
                            $('#rchars').text(textlen);});
                    </script>
  <script>
    var maxLength = 255;
    $('#docname').keyup(function() {
        var textlen = maxLength - $(this).val().length;
        $('#rchars').text(textlen);});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const externalAgencies = document.getElementById('external_agencies');
        const othersGroup = document.getElementById('others_group');
        const othersField = document.getElementById('others');
        const othersLabel = othersField.previousElementSibling;

        function toggleOthersField() {
            if (externalAgencies.value === 'others') {
                othersGroup.style.display = 'block';
                othersField.required = true;
                othersLabel.querySelector('span').classList.remove('d-none');
            } else {
                othersGroup.style.display = 'none';
                othersField.required = false;
                othersLabel.querySelector('span').classList.add('d-none');
            }
        }

        // Initial check
        toggleOthersField();

        // Add event listener
        externalAgencies.addEventListener('change', toggleOthersField);
    });
</script>

<!-- for Voice Access -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        const docnameInput = document.getElementById('docname');
        const startRecordBtn = document.getElementById('start-record-btn');

        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'en-US';

        startRecordBtn.addEventListener('click', function() {
            recognition.start();
        });

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            docnameInput.value += transcript;
        };

        recognition.onerror = function(event) {
            console.error(event.error);
        };
    });
</script>
<script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize speech recognition
        const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'en-US';

        // Function to start speech recognition and append result to the target element
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

        // Event delegation for all mic buttons
        document.addEventListener('click', function(event) {
            if (event.target.closest('.mic-btn')) {
                const button = event.target.closest('.mic-btn');
                const inputField = button.previousElementSibling;
                if (inputField && inputField.classList.contains('mic-input')) {
                    startRecognition(inputField);
                }
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize speech recognition
        const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'en-US';

        // Function to start speech recognition and append result to the target element
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

        // Event delegation for all mic buttons
        document.addEventListener('click', function(event) {
            if (event.target.closest('.mic-btn')) {
                const button = event.target.closest('.mic-btn');
                const inputField = button.previousElementSibling;
                if (inputField && inputField.classList.contains('mic-input')) {
                    startRecognition(inputField);
                }
            }
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize speech recognition
        const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'en-US';

        // Function to start speech recognition and append result to the target element
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

        // Event delegation for all mic buttons
        document.addEventListener('click', function(event) {
            if (event.target.closest('.mic-btn')) {
                const button = event.target.closest('.mic-btn');
                const inputField = button.previousElementSibling;
                if (inputField && inputField.classList.contains('mic-input')) {
                    startRecognition(inputField);
                }
            }
        });
    });

    // Show/hide the container based on user selection
    function toggleOthersField(selectedValue) {
        const container = document.getElementById('external_agencies_req');
        if (selectedValue === 'others') {
            container.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
        }
    }
</script>

<style>
    .mic-btn {
        background: none;
        border: none;
        outline: none;
        cursor: pointer;
        position: absolute;
        right: 10px; /* Position the button at the right corner */
        top: 50%; /* Center the button vertically */
        transform: translateY(-50%); /* Adjust for the button's height */
        box-shadow: none; /* Remove shadow */
    }
    .mic-btn i {
        color: black; /* Set the color of the icon */
        box-shadow: none; /* Remove shadow */
    }
    .mic-btn:focus,
    .mic-btn:hover,
    .mic-btn:active {
        box-shadow: none; /* Remove shadow on hover/focus/active */
    }

    .relative-container {
        position: relative;
    }

    .relative-container textarea {
        width: 100%;
        padding-right: 40px; /* Ensure the text does not overlap the button */
    }
</style>

    <style>
    #start-record-btn {
        background: none;
        border: none;
        outline: none;
        cursor: pointer;
    }
    #start-record-btn i {
        color: black; /* Set the color of the icon */
        box-shadow: none; /* Remove shadow */
    }
    #start-record-btn:focus,
    #start-record-btn:hover,
    #start-record-btn:active {
        box-shadow: none; /* Remove shadow on hover/focus/active */
    }
</style>


<style>
    .mic-btn {
        background: none;
        border: none;
        outline: none;
        cursor: pointer;
        position: absolute;
        right: 10px; /* Position the button at the right corner */
        top: 50%; /* Center the button vertically */
        transform: translateY(-50%); /* Adjust for the button's height */
        box-shadow: none; /* Remove shadow */
    }
    .mic-btn i {
        color: black; /* Set the color of the icon */
        box-shadow: none; /* Remove shadow */
    }
    .mic-btn:focus,
    .mic-btn:hover,
    .mic-btn:active {
        box-shadow: none; /* Remove shadow on hover/focus/active */
    }

    .relative-container {
        position: relative;
    }

    .relative-container textarea {
        width: 100%;
        padding-right: 40px; /* Ensure the text does not overlap the button */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize speech recognition
        const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'en-US';

        // Function to start speech recognition and append result to the target element
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

        // Event delegation for all mic buttons
        document.addEventListener('click', function(event) {
            const button = event.target.closest('.mic-btn');
            if (button) {
                const inputField = button.previousElementSibling;
                if (inputField && inputField.classList.contains('mic-input')) {
                    startRecognition(inputField);
                }
                return;
            }
        });

        // Show/hide mic button on focus/blur of input fields
        const micInputs = document.querySelectorAll('.mic-input');
        micInputs.forEach(input => {
            input.addEventListener('focus', function() {
                const micBtn = this.nextElementSibling;
                if (micBtn && micBtn.classList.contains('mic-btn')) {
                    micBtn.style.display = 'block';
                }
            });
            input.addEventListener('blur', function(event) {
                const micBtn = this.nextElementSibling;
                if (micBtn && micBtn.classList.contains('mic-btn')) {
                    // Use a timeout to prevent immediate hiding when the button is clicked
                    setTimeout(() => {
                        if (!event.relatedTarget || !event.relatedTarget.classList.contains('mic-btn')) {
                            micBtn.style.display = 'none';
                        }
                    }, 200);
                }
            });
        });
    });

    // Show/hide the container based on user selection
    function toggleOthersField(selectedValue) {
        const container = document.getElementById('external_agencies_req');
        if (selectedValue === 'others') {
            container.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
        }
    }
</script>

<script>
$(document).ready(function(){
    let audio = null;
    let selectedLanguage = 'en-us'; // Default language
    let inputText = '';

    // When the user clicks the button, open the mini modal 
    $(document).on('click', '.speak-btn', function() {
        let inputField = $(this).siblings('textarea, input');
        inputText = inputField.val();
        let modal = $(this).siblings('.mini-modal');
        if (inputText) {
            // Store the input field element
            $(modal).data('inputField', inputField);
            modal.css({
                display: 'block',
                top: $(this).position().top - modal.outerHeight() - 10,
                left: $(this).position().left + $(this).outerWidth() - modal.outerWidth()
            });
        }
    });

    // When the user clicks on <span> (x), close the mini modal
    $(document).on('click', '.close', function() {
        $(this).closest('.mini-modal').css('display', 'none');
    });

    // When the user selects a language and clicks the button
    $(document).on('click', '#select-language-btn', function(event) {
        event.preventDefault(); // Prevent form submission
        let modal = $(this).closest('.mini-modal');
        selectedLanguage = modal.find('#language-select').val();
        let inputField = modal.data('inputField');
        let textToSpeak = inputText;

        if (textToSpeak) {
            if (audio) {
                audio.pause();
                audio.currentTime = 0;
            }

            // Translate the text before converting to speech
            translateText(textToSpeak, selectedLanguage.split('-')[0]).then(translatedText => {
                const apiKey = '2273705f1f6f434194956a200a586470';
                const url = `https://api.voicerss.org/?key=${apiKey}&hl=${selectedLanguage}&src=${encodeURIComponent(translatedText)}&r=0&c=WAV&f=44khz_16bit_stereo`;
                audio = new Audio(url);
                audio.play();
                audio.onended = function() {
                    audio = null;
                };
            });

        }

        modal.css('display', 'none');
    });

    // Speech-to-Text functionality
    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
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

    $(document).on('click', '.mic-btn', function() {
        const inputField = $(this).siblings('textarea, input');
        startRecognition(inputField[0]);
    });

    // Show mic button on hover
    $('.relative-container').hover(
        function() {
            $(this).find('.mic-btn').show();
        }, 
        function() {
            $(this).find('.mic-btn').hide();
        }
    );

    // Function to translate text using RapidAPI
    async function translateText(text, targetLanguage) {
        const url = 'https://text-translator2.p.rapidapi.com/translate';
        const data = new FormData();
        data.append('source_language', 'en');
        data.append('target_language', targetLanguage);
        data.append('text', text);

        const options = {
            method: 'POST',
            headers: {
                'x-rapidapi-key': '5246c9098fmshc966ee7f6cea588p14a110jsn3979434fe858',
                'x-rapidapi-host': 'text-translator2.p.rapidapi.com'
            },
            body: data
        };

        const response = await fetch(url, options);
        const result = await response.json();
        return result.data.translatedText;
    }

    // Update remaining characters
    $('#docname').on('input', function() {
        const remaining = 255 - $(this).val().length;
        $('#rchars').text(remaining);
    });

    // Initialize remaining characters count
    const remaining = 255 - $('#docname').val().length;
    $('#rchars').text(remaining);
});

</script>




<!-- Ensure this CSS is present to initially hide the Others field and its group -->
<style>
    #others_group {
        display: none;
    }
</style>

<style>
        .group-input {
            margin-bottom: 20px;
        }
        .mic-btn, .speak-btn {
            background: none;
            border: none;
            outline: none;
            cursor: pointer;
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            box-shadow: none;
        }
        .mic-btn i, .speak-btn i {
            color: black;
        }
        .mic-btn:focus,
        .mic-btn:hover,
        .mic-btn:active,
        .speak-btn:focus,
        .speak-btn:hover,
        .speak-btn:active {
            box-shadow: none;
        }
        .relative-container {
            position: relative;
        }
        .relative-container input {
            width: 100%;
            padding-right: 40px;
        }
    </style>

    <style>
    #start-record-btn {
        background: none;
        border: none;
        outline: none;
        cursor: pointer;
    }
    #start-record-btn i {
        color: black; /* Set the color of the icon */
        box-shadow: none; /* Remove shadow */
    }
    #start-record-btn:focus,
    #start-record-btn:hover,
    #start-record-btn:active {
        box-shadow: none; /* Remove shadow on hover/focus/active */
    }
</style>
<style>
    .mic-btn {
        background: none;
        border: none;
        outline: none;
        cursor: pointer;
        position: absolute;
        right: 10px; /* Position the button at the right corner */
        top: 50%; /* Center the button vertically */
        transform: translateY(-50%); /* Adjust for the button's height */
        box-shadow: none;
         /* Remove shadow */
    }
    .mic-btn {
            right: 50px; /* Adjust position to avoid overlap with speaker button */
        }

        .speak-btn {
            right: 16px;
        }
    .mic-btn i {
        color: black; /* Set the color of the icon */
        // box-shadow: none; /* Remove shadow */
    }
    .mic-btn:focus,
    .mic-btn:hover,
    .mic-btn:active {
        box-shadow: none; /* Remove shadow on hover/focus/active */
        // display: none;
    }

    .relative-container {
        position: relative;
    }

    .relative-container textarea {
        width: 100%;
        padding-right: 40px; /* Ensure the text does not overlap the button */
    }
</style>

<script>
        function handleDateInput(inputElement, displayElementId) {
            var displayElement = document.getElementById(displayElementId);
            var dateValue = new Date(inputElement.value);
            displayElement.value = dateValue.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
        }

        function updateEndDateMinAudit() {
            var startDate = document.getElementById('audit_start_date_checkdate').value;
            var endDateInput = document.getElementById('audit_end_date_checkdate');
            endDateInput.min = startDate;
        }

        document.addEventListener("DOMContentLoaded", function() {
            updateEndDateMinAudit(); // Initialize the end date min on page load

        document.getElementById('audit_start_date_checkdate').addEventListener('input', function() {
            updateEndDateMin();
        });
    });
    </script>


    <script>
    function handleDateInput(inputElement, displayElementId) {
        var displayElement = document.getElementById(displayElementId);
        var dateValue = new Date(inputElement.value);
        displayElement.value = dateValue.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }

    function updateEndDateMin() {
        var startDate = document.getElementById('start_date_checkdate').value;
        var endDateInput = document.getElementById('end_date_checkdate');
        if (startDate) {
            endDateInput.setAttribute('min', startDate);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        updateEndDateMin(); // Initialize the end date min on page load

        // Reapply the min attribute whenever the start date is changed
        document.getElementById('start_date_checkdate').addEventListener('input', function() {
            updateEndDateMin();
        });
    });
</script>



        @endsection
