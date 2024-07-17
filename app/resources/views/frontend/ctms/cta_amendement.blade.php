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
        /CTMS - CTA Amendement
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#ATC_codes').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="atc_Search[]"></td>' +
                    '<td><input type="text" name="1st_Level[]"></td>' +
                    '<td><input type="text" name="2nd_Level[]"></td>' +
                    '<td><input type="text" name="3rd_Level[]"></td>' +
                    '<td><input type="text" name="4th_Level[]"></td>' +
                    '<td><input type="text" name="5th_Level[]"></td>' +
                    '<td><input type="text" name="atc_Code[]"></td>' +
                    '<td><input type="text" name="substance[]"></td>' +
                    '<td><input type="text" name="remarks[]"></td>' +


                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                //     '</tr>';

                return html;
            }

            var tableBody = $('#ATC_codes-table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#Ingredients').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="ingredient_Type[]"></td>' +
                    ' <td><input type="text" name="ingredient_Name[]"></td>' +
                    '<td><input type="text" name="ingredient_Strength[]"></td>' +
                    '<td><input type="text" name="Specification_Date[]"></td>' +
                    '<td><input type="text" name="Remarks[]"></td>' +



                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                //     '</tr>';

                return html;
            }

            var tableBody = $('#Ingredients-first-table');
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
                    '<td><input type="text" name="Batch_number[]"></td>' +
                    '<td><input type="text" name="Expiry_date[]"></td>' +
                    '<td><input type="text" name="Manufactured_date[]"></td>' +
                    '<td><input type="text" name="Disposition_date[]"></td>' +
                    '<td><input type="text" name="Comments_date[]"></td>' +
                    '<td><input type="text" name="Remarks[]"></td>' +


                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                //     '</tr>';

                return html;
            }

            var tableBody = $('#Product_Material-first-table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#Packaging_Information').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +

                    '<td><input type="text" name="Primary_Packaging[]"></td>'
                '<td><input type="text" name="Material[]"></td>' +
                '<td><input type="text" name="Pack_Size[]"></td>' +
                '<td><input type="text" name="Shelf_Life[]"></td>' +
                '<td><input type="text" name="Storage_Condition[]"></td>' +
                '<td><input type="text" name="Secondary_Packaging[]"></td>' +
                '<td><input type="text" name="Remarks[]"></td>' +

                '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                //     '</tr>';

                return html;
            }

            var tableBody = $('#Packaging_Information-first-table tbody');
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
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">CTA Amendement</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">CTA amendement Information</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Root Cause Analysis</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Signatures</button>

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
                                    <label for="Initiator">Initiator</label>
                                    <input disabled type="text" name="initiator_id" value="" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input ">
                                    <label for="Date Of Initiation"><b>Date Of Initiation</b></label>
                                    <input disabled type="text" value="" name="intiation_date">
                                    <input type="hidden" value="" name="intiation_date">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Initiator Group Code">Short Description</label>
                                    <p class="text-primary">PSUR Short Description to be presented on dekstop</p>
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
                                    <p class="text-primary"> last date this record should be closed by</p>
                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                                    </div>


                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Type">Type</label>
                                    <select name="type" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Other Type">Other Type</label>
                                    <select name="Other_type" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>

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
                                            <input type="file" id="myfile" name="" oninput="" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="group-input">
                                    <label for="Description">Description</label>

                                    <small class="text-primary">
                                        Amendment detailled description
                                    </small>

                                    <textarea class="" name="description" id="">
                                    </textarea>

                                </div>
                            </div>


                            <div class="sub-head">Location</div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="search">
                                        Zone <span class="text-danger"></span>
                                    </label>
                                    <select id="select-state" placeholder="Select..." name="zone">
                                        <option value="">Select a value</option>

                                        <option value=""></option>

                                    </select>



                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="search">
                                        Country <span class="text-danger"></span>
                                    </label>
                                    <select id="select-state" placeholder="Select..." name="country">
                                        <option value="">Select a value</option>

                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="search">
                                        State/District <span class="text-danger"></span>
                                    </label>


                                    <select id="select-state" placeholder="Select..." name="city">
                                        <option value="">Select a value</option>

                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="search">
                                        City <span class="text-danger"></span>
                                    </label>
                                    <select id="select-state" placeholder="Select..." name="city">
                                        <option value="">Select a value</option>

                                        <option value=""></option>

                                    </select>
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
                            <div class="sub-head col-12">Amendement Information</div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Procedure Number">Procedure Number</label>
                                    <input type="text" name="procedure_number" id="procedure_number" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Project Code">Project Code</label>
                                    <input type="text" name="project_code" id="project_code" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Registration Number">Registration Number</label>
                                    <input type="text" name="registration_number" id="registration_number" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Other Authority">Other Authority</label>
                                    <input type="text" name="other_authority" id="other_authority" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Authority Type">Authority Type</label>
                                    <select name="authority_type" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Authority">Authority</label>
                                    <select name="authority" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Year">Year</label>
                                    <select name="year" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>

                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Registration Status">Registration Status</label>
                                    <small class="text-primary">
                                        < No Option available>
                                    </small>
                                </div>
                            </div>



                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Registration Status">Registration Status</label>
                                    <select name="registratioon_status" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="CAR Clouser Time Weight">CAR Clouser Time Weight</label>
                                    <select name="car_clouser_time_weight" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Outcome">Outcome</label>
                                    <select name="outcome" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Trade Name">Trade Name</label>
                                    <input type="text" name="trade_name" id="trade_name" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Estimated Man-Hours">Estimated Man-Hours</label>
                                    <input type="text" name="estimated_man_hours" id="estimated_man_hours" value="">
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="group-input">
                                    <label for="Comments">Comments</label>
                                    <textarea class="" name="description" id="">
                                    </textarea>

                                </div>
                            </div>

                            <div class="sub-head">Product Information</div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Manufaturer">Manufaturer</label>
                                    <select name="manufaturer" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">A</option>
                                        <option value="">B</option>
                                        <option value="">C</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Product_Material">
                                        (Root Parent) Product/Material (0)
                                        <button type="button" onclick="add4Input('Product_Material-first-table')">+</button>
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="Product_Material-first-table">
                                            <thead>
                                                <tr>
                                                    <th>Row #</th>
                                                    <th>Product Name</th>
                                                    <th>Batch Number</th>
                                                    <th>Expiry Date</th>
                                                    <th>Manufactured Date</th>
                                                    <th>Disposition</th>
                                                    <th>Comments</th>
                                                    <th>Remarks</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td><input disabled type="text" name="serial_number[]" value="1">
                                                </td>
                                                <td><input type="text" name="Product_Type[]"></td>
                                                <td><input type="text" name="Batch_number[]"></td>
                                                <td><input type="text" name="Expiry_date[]"></td>
                                                <td><input type="text" name="Manufactured_date[]"></td>
                                                <td><input type="text" name="Disposition_date[]"></td>
                                                <td><input type="text" name="Comments_date[]"></td>
                                                <td><input type="text" name="Remarks[]"></td>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="sub-head">Important Dates</div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Actual Submission Date"> Actual Submission Date </label>
                                    <div class="calenderauditee">
                                        <input type="text" id="actual_submission_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="actual_submission_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'actual_submission_date')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Actual Rejection Date"> Actual Rejection Date </label>
                                    <div class="calenderauditee">
                                        <input type="text" id="actual_rejection_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="actual_rejection_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'actual_rejection_date')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Actual Withdrawn Date"> Actual Withdrawn Date </label>
                                    <div class="calenderauditee">
                                        <input type="text" id="actual_withdrawn_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="actual_withdrawn_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'actual_withdrawn_date')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Inquiry Date"> Inquiry Date </label>
                                    <div class="calenderauditee">
                                        <input type="text" id="inquiry_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="inquiry_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'inquiry_date')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Planened Submission Date"> Planened Submission Date </label>
                                    <div class="calenderauditee">
                                        <input type="text" id="planned_submission_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="planned_submission_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'planned_submission_date')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Planened DateSent To Affiliate"> Planened DateSent To Affiliate </label>
                                    <div class="calenderauditee">
                                        <input type="text" id="planned_date_sent_to_affiliate" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="planned_date_sent_to_affiliate" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'planned_date_sent_to_affiliate')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Effective Date"> Effective Date </label>
                                    <div class="calenderauditee">
                                        <input type="text" id="effective_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="effective_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'effective_date')" />
                                    </div>
                                </div>
                            </div>

                            <div class="sub-head">Person Involved</div>


                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Additional Assignees">Additional Assignees</label>
                                    <textarea class="" name="additional_assignees" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Additional Investigators">Additional Investigators</label>
                                    <textarea class="" name="additional_investigators" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Approvers">Approvers</label>
                                    <textarea class="" name="approvers" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Negotiation Team">Negotiation Team</label>
                                    <textarea class="" name="negotiation_team" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Trainer">Trainer</label>
                                    <select name="trainer" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">A</option>
                                        <option value="">B</option>
                                        <option value="">C</option>

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
                            Root Cause
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Root Cause Description">Root Cause Description</label>
                                    <textarea class="" name="root_cause_description" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Reason(s) for Non-Approval">Reason(s) for Non-Approval</label>
                                    <textarea class="" name="reason_for_non_approval" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Reason(s) for Withdrawal">Reason(s) for Withdrawal</label>
                                    <textarea class="" name="reason_for_withdrawal" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Justification/ Rationale">Justification/ Rationale</label>
                                    <textarea class="" name="justification_rationale" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Meeting Minutes">Meeting Minutes</label>
                                    <textarea class="" name="meeting_minutes" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Rejection Reason">Rejection Reason</label>
                                    <textarea class="" name="rejection_reason" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Effectiveness Check Summary">Effectiveness Check Summary</label>
                                    <textarea class="" name="effectiveness_check_summary" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Decision">Decision</label>
                                    <textarea class="" name="decision" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Summary">Summary</label>
                                    <textarea class="" name="summary" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Documents Affected">Documents Affected</label>
                                    <select multiple id="documents_affected" name="" id="">
                                        <option value="">--Select---</option>
                                        <option value="">
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Actual Time Spend">Actual Time Spend</label>
                                    <input type="text" name="actual_time_spend" id="actual_time_spend" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Documents">Documents</label>
                                    <input type="text" name="documents" id="documents" value="">
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
                                    <label for="Approved  By">Approved By :</label>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Approved on">Approved on :</label>
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
    document.getElementById('clearSelection').addEventListener('click', function() {
        var radios = document.querySelectorAll('input[type="radio"]');
        for (var i = 0; i < radios.length; i++) {
            radios[i].checked = false;
        }
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