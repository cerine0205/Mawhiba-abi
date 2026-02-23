<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        $submission = Submission::create([
            'full_name' => $request->fullName,
            'university_id' => $request->universityId,
            'phone' => $request->phone,
            'answers' => $request->answers ?? [],
        ]);

        return response()->json([
            'message' => 'Saved successfully',
            'data' => $submission
        ], 201);
    }


public function exportCsv(Request $request)
{
    //  حماية الرابط برمز بسيط
    $key = $request->query('key');
    if (!$key || $key !== env('EXPORT_KEY')) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $rows = Submission::latest()->get();

    $fileName = 'submissions_' . now()->format('Y-m-d_H-i') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"$fileName\"",
    ];

    $callback = function () use ($rows) {
        $out = fopen('php://output', 'w');

        fwrite($out, "\xEF\xBB\xBF");

        fputcsv($out, ['ID', 'الاسم الكامل', 'الرقم الجامعي', 'رقم الجوال', 'الإجابات', 'تاريخ الإرسال']);

        foreach ($rows as $r) {
            $answersText = is_array($r->answers)
                ? json_encode($r->answers, JSON_UNESCAPED_UNICODE)
                : (string) $r->answers;

            fputcsv($out, [
                $r->id,
                $r->full_name,
                $r->university_id,
                $r->phone,
                $answersText,
                optional($r->created_at)->format('Y-m-d H:i'),
            ]);
        }

        fclose($out);
    };

    return response()->stream($callback, 200, $headers);

}

}
