<?php
    ob_start();
    session_start();
    require_once 'classes/Empresa.php';
    // Proteção da página (opcional, remova os comentários '//' se for usar)
    if (!isset($_SESSION['empresaLogado']) || $_SESSION['empresaLogado'] !== true) {
        header('Location: login-empresa.php');
        exit;
    }
    $id = $_GET['id'] ?? $_SESSION['empresa_id'];
    $empresa = new Empresa();
    //Buscar vagas cadastradas pela empresa
    $resposta = $empresa->buscarVagas();

    $vagas = [];

    if($resposta ['status'] === 200 && isset($resposta['data'])){
        $vagas = $resposta['data']['vagas'];
    }
    
    // Buscar candidatos das vagas
    $resposta = $empresa->buscarCandidatos();

    $candidatos = [];
    if($resposta ['status'] === 200 && isset($resposta['data'])){
        $candidatos = $resposta['data']['candidaturas'];
    }

    // Buscar candidatos das vagas
    $resposta = $empresa->buscarEstagiarios();

    $estagiarios = [];
    if($resposta ['status'] === 200 && isset($resposta['data'])){
        $estagiarios = $resposta['data']['contratos'];
    }


    //cadastrar vagas
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $hoje = date('Y-m-d');
    $data_abertura   = date('Y-m-d', strtotime($_POST['data_abertura']));
    $data_fechamento = date('Y-m-d', strtotime($_POST['data_fechamento']));

    if($data_abertura < $hoje){
        $mensagem = "A data de abertura não pode ser anterior ao dia atual.";
        $tipo_msg = "erro";
    } elseif($data_fechamento < $hoje){
        $mensagem = "A data de fechamento não pode ser anterior ao dia atual.";
        $tipo_msg = "erro";
    } elseif($data_fechamento <= $data_abertura){
        $mensagem = "A data de fechamento deve ser posterior à data de abertura.";
        $tipo_msg = "erro";
    } else {
        $res = $empresa->cadastrarVagar($_POST['vaga'], $data_abertura, $data_fechamento, $_POST['requisitos']);
            //var_dump($res);
            //die();
        if($empresa->resHttp($res)){
            $mensagem = "Vaga cadastrada com sucesso!";
            $tipo_msg = "sucesso";    
        }else{
            $mensagem = "Algo deu errado, tente novamente mais tarde.";
            $tipo_msg = "erro";
        }
    }
}

    //var_dump($candidatos);
    //die();
    ob_end_flush();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Empresa - UniALFA</title>
    <link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>">
    <script src="js/script.js" defer></script>
</head>

