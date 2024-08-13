<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-3">
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

    .w-5 {
        width: 5%;
    }

    .w-10 {
        width: 10%;
    }

    .w-15 {
        width: 15%;
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
        overflow: hidden;
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

    .allow-wb {
        word-break: break-all;
        word-wrap: break-word;
    }
</style>

<body>

    <header>
        <table>
            <tr>
                <td class="w-70 head">
                    SCAR Audit Trail Report
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
                    <strong> SCAR No.</strong>
                </td>
                <td class="w-40">
                    {{ Helpers::getDivisionName($doc->division_id) }}/ SCAR
                    /{{ Helpers::year($doc->created_at) }}/
                    {{ str_pad($doc->record, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="w-30">
                    <strong>Record No.</strong> {{ str_pad($doc->record, 4, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
        </table>
    </header>

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

    <div class="inner-block">
        <div class="second-table">
            <table class="allow-wb" style="table-layout: fixed; width: 700px;">
                <thead style="max-width: 100px;">
                    <tr class="table_bg">
                        <th class="w-5">S.No</th>
                        <th class="w-15">Flow Changed From</th>
                        <th class="w-15">Flow Changed To</th>
                        <th class="w-30">Data Field</th>
                        <th class="w-15" style="word-break: break-all;">Action Type</th>
                        <th class="w-15" style="word-break: break-all;">Performer</th>
                    </tr>
                </thead>
                <tbody style="max-width: 100px;">
                    @foreach ($audit as $index => $dataDemo)
                        <tr>
                            <td class="w-10">{{ $loop->iteration }}</td>
                            <td class="w-20">
                                <div><strong>Changed From :</strong>
                                    {{ \Illuminate\Support\Str::limit($dataDemo->change_from, 600) }}
                                </div>
                            </td>
                            <td class="w-20">
                                <div><strong>Changed To :</strong>
                                    {{ \Illuminate\Support\Str::limit($dataDemo->change_to, 600) }}
                                </div>
                            </td>
                            <td class="w-30">
                                <div class="allow-wb">
                                    <strong>Data Field Name :</strong>
                                    {{ \Illuminate\Support\Str::limit($dataDemo->activity_type ?: 'Not Applicable', 600) }}
                                </div>
                                <div style="margin-top: 5px;" class="imageContainer allow-wb">
                                    @if ($dataDemo->activity_type == 'Activity Log')
                                        <strong>Change From :</strong>
                                        @if ($dataDemo->change_from)
                                            @if (strtotime($dataDemo->change_from))
                                                {{ \Carbon\Carbon::parse($dataDemo->change_from)->format('d-M-Y') }}
                                            @else
                                                {{ \Illuminate\Support\Str::limit(str_replace(',', ', ', $dataDemo->change_from), 600) }}
                                            @endif
                                        @elseif($dataDemo->change_from && trim($dataDemo->change_from) == '')
                                            NULL
                                        @else
                                            Not Applicable
                                        @endif
                                    @else
                                        <strong>Change From :</strong>
                                        @if (!empty(strip_tags($dataDemo->previous)))
                                            @if (strtotime($dataDemo->previous))
                                                {{ \Carbon\Carbon::parse($dataDemo->previous)->format('d-M-Y') }}
                                            @else
                                                {!! \Illuminate\Support\Str::limit($dataDemo->previous, 600) !!}
                                            @endif
                                        @elseif($dataDemo->previous == null)
                                            Null
                                        @else
                                            Not Applicable
                                        @endif
                                    @endif
                                </div>
                                <br>
                                <div class="allow-wb">
                                    @if ($dataDemo->activity_type == 'Activity Log')
                                        <strong>Change To :</strong>
                                        <span style="word-break: break-all; max-width: 20px;">
                                            {{ \Illuminate\Support\Str::limit($dataDemo->change_to ? $dataDemo->change_to : 'Not Applicable', 600) }}
                                        </span>
                                    @else
                                        <strong>Change To :</strong>
                                        <span style="word-break: break-all; width: 20px; word-wrap: break-word;">
                                            {{ \Illuminate\Support\Str::limit($dataDemo->current ? $dataDemo->current : 'Not Applicable', 600) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="allow-wb" style="margin-top: 5px;">
                                    <strong>Change Type :</strong>
                                    {{ \Illuminate\Support\Str::limit($dataDemo->action_name ? $dataDemo->action_name : 'Not Applicable', 600) }}
                                </div>
                            </td>
                            <td class="w-10">
                                <div><strong>Action Name :</strong>
                                    {{ \Illuminate\Support\Str::limit($dataDemo->action ? $dataDemo->action : 'Not Applicable', 600) }}
                                </div>
                            </td>
                            <td class="w-15">
                                <div><strong>Performed By :</strong>
                                    {{ \Illuminate\Support\Str::limit($dataDemo->user_name ? $dataDemo->user_name : 'Not Applicable', 600) }}
                                </div>
                                <div style="margin-top: 5px;">
                                    <strong>Performed On :</strong>
                                    {{ $dataDemo->created_at ? \Carbon\Carbon::parse($dataDemo->created_at)->format('d-M-Y H:i:s') : 'Not Applicable' }}
                                </div>
                                <div style="margin-top: 5px;">
                                    <strong>Comments :</strong>
                                    {{ \Illuminate\Support\Str::limit($dataDemo->comment ? $dataDemo->comment : 'Not Applicable', 600) }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>



</body>

</html>
