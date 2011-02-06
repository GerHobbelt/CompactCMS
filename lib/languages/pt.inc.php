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
$ccms['lang']['system']['error_database'] = /* BABELFISH */ "Não foi possível conectar ao banco de dados. Por favor, verifique seus dados de login e nome do banco.";
$ccms['lang']['system']['error_openfile'] = /* BABELFISH */ "Não é possível abrir o arquivo especificado";
$ccms['lang']['system']['error_notemplate'] = /* BABELFISH */ "Nenhum modelo pode ser encontrado para ser aplicado ao seu site. Por favor, adicione pelo menos um modelo para. / Lib / templates /.";
$ccms['lang']['system']['error_templatedir'] = /* BABELFISH */ "Não foi possível encontrar o diretório de templates! Certifique-se que existe e contém pelo menos um modelo.";
$ccms['lang']['system']['error_write'] = /* BABELFISH */ "Arquivo não tem acesso de gravação";
$ccms['lang']['system']['error_dirwrite'] = /* BABELFISH */ "Lista não tem acesso de gravação";
$ccms['lang']['system']['error_chmod'] = /* BABELFISH */ "O arquivo atual não poderia ser modificado. Verifique as permissões nos arquivos no diretório / conteúdo (666).";
$ccms['lang']['system']['error_value'] = /* BABELFISH */ "Erro: o valor incorreto";
$ccms['lang']['system']['error_default'] = /* BABELFISH */ "Página padrão não pode ser excluída.";
$ccms['lang']['system']['error_forged'] = /* BABELFISH */ "Valor foi adulterado";
$ccms['lang']['system']['error_filedots'] = /* BABELFISH */ "O nome do arquivo não deve conter pontos, por exemplo. Html '.";
$ccms['lang']['system']['error_filesize'] = /* BABELFISH */ "O nome do arquivo deve ser no mínimo 3 caracteres.";
$ccms['lang']['system']['error_filesize_2'] = /* BABELFISH */ "O nome do arquivo deve ser no máximo 50 caracteres.";
$ccms['lang']['system']['error_pagetitle'] = /* BABELFISH */ "Introduza um título da página 3 caracteres ou mais.";
$ccms['lang']['system']['error_pagetitle_2'] = /* BABELFISH */ "Introduza um título da página de 100 caracteres ou menos.";
$ccms['lang']['system']['error_subtitle'] = /* BABELFISH */ "Dê um pequeno sub-título para sua página.";
$ccms['lang']['system']['error_subtitle_2'] = /* BABELFISH */ "Digite um pequeno sub-título de 200 caracteres ou menos para sua página.";
$ccms['lang']['system']['error_description'] = /* BABELFISH */ "Digite uma descrição de mais de 3 caracteres";
$ccms['lang']['system']['error_description_2'] = /* BABELFISH */ "Digite uma descrição de menos de 250 caracteres";
$ccms['lang']['system']['error_reserved'] = /* BABELFISH */ "Você especificou um nome de arquivo reservado para uso interno.";
$ccms['lang']['system']['error_general'] = /* BABELFISH */ "Ocorreu um erro";
$ccms['lang']['system']['error_correct'] = /* BABELFISH */ "Corrija os seguintes elementos:";
$ccms['lang']['system']['error_create'] = /* BABELFISH */ "Erro ao concluir a criação do novo arquivo";
$ccms['lang']['system']['error_exists'] = /* BABELFISH */ "O nome de arquivo especificado já existe.";
$ccms['lang']['system']['error_delete'] = /* BABELFISH */ "Erro ao completar exclusão do arquivo selecionado";
$ccms['lang']['system']['error_selection'] = /* BABELFISH */ "Não foram os itens selecionados.";
$ccms['lang']['system']['error_versioninfo'] = /* BABELFISH */ "Não há informações de versão está disponível.";
$ccms['lang']['system']['error_misconfig'] = /* BABELFISH */ "<strong>Parece haver um erro de configuração.</strong><br>Por favor, verifique se o arquivo htaccess. Está configurado corretamente para refletir a estrutura do arquivo. Se você tem<br>CompactCMS instalado em um subdiretório, em seguida, ajuste o arquivo. Htaccess em conformidade.";
$ccms['lang']['system']['error_deleted'] = /* BABELFISH */ "<h1>O arquivo selecionado parece ser excluída</h1> <p>Atualizar a lista de arquivos para ver a lista mais recente dos arquivos disponíveis para evitar este erro aconteça. Se isso não resolver esse erro, verificar manualmente a pasta de conteúdo para o arquivo que você está tentando abrir.</p>";
$ccms['lang']['system']['error_404title'] = /* BABELFISH */ "Arquivo não encontrado";
$ccms['lang']['system']['error_404header'] = /* BABELFISH */ "Um erro 404 ocorreu, o arquivo solicitado não pôde ser encontrado.";
$ccms['lang']['system']['error_404content'] = /* BABELFISH */ "O arquivo solicitado <strong>{%pagereq%}.html</strong> não pôde ser encontrado.";
$ccms['lang']['system']['error_403title'] = /* BABELFISH */ "Proibido";
$ccms['lang']['system']['error_403header'] = /* BABELFISH */ "Ocorreu um erro 403: você não tem permissão para acessar o arquivo solicitado.";
$ccms['lang']['system']['error_403content'] = /* BABELFISH */ "Você não tem permissão para acessar <strong>{%pagereq%}.html</strong> neste momento.";
$ccms['lang']['system']['error_sitemap'] = /* BABELFISH */ "Uma visão geral de todas as páginas";
$ccms['lang']['system']['error_tooshort'] = /* BABELFISH */ "Um ou vários valores apresentados foram muito curta ou incorretos";
$ccms['lang']['system']['error_passshort'] = /* BABELFISH */ "A senha deve conter mais de 6 caracteres";
$ccms['lang']['system']['error_passnequal'] = /* BABELFISH */ "As senhas digitadas não encontrou";
$ccms['lang']['system']['noresults'] = /* BABELFISH */ "Não há resultados";
$ccms['lang']['system']['tooriginal'] = /* BABELFISH */ "Voltar ao original";
$ccms['lang']['system']['message_rights'] = /* BABELFISH */ "Todos os direitos reservados";
$ccms['lang']['system']['message_compatible'] = /* BABELFISH */ "Testado com sucesso em";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = /* BABELFISH */ "Tem sugestões, comentários ou tendo problemas? Visite <a class='external' title='Visit the official forum' rel='external' href='http://community.compactcms.nl/forum/'>o fórum</a>!";
$ccms['lang']['backend']['ordertip'] = /* BABELFISH */ "Use o drop-downs abaixo para refletir a estrutura de suas páginas no menu de seus sites. Esteja ciente de que o sistema não leva em conta duplicada combinações de nível superior ou secundário.";
$ccms['lang']['backend']['createtip'] = /* BABELFISH */ "Para criar uma nova página, preencha o formulário abaixo e uma nova página será criada por você na mosca. Depois que o arquivo foi criado, você será capaz de editar a página, como de costume.";
$ccms['lang']['backend']['currentfiles'] = /* BABELFISH */ "Na listagem abaixo você encontrará todas as páginas que estão publicados. Você verá que a página padrão de arquivo não pode ser excluído, porque é a página atual do seu site. Outros arquivos podem ter acesso restrito porque o administrador tem a propriedade exclusiva sobre esses arquivos.";
$ccms['lang']['backend']['confirmdelete'] = /* BABELFISH */ "Por favor, confirme que você deseja excluir o item selecionado (s).";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "Por favor, confirme que você quer recuperar todas as miniaturas.";
$ccms['lang']['backend']['settingssaved'] = /* BABELFISH */ "Suas alterações foram salvas com sucesso.";
$ccms['lang']['backend']['must_refresh'] = /* BABELFISH */ "Por favor, certifique-se de recarregar a página principal para ver <strong>todas as</strong> suas alterações";
$ccms['lang']['backend']['itemcreated'] = /* BABELFISH */ "Processado com sucesso o item apresentado (s).";
$ccms['lang']['backend']['fullremoved'] = /* BABELFISH */ "Excluído com sucesso o item selecionado (s).";
$ccms['lang']['backend']['fullregenerated'] = /* BABELFISH */ "Regenerados com êxito as miniaturas.";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "Voltar à vista geral";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "Definir permissões CCMS";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "Definir os proprietários de conteúdo";
$ccms['lang']['backend']['templateeditor'] = /* BABELFISH */ "editor de modelo";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "O gerenciamento de usuários";
$ccms['lang']['backend']['changevalue'] = /* BABELFISH */ "Clique para mudar";
$ccms['lang']['backend']['previewpage'] = "Preview";
$ccms['lang']['backend']['editpage'] = /* BABELFISH */ "Editar";
$ccms['lang']['backend']['restrictpage'] = /* BABELFISH */ "Restritos";
$ccms['lang']['backend']['newfiledone'] = /* BABELFISH */ "Este arquivo é fresco e limpo para que você preencha-up!";
$ccms['lang']['backend']['newfilecreated'] = /* BABELFISH */ "O arquivo foi criado";
$ccms['lang']['backend']['startedittitle'] = /* BABELFISH */ "Início da edição!";
$ccms['lang']['backend']['starteditbody'] = /* BABELFISH */ "O novo arquivo foi criado. Início da edição imediatamente ou quer adicionar mais páginas ou gerenciar os atuais.";
$ccms['lang']['backend']['success'] = /* BABELFISH */ "Sucesso!";
$ccms['lang']['backend']['fileexists'] = /* BABELFISH */ "O arquivo já existe";
$ccms['lang']['backend']['statusdelete'] = /* BABELFISH */ "Status da supressão selecionado:";
$ccms['lang']['backend']['statusremoved'] = /* BABELFISH */ "removidas";
$ccms['lang']['backend']['uptodate'] = /* BABELFISH */ "até à data.";
$ccms['lang']['backend']['outofdate'] = /* BABELFISH */ "desatualizado.";
$ccms['lang']['backend']['considerupdate'] = /* BABELFISH */ "Considere a atualização";
$ccms['lang']['backend']['orderprefsaved'] = /* BABELFISH */ "Suas preferências para a ordem dos seus itens de menu foram salvas.";
$ccms['lang']['backend']['inmenu'] = /* BABELFISH */ "No menu de";
$ccms['lang']['backend']['updatelist'] = /* BABELFISH */ "Atualizar a lista de arquivos";
$ccms['lang']['backend']['administration'] = /* BABELFISH */ "Administração";
$ccms['lang']['backend']['currentversion'] = /* BABELFISH */ "Você está executando a versão";
$ccms['lang']['backend']['mostrecent'] = /* BABELFISH */ "A mais recente versão estável é CompactCMS";
$ccms['lang']['backend']['versionstatus'] = /* BABELFISH */ "Sua instalação é";
$ccms['lang']['backend']['createpage'] = /* BABELFISH */ "Criar uma nova página";
$ccms['lang']['backend']['managemenu'] = /* BABELFISH */ "Gerenciar menu";
$ccms['lang']['backend']['managefiles'] = /* BABELFISH */ "Gerenciar páginas atuais";
$ccms['lang']['backend']['delete'] = /* BABELFISH */ "Excluir";
$ccms['lang']['backend']['toplevel'] = /* BABELFISH */ "de nível superior";
$ccms['lang']['backend']['sublevel'] = /* BABELFISH */ "Sub-nível";
$ccms['lang']['backend']['active'] = "Active";
$ccms['lang']['backend']['disabled'] = /* BABELFISH */ "Pessoas com mobilidade condicionada";
$ccms['lang']['backend']['template'] = /* BABELFISH */ "Modelo";
$ccms['lang']['backend']['notinmenu'] = /* BABELFISH */ "Item não em um menu";
$ccms['lang']['backend']['menutitle'] = "Menu";
$ccms['lang']['backend']['linktitle'] = "Link";
$ccms['lang']['backend']['item'] = "Item";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "Nenhum";
$ccms['lang']['backend']['yes'] = /* BABELFISH */ "Sim";
$ccms['lang']['backend']['no'] = /* BABELFISH */ "Não";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "Traduções";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "Oi";
$ccms['lang']['backend']['logout'] = "Log-out";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "Ver fórum";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>Use um nome de usuário e senha válidos para ter acesso ao back-end CompactCMS. Se você chegou aqui por engano, volte à <a href='admin/includes/'>página inicial</a>.</p> <p>Contacte o webmaster para os seus detalhes.</p>";
$ccms['lang']['login']['username'] = /* BABELFISH */ "Nome de Usuário";
$ccms['lang']['login']['password'] = /* BABELFISH */ "Senha";
$ccms['lang']['login']['login'] = "Login";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "Por favor, forneça as credenciais de usuário";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "Digite um username e senha";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "Digite seu nome de usuário";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "Digite sua senha";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "Esta conta foi desativada";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "Observe que você já fez várias tentativas";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "Nome de usuário incorreto ou senha";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = /* BABELFISH */ "Principal";
$ccms['lang']['menu']['2'] = /* BABELFISH */ "Esquerda";
$ccms['lang']['menu']['3'] = /* BABELFISH */ "Direito";
$ccms['lang']['menu']['4'] = /* BABELFISH */ "Rodapé";
$ccms['lang']['menu']['5'] = "Extra";

