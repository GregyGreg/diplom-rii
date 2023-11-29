<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UsersExport extends Controller
{
    public function exportToExcel (): Response
    {
        $users = User::query()->get();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Фамилия');
        $sheet->setCellValue('C1', 'Имя');
        $sheet->setCellValue('D1', 'Отчество');
        $sheet->setCellValue('E1', 'Почта');
        $sheet->setCellValue('F1', 'Пароль');
        $sheet->setCellValue('G1', 'Номер телефона');

        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->id);
            $sheet->setCellValue('B' . $row, $user->last_name);
            $sheet->setCellValue('C' . $row, $user->name);
            $sheet->setCellValue('D' . $row, $user->surname);
            $sheet->setCellValue('E' . $row, $user->email);
            $sheet->setCellValue('F' . $row, 'Н/Д');
            $sheet->setCellValue('G' . $row, $user->phone);
            $row++;
        }

        for ($col = 'A'; $col <= 'G'; $col++) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Сохранение файла Excel во временном местоположении
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filePath = storage_path('app/public/users.xlsx');
        $writer->save($filePath);

        // Установка заголовков ответа
        $headers = [
            'Content-Type' => 'user/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="Пользователи системы заявок РИИ.xlsx"',
        ];

        // Чтение содержимого файла и возврат в виде ответа
        $fileContents = file_get_contents($filePath);
        unlink($filePath); // Удаление временного файла
        return new Response($fileContents, 200, $headers);
    }
}
