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
$ccms['lang']['system']['error_database'] = /* BABELFISH */ "无法连接到数据库。 请确认您的登录信息和数据库名称。";
$ccms['lang']['system']['error_openfile'] = /* BABELFISH */ "无法打开指定的文件";
$ccms['lang']['system']['error_notemplate'] = /* BABELFISH */ "没有模板可以发现被应用到您的网站。 请至少添加一个模板。/ lib中/模板/。";
$ccms['lang']['system']['error_templatedir'] = /* BABELFISH */ "找不到模板目录！ 请确保它存在并且包含至少一个模板。";
$ccms['lang']['system']['error_write'] = /* BABELFISH */ "文件没有写权限";
$ccms['lang']['system']['error_dirwrite'] = /* BABELFISH */ "目录没有写权限";
$ccms['lang']['system']['error_chmod'] = /* BABELFISH */ "目前的文件无法被修改。 检查在/内容目录（666）文件权限。";
$ccms['lang']['system']['error_value'] = /* BABELFISH */ "错误：不正确的值";
$ccms['lang']['system']['error_default'] = /* BABELFISH */ "默认页不能被删除。";
$ccms['lang']['system']['error_forged'] = /* BABELFISH */ "价值已被篡改";
$ccms['lang']['system']['error_filedots'] = /* BABELFISH */ "文件名称不能包含点，例如'。html的'。";
$ccms['lang']['system']['error_filesize'] = /* BABELFISH */ "文件名应至少3个字符长。";
$ccms['lang']['system']['error_filesize_2'] = /* BABELFISH */ "文件名应该是最多50个字符长。";
$ccms['lang']['system']['error_pagetitle'] = /* BABELFISH */ "输入3个字符或多个页面的标题。";
$ccms['lang']['system']['error_pagetitle_2'] = /* BABELFISH */ "输入100个字符或更少的页面标题。";
$ccms['lang']['system']['error_subtitle'] = /* BABELFISH */ "给您一个短页面标题。";
$ccms['lang']['system']['error_subtitle_2'] = /* BABELFISH */ "输入一个简短200字子为您的网页少所有权。";
$ccms['lang']['system']['error_description'] = /* BABELFISH */ "输入超过3个字符的说明";
$ccms['lang']['system']['error_description_2'] = /* BABELFISH */ "输入少于250个字符的描述";
$ccms['lang']['system']['error_reserved'] = /* BABELFISH */ "您所指定的文件名称保留供内部使用。";
$ccms['lang']['system']['error_general'] = /* BABELFISH */ "发生错误";
$ccms['lang']['system']['error_correct'] = /* BABELFISH */ "请更正如下：";
$ccms['lang']['system']['error_create'] = /* BABELFISH */ "填写错误而创建的新文件";
$ccms['lang']['system']['error_exists'] = /* BABELFISH */ "您指定的文件名已存在。";
$ccms['lang']['system']['error_delete'] = /* BABELFISH */ "错误而完成对所选的文件删除";
$ccms['lang']['system']['error_selection'] = /* BABELFISH */ "目前还没有选定项。";
$ccms['lang']['system']['error_versioninfo'] = /* BABELFISH */ "没有版本信息可用。";
$ccms['lang']['system']['error_misconfig'] = /* BABELFISH */ "<strong>似乎有一个配置错误。</strong><br>请确认。htaccess文件，以反映正确配置您的文件结构。 如果你有<br>在一个子目录中安装CompactCMS，然后调整。htaccess文件相应。";
$ccms['lang']['system']['error_deleted'] = /* BABELFISH */ "<h1>您选择的文件似乎被删除</h1> <p>刷新文件清单，看看可用的文件的最新清单，以防止这种情况发生的错误。 如果这没有解决这个错误，手动检查该文件你试图打开文件夹的内容。</p>";
$ccms['lang']['system']['error_404title'] = /* BABELFISH */ "文件未找到";
$ccms['lang']['system']['error_404header'] = /* BABELFISH */ "404错误发生时，被请求文件无法找到。";
$ccms['lang']['system']['error_404content'] = /* BABELFISH */ "所请求的文件 <strong>{%pagereq%}.html</strong> 找不到。";
$ccms['lang']['system']['error_403title'] = /* BABELFISH */ "禁止";
$ccms['lang']['system']['error_403header'] = /* BABELFISH */ "一个403错误：您没有权限访问请求的文件。";
$ccms['lang']['system']['error_403content'] = /* BABELFISH */ "您没有允许访问 <strong>{%pagereq%}.html</strong> 这一刻。";
$ccms['lang']['system']['error_sitemap'] = /* BABELFISH */ "对所有页面的目录";
$ccms['lang']['system']['error_tooshort'] = /* BABELFISH */ "一个或多个提交的值要么太短或不正确";
$ccms['lang']['system']['error_passshort'] = /* BABELFISH */ "密码应该包含超过6个字符";
$ccms['lang']['system']['error_passnequal'] = /* BABELFISH */ "输入的密码不匹配";
$ccms['lang']['system']['noresults'] = /* BABELFISH */ "无结果";
$ccms['lang']['system']['tooriginal'] = /* BABELFISH */ "回到原";
$ccms['lang']['system']['message_rights'] = /* BABELFISH */ "保留所有权利";
$ccms['lang']['system']['message_compatible'] = /* BABELFISH */ "测试成功";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = /* BABELFISH */ "有建议，意见或有问题？ 访问 <a class='external' title='Visit the official forum' rel='external' href='http://community.compactcms.nl/forum/'>论坛的</a>！";
$ccms['lang']['backend']['ordertip'] = /* BABELFISH */ "在下拉列表下面来反映您的网页在您的网站的菜单结构。 请注意，系统没有考虑到重复的顶部或分层次组合。";
$ccms['lang']['backend']['createtip'] = /* BABELFISH */ "要创建一个新的页面，填写下面的表单和一个新的页面会为你创建在飞。 之后该文件已被创建，你就可以像往常一样编辑页面。";
$ccms['lang']['backend']['currentfiles'] = /* BABELFISH */ "在下面的清单，你会发现，目前出版的所有网页。 您会注意到，文件的默认页面无法被删除，因为它是您的网站目前的网页。 其他文件可能有限制的访问，因为管理员对这些文件的唯一所有权。";
$ccms['lang']['backend']['confirmdelete'] = /* BABELFISH */ "请确认您要删除选定的项目（）。";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "请确认您要重新生成所有的缩略图。";
$ccms['lang']['backend']['settingssaved'] = /* BABELFISH */ "您的变更已成功保存。";
$ccms['lang']['backend']['must_refresh'] = /* BABELFISH */ "请务必重新载入主网页看到 <strong>所有</strong> 的更改";
$ccms['lang']['backend']['itemcreated'] = /* BABELFISH */ "成功地处理所提交的项目（）。";
$ccms['lang']['backend']['fullremoved'] = /* BABELFISH */ "成功删除所选的项目（）。";
$ccms['lang']['backend']['fullregenerated'] = /* BABELFISH */ "成功再生的缩略图。";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "返回概览";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "CCMS系统的权限设置";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "内容所有者定义";
$ccms['lang']['backend']['templateeditor'] = /* BABELFISH */ "模板编辑器";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "用户管理";
$ccms['lang']['backend']['changevalue'] = /* BABELFISH */ "点击改变";
$ccms['lang']['backend']['previewpage'] = /* BABELFISH */ "预览";
$ccms['lang']['backend']['editpage'] = /* BABELFISH */ "编辑";
$ccms['lang']['backend']['restrictpage'] = /* BABELFISH */ "限制";
$ccms['lang']['backend']['newfiledone'] = /* BABELFISH */ "这个文件是新鲜和干净为您填写了！";
$ccms['lang']['backend']['newfilecreated'] = /* BABELFISH */ "该文件已创建";
$ccms['lang']['backend']['startedittitle'] = /* BABELFISH */ "开始编辑！";
$ccms['lang']['backend']['starteditbody'] = /* BABELFISH */ "新的文件已创建。 马上开始修改或其中添加更多的页面或当前的管理。";
$ccms['lang']['backend']['success'] = /* BABELFISH */ "成功！";
$ccms['lang']['backend']['fileexists'] = /* BABELFISH */ "文件已存在";
$ccms['lang']['backend']['statusdelete'] = /* BABELFISH */ "删除选定的现状：";
$ccms['lang']['backend']['statusremoved'] = /* BABELFISH */ "删除";
$ccms['lang']['backend']['uptodate'] = /* BABELFISH */ "最新的。";
$ccms['lang']['backend']['outofdate'] = /* BABELFISH */ "已经过时。";
$ccms['lang']['backend']['considerupdate'] = /* BABELFISH */ "考虑更新";
$ccms['lang']['backend']['orderprefsaved'] = /* BABELFISH */ "为您的菜单项的顺序您的使用偏好已保存。";
$ccms['lang']['backend']['inmenu'] = /* BABELFISH */ "在菜单";
$ccms['lang']['backend']['updatelist'] = /* BABELFISH */ "更新文件清单";
$ccms['lang']['backend']['administration'] = /* BABELFISH */ "政府当局";
$ccms['lang']['backend']['currentversion'] = /* BABELFISH */ "您当前运行的版本";
$ccms['lang']['backend']['mostrecent'] = /* BABELFISH */ "最新的稳定版本是CompactCMS";
$ccms['lang']['backend']['versionstatus'] = /* BABELFISH */ "您的安装";
$ccms['lang']['backend']['createpage'] = /* BABELFISH */ "创建一个新的一页";
$ccms['lang']['backend']['managemenu'] = /* BABELFISH */ "管理菜单";
$ccms['lang']['backend']['managefiles'] = /* BABELFISH */ "管理现有的网页";
$ccms['lang']['backend']['delete'] = /* BABELFISH */ "删除";
$ccms['lang']['backend']['toplevel'] = /* BABELFISH */ "顶层";
$ccms['lang']['backend']['sublevel'] = /* BABELFISH */ "分层次";
$ccms['lang']['backend']['active'] = /* BABELFISH */ "主动";
$ccms['lang']['backend']['disabled'] = /* BABELFISH */ "残疾人士";
$ccms['lang']['backend']['template'] = /* BABELFISH */ "模板";
$ccms['lang']['backend']['notinmenu'] = /* BABELFISH */ "在菜单项不";
$ccms['lang']['backend']['menutitle'] = /* BABELFISH */ "菜单";
$ccms['lang']['backend']['linktitle'] = /* BABELFISH */ "链接";
$ccms['lang']['backend']['item'] = /* BABELFISH */ "项目";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "无";
$ccms['lang']['backend']['yes'] = /* BABELFISH */ "是的";
$ccms['lang']['backend']['no'] = /* BABELFISH */ "无";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "翻译";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "你好";
$ccms['lang']['backend']['logout'] = /* BABELFISH */ "登出";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "见论坛";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>使用有效的用户名和密码才能进入CompactCMS后端。 如果您是通过错误，返回到 <a href='admin/includes/'>首页</a>。</p> <p>您的详细资料请联系您的网站管理员。</p>";
$ccms['lang']['login']['username'] = /* BABELFISH */ "用户名";
$ccms['lang']['login']['password'] = /* BABELFISH */ "密码";
$ccms['lang']['login']['login'] = /* BABELFISH */ "登录";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "请提供您的用户凭据";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "请输入您的用户名和密码都";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "请输入您的用户名";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "输入您的密码";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "此帐户已被停用";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "请注意，你已经作出多次尝试";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "用户名或密码不正确";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = /* BABELFISH */ "主";
$ccms['lang']['menu']['2'] = /* BABELFISH */ "左";
$ccms['lang']['menu']['3'] = /* BABELFISH */ "权";
$ccms['lang']['menu']['4'] = /* BABELFISH */ "页脚";
$ccms['lang']['menu']['5'] = /* BABELFISH */ "额外";

