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
    <div class="pr-id">
        New Document
    </div>
    <div class="division-bar">
        <strong>Site Division/Project</strong> :
        QMS-North America / CAPA
    </div>
    <!-- <div class="button-bar">
            <button type="button">Save</button>
            <button type="button">Cancel</button>
            <button type="button">New</button>
            <button type="button">Copy</button>
            <button type="button">Child</button>
            <button type="button">Check Spelling</button>
            <button type="button">Change Project</button>
        </div> -->
</div>



{{-- ======================================
                    DATA FIELDS
    ======================================= --}}
<div id="change-control-fields">
    <div class="container-fluid">

        <!-- Tab links -->
        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Environmental Task </button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Natural Reources Consumption</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Water Consumption</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Emission to water</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Emission to Air</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Chemical waste</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Solid waste</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm8')">Energy Consumption</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm9')">Recycling</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm10')">External Complaints</button>
            <button class="cctablinks" onclick="openCity(event, 'CCForm11')"> signatures</button>
            <!-- <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Activity Log</button> -->
        </div>

        <!-- Environmental Task -->

        <div id="CCForm1" class="inner-block cctabcontent">
            <div class="inner-block-content">

                <div class="sub-head">Environmental Task</div>
                <div class="row">
                    <!-- <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Division Code"> Division Code </label>
                                <div class=" static">CRS</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Initiator"> Initiator </label>
                                <div class=" static">Shaleen Mishra</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Date Due"> Date of Initiator </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div> -->
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group"> Originator </label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option></option>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group Code"> Date Opened </label>

                            <input type="date" id="date" name="date-time">

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Short Description"> Short Description </label>
                            <div class="text-primary">Envirmental Event short discruption to be presented desktop</div>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <!-- <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Assigned to"> Assigned to </label>
                                <div class=" static">Shaleen Mishra</div>
                            </div>
                        </div> -->
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group">Assigned To</label>
                            <div class="text-primary"> Person Responsible</div>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>sandhya</option>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group Code">Due Date </label>
                            <div class="text-primary">6 Last date this Task should be closed by</div>

                            <input type="date" id="date" name="date-time">

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group Code">Task Start Date </label>

                            <input type="date" id="date" name="date-time">

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group Code">Task End Date </label>

                            <input type="date" id="date" name="date-time">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group">Site</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>s</option>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group">Zone</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>sandhya</option>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group">Country</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>Bhart</option>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group">City</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>sagar</option>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group">Sate/District</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>Madhya Pradesh</option>
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <div class="sub-head pt-3">Additional Information</div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <div class="text-primary">Task detailed Information</div>
                            <label for="Short Description"> Short Description </label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input pt-4">
                            <label for="Short Description "> Immediate Action </label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Short Description"> Comments </label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group">Attached Document(s)</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>sagar</option>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group">Related URLs</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>.com</option>
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="button-block">
                    <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                    <button type="button" id="ChangeNextButton" class="nextButton" onclick="nextStep()">Next</button>
                    <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                            Exit </a> </button>
                </div>
            </div>

        </div>

        <!-- Natural Reources  Consumption -->
        <!-- <div id="CCForm2" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Schedule Start Date"> Audit Schedule Start Date </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Schedule End Date"> Audit Schedule End Date </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Agenda"> Audit Agenda </label>
                                <input type="file" id="myfile" name="myfile">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Facility Name"> Facility Name </label>
                                <select multiple name="facility_name" placeholder="Select Nature of Deviation"
                                    data-search="false" data-silent-initial-value-set="true" id="facility_name">
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Group Name"> Group Name </label>
                                <select multiple name="group_name" placeholder="Select Nature of Deviation"
                                    data-search="false" data-silent-initial-value-set="true" id="group_name">
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Product/Material Name"> Product/Material Name </label>
                                <input type="text" name="title">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Comments(If Any)"> Comments(If Any) </label>
                                <textarea name="text"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

        <!-- Water Consumption -->
        <div id="CCForm3" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">Water Comsumption</div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="group-input">

                            <label for="Short Description">Water Comsumption</label>
                            <div class="text-primary">Water that consumed by employee</div>
                            <input type="text">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Lead Auditor">Process Water Units</label>
                            <div class="text-primary">Please Choose the relevent units</div>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>Madhya Pradesh</option>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Team"> Number Of Yearly Working Days</label>
                            <input type="text">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Team">Water Userd Daily Per Employee</label>
                            <input type="text">

                            <!-- <label for="Auditee"> Auditee </label>
                                <select multiple name="auditee" placeholder="Select Nature of Deviation"
                                    data-search="false" data-silent-initial-value-set="true" id="auditee">
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                    <option value="Piyush">Piyush Sahu</option>
                                </select> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Team">Irrigation Water</label>
                            <input type="text">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Team">Irrigation Water Unit</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>8.0</option>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Team">Other Water Consumption Factor</label>
                            <input type="text">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Team">Other Water Conssumption Amount</label>
                            <input type="text">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Team">Other Water Consumption Unit</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>8.0</option>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Team">Total Water Consumption</label>
                            <input type="text">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Team">Potable Water</label>
                            <input type="text">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Team">Surface</label>
                            <input type="text">
                        </div>

                    </div>

                    <div class="col-12">
                        <div class="group-input">
                            <label for="Audit Team">Ground Water</label>
                            <input type="text">

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Audit Team">Water Consumption Comments</label>
                            <textarea name="text"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Team"> Water Attached Document(s)</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>8.0</option>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="button-block">
                        <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" id="ChangeNextButton" class="nextButton" onclick="nextStep()">Next</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                Exit </a> </button>
                    </div>
                </div>
            </div>

            <!-- Audit Execution content -->
            <div id="CCForm4" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Due Date"> Due Date </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Start Date"> Audit Star Date </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit End Date"> Audit End Date </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Attachments"> Audit Attachments </label>
                                <input type="file" id="myfile" name="myfile">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Audit Comments"> Audit Comments </label>
                                <textarea name="text"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit Response & Closure content -->
            <div id="CCForm5" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="sub-head">
                        Audit Response
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Remarks"> Remarks </label>
                                <textarea name="text"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Report Attachments"> Report Attachments </label>
                                <input type="file" id="myfile" name="myfile">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="group-input">
                                <label for="Reference Recores"> Reference Recores </label>
                                <textarea name="text"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="sub-head">
                        Audit Closure
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reference Recores"> Reference Recores </label>
                                <input type="text" id="myfile" name="Text">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Attachments"> Audit Attachments </label>
                                <input type="file" id="myfile" name="myfile">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="Audit Comments"> Audit Comments </label>
                                <textarea name="text"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Log content -->
            <div id="CCForm6" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Schedule On"> Audit Schedule By </label>
                                <div class=" static">Person datafield</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Schedule On"> Audit Schedule On </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Cancelled By"> Cancelled By </label>
                                <div class=" static">Person datafield</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Cancelled On"> Cancelled On </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Preparation Completed On"> Audit Preparation Completed By </label>
                                <div class=" static">Person datafield</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Preparation Completed On"> Audit Preparation Completed On </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Mgr.more Info Reqd By"> Audit Mgr.more Info Reqd By </label>
                                <div class=" static">Person datafield</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Mgr.more Info Reqd On"> Audit Mgr.more Info Reqd On </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Observation Submitted By"> Audit Observation Submitted By </label>
                                <div class=" static">Person datafield</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Observation Submitted On"> Audit Observation Submitted On </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Lead More Info Reqd By"> Audit Lead More Info Reqd By </label>
                                <div class=" static">Person datafield</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Lead More Info Reqd On"> Audit Lead More Info Reqd On </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Response Completed By"> Audit Response Completed By </label>
                                <div class=" static">Person datafield</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Response Completed On"> Audit Response Completed On </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Response Feedback Verified By"> Response Feedback Verified By </label>
                                <div class=" static">Person datafield</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Response Feedback Verified On"> Response Feedback Verified On </label>
                                <div class=" static">17-04-2023 11:12PM</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

    <script>
        VirtualSelect.init({
            ele: '#facility_name, #group_name, #auditee, #audit_team'
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
    </script>
    @endsection