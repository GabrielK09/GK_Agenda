-- Comandos a serem rodados --

Iniciar o BackEnd do Admin e alterar o .env de ambos os fronts: 

- composer update/install - for sanctum; 
- php artisan serve;
- php artisan migrate;
- php artisan config:ca;
- php artisan config:cle;
- php artisan route:ca;

Fronts:
- npm run dev / quasar dev;

- .env do Admin/SGAgenda:

- API_URL=http://localhost:8000/api/v1 
- API_CNPJ=https://open.cnpja.com/office
- API_CEP=viacep.com.br/ws

- A página de agendamento do cliente é a pasta Site/SGAgendaServices, essa sendo separada e que vai apenas fazer as consultas para o Backend do Admin/ApiSGAgenda;

- A pasta Boot possui alguns experimentos de Golang para boot da pata Admin, sem ter que abrir as pastas e digitar seus comandos;

- A pasta Services era a ideia de envio de e-mail em Golang, o qual não teve tempo de ser implementado;
