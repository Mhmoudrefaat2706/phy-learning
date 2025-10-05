<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::orderBy('created_at', 'desc')->paginate(8);
        return view('admin.pages.reviews.list', compact('reviews'));
    }

    public function store(StoreReviewRequest $request)
    {
    $data = $request->validated();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reviews', 'public');
            $data['image'] = $path;
        }

        $review = Review::create($data);

        return response()->json([
            'success'=>true,
            'message'=>'Review created successfully.',
            'review'=>$review]);
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
    $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reviews', 'public');
            $data['image'] = $path;
        }

        $review->update($data);

        return response()->json([
            'success'=>true,
            'message'=>'Review updated successfully.',
            'review'=>$review]);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json(['success'=>true,'message'=>'Review deleted successfully.']);
    }
}
