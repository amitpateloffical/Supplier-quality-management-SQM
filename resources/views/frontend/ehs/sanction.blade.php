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
            / EHS-sanction
        </div>
    </div>
   


    {{-- ! ========================================= --}}
    {{-- !               DATA FIELDS                 --}}
    {{-- ! ========================================= --}}
    <div id="change-control-fields">
        <div class="container-fluid">

            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Sanction</button>
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
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input"> 
                                        
                                        <label for="Originator"><b>Originator</b></label>
                                        <input type="text" name="Originator"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="opened-date">Date Opened<span class="text-danger"></span></label>
                                        <div class="calenderauditee">                                     
                                            <input type="text"  id="opened_date" placeholder="DD-MMM-YYYY" />
                                            <input type="date" name="opened_date"  min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                            class="hide-input"
                                            oninput="handleDateInput(this, 'opened_date')"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="short_description">Short Description<span
                                                class="text-danger">*</span></label>
                                                <div><small class="text-primary">Sanction short description to be represented on desktop</small></div>
                                        <input id="docname" type="text" name="short_description" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="assign_to">
                                            Assigned To <span class="text-danger"></span>
                                        </label>
                                        <div><small class="text-primary">Person Responsible</small></div>
                                        <select id="select-state" placeholder="Select..." name="assign_to">
                                            <option value="">Select a value</option>
                                            <option value="">$1</option>
                                            <option value="">$2</option>
                                            <option value="">$3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="due-date">Due Date <span class="text-danger"></span></label>
                                        <div><small class="text-primary text-danger">6 Last date this sanction should be closed by</small></div>
                                        <div class="calenderauditee">                                     
                                            <input type="text"  id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                            <input type="date" name="due_date"  min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                            class="hide-input"
                                            oninput="handleDateInput(this, 'due_date')"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="group-input">
                                        <label for="sanction_type">
                                            Type <span class="text-danger"></span>
                                        </label>
                                        <div><small class="text-primary">Type of Sanction</small></div>
                                        <select id="select-state" placeholder="Select..." name="sanction_type">
                                            <option value="">Enter your selection here</option>
                                            <option value="">$1</option>
                                            <option value="">$2</option>
                                            <option value="">$3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="file_attach">File Attachments</label>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="file_attach"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="file_attach[]"
                                                    oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                            </div>
                                        </div>
                                        {{-- <input type="file" name="file_attach[]" multiple> --}}
                                    </div>
                                </div>
                        
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="description"> Description<span
                                                class="text-danger"></span></label>
                                        <textarea name="description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="authority_type">
                                            Authority Type <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Enter your selection here" name="authority_type">
                                            <option value="">Select a value</option>
                                            <option value="">$1</option>
                                            <option value="">$2</option>
                                            <option value="">$3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="Authority">
                                            Authority <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Enter your selection here" name="Authority">
                                            <option value="">Select a value</option>
                                            <option value="">$1</option>
                                            <option value="">$2</option>
                                            <option value="">$3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">  
                                    @if (!empty($cc->id))
                                        <input type="hidden" name="ccId" value="{{ $cc->id }}">
                                    @endif
                                    <div class="group-input">
                                        <label for="Fine">Fine</label>
                                        <input type="text" value="" name="Fine">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="Currency">
                                            Currency <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Enter your selection here" name="Currency">
                                            <option value="">Select a value</option>
                                            <option value="">$1</option>
                                            <option value="">$2</option>
                                            <option value="">$3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                            </div>
                        </div>
                    </div>


                    <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                            <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="closed_by">Closed By</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="closed_on">Closed On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                                <div class="button-block">
                                    <button type="submit" class="saveButton">Save</button>
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a></button>
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
            $('#rchars').text(textlen);});
    </script>
@endsection
