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
        / Incident
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#Monitor_Information').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="date" name="date[]"></td>' +
                    ' <td><input type="text" name="Responsible[]"></td>' +
                    '<td><input type="text" name="ItemDescription[]"></td>' +
                    '<td><input type="date" name="SentDate[]"></td>' +
                    '<td><input type="date" name="ReturnDate[]"></td>' +
                    '<td><input type="text" name="Comment[]"></td>' +


                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                //     '</tr>';

                return html;
            }

            var tableBody = $('#Monitor_Information_details tbody');
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
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">RCA & Corrective Action</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Supervisor & SQA Review</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Reference Info / Comments</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm5')"> Signatures</button>

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
                                    <label for="RLS Record Number"><b>Record Number</b></label>
                                    <input disabled type="text" name="record_number" value="">

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Division Code"><b>Division Code </b></label>
                                    <input disabled type="text" name="division_code" value="">
                                    <input type="hidden" name="division_id" value="">

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="originator">Initiator</label>
                                    <input disabled type="text" name="originator_id" value="" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input ">
                                    <label for="Date Due"><b>Date of Initiation</b></label>
                                    <input disabled type="text" value="" name="intiation_date">
                                    <input type="hidden" value="" name="intiation_date">
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Due Date"> Due Date </label>

                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="" />
                                    </div>


                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Severity Level">Severity Level</label>
                                    <select name="severity_level" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator Group"><b>Initiator Group</b></label>
                                    <select name="initiator_Group" id="initiator_group">
                                        <option value="">-- Select --</option>
                                        <option value="CQA">
                                            Corporate Quality Assurance</option>
                                        <option value="QAB">Quality
                                            Assurance Biopharma</option>
                                        <option value="CQC">Central
                                            Quality Control</option>
                                        <option value="MANU">
                                            Manufacturing</option>
                                        <option value="PSG">Plasma
                                            Sourcing Group</option>
                                        <option value="CS">Central
                                            Stores</option>
                                        <option value="ITG">
                                            Information Technology Group</option>
                                        <option value="MM">
                                            Molecular Medicine</option>
                                        <option value="CL">
                                            Central Laboratory</option>

                                        <option value="TT">Tech
                                            team</option>
                                        <option value="QA">
                                            Quality Assurance</option>
                                        <option value="QM">
                                            Quality Management</option>
                                        <option value="IA">IT
                                            Administration</option>
                                        <option value="ACC">
                                            Accounting</option>
                                        <option value="LOG">
                                            Logistics</option>
                                        <option value="SM">
                                            Senior Management</option>
                                        <option value="BA">
                                            Business Administration</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator Group Code">Initiator Group Code</label>
                                    <input type="text" name="initiator_group_code" id="initiator_group_code" value="">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Initiated Through">Initiated Through </label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="initiated_through" id="summernote-1">
                                    </textarea>
                                    </div>
                                </div>
                     

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Source Document Type">If Others</label>
                                    <select name="tource_document_type" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Is Repeat">Is Repeat </label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="initiated_through" id="summernote-1">
                                    </textarea>
                                    </div>
                                </div>
                        
                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Repeat Nature">Repeat Nature</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="initiated_through" id="summernote-1">
                                    </textarea>
                                    </div>
                                </div>
                           

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Nature of Change">Nature of Change</label>
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

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Deviation Occured On">Deviation Occured On</label>

                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="" />
                                    </div>


                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Description">Description</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="initiated_through" id="summernote-1">
                                    </textarea>
                                    </div>
                                </div>

                           

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Initial Attachment">Initial Attachment</label>
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
                                    <label for="If Others">Source Document Type</label>
                                    <select name="initiated_if_other" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for=" Reference Document"> Reference Document </label>
                                    <input id="docname" type="text" name="reference_document">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Reference System Document">Reference System Document</label>
                                        <select multiple id="reference_system_document" name="" id="">
                                            <option value="">--Select---</option>
                                         
                                                <option value="">
                                              
                                                </option>
                                         
                                        </select>
                                    </div>
                                </div>

                                <div class="sub-head">Cancellation</div>
                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Cancellation Justification">Cancellation Justification</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="initiated_through" id="summernote-1">
                                    </textarea>
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
                </div>
                <div id="CCForm2" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="sub-head col-12">RCA</div>

                            
                            <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="True RC/Most Probable RC">True RC/Most Probable RC</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="True_RC/Most_Probable_RC" id="summernote-1">
                                    </textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Identified Root cause?">Identified Root cause?</label>
                                    <select name="Identified_Root_cause?" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>
                          
                            <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="If No., Justify">If No., Justify</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="If_No_Justify" id="summernote-1">
                                    </textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Cause Category">Cause Category</label>
                                    <select name="Cause_Category" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Cause Category, If Other's">Cause Category, If Other's</label>
                                    <input type="text" name="Cause_Category_If_Other's" id="Cause_Category_If_Other's" value="">
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Cause Sub-Category">Cause Sub-Category</label>
                                    <select name="Cause_Sub-Category" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Cause Sub Category, If Others">Cause Sub Category, If Others</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="Cause_Sub_Category_If_Others" id="summernote-1">
                                    </textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Cause Type">Cause Type</label>
                                    <select name="Cause_Type" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Final RCA Summary">Final RCA Summary</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="Final_RCA_Summary" id="summernote-1">
                                    </textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                <div class="group-input">
                                    <label for="Attachment">Attachment</label>
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


