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
$ccms['lang']['system']['error_database'] = /* BABELFISH */ "डेटाबेस से कनेक्ट नहीं कर सका. अपनी प्रवेश जानकारी और डेटाबेस नाम सत्यापित करें.";
$ccms['lang']['system']['error_openfile'] = /* BABELFISH */ "निर्दिष्ट फ़ाइल नहीं खोल सकता";
$ccms['lang']['system']['error_notemplate'] = /* BABELFISH */ "नहीं सका टेम्पलेट्स होने के लिए आपकी साइट के लिए लागू किया जा पाया. कम से कम एक टेम्पलेट जोड़ कृपया / lib / टेम्पलेट्स /..";
$ccms['lang']['system']['error_templatedir'] = /* BABELFISH */ "नहीं मिल सका निर्देशिका टेम्पलेट्स! यकीन है कि यह मौजूद है और कम से कम एक टेम्पलेट शामिल हैं.";
$ccms['lang']['system']['error_write'] = /* BABELFISH */ "फ़ाइल पहुँच नहीं लिखना है";
$ccms['lang']['system']['error_dirwrite'] = /* BABELFISH */ "निर्देशिका का उपयोग नहीं लिखना है";
$ccms['lang']['system']['error_chmod'] = /* BABELFISH */ "वर्तमान फ़ाइल बदला नहीं जा सका. / निर्देशिका सामग्री (666) में फाइल पर अनुमतियाँ जाँच करें.";
$ccms['lang']['system']['error_value'] = /* BABELFISH */ "त्रुटि: गलत मूल्य";
$ccms['lang']['system']['error_default'] = /* BABELFISH */ "डिफ़ॉल्ट पृष्ठ नष्ट नहीं किया जा सकता है.";
$ccms['lang']['system']['error_forged'] = /* BABELFISH */ "मूल्य के साथ छेड़छाड़ की गई है";
$ccms['lang']['system']['error_filedots'] = /* BABELFISH */ "फ़ाइल का नाम डॉट्स शामिल नहीं है, जैसे होना चाहिए. Html.";
$ccms['lang']['system']['error_filesize'] = /* BABELFISH */ "फ़ाइल का नाम कम से कम 3 अक्षर का होना चाहिए.";
$ccms['lang']['system']['error_filesize_2'] = /* BABELFISH */ "फ़ाइल का नाम 50 सबसे में अक्षर का होना चाहिए.";
$ccms['lang']['system']['error_pagetitle'] = /* BABELFISH */ "अधिक 3 या वर्णों की एक पृष्ठ का शीर्षक दर्ज करें.";
$ccms['lang']['system']['error_pagetitle_2'] = /* BABELFISH */ "100 वर्ण या उससे कम के एक पृष्ठ शीर्षक दर्ज करें.";
$ccms['lang']['system']['error_subtitle'] = /* BABELFISH */ "अपने पेज के लिए एक छोटी उप शीर्षक दें.";
$ccms['lang']['system']['error_subtitle_2'] = /* BABELFISH */ "200 वर्ण या अपने पेज के लिए कम के एक छोटे उप शीर्षक दर्ज करें.";
$ccms['lang']['system']['error_description'] = /* BABELFISH */ "3 से अधिक अक्षर का एक विवरण दर्ज करें";
$ccms['lang']['system']['error_description_2'] = /* BABELFISH */ "कम से कम 250 अक्षरों का एक विवरण दर्ज करें";
$ccms['lang']['system']['error_reserved'] = /* BABELFISH */ "आप किसी फ़ाइल को आंतरिक उपयोग के लिए आरक्षित नाम निर्दिष्ट किया है.";
$ccms['lang']['system']['error_general'] = /* BABELFISH */ "त्रुटि हुई";
$ccms['lang']['system']['error_correct'] = /* BABELFISH */ "निम्नलिखित सही करें:";
$ccms['lang']['system']['error_create'] = /* BABELFISH */ "त्रुटि जब नई फ़ाइल का निर्माण पूरा";
$ccms['lang']['system']['error_exists'] = /* BABELFISH */ "फ़ाइल का नाम आप पहले से ही मौजूद है निर्दिष्ट नहीं है.";
$ccms['lang']['system']['error_delete'] = /* BABELFISH */ "त्रुटि जब चयनित फ़ाइल का विलोपन पूरा";
$ccms['lang']['system']['error_selection'] = /* BABELFISH */ "कोई चयनित आइटम थे.";
$ccms['lang']['system']['error_versioninfo'] = /* BABELFISH */ "कोई संस्करण की जानकारी उपलब्ध है.";
$ccms['lang']['system']['error_misconfig'] = /* BABELFISH */ "<strong>वहाँ के लिए एक misconfiguration लगता है.</strong><br>सुनिश्चित करें कि. Htaccess फ़ाइल सही ढंग से अपनी फ़ाइल संरचना को प्रतिबिंबित कॉन्फ़िगर है सुनिश्चित करें. यदि आपके पास<br>एक subdirectory में स्थापित CompactCMS, तो htaccess फ़ाइल समायोजित तदनुसार..";
$ccms['lang']['system']['error_deleted'] = /* BABELFISH */ "<h1>फाइल जिसे आपने चुना है नष्ट हो रहा है</h1> <p>ताज़ा filelist उपलब्ध फ़ाइलों की सबसे हाल की सूची पर हो रहा से इस त्रुटि को रोकने के लिए देखने के लिए. यदि यह इस त्रुटि को हल नहीं करता, स्वयं फ़ाइल आप खोलने का प्रयास कर रहे हैं के लिए सामग्री फ़ोल्डर की जाँच करें.</p>";
$ccms['lang']['system']['error_404title'] = /* BABELFISH */ "फ़ाइल नहीं मिला";
$ccms['lang']['system']['error_404header'] = /* BABELFISH */ "एक 404 त्रुटि हुई है, अनुरोध फाइल पाया नहीं जा सका.";
$ccms['lang']['system']['error_404content'] = /* BABELFISH */ "अनुरोधित फ़ाइल <strong>{%pagereq%}.html</strong> पाया नहीं जा सका.";
$ccms['lang']['system']['error_403title'] = /* BABELFISH */ "निषिद्ध";
$ccms['lang']['system']['error_403header'] = /* BABELFISH */ "एक 403 त्रुटि हुई: आप के लिए अनुरोध फ़ाइल का उपयोग की अनुमति नहीं है.";
$ccms['lang']['system']['error_403content'] = /* BABELFISH */ "आप का उपयोग करने की अनुमति दी नहीं हैं <strong>{%pagereq%}.html</strong> क्षण में यह.";
$ccms['lang']['system']['error_sitemap'] = /* BABELFISH */ "सभी पृष्ठों का अवलोकन";
$ccms['lang']['system']['error_tooshort'] = /* BABELFISH */ "एक या एकाधिक प्रस्तुत मानों या तो बहुत कम या गलत थे";
$ccms['lang']['system']['error_passshort'] = /* BABELFISH */ "एक पासवर्ड से अधिक 6 अक्षर होने चाहिए";
$ccms['lang']['system']['error_passnequal'] = /* BABELFISH */ "प्रवेश पासवर्ड से मेल नहीं खाती";
$ccms['lang']['system']['noresults'] = /* BABELFISH */ "कोई परिणाम नहीं";
$ccms['lang']['system']['tooriginal'] = /* BABELFISH */ "वापस मूल करने के लिए";
$ccms['lang']['system']['message_rights'] = /* BABELFISH */ "सभी अधिकार सुरक्षित";
$ccms['lang']['system']['message_compatible'] = /* BABELFISH */ "सफलतापूर्वक पर परीक्षण किया";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = /* BABELFISH */ "समझे सुझाव, राय या परेशानी हो रही? यात्रा के <a class='external' title='Visit the official forum' rel='external' href='http://community.compactcms.nl/forum/'>मंच</a>!";
$ccms['lang']['backend']['ordertip'] = /* BABELFISH */ "ड्रॉप चढ़ाव से नीचे का प्रयोग करें अपने साइटों 'मेनू में आपके पृष्ठों की संरचना को प्रतिबिंबित. जानते हैं कि प्रणाली को ध्यान में नहीं ले ऊपर या उप स्तर संयोजन नकल करता है.";
$ccms['lang']['backend']['createtip'] = /* BABELFISH */ "एक नया पृष्ठ बनाने के लिए, नीचे दिए फॉर्म को भरने और एक नया पृष्ठ मक्खी पर आप के लिए बनाया जाएगा. के बाद फ़ाइल है बनाया गया है, तो आप सामान्य रूप से पृष्ठ संपादित कर पाएंगे.";
$ccms['lang']['backend']['currentfiles'] = /* BABELFISH */ "सूची में नीचे आप सभी वर्तमान में प्रकाशित पृष्ठों मिल जाएगा. आप उस फ़ाइल डिफ़ॉल्ट पृष्ठ नष्ट नहीं किया जा सकता है नोटिस, क्योंकि यह अपनी वेबसाइट के होमपेज पर वर्तमान है हूँ. अन्य फ़ाइलों को प्रतिबंधित उपयोग किया है क्योंकि प्रशासक इन फाइलों पर एकल स्वामित्व है हो सकता है.";
$ccms['lang']['backend']['confirmdelete'] = /* BABELFISH */ "पुष्टि करें कि आप चयनित मद (s) को मिटाना चाहते हैं.";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "पुष्टि करें कि आप सभी थंबनेल पुनर्जीवित चाहते हैं.";
$ccms['lang']['backend']['settingssaved'] = /* BABELFISH */ "आपके परिवर्तन सफलतापूर्वक सहेज लिया गया है.";
$ccms['lang']['backend']['must_refresh'] = /* BABELFISH */ "कृपया सुनिश्चित करें देखने के लिए पुनः लोड करने के लिए मुख्य पृष्ठ पर <strong>सब</strong> अपने परिवर्तन";
$ccms['lang']['backend']['itemcreated'] = /* BABELFISH */ "सफलतापूर्वक प्रस्तुत मद (ओं) को संसाधित.";
$ccms['lang']['backend']['fullremoved'] = /* BABELFISH */ "सफलतापूर्वक चयनित मद (ओं) को नष्ट कर दिया.";
$ccms['lang']['backend']['fullregenerated'] = /* BABELFISH */ "सफलतापूर्वक थंबनेल पुनर्जीवित.";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "वापस सिंहावलोकन करने के लिए";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "सेट CCMS अनुमति";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "सामग्री के मालिकों को परिभाषित";
$ccms['lang']['backend']['templateeditor'] = /* BABELFISH */ "टेम्पलेट संपादक";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "उपयोगकर्ता के प्रबंधन";
$ccms['lang']['backend']['changevalue'] = /* BABELFISH */ "बदलने के लिए क्लिक करें";
$ccms['lang']['backend']['previewpage'] = /* BABELFISH */ "पूर्वावलोकन";
$ccms['lang']['backend']['editpage'] = /* BABELFISH */ "संपादित करें";
$ccms['lang']['backend']['restrictpage'] = /* BABELFISH */ "प्रतिबंधित";
$ccms['lang']['backend']['newfiledone'] = /* BABELFISH */ "इस फाइल को ताजा और साफ है आप ऊपर भरने के लिए!";
$ccms['lang']['backend']['newfilecreated'] = /* BABELFISH */ "फ़ाइल बना दिया गया है";
$ccms['lang']['backend']['startedittitle'] = /* BABELFISH */ "शुरू संपादन!";
$ccms['lang']['backend']['starteditbody'] = /* BABELFISH */ "नई फ़ाइल बना दिया गया है. शुरू संपादन अभी भी या अधिक पृष्ठों जोड़ने या मौजूदा वालों का प्रबंधन.";
$ccms['lang']['backend']['success'] = /* BABELFISH */ "सफलता!";
$ccms['lang']['backend']['fileexists'] = /* BABELFISH */ "फ़ाइल मौजूद है";
$ccms['lang']['backend']['statusdelete'] = /* BABELFISH */ "चयनित हटाए जाने की स्थिति:";
$ccms['lang']['backend']['statusremoved'] = /* BABELFISH */ "हटाया";
$ccms['lang']['backend']['uptodate'] = /* BABELFISH */ "ऊपर तिथि करने के लिए.";
$ccms['lang']['backend']['outofdate'] = /* BABELFISH */ "पुराना.";
$ccms['lang']['backend']['considerupdate'] = /* BABELFISH */ "अद्यतन करने पर विचार";
$ccms['lang']['backend']['orderprefsaved'] = /* BABELFISH */ "अपने मेनू आइटम के आदेश के लिए आपकी प्राथमिकताएं बचा लिया गया है.";
$ccms['lang']['backend']['inmenu'] = /* BABELFISH */ "मेनू में";
$ccms['lang']['backend']['updatelist'] = /* BABELFISH */ "फ़ाइल अद्यतन सूची";
$ccms['lang']['backend']['administration'] = /* BABELFISH */ "प्रशासन";
$ccms['lang']['backend']['currentversion'] = /* BABELFISH */ "आप वर्तमान संस्करण चला रहे हैं";
$ccms['lang']['backend']['mostrecent'] = /* BABELFISH */ "सबसे हाल ही में स्थिर CompactCMS संस्करण है";
$ccms['lang']['backend']['versionstatus'] = /* BABELFISH */ "अपनी स्थापना है";
$ccms['lang']['backend']['createpage'] = /* BABELFISH */ "एक नया पृष्ठ बनाएँ";
$ccms['lang']['backend']['managemenu'] = /* BABELFISH */ "प्रबंधित करें मेनू";
$ccms['lang']['backend']['managefiles'] = /* BABELFISH */ "वर्तमान पृष्ठ का प्रबंधन";
$ccms['lang']['backend']['delete'] = /* BABELFISH */ "हटाएँ";
$ccms['lang']['backend']['toplevel'] = /* BABELFISH */ "शीर्ष स्तर";
$ccms['lang']['backend']['sublevel'] = /* BABELFISH */ "उप स्तर";
$ccms['lang']['backend']['active'] = /* BABELFISH */ "सक्रिय";
$ccms['lang']['backend']['disabled'] = /* BABELFISH */ "विकलांग";
$ccms['lang']['backend']['template'] = /* BABELFISH */ "टेम्पलेट";
$ccms['lang']['backend']['notinmenu'] = /* BABELFISH */ "आइटम नहीं मेनू में";
$ccms['lang']['backend']['menutitle'] = /* BABELFISH */ "मेनू";
$ccms['lang']['backend']['linktitle'] = /* BABELFISH */ "लिंक";
$ccms['lang']['backend']['item'] = /* BABELFISH */ "मद";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "कोई नहीं";
$ccms['lang']['backend']['yes'] = /* BABELFISH */ "हां";
$ccms['lang']['backend']['no'] = /* BABELFISH */ "नहीं";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "अनुवाद";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "हाय";
$ccms['lang']['backend']['logout'] = /* BABELFISH */ "लॉग आउट";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "मंच देखें";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>अंत का प्रयोग करें एक मान्य उपयोगकर्ता नाम और पासवर्ड वापस CompactCMS हासिल करने के लिए उपयोग करने के लिए. अगर आप को वापस आ गया यहाँ, गलती से <a href='admin/includes/'>घर पृष्ठ</a>.</p> <p>आपकी जानकारी के लिए अपने वेबमास्टर से संपर्क करें.</p>";
$ccms['lang']['login']['username'] = /* BABELFISH */ "उपयोगकर्ता नाम";
$ccms['lang']['login']['password'] = /* BABELFISH */ "पासवर्ड";
$ccms['lang']['login']['login'] = /* BABELFISH */ "लॉग इन";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "कृपया अपने उपयोगकर्ता क्रेडेंशियल्स प्रदान";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "दोनों अपने उपयोगकर्ता नाम और पासवर्ड दर्ज करें";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "अपना उपयोगकर्ता नाम दर्ज करें";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "अपना पासवर्ड दर्ज करें";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "इस खाते को निष्क्रिय कर दिया गया है";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "कृपया ध्यान दें कि आप पहले से ही कई प्रयास किए";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "गलत उपयोगकर्ता नाम या पासवर्ड";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = /* BABELFISH */ "मुख्य";
$ccms['lang']['menu']['2'] = /* BABELFISH */ "बाएं";
$ccms['lang']['menu']['3'] = /* BABELFISH */ "सही";
$ccms['lang']['menu']['4'] = /* BABELFISH */ "पाद लेख";
$ccms['lang']['menu']['5'] = /* BABELFISH */ "अतिरिक्त";

