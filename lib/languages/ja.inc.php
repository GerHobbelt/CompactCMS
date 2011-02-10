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
$ccms['lang']['system']['error_database'] = /* BABELFISH */ "データベースに接続できませんでした。 ログイン情報およびデータベース名を確認してください。";
$ccms['lang']['system']['error_openfile'] = /* BABELFISH */ "指定されたファイルを開くことができません。";
$ccms['lang']['system']['error_notemplate'] = /* BABELFISH */ "いいえあなたのサイトに適用されるが見つかりませんでしたテンプレート。 には、少なくとも一つのテンプレートを追加してください。/ libに/テンプレート/。";
$ccms['lang']['system']['error_templatedir'] = /* BABELFISH */ "templatesディレクトリに見つけることができませんでした！ 確認が存在する、少なくとも1つのテンプレートが含まれています。ください";
$ccms['lang']['system']['error_write'] = /* BABELFISH */ "ファイルは書き込みアクセス権を持つ";
$ccms['lang']['system']['error_dirwrite'] = /* BABELFISH */ "ディレクトリには書き込みアクセス権を持つ";
$ccms['lang']['system']['error_chmod'] = /* BABELFISH */ "現在のファイルを変更することはできませんでした。 /コンテンツディレクトリ（666）内のファイルのパーミッションを確認してください。";
$ccms['lang']['system']['error_value'] = /* BABELFISH */ "エラー：不正な値";
$ccms['lang']['system']['error_default'] = /* BABELFISH */ "デフォルトのページを削除することはできません。";
$ccms['lang']['system']['error_forged'] = /* BABELFISH */ "値が改ざんされている";
$ccms['lang']['system']['error_filedots'] = /* BABELFISH */ "ファイル名は、例えば、ドットを含めることはできません'html'も。";
$ccms['lang']['system']['error_filesize'] = /* BABELFISH */ "ファイル名は、少なくとも3文字の長さがあります。";
$ccms['lang']['system']['error_filesize_2'] = /* BABELFISH */ "ファイル名は、最大で50文字の長さがあります。";
$ccms['lang']['system']['error_pagetitle'] = /* BABELFISH */ "3文字以上のページのタイトルを入力してください。";
$ccms['lang']['system']['error_pagetitle_2'] = /* BABELFISH */ "100文字以下のページのタイトルを入力してください。";
$ccms['lang']['system']['error_subtitle'] = /* BABELFISH */ "ページの短いサブタイトルを付けてください。";
$ccms['lang']['system']['error_subtitle_2'] = /* BABELFISH */ "200文字またはページ以下の短いサブタイトルを入力します。";
$ccms['lang']['system']['error_description'] = /* BABELFISH */ "3文字以上の説明を入力します";
$ccms['lang']['system']['error_description_2'] = /* BABELFISH */ "未満の250文字の説明を入力します";
$ccms['lang']['system']['error_reserved'] = /* BABELFISH */ "あなたが内部使用のために予約済みのファイル名を指定している。";
$ccms['lang']['system']['error_general'] = /* BABELFISH */ "エラーが発生しました";
$ccms['lang']['system']['error_correct'] = /* BABELFISH */ "次のように修正してください：";
$ccms['lang']['system']['error_create'] = /* BABELFISH */ "エラーは、新しいファイルの作成を完了するまでの間";
$ccms['lang']['system']['error_exists'] = /* BABELFISH */ "ファイル名は既に存在して指定します。";
$ccms['lang']['system']['error_delete'] = /* BABELFISH */ "エラーは、選択したファイルの削除を完了するまでの間";
$ccms['lang']['system']['error_selection'] = /* BABELFISH */ "選択されていない項目があった。";
$ccms['lang']['system']['error_versioninfo'] = /* BABELFISH */ "バージョン情報は入手可能です。";
$ccms['lang']['system']['error_misconfig'] = /* BABELFISH */ "<strong>ミスがあるようです。</strong><br>。htaccessファイルが正しくファイルの構造を反映するように構成されていることを確認してください。 お持ちの場合<br>サブディレクトリにインストールさCompactCMSし、調整してください。htaccessファイルをそれに応じて。";
$ccms['lang']['system']['error_deleted'] = /* BABELFISH */ "<h1>あなたが選択したファイルが削除されると思われる</h1> <p>更新は、filelist起きてからこのエラーを防止するために利用可能なファイルの最新のリストを確認してください。 これは、このエラーが解決しない場合は、手動で開こうとしているファイルのコンテンツフォルダを確認してください。</p>";
$ccms['lang']['system']['error_404title'] = /* BABELFISH */ "ファイルが見つかりませんでした";
$ccms['lang']['system']['error_404header'] = /* BABELFISH */ "404エラーは、要求されたファイルが見つかりませんでしたが発生しました。";
$ccms['lang']['system']['error_404content'] = /* BABELFISH */ "要求されたファイルが <strong>{%pagereq%}.html</strong> 見つかりませんでした。";
$ccms['lang']['system']['error_403title'] = /* BABELFISH */ "紫禁城";
$ccms['lang']['system']['error_403header'] = /* BABELFISH */ "403エラーが発生しました：あなたは、要求されたファイルにアクセスする権限がありません。";
$ccms['lang']['system']['error_403content'] = /* BABELFISH */ "あなたはアクセスを許可されていない <strong>{%pagereq%}.html</strong> 今のところ、これ。";
$ccms['lang']['system']['error_sitemap'] = /* BABELFISH */ "すべてのページの概要";
$ccms['lang']['system']['error_tooshort'] = /* BABELFISH */ "つまたは複数のサブミットされた値のいずれか短すぎるか、間違っていました";
$ccms['lang']['system']['error_passshort'] = /* BABELFISH */ "パスワードは6文字以上を含める必要があります";
$ccms['lang']['system']['error_passnequal'] = /* BABELFISH */ "入力されたパスワードが一致しませんでした";
$ccms['lang']['system']['noresults'] = /* BABELFISH */ "いいえ検索結果";
$ccms['lang']['system']['tooriginal'] = /* BABELFISH */ "を元に戻します";
$ccms['lang']['system']['message_rights'] = /* BABELFISH */ "すべての著作権";
$ccms['lang']['system']['message_compatible'] = /* BABELFISH */ "正常にテスト";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = /* BABELFISH */ "ガットの提案、意見や苦労？ アクセス <a class='external' title='Visit the official forum' rel='external' href='http://community.compactcms.nl/forum/'>フォーラム</a>！";
$ccms['lang']['backend']['ordertip'] = /* BABELFISH */ "あなたのサイトのメニューのページの構造を反映して、ドロップダウンをご使用下さい。 システムは、上部またはサブレベルの組み合わせを重複して考慮されていないことに注意してください。";
$ccms['lang']['backend']['createtip'] = /* BABELFISH */ "新しいページを作成するには、下記のフォームに必要事項を記入の新しいページがその場で自動的に作成されます。 ファイルが作成された後、いつものようにページを編集することができるでしょう。";
$ccms['lang']['backend']['currentfiles'] = /* BABELFISH */ "リストでは、現在公開されているすべてのページを見つけることが下記。 それはあなたのサイトの現在のホームページされているため、ファイルのデフォルトページを削除することはできませんがわかります。 管理者は、これらのファイルの上の唯一の所有権を持っているので、他のファイルにはアクセス制限があるかもしれません。";
$ccms['lang']['backend']['confirmdelete'] = /* BABELFISH */ "あなたが選択した項目（複数可）を削除することを確認してください。";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "あなたはすべてのサムネイルを再生成するかどうかを確認してください。";
$ccms['lang']['backend']['settingssaved'] = /* BABELFISH */ "あなたの変更が正常に保存されている。";
$ccms['lang']['backend']['must_refresh'] = /* BABELFISH */ "を参照してくださいページをメイン確認してからリロードしてください <strong>すべての</strong> 変更を";
$ccms['lang']['backend']['itemcreated'] = /* BABELFISH */ "正常に送信さ項目（複数可）を処理します。";
$ccms['lang']['backend']['fullremoved'] = /* BABELFISH */ "正常に選択された項目（複数可）を削除しました。";
$ccms['lang']['backend']['fullregenerated'] = /* BABELFISH */ "正常にサムネイルを再生成されます。";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "概要に戻る";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "設定CCMSのアクセス許可";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "コンテンツの所有者を定義します。";
$ccms['lang']['backend']['templateeditor'] = /* BABELFISH */ "テンプレートエディタ";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "ユーザー管理";
$ccms['lang']['backend']['changevalue'] = /* BABELFISH */ "クリックしてください";
$ccms['lang']['backend']['previewpage'] = /* BABELFISH */ "プレビュー";
$ccms['lang']['backend']['editpage'] = /* BABELFISH */ "[編集]";
$ccms['lang']['backend']['restrictpage'] = /* BABELFISH */ "制限";
$ccms['lang']['backend']['newfiledone'] = /* BABELFISH */ "このファイルには、埋めるためにアップ新鮮できれいです！";
$ccms['lang']['backend']['newfilecreated'] = /* BABELFISH */ "ファイルが作成されている";
$ccms['lang']['backend']['startedittitle'] = /* BABELFISH */ "編集を開始！";
$ccms['lang']['backend']['starteditbody'] = /* BABELFISH */ "新しいファイルが作成されています。 [スタート]を編集してすぐにどちらかより多くのページを追加するか、または現在のものを管理します。";
$ccms['lang']['backend']['success'] = /* BABELFISH */ "成功！";
$ccms['lang']['backend']['fileexists'] = /* BABELFISH */ "ファイルが存在する";
$ccms['lang']['backend']['statusdelete'] = /* BABELFISH */ "選択された削除のステータス：";
$ccms['lang']['backend']['statusremoved'] = /* BABELFISH */ "削除";
$ccms['lang']['backend']['uptodate'] = /* BABELFISH */ "最新の状態に。";
$ccms['lang']['backend']['outofdate'] = /* BABELFISH */ "時代遅れ。";
$ccms['lang']['backend']['considerupdate'] = /* BABELFISH */ "更新を検討";
$ccms['lang']['backend']['orderprefsaved'] = /* BABELFISH */ "メニュー項目の順序の設定を保存されている。";
$ccms['lang']['backend']['inmenu'] = /* BABELFISH */ "メニューで";
$ccms['lang']['backend']['updatelist'] = /* BABELFISH */ "更新ファイル一覧";
$ccms['lang']['backend']['administration'] = /* BABELFISH */ "管理";
$ccms['lang']['backend']['currentversion'] = /* BABELFISH */ "あなたは現在のバージョンを実行している";
$ccms['lang']['backend']['mostrecent'] = /* BABELFISH */ "最新の安定CompactCMSのバージョンが";
$ccms['lang']['backend']['versionstatus'] = /* BABELFISH */ "インストールは、";
$ccms['lang']['backend']['createpage'] = /* BABELFISH */ "新しいページを作成します。";
$ccms['lang']['backend']['managemenu'] = /* BABELFISH */ "メニューを管理する";
$ccms['lang']['backend']['managefiles'] = /* BABELFISH */ "現在のページを管理する";
$ccms['lang']['backend']['delete'] = /* BABELFISH */ "削除";
$ccms['lang']['backend']['toplevel'] = /* BABELFISH */ "トップレベル";
$ccms['lang']['backend']['sublevel'] = /* BABELFISH */ "下位レベル";
$ccms['lang']['backend']['active'] = /* BABELFISH */ "アクティブ";
$ccms['lang']['backend']['disabled'] = /* BABELFISH */ "バリアフリー";
$ccms['lang']['backend']['template'] = /* BABELFISH */ "テンプレート";
$ccms['lang']['backend']['notinmenu'] = /* BABELFISH */ "項目ではないメニューで";
$ccms['lang']['backend']['menutitle'] = /* BABELFISH */ "メニュー";
$ccms['lang']['backend']['linktitle'] = /* BABELFISH */ "リンク";
$ccms['lang']['backend']['item'] = /* BABELFISH */ "アイテム";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "なし";
$ccms['lang']['backend']['yes'] = /* BABELFISH */ "はい";
$ccms['lang']['backend']['no'] = /* BABELFISH */ "いいえ";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "翻訳";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "こんにちは";
$ccms['lang']['backend']['logout'] = /* BABELFISH */ "ログアウト";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "フォーラムを参照してください";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>最後に使用する有効なユーザ名とバックCompactCMSのパスワードを得るためのアクセスに設定してください。 場合に戻り、あなたが到着したミスで、ここ <a href='admin/includes/'>のホームページ</a>。</p> <p>あなたの詳細については、ウェブマスターにお問い合わせください。</p>";
$ccms['lang']['login']['username'] = /* BABELFISH */ "ユーザー名";
$ccms['lang']['login']['password'] = /* BABELFISH */ "パスワード";
$ccms['lang']['login']['login'] = /* BABELFISH */ "ログイン";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "ユーザーの資格情報を入力してください";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "ユーザー名とパスワードの両方を入力してください";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "あなたのユーザー名を入力してください";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "パスワードを入力してください";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "このアカウントは無効になっています";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "あなたは既に複数の試みをしたことに注意してください";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "不適切なユーザー名またはパスワード";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = /* BABELFISH */ "メイン";
$ccms['lang']['menu']['2'] = /* BABELFISH */ "左";
$ccms['lang']['menu']['3'] = /* BABELFISH */ "右";
$ccms['lang']['menu']['4'] = /* BABELFISH */ "フッター";
$ccms['lang']['menu']['5'] = /* BABELFISH */ "エキストラ";

