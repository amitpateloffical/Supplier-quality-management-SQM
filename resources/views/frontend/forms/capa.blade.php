@extends('frontend.layout.main')
@section('container')
@php
        $users = DB::table('users')->get();
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
<style>
    .w-5 {
        width: 5%;
    }
    .w-10 {
        width: 10%;
    }

    .w-20 {
        width: 20%;
    }

    .w-25 {
        width: 25%;
    }

    .w-30 {
        width: 30%;
    }

    .w-40 {
        width: 40%;
    }

    .w-50 {
        width: 50%;
    }

    .w-60 {
        width: 60%;
    }

    .w-70 {
        width: 70%;
    }

    .w-80 {
        width: 80%;
    }

    .w-90 {
        width: 90%;
    }

    .w-100 {
        width: 100%;
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
        /* box-shadow: none; */
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

  .mic-btn {
            right: 50px; /* Adjust position to avoid overlap with speaker button */
        }

        .speak-btn {
            right: 16px;
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
      <!-- <script>
        $(document).ready(function() {
            $('#material').click(function(e) {
                function generateTableRow(serialNumber) {
                    var users = @json($users);
                    console.log(users);
                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial_number[]" value="' + serialNumber +
                        '"></td>' +
                        '<td><input type="text" name="material_name[]"></td>' +
                        '<td><input type="text" name="material_batch_no[]"></td>' +

                        '<td><div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"> <input type="text" id="material_mfg_date' + serialNumber +'" readonly placeholder="DD-MMM-YYYY" /><input type="date" name="material_mfg_date[]" id="material_mfg_date' + serialNumber +'_checkdate"  class="hide-input" oninput="handleDateInput(this, `material_mfg_date' + serialNumber +'`);checkDate(`material_mfg_date1' + serialNumber +'_checkdate`,`material_expiry_date' + serialNumber +'_checkdate`)" /></div></div></div></td>' +


                        '<td><div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"> <input type="text" id="material_expiry_date' + serialNumber +'" readonly placeholder="DD-MMM-YYYY" /><input type="date" name="material_expiry_date[]" id="material_expiry_date'+ serialNumber +'_checkdate" class="hide-input" oninput="handleDateInput(this, `material_expiry_date' + serialNumber +'`);checkDate(`material_mfg_date' + serialNumber +'_checkdate`,`material_expiry_date' + serialNumber +'_checkdate`)" /></div></div></div></td>' +

                        '<td><input type="text" name="material_batch_desposition[]"></td>' +
                        '<td><input type="text" name="material_remark[]"></td>' +
                        '<td><select name="material_batch_status[]">' +
                        '<option value="">Select a value</option>';

                    for (var i = 0; i < users.length; i++) {
                        html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    }

                    html += '</select></td>' +


                        '</tr>';

                    return html;
                }

                var tableBody = $('#material tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script> -->

    <div class="form-field-head">

        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName(session()->get('division')) }} / CAPA
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
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Equipment/Material Info</button>
                {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Project/Study</button> --}}
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">CAPA Details</button>
                {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Additional Information</button> --}}
                {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Group Comments</button> --}}
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">CAPA Closure</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Activity Log</button>
            </div>

            <form id="mainform" action="{{ route('capastore') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="step-form">

                    @if (!empty($parent_id))
                        <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                        <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                    @endif
                    <!-- General information content -->
                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number">Record Number</label>
                                        <input type="text" value="{{ Helpers::getDivisionName(session()->get('division')) }}/CAPA/{{ date('Y') }}/{{ $record_number }}" readonly>                                            
                                        <input type="hidden" id="record_number" name="record_number" value="{{ Helpers::getDivisionName(session()->get('division')) }}/CAPA/{{ date('Y') }}/{{ $record_number }}" > 
                                        {{-- <div class="static">QMS-EMEA/CAPA/{{ date('Y') }}/{{ $record_number }}</div> --}}
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code">Site/Location Code</label>
                                        <input readonly type="text" name="division_code"
                                            value="{{ Helpers::getDivisionName(session()->get('division')) }}">
                                        <input type="hidden" name="division_id" value="{{ session()->get('division') }}">
                                        {{-- <div class="static">QMS-North America</div> --}}
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator">Initiator</label>
                                        {{-- <div class="static">{{ Auth::user()->name }}</div> --}}
                                        <input readonly type="text" value="{{ Auth::user()->name }}">
                                        <input type="hidden" id="initiator_name" name="initiator_name" value="{{ Auth::user()->name }}">    
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Date Due">Date of Initiation</label>
                                        <input disabled type="text" value="{{ date('d-M-Y') }}" name="intiation_date">
                                        <input type="hidden" value="{{ date('Y-m-d') }}" name="intiation_date">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Assigned To <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="assign_to">
                                            <option value="">Select a value</option>
                                            @foreach ($users as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('assign_to')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <!-- <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date ">
                                        <label for="due-date">Due Date<span class="text-danger">*</span></label>
                                        <div><small class="text-primary">If revising Due Date, kindly mention revision reason in "Due Date Extension Justification" data field.</small>
                                        </div>
                                        <div class="calenderauditee">
                                            <input type="text" id="due_date" readonly
                                                placeholder="DD-MMM-YYYY" />
                                            <input type="date" name="due_date" class="hide-input"
                                                oninput="handleDateInput(this, 'due_date')" />
                                        </div>
                                    </div>
                                </div> -->
                                 @php
                                    $initiationDate = date('Y-m-d');
                                    $dueDate = date('Y-m-d', strtotime($initiationDate . '+30 days'));
                                @endphp

                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Date Due"> Due Date</label>
                                         <div><small class="text-primary">If revising Due Date, kindly mention revision reason in "Due Date Extension Justification" data field.</small></div>
                                        <div class="calenderauditee">
                                            <input type="text" name="due_date" id="due_date" readonly
                                                placeholder="DD-MMM-YYYY" />
                                            <input disabled type="date"  disabled name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'due_date')" />
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

                                    // Formatting the date in "dd-MMM-yyyy" format
                                    var dueDateFormatted = `${day}-${monthNames[monthIndex]}-${year}`;

                                    // Set the formatted due date value to the input field
                                    document.getElementById('due_date').value = dueDateFormatted;
                                </script>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group">Initiator Group</label>
                                        <select name="initiator_Group" id="initiator_group">
                                            <option value="">-- Select --</option>
                                            <option value="CQA" @if (old('initiator_Group') == 'CQA') selected @endif>
                                                Corporate Quality Assurance</option>
                                            <option value="QAB" @if (old('initiator_Group') == 'QAB') selected @endif>Quality
                                                Assurance Biopharma</option>
                                            <option value="CQC" @if (old('initiator_Group') == 'CQC') selected @endif>Central
                                                Quality Control</option>
                                            <option value="MANU" @if (old('initiator_Group') == 'MANU') selected @endif>
                                                Manufacturing</option>
                                            <option value="PSG" @if (old('initiator_Group') == 'PSG') selected @endif>Plasma
                                                Sourcing Group</option>
                                            <option value="CS" @if (old('initiator_Group') == 'CS') selected @endif>
                                                Central
                                                Stores</option>
                                            <option value="ITG" @if (old('initiator_Group') == 'ITG') selected @endif>
                                                Information Technology Group</option>
                                            <option value="MM" @if (old('initiator_Group') == 'MM') selected @endif>
                                                Molecular Medicine</option>
                                            <option value="CL" @if (old('initiator_Group') == 'CL') selected @endif>
                                                Central
                                                Laboratory</option>
                                            <option value="TT" @if (old('initiator_Group') == 'TT') selected @endif>Tech
                                                Team</option>
                                            <option value="QA" @if (old('initiator_Group') == 'QA') selected @endif>
                                                Quality Assurance</option>
                                            <option value="QM" @if (old('initiator_Group') == 'QM') selected @endif>
                                                Quality Management</option>
                                            <option value="IA" @if (old('initiator_Group') == 'IA') selected @endif>IT
                                                Administration</option>
                                            <option value="ACC" @if (old('initiator_Group') == 'ACC') selected @endif>
                                                Accounting</option>
                                            <option value="LOG" @if (old('initiator_Group') == 'LOG') selected @endif>
                                                Logistics</option>
                                            <option value="SM" @if (old('initiator_Group') == 'SM') selected @endif>
                                                Senior Management</option>
                                            <option value="BA" @if (old('initiator_Group') == 'BA') selected @endif>
                                                Business Administration</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Initiator Group Code</label>
                                        <input type="text" name="initiator_group_code" id="initiator_group_code"
                                            value="" >
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description">Short Description<span
                                                class="text-danger">*</span></label>
                                        <div><small class="text-primary">Please mention brief summary</small></div>
                                        <textarea name="short_description"></textarea>
                                    </div>
                                </div> --}}
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="short_description">Short Description<span class="text-danger">*</span></label>
                                        <span id="rchars">255</span> characters remaining
                                        <div class="relative-container">
                                            <input id="short_description" type="text" class="mic-input" name="short_description" maxlength="255" required>
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
                                

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="severity-level">Severity Level</label>
                                        <span class="text-primary">Severity levels in a QMS record gauge issue seriousness, guiding priority for corrective actions. Ranging from low to high, they ensure quality standards and mitigate critical risks.</span>
                                        <select name="severity_level_form">
                                            <option value="">-- Select --</option>
                                            <option value="minor">Minor</option>
                                            <option value="major">Major</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group">Initiated Through</label>
                                        <div><small class="text-primary">Please select related information</small></div>
                                        <select name="initiated_through"
                                            onchange="otherController(this.value, 'others', 'initiated_through_req')">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="internal audit">Internal Audit</option>
                                            <option value="external audit">External Audit</option>
                                            <option value="recall">Recall</option>
                                            <option value="return">Return</option>
                                            <option value="deviation">Deviation</option>
                                            <option value="complaint">Complaint</option>
                                            <option value="regulatory">Regulatory</option>
                                            <option value="lab incident">Lab Incident</option>
                                            <option value="improvement">Improvement</option>
                                            <option value="others">Others</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input" id="initiated_through_req">
                                        <label for="initiated_through_req">Others<span class="text-danger d-none">*</span></label>
                                        <div class="relative-container">
                                            <textarea name="initiated_through_req" id="initiated_through_req_textarea" class="mic-input"   ></textarea>
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

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="repeat">Repeat</label>
                                        <div><small class="text-primary">Please select yes if it is has recurred in past
                                                six months</small></div>
                                        <select name="repeat"
                                            onchange="otherController(this.value, 'Yes', 'repeat_nature')">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                            <option value="NA">NA</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input" id="repeat_nature_group">
                                        <label for="repeat_nature">Repeat Nature<span class="text-danger d-none">*</span></label>
                                        <div class="relative-container">
                                            <textarea name="repeat_nature" id="repeat_nature_textarea" class="mic-input" ></textarea>
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
                                    <div class="group-input" id="problem_description_group">
                                        <label for="problem_description">Problem Description</label>
                                        <div class="relative-container">
                                            <textarea name="problem_description" id="problem_description_textarea" class="mic-input" ></textarea>
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

                                                      {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="CAPA Team">CAPA Team</label>
                                        <select multiple id="select-state" placeholder="Select..." name="capa_team[]">
                                            <option value="">Select a value</option>
                                            @foreach ($users as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-6">
                                    <div class="group-input">
                                        <label for="CAPA Team">CAPA Team</label>
                                        <select multiple name="capa_team[]" placeholder="Select CAPA Team"
                                            data-search="false" data-silent-initial-value-set="true" id="Audit">
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="CAPA Related Records">CAPA Related Records</label>
                                        <div class="related-record-block">
                                            <select  multiple id="capa_related_record" name="capa_related_record[]" id="">

                                                @foreach ($old_record as $new)
                                                    <option value="{{ $new->id }}"  >
                                                        {{ Helpers::getDivisionName($new->division_id) }}/CAPA/{{date('Y')}}/{{ Helpers::recordFormat($new->record) }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Reference Records">Reference Records</label>
                                        <select multiple id="capa_related_record" name="capa_related_record[]" id="">
                                             <option value="">--Select---</option>
                                             @foreach ($old_record as $new)
                                                <option value="{{ $new->id }}">
                                                    {{ Helpers::getDivisionName($new->division_id) }}/CAPA/{{ date('Y') }}/{{ Helpers::recordFormat($new->record) }}
                                                </option>
                                             @endforeach
                                        </select>
                                    </div>
                                </div> --}}

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Reference Records">Reference Record</label>
                                        <select multiple id="capa_related_record" name="capa_related_record[]">

                                            @foreach ($old_record as $new)
                                                <option
                                                    value="{{ Helpers::getDivisionName($new->division_id) }}/CAPA/{{ date('Y') }}/{{ Helpers::recordFormat($new->record) }}">
                                                   {{ Helpers::getDivisionName($new->division_id) }}/CAPA/{{ date('Y') }}/{{ Helpers::recordFormat($new->record) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input" id="initial_observation_group">
                                        <label for="initial_observation">Initial Observation</label>
                                        <div class="relative-container">
                                            <textarea name="initial_observation" id="initial_observation_textarea" class="mic-input" ></textarea>
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

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Interim Containnment">Interim Containnment</label>
                                        <select name="interim_containnment"
                                            onchange="otherController(this.value, 'required', 'containment_comments')">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="required">Required</option>
                                            <option value="not-required">Not Required</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input" id="containment_comments_group">
                                        <label for="containment_comments">Containment Comments <span class="text-danger d-none">*</span></label>
                                        <div class="relative-container">
                                            <textarea name="containment_comments" id="containment_comments_textarea" class="mic-input" ></textarea>
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
                                        <label for="CAPA Attachments">CAPA Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        {{-- <input multiple type="file" id="myfile" name="capa_attachment[]"> --}}
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="capa_attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="capa_attachment[]"
                                                    oninput="addMultipleFiles(this, 'capa_attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input" id="capa_qa_comments_group">
                                        <label for="capa_qa_comments">Comments</label>
                                        <div class="relative-container">
                                            <textarea name="capa_qa_comments" id="capa_qa_comments_textarea" class="mic-input" ></textarea>
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
                                <button type="submit" id="ChangesaveButton" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" id="ChangeNextButton" class="nextButton">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>

                            </div>

                        </div>
                    </div>
                   

                    <!-- Product Information content -->
                    <div id="CCForm2" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12 sub-head">
                                    Product Details
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Product Details">
                                            Product Details<button type="button" name="ann" id="product">+</button>
                                        </label>
                                        <table class="table table-bordered" id="product_details">
                                            <thead>
                                                <tr>
                                                    <th class="w-5">Row #</th>
                                                    <th class="w-10">Product Name</th>
                                                    <th class="w-10">Batch No./Lot No./AR No.</th>
                                                    <th class="w-10">Manufacturing Date</th>
                                                    <th class="w-10">Date Of Expiry</th>
                                                    <th class="w-10">Batch Disposition Decision</th>
                                                    <th class="w-10">Remark</th>
                                                    <th class="w-10">Batch Status</th>
                                                    <th class="w-5">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Add your rows here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                    
                                <div class="col-12 sub-head">
                                    Material Details
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Material Details">
                                            Material Details<button type="button" name="ann" id="material">+</button>
                                        </label>
                                        <table class="table table-bordered" id="material_details">
                                            <thead>
                                                <tr>
                                                    <th class="w-5">Row #</th>
                                                    <th class="w-10">Material Name</th>
                                                    <th class="w-10">Batch No./Lot No./AR No.</th>
                                                    <th class="w-10">Manufacturing Date</th>
                                                    <th class="w-10">Date Of Expiry</th>
                                                    <th class="w-10">Batch Disposition Decision</th>
                                                    <th class="w-10">Remark</th>
                                                    <th class="w-10">Batch Status</th>
                                                    <th class="w-5">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Add your rows here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                    
                                <div class="col-12 sub-head">
                                    Equipment/Instruments Details
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Equipment Details">
                                            Equipment/Instruments Details<button type="button" name="ann" id="equipment">+</button>
                                        </label>
                                        <table class="table table-bordered" id="equipment_details">
                                            <thead>
                                                <tr>
                                                    <th class="w-5">Row #</th>
                                                    <th class="w-10">Equipment/Instruments Name</th>
                                                    <th class="w-10">Equipment/Instruments ID</th>
                                                    <th class="w-10">Equipment/Instruments Comments</th>
                                                    <th class="w-5">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                    
                                <div class="col-12 sub-head">
                                    Other type CAPA Details
                                </div>
                                <div class="col-12">
                                    <div class="group-input" id="details_new_group">
                                        <label for="details_new">Details</label>
                                        <div class="relative-container">
                                            <input type="text" name="details_new" id="details_new_input" class="mic-input">
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
                                                        <!-- Add language options here -->
                                                    </select>
                                                    <button id="select-language-btn">Select</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-12">
                                    <div class="group-input" id="capa_qa_comments2_group">
                                        <label for="capa_qa_comments2">CAPA QA Comments</label>
                                        <div class="relative-container">
                                            <textarea name="capa_qa_comments2" id="capa_qa_comments2_textarea" class="mic-input"></textarea>
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
                                                        <!-- Add language options here -->
                                                    </select>
                                                    <button id="select-language-btn">Select</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="button-block">
                                    <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                    <button type="button">
                                        <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">Exit</a>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Project Study content****************************** -->
                  

                    <!-- CAPA Details content ****************************-->
                    <div id="CCForm3" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <!-- CAPA Type Selection -->
                                <div class="col-md-12">
                                    <div class="group-input">
                                        <label for="select-state">CAPA Type<span class="text-danger"></span></label>
                                        <select id="select-state" placeholder="Select..." name="capa_type">
                                            <option value="">Select a value</option>
                                            <option value="Corrective Action">Corrective Action</option>
                                            <option value="Preventive Action">Preventive Action</option>
                                            <option value="Corrective & Preventive Action">Corrective & Preventive Action</option>
                                        </select>
                                        @error('assign_to')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                    
                                <!-- Corrective Action -->
                                <div class="col-12" id="corrective_action_group">
                                    <div class="group-input">
                                        <label for="corrective_action">Corrective Action</label>
                                        <div class="relative-container">
                                            <textarea name="corrective_action" id="corrective_action_textarea" class="mic-input"></textarea>
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
                                                        <!-- Add more languages as needed -->
                                                    </select>
                                                    <button id="select-language-btn">Select</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    
                                <!-- Preventive Action -->
                                <div class="col-12" id="preventive_action_group">
                                    <div class="group-input">
                                        <label for="preventive_action">Preventive Action</label>
                                        <div class="relative-container">
                                            <textarea name="preventive_action" id="preventive_action_textarea" class="mic-input"></textarea>
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
                                                        <!-- Add more languages as needed -->
                                                    </select>
                                                    <button id="select-language-btn">Select</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    
                                <!-- Supervisor Review Comments -->
                                <div class="col-12" id="supervisor_review_comments_group">
                                    <div class="group-input">
                                        <label for="supervisor_review_comments">Supervisor Review Comments</label>
                                        <div class="relative-container">
                                            <textarea name="supervisor_review_comments" id="supervisor_review_comments_textarea" class="mic-input"></textarea>
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
                                <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>
                    
                      {{-- <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="sub-head">
                                CFT Information
                            </div>
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Microbiology">CFT Reviewer</label>
                                        <select name="Microbiology_new">
                                            <option value="0">-- Select --</option>
                                            <option value="yes" selected>Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Microbiology-Person">CFT Reviewer Person</label>
                                        <select  name="Microbiology_Person[]" placeholder="Select CFT Reviewers"
                                            data-search="false" data-silent-initial-value-set="true" id="cft_reviewer">
                                            <option value="0">-- Select --</option>
                                            @foreach ($cft as $data)
                                                <option value="{{ $data->id}}" selected>{{ $data->name}}</option>
                                            @endforeach
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
                                        <select name="goup_review">
                                            <option value="0">-- Select --</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Production">Production</label>
                                        <select name="Production_new">
                                            <option value="0">-- Select --</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Production-Person">Production Person</label>
                                        <select name="Production_Person">
                                            <option value="0">-- Select --</option>
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Quality-Approver">Quality Approver</label>
                                        <select name="Quality_Approver">
                                            <option value="0">-- Select --</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Quality-Approver-Person">Quality Approver Person</label>
                                        <select name="Quality_Approver_Person">
                                            <option value="0">-- Select --</option>
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="bd_domestic">Others</label>
                                        <select name="bd_domestic">
                                            <option value="0">-- Select --</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="bd_domestic-Person">Others Person</label>
                                        <select name="Bd_Person">
                                            <option value="0">-- Select --</option>
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Additional Attachments">Additional Attachments</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="additional_attachments"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="additional_attachments[]"
                                                    oninput="addMultipleFiles(this, 'additional_attachments')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                        Exit </a> </button>

                            </div>
                        </div>
                    </div> --}}
                    
                    <!-- CAPA Closure content -->
                    
                    <div id="CCForm4" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="QA Review & Closure">QA Review & Closure</label>
                                        <textarea name="qa_review"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Closure Attachments">Closure Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        {{-- <input multiple type="file" id="myfile" name="closure_attachment[]"> --}}
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="closure_attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="closure_attachment[]"
                                                    oninput="addMultipleFiles(this, 'closure_attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-12 sub-head">
                                    Effectiveness Check Details
                                </div> -->
                                <!-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="Effectiveness Check Required">Effectiveness Check
                                            Required?</label>
                                        <select name="effect_check" onChange="setCurrentDate(this.value)">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div> -->
                                <!-- <div class="col-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="EffectCheck Creation Date">Effectiveness Check Creation Date</label>
                                        {{-- <input type="date" name="effect_check_date"> --}}
                                        <div class="calenderauditee">
                                            <input type="text" name="effect_check_date" id="effect_check_date" readonly
                                                placeholder="DD-MMM-YYYY" />
                                            <input type="date" name="effect_check_date" class="hide-input"
                                                oninput="handleDateInput(this, 'effect_check_date')" />
                                        </div>
                                    </div>
                                </div> -->
                                <!-- <div class="col-6">
                                    <div class="group-input">
                                        <label for="Effectiveness_checker">Effectiveness Checker</label>
                                        <select id="select-state" placeholder="Select..." name="Effectiveness_checker">
                                            <option value="">Select a person</option>
                                            @foreach ($users as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> -->
                                <!-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="effective_check_plan">Effectiveness Check Plan</label>
                                        <textarea name="effective_check_plan"></textarea>
                                    </div>
                                </div> -->
                                <div class="col-12 sub-head">
                                    Extension Justification
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="due_date_extension">Due Date Extension Justification</label>
                                        <div><small class="text-primary">Please Mention justification if due date is
                                                crossed</small></div>
                                        <textarea name="due_date_extension"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>
                    <!-- Activity Log content -->
                    <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Plan Proposed By">Proposed Plan By</label>
                                        <input type="hidden" name="plan_proposed_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Plan Proposed On">Proposed Plan On</label>
                                        <input type="hidden" name="plan_proposed_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted Comment">Proposed Plan Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="QA More Info Required By">More Info Required By</label>
                                        <input type="hidden" name="more_info_review_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="QA More Info Required On">More Info Required On</label>
                                        <input type="hidden" name="more_info_review_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted Comment">More Info Required Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Plan Approved By">Approved Plan By</label>
                                        <input type="hidden" name="Plan_approved_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Plan Approved On">Approved Plan On</label>
                                        <input type="hidden" name="Plan_approved_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted Comment">Approved Plan Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="QA More Info Required By">QA More Info Required By</label>
                                        <input type="hidden" name="qa_more_info_required_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="QA More Info Required On">QA More Info Required On</label>
                                        <input type="hidden" name="qa_more_info_required_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted Comment">QA More Info Required Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Completed By">Completed By</label>
                                        <input type="hidden" name="completed_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Completed On">Completed On</label>
                                        <input type="hidden" name="completed_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted Comment">Completed Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Approved By">Approved By</label>
                                        <input type="hidden" name="approved_by">

                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Approved On">Approved On</label>
                                        <input type="hidden" name="approved_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted Comment">Approved Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Rejected By">Rejected By</label>
                                        <input type="hidden" name="rejected_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Rejected On">Rejected On</label>
                                        <input type="hidden" name="rejected_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted Comment">Rejected Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Completed By">All Actions Completed By</label>
                                        <input type="hidden" name="all_actions_completed_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Completed On">All Actions Completed On</label>
                                        <input type="hidden" name="all_actions_completed_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted Comment">All Actions Completed Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Cancelled By">Cancelled By</label>
                                        <input type="hidden" name="cancelled_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Cancelled On">Cancelled On</label>
                                        <input type="hidden" name="cancelled_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted Comment">Cancelled Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="button-block">
                                {{-- <button type="submit" class="saveButton">Save</button> --}}
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                {{-- <button type="submit">Submit</button> --}}
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white" href="#"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

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
    $(document).ready(function() {
        
        $('#mainform').on('submit', function(e) {
            $('.on-submit-disable-button').prop('disabled', true);
        });
    })
</script>
    <script>
        VirtualSelect.init({
            ele: '#Facility, #Group, #Audit, #Auditee , #capa_related_record,#cft_reviewer'
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

        function setCurrentDate(item){
            if(item == 'yes'){
                $('#effect_check_date').val('{{ date('d-M-Y')}}');
            }
            else{
                $('#effect_check_date').val('');
            }
        }
    </script>
    <script>
        var maxLength = 255;
        $('#docname').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchars').text(textlen);});
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

    // Show/hide the container based on user selection
    function toggleOthersField(selectedValue) {
        const container = document.getElementById('external_agencies_req');
        if (selectedValue === 'others') {
            container.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
        }
    }
});

$(document).ready(function() {
    let audio = null;
    let selectedLanguage = 'en-us'; // Default language
    const apiKey = '16f141b794484a71b679325faf2d5fc4'; // Use the provided API key

    // When the user clicks the button, open the mini modal
    $(document).on('click', '.speak-btn', function() {
        let inputField = $(this).siblings('textarea, input');
        let textToSpeak = inputField.val();
        let modal = $(this).siblings('.mini-modal');
        if (textToSpeak) {
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
        let textToSpeak = inputField.val();

        if (textToSpeak) {
            if (audio) {
                audio.pause();
                audio.currentTime = 0;
            }

            const url = `https://api.voicerss.org/?key=${apiKey}&hl=${selectedLanguage}&src=${encodeURIComponent(textToSpeak)}&r=0&c=WAV&f=44khz_16bit_stereo`;
            audio = new Audio(url);
            audio.play();
            audio.onended = function() {
                audio = null;
            };
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
});
</script>



@endsection
