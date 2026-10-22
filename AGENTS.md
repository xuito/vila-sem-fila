# Contexto do projeto

- O sistema está em produção.
- A pasta local é somente uma cópia de armazenamento e edição dos arquivos; não é um ambiente de execução.
- O usuário não possui PHP, Composer nem outras tecnologias de execução do projeto instaladas localmente.
- Não presumir execução local de comandos Artisan, Composer, testes PHP ou servidor da aplicação. Não instalar ferramentas sem solicitação do usuário.
- Fazer análises estáticas quando possível e informar quando uma validação depender de um ambiente de execução separado.
- Para obter a pasta `vendor` localmente, copiar ou baixar a pasta do servidor ou de outro ambiente com as dependências já instaladas. Manter `vendor` fora do Git.
- Alterações locais e commits não significam publicação em produção. Não presumir autorização para alterar o servidor.
