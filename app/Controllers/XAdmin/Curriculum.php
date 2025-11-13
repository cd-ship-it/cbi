<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;

use App\Models\ClassesModel;


class Curriculum extends BaseController
{
	
	
	public function index($code)
	{
		
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/curriculum/'.$code);
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes('edit_class',1);			

		$action = $this->request->getPost('action');			
		
 
		
		$modelClasses = new ClassesModel();		 
		
		
		
		
		if($action == 'absence'){
			
			$r = $modelClasses->curriculumUnsigned($_POST['cid'],$_POST['bid'],$_POST['date']);
			echo $r; 
			exit();
			
		}elseif($action == 'presence'){
			
			$r = $modelClasses->curriculumSigned($_POST['cid'],$_POST['bid'],$_POST['date']);
			echo $r; 
			exit();	
			
		}elseif($action == 'remove'){
			
			$r = $modelClasses->curriculumLogsRemove($_POST['cid'],$_POST['bid']);
			echo $r; 
			exit();	
			
		}elseif($action == 'addnote'){
			

			
					if($modelClasses->update($_POST['cid'], array('note'=>$_POST['content']))){
						$r= 'OK';
					}else{
						$r= 'Error';
					}
			
			echo $r; 
			exit();	
			
		}elseif($action == 'searchStu'){
			
			$r = $modelClasses->searchStuByfn($_POST['cid'],$_POST['query']);
			echo json_encode($r); 
			exit();	
			
		}elseif($action == 'join'){
			
			$r = $modelClasses->curriculumJoin($_POST['cid'],$_POST['bid']);
			echo $r; 
			exit();	
			
		}elseif($action == 'sendMs'){
			
			
			$cid = $_POST['cid'];
			
			$sender = $_SESSION['mloggedinName'];
			if($_SESSION['xadmin']==3 || preg_match('#anders|jacky|admin#i',$_SESSION['mloggedinName'])){
				$sender = 'pastor '.$_SESSION['mloggedinName'];
			}
			
			$message = $message_db = $_POST['msg'];		
			
			$cTitle = $_POST['cTitle'];
			
			$files_db = isset($_POST['files'])?json_encode($_POST['files']):json_encode(array());
			$send_to_arr = $modelClasses->db_m_getemails($_POST['to']);
			
			

			$modelClasses->db_m_addmslogs($sender,$cid,time(),json_encode($send_to_arr),$files_db,$message_db);
			
			$emailsVars = [];
			$msgto = [];

		
			

			
			foreach($send_to_arr[0] as $key => $stu){
				$myEmailItem = [];
				$myEmailItem['From'] =  'admin@tracycrosspoint.org';
				$myEmailItem['To'] = $send_to_arr[1][$key]; 
				$myEmailItem['Subject'] = 'Regarding to: '.$cTitle;
				$myEmailItem['HtmlBody'] = nl2br($message);
				
				$emailsVars['template']['custom-msg'][] = ["email_address" => ["address" => $myEmailItem['To']], "merge_info" => ['subject'=>$myEmailItem['Subject'], 'msg'=>$myEmailItem['HtmlBody']]];

				
				$emailsVars[] = $myEmailItem;
				$msgto[] = $stu.'&lt;'.$send_to_arr[1][$key].'&gt;';
			}
			
			
		
			
			$r2 = $this->webConfig->Sendbatchemails($emailsVars); 
			
			
			echo '<div class="slog">
			<h4>'.ucwords($sender).' 於 '.date("m/d/Y g:i a").' 提交</h4>
			<h4>信息內容:</h4><div class="msgcon">'.nl2br($message).'</div>
			<h4>目標:</h4><div class="msgto">'.implode(', ',$send_to_arr[0]).'</div>	
			</div>';
			
			
			
			exit();	
			
		}


		$curriculumInfo = isset($this->webConfig->curriculumCodes[$this->lang][$code])?$this->webConfig->curriculumCodes[$this->lang][$code]:false;

		if(!$curriculumInfo){
				echo 'curriculum ['.$code.'] not found';
				exit(); 
		}





		$data['pageTitle']=$curriculumInfo[1];
		$data['pageDescription']=$curriculumInfo[2];
		$data['pageSlug']=$curriculumInfo[0];
		

		/////////////////////////////////////////



		$data['curriculums'] = $modelClasses->getCurriculumsByCode($code);
		if(!$data['curriculums']){
				echo 'curriculum ['.$code.'] not found';
				exit(); 
		}
		
		foreach($data['curriculums'] as $item){
			
			$students = $modelClasses->getStudentsByCid($item['id']);
			$data['curriculums'][$item['id']]['students'] = $students;
			
			$msgLogs = $modelClasses->getMsgLogs($item['id']);
			$data['curriculums'][$item['id']]['msgLogs'] = $msgLogs;
			
		}

				

			$data['menugroup'] = 'classes';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'admin_curriculum';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			echo view('theme-sb-admin-2/layout',$data);	

		
		}
			
		

		
		
	
	


	//--------------------------------------------------------------------

}
