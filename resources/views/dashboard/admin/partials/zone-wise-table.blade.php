<div class="card card-flush">
    <div class="card-header">
        <h3 class="card-title">Zone Wise Reports</h3>
    </div>
    <div class="card-body pt-0">
        <table class="table table-row-dashed align-middle">
            <thead>
                <tr>
                    <th>Zone</th>
                    <th class="text-end">Reports</th>
                </tr>
            </thead>
            <tbody>
                @foreach($zoneWiseReports as $zone)
                    <tr>
                        <td>{{ $zone->name }}</td>
                        <td class="text-end fw-bold">{{ $zone->reports_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
