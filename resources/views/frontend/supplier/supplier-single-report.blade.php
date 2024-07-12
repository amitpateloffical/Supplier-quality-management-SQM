<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vidyagxp - Software</title>
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
                        <img src="https://vidyagxp.com/vidyaGxp_logo.png" alt="" class="w-100">
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Supplier No.</strong>
                </td>
                <td class="w-40">
                    {{ Helpers::getDivisionName($data->division_id) }}/SUPPLIER/{{ date('Y') }}/{{ $data->record ? str_pad($data->record, 4, '0', STR_PAD_LEFT) : '' }}
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

                    <tr>
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

                        <th class="w-20">Supplier Product</th>
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
                    </tr>

                    <tr>
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
                    </tr>

                    <tr>
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
                    </tr>

                    <tr>
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
                    </tr>
                </table>
            </div>

            <!-- Supplier Details -->

            <div class="block">
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

                        <th class="w-20">Other Contacts</th>
                        <td class="w-30">
                            @if ($data->other_contacts)
                                {{ Helpers::getDateFormat($data->other_contacts) }}
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
                        </td>
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
                        <th class="w-20">Supplier Website</th>
                        <td class="w-80" colspan="3">
                            @if ($data->suppplier_web_site)
                                {{ $data->suppplier_web_site }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">ISO Certified Date</th>
                        <td class="w-30">
                            @if ($data->iso_certified_date)
                                {{ $data->iso_certified_date }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Supplier Contact</th>
                        <td class="w-30">
                            @if ($data->suppplier_contacts)
                                {{ $data->suppplier_contacts }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Related Non Conformance</th>
                        <td class="w-30">
                            @if ($data->related_non_conformance)
                                {{ $data->related_non_conformance }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Supplier Agreement</th>
                        <td class="w-30">
                            @if ($data->suppplier_agreement)
                                {{ $data->suppplier_agreement }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="w-20">Regulatory History</th>
                        <td class="w-30">
                            @if ($data->regulatory_history)
                                {{ $data->regulatory_history }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Distribution Sites</th>
                        <td class="w-30">
                            @if ($data->distribution_sites)
                                {{ $data->distribution_sites }}
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
                        <th class="w-20">Bussiness History</th>
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

            <!-- Score Card Details -->

            <div class="block">
                <div class="block-head">
                    Score Card Details
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
                        <th class="w-20">Payment Term</th>
                        <td class="w-30">
                            @if ($data->payment_term)
                                {{ $data->payment_term }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Payment Term Weight</th>
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
                        <th class="w-20">On Time Delivery</th>
                        <td class="w-30">
                            @if ($data->ontime_delivery)
                                {{ $data->ontime_delivery }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">On Time Delivery Weight</th>
                        <td class="w-30">
                            @if ($data->ontime_delivery_weight)
                                {{ $data->ontime_delivery_weight }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Supplier Bussiness Planning</th>
                        <td class="w-30">
                            @if ($data->supplier_bussiness_planning)
                                {{ $data->supplier_bussiness_planning }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Supplier Bussiness Planning Weight</th>
                        <td class="w-30">
                            @if ($data->supplier_bussiness_planning_weight)
                                {{ $data->supplier_bussiness_planning_weight }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="w-20">Quality System</th>
                        <td class="w-30">
                            @if ($data->quality_system)
                                {{ $data->quality_system }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Quality System Ranking</th>
                        <td class="w-30">
                            @if ($data->quality_system_ranking)
                                {{ $data->quality_system_ranking }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">CAR Generated</th>
                        <td class="w-30">
                            @if ($data->car_generated_weight)
                                {{ $data->car_generated_weight }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">CAR Generated Weight</th>
                        <td class="w-30">
                            @if ($data->car_generated_weight)
                                {{ $data->car_generated_weight }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Closure Time</th>
                        <td class="w-30">
                            @if ($data->closure_time)
                                {{ $data->closure_time }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Closure Time Weight</th>
                        <td class="w-30">
                            @if ($data->closure_time_weight)
                                {{ $data->closure_time_weight }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">End User Satisfaction</th>
                        <td class="w-30">
                            @if ($data->end_user_satisfaction)
                                {{ $data->end_user_satisfaction }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">End User Satisfaction Weight</th>
                        <td class="w-30">
                            @if ($data->end_user_satisfaction_weight)
                                {{ $data->end_user_satisfaction_weight }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Score Card Score</th>
                        <td class="w-30">
                            @if ($data->scorecard_record)
                                {{ $data->scorecard_record }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Total Score</th>
                        <td class="w-30">
                            @if ($data->total_score)
                                {{ $data->total_score }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Total Available Score</th>
                        <td class="w-30">
                            @if ($data->total_available_score)
                                {{ $data->total_available_score }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Achieved Score</th>
                        <td class="w-30">
                            @if ($data->achieved_score)
                                {{ $data->achieved_score }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                </table>
            </div>
        </div>



        <div class="block">
            <div class="block-head">
                Activity Log
            </div>
            <table>
                <tr>
                    <th class="w-20">Submitted By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->submit_by }}</div>
                    </td>
                    <th class="w-20">Submitted On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->submit_on }}</div>
                    </td>
                    <th class="w-20">Submitted Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->submit_comment }}</div>
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Cancelled By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->cancelled_by }}</div>
                    </td>
                    <th class="w-20">Cancelled On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->cancelled_on }}</div>
                    </td>
                    <th class="w-20">Cancelled Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->cancelled_comment }}</div>
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Pending Qualification By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pending_qualification_by }}</div>
                    </td>
                    <th class="w-20">Pending Qualification On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pending_qualification_on }}</div>
                    </td>
                    <th class="w-20">Pending Qualification Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pending_qualification_comment }}</div>
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Pending Supplier By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pending_supplier_audit_by }}</div>
                    </td>
                    <th class="w-20">Pending Supplier On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pending_supplier_audit_on }}</div>
                    </td>
                    <th class="w-20">Pending Supplier Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pending_supplier_audit_comment }}</div>
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Pending Rejction By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pending_rejection_by }}</div>
                    </td>
                    <th class="w-20">Pending Rejction On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pending_rejection_on }}</div>
                    </td>
                    <th class="w-20">Pending Rejction Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->pending_rejection_comment }}</div>
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Supplier Approved By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->supplier_approved_by }}</div>
                    </td>
                    <th class="w-20">Supplier Approved On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->supplier_approved_on }}</div>
                    </td>
                    <th class="w-20">Supplier Approved Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->supplier_approved_comment }}</div>
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Supplier Approved to Obselete By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->supplier_approved_to_obselete_by }}</div>
                    </td>
                    <th class="w-20">Supplier Approved to Obselete On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->supplier_approved_to_obselete_on }}</div>
                    </td>
                    <th class="w-20">Supplier Approved to Obselete Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->supplier_approved_to_obselete_comment }}</div>
                    </td>
                </tr>

                <tr>
                    <th class="w-20">ReAudit By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->reAudit_by }}</div>
                    </td>
                    <th class="w-20">ReAudit On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->reAudit_on }}</div>
                    </td>
                    <th class="w-20">ReAudit Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->reAudit_comment }}</div>
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Rejected By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->rejectedDueToQuality_by }}</div>
                    </td>
                    <th class="w-20">Rejected On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->rejectedDueToQuality_on }}</div>
                    </td>
                    <th class="w-20">Rejected Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->rejectedDueToQuality_comment }}</div>
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
