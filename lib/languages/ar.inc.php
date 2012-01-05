<?php
/* ************************************************************
Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
Revision:   CompactCMS - v 1.4.2
	
This file is part of CompactCMS.

CompactCMS is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

CompactCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

A reference to the original author of CompactCMS and its copyright
should be clearly visible AT ALL TIMES for the user of the back-
end. You are NOT allowed to remove any references to the original
author, communicating the product to be your own, without written
permission of the original copyright owner.

You should have received a copy of the GNU General Public License
along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.
	
> Contact me for any inquiries.
> E: Xander@CompactCMS.nl
> W: http://community.CompactCMS.nl/forum
************************************************************ */

// System wide messages
$ccms['lang']['system']['error_database'] = /* BABELFISH */ "تعذر الاتصال بقاعدة البيانات. يرجى التحقق من تفاصيل تسجيل الدخول واسم قاعدة البيانات.";
$ccms['lang']['system']['error_openfile'] = /* BABELFISH */ "لا يمكن فتح الملف المحدد";
$ccms['lang']['system']['error_notemplate'] = /* BABELFISH */ "قد لا يمكن العثور على نماذج ليتم تطبيقها على موقعك. يرجى إضافة قالب واحد على الأقل ل. / ليب / قوالب /.";
$ccms['lang']['system']['error_templatedir'] = /* BABELFISH */ "تعذر العثور على قوالب الدليل! تأكد من أنه موجود ويحتوي على قالب واحد على الأقل.";
$ccms['lang']['system']['error_write'] = /* BABELFISH */ "ملف لديه حق الوصول إلى أي";
$ccms['lang']['system']['error_dirwrite'] = /* BABELFISH */ "دليل لديه حق الوصول إلى أي";
$ccms['lang']['system']['error_chmod'] = /* BABELFISH */ "ولم يتسن على الملف الحالي يمكن تعديلها. تحقق الأذونات على الملفات في الدليل / المحتوى (666).";
$ccms['lang']['system']['error_value'] = /* BABELFISH */ "خطأ : قيمة غير صحيحة";
$ccms['lang']['system']['error_default'] = /* BABELFISH */ "لا يمكن البحث عن صفحة يمكن حذفها.";
$ccms['lang']['system']['error_filedots'] = /* BABELFISH */ "وينبغي أن اسم الملف لا يحتوي على النقاط ، على سبيل المثال. أتش تي أم أل.";
$ccms['lang']['system']['error_filesize'] = /* BABELFISH */ "وينبغي أن يكون اسم ملف ما لا يقل عن 3 أحرف.";
$ccms['lang']['system']['error_filesize_2'] = /* BABELFISH */ "وينبغي أن يكون اسم الملف على الأكثر 50 حرفا.";
$ccms['lang']['system']['error_pagetitle'] = /* BABELFISH */ "أدخل عنوان الصفحة من 3 أحرف أو أكثر.";
$ccms['lang']['system']['error_pagetitle_2'] = /* BABELFISH */ "أدخل عنوان الصفحة من 100 حرفا أو أقل.";
$ccms['lang']['system']['error_subtitle'] = /* BABELFISH */ "منح قصيرة العنوان الفرعي لصفحتك.";
$ccms['lang']['system']['error_subtitle_2'] = /* BABELFISH */ "أدخل قصيرة العنوان الفرعي من 200 حرفا أو أقل لصفحتك.";
$ccms['lang']['system']['error_description'] = /* BABELFISH */ "أدخل وصفا لأكثر من 3 أحرف";
$ccms['lang']['system']['error_description_2'] = /* BABELFISH */ "أدخل وصفا أقل من 250 حرفا";
$ccms['lang']['system']['error_reserved'] = /* BABELFISH */ "لقد حددت اسم ملف محفوظة للاستخدام الداخلي.";
$ccms['lang']['system']['error_general'] = /* BABELFISH */ "حدث خطأ";
$ccms['lang']['system']['error_correct'] = /* BABELFISH */ "الرجاء تصحيح ما يلي :";
$ccms['lang']['system']['error_create'] = /* BABELFISH */ "خطأ حين الانتهاء من إنشاء ملف جديد";
$ccms['lang']['system']['error_exists'] = /* BABELFISH */ "اسم الملف الذي حددته مسبقا.";
$ccms['lang']['system']['error_delete'] = /* BABELFISH */ "خطأ حين الانتهاء من حذف الملف المحدد";
$ccms['lang']['system']['error_selection'] = /* BABELFISH */ "لا توجد العناصر المحددة.";
$ccms['lang']['system']['error_versioninfo'] = /* BABELFISH */ "لا توجد معلومات إصدار متوفر.";
$ccms['lang']['system']['error_misconfig'] = /* BABELFISH */ "<strong>هناك يبدو أن التكوين.</strong><br>الرجاء التأكد من أن يتم تكوين الملف بشكل صحيح تاكيس. تعكس بنية الملف الخاص بك. إذا كان لديك<br>CompactCMS مثبت في دليل فرعي ، ثم تعديل الملف. هتكس وفقا لذلك.";
$ccms['lang']['system']['error_deleted'] = /* BABELFISH */ "<h1>الملف الذي حددته ويبدو أن حذف</h1> <p>تحديث filelist لمشاهدة أحدث قائمة من الملفات المتاحة لمنع هذا الخطأ من الحدوث. وإذا كان هذا لا يحل هذا الخطأ ، والتحقق من يدويا المجلد المحتوى للملف الذي تحاول فتح.</p>";
$ccms['lang']['system']['error_404title'] = /* BABELFISH */ "لم يتم العثور على الملف";
$ccms['lang']['system']['error_404header'] = /* BABELFISH */ "حدث خطأ 404 ، لا يمكن أن الملف المطلوب يمكن العثور عليه.";
$ccms['lang']['system']['error_404content'] = /* BABELFISH */ "الملف المطلوب <strong>{%pagereq%}.html</strong> لا يمكن العثور عليها.";
$ccms['lang']['system']['error_403title'] = /* BABELFISH */ "ممنوع";
$ccms['lang']['system']['error_403header'] = /* BABELFISH */ "حدث خطأ 403 : لم يكن لديك إذن الوصول إلى الملف المطلوب.";
$ccms['lang']['system']['error_403content'] = /* BABELFISH */ "يسمح لك ليست الوصول إلى <strong>{%pagereq%}.html</strong> في هذه اللحظة.";
$ccms['lang']['system']['error_sitemap'] = /* BABELFISH */ "نظرة عامة على جميع الصفحات";
$ccms['lang']['system']['error_tooshort'] = /* BABELFISH */ "وكانت واحدة أو متعددة القيم المقدمة إما قصيرة جدا أو غير صحيحة";
$ccms['lang']['system']['error_passshort'] = /* BABELFISH */ "وينبغي أن كلمة المرور تحتوي على أكثر من 6 أحرف";
$ccms['lang']['system']['error_passnequal'] = /* BABELFISH */ "وقال إن دخلت كلمات السر لا تتطابق";
$ccms['lang']['system']['noresults'] = /* BABELFISH */ "لا نتائج";
$ccms['lang']['system']['tooriginal'] = /* BABELFISH */ "العودة إلى الأصلي";
$ccms['lang']['system']['message_rights'] = /* BABELFISH */ "جميع الحقوق محفوظة";
$ccms['lang']['system']['message_compatible'] = /* BABELFISH */ "اختبرت بنجاح على";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = /* BABELFISH */ "اقتراحات حصل ، ملاحظات أو تواجه مشكلة؟ زيارة <a class='external' title='Visit the official forum' rel='external' href='http://community.compactcms.nl/forum/'>للمنتدى</a>!";
$ccms['lang']['backend']['ordertip'] = /* BABELFISH */ "استخدام هبوطا الإفلات أدناه لتعكس هيكل صفحاتك في قائمة المواقع الخاصة بك. أن تكون على علم بأن النظام لا يأخذ بعين الاعتبار تكرار تركيبات المستوى الأعلى أو الفرعي.";
$ccms['lang']['backend']['createtip'] = /* BABELFISH */ "لإنشاء صفحة جديدة ، ملء النموذج أدناه وسيتم إنشاء صفحة جديدة لك على الطاير. بعد إنشاء الملف ، عليك أن تكون قادرا على تحرير الصفحة كالمعتاد.";
$ccms['lang']['backend']['currentfiles'] = /* BABELFISH */ "في القائمة أدناه ستجد كل الصفحات المنشورة حاليا. ستلاحظ أن الصفحة لا يمكن حذف الملف الافتراضي ، لأنه موقع من موقعك الحالي. ملفات أخرى قد يكون وصول مقيد لأن المسؤول الملكية الوحيدة على هذه الملفات.";
$ccms['lang']['backend']['confirmdelete'] = /* BABELFISH */ "الرجاء تأكيد أنك تريد حذف العنصر المحدد (ق).";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "الرجاء تأكيد أنك تريد إعادة جميع الصور المصغرة.";
$ccms['lang']['backend']['settingssaved'] = /* BABELFISH */ "لقد حدثت تغيرات المحفوظة بنجاح.";
$ccms['lang']['backend']['must_refresh'] = /* BABELFISH */ "يرجى التأكد من تحديث الصفحة الرئيسية لرؤية <strong>جميع</strong> تغييراتك";
$ccms['lang']['backend']['itemcreated'] = /* BABELFISH */ "بنجاح معالجة هذا البند المقدم (ق).";
$ccms['lang']['backend']['fullremoved'] = /* BABELFISH */ "بنجاح حذف العنصر المحدد (ق).";
$ccms['lang']['backend']['fullregenerated'] = /* BABELFISH */ "مجدد بنجاح المصغرة.";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "عودة إلى نظرة عامة";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "تعيين أذونات آلية التنسيق القطرية";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "تعريف مالكي المحتوى";
$ccms['lang']['backend']['templateeditor'] = /* BABELFISH */ "قالب محرر";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "إدارة المستخدم";
$ccms['lang']['backend']['changevalue'] = /* BABELFISH */ "انقر لتغيير";
$ccms['lang']['backend']['previewpage'] = /* BABELFISH */ "معاينة";
$ccms['lang']['backend']['editpage'] = /* BABELFISH */ "تحرير";
$ccms['lang']['backend']['restrictpage'] = /* BABELFISH */ "مقيد";
$ccms['lang']['backend']['newfiledone'] = /* BABELFISH */ "هذا الملف هو جديدة ونظيفة لتعبئته المتابعة!";
$ccms['lang']['backend']['newfilecreated'] = /* BABELFISH */ "وقد تم إنشاء الملف";
$ccms['lang']['backend']['startedittitle'] = /* BABELFISH */ "بدء التحرير!";
$ccms['lang']['backend']['starteditbody'] = /* BABELFISH */ "تم إنشاء ملف جديد. بدء تحرير الفور أو بإحدى هاتين العقوبتين إضافة المزيد من الصفحات أو إدارة الحالية.";
$ccms['lang']['backend']['success'] = /* BABELFISH */ "النجاح!";
$ccms['lang']['backend']['fileexists'] = /* BABELFISH */ "الملف موجود";
$ccms['lang']['backend']['statusdelete'] = /* BABELFISH */ "حالة الحذف المختارة :";
$ccms['lang']['backend']['statusremoved'] = /* BABELFISH */ "إزالة";
$ccms['lang']['backend']['uptodate'] = /* BABELFISH */ "حتى الآن.";
$ccms['lang']['backend']['outofdate'] = /* BABELFISH */ "عفا عليها الزمن.";
$ccms['lang']['backend']['considerupdate'] = /* BABELFISH */ "النظر في تحديث";
$ccms['lang']['backend']['orderprefsaved'] = /* BABELFISH */ "تم حفظ تفضيلاتك للترتيب عناصر القائمة الخاصة بك.";
$ccms['lang']['backend']['inmenu'] = /* BABELFISH */ "في القائمة";
$ccms['lang']['backend']['updatelist'] = /* BABELFISH */ "ملف تحديث قائمة";
$ccms['lang']['backend']['administration'] = /* BABELFISH */ "الادارة";
$ccms['lang']['backend']['currentversion'] = /* BABELFISH */ "كنت تشغل حاليا الإصدار";
$ccms['lang']['backend']['mostrecent'] = /* BABELFISH */ "كان آخرها إصدار CompactCMS مستقرة";
$ccms['lang']['backend']['versionstatus'] = /* BABELFISH */ "التثبيت هو";
$ccms['lang']['backend']['createpage'] = /* BABELFISH */ "إنشاء صفحة جديدة";
$ccms['lang']['backend']['managemenu'] = /* BABELFISH */ "إدارة القائمة";
$ccms['lang']['backend']['managefiles'] = /* BABELFISH */ "إدارة الصفحات الحالية";
$ccms['lang']['backend']['delete'] = /* BABELFISH */ "حذف";
$ccms['lang']['backend']['toplevel'] = /* BABELFISH */ "أعلى مستوى";
$ccms['lang']['backend']['sublevel'] = /* BABELFISH */ "دون المستوى";
$ccms['lang']['backend']['active'] = /* BABELFISH */ "أحدث";
$ccms['lang']['backend']['disabled'] = /* BABELFISH */ "تعطيل";
$ccms['lang']['backend']['template'] = /* BABELFISH */ "قالب";
$ccms['lang']['backend']['notinmenu'] = /* BABELFISH */ "لا عنصر في القائمة";
$ccms['lang']['backend']['menutitle'] = /* BABELFISH */ "القائمة";
$ccms['lang']['backend']['linktitle'] = /* BABELFISH */ "الارتباط";
$ccms['lang']['backend']['item'] = /* BABELFISH */ "البند";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "لا شيء";
$ccms['lang']['backend']['yes'] = /* BABELFISH */ "نعم";
$ccms['lang']['backend']['no'] = /* BABELFISH */ "لا";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "ترجمة";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "مرحبا";
$ccms['lang']['backend']['logout'] = /* BABELFISH */ "سجل التدريجي";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "انظر المنتدى";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>استخدام اسم المستخدم وكلمة المرور صالحة للوصول إلى الجزء الخلفي نهاية CompactCMS. إذا وصلت إلى هنا عن طريق الخطأ ، والعودة إلى <a href='admin/includes/'>الصفحة الرئيسية</a>.</p> <p>اتصل بمشرف الموقع للحصول على التفاصيل الخاصة بك.</p>";
$ccms['lang']['login']['username'] = /* BABELFISH */ "اسم المستخدم";
$ccms['lang']['login']['password'] = /* BABELFISH */ "كلمة السر";
$ccms['lang']['login']['login'] = /* BABELFISH */ "تسجيل الدخول";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "يرجى تقديم أوراق اعتماد المستخدم الخاص بك";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "أدخل كلا من اسم المستخدم وكلمة السر";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "أدخل اسم المستخدم الخاص بك";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "أدخل كلمة السر الخاصة بك";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "وقد كان هذا الحساب المعطلة";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "ملاحظة التي قمت بها بالفعل عدة محاولات";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "اسم المستخدم أو كلمة السر غير صحيحة";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = /* BABELFISH */ "الرئيسية";
$ccms['lang']['menu']['2'] = /* BABELFISH */ "اليسار";
$ccms['lang']['menu']['3'] = /* BABELFISH */ "الحق";
$ccms['lang']['menu']['4'] = /* BABELFISH */ "تذييل";
$ccms['lang']['menu']['5'] = /* BABELFISH */ "إضافية";