// Administration form related texts
$ccms['lang']['forms']['filename'] = /* BABELFISH */ "ファイル名";
$ccms['lang']['forms']['pagetitle'] = /* BABELFISH */ "ページタイトル";
$ccms['lang']['forms']['subheader'] = /* BABELFISH */ "サブヘッダ";
$ccms['lang']['forms']['description'] = /* BABELFISH */ "概要";
$ccms['lang']['forms']['module'] = /* BABELFISH */ "モジュール";
$ccms['lang']['forms']['contentitem'] = /* BABELFISH */ "コンテンツアイテム（デフォルト）";
$ccms['lang']['forms']['additions'] = /* BABELFISH */ "追加";
$ccms['lang']['forms']['printable'] = /* BABELFISH */ "印刷可能な";
$ccms['lang']['forms']['published'] = /* BABELFISH */ "アクティブ";
$ccms['lang']['forms']['iscoding'] = /* BABELFISH */ "コーディング";
$ccms['lang']['forms']['createbutton'] = /* BABELFISH */ "作成！";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "変更";
$ccms['lang']['forms']['savebutton'] = /* BABELFISH */ "名前を付けて保存";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "フロントエンドの言語";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "今我々は、少なくとも、このテキストをここにあるページを表示している";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "編集またはフィルタを削除";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "のためのフィルタを追加";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = /* BABELFISH */ "ページのURL（home.html）で :: このページは時と呼ばれているファイル名（なしhtmlです。）";
$ccms['lang']['hints']['pagetitle'] = /* BABELFISH */ "このページのタイトル（ホーム） :: このページの短い説明タイトル。";
$ccms['lang']['hints']['subheader'] = /* BABELFISH */ "短いヘッダテキスト（ようこそ、私たちのサイトへ） :: 各ページのタイトルだけでなく、同様に各ページのヘッダーで使用される短い説明テキスト。";
$ccms['lang']['hints']['description'] = /* BABELFISH */ "メタの説明 :: ページのメタ記述として使用されるこのページに固有の説明を入力します。";
$ccms['lang']['hints']['module'] = /* BABELFISH */ "モジュール :: どのモジュールこのファイルの内容を処理する必要がありますを選択します。 不明な点がある場合は、デフォルトを選択してください。";
$ccms['lang']['hints']['printable'] = /* BABELFISH */ "ページの印刷 :: 選択された'YES'を印刷可能なページが生成されます。 は'NO'は写真や他のメディアとページを選択する必要があります。 これらは、オフに印刷可能なページ無いにこしたことはない。";
$ccms['lang']['hints']['published'] = /* BABELFISH */ "公開状態 :: それはサイトマップにリストされているでしょうか、それは一般に公開されるかどうかを選択すると、このページが公開される必要がある場合。";
$ccms['lang']['hints']['toplevel'] = /* BABELFISH */ "トップレベル :: を指定しますこのメニュー項目の最上位レベル。";
$ccms['lang']['hints']['sublevel'] = /* BABELFISH */ "サブレベルは :: を選択0のとき、このアイテムが最上位レベルの項目になります。 それは特定のトップレベルのサブ項目である場合は、適切なサブレベルを選択してください。";
$ccms['lang']['hints']['template'] = /* BABELFISH */ "テンプレートには、 :: あなたは複数のインストール用のテンプレートを使用する場合は、別の任命ことができます使用して個々のページテンプレートをこのドロップダウン。";
$ccms['lang']['hints']['activelink'] = /* BABELFISH */ "メニューのアクティブなリンク？ :: すべてのアイテムは、常に実際のリンクが必要です。 フロントエンドメニューで、この項目は、チェックを外してその下にチェックボックスの背後にあるリンクを無効にするには。";
$ccms['lang']['hints']['menuid'] = /* BABELFISH */ "メニューには、 :: どのメニューで、この項目はデフォルトのリストに表示されるを選んでくださいまた、ホームページのリンクが表示されるはずですメインメニュー（1）です。";
$ccms['lang']['hints']['iscoding'] = /* BABELFISH */ "コードを含みます :: このファイルは手動では、PHPやJavaScriptなどのコードを追加して含まれていますか？ 選択すると、[はい]をバックエンドのWYSIWYGエディタからファイルへのアクセスを制限され、コードエディタを可能にします。";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br><br>あなたがクリックすると <span class='sprite livefilter livefilter_active'> 、フィルタアイコン Enterキーを押しますと、'ホーム'のアイコンをクリックするときにフィールドに表示される編集を入力するときに、追加、編集、または例えば、削除す るには、上のリストをページのテキストをフィルタのタイトルを左折/ homeを'で、この列を返す'ページが持っており、テキストキーのみ、表示されます。 <br>もう一度アイコンをクリックすると、編集フィールド内のテキストを削除し、/ ReturnキーをEnterキーを押すと、するフィルタを削除してください。<br>フィルタアイコンの上にマウスを動かすと、どのフィルタテキストを使用して、そして、その列は、現在フィルタリングされているかどうかもしそうなら確認してください。";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = /* BABELFISH */ "エディタを閉じます";
$ccms['lang']['editor']['editorfor'] = /* BABELFISH */ "テキストエディタ";
$ccms['lang']['editor']['instruction'] = /* BABELFISH */ "エディタ、現在のファイルを変更するには、以下を使用します。 後は、[完了している直接ワールドワイドウェブへの変更を公開するには、以下のボタンを'変更を保存'を押してください。 また、検索エンジンoptimalizationのための10関連性の高いキーワードに追加します。";
$ccms['lang']['editor']['savebtn'] = /* BABELFISH */ "変更を保存する";
$ccms['lang']['editor']['cancelbtn'] = /* BABELFISH */ "キャンセル";
$ccms['lang']['editor']['confirmclose'] = /* BABELFISH */ "このウィンドウを閉じて、変更を破棄？";
$ccms['lang']['editor']['preview'] = /* BABELFISH */ "プレビュー結果ページ";
$ccms['lang']['editor']['savesuccess'] = /* BABELFISH */ "<strong>成功！</strong> 保存にされている以下のようにコンテンツ";
$ccms['lang']['editor']['backeditor'] = /* BABELFISH */ "戻るエディタに";
$ccms['lang']['editor']['closewindow'] = /* BABELFISH */ "ウィンドウを閉じる";
$ccms['lang']['editor']['keywords'] = /* BABELFISH */ "キーワード- <em>カンマで区切って、最大250文字</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "オートは、安全なパスワードを生成する";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "現在のアカウントレベルでは、この機能を使用することを許可しません。";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = /* BABELFISH */ "新規作成バックアップ";
$ccms['lang']['backup']['explain'] = /* BABELFISH */ "データによる損失の可能性を防ぐためにどのような外部イベントには、定期的にファイルのアップバックを作成するのが賢明ですし。";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "警告は :: あなたのことに注意してください。 <dfn>lightboxの</dfn> アルバム'イメージが <strong>ない</strong> バックアップの説明は！アルバム <strong>です</strong>が、画像は、自分自身とサムネイルがいる <strong>バックアップこれらに含まれていない</strong>との。バックアップをするには場合は、それらは、協議する必要がありますあなたは追加のバックアップシステムは、サイト管理者は、約バックアップをするのに役立ちますやコレクションを復元するには（おそらく大規模な）ファイル。";
$ccms['lang']['backup']['currenthd'] = /* BABELFISH */ "バックアップがあります";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "バックアップファイル名";
$ccms['lang']['backup']['download'] = /* BABELFISH */ "ダウンロードアーカイブ";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "バックアップが作成されているので、しばらくお待ちください...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "ユーザーを作成します。";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "概要CCMSのユーザー";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "ユーザーの個人情報を編集する";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "ユーザーのパスワードを編集";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "アカウントの設定";
$ccms['lang']['users']['user'] = /* BABELFISH */ "ユーザー";
$ccms['lang']['users']['username'] = /* BABELFISH */ "ユーザー名";
$ccms['lang']['users']['name'] = /* BABELFISH */ "名";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "ファーストネーム";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "姓";
$ccms['lang']['users']['password'] = /* BABELFISH */ "パスワード";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "パスワードの確認";
$ccms['lang']['users']['email'] = /* BABELFISH */ "Eメール";
$ccms['lang']['users']['active'] = /* BABELFISH */ "アクティブ";
$ccms['lang']['users']['level'] = /* BABELFISH */ "レベル";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "ユーザーレベル";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "最後のログ";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "管理テンプレートの";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "現在のテンプレートは <strong>ない</strong> 書き込み";
$ccms['lang']['template']['print'] = /* BABELFISH */ "プリント";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "パーミッションの設定";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "テーブルの最小値、ユーザーのレベルが特定の機能を使用することができますかを指定するには、以下を使用します。 指定された最小必要なユーザーレベルより下のすべてのユーザーは、表示されませんや機能にアクセスすることができます。";
$ccms['lang']['permission']['target'] = /* BABELFISH */ "ターゲット";
$ccms['lang']['permission']['level1'] = /* BABELFISH */ "レベル1 - ユーザー";
$ccms['lang']['permission']['level2'] = /* BABELFISH */ "レベル2 - エディタ";
$ccms['lang']['permission']['level3'] = /* BABELFISH */ "レベル3 - マネージャー";
$ccms['lang']['permission']['level4'] = /* BABELFISH */ "レベル4 - 管理者";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "コンテンツ所有者";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "ここでは、個々のユーザーに特定のページの所有者を任命することができます。 特定のページには、ユーザーが選択されている場合、誰もがページを変更することができます。 それ以外の場合のみ指定されたユーザーがファイルへの変更権限を持っていた。 管理者は、常にすべてのファイルにアクセスすることができます。";
$ccms['lang']['owners']['pages'] = /* BABELFISH */ "ページ";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "ユーザー";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "翻訳";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "翻訳文字列が表示されます。";

