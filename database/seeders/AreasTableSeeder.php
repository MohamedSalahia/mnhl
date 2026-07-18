<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('area_translations')->truncate();
        DB::table('areas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $areas = [

            // ===== السعودية: منطقة الرياض (gov_id=1) =====
            ['governorate_id' => 1, 'ar' => ['name' => 'الرياض'],       'en' => ['name' => 'Riyadh']],
            ['governorate_id' => 1, 'ar' => ['name' => 'الخرج'],        'en' => ['name' => 'Al Kharj']],
            ['governorate_id' => 1, 'ar' => ['name' => 'الدوادمي'],     'en' => ['name' => 'Dawadmi']],
            ['governorate_id' => 1, 'ar' => ['name' => 'الزلفي'],       'en' => ['name' => 'Zulfi']],
            ['governorate_id' => 1, 'ar' => ['name' => 'المجمعة'],      'en' => ['name' => 'Majmaah']],
            ['governorate_id' => 1, 'ar' => ['name' => 'وادي الدواسر'], 'en' => ['name' => 'Wadi Al Dawasir']],
            ['governorate_id' => 1, 'ar' => ['name' => 'الأفلاج'],      'en' => ['name' => 'Aflaj']],
            ['governorate_id' => 1, 'ar' => ['name' => 'القويعية'],     'en' => ['name' => "Quway'iyah"]],

            // ===== السعودية: منطقة مكة المكرمة (gov_id=2) =====
            ['governorate_id' => 2, 'ar' => ['name' => 'مكة المكرمة'],  'en' => ['name' => 'Makkah Al Mukarramah']],
            ['governorate_id' => 2, 'ar' => ['name' => 'جدة'],          'en' => ['name' => 'Jeddah']],
            ['governorate_id' => 2, 'ar' => ['name' => 'الطائف'],       'en' => ['name' => 'Taif']],
            ['governorate_id' => 2, 'ar' => ['name' => 'رابغ'],         'en' => ['name' => 'Rabigh']],
            ['governorate_id' => 2, 'ar' => ['name' => 'القنفذة'],      'en' => ['name' => 'Al Qunfudhah']],
            ['governorate_id' => 2, 'ar' => ['name' => 'الليث'],        'en' => ['name' => 'Al Lith']],

            // ===== السعودية: منطقة المدينة المنورة (gov_id=3) =====
            ['governorate_id' => 3, 'ar' => ['name' => 'المدينة المنورة'], 'en' => ['name' => 'Madinah']],
            ['governorate_id' => 3, 'ar' => ['name' => 'ينبع'],           'en' => ['name' => 'Yanbu']],
            ['governorate_id' => 3, 'ar' => ['name' => 'العلا'],          'en' => ['name' => 'Al Ula']],
            ['governorate_id' => 3, 'ar' => ['name' => 'خيبر'],           'en' => ['name' => 'Khaybar']],
            ['governorate_id' => 3, 'ar' => ['name' => 'بدر'],            'en' => ['name' => 'Badr']],

            // ===== السعودية: منطقة القصيم (gov_id=4) =====
            ['governorate_id' => 4, 'ar' => ['name' => 'بريدة'],    'en' => ['name' => 'Buraydah']],
            ['governorate_id' => 4, 'ar' => ['name' => 'عنيزة'],    'en' => ['name' => 'Unayzah']],
            ['governorate_id' => 4, 'ar' => ['name' => 'الرس'],     'en' => ['name' => 'Ar Rass']],
            ['governorate_id' => 4, 'ar' => ['name' => 'البكيرية'], 'en' => ['name' => 'Al Bukayriyah']],
            ['governorate_id' => 4, 'ar' => ['name' => 'المذنب'],   'en' => ['name' => 'Al Mithnab']],

            // ===== السعودية: المنطقة الشرقية (gov_id=5) =====
            ['governorate_id' => 5, 'ar' => ['name' => 'الدمام'],     'en' => ['name' => 'Dammam']],
            ['governorate_id' => 5, 'ar' => ['name' => 'الخبر'],      'en' => ['name' => 'Al Khobar']],
            ['governorate_id' => 5, 'ar' => ['name' => 'الأحساء'],    'en' => ['name' => 'Al Ahsa']],
            ['governorate_id' => 5, 'ar' => ['name' => 'الجبيل'],     'en' => ['name' => 'Al Jubayl']],
            ['governorate_id' => 5, 'ar' => ['name' => 'القطيف'],     'en' => ['name' => 'Al Qatif']],
            ['governorate_id' => 5, 'ar' => ['name' => 'الخفجي'],     'en' => ['name' => 'Al Khafji']],
            ['governorate_id' => 5, 'ar' => ['name' => 'حفر الباطن'], 'en' => ['name' => 'Hafar Al Batin']],

            // ===== السعودية: منطقة عسير (gov_id=6) =====
            ['governorate_id' => 6, 'ar' => ['name' => 'أبها'],         'en' => ['name' => 'Abha']],
            ['governorate_id' => 6, 'ar' => ['name' => 'خميس مشيط'],   'en' => ['name' => 'Khamis Mushait']],
            ['governorate_id' => 6, 'ar' => ['name' => 'بيشة'],         'en' => ['name' => 'Bisha']],
            ['governorate_id' => 6, 'ar' => ['name' => 'محايل عسير'],   'en' => ['name' => 'Muhayil Asir']],
            ['governorate_id' => 6, 'ar' => ['name' => 'النماص'],       'en' => ['name' => 'An Namas']],

            // ===== السعودية: منطقة تبوك (gov_id=7) =====
            ['governorate_id' => 7, 'ar' => ['name' => 'تبوك'],  'en' => ['name' => 'Tabuk']],
            ['governorate_id' => 7, 'ar' => ['name' => 'الوجه'], 'en' => ['name' => 'Al Wajh']],
            ['governorate_id' => 7, 'ar' => ['name' => 'تيماء'], 'en' => ['name' => 'Tayma']],
            ['governorate_id' => 7, 'ar' => ['name' => 'ضباء'],  'en' => ['name' => 'Duba']],
            ['governorate_id' => 7, 'ar' => ['name' => 'حقل'],   'en' => ['name' => 'Haql']],

            // ===== السعودية: منطقة حائل (gov_id=8) =====
            ['governorate_id' => 8, 'ar' => ['name' => 'حائل'],   'en' => ['name' => 'Hail']],
            ['governorate_id' => 8, 'ar' => ['name' => 'بقعاء'],  'en' => ['name' => 'Baqaa']],
            ['governorate_id' => 8, 'ar' => ['name' => 'الغزالة'],'en' => ['name' => 'Al Ghazalah']],

            // ===== السعودية: منطقة الحدود الشمالية (gov_id=9) =====
            ['governorate_id' => 9, 'ar' => ['name' => 'عرعر'],  'en' => ['name' => "Ar'ar"]],
            ['governorate_id' => 9, 'ar' => ['name' => 'رفحاء'], 'en' => ['name' => 'Rafha']],
            ['governorate_id' => 9, 'ar' => ['name' => 'طريف'],  'en' => ['name' => 'Turaif']],

            // ===== السعودية: منطقة جازان (gov_id=10) =====
            ['governorate_id' => 10, 'ar' => ['name' => 'جازان'],    'en' => ['name' => 'Jazan']],
            ['governorate_id' => 10, 'ar' => ['name' => 'صامطة'],    'en' => ['name' => 'Samtah']],
            ['governorate_id' => 10, 'ar' => ['name' => 'أبو عريش'], 'en' => ['name' => 'Abu Arish']],
            ['governorate_id' => 10, 'ar' => ['name' => 'صبيا'],     'en' => ['name' => 'Sabya']],

            // ===== السعودية: منطقة نجران (gov_id=11) =====
            ['governorate_id' => 11, 'ar' => ['name' => 'نجران'],  'en' => ['name' => 'Najran']],
            ['governorate_id' => 11, 'ar' => ['name' => 'شرورة'],  'en' => ['name' => 'Sharorah']],

            // ===== السعودية: منطقة الباحة (gov_id=12) =====
            ['governorate_id' => 12, 'ar' => ['name' => 'الباحة'],  'en' => ['name' => 'Al Baha']],
            ['governorate_id' => 12, 'ar' => ['name' => 'بلجرشي'],  'en' => ['name' => 'Baljurashi']],

            // ===== السعودية: منطقة الجوف (gov_id=13) =====
            ['governorate_id' => 13, 'ar' => ['name' => 'سكاكا'],       'en' => ['name' => 'Sakaka']],
            ['governorate_id' => 13, 'ar' => ['name' => 'القريات'],     'en' => ['name' => 'Al Qurayyat']],
            ['governorate_id' => 13, 'ar' => ['name' => 'دومة الجندل'], 'en' => ['name' => 'Dawmat Al Jandal']],

            // ===== مصر: القاهرة (gov_id=14) =====
            ['governorate_id' => 14, 'ar' => ['name' => 'مدينة نصر'],     'en' => ['name' => 'Nasr City']],
            ['governorate_id' => 14, 'ar' => ['name' => 'المعادي'],       'en' => ['name' => 'Maadi']],
            ['governorate_id' => 14, 'ar' => ['name' => 'مصر الجديدة'],   'en' => ['name' => 'Heliopolis']],
            ['governorate_id' => 14, 'ar' => ['name' => 'حلوان'],         'en' => ['name' => 'Helwan']],
            ['governorate_id' => 14, 'ar' => ['name' => 'التجمع الخامس'], 'en' => ['name' => 'Fifth Settlement']],
            ['governorate_id' => 14, 'ar' => ['name' => 'عين شمس'],       'en' => ['name' => 'Ain Shams']],
            ['governorate_id' => 14, 'ar' => ['name' => 'الزيتون'],       'en' => ['name' => 'Zeitoun']],
            ['governorate_id' => 14, 'ar' => ['name' => 'شبرا'],          'en' => ['name' => 'Shubra']],
            ['governorate_id' => 14, 'ar' => ['name' => 'المطرية'],       'en' => ['name' => 'Matariya']],

            // ===== مصر: الجيزة (gov_id=15) =====
            ['governorate_id' => 15, 'ar' => ['name' => 'الجيزة'],     'en' => ['name' => 'Giza']],
            ['governorate_id' => 15, 'ar' => ['name' => '6 أكتوبر'],   'en' => ['name' => '6th of October']],
            ['governorate_id' => 15, 'ar' => ['name' => 'الشيخ زايد'], 'en' => ['name' => 'Sheikh Zayed']],
            ['governorate_id' => 15, 'ar' => ['name' => 'الهرم'],      'en' => ['name' => 'Haram']],
            ['governorate_id' => 15, 'ar' => ['name' => 'إمبابة'],     'en' => ['name' => 'Imbaba']],
            ['governorate_id' => 15, 'ar' => ['name' => 'العمرانية'],  'en' => ['name' => 'Umraniyya']],

            // ===== مصر: الإسكندرية (gov_id=16) =====
            ['governorate_id' => 16, 'ar' => ['name' => 'المنتزه'],   'en' => ['name' => 'Montaza']],
            ['governorate_id' => 16, 'ar' => ['name' => 'سيدي جابر'], 'en' => ['name' => 'Sidi Gaber']],
            ['governorate_id' => 16, 'ar' => ['name' => 'العجمي'],    'en' => ['name' => 'Agami']],
            ['governorate_id' => 16, 'ar' => ['name' => 'سموحة'],     'en' => ['name' => 'Smouha']],
            ['governorate_id' => 16, 'ar' => ['name' => 'محرم بك'],   'en' => ['name' => 'Moharam Bek']],
            ['governorate_id' => 16, 'ar' => ['name' => 'سيدي بشر'],  'en' => ['name' => 'Sidi Bishr']],

            // ===== مصر: القليوبية (gov_id=17) =====
            ['governorate_id' => 17, 'ar' => ['name' => 'بنها'],         'en' => ['name' => 'Banha']],
            ['governorate_id' => 17, 'ar' => ['name' => 'شبرا الخيمة'], 'en' => ['name' => 'Shubra El Kheima']],
            ['governorate_id' => 17, 'ar' => ['name' => 'قليوب'],       'en' => ['name' => 'Qalyub']],

            // ===== مصر: الشرقية (gov_id=18) =====
            ['governorate_id' => 18, 'ar' => ['name' => 'الزقازيق'],        'en' => ['name' => 'Zagazig']],
            ['governorate_id' => 18, 'ar' => ['name' => 'العاشر من رمضان'], 'en' => ['name' => '10th of Ramadan']],
            ['governorate_id' => 18, 'ar' => ['name' => 'بلبيس'],           'en' => ['name' => 'Belbeis']],

            // ===== مصر: الدقهلية (gov_id=19) =====
            ['governorate_id' => 19, 'ar' => ['name' => 'المنصورة'],  'en' => ['name' => 'Mansoura']],
            ['governorate_id' => 19, 'ar' => ['name' => 'طلخا'],      'en' => ['name' => 'Talkha']],
            ['governorate_id' => 19, 'ar' => ['name' => 'ميت غمر'],   'en' => ['name' => 'Mit Ghamr']],

            // ===== مصر: البحيرة (gov_id=20) =====
            ['governorate_id' => 20, 'ar' => ['name' => 'دمنهور'],       'en' => ['name' => 'Damanhur']],
            ['governorate_id' => 20, 'ar' => ['name' => 'كفر الدوار'],   'en' => ['name' => 'Kafr Al Dawwar']],
            ['governorate_id' => 20, 'ar' => ['name' => 'إيتاي البارود'],'en' => ['name' => 'Itay Al Barud']],

            // ===== مصر: الغربية (gov_id=21) =====
            ['governorate_id' => 21, 'ar' => ['name' => 'طنطا'],          'en' => ['name' => 'Tanta']],
            ['governorate_id' => 21, 'ar' => ['name' => 'المحلة الكبرى'], 'en' => ['name' => 'Mahalla El Kubra']],
            ['governorate_id' => 21, 'ar' => ['name' => 'كفر الزيات'],   'en' => ['name' => 'Kafr Al Zayyat']],

            // ===== مصر: أسيوط (gov_id=33) =====
            ['governorate_id' => 33, 'ar' => ['name' => 'أسيوط'],   'en' => ['name' => 'Assiut City']],
            ['governorate_id' => 33, 'ar' => ['name' => 'ديروط'],   'en' => ['name' => 'Dayrut']],
            ['governorate_id' => 33, 'ar' => ['name' => 'منفلوط'],  'en' => ['name' => 'Manfalut']],

            // ===== مصر: أسوان (gov_id=37) =====
            ['governorate_id' => 37, 'ar' => ['name' => 'أسوان'],    'en' => ['name' => 'Aswan City']],
            ['governorate_id' => 37, 'ar' => ['name' => 'كوم أمبو'], 'en' => ['name' => 'Kom Ombo']],
            ['governorate_id' => 37, 'ar' => ['name' => 'إدفو'],     'en' => ['name' => 'Edfu']],

            // ===== مصر: المنيا (gov_id=32) =====
            ['governorate_id' => 32, 'ar' => ['name' => 'المنيا'],  'en' => ['name' => 'Minya City']],
            ['governorate_id' => 32, 'ar' => ['name' => 'ملوي'],    'en' => ['name' => 'Mallawi']],
            ['governorate_id' => 32, 'ar' => ['name' => 'مغاغة'],   'en' => ['name' => 'Maghagha']],

            // ===== مصر: سوهاج (gov_id=34) =====
            ['governorate_id' => 34, 'ar' => ['name' => 'سوهاج'],  'en' => ['name' => 'Sohag City']],
            ['governorate_id' => 34, 'ar' => ['name' => 'طهطا'],   'en' => ['name' => 'Tahta']],
            ['governorate_id' => 34, 'ar' => ['name' => 'جرجا'],   'en' => ['name' => 'Girga']],

            // ===== مصر: الأقصر (gov_id=36) =====
            ['governorate_id' => 36, 'ar' => ['name' => 'الأقصر'],    'en' => ['name' => 'Luxor City']],
            ['governorate_id' => 36, 'ar' => ['name' => 'إسنا'],      'en' => ['name' => 'Esna']],

            // ===== الإمارات: أبوظبي (gov_id=41) =====
            ['governorate_id' => 41, 'ar' => ['name' => 'مدينة أبوظبي'],  'en' => ['name' => 'Abu Dhabi City']],
            ['governorate_id' => 41, 'ar' => ['name' => 'العين'],          'en' => ['name' => 'Al Ain']],
            ['governorate_id' => 41, 'ar' => ['name' => 'الظفرة'],         'en' => ['name' => 'Al Dhafra']],
            ['governorate_id' => 41, 'ar' => ['name' => 'مدينة خليفة'],   'en' => ['name' => 'Khalifa City']],
            ['governorate_id' => 41, 'ar' => ['name' => 'الشهامة'],        'en' => ['name' => 'Al Shahama']],

            // ===== الإمارات: دبي (gov_id=42) =====
            ['governorate_id' => 42, 'ar' => ['name' => 'بر دبي'],          'en' => ['name' => 'Bur Dubai']],
            ['governorate_id' => 42, 'ar' => ['name' => 'ديرة'],            'en' => ['name' => 'Deira']],
            ['governorate_id' => 42, 'ar' => ['name' => 'جبل علي'],         'en' => ['name' => 'Jebel Ali']],
            ['governorate_id' => 42, 'ar' => ['name' => 'أم سقيم'],         'en' => ['name' => 'Umm Suqeim']],
            ['governorate_id' => 42, 'ar' => ['name' => 'الخليج التجاري'],  'en' => ['name' => 'Business Bay']],
            ['governorate_id' => 42, 'ar' => ['name' => 'مردف'],            'en' => ['name' => 'Mirdif']],
            ['governorate_id' => 42, 'ar' => ['name' => 'دبي مارينا'],      'en' => ['name' => 'Dubai Marina']],

            // ===== الإمارات: الشارقة (gov_id=43) =====
            ['governorate_id' => 43, 'ar' => ['name' => 'مدينة الشارقة'],  'en' => ['name' => 'Sharjah City']],
            ['governorate_id' => 43, 'ar' => ['name' => 'الذيد'],           'en' => ['name' => 'Al Dhaid']],
            ['governorate_id' => 43, 'ar' => ['name' => 'خور فكان'],        'en' => ['name' => 'Khor Fakkan']],

            // ===== الإمارات: عجمان (gov_id=44) =====
            ['governorate_id' => 44, 'ar' => ['name' => 'مدينة عجمان'],  'en' => ['name' => 'Ajman City']],
            ['governorate_id' => 44, 'ar' => ['name' => 'مصفوت'],        'en' => ['name' => 'Masfut']],

            // ===== الإمارات: أم القيوين (gov_id=45) =====
            ['governorate_id' => 45, 'ar' => ['name' => 'أم القيوين'],  'en' => ['name' => 'Umm Al Quwain City']],

            // ===== الإمارات: الفجيرة (gov_id=46) =====
            ['governorate_id' => 46, 'ar' => ['name' => 'مدينة الفجيرة'],  'en' => ['name' => 'Fujairah City']],
            ['governorate_id' => 46, 'ar' => ['name' => 'دبا الفجيرة'],    'en' => ['name' => 'Dibba Al Fujairah']],

            // ===== الإمارات: رأس الخيمة (gov_id=47) =====
            ['governorate_id' => 47, 'ar' => ['name' => 'رأس الخيمة'],  'en' => ['name' => 'Ras Al Khaimah City']],
            ['governorate_id' => 47, 'ar' => ['name' => 'الخصب'],       'en' => ['name' => 'Al Khasab']],
            ['governorate_id' => 47, 'ar' => ['name' => 'دقداقة'],      'en' => ['name' => 'Digdaga']],

            // ===== الكويت: العاصمة (gov_id=48) =====
            ['governorate_id' => 48, 'ar' => ['name' => 'منطقة الشرق'],   'en' => ['name' => 'Sharq']],
            ['governorate_id' => 48, 'ar' => ['name' => 'منطقة قبلة'],    'en' => ['name' => 'Qibla']],
            ['governorate_id' => 48, 'ar' => ['name' => 'الدسمة'],        'en' => ['name' => 'Dasman']],
            ['governorate_id' => 48, 'ar' => ['name' => 'ميناء عبدالله'],'en' => ['name' => 'Mina Abdullah']],

            // ===== الكويت: حولي (gov_id=49) =====
            ['governorate_id' => 49, 'ar' => ['name' => 'السالمية'],   'en' => ['name' => 'Salmiya']],
            ['governorate_id' => 49, 'ar' => ['name' => 'الرميثية'],  'en' => ['name' => 'Rumaithiya']],
            ['governorate_id' => 49, 'ar' => ['name' => 'الجابرية'],  'en' => ['name' => 'Jabriya']],
            ['governorate_id' => 49, 'ar' => ['name' => 'مشرف'],      'en' => ['name' => 'Mishref']],

            // ===== الكويت: الفروانية (gov_id=50) =====
            ['governorate_id' => 50, 'ar' => ['name' => 'الفروانية'],     'en' => ['name' => 'Farwaniya']],
            ['governorate_id' => 50, 'ar' => ['name' => 'خيطان'],        'en' => ['name' => 'Khaitan']],
            ['governorate_id' => 50, 'ar' => ['name' => 'جليب الشيوخ'], 'en' => ['name' => 'Jleeb Al Shuyoukh']],
            ['governorate_id' => 50, 'ar' => ['name' => 'العارضية'],     'en' => ['name' => 'Ardiya']],

            // ===== الكويت: مبارك الكبير (gov_id=51) =====
            ['governorate_id' => 51, 'ar' => ['name' => 'المسيلة'],      'en' => ['name' => 'Al Masayel']],
            ['governorate_id' => 51, 'ar' => ['name' => 'صباح السالم'],  'en' => ['name' => 'Sabah Al Salem']],

            // ===== الكويت: الأحمدي (gov_id=52) =====
            ['governorate_id' => 52, 'ar' => ['name' => 'الأحمدي'],  'en' => ['name' => 'Ahmadi']],
            ['governorate_id' => 52, 'ar' => ['name' => 'الفحيحيل'], 'en' => ['name' => 'Fahaheel']],
            ['governorate_id' => 52, 'ar' => ['name' => 'المنقف'],   'en' => ['name' => 'Mangaf']],

            // ===== الكويت: الجهراء (gov_id=53) =====
            ['governorate_id' => 53, 'ar' => ['name' => 'الجهراء'],   'en' => ['name' => 'Jahra']],
            ['governorate_id' => 53, 'ar' => ['name' => 'القصر'],     'en' => ['name' => 'Al Qasr']],
            ['governorate_id' => 53, 'ar' => ['name' => 'الصليبية'],  'en' => ['name' => 'Sulaibiya']],

            // ===== البحرين: محافظة العاصمة (gov_id=54) =====
            ['governorate_id' => 54, 'ar' => ['name' => 'المنامة'],   'en' => ['name' => 'Manama']],
            ['governorate_id' => 54, 'ar' => ['name' => 'جفير'],      'en' => ['name' => 'Juffair']],
            ['governorate_id' => 54, 'ar' => ['name' => 'القضيبية'],  'en' => ['name' => 'Qudaibiya']],
            ['governorate_id' => 54, 'ar' => ['name' => 'عدلية'],     'en' => ['name' => 'Adliya']],

            // ===== البحرين: المحافظة الشمالية (gov_id=55) =====
            ['governorate_id' => 55, 'ar' => ['name' => 'المحرق'],    'en' => ['name' => 'Muharraq']],
            ['governorate_id' => 55, 'ar' => ['name' => 'البسيتين'],  'en' => ['name' => 'Busaiteen']],
            ['governorate_id' => 55, 'ar' => ['name' => 'عراد'],      'en' => ['name' => 'Arad']],

            // ===== البحرين: المحافظة الجنوبية (gov_id=56) =====
            ['governorate_id' => 56, 'ar' => ['name' => 'الرفاع'],  'en' => ['name' => 'Riffa']],
            ['governorate_id' => 56, 'ar' => ['name' => 'زلاق'],    'en' => ['name' => 'Zallaq']],
            ['governorate_id' => 56, 'ar' => ['name' => 'عوالي'],   'en' => ['name' => 'Awali']],

            // ===== البحرين: المحافظة الوسطى (gov_id=57) =====
            ['governorate_id' => 57, 'ar' => ['name' => 'مدينة حمد'],  'en' => ['name' => 'Hamad Town']],
            ['governorate_id' => 57, 'ar' => ['name' => 'أم الحصم'],   'en' => ['name' => 'Umm Al Hassam']],
            ['governorate_id' => 57, 'ar' => ['name' => 'بوري'],       'en' => ['name' => 'Buri']],

            // ===== قطر: الدوحة (gov_id=58) =====
            ['governorate_id' => 58, 'ar' => ['name' => 'الدوحة القديمة'],      'en' => ['name' => 'Old Doha']],
            ['governorate_id' => 58, 'ar' => ['name' => 'الخليفات'],            'en' => ['name' => 'Khalisat']],
            ['governorate_id' => 58, 'ar' => ['name' => 'المنطقة الدبلوماسية'],'en' => ['name' => 'Diplomatic Area']],
            ['governorate_id' => 58, 'ar' => ['name' => 'الرفاع'],              'en' => ['name' => 'Al Rufaa']],

            // ===== قطر: الريان (gov_id=59) =====
            ['governorate_id' => 59, 'ar' => ['name' => 'الريان'],        'en' => ['name' => 'Al Rayyan City']],
            ['governorate_id' => 59, 'ar' => ['name' => 'أبو هامور'],     'en' => ['name' => 'Abu Hamour']],
            ['governorate_id' => 59, 'ar' => ['name' => 'مدينة العلوم'],  'en' => ['name' => 'Education City']],

            // ===== قطر: الوكرة (gov_id=62) =====
            ['governorate_id' => 62, 'ar' => ['name' => 'الوكرة'],   'en' => ['name' => 'Al Wakrah City']],
            ['governorate_id' => 62, 'ar' => ['name' => 'مسيعيد'],  'en' => ['name' => 'Messaieed']],

            // ===== قطر: أم صلال (gov_id=63) =====
            ['governorate_id' => 63, 'ar' => ['name' => 'أم صلال محمد'], 'en' => ['name' => 'Umm Salal Mohammed']],
            ['governorate_id' => 63, 'ar' => ['name' => 'أم صلال علي'],  'en' => ['name' => 'Umm Salal Ali']],

            // ===== عُمان: محافظة مسقط (gov_id=66) =====
            ['governorate_id' => 66, 'ar' => ['name' => 'مسقط'],    'en' => ['name' => 'Muscat']],
            ['governorate_id' => 66, 'ar' => ['name' => 'مطرح'],    'en' => ['name' => 'Muttrah']],
            ['governorate_id' => 66, 'ar' => ['name' => 'السيب'],   'en' => ['name' => 'Seeb']],
            ['governorate_id' => 66, 'ar' => ['name' => 'بوشر'],    'en' => ['name' => 'Bausher']],
            ['governorate_id' => 66, 'ar' => ['name' => 'العامرات'],'en' => ['name' => 'Al Amerat']],
            ['governorate_id' => 66, 'ar' => ['name' => 'قريات'],   'en' => ['name' => 'Qurayyat']],

            // ===== عُمان: محافظة ظفار (gov_id=67) =====
            ['governorate_id' => 67, 'ar' => ['name' => 'صلالة'],  'en' => ['name' => 'Salalah']],
            ['governorate_id' => 67, 'ar' => ['name' => 'طاقة'],   'en' => ['name' => 'Taqah']],
            ['governorate_id' => 67, 'ar' => ['name' => 'مرباط'],  'en' => ['name' => 'Mirbat']],

            // ===== عُمان: محافظة الداخلية (gov_id=70) =====
            ['governorate_id' => 70, 'ar' => ['name' => 'نزوى'],  'en' => ['name' => 'Nizwa']],
            ['governorate_id' => 70, 'ar' => ['name' => 'بهلاء'], 'en' => ['name' => 'Bahla']],
            ['governorate_id' => 70, 'ar' => ['name' => 'سمائل'], 'en' => ['name' => 'Samail']],

            // ===== عُمان: شمال الباطنة (gov_id=75) =====
            ['governorate_id' => 75, 'ar' => ['name' => 'صحار'],    'en' => ['name' => 'Sohar']],
            ['governorate_id' => 75, 'ar' => ['name' => 'شناص'],    'en' => ['name' => 'Shinas']],
            ['governorate_id' => 75, 'ar' => ['name' => 'المصنعة'], 'en' => ['name' => 'Al Masna']],

            // ===== عُمان: جنوب الباطنة (gov_id=76) =====
            ['governorate_id' => 76, 'ar' => ['name' => 'الرستاق'], 'en' => ['name' => 'Rustaq']],
            ['governorate_id' => 76, 'ar' => ['name' => 'بركاء'],   'en' => ['name' => 'Barka']],

            // ===== الأردن: محافظة عمان (gov_id=77) =====
            ['governorate_id' => 77, 'ar' => ['name' => 'عمان'],        'en' => ['name' => 'Amman City']],
            ['governorate_id' => 77, 'ar' => ['name' => 'سحاب'],        'en' => ['name' => 'Sahab']],
            ['governorate_id' => 77, 'ar' => ['name' => 'وادي السير'],  'en' => ['name' => 'Wadi Al Seer']],
            ['governorate_id' => 77, 'ar' => ['name' => 'ماركا'],       'en' => ['name' => 'Marka']],

            // ===== الأردن: محافظة الزرقاء (gov_id=78) =====
            ['governorate_id' => 78, 'ar' => ['name' => 'الزرقاء'],  'en' => ['name' => 'Zarqa City']],
            ['governorate_id' => 78, 'ar' => ['name' => 'الرصيفة'], 'en' => ['name' => 'Russeifa']],
            ['governorate_id' => 78, 'ar' => ['name' => 'الضليل'],  'en' => ['name' => 'Al Duleil']],

            // ===== الأردن: محافظة إربد (gov_id=79) =====
            ['governorate_id' => 79, 'ar' => ['name' => 'إربد'],   'en' => ['name' => 'Irbid City']],
            ['governorate_id' => 79, 'ar' => ['name' => 'الرمثا'], 'en' => ['name' => 'Ramtha']],
            ['governorate_id' => 79, 'ar' => ['name' => 'الكورة'], 'en' => ['name' => 'Kura']],

            // ===== الأردن: محافظة العقبة (gov_id=87) =====
            ['governorate_id' => 87, 'ar' => ['name' => 'العقبة'],  'en' => ['name' => 'Aqaba City']],

            // ===== لبنان: محافظة بيروت (gov_id=89) =====
            ['governorate_id' => 89, 'ar' => ['name' => 'الأشرفية'],  'en' => ['name' => 'Ashrafieh']],
            ['governorate_id' => 89, 'ar' => ['name' => 'الحمرا'],    'en' => ['name' => 'Hamra']],
            ['governorate_id' => 89, 'ar' => ['name' => 'رأس بيروت'], 'en' => ['name' => 'Ras Beirut']],
            ['governorate_id' => 89, 'ar' => ['name' => 'المرفأ'],    'en' => ['name' => 'Port Area']],

            // ===== لبنان: محافظة جبل لبنان (gov_id=90) =====
            ['governorate_id' => 90, 'ar' => ['name' => 'جونيه'],   'en' => ['name' => 'Jounieh']],
            ['governorate_id' => 90, 'ar' => ['name' => 'بعبدا'],   'en' => ['name' => 'Baabda']],
            ['governorate_id' => 90, 'ar' => ['name' => 'عاليه'],   'en' => ['name' => 'Aley']],
            ['governorate_id' => 90, 'ar' => ['name' => 'بيت مري'], 'en' => ['name' => 'Beit Mery']],

            // ===== لبنان: محافظة الشمال (gov_id=91) =====
            ['governorate_id' => 91, 'ar' => ['name' => 'طرابلس'], 'en' => ['name' => 'Tripoli']],
            ['governorate_id' => 91, 'ar' => ['name' => 'بشري'],   'en' => ['name' => 'Bsharri']],
            ['governorate_id' => 91, 'ar' => ['name' => 'زغرتا'],  'en' => ['name' => 'Zgharta']],

            // ===== لبنان: محافظة البقاع (gov_id=93) =====
            ['governorate_id' => 93, 'ar' => ['name' => 'زحلة'],    'en' => ['name' => 'Zahle']],
            ['governorate_id' => 93, 'ar' => ['name' => 'برالياس'], 'en' => ['name' => 'Bar Elias']],

            // ===== لبنان: محافظة الجنوب (gov_id=95) =====
            ['governorate_id' => 95, 'ar' => ['name' => 'صيدا'],  'en' => ['name' => 'Sidon']],
            ['governorate_id' => 95, 'ar' => ['name' => 'صور'],   'en' => ['name' => 'Tyre']],
            ['governorate_id' => 95, 'ar' => ['name' => 'جزين'],  'en' => ['name' => 'Jezzine']],

            // ===== لبنان: محافظة النبطية (gov_id=96) =====
            ['governorate_id' => 96, 'ar' => ['name' => 'النبطية'],    'en' => ['name' => 'Nabatieh']],
            ['governorate_id' => 96, 'ar' => ['name' => 'مرجعيون'],   'en' => ['name' => 'Marjayoun']],
            ['governorate_id' => 96, 'ar' => ['name' => 'بنت جبيل'],  'en' => ['name' => 'Bint Jbeil']],

            // ===== سوريا: دمشق (gov_id=97) =====
            ['governorate_id' => 97, 'ar' => ['name' => 'دمشق القديمة'], 'en' => ['name' => 'Old Damascus']],
            ['governorate_id' => 97, 'ar' => ['name' => 'المزة'],        'en' => ['name' => 'Mazzeh']],
            ['governorate_id' => 97, 'ar' => ['name' => 'كفرسوسة'],     'en' => ['name' => 'Kafr Sousa']],
            ['governorate_id' => 97, 'ar' => ['name' => 'المالكي'],     'en' => ['name' => 'Malki']],

            // ===== سوريا: حلب (gov_id=99) =====
            ['governorate_id' => 99, 'ar' => ['name' => 'حلب'],    'en' => ['name' => 'Aleppo City']],
            ['governorate_id' => 99, 'ar' => ['name' => 'السفيرة'],'en' => ['name' => 'As Safirah']],
            ['governorate_id' => 99, 'ar' => ['name' => 'عزاز'],   'en' => ['name' => 'Azaz']],

            // ===== سوريا: حمص (gov_id=100) =====
            ['governorate_id' => 100, 'ar' => ['name' => 'حمص'],   'en' => ['name' => 'Homs City']],
            ['governorate_id' => 100, 'ar' => ['name' => 'تدمر'],  'en' => ['name' => 'Palmyra']],

            // ===== سوريا: اللاذقية (gov_id=102) =====
            ['governorate_id' => 102, 'ar' => ['name' => 'اللاذقية'], 'en' => ['name' => 'Latakia City']],
            ['governorate_id' => 102, 'ar' => ['name' => 'جبلة'],     'en' => ['name' => 'Jableh']],

            // ===== العراق: بغداد (gov_id=111) =====
            ['governorate_id' => 111, 'ar' => ['name' => 'الكرخ'],       'en' => ['name' => 'Karkh']],
            ['governorate_id' => 111, 'ar' => ['name' => 'الرصافة'],     'en' => ['name' => 'Rusafa']],
            ['governorate_id' => 111, 'ar' => ['name' => 'المنصور'],     'en' => ['name' => 'Mansour']],
            ['governorate_id' => 111, 'ar' => ['name' => 'الكاظمية'],    'en' => ['name' => 'Kadhimiya']],
            ['governorate_id' => 111, 'ar' => ['name' => 'الصدر'],       'en' => ['name' => 'Sadr City']],
            ['governorate_id' => 111, 'ar' => ['name' => 'بغداد الجديدة'],'en' => ['name' => 'New Baghdad']],

            // ===== العراق: البصرة (gov_id=112) =====
            ['governorate_id' => 112, 'ar' => ['name' => 'البصرة'],     'en' => ['name' => 'Basra City']],
            ['governorate_id' => 112, 'ar' => ['name' => 'الزبير'],     'en' => ['name' => 'Zubair']],
            ['governorate_id' => 112, 'ar' => ['name' => 'أبو الخصيب'],'en' => ['name' => 'Abu Al Khaseeb']],

            // ===== العراق: نينوى (gov_id=113) =====
            ['governorate_id' => 113, 'ar' => ['name' => 'الموصل'],     'en' => ['name' => 'Mosul']],
            ['governorate_id' => 113, 'ar' => ['name' => 'تلعفر'],      'en' => ['name' => 'Tal Afar']],

            // ===== العراق: أربيل (gov_id=114) =====
            ['governorate_id' => 114, 'ar' => ['name' => 'أربيل'],    'en' => ['name' => 'Erbil City']],
            ['governorate_id' => 114, 'ar' => ['name' => 'شقلاوة'],   'en' => ['name' => 'Shaqlawa']],

            // ===== اليمن: أمانة العاصمة (gov_id=129) =====
            ['governorate_id' => 129, 'ar' => ['name' => 'صنعاء القديمة'],  'en' => ["name" => "Old Sana'a"]],
            ['governorate_id' => 129, 'ar' => ['name' => 'حدة'],            'en' => ['name' => 'Hadda']],
            ['governorate_id' => 129, 'ar' => ['name' => 'الحصبة'],         'en' => ['name' => 'Al Hasaba']],

            // ===== اليمن: عدن (gov_id=131) =====
            ['governorate_id' => 131, 'ar' => ['name' => 'المعلا'],    'en' => ['name' => 'Al Mualla']],
            ['governorate_id' => 131, 'ar' => ['name' => 'التواهي'],   'en' => ['name' => 'Al Tawahi']],
            ['governorate_id' => 131, 'ar' => ['name' => 'خور مكسر'], 'en' => ['name' => 'Khour Maksar']],

            // ===== ليبيا: طرابلس (gov_id=151) =====
            ['governorate_id' => 151, 'ar' => ['name' => 'طرابلس المدينة'],  'en' => ['name' => 'Tripoli City']],
            ['governorate_id' => 151, 'ar' => ['name' => 'تاجوراء'],         'en' => ['name' => 'Tajoura']],
            ['governorate_id' => 151, 'ar' => ['name' => 'جنزور'],           'en' => ['name' => 'Janzur']],

            // ===== ليبيا: بنغازي (gov_id=152) =====
            ['governorate_id' => 152, 'ar' => ['name' => 'بنغازي'],    'en' => ['name' => 'Benghazi City']],
            ['governorate_id' => 152, 'ar' => ['name' => 'قاريونس'],   'en' => ['name' => 'Qaryounis']],

            // ===== تونس: تونس (gov_id=161) =====
            ['governorate_id' => 161, 'ar' => ['name' => 'تونس العاصمة'],  'en' => ['name' => 'Tunis City']],
            ['governorate_id' => 161, 'ar' => ['name' => 'باب بحر'],       'en' => ['name' => 'Bab Bhar']],
            ['governorate_id' => 161, 'ar' => ['name' => 'المدينة'],       'en' => ['name' => 'Medina']],

            // ===== تونس: سوسة (gov_id=175) =====
            ['governorate_id' => 175, 'ar' => ['name' => 'سوسة'],      'en' => ['name' => 'Sousse City']],
            ['governorate_id' => 175, 'ar' => ['name' => 'حمام سوسة'], 'en' => ['name' => 'Hammam Sousse']],

            // ===== تونس: صفاقس (gov_id=178) =====
            ['governorate_id' => 178, 'ar' => ['name' => 'صفاقس'],      'en' => ['name' => 'Sfax City']],
            ['governorate_id' => 178, 'ar' => ['name' => 'ساقية الزيت'],'en' => ['name' => 'Sakiet Ezzit']],

            // ===== الجزائر: الجزائر (gov_id=184) =====
            ['governorate_id' => 184, 'ar' => ['name' => 'الجزائر الوسطى'],  'en' => ['name' => 'Algiers Centre']],
            ['governorate_id' => 184, 'ar' => ['name' => 'باب الوادي'],      'en' => ['name' => 'Bab El Oued']],
            ['governorate_id' => 184, 'ar' => ['name' => 'حيدرة'],           'en' => ['name' => 'Hydra']],
            ['governorate_id' => 184, 'ar' => ['name' => 'بن عكنون'],        'en' => ['name' => 'Ben Aknoun']],

            // ===== الجزائر: وهران (gov_id=210) — (184 + 26 = 210) =====
            ['governorate_id' => 210, 'ar' => ['name' => 'وهران'],       'en' => ['name' => 'Oran City']],
            ['governorate_id' => 210, 'ar' => ['name' => 'عين التركية'],'en' => ['name' => 'Ain El Turk']],
            ['governorate_id' => 210, 'ar' => ['name' => 'أرزيو'],      'en' => ['name' => 'Arzew']],

            // ===== الجزائر: قسنطينة (gov_id=193) — (184 + 9 = 193) =====
            ['governorate_id' => 193, 'ar' => ['name' => 'قسنطينة'],  'en' => ['name' => 'Constantine City']],
            ['governorate_id' => 193, 'ar' => ['name' => 'الخروب'],   'en' => ['name' => 'El Khroub']],

            // ===== المغرب: الرباط (gov_id=230) =====
            ['governorate_id' => 230, 'ar' => ['name' => 'الرباط'],   'en' => ['name' => 'Rabat']],
            ['governorate_id' => 230, 'ar' => ['name' => 'سلا'],      'en' => ['name' => 'Sale']],
            ['governorate_id' => 230, 'ar' => ['name' => 'القنيطرة'], 'en' => ['name' => 'Kenitra']],
            ['governorate_id' => 230, 'ar' => ['name' => 'تمارة'],    'en' => ['name' => 'Temara']],

            // ===== المغرب: الدار البيضاء (gov_id=231) =====
            ['governorate_id' => 231, 'ar' => ['name' => 'الدار البيضاء'],  'en' => ['name' => 'Casablanca']],
            ['governorate_id' => 231, 'ar' => ['name' => 'المحمدية'],       'en' => ['name' => 'Mohammedia']],
            ['governorate_id' => 231, 'ar' => ['name' => 'برشيد'],          'en' => ['name' => 'Berrechid']],

            // ===== المغرب: مراكش (gov_id=232) =====
            ['governorate_id' => 232, 'ar' => ['name' => 'مراكش'],           'en' => ['name' => 'Marrakesh']],
            ['governorate_id' => 232, 'ar' => ['name' => 'آسفي'],            'en' => ['name' => 'Safi']],
            ['governorate_id' => 232, 'ar' => ['name' => 'قلعة السراغنة'],   'en' => ['name' => 'Kelaa des Sraghna']],

            // ===== المغرب: فاس مكناس (gov_id=233) =====
            ['governorate_id' => 233, 'ar' => ['name' => 'فاس'],   'en' => ['name' => 'Fes']],
            ['governorate_id' => 233, 'ar' => ['name' => 'مكناس'], 'en' => ['name' => 'Meknes']],
            ['governorate_id' => 233, 'ar' => ['name' => 'إفران'], 'en' => ['name' => 'Ifrane']],

            // ===== المغرب: طنجة (gov_id=234) =====
            ['governorate_id' => 234, 'ar' => ['name' => 'طنجة'],    'en' => ['name' => 'Tangier']],
            ['governorate_id' => 234, 'ar' => ['name' => 'تطوان'],   'en' => ['name' => 'Tetouan']],
            ['governorate_id' => 234, 'ar' => ['name' => 'الحسيمة'], 'en' => ['name' => 'Al Hoceima']],

            // ===== فلسطين: القدس (gov_id=242) =====
            ['governorate_id' => 242, 'ar' => ['name' => 'القدس القديمة'],  'en' => ['name' => 'Old Jerusalem']],
            ['governorate_id' => 242, 'ar' => ['name' => 'بيت حنينا'],      'en' => ['name' => 'Beit Hanina']],
            ['governorate_id' => 242, 'ar' => ['name' => 'أبو ديس'],        'en' => ['name' => 'Abu Dis']],

            // ===== فلسطين: غزة (gov_id=253) =====
            ['governorate_id' => 253, 'ar' => ['name' => 'مدينة غزة'],  'en' => ['name' => 'Gaza City']],
            ['governorate_id' => 253, 'ar' => ['name' => 'جباليا'],     'en' => ['name' => 'Jabalia']],

            // ===== السودان: الخرطوم (gov_id=258) =====
            ['governorate_id' => 258, 'ar' => ['name' => 'الخرطوم'],      'en' => ['name' => 'Khartoum']],
            ['governorate_id' => 258, 'ar' => ['name' => 'أم درمان'],     'en' => ['name' => 'Omdurman']],
            ['governorate_id' => 258, 'ar' => ['name' => 'الخرطوم بحري'], 'en' => ['name' => 'Khartoum North']],

            // ===== تركيا: إسطنبول (gov_id=351) =====
            ['governorate_id' => 351, 'ar' => ['name' => 'الفاتح'],        'en' => ['name' => 'Fatih']],
            ['governorate_id' => 351, 'ar' => ['name' => 'بشيكتاش'],       'en' => ['name' => 'Beşiktaş']],
            ['governorate_id' => 351, 'ar' => ['name' => 'كاديكوي'],       'en' => ['name' => 'Kadıköy']],
            ['governorate_id' => 351, 'ar' => ['name' => 'أوسكودار'],      'en' => ['name' => 'Üsküdar']],
            ['governorate_id' => 351, 'ar' => ['name' => 'بيوغلو'],        'en' => ['name' => 'Beyoğlu']],
            ['governorate_id' => 351, 'ar' => ['name' => 'شيشلي'],         'en' => ['name' => 'Şişli']],
            ['governorate_id' => 351, 'ar' => ['name' => 'باشاك شهير'],    'en' => ['name' => 'Başakşehir']],
            ['governorate_id' => 351, 'ar' => ['name' => 'إسنيورت'],       'en' => ['name' => 'Esenyurt']],
            ['governorate_id' => 351, 'ar' => ['name' => 'بنديك'],         'en' => ['name' => 'Pendik']],
            ['governorate_id' => 351, 'ar' => ['name' => 'مالتبه'],        'en' => ['name' => 'Maltepe']],

            // ===== تركيا: أنقرة (gov_id=323) =====
            ['governorate_id' => 323, 'ar' => ['name' => 'تشانقايا'],   'en' => ['name' => 'Çankaya']],
            ['governorate_id' => 323, 'ar' => ['name' => 'كيتشيورن'],   'en' => ['name' => 'Keçiören']],
            ['governorate_id' => 323, 'ar' => ['name' => 'يني ماهله'], 'en' => ['name' => 'Yenimahalle']],
            ['governorate_id' => 323, 'ar' => ['name' => 'ألتين داغ'], 'en' => ['name' => 'Altındağ']],
            ['governorate_id' => 323, 'ar' => ['name' => 'أتاشهير'],   'en' => ['name' => 'Etimesgut']],

            // ===== تركيا: إزمير (gov_id=352) =====
            ['governorate_id' => 352, 'ar' => ['name' => 'كوناك'],     'en' => ['name' => 'Konak']],
            ['governorate_id' => 352, 'ar' => ['name' => 'بورنوفا'],   'en' => ['name' => 'Bornova']],
            ['governorate_id' => 352, 'ar' => ['name' => 'كارشياقا'], 'en' => ['name' => 'Karşıyaka']],
            ['governorate_id' => 352, 'ar' => ['name' => 'بوكا'],      'en' => ['name' => 'Buca']],
            ['governorate_id' => 352, 'ar' => ['name' => 'بيانديرلي'],'en' => ['name' => 'Bayındır']],

            // ===== تركيا: أنطاليا (gov_id=324) =====
            ['governorate_id' => 324, 'ar' => ['name' => 'موراتباشا'],   'en' => ['name' => 'Muratpaşa']],
            ['governorate_id' => 324, 'ar' => ['name' => 'قبيز'],         'en' => ['name' => 'Kepez']],
            ['governorate_id' => 324, 'ar' => ['name' => 'ألانيا'],       'en' => ['name' => 'Alanya']],
            ['governorate_id' => 324, 'ar' => ['name' => 'ماناوغات'],     'en' => ['name' => 'Manavgat']],
            ['governorate_id' => 324, 'ar' => ['name' => 'سيريك'],        'en' => ['name' => 'Serik']],

            // ===== تركيا: بورصة (gov_id=333) =====
            ['governorate_id' => 333, 'ar' => ['name' => 'أوسمانغازي'],  'en' => ['name' => 'Osmangazi']],
            ['governorate_id' => 333, 'ar' => ['name' => 'يلدريم'],       'en' => ['name' => 'Yıldırım']],
            ['governorate_id' => 333, 'ar' => ['name' => 'نيلوفر'],       'en' => ['name' => 'Nilüfer']],
            ['governorate_id' => 333, 'ar' => ['name' => 'غمليك'],        'en' => ['name' => 'Gemlik']],

            // ===== تركيا: طرابزون (gov_id=378) =====
            ['governorate_id' => 378, 'ar' => ['name' => 'أورتاهيسار'],  'en' => ['name' => 'Ortahisar']],
            ['governorate_id' => 378, 'ar' => ['name' => 'أكسو'],         'en' => ['name' => 'Akçaabat']],
            ['governorate_id' => 378, 'ar' => ['name' => 'أوف'],          'en' => ['name' => 'Of']],
            ['governorate_id' => 378, 'ar' => ['name' => 'أراكلي'],       'en' => ['name' => 'Araklı']],

            // ===== تركيا: غازي عنتاب (gov_id=344) =====
            ['governorate_id' => 344, 'ar' => ['name' => 'شهيت كامل'],   'en' => ['name' => 'Şahinbey']],
            ['governorate_id' => 344, 'ar' => ['name' => 'شاهين بي'],     'en' => ['name' => 'Şehitkamil']],
            ['governorate_id' => 344, 'ar' => ['name' => 'نيزيب'],        'en' => ['name' => 'Nizip']],
            ['governorate_id' => 344, 'ar' => ['name' => 'إيسلاهيه'],     'en' => ['name' => 'İslahiye']],

            // ===== تركيا: قونيه (gov_id=359) =====
            ['governorate_id' => 359, 'ar' => ['name' => 'سلطان أوغلو'],  'en' => ['name' => 'Selçuklu']],
            ['governorate_id' => 359, 'ar' => ['name' => 'قره طاي'],       'en' => ['name' => 'Karatay']],
            ['governorate_id' => 359, 'ar' => ['name' => 'ميرام'],         'en' => ['name' => 'Meram']],
            ['governorate_id' => 359, 'ar' => ['name' => 'إيريلي'],        'en' => ['name' => 'Ereğli']],

            // ===== تركيا: قيصري (gov_id=355) =====
            ['governorate_id' => 355, 'ar' => ['name' => 'ميليكغازي'],   'en' => ['name' => 'Melikgazi']],
            ['governorate_id' => 355, 'ar' => ['name' => 'قوجاسينان'],   'en' => ['name' => 'Kocasinan']],
            ['governorate_id' => 355, 'ar' => ['name' => 'ديفلي'],        'en' => ['name' => 'Develi']],

            // ===== تركيا: مرسين (gov_id=350) =====
            ['governorate_id' => 350, 'ar' => ['name' => 'يني شهير'],    'en' => ['name' => 'Yenişehir']],
            ['governorate_id' => 350, 'ar' => ['name' => 'أكدنيز'],       'en' => ['name' => 'Akdeniz']],
            ['governorate_id' => 350, 'ar' => ['name' => 'طرسوس'],        'en' => ['name' => 'Tarsus']],
            ['governorate_id' => 350, 'ar' => ['name' => 'إيجيل'],        'en' => ['name' => 'Erdemli']],

            // ===== تركيا: سامسون (gov_id=372) =====
            ['governorate_id' => 372, 'ar' => ['name' => 'إلكادي'],    'en' => ['name' => 'İlkadım']],
            ['governorate_id' => 372, 'ar' => ['name' => 'أتاقوم'],    'en' => ['name' => 'Atakum']],
            ['governorate_id' => 372, 'ar' => ['name' => 'بافرا'],     'en' => ['name' => 'Bafra']],

            // ===== تركيا: أضنة (gov_id=318) =====
            ['governorate_id' => 318, 'ar' => ['name' => 'صيحان'],     'en' => ['name' => 'Seyhan']],
            ['governorate_id' => 318, 'ar' => ['name' => 'يوريغير'],   'en' => ['name' => 'Yüreğir']],
            ['governorate_id' => 318, 'ar' => ['name' => 'جيهان'],     'en' => ['name' => 'Ceyhan']],

            // ===== تركيا: ديار بكر (gov_id=338) =====
            ['governorate_id' => 338, 'ar' => ['name' => 'باغلار'],    'en' => ['name' => 'Bağlar']],
            ['governorate_id' => 338, 'ar' => ['name' => 'يينيشهير'], 'en' => ['name' => 'Yenişehir']],
            ['governorate_id' => 338, 'ar' => ['name' => 'إيرغاني'],   'en' => ['name' => 'Ergani']],

            // ===== تركيا: شانلي أورفا (gov_id=380) =====
            ['governorate_id' => 380, 'ar' => ['name' => 'إيواليونس'],  'en' => ['name' => 'Eyyübiye']],
            ['governorate_id' => 380, 'ar' => ['name' => 'هالفيتي'],    'en' => ['name' => 'Halfeti']],
            ['governorate_id' => 380, 'ar' => ['name' => 'أكجه قلعة'],  'en' => ['name' => 'Akçakale']],
            ['governorate_id' => 380, 'ar' => ['name' => 'بيره جيك'],   'en' => ['name' => 'Birecik']],

        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
