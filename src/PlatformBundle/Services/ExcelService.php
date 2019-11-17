<?php

namespace PlatformBundle\Services;
use PHPExcel;
use PHPExcel_IOFactory;


class ExcelService
{


    public function generateExcel($id, $user, $listProduct){
        $phpExcelObject = new PHPExcel();
        $phpExcelObject->getProperties()->setCreator($user->getUsername())
            ->setLastModifiedBy($user->getUsername())
            ->setTitle("Commande n")
            ->setSubject("Office 2005 XLSX Test Document");

        $sheet = $phpExcelObject->setActiveSheetIndex(0);
        $sheet->setCellValue('A1', 'Désignation');
        $sheet->setCellValue('B1', 'Quantité');
        $sheet->setCellValue('C1', 'Fournisseur');
        $sheet->setCellValue('D1', 'Référence');
        $sheet->setCellValue('E1', 'Code');
        $sheet->setCellValue('F1', 'Marché');
        $sheet->setCellValue('G1', 'Conditionnement');
        $sheet->setCellValue('H1', 'Prix');

        $counter = 3;
        foreach ($listProduct as $product){
            $sheet->setCellValue('A' . $counter, $product->getProduct()->getDesignation());
            $sheet->setCellValue('B' . $counter, $product->getQuantity());
            $sheet->setCellValue('C' . $counter, $product->getProduct()->getSupplier());
            $sheet->setCellValue('D' . $counter, $product->getProduct()->getReference());
            $sheet->setCellValue('E' . $counter, $product->getProduct()->getCode());
            $sheet->setCellValue('F' . $counter, $product->getProduct()->getMarket());
            $sheet->setCellValue('G' . $counter, $product->getProduct()->getCdt());
            $sheet->setCellValue('H' . $counter, $product->getProduct()->getPrice());
            $counter++;
        }

        foreach(range('A','G') as $columnID) {
            $phpExcelObject->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $phpExcelObject->getActiveSheet()->setTitle("Commande");
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = PHPExcel_IOFactory::createWriter($phpExcelObject, 'Excel2007');
        $filename='C:\Users\Trist\Desktop\test\command'.$id.'.xlsx';
        $writer->save($filename);
        return $filename;
    }
}