// Administration form related texts
$ccms['lang']['forms']['filename'] = /* BABELFISH */ "फ़ाइल का नाम";
$ccms['lang']['forms']['pagetitle'] = /* BABELFISH */ "पृष्ठ शीर्षक";
$ccms['lang']['forms']['subheader'] = /* BABELFISH */ "सबहेडर";
$ccms['lang']['forms']['description'] = /* BABELFISH */ "विवरण";
$ccms['lang']['forms']['module'] = /* BABELFISH */ "मॉड्यूल";
$ccms['lang']['forms']['contentitem'] = /* BABELFISH */ "सामग्री मद (डिफ़ॉल्ट)";
$ccms['lang']['forms']['additions'] = /* BABELFISH */ "परिवर्धन";
$ccms['lang']['forms']['printable'] = /* BABELFISH */ "छापने योग्य";
$ccms['lang']['forms']['published'] = /* BABELFISH */ "सक्रिय";
$ccms['lang']['forms']['iscoding'] = /* BABELFISH */ "कोडन";
$ccms['lang']['forms']['createbutton'] = /* BABELFISH */ "बनाएँ!";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "संशोधित";
$ccms['lang']['forms']['savebutton'] = /* BABELFISH */ "सहेजें";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "सामने वाले किनारे भाषा";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "अभी हम केवल वे पन्ने जो कम से कम यहाँ में इस पाठ की है दिखा रहे हैं";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "संपादित करें या हटाने के लिए फ़िल्टर";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "फ़िल्टर के लिए जोड़ें";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = /* BABELFISH */ "पृष्ठ (home.html) url: (एचटीएमएल बिना.) फ़ाइल नाम जो इस पृष्ठ का आह्वान किया";
$ccms['lang']['hints']['pagetitle'] = /* BABELFISH */ "इस पेज के लिए शीर्षक (गृह): इस पृष्ठ के लिए एक छोटी वर्णनात्मक शीर्षक.";
$ccms['lang']['hints']['subheader'] = /* BABELFISH */ "लघु शीर्षक (आपका स्वागत हमारी साइट के लिए) पाठ: एक लघु वर्णनात्मक के रूप में प्रत्येक पृष्ठ के शीर्षक में अच्छी तरह से प्रत्येक पृष्ठ के शीर्षक में पाठ के रूप में इस्तेमाल.";
$ccms['lang']['hints']['description'] = /* BABELFISH */ "मेटा विवरण: इस पृष्ठ जो पृष्ठों के मेटा वर्णन के रूप में इस्तेमाल किया जाएगा के लिए एक अनूठा वर्णन.";
$ccms['lang']['hints']['module'] = /* BABELFISH */ "मॉड्यूल :: चुनें मॉड्यूल क्या इस फाइल की सामग्री को संभालना चाहिए. यदि आप अनिश्चित हैं, तो डिफ़ॉल्ट का चयन करें.";
$ccms['lang']['hints']['printable'] = /* BABELFISH */ "पृष्ठ printability :: जब चयनित 'हाँ' एक मुद्रण योग्य पृष्ठ उत्पन्न होता है. 'नहीं' चित्र या अन्य मीडिया के साथ पृष्ठों के लिए चयनित किया जाना चाहिए. इन प्रिंट करने योग्य पृष्ठ बिना बेहतर कर रहे हैं.";
$ccms['lang']['hints']['published'] = /* BABELFISH */ "प्रकाशित स्थिति: चुनें यदि इस पृष्ठ पर प्रकाशित किया जाना चाहिए अगर यह साइटमैप में सूचीबद्ध किया जाएगा और यह जनता के लिए सुलभ हो जाएगा या नहीं.";
$ccms['lang']['hints']['toplevel'] = /* BABELFISH */ "शीर्ष स्तर: निर्दिष्ट यह मेनू आइटम के लिए शीर्ष स्तर पर.";
$ccms['lang']['hints']['sublevel'] = /* BABELFISH */ "उप स्तर: 0 का चयन इस आइटम के एक शीर्ष स्तर के मद जब होना चाहिए. अगर यह एक निश्चित शीर्ष स्तर के लिए एक उप मद है, उपयुक्त उप स्तर का चयन करें कृपया.";
$ccms['lang']['hints']['template'] = /* BABELFISH */ "साँचा :: यदि आप का उपयोग अपने अधिष्ठापन के लिए एकाधिक टेम्पलेट्स, आप अलग अलग प्रत्येक व्यक्ति को नियुक्त कर सकते हैं पृष्ठ के लिए टेम्पलेट का उपयोग करते हुए इस ड्रॉप डाउन.";
$ccms['lang']['hints']['activelink'] = /* BABELFISH */ "मेनू में सक्रिय लिंक? :: नहीं सभी आइटम्स हमेशा एक वास्तविक लिंक की जरूरत है. सामने के अंत मेनू में इस मद, अचयनित इसके नीचे निशानबक्से के पीछे कड़ी को निष्क्रिय करने के लिए.";
$ccms['lang']['hints']['menuid'] = /* BABELFISH */ "मेनू: चुनें कि किस मेनू में इस मद डिफ़ॉल्ट अंदर सूचीबद्ध किया जाना चाहिए मुख्य (1) करें, जहाँ भी घर पृष्ठ लिंक को दर्शाया जाना है.";
$ccms['lang']['hints']['iscoding'] = /* BABELFISH */ "कोडिंग शामिल हैं :: क्या इस फाइल को समाहित पुस्तिका PHP या जावास्क्रिप्ट कोड के रूप में जोड़ा? चयन 'हाँ' के पीछे से है अंत WYSIWYG संपादक से फाइल करने के लिए उपयोग को प्रतिबंधित करने और कोड संपादक सक्षम बनाता है.";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br><br>तुम पर क्लिक कर सकते हैं <span class='sprite livefilter livefilter_active'> फ़िल्टर चिह्न पर पर सूची बाईं पृष्ठ के शीर्षक में जोड़ना या संपादित हटाने पाठ को फ़िल्टर, जैसे जब आप टाइप 'घर' में चिह्न को संपादित क्षेत्र है जो प्रकट होता है जब आप क्लिक करें, तो प्रेस दर्ज करें / वापसी कुंजी है, केवल वे पन्ने जो स्तंभ 'घर' में इस पाठ की है दिखाया जाएगा. <br>आइकन पर क्लिक करने और फिर से संपादित कर फ़ील्ड में पाठ को हटाने, तो / वापसी कुंजी दर्ज करें दबाव होगा, फिल्टर को हटा दें.<br>फ़िल्टर चिह्न पर हॉवर देखना है कि क्या स्तंभ वर्तमान फ़िल्टर्ड किया जा रहा है, और यदि ऐसा है, जो फिल्टर पाठ का उपयोग कर.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = /* BABELFISH */ "संपादक बंद";
$ccms['lang']['editor']['editorfor'] = /* BABELFISH */ "पाठ संपादक के लिए";
$ccms['lang']['editor']['instruction'] = /* BABELFISH */ "संपादक को मौजूदा फ़ाइल को संशोधित नीचे का प्रयोग करें. एक बार जब आप कर रहे हैं 'परिवर्तन सहेजें' बटन के नीचे करने के लिए सीधे वर्ल्ड वाइड वेब के लिए अपने संशोधनों को प्रकाशित मारा. भी खोज इंजन optimalization के लिए दस प्रासंगिक खोजशब्दों को जोड़ें.";
$ccms['lang']['editor']['savebtn'] = /* BABELFISH */ "परिवर्तन सहेजें";
$ccms['lang']['editor']['cancelbtn'] = /* BABELFISH */ "रद्द";
$ccms['lang']['editor']['confirmclose'] = /* BABELFISH */ "इस विंडो को बंद करें और किसी भी परिवर्तन को त्याग दें?";
$ccms['lang']['editor']['preview'] = /* BABELFISH */ "पूर्वावलोकन परिणाम पृष्ठ";
$ccms['lang']['editor']['savesuccess'] = /* BABELFISH */ "<strong>सफलता!</strong> सामग्री के रूप में सहेजा गया है दिखाया गया है नीचे";
$ccms['lang']['editor']['backeditor'] = /* BABELFISH */ "संपादक पर वापस";
$ccms['lang']['editor']['closewindow'] = /* BABELFISH */ "विंडो बंद करें";
$ccms['lang']['editor']['keywords'] = /* BABELFISH */ "खोजशब्द - <em>अल्पविराम के द्वारा अलग, अधिकतम 250 वर्ण</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "स्वत: एक सुरक्षित पासवर्ड उत्पन्न";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "अपने चालू खाता स्तर पर आप इस सुविधा का उपयोग करने के लिए अनुमति नहीं है.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = /* BABELFISH */ "नया बैक अप";
$ccms['lang']['backup']['explain'] = /* BABELFISH */ "कारण डेटा के संभावित नुकसान को रोकने के लिए जो कुछ बाहरी घटना, के लिए इसे वापस करने के लिए आपकी फ़ाइलों के अप नियमित रूप से बनाने के लिए बुद्धिमान है.";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "चेतावनी: कृपया ध्यान रखें कि आपके <dfn>lightbox</dfn> 'एलबम छवियाँ हैं <strong>नहीं</strong> समर्थित विवरण! एल्बम <strong>रहे हैं</strong>, लेकिन चित्र खुद को और अपने थंबनेल हैं <strong>इन बैकअप में शामिल नहीं</strong>के साथ. यदि का बैकअप चाहता हूँ कि तुम उन लोगों में, फिर प्रदान करने के लिए आप की आवश्यकता होगी आपके बैकअप सिस्टम अतिरिक्त साइट व्यवस्थापक के बारे में एक बैकअप तुम मदद करने और वसूली बहाल इन (संभवतः बड़े) फ़ाइल.";
$ccms['lang']['backup']['currenthd'] = /* BABELFISH */ "वापस अप उपलब्ध";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "बैक अप फ़ाइल का नाम";
$ccms['lang']['backup']['download'] = /* BABELFISH */ "डाउनलोड संग्रह";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "प्रतीक्षा करते हुए बैकअप बनाया जा रहा है कृपया ...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "एक यूजर बनाएँ";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "सिंहावलोकन CCMS उपयोगकर्ताओं";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "उपयोगकर्ता की निजी जानकारी संपादित करें";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "उपयोगकर्ता के पासवर्ड को संपादित करें";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "खाता सेटिंग्स";
$ccms['lang']['users']['user'] = /* BABELFISH */ "उपयोगकर्ता";
$ccms['lang']['users']['username'] = /* BABELFISH */ "उपयोगकर्ता नाम";
$ccms['lang']['users']['name'] = /* BABELFISH */ "नाम";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "पहला नाम";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "अंतिम नाम";
$ccms['lang']['users']['password'] = /* BABELFISH */ "पासवर्ड";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "पासवर्ड की पुष्टि करें";
$ccms['lang']['users']['email'] = /* BABELFISH */ "ई मेल करें";
$ccms['lang']['users']['active'] = /* BABELFISH */ "सक्रिय";
$ccms['lang']['users']['level'] = /* BABELFISH */ "स्तर";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "उपयोगकर्ता स्तर";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "पिछले लॉग इन";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "प्रबंधित टेम्पलेट्स";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "मौजूदा टेम्पलेट है <strong>नहीं</strong> लिखने योग्य";
$ccms['lang']['template']['print'] = /* BABELFISH */ "प्रिंट";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "अनुमति वरीयताओं";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "तालिका में निर्दिष्ट न्यूनतम उपयोगकर्ता स्तर क्या कुछ सुविधाएँ उपयोग कर सकते हैं नीचे का प्रयोग करें. निर्दिष्ट न्यूनतम आवश्यक उपयोगकर्ता के स्तर से नीचे किसी भी उपयोगकर्ता देखते हैं, न ही सुविधा के लिए उपयोग होगा.";
$ccms['lang']['permission']['target'] = /* BABELFISH */ "लक्ष्य";
$ccms['lang']['permission']['level1'] = /* BABELFISH */ "स्तर 1 - उपयोगकर्ता";
$ccms['lang']['permission']['level2'] = /* BABELFISH */ "स्तर 2 - संपादक";
$ccms['lang']['permission']['level3'] = /* BABELFISH */ "स्तर 3 - प्रबंधक";
$ccms['lang']['permission']['level4'] = /* BABELFISH */ "स्तर 4 - व्यवस्थापक";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "सामग्री के मालिकों";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "यहाँ आप व्यक्तिगत उपयोगकर्ताओं के लिए विशेष पृष्ठ स्वामित्व नियुक्त कर सकते हैं. यदि एक निश्चित पृष्ठ के लिए उपयोगकर्ता नहीं चुने गए हैं, हर कोई पृष्ठ बदल सकते हैं. अन्यथा केवल निर्दिष्ट उपयोगकर्ता फाइल करने के लिए संशोधन अधिकार था. प्रशासकों हमेशा सभी फाइल करने के लिए उपयोग किया है.";
$ccms['lang']['owners']['pages'] = /* BABELFISH */ "पेज";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "उपयोगकर्ताओं";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "अनुवाद";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "अनुवाद तार दिखाता है.";