<body>

    <div class="dashboard-container">

        <header class="top-header">
            <div class="logo-area"><img src="img/unialfa.png" alt="Logo UniALFA" class="logo-main"></div>
            <div class="user-profile">Bem vindo, Empresa</div>
        </header>

        <nav class="sidebar">
            <ul>
                <li><a href="#" class="tab-link active" onclick="alternarAba(event, 'painel-geral')">Painel Geral</a></li>
                <li><a href="#" class="tab-link" onclick="alternarAba(event, 'minhas-vagas')">Mostrar suas vagas</a></li>
                <li><a href="#" class="tab-link" onclick="alternarAba(event, 'ver-candidatos')">Candidatos</a></li>
                <li><a href="#" class="tab-link" onclick="alternarAba(event, 'estagios-andamento')">Relatório de Estágios</a></li>
                <li class="menu-sair"><a href="index.php">Sair</a></li>
            </ul>
        </nav>

        <main class="main-content">

            <div id="painel-geral" class="tab-content active">
                <div class="hero-banner">
                    <h2>Painel Corporativo - UniALFA</h2>
                    <p>Gerencie seus processos seletivos e encontre os melhores talentos.</p>
                    <button class="btn-anunciar-vaga" onclick="alternarAba(event, 'criar-vaga-tela')">
                        Anunciar Nova Vaga de Estágio
                    </button>
                </div>

                <div class="content-grid">
                    <div class="card-box">
                        <h3>Estagiários Qualificados</h3>
                        <p style="color: #000000;">Ambiente perfeito para encontrar o estagiário qualificado que você precisa.</p>
                    </div>

                    <div class="card-box">
                        <h3>Informativos Rápidos</h3>
                        <p style="color: #000000;">Lembre-se de validar os relatórios de estágio dos seus alunos contratados até o final do mês corrente.</p>
                    </div>
                </div>
            </div>

            <div id="minhas-vagas" class="tab-content">
                <div class="card-box">
                    <h3>Vagas Abertas</h3>
                    <p style="color: #666; margin-bottom: 20px;">Acompanhe as oportunidades que a sua organização disponibilizou no portal:</p>

                    <?php if(empty($vagas)):?>
                        <p>Não há Vagas</p>
                    <?php else: ?>
                        <?php foreach($vagas as $vaga): ?>                
                            <div class="item-lista-painel">
                                <div>
                                    <strong style="font-size: 1.1rem; color: #115c74;">Vaga: <?=$vaga['vaga_cargo'] ?></strong>
                                    <p style="color: #555; margin-top: 5px;">Data de Fechamento: <?= date('d/m/Y', strtotime($vaga['vaga_data_fechamento'])) ?> 
                                    | Requsitos: <?=$vaga['vaga_requisitos'] ?>
                                </p>
                                </div>
                                <?php if($vaga['vaga_vaga_preenchida'] == 1): ?>
                                    <span class="badge status-recusado">Vaga Preenchida</span>
                                <?php else: ?>
                                    <span class="badge status-aprovado">Recebendo Currículos</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>   
                </div>
            </div>

            <div id="ver-candidatos" class="tab-content">
                <div class="card-box">
                    <h3>Alunos Candidatados às Vagas</h3>
                    <p style="color: #666; margin-bottom: 15px;">Analise os perfis e currículos dos estudantes inscritos nas suas posições:</p>

                    <table class="tabela-padrao">
                        <thead>
                            <tr>
                                <th>Nome do Aluno</th>
                                <th>Curso</th>
                                <th>Vaga Pretendida</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($candidatos)): ?>
                                <p>Sem candidatos!</p>
                            <?php else: ?>
                                <?php foreach($candidatos as $candidato): ?>
                                    <tr>
                                        <td><?= $candidato['nome']?></td>
                                        <td><?= $candidato['curso']?></td>
                                        <td><?= $candidato['cargo']?></td>
                                        <td>
                                            <button class="btn-acao-primario" onclick="alert('Aluno Contratado com sucesso!')">Contratar</button>
                                        </td>
                                    </tr> 
                                <?php endforeach; ?>
                            <?php endif; ?>   
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="estagios-andamento" class="tab-content">
                <div class="card-box">
                    <h3>Contratos de Estágio Ativos</h3>
                    <p style="color: #666; margin-bottom: 15px;">Estudantes atualmente vinculados e ativos na sua organização:</p>

                    <table class="tabela-padrao">
                        <thead>
                            <tr>
                                <th>Estagiário</th>
                                <th>Vaga / Cargo</th>
                                <th>Data de Início</th>
                                <th>Data Fim</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($candidatos)): ?>
                                <p>Sem candidatos!</p>
                            <?php else: ?>
                                <?php foreach($estagiarios as $estagiario): ?>
                                    <tr>
                                        <td><?= $estagiario['nome']?></td>
                                        <td><?= $estagiario['cargo']?></td>
                                        <td><?= $estagiario['date_inicio']?></td>
                                        <td><?= $estagiario['data_fim']?></td>
                                    </tr> 
                                <?php endforeach; ?>
                            <?php endif; ?>   
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="criar-vaga-tela" class="tab-content">
                <div class="card-box">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #F1F5F9; padding-bottom: 15px; margin-bottom: 25px;">
                        <h3 style="margin-bottom: 0; border-bottom: none;">Criar Vaga de Estágio</h3>

                        <button onclick="alternarAba(event, 'painel-geral')" style="background: transparent; border: 1px solid #000; padding: 6px 12px; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 0.85rem;">
                            ← Voltar ao Painel
                        </button>
                    </div>

                    <p style="color: #475569; margin-bottom: 25px; font-size: 0.95rem;">
                        Insira as informações básicas do estágio. Os campos com asterisco (*) são obrigatórios para a publicação.
                    </p>

                    <form method="POST" style="width: 100%;">
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">

                            <div>
                                <label style="display: block; font-family: 'Montserrat', sans-serif; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">
                                    Título da Vaga / Cargo *
                                </label>
                                <input type="text" name="vaga" placeholder="Ex: Estágio em Desenvolvimento Web" required
                                    style="width: 100%; padding: 12px 14px; border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.95rem; background-color: #FFFFFF;">
                            </div>

                           <?php $hoje = date('Y-m-d'); ?>
                            <div>
                                <label style="display: block; font-family: 'Montserrat', sans-serif; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">
                                    Data de Abertura *
                                </label>
                                <input type="date" name="data_abertura" required min="<?= $hoje ?>"
                                    style="width: 100%; padding: 12px 14px; border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.95rem; background-color: #FFFFFF;">
                            </div>

                            <div>
                                <label style="display: block; font-family: 'Montserrat', sans-serif; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">
                                    Data de Fechamento *
                                </label>
                                <input type="date" name="data_fechamento" required min="<?= $hoje ?>"
                                    style="width: 100%; padding: 12px 14px; border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.95rem; background-color: #FFFFFF;">
                            </div>

                        </div>
                        
                        
                        <div style="margin-bottom: 30px;">
                            <label style="display: block; font-family: 'Montserrat', sans-serif; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">
                                Requisitos e Qualificações Desejadas *
                            </label>
                            <textarea name="requisitos" rows="6" required
                                placeholder="Descreva as competências técnicas e comportamentais esperadas.&#10;Exemplo:&#10;- Conhecimento em lógica de programação e banco de dados.&#10;- Boa comunicação e trabalho em equipe.&#10;- Estar cursando a partir do 2º período."
                                style="width: 100%; padding: 14px 16px; border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.95rem; line-height: 1.6; resize: vertical; background-color: #FFFFFF;"></textarea>
                        </div>

                        <div style="display: flex; gap: 12px;">
                            <button type="submit" class="btn-principal" style="background-color: #22E836;">
                                Publicar Vaga Oficialmente
                            </button>
                            <button type="button" onclick="alternarAba(event, 'painel-geral')" class="btn-acao-secundario">
                                Cancelar
                            </button>
                        </div>
                    </form>
                    <?php if(!empty($mensagem)): ?>
                        <div class="msg-alerta-<?= $tipo_msg ?>">
                            <?= htmlspecialchars($mensagem) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </main>
    </div>

</body>

</html>