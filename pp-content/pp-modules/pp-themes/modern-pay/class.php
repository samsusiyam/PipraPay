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
                    'bn' => 'আপনার ভাষা নির্বাচন করুন',
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
                    'bn' => 'ইনভয়েস',
                    'hi' => 'चालान',
                    'ur' => 'انوائس',
                    'ar' => 'الفاتورة',
                ],
                'checkout' => [
                    'en' => 'Secure Checkout',
                    'bn' => 'নিরাপদ চেকআউট',
                    'hi' => 'सुरक्षित चेकआउट',
                    'ur' => 'محفوظ چیک آؤٹ',
                    'ar' => 'الدفع الآمن',
                ],
                'mobile_banking' => [
                    'en' => 'Mobile Banking',
                    'bn' => 'মোবাইল ব্যাংকিং',
                    'hi' => 'मोबाइल बैंकिंग',
                    'ur' => 'موبائل بینکنگ',
                    'ar' => 'الخدمات المصرفية عبر الهاتف',
                ],
                'net_banking' => [
                    'en' => 'Cards & Net Banking',
                    'bn' => 'কার্ড ও ইন্টারনেট ব্যাংকিং',
                    'hi' => 'कार्ड और नेट बैंकिंग',
                    'ur' => 'کارڈز اور نیٹ بینکنگ',
                    'ar' => 'البطاقات والخدمات المصرفية',
                ],
                'global' => [
                    'en' => 'Global & Crypto',
                    'bn' => 'আন্তর্জাতিক ও ক্রিপ্টো',
                    'hi' => 'ग्लोबल और क्रिप्टो',
                    'ur' => 'عالمی اور کرپٹو',
                    'ar' => 'عالمي وعملات مشفرة',
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
                    'bn' => 'মানি রিসিট ডাউনলোড',
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
                    'bn' => 'সর্বমোট পরিমাণ',
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
                'product_not_active' => [
                    'en' => 'This payment link is currently inactive or expired.',
                    'bn' => 'এই পেমেন্ট লিঙ্কটি বর্তমানে নিষ্ক্রিয় বা মেয়াদোত্তীর্ণ।',
                    'hi' => 'यह भुगतान लिंक वर्तमान में निष्क्रिय या समाप्त हो चुका है।',
                    'ur' => 'یہ ادائیگی کا لنک فی الحال غیر فعال ہے یا اس کی میعاد ختم ہو چکی ہے۔',
                    'ar' => 'رابط الدفع هذا غير نشط حاليًا أو منتهي الصلاحية.',
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
