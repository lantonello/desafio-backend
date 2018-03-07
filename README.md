# Desafio desenvolvedor backend

Foi proposto um desafio para os candidatos à vaga de desenvolvedor backend NeoAssist, cujos detalhes podem ser vistos 
na íntegra no link (https://github.com/NeoAssist/desafio-backend).

O desafio consiste em classificar os tickets com prioridade Alta ou Normal, assim como desenvolver uma API 
para consulta desses tickets, com recursos de ordenação, filtros e paginação.

A solução aqui apresentada está dividida em 3 sub-projetos:

### 1. Classifier

Responsável pela classificação da prioridade dos tickets, o sub-projeto *classifier* deve ser executado 
através de Command Line Interface (prompt de comando ou terminal):
```
$> php classifier.php
```

O sub-projeto *classifier* utiliza a *Natural Language API* do Google, que possui a funcionalidade de 
analisar o sentimento de uma frase ou sentença, indicando se ela tem caráter positivo, negativo ou neutro.

###### Importante!

*Como a Natural Language API é um serviço passível de cobrança, o arquivo de credenciais não está incluído no projeto.*

### 2. API

O primeiro acesso à API deverá ser a autenticação de um usuário fictício, para obtenção de um *token*, que 
será utilizado nos acessos posteriores. O endpoint **(POST /api/public/auth)** espera os campos *username* e *password* 
e retorna um JSON com a propriedade *api_token*.

Para os demais acessos, a chave retornada acima deve ser inserida no *header* **Authorization** da requisição, 
utilizando o padrão *Bearer Token*.

O endpoint **(GET /api/public/tickets)** aceita os seguintes parâmetros:
###### order
Esse parâmetro recebe o nome da propriedade que se deseja ordenar e a direção da ordenação, separados por uma vírgula. 
Os campos suportados são *DateCreate*, *DateUpdate* e *Priority*.
Ex.: `order=DateCreate,asc`, `order=DateUpdate,desc`, `order=Priority`

###### filter
Quando se deseja filtrar os Tickets pela Data de Criação dos mesmos, deve-se informar o nome da propriedade (*DateCreate*, no caso) 
seguido de dois pontos [:], a data de início do intervalo desejado, no formato `YYYY-MM-DD`, uma vírgula como separador e 
a data de término do intervalo desejado, no formato `YYYY-MM-DD`. Ex.: `filter=DateCreate:2017-12-01,2017-12-31`.

Para o caso onde se deseja filtrar os Tickets pela sua Prioridade, deve-se informar o nome da propriedade (*Priority*, no caso) 
seguido de dois pontos [:] e a prioridade desejada. Os valores suportados são `Normal` e `Alta`.
Ex.: `filter=Priority:Alta`.

###### page
Opcional. A paginação é feita de maneira automática, retornando 10 Tickets por página. Para navegar entre as páginas disponíveis, deve-se informar 
o número da página desejada no parâmetro *page*. Ex.: `page=2`

### 3. Consumer

Trata-se de uma pequena aplicação web para demonstrar alguns dos recursos da API.

## Como rodar

A maneira mais simples de executar a solução seria obter uma cópia desse repositório, 
tanto via clone quanto via pacote zip, colocando-o em uma pasta do servidor web. Ex.: `D:\apache\htdocs\neoassist`.
(*Estou muito familiarizado com o desenvolvimento na plataforma Windows, motivo pelo qual esse exemplo usa um Path do Windows*)

Supondo que o servidor Apache do exemplo acima esteja instalado e funcionando corretamente, 
a URL de autenticação da API seria `http://localhost/neoassist/api/public/auth` e a URL de listagem de tickets 
seria `http://localhost/neoassist/api/public/tickets`.

Para verificar e testar todos os recursos que a API disponibiliza, pode-se utilizar uma ferramenta como o 
[Postman](https://www.getpostman.com/).

Ainda seguindo o exemplo, a URL da aplicação que consome a API seria `http://localhost/neoassist/consumer/public`.