<div class="sub-head">Historical Search</div>

<div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Incident repeated">Incident repeated</label>
                                    <select name="Incident_repeated" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Incident No. of repeats">Incident No. of repeats</label>
                                    <select name="Incident_No_of_repeats" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Repeat Trend Investigation Req">Repeat Trend Investigation Req</label>
                                    <select name="Repeat_Trend_Investigation_Req" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Escalation Notification To">Escalation Notification To</label>
                                        <select multiple id="Escalation_Notification_To" name="" id="">
                                            <option value="">--Select---</option>
                                         
                                                <option value="">
                                              
                                                </option>
                                         
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="If No - Justification">If No - Justification</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="If_No_Justification" id="summernote-1">
                                    </textarea>
                                    </div>
                                </div>

<div class="sub-head">Correction Action</div>

<div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Incident Correction ">Incident Correction </label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="Incident_Correction " id="summernote-1">
                                    </textarea>
                                    </div>
                                </div>
                            
                                <div class="col-12">
                                <div class="group-input">
                                    <label for="Root Cause & CA Attachment">Root Cause & CA Attachment</label>
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

                   
                        <div class="sub-head">Area Supervisor Review</div>
                        <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Area Supervisor Rev. Comments">Area Supervisor Rev. Comments</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="Area_Supervisor_Rev_Comments" id="summernote-1">
                                    </textarea>
                                    </div>
                                </div>

                      <div class="sub-head">SQA Review  & Assessment</div>

                      <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="SQA Final Review Comments">SQA Final Review Comments</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="SQA_Final_Review_Comments" id="summernote-1">
                                    </textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Impact on GxP?">Impact on GxP?</label>
                                    <select name="Impact_on_GxP?" onchange="">
                                        <option value="">-- select --</option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Impact to ">Impact to </label>
                                        <select multiple id="Impact_to" name="" id="">
                                            <option value="">--Select---</option>
                                         
                                                <option value="">
                                              
                                                </option>
                                         
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Route to Deviation Justi">Route to Deviation Justi</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="Route_to_Deviation_Justi" id="summernote-1">
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
            <div id="CCForm4" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">
                        <div class="sub-head">Comments</div>

                        <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Referenced Records">Referenced Records</label>
                                        <select multiple id="Referenced_Records" name="" id="">
                                            <option value="">--Select---</option>
                                         
                                                <option value="">
                                              
                                                </option>
                                         
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Comments">Comments</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea class="summernote" name="Comments" id="summernote-1">
                                    </textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                <div class="group-input">
                                    <label for="Attached File">Attached File</label>
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
                    <div class="sub-head">
                    Activity Log
                    </div>
                    <div class="row">

                    <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Submit by">Submit by : </label>
                                   
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Submit on"> Submit on :</label>

                                    


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Init. Imp. Asses. Rev. Comp By">Init. Imp. Asses. Rev. Comp By : </label>
                                    
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Init_Imp_Asses_Rev_Comp_on"> Init. Imp. Asses. Rev. Comp on :</label>

                                  


                                </div>
                            </div>
                           

                           
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RCA & Corrective Action Complete by">RCA & Corrective Action Complete by  :</label>
                                    
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="RCA & Corrective Action Complete on"> RCA & Corrective Action Complete on :</label>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supervisor Review Complete by">Supervisor Review Complete by : </label>
                                    
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Supervisor Review Complete on"> Supervisor Review Complete on :</label>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="SQA Review & Assessment Complete by">SQA Review & Assessment Complete by :</label>
                                   
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="SQA Review & Assessment Complete on"> SQA Review & Assessment Complete on :</label>

                                    


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Cancellation Request by">Cancellation Request by : </label>
                                    
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Cancellation Request by"> Cancellation Request by :</label>

                                   
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Cancellation Approve by">Cancellation Approve by :</label>
                                    
                                </div>
                            </div>
                        
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Cancellation Approve on"> Cancellation Approve on :</label>

                                  


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