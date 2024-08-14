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
        table-layout: fixed;
        /* Fix the table layout to ensure all columns are shown */
        border-collapse: collapse;

    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    .border-table table,
    .border-table th,
    .border-table td {
        font-size: 0.7rem;

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
                <td class="w-70 head"> Observation Single Report </td>
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
                    <strong>Observation No.</strong>
                </td>
                <td class="w-40">
                    {{ Helpers::getDivisionName($data->division_id) }}/OBS/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="w-30">
                    <strong>Record No.</strong> {{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
        </table>
    </header>
    <footer>
        <table>
            <tr>
                <td class="w-50">
                    <strong>Printed On :</strong> {{ date('d-M-Y') }}
                </td>
                <td class="w-50">
                    <strong>Printed By :</strong> {{ Auth::user()->name }}
                </td>
                {{-- <td class="w-30">
                    <strong>Page :</strong>
                    1 of 1
                </td> --}}
            </tr>
        </table>
    </footer>

    <div class="inner-block">
        <div class="content-table">
            <div class="block">
                <div class="block-head"> General Information </div>
                <table>
                    <tr>
                        <th class="w-20">Initiator</th>
                        <td class="w-30">{{ $data->originator }}</td>
                        <th class="w-20">Date Initiation</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->created_at) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Site/Location Code</th>
                        <td class="w-30">
                            @if ($data->division_id)
                                {{-- {{ Helpers::getDivisionName($data->division_code) }} --}}
                                {{-- {{ Helpers::getDivisionName(session()->get('division')) }} --}}
                                {{ Helpers::getDivisionName($data->division_id) }}
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
                        <th class="w-20">Due Date</th>
                        <td class="w-30">
                            @if ($data->due_date)
                                {{ \Carbon\Carbon::parse($data->due_date)->format('d-M-Y') }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class="w-20">Record Number</th>
                        <td class="w-30">
                            @if ($data->record)
                                {{ Helpers::getDivisionName($data->division_id) }}/OBS/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
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
                        {{-- <th class="w-20">Attached Files</th>
                        <td class="w-30">
                            @if ($data->attach_files1)
                                <a href="{{ asset('upload/document/', $data->attach_files1) }}">
                                    {{ $data->attach_files1 }} </a>
                            @else
                                Not Applicable
                            @endif
                        </td> --}}
                        <th class="w-20">Recomendation Due Date for CAPA</th>
                        <td class="w-30">
                            @if ($data->recomendation_capa_date_due)
                                {{ $data->recomendation_capa_date_due }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Non Compliance</th>
                        <td class="w-80" colspan="3">
                            @if ($data->non_compliance)
                                {{ $data->non_compliance }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Recommended Action</th>
                        <td class="w-80" colspan="3">
                            @if ($data->recommend_action)
                                {{ $data->recommend_action }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    {{-- <tr>
                        <th class="w-20">Related Obsevations</th>
                        <td class="w-80">
                            @if ($data->related_observations)
                                {{ $data->related_observations }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr> --}}
                </table>
                <div class="block-head"> Attached Files </div>
                <div class="border-table">
                    <table>
                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">File </th>
                        </tr>
                        @if ($data->attach_files1)
                            @foreach (json_decode($data->attach_files1) as $key => $file)
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
                <div class="block-head"> Related Observations </div>
                <div class="border-table">
                    <table>
                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">File </th>
                        </tr>
                        @if ($data->related_observations)
                            @foreach (json_decode($data->related_observations) as $key => $file)
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

            <div class="block">
                <div class="head">
                    <div class="block-head"> CAPA Plan Details </div>
                    <table>
                        <tr>
                            <th class="w-20">Date Response Due</th>
                            <td class="w-30">
                                @if ($data->date_Response_due2)
                                    {{ $data->date_Response_due2 }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">Due Date.</th>
                            <td class="w-30">
                                @if ($data->capa_date_due)
                                    {{ $data->capa_date_due }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Assigned To.</th>
                            <td class="w-30">
                                @if ($data->assign_to2)
                                    {{ Helpers::getInitiatorName($data->assign_to2) }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <th class="w-20">Comments</th>
                            <td class="w-80">
                                @if ($data->comments)
                                    {{ $data->comments }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                    </table>
                </div>
            </div>

            <div class="block">
                <div class="head">
                    <div class="block-head">Impact Analysis </div>
                    <table>
                        <tr>
                            <th class="w-20">Impact</th>
                            <td class="w-80">
                                @if ($data->impact)
                                    {{ $data->impact }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Impact Analysis</th>
                            <td class="w-80">
                                @if ($data->impact_analysis)
                                    {{ $data->impact_analysis }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="block">
                <div class="head">
                    <div class="block-head"> Risk Analysis </div>
                    <table>
                        <tr>
                            <th class="w-20">Severity Rate</th>
                            <td class="w-30">
                                @if ($data->severity_rate)
                                    @if ($data->severity_rate == 1)
                                        Negligible
                                    @elseif($data->severity_rate == 2)
                                        Moderate
                                    @elseif($data->severity_rate == 3)
                                        Major
                                    @else
                                        Fatal
                                    @endif
                                @else
                                    Not Applicable
                                @endif

                            </td>

                            <th class="w-20">Occurrence</th>
                            <td class="w-30">
                                @if ($data->occurrence)
                                    @if ($data->occurrence == 1)
                                        Very Likely
                                    @elseif($data->occurrence == 2)
                                        Likely
                                    @elseif($data->occurrence == 3)
                                        Unlikely
                                    @elseif($data->occurrence == 4)
                                        Rare
                                    @else
                                        Extremely Unlikely
                                    @endif
                                @else
                                    Not Applicable
                                @endif

                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Detection</th>
                            <td class="w-30">
                                @if ($data->detection)
                                    @if ($data->detection == 1)
                                        Very Likely
                                    @elseif($data->detection == 2)
                                        Likely
                                    @elseif($data->detection == 3)
                                        Unlikely
                                    @elseif($data->detection == 4)
                                        Rare
                                    @else
                                        Impossible
                                    @endif
                                @else
                                    Not Applicable
                                @endif

                            </td>
                            <th class="w-20">RPN</th>
                            <td class="w-30" colspan="3">
                                @if ($data->analysisRPN)
                                    {{ $data->analysisRPN }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="block">
                <div class="head">
                    <div class="block-head"> Action Summary </div>
                    <table>
                        <tr>
                            <th class="w-20">Actual Start Date</th>
                            <td class="w-30">
                                @if ($data->actual_start_date)
                                    {{ \Carbon\Carbon::parse($data->actual_start_date)->format('d-M-Y') }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">Actual End Date</th>
                            <td class="w-30">
                                @if ($data->actual_end_date)
                                    {{ \Carbon\Carbon::parse($data->actual_end_date)->format('d-M-Y') }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <th class="w-20">Action Taken</th>
                            <td class="w-80">
                                @if ($data->action_taken)
                                    {{ $data->action_taken }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="block">
                <div class="head">
                    <div class="block-head"> Response Summary </div>
                    <div class="block-head">Attachment</div>
                    <div class="border-table">
                        <table>
                            <tr class="table_bg">
                                <th class="w-20">S.N.</th>
                                <th class="w-60">File </th>
                            </tr>
                            @if ($data->attach_files2)
                                @foreach (json_decode($data->attach_files2) as $key => $file)
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
                    <table>
                        {{-- <tr>
                            <th class="w-20">Attached Files</th>
                            <td class="w-80">
                                @if ($data->attach_files2)
                                    {{ $data->attach_files2 }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr> --}}
                        <tr>
                            <th class="w-20">Related URL</th>
                            <td class="w-80">
                                @if ($data->related_url)
                                    {{ $data->related_url }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">Response Summary</th>
                            <td class="w-80">
                                @if ($data->response_summary)
                                    {{ $data->response_summary }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="block">
                <div class="head">
                    <div class="block-head">
                        Action Plan
                    </div>
                    <div class="border-table">
                        <table style="margin-top: 20px; width: 100%; table-layout: fixed;">
                            <tr class="table_bg">

                                <th style="width: 10%">Row#</th>
                                <th>Remarks</th>
                                <th>Responsible</th>
                                <th>Deadline</th>
                                <th>Item Status</th>

                            </tr>

                            <tbody>
                                @foreach (unserialize($griddata->action) as $key => $temps)
                                    <tr>
                                        <td class="w-15">
                                            {{ $key + 1 }}
                                        </td>
                                        <td class="w-15">
                                            {{ unserialize($griddata->action)[$key] ? unserialize($griddata->action)[$key] : 'N/A' }}
                                        </td>

                                        <td class="w-15">
                                            {{ unserialize($griddata->responsible)[$key] ? Helpers::getInitiatorName(unserialize($griddata->responsible)[$key]) : 'N/A' }}
                                        </td>

                                        <td class="w-15">
                                            {{ unserialize($griddata->deadline)[$key] ? Helpers::getdateFormat(unserialize($griddata->deadline)[$key]) : 'N/A' }}
                                        </td>
                                        <td class="w-15">
                                            {{ unserialize($griddata->item_status)[$key] ? unserialize($griddata->item_status)[$key] : 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <div class="block">
                <div class="head">
                    <div class="block-head"> Activity Log </div>
                    <table>
                        <tr>
                            <th class="w-20">Report Issued By</th>
                            <td class="w-30">
                                @if ($data->Report_Issued_By)
                                    {{ $data->Report_Issued_By }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">Report Issued On</th>
                            <td class="w-30">
                                @if ($data->Report_Issued_On)
                                    {{ $data->Report_Issued_On }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Report Issued Comment</th>
                            <td class="w-80">
                                @if ($data->Report_Issued_Comment)
                                    {{ $data->Report_Issued_Comment }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Cancelled By</th>
                            <td class="w-30">
                                @if ($data->Cancelled_By)
                                    {{ $data->Cancelled_By }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">Cancelled On</th>
                            <td class="w-30">
                                @if ($data->Cancelled_On)
                                    {{ $data->Cancelled_On }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Cancelled Comment</th>
                            <td class="w-80">
                                @if ($data->Cancelled_Comment)
                                    {{ $data->Cancelled_Comment }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Completed By</th>
                            <td class="w-30">
                                @if ($data->Completed_By)
                                    {{ $data->Completed_By }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">Completed On</th>
                            <td class="w-30">
                                @if ($data->completed_on)
                                    {{ $data->completed_on }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Completed Comment</th>
                            <td class="w-80">
                                @if ($data->Completed_Comment)
                                    {{ $data->Completed_Comment }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">QA Approved By</th>
                            <td class="w-30">
                                @if ($data->QA_Approved_By)
                                    {{ $data->QA_Approved_By }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">QA Approved On</th>
                            <td class="w-30">
                                @if ($data->QA_Approved_on)
                                    {{ $data->QA_Approved_on }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">QA Approved Comment</th>
                            <td class="w-80">
                                @if ($data->QA_Approved_Comment)
                                    {{ $data->QA_Approved_Comment }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Reject CAPA Plan By</th>
                            <td class="w-30">
                                @if ($data->Reject_CAPA_Plan_By)
                                    {{ $data->Reject_CAPA_Plan_By }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">Reject CAPA Plan On</th>
                            <td class="w-30">
                                @if ($data->Reject_CAPA_Plan_On)
                                    {{ $data->Reject_CAPA_Plan_On }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Reject CAPA Plan Comment</th>
                            <td class="w-80">
                                @if ($data->Reject_CAPA_Plan_Comment)
                                    {{ $data->Reject_CAPA_Plan_Comment }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">QA Approval Without CAPA By</th>
                            <td class="w-30">
                                @if ($data->QA_Approval_Without_CAPA_By)
                                    {{ $data->QA_Approval_Without_CAPA_By }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">QA Approval Without CAPA On</th>
                            <td class="w-30">
                                @if ($data->QA_Approval_Without_CAPA_On)
                                    {{ $data->QA_Approval_Without_CAPA_On }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">QA Approval Without CAPA Comment</th>
                            <td class="w-80">
                                @if ($data->QA_Approval_Without_CAPA_Comment)
                                    {{ $data->QA_Approval_Without_CAPA_Comment }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">All CAPA Closed By</th>
                            <td class="w-30">
                                @if ($data->All_CAPA_Closed_By)
                                    {{ $data->All_CAPA_Closed_By }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">All CAPA Closed On</th>
                            <td class="w-30">
                                @if ($data->All_CAPA_Closed_On)
                                    {{ $data->All_CAPA_Closed_On }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">All CAPA Closed Comment</th>
                            <td class="w-80">
                                @if ($data->All_CAPA_Closed_Comment)
                                    {{ $data->All_CAPA_Closed_Comment }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Reject CAPA Plan By</th>
                            <td class="w-30">
                                @if ($data->Reject_CAPA_Plan_By1)
                                    {{ $data->Reject_CAPA_Plan_By1 }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">Reject CAPA Plan On</th>
                            <td class="w-30">
                                @if ($data->Reject_CAPA_Plan_On1)
                                    {{ $data->Reject_CAPA_Plan_On1 }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Reject CAPA Plan Comment</th>
                            <td class="w-80">
                                @if ($data->Reject_CAPA_Plan_Comment1)
                                    {{ $data->Reject_CAPA_Plan_Comment1 }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Final Approval By</th>
                            <td class="w-30">
                                @if ($data->Final_Approval_By)
                                    {{ $data->Final_Approval_By }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">Final Approval On</th>
                            <td class="w-30">
                                @if ($data->Final_Approval_on)
                                    {{ $data->Final_Approval_on }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Final Approval Comment</th>
                            <td class="w-80">
                                @if ($data->Final_Approval_Comment)
                                    {{ $data->Final_Approval_Comment }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                    </table>
                </div>
            </div>


        </div>
    </div>

</body>

</html>
