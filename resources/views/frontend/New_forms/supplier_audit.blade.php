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
        .mini-modal {
            display: none;
            position: absolute;
            z-index: 1;
            padding: 10px;
            background-color: #fefefe;
            border: 1px solid #888;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 200px;
            /* Adjust width as needed */
        }

        .mini-modal-content {
            background-color: #fefefe;
            padding: 10px;
            border-radius: 4px;
        }

        .mini-modal-content h2 {
            font-size: 16px;
            margin-top: 0;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
    </style>
    <div class="mini-modal">
        <div class="mini-modal-content">
            <span class="close">&times;</span>
            <h2>Select Language</h2>
            <select id="language-select">
                <option value="en-us">English</option>
                <option value="hi-in">Hindi</option>
                <option value="te-in">Telugu</option>
                <option value="fr-fr">French</option>
                <option value="es-es">Spanish</option>
                <option value="zh-cn">Chinese (Mandarin)</option>
                <option value="ja-jp">Japanese</option>
                <option value="de-de">German</option>
                <option value="ru-ru">Russian</option>
                <option value="ko-kr">Korean</option>
                <option value="it-it">Italian</option>
                <option value="pt-br">Portuguese (Brazil)</option>
                <option value="ar-sa">Arabic</option>
                <option value="bn-in">Bengali</option>
                <option value="pa-in">Punjabi</option>
                <option value="mr-in">Marathi</option>
                <option value="gu-in">Gujarati</option>
                <option value="ur-pk">Urdu</option>
                <option value="ta-in">Tamil</option>
                <option value="kn-in">Kannada</option>
                <option value="ml-in">Malayalam</option>
                <option value="or-in">Odia</option>
                <option value="as-in">Assamese</option>
                <!-- Add more languages as needed -->
            </select>
            <button id="select-language-btn">Select</button>
        </div>
    </div>
    </div>
    </div>
    </div>



    <script>
        function otherController(value, checkValue, blockID) {
            let block = document.getElementById(blockID)
            let blockTextarea = block.getElementsByTagName('textarea')[0];
            let blockLabel = block.querySelector('label span.text-danger');
            if (value === checkValue) {
                blockLabel.classList.remove('d-none');
                blockTextarea.setAttribute('required', 'required');
            } else {
                blockLabel.classList.add('d-none');
                blockTextarea.removeAttribute('required');
            }
        }
    </script>

    {{-- <script>
    function addAuditAgenda(tableId) {
        var users = @json($users);
        var table = document.getElementById(tableId);
        var currentRowCount = table.rows.length;
        var newRow = table.insertRow(currentRowCount);
        newRow.setAttribute("id", "row" + currentRowCount);

        var cell1 = newRow.insertCell(0);
        cell1.innerHTML = currentRowCount;

        var cell2 = newRow.insertCell(1);
        cell2.innerHTML = "<input type='text' name='audit[]'>";

        var cell3 = newRow.insertCell(2);
        cell3.innerHTML = '<div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"><input type="text" id="scheduled_start_date' + currentRowCount + '" readonly placeholder="DD-MM-YYYY" /><input type="date" name="scheduled_start_date[]" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="scheduled_start_date' + currentRowCount + '_checkdate" class="hide-input" oninput="handleDateInput(this, `scheduled_start_date' + currentRowCount + '`);checkDate(`scheduled_start_date' + currentRowCount + '_checkdate`,`scheduled_end_date' + currentRowCount + '_checkdate`)" /></div></div></div>';

        var cell4 = newRow.insertCell(3);
        cell4.innerHTML = "<input type='time' name='scheduled_start_time[]'>";

        var cell5 = newRow.insertCell(4);
        cell5.innerHTML = '<div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"><input type="text" id="scheduled_end_date' + currentRowCount + '" readonly placeholder="DD-MM-YYYY" /><input type="date" name="scheduled_end_date[]" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="scheduled_end_date' + currentRowCount + '_checkdate" class="hide-input" oninput="handleDateInput(this, `scheduled_end_date' + currentRowCount + '`);checkDate(`scheduled_start_date' + currentRowCount + '_checkdate`,`scheduled_end_date' + currentRowCount + '_checkdate`)" /></div></div></div>';

        var cell6 = newRow.insertCell(5);
        cell6.innerHTML = "<input type='time' name='scheduled_end_time[]'>";

        var cell7 = newRow.insertCell(6);
        var auditorHtml = '<select name="auditor[]"><option value="">-Select-</option>';
        for (var i = 0; i < users.length; i++) {
            auditorHtml += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
        }
        auditorHtml += '</select>';
        cell7.innerHTML = auditorHtml;

        var cell8 = newRow.insertCell(7);
        var auditeeHtml = '<select name="auditee[]"><option value="">-Select-</option>';
        for (var i = 0; i < users.length; i++) {
            auditeeHtml += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
        }
        auditeeHtml += '</select>';
        cell8.innerHTML = auditeeHtml;

        var cell9 = newRow.insertCell(8);
        cell9.innerHTML = "<input type='text' name='remarks[]'>";

        var cell10 = newRow.insertCell(9);
        cell10.innerHTML = '<button type="button" class="removeRowBtn">Remove</button>';
    }

    $(document).on('click', '.removeRowBtn', function() {
        $(this).closest('tr').remove();
        updateRowNumbers();
    });

    function updateRowNumbers() {
        $('#internalaudit tbody tr').each(function(index, row) {
            $(row).find('td:first').text(index + 1);
        });
    }
</script> --}}
    <script>
        function addAuditAgenda(tableId) {
            var users = @json($users);
            var table = document.getElementById(tableId);
            var currentRowCount = table.rows.length;
            var newRow = table.insertRow(currentRowCount);
            newRow.setAttribute("id", "row" + currentRowCount);
            var cell1 = newRow.insertCell(0);
            cell1.innerHTML = currentRowCount;

            var cell2 = newRow.insertCell(1);
            cell2.innerHTML = "<input type='text' name='audit[]'>";

            var cell3 = newRow.insertCell(2);
            cell3.innerHTML =
                '<td><div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"> <input type="text" id="scheduled_start_date' +
                currentRowCount +
                '" readonly placeholder="DD-MM-YYYY" /><input type="date" name="scheduled_start_date[]" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="scheduled_start_date' +
                currentRowCount + '_checkdate"  class="hide-input" oninput="handleDateInput(this, `scheduled_start_date' +
            currentRowCount + '`);checkDate(`scheduled_start_date' + currentRowCount +
            '_checkdate`,`scheduled_end_date' + currentRowCount + '_checkdate`)" /></div></div></div></td>';

            var cell4 = newRow.insertCell(3);
            cell4.innerHTML = "<input type='time' name='scheduled_start_time[]' >";

            var cell5 = newRow.insertCell(4);
            cell5.innerHTML =
                '<td><div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"> <input type="text" id="scheduled_end_date' +
                currentRowCount +
                '" readonly placeholder="DD-MM-YYYY" /><input type="date" name="scheduled_end_date[]" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="scheduled_end_date' +
                currentRowCount + '_checkdate" class="hide-input" oninput="handleDateInput(this, `scheduled_end_date' +
            currentRowCount + '`);checkDate(`scheduled_start_date' + currentRowCount +
            '_checkdate`,`scheduled_end_date' + currentRowCount + '_checkdate`)" /></div></div></div></td>';

            var cell6 = newRow.insertCell(5);
            cell6.innerHTML = "<input type='time' name='scheduled_end_time[]' >";

            var cell7 = newRow.insertCell(6);
            var userHtml = '<select name="auditor[]"><option value="">-Select-</option>';
            for (var i = 0; i < users.length; i++) {
                userHtml += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
            }
            userHtml += '</select>';

            cell7.innerHTML = userHtml;

            var cell8 = newRow.insertCell(7);

            var userHtml = '<select name="auditee[]"><option value="">-Select-</option>';
            for (var i = 0; i < users.length; i++) {
                userHtml += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
            }
            userHtml += '</select>';

            cell8.innerHTML = userHtml;

            var cell9 = newRow.insertCell(8);
            cell9.innerHTML = "<input type='text'name='remark[]'>";
            var cell10 = newRow.insertCell(9);
            cell10.innerHTML =
                '<button type="button" class="removeRowBtn" style="background-color: black;color: white;" onclick="removeRow(this)">Remove</button>';

            // Update row numbering
            for (var i = 1; i < currentRowCount; i++) {
                var row = table.rows[i];
                row.cells[0].innerHTML = i;
            }
        }

        function removeRow(button) {
            var row = button.closest('tr');
            row.parentNode.removeChild(row);

            // Update row numbering
            var table = document.getElementById('audit-agenda-grid');
            for (var i = 1; i < table.rows.length; i++) {
                var row = table.rows[i];
                row.cells[0].innerHTML = i;
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#internalaudit-table').click(function(e) {
                function generateTableRow(serialNumber) {
                    var users = @json($users);
                    console.log(users);
                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial_number[]" value="' + serialNumber +
                        '"></td>' +
                        '<td><input type="text" name="audit[]"></td>' +
                        '<td><div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"> <input type="text" id="scheduled_start_date' +
                        serialNumber +
                        '" readonly placeholder="DD-MM-YYYY" /><input type="date" name="scheduled_start_date[]" id="scheduled_start_date' +
                        serialNumber +
                        '_checkdate" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"  class="hide-input" oninput="handleDateInput(this, `scheduled_start_date' +
                    serialNumber + '`);checkDate(`scheduled_start_date' + serialNumber +
                    '_checkdate`,`scheduled_end_date' + serialNumber +
                    '_checkdate`)" /></div></div></div></td>' +

                        '<td><input type="time" name="scheduled_start_time[]"></td>' +
                        '<td><div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"> <input type="text" id="scheduled_end_date' +
                        serialNumber +
                        '" readonly placeholder="DD-MM-YYYY" /><input type="date" name="scheduled_end_date[]" id="scheduled_end_date' +
                        serialNumber +
                        '_checkdate" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input" oninput="handleDateInput(this, `scheduled_end_date' +
                    serialNumber + '`);checkDate(`scheduled_start_date' + serialNumber +
                    '_checkdate`,`scheduled_end_date' + serialNumber +
                    '_checkdate`)" /></div></div></div></td>' +
                        '<td><input type="time" name="scheduled_end_time[]"></td>' +


                        '<td><select name="auditor[]">' +
                        '<option value="">Select a value</option>';

                    for (var i = 0; i < users.length; i++) {
                        html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    }

                    html += '</select></td>' +
                        '<td><select name="auditee[]">' +
                        '<option value="">Select a value</option>';

                    for (var i = 0; i < users.length; i++) {
                        html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    }
                    html += '</select></td>' +
                        '<td><input type="text" name="remarks[]"></td>' +
                        '</tr>';

                    return html;
                }

                var tableBody = $('#internalaudit tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>
    @php
        $division = DB::table('divisions')->get();
    @endphp
    <script>
        $(document).ready(function() {
            $('#ObservationAdd').click(function(e) {
                function generateTableRow(serialNumber) {
                    var users = @json($users);
                    var html =
                        '<tr>' +
                        '<td>' + serialNumber + '</td>' +
                        '<td><input type="text" name="observation_id[]"></td>' +
                        '<td><input type="text" name="observation_description[]"></td>' +
                        '<td><input type="text" name="area[]"></td>' +
                        '<td><input type="text" name="auditee_response[]"></td>' +
                        '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                        '</tr>';
                    return html;
                }
                var tableBody = $('#onservation-field-table tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
                updateRowNumbers();
            });
            // Remove row functionality
            $(document).on('click', '.removeRowBtn', function() {
                $(this).closest('tr').remove();
                updateRowNumbers();
            });

            function updateRowNumbers() {
                $('#onservation-field-table tbody tr').each(function(index, row) {
                    $(row).find('td:first').text(index + 1);
                });
            }
        });

        function otherController(value, checkValue, blockID) {
            let block = document.getElementById(blockID)
            let blockTextarea = block.getElementsByTagName('textarea')[0];
            let blockLabel = block.querySelector('label span.text-danger');
            if (value === checkValue) {
                blockLabel.classList.remove('d-none');
                blockTextarea.setAttribute('required', 'required');
            } else {
                blockLabel.classList.add('d-none');
                blockTextarea.removeAttribute('required');
            }
        }
    </script>

    <div class="form-field-head">

        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            @if (!empty($parent_division_id))
                {{ Helpers::getDivisionName($parent_division_id) }} / Supplier Audit
            @else
                {{ Helpers::getDivisionName(session()->get('division')) }} / Supplier Audit
            @endif
        </div>
    </div>



    {{-- ======================================
                    DATA FIELDS
    ======================================= --}}




    <div id="change-control-fields">
        <div class="container-fluid">

            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Audit Planning</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Audit Preparation</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Audit Execution</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Audit Response & Closure</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Activity Log</button>
            </div>

            <form id="auditform" class="mainform" action="{{ route('supplier_audit_store') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div id="step-form">

                    <!-- General information content -->
                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">

                                @if (!empty($parent_id))
                                    <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                                    <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                                @endif
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number"><b>Record Number</b></label>
                                        @if (!empty($parent_division_id))
                                            <input disabled type="text" name="record_number"
                                                value="{{ Helpers::getDivisionName($parent_division_id) }}/SA/{{ date('Y') }}/{{ $record_number }}">
                                            <input type="hidden" name="recordNumber"
                                                value="{{ Helpers::getDivisionName($parent_division_id) }}/SA/{{ date('Y') }}/{{ $record_number }}">
                                        @else
                                            <input disabled type="text" name="record_number"
                                                value="{{ Helpers::getDivisionName(session()->get('division')) }}/SA/{{ date('Y') }}/{{ $record_number }}">
                                            <input type="hidden" name="recordNumber"
                                                value="{{ Helpers::getDivisionName(session()->get('division')) }}/SA/{{ date('Y') }}/{{ $record_number }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code"><b>Site/Location Code</b></label>
                                        @if (!empty($parent_division_id))
                                            <input disabled type="text"
                                                value="{{ Helpers::getDivisionName($parent_division_id) }}">
                                            <input type="hidden" name="division_id" value="{{ $parent_division_id }}">
                                        @else
                                            <input disabled type="text"
                                                value="{{ Helpers::getDivisionName(session()->get('division')) }}">
                                            <input type="hidden" name="division_id"
                                                value="{{ session()->get('division') }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator"><b>Initiator</b></label>
                                        {{-- <div class="static">{{ Auth::user()->name }}</div> --}}
                                        <input disabled type="text" value="{{ Auth::user()->name }}">

                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input ">
                                        <label for="Due Date"><b>Date of Initiation</b></label>
                                        <input readonly type="text" value="{{ date('d-M-Y') }}" name="intiation_date">
                                        <input type="hidden" value="{{ date('d-m-Y') }}" name="intiation_date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Assigned To <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="assign_to">
                                            <option value="">Select a value</option>
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('assign_to')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                @php
                                    $initiationDate = date('Y-m-d');
                                    $dueDate = date('Y-m-d', strtotime($initiationDate . '+30 days'));
                                @endphp

                                <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="due-date"> Due Date</label>
                                        <div><small class="text-primary">Please mention expected date of completion</small>
                                        </div>
                                        <!-- <div class="calenderauditee"> -->
                                        <div class="calenderauditee">
                                            <input type="text" id="due_date" name="due_date" readonly
                                                placeholder="DD-MM-YYYY" />
                                            <input type="date" readonly name="due_date_n"
                                                min="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'due_date')" />
                                        </div>
                                        <!-- </div> -->
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

                                    // Formatting the date in "DD-MM-YYYY" format
                                    var dueDateFormatted = `${day}-${monthNames[monthIndex]}-${year}`;

                                    // Set the formatted due date value to the input field
                                    document.getElementById('due_date').value = dueDateFormatted;
                                </script>


                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group"><b>Initiator Group</b></label>
                                        <select name="Initiator_Group" id="initiator_group">
                                            <option value="">-- Select --</option>
                                            <option value="CQA" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'CQA') selected @endif>
                                                Corporate Quality Assurance</option>
                                            <option value="QAB" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'QAB') selected @endif>
                                                Quality Assurance Biopharma</option>
                                            <option value="CQC" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'CQC') selected @endif>
                                                Central Quality Control</option>
                                            <option value="MANU" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'MANU') selected @endif>
                                                Manufacturing</option>
                                            <option value="PSG" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'PSG') selected @endif>Plasma
                                                Sourcing Group</option>
                                            <option value="CS" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'CS') selected @endif>
                                                Central Stores</option>
                                            <option value="ITG" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'ITG') selected @endif>
                                                Information Technology Group</option>
                                            <option value="MM" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'MM') selected @endif>
                                                Molecular Medicine</option>
                                            <option value="CL" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'CL') selected @endif>
                                                Central Laboratory</option>
                                            <option value="TT" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'TT') selected @endif>Tech
                                                team</option>
                                            <option value="QA" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'QA') selected @endif>
                                                Quality Assurance</option>
                                            <option value="QM" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'QM') selected @endif>
                                                Quality Management</option>
                                            <option value="IA" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'IA') selected @endif>IT
                                                Administration</option>
                                            <option value="ACC" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'ACC') selected @endif>
                                                Accounting</option>
                                            <option value="LOG" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'LOG') selected @endif>
                                                Logistics</option>
                                            <option value="SM" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'SM') selected @endif>
                                                Senior Management</option>
                                            <option value="BA" @if (old('Initiator_Group', $data->Initiator_Group ?? '') == 'BA') selected @endif>
                                                Business Administration</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Initiator Group Code</label>
                                        <input type="text" name="initiator_group_code" id="initiator_group_code"
                                            value="" readonly>
                                    </div>
                                </div>
                                <!-- <div class="col-12">
            <div class="group-input">
                <label for="Short Description">Short Description<span class="text-danger">*</span></label>
                <div class="relative-container">
                    <input id="docname" type="text" name="short_description" maxlength="255" required>
                    <button class="mic-btn" type="button">
                        <i class="fas fa-microphone"></i>
                    </button>
                    <button class="speak-btn" type="button">
                        <i class="fas fa-volume-up"></i>
                    </button>
                </div>
            </div>
        </div> -->

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="short_description">Short Description<span
                                                class="text-danger">*</span></label>
                                        <span id='rchars' class="text-primary">255<span> characters remaining
                                                <div class="relative-container">

                                                    <input id="short_description" id="docname" type="text"
                                                        class="mic-input" name="short_description" maxlength="255"
                                                        required>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="severity-level">Severity Level</label>
                                        <span class="text-primary">Severity levels in a QMS record gauge issue seriousness,
                                            guiding priority for corrective actions. Ranging from low to high, they ensure
                                            quality standards and mitigate critical risks.</span>
                                        <select name="severity_level">
                                            <option value="0">-- Select --</option>
                                            <option value="minor">Minor</option>
                                            <option value="major">Major</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group">Initiated Through</label>
                                        <div><small class="text-primary">Please select related information</small></div>
                                        <select name="initiated_through"
                                            onchange="otherController(this.value, 'others', 'initiated_through_req')">
                                            <option value="">-- select --</option>
                                            <option value="recall">Recall</option>
                                            <option value="return">Return</option>
                                            <option value="deviation">Deviation</option>
                                            <option value="complaint">Complaint</option>
                                            <option value="regulatory">Regulatory</option>
                                            <option value="lab-incident">Lab Incident</option>
                                            <option value="improvement">Improvement</option>
                                            <option value="others">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-6">
                                        <div class="group-input" id="initiated_through_req">
                                            <label for="If Other">Others<span class="text-danger d-none">*</span></label>
                                            <textarea name="initiated_if_other"></textarea>
                                        </div>
                                    </div> -->

                                <div class="col-lg-6">
                                    <div class="group-input" id="initiated_through_req">
                                        <label for="initiated_if_other">Others<span
                                                class="text-danger d-none">*</span></label>
                                        <div class="relative-container">
                                            <textarea id="initiated_if_other" class="mic-input" name="initiated_if_other"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>


                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="repeat">Repeat</label>
                                        <select name="repeat"
                                            onchange="otherController(this.value, 'yes', 'repeat_nature')">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                            <option value="NA">NA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input" id="repeat_nature">
                                        <label for="repeat_nature">Repeat Nature<span
                                                class="text-danger d-none">*</span></label>
                                        <textarea name="repeat_nature"></textarea>
                                    </div>
                                </div> --}}
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="audit_type">Type of Audit</label>
                                        <select name="audit_type"
                                            onchange="otherController(this.value, 'others', 'type_of_audit_req')">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="R&D">R&D</option>
                                            <option value="GLP">GLP</option>
                                            <option value="GCP">GCP</option>
                                            <option value="GDP">GDP</option>
                                            <option value="GEP">GEP</option>
                                            <option value="ISO 17025">ISO 17025</option>
                                            <option value="others">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-6">
                                        <div class="group-input" id="type_of_audit_req">
                                            <label for="If Other">If Others<span class="text-danger d-none">*</span></label>
                                            <textarea name="if_other"></textarea>
                                            @error('if_other')
        <p class="text-danger">this field is required</p>
    @enderror
                                        </div>
                                    </div> -->
                                <div class="col-lg-6">
                                    <div class="group-input" id="type_of_audit_req">
                                        <label for="if_other">If Others<span class="text-danger d-none">*</span></label>
                                        <div class="relative-container">
                                            <textarea name="if_other" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                        @error('if_other')
                                            <p class="text-danger">This field is required</p>
                                        @enderror
                                    </div>
                                </div>



                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="supplier_agencies">Supplier Agencies</label>
                                        <select name="external_agencies" id="supplier_agencies"
                                            onchange="toggleOthersField(this.value)">
                                            <option value="">-- Select --</option>
                                            <option value="Jordan FDA">Jordan FDA</option>
                                            <option value="USFDA">USFDA</option>
                                            <option value="MHRA">MHRA</option>
                                            <option value="ANVISA">ANVISA</option>
                                            <option value="ISO">ISO</option>
                                            <option value="WHO"></option>
                                            <option value="Local FDA">Local FDA</option>
                                            <option value="TGA">TGA</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input d-none relative-container" id="external_agencies_req">
                                        <label for="others">Supplier Agencies Others<span
                                                class="text-danger d-none">*</span></label>
                                        <textarea name="others" id="others" class="mic-input"></textarea>
                                        @component('frontend.forms.language-model')
                                        @endcomponent

                                        @error('if_other')
                                            <p class="text-danger">This field is required</p>
                                        @enderror
                                    </div>
                                </div>


                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="others">Others<span class="text-danger d-none">*</span></label>
                                        <textarea name="others"></textarea>
                                    </div>
                                </div> --}}
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="initial_comments">Description</label>
                                        <div class="relative-container">
                                            <textarea name="initial_comments" id="initial_comments" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Inv Attachments">Initial Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="audit_file_attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="inv_attachment[]"
                                                    oninput="addMultipleFiles(this, 'audit_file_attachment')" multiple>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            {{-- <div class="col-12">
                                <div class="group-input">
                                    <label for="inv Attachments">Initial Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="audit_file_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="inv_attachment[]"
                                                oninput="addMultipleFiles(this, 'inv_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}




                            <div class="button-block">
                                <button type="submit" id="ChangesaveButton"
                                    class="saveButton on-submit-disable-button ">Save</button>
                                <button type="button" id="ChangeNextButton" class="nextButton">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                        Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- Audit Planning content -->
                    <div id="CCForm2" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                {{-- <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date ">
                                        <label for="due-date">Due Date <span class="text-danger"></span></label>
                                        <div><small class="text-primary">Please mention expected date of completion</small></div>
                                        <div class="calenderauditee">
                                        <input type="text" name="due_date" id="due_date"  readonly placeholder="DD-MM-YYYY" />
                                        <input type="date" class="hide-input"
                                        oninput="handleDateInput(this, 'due_date')"
                                        />
                                         </div>
                                    </div>
                                </div> --}}

                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="start_date"><b>Audit Schedule Start Date
                                        </b></label>
                                        <input type="text" value="{{ date('d-M-Y') }}" name="start_date"
                                            disabled>
                                        <input type="hidden" value="{{ date('Y-m-d') }}" name="start_date">
                                    </div>
                                </div> --}}
                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Audit Schedule Start Date">Audit Schedule Start Date</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="start_date" readonly placeholder="DD-MM-YYYY" />
                                            <input type="date" id="start_date_checkdate" name="start_date"
                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'start_date');checkDate('start_date_checkdate','end_date_checkdate')" />
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Audit Schedule End Date">Audit Schedule End Date</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="end_date" readonly placeholder="DD-MM-YYYY" />
                                            <input type="date" id="end_date_checkdate" name="end_date"
                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'end_date');checkDate('start_date_checkdate','end_date_checkdate')" />
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="audit-agenda-grid">
                                            Audit Agenda<button type="button" name="audit-agenda-grid"
                                                onclick="addAuditAgenda('internalaudit')">+</button>
                                        </label>
                                        <table class="table table-bordered" id="internalaudit">
                                            <thead>
                                                <tr>
                                                    <th>Row#</th>
                                                    <th>Area of Audit</th>
                                                    <th>Scheduled Start Date</th>
                                                    <th>Scheduled Start Time</th>
                                                    <th>Scheduled End Date</th>
                                                    <th>Scheduled End Time</th>
                                                    <th>Auditor</th>
                                                    <th>Auditee</th>
                                                    <th>Remarks</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td><input type="text" name="audit[]"></td>
                                                    <td>
                                                        <div class="group-input new-date-data-field mb-0">
                                                            <div class="input-date">
                                                                <div class="calenderauditee">
                                                                    <input type="text" class="test"
                                                                        id="scheduled_start_date1" readonly
                                                                        placeholder="DD-MM-YYYY" />
                                                                    <input type="date"
                                                                        id="scheduled_start_date1_checkdate"
                                                                        name="scheduled_start_date[]"
                                                                        min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                        class="hide-input"
                                                                        oninput="handleDateInput(this, `scheduled_start_date1`);checkDate('scheduled_start_date1_checkdate','scheduled_end_date1_checkdate')" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><input type="time" name="scheduled_start_time[]"></td>
                                                    <td>
                                                        <div class="group-input new-date-data-field mb-0">
                                                            <div class="input-date">
                                                                <div class="calenderauditee">
                                                                    <input type="text" class="test"
                                                                        id="scheduled_end_date1" readonly
                                                                        placeholder="DD-MM-YYYY" />
                                                                    <input type="date"
                                                                        id="scheduled_end_date1_checkdate"
                                                                        name="scheduled_end_date[]"
                                                                        min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                        class="hide-input"
                                                                        oninput="handleDateInput(this, `scheduled_end_date1`);checkDate('scheduled_start_date1_checkdate','scheduled_end_date1_checkdate')" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><input type="time" name="scheduled_end_time[]"></td>
                                                    <td>
                                                        <select id="select-state" placeholder="Select..."
                                                            name="auditor[]">
                                                            <option value="">-Select-</option>
                                                            @foreach ($users as $data)
                                                                <option value="{{ $data->id }}">{{ $data->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select id="select-state" placeholder="Select..."
                                                            name="auditee[]">
                                                            <option value="">-Select-</option>
                                                            @foreach ($users as $data)
                                                                <option value="{{ $data->id }}">{{ $data->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="remark[]"></td>
                                                    <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                {{-- <div class="col-6">
                                    <div class="group-input">
                                        <label for="Facility Name">Facility Name</label>
                                        <select multiple name="Facility[]" placeholder="Select Facility Name"
                                            data-search="false" data-silent-initial-value-set="true" id="Facility">
                                            <option value="Plant 1">Plant 1</option>
                                            <option value="QA">QA</option>
                                            <option value="QC">QC</option>
                                            <option value="MFG">MFG</option>
                                            <option value="Corporate">Corporate</option>
                                            <option value="Microbiology">Microbiology</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Group Name">Group Name</label>
                                        <select multiple name="Group[]" placeholder="Select Group Name"
                                            data-search="false" data-silent-initial-value-set="true" id="Group">
                                            <option value="QA">QA</option>
                                            <option value="QC">QC</option>
                                            <option value="Manufacturing">Manufacturing</option>
                                            <option value="Warehouse">Warehouse</option>
                                            <option value="RA">RA</option>
                                            <option value="R&D">R&D</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="material_name">Product/Material Name</label>
                                        <div class="relative-container">
                                            <input type="text" name="material_name" id="material_name"
                                                class="mic-input">
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="if_comments">Comments(If Any)</label>
                                        <div class="relative-container">
                                            <textarea name="if_comments" id="if_comments" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                        Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- Audit Preparation content -->
                    <div id="CCForm3" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Lead Auditor">Lead Auditor</label>
                                        <select name="lead_auditor">
                                            <option value="">-- Select --</option>
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="File Attachments">File Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        {{-- <div class="file-attachment-field">
                                            <div id="file_attachment"></div>
                                            <input type="file" id="myfile" name="file_attachment[]"
                                            oninput="addMultipleFiles(this, 'file_attachment')" multiple>
                                        </div> --}}
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="file_attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="file_attachment[]"
                                                    oninput="addMultipleFiles(this, 'file_attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">

                                </div>
                                <div class="col-6">
                                    <div class="group-input">
                                        <label for="Audit Team">Audit Team</label>
                                        <select multiple name="Audit_team[]" placeholder="Select Audit Team"
                                            data-search="false" data-silent-initial-value-set="true" id="Audit">
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="group-input">
                                        <label for="Auditee">Auditee</label>
                                        <select multiple name="Auditee[]" placeholder="Select Auditee"
                                            data-search="false" data-silent-initial-value-set="true" id="Auditee">
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Auditor_Details">Supplier Auditor Details</label>
                                        <div class="relative-container">
                                            <textarea name="Auditor_Details" id="Auditor_Details" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="External_Auditing_Agency">Supplier Auditing Agency</label>
                                        <div class="relative-container">
                                            <textarea name="External_Auditing_Agency" id="External_Auditing_Agency" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Relevant_Guidelines">Relevant Guidelines / Industry Standards</label>
                                        <div class="relative-container">
                                            <textarea name="Relevant_Guidelines" id="Relevant_Guidelines" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="QA_Comments">QA Comments</label>
                                        <div class="relative-container">
                                            <textarea name="QA_Comments" id="QA_Comments" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Guideline Attachment">Guideline Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>


                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="file_attachment_guideline"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="file_attachment_guideline[]"
                                                    oninput="addMultipleFiles(this, 'file_attachment_guideline')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Audit Category">Audit Category</label>
                                        <select name="Audit_Category">
                                            <option value="0">-- Select --</option>
                                            <option value="1">Internal Audit/Self Inspection</option>
                                            <option value="2">Supplier Audit</option>
                                            <option value="3">Regulatory Audit</option>
                                            <option value="4">Consultant Audit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Supplier_Details">Supplier/Vendor/Manufacturer Details</label>
                                        <div class="relative-container">
                                            <input type="text" name="Supplier_Details" id="Supplier_Details"
                                                class="mic-input">
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Supplier_Site">Supplier/Vendor/Manufacturer Site</label>
                                        <div class="relative-container">
                                            <input type="text" name="Supplier_Site" id="Supplier_Site"
                                                class="mic-input">
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Comments">Comments</label>
                                        <div class="relative-container">
                                            <textarea name="Comments" id="Comments" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                        Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- Audit Execution content -->
                    <div id="CCForm4" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <div class="calenderauditee">
                                            <label for="audit_start_date Date">Audit Start Date</label>
                                            <div class="calenderauditee">
                                                <input type="text" id="audit_start_date" readonly
                                                    placeholder="DD-MM-YYYY" />
                                                <input type="date" id="audit_start_date_checkdate"
                                                    name="audit_start_date"
                                                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                    oninput="handleDateInput(this, 'audit_start_date');checkDate('audit_start_date_checkdate','audit_end_date_checkdate')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <div class="calenderauditee">
                                            <label for="Audit End Date">Audit End Date</label>
                                            <div class="calenderauditee">
                                                <input type="text" id="audit_end_date" readonly
                                                    placeholder="DD-MM-YYYY" />
                                                <input type="date" id="audit_end_date_checkdate" name="audit_end_date"
                                                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                    oninput="handleDateInput(this, 'audit_end_date');checkDate('audit_start_date_checkdate','audit_end_date_checkdate')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="group-input">
                                    <label for="audit-agenda-grid">
                                        Observation Details
                                        <button type="button" name="audit-agenda-grid" id="ObservationAdd">+</button>
                                        <span class="text-primary" data-bs-toggle="modal"
                                            data-bs-target="#observation-field-instruction-modal"
                                            style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="onservation-field-table"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Row#</th>
                                                    <th>Observation Details</th>
                                                    <th>Pre Comments</th>
                                                    <th>CAPA Details if any</th>
                                                    <th>Post Comments</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>


                                            </tbody>

                                        </table>
                                    </div>
                                </div>

                                {{-- <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Audit Attachments">Audit Attachments</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="audit_attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="Audit_file[]"
                                                    oninput="addMultipleFiles(this, 'audit_attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Inv Attachments">Audit Attachments</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="Audit_file"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="Audit_file[]"
                                                    oninput="addMultipleFiles(this, 'Audit_file')" multiple>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Audit_Comments1">Audit Comments</label>
                                        <div class="relative-container">
                                            <textarea name="Audit_Comments1" id="Audit_Comments1" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                        Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- Audit Response & Closure content -->
                    <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="sub-head">
                                    Audit Response
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Remarks">Remarks</label>
                                        <div class="relative-container">
                                            <textarea name="Remarks" id="Remarks" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Reference Recores">Reference Record</label>
                                        <select multiple id="reference_record" name="refrence_record[]" id="">

                                            @foreach ($old_record as $new)
                                                <option
                                                    value="{{ Helpers::getDivisionName($new->division_id) }}/SA/{{ date('Y') }}/{{ Helpers::recordFormat($new->record) }}">
                                                    {{ Helpers::getDivisionName($new->division_id) }}/SA/{{ date('Y') }}/{{ Helpers::recordFormat($new->record) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Report Attachments">Report Attachments</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        {{-- <input type="file" id="myfile" name="report_file[]" multiple> --}}
                                        {{-- <div class="file-attachment-field">
                                            <div id="Audit_file_attachment"></div>
                                            <input type="file" id="myfile" name="report_file[]"
                                            oninput="addMultipleFiles(this, 'Audit_file_attachment')" multiple>
                                        </div> --}}
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="report_attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="report_file[]"
                                                    oninput="addMultipleFiles(this, 'report_attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Audit Attachments">Audit Attachments.</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        {{-- <input type="file" id="myfile" name="myfile[]" multiple> --}}
                                        {{-- <div class="file-attachment-field">
                                            <div id="myfile_attachment"></div>
                                            <input type="file" id="myfile" name="myfile[]"
                                            oninput="addMultipleFiles(this, 'myfile_attachment')" multiple>
                                        </div> --}}
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="myfile_attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="myfile[]"
                                                    oninput="addMultipleFiles(this, 'myfile_attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Audit_Comments2">Audit Comments.</label>
                                        <div class="relative-container">
                                            <textarea name="Audit_Comments2" id="Audit_Comments2" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="due_date_extension">Due Date Extension Justification</label>
                                        <div><small class="text-primary">Please Mention justification if due date is
                                                crossed</small></div>
                                        <div class="relative-container">
                                            <textarea name="due_date_extension" id="due_date_extension" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="button-block">
                                <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                        Exit </a> </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Log content -->
                <div id="CCForm6" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Schedule On">Schedule Audit By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Schedule On">Schedule Audit On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Schedule On">Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Preparation Completed On"> Completed Audit Preparation
                                        By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Preparation Completed On">Completed Audit Preparation
                                        On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Schedule On">Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Mgr.more Info Reqd By">Reject By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Mgr.more Info Reqd On"> Reject On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Schedule On">Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Cancelled By">Cancelled By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Cancelled On">Cancelled On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Schedule On">Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>


                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="No CAPA Required By">No CAPA Required By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="No Capa Required On">No CAPA Required On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Schedule On"> Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>




                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Observation Submitted By">Issue Report By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Observation Submitted On">Issue Report On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Observation Submitted By">Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>


                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Lead More Info Reqd By">CAPA Plan Proposed By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Lead More Info Reqd On">CAPA Plan Proposed On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Schedule On">Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Lead More Info Reqd By">No CAPA Required By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Lead More Info Reqd On">No CAPA Required On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="group-input">
                                    <label for="Audit Schedule On">Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <!-- <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Audit Response Completed By">All CAPA Closed By</label>
                                            <div class="static"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Audit Response Completed On">Audit Response Completed On</label>
                                            <div class="static"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Response Feedback Verified By">Response Feedback Verified
                                                By</label>
                                            <div class="static"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Response Feedback Verified On">Response Feedback Verified
                                                On</label>
                                            <div class="static"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for=" Rejected By">Rejected By</label>
                                            <div class="static"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Rejected On">Rejected On</label>
                                            <div class="static"></div>
                                        </div>
                                    </div> -->

                        </div>
                        <div class="button-block">
                            <!-- <button type="submit" class="saveButton">Save</button> -->
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <!-- <button type="submit">Submit</button> -->
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>
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
        document.getElementById('myfile').addEventListener('change', function() {
            var fileListDiv = document.querySelector('.file-list');
            fileListDiv.innerHTML = ''; // Clear previous entries

            for (var i = 0; i < this.files.length; i++) {
                var file = this.files[i];
                var listItem = document.createElement('div');
                listItem.textContent = file.name;
                fileListDiv.appendChild(listItem);
            }
        });
    </script>


    <script>
        VirtualSelect.init({
            ele: '#Facility, #Group, #Audit, #Auditee ,#reference_record'
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

    {{-- <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     document.getElementById('type_of_audit').addEventListener('change', function() {
        //         var typeOfAuditReqInput = document.getElementById('type_of_audit_req');
        //         if (typeOfAuditReqInput) {
        //             var selectedValue = this.value;
        //             if (selectedValue == 'others') {
        //                 typeOfAuditReqInput.setAttribute('required', 'required');
        //             } else {
        //                 typeOfAuditReqInput.removeAttribute('required');
        //             }
        //         } else {
        //             console.error("Element with id 'type_of_audit_req' not found");
        //         }
        //     });
        // });
    </script> --}}
    <script>
        document.getElementById('initiator_group').addEventListener('change', function() {
            var selectedValue = this.value;
            document.getElementById('initiator_group_code').value = selectedValue;
        });
    </script>
    <script>
        var maxLength = 255;
        $('#docname').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchars').text(textlen);
        });
    </script>
    <!-- Add the following script at the end of your HTML -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const supplierAgencies = document.getElementById('supplier_agencies');
            const othersGroup = document.getElementById('external_agencies_req');
            const othersField = document.getElementById('others');
            const othersLabel = othersField.previousElementSibling;

            function toggleOthersField(value) {
                if (value === 'others') {
                    othersGroup.style.display = 'block';
                    othersField.required = true;
                    othersLabel.querySelector('span').classList.remove('d-none');
                } else {
                    othersGroup.style.display = 'none';
                    othersField.required = false;
                    othersLabel.querySelector('span').classList.add('d-none');
                }
            }

            // Initial check
            toggleOthersField(supplierAgencies.value);

            // Add event listener
            supplierAgencies.addEventListener('change', function() {
                toggleOthersField(this.value);
            });
        });
    </script>

    <!-- for Voice Access -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recognition = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
            const docnameInput = document.getElementById('docname');
            const startRecordBtn = document.getElementById('start-record-btn');

            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';

            startRecordBtn.addEventListener('click', function() {
                recognition.start();
            });

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                docnameInput.value += transcript;
            };

            recognition.onerror = function(event) {
                console.error(event.error);
            };
        });
    </script>
    <script>
        < link rel = "stylesheet"
        href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" >
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize speech recognition
            const recognition = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';

            // Function to start speech recognition and append result to the target element
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


        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize speech recognition
            const recognition = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';

            // Function to start speech recognition and append result to the target element
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

            // Event delegation for all mic buttons
            //     document.addEventListener('click', function(event) {
            //         if (event.target.closest('.mic-btn')) {
            //             const button = event.target.closest('.mic-btn');
            //             const inputField = button.previousElementSibling;
            //             if (inputField && inputField.classList.contains('mic-input')) {
            //                 startRecognition(inputField);
            //             }
            //         }
            //     });
            // });

            // Show/hide the container based on user selection
            function toggleOthersField(selectedValue) {
                const container = document.getElementById('external_agencies_req');
                if (selectedValue === 'others') {
                    container.classList.remove('d-none');
                } else {
                    container.classList.add('d-none');
                }
            }
        })
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize speech recognition
            const recognition = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';

            // Function to start speech recognition and append result to the target element
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

            // Event delegation for all mic buttons
            // document.addEventListener('click', function(event) {
            //     if (event.target.closest('.mic-btn')) {
            //         const button = event.target.closest('.mic-btn');
            //         const inputField = button.previousElementSibling;
            //         if (inputField && inputField.classList.contains('mic-input')) {
            //             startRecognition(inputField);
            //         }
            //     }
            // });

            // Show/hide mic button on focus/blur of input fields
            //  const micInputs = document.querySelectorAll('.mic-input');
            //     micInputs.forEach(input => {
            //         input.addEventListener('focus', function() {
            //             const micBtn = this.nextElementSibling;
            //             if (micBtn && micBtn.classList.contains('mic-btn')) {
            //                 micBtn.style.display = 'block';
            //             }
            //         });
            //         input.addEventListener('blur', function(event) {
            //             const micBtn = this.nextElementSibling;
            //             if (micBtn && micBtn.classList.contains('mic-btn')) {
            //                 // Use a timeout to prevent immediate hiding when the button is clicked
            //                 setTimeout(() => {
            //                     if (!document.activeElement.classList.contains('mic-btn')) {
            //                         micBtn.style.display = 'none';
            //                     }
            //                 }, 200);
            //             }
            //         });
            //     });
            //
            // Show/hide the container based on user selection
            function toggleOthersField(selectedValue) {
                const container = document.getElementById('external_agencies_req');
                if (selectedValue === 'others') {
                    container.classList.remove('d-none');
                } else {
                    container.classList.add('d-none');
                }
            }
        });
    </script>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function() {
            let audio = null;
            let selectedLanguage = 'en-us'; // Default language
            let inputText = '';

            // When the user clicks the button, open the mini modal
            $(document).on('click', '.speak-btn', function() {
                let inputField = $(this).siblings('textarea, input');
                inputText = inputField.val();
                let modal = $(this).siblings('.mini-modal');
                if (inputText) {
                    // Store the input field element
                    $(modal).data('inputField', inputField);
                    modal.css({
                        display: 'block',
                        top: $(this).position().top - modal.outerHeight() - 10,
                        left: $(this).position().left + $(this).outerWidth() - modal.outerWidth()
                    });
                }
            });

            // When the user clicks on <span> (x), close the mini modal
            $(document).on('click', '.close', function() {
                $(this).closest('.mini-modal').css('display', 'none');
            });

            // When the user selects a language and clicks the button
            $(document).on('click', '#select-language-btn', function(event) {
                event.preventDefault(); // Prevent form submission
                let modal = $(this).closest('.mini-modal');
                selectedLanguage = modal.find('#language-select').val();
                let inputField = modal.data('inputField');
                let textToSpeak = inputText;

                if (textToSpeak) {
                    if (audio) {
                        audio.pause();
                        audio.currentTime = 0;
                    }

                    // Translate the text before converting to speech
                    translateText(textToSpeak, selectedLanguage.split('-')[0]).then(translatedText => {
                        const apiKey = '2273705f1f6f434194956a200a586470';
                        const url =
                            `https://api.voicerss.org/?key=${apiKey}&hl=${selectedLanguage}&src=${encodeURIComponent(translatedText)}&r=0&c=WAV&f=44khz_16bit_stereo`;
                        audio = new Audio(url);
                        audio.play();
                        audio.onended = function() {
                            audio = null;
                        };
                    });

                }

                modal.css('display', 'none');
            });

            // Speech-to-Text functionality
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


            async function translateText(text, targetLanguage) {
                const url = 'https://text-translator2.p.rapidapi.com/translate';
                const data = new FormData();
                data.append('source_language', 'en');
                data.append('target_language', targetLanguage);
                data.append('text', text);

                const options = {
                    method: 'POST',
                    headers: {
                        'x-rapidapi-key': '5246c9098fmshc966ee7f6cea588p14a110jsn3979434fe858',
                        'x-rapidapi-host': 'text-translator2.p.rapidapi.com'
                    },
                    body: data
                };

                const response = await fetch(url, options);
                const result = await response.json();
                return result.data.translatedText;
            }

            // Update remaining characters
            $('#docname').on('input', function() {
                const remaining = 255 - $(this).val().length;
                $('#rchars').text(remaining);
            });

            // Initialize remaining characters count
            const remaining = 255 - $('#docname').val().length;
            $('#rchars').text(remaining);
        });
    </script>






    <style>
        #external_agencies_req {
            display: none;
        }
    </style>
    <script>
        $(document).ready(function() {

            $('.mainform').on('submit', function(e) {
                $('.on-submit-disable-button').prop('disabled', true);
            });
        })
    </script>
@endsection
