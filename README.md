# svetovka.cz-wp

Tento repozitář obsahuje úpravy téma Fukasawa pro potřeby webu Plavu. 


one needs to connect to the container first with:
```bash
docker exec -it [id_of_the_container]  bash
mysql -u root -p
```
and then run this command from the mysql cli:
CREATE DB wordpress;
ALTER USER 'root'@'%' IDENTIFIED WITH mysql_native_password BY 'test_env';

## Postup pro instalaci
1. pro verzi WP 4.9 by mělo fungovat zdědené téma - *fukasawa-child*
2. vyhledávání - je potřeba obsah souboru [vyhledavani.html](vyhledavani.html) zkopírovat v HTML editoru jako obsah stránky, která má zajišťovat vyhledávání
