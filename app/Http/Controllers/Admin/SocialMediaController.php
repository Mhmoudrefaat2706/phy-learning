<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use App\Http\Requests\SocialMedia\StoreSocialMediaRequest;
use App\Http\Requests\SocialMedia\UpdateSocialMediaRequest;
use Illuminate\Http\JsonResponse;

class SocialMediaController extends Controller
{
    public function index()
    {
        $socials = SocialMedia::orderBy('created_at', 'desc')->paginate(8);
        return view('admin.pages.socialmedia.list', compact('socials'));
    }

    public function store(StoreSocialMediaRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['lang'] = $validated['lang'] ?? 'en';

        $social = SocialMedia::create($validated);

        return response()->json([
            'success' => true,
            'social'  => $social,
        ]);
    }

    public function update(UpdateSocialMediaRequest $request, SocialMedia $social_media): JsonResponse
    {
        $validated = $request->validated();

        $social_media->update($validated);

        return response()->json([
            'success' => true,
            'social'  => $social_media,
        ]);
    }

    public function destroy(SocialMedia $social_media): JsonResponse
    {
        $social_media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Social media link deleted successfully.',
        ]);
    }
}
