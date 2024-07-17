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
    <div class="division-bar">
        <strong>Site Division/Project</strong> :
        CTMS - clinical site
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#Drug_Accountability_Add').click(function(e) {
            function generateTableRow(serialNumber) {

                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="ProductName[]"></td>' +
                    '<td><input type="number" name="BatchNumber[]"></td>' +
                    '<td><input type="date" name="ExpiryDate[]"></td>' +
                    '<td><input type="text" name="UnitsReceived[]"></td>' +
                    '<td><input type="text" name="UnitsDispensed[]"></td>' +
                    '<td><input type="text" name="UnitsDestroyed[]"></td>' +
                    '<td><input type="date" name="ManufacturedDate[]"></td>' +
                    '<td><input type="text" name="Strength[]"></td>' +
                    '<td><input type="text" name="Form[]"></td>' +
                    '<td><input type="text" name="Remarks[]"></td>' +
                    '</tr>';
                return html;
            }

            var tableBody = $('#Drug_Accountability_Table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#Equipment_Add').click(function(e) {
            function generateTableRow(serialNumber) {

                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="Product_Name[]"></td>' +
                    '<td><input type="number" name="Batch_Number[]"></td>' +
                    '<td><input type="date" name="Expiry_Date[]"></td>' +
                    '<td><input type="date" name="Manufactured_Date[]"></td>' +
                    '<td><input type="number" name="Number_of_Items_Needed[]"></td>' +
                    '<td><input type="text" name="Exist[]"></td>' +
                    '<td><input type="text" name="Comment[]"></td>' +
                    '<td><input type="text" name="Remarks[]"></td>' +
                    '</tr>';
                return html;
            }

            var tableBody = $('#Equipment_Table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#Financial_Transactions_Add').click(function(e) {
            function generateTableRow(serialNumber) {

                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="Transaction[]"></td>' +
                    '<td><input type="text" name="Transaction_Type[]"></td>' +
                    '<td><input type="date" name="Date[]"></td>' +
                    '<td><input type="text" name="Amount[]"></td>' +
                    '<td><input type="text" name="Currency_Used[]"></td>' +
                    '<td><input type="text" name="Remarks[]"></td>' +
                    '</tr>';
                return html;
            }

            var tableBody = $('#Financial_Transactions_Table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>


{{-- ======================================
                    DATA FIELDS
    ======================================= --}}
<div id="change-control-fields">
    <div class="container-fluid">

        <!-- Tab links -->
        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Clinical Site</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Site Information</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Useful Tools</button>
        </div>

        <!-- Tab content -->
        <div id="CCForm1" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="CTMS originator"><b>Initiator</b></label>
                            <input type="text" disabled name="originator" value="">
                        </div>
                    </div>
                    <div class="col-md-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="opened-date">Date of Initiation<span class="text-danger"></span></label>
                            <div class="calenderauditee">
                                <input type="text" disabled id="opened_date" placeholder="DD-MMM-YYYY" />
                                <input type="date" disabled name="opened_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'opened_date')" />
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Short Description">Short Description<span class="text-danger">*</span></label>
                            <div><small >255 characters remaining</small></div>
                            <input id="short-description" type="text" name="short_description" maxlength="255" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="assigned_to">
                                Assigned To <span class="text-danger"></span>
                            </label>
                            <div><small class="text-primary">Person Responsible</small></div>
                            <select id="select-state" placeholder="Select..." name="assign_to">
                                <option value="">Select a value</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="due-date">Due Date <span class="text-danger"></span></label>
                            <div><small class="text-primary">Please mention expected date of completion</small></div>
                            <div class="calenderauditee">
                                <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="type">
                                Type <span class="text-danger"></span>
                            </label>
                            <!-- <div><small class="text-primary">Select type of site</small></div> -->
                            <select id="select-state" placeholder="Select..." name="type">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Site Name"><b>Name of Site</b></label>
                            <input type="text" name="Site Name" value="">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="group-input">
                            <label for="Source_Documents">Source Documents</label>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="Source_Documents"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="myfile" name="Source_Documents[]" oninput="addMultipleFiles(this, 'Source_Documents')" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="group-input">

                            <label for="Sponsor"><b>Sponsor</b></label>
                            <input type="text" name="Sponsor" value="">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Description">Description</label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="group-input">
                            <label for="attached_files">Attached Files</label>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="attached_files"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="myfile" name="attached_files[]" oninput="addMultipleFiles(this, 'attached_files')" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Comments">Comments</label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="group-input">
                            <label for="Version_no">
                                (Parent) Version No. <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="Version_no">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="group-input">
                        <label for="audit-agenda-grid">
                            Drug Accountability Site (0)
                            <button type="button" name="audit-agenda-grid" id="Drug_Accountability_Add">+</button>
                        </label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="Drug_Accountability_Table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th> Row#</th>
                                        <th> Product Name</th>
                                        <th> Batch Number</th>
                                        <th> Expiry Date</th>
                                        <th> Units Received</th>
                                        <th> Units Dispensed</th>
                                        <th> Units Destroyed</th>
                                        <th> Manufactured Date</th>
                                        <th> Strength</th>
                                        <th> Form</th>
                                        <th> Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td><input disabled type="text" name="serial[]" value="1"></td>
                                    <td><input type="text" name="ProductName[]"></td>
                                    <td><input type="number" name="BatchNumber[]"></td>
                                    <td><input type="date" name="ExpiryDate[]"></td>
                                    <td><input type="text" name="UnitsReceived[]"></td>
                                    <td><input type="text" name="UnitsDispensed[]"></td>
                                    <td><input type="text" name="UnitsDestroyed[]"></td>
                                    <td><input type="date" name="ManufacturedDate[]"></td>
                                    <td><input type="text" name="Strength[]"></td>
                                    <td><input type="text" name="Form[]"></td>
                                    <td><input type="text" name="Remarks[]"></td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 sub-head">
                        Study Information
                    </div>
                    <div class="col-6">
                        <div class="group-input">
                            <label for="Admission Criteria">(Parent) Admission Criteria</label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="group-input">
                            <label for="Clinical Significance">(Parent) Clinical Significance</label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Trade Name"><b>(Root Parent) Trade Name</b></label>
                            <input type="text" name="Trade Name" value="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Tracking Number"><b>(Parent) Tracking Number</b></label>
                            <input type="text" name="Tracking Number" value="">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Phase of Study">(Parent) Phase of Study</label>
                            <select multiple name="attendees" placeholder="" data-search="false" data-silent-initial-value-set="true" id="attendees">
                                <option value="piyush">-- Select --</option>
                                <option value="piyush">Amit Guru</option>
                                <option value="piyush">Amit Patel</option>
                                <option value="piyush">Anshul Patel</option>
                                <option value="piyush">Shaleen Mishra</option>
                                <option value="piyush">Vikas Prajapati</option>
                            </select>
                        </div>
                    </div>
                    <div class="group-input">
                        <label for="audit-agenda-grid">
                            Equipment (0)
                            <button type="button" name="audit-agenda-grid" id="Equipment_Add">+</button>
                        </label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="Equipment_Table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th> Row#</th>
                                        <th> Product Name</th>
                                        <th> Batch Number</th>
                                        <th> Expiry Date</th>
                                        <th> Manufactured Date</th>
                                        <th> Number of Items Needed</th>
                                        <th> Exist</th>
                                        <th> Comment</th>
                                        <th> Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td><input disabled type="text" name="serial[]" value="1"></td>
                                    <td><input type="text" name="Product_Name[]"></td>
                                    <td><input type="text" name="Batch_Number[]"></td>
                                    <td><input type="date" name="Expiry_Date[]"></td>
                                    <td><input type="date" name="Manufactured_Date[]"></td>
                                    <td><input type="text" name="Number_of_Items_Needed[]"></td>
                                    <td><input type="text" name="Exist[]"></td>
                                    <td><input type="text" name="Comment[]"></td>
                                    <td><input type="text" name="Remarks[]"></td>
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="Type">
                                (Parent) Type <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="Type">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Other Type"><b>(Parent) Other Type</b></label>
                            <input type="text" name="Other Type" value="">
                        </div>
                    </div>
                    <div class="col-12 sub-head">
                        Location
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="Zone">
                                Zone <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="Zone">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Country"><b>Country</b></label>
                            <select id="select-state" placeholder="Select..." name="Country">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="City"><b>City</b></label>
                            <input type="text" name="City" value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="State_District">
                                State/District <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="State_District">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="search">
                                Site Name <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="Site Name">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="search">
                                Building <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="Building">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="search">
                                Floor <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="Floor">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="search">
                                Room <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="Room">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="button-block">
                    <button type="submit" class="saveButton">Save</button>
                    <!-- <button type="button" class="backButton" onclick="previousStep()">Back</button> -->
                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>                            
                    <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit</a></button>
                </div>
            </div>
        </div>

        <div id="CCForm2" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="row">
                    <div class="col-12 sub-head">
                        Site Additional Information
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Site Name"><b>Name of Site</b></label>
                            <input type="text" name="Site Name" value="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Site Name"><b>Pharmacy</b></label>
                            <input type="text" name="Site Name" value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="search">
                                Site Number <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="assign_to">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="search">
                                Site Status <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="assign_to">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="due-date">Activation Date <span class="text-danger"></span></label>
                            <div class="calenderauditee">
                                <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="due-date">Date of Final Report <span class="text-danger"></span></label>
                            <div class="calenderauditee">
                                <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="due-date">Initial IRB Approval Date <span class="text-danger"></span></label>
                            <div class="calenderauditee">
                                <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="due-date">IMP Receipt at Site Date <span class="text-danger"></span></label>
                            <div class="calenderauditee">
                                <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="search">
                                Lab/Department Name <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="assign_to">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="search">
                                Monitoring Performed By <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="assign_to">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Site Name"><b># Dropped/Withdrawn</b></label>
                            <input type="text" name="Site Name" value="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Site Name"><b># Enrolled</b></label>
                            <input type="text" name="Site Name" value="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Site Name"><b># Follow-Up</b></label>
                            <input type="text" name="Site Name" value="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Site Name"><b># Planned</b></label>
                            <input type="text" name="Site Name" value="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Site Name"><b># Screened</b></label>
                            <input type="text" name="Site Name" value="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Site Name"><b>Projected # of Annual MV</b></label>
                            <input type="text" name="Site Name" value="">
                        </div>
                    </div>
                    <div class="col-md-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="due-date">Scheduled Start Date <span class="text-danger"></span></label>
                            <div class="calenderauditee">
                                <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="due-date">Scheduled End Date <span class="text-danger"></span></label>
                            <div class="calenderauditee">
                                <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="due-date">Actual Start Date <span class="text-danger"></span></label>
                            <div class="calenderauditee">
                                <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="due-date">Actual End Date <span class="text-danger"></span></label>
                            <div class="calenderauditee">
                                <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Site Name"><b>Lab Name</b></label>
                            <input type="text" name="Site Name" value="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Site Name"><b>Monitoring Performed By</b></label>
                            <input type="text" name="Site Name" value="">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="group-input">
                            <label for="search">
                                Control Group <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="assign_to">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="group-input">
                            <label for="Consent_Form">Consent Form</label>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="Consent_Form"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="HOD_Attachments" name="Consent_Form[]" oninput="addMultipleFiles(this, 'Consent_Form')" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 sub-head">
                        Finance
                    </div>
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Budget">Budget</label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Sites Project">Project # of Sites</label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="group-input">
                            <label for="Subjects Project">Project # of Subjects</label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="Subjects in Site">
                                Subjects in Site <span class="text-danger"></span>
                            </label>
                            <div><small class="text-primary">Automatic Calculation</small></div>
                            <select id="select-state" placeholder="Select..." name="Subjects in Site">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="group-input">
                            <label for="Currency">
                                Currency <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="Currency">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="group-input">
                            <label for="Attached_Payments">Attached Payments</label>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="Attached_Payments"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="HOD_Attachments" name="Attached_Payments[]" oninput="addMultipleFiles(this, 'Attached_Payments')" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="group-input">
                        <label for="audit-agenda-grid">
                            Financial Transactions (0)
                            <button type="button" name="audit-agenda-grid" id="Financial_Transactions_Add">+</button>
                        </label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="Financial_Transactions_Table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th> Row#</th>
                                        <th> Transaction</th>
                                        <th> Transaction Type</th>
                                        <th> Date</th>
                                        <th> Amount</th>
                                        <th> Currency Used</th>
                                        <th> Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td><input disabled type="text" name="serial[]" value="1"></td>
                                    <td><input type="text" name="Transaction[]"></td>
                                    <td><input type="text" name="Transaction_Type[]"></td>
                                    <td><input type="date" name="Date[]"></td>
                                    <td><input type="text" name="Amount[]"></td>
                                    <td><input type="text" name="Currency_Used[]"></td>
                                    <td><input type="text" name="Remarks[]"></td>
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="col-12 sub-head">
                        Persons Involved
                    </div>
                    <div class="col-lg-12">
                        <div class="group-input">

                            <label for="CRA"><b>CRA</b></label>
                            <div><small class="text-primary">Clinical Research Associate</small></div>
                            <input type="text" name="CRA" value="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Lead Investigator"><b>Lead Investigator</b></label>
                            <input type="text" name="Lead Investigator" value="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Reserve Team Associate"><b>Reserve Team Associate</b></label>
                            <input type="text" name="Reserve Team Associate" value="">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="group-input">
                            <label for="Additional Investigators">Additional Investigators</label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="group-input">
                            <label for="Clinical Research Coordinator">Clinical Research Coordinator</label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="group-input">
                            <label for="Pharmacist">Pharmacist</label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="group-input">
                            <label for="Comments">Comments</label>
                            <textarea name="text"></textarea>
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
                <div class="row">
                    <div class="col-12 sub-head">
                        Finance
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Budget"><b>Budget</b></label>
                            <input type="text" name="Budget" value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group-input">
                            <label for="Currency">
                                Currency <span class="text-danger"></span>
                            </label>
                            <select id="select-state" placeholder="Select..." name="Currency">
                                <option value="">Enter your selection here</option>
                                <option value="">$1</option>
                                <option value="">$2</option>
                                <option value="">$3</option>
                            </select>
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
</div>

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
        ele: '#attendees'
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
</script>
@endsection
