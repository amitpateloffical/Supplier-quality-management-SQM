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
        / Self Inspection
    </div>
</div>



{{-- ! ========================================= --}}
{{-- !               DATA FIELDS                 --}}
{{-- ! ========================================= --}}
<div id="change-control-fields">
    <div class="container-fluid">

        <!-- Tab links -->
        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Audittee Group Acceptance</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Self Inspection Execution</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Self Inspection Report Prepration</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Approval Of Self Inspection Report </button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Self Inspection Compliance</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Compliance Approval </button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm8')">Compliance Review </button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm9')">Signatures </button>


        </div>

        <form action="{{ route('actionItem.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div id="step-form">
                @if (!empty($parent_id))
                <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                @endif
                <!-- Tab content -->
                <!-- Tab-1 -->
                <div id="CCForm1" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="sub-head">
                            General Information
                        </div> <!-- RECORD NUMBER -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>Record Number</b></label>
                                    <input disabled type="number" name="" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>Division Code </b></label>
                                    <select>
                                        <option>D-101</option>
                                        <option>D-102</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b> Initiator </b></label>
                                    <select>
                                        <option>Pankaj</option>
                                        <option>Gaurav</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="due-date"> Date Of Initiation <span class="text-danger"></span></label>
                                    <!-- <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            value="" name="due_date"> -->
                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="due-date"> Due Date<span class="text-danger"></span></label>
                                    <!-- <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            value="" name="due_date"> -->
                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b> Severity Level </b></label>
                                    <select>
                                        <option>Pankaj</option>
                                        <option>Gaurav</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b> Initiator Group </b></label>
                                    <select>
                                        <option>Pankaj</option>
                                        <option>Gaurav</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b> Group Code </b></label>
                                    <select>
                                        <option>Pankaj</option>
                                        <option>Gaurav</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label class="mt-4" for="Audit Comments"> Initiated Through</label>
                                    <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b> If Others </b></label>
                                    <select>
                                        <option>Pankaj</option>
                                        <option>Gaurav</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label class="mt-4" for="Audit Comments"> Is Repeat</label>
                                    <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label class="mt-4" for="Audit Comments"> Repeat Nature</label>
                                    <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="closure attachment">Nature Of Change</label>
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
                                    <label class="mt-4" for="Audit Comments"> Description </label>
                                    <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                </div>
                            </div>

                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="due-date"> Deviation Occured On<span class="text-danger"></span></label>
                                    <!-- <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            value="" name="due_date"> -->
                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" />
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b> Initial Attachment </b></label>
                                    <select>
                                        <option>Pankaj</option>
                                        <option>Gaurav</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b> Source Document Type </b></label>
                                    <select>
                                        <option>Pankaj</option>
                                        <option>Gaurav</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Reference Recores">Refrence System Document </label>
                                    <select multiple id="reference_record" name="PhaseIIQCReviewProposedBy[]" id="">
                                        <option value="">--Select---</option>
                                        <option value="">Pankaj</option>
                                        <option value="">Gourav</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b> Refrence Document </b></label>
                                    <input />
                                </div>
                            </div>

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Auditor Details
                                    <button type="button" name="audit-agenda-grid" id="Auditor-add">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">

                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="Auditor-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Auditor Role</th>
                                                <th style="width: 16%">Name Of Auditor</th>
                                                <th style="width: 15%">Designation</th>
                                                <th style="width: 15%">Department </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>

                                            <td><select name="AuditorRole[]">
                                                    <option>Pankaj</option>
                                                    <option>Gaurav</option>
                                                </select></td>
                                            <td><input name="NameOfAuditor[]" /></td>
                                            <td><select name="Designation[]">
                                                    <option>Pankaj</option>
                                                    <option>Gaurav</option>
                                                </select></td>
                                            <td><select name="Department[]">
                                                    <option>Pankaj</option>
                                                    <option>Gaurav</option>
                                                </select></td>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="closure attachment">Audit File Attachment </label>
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

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>Auditor </b></label>
                                    <select>
                                        <option>Pankaj</option>
                                        <option>Gaurav</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sub-head">Cancellation</div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label class="mt-4" for="Audit Comments"> Cancellation Comments </label>
                                    <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
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

                <!-- Tab-2-->
                <div id="CCForm2" class="inner-block cctabcontent">
                    <div class="inner-block-content">

                        <div class="row">



                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>Scheduled Dates Acceptable </b></label>
                                    <select>
                                        <option>--Select--</option>

                                        <option>12</option>
                                        <option>13</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="cancelled by">Proposed Dates</label>
                                    <input />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label class="mt-4" for="Audit Comments"> Any Comments? </label>
                                    <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
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

                <!-- Tab-3 -->
                <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>Self Inspection Report Number</label>
                                    <input type="number" disabled />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>Self Inspection Started On</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>Self Inspection Ended On</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label class="mt-4" for="Audit Comments"> Justification For Delay </label>
                                    <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>Additional Auditor (if any) </label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Reference Recores">Key Persons Met </label>
                                    <select multiple id="reference_record" name="PhaseIIQCReviewProposedBy[]" id="">
                                        <option value="">--Select---</option>
                                        <option value="">Pankaj</option>
                                        <option value="">Gourav</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="closure attachment"> Checklist Attachment </label>
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

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>Data Integrity Issues </b></label>
                                    <select>
                                        <option>Pankaj</option>
                                        <option>Gaurav</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label class="mt-4" for="Audit Comments"> Data Integrity Issues Details</label>
                                    <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>Self Inspection Observation Required </b></label>
                                    <select>
                                        <option>Pankaj</option>
                                        <option>Gaurav</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Reference Recores">Self Inspection Observation Refrence </label>
                                    <select multiple id="reference_record" name="PhaseIIQCReviewProposedBy[]" id="">
                                        <option value="">--Select---</option>
                                        <option value="">Pankaj</option>
                                        <option value="">Gourav</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="closure attachment"> Supporting Documents </label>
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

                <!-- Tab-4 -->
                <div id="CCForm4" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="sub-head">
                            Observations Identified
                        </div> <!-- RECORD NUMBER -->
