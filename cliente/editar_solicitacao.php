<?php
include '../sql/db_connect.php';

$sql = "SELECT *
        FROM solicitacao";
$result = $conn->query($sql);

$colaboradores = $conn->query("SELECT id_colaborador, nome_colaborador FROM colaborador");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id_solicitacao = $_POST['id_solicitacao'];
    $status = $_POST['status'];
    $colaborador_id = $_POST['colaborador_id'];

    $sqlUpdate = "UPDATE solicitacao 
                  SET status_solicitacao = '$status', 
                      id_colaborador_responsavel_solicitacao = '$colaborador_id' 
                  WHERE id_solicitacao = '$id_solicitacao'";

    if ($conn->query($sqlUpdate) === TRUE) {
        echo "<p>Solicitação atualizada com sucesso!</p>";
    } else {
        echo "<p>Erro ao atualizar: " . $conn->error . "</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && isset($_GET['delete'])) {
    $id_solicitacao = $_GET['id'];

    $sqlDelete = "DELETE FROM solicitacao WHERE id_solicitacao = '$id_solicitacao'";
    if ($conn->query($sqlDelete) === FALSE) {
        echo "Erro ao excluir a solicitação: " . $conn->error;
    } else {
        header("Location: cliente.php"); 
        exit;
    }
}
?>

<section id="table">
    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Descrição</th>
                <th>Criticidade</th>
                <th>Status</th>
                <th>Data de Criação</th>
                <th>Colaborador Responsável</th>
                <th>Ações</th>
            </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <form method='POST' action='editar_solicitacao.php'>
                    <td>{$row['id_solicitacao']} <input type='hidden' name='id_solicitacao' value='{$row['id_solicitacao']}'></td>
                    <td>{$row['descricao_solicitacao']}</td>
                    <td>{$row['criticidade_solicitacao']}</td>
                    <td>
                        <select name='status'>
                            <option value='pendente' " . ($row['status_solicitacao'] == 'pendente' ? 'selected' : '') . ">Pendente</option>
                            <option value='em andamento' " . ($row['status_solicitacao'] == 'em andamento' ? 'selected' : '') . ">Em andamento</option>
                            <option value='resolvido' " . ($row['status_solicitacao'] == 'resolvido' ? 'selected' : '') . ">Resolvido</option>
                        </select>
                    </td>
                    <td>{$row['data_abertura_solicitacao']}</td>
                    <td>
                        <select name='colaborador_id'>";
                            $colaboradores->data_seek(0);
                            while ($colaborador = $colaboradores->fetch_assoc()) {
                                echo "<option value='{$colaborador['id_colaborador']}' " . 
                                     ($row['id_colaborador_responsavel_solicitacao'] == $colaborador['id_colaborador'] ? 'selected' : '') . 
                                     ">{$colaborador['nome_colaborador']}</option>";
                            }
            echo        "</select>
                    </td>
                    <td>
                        <button type='submit' name='update'>Salvar</button>
                        <a href='editar_solicitacao.php?id={$row['id_solicitacao']}&delete=1'>Excluir</a>
                    </td>
                </form>
            </tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum registro encontrado.";
    }
    $conn->close();
    ?>
</section>

<p>Já acabou por aqui? <a href="cliente.php"><button>Voltar</button></a></p>
