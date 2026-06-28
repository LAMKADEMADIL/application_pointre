<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    // عرض جميع العمال
    public function index()
    {
        $workers = Worker::all();
        return response()->json($workers, 200);
    }

    // إضافة عامل جديد
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $worker = Worker::create($validatedData);

        return response()->json(['message' => 'تم إضافة العامل بنجاح', 'worker' => $worker], 201);
    }

    // عرض بيانات عامل واحد
    public function show($id)
    {
        $worker = Worker::find($id);
        if (!$worker) return response()->json(['message' => 'العامل غير موجود'], 404);

        return response()->json($worker, 200);
    }

    // تعديل بيانات العامل (مثل تسعيرة الساعة)
    public function update(Request $request, $id)
    {
        $worker = Worker::find($id);
        if (!$worker) return response()->json(['message' => 'العامل غير موجود'], 404);

        $validatedData = $request->validate([
            'full_name' => 'string|max:255',
            'specialty' => 'string|max:255',
            'hourly_rate' => 'numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $worker->update($validatedData);

        return response()->json(['message' => 'تم تحديث بيانات العامل بنجاح', 'worker' => $worker], 200);
    }

    // حذف عامل (أو يمكنك فقط جعله غير نشط is_active = false)
    public function destroy($id)
    {
        $worker = Worker::find($id);
        if (!$worker) return response()->json(['message' => 'العامل غير موجود'], 404);

        $worker->delete();

        return response()->json(['message' => 'تم حذف العامل بنجاح'], 200);
    }
}
