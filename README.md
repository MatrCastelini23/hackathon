# Descrição do ambiente em Docker:

Docker, através do compose.yml criando as variaveis de ambiente, gerando um container do apache 8.2, um container de node 24-alpine e mais dois utilizando imagens do dockerhub, um para o banco em mysql e outro para o acesso via phpmyadmin.

## Configuração do Apache

- Imagem configurada dentro do Dockerfile na pasta php-web.
- Versão 8.2
- Porta: 80
- Depende da aplicação em Node para se comunicar com o banco

# Configuração do Node

- Imagem configurada dentro do Dockerfile na pasta node-api
- Versão 24-alpine
- Porta 5000
- Se comunica diretamente como o banco e aceita receber requisições HTTP do localhost:80 -> configuração do cors
- Para ver todas as configurações e libs utilizadas no container veja o arquivo .md dentro da pasta node-api

# Configuração do DataBase

- Imagem mysql: 8.0
- Porta 3306

# Configuração do PHPmyAdmin

- Imagem phpmyadmin/phpmyadmin
- Container exclusivo para visualização e operação dentro do banco mysql

# Como utilizar
1. Clone o repositório usando:
 git clone https://github.com/MatrCastelini23/hackathon.git

2. Preparando .env para comunicação com ts