// Administration form related texts
$ccms['lang']['forms']['filename'] = /* BABELFISH */ "O nome do arquivo";
$ccms['lang']['forms']['pagetitle'] = /* BABELFISH */ "Título da página";
$ccms['lang']['forms']['subheader'] = "Subheader";
$ccms['lang']['forms']['description'] = /* BABELFISH */ "Descrição";
$ccms['lang']['forms']['module'] = /* BABELFISH */ "Módulo";
$ccms['lang']['forms']['contentitem'] = /* BABELFISH */ "item de conteúdo (padrão)";
$ccms['lang']['forms']['additions'] = /* BABELFISH */ "Adições";
$ccms['lang']['forms']['printable'] = /* BABELFISH */ "Impressão";
$ccms['lang']['forms']['published'] = "Active";
$ccms['lang']['forms']['iscoding'] = /* BABELFISH */ "Codificação";
$ccms['lang']['forms']['createbutton'] = /* BABELFISH */ "Criar!";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "Modificar";
$ccms['lang']['forms']['savebutton'] = /* BABELFISH */ "Guardar";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "Front-end língua";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "agora nós estamos apenas mostrando páginas que tenham pelo menos esse texto aqui";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "Editar ou remover filtro para";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "Adicionar filtro para";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = /* BABELFISH */ "A URL da página (home.html): O nome do arquivo que esta página é chamado (sem html).";
$ccms['lang']['hints']['pagetitle'] = /* BABELFISH */ "Título para esta página (Home): Um título curto e descritivo para esta página.";
$ccms['lang']['hints']['subheader'] = /* BABELFISH */ "texto do cabeçalho curto (Bem-vindo ao nosso site) :: Um pequeno texto descritivo usado no cabeçalho de cada página, assim como no título de cada página.";
$ccms['lang']['hints']['description'] = /* BABELFISH */ "Meta descrição: uma descrição única para esta página, que será utilizada como descrição das páginas meta.";
$ccms['lang']['hints']['module'] = /* BABELFISH */ "Módulo :: Selecione o módulo deve lidar com o conteúdo desse arquivo. Se você estiver inseguro, selecione o padrão.";
$ccms['lang']['hints']['printable'] = /* BABELFISH */ "Página printability: Quando selecionado 'SIM' para imprimir uma página é gerada. 'NÃO' deve ser selecionado para páginas com fotos ou outras mídias. Estes são melhor sem uma página impressa.";
$ccms['lang']['hints']['published'] = /* BABELFISH */ "Publicado status: Selecione se esta página devem ser publicadas, se ele vai ser listada no mapa do site e se será acessível ao público.";
$ccms['lang']['hints']['toplevel'] = /* BABELFISH */ "Nível superior :: Especifique o nível mais alto para este item de menu.";
$ccms['lang']['hints']['sublevel'] = /* BABELFISH */ "Sub-nível: Selecione 0 quando este item deve ser um item de nível superior. Se for um item do sub para um determinado nível superior, selecione o nível de sub-adequadas.";
$ccms['lang']['hints']['template'] = /* BABELFISH */ "Modelo: Se você usar vários modelos para a sua instalação, você pode apontar padrões distintos para cada página que utilizem este drop-down.";
$ccms['lang']['hints']['activelink'] = /* BABELFISH */ "Active link no menu? :: Nem todos os itens sempre precisam de uma ligação efectiva. Para desactivar a ligação por trás desse item no menu de front-end, desmarque a caixa de seleção abaixo.";
$ccms['lang']['hints']['menuid'] = /* BABELFISH */ "Menu :: Escolha no menu que este item deve estar listada dentro O padrão é o menu principal (1), onde também o link da página inicial deve ser mostrada.";
$ccms['lang']['hints']['iscoding'] = /* BABELFISH */ "Contém a codificação: Será que este arquivo contém manual adicionou o código como PHP ou Javascript? Ao selecionar 'Sim' vai restringir o acesso ao arquivo do editor de back-end de WYSIWYG e permite que o editor de código.";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br><br>Você pode clicar sobre o <span class='sprite livefilter livefilter_active'> ícone do filtro à esquerda do título de adicionar, editar ou remover um texto para filtrar a lista de páginas sobre, por exemplo, quando você 'tipo' casa no campo de edição que aparece quando você clica no ícone, em seguida, pressione a tecla Enter / chave de retorno, apenas as páginas que têm em casa ', o texto' nesta coluna será exibida. <br>Clicando no ícone de novo e apagar o texto no campo de edição, pressionando a tecla Enter / Return, irá remover o filtro.<br>Passe o mouse sobre o ícone do filtro para ver se a coluna está sendo filtrada, e em caso afirmativo, qual o texto usando o filtro.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = /* BABELFISH */ "Feche o editor";
$ccms['lang']['editor']['editorfor'] = /* BABELFISH */ "Editor de texto por";
$ccms['lang']['editor']['instruction'] = /* BABELFISH */ "Utilize o editor abaixo para modificar o arquivo atual. Quando estiver pronto, clique em 'Salvar alterações' botão abaixo para publicar diretamente suas modificações para a world wide web. Também adicionar até dez palavras-chave relevantes para a Optimização do Search Engine.";
$ccms['lang']['editor']['savebtn'] = /* BABELFISH */ "Salvar alterações";
$ccms['lang']['editor']['cancelbtn'] = /* BABELFISH */ "Cancelar";
$ccms['lang']['editor']['confirmclose'] = /* BABELFISH */ "Fechar esta janela e descartar todas as alterações?";
$ccms['lang']['editor']['preview'] = /* BABELFISH */ "Pré-visualizar a página do resultado";
$ccms['lang']['editor']['savesuccess'] = /* BABELFISH */ "<strong>Sucesso!</strong> O conteúdo, como mostrado abaixo foi guardado";
$ccms['lang']['editor']['backeditor'] = /* BABELFISH */ "Voltar ao editor";
$ccms['lang']['editor']['closewindow'] = /* BABELFISH */ "Fechar a janela";
$ccms['lang']['editor']['keywords'] = /* BABELFISH */ "Palavras-chave - <em>separados por vírgulas, no máximo 250 caracteres</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "Auto gerar uma senha segura";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "Seu nível de conta corrente não permitem que você use esse recurso.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = /* BABELFISH */ "Criar uma nova back-up";
$ccms['lang']['backup']['explain'] = /* BABELFISH */ "Para evitar uma possível perda de dados devido a qualquer evento externo, é aconselhável criar back-ups de seus arquivos regularmente.";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "Atenção: Lembre-se que o seu <dfn>lightbox</dfn> 'imagens álbuns são <strong>não</strong> apoiada descrições! O álbum <strong>são</strong>, mas as imagens de si mesmos e suas miniaturas são <strong>não incluídos nesses backups</strong>com. Se você quiser backups desses, então você vai precisar para conferir o administrador do site sobre um sistema de backup adicionais para ajudar você backup e restaurar esses possivelmente grande) coleções de arquivos (.";
$ccms['lang']['backup']['currenthd'] = /* BABELFISH */ "Disponível back-ups";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "nome do arquivo de back-up";
$ccms['lang']['backup']['download'] = /* BABELFISH */ "Baixar arquivo";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "Por favor aguarde enquanto o backup está sendo criado ...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "Criar um usuário";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "visão dos usuários CCMS";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "Editar dados pessoais do usuário";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "Editar a senha do usuário";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "configurações da Conta";
$ccms['lang']['users']['user'] = /* BABELFISH */ "Usuário";
$ccms['lang']['users']['username'] = /* BABELFISH */ "Nome de Usuário";
$ccms['lang']['users']['name'] = /* BABELFISH */ "Nome";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "Primeiro nome";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "Sobrenome";
$ccms['lang']['users']['password'] = /* BABELFISH */ "Senha";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "Confirmar senha";
$ccms['lang']['users']['email'] = "E-mail";
$ccms['lang']['users']['active'] = "Active";
$ccms['lang']['users']['level'] = /* BABELFISH */ "Nível";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "em nível de usuário";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "Última log";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "Gerenciar modelos";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "O modelo atual é <strong>não</strong> gravável";
$ccms['lang']['template']['print'] = /* BABELFISH */ "Imprimir";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "preferências Permissão";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "Use a tabela abaixo para especificar o nível mínimo do usuário pode usar alguns recursos. Qualquer utilizador abaixo do nível mínimo exigido de usuário especificado, não vai ver nem ter acesso ao recurso.";
$ccms['lang']['permission']['target'] = /* BABELFISH */ "Alvo";
$ccms['lang']['permission']['level1'] = /* BABELFISH */ "Nível 1 - Usuário";
$ccms['lang']['permission']['level2'] = /* BABELFISH */ "Nível 2 - Editor";
$ccms['lang']['permission']['level3'] = /* BABELFISH */ "Nível 3 - Gerente";
$ccms['lang']['permission']['level4'] = /* BABELFISH */ "Nível 4 - Admin";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "Os proprietários de conteúdo";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "Aqui você pode apontar a posse página específica para usuários individuais. Se para uma determinada página os usuários não são selecionados, todos podem modificar a página. Caso contrário, somente o usuário especificado tinha os direitos de modificação para o arquivo. Administradores sempre têm acesso a todos os arquivos.";
$ccms['lang']['owners']['pages'] = /* BABELFISH */ "Páginas";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "Usuários";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "Traduzir";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "Mostra as seqüências de tradução.";

