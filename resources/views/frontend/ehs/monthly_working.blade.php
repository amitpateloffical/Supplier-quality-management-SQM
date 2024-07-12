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
            / Monthly Working 
        </div>
    </div>
   


    {{-- ! ========================================= --}}
    {{-- !               DATA FIELDS                 --}}
    {{-- ! ========================================= --}}
    <div id="change-control-fields">
        <div class="container-fluid">

            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Monthly Working Hours</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Activity Log</button>
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
                                        <label for="RLS Record Number"><b>Originator</b></label>
                                        <input disabled type="text" name=""
                                            value="">
                                        
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
                                        <label for="due-date">Due Date <span class="text-danger"></span></label>
                                        <!-- <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            value="" name="due_date"> -->
                                        <div class="calenderauditee">                                     
                                            <input type="text"  id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                            <input type="date" name="due_date"  min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                            class="hide-input"
                                           />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description">Short Description<span
                                                class="text-danger">*</span></label><span id="rchars">255</span>
                                        characters remaining
                                        <input id="docname" type="text" name="short_description" maxlength="255" required>
                                    </div>
                                </div>  
                               
                                
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Short Description"> Description<span
                                                class="text-danger"></span></label>
                                        <textarea name="description"></textarea>
                                    </div>
                                </div>

<div class="col-lg-6" >
    <div class="group-input">

<label for="Short Description"> Zone<span class="text-danger"></span></label>
<select>
    <option>pankaj jat</option>
    <option>pankaj jat</option>
</select>
</div>
</div>

<div class="col-lg-6" >
    <div class="group-input">

<label for="Short Description"> Country<span class="text-danger"></span></label>
<select>
    <option>India</option>
    <option>USA</option>
</select>
</div>
</div>

<div class="col-lg-6" >
    <div class="group-input">

<label for="Short Description"> City<span class="text-danger"></span></label>
<select>
    <option>Indore</option>
    <option>Bhopal</option>
</select>
</div>
</div>

<div class="col-lg-6" >
    <div class="group-input">

<label for="Short Description"> State/District<span class="text-danger"></span></label>
<select>
    <option>Mp</option>
    <option>Gujrat</option>
</select>
</div>
</div>

<div class="col-lg-6" >
    <div class="group-input">

<label for="Short Description"> Year<span class="text-danger"></span></label>
<select>
    <option>2024</option>
    <option>2025</option>
</select>
</div>
</div>

<div class="col-lg-6" >
    <div class="group-input">

<label for="Short Description"> Month<span class="text-danger"></span></label>
<select>
    <option>Jan</option>
    <option>Feb</option>
</select>
</div>
</div>

<div class="col-lg-6">
<div class="group-input">
<label>Number Of Own Employess</label>
<input/>
</div>
</div>

<div class="col-lg-6">
<div class="group-input">
<label>Hours Own Employess</label>
<input/>
</div>
</div>

<div class="col-lg-6">
<div class="group-input">
<label>Number Of Contractors</label>
<input/>
</div>
</div>

<div class="col-lg-6">
<div class="group-input">
<label>Hours Of Contractors</label>
<input/>
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
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button"> <a class="text-white"
                                        href="{{ url('rcms/qms-dashboard') }}">Exit
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
        var maxLength = 255;
        $('#docname').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchars').text(textlen);});
    </script>
@endsection
