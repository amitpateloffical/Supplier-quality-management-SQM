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
        /Production Line Audit
    </div>
</div>

{{-- ! ========================================= --}}
{{-- !               DATA FIELDS                 --}}
{{-- ! ========================================= --}}
<div id="change-control-fields">
    <div class="container-fluid">

        <!-- Tab links -->
        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Production Line Audit</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Audit Summary</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Signatures</button>
        </div>

        <form action="{{ route('actionItem.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div id="step-form">
                @if (!empty($parent_id))
                <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                @endif

                <div id="CCForm1" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Initiator</b></label>
                                    <input disabled type="text" name="Initiator" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Date of Initiation"><b>Date of Initiation</b></label>
                                    <input disabled type="date" name="Date_of_Initiation" value="">
                                    <input type="hidden" name="division_id" value="">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Product"><b>Product</b></label>
                                    <input type="text" name="Product" value="">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Short Description">Short Description<span class="text-danger">*</span></label><span id="rchars">255</span>
                                    characters remaining
                                    <input id="docname" type="text" name="short_description" maxlength="255" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="search">
                                        Assigned To <span class="text-danger"></span>
                                    </label>
                                    <select id="select-state" placeholder="Select..." name="assign_to">
                                        <option value="">Select a value</option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="due-date">Date Due</label>
                                    <div><small class="text-primary">Please mention expected date of completion</small></div>
                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="group-input">
                                    <label for="type of product">
                                        Type of Product</label>
                                    <select id="select-state" placeholder="Select..." name="type_of_product">
                                        <option value="">Select a value</option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Agenda (0)
                                    <button type="button" name="audit-agenda-grid" id="agenda">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="agenda-field-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Date</th>
                                                <th style="width: 16%">Topic</th>
                                                <th style="width: 16%">Responsible</th>
                                                <th style="width: 16%">Time Start</th>
                                                <th style="width: 15%">Time End</th>
                                                <th style="width: 15%">Comment</th>
                                                <th style="width: 15%">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>
                                            <td><input type="date" name="IDnumber[]"></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name="Remarks[]"></td>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Priority Level">Priority Level</label>
                                    <select id="select-state" placeholder="Select..." name="Priority_Level">
                                        <option value="">Select a value</option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="CRO Vender"><b>CRO/Vender</b></label>
                                    <input type="text" name="CRO_Vender" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="department"><b>Department(s)</b></label>
                                    <input type="text" name="department" value="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Co-Auditors">Co-Auditors</label>
                                    <select id="select-state" placeholder="Select..." name="CoAuditors">
                                        <option value="">Select a value</option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Description">Description</label>
                                    <textarea name="Description" id="" cols="30" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Scope">Scope</label>
                                    <textarea name="Scope" id="" cols="30" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Scheduled Start Date">Scheduled Start Date</label>
                                    <input type="date" name="Scheduled_Start_Date" id="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Scheduled End Date">Scheduled End Date</label>
                                    <input type="date" name="Scheduled_End_Date" id="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="pm_frequency">Year</label>
                                    <select name="pm_frequency">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Calibration Frequency">Quarter</label>
                                    <select name="Calibration_Frequency">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Zone">Zone</label>
                                    <select name="Zone">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Country">Country</label>
                                    <input type="Country" name="" id="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="City">City</label>
                                    <input type="City" name="" id="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="State/District">State/District</label>
                                    <select name="State_District">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Attached Files">Attached Files</label>
                                    <div>
                                        <small class="text-primary">
                                            Please Attach all relevant or supporting documents
                                        </small>
                                    </div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id=""></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Attached_Files" oninput="" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Related URLs">Related URLs</label>
                                    <select name="related_URLs">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Comments">Comments</label>
                                    <textarea name="Comments" id="" cols="30" rows="3"></textarea>
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
                            <div class="sub-head">
                                Audit Summary
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Actual_Start_Date">Actual Start Date</label>
                                    <input type="date" name="Actual_End_Date" id="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Actual_End_Date">Actual End Date</label>
                                    <input type="date" name="Actual_End_Date" id="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Audit Result">Audit Result</label>
                                    <select id="" placeholder="Select..." name="Audit_Result">
                                        <option value="">Select a value</option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Executive Summary">Executive Summary</label>
                                    <textarea name="Executive_Summary" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="sub-head">
                                Response Summary
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Date Response Due">Date Response Due</label>
                                    <input type="date" name="Date_Response_due" id="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Date Response">Date of Response</label>
                                    <input type="date" name="Date_Response" id="">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Attached Files">Attached Files</label>
                                    <div>
                                        <small class="text-primary">
                                            Please Attach all relevant or supporting documents
                                        </small>
                                    </div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id=""></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Attached_Files" oninput="" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Audit Result">Related URLs</label>
                                    <select id="" placeholder="Select..." name="Related_URLs">
                                        <option value="">Select a value</option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Response Summary">Response Summary</label>
                                    <textarea name="Response_Summary" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="button-block">
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                                </a> </button>
                        </div>
                    </div>
                </div>

                <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Scheduled by">Scheduled By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Scheduled on">Scheduled On</label>
                                    <div class="Date"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Issued by">Issued By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Issued on">Issued On</label>
                                    <div class="Date"></div>
                                </div>
                            </div>
                        </div>

                        <div class="button-block">
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                                </a> </button>
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
    $(document).ready(function() {
        $('#agenda').click(function(e) {
            function generateTableRow(serialNumber) {

                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="date" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '</tr>';

                return html;
            }

            var tableBody = $('#agenda-field-table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>
<script>
    var maxLength = 255;
    $('#docname').keyup(function() {
        var textlen = maxLength - $(this).val().length;
        $('#rchars').text(textlen);
    });
</script>
@endsection