<?php

namespace App\Http\Controllers\Tenant\TenantWebsite;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Article\Article;
use App\Models\Tenant\Consultation\Consultation;
use App\Models\Tenant\Theme\Theme;
use App\Models\Tenant\User\User;
use App\Models\Tenant\WebsiteSettings\BasicInfo;
use App\Models\Tenant\WebsiteSettings\MaintenanceMode;
use App\Models\Tenant\WebsiteSettings\Page;
use App\Models\Tenant\WebsiteSettings\SocialMediaUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Database\Models\Domain;

class TenantWebsiteController extends Controller
{
    public function index(Request $request)
    {
        $tenant = tenant();

        $is_admin = function () {
            $user = Auth::user();
            return $user && User::where('id', $user->id)->exists();
        };

        return response()->json(
            [
                'basic_info' => BasicInfo::with('media')->latest()->first(),
                'pages' => Page::with('media')->get(),
                'consultations' => Consultation::with('media')->get(),
                'theme' => Theme::first(),
                'social_media' => SocialMediaUrl::first(),
                'articles' => Article::with('media')->get(),
                'maintenance_mode' => MaintenanceMode::latest()->first(),
                'is_activated' => $tenant->is_active,
                'is_admin' => $is_admin(),
            ],
            200
        );
    }
}
