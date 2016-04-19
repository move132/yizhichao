<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function getListHeader($list = array(), $data = array())
{
	$sList = '';
	$iList = count($list);
	foreach($list as $key=>$item){
		if($key + 1 == $iList){
			$sList .= '<li class="active">'.$item['name'].'</li>';
		}else{
			if(isset($item['data'])){
				$sList .= '<li><a href="javascript:;" onclick="clickPage(\''.$item['url'].'\', \''.htmlspecialchars(json_encode($item['data'])).'\')">'.$item['name'].'</a></li>';
			}else{
				$sList .= '<li><a href="javascript:;" onclick="clickPage(\''.$item['url'].'\')">'.$item['name'].'</a></li>';
			}			
		}		
	}
	
	$sData = '';
	foreach($data as $key=> $item){
		if(isset($item['reflush'])){
			$sData .= '<button type="button" class="btn btn-link" onclick="reflush(\''.$item['url'].'\', \''.htmlspecialchars(json_encode($item['data'])).'\')">'.$item['name'].'</button>';
		}else{
			if(isset($item['data'])){
				$sData .= '<button type="button" class="btn btn-link" onclick="clickPage(\''.$item['url'].'\', \''.htmlspecialchars(json_encode($item['data'])).'\')">'.$item['name'].'</button>';
			}else{
				$sData .= '<button type="button" class="btn btn-link" onclick="clickPage(\''.$item['url'].'\')">'.$item['name'].'</button>';
			}			
		}		
	}
	
print <<<EOF
<div class="main_hd">
	<div class="row">
		<div class="col-md-6">
			<ol class="breadcrumb bg-success">
				$sList
			</ol> 		
		</div>
		<div class="col-md-6 tr">
			$sData
		</div>
	</div>
</div>
EOF;
}