@extends('frontend.layout.main')
@section('container')

@php 
    $users = DB::table('users')->select('id', 'name')->get();    
    $requestNUmber = "RV/RP/" . str_pad($data->record, 4, '0', STR_PAD_LEFT) . "/" . date('Y');
@endphp
<style>
    textarea.note-codable {
        display: none !important;
    }

    header {
        display: none;
    }
</style>
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
    </style>
    <style>
        .progress-bars div {
            flex: 1 1 auto;
            border: 1px solid grey;
            padding: 5px;
            text-align: center;
            position: relative;
            /* border-right: none; */
            background: white;
        }

        .state-block {
            padding: 20px;
            margin-bottom: 20px;
        }

        .progress-bars div.active {
            background: green;
            font-weight: bold;
        }

        #change-control-fields>div>div.inner-block.state-block>div.status>div.progress-bars.d-flex>div:nth-child(1) {
            border-radius: 20px 0px 0px 20px;
        }
        #change-control-fields>div>div.inner-block.state-block>div.status>div.progress-bars.d-flex>div:nth-child(6) {
            border-radius: 0px 20px 20px 0px;
        }
    </style>
    <style>
        textarea.note-codable {
            display: none !important;
        }

        header {
            display: none;
        }

        .sub-main-head {
            display: flex;
            justify-content: space-evenly;
        }

        .Activity-type {
            margin-bottom: 7px;
        }

        /* .sub-head {
            margin-left: 280px;
            margin-right: 280px;
            color: #4274da;
            border-bottom: 2px solid #4274da;
            padding-bottom: 5px;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 1.2rem;

        } */
        .launch_extension {
            background: #4274da;
            color: white;
            border: 0;
            padding: 4px 15px;
            border: 1px solid #4274da;
            transition: all 0.3s linear;
        }
        .main_head_modal li {
            margin-bottom: 10px;
        }

        .extension_modal_signature {
            display: block;
            width: 100%;
            border: 1px solid #837f7f;
            border-radius: 5px;
        }

        .main_head_modal {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .create-entity {
            background: #323c50;
            padding: 10px 15px;
            color: white;
            margin-bottom: 20px;

        }

        .bottom-buttons {
            display: flex;
            justify-content: flex-end;
            margin-right: 300px;
            margin-top: 50px;
            gap: 20px;
        }

        .text-danger {
            margin-top: -22px;
            padding: 4px;
            margin-bottom: 3px;
        }

        /* .saveButton:disabled{
                background: black!important;
                border:  black!important;

            } */

        .main-danger-block {
            display: flex;
        }

        .swal-modal {
            scale: 0.7 !important;
        }

        .swal-icon {
            scale: 0.8 !important;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @if (Session::has('swal'))
        <script>
            swal("{{ Session::get('swal')['title'] }}", "{{ Session::get('swal')['message'] }}",
                "{{ Session::get('swal')['type'] }}")
        </script>
    @endif

    <!-- <script>
        $(document).ready(function() {
            let certificationDataIndex = {{ $certificationData && is_array($certificationData) ? count($certificationData) : 1 }};
            $('#certificationData').click(function(e) {
                function generateTableRow(serialNumber) {
                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                        ' <td><input type="text" name="certificationData[' + certificationDataIndex + '][type]"></td>' +
                        ' <td><input type="text" name="certificationData[' + certificationDataIndex + '][issuingAgency]"></td>' +
                        '<td><input type="date" name="certificationData[' + certificationDataIndex + '][issueDate]"></td>' +
                        '<td><input type="date" name="certificationData[' + certificationDataIndex + '][expiryDate]"></td>' +
                        ' <td><input type="text" name="certificationData[' + certificationDataIndex + '][supportingDoc]"></td>' +
                        '<td><input type="text" name="certificationData[' + certificationDataIndex + '][remarks]"></td>' +
                        '<td><button type="text" class="removeRowBtn">Remove</button></td>' +

                        '</tr>';
                    '</tr>';
                    certificationDataIndex++;
                    return html;
                }

                var tableBody = $('#certificationDataTable tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script> -->

    <script>
        $(document).ready(function() {
    let certificationDataIndex = {{ $certificationData && is_array($certificationData) ? count($certificationData) : 1 }};
    
    // Function to generate new table row
    function generateTableRow(serialNumber) {
        var html =
            '<tr>' +
            '<td style="width: 60px;"><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
            '<td><input type="text" name="certificationData[' + certificationDataIndex + '][type]"></td>' +
            '<td><input type="text" name="certificationData[' + certificationDataIndex + '][issuingAgency]"></td>' +
            '<td><input type="date" name="certificationData[' + certificationDataIndex + '][issueDate]" class="issueDate" max="' + getTodayDate() + '"></td>' +
            '<td><input type="date" name="certificationData[' + certificationDataIndex + '][expiryDate]" class="expiryDate" disabled></td>' +
            '<td><input type="text" name="certificationData[' + certificationDataIndex + '][supportingDoc]"></td>' +
            '<td><input type="text" name="certificationData[' + certificationDataIndex + '][remarks]"></td>' +
            '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
            '</tr>';
        certificationDataIndex++;
        return html;
    }

    // Function to get today's date in YYYY-MM-DD format
    function getTodayDate() {
        var today = new Date();
        return today.toISOString().split('T')[0];
    }

    // Function to add date validation
    function addDateValidation() {
        $('.issueDate').off('change').on('change', function() {
            var issueDate = $(this).val();
            var expiryDateInput = $(this).closest('tr').find('.expiryDate');
            expiryDateInput.attr('min', issueDate);
            expiryDateInput.removeAttr('disabled');
        });

        $('.expiryDate').off('change').on('change', function() {
            var issueDate = $(this).closest('tr').find('.issueDate').val();
            var expiryDate = $(this).val();

            if (expiryDate <= issueDate) {
                alert('Expiry date must be greater than issue date.');
                $(this).val('');
            }
        });

        $('.removeRowBtn').off('click').on('click', function() {
            $(this).closest('tr').remove();
        });
    }

    // Add new row on button click
    $('#certificationData').click(function(e) {
        var tableBody = $('#certificationDataTable tbody');
        var rowCount = tableBody.children('tr').length;
        var newRow = generateTableRow(rowCount + 1);
        tableBody.append(newRow);

        // Add validation for the new row
        addDateValidation();
    });

    // Add validation to existing rows
    addDateValidation();
});


    </script>
    <script>
        $(document).on('click', '.removeRowBtn', function() {
            $(this).closest('tr').remove();
        })
    </script>
    <div class="form-field-head">
        <div class="division-bar">
            <strong>Site Division/Project</strong> :
             {{ Helpers::getDivisionName($data->division_id) }} / Supplier
        </div>
    </div>
    <div id="change-control-fields">
        <div class="container-fluid">
            <div class="inner-block state-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="main-head">Record Workflow </div>
                    <div class="d-flex" style="gap:20px;">
                        @php
                            $userRoles = DB::table('user_roles')
                                ->where(['user_id' => Auth::user()->id, 'q_m_s_divisions_id' => 1])
                                ->get();
                            $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
                        @endphp
                        <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/supplier-audit-trail', $data->id) }}"> Audit Trail </a> </button>
                        @if ($data->stage == 1 && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Need for Sourcing of Starting Material
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancel
                            </button>
                        @elseif($data->stage == 2 && (in_array(4, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Request Justified
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Request Not Justified
                            </button>
                        @elseif($data->stage == 3 && (in_array(7, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#send-to-supplier-approve">
                                CQA Review Completed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 4 && (in_array(7, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#sendToPendingSupplierAudit">
                                Purchase Sample Request Initiated
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 5 && (in_array(39, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#supplierApprovedToObselete">
                               Purchase Sample Analysis Satisfactory
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#sendToPendingSupplierAudit">
                                Purchase Sample Analysis Not Satisfactory
                            </button>
                        @elseif($data->stage == 6 && (in_array(39, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#supplierApprovedToObselete">
                                F&D Review Completed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#sendToPendingSupplierAudit">
                                Request More Info
                            </button>
                        @elseif($data->stage == 7 && (in_array(39, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#supplierApprovedToObselete">
                                CQA Final Review Completed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#sendToPendingSupplierAudit">
                                Request More Info
                            </button>
                        @endif
                        <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                    </div>
                </div>
                <div class="status">
                    <div class="head">Current Status</div>
                    @if ($data->stage == 0)
                        <div class="progress-bars">
                            <div class="bg-danger">Closed-Cancelled</div>
                        </div>
                    @else
                        <div class="progress-bars d-flex" style="font-size: 15px;">
                            @if ($data->stage >= 1)
                                <div class="active">Opened</div>
                            @else
                                <div class="">Opened</div>
                            @endif
    
                            @if ($data->stage >= 2)
                                <div class="active">Pending Initiating Department Update </div>
                            @else
                                <div class="">Pending Initiating Department Update</div>
                            @endif
    
                            @if ($data->stage >= 3)
                                <div class="active">Pending Update FROM CQ</div>
                            @else
                                <div class="">Pending Update FROM CQ</div>
                            @endif
    
                            @if ($data->stage >= 4)
                                <div class="active">Pending Purchase Sample Reques</div>
                            @else
                                <div class="">Pending Purchase Sample Reques</div>
                            @endif
    
                            @if ($data->stage >= 5)
                                <div class="active">Pending CQA Review After Purchase Sample Request</div>
                            @else
                                <div class="">Pending CQA Review After Purchase Sample Request</div>
                            @endif
    
                            @if ($data->stage >= 6)
                                <div class="active">Pending F&D Review</div>
                            @else
                                <div class="">Pending F&D Review</div>
                            @endif
    
                            @if ($data->stage >= 7)
                                <div class="active">Pending CQA Final Review</div>
                            @else
                                <div class="">Pending CQA Final Review</div>
                            @endif
    
                            @if ($data->stage >= 8)
                                <div class="active bg-danger"> Obsolete</div>
                            @else
                                <div class="">Obsolete</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Supplier/Manufacturer/Vendor</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">HOD Review</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Supplier Details</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Score Card</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">QA Reviewer</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Risk Assessment</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm7')">QA Head Reviewer</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm8')">Activity Log</button>
            </div>

            <!--  Contract Tab content -->
            <form action="{{ route('supplier-update', $data->id) }} }}" method="POST" enctype="multipart/form-data">
            @csrf

                <div id="CCForm1" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Request Number</b></label>
                                    <input type="text" value="{{ $requestNUmber }}" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Division</b></label>
                                    <input type="text" disabled id="division_id" value="{{ Helpers::getDivisionName($data->division_id) }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Initiator</b></label>
                                    <input disabled type="text" name="initiator_id" id="initiator_id" value="{{ Helpers::getInitiatorName($data->initiator_id) }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiation"><b>Initiation Date</b></label>
                                    <input disabled type="text" value="{{ Helpers::getdateFormat($data->intiation_date) }}" >
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
                                                <option @if($data->assign_to == $user->id) selected @endif value="{{$user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        @endif                                    
                                    </select>
                                </div>
                            </div> -->

                            @php
                                $initiationDate = date('Y-m-d');
                                $dueDate = date('Y-m-d', strtotime($initiationDate . '+30 days'));
                            @endphp

                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="due-date">Date Due</label>
                                    <div><small class="text-primary">Please mention expected date of completion</small></div>
                                    <div class="calenderauditee">
                                    <div class="calenderauditee">
                                        <input readonly type="text" value="{{ Helpers::getdateFormat($data->due_date) }}" name="due_date" />
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                                    </div>
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
                                    <input id="docname" type="text" name="short_description" maxlength="255" required value="{{ $data->short_description }}">
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
                                            <option value="CQA" @if($data->initiator_group_code == 'CQA') selected @endif> Corpo Assurance Biopharma</option>
                                            <option value="CQC" @if($data->initiator_group_code == 'CQC') selected @endif> Central Quality Control</option>
                                            <option value="MANU" @if($data->initiator_group_code == 'MANU') selected @endif> Manufacturing</option>
                                            <option value="PSG" @if($data->initiator_group_code == 'PSG') selected @endif> Plasma Sourcing Group</option>
                                            <option value="CS" @if($data->initiator_group_code == 'CS') selected @endif> Central Stores</option>
                                            <option value="ITG" @if($data->initiator_group_code == 'ITG') selected @endif> Information Technology Group</option>
                                            <option value="MM" @if($data->initiator_group_code == 'MM') selected @endif> Molecular Medicine</option>
                                            <option value="CL" @if($data->initiator_group_code == 'CL') selected @endif> Central Laboratory</option>
                                            <option value="TT" @if($data->initiator_group_code == 'TT') selected @endif> Tech Team</option>
                                            <option value="QA" @if($data->initiator_group_code == 'QA') selected @endif> Quality Assurance</option>
                                            <option value="QM" @if($data->initiator_group_code == 'QM') selected @endif> Quality Management</option>
                                            <option value="IA" @if($data->initiator_group_code == 'IA') selected @endif> Administration</option>
                                            <option value="ACC" @if($data->initiator_group_code == 'ACC') selected @endif> Accounting</option>
                                            <option value="LOG" @if($data->initiator_group_code == 'LOG') selected @endif> Logistics</option>
                                            <option value="SM" @if($data->initiator_group_code == 'SM') selected @endif> Senior Management</option>
                                            <option value="BA" @if($data->initiator_group_code == 'BA') selected @endif> Business Administration</option>
                                        </select>
                                        @error('initiator_group_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Initiator Department Code</label>
                                        <input type="text" name="initiator_group_code" id="initiator_group_code"
                                            value="{{ $data->initiator_group_code }}" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Name of Manufacturer</label>
                                        <input type="text" name="manufacturerName" value="{{ $data->manufacturerName }}" id="manufacturerName" placeholder="Name of Manufacturer">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Name of Starting Material</label>
                                        <input type="text" value="{{ $data->starting_material }}" name="starting_material" id="starting_material">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Material Code</label>
                                        <input type="text" name="material_code" id="material_code" value="{{ $data->material_code }}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Pharmacopoeial Claim</label>
                                        <input type="text" name="pharmacopoeial_claim" id="pharmacopoeial_claim" value="{{ $data->pharmacopoeial_claim }}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">CEP Grade Material</label>
                                        <select id="cep_grade" name="cep_grade">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes" @if($data->cep_grade == 'Yes') selected @endif>Yes</option>
                                            <option value="No" @if($data->cep_grade == 'No') selected @endif>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for=" Attachments">CEP Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        <div class="file-attachment-field">
                                            <div disabled class="file-attachment-list" id="cep_attachment">
                                                @if ($data->cep_attachment)
                                                    @foreach (json_decode($data->cep_attachment) as $file)
                                                        <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file" data-file-name="{{ $file }}"><i
                                                                    class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="cep_attachment[]" oninput="addMultipleFiles(this, 'cep_attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="request_for">Request For</label>
                                        <select id="request_for" name="request_for">
                                            <option value="">---- Select ----</option>
                                            <option value="API" @if(isset($data->request_for) && $data->request_for == 'API') selected @endif>API</option>
                                            <option value="Excipient" @if(isset($data->request_for) && $data->request_for == 'Excipient') selected @endif>Excipient</option>
                                            <option value="New Manufacturer" @if(isset($data->request_for) && $data->request_for == 'New Manufacturer') selected @endif>New Manufacturer</option>
                                            <option value="Existing Manufacturer" @if(isset($data->request_for) && $data->request_for == 'Existing Manufacturer') selected @endif>Existing Manufacturer</option>
                                            <option value="Additional Site of Existing Manufacturer" @if(isset($data->request_for) && $data->request_for == 'Additional Site of Existing Manufacturer') selected @endif>Additional Site of Existing Manufacturer</option>
                                            <option value="Brand New API" @if(isset($data->request_for) && $data->request_for == 'Brand New API') selected @endif>Brand New API</option>
                                            <option value="Existing API" @if(isset($data->request_for) && $data->request_for == 'Existing API') selected @endif>Existing API</option>
                                            <option value="Brand New Excipient" @if(isset($data->request_for) && $data->request_for == 'Brand New Excipient') selected @endif>Brand New Excipient</option>
                                            <option value="Existing Excipient" @if(isset($data->request_for) && $data->request_for == 'Existing Excipient') selected @endif>Existing Excipient</option>
                                            <option value="R&D development" @if(isset($data->request_for) && $data->request_for == 'R&D development') selected @endif>R&D development</option>
                                            <option value="Site Transfer" @if(isset($data->request_for) && $data->request_for == 'Site Transfer') selected @endif>Site Transfer</option>
                                            <option value="Alternate manufacturer" @if(isset($data->request_for) && $data->request_for == 'Alternate manufacturer') selected @endif>Alternate manufacturer</option>
                                            <option value="Excipient" @if(isset($data->request_for) && $data->request_for == 'Excipient') selected @endif>Excipient</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Attach Three Batch COAs</label>
                                        <select id="attach_batch" name="attach_batch">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes" @if($data->attach_batch == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($data->attach_batch == "No") selected @endif>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Justification for Request</label>
                                        <textarea type="text" name="request_justification" value="{{ $data->request_justification }}" class="">{{ $data->request_justification }}</textarea>
                                    </div>
                                </div>

                                <div class="sub-head">
                                    CQA Department
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Availability of Manufacturer COAs</label>
                                        <select id="manufacturer_availability" name="manufacturer_availability">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes" @if($data->manufacturer_availability == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($data->manufacturer_availability == "No") selected @endif>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Request Accepted</label>
                                        <select id="request_accepted" name="request_accepted">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes" @if($data->request_accepted == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($data->request_accepted == "No") selected @endif>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Remark</label>
                                        <textarea type="text" name="cqa_remark" id="cqa_remark" class="">{{ $data->cqa_remark }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Accepted By</label>
                                        <select type="hidden" name="accepted_by" id="accepted_by">
                                            <option value="">---- Select ----</option>
                                            @if(!empty($users))
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" @if($data->accepted_by == $user->id) selected @endif>{{ $user->name }}</option>
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
                                            <option value="Yes" @if($data->pre_purchase_sample == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($data->pre_purchase_sample == "No") selected @endif>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Justification</label>
                                        <textarea type="text" name="justification" id="justification" class="">{{ $data->justification }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">CQA Coordinator</label>
                                        <select type="hidden" name="cqa_coordinator" id="cqa_coordinator">
                                            <option value="">---- Select ----</option>
                                            @if(!empty($users))
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" @if($data->cqa_coordinator == $user->id) selected @endif>{{ $user->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Pre Purchase Sample Analysis Completed?</label>
                                        <select id="pre_purchase_sample_analysis" name="pre_purchase_sample_analysis">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes" @if($data->pre_purchase_sample_analysis == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($data->pre_purchase_sample_analysis == "No") selected @endif>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Availability of COAS After Analysis</label>
                                        <select id="availability_od_coa" name="availability_od_coa">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes" @if($data->availability_od_coa == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($data->availability_od_coa == "No") selected @endif>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Analyzed on Location</label>
                                        <input type="text" name="analyzed_location" id="analyzed_location" value="{{ $data->analyzed_location }}">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Review Comment of CQA</label>
                                        <textarea type="text" name="cqa_comment" id="cqa_comment" class="">{{ $data->cqa_comment }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">If Analysis found satisfactory of Pre-purchase samples send intimation</label>
                                        <div><small class="text-primary">To: Formulation and Development / MS&T Department.</small></div>
                                        <div><small class="text-primary">From: Corporate Quality Assurance</small></div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Material Name</label>
                                        <input type="text" name="materialName" id="materialName" value="{{ $data->materialName }}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Name of the Manufacturer</label>
                                        <input type="text" name="manufacturerNameNew" id="manufacturerNameNew" value="{{ $data->manufacturerNameNew }}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Analyzed on Location</label>
                                        <input type="text" name="analyzedLocation" id="analyzedLocation" value="{{ $data->analyzedLocation }}">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Review Comment of Corporate CQA</label>
                                        <textarea type="text" name="cqa_corporate_comment" id="cqa_corporate_comment" class="">{{ $data->cqa_corporate_comment }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">COA's Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        <div class="file-attachment-field">
                                            <div disabled class="file-attachment-list" id="coa_attachment">
                                                @if ($data->coa_attachment)
                                                    @foreach (json_decode($data->coa_attachment) as $file)
                                                        <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file" data-file-name="{{ $file }}"><i
                                                                    class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="coa_attachment[]" oninput="addMultipleFiles(this, 'coa_attachment')" multiple>
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
                                                    <option value="{{ $user->id }}" @if($data->cqa_designee == $user->id) selected @endif>{{ $user->name }}</option>
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
                                            <option value="Yes" @if($data->sample_ordered == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($data->sample_ordered == "No") selected @endif>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Justification</label>
                                        <textarea type="text" name="sample_order_justification" id="sample_order_justification" class="">{{ $data->sample_order_justification }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Acknowledge By</label>
                                        <select type="hidden" name="acknowledge_by" id="acknowledge_by">
                                            <option value="">---- Select ----</option>
                                            @if(!empty($users))
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" @if($data->acknowledge_by == $user->id) selected @endif>{{ $user->name }}</option>
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
                                            <option value="Yes" @if($data->trail_status_feedback == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($data->trail_status_feedback == "No") selected @endif>No</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- To be filled by CQA Department -->

                                <div class="col-lg-6"></div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Sample Stand Approved?</label>
                                        <select id="sample_stand_approved" name="sample_stand_approved">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes" @if($data->sample_stand_approved == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($data->sample_stand_approved == "No") selected @endif>No</option>
                                            <option value="N/A" @if($data->sample_stand_approved == "N/A") selected @endif>N/A</option>
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
                                                        <th style="width: 15%;">Selection</th>
                                                        <th>Remark</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="flex text-center">1.1</td>
                                                        <td>TSE/BSE</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                {{-- <div class="group-input"> --}}
                                                                    <select class="custom-select" id="tse_bse" name="tse_bse">
                                                                        <option value="">---- Select ----</option>
                                                                        <option value="Yes" @if($data->tse_bse == "Yes") selected @endif>Yes</option>
                                                                        <option value="No" @if($data->tse_bse == "No") selected @endif>No</option>
                                                                    </select>
                                                                {{-- </div> --}}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="tse_bse_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->tse_bse_remark }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">1.2</td>
                                                        <td>Residual Solvent</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="residual_solvent" name="residual_solvent">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes" @if($data->residual_solvent == "Yes") selected @endif>Yes</option>
                                                                    <option value="No" @if($data->residual_solvent == "No") selected @endif>No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="residual_solvent_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->residual_solvent_remark }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">1.3</td>
                                                        <td>GMO</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="gmo" name="gmo">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes" @if($data->gmo == "Yes") selected @endif>Yes</option>
                                                                    <option value="No" @if($data->gmo == "No") selected @endif>No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="gmo_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->gmo_remark }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">1.4</td>
                                                        <td>Melamine</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="melamine" name="melamine">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes" @if($data->melamine == "Yes") selected @endif>Yes</option>
                                                                    <option value="No" @if($data->melamine == "No") selected @endif>No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="melamine_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->melamine_remark }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">1.5</td>
                                                        <td>Gluten</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="gluten" name="gluten">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes" @if($data->gluten == "Yes") selected @endif>Yes</option>
                                                                    <option value="No" @if($data->gluten == "No") selected @endif>No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="gluten_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->gluten_remark }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">1.6</td>
                                                        <td>Nitrosamine</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select  class="custom-select" id="nitrosamine" name="nitrosamine">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes" @if($data->nitrosamine == "Yes") selected @endif>Yes</option>
                                                                    <option value="No" @if($data->nitrosamine == "No") selected @endif>No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="nitrosamine_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->nitrosamine_remark }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">1.7</td>
                                                        <td>WHO</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="who" name="who">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes" @if($data->who == "Yes") selected @endif>Yes</option>
                                                                    <option value="No" @if($data->who == "No") selected @endif>No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="who_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->who_remark }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">1.8</td>
                                                        <td>GMP</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="gmp" name="gmp">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes" @if($data->gmp == "Yes") selected @endif>Yes</option>
                                                                    <option value="No" @if($data->gmp == "No") selected @endif>No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="gmp_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->gmp_remark }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">1.9</td>
                                                        <td>ISO Cerificates</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="iso_certificate" name="iso_certificate">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes" @if($data->iso_certificate == "Yes") selected @endif>Yes</option>
                                                                    <option value="No" @if($data->iso_certificate == "No") selected @endif>No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="iso_certificate_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->iso_certificate_remark }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">1.10</td>
                                                        <td>Manufacturing License</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="manufacturing_license" name="manufacturing_license">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes" @if($data->manufacturing_license == "Yes") selected @endif>Yes</option>
                                                                    <option value="No" @if($data->manufacturing_license == "No") selected @endif>No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="manufacturing_license_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->manufacturing_license_remark }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">1.11</td>
                                                        <td>CEP</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="cep" name="cep">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes" @if($data->cep == "Yes") selected @endif>Yes</option>
                                                                    <option value="No" @if($data->cep == "No") selected @endif>No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="cep_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->cep_remark }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">1.12</td>
                                                        <td>MSDS</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="msds" name="msds">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes" @if($data->attach_batch == "Yes") selected @endif>Yes</option>
                                                                    <option value="No" @if($data->attach_batch == "Yes") selected @endif>No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="msds_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->tse_bse_remark }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">1.13</td>
                                                        <td>Elemental Impurities</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="elemental_impurities" name="elemental_impurities">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes" @if($data->elemental_impurities == "Yes") selected @endif>Yes</option>
                                                                    <option value="No" @if($data->elemental_impurities == "No") selected @endif>No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="elemental_impurities_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->elemental_impurities_remark }}</textarea>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex text-center">1.14</td>
                                                        <td>Assessment/Declaration of Azido Impurities as Applicable</td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                <select class="custom-select" id="declaration" name="declaration">
                                                                    <option value="">---- Select ----</option>
                                                                    <option value="Yes" @if($data->declaration == "Yes") selected @endif>Yes</option>
                                                                    <option value="No" @if($data->declaration == "No") selected @endif>No</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                                <textarea name="declaration_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->declaration_remark }}</textarea>
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
                                            <option value="Yes" @if($data->supply_chain_availability == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($data->supply_chain_availability == "No") selected @endif>No</option>
                                            <option value="N/A" @if($data->supply_chain_availability == "N/A") selected @endif>N/A</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Availability of Quality Agreement?</label>
                                        <select id="quality_agreement_availability" name="quality_agreement_availability">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes" @if($data->quality_agreement_availability == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($data->quality_agreement_availability == "No") selected @endif>No</option>
                                            <option value="N/A" @if($data->quality_agreement_availability == "N/A") selected @endif>N/A</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Risk Assessment Done?</label>
                                        <select id="risk_assessment_done" name="risk_assessment_done">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes" @if($data->risk_assessment_done == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($data->risk_assessment_done == "No") selected @endif>No</option>
                                            <option value="N/A" @if($data->risk_assessment_done == "N/A") selected @endif>N/A</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Risk Rating</label>
                                        <select id="risk_rating" name="risk_rating">
                                            <option value="">---- Select ----</option>
                                            <option value="High" @if($data->risk_rating == "High") selected @endif>High</option>
                                            <option value="Medium" @if($data->risk_rating == "Medium") selected @endif>Medium</option>
                                            <option value="Low" @if($data->risk_rating == "Low") selected @endif>Low</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Manufacturer Audit planned</label>
                                        <select id="manufacturer_audit_planned" name="manufacturer_audit_planned">
                                            <option value="">---- Select ----</option>
                                            <option value="Yes" @if($data->manufacturer_audit_planned == "Yes") selected @endif>Yes</option>
                                            <option value="Not Required" @if($data->manufacturer_audit_planned == "Not Required") selected @endif>Not Required</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Maufacturer Audit Conducted On</label>
                                        <input type="text" id="manufacturer_audit_conducted" name="manufacturer_audit_conducted" value="{{ $data->manufacturer_audit_conducted }}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Manufacturer Can be? </label>
                                        <select id="manufacturer_can_be" name="manufacturer_can_be">
                                            <option value="">---- Select ----</option>
                                            <option value="Approved" @if($data->manufacturer_can_be == "Approved") selected @endif>Approved</option>
                                            <option value="Not Approved" @if($data->manufacturer_can_be == "Not Approved") selected @endif>Not Approved</option>
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
                                    <textarea  class="tiny" type="text" name="HOD_feedback"value="{{ $data->HOD_feedback }}" placeholder="Enter HOD Feedback" id="HOD_feedback">{{ $data->HOD_feedback }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="HOD_comment">HOD Comments</label>
                                    <textarea class="tiny" type="text" name="HOD_comment" value="{{ $data->HOD_comment }}" placeholder="Enter HOD Comment" id="HOD_comment">{{ $data->HOD_comment }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="HOD_attachment">HOD Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="HOD_attachment">
                                            @if ($data->HOD_attachment)
                                                @foreach (json_decode($data->HOD_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file" data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="HOD_attachment[]" oninput="addMultipleFiles(this, 'HOD_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="hod_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="hod_additional_attachment">
                                            @if ($data->hod_additional_attachment)
                                                @foreach (json_decode($data->hod_additional_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file" data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="hod_additional_attachment[]" oninput="addMultipleFiles(this, 'hod_additional_attachment')" multiple>
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
                            <!-- <div class="col-12">
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
                                            @if ($certificationData && is_array($certificationData))
                                                @foreach ($certificationData as $gridData)
                                                    <tr>
                                                        <td>
                                                            <input disabled type="text" name="certificationData[{{ $loop->index }}][serial]"
                                                                value="{{ $loop->index + 1 }}">
                                                        </td>
                                                        <td>
                                                            <input class="type" type="text" name="certificationData[{{ $loop->index }}][type]"
                                                                value="{{ isset($gridData['type']) ? $gridData['type'] : '' }}">
                                                        </td>
                                                        <td>
                                                            <input class="issuingAgency" type="text" name="certificationData[{{ $loop->index }}][issuingAgency]"
                                                                value="{{ isset($gridData['issuingAgency']) ? $gridData['issuingAgency'] : '' }}">
                                                        </td>
                                                        <td>
                                                            <input class="issueDate" type="date"
                                                                name="certificationData[{{ $loop->index }}][issueDate]"
                                                                value="{{ isset($gridData['issueDate']) ? $gridData['issueDate'] : '' }}">
                                                        </td>
                                                        <td>
                                                            <input class="expiryDate" type="date"
                                                                name="certificationData[{{ $loop->index }}][expiryDate]"
                                                                value="{{ isset($gridData['expiryDate']) ? $gridData['expiryDate'] : '' }}">
                                                        </td>
                                                        <td>
                                                            <input class="supportingDoc" type="text"
                                                                name="certificationData[{{ $loop->index }}][supportingDoc]"
                                                                value="{{ isset($gridData['supportingDoc']) ? $gridData['supportingDoc'] : '' }}">
                                                        </td>
                                                        <td>
                                                            <input class="remarks" type="text"
                                                                name="certificationData[{{ $loop->index }}][remarks]"
                                                                value="{{ isset($gridData['remarks']) ? $gridData['remarks'] : '' }}">
                                                        </td>
                                                        <td><button type="text" class="removeRowBtn">Remove</button></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <td><input type="text" name="certificationData[0][serial]" value="1" readonly></td>
                                                <td><input type="text" name="certificationData[0][type]"></td>
                                                <td><input type="text" name="certificationData[0][issuingAgency]"></td>
                                                <td><input type="date" name="certificationData[0][issueDate]"></td>
                                                <td><input type="date" name="certificationData[0][expiryDate]"></td>
                                                <td><input type="text" name="certificationData[0][supportingDoc]"></td>
                                                <td><input type="text" name="certificationData[0][remarks]"></td>
                                                <td><button type="text" class="removeRowBtn">Remove</button></td>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div> -->

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Issues">
                                        Certifications & Accreditation
                                        <button type="button" name="ann" id="certificationData">+</button>
                                    </label>
                                    <table class="table table-bordered" id="certificationDataTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 60px;">Row #</th>
                                                <th>Type</th>
                                                <th>Issuing Agency</th>
                                                <th>Issue Date</th>
                                                <th>Expiry Date</th>
                                                <th>Supporting Document</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($certificationData && is_array($certificationData))
                                                @foreach ($certificationData as $gridData)
                                                    <tr>
                                                        <td style="width: 60px;">
                                                            <input disabled type="text" name="certificationData[{{ $loop->index }}][serial]" value="{{ $loop->index + 1 }}">
                                                        </td>
                                                        <td>
                                                            <input class="type" type="text" name="certificationData[{{ $loop->index }}][type]" value="{{ isset($gridData['type']) ? $gridData['type'] : '' }}">
                                                        </td>
                                                        <td>
                                                            <input class="issuingAgency" type="text" name="certificationData[{{ $loop->index }}][issuingAgency]" value="{{ isset($gridData['issuingAgency']) ? $gridData['issuingAgency'] : '' }}">
                                                        </td>
                                                        <td>
                                                            <input class="issueDate" type="date" name="certificationData[{{ $loop->index }}][issueDate]" value="{{ isset($gridData['issueDate']) ? $gridData['issueDate'] : '' }}" max="{{ date('Y-m-d') }}">
                                                        </td>
                                                        <td>
                                                            <input class="expiryDate" type="date" name="certificationData[{{ $loop->index }}][expiryDate]" value="{{ isset($gridData['expiryDate']) ? $gridData['expiryDate'] : '' }}" min="{{ isset($gridData['issueDate']) ? $gridData['issueDate'] : '' }}" {{ isset($gridData['issueDate']) ? '' : 'disabled' }}>
                                                        </td>
                                                        <td>
                                                            <input class="supportingDoc" type="text" name="certificationData[{{ $loop->index }}][supportingDoc]" value="{{ isset($gridData['supportingDoc']) ? $gridData['supportingDoc'] : '' }}">
                                                        </td>
                                                        <td>
                                                            <input class="remarks" type="text" name="certificationData[{{ $loop->index }}][remarks]" value="{{ isset($gridData['remarks']) ? $gridData['remarks'] : '' }}">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="removeRowBtn">Remove</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td style="width: 60px;"><input type="text" name="certificationData[0][serial]" value="1" readonly></td>
                                                    <td><input type="text" name="certificationData[0][type]"></td>
                                                    <td><input type="text" name="certificationData[0][issuingAgency]"></td>
                                                    <td><input type="date" name="certificationData[0][issueDate]" class="issueDate" max="{{ date('Y-m-d') }}"></td>
                                                    <td><input type="date" name="certificationData[0][expiryDate]" class="expiryDate" disabled></td>
                                                    <td><input type="text" name="certificationData[0][supportingDoc]"></td>
                                                    <td><input type="text" name="certificationData[0][remarks]"></td>
                                                    <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier.">Supplier</label>
                                    <input type="text" name="supplier_name" value="{{ $data->supplier_name }}" id="supplier_name" placeholder="Enter Supplier Name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier.">Supplier ID</label>
                                <input type="text" name="supplier_id" value="{{ $data->supplier_id }}" placeholder="Enter Supplier ID">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="">Manufacturer</label>
                                    <input type="text" name="manufacturer_name" value="{{ $data->manufacturer_name }}" placeholder="Enter Manufacturer ID">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="manufacturer">Manufacturer ID</label>
                                <input type="text" name="manufacturer_id" value="{{ $data->manufacturer_id }}" placeholder="Enter Manufacturer ID">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="">Vendor</label>
                                    <input type="text" name="vendor_name" value="{{ $data->vendor_name }}" placeholder="Enter Vendor Name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="manufacturer">Vendor ID</label>
                                    <input type="text"  name="vendor_id" value="{{ $data->vendor_id }}" placeholder="Enter Vendor ID">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Contact Person">Contact Person</label>
                                    <input type="text" name="contact_person" value="{{ $data->contact_person }}" id="contact_person" placeholder="Enter Contact Person">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Other Contacts">Other Contacts</label>
                                    <input id="other_contacts" type="text" name="other_contacts" value="{{ $data->other_contacts }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Supplier Services">Supplier Services</label>
                                    <textarea class="tiny" name="supplier_serivce" value="{{ $data->supplier_serivce }}" id="supplier_serivce" cols="30" >{{ $data->supplier_serivce }}</textarea>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Zone">Zone</label>
                                    <select name="zone">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Asia" @if($data->zone == "Asia") selected @endif>Asia</option>
                                        <option value="Europe" @if($data->zone == "Europe") selected @endif>Europe</option>
                                        <option value="Africa" @if($data->zone == "Africa") selected @endif>Africa</option>
                                        <option value="Central America" @if($data->zone == "Central America") selected @endif>Central America</option>
                                        <option value="South America" @if($data->zone == "South America") selected @endif>South America</option>
                                        <option value="Oceania" @if($data->zone == "Oceania") selected @endif>Oceania</option>
                                        <option value="North America" @if($data->zone == "North America") selected @endif>North America</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Country">Country</label>
                                    <select name="country" class="form-select country" aria-label="Default select example" onchange="loadStates()">
                                        <option value="">Select Country</option>
                                        <option value="{{ $data->country }}" selected>{{ $data->country }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="City">State</label>
                                    <select name="state" class="form-select state" aria-label="Default select example" onchange="loadCities()">
                                        <option value="{{ $data->state }}" selected>{{ $data->state }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="State/District">City</label>
                                    <select name="city" class="form-select city" aria-label="Default select example">
                                        <option value="{{ $data->city }}" selected>{{ $data->city }}</option>
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
                                    <textarea type="text" value="{{ $data->address }}" name="address" id="address">{{ $data->address }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Web Site">Supplier Web Site</label>
                                    <input type="text" name="suppplier_web_site" value="{{ $data->suppplier_web_site }}" placeholder="Enter Website ">
                                </div>
                            </div>
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="ISO Certification date">ISO Certification Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="iso_certified_date" readonly placeholder="DD-MMM-YYYY" value="{{ Helpers::getdateFormat($data->iso_certified_date) }}" />
                                        <input type="date" name="iso_certified_date" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ $data->iso_certified_date }}" class="hide-input" oninput="handleDateInput(this, 'iso_certified_date')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="iso_certificate_attachment">ISO Ceritificate Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="iso_certificate_attachment">
                                            @if ($data->iso_certificate_attachment)
                                                @foreach (json_decode($data->iso_certificate_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file" data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="iso_certificate_attachment[]" oninput="addMultipleFiles(this, 'iso_certificate_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Contracts">Contracts</label>
                                    <input type="text" name="suppplier_contacts" value="{{ $data->suppplier_contacts }}" id="suppplier_contacts">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Related Non Conformances">Related Non Conformances</label>
                                    <input type="text" name="related_non_conformance" value="{{ $data->related_non_conformance }}" id="related_non_conformance">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Contracts/Agreements">Supplier Contracts/Agreements</label>
                                    <input type="text" id="suppplier_agreement" name="suppplier_agreement" value="{{ $data->suppplier_agreement }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Regulatory History">Regulatory History</label>
                                    <input type="text" id="regulatory_history" name="regulatory_history" value="{{ $data->regulatory_history }}">
                                </div>
                            </div>
                        
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Distribution Sites">Distribution Sites</label>
                                    <input type="text" id="distribution_sites" name="distribution_sites" value="{{ $data->distribution_sites }}" maxlength="50">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Quality Management ">Manufacturing Sites </label>
                                    <textarea class="tiny" id="manufacturing_sited" type="text" name="manufacturing_sited" value="{{ $data->manufacturing_sited }}">{{ $data->manufacturing_sited  }}</textarea>
                                </div>
                            </div>  
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Quality Management ">Quality Management </label>
                                    <textarea class="tiny" id="quality_management" type="text" name="quality_management" value="{{ $data->quality_management }}">{{ $data->quality_management  }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Business History">Business History</label>
                                    <textarea class="tiny" id="bussiness_history" type="text" name="bussiness_history" value="{{ $data->bussiness_history }}">{{ $data->bussiness_history  }}</textarea>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Performance History ">Performance History </label>
                                    <textarea class="tiny" id="performance_history" type="text" name="performance_history" value="{{ $data->performance_history }}">{{ $data->performance_history  }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Compliance Risk">Compliance Risk</label>
                                    <textarea class="tiny" id="compliance_risk" type="text" name="compliance_risk" value="{{ $data->compliance_risk }}">{{ $data->compliance_risk  }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="supplier_detail_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="supplier_detail_additional_attachment">
                                            @if ($data->supplier_detail_additional_attachment)
                                                @foreach (json_decode($data->supplier_detail_additional_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file" data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="supplier_detail_additional_attachment[]" oninput="addMultipleFiles(this, 'supplier_detail_additional_attachment')" multiple>
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
                                        <option value="Unacceptable" @if($data->cost_reduction == "Unacceptable") selected @endif>Unacceptable</option>
                                        <option value="Does Not Meet Expectation" @if($data->cost_reduction == "Does Not Meet Expectation") selected @endif>Does Not Meet Expectation</option>
                                        <option value="Meets Expectations" @if($data->cost_reduction == "Meets Expectations") selected @endif>Meets Expectations</option>
                                        <option value="Exceeds Expectations" @if($data->cost_reduction == "Exceeds Expectations") selected @endif>Exceeds Expectations</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Cost Reduction Weight">Cost Reduction Weight</label>
                                    <select id="cost_reduction_weight" name="cost_reduction_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" @if($data->cost_reduction_weight == $i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Payment Terms">Payment Terms</label>
                                    <select id="payment_term" name="payment_term">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="< 30 Days" @if($data->payment_term == "< 30 Days") selected @endif>< 30 Days</option>
                                        <option value="30 - 45 Days" @if($data->payment_term == "30 - 45 Days") selected @endif>30 - 45 Days</option>
                                        <option value="45 - 60 Days" @if($data->payment_term == "45 - 60 Days") selected @endif>45 - 60 Days</option>
                                        <option value=">= 60 Days" @if($data->payment_term == ">= 60 Days") selected @endif>>= 60 Days</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Payment Terms Weight">Payment Terms Weight</label>
                                    <select name="payment_term_weight" id="payment_term_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" @if($data->payment_term_weight == $i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Lead Time Days">Lead Time Days</label>
                                    <select name="lead_time_days" name="lead_time_days">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="> 11 Days" @if($data->lead_time_days == "> 11 Days") selected @endif> > 11 Days</option>
                                        <option value="6 - 10" @if($data->lead_time_days == "6 - 10") selected @endif>6 - 10</option>
                                        <option value="3 -5" @if($data->lead_time_days == "3 -5") selected @endif>3 -5</option>
                                        <option value="1 Day or Consignment" @if($data->lead_time_days == "1 Day or Consignment") selected @endif>1 Day or Consignment</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Lead Time Days Weight">Lead Time Days Weight</label>
                                    <select name="lead_time_days_weight" id="lead_time_days_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" @if($data->lead_time_days_weight == $i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="On-Time Delivery">On-Time Delivery</label>
                                    <select id="ontime_delivery" name="ontime_delivery">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="100%" @if($data->ontime_delivery == "100%") selected @endif>100%</option>
                                        <option value="98-99%" @if($data->ontime_delivery == "98-99%") selected @endif>98-99%</option>
                                        <option value="96-97%" @if($data->ontime_delivery == "96-97%") selected @endif>96-97%</option>
                                        <option value="< 95%" @if($data->ontime_delivery == "< 95%") selected @endif>< 95%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="On-Time Delivery Weight">On-Time Delivery Weight</label>
                                    <select id="ontime_delivery_weight" name="ontime_delivery_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" @if($data->ontime_delivery_weight == $i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Business Planning">Supplier Business Planning</label>
                                    <select id="supplier_bussiness_planning" name="supplier_bussiness_planning">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Not Information at All" @if($data->supplier_bussiness_planning == "Not Information at All") selected @endif>Not Information at All</option>
                                        <option value="No Formal Information About" @if($data->supplier_bussiness_planning == "No Formal Information About") selected @endif>No Formal Information About</option>
                                        <option value="Yes - Partially Aligned With" @if($data->supplier_bussiness_planning == "Yes - Partially Aligned With") selected @endif></option>
                                        <option value="Yes - Completely Aligns" @if($data->supplier_bussiness_planning == "Yes - Completely Aligns") selected @endif></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Business Weight">Supplier Business Weight</label>
                                    <select id="supplier_bussiness_planning_weight" name="supplier_bussiness_planning_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" @if($data->supplier_bussiness_planning_weight == $i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Rejection in PPM">Rejection in PPM</label>
                                    <select id="rejection_ppm" name="rejection_ppm">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="> 500001 Defects PPM" @if($data->rejection_ppm == "> 500001 Defects PPM") selected @endif>> 500001 Defects PPM</option>
                                        <option value="5001 - 50000 Defects PPM" @if($data->rejection_ppm == "5001 - 50000 Defects PPM") selected @endif>5001 - 50000 Defects PPM</option>
                                        <option value="501 - 500 Defects PPM" @if($data->rejection_ppm == "501 - 500 Defects PPM") selected @endif>501 - 5000 Defects PPM</option>
                                        <option value="Upto 500 Defects PPM" @if($data->rejection_ppm == "Upto 500 Defects PPM") selected @endif>Upto 500 Defects PPM"</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Rejection in PPM Weight">Rejection in PPM Weight</label>
                                    <select id="rejection_ppm_weight" name="rejection_ppm_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" @if($data->rejection_ppm_weight == $i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Quality Systems">Quality Systems</label>
                                    <select id="quality_system" name="quality_system">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="No System/No Team" @if($data->quality_system == "No System/No Team") selected @endif>No System/No Team</option>
                                        <option value="System Not Certified" @if($data->quality_system == "System Not Certified") selected @endif>System Not Certified</option>
                                        <option value="ISO 9000 Cert" @if($data->quality_system == "ISO 9000 Cert") selected @endif>ISO 9000 Cert</option>
                                        <option value="ISO 9000 & 1400 Cert" @if($data->quality_system == "ISO 9000 & 1400 Cert") selected @endif>ISO 9000 & 1400 Cert</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Quality Systems Weight">Quality Systems Weight</label>
                                    <select id="quality_system_ranking" name="quality_system_ranking">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" @if($data->quality_system_ranking == $i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>  
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="# of CAR's generated"># of CAR's generated</label>
                                    <select id="car_generated" name="car_generated">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="0" @if($data->car_generated == "0") selected @endif>0</option>
                                        <option value="> 8" @if($data->car_generated == "> 8") selected @endif>> 8</option>
                                        <option value="2-7" @if($data->car_generated == "2-7") selected @endif>2-7</option>
                                        <option value="0-1" @if($data->car_generated == "0-1") selected @endif>0-1</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="# of CAR's generated Weight"># of CAR's generated Weight</label>
                                    <select id="car_generated_weight" name="car_generated_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" @if($data->car_generated_weight == $i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="CAR Closure Time">CAR Closure Time</label>
                                    <select id="closure_time" name="closure_time">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="> 60" @if($data->closure_time == "> 60") selected @endif>> 60</option>
                                        <option value="30-60" @if($data->closure_time == "30-60") selected @endif>30-60</option>
                                        <option value="15-30" @if($data->closure_time == "15-30") selected @endif>15-30</option>
                                        <option value="0-15" @if($data->closure_time == "0-15") selected @endif>0-15</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="CAR Closure Time Weight">CAR Closure Time Weight</label>
                                    <select id="closure_time_weight" name="closure_time_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" @if($data->closure_time_weight == $i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="End-User Satisfaction">End-User Satisfaction</label>
                                    <select id="end_user_satisfaction" name="end_user_satisfaction">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Information Lacks" @if($data->end_user_satisfaction == "Information Lacks") selected @endif>Information Lacks</option>
                                        <option value="Not Reactive Enough" @if($data->end_user_satisfaction == "Not Reactive Enough") selected @endif>Not Reactive Enough</option>
                                        <option value="Required" @if($data->end_user_satisfaction == "Required") selected @endif>Required</option>
                                        <option value="Active Participation" @if($data->end_user_satisfaction == "Active Participation") selected @endif>Active Participation</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="End-User Satisfaction Weight">End-User Satisfaction Weight</label>
                                    <select id="end_user_satisfaction_weight" name="end_user_satisfaction_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" @if($data->end_user_satisfaction_weight == $i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="score_card_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="score_card_additional_attachment">
                                            @if ($data->score_card_additional_attachment)
                                                @foreach (json_decode($data->score_card_additional_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file" data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="score_card_additional_attachment[]" oninput="addMultipleFiles(this, 'score_card_additional_attachment')" multiple>
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
                                    <textarea  class="tiny" type="text" name="QA_reviewer_feedback" placeholder="Enter QA Reviewer Feedback" id="QA_reviewer_feedback">{{ $data->QA_reviewer_feedback }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_reviewer_comment">QA Reviewer Comment</label>
                                    <textarea class="tiny" type="text" name="QA_reviewer_comment" placeholder="Enter QA Reviewer Comment" id="QA_reviewer_comment">{{ $data->QA_reviewer_comment }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_reviewer_attachment">QA Reviewer Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="QA_reviewer_attachment">
                                            @if ($data->QA_reviewer_attachment)
                                                @foreach (json_decode($data->QA_reviewer_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file" data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="QA_reviewer_attachment[]" oninput="addMultipleFiles(this, 'QA_reviewer_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="qa_reviewer_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="qa_reviewer_additional_attachment">
                                            @if ($data->qa_reviewer_additional_attachment)
                                                @foreach (json_decode($data->qa_reviewer_additional_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file" data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="qa_reviewer_additional_attachment[]" oninput="addMultipleFiles(this, 'qa_reviewer_additional_attachment')" multiple>
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
                                        <input type="text" id="last_audit_date"  placeholder="DD-MMM-YYYY"  value="{{ Helpers::getdateFormat($data->last_audit_date) }}" />
                                        <input type="date" name="last_audit_date" value="{{ $data->last_audit_date }}"
                                            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'last_audit_date')" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Last Audit Date">Next Audit Date</label>
                                    <div class="calenderauditee"> 
                                        <input type="text" id="next_audit_date"  placeholder="DD-MMM-YYYY"  value="{{ Helpers::getdateFormat($data->next_audit_date) }}" />
                                        <input type="date" name="next_audit_date" value="{{ $data->next_audit_date }}"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, 'next_audit_date')" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Audit Frequency">Audit Frequency</label>
                                    <select id="audit_frequency" name="audit_frequency">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Every 10 Years" @if($data->audit_frequency == "Every 10 Years") selected @endif>Every 10 Years</option>
                                        <option value="Every 9 Years" @if($data->audit_frequency == "Every 9 Years") selected @endif>Every 9 Years</option>
                                        <option value="Every 8 Years" @if($data->audit_frequency == "Every 8 Years") selected @endif>Every 8 Years</option>
                                        <option value="Every 7 Years" @if($data->audit_frequency == "Every 7 Years") selected @endif>Every 7 Years</option>
                                        <option value="Every 6 Years" @if($data->audit_frequency == "Every 6 Years") selected @endif>Every 6 Years</option>
                                        <option value="Every 5 Years" @if($data->audit_frequency == "Every 5 Years") selected @endif>Every 5 Years</option>
                                        <option value="Every 4 Years" @if($data->audit_frequency == "Every 4 Years") selected @endif>Every 4 Years</option>
                                        <option value="Every 3 Years" @if($data->audit_frequency == "Every 3 Years") selected @endif>Every 3 Years</option>
                                        <option value="Every 2 Years" @if($data->audit_frequency == "Every 2 Years") selected @endif>Every 2 Years</option>
                                        <option value="Annual" @if($data->audit_frequency == "Annual") selected @endif>Annual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Last Audit Result">Last Audit Result</label>
                                    <select id="last_audit_result" name="last_audit_result">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="5" @if($data->last_audit_result == "5") selected @endif>5</option>
                                        <option value="4" @if($data->last_audit_result == "4") selected @endif>4</option>
                                        <option value="3" @if($data->last_audit_result == "3") selected @endif>3</option>
                                        <option value="2" @if($data->last_audit_result == "2") selected @endif>2</option>
                                        <option value="1" @if($data->last_audit_result == "1") selected @endif>1</option>
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
                                        <option value="Operation, R&M - Level 3" @if($data->facility_type == "Operation, R&M - Level 3") selected @endif>Operation, R&M - Level 3</option>
                                        <option value="Operation, R&M - Level 2" @if($data->facility_type == "Operation, R&M - Level 2") selected @endif>Operation, R&M - Level 2</option>
                                        <option value="Operation Only, Stock Point Only" @if($data->facility_type == "Operation Only, Stock Point Only") selected @endif>Operation Only, Stock Point Only</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Number of Employees">Number of Employees</label>
                                    <select id="nature_of_employee" name="nature_of_employee">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="< 25" @if($data->nature_of_employee == '< 25') selected @endif> < 25 </option>
                                        <option value="26-49" @if($data->nature_of_employee == '26-49') selected @endif>26-49</option>
                                        <option value=">50" @if($data->nature_of_employee == '>50') selected @endif>>50</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Access to Technical Support">Access to Technical Support</label>
                                    <select id="technical_support" name="technical_support">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Very Limited Access" @if($data->technical_support == "Very Limited Access") selected @endif>Very Limited Access to Technical Experts</option>
                                        <option value="Available When Requested" @if($data->technical_support == "Available When Requested") selected @endif>Available When Requested or Via Beacon Center</option>
                                        <option value="Regulatory Schedule Visit by Region Experts" @if($data->technical_support == "Regulatory Schedule Visit by Region Experts") selected @endif>Regulatory Schedule Visit by Region Experts</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Services Supported">Services Supported</label>
                                    <select name="survice_supported" id="survice_supported">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Integrated, Multi-Combo Jobs" @if($data->survice_supported == "Integrated, Multi-Combo Jobs") selected @endif>Integrated, Multi-Combo Jobs</option>
                                        <option value="Basic D&E Services" @if($data->survice_supported == "Basic D&E Services") selected @endif>Basic D&E Services</option>
                                        <option value="Motors or Standalone MWD" @if($data->survice_supported == "Motors or Standalone MWD") selected @endif>Motors or Standalone MWD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Reliability">Reliability</label>
                                    <select id="reliability" name="reliability">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Significantly Below Expectations" @if($data->reliability == "Significantly Below Expectations") selected @endif>Significantly Below Expectations</option>
                                        <option value="Marginally Below Expectations" @if($data->reliability == "Marginally Below Expectations") selected @endif>Marginally Below Expectations</option>
                                        <option value="Meets Expectations" @if($data->reliability == "Meets Expectations") selected @endif>Meets Expectations</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Revenue">Revenue</label>
                                    <select name="revenue" id="revenue">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value=">50 M" @if($data->revenue == ">50 M") selected @endif>>50 M</option>
                                        <option value="26-49 M" @if($data->revenue == "26-49 M") selected @endif>26-49 M</option>
                                        <option value="< 25 M"  @if($data->revenue == "< 25 M") selected @endif>< 25 M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Client Base">Client Base</label>
                                    <select id="client_base" name="client_base">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Single or Disproportionally Skewed" @if($data->client_base == "Single or Disproportionally Skewed") selected @endif>Single or Disproportionally Skewed</option>
                                        <option value="Multiple Clients" @if($data->client_base == "Multiple Clients") selected @endif>Multiple Clients</option>
                                        <option value="Well Diversified" @if($data->client_base == "Well Diversified") selected @endif>Well Diversified</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Previous Audit Results">Previous Audit Results</label>
                                    <select id="previous_audit_result" name="previous_audit_result">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Below Requirement Major NCN's or No Audit History" @if($data->previous_audit_result == "Below Requirement Major NCN's or No Audit History") selected @endif>Below Requirement Major NCN's or No Audit History</option>
                                        <option value="Marginally Below Requirement With Minor NCN's" @if($data->previous_audit_result == "Marginally Below Requirement With Minor NCN's") selected @endif>Marginally Below Requirement With Minor NCN's</option>
                                        <option value="Meets Requirement and Minimal NCN's" @if($data->previous_audit_result == "Meets Requirement and Minimal NCN's") selected @endif>Meets Requirement and Minimal NCN's</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="risk_assessment_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="risk_assessment_additional_attachment">
                                            @if ($data->risk_assessment_additional_attachment)
                                                @foreach (json_decode($data->risk_assessment_additional_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file" data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="risk_assessment_additional_attachment[]" oninput="addMultipleFiles(this, 'risk_assessment_additional_attachment')" multiple>
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
                                    <textarea  class="tiny" type="text" name="QA_head_comment" value="{{ $data->QA_head_comment }}" placeholder="Enter QA Head Comment" id="QA_head_comment">{{ $data->QA_head_comment }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_head_attachment">QA Head Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="QA_head_attachment">
                                            @if ($data->QA_head_attachment)
                                                @foreach (json_decode($data->QA_head_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file" data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="QA_head_attachment[]" oninput="addMultipleFiles(this, 'QA_head_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="qa_head_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="qa_head_additional_attachment">
                                            @if ($data->qa_head_additional_attachment)
                                                @foreach (json_decode($data->qa_head_additional_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file" data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="qa_head_additional_attachment[]" oninput="addMultipleFiles(this, 'qa_head_additional_attachment')" multiple>
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
            
                <div id="CCForm8" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Submitted By">Need for Sourcing of Starting Material By</label>
                                <div class="static">{{$data->submitted_by}}</div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Submitted On">Need for Sourcing of Starting Material On</label>
                                <div class="static">{{$data->submitted_on}}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Submitted Comment">Need for Sourcing of Starting Material Comment</label>
                                <div class="static">{{$data->submitted_comment}}</div>
                            </div>
                        </div>


                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Suppplier Review By">Request Justified   By</label>
                                <div class="static">{{$data->request_justified_by}}</div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Suppplier Review On">Request Justified On</label>
                                <div class="static">{{$data->request_justified_on}}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Suppplier Review Comment">Request Justified Comment</label>
                                <div class="static">{{$data->request_justified_comment}}</div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Score Card By">CQA Review Completed By</label>
                                <div class="static">{{$data->cqa_review_by}}</div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Review Completed On">CQA Review Completed On</label>
                                <div class="static">{{$data->cqa_review_on}}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Review Completed Comment">CQA Review Completed Comment</label>
                                <div class="static">{{$data->cqa_review_comment}}</div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Purchase Sample Request By">Purchase Sample Request Initiated By</label>
                                <div class="static">{{$data->purchase_sample_initiated_by}}</div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Purchase Sample Request Initiated On">Purchase Sample Request Initiated On</label>
                                <div class="static">{{$data->purchase_sample_initiated_on}}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Purchase Sample Request Initiated Comment">Purchase Sample Request Initiated Comment</label>
                                <div class="static">{{$data->purchase_sample_initiated_comment}}</div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Purchase Sample Analysis Satisfactory By">Purchase Sample Analysis Satisfactory By</label>
                                <div class="static">{{$data->purchase_sample_satisfactory_by}}</div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="Purchase Sample Analysis Satisfactory On">Purchase Sample Analysis Satisfactory On</label>
                                <div class="static">{{$data->purchase_sample_satisfactory_on}}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Purchase Sample Analysis Satisfactory Comment">Purchase Sample Analysis Satisfactory Comment</label>
                                <div class="static">{{$data->purchase_sample_satisfactory_comment}}</div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="F&D Review Completed By">F&D Review Completed By</label>
                                <div class="static">{{$data->FD_review_by}}</div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="F&D Review Completed On">F&D Review Completed On</label>
                                <div class="static">{{$data->FD_review_on}}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="F&D Review Completed Comment">F&D Review Completed Comment</label>
                                <div class="static">{{$data->FD_review_comment}}</div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed By">CQA Final Review Completed By</label>
                                <div class="static">{{$data->cqa_final_review_by}}</div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="group-input">
                                <label for="CQA Final Review Completed On">CQA Final Review Completed On</label>
                                <div class="static">{{$data->cqa_final_res22view_on}}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="CQA Final Review Completed Comment">CQA Final Review Completed Comment</label>
                                <div class="static">{{$data->cqa_final_review_comment}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </form>

            <!-- Forword Stage Modal -->
            <div class="modal fade" id="signature-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/supplier-send-stage', $data->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3 text-justify">
                                    Please select a meaning and a outcome for this task and enter your username
                                    and password for this task. You are performing an electronic signature,
                                    which is legally binding equivalent of a hand written signature.
                                </div>
                                <div class="group-input">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" required>
                                </div>
                                <div class="group-input">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required>
                                </div>
                                <div class="group-input">
                                    <label for="comment">Comment</label>
                                    <input type="comment" name="comments">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Send To Obsolete from Supplier Approved Stage Modal -->
            <div class="modal fade" id="supplierApprovedToObselete">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/supplier-approved-to-obselete', $data->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3 text-justify">
                                    Please select a meaning and a outcome for this task and enter your username
                                    and password for this task. You are performing an electronic signature,
                                    which is legally binding equivalent of a hand written signature.
                                </div>
                                <div class="group-input">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" required class="form-control">
                                </div>
                                <div class="group-input mt-4">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required class="form-control">
                                </div>
                                <div class="group-input mt-4">
                                    <label for="comment">Comment</label>
                                    <input type="comment" name="comments" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Send to Pending Supplier Audit Stage Modal -->
            <div class="modal fade" id="sendToPendingSupplierAudit">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/sendToPendingSupplierAudit', $data->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3 text-justify">
                                    Please select a meaning and a outcome for this task and enter your username
                                    and password for this task. You are performing an electronic signature,
                                    which is legally binding equivalent of a hand written signature.
                                </div>
                                <div class="group-input">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                                <div class="group-input mt-4">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="group-input mt-4">
                                    <label for="comment">Comment <span class="text-danger">*</span></label>
                                    <input type="comment" name="comments" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Send to Supplier Approve Stage Modal -->
            <div class="modal fade" id="send-to-supplier-approve">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/sendTo-supplier-approved', $data->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3 text-justify">
                                    Please select a meaning and a outcome for this task and enter your username
                                    and password for this task. You are performing an electronic signature,
                                    which is legally binding equivalent of a hand written signature.
                                </div>
                                <div class="group-input">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" required class="form-control">
                                </div>
                                <div class="group-input mt-4">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required class="form-control">
                                </div>
                                <div class="group-input mt-4">
                                    <label for="comment">Comment</label>
                                    <input type="comment" name="comments" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Close Cancelled Stage Modal -->
            <div class="modal fade" id="cancel-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/supplier-close-cancelled', $data->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3 text-justify">
                                    Please select a meaning and a outcome for this task and enter your username
                                    and password for this task. You are performing an electronic signature,
                                    which is legally binding equivalent of a hand written signature.
                                </div>
                                <div class="group-input">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" required>
                                </div>
                                <div class="group-input">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required>
                                </div>
                                <div class="group-input">
                                    <label for="comment">Comment</label>
                                    <input type="comment" name="comments">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Child Stage Modal -->
            <div class="modal fade" id="child-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
            
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Child</h4>
                        </div>
                        <form action="{{ route('supplier_child_1', $data->id) }}" method="POST" target="_blank">
                            @csrf
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="group-input">
                                    @if ($data->stage == 2)
                                        
                                        <label for="major">
                                            <input type="radio" name="revision" id="major"
                                                value="RA">
                                              Supplier Risk Assessment
                                        </label>
                                        <label for="major">
                                            <input type="radio" name="revision" id="major"
                                                value="SA">
                                               Supplier Audit
                                        </label>
                                    @endif
                                    @if ($data->stage == 3)
                                    <label for="major">
                                        <input type="radio" name="revision" id="major"
                                            value="SA">
                                           Supplier Audit
                                    </label>
                                    @endif
                                    @if ($data->stage == 5)
                                        <label for="major">
                                            <input type="radio" name="revision" id="major"
                                                value="capa-child">
                                                CAPA
                                        </label>
                                        {{-- <br> --}}
                                        <label for="major">
                                            <input type="radio" name="revision" id="major"
                                                value="deviation">
                                               Deviation
                                        </label>

                                        <label for="major">
                                            <input type="radio" name="revision" id="major"
                                                value="RCA">
                                                Root Cause Analysis
                                        </label>
                                        <label for="major">
                                            <input type="radio" name="revision" id="major"
                                                value="changecontrol">
                                                Change Control
                                        </label>
                                        {{-- <br> --}}
                                        <label for="major">
                                            <input type="radio" name="revision" id="major"
                                                value="Action-Item">
                                                Action Item
                                        </label>
                                        <label for="major">
                                            <input type="radio" name="revision" id="major"
                                                value="RA">
                                              Supplier Risk Assessment
                                        </label>
                                        <label for="major">
                                            <input type="radio" name="revision" id="major"
                                                value="SA">
                                               Supplier Audit
                                        </label>
                                         <label for="major">
                                            <input type="radio" name="revision" id="major"
                                                value="SCAR">
                                               SCAR
                                        </label>
                                    @endif
                                </div>
            
                            </div>
            
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" data-bs-dismiss="modal">Close</button>
                                <button type="submit">Continue</button>
                            </div>
                        </form>
            
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removeButtons = document.querySelectorAll('.remove-file');

            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const fileName = this.getAttribute('data-file-name');
                    const fileContainer = this.closest('.file-container');

                    // Hide the file container
                    if (fileContainer) {
                        fileContainer.style.display = 'none';
                    }
                });
            });
        });
    </script>
    
    <script>
        VirtualSelect.init({
            ele: '#supplier-product, #ppap-elements, #supplier-services, #other-products, #manufacture-sites'
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

        function handleDateInput(input, targetId) {
            const target = document.getElementById(targetId);
            const date = new Date(input.value);
            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            const formattedDate = date.toLocaleDateString('en-US', options).replace(/ /g, '-');
            target.value = formattedDate;
        }
    </script>
     <script>
        // JavaScript
        document.getElementById('initiation_group').addEventListener('change', function() {
            var selectedValue = this.value;
            document.getElementById('initiator_group_code').value = selectedValue;
        });
    </script>
@endsection
