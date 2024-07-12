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
                    SCAR Report
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
                    <strong>SCAR No.</strong>
                </td>
                <td class="w-40">
                    {{ Helpers::getDivisionName($data->division_id) }}/SCAR/{{ date('Y') }}/{{ $data->record ? str_pad($data->record, 4, '0', STR_PAD_LEFT) : '' }}
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
                        <td class="w-30">{{ Helpers::getDateFormat($data->initiation_date) }}</td>
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

                        <th class="w-20">Assign To</th>
                        <td class="w-30">
                            @if ($data->assign_to)
                                {{ Helpers::getInitiatorName($data->assign_to) }}
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
                        <th class="w-20">SCAR Name</th>
                        <td class="w-30">
                            @if ($data->scar_name)
                                {{ $data->scar_name }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Owner Name</th>
                        <td class="w-30">
                            @if ($data->owner_name)
                                {{ $data->owner_name}}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">FollowUp Date</th>
                        <td class="w-30">
                            @if ($data->followup_date)
                                {{ Helpers::getdateFormat($data->followup_date) }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Supplier Site</th>
                        <td class="w-30">
                            @if ($data->supplier_site)
                                {{ $data->supplier_site}}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Supplier Product</th>
                        <td class="w-30">
                            @if ($data->supplier_product)
                                {{ $data->supplier_product }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Supplier Site Contact Email</th>
                        <td class="w-30">
                            @if ($data->supplier_site_contact_email)
                                {{ $data->supplier_site_contact_email}}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Recommended Action</th>
                        <td class="w-80" colspan="3">
                            @if ($data->recommended_action)
                                {{ $data->recommended_action }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Non Conformance</th>
                        <td class="w-80" colspan="3">
                            @if ($data->non_conformance)
                                {{ $data->non_conformance }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Expected Closure Date</th>
                        <td class="w-30">
                            @if ($data->expected_closure_date)
                                {{ Helpers::getdateFormat($data->expected_closure_date) }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Expected Closure Time</th>
                        <td class="w-30">
                            @if ($data->expected_closure_time)
                                {{ $data->expected_closure_time}}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    
                    <tr>
                        <th class="w-20">Root Cause</th>
                        <td class="w-80" colspan="3">
                            @if ($data->root_cause)
                                {{ $data->root_cause }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>


                    <tr>
                        <th class="w-20">Risk Analysis</th>
                        <td class="w-80" colspan="3">
                            @if ($data->risk_analysis)
                                {{ $data->risk_analysis }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Effectiveness Check Summary</th>
                        <td class="w-80" colspan="3">
                            @if ($data->effectiveness_check_summary)
                                {{ $data->effectiveness_check_summary }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">CAPA Plan</th>
                        <td class="w-80" colspan="3">
                            @if ($data->capa_plan)
                                {{ $data->capa_plan }}
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
                    <th class="w-20">Acknowledge By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->acknowledge_by }}</div>
                    </td>
                    <th class="w-20">Acknowledge On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->acknowledge_on }}</div>
                    </td>
                    <th class="w-20">Acknowledge Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->acknowledge_comment }}</div>
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Work in Progress By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->workin_progress_by }}</div>
                    </td>
                    <th class="w-20">Work in Progress On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->workin_progress_on }}</div>
                    </td>
                    <th class="w-20">Work in Progress Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->workin_progress_comment }}</div>
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Response Submitted By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->response_submitted_by }}</div>
                    </td>
                    <th class="w-20">Response Submitted On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->response_submitted_on }}</div>
                    </td>
                    <th class="w-20">Response Submitted Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->response_submitted_comment }}</div>
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Rejected By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->rejected_by }}</div>
                    </td>
                    <th class="w-20">Rejected On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->rejected_on }}</div>
                    </td>
                    <th class="w-20">Rejected Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->rejected_comment }}</div>
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Approved By</th>
                    <td class="w-30">
                        <div class="static">{{ $data->approved_by }}</div>
                    </td>
                    <th class="w-20">Approved On</th>
                    <td class="w-30">
                        <div class="static">{{ $data->approved_on }}</div>
                    </td>
                    <th class="w-20">Approved Comment</th>
                    <td class="w-30">
                        <div class="static">{{ $data->approved_comment }}</div>
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
