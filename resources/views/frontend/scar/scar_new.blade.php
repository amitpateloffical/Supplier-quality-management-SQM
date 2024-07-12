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

<div class="form-field-head">
    <div class="division-bar">
        <strong>Site Division/Project</strong> :
        {{ Helpers::getDivisionName(session()->get('division')) }} / SCAR 
    </div>
</div>

<div id="change-control-fields">
    <div class="container-fluid">

        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Activity Log</button>
        </div>

        <form action="{{ route('scar-store') }}" method="POST" enctype="multipart/form-data">
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
                            <div class="sub-head">
                                General Information
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="SCAR Record Number"><b>Record Number</b></label>
                                    <input type="text" disabled value="{{ Helpers::getDivisionName(session()->get('division')) }}/SCAR/{{ date('Y') }}/{{ str_pad($record_numbers, 4, '0', STR_PAD_LEFT) }}">

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Division"><b>Division</b></label>
                                    <input disabled type="text" name="division_id" value="{{ Helpers::getDivisionName(session()->get('division')) }}">
                                    <input type="hidden" name="division_id" value="{{ session()->get('division') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Initiator</b></label>
                                    <input disabled type="text" name="initiator_id" id="initiator_id" value="{{ Auth::user()->name }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiation Date"><b>Initiation Date</b></label>
                                    <input disabled type="text" value="{{ date('d-M-Y') }}" name="initiation_date">
                                    <input type="hidden" value="{{ date('Y-m-d') }}" name="initiation_date">
                                </div>
                            </div>
                        
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Due Date">Date Due</label>
                                    <div class="calenderauditee">
                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" name="due_date" readonly placeholder="DD-MM-YYYY" value="{{ $due_date }}"/>
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" value="{{ $due_date }}" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="group-input">
                                    <label for="Assign To">Assigned To <span class="text-danger"></span>
                                    </label>
                                    <select id="assign_to" name="assign_to">
                                        <option value="">Select a value</option>
                                        @if(!empty($users))
                                            @foreach($users as $user)
                                                <option value="{{$user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        @endif                                    
                                    </select>
                                </div>
                            </div>
                       
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
                             
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="SCAR Name">SCAR Name</label>
                                    <input type="text" name="scar_name" placeholder="Enter SCAR Name">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Owner">Owner</label>
                                    <input type="text" name="owner_name" placeholder="Enter Owner Name">
                                </div>
                            </div>


                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Follow Up Date">Follow Up Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="followup_date" placeholder="DD-MM-YYYY" />
                                        <input type="date" name="followup_date" min="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" class="hide-input" oninput="handleDateInput(this, 'followup_date')" />
                                    </div>
                                </div>
                            </div>
                          
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Site">Supplier Site</label>
                                    <select name="supplier_site">
                                        <option value="">Select Supplier Site</option>
                                        @if(!empty($supplierData))
                                            @foreach($supplierData as $supplier)
                                                <option value="{{ $supplier->distribution_sites }}">{{ $supplier->distribution_sites }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Product">Supplier Product</label>
                                    <select name="supplier_product">
                                        <option value="">Select Supplier Product</option>
                                        @if(!empty($supplierData))
                                            @foreach($supplierData as $supplier)
                                                <option value="{{ $supplier->supplier_products }}">{{ $supplier->supplier_products }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Site Contact Email">Supplier Site Contact Email</label>
                                    <input type="text" name="supplier_site_contact_email">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Description">Description</label>
                                   <textarea name="description" id="description" cols="30" ></textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Recommended Action">Recommended Action</label>
                                   <textarea id="recommended_action" cols="30" value="" name="recommended_action"></textarea>
                                </div>
                            </div>

                            <div class="sub-head">
                                Supplier Response
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Non Conformance">Non Conformance</label>
                                    <textarea id="non_conformance" cols="30" value="" name="non_conformance"></textarea>
                                    <!-- <select name="non_conformance">
                                        <option value="">Enter Your Selection Here</option>
                                    </select> -->
                                </div>
                            </div>


                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Expected Closure Date">Expected Closure Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="expected_closure_date" placeholder="DD-MM-YYYY" />
                                        <input type="date" name="expected_closure_date" min="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" class="hide-input" oninput="handleDateInput(this, 'expected_closure_date')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Expected Closure Time">Expected Closure Time</label>
                                   <input type="time" name="expected_closure_time">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Root Cause">Root Cause</label>
                                   <textarea id="root_cause" cols="30" name="root_cause"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Risk Analysis">Risk Analysis</label>
                                   <textarea cols="30" id="risk_analysis" name="risk_analysis"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Effectiveness Check Summary">Effectiveness Check Summary</label>
                                   <textarea cols="30" name="effectiveness_check_summary" id="effectiveness_check_summary"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="CAPA Plan">CAPA Plan</label>
                                   <textarea id="capa_plan" cols="30" name="capa_plan"></textarea>
                                </div>
                            </div>
                           
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit</a></button>
                            </div>
                        </div>
                    </div>
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
                    const index = Array.from(cctablinks).findIndex(button => button === evt.currentTarget);
                    currentStep = index;
                }

                const saveButtons = document.querySelectorAll(".saveButton");
                const nextButtons = document.querySelectorAll(".nextButton");
                const form = document.getElementById("step-form");
                const stepButtons = document.querySelectorAll(".cctablinks");
                const steps = document.querySelectorAll(".cctabcontent");
                let currentStep = 0;

                function nextStep() {
                    if (currentStep < steps.length - 1) {
                        steps[currentStep].style.display = "none";
                        steps[currentStep + 1].style.display = "block";
                        stepButtons[currentStep + 1].classList.add("active");
                        stepButtons[currentStep].classList.remove("active");
                        currentStep++;
                    }
                }

                function previousStep() {
                    if (currentStep > 0) {
                        steps[currentStep].style.display = "none";
                        steps[currentStep - 1].style.display = "block";
                        stepButtons[currentStep - 1].classList.add("active");
                        stepButtons[currentStep].classList.remove("active");
                        currentStep--;
                    }
                }
            </script>

            <script>
                var maxLength = 255;
                $('#docname').keyup(function() {
                    var textlen = maxLength - $(this).val().length;
                    $('#rchars').text(textlen);
                });
            </script>
            @endsection