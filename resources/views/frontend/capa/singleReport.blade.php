<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connexo - Software</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .w-10 {
        width: 10%;
    }

    .w-20 {
        width: 20%;
    }

    .w-25 {
        width: 25%;
    }

    .w-30 {
        width: 30%;
    }

    .w-40 {
        width: 40%;
    }

    .w-50 {
        width: 50%;
    }

    .w-60 {
        width: 60%;
    }

    .w-70 {
        width: 70%;
    }

    .w-80 {
        width: 80%;
    }

    .w-90 {
        width: 90%;
    }

    .w-100 {
        width: 100%;
    }

    .h-100 {
        height: 100%;
    }

    header table,
    header th,
    header td,
    footer table,
    footer th,
    footer td,
    .border-table table,
    .border-table th,
    .border-table td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    table {
        width: 100%;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    footer .head,
    header .head {
        text-align: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    @page {
        size: A4;
        margin-top: 160px;
        margin-bottom: 60px;
    }

    header {
        position: fixed;
        top: -140px;
        left: 0;
        width: 100%;
        display: block;
    }

    footer {
        width: 100%;
        position: fixed;
        display: block;
        bottom: -40px;
        left: 0;
        font-size: 0.9rem;
    }

    footer td {
        text-align: center;
    }

    .inner-block {
        padding: 10px;
    }

    .inner-block tr {
        font-size: 0.8rem;
    }

    .inner-block .block {
        margin-bottom: 30px;
    }

    .inner-block .block-head {
        font-weight: bold;
        font-size: 1.1rem;
        padding-bottom: 5px;
        border-bottom: 2px solid #4274da;
        margin-bottom: 10px;
        color: #4274da;
    }

    .inner-block th,
    .inner-block td {
        vertical-align: baseline;
    }

    .table_bg {
        background: #4274da57;
    }
</style>

<body>

    <header>
        <table>
            <tr>
                <td class="w-70 head">
                   CAPA Single Report
                </td>
                <td class="w-30">
                    <div class="logo">
                        <img src="https://www.connexo.io/assets/img/logo/logo.png" alt="" class="w-100">
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="w-30">
                    <strong>CAPA No.</strong>
                </td>
                <td class="w-40">
                   {{ Helpers::divisionNameForQMS($data->division_id) }}/CAPA/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="w-30">
                    <strong>Record No.</strong> {{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
        </table>
    </header>

    <div class="inner-block">
        <div class="content-table">
            <div class="block">
                <div class="block-head">
                    General Information
                </div>
                <table>

                    <tr>  {{ $data->created_at }} added by {{ $data->originator }}
                        <th class="w-20">Initiator</th>
                        <td class="w-30">{{ $data->originator }}</td>
                        <th class="w-20">Date of Initiation</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->created_at) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Record Number</th>
                        <td class="w-30">@if($data->record){{ Helpers::divisionNameForQMS($data->division_id) }}/CAPA/{{ Helpers::year($data->created_at) }}/{{  str_pad($data->record, 4, '0', STR_PAD_LEFT) }} @else Not Applicable @endif</td>
                        <th class="w-20">Site/Location Code</th>
                        <td class="w-30">@if($data->division_id){{ Helpers::getDivisionName($data->division_id) }} @else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Initiator Group</th>
                        <td class="w-30">@if($data->initiator_Group){{ Helpers::getInitiatorGroupFullName($data->initiator_Group) }} @else Not Applicable @endif</td>
                        {{-- <td class="w-30">@if($data->initiator_Group){{ $data->initiator_Group }} @else Not Applicable @endif</td> --}}
                        <th class="w-20">Initiator Group Code</th>
                        <td class="w-30">  
                        @if ($data->initiator_group_code)
                            {{ $data->initiator_group_code }}
                        @else
                            Not Applicable
                        @endif</td>

                     </tr>
                </table>

           

           
               <div class = "inner-block" >
                   <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">Short Description</label>
               <span style="font-size:0.8rem; margin-left:10px">@if($data->short_description ){{ $data->short_description  }} @else Not Applicable @endif</span>
               </div>
           
           
                <table>
                    <tr>
                        <th class="w-20">Severity Level</th>
                        <td class="w-30">@if($data->severity_level_form){{ $data->severity_level_form }}@else Not Applicable @endif</td> 
                        <th class="w-20">Initiated Through</th>
                        <td class="w-30">@if($data->initiated_through){{ $data->initiated_through }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Assigned To</th>
                        <td class="w-30">@if($data->assign_to){{ Helpers::getInitiatorName($data->assign_to) }} @else Not Applicable @endif</td>
                        <th class="w-20">Due Date</th>
                        <td class="w-30"> @if($data->due_date){{ $data->due_date }} @else Not Applicable @endif</td>

                    </tr>
                    <tr>
                        <th class="w-20">Repeat</th>
                        <td class="w-80"> @if($data->repeat_nature_textarea){{ $data->repeat_nature_textarea }} @else Not Applicable @endif</td>
                    </tr>
                </table>
                <div class = "inner-block" >
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">Others</label>
                    <span style="font-size:0.8rem; margin-left:10px">@if($data->initiated_through_req ){{ $data->initiated_through_req  }} @else Not Applicable @endif</span>
                </div>
              
                <div class = "inner-block">
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">Repeat Nature</label>
                <span style="font-size:0.8rem; margin-left:10px">@if($data->repeat_nature ){{ $data->repeat_nature  }} @else Not Applicable @endif</span>
                </div>
            
                <div class = "inner-block">
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">Problem Description</label>
                <span style="font-size:0.8rem; margin-left:10px">@if($data->problem_description ){{ $data->problem_description  }} @else Not Applicable @endif</span>
                </div>

                
                  <table>  
                     <tr>
                        <th class="w-20 pt-1">CAPA Team</th>
                        <td class="w-30">
                            @if($data->capa_team)
                                @foreach (explode(',', $data->capa_team) as $Key => $value)
                                    <li>{{ Helpers::getInitiatorName($value) }}</li>
                                @endforeach
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class="w-20">Reference Record</th>
                        <td class="w-80">
                            @if ($data->capa_related_record)
                                {{ $data->capa_related_record }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <footer>
                        <table>
                            <tr>
                                <td class="w-30">
                                    <strong>Printed On :</strong> {{ date('d-M-Y') }}
                                </td>
                                <td class="w-40">
                                    <strong>Printed By :</strong> {{ Auth::user()->name }}
                                </td>
                                {{-- <td class="w-30">
                                    <strong>Page :</strong> 1 of 1
                                </td> --}}
                            </tr>
                        </table>
                    </footer>
                    
                    
                    <tr >
                        <th class="w-20">Interim Containnment</th>
                        <td class="w-30">@if($data->interim_containnment){{ $data->interim_containnment }}@else Not Applicable @endif</td>
                    </tr>
                </table>  

                <div class = "inner-block">
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">Initial Observation</label>
                <span style="font-size:0.8rem; margin-left:10px">@if($data->initial_observation ){{ $data->initial_observation  }} @else Not Applicable @endif</span>
                </div>

                <div class = "inner-block">
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">Containment Comments</label>
                <span style="font-size:0.8rem; margin-left:10px">@if($data->containment_comments ){{ $data->containment_comments  }} @else Not Applicable @endif</span>
                </div>

                <div class = "inner-block">
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">Comments</label>
                <span style="font-size:0.8rem; margin-left:10px">@if($data->capa_qa_comments ){{ $data->capa_qa_comments  }} @else Not Applicable @endif</span>
                </div>
                
            </div>
            <footer>
                <table>
                    <tr>
                        <td class="w-30">
                            <strong>Printed On :</strong> {{ date('d-M-Y') }}
                        </td>
                        <td class="w-40">
                            <strong>Printed By :</strong> {{ Auth::user()->name }}
                        </td>
                        {{-- <td class="w-30">
                            <strong>Page :</strong> 1 of 1
                        </td> --}}
                    </tr>
                </table>
            </footer>
            
            <div class="block-head">
                CAPA Attachement
             </div>
               <div class="border-table">
                 <table>
                     <tr class="table_bg">
                         <th class="w-20">Sr no..</th>
                         <th class="w-60">File </th>
                     </tr>
                         @if($data->capa_attachment)
                         @foreach(json_decode($data->capa_attachment) as $key => $file)
                             <tr>
                                 <td class="w-20">{{ $key + 1 }}</td>
                                 <td class="w-20"><a href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a> </td>
                             </tr>
                         @endforeach
                         @else
                         <tr>
                             <td class="w-20">1</td>
                             <td class="w-20">Not Applicable</td>
                         </tr>
                     @endif

                 </table>
               </div>
           
            {{-- <div class="block">
                <div class="block-head">
                    Product Details
                </div>
                <div class="border-table">
                    <table>
                        <tr class="table_bg">
                            <th class="w-20">Sr no.</th>
                            <th class="w-5">Product Name</th>
                            <th class="w-5">Batch No</th>
                            <th class="w-15">Manu- facturing Date</th>
                            <th class="w-15">Date Of Expiry</th>
                            <th class="w-15">Batch Disposition Decision</th>
                            <th class="w-15">Remark</th>
                            <th class="w-15">Batch Status</th>
                        </tr>
                        @if($data->Product_Details->product_name)
                        @foreach (unserialize($data->Product_Details->product_name) as $key => $dataDemo)
                        <tr>
                            <td class="w-15">{{ $dataDemo ? $key + 1  : "Not Applicable" }}</td>
                            <td class="w-5">{{unserialize($data->Product_Details->product_name)[$key] ?  unserialize($data->Product_Details->product_name)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Product_Details->batch_no)[$key] ?  unserialize($data->Product_Details->batch_no)[$key] : "Not Applicable" }}</td>
                            <td class="w-5">{{unserialize($data->Product_Details->mfg_date)[$key] ?  unserialize($data->Product_Details->mfg_date)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Product_Details->expiry_date)[$key] ?  unserialize($data->Product_Details->expiry_date)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Product_Details->batch_desposition)[$key] ?  unserialize($data->Product_Details->batch_desposition)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Product_Details->remark)[$key] ?  unserialize($data->Product_Details->remark)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Product_Details->batch_status)[$key] ?  unserialize($data->Product_Details->batch_status)[$key] : "Not Applicable" }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td>1</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div> --}}
            <div class="block">
                <div class="block-head">
                    Product Details
                </div>
                <div class="border-table">
                    <table>
                        <tr class="table_bg">
                            <th class="w-20">Sr no.</th>
                            <th class="w-20">Product Name</th>
                            <th class="w-20">Batch No</th>
                            <th class="w-20">Manu- facturing Date</th>
                            <th class="w-20">Date Of Expiry</th>
                            <th class="w-20">Batch Disposition Decision</th>
                            <th class="w-20">Remark</th>
                            <th class="w-20">Batch Status</th>
                        </tr>
                        @if($data->Product_Details->product_name)
                        @foreach (unserialize($data->Product_Details->product_name) as $key => $dataDemo)
                        <tr>
                            <td class="w-15">{{$dataDemo ? $key + 1  : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Product_Details->product_name)[$key] ?  unserialize($data->Product_Details->product_name)[$key]: "Not Applicable"}}</td>
                            <td class="w-15">{{unserialize($data->Product_Details->batch_no)[$key] ?  unserialize($data->Product_Details->batch_no)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Product_Details->mfg_date)[$key] ?  unserialize($data->Product_Details->mfg_date)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Product_Details->expiry_date)[$key] ?  unserialize($data->Product_Details->expiry_date)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Product_Details->batch_desposition)[$key] ?  unserialize($data->Product_Details->batch_desposition)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Product_Details->remark)[$key] ?  unserialize($data->Product_Details->remark)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Product_Details->batch_status)[$key] ?  unserialize($data->Product_Details->batch_status)[$key] : "Not Applicable" }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td>1</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div> 
            <div class="block">
                <div class="block-head">
                    Material Details
                </div>
                <div class="border-table">
                    <table>
                        <tr class="table_bg">
                            <th class="w-20">Sr no.</th>
                            <th class="w-20">Material Name</th>
                            <th class="w-20">Batch Number</th>
                            <th class="w-20">Manu- facturing Date</th>
                            <th class="w-20">Date Of Expiry</th>
                            <th class="w-20">Batch Disposition</th>
                            <th class="w-20">Remark</th>
                            <th class="w-20">Batch Status</th>
                        </tr>
                        @if($data->Material_Details->material_name)
                        @foreach (unserialize($data->Material_Details->material_name) as $key => $dataDemo)
                        <tr>
                            <td class="w-15">{{ $dataDemo ? $key + 1  : "Not Applicable" }}</td>
                            <td class="w-15">{{ unserialize($data->Material_Details->material_name)[$key] ?  unserialize($data->Material_Details->material_name)[$key]: "Not Applicable"}}</td>
                            <td class="w-15">{{unserialize($data->Material_Details->material_batch_no)[$key] ?  unserialize($data->Material_Details->material_batch_no)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Material_Details->material_mfg_date)[$key] ?  unserialize($data->Material_Details->material_mfg_date)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Material_Details->material_expiry_date)[$key] ?  unserialize($data->Material_Details->material_expiry_date)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Material_Details->material_batch_desposition)[$key] ?  unserialize($data->Material_Details->material_batch_desposition)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Material_Details->material_remark)[$key] ?  unserialize($data->Material_Details->material_remark)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Material_Details->material_batch_status)[$key] ?  unserialize($data->Material_Details->material_batch_status)[$key] : "Not Applicable" }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td>1</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            <div class="block">
                <div class="block-head">
                    Equipment/Instruments Details
                </div>
                <div class="border-table">
                    <table>
                        <tr class="table_bg">
                            <th class="w-25">Sr no.</th>
                            <th class="w-25">Equipment/Instruments Name</th>
                            <th class="w-25">Equipment/Instruments ID</th>
                            <th class="w-25">Equipment/Instruments Comments</th>
                        </tr>
                        @if($data->Instruments_Details->equipment)
                        @foreach (unserialize($data->Instruments_Details->equipment) as $key => $dataDemo)
                        <tr>
                            <td class="w-15">{{ $dataDemo ? $key +1  : "Not Applicable" }}</td>

                            <td class="w-15">{{ $dataDemo ? $dataDemo : "Not Applicable"}}</td>
                            <td class="w-15">{{unserialize($data->Instruments_Details->equipment_instruments)[$key] ?  unserialize($data->Instruments_Details->equipment_instruments)[$key] : "Not Applicable" }}</td>
                            <td class="w-15">{{unserialize($data->Instruments_Details->equipment_comments)[$key] ?  unserialize($data->Instruments_Details->equipment_comments)[$key] : "Not Applicable" }}</td>

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td>1</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>

                        @endif
                    </table>
                </div>
            </div>


            <!-- <div class="block">
                <div class="head">
                    <div class="block-head">
                        Other type CAPA Details
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">Details</th>
                            <td class="w-80">@if($data->details){{ $data->details }}@else Not Applicable @endif</td>
                        </tr>

                         <tr>
                            <th class="w-20">Project Datails Application</th>
                            <td class="w-80"> {{ $data->project_details_application }}</td>
                        </tr> -->
                        <!--s<tr>
                            <th class="w-20">Initiator Group</th>
                            <td class="w-80"> {{ $data->project_initiator_group }}</td>
                        </tr>
                        <tr>
                            <th class="w-20">Site Number</th>
                            <td class="w-80">@if($data->site_number){{ $data->site_number }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Subject Number</th>
                            <td class="w-80">@if($data->subject_number){{ $data->subject_number }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Subject Initials</th>
                            <td class="w-80">@if($data->subject_initials){{ $data->subject_initials }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Sponsor</th>
                            <td class="w-80">@if($data->sponsor){{ $data->sponsor }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">General Deviation</th>
                            <td class="w-80">@if($data->general_deviation){{ $data->general_deviation }}@else Not Applicable @endif</td>
                        </tr> -->
                    {{-- </table> --}}
                {{-- </div> --}}

            <div class="block">
                <div class="block-head">
                  Other type CAPA Details
                </div>

                <div class = "inner-block">
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">Details</label>
                <span style="font-size:0.8rem; margin-left:10px">@if($data->details_new ){{ $data->details_new  }} @else Not Applicable @endif</span>
                </div>
                <div class = "inner-block">
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">CAPA QA Comments</label>
                <span style="font-size:0.8rem; margin-left:10px">@if($data->capa_qa_comments2 ){{ $data->capa_qa_comments2  }} @else Not Applicable @endif</span>
                </div>
            </div>
            <div class="block">
                <div class="block-head">
                    CAPA Details
                </div>
                <div class = "inner-block">
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">CAPA Type</label>
                <span style="font-size:0.8rem; margin-left:10px">@if($data->capa_type){{ $data->capa_type }}@else Not Applicable @endif</span>
                </div>
                {{-- <table>
                    <tr>
                        <th class="w-20">CAPA Type</th>
                        <td class="w-30">@if($data->capa_type){{ $data->capa_type }}@else Not Applicable @endif</td>
                    </tr>
                </table> --}}

                <div class = "inner-block">
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">Corrective Action</label>
                <span style="font-size:0.8rem; margin-left:10px">@if($data->corrective_action ){{ $data->corrective_action  }} @else Not Applicable @endif</span>
                </div>

                <div class = "inner-block">
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">Preventive Action</label>
                <span style="font-size:0.8rem; margin-left:10px">@if($data->preventive_action ){{ $data->preventive_action  }} @else Not Applicable @endif</span>
                </div>
                
                <div class = "inner-block">
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">Supervisor Review Comments</label>
                <span style="font-size:0.8rem; margin-left:10px">@if($data->supervisor_review_comments ){{ $data->supervisor_review_comments  }} @else Not Applicable @endif</span>
                </div>
            </div>
                <div class="block">
                    <div class="block-head">
                        CAPA Closure
                    </div>
                <div class = "inner-block">
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">QA Review & Closure</label>
                <span style="font-size:0.8rem; margin-left:10px">@if($data->qa_review ){{ $data->qa_review  }} @else Not Applicable @endif</span>
                </div>
            </div>

        <div class="block">
            <div class="block-head">
                Closure Attachment
             </div>
               <div class="border-table">
                 <table>
                     <tr class="table_bg">
                         <th class="w-20">Sr no..</th>
                         <th class="w-60">File </th>
                     </tr>
                         @if($data->closure_attachment)
                         @foreach(json_decode($data->closure_attachment) as $key => $file)
                             <tr>
                                 <td class="w-20">{{ $key + 1 }}</td>
                                 <td class="w-20"><a href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a> </td>
                             </tr>
                         @endforeach
                         @else
                         <tr>
                             <td class="w-20">1</td>
                             <td class="w-20">Not Applicable</td>
                         </tr>
                     @endif

                 </table>
               </div>
            </div>
                <div class="block">
                    <div class="block-head">
                        Extension Justification
                    </div>

                <div class = "inner-block">
                    <label class="summer" style="font-weight: bold; font-size:13px; display:inline;">Due Date Extension Justification</label>
                <span style="font-size:0.8rem; margin-left:10px">@if($data->due_date_extension ){{ $data->due_date_extension  }} @else Not Applicable @endif</span>
                </div>
               
                   {{-- </div> --}}

                    </div>

                    </div>

            <div class="block">
                <div class="block-head">
                    Activity Log
                </div>
                <table>
                    <tr>
                        <th class="w-20">Proposed Plan By
                        </th>
                        <td class="w-30">{{ $data->plan_proposed_by }}</td>
                        <th class="w-20">
                            Proposed Plan On</th>
                        <td class="w-30">{{ $data->plan_proposed_on }}</td>
                        <th class="w-20">
                            Proposed Plan Comment</th>
                        <td class="w-30">{{ $data->plan_proposed_comment }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Plan Approved By
                        </th>
                        <td class="w-30">{{ $data->plan_approved_by }}</td>
                        <th class="w-20">
                            Plan Approved On</th>
                        <td class="w-30">{{ $data->Plan_approved_on }}</td>
                        <th class="w-20">
                            Plan Approved Comment</th>
                        <td class="w-30">{{ $data->plan_approved_comment }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">More Info Required By</th>
                        <td class="w-30">{{ $data->more_info_review_by }}</td>
                        <th class="w-20">More Info Required On</th>
                        <td class="w-30">{{ $data->more_info_review_on }}</td>
                        <th class="w-20">More Info Required Comment</th>
                        <td class="w-30">{{ $data->more_info_review_comment }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Approved Plan By
                        </th>
                        <td class="w-30">{{ $data->plan_approved_by }}</td>
                        <th class="w-20">
                            Approved Plan On</th>
                        <td class="w-30">{{ $data->Plan_approved_on }}</td>
                        <th class="w-20">
                            Approved Plan Comment</th>
                        <td class="w-30">{{ $data->plan_approved_comment }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">QA More Info Required By
                        </th>
                        <td class="w-30">{{ $data->qa_more_info_required_by }}</td>
                        <th class="w-20">
                            QA More Info Required By</th>
                        <td class="w-30">{{ $data->qa_more_info_required_on }}</td>
                        <th class="w-20">
                            QA More Info Required By</th>
                        <td class="w-30">{{ $data->qa_more_info_required_comment }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Approved By</th>
                        <td class="w-30">{{ $data->approved_by }}</td>
                        <th class="w-20">Approved On</th>
                        <td class="w-30">{{ $data->approved_on }}</td>
                        <th class="w-20">
                            Approved Comment</th>
                        <td class="w-30">{{ $data->approved_comment }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">Rejected By</th>
                        <td class="w-30">{{ $data->reject_more_info_requierd_by }}</td>
                        <th class="w-20">Rejected On</th>
                        <td class="w-30">{{ $data->reject_more_info_requierd_on }}</td>
                        <th class="w-20">
                            Rejected Comment</th>
                        <td class="w-30">{{ $data->reject_more_info_requierd_comment }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">All Actions Completed By</th>
                        <td class="w-30">{{ $data->all_actions_completed_by }}</td>
                        <th class="w-20">All Actions Completed By</th>
                        <td class="w-30">{{ $data->all_actions_completed_on }}</td>
                        <th class="w-20">
                            All Actions Completed By</th>
                        <td class="w-30">{{ $data->all_actions_completed_comment }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">Cancelled By</th>
                        <td class="w-30">{{ $data->cancelled_by }}</td>
                        <th class="w-20">Cancelled By</th>
                        <td class="w-30">{{ $data->cancelled_on }}</td>
                        <th class="w-20">
                            Cancelled Comment</th>
                        <td class="w-30">{{ $data->cancelled_comment }}</td>
                    </tr>

                </table>
            </div>
        </div>
    </div>

    <footer>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Printed On :</strong> {{ date('d-M-Y') }}
                </td>
                <td class="w-40">
                    <strong>Printed By :</strong> {{ Auth::user()->name }}
                </td>
                {{-- <td class="w-30">
                    <strong>Page :</strong> 1 of 1
                </td> --}}
            </tr>
        </table>
    </footer>

</body>

</html>
