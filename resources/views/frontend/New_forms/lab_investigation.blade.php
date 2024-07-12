@extends('frontend.layout.main')
@section('container')
<style>
    textarea.note-codable {
        display: none !important;
    }

    header {
        display: none;
    }
</style>

<div class="form-field-head">
    {{-- <div class="pr-id">
            New Child
        </div> --}}
    <div class="division-bar">
        <strong>Site Division/Project</strong> :
        / Lab Investigation
    </div>
</div>




<script>
    $(document).ready(function() {
        $('#root_couse').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="root_couse_category[]"></td>' +
                    '<td><input type="text" name="root_couse_sub_category[]"></td>' +
                    '<td><input type="text" name="probability[]"></td>' +
                    '<td><input type="text" name="Comment[]"></td>' +
                    '<td><input type="text" name="Remarks[]"></td>' +
                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                //     '</tr>';

                return html;
            }

            var tableBody = $('#root_couse-first-table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>




{{-- ! ========================================= --}}
{{-- !               DATA FIELDS                 --}}
{{-- ! ========================================= --}}
<div id="change-control-fields">
    <div class="container-fluid">

        <!-- Tab links -->
        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Lab Investigation</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Risk Assessment</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Investigation & Root Couse </button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Signature</button>

        </div>

        <form action="{{ route('actionItem.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div id="step-form">
                @if (!empty($parent_id))
                <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                @endif
                <!-- Tab content -->
                <div id="CCForm1" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="sub-head">
                            General Information
                        </div> <!-- RECORD NUMBER -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="originator">Originator</label>
                                    <input disabled type="text" name="originator_id" value="" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input ">
                                    <label for="Date Due"><b>Date Opened</b></label>
                                    <input disabled type="text" value="" name="intiation_date">
                                    <input type="hidden" value="" name="intiation_date">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Initiator Group Code">Short Description</label>
                                    <input type="text" name="short_description" id="initiator_group_code" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="If Others">Assigned To</label>
                                    <select name="assigned_to" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Due Date">Date Due</label>

                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                                    </div>


                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="If Others">Trainer</label>
                                    <select name="trainer" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Due Date">Expiration Date</label>

                                    <div class="calenderauditee">
                                        <input type="text" id="expiration_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="expiration_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Type">Type</label>
                                    <select name="type" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Priority Level">Priority Level</label>
                                    <select name="priority_level" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="External Tests">External Tests</label>
                                    <select name="external_tests" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Test Lab">Test Lab</label>
                                    <select name="test_lab" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Original Test Results">Original Test Results</label>
                                    <select name="original_test_result" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Limits / Specifications">Limits / Specifications</label>
                                    <select name="limit_specifications" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Additional Investigators">Additional Investigators</label>
                                    <select multiple id="additional_investigators" name="" id="">
                                        <option value="">--Select---</option>

                                        <option value="">

                                        </option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Department(s)">Department(s)</label>
                                    <select multiple id="departments" name="" id="">
                                        <option value="">--Select---</option>

                                        <option value="">

                                        </option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Description">Description</label>
                                    <textarea class="" name="Description" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Comments">Comments</label>
                                    <textarea class="" name="Comments" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Country">Country</label>
                                    <select name="country" class="countries" id="country">
                                        <option value="">Select Country</option>

                                    </select>
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Attached Test">Attached Test</label>
                                    <div>
                                        <small class="text-primary">
                                            Please Attach all relevant or supporting documents
                                        </small>
                                    </div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id=""></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="" oninput="" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Related URLs">Related URLs</label>
                                    <select name="Related_URLs" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                    Exit </a> </button>
                        </div>
                    </div>
                   
                </div>
                <div id="CCForm2" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="sub-head col-12">Risk assessment</div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Severity Rate">Severity Rate</label>
                                    <select name="severity_rate" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Occurrence">Occurrence</label>
                                    <select name="occurrence" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Detection">Detection</label>
                                    <select name="detection" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RPN">RPN</label>
                                    <select name="rpn" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="group-input">
                                    <label for="Risk Analysis">Risk Analysis</label>
                                    <textarea class="" name="risk_analysis" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="sub-head">Geogrephic Information</div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Zone">Zone</label>
                                    <select name="zone" id="zone">
                                        <option value="">Enter Your Selection Here</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Country">Country</label>
                                    <select name="country" class="countries" id="country">
                                        <option value="">Select Country</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="City">City</label>
                                    <select name="city" class="cities" id="city">
                                        <option value="">Select City</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="State/District">State/District</label>
                                    <select name="state" class="states" id="stateId">
                                        <option value="">Select State</option>
                                    </select>
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
                </div>

                <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="sub-head">
                            Investigation & Root Couse
                        </div>
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Root Couse Methodology">Root Couse Methodology</label>
                                    <select name="root_couse_methodology" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="root_cause">
                                        Root Couse(0)
                                        <button type="button" onclick="" id="root_couse">+</button>
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                (Launch Instruction)
                            </span>
                                    </label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="root_couse-first-table">
                                            <thead>
                                                <tr>
                                                    <th>Row #</th>
                                                    <th>Root Couse Category</th>
                                                    <th>Root Couse Sub Category</th>
                                                    <th>Probability</th>
                                                    <th>Comment</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td><input disabled type="text" name="serial_number[]" value="1">
                                                </td>
                                                <td><input type="text" name="root_couse_category[]"></td>
                                                <td><input type="text" name="root_couse_sub_category[]"></td>
                                                <td><input type="text" name="probability[]"></td>
                                                <td><input type="text" name="Comment[]"></td>
                                                <td><input type="text" name="Remarks[]"></td>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="group-input">
                                    <label for="Root Couse Description">Root Couse Description</label>
                                    <textarea class="" name="root_couse_description" id="">
                                    </textarea>
                                </div>
                            </div>


                        </div>
                        <div class="button-block">
                          
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                                </a> </button>
                        </div>
                    </div>
                </div>

                <div id="CCForm4" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="sub-head">
                            Activity Log
                        </div>
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Completed By">Completed By</label>
                                   
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Completedon">Completed on </label>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                        <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            
                            <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                                </a> </button>
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
        ele: '#reference_record, #notify_to'
    });

    $('#summernote').summernote({
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear', 'italic']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    $('.summernote').summernote({
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear', 'italic']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    let referenceCount = 1;

    function addReference() {
        referenceCount++;
        let newReference = document.createElement('div');
        newReference.classList.add('row', 'reference-data-' + referenceCount);
        newReference.innerHTML = `
            <div class="col-lg-6">
                <input type="text" name="reference-text">
            </div>
            <div class="col-lg-6">
                <input type="file" name="references" class="myclassname">
            </div><div class="col-lg-6">
                <input type="file" name="references" class="myclassname">
            </div>
        `;
        let referenceContainer = document.querySelector('.reference-data');
        referenceContainer.parentNode.insertBefore(newReference, referenceContainer.nextSibling);
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