<?php include "../inc/dbinfo.inc"; ?>
<html>
<head>
  <meta charset="utf-8">
  <title>Gestão de Produtos</title>
</head>
<body>
  <h1>Gestão de Produtos</h1>

  <!-- Botão para voltar à Página Inicial -->
  <a href="Home.php"><button type="button">Voltar para a Página Inicial</button></a>

  <?php
  // Conectar ao PostgreSQL
  $constring = "host=" . DB_SERVER . " dbname=" . DB_DATABASE . " user=" . DB_USERNAME . " password=" . DB_PASSWORD;
  $conexao = pg_connect($constring);

  if (!$conexao) {
      echo "Falha ao conectar com o PostgreSQL: " . pg_last_error();
      exit;
  }

  // Verifica se a tabela PRODUTOS existe; se não, cria
  VerificarTabelaProdutos($conexao, DB_DATABASE);

  // Se os campos do formulário estiverem preenchidos, adiciona um novo produto
  $nomeProduto = isset($_POST['nome']) ? htmlentities($_POST['nome']) : "";
  $precoProduto = isset($_POST['preco']) ? htmlentities($_POST['preco']) : "";
  $emEstoque = isset($_POST['em_estoque']) ? ($_POST['em_estoque'] == '1' ? true : false) : false;

  if (strlen($nomeProduto) && strlen($precoProduto)) {
      AdicionarProduto($conexao, $nomeProduto, $precoProduto, $emEstoque);
  }
  ?>

  <!-- Formulário para adicionar produto -->
  <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
    <table border="0">
      <tr>
        <td>Nome:</td>
        <td><input type="text" name="nome" maxlength="100" size="30" /></td>
      </tr>
      <tr>
        <td>Preço:</td>
        <td><input type="text" name="preco" maxlength="10" size="10" /></td>
      </tr>
      <tr>
        <td>Em Estoque (1 = Sim, 0 = Não):</td>
        <td><input type="text" name="em_estoque" maxlength="1" size="2" /></td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" value="Adicionar Produto" /></td>
      </tr>
    </table>
  </form>

  <!-- Exibe os registros da tabela PRODUTOS -->
  <table border="1" cellpadding="2" cellspacing="2">
    <tr>
      <th>ID</th>
      <th>Nome</th>
      <th>Preço</th>
      <th>Em Estoque</th>
      <th>Criado Em</th>
    </tr>
    <?php
    $resultado = pg_query($conexao, "SELECT * FROM produtos ORDER BY id ASC");
    while ($linha = pg_fetch_assoc($resultado)) {
        echo "<tr>";
        echo "<td>" . $linha['id'] . "</td>";
        echo "<td>" . htmlspecialchars($linha['name']) . "</td>";
        echo "<td>" . $linha['price'] . "</td>";
        echo "<td>" . ($linha['in_stock'] ? 'Sim' : 'Não') . "</td>";
        echo "<td>" . $linha['created_at'] . "</td>";
        echo "</tr>";
    }
    pg_free_result($resultado);
    pg_close($conexao);
    ?>
</body>
</html>

<?php
// Função para adicionar um produto à tabela
function AdicionarProduto($conexao, $nome, $preco, $emEstoque) {
    $n = pg_escape_string($nome);
    $p = pg_escape_string($preco);
    $e = $emEstoque ? 'TRUE' : 'FALSE';
    $query = "INSERT INTO produtos (name, price, in_stock) VALUES ('$n', '$p', $e);";
    if (!pg_query($conexao, $query)) {
        echo "<p>Erro ao adicionar produto: " . pg_last_error($conexao) . "</p>";
    }
}

// Função para verificar se a tabela PRODUTOS existe; se não, cria a tabela
function VerificarTabelaProdutos($conexao, $dbName) {
    if (!TabelaExiste("produtos", $conexao, $dbName)) {
        $query = "CREATE TABLE produtos (
                    id SERIAL PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    price DECIMAL(10,2) NOT NULL,
                    in_stock BOOLEAN NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                  );";
        if (!pg_query($conexao, $query)) {
            echo "<p>Erro ao criar a tabela produtos: " . pg_last_error($conexao) . "</p>";
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
