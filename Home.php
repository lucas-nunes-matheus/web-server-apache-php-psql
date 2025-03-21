<?php include "../inc/dbinfo.inc"; ?>
<html>
<head>
  <meta charset="utf-8">
  <title>Página Inicial - Funcionários</title>
</head>
<body>
  <h1>Página Inicial</h1>

  <!-- Botão para navegar para a página de Produtos -->
  <a href="Produtos.php"><button type="button">Ir para a Página de Produtos</button></a>

  <?php
  // Conectar ao PostgreSQL e selecionar o banco de dados
  $constring = "host=" . DB_SERVER . " dbname=" . DB_DATABASE . " user=" . DB_USERNAME . " password=" . DB_PASSWORD;
  $conexao = pg_connect($constring);

  if (!$conexao) {
      echo "Falha ao conectar com o PostgreSQL: " . pg_last_error();
      exit;
  }

  // Verifica se a tabela FUNCIONARIOS existe; se não, cria
  VerificarTabelaFuncionarios($conexao, DB_DATABASE);

  // Se os campos do formulário estiverem preenchidos, adiciona um funcionário
  $nome = isset($_POST['NOME']) ? htmlentities($_POST['NOME']) : "";
  $endereco = isset($_POST['ENDERECO']) ? htmlentities($_POST['ENDERECO']) : "";

  if (strlen($nome) || strlen($endereco)) {
      AdicionarFuncionario($conexao, $nome, $endereco);
  }
  ?>

  <!-- Formulário para adicionar funcionário -->
  <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
    <table border="0">
      <tr>
        <td>NOME</td>
        <td>ENDEREÇO</td>
      </tr>
      <tr>
        <td><input type="text" name="NOME" maxlength="45" size="30" /></td>
        <td><input type="text" name="ENDERECO" maxlength="90" size="60" /></td>
        <td><input type="submit" value="Adicionar Funcionário" /></td>
      </tr>
    </table>
  </form>

  <!-- Exibe os registros da tabela FUNCIONARIOS -->
  <table border="1" cellpadding="2" cellspacing="2">
    <tr>
      <th>ID</th>
      <th>NOME</th>
      <th>ENDEREÇO</th>
    </tr>
    <?php
    $resultado = pg_query($conexao, "SELECT * FROM FUNCIONARIOS");
    while ($linha = pg_fetch_row($resultado)) {
        echo "<tr>";
        echo "<td>" . $linha[0] . "</td>",
             "<td>" . $linha[1] . "</td>",
             "<td>" . $linha[2] . "</td>";
        echo "</tr>";
    }
    pg_free_result($resultado);
    pg_close($conexao);
    ?>
</body>
</html>

<?php
// Função para adicionar um funcionário à tabela
function AdicionarFuncionario($conexao, $nome, $endereco) {
    $n = pg_escape_string($nome);
    $e = pg_escape_string($endereco);
    $query = "INSERT INTO FUNCIONARIOS (NOME, ENDERECO) VALUES ('$n', '$e');";
    if (!pg_query($conexao, $query)) {
        echo "<p>Erro ao adicionar funcionário: " . pg_last_error($conexao) . "</p>";
    }
}

// Função para verificar se a tabela FUNCIONARIOS existe; se não existir, cria a tabela
function VerificarTabelaFuncionarios($conexao, $dbName) {
    if (!TabelaExiste("FUNCIONARIOS", $conexao, $dbName)) {
        $query = "CREATE TABLE FUNCIONARIOS (
                    ID serial PRIMARY KEY,
                    NOME VARCHAR(45),
                    ENDERECO VARCHAR(90)
                  );";
        if (!pg_query($conexao, $query)) {
            echo "<p>Erro ao criar a tabela FUNCIONARIOS: " . pg_last_error($conexao) . "</p>";
        }
    }
}

// Função para verificar se uma tabela existe
function TabelaExiste($nomeTabela, $conexao, $dbName) {
    $t = strtolower(pg_escape_string($nomeTabela));
    $query = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t';";
    $resultado = pg_query($conexao, $query);
    return (pg_num_rows($resultado) > 0);
}
?>
