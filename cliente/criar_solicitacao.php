<?php
session_start();
include '../sql/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricao = $_POST['descricao'];
    $criticidade = $_POST['criticidade'];
    $data_abertura = date('Y-m-d H:i:s'); 
    $colaborador_id = !empty($_POST['colaborador_id']) ? $_POST['colaborador_id'] : null; 
    $cliente_id = !empty($_POST['cliente_id']) ? $_POST['cliente_id'] : null; 

    $sql = "INSERT INTO solicitacao (id_cliente_solicitacao, descricao_solicitacao, criticidade_solicitacao, data_abertura_solicitacao, status_solicitacao, id_colaborador_responsavel_solicitacao)
            VALUES ('$cliente_id', '$descricao', '$criticidade', '$data_abertura', 'pendente',".($colaborador_id ? "'$colaborador_id'" : "NULL").")";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Solicitacao criada com sucesso!</p>";
    } else {
        echo "<p>Erro ao criar o solicitacao: " . $conn->error . "</p>";
    }
}

$colaboradores = $conn->query("SELECT id_colaborador, nome_colaborador FROM colaborador");
$clientes = $conn->query("SELECT id_cliente, nome_cliente, ultimo_nome_cliente FROM cliente");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar solicitacao</title>
</head>
<body>
    <h1>Criar solicitacao</h1>
    <form method="POST" action="criar_solicitacao.php">

        <label for="descricao">Descrição do Problema:</label>
        <textarea name="descricao" id="descricao" required></textarea>

        <label for="criticidade">Urgência:</label>
        <select name="criticidade" id="criticidade" required>
            <option value="baixa">Baixa</option>
            <option value="media">Média</option>
            <option value="alta">Alta</option>
        </select>

        <label for="colaborador_id">Colaborador Responsável (opcional):</label>
        <select name="colaborador_id" id="colaborador_id">
            <option value="">Selecione um colaborador</option>
            <?php while ($colaborador = $colaboradores->fetch_assoc()): ?>
                <option value="<?= $colaborador['id_colaborador']; ?>"><?= $colaborador['nome_colaborador']; ?></option>
            <?php endwhile; ?>
        </select>
        
        <label for="cliente_id">Cliente:</label>
        <select name="cliente_id" id="cliente_id">
            <option value="">Selecione um cliente</option>
            <?php while ($cliente = $clientes->fetch_assoc()): ?>
                <option value="<?= $cliente['id_cliente']; ?>"><?= $cliente['nome_cliente']; ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Criar solicitacao</button>
    </form>
    <p>Já acabou por aqui? <a href="cliente.php"><button>Voltar</button></a></p>
</body>
</html>
