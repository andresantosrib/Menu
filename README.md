# Menu
Um menu dinamico e com possibilidades de sub niveis

temos uma hierarquia de namespace criadas com o composer psr-4

Nosso menu foi criado e testado para versão 8.0+ php

alguns testes realizados em localhost mostram alguns erros como por exemplo o nível do link, onde temos diferentes constantes para nosso link absoluto, então nesse caso deveriamos ter ou o link absoluto ou uma configuração diferente onde pudessemos ter o link do projeto local

entao para definir um link absoluto você pode criar uma váriavel ou costante para armazenar o link absoluto do seu projeto, por exemplo:
`private static absoluto = '/local do projeto/';`

ao chamar o link no metodo `addSubMenuToDropModulos()` você pode fazer o seguinte:
`'link'    => self::$absoluto.$link,`
