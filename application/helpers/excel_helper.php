<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

    function generate_excel($filename='',$sheetTitle='', $headers='',$rows='')
	{
		 
		$CI =& get_instance();
     	$CI->load->library('excel');



     	if($filename == ""){ $filename = "Records.xlsx"; }else{ $filename = str_replace(" ", "-", $filename); $filename = $filename.".xls"; }
     	if($sheetTitle == ""){ $sheetTitle = "Records"; }


		$CI->excel->setActiveSheetIndex(0);
        //name the worksheet
        $CI->excel->getActiveSheet()->setTitle($sheetTitle);
        //set cell A1 content with some text
        $CI->excel->getActiveSheet()->setCellValue('A1', $sheetTitle);

        $col = 'A'; 

        array_unshift($headers , "No");
        foreach ($headers as $key => $value) {   
        	      
                $header_name = "";     
                $header_name_parts = explode("_",$value);
                foreach ($header_name_parts as $key => $value) {
                    if($key == 0)
                    {
                        $header_name = $value;
                    }
                    else
                    {
                        $header_name = $header_name." ".$value;
                    }
                }

        		$CI->excel->getActiveSheet()->getStyle($col.'4')->getFont()->setBold(true);
           		$CI->excel->getActiveSheet()->getStyle($col.'4')->getFont()->setSize(13);
            	$CI->excel->getActiveSheet()->setCellValue($col.'4', $header_name);
            	$CI->excel->getActiveSheet()->getStyle($col.'4')->getFill()->getStartColor()->setARGB('#00ff00');
                $CI->excel->getActiveSheet()->getStyle($col.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        	
        	
        	 ++$col; 	
        }
     
        //merge cell A1 until C1
        $CI->excel->getActiveSheet()->mergeCells('A1:'.$col."1");
        //set aligment to center for that merged cell (A1 to C1)
        $CI->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //make the font become bold
        $CI->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $CI->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $CI->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#00ff00');
        for($coll = ord('A'); $coll <= ord($col); $coll++){ //$CI->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
                 //change the font size
       			$CI->excel->getActiveSheet()->getDefaultColumnDimension()->setWidth("25");
                $CI->excel->getActiveSheet()->getStyle(chr($coll))->getFont()->setSize(12);
                 
                $CI->excel->getActiveSheet()->getStyle(chr($coll))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        
        foreach ($rows  as $index=>$row){
        		$CI->excel->getActiveSheet()->getRowDimension($index+6)->setRowHeight(30);
        		array_unshift($row , $index+1);
        		
                $exceldata[] = $row;
        }
        //Fill data 
        $CI->excel->getActiveSheet()->fromArray($exceldata, null, 'A6');
         
     
        $path = "assets/panel/excel/";

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $path = "assets/panel/excel/".$filename;
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$path.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

      
        $objWriter = PHPExcel_IOFactory::createWriter($CI->excel, 'Excel5');  
     
        //use the line given below to download direct from browser, it will send file to browser for download
          //$objWriter->save('php://output'); 
        
        $objWriter->save($path);
        
        return $path;
		 
				


	}


?>