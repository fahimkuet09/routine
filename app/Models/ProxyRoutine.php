<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProxyRoutine extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function routine()
    {
        return $this->belongsTo(FullRoutine::class);
    }

    // Relationship: ProxyRoutine belongs to a Teacher (proxy teacher)
    public function proxyTeacher()
    {
        return $this->belongsTo(Teacher::class, 'proxy_teacher_id');
    }
}
