<div class="card card-flush">
    <div class="card-header">
        <h3 class="card-title">Top Users (Max Reports)</h3>
    </div>

    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Designation</th>
                    <th class="text-end">Reports</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topUsers as $user)
                <tr>
                    <td class="fw-bold">{{ $user->name }}</td>
                    <td>{{ optional($user->designation)->name }}</td>
                    <td class="text-end fw-bold">{{ $user->reports_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
