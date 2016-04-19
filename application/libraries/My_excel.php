<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PHPExcel二次封装
 *
 */

class My_excel {
	
	public function __construct()
	{
		set_time_limit(0);

		include_once(APPPATH.'libraries/lib/PHPExcel.php');
		include_once(APPPATH.'libraries/lib/PHPExcel/IOFactory.php');
	}

	/**
	 * 导出or保存excel文件
	 * @ $headArr excel表头列表说明
	 * @ $data excel数据列表
	 * @ $fileName 输出文件名
	 * @ $saveFile 是否保存文件【true生成文件存放在服务器，false生成文件下载】
	 */
	public function writerExcel($headArr, $data, $fileName = '', $saveFile = false){
        /*import('libraries.lib.PHPExcel.Writer.Excel2007');
        import('libraries.lib.PHPExcel.Writer.Excel5');*/
        
        $fileName .= "_".mdate("%Y_%m_%d").".xlsx";  
      
        //创建新的PHPExcel对象  
        $objPHPExcel = new PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        
        //设置表头,从第1列开始  
        $key = ord("A");
        foreach($headArr as $v){
            $colum = chr($key); 
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }
      
        $column = 2;  
        $objActSheet = $objPHPExcel->getActiveSheet();        
        //遍历二维数组的数据  
        foreach($data as $key => $rows){
            $span = ord("A");// 列写入
            
            foreach($rows as $keyName=>$value){//按照B2,C2,D2的顺序逐个写入单元格数据
                $j = chr($span);
                if(strpos($keyName, 'time') !== false){//格式化时间
                    $value = formatTime($value);
                }
                if(is_numeric($value)){
                    $value = ' '.$value;
                }
                $objActSheet->setCellValue($j.$column, $value);
                  
                $span++;//移动到当前行右边的单元格
            }
            //移动到excel的下一行  
            $column++;  
        }
      
        $filename = iconv("utf-8", "gb2312", $fileName);
        // $objPHPExcel->getActiveSheet()->setTitle($filename);//重命名表workspace【中文乱码】
        $objPHPExcel->setActiveSheetIndex(0);//设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');//Excel2007 Excel5

        if($saveFile){//脚本方式运行，保存在当前目录
        	$objWriter->save($filename);
        }else{//输出文档到页面
        	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	        header('Content-Disposition: attachment;filename="'.$filename.'"');
	        header('Cache-Control: max-age=0');
	        ob_end_clean();
	        $objWriter->save("php://output");
        }
            
        exit();
    }

    /**
     * 读取excel文件并返回内容
     * @ $file 文件绝对路径
     * @ $row 读取有效数据开始行
     * return array
     */
    public function readerExcel($file, $startRow = 2){
        $ret = array();
        if(! file_exists($file)){
            return $ret;
        }
        
    	$ext = strtolower(substr($file, strrpos($file, '.') + 1));
        if(! in_array($ext, array('xls', 'xlsx'))){
            return $ret;
        }

        if($ext == "xls"){
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
        }else{            
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        }

        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($file);
        //$objWorksheet = $objPHPExcel->getActiveSheet();
        $objWorksheet = $objPHPExcel->getSheet(0);
        //取得excel的总行数
        $highestRow = $objWorksheet->getHighestRow();
        //取得excel的总列数
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        for($row = $startRow; $row <= $highestRow; $row++){
            for($col = 0; $col < $highestColumnIndex; $col++) {
                $ret[$row-$startRow][] = trim($objWorksheet->getCellByColumnAndRow($col, $row)->getValue());
            }
        }

        return $ret;
    }
}