@extends('frontend.layout.main')
@section('container')
    {{-- @php dd(session()->get('division'));  @endphp --}}
    <style>
        textarea.note-codable {
            display: none !important;
        }

        header {
            display: none;
        }
    </style>

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

    {{-- voice Command --}}

    <style>
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
    </style>

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

    <div class="form-field-head">

        <div class="division-bar">
            <strong>Site Division/Project</strong> : {{ Helpers::getDivisionName($divisionId) }} /Observation
        </div>
    </div>

    @php
        $users = DB::table('users')->get();
    @endphp
    {{-- ======================================
                    DATA FIELDS
    ======================================= --}}
    <div id="change-control-fields">
        <div class="container-fluid">

            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Observation</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">CAPA Plan</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Impact Analysis</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Summary</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Signatures</button>
            </div>

            <form action="{{ route('observationstore') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="step-form">
                    @if (!empty($parent_id))
                        <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                        <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                    @endif
                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="sub-head">General Information</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number"><b>Record Number</b></label>
                                        <input disabled type="text" name="record_number"
                                            value="{{ Helpers::getDivisionName($divisionId) }}/OBS/{{ date('Y') }}/{{ $record_number }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code"><b>Site/Location Code</b></label>
                                        <input readonly type="text" name="division_id"
                                            value="{{ Helpers::getDivisionName($divisionId) }}">
                                        <input type="hidden" name="division_id" value="{{ $divisionId }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="originator">Initiator</label>
                                        <input disabled type="text" value="{{ Auth::user()->name }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="date_opened">Date of Initiation</label>
                                        <input disabled type="text" value="{{ date('d-M-Y') }}" name="intiation_date">
                                        <input type="hidden" value="{{ date('d-m-Y') }}" name="intiation_date">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="assign_to1">Assigned To</label>
                                        <select name="assign_to">
                                            <option value="">-- Select --</option>
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="date_due">Date Due</label>
                                        <input  type="hidden" value="{{ $due_date }}" name="due_date">
                                        <input disabled type="text" value="{{ Helpers::getdateFormat($due_date) }}" >
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date ">
                                        <label for="date_due">Due Date<span class="text-danger"></span></label>
                                        <div><small class="text-primary">If revising Due Date, kindly mention revision reason in "Due Date Extension Justification" data field.
                                        </small>
                                        </div>
                                        <div class="calenderauditee">
                                            <input type="text" name="due_date" id="due_date" readonly
                                                placeholder="DD-MMM-YYYY" />
                                            <input type="date"  class="hide-input"
                                                oninput="handleDateInput(this, 'due_date')" />
                                        </div>
                                    </div>
                                </div> --}}
                                @php
                                    $initiationDate = date('Y-m-d');
                                    $dueDate = date('Y-m-d', strtotime($initiationDate . '+30 days'));
                                @endphp

                                <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="due-date">Date Due</label>
                                        <div><small class="text-primary">Please mention expected date of completion</small>
                                        </div>
                                        <div class="calenderauditee">
                                            <div class="calenderauditee">
                                                <input type="text" id="due_date" name="due_date" readonly
                                                    placeholder="DD-MM-YYYY" />
                                                <input type="date" readonly name="due_date_n"
                                                    min="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" class="hide-input"
                                                    oninput="handleDateInput(this, 'due_date')" />
                                            </div>
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

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description">Short Description<span
                                                class="text-danger">*</span></label><span id="rchars">255</span>
                                        characters remaining
                                        <div style="position: relative;">
                                            <input class="mic-input" id="docname" type="text" name="short_description"
                                                maxlength="255" required>
                                            <button class="mic-btn" type="button">
                                                <i class="fas fa-microphone"></i>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description"><b>Short Description <span
                                            class="text-danger">*</span></b></label>
                                        <textarea name="short_description" required></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="sub-head">Observation Details</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="grading">Grading</label>
                                        <select name="grading">
                                            <option value="">-- Select --</option>
                                            <option value="1">Recommendation</option>
                                            <option value="2">Major</option>
                                            <option value="3">Minor</option>
                                            <option value="4">Critical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="category_observation">Category Observation</label>
                                        <select name="category_observation">
                                            <option value="">-- Select --</option>
                                            <option title="Case Report Form (CRF)" value="1">
                                                Case Report Form (CRF)
                                            </option>
                                            <option title="Clinical Database" value="2">
                                                Clinical Database
                                            </option>
                                            <option title="Clinical Trial Protocol" value="3">
                                                Clinical Trial Protocol
                                            </option>
                                            <option title="Clinical Trial Report" value="4">
                                                Clinical Trial Report
                                            </option>
                                            <option title="Compliance" value="5" >
                                                Compliance
                                            </option>
                                            <option title="Computerized systems" value="6">
                                                Computerized systems
                                            </option>
                                            <option title="Conduct of Study" value="7">
                                                Conduct of Study
                                            </option>
                                            <option title="Data Accuracy / SDV" value="8">
                                                Data Accuracy / SDV
                                            </option>
                                            <option title="Documentation" value="9">
                                                Documentation
                                            </option>
                                            <option title="Essential Documents (TMF/ISF)" value="10">
                                                Essential Documents (TMF/ISF)
                                            </option>
                                            <option title="Ethics Committee (IEC / IRB)" value="11">
                                                Ethics Committee (IEC / IRB)
                                            </option>
                                            <option title="Facilities / Equipment" value="12">
                                                Facilities / Equipment
                                            </option>
                                            <option title="Miscellaneous" value="13">
                                                Miscellaneous
                                            </option>
                                            <option title="Monitoring" value="14">
                                                Monitoring
                                            </option>
                                            <option title="Organization and Responsibilities" value="16">
                                                Organization and Responsibilities
                                            </option>
                                            <option title="Periodic Safety Reporting" value="17">
                                                Periodic Safety Reporting
                                            </option>
                                            <option title="Protocol Compliance" value="18">
                                                Protocol Compliance
                                            </option>
                                            <option title="Qualification and Training of Staff" value="19">
                                                Qualification and Training of Staff
                                            </option>
                                            <option title="Quality Management System" value="20">
                                                Quality Management System
                                            </option>
                                            <option title="Regulatory Requirements" value="25">
                                                Regulatory Requirements
                                            </option>
                                            <option title="Reliability of Data" value="26">
                                                Reliability of Data
                                            </option>
                                            <option title="Safety Reporting" value="27">
                                                Safety Reporting
                                            </option>
                                            <option title="Source Documents" value="28">
                                                Source Documents
                                            </option>
                                            <option title="Subject Diary(ies)" value="29">
                                                Subject Diary(ies)
                                            </option>
                                            <option title="Informed Consent Form" value="30">
                                                Informed Consent Form
                                            </option>
                                            <option title="Subject Questionnaire(s)" value="31">
                                                Subject Questionnaire(s)
                                            </option>
                                            <option title="Supporting Procedures" value="32">
                                                Supporting Procedures
                                            </option>
                                            <option title="Test Article and Accountability" value="33">
                                                Test Article and Accountability
                                            </option>
                                            <option title="Trial Master File (TMF)" value="34">
                                                Trial Master File (TMF)
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="reference_guideline">Referenced Guideline</label>
                                        <input type="text" name="reference_guideline">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="desc">Description</label>
                                        <textarea name="description"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="sub-head">Further Information</div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="attach_files1">Attached Files</label>
                                        <input type="file" name="attach_files1" />
                                    </div>
                                </div> --}}
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="attach_files1">Attached Files</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="attach_files1"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="attach_files1[]"
                                                    oninput="addMultipleFiles(this, 'attach_files1')" multiple>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="capa_date_due">Recomendation Date Due for CAPA</label>
                                        <input type="date" name="recomendation_capa_date_due" />
                                    </div>
                                </div> --}}
                                <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date ">
                                        <label for="capa_date_due">Recomendation Due Date for CAPA</label>
                                        <div class="calenderauditee">
                                            <input type="text" name="recomendation_capa_date_due"
                                                id="recomendation_capa_date_due" readonly placeholder="DD-MM-YYYY" />
                                            <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                class="hide-input"
                                                oninput="handleDateInput(this, 'recomendation_capa_date_due')" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="non_compliance">Non Compliance</label>
                                        <div style="position: relative;">
                                            <textarea class="mic-input" name="non_compliance"></textarea>
                                            <button class="mic-btn" type="button">
                                                <i class="fas fa-microphone"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="recommend_action">Recommended Action</label>
                                        <div style="position: relative;">
                                            <textarea class="mic-input" name="recommend_action"></textarea>
                                            <button class="mic-btn" type="button">
                                                <i class="fas fa-microphone"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="related_observations">`</label>
                                        <input type="file" name="related_observations" />
                                    </div>
                                </div> --}}
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="related_observations">Related Obsevations</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="related_observations"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="related_observations[]"
                                                oninput="addMultipleFiles(this, 'related_observations')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                                <button type="button" id="ChangeNextButton" class="nextButton">Next</button>
                                <button type="button"> <a class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <div id="CCForm2" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="sub-head">CAPA Plan Details</div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="date_Response_due">Date Response Due</label>
                                        <input type="date" name="date_Response_due2" />
                                    </div>
                                </div> --}}
                                <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date ">
                                        <label for="date_Response_due1">Date Response Due</label>
                                        <div class="calenderauditee">
                                            <input type="text" name="date_Response_due2" id="date_Response_due"
                                                readonly placeholder="DD-MM-YYYY" />
                                            <input type="date" name="date_Response_due_2"
                                                id="date_Response_due_checkdate" class="hide-input"
                                                oninput="handleDateInput(this, 'date_Response_due');checkDate('date_Response_due_checkdate','date_due_checkdate')" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date ">
                                        <label for="date_due"> Due Date</label>
                                        <div class="calenderauditee">
                                            <input type="text" name="capa_date_due" id="date_due" readonly
                                                placeholder="DD-MM-YYYY" />
                                            <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                id="date_due_checkdate" class="hide-input"
                                                oninput="handleDateInput(this, 'date_due');checkDate('date_Response_due_checkdate','date_due_checkdate')" />
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="date_due">Date Due</label>
                                        <input type="date" name="capa_date_due">
                                    </div>
                                </div> --}}
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="assign_to2">Assigned To</label>
                                        <select name="assign_to2">
                                            <option value="">-- Select --</option>
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="cro_vendor">CRO/Vendor</label>
                                        <select name="cro_vendor">
                                            <option value="">-- Select --</option>
                                            <option title="Amit Guru" value="1">
                                                Amit Guru
                                            </option>
                                            <option title="Shaleen Mishra" value="2">
                                                Shaleen Mishra
                                            </option>
                                            <option title="Vikas Prajapati" value="3">
                                                Vikas Prajapati
                                            </option>
                                            <option title="Anshul Patel" value="4">
                                                Anshul Patel
                                            </option>
                                            <option title="Amit Patel" value="5">
                                                Amit Patel
                                            </option>
                                            <option title="Madhulika Mishra" value="6">
                                                Madhulika Mishra
                                            </option>
                                            <option title="Jim Kim" value="7">
                                                Jim Kim
                                            </option>
                                            <option title="Akash Asthana" value="8">
                                                Akash Asthana
                                            </option>
                                            <option title="Not Applicable" value="9">
                                                Not Applicable
                                            </option>
                                            {{-- @foreach ($users as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach --}}
                                {{-- </select> --}}
                                {{-- </div> --}}
                                {{-- </div> --}}
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="action-plan-grid">
                                            Action Plan<button type="button" name="action-plan-grid"
                                                id="observation_table">+</button>
                                        </label>
                                        <table class="table table-bordered" id="observation">
                                            <thead>
                                                <tr>
                                                    <th style="width: 4%">Row#</th>
                                                    <th>Remarks</th>
                                                    <th>Responsible</th>
                                                    <th>Deadline</th>
                                                    <th>Item Status</th>
                                                    <th>Action</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td><input disabled type="text" name="serial_number[]" value="1">
                                                </td>
                                                <td><input type="text" name="action[]"></td>
                                                {{-- <td><input type="text" name="responsible[]"></td> --}}
                                                <td> <select id="select-state" placeholder="Select..."
                                                        name="responsible[]">
                                                        <option value="">Select a value</option>
                                                        @foreach ($users as $data)
                                                            <option value="{{ $data->id }}">{{ $data->name }}
                                                            </option>
                                                        @endforeach
                                                    </select></td>
                                                {{-- <td><input type="text" name="deadline[]"></td> --}}
                                                <td>
                                                    <div class="group-input new-date-data-field mb-0">
                                                        <div class="input-date ">
                                                            <div class="calenderauditee">
                                                                <input type="text" id="deadline' + serialNumber +'"
                                                                    readonly placeholder="DD-MM-YYYY" />
                                                                <input type="date" name="deadline[]"
                                                                    class="hide-input"
                                                                    oninput="handleDateInput(this, `deadline' + serialNumber +'`)" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><input type="text" name="item_status[]"></td>
                                                <td><button type="text" class="removeBtnMI">Remove</button>
                                                </td>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="comments">Comments</label>
                                        <div style="position: relative;">
                                            <textarea class="mic-input" name="comments"></textarea>
                                            <button class="mic-btn" type="button">
                                                <i class="fas fa-microphone"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <div id="CCForm3" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="sub-head">Impact Analysis</div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="impact">Impact</label>
                                        <select name="impact">
                                            <option value="">-- Select --</option>
                                            <option value="High">High</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Low">Low</option>
                                            <option value="None">None</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="impact_analysis">Impact Analysis</label>
                                        <div style="position: relative;">
                                            <textarea class="mic-input" name="impact_analysis"></textarea>
                                            <button class="mic-btn" type="button">
                                                <i class="fas fa-microphone"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="sub-head">Risk Analysis</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Severity Rate">Severity Rate</label>
                                        <select name="severity_rate" id="analysisR"
                                            onchange='calculateRiskAnalysis(this)'>
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="1">Negligible</option>
                                            <option value="2">Moderate</option>
                                            <option value="3">Major</option>
                                            <option value="4">Fatal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Occurrence">Occurrence</label>
                                        <select name="occurrence" id="analysisP" onchange='calculateRiskAnalysis(this)'>
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="5">Extremely Unlikely</option>
                                            <option value="4">Rare</option>
                                            <option value="3">Unlikely</option>
                                            <option value="2">Likely</option>
                                            <option value="1">Very Likely</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Detection">Detection</label>
                                        <select name="detection" id="analysisN" onchange='calculateRiskAnalysis(this)'>
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="5">Impossible</option>
                                            <option value="4">Rare</option>
                                            <option value="3">Unlikely</option>
                                            <option value="2">Likely</option>
                                            <option value="1">Very Likely</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RPN">RPN</label>
                                        <input type="text" name="analysisRPN" id="analysisRPN" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <div id="CCForm4" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="sub-head">Action Summary</div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="actual_start_date">Actual Start Date</label>
                                        <input type="date" name="actual_start_date">
                                    </div>
                                </div> --}}
                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="actual_start_date">Actual Start Date</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="actual_start_date" readonly
                                                placeholder="DD-MM-YYYY" />
                                            <input type="date" id="actual_start_date_checkdate"
                                                name="actual_start_date" class="hide-input"
                                                oninput="handleDateInput(this, 'actual_start_date');checkDate('actual_start_date_checkdate','actual_end_date_checkdate')" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6  new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="actual_end_date">Actual End Date</lable>
                                            <div class="calenderauditee">
                                                <input type="text" id="actual_end_date" placeholder="DD-MM-YYYY" />
                                                <input type="date" id="actual_end_date_checkdate"
                                                    name="actual_end_date" class="hide-input"
                                                    oninput="handleDateInput(this, 'actual_end_date');checkDate('actual_start_date_checkdate','actual_end_date_checkdate')" />
                                            </div>


                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="action_taken">Action Taken</label>
                                        <div style="position: relative;">
                                            <textarea class="mic-input" name="action_taken"></textarea>
                                            <button class="mic-btn" type="button">
                                                <i class="fas fa-microphone"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="sub-head">Response Summary</div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="date_response_due">Date Response Due</label>
                                        <input type="date" name="date_response_due1">
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date ">
                                        <label for="date_response_due">Date Response Due</label>
                                        <div class="calenderauditee">
                                            <input type="text" name="date_response_due1" id="date_response_due1" readonly
                                                placeholder="DD-MMM-YYYY" />
                                            <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"  class="hide-input"
                                                oninput="handleDateInput(this, 'date_response_due1')" />
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="response_date">Date of Response</label>
                                        <input type="date" name="response_date">
                                    </div>
                                </div> --}}
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="attach_files">Attached Files</label>
                                        <input type="file" name="attach_files2">
                                    </div>
                                </div> --}}
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="attach_files">Attached Files</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="attach_files2"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="attach_files2[]"
                                                    oninput="addMultipleFiles(this, 'attach_files2')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="related_url">Related URL</label>
                                        <input type="url" name="related_url">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="response_summary">Response Summary</label>
                                        <div style="position: relative;">
                                            <textarea class="mic-input" name="response_summary"></textarea>
                                            <button class="mic-btn" type="button">
                                                <i class="fas fa-microphone"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    {{-- <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Completed_By">Completed By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Completed_On">Completed On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA_Approved_By">QA Approved By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA_Approved_On">QA Approved On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Final_Approval_By">Final Approval By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Final_Approval_On">Final Approval On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="submit">Submit</button>
                                <button type="button"> <a class="text-white" href="{{ url('dashboard') }}"> Exit </a>
                                </button>
                            </div>
                        </div>
                    </div> --}}
                    <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Report_Issued_By"> Report Issued By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Report_Issued_On">Report Issued On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Report_Issued_Comment">Report Issued Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Cancelled_By"> Cancelled By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Cancelled_On">Cancelled On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled_Comment">Cancelled Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Completed_By">Completed By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Completed_On">Completed On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Completed_Comment">Completed Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="QA_Approved_By">QA Approved By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="QA_Approved_On">QA Approved On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA_Approved_Comment">QA Approved Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Reject_CAPA_Plan_By">Reject CAPA Plan By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Reject_CAPA_Plan_On">Reject CAPA Plan On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Reject_CAPA_Plan_Comment">Reject CAPA Plan Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="QA_Approval_Without_CAPA_By">QA Approval Without CAPA
                                            By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="QA_Approval_Without_CAPA_On">QA Approval Without CAPA
                                            On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA_Approval_Without_CAPA_Comment">QA Approval Without CAPA
                                            Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="All_CAPA_Closed_By">All CAPA Closed By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="All_CAPA_Closed_On">All CAPA Closed On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="All_CAPA_Closed_Comment">All CAPA Closed Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Reject_CAPA_Plan_By1">Reject CAPA Plan By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Reject_CAPA_Plan_On1">Reject CAPA Plan On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Reject_CAPA_Plan_Comment1">Reject CAPA Plan Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Final_Approval_By">Final Approval By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="group-input">
                                        <label for="Final_Approval_On">Final Approval On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Final_Approval_Comment">Final Approval Comment</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                {{-- <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 6 ? "disabled" : "" }}>Save</button> --}}
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                {{-- <button type="submit" {{ $data->stage == 0 || $data->stage == 6 ? "disabled" : "" }}>Submit</button> --}}
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                        Exit </a>
                                </button>
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
        VirtualSelect.init({
            ele: '#Facility, #Group, #Audit, #Auditee'
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
        $(document).ready(function() {
            $('#observation_table').click(function(e) {
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

                        '<td><input type="text" name="item_status[]"></td>' +
                        '<td><button type="text" class="removeBtnMI">Remove</button></td>' +
                        '</tr>';



                    return html;
                }

                var tableBody = $('#observation tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
        $(document).on('click', '.removeBtnMI', function() {
            $(this).closest('tr').remove();
        })
    </script>
    <script>
        var maxLength = 255;
        $('#docname').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchars').text(textlen);
        });
    </script>
@endsection
