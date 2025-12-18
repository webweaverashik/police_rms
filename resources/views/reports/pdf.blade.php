<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>{{ $report->program_title }} - {{ $report->candidate_name }}</title>
    <style>
        body {
            font-family: solaimanlipi;
            font-size: 16px;
            line-height: 1.3;
        }

        .date {
            text-align: left;
            margin-bottom: 20px;
        }

        .to {
            margin-bottom: 20px;
        }

        .to p {
            margin: 0;
        }

        .subject {
            margin: 20px 0;
        }

        .content {
            text-align: justify;
            margin-bottom: 20px;
        }

        .signature {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    @php
        use Rakibhstu\Banglanumber\NumberToBangla;

        $numto = new NumberToBangla();
    @endphp

    <!-- Date -->
    <div class="date">
        তারিখ: {{ $reportDateTime }}
    </div>

    <!-- To -->
    <div class="to">
        <p>বরাবর</p>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;পুলিশ সুপার</p>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;ডিএসবি, পটুয়াখালী।</p>
    </div>

    <!-- Subject -->
    <div class="subject">
        বিষয়ঃ {{ $report->program_title }}<br>
        ধরণঃ {{ $report->programType->name }}
    </div>

    <!-- Description -->
    <div class="content">
        <p>
            <u>বিস্তারিত বিবরণ:</u> {{ $report->program_description }}
        </p>

        <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse: collapse; margin-bottom: 20px;">
            <tbody>
                <tr>
                    <td style="border: 1px solid #000;">
                        রাজনৈতিক দল
                    </td>
                    <td style="border: 1px solid #000;">
                        {{ $report->politicalParty->name }}
                    </td>
                </tr>

                <tr>
                    <td style="border: 1px solid #000;">
                        সংসদ সদস্য পদপ্রার্থী
                    </td>
                    <td style="border: 1px solid #000;">
                        {{ $report->candidate_name }}
                    </td>
                </tr>

                @if ($report->program_date)
                    <tr>
                        <td width="30%" style="border: 1px solid #000;">
                            প্রোগ্রামের তারিখ
                        </td>
                        <td width="70%" style="border: 1px solid #000;">
                            {{ $numto->bnNum(\Carbon\Carbon::parse($report->program_date)->format('d')) }}-{{ $numto->bnNum(\Carbon\Carbon::parse($report->program_date)->format('m')) }}-{{ $numto->bnNum(\Carbon\Carbon::parse($report->program_date)->format('Y')) }}
                        </td>
                    </tr>
                @endif

                @if ($report->program_time)
                    <tr>
                        <td style="border: 1px solid #000;">
                            প্রোগ্রামের সময়
                        </td>
                        <td style="border: 1px solid #000;">
                            {{ $numto->bnNum(\Carbon\Carbon::parse($report->program_time)->format('h')) }}:{{ $numto->bnNum(\Carbon\Carbon::parse($report->program_time)->format('i')) }}
                            {{ \Carbon\Carbon::parse($report->program_time)->format('A') }}
                        </td>
                    </tr>
                @endif

                @if ($report->location_name)
                    <tr>
                        <td style="border: 1px solid #000;">
                            প্রোগ্রামের স্থান
                        </td>
                        <td style="border: 1px solid #000;">
                            {{ $report->location_name }}
                        </td>
                    </tr>
                @endif


                @if ($report->program_chair)
                    <tr>
                        <td style="border: 1px solid #000;">
                            সভাপতি
                        </td>
                        <td style="border: 1px solid #000;">
                            {{ $report->program_chair }}
                        </td>
                    </tr>
                @endif

                @if ($report->program_special_guest)
                    <tr>
                        <td style="border: 1px solid #000;">
                            প্রধান অতিথি
                        </td>
                        <td style="border: 1px solid #000;">
                            {{ $report->program_special_guest }}
                        </td>
                    </tr>
                @endif

                @if ($report->tentative_attendee_count)
                    <tr>
                        <td style="border: 1px solid #000;">
                            উপস্থিতির সংখ্যা (আনুমানিক)
                        </td>
                        <td style="border: 1px solid #000;">
                            {{ $numto->bnNum($report->tentative_attendee_count) }}
                            জন
                        </td>
                    </tr>
                @endif

            </tbody>
        </table>

    </div>

    <p>বিষয়টি আপনার সদয় অবগতির জন্য প্রেরণ করা হলো।</p>

    <!-- Signature -->
    <div class="signature">
        <p>বিনীত</p>
        <p>
            {{ $report->createdBy->name }}<br>
            {{ $report->createdBy->designation->name }}<br>
            ডিএসবি, পটুয়াখালী।
        </p>
    </div>

</body>

</html>
