<?php

namespace Database\Seeders;

use App\Models\Central\App\App;
use App\Models\Central\Promocode\Promocode;
use App\Models\Central\Setting\Setting;
use App\Models\Central\Theme\Theme;
use App\Models\Central\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = [
            'name' => 'م. هتان عاشور',
            'email' => 'dr.hattan@gmail.com',
            'password' => Hash::make('123456'),
        ];

        User::create($user);

        $promocode = [
            'code' => 'free-jara',
            'discount_type' => 'نسبة مئوية',
            'discount_amount' => '100',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addYear()->format('Y-m-d'),
        ];

        Promocode::create($promocode);

        $setting = [
            'price' => 2300,
            'email' => 'info@jara.site',
            'customer_service_email' => 'customer_support@jara.site',
            'phone' => '00966544542828'
        ];

        Setting::create($setting);

        $apps = [
            ['title' => 'تطبيق Jara QR Code', 'description' => 'عند تثبيتك لتطبيق Jara QR Code سيكون بإمكانك توليد رموز الإستجابة السريعة بأشكال مختلفة وجذابة من تصميمك تناسب الهوية البصرية الخاصة بشركتك لتستخدمها مع عملائك.',
                'type' => 'qr_code', 'auth_url' =>
                '',
                'fields' => [], 'price' => 0,
                'image' => 'apps/qr.jpg'
            ],['title' => 'تطبيق Google Tag Manager', 'description' => 'يتيح لك Google Tag Manager إدارة وتتبع جميع الأكواد والبرامج النصية لموقعك بسهولة دون الحاجة إلى تعديل الكود مباشرة، مما يساعدك على تحسين التحليلات وتتبع الزوار بكفاءة.', 'type' => 'form', 'auth_url' => '',
                'fields' => [['name' => 'gtm_id', 'type' => 'text', 'label' => 'معرف Google Tag Manager']], 'price' => 0,
                'image' => 'apps/gtm.png'
            ],['title' => 'تطبيق Zoom', 'description' => 'عند تثبيتك لتطبيق زوم ستتمكن من عقد اجتماعات اونلاين مع عملائك من خلال موقعك.', 'type' => 'oauth', 'auth_url' => 'https://zoom.us/oauth/authorize?client_id=TH3LsYb4RryMYGKoSBJBQ&response_type=code&redirect_uri=http%3A%2F%2Flocalhost%3A4200%2Fdashboard%2Fjara-appstore%2Finstalled-apps%2Fzoom-redirect',
                'fields' => [],'price' => 0,
                'image' => 'apps/zoom.jpg'
            ],['title' => 'تطبيق زر الواتساب', 'description' => 'يمكنك من ربط موقعك بحساب الواتساب الخاص بكم ومن خلاله يمكن لعملائك التواصل معك عبر الواتساب.', 'type' => 'form', 'auth_url' => '',
                'fields' => [['name' => 'phone', 'type' => 'text', 'label' => 'رقم الواتساب']], 'price' => 0,
                'image' => 'apps/whatsapp.png'
            ],['title' => 'تطبيق زر الإتصال السريع', 'description' => 'عند تثبيتك للتطبيق ستحصل على زر للإتصال السريع بموقعك لتواصل أسرع مع عملائك.', 'type' => 'form', 'auth_url' => '',
                'fields' => [['name' => 'phone', 'type' => 'text', 'label' => 'رقم الجوال']], 'price' => 0,
                'image' => 'apps/call.png'
            ],
        ];

        foreach ($apps as $app) {
            $appModel = App::create([
                'title' => $app['title'],
                'description' => $app['description'],
                'type' => $app['type'],
                'auth_url' => $app['auth_url'],
                'fields' => $app['fields'],
                'price' => $app['price'],
            ]);

                 $appModel->addMedia(storage_path('app/public/media/' . $app['image']))->preservingOriginal()->toMediaCollection('apps');
        }

        $theme = Theme::create([
            'title' => 'الثيم الإفتراضي',
            'primary_color' => '#009b6a',
            'secondary_color' => '#062a26',
            'tertiary_color' => '#daf0e9',
         ]);

        $theme->addMedia(storage_path('app/public/media/themes/default_theme.png'))->preservingOriginal()->toMediaCollection('themes');
    }
}
