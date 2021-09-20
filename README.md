# AluraFlix

## Sobre o projeto
API Restful com o objetivo de permitir ao usuário montar playlists com links para seus vídeos preferidos, separados por categorias.
O projeto faz parte do segundo Alura Challenge proposto pela plataforma Alura.

## Executando no localhost
1. Clonar projeto
2. Copiar .env.example para um arquivo .env e configurar seu banco de dados
```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/db_name?serverVersion=5.6"
```

3. Instalar dependendias 
``` 
composer install 
``` 

4. Rodar migrations
```  
php bin/console doctrine:migrations:migrate 
```

5. Rodar fixtures
```
php bin/console doctrine:fixtures:load
```