<?php
header("content-type:text/html;charset=utf-8");
//自定义常量
//define('常量名','值');
//define('PATH',123);
//echo PATH;
//echo __FILE__.'<br>';
//echo dirname(__FILE__).'<br>';
$curp=isset($_GET['pro'])?$_GET['pro']:'1';
//echo $curp.'<br>';
//查询条件
//新闻标题
$ntitle=isset($_GET['ntitle'])?$_GET['ntitle']:'';
//新闻内容
$ntype=isset($_GET['ntype'])?$_GET['ntype']:'';
$where='';
if($ntitle!='')
{
	$where.=" where title like'%{$ntitle}%' ";
}
if($ntype!='')
{
	if($where!='')
	{
		$where.=" and tid='{$ntype}' ";
	}
	else
	{
		$where.=" where tid='{$ntype}' ";
	}
}
define('IN_PHP',34);
$path=str_replace('\\','/',dirname(dirname(__FILE__)));
define('PATH',$path);
require_once PATH."/web/class/mysql.class.php";
require_once PATH."/web/class/Page.class.php";
$obj=new db_mysql('localhost','root','12345678','myphp');
$sql1="select count(*) as n from tb_news";
$total=$obj->getone($sql1);
$fenye=new Page($total['n'],15);
$sql="select n.id,title,tname from tb_news as n left join tb_type on tid=tb_type.id {$where} order by n.id desc limit ".$fenye->limit();
$arr=$obj->getall($sql);
$sql2="select * from tb_type ";
$arr2=$obj->getall($sql2);
//查询总的记录个数
echo '<pre>';
//print_r($arr2);
echo '</pre>';
?>
<html>
	<head>
		<title>新闻列表</title>
		<script src='gd/jquery-1.12.4.js'></script>
		<script>
		<!--
		function delone2(a,b)
		{
			if(confirm('确定要删除吗?'))
				{
					$.get('news_del2.php',{'id':a},function(d)
					{
						if(d.flg)
						{
							alert('删除成功');
							location.href="news_index.php?pno="+b;
						}
						else
							{
								alert('删除失败');
							}
					},'json')
				}
				else
				{
					alert('1');
				}
		}
			function mytst()
			{
				if (confirm('确定要删除吗?'))
				{
					alert('yes');
				}
				else
				{
					alert('no');
				}
			}
			function delone(a,b)
			{
				//if (confirm('确定要删除吗?'))
				//{
					//执行true
					$.get('news_del.php',{'id':a,'pid':b},function(d)
						{
							if(d=='yes')
							{
								alert('删除成功');
								location.href="news_index.php";
							}
							else
							{
								alert('删除失败');
								location.href="news_index.php";
							}
						},'text')
					//location.href='news_del.php?id='+a+'&pid='+b;
				//}
			}
			function abc(obj)
			{
				//alert(typeof(obj));//输出类型
				var cheks=document.getElementsByName('all');
				for(var i=0;i<cheks.length;i++)
				{
					cheks[i].checked=obj.checked;
				}
			}
			function delall2()
			{
					var str='';
					$('.sels').each(function(x,y)
					{
						if(y.checked==true)
						{
							str+=$(y).val()+',';
						}
					})
					if(str=='')
					{
							alert('您未选择');
							exit();
					}
				if(confirm('确定要删除吗?'))
				{
					$.get('news_del2.php',{'id':str,'aa':'as'},function(d){
						if(d.flg)
						{
							alert('删除成功');
							location.href="news_index.php";
						}
						else
						{
							alert('删除失败');
						}
					},'json');	
				}
			}
			function delall(curp)
			{
				var curselid='';
				$('.sels').each(function(x,y)
				{
					if(y.checked==true)
					{
						curselid+=y.value+',';
					}
				})
					$.get('news_del.php',{'act':'delall','pid':curp,'id':curselid},function(d)
						{
							if(d=='yes')
							{
								alert('删除成功');
								location.href="news_index.php";
							}
							else
							{
								alert('删除失败');
								location.href="news_index.php";
							}
						},'text')
				//alert(curselid);
				//location.href="news_del.php?act=delall&id="+curselid+"&pid="+curp;
			}
		-->
		</script>
	</head>
	<body>
	<form action='news_index.php' method='get' >
	新闻标题:<input type='text' name='ntitle' value="<?=$ntitle?>">
	新闻类型:
	<select name='ntype'>
		<option value='' >--请选择--</option>
		<?php
			foreach($arr2 as $v)
			{	
				$sel='';
				if($ntype==$v['id'])
					{
						$sel.="selected";
					}
				echo "<option value='{$v['id']}' $sel>{$v['tname']}</option>";
			}
		?>
	</select>
	<input type='submit' value='查询'>
	</form>
	<a href='javascript:;'onclick='mytst();'>测试</a>
		<table>
			<tr>
				<td>序号<input type='checkbox' onclick="abc(this);" ></td><td>新闻标题</td>
				<td>新闻类型</td><td><a href='news_add.php'>添加</a></td>
			</tr>
			
			<?php
			for($i=0;$i<count($arr);$i++)
			{
			?>
			<tr>
				<td><?php echo $arr[$i]['id']?><input type='checkbox' value='<?php echo $arr[$i]['id']?>' name='all' class='sels'></td>
				<td><?php echo $arr[$i]['title']?></td>
				<td><?php echo $arr[$i]['tname']?></td>
				<td><a href='news_update.php?nid=<?php echo $arr[$i]['id']?>&page=<?php echo $curp;?>'>修改</a>&nbsp;&nbsp;&nbsp;
				<!--<a href='news_del.php?nid=<?php echo $arr[$i]['id']?>&pid=<?php echo $curp;?>'>删除</a></td>
				<a href="javascript:delone('<?=$arr[$i]['id']?>','<?=$curp;?>')";' >删除</a>-->
				<a href="javascript:delone2('<?=$arr[$i]['id']?>','<?=$curp;?>')";' >删除</a>
			</tr>
			<?php
			}
			?>
			<tr>
				<td colspan="4">
				<?php
					echo $fenye->pageBar(5);
				?>
				&nbsp;<!--<a href='javascript:delall("<?=$curp?>");'>全删</a>-->
					<a href="javascript:;" onclick="delall2()">全删</a>
				</td>
			</tr>
		</table>
	</body>
</html>