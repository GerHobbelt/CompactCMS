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
$ccms['lang']['system']['error_database'] = /* BABELFISH */ "veritabanına bağlanamadı. giriş bilgilerini ve veritabanı adını kontrol edin.";
$ccms['lang']['system']['error_openfile'] = /* BABELFISH */ "Belirtilen dosya açılamıyor";
$ccms['lang']['system']['error_notemplate'] = /* BABELFISH */ "Hayır sitenize uygulanacak bulunamadı şablonları. en az bir şablonu ekleyin. / lib / templates /.";
$ccms['lang']['system']['error_templatedir'] = /* BABELFISH */ "dizin şablonları bulunamadı! Yapmak o var ve en az bir şablonu içerir. Emin";
$ccms['lang']['system']['error_write'] = /* BABELFISH */ "Dosyanın bir yazma erişimine sahip olduğunu";
$ccms['lang']['system']['error_dirwrite'] = /* BABELFISH */ "Dizinin hiçbir yazma erişimine sahip olduğunu";
$ccms['lang']['system']['error_chmod'] = /* BABELFISH */ "Geçerli dosya modifiye olamazdı. / içerik dizininde (666) içinde dosyaların izinlerini denetleyin.";
$ccms['lang']['system']['error_value'] = /* BABELFISH */ "Hatası: Yanlış değer";
$ccms['lang']['system']['error_default'] = /* BABELFISH */ "Varsayılan sayfa silinemez.";
$ccms['lang']['system']['error_forged'] = /* BABELFISH */ "Değeri Kurcalanan";
$ccms['lang']['system']['error_filedots'] = /* BABELFISH */ "Dosya adı, örneğin nokta içermemelidir. Html.";
$ccms['lang']['system']['error_filesize'] = /* BABELFISH */ "Dosya adı en az 3 karakter uzunluğunda olmalıdır.";
$ccms['lang']['system']['error_filesize_2'] = /* BABELFISH */ "Dosya adı en fazla 50 karakter uzunluğunda olmalıdır.";
$ccms['lang']['system']['error_pagetitle'] = /* BABELFISH */ "3 veya daha fazla karakterli bir sayfa başlığını girin.";
$ccms['lang']['system']['error_pagetitle_2'] = /* BABELFISH */ "100 karakter veya daha az bir sayfa başlığını girin.";
$ccms['lang']['system']['error_subtitle'] = /* BABELFISH */ "sayfanız için kısa bir alt başlık verin.";
$ccms['lang']['system']['error_subtitle_2'] = /* BABELFISH */ "200 karakter veya sayfa için daha kısa bir alt başlık girin.";
$ccms['lang']['system']['error_description'] = /* BABELFISH */ "3 harften fazla bir açıklama girin";
$ccms['lang']['system']['error_description_2'] = /* BABELFISH */ "az 250 karakterden oluşan bir açıklama girin";
$ccms['lang']['system']['error_reserved'] = /* BABELFISH */ "Eğer içi kullanım için ayrılmış bir dosya adı belirttiğiniz.";
$ccms['lang']['system']['error_general'] = /* BABELFISH */ "Hata oluştu";
$ccms['lang']['system']['error_correct'] = /* BABELFISH */ "Aşağıdaki düzeltin:";
$ccms['lang']['system']['error_create'] = /* BABELFISH */ "Hata yeni dosya oluşturma tamamlarken";
$ccms['lang']['system']['error_exists'] = /* BABELFISH */ "Dosya adı zaten var belirtildi.";
$ccms['lang']['system']['error_delete'] = /* BABELFISH */ "Hata Seçilen dosyanın silinmesi tamamlarken";
$ccms['lang']['system']['error_selection'] = /* BABELFISH */ "Seçilen öğe yok edildi.";
$ccms['lang']['system']['error_versioninfo'] = /* BABELFISH */ "Bir sürüm bilgisi mevcuttur.";
$ccms['lang']['system']['error_misconfig'] = /* BABELFISH */ "<strong>bir yapılandırma hatası var gibi gözüküyor.</strong><br>. htaccess dosyasını doğru şekilde dosya yapısını yansıtacak şekilde yapılandırıldığından emin olun. Eğer<br>bir alt kurulu CompactCMS, daha sonra ayarlayabilirsiniz. htaccess dosyasına göre.";
$ccms['lang']['system']['error_deleted'] = /* BABELFISH */ "<h1>Seçtiğiniz dosya silinmiş gibi görünüyor</h1> <p>Yenileme Filelist oluyor bu hatayı önlemek için mevcut dosyaların en son listesini görebilirsiniz. Bu bu hata çözmezse, el açmaya çalıştığınız dosyanın içeriğini klasörü kontrol edin.</p>";
$ccms['lang']['system']['error_404title'] = /* BABELFISH */ "Dosya bulunamadı";
$ccms['lang']['system']['error_404header'] = /* BABELFISH */ "A 404 hatası istenen dosya bulunamadı oluştu.";
$ccms['lang']['system']['error_404content'] = /* BABELFISH */ "İstenen dosya <strong>{%pagereq%}.html</strong> bulunamadı.";
$ccms['lang']['system']['error_403title'] = /* BABELFISH */ "Yasak";
$ccms['lang']['system']['error_403header'] = /* BABELFISH */ "A 403 hata oluştu: Eğer istenen dosya erişim izni yok.";
$ccms['lang']['system']['error_403content'] = /* BABELFISH */ "Erişim izniniz yok <strong>{%pagereq%}.html</strong> Şu anda bu.";
$ccms['lang']['system']['error_sitemap'] = /* BABELFISH */ "tüm sayfaların genel bir bakış";
$ccms['lang']['system']['error_tooshort'] = /* BABELFISH */ "Bir veya birden fazla gönderilen değerler ya çok kısa ya da yanlış edildi";
$ccms['lang']['system']['error_passshort'] = /* BABELFISH */ "Bir şifre fazla 6 karakter içermelidir";
$ccms['lang']['system']['error_passnequal'] = /* BABELFISH */ "Girilen şifreler uyuşmuyor";
$ccms['lang']['system']['noresults'] = /* BABELFISH */ "Hiçbir sonuç";
$ccms['lang']['system']['tooriginal'] = /* BABELFISH */ "Geri orijinaline";
$ccms['lang']['system']['message_rights'] = /* BABELFISH */ "Tüm hakları saklıdır";
$ccms['lang']['system']['message_compatible'] = /* BABELFISH */ "Başarıyla denendi";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = /* BABELFISH */ "Var öneri, yorum ya da sorun yaşıyorsanız? Ziyaret <a class='external' title='Visit the official forum' rel='external' href='http://community.compactcms.nl/forum/'>forum</a>!";
$ccms['lang']['backend']['ordertip'] = /* BABELFISH */ "sitelerinizi 'menüsünde sayfalarınızın yapısını yansıtacak şekilde açılan çıkışlar aşağıdaki bağlantıyı kullanın. sistem üst ya da alt düzey kombinasyonları yinelenen dikkate almaz unutmayın.";
$ccms['lang']['backend']['createtip'] = /* BABELFISH */ "Yeni bir sayfa oluşturmak için, aşağıdaki formu doldurunuz yeni bir sayfa anında sizin için oluşturulur. dosya oluşturulduktan sonra, her zamanki gibi sayfa düzenleme yapabileceksiniz.";
$ccms['lang']['backend']['currentfiles'] = /* BABELFISH */ "listeleme ise şu anda yayınlanan tüm sayfaları bulacaksınız aşağıda. Bu web sitenizin mevcut ana olduğunu, çünkü dosya varsayılan sayfa silinemez fark edeceksiniz. yönetici, bu dosyalar üzerinde tek sahibi olduğundan Diğer dosyalar kısıtlı erişim olabilir.";
$ccms['lang']['backend']['confirmdelete'] = /* BABELFISH */ "Seçili öğe (ler) silmek istediğinizi onaylayın.";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "tüm küçük yeniden istediğinizi onaylayın.";
$ccms['lang']['backend']['settingssaved'] = /* BABELFISH */ "Yaptığınız değişiklikleri başarıyla kaydedildi.";
$ccms['lang']['backend']['must_refresh'] = /* BABELFISH */ "görmek için sayfa ana emin için yeniden olun <strong>tüm</strong> değişiklikleri";
$ccms['lang']['backend']['itemcreated'] = /* BABELFISH */ "Başarıyla teslim kalem (ler) işlenmiş.";
$ccms['lang']['backend']['fullremoved'] = /* BABELFISH */ "Başarıyla seçilmiş kalem (ler) silindi.";
$ccms['lang']['backend']['fullregenerated'] = /* BABELFISH */ "Başarılı bir küçük rejenere.";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "Geri dön";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "Set CCMS izinleri";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "içerik sahipleri tanımlama";
$ccms['lang']['backend']['templateeditor'] = /* BABELFISH */ "Şablon editörü";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "Kullanıcı yönetimi";
$ccms['lang']['backend']['changevalue'] = /* BABELFISH */ "değiştirmek için tıklayın";
$ccms['lang']['backend']['previewpage'] = /* BABELFISH */ "Önizleme";
$ccms['lang']['backend']['editpage'] = /* BABELFISH */ "Düzenlemek";
$ccms['lang']['backend']['restrictpage'] = /* BABELFISH */ "Sınırlı";
$ccms['lang']['backend']['newfiledone'] = /* BABELFISH */ "Bu dosyayı doldurmak için-up için taze ve temiz!";
$ccms['lang']['backend']['newfilecreated'] = /* BABELFISH */ "Dosya oluşturulduktan";
$ccms['lang']['backend']['startedittitle'] = /* BABELFISH */ "Başlangıç düzenleme!";
$ccms['lang']['backend']['starteditbody'] = /* BABELFISH */ "Yeni dosya yaratıldı. Başlangıç düzenleme hemen ya da ya daha fazla sayfa eklemek veya mevcut olanları yönetebilirsiniz.";
$ccms['lang']['backend']['success'] = /* BABELFISH */ "Başarı!";
$ccms['lang']['backend']['fileexists'] = /* BABELFISH */ "Dosya var";
$ccms['lang']['backend']['statusdelete'] = /* BABELFISH */ "Seçilen silinmesi Durum:";
$ccms['lang']['backend']['statusremoved'] = /* BABELFISH */ "kaldırıldı";
$ccms['lang']['backend']['uptodate'] = /* BABELFISH */ "güncel.";
$ccms['lang']['backend']['outofdate'] = /* BABELFISH */ "modası geçmiş.";
$ccms['lang']['backend']['considerupdate'] = /* BABELFISH */ "güncellenmesi düşünün";
$ccms['lang']['backend']['orderprefsaved'] = /* BABELFISH */ "menü öğeleri sipariş için Tercihleriniz kaydedildi.";
$ccms['lang']['backend']['inmenu'] = /* BABELFISH */ "menüsünde";
$ccms['lang']['backend']['updatelist'] = /* BABELFISH */ "Güncelleme dosyasını listesi";
$ccms['lang']['backend']['administration'] = /* BABELFISH */ "Yönetim";
$ccms['lang']['backend']['currentversion'] = /* BABELFISH */ "Şu anda sürümünü çalıştırıyorsanız";
$ccms['lang']['backend']['mostrecent'] = /* BABELFISH */ "En son kararlı CompactCMS versiyonu";
$ccms['lang']['backend']['versionstatus'] = /* BABELFISH */ "Yükleme olduğunu";
$ccms['lang']['backend']['createpage'] = /* BABELFISH */ "Yeni sayfa oluşturma";
$ccms['lang']['backend']['managemenu'] = /* BABELFISH */ "Yönet menüsünde";
$ccms['lang']['backend']['managefiles'] = /* BABELFISH */ "mevcut sayfalarınızın Yönet";
$ccms['lang']['backend']['delete'] = /* BABELFISH */ "Silmek";
$ccms['lang']['backend']['toplevel'] = /* BABELFISH */ "Üst düzey";
$ccms['lang']['backend']['sublevel'] = /* BABELFISH */ "Alt düzey";
$ccms['lang']['backend']['active'] = /* BABELFISH */ "Aktif";
$ccms['lang']['backend']['disabled'] = /* BABELFISH */ "Özürlü";
$ccms['lang']['backend']['template'] = /* BABELFISH */ "Şablon";
$ccms['lang']['backend']['notinmenu'] = /* BABELFISH */ "Öğesi olmayan bir menü";
$ccms['lang']['backend']['menutitle'] = /* BABELFISH */ "Menü";
$ccms['lang']['backend']['linktitle'] = /* BABELFISH */ "Bağlantı";
$ccms['lang']['backend']['item'] = /* BABELFISH */ "Madde";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "Hiçbiri";
$ccms['lang']['backend']['yes'] = /* BABELFISH */ "Evet";
$ccms['lang']['backend']['no'] = /* BABELFISH */ "Yok";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "Çeviriler";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "Merhaba";
$ccms['lang']['backend']['logout'] = "Log-out";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "forum bak";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>sonuna kadar kullanın geçerli bir kullanıcı adı ve geri CompactCMS şifre erişim elde etmek. Eğer dönmek, sen geldi yanlışlıkla burada <a href='admin/includes/'>ana sayfası</a>.</p> <p>Lütfen ayrıntılar için webmaster başvurun.</p>";
$ccms['lang']['login']['username'] = /* BABELFISH */ "Kullanıcı adı";
$ccms['lang']['login']['password'] = /* BABELFISH */ "Şifre";
$ccms['lang']['login']['login'] = /* BABELFISH */ "Giriş";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "kullanıcı kimlik bilgileri veriniz";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "hem kullanıcı adınızı ve şifrenizi girin";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "Kullanıcı adınızı girin";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "şifrenizi girin";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "Bu hesabı devre dışı bırakıldı";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "Zaten birden fazla girişimde bulundu unutmayın";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "Yanlış kullanıcı adı veya şifre";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = /* BABELFISH */ "Ana";
$ccms['lang']['menu']['2'] = /* BABELFISH */ "Sol";
$ccms['lang']['menu']['3'] = /* BABELFISH */ "Sağ";
$ccms['lang']['menu']['4'] = "Footer";
$ccms['lang']['menu']['5'] = /* BABELFISH */ "İlave";

