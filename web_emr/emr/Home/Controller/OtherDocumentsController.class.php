<?php
namespace Home\Controller;
USE Components\TemrController;
class OtherDocumentsController extends TemrController{
	public function _empty(){
        echo '<meta charset=utf-8 />';
        echo "<h1>",'非法操作....',"<h1>";
    }
	public function showAdd()
	{
		$zhuyuan_id = $_GET['zhuyuan_id'];
		$this->assign("zhuyuan_id",$zhuyuan_id);
		$this->display();
	}
	
	public function addOne()
	{
		$data_info = array(
			'zhuyuan_id' => $_POST['zhuyuan_id'],
			'category' => $_POST['category'],
			'zhongwen_mingcheng' => $_POST['zhongwen_mingcheng'],
			'document_type' => $_POST['document_type'],
			'data_document_id' => $_POST['data_document_id'],
			'generate_time' => date('Y-m-d H:i'),
			'generate_user_name' => $_SESSION['user_name'],
		);
		$add = M("zhuyuan_other_document")->add($data_info);
		if(!empty($add))
		{
			echo'
			  <script language="javascript">
						  var t;
						  function showTimer(){
						location.href = "showList/zhuyuan_id/'.$_POST['zhuyuan_id'].'";
						  window.clearTimeout(t);
					}
			  t = window.setTimeout(showTimer,1000);
			  </script>
			 ';
			
			$this->assign('system_info','其它文书添加成功,1秒钟后将自动跳转回知情同意书列表页面。');
			$this->display("System:showRight");
		}
		else
		{
			echo'
			  <script language="javascript">
						  var t;
						  function showTimer(){
						location.href = "showZhiqingshuList/zhuyuan_id/'.$_POST['zhuyuan_id'].'";
						  window.clearTimeout(t);
					}
			  t = window.setTimeout(showTimer,1000);
			  </script>
			 ';
			
			$this->assign('system_info','其它文书添加成功,1秒钟后将自动跳转回知情同意书列表页面。');
			$this->display("System:showError");
		}
	}
	
	public function showList()
	{
		$zhuyuan_id = $_GET['zhuyuan_id'];
		$this->assign('zhuyuan_id',$zhuyuan_id);
		$page = intval($_GET['page']);
		if(empty($page))
		{
			$page = '1';
		}
		$lujin = "/zhuyuan_id/".$zhuyuan_id;
		$sql = "select * from zhuyuan_other_document where zhuyuan_id = '$zhuyuan_id'";
		$generate_time = $_GET['generate_time'];
		if(!empty($generate_time))
		{
			$sql .= "and generate_time like '$generate_time%' ";
			$lujin .= "generate_time/".$generate_time;
			$this->assign('generate_time',$generate_time);
		}
		$category = $_GET['category'];
		if(!empty($category))
		{
			$sql .= "and category = '$category' ";
			$lujin .= "category/".$category;
			$this->assign('category',$category);
		}
		$zhongwen_mingcheng = $_GET['zhongwen_mingcheng'];
		if(!empty($zhongwen_mingcheng))
		{
			$sql .= "and zhongwen_mingcheng like '%$zhongwen_mingcheng%' ";
			$lujin .= "zhongwen_mingcheng/".$zhongwen_mingcheng;
			$this->assign('zhongwen_mingcheng',$zhongwen_mingcheng);
		}
		$generate_user_name = $_GET['generate_user_name'];
		if(!empty($generate_user_name))
		{
			$sql .= "and generate_user_name = '$generate_user_name' ";
			$lujin .= "generate_user_name/".$generate_user_name;
			$this->assign('generate_user_name',$generate_user_name);
		}
		$tongyishu = M();
		$data_all = $tongyishu->query($sql);
		$zongshu = count($data_all);
		$page_tiaoshu = 20;
		$yeshu = ceil($zongshu/$page_tiaoshu);
		$kaishi = ($page - 1)*$page_tiaoshu;
		$sql .= "order by id desc limit $kaishi,$page_tiaoshu ";
		$data = $tongyishu->query($sql);
		$this->assign('lujin',$lujin);
		$this->assign('yeshu',$yeshu);
		$this->assign('page',$page);
		$this->assign('zongshu',$zongshu);
		$this->assign('page_tiaoshu',$page_tiaoshu);
		$this->assign('data',$data);	
		$this->display();
	}
	
	public function editDocument()
	{
		$document_id = intval($_GET['document_id']);
		$zhuyuan_id = strip_tags($_GET['zhuyuan_id']);
		$this->assign("zhuyuan_id",$zhuyuan_id);
		$document_info = M("zhuyuan_other_document")->where("id = '$document_id' ")->find();
		if(!empty($document_info))
		{
			$this->assign("document_info",$document_info);
			$this->display();
		}
		else
		{
			echo'
			  <script language="javascript">
						  var t;
						  function showTimer(){
						location.href = "/web_tiantan/ZhiqingTongyishu/showZhiqingshuList/zhuyuan_id/'.$zhuyuan_id.'";
						  window.clearTimeout(t);
					}
			  t = window.setTimeout(showTimer,1000);
			  </script>
			 ';
			
			$this->assign('system_info','此份文书不存在,1秒钟后将自动跳转回知情同意书列表页面。');
			$this->display("System:showError");
		}
	}

	public function deleteOneDocument()
	{
		$document_id = intval($_GET['document_id']);
		$delete_result = M("zhuyuan_other_document")->where("id = '$document_id' ")->delete();
		if(!empty($delete_result))
		{
			echo 'yes';
		}
		else
		{
			echo 'no';
		}
	}
}
