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
$ccms['lang']['system']['error_database'] = /* BABELFISH */ "데이터베이스에 연결할 수 없습니다. 귀하의 로그인 세부 정보 및 데이터베이스 이름을 확인하십시오.";
$ccms['lang']['system']['error_openfile'] = /* BABELFISH */ "지정된 파일을 열 수 없습니다";
$ccms['lang']['system']['error_notemplate'] = /* BABELFISH */ "아니 귀하의 사이트에 적용하는 서식 파일을 찾을 수 없습니다. 하나 이상의 템플릿을 추가하십시오. / lib 디렉토리 / 템플릿 /.";
$ccms['lang']['system']['error_templatedir'] = /* BABELFISH */ "이 디렉토리를 템플릿을 찾을 수 없습니다! 확인이 존재하며 최소한 하나의 템플릿이 포함되어 있습니다. 있는지";
$ccms['lang']['system']['error_write'] = /* BABELFISH */ "파일에 쓰기 권한";
$ccms['lang']['system']['error_dirwrite'] = /* BABELFISH */ "디렉토리는 더 쓰기 권한";
$ccms['lang']['system']['error_chmod'] = /* BABELFISH */ "현재 파일은 수정할 수 없습니다. / 콘텐츠 디렉터리 (666)에있는 파일에 대한 사용 권한을 확인합니다.";
$ccms['lang']['system']['error_value'] = /* BABELFISH */ "오류 : 잘못된 값";
$ccms['lang']['system']['error_default'] = /* BABELFISH */ "기본 페이지는 삭제할 수 없습니다.";
$ccms['lang']['system']['error_forged'] = /* BABELFISH */ "값은 위조되었습니다";
$ccms['lang']['system']['error_filedots'] = /* BABELFISH */ "파일 이름 예를 들어, 도트가 없어야 '. html로'.";
$ccms['lang']['system']['error_filesize'] = /* BABELFISH */ "파일 이름은 적어도 3 자 이상이어야합니다.";
$ccms['lang']['system']['error_filesize_2'] = /* BABELFISH */ "파일 이름은 최대 50 자 이상이어야합니다.";
$ccms['lang']['system']['error_pagetitle'] = /* BABELFISH */ "3 자 이상의 페이지 제목을 입력하세요.";
$ccms['lang']['system']['error_pagetitle_2'] = /* BABELFISH */ "100 자 이하의 페이지 제목을 입력하세요.";
$ccms['lang']['system']['error_subtitle'] = /* BABELFISH */ "귀하의 페이지에 대한 짧은 하위 제목을 줘.";
$ccms['lang']['system']['error_subtitle_2'] = /* BABELFISH */ "200 자 또는 귀하의 페이지에 대한 이하의 짧은 하위 제목을 입력하세요.";
$ccms['lang']['system']['error_description'] = /* BABELFISH */ "3 개 이상 문자의 설명을 입력합니다";
$ccms['lang']['system']['error_description_2'] = /* BABELFISH */ "미만 250 자에 대한 설명을 입력하십시오";
$ccms['lang']['system']['error_reserved'] = /* BABELFISH */ "당신은 내부 사용을 위해 예약된 파일 이름을 지정했습니다.";
$ccms['lang']['system']['error_general'] = /* BABELFISH */ "오류가 발생했습니다";
$ccms['lang']['system']['error_correct'] = /* BABELFISH */ "다음 사항을 수정하십시오 :";
$ccms['lang']['system']['error_create'] = /* BABELFISH */ "오류가 새로운 파일의 생성을 완료하는 동안";
$ccms['lang']['system']['error_exists'] = /* BABELFISH */ "파일 이름이 이미 존재합니다.";
$ccms['lang']['system']['error_delete'] = /* BABELFISH */ "오류가 선택한 파일의 삭제를 작성하는 동안";
$ccms['lang']['system']['error_selection'] = /* BABELFISH */ "선택된 항목이 있었다.";
$ccms['lang']['system']['error_versioninfo'] = /* BABELFISH */ "아니 버전 정보가 제공됩니다.";
$ccms['lang']['system']['error_misconfig'] = /* BABELFISH */ "<strong>잘못된있을 것 같습니다.</strong><br>. htaccess 파일이 제대로 당신의 파일 구조를 반영하도록 구성되어 있는지 확인하십시오. 당신이있다면<br>하위 디렉토리에 설치 CompactCMS은 다음을 조정합니다. htaccess 파일을 적절하게.";
$ccms['lang']['system']['error_deleted'] = /* BABELFISH */ "<h1>선택한 파일이 삭제된 것 같습니다</h1> <p>새로 고침 filelist 상황에서이 오류를 방지하기 위해 해당 파일의 가장 최근의 목록을 볼 수 있습니다. 이 오류가 해결되지 않으면, 수동으로 열려고하는 파일에 대한 콘텐츠 폴더를 확인합니다.</p>";
$ccms['lang']['system']['error_404title'] = /* BABELFISH */ "파일을 찾을 수 없습니다";
$ccms['lang']['system']['error_404header'] = /* BABELFISH */ "404 오류가 요청한 파일을 찾을 수 없습니다 발생했습니다.";
$ccms['lang']['system']['error_404content'] = /* BABELFISH */ "요청한 파일이 <strong>{% pagereq %}. HTML은</strong> 찾을 수 없습니다.";
$ccms['lang']['system']['error_403title'] = /* BABELFISH */ "금지된";
$ccms['lang']['system']['error_403header'] = /* BABELFISH */ "403 오류가 발생했습니다 : 당신이 요청한 파일에 액세스할 수있는 권한이 없습니다.";
$ccms['lang']['system']['error_403content'] = /* BABELFISH */ "당신은에 액세스할 수 없습니다 <strong>pagereq {% %}. html로</strong> 순간이.";
$ccms['lang']['system']['error_sitemap'] = /* BABELFISH */ "모든 페이지의 개요";
$ccms['lang']['system']['error_tooshort'] = /* BABELFISH */ "하나 또는 여러 개의 제출 값도 너무 짧은하거나 잘못되었습니다";
$ccms['lang']['system']['error_passshort'] = /* BABELFISH */ "비밀 번호는 6 자 이상이 포함되어야";
$ccms['lang']['system']['error_passnequal'] = /* BABELFISH */ "입력한 비밀 번호가 일치하지 않습니다";
$ccms['lang']['system']['noresults'] = /* BABELFISH */ "결과 없음";
$ccms['lang']['system']['tooriginal'] = /* BABELFISH */ "다시 원본";
$ccms['lang']['system']['message_rights'] = /* BABELFISH */ "판권 소유";
$ccms['lang']['system']['message_compatible'] = /* BABELFISH */ "성공적으로 테스트";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = /* BABELFISH */ "있어 제안, 의견이나하는 데 문제가 있습니까? 방문 <a class='external' title='Visit the official forum' rel='external' href='http://community.compactcms.nl/forum/'>포럼을</a>!";
$ccms['lang']['backend']['ordertip'] = /* BABELFISH */ "귀하의 사이트 '메뉴에서 페이지의 구조를 반영하기 위해 드롭 다운 아래에 사용하십시오. 시스템은 상위 또는 하위 수준의 조합을 중복 고려하지 않는 것을 유의하십시오.";
$ccms['lang']['backend']['createtip'] = /* BABELFISH */ "새 페이지를 만들려면 아래의 양식을 작성하여 새 페이지가 즉시 당신을 위해 만들어집니다. 파일이 만들어진 후, 당신은 평소처럼 페이지를 편집하실 수 있습니다.";
$ccms['lang']['backend']['currentfiles'] = /* BABELFISH */ "목록에 현재 출판된 모든 페이지를 찾을 수 아래. 그것은 귀하의 웹사이트의 현재 홈페이지이기 때문에 당신은 파일의 기본 페이지는 삭제할 수 없습니다 것을 알 수 있습니다. 관리자는 이러한 파일을 통해 단독 소유권을 가지고 있기 때문에 다른 파일에 접근이 제한된있을 수 있습니다.";
$ccms['lang']['backend']['confirmdelete'] = /* BABELFISH */ "당신이 선택한 항목 (들)을 삭제할 것인지 확인하십시오.";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "당신은 모든 축소판을 다시 생성 것인지 확인하시기 바랍니다.";
$ccms['lang']['backend']['settingssaved'] = /* BABELFISH */ "변경 사항이 성공적으로 저장되었습니다.";
$ccms['lang']['backend']['must_refresh'] = /* BABELFISH */ "보고 페이지를 메인 있는지에 다시로드하시기 바랍니다 <strong>모든</strong> 변경 사항을";
$ccms['lang']['backend']['itemcreated'] = /* BABELFISH */ "성공적으로 제출된 항목 (들)을 처리했습니다.";
$ccms['lang']['backend']['fullremoved'] = /* BABELFISH */ "성공적으로 선택한 항목 (들)을 삭제했습니다.";
$ccms['lang']['backend']['fullregenerated'] = /* BABELFISH */ "성공적으로 이미지를 재생.";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "개요로 돌아가기";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "세트 CCMS 권한";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "콘텐츠 소유자를 정의";
$ccms['lang']['backend']['templateeditor'] = /* BABELFISH */ "템플릿 편집기";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "사용자 관리";
$ccms['lang']['backend']['changevalue'] = /* BABELFISH */ "변경하려면 클릭하십시오";
$ccms['lang']['backend']['previewpage'] = /* BABELFISH */ "미리보기";
$ccms['lang']['backend']['editpage'] = /* BABELFISH */ "수정";
$ccms['lang']['backend']['restrictpage'] = /* BABELFISH */ "제한된";
$ccms['lang']['backend']['newfiledone'] = /* BABELFISH */ "이 파일은 당신이 채워 업에 대한 신선하고 깨끗합니다!";
$ccms['lang']['backend']['newfilecreated'] = /* BABELFISH */ "파일이 만들어졌습니다";
$ccms['lang']['backend']['startedittitle'] = /* BABELFISH */ "수정을 시작하십시오!";
$ccms['lang']['backend']['starteditbody'] = /* BABELFISH */ "새 파일이 만들어졌습니다. 시작 편집 당장하거나 더 많은 페이지를 추가하거나 현재의 것들을 관리할 수 있습니다.";
$ccms['lang']['backend']['success'] = /* BABELFISH */ "성공!";
$ccms['lang']['backend']['fileexists'] = /* BABELFISH */ "파일이 존재";
$ccms['lang']['backend']['statusdelete'] = /* BABELFISH */ "선택 삭제 상태 :";
$ccms['lang']['backend']['statusremoved'] = /* BABELFISH */ "삭제";
$ccms['lang']['backend']['uptodate'] = /* BABELFISH */ "최신.";
$ccms['lang']['backend']['outofdate'] = /* BABELFISH */ "오래된.";
$ccms['lang']['backend']['considerupdate'] = /* BABELFISH */ "업데이트 고려";
$ccms['lang']['backend']['orderprefsaved'] = /* BABELFISH */ "귀하의 메뉴 항목의 순서에 대한 환경 설정이 저장되었습니다.";
$ccms['lang']['backend']['inmenu'] = /* BABELFISH */ "메뉴에서";
$ccms['lang']['backend']['updatelist'] = /* BABELFISH */ "업데이트 파일 목록";
$ccms['lang']['backend']['administration'] = /* BABELFISH */ "행정";
$ccms['lang']['backend']['currentversion'] = /* BABELFISH */ "현재 버전을 실행하고";
$ccms['lang']['backend']['mostrecent'] = /* BABELFISH */ "가장 최근의 안정적인 CompactCMS 버전입니다";
$ccms['lang']['backend']['versionstatus'] = /* BABELFISH */ "설치는";
$ccms['lang']['backend']['createpage'] = /* BABELFISH */ "새 페이지 만들기";
$ccms['lang']['backend']['managemenu'] = /* BABELFISH */ "메뉴 관리";
$ccms['lang']['backend']['managefiles'] = /* BABELFISH */ "현재 페이지를 관리";
$ccms['lang']['backend']['delete'] = /* BABELFISH */ "삭제";
$ccms['lang']['backend']['toplevel'] = /* BABELFISH */ "최상위 수준";
$ccms['lang']['backend']['sublevel'] = /* BABELFISH */ "하위 수준";
$ccms['lang']['backend']['active'] = /* BABELFISH */ "활동적인";
$ccms['lang']['backend']['disabled'] = /* BABELFISH */ "사용 불가";
$ccms['lang']['backend']['template'] = /* BABELFISH */ "템플릿";
$ccms['lang']['backend']['notinmenu'] = /* BABELFISH */ "항목이 없습니다 메뉴에서";
$ccms['lang']['backend']['menutitle'] = /* BABELFISH */ "메뉴";
$ccms['lang']['backend']['linktitle'] = /* BABELFISH */ "링크";
$ccms['lang']['backend']['item'] = /* BABELFISH */ "항목";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "없음";
$ccms['lang']['backend']['yes'] = /* BABELFISH */ "예";
$ccms['lang']['backend']['no'] = /* BABELFISH */ "없음";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "번역";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "안녕하세요";
$ccms['lang']['backend']['logout'] = /* BABELFISH */ "로그 아웃";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "포럼보기";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>최종 사용하여 유효한 사용자 이름 및 다시 CompactCMS의 암호에 대한 액세스를 얻을 수 있습니다. 경우로 돌아가려면, 당신은 도착 실수로 여기 <a href='admin/includes/'>홈페이지</a>.</p> <p>당신의 자세한 내용은 웹마 스터에게 문의하십시오.</p>";
$ccms['lang']['login']['username'] = /* BABELFISH */ "사용자 이름";
$ccms['lang']['login']['password'] = /* BABELFISH */ "비밀 번호";
$ccms['lang']['login']['login'] = /* BABELFISH */ "로그인";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "사용자 자격 증명을 제공하십시오";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "모두 사용자 이름과 비밀 번호를 입력";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "사용자 이름을 입력하십시오";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "비밀 번호를 입력하세요";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "이 계정은 비활성화되었습니다";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "당신은 이미 여러 시도를합니다";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "잘못된 사용자 이름 또는 비밀 번호";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = /* BABELFISH */ "대문";
$ccms['lang']['menu']['2'] = /* BABELFISH */ "왼쪽";
$ccms['lang']['menu']['3'] = /* BABELFISH */ "오른쪽";
$ccms['lang']['menu']['4'] = /* BABELFISH */ "바닥글";
$ccms['lang']['menu']['5'] = /* BABELFISH */ "여분의";

