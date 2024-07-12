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
        /RT- PSUR
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
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">PSUR</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Analysis and Conclusions</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Product Information</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Manufacture Details</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Registration Information</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Signatures</button>

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
                                    <label for="Documents">Documents</label>
                                    <input type="text" name="initiator_group_code" id="initiator_group_code" value="">
                                </div>
                            </div>

                            <div class="col-6">
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
                                    <label for="Year">Year</label>
                                    <select name="year" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">jan</option>
                                        <option value="">fab</option>
                                        <option value="">mar</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Actual Start Date">Actual Start Date</label>

                                    <div class="calenderauditee">
                                        <input type="text" id="actual_start_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="actual_start_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'actual_start_date')" />
                                    </div>


                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Actual End Date">Actual End Date</label>

                                    <div class="calenderauditee">
                                        <input type="text" id="actual_end_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="actual_end_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'actual_end_date')" />
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Authority Type">(Parent)Authority Type</label>
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
                                    <p class="text-primary"> Enity responsible for report</p>
                                    <select name="authority" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="group-input">
                                    <label for="Introduction">Introduction</label>
                                    <textarea class="" name="introduction" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Related Records">Related Records</label>
                                    <select name="related_records" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">A</option>
                                        <option value="">B</option>
                                        <option value="">C</option>

                                    </select>
                                </div>
                            </div>

                            <div class="sub-head">Action Taken</div>

                            <div class="col-md-12">
                                <div class="group-input">
                                    <label for="World MA Status">World MA Status</label>
                                    <textarea class="" name="world_ma_status" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="RA Actions Taken">RA Actions Taken</label>
                                    <textarea class="" name="ra_actions_taken" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="MAH Actions Taken">MAH Actions Taken</label>
                                    <textarea class="" name="mah_actions_taken" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="button-block">
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                                    </a> </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="CCForm2" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="sub-head col-12">Findingsand Analysis</div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Changes To SafetyInformation">Changes To SafetyInformation</label>
                                    <textarea class="" name="changes_to_safety_information" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Patient Exposure">Patient Exposure</label>
                                    <textarea class="" name="patient_exposure" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Analysis of Individual Case">Analysis of Individual Case</label>
                                    <textarea class="" name="analysis_of_individual_case" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Newly Analyzed Studies">Newly Analyzed Studies</label>
                                    <textarea class="" name="newly_analyzed_studies" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Target and New Safety Studies">Target and New Safety Studies</label>
                                    <textarea class="" name="target_and_new_safety_studies" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Publish Safety Studies">Publish Safety Studies</label>
                                    <textarea class="" name="publish_safety_studies" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Efficacy-Related Information">Efficacy-Related Information</label>
                                    <textarea class="" name="ra_actions_taken" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Late-Breaking Information">Late-Breaking Information</label>
                                    <textarea class="" name="late_breaking_information" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="sub-head">Conclusion</div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Overall Safety Evaluation">Overall Safety Evaluation</label>
                                    <textarea class="" name="overall_safety_evaluation" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Conclusion">Conclusion</label>
                                    <textarea class="" name="conclusion" id="">
                                    </textarea>
                                </div>
                            </div>

                        </div>
                        <div class="button-block">

                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                    Exit </a> </button>
                        </div>
                    </div>
                </div>

                <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="sub-head">
                            Product Information
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Manufaturer">(Root Parent) Manufacturer</label>
                                    <select name="root_parent_manufaturer" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">a</option>
                                        <option value="">b</option>
                                        <option value="">c</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Product Type">(Root Parent) Product Type</label>
                                    <select name="root_parent_product_type" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">a</option>
                                        <option value="">b</option>
                                        <option value="">c</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Trade Name">(Root Parent)Trade Name</label>
                                    <select name="root_parent_product_trade_name" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">a</option>
                                        <option value="">b</option>
                                        <option value="">c</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="International Birth Date">(Root Parent)International Birth Date</label>

                                    <div class="calenderauditee">
                                        <input type="text" id="international_birth_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="international_birth_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'international_birth_date')" />
                                    </div>


                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="API">(Root Parent)API</label>
                                    <select multiple id="api" name="" id="">
                                        <option value="">--Select---</option>

                                        <option value="">

                                        </option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Product Strength">(Root Parent)Product Strength</label>
                                    <select name="root_parent_product_product_strangth" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">a</option>
                                        <option value="">b</option>
                                        <option value="">c</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Route of Administration">(Root Parent)Route of Administration</label>
                                    <select multiple id="route_of_administration" name="" id="">
                                        <option value="">--Select---</option>

                                        <option value="">

                                        </option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Dosage Form">(Root Parent)Dosage Form</label>
                                    <select name="root_parent_product_dosage_form" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">a</option>
                                        <option value="">b</option>
                                        <option value="">c</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="ATC_codes">
                                        (Root Parent) ATC Codes (0)
                                        <button type="button" onclick="add4Input('ATC_codes-first-table')">+</button>
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="ATC_codes-first-table">
                                            <thead>
                                                <tr>
                                                    <th>Row #</th>
                                                    <th>ATC Search</th>
                                                    <th>1st Level</th>
                                                    <th>2nd Level</th>
                                                    <th>3rd Level</th>
                                                    <th>4th Level</th>
                                                    <th>5th Level</th>
                                                    <th>ATC Code</th>
                                                    <th>Substance</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td><input disabled type="text" name="serial_number[]" value="1">
                                                </td>
                                                <td><input type="text" name="atc_Search[]"></td>
                                                <td><input type="text" name="1st_Level[]"></td>
                                                <td><input type="text" name="2nd_Level[]"></td>
                                                <td><input type="text" name="3rd_Level[]"></td>
                                                <td><input type="text" name="4th_Level[]"></td>
                                                <td><input type="text" name="5th_Level[]"></td>
                                                <td><input type="text" name="atc_Code[]"></td>
                                                <td><input type="text" name="substance[]"></td>
                                                <td><input type="text" name="remarks[]"></td>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Ingredients">
                                        (Root Parent) Ingredients (0)
                                        <button type="button" onclick="add4Input('Ingredients-first-table')">+</button>
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="Ingredients-first-table">
                                            <thead>
                                                <tr>
                                                    <th>Row #</th>
                                                    <th>IngredientType</th>
                                                    <th>Ingredient Name</th>
                                                    <th>Ingredient Strength</th>
                                                    <th>Specification Date</th>
                                                    <th>Remarks</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td><input disabled type="text" name="serial_number[]" value="1">
                                                </td>
                                                <td><input type="text" name="ingredient_Type[]"></td>
                                                <td><input type="text" name="ingredient_Name[]"></td>
                                                <td><input type="text" name="ingredient_Strength[]"></td>
                                                <td><input type="text" name="Specification_Date[]"></td>
                                                <td><input type="text" name="Remarks[]"></td>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Therapeutic Area">(Root Parent)Therapeutic Area</label>
                                    <select name="Therapeutic_Area" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">a</option>
                                        <option value="">b</option>
                                        <option value="">c</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Therapeutic Area">(Root Parent)Therapeutic Area</label>
                                </div>
                                <div>
                                    <div class="container">
                                        <input type="radio" class="radio-input" name="option" value="option1">
                                        <label class="radio-label">Yes</label>
                                    </div>

                                    <div class="container">
                                        <input type="radio" class="radio-input" name="option" value="option2">
                                        <label class="radio-label">NO</label>
                                    </div>

                                    <div class="container">
                                        <input type="radio" class="radio-input" name="option" value="option3">
                                        <label class="radio-label">NA</label>
                                    </div>
                                    <a href="#" id="clearSelection" class="container text-primary">Clear Selection</a> 
                                </div>
                               
                            </div>

                        </div>
                        <div class="button-block">
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                                </a> </button>
                        </div>
                    </div>
                </div>
                <div id="CCForm4" class="inner-block cctabcontent">
                    <div class="button-block">
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                        <button type="submit" class="saveButton">Save</button>
                        <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                            </a> </button>
                    </div>
                </div>

                <div id="CCForm5" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="sub-head">
                            Registration Plan
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Registration Status">Registration Status</label>
                                    <select name="initiated_if_other" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">A</option>
                                        <option value="">B</option>
                                        <option value="">C</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Registration Number">Registration Number</label>
                                    <input type="text" name="registration_number" id="registration_number" value="">
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Planned Submission Date"> Planned Submission Date </label>

                                    <div class="calenderauditee">
                                        <input type="text" id="planned_submission_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="planned_submission_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'planned_submission_date')" />
                                    </div>


                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Actual Submission Date"> Actual Submission Date </label>

                                    <div class="calenderauditee">
                                        <input type="text" id="actual_submission_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="actual_submission_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'actual_submission_date')" />
                                    </div>


                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="group-input">
                                    <label for="Comments">Comments</label>
                                    <textarea class="" name="comments" id="">
                                    </textarea>
                                </div>
                            </div>

                            <div class="sub-head">Local Information/Procedure</div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="(Parent) Procedure Type">(Parent) Procedure Type</label>
                                    <p>
                                        < No Options Available>
                                    </p>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="(Parent) Procedure Number">(Parent) Procedure Number</label>
                                    <input type="text" name="procedure_number" id="procedure_number" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="(Parent) Reference Member State(RMS)">(Parent) Reference Member State(RMS)</label>
                                    <select name="reference_member_state" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">A</option>
                                        <option value="">B</option>
                                        <option value="">C</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="(Parent)Renewal Rule">(Parent)Renewal Rule</label>
                                    <select name="renewal_rule" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="">A</option>
                                        <option value="">B</option>
                                        <option value="">C</option>

                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="(Parent)Concerned Member States (CMSs)">(Parent)Concerned Member States (CMSs)</label>
                                    <select multiple id="reference_system_document" name="" id="">
                                        <option value="">--Select---</option>

                                        <option value="">

                                        </option>

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


                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Packaging_Information">
                                        (Root Parent) Packaging Information (0)
                                        <button type="button" onclick="add4Input('Packaging_Information-first-table')">+</button>
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="Packaging_Information-first-table">
                                            <thead>
                                                <tr>
                                                    <th>Row #</th>
                                                    <th>Primary Packaging</th>
                                                    <th>Material</th>
                                                    <th>Pack Size</th>
                                                    <th>Shelf Life</th>
                                                    <th>Storage Condition</th>
                                                    <th>Secondary Packaging</th>
                                                    <th>Remarks</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td><input disabled type="text" name="serial_number[]" value="1">
                                                </td>
                                                <td><input type="text" name="Primary_Packaging[]"></td>
                                                <td><input type="text" name="Material[]"></td>
                                                <td><input type="text" name="Pack_Size[]"></td>
                                                <td><input type="text" name="Shelf_Life[]"></td>
                                                <td><input type="text" name="Storage_Condition[]"></td>
                                                <td><input type="text" name="Secondary_Packaging[]"></td>
                                                <td><input type="text" name="Remarks[]"></td>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="button-block">
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                                </a> </button>
                        </div>
                    </div>
                </div>

                <div id="CCForm6" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="sub-head">
                            Activity Log
                        </div>
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Started by">Started by : </label>

                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Started on"> Started on : </label>




                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Submitted By">Submitted By :</label>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Submittedon">Submitted on :</label>


                                </div>
                            </div>
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
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Withdrawn By">Withdrawn By :</label>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Withdrawnon">Withdrawn on :</label>


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