// Administration form related texts
$ccms['lang']['forms']['filename'] = /* BABELFISH */ "اسم الملف";
$ccms['lang']['forms']['pagetitle'] = /* BABELFISH */ "صفحة العنوان";
$ccms['lang']['forms']['subheader'] = /* BABELFISH */ "الفرعي الخاص";
$ccms['lang']['forms']['description'] = /* BABELFISH */ "الوصف";
$ccms['lang']['forms']['module'] = /* BABELFISH */ "وحدة";
$ccms['lang']['forms']['contentitem'] = /* BABELFISH */ "البند المحتوى (الافتراضي)";
$ccms['lang']['forms']['additions'] = /* BABELFISH */ "الإضافات";
$ccms['lang']['forms']['printable'] = /* BABELFISH */ "للطباعة";
$ccms['lang']['forms']['published'] = /* BABELFISH */ "أحدث";
$ccms['lang']['forms']['iscoding'] = /* BABELFISH */ "الترميز";
$ccms['lang']['forms']['createbutton'] = /* BABELFISH */ "خلق!";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "تعديل";
$ccms['lang']['forms']['savebutton'] = /* BABELFISH */ "حفظ";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "أمامي اللغة";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "الآن نحن فقط عرض الصفحات التي لديك على الأقل في هذا النص هنا";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "تحرير أو إزالة تصفية";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "إضافة تصفية";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = /* BABELFISH */ "عنوان الصفحة (home.html) :: اسم الملف الذي يسمى على هذه الصفحة (بدون أتش تي أم أل).";
$ccms['lang']['hints']['pagetitle'] = /* BABELFISH */ "عنوان هذه الصفحة (البداية) :: عنوان قصير وصفي لهذه الصفحة.";
$ccms['lang']['hints']['subheader'] = /* BABELFISH */ "نص قصير رأس (مرحبا بكم في موقعنا) :: نص وصفي قصيرة المستخدمة في رأس كل صفحة وكذلك في عنوان كل صفحة.";
$ccms['lang']['hints']['description'] = /* BABELFISH */ "ميتا الوصف :: وصفا فريدا لهذه الصفحة التي سيتم استخدامها كما وصف ميتا في صفحات.";
$ccms['lang']['hints']['module'] = /* BABELFISH */ "الوحدة :: وحدة حدد ما يجب التعامل مع المحتوى لهذا الملف. إذا كنت غير متأكد ، حدد الافتراضي.";
$ccms['lang']['hints']['printable'] = /* BABELFISH */ "الصفحة القابليه للنشر :: عندما يتم إنشاء تحديد 'نعم' صفحة للطباعة. 'لا' يجب أن يكون محددا لصفحات مع صور أو وسائل الإعلام الأخرى. هذه هي أفضل حالا من دون طباعة الصفحة.";
$ccms['lang']['hints']['published'] = /* BABELFISH */ "نشرت  الوضعية :: اختر ما إذا كان ينبغي نشر هذه الصفحة ، إذا عليك أن تكون مدرجة  في خريطة الموقع ، وما إذا كانت سوف تكون في متناول الجمهور.";
$ccms['lang']['hints']['toplevel'] = /* BABELFISH */ "المستوى الأعلى :: تحديد المستوى الأعلى لهذا البند من القائمة.";
$ccms['lang']['hints']['sublevel'] = /* BABELFISH */ "دون المستوى :: 0 عندما حدد هذا البند يجب أن يكون عنصر مستوى أعلى. إذا كان البند الفرعي لأعلى مستوى معين ، يرجى اختيار المستوى دون مناسبة.";
$ccms['lang']['hints']['template'] = /* BABELFISH */ "قالب :: إذا كنت تستخدم قوالب متعددة للتثبيت ، يمكنك تعيين قوالب منفصلة لكل صفحة الفردية باستخدام هذه القائمة المنسدلة.";
$ccms['lang']['hints']['activelink'] = /* BABELFISH */ "أحدث حلقة في القائمة؟ :: ليست كل البنود بحاجة دائما إلى استخدام الارتباط الفعلي. تنشيط الارتباط وراء هذا البند في القائمة الأمامية ، إلى ما دون إزالة علامة الاختيار.";
$ccms['lang']['hints']['menuid'] = /* BABELFISH */ "القائمة :: اختيار القائمة التي ينبغي أن تدرج هذا البند فيها الافتراضي القائمة  الرئيسية (1) ، حيث ينبغي أن تظهر أيضا على ارتباط الصفحة الرئيسية.";
$ccms['lang']['hints']['iscoding'] = /* BABELFISH */ "يحتوي الترميز :: هل هذا الملف يحتوي على دليل وأضاف رمز مثل بي أو جافا سكريبت؟ اختيار 'نعم' سوف تقييد الوصول إلى الملف من محرر سوغ في الخلفية وتمكن محرر رمز.";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br><br>يمكنك النقر على <span class='sprite livefilter livefilter_active'> أيقونة تصفية على يسار العنوان لإضافة ، تحرير أو إزالة النص لتصفية قائمة على الصفحة ، على سبيل المثال عندما كنت اكتب 'الرئيسية في حقل التحرير الذي يظهر عند النقر فوق رمز ، ثم اضغط على أدخل / صفحات الوحيد الذي قد نص 'هذا العمود الوطن' في سيظهر العودة الرئيسية. <br>بالنقر على أيقونة مرة أخرى ، وحذف النص في الحقل تحرير ، ثم الضغط على أدخل / مفتاح العودة ، وإزالة عامل التصفية.<br>تحوم فوق رمز مرشح لمعرفة ما إذا كان حاليا العمود يجري تصفيتها ، وإذا كان الأمر كذلك ، وذلك باستخدام النص الذي التصفية.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = /* BABELFISH */ "أغلق محرر";
$ccms['lang']['editor']['editorfor'] = /* BABELFISH */ "نص محرر ل";
$ccms['lang']['editor']['instruction'] = /* BABELFISH */ "استخدام محرر أدناه لتعديل الملف الحالي. ضرب بمجرد الانتهاء من ذلك ، 'حفظ التغييرات' في الأسفل لنشر التعديلات مباشرة على الويب في جميع أنحاء العالم. إضافة أيضا إلى عشر كلمات دالة ذات الصلة من أجل التشغيل الأمثل محرك البحث.";
$ccms['lang']['editor']['savebtn'] = /* BABELFISH */ "حفظ التغييرات";
$ccms['lang']['editor']['cancelbtn'] = /* BABELFISH */ "إلغاء";
$ccms['lang']['editor']['confirmclose'] = /* BABELFISH */ "إغلاق هذه النافذة وتجاهل أي تغييرات؟";
$ccms['lang']['editor']['preview'] = /* BABELFISH */ "معاينة صفحة النتائج";
$ccms['lang']['editor']['savesuccess'] = /* BABELFISH */ "<strong>النجاح!</strong> المحتوى كما هو مبين أدناه تم حفظ ل";
$ccms['lang']['editor']['backeditor'] = /* BABELFISH */ "عودة إلى المحرر";
$ccms['lang']['editor']['closewindow'] = /* BABELFISH */ "أغلق النافذة";
$ccms['lang']['editor']['keywords'] = /* BABELFISH */ "كلمات -- <em>مفصولة بفواصل ، ماكس 250 حرفا</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "السيارات إنشاء كلمة مرور آمنة";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "مستوى حسابك الحالي لا يسمح لك لاستخدام هذه الميزة.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = /* BABELFISH */ "إنشاء جديد احتياطية";
$ccms['lang']['backup']['explain'] = /* BABELFISH */ "لمنع احتمال فقدان البيانات بسبب ما حدث خارجي ، فمن الحكمة لإنشاء النسخ الاحتياطية من الملفات الخاصة وبشكل منتظم.";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "تحذير :: يرجى العلم بأن ما تتمتعون به <dfn>الضوء</dfn> 'البومات الصور و <strong>ليس</strong> احتياطيا الأوصاف! الألبوم <strong>هي</strong>، ولكن الصور والصور المصغرة هم أنفسهم <strong>لم تدرج في هذه النسخ الاحتياطية</strong>مع.  إذا كنت تريد نسخ احتياطية من تلك ، فإنك سوف تحتاج إلى منح موقع بمسؤول  إضافية حول نظام النسخ الاحتياطي لمساعدتك على النسخ الاحتياطي واستعادة  هذه الملفات الكبيرة) مجموعات ربما (.";
$ccms['lang']['backup']['currenthd'] = /* BABELFISH */ "تتوفر النسخ الاحتياطية";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "احتياطية اسم الملف";
$ccms['lang']['backup']['download'] = /* BABELFISH */ "تنزيل الأرشيف";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "الرجاء الانتظار بينما يتم إنشاء النسخة الاحتياطية...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "إنشاء مستخدم";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "نظرة عامة المستخدمين آلية التنسيق القطرية";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "تحرير تفاصيل المستخدم الشخصية";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "تحرير المستخدم كلمة السر";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "إعدادات الحساب";
$ccms['lang']['users']['user'] = /* BABELFISH */ "المستخدم";
$ccms['lang']['users']['username'] = /* BABELFISH */ "اسم المستخدم";
$ccms['lang']['users']['name'] = /* BABELFISH */ "اسم";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "الاسم الأول";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "اسم آخر";
$ccms['lang']['users']['password'] = /* BABELFISH */ "كلمة السر";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "تأكيد كلمة المرور";
$ccms['lang']['users']['email'] = /* BABELFISH */ "البريد الإلكتروني";
$ccms['lang']['users']['active'] = /* BABELFISH */ "أحدث";
$ccms['lang']['users']['level'] = /* BABELFISH */ "المستوى";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "مستوى المستخدم";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "سجل بالموقع";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "إدارة قوالب";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "القالب الحالي هو <strong>ليس</strong> قابل للكتابة";
$ccms['lang']['template']['print'] = /* BABELFISH */ "طباعة";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "إذن تفضيلات";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "استخدم الجدول أدناه لتحديد ما أدنى مستوى يمكن للمستخدم استخدام ميزات معينة. أي مستخدم دون الحد الأدنى المحدد مستوى المستخدم اقتضى الأمر ، لا يرى ولا يستطيعون الوصول إلى هذه الميزة.";
$ccms['lang']['permission']['target'] = /* BABELFISH */ "الهدف";
$ccms['lang']['permission']['level1'] = /* BABELFISH */ "المستوى 1 -- استكشاف";
$ccms['lang']['permission']['level2'] = /* BABELFISH */ "المستوى 2 -- محرر";
$ccms['lang']['permission']['level3'] = /* BABELFISH */ "المستوى 3 -- مدير";
$ccms['lang']['permission']['level4'] = /* BABELFISH */ "المستوى 4 -- المشرف";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "محتوى أصحاب";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "هنا يمكنك تعيين صفحة معينة ملكية إلى المستخدمين الفرديين. إذا لصفحة معينة يتم تحديد أي المستخدمين ، يمكن لأي شخص تعديل الصفحة. كان على خلاف ذلك فقط للمستخدم المحدد حقوق التعديل على هذا الملف. مديري دائما الوصول إلى كافة الملفات.";
$ccms['lang']['owners']['pages'] = /* BABELFISH */ "صفحات";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "المستخدمين";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "ترجمة";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "ويبين سلاسل الترجمة.";