// Administration form related texts
$ccms['lang']['forms']['filename'] = /* BABELFISH */ "파일 이름";
$ccms['lang']['forms']['pagetitle'] = /* BABELFISH */ "페이지 제목";
$ccms['lang']['forms']['subheader'] = /* BABELFISH */ "하위 머리글";
$ccms['lang']['forms']['description'] = /* BABELFISH */ "설명";
$ccms['lang']['forms']['module'] = /* BABELFISH */ "모듈";
$ccms['lang']['forms']['contentitem'] = /* BABELFISH */ "컨텐트 항목 (기본값)";
$ccms['lang']['forms']['additions'] = /* BABELFISH */ "추가";
$ccms['lang']['forms']['printable'] = /* BABELFISH */ "인쇄할 수있는";
$ccms['lang']['forms']['published'] = /* BABELFISH */ "활동적인";
$ccms['lang']['forms']['iscoding'] = /* BABELFISH */ "코딩";
$ccms['lang']['forms']['createbutton'] = /* BABELFISH */ "만들기!";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "수정";
$ccms['lang']['forms']['savebutton'] = /* BABELFISH */ "저장";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "프론트 엔드 언어";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "지금 우리는 적어도이 텍스트를 여기에이 페이지를 보여주는거야";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "편집 또는 제거 필터";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "필터 추가";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = /* BABELFISH */ "페이지 URL (home.html) :: 이 페이지는시 호출되는 파일 이름 (없이 HTML을.)";
$ccms['lang']['hints']['pagetitle'] = /* BABELFISH */ "이 페이지에 대한 제목 (홈) :: 이 페이지에 대한 짧은 설명 제목입니다.";
$ccms['lang']['hints']['subheader'] = /* BABELFISH */ "짧은 헤더 텍스트 (오신 것을 환영합니다 저희 사이트) :: 각 페이지의 제목뿐만 아니라 마찬가지로 각 페이지의 헤더에 사용되는 짧은 설명 텍스트입니다.";
$ccms['lang']['hints']['description'] = /* BABELFISH */ "메타 설명 :: 페이지의 메타 설명으로 사용됩니다이 페이지에 대한 고유의 설명입니다.";
$ccms['lang']['hints']['module'] = /* BABELFISH */ "모듈 :: 무슨 모듈이 파일의 내용을 처리한다 선택하십시오. 당신이 확실하지 않으면, 기본값을 선택합니다.";
$ccms['lang']['hints']['printable'] = /* BABELFISH */ "페이지 printability :: 선택 '예'를 인쇄할 수있는 페이지가 생성됩니다. '아니오'는 그림이나 기타 미디어와 함께 페이지에 선택해야합니다. 이들은 해제 인쇄용 페이지없이 좋다.";
$ccms['lang']['hints']['published'] = /* BABELFISH */ "게시 상태 :: 이것은 Sitemap에 나열되어 준다면 그것은 대중에게 액세스할 수 있는지 여부를 선택합니다이 페이지는 출판되어야합니다.";
$ccms['lang']['hints']['toplevel'] = /* BABELFISH */ "최상위 수준 :: 지정이 메뉴 항목에 대한 최상위 수준.";
$ccms['lang']['hints']['sublevel'] = /* BABELFISH */ "하위 수준 :: 선택 공 때이 상품의 최상위 항목이 있어야합니다. 그것은 특정 최상위 레벨에 대한 하위 항목이있는 경우, 해당 하위 수준을 선택하십시오.";
$ccms['lang']['hints']['template'] = /* BABELFISH */ "템플릿을 :: 당신이 여러 개 설치에 대한 템플릿을 사용하는 경우 별도의 지명할 수를 사용하여 개별 페이지에 대한 템플릿이 드롭 다운.";
$ccms['lang']['hints']['activelink'] = /* BABELFISH */ "메뉴에서 활성 링크? :: 모든 항목은 항상 실제 링크가 필요합니다. 프런트 엔드 메뉴에이 항목의 선택을 취소 아래의 확인란을 뒤로 링크를 비활성화하십시오.";
$ccms['lang']['hints']['menuid'] = /* BABELFISH */ "메뉴 :: 어느 메뉴에이 항목이 기본값 인치 나열해야 선택도 홈 페이지 링크가 표시되어야하는 메인 메뉴 (1)입니다.";
$ccms['lang']['hints']['iscoding'] = /* BABELFISH */ "코딩 포함 사항 :: 이 파일은 매뉴얼은 PHP 나 자바 스크립트와 같은 코드를 추가한 포함되어 있습니까? 을 선택하면 '예'를 백 엔드의 WYSIWYG 편집기에서 파일에 대한 액세스를 제한하고 코드 편집기 수 있습니다.";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br><br>당신은를 클릭하여 <span class='sprite livefilter livefilter_active'> 필터 아이콘을 입력 기자가 다음 '홈'에서이 아이콘을 클릭하십시오 당신이 때 필드를 나타납니다 편집 입력하면 추가, 수정 또는 예 제거에 목록을 페이지에 텍스트를 필터를 제목의 좌회전 / home을 '에 해당 열에 반환'페이지에있는 텍스트 전용 키, 표시됩니다. <br>다시 아이콘을 클릭하고 편집 필드에 텍스트를 삭제, 다음은 / 반품 키를 입력 누르면됩니다 필터를 제거합니다.<br>필터를 아이콘 위로 마우스를 가져가면 어떤 필터 텍스트를 사용하고, 컬럼은 현재 필터링되고 있는지 그렇다면 볼 수 있습니다.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = /* BABELFISH */ "편집기를 닫으십시오";
$ccms['lang']['editor']['editorfor'] = /* BABELFISH */ "텍스트 편집기에 대한";
$ccms['lang']['editor']['instruction'] = /* BABELFISH */ "편집기 현재 파일을 수정하려면 다음을 사용하십시오. 일단 당신이 완료하는 직접 월드 와이드웹에 수정을 게시하려면 아래 버튼을 '변경 사항 저장'을 누르십시오. 또한 검색 엔진 optimalization에 관련 키워드를 열 개까지 추가할 수 있습니다.";
$ccms['lang']['editor']['savebtn'] = /* BABELFISH */ "변경 사항 저장";
$ccms['lang']['editor']['cancelbtn'] = /* BABELFISH */ "취소";
$ccms['lang']['editor']['confirmclose'] = /* BABELFISH */ "이 창을 닫고 변경 사항을 취소 하시겠습니까?";
$ccms['lang']['editor']['preview'] = /* BABELFISH */ "미리 결과 페이지";
$ccms['lang']['editor']['savesuccess'] = /* BABELFISH */ "<strong>성공!</strong> 에 저장되어 아래와 같이 콘텐츠";
$ccms['lang']['editor']['backeditor'] = /* BABELFISH */ "편집기로 돌아가기";
$ccms['lang']['editor']['closewindow'] = /* BABELFISH */ "창 닫기";
$ccms['lang']['editor']['keywords'] = /* BABELFISH */ "키워드 - <em>쉼표로 구분하여, 최대 250 자</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "자동 안전한 암호를 생성할";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "귀하의 현재 계정 수준이 기능을 사용하는 것을 허용하지 않습니다.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = /* BABELFISH */ "새로 만들기 백업";
$ccms['lang']['backup']['explain'] = /* BABELFISH */ "데이터 인해 가능한 손실을 방지하기 위해 무엇이든지 외부 이벤트가 정기적으로 파일 업을 다시 만들 수 현명합니다.";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "경고 :: 귀하는 점에 유의하시기 바랍니다 <dfn>라이트 박스의</dfn> 앨범 '이미지가 <strong>없습니다</strong> 백업된 설명은! 앨범 <strong>아르</strong>지만, 이미지가 자신과 이미지가 아르 <strong>백업 이들에 포함되어 있지 않습니다</strong>과의. 백업을 원하는 당신이하면 그 다음 수여해야합니다 당신은 추가적인 백업 시스템이 귀하의 사이트 관리자는 백업에 대해 당신이 도움 및 컬렉션을 복원이 (아마도 큰) 파일.";
$ccms['lang']['backup']['currenthd'] = /* BABELFISH */ "백업 가능한";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "백업 파일 이름";
$ccms['lang']['backup']['download'] = /* BABELFISH */ "다운로드 자료실";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "백업이 생성되는 동안 잠시 기다려주십시오 ...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "사용자 만들기";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "CCMS 개요 사용자";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "사용자의 개인 정보 수정";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "사용자의 암호를 수정";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "계정 설정";
$ccms['lang']['users']['user'] = /* BABELFISH */ "사용자";
$ccms['lang']['users']['username'] = /* BABELFISH */ "사용자 이름";
$ccms['lang']['users']['name'] = /* BABELFISH */ "이름";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "첫 번째 이름";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "성";
$ccms['lang']['users']['password'] = /* BABELFISH */ "비밀 번호";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "비밀 번호 확인";
$ccms['lang']['users']['email'] = /* BABELFISH */ "이메일";
$ccms['lang']['users']['active'] = /* BABELFISH */ "활동적인";
$ccms['lang']['users']['level'] = /* BABELFISH */ "레벨";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "사용자 레벨";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "마지막 로그인";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "관리 템플릿";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "현재 템플릿은 <strong>안</strong> 쓰기";
$ccms['lang']['template']['print'] = /* BABELFISH */ "인쇄";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "허가 환경 설정";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "테이블 최소한의 사용자 수준의 특정 기능을 사용할 수있는 지정하려면 다음을 사용하십시오. 지정된 사용자 최소 요구 수준 아래의 모든 사용자가 볼 수 없습니다 않으며이 기능에 액세스할 수 있습니다.";
$ccms['lang']['permission']['target'] = /* BABELFISH */ "목표";
$ccms['lang']['permission']['level1'] = /* BABELFISH */ "레벨 1 - 사용자";
$ccms['lang']['permission']['level2'] = /* BABELFISH */ "레벨 2 - 에디터";
$ccms['lang']['permission']['level3'] = /* BABELFISH */ "레벨 3 - 관리자";
$ccms['lang']['permission']['level4'] = /* BABELFISH */ "레벨 4 - 관리자";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "컨텐츠 소유자";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "여기 개별 사용자에게 특정 페이지 소유권을 지명할 수 있습니다. 특정 페이지에 대한 사용자가 선택한 경우, 모든 사람이 페이지를 수정할 수 있습니다. 그렇지 않으면 단지 지정된 사용자가 파일에 대한 수정 권한을했다. 관리자는 항상 모든 파일에 액세스할 수 있습니다.";
$ccms['lang']['owners']['pages'] = /* BABELFISH */ "페이지";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "사용자";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "번역";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "번역 문자열을 표시합니다.";

