<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <title>{{ $report->program_title }} - {{ $report->candidate_name }}</title>
    <style>
        body {
            font-family: solaimanlipi;
            font-size: 16px;
            line-height: 1.2;
        }

        .date {
            text-align: left;
            margin-bottom: 0;
        }

        .to {
            margin-bottom: 10px;
        }

        .to p {
            margin: 0;
        }

        .subject {
            margin: 0;
        }

        .content {
            text-align: justify;
            margin-bottom: 0;
        }

        .signature {
            margin-top: 0
        }

        .first-child-td {
            border: 1px solid #000;
            background: #f0f0f0;
        }

        .second-child-td {
            border: 1px solid #000;
        }

        /* ================= RIGHT SIDE STATUS BOX ================= */
        .status-box {
            position: fixed;
            top: 0;
            right: 0;
            width: 50px;
            border: 2px solid #000;
            padding: 10px 5px;
            text-align: center;
            font-size: 16px;
            line-height: 1;
        }

        /* ========================================================= */
    </style>
</head>

<body>

    <!-- ================= Right Side Program Status Box ================= -->
    @if ($report->tentative_risks === 'yes')
    <div class="status-box">
        ঝুঁকিপূর্ণ
    </div>
    @endif
    <!-- ================================================================ -->

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
        বিষয়ঃ {{ $report->program_title }}
    </div>

    <!-- Description -->
    <div class="content">
        <p>
            <u>বিস্তারিত বিবরণ:</u> {{ $report->program_description }}
        </p>

        <!-- ========================================================= -->
        <!-- Table 01: Program Summary                                -->
        <!-- ========================================================= -->

        <table width="100%" cellpadding="6" cellspacing="0"
            style="border-collapse: collapse; margin-bottom: 20px; table-layout: fixed;">

            <caption style="caption-side: top; text-align: left; padding-bottom: 8px; text-decoration: underline;">
                প্রোগ্রামের সারসংক্ষেপ:
            </caption>

            <tbody>
                <tr>
                    <td width="30%" class="first-child-td">সংসদীয় আসন</td>
                    <td width="70%" class="second-child-td">{{ $report->parliamentSeat->name }}</td>
                </tr>

                <tr>
                    <td width="30%" class="first-child-td">উপজেলা</td>
                    <td width="70%" class="second-child-td">{{ $report->upazila->name }}</td>
                </tr>

                <tr>
                    <td width="30%" class="first-child-td">ইউনিয়ন / পৌরসভা</td>
                    <td width="70%" class="second-child-td">{{ $report->union->name }}</td>
                </tr>

                @if ($report->location_name)
                    <tr>
                        <td width="30%" class="first-child-td">প্রোগ্রামের স্থান</td>
                        <td width="70%" class="second-child-td">{{ $report->location_name }}</td>
                    </tr>
                @endif

                <tr>
                    <td width="30%" class="first-child-td">প্রোগ্রামের ধরণ</td>
                    <td width="70%" class="second-child-td">{{ $report->programType->name }}</td>
                </tr>
            </tbody>
        </table>

        <!-- ========================================================= -->
        <!-- Table 02: Political & Program Details                     -->
        <!-- ========================================================= -->

        <table width="100%" cellpadding="6" cellspacing="0"
            style="border-collapse: collapse; margin-bottom: 20px; table-layout: fixed;">

            <tbody>
                <tr>
                    <td width="30%" class="first-child-td">রাজনৈতিক দল</td>
                    <td width="70%" class="second-child-td">{{ $report->politicalParty->name }}</td>
                </tr>

                @if ($report->candidate_name)
                    <tr>
                        <td width="30%" class="first-child-td">সংসদ সদস্য পদপ্রার্থী</td>
                        <td width="70%" class="second-child-td">{{ $report->candidate_name }}</td>
                    </tr>
                @endif

                @if ($report->program_date)
                    <tr>
                        <td width="30%" class="first-child-td">প্রোগ্রামের তারিখ ও সময়</td>
                        <td width="70%" class="second-child-td">
                            {{ $numto->bnNum(\Carbon\Carbon::parse($report->program_date)->format('d')) }}-
                            {{ $numto->bnNum(\Carbon\Carbon::parse($report->program_date)->format('m')) }}-
                            {{ $numto->bnNum(\Carbon\Carbon::parse($report->program_date)->format('Y')) }}
                        </td>
                    </tr>
                @endif

                @if ($report->program_status !== 'done')
                    @if ($report->tentative_attendee_count)
                        <tr>
                            <td width="30%" class="first-child-td">উপস্থিতির সংখ্যা (আনুমানিক)</td>
                            <td width="70%" class="second-child-td">
                                {{ $numto->bnNum($report->tentative_attendee_count) }} জন
                            </td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td width="30%" class="first-child-td">মোট উপস্থিতি</td>
                        <td width="70%" class="second-child-td">
                            {{ $report->actual_attendee_count ? $numto->bnNum($report->actual_attendee_count) : '০' }} জন
                        </td>
                    </tr>

                    @if ($report->dead_injured_count)
                        <tr>
                            <td width="30%" class="first-child-td">হতাহতের সংখ্যা</td>
                            <td width="70%" class="second-child-td">{{ $report->dead_injured_count }}</td>
                        </tr>
                    @endif
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
            {{ $report->createdBy->designation->name }}, {{ $report->createdBy->zone->name }}<br>
            ডিএসবি, পটুয়াখালী।
        </p>
    </div>

</body>

</html>
