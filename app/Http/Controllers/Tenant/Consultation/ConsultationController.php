<?php

namespace App\Http\Controllers\Tenant\Consultation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Consultation\ConsultationRequest;
use App\Models\Tenant\Consultation\Consultation;
use Illuminate\Http\Request;
use Stancl\Tenancy\Database\Models\Domain;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $consultations = Consultation::with('media')->filter($request)->latest()->paginate($request->query('per_page',
        10));
        return response()->json(['data' => $consultations], 200);
    }

    public function store(ConsultationRequest $request)
    {
        $consultation = Consultation::create($request->except('image'));

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $consultation->addMediaFromRequest('image')->toMediaCollection('consultation_images');
        }

        return response()->json(['message' => 'تم إنشاء الاستشارة بنجاح.', 'consultation' => $consultation], 201);
    }

    // Update a consultation
    public function update(ConsultationRequest $request, $id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->update($request->except('image'));

        // Handle Image Update
        if ($request->hasFile('image')) {
             $consultation->addMediaFromRequest('image')->toMediaCollection('consultation_images');
        }

        return response()->json(['message' => 'تم تحديث الاستشارة بنجاح.', 'consultation' => $consultation]);
    }

     public function show(string $id)
    {
        $consultation = Consultation::findOrFail($id);
        return response()->json([
            'data' => $consultation,
            'image_url' => $consultation->getFirstMediaUrl('consultation_images')
        ]);
    }

    public function show_public(Request $request,string $id)
    {
        $consultation = Consultation::findOrFail($id);
        return response()->json([
            'data' => $consultation,
            'image_url' => $consultation->getFirstMediaUrl('consultation_images')
        ]);
    }

    // Delete a consultation
    public function destroy($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->clearMediaCollection('consultation_images'); // Remove Image
        $consultation->delete();

        return response()->json(['message' => 'تم حذف الاستشارة بنجاح.']);
    }
}