// Administration form related texts
$ccms['lang']['forms']['filename'] = /* BABELFISH */ "文件名";
$ccms['lang']['forms']['pagetitle'] = /* BABELFISH */ "网页标题";
$ccms['lang']['forms']['subheader'] = /* BABELFISH */ "副标题";
$ccms['lang']['forms']['description'] = /* BABELFISH */ "说明";
$ccms['lang']['forms']['module'] = /* BABELFISH */ "模块";
$ccms['lang']['forms']['contentitem'] = /* BABELFISH */ "内容项（默认）";
$ccms['lang']['forms']['additions'] = /* BABELFISH */ "添置";
$ccms['lang']['forms']['printable'] = /* BABELFISH */ "可打印";
$ccms['lang']['forms']['published'] = /* BABELFISH */ "主动";
$ccms['lang']['forms']['iscoding'] = /* BABELFISH */ "编码";
$ccms['lang']['forms']['createbutton'] = /* BABELFISH */ "创造！";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "修改";
$ccms['lang']['forms']['savebutton'] = /* BABELFISH */ "保存";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "前端语言";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "现在我们只是显示网页，而至少有这样的文字在这里";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "编辑或删除过滤器";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "添加过滤器";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = /* BABELFISH */ "该网页网址（home.html） :: 文件名称，此网页呼吁（没有HTML）。";
$ccms['lang']['hints']['pagetitle'] = /* BABELFISH */ "这个页面的标题（首页） :: 此页的简短描述性标题。";
$ccms['lang']['hints']['subheader'] = /* BABELFISH */ "短头文本（欢迎到我们的网站） :: 一个简短的描述文本中使用的每一页头以及在每一页的标题为。";
$ccms['lang']['hints']['description'] = /* BABELFISH */ "元描述 :: 此将作为页面的元描述使用的页面独特的描述。";
$ccms['lang']['hints']['module'] = /* BABELFISH */ "模块 :: 选择什么模块应该处理此文件的内容。 如果你不确定，选择默认。";
$ccms['lang']['hints']['printable'] = /* BABELFISH */ "页印刷 :: 当选择'是'一个可打印页面生成。 '否'应该与图片或其他媒体的页面选择。 这些都是最好没有一个可打印页面关闭。";
$ccms['lang']['hints']['published'] = /* BABELFISH */ "发布状态 :: 选择是否应该公布这个页面，如果它会被列在Sitemaps中，是否会向公众开放。";
$ccms['lang']['hints']['toplevel'] = /* BABELFISH */ "顶层 :: 指定该菜单项的最高水平。";
$ccms['lang']['hints']['sublevel'] = /* BABELFISH */ "分层次 :: 请选择0时，这个项目应该是一个顶级项目。 如果它是一个为某顶级子项，请选择级别相应的子。";
$ccms['lang']['hints']['template'] = /* BABELFISH */ "模板 :: 如果您为您的安装使用多个模板，您可以指定每个单独的页面模板来使用这个下拉。";
$ccms['lang']['hints']['activelink'] = /* BABELFISH */ "主动联系在菜单吗？ :: 不是所有的项目都需要一个实际的链接。 要关闭这背后的前端菜单项，取消其复选框下面的链接。";
$ccms['lang']['hints']['menuid'] = /* BABELFISH */ "菜单 :: 选择在哪个菜单这个项目应该是英寸默认列出的是主菜单（1），主页上的链接也应该显示。";
$ccms['lang']['hints']['iscoding'] = /* BABELFISH */ "包含编码 :: 这个文件包含手动添加如PHP或JavaScript代码？ 选择'是'将限制对从后端的所见即所得的编辑器文件，使代码编辑器。";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br><br>您可以点击 <span class='sprite livefilter livefilter_active'> 过滤器图标 在左侧的标题来添加，编辑或删除一个文本过滤页面列表，例如当你输入'家'在编辑字段时出现点击图标，然后按Enter /返回键，此栏的网页文字'家'，其中有将被显示。 <br>再次单击该图标，并删除在编辑字段中的文本，然后按Enter / Return键，将删除过滤器。<br>悬停在过滤器图标，看看是否列目前正在筛选，如果是这样，用它过滤的文本。";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = /* BABELFISH */ "关闭编辑器";
$ccms['lang']['editor']['editorfor'] = /* BABELFISH */ "文本编辑器";
$ccms['lang']['editor']['instruction'] = /* BABELFISH */ "使用下面的编辑器来修改当前文件。 一旦您完成后，点击“保存更改”按钮，直接发布你的修改到万维网。 同时添加到搜索引擎optimalization十个相关的关键字。";
$ccms['lang']['editor']['savebtn'] = /* BABELFISH */ "保存更改";
$ccms['lang']['editor']['cancelbtn'] = /* BABELFISH */ "取消";
$ccms['lang']['editor']['confirmclose'] = /* BABELFISH */ "关闭此窗口，并放弃任何改变吗？";
$ccms['lang']['editor']['preview'] = /* BABELFISH */ "预览结果页";
$ccms['lang']['editor']['savesuccess'] = /* BABELFISH */ "<strong>成功！</strong> 的内容如下所示保存已";
$ccms['lang']['editor']['backeditor'] = /* BABELFISH */ "返回到编辑器";
$ccms['lang']['editor']['closewindow'] = /* BABELFISH */ "关闭窗口";
$ccms['lang']['editor']['keywords'] = /* BABELFISH */ "关键字- <em>逗号分隔，最多250个字符</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "自动生成安全密码";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "您当前的帐户级别不允许您使用此功能。";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = /* BABELFISH */ "创建新的后备";
$ccms['lang']['backup']['explain'] = /* BABELFISH */ "为了防止可能的数据，由于外部事件的任何损失，这是明智的创建备份您的文件起坐定期。";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "警告 :: 请注意您的 <dfn>收藏夹</dfn> 相册的图片是 <strong>不</strong> 备份的说明！这张专辑 <strong>的</strong>，但图像的缩略图是他们自己和他们 <strong>不包括在这些备份</strong>的。如果你想备份的，那么你就需要赋予您的备份系统有关的其他网站管理员，帮助您备份和恢复这些（可能大）文件的集合。";
$ccms['lang']['backup']['currenthd'] = /* BABELFISH */ "可用的后备";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "备份文件的名称";
$ccms['lang']['backup']['download'] = /* BABELFISH */ "下载档案";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "请稍候，正在创建的备份...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "创建一个用户";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "概述CCMS系统用户";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "编辑用户的个人资料";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "编辑用户的密码";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "帐户设置";
$ccms['lang']['users']['user'] = /* BABELFISH */ "用户";
$ccms['lang']['users']['username'] = /* BABELFISH */ "用户名";
$ccms['lang']['users']['name'] = /* BABELFISH */ "名称";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "名字";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "姓";
$ccms['lang']['users']['password'] = /* BABELFISH */ "密码";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "确认密码";
$ccms['lang']['users']['email'] = /* BABELFISH */ "电子邮件";
$ccms['lang']['users']['active'] = /* BABELFISH */ "主动";
$ccms['lang']['users']['level'] = /* BABELFISH */ "级别";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "用户级";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "最近的日志";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "管理模板";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "当前模板是 <strong>不</strong> 写";
$ccms['lang']['template']['print'] = /* BABELFISH */ "打印";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "许可喜好";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "使用下表来指定用户的最低水平可以使用某些功能。 任何低于规定的最低要求用户级别的用户，不会看，也有机会获得该功能。";
$ccms['lang']['permission']['target'] = /* BABELFISH */ "目标";
$ccms['lang']['permission']['level1'] = /* BABELFISH */ "等级1 - 使用";
$ccms['lang']['permission']['level2'] = /* BABELFISH */ "2级 - 编者";
$ccms['lang']['permission']['level3'] = /* BABELFISH */ "3级 - 经理";
$ccms['lang']['permission']['level4'] = /* BABELFISH */ "等级4 - 管理";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "内容所有者";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "在这里您可以指定特定的网页的所有权个人用户。 如果一个特定的页面没有用户选择，每个人都可以修改页面。 否则，只有指定的用户必须修改权限的文件。 管理员始终可以访问所有文件。";
$ccms['lang']['owners']['pages'] = /* BABELFISH */ "页面";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "用户";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "翻译";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "显示翻译的字符串。";

