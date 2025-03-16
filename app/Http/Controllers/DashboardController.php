<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function showSheetData()
    {
        try {
            $spreadsheetUrl = "https://docs.google.com/spreadsheets/d/1wwN8te9qhLuNklJq1uyH0DuLTxCnjgQs3Z-mAcMvPDg/export?format=csv&gid=0";
            $csvData = file_get_contents($spreadsheetUrl);
    
            if ($csvData === false) {
                return response()->json([
                    'error' => 'Failed to fetch spreadsheet data',
                ], 500);
            }
    
            // ฟังก์ชันช่วยแปลงเดือนเป็นภาษาไทย
            $monthMap = [
                'january' => 'มกราคม',
                'february' => 'กุมภาพันธ์',
                'march' => 'มีนาคม',
                'april' => 'เมษายน',
                'may' => 'พฤษภาคม',
                'june' => 'มิถุนายน',
                'july' => 'กรกฎาคม',
                'august' => 'สิงหาคม',
                'september' => 'กันยายน',
                'october' => 'ตุลาคม',
                'november' => 'พฤศจิกายน',
                'december' => 'ธันวาคม'
            ];
    
            $rows = str_getcsv($csvData, "\n");
            $columnB = [];
            $columnA = [];
            $columnC = [];
            $storagePath = public_path('images/');
    
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }
    
            $isFirstRow = true;
            foreach ($rows as $row) {
                $columns = str_getcsv($row);
                if ($isFirstRow) {
                    $isFirstRow = false;
                    continue;
                }
    
                // ดึงวันที่จากคอลัมน์ที่ 0 (index 0)
                if (isset($columns[0]) && !empty($columns[0])) {
                    $datePart = trim($columns[0]); // เช่น "March 11"
                    // แยกเดือนและวันที่
                    $dateParts = explode(' ', $datePart);
                    if (count($dateParts) === 2) {
                        $month = strtolower($dateParts[0]); // เช่น "march"
                        $day = $dateParts[1]; // เช่น "11"
                        // แปลงเดือนเป็นภาษาไทย
                        if (array_key_exists($month, $monthMap)) {
                            $thaiMonth = $monthMap[$month];
                            $datePart = "$day $thaiMonth"; // เช่น "11 มีนาคม"
                        }
                    }
                    $columnA[] = $datePart;
                } else {
                    $columnA[] = null;
                }
    
                // ดึงปีและเวลาจากคอลัมน์ที่ 1 (index 1)
                if (isset($columns[1]) && !empty($columns[1])) {
                    $timeYearPart = trim($columns[1]); // เช่น "2025 at 04:23AM"
                    // แยกส่วนปีและเวลา
                    $timeYearParts = explode(' at ', $timeYearPart); // แยก "2025" และ "04:23AM"
                    if (count($timeYearParts) === 2) {
                        $year = (int)$timeYearParts[0]; // เช่น 2025
                        $time = $timeYearParts[1]; // เช่น "04:23AM"
                        $buddhistYear = $year + 543; // แปลงเป็น พ.ศ. (2025 + 543 = 2568)
    
                        // แปลงเวลา AM/PM เป็นภาษาไทย
                        $period = '';
                        $timeParts = explode(':', $time); // แยกชั่วโมงและนาที
                        $hour = (int)$timeParts[0]; // ชั่วโมง เช่น 04 หรือ 12
                        $minutes = rtrim($timeParts[1], 'AMP'); // นาที เช่น "23AM" → "23"
    
                        if ($hour === 12 && stripos($time, 'AM') !== false) {
                            $period = 'เที่ยงคืน';
                            $time = "12.$minutes"; // ใช้จุดและคงนาทีไว้
                        } elseif ($hour === 12 && stripos($time, 'PM') !== false) {
                            $period = 'เที่ยงวัน';
                            $time = "12.$minutes"; // ใช้จุดและคงนาทีไว้
                        } elseif (stripos($time, 'AM') !== false) {
                            $period = 'ช่วงเวลาเช้า';
                            $time = str_replace('AM', '', $time); // ลบ AM ออก คง : ไว้
                        } elseif (stripos($time, 'PM') !== false) {
                            $period = 'ช่วงเวลาบ่าย';
                            $time = str_replace('PM', '', $time); // ลบ PM ออก คง : ไว้
                        }
    
                        $timeYearPart = "$buddhistYear เวลา $time $period"; // เช่น "2568 เวลา 12.15 เที่ยงคืน"
                        $columnC[] = $timeYearPart;
                    } else {
                        $columnC[] = $timeYearPart; // ถ้าแยกไม่ได้ ใช้ค่าเดิม
                    }
                } else {
                    $columnC[] = null;
                }
    
                // สร้าง $fullDateString และอัพเดทใน $columnA
                if (!is_null($columnA[count($columnA) - 1]) && !is_null($columnC[count($columnC) - 1])) {
                    $fullDateString = $columnA[count($columnA) - 1] . ' ' . $columnC[count($columnC) - 1];
                    $columnA[count($columnA) - 1] = $fullDateString; // เก็บ $fullDateString แทน
                }
    
                // ดึง URL รูปภาพจากคอลัมน์ที่ 4 (index 3)
                if (isset($columns[3]) && filter_var($columns[3], FILTER_VALIDATE_URL)) {
                    $imageUrl = trim($columns[3]);
                    $filename = basename($imageUrl);
                    $localPath = $storagePath . $filename;
    
                    if (!file_exists($localPath)) {
                        $imageContent = @file_get_contents($imageUrl);
                        if ($imageContent !== false) {
                            file_put_contents($localPath, $imageContent);
                        }
                    }
                    $columnB[] = asset('images/' . $filename);
                } else {
                    $columnB[] = null;
                }
            }
    
            return view('sheet-data', [
                'columnB' => $columnB,
                'columnA' => $columnA,
                'columnC' => $columnC
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    // API endpoint เพื่อเช็คข้อมูลล่าสุด
    public function checkLatestData()
    {
        try {
            $spreadsheetUrl = "https://docs.google.com/spreadsheets/d/1wwN8te9qhLuNklJq1uyH0DuLTxCnjgQs3Z-mAcMvPDg/export?format=csv&gid=0";
            $csvData = file_get_contents($spreadsheetUrl);

            // ตรวจสอบว่า file_get_contents ดึงข้อมูลสำเร็จหรือไม่
            if ($csvData === false) {
                return response()->json([
                    'error' => 'Failed to fetch spreadsheet data',
                ], 500);
            }

            $rows = str_getcsv($csvData, "\n");
            $columnB = [];
            $storagePath = public_path('images/');

            // สร้างโฟลเดอร์ images ถ้ายังไม่มี
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }

            // ดึงข้อมูลจากคอลัมน์ D (index 3)
            foreach ($rows as $row) {
                $columns = str_getcsv($row);
                if (isset($columns[3]) && filter_var($columns[3], FILTER_VALIDATE_URL)) {
                    $imageUrl = trim($columns[3]);
                    $filename = basename($imageUrl);
                    $localPath = $storagePath . $filename;

                    // ดาวน์โหลดและบันทึกภาพถ้ายังไม่มี
                    if (!file_exists($localPath)) {
                        $imageContent = @file_get_contents($imageUrl);
                        if ($imageContent !== false) {
                            file_put_contents($localPath, $imageContent);
                        }
                    }

                    // เก็บ path ของภาพใน array
                    $columnB[] = asset('images/' . $filename);
                }
            }

            // ตรวจสอบว่า $columnB ว่างหรือไม่
            if (empty($columnB)) {
                return response()->json([
                    'error' => 'No valid image URLs found in the spreadsheet',
                ], 404);
            }

            // ส่งผลลัพธ์กลับเป็น JSON
            return response()->json([
                'count' => count($columnB),
                'latest' => end($columnB) // รูปภาพล่าสุด
            ]);
        } catch (\Exception $e) {
            // จัดการข้อผิดพลาดทั่วไป
            return response()->json([
                'error' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
