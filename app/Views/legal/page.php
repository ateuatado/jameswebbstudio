<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<main class="container py-5" style="max-width: 960px; margin-top: 5rem; color: rgba(255,255,255,.82);">
    <h1 class="mb-2" style="color:#c5a059;"><?= esc($heading) ?></h1>
    <p class="text-white-50 mb-5">Última atualização: 30 de setembro de 2026</p>
    <?php if ($page === 'privacy'): ?>
        <h2>1. Quem somos</h2>
        <p>O James Webb Studio é um estúdio de fotografia e fotografia autoral. Esta política explica como tratamos dados pessoais quando você navega pelo site, cria uma conta, acessa a galeria, comenta uma fotografia ou entra em contato conosco.</p>
        <h2>2. Dados que podemos coletar</h2>
        <p>Podemos coletar nome de usuário, e-mail, dados necessários para autenticação, comentários publicados, registros de acesso e informações técnicas do navegador. Ao usar o login Google, recebemos do Google seu identificador da conta, nome, e-mail verificado e, quando disponibilizada, sua foto de perfil.</p>
        <h2>3. Como usamos os dados</h2>
        <p>Usamos esses dados para autenticar usuários, manter a segurança da conta, publicar e administrar comentários, preservar o histórico de navegação das fotografias, responder solicitações e melhorar a experiência e a comunicação comercial do acervo.</p>
        <h2>4. Cookies e histórico de navegação</h2>
        <p>Utilizamos cookies essenciais para sessão e autenticação. Um identificador técnico pode registrar as fotografias visualizadas, inclusive repetições, para compreender o interesse pelas obras. Quando o visitante se autentica, o histórico do navegador pode ser associado à sua conta. Não usamos esse registro para afirmar, sozinho, que houve intenção de compra.</p>
        <h2>5. Compartilhamento e armazenamento</h2>
        <p>Não vendemos dados pessoais. O compartilhamento ocorre somente quando necessário para operar o serviço, cumprir obrigação legal, proteger direitos ou quando você escolhe um provedor de autenticação, como o Google. Mantemos os dados pelo período necessário às finalidades descritas e às obrigações aplicáveis.</p>
        <h2>6. Seus direitos</h2>
        <p>Você pode solicitar confirmação de tratamento, acesso, correção, atualização, eliminação quando aplicável e informações sobre o uso dos seus dados. Para isso, escreva para <a href="mailto:contato@jameswebbstudio.com.br">contato@jameswebbstudio.com.br</a>. Também é possível revogar o acesso do James Webb Studio na sua conta Google.</p>
        <h2>7. Segurança e alterações</h2>
        <p>Adotamos medidas razoáveis de segurança, mas nenhum serviço conectado à internet é absolutamente invulnerável. Esta política pode ser atualizada para refletir mudanças no serviço, na legislação ou nas práticas de tratamento.</p>
    <?php else: ?>
        <h2>1. Aceitação</h2>
        <p>Ao acessar o site James Webb Studio, criar uma conta, utilizar o login Google, comentar ou solicitar informações sobre uma fotografia, você concorda com estes Termos de Serviço e com a <a href="<?= site_url('politica-de-privacidade') ?>">Política de Privacidade</a>.</p>
        <h2>2. Uso do site e da conta</h2>
        <p>Você deve fornecer informações verdadeiras, proteger suas credenciais e usar o site de forma legal e respeitosa. O login Google é fornecido para facilitar o acesso; a autenticação também está sujeita às regras do Google.</p>
        <h2>3. Comentários</h2>
        <p>Você é responsável pelo conteúdo dos comentários que publicar. Não são permitidos conteúdo ilegal, ofensivo, discriminatório, fraudulento, que viole direitos autorais ou que tente explorar a segurança do site. O James Webb Studio pode remover conteúdo e suspender contas que violem estas regras.</p>
        <h2>4. Fotografias e direitos autorais</h2>
        <p>As fotografias, textos, marcas e demais materiais do site pertencem ao James Webb Studio ou aos respectivos titulares. A visualização ou o compartilhamento do link não transfere direitos de reprodução, download, comercialização ou uso público.</p>
        <h2>5. Informações sobre obras</h2>
        <p>As informações, disponibilidade, especificações e valores apresentados no acervo podem ser atualizados. O botão de informação ou contato não constitui, por si só, contrato de compra. Qualquer venda dependerá de confirmação específica das condições da edição.</p>
        <h2>6. Disponibilidade e responsabilidade</h2>
        <p>Buscamos manter o site disponível e correto, mas podem ocorrer interrupções, manutenção, falhas de terceiros ou indisponibilidade de rede. O site não deve ser utilizado para atividades ilícitas ou para armazenar informações que você não tenha autorização para compartilhar.</p>
        <h2>7. Alterações e contato</h2>
        <p>Estes termos podem ser atualizados quando houver mudança relevante no serviço. Dúvidas e solicitações podem ser encaminhadas para <a href="mailto:contato@jameswebbstudio.com.br">contato@jameswebbstudio.com.br</a>.</p>
    <?php endif ?>
</main>
<?= $this->endSection() ?>
