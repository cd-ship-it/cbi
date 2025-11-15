<?php

namespace App\Models;

class CampusModel extends \CodeIgniter\Model
{
	protected $table = 'campuses';
	protected $primaryKey = 'id';
	protected $allowedFields = ['campus', 'campus_pastor'];
	protected $useTimestamps = false;

}

