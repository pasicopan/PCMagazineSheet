<?php
namespace Home\Controller;

use Think\Controller;

class MagazineController extends Controller {
	// public function test() {
	// 	if(preg_match('/^http/','http://www.baidu.com'))
	// 	{
	// 		echo 123;
	// 	}
	// }
	public function index($name = '',$limit=10) {
		if($name)
		{
			// http://readercms.pchouse.com.cn/issue/list.do?_encode=UTF-8&start=0&limit=10
			$url = 'http://readercms.'.$name.'.com.cn/issue/list.do?_encode=UTF-8&start=0&limit='.$limit;
			$data = array();
			$cookie = "u=415h9ly; c=409qwi7; pcsuv=1451533149531.a.82145854; pcuvdata=lastAccessTime=1451533169824|visits=1; channel=115; JSESSIONID=abcme84L0nKCkbnSgDCjv; uName=%E6%BD%98%E6%80%9D%E5%98%89; uDepartment=%E5%B9%BF%E5%B7%9E+-+%E7%A0%94%E5%8F%91%E4%B8%AD%E5%BF%83-%E5%89%8D%E7%AB%AF%E5%BC%80%E5%8F%91%E9%83%A8; CMS_USER=70933; role_id=0";
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
			$ret = curl_post($url, $data, $header, $cookie)['result'];
		}
		else {
			$ret = '""';
		}
		// $ret = $ret || '{}';
		// $obj=json_decode($ret);
		// $listString = '';
		// foreach($obj->rows as $element)
		// {
		// 	$listString .= '<p><a href="http://localhost/PCMagazineSheet/index.php/home/Index/magazine/name/'.$name.'/issueid/'.$element->id.'/title/'.$element->name.'"> ' . $element->name.'</a></p>';
		// }
		//
		// echo $listString;
		// echo $ret;
		$this->assign('cmsdata',$ret);
		$this->assign('name',$name);
		$this->show();
	}
	// 同步
	public function magazinesize($name=0,$type) {
		if($name)
		{
			// http://readercms.pchouse.com.cn/magazine/publish/ipad3/json/magazines.json
			$url = 'http://readercms.'.$name.'.com.cn/magazine/publish/'.$type.'/json/magazines.json';
			$ret = file_get_contents($url);
		}else{
			$ret = '';
		}
		// echo var_dump($url);
		// echo $ret;
		$this->assign('cmsdata',$ret);
		$this->assign('name',$name);
		$this->assign('type',$type);
		$this->show();
	}
	public function synchronization($name=0, $issueid=0) {
		$url = "http://readercms.$name.com.cn/content.do?issueId=" . $issueid;
		$cookie = "JSESSIONID=abcnpIuqdBVL05TCBeBHu; uName=%E6%BD%98%E6%80%9D%E5%98%89; uDepartment=%E5%B9%BF%E5%B7%9E+-+%E7%A0%94%E5%8F%91%E4%B8%AD%E5%BF%83-%E5%89%8D%E7%AB%AF%E5%BC%80%E5%8F%91%E9%83%A8; CMS_USER=70933; role_id=0";
		$data = array();
		$Issue   =   D('Issue');
		if($Issue->where("magazine='$name' and issueid=$issueid")->find())
		{
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
			$ret = curl_post($url, $data, $header, $cookie)['result'];

			Vendor('Sunra.PhpSimple.HtmlDomParser');

			$dom = str_get_html($ret);

			$update = 0;
			$add = 0;
			$del = 0;
			$Article   =   D('Article');
			function modifyArticle($element, $a_index, $columnname, $issueid, $name)
			{
				// echo var_dump($issueid);
				// return;
				if($element)
				{
					$Article   =   D('Article');
					$data['title']  =   trim($element->innertext);
					// $data['designer']    =   $element->designer;
					$data['cmsid']    =   $element->id;
					$data['issueid']    =   $issueid;
					$data['magazinename']    =   $name;
					$data['columnname']    =   $columnname;
					$data['order']    =   $a_index;// 排序
					$data['status']    =   1;
					if($Article->where("cmsid='{$element->id}'")->find())
					{
						// if($Article->where("cmsid='{$element->id}' AND title!='{$data['title']}' AND order!='{$data['order']}'")->find())
						// {
							$Article->where("cmsid='{$element->id}'")->save($data);
							$update++;
						// }
					}
					else {
						$Article->add($data);
						$add++;
					}
				}
			}
			$a_index = 0;
			$column_titles = $dom->find('[class=tree-toggle nav-header tree-node]');
			$leaf_containers = $dom->find('[class=nav nav-list tree]');
			foreach ( $leaf_containers as $key=>$leaf_container) {
				$column_title_text = $column_titles[$key]->innertext;
				$leafs = $leaf_container->find('.leaf');
				foreach ( $leafs as $leaf) {
					modifyArticle($leaf, $a_index, $column_title_text, $issueid, $name);
					$a_index++;
				}
			}

			$leaf = $dom->find('.leaf');

			$result = $Article->where("issueid=$issueid AND status=1 ")->order('order')->select();

			foreach ($result as $element1)
			{
				if(!preg_match('/^cur/',$element1['cmsid']))
				{
					$isDel = 1;
					foreach ($leaf as $element2)
					{
						if($element1['cmsid'] == $element2->id)
						{
							$isDel = 0;
						}
					}
					if($isDel)
					{
						$delData = array();
						$delData['status'] = 0;
						$Article->where("cmsid='{$element1['cmsid']}'")->save($delData);
						$del++;
					}
				}
			}
			echo '{"msg":"success","update":'.$update.',"add":'.$add.',"del":'.$del.'}';
		}
	}
	public function magazine($name=0, $issueid=0, $title=0) {
		// $issueid = 759;
		$cookie = "uName=%E6%BD%98%E6%80%9D%E5%98%89; uDepartment=%E5%B9%BF%E5%B7%9E+-+%E7%A0%94%E5%8F%91%E4%B8%AD%E5%BF%83-%E5%89%8D%E7%AB%AF%E5%BC%80%E5%8F%91%E9%83%A8; CMS_USER=70933; role_id=0; JSESSIONID=abc3SR5nK72Cx5hBXUqrv";

		if($issueid)
		{
			if('pcauto'==$name){
				$cmsip = 9;
			}else if('pchouse'==$name){
				$cmsip = 20;
			}
			else if('pclady'==$name){
				$cmsip = 10;
			}
			// 汽车：http://192.168.11.9:8888/
			// 家居：http://192.168.11.20:8888/
			// 女性：http://192.168.11.10:8888/
			$public_url = "http://readercms.$name.com.cn/content.do?issueId=" . $issueid;
			$url = "http://192.168.11.".$cmsip.":8888/content.do?issueId=" . $issueid;
			$data = array();
			$Issue   =   D('Issue');
			// if(0)
			if($Issue->where("magazine='$name' and issueid=$issueid ")->find())
			{
				$Article   =   D('Article');
				$result = $Article->where("magazinename='$name' and issueid=$issueid and status=1 ")->order('order')->select();
				$cmsdata = '';
				$cmscustomdata = '';
				$pre_column_name = '';
				$cms_data_array = array();
				$cms_data_article = array();
				$cms_tmp_data_array;
				foreach ($result as $element) {
					if($pre_column_name != $element['columnname'] && 0<count($cms_data_article))
					{
						array_push($cms_data_array, $cms_data_article);
						$cms_data_article = array();
						$pre_column_name = $element['columnname'];
					}
					array_push($cms_data_article, $element);
				}
				array_push($cms_data_array, $cms_data_article);
				foreach ($cms_data_array as $articles) {
					$length = count($articles);
					foreach ($articles as $key=>$element) {
						if(preg_match('/^http/',$element['preview']))
						{
							$preview = "href='".urldecode($element['preview'])."'";
						}
						else {
							$preview = "";
						}
						if(preg_match('/^http/',$element['material']))
						{
							$material = "href='".urldecode($element['material'])."'";
						}
						else {
							$material = "";
						}
						if(0==$key){
							$tdHead = '<TR id="'.$element['cmsid']. '" bgColor=#ffffff class="cmsTR" ><TD rowspan="' . $length . '" class="column">' . $element['columnname'] . '</TD>';

						}
						else {

							$tdHead = "<TR bgColor=#ffffff id='".$element['cmsid']."'>";
						}
	        // var _tdString = ''

								// $tdColumn = "<TD> ".trim($element['columnname']) . "</TD>";
								if(preg_match('/^cur/',$element['cmsid']))
								{
									// $tdArticle = "<TD id='a" . $element['cmsid'] . "'class='article '>".trim($element['title']) . "</TD>";
									$tdArticle = "<TD class='title'><input class='editText' type='text' value='".( urldecode($element['title']))."' /></TD>";
								}
								else {
									$tdArticle = "<TD id='a" . $element['cmsid'] . "'class='article '><a target='_blank' href='" . ($url.'&articleId='.(split('_',$element['cmsid'])[1]).'#'.$element['cmsid']) . "' >".trim($element['title']) . "</a></TD>";
								}
								$tdDesigner = "<TD class='designer'><input class='editText' type='text' value='".( urldecode($element['designer']))."' /></TD>";
								$tdPreview = "<TD class='preview '><a ".$preview." target='_blank' class='editLink'>查看</a></TD>";
								$tdMaterial = "<TD class='material '><a ".$material." target='_blank' class='editLink'>查看</a></TD>";
								$tdEngineer = "<TD class='engineer'><input class='editText' type='text' value='".( urldecode($element['engineer']))."' /></TD>";
								$tdRemark = "<TD class='remark'><input class='editText' type='text' value='".( urldecode($element['remark']))."' /></TD>";
								$tdFoot = "</TR>";
								$table = $tdHead . $tdColumn . $tdArticle . $tdDesigner . $tdPreview . $tdMaterial . $tdEngineer . $tdRemark . $tdFoot;
								// $tdString = "<a target='_blank' href='" . ($url.'&articleId=9232#'.$element->id) . "' >" . trim($element->innertext) . "</a>";
								if(preg_match('/^cur/',$element['cmsid']))
								{
									$cmscustomdata .= $table;
								}
								else {
									$cmsdata .= $table;
								}
					}
				}
				// foreach ($result as $element) {
				//
				// }
				// $cmsdata .= $cmscustomdata;
				$this->assign('title',$title);
				$this->assign('cmsdata',$cmsdata);
				$this->assign('cmscustomdata',$cmscustomdata);
				$this->assign('issueid',$issueid);
				$this->assign('name',$name);
				$this->show();
			}
			else
			{
				// 在数据库找不到，就新建
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
				$ret = curl_post($public_url, $data, $header, $cookie)['result'];

				Vendor('Sunra.PhpSimple.HtmlDomParser');

				$dom = str_get_html($ret);

				function addArticle($element, $a_index, $columnname, $issueid, $name)
				{
					// echo var_dump($issueid);
					// return;
					if($element)
					{
						$Article   =   D('Article');
						$data['title']  =   trim($element->innertext);
						$data['designer']    =   '?';
						$data['cmsid']    =   $element->id;
						$data['issueid']    =   $issueid;
						$data['magazinename']    =   $name;
						$data['columnname']    =   $columnname;
						$data['order']    =   $a_index;
						$data['status']    =   1;
						if($Article->where("cmsid='{$element->id}'")->find())
						{
							$Article->save($data);
						}
						else {
							$Article->add($data);
						}
					}
				}
				$a_index = 0;
				$column_titles = $dom->find('[class=tree-toggle nav-header tree-node]');
				$leaf_containers = $dom->find('[class=nav nav-list tree]');
				foreach ( $leaf_containers as $key=>$leaf_container) {
					$column_title_text = $column_titles[$key]->innertext;
					$leafs = $leaf_container->find('.leaf');
					foreach ( $leafs as $leaf) {
						addArticle($leaf, $a_index, $column_title_text, $issueid, $name);
						$a_index++;
					}
				}
				// return;
				// $magazinedata['title']  =   trim($element->innertext);
				$magazinedata['magazine']    =   $name;
				$magazinedata['device']    =   'ipad/iphone';
				$magazinedata['issueid']    =   $issueid;
				$magazinedata['status']    =   1;
				$Issue->add($magazinedata);
				echo '<p style="text-align:center;">首次登记成功，3秒后自动跳转</p><script>setTimeout(function(){location.reload();},3000);</script>';
			}
		}
		else
		{
			echo 'error,can not find issue id';
			// 获取某杂志的全部issue

		}
	}