<div class="row">



                        <div class="group-input">
                            <label for="audit-agenda-grid">
                                Observations
                                <button type="button" name="audit-agenda-grid" id="Observations-add">+</button>
                                <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">

                                </span>
                            </label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="Observations-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">Row#</th>
                                            <th style="width: 12%">Name Of The System </th>
                                            <th style="width: 16%">Observation</th>
                                            <th style="width: 15%">Documents Reviewed</th>
                                            <th style="width: 15%">Type Of Non-Conformance </th>
                                            <th style="width: 15%">SI Observation Child Ref</th>





                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td><input disabled type="text" name="serial[]" value="1"></td>

                                        <td><select name="NameOfTheSystem[]">
                                                <option>Pankaj</option>
                                                <option>Gaurav</option>
                                            </select></td>
                                        <td><input name="Observation[]" /></td>
                                        <td><input name="DocumentsReviewed[]" /></td>

                                        <td><select name="TypeOfNon-Conformance[]">
                                                <option>Pankaj</option>
                                                <option>Gaurav</option>
                                            </select></td>
                                        <td><select multiple id="reference_record" id="" name="SIObservationChildRef[]">
                                                <option>Pankaj</option>
                                                <option>Gaurav</option>
                                            </select></td>
                                    </tbody>

                                </table>
                            </div>
                        </div>

                        <div class="sub-head">No Of Observation</div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="RLS Record Number"><b>Critical </b></label>
                                <input type="number" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="RLS Record Number"><b>Major </b></label>
                                <input type="number" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="RLS Record Number"><b>Minor </b></label>
                                <input type="number" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="RLS Record Number"><b>Recommendation </b></label>
                                <input type="number" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="RLS Record Number"><b>Total SI Observations </b></label>
                                <input type="number" />
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="closure attachment"> Attachment If Any</label>
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

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="RLS Record Number"><b>Auditor </b></label>
                                <select>
                                    <option>Pankaj</option>
                                    <option>Gaurav</option>
                                </select>
                            </div>
                        </div>

                        <div class="sub-head">Cancellation</div>

                        <div class="col-12">
                            <div class="group-input">
                                <label class="mt-4" for="Audit Comments"> Cancellation Comments </label>
                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="button-block">
                        <button type="submit" class="saveButton">Save</button>
                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>

                        <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                Exit </a> </button>

                    </div>
                </div>
            

            <!-- Tab-5 -->
            <div id="CCForm5" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">

                        <div class="col-12">
                            <div class="group-input">
                                <label class="mt-4" for="Audit Comments"> Approval Comments? </label>
                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="group-input">
                                <label class="mt-4" for="Audit Comments"> Delay Justification </label>
                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="group-input">
                                <label for="closure attachment">Approval Attachment </label>
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
                    </div>
                    <div class="button-block">
                        <button type="submit" class="saveButton">Save</button>
                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                            </a> </button>
                    </div>
                </div>
            </div>


            <!-- Tab-6 -->
            <div id="CCForm6" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">
                        <div class="sub-head">Self Inspection Compliance</div>

                        <div class="col-12">
                            <div class="group-input">
                                <label class="mt-4" for="Audit Comments"> Compliance Comments </label>
                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                            </div>
                        </div>



                        <div class="col-12">
                            <div class="group-input">
                                <label class="mt-4" for="Audit Comments"> Compliance Finalised On </label>
                                <input type="date" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label class="mt-4" for="Audit Comments"> Delay Justification </label>
                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="group-input">
                                <label for="closure attachment">Compliance Attachment If Any</label>
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
                    </div>
                    <div class="button-block">
                        <button type="submit" class="saveButton">Save</button>
                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                            </a> </button>
                    </div>
                </div>
            </div>


            <!-- Tab-7 -->
            <div id="CCForm7" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">

                        <div class="col-12">
                            <div class="group-input">
                                <label class="mt-4" for="Audit Comments">Compliance Approval Comments </label>
                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                            </div>
                        </div>



                        <div class="col-lg-12">
                            <div class="group-input">
                                <label for="closure attachment">File Attachment </label>
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
                    </div>
                    <div class="button-block">
                        <button type="submit" class="saveButton">Save</button>
                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                            </a> </button>
                    </div>
                </div>
            </div>

            <!-- Tab-8 -->
            <div id="CCForm8" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">

                        <div class="col-12">
                            <div class="group-input">
                                <label class="mt-4" for="Audit Comments"> Conclution </label>
                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                            </div>
                        </div>



                        <div class="col-lg-12">
                            <div class="group-input">
                                <label for="closure attachment">File Attachment If Any</label>
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
                    </div>
                    <div class="button-block">
                        <button type="submit" class="saveButton">Save</button>
                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                            </a> </button>
                    </div>
                </div>
            </div>

            <!-- Tab-9 -->
            <div id="CCForm9" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="submitted by">Self Inspection Proposed By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="submitted on">Self Inspection Proposed On</label>
                                <div class="Date"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reviewed by">Cancellation By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Approved on">Cancellation On</label>
                                <div class="Date"></div>
                            </div>
                        </div>



                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reviewed by">Self Inspection Plan Accepted By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Approved on">Self Inspection Plan Accepted On</label>
                                <div class="Date"></div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reviewed by">Self Inspection Acknowledge By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Approved on">Self Inspection Acknowledge On</label>
                                <div class="Date"></div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reviewed by">Execution Details Updated By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Approved on">Execution Details Updated On</label>
                                <div class="Date"></div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reviewed by">Self Inspection Report Submitted By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Approved on">Self Inspection Report Submitted On</label>
                                <div class="Date"></div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reviewed by">Self Inspection Report Approved By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Approved on">Self Inspection Report Approved On</label>
                                <div class="Date"></div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reviewed by">Self Inspection Compliance Submitted By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Approved on">Self Inspection Compliance Submitted On</label>
                                <div class="Date"></div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reviewed by">Self Inspection Compliance Approved By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Approved on">Self Inspection Compliance Approved On</label>
                                <div class="Date"></div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reviewed by">Self Inspection Compliance Accepted By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Accepted on">Self Inspection Compliance Accepted On</label>
                                <div class="Date"></div>
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
        $('#Auditor-add').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +

                    '<td><select name="AuditorRole[]"><option>Pankaj</option><option>Gaurav</option></select></td>' +
                    '<td><input name="NameOfAuditor[]"/></td>' +
                    '<td><select name="Designation[]"><option>Pankaj</option><option>Gaurav</option></select></td>' +
                    '<td><select name="Department[]"><option>Pankaj</option><option>Gaurav</option></select></td>' +

                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                '</tr>';

                return html;
            }

            var tableBody = $('#Auditor-table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#ObservationAdd').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="Number[]"></td>' +
                    '<td><input type="date" name="date[]"></td>' +
                    '<td><input type="date" name="SentDate[]"></td>' +
                    '<td><input type="date" name="ReturnedDate[]"></td>' +
                    '<td><input type="text" name="Data Collection Method[]"></td>' +
                    '<td><input type="text" name="Comment[]"></td>' +

                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                '</tr>';

                return html;
            }

            var tableBody = $('#minor-protocol-voilation tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#Observations-add').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><select name="NameOfTheSystem[]"><option>Pankaj</option><option>Gaurav</option></select></td>' +
                    '<td><input name="Observation[]"/></td>' +
                    '<td><input name="DocumentsReviewed[]"/></td>' +

                    '<td><select name="TypeOfNon-Conformance[]"><option>Pankaj</option><option>Gaurav</option></select></td>' +
                    '<td><select  multiple id="reference_record"  id="" name="SIObservationChildRef[]"><option>Pankaj</option><option>Gaurav</option></select></td>' +

                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                '</tr>';

                return html;
            }

            var tableBody = $('#Observations-table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
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