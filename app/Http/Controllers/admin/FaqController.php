<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Http\Requests\Faq\StoreFaqRequest;
use App\Http\Requests\Faq\UpdateFaqRequest;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::latest()->get();
        return view('admin.pages.faqs.list', compact('faqs'));
    }

    public function store(StoreFaqRequest $request)
    {
        $data = $request->validated();
        // $data['lang'] = $data['lang'] ?? app()->getLocale();

        $faq = Faq::create($data);

        return response()->json([
            'success' => true,
            'message' => 'FAQ created successfully.',
            'faq'     => $faq
        ]);
    }

    public function show(Faq $faq)
    {
        return response()->json($faq);
    }

    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $faq->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'FAQ updated successfully.',
            'faq'     => $faq
        ]);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return response()->json([
            'success' => true,
            'message' => 'FAQ deleted successfully.'
        ]);
    }
}
