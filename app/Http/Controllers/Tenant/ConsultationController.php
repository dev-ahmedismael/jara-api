<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Consultation\ConsultationRequest;
use App\Http\Requests\Tenant\Consultation\ConsultationUpdateRequest;
use App\Models\Tenant\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consultations = Consultation::with('media')->latest()->paginate(20);
        return response()->json(['consultations' => $consultations], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ConsultationRequest $request)
    {
        $request['enable_comments'] = filter_var($request->input('enable_comments'), FILTER_VALIDATE_BOOLEAN);
        $request['enable_rates'] = filter_var($request->input('enable_rates'), FILTER_VALIDATE_BOOLEAN);

        $consultation = Consultation::create($request->all());
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $consultation->addMedia($image)->toMediaCollection('consultation');
            }
        }

        return response()->json(['message' => 'تم الحفظ بنجاح.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->load('media');

        return response()->json(['consultation' => $consultation], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ConsultationUpdateRequest $request, string $id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->fill($request->only(
            'title',
            'description',
            'type',
            'start_date',
            'end_date',
            'booking_last_date',
            'price',
            'enable_comments',
            'enable_rates',
            'status',
            'form_fields'
        ));

        if ($request->hasFile('images')) {
            $consultation->clearMediaCollection('consultation');

            foreach ($request->file('images') as $image) {
                $consultation->addMedia($image)->toMediaCollection('consultation');
            }
        }
        $consultation->save();

        return response()->json(['consultation' => $consultation, 'message' => 'تم التعديل بنجاح.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->clearMediaCollection('consultation');
        $consultation->delete();

        return response()->json(['message' => 'تم الحذف بنجاح.'], 200);
    }
}
