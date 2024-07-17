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
        / CTMS_Violation
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
                    '<td><input type="text" name="Remarks[]"></td>' +


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
                    '<td><input type="number" name="BatchNumber[]"></td>' +
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
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Protocol Violation</button>
            {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Parent Information</button> --}}
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Violation Information</button>
            <!-- <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Action Approval</button> -->
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
                            Monitor Visit
                        </div> <!-- RECORD NUMBER -->
                        <div class="row">

                            <div class="col-lg-6">
                                @if (!empty($cc->id))
                                <input type="hidden" name="ccId" value="{{ $cc->id }}">
                                @endif
                                <div class="group-input">
                                    <label for="originator">Initiator</label>
                                    <input disabled type="text" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Date Opened">Date of Initiation</label>
                                    {{-- <div class="static">{{ date('d-M-Y') }}
                                </div> --}}
                                <input disabled type="text" value="" name="intiation_date">
                                <input type="hidden" value="" name="intiation_date">
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

                                    <option value="">1</option>

                                </select>



                            </div>
                        </div>
                        <div class="col-md-6 new-date-data-field">
                            <div class="group-input input-date">
                                <label for="due-date">Due Date <span class="text-danger"></span></label>
                                <p class="text-primary">Please mention expected date of completion</p>
                                <!-- <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            value="" name="due_date"> -->
                                <div class="calenderauditee">
                                    <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                    <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    Type <span class="text-danger"></span>
                                </label>
                                <select id="select-state" placeholder="Select..." name="type">
                                    <option value="">Select a value</option>
                                    <option value="">1</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    Other Type <span class="text-danger"></span>
                                </label>
                                <select id="select-state" placeholder="Select..." name="type">
                                    <option value="">Select a value</option>
                                    <option value="">1</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="search">
                                    Related URL <span class="text-danger"></span>
                                </label>
                                <select id="select-state" placeholder="Select..." name="type">
                                    <option value="">Select a value</option>
                                    <option value="">1</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="closure attachment">File Attachments </label>
                                <div><small class="text-primary">
                                    </small>
                                </div>
                                <div class="file-attachment-field">
                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                    <div class="add-btn">
                                        <div>Add</div>
                                        <input type="file" id="myfile" name="Attachment[]" oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="group-input">
                                <label for="Short Description">Description</label>

                                <textarea name="description"></textarea>
                            </div>
                        </div>


                        <div class="sub-head">
                            Location
                        </div>

                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    Zone <span class="text-danger"></span>
                                </label>
                                <select id="select-state" placeholder="Select..." name="zone">
                                    <option value="">Select a value</option>

                                    <option value="">1</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    Country <span class="text-danger"></span>

                                </label>
                                <p class="text-primary">Auto filter according to selected zone</p>
                                <input type="text" />



                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    City <span class="text-danger"></span>
                                </label>
                                <p class="text-primary">Auto filter according to selected country</p>
                                <input type="text" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    State/District <span class="text-danger"></span>
                                </label>
                                <p class="text-primary">Auto selected according to City</p>
                                <select id="select-state" placeholder="Select..." name="zone">
                                    <option value="">Select a value</option>

                                    <option value="">1</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    Site Name <span class="text-danger"></span>
                                </label>

                                <select id="select-state" placeholder="Select..." name="zone">
                                    <option value="">Select a value</option>

                                    <option value="">1</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    Building <span class="text-danger"></span>
                                </label>

                                <select id="select-state" placeholder="Select..." name="zone">
                                    <option value="">Select a value</option>

                                    <option value="">1</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    Floor <span class="text-danger"></span>
                                </label>

                                <select id="select-state" placeholder="Select..." name="zone">
                                    <option value="">Select a value</option>

                                    <option value="">1</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    Room <span class="text-danger"></span>
                                </label>

                                <select id="select-state" placeholder="Select..." name="zone">
                                    <option value="">Select a value</option>

                                    <option value="">1</option>

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

            <div id="CCForm3" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">
                        <div class="sub-head col-12">Violation Information</div>

                        <div class="col-lg-6  new-date-data-field">
                            <div class="group-input input-date">
                                <label for="end_date">Date Occured</lable>
                                    <div class="calenderauditee">
                                        <input type="text" id="end_date" placeholder="DD-MMM-YYYY" />
                                        <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="end_date_checkdate" name="end_date" class="hide-input" oninput="handleDateInput(this, 'end_date');checkDate('start_date_checkdate','end_date_checkdate')" />
                                    </div>


                            </div>
                        </div>

                        <div class="col-lg-6  new-date-data-field">
                            <div class="group-input input-date">
                                <label for="end_date">Notification Date</lable>
                                    <div class="calenderauditee">
                                        <input type="text" id="end_date" placeholder="DD-MMM-YYYY" />
                                        <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="end_date_checkdate" name="end_date" class="hide-input" oninput="handleDateInput(this, 'end_date');checkDate('start_date_checkdate','end_date_checkdate')" />
                                    </div>


                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    Severity Rate <span class="text-danger"></span>
                                </label>
                                <select id="select-state" placeholder="Select..." name="severity_rate">
                                    <option value="">Select a value</option>

                                    <option value=""></option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    Occurance <span class="text-danger"></span>
                                </label>
                                <select id="select-state" placeholder="Select..." name="occurance">
                                    <option value="">Select a value</option>

                                    <option value=""></option>

                                </select>



                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    Detection <span class="text-danger"></span>
                                </label>
                                <select id="select-state" placeholder="Select..." name="detection">
                                    <option value="">Select a value</option>

                                    <option value=""></option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="group-input">
                                <label for="search">
                                    RPN <span class="text-danger"></span>
                                </label>
                                <select id="select-state" placeholder="Select..." name="rpn">
                                    <option value="">Select a value</option>

                                    <option value=""></option>

                                </select>



                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="group-input">
                                <label for="search">
                                    Manufacturer <span class="text-danger"></span>
                                </label>
                                <input type="text" />
                            </div>
                        </div>

                        <div class="group-input">
                            <label for="audit-agenda-grid">
                                Product/Material
                                <button type="button" name="audit-agenda-grid" id="Product_Material">+</button>
                                <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                    (Launch Instruction)
                                </span>
                            </label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="Product_Material_details" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width: 4%">Row#</th>
                                            <th style="width: 12%">Product Name</th>
                                            <th style="width: 16%"> Batch Number</th>
                                            <th style="width: 16%"> Expiry Date</th>
                                            <th style="width: 16%"> Manufactured Date</th>
                                            <th style="width: 16%"> Disposition</th>
                                            <th style="width: 10%"> Comment</th>
                                            <th style="width: 16%"> Remarks</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td><input disabled type="text" name="serial[]" value="1"></td>
                                        <td><input type="text" name="ProductName[]"></td>
                                        <td><input type="number" name="BatchNumber[]"></td>
                                        <td><input type="date" name="ExpiryDate[]"></td>
                                        <td><input type="date" name="ManufacturedDate[]"></td>
                                        <td><input type="text" name="Disposition[]"></td>
                                        <td><input type="text" name="Comment[]"></td>
                                        <td><input type="text" name="Remarks[]"></td>

                                    </tbody>

                                </table>
                            </div>
                        </div>

                        <div class="col-lg-6  new-date-data-field">
                            <div class="group-input input-date">
                                <label for="end_date">Date Sent</lable>
                                    <div class="calenderauditee">
                                        <input type="text" id="end_date" placeholder="DD-MMM-YYYY" />
                                        <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="end_date_checkdate" name="end_date" class="hide-input" oninput="handleDateInput(this, 'end_date');checkDate('start_date_checkdate','end_date_checkdate')" />
                                    </div>


                            </div>
                        </div>

                        <div class="col-lg-6  new-date-data-field">
                            <div class="group-input input-date">
                                <label for="end_date">Date Returned</lable>
                                    <div class="calenderauditee">
                                        <input type="text" id="end_date" placeholder="DD-MMM-YYYY" />
                                        <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="end_date_checkdate" name="end_date" class="hide-input" oninput="handleDateInput(this, 'end_date');checkDate('start_date_checkdate','end_date_checkdate')" />
                                    </div>


                            </div>
                        </div>

                        <div class="col-6">
                            <div class="group-input">
                                <label for="followUp">Follow Up</label>
                                <textarea name="followUp"></textarea>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="group-input">
                                <label for="summary">Summary</label>
                                <textarea name="summary"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="group-input">
                                <label for="Comments">Comments</label>
                                <textarea name="comments"></textarea>
                            </div>
                        </div>


                        {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="Support_doc">Supporting Documents</label>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="Support_doc"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="Support_doc[]"
                                                    oninput="addMultipleFiles(this, 'Support_doc')" multiple>
                                            </div>
                                        </div>

                                    </div>
                                </div> --}}
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
            <!-- 
            <div id="CCForm4" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">
                        <div class="sub-head">Action Approval</div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="qa_comments">QA Review Comments</label>
                                <textarea name="qa_comments"></textarea>
                            </div>
                        </div>
                        {{--
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="file_attach">Final Attachments</label>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="final_attach"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="final_attach[]"
                                                    oninput="addMultipleFiles(this, 'final_attach')" multiple>
                                            </div>
                                        </div>

                                    </div>
                                </div> --}}
                        <div class="col-12 sub-head">
                            Extension Justification
                        </div>

                        <div class="col-12">
                            <div class="group-input">
                                <label for="due-dateextension">Due Date Extension Justification</label>
                                <textarea name="due_date_extension"></textarea>
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

            <div id="CCForm4" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Actual_Amount ">Closed By :</label>
                                    <div class="date"></div>

                                </div>
                            </div>
                            <div class="col-6 pb-3">
                                <div class="group-input">

                                    <label for="Division Code"><b>Closed On :</b></label>
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
    var maxLength = 255;
    $('#docname').keyup(function() {
        var textlen = maxLength - $(this).val().length;
        $('#rchars').text(textlen);
    });
</script>
@endsection