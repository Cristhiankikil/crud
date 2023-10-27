<!DOCTYPE html>
<html>
<head>
    <title>Gerenciamento de Usuários</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <?php
    /*
     * Método de conexão sem padrões
    */
    $username = "root";
    $password = "";
    
    try {
        $conn = new PDO('mysql:host=localhost;dbname=cedup', $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $data = $conn->query('SELECT * FROM usuarios');
        echo '<div class="container">';
        echo '<table id="tabela01" class="table table-striped table-bordered">';
        echo ' <thead>';
        echo '<tr><th>Id</th><th>Nome</th><th>Usuario</th><th>Senha</th><th>Cep</th><th>Localidade</th><th>Uf</th><th>Acoes</th></tr>';
        echo ' </thead>';
    
        foreach($data as $row) {
            echo '<tr>';
            echo '<td>' . $row["id"] . '</td>';
            echo '<td>' . $row["nome"] . '</td>';
            echo '<td>' . $row["login"] . '</td>';
            echo '<td>' . $row["senha"] . '</td>';
            echo '<td>' . $row["cep"] . '</td>';
            echo '<td>' . $row["localidade"] . '</td>';
            echo '<td>' . $row["uf"] . '</td>';
            echo '<td>
                    <a href="#"><img onclick="excluir('.$row["id"].')" style="width:30px; height:30px;" src="https://cdn-icons-png.flaticon.com/128/2602/2602735.png"></a>
                    <a href="#" onclick="abrirDivFlutuante(' . $row["id"] . ', \'' . $row["nome"] . '\', \'' . $row["login"] . '\', \'' . $row["senha"] . '\', \'' . $row["cep"] . '\')">
                        <img style="width:30px; height:30px;" src="https://cdn-icons-png.flaticon.com/128/9268/9268098.png">
                    </a>
                    
                </td>';
            echo '</tr>';
        }
    
        echo '</table>';
        echo '</div>';
    
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    ?>
</div>

</div>

<!DOCTYPE html>
<html>
<head>
    <title>Gerenciamento de Usuários</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        /* Estilos para a div flutuante */
        .modal-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<div class="container">
</div>
<div class="modal-container" id="divFlutuante">
    <div class="modal-content">
        <!-- Formulário de edição -->
        <form id="editarForm">
            <input type="hidden" id="editUserId" name="editUserId">
            <div class="form-group">
                <label for="editNome">Nome:</label>
                <input type="text" class="form-control" id="editNome" name="editNome">
            </div>
            <div class="form-group">
                <label for="editUsuario">Usuário:</label>
                <input type="text" class="form-control" id="editUsuario" name="editUsuario">
            </div>
            <div class="form-group">
                <label for="editSenha">Senha:</label>
                <input type="password" class="form-control" id="editSenha" name="editSenha">
            </div>
            <div class="form-group">
                <label for="editCep">Cep:</label>
                <input type="text" class="form-control" id="editCep" name="editCep">
            </div>
            <button type="button" onclick="salvarEdicao()">Salvar</button>
            <button type="button" onclick="fecharDivFlutuante()">Fechar</button>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function abrirDivFlutuante(id, nome, usuario, senha, cep) {
        $('#editUserId').val(id);
        $('#editNome').val(nome);
        $('#editUsuario').val(usuario);
        $('#editSenha').val(senha);
        $('#editCep').val(cep);
        $('#divFlutuante').show();
    }

    // Função para fechar a div flutuante
    function fecharDivFlutuante() {
        $('#divFlutuante').hide();
    }

    // Função para salvar a edição
     function salvarEdicao() {
        var userId = $('#editUserId').val();
        var novoNome = $('#editNome').val();
        var novoUsuario = $('#editUsuario').val();
        var novaSenha = $('#editSenha').val();
        var cep = $('#editCep').val();

        // Dados a serem enviados para o servidor
        var dados = {
            'id': userId,
            'nome': novoNome,
            'usuario': novoUsuario,
            'senha': novaSenha,
            'cep': cep
        };

        // Realiza uma solicitação AJAX para atualizar os dados no servidor
        $.ajax({
            type: 'POST',
            url: 'atualizar_usuario.php', // Substitua com o arquivo que irá processar a atualização
            data: dados,
            success: function(response) {
                // Verifica a resposta do servidor e atualiza a tabela de usuários, se necessário
                if (response === 'success') {
                    // Atualiza a tabela ou realiza qualquer outra ação necessária
                    alert('Edição salva com sucesso!');
                    fecharDivFlutuante();
                } else {
                    alert('Erro ao salvar a edição. Tente novamente.');
                }
            },
            error: function(xhr, status, error) {
                alert('Erro na solicitação AJAX: ' + error);
            }
        });
    }
</script>
<p>Voltar:</p><a href="./inicio.html"><img style="width:30px; height:30px;" src="https://cdn-icons-png.flaticon.com/128/3236/3236910.png"></a>
</body>
</html>

<script>
    function excluir(userId) {
        // Aqui você pode usar o ID do usuário para a lógica que deseja implementar

        // Exemplo: faça uma requisição AJAX para excluir o usuário no servidor

        // Dados que você quer enviar
        const data = {
            'id': userId
        };

        // Opções da requisição
        $.ajax({
            url: 'excluir.php', // O arquivo PHP que lida com a exclusão
            type: 'POST',
            data: data,
            success: function(result) {
                // Retorno se a exclusão ocorrer com sucesso
                alert("Excluído com sucesso");
                // Recarrega a página após a exclusão
                location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Retorno se ocorrer algum erro na exclusão
                alert("Erro ao excluir");
            }
        });
    }
</script>
<style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #1c1c1c;
        }
        td{
          color: #ffc107; 
            font-weight: bold;
        }
        th{
          color:white; 
            font-weight: bold;
            font: size 30px;
          }
          p{
          color:white; 
            font-weight: bold;
            font: size 30px;
          }
        a{
          background-color:white;
          border:solid black 2px ;
          }
</style>
</body>
</html>
