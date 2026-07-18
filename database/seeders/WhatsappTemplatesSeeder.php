<?php

namespace Database\Seeders;

use App\Models\WhatsappTemplate;
use Illuminate\Database\Seeder;

class WhatsappTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'type'        => 'student_registered',
                'title'       => 'تسجيل طالب جديد',
                'description' => "مرحباً {name} 👋\nتم تسجيلك بنجاح في {organization}.\nسيتم مراجعة طلبك والتواصل معك قريباً.\nشكراً لاختيارك منهل.",
                'is_active'   => true,
            ],
            [
                'type'        => 'student_approved',
                'title'       => 'قبول الطالب',
                'description' => "مرحباً {name} 🎉\nيسعدنا إخبارك بأنه تم قبول تسجيلك في {organization}.\nيمكنك الآن الدخول والاستفادة من جميع الخدمات.\nنتمنى لك رحلة تعليمية موفقة.",
                'is_active'   => true,
            ],
            [
                'type'        => 'lesson_reminder',
                'title'       => 'تذكير بموعد الدرس',
                'description' => "تذكير 📚\nمرحباً {name}، لديك درس غداً.\n📖 المادة: {subject}\n📅 التاريخ: {date}\n⏰ الوقت: {time}\nنتمنى لك حصة مثمرة.",
                'is_active'   => true,
            ],
            [
                'type'        => 'lesson_attendance',
                'title'       => 'إشعار الحضور',
                'description' => "إشعار حضور ✅\nالطالب/ة {name} حضر/ت درس {subject} بتاريخ {date}.\nشكراً على الالتزام.",
                'is_active'   => true,
            ],
            [
                'type'        => 'student_absent',
                'title'       => 'إشعار غياب الطالب',
                'description' => "إشعار غياب ❌\nنود إعلامكم بأن الطالب/ة {name} تغيب/ت عن درس {subject} بتاريخ {date}.\nللاستفسار تواصلوا مع الإدارة.",
                'is_active'   => true,
            ],
            [
                'type'        => 'payment_reminder',
                'title'       => 'تذكير بالدفع',
                'description' => "تذكير مالي 💳\nمرحباً {name}، نود تذكيرك بأن قسط {installment_name} بمبلغ {amount} {currency} مستحق بتاريخ {due_date}.\nيرجى سداد المبلغ في أقرب وقت لتجنب أي تأخير.\nشكراً لتعاملك معنا.",
                'is_active'   => true,
            ],
            [
                'type'        => 'teacher_welcome',
                'title'       => 'ترحيب بالمعلم',
                'description' => "أهلاً وسهلاً {name} 🌟\nيسعدنا انضمامك إلى فريق {organization}.\nتم إنشاء حسابك بنجاح، يمكنك الدخول الآن وإدارة حصصك.\nنتمنى لك تجربة تدريسية رائعة.",
                'is_active'   => true,
            ],
            [
                'type'        => 'exam_result',
                'title'       => 'نتيجة الاختبار',
                'description' => "نتيجة الاختبار 📊\nمرحباً {name}، تم تصحيح اختبار {subject}.\n✏️ درجتك: {score} من {total}\n🏅 التقدير: {grade}\nنتمنى لك مزيداً من التقدم والنجاح.",
                'is_active'   => true,
            ],
        ];

        foreach ($templates as $template) {
            WhatsappTemplate::firstOrCreate(
                ['type' => $template['type']],
                $template
            );
        }
    }
}