// Album messages
$ccms['lang']['album']['album'] = /* BABELFISH */ "アルバム";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "アルバムの設定";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "具体的にはこのアルバムを適用する";
$ccms['lang']['album']['description'] = /* BABELFISH */ "アルバムの説明";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "現在のアルバム";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "コンテンツをアップロード";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "アップロードアルバムを既存の";
$ccms['lang']['album']['upload'] = /* BABELFISH */ "アップロードを開始";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "参照ファイルの";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "クリアリスト";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>単一ファイルのアップロード</strong><br> <p>フラッシュローダは初期化に失敗しました。 makeは、必ずJavascriptを有効にされているFlashがインストールされている。 単一ファイルのアップロードが可能ですが、最適化されていません。</p>";
$ccms['lang']['album']['manage'] = /* BABELFISH */ "アルバムを管理する";
$ccms['lang']['album']['albumlist'] = /* BABELFISH */ "アルバム一覧";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "再生成は、すべてのサムネイル";
$ccms['lang']['album']['newalbum'] = /* BABELFISH */ "新規アルバム";
$ccms['lang']['album']['noalbums'] = /* BABELFISH */ "アルバムはまだ作成されている！";
$ccms['lang']['album']['files'] = /* BABELFISH */ "ファイル";
$ccms['lang']['album']['nodir'] = /* BABELFISH */ "ディレクトリを確認してくださいする <strong>アルバムが</strong> 存在します。/メディア/ディレクトリ";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "変更された最後の";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "しばらくお待ちください...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "現在のニュース項目を管理する";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "ニュースを見る";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "新しい記事をお書きください。";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "設定を管理する";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "ニュースをお書きください。";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "フロントエンド＃メッセージ";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "地図を作成";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "表示発行日";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "唯一のお誘いを表示";
$ccms['lang']['news']['title'] = /* BABELFISH */ "ニュースの見出し";
$ccms['lang']['news']['author'] = /* BABELFISH */ "ニュース著者";
$ccms['lang']['news']['date'] = /* BABELFISH */ "日付";
$ccms['lang']['news']['published'] = /* BABELFISH */ "公開？";
$ccms['lang']['news']['teaser'] = /* BABELFISH */ "難問";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "資料の内容";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "友達をアーカイブ";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = /* BABELFISH */ "いいえ反応はまだ投稿されている！";
$ccms['lang']['guestbook']['verinstr'] = /* BABELFISH */ "このメッセージは自動化されていないことを確認するには、再度入力してください。";
$ccms['lang']['guestbook']['reaction'] = /* BABELFISH */ "反応";
$ccms['lang']['guestbook']['rating'] = /* BABELFISH */ "評価";
$ccms['lang']['guestbook']['avatar'] = /* BABELFISH */ "Gravatar.comユーザのアバター";
$ccms['lang']['guestbook']['wrote'] = /* BABELFISH */ "書き込み";
$ccms['lang']['guestbook']['manage'] = /* BABELFISH */ "反応を管理する";
$ccms['lang']['guestbook']['delentry'] = /* BABELFISH */ "削除このエントリ";
$ccms['lang']['guestbook']['sendmail'] = /* BABELFISH */ "電子メールの著者";
$ccms['lang']['guestbook']['removed'] = /* BABELFISH */ "データベースから削除されています。";
$ccms['lang']['guestbook']['name'] = /* BABELFISH */ "あなたの名前";
$ccms['lang']['guestbook']['email'] = /* BABELFISH */ "あなたのe - mail";
$ccms['lang']['guestbook']['website'] = /* BABELFISH */ "あなたのウェブサイト";
$ccms['lang']['guestbook']['comments'] = /* BABELFISH */ "コメント";
$ccms['lang']['guestbook']['verify'] = /* BABELFISH */ "検証";
$ccms['lang']['guestbook']['preview'] = /* BABELFISH */ "プレビューコメント";
$ccms['lang']['guestbook']['add'] = /* BABELFISH */ "コメントを追加";
$ccms['lang']['guestbook']['posted'] = /* BABELFISH */ "あなたのコメントが投稿されています！";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "ありがとうございます";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "障害＆拒否の";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "あなたのコメントは拒否されました。";


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
$ccms['lang']['backend']['js_loading']          = "The browser is still loading several files from the server; please refrain from any activity while this initial loading process completes, thank you.";
$ccms['lang']['backend']['reload_admin_screen'] = "Refresh the admininistration screen";
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
$ccms['lang']['system']['error_rec_exists']     = "Entry already exists in the database.";
       
      /*
         ----------------------------------------------------------
      */
	  
?>