	public function article() {
		// echo 'post.name';
		// exit;
		$id = trim(I('post.articleID'));
		$issueid = trim(I('post.issueid'));
		$magazine = trim(I('post.magazine'));
		if($id)
		{
			$Article   =   D('Article');
			$data[trim(I('post.name'))]  =   urldecode(trim(I('post.value')));
			// echo trim(I('post.name'));
			// return;
			if(preg_match('/^cur/',$id))
			{
				if($Article->where("magazinename='{$magazine}' and cmsid='{$id}' and issueid=$issueid ")->find())
				{
					echo 'id='.$id.' is exist.';
					// $data['designer'] = '55';
					$Article->where("magazinename='{$magazine}' and cmsid='{$id}' and issueid=$issueid ")->save($data);
				}
				else
				{
					$data['status'] = 1;
					echo 'id='.$id.' is not exist.';
					$data['cmsid'] = $id;
					$data['issueid'] = $issueid;
					$data['columnname'] = '自定义';
					$Article->add($data);
				}
			}
			else
			{
				if($Article->where("magazinename='{$magazine}' and cmsid='{$id}'")->find())
				{

					$Article->where("magazinename='{$magazine}' and cmsid='$id'")->save($data);
					echo "exist cmsid={$id}";
				}
				else {
					echo "no cmsid=‘{$id}’";
				}
			}

			echo "update article success";
		}
		else {

		}



	}

}
