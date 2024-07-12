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
    <div class="division-bar">
        <strong>Site Division/Project</strong> :
        / Country Sumission Data
    </div>
</div>

{{-- ! ========================================= --}}
{{-- !               DATA FIELDS                 --}}
{{-- ! ========================================= --}}
<div id="change-control-fields">
    <div class="container-fluid">

        <!-- Tab links -->
        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Country Sumission Data</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Country Submission Data</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Important Dates and Persons</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Signatures</button>
        </div>

        <form action="{{ route('actionItem.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div id="step-form">
                @if (!empty($parent_id))
                <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                @endif

                <div id="CCForm1" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Initiator</b></label>
                                    <input disabled type="text" name="Initiator" value="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiation"><b>Date of Initiation</b></label>
                                    <input disabled type="date" name="Date_of_Initiation" value="">
                                    <input type="hidden" name="division_id" value="">
                                </div>
                            </div>

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
                                    <label for="due-date">Date Due</label>
                                    <div><small class="text-primary">Please mention expected date of completion</small></div>
                                    <div class="calenderauditee">
                                        <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                        <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Type">Type</label>
                                    <select multiple id="Type" name="Type[]" id="">
                                        <option value="">--Select---</option>
                                        <option value="">pankaj</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Other Type">Other Type</label>
                                    <input type="text" name="Other_type" id="">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Short Description">Short Description<span class="text-danger">*</span></label><span id="rchars">255</span>
                                    characters remaining
                                    <input id="docname" type="text" name="short_description" maxlength="255" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Attached_Files">Attached Files</label>
                                    <div>
                                        <small class="text-primary">
                                            Please Attach all relevant or supporting documents
                                        </small>
                                    </div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id=""></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Attached_Files" oninput="" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Customer_Name">Related URLs</label>
                                    <select name="Related_URLs">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">Type 1</option>
                                        <option value="2">Type 2</option>
                                        <option value="3">Type 3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Descriptions">Descriptions</label>
                                    <textarea name="Descriptions" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="sub-head">
                                Location
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="zone">Zone</label>
                                    <select name="zone">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Country">Country</label>
                                    <select name="country">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="City">City</label>
                                    <input type="city" name="Reporter">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="State District">State/District</label>
                                    <select name="State_District">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
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
                            <div class="sub-head">
                                Product Information
                            </div>

                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Manufacturer">Manufacturer</label>
                                    <input type="text" name="Manufacturer" id="">
                                </div>
                            </div>

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Product/Material(0)
                                    <button type="button" name="audit-agenda-grid" id="Product_Material_country_sub_data">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="Product-Material_country_sub_data-field-instruction-modal">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Product Name</th>
                                                <th style="width: 16%">Batch Number</th>
                                                <th style="width: 16%">Expiry Date</th>
                                                <th style="width: 16%">Manufactured Date</th>
                                                <th style="width: 15%">Disposition</th>
                                                <th style="width: 15%">Comments</th>
                                                <th style="width: 15%">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>
                                            <td><input type="text" name="ProductName[]"></td>
                                            <td><input type="text" name="BatchNumber[]"></td>
                                            <td><input type="date" name="ExpiryDate"></td>
                                            <td><input type="date" name="ManufacturedDate[]"></td>
                                            <td><input type="text" name="Disposition[]"></td>
                                            <td><input type="text" name="Comments[]"></td>
                                            <td><input type="text" name="Remarks[]"></td>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="sub-head">
                                Country Submission Information
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Number">Number (Id)</label>
                                    <input type="text" name="Number" id="">
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Project Code">Project Code</label>
                                    <input type="text" name="Project_Code" id="">
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Authority Type">Authority Type</label>
                                    <select name="Authority_Type">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Authority">Authority</label>
                                    <select name="Authority">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Priority Level">Priority Level</label>
                                    <select name="Priority_Level">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Other Authority">Other Authority</label>
                                    <input type="text" name="Other_Authority" id="">
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Approval Status">Approval Status</label>
                                    <select name="Approval_Status">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Managed by Company">Managed by Company?</label>
                                    <select name="Managed_by_Company">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Managed by Company">Marketing Status</label>
                                    <select name="Managed_by_Company">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Therapeutic Area">Therapeutic Area</label>
                                    <select name="Therapeutic_Area">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="End of Trial Date Status">End of Trial Date Status</label>
                                    <select name="End_of_Trial_Date_Status">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Protocol Type">Protocol Type</label>
                                    <select name="Protocol_Type">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Registration Status">Registration Status</label>
                                    <select name="Registration_Status">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Protocol Type">Unblinded SUSAR to CEC?</label>
                                    <select name="Protocol_Type">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Trade Name">Trade Name</label>
                                    <select name="Trade_Name">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Dosage Form">Dosage Form</label>
                                    <select name="Dosage_Form">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Photocure Trade Name">Photocure Trade Name</label>
                                    <input type="text" name="Photocure_Trade_Name" id="">
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Currency">Currency</label>
                                    <input type="text" name="Currency" id="">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Attacehed Payments">Attacehed Payments</label>
                                    <div>
                                        <small class="text-primary">
                                            Please Attach all relevant or supporting documents
                                        </small>
                                    </div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id=""></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Attacehed Payments" oninput="" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Follow Up Documents">Follow Up Documents</label>
                                    <select name="Follow_Up_Documents">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Hospitals">Hospitals</label>
                                    <select multiple id="Hospitals" name="" id="">
                                        <option value="">--Select---</option>
                                        <option value="">
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Vendors">Vendors</label>
                                    <select multiple id="Vendors" name="Vendors" id="">
                                        <option value="">--Select---</option>
                                        <option value="">
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="INN">INN(s)</label>
                                    <select multiple id="Route_of_Administration" name="INN" id="">
                                        <option value="">--Select---</option>
                                        <option value="">
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Route of Administration">Route of Administration</label>
                                    <select multiple id="Route_of_Administration" name="" id="">
                                        <option value="">--Select---</option>
                                        <option value="">
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="1st IB Version">1st IB Version</label>
                                    <input type="text" name="1st_IB_Version" id="">
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="1st Protocol Version">1st Protocol Version</label>
                                    <input type="text" name="1st_Protocol_Version" id="">
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="EudraCT Number">EudraCT Number</label>
                                    <input type="text" name="EudraCT_Number" id="">
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Budget">Budget</label>
                                    <input type="text" name="Budget" id="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Phase of Study">Phase of Study</label>
                                    <select multiple id="Phase_of_Study" name="" id="">
                                        <option value="">--Select---</option>
                                        <option value="">
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Related Clinical Trials">Related Clinical Trials</label>
                                    <select name="Related_Clinical_Trials">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Financial Transactions(0)
                                    <button type="button" name="audit-agenda-grid" id="Financial_Transactions_country_sub_data">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="Financial_Transactions_country_sub_data-field-instruction-modal">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Transaction</th>
                                                <th style="width: 16%">Transaction Type</th>
                                                <th style="width: 16%">Date</th>
                                                <th style="width: 16%">Amount</th>
                                                <th style="width: 15%">Currency Used</th>
                                                <th style="width: 15%">Comments</th>
                                                <th style="width: 15%">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>
                                            <td><input type="text" name="Transaction[]"></td>
                                            <td><input type="text" name="TransactionType[]"></td>
                                            <td><input type="date" name="Date[]"></td>
                                            <td><input type="number" name="Amount[]"></td>
                                            <td><input type="text" name="Currency Used[]"></td>
                                            <td><input type="text" name="Comments[]"></td>
                                            <td><input type="text" name="Remarks[]"></td>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="group-input">
                                <label for="audit-agenda-grid">
                                    Ingredients(0)
                                    <button type="button" name="audit-agenda-grid" id="Ingredients_country_sub_data">+</button>
                                    <span class="text-primary" data-bs-toggle="modal" data-bs-target="#observation-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="Ingredients_country_sub_data-field-instruction-modal">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">Row#</th>
                                                <th style="width: 12%">Ingredient Type</th>
                                                <th style="width: 16%">Ingredient Name</th>
                                                <th style="width: 16%">Ingredient Strength</th>
                                                <th style="width: 15%">Comments</th>
                                                <th style="width: 15%">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td><input disabled type="text" name="serial[]" value="1"></td>
                                            <td><input type="text" name="IDnumber[]"></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                            <td><input type="text" name=""></td>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Data Safety Notes">Data Safety Notes</label>
                                    <select name="Data_Safety_Notes">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Comments">Comments</label>
                                    <select name="Comments">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
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
                </div>

                <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Annual IB Update Date Due">Annual IB Update Date Due</label>
                                    <input type="date" name="Annual_IB_Update_Date_Due" id="">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="safety_impact_Severity">Date of 1st IB</label>
                                    <input type="date" name="" id="">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="legal_impact_Probability">Date of 1st Protocol</label>
                                    <input type="date" name="" id="">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="legal_impact_Severity">Date Safety Report</label>
                                    <input type="date" name="" id="">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Business_impact_Probability">Date Trial Active</label>
                                    <input type="date" name="" id="">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Business_impact_Severity">End of Study Report Date</label>
                                    <input type="date" name="" id="">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Revenue_impact_Probability">End of Study Synopsis Date</label>
                                    <input type="date" name="" id="">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Revenue_impact_Severity">End of Trial Date</label>
                                    <input type="date" name="" id="">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Brand_impact_Probability">Last Visit</label>
                                    <input type="date" name="" id="">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Brand_impact_Severity">Next Visit</label>
                                    <input type="date" name="" id="">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Brand_impact_Severity">Ethics Commitee Approval</label>
                                    <input type="date" name="" id="">
                                </div>
                            </div>

                            <div class="sub-head">
                                Persons Involved
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Safety_Impact_Risk">Safety Impact Risk</label>
                                    <div><small class="text-primary">Acceptable- Risks Nigligible, Further Effort not justified; consider product improvement</small></div>
                                    <select name="Safety_Impact_Risk">
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="CROM">CROM</label>
                                    <input type="text" name="CROM" id="" />
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Lead Investigator">Lead Investigator</label>
                                    <input type="text" name="Lead Investigator" id="">

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Sponsor">Sponsor</label>
                                    <input type="text" name="Sponsor" id="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Additional Investigators">Additional Investigators</label>
                                    <select multiple id="Additional_Investigators" name="Additional_Investigators" id="">
                                        <option value="">--Select---</option>
                                        <option value="">
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Clinical Events Committee">Clinical Events Committee</label>
                                    <select multiple id="Clinical_Events_Committee" name="Clinical_Events_Committee" id="">
                                        <option value="">--Select---</option>
                                        <option value="">
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Clinical Research Team">Clinical Research Team</label>
                                    <select multiple id="Clinical_Research_Team" name="Clinical_Research_Team" id="">
                                        <option value="">--Select---</option>
                                        <option value="">
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Data Safety Monitoring Board">Data Safety Monitoring Board</label>
                                    <select multiple id="Data_Safety_Monitoring_Board" name="Data_Safety_Monitoring_Board" id="">
                                        <option value="">--Select---</option>
                                        <option value="">
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Distribution List">Distribution List</label>
                                    <select multiple id="Distribution_List" name="Distribution_List" id="">
                                        <option value="">--Select---</option>
                                        <option value="">
                                        </option>
                                    </select>
                                </div>
                            </div>

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

                <div id="CCForm4" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Closed by">Closed By</label>
                                    <div class="static"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Closed on">Closed On</label>
                                    <div class="Date"></div>
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
    $(document).ready(function() {
        $('#Product_Material_country_sub_data').click(function(e) {
            function generateTableRow(serialNumber) {

                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="ProductName[]"></td>' +
                    '<td><input type="text" name="BatchNumber[]"></td>' +
                    '<td><input type="date" name="ExpiryDate[]"></td>' +
                    '<td><input type="date" name="ManufacturedDate[]"></td>' +
                    '<td><input type="text" name="Disposition[]"></td>' +
                    '<td><input type="text" name="Comments[]"></td>' +
                    '<td><input type="text" name="Remarks[]"></td>' +
                    '</tr>';

                return html;
            }

            var tableBody = $('#Product-Material_country_sub_data-field-instruction-modal tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#Financial_Transactions_country_sub_data').click(function(e) {
            function generateTableRow(serialNumber) {

                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="Transaction[]"></td>' +
                    '<td><input type="text" name="TransactionType[]"></td>' +
                    '<td><input type="date" name="Date[]"></td>' +
                    '<td><input type="number" name="Amount[]"></td>' +
                    '<td><input type="text" name="CurrencyUsed[]"></td>' +
                    '<td><input type="text" name="Comments[]"></td>' +
                    '<td><input type="text" name="Remarks[]"></td>' +
                    '</tr>';

                return html;
            }

            var tableBody = $('#Financial_Transactions_country_sub_data-field-instruction-modal tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#Ingredients_country_sub_data').click(function(e) {
            function generateTableRow(serialNumber) {

                var html =
                '<tr>' +
                    '<td><input disabled type="text" name="serial[]" value="' + serialNumber + '"></td>' +
                    '<td><input type="text" name="IngredientType[]"></td>' +
                    '<td><input type="text" name="IngredientName[]"></td>' +
                    '<td><input type="text" name="IngredientStrength[]"></td>' +
                    '<td><input type="text" name="Comments[]"></td>' +

                    '<td><input type="text" name="Remarks[]"></td>' +
                    '</tr>';

                return html;
            }

            var tableBody = $('#Ingredients_country_sub_data-field-instruction-modal tbody');
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