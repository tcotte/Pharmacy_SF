<?php

namespace PlatformBundle\Services;

use Yectep\PhpSpreadsheetBundle\Factory;

class ExcelService
{

    private $spreadSheet;

    public function __construct(Factory $spreadSheet)
    {
        $this->spreadSheet = $spreadSheet;
    }

    public function generateExcel($id, $user, $listProduct, $dateCommand){

        $phpExcelObject = $this->spreadSheet->createSpreadsheet();
        $sheet = $phpExcelObject->getActiveSheet();

        $phpExcelObject->getProperties()->setCreator($user->getUsername())
            ->setLastModifiedBy($user->getUsername())
            ->setTitle("Commande ".$id)
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
        $sheet->setCellValue('I1', 'Utilisateur');
        $sheet->setCellValue('J1', 'Date');

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
            $sheet->setCellValue('I' . $counter, $user->getUsername());
            $sheet->setCellValue('J'.$counter, $dateCommand->format('d/m/Y'));
            $counter++;
        }

        foreach(range('A','I') as $columnID) {
            $phpExcelObject->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        $phpExcelObject->setActiveSheetIndex(0);

        $filename='Commande_n'.$id;

        return array(
            'filename' => $filename,
            'phpExcelObject' => $phpExcelObject
        );
    }
}