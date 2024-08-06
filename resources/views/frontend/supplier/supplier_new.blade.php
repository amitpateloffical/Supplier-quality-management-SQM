@extends('frontend.layout.main')
@section('container')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


@php 
    $users = DB::table('users')->select('id', 'name')->get();
    $requestNUmber = "RV/RP/" . str_pad($record_numbers, 4, '0', STR_PAD_LEFT) . "/" . date('Y');
@endphp
<style>
    textarea.note-codable {
        display: none !important;
    }

    header {
        display: none;
    }
    .custom-select{
        border: 1px solid black !important;
        height: 32px;
        margin-top: -11px;
    }
    .custom-date-picker{
        height:35px;
        border: 1px solid black !important;
        padding: 11px !important;
    }
    .custom-border{
        border: 1px solid black !important;
        /* padding: 10px;
        margin-bottom: 10px;
        margin-top: 10px; */
    }
</style>

<script>
        $(document).ready(function() {
            let certificateIndex = 1;
            $('#certificationData').click(function(e) {
                function generateTableRow(serialNumber) {
                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial[]" value="' + serialNumber +'"></td>' +
                        ' <td><input type="text" name="certificationData[' + certificateIndex + '][type]"></td>' +
                        ' <td><input type="text"name="certificationData[' + certificateIndex +'][issuingAgency]"></td>' +
                        '<td><input type="date" name="certificationData[' + certificateIndex + '][issueDate]"></td>' +
                        '<td><input type="date" name="certificationData[' + certificateIndex + '][expiryDate]"></td>' +
                        '<td><input type="text" name="certificationData[' + certificateIndex + '][supportingDoc]"></td>' +
                        '<td><input type="text" name="certificationData[' + certificateIndex + '][remarks]"></td>' +
                        '<td><button type="text" class="removeRowBtn">Remove</button></td>' +
                        '</tr>';
                    '</tr>';

                    certificateIndex++;
                    return html;
                }
                var tableBody = $('#certificationDataTable tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>
    <script>
        $(document).on('click', '.removeRowBtn', function() {
            $(this).closest('tr').remove();
        })
    </script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const issuedates = document.querySelectorAll('.issuedate');
        const expirydates = document.querySelectorAll('.expirydate');

        issuedates.forEach(function (issuedate) {
            issuedate.addEventListener('change', function () {
                const type = issuedate.dataset.type;
                const index = issuedate.dataset.index;
                const correspondingExpiryDate = document.getElementById(`expirydate_${index}_${type}`);

                if (issuedate.value) {
                    correspondingExpiryDate.min = issuedate.value;
                    if (correspondingExpiryDate.value && correspondingExpiryDate.value < issuedate.value) {
                        correspondingExpiryDate.value = '';
                    }
                } else {
                    correspondingExpiryDate.removeAttribute('min');
                }
            });
        });

        expirydates.forEach(function (expirydate) {
            expirydate.addEventListener('change', function () {
                const type = expirydate.dataset.type;
                const index = expirydate.dataset.index;
                const correspondingIssueDate = document.getElementById(`issuedate_${index}_${type}`);

                if (expirydate.value) {
                    correspondingIssueDate.max = expirydate.value;
                    if (correspondingIssueDate.value && correspondingIssueDate.value > expirydate.value) {
                        correspondingIssueDate.value = '';
                    }
                } else {
                    correspondingIssueDate.removeAttribute('max');
                }
            });
        });
    });
</script>


    <div class="form-field-head">
    
        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName(session()->get('division')) }} / Supplier
        </div>
    
    </div>

    <div id="change-control-fields">
        <div class="container-fluid">

            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Request for Creation of New Manufacturer</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">HOD Review</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Supplier Details</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Score Card</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">QA Reviewer</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Risk Assessment</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm7')">QA Head Reviewer</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm8')">Activity Log</button>
            </div>

            <!--  Contract Tab content -->
            <form action="{{ route('supplier-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div id="CCForm1" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Short Description">Request Number</label>
                                    <input id="request_number" type="text" name="request_number" value="{{ $requestNUmber }}" disabled>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Division</b></label>
                                    <input disabled type="text" name="division_id" value="{{ Helpers::getDivisionName(session()->get('division')) }}">
                                    <input type="hidden" name="division_id" value="{{ session()->get('division') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Initiator</b></label>
                                    <input disabled type="text" name="initiator_id" id="initiator_id" value="{{Auth::user()->name}}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiation"><b>Initiation Date</b></label>
                                    <input disabled type="text" value="{{ date('d-M-Y') }}" name="intiation_date">
                                    <input type="hidden" value="{{ date('Y-m-d') }}" name="intiation_date">
                                </div>
                            </div>

                            <!-- <div class="col-md-6">
                                <div class="group-input">
                                    <label for="search">Assigned To <span class="text-danger"></span>
                                    </label>
                                    <select id="select-state" name="assign_to">
                                        <option value="">Select a value</option>
                                        @if(!empty($users))
                                            @foreach($users as $user)
                                                <option value="{{$user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        @endif                                    
                                    </select>
                                </div>
                            </div> -->

                            @php
                                $initiationDate = date('d-M-Y');
                                $dueDate = date('d-M-Y', strtotime($initiationDate . '+30 days'));
                            @endphp

                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="due-date">Date Due</label>
                                    <div><small class="text-primary">Please mention expected date of completion</small></div>
                                    <div class="calenderauditee">                                    
                                        <input type="text" name="due_date" readonly value="{{$dueDate}}" />
                                    </div>
                                </div>
                            </div>

                            <script>
                                    // Format the due date to DD-MM-YYYY
                                    // Your input date
                                    var dueDate = "{{ $dueDate }}"; // Replace {{ $dueDate }} with your actual date variable

                                    // Create a Date object
                                    var date = new Date(dueDate);

                                    // Array of month names
                                    var monthNames = [
                                        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                                    ];

                                    // Extracting day, month, and year from the date
                                    var day = date.getDate().toString().padStart(2, '0'); // Ensuring two digits
                                    var monthIndex = date.getMonth();
                                    var year = date.getFullYear();

                                    // Formatting the date in "dd-MMM-yyyy" format
                                    var dueDateFormatted = `${day}-${monthNames[monthIndex]}-${year}`;

                                    // Set the formatted due date value to the input field
                                    document.getElementById('due_date').value = dueDateFormatted;
                                </script>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Short Description">Short Description<span class="text-danger">*</span></label><span id="rchars">255</span>
                                    characters remaining
                                    <input id="docname" type="text" name="short_description" maxlength="255" required>
                                </div>
                            </div>
                            <script>
                                var maxLength = 255;
                                $('#docname').keyup(function() {
                                    var textlen = maxLength - $(this).val().length;
                                    $('#rchars').text(textlen);});
                            </script>

                                <div class="sub-head">
                                    Purchase Department
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group">Initiation Department</label>
                                        <select name="initiation_group" id="initiation_group">
                                            <option value="">-- Select --</option>
                                            <option value="CQA"> Corpo  Assurance Biopharma</option>
                                            <option value="CQC"> Central Quality Control</option>
                                            <option value="MANU"> Manufacturing</option>
                                            <option value="PSG"> Plasma Sourcing Group</option>
                                            <option value="CS"> Central Stores</option>
                                            <option value="ITG"> Information Technology Group</option>
                                            <option value="MM"> Molecular Medicine</option>
                                            <option value="CL"> Central Laboratory</option>
                                            <option value="TT"> Tech Team</option>
                                            <option value="QA"> Quality Assurance</option>
                                            <option value="QM">Quality Management</option>
                                            <option value="IA">Administration</option>
                                            <option value="ACC"> Accounting</option>
                                            <option value="LOG">Logistics</option>
                                            <option value="SM"> Senior Management</option>
                                            <option value="BA">Business Administration</option>
                                        </select>
                                        @error('initiation_group')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Initiator Department Code</label>
                                        <input type="text" name="initiator_group_code" id="initiator_group_code" placeholer="Enter Initiator Department Code" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Name of Manufacturer</label>
                                        <input type="text" name="manufacturerName" id="manufacturerName" placeholder="Name of Manufacturer">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Name of Starting Material</label>
                                        <input type="text" name="starting_material" id="starting_material" placeholder="Enter Name of Starting Material">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Material Code</label>
                                        <input type="text" name="material_code" id="material_code" placeholder="Enter Material Code">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Pharmacopoeial Claim</label>
                                        <input type="text" name="pharmacopoeial_claim" id="pharmacopoeial_claim" placeholder="Enter Pharmacopoeial Claim">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">CEP Grade Material</label>
                                        <select id="cep_grade" name="cep_grade">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for=" Attachments">CEP Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="cep_attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="cep_attachment[]"
                                                    oninput="addMultipleFiles(this, 'cep_attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Request For</label>
                                        <select id="request_for" name="request_for[]" multiple >
                                            <option value="API">API</option>
                                            <option value="Excipient">Excipient</option>
                                            <option value="New Manufacturer">New Manufacturer</option>
                                            <option value="Existing Manufacturer">Existing Manufacturer</option>
                                            <option value="Additional Site of Existing Manufacturer">Additional Site of Existing Manufacturer</option>
                                            <option value="Brand New API">Brand New API</option>
                                            <option value="Existing API">Existing API</option>
                                            <option value="Brand New Excipient">Brand New Excipient</option>
                                            <option value="Existing Excipient">Existing Excipient</option>
                                            <option value="R&D development">R&D development</option>
                                            <option value="Site Transfer">Site Transfer</option>
                                            <option value="Alternate manufacturer">Alternate manufacturer</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Attach Three Batch COA's</label>
                                        <select id="attach_batch" name="attach_batch">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Justification for Request</label>
                                        <textarea type="text" name="request_justification" id="request_justification" class="tiny"></textarea>
                                    </div>
                                </div>

                                <div class="sub-head">
                                    CQA Department
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Availability of Manufacturer COA's</label>
                                        <select id="manufacturer_availability" name="manufacturer_availability">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Request Accepted</label>
                                        <select id="request_accepted" name="request_accepted">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Remark</label>
                                        <textarea type="text" name="cqa_remark" id="cqa_remark" class="tiny"></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Accepted By</label>
                                        <select type="hidden" name="accepted_by" id="accepted_by">
                                            <option value="">---- Select ----</option>
                                            @if(!empty($users))
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <!-- <label for="Initiator Group Code">Accepted On</label> -->
                                        <input type="hidden" name="accepted_on" id="accepted_on">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Pre Purchase Sample Required?</label>
                                        <div><small class="text-primary">If Yes inform purchase department to initiate pre-purchase sample intimation sheet</small></div>
                                        <div><small class="text-primary">If No then provide Justification proceed to section 16</small></div>
                                        <select id="pre_purchase_sample" name="pre_purchase_sample">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Justification</label>
                                        <textarea type="text" name="justification" id="justification" class="tiny"></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">CQA Coordinator</label>
                                        <select type="hidden" name="cqa_coordinator" id="cqa_coordinator">
                                            <option value="">---- Select ----</option>
                                            @if(!empty($users))
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <!-- To be filled by Purchase Department -->

                                <div class="col-12">
                                    <div class="group-input">
                                        <div class="why-why-chart">
                                        @php
                                            $types = ['tse', 'residual_solvent','melamine','gmo','gluten','manufacturer_evaluation','who','gmp','ISO','manufacturing_license','CEP','risk_assessment','elemental_impurity','azido_impurities'];
                                        @endphp

