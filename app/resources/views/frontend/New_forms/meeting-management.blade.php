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
        / Supplier Observation
    </div>
</div>

{{-- ! ========================================= --}}
{{-- !               DATA FIELDS                 --}}
{{-- ! ========================================= --}}
<div id="change-control-fields">
    <div class="container-fluid">

        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Operational Planning & Control</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Meetings & Summary</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Closure</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Signatures</button>
        </div>

        <form action="{{ route('actionItem.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div id="step-form">
                @if (!empty($parent_id))
                <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                @endif
                <!-- ==========================================General Information============================================ -->
                <div id="CCForm1" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="sub-head">
                                General Information
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="MM Record Number"><b>Record Number</b></label>
                                    <input disabled type="text" name="record_number" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Division Code"><b>Site/Location Code</b></label>
                                    <input disabled type="text" name="division_code" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Initiator</b></label>
                                    <input type="hidden" name="initiator_id">
                                    <input disabled type="text" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Date Due"><b>Date of Initiation</b></label>
                                    <input disabled type="text" value="" name="intiation_date">
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

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator Group"><b>Initiator Group</b></label>
                                    <select name="initiator_Group">
                                        <option value="CQA">
                                            Corporate
                                            Quality Assurance</option>
                                        <option value="QAB">
                                            Quality
                                            Assurance Biopharma</option>
                                        <option value="CQC">
                                            Central
                                            Quality Control</option>
                                        <option value="CQC">
                                            Manufacturing
                                        </option>
                                        <option value="PSG">
                                            Plasma
                                            Sourcing Group</option>
                                        <option value="CS">
                                            Central
                                            Stores</option>
                                        <option value="ITG">
                                            Information
                                            Technology Group</option>
                                        <option value="MM">
                                            Molecular
                                            Medicine</option>
                                        <option value="CL">
                                            Central
                                            Laboratory</option>
                                        <option value="TT">Tech
                                            team</option>
                                        <option value="QA">Quality
                                            Assurance</option>
                                        <option value="QM">
                                            Quality
                                            Management</option>
                                        <option value="IA">IT
                                            Administration</option>
                                        <option value="ACC">
                                            Accounting
                                        </option>
                                        <option value="LOG">
                                            Logistics
                                        </option>
                                        <option value="SM">
                                            Senior
                                            Management</option>
                                        <option value="BA">
                                            Business
                                            Administration</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator Group Code">Initiator Group Code</label>
                                    <input type="text" name="initiator_group_code" value="" id="initiator_group_code" value="" readonly>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Short_Description">Short Description<span class="text-danger">*</span></label><span id="rchars">255</span>
                                    characters remaining
                                    <textarea name="short_description" id="docname" type="text" maxlength="255"></textarea>
                                    <p id="docnameError" style="color:red">**Short Description is required</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="type">Type</label>
                                    <select name="type">
                                        <option value="0">-- Select type --</option>
                                        <option>Other</option>
                                        <option>Training</option>
                                        <option>Finance</option>
                                        <option>Follow Up</option>
                                        <option>Marketing</option>
                                        <option>Sales</option>
                                        <option>Account Service</option>
                                        <option>Recent Product Launch</option>
                                        <option>IT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="type">Priority Level</label>
                                    <select name="type">
                                        <option value="0">-- Select type --</option>
                                        <option>High</option>
                                        <option>Medium</option>
                                        <option>Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Scheduled Start Date">Scheduled Start Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="start_date" readonly placeholder="DD-MMM-YYYY" value="" />
                                        <input type="date" id="start_date_checkdate" value="" name="start_date" min="" class="hide-input" oninput="handleDateInput(this, 'start_date');checkDate('start_date_checkdate','end_date_checkdate')" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Audit End Date">Scheduled end date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="end_date" readonly placeholder="DD-MMM-YYYY" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Attendees">Attendess</label>
                                    <textarea name="attendees"></textarea>
                                </div>
                            </div>

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Agenda
                                    <button type="button" name="audit-agenda-grid" id="agenda">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="agenda-field-instruction-modal">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Date</th>
                                                <th style="width: 16%">Topic</th>
                                                <th style="width: 16%">Responsible</th>
                                                <th style="width: 16%">Shelf Life</th>
                                                <th style="width: 15%">Time Start</th>
                                                <th style="width: 15%">Time End</th>
                                                <th style="width: 15%">Comments</th>
                                                <th style="width: 15%">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>
                                            <td><input type="date" name="IDnumber[]"></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="time" name="start_time" value=""></td>
                                            <td><input type="time" name="end_time[]" value=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Description">Description</label>
                                    characters remaining
                                    <textarea name="description" id="docname" type="text"></textarea>
                                </div>
                            </div>

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Management Review Participants
                                    <button type="button" name="audit-agenda-grid" id="Management_Review_Participants">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="Management_Review_Participants-field-instruction-modal">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Invite Person</th>
                                                <th style="width: 16%">Designee</th>
                                                <th style="width: 16%">Department</th>
                                                <th style="width: 16%">Meeting Attended</th>
                                                <th style="width: 15%">Designee Name</th>
                                                <th style="width: 15%">Designee Department/Designation</th>
                                                <th style="width: 15%">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>
                                            <td><input type="text" name="IDnumber[]"></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="File_Attachment">File Attachment</label>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="file_attach"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Attached_File[]" oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                        </div>
                                    </div>
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
                <!-- ==========================================Operational Planning & Control============================================ -->
                <div id="CCForm2" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Operations">Operations
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#management-review-operations-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <textarea name="operations " id="docname" type="text"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Requirements for Products and Services">Requirements for Products and Services
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#management-review-requirement_products_services-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <textarea name="operations " id="Requirements_for_Products" type="text"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Design and Development of Products and Services">
                                        Design and Development of Products and Services
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#management-review-design_development_product_services-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <textarea name="operations " id="Design_and_Development" type="text"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Control of Externally Provided Processes, Products and Services">
                                        Control of Externally Provided Processes, Products and Services
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#management-review-control_externally_provide_services-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <textarea name="operations " id="Control_of_Externally_Provided_Processes_Products_and_Services" type="text"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Production and Service Provision">
                                        Production and Service Provision
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#management-review-production_service_provision-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <textarea name="operations " id="Production_and_Service_Provision" type="text"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Release of Products and Services">
                                        Release of Products and Services
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#management-review-release_product_services-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <textarea name="operations " id="Release_of_Products_and_Services" type="text"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Control of Non-conforming Outputs ">
                                        Control of Non-conforming Outputs
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#management-review-control_nonconforming_outputs-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <textarea name="operations " id="Control_of_Non-conforming_Outputs" type="text"></textarea>
                                </div>
                            </div>

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Performance Evaluation
                                    <button type="button" name="audit-agenda-grid" id="performance_evaluation">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="performance_evaluation-field-instruction-modal">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Monitoring</th>
                                                <th style="width: 16%">Measurement</th>
                                                <th style="width: 16%">Analysis</th>
                                                <th style="width: 16%">Evalutaion</th>
                                                <th style="width: 15%">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>
                                            <td><input type="text" name="IDnumber[]"></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                        </tbody>

                                    </table>
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
                </div>
                <!-- ==========================================Meetings & Summary============================================ -->
                <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Risk & Opportunities">Risk & Opportunities</label>
                                <textarea name="Risk_&_Opportunities" id="" type="text" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="External Supplier Performance">External Supplier Performance</label>
                                <textarea name="External_Supplier_Performance" id="" type="text" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Customer Satisfaction Level">Customer Satisfaction Level</label>
                                <textarea name="Customer_Satisfaction_Level" id="" type="text" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Budget Estimates">Budget Estimates</label>
                                <textarea name="Budget_Estimates" id="" type="text" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Completion of Previous Tasks">Completion of Previous Tasks</label>
                                <textarea name="Completion_of_Previous_Tasks" id="" type="text" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Production">Production</label>
                                <textarea name="Production" id="" type="text" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Plans">Plans</label>
                                <textarea name="Plans" id="" type="text" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Forecast">Forecast</label>
                                <textarea name="Forecast" id="" type="text" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Any Additional Support Required">Any Additional Support Required</label>
                                <textarea name="Any_Additional_Support_Required" id="" type="text" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="group-input">
                                <label for="File Attachment, if any">File Attachment, if any</label>
                                <div class="file-attachment-field">
                                    <div class="file-attachment-list" id="file_attach"></div>
                                    <div class="add-btn">
                                        <div>Add</div>
                                        <input type="file" id="myfile" name="File Attachment, if any" oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ==========================================Closure============================================ -->
                <div id="CCForm4" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Action Item Details
                                    <button type="button" name="audit-agenda-grid" id="action_Item_Details">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="action_Item_Details-field-instruction-modal">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Short Description</th>
                                                <th style="width: 15%">Due Date</th>
                                                <th style="width: 15%">Site / Division</th>
                                                <th style="width: 15%"> Person Responsible</th>
                                                <th style="width: 15%">Current Status</th>
                                                <th style="width: 15%">Date Closed</th>
                                                <th style="width: 15%">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>
                                            <td><input type="text" name="IDnumber[]"></td>
                                            <td><input type="date" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td> <select id="" placeholder="Select..." name="capa_type">
                                                    <option value="">Select a value</option>
                                                    <option value="1">Amit Guru</option>
                                                    <option value="2">Esra'a Hyasat</option>
                                                    <option value="3">Sondos</option>
                                                    <option value="4">User 1</option>
                                                    <option value="5">User 2</option>
                                                    <option value="6">User 3</option>
                                                    <option value="7">User 4</option>
                                                    <option value="8">User 5</option>
                                                    <option value="9">Shaleen Mishra</option>
                                                    <option value="10">User 1</option>
                                                    <option value="11">User 2</option>
                                                    <option value="12">User 3</option>
                                                    <option value="13">User 4</option>
                                                    <option value="14">User 5</option>
                                                    <option value="15">Vikas Prajapati</option>
                                                    <option value="16">User 1</option>
                                                    <option value="17">User 2</option>
                                                    <option value="18">User 3</option>
                                                    <option value="19">User 4</option>
                                                    <option value="20">User 5</option>
                                                    <option value="21">Anshul Patel</option>
                                                    <option value="22">User 1</option>
                                                    <option value="23">User 2</option>
                                                    <option value="24">User 3</option>
                                                    <option value="25">User 4</option>
                                                    <option value="26">User 5</option>
                                                    <option value="27">Amit Patel</option>
                                                    <option value="28">User 1</option>
                                                    <option value="29">User 2</option>
                                                    <option value="30">User 3</option>
                                                    <option value="31">User 4</option>
                                                    <option value="32">User 5</option>
                                                    <option value="33">Madhulika Mishra</option>
                                                    <option value="34">User 1</option>
                                                    <option value="35">User 2</option>
                                                    <option value="36">User 3</option>
                                                    <option value="37">User 4</option>
                                                    <option value="38">User 5</option>
                                                    <option value="39">Jin Kim</option>
                                                    <option value="40">Akash Asthana</option>
                                                    <option value="41">manish</option>
                                                </select></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="date" name=""></td>
                                            <td><input type="text" name=""></td>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    CAPA Details
                                    <button type="button" name="audit-agenda-grid" id="capa_Details">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="capa_Details-field-instruction-modal">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">CAPA Details</th>
                                                <th style="width: 15%">CAPA Type</th>
                                                <th style="width: 15%">Site / Division</th>
                                                <th style="width: 15%">Person Responsible</th>
                                                <th style="width: 15%">Current Status</th>
                                                <th style="width: 15%">Date Closed</th>
                                                <th style="width: 16%">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>
                                            <td><input type="text" name=""></td>
                                            <td>
                                                <select id="" placeholder="Select..." name="capa_type">
                                                    <option value="">Select a value</option>
                                                    <option value="corrective">Corrective Action</option>
                                                    <option value="preventive">Preventive Action</option>
                                                    <option value="corrective_preventive">Corrective &amp; Preventive Action</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name=""></td>
                                            <td> <select id="" placeholder="Select..." name="capa_type">
                                                    <option value="">Select a value</option>
                                                    <option value="1">Amit Guru</option>
                                                    <option value="2">Esra'a Hyasat</option>
                                                    <option value="3">Sondos</option>
                                                    <option value="4">User 1</option>
                                                    <option value="5">User 2</option>
                                                    <option value="6">User 3</option>
                                                    <option value="7">User 4</option>
                                                    <option value="8">User 5</option>
                                                    <option value="9">Shaleen Mishra</option>
                                                    <option value="10">User 1</option>
                                                    <option value="11">User 2</option>
                                                    <option value="12">User 3</option>
                                                    <option value="13">User 4</option>
                                                    <option value="14">User 5</option>
                                                    <option value="15">Vikas Prajapati</option>
                                                    <option value="16">User 1</option>
                                                    <option value="17">User 2</option>
                                                    <option value="18">User 3</option>
                                                    <option value="19">User 4</option>
                                                    <option value="20">User 5</option>
                                                    <option value="21">Anshul Patel</option>
                                                    <option value="22">User 1</option>
                                                    <option value="23">User 2</option>
                                                    <option value="24">User 3</option>
                                                    <option value="25">User 4</option>
                                                    <option value="26">User 5</option>
                                                    <option value="27">Amit Patel</option>
                                                    <option value="28">User 1</option>
                                                    <option value="29">User 2</option>
                                                    <option value="30">User 3</option>
                                                    <option value="31">User 4</option>
                                                    <option value="32">User 5</option>
                                                    <option value="33">Madhulika Mishra</option>
                                                    <option value="34">User 1</option>
                                                    <option value="35">User 2</option>
                                                    <option value="36">User 3</option>
                                                    <option value="37">User 4</option>
                                                    <option value="38">User 5</option>
                                                    <option value="39">Jin Kim</option>
                                                    <option value="40">Akash Asthana</option>
                                                    <option value="41">manish</option>
                                                </select></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="date" name=""></td>
                                            <td><input type="text" name=""></td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-12 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Next Management Review Date">Next Management Review Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="start_date" readonly placeholder="DD-MMM-YYYY" value="" />
                                        <input type="date" id="start_date_checkdate" value="" name="Next Management Review Date" min="" class="hide-input" oninput="handleDateInput(this, 'start_date');checkDate('start_date_checkdate','end_date_checkdate')" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Summary & Recommendation">Summary & Recommendation</label>
                                    <textarea name="Summary_Recommendation" id="" type="text" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Conclusion">Conclusion</label>
                                    <textarea name="Conclusion" id="" type="text" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Closure Attachments">Closure Attachments</label>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="file_attach"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Closure_Attachments[]" oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-head">
                                Extension Justification
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Due Date Extension Justification">Due Date Extension Justification</label>
                                    <textarea name="Due_Date_Extension_Justification" id="" type="text" rows="3"></textarea>
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
                </div>
                <!-- ==========================================Signatures============================================ -->
                <div id="CCForm5" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="sub-head">
                                Signatures
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Completed by">Completed By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Completed on">Completed On</label>
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
    $(document).ready(function() {
        $('#Management_Review_Participants').click(function(e) {
            function generateTableRow(serialNumber) {

                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '</tr>';

                return html;
            }

            var tableBody = $('#Management_Review_Participants-field-instruction-modal tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
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
                    '<td><input type="time" name="[]"></td>' +
                    '<td><input type="time" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '</tr>';

                return html;
            }

            var tableBody = $('#agenda-field-instruction-modal tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#performance_evaluation').click(function(e) {
            function generateTableRow(serialNumber) {

                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '</tr>';

                return html;
            }

            var tableBody = $('#performance_evaluation-field-instruction-modal tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#action_Item_Details').click(function(e) {
            function generateTableRow(serialNumber) {

                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="date" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><select id="select-state" placeholder="Select..." name="capa_type[]">' +
                    '<option value="">Select a value</option>' +
                    '<option value="1">Amit Guru</option>' +
                    '<option value="2">Esra Hyasat</option>' +
                    '<option value="3">Sondos</option>' +
                    '<option value="4">User 1</option>' +
                    '<option value="5">User 2 </option>' +
                    '<option value="6">User 3 < /option>' +
                    '<option value="7">User 4 < /option>' +
                    '<option value="8">User 5 < /option>' +
                    '<option value="9">Shaleen Mishra < /option>' +
                    '<option value="10">User 1 < /option>' +
                    '<option value="11">User 2 < /option>' +
                    '<option value="12">User 3 < /option>' +
                    '<option value="13">User 4 < /option>' +
                    '<option value="14">User 5 < /option>' +
                    '<option value="15">Vikas Prajapati < /option>' +
                    '<option value="16">User 1 < /option>' +
                    '<option value="17">User 2 < /option>' +
                    '<option value="18">User 3 < /option>' +
                    '<option value="19">User 4 < /option>' +
                    '<option value="20">User 5 < /option>' +
                    '<option value="21">Anshul Patel < /option>' +
                    '<option value="22">User 1 < /option>' +
                    '<option value="23">User 2 < /option>' +
                    '<option value="24">User 3 < /option>' +
                    '<option value="25">User 4 < /option>' +
                    '<option value="26">User 5 < /option>' +
                    '<option value="27">Amit Patel < /option>' +
                    '<option value="28">User 1 < /option>' +
                    '<option value="29">User 2 < /option>' +
                    '<option value="30">User 3 < /option>' +
                    '<option value="31">User 4 < /option>' +
                    '<option value="32">User 5 < /option>' +
                    '<option value="33">Madhulika Mishra < /option>' +
                    '<option value="34">User 1 < /option>' +
                    '<option value="35">User 2 < /option>' +
                    '<option value="36">User 3 < /option>' +
                    '<option value="37">User 4 < /option>' +
                    '<option value="38">User 5 < /option>' +
                    '<option value="39">Jin Kim < /option>' +
                    '<option value="40">Akash Asthana < /option>' +
                    '<option value="41">manish < /option>' +
                    '</select></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="date" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '</select></td>' +
                    '</tr>';

                return html;
            }

            var tableBody = $('#action_Item_Details-field-instruction-modal tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#capa_Details').click(function(e) {
            function generateTableRow(serialNumber) {

                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><select id="select-state" placeholder="Select..." name="capa_type[]">' +
                    '<option value="">Select a value</option>' +
                    '<option value="corrective">Corrective Action</option>' +
                    '<option value="preventive">Preventive Action</option>' +
                    '<option value="corrective_preventive">Corrective &amp; Preventive Action</option>' +
                    '</select></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><select id="select-state" placeholder="Select..." name="capa_type[]">' +
                    '<option value="">Select a value</option>' +
                    '<option value="1">Amit Guru</option>' +
                    '<option value="2">Esra Hyasat</option>' +
                    '<option value="3">Sondos</option>' +
                    '<option value="4">User 1</option>' +
                    '<option value="5">User 2 </option>' +
                    '<option value="6">User 3 < /option>' +
                    '<option value="7">User 4 < /option>' +
                    '<option value="8">User 5 < /option>' +
                    '<option value="9">Shaleen Mishra < /option>' +
                    '<option value="10">User 1 < /option>' +
                    '<option value="11">User 2 < /option>' +
                    '<option value="12">User 3 < /option>' +
                    '<option value="13">User 4 < /option>' +
                    '<option value="14">User 5 < /option>' +
                    '<option value="15">Vikas Prajapati < /option>' +
                    '<option value="16">User 1 < /option>' +
                    '<option value="17">User 2 < /option>' +
                    '<option value="18">User 3 < /option>' +
                    '<option value="19">User 4 < /option>' +
                    '<option value="20">User 5 < /option>' +
                    '<option value="21">Anshul Patel < /option>' +
                    '<option value="22">User 1 < /option>' +
                    '<option value="23">User 2 < /option>' +
                    '<option value="24">User 3 < /option>' +
                    '<option value="25">User 4 < /option>' +
                    '<option value="26">User 5 < /option>' +
                    '<option value="27">Amit Patel < /option>' +
                    '<option value="28">User 1 < /option>' +
                    '<option value="29">User 2 < /option>' +
                    '<option value="30">User 3 < /option>' +
                    '<option value="31">User 4 < /option>' +
                    '<option value="32">User 5 < /option>' +
                    '<option value="33">Madhulika Mishra < /option>' +
                    '<option value="34">User 1 < /option>' +
                    '<option value="35">User 2 < /option>' +
                    '<option value="36">User 3 < /option>' +
                    '<option value="37">User 4 < /option>' +
                    '<option value="38">User 5 < /option>' +
                    '<option value="39">Jin Kim < /option>' +
                    '<option value="40">Akash Asthana < /option>' +
                    '<option value="41">manish < /option>' +
                    '</select></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '<td><input type="date" name="[]"></td>' +
                    '<td><input type="text" name="[]"></td>' +
                    '</tr>';

                return html;
            }

            var tableBody = $('#capa_Details-field-instruction-modal tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>



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
        const index = Array.from(cctablinks).findIndex(button => button === evt.currentTarget);
        currentStep = index;
    }

    const saveButtons = document.querySelectorAll(".saveButton");
    const nextButtons = document.querySelectorAll(".nextButton");
    const form = document.getElementById("step-form");
    const stepButtons = document.querySelectorAll(".cctablinks");
    const steps = document.querySelectorAll(".cctabcontent");
    let currentStep = 0;

    function nextStep() {
        if (currentStep < steps.length - 1) {
            steps[currentStep].style.display = "none";
            steps[currentStep + 1].style.display = "block";
            stepButtons[currentStep + 1].classList.add("active");
            stepButtons[currentStep].classList.remove("active");
            currentStep++;
        }
    }

    function previousStep() {
        if (currentStep > 0) {
            steps[currentStep].style.display = "none";
            steps[currentStep - 1].style.display = "block";
            stepButtons[currentStep - 1].classList.add("active");
            stepButtons[currentStep].classList.remove("active");
            currentStep--;
        }
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