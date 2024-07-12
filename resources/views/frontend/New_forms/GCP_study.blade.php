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
        / GCP Study
    </div>
</div>



{{-- ! ========================================= --}}
{{-- !               DATA FIELDS                 --}}
{{-- ! ========================================= --}}
<div id="change-control-fields">
    <div class="container-fluid">

        <!-- Tab links -->
        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">GCP Study</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">GCP Details</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Important Dates</button>

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
                        <div class="row">
                            <div class="sub-head">
                                General Information
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>Initiator</b></label>
                                    <input type="text" name="record_number" value="">

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Date of Initiation"><b>Date of Initiation</b></label>
                                    <div><span class="text-primary">When was this record opened?</span>
                                    </div>
                                    <input disabled type="date" name="Date_of_Initiation" value="">
                                    <input type="hidden" name="division_id" value="">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Short Description">Short Description</label>
                                    <p class="text-primary">Short Description to be presented on dekstop</p>
                                    <input id="docname" type="text" name="short_description" maxlength="255" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="search">
                                        Assigned To <span class="text-danger"></span>
                                    </label>
                                    <p class="text-primary">Person responsible</p>
                                    <select id="select-state" placeholder="Select..." name="assign_to">
                                        <option value="">Select a value</option>

                                        <option value=""></option>

                                    </select>

                                </div>
                            </div>
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="due-date">Date Due <span class="text-danger"></span></label>
                                    <div><small class="text-primary">Please mention expected date of completion</small></div>
                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="group-input">
                                    <label for="search">
                                        Department(s)<span class="text-danger"></span>
                                    </label>
                                    <p class="text-primary">Add all the related departments</p>
                                    <select id="select-state" placeholder="Select..." name="assign_to">
                                        <option value="">Select a value</option>

                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="sub-head">
                                Study Details
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">Study Number</label>
                                    <select name="departments">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">Name of Product</label>
                                    <input type="text" name="record_number" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">Study Title</label>
                                    <input type="text" name="record_number" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">Study type</label>
                                    <select name="departments">
                                        <option value="">Enter Your Selection Here</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Short Description">Study Protocol Number</label>
                                    <input id="docname" type="text">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <p class="text-primary">Detailed Description</p>
                                    <label for="Responsible Department">Description</label>
                                    <input type="text" name="record_number" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label class="mb-4" for="Responsible Department">Comments</label>
                                    <input type="text" name="record_number" value="">
                                </div>
                            </div>
                            <div class="sub-head">
                                Additional Information
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">Related studies</label>
                                    <p class="text-primary">Link between study records related to the same study type or topic</p>
                                    <select name="departments">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label class="mb-4" for="Responsible Department">Document Link</label>
                                    <input type="text" name="record_number" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">Appendiceis</label>
                                    <select name="departments">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Responsible Department">Related Audits</label>
                                    <select name="departments">
                                        <option value=""></option>
                                    </select>
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
            </div>
            <!-- ============================================================================================================== -->
            <div id="CCForm2" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">
                        <div class="sub-head col-12">GCP Details</div>
                        <div class="col-lg-6 new-date-data-field">
                            <div class="group-input input-date">
                                <label for="start_date">Generic Product Name</label>
                                <div class="calenderauditee">
                                    <input type="text" id="start_date" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 new-date-data-field">
                            <div class="group-input input-date">
                                <label for="start_date">Indication Name</label>
                                <div class="calenderauditee">
                                    <input type="text" id="start_date" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 new-date-data-field">
                            <div class="group-input input-date">
                                <label for="start_date">Clinical Study Manager</label>
                                <div class="calenderauditee">
                                    <input type="text" id="start_date" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 new-date-data-field">
                            <div class="group-input input-date">
                                <label for="start_date">Clinical Expert</label>
                                <div class="calenderauditee">
                                    <input type="text" id="start_date" readonly />
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="group-input">
                                <label for="Responsible Department">Phase Level</label>
                                <select name="departments">
                                    <option value="">Enter Your Selection Here</option>
                                </select>
                            </div>
                        </div>




                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Responsible Department">Therapeutic Area</label>
                                <select name="departments">
                                    <option value="">Enter Your Selection Here</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 new-date-data-field">
                            <div class="group-input input-date">
                                <label for="start_date">IND No.</label>
                                <div class="calenderauditee">
                                    <input type="text" id="start_date" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 new-date-data-field">
                            <div class="group-input input-date">
                                <label for="start_date">Number of Centers</label>
                                <div class="calenderauditee">
                                    <input type="text" id="start_date" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 new-date-data-field">
                            <div class="group-input input-date">
                                <label for="start_date">#of Subjects</label>
                                <div class="calenderauditee">
                                    <input type="text" id="start_date" readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="group-input">
                        <label for="audit-agenda-grid">
                            Audit Site Information(0)
                            <button type="button" name="audit-agenda-grid" id="AuditSiteInformation">+</button>
                            <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                (Launch Instruction)
                            </span>
                        </label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="AuditSiteInformation_details" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 4%">Row#</th>
                                        <th style="width: 12%">N</th>
                                        <th style="width: 16%">Audit Frequency</th>
                                        <th style="width: 16%"> Current</th>
                                        <th style="width: 16%"> CRO</th>
                                        <th style="width: 16%">Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td><input disabled type="text" name="serial[]" value="1"></td>
                                    <td><input type="text" name="Number[]"></td>
                                    <td><input type="text" name="ReferenceDocumentName[]"></td>
                                    <td><input type="text" name="[]"></td>
                                    <td><input type="text" name="[]"></td>
                                    <td><input type="text" name="Remarks[]"></td>
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="group-input">
                        <label for="audit-agenda-grid">
                            Study Site Information(0)
                            <button type="button" name="audit-agenda-grid" id="StudySiteInformation">+</button>
                            <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                (Launch Instruction)
                            </span>
                        </label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="StudySiteInformation_details" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 4%">Row#</th>
                                        <th style="width: 12%">Audit Site</th>
                                        <th style="width: 16%">Site No.</th>
                                        <th style="width: 16%"> Investigator</th>
                                        <th style="width: 16%"> First Patient in Date</th>
                                        <th style="width: 16%">Enrolled No.</th>
                                        <th style="width: 16%">Current</th>
                                        <th style="width: 16%">Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td><input disabled type="text" name="serial[]" value="1"></td>
                                    <td><input type="text" name="Number[]"></td>
                                    <td><input type="text" name=""></td>
                                    <td><input type="text" name=""></td>
                                    <td><input type="text" name=""></td>
                                    <td><input type="text" name=""></td>
                                    <td><input type="text" name=""></td>
                                    <td><input type="text" name="Remarks"></td>

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

            <!-- =========================================================================================================== -->

            <div id="CCForm3" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">
                        <div class="sub-head">Important Date</div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Date">Initiation Date</label>
                                <input type="date" name="Date" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="group-input">
                                <label for="Date">Study Start Date</label>
                                <input type="date" name="Date" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="group-input">
                                <label for="Date">Study End Date</label>
                                <input type="date" name="Date" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Date">Study Protocol</label>
                                <input type="date" name="Date" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="group-input">
                                <label for="Date">First Subject in(FSI)</label>
                                <input type="date" name="Date" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="group-input">
                                <label for="Date">Last Subject Out</label>
                                <input type="date" name="Date" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="group-input">
                                <label for="Date">Data Base Lock(DBL)</label>
                                <input type="date" name="Date" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="group-input">
                                <label for="Date">Integrated CTR</label>
                                <input type="date" name="Date" />
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
                $(document).ready(function() {
                    $('#AuditSiteInformation').click(function(e) {
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

                        var tableBody = $('#AuditSiteInformation_details tbody');
                        var rowCount = tableBody.children('tr').length;
                        var newRow = generateTableRow(rowCount + 1);
                        tableBody.append(newRow);
                    });
                });
            </script>
            <script>
                $(document).ready(function() {
                    $('#StudySiteInformation').click(function(e) {
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

                        var tableBody = $('#StudySiteInformation_details tbody');
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