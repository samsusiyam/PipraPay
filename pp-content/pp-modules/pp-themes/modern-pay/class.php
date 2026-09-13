<?php
    class ModernPayTheme
    {
        public function info()
        {
            return [
                'title'       => 'Modern Pay (Next-Gen)',
                'logo'        => 'assets/logo.jpg'
            ];
        }

        public function fields()
        {
            return [
                [
                    'name'  => 'theme_mode',
                    'label' => 'Theme Visual Style',
                    'type'  => 'select',
                    'options' => [
                        'glassmorphism' => 'Modern Glassmorphism (Frosted Glass)',
                        'neo_clean'     => 'Clean Minimalist (Card Elevate)',
                        'gradient_dark' => 'Ultra Dark Gradient',
                    ],
                    'value' => 'glassmorphism',
                    'required' => false,
                    'multiple' => false,
                ],
                [
                    'name'  => 'primary_color',
                    'label' => 'Primary Brand Color',
                    'type'  => 'color',
                    'value' => '#4f46e5',
                    'required' => false,
                    'placeholder' => '#4f46e5',
                ],
                [
                    'name'  => 'accent_color',
                    'label' => 'Secondary Accent Color',
                    'type'  => 'color',
                    'value' => '#06b6d4',
                    'required' => false,
                    'placeholder' => '#06b6d4',
                ],
                [
                    'name'  => 'text_color',
                    'label' => 'Button Text Color',
                    'type'  => 'color',
                    'value' => '#ffffff',
                    'required' => false,
                    'placeholder' => '#ffffff',
                ],
                [
                    'name'  => 'enable_bg_image',
                    'label' => 'Custom Background Image',
                    'type'  => 'select',
                    'options' => [
                        'enabled'  => 'Enable',
                        'disabled' => 'Disable',
                    ],
                    'value' => 'disabled',
                    'required' => false,
                    'multiple' => false,
                ],
                [
                    'name'  => 'background_image',
                    'label' => 'Background Image URL / File',
                    'required' => false,
                    'type'  => 'image',
                ],
                [
                    'name'  => 'watermark_text',
                    'label' => 'Footer Branding Text',
                    'type'  => 'text',
                    'value' => 'Secured by PipraPay',
                    'required' => false,
                    'placeholder' => 'e.g. Secured by PipraPay'
                ],
                [
                    'name'  => 'seo_title',
                    'label' => 'SEO Title',
                    'type'  => 'text',
                    'required' => false,
                    'placeholder' => 'Enter SEO title',
                ],
                [
                    'name'  => 'seo_description',
                    'label' => 'SEO Description',
                    'type'  => 'textarea',
                    'required' => false,
                    'placeholder' => 'Enter SEO description',
                ],
                [
                    'name'  => 'seo_keywords',
                    'label' => 'SEO Keywords',
                    'type'  => 'text',
                    'required' => false,
                    'placeholder' => 'e.g. billing, invoicing, payments',
                ],
                [
                    'name'  => 'analytics_code',
                    'label' => 'Analytics & Tracking Code',
                    'type'  => 'textarea',
                    'required' => false,
                    'placeholder' => 'Paste Google Analytics, GTM, or Meta Pixel code',
                ],
            ];
        }

        public function supported_languages()
        {
            return [
                'en' => 'English',
                'bn' => 'বাংলা',
                'hi' => 'हिन्दी',
                'ur' => 'اردو',
                'ar' => 'العربية',
            ];
        }

        public function lang_text()
        {
            return [
                'payment_link' => [
                    'en' => 'Payment Link',
                    'bn' => 'পেমেন্ট লিঙ্ক',
                    'hi' => 'भुगतान लिंक',
                    'ur' => 'ادائیگی کا لنک',
                    'ar' => 'رابط الدفع',
                ],
                'select_language' => [
                    'en' => 'Select your native language',
                    'bn' => 'আপনার মাতৃভাষা নির্বাচন করুন',
                    'hi' => 'अपनी मूल भाषा चुनें',
                    'ur' => 'اپنی مادری زبان منتخب کریں',
                    'ar' => 'اختر لغتك الأم',
                ],
                'language' => [
                    'en' => 'Language',
                    'bn' => 'ভাষা',
                    'hi' => 'भाषा',
                    'ur' => 'زبان',
                    'ar' => 'اللغة',
                ],
                'select_a_language' => [
                    'en' => 'Select a language',
                    'bn' => 'একটি ভাষা নির্বাচন করুন',
                    'hi' => 'एक भाषा चुनें',
                    'ur' => 'ایک زبان منتخب کریں',
                    'ar' => 'اختر لغة',
                ],
                'close' => [
                    'en' => 'Close',
                    'bn' => 'বন্ধ করুন',
                    'hi' => 'बंद करें',
                    'ur' => 'بند کریں',
                    'ar' => 'إغلاق',
                ],
                'full_name' => [
                    'en' => 'Full Name',
                    'bn' => 'পূর্ণ নাম',
                    'hi' => 'पूरा नाम',
                    'ur' => 'پورا نام',
                    'ar' => 'الاسم الكامل',
                ],
                'email_address' => [
                    'en' => 'Email Address',
                    'bn' => 'ইমেইল ঠিকানা',
                    'hi' => 'ईमेल पता',
                    'ur' => 'ای میل پتہ',
                    'ar' => 'عنوان البريد الإلكتروني',
                ],
                'mobile_number' => [
                    'en' => 'Mobile Number',
                    'bn' => 'মোবাইল নম্বর',
                    'hi' => 'मोबाइल नंबर',
                    'ur' => 'موبائل نمبر',
                    'ar' => 'رقم الجوال',
                ],
                'amount' => [
                    'en' => 'Amount',
                    'bn' => 'পরিমাণ',
                    'hi' => 'राशि',
                    'ur' => 'رقم',
                    'ar' => 'المبلغ',
                ],
                'pay_now' => [
                    'en' => 'Pay Now',
                    'bn' => 'এখনই পরিশোধ করুন',
                    'hi' => 'अभी भुगतान करें',
                    'ur' => 'ابھی ادائیگی کریں',
                    'ar' => 'ادفع الآن',
                ],
                'support' => [
                    'en' => 'Support',
                    'bn' => 'সহায়তা',
                    'hi' => 'सहायता',
                    'ur' => 'مدد',
                    'ar' => 'الدعم',
                ],
                'details' => [
                    'en' => 'Details',
                    'bn' => 'বিস্তারিত',
                    'hi' => 'विवरण',
                    'ur' => 'تفصیلات',
                    'ar' => 'التفاصيل',
                ],
                'faq' => [
                    'en' => 'FAQ',
                    'bn' => 'সাধারণ জিজ্ঞাসা',
                    'hi' => 'अक्सर पूछे जाने वाले सवाल',
                    'ur' => 'عام سوالات',
                    'ar' => 'الأسئلة الشائعة',
                ],
                'invoice' => [
                    'en' => 'Invoice',
                    'bn' => 'চালান',
                    'hi' => 'चालान',
                    'ur' => 'انوائس',
                    'ar' => 'فاتورة',
                ],
                'invoice_date' => [
                    'en' => 'Invoice Date',
                    'bn' => 'চালানের তারিখ',
                    'hi' => 'चालान दिनांक',
                    'ur' => 'تاریخ انوائس',
                    'ar' => 'تاريخ الفاتورة',
                ],
                'due_date' => [
                    'en' => 'Due Date',
                    'bn' => 'সময়সীমা',
                    'hi' => 'अंतिम तिथि',
                    'ur' => 'ادائیگی کی آخری تاریخ',
                    'ar' => 'تاريخ الاستحقاق',
                ],
                'checkout' => [
                    'en' => 'Checkout',
                    'bn' => 'চেকআউট',
                    'hi' => 'चेकआउट',
                    'ur' => 'چیک آؤٹ',
                    'ar' => 'الدفع',
                ],
                'mobile_banking' => [
                    'en' => 'Mobile Banking',
                    'bn' => 'মোবাইল ব্যাংকিং',
                    'hi' => 'मोबाइल बैंकिंग',
                    'ur' => 'موبائل بینکنگ',
                    'ar' => 'الخدمات المصرفية عبر الهاتف المحمول',
                ],
                'net_banking' => [
                    'en' => 'Net Banking',
                    'bn' => 'নেট ব্যাংকিং',
                    'hi' => 'नेट बैंकिंग',
                    'ur' => 'نیٹ بینکنگ',
                    'ar' => 'الخدمات المصرفية عبر الإنترنت',
                ],
                'global' => [
                    'en' => 'Global',
                    'bn' => 'গ্লোবাল',
                    'hi' => 'ग्लोबल',
                    'ur' => 'عالمی',
                    'ar' => 'عالمي',
                ],
                'payment_method' => [
                    'en' => 'Payment Method',
                    'bn' => 'পরিশোধের পদ্ধতি',
                    'hi' => 'भुगतान विधि',
                    'ur' => 'ادائیگی کا طریقہ',
                    'ar' => 'طريقة الدفع',
                ],
                'bill_from' => [
                    'en' => 'Bill From',
                    'bn' => 'বিল পাঠানো হয়েছে',
                    'hi' => 'बिल प्रेषक',
                    'ur' => 'بل بھیجا گیا',
                    'ar' => 'فاتورة من',
                ],
                'email' => [
                    'en' => 'Email',
                    'bn' => 'ইমেইল',
                    'hi' => 'ईमेल',
                    'ur' => 'ای میل',
                    'ar' => 'البريد الإلكتروني',
                ],
                'phone' => [
                    'en' => 'Phone',
                    'bn' => 'ফোন',
                    'hi' => 'फोन',
                    'ur' => 'فون',
                    'ar' => 'الهاتف',
                ],
                'bill_to' => [
                    'en' => 'Bill To',
                    'bn' => 'বিলের গ্রাহক',
                    'hi' => 'बिल प्राप्तकर्ता',
                    'ur' => 'بل وصول کنندہ',
                    'ar' => 'فاتورة إلى',
                ],
                'description' => [
                    'en' => 'Description',
                    'bn' => 'বিবরণ',
                    'hi' => 'विवरण',
                    'ur' => 'تفصیل',
                    'ar' => 'الوصف',
                ],
                'qty' => [
                    'en' => 'Qty',
                    'bn' => 'পরিমাণ',
                    'hi' => 'मात्रा',
                    'ur' => 'تعداد',
                    'ar' => 'الকমية',
                ],
                'unit_price' => [
                    'en' => 'Unit Price',
                    'bn' => 'একক মূল্য',
                    'hi' => 'इकाई मूल्य',
                    'ur' => 'فی یونٹ قیمت',
                    'ar' => 'سعر الوحدة',
                ],
                'note' => [
                    'en' => 'Notes',
                    'bn' => 'নোট',
                    'hi' => 'टिप्पणियाँ',
                    'ur' => 'نوٹس',
                    'ar' => 'ملاحظات',
                ],
                'currency' => [
                    'en' => 'Currency',
                    'bn' => 'মুদ্রা',
                    'hi' => 'मुद्रा',
                    'ur' => 'کرنسی',
                    'ar' => 'العملة',
                ],
                'subtotal' => [
                    'en' => 'Subtotal',
                    'bn' => 'উপ-মোট',
                    'hi' => 'उप-योग',
                    'ur' => 'ذیلی مجموعہ',
                    'ar' => 'المجموع الفرعي',
                ],
                'shipping' => [
                    'en' => 'Shipping',
                    'bn' => 'শিপিং',
                    'hi' => 'शिपिंग',
                    'ur' => 'شپنگ',
                    'ar' => 'الشحن',
                ],
                'tax' => [
                    'en' => 'Tax (VAT)',
                    'bn' => 'ট্যাক্স (ভ্যাট)',
                    'hi' => 'कर (वैट)',
                    'ur' => 'ٹیکس (وی اے ٹی)',
                    'ar' => 'الضريبة (ضريبة القيمة المضافة)',
                ],
                'discount' => [
                    'en' => 'Discount',
                    'bn' => 'ছাড়',
                    'hi' => 'छूट',
                    'ur' => 'رعایت',
                    'ar' => 'الخصم',
                ],
                'total' => [
                    'en' => 'Total',
                    'bn' => 'মোট',
                    'hi' => 'कुल',
                    'ur' => 'کل',
                    'ar' => 'الإجمالي',
                ],
                'total_due' => [
                    'en' => 'Total Due',
                    'bn' => 'মোট প্রদেয়',
                    'hi' => 'कुल देय',
                    'ur' => 'کل واجب الادا',
                    'ar' => 'الإجمالي المستحق',
                ],
                'no_signature' => [
                    'en' => 'This is an electronically generated invoice. No signature required.',
                    'bn' => 'এটি একটি ইলেকট্রনিকভাবে তৈরি চালান। কোনো স্বাক্ষরের প্রয়োজন নেই।',
                    'hi' => 'यह एक इलेक्ट्रॉनिक रूप से जनरेट किया गया चालान है। कोई हस्ताक्षर आवश्यक नहीं।',
                    'ur' => 'یہ ایک الیکٹرانک طور پر تیار کردہ انوائس ہے۔ دستخط کی ضرورت نہیں۔',
                    'ar' => 'هذه فاتورة تم إنشاؤها إلكترونيًا. لا حاجة للتوقيع.',
                ],
                'print_invoice' => [
                    'en' => 'Print Invoice',
                    'bn' => 'চালান প্রিন্ট করুন',
                    'hi' => 'चालान प्रिंट करें',
                    'ur' => 'انوائس پرنٹ کریں',
                    'ar' => 'طباعة الفاتورة',
                ],
                'badge_paid' => [
                    'en' => 'Paid',
                    'bn' => 'পরিশোধিত',
                    'hi' => 'भुगतान किया गया',
                    'ur' => 'ادا شدہ',
                    'ar' => 'مدفوع',
                ],
                'badge_unpaid' => [
                    'en' => 'Unpaid',
                    'bn' => 'অপরিশোধিত',
                    'hi' => 'अवैतनिक',
                    'ur' => 'غیر ادا شدہ',
                    'ar' => 'غير مدفوع',
                ],
                'badge_refunded' => [
                    'en' => 'Refunded',
                    'bn' => 'ফিরতি হয়েছে',
                    'hi' => 'वापस किया गया',
                    'ur' => 'رقم واپس کیا گیا',
                    'ar' => 'تم استرداده',
                ],
                'badge_canceled' => [
                    'en' => 'Canceled',
                    'bn' => 'বাতিল',
                    'hi' => 'रद्द किया गया',
                    'ur' => 'منسوخ شدہ',
                    'ar' => 'ملغاة',
                ],
                'contact_fb_page' => [
                    'en' => 'Contact via Facebook Page',
                    'bn' => 'ফেসবুক পেজের মাধ্যমে যোগাযোগ করুন',
                    'hi' => 'फेसबुक पेज के माध्यम से संपर्क करें',
                    'ur' => 'فیس بک پیج کے ذریعے رابطہ کریں',
                    'ar' => 'تواصل عبر صفحة فيسبوك',
                ],
                'contact_messenger' => [
                    'en' => 'Contact via Messenger',
                    'bn' => 'মেসেঞ্জারের মাধ্যমে যোগাযোগ করুন',
                    'hi' => 'मैसेंजर के माध्यम से संपर्क करें',
                    'ur' => 'میسنجر کے ذریعے رابطہ کریں',
                    'ar' => 'تواصل عبر ماسنجر',
                ],
                'contact_website' => [
                    'en' => 'Visit Website',
                    'bn' => 'ওয়েবসাইটে যান',
                    'hi' => 'वेबसाइट पर जाएं',
                    'ur' => 'ویب سائٹ پر جائیں',
                    'ar' => 'زيارة الموقع',
                ],
                'contact_telegram' => [
                    'en' => 'Contact via Telegram',
                    'bn' => 'টেলিগ্রামের মাধ্যমে যোগাযোগ করুন',
                    'hi' => 'टेलीग्राम के माध्यम से संपर्क करें',
                    'ur' => 'ٹیلیگرام کے ذریعے رابطہ کریں',
                    'ar' => 'تواصل عبر تيليجرام',
                ],
                'contact_whatsapp' => [
                    'en' => 'Contact via WhatsApp',
                    'bn' => 'হোয়াটসঅ্যাপের মাধ্যমে যোগাযোগ করুন',
                    'hi' => 'व्हाट्सएप के माध्यम से संपर्क करें',
                    'ur' => 'واٹس ایپ کے ذریعے رابطہ کریں',
                    'ar' => 'تواصل عبر واتساب',
                ],
                'contact_phone' => [
                    'en' => 'Contact via Phone',
                    'bn' => 'ফোনের মাধ্যমে যোগাযোগ করুন',
                    'hi' => 'फोन के माध्यम से संपर्क करें',
                    'ur' => 'فون کے ذریعے رابطہ کریں',
                    'ar' => 'تواصل عبر الهاتف',
                ],
                'contact_email' => [
                    'en' => 'Contact via Email',
                    'bn' => 'ইমেলের মাধ্যমে যোগাযোগ করুন',
                    'hi' => 'ईमेल के माध्यम से संपर्क करें',
                    'ur' => 'ای میل کے ذریعے رابطہ کریں',
                    'ar' => 'تواصل عبر البريد الإلكتروني',
                ],
                'payment_successful' => [
                    'en' => 'Payment Successful!',
                    'bn' => 'পেমেন্ট সফল হয়েছে!',
                    'hi' => 'भुगतान सफल रहा!',
                    'ur' => 'ادائیگی کامیاب ہو گئی!',
                    'ar' => 'تم الدفع بنجاح!',
                ],
                'payment_pending' => [
                    'en' => 'Payment Processing',
                    'bn' => 'পেমেন্ট প্রক্রিয়াধীন রয়েছে',
                    'hi' => 'भुगतान प्रक्रिया में है',
                    'ur' => 'ادائیگی پروسیسنگ میں ہے',
                    'ar' => 'جاري معالجة الدفع',
                ],
                'payment_refunded' => [
                    'en' => 'Payment Refunded',
                    'bn' => 'পেমেন্ট রিফান্ড করা হয়েছে',
                    'hi' => 'भुगतान वापस कर दिया गया',
                    'ur' => 'ادائیگی واپس کر دی گئی',
                    'ar' => 'تم استرداد المبلغ',
                ],
                'payment_canceled' => [
                    'en' => 'Payment Canceled',
                    'bn' => 'পেমেন্ট বাতিল করা হয়েছে',
                    'hi' => 'भुगतान रद्द कर दिया गया',
                    'ur' => 'ادائیگی منسوخ کر دی گئی',
                    'ar' => 'تم إلغاء الدفع',
                ],
                'download_receipt' => [
                    'en' => 'Download Receipt',
                    'bn' => 'রসিদ ডাউনলোড করুন',
                    'hi' => 'रसीद डाउनलोड करें',
                    'ur' => 'رسید ڈاؤن لوڈ کریں',
                    'ar' => 'تحميل الإيصال',
                ],
                'payment_method' => [
                    'en' => 'Payment Method',
                    'bn' => 'পেমেন্ট পদ্ধতি',
                    'hi' => 'भुगतान का तरीका',
                    'ur' => 'ادائیگی کا طریقہ',
                    'ar' => 'طريقة الدفع',
                ],
                'discount' => [
                    'en' => 'Discount',
                    'bn' => 'ছাড়',
                    'hi' => 'छूट',
                    'ur' => 'رعایت',
                    'ar' => 'الخصم',
                ],
                'processing_fee' => [
                    'en' => 'Processing Fee',
                    'bn' => 'প্রসেসিং ফি',
                    'hi' => 'प्रसंस्करण शुल्क',
                    'ur' => 'پروسیسنگ فیس',
                    'ar' => 'رسوم المعالجة',
                ],
                'net_amount' => [
                    'en' => 'Net Amount',
                    'bn' => 'নেট পরিমাণ',
                    'hi' => 'कुल राशि',
                    'ur' => 'کل رقم',
                    'ar' => 'المبلغ الصافي',
                ],
                'net_local_amount' => [
                    'en' => 'Payable Local Amount',
                    'bn' => 'পরিশোধযোগ্য মোট টাকা',
                    'hi' => 'देय स्थानीय राशि',
                    'ur' => 'قابل ادائیگی مقامی رقم',
                    'ar' => 'المبلغ المحلي المستحق',
                ],
                'status' => [
                    'en' => 'Status',
                    'bn' => 'অবস্থা',
                    'hi' => 'स्थिति',
                    'ur' => 'حیثیت',
                    'ar' => 'الحالة',
                ],
                'go_to_site' => [
                    'en' => 'Go to Site',
                    'bn' => 'সাইটে যান',
                    'hi' => 'साइट पर जाएं',
                    'ur' => 'سائٹ پر جائیں',
                    'ar' => 'الانتقال إلى الموقع',
                ],
                'change_status_completed' => [
                    'en' => 'Your payment has been successfully processed and verified.',
                    'bn' => 'আপনার পেমেন্টটি সফলভাবে সম্পন্ন ও নিশ্চিত করা হয়েছে।',
                    'hi' => 'आपका भुगतान सफलतापूर्वक संसाधित और सत्यापित हो गया है।',
                    'ur' => 'آپ کی ادائیگی کامیابی کے ساتھ مکمل اور تصدیق ہو گئی ہے۔',
                    'ar' => 'تمت معالجة دفعتك والتحقق منها بنجاح.',
                ],
                'change_status_pending' => [
                    'en' => 'Your payment is being verified. Please wait a moment.',
                    'bn' => 'আপনার পেমেন্টটি যাচাই করা হচ্ছে। অনুগ্রহ করে কিছুক্ষণ অপেক্ষা করুন।',
                    'hi' => 'आपके भुगतान का सत्यापन किया जा रहा है। कृपया प्रतीक्षा करें।',
                    'ur' => 'آپ کی ادائیگی کی تصدیق کی جا رہی ہے۔ براہ کرم انتظار کریں۔',
                    'ar' => 'جاري التحقق من دفعتك. يرجى الانتظار لحظة.',
                ],
                'change_status_refunded' => [
                    'en' => 'This transaction has been refunded back to your account.',
                    'bn' => 'এই লেনদেনের টাকা আপনার একাউন্টে রিফান্ড করা হয়েছে।',
                    'hi' => 'यह लेन-देन आपके खाते में वापस कर दिया गया है।',
                    'ur' => 'یہ لین دین آپ کے اکاؤنٹ میں واپس کر دیا گیا ہے۔',
                    'ar' => 'تم استرداد هذه المعاملة إلى حسابك.',
                ],
                'change_status_cancled' => [
                    'en' => 'This payment session was canceled.',
                    'bn' => 'পেমেন্টটি বাতিল করা হয়েছে।',
                    'hi' => 'यह भुगतान सत्र रद्द कर दिया गया था।',
                    'ur' => 'ادائیگی منسوخ کر دی گئی تھی۔',
                    'ar' => 'تم إلغاء جلسة الدفع هذه.',
                ],
                'copied' => [
                    'en' => 'Copied!',
                    'bn' => 'কপি হয়েছে!',
                    'hi' => 'कॉपी हो गया!',
                    'ur' => 'کاپی ہو گیا!',
                    'ar' => 'تم النسخ!',
                ],
                'copied_successfully' => [
                    'en' => 'Copied Successfully',
                    'bn' => 'সফলভাবে কপি হয়েছে',
                    'hi' => 'सफलतापूर्वक कॉपी हो गया',
                    'ur' => 'کامیابی سے کاپی ہو گیا',
                    'ar' => 'تم النسخ بنجاح',
                ],
                'copy_content_copied' => [
                    'en' => 'The content has been copied to your clipboard.',
                    'bn' => 'বিষয়বস্তু আপনার ক্লিপবোর্ডে কপি করা হয়েছে।',
                    'hi' => 'सामग्री आपके क्लिपबोर्ड पर कॉपी कर दी गई है।',
                    'ur' => 'مواد آپ کے کلپ بورڈ پر کاپی کر دیا گیا ہے۔',
                    'ar' => 'تم نسخ المحتوى إلى الحافظة.',
                ],
                'copy_failed' => [
                    'en' => 'Copy Failed!',
                    'bn' => 'কপি ব্যর্থ হয়েছে!',
                    'hi' => 'कॉपी विफल हो गई!',
                    'ur' => 'کاپی ناکام ہو گئی!',
                    'ar' => 'فشل النسخ!',
                ],
                'copy_failed_text' => [
                    'en' => 'Unable to copy. Please try manually.',
                    'bn' => 'কপি করা সম্ভব হয়নি। অনুগ্রহ করে ম্যানুয়ালি চেষ্টা করুন।',
                    'hi' => 'कॉपी नहीं हो सका। कृपया मैन्युअल रूप से प्रयास करें।',
                    'ur' => 'کاپی نہیں ہو سکی۔ براہ کرم دستی طور پر کوشش کریں۔',
                    'ar' => 'تعذّر النسخ. يرجى المحاولة يدويًا.',
                ],
                'product_not_active' => [
                    'en' => 'This payment link is currently inactive or expired.',
                    'bn' => 'এই পেমেন্ট লিঙ্কটি বর্তমানে নিষ্ক্রিয় বা মেয়াদোত্তীর্ণ।',
                    'hi' => 'यह भुगतान लिंक वर्तमान में निष्क्रिय या समाप्त हो चुका है।',
                    'ur' => 'یہ ادائیگی کا لنک فی الحال غیر فعال ہے یا اس کی میعاد ختم ہو چکی ہے۔',
                    'ar' => 'رابط الدفع هذا غير نشط حاليًا أو منتهي الصلاحية.',
                ],
                'product_not_active_text' => [
                    'en' => 'This product is currently inactive, so the payment link is not available.',
                    'bn' => 'এই পণ্যটি বর্তমানে নিষ্ক্রিয় রয়েছে, তাই পেমেন্ট লিংকটি উপলব্ধ নয়।',
                    'hi' => 'यह उत्पाद वर्तमान में निष्क्रिय है, इसलिए भुगतान लिंक उपलब्ध नहीं है।',
                    'ur' => 'یہ پروڈکٹ اس وقت غیر فعال ہے، اس لیے ادائیگی کا لنک دستیاب نہیں ہے۔',
                    'ar' => 'هذا المنتج غير نشط حاليًا، لذلك رابط الدفع غير متوفر.',
                ],
                'something_wrong' => [
                    'en' => 'Something Went Wrong!',
                    'bn' => 'কিছু একটা সমস্যা হয়েছে!',
                    'hi' => 'कुछ गलत हो गया!',
                    'ur' => 'کچھ غلط ہو گیا!',
                    'ar' => 'حدث خطأ ما!',
                ],
                'support_contact_text' => [
                    'en' => 'For further assistance, please contact our support team.',
                    'bn' => 'আরও সহায়তার জন্য, আমাদের সাপোর্ট টিমের সাথে যোগাযোগ করুন।',
                    'hi' => 'अधिक सहायता के लिए, कृपया हमारी सहायता टीम से संपर्क करें।',
                    'ur' => 'مزید مدد کے لیے، براہ کرم ہماری سپورٹ ٹیم سے رابطہ کریں۔',
                    'ar' => 'للمزيد من المساعدة، يرجى التواصل مع فريق الدعم.',
                ],
                'copy_no_content' => [
                    'en' => 'No content provided to copy.',
                    'bn' => 'কপি করার জন্য কোনো বিষয়বস্তু নেই।',
                    'hi' => 'कॉपी करने के लिए कोई सामग्री नहीं मिली।',
                    'ur' => 'کاپی کرنے کے لیے کوئی مواد فراہم نہیں کیا گیا۔',
                    'ar' => 'لا يوجد محتوى للنسخ.',
                ],
            ];
        }

        public function renderCheckout($data = [])
        {
            if($data['transaction']['status'] == "initiated"){
                if(isset($_GET['gateway'])){
                    include(__DIR__.'/gateway.php');
                }else{
                    include(__DIR__.'/checkout.php');
                }
            }else{
                include(__DIR__.'/checkout-status.php');
            }
        }

        public function renderInvoice($data = [])
        {
            include(__DIR__.'/invoice.php');
        }

        public function renderPaymentLink($data = [])
        {
            include(__DIR__.'/payment-link.php');
        }

        public function renderPaymentLinkDefault($data = [])
        {
            include(__DIR__.'/payment-link-default.php');
        }
    }