// Album messages
$ccms['lang']['album']['album'] = /* BABELFISH */ "الألبوم";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "إعدادات الألبوم";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "تنطبق على وجه التحديد لهذا الألبوم";
$ccms['lang']['album']['description'] = /* BABELFISH */ "وصف الألبوم";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "البومات الحالي";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "تحميل المحتوى";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "تحميل القائمة البوم";
$ccms['lang']['album']['upload'] = /* BABELFISH */ "بدء تحميل";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "استعراض الملفات";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "قائمة واضحة";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>واحد تحميل الملف</strong><br> <p>فشل تهيئة محمل فلاش. تأكد من تمكين جافا سكريبت وتثبيت فلاش. تحميل ملف واحد أمر ممكن ، ولكن ليس الأمثل.</p>";
$ccms['lang']['album']['manage'] = /* BABELFISH */ "إدارة الألبوم";
$ccms['lang']['album']['albumlist'] = /* BABELFISH */ "قائمة الالبومات";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "تجديد جميع الصور المصغرة";
$ccms['lang']['album']['newalbum'] = /* BABELFISH */ "إنشاء ألبوم جديد";
$ccms['lang']['album']['noalbums'] = /* BABELFISH */ "لم تنشأ أي ألبومات حتى الآن!";
$ccms['lang']['album']['files'] = /* BABELFISH */ "الملفات";
$ccms['lang']['album']['nodir'] = /* BABELFISH */ "الرجاء التأكد من الدليل <strong>البومات</strong> موجود في. / الإعلام / الدليل";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "آخر تعديل";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "الرجاء الانتظار...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "إدارة الأخبار الحالية";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "إضافة الأخبار";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "كتابة مادة جديدة";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "إدارة إعدادات";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "كتابة الأخبار";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "# الرسائل على الواجهة الأمامية";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "عرض المؤلف";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "إظهار تاريخ النشر";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "تظهر فقط دعابة";
$ccms['lang']['news']['title'] = /* BABELFISH */ "عنوان الخبر";
$ccms['lang']['news']['author'] = /* BABELFISH */ "أخبار الكاتب";
$ccms['lang']['news']['date'] = /* BABELFISH */ "تاريخ";
$ccms['lang']['news']['published'] = /* BABELFISH */ "نشرت؟";
$ccms['lang']['news']['teaser'] = /* BABELFISH */ "دعابة";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "محتويات المادة";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "عرض الأرشيف";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = /* BABELFISH */ "لم يتم نشر أية ردود فعل حتى الآن!";
$ccms['lang']['guestbook']['verinstr'] = /* BABELFISH */ "للتأكد من أن ليس الآلي هذه الرسالة ، الرجاء إعادة إدخال";
$ccms['lang']['guestbook']['reaction'] = /* BABELFISH */ "رد فعل";
$ccms['lang']['guestbook']['rating'] = /* BABELFISH */ "التصويت";
$ccms['lang']['guestbook']['avatar'] = /* BABELFISH */ "Gravatar.com العضو";
$ccms['lang']['guestbook']['wrote'] = /* BABELFISH */ "كتب";
$ccms['lang']['guestbook']['manage'] = /* BABELFISH */ "إدارة ردود الفعل";
$ccms['lang']['guestbook']['delentry'] = /* BABELFISH */ "حذف هذا البند";
$ccms['lang']['guestbook']['sendmail'] = /* BABELFISH */ "البريد الإلكتروني الكاتب";
$ccms['lang']['guestbook']['removed'] = /* BABELFISH */ "تمت إزالة من قاعدة البيانات.";
$ccms['lang']['guestbook']['name'] = /* BABELFISH */ "اسمك";
$ccms['lang']['guestbook']['email'] = /* BABELFISH */ "البريد الإلكتروني";
$ccms['lang']['guestbook']['website'] = /* BABELFISH */ "موقع الويب الخاص بك";
$ccms['lang']['guestbook']['comments'] = /* BABELFISH */ "تعليقات";
$ccms['lang']['guestbook']['verify'] = /* BABELFISH */ "التحقق";
$ccms['lang']['guestbook']['preview'] = /* BABELFISH */ "تعليق معاينة";
$ccms['lang']['guestbook']['add'] = /* BABELFISH */ "أضف تعليقاتك";
$ccms['lang']['guestbook']['posted'] = /* BABELFISH */ "وقد تم نشر التعليق الخاص بك!";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "شكرا";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "الفشل والرفض";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "تم رفض التعليق.";


      /* ### OBSOLETED ENTRIES ### */
      /*
         Please check the CompactCMS code to:

         a) make sure whether these entries are indeed obsoleted.
            When yes, then the corresponding entry above should be
            removed as well!

         b) When no, i.e. the entry exists in the code, this merits
            a bug report regarding the ./collect_lang_items.sh script.
       
         ----------------------------------------------------------
	
	$ccms['lang']['album']['nodir'] 
	$ccms['lang']['backend']['fileexists'] 
	$ccms['lang']['backend']['startedittitle'] 
	$ccms['lang']['backend']['updatelist'] 
	$ccms['lang']['editor']['closeeditor'] 
	$ccms['lang']['guestbook']['delentry'] 
	$ccms['lang']['guestbook']['removed'] 
	$ccms['lang']['login']['falsetries'] 
	$ccms['lang']['login']['provide'] 
	$ccms['lang']['system']['error_default'] 
	$ccms['lang']['system']['error_sitemap'] 
       
         ----------------------------------------------------------
	
         ### MISSING ENTRIES ###

         The entries below have been found to be missing from this 
         translation file; move them from this comment section to the
         PHP code above and assign them a suitable text.

         When done so, you can of course remove them from the list 
         below.
       
         ----------------------------------------------------------
      */
	  