// Album messages
$ccms['lang']['album']['album'] = /* BABELFISH */ "Álbum";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "configurações do álbum";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "Especificamente aplicar este álbum";
$ccms['lang']['album']['description'] = /* BABELFISH */ "descrição álbum";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "Álbuns atual";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "Carregar conteúdo";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "Carregar para actuais álbum";
$ccms['lang']['album']['upload'] = /* BABELFISH */ "Iniciar upload";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "Procurar arquivos";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "Limpar lista";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>Único arquivo upload</strong><br> <p>O carregador Flash falhou ao inicializar. Certifique-se o Javascript está habilitado e Flash está instalado. upload de arquivos individuais são possíveis, mas não otimizado.</p>";
$ccms['lang']['album']['manage'] = /* BABELFISH */ "Gerenciar álbum";
$ccms['lang']['album']['albumlist'] = /* BABELFISH */ "Lista de Álbuns";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "Regenerar todas as miniaturas";
$ccms['lang']['album']['newalbum'] = /* BABELFISH */ "Criar novo álbum";
$ccms['lang']['album']['noalbums'] = /* BABELFISH */ "Nenhum álbum foi criado ainda!";
$ccms['lang']['album']['files'] = /* BABELFISH */ "Arquivos";
$ccms['lang']['album']['nodir'] = /* BABELFISH */ "Por favor, verifique se o diretório <strong>álbuns</strong> existente no arquivo. / media / diretório";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "Modificada";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "Por favor, aguarde ...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "Gerencie notícias atuais";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "Adicione notícias";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "Escrever novo artigo";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "Gerenciar configurações";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "Escrever notícias";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "# mensagens no front-end";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "autor Show";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "Mostrar data de publicação";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "Apenas mostrar teaser";
$ccms['lang']['news']['title'] = /* BABELFISH */ "Notícias título";
$ccms['lang']['news']['author'] = /* BABELFISH */ "autor News";
$ccms['lang']['news']['date'] = /* BABELFISH */ "Data";
$ccms['lang']['news']['published'] = /* BABELFISH */ "Publicado?";
$ccms['lang']['news']['teaser'] = "Teaser";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "Conteúdo do artigo";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "Ver arquivo";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = /* BABELFISH */ "Não reacções têm sido publicados ainda!";
$ccms['lang']['guestbook']['verinstr'] = /* BABELFISH */ "Para verificar que esta mensagem não é automática, por favor, re-introduzir";
$ccms['lang']['guestbook']['reaction'] = /* BABELFISH */ "Reação";
$ccms['lang']['guestbook']['rating'] = /* BABELFISH */ "Avaliação";
$ccms['lang']['guestbook']['avatar'] = /* BABELFISH */ "Gravatar.com avatar do usuário";
$ccms['lang']['guestbook']['wrote'] = /* BABELFISH */ "escreveu";
$ccms['lang']['guestbook']['manage'] = /* BABELFISH */ "Gerir as reacções";
$ccms['lang']['guestbook']['delentry'] = /* BABELFISH */ "Excluir esta entrada";
$ccms['lang']['guestbook']['sendmail'] = /* BABELFISH */ "E-mail do autor";
$ccms['lang']['guestbook']['removed'] = /* BABELFISH */ "foi removido do banco de dados.";
$ccms['lang']['guestbook']['name'] = /* BABELFISH */ "Seu nome";
$ccms['lang']['guestbook']['email'] = /* BABELFISH */ "Seu e-mail";
$ccms['lang']['guestbook']['website'] = /* BABELFISH */ "Seu site";
$ccms['lang']['guestbook']['comments'] = /* BABELFISH */ "Comentários";
$ccms['lang']['guestbook']['verify'] = /* BABELFISH */ "Verificação";
$ccms['lang']['guestbook']['preview'] = /* BABELFISH */ "Comentário Preview";
$ccms['lang']['guestbook']['add'] = /* BABELFISH */ "Adicione seu comentário";
$ccms['lang']['guestbook']['posted'] = /* BABELFISH */ "Seu comentário foi postado!";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "Obrigado";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "Falhas e rejeições";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "Seu comentário foi rejeitado.";


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