// Administration form related texts
$ccms['lang']['forms']['filename'] = /* BABELFISH */ "Dosya adı";
$ccms['lang']['forms']['pagetitle'] = /* BABELFISH */ "Sayfa başlığı";
$ccms['lang']['forms']['subheader'] = /* BABELFISH */ "Alt başlığına";
$ccms['lang']['forms']['description'] = /* BABELFISH */ "Açıklama";
$ccms['lang']['forms']['module'] = /* BABELFISH */ "Modül";
$ccms['lang']['forms']['contentitem'] = /* BABELFISH */ "Içerik öğesi (varsayılan)";
$ccms['lang']['forms']['additions'] = /* BABELFISH */ "Yüklenenler";
$ccms['lang']['forms']['printable'] = /* BABELFISH */ "Basılabilir";
$ccms['lang']['forms']['published'] = /* BABELFISH */ "Aktif";
$ccms['lang']['forms']['iscoding'] = /* BABELFISH */ "Kodlama";
$ccms['lang']['forms']['createbutton'] = /* BABELFISH */ "Oluştur!";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "Değiştirmek";
$ccms['lang']['forms']['savebutton'] = /* BABELFISH */ "Kaydetmek";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "Ön uç dil";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "Şu anda biz sadece en azından bu metin burada var sayfalarını gösteren konum";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "Düzenlemek veya filtreyi kaldırmak";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "için filtre ekle";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = /* BABELFISH */ "Sayfa url (home.html): Bu sayfada talebinde bulunulur dosya adı (olmadan html.)";
$ccms['lang']['hints']['pagetitle'] = /* BABELFISH */ "Bu sayfa için Başlık (Ev): Bu sayfada kısa bir açıklayıcı bir başlık.";
$ccms['lang']['hints']['subheader'] = /* BABELFISH */ "Kısa başlık metni (Sitemizin için): Her sayfanın başlığı sıra her sayfanın başlığında kullanılan kısa bir açıklayıcı metin.";
$ccms['lang']['hints']['description'] = /* BABELFISH */ "Meta açıklaması: sayfalarınızın meta açıklama olarak kullanılacak bu sayfa için benzersiz bir açıklama.";
$ccms['lang']['hints']['module'] = /* BABELFISH */ "Modül: ne modül bu dosyanın içeriği ele gerektiğini seçin. Eğer emin değilseniz, varsayılan seçin.";
$ccms['lang']['hints']['printable'] = /* BABELFISH */ "Sayfa printability: Seçilen 'EVET' yazdırılabilir bir sayfa oluşturulur. 'NO' resimleri ya da diğer medya ile sayfaları için seçilmelidir. Bu kapalı bir yazdırılabilir sayfa olmadan daha iyi.";
$ccms['lang']['hints']['published'] = /* BABELFISH */ "Yayınlanan durumu: o site haritası yer olacak ve eğer halka erişilebilir olacak belirtin bu sayfada yayınlanan gerekiyor demektir.";
$ccms['lang']['hints']['toplevel'] = /* BABELFISH */ "Üst düzey: belirtin Bu menü için üst düzeyde.";
$ccms['lang']['hints']['sublevel'] = /* BABELFISH */ "Alt seviye :: Seçim 0 zaman bu maddeyi üst düzey bir öğe olmalıdır. belirli bir üst düzey için bir alt kalemi olan, uygun alt düzeyini seçin lütfen.";
$ccms['lang']['hints']['template'] = /* BABELFISH */ "Şablon: Birden fazla yükleme için şablon kullanıyorsanız, ayrı atayabilir kullanarak her bir sayfa şablonları Bu açılır.";
$ccms['lang']['hints']['activelink'] = /* BABELFISH */ "menüsünde Etkin bağlantı? :: Tüm öğeleri her zaman gerçek bir bağlantı gerekir. Ön uç menüsünde bu öğeyi işaretini kaldırın altında kendi onay arkasındaki bağlantı devre dışı bırakmak için.";
$ccms['lang']['hints']['menuid'] = /* BABELFISH */ "Menü: Hangi menüden bu madde varsayılan içeri listelenmiş olmalıdır seçin da ana sayfasından link gösterilmesi gereken ana menü (1) vardır.";
$ccms['lang']['hints']['iscoding'] = /* BABELFISH */ "kodlama içerir: Bu dosyayı manuel PHP veya Javascript gibi kod parçasını içeriyor mu? Seçerek 'Evet' back-end's WYSIWYG editörü gelen dosyaya erişimi kısıtlamak ve kod editörü sağlar.";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br><br>Eğer tıklayabilirsiniz <span class='sprite livefilter livefilter_active'> filtre simgesini Enter tuşuna basın, sonra da 'ev' in simgesini tıklayın ne zaman alan görüntülenir düzenlemek türü ne zaman eklemek, düzenlemek veya kaldırmak gibi bir listede sayfa metin için filtre başlığı ile sol / home 'Bu sütunu Dönüş' sayfaları sadece metin anahtar gösterilecek. <br>tekrar simgeye tıkladığınızda ve düzenleme alanındaki metni silme, sonra / Return Enter tuşuna basarak, olacak filtreyi kaldırmak.<br>filtre simgesinin üzerine gidin hangi filtresi metin kullanarak ve sütun şu anda filtre olan olup olmadığını, eğer öyleyse görebilirsiniz.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = /* BABELFISH */ "editör Kapat";
$ccms['lang']['editor']['editorfor'] = /* BABELFISH */ "Metin editörü";
$ccms['lang']['editor']['instruction'] = /* BABELFISH */ "editör geçerli dosya değiştirmek için aşağıdaki bağlantıyı kullanın. Bir kez, bitti doğrudan world wide web için değişiklikler yayınlamak için aşağıdaki butonu 'Değişiklikleri Kaydet' tuşuna basın. Ayrıca, arama motoru iyileştirmesi için on alakalı anahtar kelimeler ekleyebilirsiniz.";
$ccms['lang']['editor']['savebtn'] = /* BABELFISH */ "Değişiklikleri kaydet";
$ccms['lang']['editor']['cancelbtn'] = /* BABELFISH */ "Iptal";
$ccms['lang']['editor']['confirmclose'] = /* BABELFISH */ "Bu pencereyi kapatın ve değişiklikleri iptal?";
$ccms['lang']['editor']['preview'] = /* BABELFISH */ "Ön sonuç sayfası";
$ccms['lang']['editor']['savesuccess'] = /* BABELFISH */ "<strong>Başarı!</strong> kaydedilmiş olup, aşağıda gösterildiği gibi içerik";
$ccms['lang']['editor']['backeditor'] = /* BABELFISH */ "Geri editöre";
$ccms['lang']['editor']['closewindow'] = /* BABELFISH */ "Pencereyi kapat";
$ccms['lang']['editor']['keywords'] = /* BABELFISH */ "Anahtar Kelimeler - <em>virgülle ayırarak, max 250 karakter</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "Otomatik güvenli bir parola oluşturmak";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "Mevcut hesap düzeyinde bu özelliği kullanmak için izin vermiyor.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = /* BABELFISH */ "Yaratmak yeni back-up";
$ccms['lang']['backup']['explain'] = /* BABELFISH */ "veri nedeniyle olası kaybını önlemek için her türlü dış olay, düzenli olarak dosya-up geri oluşturmak için akıllıca için.";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "Uyarı: Lütfen unutmayın <dfn>lightbox</dfn> albümü 'görüntüler <strong>değil</strong> yedeklenmiş açıklamalar! albümü <strong>vardır</strong>, ama görüntü kendilerini ve küçük olan <strong>bu yedekleri de dahil</strong>olan bir. yedeklenmesini istiyorsanız Eğer o, o zaman tanımak gerek, onlar size ek yedekleme sistemi bir site yöneticisi hakkında yedekleme size yardım ve koleksiyonları restore bu (muhtemelen büyük) dosyası.";
$ccms['lang']['backup']['currenthd'] = /* BABELFISH */ "back-up Available";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "Yedekleme dosya adı";
$ccms['lang']['backup']['download'] = /* BABELFISH */ "Download arşiv";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "yedek oluşturulmaktadır lütfen bekleyin ...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "bir kullanıcı oluşturun";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "Genel bakış CCMS kullanıcılar";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "kullanıcının kişisel bilgilerini düzenleme";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "kullanıcı şifresi Düzenle";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "Hesap ayarları";
$ccms['lang']['users']['user'] = /* BABELFISH */ "Kullanıcı";
$ccms['lang']['users']['username'] = /* BABELFISH */ "Kullanıcı adı";
$ccms['lang']['users']['name'] = /* BABELFISH */ "Adı";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "Ilk isim";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "Soyadı";
$ccms['lang']['users']['password'] = /* BABELFISH */ "Şifre";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "Onaylamak şifre";
$ccms['lang']['users']['email'] = /* BABELFISH */ "E-posta";
$ccms['lang']['users']['active'] = /* BABELFISH */ "Aktif";
$ccms['lang']['users']['level'] = /* BABELFISH */ "Düzeyi";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "Kullanıcı düzeyinde";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "Son günlük";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "Yönetmek şablonları";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "Mevcut şablon <strong>değil</strong> yazılabilir";
$ccms['lang']['template']['print'] = /* BABELFISH */ "Baskı";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "Izin tercihleri";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "tabloda asgari kullanıcı düzeyinde belirli özelliklerini kullanmak ne belirtmek için aşağıdaki bağlantıyı kullanın. Belirtilen minimum kullanım seviyesinin altında herhangi bir kullanıcı, görmez ne özelliği erişebilirsiniz.";
$ccms['lang']['permission']['target'] = /* BABELFISH */ "Hedef";
$ccms['lang']['permission']['level1'] = /* BABELFISH */ "Seviye 1 - Kullanma";
$ccms['lang']['permission']['level2'] = /* BABELFISH */ "Seviye 2 - Editör";
$ccms['lang']['permission']['level3'] = /* BABELFISH */ "Level 3 - Müdür";
$ccms['lang']['permission']['level4'] = /* BABELFISH */ "Level 4 - Yönetici";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "Içerik sahipleri";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "Burada bireysel kullanıcılar için özel sayfa mülkiyet atayabilir. belirli bir sayfa için kullanıcılar seçilirse, herkes sayfa değiştirebilirsiniz. Aksi takdirde sadece belirtilen kullanıcı dosyaya değiştirme hakları vardı. Yöneticiler her zaman tüm dosyalara erişimi var.";
$ccms['lang']['owners']['pages'] = /* BABELFISH */ "Sayfaları";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "Kullanıcı";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "Çevirmek";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "Çeviri dizeleri gösterir.";

