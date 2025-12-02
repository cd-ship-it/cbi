<?php

namespace App\Models;

class VisitorLifestatusPastorMapModel extends \CodeIgniter\Model
{
	protected $table = 'visitor_lifestatus_pastor_map';
	protected $primaryKey = 'id';
	protected $allowedFields = ['lifestatus_id', 'campus_id', 'pastor_id'];
	protected $useTimestamps = false;

}



