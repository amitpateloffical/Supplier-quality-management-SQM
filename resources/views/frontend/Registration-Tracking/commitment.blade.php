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

<script>
    $(document).ready(function() {
        $('#ReferenceDocument').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +


                    '<td><input type="text" name="Action[]"></td>' +
                    '<td><input type="text" name="Responsible[]"></td>' +
                    '<td><input type="date" name="Deadline[]"></td>' +
                    '<td><input type="text" name="ItemStatus[]"></td>' +
                    '<td><input type="text" name="Remarks[]"></td>' +



                    //     '</tr>';

                    // for (var i = 0; i < users.length; i++) {
                    //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    // }

                    // html += '</select></td>' + 

                    '</tr>';

                return html;
            }

            var tableBody = $('#ReferenceDocument_details tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>

<div class="form-field-head">
    {{-- <div class="pr-id">
            New Child
        </div> --}}
    <div class="division-bar">
        <strong>Site Division/Project</strong> :
        / RT-Commitment
    </div>
</div>



{{-- ! ========================================= --}}
{{-- !               DATA FIELDS                 --}}
{{-- ! ========================================= --}}
<div id="change-control-fields">
    <div class="container-fluid">

        <!-- Tab links -->
        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Commitment Information</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Contact Tracking Information</button>

            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Signatures</button>

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


                                    <label for="RLS Record Number"><b>(Parent) Member State</b></label>

                                    <input type="text" name="record_number" value="">


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">


                                    <label for="RLS Record Number"><b>(Root Parent) Trade Name</b></label>

                                    <input type="text" name="record_number" value="">


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">


                                    <label for="RLS Record Number"><b>Initiator</b></label>

                                    <input type="text" disabled name="record_number" value="">


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Division Code"><b>Date of Initiation</b></label>

                                    <input type="date" disabled name="division_code" value="">

                                </div>
                            </div>


                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Short Description">Short Description<span class="text-danger">*</span>
                                        <p>255 characters remaining </p>
                                        <input id="docname" type="text" name="short_description" maxlength="255" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">Assigned To</label>
                                    <select name="departments">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">Type</label>
                                    <select name="departments">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="due-date">Date Due <span class="text-danger"></span></label>
                                    <p class="text-primary">Please mention expected date of completion</p>
                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 new-date-data-field pt-3">
                                <div class="group-input input-date">
                                    <label for="due-date">(Parent) Date Due to Authority <span class="text-danger"></span></label>

                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                                    </div>
                                </div>
                            </div>
                            <div class="sub-head">Commitment Plan</div>
                            <p class="text-primary">Important Dates</p>
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

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Action Plan (0)
                                    <button type="button" name="audit-agenda-grid" id="ReferenceDocument">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (open)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="ReferenceDocument_details" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 4%">Row#</th>

                                                <th style="width: 16%">Action</th>
                                                <th style="width: 16%">Responsible</th>
                                                <th style="width: 16%">Deadline</th>
                                                <th style="width: 16%">Item Status</th>
                                                <th style="width: 16%">Remarks</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input disabled type="text" name="serial[]" value="1"></td>

                                                <td><input type="text" name="Action[]"></td>
                                                <td><input type="text" name="Responsible[]"></td>
                                                <td><input type="date" name="Deadline[]"></td>
                                                <td><input type="text" name="ItemStatus[]"></td>
                                                <td><input type="text" name="Remarks[]"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>


                            <div class=" pt-3 col-lg-6">
                                <div class="group-input">

                                    <label for="RLS Record Number"><b>Estimated Man-Hours</b></label>

                                    <input type="text" name="record_number" value="">


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Audit Attachments">File Attachments</label>
                                    <small class="text-primary">
                                        Please Attach all relevant or supporting documents
                                    </small>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="file_attach"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="file_attach[]" oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="sub-head">Commitment Summary</div>
                            <p class="text-primary">The main commitment steps and findings</p>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Actions">Summary<span class="text-danger"></span></label>
                                    <textarea placeholder="" name="description"></textarea>
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

                <!-- TAB 1 ENDS HERE -->

                <div id="CCForm2" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="pt-2 col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">(Parent) Priority Level</label>
                                    <select name="departments">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>(Parent) Local Trade Name</b></label>
                                    <p class="text-primary">Person responsible</p>
                                    <input type="text" name="record_number" value="">

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">Parent Authority</label>
                                    <select name="departments">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">(Parent) Authority Type</label>
                                    <select name="departments">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Actions">(Parent) Description <span class="text-danger"></span></label>
                                    <textarea name="description"></textarea>
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

                <!-- <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="sub-head">Audir Summary</div>
                            <div class="col-6">
                                <div class="group-input">

                                    <label for="Division Code"><b>Actual start Date</b></label>

                                    <input type="date" name="division_code" value="">


                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">

                                    <label for="Division Code"><b>Actual end Date</b></label>

                                    <input type="date" name="division_code" value="">


                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Actions">Executive Summary <span class="text-danger"></span></label>
                                    <textarea name="description"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">Audit Result</label>
                                    <select name="departments">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div class="sub-head"> Response Summary</div>
                            <div class="col-6">
                                <div class="group-input">

                                    <label for="Division Code"><b>Date of Response</b></label>

                                    <input type="date" name="division_code" value="">


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">Attached File</label>
                                    <select name="departments">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Actions">Response Summary <span class="text-danger"></span></label>
                                    <textarea name="description"></textarea>
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
                </div> -->

                <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Actual_Amount ">Completed by :</label>
                                    <div class="date"></div>

                                </div>
                            </div>
                            <div class="col-6 pb-3">
                                <div class="group-input">

                                    <label for="Division Code"><b>Completed on :</b></label>
                                    <div class="date"></div>



                                </div>
                            </div>
                           
                           



                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            
                            <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                    Exit </a> </button>
                        </div>
                    </div>
                </div>

                <div id="CCForm5" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="sub-head">Risk Factors</div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Safety_Impact_Probability">Safety Impact Probability</label>
                                    <select name="Safety_Impact_Probability">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Safety_Impact_Severity">Safety Impact Severity</label>
                                    <select name="Safety_Impact_Severity">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Legal_Impact_Probability">Legal Impact Probability</label>
                                    <select name="Legal_Impact_Probability">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Legal_Impact_Severity">Legal Impact Severity</label>
                                    <select name="Legal_Impact_Severity">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Business_Impact_Probability">Business Impact Probability</label>
                                    <select name="Business_Impact_Probability">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Business_Impact_Severity">Business Impact Severity</label>
                                    <select name="Business_Impact_Severity">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Revenue_Impact_Probability">Revenue Impact Probability</label>
                                    <select name="Revenue_Impact_Probability">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Revenue_Impact_Severity">Revenue Impact Severity</label>
                                    <select name="Revenue_Impact_Severity">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Brand_Impact_Probability">Brand Impact Probability</label>
                                    <select name="Brand_Impact_Probability">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Brand_Impact_Severity">Brand Impact Severity</label>
                                    <select name="Brand_Impact_Severity">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 sub-head">
                                Calculated Risk and Further Actions
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Safety_Impact_Risk">Safety Impact Risk</label>
                                    <select name="Safety_Impact_Risk">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Legal_Impact_Risk">Legal Impact Risk</label>
                                    <select name="Legal_Impact_Risk">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Business_Impact_Risk">Business Impact Risk</label>
                                    <select name="Business_Impact_Risk">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Revenue_Impact_Risk">Revenue Impact Risk</label>
                                    <select name="Revenue_Impact_Risk">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Brand_Impact_Risk">Brand Impact Risk</label>
                                    <select name="Brand_Impact_Risk">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sub-head">
                                General Risk Information
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Impact">Impact</label>
                                    <select name="Impact">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Impact_Analysis">Impact Analysis</label>
                                    <textarea name="Impact_Analysis" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Recommended_Action">Recommended Action</label>
                                    <textarea name="Recommended_Action" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Comments">Comments</label>
                                    <textarea name="Comments" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Direct_Cause">Direct Cause</label>
                                    <select name="Direct_Cause">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Safeguarding">Safeguarding Measure Taken</label>
                                    <select name="Safeguarding">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sub-head">
                                Root Cause Analysis
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Permanent">Root cause Methodlogy</label>
                                    <select name="Permanent">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Root Cause(0)
                                    <button type="button" name="audit-agenda-grid" id="RootCause">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#RootCause-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="RootCause-field-instruction-modal">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Root Cause Category</th>
                                                <th style="width: 16%"> Root Cause Sub Category</th>
                                                <th style="width: 16%"> Probability</th>
                                                <th style="width: 16%"> Comments</th>
                                                <th style="width: 15%">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>
                                            <td><input type="text" name="IDnumber[]"></td>
                                            <td> <input type="text" name=""></td>
                                            <td> <input type="text" name=""></td>
                                            <td> <input type="text" name=""></td>
                                            <td><input type="text" name="Remarks[]"></td>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Root_cause_Description">Root cause Description</label>
                                    <textarea name="Root_cause_Description" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="sub-head">
                                Risk Analysis
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Severity_Rate">Severity Rate</label>
                                    <select name="Severity_Rate">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Occurrence">Occurrence</label>
                                    <select name="Occurrence">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Detection">Detection</label>
                                    <select name="Detection">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="RPN">RPN</label>
                                    <select name="RPN">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Risk_Analysis">Risk Analysis</label>
                                    <textarea name="Risk_Analysis" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Criticality">Criticality</label>
                                    <select name="Criticality">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Inform_Local_Authority">Inform Local Authority?</label>
                                    <select name="Inform_Local_Authority">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Authority_Type">Authority Type</label>
                                    <select name="Authority_Type">
                                        <option value="">--select--</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Actions">(Parent) Description <span class="text-danger"></span></label>
                                    <textarea name="description"></textarea>
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
            </div>

            <div id="CCForm6" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="sub-head">
                        Electronic Signatures
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
                            <label for="cancelled by">Plan Approved By</label>
                            <div class="static"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="cancelled on">Plan Approved On</label>
                            <div class="Date"></div>
                        </div>
                    </div>

                    <!-- <div class="button-block">
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="submit" class="saveButton">Save</button>
                        <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                            </a> </button>
                    </div> -->
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
        $('#Witness_details').click(function(e) {
            function generateTableRow(serialNumber) {

                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="WitnessName[]"></td>' +
                    '<td><input type="text" name="WitnessType[]"></td>' +
                    '<td><input type="text" name="ItemDescriptions[]"></td>' +
                    '<td><input type="text" name="Comments[]"></td>' +
                    '<td><input type="text" name="Remarks[]"></td>' +
                    '</tr>';

                return html;
            }

            var tableBody = $('#Witness_details_details tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#MaterialsReleased').click(function(e) {
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

            var tableBody = $('#MaterialsReleased-field-table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#RootCause').click(function(e) {
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

            var tableBody = $('#RootCause-field-instruction-modal tbody');
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