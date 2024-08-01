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
        $(document).ready(function() {
            let certificateIndex = 1;

            $('#certificationData').click(function(e) {
                e.preventDefault();

                function generateTableRow(index) {
                    return `
                    <tr>
                        <td><input disabled type="text" name="serial[]" value="${index + 1}"></td>
                        <td><input type="text" name="certificationData[${index}][type]"></td>
                        <td><input type="text" name="certificationData[${index}][issuingAgency]"></td>
                        <td><input type="date" name="certificationData[${index}][issueDate]" id="issueDate_${index}" onchange="updateExpiryDateMin(${index})"></td>
                        <td><input type="date" name="certificationData[${index}][expiryDate]" id="expiryDate_${index}"></td>
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
        });

        function updateExpiryDateMin(index) {
            var issueDateInput = document.getElementById('issueDate_' + index);
            var expiryDateInput = document.getElementById('expiryDate_' + index);

            if (issueDateInput && expiryDateInput) {
                var issueDate = new Date(issueDateInput.value);
                if (issueDate) {
                    expiryDateInput.min = issueDate.toISOString().split('T')[0];
                }
            }
        }
    </script>

    <script>
        < link rel = "stylesheet"
        href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" >
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



    <div class="form-field-head">

        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName(session()->get('division')) }} / Supplier Site
        </div>

    </div>

    <div id="change-control-fields">
        <div class="container-fluid">

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
            <form action="{{ route('supplier-site-store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="CCForm1" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Record Number</b></label>
                                    <input type="text"
                                        value="{{ Helpers::getDivisionName(session()->get('division')) }}/SS/{{ date('Y') }}/{{ str_pad($record_number, 4, '0', STR_PAD_LEFT) }}"
                                        readonly>
                                    <input type="hidden" name="record" id="record">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Division</b></label>
                                    <input disabled type="text" name="division_id"
                                        value="{{ Helpers::getDivisionName(session()->get('division')) }}">
                                    <input type="hidden" name="division_id" value="{{ session()->get('division') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Initiator</b></label>
                                    <input disabled type="text" name="initiator_id" id="initiator_id"
                                        value="{{ Auth::user()->name }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiation"><b>Initiation Date</b></label>
                                    <input disabled type="text" value="{{ date('d-M-Y') }}" name="intiation_date">
                                    <input type="hidden" value="{{ date('Y-m-d') }}" name="intiation_date">
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
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
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
                                            <input type="text" id="due_date" readonly placeholder="DD-MM-YYYY" />
                                            <input type="date" name="due_date"
                                                min="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" class="hide-input"
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
                                            class="text-danger">*</span></label>
                                    <span id="rchars">255</span> characters remaining

                                    <div class="relative-container">
                                        <input id="docname" type="text" name="short_description" maxlength="255"
                                            class="mic-input" required>
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
                                                <option value="{{ $user->id  }}">{{ $user->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for=" Attachments">Logo</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="logo_attachment">
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
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                {{-- </div> --}}
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Suppliers Products">Suppliers Products</label>
                                        <div class="relative-container">
                                            <input class="mic-input" id="supplier_products" type="text"
                                                name="supplier_products" maxlength="255">
                                            @component('frontend.forms.language-model', ['name' => 'supplier_products', 'id' => 'supplier_products'])
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Description">Description</label>
                                    <div class="relative-container">
                                        <textarea name="description" class="mic-input" id="description" placeholder=""></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'description', 'id' => 'description'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Type..">Type</label>
                                    <select name="supplier_type">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="CRO">CRO</option>
                                        <option value="F&B">F&B</option>
                                        <option value="Finished Goods">Finished Goods</option>
                                        <option value="Grower">Grower</option>
                                        <option value="Legal">Legal</option>
                                        <option value="Midecinal + Medical Devices">Midecinal + Medical Devices</option>
                                        <option value="Vendor">Vendor</option>
                                        <option value="Other">Other</option>
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
                                        <option value="Other">Other</option>
                                        <option value="Vendor">Vendor</option>
                                        <option value="Finished Goods">Finished Goods</option>
                                        <option value="Legal">Legal</option>
                                        <option value="Other Fruits">Other Fruits</option>
                                        <option value="Exotic Fruits">Exotic Fruits</option>
                                        <option value="Other Vegetables">Other Vegetables</option>
                                        <option value="Beans & Peas">Beans & Peas</option>
                                        <option value="Red & Orange Vegetables">Red & Orange Vegetables</option>
                                        <option value="Starchy Vegetables">Starchy Vegetables</option>
                                        <option value="Dark Green Vegetables">VendorDark Green Vegetables</option>
                                        <option value="CRO">CRO</option>
                                        <option value="Raw Material">Raw Material</option>
                                        <option value="Interfaction Diesease">Interfaction Diesease</option>
                                        <option value="Pedriatrics">Pedriatrics</option>
                                        <option value="Sleep Medicine">Sleep Medicine</option>
                                        <option value="Nephrology">Nephrology</option>
                                        <option value="Geriatrics">Geriatrics</option>
                                        <option value="Critical Care">Critical Care</option>
                                        <option value="Cardiology">Cardiology</option>
                                        <option value="Vitamins">Vitamins</option>
                                        <option value="Meat & Poultry">Meat & Poultry</option>
                                        <option value="Fruits & Vegetables">Fruits & Vegetables</option>
                                        <option value="Pastry">Pastry</option>
                                        <option value="Frozen Fruits">Frozen Fruits</option>
                                        <option value="Dairy">Dairy</option>
                                        <option value="Beverages">Beverages</option>
                                        <option value="Flavour">Flavour</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Other Type">Other Type</label>
                                    <div class="relative-container">
                                        <input type="text" name="supplier_other_type" id="supplier_other_type"
                                            placeholder="Enter Other Type" class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'supplier_other_type', 'id' => 'supplier_other_type'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supply from">Supply From</label>
                                    <div class="relative-container">
                                        <input type="text" name="supply_from" id="supply_from"
                                            placeholder="Enter Supply From" class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'supply_from', 'id' => 'supply_from'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supply to">Supply To</label>
                                    <div class="relative-container">
                                        <input type="text" name="supply_to" id="supply_to"
                                            placeholder="Enter Supply To" class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'supply_to', 'id' => 'supply_to'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Web Site">Supplier Website</label>
                                    <div class="relative-container">
                                        <input type="text" name="supplier_website" id="supplier_website"
                                            placeholder="Enter Supply Website" class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'supplier_website', 'id' => 'supplier_website'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Web Search">Web Search</label>
                                    <div class="relative-container">
                                        <input type="search" name="supplier_web_search" id="supplier_web_search"
                                            placeholder="Enter Supply Web Search" class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'supplier_web_search', 'id' => 'supplier_web_search'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Audit Attachments">File Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="supplier_attachment"></div>
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
                                        <div class="file-attachment-list" id="gi_additional_attachment"></div>
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
                                    <input type="url" name="related_url" placeholder="Enter Related URLs">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Related Quality Events">Related Quality Events</label>
                                    <div class="relative-container">
                                        <input type="text" name="related_quality_events" id="related_quality_events"
                                            placeholder="Enter Related Quality Events" class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', [
                                            'name' => 'related_quality_events',
                                            'id' => 'related_quality_events',
                                        ])
                                        @endcomponent
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
                        </div>

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
                                        <textarea name="HOD_feedback" id="HOD_feedback" class="mic-input" placeholder="Enter HOD Feedback"></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'HOD_feedback', 'id' => 'HOD_feedback'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="HOD_comment">HOD Comment</label>
                                    <div class="relative-container">
                                        <textarea name="HOD_comment" id="HOD_comment" class="mic-input" placeholder="Enter HOD Comment"></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'HOD_comment', 'id' => 'HOD_comment'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="HOD_attachment">HOD Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
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
                                    <label for="hod_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
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
                                                <td><input disabled type="text" name="serial[]" value="1"></td>
                                                <td><input type="text" name="certificationData[0][type]"></td>
                                                <td><input type="text" name="certificationData[0][issuingAgency]"></td>
                                                <td><input type="date" name="certificationData[0][issueDate]"
                                                        id="issueDate_0" onchange="updateExpiryDateMin(0)"></td>
                                                <td><input type="date" name="certificationData[0][expiryDate]"
                                                        id="expiryDate_0"></td>
                                                <td><input type="text" name="certificationData[0][supportingDoc]"></td>
                                                <td><input type="text" name="certificationData[0][remarks]"></td>
                                                <td><button type="button" class="removeRowBtn">Remove</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier.">Supplier</label>
                                    <div class="relative-container">
                                        <input type="text" name="supplier_name" id="supplier_name"
                                            placeholder="Enter Supplier Name" class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'supplier_name', 'id' => 'supplier_name'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier.">Supplier ID</label>
                                    <div class="relative-container">
                                        <input type="text" name="supplier_id" id="supplier_id"
                                            placeholder="Enter Supplier ID" class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'supplier_id', 'id' => 'supplier_id'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="">Manufacturer</label>
                                    <div class="relative-container">
                                        <input type="text" name="manufacturer_name" id="manufacturer_name"
                                            placeholder="Enter Manufacturer Name" class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'manufacturer_name', 'id' => 'manufacturer_name'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="manufacturer">Manufacturer ID</label>
                                    <div class="relative-container">
                                        <input type="text" name="manufacturer_id" id="manufacturer_id"
                                            placeholder="Enter Manufacturer ID" class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'manufacturer_id', 'id' => 'manufacturer_id'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="">Vendor</label>
                                    <div class="relative-container">
                                        <input type="text" name="vendor_name" id="vendor_name"
                                            placeholder="Enter Vendor Name" class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'vendor_name', 'id' => 'vendor_name'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="manufacturer">Vendor ID</label>
                                    <div class="relative-container">
                                        <input type="text" name="vendor_id" id="vendor_id"
                                            placeholder="Enter Vendor ID" class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'vendor_id', 'id' => 'vendor_id'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Contact Person">Contact Person</label>
                                    <div class="relative-container">
                                        <input type="text" name="contact_person" id="contact_person"
                                            placeholder="Enter Contact Person" class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'contact_person', 'id' => 'contact_person'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Other Contacts">Other Contacts</label>
                                    <div class="relative-container">
                                        <textarea name="other_contacts" id="other_contacts" class="mic-input" placeholder="Enter Other Contacts"></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'other_contacts', 'id' => 'other_contacts'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Supplier Services">Supplier Services</label>
                                    <div class="relative-container">
                                        <textarea name="supplier_serivce" id="supplier_serivce" class="mic-input" placeholder="Enter Supplier Service"
                                            cols="30"></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'supplier_serivce', 'id' => 'supplier_serivce'])
                                        @endcomponent
                                    </div>

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
                                    <select name="country" class="form-select country"
                                        aria-label="Default select example" onchange="loadStates()">
                                        <option value="">Select Country</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="City">State</label>
                                    <select name="state" class="form-select state" aria-label="Default select example"
                                        onchange="loadCities()">
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
                                    <div class="relative-container">
                                        <textarea name="address" id="address" class="mic-input"></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'address', 'id' => 'address'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Web Site">Supplier Website</label>
                                    <div class="relative-container">
                                        <input type="text" name="suppplier_web_site" id="suppplier_web_site"
                                            class="mic-input" placeholder="Enter Website" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'suppplier_web_site', 'id' => 'suppplier_web_site'])
                                        @endcomponent
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="ISO Certification date">ISO Certification Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="iso_certified_date" readonly
                                            placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="iso_certified_date"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                            class="hide-input" oninput="handleDateInput(this, 'iso_certified_date')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="iso_certificate_attachment">Certificate Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
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
                                    <div class="relative-container">
                                        <input type="text" name="suppplier_contacts" id="suppplier_contacts"
                                            class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'suppplier_contacts', 'id' => 'suppplier_contacts'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Related Non Conformances">Related Non Conformances</label>
                                    <div class="relative-container">
                                        <input type="text" name="related_non_conformance" id="related_non_conformance"
                                            class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', [
                                            'name' => 'related_non_conformance',
                                            'id' => 'related_non_conformance',
                                        ])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Contracts/Agreements">Supplier Contracts/Agreements</label>
                                    <div class="relative-container">
                                        <input type="text" name="suppplier_agreement" id="suppplier_agreement"
                                            class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'suppplier_agreement', 'id' => 'suppplier_agreement'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Regulatory History">Regulatory History</label>
                                    <div class="relative-container">
                                        <input type="text" name="regulatory_history" id="regulatory_history"
                                            class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'regulatory_history', 'id' => 'regulatory_history'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Distribution Sites">Distribution Sites</label>
                                    <div class="relative-container">
                                        <input type="text" name="distribution_sites" id="distribution_sites"
                                            class="mic-input" maxlength="255">
                                        @component('frontend.forms.language-model', ['name' => 'distribution_sites', 'id' => 'distribution_sites'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Quality Management ">Manufacturing Sites </label>
                                    <div class="relative-container">
                                        <textarea name="manufacturing_sited" id="manufacturing_sited" class="mic-input"></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'manufacturing_sited', 'id' => 'manufacturing_sited'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Quality Management ">Quality Management </label>
                                    <div class="relative-container">
                                        <textarea id="quality_management" name="quality_management" class="mic-input"></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'quality_management', 'id' => 'quality_management'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Business History">Business History</label>
                                    <div class="relative-container">
                                        <textarea id="bussiness_history" name="bussiness_history" class="mic-input"></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'bussiness_history', 'id' => 'bussiness_history'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Performance History ">Performance History </label>
                                    <div class="relative-container">
                                        <textarea id="performance_history" name="performance_history" class="mic-input"></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'performance_history', 'id' => 'performance_history'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Compliance Risk">Compliance Risk</label>
                                    <div class="relative-container">
                                        <textarea id="compliance_risk" name="compliance_risk" class="mic-input"></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'compliance_risk', 'id' => 'compliance_risk'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="supplier_detail_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="supplier_detail_additional_attachment">
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
                                        <option value="< 30 Days">
                                            < 30 Days</option>
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
                                        <option value="">Enter Your Selection Here</option>
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
                                        <option value="< 95%">
                                            < 95%</option>
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
                                        <option value="Yes - Partially Aligned With">Yes - Partially Aligned With</option>
                                        <option value="Yes - Completely Aligns">Yes - Completely Aligns</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Business Weight">Supplier Business Weight</label>
                                    <select id="supplier_bussiness_planning_weight"
                                        name="supplier_bussiness_planning_weight">
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
                                    <label for="score_card_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="score_card_additional_attachment"></div>
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
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit
                                </a>
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
                                        <textarea name="QA_reviewer_feedback" id="QA_reviewer_feedback" class="mic-input"
                                            placeholder="Enter QA Reviewer Feedback"></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'QA_reviewer_feedback', 'id' => 'QA_reviewer_feedback'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_reviewer_comment">QA Reviewer Comment</label>
                                    <div class="relative-container">
                                        <textarea name="QA_reviewer_comment" id="QA_reviewer_comment" class="mic-input"
                                            placeholder="Enter QA Reviewer Comment"></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'QA_reviewer_comment', 'id' => 'QA_reviewer_comment'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_reviewer_attachment">QA Reviewer Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
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
                                    <label for="qa_reviewer_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="qa_reviewer_additional_attachment"></div>
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
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit
                                </a>
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
                                    {{-- <div class="calenderauditee">
                                        <input type="text" id="last_audit_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="last_audit_date"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'last_audit_date')" />
                                    </div> --}}

                                    <div class="calenderauditee">
                                        <input type="text" id="last_audit_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="last_audit_date"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'last_audit_date'); updateNextAuditDate(this.value)" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Last Audit Date">Next Audit Date</label>
                                    {{-- <div class="calenderauditee">
                                        <input type="text" id="next_audit_date" readonly
                                            placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="next_audit_date"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'next_audit_date')" />
                                    </div> --}}

                                    <div class="calenderauditee">
                                        <input type="text" id="next_audit_date" readonly
                                            placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="next_audit_date"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'next_audit_date')" />
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
                                        <option value="Operation Only, Stock Point Only">Operation Only, Stock Point Only
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Number of Employees">Number of Employees</label>
                                    <select id="nature_of_employee" name="nature_of_employee">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="<25">
                                            < 25 </option>
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
                                        <option value="Very Limited Access to Technical Experts ">Very Limited Access to
                                            Technical Experts
                                        </option>
                                        <option value="Available When Requested or Via Beacon Center">Available When
                                            Requested or Via Beacon Center</option>
                                        <option value="Regulatory Schedule Visit by Region Experts">Regulatory Schedule
                                            Visit by Region Experts</option>
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
                                        <option value="Significantly Below Expectations">Significantly Below Expectations
                                        </option>
                                        <option value="Marginally Below Expectations">Marginally Below Expectations
                                        </option>
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
                                        <option value="<25 M">
                                            < 25 M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Client Base">Client Base</label>
                                    <select id="client_base" name="client_base">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Single or Disproportionally Skewed">Single or Disproportionally
                                            Skewed</option>
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
                                        <option value="Below Requirement Major NCN's or No Audit History">Below
                                            Requirement
                                            Major NCN's or No Audit History</option>
                                        <option value="Marginally Below Requirement With Minor NCN's">Marginally Below
                                            Requirement With Minor NCN's</option>
                                        <option value="Meets Requirement and Minimal NCN's">Meets Requirement and Minimal
                                            NCN's</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="risk_assessment_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="risk_assessment_additional_attachment">
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
                                        <textarea name="QA_head_comment" id="QA_head_comment" class="mic-input" placeholder="Enter QA Head Comment"></textarea>
                                        @component('frontend.forms.language-model', ['name' => 'QA_head_comment', 'id' => 'QA_head_comment'])
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_head_attachment">QA Head Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
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
                                    <label for="qa_head_additional_attachment">Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="qa_head_additional_attachment"></div>
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
                                    <label for="Submitted By">Submitted By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Submitted On">Submitted On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Submitted Comment">Submitted Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>


                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Suppplier Review By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Suppplier Review On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Suppplier Review Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Score Card By">Score Card By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Score Card On">Score Card On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Score Card Comment">Score Card Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Risk Assessment By">Risk Assessment By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Risk Assessment On">Risk Assessment On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Risk Assessment Comment">Risk Assessment Comment</label>
                                    <div class="static"></div>
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
        </div>
    </div>

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
            const date = new Date(input.value);
            const formattedDate = date.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }).replace(/ /g, '-');
            document.getElementById(targetId).value = formattedDate;
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
