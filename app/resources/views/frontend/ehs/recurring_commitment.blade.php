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
            / EHS_Recurring Commitment 
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
                {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Parent Information</button> --}}
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Commitment Information</button>
                <!-- <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Action Approval</button> -->
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Signature</button>
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
                                    @if (!empty($cc->id))
                                        <input type="hidden" name="ccId" value="{{ $cc->id }}">
                                    @endif
                                    <div class="group-input">
                                        <label for="originator">Initiator</label>
                                        <input disabled type="text"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Date Opened">Date of Initiation</label>
                                        {{-- <div class="static">{{ date('d-M-Y') }}</div> --}}
                                        <input disabled type="text"
                                            value=""
                                            name="intiation_date">
                                        <input type="hidden" value="" name="intiation_date">
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
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Assigned To <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="assign_to">
                                            <option value="">Select a value</option>
                                           
                                                <option value=""></option>
                                      
                                        </select>
                                      
                                            
                                 
                                    </div>
                                </div>
                                <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="due-date">Due Date <span class="text-danger"></span></label>
                                
                                        <div class="calenderauditee">                                     
                                            <input type="text"  id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                            <input type="date" name="due_date"  min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                            class="hide-input"
                                            oninput="handleDateInput(this, 'due_date')"/>
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
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Zone <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="zone">
                                            <option value="">Select a value</option>
                                           
                                                <option value=""></option>
                                      
                                        </select>
                                      
                                            
                                 
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Country <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="country">
                                            <option value="">Select a value</option>
                                           
                                                <option value=""></option>
                                      
                                        </select>
                                      
                                            
                                 
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            City <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="city">
                                            <option value="">Select a value</option>
                                           
                                                <option value=""></option>
                                      
                                        </select>
                                      
                                            
                                 
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            State/District <span class="text-danger"></span>
                                        </label>
                                        <input id="docname" type="text" name="state_district">
                                      
                                        </select>
                                      
                                            
                                 
                                    </div>
                                </div> 

                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            EPA Identification Number <span class="text-danger"></span>
                                        </label>
                                        <input id="docname" type="text" name="EPA_identification_number">    
                                 
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Impact<span class="text-danger"></span>
                                        </label>
                                        <input id="docname" type="text" name="impact">
                                      
                                        </select>
                                      
                                            
                                 
                                    </div>
                                </div> 

                                
                             
                                </div>
                              
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Responsible Department">Responsible Department</label>
                                        <select name="departments">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="1">Quality Assurance-CQA</option>
                                            <option value="2">Research and development</option>
                                            <option value="3">Regulatory Science</option>
                                            <option value="4">Supply Chain Management</option>
                                            <option value="5">Finance</option>
                                            <option value="6">QA-Digital</option>
                                            <option value="7">Central Engineering</option>
                                            <option value="8">Projects</option>
                                            <option value="9">Marketing</option>
                                            <option value="10">QCAT</option>
                                            <option value="11">Marketing</option>
                                            <option value="12">GMP Pilot Plant</option>
                                            <option value="13">Manufacturing Sciences and Technology</option>
                                            <option value="14">Environment, Health and Safety</option>
                                            <option value="15">Business Relationship Management</option>
                                            <option value="16">National Regulatory Affairs</option>
                                            <option value="17">HR</option>
                                            <option value="18">Admin</option>
                                            <option value="19">Information Technology</option>
                                            <option value="20">Program Management QA Analytical (Q13)</option>
                                            <option value="21">QA Analytical (Q8)</option>
                                            <option value="22">QA Packaging Development</option>
                                            <option value="23">QA Engineering</option>
                                            <option value="24">DS Quality Assurance</option>
                                            <option value="25">Quality Control (Q13)</option>
                                            <option value="26">Quality Control (Q8)</option>
                                            <option value="27">Quality Control (Q15)</option>
                                            <option value="28">QC Microbiology (B1)</option>
                                            <option value="29">QC Microbiology (B2)</option>
                                            <option value="30">Production (B1)</option>
                                            <option value="31">Production (B2)</option>
                                            <option value="32">Production (Packing)</option>
                                            <option value="33">Production (Devices)</option>
                                            <option value="34">Production (DS)</option>
                                            <option value="35">Engineering and Maintenance (B1)</option>
                                            <option value="36">Engineering and Maintenance (B2)</option>
                                            <option value="37">Engineering and Maintenance (W20)</option>
                                            <option value="38">Device Technology Principle Management</option>
                                            <option value="39">Production (82)</option>
                                            <option value="40">Production (Packing)</option>
                                            <option value="41">Production (Devices)</option>
                                            <option value="42">Production (DS)</option>
                                            <option value="43">Engineering and Maintenance (B1)</option>
                                            <option value="44">Engineering and Maintenance (B2) Engineering and
                                                Maintenance (W20)
                                            </option>
                                            <option value="45">Device Technology Principle Management</option>
                                            <option value="46">Warehouse(DP)</option>
                                            <option value="47">Drug safety</option>
                                            <option value="48">Others</option>
                                            <option value="49">Visual Inspection</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sub-head">
                                Additional Information
                            </div>
                                <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="file_attach">Permit Certificate</label>
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
                                <div class="col-lg-6">
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
                                </div>
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="file_attach">Related URL</label>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="file_attach"></div>
                                            <div class="add-btn">
                                                <div>Edit</div>
                                                <input type="file" id="myfile" name="file_attach[]"
                                                    oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                            </div>
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
                            
                        </div>
                    </div>
                    <div id="CCForm3" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="sub-head col-12">Commitment Details</div>
                       
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Type Of Commitment <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="type_of_commitment">
                                            <option value="">Select a value</option>
                                           
                                                <option value=""></option>
                                      
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                        Commitment Frequency <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="commitment_frequency">
                                            <option value="">Select a value</option>
                                           
                                                <option value=""></option>
                                      
                                        </select>
                                      
                                            
                                 
                                    </div>
                                </div>
                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="start_date">Commitment Start Date</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="start_date" readonly
                                                placeholder="DD-MMM-YYYY" />
                                            <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"  id="start_date_checkdate" name="start_date" class="hide-input"
                                                oninput="handleDateInput(this, 'start_date');checkDate('start_date_checkdate','end_date_checkdate')" />
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-lg-6  new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="end_date">Commitment End Date</lable>
                                        <div class="calenderauditee">
                                        <input type="text" id="end_date"                             
                                                placeholder="DD-MMM-YYYY" />
                                             <input type="date"  min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="end_date_checkdate" name="end_date" class="hide-input"
                                                oninput="handleDateInput(this, 'end_date');checkDate('start_date_checkdate','end_date_checkdate')" />
                                        </div>
                                   
                                        
                                    </div>
                                </div>
                                <div class="col-lg-6  new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="end_date">Next Commitment Date</lable>
                                        <div class="calenderauditee">
                                        <input type="text" id="end_date"                             
                                                placeholder="DD-MMM-YYYY" />
                                             <input type="date"  min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="end_date_checkdate" name="end_date" class="hide-input"
                                                oninput="handleDateInput(this, 'end_date');checkDate('start_date_checkdate','end_date_checkdate')" />
                                        </div>
                                   
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Others Involved <span class="text-danger"></span>
                                        </label>
                                        <input id="docname" type="text" name="other_involved"  >
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Site <span class="text-danger"></span>
                                        </label>
                                        <input id="docname" type="text" name="site"  >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Site Contact <span class="text-danger"></span>
                                        </label>
                                        <input id="docname" type="text" name="site_contact"  >
                                    </div>
                                </div>





                                <div class="col-6">
                                    <div class="group-input">
                                        <label for="Comments">Description</label>
                                        <textarea name="description"></textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="group-input">
                                        <label for="Comments">Comments</label>
                                        <textarea name="comments"></textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="group-input">
                                        <label for="Comments">Commitment Action</label>
                                        <textarea name="commitment_action"></textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="group-input">
                                        <label for="Comments">Commitment Notes</label>
                                        <textarea name="commitment_action"></textarea>
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="Support_doc">Supporting Documents</label>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="Support_doc"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="Support_doc[]"
                                                    oninput="addMultipleFiles(this, 'Support_doc')" multiple>
                                            </div>
                                        </div>

                                    </div>
                                </div> --}}
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                        Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="sub-head">
                                Electronic Signatures
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="submitted by">Submitted By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="submitted on">Submitted On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                              
                              
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="completed by">Commitment Approved By</label>
                                        <div class="static"></div> 
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="completed on">Commitment Approved On</label>
                                         <div class="Date"></div>
                                    </div>
                                </div>
                              
                            </div>
                            <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                               
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
