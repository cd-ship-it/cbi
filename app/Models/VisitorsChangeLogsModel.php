<?php

    namespace App\Models;

    class VisitorsChangeLogsModel extends \CodeIgniter\Model
    {
		
		
protected $table = 'visitor_change_log';



protected $allowedFields = ['visitor_id','field','old_value','new_value','user_login','change_time'];


 







































    }