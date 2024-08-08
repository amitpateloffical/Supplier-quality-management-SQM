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
        min-width: 100vw;
        min-height: 100vh;
    }

    .w-10 {
        width: 10%;
    }

    .w-20 {
        width: 20%;
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

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    table {
        width: 100%;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    header .head {
        font-weight: bold;
        text-align: center;
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
        position: fixed;
        bottom: -40px;
        left: 0;
        width: 100%;
    }

    .inner-block {
        padding: 10px;
    }

    .inner-block .head {
        font-weight: bold;
        font-size: 1.2rem;
        margin-bottom: 5px;
    }

    .inner-block .division {
        margin-bottom: 10px;
    }

    .first-table {
        border-top: 1px solid black;
        margin-bottom: 20px;
    }

    .first-table table td,
    .first-table table th,
    .first-table table {
        border: 0;
    }

    .second-table td:nth-child(1)>div {
        margin-bottom: 10px;
    }

    .second-table td:nth-child(1)>div:nth-last-child(1) {
        margin-bottom: 0px;
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
                   Root Cause Analysis Audit Trial Report
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
                    <strong>Root Cause Analysis No.</strong>
                </td>
                <td class="w-40"> 
                    {{ Helpers::getDivisionName($doc->division_id) }}/RCA/{{ Helpers::year($doc->created_at) }}/{{ str_pad($doc->record, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="w-30">
                    <strong>Record No.</strong> {{ str_pad($doc->record, 4, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
        </table>
    </header>

    <div class="inner-block">

        <div class="head">Root Cause Analysis Audit Trial Report</div>

        <div class="division">
            {{ Helpers::getDivisionName($doc->division_id) }}/RCA/{{ Helpers::year($doc->created_at) }}/{{ str_pad($doc->record, 4, '0', STR_PAD_LEFT) }}
        </div>

        {{-- <div class="first-table">
            <table>
                <tr>
                    <td class="w-50">
                        <strong>Config Area :</strong> All - No Filter
                    </td>
                    <td class="w-50">
                        <strong>Start Date (GMT) :</strong> {{ $doc->created_at }}
                    </td>
                </tr>
                <tr>
                    <td class="w-50">
                        <strong>Config Sub Area :</strong> All - No Filter
                    </td>
                    <td class="w-50">
                        <strong>End Date (GMT) :</strong>
                        @if ($doc->stage >= 9)
                            {{ $doc->updated_at }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="w-50">&nbsp;</td>
                    <td class="w-50">
                        <strong>Person Responsible : {{ $doc->originator }}</strong>
                    </td>
                </tr>
            </table>
        </div> --}}

        <div class="second-table">
            <table>
                <tr class="table_bg">
                    
                    <th>S.No</th>
                        <th>Flow Changed From</th>
                        <th>Flow Changed To</th>
                        <th>Data Field</th>
                        <th>Action Type</th>
                        <th>Performer</th>
                </tr>
                @foreach ($data as $datas)
                    <tr >
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div><strong>Changed From :</strong> {{ $datas->change_from }}</div>
                        </td>
                        <td>
                            <div><strong>Changed To :</strong> {{ $datas->change_to }}</div>
                        </td>

                        <td>
                            <div>
                                <strong>Data Field Name :</strong>
                                {{$datas->activity_type ?: 'Not Applicable' }}
                            </div>
                            <div style="margin-top: 5px;" class="imageContainer">
                                <!-- Assuming $dataDemo->image_url contains the URL of your image -->
                                @if ($datas->activity_type == 'Activity Log')
                                    <strong>Change From :</strong>
                                    @if ($datas->change_from)
                                        {{-- Check if the change_from is a date --}}
                                        @if (strtotime($datas->change_from))
                                            {{ \Carbon\Carbon::parse($datas->change_from)->format('d-M-Y') }}
                                        @else
                                            {{ str_replace(',', ', ', $datas->change_from) }}
                                        @endif
                                    @elseif($datas->change_from && trim($datas->change_from) == '')
                                        NULL
                                    @else
                                        Not Applicable
                                    @endif
                                @else
                                    <strong>Change From :</strong>
                                    @if (!empty(strip_tags($datas->previous)))
                                        {{-- Check if the previous is a date --}}
                                        @if (strtotime($datas->previous))
                                            {{ \Carbon\Carbon::parse($datas->previous)->format('d-M-Y') }}
                                        @else
                                            {!! $datas->previous !!}
                                        @endif
                                    @elseif($datas->previous == null)
                                        Null
                                    @else
                                        Not Applicable
                                    @endif
                                @endif
                            </div>
                        

                        <div class="imageContainer">
                            @if ($datas->activity_type == 'Activity Log')
                                <strong>Change To :</strong>
                                @if (strtotime($datas->change_to))
                                    {{ \Carbon\Carbon::parse($datas->change_to)->format('d-M-Y') }}
                                @else
                                    {!! str_replace(',', ', ', $datas->change_to) ?: 'Not Applicable' !!}
                                @endif
                            @else
                                <strong>Change To :</strong>
                                @if (strtotime($datas->current))
                                    {{ \Carbon\Carbon::parse($datas->current)->format('d-M-Y') }}
                                @else
                                    {!! !empty(strip_tags($datas->current)) ? $datas->current : 'Not Applicable' !!}
                                @endif
                            @endif
                        </div>
                        <div style="margin-top: 5px;">
                            <strong>Change Type :</strong>
                            {{ $datas->action_name ? $datas->action_name : 'Not Applicable' }}
                        </div>
                    </td>
                    <td>
                        <div><strong>Action Name :</strong>
                            {{ $datas->action ? $datas->action : 'Not Applicable' }}</div>
                    </td>
                    <td>
                        <div><strong>Performed By :</strong>
                            {{ $datas->user_name ? $datas->user_name : 'Not Applicable' }}</div>
                        <div style="margin-top: 5px;"> <strong>Performed On
                                :</strong>{{ $datas->created_at ? \Carbon\Carbon::parse($datas->created_at)->format('d-M-Y H:i:s') : 'Not Applicable' }}
                        </div>
                        <div style="margin-top: 5px;"><strong>Comments :</strong>
                            {{ $datas->comment ? $datas->comment : 'Not Applicable' }}</div>
                    </td>



                        {{-- <td class="row">
                            <div class="col-2">{{ $datas->activity_type }}</div>
                            <div>
                                <div><strong>Changed From :</strong></div>
                                @if(!empty($datas->previous))
                                <div>{{ $datas->previous }}</div>
                                @else
                                <div>Null</div>
                                @endif
                            </div>
                            <div class="col-4">
                                <div><strong>Changed To :</strong></div>
                                <div>{{ $datas->current }}</div>
                            </div>
                        </td> --}}
                        {{-- <td class="col-2">{{ Helpers::getdateFormat($datas->created_at) }}</td>
                        <td class="col-2">{{ $datas->user_name }}</td>
                        <td class="col-2">
                            @if ($datas->previous == "NULL")
                                Modify
                            @else
                                New
                            @endif
                        </td> --}}
                    </tr>
                @endforeach
            </table>
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
                <td class="w-30">
                    <strong>Page :</strong> 1 of 1
                </td>
            </tr>
        </table>
    </footer>

</body>

</html>
