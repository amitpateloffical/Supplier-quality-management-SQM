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
        / Training Course
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#test').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="Question[]"></td>' +
                    ' <td><input type="text" name="Answer[]"></td>' +
                    '<td><input type="text" name="Result[]"></td>' +
                    '<td><input type="text" name="Comment[]"></td>' +


                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                //     '</tr>';

                return html;
            }

            var tableBody = $('#test-first-table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#tests').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="Question[]"></td>' +
                    ' <td><input type="text" name="Answer[]"></td>' +
                    '<td><input type="text" name="Result[]"></td>' +
                    '<td><input type="text" name="Comment[]"></td>' +


                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                //     '</tr>';

                return html;
            }

            var tableBody = $('#tests-first-table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#survey').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '   <td><input type="text" name="Subject[]"></td>' +
                    '<td><input type="text" name="Topic[]"></td>' +
                    '<td><input type="text" name="Rating[]"></td>' +
                    '<td><input type="text" name="Comment[]"></td>' +


                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                //     '</tr>';

                return html;
            }

            var tableBody = $('#survey-first-table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#Product_Material').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="ProductName[]"></td>' +
                    '<td><input type="number" name="ReBatchNumber[]"></td>' +
                    '<td><input type="date" name="ExpiryDate[]"></td>' +
                    '<td><input type="date" name="ManufacturedDate[]"></td>' +
                    '<td><input type="text" name="Disposition[]"></td>' +
                    '<td><input type="text" name="Comment[]"></td>' +


                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                //     '</tr>';

                return html;
            }

            var tableBody = $('#Product_Material_details tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#Equipment').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="ProductName[]"></td>' +
                    '<td><input type="number" name="BatchNumber[]"></td>' +
                    '<td><input type="date" name="ExpiryDate[]"></td>' +
                    '<td><input type="date" name="ManufacturedDate[]"></td>' +
                    '<td><input type="number" name="NumberOfItemsNeeded[]"></td>' +
                    '<td><input type="text" name="Exist[]"></td>' +
                    '<td><input type="text" name="Comment[]"></td>' +


                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                //     '</tr>';

                return html;
            }

            var tableBody = $('#Equipment_details tbody');
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
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Training</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Evaluation</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Signature</button>

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
                                        <input type="date" name="expiration_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'expiration_date')" />
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
                                    <label for="Launch CBT">Launch CBT</label>
                                    <select name="launch_CBT" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

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

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="SharePoint Document">SharePoint Document</label>
                                    <input type="text" name="sharePoint_document" id="sharePoint_document" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="LiveLink Documents">LiveLink Document</label>
                                    <select name="liveLink_document" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for=" Document"> Document</label>
                                    <input type="text" name="document" id="" value="">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Attached Documents">Attached Documents</label>
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

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="root_cause">
                                        Agenda(0)
                                        <button type="button" onclick="add4Input('root-cause-first-table')">+</button>
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="root-cause-first-table">
                                            <thead>
                                                <tr>
                                                    <th>Row #</th>
                                                    <th>Date</th>
                                                    <th>Topic</th>
                                                    <th>Responsible</th>
                                                    <th>Time Start</th>
                                                    <th>Time End</th>
                                                    <th>Comment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td><input disabled type="text" name="serial_number[]" value="1">
                                                </td>
                                                <td><input type="text" name="Date[]"></td>
                                                <td><input type="text" name="Topic[]"></td>
                                                <td><input type="text" name="Responsible[]"></td>
                                                <td><input type="text" name="Time_Start[]"></td>
                                                <td><input type="text" name="Time_end[]"></td>
                                                <td><input type="text" name="Comment[]"></td>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="root_cause">
                                        Test
                                        <button type="button" onclick="" id="test">+</button>
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="test-first-table">
                                            <thead>
                                                <tr>
                                                    <th>Row #</th>
                                                    <th>Question</th>
                                                    <th>Answer</th>
                                                    <th>Result</th>
                                                    <th>Comment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td><input disabled type="text" name="serial_number[]" value="1">
                                                </td>
                                                <td><input type="text" name="Question[]"></td>
                                                <td><input type="text" name="Answer[]"></td>
                                                <td><input type="text" name="Result[]"></td>
                                                <td><input type="text" name="Comment[]"></td>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="group-input">
                                    <label for="root_cause">
                                        Survey
                                        <button type="button" onclick="" id="survey">+</button>
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="survey-first-table">
                                            <thead>
                                                <tr>
                                                    <th>Row #</th>
                                                    <th>Subject</th>
                                                    <th>Topic</th>
                                                    <th>Rating</th>
                                                    <th>Comment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td><input disabled type="text" name="serial_number[]" value="1">
                                                </td>
                                                <td><input type="text" name="Subject[]"></td>
                                                <td><input type="text" name="Topic[]"></td>
                                                <td><input type="text" name="Rating[]"></td>
                                                <td><input type="text" name="Comment[]"></td>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Training Requirement">Training Requirement</label>
                                    <select name="training_requirement" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

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


                            <div class="button-block">

                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                                    </a> </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="CCForm2" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="sub-head col-12">Evaluation</div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="root_cause">
                                        Test
                                        <button type="button" onclick="" id="tests">+</button>
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="tests-first-table">
                                            <thead>
                                                <tr>
                                                    <th>Row #</th>
                                                    <th>Question</th>
                                                    <th>Answer</th>
                                                    <th>Result</th>
                                                    <th>Comment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td><input disabled type="text" name="serial_number[]" value="1">
                                                </td>
                                                <td><input type="text" name="Question[]"></td>
                                                <td><input type="text" name="Answer[]"></td>
                                                <td><input type="text" name="Result[]"></td>
                                                <td><input type="text" name="Comment[]"></td>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Passed/Fail">Passed/Fail</label>
                                    <select name="Passed_Fail" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Feedback">Feedback</label>
                                    <textarea class="" name="Feedback" id="">
                                    </textarea>
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
                            Activity Log
                        </div>
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Approved by">Approved by</label>

                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Approved on"> Approved on </label>




                                </div>
                            </div>
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