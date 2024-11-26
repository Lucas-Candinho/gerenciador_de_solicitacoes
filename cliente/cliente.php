<?php

include '../sql/db_connect.php';

$sql = "SELECT solicitacao.id_solicitacao, solicitacao.descricao_solicitacao, solicitacao.criticidade_solicitacao, solicitacao.status_solicitacao, solicitacao.data_abertura_solicitacao, colaborador.nome_colaborador, colaborador.ultimo_nome_colaborador, cliente.nome_cliente, cliente.ultimo_nome_cliente
        FROM solicitacao
        INNER JOIN colaborador ON colaborador.id_colaborador = solicitacao.id_colaborador_responsavel_solicitacao
        INNER JOIN cliente ON cliente.id_cliente = solicitacao.id_cliente_solicitacao";


$colaboradores = $conn->query("SELECT id_colaborador, nome_colaborador FROM colaborador");

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $colaborador_id = !empty($_POST['colaborador_id']) ? $_POST['colaborador_id'] : null; 
    $criticidade = !empty($_POST['criticidade']) ? $_POST['criticidade'] : null; 
    $status = !empty($_POST['status']) ? $_POST['status'] : null;

    $sql = "SELECT solicitacao.id_solicitacao, solicitacao.descricao_solicitacao, solicitacao.criticidade_solicitacao, solicitacao.status_solicitacao, solicitacao.data_abertura_solicitacao, colaborador.nome_colaborador, colaborador.ultimo_nome_colaborador, cliente.nome_cliente, cliente.ultimo_nome_cliente
        FROM solicitacao
        INNER JOIN colaborador ON colaborador.id_colaborador = solicitacao.id_colaborador_responsavel_solicitacao
        INNER JOIN cliente ON cliente.id_cliente = solicitacao.id_cliente_solicitacao
        WHERE 1=1";

    if ($criticidade !== null) {
        $sql .= " AND solicitacao.criticidade_solicitacao = '$criticidade'";        
    }

    if ($status !== null) {
        $sql .= " AND solicitacao.status_solicitacao = '$status'";        
    }

    if ($colaborador_id !== null) {
        $sql .= " AND solicitacao.id_colaborador_responsavel_solicitacao = $colaborador_id";        
    }

    if ($conn->query($sql) == TRUE) {
        echo "<p>Filtro realizado com sucesso!</p>";
    } else if ($conn->query($sql) == FALSE){
        echo "<p>Sem registros para esse filtro</p>";
    } else {
        echo "<p>Houve um erro: " . $conn->error . "</p>";
        echo $sql;
    }
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo</title>
</head>
<body>
    <h1>Bem-vindo!</h1>
    <div class="button-container">
            <a href="criar_solicitacao.php"><button>Criar solicitacao</button></a>
            <a href="editar_solicitacao.php"><button>Editar solicitacao</button></a>
        </div>
        <br />
    <section id="table">
        <?php

            if ($result->num_rows > 0) {
                echo "<table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Nome do Solicitador</th>
                        <th>Ultimo nome do Solicitador</th>
                        <th>Descrição do solicitacao</th>
                        <th>Urgência do solicitacao</th>
                        <th>Status do solicitacao</th>
                        <th>Data de Criação do solicitacao</th>
                        <th>Nome do Funcionário Responsável</th>
                        <th>Ultimo nome do Funcionário Responsável</th>
                    </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id_solicitacao']}</td>
                            <td>{$row['nome_cliente']}</td>
                            <td>{$row['ultimo_nome_cliente']}</td>
                            <td>{$row['descricao_solicitacao']}</td>
                            <td>{$row['criticidade_solicitacao']}</td>
                            <td>{$row['status_solicitacao']}</td>
                            <td>{$row['data_abertura_solicitacao']}</td>
                            <td>{$row['nome_colaborador']}</td>
                            <td>{$row['ultimo_nome_colaborador']}</td>
                        </tr>";
                }
                echo "</table>";
            } else {
                echo "Nenhum registro encontrado.";
            }
            $conn->close();
        ?>
    </section>
            <br />
    <form method="POST" action="cliente.php">

        <label for="criticidade">Urgência:</label>
        <select name="criticidade" id="criticidade">
            <option value="">Todas</option>
            <option value="baixa">Baixa</option>
            <option value="media">Média</option>
            <option value="alta">Alta</option>
        </select>

        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="">Todos</option>
            <option value="pendente">Pendente</option>
            <option value="em andamento">Em andamento</option>
            <option value="resolvido">Resolvido</option>
        </select>

        <label for="colaborador_id">Colaborador Responsável:</label>
        <select name="colaborador_id" id="colaborador_id">
            <option value="">Todos</option>
            <?php while ($colaborador = $colaboradores->fetch_assoc()): ?>
                <option value="<?= $colaborador['id_colaborador']; ?>"><?= $colaborador['nome_colaborador']; ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Filtrar</button>
    </form>
    <br />
    <a href="../index.php">Sair</a>
</body>
</html>
