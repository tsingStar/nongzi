<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
require_once __DIR__ . '/../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';

use think\Exception;

class Excel
{
    /**
     * 一键导 出Excel
     * @param array $header Excel 头部 ["COL1","COL2","COL3",...]
     * @param array $body 和头部长度相等字段查询出的数据就可以直接导出
     * @param null|string $name 文件名，不包含扩展名，为空默认为当前时间
     * @param string|int $version Excel版本 2003|2007
     * @param int $num 工作区数量
     * @param array $extraTitle 工作区标题
     * @return string
     */
    public static function export($head, $body, $name = null, $num = 1, $extraTitle = [], $version = '2007')
    {
        try {
            // 输出 Excel 文件头
            $name = empty($name) ? date('YmdHis') : $name;

            $objPHPExcel = new \PHPExcel();

            if ($num == 1) {
                $sheetPHPExcel = $objPHPExcel->setActiveSheetIndex(0);
                $char_index = range("A", "Z");

                // Excel 表格头
                foreach ($head as $key => $val) {
                    $sheetPHPExcel->setCellValue("{$char_index[$key]}1", $val);
                }

                // Excel body 部分
                foreach ($body as $key => $val) {
                    $row = $key + 2;
                    $col = 0;
                    foreach ($val as $k => $v) {
                        $sheetPHPExcel->setCellValue("{$char_index[$col]}{$row}", $v);
                        $col++;
                    }
                }
            } else {
                if (count($head) != count($body)) {
                    throw new \Exception("标题数量与内容不等");
                }

                if (count($head) != $num) {
                    throw new \Exception("工作区与标题数量不等");
                }
                for ($i = 0; $i < $num; $i++) {
                    $objPHPExcel->createSheet();
                    $sheetPHPExcel = $objPHPExcel->setActiveSheetIndex($i);
                    if (isset($extraTitle[$i])) {
                        $objPHPExcel->getActiveSheet()->setTitle($extraTitle[$i]);
                    }
                    $char_index = range("A", "Z");
                    // Excel 表格头
                    foreach ($head[$i] as $key => $val) {
                        $sheetPHPExcel->setCellValue("{$char_index[$key]}1", $val);
                    }

                    // Excel body 部分
                    foreach ($body[$i] as $key => $val) {
                        $row = $key + 2;
                        $col = 0;
                        foreach ($val as $k => $v) {
                            $sheetPHPExcel->setCellValue("{$char_index[$col]}{$row}", $v);
                            $col++;
                        }
                    }
                }
            }
            $objPHPExcel->setActiveSheetIndex(0);

            // 版本差异信息
            $version_opt = [
                '2007' => [
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'ext' => '.xlsx',
                    'write_type' => 'Excel2007',
                ],
                '2003' => ['mime' => 'application/vnd.ms-excel',
                    'ext' => '.xls',
                    'write_type' => 'Excel5',
                ],
                'pdf' => ['mime' => 'application/pdf',
                    'ext' => '.pdf',
                    'write_type' => 'PDF',
                ],
                'ods' => ['mime' => 'application/vnd.oasis.opendocument.spreadsheet',
                    'ext' => '.ods',
                    'write_type' => 'OpenDocument',
                ],
            ];

            header('Content-Type: ' . $version_opt[$version]['mime']);
            header('Content-Disposition: attachment;filename="' . $name . $version_opt[$version]['ext'] . '"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $version_opt[$version]['write_type']);
            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 导出订单
     */
    public static function exportOrder($header, $body, $name = null)
    {
        try {
            // 输出 Excel 文件头
            $name = empty($name) ? date('YmdHis') : $name;

            $objPHPExcel = new \PHPExcel();

            $sheetPHPExcel = $objPHPExcel->setActiveSheetIndex(0);
            $char_index = range("A", "Z");

            // Excel 表格头
            foreach ($head as $key => $val) {
                $sheetPHPExcel->setCellValue("{$char_index[$key]}1", $val);
            }

            // Excel body 部分
            $temp_row = 2;
            foreach ($body as $key => $val) {
                $row = $key + $temp_row;
                $col = 0;
                foreach ($val as $k => $v) {
                    $column_index = $char_index[$col];
                    //清空要合并的首行单元格值，用于填充合并后的单元格值
                    $objPHPExcel->getActiveSheet()->setCellValue($column_index . '' . $row, $v);
                    //获取待合并行数

                    //合并单元格,值为''
                    $objPHPExcel->getActiveSheet()->mergeCells($column_index . '' . $beginRow . ":" . $column_index . '' . $endRow);

                    $sheetPHPExcel->setCellValue("{$char_index[$col]}{$row}", $v);
                    $col++;
                }
            }

            // 版本差异信息
            $version_opt = [
                '2007' => [
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'ext' => '.xlsx',
                    'write_type' => 'Excel2007',
                ],
                '2003' => ['mime' => 'application/vnd.ms-excel',
                    'ext' => '.xls',
                    'write_type' => 'Excel5',
                ],
                'pdf' => ['mime' => 'application/pdf',
                    'ext' => '.pdf',
                    'write_type' => 'PDF',
                ],
                'ods' => ['mime' => 'application/vnd.oasis.opendocument.spreadsheet',
                    'ext' => '.ods',
                    'write_type' => 'OpenDocument',
                ],
            ];

            header('Content-Type: ' . $version_opt[$version]['mime']);
            header('Content-Disposition: attachment;filename="' . $name . $version_opt[$version]['ext'] . '"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $version_opt[$version]['write_type']);
            $objWriter->save('php://output');
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * 解析 Excel 数据并返回数组数据
     * @param string $file Excel 路径名文件名
     * @param array $header 表头对应字段信息 ['A'=>'field1', 'B'=>'field2', ...]
     * @param string $type Excel2007|Excel5
     * @return array
     * @throws Exception
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public static function parse($file, $header, $type = '')
    {
        $type = self::getType($file, $type);
        $objReader = PHPExcel_IOFactory::createReader($type);
        $objPHPExcel = $objReader->load($file);
        $sheet_count = $objPHPExcel->getSheetCount();
//        if($sheet_count != count($header)){
//            throw new Exception("字段数组与工作区数量不等".$sheet_count."@".count($header));
//        }
        // 数据数组
        $data = [];
        foreach ($header as $key => $val) {
            // 已导入数据计数
            $count = 0;
            //当前工作区数据
            $sheet_data = [];
            // 跳过第一行
            foreach ($objPHPExcel->getSheet($key)->getRowIterator(2) as $row) {
                // 逐个单元格读取，减少内存消耗
                $cellIterator = $row->getCellIterator();
                // 不跳过空值
                $cellIterator->setIterateOnlyExistingCells();
                // 只读取显示的行、列，跳过隐藏行、列
                if ($objPHPExcel->getActiveSheet()->getRowDimension($row->getRowIndex())->getVisible()) {
                    $rowData = [];
                    foreach ($cellIterator as $cell) {
                        if ($objPHPExcel->getActiveSheet()->getColumnDimension($cell->getColumn())->getVisible()) {
                            if (isset($val[$cell->getColumn()])) {
                                $rowData[$val[$cell->getColumn()]] = $cell->getValue();
                            }
                        }
                    }
                    $sheet_data[] = $rowData;
                    $count++;
                }
            }
            $data[] = [
                "data" => $sheet_data,
                "total" => $count
            ];
        }
        return $data;
    }

    /**
     * 解析 Excel 数据并写入到数据库
     * @param string $file Excel 路径名文件名
     * @param array $header 表头对应字段信息 ['A'=>'field1', 'B'=>'field2', ...]
     * @param int $perLimit 每次一次性写入数据库中的行数
     * @param mixed $insertFunc 写入数据库的回调函数，可以用匿名函数
     * @param string $type Excel2007|Excel5
     * @return int
     */
    public static function parse_back($file, $header, $perLimit, $insertFunc, $type = '')
    {
        $type = self::getType($file, $type);
        $objReader = PHPExcel_IOFactory::createReader($type);
        $objPHPExcel = $objReader->load($file);
        // 数据数组
        $data = [];
        // 已导入数据计数
        $count = 0;
        // 跳过第一行
        foreach ($objPHPExcel->getSheet()->getRowIterator(2) as $row) {
            // 逐个单元格读取，减少内存消耗
            $cellIterator = $row->getCellIterator();
            // 不跳过空值
            $cellIterator->setIterateOnlyExistingCells();
            // 只读取显示的行、列，跳过隐藏行、列
            if ($objPHPExcel->getActiveSheet()->getRowDimension($row->getRowIndex())->getVisible()) {
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    if ($objPHPExcel->getActiveSheet()->getColumnDimension($cell->getColumn())->getVisible()) {
                        if (isset($header[$cell->getColumn()])) {
                            $rowData[$header[$cell->getColumn()]] = $cell->getValue();
                        }
                    }
                }
                $data[] = $rowData;
                $count++;
                // 数据分批写入数据库，防止一条SQL太长数据库不支持
                if ($count && $count % $perLimit == 0) {
                    $insertFunc($data);
                    // 清空已有数据
                    $data = [];
                }
            }
        }
        // 写入剩余数据
        if ($data) {
            $insertFunc($data);
        }

        return $count;
    }


    /**
     * 解析 Excel 获取第一行头信息
     * @param string $file Excel 路径名文件名
     * @param string $type Excel2007|Excel5
     * @return array
     */
    public static function parseHeader($file, $type = '')
    {
        $type = self::getType($file, $type);
        $objReader = PHPExcel_IOFactory::createReader($type);
        $objPHPExcel = $objReader->load($file);
        $header = [];
        foreach ($objPHPExcel->getSheet()->getRowIterator() as $row) {
            // 逐个单元格读取，减少内存消耗
            $cellIterator = $row->getCellIterator();
            // 不跳过空值
            $cellIterator->setIterateOnlyExistingCells();
            if ($objPHPExcel->getActiveSheet()->getRowDimension($row->getRowIndex())->getVisible()) {
                foreach ($cellIterator as $cell) {
                    if ($objPHPExcel->getActiveSheet()->getColumnDimension($cell->getColumn())->getVisible()) {
                        $header[$cell->getColumn()] = $cell->getValue();
                    }
                }
                break;
            }
        }

        return $header;
    }

    /**
     * 自动获取 Excel 类型
     * @param string $file Excel 路径名文件名
     * @param string $type Excel2007|Excel5
     * @return string
     * @throws Exception
     */
    private static function getType($file, $type = '')
    {
        if (!$type) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            switch ($ext) {
                case 'xls' :
                    $type = 'Excel5';
                    break;
                case 'xlsx' :
                    $type = 'Excel2007';
                    break;
                default:
                    throw new Exception('请指定Excel的类型');
            }
        }
        return $type;
    }

    /**
     * 将 Excel 时间转为标准的时间格式
     * @param $date
     * @param bool $time
     * @return array|int|string
     */
    public static function excelTime($date, $time = false)
    {
        if (function_exists('GregorianToJD')) {
            if (is_numeric($date)) {
                $jd = GregorianToJD(1, 1, 1970);
                $gregorian = JDToGregorian($jd + intval($date) - 25569);
                $date = explode('/', $gregorian);
                $date_str = str_pad($date [2], 4, '0', STR_PAD_LEFT)
                    . "-" . str_pad($date [0], 2, '0', STR_PAD_LEFT)
                    . "-" . str_pad($date [1], 2, '0', STR_PAD_LEFT)
                    . ($time ? " 00:00:00" : '');

                return $date_str;
            }
        } else {
            $date = $date > 25568 ? $date + 1 : 25569;
            $ofs = (70 * 365 + 17 + 3) * 86400;
            $date = date("Y-m-d", ($date * 86400) - $ofs) . ($time ? " 00:00:00" : '');
        }

        return $date;
    }

}
