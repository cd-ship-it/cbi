<?php

namespace App\Models;

class MeetingModel extends \CodeIgniter\Model
{
    protected $table = 'meetings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'created_at', 'chair_first_name', 'chair_last_name', 'chair_email',
        'campus_name', 'ministry', 'file_path', 'document_url'
    ];
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
}
