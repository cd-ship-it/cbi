<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;
use App\Models\PermissionModel;
 

class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['status'];
	public $lang;
	public $session;
	public $userCaps;
	public $webConfig;
	public $logged_id;
	public $logged_name;
	public $modelPermission;
 
	public $data = [];  //session,userCaps,logged_id,logged_name,userPicture,webConfig,userLg

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------

 	
		$this->modelPermission = new PermissionModel();		
		$this->data['senior_pastor'] = $this->modelPermission->get_sp_users('is_senior_pastor');
		$this->data['office_director'] = $this->modelPermission->get_sp_users('is_office_director');
		$this->data['office_assistant'] = $this->modelPermission->get_sp_users('is_office_assistant'); 
		$this->data['bot_chair'] = $this->modelPermission->get_sp_users('is_bot_chair');
			
		 
		
		$this->data['session'] = $this->session = \Config\Services::session();
		$this->data['userCaps'] = $this->userCaps = $this->session->get('capabilities');

		$this->data['logged_id'] = $this->logged_id = $this->session->get('mloggedin');	
		$this->data['logged_name'] = $this->logged_name = $this->session->get('mloggedinName');	
		
		if($this->session->get('dsfPicture')){	
			$this->data['userPicture'] =  $this->session->get('dsfPicture');
		}else{
			$this->data['userPicture'] =  base_url().'/assets/images/default_user_profile.jpg'; 
		}

		
		
		
		$this->data['webConfig'] = $this->webConfig = new \Config\WebConfig();
		
 
 
		
		
		
		helper('cookie');
		$lang = get_cookie('userLg');		
		if(!$lang){			
			$hal = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';			
			$Lang = preg_match('/^([a-z\-]+)/i', $hal, $matches) ? $matches[1] : 'en';	 
			if (preg_match('/zh/i',$Lang) && preg_match('/cn/i',$Lang)) { 				$lang = 'zh-Hans';  
			}elseif (preg_match('/zh/i',$Lang)) {  
				$lang = 'zh-Hant';  	
			}else {  
				$lang = 'en';  
			} 			
		}		
		$this->data['userLg'] = $this->lang = $lang;
		
		
	}

}
