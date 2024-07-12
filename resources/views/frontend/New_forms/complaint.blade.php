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
        / Complaint
    </div>
</div>



{{-- ! ========================================= --}}
{{-- !               DATA FIELDS                 --}}
{{-- ! ========================================= --}}
<div id="change-control-fields">
    <div class="container-fluid">

        <!-- Tab links -->
        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Complaint</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Patient Information</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Risk Assessment</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Investigation & Root Cause</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Decision Tree</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Complaint Closer</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Signatures</button>
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
                                    <label for="Initiation"><b>Date of Initiation</b></label>
                                    <input disabled type="date" name="Date_of_Initiation" value="">
                                    <input type="hidden" name="division_id" value="">
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

                            <div class="sub-head">
                                Complaint Details
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Type">Type</label>
                                    <select name="Type">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">Type 1</option>
                                        <option value="2">Type 2</option>
                                        <option value="3">Type 3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="How_Initiated">How Initiated</label>
                                    <select name="How_Initiated">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Customer_Name">Customer Name</label>
                                    <input type="text" name="Customer_Name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Customer_organization">Customer Organization</label>
                                    <input type="text" name="Customer_organization">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Outcome">Outcome</label>
                                    <select name="Outcome">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Patient_Involved">Patient Involved</label>
                                    <select name="Patient_Involved">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Reporter">Reporter</label>
                                    <input type="text" name="Reporter">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Priority_level">Priority Level</label>
                                    <input type="text" name="Priority_level">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Date_Occurred">Date Occurred</label>
                                    <input type="date" name="Date_Occurred">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Date_of_Reporting">Date of Reporting</label>
                                    <input type="date" name="Date_of_Reporting">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Description">Description</label>
                                    <textarea name="Description" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Comments">Comments</label>
                                    <textarea name="Comments" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="sub-head">
                                Location Information
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Event_Location">Event Location</label>
                                    <select name="Event_Location">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Other_Event_Location">Other Event Location</label>
                                    <input type="text" name="Other_Event_Location">
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
                                    <input type="text" name="Country">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="State_District">State/District</label>
                                    <input type="text" name="State_District">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="City">City</label>
                                    <select name="City">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Department">Department(s)</label>
                                    <select name="Department">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Countries">Countries</label>
                                    <select name="Countries">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sub-head">
                                Product Information
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Manufacturer">Manufacturer</label>
                                    <input type="text" name="Manufacturer">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Reported_Manufacturer">Reported to Manufacturer</label>
                                    <select name="Reported_Manufacturer">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Product/Material(0)
                                    <button type="button" name="audit-agenda-grid" id="Product_Material">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="Product-Material-field-instruction-modal">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Product Name</th>
                                                <th style="width: 16%">Batch Number</th>
                                                <th style="width: 16%">Expiry Date</th>
                                                <th style="width: 16%">ManuFactured Date</th>
                                                <th style="width: 15%">Disposition</th>
                                                <th style="width: 15%">Comment</th>
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

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Related_Suppliers">Related Suppliers</label>
                                    <select name="Related_Suppliers">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Trade_name">Trade Name</label>
                                    <input type="text" name="Trade_name">
                                </div>
                            </div>

                            <div class="sub-head">
                                Additional Data
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Attached_Files">Attached Files</label>
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
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Attached_Pictures">Attached Pictures</label>
                                    <div>
                                        <small class="text-primary">
                                            Please Attach all relevant or supporting documents
                                        </small>
                                    </div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id=""></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Attached_Pictures" oninput="" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Related_URLs">Related URLs</label>
                                    <select name="Related_URLs">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Web_Search">Web Search</label>
                                    <input type="text" name="Web_Search">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Related_Events">Related Events</label>
                                    <input type="text" name="Related_Events">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Similar_Complains">Similar Complains</label>
                                    <input type="text" name="Similar_Complains">
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
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Patient_Involved">Patient Involved?</label>
                                    <select name="Patient_Involved">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Protocol">Hospital Name</label>
                                    <input type="text" name="Hospital Name">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Patient_ID">Patient ID</label>
                                    <input type="text" name="Patient_ID">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Patient_name">Patient Name</label>
                                    <input type="text" name="Patient_name">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <input type="date" name="date_of_birth">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="gender">Gender</label>
                                    <select name="gender">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="">Male</option>
                                        <option value="">Female</option>
                                        <option value="">Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="age_at_time">Age at Time of Event</label>
                                    <input type="text" name="age_at_time">
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Height">Height</label>
                                    <input type="text" name="Height">
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Height_units">Height Units</label>
                                    <input type="text" name="Height_units">
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Weight">Weight</label>
                                    <input type="text" name="Weight">
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Weight_units">Weight Units</label>
                                    <input type="text" name="Weight_units">
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Cause_of_Death">Cause of Death</label>
                                    <input type="text" name="Cause_of_Death">
                                </div>
                            </div>


                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="date_of_death">Date of Death</label>
                                    <input type="date" name="date_of_death">
                                </div>
                            </div>

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Treatment of Adverse Reaction(0)
                                    <button type="button" name="audit-agenda-grid" id="Treatment_of_Adverse_Reaction">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="Treatment_of_Adverse_Reaction-instruction-modal">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Treatment Type</th>
                                                <th style="width: 16%">Treatment</th>
                                                <th style="width: 16%">Date Start</th>
                                                <th style="width: 16%">Date End</th>
                                                <th style="width: 15%">Comment</th>
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
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Relevant_History">Relevant History</label>
                                    <textarea name="Relevant_History" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Treatment_Therapy">Treatment/Therapy Summary</label>
                                    <textarea name="Treatment_Therapy" id="" cols="30" rows="3"></textarea>
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

                <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="safety_impact_Probability">Safety Impact Probability</label>
                                    <select name="safety_impact_Probability">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="safety_impact_Severity">Safety Impact Severity</label>
                                    <select name="safety_impact_Severity">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="legal_impact_Probability">Legal Impact Probability</label>
                                    <select name="legal_impact_Probability">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="legal_impact_Severity">Legal Impact Severity</label>
                                    <select name="legal_impact_Severity">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Business_impact_Probability">Business Impact Probability</label>
                                    <select name="Business_impact_Probability">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Business_impact_Severity">Business Impact Severity</label>
                                    <select name="Business_impact_Severity">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Revenue_impact_Probability">Revenue Impact Probability</label>
                                    <select name="Revenue_impact_Probability">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Revenue_impact_Severity">Revenue Impact Severity</label>
                                    <select name="Revenue_impact_Severity">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Brand_impact_Probability">Brand Impact Probability</label>
                                    <select name="Brand_impact_Probability">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Brand_impact_Severity">Brand Impact Severity</label>
                                    <select name="Brand_impact_Severity">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sub-head">
                                Calculated Risk and Further Actions
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Safety_Impact_Risk">Safety Impact Risk</label>
                                    <div><small class="text-primary">Acceptable- Risks Nigligible, Further Effort not justified; consider product improvement</small></div>
                                    <select name="Safety_Impact_Risk">
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="legal_Impact_Risk">Legal Impact Risk</label>
                                    <div><small class="text-primary">Mostly Acceptable- Risks Nigligible, Further Effort not justified; consider product improvement</small></div>
                                    <select name="legal_Impact_Risk">
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Business_Impact_Risk">Business Impact Risk</label>
                                    <div><small class="text-primary">Tolerable- Risks can be acceptable,Further Effort can be considered in case of safety issues; consider CAPA</small></div>
                                    <select name="Business_Impact_Risk">
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Revenue_Impact_Risk">Revenue Impact Risk</label>
                                    <div><small class="text-primary">Mostly Unacceptable-Risks not justified with few exceptions; CAPA will be created</small></div>
                                    <select name="Revenue_Impact_Risk">
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Brand_Impact_Risk">Brand Impact Risk</label>
                                    <div><small class="text-primary">Unacceptable- Risk not justified; CAPA will be created</small></div>
                                    <select name="Brand_Impact_Risk">
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sub-head">
                                Overall Assessment
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Impact">Impact</label>
                                    <select name="Impact">
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Criticality">Criticality</label>
                                    <select name="Criticality">
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Impact_Analysis">Impact Analysis</label>
                                    <textarea name="Impact_Analysis" id="" cols="30" rows="5"></textarea>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="risk_Analysis">Risk Analysis</label>
                                    <textarea name="risk_Analysis" id="" cols="30" rows="5"></textarea>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Severity_Rate">Severity Rate</label>
                                    <select name="Severity_Rate">
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Occurrence">Occurrence</label>
                                    <select name="Occurrence">
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Detection">Detection</label>
                                    <select name="Detection">
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="RPN">RPN</label>
                                    <select name="RPN">
                                        <option value="">--select--</option>
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

                <div id="CCForm4" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="sub-head">Investigation</div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Lead_investigator"><b>Lead Investigator</b></label>
                                    <input type="text" name="Lead_investigator" value="">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Investigation_Summary"><b>Investigation Summary</b></label>
                                    <textarea name="Investigation_Summary" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Conclusion"><b>Conclusion</b></label>
                                    <textarea name="Conclusion" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="sub-head">Root Cause Analysis</div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Root_Cause_Methodology"><b>Root Cause Methodology</b></label>
                                    <select>
                                        <option value="">Select a value</option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Root Cause(0)
                                    <button type="button" name="audit-agenda-grid" id="Root_Cause">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="Root_Cause-field-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Root Cause Category</th>
                                                <th style="width: 16%">Root Cause Sub Category</th>
                                                <th style="width: 16%">Probability</th>
                                                <th style="width: 15%">Comment</th>
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

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Root_Cause_Description"><b>Root Cause Description</b></label>
                                    <textarea name="Root_Cause_Description" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="sub-head">Tasks and Additional Info</div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Justified"><b>Justified</b></label>
                                    <select>
                                        <option value="">Select a value</option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Justified"><b>Date Requested</b></label>
                                    <input type="date" name="Justified" id="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Justified"><b>Date Received</b></label>
                                    <input type="date" name="Justified" id="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Sample_Received"><b>Sample Received</b></label>
                                    <select name="Sample_Received">
                                        <option value="">Select a value</option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Sample_Received_date"><b>Sample Received Date</b></label>
                                    <input type="date" name="Sample_Received_date" id="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Relevant_Test_Lab_Data"><b>Relevant Test/Lab Data</b></label>
                                    <textarea name="Relevant_Test_Lab_Data" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Team_Members"><b>Team Members</b></label>
                                    <textarea name="Team_Members" id="" cols="30" rows="3"></textarea>
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

                <div id="CCForm5" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Outcome">Outcome</label>
                                    <select name="Outcome">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Outcome">Adverse Event/Product Problem</label>
                                    <select name="Outcome">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Adverse_Event_Type">Adverse Event Type</label>
                                    <select name="Adverse_Event_Type">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Product_Makers">Product Makers</label>
                                    <select name="Product_Makers">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Authorities_to_Report">Authorities to Report</label>
                                    <select name="Authorities_to_Report">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Type_of_report">Type of Report</label>
                                    <textarea name="Type_of_report" id="" cols="30" rows="3"></textarea>
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

                <div id="CCForm6" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Action_Plan">Action Plan</label>
                                    <input type="Action_Plan" name="" id="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Auto_create_action_paln">Auto-create Action Plan?</label>
                                    <select name="Auto_create_action_paln">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Related_Actions">Related Actions</label>
                                    <select name="Related_Actions">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Actions_taken">Actions Taken</label>
                                    <textarea name="Actions_taken" id="" cols="30" rows="3"></textarea>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Response_to_Customer">Response to Customer</label>
                                    <textarea name="Response_to_Customer" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="col-12 sub-head">
                                Responsible Suppliers
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Related_Suppliers">Related Suppliers</label>
                                    <select name="Related_Suppliers">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Demerit Points">Demerit Points</label>
                                </div>
                                <div>
                                    <div class="container">
                                        <input type="radio" class="radio-input" name="option" value="option1">
                                        <label class="radio-label">0-50</label>
                                    </div>

                                    <div class="container">
                                        <input type="radio" class="radio-input" name="option" value="option2">
                                        <label class="radio-label">50-100</label>
                                    </div>

                                    <div class="container">
                                        <input type="radio" class="radio-input" name="option" value="option3">
                                        <label class="radio-label">100-150</label>
                                    </div>
                                    <a href="#" id="clearSelection">Clear Selection</a>
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

                <div id="CCForm7" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="sub-head">
                                Signatures
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="submitted by">Submitted By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="submitted on">Submitted On</label>
                                    <div class="Date"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Reviewed by">Reviewed By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Reviewed on">Reviewed On</label>
                                    <div class="Date"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Plan_Approved_by">Plan Approved By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Plan_Approved_on">Plan Approved On</label>
                                    <div class="Date"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Letter_by">Letter Sent By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Letter_on">Letter Sent On</label>
                                    <div class="Date"></div>
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
    document.getElementById('clearSelection').addEventListener('click', function() {
        var radios = document.querySelectorAll('input[type="radio"]');
        for (var i = 0; i < radios.length; i++) {
            radios[i].checked = false;
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('#Treatment_of_Adverse_Reaction').click(function(e) {
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
                    '</tr>';

                return html;
            }

            var tableBody = $('#Treatment_of_Adverse_Reaction-instruction-modal tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#Root_Cause').click(function(e) {
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

            var tableBody = $('#Root_Cause-field-table tbody');
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

            var tableBody = $('#Product-Material-field-instruction-modal tbody');
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