// Album messages
$ccms['lang']['album']['album'] = /* BABELFISH */ "앨범";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "앨범 설정";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "특히이 앨범에 적용";
$ccms['lang']['album']['description'] = /* BABELFISH */ "앨범 설명";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "현재 앨범";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "업로드 내용";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "업로드 앨범 기존의";
$ccms['lang']['album']['upload'] = /* BABELFISH */ "업로드 시작";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "찾아보기 파일";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "지우기 목록";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>단일 파일 업로드</strong><br> <p>플래시 로더 초기화하지 못했습니다. 확인 확실 Javascript 및 활성화되어 플래시가 설치됩니다. 단일 파일 업로드하지만, 가능 최적화되어 있지.</p>";
$ccms['lang']['album']['manage'] = /* BABELFISH */ "앨범 관리";
$ccms['lang']['album']['albumlist'] = /* BABELFISH */ "앨범 목록";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "재생 모두 작은 이미지";
$ccms['lang']['album']['newalbum'] = /* BABELFISH */ "새 앨범";
$ccms['lang']['album']['noalbums'] = /* BABELFISH */ "앨범이 아직 만들어진!";
$ccms['lang']['album']['files'] = /* BABELFISH */ "파일";
$ccms['lang']['album']['nodir'] = /* BABELFISH */ "디렉토리 있는지 확인하세요 <strong>앨범</strong> 에 존재합니다. / 미디어 / 디렉토리";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "최종 수정된 날짜";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "기다려주십시오 ...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "현재 뉴스 항목 관리";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "뉴스 추가";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "새 글 쓰기";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "설정 관리";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "뉴스 쓰기";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "프런트 엔드에 # 메시지";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "보기 저자";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "표시 발행일";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "맛보기만을 보여";
$ccms['lang']['news']['title'] = /* BABELFISH */ "뉴스 제목";
$ccms['lang']['news']['author'] = /* BABELFISH */ "뉴스 작성자";
$ccms['lang']['news']['date'] = /* BABELFISH */ "날짜";
$ccms['lang']['news']['published'] = /* BABELFISH */ "게시?";
$ccms['lang']['news']['teaser'] = /* BABELFISH */ "어려운 일";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "문서 내용";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "보기 아카이브";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = /* BABELFISH */ "아뇨 아직 반응이 게시되었습니다!";
$ccms['lang']['guestbook']['verinstr'] = /* BABELFISH */ "이 메시지는 자동으로되어 있지 않은지 확인하기 위해 다시 입력하십시오";
$ccms['lang']['guestbook']['reaction'] = /* BABELFISH */ "반응";
$ccms['lang']['guestbook']['rating'] = /* BABELFISH */ "평가";
$ccms['lang']['guestbook']['avatar'] = /* BABELFISH */ "Gravatar.com 사용자 아바타";
$ccms['lang']['guestbook']['wrote'] = /* BABELFISH */ "쓴";
$ccms['lang']['guestbook']['manage'] = /* BABELFISH */ "반응 관리";
$ccms['lang']['guestbook']['delentry'] = /* BABELFISH */ "이 항목 삭제";
$ccms['lang']['guestbook']['sendmail'] = /* BABELFISH */ "전자 우편 작성자";
$ccms['lang']['guestbook']['removed'] = /* BABELFISH */ "데이터베이스에서 제거되었습니다.";
$ccms['lang']['guestbook']['name'] = /* BABELFISH */ "귀하의 이름";
$ccms['lang']['guestbook']['email'] = /* BABELFISH */ "전자 메일";
$ccms['lang']['guestbook']['website'] = /* BABELFISH */ "귀하의 웹사이트";
$ccms['lang']['guestbook']['comments'] = /* BABELFISH */ "댓글";
$ccms['lang']['guestbook']['verify'] = /* BABELFISH */ "확인";
$ccms['lang']['guestbook']['preview'] = /* BABELFISH */ "미리보기 덧글";
$ccms['lang']['guestbook']['add'] = /* BABELFISH */ "귀하의 의견을 추가";
$ccms['lang']['guestbook']['posted'] = /* BABELFISH */ "여러분의 댓글이 게시되었습니다!";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "감사합니다";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "실패 & 거부";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "귀하의 덧글이 거부되었습니다.";


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
	  
?>
