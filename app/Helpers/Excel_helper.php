<?php

use App\Libraries\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

        
    function warna($warna){
        return  array(
                    'fill' => array(
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => array('argb' => $warna)
                    )
                );
    }

    function border(){
        return array(
                    'borders' => array(
                        'top'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'bottom'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'left'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'right'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    )
                );
    }

    function borderBold(){
        return array(
                    'borders' => array(
                        'top'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'bottom'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'left'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'right'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    ),
                    'font' => array('bold'=>true)
                );
    }

    function borderBoldCenter(){
        return array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ),
                    'borders' => array(
                        'top'=> array('style'=>  Border::BORDER_THIN),
                        'bottom'=> array('style'=>  Border::BORDER_THIN),
                        'left'=> array('style'=>  Border::BORDER_THIN),
                        'right'=> array('style'=>  Border::BORDER_THIN)
                    ),
                    'font' => array('bold'=>true)
                );
    }

    function right(){
        return array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    )
                );
    }

    function rightBorder(){
        return array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ),
                    'borders' => array(
                        'top'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'bottom'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'left'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'right'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    )
                );
    }

    function rightBorderBold(){
        return array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ),
                    'borders' => array(
                        'top'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'bottom'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'left'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'right'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    ),
                    'font' => array('bold'=>true)
                );
    }

    function center(){
        return array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    )
                );
    }
    function top(){
        return array(
                    'alignment' => array(
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
                    )
                );
    }

    function centerBorder(){
        return array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ),
                    'borders' => array(
                        'top'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'bottom'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'left'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                        'right'=> array('style'=>  \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    )
                );
    }

    function centerBold(){
        return array(
                    'alignment' => array(
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ),
                    'font' => array('bold'=>true)
                );
    }

    function bold(){
        return array('font'=> array('bold'=>true));
    }