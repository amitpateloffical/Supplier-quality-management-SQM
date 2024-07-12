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
        / Serious Adverse Event
    </div>
</div>



{{-- ! ========================================= --}}
{{-- !               DATA FIELDS                 --}}
{{-- ! ========================================= --}}
<div id="change-control-fields">
    <div class="container-fluid">

        <!-- Tab links -->
        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">SAE</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">SAE Information</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Signatures</button>
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
                            <!-- General Information -->
                        </div> <!-- RECORD NUMBER -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="RLS Record Number"><b>Initiator</b></label>
                                    <input disabled type="text" name="" value="">

                                </div>
                            </div>


                            {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Due Date"> Date Opened</label>

                                        @if (!empty($cc->due_date))
                                        <div class="static"></div>
                                        @endif
                                    </div>
                                </div> --}}


                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="due-date">Date of Initiation<span class="text-danger"></span></label>
                                    <!-- <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            value="" name="due_date"> -->
                                    <div class="calenderauditee">
                                        <input type="text" disabled id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input disabled type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Short Description">Short Description<span class="text-danger">*</span></label><span id="rchars">255</span>
                                    characters remaining
                                    <input id="docname" type="text" name="short_description" maxlength="255" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">

                                    <label for="Short Description"> Assigned To<span class="text-danger"></span></label>
                                    <select>
                                        <option>pankaj jat</option>
                                        <option>pankaj jat</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="due-date">Due Date<span class="text-danger"></span></label>
                                    <p class="text-primary">Please mention expected date of completion</p>
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

                                    <label for="Short Description"> Type<span class="text-danger"></span></label>
                                    <select>
                                        <option>SAE</option>
                                        <option>RWD</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="file_attach">File Attachments</label>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="file_attach"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="file_attach[]" oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                        </div>
                                    </div>
                                    {{-- <input type="file" name="file_attach[]" multiple> --}}
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Short Description"> Description<span class="text-danger"></span></label>
                                    <textarea name="description"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Short Description"> Comments<span class="text-danger"></span></label>
                                    <textarea name="description"></textarea>
                                </div>
                            </div>

                            <div class="sub-head">Location</div>

                            <div class="col-lg-6">
                                <div class="group-input">

                                    <label for="Short Description"> Zone<span class="text-danger"></span></label>
                                    <select>
                                        <option>pankaj jat</option>
                                        <option>pankaj jat</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">

                                    <label for="Short Description"> Country<span class="text-danger"></span></label>
                                    <select>
                                        <option>India</option>
                                        <option>USA</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">

                                    <label for="Short Description"> City<span class="text-danger"></span></label>
                                    <select>
                                        <option>Indore</option>
                                        <option>Bhopal</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">

                                    <label for="Short Description"> State/District<span class="text-danger"></span></label>
                                    <select>
                                        <option>Mp</option>
                                        <option>Gujrat</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">

                                    <label for="Short Description"> Site Name<span class="text-danger"></span></label>
                                    <select>
                                        <option>Pharma</option>
                                        <option>it</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">

                                    <label for="Short Description"> Building<span class="text-danger"></span></label>
                                    <select>
                                        <option>Pu-4</option>
                                        <option>Pu-5</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">

                                    <label for="Short Description"> Floor<span class="text-danger"></span></label>
                                    <select>
                                        <option>1</option>
                                        <option>2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">

                                    <label for="Short Description"> Room<span class="text-danger"></span></label>
                                    <select>
                                        <option>C-101</option>
                                        <option>C-102</option>
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



                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="cancelled by">Number (ID)</label>
                                    <input />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Closed on">Project Code</label>
                                    <input />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="cancelled by">Primary SAE</label>
                                    <input />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Closed on">SAE Number</label>
                                    <input />
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Short Description">Severity Rate<span class="text-danger"></span></label>
                                    <select>
                                        <option>101</option>
                                        <option>102</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Short Description"> Occurence<span class="text-danger"></span></label>
                                    <select>
                                        <option>A</option>
                                        <option>B</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Short Description"> Detection<span class="text-danger"></span></label>
                                    <select>
                                        <option>01</option>
                                        <option>02</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Short Description"> RPN<span class="text-danger"></span></label>
                                    <select>
                                        <option>pankaj</option>
                                        <option>jat</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Short Description"> Protocol Type<span class="text-danger"></span></label>
                                    <select>
                                        <option>select</option>
                                        <option>select1</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Short Description"> Reportability<span class="text-danger"></span></label>
                                    <select>
                                        <option>123</option>
                                        <option>1234</option>
                                    </select>
                                </div>
                            </div>



                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Short Description"> CROM<span class="text-danger"></span></label>
                                    <select>
                                        <option>C-A</option>
                                        <option>C-B</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Short Description"> Lead Investigator<span class="text-danger"></span></label>
                                    <select>
                                        <option>Pankaj</option>
                                        <option>Gaurav</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Short Description"> Follow-up Information<span class="text-danger"></span></label>
                                    <select>
                                        <option>AB-1</option>
                                        <option>AB-2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Short Description"> Route Of Administration<span class="text-danger"></span></label>
                                    <select>
                                        <option>SAE</option>
                                        <option>SAR</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Short Description"> Carbon Copy List<span class="text-danger"></span></label>
                                    <select>
                                        <option>C-101</option>
                                        <option>C-102</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Short Description"> Comments<span class="text-danger"></span></label>
                                    <textarea name="description"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="cancelled by">Primary SAE</label>
                                    <input />
                                </div>
                            </div>
                            <div class="sub-head">Product Information</div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="cancelled by">Manufacturer</label>
                                    <input />
                                </div>
                            </div>
                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Product/Material (0)
                                    <button type="button" name="audit-agenda-grid" id="ObservationAdd">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        Open
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="onservation-field-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Product Name</th>
                                                <th style="width: 16%"> Batch Number</th>
                                                <th style="width: 15%">Expiry Date</th>
                                                <th style="width: 15%">Manufactured Date</th>
                                                <th style="width: 15%">Disposition </th>
                                                <th style="width: 15%">Comment</th>
                                                <th style="width: 15%">Remarks</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>
                                           
                                            <td><input type="text" name="ProductName[]"></td>
                                            <td><input type="text" name="BatchNumber[]"></td>
                                            <td><input type="date" name="ExpiryDate[]"></td>
                                            <td><input type="date" name="ManufacturedDate[]"></td>
                                            <td><input type="text" name="Disposition[]"></td>
                                            <td><input type="text" name="Comment[]"></td>
                                            <td><input type="text" name="Remarks[]"></td>


                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="sub-head">Important Dates</div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>Awareness Date</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>CROM Safety Report App On</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label> Date CROM Concurred</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>Date Draft SR Sent</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>Date MM Concurred</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>Date Of Event Resolution</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>Date PI Concurred</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>Date Recieved</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>Date Safety Assessment Sent</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>Date Sent To RA</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>Date Sent To Sites</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>SAE Onset Date</label>
                                    <input type="date" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>MM Safety Report Approved On</label>
                                    <input type="date" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label>PI Safety Report Approved On</label>
                                    <input type="date" />
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
                </div>
                <div id="CCForm3" class="inner-block cctabcontent">
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
            $('#ObservationAdd').click(function(e) {
                function generateTableRow(serialNumber) {
                    
                    
                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial[]" value="' + serialNumber +'"></td>' +
                       '<td><input type="text" name="ProductName[]"></td>'+
                         '<td><input type="text" name="BatchNumber[]"></td>'+
                         '<td><input type="date" name="ExpiryDate[]"></td>'+
                         '<td><input type="date" name="ManufacturedDate[]"></td>'+
                        '<td><input type="text" name="Disposition[]"></td>'+
                         '<td><input type="text" name="Comment[]"></td>'+
                         '<td><input type="text" name="Remarks[]"></td>'+
                        '</tr>';

                    // for (var i = 0; i < users.length; i++) {
                    //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    // }

                    // html += '</select></td>' + 
                  
                        '</tr>';

                    return html;
                }

                var tableBody = $('#onservation-field-table tbody');
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
