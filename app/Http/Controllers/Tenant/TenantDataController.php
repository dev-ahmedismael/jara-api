<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Article;
use App\Models\Tenant\Consultation;
use App\Models\Tenant\MaintenanceMode;
use App\Models\Tenant\SiteBuilder\PolicyPage;
use App\Models\Tenant\SiteBuilder\ThemeCustomization;
use App\Models\Tenant\WebSettings\BasicInfo;
use App\Models\Tenant\WebSettings\SocialMedia;
use Illuminate\Http\Request;
use Stancl\Tenancy\Database\Models\Domain;

class TenantDataController extends Controller
{
    public function get_data(Request $request)
    {
        $subdomain = $request->input('subdomain');
        $domain = Domain::where('domain', $subdomain)->first();
        tenancy()->initialize($domain->tenant_id);

        $basic_info = BasicInfo::with('media')->latest()->first();
        $pages = PolicyPage::all();
        $services = Consultation::with('media')->get();
        $social_media = SocialMedia::all();
        $theme = ThemeCustomization::all();
        $articles = Article::all();
        $is_online = MaintenanceMode::latest()->first();

        return response()->json([
            'basic_info' => $basic_info,
            'pages' => $pages,
            'services' => $services,
            'social_media' => $social_media,
            'theme' => $theme,
            'articles' => $articles,
            'is_online' => $is_online
        ], 200);
    }
}
