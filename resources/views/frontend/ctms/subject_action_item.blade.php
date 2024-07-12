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
        / Subject Action Item
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
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Treatment Information</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Signatures</button>

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
                                    <label for="RLS Record Number"><b>Initiator</b></label>
                                    <input disabled type="text" name="" value="">

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>Assigned To</b></label>
                                    <select>
                                        <option>Pankaj</option>
                                        <option>Manish</option>

                                    </select>
                                </div>
                            </div>

                            {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Due Date">Due Date</label>

                                        @if (!empty($cc->due_date))
                                        <div class="static"></div>
                                        @endif
                                    </div>
                                </div> --}}


                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="due-date"> Date Due <span class="text-danger"></span></label>
                                    <p class="text-primary">Please mention expected date of completion</p>
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
                                    <label for="due-date">Date of Initiation<span class="text-danger"></span></label>
                                    <!-- <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            value="" name="due_date"> -->
                                    <div class="calenderauditee">
                                        <input type="text" disabled id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" disabled name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" />
                                    </div>
                                </div>
                            </div>

                            <div class="sub-head">Study Details</div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>(Root Parent) Trade Name</b></label>
                                    <input  type="text" name="" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>(Root Parent) Assigned To</b></label>
                                    <select>
                                        <option>Pankaj</option>
                                        <option>Manish</option>

                                    </select>
                                </div>
                            </div>


                            <div class="sub-head">Subject Details</div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>( Parent) Subject Name</b></label>
                                    <input  type="text" name="" value="">
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>( Parent) Gender</b></label>
                                    <select>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>( Parent) Date Of Birth</b></label>
                                    <input  type="date" name="" value="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>( Parent) Race</b></label>
                                    <select>
                                        <option>--Select--</option>

                                        <option>23</option>
                                        <option>23</option>
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


                <div id="CCForm2" class="inner-block cctabcontent">
                    <div class="inner-block-content">

                        <div class="row">

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="cancelled by">Short Description</label>
                                    <input />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>Clinical Efficacy</b></label>
                                    <select>
                                        <option>--Select--</option>

                                        <option>none</option>
                                        <option>pankaj</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>Carry Over Effect</b></label>
                                    <select>
                                        <option>--Select--</option>

                                        <option>1</option>
                                        <option>2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>Last Monitered (Days)</b></label>
                                    <select>
                                        <option>--Select--</option>
                                        <option>1Days</option>
                                        <option>2Days</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="cancelled by">Total Doses Recieved</label>
                                    <input />
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="cancelled by">Treatment Effect</label>
                                    <input />
                                </div>
                            </div>


                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    DCF (0)
                                    <button type="button" name="audit-agenda-grid" id="DCFadd">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        Open
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="DCF">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Number</th>
                                                <th style="width: 16%"> Date</th>
                                                <th style="width: 15%">Sent Date</th>
                                                <th style="width: 15%">Returned Date</th>
                                                <th style="width: 15%">Data Collection Method </th>
                                                <th style="width: 15%">Comment</th>
                                                <th style="width: 15%">Remarks</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>

                                            <td><input type="text" name="Number[]"></td>
                                            <td><input type="date" name="date[]"></td>
                                            <td><input type="date" name="SentDate[]"></td>
                                            <td><input type="date" name="ReturnedDate[]"></td>
                                            <td><input type="text" name="Data Collection Method[]"></td>
                                            <td><input type="text" name="Comment[]"></td>
                                            <td><input type="text" name="Remarks[]"></td>


                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Minor Protocol Voilation (0)
                                    <button type="button" name="audit-agenda-grid" id="ObservationAdd">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        Open
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="minor-protocol-voilation">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Item Description</th>
                                                <th style="width: 16%"> Date</th>
                                                <th style="width: 15%">Sent Date</th>
                                                <th style="width: 15%">Returned Date</th>
                                                <th style="width: 15%">Comment</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>
                                            <td><input type="text" name="ItemDescription[]"></td>
                                            <td><input type="date" name="date[]"></td>
                                            <td><input type="date" name="SentDate[]"></td>
                                            <td><input type="date" name="ReturnedDate[]"></td>
                                            <td><input type="text" name="Comment[]"></td>


                                        </tbody>

                                    </table>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <Label>Comments</Label>
                                    <textarea></textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <Label>Summary </Label>
                                    <textarea></textarea>
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


                <div id="CCForm5" class="inner-block cctabcontent">
                    <div class="inner-block-content">

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="cancelled by">Closed By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Closed on">Closed On</label>
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
        $('#DCFadd').click(function(e) {
            function generateTableRow(serialNumber) {


                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="ItemDescription[]"></td>' +
                    '<td><input type="date" name="date[]"></td>' +
                    '<td><input type="date" name="SentDate[]"></td>' +
                    '<td><input type="date" name="ReturnedDate[]"></td>' +
                    '<td><input type="text" name="Comment[]"></td>' +
                    '<td><input type="text" name="Comment[]"></td>' +
                    '<td><input type="text" name="Comment[]"></td>' +

                    '</tr>';

                // for (var i = 0; i < users.length; i++) {
                //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                // }

                // html += '</select></td>' + 

                '</tr>';

                return html;
            }

            var tableBody = $('#DCF tbody');
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
    var maxLength = 255;
    $('#docname').keyup(function() {
        var textlen = maxLength - $(this).val().length;
        $('#rchars').text(textlen);
    });
</script>
@endsection