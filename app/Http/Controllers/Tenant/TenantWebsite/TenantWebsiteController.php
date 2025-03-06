<?php

namespace App\Http\Controllers\Tenant\TenantWebsite;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Article\Article;
use App\Models\Tenant\Consultation\Consultation;
use App\Models\Tenant\Theme\Theme;
use App\Models\Tenant\WebsiteSettings\BasicInfo;
use App\Models\Tenant\WebsiteSettings\Page;
use App\Models\Tenant\WebsiteSettings\SocialMediaUrl;
use Illuminate\Http\Request;
use Stancl\Tenancy\Database\Models\Domain;

class TenantWebsiteController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            [
                'basic_info' => BasicInfo::with('media')->latest()->first(),
                'pages' => Page::with('media')->get(),
                'consultations' => Consultation::with('media')->get(),
                'theme' => Theme::first(),
                'social_media' => SocialMediaUrl::first(),
                'articles' => Article::with('media')->get(),
            ], 200
        );

    }
}
