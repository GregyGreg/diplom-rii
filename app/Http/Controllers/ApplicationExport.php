<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Response;

class ApplicationExport extends Controller
{
    public function exportToPDF ()
    {

    }

    public function exportToExcel (): Response
    {
        $applications = Application::query()->with(['Authors', 'Executors'])->get();
//        dd($applications);
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'ФИО заявителя');
        $sheet->setCellValue('C1', 'ФИО исполнителя');
        $sheet->setCellValue('D1', 'Почта заявителя');
        $sheet->setCellValue('E1', 'Статус заявки');
        $sheet->setCellValue('F1', 'Причина не выполнения заявки');
        $sheet->setCellValue('G1', 'Дата создания заявки');
        $sheet->setCellValue('H1', 'Дата обработки заявки');

        $row = 2;
        foreach ($applications as $application) {
            $sheet->setCellValue('A' . $row, $application->id);
            $sheet->setCellValue('B' . $row, $application->authors->last_name . ' ' . $application->authors->name . ' ' . $application->authors->surname);
            $sheet->setCellValue('C' . $row, $application->executors->last_name . ' ' . $application->executors->name . ' ' . $application->executors->surname or 'Исполнитель не выбран');
            $sheet->setCellValue('D' . $row, $application->authors->email);
            $sheet->setCellValue('E' . $row, $application->status);
            $sheet->setCellValue('F' . $row, $application->cause_fall);
            $sheet->setCellValue('F' . $row, $application->crete_application);
            $sheet->setCellValue('F' . $row, $application->close_application);
            $row++;
        }

        // Сохранение файла Excel во временном местоположении
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filePath = storage_path('app/public/applications.xlsx');
        $writer->save($filePath);

        // Установка заголовков ответа
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="Заявки РИИ.xlsx"',
        ];

        // Чтение содержимого файла и возврат в виде ответа
        $fileContents = file_get_contents($filePath);
        unlink($filePath); // Удаление временного файла
        return new Response($fileContents, 200, $headers);
    }
}
