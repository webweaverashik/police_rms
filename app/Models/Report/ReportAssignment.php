<?php
namespace App\Models\Report;

use App\Models\Report\Report;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class ReportAssignment extends Model
{
    protected $fillable = [
        'report_id',
        'user_id',
        'assigned_by',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /** Assigned UNO */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Admin / SuperAdmin */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by')->withTrashed();
    }
}
