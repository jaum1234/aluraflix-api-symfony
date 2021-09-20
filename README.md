# AluraFlix

## Sobre o projeto
API Restful com o objetivo de permitir ao usuário montar playlists com links para seus vídeos preferidos, separados por categorias.
O projeto faz parte do segundo Alura Challenge proposto pela plataforma Alura.

## Executando no localhost
1. Clonar projeto
2. Instalar dependendias 
``` 
composer install 
``` 

3. Rodar migrations
```  
php bin/console doctrine:migrations:migrate 
```

4. Rodar fixtures
```
php bin/console doctrine:fixtures:load
```