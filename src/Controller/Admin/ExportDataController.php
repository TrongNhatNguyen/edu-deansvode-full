<?php

namespace App\Controller\Admin;

use App\Service\Admin\CountryService;
use App\Service\Admin\VoteManagerService;
use App\Service\Admin\ZoneService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// PhpSpreadsheet required namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @Route("/admin")
 */
class ExportDataController extends AbstractController
{
    private $zoneService;
    private $countryService;
    private $voteManagerService;

    public function __construct(
        ZoneService $zoneService,
        CountryService $countryService,
        VoteManagerService $voteManagerService
    ) {
        $this->zoneService = $zoneService;
        $this->countryService = $countryService;
        $this->voteManagerService = $voteManagerService;
    }

    /**
     * @Route("/export-area-data", name="admin_export_area_data")
     */
    public function exportAreaData(Request $request)
    {
        $reqParams = $request->query->all();
        $listData = $this->zoneService->getExportZoneList($reqParams);
       
        $spreadsheet = new Spreadsheet();
        $writer      = new Xlsx($spreadsheet);
        $sheet = $spreadsheet->getActiveSheet();
        $fileName = 'Area_list_'.date('dMy').'.xlsx';
        $titleName = 'Area List';
        // title:
        $sheet->setTitle($titleName);
        $spreadsheet->getActiveSheet()->mergeCells('A1:I1')->setCellValue('D1', $titleName);
        $spreadsheet->getActiveSheet()->getCell('A1')
        ->setValue($titleName.' | '.date('d-m-Y'));
        // thread:
        $sheet->setCellValue('A3', 'ID');
        $sheet->setCellValue('B3', 'NAME');
        $sheet->setCellValue('C3', 'ALIAS');
        $sheet->setCellValue('D3', 'IMAGE NAME');
        $sheet->setCellValue('E3', 'SORT');
        $sheet->setCellValue('F3', 'ACTIVE');
        $sheet->setCellValue('G3', 'DATE CREATE');
        $sheet->setCellValue('H3', 'DATE UPDATE');
        // tbody:
        $lignes = 4;
        foreach ($listData as $data) {
            $sheet->setCellValue('A'.$lignes, $data->getId());
            $sheet->setCellValue('B'.$lignes, $data->getName());
            $sheet->setCellValue('C'.$lignes, $data->getSlug());
            $sheet->setCellValue('D'.$lignes, $data->getImage());
            $sheet->setCellValue('E'.$lignes, $data->getSort());
            $sheet->setCellValue('F'.$lignes, ($data->getStatus() == 1) ? 'Yes' : 'No');
            $sheet->setCellValue('G'.$lignes, $data->getCreatedAt()->format('d-m-Y H:i:s'));
            $sheet->setCellValue('H'.$lignes, $data->getUpdatedAt()->format('d-m-Y H:i:s'));
            $lignes++;
        }

        foreach (range('A', 'I') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        };
        $spreadsheet->getActiveSheet()->getStyle('A1:Z50')->applyFromArray($this->generalStyle());
        $spreadsheet->getActiveSheet()->getStyle('A3:H3')->applyFromArray($this->theadCellStyle());
        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($this->titleCellStyle());

        // Temporary file:
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        $writer->save($temp_file);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/export-data-country", name="admin_export_data_country")
     */
    public function exportCountryData(Request $request)
    {
        $reqParams = $request->query->all();
        $listData = $this->countryService->getExportCountryList($reqParams);

        $spreadsheet = new Spreadsheet();
        $writer      = new Xlsx($spreadsheet);
        $sheet = $spreadsheet->getActiveSheet();

        // ==== file name:
        $fileName = 'Country_list_'.date('dMy').'.xlsx';
        $titleName = "Country List";

        // title:
        $sheet->setTitle($titleName);
        $spreadsheet->getActiveSheet()->mergeCells('A1:I1')->setCellValue('D1', $titleName);
        $spreadsheet->getActiveSheet()->getCell('A1')
        ->setValue($titleName.' | '.date('d-m-Y'));
        // thread:
        $sheet->setCellValue('A3', 'ID');
        $sheet->setCellValue('B3', 'ISO CODE');
        $sheet->setCellValue('C3', 'NAME');
        $sheet->setCellValue('D3', 'ALIAS');
        $sheet->setCellValue('E3', 'ZONE');
        $sheet->setCellValue('F3', 'SORT');
        $sheet->setCellValue('G3', 'ACTIVE');
        $sheet->setCellValue('H3', 'DATE CREATE');
        $sheet->setCellValue('I3', 'DATE UPDATE');
        // tbody:
        $lignes = 4;
        foreach ($listData as $data) {
            $sheet->setCellValue('A'.$lignes, $data->getId());
            $sheet->setCellValue('B'.$lignes, $data->getIsoCode());
            $sheet->setCellValue('C'.$lignes, $data->getName());
            $sheet->setCellValue('D'.$lignes, $data->getSlug());
            $sheet->setCellValue('E'.$lignes, $data->getZone()->getName());
            $sheet->setCellValue('F'.$lignes, $data->getSort());
            $sheet->setCellValue('G'.$lignes, ($data->getStatus() == 1) ? 'Yes' : 'No');
            $sheet->setCellValue('H'.$lignes, $data->getCreatedAt()->format('d-m-Y H:i:s'));
            $sheet->setCellValue('I'.$lignes, $data->getUpdatedAt()->format('d-m-Y H:i:s'));
            $lignes++;
        }
    
        foreach (range('A', 'J') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        };
        $spreadsheet->getActiveSheet()->getStyle('A1:Z200')->applyFromArray($this->generalStyle());
        $spreadsheet->getActiveSheet()->getStyle('A3:I3')->applyFromArray($this->theadCellStyle());
        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($this->titleCellStyle());
        
        // Temporary file:
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        $writer->save($temp_file);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/export-data-vote-session", name="admin_export_data_vote_session")
     */
    public function exportVoteSessionData(Request $request)
    {
        $reqParams = $request->query->all();
        $listData = $this->voteManagerService->getListVoteSession($reqParams);

        $spreadsheet = new Spreadsheet();
        $writer      = new Xlsx($spreadsheet);
        $sheet = $spreadsheet->getActiveSheet();

        // ==== file name:
        $fileName = 'Vote_session_list_'.date('dMy').'.xlsx';
        $titleName = "Vote Session List";

        // title:
        $sheet->setTitle($titleName);
        $spreadsheet->getActiveSheet()->mergeCells('A1:G1');
        $spreadsheet->getActiveSheet()->getCell('A1')
        ->setValue($titleName.' | '.date('d-m-Y'));
        // thread:
        $sheet->setCellValue('A3', 'ID');
        $sheet->setCellValue('B3', 'YEAR');
        $sheet->setCellValue('C3', 'START DAY');
        $sheet->setCellValue('D3', 'CLOSE DAY');
        $sheet->setCellValue('E3', 'ACTIVE');
        $sheet->setCellValue('F3', 'DATE CREATE');
        $sheet->setCellValue('G3', 'DATE UPDATE');
        // tbody:
        $lignes = 4;
        foreach ($listData as $data) {
            $sheet->setCellValue('A'.$lignes, $data->getId());
            $sheet->setCellValue('B'.$lignes, $data->getYear());
            $sheet->setCellValue('C'.$lignes, $data->getBeginAt()->format('d-m-Y H:i:s'));
            $sheet->setCellValue(
                'D'.$lignes,
                ($data->getClosedAt() == null) ? '' : $data->getClosedAt()->format('d-m-Y H:i:s')
            );
            $sheet->setCellValue('E'.$lignes, ($data->getStatus() == 1) ? 'Yes' : 'No');
            $sheet->setCellValue('F'.$lignes, $data->getCreatedAt()->format('d-m-Y H:i:s'));
            $sheet->setCellValue('G'.$lignes, $data->getUpdatedAt()->format('d-m-Y H:i:s'));
            $lignes++;
        }
    
        foreach (range('A', 'H') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        };
        $spreadsheet->getActiveSheet()->getStyle('A1:Z300')->applyFromArray($this->generalStyle());
        $spreadsheet->getActiveSheet()->getStyle('A3:G3')->applyFromArray($this->theadCellStyle());
        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($this->titleCellStyle());
        
        // Temporary file:
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        $writer->save($temp_file);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    // ==== Style:
    public function titleCellStyle()
    {
        return array(
            'font' => [
                'name'      =>  'Arial',
                'size'      =>  14,
                'bold'      =>  true,
                'color' => ['argb' => '045569'],
            ]
        );
    }

    public function theadCellStyle()
    {
        return array(
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '040e10'],
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '045569']
            ],
            'font' => [
                'name'      =>  'Arial',
                'size'      =>  9,
                'bold'      =>  true,
                'color' => ['argb' => 'ffffff'],
            ]
        );
    }

    public function generalStyle()
    {
        return array(
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_DISTRIBUTED,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        );
    }
}
