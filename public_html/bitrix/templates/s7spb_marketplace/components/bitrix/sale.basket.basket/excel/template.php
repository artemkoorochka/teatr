<?
/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $templateName
 * @var CMain $APPLICATION
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $giftParameters
 */
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);


////////////////////////////////////
///
//1 Часть: запись в файл

//Создание объекта класса библиотеки
$objPHPExcel = new PHPExcel();

//Указываем страницу, с которой работаем
$objPHPExcel->setActiveSheetIndex(0);

//Получаем страницу, с которой будем работать
$active_sheet = $objPHPExcel->getActiveSheet();

//Создание новой страницы(пример)
//$objPHPExcel->createSheet();

//Ориентация и размер страницы
// $active_sheet->getPageSetup()
// ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$active_sheet->getPageSetup()
    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$active_sheet->getPageSetup()
    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

//Имя страницы
$active_sheet->setTitle("Данные из docs");

//Ширина стобцов
$active_sheet->getColumnDimension('A')->setWidth(8);
$active_sheet->getColumnDimension('B')->setWidth(10);
$active_sheet->getColumnDimension('C')->setWidth(90);

//Объединение ячеек
$active_sheet->mergeCells('A1:C1');

//Высота строки
$active_sheet->getRowDimension('1')->setRowHeight(30);

//Вставить данные(примеры)
//Нумерация строк начинается с 1, координаты A1 - 0,1
$active_sheet->setCellValueByColumnAndRow(0, 1, 'Сегодня '.date('d-m-Y'));
$active_sheet->setCellValue('A3', 'id');
$active_sheet->setCellValue('B3', 'name');
$active_sheet->setCellValue('C3', 'info');

//Отправляем заголовки с типом контекста и именем файла
header("Content-Type:application/vnd.ms-excel");
header("Content-Disposition:attachment;filename=simple.xlsx");

//Сохраняем файл с помощью PHPExcel_IOFactory и указываем тип Excel
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel15');

//Отправляем файл
$objWriter->save('php://output');

?>