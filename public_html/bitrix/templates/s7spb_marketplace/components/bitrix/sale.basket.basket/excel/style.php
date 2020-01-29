<?
/**
 * @var PHPExcel $objPHPExcel
 * Тут можно задать общие стили для ячеек
 * Например ширину колонок
 * Ориентацию листа
 */

$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);

//$objPHPExcel->getActiveSheet()->getComment('B')->setMarginLeft('150pt');