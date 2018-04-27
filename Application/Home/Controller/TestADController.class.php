<?php
namespace Home\Controller;

use Think\Controller;

class TestADController extends Controller {
	// public function test() {
	// 	if(preg_match('/^http/','http://www.baidu.com'))
	// 	{
	// 		echo 123;
	// 	}
	// }
	public function index($id=0) {
		$this->show(checkID($id));
	}
	public function check($id=0) {
			// http://readercms.pchouse.com.cn/issue/list.do?_encode=UTF-8&start=0&limit=10
		function curl_post($url, $data = array(), $header = array(), $cookie = array(), $timeout = 5, $port = 80) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_PORT, $port);
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$result = array('status' => true);
			$result['result'] = curl_exec($ch);

			if (0 != curl_errno($ch)) {
				$result['error'] = "Error:\n" . curl_error($ch);
				$result['status'] = false;
			}
			curl_close($ch);
			return $result;
		}
		function checkID($id=''){


			$url = 'http://agent.pconline.com.cn:8138/adpubb/adData/show.htm?id='.$id;
			$data = array();
			$cookie = "u=415h9ly; c=409qwi7; u4ad=7755ep1sm; channel=144; lsid=1453775233333.96; pcsuv=1453775233689.a.298988337; pcuvdata=lastAccessTime=1457664685861|visits=2; JSESSIONID=abcp4Kj2PadnmfD-KZsqv";

			$ret = curl_post($url, $data, $header, $cookie,5,8138)["result"];
			Vendor('Sunra.PhpSimple.HtmlDomParser');
			$dom = str_get_html($ret);
			$sid = $dom->find('textarea[id=adContent'.$id.'_0]')[0]->innertext;
			return $sid;
		}

		// $dataURL = 'http://192.168.22.28/test/excel%E5%AF%BC%E5%87%BAjson/data.js';
		// $data = file_get_contents($dataURL);
		// // $data='{"id":1,"name":"jb51","email":"admin@jb51.net","interest":["wordpress","php"]}';
		// $jsonData = json_decode($data)->data;
		// foreach( $jsonData as $key=>$item) {
		// 	// if($key<3){
		// 		if($item->sid != 'error')
		// 		{
		// 			echo '<p>'.$item->sid.'=>';
		// 			$sid = checkID($item->id);
		// 			if($sid!=$item->sid){
		// 				echo error.'</p>';
		// 			}else{
		// 				echo '</p>';
		// 			}
		// 		}else{
		// 			echo '<p>'.$item->sid.'=>error';
		// 		}
		// 	// }
		// 	// checkID('403498');
		// }

		// $this->assign('cmsdata',$ret);
		// $this->assign('name',$name);
		// $this->show(checkID($id));
		echo checkID($id);
	}


}
