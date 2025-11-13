<?php

namespace App\Models;

class ShapeModel extends \CodeIgniter\Model
{
		protected $table = 'shape';
		protected $allowedFields = ['bid','shapeP1','shapeP2','shapeP3','shapeP4','shapeP5','shapeP6','myS','myP','ministry'];

		

function updateMinistryBybid($id,$newMinistry){
	$r = $this->where('bid', $id)->set(['ministry' =>  $newMinistry])->update();
	
	return $r;
}
	



		
		
		
}