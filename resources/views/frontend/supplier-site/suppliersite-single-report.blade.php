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
                    Supplier Site Single Report
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
                    <strong>Supplier Site No.</strong>
                </td>
                <td class="w-40">
                    {{ Helpers::getDivisionName($data->division_id) }}/SS/{{ date('Y') }}/{{ $data->record ? str_pad($data->record, 4, '0', STR_PAD_LEFT) : '' }}
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
                    Supplier/Manufacturer/Vendor
                </div>
                <table>
                    <tr>
                        <th class="w-20">Initiator</th>
                        <td class="w-30">{{ $data->originator }}</td>

                        <th class="w-20">Initiation Date</th>
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

                    <tr>
                        <th class="w-20">Supplier</th>
                        <td class="w-30">
                            @if ($data->supplier_person)
                                {{ Helpers::getInitiatorName($data->supplier_person) }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class="w-20">Assigned To</th>
                        <td class="w-30">
                            @if ($data->assign_to)
                                {{ Helpers::getInitiatorName($data->assign_to) }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Contact Person</th>
                        <td class="w-30">
                            @if ($data->supplier_contact_person)
                                {{ Helpers::getInitiatorName($data->supplier_contact_person) }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Suppliers Products</th>
                        <td class="w-30">
                            @if ($data->supplier_products)
                                {{ $data->supplier_products }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Description</th>
                        <td class="w-80" colspan="3">
                            @if ($data->description)
                                {{ $data->description }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Type</th>
                        <td class="w-30">
                            @if ($data->supplier_type)
                                {{ $data->supplier_type }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Sub Type</th>
                        <td class="w-30">
                            @if ($data->supplier_sub_type)
                                {{ $data->supplier_sub_type }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Other Type</th>
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

                    <tr>
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
                    </tr>

                    <tr>
                        <th class="w-20">Web Search</th>
                        <td class="w-30">
                            @if ($data->supplier_web_search)
                                {{ $data->supplier_web_search }}
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
                    </tr>

                    <tr>
                        <th class="w-20">Related Quality Events</th>
                        <td class="w-30">
                            @if ($data->related_quality_events)
                                {{ $data->related_quality_events }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                    </tr>


                   

                </table>
            </div>

            <div class="border-table">
                <div class="block-head">
                    Logo Attachments
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">Attachment</th>
                    </tr>
                    @if ($data->logo_attachment)
                        @foreach (json_decode($data->logo_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a>
                                </td>
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
                    File Attachments
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">Attachment</th>
                    </tr>
                    @if ($data->supplier_attachment)
                        @foreach (json_decode($data->supplier_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a>
                                </td>
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
                    @if ($data->gi_additional_attachment)
                        @foreach (json_decode($data->gi_additional_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a>
                                </td>
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

            <!-- HOD Details -->

            <div class="block">
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
                                        target="_blank"><b>{{ $file }}</b></a>
                                </td>
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
                  HOD Additional Attachments
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
                                        target="_blank"><b>{{ $file }}</b></a>
                                </td>
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

            <div class="block">
                <div class="block-head">
                    Supplier Details
                </div>
                <div class="border-table">
                    <table>
                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-20">Type</th>
                            <th class="w-20">Issuing Agency</th>
                            <th class="w-20">Issue Date</th>
                            <th class="w-20">Expiry Date</th>
                            <th class="w-20">Supporting Document</th>
                            <th class="w-20">Remarks</th>
                        </tr>
                        @php
                            $data = isset($gridData) && $gridData->data ? json_decode($gridData->data) : null;
                        @endphp

                        @if ($data)
                            @if (is_array($data) || is_object($data))
                                @foreach ($data as $index => $item)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}.</td>
                                        <td>{{ isset($item->type) ? $item->type : (is_array($item) && isset($item['type']) ? $item['type'] : '') }}
                                        </td>
                                        <td>{{ isset($item->issuingAgency) ? $item->issuingAgency : (is_array($item) && isset($item['issuingAgency']) ? $item['issuingAgency'] : '') }}
                                        </td>
                                        <td>

                                            @php
                                                $issueDate = isset($item->issueDate)
                                                    ? $item->issueDate
                                                    : (is_array($item) && isset($item['issueDate'])
                                                        ? $item['issueDate']
                                                        : null);
                                            @endphp
                                            {{ $issueDate ? \Carbon\Carbon::parse($issueDate)->format('d-M-Y') : '' }}

                                            {{-- {{ \Carbon\Carbon::parse(isset($item->issueDate) ? $item->issueDate : (is_array($item) && isset($item['issueDate']) ? $item['issueDate'] : ''))->format('d-M-Y') }} --}}
                                        </td>
                                        <td>

                                            @php
                                                $expiryDate = isset($item->expiryDate)
                                                    ? $item->expiryDate
                                                    : (is_array($item) && isset($item['expiryDate'])
                                                        ? $item['expiryDate']
                                                        : null);
                                            @endphp
                                            {{ $expiryDate ? \Carbon\Carbon::parse($expiryDate)->format('d-M-Y') : '' }}

                                            {{-- {{ \Carbon\Carbon::parse(isset($item->expiryDate) ? $item->expiryDate : (is_array($item) && isset($item['expiryDate']) ? $item['expiryDate'] : ''))->format('d-M-Y') }} --}}
                                        </td>
                                        <td>{{ isset($item->supportingDoc) ? $item->supportingDoc : (is_array($item) && isset($item['supportingDoc']) ? $item['supportingDoc'] : '') }}
                                        </td>
                                        <td>{{ isset($item->remarks) ? $item->remarks : (is_array($item) && isset($item['remarks']) ? $item['remarks'] : '') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>Not Applicable</td>
                                    <td>Not Applicable</td>
                                    <td>Not Applicable</td>
                                    <td>Not Applicable</td>
                                    <td>Not Applicable</td>
                                    <td>Not Applicable</td>
                                    <td>Not Applicable</td>
                                </tr>
                            @endif
                        @else
                            <tr>
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


                <table>
                    <tr>
                        <th class="w-20">Supplier</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData))
                                {{ isset($supplierData->supplier_name) && $supplierData->supplier_name ? $supplierData->supplier_name : 'Not Applicable' }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Supplier ID</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData))
                                {{ isset($supplierData->supplier_id) && $supplierData->supplier_id ? $supplierData->supplier_id : 'Not Applicable' }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                    </tr>
                    <tr>
                        <th class="w-20">Manufacturer</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData))
                                {{ isset($supplierData->manufacturer_name) && $supplierData->manufacturer_name ? $supplierData->manufacturer_name : 'Not Applicable' }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Manufacturer ID</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData))
                                {{ isset($supplierData->manufacturer_id) && $supplierData->manufacturer_id ? $supplierData->manufacturer_id : 'Not Applicable' }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Vendor</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData))
                                {{ isset($supplierData->vendor_name) && $supplierData->vendor_name ? $supplierData->vendor_name : 'Not Applicable' }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                        <th class="w-20">Vendor ID</th>
                        <td class="w-30">
                            @if (is_object($supplierData) && !empty($supplierData->vendor_id))
                                {{ $supplierData->vendor_id }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Contact Person</th>
                        <td class="w-30">
                            @if (is_object($supplierData) && !empty($supplierData->contact_person))
                                {{ $supplierData->contact_person }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                        <th class="w-20">Other Contacts</th>
                        <td class="w-30">
                            @if (is_object($supplierData) && !empty($supplierData->other_contacts))
                                {{ $supplierData->other_contacts }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Supplier Services</th>
                        <td class="w-80" colspan="3">
                            @if (is_object($supplierData) && !empty($supplierData->supplier_serivce))
                                {{ $supplierData->supplier_serivce }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                    </tr>
                    <tr>
                        <th class="w-20">Zone</th>
                        <td class="w-30">
                            @if (is_object($supplierData) && !empty($supplierData->zone))
                                {{ $supplierData->zone }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                        <th class="w-20">Country</th>
                        <td class="w-30">
                            @if (is_object($supplierData) && !empty($supplierData->country))
                                {{ $supplierData->country }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">State</th>
                        <td class="w-30">
                            @if (is_object($supplierData) && !empty($supplierData->state))
                                {{ $supplierData->state }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                        <th class="w-20">City</th>
                        <td class="w-30">
                            @if (is_object($supplierData) && !empty($supplierData->city))
                                {{ $supplierData->city }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Address</th>
                        <td class="w-80" colspan="3">
                            @if (is_object($supplierData) && !empty($supplierData->address))
                                {{ $supplierData->address }}
                            @else
                                Not Applicable
                            @endif


                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Supplier Website</th>
                        <td class="w-80" colspan="3">
                            @if (is_object($supplierData) && !empty($supplierData->suppplier_web_site))
                                {{ $supplierData->suppplier_web_site }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">ISO Certification Date</th>
                        <td class="w-30">
                            @if (is_object($supplierData) && !empty($supplierData->iso_certified_date))
                                {{ \Carbon\Carbon::parse($supplierData->iso_certified_date)->format('d-M-Y') }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Contracts</th>
                        <td class="w-30">
                            @if (is_object($supplierData) && !empty($supplierData->suppplier_contacts))
                                {{ $supplierData->suppplier_contacts }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Related Non Conformances</th>
                        <td class="w-30">
                            @if (is_object($supplierData) && !empty($supplierData->related_non_conformance))
                                {{ $supplierData->related_non_conformance }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Supplier Contracts/Agreements</th>
                        <td class="w-30">
                            @if (is_object($supplierData) && !empty($supplierData->suppplier_agreement))
                                {{ $supplierData->suppplier_agreement }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Regulatory History</th>
                        <td class="w-30">
                            @if (is_object($supplierData) && !empty($supplierData->regulatory_history))
                                {{ $supplierData->regulatory_history }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Distribution Sites</th>
                        <td class="w-30">
                            @if (is_object($supplierData) && !empty($supplierData->distribution_sites))
                                {{ $supplierData->distribution_sites }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Manufacturing Sites</th>
                        <td class="w-80" colspan="3">
                            @if (is_object($supplierData) && !empty($supplierData->manufacturing_sited))
                                {{ $supplierData->manufacturing_sited }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>


                    <tr>
                        <th class="w-20">Quality Management</th>
                        <td class="w-80" colspan="3">
                            @if (is_object($supplierData) && !empty($supplierData->quality_management))
                                {{ $supplierData->quality_management }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Business History</th>
                        <td class="w-80" colspan="3">
                            @if (is_object($supplierData) && !empty($supplierData->bussiness_history))
                                {{ $supplierData->bussiness_history }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Performance History</th>
                        <td class="w-80" colspan="3">
                            @if (is_object($supplierData) && !empty($supplierData->performance_history))
                                {{ $supplierData->performance_history }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Compliance Risk</th>
                        <td class="w-80" colspan="3">
                            @if (is_object($supplierData) && !empty($supplierData->compliance_risk))
                                {{ $supplierData->compliance_risk }}
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
                    @if ($supplierData && is_object($supplierData) && !empty($supplierData->iso_certificate_attachment))
                        @foreach (json_decode($supplierData->iso_certificate_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-60">
                                    <a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="w-20">1</td>
                            <td class="w-60">Not Applicable</td>
                        </tr>
                    @endif
                </table>
            </div>


            <div class="border-table">
                <div class="block-head">
                    Supplier Details Additional Attachments
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">Attachment</th>
                    </tr>
                    @if ($supplierData && is_object($supplierData) && !empty($supplierData->supplier_detail_additional_attachment))
                        @foreach (json_decode($supplierData->supplier_detail_additional_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-60">
                                    <a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="w-20">1</td>
                            <td class="w-60">Not Applicable</td>
                        </tr>
                    @endif
                </table>
            </div>




            <!-- Score Card Details -->

            <div class="block">
                <div class="block-head">
                    Score Card Details
                </div>
                <table>
                    <tr>
                        <th class="w-20">Cost Reduction</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->cost_reduction))
                                {{ $supplierData->cost_reduction }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20">Cost Reduction Weight</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->cost_reduction_weight))
                                {{ $supplierData->cost_reduction_weight }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Payment Terms</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->payment_term))
                                {{ $supplierData->payment_term }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20">Payment Terms Weight</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->payment_term_weight))
                                {{ $supplierData->payment_term_weight }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Lead Time Days</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->lead_time_days))
                                {{ $supplierData->lead_time_days }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20">Lead Time Days Weight</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->lead_time_days_weight))
                                {{ $supplierData->lead_time_days_weight }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">On-Time Delivery</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->ontime_delivery))
                                {{ $supplierData->ontime_delivery }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">On-Time Delivery Weight</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->ontime_delivery_weight))
                                {{ $supplierData->ontime_delivery_weight }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Supplier Business Planning</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->supplier_bussiness_planning))
                                {{ $supplierData->supplier_bussiness_planning }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20">Supplier Business Weight</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->supplier_bussiness_planning_weight))
                                {{ $supplierData->supplier_bussiness_planning_weight }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>

                        <th class="w-20">Rejection in PPM</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->rejection_ppm))
                                {{ $supplierData->rejection_ppm }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20">Rejection in PPM Weight</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->rejection_ppm_weight))
                                {{ $supplierData->rejection_ppm_weight }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                    </tr>

                    <tr>
                        <th class="w-20">Quality Systems</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->quality_system))
                                {{ $supplierData->quality_system }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20">Quality Systems Weight</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->quality_system_ranking))
                                {{ $supplierData->quality_system_ranking }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20"># of CAR's generated</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->car_generated))
                                {{ $supplierData->car_generated }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20"># of CAR's generated Weight</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->car_generated_weight))
                                {{ $supplierData->car_generated_weight }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">CAR Closure Time</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->closure_time))
                                {{ $supplierData->closure_time }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20">CAR Closure Time Weight</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->closure_time_weight))
                                {{ $supplierData->closure_time_weight }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">End-User Satisfaction</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->end_user_satisfaction))
                                {{ $supplierData->end_user_satisfaction }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20">End-User Satisfaction Weight</th>
                        <td class="w-30">
                            @if ($supplierData && is_object($supplierData) && !empty($supplierData->end_user_satisfaction_weight))
                                {{ $supplierData->end_user_satisfaction_weight }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>
                </table>
            </div>

            <div class="border-table">
                <div class="block-head">
                    Score Card Additional Attachments
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">Attachment</th>
                    </tr>
                    @if ($supplierData && !empty($supplierData->score_card_additional_attachment))
                        @foreach (json_decode($supplierData->score_card_additional_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-60">
                                    <a href="{{ asset('upload/' . $file) }}" target="_blank">
                                        <b>{{ $file }}</b>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="w-20">1</td>
                            <td class="w-60">Not Applicable</td>
                        </tr>
                    @endif
                </table>
            </div>

            <!-- QA Reviewer Detail -->

            <div class="block">
                <div class="block-head">
                    QA Reviewer Detail
                </div>
                <table>
                    <tr>
                        <th class="w-20">QA Reviewer Feedback</th>
                        <td class="w-80" colspan="3">
                            @if (!empty($supplierData->QA_reviewer_feedback))
                                {{ $supplierData->QA_reviewer_feedback }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">QA Reviewer Comment</th>
                        <td class="w-80" colspan="3">
                            @if (!empty($supplierData->QA_reviewer_comment))
                                {{ $supplierData->QA_reviewer_comment }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>
                </table>
            </div>

            <div class="border-table">
                <div class="block-head">
                    QA Reviewer Attachments
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">Attachment</th>
                    </tr>
                    @if (!empty($supplierData->QA_reviewer_attachment))
                        @foreach (json_decode($supplierData->QA_reviewer_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-60">
                                    <a href="{{ asset('upload/' . $file) }}" target="_blank">
                                        <b>{{ $file }}</b>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="w-20">1</td>
                            <td class="w-60">Not Applicable</td>
                        </tr>
                    @endif
                </table>
            </div>


            <div class="border-table">
                <div class="block-head">
                    QA Reviewer Additional Attachments
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">Attachment</th>
                    </tr>
                    @if (!empty($supplierData->qa_reviewer_additional_attachment))
                        @foreach (json_decode($supplierData->qa_reviewer_additional_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-60">
                                    <a href="{{ asset('upload/' . $file) }}" target="_blank">
                                        <b>{{ $file }}</b>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="w-20">1</td>
                            <td class="w-60">Not Applicable</td>
                        </tr>
                    @endif
                </table>
            </div>


            <!-- Risk Assessment Details -->

            <div class="block">
                <div class="block-head">
                    Risk Assessment Details
                </div>
                <table>
                    <tr>
                        <th class="w-20">Last Audit Date</th>
                        <td class="w-30">
                            @if (!empty($supplierData->last_audit_date))
                                {{ Helpers::getDateFormat($supplierData->last_audit_date) }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20">Next Audit Date</th>
                        <td class="w-30">
                            @if (!empty($supplierData->next_audit_date))
                                {{ Helpers::getDateFormat($supplierData->next_audit_date) }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Audit Frequency</th>
                        <td class="w-30">
                            @if (!empty($supplierData->audit_frequency))
                                {{ $supplierData->audit_frequency }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                        <th class="w-20">Last Audit Result</th>
                        <td class="w-30">
                            @if (!empty($supplierData->last_audit_result))
                                {{ $supplierData->last_audit_result }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Facility Type</th>
                        <td class="w-30">
                            @if (!empty($supplierData->facility_type))
                                {{ $supplierData->facility_type }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20">Number of Employees</th>
                        <td class="w-30">
                            @if (!empty($supplierData->nature_of_employee))
                                {{ $supplierData->nature_of_employee }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Access to Technical Support</th>
                        <td class="w-30">
                            @if (!empty($supplierData->technical_support))
                                {{ $supplierData->technical_support }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20">Services Supported</th>
                        <td class="w-30">
                            @if (!empty($supplierData->survice_supported))
                                {{ $supplierData->survice_supported }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Reliability</th>
                        <td class="w-30">
                            @if (!empty($supplierData->reliability))
                                {{ $supplierData->reliability }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20">Revenue</th>
                        <td class="w-30">
                            @if (!empty($supplierData->revenue))
                                {{ $supplierData->revenue }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Client Base</th>
                        <td class="w-30">
                            @if (!empty($supplierData->client_base))
                                {{ $supplierData->client_base }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                        <th class="w-20">Previous Audit Results</th>
                        <td class="w-30">
                            @if (!empty($supplierData->previous_audit_result))
                                {{ $supplierData->previous_audit_result }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>
                </table>
            </div>

            <div class="border-table">
                <div class="block-head">
                   Risk Assessment Additional Attachments
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">Attachment</th>
                    </tr>
                    @if (!empty($supplierData->risk_assessment_additional_attachment))
                        @foreach (json_decode($supplierData->risk_assessment_additional_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-60"><a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="w-20">1</td>
                            <td class="w-60">Not Applicable</td>
                        </tr>
                    @endif
                </table>
            </div>

            <!-- QA Head Reviewer Detail -->

            <div class="block">
                <div class="block-head">
                    QA Head Reviewer Detail
                </div>
                <table>
                    <tr>
                        <th class="w-20">QA Head Comment</th>
                        <td class="w-80" colspan="3">
                            @if (!empty($supplierData->QA_head_comment))
                                {{ $supplierData->QA_head_comment }}
                            @else
                                Not Applicable
                            @endif

                        </td>
                    </tr>
                </table>
            </div>

            <div class="border-table">
                <div class="block-head">
                    QA Head Attachments
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">Attachment</th>
                    </tr>
                    @if (!empty($supplierData->QA_head_attachment))
                        @foreach (json_decode($supplierData->QA_head_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-60"><a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="w-20">1</td>
                            <td class="w-60">Not Applicable</td>
                        </tr>
                    @endif
                </table>
            </div>

            <div class="border-table">
                <div class="block-head">
                    QA Head Additional Attachments
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">Attachment</th>
                    </tr>
                    @if (!empty($supplierData->qa_head_additional_attachment))
                        @foreach (json_decode($supplierData->qa_head_additional_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-60"><a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="w-20">1</td>
                            <td class="w-60">Not Applicable</td>
                        </tr>
                    @endif
                </table>
            </div>




            <div class="block">
                <div class="block-head">
                    Activity Log
                </div>
                <table>
                    <tr>
                        <th class="w-20">Submit Supplier Details By</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->submitted_by ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Submit Supplier Details On</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->submitted_on ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Submit Supplier Details Comment</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->submitted_comment ?? 'Not Applicable' }}</div>
                        </td>

                    </tr>

                    <tr>
                        <th class="w-20">Cancelled By</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->cancelled_by ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Cancelled On</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->cancelled_on ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Cancelled Comment</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->cancelled_comment ?? 'Not Applicable' }}</div>
                        </td>

                    </tr>

                    <tr>
                        <th class="w-20">Qualification Complete By</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->pending_qualification_by ?? 'Not Applicable' }}
                            </div>
                        </td>
                        <th class="w-20">Qualification Complete on</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->pending_qualification_on ?? 'Not Applicable' }}
                            </div>
                        </td>
                        <th class="w-20">Qualification Complete Comment</th>
                        <td class="w-30">
                            <div class="static">
                                {{ $supplierData->pending_qualification_comment ?? 'Not Applicable' }}
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Audit Passed By</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->supplier_approved_by ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Audit Passed On</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->supplier_approved_on ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Audit Passed Comment</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->supplier_approved_comment ?? 'Not Applicable' }}
                            </div>
                        </td>

                    </tr>


                    <tr>
                        <th class="w-20">Audit Failed By</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->pending_supplier_audit_by ?? 'Not Applicable' }}
                            </div>
                        </td>
                        <th class="w-20">Audit Failed On</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->pending_supplier_audit_on ?? 'Not Applicable' }}
                            </div>
                        </td>
                        <th class="w-20">Audit Failed Comment</th>
                        <td class="w-30">
                            <div class="static">
                                {{ $supplierData->pending_supplier_audit_comment ?? 'Not Applicable' }}
                            </div>
                        </td>

                    </tr>

                    <tr>
                        <th class="w-20">Conditionally Approved By</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->conditionally_approved_by ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Conditionally Approved On</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->conditionally_approved_on ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Conditionally Approved Comment</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->conditionally_approved_comments ?? 'Not Applicable' }}
                            </div>
                        </td>

                    </tr>


                    <tr>
                        <th class="w-20">Supplier Approved to Obsolete By</th>
                        <td class="w-30">
                            <div class="static">
                                {{ $supplierData->supplier_approved_to_obselete_by ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Supplier Approved to Obsolete On</th>
                        <td class="w-30">
                            <div class="static">
                                {{ $supplierData->supplier_approved_to_obselete_on ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Supplier Approved to Obsolete Comment</th>
                        <td class="w-30">
                            <div class="static">
                                {{ $supplierData->supplier_approved_to_obselete_comment ?? 'Not Applicable' }}</div>
                        </td>

                    </tr>
                    <tr>
                        <th class="w-20">Reject Due To Quality Issues By</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->rejectedDueToQuality_by ?? 'Not Applicable' }}
                            </div>
                        </td>
                        <th class="w-20">Reject Due To Quality Issues On</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->rejectedDueToQuality_on ?? 'Not Applicable' }}
                            </div>
                        </td>
                        <th class="w-20">Reject Due To Quality Issues Comment</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->rejectedDueToQuality_comment ?? 'Not Applicable' }}
                            </div>
                        </td>

                    </tr>

                    <tr>
                        <th class="w-20">Pending Rejction to Supplier Obsolete By</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->pending_rejection_by ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Pending Rejction to Supplier Obsolete On</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->pending_rejection_on ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Pending Rejction to Supplier Obsolete
                            Comment</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->pending_rejection_comment ?? 'Not Applicable' }}
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <th class="w-20">Re-Audit By</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->reAudit_by ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Re-Audit On</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->reAudit_on ?? 'Not Applicable' }}</div>
                        </td>
                        <th class="w-20">Re-Audit Comment</th>
                        <td class="w-30">
                            <div class="static">{{ $supplierData->reAudit_comment ?? 'Not Applicable' }}</div>
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
