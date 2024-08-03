@extends('frontend.layout.main')
@section('container')

    @php
        $users = DB::table('users')->select('id', 'name')->get();
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

        /* .saveButton:disabled
                {
                   background: black!important;
                   border:  black!important;
                 }
                   
                */

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
    <style>
        .mic-btn {
            background: none;
            border: none;
            outline: none;
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            box-shadow: none;
            color: black;
            display: none;
            /* Hide the button initially */
        }

        .relative-container textarea {
            width: 100%;
            padding-right: 40px;
        }

        .relative-container input:focus+.mic-btn {
            display: inline-block;
            /* Show the button when input is focused */
        }

        .mic-btn:focus,
        .mic-btn:hover,
        .mic-btn:active {
            box-shadow: none;
        }
    </style>

    <script>
        < link rel = "stylesheet"
        href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" >
    </script>

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

    <script>
        $(document).ready(function() {
            let certificateIndex =
                {{ isset($certificationData) && is_array($certificationData) ? count($certificationData) : 1 }};

            $('#certificationData').click(function(e) {
                e.preventDefault();

                function generateTableRow(index) {
                    return `
                <tr>
                    <td><input disabled type="text" name="serial[]" value="${index + 1}"></td>
                    <td><input type="text" name="certificationData[${index}][type]"></td>
                    <td><input type="text" name="certificationData[${index}][issuingAgency]"></td>
                    <td><input type="date" name="certificationData[${index}][issueDate]" id="issueDate_${index}" onchange="updateExpiryDateMin(${index})"></td>
                    <td><input type="date" name="certificationData[${index}][expiryDate]" id="expiryDate_${index}" onchange="validateExpiryDate(${index})"></td>
                    <td><input type="text" name="certificationData[${index}][supportingDoc]"></td>
                    <td><input type="text" name="certificationData[${index}][remarks]"></td>
                    <td><button type="button" class="removeRowBtn">Remove</button></td>
                </tr>`;
                }

                var tableBody = $('#certificationDataTable tbody');
                var newRow = generateTableRow(certificateIndex);
                tableBody.append(newRow);
                certificateIndex++;
            });

            $(document).on('click', '.removeRowBtn', function() {
                $(this).closest('tr').remove();
            });

            $(document).on('change', '[id^="issueDate_"]', function() {
                var index = $(this).attr('id').split('_')[1];
                updateExpiryDateMin(index);
            });

            $(document).on('change', '[id^="expiryDate_"]', function() {
                var index = $(this).attr('id').split('_')[1];
                validateExpiryDate(index);
            });
        });

        function updateExpiryDateMin(index) {
            var issueDateInput = document.getElementById('issueDate_' + index);
            var expiryDateInput = document.getElementById('expiryDate_' + index);

            if (issueDateInput && expiryDateInput) {
                var issueDate = new Date(issueDateInput.value);
                if (issueDate) {
                    expiryDateInput.min = issueDate.toISOString().split('T')[0];
                    if (new Date(expiryDateInput.value) < issueDate) {
                        expiryDateInput.value = expiryDateInput.min;
                    }
                }
            }
        }

        function validateExpiryDate(index) {
            var issueDateInput = document.getElementById('issueDate_' + index);
            var expiryDateInput = document.getElementById('expiryDate_' + index);

            if (issueDateInput && expiryDateInput) {
                var issueDate = new Date(issueDateInput.value);
                var expiryDate = new Date(expiryDateInput.value);

                if (expiryDate < issueDate) {
                    expiryDateInput.value = issueDate.toISOString().split('T')[0];
                    alert('Expiry date cannot be earlier than issue date.');
                }
            }
        }
    </script>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recognition = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';

            function startRecognition(targetElement) {
                recognition.start();
                recognition.onresult = function(event) {
                    const transcript = event.results[0][0].transcript;
                    targetElement.value += transcript;
                };
                recognition.onerror = function(event) {
                    console.error(event.error);
                };
            }

            document.addEventListener('click', function(event) {
                if (event.target.closest('.mic-btn')) {
                    const button = event.target.closest('.mic-btn');
                    const inputField = button.previousElementSibling;
                    if (inputField && inputField.classList.contains('mic-input')) {
                        startRecognition(inputField);
                    }
                }
            });

            document.querySelectorAll('.mic-input').forEach(input => {
                input.addEventListener('focus', function() {
                    const micBtn = this.nextElementSibling;
                    if (micBtn && micBtn.classList.contains('mic-btn')) {
                        micBtn.style.display = 'inline-block';
                    }
                });

                input.addEventListener('blur', function() {
                    const micBtn = this.nextElementSibling;
                    if (micBtn && micBtn.classList.contains('mic-btn')) {
                        setTimeout(() => {
                            micBtn.style.display = 'none';
                        }, 200); // Delay to prevent button from hiding immediately when clicked
                    }
                });
            });
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
            {{ Helpers::getDivisionName($data->division_id) }} / Supplier Site
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
                        <button class="button_theme1"> <a class="text-white"
                                href="{{ url('rcms/supplier-site-audit-trail', $data->id) }}"> Audit Trail </a> </button>

                        @if ($data->stage == 1 && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Submit Supplier Details
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancel
                            </button>
                        @elseif($data->stage == 2 && (in_array(4, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Qualification Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancel
                            </button>
                        @elseif($data->stage == 3 && (in_array(7, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#send-to-supplier-approve">
                                Audit Passed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Audit Failed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal"
                                data-bs-target="#sendToPendingSupplierAudit">
                                Conditionally Approved
                            </button>
                        @elseif($data->stage == 4 && (in_array(7, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal"
                                data-bs-target="#sendToPendingSupplierAudit">
                                Re-Audit
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Supplier Obsolete
                            </button>
                        @elseif($data->stage == 5 && (in_array(39, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal"
                                data-bs-target="#supplierApprovedToObselete">
                                Supplier Obsolete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal"
                                data-bs-target="#sendToPendingSupplierAudit">
                                Reject Due To Quality Issue
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button>
                        @endif
                        <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit
                            </a> </button>
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
                                <div class="active">Pending Qualification </div>
                            @else
                                <div class="">Pending Qualification</div>
                            @endif

                            @if ($data->stage >= 3)
                                <div class="active">Pending Supplier Audit</div>
                            @else
                                <div class="">Pending Supplier Audit</div>
                            @endif

                            @if ($data->stage >= 4)
                                <div class="active">Pending Rejction</div>
                            @else
                                <div class="">Pending Rejction</div>
                            @endif

                            @if ($data->stage >= 5)
                                <div class="active">Supplier Approved</div>
                            @else
                                <div class="">Supplier Approved</div>
                            @endif

                            @if ($data->stage >= 6)
                                <div class="active bg-danger">
                                    Obselete</div>
                            @else
                                <div class="">
                                    Obselete</div>
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

            <script>
                $(document).ready(function() {
                    <?php if ($data->stage == 6) { ?>
                    $("#target :input").prop("disabled", true);
                    <?php } ?>
                });
            </script>
            <!--  Contract Tab content -->
            <form id="target" action="{{ route('supplier-site-update', $data->id) }} }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div id="CCForm1" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Record Number</b></label>
                                    <input type="text"
                                        value="{{ Helpers::getDivisionName($data->division_id) }}/SS/{{ date('Y') }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Division</b></label>

                                    <input type="text" readonly id="division_id"
                                        value="{{ Helpers::getDivisionName($data->division_id) }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Initiator</b></label>
                                    <input disabled type="text" name="initiator_id" id="initiator_id"
                                        value="{{ Helpers::getInitiatorName($data->initiator_id) }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiation"><b>Initiation Date</b></label>
                                    <input disabled type="text" value="{{ date('d-M-Y') }}" name="intiation_date">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="search">Assigned To <span class="text-danger"></span>
                                    </label>
                                    <select id="select-state" name="assign_to">
                                        <option value="">Select a value</option>
                                        @if (!empty($users))
                                            @foreach ($users as $user)
                                                <option @if ($data->assign_to == $user->id) selected @endif
                                                    value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            @php
                                $initiationDate = date('Y-m-d');
                                $dueDate = date('Y-m-d', strtotime($initiationDate . '+30 days'));
                            @endphp

                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="due-date">Date Due</label>
                                    <div><small class="text-primary">Please mention expected date of completion</small>
                                    </div>
                                    <div class="calenderauditee">
                                        <div class="calenderauditee">
                                            <input readonly type="text"
                                                value="{{ Helpers::getdateFormat($data->due_date) }}" name="due_date" />
                                            <input type="date" disabled name="due_date"
                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'due_date')" />
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
                                    <label for="Short Description">Short Description<span
                                            class="text-danger">*</span></label><span id="rchars">255</span>
                                    characters remaining

                                    <div class="relative-container">
                                        <input id="docname" type="text" name="short_description" maxlength="255"
                                            value="{{ $data->short_description }}" class="mic-input" required>
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'short_description', 'id' => 'short_description'])
                                            @endcomponent
                                    </div>

                                </div>
                            </div>

                            <script>
                                var maxLength = 255;
                                $('#docname').keyup(function() {
                                    var textlen = maxLength - $(this).val().length;
                                    $('#rchars').text(textlen);
                                });
                            </script>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Supplier.">Supplier</label>
                                    <select name="supplier_person" id="supplier_person">
                                        <option value="">Select Supplier</option>
                                        @if (!empty($users))
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    @if ($data->supplier_person == $user->id) selected @endif>{{ $user->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for=" Attachments">Logo</label>
                                    <div>
                                        <small class="text-primary">Please Attach all relevant or supporting
                                            documents
                                        </small>
                                    </div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="logo_attachment">
                                            @if ($data->logo_attachment)
                                                @foreach (json_decode($data->logo_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="logo_attachment[]"
                                                oninput="addMultipleFiles(this, 'logo_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Contact Person">Contact Person</label>
                                    <select name="supplier_contact_person" id="supplier_contact_person">
                                        <option value="">Select Supplier</option>
                                        @if (!empty($users))
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    @if ($data->supplier_contact_person == $user->id) selected @endif>{{ $user->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Suppliers Products">Suppliers Products</label>
                                        <div class="relative-container">
                                            <input id="supplier_products" type="text" name="supplier_products"
                                                maxlength="255" value="{{ $data->supplier_products }}"
                                                class="mic-input">
                                            <button class="mic-btn" type="button">
                                                @component('frontend.forms.language-model', ['name' => 'supplier_products', 'id' => 'supplier_products'])
                                                @endcomponent
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Description">Description</label>
                                    <div class="relative-container">
                                        <textarea id="description" name="description" class="mic-input"> {{ $data->description }}</textarea>
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'description', 'id' => 'description'])
                                            @endcomponent
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Type..">Type</label>
                                    <select name="supplier_type">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="CRO" @if ($data->supplier_type == 'CRO') selected @endif>CRO
                                        </option>
                                        <option value="F&B" @if ($data->supplier_type == 'F&B') selected @endif>F&B
                                        </option>
                                        <option value="Finished Goods" @if ($data->supplier_type == 'Finished Goods') selected @endif>
                                            Finished Goods</option>
                                        <option value="Grower" @if ($data->supplier_type == 'Grower') selected @endif>Grower
                                        </option>
                                        <option value="Legal" @if ($data->supplier_type == 'Legal') selected @endif>Legal
                                        </option>
                                        <option value="Midecinal + Medical Devices"
                                            @if ($data->supplier_type == 'Midecinal + Medical Devices') selected @endif>Midecinal + Medical Devices
                                        </option>
                                        <option value="Vendor" @if ($data->supplier_type == 'Vendor') selected @endif>Vendor
                                        </option>
                                        <option value="Other" @if ($data->supplier_type == 'Other') selected @endif>Other
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#suplier_other').hide();

                                    $('[name="supplier_type"]').change(function() {
                                        if ($(this).val() === 'Other') {
                                            $('#suplier_other').show();
                                            $('#suplier_other ').show();
                                        } else {
                                            $('#suplier_other').hide();
                                            $('#suplier_other ').hide();
                                        }
                                    });
                                });
                            </script>
                            <div id="suplier_other" class="col-lg-6">
                                <div class="group-input">
                                    <label for="">Other <span class="text-danger">*</span></label>
                                    <input type="text">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Sub Type.">Sub Type</label>
                                    <select name="supplier_sub_type">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Other" @if ($data->supplier_sub_type == 'Other') selected @endif>Other
                                        </option>
                                        <option value="Vendor" @if ($data->supplier_sub_type == 'Vendor') selected @endif>Vendor
                                        </option>
                                        <option value="Finished Goods" @if ($data->supplier_sub_type == 'Finished Goods') selected @endif>
                                            Finished Goods</option>
                                        <option value="Legal" @if ($data->supplier_sub_type == 'Legal') selected @endif>Legal
                                        </option>
                                        <option value="Other Fruits" @if ($data->supplier_sub_type == 'Other Fruits') selected @endif>
                                            Other Fruits</option>
                                        <option value="Exotic Fruits" @if ($data->supplier_sub_type == 'Exotic Fruits') selected @endif>
                                            Exotic Fruits</option>
                                        <option value="Other Vegetables"
                                            @if ($data->supplier_sub_type == 'Other Vegetables') selected @endif>Other Vegetables</option>
                                        <option value="Beans & Peas" @if ($data->supplier_sub_type == 'Beans & Peas') selected @endif>
                                            Beans & Peas</option>
                                        <option value="Red & Orange Vegetables"
                                            @if ($data->supplier_sub_type == 'Red & Orange Vegetables') selected @endif>Red & Orange Vegetables
                                        </option>
                                        <option value="Starchy Vegetables"
                                            @if ($data->supplier_sub_type == 'Starchy Vegetables') selected @endif>Starchy Vegetables</option>
                                        <option value="Dark Green Vegetables"
                                            @if ($data->supplier_sub_type == 'Dark Green Vegetables') selected @endif>VendorDark Green Vegetables
                                        </option>
                                        <option value="CRO" @if ($data->supplier_sub_type == 'CRO') selected @endif>CRO
                                        </option>
                                        <option value="Raw Material" @if ($data->supplier_sub_type == 'Raw Material') selected @endif>
                                            Raw Material</option>
                                        <option value="Interfaction Diesease"
                                            @if ($data->supplier_sub_type == 'Interfaction Diesease') selected @endif>Interfaction Diesease
                                        </option>
                                        <option value="Pedriatrics" @if ($data->supplier_sub_type == 'Pedriatrics') selected @endif>
                                            Pedriatrics</option>
                                        <option value="Sleep Medicine" @if ($data->supplier_sub_type == 'Sleep Medicine') selected @endif>
                                            Sleep Medicine</option>
                                        <option value="Nephrology" @if ($data->supplier_sub_type == 'Nephrology') selected @endif>
                                            Nephrology</option>
                                        <option value="Geriatrics" @if ($data->supplier_sub_type == 'Geriatrics') selected @endif>
                                            Geriatrics</option>
                                        <option value="Critical Care" @if ($data->supplier_sub_type == 'Critical Care') selected @endif>
                                            Critical Care</option>
                                        <option value="Cardiology" @if ($data->supplier_sub_type == 'Cardiology') selected @endif>
                                            Cardiology</option>
                                        <option value="Vitamins" @if ($data->supplier_sub_type == 'Vitamins') selected @endif>
                                            Vitamins</option>
                                        <option value="Meat & Poultry" @if ($data->supplier_sub_type == 'Meat & Poultry') selected @endif>
                                            Meat & Poultry</option>
                                        <option value="Fruits & Vegetables"
                                            @if ($data->supplier_sub_type == 'Fruits & Vegetables') selected @endif>Fruits & Vegetables</option>
                                        <option value="Pastry" @if ($data->supplier_sub_type == 'Pastry') selected @endif>Pastry
                                        </option>
                                        <option value="Frozen Fruits" @if ($data->supplier_sub_type == 'Frozen Fruits') selected @endif>
                                            Frozen Fruits</option>
                                        <option value="Dairy" @if ($data->supplier_sub_type == 'Dairy') selected @endif>Dairy
                                        </option>
                                        <option value="Beverages" @if ($data->supplier_sub_type == 'Beverages') selected @endif>
                                            Beverages</option>
                                        <option value="Flavour" @if ($data->supplier_sub_type == 'Flavour') selected @endif>Flavour
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Other Type">Other Type</label>
                                    <div class="relative-container">
                                        <input id="supplier_other_type" type="text" name="supplier_other_type"
                                            maxlength="255" value="{{ $data->supplier_other_type }}"
                                            placeholder="Enter Other Type" class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'supplier_other_type', 'id' => 'supplier_other_type'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supply from">Supply From</label>
                                    <div class="relative-container">
                                        <input id="supply_from" type="text" name="supply_from" maxlength="255"
                                            value="{{ $data->supply_from }}" placeholder="Enter Supply From"
                                            class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'supply_from', 'id' => 'supply_from'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supply to">Supply To</label>
                                    <div class="relative-container">
                                        <input id="supply_to" type="text" name="supply_to" maxlength="255"
                                            value="{{ $data->supply_to }}" placeholder="Enter Supply To"
                                            class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'supply_to', 'id' => 'supply_to'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Web Site">Supplier Website</label>
                                    <div class="relative-container">
                                        <input id="supplier_website" type="text" name="supplier_website"
                                            maxlength="255" value="{{ $data->supplier_website }}"
                                            placeholder="Enter Supplier Website" class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'supplier_website', 'id' => 'supplier_website'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Web Search">Web Search</label>
                                    <div class="relative-container">
                                        <input id="supplier_web_search" type="search" name="supplier_web_search"
                                            maxlength="255" value="{{ $data->supplier_web_search }}"
                                            placeholder="Enter Supply Web Search" class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'supplier_web_search', 'id' => 'supplier_web_search'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Audit Attachments">File Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="supplier_attachment">
                                            @if ($data->supplier_attachment)
                                                @foreach (json_decode($data->supplier_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="supplier_attachment[]"
                                                oninput="addMultipleFiles(this, 'supplier_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="gi_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="gi_additional_attachment">
                                            @if ($data->gi_additional_attachment)
                                                @foreach (json_decode($data->gi_additional_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="gi_additional_attachment[]"
                                                oninput="addMultipleFiles(this, 'gi_additional_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Related URLs">Related URLs</label>
                                    <input type="url" name="related_url" value="{{ $data->related_url }}"
                                        placeholder="Enter Related URLs">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Related Quality Events">Related Quality Events</label>
                                    <div class="relative-container">
                                        <input id="related_quality_events" type="text" name="related_quality_events"
                                            maxlength="255" value="{{ $data->related_quality_events }}"
                                            placeholder="Enter Related Quality Events" class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', [
                                                'name' => 'related_quality_events',
                                                'id' => 'related_quality_events',
                                            ])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Of Complaints/Deviations"># Of Complaints/Deviations</label>
                                    <input type="text" name="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="total demerit points">Total Demerit Points</label>
                                    <input type="text" name="" id="totalDemeritPoints">
                                </div>
                            </div> --}}


                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            {{-- <button type="button" class="backButton" onclick="previousStep()">Back</button> --}}
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit
                                </a>
                            </button>
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
                                    <div class="relative-container">
                                        <textarea id="HOD_feedback" name="HOD_feedback" placeholder="Enter HOD Feedback" class="mic-input">{{ $data->HOD_feedback }}</textarea>
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'HOD_feedback', 'id' => 'HOD_feedback'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="HOD_comment">HOD Comment</label>
                                    <div class="relative-container">
                                        <textarea id="HOD_comment" name="HOD_comment" placeholder="Enter HOD Comment" class="mic-input">{{ $data->HOD_comment }}</textarea>
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'HOD_comment', 'id' => 'HOD_comment'])
                                            @endcomponent
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="HOD_attachment">HOD Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="HOD_attachment">
                                            @if ($data->HOD_attachment)
                                                @foreach (json_decode($data->HOD_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
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
                                    <label for="hod_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="hod_additional_attachment">
                                            @if ($data->hod_additional_attachment)
                                                @foreach (json_decode($data->hod_additional_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
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
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit
                                </a>
                            </button>
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
                                        Certifications & Accreditation<button type="button" name="ann"
                                            id="certificationData">+</button>
                                    </label>
                                    <table class="table table-bordered" id="certificationDataTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 6%;">Row #</th>
                                                <th>Type</th>
                                                <th>Issuing Agancy</th>
                                                <th>Issue Date</th>
                                                <th>Expiry Date</th>
                                                <th>Supporting Document</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="certificationDataTable">
                                            @if ($certificationData && is_array($certificationData))
                                                @foreach ($certificationData as $gridData)
                                                    <tr>
                                                        <td><input disabled type="text"
                                                                name="certificationData[{{ $loop->index }}][serial]"
                                                                value="{{ $loop->index + 1 }}"></td>
                                                        <td><input type="text"
                                                                name="certificationData[{{ $loop->index }}][type]"
                                                                value="{{ isset($gridData['type']) ? $gridData['type'] : '' }}">
                                                        </td>
                                                        <td><input type="text"
                                                                name="certificationData[{{ $loop->index }}][issuingAgency]"
                                                                value="{{ isset($gridData['issuingAgency']) ? $gridData['issuingAgency'] : '' }}">
                                                        </td>
                                                        <td><input type="date"
                                                                name="certificationData[{{ $loop->index }}][issueDate]"
                                                                value="{{ isset($gridData['issueDate']) ? $gridData['issueDate'] : '' }}"
                                                                id="issueDate_{{ $loop->index }}"
                                                                onchange="updateExpiryDateMin({{ $loop->index }})"></td>
                                                        <td><input type="date"
                                                                name="certificationData[{{ $loop->index }}][expiryDate]"
                                                                value="{{ isset($gridData['expiryDate']) ? $gridData['expiryDate'] : '' }}"
                                                                id="expiryDate_{{ $loop->index }}"></td>
                                                        <td><input type="text"
                                                                name="certificationData[{{ $loop->index }}][supportingDoc]"
                                                                value="{{ isset($gridData['supportingDoc']) ? $gridData['supportingDoc'] : '' }}">
                                                        </td>
                                                        <td><input type="text"
                                                                name="certificationData[{{ $loop->index }}][remarks]"
                                                                value="{{ isset($gridData['remarks']) ? $gridData['remarks'] : '' }}">
                                                        </td>
                                                        <td><button type="button" class="removeRowBtn">Remove</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td><input disabled type="text" name="certificationData[0][serial]"
                                                            value="1"></td>
                                                    <td><input type="text" name="certificationData[0][type]"></td>
                                                    <td><input type="text" name="certificationData[0][issuingAgency]">
                                                    </td>
                                                    <td><input type="date" name="certificationData[0][issueDate]"
                                                            id="issueDate_0" onchange="updateExpiryDateMin(0)"></td>
                                                    <td><input type="date" name="certificationData[0][expiryDate]"
                                                            id="expiryDate_0"></td>
                                                    <td><input type="text" name="certificationData[0][supportingDoc]">
                                                    </td>
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
                                    <div class="relative-container">
                                        <input id="supplier_name" type="text" name="supplier_name" maxlength="255"
                                            value="{{ $data->supplier_name }}" placeholder="Enter Supplier Name"
                                            class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'supplier_name', 'id' => 'supplier_name'])
                                            @endcomponent
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier.">Supplier ID</label>
                                    <div class="relative-container">
                                        <input id="supplier_id" type="text" name="supplier_id" maxlength="255"
                                            value="{{ $data->supplier_id }}" placeholder="Enter Supplier ID"
                                            class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'supplier_id', 'id' => 'supplier_id'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="">Manufacturer</label>
                                    <div class="relative-container">
                                        <input id="manufacturer_name" type="text" name="manufacturer_name"
                                            maxlength="255" value="{{ $data->manufacturer_name }}"
                                            placeholder="Enter Manufacturer Name" class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'manufacturer_name', 'id' => 'manufacturer_name'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="manufacturer">Manufacturer ID</label>
                                    <div class="relative-container">
                                        <input id="manufacturer_id" type="text" name="manufacturer_id"
                                            maxlength="255" value="{{ $data->manufacturer_id }}"
                                            placeholder="Enter Manufacturer ID" class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'manufacturer_id', 'id' => 'manufacturer_id'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="">Vendor</label>
                                    <div class="relative-container">
                                        <input id="vendor_name" type="text" name="vendor_name" maxlength="255"
                                            value="{{ $data->vendor_name }}" placeholder="Enter Vendor Name"
                                            class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'vendor_name', 'id' => 'vendor_name'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="manufacturer">Vendor ID</label>
                                    <div class="relative-container">
                                        <input id="vendor_id" type="text" name="vendor_id" maxlength="255"
                                            value="{{ $data->vendor_id }}" placeholder="Enter Vendor ID"
                                            class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'vendor_id', 'id' => 'vendor_id'])
                                            @endcomponent
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Contact Person">Contact Person</label>
                                    <div class="relative-container">
                                        <input id="contact_person" type="text" name="contact_person" maxlength="255"
                                            value="{{ $data->contact_person }}" placeholder="Enter Contact Person"
                                            class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'contact_person', 'id' => 'contact_person'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Other Contacts">Other Contacts</label>
                                    <div class="relative-container">
                                        <textarea id="other_contacts" name="other_contacts" placeholder="Enter Other Contacts" class="mic-input">{{ $data->other_contacts }}</textarea>
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'other_contacts', 'id' => 'other_contacts'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Supplier Services">Supplier Services</label>
                                    <div class="relative-container">
                                        <textarea id="supplier_serivce" name="supplier_serivce" cols="30" placeholder="Enter Supplier Service"
                                            class="mic-input">{{ $data->supplier_serivce }}</textarea>
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'supplier_serivce', 'id' => 'supplier_serivce'])
                                            @endcomponent
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Zone">Zone</label>
                                    <select name="zone">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Asia" @if ($data->zone == 'Asia') selected @endif>
                                            Asia</option>
                                        <option value="Europe" @if ($data->zone == 'Europe') selected @endif>
                                            Europe</option>
                                        <option value="Africa" @if ($data->zone == 'Africa') selected @endif>
                                            Africa</option>
                                        <option value="Central America"
                                            @if ($data->zone == 'Central America') selected @endif>
                                            Central America</option>
                                        <option value="South America" @if ($data->zone == 'South America') selected @endif>
                                            South America</option>
                                        <option value="Oceania" @if ($data->zone == 'Oceania') selected @endif>
                                            Oceania</option>
                                        <option value="North America" @if ($data->zone == 'North America') selected @endif>
                                            North America</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Country">Country</label>
                                    <select name="country" class="form-select country"
                                        aria-label="Default select example" onchange="loadStates()">
                                        <option value="">Select Country</option>
                                        <option value="{{ $data->country }}" selected>{{ $data->country }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="City">State</label>
                                    <select name="state" class="form-select state" aria-label="Default select example"
                                        onchange="loadCities()">
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
                                    <div class="relative-container">
                                        <textarea id="address" name="address" placeholder="Enter Address" class="mic-input">{{ $data->address }}</textarea>
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'address', 'id' => 'address'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Web Site">Supplier Website</label>
                                    <div class="relative-container">
                                        <input id="supplier_website" type="text" name="supplier_website"
                                            maxlength="255" value="{{ $data->supplier_website }}"
                                            placeholder="Enter Website" class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'supplier_website', 'id' => 'supplier_website'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="ISO Certification date">ISO Certification Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="iso_certified_date" readonly placeholder="DD-MMM-YYYY"
                                            value="{{ Helpers::getdateFormat($data->iso_certified_date) }}" />
                                        <input type="date" name="iso_certified_date"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            value="{{ $data->iso_certified_date }}" class="hide-input"
                                            oninput="handleDateInput(this, 'iso_certified_date')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="iso_certificate_attachment">ISO Ceritificate Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="iso_certificate_attachment">
                                            @if ($data->iso_certificate_attachment)
                                                @foreach (json_decode($data->iso_certificate_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
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
                                    <div class="relative-container">
                                        <input id="suppplier_contacts" type="text" name="suppplier_contacts"
                                            maxlength="255" value="{{ $data->suppplier_contacts }}" class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'suppplier_contacts', 'id' => 'suppplier_contacts'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Related Non Conformances">Related Non Conformances</label>
                                    <div class="relative-container">
                                        <input id="related_non_conformance" type="text" name="related_non_conformance"
                                            maxlength="255" value="{{ $data->related_non_conformance }}"
                                            class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', [
                                                'name' => 'related_non_conformance',
                                                'id' => 'related_non_conformance',
                                            ])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Contracts/Agreements">Supplier Contracts/Agreements</label>
                                    <div class="relative-container">
                                        <input id="suppplier_agreement" type="text" name="suppplier_agreement"
                                            maxlength="255" value="{{ $data->suppplier_agreement }}" class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'suppplier_agreement', 'id' => 'suppplier_agreement'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Regulatory History">Regulatory History</label>
                                    <div class="relative-container">
                                        <input id="regulatory_history" type="text" name="regulatory_history"
                                            maxlength="255" value="{{ $data->regulatory_history }}" class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'regulatory_history', 'id' => 'regulatory_history'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Distribution Sites">Distribution Sites</label>
                                    <div class="relative-container">
                                        <input id="distribution_sites" type="text" name="distribution_sites"
                                            maxlength="255" value="{{ $data->distribution_sites }}" class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'distribution_sites', 'id' => 'distribution_sites'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Quality Management ">Manufacturing Sites </label>
                                    <div class="relative-container">
                                        <input id="manufacturing_sited" type="text" name="manufacturing_sited"
                                            maxlength="255" value="{{ $data->manufacturing_sited }}" class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'manufacturing_sited', 'id' => 'manufacturing_sited'])
                                            @endcomponent
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Quality Management ">Quality Management </label>
                                    <div class="relative-container">
                                        <input id="quality_management" type="text" name="quality_management"
                                            maxlength="255" value="{{ $data->quality_management }}" class="mic-input">
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'quality_management', 'id' => 'quality_management'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Business History">Business History</label>
                                    <div class="relative-container">
                                        <textarea id="bussiness_history" name="bussiness_history" placeholder="Enter Business History" class="mic-input">{{ $data->bussiness_history }}</textarea>
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'bussiness_history', 'id' => 'bussiness_history'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Performance History ">Performance History </label>
                                    <div class="relative-container">
                                        <textarea id="performance_history" name="performance_history" placeholder="Enter Performance History"
                                            class="mic-input">{{ $data->performance_history }}</textarea>
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'performance_history', 'id' => 'performance_history'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Compliance Risk">Compliance Risk</label>
                                    <div class="relative-container">
                                        <textarea id="compliance_risk" name="compliance_risk" placeholder="Enter Compliance Risk" class="mic-input">{{ $data->compliance_risk }}</textarea>
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'compliance_risk', 'id' => 'compliance_risk'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="supplier_detail_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list"
                                            id="supplier_detail_additional_attachment">
                                            @if ($data->supplier_detail_additional_attachment)
                                                @foreach (json_decode($data->supplier_detail_additional_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile"
                                                name="supplier_detail_additional_attachment[]"
                                                oninput="addMultipleFiles(this, 'supplier_detail_additional_attachment')"
                                                multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit
                                </a>
                            </button>
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
                                        <option value="Unacceptable" @if ($data->cost_reduction == 'Unacceptable') selected @endif>
                                            Unacceptable</option>
                                        <option value="Does Not Meet Expectation"
                                            @if ($data->cost_reduction == 'Does Not Meet Expectation') selected @endif>Does Not Meet Expectation
                                        </option>
                                        <option value="Meets Expectations"
                                            @if ($data->cost_reduction == 'Meets Expectations') selected @endif>Meets Expectations</option>
                                        <option value="Exceeds Expectations"
                                            @if ($data->cost_reduction == 'Exceeds Expectations') selected @endif>Exceeds Expectations
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Cost Reduction Weight">Cost Reduction Weight</label>
                                    <select id="cost_reduction_weight" name="cost_reduction_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->cost_reduction_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Payment Terms">Payment Terms</label>
                                    <select id="payment_term" name="payment_term">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="< 30 Days" @if ($data->payment_term == '< 30 Days') selected @endif>
                                            < 30 Days</option>
                                        <option value="30 - 45 Days" @if ($data->payment_term == '30 - 45 Days') selected @endif>
                                            30
                                            - 45 Days</option>
                                        <option value="45 - 60 Days" @if ($data->payment_term == '45 - 60 Days') selected @endif>
                                            45
                                            - 60 Days</option>
                                        <option value=">= 60 Days" @if ($data->payment_term == '>= 60 Days')
                                            selected
                                            @endif>>= 60 Days</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Payment Terms Weight">Payment Terms Weight</label>
                                    <select name="payment_term_weight" id="payment_term_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->payment_term_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Lead Time Days">Lead Time Days</label>
                                    <select name="lead_time_days" name="lead_time_days">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="> 11 Days" @if ($data->lead_time_days == '> 11 Days')
                                            selected
                                            @endif>
                                            > 11 Days</option>
                                        <option value="6 - 10" @if ($data->lead_time_days == '6 - 10') selected @endif>6 - 10
                                        </option>
                                        <option value="3 -5" @if ($data->lead_time_days == '3 -5') selected @endif>3 -5
                                        </option>
                                        <option value="1 Day or Consignment"
                                            @if ($data->lead_time_days == '1 Day or Consignment') selected @endif>1 Day or Consignment
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Lead Time Days Weight">Lead Time Days Weight</label>
                                    <select name="lead_time_days_weight" id="lead_time_days_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->lead_time_days_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="On-Time Delivery">On-Time Delivery</label>
                                    <select id="ontime_delivery" name="ontime_delivery">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="100%" @if ($data->ontime_delivery == '100%') selected @endif>100%
                                        </option>
                                        <option value="98-99%" @if ($data->ontime_delivery == '98-99%') selected @endif>98-99%
                                        </option>
                                        <option value="96-97%" @if ($data->ontime_delivery == '96-97%') selected @endif>96-97%
                                        </option>
                                        <option value="< 95%" @if ($data->ontime_delivery == '< 95%') selected @endif>
                                            < 95%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="On-Time Delivery Weight">On-Time Delivery Weight</label>
                                    <select id="ontime_delivery_weight" name="ontime_delivery_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->ontime_delivery_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Business Planning">Supplier Business Planning</label>
                                    <select id="supplier_bussiness_planning" name="supplier_bussiness_planning">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Not Information at All"
                                            @if ($data->supplier_bussiness_planning == 'Not Information at All') selected @endif>Not Information at All
                                        </option>
                                        <option value="No Formal Information About"
                                            @if ($data->supplier_bussiness_planning == 'No Formal Information About') selected @endif>No Formal Information About
                                        </option>
                                        <option value="Yes - Partially Aligned With"
                                            @if ($data->supplier_bussiness_planning == 'Yes - Partially Aligned With') selected @endif></option>
                                        <option value="Yes - Completely Aligns"
                                            @if ($data->supplier_bussiness_planning == 'Yes - Completely Aligns') selected @endif></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Business Weight">Supplier Business Weight</label>
                                    <select id="supplier_bussiness_planning_weight"
                                        name="supplier_bussiness_planning_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->supplier_bussiness_planning_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Rejection in PPM">Rejection in PPM</label>
                                    <select id="rejection_ppm" name="rejection_ppm">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value=">500001 Defects PPM" @if ($data->rejection_ppm == '>500001 Defect PPM')
                                            selected
                                            @endif>> 500001 Defects PPM</option>
                                        <option value="5001 - 50000 Defects PPM"
                                            @if ($data->rejection_ppm == '5001 - 50000 Defects PPM') selected @endif>5001 - 50000 Defects PPM
                                        </option>
                                        <option value="501 - 500 Defects PPM"
                                            @if ($data->rejection_ppm == '501 - 500 Defects PPM') selected @endif>501 - 5000 Defects PPM
                                        </option>
                                        <option value="Upto 500 Defects PPM"
                                            @if ($data->rejection_ppm == 'Upto 500 Defects PPM') selected @endif>Upto 500 Defects PPM
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Rejection in PPM Weight">Rejection in PPM Weight</label>
                                    <select id="rejection_ppm_weight" name="rejection_ppm_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->rejection_ppm_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Quality Systems">Quality Systems</label>
                                    <select id="quality_system" name="quality_system">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="No System/No Team"
                                            @if ($data->quality_system == 'No System/No Team') selected @endif>No System/No Team</option>
                                        <option value="System Not Certified"
                                            @if ($data->quality_system == 'System Not Certified') selected @endif>System Not Certified
                                        </option>
                                        <option value="ISO 9000 Cert"
                                            @if ($data->quality_system == 'ISO 9000 Cert') selected @endif>
                                            ISO 9000 Cert</option>
                                        <option value="ISO 9000 & 1400 Cert"
                                            @if ($data->quality_system == 'ISO 9000 & 1400 Cert') selected @endif>ISO 9000 & 1400 Cert
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Quality Systems Weight">Quality Systems Weight</label>
                                    <select id="quality_system_ranking" name="quality_system_ranking">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->quality_system_ranking == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="# of CAR's generated"># of CAR's generated</label>
                                    <select id="car_generated" name="car_generated">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="0" @if ($data->car_generated == '0') selected @endif>0
                                        </option>
                                        <option value="> 8" @if ($data->car_generated == '> 8')
                                            selected
                                            @endif>> 8</option>
                                        <option value="2-7" @if ($data->car_generated == '2-7') selected @endif>2-7
                                        </option>
                                        <option value="0-1" @if ($data->car_generated == '0-1') selected @endif>0-1
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="# of CAR's generated Weight"># of CAR's generated Weight</label>
                                    <select id="car_generated_weight" name="car_generated_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->car_generated_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="CAR Closure Time">CAR Closure Time</label>
                                    <select id="closure_time" name="closure_time">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="> 60" @if ($data->closure_time == '> 60')
                                            selected
                                            @endif>> 60
                                        </option>
                                        <option value="30-60" @if ($data->closure_time == '30-60') selected @endif>30-60
                                        </option>
                                        <option value="15-30" @if ($data->closure_time == '15-30') selected @endif>15-30
                                        </option>
                                        <option value="0-15" @if ($data->closure_time == '0-15') selected @endif>0-15
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="CAR Closure Time Weight">CAR Closure Time Weight</label>
                                    <select id="closure_time_weight" name="closure_time_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->closure_time_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="End-User Satisfaction">End-User Satisfaction</label>
                                    <select id="end_user_satisfaction" name="end_user_satisfaction">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Information Lacks"
                                            @if ($data->end_user_satisfaction == 'Information Lacks') selected @endif>Information Lacks</option>
                                        <option value="Not Reactive Enough"
                                            @if ($data->end_user_satisfaction == 'Not Reactive Enough') selected @endif>Not Reactive Enough
                                        </option>
                                        <option value="Required" @if ($data->end_user_satisfaction == 'Required') selected @endif>
                                            Required</option>
                                        <option value="Active Participation"
                                            @if ($data->end_user_satisfaction == 'Active Participation') selected @endif>Active Participation
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="End-User Satisfaction Weight">End-User Satisfaction Weight</label>
                                    <select id="end_user_satisfaction_weight" name="end_user_satisfaction_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->end_user_satisfaction_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="score_card_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list"
                                            id="score_card_additional_attachment">
                                            @if ($data->score_card_additional_attachment)
                                                @foreach (json_decode($data->score_card_additional_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile"
                                                name="score_card_additional_attachment[]"
                                                oninput="addMultipleFiles(this, 'score_card_additional_attachment')"
                                                multiple>
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
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a>
                            </button>
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
                                    <div class="relative-container">
                                        <textarea id="QA_reviewer_feedback" name="QA_reviewer_feedback" placeholder="Enter QA Reviewer Feedback"
                                            class="mic-input">{{ $data->QA_reviewer_feedback }}</textarea>
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'QA_reviewer_feedback', 'id' => 'QA_reviewer_feedback'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_reviewer_comment">QA Reviewer Comment</label>
                                    <div class="relative-container">
                                        <textarea id="QA_reviewer_comment" name="QA_reviewer_comment" placeholder="Enter QA Reviewer Comment"
                                            class="mic-input">{{ $data->QA_reviewer_comment }}</textarea>
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'QA_reviewer_comment', 'id' => 'QA_reviewer_comment'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_reviewer_attachment">QA Reviewer Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="QA_reviewer_attachment">
                                            @if ($data->QA_reviewer_attachment)
                                                @foreach (json_decode($data->QA_reviewer_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
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
                                    <label for="qa_reviewer_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list"
                                            id="qa_reviewer_additional_attachment">
                                            @if ($data->qa_reviewer_additional_attachment)
                                                @foreach (json_decode($data->qa_reviewer_additional_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile"
                                                name="qa_reviewer_additional_attachment[]"
                                                oninput="addMultipleFiles(this, 'qa_reviewer_additional_attachment')"
                                                multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a>
                            </button>
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
                                        <input type="text" id="last_audit_date" readonly placeholder="DD-MMM-YYYY"
                                            value="{{ Helpers::getdateFormat($data->last_audit_date) }}" />
                                        <input type="date" name="last_audit_date"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            {{-- value="{{ $data->last_audit_date }}" --}} {{-- max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" --}}
                                            oninput="handleDateInput(this, 'last_audit_date'); updateNextAuditDate(this.value)" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Last Audit Date">Next Audit Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="next_audit_date" placeholder="DD-MMM-YYYY"
                                            value="{{ Helpers::getdateFormat($data->next_audit_date) }}" />
                                        <input type="date" name="next_audit_date"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            {{-- value="{{ $data->next_audit_date }}" --}} {{-- max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" --}}
                                            oninput="handleDateInput(this, 'next_audit_date')" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Audit Frequency">Audit Frequency</label>
                                    <select id="audit_frequency" name="audit_frequency">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Every 10 Years"
                                            @if ($data->audit_frequency == 'Every 10 Years') selected @endif>Every 10 Years</option>
                                        <option value="Every 9 Years"
                                            @if ($data->audit_frequency == 'Every 9 Years') selected @endif>Every 9 Years</option>
                                        <option value="Every 8 Years"
                                            @if ($data->audit_frequency == 'Every 8 Years') selected @endif>Every 8 Years</option>
                                        <option value="Every 7 Years"
                                            @if ($data->audit_frequency == 'Every 7 Years') selected @endif>Every 7 Years</option>
                                        <option value="Every 6 Years"
                                            @if ($data->audit_frequency == 'Every 6 Years') selected @endif>Every 6 Years</option>
                                        <option value="Every 5 Years"
                                            @if ($data->audit_frequency == 'Every 5 Years') selected @endif>Every 5 Years</option>
                                        <option value="Every 4 Years"
                                            @if ($data->audit_frequency == 'Every 4 Years') selected @endif>Every 4 Years</option>
                                        <option value="Every 3 Years"
                                            @if ($data->audit_frequency == 'Every 3 Years') selected @endif>Every 3 Years</option>
                                        <option value="Every 2 Years"
                                            @if ($data->audit_frequency == 'Every 2 Years') selected @endif>Every 2 Years</option>
                                        <option value="Annual" @if ($data->audit_frequency == 'Annual') selected @endif>
                                            Annual
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Last Audit Result">Last Audit Result</label>
                                    <select id="last_audit_result" name="last_audit_result">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="5" @if ($data->last_audit_result == '5') selected @endif>5
                                        </option>
                                        <option value="4" @if ($data->last_audit_result == '4') selected @endif>4
                                        </option>
                                        <option value="3" @if ($data->last_audit_result == '3') selected @endif>3
                                        </option>
                                        <option value="2" @if ($data->last_audit_result == '2') selected @endif>2
                                        </option>
                                        <option value="1" @if ($data->last_audit_result == '1') selected @endif>1
                                        </option>
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

                                        <option value="Operation, R&M - Level 3"
                                            @if ($data->facility_type == 'Operation, R&M - Level 3') selected @endif> Operation, R&M - Level 3
                                        </option>

                                        <option value="Operation, R&M - Level 2"
                                            @if ($data->facility_type == 'Operation, R&M - Level 2') selected @endif>Operation, R&M - Level 2
                                        </option>

                                        <option value="Operation Only, Stock Point Only"
                                            @if ($data->facility_type == 'Operation Only, Stock Point Only1') selected @endif>Operation Only, Stock
                                            Point Only</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Number of Employees">Number of Employees</label>
                                    <select id="nature_of_employee" name="nature_of_employee">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="<25" @if ($data->nature_of_employee == '<25') selected @endif>
                                            <25 </option>
                                        <option value="26-49" @if ($data->nature_of_employee == '26-49') selected @endif>
                                            26-49 </option>
                                        <option value=">50" @if ($data->nature_of_employee == '>50')
                                            selected
                                            @endif>
                                            >50 </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Access to Technical Support">Access to Technical Support</label>
                                    <select id="technical_support" name="technical_support">
                                        <option value="">Enter Your Selection Here</option>

                                        <option value="Very Limited Access"
                                            @if ($data->technical_support == 'Very Limited Access') selected @endif> Very Limited Access to
                                            Technical Experts
                                        </option>

                                        <option value="Available When Requested"
                                            @if ($data->technical_support == 'Available When Requested') selected @endif> Available When Requested
                                            or Via Beacon Center
                                        </option>

                                        <option value="Regulatory Schedule Visit by Region Experts"
                                            @if ($data->technical_support == 'Regulatory Schedule Visit by Region Experts') selected @endif>
                                            Regulatory Schedule Visit by Region Experts
                                        </option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Services Supported">Services Supported</label>
                                    <select name="survice_supported" id="survice_supported">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Integrated, Multi-Combo Jobs"
                                            @if ($data->survice_supported == 'Integrated, Multi-Combo Jobs') selected @endif>Integrated, Multi-Combo
                                            Jobs
                                        </option>
                                        <option value="Basic D&E Services"
                                            @if ($data->survice_supported == 'Basic D&E Services') selected @endif>Basic D&E Services
                                        </option>
                                        <option value="Motors or Standalone MWD"
                                            @if ($data->survice_supported == 'Motors or Standalone MWD') selected @endif>Motors or Standalone MWD
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Reliability">Reliability</label>
                                    <select id="reliability" name="reliability">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Significantly Below Expectations"
                                            @if ($data->reliability == 'Significantly Below Expectations') selected @endif>Significantly Below
                                            Expectations</option>
                                        <option value="Marginally Below Expectations"
                                            @if ($data->reliability == 'Marginally Below Expectations') selected @endif>Marginally Below
                                            Expectations</option>
                                        <option value="Meets Expectations"
                                            @if ($data->reliability == 'Meets Expectations') selected @endif>Meets Expectations
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Revenue">Revenue</label>
                                    <select name="revenue" id="revenue">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value=">50 M" @if ($data->revenue == '>50 M')
                                            selected
                                            @endif>>50 M</option>
                                        <option value="26-49 M" @if ($data->revenue == '26-49 M') selected @endif>
                                            26-49 M
                                        </option>
                                        <option value="< 25 M" @if ($data->revenue == '< 25 M') selected @endif>
                                            < 25 M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Client Base">Client Base</label>
                                    <select id="client_base" name="client_base">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Single or Disproportionally Skewed"
                                            @if ($data->client_base == 'Single or Disproportionally Skewed') selected @endif>Single or
                                            Disproportionally Skewed
                                        </option>
                                        <option value="Multiple Clients"
                                            @if ($data->client_base == 'Multiple Clients') selected @endif>Multiple Clients</option>
                                        <option value="Well Diversified"
                                            @if ($data->client_base == 'Well Diversified') selected @endif>Well Diversified</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Previous Audit Results">Previous Audit Results</label>
                                    <select id="previous_audit_result" name="previous_audit_result">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Below Requirement Major NCN's or No Audit History"
                                            @if ($data->previous_audit_result == "Below Requirement Major NCN's or No Audit History") selected @endif>Below Requirement Major
                                            NCN's or No Audit History</option>
                                        <option value="Marginally Below Requirement With Minor NCN's"
                                            @if ($data->previous_audit_result == "Marginally Below Requirement With Minor NCN's") selected @endif>Marginally Below
                                            Requirement With Minor NCN's</option>
                                        <option value="Meets Requirement and Minimal NCN's"
                                            @if ($data->previous_audit_result == "Meets Requirement and Minimal NCN's") selected @endif>Meets Requirement and
                                            Minimal NCN's</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="risk_assessment_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list"
                                            id="risk_assessment_additional_attachment">
                                            @if ($data->risk_assessment_additional_attachment)
                                                @foreach (json_decode($data->risk_assessment_additional_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile"
                                                name="risk_assessment_additional_attachment[]"
                                                oninput="addMultipleFiles(this, 'risk_assessment_additional_attachment')"
                                                multiple>
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
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a>
                            </button>
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
                                    <div class="relative-container">
                                        <textarea id="QA_head_comment" name="QA_head_comment" placeholder="Enter QA Head Comment" class="mic-input">{{ $data->QA_head_comment }}</textarea>
                                        <button class="mic-btn" type="button">
                                            @component('frontend.forms.language-model', ['name' => 'QA_head_comment', 'id' => 'QA_head_comment'])
                                            @endcomponent
                                        </button>
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_head_attachment">QA Head Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="QA_head_attachment">
                                            @if ($data->QA_head_attachment)
                                                @foreach (json_decode($data->QA_head_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
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
                                    <label for="qa_head_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div disabled class="file-attachment-list" id="qa_head_additional_attachment">
                                            @if ($data->qa_head_additional_attachment)
                                                @foreach (json_decode($data->qa_head_additional_attachment) as $file)
                                                    <h6 type="button" class="file-container text-dark"
                                                        style="background-color: rgb(243, 242, 240);">
                                                        <b>{{ $file }}</b>
                                                        <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                class="fa fa-eye text-primary"
                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                        <a type="button" class="remove-file"
                                                            data-file-name="{{ $file }}"><i
                                                                class="fa-solid fa-circle-xmark"
                                                                style="color:red; font-size:20px;"></i></a>
                                                    </h6>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile"
                                                name="qa_head_additional_attachment[]"
                                                oninput="addMultipleFiles(this, 'qa_head_additional_attachment')"
                                                multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Signature content -->
                <div id="CCForm8" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Submitted By">Submit Supplier Details By</label>
                                    <div class="static">{{ $data->submitted_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Submitted On">Submit Supplier Details On</label>
                                    <div class="static">{{ $data->submitted_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Submitted Comment">Submit Supplier Details Comment</label>
                                    <div class="static">{{ $data->submitted_comment }}</div>
                                </div>
                            </div>


                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Cancelled By</label>
                                    <div class="static">{{ $data->cancelled_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Cancelled On</label>
                                    <div class="static">{{ $data->cancelled_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Cancelled Comment</label>
                                    <div class="static">{{ $data->cancelled_comment }}</div>
                                </div>
                            </div>


                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By"> Qualification Complete By</label>
                                    <div class="static">{{ $data->pending_qualification_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Qualification Complete on</label>
                                    <div class="static">{{ $data->pending_qualification_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Qualification Complete Comment</label>
                                    <div class="static">{{ $data->pending_qualification_comment }}</div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Audit Failed By</label>
                                    <div class="static">{{ $data->pending_supplier_audit_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Audit Failed On</label>
                                    <div class="static">{{ $data->pending_supplier_audit_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Audit Failed Comment</label>
                                    <div class="static">{{ $data->pending_supplier_audit_comment }}</div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Supplier Obsolete By</label>
                                    <div class="static">{{ $data->pending_rejection_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Supplier Obsolete On</label>
                                    <div class="static">{{ $data->pending_rejection_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Supplier Obsolete Comment</label>
                                    <div class="static">{{ $data->pending_rejection_comment }}</div>
                                </div>
                            </div>


                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Audit Passed By</label>
                                    <div class="static">{{ $data->supplier_approved_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Audit Passed On</label>
                                    <div class="static">{{ $data->supplier_approved_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Audit Passed Comment</label>
                                    <div class="static">{{ $data->supplier_approved_comment }}</div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Conditionally Approved By</label>
                                    <div class="static">{{ $data->supplier_approved_by }}</div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Conditionally Approved On</label>
                                    <div class="static">{{ $data->supplier_approved_on }}</div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Conditionally Approved Comment</label>
                                    <div class="static">{{ $data->supplier_approved_comment }}</div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Supplier Approved to Obsolete By </label>
                                    <div class="static">{{ $data->supplier_approved_to_obselete_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Supplier Approved to Obsolete On</label>
                                    <div class="static">{{ $data->supplier_approved_to_obselete_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Supplier Approved to Obsolete Comment</label>
                                    <div class="static">{{ $data->supplier_approved_to_obselete_comment }}</div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Re-Audit By</label>
                                    <div class="static">{{ $data->reAudit_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Re-Audit On</label>
                                    <div class="static">{{ $data->reAudit_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Re-Audit Comment</label>
                                    <div class="static">{{ $data->reAudit_comment }}</div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Reject Due To Quality Issues By</label>
                                    <div class="static">{{ $data->rejectedDueToQuality_by }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Reject Due To Quality Issues On</label>
                                    <div class="static">{{ $data->rejectedDueToQuality_on }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Reject Due To Quality Issues Comment</label>
                                    <div class="static">{{ $data->rejectedDueToQuality_comment }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a>
                            </button>
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
                        <form action="{{ url('rcms/supplier-site-send-stage', $data->id) }}" method="POST">
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
                        <form action="{{ url('rcms/supplier-site-approved-to-obselete', $data->id) }}" method="POST">
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
                        <form action="{{ url('rcms/sendToPendingSupplierSiteAudit', $data->id) }}" method="POST">
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
                        <form action="{{ url('rcms/sendTo-supplier-site-approved', $data->id) }}" method="POST">
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
                        <form action="{{ url('rcms/supplier-site-close-cancelled', $data->id) }}" method="POST">
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
                        <form action="{{ route('suppliersite_child', $data->id) }}" method="POST">
                            @csrf
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="group-input">
                                    @if ($data->stage == 2)
                                        <label for="major">
                                            <input type="radio" name="revision" id="major" value="RA">
                                            Supplier Risk Assessment
                                        </label>
                                        <label for="major">
                                            <input type="radio" name="revision" id="major" value="SA">
                                            Supplier Audit
                                        </label>
                                    @endif
                                    @if ($data->stage == 3)
                                        <label for="major">
                                            <input type="radio" name="revision" id="major" value="SA">
                                            Supplier Audit
                                        </label>
                                    @endif
                                    @if ($data->stage == 5)
                                        <label for="major">
                                            <input type="radio" name="revision" id="major" value="capa-child">
                                            CAPA
                                        </label>
                                        {{-- <br> --}}
                                        <label for="major">
                                            <input type="radio" name="revision" id="major" value="deviation">
                                            Deviation
                                        </label>

                                        <label for="major">
                                            <input type="radio" name="revision" id="major" value="RCA">
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
                                            <input type="radio" name="revision" id="major" value="RA">
                                            Supplier Risk Assessment
                                        </label>
                                        <label for="major">
                                            <input type="radio" name="revision" id="major" value="SA">
                                            Supplier Audit
                                        </label>
                                        <label for="major">
                                            <input type="radio" name="revision" id="major" value="SCAR">
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
            const options = {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            };
            const formattedDate = date.toLocaleDateString('en-US', options).replace(/ /g, '-');
            target.value = formattedDate;
        }

        function updateNextAuditDate(lastAuditDate) {
            const date = new Date(lastAuditDate);

            // Calculate the next audit date (one day after the last audit date)
            date.setDate(date.getmonth() + 1);

            // Format the date in the desired format
            const formattedDate = date.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }).replace(/ /g, '-');

            // Get the next audit date input fields
            const nextAuditDateInput = document.querySelector('input[name="next_audit_date"]');
            const nextAuditDateDisplay = document.getElementById('next_audit_date');

            // Set the value of the input fields
            nextAuditDateInput.value = date.toISOString().split('T')[0];
            nextAuditDateDisplay.value = formattedDate;

            // Make the input field editable
            nextAuditDateInput.readOnly = false;
        }
    </script>
@endsection
