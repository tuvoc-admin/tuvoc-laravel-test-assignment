<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ReportTemplate;
use App\Http\Controllers\Controller;

class ReportTemplateController extends Controller
{
    public function getTemplate()
    {
        $template = ReportTemplate::first();

        if (!$template) {
            return response()->json(['error' => 'Template not found'], 404);
        }

        return response()->json($template);
    }

    public function updateTemplate(Request $request)
    {
        $request->validate([
            'template_text' => 'required|string',
        ]);
    
        $templateText = $request->input('template_text');
    
        // Extract all keywords in the format %%KEY_WORD%%
        preg_match_all('/%%(\w+)%%/', $templateText, $matches);
        $keywords = $matches[1];
    
        // Check for unique keywords
        if (count($keywords) !== count(array_unique($keywords))) {
            return response()->json(['error' => 'Keywords must be unique'], 400);
        }
    
        // Sanitize and validate keywords
        $sanitizedKeywords = array_map(function($keyword) {
            return preg_replace('/^[%#@]+/', '', $keyword);
        }, $keywords);
    
        foreach ($sanitizedKeywords as $keyword) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $keyword)) {
                return response()->json(['error' => "Invalid keyword format: $keyword"], 400);
            }
        }
    
        // Replace original keywords in template text with sanitized keywords
        foreach ($keywords as $index => $originalKeyword) {
            $sanitizedKeyword = $sanitizedKeywords[$index];
            $templateText = str_replace("%%$originalKeyword%%", "%%$sanitizedKeyword%%", $templateText);
        }
    
        // Remove special characters from the template text
        $templateText = preg_replace('/[@#%]+/', '', $templateText);
    
        // Update the template record
        $template = ReportTemplate::first();

        if (!$template) {
            return response()->json(['error' => 'Template not found'], 404);
        }
    
        $template->template_text = $templateText;
        $template->save();
    
        return response()->json(['message' => 'Template updated successfully']);
    }
}
