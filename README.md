# Projeto de Aplicação Web Integrada com Banco de Dados

Este repositório contém o código de uma aplicação web desenvolvida para demonstrar a integração entre um servidor web (Apache + PHP) e um banco de dados PostgreSQL hospedado no Amazon RDS. A aplicação consiste em duas páginas principais:

- **Home.php**: Página inicial para cadastro e listagem de funcionários.
- **Produtos.php**: Página para cadastro e listagem de produtos.

## Funcionalidades

### Página de Funcionários (Home.php)
- **Cadastro de Funcionários**: Permite a inserção de registros com informações de nome e endereço.
- **Listagem de Funcionários**: Exibe os registros existentes na tabela `FUNCIONARIOS`.
- **Criação Automática da Tabela**: Se a tabela `FUNCIONARIOS` não existir, ela é criada automaticamente ao acessar a página.

### Página de Produtos (Produtos.php)
- **Cadastro de Produtos**: Permite a inserção de registros de produtos com os seguintes campos:
  - **ID**: Auto-incrementado.
  - **Nome**: Nome do produto.
  - **Preço**: Valor do produto.
  - **Em Estoque**: Indica se o produto está disponível (booleano).
  - **Criado Em**: Data e hora da criação do registro.
- **Listagem de Produtos**: Exibe os produtos cadastrados, ordenados pelo ID.
- **Criação Automática da Tabela**: Se a tabela `produtos` não existir, ela é criada automaticamente.

## Banco de Dados

- **Tipo**: PostgreSQL
- **Hospedagem**: Amazon RDS
- **Tabelas**:
  - `FUNCIONARIOS`: Armazena dados dos funcionários (ID, NOME, ENDERECO).
  - `produtos`: Armazena dados dos produtos (id, name, price, in_stock, created_at).

As credenciais e o endpoint do banco de dados estão configurados no arquivo `/var/www/inc/dbinfo.inc`.

## Deploy

### Ambiente AWS
- **Instância EC2**: Hospeda o servidor web com Apache e PHP.
- **Instância RDS**: Hospeda o banco de dados PostgreSQL.
- **Configurações**:
  - Apache está configurado para iniciar automaticamente no boot do sistema.
  - Permissões e propriedade do diretório `/var/www` foram ajustadas para que o usuário (neste exemplo, `ubuntu`) possa gerenciar os arquivos.
  - O arquivo de configuração de conexão com o banco de dados (`dbinfo.inc`) está localizado fora da raiz pública, aumentando a segurança.

### Passos de Deploy
1. **Criação e Configuração das Instâncias**:
   - Instância EC2 criada com Ubuntu.
   - Instância RDS configurada com PostgreSQL.
2. **Instalação e Configuração do Servidor Web**:
   - Instalação do Apache e PHP.
   - Configuração do Apache para iniciar automaticamente.
   - Ajuste das permissões do diretório `/var/www` para o usuário `ubuntu` e grupo `www-data`.
3. **Deploy do Código**:
   - Transferência do código do projeto para a instância EC2.
   - Configuração das variáveis de ambiente e do arquivo `dbinfo.inc`.
4. **Teste da Aplicação**:
   - Acesso às páginas `Home.php` e `Produtos.php` via navegador, utilizando o endereço IPv4 público da instância EC2.
5. **Versionamento com Git**:
   - O código está versionado neste repositório e os commits seguem as melhores práticas.

## Demonstração do Deploy

Assista ao vídeo de demonstração do deploy e funcionamento dos serviços na AWS:  
[Link para o Vídeo](https://link-para-o-video)

## Conclusão

Este projeto demonstra a integração entre uma aplicação web (PHP) e um banco de dados PostgreSQL, utilizando serviços AWS (EC2 e RDS). Ele atende aos requisitos de:
- Criação de tabelas e páginas web para cadastro e listagem de registros.
- Versionamento do código no GitHub.
- Deploy e demonstração dos serviços em um ambiente real.

---

*Desenvolvido por: lucas-nunes-matheus*