$ccms['lang']['album']['assigned_page']         = "Assigned to page";
$ccms['lang']['backend']['floatingtitle']       = "Other";
$ccms['lang']['backend']['install_dir_exists']  = "Be aware that the <em>./_install/</em> directory still exists; this is a security hazard of the first degree! Please remove the <em>_install</em> directory immediately!";
$ccms['lang']['backend']['js_loading']          = "The browser is still loading several files from the server; please refrain from any activity while this initial loading process completes, thank you.";
$ccms['lang']['backend']['reload_admin_screen'] = "Refresh the admininistration screen";
$ccms['lang']['backend']['rename_file']         = "Rename file";
$ccms['lang']['backend']['reordermenu_done']    = "The pages have been assigned new (renumbered) menu positions";
$ccms['lang']['backend']['warning']             = "Warning";
$ccms['lang']['forms']['overwrite_imgs']        = "Overwrite existing files";
$ccms['lang']['forms']['reorderbutton']         = "Re-order";
$ccms['lang']['guestbook']['author']            = "Author";
$ccms['lang']['guestbook']['configuration']     = "Configuration";
$ccms['lang']['guestbook']['date']              = "Date";
$ccms['lang']['guestbook']['host']              = "IP address";
$ccms['lang']['hints']['reordercmdhelp']        = "Renumber all menu entries to ensure both each menu entry has a unique top+sublevel position and the positions are sequential";
$ccms['lang']['permitem']['manageMenu']         = "From what user level on can users manage menu preferences";
$ccms['lang']['permitem']['manageModBackup']    = "From what user level on can users delete current back-up files";
$ccms['lang']['permitem']['manageModComment']   = "The level of a user that is allowed to manage comments";
$ccms['lang']['permitem']['manageModLightbox']  = "From what user level on can users manage albums throught the lightbox module (add, modify, delete)";
$ccms['lang']['permitem']['manageModNews']      = "From what user level on can users manage news items through the news module (add, modify, delete)";
$ccms['lang']['permitem']['manageModTranslate'] = "The level of a user that is allowed to use the (experimental) translation support utility";
$ccms['lang']['permitem']['manageModules']      = "From what user level on can users manage modules";
$ccms['lang']['permitem']['manageOwners']       = "To allow to appoint certain users to a specific page";
$ccms['lang']['permitem']['managePageActivation']     = "From what user level on can users manage the activeness of pages (published vs. unpublished)";
$ccms['lang']['permitem']['managePageCoding']    = "From what user level on can users set whether a page contains coding (wysiwyg vs. code editor)";
$ccms['lang']['permitem']['managePageEditing']  = "From what user level on can users edit pages (content, title, subheader, keywords, etc.)";
$ccms['lang']['permitem']['managePages']        = "From what user level on can users manage pages (add, delete)";
$ccms['lang']['permitem']['manageTemplate']     = "From what user level on can users manage and edit all of the available templates";
$ccms['lang']['permitem']['manageUsers']        = "From what user level on can users manage user accounts (add, modify, delete)";
$ccms['lang']['system']['error_forged']         = "You attempted to access site areas for which you are not authorized";
$ccms['lang']['system']['error_rec_exists']     = "Entry already exists in the database";
$ccms['lang']['system']['error_rename']         = "Failed to rename the file";
$ccms['lang']['system']['error_rename_target_exists'] = "Cannot rename the file as a file with the new name already exists";
$ccms['lang']['system']['error_session_expired'] = "Your session has expired or you are not logged in";
$ccms['lang']['system']['home']                 = "Home";
       
      /*
         ----------------------------------------------------------
      */
	  
?>
