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
                    Supplier Single Report
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
                    <strong>Supplier No.</strong>
                </td>
                @php
                    $requestNUmber = 'RV/RP/' . str_pad($data->record, 4, '0', STR_PAD_LEFT) . '/' . date('Y');
                @endphp
                <td class="w-40">
                    {{ $requestNUmber }}
                </td>
                <td class="w-30">
                    <strong>Record No.</strong> {{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
        </table>
    </header>

    <div class="inner-block">
        <div class="content-table">
            <!-- <div class="block">
                <div class="block-head">
                    Request for Creation of New Manufacturer
                </div>
                <div class="head">
                    <div class="block-head">
                        Initiating Department
                        <table>
                            <tr>
                                <th class="w-20">Initiator</th>
                                <td class="w-30">{{ $data->originator }}</td>
        
                                <th class="w-20">Date Initiation</th>
                                <td class="w-30">{{ Helpers::getDateFormat($data->intiation_date) }}</td>
                            </tr>
                            <tr>
                                <th class="w-20">Due Date</th>
                                <td class="w-30">
                                    @if ($data->due_date)
                                        {{ $data->due_date }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr>
        
                            <tr>
                                <th class="w-20">Short Description</th>
                                <td class="w-80" colspan="3">
                                    @if ($data->short_description)
                                        {{ $data->short_description }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr>
        
                            {{-- <tr>
                                <th class="w-20">Supplier</th>
                                <td class="w-30">
                                    @if ($data->supplier_person)
                                        {{ $data->supplier_person }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                                <th class="w-20">Logo Attachment</th>
                                <td class="w-30">
                                    @if ($data->logo_attachment)
                                        {{ $data->logo_attachment }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr> --}}
        
                            {{-- <tr>
                                <th class="w-20">Contact Person</th>
                                <td class="w-30">
                                    @if ($data->supplier_contact_person)
                                        {{ Helpers::getInitiatorName($data->supplier_contact_person) }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
        
                                <th class="w-20">Supplier Product</th>
                                <td class="w-30">
                                    @if ($data->supplier_products)
                                        {{ $data->supplier_products }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr> --}}
        
                            {{-- <tr>
                                <th class="w-20">Description</th>
                                <td class="w-80" colspan="3">
                                    @if ($data->description)
                                        {{ strip_tags($data->description) }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr> --}}
        
                            {{-- <tr>
                                <th class="w-20">Supplier Type</th>
                                <td class="w-30">
                                    @if ($data->supplier_type)
                                        {{ $data->supplier_type }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
        
                                <th class="w-20">Supplier Sub Type</th>
                                <td class="w-30">
                                    @if ($data->supplier_sub_type)
                                        {{ $data->supplier_sub_type }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr> --}}
        
                            {{-- <tr>
                                <th class="w-20">Supplier Other Type</th>
                                <td class="w-30">
                                    @if ($data->supplier_other_type)
                                        {{ $data->supplier_other_type }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
        
                                <th class="w-20">Supply From</th>
                                <td class="w-30">
                                    @if ($data->supply_from)
                                        {{ $data->supply_from }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr>
                             --}}
                            {{-- <tr>
                                <th class="w-20">Supply To</th>
                                <td class="w-30">
                                    @if ($data->supply_to)
                                        {{ $data->supply_to }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
        
                                <th class="w-20">Supplier Website</th>
                                <td class="w-30">
                                    @if ($data->supplier_website)
                                        {{ $data->supplier_website }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr> --}}
        
                            {{-- <tr>
                                <th class="w-20">Supply To</th>
                                <td class="w-30">
                                    @if ($data->supply_to)
                                        {{ $data->supply_to }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
        
                                <th class="w-20">Supplier Web Search</th>
                                <td class="w-30">
                                    @if ($data->supplier_web_search)
                                        {{ $data->supplier_web_search }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr> --}}
        
                            {{-- <tr>
                                <th class="w-20">Suppplier Attachment</th>
                                <td class="w-30">
                                    @if ($data->supplier_attachment)
                                        {{ $data->supplier_attachment }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
        
                                <th class="w-20">Related URLs</th>
                                <td class="w-30">
                                    @if ($data->related_url)
                                        {{ $data->related_url }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr> --}}
        
                            {{-- <tr>
                                <th class="w-20">Related Quality Event</th>
                                <td class="w-30">
                                    @if ($data->related_quality_events)
                                        {{ $data->related_quality_events }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
        
                                <th class="w-20">Initiator Date</th>
                                <td class="w-30">
                                    @if ($data->intiation_date)
                                        {{ Helpers::getDateFormat($data->intiation_date) }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr> --}}
                        </table>
                    </div>
                </div>
            </div> -->

            <div class="block">
                <div class="head">
                    <div class="block-head">
                        Request for Creation of New Manufacturer
                    </div>
                    <div class="head">
                        <div class="block-head"> 
                            Initiating Department
                        </div>
                        <table>
                            <tr>
                                <th class="w-20">Initiator</th>
                                <td class="w-30">{{ $data->originator }}</td>
        
                                <th class="w-20">Date Initiation</th>
                                <td class="w-30">{{ Helpers::getDateFormat($data->intiation_date) }}</td>
                            </tr>
                            <tr>
                                <th class="w-20">Due Date</th>
                                <td class="w-30">
                                    @if ($data->due_date)
                                        {{ $data->due_date }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr>
        
                            <tr>
                                <th class="w-20">Short Description</th>
                                <td class="w-80" colspan="3">
                                    @if ($data->short_description)
                                        {{ $data->short_description }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr>        
                        </table>
                    </div>
                    <div class="head">
                        <div class="block-head">
                            Purchase Department
                        </div>
                        <table>
                            <tr>
                                <th class="w-20">Initiation Department</th>
                                <td class="w-30">{{ Helpers::getFullDepartmentName($data->initiator_group_code) }}</td>
        
                                <th class="w-20">Initiator Department Code</th>
                                <td class="w-30">{{ $data->initiator_group_code }}</td>
                            </tr>
                            <tr>
                                <th class="w-20">Name of Manufacturer</th>
                                <td class="w-30">
                                    @if ($data->manufacturerName)
                                        {{ $data->manufacturerName }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>

                                <th class="w-20">Name of Starting Material</th>
                                <td class="w-30">
                                    @if ($data->starting_material)
                                        {{ $data->starting_material }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="w-20">Material Code</th>
                                <td class="w-30">
                                    @if ($data->material_code)
                                        {{ $data->material_code }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>

                                <th class="w-20">Pharmacopoeial Claim</th>
                                <td class="w-30">
                                    @if ($data->pharmacopoeial_claim)
                                        {{ $data->pharmacopoeial_claim }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="w-20">CEP Grade Material</th>
                                <td class="w-30">
                                    @if ($data->cep_grade)
                                        {{ $data->cep_grade }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>

                                <th class="w-20">Attach Three Batch COAs</th>
                                <td class="w-30">
                                    @if ($data->attach_batch)
                                        {{ $data->attach_batch }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="w-20">Request For</th>
                                <td class="w-80">
                                    @if ($data->request_for)
                                        {{ $data->request_for }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>                                
                            </tr>

                            <tr>
                                <th class="w-20">Justification for Request</th>
                                <td class="w-80" colspan="3">
                                    @if ($data->request_justification)
                                        {{ $data->request_justification }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr> 
                        </table>

                        <div class="border-table">
                            <div class="block-head">
                                CEP Attachment
                            </div>
                            <table>

                                <tr class="table_bg">
                                    <th class="w-20">S.N.</th>
                                    <th class="w-80">Attachment</th>
                                </tr>
                                @if ($data->cep_attachment)
                                    @foreach (json_decode($data->cep_attachment) as $key => $file)
                                        <tr>
                                            <td class="w-20">{{ $key + 1 }}</td>
                                            <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                                    target="_blank"><b>{{ $file }}</b></a> </td>
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

            <!-- Purchase Department -->

            <!-- HOD Details -->

            <div class="block">
                <div class="block-head">
                    CQA Department
                </div>
                <table>
                    <tr>
                        <th class="w-20">Availability of Manufacturer COAs</th>
                        <td class="w-30">
                            @if ($data->manufacturer_availability)
                                {{ $data->manufacturer_availability }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Request Accepted</th>
                        <td class="w-80" colspan="3">
                            @if ($data->request_accepted)
                                {{ $data->request_accepted }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Remark</th>
                        <td class="w-80" colspan="3">
                            @if ($data->cqa_remark)
                                {{ $data->cqa_remark }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Accepted By</th>
                        <td class="w-30">
                            @if ($data->accepted_by)
                                {{ Helpers::getInitiatorName($data->accepted_by) }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Pre Purchase Sample Required?</th>
                        <td class="w-30">
                            @if ($data->pre_purchase_sample)
                                {{ $data->pre_purchase_sample }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>                    
                    
                    <tr>
                        <th class="w-20">Justification</th>
                        <td class="w-80" colspan="3">
                            @if ($data->justification)
                                {{ $data->justification }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">CQA Coordinator</th>
                        <td class="w-30">
                            @if ($data->cqa_coordinator)
                                {{ Helpers::getInitiatorName($data->cqa_coordinator) }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Pre Purchase Sample Analysis Completed?
                        </th>
                        <td class="w-30">
                            @if ($data->pre_purchase_sample_analysis)
                                {{ $data->pre_purchase_sample_analysis }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="w-20">Availability of COAS After Analysis</th>
                        <td class="w-30">
                            @if ($data->availability_od_coa)
                                {{ $data->availability_od_coa }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Analyzed on Location</th>
                        <td class="w-30">
                            @if ($data->analyzed_location)
                                {{ $data->analyzed_location }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="w-20">Review Comment of CQA</th>
                        <td class="w-80" colspan="3">
                            @if ($data->cqa_comment)
                                {{ $data->cqa_comment }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Material Name</th>
                        <td class="w-30">
                            @if ($data->materialName)
                                {{ $data->materialName }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Name of the Manufacturer</th>
                        <td class="w-30">
                            @if ($data->manufacturerNameNew)
                                {{ $data->manufacturerNameNew }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Analyzed on Location</th>
                        <td class="w-30">
                            @if ($data->analyzedLocation)
                                {{ $data->analyzedLocation }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Justification</th>
                        <td class="w-80" colspan="3">
                            @if ($data->justification)
                                {{ $data->justification }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Review Comment of Corporate CQA</th>
                        <td class="w-80" colspan="3"> 
                            @if ($data->cqa_corporate_comment)
                                {{ $data->cqa_corporate_comment }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">CQA Designee</th>
                        <td class="w-30">
                            @if ($data->cqa_designee)
                                {{ Helpers::getInitiatorName($data->cqa_designee) }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                </table>

                <div class="border-table">
                    <div class="block-head">
                        COA's Attachment
                    </div>
                    <table>
                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-80">Batch No</th>
                        </tr>
                        @if ($data->coa_attachment)
                            @foreach (json_decode($data->coa_attachment) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                            target="_blank"><b>{{ $file }}</b></a> </td>
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

                <div class="border-table">
                    <div class="block-head">
                        Certificate Attachments
                    </div>
                    @php
                        $types = ['tse', 'residual_solvent','melamine','gmo','gluten','manufacturer_evaluation','who','gmp','ISO','manufacturing_license','CEP','risk_assessment','elemental_impurity','azido_impurities'];
                    @endphp
                    @foreach ($types as $type)
                        <table style="margin-top: 10px;">
                            <tr>
                                <th style="width: 15%">Certificate Name</th>
                                <th style="width: 25%">Attachment</th>
                                <th style="width: 15%">Issue Date</th>
                                <th style="width: 15%">Expiry Date</th>
                                <th>Remark</th>
                            </tr>
                                @if(!empty($supplierChecklist))
                                    @foreach ($supplierChecklist->where('doc_type', $type) as $grid)
                                        @php
                                            $filePath = $grid->attachment;
                                            $fileName = str_replace('upload\\', '', $filePath);
                                        @endphp
                                        <tr>
                                            <td>{{ strtoupper(str_replace('_', ' ', $type)) }}</td>
                                            <td>
                                                <a href="{{ asset('upload/' . $fileName) }}" target="_blank">{{ $fileName }}</a>
                                            </td>
                                            <td>{{ Helpers::getdateFormat($grid->issue_date) }}</td>
                                            <td>{{ Helpers::getdateFormat($grid->expiry_date) }}</td>
                                            <td>{{ $grid->remarks }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="w-20">Not Applicable</td>
                                    </tr>
                                @endif
                        </table>
                    @endforeach                   
                </div>
            </div>

            {{-- <div class="border-table">
                <div class="block-head">
                    HOD Attachments
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">Attachment</th>
                    </tr>
                    @if ($data->HOD_attachment)
                        @foreach (json_decode($data->HOD_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a> </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="w-20">1</td>
                            <td class="w-20">Not Applicable</td>
                        </tr>
                    @endif
                </table>
            </div> --}}
            <div class="block">
                <div class="block-head">
                    Formulation & Development Department/CQA/MS&T
                </div>
                <table>
                    <tr>
                        <th class="w-20">Samples Ordered for Suitability Trail at R&D/MS & T</th>
                        <td class="w-30">
                            @if ($data->sample_ordered)
                                {{ $data->sample_ordered }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                        
                        <th class="w-20">Acknowledge By</th>
                        <td class="w-30">
                            @if ($data->acknowledge_by)
                                {{ $data->acknowledge_by }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Justification</th>
                        <td class="w-80" colspan="3">
                            @if ($data->sample_order_justification)
                                {{ $data->sample_order_justification }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Feedback on Trail Status Completed</th>
                        <td class="w-30">
                            @if ($data->trail_status_feedback)
                                {{ $data->trail_status_feedback }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Sample Stand Approved?</th>
                        <td class="w-30">
                            @if ($data->sample_stand_approved)
                                {{ $data->sample_stand_approved }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Availability of Supply Chain?</th>
                        <td class="w-30">
                            @if ($data->supply_chain_availability)
                                {{ $data->supply_chain_availability }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Availability of Quality Agreement?</th>
                        <td class="w-30">
                            @if ($data->quality_agreement_availability)
                                {{ $data->quality_agreement_availability }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Risk Assessment Done?</th>
                        <td class="w-30">
                            @if ($data->risk_assessment_done)
                                {{ $data->risk_assessment_done }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Risk Rating</th>
                        <td class="w-30">
                            @if ($data->risk_rating)
                                {{ $data->risk_rating }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Manufacturer Audit planned</th>
                        <td class="w-30">
                            @if ($data->manufacturer_audit_planned)
                                {{ $data->manufacturer_audit_planned }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Maufacturer Audit Conducted On</th>
                        <td class="w-30">
                            @if ($data->manufacturer_audit_conducted)
                                {{ $data->manufacturer_audit_conducted }}
                            @else
                                Not Applicable
                            @endif
                        </td>                        
                    </tr>

                    <tr>
                        <th class="w-20">Manufacturer Can be?</th>
                        <td class="w-30">
                            @if ($data->manufacturer_can_be)
                                {{ $data->manufacturer_can_be }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                </table>

                <div class="border-table">
                    <div class="block-head">
                        Document Checklist
                    </div>
                    <table>
                        <tr class="table_bg">
                            <th class="w-20">Document Name</th>
                            <th class="w-20">Selection</th>
                            <th class="w-60">Remarks</th>
                        </tr>
                        <tr>
                            <td>TSE/BSE</td>
                            <td>{{ $data->tse_bse }}</td>
                            <td>{{ $data->tse_bse_remark }}</td>
                        </tr>
                        
                        <tr>
                            <td>Residual Solvent</td>
                            <td>{{ $data->residual_solvent }}</td>
                            <td>{{ $data->residual_solvent_remark }}</td>
                        </tr>
                        
                        <tr>
                            <td>GMO</td>
                            <td>{{ $data->gmo }}</td>
                            <td>{{ $data->gmo_remark }}</td>
                        </tr>
                        
                        <tr>
                            <td>Melamine</td>
                            <td>{{ $data->melamine }}</td>
                            <td>{{ $data->melamine_remark }}</td>
                        </tr>
                        
                        <tr>
                            <td>Gluten</td>
                            <td>{{ $data->gluten }}</td>
                            <td>{{ $data->gluten_remark }}</td>
                        </tr>
                        
                        <tr>
                            <td>Nitrosamine</td>
                            <td>{{ $data->nitrosamine }}</td>
                            <td>{{ $data->nitrosamine_remark }}</td>
                        </tr>
                        
                        <tr>
                            <td>WHO</td>
                            <td>{{ $data->who }}</td>
                            <td>{{ $data->who_remark }}</td>
                        </tr>
                        
                        <tr>
                            <td>GMP</td>
                            <td>{{ $data->gmp }}</td>
                            <td>{{ $data->gmp_remark }}</td>
                        </tr>
                        
                        <tr>
                            <td>ISO Cerificates</td>
                            <td>{{ $data->iso_certificate }}</td>
                            <td>{{ $data->iso_certificate_remark }}</td>
                        </tr>
                        
                        <tr>
                            <td>Manufacturing License</td>
                            <td>{{ $data->manufacturing_license }}</td>
                            <td>{{ $data->manufacturing_license_remark }}</td>
                        </tr>
                        
                        <tr>
                            <td>CEP</td>
                            <td>{{ $data->cep }}</td>
                            <td>{{ $data->cep_remark }}</td>
                        </tr>
                        
                        <tr>
                            <td>MSDS</td>
                            <td>{{ $data->msds }}</td>
                            <td>{{ $data->msds_remark }}</td>
                        </tr>
                        
                        <tr>
                            <td>Elemental Impurities</td>
                            <td>{{ $data->elemental_impurities }}</td>
                            <td>{{ $data->elemental_impurities_remark }}</td>
                        </tr>
                        
                        <tr>
                            <td>Assessment/Declaration of Azido Impurities as Applicable</td>
                            <td>{{ $data->declaration }}</td>
                            <td>{{ $data->declaration_remark }}</td>
                        </tr>
                    </table>
                </div>


                <!--  HOD Details -->
                <div class="block" style="margin-top: 15px;">
                    <div class="block-head">
                        HOD Details
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">HOD Feedback</th>
                            <td class="w-80" colspan="3">
                                @if ($data->HOD_feedback)
                                    {{ $data->HOD_feedback }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">HOD Comment</th>
                            <td class="w-80" colspan="3">
                                @if ($data->HOD_comment)
                                    {{ $data->HOD_comment }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="border-table">
                    <div class="block-head">
                        HOD Attachments
                    </div>
                    <table>
                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Attachment</th>
                        </tr>
                        @if ($data->HOD_attachment)
                            @foreach (json_decode($data->HOD_attachment) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                            target="_blank"><b>{{ $file }}</b></a> </td>
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
                <div class="border-table">
                    <div class="block-head">
                        Additional Attachments
                    </div>
                    <table>
                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Attachment</th>
                        </tr>
                        @if ($data->hod_additional_attachment)
                            @foreach (json_decode($data->hod_additional_attachment) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                            target="_blank"><b>{{ $file }}</b></a> </td>
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

                <!-- Supplier Details -->
                <div class="block" style="margin-top: 15px;">
                    <div class="block-head">
                        Supplier Details
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">Supplier Name</th>
                            <td class="w-30">
                                @if ($data->supplier_name)
                                    {{ $data->supplier_name }}
                                @else
                                    Not Applicable
                                @endif
                            </td>

                            <th class="w-20">Supplier ID</th>
                            <td class="w-30">
                                @if ($data->supplier_id)
                                    {{ $data->supplier_id }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">Manufacturer Name</th>
                            <td class="w-30">
                                @if ($data->manufacturer_name)
                                    {{ $data->manufacturer_name }}
                                @else
                                    Not Applicable
                                @endif
                            </td>

                            <th class="w-20">Manufacturer ID</th>
                            <td class="w-30">
                                @if ($data->manufacturer_id)
                                    {{ $data->manufacturer_id }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">Vendor Name</th>
                            <td class="w-30">
                                @if ($data->vendor_name)
                                    {{ $data->vendor_name }}
                                @else
                                    Not Applicable
                                @endif
                            </td>

                            <th class="w-20">Vendor ID</th>
                            <td class="w-30">
                                @if ($data->vendor_id)
                                    {{ $data->vendor_id }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">Contact Person</th>
                            <td class="w-30">
                                @if ($data->contact_person)
                                    {{ $data->contact_person }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">Other Contacts</th>
                            <td class="w-30">
                                @if ($data->other_contacts)
                                    {{ $data->other_contacts }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">Supplier Services</th>
                            <td class="w-80" colspan="3">
                                @if ($data->supplier_serivce)
                                    {{ $data->supplier_serivce }}
                                @else
                                    Not Applicable
                                @endif
                            </td>|
                        </tr>

                        <tr>
                            <th class="w-20">Zone</th>
                            <td class="w-30">
                                @if ($data->zone)
                                    {{ $data->zone }}
                                @else
                                    Not Applicable
                                @endif
                            </td>

                            <th class="w-20">Country</th>
                            <td class="w-30">
                                @if ($data->country)
                                    {{ $data->country }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">State</th>
                            <td class="w-30">
                                @if ($data->state)
                                    {{ $data->state }}
                                @else
                                    Not Applicable
                                @endif
                            </td>

                            <th class="w-20">City</th>
                            <td class="w-30">
                                @if ($data->city)
                                    {{ $data->city }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">Address</th>
                            <td class="w-80" colspan="3">
                                @if ($data->address)
                                    {{ $data->address }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Supplier Web Site</th>
                            <td class="w-30">
                                @if ($data->suppplier_web_site)
                                    {{ $data->suppplier_web_site }}
                                @else
                                    Not Applicable
                                @endif
                            </td>

                            <th class="w-20">ISO Certification Date</th>
                            <td class="w-30">
                                @if ($data->iso_certified_date)
                                    {{ Helpers::getdateFormat($data->iso_certified_date) }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                    </table>
                </div>
            </div>

            <table>
                <tr>
                    <th class="w-20">Contracts</th>
                    <td class="w-30">
                        @if ($data->address)
                            {{ $data->address }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Related Non Conformances</th>
                    <td class="w-30">
                        @if ($data->suppplier_web_site)
                            {{ $data->suppplier_web_site }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Supplier Contracts/Agreements
                    </th>
                    <td class="w-30">
                        @if ($data->address)
                            {{ $data->address }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Regulatory History</th>
                    <td class="w-30">
                        @if ($data->suppplier_web_site)
                            {{ $data->suppplier_web_site }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Distribution Sites</th>
                    <td class="w-30">
                        @if ($data->address)
                            {{ $data->address }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <th class="w-20">Manufacturing Sites</th>
                    <td class="w-80" colspan="3">
                        @if ($data->manufacturing_sited)
                            {{ $data->manufacturing_sited }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Quality Management</th>
                    <td class="w-80" colspan="3">
                        @if ($data->quality_management)
                            {{ $data->quality_management }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Business History</th>
                    <td class="w-80" colspan="3">
                        @if ($data->bussiness_history)
                            {{ $data->bussiness_history }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Performance History</th>
                    <td class="w-80" colspan="3">
                        @if ($data->performance_history)
                            {{ $data->performance_history }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Compliance Risk</th>
                    <td class="w-80" colspan="3">
                        @if ($data->compliance_risk)
                            {{ $data->compliance_risk }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

            </table>
        </div>

        
        <div class="border-table">
                <div class="block-head">
                    ISO Certificate Attachments
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">Attachment</th>
                    </tr>
                    @if ($data->iso_certificate_attachment)
                        @foreach (json_decode($data->iso_certificate_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a> </td>
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


        <div class="border-table">
            <div class="block-head">
                Additional Attachments
            </div>
            <table>
                <tr class="table_bg">
                    <th class="w-20">S.N.</th>
                    <th class="w-60">Attachment</th>
                </tr>
                @if ($data->supplier_detail_additional_attachment)
                    @foreach (json_decode($data->supplier_detail_additional_attachment) as $key => $file)
                        <tr>
                            <td class="w-20">{{ $key + 1 }}</td>
                            <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                    target="_blank"><b>{{ $file }}</b></a> </td>
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


        <!--  Score Card -->
        <div class="block" style="margin-top: 15px;">
            <div class="block-head">
                Score Card
            </div>
            <table>
                <tr>
                    <th class="w-20">Cost Reduction</th>
                    <td class="w-30">
                        @if ($data->cost_reduction)
                            {{ $data->cost_reduction }}
                        @else
                            Not Applicable
                        @endif                        
                    </td>
                    <th class="w-20">Cost Reduction Weight</th>
                    <td class="w-30">
                        @if ($data->cost_reduction_weight)
                            {{ $data->cost_reduction_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Payment Terms</th>
                    <td class="w-30">
                        @if ($data->payment_term)
                            {{ $data->payment_term }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Payment Terms Weight</th>
                    <td class="w-30">
                        @if ($data->payment_term_weight)
                            {{ $data->payment_term_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Lead Time Days</th>
                    <td class="w-30">
                        @if ($data->lead_time_days)
                            {{ $data->lead_time_days }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Lead Time Days Weight</th>
                    <td class="w-30">
                        @if ($data->lead_time_days_weight)
                            {{ $data->lead_time_days_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">On-Time Delivery
                    </th>
                    <td class="w-30">
                        @if ($data->ontime_delivery)
                            {{ $data->ontime_delivery }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">On-Time Delivery Weight</th>
                    <td class="w-30">
                        @if ($data->ontime_delivery_weight)
                            {{ $data->ontime_delivery_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Supplier Business Planning</th>
                    <td class="w-30">
                        @if ($data->supplier_bussiness_planning)
                            {{ $data->supplier_bussiness_planning }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Supplier Business Weight</th>
                    <td class="w-30">
                        @if ($data->supplier_bussiness_planning_weight)
                            {{ $data->supplier_bussiness_planning_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Rejection in PPM</th>
                    <td class="w-30">
                        @if ($data->rejection_ppm)
                            {{ $data->addresrejection_ppms }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Rejection in PPM Weight</th>
                    <td class="w-30">
                        @if ($data->rejection_ppm_weight)
                            {{ $data->rejection_ppm_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Quality Systems</th>
                    <td class="w-30">
                        @if ($data->quality_system)
                            {{ $data->quality_system }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Quality Systems Weight</th>
                    <td class="w-30">
                        @if ($data->quality_system_ranking)
                            {{ $data->quality_system_ranking }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20"># of CAR's generated</th>
                    <td class="w-30">
                        @if ($data->car_generated)
                            {{ $data->car_generated }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20"># of CAR's generated Weight</th>
                    <td class="w-30">
                        @if ($data->car_generated_weight)
                            {{ $data->car_generated_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">CAR Closure Time</th>
                    <td class="w-30">
                        @if ($data->closure_time)
                            {{ $data->closure_time }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">CAR Closure Time Weight</th>
                    <td class="w-30">
                        @if ($data->closure_time_weight)
                            {{ $data->closure_time_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">End-User Satisfaction</th>
                    <td class="w-30">
                        @if ($data->end_user_satisfaction)
                            {{ $data->end_user_satisfaction }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">End-User Satisfaction Weight</th>
                    <td class="w-30">
                        @if ($data->end_user_satisfaction_weight)
                            {{ $data->end_user_satisfaction_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="border-table">
            <div class="block-head">
                Additional Attachments
            </div>
            <table>
                <tr class="table_bg">
                    <th class="w-20">S.N.</th>
                    <th class="w-60">Attachment</th>
                </tr>
                @if ($data->score_card_additional_attachment)
                    @foreach (json_decode($data->score_card_additional_attachment) as $key => $file)
                        <tr>
                            <td class="w-20">{{ $key + 1 }}</td>
                            <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                    target="_blank"><b>{{ $file }}</b></a> </td>
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

        <!--  QA Reviewer -->
        <div class="block" style="margin-top: 15px;">
            <div class="block-head">
                QA Reviewer
            </div>
            <table>
                <tr>
                    <th class="w-20">QA Reviewer Feedback</th>
                    <td class="w-80" colspan="3">
                        @if ($data->QA_reviewer_feedback)
                            {{ $data->QA_reviewer_feedback }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">QA Reviewer Comment</th>
                    <td class="w-80" colspan="3">
                        @if ($data->QA_reviewer_comment)
                            {{ $data->QA_reviewer_comment }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="border-table">
            <div class="block-head">
                QA Reviewer Attachment
            </div>
            <table>
                <tr class="table_bg">
                    <th class="w-20">S.N.</th>
                    <th class="w-60">Attachment</th>
                </tr>
                @if ($data->QA_reviewer_attachment)
                    @foreach (json_decode($data->QA_reviewer_attachment) as $key => $file)
                        <tr>
                            <td class="w-20">{{ $key + 1 }}</td>
                            <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                    target="_blank"><b>{{ $file }}</b></a> </td>
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

        <div class="border-table">
            <div class="block-head">
                Additional Attachments
            </div>
            <table>
                <tr class="table_bg">
                    <th class="w-20">S.N.</th>
                    <th class="w-60">Attachment</th>
                </tr>
                @if ($data->qa_reviewer_additional_attachment)
                    @foreach (json_decode($data->qa_reviewer_additional_attachment) as $key => $file)
                        <tr>
                            <td class="w-20">{{ $key + 1 }}</td>
                            <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                    target="_blank"><b>{{ $file }}</b></a> </td>
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


        <!--  Score Card -->
        <div class="block" style="margin-top: 15px;">
            <div class="block-head">
                Score Card
            </div>
            <table>
                <tr>
                    <th class="w-20">Cost Reduction</th>
                    <td class="w-30">
                        @if ($data->cost_reduction)
                            {{ $data->cost_reduction }}
                        @else
                            Not Applicable
                        @endif                        
                    </td>
                    <th class="w-20">Cost Reduction Weight</th>
                    <td class="w-30">
                        @if ($data->cost_reduction_weight)
                            {{ $data->cost_reduction_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Payment Terms</th>
                    <td class="w-30">
                        @if ($data->payment_term)
                            {{ $data->payment_term }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Payment Terms Weight</th>
                    <td class="w-30">
                        @if ($data->payment_term_weight)
                            {{ $data->payment_term_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Lead Time Days</th>
                    <td class="w-30">
                        @if ($data->lead_time_days)
                            {{ $data->lead_time_days }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Lead Time Days Weight</th>
                    <td class="w-30">
                        @if ($data->lead_time_days_weight)
                            {{ $data->lead_time_days_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">On-Time Delivery
                    </th>
                    <td class="w-30">
                        @if ($data->ontime_delivery)
                            {{ $data->ontime_delivery }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">On-Time Delivery Weight</th>
                    <td class="w-30">
                        @if ($data->ontime_delivery_weight)
                            {{ $data->ontime_delivery_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Supplier Business Planning</th>
                    <td class="w-30">
                        @if ($data->supplier_bussiness_planning)
                            {{ $data->supplier_bussiness_planning }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Supplier Business Weight</th>
                    <td class="w-30">
                        @if ($data->supplier_bussiness_planning_weight)
                            {{ $data->supplier_bussiness_planning_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Rejection in PPM</th>
                    <td class="w-30">
                        @if ($data->rejection_ppm)
                            {{ $data->addresrejection_ppms }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Rejection in PPM Weight</th>
                    <td class="w-30">
                        @if ($data->rejection_ppm_weight)
                            {{ $data->rejection_ppm_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Quality Systems</th>
                    <td class="w-30">
                        @if ($data->quality_system)
                            {{ $data->quality_system }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Quality Systems Weight</th>
                    <td class="w-30">
                        @if ($data->quality_system_ranking)
                            {{ $data->quality_system_ranking }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20"># of CAR's generated</th>
                    <td class="w-30">
                        @if ($data->car_generated)
                            {{ $data->car_generated }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20"># of CAR's generated Weight</th>
                    <td class="w-30">
                        @if ($data->car_generated_weight)
                            {{ $data->car_generated_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">CAR Closure Time</th>
                    <td class="w-30">
                        @if ($data->closure_time)
                            {{ $data->closure_time }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">CAR Closure Time Weight</th>
                    <td class="w-30">
                        @if ($data->closure_time_weight)
                            {{ $data->closure_time_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">End-User Satisfaction</th>
                    <td class="w-30">
                        @if ($data->end_user_satisfaction)
                            {{ $data->end_user_satisfaction }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">End-User Satisfaction Weight</th>
                    <td class="w-30">
                        @if ($data->end_user_satisfaction_weight)
                            {{ $data->end_user_satisfaction_weight }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="border-table">
            <div class="block-head">
                Additional Attachments
            </div>
            <table>
                <tr class="table_bg">
                    <th class="w-20">S.N.</th>
                    <th class="w-60">Attachment</th>
                </tr>
                @if ($data->score_card_additional_attachment)
                    @foreach (json_decode($data->score_card_additional_attachment) as $key => $file)
                        <tr>
                            <td class="w-20">{{ $key + 1 }}</td>
                            <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                    target="_blank"><b>{{ $file }}</b></a> </td>
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

        <!--  Risk Assessment -->
        <div class="block" style="margin-top: 15px;">
            <div class="block-head">
                Risk Assessment
            </div>
            <table>
            <tr>
                    <th class="w-20">Last Audit Date
                    </th>
                    <td class="w-30">
                        @if ($data->last_audit_date)
                            {{ Helpers::getdateFormat($data->last_audit_date) }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Next Audit Date</th>
                    <td class="w-30">
                        @if ($data->next_audit_date)
                            {{ Helpers::getdateFormat($data->next_audit_date) }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Audit Frequency
                    </th>
                    <td class="w-30">
                        @if ($data->audit_frequency)
                            {{ $data->audit_frequency }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Last Audit Result
                    </th>
                    <td class="w-30">
                        @if ($data->last_audit_result)
                            {{ $data->last_audit_result }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Facility Type
                    </th>
                    <td class="w-30">
                        @if ($data->facility_type)
                            {{ $data->facility_type }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Number of Employees
                    </th>
                    <td class="w-30">
                        @if ($data->nature_of_employee)
                            {{ $data->nature_of_employee }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Access to Technical Support
                    </th>
                    <td class="w-30">
                        @if ($data->technical_support)
                            {{ $data->technical_support }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Services Supported
                    </th>
                    <td class="w-30">
                        @if ($data->survice_supported)
                            {{ $data->survice_supported }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Reliability
                    </th>
                    <td class="w-30">
                        @if ($data->reliability)
                            {{ $data->reliability }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Revenue
                    </th>
                    <td class="w-30">
                        @if ($data->revenue)
                            {{ $data->revenue }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Client Base
                    </th>
                    <td class="w-30">
                        @if ($data->client_base)
                            {{ $data->client_base }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                    <th class="w-20">Previous Audit Results
                    </th>
                    <td class="w-30">
                        @if ($data->previous_audit_result)
                            {{ $data->previous_audit_result }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="border-table">
            <div class="block-head">
                Additional Attachments
            </div>
            <table>
                <tr class="table_bg">
                    <th class="w-20">S.N.</th>
                    <th class="w-60">Attachment</th>
                </tr>
                @if ($data->risk_assessment_additional_attachment)
                    @foreach (json_decode($data->risk_assessment_additional_attachment) as $key => $file)
                        <tr>
                            <td class="w-20">{{ $key + 1 }}</td>
                            <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                    target="_blank"><b>{{ $file }}</b></a> </td>
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

        <!-- QR Head Reviewer -->
        <div class="block" style="margin-top: 15px;">
            <div class="block-head">
                QA Head Reviewer
            </div>
            <table>
                <tr>
                    <th class="w-20">QA Head Comment</th>
                    <td class="w-80" colspan="3">
                        @if ($data->QA_head_comment)
                            {{ $data->QA_head_comment }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
            </table>
        </div>


        <div class="border-table">
            <div class="block-head">
                QA Head Attachment
            </div>
            <table>
                <tr class="table_bg">
                    <th class="w-20">S.N.</th>
                    <th class="w-60">Attachment</th>
                </tr>
                @if ($data->QA_head_attachment)
                    @foreach (json_decode($data->QA_head_attachment) as $key => $file)
                        <tr>
                            <td class="w-20">{{ $key + 1 }}</td>
                            <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                    target="_blank"><b>{{ $file }}</b></a> </td>
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

        <div class="border-table">
            <div class="block-head">
                Additional Attachments
            </div>
            <table>
                <tr class="table_bg">
                    <th class="w-20">S.N.</th>
                    <th class="w-60">Attachment</th>
                </tr>
                @if ($data->qa_head_additional_attachment)
                    @foreach (json_decode($data->qa_head_additional_attachment) as $key => $file)
                        <tr>
                            <td class="w-20">{{ $key + 1 }}</td>
                            <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                    target="_blank"><b>{{ $file }}</b></a> </td>
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

        <!--  Activity Log -->
        <div class="block">
            <div class="block-head">
                Activity Log
            </div>
            <table>
                <tr>
                    <th class="w-20">Need for Sourcing of Starting Material By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->submitted_by }}</div>
                    </td>
                    <th class="w-20">
                        Need for Sourcing of Starting Material On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->submitted_on }}</div>
                    </td>
                    <th class="w-20">Need for Sourcing of Starting Material Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->submitted_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Approved by Contract Giver By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->approvedBy_contract_giver_by }}</div>
                    </td>
                    <th class="w-20">Approved by Contract Giver On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->approvedBy_contract_giver_on }}</div>
                    </td>
                    <th class="w-20">Approved by Contract Giver Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->approvedBy_contract_giver_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Request Justified By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->request_justified_by }}</div>
                    </td>
                    <th class="w-20">
                        Request Justified On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->request_justified_on }}</div>
                    </td>
                    <th class="w-20">Request Justified Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->request_justified_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Request Not Justified By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->request_not_justified_by }}</div>
                    </td>
                    <th class="w-20">
                        Request Not Justified On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->request_not_justified_on }}</div>
                    </td>
                    <th class="w-20">Request Not Justified Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->request_not_justified_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Pre-Purchase Sample Required By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->prepurchase_sample_by }}</div>
                    </td>
                    <th class="w-20">Pre-Purchase Sample Required On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->prepurchase_sample_on }}</div>
                    </td>
                    <th class="w-20">Pre-Purchase Sample Required Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->prepurchase_sample_comment }}</div>
                    </td>
                </tr>                
                <tr>
                    <th class="w-20">Pre-Purchase Sample Not Required By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->prepurchase_sample_notRequired_by }}</div>
                    </td>
                    <th class="w-20">Pre-Purchase Sample Not Required On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->prepurchase_sample_notRequired_on }}</div>
                    </td>
                    <th class="w-20">Pre-Purchase Sample Not Required Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->prepurchase_sample_notRequired_comment }}</div>
                    </td>
                </tr>             
                <tr>
                    <th class="w-20">>Purchase Sample Request Ack. by Dep. By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pendigPurchaseSampleRequested_by }}</div>
                    </td>
                    <th class="w-20">>Purchase Sample Request Ack. by Dep. On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pendigPurchaseSampleRequested_on }}</div>
                    </td>
                    <th class="w-20">>Purchase Sample Request Ack. by Dep. Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pendigPurchaseSampleRequested_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Purchase Sample Analysis Satisfactory By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->purchaseSampleanalysis_by }}</div>
                    </td>
                    <th class="w-20">Purchase Sample Analysis Satisfactory On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->purchaseSampleanalysis_on }}</div>
                    </td>
                    <th class="w-20">
                        Purchase Sample Analysis Satisfactory Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->purchaseSampleanalysis_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Purchase Sample Analysis Not Satisfactory By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->purchaseSampleanalysisNotSatisfactory_by }}</div>
                    </td>
                    <th class="w-20">Purchase Sample Analysis Not Satisfactory On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->purchaseSampleanalysisNotSatisfactory_on }}</div>
                    </td>
                    <th class="w-20">Purchase Sample Analysis Not Satisfactory Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->purchaseSampleanalysisNotSatisfactory_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">F&D Review Completed By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->FdReviewCompleted_by }}</div>
                    </td>
                    <th class="w-20">F&D Review Completed On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->FdReviewCompleted_on }}</div>
                    </td>
                    <th class="w-20">F&D Review Completed Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->FdReviewCompleted_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Acknowledgement By Purchase Dept. By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->acknowledgByPD_by }}</div>
                    </td>
                    <th class="w-20">Acknowledgement By Purchase Dept. On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->acknowledgByPD_on }}</div>
                    </td>
                    <th class="w-20">Acknowledgement By Purchase Dept. Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->acknowledgByPD_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">All Requirements Fulfilled By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->requirementFullfilled_by }}</div>
                    </td>
                    <th class="w-20">All Requirements Fulfilled On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->requirementFullfilled_on }}</div>
                    </td>
                    <th class="w-20">All Requirements Fulfilled Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->requirementFullfilled_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">All Requirements Not Fulfilled By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->requiredNotFulfilled_by }}</div>
                    </td>
                    <th class="w-20">All Requirements Not Fulfilled On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->requiredNotFulfilled_on }}</div>
                    </td>
                    <th class="w-20">All Requirements Not Fulfilled Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->requiredNotFulfilled_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Risk Rating Observed as High By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->riskRatingObservedAsHigh_by }}</div>
                    </td>
                    <th class="w-20">Risk Rating Observed as High On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->riskRatingObservedAsHigh_on }}</div>
                    </td>
                    <th class="w-20">Risk Rating Observed as High Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->riskRatingObservedAsHigh_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Risk Rating Observed as Low By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->riskRatingObservedAsLow_by }}</div>
                    </td>
                    <th class="w-20">Risk Rating Observed as Low On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->riskRatingObservedAsLow_on }}</div>
                    </td>
                    <th class="w-20">Risk Rating Observed as Low Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->riskRatingObservedAsLow_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Manufacturer Audit Passed By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->manufacturerAuditPassed_by }}</div>
                    </td>
                    <th class="w-20">Manufacturer Audit Passed On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->manufacturerAuditPassed_on }}</div>
                    </td>
                    <th class="w-20">Manufacturer Audit Passed Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->manufacturerAuditPassed_comment }}</div>
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Initiate Periodic Revaluation By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->periodicRevolutionInitiated_by }}</div>
                    </td>
                    <th class="w-20">Initiate Periodic Revaluation On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->periodicRevolutionInitiated_on }}</div>
                    </td>
                    <th class="w-20">Initiate Periodic Revaluation Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->periodicRevolutionInitiated_comment }}</div>
                    </td>
                </tr>                
                <tr>
                    <th class="w-20">Risk Rating Observed as High/Medium By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->riskRatingObservedAsHighMedium_by }}</div>
                    </td>
                    <th class="w-20">Risk Rating Observed as High/Medium On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->riskRatingObservedAsHighMedium_on }}</div>
                    </td>
                    <th class="w-20">Risk Rating Observed as High/Medium Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->riskRatingObservedAsHighMedium_comment }}</div>
                    </td>
                </tr>             
                <tr>
                    <th class="w-20">Risk Rating Observed as Low By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->riskRatingObservedLow_by }}</div>
                    </td>
                    <th class="w-20">Risk Rating Observed as Low On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->riskRatingObservedLow_on }}</div>
                    </td>
                    <th class="w-20">Risk Rating Observed as Low Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->riskRatingObservedLow_comment }}</div>
                    </td>
                </tr>             
                <tr>
                    <th class="w-20">Manufacturer Audit Failed By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pendingManufacturerAuditFailed_by }}</div>
                    </td>
                    <th class="w-20">Manufacturer Audit Failed On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pendingManufacturerAuditFailed_on }}</div>
                    </td>
                    <th class="w-20">Manufacturer Audit Failed Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pendingManufacturerAuditFailed_comment }}</div>
                    </td>
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

            </tr>
        </table>
    </footer>

</body>

</html>
