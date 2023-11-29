<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Response;

class ApplicationExport extends Controller
{
    public function exportToExcel (): Response
    {
        $applications = Application::with(['Authors', 'Executors'])->get();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'ФИО заявителя');
        $sheet->setCellValue('C1', 'ФИО исполнителя');
        $sheet->setCellValue('D1', 'Почта заявителя');
        $sheet->setCellValue('E1', 'Категория заявки');
        $sheet->setCellValue('F1', 'Текст заявки');
        $sheet->setCellValue('G1', 'Статус заявки');
        $sheet->setCellValue('H1', 'Причина не выполнения заявки');
        $sheet->setCellValue('I1', 'Дата создания заявки');
        $sheet->setCellValue('J1', 'Дата обработки заявки');

        $row = 2;
        foreach ($applications as $application) {
            $sheet->setCellValue('A' . $row, $application->id);
            $sheet->setCellValue('B' . $row, $application->Authors->last_name . ' ' . $application->Authors->name . ' ' . $application->Authors->surname);
            $sheet->setCellValue('C' . $row, $application->Executors ? $application->Executors->last_name . ' ' . $application->Executors->name . ' ' . $application->Executors->surname : 'Исполнитель не выбран');
            $sheet->setCellValue('D' . $row, $application->authors->email);
            $sheet->setCellValue('E' . $row, $application->Categories->title);
            $sheet->setCellValue('F' . $row, $application->text_application);
            $sheet->setCellValue('G' . $row, $application->status);
            $sheet->setCellValue('H' . $row, $application->cause_fall);
            $sheet->setCellValue('I' . $row, $application->create_application);
            $sheet->setCellValue('J' . $row, $application->close_application);
            $row++;
        }

        for ($col = 'A'; $col <= 'J'; $col++) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
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
