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
                                        <label for="Short Description">Short Description<span>class="text-danger">*</span>
                                        </label><span id="rchars">255</span> characters remaining

                                        <div class="relative-container">
                                            <input id="docname" class="mic-input" type="text" name="short_description" maxlength="255" required>
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="SCAR Name">SCAR Name</label>
                                        <div class="relative-container">
                                            <input id="docname" class="mic-input" type="text" name="scar_name" maxlength="255">
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Owner">Owner</label>
                                        <div class="relative-container">
                                            <input id="docname" class="mic-input" type="text" name="owner_name" maxlength="255">
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
                                            <option value="">Select Supplier Site</option>
                                            <option value="Quality Assurance-CQA">Global Supply</option>
                                            <option value="Research and development"> Essential Supplies.</option>
                                            <option value="Regulatory Science">Supply Savvy</option>
                                            <option value="Regulatory Science">Trusted Traders</option>
                                            <option value="Regulatory Science"></option>
                                            @if (!empty($distributionSites))
                                                @foreach ($distributionSites as $supplier)
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
                                            <option value="Quality Assurance-CQA">All In One Supply Warehouse</option>
                                            <option value="Research and development">Boundless Supply</option>
                                            <option value="Regulatory Science">Multiproduct Solutions</option>
                                            <option value="Quality Assurance-CQA">Supply Central</option>
                                            <option value="Research and development">Supply Empire</option>
                                            <option value="Regulatory Science">Fresh Supplies</option>
                                            @if (!empty($supplierProduct))
                                                @foreach ($supplierProduct as $supplier)
                                                    <option value="{{ $supplier->supplier_products }}"> {{ $supplier->supplier_products }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">

                                        <label for="Supplier Site Contact Email">Supplier Site Contact Email</label>
                                        <div class="relative-container">
                                            <input id="docname" class="mic-input" type="text" name="supplier_site_contact_email" maxlength="255">
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                        {{-- <div style="position: relative;">
                                            <input type="text" name="supplier_site_contact_email" class="mic-input">
                                            <button class="mic-btn"type="button">
                                                <i class="fas fa-microphone"></i>
                                            </button>
                                        </div> --}}
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Description">Description</label>
                                        <div class="relative-container">
                                            <input id="docname" class="mic-input" type="text" name="description" maxlength="255" >
                                            @component('frontend.forms.language-model')
                                            @endcomponent
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="group-input">
                                            <label for="Recommended Action">Recommended Action</label>
                                            <div class="relative-container">
                                                <input id="docname" class="mic-input" type="text" name="recommended_action" maxlength="255">
                                                @component('frontend.forms.language-model')
                                                @endcomponent
                                            </div>
                                        </div>

                                        <div class="sub-head"> Supplier Response</div>
                                        <div class="col-lg-6">
                                            <div class="group-input">

                                                <label for="Non Conformance">Non Conformance</label>
                                                <div class="relative-container">
                                                    <input id="docname" class="mic-input" type="text" name="non_conformance" maxlength="255">
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6 new-date-data-field">
                                            <div class="group-input input-date">
                                                <label for="Expected Closure Date">Expected Closure Date</label>
                                                <div class="calenderauditee">
                                                    <input type="text" id="expected_closure_date"
                                                        placeholder="DD-MM-YYYY" />
                                                    <input type="date" name="expected_closure_date"
                                                        max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                        class="hide-input"
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
                                                    <input id="docname" class="mic-input" type="text" name="root_cause"  maxlength="255">
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="group-input">
                                            <label for="Risk Analysis">Risk Analysis</label>
                                            <div class="relative-container">
                                                <input id="docname" class="mic-input" type="text" name="risk_analysis" maxlength="255" >
                                                @component('frontend.forms.language-model')
                                                @endcomponent
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="group-input">
                                            <label for="Effectiveness Check Summary">Effectiveness Check Summary</label>
                                            <div class="relative-container">
                                                <input id="docname" class="mic-input" type="text" name="effectiveness_check_summary" maxlength="255">
                                                @component('frontend.forms.language-model')
                                                @endcomponent
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="group-input"style="position: relative;">
                                            {{-- <div style="position: relative;"> --}}
                                            <label for="CAPA Plan">CAPA Plan</label>
                                            <div class="relative-container">
                                                <input id="docname" class="mic-input" type="text" name="capa_plan" maxlength="255">
                                                @component('frontend.forms.language-model')
                                                @endcomponent
                                            </div>

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
                <div id="CCForm2" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Submitted By">Submitted By</label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Submitted On">Submitted On</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Submitted Comment">Submitted Comment</label>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Acknowledge By</label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Acknowledge On</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Acknowledge Comment</label>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Work in Progress By</label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Work in Progress On</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Work in Progress Comment</label>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Response Submitted By</label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Response Submitted On</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Response Submitted Comment</label>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Rejected By</label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Rejected On</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Rejected Comment</label>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review By">Approved By</label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="group-input">
                                    <label for="Suppplier Review On">Approved On</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Suppplier Review Comment">Approved Comment</label>
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

                {{-- <style>
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
                </style> --}}

                {{-- <script>
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
 --}}

                    <script >
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
                </>

                <script>
                    var maxLength = 255;
                    $('#docname').keyup(function() {
                        var textlen = maxLength - $(this).val().length;
                        $('#rchars').text(textlen);
                    });
                </script>
            @endsection
