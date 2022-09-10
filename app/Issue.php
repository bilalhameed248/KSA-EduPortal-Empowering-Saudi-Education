<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        'issue_id', 'issue_text', 'issue_type','issue_related','issue_file','issue_date','issue_created','issue_status','issue_remarks','issue_detail_file',
    ];
}
