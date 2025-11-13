<?php

    namespace App\Models;

    class PrayerItemsModel extends \CodeIgniter\Model
    {
		
		
protected $table = 'prayer_weekly';

protected $allowedFields = ['tier','start','end','remind1','remind2'];



function getNewestPrayer(){			
		
		$query = $this->db->query("SELECT * FROM prayer_weekly ORDER BY start DESC LIMIT 1");
		$result = $query->getRowArray();
		return $result;

		
}


function getNewest4Prayer(){			
		
		$query = $this->db->query("SELECT * FROM prayer_weekly ORDER BY start DESC LIMIT 4");
		$result = $query->getResult('array');
		return $result;

		
}

function getThisWeekPrayer(){			
		
		$query = $this->db->query("SELECT * FROM prayer_weekly where end > ? ORDER BY start ASC LIMIT 1",array(time()));
		$result = $query->getRowArray();
		return $result;

		
}





function getPrayerItem($prayer_id, $day, $author_id){		

			
		$query = $this->db->query("SELECT * FROM prayer_items where prayer_id = ? and day = ? and pid = ?",array($prayer_id, $day, $author_id));
		$result = $query->getRowArray();
		return $result;

		
}



function getPrayerItemByUid($author_id){		

			
		$query = $this->db->query("SELECT * FROM prayer_items where pid=? ORDER BY `day` DESC limit 0,1",array($author_id));
		$result = $query->getRowArray();
		return $result;

		
}


function getLastPrayerItem(){		

			
		$query = $this->db->query("SELECT * FROM prayer_items ORDER BY `id` DESC limit 0,1");
		$result = $query->getRowArray();
		return $result;

		
}




function getPrayerItemsForOutput(){			
		
		$query = $this->db->query("SELECT t1.* 
FROM prayer_items t1
JOIN (
    SELECT pid, MAX(day) as max_day
    FROM prayer_items
    GROUP BY pid
) t2 ON t1.pid = t2.pid AND t1.day = t2.max_day AND t1.day > 202501 order by t1.day desc;");
		$result = $query->getResultArray();
		return $result;

		
}






function getPrayerItems($prayer_id){			
		
		$query = $this->db->query("SELECT * FROM prayer_items where prayer_id = ?",array($prayer_id));
		$result = $query->getResultArray();
		return $result;

		
}





function insertOrUpdate($prayerItem){			
		
		$db = db_connect();	
		
		$builder = $db->table('prayer_items');	
		
		return $builder->replace($prayerItem);
		
}







function getPastorsByTier($prayers,$prayer_id){		

		$inCause = '('.implode(',', $prayers).')';
		
$query = $this->db->query("SELECT a.id as bid, a.name ,c.* FROM (SELECT CONCAT( fName,' ',lName) as name, id FROM baptism WHERE id in {$inCause}) a left join (SELECT en, zh_hant, zh_hans, pid, day FROM prayer_items WHERE prayer_id = ?) c on c.pid = a.id", array($prayer_id));

// echo "SELECT a.* ,c.* FROM (SELECT CONCAT( fName,' ',lName) as name, id as pid FROM baptism WHERE id in {$inCause}) a left join (SELECT * FROM prayer_items WHERE prayer_id = ?) c on c.pid = a.pid"; exit;

		$result = $query->getResultArray();

		return $result;

		
}








    }