// Album messages
$ccms['lang']['album']['album'] = /* BABELFISH */ "相册";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "相册设置";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "具体适用这张专辑";
$ccms['lang']['album']['description'] = /* BABELFISH */ "专辑介绍";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "当前相册";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "上载内容";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "上传到现有相册";
$ccms['lang']['album']['upload'] = /* BABELFISH */ "开始上传";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "浏览档案";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "清除列表";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>单文件上传</strong><br> <p>该Flash装载初始化失败。 确保启用了JavaScript和Flash的安装位置。 单文件上传是可能的，但没有得到优化。</p>";
$ccms['lang']['album']['manage'] = /* BABELFISH */ "管理相册";
$ccms['lang']['album']['albumlist'] = /* BABELFISH */ "相册列表";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "重新生成所有的缩略图";
$ccms['lang']['album']['newalbum'] = /* BABELFISH */ "创建新专辑";
$ccms['lang']['album']['noalbums'] = /* BABELFISH */ "没有相册被建立！";
$ccms['lang']['album']['files'] = /* BABELFISH */ "文件";
$ccms['lang']['album']['nodir'] = /* BABELFISH */ "请确保目录 <strong>专辑</strong> 的存在。/媒体/目录";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "最后修改";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "请稍候...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "管理现有新闻项目";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "新增新闻";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "写新文章";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "管理设置";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "写新闻";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "在前端＃消息";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "只看该作者";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "出版日期显示";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "只显示传情";
$ccms['lang']['news']['title'] = /* BABELFISH */ "新闻标题";
$ccms['lang']['news']['author'] = /* BABELFISH */ "新闻作家";
$ccms['lang']['news']['date'] = /* BABELFISH */ "日期";
$ccms['lang']['news']['published'] = /* BABELFISH */ "公布？";
$ccms['lang']['news']['teaser'] = /* BABELFISH */ "预告";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "文章内容";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "查看存档";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = /* BABELFISH */ "没有反应，已张贴呢！";
$ccms['lang']['guestbook']['verinstr'] = /* BABELFISH */ "要检查这消息不是自动的，请重新输入";
$ccms['lang']['guestbook']['reaction'] = /* BABELFISH */ "反应";
$ccms['lang']['guestbook']['rating'] = /* BABELFISH */ "评分";
$ccms['lang']['guestbook']['avatar'] = /* BABELFISH */ "Gravatar.com用户头像";
$ccms['lang']['guestbook']['wrote'] = /* BABELFISH */ "说";
$ccms['lang']['guestbook']['manage'] = /* BABELFISH */ "管理反应";
$ccms['lang']['guestbook']['delentry'] = /* BABELFISH */ "删除此记录";
$ccms['lang']['guestbook']['sendmail'] = /* BABELFISH */ "电子邮件提交";
$ccms['lang']['guestbook']['removed'] = /* BABELFISH */ "已经从数据库中删除。";
$ccms['lang']['guestbook']['name'] = /* BABELFISH */ "你的名字";
$ccms['lang']['guestbook']['email'] = /* BABELFISH */ "您的电子邮件";
$ccms['lang']['guestbook']['website'] = /* BABELFISH */ "您的网站";
$ccms['lang']['guestbook']['comments'] = /* BABELFISH */ "评论";
$ccms['lang']['guestbook']['verify'] = /* BABELFISH */ "验证";
$ccms['lang']['guestbook']['preview'] = /* BABELFISH */ "预览评论";
$ccms['lang']['guestbook']['add'] = /* BABELFISH */ "加入您的评论";
$ccms['lang']['guestbook']['posted'] = /* BABELFISH */ "您的评论已发布！";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "谢谢";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "故障及拒收";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "您的评论已被拒绝。";


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
$ccms['lang']['system']['home']                 = "Home";
       
      /*
         ----------------------------------------------------------
      */
	  
?>