// Album messages
$ccms['lang']['album']['album'] = /* BABELFISH */ "Albüm";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "Albüm ayarları";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "Özellikle bu albüm için geçerli";
$ccms['lang']['album']['description'] = /* BABELFISH */ "Albüm açıklaması";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "Geçerli albüm";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "Upload içerik";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "Upload albüm mevcut";
$ccms['lang']['album']['upload'] = /* BABELFISH */ "upload Başlat";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "Dosyalara göz at";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "Açık liste";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>Tek dosya upload</strong><br> <p>Flash yükleyici başlatılamadı. Emin olun Javascript ve etkin Flash yüklenir. Tek bir dosya yükleme da mümkündür, ama optimize edilmemiş.</p>";
$ccms['lang']['album']['manage'] = /* BABELFISH */ "albüm Yönet";
$ccms['lang']['album']['albumlist'] = /* BABELFISH */ "Albüm listesi";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "Yeniden tüm küçük";
$ccms['lang']['album']['newalbum'] = /* BABELFISH */ "Yaratmak yeni albüm";
$ccms['lang']['album']['noalbums'] = /* BABELFISH */ "Henüz hiç albüm yaratılmıştır!";
$ccms['lang']['album']['files'] = /* BABELFISH */ "Resimler";
$ccms['lang']['album']['nodir'] = /* BABELFISH */ "dizin emin olun <strong>albümü</strong> bulunmaktadır. / media / dizini";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "Son değiştirilme";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "Lütfen bekleyin ...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "güncel haber yönetin";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "Haber Ekle";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "Yeni makale yazın";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "ayarlarını yönetme";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "haber yaz";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "ön uç üzerinde # mesajları";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "Göstermek yazar";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "Show yayın tarihi";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "Sadece teaser gösterisi";
$ccms['lang']['news']['title'] = /* BABELFISH */ "Haber başlığı";
$ccms['lang']['news']['author'] = /* BABELFISH */ "Haber yazar";
$ccms['lang']['news']['date'] = /* BABELFISH */ "Tarih";
$ccms['lang']['news']['published'] = /* BABELFISH */ "Yayınlanmış?";
$ccms['lang']['news']['teaser'] = "Teaser";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "Madde içerikleri";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "Görmek arşiv";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = /* BABELFISH */ "Tepkisi henüz gönderilmemiş!";
$ccms['lang']['guestbook']['verinstr'] = /* BABELFISH */ "Bu mesajı otomatik olmadığını kontrol etmek için, yeniden girin";
$ccms['lang']['guestbook']['reaction'] = /* BABELFISH */ "Tepki";
$ccms['lang']['guestbook']['rating'] = "Rating";
$ccms['lang']['guestbook']['avatar'] = /* BABELFISH */ "Gravatar.com kullanıcı avatar";
$ccms['lang']['guestbook']['wrote'] = /* BABELFISH */ "yazdı";
$ccms['lang']['guestbook']['manage'] = /* BABELFISH */ "reaksiyonları Yönet";
$ccms['lang']['guestbook']['delentry'] = /* BABELFISH */ "Bu Girişi Sil";
$ccms['lang']['guestbook']['sendmail'] = /* BABELFISH */ "E-mail yazar";
$ccms['lang']['guestbook']['removed'] = /* BABELFISH */ "veritabanından kaldırılmıştır.";
$ccms['lang']['guestbook']['name'] = /* BABELFISH */ "Adınızı";
$ccms['lang']['guestbook']['email'] = /* BABELFISH */ "E-posta";
$ccms['lang']['guestbook']['website'] = /* BABELFISH */ "Web sitenizin";
$ccms['lang']['guestbook']['comments'] = /* BABELFISH */ "Yorum";
$ccms['lang']['guestbook']['verify'] = /* BABELFISH */ "Doğrulama";
$ccms['lang']['guestbook']['preview'] = /* BABELFISH */ "Ön yorum";
$ccms['lang']['guestbook']['add'] = /* BABELFISH */ "Lütfen yorum ekle";
$ccms['lang']['guestbook']['posted'] = /* BABELFISH */ "Yorum gönderilmemiş!";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "teşekkürler";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "Başarısızlıkları ve reddedilmesi";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "Yorum reddedildi.";


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
	$ccms['lang']['guestbook']['reaction']	
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
	  
       
      /*
         ----------------------------------------------------------
      */
	  



/* ADDITION */


?>