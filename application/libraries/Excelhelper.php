<?php
class Excelhelper
{
    public function __construct(){
        require_once APPPATH.'third_party/PHPExcel/PHPExcel.php';
        require_once APPPATH.'third_party/PHPExcel/PHPExcel/IOFactory.php';
    }
    public function load($filepath, $type = 'Excel5')
    {
        $objReader = PHPExcel_IOFactory::createReader("Excel5");
        $objPHPExcel = $objReader->load($filepath);

        $sheetSelected = 0;
        $objPHPExcel->setActiveSheetIndex($sheetSelected);
        $data = array();
        foreach($objPHPExcel->getWorksheetIterator() as $worksheet) {
            foreach($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                foreach($cellIterator as $cell) {
                    if(! is_null($cell)) {
                        $data[$row->getRowIndex()][] = $cell->getCalculatedValue();
                    }
                }
            }
        }
        return $data;
    }
    /*
     * @ dataArray  二维数组
     * @ filename   文件名（不带后缀）可以为空
     * @ suffix  文件后缀  xls = 2013Excel(默认)    xlsx = 2017Excel 
     * @ path 
     * 
     */
    public function export($dataArray,$filename='',$suffix='xls',$path='')
    {
        if($filename == ""){
            $filename = 'Products_'.date('md');
        }
        if($suffix == 'xlsx'){
            $etype = 'Excel2007';
        }else{
            $etype = 'Excel5';
        }
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $row = 1;
        foreach($dataArray as $data){
            $col = 0;
            foreach ($data as $value){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
                $col++;
            }
            $row++;
        }
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $etype);
        if($path != ""){
            $objWriter->save($path.'/'.$filename.'.'.$suffix);
        }else{
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.'.$suffix.'"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
        }
    }
}