// Album messages
$ccms['lang']['album']['album'] = /* BABELFISH */ "एल्बम";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "एल्बम सेटिंग्स";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "विशेष रूप से इस एलबम के लिए लागू";
$ccms['lang']['album']['description'] = /* BABELFISH */ "एल्बम विवरण";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "वर्तमान एल्बम";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "अपलोड सामग्री";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "अपलोड करने के लिए मौजूदा एल्बम";
$ccms['lang']['album']['upload'] = /* BABELFISH */ "अपलोड शुरू करें";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "ब्राउज़ फ़ाइलें";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "साफ सूची";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>एकल फाइल अपलोड</strong><br> <p>फ्लैश लोडर की शुरूआत करने में असफल रहा. सुनिश्चित करें कि जावास्क्रिप्ट सक्षम है और फ्लैश स्थापित है. एकल फाइल अपलोड संभव हो रहे हैं, लेकिन अनुकूल नहीं है.</p>";
$ccms['lang']['album']['manage'] = /* BABELFISH */ "एलबम का प्रबंधन";
$ccms['lang']['album']['albumlist'] = /* BABELFISH */ "एल्बम सूची";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "पुनर्जन्म सभी थंबनेल";
$ccms['lang']['album']['newalbum'] = /* BABELFISH */ "नया एल्बम";
$ccms['lang']['album']['noalbums'] = /* BABELFISH */ "अभी कोई एलबम नहीं बनाया गया है!";
$ccms['lang']['album']['files'] = /* BABELFISH */ "फाइलें";
$ccms['lang']['album']['nodir'] = /* BABELFISH */ "कृपया निर्देशिका सुनिश्चित करें कि <strong>एलबम</strong> में मौजूद निर्देशिका / /. मीडिया";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "संशोधित अंतिम";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "कृपया प्रतीक्षा करें ...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "वर्तमान समाचार आइटम का प्रबंधन";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "समाचार जोड़ें";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "नए लेख लिखें";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "व्यवस्थायें";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "समाचार लिखें";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "सामने के अंत पर # संदेश";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "दिखाने के लेखक";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "दिखाएँ प्रकाशन दिनांक";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "केवल दिखाने के लिए टीज़र";
$ccms['lang']['news']['title'] = /* BABELFISH */ "समाचार शीर्षक";
$ccms['lang']['news']['author'] = /* BABELFISH */ "समाचार लेखक";
$ccms['lang']['news']['date'] = /* BABELFISH */ "तारीख";
$ccms['lang']['news']['published'] = /* BABELFISH */ "प्रकाशित?";
$ccms['lang']['news']['teaser'] = /* BABELFISH */ "टीज़र";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "लेख सामग्री";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "देखें संग्रह";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = /* BABELFISH */ "कोई प्रतिक्रिया अभी तक तैनात किया गया है!";
$ccms['lang']['guestbook']['verinstr'] = /* BABELFISH */ "जाँच करने के लिए कि इस संदेश को स्वचालित नहीं है, कृपया पुनः प्रवेश";
$ccms['lang']['guestbook']['reaction'] = /* BABELFISH */ "प्रतिक्रिया";
$ccms['lang']['guestbook']['rating'] = /* BABELFISH */ "रेटिंग";
$ccms['lang']['guestbook']['avatar'] = /* BABELFISH */ "Gravatar.com उपयोगकर्ता अवतार";
$ccms['lang']['guestbook']['wrote'] = /* BABELFISH */ "लिखा";
$ccms['lang']['guestbook']['manage'] = /* BABELFISH */ "प्रतिक्रियाओं का प्रबंधन";
$ccms['lang']['guestbook']['delentry'] = /* BABELFISH */ "इस प्रविष्टि हटाना";
$ccms['lang']['guestbook']['sendmail'] = /* BABELFISH */ "ई मेल करें लेखक";
$ccms['lang']['guestbook']['removed'] = /* BABELFISH */ "है डाटाबेस से हटा दिया गया.";
$ccms['lang']['guestbook']['name'] = /* BABELFISH */ "आपका नाम";
$ccms['lang']['guestbook']['email'] = /* BABELFISH */ "अपना ई मेल";
$ccms['lang']['guestbook']['website'] = /* BABELFISH */ "अपनी वेबसाइट";
$ccms['lang']['guestbook']['comments'] = /* BABELFISH */ "टिप्पणियाँ";
$ccms['lang']['guestbook']['verify'] = /* BABELFISH */ "सत्यापन";
$ccms['lang']['guestbook']['preview'] = /* BABELFISH */ "पूर्वावलोकन टिप्पणी";
$ccms['lang']['guestbook']['add'] = /* BABELFISH */ "अपनी टिप्पणी जोड़ें";
$ccms['lang']['guestbook']['posted'] = /* BABELFISH */ "अपनी टिप्पणी पोस्ट कर दिया गया है!";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "शुक्रिया";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "विफलताओं और Rejections";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "आपकी टिप्पणी को अस्वीकार कर दिया गया है.";


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