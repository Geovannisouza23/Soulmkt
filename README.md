# Teste-PHP-HTML-Soulmkt-
# Instruções de uso

1.Clone este repositório para sua máquina local utilizando o comando git clone <url-do-repositorio>.

2.A aplicação foi configurada para rodar utilizando Docker. Certifique-se de ter o Docker instalado na sua máquina.

3.Navegue até o diretório do projeto e execute o comando docker-compose up --build para iniciar os containers.

4.Após os containers estarem rodando, abra o navegador e acesse a aplicação em http://localhost:80.

5.Carregue um arquivo CSV contendo as colunas nome, codigo e preco.

6.A tabela será gerada com os dados do CSV, ordenados conforme a lógica do sistema.

7.As linhas com preços negativos serão destacadas em vermelho. Para códigos de produtos com números pares, haverá um botão "Copiar" para copiar os dados da linha para a área de transferência.

