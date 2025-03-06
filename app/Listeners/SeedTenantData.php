<?php

namespace App\Listeners;

use App\Models\Tenant\Theme\Theme;
use App\Models\Tenant\WebsiteSettings\Page;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Permission\Models\Permission;
use Stancl\Tenancy\Events\TenantCreated;

class SeedTenantData
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TenantCreated $event): void
    {
        tenancy()->initialize($event->tenant);

        // Seed the pages for the new tenant
        Page::insert([
            [
                'name' => 'الرئيسية',
                'path' => 'home',
                'title' => 'اسم الموقع او الشركة',
                'description' => 'وصف مختصر عن الموقع أو الشركة في حدود سطر أو سطرين.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'من نحن',
                'path' => 'about',
                'title' => 'عن شركتنا',
                'description' => 'أكتب عن موقعك أو شركتك وعرف عملائك من تكون.',
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'name' => 'الخدمات',
                'path' => 'services',
                'title' => 'الخدمات التي نقدمها',
                'description' => 'يقدم موقعنا العديد من الخدمات التي تفيد اصحاب الأعمال وتقديم الاستشارات الخاصة بالمجالات الإدارية.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'معرض الصور',
                'path' => 'gallery',
                'title' => 'معرض الصور',
                'description' => 'أكتب وصف مختصر عن المعرض أو بإمكانك ترك هذا الحقل فارغ.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'الفيديوهات',
                'path' => 'videos',
                'title' => 'الفيديوهات',
                'description' => 'أكتب وصف عن الفيديوهات أو بإمكانك ترك هذا الحقل فارغ.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'تواصل معنا',
                'path' => 'contact',
                'title' => 'تواصل معنا',
                'description' => 'اذا كنت تواجه اى صعوبة او استفسارات يمكنك التواصل معنا الان من خلال الرسائل او اتصل بنا.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'الشروط والأحكام',
                'path' => 'terms-and-conditions',
                'title' => 'الشروط والأحكام',
                'description' => 'أكتب الشروط والأحكام هنا...',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'سياسة الخصوصية',
                'path' => 'privacy-policy',
                'title' => 'سياسة الخصوصية',
                'description' => 'أكتب عن سياسة الخصوصية الخاصة بموقعك هنا...',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'سياسة الإستبدال والإسترجاع',
                'path' => 'return-and-exchange',
                'title' => 'سياسة الإستبدال والإسترجاع',
                'description' => 'أكتب عن سياسة الإستبدال والإسترجاع هنا...',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed Permissions
        Permission::insert([
            ['name' => 'reports', 'guard_name' => 'web'],
            ['name' => 'mail', 'guard_name' => 'web'],
            ['name' => 'website_settings', 'guard_name' => 'web'],
            ['name' => 'jara_appstore', 'guard_name' => 'web'],
            ['name' => 'jara_themestore', 'guard_name' => 'web'],
            ['name' => 'articles', 'guard_name' => 'web'],
            ['name' => 'services', 'guard_name' => 'web'],
            ['name' => 'orders', 'guard_name' => 'web'],
            ['name' => 'customers', 'guard_name' => 'web'],
            ['name' => 'promocodes', 'guard_name' => 'web'],
            ['name' => 'supervisors', 'guard_name' => 'web'],
        ]);

        Theme::insert(
            [
                'theme_id' => 1,
                'title' => 'الثيم الإفتراضي',
                'primary_color' => '#009b6a',
                'secondary_color' => '#062a26',
                'tertiary_color' => '#daf0e9',
                'custom_primary_color' => '#009b6a',
                'custom_secondary_color' => '#062a26',
                'custom_tertiary_color' => '#daf0e9',
            ]
        );
    }
}
