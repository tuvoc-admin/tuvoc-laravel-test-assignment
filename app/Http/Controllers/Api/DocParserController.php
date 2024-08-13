<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentTarget;
use ZipArchive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DocParserController extends Controller
{
     public function parse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'docx_file' => 'required|file|mimes:doc,docx',
            'session_id' => 'exists:student_sessions,id',
        ]);
         if ($validator->fails()) {
            return response()->json(['success' => false ,'message' => $validator->errors()]);
        }
        // Retrieve the validated data
        $validatedData = $validator->validated();

        // Store the uploaded file
        $path = $request->file('docx_file')->store('app');
        $filePath = storage_path('app/' . $path);

        // Extract the .docx file
        $zip = new ZipArchive;
        if ($zip->open($filePath) === TRUE) {
            $xmlContent = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileName = $zip->getNameIndex($i);
                if (strpos($fileName, 'word/') === 0) {
                    $xmlContent[$fileName] = $zip->getFromIndex($i);
                }
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'Unable to open .docx file'], 400);
        }

        // Parse XML content
        $xml = simplexml_load_string($xmlContent['word/document.xml']);
        $namespaces = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('w', $namespaces['w']);

        // Extract tables
        $tables = $xml->xpath('//w:tbl');
        $dates = [];

        $i=0;
        foreach ($tables as $table) {
            $rows = $table->xpath('.//w:tr');
            $rowCount = count($rows);
            if($rowCount==4){
                // print_r($rowCount); exit;
                $rows = $table->xpath('.//w:tr');
                    // Skip the first row if it's a header row
                    foreach ($rows as $row) {
                        // Process each row to extract data
                        $cells = $row->xpath('.//w:tc');
                        if (count($cells) > 4) { // Ensure there are at least 4 cells
                            $startDate = $this->getCellText($cells[2]);
                            $endDate = $this->getCellText($cells[3]);
                            $target = $this->getCellText($cells[4]);
                            if($this->isDate($startDate)){
                                $dates[$i]['startDate'] =  Carbon::parse($startDate)->toDateString();
                            }
                            if($this->isDate($endDate)){
                                $dates[$i]['endDate'] =  Carbon::parse($endDate)->toDateString();
                            }
                            if (isset($dates[$i]['startDate']) && isset($dates[$i]['endDate'])) {
                                $dates[$i]['target'] = $target;
                                $i++;
                            }
                        }
                    }
            }
            // Extract rows and cells
            $text='';
            foreach ($table->xpath('.//w:tr') as $row) {
                if(count($row->xpath('.//w:tc'))==4){
                    foreach ($row->xpath('.//w:tc') as $cell) {
                        $text = $this->getCellText($cell);

                        if (isset($dates[$i]['startDate']) && isset($dates[$i]['endDate'])) {
                                $dates[$i]['target'] = str_ireplace('per session', '', $text);
                                $i++;
                            }

                        if (preg_match('/(\d{4}-\d{2}-\d{2}) to (\d{4}-\d{2}-\d{2})/', $this->getCellText($cell), $matches)) {
                                $dates[$i]['startDate'] = $matches[1];
                                $dates[$i]['endDate'] = $matches[2];
                            }
                    }
                   
                }else{

                    $old = $text;
                    foreach ($row->xpath('.//w:tc') as $cell) {
                        $text = $this->getCellText($cell);
                            if (stripos($old, 'target') !== false &&  isset($dates[$i]['startDate']) && isset($dates[$i]['endDate'])) {
                                $dates[$i]['target'] = $text;
                                $i++;
                            }
                            if ($this->isDate($text)) {
                                if (stripos($old, 'Start Date') !== false) {
                                     $dates[$i]['startDate'] =  Carbon::parse($text)->toDateString();
                                } elseif (stripos($old, 'End Date') !== false) {
                                     $dates[$i]['endDate'] =  Carbon::parse($text)->toDateString();
                                } 
                            }
                    }
                }
            }
        }
        foreach ($dates as $key => $value) {
            // Store the parsed data
            StudentTarget::create([
                'student_id' => $request->student_id, // assuming you pass the student_id from the form
                'session_id' => $request->session_id, // assuming the subject is passed from the form
                'start_date' => $value['startDate'],
                'end_date' => $value['endDate'],
                'target' => $value['target'],
            ]);
        }

        return response()->json(['success'=>true,'message'=>'File parsed and data saved successfully.']);
    }

    private function getCellText($cell)
    {
        // Concatenate all text segments in a cell
        $textSegments = $cell->xpath('.//w:t');
        $fullText = '';

        foreach ($textSegments as $segment) {
            $fullText .= (string) $segment;
        }

        // Trim leading and trailing whitespace
        return trim($fullText);
    }

    private function isDate($string)
    {
        return (bool) strtotime($string);
    }
}
