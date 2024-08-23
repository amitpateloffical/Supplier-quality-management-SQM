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

            <form class="formSubmit" action="{{ route('scar-store') }}" method="POST" enctype="multipart/form-data">
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
                                        <input type="text" disabled
                                            value="{{ Helpers::getDivisionName(session()->get('division')) }}/SCAR/{{ date('Y') }}/{{ str_pad($record_number, 4, '0', STR_PAD_LEFT) }}">

                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division"><b>Division</b></label>
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
                                        <label for="Initiation Date"><b>Initiation Date</b></label>
                                        <input disabled type="text" value="{{ date('d-M-Y') }}" name="initiation_date">
                                        <input type="hidden" value="{{ date('d-M-Y') }}" name="initiation_date">
                                    </div>
                                </div>

                                <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Due Date">Date Due</label>
                                        <div class="calenderauditee">
                                            <div class="calenderauditee">
                                                <input type="text" id="due_date" name="due_date" readonly
                                                    placeholder="DD-MM-YYYY" value="{{ $due_date }}" />
                                                <input type="date" name="due_date"
                                                    min="{{ \Carbon\Carbon::now()->format('d-M-Y') }}"
                                                    value="{{ $due_date }}" class="hide-input"
                                                    oninput="handleDateInput(this, 'due_date')" />
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
                                            @if (!empty($users))
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description">Short Description<span
                                                class="text-danger">*</span></label><span
                                            id="rchars">255</span>characters remaining
                                        <div class="relative-container">
                                            <input id="docname" class="mic-input" type="text" name="short_description"
                                                maxlength="255" required>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="SCAR Name">SCAR Name</label>
                                        <div class="relative-container">
                                            <input type="text" name="scar_name" class="mic-input"
                                                placeholder="Enter SCAR Name">
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>

                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Owner">Owner Name </label>
                                        <div class="relative-container">
                                            <input type="text" name="owner_name"
                                                placeholder="Enter Owner Name"class="mic-input">
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>

                                    </div>
                                </div>


                                <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Follow Up Date">Follow Up Date</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="followup_date" placeholder="DD-MM-YYYY" />
                                            <input type="date" name="followup_date"
                                                max="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'followup_date')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Supplier Site">Supplier Site</label>
                                        <select name="supplier_site">
                                            <option value="">Enter your selection</option>
                                            <option value="supplier-site-1">Supplier Site-1</option>
                                            <option value="supplier-site-2">Supplier Site-2</option>
                                            <option value="supplier-site-3">Supplier Site-3</option>
                                            <option value="supplier-site-4">Supplier Site-4</option>
                                            <option value="supplier-site-5">Supplier Site-5</option>

                                            {{-- @if (!empty($distributionSites))
                                                @foreach ($distributionSites as $supplier)
                                                    <option value="{{ $supplier->distribution_sites }}">
                                                        {{ $supplier->distribution_sites }}</option>
                                                @endforeach
                                            @endif --}}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Supplier Product">Supplier Product</label>
                                        <select name="supplier_product">
                                            <option value="">Enter your selection</option>
                                            <option value="supplier-product-1">Supplier Product-1</option>
                                            <option value="supplier-product-2">Supplier Product-2</option>
                                            <option value="supplier-product-3">Supplier Product-3</option>
                                            <option value="supplier-product-4">Supplier Product-4</option>
                                            <option value="supplier-product-5">Supplier Product-5</option>

                                            {{-- @if (!empty($supplierProduct))
                                                @foreach ($supplierProduct as $supplier)
                                                    <option value="{{ $supplier->supplier_products }}">
                                                        {{ $supplier->supplier_products }}</option>
                                                @endforeach
                                            @endif --}}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">

                                        <label for="Supplier Site Contact Email">Supplier Site Contact Email</label>
                                        <div class="relative-container">
                                            <input type="text" name="supplier_site_contact_email" class="mic-input">
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Description">Description</label>
                                        <div class="relative-container">
                                            <textarea name="description" id="description" cols="30" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Recommended Action">Recommended Action</label>
                                        <div class="relative-container">
                                            <textarea id="recommended_action" cols="30" value="" name="recommended_action"class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>

                                    </div>
                                </div>
                                <div class="sub-head">
                                    Supplier Response
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">

                                        <label for="Non Conformance">Non Conformance</label>
                                        <div class="relative-container">
                                            <textarea id="non_conformance" cols="30" value="" name="non_conformance"class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>

                                    </div>
                                </div>


                                <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Expected Closure Date">Expected Closure Date</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="expected_closure_date" placeholder="DD-MM-YYYY" />
                                            <input type="date" name="expected_closure_date"
                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'expected_closure_date')" />
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
                                        <div class="relative-container">
                                            <textarea id="root_cause" cols="30" name="root_cause" class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>

                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Risk Analysis">Risk Analysis</label>
                                        <div class="relative-container">
                                            <textarea cols="30" id="risk_analysis" name="risk_analysis"class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Effectiveness Check Summary">Effectiveness Check Summary</label>
                                        <div class="relative-container">
                                            <textarea cols="30" name="effectiveness_check_summary" id="effectiveness_check_summary"class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="CAPA Plan">CAPA Plan</label>
                                        <div class="relative-container">
                                            <textarea id="capa_plan" cols="30" name="capa_plan"class="mic-input"></textarea>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="button-block">
                                <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white"
                                        href="{{ url('rcms/qms-dashboard') }}">Exit</a></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="CCForm2" class="inner-block cctabcontent">
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
                                    <label for="Suppplier Review By">Acknowledge By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Acknowledge On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Acknowledge Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Work in Progress By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Work in Progress On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Work in Progress Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Response Submitted By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Response Submitted On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Response Submitted Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Rejected By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Rejected On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Rejected Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Approved By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Approved On</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Approved Comment</label>
                                    <div class="static"></div>
                                </div>
                            </div>

                            <div class="button-block">
                                {{-- <button type="submit" class="saveButton on-submit-disable-button">Save</button> --}}
                                <button type="button" class="nextButton" onclick="previousStep()">Back</button>
                                <button type="button"> <a class="text-white"
                                        href="{{ url('rcms/qms-dashboard') }}">Exit</a></button>
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
    <script>
        $(document).ready(function() {

            $('.formSubmit').on('submit', function(e) {
                $('.on-submit-disable-button').prop('disabled', true);
            });
        })
    </script>
@endsection