@foreach ($types as $type)
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 15%">Certificate Name</th>
                <th style="width: 20%">Attachment</th>
                <th style="width: 15%">Issue Date</th>
                <th style="width: 15%">Expiry Date</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody id="{{ $type }}_rows">
            <tr>
                <td>
                    {{ strtoupper(str_replace('_', ' ', $type)) }} <br>
                    <button type="button" onclick="addRow('{{ $type }}')">Add Row</button>
                </td>
                <td>
                    <input type="file" name="{{ $type }}_attachment[]" class="custom-border">
                </td>
                <td>
                    <input type="date" id="issuedate_{{ $loop->index }}_{{ $type }}" name="certificate_issue_{{ $type }}[]" class="custom-border issuedate" data-type="{{ $type }}" data-index="{{ $loop->index }}">
                </td>
                <td>
                    <input type="date" id="expirydate_{{ $loop->index }}_{{ $type }}" name="certificate_expiry_{{ $type }}[]" class="custom-border expirydate" data-type="{{ $type }}" data-index="{{ $loop->index }}">
                </td>
                <td>
                    <textarea name="{{ $type }}_remarks[]" class="custom-border"></textarea>
                </td>
            </tr>
        </tbody>
    </table>
@endforeach

                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Pre Purchase Sample Analysis Completed?</label>
                                        <select id="pre_purchase_sample_analysis" name="pre_purchase_sample_analysis">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Availability of COA's After Analysis</label>
                                        <select id="availability_od_coa" name="availability_od_coa">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Analyzed on Location</label>
                                        <input type="text" name="analyzed_location" id="analyzed_location">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Review Comment of CQA</label>
                                        <textarea type="text" name="cqa_comment" id="cqa_comment" class="tiny"></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">If Analysis found satisfactory of Pre-purchase samples send intimation</label>
                                        <!-- <div><small class="text-primary">To: Formulation and Development / MS&T Department.</small></div>
                                        <div><small class="text-primary">From: Corporate Quality Assurance</small></div> -->
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Material Name</label>
                                        <input type="text" name="materialName" id="materialName" placeholder="Enter Material Name">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Name of the Manufacturer</label>
                                        <input type="text" name="manufacturerNameNew" id="manufacturerNameNew" placeholder="Enter Name of the Manufacturer">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Analyzed at Location</label>
                                        <input type="text" name="analyzedLocation" id="analyzedLocation" placeholder="Enter Analyzed on Location">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Justification</label>
                                        <textarea type="text" name="supplierJustification" id="supplierJustification" class="tiny"></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Review Comment of Corporate CQA</label>
                                        <textarea type="text" name="cqa_corporate_comment" id="cqa_corporate_comment" class="tiny"></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">COA's Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="coa_attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="coa_attachment[]"
                                                    oninput="addMultipleFiles(this, 'coa_attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">CQA Designee</label>
                                        <select type="hidden" name="cqa_designee" id="cqa_designee">
                                            <option value="">---- Select ----</option>
                                            @if(!empty($users))
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="sub-head">
                                    Formulation & Development Department/CQA/MS&T
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Samples Ordered for Suitability Trail at R&D/MS & T</label>
                                        <div><small class="text-primary">If no provide Justification.</small></div>
                                        <select id="sample_ordered" name="sample_ordered">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Sample Justification</label>
                                        <textarea type="text" name="sample_order_justification" id="sample_order_justification" class="tiny"></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Acknowledge By</label>
                                        <select type="hidden" name="acknowledge_by" id="acknowledge_by">
                                            <option value="">---- Select ----</option>
                                            @if(!empty($users))
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Feedback on Trail Status Completed</label>
                                        <select id="trail_status_feedback" name="trail_status_feedback">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- To be filled by CQA Department -->

                                <!-- <div class="col-lg-6"></div> -->
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Sample Stand Approved?</label>
                                        <select id="sample_stand_approved" name="sample_stand_approved">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                            <option value="N/A">N/A</option>
                                        </select>
                                    </div>
                                </div>



                                <!-- Checklist -->

                                <div class="col-12">
                                    <div class="group-input">
                                        <div class="why-why-chart">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;">Sr. No.</th>
                                                        <th style="width: 30%;">Document Received</th>
                                                        <th style="width: 20%;">Selection</th>
                                                        <th>Remark</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="flex text-center">1</td>
                                                        <td>TSE/BSE</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="tse_bse" name="tse_bse">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="tse_bse_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">2</td>
                                                        <td>Residual Solvent</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="residual_solvent" name="residual_solvent">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="residual_solvent_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">3</td>
                                                        <td>GMO</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="gmo" name="gmo">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="gmo_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">4</td>
                                                        <td>Melamine</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="melamine" name="melamine">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="melamine_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">5</td>
                                                        <td>Gluten</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="gluten" name="gluten">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="gluten_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">6</td>
                                                        <td>Nitrosamine</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="nitrosamine" name="nitrosamine">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="nitrosamine_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">7</td>
                                                        <td>WHO</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="who" name="who">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="who_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">8</td>
                                                        <td>GMP</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="gmp" name="gmp">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="gmp_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">9</td>
                                                        <td>ISO Cerificates</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="iso_certificate" name="iso_certificate">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="iso_certificate_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">10</td>
                                                        <td>Manufacturing License</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="manufacturing_license" name="manufacturing_license">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="manufacturing_license_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">11</td>
                                                        <td>CEP</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="cep" name="cep">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="cep_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">12</td>
                                                        <td>MSDS</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="msds" name="msds">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="msds_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">13</td>
                                                        <td>Elemental Impurities</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="elemental_impurities" name="elemental_impurities">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="elemental_impurities_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">14</td>
                                                        <td>Assessment/Declaration of Azido Impurities as Applicable</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="declaration" name="declaration">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes">Yes</option>
                                                                    <option value="No">No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="declaration_remark" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>



                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Availability of Supply Chain?</label>
                                        <select id="supply_chain_availability" name="supply_chain_availability">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                            <option value="N/A">N/A</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Availability of Quality Agreement?</label>
                                        <select id="quality_agreement_availability" name="quality_agreement_availability">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                            <option value="N/A">N/A</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Risk Assessment Done?</label>
                                        <select id="risk_assessment_done" name="risk_assessment_done">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                            <option value="N/A">N/A</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Risk Rating</label>
                                        <select id="risk_rating" name="risk_rating">
                                            <option value="">---- Select ----</option>
                                            <option value="High">High</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Low">Low</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Manufacturer Audit planned</label>
                                        <select id="manufacturer_audit_planned" name="manufacturer_audit_planned">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Maufacturer Audit Conducted On</label>
                                        <input type="text" id="manufacturer_audit_conducted" name="manufacturer_audit_conducted">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Manufacturer Can be? </label>
                                        <select id="manufacturer_can_be" name="manufacturer_can_be">
                                            <option value="">---- Select ----</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Not Approved">Not Approved</option>
                                        </select>
                                    </div>
                                </div>
                        </div>

                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                        </div>
                    </div>
                </div>
                

                <!-- HOD Review content -->
                <div id="CCForm2" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="HOD_feedback">HOD Feedback</label>
                                    <textarea class="tiny" type="text" name="HOD_feedback" placeholder="Enter HOD Feedback" id="HOD_feedback"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="HOD_comment">HOD Comments</label>
                                    <textarea class="tiny" type="text" name="HOD_comment" placeholder="Enter HOD Comment" id="HOD_comment"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="HOD_attachment">HOD Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="HOD_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="HOD_attachment[]"
                                                oninput="addMultipleFiles(this, 'HOD_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="hod_additional_attachment">HOD Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="hod_additional_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="hod_additional_attachment[]"
                                                oninput="addMultipleFiles(this, 'hod_additional_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                        </div>
                    </div>
                </div>

                <!-- Supplier Details content -->
                <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Issues">
                                        Certifications & Accreditation<button type="button" name="ann" id="certificationData">+</button>
                                    </label>
                                    <table class="table table-bordered" id="certificationDataTable">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Type</th>
                                                <th>Issuing Agancy</th>
                                                <th>Issue Date</th>
                                                <th>Expiry Date</th>
                                                <th>Supporting Document</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" value="1" name="certificationData[]" readonly></td>
                                                <td><input type="text" name="certificationData[0][type]"></td>
                                                <td><input type="text" name="certificationData[0][issuingAgency]"></td>
                                                <td><input type="date" name="certificationData[0][issueDate]"></td>
                                                <td><input type="date" name="certificationData[0][expiryDate]"></td>
                                                <td><input type="text" name="certificationData[0][supportingDoc]"></td>
                                                <td><input type="text" name="certificationData[0][remarks]"></td>
                                                <td><button type="text" class="removeRowBtn">Remove</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier.">Supplier</label>
                                    <input type="text" name="supplier_name" id="supplier_name" placeholder="Enter Supplier Name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier.">Supplier ID</label>
                                <input type="text" name="supplier_id" placeholder="Enter Supplier ID">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="">Manufacturer</label>
                                    <input type="text" name="manufacturer_name" placeholder="Enter Manufacturer ID">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="manufacturer">Manufacturer ID</label>
                                <input type="text" name="manufacturer_id" placeholder="Enter Manufacturer ID">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="">Vendor</label>
                                    <input type="text" name="vendor_name" placeholder="Enter Vendor Name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="manufacturer">Vendor ID</label>
                                    <input type="text" name="vendor_id" placeholder="Enter Vendor ID">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Contact Person">Contact Person</label>
                                    <input type="text" name="contact_person" id="contact_person" placeholder="Enter Contact Person">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Other Contacts">Other Contacts</label>
                                    <input name="other_contacts" id="other_contacts" type="text">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Supplier Services">Supplier Services</label>
                                    <textarea class="tiny" name="supplier_serivce" id="supplier_serivce" cols="30" ></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Zone">Zone</label>
                                    <select name="zone">
                                        <option value="">Enter Your Selection Here</option>
                                        <option>Asia</option>
                                        <option>Europe</option>
                                        <option>Africa</option>
                                        <option>Central America</option>
                                        <option>South America</option>
                                        <option>Oceania</option>
                                        <option>North America</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Country">Country</label>
                                    <select name="country" class="form-select country" aria-label="Default select example" onchange="loadStates()">
                                        <option value="">Select Country</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="City">State</label>
                                    <select name="state" class="form-select state" aria-label="Default select example" onchange="loadCities()">
                                        <option value="">Select State/District</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="State/District">City</label>
                                    <select name="city" class="form-select city" aria-label="Default select example">
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>
                            <script>
                                var config = {
                                    cUrl: 'https://api.countrystatecity.in/v1',
                                    ckey: 'NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA=='
                                };

                                var countrySelect = document.querySelector('.country'),
                                    stateSelect = document.querySelector('.state'),
                                    citySelect = document.querySelector('.city');

                                function loadCountries() {
                                    let apiEndPoint = `${config.cUrl}/countries`;

                                    $.ajax({
                                        url: apiEndPoint,
                                        headers: {
                                            "X-CSCAPI-KEY": config.ckey
                                        },
                                        success: function(data) {
                                            data.forEach(country => {
                                                const option = document.createElement('option');
                                                option.value = country.iso2;
                                                option.textContent = country.name;
                                                countrySelect.appendChild(option);
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Error loading countries:', error);
                                        }
                                    });
                                }

                                function loadStates() {
                                    stateSelect.disabled = false;
                                    stateSelect.innerHTML = '<option value="">Select State</option>';

                                    const selectedCountryCode = countrySelect.value;

                                    $.ajax({
                                        url: `${config.cUrl}/countries/${selectedCountryCode}/states`,
                                        headers: {
                                            "X-CSCAPI-KEY": config.ckey
                                        },
                                        success: function(data) {
                                            data.forEach(state => {
                                                const option = document.createElement('option');
                                                option.value = state.iso2;
                                                option.textContent = state.name;
                                                stateSelect.appendChild(option);
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Error loading states:', error);
                                        }
                                    });
                                }

                                function loadCities() {
                                    citySelect.disabled = false;
                                    citySelect.innerHTML = '<option value="">Select City</option>';

                                    const selectedCountryCode = countrySelect.value;
                                    const selectedStateCode = stateSelect.value;

                                    $.ajax({
                                        url: `${config.cUrl}/countries/${selectedCountryCode}/states/${selectedStateCode}/cities`,
                                        headers: {
                                            "X-CSCAPI-KEY": config.ckey
                                        },
                                        success: function(data) {
                                            data.forEach(city => {
                                                const option = document.createElement('option');
                                                option.value = city.id;
                                                option.textContent = city.name;
                                                citySelect.appendChild(option);
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Error loading cities:', error);
                                        }
                                    });
                                }
                                $(document).ready(function() {
                                    loadCountries();
                                });
                            </script>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Address">Address</label>
                                    <textarea type="text" name="address" id="address" class="tiny"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Web Site">Supplier Web Site</label>
                                    <input type="text" name="suppplier_web_site" id="suppplier_web_site" placeholder="Enter Website ">
                                </div>
                            </div>
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="ISO Certification date">ISO Certification Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="iso_certified_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="iso_certified_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'iso_certified_date')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="iso_certificate_attachment">ISO Ceritificate Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="iso_certificate_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="iso_certificate_attachment[]"
                                                oninput="addMultipleFiles(this, 'iso_certificate_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Contracts">Contracts</label>
                                    <input type="text" name="suppplier_contacts" id="suppplier_contacts">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Related Non Conformances">Related Non Conformances</label>
                                    <input type="text" name="related_non_conformance" id="related_non_conformance">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Contracts/Agreements">Supplier Contracts/Agreements</label>
                                    <input type="text" id="suppplier_agreement" name="suppplier_agreement">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Regulatory History">Regulatory History</label>
                                    <input type="text" id="regulatory_history" name="regulatory_history">
                                </div>
                            </div>
                        
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Distribution Sites">Distribution Sites</label>
                                    <input type="text" id="distribution_sites" name="distribution_sites" maxlength="50">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Quality Management ">Manufacturing Sites </label>
                                    <textarea class="tiny" name="text" name="manufacturing_sited" id="manufacturing_sited"></textarea>
                                </div>
                            </div>  
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Quality Management ">Quality Management </label>
                                    <textarea class="tiny" name="text" id="quality_management" name="quality_management"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Business History">Business History</label>
                                    <textarea class="tiny" name="text" id="bussiness_history" name="bussiness_history"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Performance History ">Performance History </label>
                                    <textarea class="tiny" name="text" id="performance_history" name="performance_history"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Compliance Risk">Compliance Risk</label>
                                    <textarea class="tiny" name="text" id="compliance_risk" name="compliance_risk"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="supplier_detail_additional_attachment">Supplier Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="supplier_detail_additional_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="supplier_detail_additional_attachment[]"
                                                oninput="addMultipleFiles(this, 'supplier_detail_additional_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                        </div>
                    </div>
                </div>

                <!-- score card content -->
                <div id="CCForm4" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Cost Reduction">Cost Reduction</label>
                                    <select id="cost_reduction" name="cost_reduction">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Unacceptable">Unacceptable</option>
                                        <option value="Does Not Meet Expectation">Does Not Meet Expectation</option>
                                        <option value="Meets Expectations">Meets Expectations</option>
                                        <option value="Exceeds Expectations">Exceeds Expectations</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Cost Reduction Weight">Cost Reduction Weight</label>
                                    <select id="cost_reduction_weight" name="cost_reduction_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Payment Terms">Payment Terms</label>
                                    <select id="payment_term" name="payment_term">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="< 30 Days">< 30 Days</option>
                                        <option value="30 - 45 Days">30 - 45 Days</option>
                                        <option value="45 - 60 Days">45 - 60 Days</option>
                                        <option value=">= 60 Days">>= 60 Days</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="CPayment Terms Weight">Payment Terms Weight</label>
                                    <select name="payment_term_weight" id="payment_term_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Lead Time Days">Lead Time Days</label>
                                    <select name="lead_time_days" name="lead_time_days">
                                        <option>Enter Your Selection Here</option>
                                        <option value="> 11 Days"> > 11 Days</option>
                                        <option value="6 - 10">6 - 10</option>
                                        <option value="3 -5">3 -5</option>
                                        <option value="1 Day or Consignment">1 Day or Consignment</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Lead Time Days Weight">Lead Time Days Weight</label>
                                    <select name="lead_time_days_weight" id="lead_time_days_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="On-Time Delivery">On-Time Delivery</label>
                                    <select id="ontime_delivery" name="ontime_delivery">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="100%">100%</option>
                                        <option value="98-99%">98-99%</option>
                                        <option value="96-97%">96-97%</option>
                                        <option value="< 95%">< 95%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="On-Time Delivery Weight">On-Time Delivery Weight</label>
                                    <select id="ontime_delivery_weight" name="ontime_delivery_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Business Planning">Supplier Business Planning</label>
                                    <select id="supplier_bussiness_planning" name="supplier_bussiness_planning">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Not Information at All">Not Information at All</option>
                                        <option value="No Formal Information About">No Formal Information About</option>
                                        <option value="Yes - Partially Aligned With"></option>
                                        <option value="Yes - Completely Aligns"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Business Weight">Supplier Business Weight</label>
                                    <select id="supplier_bussiness_planning_weight" name="supplier_bussiness_planning_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Rejection in PPM">Rejection in PPM</label>
                                    <select id="rejection_ppm" name="rejection_ppm">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="> 500001 Defects PPM">> 500001 Defects PPM</option>
                                        <option value="5001 - 50000 Defects PPM">5001 - 50000 Defects PPM</option>
                                        <option value="501 - 500 Defects PPM">501 - 5000 Defects PPM</option>
                                        <option value="Upto 500 Defects PPM">Upto 500 Defects PPM"</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Rejection in PPM Weight">Rejection in PPM Weight</label>
                                    <select id="rejection_ppm_weight" name="rejection_ppm_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Quality Systems">Quality Systems</label>
                                    <select id="quality_system" name="quality_system">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="No System/No Team">No System/No Team</option>
                                        <option value="System Not Certified">System Not Certified</option>
                                        <option value="ISO 9000 Cert">ISO 9000 Cert</option>
                                        <option value="ISO 9000 & 1400 Cert">ISO 9000 & 1400 Cert</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Quality Systems Weight">Quality Systems Weight</label>
                                    <select id="quality_system_ranking" name="quality_system_ranking">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>  
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="# of CAR's generated"># of CAR's generated</label>
                                    <select id="car_generated" name="car_generated">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="0">0</option>
                                        <option value="> 8">> 8</option>
                                        <option value="2-7">2-7</option>
                                        <option value="0-1">0-1</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="# of CAR's generated Weight"># of CAR's generated Weight</label>
                                    <select id="car_generated_weight" name="car_generated_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="CAR Closure Time">CAR Closure Time</label>
                                    <select id="closure_time" name="closure_time">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="> 60">> 60</option>
                                        <option value="30-60">30-60</option>
                                        <option value="15-30">15-30</option>
                                        <option value="0-15">0-15</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="CAR Closure Time Weight">CAR Closure Time Weight</label>
                                    <select id="closure_time_weight" name="closure_time_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="End-User Satisfaction">End-User Satisfaction</label>
                                    <select id="end_user_satisfaction" name="end_user_satisfaction">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Information Lacks">Information Lacks</option>
                                        <option value="Not Reactive Enough">Not Reactive Enough</option>
                                        <option value="Required">Required</option>
                                        <option value="Active Participation">Active Participation</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="End-User Satisfaction Weight">End-User Satisfaction Weight</label>
                                    <select id="end_user_satisfaction_weight" name="end_user_satisfaction_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="score_card_additional_attachment">Score Card Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="score_card_additional_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="score_card_additional_attachment[]"
                                                oninput="addMultipleFiles(this, 'score_card_additional_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-12 sub-head">
                                Total Score
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Scorecard Record">Scorecard Record</label>
                                    <input type="text" name="scorecard_record" id="scorecard_record" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Achived Score">Achived Score</label>
                                    <input type="text" name="achieved_score" id="achieved_score" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Total Available Score">Total Available Score</label>
                                    <input type="text" name="total_available_score" id="total_available_score" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Total Score">Total Score</label>
                                    <input type="text" name="total_score"  id="total_score" readonly>
                                </div>
                            </div> --}}
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                        </div>
                    </div>
                </div>

                <!-- QA Reviewer content -->
                <div id="CCForm5" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_reviewer_feedback">QA Reviewer Feedback</label>
                                    <textarea class="tiny" type="text" name="QA_reviewer_feedback" placeholder="Enter QA Reviewer Feedback" id="QA_reviewer_feedback"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_reviewer_comment">QA Reviewer Comment</label>
                                    <textarea class="tiny" type="text" name="QA_reviewer_comment" placeholder="Enter QA Reviewer Comment" id="QA_reviewer_comment"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_reviewer_attachment">QA Reviewer Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="QA_reviewer_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="QA_reviewer_attachment[]"
                                                oninput="addMultipleFiles(this, 'QA_reviewer_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="qa_reviewer_additional_attachment">QA Reviewer Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="qa_reviewer_additional_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="qa_reviewer_additional_attachment[]"
                                                oninput="addMultipleFiles(this, 'qa_reviewer_additional_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                        </div>
                    </div>
                </div>

                <!-- Risk Assessment Content -->
                <div id="CCForm6" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Last Audit Date">Last Audit Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="last_audit_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="last_audit_date" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'last_audit_date')" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Last Audit Date">Next Audit Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="next_audit_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="next_audit_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'next_audit_date')" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Audit Frequency">Audit Frequency</label>
                                    <select id="audit_frequency" name="audit_frequency">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Every 10 Years">Every 10 Years</option>
                                        <option value="Every 9 Years">Every 9 Years</option>
                                        <option value="Every 8 Years">Every 8 Years</option>
                                        <option value="Every 7 Years">Every 7 Years</option>
                                        <option value="Every 6 Years">Every 6 Years</option>
                                        <option value="Every 5 Years">Every 5 Years</option>
                                        <option value="Every 4 Years">Every 4 Years</option>
                                        <option value="Every 3 Years">Every 3 Years</option>
                                        <option value="Every 2 Years">Every 2 Years</option>
                                        <option value="Annual">Annual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Last Audit Result">Last Audit Result</label>
                                    <select id="last_audit_result" name="last_audit_result">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="5">5</option>
                                        <option value="4">4</option>
                                        <option value="3">3</option>
                                        <option value="2">2</option>
                                        <option value="1">1</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 sub-head">
                                Risk Factors
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Facility Type">Facility Type</label>
                                    <select id="facility_type" name="facility_type">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Operation, R&M - Level 3">Operation, R&M - Level 3</option>
                                        <option value="Operation, R&M - Level 2">Operation, R&M - Level 2</option>
                                        <option value="Operation Only, Stock Point Only">Operation Only, Stock Point Only</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Number of Employees">Number of Employees</label>
                                    <select id="nature_of_employee" name="nature_of_employee">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="<25"> < 25 </option>
                                        <option value="26-49">26-49</option>
                                        <option value=">50">>50</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Access to Technical Support">Access to Technical Support</label>
                                    <select id="technical_support" name="technical_support">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Very Limited Access">Very Limited Access to Technical Experts</option>
                                        <option value="Available When Requested">Available When Requested or Via Beacon Center</option>
                                        <option value="Regulatory Schedule Visit by Region Experts">Regulatory Schedule Visit by Region Experts</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Services Supported">Services Supported</label>
                                    <select name="survice_supported" id="survice_supported">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Integrated, Multi-Combo Jobs">Integrated, Multi-Combo Jobs</option>
                                        <option value="Basic D&E Services">Basic D&E Services</option>
                                        <option value="Motors or Standalone MWD">Motors or Standalone MWD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Reliability">Reliability</label>
                                    <select id="reliability" name="reliability">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Significantly Below Expectations">Significantly Below Expectations</option>
                                        <option value="Marginally Below Expectations">Marginally Below Expectations</option>
                                        <option value="Meets Expectations">Meets Expectations</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Revenue">Revenue</label>
                                    <select name="revenue" id="revenue">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value=">50 M">>50 M</option>
                                        <option value="26-49 M">26-49 M</option>
                                        <option value="<25 M">< 25 M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Client Base">Client Base</label>
                                    <select id="client_base" name="client_base">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Single or Disproportionally Skewed">Single or Disproportionally Skewed</option>
                                        <option value="Multiple Clients">Multiple Clients</option>
                                        <option value="Well Diversified">Well Diversified</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Previous Audit Results">Previous Audit Results</label>
                                    <select id="previous_audit_result" name="previous_audit_result">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Below Requirement Major NCN's or No Audit History">Below Requirement Major NCN's or No Audit History</option>
                                        <option value="Marginally Below Requirement With Minor NCN's">Marginally Below Requirement With Minor NCN's</option>
                                        <option value="Meets Requirement and Minimal NCN's">Meets Requirement and Minimal NCN's</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="risk_assessment_additional_attachment">Risk Assesment Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="risk_assessment_additional_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="risk_assessment_additional_attachment[]"
                                                oninput="addMultipleFiles(this, 'risk_assessment_additional_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="sub-head">
                                Results
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Total Available Score">Risk Row Total</label>
                                    <input type="text" name="risk_raw_total" id="risk_raw_total" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Total Available Score">Risk Median</label>
                                    <input type="text" name="risk_median" id="risk_median" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Total Available Score">Risk Average</label>
                                    <input type="text" name="risk_average" id="risk_average" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Total Available Score">Risk Assessment Total</label>
                                    <input type="text" name="risk_assessment_total" id="risk_assessment_total" readonly>
                                </div>
                            </div> --}}
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                        </div>
                    </div>
                </div>

                <!-- QA Head content -->
                <div id="CCForm7" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_head_comment">QA Head Comment</label>
                                    <textarea class="tiny" type="text" name="QA_head_comment" placeholder="Enter QA Head Comment" id="QA_head_comment"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_head_attachment">QA Head Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="QA_head_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="QA_head_attachment[]"
                                                oninput="addMultipleFiles(this, 'QA_head_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="qa_head_additional_attachment">QA Head Reviewer Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="qa_head_additional_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="qa_head_additional_attachment[]"
                                                oninput="addMultipleFiles(this, 'qa_head_additional_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                        </div>
                    </div>
                </div>
            
                <!-- Signature content -->
                <div id="CCForm8" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Submitted By">Need for Sourcing of Starting Material By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Submitted On">Need for Sourcing of Starting Material On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Submitted Comment">Need for Sourcing of Starting Material Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Submitted By">Approved by Contract Giver By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Submitted On">Approved by Contract Giver On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Submitted Comment">Approved by Contract Giver Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Submitted By">Request Justified By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Submitted On">Request Justified On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Submitted Comment">Request Justified Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Submitted By">Request Not Justified By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Submitted On">Request Not Justified On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Submitted Comment">Request Not Justified Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>


                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Suppplier Review By">Pre-Purchase Sample Required By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Suppplier Review On">Pre-Purchase Sample Required On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Suppplier Review Comment">Pre-Purchase Sample Required Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Score Card By">Pre-Purchase Sample Not Required By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Review Completed On">Pre-Purchase Sample Not Required On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Review Completed Comment">Pre-Purchase Sample Not Required Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Purchase Sample Request By">Purchase Sample Request Ack. by Dep.</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Purchase Sample Request Initiated On">Purchase Sample Request Ack. by Dep. On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Purchase Sample Request Initiated Comment">Purchase Sample Request Ack. by Dep. Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Purchase Sample Analysis Satisfactory By">Purchase Sample Analysis Satisfactory By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Purchase Sample Analysis Satisfactory On">Purchase Sample Analysis Satisfactory On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Purchase Sample Analysis Satisfactory Comment">Purchase Sample Analysis Satisfactory Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="F&D Review Completed By">Purchase Sample Analysis Not Satisfactory</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="F&D Review Completed On">Purchase Sample Analysis Not Satisfactory On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="F&D Review Completed Comment">Purchase Sample Analysis Not Satisfactory Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed By">F&D Review Completed By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed On">F&D Review Completed On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Final Review Completed Comment">F&D Review Completed Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed By">Acknowledgement By Purchase Dept. By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed On">Acknowledgement By Purchase Dept. On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Final Review Completed Comment">Acknowledgement By Purchase Dept. Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed By">All Requirements Fulfilled By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed On">All Requirements Fulfilled On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Final Review Completed Comment">All Requirements Fulfilled Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed By">All Requirements Not Fulfilled By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed On">All Requirements Not Fulfilled On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Final Review Completed Comment">All Requirements Not Fulfilled Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed By">Risk Rating Observed as High By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed On">Risk Rating Observed as High On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Final Review Completed Comment">Risk Rating Observed as High Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed By">Risk Rating Observed as Low By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed On">Risk Rating Observed as Low On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Final Review Completed Comment">Risk Rating Observed as Low Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed By">Manufacturer Audit Passed By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed On">Manufacturer Audit Passed On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Final Review Completed Comment">Manufacturer Audit Passed Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed By">Initiate Periodic Revaluation By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed On">Initiate Periodic Revaluation On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Final Review Completed Comment">Initiate Periodic Revaluation Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed By">Risk Rating Observed as High/Medium By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed On">Risk Rating Observed as High/Medium On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Final Review Completed Comment">Risk Rating Observed as High/Medium Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed By">Risk Rating Observed as Low By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed On">Risk Rating Observed as Low On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Final Review Completed Comment">Risk Rating Observed as Low Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed By">Manufacturer Audit Failed By</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed On">Manufacturer Audit Failed On</label>
                                <div class="static"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Final Review Completed Comment">Manufacturer Audit Failed Comment</label>
                                <div class="static"></div>
                            </div>
                        </div>
                    </div>
                    <div class="button-block">
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                    </div>
                </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        VirtualSelect.init({
            ele: '#supplier-product, #ppap-elements, #supplier-services, #other-products, #manufacture-sites, #request_for'
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
        // JavaScript
        document.getElementById('initiation_group').addEventListener('change', function() {
            var selectedValue = this.value;
            document.getElementById('initiator_group_code').value = selectedValue;
        });
    </script>

    <script>
        flatpickr("#datepicker", {
            dateFormat: "d-M-Y", // Display format
            altFormat: "d-M-Y", // Format to show in the input field
            altInput: true,
            altInputClass: "form-control",
            onChange: function(selectedDates, dateStr, instance) {
                instance._input.value = dateStr; // Ensure the displayed format is stored
            }
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function addRow(type) {
                // console.log(`Adding row for type: ${type}`);
                let tbody = document.getElementById(`${type}_rows`);
                let newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><button type="button" onclick="removeRow(this)">Remove</button></td>
                    <td><input type="file" name="${type}_attachment[]" class="custom-border"></td>
                    <td><input type="date" name="certificate_issue_${type}[]" class="custom-border"></td>
                    <td><input type="date" name="certificate_expiry_${type}[]" class="custom-border"></td>
                    <td><textarea name="${type}_remarks[]" class="custom-border"></textarea></td>
                `;
                tbody.appendChild(newRow);
                // console.log(`Row added for type: ${type}`);
            }

            window.addRow = addRow;
        });
        function removeRow(button) {
            let row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }
    </script>


@endsection
